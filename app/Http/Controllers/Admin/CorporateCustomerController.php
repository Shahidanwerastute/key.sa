<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\Page;
use App\Models\Admin\CorporateCustomer;
use Illuminate\Http\Request;
use DB;
use Lang;
use App\Helpers\Custom;


class CorporateCustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!custom::rights(20, 'view')) {
            return redirect('admin/dashboard');
        }
        $customer = new CorporateCustomer();
        $data['customers'] = $customer->getAll();
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'corporate_customers';

        return view('admin/corporate_customer/manage', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        if (!custom::rights(20, 'add')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'corporate_customers';
        return view('admin/corporate_customer/add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function save(Request $request)
    {
        $page = new Page();
        $corporate_data = $request->input();

        $usernames = $corporate_data['username'];
        $passwords = $corporate_data['password'];
        $is_email_verified = $corporate_data['is_email_verified'];
        $is_phone_verified = $corporate_data['is_phone_verified'];

        unset($corporate_data['username']);
        unset($corporate_data['password']);
        unset($corporate_data['is_email_verified']);
        unset($corporate_data['is_phone_verified']);

        $corporate_count = $page->getRowsCount('corporate_customer', array('company_code' => $corporate_data['company_code']));
        if ($corporate_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'Corporate user already exist with posted company code.';
            echo json_encode($responseArr);
            exit();
        }

        foreach ($usernames as $index => $username) {
            $user_count = $page->getRowsCount('users', array('email' => $username));
            if ($user_count > 0) {
                $responseArr['status'] = false;
                $responseArr['message'] = 'User already exist with posted username "' . $username . '".';
                echo json_encode($responseArr);
                exit();
            }
        }

        if (!isset($corporate_data['active_status'])) {
            $corporate_data['active_status'] = 'inactive';
        }

        if (!isset($corporate_data['lease_invoices'])) {
            $corporate_data['lease_invoices'] = '0';
        }

        if (!isset($corporate_data['has_price_with_quotation'])) {
            $corporate_data['has_price_with_quotation'] = 'No';
        }

        if (!isset($corporate_data['has_limousine_option'])) {
            $corporate_data['has_limousine_option'] = 'No';
        }

        if (!isset($corporate_data['credit_card'])) {
            $corporate_data['credit_card'] = 0;
        }

        if (!isset($corporate_data['cash'])) {
            $corporate_data['cash'] = 0;
        }

        if (!isset($corporate_data['corporate_credit'])) {
            $corporate_data['corporate_credit'] = 0;
        }
        $corporate_data['created_at'] = date('Y-m-d H:i:s');
        $corporate_data['updated_at'] = date('Y-m-d H:i:s');
        $corporate_id = $page->saveData('corporate_customer', $corporate_data);
        if ($corporate_id > 0) {

            $new_user_ids = [];
            foreach ($usernames as $index => $username) {

                $user['name'] = $corporate_data['company_name_en'];
                $user['email'] = $username;
                $user['password'] = md5($passwords[$index]);
                $user['type'] = 'corporate_customer';
                $user['is_email_verified'] = $is_email_verified[$index];
                $user['is_phone_verified'] = $is_phone_verified[$index];
                $user['created_at'] = date('Y-m-d H:i:s');
                $user['updated_at'] = date('Y-m-d H:i:s');
                $new_user_ids[] = $page->saveData('users', $user);

                if (isset($corporate_data['active_status'])) {
                    $this->sendActivationEmail($username, $corporate_data['company_name_en'], $username, $passwords[$index]);
                }

            }

            $page->updateData('corporate_customer', array('uid' => implode(',', $new_user_ids)), array('id' => $corporate_id));

        }

        $responseArr['status'] = true;
        $responseArr['message'] = 'Corporate user created successfully.';
        $responseArr['is_redirect'] = true;
        $responseArr['redirect_url'] = custom::baseurl('/admin/corporate_customer');
        echo json_encode($responseArr);
        exit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function view($id)
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
        if (!custom::rights(20, 'edit')) {
            return redirect('admin/dashboard');
        }
        $page = new Page();
        $data['id'] = $id;
        $customer = $page->getSingle('corporate_customer', array('id' => $id));
        $data['customer'] = $customer;
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'corporate_customers';
        return view('admin/corporate_customer/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $page = new Page();
        $corporate_data = $request->input();

        $id = $corporate_data['id'];
        unset($corporate_data['id']);

        $old_user_ids = explode(',', $corporate_data['uid']);
        unset($corporate_data['uid']);

        if (!empty($corporate_data['username'])) {
            $usernames = $corporate_data['username'];
            $passwords = $corporate_data['password'];

            unset($corporate_data['username']);
            unset($corporate_data['password']);
        }

        if (isset($corporate_data['active_status']) && $corporate_data['active_status'] == 'active') {
            $is_being_activated = true;
            $is_being_deactivated = false;
        } else {
            $is_being_activated = false;
            $is_being_deactivated = true;
        }

        $corporate_count = $page->getRowsCount('corporate_customer', array('company_code' => $corporate_data['company_code']));
        $corporate_check = $page->getSingle('corporate_customer', array('company_code' => $corporate_data['company_code']));
        if ((isset($corporate_check->company_code) && $corporate_check->company_code != $corporate_data['company_code']) && $corporate_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'Corporate user already exist with posted company code.';
            echo json_encode($responseArr);
            exit();
        }

        if (isset($usernames)) {
            foreach ($usernames as $index => $username) {
                $user_count = $page->getRowsCount('users', array('email' => $username));
                if ($user_count > 0) {
                    $responseArr['status'] = false;
                    $responseArr['message'] = 'User already exist with posted username "' . $username . '".';
                    echo json_encode($responseArr);
                    exit();
                }
            }
        }

        if (!isset($corporate_data['active_status'])) {
            $corporate_data['active_status'] = 'inactive';
        }

        if (!isset($corporate_data['lease_invoices'])) {
            $corporate_data['lease_invoices'] = '0';
        }

        if (!isset($corporate_data['has_price_with_quotation'])) {
            $corporate_data['has_price_with_quotation'] = 'No';
        }

        if (!isset($corporate_data['has_limousine_option'])) {
            $corporate_data['has_limousine_option'] = 'No';
        }

        if (!isset($corporate_data['credit_card'])) {
            $corporate_data['credit_card'] = 0;
        }

        if (!isset($corporate_data['cash'])) {
            $corporate_data['cash'] = 0;
        }

        if (!isset($corporate_data['corporate_credit'])) {
            $corporate_data['corporate_credit'] = 0;
        }

        if (!isset($corporate_data['call_center'])) {
            $corporate_data['call_center'] = 0;
        }
        $corporate_data['updated_at'] = date('Y-m-d H:i:s');
        $page->updateData('corporate_customer', $corporate_data, array('id' => $id));

        // sending account emails to new accounts
        if (isset($usernames)) {

            $new_user_ids = [];
            foreach ($usernames as $index => $username) {
                $user['name'] = $corporate_data['company_name_en'];
                $user['email'] = $username;
                $user['password'] = md5($passwords[$index]);
                $user['type'] = 'corporate_customer';
                $user['created_at'] = date('Y-m-d H:i:s');
                $user['updated_at'] = date('Y-m-d H:i:s');
                $new_user_ids[] = $page->saveData('users', $user);

                if (isset($corporate_data['active_status'])) {
                    $this->sendActivationEmail($username, $corporate_data['company_name_en'], $username, $passwords[$index]);
                }
            }

            $page->updateData('corporate_customer', array('uid' => implode(',', array_merge($old_user_ids, $new_user_ids))), array('id' => $id));

        }

        // sending account emails to old accounts in case of account activation or deactivation
        if ($is_being_activated && $corporate_check->active_status != $corporate_data['active_status']) {
            foreach ($old_user_ids as $old_user_id) {
                $user_detail = custom::getSingle('users', ['id' => $old_user_id]);
                $this->sendActivationEmailOnUpdate($user_detail->email, $corporate_data['company_name_en'], $user_detail->email);
            }
        }
        if ($is_being_deactivated && $corporate_check->active_status != $corporate_data['active_status']) {
            foreach ($old_user_ids as $old_user_id) {
                $user_detail = custom::getSingle('users', ['id' => $old_user_id]);
                $this->sendDeactivationEmail($user_detail->email, $corporate_data['company_name_en']);
            }
        }

        $responseArr['status'] = true;
        $responseArr['message'] = 'Corporate user updated successfully.';
        $responseArr['is_redirect'] = true;
        $responseArr['redirect_url'] = custom::baseurl('/admin/corporate_customer/edit/' . $id);
        echo json_encode($responseArr);
        exit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function delete($id)
    {
        if (!custom::rights(20, 'delete')) {
            return redirect('admin/dashboard');
        }
        $page = new Page();
        $record = $page->getSingle('corporate_customer', array('id' => $id));
        $deleted = $page->deleteData('corporate_customer', array('id' => $id));
        if ($deleted) {
            $page->deleteData('users', array('id' => $record->uid));
            echo 1;
            exit();
        } else {
            echo 0;
            exit();
        }
    }


    public function getSingleUserInfo()
    {
        $customer = new CorporateCustomer();
        $user_id = $_REQUEST['user_id'];
        $row = $customer->getSingleUserInfo($user_id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = 1;
        $jTableResult['Records'] = $row;
        print json_encode($jTableResult);

    }

    private function sendActivationEmail($to_email, $to_name, $user_email, $password)
    {
        $page = new Page();
        $smtp = custom::smtp_settings();
        $site = custom::site_settings();
        $token = sha1(mt_rand());
        $resetData['email'] = $user_email;
        $resetData['token'] = $token;
        $resetData['created_at'] = date('Y-m-d H:i:s');
        $page->saveData('password_resets', $resetData);

        $message = 'Your account is created and activated at key.sa. Following are the login details for your corporate account.<br>';
        $message .= "Username: " . $user_email . "<br>";
        $message .= "Password: " . $password . "<br>";
        $message .= "You can use the following link if you want to change your password.<br>";
        $message .= custom::baseurl('/') . '/reset-password?_key=' . $token;


        $email['subject'] = "Account Activated At Key";

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = $to_email;

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = $to_name;
        $content['msg'] = $message;
        $content['gender'] = 'male';
        custom::sendEmail('general', $content, $email);
    }

    private function sendActivationEmailOnUpdate($to_email, $to_name, $user_email)
    {
        $page = new Page();
        $smtp = custom::smtp_settings();
        $site = custom::site_settings();

        $message = 'Your account is activated at key.sa. Use following username and your password to login to your corporate account. If you have forgot your password, please use forgot password functionality in the site to change your password.<br>';
        $message .= "Username: " . $user_email . "<br>";


        $email['subject'] = "Account Activated At Key";

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = $to_email;

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = $to_name;
        $content['msg'] = $message;
        $content['gender'] = 'male';
        custom::sendEmail('general', $content, $email);
    }

    private function sendDeactivationEmail($to_email, $to_name)
    {
        $smtp = custom::smtp_settings();
        $site = custom::site_settings();
        $message = 'You account has been deactivated by key.sa.';

        $email['subject'] = 'Account Deactivated At Key';

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = $to_email;

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = $to_name;
        $content['msg'] = $message;
        $content['gender'] = 'male';
        custom::sendEmail('general', $content, $email);
    }

    public function getAllForDropdown()
    {
        $data = array();
        $customer = new CorporateCustomer();
        $record = $customer->getAllCorporateCustomers();

        $rows = array();
        // first time set empty value here
        $rows[] = array("DisplayText"=>"","Value"=>"");

        foreach ($record as $t) {
            $rows[] = $t;
        }

        $data['Options'] = $rows;
        $data['Result'] = "OK";

        echo json_encode($data);
        exit();
    }

}

?>