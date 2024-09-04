<?php

namespace App\Http\Controllers\Services;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services\Services;
use App\Models\Front\Page;
use App\Models\Admin\Booking;
use App\Helpers\Custom;
use App;
use Lang;
use DB;
use SoapClient;
use SoapFault;
use Validator;
use Carbon\Carbon;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;

class SSLPinningServicesController extends Controller
{
    private $lang = '';
    private $page = '';
    private $pdf = '';
    private $services = '';
    private $lang_base_url = '';

    public function __construct(Request $request)
    {
        /*$mobile_no = '03368809300';
        echo $this->generate_mreg_no($mobile_no);exit();*/
        /*$phone_no = '966566300291';
        echo $this->fixSaudiMobileNumber($phone_no);exit();*/
        try {
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            // cleaning request data
            // $_REQUEST = custom::clean_request_data($_REQUEST);

            $segments = \Request::segments();
            $last_segment = end($segments);
            // k=ItykVex546VBeiXabxlExlyzErtc313
            if ($last_segment != 'verifyPayment' && $last_segment != 'sadad-confirmation') {
                if (!isset($_REQUEST['k'])) {
                    echo 'Key is missing';
                    exit();
                } elseif ($_REQUEST['k'] != 'ItykVex546VBeiXabxlExlyzErtc313') {
                    echo 'Key is missing';
                    exit();
                }
            }

            if (isset($_REQUEST['lang'])) {
                if ($_REQUEST['lang'] == 'ar') {
                    $lang = 'arb';
                } else {
                    $lang = 'eng';
                }
            } else {
                $lang = 'eng';
            }

            $this->lang = $lang;

            if ($this->lang == 'eng') {
                $this->lang_base_url = custom::baseurl('/') . '/en';
            } else {
                $this->lang_base_url = custom::baseurl('/');
            }

            $this->page = new Page();
            $this->services = new Services();
            $this->pdf = App::make('snappy.pdf.wrapper');
            app()->setLocale($this->lang);
            \Artisan::call('cache:clear');

            if (isset($_REQUEST['pickup_time'])) $_REQUEST['pickup_time'] = str_replace(' ', ' ', $_REQUEST['pickup_time']);
            if (isset($_REQUEST['dropoff_time'])) $_REQUEST['dropoff_time'] = str_replace(' ', ' ', $_REQUEST['dropoff_time']);

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getAllCities()
    {
        $cities = $this->page->getAll('city', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
        foreach ($cities as $city) {
            $branches_count = $this->page->getMultipleRows('branch', ['city_id' => $city->id, 'active_status' => '1']);
            $city->branches_count = $branches_count ? count($branches_count) : 0;
        }
        $response['status'] = 1;
        $response['message'] = "";
        $response['response'] = custom::isNullToEmpty($cities);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getAllBranches()
    {
        try {
            $i = 0;
            $city_id = (isset($_REQUEST['city_id']) ? $_REQUEST['city_id'] : false);
            $lat = (isset($_REQUEST['lat']) ? $_REQUEST['lat'] : 0.0);
            $long = (isset($_REQUEST['long']) ? $_REQUEST['long'] : 0.0);
            $mode = (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0); // mode 1 will return delivery type branches otherwise it will return pickup type branches
            if ($mode == 1) {
                $branches = $this->services->getAllDeliveryBranches($lat, $long, $city_id);
            } else {
                $branches = $this->services->getAllBranches($lat, $long, $city_id);
            }
            $branches = json_decode(json_encode($branches), true);
            foreach ($branches as $branch) {
                $j = 0;
                $coords_array = array();
                if ($branch['is_delivery_branch'] == 'yes') {
                    $area_coordinates = $this->page->getMultipleRows('branch_coverage_points', array('branch_id' => $branch['branch_id']), 'branch_id');
                    foreach ($area_coordinates as $area_coordinate) {
                        $lat_longs = explode(',', $area_coordinate->coordinates);
                        $coords_array[$j]['latitude'] = (float)$lat_longs[0];
                        $coords_array[$j]['longitude'] = (float)$lat_longs[1];
                        $j++;
                    }
                }
                $branches[$i]['area_coordinates'] = $coords_array;
                $i++;
            }

            //echo '<pre>';print_r($branches);exit();
            if ($branches) {
                $branches = custom::isNullToEmpty($branches);
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = $branches;
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'No Branches Found.' : 'No Branches Found.');
                $response['response'] = "";
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getAllBranchesForMap()
    {
        try {
            $lat = $_REQUEST['lat'];
            $long = $_REQUEST['long'];
            $branches = $this->services->getAllBranchesForMap($lat, $long);
            //echo '<pre>';print_r($branches);exit();
            if ($branches) {
                $branches = custom::isNullToEmpty($branches);
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = $branches;
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'No Branches Found.' : 'لا يوجد فروع');
                $response['response'] = "";
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getBranchesBySearch()
    {
        try {
            $lat = $_REQUEST['lat'];
            $long = $_REQUEST['long'];
            $keyword = $_REQUEST['keyword'];
            //$keyword = 'toyota';
            $branches = $this->services->getBranchesBySearch($keyword, $lat, $long);
            //echo '<pre>'.$keyword; print_r($branches);exit();
            if ($branches) {
                $branches = custom::isNullToEmpty($branches);
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = $branches;
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'No Branches Found Against This Search.' : 'لا يوجد فروع للبحث');
                $response['response'] = "";
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getSavedBranches()
    {
        try {
            $lat = $_REQUEST['lat'];
            $long = $_REQUEST['long'];
            $savedBranches = $_REQUEST['branches'];
            $savedBranches = explode(',', $savedBranches);
            $branches = $this->services->getSavedBranches($lat, $long, $savedBranches);
            //echo '<pre>';print_r($branches);exit();
            if ($branches) {
                $branches = custom::isNullToEmpty($branches);
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = $branches;
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'No Branches Found.' : 'لا يوجد فروع');
                $response['response'] = "";
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    /**
     *
     */
    public function getCars()
    {
        try {
            // change
            $this->checkIfMaintenanceModeOn();
            $modelId = "";
            $offset = "";
            $limit = "";
            $loyalty_discount_percent = 0;
            $site_settings = custom::site_settings();
            $no_of_hours_are_fine = false;
            $difference_is_fine = false;
            $pickup_dropoff_are_ahead_of_current_time = false;
            $pickup_time_is_ok = false;
            $dropoff_time_is_ok = false;
            $delivery_slots_are_ok = true;
            $monthly_date_diff_is_fine = true;
            $customer_id_for_checkout_screen = 0;

            $customer_type = (isset($_REQUEST['customer_type']) && $_REQUEST['customer_type'] != "" ? $_REQUEST['customer_type'] : "Individual");

            //fixture for IOS in case of individual or  individual_customer both are considered now same.
            if ($customer_type == "individual_customer") $customer_type = "individual";

            $id_no_for_loyalty_check = (isset($_REQUEST['id_no_for_loyalty_check']) && $_REQUEST['id_no_for_loyalty_check'] != '' ? $_REQUEST['id_no_for_loyalty_check'] : 0);
            $id_no_for_loyalty_check = custom::convertArabicNumbersToEnglish($id_no_for_loyalty_check);
            $errorMsg = "An Error Has Occurred.";
            $type = $_REQUEST['type'];
            $posted_user_id = custom::jwt_decode($_REQUEST['user_id']);
            if ($type == "ios" && $id_no_for_loyalty_check > 0) {
                $posted_user_id = 0;
            }
            if (strpos($_REQUEST['pickup_time'], 'ص') !== false || strpos($_REQUEST['pickup_time'], 'م') !== false || strpos($_REQUEST['dropoff_time'], 'ص') !== false || strpos($_REQUEST['dropoff_time'], 'م') !== false) {
                $_REQUEST['pickup_time'] = $pickup_time = custom::convertEngTimeToArbTime($_REQUEST['pickup_time'], $type);
                $_REQUEST['dropoff_time'] = $dropoff_time = custom::convertEngTimeToArbTime($_REQUEST['dropoff_time'], $type);
            } else {
                $_REQUEST['pickup_time'] = $pickup_time = $_REQUEST['pickup_time'];
                $_REQUEST['dropoff_time'] = $dropoff_time = $_REQUEST['dropoff_time'];
            }

            $is_delivery_mode = (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] != '' ? $_REQUEST['is_delivery_mode'] : 0);

            $search_by['book_for_hours'] = $book_for_hours = (isset($_REQUEST['book_for_hours']) && $_REQUEST['book_for_hours'] != '' ? $_REQUEST['book_for_hours'] : 0);

            if ($is_delivery_mode == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($_REQUEST['subscribe_for_months']) && $_REQUEST['subscribe_for_months'] != '' ? $_REQUEST['subscribe_for_months'] : 0);
            if ($is_delivery_mode == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $branchInfo = $this->page->getSingle('branch', array('id' => $_REQUEST['branch_id_from']));

            if ($is_delivery_mode == 1) {
                /*$days_allowed = ((int)$site_settings->hours_before_delivery > 0 ? (int)$site_settings->hours_before_delivery : '0');*/
                $days_allowed = ((int)$branchInfo->hours_before_delivery > 0 ? (int)$branchInfo->hours_before_delivery : '0');
            } elseif ($is_delivery_mode == 4) {
                /*$days_allowed = ((int)$site_settings->reservation_before_hours_for_subscription > 0 ? (int)$site_settings->reservation_before_hours_for_subscription : '0');*/
                $days_allowed = ((int)$branchInfo->reservation_before_hours_for_subscription > 0 ? (int)$branchInfo->reservation_before_hours_for_subscription : '0');
            } else {
                /*$days_allowed = ((int)$site_settings->reservation_before_hours > 0 ? (int)$site_settings->reservation_before_hours : '0');*/
                $days_allowed = ((int)$branchInfo->reservation_before_hours > 0 ? (int)$branchInfo->reservation_before_hours : '0');
            }


            $search_by['region_id'] = $_REQUEST['region_id'];
            $search_by['city_id'] = $_REQUEST['city_id'];
            $search_by['branch_id'] = $_REQUEST['branch_id_from'];
            $search_by['pickup_date'] = date('Y-m-d', strtotime($_REQUEST['pickup_date']));
            $pickup_date_for_search = date('Y-m-d H:i:s', strtotime($_REQUEST['pickup_date']));
            $search_by['dropoff_date'] = date('Y-m-d', strtotime($_REQUEST['dropoff_date']));
            $search_by['customer_type'] = $customer_type; // Individual, Corporate

            /*here get company_code for corporate user to get corporate prices with country_code*/
            if ($customer_type == 'Corporate') {
                $customerInfo = $this->page->getSingle('corporate_customer', array('id' => $posted_user_id));

                if ($customerInfo && $customerInfo->company_code != '') {
                    $search_by['company_code'] = $customerInfo->company_code;
                }
            }

            //$search_by['customer_type'] = 'Individual'; // Individual, Corporate // 12 Nov 2017, As issue in IOS app, Fozan raised a bug on sunday
            $search_by['category'] = ($_REQUEST['category'] > 0 ? $_REQUEST['category'] : ""); // for 'All' it will be empty

            if (isset($_REQUEST['price_sort'])) {
                $car_price_sort = $_REQUEST['price_sort']; // asc, desc
            } else {
                $car_price_sort = 'asc';
            }

            $ignoreHoursForBronze = $site_settings->ignore_hours_bronze;
            $ignoreHoursForGuestSilver = $site_settings->ignore_hours_silver;
            $ignoreHoursForGolden = $site_settings->ignore_hours_golden;
            $ignoreHoursForPlatinum = $site_settings->ignore_hours_platinum;

            if ($posted_user_id == 0) {
                $ignoreHours = $ignoreHoursForBronze;
            } elseif ($posted_user_id > 0) {
                if ($customer_type == 'Corporate') {
                    $customerInfo = $this->page->getSingle('corporate_customer', array('id' => $posted_user_id));
                    $loyalty_card_type = $customerInfo->membership_level;
                } else {
                    $customerInfo = $this->page->getSingle('individual_customer', array('id' => $posted_user_id));
                    $loyalty_card_type = $customerInfo->loyalty_card_type;
                }
                if ($loyalty_card_type == 'Bronze') {
                    $ignoreHours = $ignoreHoursForBronze;
                } elseif ($loyalty_card_type == 'Silver') {
                    $ignoreHours = $ignoreHoursForGuestSilver;
                } elseif ($loyalty_card_type == 'Golden') {
                    $ignoreHours = $ignoreHoursForGolden;
                } elseif ($loyalty_card_type == 'Platinum') {
                    $ignoreHours = $ignoreHoursForPlatinum;
                } elseif ($loyalty_card_type == '') {
                    $ignoreHours = $ignoreHoursForBronze;
                }
            } else {
                $ignoreHours = $ignoreHoursForBronze;
            }

            $original_DOD = date('Y-m-d H:i:s', strtotime($_REQUEST['dropoff_date'] . ' ' . $dropoff_time));
            $manupulatedTime = date('Y-m-d H:i:s', strtotime($original_DOD) - 60 * 60 * $ignoreHours);
            $manupulatedTime = explode(' ', $manupulatedTime);
            //===
            $tempDOD = date('Y-m-d', strtotime($manupulatedTime[0]));
            $tempDOT = date('H:i:s', strtotime($manupulatedTime[1]));

            $no_of_days = custom::getCheckoutDays($_REQUEST['pickup_date'] . ' ' . $pickup_time, $tempDOD . ' ' . $tempDOT);
            if ($is_delivery_mode == 4) {
                $no_of_days = 30;
            }

            $today_for_com = Carbon::now();
            $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            $pick_for_com_mins = $today_for_com->diffInMinutes($date_picked_up);
            $dropoff_for_com_mins = $today_for_com->diffInMinutes($date_dropped_off);
            if ($dropoff_for_com_mins >= $pick_for_com_mins) {
                if ((int)$no_of_days === 0) $no_of_days = 1;
            }


            $search_by['days'] = $no_of_days;

            // echo $pickup_time. ' - '.$dropoff_time;die();

            $search_by['is_delivery_mode'] = $is_delivery_mode;
            $date_picked_up_for_hours_cal = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off_for_hours_cal = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            $search_by['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

            // echo $search_by['hours_diff'];die();

            if (isset($_REQUEST['mod_id']) && null !== $_REQUEST['mod_id']) {
                $modelId = $_REQUEST['mod_id'];
            }

            // if he is coming as logged in user
            if ($posted_user_id > 0) {
                if ($customer_type == 'Corporate') {
                    $customerInfo = $this->page->getSingle('corporate_customer', array('id' => $posted_user_id));
                    $loyalty_type = $customerInfo->membership_level;
                    $customer_type_for_loyalty = 'corporate_customer';
                } else {
                    $userDataForLoyalty = $this->page->getSingle('individual_customer', array('id' => $posted_user_id));
                    $loyalty_type = $userDataForLoyalty->loyalty_card_type;
                    $customer_type_for_loyalty = 'individual_customer';
                }
                $loyalty_card_applicable = $this->page->getLoyaltyInfo($no_of_days, $loyalty_type, $customer_type_for_loyalty);
                /*add check here if not data come*/
                if ($loyalty_card_applicable) {
                    $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                } else {
                    $loyalty_discount_percent = 0;
                }

            } elseif ($id_no_for_loyalty_check > 0) { // if he is checking his loyalty by ID number popup in search cars screen
                $getUserDataForLoyalty = $this->page->getSingle('individual_customer', array('id_no' => $id_no_for_loyalty_check));
                if ($getUserDataForLoyalty) {
                    $customer_id_for_checkout_screen = $getUserDataForLoyalty->id;

                    if ($customer_type == 'Corporate') {
                        $customer_type_for_loyalty = 'corporate_customer';
                    } else {
                        $customer_type_for_loyalty = 'individual_customer';
                    }
                    $loyalty_card_applicable = $this->page->getLoyaltyInfo($no_of_days, $getUserDataForLoyalty->loyalty_card_type, $customer_type_for_loyalty);
                    $loyalty_type = $getUserDataForLoyalty->loyalty_card_type;

                    /*add check here if not data come*/
                    if ($loyalty_card_applicable) {
                        $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                    } else {
                        $loyalty_discount_percent = 0;
                    }

                } else {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'User Not Found With This ID No.' : 'لايوجد عميل برقم الهوية المدخل');
                    $response['loyalty_applied'] = 0;
                    $response['response']['cars'] = "";
                    //$response['response']['categories'] = $this->page->getAll('car_category', 'sort_col');
                    $response['response']['categories'] = $this->page->getAllCarCategories();
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } else {
                if ($customer_type == 'Corporate') {
                    $customer_type_for_loyalty = 'corporate_customer';
                } else {
                    $customer_type_for_loyalty = 'individual_customer';
                }
                $loyalty_type = 'Bronze';
                $loyalty_card_applicable = $this->page->getLoyaltyInfo($no_of_days, $loyalty_type, $customer_type_for_loyalty);

                /*add check here if not data come*/
                if ($loyalty_card_applicable) {
                    $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                } else {
                    $loyalty_discount_percent = 0;
                }

            }

            // checking if delivery slots are ok for delivery branch
            $from_branch_id_for_checking_slot = $_REQUEST['branch_id_from'];
            $branch_data_for_slot = $this->page->getSingle('branch', array('id' => $from_branch_id_for_checking_slot));
            if ($is_delivery_mode == 1) {
                if ($branch_data_for_slot->capacity_mode == 'on') {
                    $min_diff = $branch_data_for_slot->hours_for_delivery * 60; // converting hours to minutes and getting before and after time interval.
                    $pickup_date_time = $_REQUEST['pickup_date'] . ' ' . $pickup_time;
                    $time = strtotime($pickup_date_time);
                    $before_time = date("Y-m-d H:i:s", strtotime('-' . $min_diff . ' minutes', $time));
                    $after_time = date("Y-m-d H:i:s", strtotime('+' . $min_diff . ' minutes', $time));
                    $slot_capacity = $branch_data_for_slot->capacity;

                    $countOfBookingsInTheTimeSlot = $this->page->getCountOfBookingsInTimeInterval($from_branch_id_for_checking_slot, $before_time, $after_time);
                    if ($countOfBookingsInTheTimeSlot >= $slot_capacity) {
                        $delivery_slots_are_ok = false;

                    }
                }
            }

            if ($is_delivery_mode == 1) // only checking if he is coming through delivery tab
            {
                // To check if delivery time is fine as per database allowed time
                // To check booking time is fine as per database allowed time
                $today = Carbon::now();
                $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
                // no of mins in 1 day: 1440
                /*$no_of_secs_from_db = (int)$site_settings->hours_before_delivery * 60 * 60;*/

                $no_of_secs_from_db = (int)$branchInfo->hours_before_delivery * 60 * 60;

                if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                    $no_of_hours_are_fine = true;
                }
            } elseif ($is_delivery_mode == 4) // only checking if he is coming through delivery tab
            {
                // To check if delivery time is fine as per database allowed time
                // To check booking time is fine as per database allowed time
                $today = Carbon::now();
                $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
                // no of mins in 1 day: 1440
                /*$no_of_secs_from_db = (int)$site_settings->hours_before_delivery * 60 * 60;*/

                /* $no_of_secs_from_db = (int)$site_settings->reservation_before_hours_for_subscription * 60 * 60;*/
                $no_of_secs_from_db = (int)$branchInfo->reservation_before_hours_for_subscription * 60 * 60;

                if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                    $no_of_hours_are_fine = true;
                }
            } else {

                // To check booking time is fine as per database allowed time
                $today = Carbon::now();
                $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
                // no of mins in 1 day: 1440

                /* $no_of_secs_from_db = (int)$site_settings->reservation_before_hours * 60 * 60;*/
                $no_of_secs_from_db = (int)$branchInfo->reservation_before_hours * 60 * 60;

                if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                    $no_of_hours_are_fine = true;
                }
            }

            // To check if dropoff date time is ahead of pickup date time
            $today_for_com = Carbon::now();
            $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            $pick_for_com_mins = $today_for_com->diffInMinutes($date_picked_up);
            $dropoff_for_com_mins = $today_for_com->diffInMinutes($date_dropped_off);
            if ($dropoff_for_com_mins > $pick_for_com_mins) {
                $difference_is_fine = true;
            }

            // To check if pickup and dropoff time ahead of current time
            $today_for_com = Carbon::now();
            $date_picked_up = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            if (($date_picked_up > $today_for_com) && ($date_dropped_off > $today_for_com)) {
                $pickup_dropoff_are_ahead_of_current_time = true;
            }

            // Code for branch open / closed logic
            $frm_brnch = $_REQUEST['branch_id_from'];
            $pickup_dt = $_REQUEST['pickup_date'];
            $pickup_day = date('l', strtotime($pickup_dt));
            $pickup_tm = date('H:i', strtotime($pickup_time));

            $to_brnch = $_REQUEST['branch_id_to'];
            $drpoff_dt = $_REQUEST['dropoff_date'];
            $drpoff_day = date('l', strtotime($drpoff_dt));
            $drpoff_tm = date('H:i', strtotime($dropoff_time));

            // checking if time is ok for pickup branch and dropoff branch
            $pickup_schedule_is_ok = $this->page->checkIfBranchIsOpen($frm_brnch, $pickup_day, $pickup_tm, $pickup_dt, ($is_delivery_mode == 1));
            $dropoff_schedule_is_ok = $this->page->checkIfBranchIsOpen($to_brnch, $drpoff_day, $drpoff_tm, $drpoff_dt, ($is_delivery_mode == 1));

            if ($pickup_schedule_is_ok) {
                $pickup_time_is_ok = true;
            }

            if ($dropoff_schedule_is_ok) {
                $dropoff_time_is_ok = true;
            }

            $from_branch_info = $this->page->getSingle('branch', array('id' => $_REQUEST['branch_id_from']));
            $to_branch_info = $this->page->getSingle('branch', array('id' => $_REQUEST['branch_id_to']));

            $siteSettings = custom::site_settings();
            $isRangeOn = $siteSettings->date_range_mode == 'on' ? true : false;
            $startRange = date('d-m-Y', strtotime($siteSettings->start_date));
            $endRange = date('d-m-Y', strtotime($siteSettings->end_date));
            if ($isRangeOn) {
                $dateRangeHours = $startRange . ' - ' . $endRange . ': ' . $from_branch_info->opening_hours_date_range;
            } else {
                $dateRangeHours = '';
            }

            if ($site_settings->monthly_renting_mode_mobile == 'on' && $is_delivery_mode == 3) {
                $date_picked_up_for_monthly_cal = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $date_dropped_off_for_monthly_cal = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
                $months_diff = $date_dropped_off_for_monthly_cal->diffInDays($date_picked_up_for_monthly_cal);
                if ($months_diff < 30) {
                    $monthly_date_diff_is_fine = false;
                    $expected_dropoff_date = $date_picked_up_for_monthly_cal->addMonth(1)->toDayDateTimeString();
                    $monthly_date_diff_is_fine_msg = ($this->lang == 'eng' ? 'The dropoff date must be a month from pickup date.' : 'تاريخ التسليم يجب ان يكون شهر من تاريخ الاستلام');
                }
            }

            if ($no_of_hours_are_fine == false || $difference_is_fine == false || $pickup_time_is_ok == false || $dropoff_time_is_ok == false || $pickup_dropoff_are_ahead_of_current_time == false || $delivery_slots_are_ok == false || $monthly_date_diff_is_fine == false) {
                if ($pickup_dropoff_are_ahead_of_current_time == false) {
                    $errorMsg = ($this->lang == 'eng' ? 'Pickup/Dropoff time should be ahead of current date/time.' : 'وقت أو تاريخ الإستلام / التسليم يجب ان يكون بعد الوقت / التاريخ الحالي');
                } elseif ($no_of_hours_are_fine == false) {
                    $errorMsg = ($this->lang == 'eng' ?
                        'Pickup time must be ' . $days_allowed . ' hour(s) ahead.' : ' وقت/تاريخ الإستلام يجب ان يكون بعد ' . $days_allowed . ' ساعة من الوقت الحالي ');
                } elseif ($difference_is_fine == false) {
                    $errorMsg = ($this->lang == 'eng' ? 'The dropoff time should be ahead of pickup time.' : 'وقت/تاريخ التسليم يجب ان يكون بعد وقت/تاريخ الإستلام');
                } elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == true) {
                    $errorMsg = ($this->lang == 'eng' ?
                        'The pickup branch is close at the selected pickup time. \n Working Hours: ' . $from_branch_info->opening_hours . ' \n ' . $dateRangeHours . ''
                        : 'فرع الاستلام غير متوفر في الوقت المختار ساعات العمل: ' . $from_branch_info->opening_hours . ' \n ' . $dateRangeHours . '');
                } elseif ($pickup_time_is_ok == true && $dropoff_time_is_ok == false) {
                    $errorMsg = ($this->lang == 'eng' ?
                        'The dropoff branch is close at the selected dropoff time. \n Working Hours: ' . $to_branch_info->opening_hours . ' \n ' . $dateRangeHours . ''
                        : 'فرع التسليم غير متوفر في الوقت المختار ساعات العمل: ' . $to_branch_info->opening_hours . ' \n ' . $dateRangeHours . '');
                } elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == false) {
                    $errorMsg = ($this->lang == 'eng' ?
                        'The pickup and dropoff branches are closed at the selected pickup and dropoff time. \n Pickup Branch Working Hours: ' . $from_branch_info->opening_hours . ' \n Dropoff Branch Working Hours: ' . $to_branch_info->opening_hours . ' \n ' . $dateRangeHours . '' :
                        'فرع الاستلام و التسليم مغلق في الوقت المختار (ساعات العمل)');
                } elseif ($delivery_slots_are_ok == false) {
                    $errorMsg = ($this->lang == 'eng' ? $branch_data_for_slot->eng_capacity_message : $branch_data_for_slot->arb_capacity_message);
                } elseif ($monthly_date_diff_is_fine == false) {
                    $errorMsg = $monthly_date_diff_is_fine_msg;
                }
                $response['status'] = 0;
                $response['message'] = $errorMsg;
                $response['loyalty_applied'] = 1;
                $response['response']['cars'] = "";
                //$response['response']['categories'] = $this->page->getAll('car_category', 'sort_col');
                $response['response']['categories'] = $this->page->getAllCarCategories();
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($no_of_hours_are_fine == true && $difference_is_fine == true && $pickup_time_is_ok == true && $dropoff_time_is_ok == true && $pickup_dropoff_are_ahead_of_current_time == true && $delivery_slots_are_ok == true && $monthly_date_diff_is_fine == true) {
                if ($search_by['is_delivery_mode'] == 2) // if user is coming from 3rd tab (hour rate) than no loyalty discount for this user
                {
                    $loyalty_discount_percent = "0";
                }
                if (false && isset($_REQUEST['paginate']) && $_REQUEST['paginate'] == 1) { // was doing this pagination
                    $limit = 5;

                    $page = (isset($_REQUEST['page']) ? $_REQUEST['page'] : 1);
                    $offset = ($page - 1) * $limit;
                }
                $car_ids_already_shown = (isset($_REQUEST['car_ids']) && $_REQUEST['car_ids'] != '' ? $_REQUEST['car_ids'] : false);
                $cars = $this->services->getAllCarModels($search_by, 'rent', $modelId, $offset, $limit, $loyalty_discount_percent, $car_price_sort, $car_ids_already_shown);
                if ($cars) {

                    /*this functionality to get pickup and current date difference to check booking days limit */
                    $start = date('Y-m-d');
                    $pickup_date = date('Y-m-d', strtotime($search_by['pickup_date']));
                    $dateDiff = custom::getDifferenceInDates($start, $pickup_date, 'days');
                    /*this variable is use in below checkBookingDaysLimit() function but get here once
                     before loop, to avoid each time execution in loop*/

                    $i = 0;

                    $arrData['region_id'] = $search_by['region_id'];
                    $arrData['city_id'] = $search_by['city_id'];
                    $arrData['pickup_date'] = $search_by['pickup_date'];
                    $arrData['dropoff_date'] = $search_by['dropoff_date'];

                    $avail_array_return = custom::checkIfModelExistGetInArray($arrData);

                    $avail_array_car_ids = $avail_array_return[0];
                    $avail_array_per_day = $avail_array_return[1];

                    $avail_array_utilization_percentage_1 = $avail_array_return[2];
                    $avail_array_increase_price_percentage_1 = $avail_array_return[3];

                    $avail_array_utilization_percentage_2 = $avail_array_return[4];
                    $avail_array_increase_price_percentage_2 = $avail_array_return[5];

                    $avail_array_utilization_percentage_3 = $avail_array_return[6];
                    $avail_array_increase_price_percentage_3 = $avail_array_return[7];

                    $car_ids_for_availability = array();
                    foreach ($cars as $car) {
                        $car_ids_for_availability[] = $car->car_id;
                    }

                    $arrDataBookings['city_id'] = $search_by['city_id'];
                    $arrDataBookings['pickup_date'] = $search_by['pickup_date'];
                    $arrDataBookings['car_ids_for_availability'] = implode(',', $car_ids_for_availability);

                    $availabilityBookings = custom::checkCarAvailabilityBookings($arrDataBookings);

                    $booking_car_models = array();
                    $booking_car_models_count = array();
                    foreach ($availabilityBookings as $availabilityBooking) {
                        $booking_car_models[] = $availabilityBooking->car_model_id;
                        $booking_car_models_count[] = $availabilityBooking->count;
                    }

                    foreach ($cars as $car) {

                        $car_rate_is_with_additional_utilization_rate = 0;

                        /*checking if car is available for booking, this will return true or false*/
                        $booking_per_day_found_bookings = 0;
                        $booking_car_id_key = array_search($car->car_id, $avail_array_car_ids);
                        $booking_per_day_found = $avail_array_per_day[$booking_car_id_key];
                        if ($booking_per_day_found !== false) {
                            if ($booking_per_day_found) {
                                $booking_car_id_key_booking = array_search($car->car_id, $booking_car_models);
                                if ($booking_car_id_key_booking !== false) {
                                    $booking_per_day_found_bookings = $booking_car_models_count[$booking_car_id_key_booking];
                                }

                                if ($booking_per_day_found_bookings < $booking_per_day_found) {

                                    $availability = true;

                                    /*We will overwrite price and old price here depending upon car utilization*/

                                    if ($site_settings->car_utilization_mode_mobile == 'on') {

                                        $price = $car->discounted_price;
                                        $old_price = $car->actual_price;

                                        $no_of_bookings_allowed_per_day = $booking_per_day_found;
                                        $no_of_currently_active_bookings = $booking_per_day_found_bookings;

                                        $utilization_percentage_1 = $avail_array_utilization_percentage_1[$booking_car_id_key];
                                        $increase_price_percentage_1 = $avail_array_increase_price_percentage_1[$booking_car_id_key];

                                        $utilization_percentage_2 = $avail_array_utilization_percentage_2[$booking_car_id_key];
                                        $increase_price_percentage_2 = $avail_array_increase_price_percentage_2[$booking_car_id_key];

                                        $utilization_percentage_3 = $avail_array_utilization_percentage_3[$booking_car_id_key];
                                        $increase_price_percentage_3 = $avail_array_increase_price_percentage_3[$booking_car_id_key];

                                        if ($utilization_percentage_1 > 0 && $increase_price_percentage_1 > 0) {
                                            $bookings_allowed_as_per_factor_1 = ($utilization_percentage_1 / 100) * $no_of_bookings_allowed_per_day;
                                            if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_1) {
                                                $car->discounted_price = (string)($price + (($increase_price_percentage_1 / 100) * $price));
                                                $car->actual_price = (string)($old_price + (($increase_price_percentage_1 / 100) * $old_price));
                                                $car_rate_is_with_additional_utilization_rate = 1;
                                            }
                                        }

                                        if ($utilization_percentage_2 > 0 && $increase_price_percentage_2 > 0) {
                                            $bookings_allowed_as_per_factor_2 = ($utilization_percentage_2 / 100) * $no_of_bookings_allowed_per_day;
                                            if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_2) {
                                                $car->discounted_price = (string)($price + (($increase_price_percentage_2 / 100) * $price));
                                                $car->actual_price = (string)($old_price + (($increase_price_percentage_2 / 100) * $old_price));
                                                $car_rate_is_with_additional_utilization_rate = 1;
                                            }
                                        }

                                        if ($utilization_percentage_3 > 0 && $increase_price_percentage_3 > 0) {
                                            $bookings_allowed_as_per_factor_3 = ($utilization_percentage_3 / 100) * $no_of_bookings_allowed_per_day;
                                            if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_3) {
                                                $car->discounted_price = (string)($price + (($increase_price_percentage_3 / 100) * $price));
                                                $car->actual_price = (string)($old_price + (($increase_price_percentage_3 / 100) * $old_price));
                                                $car_rate_is_with_additional_utilization_rate = 1;
                                            }
                                        }

                                    }

                                } else {
                                    $availability = false;
                                }


                            } else {
                                $availability = false;
                            }
                        } else {
                            $availability = true;
                        }


                        // check if car has any redeem offer setup and active against it. Criteria for checking is as follows:
                        // 1. It must be having the from region id as region id.
                        // 2. It must be having the car id thats in search results.
                        // 3. Percentage of open contracts, means to check how many number of open contracts are there for this car model, it must be less than prescribed ones.
                        if ($site_settings->redeem_offer_mode == 'on') { // if redeem offer mode is enabled from backend
                            // $sessionVals['from_region_id'], $car->car_id
                            $car_info = $this->page->getSingle('car_model', array('id' => $car->car_id));
                            $redeemOfferAvailable = $this->page->checkIfRedeemAllowed($search_by['region_id'], $car_info->car_type_id, $car->car_id, $search_by['pickup_date']);
                            if ($redeemOfferAvailable) {
                                $no_of_currently_open_contracts = $this->page->getNoOfOpenContracts($car->car_id, $search_by['region_id']); // Getting number of open contracts for the car model at present.
                                $no_of_cars_present = $redeemOfferAvailable->no_of_cars_present;
                                $percentage_of_open_contracts = $redeemOfferAvailable->percentage_of_open_contracts;
                                $no_of_open_contracts_allowed = ($percentage_of_open_contracts / 100) * $no_of_cars_present;
                                if ($no_of_currently_open_contracts < $no_of_open_contracts_allowed) {
                                    $redeem_offer_id = $redeemOfferAvailable->id;
                                } else {
                                    $redeem_offer_id = 0;
                                }
                            } else {
                                $redeem_offer_id = 0;
                            }
                        } else {
                            $redeem_offer_id = 0;
                        }

                        if ($availability) {
                            $booking_availability = 1;
                            $avail_msg = '';
                            /*this is for booking_days_limit if availability is true*/
                            $days_limit = custom::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                            if ($days_limit) {
                                $day_limit = 1;
                                $limit_msg = '';
                            } else {
                                $day_limit = 0;
                                if ($this->lang == 'eng') {
                                    $newMsg = Lang::get('labels.booking_day_limit_label');
                                } else {
                                    $newMsg = str_replace("days", $car->booking_days_limit, Lang::get('labels.booking_day_limit_label'));
                                }
                                $limit_msg = $newMsg;
                            }
                        } else {
                            $day_limit = 1;
                            $limit_msg = '';
                            $booking_availability = 0;
                            $avail_msg = Lang::get('labels.sold_out');
                        }
                        /*end new logics*/

                        $type_of_discount = '% ';
                        $new_rent_per_day = round($car->discounted_price, 2);
                        $old_rent_per_day = round($car->actual_price, 2);
                        $discount_percentage = $loyalty_disc_per = $car->loyalty_discount_percentage;
                        $promotion_offer_id = 0;

                        $promo_discount = $this->page->checkAutoPromoDiscount($car->car_id, $pickup_date_for_search, $search_by['region_id'], $search_by['city_id'], $search_by['branch_id'], $search_by['days'], $customer_type, $is_delivery_mode);

                        $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount, $search_by['pickup_date']);

                        $is_promotion_auto_apply = false;

                        if ($promo_discount && $promo_discount->type == 'Percentage Auto Apply' && $coupon_is_valid_for_pickup_day) {
                            $is_promotion_auto_apply = true;
                            $promo_discount_percent = $promo_discount->discount;

                            if ($promo_discount_percent > 0 && $promo_discount_percent > $discount_percentage) {
                                $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                                $new_rent_per_day = $old_rent_per_day - $discount_amount;
                                $new_rent_per_day = round($new_rent_per_day, 2);
                                $discount_percentage = (float)$promo_discount_percent;
                                $promotion_offer_id = $promo_discount->id;
                            }
                        } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply on Loyalty' && $coupon_is_valid_for_pickup_day) {
                            $is_promotion_auto_apply = true;
                            $promo_discount_percent = $promo_discount->discount + $discount_percentage;

                            $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                            $new_rent_per_day = $old_rent_per_day - $discount_amount;
                            $new_rent_per_day = round($new_rent_per_day, 2);
                            $discount_percentage = (float)$promo_discount_percent;
                            $promotion_offer_id = $promo_discount->id;
                        } elseif ($promo_discount && $promo_discount->type == 'Fixed Price Auto Apply' && $coupon_is_valid_for_pickup_day) {
                            $is_promotion_auto_apply = true;
                            // $new_rent_per_day -> This is after promo discount
                            $after_fixed_price_discount = round($old_rent_per_day - $promo_discount->discount, 2);

                            // echo $after_fixed_price_discount .' '. $new_rent_per_day;exit();

                            if ($after_fixed_price_discount < $new_rent_per_day) {
                                $discount_amount = $promo_discount->discount;
                                $new_rent_per_day = $old_rent_per_day - $discount_amount;
                                $new_rent_per_day = round($new_rent_per_day, 2);
                                $discount_percentage = ((float)$discount_amount / $old_rent_per_day) * 100;
                                $promotion_offer_id = $promo_discount->id;
                                //$type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';

                            }

                        } elseif ($promo_discount && $promo_discount->type == 'Fixed Daily Rate Auto Apply' && $coupon_is_valid_for_pickup_day) {
                            $is_promotion_auto_apply = true;
                            $after_fixed_price_discount = $promo_discount->discount;

                            if ($after_fixed_price_discount < $new_rent_per_day) {
                                $discount_amount = $promo_discount->discount;
                                $new_rent_per_day = round($promo_discount->discount, 2);
                                //$discount_percentage = ($old_rent_per_day - $discount_amount)*100;
                                $discount_percentage = round((($old_rent_per_day - $discount_amount) / $old_rent_per_day) * 100, 2);
                                $promotion_offer_id = $promo_discount->id;
                                //$type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';
                            }
                        }

                        $cars[$i]->type_of_discount = $type_of_discount;
                        $cars[$i]->discounted_price = $new_rent_per_day;
                        $cars[$i]->loyalty_discount_percentage = "$discount_percentage"; // this is bascially loyalty_discount_percentage and if there is any auto apply coupon being applied above than this values gets overwrite
                        $cars[$i]->actual_loyalty_discount_percentage = "$loyalty_disc_per"; // had to do this for loyalty_discount_percentage because above key is already used in apps so had to make a new key for apps
                        $cars[$i]->promotion_offer_id = $promotion_offer_id;
                        $cars[$i]->redeem_offer_id = $redeem_offer_id;
                        /*new parames for check booking avail and max days*/
                        $cars[$i]->availability = $booking_availability;
                        $cars[$i]->avail_msg = $avail_msg;
                        $cars[$i]->day_limit = $day_limit;
                        $cars[$i]->day_limit_msg = $limit_msg;
                        $cars[$i]->no_of_hours = $search_by['hours_diff'];
                        $cars[$i]->car_rate_is_with_additional_utilization_rate = $car_rate_is_with_additional_utilization_rate;
                        /*end new params*/

                        $cars[$i]->is_special_car = ($car->is_special_car == 'yes' && $is_promotion_auto_apply ? 'yes' : 'no');
                        $cars[$i]->special_car_image = custom::baseurl('public/frontend/images/' . ($this->lang == 'eng' ? 'eng_specialFeature.png' : 'arb_specialFeature.png'));

                        $i++;
                    }
                    //echo "<pre>";print_r($cars);exit();
                    $cars = custom::isNullToEmpty($cars);

                    $response['status'] = 1;
                    $response['message'] = "";
                    $response['loyalty_applied'] = 1;
                    $response['response']['customer_id'] = ($customer_id_for_checkout_screen > 0 ? custom::jwt_encode($customer_id_for_checkout_screen) : $customer_id_for_checkout_screen);
                    $response['response']['loyalty_type'] = $loyalty_type;
                    $response['response']['renting_type_id'] = $cars[0]->renting_type_id;
                    $response['response']['cars'] = $cars;
                    //$response['response']['categories'] = $this->page->getAll('car_category', 'sort_col');
                    $response['response']['categories'] = $this->page->getAllCarCategories();

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'No Record Found.' : 'لايوجد نتائج');
                    $response['loyalty_applied'] = 1;
                    $response['response']['cars'] = "";
                    //$response['response']['categories'] = $this->page->getAll('car_category', 'sort_col');
                    $response['response']['categories'] = $this->page->getAllCarCategories();

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function login()
    {
        // this is added just for the time being as the DB is crashing and hanging
        /*$response['status'] = 0;
        $response['message'] = ($this->lang == 'eng' ? 'Sorry we can\'t log you into the system.' : 'عذرا لا يمكن إنشاء حساب');
        $response['response'] = "";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();*/

        // change
        try {
            $response = array();
            $this->checkIfMaintenanceModeOn();
            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];

            $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('email' => $username));
            if ($checkIfUserIsBlacklist) {

                $user = $this->page->getSingle('users', array('id' => $checkIfUserIsBlacklist->uid));

                if ($user->active_status == 'inactive' || $user->is_email_verified == 0 || $user->is_phone_verified == 0) {
                    $response['status'] = 0;
                    $response['message'] = \Lang::get('labels.incorrect_user_password_msg');
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }

                if ($checkIfUserIsBlacklist->black_listed == "Y") {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'Sorry we can\'t log you into the system.' : 'عذرا لا يمكن إنشاء حساب');
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
            $fetch_by['email'] = $username;
            $fetch_by['password'] = $password = md5($password);

            /*if (strpos($username, '@') !== false) {
                $user = $this->page->getSingle('users', $fetch_by);
            } else {
                // Checking user with ID no.
                $user = $this->page->checkLoginUserByIdNo($username, $password);
            }*/
            $user = $this->page->validate_user($username, $password);
            if (!$user) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.incorrect_user_password_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                if ($user->type == 'individual_customer') {
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    $user_bookings_count = $this->page->getUserBookingsCount($user->id);
                    $user_data = (array)$this->services->getUserInfo($individual_user->id);
                    $user_data = custom::isNullToEmpty($user_data);
                    $user_data['mobile_no'] = custom::getPhoneNumber($user_data['mobile_no']);
                    $response['status'] = 1;
                    $response['message'] = \Lang::get('labels.login_successfully_msg');
                    $response['response'] = array('user_id' => $individual_user->id, 'user_bookings_count' => $user_bookings_count, "logged_in_customer_type" => "Individual", "customer_type_flag" => 0);
                    $response['user_data'] = $user_data;
                    $response['user_data']['user_bookings_count'] = $user_bookings_count;
                    $response['user_data']['loyalty_card_type'] = ($user_data['loyalty_card_type'] != '' ? $user_data['loyalty_card_type'] : 'Bronze');
                    $response['user_data']['jwt_token'] = custom::jwt_encode($individual_user->id);
                } elseif ($user->type == 'corporate_customer') {
                    $corporate_user = $this->page->getSingle('corporate_customer', array('uid' => $user->id));

                    if ($corporate_user->active_status == 'inactive') {
                        $response['status'] = 0;
                        $response['message'] = \Lang::get('labels.incorrect_user_password_msg');
                        $response['response'] = "";
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                    $user_bookings_count = $this->page->getCorporateUserBookingsCount($user->id);
                    $user_data = (array)$this->services->getCorporateUserInfo($corporate_user->id);
                    $user_data = custom::isNullToEmpty($user_data);
                    $user_data['primary_phone'] = custom::getPhoneNumber($user_data['primary_phone']);
                    if ($user_data['secondary_phone'] != "") {
                        $user_data['secondary_phone'] = custom::getPhoneNumber($user_data['secondary_phone']);
                    }
                    $response['status'] = 1;
                    $response['message'] = \Lang::get('labels.login_successfully_msg');
                    $response['response'] = array('user_id' => $corporate_user->id, 'user_bookings_count' => $user_bookings_count, 'logged_in_customer_type' => 'Corporate', 'customer_type_flag' => 1);
                    $response['user_data'] = $user_data;
                    $response['user_data']['username'] = $user->email;
                    $response['user_data']['user_bookings_count'] = $user_bookings_count;
                    $response['user_data']['loyalty_card_type'] = ($user_data['membership_level'] != '' ? $user_data['membership_level'] : 'Bronze');
                    $response['user_data']['jwt_token'] = custom::jwt_encode($corporate_user->id);
                }

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getUserInfo()
    {
        try {
            // change
            $user_bookings_count = 0;
            $individual_customer_id = custom::jwt_decode($_REQUEST['user_id']);
            $customer_type = (isset($_REQUEST['customer_type']) && $_REQUEST['customer_type'] != '' ? $_REQUEST['customer_type'] : 'Individual'); // Corporate, Individual
            if ($customer_type == "Corporate") {
                $data['user_data'] = (array)$this->services->getCorporateUserInfo($individual_customer_id);
                $data['user_data'] = custom::isNullToEmpty($data['user_data']);
                $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
                $data['user_data']['loyalty_card_type'] = ($data['user_data']['membership_level'] != '' ? $data['user_data']['membership_level'] : 'Bronze');
                $data['user_data']['primary_phone'] = custom::getPhoneNumber($data['user_data']['primary_phone']);
                if ($data['user_data']['secondary_phone'] != "") {
                    $data['user_data']['secondary_phone'] = custom::getPhoneNumber($data['user_data']['secondary_phone']);
                }

                if ($data['user_data']['uid'] > 0) {
                    $user_bookings_count = $this->page->getCorporateUserBookingsCount($data['user_data']['uid']);
                    $userInfo = $this->page->getSingle('users', array('id' => $data['user_data']['uid']));
                    $data['user_data']['username'] = $userInfo->email;
                }
            } else {
                $data['user_data'] = (array)$this->services->getUserInfo($individual_customer_id, $customer_type);
                $data['user_data'] = custom::isNullToEmpty($data['user_data']);
                $data['user_data']['mobile_no'] = isset($data['user_data']['mobile_no']) ? custom::getPhoneNumber($data['user_data']['mobile_no']) : '';
                $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
                $data['user_data']['loyalty_card_type'] = ($data['user_data']['loyalty_card_type'] != '' ? $data['user_data']['loyalty_card_type'] : 'Bronze');

                if ($data['user_data']['uid'] > 0) {
                    $user_bookings_count = $this->page->getUserBookingsCount($data['user_data']['uid']);
                }
            }


            if (isset($data['user_data'])) {
                $data['user_data']['user_bookings_count'] = $user_bookings_count;
                $data['user_data']['user_type'] = $customer_type;
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = $data;

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = "Record Not Found For This User.";
                $response['response'] = "";

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function registerNewCustomerAsUser()
    {
        try {

            $_REQUEST['first_name'] = urldecode($_REQUEST['first_name']);
            $_REQUEST['last_name'] = urldecode($_REQUEST['last_name']);

            if (isset($_REQUEST['sponsor']) && $_REQUEST['sponsor']) {
                $_REQUEST['sponsor'] = urldecode($_REQUEST['sponsor']);
            }

            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $_REQUEST['mobile_no'] = trim($_REQUEST['mobile_no']);
            $is_mobile_no_valid = custom::validate_mobile_no($_REQUEST['mobile_no']);
            if (!$is_mobile_no_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_mobile_no_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $this->checkIfMaintenanceModeOn();
            $id_no = $_REQUEST['id_no'];
            $id_no = custom::convertArabicNumbersToEnglish($id_no);
            custom::validateIDNoForMobile($_REQUEST['id_type'], $id_no, $this->lang);
            //issue to fix to make the license number same as id number in case of saudi ID and Iqama for android
            $id_type = $_REQUEST['id_type'] == "null" ? "243" : $_REQUEST['id_type'];
            $gender = $_REQUEST['gender'] == "null" ? "male" : $_REQUEST['gender'];

            $password = $_REQUEST['password'];

            $isPasswordStrong = custom::isPasswordStrong($password, $this->lang);
            if (!$isPasswordStrong['status']) {
                $response['status'] = 0;
                $response['message'] = $isPasswordStrong['message'];
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $confirm_password = $_REQUEST['confirm_password'];
            $email = $_REQUEST['email'];
            $first_name = $_REQUEST['first_name'];
            $last_name = $_REQUEST['last_name'];
            $mobile_no = custom::getPhoneNumber($_REQUEST['mobile_no']);
            $mobile_no = $mobile_no['country_code'] . $mobile_no['number'];

            //issue to fix to make the license number same as id number in case of saudi ID and Iqama for android
            $license_no = $_REQUEST['license_no'] == "null" && ($id_type == 243 || $id_type == 68) ? $_REQUEST['id_no'] : $_REQUEST['license_no'];
            $sponsor = (isset($_REQUEST['sponsor']) && $_REQUEST['sponsor'] != '' ? $_REQUEST['sponsor'] : '');

            // Optional Parameters
            $extra_info = $_REQUEST['extra_info'];
            if (isset($extra_info) && $extra_info == 1) {
                $id_country = $_REQUEST['id_country'];
                $license_country = $_REQUEST['license_country'];
                $job_title = $_REQUEST['job_title'];
                $street_address = $_REQUEST['street_address'];
                $district_address = $_REQUEST['district_address'];
                $dob = $_REQUEST['dob'];
                $nationality = $_REQUEST['nationality'];
                $id_date_type = $_REQUEST['id_date_type'];
                $id_expiry_date = $_REQUEST['id_expiry_date'];
                $license_id_type = $_REQUEST['license_id_type'];
                $license_expiry_date = $_REQUEST['license_expiry_date'];
                $payment_method = $_REQUEST['payment_method'];
                // $id_image = $_FILES['id_image'];
                // $license_image = $_FILES['license_image'];
            }

            $this->checkIfUserBlacklistedOrSimahBlock($id_no, $id_type);

            ini_set('max_execution_time', 0);

            if ($password != $confirm_password) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.password_not_match_msg');
                $response['response'] = "";

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $get_user_by_email['email'] = $email;
                $user = $this->page->getSingle('users', $get_user_by_email);
                $individual_customer_by_email = $this->page->getSingle('individual_customer', $get_user_by_email);
                $get_user_by_id_no['id_no'] = $id_no;
                $individual_customer_by_id_no = $this->page->getSingle('individual_customer', $get_user_by_id_no);

                // checking if this customer exists and is already registered in the system but have deleted their account previously
                if ($individual_customer_by_email && $individual_customer_by_email->uid > 0) {

                    $user = $this->page->getSingle('users', ['id' => $individual_customer_by_email->uid]);
                    if ($user && $user->active_status == 'inactive') {
                        $mobile_no = custom::getPhoneNumber($_REQUEST['mobile_no']);
                        $mobile_no = $mobile_no['country_code'] . $mobile_no['number'];

                        // updating data in individual_customer table
                        $u_dta['first_name'] = $_REQUEST['first_name'];
                        $u_dta['last_name'] = $_REQUEST['last_name'];
                        $u_dta['mobile_no'] = $mobile_no;
                        $this->page->updateData('individual_customer', $u_dta, ['email' => $individual_customer_by_email->email]);

                        // updating data in users table
                        $uData['name'] = $u_dta['first_name'] . ' ' . $u_dta['last_name'];
                        $uData['password'] = md5($_REQUEST['password']);
                        $uData['active_status'] = 'active';
                        $uData['updated_at'] = date('Y-m-d H:i:s');
                        $this->page->updateData('users', $uData, ['id' => $individual_customer_by_email->uid]);

                        $response['status'] = 1;
                        $response['message'] = \Lang::get('labels.account_created_msg');
                        $response['response'] = array('user_id' => $individual_customer_by_email);
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }

                }

                if ($individual_customer_by_id_no && $individual_customer_by_id_no->uid > 0) {

                    $user = $this->page->getSingle('users', ['id' => $individual_customer_by_id_no->uid]);
                    if ($user && $user->active_status == 'inactive') {
                        $mobile_no = custom::getPhoneNumber($_REQUEST['mobile_no']);
                        $mobile_no = $mobile_no['country_code'] . $mobile_no['number'];

                        // updating data in individual_customer table
                        $u_dta['first_name'] = $_REQUEST['first_name'];
                        $u_dta['last_name'] = $_REQUEST['last_name'];
                        $u_dta['mobile_no'] = $mobile_no;
                        $this->page->updateData('individual_customer', $u_dta, ['id_no' => $individual_customer_by_id_no->id_no]);

                        // updating data in users table
                        $uData['name'] = $u_dta['first_name'] . ' ' . $u_dta['last_name'];
                        $uData['password'] = md5($_REQUEST['password']);
                        $uData['active_status'] = 'active';
                        $uData['updated_at'] = date('Y-m-d H:i:s');
                        $this->page->updateData('users', $uData, ['id' => $individual_customer_by_id_no->uid]);

                        $response['status'] = 1;
                        $response['message'] = \Lang::get('labels.account_created_msg');
                        $response['response'] = array('user_id' => $individual_customer_by_id_no);
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }

                }

                if ($user && $user->email != '' && $user->type == 'individual_customer') {
                    $response['status'] = 0;
                    $response['message'] = \Lang::get('labels.email_already_register_msg');
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } elseif ($individual_customer_by_id_no && $individual_customer_by_id_no->id_no != '' && (int)$individual_customer_by_id_no->uid !== 0) {
                    $response['status'] = 0;
                    $response['message'] = \Lang::get('labels.id_number_already_register_msg');
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } elseif ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid !== 0) {
                    $response['status'] = 0;
                    $response['message'] = \Lang::get('labels.email_already_register_msg');
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } elseif (
                    ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid === 0)
                    || ($individual_customer_by_id_no && $individual_customer_by_id_no->id_no != '' && (int)$individual_customer_by_id_no->uid === 0)
                ) {
                    // If we fall in this condition it means user had a record as a guest. Updating by email or id no.
                    // the already registered usr check based upon email is already checked in above condition.
                    $userData['name'] = $first_name . ' ' . $last_name;
                    $userData['email'] = $email;
                    $userData['password'] = md5($password);
                    $userData['type'] = 'individual_customer';
                    $userData['created_at'] = date('Y-m-d H:i:s');
                    $userData['updated_at'] = date('Y-m-d H:i:s');
                    $user_id = $this->page->saveData('users', $userData);

                    //there are chances that this customer had made some bookings as a guest so now update bookings from guest to reg user.
                    if ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid === 0)
                        $individual_customer_id = $individual_customer_by_email->id;
                    else
                        $individual_customer_id = $individual_customer_by_id_no->id; //we know that its by id no in else

                    $guestBookings = $this->page->getGuestBookings($individual_customer_id);

                    if ($guestBookings) //if the user had made any bookings as a guest.
                    {
                        foreach ($guestBookings as $guestBooking) {

                            $guestBookingId = $guestBooking->booking_id;
                            //Insert into booking_individual_user and update booking table to mention it as registered user bookings.
                            $this->page->saveData('booking_individual_user', array('booking_id' => $guestBookingId, 'uid' => $user_id));
                            //type individual_customer means its a registered user booking.
                            $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => $guestBookingId));
                        }
                    }
                    //Now we have fetched the guest bookings from getGuestBookings and saved them to ind. customer bookings, so we can delete bookings from booking_individual_guest table
                    $this->page->deleteData('booking_individual_guest', array('individual_customer_id' => $individual_customer_id));
                    //=========
                    $data['first_name'] = $first_name;
                    $data['last_name'] = $last_name;
                    $data['mobile_no'] = $mobile_no;
                    $data['gender'] = $gender;
                    $data['email'] = $email;
                    $data['id_type'] = $id_type;
                    $data['id_version'] = ($id_type == '68' || $id_type == '243' ? '1' : '');
                    $data['id_no'] = $id_no;
                    $data['license_no'] = $license_no;
                    $data['sponsor'] = $sponsor;
                    $image = array();
                    if (isset($extra_info) && $extra_info == 1) {
                        // New Fields as Fozan suggested
                        $data['id_country'] = $id_country;
                        $data['license_country'] = $license_country;
                        $data['job_title'] = $job_title;
                        $data['street_address'] = $street_address;
                        $data['district_address'] = $district_address;

                        $data['nationality'] = $nationality;
                        $data['dob'] = date('Y-m-d', strtotime($dob));
                        $data['id_date_type'] = ($id_date_type == 'gregorian' ? 'G' : 'H');
                        if ($id_date_type == 'gregorian') {
                            $data['id_expiry_date'] = date('Y-m-d', strtotime($id_expiry_date));
                        } else {
                            $date_for_hijri = explode('-', $id_expiry_date);
                            $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                        }
                        $data['license_id_type'] = $license_id_type;
                        $data['license_expiry_date'] = date('Y-m-d', strtotime($license_expiry_date));

                        // $data['id_image'] = custom::uploadFile($id_image);
                        // $image['idImage'] = $data['id_image'];

                        // $data['license_image'] = custom::uploadFile($license_image);
                        // $image['licenseImage'] = $data['license_image'];


                        $data['payment_method'] = $payment_method;
                    }

                    if ($individual_customer_by_email || $individual_customer_by_id_no) {
                        if ($individual_customer_by_email) {
                            $data['loyalty_card_type'] = $individual_customer_by_email->loyalty_card_type;
                        } elseif ($individual_customer_by_id_no) {
                            $data['loyalty_card_type'] = $individual_customer_by_id_no->loyalty_card_type;
                        }
                    } else {
                        $data['loyalty_card_type'] = 'Bronze';
                    }

                    if ($individual_customer_by_email || $individual_customer_by_id_no) {
                        if ($individual_customer_by_email) {
                            $data['loyalty_points'] = $individual_customer_by_email->loyalty_points;
                        } elseif ($individual_customer_by_id_no) {
                            $data['loyalty_points'] = $individual_customer_by_id_no->loyalty_points;
                        }
                    } else {
                        $data['loyalty_points'] = '0';
                    }

                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['uid'] = $user_id;
                    if ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid === 0)
                        $update_by['email'] = $individual_customer_by_email->email;
                    else
                        $update_by['id_no'] = $individual_customer_by_id_no->id_no; //we know that its by id no in else

                    $id = $this->page->updateData('individual_customer', $data, $update_by);

                    custom::send_account_verification_links($user_id, $this->lang_base_url, $this->lang);

                    $users_id = $this->page->getSingle('individual_customer', $update_by);

                    // if (isset($data['id_image']) && pathinfo($data['id_image'], PATHINFO_EXTENSION) != 'pdf')
                    // $data['id_image'] = $this->convertImageToPdf($data['id_image'], $users_id->id_no, 'id');
                    // if (isset($data['license_image']) && pathinfo($data['license_image'], PATHINFO_EXTENSION) != 'pdf')
                    // $data['license_image'] = $this->convertImageToPdf($data['license_image'], $users_id->id_no, 'licence');

                    // if (isset($data['id_image']) && isset($data['license_image']))
                    // $this->page->updateData('individual_customer', array('id_image' => $data['id_image'], 'license_image' => $data['license_image']), $update_by);

                    // for logging in after registration
                    $user = $this->page->getSingle('users', array('email' => $email));
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    /////////////
                    // send email here
                    $this->sendRegistrationEmail($data, $image);
                    //===========
                    $response['status'] = 1;
                    $response['message'] = \Lang::get('labels.account_created_msg');
                    $response['response'] = array('user_id' => $individual_user);

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    // no data found for entered email/ID no, so saving new user.
                    $userData['name'] = $first_name . ' ' . $last_name;
                    $userData['email'] = $email;
                    $userData['password'] = md5($password);
                    $userData['type'] = 'individual_customer';
                    $userData['created_at'] = date('Y-m-d H:i:s');
                    $userData['updated_at'] = date('Y-m-d H:i:s');
                    $user_id = $this->page->saveData('users', $userData);

                    $data['first_name'] = $first_name;
                    $data['last_name'] = $last_name;
                    $data['mobile_no'] = $mobile_no;
                    $data['gender'] = $gender;
                    $data['email'] = $email;
                    $data['id_type'] = $id_type;
                    $data['id_version'] = ($id_type == '68' || $id_type == '243' ? '1' : '');
                    $data['id_no'] = $id_no;
                    $data['license_no'] = $license_no;
                    $image = array();
                    if (isset($extra_info) && $extra_info == 1) {
                        // New fields suggested by Fozan
                        $data['id_country'] = $id_country;
                        $data['license_country'] = $license_country;
                        $data['job_title'] = $job_title;
                        $data['sponsor'] = $sponsor;
                        $data['street_address'] = $street_address;
                        $data['district_address'] = $district_address;

                        $data['nationality'] = $nationality;
                        $data['dob'] = date('Y-m-d', strtotime($dob));
                        $data['id_date_type'] = ($id_date_type == 'gregorian' ? 'G' : 'H');
                        if ($id_date_type == 'gregorian') {
                            $data['id_expiry_date'] = date('Y-m-d', strtotime($id_expiry_date));
                        } else {
                            $date_for_hijri = explode('-', $id_expiry_date);
                            $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                        }
                        $data['license_id_type'] = $license_id_type;
                        $data['license_expiry_date'] = date('Y-m-d', strtotime($license_expiry_date));

                        // $data['id_image'] = custom::uploadFile($id_image);

                        $moveToPdf = false;
                        /*if (pathinfo($data['id_image'], PATHINFO_EXTENSION) == "pdf") {
                            \File::copy(public_path('uploads/' . $data['id_image']), public_path('pdf/' . $data['id_image']));
                            \File::copy(public_path('uploads/' . $data['id_image']), public_path('pdf2/' . $data['id_image']));
                        }*/

                        // $image['idImage'] = $data['id_image'];

                        // $data['license_image'] = custom::uploadFile($license_image);

                        $moveToPdf = false;
                        /*if (pathinfo($_FILES['license_image'], PATHINFO_EXTENSION) == "pdf") {
                            \File::copy(public_path('uploads/' . $data['license_image']), public_path('pdf/' . $data['license_image']));
                            \File::copy(public_path('uploads/' . $data['license_image']), public_path('pdf2/' . $data['license_image']));
                        }*/

                        // $image['licenseImage'] = $data['license_image'];
                        $data['payment_method'] = $payment_method;
                    }

                    $data['loyalty_card_type'] = 'Bronze';
                    $data['loyalty_points'] = '0';
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['uid'] = $user_id;
                    $id = $this->page->saveData('individual_customer', $data);

                    custom::send_account_verification_links($user_id, $this->lang_base_url, $this->lang);

                    $user_data = $this->page->getSingle('individual_customer', array('id' => $id));
                    // if (isset($data['id_image']) && pathinfo($data['id_image'], PATHINFO_EXTENSION) != 'pdf')
                    // $data['id_image'] = $this->convertImageToPdf($data['id_image'], $user_data->id_no, 'id');
                    // if (isset($data['license_image']) && pathinfo($data['license_image'], PATHINFO_EXTENSION) != 'pdf')
                    // $data['license_image'] = $this->convertImageToPdf($data['license_image'], $user_data->id_no, 'licence');

                    $where['id_no'] = $data['id_no'];

                    // if (isset($data['id_image']) && isset($data['license_image']))
                    // $this->page->updateData('individual_customer', array('id_image' => $data['id_image'], 'license_image' => $data['license_image']), $where);

                    // for logging in after registration
                    $user = $this->page->getSingle('users', array('email' => $email));
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    /////////////
                    // send email here for new register user..
                    $this->sendRegistrationEmail($data, $image);
                    //===========
                    $response['status'] = 1;
                    $response['message'] = \Lang::get('labels.account_created_msg');
                    $response['response'] = array('user_id' => $individual_user);

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function manageBooking()
    {
        // this is added just for the time being as the DB is crashing and hanging
        /*$response['status'] = 0;
        $response['message'] = \Lang::get('labels.reservation_not_found_msg');
        $response['response'] = "";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();*/

        try {
            // change
            $this->checkIfMaintenanceModeOn();
            $booking_ref_no = $_REQUEST['booking_ref_no'];

            $get_by['reservation_code'] = $booking_ref_no;
            $checkIfRecordExist = $this->page->getSingle('booking', $get_by);
            $booking_type = $checkIfRecordExist ? $checkIfRecordExist->type : '';
            if (!$checkIfRecordExist) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.reservation_not_found_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $search_query = $_REQUEST['search_query'];
                if ($search_query == '') {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'Email/ID No./Mobile No. Must Be Used For This.' : 'عفوا، هذا البريد الإلكتروني غير مطابق مع بيانات الحجز');
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    if ($booking_type == "corporate_customer") {
                        $recordExist = (array)$this->services->getSingleBookingCorporate($booking_ref_no);
                        $recordExist = custom::isNullToEmpty($recordExist);
                        $email = $recordExist['email'];
                        $mobile_no = $recordExist['mobile_no'];
                        $id_no = $recordExist['id_no'];
                        $company_code = $recordExist['company_code'];
                    } else {
                        $recordExist = (array)$this->services->getSingleBooking($booking_ref_no);
                        $recordExist = custom::isNullToEmpty($recordExist);
                        $email = ($recordExist['email'] != "" ? $recordExist['email'] : $recordExist['icg_email']);
                        $mobile_no = ($recordExist['mobile_no'] != "" ? $recordExist['mobile_no'] : $recordExist['icg_mobile_no']);
                        $id_no = ($recordExist['id_no'] != "" ? $recordExist['id_no'] : $recordExist['icg_id_no']);
                        $company_code = false;
                    }

                    if (
                        $recordExist &&
                        (
                            $email == $search_query ||
                            $mobile_no == $search_query ||
                            $id_no == $search_query ||
                            ($company_code && $search_query == $company_code)
                        )
                    ) {
                        $booking_id = $checkIfRecordExist->id;
                        if ($booking_type == "corporate_customer") {
                            $booking_detail = (array)$this->services->getSingleBookingDetailsForCorporate($booking_id, 'individual_user', $this->lang);
                        } else {
                            $booking_detail = (array)$this->services->getSingleBookingDetails($booking_id, 'individual_user', $this->lang);
                        }
                        $booking_detail = custom::isNullToEmpty($booking_detail);

                        $show_add_payment_option_for_booking = custom::show_add_payment_option($booking_id, true);
                        $booking_detail['show_add_payment_option_for_booking'] = ($show_add_payment_option_for_booking ? 1 : 0);

                        $response['status'] = 1;
                        $response['message'] = "";
                        $response['response'] = $booking_detail;
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    } else {
                        $error_message = ($this->lang == 'eng' ? 'Sorry, we couldn\'t find this email with this booking reference.' : 'عفوا، هذا البريد الإلكتروني غير مطابق مع بيانات الحجز');
                        $response['status'] = 0;
                        $response['message'] = $error_message;
                        $response['response'] = "";
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getSingleBookingDetails()
    {
        try {
            // change
            $id = $_REQUEST['id'];
            $booking = $this->page->getSingle('booking', array('id' => $id));
            if ($booking->type == "corporate_customer") {
                $booking_detail = (array)$this->services->getSingleBookingDetailsForCorporate($id, 'individual_user', $this->lang);
            } else {
                $booking_detail = (array)$this->services->getSingleBookingDetails($id, 'individual_user', $this->lang);
            }

            $booking_detail = custom::isNullToEmpty($booking_detail);
            $response['response'] = $booking_detail;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkIfUserExistByIDno()
    {
        try {
            $id_no = $_REQUEST['id_no'];
            $id_no = custom::convertArabicNumbersToEnglish($id_no);
            custom::validateIDNoForMobile($_REQUEST['id_type'], $id_no, $this->lang);

            $id_type = $_REQUEST['id_type'];

            $fetch_by['id_no'] = $id_no;
            $fetch_by['id_type'] = $id_type;
            $userExist = $this->page->getSingle('individual_customer', $fetch_by);
            if (!$userExist) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.user_info_not_found_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($userExist && $userExist->uid != 0) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.id_number_exist_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $randNumber = custom::generateRand();
                $smsSent = $this->sendVerificationCode($userExist->id, $randNumber);
                if (is_bool($smsSent) == true && $smsSent == true) {
                    $response['status'] = 1;
                    $response['message'] = ($this->lang == 'eng' ? 'A verification code is sent via SMS.' : 'تم إرسال رمز التحقق عن طريق رسالة نصية');
                    $response['response'] = array('verification_token' => $randNumber, 'user_id' => custom::jwt_encode($userExist->id));
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    $response['status'] = 0;
                    $response['message'] = $smsSent;
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function forgot_password()
    {
        try {
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $this->checkIfMaintenanceModeOn();
            $user_email = $_REQUEST['email'];
            $fetch_by['email'] = $user_email;
            $user = $this->page->getSingle('users', $fetch_by);
            if (!$user) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.account_not_found_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                if ($user->type == 'corporate_customer') {
                    $customer_detail = $this->page->getSingle('corporate_customer', array('uid' => $user->id));
                    $emailName = $customer_detail->company_name_en;
                    $user_gender = 'male';
                    $mobile_no_for_sms = $customer_detail->primary_phone;
                } else {
                    $customer_detail = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    $emailName = $customer_detail->first_name . ' ' . $customer_detail->last_name;
                    $user_gender = $customer_detail->gender;
                    $mobile_no_for_sms = $customer_detail->mobile_no;
                }
                $token = sha1(mt_rand());
                $resetData['email'] = $user_email;
                $resetData['token'] = $token;
                $resetData['created_at'] = date('Y-m-d H:i:s');
                $data = $this->page->saveData('password_resets', $resetData);
                $emailMsg = \Lang::get('labels.password_reset_request_msg');
                $emailMsg .= '<br>';
                $emailMsg .= $this->lang_base_url . '/reset-password?_key=' . $token;

                // send email
                $site = custom::site_settings();
                $smtp = custom::smtp_settings();

                $email['subject'] = \Lang::get('labels.password_reset_msg');

                $email['fromEmail'] = $smtp->username;
                $email['fromName'] = 'no-reply';
                $email['toEmail'] = $user_email;
                $email['ccEmail'] = '';
                $email['bccEmail'] = '';
                $email['attachment'] = '';

                $content['contact_no'] = $site->site_phone;
                $content['lang_base_url'] = $this->lang_base_url;
                $content['name'] = $emailName;
                $content['msg'] = $emailMsg;
                $content['gender'] = $user_gender;
                custom::sendSMS($mobile_no_for_sms, $emailMsg, $this->lang);
                custom::sendEmail('general', $content, $email, $this->lang);

                $response['status'] = 1;
                $response['message'] = \Lang::get('labels.password_rest_email_send_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getDropdownsDataForRegistration()
    {
        try {
            //$data['nationalities'] = $this->page->getAllNationalities($this->lang);
            $data['countries'] = $this->page->getAllCountries($this->lang);
            //$data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $site_settings = $this->page->getSingle('setting_site_settings', array('id' => 1));
            $api_settings = $this->page->getSingle('setting_api_settings', array('id' => 1));
            $home_content = $this->page->getSingle('home', array('id' => 1));
            $data['cc_company'] = $site_settings->cc_company;

            $data['sts_security_token'] = $api_settings->sts_secret_key_mobile;
            $data['sts_merchant_id'] = $api_settings->sts_merchant_id_mobile;

            $delivery_mode = ($site_settings->delivery_mode_mobile == 'off' ? 0 : 1);

            if (isset($_REQUEST['source']) && (strtolower($_REQUEST['source']) == 'android' || strtolower($_REQUEST['source']) == 'huawei')) {
                // $delivery_mode = 0;
            }

            $hourly_renting_mode = ($site_settings->hourly_renting_mode_mobile == 'off' ? 0 : 1);
            $monthly_renting_mode = ($site_settings->monthly_renting_mode_mobile == 'off' ? 0 : 1);
            $subscription_renting_mode = ($site_settings->subscription_renting_mode_mobile == 'off' ? 0 : 1);
            $campaign_mode = ($site_settings->campaign_mode == 'off' ? 0 : 1);
            $promo_coupon_mode = ($site_settings->promo_coupon_mode == 'off' ? 0 : 1);
            $vat_mode = ($site_settings->vat_mode_for_mobile == 'off' ? 0 : 1);
            $show_loyalty_programs_section = ($site_settings->show_loyalty_programs_section_for_mobile == 'no' ? 0 : 1);
            $daily_with_delivery_flow = ($site_settings->daily_with_delivery_flow == 'off' ? 0 : 1);
            $monthly_with_delivery_flow = ($site_settings->monthly_with_delivery_flow == 'off' ? 0 : 1);
            $subscription_with_delivery_flow = ($site_settings->subscription_with_delivery_flow == 'off' ? 0 : 1);
            $show_add_payment_option = ($site_settings->show_add_payment_option_for_mobile == 'no' ? 0 : 1);
            $show_pay_current_balance_option = ($site_settings->show_pay_current_balance_option_for_mobile == 'no' ? 0 : 1);
            $show_pay_by_number_of_days_option = ($site_settings->show_pay_by_number_of_days_option_for_mobile == 'no' ? 0 : 1);

            $data['ignore_hours_for_bronze'] = $site_settings->ignore_hours_bronze;
            $data['ignore_hours_for_silver'] = $site_settings->ignore_hours_silver;
            $data['ignore_hours_for_golden'] = $site_settings->ignore_hours_golden;
            $data['ignore_hours_for_platinum'] = $site_settings->ignore_hours_platinum;

            $vat_percentage = (float)$site_settings->vat_percentage;
            $qitaf = ($site_settings->qitaf == 'off' ? 0 : 1);
            $niqaty = ($site_settings->niqaty == 'off' ? 0 : 1);
            $anb = ($site_settings->anb == 'off' ? 0 : 1);
            $mokafaa = ($site_settings->mokafaa == 'off' ? 0 : 1);
            $data['monthly_renting_mode'] = (int)$monthly_renting_mode;
            $data['subscription_renting_mode'] = (int)$subscription_renting_mode;
            $data['delivery_mode'] = (int)$delivery_mode;
            $data['qitaf'] = (int)$qitaf;
            $data['niqaty'] = (int)$niqaty;
            $data['anb'] = (int)$anb;
            $data['mokafaa'] = (int)$mokafaa;
            $data['hourly_renting_mode'] = (int)$hourly_renting_mode;
            $data['campaign_mode'] = (int)$campaign_mode;
            $data['promo_visibility'] = (int)$promo_coupon_mode;
            $data['vat_mode'] = (int)$vat_mode;
            $data['show_loyalty_programs_section'] = (int)$show_loyalty_programs_section;
            $data['vat_percentage'] = $vat_percentage;
            $data['ios_version'] = (int)$site_settings->ios_version;
            $data['android_version'] = (int)$site_settings->android_version;
            $data['huawei_version'] = (int)$site_settings->huawei_version;
            $data['home_image_path'] = custom::baseurl('public/uploads') . '/' . ($this->lang == 'eng' ? $home_content->mobile_eng_image : $home_content->mobile_arb_image);
            $response['response'] = $data;
            $data['delivery_tab_title_eng'] = $site_settings->delivery_tab_title_eng;
            $data['delivery_tab_title_arb'] = $site_settings->delivery_tab_title_arb;
            $data['hourly_tab_title_eng'] = $site_settings->hourly_tab_title_eng;
            $data['hourly_tab_title_arb'] = $site_settings->hourly_tab_title_arb;
            $data['daily_tab_title_eng'] = $site_settings->daily_tab_title_eng;
            $data['daily_tab_title_arb'] = $site_settings->daily_tab_title_arb;
            $data['monthly_tab_title_eng'] = $site_settings->monthly_tab_title_eng;
            $data['monthly_tab_title_arb'] = $site_settings->monthly_tab_title_arb;
            $data['subscription_tab_title_eng'] = $site_settings->subscription_tab_title_eng;
            $data['subscription_tab_title_arb'] = $site_settings->subscription_tab_title_arb;

            $data['hyper_pay_endpoint_url'] = $api_settings->hyper_pay_endpoint_url;
            $data['hyper_pay_bearer_token'] = $api_settings->hyper_pay_bearer_token;
            $data['hyper_pay_entity_id_master_visa'] = $api_settings->hyper_pay_entity_id_master_visa;
            $data['hyper_pay_entity_id_mada'] = $api_settings->hyper_pay_entity_id_mada;
            $data['hyper_pay_test_mode'] = $api_settings->hyper_pay_test_mode == 'EXTERNAL' ? 1 : 0;

            $cancellation_reasons = $this->page->getMultipleRows('setting_cancellation_reasons', array('is_active' => 1));
            $data['cancellation_reasons'] = $cancellation_reasons ?: [];

            $loyalty_programs = $this->page->getMultipleRows('setting_loyalty_programs', array('is_active' => 1), 'is_default', 'desc');
            $data['loyalty_programs'] = $loyalty_programs ?: [];

            $data['cc'] = (int)$site_settings->cc;
            $data['amex'] = (int)$site_settings->amex;
            $data['mada'] = (int)$site_settings->mada;
            $data['stc_pay'] = (int)$site_settings->stc_pay;
            $data['apple_pay'] = (int)$site_settings->apple_pay;
            $data['cash'] = (int)$site_settings->cash;
            $data['points'] = (int)$site_settings->points;
            $data['sadad'] = (int)$site_settings->sadad;
            $data['hours_setup'] = [2, 3, 4, 5];

            $data['amount_to_be_redeemed_by_qitaf_as_percentage'] = $site_settings->amount_to_be_redeemed_by_qitaf_as_percentage;
            $data['amount_to_be_redeemed_by_niqaty_as_percentage'] = $site_settings->amount_to_be_redeemed_by_niqaty_as_percentage;

            $data['amount_to_be_redeemed_by_anb_as_percentage'] = $site_settings->amount_to_be_redeemed_by_anb_as_percentage;
            $data['amount_to_be_redeemed_by_mokafaa_as_percentage'] = $site_settings->amount_to_be_redeemed_by_mokafaa_as_percentage;

            $data['show_account_deletion_button'] = $site_settings->show_account_deletion_button;
            $data['daily_with_delivery_flow'] = $daily_with_delivery_flow;
            $data['monthly_with_delivery_flow'] = $monthly_with_delivery_flow;
            $data['subscription_with_delivery_flow'] = $subscription_with_delivery_flow;

            $data['show_add_payment_option'] = $show_add_payment_option;
            $data['show_pay_current_balance_option'] = $show_pay_current_balance_option;
            $data['show_pay_by_number_of_days_option'] = $show_pay_by_number_of_days_option;

            $active_app_popup_promo_codes = DB::table('app_popup_promo_codes')->where('status', 1)->pluck('id');
            $available_codes = DB::table('app_popup_promo_codes_list')->where('is_used', 0)->whereIn('parent_id', $active_app_popup_promo_codes)->where('seen_by', '')->count();
            $data['show_promo_code_popup_in_apps'] = $site_settings->show_promo_code_popup_in_apps == 'yes' && $available_codes > 0 ? 1 : 0;

            $data['mobile_app_splash_screen'] = custom::baseurl('public/uploads') . '/' . $site_settings->mobile_app_splash_screen;

            $data['redeem_section_type'] = $site_settings->redeem_offer_mode_type;

            $data['logout_customers_forcefully'] = ($site_settings->logout_customers_forcefully == 'off' ? 0 : 1);

            $data['refer_and_earn_option'] = ($site_settings->refer_and_earn_option == 'off' ? 0 : 1);
            $data['show_pay_by_promo_code_in_extend_payment'] = ($site_settings->show_pay_by_promo_code_in_extend_payment == 'yes' ? 1 : 0);

            $refer_and_earn_content = DB::table('refer_and_earn_content')->where('id', 1)->first();
            $data['eng_share_and_earn_button_amount_text'] = $refer_and_earn_content->eng_share_and_earn_button_amount_text;
            $data['arb_share_and_earn_button_amount_text'] = $refer_and_earn_content->arb_share_and_earn_button_amount_text;

            //echo "<pre>";print_r($data);exit();
            echo json_encode($data, JSON_UNESCAPED_UNICODE); // JSON_NUMERIC_CHECK
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function printBooking()
    {
        try {
            $this->pdf = App::make('snappy.pdf.wrapper');
            $booking_id = $_REQUEST['booking_id'];
            $booking_details = $this->page->getSingle('booking', array('id' => $booking_id));
            $data = array();
            if ($booking_details == "corporate_customer") {
                $data['booking_content'] = (array)$this->services->getSingleBookingDetailsForCorporate($booking_id, 'individual_user', $this->lang);
            } else {
                $data['booking_content'] = (array)$this->services->getSingleBookingDetails($booking_id, 'individual_user', $this->lang);
            }

            if ($data['booking_content'] == '') {
                exit();
            }
            $data['isPdf'] = "pdf";
            if ($this->lang == 'eng') {
                $view = "frontend.emails.booking_email_eng";
            } else {
                $view = "frontend.emails.booking_email_ar";
            }
            $this->pdf->loadView($view, $data);
            return @$this->pdf->inline();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function resendVerificationCodeForCreateLogin()
    {
        try {
            $id_no = $_REQUEST['id_no'];
            $id_no = custom::convertArabicNumbersToEnglish($id_no);

            $individual_customer = $this->page->getSingle("individual_customer", array("id_no" => $id_no));
            $userId = $individual_customer->id;
            // send sms
            $randNumber = custom::generateRand();
            $sent = $this->sendVerificationCode($userId, $randNumber);
            //===========
            if (is_bool($sent) == true && $sent == true) {
                $response['status'] = 1;
                $response['message'] = ($this->lang == 'eng' ? 'A verification code is sent via SMS' : 'A verification code is sent via SMS');
                $response['response'] = array('verification_token' => $randNumber, 'user_id' => $userId);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = $sent;
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getBookingsForUser()
    {
        try {
            // change
            $user_id = custom::jwt_decode($_REQUEST['user_id']);
            $type = $_REQUEST['type'];
            $customer_type = (isset($_REQUEST['customer_type']) && $_REQUEST['customer_type'] != "" ? $_REQUEST['customer_type'] : "Individual");
            $from_ios = (isset($_REQUEST['ios']) ? 1 : 0);
            if ($type == 'history') {
                $search_type = 'history_bookings';
            } else {
                $search_type = 'current_bookings';
            }

            if ($customer_type == "Corporate") {
                $user_info = (array)$this->page->getSingle('corporate_customer', array('id' => $user_id));
            } else {
                $user_info = (array)$this->page->getSingle('individual_customer', array('id' => $user_id));
            }
            $user_info = custom::isNullToEmpty($user_info);
            $user_uid = $user_info['uid'];
            $bookings = [];
            if ($from_ios == 1) {
                $bookings = $this->services->getLatestBookingForEachUserIos($user_uid, $search_type, "", $this->lang);
            } else {
                $bookings = $this->services->getLatestBookingForEachUserAndroid($user_uid, $search_type, "", $this->lang);
            }
            if (count($bookings) > 0) {

                foreach($bookings as $booking) {
                    $show_add_payment_option_for_booking = custom::show_add_payment_option($booking->id, true);
                    $booking->show_add_payment_option_for_booking = ($show_add_payment_option_for_booking ? 1 : 0);
                }

                $response['status'] = 1;
                $response['message'] = "";
                $response['response']['user_info'] = $user_info;
                $response['response']['bookings'] = $bookings;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.no_record_found');
                $response['response']['user_info'] = $user_info;
                $response['response']['bookings'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getPageContent()
    {
        try {
            $content = array();
            $site = custom::site_settings();
            $page = $_REQUEST['page'];

            if ($page == 'about_us') {
                $content = $this->page->getSingle('about_us', array('id' => '1'));
            } elseif ($page == 'location') {
                $content['page_content'] = (array)$this->page->getSingle('location', array('id' => '1'));
                $content['locations'] = $this->page->getBranchesAndCities();
                $content['airports'] = $this->page->getBranchesAndCitiesWithAirport();
                $content['branches'] = $this->page->getBranchesOfCities();
            } elseif ($page == 'contact_us') {
                $content['page_content'] = (array)$this->page->getSingle('contact_us', array('id' => '1'));
                $content['page_content']['toll_free_no'] = $site->site_phone;
                $content['inquiry_types'] = $this->page->getAll('setting_inquiry_type', 'id', 'Desc');
                $content['countries'] = $this->page->getAll('country', 'oracle_reference_number', 'ASC');
            } elseif ($page == 'faq') {
                $content['page_content'] = (array)$this->page->getSingle('faqs_content', array('id' => '1'));
                $content['listing'] = $this->page->getAll('faqs_question', 'id', 'Desc');
            } elseif ($page == 'loyalty') {
                $content['page_content'] = (array)$this->page->getSingle('loyalty_program', array('id' => '1'));
                $content['page_content']['banner_image'] = $content['page_content']['image_phone'];
            }
            $response['status'] = 1;
            $response['message'] = "";
            $response['image_base_path'] = custom::baseurl('/') . '/public/uploads/';
            $response['response'] = $content;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveContactUsForm()
    {
        try {
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $_REQUEST['mobile'] = trim($_REQUEST['mobile']);
            $is_mobile_no_valid = custom::validate_mobile_no($_REQUEST['mobile']);
            if (!$is_mobile_no_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_mobile_no_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $site = custom::site_settings();
            $smtp = custom::smtp_settings();

            $inputs['name'] = $_REQUEST['name'];
            $inputs['email'] = $_REQUEST['email'];
            $inputs['mobile'] = $_REQUEST['mobile'];
            $inputs['country'] = $_REQUEST['country'];
            $inputs['inquiry_type_id'] = $_REQUEST['inquiry_type'];
            $inputs['message'] = $_REQUEST['message'];

            $inquiryDetail = $this->page->getSingle('setting_inquiry_type', array('id' => $inputs['inquiry_type_id']));

            $emailSend = $inquiryDetail->email;
            $inquiryName = ($this->lang == 'eng' ? $inquiryDetail->eng_title : $inquiryDetail->arb_title);

            $saved = $this->page->saveData("inquiries", $inputs);

            $email['subject'] = \Lang::get('labels.inquiry_received_msg');
            $email['fromEmail'] = $smtp->username;
            $email['fromName'] = "no-reply";
            $email['toEmail'] = $emailSend;
            $email['ccEmail'] = ""; //$smtp->username;
            $email['bccEmail'] = '';
            $email['attachment'] = '';

            $data['data']['name'] = "Admin";
            $data['data']['gender'] = "male";
            $data['data']['contact_no'] = $site->site_phone;
            $data['data']['lang_base_url'] = $this->lang_base_url;
            $data['data']['message'] = $inputs['message'];

            $info['name'] = $inputs['name'];
            $info['email'] = $inputs['email'];
            $info['phone'] = $inputs['mobile'];
            $info['inquiry_type'] = $inquiryName;
            $data['data']['info'] = $info;

            custom::sendEmail('form', $data, $email, "eng");

            if ($saved) {
                $response['status'] = 1;
                $response['message'] = \Lang::get('labels.form_submitted_msg');
                $response['response'] = "";

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.form_submitting_error_msg');
                $response['response'] = "";

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getExtraCharges()
    {
        try {
            $car_id = $_REQUEST['car_id'];
            $from_region_id = $_REQUEST['from_region_id'];
            $from_city_id = $_REQUEST['from_city_id'];
            $from_branch_id = $_REQUEST['from_branch_id'];
            $to_city_id = $_REQUEST['to_city_id'];
            $to_branch_id = $_REQUEST['to_branch_id'];
            $days = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] == 4) {
                $days = 30;
            }
            $pickup_date = $_REQUEST['pickup_date'];
            $customer_type = 'Individual';
            $category = 0;
            $rent_per_day = $_REQUEST['discounted_price'];
            $old_price = $_REQUEST['actual_price'];
            $loyalty_discount_percent = $_REQUEST['loyalty_discount_percent'];

            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'android';

            $parking_fee = custom::parking_fee_for_branch($from_branch_id);
            $tamm_charges_for_branch = custom::tamm_charges_for_branch($from_branch_id);

            $site_settings = custom::site_settings();

            /*$data['car_info'] = $this->page->getSingleCarDetail($car_id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($from_branch_id);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($to_branch_id);*/

            $search_by['region_id'] = $from_region_id;
            $search_by['city_id'] = $from_city_id;
            $search_by['branch_id'] = $from_branch_id;
            $search_by['days'] = $days;
            $search_by['pickup_date'] = $pickup_date;
            $search_by['customer_type'] = $customer_type;
            $search_by['category'] = $category;
            $search_by['car_model'] = $car_id;

            if (strpos($_REQUEST['pickup_time'], 'ص') !== false || strpos($_REQUEST['pickup_time'], 'م') !== false || strpos($_REQUEST['dropoff_time'], 'ص') !== false || strpos($_REQUEST['dropoff_time'], 'م') !== false) {
                $pickup_time = custom::convertEngTimeToArbTime($_REQUEST['pickup_time'], $type);
                $dropoff_time = custom::convertEngTimeToArbTime($_REQUEST['dropoff_time'], $type);
            } else {
                $pickup_time = $_REQUEST['pickup_time'];
                $dropoff_time = $_REQUEST['dropoff_time'];
            }


            $search_by['is_delivery_mode'] = $is_delivery_mode = (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] != '' ? $_REQUEST['is_delivery_mode'] : 0);

            $search_by['book_for_hours'] = $book_for_hours = (isset($_REQUEST['book_for_hours']) && $_REQUEST['book_for_hours'] != '' ? $_REQUEST['book_for_hours'] : 0);

            if ($is_delivery_mode == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($_REQUEST['subscribe_for_months']) && $_REQUEST['subscribe_for_months'] != '' ? $_REQUEST['subscribe_for_months'] : 0);
            if ($is_delivery_mode == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }


            $date_picked_up_for_hours_cal = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off_for_hours_cal = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            $search_by['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

            $car_detail = $this->page->getSingle('car_model', array('id' => $car_id));

            $search_by['pickup_date'] = date('Y-m-d', strtotime($search_by['pickup_date']));
            // custom::dump($search_by);
            $data['extra_charges'] = $this->services->getAllCarModels($search_by, 'extras');
            // $data['extra_charges'] = $this->services->getAllCarModels($search_by, 'extras',"","","",$loyalty_discount_percent);
            // custom::dump($data['extra_charges']);

            $customer_type = (isset($_REQUEST['customer_type']) && $_REQUEST['customer_type'] != "" ? $_REQUEST['customer_type'] : "Individual");

            $user_id = custom::jwt_decode($_REQUEST['user_id']);
            $user_loyalty = 'Bronze'; // default value
            if ($user_id == 0 && $customer_type == 'guest') {
                $user_loyalty = 'Bronze';
                $customer_type_for_promo = 'Individual';
            } elseif ($user_id > 0) {
                if ($customer_type == "Individual" || $customer_type == "individual_customer" || $customer_type == "guest") {
                    $user_info = (array)$this->page->getSingle('individual_customer', array('id' => $user_id));
                    $user_loyalty = $user_info['loyalty_card_type'];
                    $customer_type_for_promo = 'Individual';
                } else {
                    $user_info = (array)$this->page->getSingle('corporate_customer', array('id' => $user_id));
                    $user_loyalty = $user_info['membership_level'];
                    $customer_type_for_promo = 'Corporate';
                }
            }

            if ($from_city_id != $to_city_id) {
                $dropoff_charges = $this->page->getDropoffCharges(date('Y-m-d', strtotime($pickup_date)), $from_city_id, $to_city_id, $user_loyalty);
            } else {
                $dropoff_charges = false;
            }
            $dropoff_charges_price = ($dropoff_charges ? (int)$dropoff_charges[0]->price : 0);
            $data['dropoff_charges_price'] = $dropoff_charges_price;

            $new_rent_per_day = $rent_per_day;
            $old_rent_per_day = $old_price;
            $loyalty_discount_percentage = ($loyalty_discount_percent != '' ? $loyalty_discount_percent : 0);

            $promotion_offer_id = 0;

            $promo_discount = $this->page->checkAutoPromoDiscount($car_id, date('Y-m-d H:i:s', strtotime($pickup_date)), $from_region_id, $from_city_id, $from_branch_id, $days, $customer_type_for_promo);

            $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount, $pickup_date);

            if ($promo_discount && $promo_discount->type == 'Percentage Auto Apply' && $coupon_is_valid_for_pickup_day) {
                $promo_discount_percent = $promo_discount->discount;
                if ($promo_discount_percent > 0 && $promo_discount_percent > $loyalty_discount_percentage) {
                    $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                    $new_rent_per_day = $old_rent_per_day - $discount_amount;
                    $new_rent_per_day = round($new_rent_per_day, 2);
                    $promotion_offer_id = $promo_discount->id;
                }
            } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply on Loyalty' && $coupon_is_valid_for_pickup_day) {
                $promo_discount_percent = $loyalty_discount_percentage; // it already includes promo discount, check getCars api for detail
                $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                $new_rent_per_day = $old_rent_per_day - $discount_amount;
                $new_rent_per_day = round($new_rent_per_day, 2);
                $promotion_offer_id = $promo_discount->id;
            } elseif ($promo_discount && $promo_discount->type == 'Fixed Price Auto Apply' && $coupon_is_valid_for_pickup_day) {
                $after_fixed_price_discount = round($old_rent_per_day - $promo_discount->discount, 2);
                if ($after_fixed_price_discount < $new_rent_per_day) {
                    $discount_amount = $promo_discount->discount;
                    $new_rent_per_day = $old_rent_per_day - $discount_amount;
                    $new_rent_per_day = round($new_rent_per_day, 2);
                    $promotion_offer_id = $promo_discount->id;
                }
            } elseif ($promo_discount && $promo_discount->type == 'Fixed Daily Rate Auto Apply' && $coupon_is_valid_for_pickup_day) {
                $after_fixed_price_discount = $promo_discount->discount;

                if ($after_fixed_price_discount < $new_rent_per_day) {
                    $new_rent_per_day = round($promo_discount->discount, 2);
                    $promotion_offer_id = $promo_discount->id;
                }
            }

            if (!$data['extra_charges']) {
                $data['extra_charges'] = array();
            }
            $from_branch_info = $this->page->getSingle('branch', array('id' => $from_branch_id));
            $is_driver_available_for_this_branch = custom::is_driver_available_for_this_branch($_REQUEST['pickup_date'], $from_branch_info->id, $from_branch_info->no_of_drivers_per_day);
            $data['rent_per_day'] = (float)$new_rent_per_day;
            $data['parking_fee'] = $parking_fee;
            $data['tamm_charges_for_branch'] = $tamm_charges_for_branch;
            $data['is_driver_available_for_this_branch'] = $is_driver_available_for_this_branch;
            // $data['promotion_offer_id'] = $promotion_offer_id;
            $response['status'] = 1;
            $response['message'] = "";
            $response['response'] = $data;

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkIfBookingCancellable()
    {
        try {
            $booking_id = $_REQUEST['booking_id'];
            $is_send_otp = isset($_REQUEST['is_send_otp']) && $_REQUEST['is_send_otp'] == 1;

            $site = custom::site_settings();

            $where_bp['booking_id'] = $booking_id;
            $booking_payment_rec = $this->page->getSingle("booking_payment", $where_bp);
            $rent_price = $booking_payment_rec->rent_price;

            $where['id'] = $booking_id;
            $booking_detail = $this->page->getSingle("booking", $where);


            $cancel_hours_before_pickup_from_db = $site->cancel_in_hours;
            $cancel_percentage_for_before_pickup_from_db = $site->cancel_percentage;
            $cancel_hours_after_pickup_from_db = $site->post_booking_cancellation_hours;
            $cancel_charges = ($cancel_percentage_for_before_pickup_from_db * $rent_price) / 100;

            $cancel_time = date("Y-m-d H:i:s");
            $cancel_time = new \DateTime($cancel_time);

            $pickup_date = $booking_detail->from_date;
            $pickup_date = new \DateTime($pickup_date);

            if ($pickup_date->getTimestamp() >= $cancel_time->getTimestamp()) {
                $start = Carbon::now();
                $end = new Carbon($booking_detail->from_date);
                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] >= (int)$cancel_hours_before_pickup_from_db * 60) {
                    if ($is_send_otp) {
                        $verified = $this->sendVerificationCodeForBookingCancellation($booking_id);
                        if ($verified['status'] == false) {
                            $response['status'] = 0;
                            $response['message'] = 'The mobile you entered is not valid.';
                            $response['response'] = "";
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        } else {
                            $response['status'] = 1;
                            $response['message'] = 'OTP is sent to your registered mobile no successfully.';
                            $response['response'] = array('cancel_charges' => "", 'verification_token' => $verified['response']['verification_token']);
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        }
                    } else {
                        if ($this->lang == 'eng') {
                            $message = "Your booking will be cancelled and your whole paid amount will be refunded within 2 weeks.";
                        } else {
                            $message = "سوف يتم إلغاء حجزك و سوف يتم إسترداد كامل المبلغ خلال أسبوعين";
                        }
                        $response['status'] = 1;
                        $response['message'] = $message;
                        $response['response'] = array('cancel_charges' => 0, 'verification_token' => '');
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                } elseif ((int)$cancel_hours_before_pickup_from_db * 60 > $difference['minutes']) {
                    if ($is_send_otp) {
                        $verified = $this->sendVerificationCodeForBookingCancellation($booking_id);
                        if ($verified['status'] == false) {
                            $response['status'] = 0;
                            $response['message'] = 'The mobile you entered is not valid.';
                            $response['response'] = "";
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        } else {
                            $response['status'] = 1;
                            $response['message'] = 'OTP is sent to your registered mobile no successfully.';
                            $response['response'] = array('cancel_charges' => "", 'verification_token' => $verified['response']['verification_token']);
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        }
                    } else {
                        $cancel_charges = round(((int)$booking_payment_rec->rent_price * $cancel_percentage_for_before_pickup_from_db) / 100, 2);
                        if ($this->lang == 'eng') {
                            $message = "Your booking will be cancelled and " . $cancel_percentage_for_before_pickup_from_db . " % will be deducted from the first day that is (" . $cancel_charges . " " . \Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
                        } else {
                            $message = "Your booking will be cancelled and " . $cancel_percentage_for_before_pickup_from_db . " % will be deducted from the first day that is (" . $cancel_charges . " " . \Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
                        }
                        $response['status'] = 1;
                        $response['message'] = $message;
                        $response['response'] = array('cancel_charges' => $cancel_charges, 'verification_token' => '');
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                }

            } elseif ($cancel_time->getTimestamp() > $pickup_date->getTimestamp()) {
                // 3,4
                $start = new Carbon($booking_detail->from_date);
                $end = Carbon::now();

                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] <= (int)$cancel_hours_after_pickup_from_db * 60) {
                    if ($is_send_otp) {
                        $verified = $this->sendVerificationCodeForBookingCancellation($booking_id);
                        if ($verified['status'] == false) {
                            $response['status'] = 0;
                            $response['message'] = 'The mobile you entered is not valid.';
                            $response['response'] = "";
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        } else {
                            $response['status'] = 1;
                            $response['message'] = 'OTP is sent to your registered mobile no successfully.';
                            $response['response'] = array('cancel_charges' => "", 'verification_token' => $verified['response']['verification_token']);
                            echo json_encode($response, JSON_UNESCAPED_UNICODE);
                            exit();
                        }
                    } else {
                        $cancel_charges = $booking_payment_rec->rent_price;
                        if ($this->lang == 'eng') {
                            $message = "Your booking will be cancelled and " . $cancel_charges . " " . \Lang::get('labels.currency') . " will be deducted from the total paid amount and will be refunded within 2 weeks.";
                        } else {
                            $message = "سوف يتم إلغاء حجزك وسوف يتم خصم " . $cancel_charges . " " . \Lang::get('labels.currency') . "ل من مجموع المبلغ المدفوع، و سوف يتم إسترداد المبلغ خلال أسبوعين";
                        }
                        $response['status'] = 1;
                        $response['message'] = $message;
                        $response['response'] = array('cancel_charges' => $cancel_charges, 'verification_token' => '');
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                } elseif ((int)$cancel_hours_after_pickup_from_db * 60 < $difference['minutes']) {
                    $message = \Lang::get('labels.cancel_booking_not_allowed_msg');
                    $response['status'] = 0;
                    $response['message'] = $message;
                    $response['response'] = array('cancel_charges' => "", 'verification_token' => "");

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }

            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function sendVerificationCodeForBookingCancellation($bookingId = '')
    {

        try {
            if ($bookingId != '') {
                $booking_id = $bookingId;
                $send_json_response = 0;
            } else if (isset($_REQUEST['booking_id']) && $_REQUEST['booking_id'] != "") {
                $booking_id = $_REQUEST['booking_id'];
                $send_json_response = 1;
            }


            $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $booking_id));
            if ($booking_individual_user) {
                $uid = $booking_individual_user->uid;
                $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                $userId = $individual_customer->id;
            } else {
                $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $booking_id));
                $userId = $booking_individual_guest->individual_customer_id;

            }
            $response = array();
            // send sms
            $randNumber = custom::generateRand();
            $sent = $this->sendVerificationCodeViaWhatsapp($userId, $randNumber);
            //===========
            if (is_bool($sent) == true && $sent == true) {
                if ($send_json_response == 0) {
                    $response['status'] = true;
                    $response['response'] = array('verification_token' => $randNumber, 'user_id' => $userId);
                    return $response;
                } else {
                    $response['status'] = 1;
                    $response['message'] = ($this->lang == 'eng' ? 'A verification code is sent via SMS' : 'A verification code is sent via SMS');
                    $response['response'] = array('verification_token' => $randNumber, 'user_id' => $userId);

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } else {
                if ($send_json_response == 0) {
                    $response['status'] = false;
                    $response['response'] = array();
                    return $response;
                } else {
                    $response['status'] = 0;
                    $response['message'] = $sent;
                    $response['response'] = "";

                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }

            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function cancelBooking()
    {
        try {
            DB::enableQueryLog();
            $id = $_REQUEST['booking_id'];
            $cancellation_reason = isset($_REQUEST['cancellation_reason']) ? $_REQUEST['cancellation_reason'] : '';

            $site = custom::site_settings();
            $cancel_hours_before_pickup_from_db = $site->cancel_in_hours;
            $cancel_percentage_for_before_pickup_from_db = $site->cancel_percentage;
            $cancel_hours_after_pickup_from_db = $site->post_booking_cancellation_hours;

            $booking_id['id'] = $id;
            $booking_status['booking_status'] = "Cancelled";


            $booking_detail = $this->page->getSingle("booking", array('id' => $id));
            $booking_payment_rec = $this->page->getSingle("booking_payment", array('booking_id' => $id));

            $data_cancel_charges['booking_id'] = $id;
            $data_cancel_charges['cancel_time'] = date("Y-m-d H:i:s");

            $cancel_time = date("Y-m-d H:i:s");
            $cancel_time = new \DateTime($cancel_time);

            $pickup_date = $booking_detail->from_date;
            $pickup_date = new \DateTime($pickup_date);

            if ($pickup_date->getTimestamp() >= $cancel_time->getTimestamp()) {
                $start = Carbon::now();
                $end = new Carbon($booking_detail->from_date);
                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] >= (int)$cancel_hours_before_pickup_from_db * 60) {
                    $data_cancel_charges['cancel_charges'] = 0;
                    $booking_status['booking_status'] = "Cancelled";
                } elseif ((int)$cancel_hours_before_pickup_from_db * 60 > $difference['minutes']) {
                    $data_cancel_charges['cancel_charges'] = round(((int)$booking_payment_rec->rent_price * $cancel_percentage_for_before_pickup_from_db) / 100, 2);
                    $booking_status['booking_status'] = "Cancelled";
                }

            } elseif ($cancel_time->getTimestamp() > $pickup_date->getTimestamp()) {
                $start = new Carbon($booking_detail->from_date);
                $end = Carbon::now();

                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] <= (int)$cancel_hours_after_pickup_from_db * 60) {
                    $data_cancel_charges['cancel_charges'] = $booking_payment_rec->rent_price;
                    $booking_status['booking_status'] = "Cancelled";
                } elseif ((int)$cancel_hours_after_pickup_from_db * 60 < $difference['minutes']) {
                    $data_cancel_charges['cancel_charges'] = $booking_payment_rec->rent_price;
                    $booking_status['booking_status'] = "Expired";
                }

            }

            $data_cancel_charges['cancellation_reason'] = custom::cancellation_reasons($cancellation_reason, $this->lang);
            $data_cancel_charges['sync'] = "N";
            $data_cancel_charges['synced_at'] = "0000-00-00";
            $this->page->saveData("booking_cancel", $data_cancel_charges);
            $updated = $this->page->updateData('booking', $booking_status, $booking_id);
            // $updated = true;
            /*$query = DB::getQueryLog();
            $lastQuery = end($query);
            print_r($lastQuery);
            exit();*/

            if ($updated) {
                if ($booking_detail->type == 'corporate_customer') {
                    $details = $this->page->getSingle("booking_corporate_customer", array('booking_id' => $id));
                    $user_details = $this->page->getSingle("corporate_customer", array('uid' => $details->uid));
                    $user_mobile_no = $user_details->primary_phone;
                    $user_email_address = $user_details->primary_email;
                } elseif ($booking_detail->type == 'individual_customer') {
                    $details = $this->page->getSingle("booking_individual_user", array('booking_id' => $id));
                    $user_details = $this->page->getSingle("individual_customer", array('uid' => $details->uid));
                    $user_mobile_no = $user_details->mobile_no;
                    $user_email_address = $user_details->email;
                } elseif ($booking_detail->type == 'guest') {
                    $details = $this->page->getSingle("booking_individual_guest", array('booking_id' => $id));
                    $user_details = $this->page->getSingle("individual_customer", array('id' => $details->individual_customer_id));
                    $user_mobile_no = $user_details->mobile_no;
                    $user_email_address = $user_details->email;
                }

                if ($booking_detail->type == 'individual_customer' || $booking_detail->type == 'guest') {
                    // Adding and updating customer's redeem points
                    $this->add_or_deduct_loyalty_points_for_customer($id, 'add');
                }

                // $this->autoReverseQitafRedeem($booking_id['id']); // returning qitaf redeemed points

                // $this->autoReverseNiqatyRedeem($booking_id['id']); // returning niqaty redeemed points

                if (isset($user_mobile_no) && $user_mobile_no != '') {
                    $smsPhone = str_replace(array('+', ' '), '', $user_mobile_no);
                    // sending booking cancelled sms to user
                    $userSms = "Dear Customer, \nYour booking with reservation code " . $booking_detail->reservation_code . " is cancelled successfully.";
                    $userSms .= "Click link for details: \n";
                    $userSms .= $this->lang_base_url . '/manage-booking/' . custom::encode_with_jwt($booking_detail->id);
                    custom::sendSMS($smsPhone, $userSms, $this->lang);

                }

                if (isset($user_email_address) && $user_email_address != '') {

                    $this->sendCancellationEmail($booking_id['id'], $booking_detail->type);
                }

                if ($site->admin_phone != '') {
                    $paymentMethod = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking_id['id']));
                    if ($paymentMethod->payment_method == 'Credit Card') {
                        $cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking_id['id']));
                        $transaction_id = $cc_payment->transaction_id;
                    } elseif ($paymentMethod->payment_method == 'Sadad') {
                        $cc_payment = $this->page->getSingle('booking_sadad_payment', array('s_booking_id' => $booking_id['id']));
                        $transaction_id = $cc_payment->s_transaction_id;
                    }

                    if (isset($cc_payment) && isset($transaction_id)) {
                        $adminSms = "A booking with reservation # " . $booking_detail->reservation_code . " trans # " . $transaction_id . " has been cancelled by user";
                    } else {
                        $adminSms = "A booking with reservation # " . $booking_detail->reservation_code . " has been cancelled by user";
                    }
                    $adminPhones = explode(',', $site->admin_phone);
                    foreach ($adminPhones as $phone) {
                        custom::sendSMS($phone, $adminSms, $this->lang);
                    }
                }

                // sending cancellation email to admin

                if ($site->admin_email != '') {
                    $this->sendCancellationEmailToAdmin($booking_id['id'], $booking_detail->type);
                }

                $response['status'] = 1;
                $response['message'] = \Lang::get('labels.booking_canceled_msg');
                $response['response'] = "";


                $cronjob_url = custom::baseurl('/') . '/cronjob/setCancelledBookingCollectionCronJob';
                // file_get_contents($cronjob_url); // commented by Bilal on 18-08-2020, file_get_contents function not working on new server for now so doing it through curl
                $curlResponse = $this->sendCurlRequest($cronjob_url);

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.booking_already_canceled_msg');
                $response['response'] = "";

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function payment(Request $request)
    {
        try {
            $cdw = $_REQUEST['cdw'];
            $cdw_plus = $_REQUEST['cdw_plus'];
            $gps = $_REQUEST['gps'];
            $extra_driver = $_REQUEST['extra_driver'];
            $baby_seat = $_REQUEST['baby_seat'];
            $dropoff_charges = $_REQUEST['dropoff_charges'];
            $from_branch_id = $_REQUEST['from_branch_id'];
            $to_branch_id = $_REQUEST['to_branch_id'];
            $car_id = $_REQUEST['car_id'];
            $pickup_date = $_REQUEST['pickup_date'];
            $from_region_id = $_REQUEST['from_region_id'];
            $from_city_id = $_REQUEST['from_city_id'];
            $days = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days = 30;
            }
            $individual_customer_id = $_REQUEST['individual_customer_id'];
            $rent_per_day = $_REQUEST['rent_per_day'];
            $old_price = $_REQUEST['old_price'];
            $loyalty_discount_percent = $_REQUEST['loyalty_discount_percent'];

            $fetch_user_info_by['id'] = $individual_customer_id;
            $user_info = $this->page->getSingle('individual_customer', $fetch_user_info_by);

            $regions = $this->page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }

            $data['car_info'] = $this->page->getSingleCarDetail($car_id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($from_branch_id);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($to_branch_id);
            $data['user_info'] = $user_info;

            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');

            $promo_discount_percent = 0;
            $new_rent_per_day = $rent_per_day;
            $old_rent_per_day = $old_price;
            $loyalty_discount_percentage = ((int)$loyalty_discount_percent != '' ? (int)$loyalty_discount_percent : 0);


            $promo_discount = $this->page->checkAutoPromoDiscount($car_id, date('Y-m-d H:i:s', strtotime($pickup_date)), $from_region_id, $from_city_id, $from_branch_id, $days);

            $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount, $pickup_date);

            if ($promo_discount && $promo_discount->type == 'Fixed Price Auto Apply' && $coupon_is_valid_for_pickup_day) {
                $promotion_id = $promo_discount->id;
                $promo_discount_amount = $promo_discount->discount;
                $total_amount_after_discount = round($rent_per_day * $days - ($promo_discount_amount * $days), 2);
            } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply' && ($promo_discount_percent > $loyalty_discount_percentage) && $coupon_is_valid_for_pickup_day) {
                $promotion_id = $promo_discount->id;
                $percentage = $promo_discount->discount;
                $promo_discount_amount = round($percentage * $rent_per_day / 100, 2);
                $total_amount_after_discount = round($rent_per_day * $days - ($promo_discount_amount * $days), 2);
            } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply on Loyalty' && $coupon_is_valid_for_pickup_day) {
                $promotion_id = $promo_discount->id;
                $percentage = $promo_discount->discount + $loyalty_discount_percentage;
                $promo_discount_amount = round($percentage * $rent_per_day / 100, 2);
                $total_amount_after_discount = round($rent_per_day * $days - ($promo_discount_amount * $days), 2);
            } elseif ($promo_discount && $promo_discount->type == 'Fixed Daily Rate Auto Apply' && $coupon_is_valid_for_pickup_day) {
                $promotion_id = $promo_discount->id;
                $promotionAmount = $promo_discount->discount;
                $promo_discount_amount = round($rent_per_day - $promotionAmount, 2);
                $total_amount_after_discount = round($promotionAmount * $days, 2);
            }

            $response['status'] = 0;
            $response['message'] = \Lang::get('labels.some_error_msg');;
            $response['response'] = "";

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function updateProfile()
    {
        try {

            $_REQUEST['first_name'] = urldecode($_REQUEST['first_name']);
            $_REQUEST['last_name'] = urldecode($_REQUEST['last_name']);

            if (isset($_REQUEST['sponsor']) && $_REQUEST['sponsor']) {
                $_REQUEST['sponsor'] = urldecode($_REQUEST['sponsor']);
            }

            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $_REQUEST['mobile_no'] = trim($_REQUEST['mobile_no']);
            $is_mobile_no_valid = custom::validate_mobile_no($_REQUEST['mobile_no']);
            if (!$is_mobile_no_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_mobile_no_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $id_no = $_REQUEST['id_no'];
            $id_no = custom::convertArabicNumbersToEnglish($id_no);
            custom::validateIDNoForMobile($_REQUEST['id_type'], $id_no, $this->lang);
            $user_id = custom::jwt_decode($_REQUEST['user_id']);
            $logged_in_user_id = $_REQUEST['uid'];
            $id_type = $_REQUEST['id_type'];
            $old_id_no = $_REQUEST['old_id_no'];
            $old_email = $_REQUEST['old_email'];
            $first_name = urldecode($_REQUEST['first_name']);
            $last_name = urldecode($_REQUEST['last_name']);
            $email = $_REQUEST['email'];

            $password = $_REQUEST['password'];

            if ($password != '') {
                $isPasswordStrong = custom::isPasswordStrong($password, $this->lang);
                if (!$isPasswordStrong['status']) {
                    $response['status'] = 0;
                    $response['message'] = $isPasswordStrong['message'];
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }

            $mobile_no = custom::getPhoneNumber($_REQUEST['mobile_no']);
            $mobile_no = $mobile_no['country_code'] . $mobile_no['number'];
            $sponsor = (isset($_REQUEST['sponsor']) && $_REQUEST['sponsor'] != '' ? $_REQUEST['sponsor'] : '');

            /*$nationality = $_REQUEST['nationality'];
            $dob = $_REQUEST['dob'];
            $id_date_type = $_REQUEST['id_date_type'];
            $id_expiry_date = $_REQUEST['id_expiry_date'];
            $license_no = $_REQUEST['license_no'];
            $license_id_type = $_REQUEST['license_id_type'];
            $license_expiry_date = $_REQUEST['license_expiry_date'];
            $id_country = $_REQUEST['id_country'];
            $license_country = $_REQUEST['license_country'];
            $job_title = $_REQUEST['job_title'];
            $sponsor = $_REQUEST['sponsor'];
            $street_address = $_REQUEST['street_address'];
            $district_address = $_REQUEST['district_address'];
            $old_id_image = $_REQUEST['old_id_image'];
            $old_license_image = $_REQUEST['old_license_image'];*/

            $first_element_of_id_number = substr($id_no, 0, 1);
            if (($id_type == '243' && $first_element_of_id_number != '1') || ($id_type == '68' && $first_element_of_id_number != '2')) {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
            //$request = custom::isNullToEmpty($request);


            if ($old_id_no != $id_no) {
                $users = $this->page->checkIfIndividualUserExist(array('id_no' => $id_no));
                if ($users) {
                    $response['status'] = 0;
                    $response['message'] = Lang::get('labels.user_already_exist_msg');
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } elseif ($old_email != $email) {
                $users = $this->page->checkIfIndividualUserExist(array('email' => $email));
                if ($users) {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'A user already exist with the email address that you inserted.' : 'A user already exist with the email address that you inserted.');
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }

            $userData['name'] = $first_name . ' ' . $last_name;
            $userData['email'] = $email;
            if ($password != '') {
                $userData['password'] = md5($password);
            }
            $userData['updated_at'] = date('Y-m-d H:i:s');
            $user_update_by['id'] = $logged_in_user_id;
            $this->page->updateData('users', $userData, $user_update_by);

            $data['first_name'] = $first_name;
            $data['last_name'] = $last_name;
            $data['mobile_no'] = $mobile_no;
            $data['email'] = $email;
            $data['id_type'] = $id_type;
            $data['id_version'] = ($id_type == '68' || $id_type == '243' ? '1' : '');
            $data['id_no'] = $id_no;
            $data['sponsor'] = $sponsor;
            //$data['nationality'] = $nationality;
            //$data['dob'] = date('Y-m-d', strtotime($dob));
            /*if ($id_date_type == 'gregorian') {
                $data['id_expiry_date'] = date('Y-m-d', strtotime($id_expiry_date));
            } else {
                $date_for_hijri = explode('-', $id_expiry_date);
                $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
            }*/
            //echo $data['id_expiry_date'];exit();
            //$data['license_no'] = $license_no;
            //$data['license_id_type'] = $license_id_type;
            //$data['license_expiry_date'] = date('Y-m-d', strtotime($license_expiry_date));

            // New fields as suggested by Fozan
            //$data['id_country'] = $id_country;
            //$data['license_country'] = $license_country;
            //$data['id_date_type'] = ($id_date_type == 'gregorian' ? 'G' : 'H');
            //$data['job_title'] = $job_title;
            //$data['sponsor'] = $sponsor;
            //$data['street_address'] = $street_address;
            //$data['district_address'] = $district_address;

            /*$image = array();
            if ($_FILES['id_image'] && $_FILES['id_image'] != '') {
                $data['id_image'] = custom::uploadFile($_FILES['id_image']);
                $image['idImage'] = $data['id_image'];
            } else {
                $data['id_image'] = $old_id_image;
            }*/

            /*if ($_FILES['license_image'] && $_FILES['license_image'] != '') {
                $data['license_image'] = custom::uploadFile($_FILES['license_image']);
                $image['licenseImage'] = $data['license_image'];
            } else {
                $data['license_image'] = $old_license_image;
            }*/

            $update_by['id'] = $user_id;
            $updated = $this->page->updateData('individual_customer', $data, $update_by);

            $users_info = $this->page->getSingle('individual_customer', $update_by);
            /*if (isset($_FILES['id_image']) && $_FILES['id_image']['name'] != '' && pathinfo($_FILES['id_image'], PATHINFO_EXTENSION) != 'pdf') {
                $data['id_image'] = $this->convertImageToPdf($data['id_image'], $users_info->id_no, 'id');
            }
            if (isset($_FILES['license_image']) && $_FILES['license_image']['name'] != '' && pathinfo($_FILES['license_image'], PATHINFO_EXTENSION) != 'pdf') {
                $data['license_image'] = $this->convertImageToPdf($data['license_image'], $users_info->id_no, 'licence');
            }*/
            //$this->page->updateData('individual_customer', array('id_image' => $data['id_image'], 'license_image' => $data['license_image']), $update_by);

            $user_data = (array)$this->services->getUserInfo($users_info->id, 'individual');
            $user_data['mobile_no'] = custom::getPhoneNumber($user_data['mobile_no']);
            $response['status'] = 1;
            $response['message'] = Lang::get('labels.account_info_updated_msg');
            $response['response'] = $user_data;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function book()
    {
        try {
            // had to do this because of non-well formed numeric value error in arabic
            if ($_REQUEST['lang'] == 'ar' && ($_REQUEST['source'] == 'android' || $_REQUEST['source'] == 'huawei')) {
                foreach ($_REQUEST as $key => $val) {
                    $val = custom::convertArabicNumbersToEnglish($val);
                    $val = str_replace(array('٫', ',', '٬'), array('.', '.', ''), $val);
                    if ($key == 'pickup_time' || $key == 'dropoff_time') {
                        // $val = custom::convertEngTimeToArbTime($val, $_REQUEST['source']);
                    }
                    $post_data[$key] = $val;
                }
                // custom::dump($post_data);
                $_REQUEST = $post_data;
            }
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $_REQUEST['mobile_no'] = trim($_REQUEST['mobile_no']);
            $is_mobile_no_valid = custom::validate_mobile_no($_REQUEST['mobile_no']);
            if (!$is_mobile_no_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_mobile_no_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $id_no = $_REQUEST['id_no'];
            //mail("ahsan@astutesol.com","book service debug",implode($_REQUEST)); 69 vs 243 id_type issue android side
            $id_no = custom::convertArabicNumbersToEnglish($id_no);
            custom::validateIDNoForMobile($_REQUEST['id_type'], $id_no, $this->lang);
            $api_settings = custom::api_settings();
            $user_type = $_REQUEST['user_type']; // individual_customer, corporate_customer, guest
            $logged_in_user_id = custom::jwt_decode($_REQUEST['user_id']);
            if ($user_type == 'guest') {
                $logged_in_user_id = 0;
            }
            $original_rent = $_REQUEST['actual_price'];
            $rent_per_day = str_replace(',', '', $_REQUEST['discounted_price']);
            $days = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days = 30;
            }

            $cdw = $_REQUEST['cdw_charges'];
            $cdw_is_one_time_applicable_on_booking = $_REQUEST['cdw_is_one_time_applicable_on_booking'];

            $cdw_plus = $_REQUEST['cdw_plus_charges'];
            $cdw_plus_is_one_time_applicable_on_booking = $_REQUEST['cdw_plus_is_one_time_applicable_on_booking'];

            $gps = $_REQUEST['gps_charges'];
            $gps_is_one_time_applicable_on_booking = $_REQUEST['gps_is_one_time_applicable_on_booking'];

            $extra_driver = $_REQUEST['extra_driver_charges'];
            $extra_driver_is_one_time_applicable_on_booking = $_REQUEST['extra_driver_is_one_time_applicable_on_booking'];

            $baby_seat = $_REQUEST['baby_seat_charges'];
            $baby_seat_is_one_time_applicable_on_booking = $_REQUEST['baby_seat_is_one_time_applicable_on_booking'];

            $total_rent_for_all_days = $rent_per_day * $days;
            $first_name = $_REQUEST['first_name'];
            $last_name = $_REQUEST['last_name'];
            $id_type = $_REQUEST['id_type'];
            $mobile_no = custom::getPhoneNumber($_REQUEST['mobile_no']);
            $mobile_no = $mobile_no['country_code'] . $mobile_no['number'];
            $email = $_REQUEST['email'];
            $license_no = $_REQUEST['license_no'];
            $promo_code = $_REQUEST['promo_code'];
            $payment_method = $_REQUEST['payment_method'];
            $car_id = $_REQUEST['car_id'];
            $total_rent_after_discount_on_promo = $rent_per_day * $days;
            $discount_amount_per_day = (isset($_REQUEST['discount_amount_per_day']) && $_REQUEST['discount_amount_per_day'] > 0 ? $_REQUEST['discount_amount_per_day'] : 0);
            $promotion_id = $_REQUEST['promotion_id'];
            $renting_type_id = $_REQUEST['renting_type_id'];
            $dropoff_charges_amount = $_REQUEST['dropoff_charges_amount'];
            //$edit_booking_id = $_REQUEST['edit_booking_id'];
            $loyalty_type_used = $_REQUEST['loyalty_type_used'];
            $redeem_points_used = (isset($_REQUEST['redeem_points_used']) && $_REQUEST['redeem_points_used'] > 0 ? $_REQUEST['redeem_points_used'] : 0);
            $redeem_discount_availed = (isset($_REQUEST['redeem_discount_availed']) && $_REQUEST['redeem_discount_availed'] > 0 ? $_REQUEST['redeem_discount_availed'] : 0);

            $from_branch_id = $_REQUEST['from_branch_id'];
            $to_branch_id = $_REQUEST['to_branch_id'];
            $pickup_date = $_REQUEST['pickup_date'];
            $pickup_time = $_REQUEST['pickup_time'];
            $dropoff_date = $_REQUEST['dropoff_date'];
            $dropoff_time = $_REQUEST['dropoff_time'];
            $booking_source = $_REQUEST['source'];
            $app_version = $_REQUEST['app_version'];


            $sponsor = (isset($_REQUEST['sponsor']) ? $_REQUEST['sponsor'] : '');

            $address_street = (isset($_REQUEST['address_street']) ? $_REQUEST['address_street'] : '');
            $address_city = (isset($_REQUEST['address_city']) ? $_REQUEST['address_city'] : '');
            $address_state = (isset($_REQUEST['address_state']) ? $_REQUEST['address_state'] : '');
            $address_country = (isset($_REQUEST['address_country']) ? $_REQUEST['address_country'] : '');
            $address_post_code = (isset($_REQUEST['address_post_code']) ? $_REQUEST['address_post_code'] : '');

            $parking_fee = custom::parking_fee_for_branch($from_branch_id);
            $tamm_charges_for_branch = custom::tamm_charges_for_branch($from_branch_id);

            if (strpos($_REQUEST['pickup_time'], 'ص') !== false || strpos($_REQUEST['pickup_time'], 'م') !== false || strpos($_REQUEST['dropoff_time'], 'ص') !== false || strpos($_REQUEST['dropoff_time'], 'م') !== false) {
                $pickup_time = custom::convertEngTimeToArbTime($_REQUEST['pickup_time'], $booking_source);
                $dropoff_time = custom::convertEngTimeToArbTime($_REQUEST['dropoff_time'], $booking_source);
            } else {
                $pickup_time = $_REQUEST['pickup_time'];
                $dropoff_time = $_REQUEST['dropoff_time'];
            }
            // old code
            /*$is_delivery_mode = $_REQUEST['is_delivery_mode'];
            $pickup_delivery_coordinate = $_REQUEST['pickup_delivery_coordinate'];
            $dropoff_delivery_coordinate = $_REQUEST['dropoff_delivery_coordinate'];
            $delivery_charges = $_REQUEST['delivery_charges'];*/

            // new code
            $is_delivery_mode = (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] != '' ? $_REQUEST['is_delivery_mode'] : 0);
            $pickup_delivery_coordinate = (isset($_REQUEST['pickup_delivery_coordinate']) && $_REQUEST['pickup_delivery_coordinate'] != '' ? $_REQUEST['pickup_delivery_coordinate'] : '');
            $dropoff_delivery_coordinate = (isset($_REQUEST['dropoff_delivery_coordinate']) && $_REQUEST['dropoff_delivery_coordinate'] != '' ? $_REQUEST['dropoff_delivery_coordinate'] : '');
            $delivery_charges = (float)(isset($_REQUEST['delivery_charges']) && $_REQUEST['delivery_charges'] != '' ? $_REQUEST['delivery_charges'] : 0.00);

            if ($cdw_is_one_time_applicable_on_booking == 1) {
                $cdw_multiply_factor = 1;
            } else {
                $cdw_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($cdw_plus_is_one_time_applicable_on_booking == 1) {
                $cdw_plus_multiply_factor = 1;
            } else {
                $cdw_plus_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($gps_is_one_time_applicable_on_booking == 1) {
                $gps_multiply_factor = 1;
            } else {
                $gps_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($extra_driver_is_one_time_applicable_on_booking == 1) {
                $extra_driver_multiply_factor = 1;
            } else {
                $extra_driver_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($baby_seat_is_one_time_applicable_on_booking == 1) {
                $baby_seat_multiply_factor = 1;
            } else {
                $baby_seat_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            $book_for_hours = (isset($_REQUEST['book_for_hours']) && $_REQUEST['book_for_hours'] != '' ? $_REQUEST['book_for_hours'] : 0);

            if ($is_delivery_mode == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $subscribe_for_months = (isset($_REQUEST['subscribe_for_months']) && $_REQUEST['subscribe_for_months'] != '' ? $_REQUEST['subscribe_for_months'] : 0);
            if ($is_delivery_mode == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }


            $date_picked_up_for_hours_cal = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
            $date_dropped_off_for_hours_cal = new Carbon($_REQUEST['dropoff_date'] . ' ' . $dropoff_time);
            $hours_diff = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

            // date needed for push notifications
            $user_fcm_token = $_REQUEST['token'];
            $user_device_unique_id = $_REQUEST['device_unique_id'];

            $site_settings = custom::site_settings();

            if ($is_delivery_mode == 1) {
                $is_delivery_mode_to_save = 'yes';
            } elseif (isset($_REQUEST['is_subscription_with_delivery_flow']) && $_REQUEST['is_subscription_with_delivery_flow'] == 1) {
                $is_delivery_mode_to_save = 'yes';
            } else {
                $is_delivery_mode_to_save = ($is_delivery_mode == 2 ? 'hourly' : ($is_delivery_mode == 4 ? 'subscription' : 'no'));
            }

            // echo $delivery_charges;exit();
            if ($is_delivery_mode == 1 && ($delivery_charges == 0 || $delivery_charges == '')) {
                $delivery_charges = 0.00;
            }

            $olp_id = (isset($_REQUEST['olp_id']) ? $_REQUEST['olp_id'] : 0);

            $current_time = date('Y-m-d H:i:s');

            $this->checkIfUserBlacklistedOrSimahBlock($id_no, $id_type);

            $this->validateEmailAndIdNo($email, $id_no);

            ini_set('max_execution_time', 600);

            $session_data['first_name'] = $first_name;
            $session_data['last_name'] = $last_name;
            $session_data['id_type'] = $id_type;
            $session_data['id_version'] = $id_version = ($id_type == '68' || $id_type == '243' ? '1' : '');
            $session_data['id_no'] = $id_no;
            $session_data['mobile_no'] = $mobile_no;
            $session_data['email'] = $email;
            $session_data['license_no'] = $license_no;
            // New fields suggested by Fozan

            $user_data['first_name'] = $first_name;
            $user_data['last_name'] = $last_name;
            $user_data['id_type'] = $id_type;
            $user_data['id_version'] = $id_version;
            $user_data['id_no'] = $id_no;
            $user_data['mobile_no'] = $mobile_no;
            $user_data['email'] = $email;
            $user_data['license_no'] = $license_no;
            $user_data['sponsor'] = $sponsor;
            //$user_data['loyalty_card_type'] = 'Silver';
            //$user_data['loyalty_points'] = '0';

            $user_data['address_street'] = $address_street;
            $user_data['address_city'] = $address_city;
            $user_data['address_state'] = $address_state;
            $user_data['address_country'] = $address_country;
            $user_data['address_post_code'] = custom::convertArabicNumbersToEnglish($address_post_code);

            $session_data['promo_code'] = $promo_code;
            $session_data['payment_method'] = $payment_method;
            $session_data['car_id'] = $car_id;
            $session_data['rent_per_day'] = $rent_per_day;
            $session_data['total_rent_for_all_days'] = $total_rent_for_all_days; // Only rent multiplied by days
            $session_data['total_rent_after_discount_on_promo'] = $total_rent_after_discount_on_promo;
            $session_data['discount_amount_per_day'] = $discount_amount_per_day;
            $session_data['promotion_id'] = $promotion_id;
            $session_data['renting_type_id'] = $renting_type_id;
            //Session::put('payment_form_data', $session_data);
            //Session::save();

            // we can check here for survey thing

            // Getting branch data to get its prefix
            $get_branch_data_by['id'] = $from_branch_id;
            $branch_info = $this->page->getSingle('branch', $get_branch_data_by);
            // Saving booking information
            $booking_info['car_model_id'] = $car_id;
            $booking_info['from_location'] = $from_branch_id;
            $booking_info['to_location'] = $to_branch_id;
            $booking_info['from_date'] = date('Y-m-d H:i:s', strtotime($pickup_date . ' ' . $pickup_time));
            $booking_info['to_date'] = date('Y-m-d H:i:s', strtotime($dropoff_date . ' ' . $dropoff_time));
            $booking_info['booking_status'] = 'Not Picked';
            $booking_info['sync'] = 'N';
            $booking_info['renting_type_id'] = $renting_type_id;
            $booking_info['type'] = $user_type;
            $booking_info['pickup_delivery_lat_long'] = $pickup_delivery_coordinate;
            $booking_info['dropoff_delivery_lat_long'] = $dropoff_delivery_coordinate;
            $booking_info['is_delivery_mode'] = $is_delivery_mode_to_save;
            $booking_info['booking_source'] = $booking_source;
            $booking_info['lang'] = $this->lang;
            $booking_info['app_version'] = $app_version;
            $booking_info['downloaded_from'] = isset($_REQUEST['downloaded_from']) ? $_REQUEST['downloaded_from'] : '';
            $booking_info['subscription_with_delivery_flow'] = (isset($_REQUEST['is_subscription_with_delivery_flow']) && $_REQUEST['is_subscription_with_delivery_flow'] == 1 ? 'on' : 'off');

            // echo '<pre>';print_r($booking_info);exit();

            if (isset($edit_booking_id) && $edit_booking_id > 0) {
                $booking_info['updated_at'] = $current_time;
                $updated = $this->page->updateData('booking', $booking_info, array('id' => $edit_booking_id));
                // delete data from child tables
                // booking_individual_payment_method, booking_individual_user, booking_individual_guest, booking_payment, booking_cc_payment
                $this->page->deleteData('booking_individual_payment_method', array('booking_id' => $edit_booking_id));
                $this->page->deleteData('booking_individual_user', array('booking_id' => $edit_booking_id));
                $this->page->deleteData('booking_individual_guest', array('booking_id' => $edit_booking_id));
                $this->page->deleteData('booking_payment', array('booking_id' => $edit_booking_id));
                $this->page->deleteData('booking_cc_payment', array('booking_id' => $edit_booking_id));
                $this->page->deleteData('booking_sadad_payment', array('booking_id' => $edit_booking_id));
                $savedBookingId = $edit_booking_id;
            } else {
                $booking_info['created_at'] = $current_time;
                $savedBookingId = $this->page->saveData('booking', $booking_info);
            }
            // Generating reservation code and updating in database
            $booking_info_extra['reservation_code'] = custom::generateReservationCode($branch_info->prefix, $savedBookingId, 'M');
            $updateBookingInfoBy['id'] = $savedBookingId;
            $bookingUpdated = $this->page->updateData('booking', $booking_info_extra, $updateBookingInfoBy);

            $check_if_user_exist_by_email['email'] = $email;
            $userExistWithEmail = $this->page->getSingle('individual_customer', $check_if_user_exist_by_email);
            $check_if_user_exist_by_id_no['id_no'] = $id_no;
            $userExistWithIdNo = $this->page->getSingle('individual_customer', $check_if_user_exist_by_id_no);

            if ($userExistWithEmail || $userExistWithIdNo) {
                if ($userExistWithEmail) {
                    $update_customer_info_by['email'] = $email;
                    $individual_customer_id = $userExistWithEmail->id;
                } else {
                    $update_customer_info_by['id_no'] = $id_no;
                    $individual_customer_id = $userExistWithIdNo->id;
                }

                $this->page->updateData('individual_customer', $user_data, $update_customer_info_by);
                $getIndCustomer = $this->page->getSingle('individual_customer', $update_customer_info_by);
                if ($getIndCustomer->uid > 0) {
                    $this->page->updateData('users', array('name' => $first_name . ' ' . $last_name, 'email' => $email), array('id' => $getIndCustomer->uid));
                }
            } else {
                $user_data['loyalty_card_type'] = 'Bronze';
                $user_data['loyalty_points'] = 0;
                $individual_customer_id = $this->page->saveData('individual_customer', $user_data);
            }


            // saving user data for push notifications
            $count_notification_tokens = $this->page->getRowsCount('survey_notification_tokens', array('device_unique_id' => $user_device_unique_id));
            if ($count_notification_tokens > 0) {
                $notification_data['customer_id'] = $individual_customer_id;
                $notification_data['token'] = $user_fcm_token;
                $notification_data['device_type'] = $booking_source;
                $notification_data['created_at'] = date('Y-m-d H:i:s');
                $update_by['device_unique_id'] = $user_device_unique_id;
                $this->page->updateData('survey_notification_tokens', $notification_data, $update_by);
            } else {
                $notification_data['customer_id'] = $individual_customer_id;
                $notification_data['token'] = $user_fcm_token;
                $notification_data['device_type'] = $booking_source;
                $notification_data['device_unique_id'] = $user_device_unique_id;
                $notification_data['created_at'] = date('Y-m-d H:i:s');
                $this->page->saveData('survey_notification_tokens', $notification_data);
            }


            // Saving booking payment method
            if ($payment_method == 'cc' || $payment_method == 'sadad' || $payment_method == 'cash') {
                if ($payment_method == 'cc') {
                    $method_is = 'Credit Card';
                } elseif ($payment_method == 'sadad') {
                    $method_is = 'Sadad';
                } elseif ($payment_method == 'cash') {
                    $method_is = 'Cash';
                }
                $payment_method_data['booking_id'] = $savedBookingId;
                $payment_method_data['payment_method'] = $method_is;
                $this->page->saveData('booking_individual_payment_method', $payment_method_data);
            }

            // checking if posted email exist in users table
            $checkingIfUserExistAlredy = $this->page->getSingle('users', array('email' => $email));

            if ((isset($logged_in_user_id) && $logged_in_user_id > 0) || ($checkingIfUserExistAlredy && $checkingIfUserExistAlredy->email != '')) {
                $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => $savedBookingId));
            }

            // Saving payment against individual or guest user
            if (isset($logged_in_user_id) && $logged_in_user_id > 0) {
                $logged_in_user_data = $this->page->getSingle('individual_customer', array('id' => $logged_in_user_id));
                $booking_individual_user_data['booking_id'] = $savedBookingId;
                $booking_individual_user_data['uid'] = $logged_in_user_data->uid;
                $this->page->saveData('booking_individual_user', $booking_individual_user_data);
            } elseif ($checkingIfUserExistAlredy && $checkingIfUserExistAlredy->email != '') {
                $booking_individual_user_data_for_user['booking_id'] = $savedBookingId;
                $booking_individual_user_data_for_user['uid'] = $checkingIfUserExistAlredy->id;
                $this->page->saveData('booking_individual_user', $booking_individual_user_data_for_user);
            } else {
                $booking_individual_guest_data['booking_id'] = $savedBookingId;
                $booking_individual_guest_data['individual_customer_id'] = $individual_customer_id;
                $this->page->saveData('booking_individual_guest', $booking_individual_guest_data);
            }

            // Saving booking payment details
            $booking_payment_data['booking_id'] = $savedBookingId;
            $booking_payment_data['rent_price'] = $rent_per_day;
            $booking_payment_data['original_rent'] = $original_rent; // saving old rent that is without any discounts

            if ($promotion_id > 0) {
                $promotion_offer = DB::table('promotion_offer')->where('id', $promotion_id)->first();
                if ($promotion_offer && $promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types' && $is_delivery_mode == 4) {
                    $booking_payment_data['rent_price'] = $original_rent;
                }
            }

            $booking_payment_data['cdw_price'] = $cdw;
            $booking_payment_data['cdw_price_is_one_time_applicable_on_booking'] = $cdw_is_one_time_applicable_on_booking;

            $booking_payment_data['cdw_plus_price'] = $cdw_plus;
            $booking_payment_data['cdw_plus_price_is_one_time_applicable_on_booking'] = $cdw_plus_is_one_time_applicable_on_booking;

            $booking_payment_data['gps_price'] = $gps;
            $booking_payment_data['gps_price_is_one_time_applicable_on_booking'] = $gps_is_one_time_applicable_on_booking;

            $booking_payment_data['extra_driver_price'] = $extra_driver;
            $booking_payment_data['extra_driver_price_is_one_time_applicable_on_booking'] = $extra_driver_is_one_time_applicable_on_booking;

            $booking_payment_data['baby_seat_price'] = $baby_seat;
            $booking_payment_data['baby_seat_price_is_one_time_applicable_on_booking'] = $baby_seat_is_one_time_applicable_on_booking;

            $booking_payment_data['promotion_offer_id'] = $promotion_id;
            $booking_payment_data['promotion_offer_code_used'] = isset($_REQUEST['promo_code']) && $_REQUEST['promo_code'] != "" ? $_REQUEST['promo_code'] : "";
            $booking_payment_data['discount_price'] = $discount_amount_per_day;
            $booking_payment_data['total_rent_after_discount'] = $total_rent_after_discount_on_promo; // we have to fix this as it only has rent * days, add other charges to it also
            $booking_payment_data['dropoff_charges'] = $dropoff_charges_amount;
            $booking_payment_data['delivery_charges'] = $delivery_charges;
            $booking_payment_data['parking_fee'] = $parking_fee;
            $booking_payment_data['tamm_charges_for_branch'] = $tamm_charges_for_branch;
            $booking_payment_data['no_of_days'] = ($is_delivery_mode == 2 ? $hours_diff : $days);
            $booking_payment_data['loyalty_type_used'] = $loyalty_type_used;
            $booking_payment_data['redeem_points'] = $redeem_points_used;
            $booking_payment_data['redeem_discount_availed'] = $redeem_discount_availed;

            $booking_payment_data['qitaf_request'] = (isset($_REQUEST['qitaf_request']) ? str_replace('.', ',', $_REQUEST['qitaf_request']) : '');
            $booking_payment_data['niqaty_request'] = (isset($_REQUEST['niqaty_request']) ? base64_decode($_REQUEST['niqaty_request']) : '');
            $booking_payment_data['anb_request'] = (isset($_REQUEST['anb_request']) ? $_REQUEST['anb_request'] : '');
            $booking_payment_data['mokafaa_request'] = (isset($_REQUEST['mokafaa_request']) ? $_REQUEST['mokafaa_request'] : '');

            $booking_payment_data['qitaf_amount'] = $qitaf_amount = (isset($_REQUEST['qitaf_amount']) && $_REQUEST['qitaf_amount'] > 0 ? $_REQUEST['qitaf_amount'] : 0);
            $booking_payment_data['niqaty_amount'] = $niqaty_amount = (isset($_REQUEST['niqaty_amount']) && $_REQUEST['niqaty_amount'] > 0 ? $_REQUEST['niqaty_amount'] : 0);
            $booking_payment_data['anb_amount'] = $anb_amount = (isset($_REQUEST['anb_amount']) && $_REQUEST['anb_amount'] > 0 ? $_REQUEST['anb_amount'] : 0);
            $booking_payment_data['mokafaa_amount'] = $mokafaa_amount = (isset($_REQUEST['mokafaa_amount']) && $_REQUEST['mokafaa_amount'] > 0 ? $_REQUEST['mokafaa_amount'] : 0);

            $loyalty_program_id = (isset($_REQUEST['loyalty_program_id']) && $_REQUEST['loyalty_program_id'] > 0 ? $_REQUEST['loyalty_program_id'] : '');
            $booking_payment_data['loyalty_program_for_oracle'] = custom::get_loyalty_program_used_for_booking($loyalty_program_id);
            $booking_payment_data['is_promo_discount_on_total'] = $is_promo_discount_on_total = custom::is_promo_discount_on_total($booking_payment_data['promotion_offer_id']);

            $pre_total_discount = 0;
            $post_total_discount = 0;

            if ($is_promo_discount_on_total == 1) {
                $post_total_discount = $discount_amount_per_day;
            } else {
                $pre_total_discount = $discount_amount_per_day;
            }

            $total_sum = ($rent_per_day * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch - $redeem_discount_availed;

            $total_sum_without_deducting_redeem = ($rent_per_day * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;

            $total_sum_with_deducting_redeem = ($rent_per_day * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch - $redeem_discount_availed;

            $site_settings = custom::site_settings();
            $vat_mode = $site_settings->vat_mode_for_mobile;
            $vat_percentage = ($vat_mode == 'on' && $site_settings->vat_percentage > 0 ? $site_settings->vat_percentage : 0);
            $vat = ($vat_percentage / 100) * $total_sum_without_deducting_redeem; // calculating VAT on total sum
            $booking_payment_data['total_sum'] = $total_sum_without_deducting_redeem + $vat - $qitaf_amount - $niqaty_amount - $anb_amount - $mokafaa_amount - $redeem_discount_availed - $post_total_discount; // adding value added tax to total amount
            $booking_payment_data['vat_percentage'] = $vat_percentage;
            $booking_payment_data['vat_applied'] = $vat;


            // did this here to fix the issue of duplicated qitaf request ids and reversal, happening in mobile apps only, updating old one before adding new one
            if ($booking_payment_data['qitaf_request'] != '') {
                $this->page->updateData('booking_payment', ['qitaf_request' => ''], ['qitaf_request' => $booking_payment_data['qitaf_request']]);
            }

            $booking_payment_data['car_rate_is_with_additional_utilization_rate'] = (isset($_REQUEST['car_rate_is_with_additional_utilization_rate']) && $_REQUEST['car_rate_is_with_additional_utilization_rate'] == 1 ? 1 : 0);

            $booking_payment_data['cpid'] = $_REQUEST['cpid'];

            if ($is_delivery_mode == 4) { // only to be done for subscription mode
                $booking_payment_data['subscribe_for_months'] = $subscribe_for_months;
                $car_prices = $this->page->getSingle('car_price', ['id' => $booking_payment_data['cpid']]);
                $booking_payment_data['three_month_subscription_price_for_car'] = $car_prices->three_month_subscription_price;
                $booking_payment_data['six_month_subscription_price_for_car'] = $car_prices->six_month_subscription_price;
                $booking_payment_data['nine_month_subscription_price_for_car'] = $car_prices->nine_month_subscription_price;
                $booking_payment_data['twelve_month_subscription_price_for_car'] = $car_prices->twelve_month_subscription_price;
            }

            $booking_payment_data['is_free_cdw_promo_applied'] = (isset($_REQUEST['is_free_cdw_promo_applied']) && $_REQUEST['is_free_cdw_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_cdw_plus_promo_applied'] = (isset($_REQUEST['is_free_cdw_plus_promo_applied']) && $_REQUEST['is_free_cdw_plus_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_baby_seat_promo_applied'] = (isset($_REQUEST['is_free_baby_seat_promo_applied']) && $_REQUEST['is_free_baby_seat_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_driver_promo_applied'] = (isset($_REQUEST['is_free_driver_promo_applied']) && $_REQUEST['is_free_driver_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_open_km_promo_applied'] = (isset($_REQUEST['is_free_open_km_promo_applied']) && $_REQUEST['is_free_open_km_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_delivery_promo_applied'] = (isset($_REQUEST['is_free_delivery_promo_applied']) && $_REQUEST['is_free_delivery_promo_applied'] == 1 ? 1 : 0);
            $booking_payment_data['is_free_dropoff_promo_applied'] = (isset($_REQUEST['is_free_dropoff_promo_applied']) && $_REQUEST['is_free_dropoff_promo_applied'] == 1 ? 1 : 0);

            $this->page->saveData('booking_payment', $booking_payment_data);

            //Session::put('total_amount_for_transaction', $booking_payment_data['total_sum']);
            //Session::save();

            $booking_details['booking_details'] = $this->services->getSingleBookingDetails($savedBookingId, 'individual_user');
            // params required for paytabs SDK
            if ($is_delivery_mode == 1) {
                $from_branch_id_for_cc = $_REQUEST['from_branch_id'];
            } else {
                $from_branch_id_for_cc = 0;
            }
            $paytabs_params['pt_merchant_email'] = $api_settings->paytabs_merchant_email;
            $paytabs_params['paytabs_merchant_id'] = $api_settings->paytabs_merchant_id;
            $paytabs_params['pt_secret_key'] = $api_settings->paytabs_secret_key;
            $paytabs_params['pt_transaction_title'] = $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'];
            $paytabs_params['pt_amount'] = $booking_payment_data['total_sum'];
            $paytabs_params['pt_currency_code'] = 'SAR';
            $paytabs_params['pt_customer_email'] = $_REQUEST['email'];
            $paytabs_params['pt_customer_phone_number'] = $_REQUEST['mobile_no'];
            $paytabs_params['pt_order_id'] = $savedBookingId . '-' . $this->lang . '-' . $from_branch_id_for_cc . '-' . 'individual_customer';
            if ($this->lang == 'eng') {
                $paytabs_params['pt_product_name'] = $booking_details['booking_details']->car_model_eng_title . ' | ' . $booking_details['booking_details']->car_type_eng_title;
            } else {
                $paytabs_params['pt_product_name'] = $booking_details['booking_details']->car_model_arb_title . ' | ' . $booking_details['booking_details']->car_type_arb_title;
            }

            $userByEmail = $this->page->getRowsCount('users', array('email' => $email));
            if ($userByEmail > 0) {
                $userHasAccount = 1;
            } else {
                $userHasAccount = 0;
            }
            if ($payment_method == 'cc') {
                $booking_cc['booking_id'] = $savedBookingId;
                $booking_cc['status'] = 'pending';
                if (isset($_REQUEST['is_mada']) && $_REQUEST['is_mada'] == 1) {
                    $booking_cc['card_brand'] = 'Mada';
                }
                $site_settings = custom::site_settings();
                if ($site_settings->cc_company == 'sts') {
                    $booking_cc['payment_company'] = 'sts';
                } elseif ($site_settings->cc_company == 'hyper_pay') {
                    $booking_cc['payment_company'] = 'hyper_pay';
                }
                $this->page->saveData('booking_cc_payment', $booking_cc);
                $response['status'] = 1;
                $response['user_already_has_account'] = $userHasAccount;
                $response['message'] = "Thank you for booking at " . custom::getSiteName($this->lang) . ". You will shortly receive confirmation email and sms containing your booking details.";
                $response['response'] = array('payment_method' => 'cc', 'booking_details' => $booking_details['booking_details'], 'url' => '');
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($payment_method == 'sadad') {

                $booking_sadad['s_booking_id'] = $savedBookingId;
                $booking_sadad['s_status'] = 'pending';
                $booking_sadad['s_olp_id'] = $olp_id;
                $this->page->saveData('booking_sadad_payment', $booking_sadad);
                $dataForSadad = $_REQUEST;
                $dataForSadad['mobile_no'] = $mobile_no;
                $dataForSadad['transaction_amount'] = $booking_payment_data['total_sum'];
                $this->payWithSadad($olp_id, $dataForSadad, $savedBookingId, $paytabs_params, $is_delivery_mode);
                /*$response['status'] = 1;
                $response['message'] = "Thank you for booking at ".custom::getSiteName($this->lang)." You will shortly receive confirmation email and sms containing your booking details.";
                $response['response'] = array('payment_method' => 'sadad', 'booking_details' => $booking_details['booking_details']);
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();*/
            } else { // in case of cash on delivery
                // $this->sendEmailToUser($savedBookingId);
                // $this->sendToKeyAdmin($savedBookingId);
                $userPhoneNo = $mobile_no;
                $this->sendThankYouSMS($booking_info_extra['reservation_code'], $userPhoneNo);

                // Deducting and updating customer's redeem points
                $this->add_or_deduct_loyalty_points_for_customer($savedBookingId, 'deduct');

                // code to send email and sms to branch agent starts here
                if ($is_delivery_mode == 1) {
                    $customer_name = $first_name . ' ' . $last_name;
                    // $this->send_email_to_branch_agent($savedBookingId, $branch_info->email, $customer_name);
                    $this->send_sms_to_branch_agent($booking_info_extra['reservation_code'], $branch_info->mobile, $customer_name);
                }
                // ends here

                $response['status'] = 1;
                $response['user_already_has_account'] = $userHasAccount;
                $response['message'] = "Thank you for booking at " . custom::getSiteName($this->lang) . ". You will shortly receive confirmation email and sms containing your booking details.";
                $response['response'] = array('payment_method' => 'cash', 'booking_details' => $booking_details['booking_details'], 'url' => '');
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function applyCoupon()
    {
        try {
            $site_settings = custom::site_settings();
            $type = (isset($_REQUEST['type']) ? 'android' : 'ios'); // as the iOS app is live now so only android dev is sending this param

            $coupon_code = $_REQUEST['coupon'];
            $coupon_code = urldecode($coupon_code);
            $coupon_code = str_replace('—', '--', $coupon_code);

            $discounted_price = floatval(str_replace(',', '', $_REQUEST['discounted_price'])); // this is already multiplied with days

            $old_price = $_REQUEST['actual_price'];
            $pickup_date = $_REQUEST['pickup_date'];



            if (strpos($_REQUEST['pickup_time'], 'ص') !== false || strpos($_REQUEST['pickup_time'], 'م') !== false) {
                $_REQUEST['pickup_time'] = $pickup_time = custom::convertEngTimeToArbTime($_REQUEST['pickup_time'], $type);
            } else {
                $_REQUEST['pickup_time'] = $pickup_time = $_REQUEST['pickup_time'];
            }

            $from_region_id = $_REQUEST['from_region_id'];
            $from_city_id = $_REQUEST['from_city_id'];
            $from_branch_id = $_REQUEST['from_branch_id'];
            $days = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days = 30;
            }

            if ($type == 'ios') {
                $discounted_price = $discounted_price * $days;
            }

            $discounted_price_per_day = $discounted_price / $days;

            $car_id = $_REQUEST['car_id'];
            $loyalty_discount_percent = $_REQUEST['loyalty_discount_percent']; // this is already auto promo discount
            $actual_loyalty_discount_percentage = $_REQUEST['actual_loyalty_discount_percentage'];

            $is_delivery_mode = (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] != '' ? $_REQUEST['is_delivery_mode'] : 0);

            $cdw_charges = $_REQUEST['cdw_charges'];
            $cdw_is_one_time_applicable_on_booking = $_REQUEST['cdw_is_one_time_applicable_on_booking'];

            $cdw_plus_charges = $_REQUEST['cdw_plus_charges'];
            $cdw_plus_is_one_time_applicable_on_booking = $_REQUEST['cdw_plus_is_one_time_applicable_on_booking'];

            $gps_charges = $_REQUEST['gps_charges'];
            $gps_is_one_time_applicable_on_booking = $_REQUEST['gps_is_one_time_applicable_on_booking'];

            $extra_driver_charges = $_REQUEST['extra_driver_charges'];
            $extra_driver_is_one_time_applicable_on_booking = $_REQUEST['extra_driver_is_one_time_applicable_on_booking'];

            $baby_seat_charges = $_REQUEST['baby_seat_charges'];
            $baby_seat_is_one_time_applicable_on_booking = $_REQUEST['baby_seat_is_one_time_applicable_on_booking'];

            if ($cdw_is_one_time_applicable_on_booking == 1) {
                $cdw_multiply_factor = 1;
            } else {
                $cdw_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($cdw_plus_is_one_time_applicable_on_booking == 1) {
                $cdw_plus_multiply_factor = 1;
            } else {
                $cdw_plus_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($gps_is_one_time_applicable_on_booking == 1) {
                $gps_multiply_factor = 1;
            } else {
                $gps_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($extra_driver_is_one_time_applicable_on_booking == 1) {
                $extra_driver_multiply_factor = 1;
            } else {
                $extra_driver_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($baby_seat_is_one_time_applicable_on_booking == 1) {
                $baby_seat_multiply_factor = 1;
            } else {
                $baby_seat_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            $dropoff_charges_amount = $_REQUEST['dropoff_charges_amount'];
            $delivery_charges = (float)(isset($_REQUEST['delivery_charges']) ? $_REQUEST['delivery_charges'] : 0.00);

            $parking_fee = custom::parking_fee_for_branch($from_branch_id);
            $tamm_charges_for_branch = custom::tamm_charges_for_branch($from_branch_id);

            $total_amount_without_vat = ($discounted_price * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
            $vat_amount = ($site_settings->vat_percentage / 100) * $total_amount_without_vat;
            $total_amount_with_vat = $total_amount_without_vat + $vat_amount;

            $promo_discount_with_coupon = $this->page->checkIfPromoApplicable($car_id, $coupon_code, date('Y-m-d H:i:s', strtotime($pickup_date . ' ' . $pickup_time)), $from_region_id, $from_city_id, $from_branch_id, $total_amount_with_vat, $days);

            $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount_with_coupon, $pickup_date);

            $is_coupon_usage_fine = custom::is_coupon_usage_fine($coupon_code, $_REQUEST['id_no'], $this->lang);

            if ($is_coupon_usage_fine['status'] == false) {
                $responseArr['status'] = 0;
                $responseArr['message'] = $is_coupon_usage_fine['message'];
                $responseArr['response'] = "";
                echo json_encode($responseArr);
                exit();
            }

            if ($promo_discount_with_coupon && $promo_discount_with_coupon->type != 'Fixed Discount on Booking Total Using Coupon For All Booking Types') {
                if ($is_delivery_mode != 4 && stripos($promo_discount_with_coupon->type, 'subscription -') !== false) {
                    $promo_discount_with_coupon = false;
                } elseif ($is_delivery_mode == 4 && stripos($promo_discount_with_coupon->type, 'subscription -') === false) {
                    $promo_discount_with_coupon = false;
                }
            }

            if ($promo_discount_with_coupon && $promo_discount_with_coupon->discount != '' && $coupon_is_valid_for_pickup_day && $is_coupon_usage_fine['status'] == true) {

                if ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Fixed Price by Using Coupon') {
                    $promo_discount = $promo_discount_with_coupon->discount;
                    $calculated_rent_per_day_after_discount = $old_price - $promo_discount;
                    if ($calculated_rent_per_day_after_discount < $discounted_price) {
                        $promo_discount = $promo_discount_with_coupon->discount;
                        $calculated_rent_per_day_after_discount = round($calculated_rent_per_day_after_discount, 2);
                        $promotion_id = $promo_discount_with_coupon->id;
                        $response['discount_amount'] = $promo_discount;
                        $response['rent_per_day_after_discount'] = $calculated_rent_per_day_after_discount;
                        $response['promotion_id'] = $promotion_id;
                        $response['total_amount'] = ($calculated_rent_per_day_after_discount * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $response['is_promo_discount_on_total'] = 0;

                        $response['is_promo_applied_for_extras'] = 0;

                        $responseArr['status'] = 1;
                        $responseArr['message'] = "";
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    } else {
                        $responseArr['status'] = 0;
                        $responseArr['message'] = ($this->lang == 'eng' ? 'The coupon you have entered is not applied. You already have greater discount.' : 'لا يمكن استخدام الرمز الترويجي المدخل لانه لديك خصم اكبر من خصم الرمز');
                        $responseArr['response'] = "";
                        echo json_encode($responseArr);
                        exit();
                    }
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Percentage by Using Coupon') {
                    $promo_discount_percent = $promo_discount_with_coupon->discount;
                    if ($loyalty_discount_percent > 0 && $promo_discount_percent > $loyalty_discount_percent) {
                        $promo_discount = round($promo_discount_percent * $old_price / 100, 2);
                        $calculated_rent_per_day_after_discount = round(($old_price - $promo_discount), 2);
                        $promotion_id = $promo_discount_with_coupon->id;
                        $status = 1;
                        $message = "";
                        $response['discount_amount'] = $promo_discount;
                        $response['rent_per_day_after_discount'] = $calculated_rent_per_day_after_discount;
                        $response['promotion_id'] = $promotion_id;
                        $response['total_amount'] = ($calculated_rent_per_day_after_discount * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $response['is_promo_discount_on_total'] = 0;

                        $response['is_promo_applied_for_extras'] = 0;

                        $responseArr['status'] = $status;
                        $responseArr['message'] = $message;
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    } else {
                        $status = 0;
                        $message = ($this->lang == 'eng' ? 'The coupon you have entered is not applied. You already have greater discount.' : 'لا يمكن استخدام الرمز الترويجي المدخل لانه لديك خصم اكبر من خصم الرمز');
                        $response = "";
                        $responseArr['status'] = $status;
                        $responseArr['message'] = $message;
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    }
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Percentage by Using Coupon on Loyalty') {
                    $promo_discount_percent = $promo_discount_with_coupon->discount + $actual_loyalty_discount_percentage;
                    $promo_discount = round($promo_discount_percent * $old_price / 100, 2);
                    $calculated_rent_per_day_after_discount = round(($old_price - $promo_discount), 2);
                    $promotion_id = $promo_discount_with_coupon->id;
                    $status = 1;
                    $message = "";
                    $response['discount_amount'] = $promo_discount;
                    $response['rent_per_day_after_discount'] = $calculated_rent_per_day_after_discount;
                    $response['promotion_id'] = $promotion_id;
                    $response['total_amount'] = ($calculated_rent_per_day_after_discount * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                    $response['is_promo_discount_on_total'] = 0;

                    $response['is_promo_applied_for_extras'] = 0;

                    $responseArr['status'] = $status;
                    $responseArr['message'] = $message;
                    $responseArr['response'] = $response;
                    echo json_encode($responseArr);
                    exit();
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Fixed Daily Rate Coupon') {
                    $promotionAmount = $promo_discount_with_coupon->discount;
                    if ($promotionAmount < $discounted_price) {
                        $promo_discount = round($old_price - $promotionAmount, 2);
                        $promotion_id = $promo_discount_with_coupon->id;
                        $rent_per_day = $promotionAmount;
                        $status = 1;
                        $message = "";
                        $response['discount_amount'] = $promo_discount;
                        $response['rent_per_day_after_discount'] = $rent_per_day;
                        $response['promotion_id'] = $promotion_id;
                        $response['total_amount'] = ($rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $response['is_promo_discount_on_total'] = 0;

                        $response['is_promo_applied_for_extras'] = 0;

                        $responseArr['status'] = $status;
                        $responseArr['message'] = $message;
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    } else {
                        $status = 0;
                        $message = ($this->lang == 'eng' ? 'The coupon you have entered is not applied. You already have greater discount.' : 'لا يمكن استخدام الرمز الترويجي المدخل لانه لديك خصم اكبر من خصم الرمز');
                        $response = "";
                        $responseArr['status'] = $status;
                        $responseArr['message'] = $message;
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    }

                } elseif (
                    $promo_discount_with_coupon &&
                    (
                        $promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon' ||
                        $promo_discount_with_coupon->type == 'Percentage Discount on Booking Total Using Coupon' ||
                        ($promo_discount_with_coupon->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' && $is_delivery_mode == 4) ||
                        ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types')
                    )
                ) {

                    if ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types' && $is_delivery_mode != 4) {
                        $rent_per_day_for_promo_code_check = $discounted_price_per_day;
                    } else {
                        $rent_per_day_for_promo_code_check = $old_price;
                        if ($actual_loyalty_discount_percentage > 0 && $promo_discount_with_coupon->apply_discount_with_loyalty_discount == 1) {
                            $loyalty_discount = round(($actual_loyalty_discount_percentage / 100) * $old_price, 2);
                            $rent_per_day_for_promo_code_check = $old_price - $loyalty_discount;
                        }
                    }

                    $total_per_1_day = $rent_per_day_for_promo_code_check + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                    $total_amount_without_vat = ($total_per_1_day * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                    $vat_amount = ($site_settings->vat_percentage / 100) * $total_amount_without_vat;
                    $total_amount_with_vat = $total_amount_without_vat + $vat_amount;

                    if ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon' || $promo_discount_with_coupon->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' || $promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types') {
                        $promo_discount = round($promo_discount_with_coupon->discount, 2); // its fixed discount on booking total
                    } else {
                        $promo_discount = round(($promo_discount_with_coupon->discount / 100) * $total_amount_with_vat, 2); // its percentage discount on booking total
                    }

                    $total_amount_with_vat_after_promo_discount = $total_amount_with_vat - $promo_discount;

                    if ($total_amount_with_vat_after_promo_discount > 0) {

                        $response['discount_amount'] = $promo_discount;
                        $response['rent_per_day_after_discount'] = $rent_per_day_for_promo_code_check;
                        $response['promotion_id'] = $promo_discount_with_coupon->id;
                        $response['total_amount'] = $total_amount_without_vat;
                        $response['is_promo_discount_on_total'] = 1;

                        $response['is_promo_applied_for_extras'] = 0;

                        $responseArr['status'] = 1;
                        $responseArr['message'] = "";
                        $responseArr['response'] = $response;
                        echo json_encode($responseArr);
                        exit();
                    } else {
                        $responseArr['status'] = 0;
                        $responseArr['message'] = ($this->lang == 'eng' ? 'The coupon you have entered is not applied.' : 'القسيمة التي أدخلتها غير مطبقة.');
                        $responseArr['response'] = "";
                        echo json_encode($responseArr);
                        exit();
                    }
                } elseif ($promo_discount_with_coupon && (
                        ($promo_discount_with_coupon->type == 'Free CDW Using Coupon' && $cdw_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free CDW Plus Using Coupon' && $cdw_plus_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free Baby Seat Using Coupon' && $baby_seat_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free Driver Using Coupon' && $extra_driver_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free Open KM Using Coupon' && $gps_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free Delivery Using Coupon' && $delivery_charges > 0) ||
                        ($promo_discount_with_coupon->type == 'Free Drop Off Using Coupon' && $dropoff_charges_amount > 0)
                    )) {

                    $promo_discount = 0;
                    $is_free_cdw_promo_applied = 0;
                    $is_free_cdw_plus_promo_applied = 0;
                    $is_free_baby_seat_promo_applied = 0;
                    $is_free_driver_promo_applied = 0;
                    $is_free_open_km_promo_applied = 0;
                    $is_free_delivery_promo_applied = 0;
                    $is_free_dropoff_promo_applied = 0;

                    if ($promo_discount_with_coupon->type == 'Free CDW Using Coupon' && $cdw_charges > 0) {
                        $cdw_charges = 0;
                        $is_free_cdw_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free CDW Plus Using Coupon' && $cdw_plus_charges > 0) {
                        $cdw_plus_charges = 0;
                        $is_free_cdw_plus_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Baby Seat Using Coupon' && $baby_seat_charges > 0) {
                        $baby_seat_charges = 0;
                        $is_free_baby_seat_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Driver Using Coupon' && $extra_driver_charges > 0) {
                        $extra_driver_charges = 0;
                        $is_free_driver_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Open KM Using Coupon' && $gps_charges > 0) {
                        $gps_charges = 0;
                        $is_free_open_km_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Delivery Using Coupon' && $delivery_charges > 0) {
                        $delivery_charges = 0;
                        $is_free_delivery_promo_applied = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Drop Off Using Coupon' && $dropoff_charges_amount > 0) {
                        $dropoff_charges_amount = 0;
                        $is_free_dropoff_promo_applied = 1;
                    }

                    $promotion_id = $promo_discount_with_coupon->id;

                    $response['discount_amount'] = round($discounted_price_per_day - $promo_discount, 2);
                    $response['rent_per_day_after_discount'] = $discounted_price_per_day;
                    $response['promotion_id'] = $promotion_id;
                    $response['total_amount'] = ($discounted_price_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;

                    $response['is_promo_discount_on_total'] = 0;
                    $response['is_promo_applied_for_extras'] = 1;

                    $response['cdw_charges'] = $cdw_charges;
                    $response['cdw_plus_charges'] = $cdw_plus_charges;
                    $response['gps_charges'] = $gps_charges;
                    $response['extra_driver_charges'] = $extra_driver_charges;
                    $response['baby_seat_charges'] = $baby_seat_charges;
                    $response['dropoff_charges_amount'] = $dropoff_charges_amount;
                    $response['delivery_charges'] = $delivery_charges;

                    $response['is_free_cdw_promo_applied'] = $is_free_cdw_promo_applied;
                    $response['is_free_cdw_plus_promo_applied'] = $is_free_cdw_plus_promo_applied;
                    $response['is_free_baby_seat_promo_applied'] = $is_free_baby_seat_promo_applied;
                    $response['is_free_driver_promo_applied'] = $is_free_driver_promo_applied;
                    $response['is_free_open_km_promo_applied'] = $is_free_open_km_promo_applied;
                    $response['is_free_delivery_promo_applied'] = $is_free_delivery_promo_applied;
                    $response['is_free_dropoff_promo_applied'] = $is_free_dropoff_promo_applied;

                    $responseArr['status'] = 1;
                    $responseArr['message'] = "";
                    $responseArr['response'] = $response;
                    echo json_encode($responseArr);
                    exit();


                } else {
                    $status = 0;
                    $message = ($this->lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح');
                    $response = "";
                    $responseArr['status'] = $status;
                    $responseArr['message'] = $message;
                    $responseArr['response'] = $response;
                    echo json_encode($responseArr);
                    exit();
                }
            } else {
                $status = 0;
                $message = ($this->lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح');
                $response = "";
                $responseArr['status'] = $status;
                $responseArr['message'] = $message;
                $responseArr['response'] = $response;
                echo json_encode($responseArr);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getQueryString(Request $request)
    {
        try {
            $base_url = $this->lang_base_url;
            $data = $request->input();
            $request_string = http_build_query($data);
            $request_string = $base_url . '?' . $request_string;
            echo json_encode($request_string, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function signup_after_booking()
    {
        try {
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $this->checkIfMaintenanceModeOn();
            $first_name = $_REQUEST['first_name'];
            $last_name = $_REQUEST['last_name'];
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];

            $isPasswordStrong = custom::isPasswordStrong($password, $this->lang);
            if (!$isPasswordStrong['status']) {
                $response['status'] = 0;
                $response['message'] = $isPasswordStrong['message'];
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $confirm_password = $_REQUEST['confirm_password'];
            $id_no = $_REQUEST['id_no'];
            $id_no = custom::convertArabicNumbersToEnglish($id_no);
            $booking_id = $_REQUEST['booking_id'];

            $get_user_by_email['email'] = $email;
            $user = $this->page->getSingle('users', $get_user_by_email);
            $individual_customer_by_email = $this->page->getSingle('individual_customer', $get_user_by_email);
            $get_user_by_id_no['id_no'] = $id_no;
            $individual_customer_by_id_no = $this->page->getSingle('individual_customer', $get_user_by_id_no);

            if ($password != $confirm_password) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.password_and_confirm_password_not_match_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else if ($user && $user->email != '' && $user->type == 'individual_customer') {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.email_already_register_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($individual_customer_by_id_no && $individual_customer_by_id_no->id_no != '' && (int)$individual_customer_by_id_no->uid !== 0) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.id_number_already_register_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid !== 0) {
                $response['status'] = 0;
                $response['message'] = \Lang::get('labels.email_already_register_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {

                if ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid === 0) {
                    $individual_customer_id = $individual_customer_by_email->id;
                } else {
                    $individual_customer_id = $individual_customer_by_id_no->id;
                }


                $userData['name'] = $first_name . ' ' . $last_name;
                $userData['email'] = $email;
                $userData['password'] = md5($password);
                $userData['type'] = 'individual_customer';
                $userData['created_at'] = date('Y-m-d H:i:s');
                $userData['updated_at'] = date('Y-m-d H:i:s');
                $user_id = $this->page->saveData('users', $userData);

                $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => $booking_id));

                $guestBookings = $this->page->getMultipleRows('booking_individual_guest', array('individual_customer_id' => $individual_customer_id), 'booking_id');

                if ($guestBookings) //if the user had made any bookings as guest.
                {
                    foreach ($guestBookings as $guestBooking) {
                        $guestBookingId = $guestBooking->booking_id;
                        //Insert into booking_individual_user and update booking table to mention it as registered user bookings.
                        $this->page->saveData('booking_individual_user', array('booking_id' => $guestBookingId, 'uid' => $user_id));
                        //type individual_customer means its a registered user booking.
                        $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => $guestBookingId));
                    }
                }
                //Now we have fetched the guest bookings from getGuestBookings and saved them to ind. customer bookings, so we can delete bookings from booking_individual_guest table
                $this->page->deleteData('booking_individual_guest', array('individual_customer_id' => $individual_customer_id));
                $update_info_by['email'] = $email;
                $customer_data['uid'] = $user_id;
                $this->page->updateData('individual_customer', $customer_data, $update_info_by);
                custom::send_account_verification_links($user_id, $this->lang_base_url, $this->lang);

                $response['status'] = 1;
                $response['message'] = \Lang::get('labels.account_created_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();

            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function payWithSadad($olp_id, $dataForSadad, $booking_id, $paytabs_params, $is_delivery_mode)
    {
        try {
            $first_name = $dataForSadad['first_name'];
            $last_name = $dataForSadad['last_name'];
            $mobile_no = $dataForSadad['mobile_no'];
            $email = $dataForSadad['email'];
            $transaction_amount = $dataForSadad['transaction_amount'];
            if ($is_delivery_mode == 1) {
                $from_branch_id_for_sadad = $dataForSadad['from_branch_id'];
            } else {
                $from_branch_id_for_sadad = 0;
            }

            $userByEmail = $this->page->getRowsCount('users', array('email' => $email));
            if ($userByEmail > 0) {
                $userHasAccount = 1;
            } else {
                $userHasAccount = 0;
            }

            $booking = $this->page->getSingle('booking', array('id' => $booking_id));
            $car_model_id = $booking->car_model_id;
            // $booking_details = $this->services->getSingleBookingDetails($booking_id, 'individual_user');

            $car_info = $this->page->getSingleCarDetail($car_model_id);
            $api_settings = custom::api_settings();
            //mail('bilal_ejaz@astutesol.com', 'Sadad function called', 'email sent');
            $payment_data = array(
                'merchant_email' => $api_settings->paytabs_merchant_email,
                'secret_key' => $api_settings->paytabs_secret_key,
                //'merchant_email' => 'huda@paytabs.co',
                //'secret_key' => 'QGGJhB2GNCHM7MDzkAfD0n3Noc9v6nDX4jHmVJ3IlKvtx82RJivJFyw3KGyR4cO9vvvqfy9BiE5PM7Gwpcf5KM8vsHmxTaGpGbQV',
                'site_url' => custom::baseurl('/'),
                'return_url' => $this->lang_base_url . '/service/verifyPayment',
                'title' => $first_name . ' ' . $last_name,
                'cc_first_name' => $first_name,
                'cc_last_name' => $last_name,
                'cc_phone_number' => $mobile_no,
                'phone_number' => $mobile_no,
                'email' => $email,
                'products_per_title' => ($this->lang == 'eng' ? $car_info->eng_title . ' | ' . $car_info->car_type_eng_title : $car_info->arb_title . ' | ' . $car_info->car_type_arb_title),
                'unit_price' => $transaction_amount,
                'quantity' => '1',
                'other_charges' => '0.00',
                'amount' => $transaction_amount,
                'discount' => '0.00',
                'currency' => 'SAR',
                'reference_no' => $booking_id . '-' . $this->lang . '-' . $from_branch_id_for_sadad . '-' . 'individual_customer',
                'ip_customer' => '116.58.71.82',
                'ip_merchant' => '159.253.153.126',
                'address_shipping' => 'Flat 3021 Khobar',
                'state_shipping' => 'Khobar',
                'city_shipping' => 'Khobar',
                'postal_code_shipping' => '1234',
                'country_shipping' => 'SAU',
                'msg_lang' => ($this->lang == 'eng' ? 'English' : 'Arabic'), //Arabic
                'cms_with_version' => 'PHP Laravel 5.4',
                'olp_id' => strip_tags($olp_id)
            );

            $request_string1 = http_build_query($payment_data);

            $response_data = $this->sendRequest('https://www.paytabs.com/apiv2/create_sadad_payment', $request_string1);

            $object = json_decode($response_data);
            if ($object->result == 'success') {
                $response['status'] = 1;
                $response['user_already_has_account'] = $userHasAccount;
                $response['message'] = "";
                $response['response'] = array('payment_method' => 'sadad', 'booking_details' => $booking_details, 'url' => $object->payment_url);
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                //$error_message = ($this->lang == 'eng' ? 'The OLP ID you entered is not valid.' : 'حساب سداد المدخل غير صحيح');
                $error_message = $object->result;
                $response['status'] = 0;
                $response['user_already_has_account'] = $userHasAccount;
                $response['message'] = $error_message;
                $response['response'] = "";
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function verifyPayment()
    {
        try {
            if (isset($_REQUEST['payment_reference'])) {
                $verifyPayment = $this->verify($_REQUEST['payment_reference']);
                if ($verifyPayment['status'] == false) {
                    $error_message = $verifyPayment['error_message'];
                    $redURL = $this->lang_base_url . '/services/sadad-confirmation?success=0&message=' . $error_message; // sadad_confirmation function is in front page controller
                    header('Location:' . $redURL);
                    exit();
                } elseif ($verifyPayment['status'] == true) {
                    $error_message = 'Your payment has been successfully made. Thank you.';
                    $redURL = $this->lang_base_url . '/services/sadad-confirmation?success=1&message=' . $error_message;
                    header('Location:' . $redURL);
                    exit();
                }
            } else {
                $error_message = 'We did not receive any response from the payment merchant. Please try again.';
                $redURL = $this->lang_base_url . '/services/sadad-confirmation?success=0&message=' . $error_message;
                header('Location:' . $redURL);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function sadad_confirmation()
    {
        try {
            $data['show_terms'] = false;
            $data['content'] = $_REQUEST;
            return view('frontend.emails.terms_and_conditions')->with($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkIfPointInsideOrOutside()
    {
        try {
            $j = 0;
            $polygon = array();
            $latitude = $_REQUEST['latitude'];
            $longitude = $_REQUEST['longitude'];
            $branch_id = $_REQUEST['branch_id'];

            //$latitude = round($latitude,7,PHP_ROUND_HALF_UP);
            //$longitude = round($longitude,7,PHP_ROUND_HALF_UP);

            $point_to_find = "$latitude,$longitude";
            $area_coordinates = $this->page->getMultipleRows('branch_coverage_points', array('branch_id' => $branch_id), 'branch_id');
            foreach ($area_coordinates as $area_coordinate) {
                $lat_longs = explode(',', $area_coordinate->coordinates);
                $poly_latitude = (float)$lat_longs[0];
                $poly_longitude = (float)$lat_longs[1];
                $polygon[$j] = "$poly_latitude,$poly_longitude";
                $j++;
            }

            /*$email['subject'] = 'Map Coordinates';
            $email['fromEmail'] = 'admin@key.sa';
            $email['fromName'] = 'no-reply';
            $email['toEmail'] = 'bilal_ejaz@astutesol.com';
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';
            $email['attachment'] = '';

            $content['contact_no'] = '0321';
            $content['lang_base_url'] = custom::baseurl('/');
            $content['name'] = 'Admin';
            $content['msg'] = $point_to_find;
            $content['gender'] = 'male';
            custom::sendEmail2('general', $content, $email, 'eng');*/

            require_once(app_path() . '/libraries/PhpMapContainCoords.php');

            $pointLocation = new \PhpMapContainCoords();


            /*$headers = "From: " . "test@gmail.com" . "\r\n";
            $headers .= "Reply-To: " . "test@gmail.com" . "\r\n";
            $headers .= "CC: susan@example.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            mail('bilal_ejaz@astutesol.com', 'Map Coordinates', $point_to_find, $headers);*/

            $status = $pointLocation->pointInPolygon($point_to_find, $polygon);
            if ($status['status'] == 0) {
                $message = ($this->lang == 'eng' ? 'Sorry but our delivery services are not available for the selected location.' : 'عفوا، خدمة التوصيل غير متاحة للمكان المختار');
            } else {
                $message = "";
            }
            $response['status'] = $status['status'];
            $response['message'] = $message;
            $response['lat_long'] = $status['latitude'] . ', ' . $status['longitude'];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkSurveyPending()
    {
        try {
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $customer_email = $_REQUEST['email'];
            $customer_id_no = $_REQUEST['id_no'];
            $customer_id_no = custom::convertArabicNumbersToEnglish($customer_id_no);

            $site_settings = custom::site_settings();
            $survey_mode = ($site_settings->survey_mode_mobile == 'optional' ? 0 : 1);
            $survey_mode = (int)$survey_mode;

            $userExistWithEmail = $this->page->getSingle('individual_customer', array('email' => $customer_email));
            $userExistWithIdNo = $this->page->getSingle('individual_customer', array('id_no' => $customer_id_no));
            if ($userExistWithEmail || $userExistWithIdNo) {
                if ($userExistWithEmail) {
                    $individual_customer_id = $userExistWithEmail->id;
                } else {
                    $individual_customer_id = $userExistWithIdNo->id;
                }
                $getBy['customer_id'] = $individual_customer_id;
                $getBy['survey_filled_status'] = 'no';
                $survey_to_fill = $this->page->getSingle('survey_filled_status', $getBy);
                if (false) { // if at least one survey remains to be filled
                    $i = 0;
                    $surveyData = array();
                    $booking_id_for_survey = $survey_to_fill->booking_id;
                    $customer_id_for_survey = $survey_to_fill->customer_id;
                    $booking_detail = $this->services->getSingleBookingDetails($booking_id_for_survey, 'individual_user');
                    $booking_detail->created_at = date('l, H:i A', strtotime($booking_detail->created_at));
                    $survey_emojis = $this->page->getAll('survey_emoji', 'sort_col');
                    foreach ($survey_emojis as $emoji) {
                        $surveyData[$i]['emoji_id'] = $emoji->id;
                        $surveyData[$i]['emoji_eng_title'] = $emoji->eng_title;
                        $surveyData[$i]['emoji_arb_title'] = $emoji->arb_title;
                        $surveyData[$i]['class_name'] = $emoji->class_name;
                        $categories = $this->page->getMultipleRows('survey_category', array('emoji_id' => $emoji->id, 'publish' => 'yes'), 'sort_col');
                        $categoriesCount = $this->page->getRowsCount('survey_category', array('emoji_id' => $emoji->id, 'publish' => 'yes'), 'sort_col');
                        if ($categoriesCount > 0) {
                            $surveyData[$i]['show_submit_button'] = 0;
                            $j = 0;
                            foreach ($categories as $category) {
                                $surveyData[$i]['categories'][$j] = (array)$category;
                                $j++;
                            }
                        } else {
                            $surveyData[$i]['show_submit_button'] = 1;
                            $surveyData[$i]['categories'] = array();
                        }

                        $i++;
                    }
                    $arr['survey_mode'] = $survey_mode;
                    $arr['booking_id_for_survey'] = $booking_id_for_survey;
                    $arr['customer_id_for_survey'] = $customer_id_for_survey;
                    $arr['survey_data'] = $surveyData;
                    $arr['booking_detail'] = (array)$booking_detail;

                    // changing null to empty values
                    $arr['survey_data'] = custom::isNullToEmpty($arr['survey_data']);
                    $arr['booking_detail'] = custom::isNullToEmpty($arr['booking_detail']);

                    $response['status'] = 1;
                    $response['response'] = $arr;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    // no survey is there to fill
                    $response['status'] = 0;
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } else {
                // no survey is there to fill
                $response['status'] = 0;
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getSurveyData()
    {
        try {
            $booking_id = $_REQUEST['booking_id'];
            $surveyData = array();
            $i = 0;
            //$booking_id = base64_decode($booking_id);
            $booking_detail = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            $survey_emojis = $this->page->getAll('survey_emoji', 'sort_col');
            foreach ($survey_emojis as $emoji) {
                $surveyData[$i]['emoji_id'] = $emoji->id;
                $surveyData[$i]['emoji_eng_title'] = $emoji->eng_title;
                $surveyData[$i]['emoji_arb_title'] = $emoji->arb_title;
                $surveyData[$i]['class_name'] = $emoji->class_name;
                $categories = $this->page->getMultipleRows('survey_category', array('emoji_id' => $emoji->id, 'publish' => 'yes'), 'sort_col');
                $j = 0;
                foreach ($categories as $category) {
                    $surveyData[$i]['categories'][$j] = (array)$category;
                    $options = $this->page->getMultipleRows('survey_category_options', array('category_id' => $category->id, 'publish' => 'yes'), 'sort_col');

                    foreach ($options as $option) {
                        $surveyData[$i]['categories'][$j]['options'][] = (array)$option;
                    }

                    $j++;
                }
                $i++;
            }
            //echo '<pre>';print_r($surveyData);exit();
            $response['survey_data'] = $surveyData;
            $response['booking_detail'] = $booking_detail;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getOptionsForCategory()
    {
        try {
            $i = 0;
            $data = array();
            $category_id = $_REQUEST['category_id'];
            $category_detail = $this->page->getSingle('survey_category', array('id' => $category_id));
            $emoji_detail = $this->page->getSingle('survey_emoji', array('id' => $category_detail->emoji_id));
            $options_count = $this->page->getRowsCount('survey_category_options', array('category_id' => $category_id, 'publish' => 'yes'), 'sort_col');
            if ($options_count > 0) {
                $options = $this->page->getMultipleRows('survey_category_options', array('category_id' => $category_id, 'publish' => 'yes'), 'sort_col');
                foreach ($options as $option) {
                    $data[$i]['id'] = $option->id;
                    $data[$i]['eng_title'] = $option->eng_title;
                    $data[$i]['arb_title'] = $option->arb_title;
                    $i++;
                }
            }

            if ($category_detail->is_other_type == 'yes') {
                $data[$i]['id'] = 0;
                $data[$i]['eng_title'] = 'Other';
                $data[$i]['arb_title'] = 'آخر';
            }

            $response['emoji_id'] = $emoji_detail->id;
            $response['emoji_title'] = ($this->lang == 'eng' ? $emoji_detail->eng_title : $emoji_detail->arb_title);
            $response['category_id'] = $category_id;
            $response['category_title'] = ($this->lang == 'eng' ? $category_detail->eng_title : $category_detail->arb_title);
            $response['question_title'] = ($this->lang == 'eng' ? $category_detail->eng_question : $category_detail->arb_question);
            $response['options'] = $data;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveSurveyFeedback()
    {
        // customer_id, booking_id, emoji_desc, category_desc, question_desc, answer_desc, emoji_id, category_id, option_id

        try {
            $posted_data['customer_id'] = $_REQUEST['customer_id'];
            $posted_data['booking_id'] = $_REQUEST['booking_id'];
            $posted_data['emoji_id'] = $_REQUEST['emoji_id'];
            $posted_data['emoji_desc'] = $_REQUEST['emoji_desc'];

            $posted_data['category_desc'] = (isset($_REQUEST['category_desc']) ? $_REQUEST['category_desc'] : '');

            $posted_data['question_desc'] = (isset($_REQUEST['question_desc']) ? $_REQUEST['question_desc'] : '');
            $posted_data['answer_desc'] = (isset($_REQUEST['answer_desc']) ? $_REQUEST['answer_desc'] : '');
            $posted_data['category_id'] = (isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : '');
            $posted_data['option_id'] = (isset($_REQUEST['option_id']) && $_REQUEST['option_id'] >= '0' ? $_REQUEST['option_id'] : '');

            $posted_data['created_at'] = date('Y-m-d H:i:s');

            if ($posted_data['option_id'] == '0') {
                $posted_data['answer_desc'] = (isset($_REQUEST['feedback_textfield']) ? $_REQUEST['feedback_textfield'] : '');
            }

            unset($posted_data['feedback_textfield']);
            $emoji_categories_count = $this->page->getRowsCount('survey_category', array('emoji_id' => $posted_data['emoji_id'], 'publish' => 'yes'));
            $category_options_count = $this->page->getRowsCount('survey_category_options', array('category_id' => $posted_data['category_id'], 'publish' => 'yes'));

            if (($posted_data['category_id'] == '' && $emoji_categories_count > 0) || ($posted_data['option_id'] == '' && $category_options_count > 0)) {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'Please fill the survey correctly.' : 'يرجى ملء الاستبيان بشكل صحيح. ');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            // checking if user has already submitted this survey
            $feedbackAlreadySubmitted = $this->page->getRowsCount('survey_feedback', array('customer_id' => $posted_data['customer_id'], 'booking_id' => $posted_data['booking_id']));
            if ($feedbackAlreadySubmitted > 0) {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'تم تعبئة التقييم من قبل.');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            if ($posted_data['option_id'] != '') {
                if ($posted_data['option_id'] == '0') { // if submitted option is other type
                    $posted_data['option_value'] = 0;
                } else {
                    $option_details = $this->page->getSingle('survey_category_options', array('id' => $posted_data['option_id']));
                    $posted_data['option_value'] = $option_details->value;
                }
            }

            $saved_id = $this->page->saveData('survey_feedback', $posted_data); // commented this code for mobile apps temporarily.
            if ($saved_id > 0) { // commented this code for mobile apps temporarily.
                //if (true) {
                $updateBy['customer_id'] = $posted_data['customer_id'];
                $updateBy['booking_id'] = $posted_data['booking_id'];
                $update['survey_filled_status'] = 'yes';
                $update['updated_at'] = date('Y-m-d H:i:s');
                $this->page->updateData('survey_filled_status', $update, $updateBy); // commented this code for mobile apps temporarily.
                $response['status'] = 1;
                $response['message'] = ($this->lang == 'eng' ? 'Thank You For Taking Your Time To Fill The Survey.' : 'شكرا ً لأخذ وقتك لملء هذا الاستبيان.');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'Survey failed to submit. Please try again.' : 'لقد فشلت في تقديم الاستبيان. يرجى إعادة المحاولة مرة اخرى');
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function skipSurvey()
    {
        try {
            $update_by['customer_id'] = $_REQUEST['customer_id'];
            $update_by['survey_filled_status'] = 'no';
            $data_to_update['survey_filled_status'] = 'yes';
            $data_to_update['is_skipped'] = 'yes';
            $data_to_update['updated_at'] = date('Y-m-d H:i:s');
            $this->page->updateData('survey_filled_status', $data_to_update, $update_by);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function checkIfMaintenanceModeOn()
    {
        $skip_maintenance_mode = isset($_REQUEST['skip_maintenance_mode']);

        if ($skip_maintenance_mode) {
            return true;
        } else {
            $source = (isset($_REQUEST['type']) ? $_REQUEST['type'] : false);

            $site_settings = custom::site_settings();
            $maintenance_eng_desc = $site_settings->mobile_maintenance_eng_desc;
            $maintenance_arb_desc = $site_settings->mobile_maintenance_arb_desc;

            if ($source) {
                if (
                    (strtolower($source) == 'android' && $site_settings->mobile_maintenance_mode_for_android == 'on') ||
                    (strtolower($source) == 'ios' && $site_settings->mobile_maintenance_mode_for_ios == 'on') ||
                    (strtolower($source) == 'huawei' && $site_settings->mobile_maintenance_mode_for_huawei == 'on')
                ) {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? $maintenance_eng_desc : $maintenance_arb_desc);
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } elseif ($site_settings->mobile_maintenance_mode == 'on') {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? $maintenance_eng_desc : $maintenance_arb_desc);
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
    }

    private function validateEmailAndIdNo($email, $id_no)
    {
        $ind_customer_with_email = $this->page->getSingle('individual_customer', array('email' => $email));
        $ind_customer_with_id_no = $this->page->getSingle('individual_customer', array('id_no' => $id_no));
        if ($ind_customer_with_email && $ind_customer_with_id_no) {
            $id_with_email = $ind_customer_with_email->id;
            $id_with_id_no = $ind_customer_with_id_no->id;
            if ($id_with_email != $id_with_id_no) {
                $response['status'] = 0;
                $response['message'] = 'This email address is used with some other ID number.';
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
    }

    private function checkIfUserBlacklistedOrSimahBlock($id_no, $id_type)
    {
        $first_element_of_id_number = substr($id_no, 0, 1);
        $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('id_no' => $id_no));
        if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
            $response['status'] = 0;
            $response['message'] = ($this->lang == 'eng' ? 'Sorry we can\'t complete your request. Please contact ' . custom::getSiteName($this->lang) . ' for more details.' : 'عذرا، لا يمكننا إكمال طلبك.');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } elseif ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->simah_block == "yes") {
            $response['status'] = 0;
            $response['message'] = ($this->lang == 'eng' ? 'Dear Customer.<br>
You have an outstanding amount payable to ' . custom::getSiteName($this->lang) . '.<br>
Kindly remit the payment at our nearest branch to use our website.' : '<br>.عزيزي العميل
<br>.توجد لديكم مديونية لدى شركة ' . custom::getSiteName($this->lang) . ' لتأجير السيارات
.نرجو منكم التفضل بزيارة أي من فروعنا لتسوية المديونية والإستفادة من خدمات موقع المفتاح');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } elseif ($id_type == '243' && $first_element_of_id_number != '1') {
            $response['status'] = 0;
            $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } elseif ($id_type == '68' && $first_element_of_id_number != '2') {
            $response['status'] = 0;
            $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    public function saveTokenForDevice()
    {
        try {
            $device_unique_id = $_REQUEST['device_unique_id'];
            $fcm_token = $_REQUEST['fcm_token'];
            $device_type = $_REQUEST['device_type'];
            $created_at = date('Y-m-d H:i:s');
            $get_tokens_by['device_unique_id'] = $device_unique_id;
            $tokensForDevice = $this->page->getRowsCount('device_token', $get_tokens_by);
            if ($tokensForDevice > 0) {
                $update_by['device_unique_id'] = $device_unique_id;
                $data_to_update['fcm_token'] = $fcm_token;
                $data_to_update['device_type'] = $device_type;
                $data_to_update['token_status'] = 'Active';
                $data_to_update['token_status_checked_date'] = date('Y-m-d');
                $data_to_update['is_huawei'] = isset($_REQUEST['is_huawei']) && $_REQUEST['is_huawei'] == 1 ? 1 : 0;
                $data_to_update['created_at'] = $created_at;

                $response = custom::subscribe_device_to_fcm_topic($fcm_token);
                if ($response['status'] == true) {
                    $data_to_update['is_subscribed_to_topic'] = 1;
                }

                $this->page->updateData('device_token', $data_to_update, $update_by);
            } else {
                $data_to_insert['device_unique_id'] = $device_unique_id;
                $data_to_insert['fcm_token'] = $fcm_token;
                $data_to_insert['device_type'] = $device_type;
                $data_to_insert['token_status'] = 'Active';
                $data_to_insert['token_status_checked_date'] = date('Y-m-d');
                $data_to_insert['is_huawei'] = isset($_REQUEST['is_huawei']) && $_REQUEST['is_huawei'] == 1 ? 1 : 0;
                $data_to_insert['created_at'] = $created_at;

                $response = custom::subscribe_device_to_fcm_topic($fcm_token);
                if ($response['status'] == true) {
                    $data_to_insert['is_subscribed_to_topic'] = 1;
                }

                $this->page->saveData('device_token', $data_to_insert);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function verifyUserForCampaign()
    {
        try {
            $mobile_no = trim($_REQUEST['mobile_no']);
            $smsPhone = str_replace(array('+', ' '), '', $mobile_no);
            $smsPhone = $this->fixSaudiMobileNumber($smsPhone);
            $verification_code = custom::generateRand();

            if ($this->lang == 'eng') {
                $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . ": ";
            } else {
                $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . ": ";

            }
            $smsMsg .= $verification_code;
            //echo $smsPhone;exit();
            $smsSent = custom::sendSMS($smsPhone, $smsMsg, $this->lang);
            if ($smsSent != true) {
                if (is_bool($smsSent) && $smsSent == false) {
                    $resposeMsg = ($this->lang == 'eng' ? 'Verification code failed to be sent to this mobile no.' : 'لم يتم إرسال رمز التوثيق بنجاح');
                } else {
                    $resposeMsg = $smsSent;
                }
                $response['status'] = 0;
                $response['message'] = $resposeMsg;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($smsSent == true) {
                $response['status'] = 1;
                $response['message'] = "";
                $response['response'] = array('verification_code' => $verification_code, 'mobile_no' => $smsPhone);
                //$response['response'] = array('verification_code' => 123, 'mobile_no' => $smsPhone);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveCampaignData()
    {
        try {
            $name = $_REQUEST['name'];
            $mobile_no = trim($_REQUEST['mobile_no']);
            $device_type = trim($_REQUEST['device_type']);
            $smsPhone = str_replace(array('+', ' '), '', $mobile_no);
            $smsPhone = $this->fixSaudiMobileNumber($smsPhone);
            $userAlreadyExist = $this->page->getSingle('mreg_campaign', array('mobile_no' => $smsPhone));
            if ($userAlreadyExist) {
                $mreg_no = $userAlreadyExist->mreg_no;
                if ($this->lang == 'eng') {
                    $smsMsg = "You are already registered and below is your registration number.\n";
                } else {
                    $smsMsg = "\nلقد تم تسجيلكم من قبل رقم تسجيلكم لدى المفتاح هو";
                }
            } else {
                $mreg_no = $this->generate_mreg_no($smsPhone);
                if ($this->lang == 'eng') {
                    $smsMsg = "Thank you for registering this is your registration number.\n";
                } else {
                    $smsMsg = "شكرا لتسجيلكم، تم إنشاء رقم تسجيل بنجاح";
                }
            }

            $smsMsg .= $mreg_no;
            $smsSent = custom::sendSMS($smsPhone, $smsMsg, $this->lang);
            if ($smsSent != true) {
                if (is_bool($smsSent) && $smsSent == false) {
                    $resposeMsg = ($this->lang == 'eng' ? 'Verification code failed to be sent to this mobile no.' : 'لم يتم إرسال رمز التوثيق بنجاح');
                } else {
                    $resposeMsg = $smsSent;
                }
                $response['status'] = 0;
                $response['message'] = $resposeMsg;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } elseif ($smsSent == true) {
                if ($userAlreadyExist == false) {
                    $data['full_name'] = $name;
                    $data['mobile_no'] = $smsPhone;
                    $data['mreg_no'] = $mreg_no;
                    $data['device_type'] = $device_type;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $this->page->saveData('mreg_campaign', $data); // to be uncommented after Android testing done
                    $responseText = ($this->lang == 'eng' ? 'Thank you for registering this is your registration number.' : 'شكرا لتسجيلكم، تم إنشاء رقم تسجيل بنجاح');
                } else {
                    $responseText = ($this->lang == 'eng' ? 'You are already registered and below is your registration number.' : 'لقد تم تسجيلكم من قبل رقم تسجيلكم لدى ' . custom::getSiteName($this->lang) . ' هو');
                }
                $response['status'] = 1;
                $response['message'] = $responseText;
                $response['response'] = array('mreg_no' => $mreg_no, 'mobile_no' => $smsPhone);
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendRegistrationEmail($data, $image)
    {
        // get id type for email
        if (isset($data['id_type'])) {
            $where_cust['ref_id'] = $data['id_type'];
            $id_type = $this->page->getSingle('customer_id_types', $where_cust);
            if ($this->lang == "eng")
                $idType = $id_type->eng_title;
            else
                $idType = $id_type->arb_title;
        }


        if (isset($data['gender'])) {
            $gender = $data['gender'];
        } else {
            $gender = 'male';
        }

        // get job_title from db
        if (isset($data['job_title'])) {
            $where_job['oracle_reference_number'] = $data['job_title'];
            $job_title = $this->page->getSingle('job_title', $where_job);
            if ($this->lang == "eng")
                $jobTitle = $job_title->eng_title;
            else
                $jobTitle = $id_type->arb_title;
        }

        // get id_country
        if (isset($data['id_country'])) {
            $where_country['oracle_reference_number'] = $data['id_country'];
            $country = $this->page->getSingle('country', $where_country);
            if ($this->lang == "eng")
                $countryId = $country->eng_country;
            else
                $countryId = $country->arb_country;
        }
        //get license type
        if (isset($data['license_id_type'])) {
            $where_lcns_id['ref_id'] = $data['license_id_type'];
            $licence_id_type = $this->page->getSingle('driving_license_id_types', $where_lcns_id);
            if ($this->lang == "eng")
                $licenceIdType = $licence_id_type->eng_title;
            else
                $licenceIdType = $licence_id_type->arb_title;
        }
        // get license_country
        if (isset($data['license_country'])) {
            $where_country['oracle_reference_number'] = $data['license_country'];
            $license_country = $this->page->getSingle('country', $where_country);
            if ($this->lang == "eng")
                $licenceCountry = $license_country->eng_country;
            else
                $licenceCountry = $license_country->arb_country;
        }

        // get nationality for email
        $national = "";
        if (isset($data['nationality'])) {
            $where_nat['oracle_reference_number'] = $data['nationality'];
            $nationality = $this->page->getSingle('nationalities', $where_nat);
            if ($this->lang == "eng")
                $national = $nationality->eng_country_name;
            else
                $national = $nationality->arb_country_name;
        }

        // get license_id_type from email
        $licenceId = "";
        if (isset($data['license_id_type'])) {
            $where_lin['ref_id'] = $data['license_id_type'];
            $licence = $this->page->getSingle('driving_license_id_types', $where_lin);
            if ($this->lang == "eng")
                $licenceId = $licence->eng_title;
            else
                $licenceId = $licence->arb_title;
        }

        $attchArr = array();
        /*if (isset($data['id_image'])) {
            $attchArr['atach1'] = public_path('pdf/') . $data['id_image'];
        }*/

        /*if (isset($data['license_image'])) {
            $attchArr['atach2'] = public_path('pdf/') . $data['license_image'];
        }*/


        // email send to the user after successful register
        $site = custom::site_settings();
        $smtp = custom::smtp_settings();
        $email['subject'] = 'Registration';
        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $data['email'];

        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';
        $email['multiAtach'] = $attchArr;

        $data['data']['name'] = $data['first_name'] . ' ' . $data['last_name'];
        $data['data']['contact_no'] = $site->site_phone;
        $data['data']['gender'] = $gender;
        $data['data']['lang_base_url'] = $this->lang_base_url;
        $data['data']['message'] = Lang::get('labels.successfully_register_msg');

        $getTitle = custom::changeTitles($this->lang, "name");
        $info[$getTitle] = $data['first_name'] . ' ' . $data['last_name'];

        if (isset($data['email'])) {
            $getTitle = custom::changeTitles($this->lang, "email");
            $info[$getTitle] = $data['email'];
        }
        if (isset($data['mobile_no'])) {
            $getTitle = custom::changeTitles($this->lang, "phone");
            $info[$getTitle] = $data['mobile_no'];
        }
        if (isset($data['id_no'])) {
            $getTitle = custom::changeTitles($this->lang, "id_no");
            $info[$getTitle] = $data['id_no'];
        }
        if ($idType != "") {
            $getTitle = custom::changeTitles($this->lang, "id_type");
            $info[$getTitle] = $idType;
        }
        if (isset($data['id_version'])) {
            if ($id_type->ref_id == "68" || $id_type->ref_id == "243") {
                $getTitle = custom::changeTitles($this->lang, "id_version");
                $info[$getTitle] = $data['id_version'];
            }
        }

        if ($national != "") {
            $getTitle = custom::changeTitles($this->lang, "nationality");
            $info[$getTitle] = $national;
        }


        if (isset($data['dob'])) {
            $getTitle = custom::changeTitles($this->lang, "date_of_birth");
            $info[$getTitle] = date('d-m-Y', strtotime($data['dob']));
        }


        if (isset($data['id_expiry_date'])) {
            $getTitle = custom::changeTitles($this->lang, "id_expiry_date");
            $info[$getTitle] = date('d-m-Y', strtotime($data['id_expiry_date']));
        }

        if ($licenceId != "") {
            $getTitle = custom::changeTitles($this->lang, "licence_id");
            $info[$getTitle] = $licenceId;
        }
        if (isset($data['license_no'])) {
            $getTitle = custom::changeTitles($this->lang, "license_no");
            $info[$getTitle] = $data['license_no'];
        }
        if (isset($data['license_expiry_date'])) {
            $getTitle = custom::changeTitles($this->lang, "license_expiry_date");
            $info[$getTitle] = date('d-m-Y', strtotime($data['license_expiry_date']));
        }
        if (isset($data['job_title'])) {
            $getTitle = custom::changeTitles($this->lang, "job_title");
            $info[$getTitle] = $jobTitle;
        }
        if (isset($data['street_address'])) {
            $getTitle = custom::changeTitles($this->lang, "street_address");
            $info[$getTitle] = $data['street_address'];
        }
        if (isset($data['district_address'])) {
            $getTitle = custom::changeTitles($this->lang, "district_address");
            $info[$getTitle] = $data['district_address'];
        }
        if (isset($data['id_country'])) {
            $getTitle = custom::changeTitles($this->lang, "id_country");
            $info[$getTitle] = $countryId;
        }
        if (isset($data['license_id_type'])) {
            $getTitle = custom::changeTitles($this->lang, "license_id_type");
            $info[$getTitle] = $data['license_id_type'];
        }
        if (isset($data['license_id_type'])) {
            $getTitle = custom::changeTitles($this->lang, "license_id_type");
            $info[$getTitle] = $licenceIdType;
        }
        if (isset($data['license_country'])) {
            $getTitle = custom::changeTitles($this->lang, "license_country");
            $info[$getTitle] = $licenceCountry;
        }
        if (isset($data['sponsor'])) {
            $getTitle = custom::changeTitles($this->lang, "sponsor");
            $info[$getTitle] = $data['sponsor'];
        }
        if (isset($data['reg_no'])) {
            $getTitle = custom::changeTitles($this->lang, "registration_number");
            $info[$getTitle] = $data['reg_no'];
        }

        $data['data']['info'] = $info;

        $sent = custom::sendEmail('form', $data, $email, $this->lang);

        return $sent;
    }

    private function sendVerificationCode($userId, $randNumber = "")
    {
        if ($this->lang == 'eng') {
            $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . " is: ";
        } else {
            $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . " is: ";

        }

        if ($randNumber == "") {
            $randNumber = custom::generateRand();
        }

        $smsMsg .= $randNumber;
        $userInfo = $this->page->getSingle('individual_customer', array('id' => $userId));
        $smsPhone = str_replace(array('+', ' '), '', $userInfo->mobile_no);

        return $smsSent = custom::sendSMS($smsPhone, $smsMsg, $this->lang);
    }

    private function sendVerificationCodeViaWhatsapp($userId, $randNumber = "")
    {
        if ($this->lang == 'eng') {
            $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . " is: ";
        } else {
            $smsMsg = "Verification code for " . custom::getSiteName($this->lang) . " is: ";

        }

        if ($randNumber == "") {
            $randNumber = custom::generateRand();
        }

        $smsMsg .= $randNumber;
        $userInfo = $this->page->getSingle('individual_customer', array('id' => $userId));
        $smsPhone = str_replace(array('+', ' '), '', $userInfo->mobile_no);

        $email_address = $userInfo->email;
        $name = $userInfo->first_name . ' ' . $userInfo->last_name;
        $gender = $userInfo->gender;

        $site_settings = custom::site_settings();
        if ($site_settings->notify_customers_by == 'sms') {
            custom::sendSMS($smsPhone, $smsMsg, $this->lang);
        } else {
            custom::send_booking_cancellation_otp_via_whatsapp($smsPhone, $randNumber, $this->lang);
        }

        // sending email for cancellation OTP
        $site = custom::site_settings();
        $smtp = custom::smtp_settings();

        if ($this->lang == 'eng') {
            $subject = 'Booking Cancellation OTP';
            $msg = $smsMsg;
        } else {
            $subject = 'Booking Cancellation OTP';
            $msg = $smsMsg;
        }

        $email['subject'] = $subject;

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = $email_address;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = $this->lang_base_url;
        $content['name'] = $name;
        $content['msg'] = $msg;
        $content['gender'] = $gender;
        custom::sendEmail('general', $content, $email, $this->lang);

        return true;
    }

    // send email to the user to confirm booking
    private function sendEmailToUser($booking_id)
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));
        if ($booking_detail->type == "corporate_customer") {
            $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        } else {
            $emailObj = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
        }

        //echo "<pre>"; print_r($emailObj); exit();
        $emailData['booking_content'] = (array)$emailObj;
        if (isset($emailObj->icg_email) && $emailObj->icg_email != null) {
            //guest info with icg_
            $first_name = $emailObj->icg_first_name;
            $last_name = $emailObj->icg_last_name;
            $gender = $emailObj->icg_gender;
            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->icg_id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->icg_mobile_no;
            $emailData['booking_content']['email'] = $emailObj->icg_email;

            $emailAddress = $emailObj->icg_email;

        } else {
            //customer info is of logged in user and id joing is from booking_individual_user
            $first_name = $emailObj->first_name;
            $last_name = $emailObj->last_name;
            $gender = $emailObj->gender;

            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
            $emailData['booking_content']['email'] = $emailObj->email;

            $emailAddress = $emailObj->email;
        }

        if ($lang == "eng") {
            $subject = "Booking confirmation";
        } else {
            $subject = "تأكيد الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        //echo '<pre>';print_r($email);exit();

        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendThankYouSMS($reservation_code, $userPhoneNo)
    {
        $lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Thank you for booking at " . custom::getSiteName($this->lang) . " Reservation# ";
        } else {
            $smsMsg = "Thank you for booking at " . custom::getSiteName($this->lang) . " Reservation# ";
        }
        $url = $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id);
        //reservation thankyou sms
        $smsMsg .= $reservation_code . "\n";
        $smsMsg .= "Click below link to see this reservation details.\n";
        $smsMsg .= $url;
        $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);

        $site_settings = custom::site_settings();
        if ($site_settings->notify_customers_by == 'sms') {
            custom::sendSMS($smsPhone, $smsMsg, $lang);
        } else {
            custom::call_whatsapp_message_service_after_booking($smsPhone, $reservation_code, $url, $lang);
        }
    }

    private function bookingPdf($data)
    {
        $lang = $this->lang;
        $data['isPdf'] = "pdf";
        $filename = rand() . '-Booking.pdf';
        if ($lang == "eng") {
            $template = 'frontend.emails.booking_email_pdf_eng';
        } else {
            $template = 'frontend.emails.booking_email_pdf_ar';
        }
        $this->pdf->loadView($template, $data)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 0)
            ->save('public/pdf/' . $filename);
        return $filename;
    }

    private function sendCancellationEmail($booking_id, $user_type)
    {
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($user_type == "corporate_customer") {
            $this->sendCancellationEmailToCorporateUser($booking_id);
            $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
            $first_name = $emailObj->first_name;
            $last_name = $emailObj->last_name;
            $gender = $emailObj->gender;
            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;
            $emailData['booking_content']['id_no'] = $emailObj->id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
            $emailData['booking_content']['email'] = $emailObj->email;
            $emailAddress = $emailObj->email;
        } else {
            $emailObj = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
            if ($emailObj->icg_email != null) {
                //guest info with icg_
                $first_name = $emailObj->icg_first_name;
                $last_name = $emailObj->icg_last_name;
                $gender = $emailObj->icg_gender;
                $emailData['booking_content']['first_name'] = $first_name;
                $emailData['booking_content']['last_name'] = $last_name;
                $emailData['booking_content']['gender'] = $gender;

                $emailData['booking_content']['id_no'] = $emailObj->icg_id_no;
                $emailData['booking_content']['mobile_no'] = $emailObj->icg_mobile_no;
                $emailData['booking_content']['email'] = $emailObj->icg_email;

                $emailAddress = $emailObj->icg_email;
            } else {
                //customer info is of logged in user and id joing is from booking_individual_user
                $first_name = $emailObj->first_name;
                $last_name = $emailObj->last_name;
                $gender = $emailObj->gender;
                $emailData['booking_content']['first_name'] = $first_name;
                $emailData['booking_content']['last_name'] = $last_name;
                $emailData['booking_content']['gender'] = $gender;

                $emailData['booking_content']['id_no'] = $emailObj->id_no;
                $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
                $emailData['booking_content']['email'] = $emailObj->email;

                $emailAddress = $emailObj->email;
            }
        }


        $emailData['booking_content']['transaction_id'] = '';

        if ($this->lang == "eng") {
            $subject = "Booking Cancellation";
        } else {
            $subject = "تأكيد إلغاء الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingCancellationPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        custom::sendEmail('cancel', $emailData, $email, $this->lang);
    }

    private function sendCancellationEmailToAdmin($booking_id, $user_type)
    {
        $paymentMethod = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking_id));
        if ($paymentMethod->payment_method == 'Credit Card') {
            $cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking_id));
            $transaction_id = $cc_payment->transaction_id;
        } elseif ($paymentMethod->payment_method == 'Sadad') {
            $cc_payment = $this->page->getSingle('booking_sadad_payment', array('s_booking_id' => $booking_id));
            $transaction_id = $cc_payment->s_transaction_id;
        }
        $smtp_settings = custom::smtp_settings();
        $site_settings = custom::site_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($user_type == "corporate_customer") {
            $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
            $first_name = $emailObj->first_name;
            $last_name = $emailObj->last_name;
            $gender = $emailObj->gender;
            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;
            $emailData['booking_content']['id_no'] = $emailObj->id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
            $emailData['booking_content']['email'] = $emailObj->email;
        } else {
            $emailObj = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
            if ($emailObj->icg_email != null) {
                //guest info with icg_
                $first_name = $emailObj->icg_first_name;
                $last_name = $emailObj->icg_last_name;
                $gender = $emailObj->icg_gender;
                $emailData['booking_content']['first_name'] = $first_name;
                $emailData['booking_content']['last_name'] = $last_name;
                $emailData['booking_content']['gender'] = $gender;
                $emailData['booking_content']['id_no'] = $emailObj->icg_id_no;
                $emailData['booking_content']['mobile_no'] = $emailObj->icg_mobile_no;
                $emailData['booking_content']['email'] = $emailObj->icg_email;
            } else {
                //customer info is of logged in user and id joing is from booking_individual_user
                $first_name = $emailObj->first_name;
                $last_name = $emailObj->last_name;
                $gender = $emailObj->gender;
                $emailData['booking_content']['first_name'] = $first_name;
                $emailData['booking_content']['last_name'] = $last_name;
                $emailData['booking_content']['gender'] = $gender;

                $emailData['booking_content']['id_no'] = $emailObj->id_no;
                $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
                $emailData['booking_content']['email'] = $emailObj->email;
            }
        }

        if ($this->lang == "eng") {
            $subject = "Booking Cancellation";
        } else {
            $subject = "تأكيد ملغي";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $smtp_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $site_settings->admin_email;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingCancellationPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        $emailData['booking_content']['transaction_id'] = '';
        if (isset($cc_payment) && isset($transaction_id)) {
            $emailData['booking_content']['transaction_id'] = $transaction_id;
        }
        custom::sendEmail('cancel', $emailData, $email, $this->lang);
    }

    private function bookingCancellationPdf($data)
    {
        $data['isPdf'] = "pdf";
        $filename = rand() . '-Booking.pdf';
        if ($this->lang == 'eng') {
            $template = 'frontend.emails.booking_cancellation_email_pdf_eng';
        } else {
            $template = 'frontend.emails.booking_cancellation_email_pdf_arb';
        }
        $this->pdf->loadView($template, $data)
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 0)
            ->save('public/pdf/' . $filename);
        return $filename;
    }

    private function sendRequest($gateway_url, $request_string)
    {

        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_URL, $gateway_url);
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_HEADER, false);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        @curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = @curl_exec($ch);
        if (!$result)
            die(curl_error($ch));

        @curl_close($ch);

        return $result;
    }

    private function verify($payment_reference)
    {
        $api_settings = custom::api_settings();
        $data['merchant_email'] = $api_settings->paytabs_merchant_email;
        $data['secret_key'] = $api_settings->paytabs_secret_key;
        $data['payment_reference'] = $payment_reference;

        $request_string = http_build_query($data);

        $response_data = $this->sendRequest('https://www.paytabs.com/apiv2/verify_payment', $request_string);
        $response_data = json_decode($response_data, true);
        $response_code = $response_data['response_code'];
        if ($response_code != 100 && $response_code != 5001 && $response_code != 5002) {
            $response_message = $response_data['result'];
            $responseArr['status'] = false;
            $responseArr['error_message'] = $response_message;
            return $responseArr;
        } else {
            $responseArr = $response_data;
            $responseArr['status'] = true;
            $responseArr['error_message'] = '';
            return $responseArr;
        }

    }

    private function generate_mreg_no($number, $num_of_strings = 4, $string_length = 6)
    {
        srand($number); // initialize rng with that number
        $chrs = '123456789087654321';
        $chrs_len = strlen($chrs);

        $ret = [];
        for ($i = 0; $i < $num_of_strings; $i++) {
            $ret[$i] = '';
            for ($j = 0; $j < $string_length; $j++) {
                $ret[$i] .= $chrs[rand(0, $chrs_len - 1)];
            }
        }

        return 'MREG' . array_random($ret);
    }

    public function test_func(Request $request)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        $input = $purifier->purify($_REQUEST['asd']);
        echo $input;
        die;
        /*if (isset($_REQUEST['mobile_no']) && $_REQUEST['mobile_no'] != '') {
            $mobile_no = $_REQUEST['mobile_no'];
        } else {
            $mobile_no = '+923368809300';
        }
        $smsMsg = 'testing message';
        $return_msg = true;
        $smsSent = custom::sendSMS($mobile_no, $smsMsg, $this->lang, $return_msg);*/
    }

    private function fixSaudiMobileNumber($full_mobile_no)
    {
        $phone = custom::getPhoneNumber($full_mobile_no);
        return $phone['country_code'] . $phone['number'];
        /*$country_code = substr($full_mobile_no, 0, 3);
        if ($country_code == '966') {
            $mobile_no = substr($full_mobile_no, 3);
            $mobile_no = ltrim($mobile_no, '0');
            $mobile_no = (int)$mobile_no;
            return $country_code . $mobile_no;
        } else {
            return $full_mobile_no;
        }*/
    }

    // send email to the branch agent to confirm booking
    private function send_email_to_branch_agent($booking_id, $toEmail, $toName = '')
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        $booking_details = $this->page->getSingle('booking', array('id' => $booking_id));
        if ($booking_details->type == 'corporate_customer') {
            $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id);
            $emailData['booking_content'] = (array)$emailObj;
        } else {
            $emailObj = $this->page->getSingleBookingDetails($booking_id);
            $emailData['booking_content'] = (array)$emailObj;
        }
        if (isset($emailObj->icg_email) && $emailObj->icg_email != null) {
            //guest info with icg_
            $first_name = $emailObj->icg_first_name;
            $last_name = $emailObj->icg_last_name;
            $gender = $emailObj->icg_gender;
            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->icg_id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->icg_mobile_no;
            $emailData['booking_content']['email'] = $emailObj->icg_email;

            $emailAddress = $emailObj->icg_email;
        } else {
            //customer info is of logged in user and id joing is from booking_individual_user
            $first_name = $emailObj->first_name;
            $last_name = $emailObj->last_name;
            $gender = $emailObj->gender;

            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
            $emailData['booking_content']['email'] = $emailObj->email;

            $emailAddress = $emailObj->email;
        }

        if ($lang == "eng") {
            $subject = "Booking confirmation";
        } else {
            $subject = "تأكيد الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $toEmail;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        if ($toEmail != '') {
            custom::sendEmail('booking', $emailData, $email, $lang);
        }
    }

    // sending sms to branch agent
    private function send_sms_to_branch_agent($reservation_code, $userPhoneNo, $toName = '')
    {
        $lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Dear " . $toName . "\n" . "a booking is made at " . custom::getSiteName($this->lang) . ", Reservation# ";
        } else {
            $smsMsg = "Dear " . $toName . "\n" . "a booking is made at " . custom::getSiteName($this->lang) . ", Reservation# ";
        }
        //reservation thankyou sms
        $smsMsg .= $reservation_code . "\n";
        $smsMsg .= "Click link to see reservation details.\n";
        $smsMsg .= $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id);
        $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);

        custom::sendSMS($smsPhone, $smsMsg, $lang);
        //=================
    }

    public function updateCorporateProfile()
    {
        try {
            $user_id = custom::jwt_decode($_REQUEST['user_id']);

            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];

            if ($password != '') {
                $isPasswordStrong = custom::isPasswordStrong($password, $this->lang);
                if (!$isPasswordStrong['status']) {
                    $response['status'] = 0;
                    $response['message'] = $isPasswordStrong['message'];
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }

            $company_name_en = $_REQUEST['company_name_en'];
            $company_name_ar = $_REQUEST['company_name_ar'];

            $primary_name = $_REQUEST['primary_name'];
            $primary_position = $_REQUEST['primary_position'];
            $primary_email = $_REQUEST['primary_email'];
            $primary_phone = $_REQUEST['primary_phone'];

            $secondary_name = $_REQUEST['secondary_name'];
            $secondary_position = $_REQUEST['secondary_position'];
            $secondary_email = $_REQUEST['secondary_email'];
            $secondary_phone = $_REQUEST['secondary_phone'];

            $corporate_details = $this->page->getSingle('corporate_customer', array('id' => $user_id));
            $logged_in_user_id = $corporate_details->uid;

            $logged_in_user_details = $this->page->getSingle('users', array('id' => $logged_in_user_id));
            $old_username = $logged_in_user_details->email;
            $posted_username = $username;
            if ($old_username != $posted_username) {
                $users = $this->page->getRowsCount('users', array('email' => $posted_username));
                if ($users > 0) {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'A user already exist with the username that you inserted.' : 'A user already exist with the username that you inserted.');
                    $response['response'] = '';
                    echo json_encode($response);
                    exit();
                }
            }

            $userData['name'] = $company_name_en;
            $userData['email'] = $username;
            if ($password != '') {
                $userData['password'] = md5($password);
            }
            $userData['updated_at'] = date('Y-m-d H:i:s');
            $user_update_by['id'] = $logged_in_user_id;
            $this->page->updateData('users', $userData, $user_update_by);

            $data['company_name_en'] = $company_name_en;
            $data['company_name_ar'] = $company_name_ar;

            $data['primary_name'] = $primary_name;
            $data['primary_position'] = $primary_position;
            $data['primary_email'] = $primary_email;
            $data['primary_phone'] = $primary_phone;

            $data['secondary_name'] = $secondary_name;
            $data['secondary_position'] = $secondary_position;
            $data['secondary_email'] = $secondary_email;
            $data['secondary_phone'] = $secondary_phone;

            $data['updated_at'] = date('Y-m-d H:i:s');

            $update_by['id'] = $user_id;
            $this->page->updateData('corporate_customer', $data, $update_by);

            $user_data = (array)$this->services->getCorporateUserInfo($user_id);
            $user_data['mobile_no'] = custom::getPhoneNumber($user_data['primary_phone']);
            $response['status'] = 1;
            $response['message'] = Lang::get('labels.account_info_updated_msg');
            $response['response'] = $user_data;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getDriverInfo()
    {
        try {
            $search_q = $_REQUEST['search_q'];
            $corporate_driver = $this->page->getDriverInfo($search_q);
            if ($corporate_driver) {
                $corporate_driver->mobile_no_seperated = custom::getPhoneNumber($corporate_driver->mobile_no);
                $response['status'] = 1;
                $response['response'] = $corporate_driver;
                $response['message'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $response['status'] = 0;
                $response['response'] = "";
                $response['message'] = "No Driver Found Against This Search.";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function bookForCorporate()
    {
        try {
            $is_email_valid = custom::validate_email($_REQUEST['email']);
            if (!$is_email_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            $_REQUEST['mobile_no'] = trim($_REQUEST['mobile_no']);
            $is_mobile_no_valid = custom::validate_mobile_no($_REQUEST['mobile_no']);
            if (!$is_mobile_no_valid) {
                $response['status'] = 0;
                $response['message'] = trans('labels.enter_valid_mobile_no_msg');
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }

            // change
            $api_settings = custom::api_settings();
            $site_settings = custom::site_settings();
            ini_set('max_execution_time', 600);
            $created_at = date('Y-m-d H:i:s');

            $corporate_customer_id = custom::jwt_decode($_REQUEST['user_id']);
            $corporate_customer_details = $this->page->getSingle('corporate_customer', array('id' => $corporate_customer_id));
            $original_rent = $_REQUEST['actual_price'];
            $rent_per_day = $_REQUEST['discounted_price'];
            $days = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days = 30;
            }

            $cdw = $_REQUEST['cdw_charges'];
            $cdw_is_one_time_applicable_on_booking = $_REQUEST['cdw_is_one_time_applicable_on_booking'];

            $cdw_plus = $_REQUEST['cdw_plus_charges'];
            $cdw_plus_is_one_time_applicable_on_booking = $_REQUEST['cdw_plus_is_one_time_applicable_on_booking'];

            $gps = $_REQUEST['gps_charges'];
            $gps_is_one_time_applicable_on_booking = $_REQUEST['gps_is_one_time_applicable_on_booking'];

            $extra_driver = $_REQUEST['extra_driver_charges'];
            $extra_driver_is_one_time_applicable_on_booking = $_REQUEST['extra_driver_is_one_time_applicable_on_booking'];

            $baby_seat = $_REQUEST['baby_seat_charges'];
            $baby_seat_is_one_time_applicable_on_booking = $_REQUEST['baby_seat_is_one_time_applicable_on_booking'];

            $total_rent_for_all_days = $rent_per_day * $days;
            $first_name = $_REQUEST['first_name'];
            $last_name = $_REQUEST['last_name'];
            $id_no = $_REQUEST['id_no'];
            $id_type = $_REQUEST['id_type'];
            $mobile_no_separated = custom::getPhoneNumber($_REQUEST['mobile_no']);
            $mobile_no = $mobile_no_separated['country_code'] . $mobile_no_separated['number'];
            $email = $_REQUEST['email'];
            $gender = $_REQUEST['gender'];
            $license_no = $_REQUEST['license_no'];
            $sponsor = (isset($_REQUEST['sponsor']) ? $_REQUEST['sponsor'] : '');
            $promo_code = $_REQUEST['promo_code'];
            $payment_method = $_REQUEST['payment_method'];
            $car_id = $_REQUEST['car_id'];
            $total_rent_after_discount_on_promo = $rent_per_day * $days;
            $discount_amount_per_day = $_REQUEST['discount_amount_per_day'];
            $promotion_id = $_REQUEST['promotion_id'];
            $renting_type_id = $_REQUEST['renting_type_id'];
            $dropoff_charges_amount = $_REQUEST['dropoff_charges_amount'];
            $loyalty_type_used = $_REQUEST['loyalty_type_used'];

            $from_branch_id = $_REQUEST['from_branch_id'];
            $to_branch_id = $_REQUEST['to_branch_id'];
            $pickup_date = $_REQUEST['pickup_date'];
            $pickup_time = $_REQUEST['pickup_time'];
            $dropoff_date = $_REQUEST['dropoff_date'];
            $dropoff_time = $_REQUEST['dropoff_time'];
            $booking_source = $_REQUEST['source'];
            $app_version = $_REQUEST['app_version'];

            $parking_fee = custom::parking_fee_for_branch($from_branch_id);
            $tamm_charges_for_branch = custom::tamm_charges_for_branch($from_branch_id);


            $is_delivery_mode = (isset($_REQUEST['is_delivery_mode']) && $_REQUEST['is_delivery_mode'] != '' ? $_REQUEST['is_delivery_mode'] : 0);
            $pickup_delivery_coordinate = (isset($_REQUEST['pickup_delivery_coordinate']) && $_REQUEST['pickup_delivery_coordinate'] != '' ? $_REQUEST['pickup_delivery_coordinate'] : '');
            $dropoff_delivery_coordinate = (isset($_REQUEST['dropoff_delivery_coordinate']) && $_REQUEST['dropoff_delivery_coordinate'] != '' ? $_REQUEST['dropoff_delivery_coordinate'] : '');
            $delivery_charges = (float)(isset($_REQUEST['delivery_charges']) && $_REQUEST['delivery_charges'] != '' ? $_REQUEST['delivery_charges'] : 0.00);

            if ($cdw_is_one_time_applicable_on_booking == 1) {
                $cdw_multiply_factor = 1;
            } else {
                $cdw_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($cdw_plus_is_one_time_applicable_on_booking == 1) {
                $cdw_plus_multiply_factor = 1;
            } else {
                $cdw_plus_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($gps_is_one_time_applicable_on_booking == 1) {
                $gps_multiply_factor = 1;
            } else {
                $gps_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($extra_driver_is_one_time_applicable_on_booking == 1) {
                $extra_driver_multiply_factor = 1;
            } else {
                $extra_driver_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            if ($baby_seat_is_one_time_applicable_on_booking == 1) {
                $baby_seat_multiply_factor = 1;
            } else {
                $baby_seat_multiply_factor = ($is_delivery_mode == 2 ? 1 : $days);
            }

            $book_for_hours = (isset($_REQUEST['book_for_hours']) && $_REQUEST['book_for_hours'] != '' ? $_REQUEST['book_for_hours'] : 0);

            if ($is_delivery_mode == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $subscribe_for_months = (isset($_REQUEST['subscribe_for_months']) && $_REQUEST['subscribe_for_months'] != '' ? $_REQUEST['subscribe_for_months'] : 0);
            if ($is_delivery_mode == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($_REQUEST['pickup_date'] . ' ' . $pickup_time);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $_REQUEST['dropoff_date'] = $dropoff_date = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $_REQUEST['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            if ($is_delivery_mode == 1) {
                $is_delivery_mode_to_save = 'yes';
            } else {
                $is_delivery_mode_to_save = ($is_delivery_mode == 2 ? 'hourly' : 'no');
            }

            if ($is_delivery_mode == 1 && ($delivery_charges == 0 || $delivery_charges == '')) {
                $delivery_charges = 0.00;
            }

            // save extra information of driver if corporate customer
            $corporate_driver['first_name'] = $first_name;
            $corporate_driver['last_name'] = $last_name;
            $corporate_driver['email'] = $email;
            $corporate_driver['mobile_no'] = $mobile_no;
            $corporate_driver['id_type'] = $id_type;
            $corporate_driver['id_no'] = $id_no;
            $corporate_driver['gender'] = $gender;
            $corporate_driver['sponsor'] = $sponsor;
            $corporate_driver['license_no'] = $license_no;
            $session_data = $corporate_driver;

            $session_data['id_version'] = $id_version = ($id_type == '68' || $id_type == '243' ? '1' : '');

            $session_data['promo_code'] = $promo_code;
            $session_data['payment_method'] = $payment_method;
            $session_data['car_id'] = $car_id;
            $session_data['rent_per_day'] = $rent_per_day;
            $session_data['total_rent_for_all_days'] = $total_rent_for_all_days; // Only rent multiplied by days
            $session_data['total_rent_after_discount_on_promo'] = $total_rent_after_discount_on_promo;
            $session_data['discount_amount_per_day'] = $discount_amount_per_day;
            $session_data['promotion_id'] = $promotion_id;
            $session_data['renting_type_id'] = $renting_type_id;

            // Getting branch data to get its prefix
            $get_branch_data_by['id'] = $from_branch_id;
            $branch_info = $this->page->getSingle('branch', $get_branch_data_by);
            // Saving booking information
            $booking_info['car_model_id'] = $car_id;
            $booking_info['from_location'] = $from_branch_id;
            $booking_info['to_location'] = $to_branch_id;
            $booking_info['from_date'] = date('Y-m-d', strtotime($pickup_date)) . ' ' . date('H:i:s', strtotime($pickup_time));
            $booking_info['to_date'] = date('Y-m-d', strtotime($dropoff_date)) . ' ' . date('H:i:s', strtotime($dropoff_time));
            $booking_info['booking_status'] = 'Not Picked';
            $booking_info['sync'] = 'N';
            $booking_info['renting_type_id'] = $renting_type_id;
            $user_type = 'corporate_customer';
            $booking_info['type'] = $user_type;
            $booking_info['pickup_delivery_lat_long'] = $pickup_delivery_coordinate;
            $booking_info['dropoff_delivery_lat_long'] = $dropoff_delivery_coordinate;
            $booking_info['is_delivery_mode'] = $is_delivery_mode_to_save;
            $booking_info['booking_source'] = $booking_source;
            $booking_info['app_version'] = $app_version;
            $booking_info['downloaded_from'] = isset($_REQUEST['downloaded_from']) ? $_REQUEST['downloaded_from'] : '';
            $booking_info['lang'] = $this->lang;
            $booking_info['created_at'] = $created_at;
            $booking_info['subscription_with_delivery_flow'] = (isset($_REQUEST['is_subscription_with_delivery_flow']) && $_REQUEST['is_subscription_with_delivery_flow'] == 1 ? 'on' : 'off');
            $savedBookingId = $this->page->saveData('booking', $booking_info);
            // Generating reservation code and updating in database
            $booking_info_extra['reservation_code'] = custom::generateReservationCode($branch_info->prefix, $savedBookingId, 'W');
            $updateBookingInfoBy['id'] = $savedBookingId;
            $bookingUpdated = $this->page->updateData('booking', $booking_info_extra, $updateBookingInfoBy);

            // Saving booking payment method
            if ($payment_method == 'cc') {
                $method_is = 'Credit Card';
            } elseif ($payment_method == 'corporate_credit') {
                $method_is = 'Corporate Credit';
            } else {
                $method_is = 'Cash';
            }
            $payment_method_data['booking_id'] = $savedBookingId;
            $payment_method_data['payment_method'] = $method_is;
            $this->page->saveData('booking_individual_payment_method', $payment_method_data);
            $this->page->updateData('booking', array('type' => 'corporate_customer'), array('id' => $savedBookingId));

            $checkCorporateDriverByIdNo = $this->page->getSingle('corporate_driver', array('id_no' => $corporate_driver['id_no']));
            $checkCorporateDriverByEmail = $this->page->getSingle('corporate_driver', array('email' => $corporate_driver['email']));
            if ($checkCorporateDriverByIdNo || $checkCorporateDriverByEmail) {
                if ($checkCorporateDriverByIdNo) {
                    $update_corporate_driver_by['id_no'] = $corporate_driver['id_no'];
                    $corporate_driver['updated_at'] = date('Y-m-d H:i:s');
                    $this->page->updateData('corporate_driver', $corporate_driver, $update_corporate_driver_by);
                    $corporate_driver_id = $checkCorporateDriverByIdNo->id;
                } elseif ($checkCorporateDriverByEmail) {
                    $update_corporate_driver_by['email'] = $corporate_driver['email'];
                    $corporate_driver['updated_at'] = date('Y-m-d H:i:s');
                    $this->page->updateData('corporate_driver', $corporate_driver, $update_corporate_driver_by);
                    $corporate_driver_id = $checkCorporateDriverByEmail->id;
                }
            } else {
                $corporate_driver['created_at'] = date('Y-m-d H:i:s');
                $corporate_driver_id = $this->page->saveData('corporate_driver', $corporate_driver);
            }
            $booking_corporate_user_data['driver_id'] = $corporate_driver_id;
            $booking_corporate_user_data['booking_id'] = $savedBookingId;
            $booking_corporate_user_data['uid'] = $corporate_customer_details->uid;
            $this->page->saveData('booking_corporate_customer', $booking_corporate_user_data);

            // Saving booking payment details
            $booking_payment_data['booking_id'] = $savedBookingId;
            $booking_payment_data['rent_price'] = $rent_per_day;
            $booking_payment_data['original_rent'] = $original_rent; // saving old rent that is without any discounts
            $booking_payment_data['cdw_price'] = $cdw;
            $booking_payment_data['cdw_plus_price'] = $cdw_plus;
            $booking_payment_data['gps_price'] = $gps;
            $booking_payment_data['extra_driver_price'] = $extra_driver;
            $booking_payment_data['baby_seat_price'] = $baby_seat;
            $booking_payment_data['promotion_offer_id'] = $promotion_id;
            $booking_payment_data['promotion_offer_code_used'] = isset($_REQUEST['promo_code']) && $_REQUEST['promo_code'] != "" ? $_REQUEST['promo_code'] : "";
            $booking_payment_data['discount_price'] = $discount_amount_per_day;
            $booking_payment_data['total_rent_after_discount'] = $total_rent_after_discount_on_promo;
            $booking_payment_data['dropoff_charges'] = $dropoff_charges_amount;
            $booking_payment_data['delivery_charges'] = $delivery_charges;
            $booking_payment_data['parking_fee'] = $parking_fee;
            $booking_payment_data['tamm_charges_for_branch'] = $tamm_charges_for_branch;
            $booking_payment_data['no_of_days'] = $days;
            $booking_payment_data['loyalty_type_used'] = $loyalty_type_used;

            $booking_payment_data['qitaf_request'] = (isset($_REQUEST['qitaf_request']) ? str_replace('.', ',', $_REQUEST['qitaf_request']) : '');
            $booking_payment_data['niqaty_request'] = (isset($_REQUEST['niqaty_request']) ? base64_decode($_REQUEST['niqaty_request']) : '');
            $booking_payment_data['anb_request'] = (isset($_REQUEST['anb_request']) ? $_REQUEST['anb_request'] : '');
            $booking_payment_data['mokafaa_request'] = (isset($_REQUEST['mokafaa_request']) ? $_REQUEST['mokafaa_request'] : '');

            $booking_payment_data['qitaf_amount'] = $qitaf_amount = (isset($_REQUEST['qitaf_amount']) && $_REQUEST['qitaf_amount'] > 0 ? $_REQUEST['qitaf_amount'] : 0);

            $booking_payment_data['niqaty_amount'] = $niqaty_amount = (isset($_REQUEST['niqaty_amount']) && $_REQUEST['niqaty_amount'] > 0 ? $_REQUEST['niqaty_amount'] : 0);
            $booking_payment_data['anb_amount'] = $anb_amount = (isset($_REQUEST['anb_amount']) && $_REQUEST['anb_amount'] > 0 ? $_REQUEST['anb_amount'] : 0);
            $booking_payment_data['mokafaa_amount'] = $mokafaa_amount = (isset($_REQUEST['mokafaa_amount']) && $_REQUEST['mokafaa_amount'] > 0 ? $_REQUEST['mokafaa_amount'] : 0);

            $loyalty_program_id = (isset($_REQUEST['loyalty_program_id']) && $_REQUEST['loyalty_program_id'] > 0 ? $_REQUEST['loyalty_program_id'] : '');
            $booking_payment_data['loyalty_program_for_oracle'] = custom::get_loyalty_program_used_for_booking($loyalty_program_id);
            $booking_payment_data['is_promo_discount_on_total'] = $is_promo_discount_on_total = custom::is_promo_discount_on_total($booking_payment_data['promotion_offer_id']);

            $pre_total_discount = 0;
            $post_total_discount = 0;

            if ($is_promo_discount_on_total == 1) {
                $post_total_discount = $discount_amount_per_day;
            } else {
                $pre_total_discount = $discount_amount_per_day;
            }

            $total_sum = ($rent_per_day * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;

            // Calculating and adding VAT
            $site_settings = custom::site_settings();
            $vat_mode = $site_settings->vat_mode_for_mobile;
            $vat_percentage = ($vat_mode == 'on' && $site_settings->vat_percentage > 0 ? $site_settings->vat_percentage : 0);
            $vat = ($vat_percentage / 100) * $total_sum; // calculating VAT on total sum
            $booking_payment_data['total_sum'] = $total_sum + $vat - $qitaf_amount - $niqaty_amount - $anb_amount - $mokafaa_amount - $post_total_discount; // adding value added tax to total amount
            $booking_payment_data['vat_percentage'] = $vat_percentage;
            $booking_payment_data['vat_applied'] = $vat;


            // did this here to fix the issue of duplicated qitaf request ids and reversal, happening in mobile apps only, updating old one before adding new one
            if ($booking_payment_data['qitaf_request'] != '') {
                $this->page->updateData('booking_payment', ['qitaf_request' => ''], ['qitaf_request' => $booking_payment_data['qitaf_request']]);
            }

            $booking_payment_data['car_rate_is_with_additional_utilization_rate'] = (isset($_REQUEST['car_rate_is_with_additional_utilization_rate']) && $_REQUEST['car_rate_is_with_additional_utilization_rate'] == 1 ? 1 : 0);

            $this->page->saveData('booking_payment', $booking_payment_data);

            $booking_details['booking_details'] = $this->services->getSingleBookingDetailsForCorporate($savedBookingId);

            if ($is_delivery_mode == 1) {
                $from_branch_id_for_cc = $from_branch_id;
            } else {
                $from_branch_id_for_cc = 0;
            }
            $paytabs_params['pt_merchant_email'] = $api_settings->paytabs_merchant_email;
            $paytabs_params['paytabs_merchant_id'] = $api_settings->paytabs_merchant_id;
            $paytabs_params['pt_secret_key'] = $api_settings->paytabs_secret_key;
            $paytabs_params['pt_transaction_title'] = $first_name . ' ' . $last_name;
            $paytabs_params['pt_amount'] = $booking_payment_data['total_sum'];
            $paytabs_params['pt_currency_code'] = 'SAR';
            $paytabs_params['pt_customer_email'] = $email;
            $paytabs_params['pt_customer_phone_number'] = $mobile_no;
            $paytabs_params['pt_order_id'] = $savedBookingId . '-' . $this->lang . '-' . $from_branch_id_for_cc . '-' . 'corporate_customer';
            if ($this->lang == 'eng') {
                $paytabs_params['pt_product_name'] = $booking_details['booking_details']->car_model_eng_title . ' | ' . $booking_details['booking_details']->car_type_eng_title;
            } else {
                $paytabs_params['pt_product_name'] = $booking_details['booking_details']->car_model_arb_title . ' | ' . $booking_details['booking_details']->car_type_arb_title;
            }

            // If credit card method is used than redirecting to payment page
            if ($payment_method == 'cc') {
                $booking_cc['booking_id'] = $savedBookingId;
                $booking_cc['status'] = 'pending';
                if (isset($_REQUEST['is_mada']) && $_REQUEST['is_mada'] == 1) {
                    $booking_cc['card_brand'] = 'Mada';
                }
                $site_settings = custom::site_settings();
                if ($site_settings->cc_company == 'sts') {
                    $booking_cc['payment_company'] = 'sts';
                } elseif ($site_settings->cc_company == 'hyper_pay') {
                    $booking_cc['payment_company'] = 'hyper_pay';
                }
                $this->page->saveData('booking_cc_payment', $booking_cc);
                $response['status'] = 1;
                $response['user_already_has_account'] = 1;
                $response['message'] = "Thank you for booking at " . custom::getSiteName($this->lang) . ". You will shortly receive confirmation email and sms containing your booking details.";
                $response['response'] = array('payment_method' => 'cc', 'booking_details' => $booking_details['booking_details'], 'url' => '');
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                // send email to admin, primary, secondary emails and driver email
                // $this->sendToKeyAdmin($savedBookingId);
                // $this->sendToCorporateUser($savedBookingId);
                // $this->sendToDriver($savedBookingId);
                // send sms to driver
                $this->sendCorporateBookingSms($booking_info_extra['reservation_code'], $corporate_driver['mobile_no']);

                if ($is_delivery_mode == 1) {
                    $customer_name = $first_name . ' ' . $last_name;
                    // $this->send_email_to_branch_agent($savedBookingId, $branch_info->email, $customer_name);
                    $this->send_sms_to_branch_agent($booking_info_extra['reservation_code'], $branch_info->mobile, $customer_name);
                }

                $response['status'] = 1;
                $response['user_already_has_account'] = 1;
                $response['message'] = "Thank you for booking at " . custom::getSiteName($this->lang) . ". You will shortly receive confirmation email and sms containing your booking details.";
                $response['response'] = array('payment_method' => $method_is, 'booking_details' => $booking_details['booking_details'], 'url' => '');
                $response['paytabs_params'] = $paytabs_params;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendCorporateBookingSms($reservation_code, $userPhoneNo)
    {
        try {
            $lang = $this->lang;
            $lang_base_url = $this->lang_base_url;
            $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
            //========send thank you sms with reservation number
            if ($lang == "eng") {
                $smsMsg = "Thank you for booking at " . custom::getSiteName($this->lang) . " Reservation# ";
            } else {
                $smsMsg = "Thank you for booking at " . custom::getSiteName($this->lang) . " Reservation# ";
            }
            //reservation thankyou sms
            $smsMsg .= $reservation_code . "\n";
            $smsMsg .= "Click below link to see this reservation details.\n";
            $smsMsg .= $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id);
            $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);

            custom::sendSMS($smsPhone, $smsMsg, $lang);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendToKeyAdmin($booking_id)
    {
        // return true; // put it here as Fozan asked to stop sending emails to admin on 07-02-2018.
        $lang = $this->lang;
        $smtp_settings = custom::smtp_settings();
        $site_settings = custom::site_settings();
        $emailData = array();
        $email = array();
        $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));
        if ($booking_detail->type == "corporate_customer") {
            $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
        } else {
            $emailObj = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            $emailData['booking_content'] = (array)$emailObj;
        }


        if (isset($emailObj->icg_email) && $emailObj->icg_email != null) {
            //guest info with icg_
            $first_name = $emailObj->icg_first_name;
            $last_name = $emailObj->icg_last_name;
            $gender = $emailObj->icg_gender;
            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->icg_id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->icg_mobile_no;
            $emailData['booking_content']['email'] = $emailObj->icg_email;

            $emailAddress = $emailObj->icg_email;

        } else {
            //customer info is of logged in user and id joing is from booking_individual_user
            $first_name = $emailObj->first_name;
            $last_name = $emailObj->last_name;
            $gender = $emailObj->gender;

            $emailData['booking_content']['first_name'] = $first_name;
            $emailData['booking_content']['last_name'] = $last_name;
            $emailData['booking_content']['gender'] = $gender;

            $emailData['booking_content']['id_no'] = $emailObj->id_no;
            $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
            $emailData['booking_content']['email'] = $emailObj->email;

            $emailAddress = $emailObj->email;
        }

        if ($lang == "eng") {
            $subject = "Booking confirmation";
        } else {
            $subject = "تأكيد الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $smtp_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $site_settings->admin_email;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendToCorporateUser($booking_id)
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $emailData['booking_content'] = (array)$emailObj;
        //customer info is of logged in user and id joing is from booking_individual_user
        $first_name = $emailObj->first_name;
        $last_name = $emailObj->last_name;
        $gender = $emailObj->gender;

        $emailData['booking_content']['first_name'] = $first_name;
        $emailData['booking_content']['last_name'] = $last_name;
        $emailData['booking_content']['gender'] = $gender;

        $emailData['booking_content']['id_no'] = $emailObj->id_no;
        $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
        $emailData['booking_content']['email'] = $emailObj->email;

        $emailAddress = $emailObj->corporate_user_email;

        if ($lang == "eng") {
            $subject = "Booking confirmation";
        } else {
            $subject = "تأكيد الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendToDriver($booking_id)
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $emailData['booking_content'] = (array)$emailObj;
        //customer info is of logged in user and id joing is from booking_individual_user
        $first_name = $emailObj->first_name;
        $last_name = $emailObj->last_name;
        $gender = $emailObj->gender;

        $emailData['booking_content']['first_name'] = $first_name;
        $emailData['booking_content']['last_name'] = $last_name;
        $emailData['booking_content']['gender'] = $gender;

        $emailData['booking_content']['id_no'] = $emailObj->id_no;
        $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
        $emailData['booking_content']['email'] = $emailObj->email;

        $emailAddress = $emailObj->email;

        if ($lang == "eng") {
            $subject = "Booking confirmation";
        } else {
            $subject = "تأكيد الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendCancellationEmailToCorporateUser($booking_id)
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $emailData['booking_content'] = (array)$emailObj;
        //customer info is of logged in user and id joing is from booking_individual_user
        $first_name = $emailObj->first_name;
        $last_name = $emailObj->last_name;
        $gender = $emailObj->gender;

        $emailData['booking_content']['first_name'] = $first_name;
        $emailData['booking_content']['last_name'] = $last_name;
        $emailData['booking_content']['gender'] = $gender;

        $emailData['booking_content']['id_no'] = $emailObj->id_no;
        $emailData['booking_content']['mobile_no'] = $emailObj->mobile_no;
        $emailData['booking_content']['email'] = $emailObj->email;

        $emailAddress = $emailObj->corporate_user_email;

        if ($this->lang == "eng") {
            $subject = "Booking Cancellation";
        } else {
            $subject = "تأكيد إلغاء الحجز";
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        // generate and attache pdf to email
        $fileName = $this->bookingCancellationPdf($emailData);

        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';

        $email['attachment'] = $attachment;

        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    public function checkIfRedeemable()
    {
        // Only to check if user is logged in and is individual customer OR trying his loyalty through popup AND has silver, golden or platinum loyalty type.

        try {
            $site_settings = custom::site_settings();
            $id_no = $_REQUEST['id_no'];
            $days_for_checkout = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days_for_checkout = 30;
            }
            $rent_per_day = $_REQUEST['rent_per_day'];
            $redeem_offer_id = $_REQUEST['redeem_offer_id']; // will be called only if the car has redeem offer for it

            if ($redeem_offer_id > 0) {
                $fetch_customer_info_by['id_no'] = $id_no;
                $user_info = $this->page->getSingle('individual_customer', $fetch_customer_info_by);

                // checking if user can use redeem points
                if ($user_info->loyalty_card_type != '' && $user_info->loyalty_card_type != 'Bronze') {
                    $redeemOfferAvailable = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
                    if ($redeemOfferAvailable) {
                        $canUseRedeemOffer = true;
                        $redeemPointsAvailableForUser = $user_info->loyalty_points;
                        $redeemLoyaltyTypeForUser = $user_info->loyalty_card_type;
                        $MaxRedeemablePoints = $this->MaxRedeemablePoints($redeemLoyaltyTypeForUser, $days_for_checkout, $redeem_offer_id, $rent_per_day);
                        $MaxRedeemableAmount = $this->MaxRedeemableAmount($days_for_checkout, $redeem_offer_id, $rent_per_day);
                    } else {
                        $canUseRedeemOffer = false;
                        $redeemPointsAvailableForUser = 0;
                        $redeemLoyaltyTypeForUser = "";
                        $MaxRedeemablePoints = 0;
                        $MaxRedeemableAmount = 0;
                    }
                } else {
                    $canUseRedeemOffer = false;
                    $redeemPointsAvailableForUser = 0;
                    $redeemLoyaltyTypeForUser = "";
                    $MaxRedeemablePoints = 0;
                    $MaxRedeemableAmount = 0;
                }

                $allowed_days_for_redeem = $site_settings->days_for_redeem;
                if ($days_for_checkout > $allowed_days_for_redeem) {
                    $days_for_redeem = $allowed_days_for_redeem;
                } else {
                    $days_for_redeem = $days_for_checkout;
                }

                $response['customer_loyalty_points'] = $redeemPointsAvailableForUser;
                $response['customer_loyalty_type'] = $redeemLoyaltyTypeForUser;

                // to be shown under the fields of redeem section e.g (Max: 3000 Points)
                $response['max_redeemable_points'] = $MaxRedeemablePoints;
                $response['max_redeemable_amount'] = $MaxRedeemableAmount;
                $response['redeem_applicable_for_days'] = "$days_for_redeem";

                if ($canUseRedeemOffer) {
                    // code here for autofill the fields
                    if ($redeemPointsAvailableForUser >= $MaxRedeemablePoints) {
                        $customer_redeemable_points = $MaxRedeemablePoints;
                        $customer_redeemable_amount = $MaxRedeemableAmount;
                    } elseif ($MaxRedeemablePoints > $redeemPointsAvailableForUser) {
                        $customer_redeemable_points = $redeemPointsAvailableForUser;
                        $customer_redeemable_amount = $this->convertRedeemPointsToAmount($redeemLoyaltyTypeForUser, $redeemPointsAvailableForUser, $rent_per_day, $days_for_checkout, $redeem_offer_id);
                    }
                    $textual_redeem_section_text = ($this->lang == 'eng' ?
                        'Use ' . $customer_redeemable_amount . ' SAR for redeem of ' . $customer_redeemable_points . ' points' :
                        'استخدم ' . $customer_redeemable_amount . ' ريال باستبدال ' . $customer_redeemable_points . ' نقطة');
                } else {
                    $customer_redeemable_points = 0;
                    $customer_redeemable_amount = 0;
                    $textual_redeem_section_text = '';
                }

                // these two fields are to be filled inside the redeem section fields
                $response['customer_redeemable_points'] = $customer_redeemable_points;
                $response['customer_redeemable_amount'] = $customer_redeemable_amount;
                $response['redeem_section_type'] = $site_settings->redeem_offer_mode_type;
                $response['textual_redeem_section_text'] = $textual_redeem_section_text;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function MaxRedeemablePoints($customer_redeem_loyalty_type, $days_for_checkout, $redeem_offer_id, $rent_per_day)
    {
        $points = array();
        $amount_sar = array();
        $remaining_amount = array();
        $used_amount = array();
        $used_points = array();

        $site = custom::site_settings();
        $allowed_days_for_redeem = $site->days_for_redeem;

        if ($customer_redeem_loyalty_type == 'Silver') {
            $factor_to_divide = $site->silver_redeem_factor;
        } elseif ($customer_redeem_loyalty_type == 'Golden') {
            $factor_to_divide = $site->golden_redeem_factor;
        } elseif ($customer_redeem_loyalty_type == 'Platinum') {
            $factor_to_divide = $site->platinum_redeem_factor;
        } else {
            $factor_to_divide = 1;
        }

        if ($days_for_checkout > $allowed_days_for_redeem) {
            $days_to_calculate_and_check = $allowed_days_for_redeem;
        } else {
            $days_to_calculate_and_check = $days_for_checkout;
        }

        $redeemOffer = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
        if ($redeemOffer->type_of_redeem == 'Percentage') {
            $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
            $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
            $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
        } else {
            $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
        }
        $amount_redeemable = $amount_redeemable / $factor_to_divide;
        $redeem_factors = $this->page->getAll('setting_redeem_factor');
        $grp = 0;
        foreach ($redeem_factors as $redeem_factor) {
            $from_points = $redeem_factor->from_points;
            $to_points = $redeem_factor->to_points;
            $points_per_riyal = $redeem_factor->points_per_riyal;

            $grpPrev = $grp - 1;
            $points[] = $to_points - $from_points + 1;
            $amount_sar[] = round(($to_points - $from_points + 1) / $points_per_riyal, 2);
            if ($grp === 0) $remaining_amount[] = $amount_redeemable;
            else {
                $remaining_amount[] = max(0, $remaining_amount[$grpPrev] - $amount_sar[$grpPrev]);
            }
            $used_amount[] = min($remaining_amount[$grp], $amount_sar[$grp]);
            $used_points[] = $used_amount[$grp] * $points_per_riyal;

            $grp++;
        }
        $total_redeemed_points = round(array_sum($used_points), 0);
        return $total_redeemed_points;
    }

    private function MaxRedeemableAmount($days_for_checkout, $redeem_offer_id, $rent_per_day)
    {
        $site = custom::site_settings();
        $allowed_days_for_redeem = $site->days_for_redeem;

        /*  Validation how much riyal can be redeemed in a single checkout.
        % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
        if ($days_for_checkout > $allowed_days_for_redeem) {
            $days_to_calculate_and_check = $allowed_days_for_redeem;
        } else {
            $days_to_calculate_and_check = $days_for_checkout;
        }

        $redeemOffer = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
        if ($redeemOffer->type_of_redeem == 'Percentage') {
            $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
            $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
            $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
        } else {
            $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
        }
        return $amount_redeemable;
    }

    private function convertRedeemPointsToAmount($customer_redeem_loyalty_type, $points_to_redeem, $rent_per_day, $days_for_checkout, $redeem_offer_id)
    {
        $site = custom::site_settings();
        if ($customer_redeem_loyalty_type == 'Silver') {
            $factor_to_multiply = $site->silver_redeem_factor;
        } elseif ($customer_redeem_loyalty_type == 'Golden') {
            $factor_to_multiply = $site->golden_redeem_factor;
        } elseif ($customer_redeem_loyalty_type == 'Platinum') {
            $factor_to_multiply = $site->platinum_redeem_factor;
        } else {
            $factor_to_multiply = 1;
        }
        $allowed_days_for_redeem = $site->days_for_redeem;

        $points = $points_to_redeem;    //user input this value
        $to_points = array();
        $redeem_points = array();
        $redeem_riyal = array();

        $redeem_factors = $this->page->getAll('setting_redeem_factor');
        $grp = 0;
        foreach ($redeem_factors as $redeem_factor) {
            $from_points = $redeem_factor->from_points;
            $to_points[] = $redeem_factor->to_points;
            $points_per_riyal = $redeem_factor->points_per_riyal;

            $grpPrev = $grp - 1;
            if ($grp === 0) $redeem_points[] = max(0, $points - max(0, $points - $to_points[$grp]));
            else {
                $redeem_points[] = max(0, max(0, $points - $to_points[$grpPrev]) - max(0, $points - $to_points[$grp]));
            }
            $redeem_riyal[] = round($redeem_points[$grp] / $points_per_riyal, 2);

            $grp++;
        }
        $total_redeemed_amount = round(array_sum($redeem_riyal), 1);

        /*  Validation how much riyal can be redeemed in a single checkout.
        % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
        if ($days_for_checkout > $allowed_days_for_redeem) {
            $days_to_calculate_and_check = $allowed_days_for_redeem;
        } else {
            $days_to_calculate_and_check = $days_for_checkout;
        }

        $redeemOffer = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
        if ($redeemOffer->type_of_redeem == 'Percentage') {
            $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
            $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
            $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
        } else {
            $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
        }
        // $amount_redeemable_multiplied_factor = $amount_redeemable * $factor_to_multiply;
        $total_redeemed_amount_multiplied_factor = $total_redeemed_amount * $factor_to_multiply;
        //return $amount_redeemable;
        return $total_redeemed_amount_multiplied_factor;
    }

    private function add_or_deduct_loyalty_points_for_customer($booking_id, $add_or_deduct = 'add')
    {
        $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));
        $booking_payment_detail = $this->page->getSingle("booking_payment", array("booking_id" => $booking_id));
        $redeem_points_used = ($booking_payment_detail->redeem_points > 0 ? $booking_payment_detail->redeem_points : 0);
        if ($booking_detail->type == "individual_customer") {
            $checkForCustomerUid = $this->page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
            $checkForCustomerId = $this->page->getSingle('individual_customer', array('uid' => $checkForCustomerUid->uid));
            $ind_customer_id = $checkForCustomerId->id;
        } else {
            $checkForCustomerId = $this->page->getSingle('booking_individual_guest', array('booking_id' => $booking_id));
            $ind_customer_id = $checkForCustomerId->individual_customer_id;
        }
        // updating customer loyalty points
        $customer_info = $this->page->getSingle('individual_customer', array('id' => $ind_customer_id));
        if ($customer_info && $customer_info->loyalty_points > 0) {
            $customer_old_points = $customer_info->loyalty_points;
            if ($add_or_deduct == 'add') {
                $customer_new_points = round((int)$customer_old_points, 2) + round((int)$redeem_points_used, 2);
            } elseif ($add_or_deduct == 'deduct') {
                $customer_new_points = round((int)$customer_old_points, 2) - round((int)$redeem_points_used, 2);
            }
            $this->page->updateData('individual_customer', array('loyalty_points' => $customer_new_points), array('id' => $ind_customer_id));
            // an api call will be here to sync customer loyalty points with oasis system
        }
    }

    public function calculateRedeemPointsFromAmount()
    {
        try {
            $site = custom::site_settings();

            $customer_total_points = $_REQUEST['customer_total_points'];
            $amount_to_redeem = $_REQUEST['amount_to_redeem'];
            $customer_redeem_loyalty_type = $_REQUEST['customer_loyalty_type'];
            $redeem_offer_id = $_REQUEST['redeem_offer_id'];
            $rent_per_day = $_REQUEST['rent_per_day'];
            $days_for_checkout = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days_for_checkout = 30;
            }

            if ($customer_redeem_loyalty_type == 'Silver') {
                $factor_to_divide = $site->silver_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Golden') {
                $factor_to_divide = $site->golden_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Platinum') {
                $factor_to_divide = $site->platinum_redeem_factor;
            } else {
                $factor_to_divide = 1;
            }
            $amount_to_redeem_divided_factor = $amount_to_redeem / $factor_to_divide;
            $allowed_days_for_redeem = $site->days_for_redeem;

            /*  Validation how much riyal can be redeemed in a single checkout.
                % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
            if ($days_for_checkout > $allowed_days_for_redeem) {
                $days_to_calculate_and_check = $allowed_days_for_redeem;
            } else {
                $days_to_calculate_and_check = $days_for_checkout;
            }

            $redeemOffer = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
            if ($redeemOffer->type_of_redeem == 'Percentage') {
                $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
                $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
                $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
            } else {
                $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
            }

            //$amount_redeemable_divided_factor = $amount_redeemable / $factor_to_divide;
            if ($amount_to_redeem > $amount_redeemable) {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ?
                    'The amount you have entered can not be redeemed. Please reduce the amount. (Max: ' . $amount_redeemable . ' SAR)' :
                    'لايمكن استخدام المبلغ المدخل، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                $response['response']['total_redeemed_amount'] = 0;
                $response['response']['total_redeemed_points'] = 0;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                $riyal = $amount_to_redeem_divided_factor; //user input this value
                $points = array();
                $amount_sar = array();
                $remaining_amount = array();
                $used_amount = array();
                $used_points = array();

                $redeem_factors = $this->page->getAll('setting_redeem_factor');
                if ($redeem_factors) {
                    $grp = 0;
                    foreach ($redeem_factors as $redeem_factor) {
                        $from_points = $redeem_factor->from_points;
                        $to_points = $redeem_factor->to_points;
                        $points_per_riyal = $redeem_factor->points_per_riyal;

                        $grpPrev = $grp - 1;
                        $points[] = $to_points - $from_points + 1;
                        $amount_sar[] = round(($to_points - $from_points + 1) / $points_per_riyal, 2);
                        if ($grp === 0) $remaining_amount[] = $riyal;
                        else {
                            $remaining_amount[] = max(0, $remaining_amount[$grpPrev] - $amount_sar[$grpPrev]);
                        }
                        $used_amount[] = min($remaining_amount[$grp], $amount_sar[$grp]);
                        $used_points[] = $used_amount[$grp] * $points_per_riyal;

                        $grp++;
                    }
                    $total_redeemed_points = round(array_sum($used_points), 0);
                    if ($total_redeemed_points > $customer_total_points) {
                        $response['status'] = 0;
                        $response['message'] = ($this->lang == 'eng' ?
                            'The amount you have entered can not be redeemed. Please reduce the amount. (Max: ' . $amount_redeemable . ' SAR)' :
                            'لايمكن استخدام المبلغ المدخل، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                        $response['response']['total_redeemed_amount'] = 0;
                        $response['response']['total_redeemed_points'] = 0;
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    } else {
                        $response['status'] = 1;
                        $response['message'] = ($this->lang == 'eng' ? 'Redeem Applied Successfully.' : 'تم إضافة خصم نقاط الولاء');
                        $response['response']['total_redeemed_amount'] = $amount_to_redeem;
                        $response['response']['total_redeemed_points'] = $total_redeemed_points;
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit();
                    }
                } else {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ? 'Sorry. The redeem can not be applied as no redeem factors are added in backend.' : 'عفوا لا يمكن إضافة عرض الاسترداد');
                    $response['response']['total_redeemed_amount'] = 0;
                    $response['response']['total_redeemed_points'] = 0;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function calculateRedeemAmountFromPoints()
    {
        try {
            $site = custom::site_settings();

            $customer_total_points = $_REQUEST['customer_total_points'];
            $points_to_redeem = $_REQUEST['points_to_redeem'];
            $customer_redeem_loyalty_type = $_REQUEST['customer_loyalty_type'];
            $redeem_offer_id = $_REQUEST['redeem_offer_id'];
            $rent_per_day = $_REQUEST['rent_per_day'];
            $days_for_checkout = $_REQUEST['days'];
            if (isset($_REQUEST['is_delivery_mode']) && ($_REQUEST['is_delivery_mode'] == 4)) {
                $days_for_checkout = 30;
            }

            if ($customer_redeem_loyalty_type == 'Silver') {
                $factor_to_multiply = $site->silver_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Golden') {
                $factor_to_multiply = $site->golden_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Platinum') {
                $factor_to_multiply = $site->platinum_redeem_factor;
            } else {
                $factor_to_multiply = 1;
            }
            $allowed_days_for_redeem = $site->days_for_redeem;

            $points = $points_to_redeem;    //user input this value
            $to_points = array();
            $redeem_points = array();
            $redeem_riyal = array();

            $redeem_factors = $this->page->getAll('setting_redeem_factor');
            if ($redeem_factors) {
                $grp = 0;
                foreach ($redeem_factors as $redeem_factor) {
                    $from_points = $redeem_factor->from_points;
                    $to_points[] = $redeem_factor->to_points;
                    $points_per_riyal = $redeem_factor->points_per_riyal;

                    $grpPrev = $grp - 1;
                    if ($grp === 0) $redeem_points[] = max(0, $points - max(0, $points - $to_points[$grp]));
                    else {
                        $redeem_points[] = max(0, max(0, $points - $to_points[$grpPrev]) - max(0, $points - $to_points[$grp]));
                    }
                    $redeem_riyal[] = round($redeem_points[$grp] / $points_per_riyal, 2);

                    $grp++;
                }
                $total_redeemed_amount = round(array_sum($redeem_riyal), 1);

                /*  Validation how much riyal can be redeemed in a single checkout.
                % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
                if ($days_for_checkout > $allowed_days_for_redeem) {
                    $days_to_calculate_and_check = $allowed_days_for_redeem;
                } else {
                    $days_to_calculate_and_check = $days_for_checkout;
                }

                $redeemOffer = $this->page->getSingle('redeem_setup', array('id' => $redeem_offer_id));
                if ($redeemOffer->type_of_redeem == 'Percentage') {
                    $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
                    $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
                    $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
                } else {
                    $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
                }
                // $amount_redeemable_multiplied_factor = $amount_redeemable * $factor_to_multiply;
                $total_redeemed_amount_multiplied_factor = $total_redeemed_amount * $factor_to_multiply;
                if ($total_redeemed_amount_multiplied_factor > $amount_redeemable) {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ?
                        'The number of points you have entered can not be redeemed. You can only redeem this maximum amount. (Max: ' . $amount_redeemable . ' SAR)' :
                        'لايمكن استخدام عدد النقاط المدخلة، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                    $response['response']['total_redeemed_amount'] = 0;
                    $response['response']['total_redeemed_points'] = 0;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } elseif ($points_to_redeem > $customer_total_points) {
                    $response['status'] = 0;
                    $response['message'] = ($this->lang == 'eng' ?
                        'The number of points you have entered exceeding the number of points you have. PLease reduce the points you want to use. (Max: ' . $amount_redeemable . ' SAR)' :
                        'لايمكن استخدام عدد النقاط المدخلة، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                    $response['response']['total_redeemed_amount'] = 0;
                    $response['response']['total_redeemed_points'] = 0;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    $response['status'] = 1;
                    $response['message'] = ($this->lang == 'eng' ? 'Redeem Applied Successfully.' : 'تم إضافة خصم نقاط الولاء');
                    $response['response']['total_redeemed_amount'] = $total_redeemed_amount_multiplied_factor;
                    $response['response']['total_redeemed_points'] = $points_to_redeem;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            } else {
                $response['status'] = 0;
                $response['message'] = ($this->lang == 'eng' ? 'Sorry. The redeem can not be applied as no redeem factors are added in backend.' : 'عفوا لا يمكن إضافة عرض الاسترداد');
                $response['response']['total_redeemed_amount'] = 0;
                $response['response']['total_redeemed_points'] = 0;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsPaymentSuccess()
    {
        try {
            //$booking_id = $_REQUEST['booking_id'];
            //this is happening from mada mobile web view only in android
            //so to avoid the reupload the android app we are replacing 'KEY' with empty.
            $booking_id = str_replace('KEY', '', $_REQUEST['booking_id']);
            $from_branch_id = $_REQUEST['from_branch_id'];
            $transaction_id = $_REQUEST['transaction_id'];
            $user_mobile_no = $_REQUEST['user_mobile_no'];
            $first_4_digits = $_REQUEST['first_4_digits'];
            $last_4_digits = $_REQUEST['last_4_digits'];
            $card_brand = $_REQUEST['card_brand'];

            //this is happening only in mada IOS app so to avoid the re-upload of ios app.
            if (isset($_REQUEST['sts_attempts']) && $_REQUEST['sts_attempts'] == "")
                unset($_REQUEST['sts_attempts']);

            if (isset($_REQUEST['sts_attempts']))
                $sts_attempts = $_REQUEST['sts_attempts'];

            $booking_cc_update['is_sts_inquired'] = 0;
            $booking_cc_update['status'] = 'completed';
            $booking_cc_update['transaction_id'] = $transaction_id;
            $booking_cc_update['first_4_digits'] = $first_4_digits;
            $booking_cc_update['last_4_digits'] = $last_4_digits;
            $booking_cc_update['card_brand'] = $card_brand;
            if (isset($_REQUEST['sts_attempts']))
                $booking_cc_update['sts_attempts'] = $sts_attempts;
            $booking_cc_update['payment_company'] = 'sts';
            $booking_cc_update['trans_date'] = date('Y-m-d H:i:s');

            $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
            $bookingInfo = $this->page->getSingle('booking', array("id" => $booking_id));
            if ($bookingInfo->type == "corporate_customer") {
                // $this->sendToKeyAdmin($booking_id);
                // $this->sendToCorporateUser($booking_id);
                // $this->sendToDriver($booking_id);
                $this->sendCorporateBookingSms($bookingInfo->reservation_code, $user_mobile_no);
            } else {
                // $this->sendEmailToUser($booking_id);
                // $this->sendToKeyAdmin($booking_id);
                $this->sendThankYouSMS($bookingInfo->reservation_code, $user_mobile_no);
            }

            // code to send email and sms to branch agent starts here
            if ($from_branch_id > 0) {
                if ($bookingInfo->type == 'corporate_customer') {
                    $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id);
                } else {
                    $emailObj = $this->page->getSingleBookingDetails($booking_id);
                }
                if (isset($emailObj->icg_email) && $emailObj->icg_email != null) {
                    $first_name = $emailObj->icg_first_name;
                    $last_name = $emailObj->icg_last_name;
                } else {
                    $first_name = $emailObj->first_name;
                    $last_name = $emailObj->last_name;
                }
                //$customer_name = $response_from_api['customer_name'];
                $customer_name = $first_name . ' ' . $last_name;
                $branch_info = $this->page->getSingle('branch', array('id' => $from_branch_id));
                if ($branch_info->email) {
                    // $this->send_email_to_branch_agent($booking_id, $branch_info->email, $first_name, $last_name);
                    $this->send_sms_to_branch_agent($bookingInfo->reservation_code, $branch_info->mobile, $customer_name);
                }
            }

            if ($bookingInfo->type == "individual_customer" || $bookingInfo->type == "guest") {
                // Deducting and updating customer's redeem points
                $this->add_or_deduct_loyalty_points_for_customer($booking_id, 'deduct');
            }

            // For now, commented By Bilal on 03-12-2019
            if ($_SERVER['HTTP_HOST'] == 'key.sa') {
                // hitting cronjob to sync bookings
                $cronjob_url = custom::baseurl('/') . '/cronjob/setDataCronJob';
                // file_get_contents($cronjob_url); // commented by Bilal on 18-08-2020, file_get_contents function not working on new server for now so doing it through curl
                $curlResponse = $this->sendCurlRequest($cronjob_url);
            }

            $response['status'] = 1;
            $response['message'] = 'Success';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();

            /*$response['status'] = 0;
            $response['message'] = 'Error';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();*/
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function updateStsAttempts()
    {
        try {
            $response = array();
            $booking_id = $_REQUEST['booking_id'];
            $booking_cc_update['is_sts_inquired'] = 0;
            $booking_cc_update['sts_attempts'] = $_REQUEST['sts_attempts'];
            $updateAttempts = $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
            if ($updateAttempts) {
                $response['status'] = 1;
                $response['message'] = 'Attempts saved successfully';
            } else {
                $response['status'] = 0;
                $response['message'] = 'Error in saving attempts';

            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsTransactionLog(Request $request)
    {
        try {
            $transaction_id = $_REQUEST['transaction_id'];
            $type = $_REQUEST['type'];
            $json_response = json_decode($_REQUEST['response']);
            $resp = $json_response->response;
            $get_response = '';
            foreach ($resp as $key => $value) {
                $get_response .= '|' . $key . '=' . $value;
            }
            //STS response log
            $insert_id = $this->page->saveData('booking_sts_log',
                array('transaction_id' => $transaction_id,
                    'type' => $type,
                    'response' => $get_response,
                    'created_at' => date('Y-m-d H:i:s')
                )
            );
            if ($insert_id > 0) {
                $response['status'] = 1;
                $response['message'] = 'STS response saved successfully';
            } else {
                $response['status'] = 0;
                $response['message'] = 'Error in saving STS response';

            }
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function qitafSendOTP(Request $request)
    {
        $api_settings = custom::api_settings();
        $branch_info = $this->page->getSingle('branch', array('id' => $request->from_branch_id));
        $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/GenerateOtp?mobile=' . $request->mobile_number . '&branch=' . $branch_info->oracle_reference_number;
        $this->logQitafResponse($api_url, 'GenerateOtpRequest');
        $curlResponse = $this->sendCurlRequest($api_url);
        $this->logQitafResponse($curlResponse, 'GenerateOtpResponse');
        $response['status'] = (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false ? 1 : 0);
        $response['message'] = $this->qitaf_error($curlResponse);
        echo json_encode($response);
        die();
    }

    public function qitafRedeem(Request $request)
    {
        // $request->total_payable it will be total amount without vat
        $response = array();
        $api_settings = custom::api_settings();
        $site_settings = custom::site_settings();
        $branch_info = $this->page->getSingle('branch', array('id' => $request->from_branch_id));

        $payable_amount_arr = $this->checkIfQitafRedeemable($request->qitaf_amount, $request->total_payable);

        $payable_amount = $payable_amount_arr['total_payable_after_qitaf'];
        $vat_applicable = $payable_amount_arr['vat_after_qitaf'];

        $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/OnlineRedeemPoints?mobile=' . $request->mobile_number . '&branch=' . $branch_info->oracle_reference_number . '&otp=' . $request->qitaf_otp . '&points=' . $request->qitaf_amount;
        $this->logQitafResponse($api_url, 'OnlineRedeemPointsRequest');
        $curlResponse = $this->sendCurlRequest($api_url);
        $this->logQitafResponse($curlResponse, 'OnlineRedeemPointsResponse');
        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            $curlResponse .= ',' . $request->mobile_number . ',' . $branch_info->oracle_reference_number;

            /*$curlResponse = explode(',', $curlResponse);
            $curlResponse[] = $request->mobile_number;
            $curlResponse[] = $branch_info->oracle_reference_number;
            $curlResponse = implode(',', $curlResponse);*/

            // saving the data in database so that we can use it to redeem back the qitaf amount if booking not completed
            $curlResponse = str_replace('.', ',', $curlResponse);
            $this->page->saveData('qitaf_logs', array('status' => 'New', 'qitaf_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            // $this->callQitafReverseRedeemAPI($curlResponse); #todo: we will remove it once we move it to live
            $response['status'] = 1;
            $response['message'] = $curlResponse;
            $response['qitaf_request'] = $curlResponse;
            $response['qitaf_amount'] = $request->qitaf_amount;
            $response['amount_remaining'] = $payable_amount > 0;
            $response['total_payable_amount_after_qitaf'] = round($payable_amount, 2);
            $response['vat_after_qitaf'] = round($vat_applicable, 2);
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.qitaf_partial_redeem_text_to_show'), $request->qitaf_amount, round($payable_amount, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.qitaf_full_redeem_text_to_show'), $request->qitaf_amount);
            }
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = $this->qitaf_error($curlResponse);
            echo json_encode($response);
            die();
        }
    }

    private function checkIfQitafRedeemable($qitaf_amount, $total_amount_without_vat)
    {
        $site_settings = custom::site_settings();

        $vat_percentage = $site_settings->vat_percentage;

        $vat_applicable = ($vat_percentage / 100) * $total_amount_without_vat;
        $total_amount_with_vat = $total_amount_without_vat + $vat_applicable;

        $max_amount_redeemable_with_qitaf = round(($site_settings->amount_to_be_redeemed_by_qitaf_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($qitaf_amount > $max_amount_redeemable_with_qitaf) {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_qitaf);
            echo json_encode($response);
            die();
        }

        // below commented code is when we needed to minus qitaf first than calculate vat, but Kholoud asked us to not do this.
        // $total_amount_without_vat_after_qitaf = $total_amount_without_vat - $qitaf_amount;
        // $vat_applicable = ($vat_percentage / 100) * $total_amount_without_vat_after_qitaf;
        // $total_amount_with_vat_after_qitaf = $total_amount_without_vat_after_qitaf + $vat_applicable;

        // return $total_amount_with_vat_after_qitaf;

        return array('total_payable_after_qitaf' => $total_amount_with_vat - $qitaf_amount, 'vat_after_qitaf' => $vat_applicable);
    }

    private function sendCurlRequest($curl_url, $dump = false)
    {
        if ($dump) {
            echo $curl_url;die;
        }
        $curlConnection = curl_init();
        curl_setopt($curlConnection, CURLOPT_URL, $curl_url);
        curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
        $curlResponse = curl_exec($curlConnection);
        curl_close($curlConnection);
        return $curlResponse;
    }

    private function logQitafResponse($txt, $type)
    {
        // file_put_contents('qitaf.txt', $type . '=' . $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function callQitafReverseRedeemAPI($data)
    {
        if ($data != "") {
            $data = str_replace('.', ',', $data);
            $api_settings = custom::api_settings();
            $data = explode(',', $data);
            $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/ReverseRedeem?mobile=' . ltrim($data[2], '0') . '&branch=' . $data[3] . '&request_id=' . $data[1] . '&request_date=' . $data[0];
            $this->logQitafResponse($api_url, 'ReverseRedeemRequest');
            $curlResponse = $this->sendCurlRequest($api_url);
            $this->logQitafResponse($curlResponse, 'ReverseRedeemResponse');
        }
    }

    private function autoReverseQitafRedeem($booking_id)
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        $this->callQitafReverseRedeemAPI(str_replace('.', ',', $booking_payment_detail->qitaf_request));
    }

    /**
     * Clear values from qitaf_logs temp table after booking confirmed, takes reservation_code as parameter. mobile apps are calling this api.
     * @param Request $request
     */
    public function clearQitafAfterBookingConfirmed(Request $request)
    {
        $booking = $this->page->getSingle('booking', array('reservation_code' => $request->reservation_code));
        $booking_payment = $this->page->getSingle('booking_payment', array('booking_id' => $booking->id));

        if ($booking && $booking_payment && $booking_payment->qitaf_request != "") {


            $ok_to_update = true;

            if (strtolower($booking->booking_source) == 'ios') { // if request is from IOS

                $booking_individual_payment_method = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking->id));
                $booking_cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking->id));

                // If payment is done from card and payment is completed and qitaf is used
                if ($booking_individual_payment_method->payment_method == 'Credit Card' && $booking_cc_payment->status == 'pending') {

                    $ok_to_update = false;

                }

            }

            if ($ok_to_update) {
                $this->page->updateData('qitaf_logs', ['status' => 'Copied', 'updated_from' => strtoupper($booking->booking_source) . ' mobile app'], array('qitaf_request' => str_replace('.', ',', $booking_payment->qitaf_request)));
            }
        }

        $response['status'] = 1;
        $response['message'] = "";
        echo json_encode($response);
        die();
    }

    private function qitaf_error($string)
    {
        if ($string !== strip_tags($string)) {
            return trans('labels.qitaf_input_not_fine');
        } else {
            return $string;
        }
    }

    /**
     * @param Request $request
     * this function is being used to generate checkout id needed for hyper pay first step "Create Checkout"
     */
    public function hp_generate_checkout_id(Request $request)
    {
        $hp_response = custom::hp_generate_checkout_id_api($request->booking_id, $request->isMada);
        if (isset($post_data['debug'])) {
            custom::dump($hp_response);
        }
        if (isset($hp_response['id'])) {
            $response['status'] = 1;
            $response['message'] = $hp_response['result']['description'];
            $response['checkout_id'] = $hp_response['id'];
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), ucfirst($hp_response['result']['description']));
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        }
    }

    /**
     * @param Request $request
     * this function is used when we pay through cc hyper pay and instantly we receive data on this function to validate payment
     */
    public function hp_check_payment_status(Request $request)
    {
        $site_settings = custom::site_settings();
        if ($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds > 0) {
            sleep($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds); // adding pause here to wait for the payment approval by HyperPay then check payment status
        }

        $hp_response = custom::hp_check_payment_status_api($request->resource_path, $request->isMada, $request->booking_id);

        $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";
        $pending_transaction_regex = "/^(000\.200)/";
        $manually_pending_transaction_regex = "/^(000\.400\.0[^3]|000\.400\.100)/";

        /* 1. if payment is successfully completed OR
           2. payment is pending and will be updated automatically later by hyper pay OR
           3. if payment is pending and needs manual approval by admin from hyper pay dashboard */
        if (
        preg_match($successful_transaction_regex, $hp_response['result']['code'])/* ||
            preg_match($pending_transaction_regex, $hp_response['result']['code']) ||
            preg_match($manually_pending_transaction_regex, $hp_response['result']['code'])*/
        ) {

            $booking = DB::table('booking')->where('reservation_code', $hp_response['merchantTransactionId'])->first();

            /* if payment is successfully done then update hyperpay details in db against booking id and sending confirmation to custom/admin/agent */
            /* Start */
            if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {
                $booking_cc_update['status'] = 'completed';
                $booking_cc_update['transaction_id'] = $hp_response['id'];
                $booking_cc_update['first_4_digits'] = isset($hp_response['card']) ? $hp_response['card']['bin'] : '';
                $booking_cc_update['last_4_digits'] = isset($hp_response['card']) ? $hp_response['card']['last4Digits'] : '';
                $booking_cc_update['card_brand'] = $hp_response['paymentBrand'];
                $booking_cc_update['payment_company'] = 'hyper_pay';
                $booking_cc_update['trans_date'] = date('Y-m-d H:i:s');

                $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking->id));

                if ($booking->type == 'corporate_customer') {
                    $details = $this->page->getSingle("booking_corporate_customer", array('booking_id' => $booking->id));
                    $user_details = $this->page->getSingle("corporate_customer", array('uid' => $details->uid));
                    $user_mobile_no = $user_details->primary_phone;
                    $user_email_address = $user_details->primary_email;
                } elseif ($booking->type == 'individual_customer') {
                    $details = $this->page->getSingle("booking_individual_user", array('booking_id' => $booking->id));
                    $user_details = $this->page->getSingle("individual_customer", array('uid' => $details->uid));
                    $user_mobile_no = $user_details->mobile_no;
                    $user_email_address = $user_details->email;
                } elseif ($booking->type == 'guest') {
                    $details = $this->page->getSingle("booking_individual_guest", array('booking_id' => $booking->id));
                    $user_details = $this->page->getSingle("individual_customer", array('id' => $details->individual_customer_id));
                    $user_mobile_no = $user_details->mobile_no;
                    $user_email_address = $user_details->email;
                }

                if ($booking->type == "corporate_customer") {
                    // $this->sendToKeyAdmin($booking->id);
                    // $this->sendToCorporateUser($booking->id);
                    // $this->sendToDriver($booking->id);
                    $this->sendCorporateBookingSms($booking->reservation_code, $user_mobile_no);
                } else {
                    // $this->sendEmailToUser($booking->id);
                    // $this->sendToKeyAdmin($booking->id);
                    $this->sendThankYouSMS($booking->reservation_code, $user_mobile_no);
                }

                // code to send email and sms to branch agent starts here
                if ($booking->from_location > 0) {
                    if ($booking->type == 'corporate_customer') {
                        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking->id);
                    } else {
                        $emailObj = $this->page->getSingleBookingDetails($booking->id);
                    }
                    if (isset($emailObj->icg_email) && $emailObj->icg_email != null) {
                        $first_name = $emailObj->icg_first_name;
                        $last_name = $emailObj->icg_last_name;
                    } else {
                        $first_name = $emailObj->first_name;
                        $last_name = $emailObj->last_name;
                    }

                    $customer_name = $first_name . ' ' . $last_name;
                    $branch_info = $this->page->getSingle('branch', array('id' => $booking->from_location));
                    if ($branch_info->email) {
                        // $this->send_email_to_branch_agent($booking->id, $branch_info->email);
                        $this->send_sms_to_branch_agent($booking->reservation_code, $branch_info->mobile, $customer_name);
                    }
                }

                if ($booking->type == "individual_customer" || $booking->type == "guest") {
                    $this->add_or_deduct_loyalty_points_for_customer($booking->id, 'deduct');
                }

                if ($_SERVER['HTTP_HOST'] == 'key.sa') {
                    $cronjob_url = custom::baseurl('/') . '/cronjob/setDataCronJob';
                    $curlResponse = $this->sendCurlRequest($cronjob_url);
                }
            }
            /* End */

            $response['status'] = 1;
            $response['message'] = $hp_response['result']['description'];
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), trans('labels.transaction_failed'));
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        }
    }

    public function booking_cancellation_reasons()
    {
        $cancellation_reasons = $this->page->getMultipleRows('setting_cancellation_reasons', array('is_active' => 1));
        $response['cancellation_reasons'] = $cancellation_reasons;
        echo json_encode($response);
        die();
    }

    public function get_countries()
    {
        $order_by = $this->lang == 'eng' ? 'eng_country' : 'arb_country';
        $countries = $this->page->getAll('country', $order_by);
        $response['countries'] = $countries;
        echo json_encode($response);
        die();
    }

    public function loyalty_programs()
    {
        $loyalty_programs = $this->page->getMultipleRows('setting_loyalty_programs', array('is_active' => 1), 'is_default', 'desc');
        $response['loyalty_programs'] = $loyalty_programs ?: [];
        echo json_encode($response);
        die();
    }

    public function edit_booking(Request $request)
    {

        $posted_data = $request->all(); // booking_id, pickup_date, pickup_time, type = android, ios

        // converting time to english in case of arabic language
        if (strpos($posted_data['pickup_time'], 'ص') == true || strpos($posted_data['pickup_time'], 'م') == true) {
            $posted_data['pickup_time'] = custom::convertEngTimeToArbTime($posted_data['pickup_time'], $posted_data['type']);
        }

        // Getting old booking detail
        $booking_detail = $this->page->getSingle('booking', array('id' => $posted_data['booking_id']));

        // calculating minutes difference between old pickup and dropoff date/time
        $old_pickup_date = new Carbon($booking_detail->from_date);
        $old_dropoff_date = new Carbon($booking_detail->to_date);
        $mins_diff_old = $old_dropoff_date->diffInMinutes($old_pickup_date);

        // setting up booking values to be updated calculated dropoff date/time (from minutes difference calculated above)
        $booking['from_date'] = date('Y-m-d H:i:s', strtotime($posted_data['pickup_date'] . ' ' . $posted_data['pickup_time']));
        $booking['to_date'] = date("Y-m-d H:i:s", strtotime('+' . $mins_diff_old . ' minutes', strtotime($posted_data['pickup_date'] . ' ' . $posted_data['pickup_time'])));
        $booking['is_edited'] = 1;
        $booking['to_be_notified'] = 'yes';
        $booking['updated_at'] = date('Y-m-d H:i:s');

        // checking all the validations here
        $this->check_if_booking_can_be_edited($booking_detail, $booking, $posted_data);

        // updating booking values with newly set values above
        $this->page->updateData('booking', $booking, array('id' => $posted_data['booking_id']));

        // creating record for booking edit history
        $booking_edit_history['booking_id'] = $posted_data['booking_id'];
        $booking_edit_history['old_from_date'] = $booking_detail->from_date;
        $booking_edit_history['old_to_date'] = $booking_detail->to_date;
        $booking_edit_history['new_from_date'] = $booking['from_date'];
        $booking_edit_history['new_to_date'] = $booking['to_date'];
        $booking_edit_history['type'] = $posted_data['type'];
        $booking_edit_history['created_at'] = date('Y-m-d H:i:s');
        $this->page->saveData('booking_edit_history', $booking_edit_history);

        // sending booking to OASIS
        $api_settings = custom::api_settings();
        if ($api_settings->edit_booking_sync_api_url != "") {
            // http://api2.keyrac.sa:8080/KeyBookingService/UpdateBetaBooking?booking_id=WJAT003055&applies_from=30-10-202009:00:00&applies_to=31-10-202009:00:00
            $oasis_api_url = rtrim($api_settings->edit_booking_sync_api_url, '/') . "?booking_id=" . $booking_detail->reservation_code . "&applies_from=" . date('d-m-YH:i:s', strtotime($booking['from_date'])) . "&applies_to=" . date('d-m-YH:i:s', strtotime($booking['to_date']));
            $api_response = $this->sendCurlRequest($oasis_api_url);
            // custom::dump($api_response);
        }

        // send confirmation email to customer and admin for edited booking here
        $this->sendEmailToUser($posted_data['booking_id']);
        $this->sendToKeyAdmin($posted_data['booking_id']);

        $response['status'] = 1;
        $response['message'] = trans('labels.booking_edited_successfully');
        $response['posted_data'] = $posted_data;
        echo json_encode($response);
        die();
    }

    private function check_if_booking_can_be_edited($booking_detail, $booking, $posted_data)
    {

        $error_msg = '';
        $pickup_dropoff_are_ahead_of_current_time = false;
        $no_of_hours_are_fine = false;
        $pickup_time_is_ok = false;
        $dropoff_time_is_ok = false;
        $delivery_slots_are_ok = true;

        // 1. checking if delivery slots are ok for delivery branch
        $pickup_branch = $booking_detail->from_location;
        $dropoff_branch = $booking_detail->to_location;
        $pickup_branch_info = $this->page->getSingle('branch', array('id' => $pickup_branch));
        $dropoff_branch_info = $this->page->getSingle('branch', array('id' => $dropoff_branch));
        if ($booking_detail->is_delivery_mode == 'yes' && $pickup_branch_info->capacity_mode == 'on') {
            $min_diff = $pickup_branch_info->hours_for_delivery * 60;
            $pickup_date_time = $booking['from_date'];
            $time = strtotime($pickup_date_time);
            $before_time = date("Y-m-d H:i:s", strtotime('-' . $min_diff . ' minutes', $time));
            $after_time = date("Y-m-d H:i:s", strtotime('+' . $min_diff . ' minutes', $time));
            $slot_capacity = $pickup_branch_info->capacity;
            $countOfBookingsInTheTimeSlot = $this->page->getCountOfBookingsInTimeInterval($pickup_branch, $before_time, $after_time);
            if ($countOfBookingsInTheTimeSlot >= $slot_capacity) {
                $delivery_slots_are_ok = false;
            }
        }

        // 2. To check if delivery time is fine as per database allowed time OR To check booking time is fine as per database allowed time
        if ($booking_detail->is_delivery_mode == 'yes') {
            $today = Carbon::now();
            $date_picked_up = new Carbon($booking['from_date']);
            $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
            $no_of_secs_from_db = (int)$pickup_branch_info->hours_before_delivery * 60 * 60;
            if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                $no_of_hours_are_fine = true;
            }
        } elseif ($booking_detail->is_delivery_mode == 'subscription') {
            $today = Carbon::now();
            $date_picked_up = new Carbon($booking['from_date']);
            $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
            $no_of_secs_from_db = (int)$pickup_branch_info->reservation_before_hours_for_subscription * 60 * 60;
            if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                $no_of_hours_are_fine = true;
            }
        } else {
            $today = Carbon::now();
            $date_picked_up = new Carbon($booking['from_date']);
            $no_of_mins_to_pickup_from_today = $today->diffInSeconds($date_picked_up);
            $no_of_secs_from_db = (int)$pickup_branch_info->reservation_before_hours * 60 * 60;
            if ((int)$no_of_mins_to_pickup_from_today > $no_of_secs_from_db) {
                $no_of_hours_are_fine = true;
            }
        }

        // 3. To check if pickup and dropoff time ahead of current time
        $today = Carbon::now();
        $date_picked_up = new Carbon($booking['from_date']);
        $date_dropped_off = new Carbon($booking['to_date']);
        if (($date_picked_up > $today) && ($date_dropped_off > $today)) {
            $pickup_dropoff_are_ahead_of_current_time = true;
        }

        // 4. Code for branch open / closed logic
        $pickup_day = date('l', strtotime($booking['from_date']));
        $pickup_time = date('H:i', strtotime($booking['from_date']));
        $drpoff_day = date('l', strtotime($booking['to_date']));
        $drpoff_time = date('H:i', strtotime($booking['to_date']));
        $pickup_schedule_is_ok = $this->page->checkIfBranchIsOpen($booking_detail->from_location, $pickup_day, $pickup_time, $booking['from_date'], ($booking_detail->is_delivery_mode == 'yes'));
        $dropoff_schedule_is_ok = $this->page->checkIfBranchIsOpen($booking_detail->to_location, $drpoff_day, $drpoff_time, $booking['to_date'], ($booking_detail->is_delivery_mode == 'yes'));
        if ($pickup_schedule_is_ok) {
            $pickup_time_is_ok = true;
        }
        if ($dropoff_schedule_is_ok) {
            $dropoff_time_is_ok = true;
        }

        // 5. checking if hourly mode ok
        $site_settings = custom::site_settings();
        $date_picked_up_for_hours_cal = new Carbon($booking['from_date']);
        $date_dropped_off_for_hours_cal = new Carbon($booking['to_date']);
        $hours_diff = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

        // $pickup_dropoff_are_ahead_of_current_time = false;

        if ($pickup_dropoff_are_ahead_of_current_time == false) {
            $error_msg = ($this->lang == 'eng' ? 'Pickup/Dropoff time should be ahead of current date/time.' : 'وقت أو تاريخ الإستلام / التسليم يجب ان يكون بعد الوقت / التاريخ الحالي');
        } elseif ($no_of_hours_are_fine == false) {
            if ($booking_detail->is_delivery_mode == 'yes') {
                $days_allowed = ((int)$pickup_branch_info->hours_before_delivery > 0 ? (int)$pickup_branch_info->hours_before_delivery : '0');
            } elseif ($booking_detail->is_delivery_mode == 'subscription') {
                $days_allowed = ((int)$pickup_branch_info->reservation_before_hours_for_subscription > 0 ? (int)$pickup_branch_info->reservation_before_hours_for_subscription : '0');
            } else {
                $days_allowed = ((int)$pickup_branch_info->reservation_before_hours > 0 ? (int)$pickup_branch_info->reservation_before_hours : '0');
            }
            $error_msg = ($this->lang == 'eng' ? 'Pickup time must be ' . $days_allowed . ' hour(s) ahead.' : ' وقت/تاريخ الإستلام يجب ان يكون بعد ' . $days_allowed . ' ساعة من الوقت الحالي ');
        } elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == true) {
            $opening_hours = $pickup_branch_info->opening_hours;
            $error_msg = ($this->lang == 'eng' ? 'The pickup branch is close at the selected pickup time. \n Working Hours: ' . $opening_hours : 'فرع الاستلام غير متوفر في الوقت المختار ساعات العمل: ' . $opening_hours);
        } elseif ($pickup_time_is_ok == true && $dropoff_time_is_ok == false) {
            $opening_hours = $dropoff_branch_info->opening_hours;
            $error_msg = ($this->lang == 'eng' ?
                'The dropoff branch is close at the selected dropoff time. \n Working Hours: ' . $opening_hours : 'فرع التسليم غير متوفر في الوقت المختار ساعات العمل: ' . $opening_hours);
        } elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == false) {
            $error_msg = ($this->lang == 'eng' ?
                'The pickup and dropoff branches are closed at the selected pickup and dropoff time. \n Pickup Branch Working Hours: ' . $pickup_branch_info->opening_hours . ' \n Dropoff Branch Working Hours: ' . $dropoff_branch_info->opening_hours :
                'فرع الاستلام و التسليم مغلق في الوقت المختار (ساعات العمل)');
        } elseif ($delivery_slots_are_ok == false) {
            $error_msg = ($this->lang == 'eng' ? $pickup_branch_info->eng_capacity_message : $pickup_branch_info->arb_capacity_message);
        } elseif (!custom::is_booking_editable($booking_detail->id)) {
            $error_msg = "Booking can't be edited now.";
        }

        if ($error_msg != '') {
            $response['status'] = 0;
            $response['message'] = $error_msg;
            $response['posted_data'] = $posted_data;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }

    }

    public function check_is_edit_allowed_in_booking(Request $request)
    {
        $response['status'] = 1;
        $response['message'] = "";
        $response['is_edit_allowed'] = custom::is_booking_editable($request->booking_id, true);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function get_niqaty_redeem_options(Request $request)
    {
        $posted_data = $request->all(); // mobile
        unset($posted_data['k']);

        $RedeemOptionsResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemOptions?mobile=" . $posted_data['mobile']);
        $RedeemOptionsResponse = rtrim($RedeemOptionsResponse, "\r\n");
        if (strtoupper($RedeemOptionsResponse) == 'Y') {
            $soapclient = new SoapClient("http://api.keyrac.sa:8080/NeqatyRedeemAPI/RedeemAPI?WSDL", ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
            $xml = simplexml_load_string('
            <getRedeemOption>
                <customerMobile>' . $posted_data['mobile'] . '</customerMobile>
            </getRedeemOption>
        ');
            $soap_response = $soapclient->getRedeemOption($xml);
            if (isset($soap_response->return->returnCode) && isset($soap_response->return->returnResult) && $soap_response->return->returnCode == 0) {
                $niqaty_redeem_options = [];
                $i = 0;
                if (is_array($soap_response->return->returnResult)) {
                    foreach ($soap_response->return->returnResult as $item) {
                        $niqaty_redeem_options[$i]['amount'] = $item->redeemAmount;
                        $niqaty_redeem_options[$i]['code'] = $item->redeemCode;
                        $niqaty_redeem_options[$i]['points'] = $item->redeemPoints;
                        $niqaty_redeem_options[$i]['token'] = $item->token;
                        $niqaty_redeem_options[$i]['mobile'] = $posted_data['mobile'];
                        $i++;
                    }
                } else {
                    $niqaty_redeem_options[$i]['amount'] = $soap_response->return->returnResult->redeemAmount;
                    $niqaty_redeem_options[$i]['code'] = $soap_response->return->returnResult->redeemCode;
                    $niqaty_redeem_options[$i]['points'] = $soap_response->return->returnResult->redeemPoints;
                    $niqaty_redeem_options[$i]['token'] = $soap_response->return->returnResult->token;
                    $niqaty_redeem_options[$i]['mobile'] = $posted_data['mobile'];
                }

                $sort_order = array();
                $i = 0;
                foreach ($niqaty_redeem_options as $niqaty_redeem_option) {
                    $sort_order[$i] = $niqaty_redeem_option['amount'];
                    $i++;
                }
                array_multisort($sort_order, SORT_ASC, $niqaty_redeem_options);

                $response['status'] = 1;
                $response['message'] = "";
                $response['data'] = ['niqaty_redeem_options' => $niqaty_redeem_options];
                echo json_encode($response);
                die();
            } else {
                $response['status'] = 0;
                $response['message'] = (isset($soap_response->return->returnMessage) ? $soap_response->return->returnMessage : trans('labels.niqaty_input_not_fine'));
                echo json_encode($response);
                die();
            }
        } else {
            $response['status'] = 0;
            $response['message'] = $RedeemOptionsResponse;
            echo json_encode($response);
            die();
        }
    }

    public function authorize_niqaty_redeem_request(Request $request)
    {
        $posted_data = $request->all(); // mobile, token, points, code, amount, total_payable
        unset($posted_data['k']);

        $this->checkIfNiqatyRedeemable($posted_data['amount'], $posted_data['total_payable']);

        $data['mobile'] = $posted_data['mobile'];
        $data['token'] = $posted_data['token'];
        $data['points'] = $posted_data['points'];
        $data['code'] = $posted_data['code'];
        $data['amount'] = $posted_data['amount'];

        $RedeemAuthorizeResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemAuthorize?" . http_build_query($data));
        $RedeemAuthorizeResponse = rtrim($RedeemAuthorizeResponse, "\r\n");

        if (stripos($RedeemAuthorizeResponse, 'Error') === false && stripos(strip_tags($RedeemAuthorizeResponse), 'Origin Down') === false && stripos(strip_tags($RedeemAuthorizeResponse), 'Origin Connection Time-out') === false && stripos($RedeemAuthorizeResponse, '-') === false) {
            $data['transaction_reference'] = str_replace(' ', '+', $RedeemAuthorizeResponse);
            $response['status'] = 1;
            $response['message'] = $RedeemAuthorizeResponse;
            $response['niqaty_data'] = $data;
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = $RedeemAuthorizeResponse;
            echo json_encode($response);
            die();
        }
    }

    public function confirm_niqaty_redeem_request(Request $request)
    {
        $posted_data = $request->all(); // otp, mobile, transaction_reference, token, points, code, amount, total_payable
        unset($posted_data['k']);

        $data['otp'] = $posted_data['otp'];
        $data['mobile'] = $posted_data['mobile'];
        $data['transactionReference'] = $posted_data['transaction_reference'];

        $total_payable = $posted_data['total_payable'];

        unset($posted_data['otp'], $posted_data['total_payable']);

        $RedeemConfirmResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemConfirm?" . http_build_query($data));
        $RedeemConfirmResponse = rtrim($RedeemConfirmResponse, "\r\n");

        if (strtoupper($RedeemConfirmResponse) == 'Y') {

            $niqaty_request = http_build_query($posted_data);

            $niqaty_request = htmlspecialchars_decode($niqaty_request);

            $this->page->saveData('niqaty_logs', array('status' => 'New', 'niqaty_request' => htmlspecialchars_decode($niqaty_request), 'created_at' => date('Y-m-d H:i:s')));

            $payable_amount_arr = $this->checkIfNiqatyRedeemable($posted_data['amount'], $total_payable);
            $payable_amount = $payable_amount_arr['total_payable_after_niqaty'];
            $vat_applicable = $payable_amount_arr['vat_after_niqaty'];

            $response['status'] = 1;
            $response['message'] = $RedeemConfirmResponse;
            $response['niqaty_request'] = base64_encode(htmlspecialchars_decode($niqaty_request));
            $response['niqaty_amount'] = $posted_data['amount'];
            $response['amount_remaining'] = $payable_amount > 0;

            $response['total_payable_amount_after_niqaty'] = round($payable_amount, 2);
            $response['vat_after_niqaty'] = round($vat_applicable, 2);

            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.niqaty_partial_redeem_text_to_show'), $posted_data['amount'], round($payable_amount, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.niqaty_full_redeem_text_to_show'), $posted_data['amount']);
            }
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = $RedeemConfirmResponse;
            echo json_encode($response);
            die();
        }
    }

    public function clear_niqaty_after_booking_confirmed(Request $request)
    {
        $booking = $this->page->getSingle('booking', array('reservation_code' => $request->reservation_code));
        $booking_payment = $this->page->getSingle('booking_payment', array('booking_id' => $booking->id));

        if ($booking && $booking_payment && $booking_payment->niqaty_request != "") {


            $ok_to_update = true;

            if (strtolower($booking->booking_source) == 'ios') { // if request is from IOS

                $booking_individual_payment_method = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking->id));
                $booking_cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking->id));

                // If payment is done from card and payment is completed and qitaf is used
                if ($booking_individual_payment_method->payment_method == 'Credit Card' && $booking_cc_payment->status == 'pending') {

                    $ok_to_update = false;

                }

            }

            if ($ok_to_update) {
                $this->page->updateData('niqaty_logs', ['status' => 'Copied', 'updated_from' => strtoupper($booking->booking_source) . ' mobile app'], array('niqaty_request' => htmlspecialchars_decode($booking_payment->niqaty_request)));
            }
        }

        $response['status'] = 1;
        $response['message'] = "";
        echo json_encode($response);
        die();
    }

    private function checkIfNiqatyRedeemable($niqaty_amount, $total_amount_with_vat)
    {
        $site_settings = custom::site_settings();
        $max_amount_redeemable_with_niqaty = round(($site_settings->amount_to_be_redeemed_by_niqaty_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($niqaty_amount > $max_amount_redeemable_with_niqaty) {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_niqaty);
            echo json_encode($response);
            die();
        }

        return array('total_payable_after_niqaty' => $total_amount_with_vat - $niqaty_amount, 'vat_after_niqaty' => 0);
    }

    private function autoReverseNiqatyRedeem($booking_id)
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        $niqaty_reverse_redeem_api_response = $this->callNiqatyReverseRedeemAPI($booking_payment_detail->niqaty_request);
        if ($niqaty_reverse_redeem_api_response) {
            $niqaty_request_after_refund = $booking_payment_detail->niqaty_request . '&refundTransactionReference=' . $niqaty_reverse_redeem_api_response;
            $this->page->updateData('booking_payment', ['niqaty_request' => $niqaty_request_after_refund, 'status' => 'Reversed'], ['booking_id' => $booking_id]);
        }
    }

    private function callNiqatyReverseRedeemAPI($niqaty_request)
    {
        if ($niqaty_request != "") {
            parse_str($niqaty_request, $niqaty_redeem_refund_request_data);
            $api_url = 'http://api2.keyrac.sa:8080/NeqatyService/RedeemRefundAuthorize?mobile=' . $niqaty_redeem_refund_request_data['mobile'] . '&transactionReference=' . str_replace(' ', '%20', $niqaty_redeem_refund_request_data['transaction_reference']) . '&code=' . $niqaty_redeem_refund_request_data['code'] . '&points=' . $niqaty_redeem_refund_request_data['points'] . '&amount=' . $niqaty_redeem_refund_request_data['amount'];
            $RedeemRefundAuthorizeResponse = $this->sendCurlRequest($api_url);
            $RedeemRefundAuthorizeResponse = rtrim($RedeemRefundAuthorizeResponse, "\r\n");
            $this->logNiqaty("RedeemRefundAuthorizeResponse=" . $RedeemRefundAuthorizeResponse);

            if (stripos($RedeemRefundAuthorizeResponse, 'Error') === false && stripos(strip_tags($RedeemRefundAuthorizeResponse), 'Origin Down') === false && stripos(strip_tags($RedeemRefundAuthorizeResponse), 'Origin Connection Time-out') === false && stripos($RedeemRefundAuthorizeResponse, '-') === false) {
                $api_url = 'http://api2.keyrac.sa:8080/NeqatyService/RedeemRefundConfirm?mobile=' . $niqaty_redeem_refund_request_data['mobile'] . '&transactionReference=' . str_replace(' ', '%20', $RedeemRefundAuthorizeResponse);
                $RedeemRefundConfirmResponse = $this->sendCurlRequest($api_url);
                if (stripos($RedeemRefundConfirmResponse, 'Error') === false && stripos(strip_tags($RedeemRefundConfirmResponse), 'Origin Down') === false && stripos(strip_tags($RedeemRefundConfirmResponse), 'Origin Connection Time-out') === false && stripos($RedeemRefundConfirmResponse, '-') === false) {
                    return str_replace(' ', '+', $RedeemRefundAuthorizeResponse);
                }
            }
        }
        return false;
    }

    private function logNiqaty($txt)
    {
        // file_put_contents('niqaty.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function fetch_nearest_delivery_branch(Request $request)
    {
        $current_latitude = $request->current_latitude;
        $current_longitude = $request->current_longitude;

        $branch = $this->page->get_nearest_branch($current_latitude, $current_longitude);

        if ($branch) {
            $response['status'] = 1;
            $response['branch'] = $branch;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            $response['status'] = 0;
            $response['message'] = "No branches found!";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }

    }

    public function mark_account_as_deleted(Request $request)
    {
        $individual_customer = $this->page->getSingle('individual_customer', ['id_no' => $request->id_number]);
        if ($individual_customer && $individual_customer->uid > 0) {
            $this->page->updateData('users', ['active_status' => 'inactive'], ['id' => $individual_customer->uid]);
            $response['status'] = 1;
            $response['message'] = 'Your account deleted successfully at KEY.';
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            $response['status'] = 0;
            $response['message'] = "Something went wrong.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }

    }

    public function add_payment_for_booking(Request $request)
    {
        try {
            $site_settings = custom::site_settings();
            $booking = DB::table('booking')
                ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
                ->where('id', $request->booking_id)
                ->select('booking.*', 'booking_payment.*')
                ->first();
            if ($booking) {

                // $reservation_code = 'WJMB583503'; // hardcoded just for testing
                $reservation_code = $booking->reservation_code;

                $api_settings = custom::api_settings();
                if (custom::is_oasis_api_enabled()) {
                    $soapclient = new SoapClient($api_settings->oasis_api_url . '?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
                    $xml = simplexml_load_string('<getContractBalance><getContractBalance>' . $reservation_code . '</getContractBalance></getContractBalance>');
                    $soap_response = $soapclient->getContractBalance($xml);
                }

                /*if ($request->has('testing')) {
                    $soap_response->return = "100"; // just for testing
                }*/

                if (isset($soap_response) && strpos($soap_response->return, 'Error') === false) {
                    $balance_amount = $soap_response->return;

                    $amount_per_day = $booking->rent_price + $booking->cdw_price + $booking->cdw_plus_price + $booking->gps_price + $booking->extra_driver_price + $booking->baby_seat_price;
                    $vat = ($site_settings->vat_percentage / 100) * $amount_per_day;
                    $total_amount_per_day = round($amount_per_day + $vat, 2);

                    $response['status'] = 1;
                    $response['message'] = '';
                    $response['data'] = ['balance_amount' => $balance_amount, 'total_amount_per_day' => (string)$total_amount_per_day, 'booking_id' => (string)$booking->id, 'reservation_code' => $booking->reservation_code, 'show_coupon_option' => 1];
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                } else {
                    $this->send_debug_email('Add Payment: Response error in getContractBalance OASIS API', $soap_response->return);
                    $response['status'] = 0;
                    $response['message'] = "Something went wrong.";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }

            $response['status'] = 0;
            $response['message'] = "Something went wrong.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } catch (Exception $e) {
            $this->send_debug_email('Add Payment: Catch error in add_payment_for_booking function for mobile API', $e->getMessage());
            $response['status'] = 0;
            $response['message'] = "Something went wrong.";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    public function hp_generate_checkout_id_for_add_payment(Request $request)
    { // booking_id, isMada, amount, number_of_days
        $amount = str_replace(',', '', number_format($request->amount, 2));
        $booking = $this->page->getSingle('booking', ['id' => $request->booking_id]);
        $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();
        $hp_response = custom::hp_generate_checkout_id_api($request->booking_id, $request->isMada, 'E' . ($booking_added_payments + 1), $amount, $request->number_of_days);
        if (isset($post_data['debug'])) {
            custom::dump($hp_response);
        }
        if (isset($hp_response['id'])) {
            $response['status'] = 1;
            $response['message'] = $hp_response['result']['description'];
            $response['checkout_id'] = $hp_response['id'];
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        } else {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), ucfirst($hp_response['result']['description']));
            $response['hp_response'] = $hp_response;
            echo json_encode($response);
            die();
        }
    }

    public function hp_check_payment_status_for_add_payment(Request $request)
    { // resource_path, booking_id, isMada, number_of_days, amount
        $site_settings = custom::site_settings();
        if ($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds > 0) {
            sleep($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds);
        }

        $booking = $this->page->getSingle('booking', ['id' => $request->booking_id]);
        $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();
        $hp_response = custom::hp_check_payment_status_api($request->resource_path, $request->isMada, $request->booking_id, 'E' . ($booking_added_payments + 1));

        $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";

        $pending_transaction_regex = "/^(000\.200)/";
        $manually_pending_transaction_regex = "/^(000\.400\.0[^3]|000\.400\.100)/";

        if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {
            $booking = $this->page->getSingle('booking', ['id' => $request->booking_id]);

            $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

            $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
            $booking_add_payment['extended_days'] = $request->number_of_days;
            $booking_add_payment['payment_company'] = 'HP';
            $booking_add_payment['payment_method'] = $this->hp_payment_method($hp_response['paymentBrand']);
            $booking_add_payment['transaction_reference'] = $hp_response['id'];
            $booking_add_payment['card_number'] = (isset($hp_response['card']) ? $hp_response['card']['bin'] : '') . '********' . (isset($hp_response['card']) ? $hp_response['card']['last4Digits'] : '');
            $booking_add_payment['amount'] = $request->amount;
            $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
            $booking_add_payment['payment_source'] = ucfirst($request->booking_source);
            $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
            $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
            $id = $this->page->saveData('booking_added_payments', $booking_add_payment);

            // sending confirmation SMS and email to customer
            $this->send_add_payment_confirmation_to_customer($id);

            // syncing added payment data with OASIS
            $this->sync_booking_added_payments_with_oasis($id);

            $booking = $this->page->getSingle('booking', ['id' => $request->booking_id]);

            $response['status'] = 1;
            $response['message'] = 'Transaction completed!';
            $response['data'] = ['amount' => $booking_add_payment['amount'], 'reservation_code' => $booking->reservation_code];
            echo json_encode($response);
            die();
        } elseif (
            preg_match($pending_transaction_regex, $hp_response['result']['code']) ||
            preg_match($manually_pending_transaction_regex, $hp_response['result']['code'])
        ) {
            $response['status'] = 0;
            $response['message'] = trans('labels.hyper_pay_pending_payment_error');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } else {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), trans('labels.transaction_failed'));
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }

    private function hp_payment_method($payment_brand)
    {
        $p_methods = [
            'VISA' => 'HP_Visa',
            'Visa' => 'HP_Visa',
            'Master Card' => 'HP_MC',
            'MasterCard' => 'HP_MC',
            'Master' => 'HP_MC',
            'MASTER' => 'HP_MC',
            'MADA' => 'HP_Mada',
            'Mada' => 'HP_Mada',
            'STC_PAY' => 'HP_STCP',
            'AMEX' => 'HP_Amex',
            'APPLE_PAY' => 'HP_Apple',
        ];

        return isset($p_methods[$payment_brand]) ? $p_methods[$payment_brand] : 'N/A';
    }

    private function sync_booking_added_payments_with_oasis($id)
    {
        $api_settings = custom::api_settings();
        $booking_added_payment = $this->page->getSingle('booking_added_payments', ['id' => $id]);

        // $reservation_code = 'WJMB583503'; // hardcoded just for testing
        $reservation_code = $booking_added_payment->booking_reservation_code;

        if (custom::is_oasis_api_enabled()) {
            $soapclient = new SoapClient($api_settings->oasis_api_url . '?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
            $xml = simplexml_load_string('<addContractPayment><addContractPayment><accountCardNo>' . $booking_added_payment->card_number . '</accountCardNo><amount>' . $booking_added_payment->amount . '</amount><bookingId>' . $reservation_code . '</bookingId><extensionDays>' . $booking_added_payment->extended_days . '</extensionDays><paymentMethod>' . $booking_added_payment->payment_method . '</paymentMethod><transReference>' . $booking_added_payment->transaction_reference . '</transReference></addContractPayment></addContractPayment>
');
            $response = $soapclient->addContractPayment($xml);
        }

        if (isset($response) && isset($response->return) && (strtolower($response->return) == 'success' || strtolower($response->return) == 'error: this transaction reference is already added before.')) {
            $this->page->updateData('booking_added_payments', ['sync_status' => 'A', 'synced_at' => date('Y-m-d H:i:s')], ['id' => $booking_added_payment->id]);
        }

    }

    private function send_add_payment_confirmation_to_customer($id)
    {
        $booking_added_payment = $this->page->getSingle('booking_added_payments', ['id' => $id]);
        $booking = $this->page->getSingle('booking', ['reservation_code' => $booking_added_payment->booking_reservation_code]);
        if ($booking->type == 'corporate_customer') {
            $details = $this->page->getSingle("booking_corporate_customer", array('booking_id' => $booking->id));
            $user_details = $this->page->getSingle("corporate_customer", array('uid' => $details->uid));
            $customer_name = $user_details->primary_name;
            $customer_email = $user_details->primary_email;
            $customer_mobile_no = $user_details->primary_phone;
        } elseif ($booking->type == 'individual_customer') {
            $details = $this->page->getSingle("booking_individual_user", array('booking_id' => $booking->id));
            $user_details = $this->page->getSingle("individual_customer", array('uid' => $details->uid));
            $customer_name = $user_details->first_name;
            $customer_email = $user_details->email;
            $customer_mobile_no = $user_details->mobile_no;
        } elseif ($booking->type == 'guest') {
            $details = $this->page->getSingle("booking_individual_guest", array('booking_id' => $booking->id));
            $user_details = $this->page->getSingle("individual_customer", array('id' => $details->individual_customer_id));
            $customer_name = $user_details->first_name;
            $customer_email = $user_details->email;
            $customer_mobile_no = $user_details->mobile_no;
        }

        if (isset($customer_email) && $customer_email != '') {
            $site = custom::site_settings();
            $smtp = custom::smtp_settings();

            $message_text_for_email = 'Your payment of <strong>' . $booking_added_payment->amount . ' SAR</strong> is received against your booking <strong>' . $booking_added_payment->booking_reservation_code . '</strong> with transaction reference number <strong>' . $booking_added_payment->payment_booking_id . '</strong>';

            $email['subject'] = 'Payment Added Against Booking ' . $booking_added_payment->booking_reservation_code;
            $email['fromEmail'] = $smtp->username;
            $email['fromName'] = 'no-reply';
            $email['toEmail'] = $customer_email;
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';
            $email['attachment'] = '';
            $content['contact_no'] = $site->site_phone;
            $content['lang_base_url'] = $this->lang_base_url;
            $content['name'] = $customer_name;
            $content['msg'] = $message_text_for_email;
            $content['gender'] = 'male';
            custom::sendEmail('general', $content, $email, $this->lang);
        }

        if (isset($customer_mobile_no) && $customer_mobile_no != '') {
            $message_text_for_mobile = 'Dear ' . $customer_name . ', Your payment of ' . $booking_added_payment->amount . ' SAR is received against your booking ' . $booking_added_payment->booking_reservation_code . ' with transaction reference number ' . $booking_added_payment->payment_booking_id;
            custom::sendSMS($customer_mobile_no, $message_text_for_mobile, $this->lang);
        }
    }

    public function get_promo_code_for_app_popup(Request $request)
    {
        $site_settings = custom::site_settings();
        if ($site_settings->show_promo_code_popup_in_apps == 'yes') {

            $app_popup_promo_codes_list = DB::table('app_popup_promo_codes_list')->where('seen_by', $request->device_id)->first();

            // If this device hasn't generated any codes yet (is hitting it for the first time)
            if (!$app_popup_promo_codes_list) {
                $active_app_popup_promo_codes = DB::table('app_popup_promo_codes')->where('status', 1)->pluck('id');
                $available_codes = DB::table('app_popup_promo_codes_list')->where('is_used', 0)->whereIn('parent_id', $active_app_popup_promo_codes)->where('seen_by', '')->count();
                if ($available_codes > 0) {
                    $random_row = DB::table('app_popup_promo_codes_list')->where('is_used', 0)->whereIn('parent_id', $active_app_popup_promo_codes)->where('seen_by', '')->inRandomOrder()->first();

                    // marking that this code is seen by this device ID
                    DB::table('app_popup_promo_codes_list')->where('id', $random_row->id)->update(['seen_by' => $request->device_id, 'seen_at' => date('Y-m-d H:i:s')]);

                    $parent = DB::table('app_popup_promo_codes')->where('id', $random_row->parent_id)->first();
                    $title = ($request->lang == 'en' ? $parent->eng_title : $parent->arb_title);
                    $sub_title = ($request->lang == 'en' ? $parent->eng_sub_title : $parent->arb_sub_title);
                    $description = ($request->lang == 'en' ? $parent->eng_description : $parent->arb_description);
                    echo json_encode(['status' => 1, 'message' => '', 'title' => $title, 'sub_title' => $sub_title, 'description' => $description, 'code' => $random_row->promo_code]);
                    die;
                }
            }

            // if this device has already generated a code against its device ID but it hasn't used this code yet
            if ($app_popup_promo_codes_list && $app_popup_promo_codes_list->is_used == 0) {
                $app_popup_promo_code = DB::table('app_popup_promo_codes')->where('id', $app_popup_promo_codes_list->parent_id)->first();
                $title = ($request->lang == 'en' ? $app_popup_promo_code->eng_title : $app_popup_promo_code->arb_title);
                $sub_title = ($request->lang == 'en' ? $app_popup_promo_code->eng_sub_title : $app_popup_promo_code->arb_sub_title);
                $description = ($request->lang == 'en' ? $app_popup_promo_code->eng_description : $app_popup_promo_code->arb_description);
                echo json_encode(['status' => 1, 'message' => '', 'title' => $title, 'sub_title' => $sub_title, 'description' => $description, 'code' => $app_popup_promo_codes_list->promo_code]);
                die;
            }

            // if this device ID has already used a code
            if ($app_popup_promo_codes_list && $app_popup_promo_codes_list->is_used == 1) {
                $app_popup_promo_code = DB::table('app_popup_promo_codes')->where('id', $app_popup_promo_codes_list->parent_id)->first();
                echo json_encode(['status' => 0, 'message' => ($request->lang == 'en' ? $app_popup_promo_code->eng_error_message : $app_popup_promo_code->arb_error_message)]);
                die;
            }

        }

        echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'code' => '']);
        die;
    }

    private function send_debug_email($subject, $message)
    {
        $email['subject'] = $subject;
        $email['fromEmail'] = 'admin@key.sa';
        $email['fromName'] = 'no-reply';
        if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa' || $_SERVER['SERVER_NAME'] == 'awfar.sa' || $_SERVER['SERVER_NAME'] == 'www.awfar.sa') {
            $email['toEmail'] = 'bilal_ejaz@astutesol.com';
        } else {
            $email['toEmail'] = 'bilal_ejaz@astutesol.com';
            //$email['ccEmail'] = 'kholoud.j@edesign.com.sa';
        }
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $content['contact_no'] = '0321';
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = 'Admin';
        $content['msg'] = $message;
        $content['gender'] = 'male';
        custom::sendEmail2('general', $content, $email, 'eng');
    }

    public function mokafaa_get_access_token(Request $request)
    {
        $api_settings = custom::api_settings();
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'GetAccessToken');

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => 1, 'message' => "", 'data' => ['access_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function mokafaa_send_otp(Request $request) // access_token (received in mokafaa_get_access_token), mobile_number
    {
        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->mokafaa_api_base_url, 'test') === false ? $request->mobile_number : '966002072675');
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'IssueOtp?mobile=' . $mobile_number . '&accessToken=' . $request->access_token);

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => 1, 'message' => "", 'data' => ['otp_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function mokafaa_initiate_redeem_request(Request $request) // access_token (received in mokafaa_get_access_token), mobile_number, otp_token (received in mokafaa_send_otp), mokafaa_amount, otp_code, total_payable
    {
        $payable_amount_arr = $this->mokafaa_check_if_redeemable($request->mokafaa_amount, $request->total_payable);

        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->mokafaa_api_base_url, 'test') === false ? $request->mobile_number : '966002072675');
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'RedeemAmount?mobile=' . $mobile_number . '&amount=' . $request->mokafaa_amount . '&otp=' . $request->otp_code . '&otpToken=' . $request->otp_token . '&accessToken=' . $request->access_token);

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {

            $payable_amount = $payable_amount_arr['total_payable_after_mokafaa'];
            $vat_applicable = $payable_amount_arr['vat_after_mokafaa'];


            $this->page->saveData('mokafaa_logs', array('status' => 'New', 'mokafaa_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            $response['status'] = 1;
            $response['message'] = "";
            $response['mokafaa_transaction_id'] = $curlResponse;
            $response['mokafaa_amount'] = $request->mokafaa_amount;
            $response['amount_remaining'] = $payable_amount > 0;
            $response['total_payable_amount_after_mokafaa'] = round($payable_amount, 2);
            $response['vat_after_mokafaa'] = round($vat_applicable, 2);
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.mokafaa_partial_redeem_text_to_show'), $request->mokafaa_amount, round($payable_amount, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.mokafaa_full_redeem_text_to_show'), $request->mokafaa_amount);
            }
            echo json_encode($response);
            die();
        } else {
            echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later')]);
            die();
        }
    }

    private function mokafaa_check_if_redeemable($mokafaa_amount, $total_amount_with_vat)
    {
        $site_settings = custom::site_settings();
        $max_amount_redeemable_with_mokafaa = round(($site_settings->amount_to_be_redeemed_by_mokafaa_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($mokafaa_amount > $max_amount_redeemable_with_mokafaa) {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_mokafaa);
            echo json_encode($response);
            die();
        }

        return array('total_payable_after_mokafaa' => $total_amount_with_vat - $mokafaa_amount, 'vat_after_mokafaa' => 0);
    }

    public function mokafaa_clear_after_booking_confirmed(Request $request)
    {
        $booking = $this->page->getSingle('booking', array('reservation_code' => $request->reservation_code));
        $booking_payment = $this->page->getSingle('booking_payment', array('booking_id' => $booking->id));

        if ($booking && $booking_payment && $booking_payment->mokafaa_request != "") {


            $ok_to_update = true;

            if (strtolower($booking->booking_source) == 'ios') { // if request is from IOS

                $booking_individual_payment_method = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking->id));
                $booking_cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking->id));

                // If payment is done from card and payment is completed and Mokafaa is used
                if ($booking_individual_payment_method->payment_method == 'Credit Card' && $booking_cc_payment->status == 'pending') {

                    $ok_to_update = false;

                }

            }

            if ($ok_to_update) {
                $this->page->updateData('mokafaa_logs', ['status' => 'Copied', 'updated_from' => strtoupper($booking->booking_source) . ' mobile app'], array('mokafaa_request' => $booking_payment->mokafaa_request));
            }
        }

        $response['status'] = 1;
        $response['message'] = "";
        echo json_encode($response);
        die();
    }

    public function anb_get_access_token(Request $request)
    {
        $api_settings = custom::api_settings();
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'AuthorizeToken');

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => 1, 'message' => "", 'data' => ['access_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function anb_send_otp(Request $request) // access_token (received in anb_get_access_token), mobile_number, pickup_branch_id
    {
        $api_settings = custom::api_settings();
        $branch_info = $this->page->getSingle('branch', array('id' => $request->pickup_branch_id));
        $mobile_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $request->mobile_number : '966504652456');
        $oracle_reference_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $branch_info->oracle_reference_number : 'RR5001');
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'AuthorizeOtp?mobile=' . $mobile_number . '&branch=' . $oracle_reference_number . '&access_token=' . $request->access_token);

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false) {
            echo json_encode(['status' => 1, 'message' => "", 'data' => ['otp_token' => $curlResponse]]);
            die();
        } else {
            if (stripos($curlResponse, 'Last generated OTP is still valid') !== false) {
                echo json_encode(['status' => 1, 'message' => trans('labels.last_sent_otp_is_still_valid'), 'data' => []]);
                die();
            } else {
                echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
                die();
            }
        }
    }

    public function anb_initiate_redeem_request(Request $request) // access_token (received in anb_get_access_token), mobile_number, otp_token (received in anb_send_otp), anb_amount, otp_code, total_payable
    {
        $payable_amount_arr = $this->anb_check_if_redeemable($request->anb_amount, $request->total_payable);

        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $request->mobile_number : '966504652456');
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'Redemption?mobile=' . $mobile_number . '&amount=' . $request->anb_amount . '&otp_value=' . $request->otp_code . '&otp_token=' . $request->otp_token . '&access_token=' . $request->access_token);

        if ($request->has('debug')) {
            custom::dump($curlResponse);
        }

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {

            $payable_amount = $payable_amount_arr['total_payable_after_anb'];
            $vat_applicable = $payable_amount_arr['vat_after_anb'];


            $this->page->saveData('anb_logs', array('status' => 'New', 'anb_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            $response['status'] = 1;
            $response['message'] = "";
            $response['anb_transaction_id'] = $curlResponse;
            $response['anb_amount'] = $request->anb_amount;
            $response['amount_remaining'] = $payable_amount > 0;
            $response['total_payable_amount_after_anb'] = round($payable_amount, 2);
            $response['vat_after_anb'] = round($vat_applicable, 2);
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.anb_partial_redeem_text_to_show'), $request->anb_amount, round($payable_amount, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.anb_full_redeem_text_to_show'), $request->anb_amount);
            }
            echo json_encode($response);
            die();
        } else {
            echo json_encode(['status' => 0, 'message' => trans('labels.something_went_wrong_please_try_again_later')]);
            die();
        }
    }

    private function anb_check_if_redeemable($anb_amount, $total_amount_with_vat)
    {
        $site_settings = custom::site_settings();
        $max_amount_redeemable_with_anb = round(($site_settings->amount_to_be_redeemed_by_anb_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($anb_amount > $max_amount_redeemable_with_anb) {
            $response['status'] = 0;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_anb);
            echo json_encode($response);
            die();
        }

        return array('total_payable_after_anb' => $total_amount_with_vat - $anb_amount, 'vat_after_anb' => 0);
    }

    public function anb_clear_after_booking_confirmed(Request $request)
    {
        $booking = $this->page->getSingle('booking', array('reservation_code' => $request->reservation_code));
        $booking_payment = $this->page->getSingle('booking_payment', array('booking_id' => $booking->id));

        if ($booking && $booking_payment && $booking_payment->anb_request != "") {


            $ok_to_update = true;

            if (strtolower($booking->booking_source) == 'ios') { // if request is from IOS

                $booking_individual_payment_method = $this->page->getSingle('booking_individual_payment_method', array('booking_id' => $booking->id));
                $booking_cc_payment = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking->id));

                // If payment is done from card and payment is completed and ANB is used
                if ($booking_individual_payment_method->payment_method == 'Credit Card' && $booking_cc_payment->status == 'pending') {

                    $ok_to_update = false;

                }

            }

            if ($ok_to_update) {
                $this->page->updateData('anb_logs', ['status' => 'Copied', 'updated_from' => strtoupper($booking->booking_source) . ' mobile app'], array('anb_request' => $booking_payment->anb_request));
            }
        }

        $response['status'] = 1;
        $response['message'] = "";
        echo json_encode($response);
        die();
    }

    public function refer_and_earn(Request $request) {
        $customer_id = custom::jwt_decode($request->customer_id);
        $refer_and_earn_data = custom::refer_and_earn_data($customer_id, $this->lang, 'app', $request->has('add_to_db'));
        if ($refer_and_earn_data['status'] == true) {
            $response['status'] = 1;
            $response['message'] = "";
            $response['data'] = $refer_and_earn_data;
        } else {
            $response['status'] = 0;
            $response['message'] = "Something went wrong!";
            $response['data'] = [];
        }
        echo json_encode($response);
        die();
    }

    public function apply_coupon_for_add_payment(Request $request) {
        $posted_data = $request->all(); // coupon_code_for_add_payment, booking_id, number_of_days, booking_source

        $coupon = DB::table('promotion_offer_coupon')->where('code', $posted_data['coupon_code_for_add_payment'])->first();
        if ($coupon) {
            $promotion_offer = DB::table('promotion_offer')->where('id', $coupon->promotion_offer_id)->where('is_for_refer_and_earn', 0)->first();

            if ($promotion_offer && ($promotion_offer->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types') && $promotion_offer->allow_on_extend_payment == 1) {
                $booking = $this->page->getSingle('booking', ['id' => $posted_data['booking_id']]);

                $pickup_date = date('Y-m-d');
                $customer_id_no = false;
                $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promotion_offer, $pickup_date);

                $booking_individual_guest = $this->page->getSingle('booking_individual_guest', ['booking_id' => $posted_data['booking_id']]);
                $booking_individual_user = $this->page->getSingle('booking_individual_user', ['booking_id' => $posted_data['booking_id']]);

                if ($booking_individual_guest) {
                    $individual_guest = $this->page->getSingle('individual_customer', ['id' => $booking_individual_guest->individual_customer_id]);
                    $customer_id_no = $individual_guest->id_no;
                } elseif ($booking_individual_user) {
                    $individual_user = $this->page->getSingle('individual_customer', ['uid' => $booking_individual_user->uid]);
                    $customer_id_no = $individual_user->id_no;
                }

                if ($customer_id_no) {
                    $is_coupon_usage_fine = custom::is_coupon_usage_fine($posted_data['coupon_code_for_add_payment'], $customer_id_no, $this->lang);

                    if ($coupon_is_valid_for_pickup_day && $is_coupon_usage_fine['status'] == true) {

                        $booking_payment_data['promotion_offer_id'] = $promotion_offer->id;
                        $booking_payment_data['promotion_offer_code_used'] = $posted_data['coupon_code_for_add_payment'];
                        $this->page->updateData('booking_payment', $booking_payment_data, ['booking_id' => $posted_data['booking_id']]);

                        $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

                        $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
                        $booking_add_payment['extended_days'] = $posted_data['number_of_days'];
                        $booking_add_payment['payment_company'] = 'HP';
                        $booking_add_payment['payment_method'] = 'PROMOCODE';
                        $booking_add_payment['transaction_reference'] = $posted_data['coupon_code_for_add_payment'];
                        $booking_add_payment['card_number'] = '';
                        $booking_add_payment['amount'] = $promotion_offer->discount;
                        $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
                        $booking_add_payment['payment_source'] = ucfirst($request->booking_source);
                        $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
                        $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
                        $id = $this->page->saveData('booking_added_payments', $booking_add_payment);

                        // sending confirmation SMS and email to customer
                        $this->send_add_payment_confirmation_to_customer($id);

                        // syncing added payment data with OASIS
                        $this->sync_booking_added_payments_with_oasis($id);

                        $response['status'] = 1;
                        $response['message'] = ($this->lang == 'eng' ? 'Payment added successfully!' : 'تمت إضافة الدفع بنجاح!');
                        $response['data'] = [];
                        echo json_encode($response);
                        die;
                    }
                }
            }
        }
        $response['status'] = 0;
        $response['message'] = "Something went wrong again!";
        $response['data'] = [];
        echo json_encode($response);
        die;
    }

    public function get_push_notifications() {
        $push_notifications = $this->page->getAll('push_notifications_log', 'id', 'desc');
        $push_notifications_array = [];
        foreach ($push_notifications as $key => $push_notification) {
            $push_notifications_array[$key]['text'] = $push_notification->body;
            $push_notifications_array[$key]['created_at'] = date('d-m-Y', strtotime($push_notification->created_at));
        }
        $response['status'] = 1;
        $response['message'] = "Success";
        $response['data'] = $push_notifications_array;
        echo json_encode($response);
        die;
    }

    public function get_home_slider_images(Request $request) {
        $slider_images = $this->page->getMultipleRows('home_slider', ['is_active' => 1, 'is_mobile' => 0, 'display_type' => 'app'], 'sort', 'asc');
        $slider_images_array = [];
        foreach ($slider_images as $slider_image) {
            $slider_images_array[] = custom::baseUrl('public/uploads/' . ($this->lang == 'eng' ? $slider_image->image1 : $slider_image->image2));
        }
        $response['status'] = 1;
        $response['message'] = "Success";
        $response['data'] = $slider_images_array;
        echo json_encode($response);
        die;
    }

}

?>