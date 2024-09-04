<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\IndividualCustomer;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helpers\Custom;


class IndividualCustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!custom::rights(10, 'view')) {
            return redirect('admin/dashboard');
        }
        //$customer = new IndividualCustomer();
        //$data['customers'] = $customer->getAll();
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'individual_customers';
        return view('admin/individual_customer/manage', $data);
    }

    public function getCustomerForDataTable(Request $request)
    {
        //echo "check1"; exit();
        $customer = new IndividualCustomer();
        $reqArr = $request->all();

        $count = $customer->getCount($reqArr['search']['value']);
        /*
         if(isset($reqArr['order'][0]['column']))
             $columnNumber = $reqArr['order'][0]['column'];
         else
             $columnNumber = "";

         if(isset($reqArr['order'][0]['dir']))
             $orderBy = $reqArr['order'][0]['dir'];
         else
             $orderBy = "";*/

        $allCustomers = $customer->getAllCustomers($reqArr['start'], $reqArr['length'], $reqArr['search']['value']);
        //$data['data'] = json_decode(json_encode($allCustomers),true);
        $allCustStr = "";
        /*foreach ($allCustomers as $row)
        {
            if($allCustStr!="") $allCustStr.= ",";
            $allCustStr .= '["'.$row->first_name.'",'.'"'.$row->last_name.'",'.'"'.$row->mobile_no.'",'.'"'.$row->email.'",'.'"'.$row->id_type.'",'.'"'.$row->id_no.'",'.'"'.$row->nationality.'",'.'"'.$row->dob.'",'.'"'.$row->id_expiry_date.'",'.'"'.$row->license_no.'",'.'"'.$row->license_expiry_date.'",'.'"'.$row->created_at.'"]';

        }

        $allCustStr = "[".$allCustStr."]";*/

        $allCustStr = json_encode($allCustomers);

        echo '{
              "draw": ' . $reqArr['draw'] . ',
              "recordsTotal": ' . $count . ',
              "recordsFiltered": ' . $count . ',
              "data": ' . $allCustStr . '
            }';


        exit();


        //echo "<pre>";
        //print_r($allCustJson);
        //exit();


        //$data['data'] = json_encode($ObjArr);
        /*foreach ($customers as $customer){
            $data['data'] = $customer;
        }*/

        //echo json_encode($data); exit;
        //return response()->json($data);

    }

    public function getCustomerForJTable(Request $request)
    {
        $rows = array();

        $sort_by = $request->input('jtSorting');
        $jtStartIndex = $request->input('jtStartIndex');
        $jtPageSize = $request->input('jtPageSize');

        $search_keyword = "";

        if ($request->input('search_keyword') && $request->input('search_keyword') != '') {
            $search_keyword = $request->input('search_keyword');
        }
        $ind_customers = new IndividualCustomer();
        $customers = $ind_customers->getAllCustomers("", $jtStartIndex, $jtPageSize, $sort_by, $search_keyword);
        foreach ($customers as $customer) {
            $rows[] = $customer;
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = count($rows);
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function getCustomerDetailsForJTable()
    {
        $rows = array();
        $customer_id = $_REQUEST['customer_id'];
        $ind_customers = new IndividualCustomer();
        $customers = $ind_customers->getAllCustomers($customer_id);
        foreach ($customers as $customer) {
            $rows[] = $customer;
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = count($rows);
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function updateCustomerForJTable(Request $request)
    {
        $ind_customer = new IndividualCustomer();
        $page = new Page();
        $data = $request->input();
        $alreadyExistWithIdNo = false;

        $customer_data = $page->getSingleRow('individual_customer', array('id' => $data['id']));

        if ($customer_data->id_no != $data['id_no'])
        {
            $alreadyExistWithIdNo = $ind_customer->checkIfCustomerAlreadyExistWithIdNo($data['id'], $data['id_no']);
        }

        if ($alreadyExistWithIdNo) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "This record cannot be updated as a user already exist with this ID No.";
        } else {
            $page->updateData('individual_customer', $data, array('id' => $data['id']));
            $customer_updated_data = $page->getSingleRow('individual_customer', array('id' => $data['id']));
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $customer_updated_data;
        }
        print json_encode($jTableResult);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'customers';
        return view('admin/individual_customer/add', $data);
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
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }


    public function getSingleUserInfo()
    {
        $customer = new IndividualCustomer();
        $user_id = $_REQUEST['user_id'];
        $row = $customer->getSingleUserInfo($user_id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = 1;
        $jTableResult['Records'] = $row;
        print json_encode($jTableResult);

    }


    public function exportUsers(Request $request)
    {
        if (!Auth::check())
        {
            exit();
        }
        $userArr = array();
        $users = array();
        $type = "download";
        custom::log('Registered Users', 'export');
        $customers = new IndividualCustomer();

        $records = $customers->exportUsers();
        foreach ($records as $record) {
            $userArr['ID'] = 'REG' . $record->uid;
            $userArr['First Name'] = $record->first_name;
            $userArr['Last Name'] = $record->last_name;
            $userArr['Mobile No.'] = $record->mobile_no;
            $userArr['Email Address'] = $record->email;
            $userArr['Nationality'] = $record->nationality;
            $userArr['Date Of Birth'] = $record->dob;
            $userArr['ID Type'] = $record->id_type;
            $userArr['ID Number'] = $record->id_no;
            $userArr['ID Version'] = $record->id_version;
            $userArr['ID Expiry Date'] = $record->id_expiry_date;
            $userArr['ID Date Type'] = $record->id_date_type;
            $userArr['ID Country'] = $record->id_country;
            $userArr['License ID Type'] = $record->license_id_type;
            $userArr['License Number'] = $record->license_no;
            $userArr['License Expiry Date'] = $record->license_expiry_date;
            $userArr['License Country'] = $record->license_country;
            $userArr['Loyalty Type'] = $record->loyalty_card_type;
            $userArr['Loyalty Points'] = $record->loyalty_points;
            $userArr['Job Title'] = $record->job_title;
            $userArr['Sponsor'] = $record->sponsor;
            $userArr['Street Address'] = $record->street_address;
            $userArr['District Address'] = $record->district_address;
            $userArr['Blacklisted'] = $record->black_listed;
            $userArr['Simah Block'] = $record->simah_block;
            $users[] = $userArr;

        }
        //$users = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($users);exit();

        return custom::export_excel_file_custom($users);
        if (!$fileName) {
            echo $fileName;
            exit;
        } else {
        }
    }

    public function exportCustomers(Request $request)
    {
        ini_set('max_execution_time', 0);
        $userArr = array();
        $users = array();
        $type = "download";
        custom::log('Individual Customers', 'export');
        $customers = new IndividualCustomer();

        $records = $customers->exportCustomers();
        foreach ($records as $record) {
            $userArr['First Name'] = $record->first_name;
            $userArr['Last Name'] = $record->last_name;
            $userArr['Mobile No.'] = $record->mobile_no;
            $userArr['Email Address'] = $record->email;
            $userArr['Nationality'] = $record->nationality;
            $userArr['Date Of Birth'] = $record->dob;
            $userArr['ID Type'] = $record->id_type;
            $userArr['ID Number'] = $record->id_no;
            $userArr['ID Version'] = $record->id_version;
            $userArr['ID Expiry Date'] = $record->id_expiry_date;
            $userArr['ID Date Type'] = $record->id_date_type;
            $userArr['ID Country'] = $record->id_country;
            $userArr['License ID Type'] = $record->license_id_type;
            $userArr['License Number'] = $record->license_no;
            $userArr['License Expiry Date'] = $record->license_expiry_date;
            $userArr['License Country'] = $record->license_country;
            $userArr['Loyalty Type'] = $record->loyalty_card_type;
            $userArr['Loyalty Points'] = $record->loyalty_points;
            $userArr['Job Title'] = $record->job_title;
            $userArr['Sponsor'] = $record->sponsor;
            $userArr['Street Address'] = $record->street_address;
            $userArr['District Address'] = $record->district_address;
            $userArr['Blacklisted'] = $record->black_listed;
            $userArr['Simah Block'] = $record->simah_block;
            $users[] = $userArr;

        }
        //$users = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($users);exit();

        return custom::export_excel_file_custom($users);
        if (!$fileName) {
            echo $fileName;
            exit;
        } else {
        }
    }

}

?>