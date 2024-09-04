<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\DropoffCharges;
use App\Helpers\Custom;

class DropoffChargesController extends Controller {


  public function index()
  {
    if (!custom::rights(16, 'view'))
    {
      return redirect('admin/dashboard');
    }
    $data['main_section'] = 'booking_engine';
    $data['inner_section'] = 'dropoff_charges';
    return view('admin/dropoff_charges/manage', $data);
  }


  public function getAll()
  {
    $region_id = $_REQUEST['region_id'];
    $city_id = $_REQUEST['city_id'];
    $rows = array();
    $carPricing = new DropoffCharges();
    $models = $carPricing->getAllDropoffCharges($region_id, $city_id);
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

  public function saveData(Request $request)
  {
    $group = new DropoffCharges();
    $data = $request->input();
    $data = custom::isNullToEmpty($data);
      if($data['applies_to'] == ""){
          $data['applies_to'] = null;
      }
      $data['bronze'] = (isset($data['bronze']) && $data['bronze'] == 1)?1:0;
      $data['silver'] = (isset($data['silver']) && $data['silver'] == 1)?1:0;
      $data['gold'] = (isset($data['gold']) && $data['gold'] == 1)?1:0;
      $data['platinum'] = (isset($data['platinum']) && $data['platinum'] == 1)?1:0;
    $result = $group->checkIfConflictDataExist($data);
    if (count($result) > 0) {
      $jTableResult['Result'] = "ERROR";
      $jTableResult['Message'] = 'Record already exist with this data and date range.';
    } else {
      $id = $group->saveData($data);
      if ($id > 0)
      {
        custom::log('Drop-off Charges', 'add');
      }
      $responseData = $group->get_single_record($id);
      $jTableResult['Result'] = "OK";
      $jTableResult['Record'] = $responseData;
    }
    print json_encode($jTableResult);

  }

  public function updateData(Request $request)
  {
    $group = new DropoffCharges();
    $id = $request->input('id');
    $data = $request->input();
    $data['bronze'] = (isset($data['bronze']) && $data['bronze'] == 1)?1:0;
    $data['silver'] = (isset($data['silver']) && $data['silver'] == 1)?1:0;
    $data['gold'] = (isset($data['gold']) && $data['gold'] == 1)?1:0;
    $data['platinum'] = (isset($data['platinum']) && $data['platinum'] == 1)?1:0;
    unset($data['id']);
    $result = $group->checkIfConflictDataExist($data);
    if (count($result) > 0) {
      $jTableResult['Result'] = "ERROR";
      $jTableResult['Message'] = 'Record already exist with this data and date range.';
    } else {
      $id = $group->updateData($data, $id);
      custom::log('Drop-off Charges', 'update');
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
    $group = new DropoffCharges();
    $group->deleteData($id);
    custom::log('Drop-off Charges', 'delete');
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    print json_encode($jTableResult);

  }


}

?>