<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CarGroup;
use App\Helpers\Custom;

class CarGroupController extends Controller {


  public function getAllCarGroups()
  {
    $category_id = $_REQUEST['category_id'];
    $rows = array();
    $carGroups = new CarGroup();
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

    $count = $carGroups->getAllCarGroups($category_id,$count_only);
    $count_only = false;
    $groups = $carGroups->getAllCarGroups($category_id,$count_only,$jtStartIndex,$jtPageSize,$sort_by,$sort_as);

    foreach ($groups as $group) {
      $rows[] = $group;
    }

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = $count;
    $jTableResult['Records'] = $rows;
    print json_encode($jTableResult);
  }

  public function saveCarGroup(Request $request)
  {
    $group = new CarGroup();
    $data = $request->input();
    $id = $group->saveData($data);
    if ($id > 0)
    {
      custom::log('Car Groups', 'add');
    }
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function updateCarGroup(Request $request)
  {
    $group = new CarGroup();
    $id = $request->input('id');
    $data = $request->input();
    unset($data['id']);
    $id = $group->updateData($data, $id);
    custom::log('Car Groups', 'update');
    $responseData = $group->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function deleteCarGroup(Request $request)
  {
    $id = $request->input('id');
    $group = new CarGroup();
    $rows = $group->checkIfGroupInUse($id);
    if (count($rows) > 0) {
      $jTableResult['Result'] = "ERROR";
      $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
      return response()->json($jTableResult);
    } else {
      $group->deleteData($id);
      custom::log('Car Groups', 'delete');
      $jTableResult = array();
      $jTableResult['Result'] = "OK";
      print json_encode($jTableResult);

    }
  }


}

?>