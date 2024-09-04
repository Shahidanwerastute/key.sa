<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CarType;
use Illuminate\Http\Request;
use App\Models\Admin\CarModel;
use Illuminate\Support\Facades\Input;
use App\Helpers\Custom;

class CarModelController extends Controller
{

    public function index()
    {
        if (!custom::rights(15, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'models';

        return view('admin/model/manage', $data);
    }

    public function getAllCarModels()
    {
        $type_id = $_REQUEST['type_id'];
        $rows = array();
        $carModels = new CarModel();

        $sort_by = 'id';
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

        $count = $carModels->getAllCarModels($type_id, $count_only);
        $count_only = false;

        $models = $carModels->getAllCarModels($type_id, $count_only, $jtStartIndex, $jtPageSize, $sort_by, $sort_as);
        foreach ($models as $model) {
            $rows[] = $model;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $count;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveCarModel(Request $request)
    {
        $group = new CarModel();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);

        $id = $group->saveData($data);
        if ($id > 0) {
            // saveAvailability
            $branches = $group->getAllBranches();

            $data2 = array();
            foreach ($branches as $branch) {
                $data2['branch_id'] = $branch->id;
                $data2['car_model_id'] = $id;
                $group->saveAvailability($data2);

            }
            custom::log('Car Models', 'add');
        }
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateCarModel(Request $request)
    {
        $group = new CarModel();
        $id = $request->input('id');
        $data = $request->input();

        if(!isset($data['is_special_car']))
        {
            $data['is_special_car'] = "no";
        }

        $data = custom::isNullToEmpty($data);
        unset($data['id']);
        $id = $group->updateData($data, $id);
        custom::log('Car Models', 'update');
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteCarModel(Request $request)
    {
        $id = $request->input('id');
        $group = new CarModel();
        $rows = $group->checkIfCarInUse($id);
        if (count($rows) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
        } else {
            $group->deleteData($id);
            custom::log('Car Models', 'delete');
            $jTableResult['Result'] = "OK";
        }
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

    public function getAllForDropdown()
    {
        $data = array();
        $region = new CarModel();
        $rt = $region->getAllCarModelsForDropdown();
        //echo '<pre>';print_r($rt);exit();

        $rows = array();
        // first time set empty value here
        $rows[] = array("DisplayText"=>"","Value"=>"");

        foreach ($rt as $t) {
            $rows[] = $t;
        }

        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }


}

?>