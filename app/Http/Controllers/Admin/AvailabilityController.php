<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Region;
use Illuminate\Http\Request;
use App\Models\Admin\Availability;
use App\Helpers\Custom;
use App\Models\Admin\City;
use App\Models\Admin\CarModel;
use DB;

class AvailabilityController extends Controller
{

    public function index()
    {
        if (!custom::rights(44, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'availability';
        return view('admin/availability/manage', $data);
    }


    public function getAll()
    {
        $rows = array();
        $availability = new Availability();
        $records = $availability->getAll();
        foreach ($records as $record) {
            $rows[] = $record;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveData(Request $request)
    {
        $availability = new Availability();
        $data = $request->input();
        $data['created_at'] = date('Y-m-d H:i:s');

        $isExisting = $availability->checkDateConflict($data);

        if($isExisting){
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record is already existing in this date range';
        }else{
            $id = $availability->saveData($data);
            if ($id > 0) {
                custom::log('Car Availability Setup', 'add');
                $responseData = $availability->getSingle($id);
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['Record'] = $responseData;
            } else {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
            }
        }

        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $availability =  new Availability();
        $id = $request->input('id');
        $data = $request->input();
        $data['updated_at'] = date('Y-m-d H:i:s');

        /*$isExisting = $availability->checkDateConflict($data);

        if($isExisting){
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record is already existing in this date range';
        }else{*/
            $updated = $availability->updateData($data, $id);
            if ($updated) {
                custom::log('Booking Availability Setup', 'update');
                $responseData = $availability->getSingle($id);
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['Record'] = $responseData;
            } else {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
            }
        /*}*/

        print json_encode($jTableResult);

    }

    
    public function updateActiveStatus(Request $request)
    {
        $availability =  new Availability();
        $id = $request->input('id');
        $data['active_status'] = $request->input('active_status');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $updated = $availability->updateData($data, $id);
        if ($updated) {
            custom::log('Booking Availability Setup', 'update');
            $responseData = $availability->getSingle($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $availability =  new Availability();
        $availability->deleteData($id);
        custom::log('Booking Availability Setup', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllModelsByType(Request $request)
    {
        $car_type_id = $request->input('car_type_id');
        $rentingType = new CarModel();
        $cities = $rentingType->getAllModelsByType($car_type_id);
        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        echo json_encode($rows);
        exit();
    }

    public function getAllCities(Request $request)
    {
        $region_id = $_REQUEST['region_id'];
        $rows = array();
        $allCities = new City();

        $car_type_id = $request->input('car_type_id');
        $rentingType = new CarModel();
        $cities = $allCities->getAllCitiesByRegion($region_id);
        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        echo json_encode($rows);
        exit();
    }

    public function export_data() {
        ini_set('max_execution_time', 0);
        $userArr = array();
        $users = array();
        $type = "download";
        custom::log('Booking Availability', 'export');
        $records = DB::table('availability_cars')->get();
        foreach ($records as $record) {
            $userArr['ID'] = $record->id;
            $userArr['Region'] = $record->region;
            $userArr['City'] = $record->city;
            $userArr['Car Type'] = $record->car_type;
            $userArr['Car Model'] = $record->car_model;
            $userArr['Availability'] = $record->availability;
            $userArr['From Date'] = $record->from_date;
            $userArr['To Date'] = ($record->to_date ?: 'N/A');
            $users[] = $userArr;

        }
        //$users = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($users);exit();

        return custom::export_excel_file_custom($users, 'Booking-Availability');
        if (!$fileName) {
            echo $fileName;
            exit;
        } else {
        }
    }


}

?>