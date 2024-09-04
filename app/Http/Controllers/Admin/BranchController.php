<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use App\Models\Admin\Branch;
use App\Helpers\Custom;
use DB;

class BranchController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!custom::rights(14, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'branches';

        if (isset($_GET['only_limousine'])) {
            return view('admin/branch/manage-limousine', $data);
        } else {
            return view('admin/branch/manage', $data);
        }
    }

    public function getAll()
    {
        $i = 0;
        $page = new Page();
        $city_id = $_REQUEST['city_id'];
        $rows = array();
        $allBranches = new Branch();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $only_limousine_branches = (isset($_REQUEST['only_limousine_branches']) ? $_REQUEST['only_limousine_branches'] : 0);
        $count_only = true;

        $count = $allBranches->getAll($city_id, $count_only, "", "", "", $only_limousine_branches);
        $count_only = false;
        $branches = $allBranches->getAll($city_id, $count_only, $sort_by, $jtStartIndex, $jtPageSize, $only_limousine_branches);

        foreach ($branches as $branch) {
            $coord_arra = array();
            $rows[$i] = $branch;
            $coordinates = $page->getDeliveryCoordinatesForBranch($branch->id);
            if ($coordinates) {
                foreach ($coordinates as $coordinate) {
                    $coord_arra[] = "(" . $coordinate->coordinates . ")";
                }
                $coord = implode('|', $coord_arra);
                $rows[$i]->delivery_coordinates = $coord;
            } else {
                $rows[$i]->delivery_coordinates = '';
            }

            $i++;
        }


        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $count[0]->tcount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveData(Request $request)
    {
        $i = 0;
        $first_coord = '';
        $page = new Page();
        $group = new Branch();
        $delivery_coordinates = array();
        $data = $request->input();
        if (isset($data['delivery_coordinates']) && !empty($data['delivery_coordinates'])) {
            $delivery_coordinates = $data['delivery_coordinates'];
            $delivery_coordinates = str_replace(array('(', ')', " "), "", $delivery_coordinates);
            $delivery_coordinates = explode('|', $delivery_coordinates);
            // $delivery_coordinates[] = $delivery_coordinates[0]; // to match the coordinates polygon
        }

        unset($data['delivery_coordinates']);
        // kashif work change null values to empty string
        $data = custom::isNullToEmpty($data);

        $id = $group->saveData($data);
        if ($id > 0) {

            // saving branch coordinates for delivery
            if (isset($delivery_coordinates) && !empty($delivery_coordinates)) {
                $delivery_coordinates = array_unique($delivery_coordinates);
                foreach ($delivery_coordinates as $delivery_coordinate) {
                    if ($i == 0) {
                        $first_coord = $delivery_coordinate;
                    }
                    $branch_coverage_points['branch_id'] = $id;
                    $branch_coverage_points['coordinates'] = $delivery_coordinate;
                    $page->saveData('branch_coverage_points', $branch_coverage_points);
                    $i++;
                }
                if ($first_coord != '') {
                    $branch_coverage_points['branch_id'] = $id;
                    $branch_coverage_points['coordinates'] = $first_coord;
                    $page->saveData('branch_coverage_points', $branch_coverage_points);
                }
            }

            // saveAvailability
            $models = $group->getAllModels();

            $data2 = array();
            foreach ($models as $model) {
                // kashif work --> this was old buggy code i have change as below
//          $data['branch_id'] = $id;
//          $data['car_model_id'] = $model->id;
//          $group->saveAvailability($data);

                $data2['branch_id'] = $id;
                $data2['car_model_id'] = $model->id;
                $group->saveAvailability($data2);

            }
            custom::log('Branches', 'add');

            // Saving Branch Schedule $id
            $days = array('Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday');
            foreach ($days as $day) {
                $data3['branch_id'] = $id;
                $data3['day'] = $day;
                $data3['opening_time'] = '08:00:00';
                $data3['closing_time'] = '11:00:00';
                $data3['sec_shift_opening_time'] = '00:00:00';
                $data3['sec_shift_closing_time'] = '00:00:00';
                $data3['sec_shift'] = 'no';
                $data3['closed_day'] = 'No';
                $group->saveSchedule($data3);
                $group->saveScheduleDateRange($data3);
            }
        }
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $i = 0;
        $first_coord = '';
        $page = new Page();
        $group = new Branch();
        $id = $request->input('id');
        $data = $request->input();

        // removing old coordinates and saving new ones for delivery branch coordinates
        if (isset($data['delivery_coordinates']) && !empty($data['delivery_coordinates'])) {
            $delivery_coordinates = $data['delivery_coordinates'];
            $delivery_coordinates = str_replace(array('(', ')', " "), "", $delivery_coordinates);
            $delivery_coordinates = explode('|', $delivery_coordinates);

            $page->deleteData('branch_coverage_points', array('branch_id' => $id));
            $delivery_coordinates = array_unique($delivery_coordinates);
            foreach ($delivery_coordinates as $delivery_coordinate) {
                if ($i == 0) {
                    $first_coord = $delivery_coordinate;
                }
                $branch_coverage_points['branch_id'] = $id;
                $branch_coverage_points['coordinates'] = $delivery_coordinate;
                $page->saveData('branch_coverage_points', $branch_coverage_points);
                $i++;
            }
            if ($first_coord != '') {
                $branch_coverage_points['branch_id'] = $id;
                $branch_coverage_points['coordinates'] = $first_coord;
                $page->saveData('branch_coverage_points', $branch_coverage_points);
            }
        }

        unset($data['delivery_coordinates']);
        $coord_arra = array();
        $coordinates = $page->getDeliveryCoordinatesForBranch($id);
        if ($coordinates) {
            foreach ($coordinates as $coordinate) {
                $coord_arra[] = "(" . $coordinate->coordinates . ")";
            }
            $coord = implode('|', $coord_arra);
        } else {
            $coord = "";
        }

        $i++;
        $data = custom::isNullToEmpty($data);
        unset($data['id']);
        $id = $group->updateData($data, $id);
        custom::log('Branches', 'update');
        $responseData = $group->find($id);
        $responseData['delivery_coordinates'] = $coord;
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $group = new Branch();
        $rows = $group->checkIfBranchInUse($id);
        $rows1 = $group->checkIfBranchInUseInPricing($id);
        $rows2 = $group->checkIfBranchInUseInPromotions($id);
        if (count($rows) > 0 || count($rows1) > 0 || count($rows2) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
        } else {
            $group->deleteData($id);
            custom::log('Branches', 'delete');
            $jTableResult['Result'] = "OK";
        }
        print json_encode($jTableResult);

    }

    public function getBranchesForCity(Request $request)
    {
        $html = '';
        $data = array();
        $city_id = $request->input('city_id');
        $allBranches = new Branch();
        $branches = $allBranches->getAll($city_id);
        foreach ($branches as $branch) {
            $html .= $branch->id . '|' . $branch->eng_title . ',';
        }
        $html = rtrim($html, ',');
        $data['dropdown_options'] = $html;
        echo json_encode($data);
        exit();
    }

    public function getAllSchedule()
    {
        $branch_id = $_REQUEST['branch_id'];
        $rows = array();
        $allBranches = new Branch();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];

        $branches = $allBranches->getAllSchedule($branch_id);

        foreach ($branches as $branch) {
            $rows[] = $branch;
        }

        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = count($branches);
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function getAllScheduleDateRange()
    {
        $branch_id = $_REQUEST['branch_id'];
        $rows = array();
        $allBranches = new Branch();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];

        $branches = $allBranches->getAllScheduleDateRange($branch_id);

        foreach ($branches as $branch) {
            $rows[] = $branch;
        }

        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = count($branches);
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function updateSchedule(Request $request)
    {
        $sec_shift = 'no';
        $group = new Branch();
        $id = $request->input('id');
        if ($request->input('sec_shift') && $request->input('sec_shift') != '') {
            $sec_shift = $request->input('sec_shift');
        }
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        unset($data['id']);
        $data['sec_shift'] = $sec_shift;
        $id = $group->updateSchedule($data, $id);
        custom::log('Branches', 'update');
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateScheduleDateRange(Request $request)
    {
        $sec_shift = 'no';
        $third_shift = 'no';
        $group = new Branch();
        $id = $request->input('id');
        if ($request->input('sec_shift') && $request->input('sec_shift') != '') {
            $sec_shift = $request->input('sec_shift');
        }
        if ($request->input('third_shift') && $request->input('third_shift') != '') {
            $third_shift = $request->input('third_shift');
        }
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        unset($data['id']);
        $data['sec_shift'] = $sec_shift;
        $data['third_shift'] = $third_shift;
        $id = $group->updateScheduleDateRange($data, $id);
        custom::log('Branches', 'update');
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function bulkOptionsBranches()
    {
        if (!custom::rights(18, 'view')) {
            return redirect('admin/dashboard');
        }

        $page = new Page();
        $data['regions'] = $page->getAll('region');
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'branches';
        return view('admin/branch/bulk_options', $data);
    }

    public function saveBulk(Request $request)
    {

        custom::log('Branches Schedule', 'add');
        $branchObj = new Branch();
        $page = new Page();
        $branchesArr = array();
        $days = array('Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday');

        $region_id = $request->input('region_id');
        $city_id = $request->input('city_id');
        $branch_id = $branches = $request->input('branch_id');
        $first_shift_opening_time = $request->input('first_shift_opening_time');
        $first_shift_closing_time = $request->input('first_shift_closing_time');
        $second_shift_opening_time = $request->input('second_shift_opening_time');
        $second_shift_closing_time = $request->input('second_shift_closing_time');
        $off_days = $request->input('off_days');


        // getting all branches
        if ($region_id == '' && $city_id == '' && $branch_id == '') {
            $allBranches = $page->getAll('branch');
            foreach ($allBranches as $allBranch) {
                $branchesArr[] = $allBranch->id;
            }
        } elseif ($region_id != '' && $city_id == '' && $branch_id == '') {
            $branchesByRegion = $branchObj->getBranchesByRegion($region_id);
            foreach ($branchesByRegion as $item) {
                $branchesArr[] = $item->id;
            }
        } elseif ($region_id != '' && $city_id != '' && $branch_id == '') {
            $branchesByCity = $page->getMultipleRows('branch', array('city_id' => $city_id));
            foreach ($branchesByCity as $item) {
                $branchesArr[] = $item->id;
            }
        } elseif ($region_id != '' && $city_id != '' && $branch_id != '') {
            $branchesArr = $branches;
        }


        foreach ($branchesArr as $branch_id) {

            foreach ($days as $day) {
                $schedule['branch_id'] = $branch_id;
                $schedule['day'] = $day;
                $schedule['opening_time'] = $first_shift_opening_time;
                $schedule['closing_time'] = $first_shift_closing_time;
                if ($second_shift_opening_time != '') {
                    $schedule['sec_shift_opening_time'] = $second_shift_opening_time;
                }
                if ($second_shift_closing_time != '') {
                    $schedule['sec_shift_closing_time'] = $second_shift_closing_time;
                }

                if ($second_shift_opening_time != '' || $second_shift_closing_time != '') {
                    $schedule['sec_shift'] = 'yes';
                } else {
                    $schedule['sec_shift'] = 'no';
                }

                if (in_array($day, $off_days)) {
                    $schedule['closed_day'] = 'Yes';
                } else {
                    $schedule['closed_day'] = 'No';
                }

                $alreadyHasData = $page->deleteData('branch_schedule', array('branch_id' => $branch_id, 'day' => $day));
//        if ($alreadyHasData)
//        {
//          unset($schedule['branch_id']);
//          unset($schedule['day']);
//          unset($schedule['closed_day']);
//          $page->updateData('branch_schedule', $schedule, array('branch_id' => $branch_id, 'day' => $day));
//        }else{
                $branchObj->saveSchedule($schedule);
                //}
            }
        }

        $response['message'] = 'Data saved successfully.';
        echo json_encode($response);
        exit;

    }


    public function saveBulkBranchActive(Request $request)
    {

        custom::log('Branches Schedule', 'add');
        $branchObj = new Branch();
        $page = new Page();
        $branchesArr = array();

        $region_id = $request->input('region_id');
        $city_id = $request->input('city_id');
        $branch_id = $branches = $request->input('branch_id');
        $status = $request->input('status');


        // getting all branches
        if ($region_id == '' && $city_id == '' && $branch_id == '') {
            $allBranches = $page->getAll('branch');
            foreach ($allBranches as $allBranch) {
                $branchesArr[] = $allBranch->id;
            }
        } elseif ($region_id != '' && $city_id == '' && $branch_id == '') {
            $branchesByRegion = $branchObj->getBranchesByRegion($region_id);
            foreach ($branchesByRegion as $item) {
                $branchesArr[] = $item->id;
            }
        } elseif ($region_id != '' && $city_id != '' && $branch_id == '') {
            $branchesByCity = $page->getMultipleRows('branch', array('city_id' => $city_id));
            foreach ($branchesByCity as $item) {
                $branchesArr[] = $item->id;
            }
        } elseif ($region_id != '' && $city_id != '' && $branch_id != '') {
            $branchesArr = $branches;
        }


        foreach ($branchesArr as $branch_id) {
            if (isset($status) && $status == 'active') {
                $data['active_status'] = '1';
            } else {
                $data['active_status'] = '0';
            }

            // updating branch active status
            $page->updateData('branch', $data, array('id' => $branch_id));

        }

        $response['message'] = 'Data saved successfully.';
        echo json_encode($response);
        exit;

    }

    public function getBranchDeliveryCoordinates()
    {
        $page = new Page();
        $coord_arra = array();
        $branch_id = $_REQUEST['branch_id'];
        $coordinates = $page->getDeliveryCoordinatesForBranch($branch_id);
        if ($coordinates) {
            foreach ($coordinates as $coordinate) {
                $coord_arra[] = $coordinate->coordinates;
            }
            $coord_str = implode('|', $coord_arra);
            $status = true;
        } else {
            $coord_str = '';
            $status = false;
        }

        $responseArray['status'] = $status;
        $responseArray['coordinates'] = $coord_str;
        echo json_encode($responseArray);
        exit();
    }

    public function deactivate_general_timing_for_delivery_branches() {
        DB::table('setting_site_settings')->where('id', 1)->update(['general_timings_for_delivery_branches' => 0]);
        return redirect('admin/branch');
    }

    public function activate_general_timing_for_delivery_branches() {
        DB::table('setting_site_settings')->where('id', 1)->update(['general_timings_for_delivery_branches' => 1]);
        return redirect('admin/branch');
    }

    public function get_general_timing_for_delivery_branches(Request $request) {
        $page = new Page();
        $branch_general_timings_for_delivery_branches = $page->getAll('branch_general_timings_for_delivery_branches');

        $rows = array();
        $i = 0;
        foreach ($branch_general_timings_for_delivery_branches as $branch_general_timings_for_delivery_branch) {
            $rows[$i] = $branch_general_timings_for_delivery_branch;
            $i++;
        }

        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = count($rows);
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function update_general_timing_for_delivery_branches(Request $request) {
        $posted_data = $request->all();
        $page = new Page();
        $id = $posted_data['id'];
        unset($posted_data['id']);

        $posted_data['sec_shift'] = (isset($posted_data['sec_shift']) ? 'yes' : 'no');
        $posted_data['third_shift'] = (isset($posted_data['third_shift']) ? 'yes' : 'no');
        $page->updateData('branch_general_timings_for_delivery_branches', $posted_data, ['id' => $id]);

        $data = $page->getSingleRow('branch_general_timings_for_delivery_branches', ['id' => $id]);

        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $data;
        print json_encode($jTableResult);
    }

}

?>