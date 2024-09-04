<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use App\Models\Admin\Pricing;
use App\Helpers\custom;

class PricingController extends Controller
{


    public function index()
    {
        if (!custom::rights(18, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'pricing';
        return view('admin/pricing/manage', $data);
    }


    public function getAllCarModels()
    {
        $rows = array();
        $pricings = new Pricing();

        $sort_by = 'sort_col';
        $sort_as = 'asc';
        if ($_REQUEST['jtSorting'] != '') {
            $sorting = $_REQUEST['jtSorting'];
            $sort = explode(' ', $sorting);
            $sort_by = $sort[0];
            $sort_as = $sort[1];
        }
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $count_only = true;
        $count = $pricings->getAllCarModels($count_only);
        $count_only = false;
        $carModels = $pricings->getAllCarModels($count_only, $jtStartIndex, $jtPageSize, $sort_by, $sort_as);
        foreach ($carModels as $model) {
            $rows[] = $model;
        }

        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $count[0]->count;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function getAllPricesForModel()
    {
        $expired = (isset($_REQUEST['expired']) ? true : false);
        $model_id = $_REQUEST['model_id'];
        $rows = array();
        $carPricing = new Pricing();
        $models = $carPricing->getAllPricesForModel($model_id, $expired);
        foreach ($models as $model) {
            $rows[] = $model;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function getPriceHistory()
    {

        $id = $_REQUEST['id'];
        $rows = array();
        $page = new Pricing();
        $pricing = $page->getPriceHistory($id);

        foreach ($pricing as $price) {
            $rows[] = $price;
        }

        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;

        return response()->json($jTableResult);
    }


    public function getAllExtrasForModel()
    {
        $expired = (isset($_REQUEST['expired']) ? true : false);
        $model_id = $_REQUEST['model_id'];
        $rows = array();
        $carPricing = new Pricing();
        $models = $carPricing->getAllExtrasForModel($model_id, $expired);
        foreach ($models as $model) {
            $rows[] = $model;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    /*import corporate pricing */

    public function importCorporatePricing(Request $request){

        $file = $request->file('import_booking');
        $pricing = new Pricing();
        $page = new Page();
        $response['msg'] = '';
        $excelData = custom::importExcel($file);
        foreach($excelData as $xlData) {
            //echo $xlData['company_code']; print_r($xlData); exit;
            if ($xlData['applies_to'] == "") {
                $xlData['applies_to'] = null;
            }
            $get_by['oracle_reference_number'] = $xlData['car_type'];
            $get_by['year'] = $xlData['car_model'];
            $car_model = $page->getSingleRow('car_model', $get_by);
            /*get car model here and insert in car_price with other data*/

            /*also insert two recored against renting_type in one iteration */
            /*if same country_code then update recored but wuth empty region,city and branch check*/

            if ($car_model) {
                $rates = array(
                    '1' => 'new_daily_rent',
                    '4' => 'new_month_rent'
                );
                foreach ($rates as $key => $rate) {
                    $price = $xlData[$rate];
                    $rent_type = $key;

                    $data = array
                    (
                        'car_model_id' => $car_model->id,
                        'renting_type_id' => $rent_type,
                        'quotation_no' => $xlData['quotation_no'],
                        'quotation_detail_id' => $xlData['quotation_detail_id'],
                        'price' => $price,
                        'applies_from' => $xlData['applies_from'],
                        'applies_to' => $xlData['applies_to'],
                        'region_id' => '',
                        'city_id' => '',
                        'branch_id' => '',
                        'customer_type' => 'Corporate',
                        'company_code' => $xlData['company_code'],
                        'charge_element' => 'Rent'
                    );

                    $check_by['company_code'] = $xlData['company_code'];
                    $check_by['car_model_id'] = $car_model->id;
                    $check_by['renting_type_id'] = $rent_type;
                    $check_by['region_id'] = '0';
                    $check_by['city_id'] = '0';
                    $check_by['branch_id'] = '0';
                    $isExist = $page->getSingleRow('car_price', $check_by);
                    if($isExist) {
                        $data['id'] = $isExist->id;
                    }

                    $result = $pricing->checkIfConflictDataExist($data);
                    unset($data['id']);

                    if (count($result) > 0) {
                        $response['status'] = false;
                        $response['msg'] .= 'This model '.$car_model->id.' already exist with this '.$price.' price. '."\n";
                        continue;

                    } else {

                        if ($isExist) {

                            $update_by['id'] = $isExist->id;

                            $data['created_by'] = auth()->user()->id;
                            $data['created_at'] = date('Y-m-d H:i:s');

                            $page->updateData('car_price', $data, $update_by);
                            $response['status'] = true;
                            $response['msg'] .= 'Record updated '."\n";
                        } else {
                            /*print_r($isExist);*/

                            $data['created_by'] = auth()->user()->id;
                            $data['created_at'] = date('Y-m-d H:i:s');

                            $pricing->saveData($data);
                            $response['status'] = true;
                            $response['msg'] .='Record inserted '."\n";
                        }
                    }
                }

            }else{
                $response['status'] = false;
                $response['msg'] .= 'Car model not exist. '."\n";
                continue;
            }
        }

        $response['status'] = true;
        $response['msg'] = 'Import successfully';
        echo json_encode($response);
        exit;
    }

    public function saveData(Request $request)
    {
        $group = new Pricing();
        $data = $request->input();
        if($data['customer_type'] == 'Individual'){
            $data['company_code'] = '';
        }
        $data = custom::isNullToEmpty($data);
        if ($data['applies_to'] == "") {
            $data['applies_to'] = null;
        }

        if (isset($data['region_id'])) {
            $data['region_id'] = ($data['region_id'] ?: 0);
        } else {
            $data['region_id'] = 0;
        }

        if (isset($data['city_id'])) {
            $data['city_id'] = ($data['city_id'] ?: 0);
        } else {
            $data['city_id'] = 0;
        }

        if (isset($data['branch_id'])) {
            $data['branch_id'] = ($data['branch_id'] ?: 0);
        } else {
            $data['branch_id'] = 0;
        }

        if (isset($data['company_code'])) {
            $data['company_code'] = ($data['company_code'] ?: 0);
        } else {
            $data['company_code'] = 0;
        }

        if (!isset($data['customer_type']) || (isset($data['customer_type']) && $data['customer_type'] == '')) {
            $data['customer_type'] = '';
        }

        $result = $group->checkIfConflictDataExist($data);

        if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {
            if ($request->hasFile('image'))
                $data['image'] = custom::uploadImage($request->file('image'), 'pr');

            $data['created_by'] = auth()->user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $id = $group->saveData($data);
            if ($id > 0) {
                custom::log('Car Pricing and Extra Charges', 'add');
            }
            $responseData = $group->get_single_record($id);
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }
        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $group = new Pricing();
        $page = new Page();
        $id = $request->input('id');
        $data = $request->input();
        if($data['customer_type'] == 'Individual'){
            $data['company_code'] = '';
        }
        unset($data['id']);


        if (isset($data['region_id'])) {
            $data['region_id'] = ($data['region_id'] ?: 0);
        } else {
            $data['region_id'] = 0;
        }

        if (isset($data['city_id'])) {
            $data['city_id'] = ($data['city_id'] ?: 0);
        } else {
            $data['city_id'] = 0;
        }

        if (isset($data['branch_id'])) {
            $data['branch_id'] = ($data['branch_id'] ?: 0);
        } else {
            $data['branch_id'] = 0;
        }

        if (isset($data['company_code'])) {
            $data['company_code'] = ($data['company_code'] ?: 0);
        } else {
            $data['company_code'] = 0;
        }

        if (!isset($data['customer_type']) || (isset($data['customer_type']) && $data['customer_type'] == '')) {
            $data['customer_type'] = '';
        }

        $result = $group->checkIfConflictDataExist($data, $id);
        if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {
            $where['id'] = $id;
            $getObj = $page->getSingleRow('car_price', $where);
            $getAndCopyForHistory = json_decode(json_encode($getObj), true);
            $getAndCopyForHistory['car_price_id'] = $id;
            unset($getAndCopyForHistory['id']);
            $page->saveData('car_price_history', $getAndCopyForHistory);

            $data['created_by'] = auth()->user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');

            $id = $group->updateData($data, $id);
            custom::log('Car Pricing and Extra Charges', 'update');
            $responseData = $group->get_single_record($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }
        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $group = new Pricing();
        $group->deleteData($id);
        custom::log('Car Pricing and Extra Charges', 'delete');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function carsAvailability(Request $request)
    {
        $pricing = new Pricing();
        $html = '';
        $car_model_id = $request->input('model_id');
        $branches = $pricing->getAllBranches();
        $html .= '<div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-overflow-container">
                            <table class="uk-table">
                                <div class="md-card-toolbar">
                                    <div class="md-card-toolbar-actions">
                                    <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light saveAvailabilityForm" id="' . $car_model_id . '" href="javascript:void(0);" title="Save Availability">Save</a>
                                    </div>
                                   
                                    <h2 class="heading_b md-card-toolbar-heading-text"><input type="checkbox" title="Click to select all branches" value="' . $car_model_id . '" class="checkAllcb">   Available Branches Details</h2>
                                </div>
                                <tbody>
                                <form method="post" action="' . custom::baseurl('/') . '/admin/pricing/updateCarsAvailability" id="' . $car_model_id . '" onsubmit="return false;">';
        foreach ($branches as $branch) {
            $checkIfAvailbleForBranch = $pricing->checkIfAvailbleForBranch($car_model_id, $branch->branch_id);

            if (isset($checkIfAvailbleForBranch[0]) && $checkIfAvailbleForBranch[0]->branch_id > 0) {
                $checked = 'checked';
            } else {
                $checked = '';
            }

            if (isset($checkIfAvailbleForBranch[0]) && $checkIfAvailbleForBranch[0]->is_indi_avail > 0) {
                $checked_indi = 'checked';
            } else {
                $checked_indi = '';
            }
            if (isset($checkIfAvailbleForBranch[0]) && $checkIfAvailbleForBranch[0]->is_corp_avail > 0) {
                $checked_corp = 'checked';
            } else {
                $checked_corp = '';
            }
            $html .= '<tr>
            <td width="5%" "><input type="checkbox" class="branch_cb_' . $car_model_id . '" name="availableBranches[]" value="' . $branch->branch_id . '" ' . $checked . '>
            </td>
            <td>
            <strong>' . $branch->branch_title . ', ' . $branch->city_title . ', ' . $branch->region_title . '</strong> '.($branch->is_for_limousine_mode_only == 'yes' ? '<strong><u>(Only For Limousine Branch)</u></strong>' : '').'
            &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <input type="checkbox" class="user_indi_cb_' . $car_model_id . '" name="indiUser[]" value="' . $branch->branch_id . '" ' . $checked_indi . ' id="indi_' . $branch->branch_id . '"> <label for="indi_' . $branch->branch_id . '">Individual</label>
            <input type="checkbox" class="user_corp_cb_' . $car_model_id . '" name="corpUser[]" value="' . $branch->branch_id . '" ' . $checked_corp . ' id="corp_' . $branch->branch_id . '"> <label for="corp_' . $branch->branch_id . '">Corporate</label>
            </td>
            </tr>';
        }
        $html .= '<input type="hidden" name="car_model_id" value="' . $car_model_id . '">';
        $html .= '</form></tbody>
                            </table>
                            </div>
                            </div>
                            </div>';


        //echo $html;exit();
        $response['html'] = $html;
        $recordCount = 1;
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $response;
        print json_encode($jTableResult);

    }


    public function updateCarsAvailability(Request $request)
    {
        $pricing = new Pricing();
        $car_model_id = $request->input('car_model_id');
        $pricing->deleteAvailability($car_model_id);
        if ($request->input('branches')) {
            $branches = $request->input('branches');
            foreach ($branches as $branch) {
                $data['car_model_id'] = $car_model_id;
                $data['branch_id'] = $branch;
                $pricing->saveAvailability($data);
            }
            $data = array();
            if ($request->input('is_indi_avail')) {
                $is_indi_arr = $request->input('is_indi_avail');
                foreach ($is_indi_arr as $branch) {
                    $data['car_model_id'] = $car_model_id;
                    $data['branch_id'] = $branch;
                    $data['is_indi_avail'] = 1;
                    if(in_array($branch,$branches))
                    $pricing->updateAvailability($data);
                }
            }
            $data = array();
            if ($request->input('is_corp_avail')) {
                $is_corp_arr = $request->input('is_corp_avail');
                foreach ($is_corp_arr as $branch) {
                    $data['car_model_id'] = $car_model_id;
                    $data['branch_id'] = $branch;
                    $data['is_corp_avail'] = 1;
                    if(in_array($branch,$branches))
                    $pricing->updateAvailability($data);
                }
            }
        }

        $responseArr['status'] = true;
        $responseArr['message'] = 'Car Availability Updated Successfully';
        echo json_encode($responseArr);
        exit();

    }

    public function bulkOptions()
    {
        if (!custom::rights(18, 'view')) {
            return redirect('admin/dashboard');
        }

        $page = new Page();
        $data['car_categories'] = $page->getAll('car_category');
        $data['renting_types'] = $page->getAll('setting_renting_type');
        $data['regions'] = $page->getAll('region');
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'pricing';
        return view('admin/pricing/bulk_options', $data);
    }

    public function badLog()
    {
        if (!custom::rights(18, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'pricing';
        return view('admin/pricing/bad_logs', $data);
    }

    public function getGroupsForCategory(Request $request)
    {
        $html = '';
        $page = new Page();
        $fetch_by['car_category_id'] = $request->input('category_id');
        $car_groups = $page->getMultipleRows('car_group', $fetch_by);
        //echo '<pre>';print_r($weightages);exit();
        foreach ($car_groups as $car_group) {
            $html .= $car_group->id . '|' . $car_group->eng_title . ',';
        }
        $response['dropdown_options'] = rtrim($html, ',');
        echo json_encode($response);
        exit();
    }


    public function getTypeForGroups(Request $request)
    {
        $html = '';
        $page = new Page();
        $fetch_by['car_group_id'] = $request->input('group_id');
        $car_groups = $page->getMultipleRows('car_type', $fetch_by);
        //echo '<pre>';print_r($weightages);exit();
        foreach ($car_groups as $car_group) {
            $html .= $car_group->id . '|' . $car_group->eng_title . ',';
        }
        $response['dropdown_options'] = rtrim($html, ',');
        echo json_encode($response);
        exit();
    }

    public function getModelsForType(Request $request)
    {
        $html = '';
        $page = new Page();
        $fetch_by['car_type_id'] = $request->input('type_id');
        $car_groups = $page->getMultipleRows('car_model', $fetch_by);
        //echo '<pre>';print_r($weightages);exit();
        foreach ($car_groups as $car_group) {
            $html .= $car_group->id . '|' . $car_group->eng_title . ',';
        }
        $response['dropdown_options'] = rtrim($html, ',');
        echo json_encode($response);
        exit();
    }

    public function getCitiesForRegion(Request $request)
    {
        $html = '';
        $page = new Page();
        $fetch_by['region_id'] = $request->input('region_id');
        $car_groups = $page->getMultipleRows('city', $fetch_by);
        //echo '<pre>';print_r($weightages);exit();
        foreach ($car_groups as $car_group) {
            $html .= $car_group->id . '|' . $car_group->eng_title . ',';
        }
        $response['dropdown_options'] = rtrim($html, ',');
        echo json_encode($response);
        exit();
    }

    public function getBranchesForCity(Request $request)
    {
        $html = '';
        $page = new Page();
        $fetch_by['city_id'] = $request->input('city_id');
        $car_groups = $page->getMultipleRows('branch', $fetch_by);
        //echo '<pre>';print_r($weightages);exit();
        foreach ($car_groups as $car_group) {
            $html .= $car_group->id . '|' . $car_group->eng_title . ',';
        }
        $response['dropdown_options'] = rtrim($html, ',');
        echo json_encode($response);
        exit();
    }

    public function saveBulkPrice(Request $request)
    {
        $skippedCarModels = '';
        $carModels = array();
        $page = new Page();
        $pricing = new Pricing();
        $car_category = $request->input('car_category');
        $car_group = $request->input('car_group');
        $car_type = $request->input('car_type');

        $car_models = $request->input('car_model');

        //$branches = $request->input('branch_id');

        $data['charge_element'] = $request->input('charge_element');
        $data['renting_type_id'] = $request->input('renting_type_id');
        $data['price'] = $request->input('price');
        $data['applies_from'] = date('Y-m-d', strtotime($request->input('applies_from')));
        $data['applies_to'] = date('Y-m-d', strtotime($request->input('applies_to')));
        $data['customer_type'] = $request->input('customer_type');
        $data['region_id'] = ($request->input('region_id') != '' ? $request->input('region_id') : 0);
        $data['city_id'] = ($request->input('city_id') != '' ? $request->input('city_id') : 0);
        $data['branch_id'] = ($request->input('branch_id') != '' ? $request->input('branch_id') : 0);

        if ($car_category == '' && $car_group == '' && $car_type == '' && $car_models == '') {
            $models = $page->getAll('car_model');
            foreach ($models as $model) {
                $carModels[] = $model->id;
            }
        } elseif ($car_category != '' && $car_group == '' && $car_type == '' && $car_models == '') {
            $carModelsByCategory = $page->getCarModelsByCategory($car_category);
            foreach ($carModelsByCategory as $model) {
                $carModels[] = $model->id;
            }
        } elseif ($car_category != '' && $car_group != '' && $car_type == '' && $car_models == '') {
            $carModelsByGroup = $page->getCarModelsByGroup($car_category, $car_group);
            foreach ($carModelsByGroup as $model) {
                $carModels[] = $model->id;
            }
        } elseif ($car_category != '' && $car_group != '' && $car_type != '' && $car_models == '') {
            $carModelsByType = $page->getMultipleRows('car_model', array('car_type' => $car_type));
            foreach ($carModelsByType as $model) {
                $carModels[] = $model->id;
            }
        } elseif ($car_category != '' && $car_group != '' && $car_type != '' && $car_models != '') {
            $carModels = $car_models;
        }


        foreach ($carModels as $carModel) {
            $data['car_model_id'] = $carModel;

            /*if (count($branches) > 0) {
                foreach ($branches as $branch) {
                    $data['branch_id'] = $branch;
                    $result = $pricing->checkIfConflictDataExist($data);
                    if (count($result) > 0) {
                        $skippedCarModels = $data['car_model_id'] . ',';
                    } else {
                        $id = $pricing->saveData($data);
                    }
                }
            } else {*/
                //$data['branch_id'] = ($request->input('branch_id') != '' ? $request->input('branch_id') : 0);
                $result = $pricing->checkIfConflictDataExist($data);
                if (empty($result)) {
                    $skippedCarModels .= $data['car_model_id'] . ',';
                } else {

                    $data['created_by'] = auth()->user()->id;
                    $data['created_at'] = date('Y-m-d H:i:s');

                    $id = $pricing->saveData($data);
                }
            //}
        }

        $skippedCarModelsString = rtrim($skippedCarModels, ',');
        $skippedCarModels = explode(',', $skippedCarModelsString);
        if (count($skippedCarModels) > 0) {
            $responseMsg = 'Data Save Successfully But The Car Models ' . $skippedCarModelsString . ' Had duplicate Data So They Are Skipped.';
        } else {
            $responseMsg = 'Data Save Successfully.';
        }

        $response['message'] = $responseMsg;
        echo json_encode($response);
        exit;

    }


}

?>