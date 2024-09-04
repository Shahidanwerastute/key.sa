<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CarType;
use App\Helpers\Custom;

class CarTypeController extends Controller {


  public function getAllCarTypes()
  {
    $group_id = (isset($_REQUEST['group_id']) && $_REQUEST['group_id'] > 0 ? $_REQUEST['group_id'] : 0);
    $rows = array();
    $carTypes = new CarType();

    $sort_by = 'id';
    $sort_as = 'asc';
    if ($_REQUEST['jtSorting'] != '')
    {
        $sorting = $_REQUEST['jtSorting'];
        $sort = explode(' ', $sorting);
        $sort_by = $sort[0];
        $sort_as = $sort[1];
    }
    $jtStartIndex = $_REQUEST['jtStartIndex'];
    $jtPageSize = $_REQUEST['jtPageSize'];

    $count_only = true;
    $count = $carTypes->getAllCarTypes($group_id,$count_only);
    $count_only = false;
    $groups = $carTypes->getAllCarTypes($group_id,$count_only,$jtStartIndex,$jtPageSize,$sort_by,$sort_as);

    foreach ($groups as $group) {
      $rows[] = $group;
    }

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = $count;
    $jTableResult['Records'] = $rows;
    print json_encode($jTableResult);
  }

  public function saveCarType(Request $request)
  {
    $group = new CarType();
    $data = $request->input();
    $id = $group->saveData($data);
    if ($id > 0)
    {
      custom::log('Car Types', 'add');
    }
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function updateCarType(Request $request)
  {
    $group = new CarType();
    $id = $request->input('id');
    $data = $request->input();
    unset($data['id']);
    $id = $group->updateData($data, $id);
    custom::log('Car Types', 'update');
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function deleteCarType(Request $request)
  {
      $id = $request->input('id');
      $group = new CarType();

      $rows = $group->checkIfTypeInUse($id);
      if (count($rows) > 0) {
          $jTableResult['Result'] = "ERROR";
          $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
          return response()->json($jTableResult);
      } else {

          $group->deleteData($id);
          custom::log('Car Types', 'delete');
          $jTableResult = array();
          $jTableResult['Result'] = "OK";
          print json_encode($jTableResult);

      }
  }

    public function getAllForDropdown()
    {
        $data = array();
        $region = new CarType();
        $rt = $region->getAllCarTypesForDropdown();
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