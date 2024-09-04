<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\Page;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use App\Models\Admin\UserRole;
use App\Helpers\Custom;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_register()
    {
        $page = new Page();
        $data['branches'] = $page->getBranchesOfCities();
        $data['roles'] = $page->getAll('setting_user_role');
        return view('admin.auth.register', $data);
    }

    public function admin_registration(Request $request)
    {
        $branches = $request->input('branches');
        $page = new Page();
        $role = array();
        $branchData = array();
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
        $role['role_id'] = $request->input('role');
        $role['uid'] = $user['id'];
        UserRole::Create($role);

        foreach ($branches as $branch) {
            $branchData['admin_id'] = $user['id'];
            $branchData['branch_id'] = $branch;
            $page->saveData('admin_branch', $branchData);
        }

        // admin

        return redirect('admin/login');
    }

    public function adminLoginPage()
    {
        $allowed_ips = array('87.101.143.178', '188.48.143.73', '116.58.71.81', '116.58.71.82', '37.99.161.118', '5.42.246.1', '5.42.246.2', '5.42.246.3', '5.42.246.4', '5.42.246.5', '5.42.246.6', '37.99.171.68', '37.99.171.67', '31.166.78.46');
        $request_ip = $_SERVER['REMOTE_ADDR'];
        if (strpos($_SERVER['SERVER_NAME'], 'key.sa') !== false)
        {
            if (!in_array($request_ip, $allowed_ips))
            {
                //echo 'Not Allowed';
                //exit();
            }
        }
        return view('admin.auth.login');
    }

    public function admin_login(Request $request)
    {

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'type' => 'admin', 'active_status' => 'active'])) {
            return redirect()->to(custom::baseurl().'/admin/welcome');
        }else{
            Session::put('last_login_try_at', date('Y-m-d H:i:s'));
            if (Session::has('login_attempt') && Session::get('login_attempt') > 0)
            {
                $login_attempt = Session::get('login_attempt');
                Session::put('login_attempt', $login_attempt+1);
            }else{
                Session::put('login_attempt', 1);
            }

            if (Session::get('login_attempt') > 2)
            {
                // updating user active status to inactive
                $page = new Page();
                $page->updateData('users', array('active_status' => 'inactive'), array('email' => $request['email']));
                Session::put('login_error_message', 'You have been blocked from accessing this. Please contact admin for further action.');
            }
            Session::save();
        }
        return redirect()->back();

    }

    public function adminLogout()
    {

        Auth::logout();
        Session::forget('admin_branches');
        return redirect()->to('admin/login');
    }

    public function reset_password()
    {
        $data['main_section'] = 'dashboard';
        $data['inner_section'] = '';
        return view('admin.auth.reset_password', $data);
    }

    public function resetPassword(Request $request)
    {
        $user_id = Auth::id();
        $page = new Page();
        $password = $request->password;
        $data['password'] = bcrypt($password);
        $updated = $page->updateData('users', $data, array('id' => $user_id));
        if ($updated)
        {
            $response['status'] = true;
            $response['title'] = 'Success';
            $response['message'] = 'Your password is changed successfully.';
        }else{
            $response['status'] = false;
            $response['title'] = 'Error';
            $response['message'] = 'Your password failed to be changed. Please try again.';
        }
        echo json_encode($response);
        exit();

    }
}
