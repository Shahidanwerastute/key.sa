<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Region;
use App\Helpers\Custom;

class RegionController extends Controller {

  public function getAll()
  {
    $rows = array();
    $carRegions = new Region();
    $sort_by = $_REQUEST['jtSorting'];
    $jtStartIndex = $_REQUEST['jtStartIndex'];
    $jtPageSize = $_REQUEST['jtPageSize'];
    $count_only = true;
    $counts = $carRegions->getAll($count_only);
    $count_only = false;
    $regions = $carRegions->getAll($count_only,$sort_by,$jtStartIndex,$jtPageSize);
    foreach ($regions as $region) {
      $rows[] = $region;
    }

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = $counts[0]->tcount;
    $jTableResult['Records'] = $rows;
    print json_encode($jTableResult);
  }

  public function saveData(Request $request)
  {
    $group = new Region();
    $data = $request->input();
    $id = $group->saveData($data);
    if ($id > 0)
    {
      custom::log('Regions', 'add');
    }
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function updateData(Request $request)
  {
    $group = new Region();
    $id = $request->input('id');
    $data = $request->input();
    unset($data['id']);
    $id = $group->updateData($data, $id);
    if ($id > 0)
    {
      custom::log('Regions', 'update');
    }
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function deleteData(Request $request)
  {
      $id = $request->input('id');
      $group = new Region();

      $rows = $group->checkIfRegionInUse($id);
      $rows1 = $group->checkIfRegionInUseInPricing($id);
      $rows2 = $group->checkIfRegionInUseInPromotions($id);
      if (count($rows) > 0 || count($rows1) > 0 || count($rows2) > 0) {
          $jTableResult['Result'] = "ERROR";
          $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
          return response()->json($jTableResult);
      } else {

          $group->deleteData($id);
          custom::log('Regions', 'delete');
          $jTableResult = array();
          $jTableResult['Result'] = "OK";
          print json_encode($jTableResult);

      }
  }

 /* public function getAllForDropdown()
  {
    $rows = array();
    $carRegions = new Region();
    $regions = $carRegions->getAll();
    foreach ($regions as $region) {
      $rows[] = $region;
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $rows;
    print json_encode($jTableResult);
  }*/

  /*public function getAllForDropdown()
  {
    $data = array();
    $html = '';
    $carRegions = new Region();
    $regions = $carRegions->getAll();
    foreach ($regions as $region) {
      //$rows[] = $region;
      $html .= $region->id.'|'.$region->eng_title.',';
    }
    $html = rtrim($html, ',');
    $data['dropdown_options'] = $html;
    echo json_encode($data);
    exit();
  }*/

    public function getAllForDropdown()
    {
        $data = array();
        $region = new Region();
        $rt = $region->getAllRegion();

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