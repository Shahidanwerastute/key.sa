<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\City;
use App\Helpers\Custom;

class CityController extends Controller {

  public function getAll()
  {
    $region_id = $_REQUEST['region_id'];
    $rows = array();
    $allCities = new City();

    $sort_by = $_REQUEST['jtSorting'];
    $jtStartIndex = $_REQUEST['jtStartIndex'];
    $jtPageSize = $_REQUEST['jtPageSize'];
    $count_only = true;

    $count = $allCities->getAll($region_id,$count_only);
    $count_only = false;
      $cities = $allCities->getAll($region_id,$count_only,$sort_by,$jtStartIndex,$jtPageSize);

    foreach ($cities as $city) {
      $rows[] = $city;
    }

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = $count[0]->tcount;
    $jTableResult['Records'] = $rows;
    print json_encode($jTableResult);
  }

  public function saveData(Request $request)
  {
    $group = new City();
    $data = $request->input();
    $id = $group->saveData($data);
    if ($id > 0)
    {
      custom::log('Cities', 'add');
    }
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function updateData(Request $request)
  {
    $group = new City();
    $id = $request->input('id');
    $data = $request->input();
    unset($data['id']);
    $id = $group->updateData($data, $id);
    custom::log('Cities', 'update');
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function deleteData(Request $request)
  {
      $id = $request->input('id');
      $group = new City();
      $rows = $group->checkIfCityInUse($id);
      $rows1 = $group->checkIfCityInUseInPricing($id);
      $rows2 = $group->checkIfCityInUseInPromotions($id);
      if (count($rows) > 0 || count($rows1) > 0 || count($rows2) > 0) {
          $jTableResult['Result'] = "ERROR";
          $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
          return response()->json($jTableResult);
      } else {

          $group->deleteData($id);
          custom::log('Cities', 'delete');
          $jTableResult = array();
          $jTableResult['Result'] = "OK";
          print json_encode($jTableResult);

      }
  }

  /*public function getCitiesForRegion(Request $request)
  {
    $html = '';
    $data = array();
    $region_id = $request->input('region_id');
    $allBranches = new City();
    $cities = $allBranches->getAllCities();
    foreach ($cities as $city) {
      $html .= $city->id.'|'.$city->eng_title.',';
    }
    $html = rtrim($html, ',');
    $data['dropdown_options'] = $html;
    echo json_encode($data);
    exit();
  }*/

    public function getCitiesForRegion(Request $request)
    {
        $formCityId = 0;
        if(null !== $request->input('from_city_id')){
            $formCityId = $request->input('from_city_id');
        }
        $data = array();
        $allBranches = new City();
        $cities = $allBranches->getAllCities($formCityId);

        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }


}

?>