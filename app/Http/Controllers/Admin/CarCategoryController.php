<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\CarCategory;
use App\Helpers\Custom;

class CarCategoryController extends Controller {
  

  public function getAllCarCategories()
  {
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

    $rows = array();
    $carCategories = new CarCategory();
    $count = $carCategories->getAllCarCategories($count_only);
    $count_only = false;
    $categories = $carCategories->getAllCarCategories($count_only,$jtStartIndex,$jtPageSize,$sort_by,$sort_as);
    foreach ($categories as $category) {
      $rows[] = $category;
    }
    $recordCount = count($rows);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = $count;
    $jTableResult['Records'] = $rows;
    print json_encode($jTableResult);
  }

  public function saveCategory(Request $request)
  {
    $category = new CarCategory();
    $data = $request->input();
    $id = $category->saveData($data);
    if ($id > 0)
    {
      custom::log('Car Categories', 'add');
    }
    $responseData = $category->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function updateCategory(Request $request)
  {
    $category = new CarCategory();
    $id = $request->input('id');
    $data = $request->input();
    unset($data['id']);
    $id = $category->updateData($data, $id);
    custom::log('Car Categories', 'update');
    $responseData = $category->find($id);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $responseData;
    print json_encode($jTableResult);

  }

  public function deleteCategory(Request $request)
  {
    $id = $request->input('id');
    $category = new CarCategory();
    $rows = $category->checkIfCategoryInUse($id);
    if (count($rows) > 0) {
      $jTableResult['Result'] = "ERROR";
      $jTableResult['Message'] = "This record cannot be deleted as this is already being used.";
      return response()->json($jTableResult);
    } else {
      $category->deleteData($id);
      custom::log('Car Categories', 'delete');
      $jTableResult = array();
      $jTableResult['Result'] = "OK";
      print json_encode($jTableResult);

    }
  }

  
}

?>