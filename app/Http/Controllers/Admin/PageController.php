<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Page;
use App\Helpers\Custom;
use Auth;
use App\User;
use Illuminate\Support\Facades\App;
use Session;
use App\Models\Admin\UserRole;

class PageController extends Controller {


    private $page = '';

    public function __construct()
    {
        $this->page = new Page();
    }

    /**
     * Display loyality edit page.
     *
     * @return Response
     */

    public function home(){
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('home', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'home';
        return view('admin/pages/home', $data);
    }

    public function home_slider(){
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'home_slider';
        return view('admin/pages/home_slider', $data);
    }

    public function slider_sorting(){
        $page = new \App\Models\Front\Page();
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['mobile_slider'] = $mobile_slider = (isset($_REQUEST['m']) && $_REQUEST['m'] == 1)?1:0;
        $get_by = array('is_mobile'=>$mobile_slider);
        $records = $page->getMultipleRows('home_slider', $get_by, 'sort', 'ASC');
        $data['sliders'] = $records;
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'home_slider';
        return view('admin/pages/slider_sorting', $data);
    }

    public function sorting(Request $request){
        $page = new \App\Models\Front\Page();
        $data = array();
        if ($request->isMethod('post')){
            $array = $request->arrayorder;
            $count = 1;
            foreach ($array as $id)
            {
                $page->updateData('home_slider', array('sort' => $count), array('id' => $id));
                $count++;
            }
        }
        $data['mobile_slider'] = $mobile_slider = (isset($_REQUEST['m']) && $_REQUEST['m'] == 1)?1:0;
        $get_by = array('is_mobile'=>$mobile_slider);
        $records = $page->getMultipleRows('home_slider', $get_by, 'sort', 'ASC');
        $data['sliders'] = $records;
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'home_slider';
        return view('admin/pages/slider_sorting', $data);
    }

    public function loyalty()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('loyalty_program', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'loyalty';
        return view('admin/pages/loyalty', $data);
    }

    /**
     * Display services edit page.
     *
     * @return Response
     */
    public function services()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('services', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'services';
        return view('admin/pages/services', $data);
    }

    /**
     * Display about us edit page.
     *
     * @return Response
     */
    public function about_us()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('about_us', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'about_us';
        return view('admin/pages/about_us', $data);
    }

    public function change_points()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('change_points', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'change_points';
        return view('admin/pages/change_points', $data);
    }

    /**
     * Display news edit page.
     *
     * @return Response
     */
    public function news()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('news_content', array('id' => '1'));
        $data['content_listing'] = $this->page->getAll('news_listing', 'id', 'DESC');
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'news';
        return view('admin/pages/news', $data);
    }
	
	public function program_awards()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('program_rewards_content', array('id' => '1'));
		
        $data['content_listing'] = $this->page->getAll('programs_rewards_listing', 'id', 'DESC');
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'program_awards';
        return view('admin/pages/program_awards', $data);
    }

    public function program_awards_sorting(){
        $page = new \App\Models\Front\Page();
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content_listing'] = $this->page->getAll('programs_rewards_listing', 'sort', 'ASC');
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'program_awards';
        return view('admin/pages/program_awards_sorting', $data);
    }

    public function program_awards_update_sorting(Request $request){
        $page = new \App\Models\Front\Page();
        $data = array();
        if ($request->isMethod('post')){
            $array = $request->arrayorder;
            $count = 1;
            foreach ($array as $id)
            {
                $page->updateData('programs_rewards_listing', array('sort' => $count), array('id' => $id));
                $count++;
            }
        }
        $data['content_listing'] = $this->page->getAll('programs_rewards_listing', 'sort', 'DESC');
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'program_awards';
        return view('admin/pages/program_awards_sorting', $data);
    }
	
	/**
     * Display faqa edit page.
     *
     * @return Response
     */
    public function faqs()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('faqs_content', array('id' => '1'));
        $data['content_listing'] = $this->page->getAll('faqs_question', 'id', 'DESC');
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'faqs';
        return view('admin/pages/faqs', $data);
    }
	
	
	/**
     * Display Career edit page.
     *
     * @return Response
     */
    public function career()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('career', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'career';
        return view('admin/pages/career', $data);
    }

    public function refunds()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('refunds', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'refunds';
        return view('admin/pages/refunds', $data);
    }

    public function guar_refunds()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('guar_refunds', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'guar_refunds';
        return view('admin/pages/guar_refunds', $data);
    }

    public function location()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('location', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'location';
        return view('admin/pages/location', $data);
    }
	
	/**
     * Display Contact us edit page.
     *
     * @return Response
     */
    public function contactUs()
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('contact_us', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'contact_us';
        return view('admin/pages/contact_us', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */


    public function saveAdmin(Request $request)
    {
        $page = new \App\Models\Front\Page();
        $role = array();
        $branchData = array();
        $branches = array();

        //echo '<pre>';print_r($request->all());exit();

        if ($request->input('all_branches') && $request->input('all_branches') == '1')
        {
            $brnchs = $page->getAll('branch');
            foreach ($brnchs as $brnch)
            {
                $branches[] = $brnch->id;
            }
        }else{
            $branches = $request->input('branches');
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        $role['role_id']= $request->input('role');
        $role['uid']= $user['id'];
        UserRole::Create($role);

        foreach ($branches as $branch)
        {
            $branchData['admin_id'] = $user['id'];
            $branchData['branch_id'] = $branch;
            $page->saveData('admin_branch', $branchData);
        }

        if($user['id'])
        {
            $response['success'] = 'Admin created successfully.';
            $response['error'] = false;
        }
        else
        {
            $response['success'] = false;
            $response['error'] = 'Admin not created. Please try later';
        }
        echo json_encode($response);
        exit;

    }

    public function updateAdmin(Request $request)
    {
        $page = new \App\Models\Front\Page();
        $role = array();
        $branchData = array();
        $branches = array();

        if ($request->input('all_branches') && $request->input('all_branches') == '1')
        {
            $brnchs = $page->getAll('branch');
            foreach ($brnchs as $brnch)
            {
                $branches[] = $brnch->id;
            }
        }else{
            $branches = $request->input('branches');
        }
        $id = $request->input('id');
        $userData['name'] = $request->input('name');
        $userData['email'] = $request->input('email');
        $userData['active_status'] = $request->input('active_status');
        if ($request->input('password') != '')
        {
            $userData['password'] = bcrypt($request->input('password'));
        }
        $userUpdated = $page->updateData('users', $userData, array('id' => $id));
        $role['role_id']= $request->input('role');
        $roleUpdated = $page->updateData('admin_role', $role, array('uid' => $id));

        $page->deleteData('admin_branch', array('admin_id' => $id));

        foreach ($branches as $branch)
        {
            $branchData['admin_id'] = $id;
            $branchData['branch_id'] = $branch;
            $branchSaved = $page->saveData('admin_branch', $branchData);
        }

        if($userUpdated || $roleUpdated || $branchSaved)
        {
            $response['success'] = 'Admin updated successfully.';
            $response['error'] = false;
        }
        else
        {
            $response['success'] = false;
            $response['error'] = 'Admin not updated. Please try later';
        }
        echo json_encode($response);
        exit;

    }

    public function deleteAdmin(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $page = new \App\Models\Front\Page();
        $page->deleteData('admin_role', array('uid' => $admin_id));
        $page->deleteData('admin_branch', array('admin_id' => $admin_id));
        $deleted = $page->deleteData('users', array('id' => $admin_id));

        if($deleted)
        {
            $response['message'] = 'Admin deleted successfully.';
            $response['status'] = true;
        }
        else
        {
            $response['status'] = false;
            $response['message'] = 'Admin not deleted. Please try later';
        }
        echo json_encode($response);
        exit;

    }


    public function update(Request $request)
    {

        $id = $request->input('id');
        $table_name = $request->input('table_name');
        $data = $request->input();
        if($request->file('image'))
        {
            $data['image'] = custom::uploadImage($request->file('image'), $data['type']);
        }
        if($request->file('banner_image'))
        {
            $data['banner_image'] = custom::uploadImage($request->file('banner_image'), $data['type']);
        }
        if($request->file('image_phone'))
        {
            $data['image_phone'] = custom::uploadImage($request->file('image_phone'), $data['type']);
        }
        if($request->file('image1'))
        {
            $data['image1'] = custom::uploadImage($request->file('image1'), $data['type']);
        }
        if($request->file('image2'))
        {
            $data['image2'] = custom::uploadImage($request->file('image2'), $data['type']);
        }
        if($request->file('image3'))
        {
            $data['image3'] = custom::uploadImage($request->file('image3'), $data['type']);
        }
        if($request->file('image4'))
        {
            $data['image4'] = custom::uploadImage($request->file('image4'), $data['type']);
        }
        if($request->file('b1_image'))
        {
            $data['b1_image'] = custom::uploadImage($request->file('b1_image'), $data['type']);
        }
        if($request->file('b2_image'))
        {
            $data['b2_image'] = custom::uploadImage($request->file('b2_image'), $data['type']);
        }
        if($request->file('b3_image'))
        {
            $data['b3_image'] = custom::uploadImage($request->file('b3_image'), $data['type']);
        }
        if($request->file('mobile_eng_image'))
        {
            $data['mobile_eng_image'] = custom::uploadImage($request->file('mobile_eng_image'), $data['type']);
        }
        if($request->file('mobile_arb_image'))
        {
            $data['mobile_arb_image'] = custom::uploadImage($request->file('mobile_arb_image'), $data['type']);
        }
        /*upload new images*/
        if($request->file('eng_big_image'))
        {
            $data['eng_big_image'] = custom::uploadImage($request->file('eng_big_image'), $data['type']);
        }
        if($request->file('arb_big_image'))
        {
            $data['arb_big_image'] = custom::uploadImage($request->file('arb_big_image'), $data['type']);
        }
        if($request->file('company_images'))
        {
            $company_images = $request->file('company_images');
            foreach ($company_images as $company_image) {
                $filenames[] = custom::uploadImage($company_image, $data['type']);
            }
            $data['company_images'] = implode(",", $filenames);
        }
        $data['updated_at']= date('Y-m-d H:i:s');

        if ($data['table_name'] != 'loyalty_program') {
            $data['updated_by']= Auth::id();
        }

        unset($data['id']);
        unset($data['table_name']);
        unset($data['type']);

        $id = $this->page->updateData($table_name,$data, array('id'=>$id));
        if($id)
        {
            $response['success'] = 'Data updated successfully.';
            $response['error'] = false;
        }
        else
        {
            $response['success'] = false;
            $response['error'] = 'Data not updated. Please try later';
        }
        echo json_encode($response);
        exit;

    }

    public function ajaxUploadFile(Request $request)
    {
        if($request->file('file'))
        {
            return custom::uploadImage($request->file('file'));
        }
    }

    public function get_listing(Request $request)
    {
        $rows = array();
        $page = new Page();
        $tbl = $_GET['tbl'];
        $records = $page->getAll($tbl, 'id', 'DESC');
        foreach ($records as $region)
        {
            $rows[] = $region;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);

    }

    public function getHomeSlider(){
        $rows = array();
        $page = new Page();
        /* $sort_by = $_REQUEST['jtSorting'];
         $jtStartIndex = $_REQUEST['jtStartIndex'];
         $jtPageSize = $_REQUEST['jtPageSize'];

         $count_only = true;
         $totalRecordCount = $page->getAll($count_only);
         $count_only = false;
         */

        $get_by = array('is_mobile'=>0);
        $records = $page->getMultipleRows('home_slider', $get_by, 'sort', 'ASC');
        foreach ($records as $slider)
        {
            $rows[] = $slider;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        return response()->json($jTableResult);
    }

    public function saveHomeSlider(Request $request){

        $data['is_active'] = 0;
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        $page = new Page();

        $isValid = $page->checkValidation($data);
        if($isValid) {

            if ($request->input('is_active') != '') {
                $data['is_active'] = 1;
            }

            $fetch_by['id'] = $page->saveData("home_slider", $data);
            $responseData = $page->getSingleRow('home_slider', $fetch_by);
            //echo '<pre>';print_r($responseData);exit();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }else{
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "All fields With stric were required !";
        }
        return response()->json($jTableResult);

    }

    public function updateHomeSlider(Request $request)
    {
        //echo '<pre>';print_r($_FILES);exit();
        $page = new Page();
        $id = $request->input('id');
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        if(!isset($data['image1']) && $data['image1'] == '')
        {
            unset($data['image1']);
        }
        if($request->input('is_active') == '')
        {
            $data['is_active'] = 0;
        }
        unset($data['id']);
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['updated_by']=Auth::id();
        $update_by['id'] = $id;
        $id = $page->updateData('home_slider', $data, $update_by);
        $responseData = $page->getSingleRow('home_slider', $update_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteHomeSlider(Request $request)
    {
        $id = $request->input('id');
        $delete_by['id'] = $id;
        $page = new Page();
        $page->deleteData('home_slider', $delete_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


    public function getMobileSlider(){
        $rows = array();
        $page = new Page();
        /* $sort_by = $_REQUEST['jtSorting'];
         $jtStartIndex = $_REQUEST['jtStartIndex'];
         $jtPageSize = $_REQUEST['jtPageSize'];

         $count_only = true;
         $totalRecordCount = $page->getAll($count_only);
         $count_only = false;
         */
        $get_by = array('is_mobile'=>1);
        $records = $page->getMultipleRows('home_slider', $get_by, 'id', 'DESC');
        foreach ($records as $slider)
        {
            $rows[] = $slider;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        return response()->json($jTableResult);
    }

    public function saveMobileSlider(Request $request){

        $data['is_active'] = 0;
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        $page = new Page();

        $isValid = $page->checkValidation($data);
        if($isValid) {

            if ($request->input('is_active') != '') {
                $data['is_active'] = 1;
            }

            $fetch_by['id'] = $page->saveData("home_slider", $data);
            $responseData = $page->getSingleRow('home_slider', $fetch_by);
            //echo '<pre>';print_r($responseData);exit();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }else{
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "All fields With stric were required !";
        }
        return response()->json($jTableResult);

    }

    public function updateMobileSlider(Request $request)
    {
        //echo '<pre>';print_r($_FILES);exit();
        $page = new Page();
        $id = $request->input('id');
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        if(!isset($data['image1']) && $data['image1'] == '')
        {
            unset($data['image1']);
        }
        if($request->input('is_active') == '')
        {
            $data['is_active'] = 0;
        }
        unset($data['id']);
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['updated_by']=Auth::id();
        $update_by['id'] = $id;
        $id = $page->updateData('home_slider', $data, $update_by);
        $responseData = $page->getSingleRow('home_slider', $update_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteMobileSlider(Request $request)
    {
        $id = $request->input('id');
        $delete_by['id'] = $id;
        $page = new Page();
        $page->deleteData('home_slider', $delete_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }



    // Generic functions for listings in JTables
    public function save_listing(Request $request)
    {
        //echo '<pre>';print_r($request->input());exit();
        //$request->file('banner_image')
        //echo '<pre>';print_r($_FILES);exit();
        $data = $request->input();
        $page = new Page();

        $isValid = $page->checkValidation($data);
        if($isValid) {

            $tbl = $request->input('tbl');

            if ($request->input('active_status') == '') {
                $data['active_status'] = 0;
            }
            unset($data['tbl']);
            $data['updated_by'] = Auth::id();
            $fetch_by['id'] = $page->saveData($tbl, $data);
            $responseData = $page->getSingleRow($tbl, $fetch_by);
            //echo '<pre>';print_r($responseData);exit();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }else{
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "All fields With stric were required !";
        }
        return response()->json($jTableResult);
    }

    public function update_listing(Request $request)
    {
        //echo '<pre>';print_r($_FILES);exit();
        $page = new Page();
        $tbl = $request->input('tbl');
        $id = $request->input('id');
        $data = $request->input();

        if(isset($data['image1']) && $data['image1'] == '')
        {
            $data['image1'] = $data['edit_image1'];
        }
        if($request->input('active_status') == '')
        {
            $data['active_status'] = 0;
        }
        unset($data['edit_image1']);
        unset($data['id']);
        unset($data['tbl']);
		$data['updated_at']=date('Y-m-d H:i:s');
        $data['updated_by']=Auth::id();
        $update_by['id'] = $id;
        $id = $page->updateData($tbl, $data, $update_by);
        $responseData = $page->getSingleRow($tbl, $update_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function delete_listing(Request $request)
    {
        $id = $request->input('id');
        $delete_by['id'] = $id;
        $page = new Page();
        $tbl = $request->input('tbl');
        $page->deleteData($tbl, $delete_by);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function exportExcel()
    {

    }

    public function importExcel(){

        $path = public_path('excel/individual_customer.xls');
        $results = \Excel::load( $path )
            ->formatDates( true, 'Y-m-d' )
            ->get();
        echo "<pre>"; print_r($results);
        exit;
    }

    public function deleteLoyaltyImages(Request $request){
        $page = new \App\Models\Front\Page();
        $id = $request->input("id");
        $type = $request->input("type");
        if($type == 'eng'){
            $data['eng_big_image'] =  '';
        }else{
            $data['arb_big_image'] =  '';
        }

        $delete = $page->updateData('loyalty_program', $data, array('id' => $id));
        if($delete) {
            $response['message'] = 'Image deleted successfully.';
            $response['title'] = 'Success';
        }else{
            $response['message'] = 'Error to delete this.';
            $response['title'] = 'Error';
        }
        echo json_encode($response);
        exit;
    }

    public function getAllForLoyaltyCards()
    {
        $rows = array();
        $page = new \App\Models\Front\Page();
        $records = $page->getAll('loyalty_program_cards_listing');
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

    public function saveDataForLoyaltyCards(Request $request)
    {
        $page = new \App\Models\Front\Page();
        $data = $request->input();
        if($request->file('image'))
        {
            $data['image'] = custom::uploadImage($request->file('image'));
        }
        $id = $page->saveData('loyalty_program_cards_listing', $data);
        if ($id > 0) {
            $responseData = $page->getSingle('loyalty_program_cards_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function updateDataForLoyaltyCards(Request $request)
    {
        $id = $request->input('id');
        $data = $request->input();
        $page = new \App\Models\Front\Page();
        if($request->file('image'))
        {
            $data['image'] = custom::uploadImage($request->file('image'));
        }
        $updated = $page->updateData('loyalty_program_cards_listing', $data, ['id' => $id]);
        if ($updated) {
            $responseData = $page->getSingle('loyalty_program_cards_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteDataForLoyaltyCards(Request $request)
    {
        $id = $request->input('id');
        $page = new \App\Models\Front\Page();
        $page->deleteData('loyalty_program_cards_listing', ['id' => $id]);
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllForLoyaltyRewardPrograms()
    {
        $rows = array();
        $page = new \App\Models\Front\Page();
        $records = $page->getAll('loyalty_program_reward_programs_listing');
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

    public function saveDataForLoyaltyRewardPrograms(Request $request)
    {
        $page = new \App\Models\Front\Page();
        $data = $request->input();
        if($request->file('image'))
        {
            $data['image'] = custom::uploadImage($request->file('image'));
        }
        $id = $page->saveData('loyalty_program_reward_programs_listing', $data);
        if ($id > 0) {
            $responseData = $page->getSingle('loyalty_program_reward_programs_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function updateDataForLoyaltyRewardPrograms(Request $request)
    {
        $id = $request->input('id');
        $data = $request->input();
        $page = new \App\Models\Front\Page();
        if($request->file('image'))
        {
            $data['image'] = custom::uploadImage($request->file('image'));
        }
        $updated = $page->updateData('loyalty_program_reward_programs_listing', $data, ['id' => $id]);
        if ($updated) {
            $responseData = $page->getSingle('loyalty_program_reward_programs_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteDataForLoyaltyRewardPrograms(Request $request)
    {
        $id = $request->input('id');
        $page = new \App\Models\Front\Page();
        $page->deleteData('loyalty_program_reward_programs_listing', ['id' => $id]);
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllForLoyaltyFaqs()
    {
        $rows = array();
        $page = new \App\Models\Front\Page();
        $records = $page->getAll('loyalty_program_faqs_listing');
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

    public function saveDataForLoyaltyFaqs(Request $request)
    {
        $page = new \App\Models\Front\Page();
        $data = $request->input();
        $id = $page->saveData('loyalty_program_faqs_listing', $data);
        if ($id > 0) {
            $responseData = $page->getSingle('loyalty_program_faqs_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function updateDataForLoyaltyFaqs(Request $request)
    {
        $id = $request->input('id');
        $data = $request->input();
        $page = new \App\Models\Front\Page();
        $updated = $page->updateData('loyalty_program_faqs_listing', $data, ['id' => $id]);
        if ($updated) {
            $responseData = $page->getSingle('loyalty_program_faqs_listing', ['id' => $id]);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteDataForLoyaltyFaqs(Request $request)
    {
        $id = $request->input('id');
        $page = new \App\Models\Front\Page();
        $page->deleteData('loyalty_program_faqs_listing', ['id' => $id]);
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function sta(Request $request)
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('sta_page', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'sta';
        return view('admin/pages/sta', $data);

    }

    public function refer_and_earn(Request $request)
    {
        if (!custom::rights(11, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['content'] = $this->page->getSingleRow('refer_and_earn_content', array('id' => '1'));
        $data['main_section'] = 'pages';
        $data['inner_section'] = 'refer_and_earn';
        return view('admin/pages/refer_and_earn', $data);

    }

}

?>