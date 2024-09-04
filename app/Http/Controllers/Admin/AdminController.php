<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\App;
use App\Helpers\Custom;

class AdminController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    if (!custom::rights(9, 'view'))
    {
      return redirect('admin/dashboard');
    }
    $page = new Page();
    $data['admin_users'] = $page->getAllAdmins();
    $data['main_section'] = 'admin_users';
    $data['inner_section'] = '';

    return view('admin/admin/manage', $data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    if (!custom::rights(9, 'add'))
    {
      return redirect('admin/dashboard');
    }
    $page = new \App\Models\Front\Page();
    $data['branches'] = $page->getBranchesOfCities();
    $data['roles'] = $page->getAll('setting_user_role');
    $data['main_section'] = 'admin_users';
    $data['inner_section'] = '';
    return view('admin/admin/add', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    if (!custom::rights(9, 'edit'))
    {
      return redirect('admin/dashboard');
    }
    $adminBranches = array();
    $page = new \App\Models\Front\Page();
    $data['branches'] = $page->getBranchesOfCities();
    $branches = $page->getMultipleRows('admin_branch', array('admin_id' => $id));
    foreach ($branches as $branch)
    {
      $adminBranches[] = $branch->branch_id;
    }
    //echo '<pre>';print_r($adminBranches);exit();
    $data['adminBranches'] = $adminBranches;
    $data['adminRole'] = $page->getSingle('admin_role', array('uid' => $id));
    $data['admin_details'] = $page->getSingle('users', array('id' => $id));
    $data['roles'] = $page->getAll('setting_user_role');
    $data['main_section'] = 'admin_users';
    $data['inner_section'] = '';
    return view('admin/admin/edit', $data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    
  }
  
}

?>