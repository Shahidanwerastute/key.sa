<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Settings;
use App\Models\Front\Page;
use Illuminate\Http\Request;
use App\Models\Admin\Booking;
use App\Helpers\custom;
use Excel;
use Auth;
use Session;
use DB;
use Mockery\Exception;


use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Writer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class BookingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        DB::enableQueryLog();
        $bookings = new Booking();
        $countBookings = 0;
        $recs = $bookings->getLatestBookingForEachUserNew(true, "", 0, 0, 0, 'current_bookings', "", false, true);
        //echo '<pre>';print_r($recs);exit();
        /*foreach ($recs as $rec) {
            if ($rec->sync == 'N')
            {
                $countBookings++;
            }
        }*/
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);*/
        if (!custom::rights(1, 'view')) {
            return redirect('admin/dashboard');
        }

        $query = "
        SELECT COUNT(b.id) as b_count 
        FROM booking b
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method bipm on b.id=bipm.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        WHERE b.is_email_and_pdf_sent_to_customer = 0
        AND ( (b.booking_status='Not Picked') or (b.booking_status='Picked') or (b.booking_status='Walk in') )
        AND (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit' or bci.payment_status='paid')";

        $bookings_count = DB::select($query);

        $data['main_section'] = 'bookings';
        $data['inner_section'] = 'bookings';
        $data['null_bookings_count'] = (isset($recs[0]->tcount) ? $recs[0]->tcount : 0);
        $data['bookings_count'] = (isset($bookings_count[0]->b_count) ? $bookings_count[0]->b_count : 0);
        return view('admin/booking/manage', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

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
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

    public function getAllActiveReservations(Request $request)
    {
        $search_keyword_customer = "";
        $search_keyword_booking = "";
        $bookings = new Booking();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $search_type = "";

        //getLatestBookingForEachUser(false, '', 0, 0, Session::get('user_id'), 'history_bookings');

        if ($request->input('search_keyword_customer') && $request->input('search_keyword_customer') != '') {
            $search_keyword_customer = $request->input('search_keyword_customer');
            $search_type = $request->input('search_type');
        }

        if ($request->input('search_keyword_booking') && $request->input('search_keyword_booking') != '') {
            $search_keyword_booking = $request->input('search_keyword_booking');
        }

        if (session()->get('bHistory') != "" && session()->get('bHistory') == 'history') {
            $type_of_bookings_to_fetch = "history_bookings";
        } else if (session()->get('bhl') != "" && session()->get('bhl') == 'human_less') {
            $type_of_bookings_to_fetch = "human_less_bookings";
        } else {
            $type_of_bookings_to_fetch = "current_bookings";
        }

        $count_only = true;
        $totalRecordCount = $bookings->getLatestBookingForEachUserNew($count_only, "", 0, 0, 0, $type_of_bookings_to_fetch, $search_keyword_customer, true, false, $search_keyword_booking, $search_type);
        $count_only = false;
        $recs = $bookings->getLatestBookingForEachUserNew($count_only, $sort_by, $jtStartIndex, $jtPageSize, 0, $type_of_bookings_to_fetch, $search_keyword_customer, true, false, $search_keyword_booking, $search_type);

        $rows = array();
        foreach ($recs as $rec) {
            $rows[] = $rec;
        }
        //$recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $totalRecordCount[0]->tcount;
        $jTableResult['Records'] = $rows;

        print json_encode($jTableResult);

    }

    public function getAllReservationsForUser()
    {
        $bookings = new Booking();
        $rows = array();
        $booking_id = $_REQUEST['booking_id'];
        $user_type = $_REQUEST['user_type'];
        $sort = 'id';
        if ($_REQUEST['jtSorting'] != '') {
            $sort = $_REQUEST['jtSorting'];
        }
        if ($user_type == 'corporate_customer') {
            $user_id = $bookings->getUserIdFromCorporateBookings($booking_id);
            $bookings = $bookings->getBookingsForCorporateUser($user_id, $sort, $booking_id);
        } elseif ($user_type == 'individual_customer') {
            $user_id = $bookings->getUserIdFromIndividualBookings($booking_id);
            $bookings = $bookings->getBookingsForIndividualUser($user_id, $sort, $booking_id);
        } elseif ($user_type == 'guest') {
            $user_id = $bookings->getUserIdFromGuestBookings($booking_id);
            $bookings = $bookings->getBookingsForGuestUser($user_id, $sort, $booking_id);
        }
        //echo '<pre>';print_r($bookings);exit();
        foreach ($bookings as $booking) {
            if ($booking->payment_method == 'Corporate Credit' || $booking->payment_method == 'Cash' || ($booking->payment_method == 'Credit Card' && $booking->status == 'completed') || ($booking->payment_method == 'Sadad' && $booking->s_status == 'completed')) {
                $rows[] = $booking;
            }
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function exportBooking(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $type = "save";
        custom::log('Customer Bookings', 'export');
        $bookingsData = array();
        $userNewDataArr = array();
        $paymentData = array();
        // get data here
        $bookings = new Booking();
        $page = new Page();
        $booking_ids = $request->input('booking_ids');

        $records = $bookings->exportBooking($booking_ids);
        $bookingsData = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($bookingsData);exit();

        $commaSepBIds = "";
        foreach ($bookingsData as $row) {
            if ($commaSepBIds != "") $commaSepBIds .= ",";
            $commaSepBIds .= "'" . $row['BOOKING_ID'] . "'";
        }

        if ($commaSepBIds != '') {
            // For users sheet
            $usersToExport = $bookings->exportUsers($commaSepBIds);
            $usersData = json_decode(json_encode($usersToExport), true);
            foreach ($usersData as $item) {
                $usersDataArr = array();
                if ($item['booking_type'] == 'guest') {

                    //echo 'ICG '.$item['icg_first_name'].' '.$item['icg_last_name'].' '.$item['icg_id_no'].' '.$item['icg_id_date_type'].'<br>';
                    if ($item['icg_dob'] == "0000-00-00") $item['icg_dob'] = "";
                    if ($item['icg_license_expiry_date'] == "0000-00-00") $item['icg_license_expiry_date'] = "";
                    if ($item['icg_id_expiry_date'] == "0000-00-00") $item['icg_id_expiry_date'] = "";

                    if ($item['icg_id_expiry_date'] != "0000-00-00" && $item['icg_id_expiry_date'] != "1970-01-01" && $item['icg_id_date_type'] == "H") {

                        $date = explode('-', $item['icg_id_expiry_date']);
                        $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                        $item['icg_id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
                    }


                    //$usersDataArr['DB_ID'] = $item['icg_db_id'];
                    $usersDataArr['ID_NUMBER'] = $item['icg_id_no'];
                    $usersDataArr['ID_TYPE'] = $item['icg_id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['icg_first_name'];
                    $usersDataArr['LAST_NAME'] = $item['icg_last_name'];
                    $mobile = str_replace(' ', '', $item['icg_mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['icg_email'];
                    $usersDataArr['NATIONALITY'] = $item['icg_nationality'];
                    $usersDataArr['DOB_G'] = $item['icg_dob'];

                    $usersDataArr['ID_EXPIRY_DATE'] = $item['icg_id_expiry_date'];

                    $usersDataArr['ID_DATE_TYPE'] = $item['icg_id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['icg_id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['icg_id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['icg_license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['icg_license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['icg_license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['icg_license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['icg_job_title'];
                    $usersDataArr['SPONSOR'] = $item['icg_sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['icg_district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['icg_street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['icg_id_image']; //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['icg_license_copy']; //new added
                } elseif ($item['booking_type'] == 'individual_customer') {

                    //echo 'IC '.$item['first_name'].' '.$item['last_name'].' '.$item['id_no'].' '.$item['id_date_type'].'<br>';

                    if ($item['dob'] == "0000-00-00") $item['dob'] = "";
                    if ($item['license_expiry_date'] == "0000-00-00") $item['license_expiry_date'] = "";
                    if ($item['id_expiry_date'] == "0000-00-00") $item['id_expiry_date'] = "";

                    if ($item['id_expiry_date'] != "0000-00-00" && $item['id_expiry_date'] != "1970-01-01" && $item['id_date_type'] == "H") {
                        $date = explode('-', $item['id_expiry_date']);
                        $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                        $item['id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
                    }

                    //$usersDataArr['DB_ID'] = $item['db_id'];
                    $usersDataArr['ID_NUMBER'] = $item['id_no'];
                    $usersDataArr['ID_TYPE'] = $item['id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['first_name'];
                    $usersDataArr['LAST_NAME'] = $item['last_name'];
                    $mobile = str_replace(' ', '', $item['mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['email'];
                    $usersDataArr['NATIONALITY'] = $item['nationality'];
                    $usersDataArr['DOB_G'] = $item['dob'];
                    $usersDataArr['ID_EXPIRY_DATE'] = $item['id_expiry_date'];
                    $usersDataArr['ID_DATE_TYPE'] = $item['id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['job_title'];
                    $usersDataArr['SPONSOR'] = $item['sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['id_image'];    //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['license_copy']; //new added
                } elseif ($item['booking_type'] == 'corporate_customer') {

                    //echo 'ICG '.$item['icg_first_name'].' '.$item['icg_last_name'].' '.$item['icg_id_no'].' '.$item['icg_id_date_type'].'<br>';

                    //$usersDataArr['DB_ID'] = $item['cd_db_id'];
                    $usersDataArr['ID_NUMBER'] = $item['cd_id_no'];
                    $usersDataArr['ID_TYPE'] = $item['cd_id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['cd_first_name'];
                    $usersDataArr['LAST_NAME'] = $item['cd_last_name'];
                    $mobile = str_replace(' ', '', $item['cd_mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['cd_email'];
                    $usersDataArr['NATIONALITY'] = $item['cd_nationality'];
                    $usersDataArr['DOB_G'] = $item['cd_dob'];

                    $usersDataArr['ID_EXPIRY_DATE'] = $item['cd_id_expiry_date'];

                    $usersDataArr['ID_DATE_TYPE'] = $item['cd_id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['cd_id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['cd_id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['cd_license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['cd_license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['cd_license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['cd_license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['cd_job_title'];
                    $usersDataArr['SPONSOR'] = $item['cd_sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['cd_district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['cd_street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['cd_id_image']; //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['cd_license_copy']; //new added
                }

                $userNewDataArr[] = $usersDataArr;

            }
        }

        if ($commaSepBIds != '') {
            $payments = $bookings->exportPaymentCollection($commaSepBIds);
            $paymentData = json_decode(json_encode($payments), true);
        }

        if ($commaSepBIds != '') {
            $bids = explode(',', $commaSepBIds);
            $bookings->exportBookingSyncStatus($commaSepBIds);
            //print_r($bids);
            foreach ($bids as $bid) {
                $bid = str_replace("'", "", $bid);
                $booking_details = $page->getSingle('booking', array('reservation_code' => $bid));
                if ($booking_details) {
                    $bookings->exportCancelledBookingSyncStatus($booking_details->id);
                }
            }
        }

        if ($commaSepBIds != "") {
            $fileName = custom::exportExcel($type, $bookingsData, $userNewDataArr, $paymentData, "Bookings", "Individual Customers", "Collections");
            $page = new Page();
            $fileData['exported_by'] = Auth::user()->name;
            $fileData['exported_at'] = date('Y-m-d H:i:s');
            $fileData['filename'] = $fileName;
            $saved_file = $page->saveData('exported_files', $fileData);
            $response['status'] = true;
            $response['msg'] = 'Data exported successfully.';
            echo json_encode($response);
            exit();

        } else {
            $response['status'] = false;
            $response['msg'] = 'Nothing to export.';
            echo json_encode($response);
            exit();
        }
        //$fileNameForAttachment = custom::exportExcel("attachment", $bookingsData, $userNewDataArr, $paymentData, "Bookings", "Individual Customers", "Collections");

        //$site = custom::site_settings();
        /*if ($site->admin_email != '')
        {
            // $fileNameForAttachment contains the complete path of downloaded excel file
            $email['subject'] = 'Excel Exported At Key Rental';
            $email['fromEmail'] = 'no-reply@key.sa';
            $email['fromName'] = "no-reply";
            $email['toEmail'] = 'bilal_ejaz@astutesol.com';
            //$email['toEmail'] = $site->admin_email;
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';

            $email['pdf'] = '';

            $email['attachment'] = $fileNameForAttachment;

            $content['contact_no'] = $site->site_phone;
            $content['lang_base_url'] = custom::baseurl('/');
            $content['name'] = 'Admin';
            $content['msg'] = "\n Booking excel sheet has been exported at ".date('d-m-Y, h:i:s A').". You can find this sheet in the attachment.";
            custom::sendEmail('general', $content, $email, 'eng');
        }*/
        //echo "<pre>"; print_r($userNewDataArr); exit;
        // we can send more than one data arrays
        // if need to create more than one excel sheet
        // send arg type eg download, save. default
        // will be download
        /*if (!$fileName) {
            echo $fileName;
            exit;
        } else {
        }*/
    }

    /*public function exportUsers()
    {
        $bookings = new Booking();
        $records = $bookings->exportUsers();
        $data = json_decode(json_encode($records), true);
        //echo "<pre>"; print_r($data); exit;

        $type = "download";
        $fileName = custom::exportExcel($type, $data);
        if (!$fileName) {
            echo $fileName;
            exit;
        } else {
        }

    }*/

    public function importBooking(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $site_settings = custom::site_settings();
        $bookingStatus = '';
        custom::log('Customer Bookings', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_booking');
        if ($file != '') {
            $bookings = custom::importExcel($file);
            //echo '<pre>';print_r($bookings);exit();
            foreach ($bookings as $booking) {
                if ($booking['booking_status'] == 'A') {
                    $bookingStatus = 'Not Picked';
                } elseif ($booking['booking_status'] == 'O') {
                    $bookingStatus = 'Picked';
                } elseif ($booking['booking_status'] == 'H') {
                    $bookingStatus = 'Completed with Overdue';
                } elseif ($booking['booking_status'] == 'C') {
                    $bookingStatus = 'Completed';
                } elseif ($booking['booking_status'] == 'I') {
                    $bookingStatus = 'Cancelled';
                } elseif ($booking['booking_status'] == 'E') {
                    $bookingStatus = 'Expired';
                } elseif ($booking['booking_status'] == 'V') {
                    $bookingStatus = 'Walk in';
                }

                $data['oracle_reference_number'] = $booking['oracle_reservation_number'];
                $data['booking_status'] = $bookingStatus;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $update_by['reservation_code'] = $booking['booking_id'];
                $updated = $bookingObj->updateData($data, $update_by);

                if ($updated && $booking['booking_status'] == 'C') // if booking status is changed to completed through import
                {
                    if ($site_settings->survey_on_off == 'on') {
                        $this->setSurveyPendingToFill($update_by['reservation_code']);
                    }
                }

            }
            $status = true;
            $msg = 'Excel imported successfully.';
        } else {
            $status = false;
            $msg = 'Please select a file to import.';
        }
        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
        exit();

    }

    public function importCustomerLoyalty(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $bookingStatus = '';
        $loyalty_card_type = '';
        custom::log('Customer Loyalty Information', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_loyalty');
        if ($file != '') {
            $loyalties = custom::importExcel($file);
            //echo '<pre>';print_r($bookings);exit();
            foreach ($loyalties as $loyalty) {
                if ($loyalty['loyalty_type'] == 0) $loyalty_card_type = 'Bronze';
                if ($loyalty['loyalty_type'] == 1) $loyalty_card_type = 'Silver';
                if ($loyalty['loyalty_type'] == 2) $loyalty_card_type = 'Golden';
                if ($loyalty['loyalty_type'] == 3) $loyalty_card_type = 'Platinum';
                $data['loyalty_card_type'] = $loyalty_card_type;
                $data['loyalty_points'] = $loyalty['loyalty_points'];
                $update_by['id_no'] = $loyalty['id_number'];
                $updated = $bookingObj->updateCustomerData($data, $update_by);
            }
            $status = true;
            $msg = 'Excel imported successfully.';
        } else {
            $status = false;
            $msg = 'Please select a file to import.';
        }
        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
        exit();

    }

    /*public function importSimahInfo(Request $request)
    {
        custom::log('Customer Simah Information', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_simah');
        if ($file != '') {
            $loyalties = custom::importExcel($file);
            foreach ($loyalties as $loyalty) {
                $data['simah_block'] = $loyalty['simah_block'];
                $update_by['id_no'] = $loyalty['id_number'];
                $updated = $bookingObj->updateCustomerData($data, $update_by);
            }
            $status = true;
            $msg = 'Excel imported successfully.';
        } else {
            $status = false;
            $msg = 'Please select a file to import.';
        }
        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
        exit();

    }*/

    public function importSimahInfo(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $page = new Page();
        custom::log('Customer Simah Information', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_simah');
        if ($file != '') {
            $loyalties = custom::importExcel($file);
            foreach ($loyalties as $loyalty) {
                $data['simah_block'] = $loyalty['simah_block'];
                $data['id_no'] = $loyalty['id_number'];
                $userInfo = $page->getSingle('individual_customer', array('id_no' => $data['id_no']));
                if ($userInfo) {
                    $bookingObj->updateCustomerData($data, array('id_no' => $data['id_no']));
                } else {
                    $page->saveData('individual_customer', $data);
                }
            }
            $status = true;
            $msg = 'Excel imported successfully.';
        } else {
            $status = false;
            $msg = 'Please select a file to import.';
        }
        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
        exit();

    }

    public function importBlackListInfo(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $page = new Page();
        custom::log('Customer Black List Information', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_black_list');
        if ($file != '') {
            $customers = custom::importExcel($file);
            foreach ($customers as $customer) {
                $userInfo = $page->getSingle('individual_customer', array('id_no' => $customer['id_number']));
                if ($userInfo) {
                    if ($customer['black_list'] == 'Yes' || $customer['black_list'] == 'yes') {
                        $data['black_listed'] = 'Y';
                        $bookingObj->updateCustomerData($data, array('id_no' => $customer['id_number']));
                    } else if ($customer['black_list'] == 'No' || $customer['black_list'] == 'no') {
                        $data['black_listed'] = '';
                        $bookingObj->updateCustomerData($data, array('id_no' => $customer['id_number']));
                    }
                }
            }
            $status = true;
            $msg = 'Excel imported successfully.';
        } else {
            $status = false;
            $msg = 'Please select a file to import.';
        }
        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
        exit();

    }

    /* public function errorHandlerCatchUndefinedIndex($errno, $errstr, $errfile, $errline ) {
         // We are only interested in one kind of error
         if ($errstr=='Undefined index: bar') {
             //We throw an exception that will be catched in the test
             throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
         }
         return false;
     }*/

    public function importCustomers(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $errorLines = '';
        $importError = false;
        //set_error_handler(array(&$this, 'errorHandlerCatchUndefinedIndex'));
        ini_set('max_execution_time', 0);
        custom::log('Customers', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_customer');
        if ($file == '') {
            $response['status'] = false;
            $response['msg'] = 'Please select a file to import.';
            echo json_encode($response);
            exit();
        } else {
            $i = 1;
            $customers = custom::importExcel($file);
            $importableRecordsCount = count($customers);
            //echo '<pre>';print_r($customers);exit();
            foreach ($customers as $customer) {
                try {
                    $data['id_no'] = $customer['id_number'];
                    $data['id_type'] = $customer['id_type'];
                    $data['first_name'] = $customer['first_name'];
                    $data['last_name'] = $customer['last_name'];
                    $data['mobile_no'] = $customer['mobile'];
                    $data['email'] = $customer['email'];
                    $data['nationality'] = $customer['nationality'];
                    $data['dob'] = date("Y-m-d", strtotime($customer['dob_g']));
                    $data['id_expiry_date'] = date("Y-m-d", strtotime($customer['id_expiry_date']));
                    $data['id_date_type'] = $customer['id_date_type'];
                    $data['id_version'] = $customer['id_copy'];
                    $data['id_country'] = $customer['id_country'];
                    $data['license_no'] = $customer['dl_id_number'];
                    $data['license_id_type'] = $customer['dl_id_type'];
                    $data['license_expiry_date'] = date("Y-m-d", strtotime($customer['dl_expiry_date']));
                    $data['license_country'] = $customer['dl_country'];
                    $data['job_title'] = $customer['job_title'];
                    $data['sponsor'] = $customer['sponsor'];
                    $data['district_address'] = $customer['address_district'];
                    $data['street_address'] = $customer['address_street'];
                    if ($customer['loyalty_type'] == 0) $customer['loyalty_type'] = 'Bronze';
                    if ($customer['loyalty_type'] == 1) $customer['loyalty_type'] = 'Silver';
                    if ($customer['loyalty_type'] == 2) $customer['loyalty_type'] = 'Golden';
                    if ($customer['loyalty_type'] == 3) $customer['loyalty_type'] = 'Platinum';
                    $data['loyalty_card_type'] = $customer['loyalty_type'];
                    $data['loyalty_points'] = $customer['loyalty_points'];
                    $data['black_listed'] = ($customer['black_listed'] != '' ? $customer['black_listed'] : '');

                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');

                    //$get_by_email['email'] = $data['email'];
                    //$get_by_id['id_no'] = $data['id_no'];

                    $bookingObj->saveCustomerData($data);
                    $i++;

                } catch (\Exception $e) {
                    $importError = true;
                    $errorLines .= $i . ', ';
                    //restore_error_handler();
                    //$errorMsg = $e->getMessage(); // it gives exact error
                    //$response['status'] = true;
                    //$response['msg'] = $errorMsg;
                    //echo json_encode($response);
                    //exit();
                }

            }

            if ($importError == true) {
                $errorLines = rtrim($errorLines, ',');
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. But the row no ' . $errorLines . ' are having some error. Please fix and retry. Total Records: ' . $importableRecordsCount . ', Imported Records: ' . $importableRecordsCount - count(explode(',', $errorLines));
                echo json_encode($response);
                exit();
            } else {
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. Total Records: ' . $importableRecordsCount . ', Imported Records: ' . $importableRecordsCount;
                echo json_encode($response);
                exit();
            }

        }

    }


    public function importCorporateInvoices(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $errorLines = '';
        $importError = false;
        $c_exist_msg = '';
        //set_error_handler(array(&$this, 'errorHandlerCatchUndefinedIndex'));
        ini_set('max_execution_time', 0);
        custom::log('Corporate Invoices', 'import');
        $pageObj = new Page();
        $bookingObj = new Booking();
        $file = $request->file('import_corp_invoices');
        if ($file == '') {
            $response['status'] = false;
            $response['msg'] = 'Please select a file to import.';
            echo json_encode($response);
            exit();
        } else {
            $i = 1;
            $invoices = custom::importExcel($file);
            // custom::dump($invoices);
            $importableRecordsCount = count($invoices);
            //echo '<pre>';print_r($invoices);exit();

            // removing records from 3rd table
            foreach ($invoices as $invoice) {
                $corporate_invoice_detail = $pageObj->getSingle('corporate_invoices', array('invoice_no' => $invoice['invoice_no']));
                if ($corporate_invoice_detail) {
                    $pageObj->deleteData('corporate_invoices_contract', array('contract_no' => $invoice['contract_no'], 'invoice_id' => $corporate_invoice_detail->id));
                }
            }

            // inserting fresh data now
            foreach ($invoices as $invoice) {
                try {

                    $customer_data['company_code'] = $invoice['customer_code'];
                    $customer_data['inv_customer_name'] = $invoice['customer_name'];
                    $customer_data['inv_customer_address'] = $invoice['customer_address'];
                    $customer_data['inv_customer_vat_id'] = $invoice['customer_vat_id'];
                    $customer_data['inv_customer_po_box'] = $invoice['customer_po_box'];
                    $customer_data['created_at'] = date('Y-m-d H:i:s');
                    $customer_data['updated_at'] = date('Y-m-d H:i:s');

                    $customer_id = $this->saveCompanyData($invoice['customer_code'], $customer_data);

                    if ($customer_id > 0) {
                        $invoiceData['customer_id'] = $customer_id;
                        $invoiceData['invoice_no'] = $invoice['invoice_no'];
                        $invoiceData['invoice_issue_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_issue_date'])));
                        $invoiceData['invoice_deserved_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_deserved_date'])));
                        $invoiceData['created_at'] = date('Y-m-d H:i:s');
                        $invoiceData['updated_at'] = date('Y-m-d H:i:s');
                        $contractData['invoice_id'] = $this->saveInvoiceData($invoice['invoice_no'], $invoiceData);

                        $contractData['contract_no'] = $invoice['contract_no'];
                        $contractData['contract_status'] = $invoice['contract_status'];
                        $contractData['plate_no'] = $invoice['plate_no'];
                        $contractData['car_type'] = $invoice['car_type'];
                        $contractData['car_model'] = $invoice['car_model'];
                        $contractData['rent_price'] = $invoice['rent_price'];
                        $contractData['period'] = $invoice['period'];
                        $contractData['start_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['start_date'])));
                        $contractData['end_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['end_date'])));
                        $contractData['charge_price'] = $invoice['charge_price'];
                        $contractData['charge_discount'] = $invoice['charge_discount'];
                        $contractData['vat_pct'] = $invoice['vat_pct'];
                        $contractData['vat'] = $invoice['vat'];
                        $contractData['bills'] = $invoice['bills'];
                        $contractData['payments'] = $invoice['payments'];
                        $contractData['settlements'] = $invoice['settlements'];
                        $contractData['balance'] = $invoice['balance'];
                        $contractData['renting'] = $invoice['renting'];
                        $contractData['cdw'] = $invoice['cdw'];
                        $contractData['exceed_km'] = $invoice['exceed_km'];
                        $contractData['exceed_hr'] = $invoice['exceed_hour'];
                        $contractData['driver_day'] = $invoice['driver_day'];
                        $contractData['driver_hour'] = $invoice['driver_hour'];
                        $contractData['fuel'] = $invoice['fuel'];
                        $contractData['drop_off'] = $invoice['drop_off'];
                        $contractData['permit_exit'] = $invoice['permit_exit'];
                        $contractData['penalty'] = $invoice['penalty'];
                        $contractData['penalty_driver_hours'] = $invoice['penalty_driver_hours'];
                        $contractData['penalty_driver_days'] = $invoice['penlty_driver_days'];
                        $contractData['tamm'] = $invoice['tamm'];
                        $contractData['driver_name'] = $invoice['driver_name'];

                        $this->saveContractData($contractData['contract_no'], $contractData);
                    } else {
                        $importError = true;
                        $c_exist_msg = '(Company does not exist in the database)';
                        $errorLines .= $i . ', ';
                    }

                } catch (\Exception $e) {
                    $importError = true;
                    $errorLines .= $i . ', ';
                }

                $i++;
            }
            if ($importError == true) {
                $errorLines = rtrim($errorLines, ',');
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. But the row no ' . $errorLines . ' are having some error ' . $c_exist_msg . '. Please fix and retry. Total Records: ' . $importableRecordsCount . '';
                echo json_encode($response);
                exit();
            } else {
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. Total Records: ' . $importableRecordsCount . ', Imported Records: ' . $importableRecordsCount;
                echo json_encode($response);
                exit();
            }

        }

    }


    public function importCorporateLeaseInvoices(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $errorLines = '';
        $importError = false;
        $c_exist_msg = '';
        //set_error_handler(array(&$this, 'errorHandlerCatchUndefinedIndex'));
        ini_set('max_execution_time', 0);
        custom::log('Corporate Lease Invoices', 'import');
        $bookingObj = new Booking();
        $file = $request->file('import_corp_invoices');

        if ($file == '') {
            $response['status'] = false;
            $response['msg'] = 'Please select a file to import.';
            echo json_encode($response);
            exit();
        } else {
            $i = 1;
            $invoices = custom::importExcel($file);
            $importableRecordsCount = count($invoices);
            //echo '<pre>';print_r($invoices);exit();
            foreach ($invoices as $invoice) {
                try {

                    $customer_data['company_code'] = $invoice['customer_code'];
                    $customer_data['inv_customer_name'] = $invoice['customer_name'];
                    $customer_data['inv_customer_address'] = $invoice['customer_address'];
                    $customer_data['inv_customer_vat_id'] = $invoice['customer_vat_id'];
                    $customer_data['created_at'] = date('Y-m-d H:i:s');
                    $customer_data['updated_at'] = date('Y-m-d H:i:s');

                    $customer_id = $this->saveCompanyData($invoice['customer_code'], $customer_data);

                    if ($customer_id > 0) {
                        $invoiceData['customer_id'] = $customer_id;
                        $invoiceData['invoice_type'] = $invoice['invoice_type'];
                        $invoiceData['invoice_no'] = $invoice['invoice_no'];
                        $invoiceData['invoice_deserved_date'] = date("Y-m-d", strtotime($invoice['invoice_deserved_date']));
                        $invoiceData['created_at'] = date('Y-m-d H:i:s');
                        $invoiceData['updated_at'] = date('Y-m-d H:i:s');

                        $invoiceDetail['invoice_id'] = $this->saveInvoiceData($invoice['invoice_no'], $invoiceData);

                        $invoiceDetail['car_type'] = $invoice['car_type'];
                        $invoiceDetail['car_model'] = $invoice['car_model'];
                        $invoiceDetail['plate_no'] = $invoice['plate_no'];
                        $invoiceDetail['car_status'] = $invoice['car_status'];
                        $invoiceDetail['monthly_rent_price'] = $invoice['monthly_rent_price'];
                        $invoiceDetail['start_date'] = $invoice['start_date'];
                        $invoiceDetail['end_date'] = $invoice['end_date'];
                        $invoiceDetail['charge_price'] = $invoice['charge_price'];
                        $invoiceDetail['vat_pct'] = $invoice['vat_pct'];
                        $invoiceDetail['vat'] = $invoice['vat'];
                        $invoiceDetail['bills'] = $invoice['bills'];
                        $invoiceDetail['notes'] = $invoice['notes'];
                        $invoiceDetail['additional_fees_type'] = $invoice['additional_fees_type'];
                        $invoiceDetail['key_bank'] = $invoice['key_bank'];
                        $invoiceDetail['key_bank'] = $invoice['key_iban'];
                        $this->saveLeaseInvoiceDetail($invoiceDetail['invoice_id'], $invoice['plate_no'], $invoice['charge_price'], $invoiceDetail);
                    } else {
                        $importError = true;
                        $c_exist_msg = '(Company does not exist in the database)';
                        $errorLines .= $i . ', ';
                    }

                } catch (\Exception $e) {
                    $importError = true;
                    $errorLines .= $i . ', ';
                }

                $i++;
            }
            if ($importError == true) {
                $errorLines = rtrim($errorLines, ',');
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. But the row no ' . $errorLines . ' are having some error ' . $c_exist_msg . '. Please fix and retry. Total Records: ' . $importableRecordsCount . '';
                echo json_encode($response);
                exit();
            } else {
                $response['status'] = true;
                $response['msg'] = 'Excel imported successfully. Total Records: ' . $importableRecordsCount . ', Imported Records: ' . $importableRecordsCount;
                echo json_encode($response);
                exit();
            }

        }

    }

    private function saveCompanyData($company_code, $customer_data)
    {

        $pageObj = new Page();
        $isCustomerExist = $pageObj->getSingle('corporate_customer', array('company_code' => $company_code));
        if ($isCustomerExist) {
            $pageObj->updateData('corporate_customer', $customer_data, array('company_code' => $company_code));
            $customerId = $isCustomerExist->id;
        } else {
            //$customerId = $pageObj->saveData('corporate_customer',$customer_data);
            $customerId = 0;
        }
        return $customerId;
    }

    private function saveInvoiceData($invoice_no, $invoice_data)
    {

        $pageObj = new Page();
        $isInvoiceExist = $pageObj->getSingle('corporate_invoices', array('invoice_no' => $invoice_no));
        if ($isInvoiceExist) {
            $pageObj->updateData('corporate_invoices', $invoice_data, array('invoice_no' => $invoice_no));
            $invoiceId = $isInvoiceExist->id;
        } else {
            $invoiceId = $pageObj->saveData('corporate_invoices', $invoice_data);
        }
        return $invoiceId;
    }

    private function saveContractData($contract_no, $contract_data)
    {

        $pageObj = new Page();

        // its moved to function calling side to remove all invoices of this contract in one go
        // $pageObj->deleteData('corporate_invoices_contract',array('contract_no'=>$contract_no,'invoice_id'=>$contract_data['invoice_id'],'plate_no'=>$contract_data['plate_no']));

        $contractId = $pageObj->saveData('corporate_invoices_contract', $contract_data);
        return $contractId;
    }

    private function saveLeaseInvoiceDetail($invoice_id, $plate_no, $charge_price, $invoiceDetail)
    {

        $pageObj = new Page();
        //$pageObj->deleteData('corporate_lease_transactions', array('invoice_id'=>$invoice_id));
        $isContractExist = $pageObj->getSingle('corporate_lease_transactions', array('invoice_id' => $invoice_id, 'plate_no' => $plate_no, 'charge_price' => $charge_price));
        if ($isContractExist) {
            $pageObj->updateData('corporate_lease_transactions', $invoiceDetail, array('invoice_id' => $invoice_id, 'plate_no' => $plate_no, 'charge_price' => $charge_price));
            $contractId = $isContractExist->id;
        } else {
            $contractId = $pageObj->saveData('corporate_lease_transactions', $invoiceDetail);
        }
        //$contractId = $pageObj->saveData('corporate_lease_transactions',$invoiceDetail);
        return $contractId;
    }

    public function getSingleBookingInfo()
    {
        $bookings = new Booking();
        $booking_id = $_REQUEST['booking_id'];
        $row = $bookings->getSingleBookingInfo($booking_id);
        $row[0]->pickup_delivery_location_details = ($row[0]->pickup_delivery_lat_long != '' ? custom::getCleanLocationName($row[0]->pickup_delivery_lat_long) : '');
        $row[0]->dropoff_delivery_location_details = ($row[0]->dropoff_delivery_lat_long != '' ? custom::getCleanLocationName($row[0]->dropoff_delivery_lat_long) : '');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = 1;
        $jTableResult['Records'] = $row;
        print json_encode($jTableResult);

    }

    public function getPaymentDetailsForBooking()
    {
        $bookings = new Booking();
        $booking_id = $_REQUEST['booking_id'];
        $user_type = $_REQUEST['user_type'];
        $row = $bookings->getPaymentInfoForBooking($booking_id);
        if ($user_type == "corporate_customer" && $row[0]->payment_method == "Pay Later" && $row[0]->payment_status == "pending") {
            $row[0]->expiry_date = date('d-M-Y', strtotime($row[0]->expiry));
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = 1;
        $jTableResult['Records'] = $row;
        print json_encode($jTableResult);
    }

    public function bookingHistory()
    {

        $data['main_section'] = 'bookings';
        $data['inner_section'] = 'bookings';

        return view('admin.booking.history', $data);
    }

    public function exported_files()
    {
        $setting = new Settings();
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        $data['main_section'] = 'bookings';
        $data['inner_section'] = 'bookings';
        return view('admin.booking.exports', $data);
    }

    public function pending_bookings()
    {
        $setting = new Settings();
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        $data['main_section'] = 'bookings';
        $data['inner_section'] = 'bookings';
        return view('admin.booking.pending_bookings', $data);
    }

    public function paylater_bookings()
    {
        $setting = new Settings();
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        $data['main_section'] = 'bookings';
        $data['inner_section'] = 'bookings';
        return view('admin.booking.pay_later_bookings', $data);
    }

    public function getAllPendingBookings(Request $request)
    {
        $paylater = false;
        if (isset($_GET['paylater'])) {
            $paylater = true;
        }

        $filter_date = "";
        $pending_search_keyword_customer = "";
        $pending_search_keyword_booking = "";
        $bookings = new Booking();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $search_type = "";

        if ($request->input('filter_date')) {
            $filter_date = date('Y-m-d', strtotime($request->input('filter_date')));
        }

        if ($request->input('pending_search_keyword_customer') && $request->input('pending_search_keyword_customer') != '') {
            $pending_search_keyword_customer = $request->input('pending_search_keyword_customer');
            $search_type = $request->input('search_type');
        }

        if ($request->input('pending_search_keyword_booking') && $request->input('pending_search_keyword_booking') != '') {
            $pending_search_keyword_booking = $request->input('pending_search_keyword_booking');
        }

        if (session()->get('bHistory') != "" && session()->get('bHistory') == 'history') {

            $count_only = true;
            $totalRecordCount = $bookings->getAllPendingBookings($count_only, "", 0, 0, 0, 'history_bookings', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking);
            $count_only = false;
            $recs = $bookings->getAllPendingBookings($count_only, $sort_by, $jtStartIndex, $jtPageSize, 0, 'history_bookings', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking);
        }
        if ($paylater === true && $_GET['paylater'] == 1) {
            $count_only = true;
            $totalRecordCount = $bookings->getSTSInvoicePendingBookings($count_only, "", 0, 0, 0, '', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking);
            $count_only = false;
            $recs = $bookings->getSTSInvoicePendingBookings($count_only, $sort_by, $jtStartIndex, $jtPageSize, 0, '', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking);

        } else {

            $count_only = true;
            $totalRecordCount = $bookings->getAllPendingBookings($count_only, "", 0, 0, 0, 'current_bookings', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking, $search_type);
            $count_only = false;
            $recs = $bookings->getAllPendingBookings($count_only, $sort_by, $jtStartIndex, $jtPageSize, 0, 'current_bookings', $filter_date, $pending_search_keyword_customer, $pending_search_keyword_booking, $search_type);

        }


        $rows = array();
        foreach ($recs as $rec) {
            $rows[] = $rec;
        }
        //$recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $totalRecordCount[0]->tcount;
        $jTableResult['Records'] = $rows;

        print json_encode($jTableResult);

    }

    // exportPendingBookings
    public function exportPendingBookings(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $filter_date = "";
        $userNewDataArr = array();
        $bookings = new Booking();
        $booking_ids = $request->input('booking_ids');

        if ($request->input('filter_date')) {
            $filter_date = date('Y-m-d', strtotime($request->input('filter_date')));
        }

        $records = $bookings->exportPendingBookings($filter_date, $booking_ids);
        $bookingsData = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($bookingsData);exit();

        $commaSepBIds = "";
        foreach ($bookingsData as $row) {
            if ($commaSepBIds != "") $commaSepBIds .= ",";
            $commaSepBIds .= "'" . $row['BOOKING_ID'] . "'";
        }

        if ($commaSepBIds != '') {
            $usersToExport = $bookings->exportUsers($commaSepBIds);
            $usersData = json_decode(json_encode($usersToExport), true);
            foreach ($usersData as $item) {
                if ($item['id_no'] == '') {

                    if ($item['icg_dob'] == "0000-00-00") $item['icg_dob'] = "";
                    if ($item['icg_license_expiry_date'] == "0000-00-00") $item['icg_license_expiry_date'] = "";
                    if ($item['icg_id_expiry_date'] == "0000-00-00") $item['icg_id_expiry_date'] = "";

                    if ($item['icg_id_expiry_date'] != "0000-00-00" && $item['icg_id_expiry_date'] != "1970-01-01" && $item['icg_id_date_type'] == "H") {

                        $date = explode('-', $item['icg_id_expiry_date']);
                        $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                        $item['icg_id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
                    }

                    $usersDataArr['ID_NUMBER'] = $item['icg_id_no'];
                    $usersDataArr['ID_TYPE'] = $item['icg_id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['icg_first_name'];
                    $usersDataArr['LAST_NAME'] = $item['icg_last_name'];
                    $mobile = str_replace(' ', '', $item['icg_mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['icg_email'];
                    $usersDataArr['NATIONALITY'] = $item['icg_nationality'];
                    $usersDataArr['DOB_G'] = $item['icg_dob'];

                    $usersDataArr['ID_EXPIRY_DATE'] = $item['icg_id_expiry_date'];

                    $usersDataArr['ID_DATE_TYPE'] = $item['icg_id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['icg_id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['icg_id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['icg_license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['icg_license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['icg_license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['icg_license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['icg_job_title'];
                    $usersDataArr['SPONSOR'] = $item['icg_sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['icg_district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['icg_street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['icg_id_image']; //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['icg_license_copy']; //new added
                } else {

                    if ($item['dob'] == "0000-00-00") $item['dob'] = "";
                    if ($item['license_expiry_date'] == "0000-00-00") $item['license_expiry_date'] = "";
                    if ($item['id_expiry_date'] == "0000-00-00") $item['id_expiry_date'] = "";

                    if ($item['id_expiry_date'] != "0000-00-00" && $item['id_expiry_date'] != "1970-01-01" && $item['id_date_type'] == "H") {
                        $date = explode('-', $item['id_expiry_date']);
                        $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                        $item['id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
                    }

                    $usersDataArr['ID_NUMBER'] = $item['id_no'];
                    $usersDataArr['ID_TYPE'] = $item['id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['first_name'];
                    $usersDataArr['LAST_NAME'] = $item['last_name'];
                    $mobile = str_replace(' ', '', $item['mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['email'];
                    $usersDataArr['NATIONALITY'] = $item['nationality'];
                    $usersDataArr['DOB_G'] = $item['dob'];
                    $usersDataArr['ID_EXPIRY_DATE'] = $item['id_expiry_date'];
                    $usersDataArr['ID_DATE_TYPE'] = $item['id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['job_title'];
                    $usersDataArr['SPONSOR'] = $item['sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['id_image'];    //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['license_copy']; //new added
                }
                $userNewDataArr[] = $usersDataArr;
            }

            $payments = $bookings->exportPaymentCollection($commaSepBIds);
            $paymentData = json_decode(json_encode($payments), true);

            $fileName = custom::exportExcel("download", $bookingsData, $userNewDataArr, $paymentData, "Bookings", "Individual Customers", "Collections");
            if (!$fileName) {
                echo $fileName;
                exit;
            } else {
            }
        } else {
            echo 'Nothing to export.';
            exit();
        }

    }

    // exportPayLaterPendingBookings
    public function exportPayLaterPendingBookings(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $filter_date = "";
        $userNewDataArr = array();
        $bookings = new Booking();
        $booking_ids = $request->input('booking_ids');

        if ($request->input('filter_date')) {
            $filter_date = date('Y-m-d', strtotime($request->input('filter_date')));
        }

        $records = $bookings->exportPendingBookings($filter_date, $booking_ids);
        $bookingsData = json_decode(json_encode($records), true);
        //echo '<pre>';print_r($bookingsData);exit();

        $commaSepBIds = "";
        foreach ($bookingsData as $row) {
            if ($commaSepBIds != "") $commaSepBIds .= ",";
            $commaSepBIds .= "'" . $row['BOOKING_ID'] . "'";
        }
        //$commaSepBIds = "'WJTP001383'";
        if ($commaSepBIds != '') {
            $usersToExport = $bookings->exportCorporatePayLaterUsers($commaSepBIds);
            $usersData = json_decode(json_encode($usersToExport), true);
            foreach ($usersData as $item) {
                if ($item['booking_type'] == 'corporate_customer') {

                    $usersDataArr['ID_NUMBER'] = $item['cd_id_no'];
                    $usersDataArr['ID_TYPE'] = $item['cd_id_type'];
                    $usersDataArr['FIRST_NAME'] = $item['cd_first_name'];
                    $usersDataArr['LAST_NAME'] = $item['cd_last_name'];
                    $mobile = str_replace(' ', '', $item['cd_mobile_no']);
                    $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
                    $usersDataArr['EMAIL'] = $item['cd_email'];
                    $usersDataArr['NATIONALITY'] = $item['cd_nationality'];
                    $usersDataArr['DOB_G'] = $item['cd_dob'];

                    $usersDataArr['ID_EXPIRY_DATE'] = $item['cd_id_expiry_date'];

                    $usersDataArr['ID_DATE_TYPE'] = $item['cd_id_date_type'];
                    $usersDataArr['ID_COPY'] = $item['cd_id_copy'];
                    $usersDataArr['ID_COUNTRY'] = $item['cd_id_country'];
                    $usersDataArr['DL_ID_NUMBER'] = $item['cd_license_no'];
                    $usersDataArr['DL_ID_TYPE'] = $item['cd_license_id_type'];
                    $usersDataArr['DL_EXPIRY_DATE'] = $item['cd_license_expiry_date'];
                    $usersDataArr['DL_COUNTRY'] = $item['cd_license_id_country'];
                    $usersDataArr['JOB_TITLE'] = $item['cd_job_title'];
                    $usersDataArr['SPONSOR'] = $item['cd_sponsor'];
                    $usersDataArr['ADDRESS_DISTRICT'] = $item['cd_district_address'];
                    $usersDataArr['ADDRESS_STREET'] = $item['cd_street_address'];
                    $usersDataArr['ID_NAME_COPY'] = $item['cd_id_image']; //new added
                    $usersDataArr['DL_NAME_COPY'] = $item['cd_license_copy']; //new added
                }
                $userNewDataArr[] = $usersDataArr;
            }

            $payments = $bookings->exportPaymentCollection($commaSepBIds);
            $paymentData = json_decode(json_encode($payments), true);

            $fileName = custom::exportExcel("download", $bookingsData, $userNewDataArr, $paymentData, "Bookings", "Corporate Customers", "Collections");
            if (!$fileName) {
                echo $fileName;
                exit;
            } else {
            }
        } else {
            echo 'Nothing to export.';
            exit();
        }

    }

    private function setSurveyPendingToFill($reservation_code)
    {
        $page = new Page();
        $booking_details = $page->getSingle('booking', array('reservation_code' => $reservation_code));
        $booking_id = $booking_details->id;
        $lang = (isset($booking_details->lang) && $booking_details->lang != '' ? $booking_details->lang : 'eng');
        if ($booking_details->type == 'corporate_customer') {
            $corporate_booking_details = $page->getSingle('booking_corporate_customer', array('booking_id' => $booking_id));
            $corporate_customer_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$corporate_booking_details->uid.', uid)')->first();
            $customer_id = $corporate_customer_details->id;
        } elseif ($booking_details->type == 'individual_customer') {
            $individual_booking_details = $page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
            $individual_customer_details = $page->getSingle('individual_customer', array('uid' => $individual_booking_details->uid));
            $customer_id = $individual_customer_details->id;
        } else {
            $guest_booking_details = $page->getSingle('booking_individual_guest', array('booking_id' => $booking_id));
            $customer_id = $guest_booking_details->individual_customer_id;
        }
        if ($booking_details->type == 'corporate_customer') {
            // send email to driver, sms to driver and push notification to driver
        } else {
            $customer_details = $page->getSingle('individual_customer', array('id' => $customer_id));
            $this->sendSmsToCustomer($customer_details, $booking_id, $lang);
            $this->sendEmailToCustomer($customer_details, $booking_id, $lang);
            $this->sendPushNotofication($customer_details, $booking_id, $lang);
        }
        $save['customer_id'] = $customer_id;
        $save['booking_id'] = $booking_id;
        $save['survey_filled_status'] = 'no';
        $save['created_at'] = date('Y-m-d H:i:s');
        $page->saveData('survey_filled_status', $save);
    }

    private function sendSmsToCustomer($customer_details, $booking_id, $lang = 'eng')
    {
        $booking_id = base64_encode($booking_id);
        $customer_mobile_no = $customer_details->mobile_no;
        $customer_name = $customer_details->first_name . ' ' . $customer_details->last_name;
        $smsPhone = str_replace(array('+', ' '), '', $customer_mobile_no);

        if ($lang == 'eng') {
            $userSms = "Dear $customer_name, \nThanks for choosing " . custom::getSiteName($lang) . ".";
            $userSms .= "Share your experience with our services by using the below link: \n";
            $userSms .= custom::baseurl('/') . '/en/survey?ref=' . $booking_id;
        } else {
            $userSms = "عميلنا $customer_name,\n";
            $userSms .= "شكرا لإختيارك شركة " . custom::getSiteName($lang) . ".شاركنا رأيك وقيم خدماتنا بإستخدام الرابط التالي,  ";
            $userSms .= "\n";
            $userSms .= custom::baseurl('/') . '/survey?ref=' . $booking_id;
        }

        custom::sendSMS($smsPhone, $userSms);
    }

    private function sendEmailToCustomer($customer_details, $booking_id, $lang = 'eng')
    {
        $booking_id = base64_encode($booking_id);
        $customer_email = $customer_details->email;
        $emailName = $customer_details->first_name . ' ' . $customer_details->last_name;

        if ($lang == 'eng') {
            $emailMsg = "Please use below link to fill:";
            $emailMsg .= '<br>';
            $emailMsg .= custom::baseurl('/') . '/en/survey?ref=' . $booking_id;
        } else {
            $emailMsg = "يوجد لديك استبيان معلق من المفتاح.يرجى إستخدام الرابط لتقييم خدماتنا :";
            $emailMsg .= '<br>';
            $emailMsg .= custom::baseurl('/') . '/survey?ref=' . $booking_id;
        }

        // send email
        $site = custom::site_settings();
        $smtp = custom::smtp_settings();

        $email['subject'] = ($lang == 'eng' ? 'Survey Pending at ' . custom::getSiteName($lang) : 'تقييم غير مكتمل ');

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = $customer_email;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = $emailName;
        $content['msg'] = $emailMsg;
        $content['gender'] = $customer_details->gender;
        custom::sendEmail('general', $content, $email);
    }

    private function sendPushNotofication($customer_details, $booking_id, $lang = 'eng')
    {
        $tokens = array();
        $page = new Page();
        $customer_id = $customer_details->id;
        $customer_device_tokens = $page->getMultipleRows('survey_notification_tokens', array('customer_id' => $customer_id));
        $customerName = $customer_details->first_name . ' ' . $customer_details->last_name;
        $title = ($lang == 'eng' ? 'Survey Pending at ' . custom::getSiteName($lang) : 'تقييم غير مكتمل ');
        if ($lang == 'eng') {
            $message = "Dear $customerName,\nYour have a pending survey at " . custom::getSiteName($lang) . ".";
        } else {
            $message = "عزيزي $customerName,\n يوجد لديك استبيان معلق من " . custom::getSiteName($lang) . ".يرجى إستخدام الرابط لتقييم خدماتنا";
        }
        //echo '<pre>';print_r($customer_device_tokens);
        foreach ($customer_device_tokens as $device_token) {
            $tokens[] = $device_token->token;
        }

        if (count($tokens) > 0) {
            custom::sendPushNotification($title, $message, $tokens, $booking_id);
        }
    }

    public function exportCampaignData()
    {
        if (!Auth::check()) {
            exit();
        }
        $booking = new Booking();
        $yourFileName = 'campaign-data-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        $responses_data = $booking->getDataToExport();
        //echo "<pre>";print_r($responses_data);exit();
        foreach ($responses_data as $responses_datum) {
            $data_for_export[] = (array)$responses_datum;
        }
        //echo "<pre>";print_r($data_for_export);exit();

        return custom::excelExport($yourFileName, $data_for_export);
    }

    public function export_cancelled_bookings(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $booking = new Booking();
        $filename = 'cancelled-bookings-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        $responses_data = $booking->getCancelledBookingsToExport($request->from_date, $request->to_date);
        // echo "<pre>";print_r($responses_data);exit();
        foreach ($responses_data as $responses_datum) {
            $data_for_export[] = (array)$responses_datum;
        }
        //echo "<pre>";print_r($data_for_export);exit();

        return custom::excelExport($filename, $data_for_export);
    }

    public function getBookingEditHistory(Request $request)
    {
        $page = new Page();

        $page->updateData('booking', array('to_be_notified' => 'no'), array('id' => $request->booking_id));

        $edit_history = $page->getMultipleRows('booking_edit_history', array('booking_id' => $request->booking_id));
        $rows = array();
        if ($edit_history) {
            foreach ($edit_history as $rec) {
                $rows[] = $rec;
            }
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = ($edit_history ? count($edit_history) : 0);
        $jTableResult['Records'] = $rows;

        print json_encode($jTableResult);
    }

    public function empty_corporate_invoices_from_db(Request $request)
    {
        if (!Auth::check()) {
            exit();
        }
        $page = new Page();
        $page->truncateData('corporate_invoices_contract');
        $page->truncateData('corporate_invoices');
        echo "DB cleared";
        die;
    }

    public function booking_added_payments()
    {
        if (!custom::rights(58, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['unsynced_bookings_count'] = DB::table('booking_added_payments')->where('sync_status', 'N')->count();
        $data['main_section'] = 'booking_added_payments';
        $data['inner_section'] = 'booking_added_payments';
        return view('admin/booking/booking_added_payments', $data);
    }

    public function getAllBookingAddedPayments()
    {
        $rows = [];

        $sorting = explode(' ', $_REQUEST['jtSorting']);
        $booking_added_payments = DB::table('booking_added_payments');

        if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
            $booking_added_payments = $booking_added_payments->whereRaw("booking_reservation_code LIKE '%" . $_REQUEST['search'] . "%' OR transaction_reference LIKE '%" . $_REQUEST['search'] . "%'");
        }

        $booking_added_payments = $booking_added_payments->orderBy($sorting[0], $sorting[1]);
        $booking_added_payments = $booking_added_payments->offset($_REQUEST['jtStartIndex'])->limit($_REQUEST['jtPageSize']);
        $booking_added_payments = $booking_added_payments->get();

        if ($booking_added_payments) {
            foreach ($booking_added_payments as $booking_added_payment) {
                $rows[] = $booking_added_payment;
            }
        }
        $recordCount = count($rows);
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = DB::table('booking_added_payments')->count();
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function export_booking_added_payments(Request $request)
    {
        $rows = array();

        $booking_added_payments = DB::table('booking_added_payments');

        $booking_added_payments = $booking_added_payments->where('sync_status', 'N');

        if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
            $booking_added_payments = $booking_added_payments->whereRaw("booking_reservation_code LIKE '%" . $_REQUEST['search'] . "%' OR transaction_reference LIKE '%" . $_REQUEST['search'] . "%'");
        }

        $booking_added_payments = $booking_added_payments->get();

        $i = 0;
        foreach ($booking_added_payments as $booking_added_payment) {

            DB::table('booking_added_payments')->where('id', $booking_added_payment->id)->update(['sync_status' => 'M', 'synced_at' => date('Y-m-d H:i:s')]);

            $rows[$i]['ACCOUNT_CARD_NUMBER'] = $booking_added_payment->card_number;
            $rows[$i]['AMOUNT'] = $booking_added_payment->amount;
            $rows[$i]['BOOKING_ID'] = $booking_added_payment->booking_reservation_code;
            $rows[$i]['EXTENSION_DAYS'] = $booking_added_payment->extended_days;
            $rows[$i]['PAYMENT_METHOD'] = $booking_added_payment->payment_method;
            $rows[$i]['TRANSACTION_REFERENCE'] = $booking_added_payment->transaction_reference;

            $i++;
        }

        $file_name = 'booking-added-payments' . (isset($_REQUEST['search']) && $_REQUEST['search'] != '' ? '-against-' . $_REQUEST['search'] : '');
        return custom::export_excel_file_custom($rows, $file_name);
    }

    public function manage_bookings()
    {
        if (!custom::rights(62, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'manage_bookings';
        $data['inner_section'] = 'manage_bookings';
        return view('admin/booking/manage_bookings', $data);
    }

    public function export_manage_bookings(Request $request)
    {

        if (!custom::rights(62, 'view')) {
            return redirect('admin/dashboard');
        }

        $filename = 'manage-bookings-' . date('d-m-y-H-i-s');

        if ($request->export_type == 'extended_bookings') {
            $data_to_export = $this->extended_bookings_data_to_export_for_manage_booking($request);
        } else {
            $data_to_export = $this->bookings_data_to_export_for_manage_booking($request);
        }

        return custom::excelExport($filename, $data_to_export);

    }

    public function get_bookings_count_for_export_in_manage_bookings(Request $request)
    {

        if (!custom::rights(62, 'view')) {
            return redirect('admin/dashboard');
        }

        $data_to_export = [];
        if ($request->count_type == '-') {
            if ($request->export_type == 'extended_bookings') {
                $data_to_export = $this->extended_bookings_data_to_export_for_manage_booking($request);
            } else {
                $data_to_export = $this->bookings_data_to_export_for_manage_booking($request);
            }
        } elseif ($request->count_type == 'Registration') {
            $where = '';
            if ($request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            } elseif ($request->from_date && !$request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
            } elseif (!$request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            }
            $query = "SELECT * FROM `users` WHERE `type` = 'individual_customer' " . $where;
            $data_to_export = DB::select($query);
        } elseif ($request->count_type == 'Canceled') {
            $where = '';
            if ($request->from_date && $request->to_date) {
                $where .= " AND DATE(synced_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(synced_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            } elseif ($request->from_date && !$request->to_date) {
                $where .= " AND DATE(synced_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
            } elseif (!$request->from_date && $request->to_date) {
                $where .= " AND DATE(synced_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            }
            $query = "SELECT * FROM `booking` WHERE `booking_status` = 'Cancelled' AND `sync` = 'A' " . $where;
            $data_to_export = DB::select($query);
        } elseif ($request->count_type == 'Expired') {
            $where = '';
            if ($request->from_date && $request->to_date) {
                $where .= " AND DATE(synced_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(synced_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            } elseif ($request->from_date && !$request->to_date) {
                $where .= " AND DATE(synced_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
            } elseif (!$request->from_date && $request->to_date) {
                $where .= " AND DATE(synced_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            }
            $query = "SELECT * FROM `booking` WHERE `booking_status` = 'expired' AND `sync` = 'A' " . $where;
            $data_to_export = DB::select($query);
        } elseif ($request->count_type == 'Corporate') {
            $where = '';
            if ($request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            } elseif ($request->from_date && !$request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
            } elseif (!$request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            }
            $query = "SELECT * FROM `booking` WHERE `sync` = 'A' AND `type` = 'corporate_customer' " . $where;
            $data_to_export = DB::select($query);
        } elseif ($request->count_type == 'Inquiry') {
            $where = '';
            if ($request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            } elseif ($request->from_date && !$request->to_date) {
                $where .= " AND DATE(created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
            } elseif (!$request->from_date && $request->to_date) {
                $where .= " AND DATE(created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
            }
            $query = "SELECT * FROM `inquiries` WHERE 1=1 " . $where;
            $data_to_export = DB::select($query);
        }
        return 'Total Bookings Count: ' . ($data_to_export ? count($data_to_export) : 0);

    }

    private function extended_bookings_data_to_export_for_manage_booking($request) {
        $where = '';
        if ($request->from_date && $request->to_date) {
            $where .= " AND DATE(bap.transaction_created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(bap.transaction_created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
        } elseif ($request->from_date && !$request->to_date) {
            $where .= " AND DATE(bap.transaction_created_at) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
        } elseif (!$request->from_date && $request->to_date) {
            $where .= " AND DATE(bap.transaction_created_at) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
        }
        $query = "SELECT payment_booking_id FROM booking_added_payments bap WHERE payment_booking_id != '' " . $where;
        $data = DB::select($query);

        $data_to_export = [];
        foreach ($data as $key => $datum) {
            $data_to_export[$key]['Payment_Booking_ID'] = $datum->payment_booking_id;
        }

        return $data_to_export;
    }

    private function bookings_data_to_export_for_manage_booking($request) {
        $where = '1=1';
        if ($request->from_date && $request->to_date) {
            $where .= " AND status = 'completed' AND DATE(bccp.trans_date) >= '" . date('Y-m-d', strtotime($request->from_date)) . "' AND DATE(bccp.trans_date) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
        } elseif ($request->from_date && !$request->to_date) {
            $where .= " AND status = 'completed' AND DATE(bccp.trans_date) >= '" . date('Y-m-d', strtotime($request->from_date)) . "'";
        } elseif (!$request->from_date && $request->to_date) {
            $where .= " AND status = 'completed' AND DATE(bccp.trans_date) <= '" . date('Y-m-d', strtotime($request->to_date)) . "'";
        }
        $query = "SELECT b.reservation_code FROM booking b JOIN booking_cc_payment bccp ON b.id = bccp.booking_id WHERE " . $where;
        $data = DB::select($query);

        $data_to_export = [];
        foreach ($data as $key => $datum) {
            $data_to_export[$key]['Booking_ID'] = $datum->reservation_code;
        }

        return $data_to_export;
    }

    public function search_manage_bookings(Request $request) {
        if ($request->search_type == 'bookings') {
            $this->booking_data_for_manage_booking($request);
        } elseif ($request->search_type == 'extended_bookings') {
            $this->extended_booking_data_for_manage_booking($request);
        } elseif ($request->search_type == 'resync') {
            $this->resync_booking_data_for_manage_booking($request);
        }
    }

    private function booking_data_for_manage_booking($request) { // MJAT002235
        $record = DB::table('booking as b')->join('booking_cc_payment as bccp', 'b.id', 'bccp.booking_id')->where('b.reservation_code', $request->search)->where('bccp.status', 'completed')->select('b.*', 'bccp.*')->first();
        if ($record) {

            $edit_btn_html = 'N/A';

            if (custom::rights(62, 'edit')) {
                $edit_btn_html = '<a href="javascript:void(0);" class="edit_booking_payment" data-booking_id="'.$record->id.'" data-trans_date="'.$record->trans_date.'"><i class="material-icons"></i></a>';
            }

            $html = '<div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Booking ID</th>
                                <th>Booking Status</th>
                                <th>TRX #</th>
                                <th>Card Digits</th>
                                <th>TRX Type</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Synced At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>'.$record->booking_id.'</td>
                                <td>'.$record->reservation_code.'</td>
                                <td>'.$record->booking_status.'</td>
                                <td>'.$record->transaction_id.'</td>
                                <td>'.$record->first_4_digits.'xxxxxxxx'.$record->last_4_digits.'</td>
                                <td>'.$record->card_brand.'</td>
                                <td>'.$record->trans_date.'</td>
                                <td>'.$record->sync.'</td>
                                <td>'.$record->synced_at.'</td>
                                <td>'.$edit_btn_html.'</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';
        } else {

            $add_btn_html = '';

            if (custom::rights(62, 'add')) {
                $add_btn_html .= '<a class="md-fab md-fab-accent add_booking_payment" href="javascript:void(0);" style="margin: auto;">
                                    <i class="material-icons"></i>
                                </a>';
            }

            $html = '<div style="text-align: center;margin-top: 60px;">
                                <h4>No Record Found</h4>
                                '.$add_btn_html.'
                            </div>';
        }
        echo $html;die;
    }

    private function extended_booking_data_for_manage_booking($request) { // MJAT001487E5
        $record = DB::table('booking_added_payments')->join('booking', 'booking_added_payments.booking_reservation_code', 'booking.reservation_code')->where('payment_booking_id', $request->search)->select('booking.*', 'booking_added_payments.*')->first();
        if ($record) {

            $edit_btn_html = 'N/A';

            if (custom::rights(62, 'edit')) {
                $edit_btn_html = '<a href="javascript:void(0);" class="edit_booking_added_payment" data-id="'.$record->id.'" data-transaction_created_at="'.$record->transaction_created_at.'" data-amount="'.$record->amount.'"><i class="material-icons"></i></a>';
            }

            $html = '<div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Booking Status</th>
                                <th>Extended Days</th>
                                <th>Payment Company</th>
                                <th>TRX Type</th>
                                <th>TRX #</th>
                                <th>Card Digits</th>
                                <th>Amount</th>
                                <th>Date & Time</th>
                                <th>Payment Source</th>
                                <th>No. of Payment</th>
                                <th>Payment Booking ID</th>
                                <th>Status</th>
                                <th>Synced At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>'.$record->reservation_code.'</td>
                                <td>'.$record->booking_status.'</td>
                                <td>'.$record->extended_days.'</td>
                                <td>'.$record->payment_company.'</td>
                                <td>'.$record->payment_method.'</td>
                                <td>'.$record->transaction_reference.'</td>
                                <td>'.$record->card_number.'</td>
                                <td>'.$record->amount.'</td>
                                <td>'.$record->transaction_created_at.'</td>
                                <td>'.$record->payment_source.'</td>
                                <td>'.$record->number_of_payment.'</td>
                                <td>'.$record->payment_booking_id.'</td>
                                <td>'.$record->sync_status.'</td>
                                <td>'.$record->synced_at.'</td>
                                <td>'.$edit_btn_html.'</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';
        } else {

            $add_btn_html = '';

            if (custom::rights(62, 'add')) {
                $add_btn_html .= '<a class="md-fab md-fab-accent add_booking_added_payment" href="javascript:void(0);" style="margin: auto;">
                                    <i class="material-icons"></i>
                                </a>';
            }

            $html = '<div style="text-align: center;margin-top: 60px;">
                                <h4>No Record Found</h4>
                                '.$add_btn_html.'
                            </div>';
        }
        echo $html;die;
    }

    private function resync_booking_data_for_manage_booking($request) { // MJAT001764
        $record = DB::table('booking')->where('reservation_code', $request->search)->first();
        if ($record) {

            $resync_btn_html = 'N/A';

            if (custom::rights(63, 'view')) {
                $resync_btn_html = '<a href="javascript:void(0);" class="resync_booking" data-id="'.$record->id.'"><i class="material-icons" style="font-weight: bold;">sync</i></a>';
            }

            $html = '<div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <div class="uk-overflow-container">
                        <table class="uk-table">
                            <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Booking Status</th>
                                <th>Status</th>
                                <th>Synced At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>'.$record->reservation_code.'</td>
                                <td>'.$record->booking_status.'</td>
                                <td>'.$record->sync.'</td>
                                <td>'.$record->synced_at.'</td>
                                <td>'.$resync_btn_html.'</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';
        } else {
            $html = '<div style="text-align: center;margin-top: 60px;">
                                <h4>No Record Found</h4>
                            </div>';
        }
        echo $html;die;
    }

    public function add_booking_payment_in_manage_bookings(Request $request) {
        $data = $request->all();
        unset($data['booking_id']);
        $data['status'] = 'completed';
        $data['payment_company'] = 'hyper_pay';
        $data['last_activity_by'] = auth()->user()->name;
        $data['last_activity_at'] = date('Y-m-d H:i:s');
        DB::table('booking_cc_payment')->where('booking_id', $request->booking_id)->update($data);
        return redirect('admin/manage-bookings');
    }

    public function update_booking_payment_in_manage_bookings(Request $request) {
        $data['trans_date'] = $request->trans_date;
        $data['last_activity_by'] = auth()->user()->name;
        $data['last_activity_at'] = date('Y-m-d H:i:s');
        DB::table('booking_cc_payment')->where('booking_id', $request->booking_id)->update($data);
        return redirect('admin/manage-bookings');
    }

    public function add_extended_booking_payment_in_manage_bookings(Request $request) {
        $data = $request->all();
        $data['last_activity_by'] = auth()->user()->name;
        $data['last_activity_at'] = date('Y-m-d H:i:s');
        DB::table('booking_added_payments')->insertGetId($data);
        return redirect('admin/manage-bookings');
    }

    public function update_extended_booking_payment_in_manage_bookings(Request $request) {
        $data['transaction_created_at'] = $request->transaction_created_at;
        $data['amount'] = $request->amount;
        $data['last_activity_by'] = auth()->user()->name;
        $data['last_activity_at'] = date('Y-m-d H:i:s');
        DB::table('booking_added_payments')->where('id', $request->id)->update($data);
        return redirect('admin/manage-bookings');
    }

}

?>