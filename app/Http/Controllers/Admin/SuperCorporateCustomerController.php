<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\Page;
use App\Models\Admin\CorporateCustomer;
use Illuminate\Http\Request;
use DB;
use Lang;
use App\Helpers\Custom;


class SuperCorporateCustomerController extends Controller
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
        $data['customers'] = $customer->getAllSuper();
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'super_corporate_customers';
        return view('admin/super_corporate_customer/manage', $data);
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
        $data['inner_section'] = 'super_corporate_customers';
        return view('admin/super_corporate_customer/add', $data);
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

        $corporate_count = $page->getRowsCount('corporate_customer', array('primary_email' => $corporate_data['primary_email'],'is_super'=>1));
        $user_count = $page->getRowsCount('users', array('email' => $corporate_data['username']));

        if ($corporate_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'Corporate user already exist with posted email address.';
            echo json_encode($responseArr);
            exit();
        } elseif ($user_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'User already exist with posted username.';
            echo json_encode($responseArr);
            exit();
        } else {
            $username = $corporate_data['username'];
            $password = $corporate_data['password'];

            unset($corporate_data['username']);
            unset($corporate_data['password']);

            if (!isset($corporate_data['active_status'])) {
                $corporate_data['active_status'] = 'inactive';
            }

            $corporate_data['is_super'] = 1;
            $corporate_data['created_at'] = date('Y-m-d H:i:s');
            $corporate_data['updated_at'] = date('Y-m-d H:i:s');
            $corporate_id = $page->saveData('corporate_customer', $corporate_data);
            if ($corporate_id > 0) {
                $user['email'] = $username;
                $user['name'] = $corporate_data['primary_name'];
                $user['password'] = md5($password);
                $user['type'] = 'corporate_customer';
                $user['created_at'] = date('Y-m-d H:i:s');
                $user['updated_at'] = date('Y-m-d H:i:s');
                $user_id = $page->saveData('users', $user);
                $page->updateData('corporate_customer', array('uid' => $user_id), array('id' => $corporate_id));
            }
            if (isset($corporate_data['active_status']) && $corporate_data['active_status'] == 'active' && $password != '') {
                $this->sendActivationEmail($corporate_data['primary_email'], $corporate_data['primary_name'], $username, $password);
            }
            $responseArr['status'] = true;
            $responseArr['message'] = 'Super corporate user created successfully.';
            $responseArr['is_redirect'] = true;
            $responseArr['redirect_url'] = custom::baseurl('/admin/super_corporate_customer');
            echo json_encode($responseArr);
            exit();
        }
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
        $user = $page->getSingle('users', array('id' => $customer->uid));
        $data['user'] = $user;
        $data['customer'] = $customer;
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'super_corporate_customers';
        return view('admin/super_corporate_customer/edit', $data);
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
        if (isset($corporate_data['active_status']) && $corporate_data['active_status'] == 'active') {
            $is_being_activated = true;
            $is_being_deactivated = false;
        } else {
            $is_being_activated = false;
            $is_being_deactivated = true;
        }

        $corporate_count = $page->getRowsCount('corporate_customer', array('primary_email' => $corporate_data['primary_email'],'is_super'=>1));
        $user_count = $page->getRowsCount('users', array('email' => $corporate_data['username']));

        $corporate_check = $page->getSingle('corporate_customer', array('primary_email' => $corporate_data['primary_email']));
        $user_check = $page->getSingle('users', array('email' => $corporate_data['username']));

        if ((isset($corporate_check->primary_email) && $corporate_check->primary_email != $corporate_data['primary_email']) && $corporate_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'Corporate user already exist with posted email.';
            echo json_encode($responseArr);
            exit();
        } elseif ((isset($user_check->email) && $user_check->email != $corporate_data['username']) && $user_count > 0) {
            $responseArr['status'] = false;
            $responseArr['message'] = 'User already exist with posted username.';
            echo json_encode($responseArr);
            exit();
        }/* elseif ($is_being_activated && $corporate_data['password'] == '') {
            $responseArr['status'] = false;
            $responseArr['message'] = 'Please fill in password field.';
            echo json_encode($responseArr);
            exit();
        }*/ else {
            $username = $corporate_data['username'];
            $id = $corporate_data['id'];
            $uid = $corporate_data['uid'];

            unset($corporate_data['id']);
            unset($corporate_data['uid']);
            unset($corporate_data['username']);
            unset($corporate_data['password']);
            unset($corporate_data['old_password']);

            if (!isset($corporate_data['active_status'])) {
                $corporate_data['active_status'] = 'inactive';
            }

            $corporate_data['is_super'] = 1;
            $corporate_data['updated_at'] = date('Y-m-d H:i:s');
            $page->updateData('corporate_customer', $corporate_data, array('id' => $id));
            $user['email'] = $username;
            $user['name'] = $corporate_data['primary_name'];
            if($request->input('password') != ''){
                $user['password'] = md5($request->input('password'));
            }
            $user['type'] = 'corporate_customer';
            $user['updated_at'] = date('Y-m-d H:i:s');
            $page->updateData('users', $user, array('id' => $uid));
            if ($is_being_activated && $corporate_check->active_status != $corporate_data['active_status']) {
                $this->sendActivationEmailOnUpdate($corporate_data['primary_email'], $corporate_data['primary_name'], $username);
            }
            if ($is_being_deactivated && $corporate_check->active_status != $corporate_data['active_status']) { // checking if posted status is changed than already status
                $this->sendDeactivationEmail($corporate_data['primary_email'], $corporate_data['primary_name']);
            }
            $responseArr['status'] = true;
            $responseArr['message'] = 'Corporate user updated successfully.';
            $responseArr['is_redirect'] = true;
            $responseArr['redirect_url'] = custom::baseurl('/admin/super_corporate_customer');
            echo json_encode($responseArr);
            exit();
        }
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

        $message = 'Your account is activated at key.sa. Following are the login details for your corporate account.<br>';
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