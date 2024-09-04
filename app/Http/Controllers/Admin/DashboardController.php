<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Settings;
use Carbon\Carbon;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\DB;
use Auth;
use custom;
use Session;
use Google_Client;
use Google_Service_Books;

class DashboardController extends Controller
{

    public function __construct(Request $request)
    {
        DB::enableQueryLog();
    }

    public function index()
    {
        Session::put('login_attempt', 0);
        Session::put('login_error_message', '');
        Session::forget('login_error_message');
        Session::save();
        $ga_view_id = '138871032';
        $app_key = 'AIzaSyAC9E9BtK-WByMnmKgTPD_9CRgAJpD_8I0';
        $account_id = '24417190';

        /*require_once "vendor/autoload.php";
        // $service_account_email = 'nesma-632@nesma-1216.iam.gserviceaccount.com';

        $data['ga_view_id'] = $ga_view_id;

        $service_account_email = 'maximal-cabinet-104112@appspot.gserviceaccount.com';

        //$key_file_location = APPPATH . "libraries/vendor/Nesma-0684e421b44b.p12";//
        $key_file_location = APPPATH . "libraries/vendor/googlemap-7848789c3ad7.p12";
        // $key_file_location =  __DIR__ . '/Nesma-0684e421b44b.p12';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Analytics");
        $analytics = new Google_Service_Analytics($client);

        // Read the generated client_secrets.p12 key.
        $key = file_get_contents($key_file_location);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_email,
            array(Google_Service_Analytics::ANALYTICS_READONLY),
            $key
        );
        $client->setAssertionCredentials($cred);
        if($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }


        $res = json_decode($client->getAccessToken());
        $data['analytics'] = $res->access_token;*/

        if (custom::rights(36, 'view')) // if user has rights to see dashboard than show him otherwise show him blank page
        {
            $sessionData = array();
            $branch_labels = array();
            $bookings_count = array();
            $page = new Page();
            $userID = Auth::id();
            $page = new Page();
            $admin_branches = $page->getMultipleRows('admin_branch', array('admin_id' => $userID));
            if ($admin_branches) {
                foreach ($admin_branches as $branch) {
                    $sessionData[] = $branch->branch_id;
                }
                Session::put('admin_branches', $sessionData);
                Session::save();
            }

            $for_today = Carbon::parse(Carbon::now())->format('Y-m-d');
            $for_yesterday = Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d');
            $for_last_seven_days = Carbon::parse(Carbon::now()->subDays(7))->format('Y-m-d');
            $for_last_week = Carbon::parse(Carbon::now()->subWeeks(1))->format('Y-m-d');
            $for_last_month = Carbon::parse(Carbon::now()->subMonth(1))->format('Y-m-d');

            $settings = new Settings();
            $data['logs'] = $settings->get_latest_logs($for_last_seven_days);


            $data['bookings_count_for_today'] = $settings->get_bookings_done($for_today);
            $data['bookings_count_for_yesterday'] = $settings->get_bookings_done($for_yesterday, "=");
            $data['bookings_count_for_last_week'] = $settings->get_bookings_done($for_last_week);
            $data['bookings_count_for_last_month'] = $settings->get_bookings_done($for_last_month);
            $data['bookings_count_total'] = $settings->get_bookings_done("");

            $data['cancelled_count_for_today'] = $settings->get_bookings_cancelled($for_today);
            $data['cancelled_count_for_yesterday'] = $settings->get_bookings_cancelled($for_yesterday, "=");
            $data['cancelled_count_for_last_week'] = $settings->get_bookings_cancelled($for_last_week);
            $data['cancelled_count_for_last_month'] = $settings->get_bookings_cancelled($for_last_month);
            $data['cancelled_count_total'] = $settings->get_bookings_cancelled("");


            $data['sales_for_today'] = $settings->get_bookings_total_sale($for_today);
            $data['sales_for_yesterday'] = $settings->get_bookings_total_sale($for_yesterday, "=");
            $data['sales_for_last_week'] = $settings->get_bookings_total_sale($for_last_week);
            $data['sales_for_last_month'] = $settings->get_bookings_total_sale($for_last_month);
            $data['sales_total'] = $settings->get_bookings_total_sale("");

            $all_branches = $page->getAll('branch');
            foreach ($all_branches as $branch) {
                $branchCity = $page->getSingleRow('city', array('id' => $branch->city_id));
                $branchName = $branch->eng_title . ', ' . $branchCity->eng_title;
                $branch_labels[] = $branchName;
                $branchs[] = $branch->id;
                //$count = $page->getMultipleRowsCount('booking', array('from_location' => $branch->id));
                $count = $settings->get_bookings_done_from_branch($branch->id);
                //$count = false;
                if ($count == false) {
                    $bookings_count[] = 0;
                } else {
                    $bookings_count[] = $count;
                }
            }
	

            $data['inquiries_count_for_today'] = $settings->get_inquiries_count($for_today);
            $data['inquiries_count_for_yesterday'] = $settings->get_inquiries_count($for_yesterday, "=");
            $data['inquiries_count_for_last_week'] = $settings->get_inquiries_count($for_last_week);
            $data['inquiries_count_for_last_month'] = $settings->get_inquiries_count($for_last_month);
            $data['inquiries_count_total'] = $settings->get_inquiries_count("");

            $data['registered_users_for_today'] = $settings->get_users_count($for_today);
            $data['registered_users_for_yesterday'] = $settings->get_users_count($for_yesterday, "=");
            $data['registered_users_for_last_week'] = $settings->get_users_count($for_last_week);
            $data['registered_users_for_last_month'] = $settings->get_users_count($for_last_month);
            $data['registered_users_total'] = $settings->get_users_count("");

            $data['show_charts'] = true;
            $data['branch_labels'] = $branch_labels;
            $data['bookings_count'] = $bookings_count;
            $view = 'admin/dashboard';
        } else {
            $view = 'admin/dashboard-no-admin';
        }


        $data['main_section'] = 'dashboard';
        $data['inner_section'] = '';

        return view($view, $data);

    }

    public function blank_page()
    {
        Session::put('login_attempt', 0);
        Session::put('login_error_message', '');
        Session::forget('login_error_message');
        Session::save();
        $data['main_section'] = '';
        $data['inner_section'] = '';

        return view('admin/dashboard-no-admin', $data);
    }

}
