<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\RentingType;

class RentingTypeController extends Controller
{

    public function getAll()
    {
        $rows = array();
        $carRegions = new RentingType();
        $regions = $carRegions->getAll();
        foreach ($regions as $region) {
            $rows[] = $region;
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
        $group = new RentingType();
        /*$data['eng_title'] = $request->input('eng_title');
        $data['arb_title'] = $request->input('arb_title');*/
        //echo '<pre>';print_r($data);exit();
        $data = $request->input();
        $id = $group->saveData($data);
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $group = new RentingType();
        $id = $request->input('id');
        /*$data['eng_title'] = $request->input('eng_title');
        $data['arb_title'] = $request->input('arb_title');*/
        //echo '<pre>';print_r($data);exit();
        $data = $request->input();
        unset($data['id']);
        $id = $group->updateData($data, $id);
        $responseData = $group->find($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $group = new RentingType();
        $group->deleteData($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    /*public function getAllForDropdown()
    {
        $data = array();
        $html = '';
        $carRegions = new RentingType();
        $regions = $carRegions->getAll();
        foreach ($regions as $region) {
            //$rows[] = $region;
            $html .= $region->id . '|' . $region->type . ',';
        }
        $html = rtrim($html, ',');
        $data['dropdown_options'] = $html;
        echo json_encode($data);
        exit();
    }*/

    public function getAllForDropdown()
    {
        $data = array();
        $rentingType = new RentingType();
        $rt = $rentingType ->getAllOptions();

        $rows = array();
        foreach ($rt as $t) {
            $rows[] = $t;
        }
        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }

   /* public function getAllCitiesById(Request $request){
        $region_id = $request->input('region_id');

        $rentingType = new RentingType();

        $cities = $rentingType->getAllCitiesById($region_id);

        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }*/

    public function getAllCitiesById(Request $request){
        $region_id = $request->input('region_id');

        $rentingType = new RentingType();

        $cities = $rentingType->getAllCitiesById($region_id);

        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        //$data['Options'] = $rows;
        //$data['Result'] = "OK";

        echo json_encode($rows);
        exit();
    }

    public function getAllBranchesById(Request $request){
        $city_id = $request->input('city_id');

        $rentingType = new RentingType();

        $branches = $rentingType->getAllBranchesById($city_id);

        $rows = array();
        foreach ($branches as $branch) {
            $rows[] = $branch;
        }
        //$data['Options'] = $rows;
        //$data['Result'] = "OK";

        echo json_encode($rows);
        exit();
    }

    public function getAllCities(){

        $rentingType = new RentingType();

        $cities = $rentingType->getAllCities();
       // print_r($cities); exit;
        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }

    public function getAllBranches(){

        $is_limousine = (isset($_REQUEST['is_limousine']) ? 1 : 0);

        $rentingType = new RentingType();

        $branches = $rentingType->getAllBranches($is_limousine);
        // print_r($cities); exit;
        $rows = array();
        foreach ($branches as $branch) {
            $rows[] = $branch;
        }
        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }


}

?>