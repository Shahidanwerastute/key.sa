<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Front\Page;
use App\Helpers\Custom;
use Session;
use App;
use Lang;
use Carbon\Carbon;
use App\Models\Admin\Booking;
use DB;
use SoapClient;
use SoapFault;
use stdClass;
use Validator;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;
use Auth;

class PageController extends Controller
{
    private $page = '';
    private $base_url = '';
    private $lang_base_url = '';
    private $lang = '';
    private $pdf = '';


    public function __construct(Request $request)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if (Session::has('individual_customer_id') && Session::has('corporate_customer_id')) {
            die;
        }

        // cleaning request data
        // $_REQUEST = custom::clean_request_data($_REQUEST);

        DB::enableQueryLog();
        $this->page = new Page();
        $this->pdf = App::make('snappy.pdf.wrapper');
        // $this->base_url = custom::baseurl('/');
        $this->base_url = custom::baseurl();

        // echo $this->base_url;die();
        $site = custom::site_settings();
        if ($site->site_language == 'both') {
            $segments = $request->segments();
            if (isset($segments[0]) && $segments[0] == 'en') {
                $this->lang_base_url = $this->base_url . '/en';
                $language = 'eng';
            } else {
                $this->lang_base_url = $this->base_url;
                $language = 'arb';
            }
        } else {
            $this->lang_base_url = $this->base_url;
            $language = $site->site_language;
        }
        $this->lang = $language;
        app()->setLocale($language);
        \Artisan::call('cache:clear');
        // \Artisan::call('view:clear');
        //echo \URL::previous();
        /*echo app()->getLocale();
        exit();*/
        //echo '<pre>';print_r($request->segments());exit();
    }

    public function testEmailTemplates() // test_mail
    {

    }

    public function set_lang_session(Request $request)
    {
        //$langPosted = $_REQUEST['lang'];
        $langPosted = $request->input('lang');
        Session::put('lang', $langPosted);
        Session::save();
        echo 1;
        exit();
    }

    public function set_mobile_full_version(Request $request)
    {
        //when web version then full_version = 1
        //when mobile version then full_version = 0
        $full_version = $request->input('full_version');
        Session::put('full_version', $full_version);
        Session::save();
        echo 1;
        exit();
    }

    public function index()
    {
        try {
            // $payment_link = "https://www.barnscafe.com.sa/";
            // $payment_short_link = custom::fireBaseShortLink($payment_link);
            // custom::dump($payment_short_link);
            if (isset($_REQUEST['sess']) && $_REQUEST['sess'] == 1) {
                //sess will be set in mobile version search when user get back to edit the search
                //do not clear session
            } else {
                custom::clearAllSessionsFromCheckout();
                custom::clearSessionsFromCheckoutForGuest();
            }
            //echo Session::get('vat_mode');exit();

            $regions = $this->page->getRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;

            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;

            Session::put('lang', $this->lang);
            Session::save();
            $myArray = array();
            $where['is_active'] = 1;
            $where['display_type'] = 'website';
            if (custom::is_mobile()) {
                $where['is_mobile'] = 1;
            } else {
                $where['is_mobile'] = 0;
            }
            $homeSlides = $this->page->getMultipleRows('home_slider', $where, 'sort', 'asc');
            $offers = $this->page->getHomePageOffers();
            $i = 0;
            foreach ($homeSlides as $slide) {
                $myArray[$i]['desc'] = ($this->lang == 'eng' ? $slide->eng_slider_text : $slide->arb_slider_text);
                $myArray[$i]['image'] = ($this->lang == 'eng' ? $slide->image1 : $slide->image2);
                $myArray[$i]['id'] = $slide->id;
                $myArray[$i]['url'] = ($this->lang == 'eng' ? $slide->eng_url : $slide->arb_url);
                $myArray[$i]['alt'] = ($this->lang == 'eng' ? $slide->image1_eng_alt : $slide->image2_arb_alt);
                $myArray[$i]['clickable'] = 0;
                $myArray[$i]['car_model_id'] = 0;
                $i++;
            }

            foreach ($offers as $offer) {
                $myArray[$i]['desc'] = ($this->lang == 'eng' ? $offer->eng_home_offer_desc : $offer->arb_home_offer_desc);
                if (custom::is_mobile()) {
                    $myArray[$i]['image'] = ($this->lang == 'eng' ? $offer->image3 : $offer->image4);
                } else {
                    $myArray[$i]['image'] = ($this->lang == 'eng' ? $offer->image1 : $offer->image2);
                }

                $myArray[$i]['id'] = $offer->id;
                $myArray[$i]['url'] = '';
                $car_models_for_promotion_offer = custom::get_car_models_against_promotion($offer->id);
                $myArray[$i]['car_model_id'] = implode(',', $car_models_for_promotion_offer);
                $myArray[$i]['clickable'] = $offer->car_model_id == -1 ? 0 : 1;
                $i++;
            }

            $data['sliders'] = $myArray;
            $data['branches'] = $this->page->getBranchesOfCities();
            // custom::dump($data['branches']);
            $data['companies'] = $this->page->getMultipleRows('corporate_customer', array('is_super' => '0', 'active_status' => 'active'), 'id', 'DESC');
            //echo '<pre>';print_r($myArray);exit();
            $data['home_content'] = (array)$this->page->getSingle('home', array('id' => '1'));
            $data['content'] = (array)$this->page->getSingle('home', array('id' => '1'));
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'home';
            $data['has_searchbar'] = true;

            Session::put('show_customer_popup_after_search', 1);
            Session::save();

            $data['flash_message'] = Session::get('flash_message');
            Session::forget('flash_message');
            Session::save();

            // custom::dump($data);

            if (custom::is_mobile()) {
                return view('frontend.mobile.index', $data);
            } else {
                return view('frontend.index', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function loyalty()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('loyalty_program', array('id' => '1'));
            $data['cards'] = $this->page->getAll('loyalty_program_cards_listing');
            $data['reward_programs'] = $this->page->getAll('loyalty_program_reward_programs_listing');
            $data['faqs'] = $this->page->getAll('loyalty_program_faqs_listing');
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            return view('frontend.loyalty', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function location()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'location';
            $data['locations'] = $this->page->getBranchesAndCities(); // in model, there is where in to hide specific branches
            $data['airports'] = $this->page->getBranchesAndCitiesWithAirport(); // in model, there is where in to hide specific branches
            $data['branches'] = $this->page->getBranchesOfCities(); // in model, there is where in to hide specific branches
            $data['content'] = (array)$this->page->getSingle('location', array('id' => '1'));
            //echo '<pre>';print_r($data['locations']);exit();
            return view('frontend.locations', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function about_us()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('about_us', array('id' => '1'));
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'about-us';
            return view('frontend.about_us', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function change_points()
    {
        try {
            $api = custom::api_settings();
            $data['captcha_site_key'] = $api->captcha_site_key;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('change_points', array('id' => '1'));
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'change-points';
            return view('frontend.change_points', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function apps()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'home';
            return view('frontend.apps', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function mada()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'home';
            return view('frontend.mada', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function fleet($category_name = NULL)
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'fleet'));
            $data['active_menu'] = 'fleet';
            $offset = '0';
            $limit = '10';
            $data['categories'] = $this->page->getAll('car_category');
            $data['passengers'] = $this->page->getAllCarModelsPassengers();
            $branches = $this->page->getBranches();
            $branchObj = array();
            foreach ($branches as $key => $branch) {
                if ($this->lang == "eng") {
                    $city = $branch->c_eng_title;
                } else {
                    $city = $branch->c_arb_title;
                }
                $branchObj[$city][] = $branch;
            }
            $branchArr = json_decode(json_encode($branchObj), true);
            $data['branches'] = $branchArr;
            $regions = $this->page->getRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['pickup_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            if (isset($category_name) && $category_name != '') {
                $categoryName = str_replace('-', ' ', $category_name);
                $car_category = $this->page->getSingle('car_category', array('eng_title' => ucwords($categoryName)));
                $data['car_category'] = $cat_id = $car_category->id;
                $inputs['branch'] = '';
                $inputs['model'] = '';
                $inputs['capacity'] = '';
                $inputs['offset'] = "0";
                $inputs['cat_id'] = $cat_id;
                $stdObj = $this->page->getCarsByAllFilters($inputs, $limit);
            } else {
                $data['car_category'] = '';
                $stdObj = $this->page->getAllCars("", 'car_model.sort_col', 'asc', $offset, $limit);
            }
            $data['car_models'] = json_decode(json_encode($stdObj), true);
            // $data['years'] = $this->page->getOneColumnGroupBy('car_model', 'year', 'id', 'Desc');
            $data['years'] = ['2021', '2022', '2023', '2024'];
            if (custom::is_mobile()) {
                return view('frontend/mobile/fleet', $data);
            } else {
                return view('frontend/fleet', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getFleetPagination(Request $request)
    {
        try {
            $inputs = $request->all();
            $limit = "10";
            //$stdObj = $this->page->getCarsPagination($inputs,$limit);
            $stdObj = $this->page->getCarsByAllFilters($inputs, $limit);
            $car_models = json_decode(json_encode($stdObj), true);
            $data = custom::fleetPageHtml($car_models, $this->base_url, $this->lang_base_url, $this->lang);
            return response()->json($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getSearchResultPagination(Request $request)
    {
        try {
            if (null === $request->input('edit_booking_id')) {
                Session::forget('edit_booking_id');
                Session::Save();
            }
            //Session::forget('search_data'); Session::save(); exit();
            //print_r(Session::get('search_data')); exit();
            $modelId = "";
            if (null !== $request->input('edit_booking_id')) {
                $edit_booking_id = $request->input('edit_booking_id');
                $booking_detail = $this->page->getSingleBookingDetails($edit_booking_id, 'individual_user');
                $bookingDataForSession['from_region_id'] = $booking_detail->from_region_id;
                $bookingDataForSession['from_city_id'] = $booking_detail->from_city_id;
                $bookingDataForSession['from_branch_id'] = $booking_detail->from_location;
                $bookingDataForSession['to_city_id'] = $booking_detail->to_city_id;
                $bookingDataForSession['to_branch_id'] = $booking_detail->to_location;
                $bookingDataForSession['from_branch_name'] = ($this->lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title);
                $bookingDataForSession['to_branch_name'] = ($this->lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title);
                $fromDate = explode(' ', $booking_detail->from_date);
                $toDate = explode(' ', $booking_detail->to_date);
                $bookingDataForSession['pickup_date'] = $fromDate[0];
                $bookingDataForSession['pickup_time'] = $fromDate[1];
                $bookingDataForSession['dropoff_date'] = $toDate[0];
                $bookingDataForSession['dropoff_time'] = $toDate[1];
                $sessionArr = $bookingDataForSession; //make an array of search form for edit booking
                //$data['edit_booking_id'] = $edit_booking_id;
                Session::put('edit_booking_id', $edit_booking_id);
                //Session::put('edit_booking', true);
            } else {
                $inputs = $request->input();
                unset($inputs['offset']);
                $sessionArr = $inputs;
            }
            //=======For fleet & booking edit logic=========
            //$sessionArr['mod_id'] = "";
            if (null !== $request->input('mod_id')) {
                $modelId = $request->input('mod_id');
            }
            //========
            $sessionArr['customer_type'] = 'Individual';
            $sessionArr = session()->get('search_data');

            $search_by['book_for_hours'] = $book_for_hours = (isset($sessionArr['book_for_hours']) && $sessionArr['book_for_hours'] != '' ? $sessionArr['book_for_hours'] : 0);
            if ($sessionArr['is_delivery_mode'] == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionArr['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionArr['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($sessionArr['subscribe_for_months']) && $sessionArr['subscribe_for_months'] != '' ? $sessionArr['subscribe_for_months'] : 0);
            if ($sessionArr['is_delivery_mode'] == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionArr['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionArr['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['region_id'] = $sessionArr['from_region_id'];
            $search_by['city_id'] = $sessionArr['from_city_id'];
            $search_by['branch_id'] = $sessionArr['from_branch_id'];
            $search_by['days'] = $sessionArr['days'];
            $search_by['pickup_date'] = date('Y-m-d', strtotime($sessionArr['pickup_date']));
            $search_by['customer_type'] = $sessionArr['customer_type'];
            $search_by['category'] = $request->input('cat_id');
            $search_by['is_delivery_mode'] = $sessionArr['is_delivery_mode'];
            $search_by['isLimousine'] = (isset($sessionArr['isLimousine']) ? $sessionArr['isLimousine'] : 0);
            $search_by['isRoundTripForLimousine'] = (isset($sessionArr['isRoundTripForLimousine']) ? $sessionArr['isRoundTripForLimousine'] : 0);
            $date_picked_up_for_hours_cal = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
            $date_dropped_off_for_hours_cal = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
            $search_by['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);
            /*here was a bug, not define $car_price_sort default value, i have made it default, so if categoryId is not  =
            -1 or -2 then default value sent to query and error removed*/
            $search_by['category'] = ($search_by['category'] > 0 ? $search_by['category'] : 0);
            if (Session::has('categoryId')) { // being used for pricing filtering
                if (Session::get('categoryId') == -1) { // being used for pricing filtering
                    // car price ascending
                    $car_price_sort = 'asc';
                } elseif (Session::get('categoryId') == -2) { // being used for pricing filtering
                    // car price descending
                    $car_price_sort = 'desc';
                } else {
                    $car_price_sort = '';
                }
            } else {
                // car price ascending
                $car_price_sort = '';
            }
            if ($request->session()->has('user_company_code') && $request->session()->get('user_company_code') != '') {
                $search_by['company_code'] = $request->session()->get('user_company_code');
            }
            $limit = "10";
            $offset = $request->input('offset');

            $cars_rows = false;
            if (Session::has('corporate_customer_id')) {
                $corporate_customer_detail = DB::table('corporate_customer')->where('id', Session::get('corporate_customer_id'))->first();
                if ($corporate_customer_detail->has_price_with_quotation == 'Yes') {
                    $cars_rows = $this->page->getCorporateQuotationPrices($search_by, $corporate_customer_detail->id, $modelId, $offset, $limit, Session::get('loyalty_discount_percent'), $car_price_sort);
                }
            }

            if (!$cars_rows) {
                $cars_rows = $this->page->getAllCarModels($search_by, 'rent', $modelId, $offset, $limit, Session::get('loyalty_discount_percent'), $car_price_sort);
            }

            $cars = $cars_rows;

            $data = custom::searchResultPageHtml($cars, $this->base_url, $this->lang_base_url, $this->lang, Session::get('loyalty_discount_percent'));
            return response()->json($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function services()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('services', array('id' => '1'));
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'services';
            return view('frontend/services', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function program_awards()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('program_rewards_content', array('id' => '1'));
            $object = $this->page->getMultipleRows('programs_rewards_listing', array('active_status' => '1'), 'sort', 'ASC');
            $data['program_awards'] = json_decode(json_encode($object), True);
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'program_awards';
            return view('frontend/program_awards', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function corporate_sales()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('corporate_sales', array('id' => '1'));
            $object = $this->page->getMultipleRows('corporate_listing', array('active_status' => '1'), 'id', 'DESC');
            $data['corporate_sales'] = json_decode(json_encode($object), True);
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'corporate_sales';
            return view('frontend/corporate_sales', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function interestedInCorporateSales(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $data = $request->all();
            $siteKey = $data["g-recaptcha-response"];
            $response['captcha'] = false;
            // captcha verification function
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['captcha'] = $res;
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.captcha_msg');
                return response()->json($response);
            } else {
                unset($data["g-recaptcha-response"]);
                $lang = $this->lang;
                $data['created_at'] = date('Y-m-d H:i:s');
                $checkIfHeAlreadySentRequest = $this->page->getRowsCount('corporate_sales_response', array('email' => $data['email'], 'mobile' => $data['mobile']));
                if ($checkIfHeAlreadySentRequest > 0) {
                    $response['status'] = false;
                    $response['title'] = trans('labels.error');
                    $response['message'] = ($this->lang == 'eng' ? 'Sorry. You have already requested against this car model.' : 'آسف. كنت قد طلبت بالفعل ضد هذا النموذج سيارة.');
                    echo json_encode($response);
                    exit();
                } else {
                    $saved_id = $this->page->saveData('corporate_sales_response', $data);
                    if ($saved_id > 0) {
                        $page_content = $this->page->getSingle('corporate_sales', array('id' => '1'));
                        $site = custom::site_settings();
                        $smtp = custom::smtp_settings();
                        $email['subject'] = ($this->lang == 'eng' ? 'Interested In Car Selling' : 'مهتم في بيع السيارات');
                        $email['fromEmail'] = $smtp->username;
                        $email['fromName'] = "no-reply";
                        $email['toEmail'] = $page_content->admin_email;
                        $email['ccEmail'] = ""; //$smtp->username;
                        $email['bccEmail'] = '';
                        $email['attachment'] = '';
                        $mail_data['data']['name'] = "Admin";
                        $mail_data['data']['gender'] = "male";
                        $mail_data['data']['contact_no'] = $site->site_phone;
                        $mail_data['data']['lang_base_url'] = $this->lang_base_url;
                        $mail_data['data']['message'] = ($this->lang == 'eng' ? 'A request has been received for corporate sale. Below are the details of the interested person.' :
                            'تم استلام طلب لبيع سيارة. وفيما يلي تفاصيل الشخص المعني.');
                        $info['name'] = $data['name'];
                        $info['mobile'] = $data['mobile'];
                        $info['phone'] = $data['phone'];
                        $info['email'] = $data['email'];
                        $info['company'] = $data['company'];
                        $info['address'] = $data['address'];
                        $info['designation'] = $data['designation'];
                        $info['message'] = $data['message'];
                        $info['contact_type'] = $data['contact_type'];
                        $mail_data['data']['info'] = $info;
                        $sent = custom::sendEmail('form', $mail_data, $email, "eng");
                        $response['captcha'] = true;
                        $response['status'] = true;
                        $response['title'] = 'Success';
                        $response['message'] = ($this->lang == 'eng' ? 'Thanks for having interest, one of our representative will contact you soon!' : 'Thanks for having interest, one of our representative will contact you soon!');
                        echo json_encode($response);
                        exit();
                    } else {
                        $response['status'] = false;
                        $response['title'] = trans('labels.error');
                        $response['message'] = ($this->lang == 'eng' ? 'Sorry. You request failed to be submitted. Please try again.' : 'نعتذر. لم يتم إرسال طلبك. حاول مرة اخرى.');
                        echo json_encode($response);
                        exit();
                    }
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function sell_car()
    {
        try {
            $offset = 0;
            $limit  = 6; // change limit here if need to change how many records are to be loaded on first page load
            $data['base_url']       = $this->base_url;
            $data['lang_base_url']  = $this->lang_base_url;
            $data['lang']           = $this->lang;
            $data['content']        = (array)$this->page->getSingle('car_selling', array('id'           => '1'));
            $data['brands']         = $this->page->getAll('car_selling_brand');
            $data['services']         = $this->page->getAll('car_selling_services');
            $data['slider_images']         = $this->page->getAll('car_selling_slider_images');
            $data['years']          = $this->page->getYearsOfCarsToSell();
            $data['cars']           = $this->page->getCarsToSell("", "", $offset, $limit, $data['lang']);
            $all_cars               = $this->page->getCarsToSell();
            $data['count_of_cars']  = count($all_cars);
            $data['active_menu']    = 'car-selling';

            return view('frontend/car-selling', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function book_car()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'book-car'));
            $regions = $this->page->getRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $data['active_menu'] = 'book-car';
            $data['has_searchbar'] = true;
            return view('frontend/book_car', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function news()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('news_content', array('id' => '1'));
            $data['content_listing'] = $this->page->getMultipleRows('news_listing', array('active_status' => '1'), 'id', 'DESC');
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'news';
            return view('frontend/latest_news', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function faqs()
    {
        try {
            $data['content'] = (array)$this->page->getSingle('faqs_content', array('id' => '1'));
            $data['content_listing'] = $this->page->getAll('faqs_question', 'id', 'Desc');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'faqs';
            return view('frontend/faqs', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function careers()
    {
        try {
            $api = custom::api_settings();
            $data['captcha_site_key'] = $api->captcha_site_key;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('career', array('id' => '1'));
            $data['departments'] = $this->page->getAll('department', 'id', 'Desc');
            $data['nationalities'] = $this->page->getAll('nationalities', 'oracle_reference_number', 'Desc');
            $data['languages'] = $this->page->getAll('setting_languages', 'sort_col', 'asc');
            $data['career_cities'] = $this->page->getAll('city');
            $data['active_menu'] = 'careers';
            return view('frontend/careers', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function contact_us()
    {
        try {
            $api = custom::api_settings();
            $data['captcha_site_key'] = $api->captcha_site_key;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('contact_us', array('id' => '1'));
            $data['inquiries'] = $this->page->getAll('setting_inquiry_type', 'id', 'Desc');
            $data['countries'] = $this->page->getAll('country', 'oracle_reference_number', 'ASC');
            $data['active_menu'] = 'contact-us';
            return view('frontend/contact_us', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function register()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'register'));
            $data['active_menu'] = 'register';
            return view('frontend/registration', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function new_user_ind()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'create-ind-user'));
            $data['active_menu'] = 'register';
            $data['nationalities'] = $this->page->getAllNationalities($this->lang);
            $data['countries'] = $this->page->getAllCountries($this->lang);
            $fetch_user_info_by['id'] = Session::get('individual_customer_id');
            $data['user_info'] = $this->page->getSingle('individual_customer', $fetch_user_info_by);
            $data['site_settings'] = custom::site_settings();
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            return view('frontend/registration_new_user', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function create_login_ind()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'create-ind-login'));
            $data['active_menu'] = 'register';
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            return view('frontend/create_login', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function new_user_corp()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'register';
            return view('frontend/registration_new_user', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function create_login_corp()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'register';
            return view('frontend/create_login', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function new_ind_user_signup(Request $request)
    {
        try {
            $siteKey = $request->input('g-recaptcha-response');
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.captcha_msg');
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            }

            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            }

            $isPasswordStrong = custom::isPasswordStrong($request->input('password'), $this->lang);
            if (!$isPasswordStrong['status']) {
                $response['title'] = Lang::get('labels.error');
                $response['message'] = $isPasswordStrong['message'];
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            }

            if ($request->input('id_type') == '243'/* || $request->input('id_type') == '68'*/) {
                $is_valid_id = custom::validateSaudiID($request->input('id_no'), $this->lang);
                if (!$is_valid_id) {
                    $response['title'] = trans('labels.error');
                    $response['message'] = trans('labels.enter_valid_id_no_msg');
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                }
            }

            $send_extra_sms = $request->input('send_extra_sms');
            $id_n = custom::convertArabicNumbersToEnglish($request->input('id_no'));
            $id_t = $request->input('id_type');
            $first_element_of_id_number = substr($id_n, 0, 1);
            $response['something_went_wrong'] = false;
            $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('id_no' => $id_n));
            if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
                $response['error_message'] = ($this->lang == 'eng' ? 'Sorry we can\'t complete your registration' : 'عذرا لا يمكن إنشاء حساب');
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            } elseif ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->simah_block == "yes") {
                $response['error_message'] = ($this->lang == 'eng' ? 'Dear Customer.<br>
You have an outstanding amount payable to ' . custom::getSiteName($this->lang) . '.<br>
Kindly remit the payment at our nearest branch to use our website.' : '<br>.عزيزي العميل
<br>.توجد لديكم مديونية لدى شركة ' . custom::getSiteName($this->lang) . ' لتأجير السيارات
.نرجو منكم التفضل بزيارة أي من فروعنا لتسوية المديونية والإستفادة من خدمات موقع ' . custom::getSiteName($this->lang));
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            } elseif ($id_t == '243' && $first_element_of_id_number != '1') {
                $response['error_message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            } elseif ($id_t == '68' && $first_element_of_id_number != '2') {
                $response['error_message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            }
            ini_set('max_execution_time', 0);
            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');
            if ($password != $confirm_password) {
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.password_not_match_msg');
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            } else {
                $get_user_by_email['email'] = $request->input('email');
                $user = $this->page->getSingle('users', $get_user_by_email);
                $individual_customer_by_email = $this->page->getSingle('individual_customer', $get_user_by_email);
                $get_user_by_id_no['id_no'] = $id_n;
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

                        $response['title'] = Lang::get('labels.success');
                        $response['message'] = Lang::get('labels.account_created_msg');
                        $response['redirectURL'] = 'home';
                        echo json_encode($response);
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

                        $response['title'] = Lang::get('labels.success');
                        $response['message'] = Lang::get('labels.account_created_msg');
                        $response['redirectURL'] = 'home';
                        echo json_encode($response);
                        exit();
                    }

                }

                //$individual_customer_by_id = $this->page->checkIfIDExist($request->input('id_no'));
                if ($user && $user->email != '' && $user->type == 'individual_customer') {
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = Lang::get('labels.email_already_register_msg');
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                } elseif ($individual_customer_by_id_no && $individual_customer_by_id_no->id_no != '' && (int)$individual_customer_by_id_no->uid !== 0) {
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = Lang::get('labels.id_number_already_register_msg');
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                } elseif ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid !== 0) {
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = Lang::get('labels.email_already_register_msg');
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                } elseif (
                    ($individual_customer_by_email && $individual_customer_by_email->email != '' && (int)$individual_customer_by_email->uid === 0)
                    || ($individual_customer_by_id_no && $individual_customer_by_id_no->id_no != '' && (int)$individual_customer_by_id_no->uid === 0)
                ) {
                    // If we fall in this condition it means user had a record as a guest. Updating by email or id no.
                    // the already registered usr check based upon email is already checked in above condition.
                    $userData['name'] = htmlspecialchars($request->input('first_name')) . ' ' . htmlspecialchars($request->input('last_name'));
                    $userData['email'] = $request->input('email');
                    $userData['password'] = md5($request->input('password'));
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
                    $data['first_name'] = htmlspecialchars($request->input('first_name'));
                    $data['last_name'] = htmlspecialchars($request->input('last_name'));
                    $data['mobile_no'] = $request->input('mobile_no');
                    $data['gender'] = $request->input('gender');
                    $data['email'] = $request->input('email');
                    $data['id_type'] = $request->input('id_type');
                    $data['id_version'] = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');
                    $data['id_no'] = $id_n;
                    $data['license_no'] = custom::convertArabicNumbersToEnglish($request->input('license_no'));
                    $image = array();
                    if ($request->input('extra_info')) {
                        // New Fields as Fozan suggested
                        $data['id_country'] = $request->input('id_country');
                        $data['license_country'] = $request->input('license_country');
                        $data['job_title'] = $request->input('job_title');
                        $data['sponsor'] = htmlspecialchars($request->input('sponsor'));
                        $data['street_address'] = $request->input('street_address');
                        $data['district_address'] = $request->input('district_address');
                        $data['nationality'] = $request->input('nationality');
                        $data['dob'] = date('Y-m-d', strtotime($request->input('dob')));
                        $data['id_date_type'] = ($request->input('id_date_type') == 'gregorian' ? 'G' : 'H');
                        if ($request->input('id_date_type') == 'gregorian') {
                            $data['id_expiry_date'] = date('Y-m-d', strtotime($request->input('id_expiry_date')));
                        } else {
                            $date_for_hijri = explode('-', $request->input('id_expiry_date'));
                            $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                        }
                        $data['license_id_type'] = $request->input('license_id_type');
                        $data['license_expiry_date'] = date('Y-m-d', strtotime($request->input('license_expiry_date')));
                        // $data['id_image'] = custom::uploadFile($request->file('id_image'));
                        // $image['idImage'] = $data['id_image'];
                        // $data['license_image'] = custom::uploadFile($request->file('license_image'));
                        // $image['licenseImage'] = $data['license_image'];
                        $data['payment_method'] = $request->input('payment_method');
                    }
                    if ($individual_customer_by_email || $individual_customer_by_id_no) {
                        if ($individual_customer_by_email) {
                            $data['loyalty_card_type'] = $individual_customer_by_email->loyalty_card_type;
                        } elseif ($individual_customer_by_id_no) {
                            $data['loyalty_card_type'] = $individual_customer_by_id_no->loyalty_card_type;
                        }
                    } else {

                        if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {
                            /*new change, now loyalty will be Silver if user is female */
                            if ($request->input('gender') == "female") {
                                $data['loyalty_card_type'] = 'Silver';
                            } else {
                                $data['loyalty_card_type'] = 'Bronze';
                            }
                        }
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
                    $user = $this->page->getSingle('users', array('email' => $request->input('email')));
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    Session::put('user_id', $user->id);
                    Session::put('individual_customer_id', $individual_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_email', $user->email);
                    Session::put('logged_in_from_frontend', true);
                    Session::save();
                    /////////////
                    $data['reg_no'] = 'REG' . $user_id;
                    // send email here
                    $this->sendRegistrationEmail($data, $image);
                    if ($send_extra_sms == 'yes') {
                        // send him an sms and email for discount
                        $posted_first_name = htmlspecialchars($request->input('first_name'));
                        $posted_last_name = htmlspecialchars($request->input('last_name'));
                        $full_name = $posted_first_name . ' ' . $posted_last_name;
                        $posted_gender = $request->input('gender');
                        $posted_email = $request->input('email');
                        $posted_mobile_no = $request->input('mobile_no');
                        $walkin_record_id = $request->input('walkin_record_id');
                        $this->createWalkin($walkin_record_id, $full_name, $posted_mobile_no);
                    }
                    //===========
                    $response['title'] = Lang::get('labels.success');
                    $response['message'] = Lang::get('labels.account_created_msg');
                    //$response['redirectURL'] = 'home'; // for logging in after registration
                    $response['redirectURL'] = 'my-profile';
                    echo json_encode($response);
                    exit();
                } else { // send account confirmation email in this case
                    // no data found for entered email/ID no, so saving new user.
                    $userData['name'] = htmlspecialchars($request->input('first_name')) . ' ' . htmlspecialchars($request->input('last_name'));
                    $userData['email'] = $request->input('email');
                    $userData['password'] = md5($request->input('password'));
                    $userData['type'] = 'individual_customer';
                    $userData['created_at'] = date('Y-m-d H:i:s');
                    $userData['updated_at'] = date('Y-m-d H:i:s');
                    $user_id = $this->page->saveData('users', $userData);
                    $data['first_name'] = htmlspecialchars($request->input('first_name'));
                    $data['last_name'] = htmlspecialchars($request->input('last_name'));
                    $data['mobile_no'] = $request->input('mobile_no');
                    $data['gender'] = $request->input('gender');
                    $data['email'] = $request->input('email');
                    $data['id_type'] = $request->input('id_type');
                    $data['id_version'] = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');
                    //$data['id_version'] = ($request->input('id_version') != '' ? $request->input('id_version') : '0');
                    $data['id_no'] = $id_n;
                    $data['license_no'] = custom::convertArabicNumbersToEnglish($request->input('license_no'));
                    $image = array();
                    // following code's html is also commented out
                    /*if ($request->input('extra_info')) {
                        // New fields suggested by Fozan
                        $data['id_country'] = $request->input('id_country');
                        $data['license_country'] = $request->input('license_country');
                        $data['job_title'] = $request->input('job_title');
                        $data['sponsor'] = $request->input('sponsor');
                        $data['street_address'] = $request->input('street_address');
                        $data['district_address'] = $request->input('district_address');
                        $data['nationality'] = $request->input('nationality');
                        $data['dob'] = date('Y-m-d', strtotime($request->input('dob')));
                        $data['id_date_type'] = ($request->input('id_date_type') == 'gregorian' ? 'G' : 'H');
                        if ($request->input('id_date_type') == 'gregorian') {
                            $data['id_expiry_date'] = date('Y-m-d', strtotime($request->input('id_expiry_date')));
                        } else {
                            $date_for_hijri = explode('-', $request->input('id_expiry_date'));
                            $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                        }
                        $data['license_id_type'] = $request->input('license_id_type');
                        $data['license_expiry_date'] = date('Y-m-d', strtotime($request->input('license_expiry_date')));
                        $data['id_image'] = custom::uploadFile($request->file('id_image'));
                        $moveToPdf = false;
                        if (pathinfo($data['id_image'], PATHINFO_EXTENSION) == "pdf") {
                            \File::copy(public_path('uploads/' . $data['id_image']), public_path('pdf/' . $data['id_image']));
                            \File::copy(public_path('uploads/' . $data['id_image']), public_path('pdf2/' . $data['id_image']));
                        }
                        $image['idImage'] = $data['id_image'];
                        $data['license_image'] = custom::uploadFile($request->file('license_image'));
                        $moveToPdf = false;
                        if (pathinfo($request->file('license_image'), PATHINFO_EXTENSION) == "pdf") {
                            \File::copy(public_path('uploads/' . $data['license_image']), public_path('pdf/' . $data['license_image']));
                            \File::copy(public_path('uploads/' . $data['license_image']), public_path('pdf2/' . $data['license_image']));
                        }
                        $image['licenseImage'] = $data['license_image'];
                        $data['payment_method'] = $request->input('payment_method');
                    }*/
                    if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {
                        if ($request->input('gender') == "female") {
                            $data['loyalty_card_type'] = 'Silver';
                        } else {
                            $data['loyalty_card_type'] = 'Bronze';
                        }
                    } else {
                        $data['loyalty_card_type'] = 'Bronze';
                    }

                    $data['loyalty_points'] = '0';
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $data['uid'] = $user_id;
                    $id = $this->page->saveData('individual_customer', $data);
                    custom::send_account_verification_links($user_id, $this->lang_base_url, $this->lang);
                    //$user_data = $this->page->getSingle('individual_customer', array('id' => $id));
                    /*if (isset($data['id_image']) && pathinfo($data['id_image'], PATHINFO_EXTENSION) != 'pdf')
                        $data['id_image'] = $this->convertImageToPdf($data['id_image'], $user_data->id_no, 'id');
                    if (isset($data['license_image']) && pathinfo($data['license_image'], PATHINFO_EXTENSION) != 'pdf')
                        $data['license_image'] = $this->convertImageToPdf($data['license_image'], $user_data->id_no, 'licence');*/
                    //$where['id_no'] = $data['id_no'];
                    /*if (isset($data['id_image']) && isset($data['license_image']))
                        $this->page->updateData('individual_customer', array('id_image' => $data['id_image'], 'license_image' => $data['license_image']), $where);*/
                    // for logging in after registration
                    $user = $this->page->getSingle('users', array('email' => $request->input('email')));
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    Session::put('user_id', $user->id);
                    Session::put('individual_customer_id', $individual_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_email', $user->email);
                    Session::put('logged_in_from_frontend', true);
                    Session::save();
                    /////////////
                    $data['reg_no'] = 'REG' . $user_id;
                    // send email here for new register user..
                    $this->sendRegistrationEmail($data, $image);
                    if ($send_extra_sms == 'yes') {
                        // send him an sms and email for discount
                        if ($send_extra_sms == 'yes') {
                            // send him an sms and email for discount
                            $posted_first_name = htmlspecialchars($request->input('first_name'));
                            $posted_last_name = htmlspecialchars($request->input('last_name'));
                            $full_name = $posted_first_name . ' ' . $posted_last_name;
                            $posted_gender = $request->input('gender');
                            $posted_email = $request->input('email');
                            $posted_mobile_no = $request->input('mobile_no');
                            $walkin_record_id = $request->input('walkin_record_id');
                            $this->createWalkin($walkin_record_id, $full_name, $posted_mobile_no);
                        }
                    }
                    //===========
                    $response['title'] = Lang::get('labels.success');
                    $response['message'] = Lang::get('labels.account_created_msg');
                    //$response['redirectURL'] = 'home'; // for logging in after registration
                    $response['redirectURL'] = 'my-profile';
                    echo json_encode($response);
                    exit();
                }
            }
            // Some potentially crashy code
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

    //===========
    private function createWalkin($walkin_record_id, $full_name, $posted_mobile_no)
    {
        try {
            $walkin_data = $this->page->getSingle('walkin_data', array('id' => $walkin_record_id));
            $dataInOurSystem = $this->saveDataInOurSystem($walkin_data);
            $contract_no = $dataInOurSystem['oasis_contract_number'];
            $reservation_code = $dataInOurSystem['reservation_code'];
            $bid = $dataInOurSystem['bid'];
            $this->runBookingsSyncCronjob($bid);
            $contract_no = $this->syncDataWithOasis($walkin_data->contract_number, $reservation_code, $walkin_data, $bid);
            if ($contract_no) {
                $this->sendExtraRegistrationEmailAndSms($full_name, $posted_mobile_no, $reservation_code, $contract_no, $bid);
            }
            $this->page->deleteData('walkin_data', array('id' => $walkin_record_id));
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function saveDataInOurSystem($walkin_data)
    {
        $walkin_data = (array)$walkin_data;
        // Booking info
        $contract_no = $walkin_data['contract_number'];
        $from_branch = $walkin_data['from_branch'];
        $to_branch = $walkin_data['to_branch'];
        $from_date = $walkin_data['from_date'];
        $to_date = $walkin_data['to_date'];
        $car_model_oracle_number = $walkin_data['car_model_oracle_number'];
        $car_year = $walkin_data['model_year'];
        $contract_amount = $walkin_data['contract_amount']; // this is basically rent per day
        // Customer info
        $customer_first_name = $walkin_data['customer_first_name'];
        $customer_last_name = $walkin_data['customer_last_name'];
        $customer_id_no = $walkin_data['customer_id'];
        $customer_mobile = $walkin_data['customer_mobile'];
        $customer_gender = $walkin_data['gender'];
        $customer_id_type = $walkin_data['customer_id_type'];
        $customer_license_number = $walkin_data['customer_license_number'];
        $customer_loyalty_level = $walkin_data['customer_loyalty_level'];
        $customer_loyalty_points = $walkin_data['customer_loyalty_points'];
        $customer_data['first_name'] = $customer_first_name;
        $customer_data['last_name'] = $customer_last_name;
        $customer_data['id_type'] = $customer_id_type;
        $customer_data['id_version'] = ($customer_id_type == '68' || $customer_id_type == '243' ? '1' : '');
        $customer_data['id_no'] = $customer_id_no;
        $customer_data['mobile_no'] = $customer_mobile;
        $customer_data['license_no'] = $customer_license_number;
        $customer_data['gender'] = $customer_gender;
        $customer_data['loyalty_card_type'] = $customer_loyalty_level;
        $customer_data['loyalty_points'] = $customer_loyalty_points;
        $customer_data['is_walkin'] = 'yes'; // create new column in db
        $from_branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $from_branch));
        $to_branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $to_branch));
        $car_details = $this->page->getSingle('car_model', array('oracle_reference_number' => $car_model_oracle_number, 'year' => $car_year));
        // 1- if customer is already in our website then add the booking we are getting from api and create a booking for him
        $customerExistingWithIdNo = $this->page->getSingle('individual_customer', array('id_no' => $customer_id_no));
        if ($customerExistingWithIdNo) {
            $individual_customer_id = $customerExistingWithIdNo->id;
            $this->page->updateData('individual_customer', $customer_data, array('id_no' => $customer_id_no));
            if ($customerExistingWithIdNo->uid > 0) {
                $customerAlreadyExists = true;
            } else {
                $customerAlreadyExists = false;
            }
        } else {
            $individual_customer_id = $this->page->saveData('individual_customer', $customer_data);
            $customerAlreadyExists = false;
        }
        $no_of_days = custom::getCheckoutDays($from_date, $to_date);
        if ((int)$no_of_days === 0) $no_of_days = 1;
        if ($no_of_days >= 27) {
            $renting_type_id = 2;
        } else {
            $renting_type_id = 1;
        }
        // Saving booking information
        $booking_info['car_model_id'] = $car_details->id;
        $booking_info['from_location'] = $from_branch_details->id;
        $booking_info['to_location'] = $to_branch_details->id;
        $booking_info['from_date'] = date('Y-m-d H:i:s', strtotime($from_date));
        $booking_info['to_date'] = date('Y-m-d H:i:s', strtotime($to_date));
        $booking_info['oracle_reference_number'] = $contract_no;
        $booking_info['booking_status'] = 'Walk in'; // Make a new booking status as Walkin in bookings table
        $booking_info['sync'] = 'N';
        $booking_info['synced_at'] = "0000-00-00";
        $booking_info['type'] = ($customerAlreadyExists ? 'individual_customer' : 'guest');
        $booking_info['renting_type_id'] = $renting_type_id;
        $booking_info['pickup_delivery_lat_long'] = '';
        $booking_info['dropoff_delivery_lat_long'] = '';
        $booking_info['is_delivery_mode'] = 'no';
        $booking_info['booking_source'] = 'website';
        $booking_info['lang'] = $this->lang;
        $booking_info['walkin_cronjob'] = 1; // new column in bookings table
        $booking_info['created_at'] = date('Y-m-d H:i:s');
        $savedBookingId = $this->page->saveData('booking', $booking_info);
        // Generating reservation code and updating in database
        $booking_info_extra['reservation_code'] = custom::generateReservationCode($from_branch_details->prefix, $savedBookingId, 'W');
        $updateBookingInfoBy['id'] = $savedBookingId;
        $this->page->updateData('booking', $booking_info_extra, $updateBookingInfoBy);
        // saving booking_individual_payment_method
        $booking_ind_payment_method['booking_id'] = $savedBookingId;
        $booking_ind_payment_method['payment_method'] = 'Cash';
        $this->page->saveData('booking_individual_payment_method', $booking_ind_payment_method);
        if ($customerAlreadyExists) {
            // saving booking_individual_user
            $booking_individual_user['booking_id'] = $savedBookingId;
            $booking_individual_user['uid'] = $customerExistingWithIdNo->uid;
            $this->page->saveData('booking_individual_user', $booking_individual_user);
        } else {
            // saving booking_individual_guest
            $booking_individual_guest['booking_id'] = $savedBookingId;
            $booking_individual_guest['individual_customer_id'] = $individual_customer_id;
            $this->page->saveData('booking_individual_guest', $booking_individual_guest);
        }
        // saving booking_payment
        $site = custom::site_settings();
        $walkin_discount_percent = $site->walkin_discount_percent;
        $booking_payment['discount_price'] = $walkin_discount_percent;
        $booking_payment['booking_id'] = $savedBookingId;
        $booking_payment['original_rent'] = $contract_amount;
        $booking_payment['rent_price'] = $contract_amount;
        $booking_payment['total_rent_after_discount'] = $contract_amount * $no_of_days;
        $booking_payment['no_of_days'] = $no_of_days;
        $booking_payment['total_sum'] = $contract_amount * $no_of_days;
        $this->page->saveData('booking_payment', $booking_payment);
        return array("reservation_code" => $booking_info_extra['reservation_code'], "bid" => $savedBookingId, 'oasis_contract_number' => $contract_no);
    }

    private function syncDataWithOasis($contract_number, $reservation_code, $data, $bid = '')
    {
        /*this is for soap api we can improve it via helper function*/
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        // checking if api's are turned off from backend then don't run the api's here
        $api_settings = custom::api_settings();
        if (isset($_REQUEST['walkin_api'])) // coming from walkin api
        {
            // If coming from walkin api then don't check for oasis apis off or on
        } else {
            if ($api_settings->api_on_off == 'off') {
                exit();
            }
        }
        try {
            $soapclient = new SoapClient($api_settings->oasis_api_url . '?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        $data = json_decode(json_encode($data), true);
        $site = custom::site_settings();
        $walkin_discount_percent = $site->walkin_discount_percent;
        $xmlr1 = simplexml_load_string('
            <setWalkinCustomerInfo>
            <walkinCustomerInfo>
            <bookingId>' . $reservation_code . '</bookingId>
            <contractNo>' . $contract_number . '</contractNo>
            <discountRatio>' . $walkin_discount_percent . '</discountRatio>
            </walkinCustomerInfo>
            </setWalkinCustomerInfo>
        ');
        try {
            $response = $soapclient->setWalkinCustomerInfo($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair($data);
            $this->sendEmail('Catch Error In Set Walkin Customer Info API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }
        if (isset($response) && strpos($response->return, 'Error') === false && !$isError) {
            //this is success case
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            if (strpos($responseMsg, 'contract is closed') !== false) {
                if ($this->lang == 'eng') {
                    $sms_text = "Sorry contract is closed.";
                } else {
                    $sms_text = "عفوا، العقد المطلوب تم إغلاقه";
                }
                $this->page->saveData('booking_cc_payment', array('status' => 'pending', 'booking_id' => $bid));
                $smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
                $isError = true;
            }
            if (strpos($responseMsg, 'returned without value') !== false) {
                // update payment status as pending
                $this->page->saveData('booking_cc_payment', array('status' => 'pending', 'booking_id' => $bid));
                $isError = true;
            }
            $emailMsg = $this->keyValPair($data, $responseMsg);
            $this->sendEmail('Error In Error In Set Walkin Customer Info API', $emailMsg);
            $isError = true;
        }
        if ($isError)
            return false;
        else
            return $response->return;
    }

    private function sendEmail($subject, $message, $sendToFozan = false)
    {
        $email['subject'] = $subject;
        $email['fromEmail'] = 'admin@key.sa';
        $email['fromName'] = 'no-reply';
        if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa' || $_SERVER['SERVER_NAME'] == 'awfar.sa' || $_SERVER['SERVER_NAME'] == 'www.awfar.sa') {
            $email['toEmail'] = 'api.admin@key.sa';
            $email['ccEmail'] = 'kholoud.j@edesign.com.sa';
        } else {
            $email['toEmail'] = 'ahsan@astutesol.com';
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

    private function keyValPair($data1, $data2 = "")
    {
        $keyValString = '';
        if ($data2 != "") {
            $keyValString .= $data2 . '<br>';
        }
        foreach ($data1 as $key => $value) {
            $keyValString .= $key . ' => ' . $value . '<br>';
        }
        return $keyValString;
    }

    private function sendExtraRegistrationEmailAndSms($name, $mobile_no, $reservation_code, $contract_no, $booking_id)
    {
        $site = custom::site_settings();
        $walkin_discount_percent = $site->walkin_discount_percent;
        $booking_detail = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        $booking_payment['discount_price'] = $walkin_discount_percent;
        // sending email code
        // sending sms code
        if (isset($mobile_no) && $mobile_no != '' && $mobile_no != null) {
            if ($this->lang == 'eng') {
                $sms_text = 'Dear ' . $name . ',' . "\n";
                $sms_text .= 'Thank you for your confirmation. You can show this sms at reservation counter and avail ' . $walkin_discount_percent . '% discount on your reservation code ' . $reservation_code . ', contract no. ' . $contract_no . "\n";
                /*$sms_text .= 'Click the below link to view your booking detail. ' . "\n";
                $sms_text .= $this->lang_base_url . '/print-booking/'.custom::encode_with_jwt($booking_id);*/
            } else {
                $walkin_discount_percent = '%' . $walkin_discount_percent;
                $sms_text = 'عزيزي ' . $name . ',' . "\n";
                /*$sms_text .= "شكرا لتأكيدكم تحويل الحجز، نرجو إظهار هذه الرسالة للموظف للحصول على خصم $walkin_discount_percent للحجز $contract_no ";*/
                $sms_text .= " شكرا لتأكيدكم تحويل الحجز $reservation_code
لقد تم إضافة خصم %10 على عقدكم ($contract_no) ";
                /*$sms_text .= "\n";
                $sms_text .= "يمكنك الضغط على الرابط لرؤية الحجز ";
                $sms_text .= $this->lang_base_url . '/print-booking/'.custom::encode_with_jwt($booking_id);*/
            }
            $smsSent = custom::sendSMS($mobile_no, $sms_text);
            if (is_bool($smsSent) == true && $smsSent == true) {
                $this->page->updateData('booking_payment', $booking_payment, array('booking_id' => $booking_detail->id));
            }
        }
    }

    //generate pdf by click on print btn on booking under user profile page
    public function generatePdf($str_url)
    {
        try {
            $str_url = custom::decode_with_jwt($str_url);
            $expl_url = explode('||', $str_url);
            if (isset($expl_url[1]) && $expl_url[1] == 'EDxjrybEuppO') {
                $reservation_code = $expl_url[0];
                //$booking_id = base64_decode($booking_id);
                $data = array();
                $booking_detail = $this->page->getSingle("booking", array("reservation_code" => $reservation_code));
                if (!$booking_detail) {
                    echo 'Invalid URL.';
                    exit;
                }
                $booking_id = $booking_detail->id;
                if ($booking_detail->type == "corporate_customer") {
                    $data['booking_content'] = (array)$this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
                } else {
                    $data['booking_content'] = (array)$this->page->getSingleBookingDetails($booking_id, 'individual_user');
                }
                if ($data['booking_content'] == '') {
                    exit();
                }
                $data['isPdf'] = "pdf";
                if ($this->lang == "eng") {
                    $view = "frontend.emails.booking_email_eng";
                } else {
                    $view = "frontend.emails.booking_email_ar";
                }
                // $html = view($view, $data)->render();echo $html;die;
                $this->pdf->loadView($view, $data);
                return @$this->pdf->setPaper('a4')->setOption('margin-bottom', 0)->inline();
                // Some potentially crashy code
            } else {
                echo 'Invalid URL.';
                exit;
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    //for corporate customers
    public function print_invoice($invoice_id)
    {
        try {
            $site = custom::site_settings();
            $invoice_id = custom::decode_with_jwt($invoice_id);
            $data = array();
            $data['company_vat_id'] = $site->vat_id;
            $data['customer_detail'] = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->select('corporate_customer.*')
                ->where('corporate_invoices.id', '=', $invoice_id)
                ->first();
            $data['invoice_detail'] = $this->page->getSingle('corporate_invoices', array('id' => $invoice_id));
            $data['contracts'] = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->join('corporate_invoices_contract', 'corporate_invoices_contract.invoice_id', '=', 'corporate_invoices.id')
                ->select('corporate_invoices_contract.*')
                ->where('corporate_invoices.id', '=', $invoice_id)
                ->get();
            $data['isPdf'] = "pdf";
            if ($this->lang == "eng") {
                $view = "frontend.pdf.invoice_print_eng";
            } else {
                $view = "frontend.pdf.invoice_print_ar";
            }

            $data['qr_code'] = custom::get_qr_code_for_invoice($data['invoice_detail']->invoice_no);

            // return view($view, $data);
            $this->pdf->setPaper('a4');
            $this->pdf->setOption('header-html', view('frontend.pdf.corp_invoice_header'));
            $this->pdf->loadView($view, $data);
            $this->pdf->setOption('footer-html', view('frontend.pdf.corp_invoice_footer'));
            return @$this->pdf->inline();
            // Some potentially crashy code
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function print_lease_invoice($invoice_id)
    {
        try {
            $site = custom::site_settings();
            $invoice_id = base64_decode($invoice_id);
            $data = array();
            $data['company_vat_id'] = $site->vat_id;
            $data['customer_detail'] = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->select('corporate_customer.*')
                ->where('corporate_invoices.id', '=', $invoice_id)
                ->first();
            $data['invoice_detail'] = $this->page->getSingle('corporate_invoices', array('id' => $invoice_id));
            $data['transactions'] = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->join('corporate_lease_transactions', 'corporate_lease_transactions.invoice_id', '=', 'corporate_invoices.id')
                ->select('corporate_lease_transactions.*')
                ->where('corporate_invoices.id', '=', $invoice_id)
                ->get();
            $data['isPdf'] = "pdf";
            if ($this->lang == "eng") {
                $view = "frontend.pdf.lease_invoice_print_eng";
            } else {
                $view = "frontend.pdf.lease_invoice_print_ar";
            }
            $this->pdf->setPaper('a4');
            $this->pdf->setOption('header-html', view('frontend.pdf.corp_invoice_header'));
            $this->pdf->loadView($view, $data);
            $this->pdf->setOption('footer-html', view('frontend.pdf.corp_invoice_footer'));
            return @$this->pdf->inline();
            // Some potentially crashy code
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function savePdf()
    {
        try {
            $filename = 'File-' . rand() . '.pdf';
            $data = array();
            $this->pdf->loadView('frontend.emails.form_email', $data)
                ->setPaper('a4')
                ->setOrientation('portrait')
                ->setOption('margin-bottom', 0)
                ->save('public/pdf/' . $filename);
            return $filename;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function convertImageToPdf($fileName, $id, $type)
    {
        try {
            $data = array();
            $data['file'] = $fileName;
            if ($type == "id") {
                $pdfFileNameWithEx = $id . "-1" . ".pdf";
                if (file_exists('public/pdf/' . $pdfFileNameWithEx)) {
                    \File::delete("public/pdf/" . $pdfFileNameWithEx);
                }
                if (file_exists('public/pdf2/' . $pdfFileNameWithEx)) {
                    \File::delete("public/pdf2/" . $pdfFileNameWithEx);
                }
            }
            if ($type == "licence") {
                $pdfFileNameWithEx = $id . "-2" . ".pdf";
                if (file_exists('public/pdf/' . $pdfFileNameWithEx)) {
                    \File::delete("public/pdf/" . $pdfFileNameWithEx);
                }
                if (file_exists('public/pdf2/' . $pdfFileNameWithEx)) {
                    \File::delete("public/pdf2/" . $pdfFileNameWithEx);
                }
            }
            $this->pdf->loadView('frontend.pdf.image_to_pdf', $data);
            $this->pdf->setPaper('a4');
            $this->pdf->setOrientation('portrait');
            $this->pdf->setOption('margin-bottom', 0);
            $this->pdf->save("public/pdf/" . $pdfFileNameWithEx);
            $this->pdf->save("public/pdf2/" . $pdfFileNameWithEx);
            return $pdfFileNameWithEx;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function downloadPdf()
    {
        try {
            $filename = 'File-' . rand() . '.pdf';
            $data = array();
            $pdf = $this->pdf->loadView('frontend.emails.form_email', $data);
            return @$pdf->download($filename);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function openPdf()
    {
        try {
            $filename = 'File-' . rand() . '.pdf';
            $data = array();
            $pdf = $this->pdf->loadView('frontend.emails.form_email', $data);
            return @$pdf->inline($filename);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function generateBookingReceipt(Request $request)
    {
        try {
            $booking_id = $request->input('booking_id');
            $email_view = ($this->lang == 'eng' ? 'frontend.emails.booking_email_eng' : 'frontend.emails.booking_email_ar');
            $filename = 'File-' . rand() . '.pdf';
            $data = array();
            $pdf = $this->pdf->loadView($email_view, $data);
            return @$pdf->inline($filename);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // kashif work 3-30-2017
    public function submitContactFrm(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['success'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $page = new Page();
            $inputs = $request->all();
            $response = array();
            $siteKey = $inputs["g-recaptcha-response"];
            // captcha verification function
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['captcha'] = $res;
                $response['message'] = trans('labels.captcha_msg');
                return response()->json($response);
            } else {
                $response['captcha'] = $res;
                $arrType = explode("|", $inputs['inquiry_type_id']);
                $inputs['inquiry_type_id'] = $arrType[0];
                $emailSend = $arrType[1];
                $inquiryName = $arrType[2];
                $info = array();
                $data = array();
                unset($inputs["g-recaptcha-response"]);
                $save = $page->saveData("inquiries", $inputs);
                // send to the admin
                // get site admin email for cc
                $site = custom::site_settings();
                $smtp = custom::smtp_settings();
                $email['subject'] = Lang::get('labels.inquiry_received_msg');
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
                $sent = custom::sendEmail('form', $data, $email, "eng");
                if ($save && $sent) {
                    $response['success'] = true;
                    $response['message'] = Lang::get('labels.form_submitted_msg');
                } else {
                    $response['success'] = false;
                    $response['message'] = Lang::get('labels.form_submitting_error_msg');
                }
                return response()->json($response);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function submitChangePoints(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['success'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $page = new Page();
            $inputs = $request->all();
            $response = array();
            $siteKey = $inputs["g-recaptcha-response"];
            //captcha verification function
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['captcha'] = $res;
                $response['message'] = trans('labels.captcha_msg');
                $response['success'] = false;
                echo json_encode($response);
                exit;
                //return response()->json($response);
            } else {
                //send to the admin
                $site = custom::site_settings();
                $smtp = custom::smtp_settings();
                $response['captcha'] = $res;
                $emailSend = $site->admin_email;
                $info = array();
                $data = array();
                unset($inputs["g-recaptcha-response"]);
                unset($inputs["checkbox"]);
                $inputs['created_at'] = date('Y-m-d H:i:s');
                $save = $page->saveData("change_points_form", $inputs);
                $email['subject'] = "Change Your Points";
                $email['fromEmail'] = $smtp->username;
                $email['fromName'] = "no-reply";
                $email['toEmail'] = $emailSend;
                $email['ccEmail'] = ""; //$smtp->username;
                $email['bccEmail'] = '';
                $email['attachment'] = '';
                $data['data']['name'] = "Admin";
                $data['data']['gender'] = "";
                $data['data']['contact_no'] = $site->site_phone;
                $data['data']['lang_base_url'] = $this->lang_base_url;
                $data['data']['message'] = "";
                $info['name'] = $inputs['name'];
                $info['email'] = $inputs['email'];
                $info['ID #'] = $inputs['id_number'];
                $info['phone'] = $inputs['mobile'];
                $data['data']['info'] = $info;
                $sent = custom::sendEmail('form', $data, $email, "eng");
                if ($save && $sent) {
                    $response['success'] = true;
                    $response['message'] = Lang::get('labels.form_submitted_msg');
                } else {
                    $response['success'] = false;
                    $response['message'] = Lang::get('labels.form_submitting_error_msg');
                }
                echo json_encode($response);
                exit;
                //return response()->json($response);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // kashif work 3-31-2017 captcha start here
    private function reCaptcha_bk($siteKey)
    {
        $api = custom::api_settings();
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $api->captcha_secret_key,
            'response' => $siteKey,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $verify = @file_get_contents($url, false, $context);
        $captcha_success = json_decode($verify);
        custom::dump($captcha_success);

        // return response true or false
        return $captcha_success->success;
    }

    private function reCaptcha($siteKey)
    {
        $api = custom::api_settings();
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => array(
                'secret' => $api->captcha_secret_key,
                'response' => $siteKey,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        curl_setopt_array($ch, $curlConfig);
        if ($result = curl_exec($ch)) {
            curl_close($ch);
            $response = json_decode($result);
            return $response->success;
        } else {
            return false;
        }
    }

    // end captcha code
    public function saveCareerForm(Request $request)
    {
        try {
            $page = new Page();
            $inputs = $request->all();
            //echo '<pre>';print_r($inputs);exit();

            $this->validate_career_form($inputs);

            $do_you_have_experience = $inputs['do_you_have_experience']; // 0, 1
            unset($inputs['do_you_have_experience']);

            $response = array();
            $siteKey = $inputs["g-recaptcha-response"];
            // captcha verification function
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['captcha'] = $res;
                $response['message'] = trans('labels.captcha_msg');
                return response()->json($response);
            } else {

                // checking if any record exist against this id no
                $id_no_exist = $this->page->getSingle('career_inquiry', ['id_number' => $inputs['id_number']]);
                if ($id_no_exist) {
                    $response['success'] = false;
                    $response['message'] = trans('labels.career_form_id_no_already_exist_msg');
                    echo json_encode($response);
                    exit();
                }

                $inputs['company_name'] = implode(',', $inputs['company_name']);
                $inputs['job_title'] = implode(',', $inputs['job_title']);
                $inputs['from_date'] = implode(',', $inputs['from_date']);
                $inputs['to_date'] = implode(',', $inputs['to_date']);

                $response['captcha'] = $res;
                $arrDept = explode("|", $inputs['department_id']);
                $inputs['department_id'] = $arrDept[0];
                $emailSend = $arrDept[1];
                $departName = $arrDept[2];
                $nationalityArr = explode("|", $inputs['nationality']);
                $nationalityTitle = $nationalityArr[1];
                $inputs['nationality'] = $nationalityTitle;
                $response['success'] = false;
                if ($request->hasFile('cv')) {
                    //$inputs['cv'] = custom::uploadFile($request->file('cv'));
                }
                unset($inputs['g-recaptcha-response']);
                $inputs['language'] = implode(', ', $inputs['language']);
                $save = $page->saveData("career_inquiry", $inputs);
                // send email to the admin
                $smtp = custom::smtp_settings();
                $site = custom::site_settings();
                //$attachPath = public_path('uploads/') . $inputs['cv'];
                $email['subject'] = Lang::get('labels.career_request_msg');
                $email['fromEmail'] = $smtp->username;
                $email['fromName'] = "no-reply";
                $email['toEmail'] = $emailSend;
                $email['ccEmail'] = "";
                $email['bccEmail'] = '';
                //$email['attachment'] = $attachPath;
                $email['attachment'] = "";
                $data['data']['name'] = "Admin";
                $data['data']['gender'] = "male";
                $data['data']['contact_no'] = $site->site_phone;
                $data['data']['lang_base_url'] = $this->lang_base_url;
                $data['data']['message'] = Lang::get('labels.career_detail_msg');
                $info['name'] = $inputs['name'];
                $info['email'] = $inputs['email'];
                $info['id_number'] = $inputs['id_number'];
                $info['phone'] = $inputs['mobile'];
                $info['department'] = $departName;
                $info['date_of_birth'] = $inputs['dob'];
                $info['nationality'] = $nationalityTitle;
                //$info['profession'] = $inputs['profession'];
                $info['city'] = $inputs['city'];
                $info['qualification'] = $inputs['qualification'];
                $info['company_name'] = $inputs['company_name'];
                $info['job_title'] = $inputs['job_title'];
                $info['from_date'] = $inputs['from_date'];
                $info['to_date'] = $inputs['to_date'];
                $info['language'] = $inputs['language'];
                $info['linkedin_profile_url'] = $inputs['linkedin_profile_url'];
                $data['data']['info'] = $info;
                $sent = custom::sendEmail('form', $data, $email, "eng");
                if ($save && $sent) {
                    $response['success'] = true;
                    $response['message'] = Lang::get('labels.form_submitted_msg');
                } else {
                    $response['success'] = false;
                    $response['message'] = Lang::get('labels.form_submitting_error_msg');
                }
                return response()->json($response);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function login(Request $request)
    {
        try {
            if ($_REQUEST['username'] == 'fozan_bag@hotmail.com') {
                $bilal = true;
            } else {
                $bilal = false;
            }
            // var_dump($bilal);die;
            $has_profile = false;
            $is_humanLess = false;
            $is_super = false;
            $is_super_corporate = false;
            $response['something_went_wrong'] = false;
            $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('email' => $request->input('username')));
            if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            }
            $fetch_by['email'] = $username = $request->input('username');
            $fetch_by['password'] = $password = md5($request->input('password'));
            $bookingIDGetCar = $request->input('bookingIDGetCar');
            // $fetch_by['type'] = $type = 'individual_customer'; commented after enrolling corporate user
            //echo '<pre>';print_r($fetch_by);exit();
            //$a = 'How are you?';
            /*if (strpos($username, '@') !== false) {
                $user = $this->page->getSingle('users', $fetch_by);
            } else {
                // Checking user with ID no.
                $user = $this->page->checkLoginUserByIdNo($username, $password);
            }*/
            $user = $this->page->validate_user($username, $password);
            // echo '<pre>';print_r($user);exit();
            // custom::logQuery();
            if (!$user || ($user && ($user->active_status == 'inactive' || $user->is_email_verified == 0 || $user->is_phone_verified == 0))) {
                $response['status'] = false;
                $response['has_profile'] = $has_profile;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.incorrect_user_password_msg');

                if ($user) {
                    if ($user->is_email_verified == 0 && $user->is_phone_verified == 0) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your mobile number & email by checking the link sent to your registered mobile number & email.' : 'الرجاء التحقق من الرسالة المرسلة على رقم الجوال والبريد الإلكتروني لتفعيل الحساب بالضغط على الرابط المرسل');
                    } elseif ($user->is_email_verified == 0 && $user->is_phone_verified == 1) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your email address by checking the link sent to your email.' : 'الرجاء التحقق من البريد الإلكتروني لتفعيل الحساب بالضغط على الرابط المرسل');
                    } elseif ($user->is_email_verified == 1 && $user->is_phone_verified == 0) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your mobile number by checking the link sent to your registered mobile number.' : 'الرجاء التحقق من الرسالة المرسلة على رقم الجوال لتفعيل الحساب بالضغط على الرابط المرسل');
                    }
                }

                echo json_encode($response);
                exit();
            } else {
                //echo $user->id;exit();
                if ($user->type == 'individual_customer') {
                    $has_profile = true;
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    Session::put('user_id', $user->id);
                    Session::put('logged_in_from_frontend', true);
                    Session::put('individual_customer_id', $individual_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_type_short', 'Individual');
                    Session::put('user_email', $user->email);
                    Session::put('user_id_no', $individual_user->id_no);
                    Session::put('has_profile', $has_profile);
                    Session::put('id_version', $individual_user->id_version);
                    Session::save();
                } elseif ($user->type == 'corporate_customer') {
                    $has_profile = false;
                    $corporate_user = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$user->id.', uid)')->first();

                    if ($corporate_user->active_status == 'inactive') {
                        $response['status'] = false;
                        $response['has_profile'] = $has_profile;
                        $response['title'] = Lang::get('labels.error');
                        $response['message'] = Lang::get('labels.incorrect_user_password_msg');
                        echo json_encode($response);
                        exit();
                    }
                    if ($corporate_user->is_super == 1) {
                        $is_super = true;
                        $is_super_corporate = true;
                        Session::put('super_corporate_id', $corporate_user->id);
                        Session::put('is_super', $is_super);
                    }

                    Session::put('user_id', $user->id);
                    Session::put('logged_in_from_frontend', true);
                    Session::put('corporate_customer_id', $corporate_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_type_short', 'Corporate');
                    Session::put('user_email', $user->email);
                    Session::put('user_company_code', $corporate_user->company_code);
                    Session::put('has_profile', $has_profile);

                    Session::save();
                }
                if ($bookingIDGetCar != '') {
                    $is_humanLess = true;
                    $response['bookingIDGetCar'] = $bookingIDGetCar;
                }
                $response['has_profile'] = $has_profile;
                $response['is_humanLess'] = $is_humanLess;
                // $response['is_super_corporate'] = $is_super_corporate;
                $response['status'] = true;
                $response['message'] = Lang::get('labels.login_successfully_msg');
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function corporateCompany(Request $request)
    {
        try {
            $super_corporate_id = $request->input('super_corporate_id');
            $corporate_company_id = $request->input('corporate_company_id');
            $corporate_user = $this->page->getSingle('corporate_customer', array('id' => $corporate_company_id));

            $user = $this->page->getSingle('users', array('id' => $corporate_user->uid));

            $has_profile = false;
            Session::put('is_super', true);
            Session::put('super_corporate_id', $super_corporate_id);
            Session::put('user_id', $user->id);
            Session::put('logged_in_from_frontend', true);
            Session::put('corporate_customer_id', $corporate_company_id);
            Session::put('user_name', $user->name);
            Session::put('user_type', $user->type);
            Session::put('user_type_short', 'Corporate');
            Session::put('user_email', $user->email);
            Session::put('user_company_code', $corporate_user->company_code);
            Session::put('has_profile', $has_profile);
            Session::save();

            $response['status'] = true;
            echo json_encode($response);
            exit();

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function loginOnPayment(Request $request)
    {
        /*$fetch_by['email'] = $email = $request->input('username');
        $fetch_by['password'] = $password = md5($request->input('password'));
        $fetch_by['type'] = $type = 'individual_customer';
        //echo '<pre>';print_r($fetch_by);exit();
        $user = $this->page->getSingle('users', $fetch_by);*/
        try {
            $has_profile = false;
            $response['something_went_wrong'] = false;
            $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('email' => $request->input('username')));
            if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
                $response['something_went_wrong'] = true;
                echo json_encode($response);
                exit();
            }
            $fetch_by['email'] = $username = $request->input('username');
            $fetch_by['password'] = $password = md5($request->input('password'));
            // $fetch_by['type'] = $type = 'individual_customer'; commented after enrolling corporate user
            //echo '<pre>';print_r($fetch_by);exit();
            //$a = 'How are you?';
            /*if (strpos($username, '@') !== false) {
                $user = $this->page->getSingle('users', $fetch_by);
            } else {
                // Checking user with ID no.
                $user = $this->page->checkLoginUserByIdNo($username, $password);
            }*/
            $user = $this->page->validate_user($username, $password);
            if (!$user || ($user && ($user->active_status == 'inactive' || $user->is_email_verified == 0 || $user->is_phone_verified == 0))) {
                $response['status'] = false;
                $response['has_profile'] = $has_profile;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.incorrect_user_password_msg');

                if ($user) {
                    if ($user->is_email_verified == 0 && $user->is_phone_verified == 0) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your mobile number & email by checking the link sent to your registered mobile number & email.' : 'الرجاء التحقق من الرسالة المرسلة على رقم الجوال والبريد الإلكتروني لتفعيل الحساب بالضغط على الرابط المرسل');
                    } elseif ($user->is_email_verified == 0 && $user->is_phone_verified == 1) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your email address by checking the link sent to your email.' : 'الرجاء التحقق من البريد الإلكتروني لتفعيل الحساب بالضغط على الرابط المرسل');
                    } elseif ($user->is_email_verified == 1 && $user->is_phone_verified == 0) {
                        $response['message'] = ($this->lang == 'eng' ? 'Please verify your mobile number by checking the link sent to your registered mobile number.' : 'الرجاء التحقق من الرسالة المرسلة على رقم الجوال لتفعيل الحساب بالضغط على الرابط المرسل');
                    }
                }

                echo json_encode($response);
                exit();
            } else {
                //echo $user->id;exit();
                if ($user->type == 'individual_customer') {
                    $has_profile = true;
                    $individual_user = $this->page->getSingle('individual_customer', array('uid' => $user->id));
                    Session::put('user_id', $user->id);
                    Session::put('logged_in_from_frontend', true);
                    Session::put('individual_customer_id', $individual_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_type_short', 'Individual');
                    Session::put('user_email', $user->email);
                    Session::put('user_id_no', $individual_user->id_no);
                    Session::put('has_profile', $has_profile);
                    Session::save();
                } elseif ($user->type == 'corporate_customer') {
                    $has_profile = false;
                    $corporate_user = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$user->id.', uid)')->first();
                    if ($corporate_user->active_status == 'inactive') {
                        $response['status'] = false;
                        $response['has_profile'] = $has_profile;
                        $response['title'] = Lang::get('labels.error');
                        $response['message'] = Lang::get('labels.incorrect_user_password_msg');
                        echo json_encode($response);
                        exit();
                    }
                    Session::put('user_id', $user->id);
                    Session::put('logged_in_from_frontend', true);
                    Session::put('corporate_customer_id', $corporate_user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_type', $user->type);
                    Session::put('user_type_short', 'Corporate');
                    Session::put('user_email', $user->email);
                    Session::put('user_company_code', $corporate_user->company_code);
                    Session::put('has_profile', $has_profile);
                    Session::save();
                }
                $response['status'] = true;
                $response['has_profile'] = $has_profile;
                $response['message'] = Lang::get('labels.login_successfully_msg');
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function profile()
    {
        // check user logged in
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $user_id = Session::get('user_id');
            $user_type = Session::get('user_type');
            if ($user_type == 'individual_customer') {
                $individual_customer_id = Session::get('individual_customer_id');
                $get_by['id'] = $individual_customer_id;
                $user_data = $this->page->getSingle('individual_customer', $get_by);
            } else {
                $corporate_customer_id = Session::get('corporate_customer_id');
                $get_by['id'] = $corporate_customer_id;
                $user_data = $this->page->getSingle('corporate_customer', $get_by);
            }
            if (isset($user_data->nationality) && $user_data->nationality != '') {
                $fetch_by['oracle_reference_number'] = $user_data->nationality;
                $data['country_data'] = $this->page->getSingle('nationalities', $fetch_by);
            }
            $data['user_data'] = $user_data;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'my-profile'));
            $data['active_menu'] = 'profile';
            if ($user_type == 'individual_customer') {
                if (custom::is_mobile()) {
                    $profile_view = 'frontend/mobile/my_profile';
                } else {
                    $profile_view = 'frontend/my_profile';
                }

            } else {
                if (custom::is_mobile()) {
                    $profile_view = 'frontend/mobile/my_corporate_profile';
                } else {
                    $profile_view = 'frontend/my_corporate_profile';
                }
            }
            return view($profile_view, $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function edit_profile()
    {
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $user_id = Session::get('user_id');
            $user_type = Session::get('user_type');
            $get_by['uid'] = $user_id;
            if ($user_type == 'individual_customer') {
                $user_data = $this->page->getSingle('individual_customer', $get_by);
            } else {
                $user_data = $this->page->getSingle('corporate_customer', $get_by);
            }
            if (isset($user_data->nationality) && $user_data->nationality != '') {
                $fetch_by['oracle_reference_number'] = $user_data->nationality;
                $data['country_data'] = $this->page->getSingle('nationalities', $fetch_by);
            }
            $data['user_data'] = $user_data;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'edit-my-profile'));
            $data['active_menu'] = 'profile';
            if ($user_type == 'individual_customer') {
                $data['nationalities'] = $this->page->getAllNationalities($this->lang);
                $data['countries'] = $this->page->getAllCountries($this->lang);
                $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
                $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
                $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
                if (custom::is_mobile()) {
                    $profile_view = 'frontend/mobile/edit_my_profile';
                } else {
                    $profile_view = 'frontend/edit_my_profile';
                }

            } else {
                if (custom::is_mobile()) {
                    $profile_view = 'frontend/mobile/edit_my_corporate_profile';
                } else {
                    $profile_view = 'frontend/edit_my_corporate_profile';
                }
            }
            return view($profile_view, $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function forgot_password(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['success'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $lang = $this->lang;
            $user_email = $request->input('email');
            $fetch_by['email'] = $user_email;
            $user = $this->page->getSingle('users', $fetch_by);
            if ($user == false || $user->type == 'admin') {
                $response['status'] = false;
                $response['message'] = Lang::get('labels.account_not_found_msg');
            } else {
                if ($user->type == 'corporate_customer') {
                    $customer_detail = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$user->id.', uid)')->first();
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
                $this->page->saveData('password_resets', $resetData);
                $emailMsg = Lang::get('labels.password_reset_request_msg');
                $emailMsg .= '<br>';
                $emailMsg .= $this->lang_base_url . '/reset-password?_key=' . $token;
                // send email
                $site = custom::site_settings();
                $smtp = custom::smtp_settings();
                $email['subject'] = Lang::get('labels.password_reset_msg');
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
                custom::sendEmail('general', $content, $email, $lang);
                $response['status'] = true;
                $response['message'] = Lang::get('labels.password_rest_email_send_msg');
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function reset_password()
    {
        try {
            $token = $_REQUEST['_key'];
            if ($token != '') {
                $fetch_by['token'] = $token;
                $record = $this->page->getSingle('password_resets', $fetch_by);
                if (!$record) {
                    return redirect($this->lang_base_url . '/home');
                } else {
                    $data['base_url'] = $this->base_url;
                    $data['lang_base_url'] = $this->lang_base_url;
                    $data['lang'] = $this->lang;
                    $data['active_menu'] = 'profile';
                    $data['user_email'] = $record->email;
                    $this->page->deleteData('password_resets', array('token' => $token));
                    return view('frontend/reset_password', $data);
                }
            } else {
                return redirect($this->lang_base_url . '/home');
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function change_password(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $isPasswordStrong = custom::isPasswordStrong($request->input('password'), $this->lang);
            if (!$isPasswordStrong['status']) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = $isPasswordStrong['message'];
                echo json_encode($response);
                exit();
            }

            $email = $request->input('email');
            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');
            if ($password != $confirm_password) {
                $response['status'] = false;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.password_confirm_password_not_match_msg');
            } else {
                $data['password'] = md5($password);
                $update_by['email'] = $email;
                $updated = $this->page->updateData('users', $data, $update_by);
                if ($updated) {
                    $response['status'] = true;
                    $response['title'] = Lang::get('labels.success');
                    $response['message'] = Lang::get('labels.password_changed_msg');
                } else {
                    $response['status'] = false;
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = Lang::get('labels.password_reste_request_failed_msg');
                }
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function logout()
    {
        //$data = \Request::session()->all();
        //echo '<pre>';print_r($data);exit();
        try {
            Session::forget('user');
            Session::forget('user_id');
            Session::forget('user_name');
            Session::forget('user_type');
            Session::forget('user_type_short');
            Session::forget('user_email');
            Session::forget('individual_customer_id');
            Session::forget('user_company_code');
            Session::forget('has_profile');
            Session::forget('logged_in_from_frontend');
            Session::forget('corporate_customer_id');
            Session::forget('is_super');
            Session::forget('super_corporate_id');
            Session::save();
            return redirect($this->lang_base_url . '/home');
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
        //echo Session::get('user_id'); exit();
    }

    public function update_profile(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            }

            if ($request->input('password') != '') {
                $isPasswordStrong = custom::isPasswordStrong($request->input('password'), $this->lang);
                if (!$isPasswordStrong['status']) {
                    $response['status'] = false;
                    $response['message'] = $isPasswordStrong['message'];
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                }
            }


            $id_n = $request->input('id_no');
            $id_type = $request->input('id_type');
            $first_element_of_id_number = substr($id_n, 0, 1);
            if (($id_type == '243' && $first_element_of_id_number != '1') || ($id_type == '68' && $first_element_of_id_number != '2')) {
                $response['status'] = false;
                $response['redirectURL'] = '';
                $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                echo json_encode($response);
                exit();
            }
            //$request = custom::isNullToEmpty($request);
            $logged_in_user = Session::get('user_email');
            $logged_in_user_id = Session::get('user_id');
            $old_id_no = $request->input('old_id_no');
            $posted_id_no = $request->input('id_no');
            $old_email = $request->input('old_email');
            $posted_email = $request->input('email');
            if ($old_id_no != $posted_id_no) {
                $users = $this->page->checkIfIndividualUserExist(array('id_no' => $posted_id_no));
                if ($users) {
                    $response['status'] = false;
                    $response['redirectURL'] = '';
                    $response['message'] = Lang::get('labels.user_already_exist_msg');
                    echo json_encode($response);
                    exit();
                }
            } elseif ($old_email != $posted_email) {
                $users = $this->page->checkIfIndividualUserExist(array('email' => $posted_email));
                if ($users) {
                    $response['status'] = false;
                    $response['redirectURL'] = '';
                    $response['message'] = ($this->lang == 'eng' ? 'A user already exist with the email address that you inserted.' : 'A user already exist with the email address that you inserted.');
                    echo json_encode($response);
                    exit();
                }
            }
            $userData['name'] = htmlspecialchars($request->input('first_name')) . ' ' . htmlspecialchars($request->input('last_name'));
            $userData['email'] = $request->input('email');
            if ($request->input('password') != '') {
                $userData['password'] = md5($request->input('password'));
            }
            $userData['updated_at'] = date('Y-m-d H:i:s');
            $user_update_by['id'] = $logged_in_user_id;
            $this->page->updateData('users', $userData, $user_update_by);
            $data['first_name'] = htmlspecialchars($request->input('first_name'));
            $data['last_name'] = htmlspecialchars($request->input('last_name'));
            $data['mobile_no'] = $request->input('mobile_no');
            $data['email'] = $request->input('email');
            // $data['id_type'] = $request->input('id_type');
            $data['id_version'] = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');
            // $data['id_no'] = $request->input('id_no');
            //$data['nationality'] = $request->input('nationality');
            /*$data['dob'] = date('Y-m-d', strtotime($request->input('dob')));
            if ($request->input('id_date_type') == 'gregorian') {
                $data['id_expiry_date'] = date('Y-m-d', strtotime($request->input('id_expiry_date')));
            } else {
                $date_for_hijri = explode('-', $request->input('id_expiry_date'));
                $data['id_expiry_date'] = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
            }*/
            //echo $data['id_expiry_date'];exit();
            //$data['license_no'] = $request->input('license_no');
            //$data['license_id_type'] = $request->input('license_id_type');
            //$data['license_expiry_date'] = date('Y-m-d', strtotime($request->input('license_expiry_date')));
            // New fields as suggested by Fozan
            /*$data['id_country'] = $request->input('id_country');
            $data['license_country'] = $request->input('license_country');
            $data['id_date_type'] = ($request->input('id_date_type') == 'gregorian' ? 'G' : 'H');
            $data['job_title'] = $request->input('job_title');
            $data['sponsor'] = $request->input('sponsor');
            $data['street_address'] = $request->input('street_address');
            $data['district_address'] = $request->input('district_address');*/
            /*$image = array();
            if ($request->file('id_image') && $request->file('id_image') != '') {
                $data['id_image'] = custom::uploadFile($request->file('id_image'));
                $image['idImage'] = $data['id_image'];
            } else {
                $data['id_image'] = $request->input('old_id_image');
            }
            if ($request->file('license_image') && $request->file('license_image') != '') {
                $data['license_image'] = custom::uploadFile($request->file('license_image'));
                $image['licenseImage'] = $data['license_image'];
            } else {
                $data['license_image'] = $request->input('old_license_image');
            }*/
            $update_by['id'] = $request->input('id');
            $updated = $this->page->updateData('individual_customer', $data, $update_by);
            //$users_id = $this->page->getSingle('individual_customer', $update_by);
            /*if ($request->hasFile('id_image') && pathinfo($request->file('id_image'), PATHINFO_EXTENSION) != 'pdf') {
                $data['id_image'] = $this->convertImageToPdf($data['id_image'], $users_id->id_no, 'id');
            }
            if ($request->hasFile('license_image') && pathinfo($request->file('license_image'), PATHINFO_EXTENSION) != 'pdf') {
                $data['license_image'] = $this->convertImageToPdf($data['license_image'], $users_id->id_no, 'licence');
            }
            $this->page->updateData('individual_customer', array('id_image' => $data['id_image'], 'license_image' => $data['license_image']), $update_by);*/
            $response['status'] = true;
            $response['redirectURL'] = 'my-profile';
            $response['message'] = Lang::get('labels.account_info_updated_msg');
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function update_corporate_profile(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('primary_email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                $response['redirectURL'] = '';
                echo json_encode($response);
                exit();
            }

            if ($request->input('password') != '') {
                $isPasswordStrong = custom::isPasswordStrong($request->input('password'), $this->lang);
                if (!$isPasswordStrong['status']) {
                    $response['status'] = false;
                    $response['message'] = $isPasswordStrong['message'];
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                }
            }

            $logged_in_user_id = Session::get('user_id');
            $logged_in_user_details = $this->page->getSingle('users', array('id' => $logged_in_user_id));
            $old_username = $logged_in_user_details->email;
            $posted_username = $request->input('username');
            if ($old_username != $posted_username) {
                $users = $this->page->getRowsCount('users', array('email' => $posted_username));
                if ($users > 0) {
                    $response['status'] = false;
                    $response['redirectURL'] = '';
                    $response['message'] = ($this->lang == 'eng' ? 'A user already exist with the username that you inserted.' : 'A user already exist with the username that you inserted.');
                    echo json_encode($response);
                    exit();
                }
            }
            $userData['name'] = $request->input('company_name_en');
            $userData['email'] = $request->input('username');
            if ($request->input('password') != '') {
                $userData['password'] = md5($request->input('password'));
            }
            $userData['updated_at'] = date('Y-m-d H:i:s');
            $user_update_by['id'] = $logged_in_user_id;
            $this->page->updateData('users', $userData, $user_update_by);
            $data['company_name_en'] = $request->input('company_name_en');
            $data['company_name_ar'] = $request->input('company_name_ar');
            $data['primary_name'] = $request->input('primary_name');
            $data['primary_position'] = $request->input('primary_position');
            $data['primary_email'] = $request->input('primary_email');
            $data['primary_phone'] = $request->input('primary_phone');
            $data['secondary_name'] = $request->input('secondary_name');
            $data['secondary_position'] = $request->input('secondary_position');
            $data['secondary_email'] = $request->input('secondary_email');
            $data['secondary_phone'] = $request->input('secondary_phone');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $update_by['id'] = $request->input('id');
            $updated = $this->page->updateData('corporate_customer', $data, $update_by);
            $response['status'] = true;
            $response['redirectURL'] = 'my-profile';
            $response['message'] = Lang::get('labels.account_info_updated_msg');
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function main_search(Request $request)
    {
        try {

            Session::forget('categoryId'); // being used for pricing filtering
            Session::save();
            /*echo $today_for_com = Carbon::now();
            echo '<pre>';print_r($request->input());exit();*/
            $data['show_mod_id'] = true; // put this here as Fozan said to show car model on search, if any issue, it should be removed from here
            $display_scroll_button = true;
            if (null !== $request->input('mod_id') || $request->input('mod_id')) {
                $display_scroll_button = false;
            }
            custom::clearPromoSessions();
            $site_settings = custom::site_settings();
            $data = array();
            $data['no_of_hours_are_fine'] = false;
            $data['difference_is_fine'] = false;
            $data['pickup_time_is_ok'] = false;
            $data['dropoff_time_is_ok'] = false;
            $data['delivery_slots_are_ok'] = true;
            $data['pickup_dropoff_are_ahead_of_current_time'] = false;
            $data['monthly_date_diff_is_fine'] = true;
            $data['weekly_date_diff_is_fine'] = true;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'search-results'));
            //Session::put('customer_loyalty_applied', false);
            $loyalty_discount_percent = "";
            $loyalty_type_used = "Bronze";
            if (null === $request->input('edit_booking_id')) {
                Session::forget('edit_booking_id');
                Session::Save();
            }
            //Session::forget('search_data'); Session::save(); exit();
            //print_r(Session::get('search_data')); exit();
            $modelId = "";
            if ($request->isMethod('post')) {
                if (null !== $request->input('edit_booking_id')) {
                    $edit_booking_id = $request->input('edit_booking_id');
                    $booking_detail = $this->page->getSingleBookingDetails($edit_booking_id, 'individual_user');
                    $bookingDataForSession['from_region_id'] = $booking_detail->from_region_id;
                    $bookingDataForSession['from_city_id'] = $booking_detail->from_city_id;
                    $bookingDataForSession['from_branch_id'] = $booking_detail->from_location;
                    $bookingDataForSession['to_city_id'] = $booking_detail->to_city_id;
                    $bookingDataForSession['to_branch_id'] = $booking_detail->to_location;
                    $bookingDataForSession['from_branch_name'] = ($this->lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title);
                    $bookingDataForSession['to_branch_name'] = ($this->lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title);
                    $fromDate = explode(' ', $booking_detail->from_date);
                    $toDate = explode(' ', $booking_detail->to_date);
                    $bookingDataForSession['pickup_date'] = $fromDate[0];
                    $bookingDataForSession['pickup_time'] = $fromDate[1];
                    $bookingDataForSession['dropoff_date'] = $toDate[0];
                    $bookingDataForSession['dropoff_time'] = $toDate[1];
                    $sessionArr = $bookingDataForSession; //make an array of search form for edit booking
                    //$data['edit_booking_id'] = $edit_booking_id;
                    Session::put('edit_booking_id', $edit_booking_id);
                    //Session::put('edit_booking', true);
                } else {
                    //echo '<pre>';
                    //print_r($request->input());
                    $sessionArr = $request->input(); //make an array of search form submitted and put into session.
                    //echo '<pre>';print_r($sessionArr);exit();
                    if (isset($sessionArr['isLimousine']) && $sessionArr['isLimousine'] == 1 && $sessionArr['isRoundTripForLimousine'] == 0) {
                        $pickup_date_time = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                        $dropoff_date_time = $pickup_date_time->addHours(8);
                        $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                        $sessionArr['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                        $sessionArr['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
                    }
                }

                // custom::dump($sessionArr);


                $search_by['book_for_hours'] = $book_for_hours = (isset($sessionArr['book_for_hours']) && $sessionArr['book_for_hours'] != '' ? $sessionArr['book_for_hours'] : 0);
                if ($sessionArr['is_delivery_mode'] == 2 && $book_for_hours > 0) {
                    $pickup_date_time = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                    $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                    $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                    $sessionArr['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                    $sessionArr['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
                }

                $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($sessionArr['subscribe_for_months']) && $sessionArr['subscribe_for_months'] != '' ? $sessionArr['subscribe_for_months'] : 0);
                if ($sessionArr['is_delivery_mode'] == 4 && $subscribe_for_months > 0) {

                    if (custom::is_mobile() && Session::has('search_data')) {
                        $search_session_data = Session::get('search_data');
                        $sessionArr['pickup_date'] = $search_session_data['pickup_date'];
                        $sessionArr['pickup_time'] = $search_session_data['pickup_time'];
                    }

                    // custom::dump($sessionArr);

                    $pickup_date_time = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                    $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                    $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                    $sessionArr['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                    $sessionArr['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
                }

                //=======For fleet & booking edit logic=========
                //$sessionArr['mod_id'] = "";
                if (null !== $request->input('mod_id')) {
                    $modelId = $request->input('mod_id');
                }
                //========
                if ($request->input('id_no_for_loyalty') != '') {
                    //previously loyalty popup was only for guests but now its also for logged in corporate user but corporate user will enter the id of individual customer.
                    //See details in documentation

                    Session::put('loyalty_tried', 'yes');
                    $id_no_for_loyalty = $request->input('id_no_for_loyalty');
                    Session::put('customer_id_no_for_loyalty', $id_no_for_loyalty);
                    $getUserDataForLoyalty = $this->page->getSingle('individual_customer', array('id_no' => $request->input('id_no_for_loyalty')));
                    //print_r($getUserDataForLoyalty);exit();
                    if ($getUserDataForLoyalty) {
                        //echo 'here in manual apply'; exit();
                        Session::put('verify_at_search_field_for_loyalty', true);
                        Session::put('verified_user_id', $getUserDataForLoyalty->id);
                        if (Session::get('user_type') == 'corporate_customer') {
                            $customer_type_for_loyalty = 'corporate_customer';
                        } else {
                            $customer_type_for_loyalty = 'individual_customer';
                        }
                        //overwrite corporate type to individual in this case
                        if (custom::isCorporateLoyalty()) $customer_type_for_loyalty = "individual_customer";
                        $loyalty_card_applicable = $this->page->getLoyaltyInfo(Session::get('search_data')['days'], $getUserDataForLoyalty->loyalty_card_type, $customer_type_for_loyalty);
                        //print_r($loyalty_card_applicable); exit();
                        if ($loyalty_card_applicable) {
                            $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                            $loyalty_type_used = $loyalty_card_applicable->loyalty_type;
                        }
                        Session::put('customer_loyalty_applied', true);
                    } else {
                        Session::put('verify_at_search_field_for_loyalty', false);
                        Session::put('customer_loyalty_applied', '');
                        $loyalty_discount_percent = "";
                        $loyalty_type_used = "Bronze";
                    }
                }
                Session::put('loyalty_discount_percent', $loyalty_discount_percent);
                Session::put('loyalty_type_used', $loyalty_type_used);
                //========
                //calculate no of days, less than 1 is 1 day
                //$no_of_days = custom::getCheckoutDays($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time'], $sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
                $ignoreHoursForBronze = $site_settings->ignore_hours_bronze;
                $ignoreHoursForGuestSilver = $site_settings->ignore_hours_silver;
                $ignoreHoursForGolden = $site_settings->ignore_hours_golden;
                $ignoreHoursForPlatinum = $site_settings->ignore_hours_platinum;
                $tempDOD = $sessionArr['dropoff_date'];
                $tempDOT = $sessionArr['dropoff_time'];
                if (Session::get('logged_in_from_frontend') != true) {
                    $ignoreHours = $ignoreHoursForBronze;
                } elseif (Session::get('logged_in_from_frontend') == true) {
                    if (Session::get('user_type') == 'individual_customer') {
                        $customerInfo = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
                        $loyalty_card_type = $customerInfo->loyalty_card_type;
                    } elseif (Session::get('user_type') == 'corporate_customer') {
                        $customerInfo = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
                        $loyalty_card_type = $customerInfo->membership_level;
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
                //===
                //$tempDOD = "25-05-2017";
                //$tempDOT = "8:45 AM";
                $original_DOD = date('Y-m-d H:i:s', strtotime($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']));
                $manupulatedTime = date('Y-m-d H:i:s', strtotime($original_DOD) - 60 * 60 * $ignoreHours);
                $manupulatedTime = explode(' ', $manupulatedTime);
                //===
                $tempDOD = date('d-m-Y', strtotime($manupulatedTime[0]));
                $tempDOT = date('h:i:s A', strtotime($manupulatedTime[1]));
                $no_of_days = custom::getCheckoutDays($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time'], $tempDOD . ' ' . $tempDOT);
                $today_for_com = Carbon::now();
                $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $date_dropped_off = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
                $pick_for_com_mins = $today_for_com->diffInMinutes($date_picked_up);
                $dropoff_for_com_mins = $today_for_com->diffInMinutes($date_dropped_off);
                if ($dropoff_for_com_mins >= $pick_for_com_mins) {
                    if ((int)$no_of_days === 0) $no_of_days = 1;
                }
                $sessionArr['days'] = $no_of_days;
                //=======
                if (Session::has('user_type') && Session::get('user_type') != '') {
                    if (Session::get('user_type') == 'corporate_customer') {
                        $customer_type_for_search = 'Corporate';
                    } elseif (Session::get('user_type') == 'individual_customer') {
                        $customer_type_for_search = 'Individual';
                    }
                } else {
                    $customer_type_for_search = 'Individual';
                }
                $sessionArr['customer_type'] = $customer_type_for_search;
                $sessionArr['category'] = 0;
                $sessionArr['parking_fee'] = custom::parking_fee_for_branch($sessionArr['from_branch_id']);
                $sessionArr['tamm_charges_for_branch'] = custom::tamm_charges_for_branch($sessionArr['from_branch_id']);
                $date_picked_up_for_hours_cal = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $date_dropped_off_for_hours_cal = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
                $sessionArr['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);
                Session::put('search_data', $sessionArr);
                Session::save();
            } else {
                if (Session::get('search_data') == "") {
                    return redirect($this->lang_base_url . '/home');
                }
                $sessionArr = Session::get('search_data');
                //print_r(Session::get('search_data')); exit();
            }
            /*$emailString = '';
            foreach (Session::get('search_data') as $searchData => $value) {
                $emailString .= $searchData . '|' . $value . ',';
                //
            }*/
            //mail('bilal_ejaz@astutesol.com', 'Search Data', $emailString);
            // checking if delivery slots are ok for delivery branch

            if ($sessionArr['is_delivery_mode'] == 1) {
                $from_branch_id_for_checking_slot = $sessionArr['from_branch_id'];
                $branch_data_for_slot = $this->page->getSingle('branch', array('id' => $from_branch_id_for_checking_slot));
                if ($branch_data_for_slot->capacity_mode == 'on') {
                    $min_diff = $branch_data_for_slot->hours_for_delivery * 60; // converting hours to minutes and getting before and after time interval.
                    $pickup_date_time = $sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time'];
                    $time = strtotime($pickup_date_time);
                    $before_time = date("Y-m-d H:i:s", strtotime('-' . $min_diff . ' minutes', $time));
                    $after_time = date("Y-m-d H:i:s", strtotime('+' . $min_diff . ' minutes', $time));
                    $slot_capacity = $branch_data_for_slot->capacity;
                    $countOfBookingsInTheTimeSlot = $this->page->getCountOfBookingsInTimeInterval($from_branch_id_for_checking_slot, $before_time, $after_time);
                    if ($countOfBookingsInTheTimeSlot >= $slot_capacity) {
                        $data['delivery_slots_are_ok'] = false; // to be uncommented when showing this module to Fozan
                    }
                }
            }
            if ($sessionArr['is_delivery_mode'] == 1) // only checking if he is coming through delivery tab
            {
                /*new query for getting allowed hours by Kashif*/
                $branchInfo = $this->page->getSingle('branch', array('id' => $sessionArr['from_branch_id']));

                // To check if delivery time is fine as per database allowed time
                /*$before_hours_to_show = (int)$site_settings->hours_before_delivery;*/
                $before_hours_to_show = ($branchInfo) ? (int)$branchInfo->hours_before_delivery : 0;
                $today = Carbon::now();
                $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $no_of_mins_to_pickup_from_today = $today->diffInMinutes($date_picked_up);
                // no of mins in 1 day: 1440
                /*$no_of_mins_from_db = (int)$site_settings->hours_before_delivery * 60;*/
                $no_of_mins_from_db = ($branchInfo) ? (int)$branchInfo->hours_before_delivery * 60 : 0;
                if ((int)$no_of_mins_to_pickup_from_today > $no_of_mins_from_db) {
                    $data['no_of_hours_are_fine'] = true;
                }
            } elseif ($sessionArr['is_delivery_mode'] == 4) {
                /*new query for getting allowed hours by Kashif*/
                $branchInfo = $this->page->getSingle('branch', array('id' => $sessionArr['from_branch_id']));

                // To check booking time is fine as per database allowed time
                /*$before_hours_to_show = (int)$site_settings->reservation_before_hours_for_subscription;*/
                $before_hours_to_show = ($branchInfo) ? (int)$branchInfo->reservation_before_hours_for_subscription : 0; /*new logic by kashif*/
                $today = Carbon::now();
                $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $no_of_mins_to_pickup_from_today = $today->diffInMinutes($date_picked_up);
                // no of mins in 1 day: 1440
                /*$no_of_mins_from_db = (int)$site_settings->reservation_before_hours_for_subscription * 60;*/
                $no_of_mins_from_db = ($branchInfo) ? (int)$branchInfo->reservation_before_hours_for_subscription * 60 : 0;
                if ((int)$no_of_mins_to_pickup_from_today > $no_of_mins_from_db) {
                    $data['no_of_hours_are_fine'] = true;
                }
            } else {
                /*new query for getting allowed hours by Kashif*/
                $branchInfo = $this->page->getSingle('branch', array('id' => $sessionArr['from_branch_id']));

                // To check booking time is fine as per database allowed time
                /*$before_hours_to_show = (int)$site_settings->reservation_before_hours;*/
                $before_hours_to_show = ($branchInfo) ? (int)$branchInfo->reservation_before_hours : 0; /*new logic by kashif*/
                $today = Carbon::now();
                $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $no_of_mins_to_pickup_from_today = $today->diffInMinutes($date_picked_up);
                // no of mins in 1 day: 1440
                /*$no_of_mins_from_db = (int)$site_settings->reservation_before_hours * 60;*/
                $no_of_mins_from_db = ($branchInfo) ? (int)$branchInfo->reservation_before_hours * 60 : 0;
                if ((int)$no_of_mins_to_pickup_from_today > $no_of_mins_from_db) {
                    $data['no_of_hours_are_fine'] = true;
                }
            }
            $data['days_allowed'] = ($before_hours_to_show > 0 ? $before_hours_to_show : '0');
            // To check if dropoff date time is ahead of pickup date time
            $today_for_com = Carbon::now();
            $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
            $date_dropped_off = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
            $pick_for_com_mins = $today_for_com->diffInMinutes($date_picked_up);
            $dropoff_for_com_mins = $today_for_com->diffInMinutes($date_dropped_off);
            if ($dropoff_for_com_mins > $pick_for_com_mins) {
                $data['difference_is_fine'] = true;
            }
            // To check if pickup and dropoff time ahead of current time
            $today_for_com = Carbon::now();
            $date_picked_up = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
            $date_dropped_off = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
            if (($date_picked_up > $today_for_com) && ($date_dropped_off > $today_for_com)) {
                $data['pickup_dropoff_are_ahead_of_current_time'] = true;
            }
            //print_r(Session::get('search_data')); exit();
            $search_by['region_id'] = $sessionArr['from_region_id'];
            $search_by['city_id'] = $sessionArr['from_city_id'];
            $search_by['branch_id'] = $sessionArr['from_branch_id'];
            $search_by['days'] = $sessionArr['days'];
            $search_by['pickup_date'] = $sessionArr['pickup_date'];
            $search_by['customer_type'] = $sessionArr['customer_type'];
            $search_by['category'] = $sessionArr['category'];
            $search_by['isLimousine'] = (isset($sessionArr['isLimousine']) ? $sessionArr['isLimousine'] : 0);
            $search_by['isRoundTripForLimousine'] = (isset($sessionArr['isRoundTripForLimousine']) ? $sessionArr['isRoundTripForLimousine'] : 0);
            if ($request->session()->has('user_company_code') && $request->session()->get('user_company_code') != '') {
                $search_by['company_code'] = $request->session()->get('user_company_code');
            }

            $search_by['is_delivery_mode'] = $sessionArr['is_delivery_mode'];
            $date_picked_up_for_hours_cal = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
            $date_dropped_off_for_hours_cal = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
            $search_by['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

            // checking for monthly dates logic for monthly tab here
            if ($site_settings->monthly_renting_mode == 'on' && $search_by['is_delivery_mode'] == 3) {
                $date_picked_up_for_monthly_cal = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $date_dropped_off_for_monthly_cal = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
                $months_diff = $date_dropped_off_for_monthly_cal->diffInDays($date_picked_up_for_monthly_cal);
                if ($months_diff < 30) {
                    $data['monthly_date_diff_is_fine'] = false;
                    $expected_dropoff_date = $date_picked_up_for_monthly_cal->addMonth(1)->toDayDateTimeString();
                    $data['monthly_date_diff_is_fine_msg'] = ($this->lang == 'eng' ? 'The dropoff date must be a month from pickup date.' : 'تاريخ التسليم يجب ان يكون شهر من تاريخ الاستلام');
                }
            }

            // checking for weekly dates logic for weekly tab here
            if ($site_settings->weekly_renting_mode == 'on' && $search_by['is_delivery_mode'] == 5) {
                $date_picked_up_for_weekly_cal = new Carbon($sessionArr['pickup_date'] . ' ' . $sessionArr['pickup_time']);
                $date_dropped_off_for_weekly_cal = new Carbon($sessionArr['dropoff_date'] . ' ' . $sessionArr['dropoff_time']);
                $months_diff = $date_dropped_off_for_weekly_cal->diffInDays($date_picked_up_for_weekly_cal);
                if ($months_diff < 7) {
                    $data['weekly_date_diff_is_fine'] = false;
                    $expected_dropoff_date = $date_picked_up_for_weekly_cal->addWeek(1)->toDayDateTimeString();
                    $data['weekly_date_diff_is_fine_msg'] = ($this->lang == 'eng' ? 'The dropoff date must be a week from pickup date.' : 'تاريخ التسليم يجب ان يكون شهر من تاريخ الاستلام');
                }
            }

            //$modelId = $sessionArr['mod_id'];
            // Code for branch open / closed logic
            $frm_brnch = $sessionArr['from_branch_id'];
            $pickup_dt = $sessionArr['pickup_date'];
            $pickup_day = date('l', strtotime($pickup_dt));
            $pickup_tm = date('H:i', strtotime($sessionArr['pickup_time']));
            $to_brnch = $sessionArr['to_branch_id'];
            $drpoff_dt = $sessionArr['dropoff_date'];
            $drpoff_day = date('l', strtotime($drpoff_dt));
            $drpoff_tm = date('H:i', strtotime($sessionArr['dropoff_time']));
            // checking if time is ok for pickup branch
            $pickup_schedule_is_ok = $this->page->checkIfBranchIsOpen($frm_brnch, $pickup_day, $pickup_tm, $pickup_dt, ($sessionArr['is_delivery_mode'] == 1));
            $dropoff_schedule_is_ok = $this->page->checkIfBranchIsOpen($to_brnch, $drpoff_day, $drpoff_tm, $drpoff_dt, ($sessionArr['is_delivery_mode'] == 1));
            if ($pickup_schedule_is_ok) {
                $data['pickup_time_is_ok'] = true;
            }
            if ($dropoff_schedule_is_ok) {
                $data['dropoff_time_is_ok'] = true;
            }
            $page = new Page();
            $regions = $page->getRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $search_by['pickup_date'] = date('Y-m-d', strtotime($search_by['pickup_date']));
            $offset = "0";
            $limit = "10";
            // Applying auto Bronze loyalty
            if (Session::get('customer_loyalty_applied') == '') {
                //echo 'here in auto apply'; exit();
                if (Session::get('user_type') == 'corporate_customer') {
                    $customer_type_for_loyalty = 'corporate_customer';
                } else {
                    $customer_type_for_loyalty = 'individual_customer';
                }

                //overwrite corporate type to individual in this case
                if (custom::isCorporateLoyalty()) $customer_type_for_loyalty = "individual_customer";
                $loyalty_card_applicable = $this->page->getLoyaltyInfo(Session::get('search_data')['days'], 'Bronze', $customer_type_for_loyalty);
                if ($loyalty_card_applicable) {
                    $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                    $loyalty_type_used = $loyalty_card_applicable->loyalty_type;
                    Session::put('loyalty_discount_percent', $loyalty_discount_percent);
                    Session::put('loyalty_type_used', $loyalty_type_used);
                }
            }
            // If user logged in than get his loyalty type and apply discount
            if (Session::get('logged_in_from_frontend') == true && $request->input('id_no_for_loyalty') == '') {
                if (Session::get('user_type') == 'corporate_customer') {
                    $userDataForLoyalty = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
                    $loyalty_card_type_for_loyalty = $userDataForLoyalty->membership_level;
                    $customer_type_for_loyalty = 'corporate_customer';
                } else {
                    $userDataForLoyalty = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
                    $loyalty_card_type_for_loyalty = $userDataForLoyalty->loyalty_card_type;
                    $customer_type_for_loyalty = 'individual_customer';
                    Session::put('loyalty_tried', 'yes');
                }
                //echo 'here in auto apply';
                //overwrite corporate type to individual in this case
                if (custom::isCorporateLoyalty()) $customer_type_for_loyalty = "individual_customer";
                $loyalty_card_applicable = $this->page->getLoyaltyInfo(Session::get('search_data')['days'], $loyalty_card_type_for_loyalty, $customer_type_for_loyalty);
                if ($loyalty_card_applicable) {
                    $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
                    $loyalty_type_used = $loyalty_card_applicable->loyalty_type;
                    Session::put('loyalty_discount_percent', $loyalty_discount_percent);
                    Session::put('loyalty_type_used', $loyalty_type_used);
                }
            }

            if (isset($search_by['is_delivery_mode']) && $search_by['is_delivery_mode'] == 2) // if user is coming from 3rd tab (hour rate) than no loyalty discount for this user
            {
                Session::put('loyalty_discount_percent', "");
                Session::put('loyalty_type_used', "");
            }

            $search_by['book_for_hours'] = $sessionArr['book_for_hours'];
            $search_by['subscribe_for_months'] = $sessionArr['subscribe_for_months'];

            //echo 'loyalty_discount_percent '.Session::get('loyalty_discount_percent');exit();

            $corporate_quotation_cars_array = [];
            if (Session::has('corporate_customer_id')) {
                $corporate_customer_detail = DB::table('corporate_customer')->where('id', Session::get('corporate_customer_id'))->first();
                if ($corporate_customer_detail->has_price_with_quotation == 'Yes') {
                    $corporate_quotation_cars = $this->page->getCorporateQuotationPrices($search_by, $corporate_customer_detail->id, $modelId, $offset, $limit, Session::get('loyalty_discount_percent'), "");
                    // custom::dump($corporate_quotation_cars);
                    if ($corporate_quotation_cars) {
                        foreach ($corporate_quotation_cars as $corporate_quotation_car) {
                            $corporate_quotation_cars_array[] = "'" . $corporate_quotation_car->cqp_oracle_reference_number . '-' . $corporate_quotation_car->cqp_year . "'";
                        }
                    }
                }
            }

            // custom::dump($corporate_quotation_cars_array);

            $cars_rows = $this->page->getAllCarModels($search_by, 'rent', $modelId, $offset, $limit, Session::get('loyalty_discount_percent'), "", $corporate_quotation_cars_array);

            if (isset($corporate_quotation_cars) && $corporate_quotation_cars) {
                $data['cars'] = array_merge($corporate_quotation_cars, $cars_rows);
            } else {
                $data['cars'] = $cars_rows;
            }

            // custom::dump($data['cars']);

            // echo '<pre>';print_r($data['cars']);exit();
            $data['categories'] = $this->page->getAll('car_category');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'search-results'));
            $data['active_menu'] = 'book-car';
            $data['site_settings'] = custom::site_settings();
            $data['from_fleet_no_result'] = false;
            if (null !== $request->input('edit_booking_id')) {
                $data['show_mod_id'] = false;
            }/*else{
            $data['show_mod_id'] = false;
        }*/
            $data['mod_id'] = $modelId;
            if ($modelId != '' && count($data['cars']) == 0) {
                $data['from_fleet_no_result'] = true;
            }
            $data['from_branch_info'] = $this->page->getSingle('branch', array('id' => $sessionArr['from_branch_id']));
            $data['to_branch_info'] = $this->page->getSingle('branch', array('id' => $sessionArr['to_branch_id']));
            $data['display_scroll_button'] = $display_scroll_button;

            $data['returnToHome'] = 1;

            if (custom::is_mobile()) {
                return view('frontend/mobile/booking', $data);
            } else {
                return view('frontend/booking', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    public function main_search_with_filter(Request $request)
    {
        try {
            $site_settings = custom::site_settings();
            $lang = $this->lang;
            $html = '';
            $promo_discount_percent = 0;
            $sessionVals = Session::get('search_data');

            $search_by['book_for_hours'] = $book_for_hours = (isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] != '' ? $sessionVals['book_for_hours'] : 0);
            if ($sessionVals['is_delivery_mode'] == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionVals['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionVals['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] != '' ? $sessionVals['subscribe_for_months'] : 0);
            if ($sessionVals['is_delivery_mode'] == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionVals['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionVals['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['region_id'] = $sessionVals['from_region_id'];
            $search_by['city_id'] = $sessionVals['from_city_id'];
            $search_by['branch_id'] = $sessionVals['from_branch_id'];
            $search_by['days'] = $sessionVals['days'];
            $search_by['pickup_date'] = $sessionVals['pickup_date'];
            $search_by['customer_type'] = $sessionVals['customer_type'];

            $search_by['is_delivery_mode'] = $sessionVals['is_delivery_mode'];
            $date_picked_up_for_hours_cal = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
            $date_dropped_off_for_hours_cal = new Carbon($sessionVals['dropoff_date'] . ' ' . $sessionVals['dropoff_time']);
            $search_by['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

            if ($request->has('category_id')) {
                $category_id = $request->input('category_id');
                Session::put('categoryId', $category_id); // being used for pricing filtering
                Session::save();
            } else {
                $category_id = Session::get('categoryId'); // being used for pricing filtering
            }
            $search_by['category'] = ($category_id > 0 ? $category_id : 0);
            if ($category_id < 0) {
                if ($category_id == -1) {
                    // car price ascending
                    $car_price_sort = 'asc';
                } elseif ($category_id == -2) {
                    // car price descending
                    $car_price_sort = 'desc';
                }
            } else {
                // car price ascending
                $car_price_sort = 'asc';
            }
            if ($request->session()->has('user_company_code') && $request->session()->get('user_company_code') != '') {
                $search_by['company_code'] = $request->session()->get('user_company_code');
            }
            $offset = "0";
            $limit = "10";
            $search_by['pickup_date'] = date('Y-m-d', strtotime($search_by['pickup_date']));

            $corporate_quotation_cars_array = [];
            if (Session::has('corporate_customer_id')) {
                $corporate_customer_detail = DB::table('corporate_customer')->where('id', Session::get('corporate_customer_id'))->first();
                if ($corporate_customer_detail->has_price_with_quotation == 'Yes') {
                    $corporate_quotation_cars = $this->page->getCorporateQuotationPrices($search_by, $corporate_customer_detail->id, "", $offset, $limit, Session::get('loyalty_discount_percent'), $car_price_sort);
                    if ($corporate_quotation_cars) {
                        foreach ($corporate_quotation_cars as $corporate_quotation_car) {
                            $corporate_quotation_cars_array[] = $corporate_quotation_car->id;
                        }
                    }
                }
            }

            $cars_rows = $this->page->getAllCarModels($search_by, 'rent', "", $offset, $limit, Session::get('loyalty_discount_percent'), $car_price_sort, $corporate_quotation_cars_array);

            if (isset($corporate_quotation_cars) && $corporate_quotation_cars) {
                $cars = array_merge($corporate_quotation_cars, $cars_rows);
            } else {
                $cars = $cars_rows;
            }

            if (count($cars) > 0) {
                $html = custom::searchResultPageHtml($cars, $this->base_url, $this->lang_base_url, $this->lang);
            } else {
                $html = '<div class="noResultFound"><span>' . Lang::get('labels.no_record_found') . '</span></div>';
            }
            echo $html;
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    //kashif work
    public function search_cars_with_all_fields(Request $request)
    {
        try {
            $lang = $this->lang;
            $site_settings = custom::site_settings();
            //$lang = $request->input('lang');
            //app()->setLocale($lang);
            //print_r($request->all()); exit;
            $limit = "10";
            $inputs = $request->all();
            $inputs['pickup_date'] = "";
            $inputs['offset'] = "0";
            $results = $this->page->getCarsByAllFilters($inputs, $limit);
            $cars = json_decode(json_encode($results), true);
            $html = '';
            if (count($cars) > 0) {
                foreach ($cars as $car) {

                    $disablility_html = '';
                    if ($car['is_for_disabled'] == 1) {
                        $disablility_html = '<div class="disability-div"><img src="'.$this->base_url.'/public/frontend/images/web-disability-'.$this->lang.'.png"></div>';
                    }

                    $singleRowPaddingCss = '';
                    // check if car has any redeem offer setup and active against it. Criteria for checking is as follows:
                    // 1. It must be having the from region id as region id.
                    // 2. It must be having the car id thats in search results.
                    // 3. Percentage of open contracts, means to check how many number of open contracts are there for this car model, it must be less than prescribed ones.
                    if ($site_settings->redeem_offer_mode == 'on') { // if redeem offer mode is enabled from backend
                        // $sessionVals['from_region_id'], $car->id
                        $car_info = $this->page->getSingle('car_model', array('id' => $car['id']));

                        $redeemOfferAvailable = $this->page->checkIfRedeemAllowed($inputs['from_region_id'], $car_info->car_type_id, $car['id'], $inputs['pickup_date']);
                        if ($redeemOfferAvailable) {
                            $no_of_currently_open_contracts = $this->page->getNoOfOpenContracts($car['id'], $inputs['from_region_id']); // Getting number of open contracts for the car model at present.
                            $no_of_cars_present = $redeemOfferAvailable->no_of_cars_present;
                            $percentage_of_open_contracts = $redeemOfferAvailable->percentage_of_open_contracts;
                            $no_of_open_contracts_allowed = ($percentage_of_open_contracts / 100) * $no_of_cars_present;
                            if ($no_of_currently_open_contracts < $no_of_open_contracts_allowed) {
                                $hasRedeemOffer = true;
                                $redeemHtml = '<div class="redeemText"><span>' . ($lang == 'eng' ? 'Redeem Offer Available' : 'عرض استبدال نقاط الولاء') . '</span><img src="' . $this->base_url . '/public/frontend/images/redeem_offer_logo.png?v=0.1" class="redeemOfferImage"></div>';
                                $singleRowPaddingCss = 'padding-top: 36px;';
                            } else {
                                $hasRedeemOffer = false;
                                $redeemHtml = '';
                            }
                        } else {
                            $hasRedeemOffer = false;
                            $redeemHtml = '';
                        }
                    } else {
                        $hasRedeemOffer = false;
                        $redeemHtml = '';
                    }
                    if (!custom::is_mobile()) {
                        $html .= '<div class="singleRow" style="' . $singleRowPaddingCss . '">
' . $redeemHtml . '
                <div class="imgBox">
                    <div class="listViewCarImg">
                        <img src="' . $this->base_url . '/public/uploads/' . $car['image1'] . '" alt="' . ($lang == 'eng' ? $car['image1_eng_alt'] : $car['image1_arb_alt']) . '" height="132" width="274" />
                    </div>
                </div>
                
                <div class="bookDtlSec">
                    <div class="bookPSec">
                        <div class="bookFeature ' . ($car["min_age"] > 0 ? 'contains-min-age' : '') . '">
                        <h2>' . $car["ct_" . $lang . "_title"] . ' ' . $car[$lang . "_title"] . ' ' . $car["year"] . ' <span>' . \Lang::get('labels.or_similar') . '</span>
							</h2>
							<h3>' . $car["cc_" . $lang . "_title"] . '</h3>
                            <div class="gridViewCarImg">
                                 <img src="' . $this->base_url . '/public/uploads/' . $car['image1'] . '" alt="' . ($lang == 'eng' ? $car['image1_eng_alt'] : $car['image1_arb_alt']) . '"/>
                            </div>	
                            '.$disablility_html.'						
                            <ul>
                                <li><div class="spIconF person"></div>		<p>' . $car["no_of_passengers"] . '</p></li>
                                <li><div class="spIconF transmition"></div>	<p>' . $car["transmission"] . '</p></li>
                                <li><div class="spIconF door"></div>		<p>' . $car["no_of_doors"] . '</p></li>
                                <li><div class="spIconF bag"></div>			<p>' . $car["no_of_bags"] . '</p></li>';
                        if ($car["min_age"] > 0) {
                            $html .= '<li><div class="spIconF minAge"></div>			<p>' . $car["min_age"] . '</p></li>';
                        }
                        $html .= ' </ul>
                        </div>
                        <div class="col bookBtn">
                            <a href="' . $this->lang_base_url . '/fleet/booking/' . $car['id'] . '"><input type="button" class="edBtn" value="' . Lang::get('labels.book_now_btn') . '" ></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>';
                        if ($car['is_special_car'] == 'yes') {
                            $html .= ' <div class="tagSpecialCar" >
                           <img src="' . $this->base_url . '/public/frontend/images/' . $lang . '_specialFeature.png?v=?' . rand() . '">
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car['id'] . '" ></a >
                        </div >';
                        }
                        $html .= '</div>';

                    } else {
                        //start mobile verison html
                        $html .= '<div class="singleRow" style="' . $singleRowPaddingCss . '">
                    ' . $redeemHtml;
                        if ($car['is_special_car'] == 'yes') {
                            $html .= ' <div class="tagSpecialCar" >
                           <img src="' . $this->base_url . '/public/frontend/images/specialFeature_mobile.png?v=?' . rand() . '">
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car['id'] . '" ></a >
                        </div >';
                        }

                        $html .= '<a href="' . $this->lang_base_url . '/fleet/booking/' . $car['id'] . '">
                <h2>' . $car["ct_" . $lang . "_title"] . ' ' . $car[$lang . "_title"] . ' ' . $car["year"] . '</h2>
                <h3>' . $car["cc_" . $lang . "_title"] . '</h3>
                <div class="imgBox">
                    <img src="' . $this->base_url . '/public/uploads/' . $car['image1'] . '" alt="' . ($lang == 'eng' ? $car['image1_eng_alt'] : $car['image1_arb_alt']) . '" height="132" width="274" />
                </div>
                </a>';

                        $html .= '</div>';
                    }
                }
            } else {
                $html .= '<div class="noResultFound"><span>' . Lang::get('labels.no_record_found') . '</span></div>';
            }
            echo $html;
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function fleetBooking($id)
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'fleet';
            $data['mod_id'] = $id;
            $data['show_mod_id'] = true;
            $data['has_searchbar'] = true;
            $regionArr = array();
            $regions = $this->page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $stdObj = $this->page->getAllCars($id);
            $data['car_models'] = json_decode(json_encode($stdObj), true);
            if (custom::is_mobile()) {
                return view('frontend.mobile.search_fleet')->with($data);
            } else {
                return view('frontend.search_fleet')->with($data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // end kashif work 7-april-2017
    public function extra_services()
    {
        try {
            if (Session::get('search_data') == "") {
                return redirect($this->lang_base_url . '/home');
            }
            // setting value added tax values in session to use it in other places. I have cleared them in above custom functions.
            $site_setting = custom::site_settings();
            Session::put('vat_mode', $site_setting->vat_mode);
            Session::put('vat_percentage', $site_setting->vat_percentage);
            Session::save();
            Session::forget('vat');
            Session::forget('totalPriceWithVat');
            Session::save();
            $regionArr = array();
            $page = new Page();
            $sessionVals = Session::get('search_data');

            $search_by['book_for_hours'] = $book_for_hours = (isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] != '' ? $sessionVals['book_for_hours'] : 0);
            if ($sessionVals['is_delivery_mode'] == 2 && $book_for_hours > 0) {
                $pickup_date_time = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addHours($book_for_hours);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionVals['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionVals['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $search_by['subscribe_for_months'] = $subscribe_for_months = (isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] != '' ? $sessionVals['subscribe_for_months'] : 0);
            if ($sessionVals['is_delivery_mode'] == 4 && $subscribe_for_months > 0) {
                $pickup_date_time = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
                $dropoff_date_time = $pickup_date_time->addDays($subscribe_for_months * 30);
                $explode_dropoff_date_time = explode(' ', $dropoff_date_time);
                $sessionVals['dropoff_date'] = date('d-m-Y', strtotime($explode_dropoff_date_time[0]));
                $sessionVals['dropoff_time'] = $dropoff_time = date('h:iA', strtotime($explode_dropoff_date_time[1]));
            }

            $data['booking_info'] = $sessionVals;
            $id = Session::get('car_id');
            $data['car_info'] = $this->page->getSingleCarDetail($id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['from_branch_id']);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['to_branch_id']);
            $regions = $page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }

            //using to get drop-off-charges w.r.t customer_loyalty_type
            $user_loyalty = Session::get('loyalty_type_used');

            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $search_by['region_id'] = $sessionVals['from_region_id'];
            $search_by['city_id'] = $sessionVals['from_city_id'];
            $search_by['branch_id'] = $sessionVals['from_branch_id'];
            $search_by['days'] = $sessionVals['days'];
            $search_by['pickup_date'] = $sessionVals['pickup_date'];
            $search_by['customer_type'] = $sessionVals['customer_type'];
            $search_by['category'] = $sessionVals['category'];
            $search_by['pickup_date'] = date('Y-m-d', strtotime($search_by['pickup_date']));
            $search_by['is_delivery_mode'] = $sessionVals['is_delivery_mode'];
            $search_by['hours_diff'] = $sessionVals['hours_diff'];
            $search_by['isLimousine'] = (isset($sessionVals['isLimousine']) ? $sessionVals['isLimousine'] : 0);
            $search_by['isRoundTripForLimousine'] = (isset($sessionVals['isRoundTripForLimousine']) ? $sessionVals['isRoundTripForLimousine'] : 0);

            $extra_charges = false;
            if (Session::has('corporate_customer_id')) {
                $corporate_customer_detail = DB::table('corporate_customer')->where('id', Session::get('corporate_customer_id'))->first();
                if ($corporate_customer_detail->has_price_with_quotation == 'Yes') {
                    $extra_charges = custom::get_corporate_quotation_prices_extras_for_car($id, $corporate_customer_detail->id, $search_by);
                    // custom::dump($extra_charges);
                }
            }

            if (!$extra_charges) {
                $extra_charges = $this->page->getAllCarModels($search_by, 'extras');
            }

            // custom::dump($extra_charges);

            $data['extra_charges'] = $extra_charges;


            $data['base_url'] = $this->base_url;
            if ($sessionVals['from_city_id'] != $sessionVals['to_city_id']) {
                $dropoff_charges = $this->page->getDropoffCharges(date('Y-m-d', strtotime($sessionVals['pickup_date'])), $sessionVals['from_city_id'], $sessionVals['to_city_id'], $user_loyalty);
            } else {
                $dropoff_charges = false;
            }
            $dropoff_charges_price = ($dropoff_charges ? (int)$dropoff_charges[0]->price : 0);
            $data['dropoff_charges'] = $dropoff_charges;
            $data['dropoff_charges_price'] = $dropoff_charges_price;
            Session::put('dropoff_charges_amount', $dropoff_charges_price);
            Session::put('total_rent_for_all_days', Session::get('rent_per_day') * ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days'])); // Only rent multiplied by days
            Session::save();
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'extra-services'));
            $data['active_menu'] = 'price';
            $new_rent_per_day = Session::get('rent_per_day');
            $old_rent_per_day = Session::get('old_price');
            $loyalty_discount_percentage = ((int)Session::get('loyalty_discount_percent') != '' ? (int)Session::get('loyalty_discount_percent') : 0);
            if (Session::get('logged_in_from_frontend') == true && Session::get('user_type') == "corporate_customer") {
                $customer_type_for_auto_promo = "Corporate";
            } else {
                $customer_type_for_auto_promo = "Individual";
            }
            $promo_discount = $page->checkAutoPromoDiscount(Session::get('car_id'), date('Y-m-d H:i:s', strtotime($sessionVals['pickup_date'])), $sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id'], Session::get('search_data')['days'], $customer_type_for_auto_promo, $sessionVals['is_delivery_mode']);

            $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount, $sessionVals['pickup_date']);

            if ($promo_discount && $promo_discount->discount > 0 && $coupon_is_valid_for_pickup_day) {
                if ($promo_discount->type == 'Fixed Price Auto Apply') {
                    $after_fixed_price_discount = round($old_rent_per_day - $promo_discount->discount, 2);
                    if ($after_fixed_price_discount < $new_rent_per_day) {
                        $new_rent_per_day = $after_fixed_price_discount;
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply') {
                    $promo_discount_percent = $promo_discount->discount;
                    if ($promo_discount_percent > $loyalty_discount_percentage) {
                        $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                        $new_rent_per_day = round($old_rent_per_day - $discount_amount, 2);
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply on Loyalty') {
                    $promo_discount_percent = $promo_discount->discount + $loyalty_discount_percentage;
                    $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                    $new_rent_per_day = round($old_rent_per_day - $discount_amount, 2);
                } elseif ($promo_discount->type == 'Fixed Daily Rate Auto Apply') {
                    $after_fixed_price_discount = $promo_discount->discount;
                    if ($after_fixed_price_discount < $new_rent_per_day) {
                        $new_rent_per_day = round($after_fixed_price_discount, 2);
                    }
                }
            }
            // custom::dump($data);
            $data['rent_p_day_loya_vs_dis'] = $new_rent_per_day;

            $data['returnToHome'] = 1;

            if (custom::is_mobile()) {
                return view('frontend/mobile/price', $data);
            } else {
                return view('frontend/price', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function redirectToExtraServicesPage(Request $request)
    {
        // clearing promo code applied values from session
        try {
            $car_model_id = $request->input('car_model_id');

            $request_data_params = $request->all();
            $car_prices_data_to_check = Session::get('car_prices_data_to_check_' . $car_model_id);

            $site_settings = custom::site_settings();
            if (
                ($site_settings->forcefully_recheck_prices == 0) ||
                ($site_settings->forcefully_recheck_prices == 1 &&
                    $request_data_params['price'] == $car_prices_data_to_check['price'] &&
                    $request_data_params['old_price'] == $car_prices_data_to_check['old_price'] &&
                    $request_data_params['car_rate_is_with_additional_utilization_rate'] == $car_prices_data_to_check['car_rate_is_with_additional_utilization_rate'] &&
                    $request_data_params['extra_hours_rate_for_limousine'] == $car_prices_data_to_check['extra_hours_rate_for_limousine'])
            ) {
                Session::forget('promo_discount_amount');
                Session::forget('total_amount_after_discount');
                Session::forget('coupon_applied');
                Session::forget('is_promo_discount_on_total');
                Session::forget('coupon_code');
                Session::forget('promotion_id');
                Session::forget('renting_type_id');
                Session::forget('car_rate_is_with_additional_utilization_rate');
                Session::forget('car_prices_data_to_check_' . $car_model_id);
                Session::save();
                $car_model_id = $request->input('car_model_id');
                $cpid = $request->input('cpid');
                $renting_type_id = $request->input('renting_type_id');
                $price = $request->input('price');
                $old_price = $request->input('old_price');
                $car_rate_is_with_additional_utilization_rate = $request->input('car_rate_is_with_additional_utilization_rate');
                $extra_hours_rate_for_limousine = $request->input('extra_hours_rate_for_limousine');
                Session::put('car_id', $car_model_id);
                Session::put('cpid', $cpid);
                Session::put('rent_per_day', $price);
                Session::put('old_price', $old_price);
                Session::put('renting_type_id', $renting_type_id);
                Session::put('car_rate_is_with_additional_utilization_rate', $car_rate_is_with_additional_utilization_rate);
                Session::put('extra_hours_rate_for_limousine', $extra_hours_rate_for_limousine);
                Session::save();
                //echo true;
                return redirect($this->lang_base_url . '/extra-services');
            } else {
                return redirect($this->lang_base_url);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function payment(Request $request)
    {
        try {
            $request_data_params = $request->all();
            $car_extra_prices_data_to_check = Session::get('car_extra_prices_data_to_check');

            if (Session::get('search_data.is_delivery_mode') == 4) {
                $request_data_params['total_rent_hdn'] = (int)($request_data_params['total_rent_hdn'] * 1);
                $car_extra_prices_data_to_check['total_rent_hdn'] = (int)($car_extra_prices_data_to_check['total_rent_hdn'] * 1);
            }

            $site_settings = custom::site_settings();
            if (
                ($site_settings->forcefully_recheck_prices == 0) ||
                ($site_settings->forcefully_recheck_prices == 1 &&
                $request_data_params['total_rent_hdn'] == $car_extra_prices_data_to_check['total_rent_hdn'] &&
                $request_data_params['totalPriceWithVat'] == $car_extra_prices_data_to_check['totalPriceWithVat']/* &&
                $request_data_params['vat_percentage'] == $car_extra_prices_data_to_check['vat_percentage'] &&
                round($request_data_params['vat'], 2) == round($car_extra_prices_data_to_check['vat'], 2)*/)
            ) {
                Session::forget('car_extra_prices_data_to_check');
                Session::save();

                if (Session::get('logged_in_from_frontend') == true && Session::get('user_type') == "corporate_customer") {
                    $customer_type_for_auto_promo = "Corporate";
                } else {
                    $customer_type_for_auto_promo = "Individual";
                }
                //exit();
                if (Session::get('search_data') == "") {
                    return redirect($this->lang_base_url . '/home');
                }

                if (Session::has('qitaf_request')) {
                    Session::forget('qitaf_request');
                    Session::forget('qitaf_amount');
                    Session::save();
                }

                if (Session::has('niqaty_request')) {
                    Session::forget('niqaty_request');
                    Session::forget('niqaty_amount');
                    Session::save();
                }

                if (Session::has('mokafaa_request')) {
                    Session::forget('mokafaa_request');
                    Session::forget('mokafaa_amount');
                    Session::save();
                }

                if (Session::has('anb_request')) {
                    Session::forget('anb_request');
                    Session::forget('anb_amount');
                    Session::save();
                }

                //echo '<pre>';print_r($request->input());exit();
                $site_settings = custom::site_settings();
                $vat_percentage = $site_settings->vat_percentage;
                $user_info = array();
                $data = array();
                $minus_discount = true;
                $userIsOkToUseRedeemOffer = false;
                $sessionVals = Session::get('search_data');
                $dropoff_charges = Session::get('dropoff_charges_amount');
                $delivery_charges = ($sessionVals['delivery_charges'] ? $sessionVals['delivery_charges'] : 0);
                $parking_fee = $sessionVals['parking_fee'];
                $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];
                $limousine_extra_charges = custom::get_limousine_extra_charges();
                $waiting_extra_hours = $limousine_extra_charges['waiting_extra_hours'];
                $waiting_extra_hours_charges = $limousine_extra_charges['waiting_extra_hours_charges'];
                $promotion_id = 0;

                $days = $sessionVals['days'];
                $hours_diff = $sessionVals['hours_diff'];
                $is_delivery_mode = $sessionVals['is_delivery_mode'];

                if ($is_delivery_mode == 4) {
                    $sessionVals['days'] = 30; // because 1 month is to be charged
                    $days = $sessionVals['days'];
                }

                if ($request->isMethod('post')) {
                    $cdw = 0;
                    $cdw_is_one_time_applicable_on_booking = 0;

                    $cdw_plus = 0;
                    $cdw_plus_is_one_time_applicable_on_booking = 0;

                    $gps = 0;
                    $gps_is_one_time_applicable_on_booking = 0;

                    $extra_driver = 0;
                    $extra_driver_is_one_time_applicable_on_booking = 0;

                    $baby_seat = 0;
                    $baby_seat_is_one_time_applicable_on_booking = 0;

                    if ($request->input('cdw')) {
                        $cdw = ($is_delivery_mode == 4 ? $request->input('cdw') : (int)$request->input('cdw'));
                        $cdw_is_one_time_applicable_on_booking = (int)$request->input('cdw_is_one_time_applicable_on_booking');
                    }
                    if ($request->input('cdw_plus')) {
                        $cdw_plus = ($is_delivery_mode == 4 ? $request->input('cdw_plus') : (int)$request->input('cdw_plus'));
                        $cdw_plus_is_one_time_applicable_on_booking = (int)$request->input('cdw_plus_is_one_time_applicable_on_booking');
                    }
                    if ($request->input('gps')) {
                        $gps = ($is_delivery_mode == 4 ? $request->input('gps') : (int)$request->input('gps'));
                        $gps_is_one_time_applicable_on_booking = (int)$request->input('gps_is_one_time_applicable_on_booking');
                    }
                    if ($request->input('extra_driver')) {
                        $extra_driver = ($is_delivery_mode == 4 ? $request->input('extra_driver') : (int)$request->input('extra_driver'));
                        $extra_driver_is_one_time_applicable_on_booking = (int)$request->input('extra_driver_is_one_time_applicable_on_booking');
                    }
                    if ($request->input('baby_seat')) {
                        $baby_seat = ($is_delivery_mode == 4 ? $request->input('baby_seat') : (int)$request->input('baby_seat'));
                        $baby_seat_is_one_time_applicable_on_booking = (int)$request->input('baby_seat_is_one_time_applicable_on_booking');
                    }
                    // Saving all values in session
                    Session::put('cdw_charges', $cdw);
                    Session::put('cdw_charges_is_one_time_applicable_on_booking', $cdw_is_one_time_applicable_on_booking);

                    Session::put('cdw_plus_charges', $cdw_plus);
                    Session::put('cdw_plus_charges_is_one_time_applicable_on_booking', $cdw_plus_is_one_time_applicable_on_booking);

                    Session::put('gps_charges', $gps);
                    Session::put('gps_charges_is_one_time_applicable_on_booking', $gps_is_one_time_applicable_on_booking);

                    Session::put('extra_driver_charges', $extra_driver);
                    Session::put('extra_driver_charges_is_one_time_applicable_on_booking', $extra_driver_is_one_time_applicable_on_booking);

                    Session::put('baby_seat_charges', $baby_seat);
                    Session::put('baby_seat_charges_is_one_time_applicable_on_booking', $baby_seat_is_one_time_applicable_on_booking);
                    Session::save();
                } else {

                    $cdw = Session::get('cdw_charges');
                    $cdw_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

                    $cdw_plus = Session::get('cdw_plus_charges');
                    $cdw_plus_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

                    $gps = Session::get('gps_charges');
                    $gps_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

                    $extra_driver = Session::get('extra_driver_charges');
                    $extra_driver_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

                    $baby_seat = Session::get('baby_seat_charges');
                    $baby_seat_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');
                }

                if ($cdw_is_one_time_applicable_on_booking == 1) {
                    $cdw_multiply_factor = 1;
                } else {
                    $cdw_multiply_factor = ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']);
                }

                if ($cdw_plus_is_one_time_applicable_on_booking == 1) {
                    $cdw_plus_multiply_factor = 1;
                } else {
                    $cdw_plus_multiply_factor = ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']);
                }

                if ($gps_is_one_time_applicable_on_booking == 1) {
                    $gps_multiply_factor = 1;
                } else {
                    $gps_multiply_factor = ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']);
                }

                if ($extra_driver_is_one_time_applicable_on_booking == 1) {
                    $extra_driver_multiply_factor = 1;
                } else {
                    $extra_driver_multiply_factor = ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']);
                }

                if ($baby_seat_is_one_time_applicable_on_booking == 1) {
                    $baby_seat_multiply_factor = 1;
                } else {
                    $baby_seat_multiply_factor = ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']);
                }

                /////////////////////////////////////////////
                if (Session::get('coupon_applied') != true) {
                    $new_rent_per_day = Session::get('rent_per_day');
                    $old_rent_per_day = Session::get('old_price');
                    $loyalty_discount_percentage = ((int)Session::get('loyalty_discount_percent') != '' ? (int)Session::get('loyalty_discount_percent') : 0);
                    $promo_discount_amount = 0;
                    $total_amount_for_all_days = ($new_rent_per_day * ($sessionVals['is_delivery_mode'] == 2 ? 1 : $sessionVals['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges;
                    $promo_discount = $this->page->checkAutoPromoDiscount(Session::get('car_id'), date('Y-m-d H:i:s', strtotime($sessionVals['pickup_date'])), $sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id'], Session::get('search_data')['days'], $customer_type_for_auto_promo, $sessionVals['is_delivery_mode']);

                    $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount, $sessionVals['pickup_date']);
                    // dump($promo_discount);die();

                    if ($promo_discount && $promo_discount->discount > 0 && $coupon_is_valid_for_pickup_day) {
                        if ($promo_discount && $promo_discount->type == 'Fixed Price Auto Apply') {
                            $data['promo_discount'] = $promo_discount;
                            $promo_discount_fixed = $promo_discount->discount;
                            $calculated_rent_per_day_after_discount = round($old_rent_per_day - $promo_discount_fixed, 2);
                            if ($calculated_rent_per_day_after_discount < $new_rent_per_day) {
                                $promotion_id = $promo_discount->id;
                                $promo_discount_amount = $promo_discount->discount;
                                $total_amount_for_all_days = round(($calculated_rent_per_day_after_discount * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2);
                                Session::put('rent_per_day', $old_rent_per_day);
                                Session::save();
                            }
                        } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply') {
                            $data['promo_discount'] = $promo_discount;
                            $promo_discount_percent = $promo_discount->discount;
                            if ($promo_discount_percent > $loyalty_discount_percentage) {
                                $promotion_id = $promo_discount->id;
                                $promo_discount_amount = round($promo_discount_percent * $old_rent_per_day / 100, 2);
                                $calculated_rent_per_day_after_discount = round(($old_rent_per_day - $promo_discount_amount), 2);
                                $total_amount_for_all_days = round(($calculated_rent_per_day_after_discount * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2);
                                Session::put('rent_per_day', $old_rent_per_day);
                                Session::save();
                            }
                        } elseif ($promo_discount && $promo_discount->type == 'Percentage Auto Apply on Loyalty') {
                            $data['promo_discount'] = $promo_discount;
                            $promo_discount_percent = $promo_discount->discount + $loyalty_discount_percentage;
                            $promotion_id = $promo_discount->id;
                            $promo_discount_amount = round($promo_discount_percent * $old_rent_per_day / 100, 2);
                            $calculated_rent_per_day_after_discount = round(($old_rent_per_day - $promo_discount_amount), 2);
                            $total_amount_for_all_days = round(($calculated_rent_per_day_after_discount * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2);
                            Session::put('rent_per_day', $old_rent_per_day);
                            Session::save();
                        } elseif ($promo_discount && $promo_discount->type == 'Fixed Daily Rate Auto Apply') {
                            $data['promo_discount'] = $promo_discount;
                            $rent_per_day_after_fixed_price_discount = $promo_discount->discount;
                            if ($rent_per_day_after_fixed_price_discount < $new_rent_per_day) {
                                $promotion_id = $promo_discount->id;
                                $promo_discount_amount = round($old_rent_per_day - $rent_per_day_after_fixed_price_discount, 2);
                                $total_amount_for_all_days = round(($rent_per_day_after_fixed_price_discount * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2);
                                Session::put('rent_per_day', $old_rent_per_day);
                                Session::put('daily_rate_coupon_applied', true); // status for daily rate coupon applied
                                Session::save();
                                $minus_discount = false;
                            }
                        }
                    }
                    // echo $promotion_id;die();
                    Session::put('promotion_id', $promotion_id);
                    Session::put('minus_discount', $minus_discount);
                    Session::put('promo_discount_amount', $promo_discount_amount);
                    Session::put('total_amount_after_discount', $total_amount_for_all_days);
                    Session::save();
                    $data['minus_discount'] = $minus_discount;
                    $data['promo_discount_amount'] = $promo_discount_amount;
                    $data['total_amount_after_discount'] = $total_amount_for_all_days;
                } else {
                    $data['minus_discount'] = $minus_discount;
                    $data['promo_discount_amount'] = (Session::has('promo_discount_amount') && Session::get('promo_discount_amount') > 0 ? Session::get('promo_discount_amount') : 0);
                    $data['total_amount_after_discount'] = Session::get('total_amount_after_discount');
                }
                if (Session::has('promo_discount_amount') && Session::get('promo_discount_amount') > 0) // if promo applied and page being refreshed
                {
                    $promo_discount_amount_on_payment = Session::get('promo_discount_amount');
                } else {
                    $promo_discount_amount_on_payment = 0;
                }
                $parking_fee = Session::get('search_data')['parking_fee'];
                $tamm_charges_for_branch = Session::get('search_data')['tamm_charges_for_branch'];
                $limousine_extra_charges = custom::get_limousine_extra_charges();
                $waiting_extra_hours = $limousine_extra_charges['waiting_extra_hours'];
                $waiting_extra_hours_charges = $limousine_extra_charges['waiting_extra_hours_charges'];
                $totalPriceWithoutVat = (((Session::get('rent_per_day') * ($is_delivery_mode == 2 ? 1 : $days)) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) - ($promo_discount_amount_on_payment * ($is_delivery_mode == 2 ? 1 : $days)))) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges;
                // echo $totalPriceWithoutVat;
                $vat = ($vat_percentage / 100) * $totalPriceWithoutVat;
                $totalPriceWithVat = (((Session::get('rent_per_day') * ($is_delivery_mode == 2 ? 1 : $days)) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) - ($promo_discount_amount_on_payment * ($is_delivery_mode == 2 ? 1 : $days))) * ($is_delivery_mode == 2 ? 1 : $days)) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $vat;
                Session::put('vat', $vat);
                Session::put('vat_percentage', $vat_percentage);
                Session::put('totalPriceWithoutVat', $totalPriceWithoutVat);
                Session::put('totalPriceWithVat', $totalPriceWithVat);
                Session::save();
                /////////////////////////////////////////////////////////////////////////////////////////
                if (Session::get('logged_in_from_frontend') && Session::get('logged_in_from_frontend') == true && Session::get('user_type') == 'individual_customer') {
                    $fetch_user_info_by['id'] = Session::get('individual_customer_id');
                    $user_info = $this->page->getSingle('individual_customer', $fetch_user_info_by);
                    if ($user_info->loyalty_card_type != '' && $user_info->loyalty_card_type != 'Bronze') {
                        $userIsOkToUseRedeemOffer = true;
                    }
                } elseif (Session::has('verify_at_search_field_for_loyalty') && Session::get('verify_at_search_field_for_loyalty') == true) {
                    $fetch_user_info_by['id'] = Session::get('verified_user_id');
                    $user_info = $this->page->getSingle('individual_customer', $fetch_user_info_by);
                    if ($user_info->loyalty_card_type != '' && $user_info->loyalty_card_type != 'Bronze') {
                        $userIsOkToUseRedeemOffer = true;
                    }
                } elseif (Session::get('edit_booking_id') != '') {
                    $edit_booking_id = Session::get('edit_booking_id');
                    $edit_booking_data = $this->page->getSingle('booking', array('id' => $edit_booking_id));
                    if ($edit_booking_data->type == 'individual_customer') {
                        $ind_cus_book_data = $this->page->getSingle('booking_individual_user', array('booking_id' => $edit_booking_id));
                        $userId = $ind_cus_book_data->uid;
                        $user_info = $this->page->getSingle('individual_customer', array('uid' => $userId));
                    } elseif ($edit_booking_data->type == 'guest') {
                        $guest_cus_book_data = $this->page->getSingle('booking_individual_guest', array('booking_id' => $edit_booking_id));
                        $userId = $guest_cus_book_data->individual_customer_id;
                        $user_info = $this->page->getSingle('individual_customer', array('id' => $userId));
                    }
                } elseif (Session::has('unfilled_survey_customer_id') && Session::get('unfilled_survey_customer_id') != '') {
                    $user_info = $this->page->getSingle('individual_customer', array('id' => Session::get('unfilled_survey_customer_id')));
                }
                $regions = $this->page->getRegions();
                foreach ($regions as $key => $region) {
                    if ($this->lang == "eng") {
                        $city = $region->cit . '|' . $region->c_eng_title;
                    } else {
                        $city = $region->cit . '|' . $region->c_arb_title;
                    }
                    $regionArr[$city][] = $region;
                }
                $data['pickup_regions'] = $regionArr;
                $data['dropoff_regions'] = $regionArr;
                // getDeliveryRegions
                $regions = $this->page->getDeliveryRegions();
                $regionArr = array();
                foreach ($regions as $key => $region) {
                    if ($this->lang == "eng") {
                        $city = $region->cit . '|' . $region->c_eng_title;
                    } else {
                        $city = $region->cit . '|' . $region->c_arb_title;
                    }
                    $regionArr[$city][] = $region;
                }
                // echo '<pre>';print_r($regionArr);exit();
                $data['delivery_pickup_regions'] = $regionArr;
                $data['delivery_dropoff_regions'] = $regionArr;
                $sessionVals = Session::get('search_data');
                $data['booking_info'] = $sessionVals;
                $id = Session::get('car_id');
                $data['car_info'] = $this->page->getSingleCarDetail($id);
                $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['from_branch_id']);
                $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['to_branch_id']);
                $data['base_url'] = $this->base_url;
                $data['lang_base_url'] = $this->lang_base_url;
                $data['lang'] = $this->lang;
                $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'payment'));
                $data['active_menu'] = 'payment';
                $data['user_info'] = $user_info;

                $data['cdw'] = $cdw;
                $data['cdw_is_one_time_applicable_on_booking'] = $cdw_is_one_time_applicable_on_booking;
                $data['cdw_multiply_factor'] = $cdw_multiply_factor;

                $data['cdw_plus'] = $cdw_plus;
                $data['cdw_plus_is_one_time_applicable_on_booking'] = $cdw_plus_is_one_time_applicable_on_booking;
                $data['cdw_plus_multiply_factor'] = $cdw_plus_multiply_factor;

                $data['gps'] = $gps;
                $data['gps_is_one_time_applicable_on_booking'] = $gps_is_one_time_applicable_on_booking;
                $data['gps_multiply_factor'] = $gps_multiply_factor;

                $data['extra_driver'] = $extra_driver;
                $data['extra_driver_is_one_time_applicable_on_booking'] = $extra_driver_is_one_time_applicable_on_booking;
                $data['extra_driver_multiply_factor'] = $extra_driver_multiply_factor;

                $data['baby_seat'] = $baby_seat;
                $data['baby_seat_is_one_time_applicable_on_booking'] = $baby_seat_is_one_time_applicable_on_booking;
                $data['baby_seat_multiply_factor'] = $baby_seat_multiply_factor;

                $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
                $data['site_settings'] = custom::site_settings();
                if (Session::get('logged_in_from_frontend') == true && Session::get('user_type') == 'corporate_customer') {
                    $data['corporate_customer_info'] = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
                    $orderCountriesBy = ($this->lang == "eng" ? "eng_country" : "arb_country");
                    $data['countries'] = $this->page->getAll('country', $orderCountriesBy);
                    $data['nationalities'] = $this->page->getAllNationalities($this->lang);
                    $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
                    if (custom::is_mobile()) {
                        $payment_view = 'frontend/mobile/corporate-payment';
                    } else {
                        $payment_view = 'frontend/corporate-payment';
                    }
                } else {
                    if (custom::is_mobile()) {
                        $payment_view = 'frontend/mobile/payment';
                    } else {
                        $payment_view = 'frontend/payment';
                    }
                }
                // checking if user can use redeem points
                if ($userIsOkToUseRedeemOffer) //if user is logged in and is individual customer OR trying his loyalty through popup AND has silver, golden or platinum loyalty type.
                {
                    // set in session user's loyalty type
                    Session::put('customer_redeem_loyalty_type', $user_info->loyalty_card_type);
                    $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
                    $redeemOfferAvailable = $this->page->checkIfRedeemAllowed($sessionVals['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $sessionVals['pickup_date']);
                    if ($redeemOfferAvailable) {
                        $no_of_currently_open_contracts = $this->page->getNoOfOpenContracts(Session::get('car_id'), $sessionVals['from_region_id']); // Getting number of open contracts for the car model at present.
                        $no_of_cars_present_of_this_model = $redeemOfferAvailable->no_of_cars_present;
                        $percentage_of_open_contracts = $redeemOfferAvailable->percentage_of_open_contracts;
                        $no_of_open_contracts_allowed = ($percentage_of_open_contracts / 100) * $no_of_cars_present_of_this_model;
                        if ($no_of_currently_open_contracts < $no_of_open_contracts_allowed) {
                            $canUseRedeemOffer = true;
                            $redeemPointsAvailableForUser = $user_info->loyalty_points;
                            $redeemLoyaltyTypeForUser = $user_info->loyalty_card_type;
                            $MaxRedeemablePoints = $this->MaxRedeemablePoints($redeemLoyaltyTypeForUser);
                            $MaxRedeemableAmount = $this->MaxRedeemableAmount();
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
                } else {
                    $canUseRedeemOffer = false;
                    $redeemPointsAvailableForUser = 0;
                    $redeemLoyaltyTypeForUser = "";
                    $MaxRedeemablePoints = 0;
                    $MaxRedeemableAmount = 0;
                }
                $days_for_checkout = Session::get('search_data')['days'];
                $allowed_days_for_redeem = $site_settings->days_for_redeem;
                if ($days_for_checkout > $allowed_days_for_redeem) {
                    $days_for_redeem = $allowed_days_for_redeem;
                } else {
                    $days_for_redeem = $days_for_checkout;
                }
                $data['canUseRedeemOffer'] = $canUseRedeemOffer;
                $data['redeemPointsAvailableForUser'] = $redeemPointsAvailableForUser;
                $data['redeemLoyaltyTypeForUser'] = $redeemLoyaltyTypeForUser;
                $data['MaxRedeemablePoints'] = $MaxRedeemablePoints;
                $data['MaxRedeemableAmount'] = $MaxRedeemableAmount;
                $data['DaysForRedeem'] = $days_for_redeem;
                if ($canUseRedeemOffer) {
                    // code here for autofill the fields
                    if ($redeemPointsAvailableForUser >= $MaxRedeemablePoints) {
                        $showApplyBtn = true;
                        $customer_redeemable_points = $MaxRedeemablePoints;
                        $customer_redeemable_amount = $MaxRedeemableAmount;
                    } elseif ($MaxRedeemablePoints > $redeemPointsAvailableForUser) {
                        $showApplyBtn = true;
                        $customer_redeemable_points = $redeemPointsAvailableForUser;
                        $customer_redeemable_amount = $this->convertRedeemPointsToAmount($redeemLoyaltyTypeForUser, $redeemPointsAvailableForUser);
                    }
                } else {
                    $showApplyBtn = false;
                    $customer_redeemable_points = 0;
                    $customer_redeemable_amount = 0;
                }

                $data['showApplyBtn'] = $showApplyBtn;
                $data['customer_redeemable_points'] = $customer_redeemable_points;
                $data['customer_redeemable_amount'] = $customer_redeemable_amount;
                $data['redeem_offer_mode_from_backend'] = $site_settings->redeem_offer_mode;
                $data['redeem_offer_mode_type_from_backend'] = $site_settings->redeem_offer_mode_type;

                if (session()->has('hyper_pay_transaction_error')) {
                    $data['hyper_pay_transaction_error'] = session()->get('hyper_pay_transaction_error');
                    session()->forget('hyper_pay_transaction_error');
                }

                $data['address_countries'] = $this->page->getAllCountries($this->lang);

                $data['loyalty_programs'] = $this->page->getMultipleRows('setting_loyalty_programs', array('is_active' => 1), 'is_default', 'desc');

                $data['returnToHome'] = 1;

                // custom::dump($data['user_info']);
                return view($payment_view, $data);
            } else {
                return redirect($this->lang_base_url);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function applyCoupon(Request $request)
    {
        try {
            $sessionVals = Session::get('search_data');
            $coupon_code = $request->input('coupon');
            $actual_rent_per_day = Session::get('old_price');
            $loyalty_discounted_rent_per_day = $rent_per_day = Session::get('rent_per_day');
            $pickup_date = $sessionVals['pickup_date'];
            $pickup_time = $sessionVals['pickup_time'];
            $from_region_id = $sessionVals['from_region_id'];
            $from_city_id = $sessionVals['from_city_id'];
            $from_branch_id = $sessionVals['from_branch_id'];
            $days = $sessionVals['days'];

            if ($sessionVals['is_delivery_mode'] == 4) {
                $sessionVals['days'] = 30; // because 1 month is to be charged
                $days = $sessionVals['days'];
            }

            $car_id = Session::get('car_id');
            $loyalty_discount_percent = ((int)Session::get('loyalty_discount_percent') != '' ? (int)Session::get('loyalty_discount_percent') : 0);

            $cdw_charges = Session::get('cdw_charges');
            $cdw_charges_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

            $cdw_plus_charges = Session::get('cdw_plus_charges');
            $cdw_plus_charges_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

            $gps_charges = Session::get('gps_charges');
            $gps_charges_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

            $extra_driver_charges = Session::get('extra_driver_charges');
            $extra_driver_charges_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

            $baby_seat_charges = Session::get('baby_seat_charges');
            $baby_seat_charges_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

            if ($cdw_charges_is_one_time_applicable_on_booking == 1) {
                $cdw_multiply_factor = 1;
            } else {
                $cdw_multiply_factor = $days;
            }

            if ($cdw_plus_charges_is_one_time_applicable_on_booking == 1) {
                $cdw_plus_multiply_factor = 1;
            } else {
                $cdw_plus_multiply_factor = $days;
            }

            if ($gps_charges_is_one_time_applicable_on_booking == 1) {
                $gps_multiply_factor = 1;
            } else {
                $gps_multiply_factor = $days;
            }

            if ($extra_driver_charges_is_one_time_applicable_on_booking == 1) {
                $extra_driver_multiply_factor = 1;
            } else {
                $extra_driver_multiply_factor = $days;
            }

            if ($baby_seat_charges_is_one_time_applicable_on_booking == 1) {
                $baby_seat_multiply_factor = 1;
            } else {
                $baby_seat_multiply_factor = $days;
            }

            $dropoff_charges_amount = Session::get('dropoff_charges_amount');
            $delivery_charges = ($sessionVals['delivery_charges'] ? $sessionVals['delivery_charges'] : 0);
            $parking_fee = $sessionVals['parking_fee'];
            $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];
            $is_delivery_mode = $sessionVals['is_delivery_mode'];

            $total_amount_without_vat = ((($loyalty_discounted_rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor))) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
            $vat_amount = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
            $total_amount_with_vat = $total_amount_without_vat + $vat_amount;

            $promo_discount_with_coupon = $this->page->checkIfPromoApplicable($car_id, $coupon_code, date('Y-m-d H:i:s', strtotime($pickup_date . ' ' . $pickup_time)), $from_region_id, $from_city_id, $from_branch_id, $total_amount_with_vat, $days, $is_delivery_mode);

            $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promo_discount_with_coupon, $pickup_date);

            $is_coupon_usage_fine = custom::is_coupon_usage_fine($coupon_code, $request->id_no, $this->lang);

            if ($is_coupon_usage_fine['status'] == false) {
                $response['status'] = false;
                $response['message'] = $is_coupon_usage_fine['message'];
                $response['promotion_id'] = '';
                $response['promo_discount_amount'] = '';
                $response['total_amount_after_discount'] = '';
                $response['discount_multipl_days'] = '';
                $response['total_amount'] = '';
                $response['rent_per_day'] = '';
                $response['rent_m_days'] = '';
                echo json_encode($response);
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
                $minus_discount = true;
                if ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Fixed Price by Using Coupon') {
                    $promo_discount = $promo_discount_with_coupon->discount; // in actual, this is coming as fixed price not percentage here.
                    $calculated_rent_per_day_after_promo_discount = $actual_rent_per_day - $promo_discount;
                    // if his already loyalty discounted rent is less then the newly discounted rent amount obtained after subtracting discounted amount from old rent per day, show error.
                    if ($calculated_rent_per_day_after_promo_discount >= $loyalty_discounted_rent_per_day) {
                        $response['status'] = false;
                        $response['apply_status'] = false;
                        echo json_encode($response);
                        exit();
                    } elseif ($calculated_rent_per_day_after_promo_discount < $loyalty_discounted_rent_per_day) {
                        $promotion_id = $promo_discount_with_coupon->id;
                        $total_amount_without_vat = (($actual_rent_per_day * $days) - ($promo_discount * $days)) + ($cdw_charges * $days) + ($cdw_plus_charges * $days) + ($gps_charges * $days) + ($extra_driver_charges * $days) + ($baby_seat_charges * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $vat_after_promo_apply = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                        $total_amount_with_vat = $total_amount_without_vat + $vat_after_promo_apply;
                        $total_per_1_day = $actual_rent_per_day + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                        $total_amount = $total_amount_without_vat;
                        // Setting values for response and session.
                        Session::put('vat', $vat_after_promo_apply);
                        Session::put('promo_discount_amount', $promo_discount);
                        Session::put('total_amount_after_discount', $total_amount_with_vat);
                        Session::put('coupon_applied', true);
                        Session::put('coupon_code', $coupon_code);
                        Session::put('promotion_id', $promotion_id);
                        Session::put('minus_discount', $minus_discount);
                        Session::put('rent_per_day', $actual_rent_per_day);
                        Session::save();
                        $response['status'] = true;
                        $response['promotion_id'] = $promotion_id;

                        $response['promo_discount_amount'] = $promo_discount;
                        $response['total_amount_after_discount'] = $total_amount_with_vat;

                        Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                        Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat);
                        Session::save();

                        $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                        $response['rent_per_day'] = $actual_rent_per_day;
                        $response['rent_m_days'] = round($actual_rent_per_day * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                        $response['vat_after_promo_apply'] = round($vat_after_promo_apply, 2);
                        $response['total_per_1_day'] = round($total_per_1_day, 2);
                        $response['total_amount'] = round($total_amount, 2);
                        $response['total_amount_with_vat'] = round($total_amount_with_vat, 2);
                        $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                        $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                        $response['is_promo_applied_for_extras'] = 0;

                        echo json_encode($response);
                        exit();
                    }
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Percentage by Using Coupon') {
                    $promo_discount_percent = $promo_discount_with_coupon->discount;
                    // if his already loyalty discount % is more then the newly discounted %, show error.
                    if ($loyalty_discount_percent >= $promo_discount_percent) {
                        $response['status'] = false;
                        $response['apply_status'] = false;
                        echo json_encode($response);
                        exit();
                    } elseif ($promo_discount_percent > $loyalty_discount_percent) {
                        $promo_discount = round($promo_discount_percent * $actual_rent_per_day / 100, 2);
                        $promotion_id = $promo_discount_with_coupon->id;
                        $total_amount_without_vat = (($actual_rent_per_day * $days) - ($promo_discount * $days)) + ($cdw_charges * $days) + ($cdw_plus_charges * $days) + ($gps_charges * $days) + ($extra_driver_charges * $days) + ($baby_seat_charges * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $vat_after_promo_apply = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                        $total_amount_with_vat = $total_amount_without_vat + $vat_after_promo_apply;
                        $total_per_1_day = $actual_rent_per_day + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                        $total_amount = $total_amount_without_vat;
                        // Setting values for response and session.
                        Session::put('vat', $vat_after_promo_apply);
                        Session::put('promo_discount_amount', $promo_discount);
                        Session::put('total_amount_after_discount', $total_amount_with_vat);
                        Session::put('coupon_applied', true);
                        Session::put('coupon_code', $coupon_code);
                        Session::put('promotion_id', $promotion_id);
                        Session::put('minus_discount', $minus_discount);
                        Session::put('rent_per_day', $actual_rent_per_day);
                        Session::save();
                        $response['status'] = true;
                        $response['promotion_id'] = $promotion_id;

                        $response['promo_discount_amount'] = $promo_discount;
                        $response['total_amount_after_discount'] = $total_amount_with_vat;

                        Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                        Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat);
                        Session::save();

                        $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                        $response['rent_per_day'] = $actual_rent_per_day;
                        $response['rent_m_days'] = round($actual_rent_per_day * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                        $response['vat_after_promo_apply'] = round($vat_after_promo_apply, 2);
                        $response['total_per_1_day'] = round($total_per_1_day, 2);
                        $response['total_amount'] = round($total_amount, 2);
                        $response['total_amount_with_vat'] = round($total_amount_with_vat, 2);
                        $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                        $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                        $response['is_promo_applied_for_extras'] = 0;

                        echo json_encode($response);
                        exit();
                    }
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Percentage by Using Coupon on Loyalty') {
                    $promo_discount_percent = $promo_discount_with_coupon->discount + $loyalty_discount_percent;
                    $promo_discount = round($promo_discount_percent * $actual_rent_per_day / 100, 2);
                    $promotion_id = $promo_discount_with_coupon->id;
                    $total_amount_without_vat = (($actual_rent_per_day * $days) - ($promo_discount * $days)) + ($cdw_charges * $days) + ($cdw_plus_charges * $days) + ($gps_charges * $days) + ($extra_driver_charges * $days) + ($baby_seat_charges * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                    $vat_after_promo_apply = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                    $total_amount_with_vat = $total_amount_without_vat + $vat_after_promo_apply;
                    $total_per_1_day = $actual_rent_per_day + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                    $total_amount = $total_amount_without_vat;
                    // Setting values for response and session.
                    Session::put('vat', $vat_after_promo_apply);
                    Session::put('promo_discount_amount', $promo_discount);
                    Session::put('total_amount_after_discount', $total_amount_with_vat);
                    Session::put('coupon_applied', true);
                    Session::put('coupon_code', $coupon_code);
                    Session::put('promotion_id', $promotion_id);
                    Session::put('minus_discount', $minus_discount);
                    Session::put('rent_per_day', $actual_rent_per_day);
                    Session::save();
                    $response['status'] = true;
                    $response['promotion_id'] = $promotion_id;

                    $response['promo_discount_amount'] = $promo_discount;
                    $response['total_amount_after_discount'] = $total_amount_with_vat;

                    Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                    Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat);
                    Session::save();

                    $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                    $response['rent_per_day'] = $actual_rent_per_day;
                    $response['rent_m_days'] = round($actual_rent_per_day * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                    $response['vat_after_promo_apply'] = round($vat_after_promo_apply, 2);
                    $response['total_per_1_day'] = round($total_per_1_day, 2);
                    $response['total_amount'] = round($total_amount, 2);
                    $response['total_amount_with_vat'] = round($total_amount_with_vat, 2);
                    $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                    $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                    $response['is_promo_applied_for_extras'] = 0;

                    echo json_encode($response);
                    exit();
                } elseif ($promo_discount_with_coupon && $promo_discount_with_coupon->type == 'Fixed Daily Rate Coupon') {
                    $minus_discount = false;
                    $promotion_discount_amount_fixed_daily_rate = $promo_discount_with_coupon->discount; // this is taken as rent per day here because of fixed daily rate.
                    // if his already loyalty discounted rent per day is less then the newly discounted rent per day amount, show error.
                    if ($promotion_discount_amount_fixed_daily_rate >= $loyalty_discounted_rent_per_day) {
                        $response['status'] = false;
                        $response['apply_status'] = false;
                        echo json_encode($response);
                        exit();
                    } elseif ($promotion_discount_amount_fixed_daily_rate < $loyalty_discounted_rent_per_day) {
                        $promo_discount = round($actual_rent_per_day - $promotion_discount_amount_fixed_daily_rate, 2);
                        $promotion_id = $promo_discount_with_coupon->id;
                        $total_amount_without_vat = ($promotion_discount_amount_fixed_daily_rate * $days) + ($cdw_charges * $days) + ($cdw_plus_charges * $days) + ($gps_charges * $days) + ($extra_driver_charges * $days) + ($baby_seat_charges * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                        $vat_after_promo_apply = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                        $total_amount_with_vat = $total_amount_without_vat + $vat_after_promo_apply;
                        $total_per_1_day = $promotion_discount_amount_fixed_daily_rate + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                        $total_amount = $total_amount_without_vat;
                        // Setting values for response and session.
                        Session::put('vat', $vat_after_promo_apply);
                        Session::put('promo_discount_amount', $promo_discount);
                        Session::put('total_amount_after_discount', $total_amount_with_vat);
                        Session::put('coupon_applied', true);
                        Session::put('coupon_code', $coupon_code);
                        Session::put('promotion_id', $promotion_id);
                        Session::put('minus_discount', $minus_discount);
                        Session::put('rent_per_day', $promotion_discount_amount_fixed_daily_rate);
                        Session::save();
                        $response['status'] = true;
                        $response['promotion_id'] = $promotion_id;

                        $response['promo_discount_amount'] = $promo_discount;
                        $response['total_amount_after_discount'] = $total_amount_with_vat;

                        Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                        Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat);
                        Session::save();

                        $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                        $response['rent_per_day'] = $promotion_discount_amount_fixed_daily_rate;
                        $response['rent_m_days'] = round($promotion_discount_amount_fixed_daily_rate * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                        $response['vat_after_promo_apply'] = round($vat_after_promo_apply, 2);
                        $response['total_per_1_day'] = round($total_per_1_day, 2);
                        $response['total_amount'] = round($total_amount, 2);
                        $response['total_amount_with_vat'] = round($total_amount_with_vat, 2);
                        $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                        $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                        $response['is_promo_applied_for_extras'] = 0;

                        echo json_encode($response);
                        exit();
                    }
                } elseif (
                    $promo_discount_with_coupon &&
                    (
                        $promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon' ||
                        $promo_discount_with_coupon->type == 'Percentage Discount on Booking Total Using Coupon' ||
                        ($promo_discount_with_coupon->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' && $sessionVals['is_delivery_mode'] == 4) ||
                        ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types')
                    )
                ) {

                    $promotion_id = $promo_discount_with_coupon->id;

                    if ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types') {
                        $rent_per_day_for_promo_code_check = $loyalty_discounted_rent_per_day;
                    } else {
                        if ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon' || $promo_discount_with_coupon->type == 'Percentage Discount on Booking Total Using Coupon') {
                            $rent_per_day_for_promo_code_check = $loyalty_discounted_rent_per_day;
                        } else {
                            $rent_per_day_for_promo_code_check = $actual_rent_per_day;
                        }
                        if ($loyalty_discount_percent > 0 && $promo_discount_with_coupon->apply_discount_with_loyalty_discount == 1) {
                            $loyalty_discount = round(($loyalty_discount_percent / 100) * $actual_rent_per_day, 2);
                            $rent_per_day_for_promo_code_check = $actual_rent_per_day - $loyalty_discount;
                        }
                    }

                    $total_per_1_day = $rent_per_day_for_promo_code_check + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                    $total_amount_without_vat = ($total_per_1_day * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                    $vat_amount = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                    $total_amount_with_vat = $total_amount_without_vat + $vat_amount;

                    if ($promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon' || $promo_discount_with_coupon->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' || $promo_discount_with_coupon->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types') {
                        $promo_discount = $promo_discount_with_coupon->discount; // its fixed discount on booking total
                    } else {
                        $promo_discount = round(($promo_discount_with_coupon->discount / 100) * $total_amount_with_vat, 2); // its percentage discount on booking total
                    }

                    $total_amount_with_vat_after_promo_discount = $total_amount_with_vat - $promo_discount;

                    if ($total_amount_with_vat_after_promo_discount <= 0) {
                        $response['status'] = false;
                        $response['apply_status'] = false;
                        echo json_encode($response);
                        exit();
                    } elseif ($total_amount_with_vat_after_promo_discount > 0) {

                        Session::put('vat', $vat_amount);
                        Session::put('promo_discount_amount', $promo_discount);
                        Session::put('total_amount_after_discount', $total_amount_with_vat_after_promo_discount);
                        Session::put('coupon_applied', true);
                        Session::put('is_promo_discount_on_total', true);
                        Session::put('coupon_code', $coupon_code);
                        Session::put('promotion_id', $promotion_id);
                        Session::put('minus_discount', $minus_discount);
                        Session::put('rent_per_day', $rent_per_day_for_promo_code_check);
                        Session::save();

                        $response['status'] = true;
                        $response['promotion_id'] = $promotion_id;

                        $response['promo_discount_amount'] = $promo_discount;
                        $response['total_amount_after_discount'] = $total_amount_with_vat_after_promo_discount;

                        Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                        Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat_after_promo_discount);
                        Session::save();

                        if (
                        ($promo_discount_with_coupon->type == 'Subscription - Fixed Discount on Booking Total Using Coupon') &&
                        ($sessionVals['is_delivery_mode'] == 4)
                        ) {
                            $response['discount_multipl_days'] = $promo_discount . ' ' . Lang::get('labels.currency');
                        } else {
                            $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                        }
                        $response['rent_per_day'] = $rent_per_day_for_promo_code_check;
                        $response['rent_m_days'] = round($rent_per_day_for_promo_code_check * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                        $response['vat_after_promo_apply'] = round($vat_amount, 2);
                        $response['total_per_1_day'] = round($total_per_1_day, 2);
                        $response['total_amount'] = round($total_amount_without_vat, 2);
                        $response['total_amount_with_vat'] = round($total_amount_with_vat_after_promo_discount, 2);
                        $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                        $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                        $response['is_promo_applied_for_extras'] = 0;

                        echo json_encode($response);
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

                    if ($promo_discount_with_coupon->type == 'Free CDW Using Coupon' && $cdw_charges > 0) {
                        $cdw_charges = 0;
                        Session::put('cdw_charges', 0);
                        Session::put('is_free_cdw_promo_applied', 1);
                        Session::save();
                        $response['is_free_cdw_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free CDW Plus Using Coupon' && $cdw_plus_charges > 0) {
                        $cdw_plus_charges = 0;
                        Session::put('cdw_plus_charges', 0);
                        Session::put('is_free_cdw_plus_promo_applied', 1);
                        Session::save();
                        $response['is_free_cdw_plus_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Baby Seat Using Coupon' && $baby_seat_charges > 0) {
                        $baby_seat_charges = 0;
                        Session::put('baby_seat_charges', 0);
                        Session::put('is_free_baby_seat_promo_applied', 1);
                        Session::save();
                        $response['is_free_baby_seat_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Driver Using Coupon' && $extra_driver_charges > 0) {
                        $extra_driver_charges = 0;
                        Session::put('extra_driver_charges', 0);
                        Session::put('is_free_driver_promo_applied', 1);
                        Session::save();
                        $response['is_free_driver_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Open KM Using Coupon' && $gps_charges > 0) {
                        $gps_charges = 0;
                        Session::put('gps_charges', 0);
                        Session::put('is_free_open_km_promo_applied', 1);
                        Session::save();
                        $response['is_free_open_km_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Delivery Using Coupon' && $delivery_charges > 0) {
                        $delivery_charges = 0;
                        Session::put('search_data.delivery_charges', 0);
                        Session::put('delivery_charges', 0);
                        Session::put('is_free_delivery_promo_applied', 1);
                        Session::save();
                        $response['is_free_delivery_promo_applied'] = 1;
                    } elseif ($promo_discount_with_coupon->type == 'Free Drop Off Using Coupon' && $dropoff_charges_amount > 0) {
                        $dropoff_charges_amount = 0;
                        Session::put('dropoff_charges_amount', 0);
                        Session::put('is_free_dropoff_promo_applied', 1);
                        Session::save();
                        $response['is_free_dropoff_promo_applied'] = 1;
                    }

                    $promotion_id = $promo_discount_with_coupon->id;
                    $total_amount_without_vat = (($loyalty_discounted_rent_per_day * $days) - ($promo_discount * $days)) + ($cdw_charges * $days) + ($cdw_plus_charges * $days) + ($gps_charges * $days) + ($extra_driver_charges * $days) + ($baby_seat_charges * $days) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                    $vat_after_promo_apply = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
                    $total_amount_with_vat = $total_amount_without_vat + $vat_after_promo_apply;
                    $total_per_1_day = $loyalty_discounted_rent_per_day + $cdw_charges + $cdw_plus_charges + $gps_charges + $extra_driver_charges + $baby_seat_charges;
                    $total_amount = $total_amount_without_vat;
                    // Setting values for response and session.
                    Session::put('vat', $vat_after_promo_apply);
                    Session::put('promo_discount_amount', $promo_discount);
                    Session::put('total_amount_after_discount', $total_amount_with_vat);
                    Session::put('coupon_applied', true);
                    Session::put('coupon_code', $coupon_code);
                    Session::put('promotion_id', $promotion_id);
                    Session::put('minus_discount', $minus_discount);
                    Session::put('rent_per_day', $loyalty_discounted_rent_per_day);
                    Session::save();
                    $response['status'] = true;
                    $response['promotion_id'] = $promotion_id;


                    $response['promo_discount_amount'] = $promo_discount;
                    $response['total_amount_after_discount'] = $total_amount_with_vat;

                    Session::put('car_payment_data_to_check.discount_amount_per_day', $promo_discount);
                    Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_amount_with_vat);
                    Session::save();

                    $response['discount_multipl_days'] = $promo_discount * $sessionVals['days'] . ' ' . Lang::get('labels.currency');
                    $response['rent_per_day'] = $loyalty_discounted_rent_per_day;
                    $response['rent_m_days'] = round($loyalty_discounted_rent_per_day * $sessionVals['days'], 2) . ' ' . Lang::get('labels.currency');
                    $response['vat_after_promo_apply'] = round($vat_after_promo_apply, 2);
                    $response['total_per_1_day'] = round($total_per_1_day, 2);
                    $response['total_amount'] = round($total_amount, 2);
                    $response['total_amount_with_vat'] = round($total_amount_with_vat, 2);
                    $response['total_to_be_paid_used_for_mobile'] = number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');
                    $response['total_to_be_paid_with_days_used_for_mobile'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($response['total_amount_with_vat'], 2) . ' ' . Lang::get('labels.currency');

                    $response['is_promo_applied_for_extras'] = 1;

                    echo json_encode($response);
                    exit();


                } else {
                    $response['status'] = false;
                    $response['message'] = ($this->lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح');
                    $response['promotion_id'] = '';
                    $response['promo_discount_amount'] = '';
                    $response['total_amount_after_discount'] = '';
                    $response['discount_multipl_days'] = '';
                    $response['total_amount'] = '';
                    $response['rent_per_day'] = '';
                    $response['rent_m_days'] = '';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = false;
                $response['message'] = ($this->lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح');
                $response['promotion_id'] = '';
                $response['promo_discount_amount'] = '';
                $response['total_amount_after_discount'] = '';
                $response['discount_multipl_days'] = '';
                $response['total_amount'] = '';
                $response['rent_per_day'] = '';
                $response['rent_m_days'] = '';
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function book_now(Request $request)
    {
        try {
            $request_data_params = $request->all();
            $car_payment_data_to_check = Session::get('car_payment_data_to_check');

            $request_data_params['total_rent_after_discount_on_promo'] = floor($request_data_params['total_rent_after_discount_on_promo']);
            $car_payment_data_to_check['total_rent_after_discount_on_promo'] = floor($car_payment_data_to_check['total_rent_after_discount_on_promo']);
            $request_data_params['original_total_rent_after_discount_on_promo'] = floor($request_data_params['original_total_rent_after_discount_on_promo']);
            $car_payment_data_to_check['original_total_rent_after_discount_on_promo'] = floor($car_payment_data_to_check['original_total_rent_after_discount_on_promo']);

            $request_data_params['discount_amount_per_day'] = $request_data_params['discount_amount_per_day'] > 0 ? floor($request_data_params['discount_amount_per_day']) : 0;
            $car_payment_data_to_check['discount_amount_per_day'] = $car_payment_data_to_check['discount_amount_per_day'] > 0 ? floor($car_payment_data_to_check['discount_amount_per_day']) : 0;

            $request_data_params['redeem_discount_availed'] = $request_data_params['redeem_discount_availed'] > 0 ? floor($request_data_params['redeem_discount_availed']) : 0;
            $car_payment_data_to_check['redeem_discount_availed'] = $car_payment_data_to_check['redeem_discount_availed'] > 0 ? floor($car_payment_data_to_check['redeem_discount_availed']) : 0;

            $site_settings = custom::site_settings();
            if (
                ($site_settings->forcefully_recheck_prices == 0) ||
                ($site_settings->forcefully_recheck_prices == 1 &&
                    $request_data_params['total_rent_after_discount_on_promo'] == $car_payment_data_to_check['total_rent_after_discount_on_promo'] &&
                    $request_data_params['original_total_rent_after_discount_on_promo'] == $car_payment_data_to_check['original_total_rent_after_discount_on_promo'] &&
                    $request_data_params['discount_amount_per_day'] == $car_payment_data_to_check['discount_amount_per_day'] &&
                    $request_data_params['redeem_discount_availed'] == $car_payment_data_to_check['redeem_discount_availed'])
            ) {
                Session::forget('car_payment_data_to_check');
                Session::save();

                $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('id_no' => $request->input('id_no')));
                if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
                    return redirect($this->lang_base_url . '/something-went-wrong');
                }
                ini_set('max_execution_time', 600);
                $site_settings = custom::site_settings();
                $created_at = date('Y-m-d H:i:s');
                $sessionVals = Session::get('search_data');
                $rent_per_day = Session::get('rent_per_day');
                $days = $sessionVals['days'];

                if ($sessionVals['is_delivery_mode'] == 4) {
                    $sessionVals['days'] = 30; // because 1 month is to be charged
                    $days = $sessionVals['days'];
                }

                $hours_diff = $sessionVals['hours_diff'];
                $is_delivery_mode = $sessionVals['is_delivery_mode'];
                $parking_fee = $sessionVals['parking_fee'];
                $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];

                $cdw = Session::get('cdw_charges');
                $cdw_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

                $cdw_plus = Session::get('cdw_plus_charges');
                $cdw_plus_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

                $gps = Session::get('gps_charges');
                $gps_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

                $extra_driver = Session::get('extra_driver_charges');
                $extra_driver_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

                $baby_seat = Session::get('baby_seat_charges');
                $baby_seat_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

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

                $total_rent_for_all_days = Session::get('total_rent_for_all_days'); // Only rent multiplied by days
                if ($sessionVals['is_delivery_mode'] == 1 && $sessionVals['pickup_delivery_coordinate'] != '' && $sessionVals['dropoff_delivery_coordinate'] != '') {
                    $pickup_delivery_coordinate = $sessionVals['pickup_delivery_coordinate'];
                    $dropoff_delivery_coordinate = $sessionVals['dropoff_delivery_coordinate'];
                    $is_delivery_mode_to_save = 'yes';
                } elseif ($sessionVals['is_subscription_with_delivery_flow'] == 1) {
                    $pickup_delivery_coordinate = $sessionVals['pickup_delivery_coordinate'];
                    $dropoff_delivery_coordinate = $sessionVals['dropoff_delivery_coordinate'];
                    $is_delivery_mode_to_save = 'yes';
                } else {
                    $pickup_delivery_coordinate = '';
                    $dropoff_delivery_coordinate = '';
                    $is_delivery_mode_to_save = ($sessionVals['is_delivery_mode'] == 2 ? 'hourly' : ($sessionVals['is_delivery_mode'] == 4 ? 'subscription' : 'no'));
                }
                if (($sessionVals['is_delivery_mode'] == 1 && Session::get('search_data')['delivery_charges'] > 0) || ($sessionVals['is_subscription_with_delivery_flow'] == 1 && Session::get('search_data')['delivery_charges'] > 0)) {
                    $delivery_charges = Session::get('search_data')['delivery_charges'];
                } else {
                    $delivery_charges = 0.00;
                }
                $session_data['first_name'] = $first_name = htmlspecialchars($request->input('first_name'));
                $session_data['last_name'] = $last_name = htmlspecialchars($request->input('last_name'));
                $session_data['id_type'] = $id_type = $request->input('id_type');

                //ahsan
                if (!Session::has('id_version')) {
                    $session_data['id_version'] = $id_version = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');
                    $user_data['id_version'] = $id_version;
                } elseif (Session::has('id_version') && Session::get('id_version') == '') {
                    $session_data['id_version'] = $id_version = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');
                    $user_data['id_version'] = $id_version;
                }

                $session_data['id_no'] = $id_no = custom::convertArabicNumbersToEnglish($request->input('id_no'));
                $session_data['mobile_no'] = $mobile_no = custom::convertArabicNumbersToEnglish($request->input('mobile_no'));
                $session_data['email'] = $email = $request->input('email');
                $session_data['license_no'] = $license_no = custom::convertArabicNumbersToEnglish($request->input('license_no'));
                // New fields suggested by Fozan
                $session_data['sponsor'] = $sponsor = htmlspecialchars($request->input('sponsor'));
                $user_data['first_name'] = $first_name;
                $user_data['last_name'] = $last_name;
                $user_data['id_type'] = $id_type;
                $user_data['id_no'] = $id_no;
                $user_data['mobile_no'] = $mobile_no;
                $user_data['email'] = $email;
                $user_data['license_no'] = $license_no;
                $user_data['sponsor'] = $sponsor;

                $session_data['address_street'] = $user_data['address_street'] = htmlspecialchars($request->input('address_street'));
                $session_data['address_city'] = $user_data['address_city'] = htmlspecialchars($request->input('address_city'));
                $session_data['address_state'] = $user_data['address_state'] = htmlspecialchars($request->input('address_state'));
                $session_data['address_country'] = $user_data['address_country'] = $request->input('address_country');
                $session_data['address_post_code'] = $user_data['address_post_code'] = htmlspecialchars(custom::convertArabicNumbersToEnglish($request->input('address_post_code')));

                $session_data['promo_code'] = $promo_code = $request->input('promo_code');
                $session_data['payment_method'] = $payment_method = $request->input('payment_method');
                $session_data['isMada'] = $isMada = $request->input('isMada');
                $session_data['car_id'] = $car_id = Session::get('car_id');
                $session_data['rent_per_day'] = $rent_per_day;
                $session_data['total_rent_for_all_days'] = $total_rent_for_all_days; // Only rent multiplied by days
                $session_data['total_rent_after_discount_on_promo'] = $request->input('total_rent_after_discount_on_promo');
                $session_data['discount_amount_per_day'] = $request->input('discount_amount_per_day');
                $session_data['promotion_id'] = $request->input('promotion_id');
                $session_data['renting_type_id'] = Session::get('renting_type_id');
                Session::put('payment_form_data', $session_data);
                Session::save();
                // Getting branch data to get its prefix
                $get_branch_data_by['id'] = $sessionVals['from_branch_id'];
                $branch_info = $this->page->getSingle('branch', $get_branch_data_by);
                // Saving booking information
                $booking_info['car_model_id'] = $car_id;
                $booking_info['from_location'] = $sessionVals['from_branch_id'];
                $booking_info['to_location'] = $sessionVals['to_branch_id'];
                $booking_info['from_date'] = date('Y-m-d', strtotime($sessionVals['pickup_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['pickup_time']));
                $booking_info['to_date'] = date('Y-m-d', strtotime($sessionVals['dropoff_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['dropoff_time']));
                $booking_info['booking_status'] = 'Not Picked';
                $booking_info['sync'] = 'N';
                $booking_info['renting_type_id'] = Session::get('renting_type_id');
                if (Session::get('logged_in_from_frontend') == true) {
                    $user_type = 'individual_customer';
                } else {
                    $user_type = 'guest';
                }
                $booking_info['type'] = $user_type;
                $booking_info['pickup_delivery_lat_long'] = $pickup_delivery_coordinate;
                $booking_info['dropoff_delivery_lat_long'] = $dropoff_delivery_coordinate;
                $booking_info['is_delivery_mode'] = $is_delivery_mode_to_save;
                $booking_info['booking_source'] = (custom::is_mobile() ? 'mobile' : 'website');
                $booking_info['lang'] = $this->lang;
                $booking_info['subscription_with_delivery_flow'] = ($sessionVals['is_subscription_with_delivery_flow'] == 1 ? 'on' : 'off');

                if (Session::get('edit_booking_id') != '') {
                    $booking_info['updated_at'] = $created_at;
                    $updated = $this->page->updateData('booking', $booking_info, array('id' => Session::get('edit_booking_id')));
                    // delete data from child tables
                    // booking_individual_payment_method, booking_individual_user, booking_individual_guest, booking_payment, booking_cc_payment
                    $this->page->deleteData('booking_individual_payment_method', array('booking_id' => Session::get('edit_booking_id')));
                    $this->page->deleteData('booking_individual_user', array('booking_id' => Session::get('edit_booking_id')));
                    $this->page->deleteData('booking_individual_guest', array('booking_id' => Session::get('edit_booking_id')));
                    $this->page->deleteData('booking_payment', array('booking_id' => Session::get('edit_booking_id')));
                    $this->page->deleteData('booking_cc_payment', array('booking_id' => Session::get('edit_booking_id')));
                    $this->page->deleteData('booking_sadad_payment', array('booking_id' => Session::get('edit_booking_id')));
                    $savedBookingId = Session::get('edit_booking_id');
                    Session::forget('edit_booking_id');
                    Session::save();
                } else {
                    $booking_info['browser_os'] = custom::get_browser_os();
                    $booking_info['created_at'] = $created_at;
                    $savedBookingId = $this->page->saveData('booking', $booking_info);
                }
                // Generating reservation code and updating in database
                $booking_info_extra['reservation_code'] = custom::generateReservationCode($branch_info->prefix, $savedBookingId, 'W');
                $updateBookingInfoBy['id'] = $savedBookingId;
                $bookingUpdated = $this->page->updateData('booking', $booking_info_extra, $updateBookingInfoBy);
                Session::put('booking_gen_reference_code', $booking_info_extra['reservation_code']);
                Session::put('booking_id', $savedBookingId);
                Session::save();
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

                    // dd($user_data);

                    $this->page->updateData('individual_customer', $user_data, $update_customer_info_by);
                    $getIndCustomer = $this->page->getSingle('individual_customer', $update_customer_info_by);
                    if ($getIndCustomer->uid > 0) {
                        $this->page->updateData('users', array('name' => $first_name . ' ' . $last_name, 'email' => $email), array('id' => $getIndCustomer->uid));
                    }
                    Session::put('individual_customer_id', $individual_customer_id);
                    Session::save();
                } else {
                    $user_data['loyalty_card_type'] = 'Bronze';
                    $user_data['loyalty_points'] = 0;
                    $individual_customer_id = $this->page->saveData('individual_customer', $user_data);
                    Session::put('individual_customer_id', $individual_customer_id);
                    Session::save();
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
                $checkingIfUserIDExistInCustomerTable = false;
                $checkingIfUserExistAlredy = $this->page->getSingle('users', array('email' => $email));

                if ($checkingIfUserExistAlredy) {
                    $checkingIfUserIDExistInCustomerTable = $this->page->getSingle('individual_customer', array('uid' => $checkingIfUserExistAlredy->id));
                }

                if ((Session::get('logged_in_from_frontend') == true) || (($checkingIfUserExistAlredy && $checkingIfUserExistAlredy->email != '') && $checkingIfUserIDExistInCustomerTable)) {
                    $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => $savedBookingId));
                }
                // Saving payment against individual or guest user
                if (Session::get('logged_in_from_frontend') == true && Session::get('user_id') != '') {
                    $booking_individual_user_data['booking_id'] = $savedBookingId;
                    $booking_individual_user_data['uid'] = Session::get('user_id');
                    $this->page->saveData('booking_individual_user', $booking_individual_user_data);
                } elseif (($checkingIfUserExistAlredy && $checkingIfUserExistAlredy->email != '') && $checkingIfUserIDExistInCustomerTable) {
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
                $booking_payment_data['original_rent'] = Session::get('old_price');

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

                $booking_payment_data['promotion_offer_id'] = $request->input('promotion_id');
                $booking_payment_data['promotion_offer_code_used'] = Session::has('coupon_code') ? Session::get('coupon_code') : "";
                $booking_payment_data['discount_price'] = $discount_price = $request->input('discount_amount_per_day');

                $booking_payment_data['total_rent_after_discount'] = $request->input('total_rent_after_discount_on_promo');
                $booking_payment_data['dropoff_charges'] = $dropoff_charges = Session::get('dropoff_charges_amount');
                $booking_payment_data['delivery_charges'] = $delivery_charges;
                $booking_payment_data['parking_fee'] = $parking_fee;
                $booking_payment_data['tamm_charges_for_branch'] = $tamm_charges_for_branch;
                $booking_payment_data['no_of_days'] = ($is_delivery_mode == 2 ? $hours_diff : $days);
                $booking_payment_data['loyalty_type_used'] = Session::get('loyalty_type_used');
                $redeem_points_used = $request->input('redeem_points_used');
                $booking_payment_data['redeem_points'] = isset($redeem_points_used) && $redeem_points_used > 0 ? $redeem_points_used : 0;
                $redeem_discount_availed = $request->input('redeem_discount_availed');
                $booking_payment_data['redeem_discount_availed'] = $redeem_discount_availed = isset($redeem_discount_availed) && $redeem_discount_availed > 0 ? $redeem_discount_availed : 0;

                $booking_payment_data['qitaf_request'] = (Session::has('qitaf_request') ? str_replace('.', ',', Session::get('qitaf_request')) : '');
                $booking_payment_data['qitaf_amount'] = $qitaf_amount = (Session::has('qitaf_amount') ? Session::get('qitaf_amount') : 0);

                $booking_payment_data['niqaty_request'] = (Session::has('niqaty_request') ? Session::get('niqaty_request') : '');
                $booking_payment_data['niqaty_amount'] = $niqaty_amount = (Session::has('niqaty_amount') ? Session::get('niqaty_amount') : 0);

                $booking_payment_data['mokafaa_request'] = (Session::has('mokafaa_request') ? Session::get('mokafaa_request') : '');
                $booking_payment_data['mokafaa_amount'] = $mokafaa_amount = (Session::has('mokafaa_amount') ? Session::get('mokafaa_amount') : 0);

                $booking_payment_data['anb_request'] = (Session::has('anb_request') ? Session::get('anb_request') : '');
                $booking_payment_data['anb_amount'] = $anb_amount = (Session::has('anb_amount') ? Session::get('anb_amount') : 0);

                $loyalty_program_id = (isset($_REQUEST['loyalty_program_id']) && $_REQUEST['loyalty_program_id'] > 0 ? $_REQUEST['loyalty_program_id'] : '');
                $booking_payment_data['loyalty_program_for_oracle'] = custom::get_loyalty_program_used_for_booking($loyalty_program_id);
                $booking_payment_data['is_promo_discount_on_total'] = $is_promo_discount_on_total = (Session::has('is_promo_discount_on_total') ? 1 : 0);

                $pre_total_discount = 0;
                $post_total_discount = 0;

                if ($is_promo_discount_on_total == 1) {
                    $post_total_discount = $discount_price;
                } else {
                    $pre_total_discount = $discount_price;
                }

                /*// updating customer loyalty points
                $customer_info = $this->page->getSingle('individual_customer', array('id' => $individual_customer_id));
                if ($customer_info->loyalty_points > 0) {
                    $customer_old_points = $customer_info->loyalty_points;
                    $customer_new_points = round((int)$customer_old_points, 2) - round((int)$redeem_points_used, 2);
                    $this->page->updateData('individual_customer', array('loyalty_points' => $customer_new_points), array('id' => $individual_customer_id));
                    // an api call will be here to sync customer loyalty points with oasis system
                }*/
                if (Session::get('minus_discount') == true) { // in case of promo code discount
                    $total_sum_without_vat = (($rent_per_day * ($is_delivery_mode == 2 ? 1 : $days)) - ($pre_total_discount * ($is_delivery_mode == 2 ? 1 : $days))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                } else {
                    $total_sum_without_vat = ($rent_per_day * ($is_delivery_mode == 2 ? 1 : $days)) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
                }
                // echo $total_sum_without_vat;die();
                $vat_to_add = (Session::get('vat_percentage') / 100) * $total_sum_without_vat;
                Session::put('vat', $vat_to_add);
                Session::save();
                $booking_payment_data['total_sum'] = $total_sum_without_vat + $vat_to_add - $qitaf_amount - $niqaty_amount - $mokafaa_amount - $anb_amount - $redeem_discount_availed - $post_total_discount;
                $booking_payment_data['vat_percentage'] = Session::get('vat_percentage');
                $booking_payment_data['vat_applied'] = $vat_to_add;

                if ($rent_per_day != Session::get('old_price')) {
                    $booking_payment_data['rent_price'] = $rent_per_day;
                } else {
                    $payment_form_data = session()->get('payment_form_data');
                    $rent_per_day = $rent_per_day - $payment_form_data['discount_amount_per_day'];
                    $booking_payment_data['rent_price'] = $rent_per_day;

                    if ($booking_payment_data['promotion_offer_id'] > 0) {
                        $promotion_offer = DB::table('promotion_offer')->where('id', $booking_payment_data['promotion_offer_id'])->first();
                        if ($promotion_offer && $promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types' && $is_delivery_mode == 4) {
                            $booking_payment_data['rent_price'] = Session::get('old_price');
                        }
                    }
                }

                $is_promo_discount_on_total_without_loyalty = custom::is_promo_discount_on_total_without_loyalty($booking_payment_data['promotion_offer_id']);
                if ($is_promo_discount_on_total_without_loyalty) {
                    $booking_payment_data['rent_price'] = Session::get('old_price');
                }

                $booking_payment_data['car_rate_is_with_additional_utilization_rate'] = (Session::has('car_rate_is_with_additional_utilization_rate') ? Session::get('car_rate_is_with_additional_utilization_rate') : 0);

                if ($booking_payment_data['car_rate_is_with_additional_utilization_rate'] > 0) {
                    $car_utilization_setup = $this->page->getSingle('setting_car_utilization_setup', ['id' => $booking_payment_data['car_rate_is_with_additional_utilization_rate']]);
                    if ($car_utilization_setup) {
                        $booking_payment_data['utilization_percentage'] = $car_utilization_setup->utilization_percentage;
                        $booking_payment_data['utilization_percentage_rate'] = $car_utilization_setup->addition_or_subtraction_percentage;
                        $booking_payment_data['utilization_record_time'] = $car_utilization_setup->last_amend_date;
                    }
                }

                $booking_payment_data['cpid'] = Session::get('cpid');

                if ($is_delivery_mode == 4) { // only to be done for subscription mode
                    $booking_payment_data['subscribe_for_months'] = $sessionVals['subscribe_for_months'];

                    if ($booking_payment_data['cpid']) {
                        $car_prices = $this->page->getSingle('car_price', ['id' => $booking_payment_data['cpid']]);
                        $booking_payment_data['three_month_subscription_price_for_car'] = $car_prices->three_month_subscription_price;
                        $booking_payment_data['six_month_subscription_price_for_car'] = $car_prices->six_month_subscription_price;
                        $booking_payment_data['nine_month_subscription_price_for_car'] = $car_prices->nine_month_subscription_price;
                        $booking_payment_data['twelve_month_subscription_price_for_car'] = $car_prices->twelve_month_subscription_price;
                    }
                }

                $booking_payment_data['is_free_cdw_promo_applied'] = (Session::has('is_free_cdw_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_cdw_plus_promo_applied'] = (Session::has('is_free_cdw_plus_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_baby_seat_promo_applied'] = (Session::has('is_free_baby_seat_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_driver_promo_applied'] = (Session::has('is_free_driver_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_open_km_promo_applied'] = (Session::has('is_free_open_km_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_delivery_promo_applied'] = (Session::has('is_free_delivery_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_dropoff_promo_applied'] = (Session::has('is_free_dropoff_promo_applied') ? 1 : 0);

                // workaround fix for discount field getting 0 for loyalty discount case, in offer apply case this is working already fine so only did it for no offer
                if ($booking_payment_data['promotion_offer_id'] == 0) {
                    $booking_payment_data['discount_price'] = $booking_payment_data['original_rent'] - $booking_payment_data['rent_price'];
                }

                $this->page->saveData('booking_payment', $booking_payment_data);
                Session::put('total_amount_for_transaction', $booking_payment_data['total_sum']);
                Session::save();

                $car_info = $this->page->getSingleCarDetail($car_id);
                $items = [];
                $item_data = [
                    'item_id' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
                    'item_name' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
                    'item_brand' => $method_is
                ];
                $items[] = $item_data;
                $event = 'begin_checkout';
                $event_data = [
                    'currency' => 'SAR',
                    'value' => Session::get('total_amount_for_transaction'),
                    'items' => $items
                ];
                custom::sendEventToGA4($event, $event_data);
                
                // If credit card method is used than redirecting to payment page
                if ($payment_method == 'cc') {
                    $booking_cc['booking_id'] = Session::get('booking_id');
                    $booking_cc['status'] = 'pending';

                    $site_settings = custom::site_settings();
                    if ($site_settings->cc_company == 'sts') {
                        $booking_cc['payment_company'] = 'sts';
                    } elseif ($site_settings->cc_company == 'hyper_pay') {
                        $booking_cc['payment_company'] = 'hyper_pay';
                    }

                    $this->page->saveData('booking_cc_payment', $booking_cc);
                    if ($this->lang == "arb") {
                        $bPayPath = "cc-payment";
                    } else {
                        $bPayPath = "en/cc-payment";
                    }
                    return redirect($bPayPath);
                } elseif ($payment_method == 'sadad') {
                    $olp_id = $request->input('olp_id');
                    $booking_sadad['s_booking_id'] = Session::get('booking_id');
                    $booking_sadad['s_status'] = 'pending';
                    $booking_sadad['s_olp_id'] = $olp_id;
                    $this->page->saveData('booking_sadad_payment', $booking_sadad);
                    $this->payWithSadad($olp_id, $request->input(), Session::get('booking_id'));
                } elseif ($payment_method == 'cash') {

                    $this->clearQitafAfterBookingConfirmed($savedBookingId, 'website book_now function'); // removing qitaf log from temp table if paid in cash when saving booking

                    $this->clearNiqatyAfterBookingConfirmed($savedBookingId, 'website book_now function'); // removing niqaty log from temp table if paid in cash when saving booking

                    $this->clear_mokafaa_after_booking_confirmed($savedBookingId, 'website book_now function'); // removing niqaty log from temp table if paid in cash when saving booking

                    $this->clear_anb_after_booking_confirmed($savedBookingId, 'website book_now function'); // removing niqaty log from temp table if paid in cash when saving booking

                    if ($this->lang == "arb")
                        $bDonePath = "booking-done";
                    else
                        $bDonePath = "en/booking-done";
                    // $this->sendEmailToUser(Session::get('booking_id'));
                    // $this->sendToKeyAdmin(Session::get('booking_id'));
                    $userPhoneNo = $request->input('mobile_no');
                    $this->sendThankYouSMS($booking_info_extra['reservation_code'], $userPhoneNo);
                    // Deducting and updating customer's redeem points
                    $this->add_or_deduct_loyalty_points_for_customer($savedBookingId, 'deduct');
                    // code to send email and sms to branch agent starts here
                    if (Session::get('search_data')['is_delivery_mode'] == 1) {
                        $customer_name = $first_name . ' ' . $last_name;
                        // $this->send_email_to_branch_agent(Session::get('booking_id'), $branch_info->email, $first_name, $last_name);
                        $this->send_sms_to_branch_agent($booking_info_extra['reservation_code'], $branch_info->mobile, $customer_name);
                    }
                    // ends here
                    return redirect($this->lang_base_url . '/booking-done');
                }
            } else {
                return redirect($this->lang_base_url);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // send email to the user to confirm booking
    private function sendEmailToUser($bid = "")
    {
        //$lang = $this->lang;
        try {
            $site_settings = custom::smtp_settings();
            //echo "<pre>"; print_r($site_settings); exit;
            $emailData = array();
            $email = array();
            if ($bid == "") {
                $booking_id = Session::get('booking_id');
            } else {
                $booking_id = $bid;
            }

            $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));
            if ($booking_detail->type == "corporate_customer") {
                $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
            } else {
                $emailObj = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            }

            // custom::dump($emailObj);
            $lang = $emailObj->lang;
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
            $fileName = $this->bookingPdf($emailData, $emailObj->reservation_code);
            $attachment = public_path('pdf/') . $fileName;
            $email['pdf'] = 'pdf';
            $email['attachment'] = $attachment;
            custom::sendEmail('booking', $emailData, $email, $lang);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    //=========
    private function bookingPdf($data, $booking_ref_no = false)
    {
        try {
            $lang = $this->lang;
            $data['isPdf'] = "pdf";
            //echo '<pre>';print_r($data);exit();
            if ($booking_ref_no) {
                $filename = $booking_ref_no . '-Booking.pdf';
            } else {
                $filename = rand() . '-Booking.pdf';
            }

            if ($lang == "eng") {
                $template = 'frontend.emails.booking_email_pdf_eng';
            } else {
                $template = 'frontend.emails.booking_email_pdf_ar';
            }
            // echo '<pre>';print_r($data);exit();
            $this->pdf->loadView($template, $data)
                ->setPaper('a4')
                ->setOrientation('portrait')
                ->setOption('margin-bottom', 0)
                ->save('public/pdf/' . $filename, 'overwrite');
            return $filename;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function bookingCancellationPdf($data)
    {
        try {
            $lang = $this->lang;
            $data['isPdf'] = "pdf";
            $filename = rand() . '-Booking.pdf';
            if ($lang == "eng") {
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
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function mada_payment(Request $request)
    {
        try {
            $data = array();

            $apiSettings = custom::api_settings();
            $SECRET_KEY = $apiSettings->sts_secret_key_web;
            $MERCHANT_ID = $apiSettings->sts_merchant_id_web;
            $sts_payment_link = $apiSettings->sts_payment_link;
            $data['merchantID'] = $MERCHANT_ID;
            $data['sts_payment_link'] = $sts_payment_link;

            //Start handling STS response.
            if (isset($_REQUEST['Response_SecureHash'])) {

                $parameters = $_REQUEST;
                //echo "<pre>";
                //print_r($parameters);
                $receivedSecurehash = $parameters["Response_SecureHash"];
                unset($parameters["Response_SecureHash"]);
                ksort($parameters);
                $responseOrderdString = "";
                $responseOrderdString .= $SECRET_KEY;

                $get_response = '';
                foreach ($parameters as $key => $value) {
                    if ($key == 'Response_GatewayStatusDescription' || $key == 'Response_StatusDescription') {
                        $responseOrderdString .= urlencode($value); //utf8_encode(string)
                    } else {
                        $responseOrderdString .= $value;
                    }
                    $get_response .= '|' . $key . '=' . $value;
                }

                //STS response log
                $get_booking_id = $this->getBookingIdSts($parameters['Response_TransactionID']);
                if ($get_booking_id) {
                    $booking = $this->page->getSingle('booking', array('id' => $get_booking_id));
                }

                if (isset($booking) && $booking->booking_source == 'ios') {
                    $type = 'ios_mada';
                } else {
                    $type = 'android_mada';
                }
                $this->page->saveData('booking_sts_log',
                    array('transaction_id' => $parameters['Response_TransactionID'],
                        'type' => $type,
                        'response' => $get_response,
                        'created_at' => date('Y-m-d H:i:s')
                    )
                );

                //echo ("Response Orderd String is: " . $responseOrderdString).chr(10);
                $generatedsecureHash = hash('sha256', $responseOrderdString, false);

                if ($parameters['Response_StatusCode'] == '00000' && $parameters['Response_GatewayStatusCode'] == '0000') {

                    if ($receivedSecurehash !== $generatedsecureHash) {
                        echo "Received Secure Hash does not Equal generated Secure hash";
                        exit();
                    }

                    $response_html = '';
                    $booking_id = explode('KEY', $parameters['Response_TransactionID']);
                    $transaction_id = $parameters['Response_TransactionID'];
                    $card_brand = explode(':', $parameters['Response_PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0
                    //$card_brand = "";
                    //Mada card has 13 digits so we are exploding by 3(***) excluding first 6 and last 4 digits
                    //Master & visa card has 16 digits so we are exploding by 6(******) excluding frist 6 and last 4 digits
                    /*if($card_brand[1] == 'Mada'){
                        $Response_CardNumber = explode("***", $parameters['Response_CardNumber']);
                    }else{
                        $Response_CardNumber = explode("******", $parameters['Response_CardNumber']);
                    }*/

                    //update sts attempts
                    $booking_cc_update['is_sts_inquired'] = 0;
                    $getKeyId = substr($booking_id[1], 0, 3);
                    $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side
                    $getPrimaryId = substr($booking_id[1], 3);
                    $booking_cc_update['sts_attempts'] = ltrim($sts_attempted, '0');
                    $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $getPrimaryId));

                    $first_4_digits = substr($parameters['Response_CardNumber'], 0, 6);
                    $last_4_digits = substr($parameters['Response_CardNumber'], -4);

                    $response_html .= '<div id="status" style="display: none;">1</div>';
                    $response_html .= '<div id="booking_id" style="display: none;">' . ltrim($booking_id[1], '0') . '</div>';
                    $response_html .= '<div id="madaPayment" style="display: none;">' . $parameters['Response_StatusDescription'] . '</div>';
                    $response_html .= '<div id="transaction_id" style="display: none;">' . $transaction_id . '</div>';
                    $response_html .= '<div id="first_4_digits" style="display: none;">' . $first_4_digits . '</div>';
                    $response_html .= '<div id="last_4_digits" style="display: none;">' . $last_4_digits . '</div>';
                    $response_html .= '<div id="card_brand" style="display: none;">' . $card_brand[1] . '</div>';

                    echo $response_html;
                    exit;
                } else {

                    //echo "Status is: failed";
                    $bookingLabel = $this->lang == 'eng' ? 'Booking' : 'الحجز';
                    $errorLabel = $this->lang == 'eng' ? 'Error' : 'خطأ';
                    $data['sts_error'] = $bookingLabel . ' # : ' . $parameters['Response_TransactionID'] . ' - ' . $parameters['Response_StatusDescription'];
                    $Response_TransactionID = explode('KEY', $parameters['Response_TransactionID']);
                    $getKeyId = substr($Response_TransactionID[1], 0, 3);
                    $getIncrement = str_pad($getKeyId + 1, 3, "0", STR_PAD_LEFT);
                    $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side
                    $getPrimaryId = substr($Response_TransactionID[1], 3);
                    $data['Response_TransactionID'] = "KEY" . $getIncrement . str_pad($getPrimaryId, 14, '0', STR_PAD_LEFT);
                    //update sts attempts
                    $booking_cc_update['is_sts_inquired'] = 0;
                    $booking_cc_update['sts_attempts'] = ltrim($sts_attempted, '0');
                    $booking_id = $getPrimaryId;
                    $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
                }
            }
            //end STS Responce

            $data['stsMobile'] = $request->input('mobile');
            $data['lang'] = $this->lang;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['active_menu'] = 'payment';
            return view('frontend/mada-payment', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function getBookingIdSts($sts_transcation_id)
    {
        try {
            if ($sts_transcation_id != '') {
                $booking_id = explode('KEY', $sts_transcation_id);
                $return_id = substr($booking_id[1], 3);
            } else {
                $return_id = 0;
            }
            return $return_id;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function cc_payment()
    {
        try {
            if (Session::get('search_data') == "") {
                return redirect($this->lang_base_url . '/home');
            }

            $data = array();

            // echo '<pre>';print_r($_REQUEST);die();


            //Start handling STS response.
            if (isset($_REQUEST['Response_SecureHash'])) {

                $apiSettings = custom::api_settings();
                $SECRET_KEY = $apiSettings->sts_secret_key_web;
                $MERCHANT_ID = $apiSettings->sts_merchant_id_web;

                $get_response = '';
                $parameters = $_REQUEST;
                //echo "<pre>";
                //print_r($parameters);
                $receivedSecurehash = $parameters["Response_SecureHash"];
                unset($parameters["Response_SecureHash"]);
                ksort($parameters);
                $responseOrderdString = "";
                $responseOrderdString .= $SECRET_KEY;

                foreach ($parameters as $key => $value) {
                    if ($key == 'Response_GatewayStatusDescription' || $key == 'Response_StatusDescription') {
                        $responseOrderdString .= urlencode($value); //utf8_encode(string)
                    } else {
                        $responseOrderdString .= $value;
                    }

                    $get_response .= '|' . $key . '=' . $value;
                }


                //echo ("Response Ordered String is: " . $responseOrderdString).chr(10);
                $generatedsecureHash = hash('sha256', $responseOrderdString, false);

                if ($parameters['Response_StatusCode'] == '00000' && $parameters['Response_GatewayStatusCode'] == '0000') {

                    if ($receivedSecurehash !== $generatedsecureHash) {
                        echo "Received Secure Hash does not Equal generated Secure hash";
                        exit();
                    }

                    //sts credit card is success
                    //echo "Status is: ".$_REQUEST['Response_StatusCode'];

                    //update the database and send emails sms, redeem update and sync service call
                    $search_data_session = session()->get('search_data');
                    $payment_form_data = session()->get('payment_form_data');

                    $booking_id = Session::get('booking_id');
                    $from_branch_id = $search_data_session['from_branch_id'];

                    //update sts attempts
                    $Response_TransactionID = explode('KEY', $parameters['Response_TransactionID']);
                    $getKeyId = substr($Response_TransactionID[1], 0, 3);
                    $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side
                    $booking_cc_update['is_sts_inquired'] = 0;
                    $booking_cc_update['sts_attempts'] = ltrim($sts_attempted, '0');
                    $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));

                    /*don't get confused by this transaction id name. Actually in paytabs transaction id is paytabs database id
                    While in sts the transaction id is the same id of our primary key that we sent to sts
                    before payment and it returned us back*/
                    $transaction_id = $parameters['Response_TransactionID'];
                    $user_mobile_no = $payment_form_data['mobile_no'];

                    $card_brand = explode(':', $parameters['Response_PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0
                    //$card_brand = "";
                    //Mada card has 13 digits so we are exploding by 3(***) excluding first 6 and last 4 digits
                    //Master & visa card has 16 digits so we are exploding by 6(******) excluding frist 6 and last 4 digits
                    /*if($card_brand[1] == 'Mada'){
                        $Response_CardNumber = explode("***", $parameters['Response_CardNumber']);
                    }else{
                        $Response_CardNumber = explode("******", $parameters['Response_CardNumber']);
                    }*/

                    $first_4_digits = substr($parameters['Response_CardNumber'], 0, 6);
                    $last_4_digits = substr($parameters['Response_CardNumber'], -4);

                    //if condition added after implementing IPN module, IPN is more fast
                    $isStsBookingsStatusPending = $this->page->isStsBookingsStatusPending($booking_id);
                    if ($isStsBookingsStatusPending) {//its mean the booking status is still pending in database. so update the status and send booking emails.

                        $this->sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand[1]);
                        //STS response log
                        $this->page->saveData('booking_sts_log',
                            array('transaction_id' => $parameters['Response_TransactionID'],
                                'type' => (custom::is_mobile() ? 'mobile' : 'website'),
                                'response' => $get_response,
                                'created_at' => date('Y-m-d H:i:s')
                            )
                        );
                    }
                    //set success response for ajax

                    //redirect to booked.
                    return redirect($this->lang_base_url . '/booked');

                } else {
                    //echo "Status is: failed";
                    $bookingLabel = $this->lang == 'eng' ? 'Booking' : 'الحجز';
                    $errorLabel = $this->lang == 'eng' ? 'Error' : 'خطأ';
                    $data['sts_error'] = $bookingLabel . ' # : ' . $parameters['Response_TransactionID'] . ' - ' . $parameters['Response_StatusDescription'];
                    $Response_TransactionID = explode('KEY', $parameters['Response_TransactionID']);
                    $getKeyId = substr($Response_TransactionID[1], 0, 3);
                    $getIncrement = str_pad($getKeyId + 1, 3, "0", STR_PAD_LEFT);
                    $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this val+ue is that is now saved into sts engine side
                    $getPrimaryId = substr($Response_TransactionID[1], 3);
                    $data['Response_TransactionID'] = "KEY" . $getIncrement . str_pad($getPrimaryId, 14, '0', STR_PAD_LEFT);
                    //update sts attempts
                    $booking_cc_update['is_sts_inquired'] = 0;
                    $booking_cc_update['sts_attempts'] = ltrim($sts_attempted, '0');
                    $booking_id = Session::get('booking_id');
                    $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));

                }
            }
            //end STS Response

            $site_settings = custom::site_settings();
            if ($site_settings->cc_company == 'hyper_pay') {
                $data['hp_params'] = $this->hp_generate_checkout_id(Session::get('booking_id'), Session::get('payment_form_data')['isMada']);
            }

            $regions = $this->page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $sessionVals = Session::get('search_data');
            if ($sessionVals['is_delivery_mode'] == 4) {
                $sessionVals['days'] = 30; // because 1 month is to be charged
            }

            $data['booking_info'] = $sessionVals;
            $id = Session::get('car_id');
            $data['car_info'] = $this->page->getSingleCarDetail($id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['from_branch_id']);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['to_branch_id']);
            $data['rent_per_day'] = Session::get('rent_per_day');
            $data['days'] = Session::get('days');
            $data['car_id'] = Session::get('car_id');
            $data['total_rent'] = Session::get('total_rent');
            //echo '<pre>';print_r($data);exit();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'payment';

            $data['cdw'] = Session::get('cdw_charges');
            $data['cdw_is_one_time_applicable_on_booking'] = Session::get('cdw_charges_is_one_time_applicable_on_booking');

            $data['cdw_plus'] = Session::get('cdw_plus_charges');
            $data['cdw_plus_is_one_time_applicable_on_booking'] = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

            $data['gps'] = Session::get('gps_charges');
            $data['gps_is_one_time_applicable_on_booking'] = Session::get('gps_charges_is_one_time_applicable_on_booking');

            $data['extra_driver'] = Session::get('extra_driver_charges');
            $data['extra_driver_is_one_time_applicable_on_booking'] = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

            $data['baby_seat'] = Session::get('baby_seat_charges');
            $data['baby_seat_is_one_time_applicable_on_booking'] = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

            if ($data['cdw_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_multiply_factor'] = 1;
            } else {
                $data['cdw_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['cdw_plus_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_plus_multiply_factor'] = 1;
            } else {
                $data['cdw_plus_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['gps_is_one_time_applicable_on_booking'] == 1) {
                $data['gps_multiply_factor'] = 1;
            } else {
                $data['gps_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['extra_driver_is_one_time_applicable_on_booking'] == 1) {
                $data['extra_driver_multiply_factor'] = 1;
            } else {
                $data['extra_driver_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['baby_seat_is_one_time_applicable_on_booking'] == 1) {
                $data['baby_seat_multiply_factor'] = 1;
            } else {
                $data['baby_seat_multiply_factor'] = $data['booking_info']['days'];
            }

            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            //echo '<pre>';print_r($data);exit();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'cc-payment'));
            $data['active_menu'] = 'cc-payment';
            $data['payment_form_data'] = Session::get('payment_form_data');
            $customer_id_info = $this->page->getSingle('customer_id_types', array('ref_id' => Session::get('payment_form_data')['id_type']));
            $data['customer_id_title'] = ($this->lang == 'eng' ? $customer_id_info->eng_title : $customer_id_info->arb_title);
            $data['api_settings'] = custom::api_settings();
            $data['car_info'] = $this->page->getSingleCarDetail(Session::get('car_id'));
            /*$data['promo_discount'] = $promo_discount = $this->page->checkAutoPromoDiscount(Session::get('car_id'), date('Y-m-d', strtotime($sessionVals['pickup_date'])),$sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id']);*/
            $data['promo_discount_amount'] = Session::get('promo_discount_amount');
            $data['booking_payment_details'] = $this->page->getSingle('booking_payment', array('booking_id' => Session::get('booking_id')));

            $data['returnToHome'] = 1;

            if (custom::is_mobile()) {
                return view('frontend/mobile/cc-payment', $data);
            } else {
                return view('frontend/cc-payment', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkSessionBeforePayment()
    {
        try {
            $response = array();
            $payment_booking_id = $_REQUEST['b_id'];
            $session_booking_id = Session::get('booking_id');
            if ($payment_booking_id == $session_booking_id) {
                $response['status'] = true;
                $response['title'] = '';
                $response['message'] = 'success';
            } else {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = $this->lang == 'eng' ? 'Booking session has been expired. Please try again.' : 'تم إنتهاء صلاحية الجسلة، الرجاء المحاولة مرة أخرى';
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsIPN()
    {
        try {
            $site = custom::site_settings();

            $emailString = '';
            foreach ($_REQUEST as $key => $value) {
                $emailString .= $key . '|' . $value . ',';
            }

            if (isset($_REQUEST['Response_TransactionID'])) {

                $email['subject'] = 'KEY Car Rental | STS IPN Triggered';
                $email['fromEmail'] = 'no-reply@paytabs.com';
                $email['fromName'] = 'no-reply';
                if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {
                    $email['toEmail'] = '';
                    $email['ccEmail'] = '';
                    $email['bccEmail'] = '';
                    /*$email['toEmail'] = 'f.baghdadi@key.sa';
                    $email['ccEmail'] = 'kholoud.j@edesign.com.sa';
                    $email['bccEmail'] = 'ahsan@astutesol.com';*/
                } else {
                    $email['toEmail'] = 'ahsan@astutesol.com';
                    $email['ccEmail'] = 'kholoud.j@edesign.com.sa';
                    $email['bccEmail'] = '';
                }
                $email['attachment'] = '';
                $content['contact_no'] = $site->site_phone;
                $content['lang_base_url'] = $this->lang_base_url;
                $content['name'] = 'Fozan Baghdadi';
                $content['msg'] = $emailString;
                $content['gender'] = 'male';
                custom::sendEmail2('general', $content, $email, 'eng');

                $get_booking_id = explode('KEY', $_REQUEST['Response_TransactionID']);
                $getKeyId = substr($get_booking_id[1], 0, 3);
                $sts_attempted = str_pad($getKeyId, 3, '0', STR_PAD_LEFT);
                $booking_id = substr($get_booking_id[1], 3);
                $booking_id = ltrim($booking_id, '0');
                $total_attempts = ltrim($sts_attempted, '0');

                if ($_REQUEST['Response_StatusCode'] == '00000' && $_REQUEST['Response_GatewayStatusCode'] == '0000') {

                    $booking = $this->page->getSingle('booking_cc_payment', array('booking_id' => $booking_id));

                    if ($booking->status == 'pending' && $booking->payment_company == 'sts') {

                        $card_brand = explode(':', $_REQUEST['Response_PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0

                        //Do STS Inquiry
                        $stsIpnStatus = $this->stsIpnInquiry($booking_id, $total_attempts, $card_brand[1]);

                        if ($stsIpnStatus['status']) {
                            //email code
                            $email['subject'] = 'Booking Status Changed Through STS IPN';
                            $email['fromEmail'] = 'no-reply@paytabs.com';
                            $email['fromName'] = 'no-reply';
                            if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {
                                $email['toEmail'] = 'f.baghdadi@key.sa';
                                $email['ccEmail'] = '';
                                $email['bccEmail'] = '';
                                /*$email['ccEmail'] = 'kholoud.j@edesign.com.sa';
                                $email['bccEmail'] = 'ahsan@astutesol.com';*/
                            } else {
                                $email['toEmail'] = 'ahsan@astutesol.com';
                                $email['ccEmail'] = 'kholoud.j@edesign.com.sa';
                                $email['bccEmail'] = '';
                            }
                            $email['attachment'] = '';
                            $content['contact_no'] = $site->site_phone;
                            $content['lang_base_url'] = $this->lang_base_url;
                            $content['name'] = 'Fozan Baghdadi';
                            $content['msg'] = $emailString . '| Booking ID: ' . $booking_id;
                            $content['gender'] = 'male';
                            custom::sendEmail2('general', $content, $email, 'eng');
                        }
                    }
                }
                $response['status'] = 1;
                $response['message'] = 'Success';
            } else {
                $response['status'] = 0;
                $response['message'] = 'Invalid Request';
            }

            echo json_encode($response);
            exit;

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function stsIpnInquiry($booking_id, $total_attempts, $card_brand)
    {
        $stsIpnStatus = array();
        $stsIpnStatus['status'] = false;

        $tid = $booking_id;
        $sts_attempts = $total_attempts;
        $get_attempts = str_pad($sts_attempts, 3, "0", STR_PAD_LEFT);
        $OriginalTransactionID = "KEY" . $get_attempts . str_pad($tid, 14, '0', STR_PAD_LEFT);

        $apiSettings = custom::api_settings();
        $SECRET_KEY = $apiSettings->sts_secret_key_web;
        $merchantID = $apiSettings->sts_merchant_id_web;
        $sts_payment_inquiry_link = $apiSettings->sts_payment_inquiry_link;

        $secureHash = custom::generate_token(array(
            "OriginalTransactionID" => $OriginalTransactionID,
            "MerchantID" => $merchantID,
            "MessageID" => "2",
            "Version" => "2.0"
        ), $SECRET_KEY);

        $requestQueryArr = [
            "OriginalTransactionID" => $OriginalTransactionID,
            "MerchantID" => $merchantID,
            "MessageID" => "2",
            "Version" => "2.0",
            "SecureHash" => $secureHash,
        ];

        $newRequestQuery = http_build_query($requestQueryArr);
        $ch = curl_init($sts_payment_inquiry_link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $newRequestQuery);

        $output = curl_exec($ch);

        $result = array();
        $result_ar = explode('&', $output);

        foreach ($result_ar as $value) {
            $arr2 = explode('=', $value);
            $result[$arr2[0]] = $arr2[1];
        }

        $receivedSecurehash = $result['Response.SecureHash'];
        unset($result["Response.SecureHash"]);
        ksort($result);
        $responseOrderdString = $SECRET_KEY;

        $get_response = '';
        foreach ($result as $key => $result_v) {
            if ($result_v != 'null') {
                if ($key == 'Response.GatewayStatusDescription' || $key == 'Response.StatusDescription') {
                    $responseOrderdString .= urlencode($result_v); //utf8_encode(string)
                } else {
                    $responseOrderdString .= $result_v;
                }
            }
            $get_response .= '|' . $key . '=' . $result_v;
        }

        //STS response log
        $this->page->saveData('booking_sts_log',
            array('transaction_id' => $OriginalTransactionID,
                'type' => 'ipn',
                'response' => $get_response,
                'created_at' => date('Y-m-d H:i:s')
            )
        );

        //echo "Response Orderd String is " . $responseOrderdString;
        $generatedsecureHash = hash('sha256', $responseOrderdString);

        if ($result["Response.StatusCode"] == "00000" && $result["Response.GatewayStatusCode"] == "0000") {

            if ($receivedSecurehash != $generatedsecureHash) {
                echo "<br/><br/>Received Secure Hash does not Equal generated Secure hash";
                exit();
            }

            $booking_id = $tid; //its primary key "id" of booking table
            $stsBooking = $this->page->getSingle('booking', array('id' => $booking_id));
            $from_branch_id = $stsBooking->from_location; //from_location from booking table
            $transaction_id = $result["Response.TransactionID"];

            $user_mobile_no = "";
            if ($stsBooking->type == "corporate_customer") {
                $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
                $uid = $booking_corporate_customer->uid;
                // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                $user_mobile_no = $corporate_customer->primary_phone;
            } elseif ($stsBooking->type == "individual_customer") {
                $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $booking_id));
                $uid = $booking_individual_user->uid;
                $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                $user_mobile_no = $individual_customer->mobile_no;
            } else {
                $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $booking_id));
                $individual_customer_id = $booking_individual_guest->individual_customer_id;
                $individual_customer = $this->page->getSingle("individual_customer", array("id" => $individual_customer_id));
                $user_mobile_no = $individual_customer->mobile_no;
            }

            //$card_brand = explode(':',$result['Response.PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0
            $first_4_digits = substr($result['Response.CardNumber'], 0, 6);
            $last_4_digits = substr($result['Response.CardNumber'], -4);

            //$this->sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand[1]);
            $this->sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand);

            $stsIpnStatus['status'] = true;
        }
        return $stsIpnStatus;
    }

    public function stsTransactionInquiryToTest()
    {
        try {
            $orderBy = 'booking_id';
            $sort = 'asc';
            //the null(sts_attempts is not null) check is inside the following model function
            //$stsPendingPayments = $this->page->getStsBookingsForInquiry('booking_cc_payment', array('status' => 'pending', 'payment_company' => 'sts', 'is_sts_inquired' => '0'), $orderBy, $sort);
            $stsPendingPayments = $this->page->getStsBookingsForInquiry($orderBy, $sort);
            // custom::dump($stsPendingPayments);

            $count = 0;
            $limit = 5;
            foreach ($stsPendingPayments as $payment) {
                if ($count < $limit) {
                    $tid = $payment->booking_id;
                    $sts_attempts = $payment->sts_attempts;
                    $get_attempts = str_pad($sts_attempts, 3, "0", STR_PAD_LEFT);
                    $OriginalTransactionID = "KEY" . $get_attempts . str_pad($tid, 14, '0', STR_PAD_LEFT);

                    $apiSettings = custom::api_settings();
                    //sts_merchant_id_mobile
                    //sts_secret_key_mobile
                    if ($payment->booking_source != 'website' && $payment->booking_source != 'mobile' && ($payment->card_brand == null || $payment->card_brand != 'Mada')) {
                        $SECRET_KEY = $apiSettings->sts_secret_key_mobile;
                        $merchantID = $apiSettings->sts_merchant_id_mobile;
                    } else {
                        $SECRET_KEY = $apiSettings->sts_secret_key_web;
                        $merchantID = $apiSettings->sts_merchant_id_web;
                    }
                    $SECRET_KEY = $apiSettings->sts_secret_key_web;
                    $merchantID = $apiSettings->sts_merchant_id_web;
                    $sts_payment_inquiry_link = $apiSettings->sts_payment_inquiry_link;

                    $secureHash = custom::generate_token(array(
                        "OriginalTransactionID" => $OriginalTransactionID,
                        "MerchantID" => $merchantID,
                        "MessageID" => "2",
                        "Version" => "2.0"
                    ), $SECRET_KEY);

                    $requestQueryArr = [
                        "OriginalTransactionID" => $OriginalTransactionID,
                        "MerchantID" => $merchantID,
                        "MessageID" => "2",
                        "Version" => "2.0",
                        "SecureHash" => $secureHash,
                    ];

                    $newRequestQuery = http_build_query($requestQueryArr);
                    $ch = curl_init($sts_payment_inquiry_link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $newRequestQuery);

                    $output = curl_exec($ch);

                    //$result = parse_url($output);
                    //var_dump($output);
                    $get_response = '';
                    $result = array();
                    $result_ar = explode('&', $output);

                    foreach ($result_ar as $value) {
                        $arr2 = explode('=', $value);
                        $result[$arr2[0]] = $arr2[1];
                    }
                    $receivedSecurehash = $result['Response.SecureHash'];
                    unset($result["Response.SecureHash"]);
                    ksort($result);
                    $responseOrderdString = $SECRET_KEY;

                    foreach ($result as $key => $result_v) {
                        if ($result_v != 'null') {
                            if ($key == 'Response.GatewayStatusDescription' || $key == 'Response.StatusDescription') {
                                $responseOrderdString .= urlencode($result_v); //utf8_encode(string)
                            } else {
                                $responseOrderdString .= $result_v;
                            }
                        }
                        $get_response .= '|' . $key . '=' . $result_v;
                    }

                    //STS response log
                    $log_arr = array();
                    if (isset($result['Response.TransactionID'])) {
                        $log_arr['transaction_id'] = $result['Response.TransactionID'];
                        $log_arr['type'] = 'cronjob';
                        $log_arr['response'] = $get_response;
                        $log_arr['created_at'] = date('Y-m-d H:i:s');
                        $this->page->saveData('booking_sts_log', $log_arr);
                    }

                    //echo "Response Orderd String is " . $responseOrderdString;
                    $generatedsecureHash = hash('sha256', $responseOrderdString);

                    if ($receivedSecurehash != $generatedsecureHash) {
                        echo "<br/><br/>Received Secure Hash does not Equal generated Secure hash";
                    } else {
                        echo "<pre>";
                        print_r($result);
                    }
                }
                $count++;
            }
            if (isset($_REQUEST['cronjob'])) {
                $response_message = 'Cronjob Completed Successfully';
                echo $response_message;
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsTransactionInquiry()
    {
        try {
            // $this->stsTransactionInquiryToTest();exit(); // done by Bilal, its for testing on 02-06-2020
            $orderBy = 'booking_id';
            $sort = 'DESC';
            //the null(sts_attempts is not null) check is inside the following model function
            //$stsPendingPayments = $this->page->getStsBookingsForInquiry('booking_cc_payment', array('status' => 'pending', 'payment_company' => 'sts', 'is_sts_inquired' => '0'), $orderBy, $sort);
            $stsPendingPayments = $this->page->getStsBookingsForInquiry($orderBy, $sort);
            // custom::dump($stsPendingPayments);

            foreach ($stsPendingPayments as $payment) {
                $tid = $payment->booking_id;
                $sts_attempts = $payment->sts_attempts;
                $get_attempts = str_pad($sts_attempts, 3, "0", STR_PAD_LEFT);
                $OriginalTransactionID = "KEY" . $get_attempts . str_pad($tid, 14, '0', STR_PAD_LEFT);

                $apiSettings = custom::api_settings();
                //sts_merchant_id_mobile
                //sts_secret_key_mobile
                if ($payment->booking_source != 'website' && $payment->booking_source != 'mobile' && ($payment->card_brand == null || $payment->card_brand != 'Mada')) {
                    $SECRET_KEY = $apiSettings->sts_secret_key_mobile;
                    $merchantID = $apiSettings->sts_merchant_id_mobile;
                } else {
                    $SECRET_KEY = $apiSettings->sts_secret_key_web;
                    $merchantID = $apiSettings->sts_merchant_id_web;
                }
                $SECRET_KEY = $apiSettings->sts_secret_key_web;
                $merchantID = $apiSettings->sts_merchant_id_web;
                $sts_payment_inquiry_link = $apiSettings->sts_payment_inquiry_link;

                $secureHash = custom::generate_token(array(
                    "OriginalTransactionID" => $OriginalTransactionID,
                    "MerchantID" => $merchantID,
                    "MessageID" => "2",
                    "Version" => "2.0"
                ), $SECRET_KEY);

                $requestQueryArr = [
                    "OriginalTransactionID" => $OriginalTransactionID,
                    "MerchantID" => $merchantID,
                    "MessageID" => "2",
                    "Version" => "2.0",
                    "SecureHash" => $secureHash,
                ];

                $newRequestQuery = http_build_query($requestQueryArr);
                $ch = curl_init($sts_payment_inquiry_link);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $newRequestQuery);

                $output = curl_exec($ch);

                //$result = parse_url($output);
                //var_dump($output);
                $get_response = '';
                $result = array();
                $result_ar = explode('&', $output);

                foreach ($result_ar as $value) {
                    $arr2 = explode('=', $value);
                    $result[$arr2[0]] = $arr2[1];
                }

                // custom::dump($result);
                $receivedSecurehash = $result['Response.SecureHash'];
                unset($result["Response.SecureHash"]);
                ksort($result);
                $responseOrderdString = $SECRET_KEY;

                foreach ($result as $key => $result_v) {
                    if ($result_v != 'null') {
                        if ($key == 'Response.GatewayStatusDescription' || $key == 'Response.StatusDescription') {
                            $responseOrderdString .= urlencode($result_v); //utf8_encode(string)
                        } else {
                            $responseOrderdString .= $result_v;
                        }
                    }
                    $get_response .= '|' . $key . '=' . $result_v;
                }

                //STS response log
                $log_arr = array();
                if (isset($result['Response.TransactionID'])) {
                    $log_arr['transaction_id'] = $result['Response.TransactionID'];
                    $log_arr['type'] = 'cronjob';
                    $log_arr['response'] = $get_response;
                    $log_arr['created_at'] = date('Y-m-d H:i:s');
                    $this->page->saveData('booking_sts_log', $log_arr);
                }

                //echo "Response Orderd String is " . $responseOrderdString;
                $generatedsecureHash = hash('sha256', $responseOrderdString);

                if (isset($result["Response.StatusCode"]) && $result["Response.StatusCode"] == "00000" && $result["Response.GatewayStatusCode"] == "0000") {

                    if ($receivedSecurehash != $generatedsecureHash) {
                        echo "<br/><br/>Received Secure Hash does not Equal generated Secure hash";
                        die;
                    }
                    //echo '<pre>';
                    //print_r($result);
                    //exit;
                    //send the email sms of completed booking
                    $booking_id = $tid; //its primary key "id" of booking table
                    $stsBooking = $this->page->getSingle('booking', array('id' => $booking_id));
                    $from_branch_id = $stsBooking->from_location; //from_location from booking table
                    $transaction_id = $result["Response.TransactionID"];

                    $user_mobile_no = "";
                    if ($stsBooking->type == "corporate_customer") {
                        $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
                        $uid = $booking_corporate_customer->uid;
                        // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                        $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                        $user_mobile_no = $corporate_customer->primary_phone;
                    } elseif ($stsBooking->type == "individual_customer") {
                        $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $booking_id));
                        $uid = $booking_individual_user->uid;
                        $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                        $user_mobile_no = $individual_customer->mobile_no;
                    } else {
                        $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $booking_id));
                        $individual_customer_id = $booking_individual_guest->individual_customer_id;
                        $individual_customer = $this->page->getSingle("individual_customer", array("id" => $individual_customer_id));
                        $user_mobile_no = $individual_customer->mobile_no;
                    }

                    /*$Response_CardNumber = explode("******", $result['Response.CardNumber']);
                    $first_4_digits = $Response_CardNumber[0];
                    $last_4_digits = $Response_CardNumber[1];
                    //$card_brand = $result['Response.PaymentMethod']; Only returned if Version is 2.0 but we are using Version 1.0
                    $card_brand = "";*/

                    $card_brand = explode(':', $result['Response.PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0
                    /*if($card_brand[1] == 'Mada') {
                        $Response_CardNumber = explode("***", $result['Response.CardNumber']);
                    }else{
                        $Response_CardNumber = explode("******", $result['Response.CardNumber']);
                    }*/
                    $first_4_digits = substr($result['Response.CardNumber'], 0, 6);
                    $last_4_digits = substr($result['Response.CardNumber'], -4);

                    $this->sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand[1]);

                    if (!isset($_REQUEST['cronjob'])) {
                        echo "<br>" . $tid . " is moved from pending to completed";
                    }
                } else {
                    if (!isset($_REQUEST['cronjob'])) {
                        echo "<br>" . $tid . " is still pending";
                    }
                }

                $booking_cc_update['is_sts_inquired'] = '1';
                $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $tid));
            }
            if (isset($_REQUEST['cronjob'])) {
                $response_message = 'Cronjob Completed Successfully';
                echo $response_message;
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsInvoicesInquiry()
    {
        try {
            set_time_limit(0);

            $stsPendingPayments = $this->page->getStsPendingPayments();

            foreach ($stsPendingPayments as $payment) {

                $tid = $payment->booking_id;

                $invoiceInfo = $this->page->getSingle('booking_corporate_invoice', array('booking_id' => $tid));
                $attempts = $invoiceInfo->attempts;
                $expiry = $invoiceInfo->expiry;

                for ($i = 0; $i <= $attempts; $i++) {
                    $get_attempts = str_pad($i, 3, "0", STR_PAD_LEFT);
                    //$getInvoiceId = 'KEY' . str_pad($tid, 17, '0', STR_PAD_LEFT);
                    $getInvoiceId = 'KEY' . $get_attempts . str_pad($tid, 14, '0', STR_PAD_LEFT);

                    $apiSettings = custom::api_settings();
                    $SECRET_KEY = $apiSettings->sts_paylater_secret_key;
                    $merchantID = $apiSettings->sts_paylater_merchant_id;
                    $sts_paylater_invoice_inquiry_link = $apiSettings->sts_paylater_invoice_inquiry_link;

                    //invoice inquiry curl
                    $requestQueryArr = [
                        "authenticationToken" => $SECRET_KEY,
                        "merchantID" => $merchantID,
                        "invoiceID" => $getInvoiceId
                    ];

                    $str_length = (int)strlen(json_encode($requestQueryArr)) + 8;

                    $ch = curl_init($sts_paylater_invoice_inquiry_link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "invoice=" . json_encode($requestQueryArr));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . $str_length
                        )
                    );

                    $output = curl_exec($ch);
                    $get_output = json_decode($output);

                    /*if (!isset($_REQUEST['cronjob'])) {
                        echo "Inquiry Link : ".$apiSettings->sts_paylater_invoice_inquiry_link;
                        echo "<br>";
                        echo "<br>";
                        echo "--------------------------";
                        echo "<br>";
                        echo "<br>";
                        echo "Invoice ID: ".$getInvoiceId;
                        echo "<br>";
                        echo "<br>";
                        echo "Inquiry Response : ";
                        echo "<pre>";
                        print_r($get_output);
                        exit;
                    }*/

                    if (isset($get_output->invoiceStatus) && $get_output->invoiceStatus == "PAID") {


                        $booking_id = $tid; //its primary key "id" of booking table
                        $stsBooking = $this->page->getSingle('booking', array('id' => $booking_id));

                        $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
                        $driver_id = $booking_corporate_customer->driver_id;
                        $corporate_driver = $this->page->getSingle("corporate_driver", array("id" => $driver_id));
                        $driver_mobile_no = $corporate_driver->mobile_no;

                        $first_4_digits = substr($get_output->cardNumber, 0, 6);
                        $last_4_digits = substr($get_output->cardNumber, -4);

                        $booking_invoice_update['payment_status'] = 'paid';
                        $booking_invoice_update['continue_inquiry'] = '0';
                        //If RNN number is required then use $get_output->rrn else we can save invoiceID as transaction_id
                        $booking_invoice_update['transaction_id'] = $get_output->rrn;
                        $booking_invoice_update['invoice_id'] = $get_output->invoiceID;
                        $booking_invoice_update['first_4_digits'] = $first_4_digits;
                        $booking_invoice_update['last_4_digits'] = $last_4_digits;
                        $booking_invoice_update['card_brand'] = $get_output->cardType;
                        $booking_invoice_update['transaction_date'] = date('Y-m-d H:i:s');
                        $this->page->updateData('booking_corporate_invoice', $booking_invoice_update, array('booking_id' => $tid));

                        // $this->sendToKeyAdmin($booking_id);
                        // $this->sendToCorporateUser($booking_id);
                        // $this->sendToDriver($booking_id);

                        // send sms to driver
                        $this->sendCorporateBookingSms($stsBooking->reservation_code, $driver_mobile_no);

                        // code to send email and sms to branch agent starts here
                        if ($stsBooking->is_delivery_mode == "yes") {
                            $customer_name = $corporate_driver->first_name . ' ' . $corporate_driver->last_name;
                            $branch_info = $this->page->getSingle('branch', array('id' => $stsBooking->from_location));
                            // $this->send_email_to_branch_agent($booking_id, $branch_info->email, $corporate_driver->first_name, $corporate_driver->last_name);
                            $this->send_sms_to_branch_agent($stsBooking->reservation_code, $branch_info->mobile, $customer_name);
                        }

                        if (!isset($_REQUEST['cronjob'])) {
                            echo "<br>" . $tid . " is paid now";
                        }
                    } else {

                        if (date("Y-m-d") > date($expiry)) {
                            //expiry is yesterday or previous
                            $booking_invoice_update['continue_inquiry'] = '0';
                            $this->page->updateData('booking_corporate_invoice', $booking_invoice_update, array('booking_id' => $tid));
                        }


                        if (!isset($_REQUEST['cronjob'])) {
                            echo "<br>" . $tid . " is still pending";
                        }
                    }
                }
            }

            if (isset($_REQUEST['cronjob'])) {
                $response_message = 'Cronjob Completed Successfully';
                echo $response_message;
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function stsPaymentPushNotification()
    {

        try {
            $post_arr = "";
            foreach ($_REQUEST as $key => $value) {
                $post_arr .= $key . ":" . $value . " | ";
            }
            $message = $post_arr;
            $email = "ahsan@astutesol.com";
            $subject = "STS Payment Notification URL is triggered.";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            $headers .= 'From: Key Rental' . "\r\n";
            mail($email, $subject, $message, $headers);


            //echo '<pre>';
            //print_r($post_arr);
            //exit;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function booking_done() // being used for cash and pay later
    {
        try {
            $data['UserExistWithEmail'] = false;
            if (Session::get('search_data') == "") {
                return redirect($this->lang_base_url . '/home');
            }
            $regions = $this->page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $sessionVals = Session::get('search_data');
            if ($sessionVals['is_delivery_mode'] == 4) {
                $sessionVals['days'] = 30; // because 1 month is to be charged
            }
            $data['booking_info'] = $sessionVals;
            $id = Session::get('car_id');
            $data['car_info'] = $this->page->getSingleCarDetail($id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['from_branch_id']);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['to_branch_id']);
            $data['rent_per_day'] = Session::get('rent_per_day');
            $data['days'] = Session::get('days');
            $data['car_id'] = Session::get('car_id');
            $data['total_rent'] = Session::get('total_rent');
            //echo '<pre>';print_r($data);exit();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'booking-done'));
            $data['payment_form_data'] = Session::get('payment_form_data');
            //print_r($data['payment_form_data']); exit();
            $data['cdw'] = Session::get('cdw_charges');
            $data['cdw_is_one_time_applicable_on_booking'] = Session::get('cdw_charges_is_one_time_applicable_on_booking');

            $data['cdw_plus'] = Session::get('cdw_plus_charges');
            $data['cdw_plus_is_one_time_applicable_on_booking'] = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

            $data['gps'] = Session::get('gps_charges');
            $data['gps_is_one_time_applicable_on_booking'] = Session::get('gps_charges_is_one_time_applicable_on_booking');

            $data['extra_driver'] = Session::get('extra_driver_charges');
            $data['extra_driver_is_one_time_applicable_on_booking'] = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

            $data['baby_seat'] = Session::get('baby_seat_charges');
            $data['baby_seat_is_one_time_applicable_on_booking'] = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

            if ($data['cdw_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_multiply_factor'] = 1;
            } else {
                $data['cdw_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['cdw_plus_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_plus_multiply_factor'] = 1;
            } else {
                $data['cdw_plus_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['gps_is_one_time_applicable_on_booking'] == 1) {
                $data['gps_multiply_factor'] = 1;
            } else {
                $data['gps_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['extra_driver_is_one_time_applicable_on_booking'] == 1) {
                $data['extra_driver_multiply_factor'] = 1;
            } else {
                $data['extra_driver_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['baby_seat_is_one_time_applicable_on_booking'] == 1) {
                $data['baby_seat_multiply_factor'] = 1;
            } else {
                $data['baby_seat_multiply_factor'] = $data['booking_info']['days'];
            }

            $data['active_menu'] = 'cc-payment';
            $data['nationalities'] = $this->page->getAllNationalities($this->lang);
            $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['countries'] = $this->page->getAllCountries($this->lang);
            $data['booking_details'] = $this->page->getSingle('booking', array('id' => Session::get('booking_id')));
            $data['booking_payment_details'] = $this->page->getSingle('booking_payment', array('booking_id' => Session::get('booking_id')));

            if (Session::get('user_type') == 'corporate_customer' && $data['payment_form_data']['payment_method'] == "pay_later") {
                $data['booking_invoice_details'] = $this->page->getSingle('booking_corporate_invoice', array('booking_id' => Session::get('booking_id')));
                //print_r($data['booking_invoice_details']); exit();
                $data['corporate_customer_data'] = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.Session::get('user_id').', uid)')->first();
                $data['expiry_period'] = $data['corporate_customer_data']->expiry_period;
                $data['method_of_payment'] = 'Paylater';

            }
            /*else{
                $data['method_of_payment'] = 'Cash';
            }*/
            $bkin_indi_usr_edit_chk_res = $this->page->getSingle('booking_individual_user', array('booking_id' => Session::get('booking_id')));
            if ($bkin_indi_usr_edit_chk_res) $data['bkin_indi_usr_edit_chk_res'] = true;
            // checking if user exist with inserted email address
            $checkUserExistWithEmail = $this->page->getSingle('users', array('email' => Session::get('payment_form_data')['email']));
            if ($checkUserExistWithEmail) {
                $data['UserExistWithEmail'] = true;
            }
            /*$data['promo_discount'] = $promo_discount = $this->page->checkAutoPromoDiscount(Session::get('car_id'), date('Y-m-d', strtotime($sessionVals['pickup_date'])),$sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id']);*/
            $data['promo_discount_amount'] = Session::get('promo_discount_amount');
            $data['countries'] = $this->page->getAllCountries($this->lang);

            if (Session::get('logged_in_from_frontend') != true) {
                $data['guest_user_id_no'] = Session::get('payment_form_data')['id_no'];
            }

            $this->clearQitafAfterBookingConfirmed(Session::get('booking_id'), 'website booking_done function'); // removing qitaf log from temp table if paid in cash when redirecting to summary screen

            $this->clearNiqatyAfterBookingConfirmed(Session::get('booking_id'), 'website booking_done function'); // removing niqaty log from temp table if paid in cash when redirecting to summary screen

            $data['returnToHome'] = 1;
            if (custom::is_mobile()) {
                return view('frontend/mobile/summary_reg', $data);
            } else {
                return view('frontend/summary_reg', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function booked() // being used for cc & sadad
    {
        try {
            if (isset($_REQUEST['payment_reference'])) {
                $verifyPayment = $this->verifyPayment($_REQUEST['payment_reference']);
                if ($verifyPayment['redirect_url'] != '') {
                    Session::put('error_message_payment', $verifyPayment['error_message']);
                    Session::save();
                    return redirect($verifyPayment['redirect_url']);
                }
            }
            $sessionDataForIPN = array();
            $sessionDataForIPN['booking_id'] = Session::get('booking_id');
            $sessionDataForIPN['user_mobile_no'] = Session::get('payment_form_data')['mobile_no'];
            Session::put('session_data_for_ipn', $sessionDataForIPN);
            Session::save();
            $data['UserExistWithEmail'] = false;
            if (Session::get('search_data') == "") {
                return redirect($this->lang_base_url . '/home');
            }
            // updating payment status if credit card is used
            // $response_from_api = $_POST;
            //echo '<pre>';print_r($response_from_api);exit();
            // Following code is commented so that we are using this in IPN Method
            /*if ($response_from_api['response_message'] == 'Approved') {
                $booking_id = Session::get('booking_id');
                $booking_cc_update['status'] = 'completed';
                $booking_cc_update['transaction_id'] = $response_from_api['transaction_id'];
                $booking_cc_update['first_4_digits'] = $response_from_api['first_4_digits'];
                $booking_cc_update['last_4_digits'] = $response_from_api['last_4_digits'];
                $booking_cc_update['card_brand'] = $response_from_api['card_brand'];
                $booking_cc_update['trans_date'] = date('Y-m-d H:i:s', strtotime($response_from_api['trans_date']));
                $this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
                // moved this email function here to send email after successful credit card transaction
                $this->sendEmailToUser();
                $bookingInfo = $this->page->getSingle('booking', array("id" => $booking_id));
                $userPhoneNo = Session::get('payment_form_data')['mobile_no'];
                $this->sendThankYouSMS($bookingInfo->reservation_code, $userPhoneNo);
            }*/
            $regions = $this->page->getRegions();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            $data['pickup_regions'] = $regionArr;
            $data['dropoff_regions'] = $regionArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $sessionVals = Session::get('search_data');
            if ($sessionVals['is_delivery_mode'] == 4) {
                $sessionVals['days'] = 30; // because 1 month is to be charged
            }
            $data['booking_info'] = $sessionVals;
            $id = Session::get('car_id');
            $data['car_info'] = $this->page->getSingleCarDetail($id);
            $data['pickup_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['from_branch_id']);
            $data['dropoff_branch_info'] = $this->page->getSingleBranchDetail($sessionVals['to_branch_id']);
            $data['rent_per_day'] = Session::get('rent_per_day');
            $data['days'] = Session::get('days');
            $data['car_id'] = Session::get('car_id');
            $data['total_rent'] = Session::get('total_rent');
            //echo '<pre>';print_r($data);exit();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['payment_form_data'] = Session::get('payment_form_data');

            $data['cdw'] = Session::get('cdw_charges');
            $data['cdw_is_one_time_applicable_on_booking'] = Session::get('cdw_charges_is_one_time_applicable_on_booking');

            $data['cdw_plus'] = Session::get('cdw_plus_charges');
            $data['cdw_plus_is_one_time_applicable_on_booking'] = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

            $data['gps'] = Session::get('gps_charges');
            $data['gps_is_one_time_applicable_on_booking'] = Session::get('gps_charges_is_one_time_applicable_on_booking');

            $data['extra_driver'] = Session::get('extra_driver_charges');
            $data['extra_driver_is_one_time_applicable_on_booking'] = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

            $data['baby_seat'] = Session::get('baby_seat_charges');
            $data['baby_seat_is_one_time_applicable_on_booking'] = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

            if ($data['cdw_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_multiply_factor'] = 1;
            } else {
                $data['cdw_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['cdw_plus_is_one_time_applicable_on_booking'] == 1) {
                $data['cdw_plus_multiply_factor'] = 1;
            } else {
                $data['cdw_plus_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['gps_is_one_time_applicable_on_booking'] == 1) {
                $data['gps_multiply_factor'] = 1;
            } else {
                $data['gps_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['extra_driver_is_one_time_applicable_on_booking'] == 1) {
                $data['extra_driver_multiply_factor'] = 1;
            } else {
                $data['extra_driver_multiply_factor'] = $data['booking_info']['days'];
            }

            if ($data['baby_seat_is_one_time_applicable_on_booking'] == 1) {
                $data['baby_seat_multiply_factor'] = 1;
            } else {
                $data['baby_seat_multiply_factor'] = $data['booking_info']['days'];
            }

            $data['parking_fee'] = $sessionVals['parking_fee'];
            $data['tamm_charges_for_branch'] = $sessionVals['tamm_charges_for_branch'];

            $data['nationalities'] = $this->page->getAllNationalities($this->lang);
            $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['countries'] = $this->page->getAllCountries($this->lang);
            $data['active_menu'] = 'cc-payment';
            $data['booking_details'] = $this->page->getSingle('booking', array('id' => Session::get('booking_id')));
            $data['booking_payment_details'] = $this->page->getSingle('booking_payment', array('booking_id' => Session::get('booking_id')));
            /*$data['promo_discount'] = $promo_discount = $this->page->checkAutoPromoDiscount(Session::get('car_id'), date('Y-m-d', strtotime($sessionVals['pickup_date'])),$sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id']);*/
            $data['promo_discount_amount'] = Session::get('promo_discount_amount');
            $data['countries'] = $this->page->getAllCountries($this->lang);
            // checking if user exist with inserted email address
            $checkUserExistWithEmail = $this->page->getSingle('users', array('email' => Session::get('payment_form_data')['email']));
            if ($checkUserExistWithEmail) {
                $data['UserExistWithEmail'] = true;
            }
            $data['method_of_payment'] = 'CC';
            if (Session::get('logged_in_from_frontend') != true) {
                $data['guest_user_id_no'] = Session::get('payment_form_data')['id_no'];
            }

            $this->clearQitafAfterBookingConfirmed(Session::get('booking_id'), 'website booked function'); // removing qitaf log from temp table when showing booking summary after successful booking after cc

            $this->clearNiqatyAfterBookingConfirmed(Session::get('booking_id'), 'website booked function'); // removing niqaty log from temp table when showing booking summary after successful booking after cc
            $data['returnToHome'] = 1;
            if (custom::is_mobile()) {
                return view('frontend/mobile/summary_reg', $data);
            } else {
                return view('frontend/summary_reg', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function booking_detail($booking_id) // This is for logged in user
    {
        try {
            $booking_id = custom::decode_with_jwt($booking_id);
            $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'booking-detail'));
            $data['active_menu'] = 'cc-payment';
            if ($bookingDetail->type == 'corporate_customer') {
                $data['booking_detail'] = $this->page->getSingleBookingDetailsForCorporate($booking_id);
            } else {
                $data['booking_detail'] = $this->page->getSingleBookingDetails($booking_id);
            }
            if (!isset($data['booking_detail']) || $data['booking_detail'] == '') {
                exit();
            }
            if (custom::is_mobile()) {
                return view('frontend/mobile/manage_booking_logged_in', $data);
            } else {
                return view('frontend/manage_booking_logged_in', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkIfBookingExistWithRefNo(Request $request)
    {
        try {
            $booking = new Booking();
            $get_by['reservation_code'] = $request->input('booking_ref_no');
            $checkIfRecordExist = $this->page->getSingle('booking', $get_by);

            $booking_detail = "";
            if ($checkIfRecordExist) {
                $booking_id = $checkIfRecordExist->id;
                $booking_detail = $booking->getLatestBookingForEachUserForFrontend(true, '', 0, 0, 0, '', '', false, false, $booking_id);
            }

            if ($booking_detail && $booking_detail[0]->tcount > 0) {
                $response['status'] = true;
                $response['record_id'] = $checkIfRecordExist->id;
            } else {
                $response['status'] = false;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.reservation_not_found_msg');
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkIfBookingExistWithRefNoStep2(Request $request)
    {
        try {
            $reservation_code = $request->input('booking_ref_no');
            $email_field_for_manage = $request->input('email_field_for_manage');
            $booking_details = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
            if ($booking_details->type == "corporate_customer") {
                $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_details->id));
                $uid = $booking_corporate_customer->uid;
                // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                $corporate_driver = $this->page->getSingle("corporate_driver", array("id" => $booking_corporate_customer->driver_id));
                $email = $corporate_customer->primary_email;
                $mobile_no = $corporate_customer->primary_phone;
                $id_no = $corporate_driver->id_no;
                $company_code = $corporate_customer->company_code;
            } elseif ($booking_details->type == "individual_customer") {
                $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $booking_details->id));
                $uid = $booking_individual_user->uid;
                $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                $email = $individual_customer->email;
                $mobile_no = $individual_customer->mobile_no;
                $id_no = $individual_customer->id_no;
                $company_code = false;
            } else {
                $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $booking_details->id));
                $individual_customer_id = $booking_individual_guest->individual_customer_id;
                $individual_customer = $this->page->getSingle("individual_customer", array("id" => $individual_customer_id));
                $email = $individual_customer->email;
                $mobile_no = $individual_customer->mobile_no;
                $id_no = $individual_customer->id_no;
                $company_code = false;
            }
            if ($booking_details &&
                (
                    $email_field_for_manage == $email ||
                    $email_field_for_manage == $mobile_no ||
                    $email_field_for_manage == $id_no ||
                    ($company_code && $email_field_for_manage == $company_code)
                )
            ) {
                $response['status'] = true;
                $response['record_id'] = custom::encode_with_jwt($booking_details->id);
            } else {
                $response['status'] = false;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = ($this->lang == 'eng' ? 'Sorry, we couldn\'t find this email with this booking reference.' : 'عفوا، هذا البريد الإلكتروني غير مطابق مع بيانات الحجز');
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function manage_booking($booking_id) // This is for manage booking at home page
    {
        try {
            $booking_id = custom::decode_with_jwt($booking_id);
            $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'cc-payment';
            if (!empty($bookingDetail)) {
                if ($bookingDetail->type == 'corporate_customer') {
                    $data['booking_detail'] = $this->page->getSingleBookingDetailsForCorporate($booking_id);
                } else {
                    $data['booking_detail'] = $this->page->getSingleBookingDetails($booking_id);
                }
            } else {
                exit();
            }

            if ($data['booking_detail'] == '') {
                exit();
            }
            if (custom::is_mobile()) {
                return view('frontend/mobile/manage_booking_guest', $data);
            } else {
                return view('frontend/manage_booking_guest', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function save_extra_infor_after_reservation(Request $request)
    {
        try {
            if (Session::get('logged_in_from_frontend') == true) {
                $user_id = Session::get('user_id');
            } else {
                $user_id = 0;
            }
            if ($request->input('want_to_register') == '1') {

                $isPasswordStrong = custom::isPasswordStrong($request->input('password'), $this->lang);
                if (!$isPasswordStrong['status']) {
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = $isPasswordStrong['message'];
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                }

                if ($request->input('password') != $request->input('confirm_password')) {
                    $response['title'] = Lang::get('labels.error');
                    $response['message'] = Lang::get('labels.password_and_confirm_password_not_match_msg');
                    $response['redirectURL'] = '';
                    echo json_encode($response);
                    exit();
                } else {
                    $userData['name'] = Session::get('payment_form_data')['first_name'] . ' ' . Session::get('payment_form_data')['last_name'];
                    $userData['email'] = Session::get('payment_form_data')['email'];
                    $userData['password'] = md5($request->input('password'));
                    $userData['type'] = 'individual_customer';
                    $userData['created_at'] = date('Y-m-d H:i:s');
                    $userData['updated_at'] = date('Y-m-d H:i:s');
                    $user_id = $this->page->saveData('users', $userData);
                    $this->page->updateData('booking', array('type' => 'individual_customer'), array('id' => Session::get('booking_id')));
                    $guestBookings = $this->page->getMultipleRows('booking_individual_guest', array('individual_customer_id' => Session::get('individual_customer_id')), 'booking_id');

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
                    $this->page->deleteData('booking_individual_guest', array('individual_customer_id' => Session::get('individual_customer_id')));
                    //$this->page->deleteData('booking_individual_guest', array('booking_id' => Session::get('booking_id')));
                    //$this->page->saveData('booking_individual_user', array('booking_id' => Session::get('booking_id'), 'uid' => $user_id));
                }
            }
            //echo '<pre>';print_r($request->input());exit();
            if ($request->input('avoid_waiting') == '1') {
                if ($request->input('id_date_type') == 'gregorian') {
                    $customer_data['id_expiry_date'] = $id_expiry_date = date('Y-m-d', strtotime($request->input('id_expiry_date')));
                } else {
                    $date_for_hijri = explode('-', $request->input('id_expiry_date'));
                    $customer_data['id_expiry_date'] = $id_expiry_date = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                }
                $customer_data['id_date_type'] = $id_date_type = ($request->input('id_date_type') == 'gregorian' ? 'G' : 'H');
                $customer_data['id_country'] = $id_country = $request->input('id_country');
                $customer_data['dob'] = date('Y-m-d', strtotime($request->input('dob')));
                $customer_data['license_no'] = custom::convertArabicNumbersToEnglish($request->input('license_no'));
                $customer_data['license_expiry_date'] = date('Y-m-d', strtotime($request->input('license_expiry_date')));
                // $customer_data['id_image'] = custom::uploadFile($request->file('id_image'));
                // $customer_data['license_image'] = custom::uploadFile($request->file('license_image'));
                $customer_data['nationality'] = $request->input('nationality');
                $customer_data['job_title'] = $request->input('job_title');
                $customer_data['sponsor'] = htmlspecialchars($request->input('sponsor'));
                $customer_data['license_country'] = $request->input('license_country');
                $customer_data['street_address'] = $request->input('street_address');
                $customer_data['district_address'] = $request->input('district_address');
            }
            $update_info_by['email'] = Session::get('payment_form_data')['email'];
            $customer_data['uid'] = $user_id;
            $this->page->updateData('individual_customer', $customer_data, $update_info_by);
            custom::send_account_verification_links($user_id, $this->lang_base_url, $this->lang);
            if ($user_id > 0 && Session::get('logged_in_from_frontend') == true) {
                $messageRes = Lang::get('labels.providing_detail_msg');
            } elseif ($user_id > 0 && Session::get('logged_in_from_frontend') != true) {
                $messageRes = Lang::get('labels.account_created_msg');
            } else {
                $messageRes = Lang::get('labels.providing_detail_msg');
            }
            $response['title'] = Lang::get('labels.success');
            $response['message'] = $messageRes;
            $response['redirectURL'] = 'home';
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function create_login_find_user(Request $request)
    {
        try {
            $fetch_by['id_type'] = $request->input('id_type');
            $fetch_by['id_no'] = $request->input('id_no');
            $userExist = $this->page->getSingle('individual_customer', $fetch_by);
            //echo '<pre>';print_r($userExist);exit();
            if (!$userExist)  //! || $userExist->uid != 0
            {
                $response['status'] = false;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.user_info_not_found_msg');
                echo json_encode($response);
                exit();
            } elseif ($userExist && $userExist->uid != 0) {
                $response['status'] = false;
                $response['title'] = Lang::get('labels.error');
                $response['message'] = Lang::get('labels.id_number_exist_msg');
                echo json_encode($response);
                exit();
            } else {
                Session::put('found_user', $userExist->id);
                session()->save();
                $smsSent = $this->sendVerificationCode($userExist->id);
                if (is_bool($smsSent) == true && $smsSent == true) {
                    $response['status'] = true;
                    $response['message'] = "A verification code is sent via SMS";
                } else {
                    $response['status'] = false;
                    $response['message'] = $smsSent;
                }
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function verifySmsCheck(Request $request)
    {
        try {
            $verification_code = $request->get('verification_code');
            $sentVerifyCode = session()->get('verification_code');
            if ($verification_code == $sentVerifyCode) {
                $userId = Session::get('found_user');
                session()->put('found_user_verify', $userId);
                $data['status'] = true;
                $data['redirectURL'] = 'create-login';
            } else {
                $data = array();
                $data['status'] = false;
                $data['message'] = "Your verification code is invalid";
                //Session::forget('found_user');
                session()->put('found_user_verify', "");
            }

            //for localhost or key.ed.sa booking the sms code can be entered any code.
            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'kra.ced.sa') $data['status'] = true;

            if (session()->has('bidForEdit')) {
                $data['bid'] = session()->get('bidForEdit');
            }

            return response()->json($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function create_login_step_2()
    {
        try {
            $hasUserVerify = session()->get('found_user_verify');
            if ($hasUserVerify != NULL && $hasUserVerify != "") {
                $userId = $hasUserVerify;
                $data['base_url'] = $this->base_url;
                $data['lang_base_url'] = $this->lang_base_url;
                $data['user_data'] = $this->page->getSingle('individual_customer', array('id' => $userId));
                $data['lang'] = $this->lang;
                $data['active_menu'] = 'loyalty';
                $data['nationalities'] = $this->page->getAllNationalities($this->lang);
                $data['countries'] = $this->page->getAllCountries($this->lang);
                $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
                $data['site_settings'] = custom::site_settings();
                $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
                $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
                return view('frontend/create_login_step_2', $data);
            } else {
                if ($this->lang == "arb") {
                    $redirect = 'create-ind-login';
                } else {
                    $redirect = 'en/create-ind-login';
                }
                return redirect($redirect);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function resendVerifyCode(Request $request)
    {
        try {
            $cancel_booking_id = $request->input('cancelBookingId');
            if ((int)$cancel_booking_id > 0) {
                //its cancel case
                if (session()->get('individual_customer_id') != null) {
                    $userId = session()->get('individual_customer_id');
                } else {
                    $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $cancel_booking_id));
                    if ($booking_individual_user) {
                        $uid = $booking_individual_user->uid;
                        $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                        $userId = $individual_customer->id;
                    } else {
                        $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $cancel_booking_id));
                        $userId = $booking_individual_guest->individual_customer_id;
                    }
                }
            } else {
                $userId = session()->get('individual_customer_id');
            }
            $response = array();
            // send sms
            if ($userId != null) {
                $sent = $this->sendVerificationCode($userId);
            } else {
                $userExistId = Session::get('found_user');
                $sent = $this->sendVerificationCode($userExistId);
            }
            //===========
            if (is_bool($sent) == true) {
                $response['status'] = true;
                $response['message'] = "Your verification code has been sent again.";
            } else {
                $response['status'] = false;
                $response['message'] = $sent;
            }
            return response()->json($response);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    /*private function sendSmsCommonFunction($mobile = "",$booking_code = ""){
        //here sms send to user for resend verification code
        if($this->lang == "eng"){
            $verificationCodeMsg = "Verification code for key.sa is: ";
            $reservationMsg = "Thank you for booking at key.sa Reservation# ";
        }else{
            $verificationCodeMsg = "رمز التحقق للمفتاح هو: ";
            $reservationMsg = "شكرا للحجز في key.sa الحجز # ";
        }
        $code = rand(100000, 999999);
        session()->put('verification_code', $code);
        session()->save();
        //user register verification condition
        if($mobile == "" && $booking_code == "") {
            $userExistId = Session::get('found_user');
            $smsMsg = $verificationCodeMsg . $code;
            $userInfo = $this->page->getSingle('individual_customer', array('id' => $userExistId));
            $smsPhone = str_replace(array('+', ' '),'',$userInfo->mobile_no);
        }
        //cancel booking condition
        elseif($mobile != "" && $booking_code == ""){
            $smsMsg = $verificationCodeMsg . $code;;
            $smsPhone = str_replace(array('+', ' '),'',$mobile);
        }
        else{ //reservation thankyou sms
            $smsMsg = $reservationMsg.$booking_code;
            $smsPhone = str_replace(array('+', ' '),'',$mobile);
        }
            //$smsPhone = "923219410733";
        //echo $smsPhone." ".$smsMsg; exit;
        $sentSms = custom::sendSMS($smsPhone,$smsMsg,$this->lang);
        return $sentSms;
    }*/
    public function my_booking()
    {
        // check if user is logged in
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $booking = new Booking();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $user_type = Session::get("user_type");
            if ($user_type == "corporate_customer") {
                $data['user_data'] = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
            } else {
                $data['user_data'] = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
            }
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'my-bookings'));
            $data['active_menu'] = 'loyalty';
            $data['site_settings'] = custom::site_settings();
            $data['my_bookings'] = $booking->getLatestBookingForEachUserForFrontend(false, '', 0, 0, Session::get('user_id'));
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            if (custom::is_mobile()) {
                return view('frontend/mobile/my_booking', $data);
            } else {
                return view('frontend/my_booking', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function my_history()
    {
        // check if user is logged in
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $booking = new Booking();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $user_type = Session::get("user_type");
            if ($user_type == "corporate_customer") {
                $data['user_data'] = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
            } else {
                $data['user_data'] = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
            }
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            //$data['countries'] = $this->page->getAllCountries();
            $data['site_settings'] = custom::site_settings();
            $data['my_history'] = $booking->getLatestBookingForEachUserForFrontend(false, '', 0, 0, Session::get('user_id'), 'history_bookings');
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            if (custom::is_mobile()) {
                return view('frontend/mobile/my_history', $data);
            } else {
                return view('frontend/my_history', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function my_invoices()
    {
        // check if user is logged in
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $booking = new Booking();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $user_type = Session::get("user_type");
            if ($user_type == "corporate_customer") {
                $data['user_data'] = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
            } else {
                $data['user_data'] = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
            }
            $user_id = $data['user_data']->id;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            //$data['countries'] = $this->page->getAllCountries();
            $data['site_settings'] = custom::site_settings();
            //$data['my_invoices'] = DB::table('corporate_invoices')
                //->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                //->join('corporate_invoices_contract', 'corporate_invoices_contract.invoice_id', '=', 'corporate_invoices.id')
                //->select('corporate_invoices_contract.*','corporate_customer.company_code','corporate_invoices.invoice_no','corporate_invoices.invoice_issue_date')
                //->where('corporate_customer.id','=',$user_id)
                //->get();

            $query = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->select('corporate_invoices.*')
                ->where('corporate_customer.id', '=', $user_id)
                ->whereNull('corporate_invoices.invoice_type');
            if (isset($_REQUEST['month']) && isset($_REQUEST['year']) && (int)$_REQUEST['month'] > 0 && $_REQUEST['year'] > 0) {
                $query->whereYear('invoice_issue_date', '=', $_REQUEST['year'])->whereMonth('invoice_issue_date', '=', $_REQUEST['month']);
            }
            $data['my_invoices_total_count'] = $query->count();

            $offset = 0;
            $limit = 10;
            $query = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->select('corporate_invoices.*')
                ->where('corporate_customer.id', '=', $user_id)
                ->whereNull('corporate_invoices.invoice_type');
            if (isset($_REQUEST['month']) && isset($_REQUEST['year']) && (int)$_REQUEST['month'] > 0 && $_REQUEST['year'] > 0) {
                $query->whereYear('invoice_issue_date', '=', $_REQUEST['year'])->whereMonth('invoice_issue_date', '=', $_REQUEST['month']);
            }
            $data['my_invoices'] = $query->orderBy('invoice_issue_date', 'DESC')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $data['due_balance'] = DB::table("corporate_invoices")
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->join('corporate_invoices_contract', 'corporate_invoices_contract.invoice_id', '=', 'corporate_invoices.id')
                ->where('corporate_customer.id', '=', $user_id)
                ->sum('corporate_invoices_contract.balance');

            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            return view('frontend/my_invoices', $data);
            /*if(custom::is_mobile()){
                return view('frontend/mobile/my_invoices', $data);
            }else{
                return view('frontend/my_invoices', $data);
            }*/
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function my_invoices_paginate(Request $request) {
        $limit = 10;
        $offset = ($request->page - 1) * $limit;
        $query = DB::table('corporate_invoices')
            ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
            ->select('corporate_invoices.*')
            ->where('corporate_customer.id', '=', $request->user_id)
            ->whereNull('corporate_invoices.invoice_type');
        if (isset($_REQUEST['month']) && isset($_REQUEST['year']) && (int)$_REQUEST['month'] > 0 && $_REQUEST['year'] > 0) {
            $query->whereYear('invoice_issue_date', '=', $_REQUEST['year'])->whereMonth('invoice_issue_date', '=', $_REQUEST['month']);
        }
        $my_invoices = $query->orderBy('invoice_issue_date', 'DESC')
            ->offset($offset)
            ->limit($limit)
            ->get();
        if ($my_invoices) {
            $html = '';
            foreach ($my_invoices as $invoice) {
                $contracts = custom::get_invoice_amount($invoice->id);
                if($contracts['due_balance'] > 0) {
                    $add_style = 'background-color: rgba(248, 177, 47, 0.38);';
                } else {
                    $add_style = '';
                }

                $html .= '<tr style="'.$add_style.'"><td>'.$invoice->invoice_no.'</td><td>'.date('F-Y',strtotime($invoice->invoice_issue_date)).'</td><td>'.number_format($contracts['amount'], 2).'</td><td>'.number_format($contracts['paid'], 2).'</td><td>'.number_format($contracts['due_balance'], 2).'</td><td class="printBtn"><a class="edBtn" href="'.custom::baseurl('en/print-invoice').'/'.custom::encode_with_jwt($invoice->id).'" target="_blank">'.trans('labels.eng_print').'</a><a class="edBtn" href="'.custom::baseurl('print-invoice').'/'.base64_encode($invoice->id).'" target="_blank">'.trans('labels.arb_print').'</a></td></tr>';
            }

            $show_load_more_btn = true;
        } else {
            $html = '<tr><td colspan="6" style="text-align: center;color: red;font-weight: 500;">'.trans('labels.no_record_found').'</td></tr>';
            $show_load_more_btn = false;
        }

        echo json_encode(['html' => $html, 'show_load_more_btn' => $show_load_more_btn]);exit;
    }

    public function lease_invoices()
    {
        // check if user is logged in
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/home');
            }
            $booking = new Booking();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $user_type = Session::get("user_type");
            if ($user_type == "corporate_customer") {
                $data['user_data'] = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
            } else {
                $data['user_data'] = $this->page->getSingle('individual_customer', array('id' => Session::get('individual_customer_id')));
            }
            $user_id = $data['user_data']->id;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            $data['site_settings'] = custom::site_settings();
            $data['my_invoices'] = DB::table('corporate_invoices')
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->select('corporate_invoices.*')
                ->where('corporate_customer.id', '=', $user_id)
                ->whereNotNull('corporate_invoices.invoice_type')
                ->get();
            $data['due_balance'] = DB::table("corporate_invoices")
                ->join('corporate_customer', 'corporate_customer.id', '=', 'corporate_invoices.customer_id')
                ->join('corporate_lease_transactions', 'corporate_lease_transactions.invoice_id', '=', 'corporate_invoices.id')
                ->where('corporate_customer.id', '=', $user_id)
                ->sum('corporate_lease_transactions.bills');

            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            return view('frontend/lease_invoices', $data);
            /*if(custom::is_mobile()){
                return view('frontend/mobile/my_invoices', $data);
            }else{
                return view('frontend/my_invoices', $data);
            }*/
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function cancelBooking(Request $request)
    {
        try {
            $site = custom::site_settings();
            $cancel_hours_before_pickup_from_db = $site->cancel_in_hours;
            $cancel_percentage_for_before_pickup_from_db = $site->cancel_percentage;
            $cancel_hours_after_pickup_from_db = $site->post_booking_cancellation_hours;
            $smtp = custom::smtp_settings();
            $id = $request->input('id');
            $booking_id['id'] = $id;
            $booking_status['booking_status'] = "Cancelled";
            $charges = $request->input('apply_cancel_charges');
            $booking_detail = $this->page->getSingle("booking", array('id' => $id));
            $booking_payment_rec = $this->page->getSingle("booking_payment", array('booking_id' => $id));
            // save charges amount here
            $data_cancel_charges['booking_id'] = $id;
            $data_cancel_charges['cancel_time'] = date("Y-m-d H:i:s");
            /*if ($charges) {
                $data_cancel_charges['cancel_charges'] = round(((int)$booking_payment_rec->total_sum * $cancel_percentage_for_before_pickup_from_db) / 100, 2);
            } else {
                $data_cancel_charges['cancel_charges'] = 0;
            }*/
            // making booking cancellation charges logic here
            if ($charges) {
                $cancel_time = date("Y-m-d H:i:s");
                $cancel_time = new \DateTime($cancel_time);
                $pickup_date = $booking_detail->from_date;
                $pickup_date = new \DateTime($pickup_date);
                // 1. if user cancels 2 or more hours (as set up from backend) before pickup time, no charges and status cancelled
                // 2. if user cancels 2 or less hours (as set up from backend) before pickup time, percent of total rent sum coming from backend will be deducted from total sum and status cancelled
                // 3. if user cancels 24 or less hours (as set up from backend) after pickup time, one day rent to be deducted from total (rent per day * days) and status cancelled
                // 4. if user cancels 24 or more hours (as set up from backend) after pickup time, one day rent to be deducted from total (rent per day * days) and status expired
                if ($pickup_date->getTimestamp() >= $cancel_time->getTimestamp()) {
                    // 1,2
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
                    // 3,4
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
            } else {
                $data_cancel_charges['cancel_charges'] = 0;
            }
            $data_cancel_charges['cancellation_reason'] = custom::cancellation_reasons($request->cancellation_reason, $this->lang);
            $data_cancel_charges['sync'] = "N";
            $data_cancel_charges['synced_at'] = "0000-00-00";
            $this->page->saveData("booking_cancel", $data_cancel_charges);
            $booking_status['sync'] = "N";
            $updated = $this->page->updateData('booking', $booking_status, $booking_id);
            $isUpdate = array();
            if ($updated) {
                if ($booking_detail->type == 'corporate_customer') {
                    $details = $this->page->getSingle("booking_corporate_customer", array('booking_id' => $id));
                    $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
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
                // sending cancellation email to user
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
                    // sending booking cancelled sms to admin
                    //$adminSms = "Dear Admin, \n A booking with reservation code " . $booking_detail->reservation_code . " has been cancelled by user.";
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

                $isUpdate['isUpdate'] = true;
                $isUpdate['title'] = "Canceled";
                $isUpdate['updateMsg'] = Lang::get('labels.booking_canceled_msg');
            } else {
                $isUpdate['isUpdate'] = false;
                $isUpdate['updateMsg'] = Lang::get('labels.some_error_msg');
            }
            // code to send email and sms to branch agent starts here
            // stopped this code from working because fozan did not asked to do this. if he says, we will make it work.
            if (1 == 2 && $booking_detail->is_delivery_mode == 'yes') {
                $customer_name = $user_details->first_name . ' ' . $user_details->last_name;
                $branch_info = $this->page->getSingle('branch', array('id' => $booking_detail->from_location));
                // $this->send_email_to_branch_agent($booking_id, $branch_info->email, $user_details->first_name, $user_details->last_name);
                $this->send_sms_to_branch_agent($booking_detail->reservation_code, $branch_info->mobile, $customer_name);
            }
            // ends here
            return response()->json($isUpdate);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendCancellationEmail($booking_id, $user_type)
    {
        try {
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
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendCancellationEmailToAdmin($booking_id, $user_type)
    {
        try {
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
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function cancelBookingVerification(Request $request)
    {
        try {
            $cancel_booking_id = $request->input('cancelBookingId');
            if (session()->get('individual_customer_id') != null) {
                $userId = session()->get('individual_customer_id');
            } else {
                $booking_details = $this->page->getSingle('booking', array('id' => $cancel_booking_id));
                if ($booking_details->type == "corporate_customer") {

                    $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $cancel_booking_id));
                    $uid = $booking_corporate_customer->uid;
                    // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                    $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                    $userId = $corporate_customer->id;

                } elseif ($booking_details->type == "individual_customer") {
                    $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $cancel_booking_id));
                    $uid = $booking_individual_user->uid;
                    $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                    $userId = $individual_customer->id;
                } else {
                    $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $cancel_booking_id));
                    $userId = $booking_individual_guest->individual_customer_id;
                }
            }
            $smsSent = $this->sendVerificationCodeViaWhatsapp($userId, $cancel_booking_id);
            if (is_bool($smsSent) == true && $smsSent == true) {
                $response['status'] = true;
                $response['message'] = ($this->lang == 'eng' ? 'A verification code is sent via SMS' : 'تم إرسال رقم التحقق عن طريق رسالة قصيرة');
            } else {
                $response['status'] = false;
                $response['message'] = $smsSent;
            }
            return response()->json($response);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function removeBooking(Request $request)
    {
        try {
            $delete_parent_by['id'] = $request->input('booking_id');
            $delete_child_by['booking_id'] = $request->input('booking_id');
            $this->page->deleteData('booking_payment', $delete_child_by);
            $this->page->deleteData('booking_individual_user', $delete_child_by);
            $this->page->deleteData('booking_individual_payment_method', $delete_child_by);
            $this->page->deleteData('booking_individual_guest', $delete_child_by);
            $this->page->deleteData('booking_cc_payment', $delete_child_by);
            $this->page->deleteData('booking_sadad_payment', $delete_child_by);
            $this->page->deleteData('booking', $delete_parent_by);
            Session::forget('booking_id');
            if ($this->lang == 'arb') {
                $redirectTo = '/payment';
            } else {
                $redirectTo = 'en/payment';
            }
            return redirect($redirectTo);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // function for news letter subscribe on mail chimp
    public function newsLetter(Request $request)
    {
        try {
            $api_settings = custom::api_settings();
            $email = $request->input('news_letter');
            $response = array();
            $response['message'] = Lang::get('labels.subscribe_news_letter_msg');
            $response['title'] = Lang::get('labels.news_letter');
            $response['success'] = true;
            $apiKey = $api_settings->mailchimp_api_key;
            $listID = $api_settings->mailchimp_list_id;
            //$apiKey = 'b594d4824017c492e60fd83142651a37-us16';
            //$listID = '5d7f9f7694';
            // MailChimp API URL
            $memberID = md5(strtolower($email));
            $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
            $check_subscribed = $this->mc_checklist($email, false, $apiKey, $listID, $dataCenter);
            if ($check_subscribed == '404' || $check_subscribed == '' || !$check_subscribed) {
                // member information
                // member information
                $json = json_encode([
                    'email_address' => $email,
                    'status' => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => '',
                        'LNAME' => ''
                    ]
                ]);
                // send a HTTP POST request with curl
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                // store the status message based on response code
                if ($httpCode == 200) {
                    $msg = "You are successfully subscribe for news letter";
                } else {
                    switch ($httpCode) {
                        case 214:
                            $msg = "News letter already subscribed";
                            break;
                        default:
                            $response['message'] = "News letter un know error";
                            $response['title'] = "ERROR";
                            break;
                    }
                    $response['message'] = $msg;
                }
            } else {
                $msg = "News letter already subscribed";
                $response['message'] = $msg;
            }
            return response()->json($response);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // function used in newsLetter function to check if user already subscribe.
    private function mc_checklist($email, $debug, $apikey, $listid, $server)
    {
        $userid = md5($email);
        $auth = base64_encode('user:' . $apikey);
        $data = array(
            'apikey' => $apikey,
            'email_address' => $email
        );
        $json_data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://' . $server . '.api.mailchimp.com/3.0/lists/' . $listid . '/members/' . $userid);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic ' . $auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        $result = curl_exec($ch);
        if ($debug) {
            var_dump($result);
            exit();
        }
        $json = json_decode($result);
        return $json->{'status'};
    }

    /*public function getServerTime(Request $request)
    {
        $data = array();
        $data['allowed'] = 1;
        $data['apply_cancel_charges'] = 0;
        $data['message'] = Lang::get('labels.cancel_booking_msg');
        $where_bp['booking_id'] = $request->input('bookingId');
        $booking_payment = $this->page->getSingle("booking_payment", $where_bp);
        $rent_price = $booking_payment->rent_price; //per day
        $where['id'] = $request->input('bookingId');
        $pickUpDate = $this->page->getSingle("booking", $where);
        // get hours and precentage dynamically from db. and calculate
        $cancelInfo = $this->page->getAll('setting_site_settings');
        $cancel_in_hours = $cancelInfo[0]->cancel_in_hours;
        $cancel_percentage = $cancelInfo[0]->cancel_percentage;
        $cancel_minutes = $cancel_in_hours * 60;
        $cancel_charges = ($cancel_percentage * $rent_price) / 100;
        //=============================
        $time = date("Y-m-d H:i:s");
        $pick_up_date = new \DateTime($pickUpDate->from_date);
        $cancel_date = new \DateTime($time);
        $diffInSeconds = $pick_up_date->getTimestamp() - $cancel_date->getTimestamp();
        $days = floor($diffInSeconds / 86400);
        $hours = floor(($diffInSeconds - ($days * 86400)) / 3600);
        $minutes = floor(($diffInSeconds - ($days * 86400) - ($hours * 3600)) / 60);
        $totalMinutes = $days * 24 * 60 + $hours * 60 + $minutes;
        if ($pick_up_date->getTimestamp() < $cancel_date->getTimestamp()) {
            $data['allowed'] = 0;
            $data['message'] = Lang::get('labels.cancel_booking_not_allowed_msg');
        } elseif ($totalMinutes < $cancel_minutes) {
            $data['apply_cancel_charges'] = 1;
            if ($this->lang == "eng") {
                $successMsg = "Your booking will be cancelled and " . $cancel_percentage . " % will be deducted from the first day that is (" . $cancel_charges . " " . Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
            } else {
                $successMsg = "Your booking will be cancelled and " . $cancel_percentage . " % will be deducted from the first day that is (" . $cancel_charges . " " . Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
            }
            $data['message'] = $successMsg;
        }
        return response()->json($data);
    }*/
    public function getServerTime(Request $request)
    {
        // 1. if user cancels 2 or more hours (as set up from backend) before pickup time, no charges and status cancelled
        // 2. if user cancels 2 or less hours (as set up from backend) before pickup time, percent of total rent sum coming from backend will be deducted from total sum and status cancelled
        // 3. if user cancels 24 or less hours (as set up from backend) after pickup time, one day rent to be deducted from total (rent per day * days) and status cancelled
        // 4. if user cancels 24 or more hours (as set up from backend) after pickup time, one day rent to be deducted from total (rent per day * days) and status expired
        try {
            $data = array();
            $data['allowed'] = 1;
            $data['apply_cancel_charges'] = 0;
            $data['message'] = Lang::get('labels.cancel_booking_msg');
            $where_bp['booking_id'] = $request->input('bookingId');
            $booking_payment_rec = $this->page->getSingle("booking_payment", $where_bp);
            $rent_price = $booking_payment_rec->rent_price; //per day
            $where['id'] = $request->input('bookingId');
            $booking_detail = $this->page->getSingle("booking", $where);
            // get hours and precentage dynamically from db. and calculate
            $cancelInfo = $this->page->getAll('setting_site_settings');
            $site = custom::site_settings();
            $cancel_hours_before_pickup_from_db = $site->cancel_in_hours;
            $cancel_percentage_for_before_pickup_from_db = $site->cancel_percentage;
            $cancel_hours_after_pickup_from_db = $site->post_booking_cancellation_hours;
            $cancel_minutes = $cancel_hours_before_pickup_from_db * 60;
            $cancel_charges = ($cancel_percentage_for_before_pickup_from_db * $rent_price) / 100;
            //=============================
            /*$time = date("Y-m-d H:i:s");
            $pick_up_date = new \DateTime($booking_detail->from_date);
            $cancel_date = new \DateTime($time);
            $diffInSeconds = $pick_up_date->getTimestamp() - $cancel_date->getTimestamp();*/
            $cancel_time = date("Y-m-d H:i:s");
            $cancel_time = new \DateTime($cancel_time);
            $pickup_date = $booking_detail->from_date;
            $pickup_date = new \DateTime($pickup_date);
            if ($pickup_date->getTimestamp() >= $cancel_time->getTimestamp()) {
                // 1,2
                $start = Carbon::now();
                $end = new Carbon($booking_detail->from_date);
                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] >= (int)$cancel_hours_before_pickup_from_db * 60) {
                    if ($this->lang == "eng") {
                        $data['message'] = "Your booking will be cancelled and your whole paid amount will be refunded within 2 weeks.";
                    } else {
                        $data['message'] = "سوف يتم إلغاء حجزك و سوف يتم إسترداد كامل المبلغ خلال أسبوعين";
                    }
                    $data['apply_cancel_charges'] = 1;
                } elseif ((int)$cancel_hours_before_pickup_from_db * 60 > $difference['minutes']) {
                    $data['apply_cancel_charges'] = 1;
                    $data_cancel_charges['cancel_charges'] = round(((int)$booking_payment_rec->rent_price * $cancel_percentage_for_before_pickup_from_db) / 100, 2);
                    if ($this->lang == "eng") {
                        $data['message'] = "Your booking will be cancelled and " . $cancel_percentage_for_before_pickup_from_db . " % will be deducted from the first day that is (" . $cancel_charges . " " . Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
                    } else {
                        $data['message'] = "Your booking will be cancelled and " . $cancel_percentage_for_before_pickup_from_db . " % will be deducted from the first day that is (" . $cancel_charges . " " . Lang::get('labels.currency') . ") and will be refunded within 2 weeks.";
                    }
                }
            } elseif ($cancel_time->getTimestamp() > $pickup_date->getTimestamp()) {
                // 3,4
                $start = new Carbon($booking_detail->from_date);
                $end = Carbon::now();
                $difference = custom::getDateDifference($start, $end);
                if ($difference['minutes'] <= (int)$cancel_hours_after_pickup_from_db * 60) {
                    $data['apply_cancel_charges'] = 1;
                    $cancel_charges = $booking_payment_rec->rent_price;
                    if ($this->lang == "eng") {
                        $data['message'] = "Your booking will be cancelled and " . $cancel_charges . " " . Lang::get('labels.currency') . " will be deducted from the total paid amount and will be refunded within 2 weeks.";
                    } else {
                        $data['message'] = "سوف يتم إلغاء حجزك وسوف يتم خصم " . $cancel_charges . " " . Lang::get('labels.currency') . "ل من مجموع المبلغ المدفوع، و سوف يتم إسترداد المبلغ خلال أسبوعين";
                    }
                } elseif ((int)$cancel_hours_after_pickup_from_db * 60 < $difference['minutes']) {
                    $data['allowed'] = 0;
                    $data['message'] = Lang::get('labels.cancel_booking_not_allowed_msg');
                    //$cancel_charges = $booking_payment_rec->rent_price;
                }
            }
            return response()->json($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function my_bookings_filter(Request $request)
    {
        try {
            $user_type = Session::get("user_type");
            $html = '';
            $key_name = $request->input('key_name');
            if ($request->input('date') != '') {
                $date = date('Y-m-d', strtotime($request->input('date')));
            } else {
                $date = "";
            }
            $records_for = $request->input('records_for');
            $bookings = $this->page->getFilteredBookings($key_name, $date, $records_for, $user_type);
            if (count($bookings) > 0) {
                foreach ($bookings as $booking) {
                    if ($booking->booking_status == 'Not Picked') {
                        $highlightClass = 'notPicked';
                        if ($this->lang == 'eng')
                            $status_text = 'NOT PICKED';
                        else
                            $status_text = 'لم يتم اختيارها';
                    } elseif ($booking->booking_status == 'Picked') {
                        $highlightClass = 'pickedUp';
                        if ($this->lang == 'eng')
                            $status_text = 'PICKED UP';
                        else
                            $status_text = 'التقط';
                    } elseif ($booking->booking_status == 'Completed') {
                        $highlightClass = 'completed';
                        if ($this->lang == 'eng')
                            $status_text = 'COMPLETED';
                        else
                            $status_text = 'منجز';
                    } elseif ($booking->booking_status == 'Cancelled') {
                        $highlightClass = 'cancelled';
                        if ($this->lang == 'eng')
                            $status_text = 'CANCELLED';
                        else
                            $status_text = 'ألغيت';
                    }
                    $html .= '<div id="myBookingRow_' . $booking->id . '" class="myBookingRow ' . $highlightClass . '">
                    <div class="topName">
                        <h2>' . ($this->lang == 'eng' ? $booking->car_type_eng_title : $booking->car_type_arb_title) . ' ' . ($this->lang == 'eng' ? $booking->car_eng_title : $booking->car_arb_title) . ' ' . $booking->year . '<span> ' . Lang::get('labels.or_similar') . ' </span>
                            <strong> ' . ($this->lang == 'eng' ? $booking->car_category_eng_title : $booking->car_category_arb_title) . ' </strong>
                        </h2>
                    </div>
                    <div class="topOptions">
                        <h3 id="statusMsg">' . $status_text . '</h3>
                        <div class="seprator"> |</div>
                        <h4>' . Lang::get('labels.your_reservation') . '
                            <span> ' . $booking->reservation_code . ' </span></h4>
                        <div class="buttonsOpt">';
                    if ($booking->booking_status == "Not Picked") {
                        $site = custom::site_settings();
                        $post_booking_hours_from_db = $site->post_booking_cancellation_hours . ' hours';
                        $time = date("Y-m-d H:i:s");
                        $pick_up_date = new \DateTime($booking->from_date);
                        $cancel_date = new \DateTime($time);
                        $start = new Carbon($booking->from_date);
                        $end = Carbon::now();
                        $difference = custom::getDateDifference($start, $end);
                        if (($pick_up_date->getTimestamp() > $cancel_date->getTimestamp()) || ($difference['minutes'] <= $site->post_booking_cancellation_hours * 60)) {
                            $html .= '<a id="bCancelBtn_' . $booking->id . '" class="bCancelBtn" href="javascript:void(0);" onclick="cancelBooking(' . $booking->id . ');">
                                        <button class="grayishButton"><img src="' . $this->base_url . '/public/frontend/images/cancel.png" alt="X" height="14" width="15"/> 
                                        ' . ($this->lang == 'eng' ? 'Cancel' : 'إلغاء') . ' 
                                        </button>
                                    </a>';
                        }

                        if (custom::is_booking_editable($booking->id)) {
                            $html .= '<a class="bCancelBtn"
                                               href="' . $lang_base_url . '/edit-booking/' . custom::encode_with_jwt($booking->id) . '">';
                            $html .= '<button class="grayishButton">
                                                    <img src="' . $this->base_url . '/public/frontend/images/edit.png" alt="E" height="14" width="15"/>
                                                    ' . ($this->lang == 'eng' ? 'Edit' : 'تعديل') . '
                                                        </button>';
                            $html .= '</a>';
                        }
                    }
                    $html .= '<a href="' . $this->lang_base_url . '/print-booking/' . custom::encode_with_jwt($booking->reservation_code . '||EDxjrybEuppO') . '" target="_blank">
                                <button class="grayishButton">
                                <img src="' . $this->base_url . '/public/frontend/images/print.png" alt="P" height="14" width="15"/>' . Lang::get('labels.print') . '
                                </button>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>';
                    if ($booking->image1 != '') {
                        $car_image_path = $this->base_url . '/public/uploads/' . $booking->image1;
                    } else {
                        $car_image_path = $this->base_url . '/public/frontend/images/no_image_available.jpg';
                    }
                    $html .= '<div class="clearfix"></div>
                    <a href="' . $this->lang_base_url . '/booking-detail/' . custom::encode_with_jwt($booking->id) . '"
                       class="bookingLink">
                        <div class="mBookingDTL">
                            <div class="col imgBox">
                                <div class="displayTable">
                                    <div class="disTableCell">
                                        <img src="' . $car_image_path . '" alt="Car" height="132" width="274"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col twoBig">
                                <label>' . Lang::get('labels.pick_up') . '</label>
                                <ul>
                                    <li title="' . ($this->lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from) . '">
                                        <img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/location.png"
                                             alt="" height="18"
                                             width="13"/>' . ($this->lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from) . '
                                    </li>
                                    <li><img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/calendar.png"
                                             alt="" height="18"
                                             width="16"/> ' . date('d / m / Y', strtotime($booking->from_date)) . '
                                    </li>
                                    <li><img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/clock.png"
                                             alt="" height="18"
                                             width="18"/> ' . date('H:i A', strtotime($booking->from_date)) . '
                                    </li>
                                </ul>
                            </div>
                            <div class="col twoBig">
                                <label>' . Lang::get('labels.drop_off') . '</label>
                                <ul>
                                    <li title="' . ($this->lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to) . '">
                                        <img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/location.png"
                                             alt="" height="18"
                                             width="13"/>' . ($this->lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to) . '
                                    </li>
                                    <li><img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/calendar.png"
                                             alt="" height="18"
                                             width="16"/>' . date('d / m / Y', strtotime($booking->to_date)) . '
                                    </li>
                                    <li><img class="abImg"
                                             src="' . $this->base_url . '/public/frontend/images/clock.png"
                                             alt="" height="18"
                                             width="18"/> ' . date('H:i A', strtotime($booking->to_date)) . '
                                    </li>
                                </ul>
                            </div>
                            <div class="col small features ' . ($booking->min_age > 0 ? 'contains-min-age' : '') . '">
                                <label>' . Lang::get('labels.features') . '</label>
                                <ul>
                                    <li>
                                        <div class="spIconF person"></div>
                                        <p>' . $booking->no_of_passengers . '</p></li>
                                    <li>
                                        <div class="spIconF transmition"></div>
                                        <p>' . ($booking->transmission == 'Auto' ? ($this->lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($this->lang == 'eng' ? 'Manual' : 'عادي')) . '</p>
                                    </li>
                                    <li>
                                        <div class="spIconF door"></div>
                                        <p>' . $booking->no_of_doors . '</p></li>
                                    <li>
                                        <div class="spIconF bag"></div>
                                        <p>' . $booking->no_of_bags . '</p></li>';
                    if ($booking->min_age > 0) {
                        $html .= '<li>
                                        <div class="spIconF minAge"></div>
                                        <p>' . $booking->min_age . '</p></li>';
                    }
                    $html .= ' </ul>
                            </div>
                            <div class="col colorBg">
                                <div class="displayTable">
                                    <div class="disTableCell">
                                        <p>' . Lang::get('labels.total_rent_for_capital') . ' ' . $booking->no_of_days . ' ' . Lang::get('labels.days') . '</p>
                                        <strong class="bigText">' . number_format($booking->total_sum, 2) . '
                                            ' . Lang::get('labels.currency') . '
                                            </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>';
                }
            } else {
                $html .= '<div class="noResultFound"><span>' . Lang::get('labels.no_record_found') . '</span></div>';
            }
            $response['html'] = $html;
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function printMapPopups($branch_id)
    {
        try {
            $locations = $this->page->getBranchesAndCities($branch_id);
            $pdf = $this->generatePdfForMap($locations);
            return $pdf;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function generatePdfForMap($locations)
    {
        //echo "<pre>"; print_r($locations); exit;
        try {
            $data = array();
            $data['locations'] = $locations;
            if ($this->lang == "eng") {
                $view = "frontend.pdf.print_map_popup";
            } else {
                $view = "frontend.pdf.print_map_popup_ar";
            }
            // return view($view, $data);
            $this->pdf->loadView($view, $data);
            $this->pdf->setOption('encoding', 'UTF-8');
            return @$this->pdf->inline();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendThankYouSMS($reservation_code, $userPhoneNo)
    {
        //$lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        $lang = $bookingInfo->lang;
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Thank you for booking at key.sa Reservation# ";
        } else {
            $smsMsg = "Thank you for booking at key.sa Reservation# ";
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

    private function sendVerificationCode($userId, $booking_id = 0)
    {
        //$booking_id is basically to overwrite in this function in case of corporate call center booking

        $lang = $this->lang;
        //========send send sms to user for verification code
        if ($lang == "eng") {
            $smsMsg = "Verification code for key.sa is: ";
        } else {
            $smsMsg = "Verification code for key.sa is: ";
        }
        $randNumber = custom::generateRand();
        session()->put('verification_code', $randNumber);
        session()->save();
        $smsMsg .= $randNumber;

        /*
         * This is loyalty_id which customer enter in loyalty popup in main search
         * It is saving in booking_corporate_customer table
         * We are showing this in excel export when paylater booking is done with call center on
         * customer_id_no_for_loyalty
         * sms will be sent to individual customer mobile instead of corporate customer mobile
        */
        if (custom::isCorporateLoyalty()) {
            //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
            $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
            $user_id = $booking_corporate_customer->driver_id;

            //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
            $userInfo = $this->page->getSingle("corporate_driver", array("id" => $user_id));
            $mobile_no = $userInfo->mobile_no;
        } else {
            $corporateUserInfo = $this->page->getSingle('corporate_customer', array('id' => $userId));
            if ($corporateUserInfo) {
                $mobile_no = $corporateUserInfo->primary_phone;
            } else {
                $userInfo = $this->page->getSingle('individual_customer', array('id' => $userId));
                $mobile_no = $userInfo->mobile_no;
            }
        }


        $smsPhone = str_replace(array('+', ' '), '', $mobile_no);
        return $smsSent = custom::sendSMS($smsPhone, $smsMsg, $lang);
        //=================
    }

    private function sendVerificationCodeViaWhatsapp($userId, $booking_id = 0, $isForCancel = true)
    {
        //$booking_id is basically to overwrite in this function in case of corporate call center booking

        $lang = $this->lang;
        //========send send sms to user for verification code
        if ($lang == "eng") {
            $smsMsg = "Verification code for key.sa is: ";
        } else {
            $smsMsg = "Verification code for key.sa is: ";
        }
        $randNumber = custom::generateRand();
        session()->put('verification_code', $randNumber);
        session()->save();
        $smsMsg .= $randNumber;

        /*
         * This is loyalty_id which customer enter in loyalty popup in main search
         * It is saving in booking_corporate_customer table
         * We are showing this in excel export when paylater booking is done with call center on
         * customer_id_no_for_loyalty
         * sms will be sent to individual customer mobile instead of corporate customer mobile
        */
        if (custom::isCorporateLoyalty()) {
            //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
            $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
            $user_id = $booking_corporate_customer->driver_id;

            //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
            $userInfo = $this->page->getSingle("corporate_driver", array("id" => $user_id));
            $mobile_no = $userInfo->mobile_no;
            $email_address = $userInfo->email;
            $name = $userInfo->first_name . ' ' . $userInfo->last_name;
            $gender = "male";
        } else {
            $corporateUserInfo = $this->page->getSingle('corporate_customer', array('id' => $userId));
            if ($corporateUserInfo) {
                $mobile_no = $corporateUserInfo->primary_phone;
                $email_address = $corporateUserInfo->primary_email;
                $name = $corporateUserInfo->primary_name;
                $gender = "male";
            } else {
                $userInfo = $this->page->getSingle('individual_customer', array('id' => $userId));
                $mobile_no = $userInfo->mobile_no;
                $email_address = $userInfo->email;
                $name = $userInfo->first_name . ' ' . $userInfo->last_name;
                $gender = $userInfo->gender;
            }
        }


        $smsPhone = str_replace(array('+', ' '), '', $mobile_no);

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
            $subject = $isForCancel ? 'Booking Cancellation OTP' : 'Booking Edit OTP';
            $msg = $smsMsg;
        } else {
            $subject = $isForCancel ? 'Booking Cancellation OTP' : 'Booking Edit OTP';
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
        //=================
    }

    public function checkIfIdNoExist(Request $request)
    {
        try {
            $user_id_no = $request->input('user_id_no');
            $record = $this->page->getSingle('individual_customer', array('id_no' => $user_id_no));
            if ($record) {
                $resArr['status'] = true;
            } else {
                $resArr['status'] = false;
            }
            echo json_encode($resArr);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function validateEmailAndIdNo(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $email = $request->input('email');
            $id_no = $request->input('id_no');
            $ind_customer_with_email = $this->page->getSingle('individual_customer', array('email' => $email));
            $ind_customer_with_id_no = $this->page->getSingle('individual_customer', array('id_no' => $id_no));
            if ($ind_customer_with_email && $ind_customer_with_id_no) {
                $id_with_email = $ind_customer_with_email->id;
                $id_with_id_no = $ind_customer_with_id_no->id;
                if ($id_with_email != $id_with_id_no) {
                    $resArr['status'] = false;
                    $resArr['message'] = 'This email address is used with some other ID number.';
                } else {
                    $resArr['status'] = true;
                }
            } else {
                $resArr['status'] = true;
            }
            echo json_encode($resArr);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function validateLoginEmail(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            $posted_email = $request->input('email');
            $id_no = $request->input('id_no');
            $ind_customer_with_id_no = $this->page->checkUser($id_no);
            if ($ind_customer_with_id_no) {
                $user_email = $ind_customer_with_id_no->email;
                if ($user_email != $posted_email) {
                    $response['status'] = false;
                    $response['message'] = 'Your login email will also be updated';
                } else {
                    $response['status'] = true;
                }
            } else {
                $response['status'] = true;
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function something_went_wrong()
    {
        try {
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'home';
            return view('frontend/something_went_wrong', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function offer()
    {
        try {
            $req = $_REQUEST['r'];
            $req = explode('|', $req);
            $ids = base64_decode($req[0]);
            $car_model_ids = explode(',', $ids);
            $id = $car_model_ids[0];
            Session::put('offer_car_model_id', $id);
            Session::save();
            $promotion_id = base64_decode($req[1]);
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'fleet';
            $data['mod_id'] = $id;
            $data['show_mod_id'] = true;
            $pickupRegionsArr = array();
            $dropoffRegionsArr = array();
            $regionsForOffer = array();
            $branch_ids_arr = array();
            $branch_ids_in_arr = array();
            $all_cities_for_this_region = array();
            $all_branches_for_this_city = array();
            // New Logic
            $all_promos_for_this_model = $this->page->getOffersForCarModel($id);
            if ($all_promos_for_this_model) {
                foreach ($all_promos_for_this_model as $car_promotion) {
                    if ($car_promotion->branch_id > 0) {
                        $branch_ids_arr[] = $this->page->getBranchIdsForPromo($car_promotion->branch_id);
                    } elseif ($car_promotion->city_id > 0) {
                        $all_branches_for_this_city = $this->page->getMultipleRows('branch', array('city_id' => $car_promotion->city_id));
                        foreach ($all_branches_for_this_city as $branch) {
                            $branch_ids_arr[] = $this->page->getBranchIdsForPromo($branch->id);
                        }
                    } elseif ($car_promotion->region_id > 0) {
                        $all_cities_for_this_region = $this->page->getMultipleRows('city', array('region_id' => $car_promotion->region_id));
                        foreach ($all_cities_for_this_region as $city) {
                            $all_branches_for_this_city = $this->page->getMultipleRows('branch', array('city_id' => $city->id));
                            foreach ($all_branches_for_this_city as $branch) {
                                $branch_ids_arr[] = $this->page->getBranchIdsForPromo($branch->id);
                            }
                        }
                    }
                }
            }

            /*currently i have implement array_filter to remove empty array because of working onlive server
            , we can deep debug it later*/
            $branch_ids_arr = array_filter($branch_ids_arr);
            foreach ($branch_ids_arr as $bid) {
                $branch_ids_in_arr[] = $bid->id;
            }
            //echo '<pre>';print_r($branch_ids_in_arr);exit();
            if (count($branch_ids_in_arr) > 0) {
                $regionsForOffer = $this->page->getBranchesForOffer($branch_ids_in_arr); // get all branches for this promotion
            }
            //echo '<pre>';print_r($regionsForOffer);exit();
            if ($regionsForOffer && count($regionsForOffer) > 0) {
                $pickupRegions = $regionsForOffer;
            } else {
                $pickupRegions = $this->page->getRegions(); // if branches for this offer not found, then showing all branches
            }
            $dropoffRegions = $this->page->getRegions();
            //echo '<pre>';print_r($promotion_detail);exit();
            foreach ($pickupRegions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $pickupRegionsArr[$city][] = $region;
            }
            foreach ($dropoffRegions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $dropoffRegionsArr[$city][] = $region;
            }
            $data['pickup_regions'] = $pickupRegionsArr;
            $data['dropoff_regions'] = $dropoffRegionsArr;
            // getDeliveryRegions
            $regions = $this->page->getDeliveryRegions();
            $regionArr = array();
            foreach ($regions as $key => $region) {
                if ($this->lang == "eng") {
                    $city = $region->cit . '|' . $region->c_eng_title;
                } else {
                    $city = $region->cit . '|' . $region->c_arb_title;
                }
                $regionArr[$city][] = $region;
            }
            // echo '<pre>';print_r($regionArr);exit();
            $data['delivery_pickup_regions'] = $regionArr;
            $data['delivery_dropoff_regions'] = $regionArr;
            $stdObj = $this->page->getAllCars($ids);
            $data['car_models'] = json_decode(json_encode($stdObj), true);
            $data['has_searchbar'] = true;
            $data['promotion_id'] = $promotion_id;
            $data['show_book_now_btn'] = count($car_model_ids) > 1 ? true : false;
            if (custom::is_mobile()) {
                return view('frontend.mobile.offer')->with($data);
            } else {
                return view('frontend.offer')->with($data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function offers()
    {
        try {
            $i = 0;
            $myArray = array();
            $offers = $this->page->getOfferPageOffers();

            foreach ($offers as $offer) {
                $myArray[$i]['title'] = ($this->lang == 'eng' ? $offer->eng_title : $offer->arb_title);
                $myArray[$i]['desc'] = ($this->lang == 'eng' ? $offer->eng_home_offer_desc : $offer->arb_home_offer_desc);
                $myArray[$i]['image'] = ($this->lang == 'eng' ? $offer->image1 : $offer->image2);
                $myArray[$i]['id'] = $offer->id;
                $car_models_for_promotion_offer = custom::get_car_models_against_promotion($offer->id);
                $myArray[$i]['car_model_id'] = implode(',', $car_models_for_promotion_offer);
                $myArray[$i]['clickable'] = $offer->car_model_id == -1 ? 0 : 1;
                $i++;
            }

            $data['sliders'] = $myArray;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['content'] = (array)$this->page->getSingle('loyalty_program', array('id' => '1'));
            //echo "<pre>"; print_r($data['content']); exit;
            $data['content']['eng_meta_title'] = 'offers';
            $data['content']['arb_meta_title'] = 'العروض';
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'offers';
            if (custom::is_mobile()) {
                return view('frontend/mobile/offers', $data);
            } else {
                return view('frontend/offers', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function redirectToBookingPage(Request $request)
    {
        try {
            $branch_id = $request->input('branch_id');
            $lang = $request->input('lang');
            $branch_data = $this->page->getSingle('branch', array('id' => $branch_id));
            $city_data = $this->page->getSingle('city', array('id' => $branch_data->city_id));
            $region_data = $this->page->getSingle('city', array('id' => $city_data->region_id));
            $sessionArr['from_region_id'] = $region_data->id;
            $sessionArr['from_city_id'] = $city_data->id;
            $sessionArr['from_branch_id'] = $branch_id;
            $sessionArr['to_city_id'] = $city_data->id;
            $sessionArr['to_branch_id'] = $branch_id;
            $sessionArr['from_branch_name'] = ($this->lang == 'eng' ? $branch_data->eng_title : $branch_data->arb_title);
            $sessionArr['to_branch_name'] = ($this->lang == 'eng' ? $branch_data->eng_title : $branch_data->arb_title);
            $sessionArr['pickup_date'] = "";
            $sessionArr['pickup_time'] = "";
            $sessionArr['dropoff_date'] = "";
            $sessionArr['dropoff_time'] = "";
            $sessionArr['is_delivery_mode'] = (Session::has('search_data') && isset(Session::get('search_data')['is_delivery_mode']) ? Session::get('search_data')['is_delivery_mode'] : 0);
            $sessionArr['pickup_delivery_coordinate'] = (Session::has('search_data') && isset(Session::get('search_data')['pickup_delivery_coordinate']) ? Session::get('search_data')['pickup_delivery_coordinate'] : "");
            $sessionArr['dropoff_delivery_coordinate'] = (Session::has('search_data') && isset(Session::get('search_data')['dropoff_delivery_coordinate']) ? Session::get('search_data')['dropoff_delivery_coordinate'] : "");
            $sessionArr['delivery_charges'] = (Session::has('search_data') && isset(Session::get('search_data')['delivery_charges']) ? Session::get('search_data')['delivery_charges'] : 0);
            Session::put('search_data', $sessionArr);
            Session::save();
            $response['lang'] = $lang;
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function paytabsIPN(Request $request)
    {
        $site = custom::site_settings();
        $emailString = '';
        $response_from_api = $_POST;
        foreach ($_POST as $key => $value) {
            $emailString .= $key . '|' . $value . ',';
        }
        $email['subject'] = 'Paytabs IPN hit at ' . custom::getSiteName($this->lang);
        $email['fromEmail'] = 'no-reply@paytabs.com';
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = 'kholoud.j@edesign.com.sa';
        $email['ccEmail'] = 'ahsan@astutesol.com';
        $email['bccEmail'] = '';
        $email['attachment'] = '';
        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = $this->lang_base_url;
        $content['name'] = 'Fozan Baghdadi';
        $content['msg'] = $emailString;
        $content['gender'] = 'male';
        //custom::sendEmail2('general', $content, $email, 'eng');
        if ($_SERVER['REQUEST_METHOD'] == 'GET') { // The request is using the GET method
            exit();
        }
        if (isset($response_from_api['first_4_digits']) && $response_from_api['first_4_digits'] > 0 && $response_from_api['card_brand'] != 'Unknown') { // this is a case of credit card
            // response code 5001: Payment has been accepted successfully
            // response code 5002: Payment has been forcefully accepted
            if (isset($response_from_api['response_code'])) {
                $res = explode('-', $response_from_api['order_id']);
                $booking_id = $res[0];
                $lang = $this->lang = $res[1];
                $from_branch_id = $res[2];
                $user_mobile_no = $response_from_api['customer_phone'];
                if ($response_from_api['response_code'] == 100 || $response_from_api['response_code'] == 5002) {
                    $booking_cc_update['status'] = 'completed';
                    $booking_cc_update['transaction_id'] = $response_from_api['transaction_id'];
                    $booking_cc_update['first_4_digits'] = $response_from_api['first_4_digits'];
                    $booking_cc_update['last_4_digits'] = $response_from_api['last_4_digits'];
                    $booking_cc_update['card_brand'] = $response_from_api['card_brand'];
                    $booking_cc_update['trans_date'] = date('Y-m-d H:i:s', strtotime($response_from_api['datetime']));
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
                        // $this->send_email_to_branch_agent($booking_id, $branch_info->email, $first_name, $last_name);
                        $this->send_sms_to_branch_agent($bookingInfo->reservation_code, $branch_info->mobile, $customer_name);
                    }
                    if ($bookingInfo->type == "individual_customer" || $bookingInfo->type == "guest") {
                        // Deducting and updating customer's redeem points
                        $this->add_or_deduct_loyalty_points_for_customer($booking_id, 'deduct');
                    }
                }
            }
        } else { // here if sadad used
            if ($response_from_api['response_code'] == 5001) {
                $res = explode('-', $response_from_api['order_id']);
                $booking_id = $res[0];
                $this->lang = $res[1];
                $from_branch_id = $res[2];
                $user_mobile_no = $response_from_api['customer_phone'];
                $booking_sadad_update['s_status'] = 'completed';
                $booking_sadad_update['s_transaction_id'] = $response_from_api['transaction_id'];
                $booking_sadad_update['s_invoice_id'] = $response_from_api['invoice_id'];
                $booking_sadad_update['s_trans_date'] = date('Y-m-d H:i:s', strtotime($response_from_api['datetime']));
                $this->page->updateData('booking_sadad_payment', $booking_sadad_update, array('s_booking_id' => $booking_id));
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
                    // $this->send_email_to_branch_agent($booking_id, $branch_info->email, $first_name, $last_name);
                    $this->send_sms_to_branch_agent($bookingInfo->reservation_code, $branch_info->mobile, $customer_name);
                }
                if ($bookingInfo->type == "individual_customer" || $bookingInfo->type == "guest") {
                    // Deducting and updating customer's redeem points
                    $this->add_or_deduct_loyalty_points_for_customer($booking_id, 'deduct');
                }
            }
        }
        // hitting cronjob to sync bookings
        $cronjob_url = custom::baseurl('/') . '/cronjob/setDataCronJob';
        $response = file_get_contents($cronjob_url);
        var_dump($response);
        exit();
    }

    public function checkIfUserBlacklistedOrSimahBlock(Request $request)
    {
        try {
            $id_no = $request->input('id_no');
            $id_type = $request->input('id_type');
            $first_element_of_id_number = substr($id_no, 0, 1);
            $checkIfUserIsBlacklist = $this->page->getSingle('individual_customer', array('id_no' => $id_no));
            if ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->black_listed == "Y") {
                $response['message'] = ($this->lang == 'eng' ? 'Sorry, your account have been blocked.' : 'عذرا، تم إيقاف حسابك');
                $response['status'] = false;
                echo json_encode($response);
                exit();
            } elseif ($checkIfUserIsBlacklist && $checkIfUserIsBlacklist->simah_block == "yes") {
                $response['message'] = ($this->lang == 'eng' ? 'Dear Customer.<br>
You have an outstanding amount payable to ' . custom::getSiteName($this->lang) . '.<br>
Kindly remit the payment at our nearest branch to use our website.' : '<br>.عزيزي العميل
<br>.توجد لديكم مديونية لدى شركة ' . custom::getSiteName($this->lang) . ' لتأجير السيارات
.نرجو منكم التفضل بزيارة أي من فروعنا لتسوية المديونية والإستفادة من خدمات موقع ' . custom::getSiteName($this->lang));
                $response['status'] = false;
                echo json_encode($response);
                exit();
            } elseif ($id_type == '243' && $first_element_of_id_number != '1') {
                $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                $response['status'] = false;
                echo json_encode($response);
                exit();
            } elseif ($id_type == '68' && $first_element_of_id_number != '2') {
                $response['message'] = ($this->lang == 'eng' ? 'ID number is incorrect' : 'رقم الهوية غير صحيح');
                $response['status'] = false;
                echo json_encode($response);
                exit();
            } else {
                $response['status'] = true;
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function searchAreaBranchFilter(Request $request)
    {
        try {
            $branchesArr = array();
            $citiesArr = array();
            // getting branches by car model from availability
            $filter = $request->input('filter');
            $branches = $this->page->getBranchesForSearchFilter($filter);
            //echo '<pre>';print_r($branches);exit();
            if ($branches) {
                foreach ($branches as $branch) {
                    $branchesArr[] = $branch->branch_id;
                    $citiesArr[] = $branch->city_id;
                }
            }
            $response['cities'] = implode(',', $citiesArr);
            $response['branches'] = implode(',', $branchesArr);
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function validateSaudiID(Request $request)
    {
        try {
            $id_no = $request->input('id_no');
            $is_valid_id = custom::validateSaudiID($id_no, $this->lang);
            if ($is_valid_id) {
                $response['status'] = true;
            } else {
                $response['status'] = false;
            }
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function payWithSadad($olp_id, $posted_data, $booking_id)
    {
        if (Session::get('search_data')['is_delivery_mode'] == 1) {
            $from_branch_id = $posted_data['from_branch_id'];
        } else {
            $from_branch_id = 0;
        }
        $id = Session::get('car_id');
        $car_info = $this->page->getSingleCarDetail($id);
        $api_settings = custom::api_settings();
        //mail('bilal_ejaz@astutesol.com', 'Sadad function called', 'email sent');
        $payment_data = array(
            'merchant_email' => $api_settings->paytabs_merchant_email,
            'secret_key' => $api_settings->paytabs_secret_key,
            'site_url' => custom::baseurl('/'),
            'return_url' => $this->lang_base_url . '/booked',
            'title' => $posted_data['first_name'] . ' ' . $posted_data['last_name'],
            'cc_first_name' => $posted_data['first_name'],
            'cc_last_name' => $posted_data['last_name'],
            'cc_phone_number' => $posted_data['mobile_no'],
            'phone_number' => $posted_data['mobile_no'],
            'email' => $posted_data['email'],
            'products_per_title' => ($this->lang == 'eng' ? $car_info->eng_title . ' | ' . $car_info->car_type_eng_title : $car_info->arb_title . ' | ' . $car_info->car_type_arb_title),
            'unit_price' => Session::get('total_amount_for_transaction'),
            'quantity' => '1',
            'other_charges' => '0.00',
            'amount' => Session::get('total_amount_for_transaction'),
            'discount' => '0.00',
            'currency' => 'SAR',
            'reference_no' => $booking_id . '-' . $this->lang . '-' . $from_branch_id . '-' . 'individual_customer',
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
            header('Location:' . $object->payment_url);
            exit();
        } else {
            //$error_message = ($this->lang == 'eng' ? 'The OLP ID you entered is not valid.' : 'حساب سداد المدخل غير صحيح');
            $error_message = $object->result;
            Session::put('error_message_payment', $error_message);
            Session::save();
            header('Location:' . $this->lang_base_url . '/payment');
            exit();
        }
        //redirect($object->payment_url);
        /*echo "Response:<br><pre>";
        print_r($object);
        exit();*/
    }

    private function verifyPayment($payment_reference)
    {
        $api_settings = custom::api_settings();
        $data['merchant_email'] = $api_settings->paytabs_merchant_email;
        $data['secret_key'] = $api_settings->paytabs_secret_key;
        $data['payment_reference'] = $payment_reference;
        $request_string = http_build_query($data);
        $response_data = $this->sendRequest('https://www.paytabs.com/apiv2/verify_payment', $request_string);
        //$response_data = json_decode($response_data);
        $response_data = json_decode($response_data, true);
        $response_code = $response_data['response_code'];
        if ($response_code != 100 && $response_code != 5001 && $response_code != 5002) {
            $response_message = $response_data['result'];
            $responseArr['error_message'] = $response_message;
            $responseArr['redirect_url'] = $this->lang_base_url . '/payment';
            return $responseArr;
        } else {
            $responseArr = $response_data;
            $responseArr['error_message'] = '';
            $responseArr['redirect_url'] = '';
            return $responseArr;
        }
    }

    private function sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand)
    {
        $booking_cc_update['status'] = 'completed';
        $booking_cc_update['transaction_id'] = $transaction_id;
        $booking_cc_update['first_4_digits'] = $first_4_digits;
        $booking_cc_update['last_4_digits'] = $last_4_digits;
        $booking_cc_update['card_brand'] = $card_brand;
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

        if ($bookingInfo->is_delivery_mode == "yes") {
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

    }

    public function verifySTSPayment(Request $request)
    {
        //http://kra.ced.sa/en/verifySTSPayment?debug=1
        //change the route to get for debugging
        $debug = false;
        if (isset($_GET['debug']))
            $debug = true;

        //For debugging
        if (!$debug) {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = (int)microtime(true) * 1000; //output to be like: 1495004320389
        }

        if (Session::has('total_amount_for_transaction')) {
            $total_amount = Session::get('total_amount_for_transaction');
        } else {
            $total_amount = 10;
        }

        //Set parameters for hash
        $apiSettings = custom::api_settings();
        $SECRET_KEY = $apiSettings->sts_secret_key_web;
        $MERCHANT_ID = $apiSettings->sts_merchant_id_web;
        $parameters = [];
        $parameters["TransactionID"] = $booking_id;
        $parameters["MerchantID"] = $MERCHANT_ID;
        $parameters["Amount"] = $total_amount * 100;
        $parameters["CurrencyISOCode"] = "682";
        $parameters["MessageID"] = "1";
        $parameters["Quantity"] = "1";
        $parameters["Channel"] = "0";
        $parameters["ItemID"] = "130";
        $parameters["PaymentMethod"] = "1";
        $parameters["PaymentDescription"] = urlencode("Payment");
        $parameters["Language"] = "En";
        $parameters["ThemeID"] = "1000000001";
        $parameters["ResponseBackURL"] = "http://key.ed.sa/KeySts/Response.php";
        $parameters["Version"] = "1.0";
        $parameters["ClientIPaddress"] = "79.183.118.666";


        //Generate secure hash
        ksort($parameters);
        $orderedString = $SECRET_KEY;
        foreach ($parameters as $param) {
            $orderedString .= $param;
        }
        $secureHash = hash('sha256', $orderedString, false);

        //For debugging
        if ($debug) {
            echo "<PRE>";
            echo "<BR>=======hash generating keys===========<BR>";
            PRINT_R($parameters);
            echo "<BR><BR><BR>";
        }

        //parameters for sending to STS
        $requestQueryArr = $parameters;
        $requestQueryArr["SecureHash"] = $secureHash;
        $requestQueryArr["CardNumber"] = "5271045423029111";
        $requestQueryArr["ExpiryDateYear"] = "20";
        $requestQueryArr["ExpiryDateMonth"] = "08";
        $requestQueryArr["SecurityCode"] = "123";
        $requestQueryArr["CardHolderName"] = "John";

        //For debugging
        if ($debug) {
            echo "<PRE>";
            echo "<BR>=======Parameters to post to https://srstaging.stspayone.com/SmartRoutePaymentWeb/SRMsgHandler===========<BR>";
            PRINT_R($requestQueryArr);
            echo "<BR><BR><BR>";
        }

        //Send curl to STS
        $request_string = http_build_query($requestQueryArr);
        //Send the request
        $ch = curl_init("https://srstaging.stspayone.com/SmartRoutePaymentWeb/SRMsgHandler");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);

        //Get the response
        $output = curl_exec($ch);
        curl_close($ch);
        $result = [];
        parse_str($output, $result);
        ksort($result);

        //For debugging
        if ($debug) {
            echo '<pre>';
            echo "<BR>==========Response=================<BR>";
            print_r($result);
            echo "<BR><BR><BR><BR>";
            //exit;
        }

        //Check STS Responce Success or Failure
        $payment_status = false;
        $payment_error_message = "STS Error";
        $status_code = $result["Response_StatusCode"];

        if ($status_code == "00000") { //sts success case

            //Re calculate secure hash
            $responseOrderdString = $SECRET_KEY;
            foreach ($result as $resParam) {
                $responseOrderdString .= $resParam;
            }
            // Generate SecureHash with SHA256
            $generatedSecureHash = hash('sha256', $responseOrderdString);
            // get the received secure hash from result map
            $receivedSecureHash = $result['Response_SecureHash'];
            if ($receivedSecureHash !== $generatedSecureHash) {
                echo 'Received Secure Hash does not Equal generated Secure hash';
                exit;
            }
            $payment_status = true;
        } else {
            //$status_code
            $payment_error_message = $result["Response_StatusDescription"];
        }


        //Payment status is success
        $response_arr = array();
        if (!$debug) {
            $payment_status = true;
        }
        if ($payment_status == true) {
            //update the database and send emails sms, redeem update and sync service call
            $search_data_session = session()->get('search_data');
            $payment_form_data = session()->get('payment_form_data');

            $booking_id = Session::get('booking_id');
            $from_branch_id = $search_data_session['from_branch_id'];
            $transaction_id = "66666"; //RNN // check data type it can be 60 digits long so have it as varchar in database
            $user_mobile_no = $payment_form_data['mobile_no'];
            $first_4_digits = ""; //explode 4555*******8474
            $last_4_digits = "";
            $card_brand = "";

            $this->sts_success($booking_id, $from_branch_id, $transaction_id, $user_mobile_no, $first_4_digits, $last_4_digits, $card_brand);
            //set success response for ajax
            $response_arr['status'] = true;
            $response_arr['redirect_url'] = $this->lang_base_url . '/booked';
        } else {
            //set failure response for ajax
            $response_arr['status'] = false;
            $response_arr['error_message'] = $payment_error_message; //show error from sts
            $response_arr['redirect_url'] = "#";

        }

        if (!$debug) {
            echo json_encode($response_arr);
            exit();
        }
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

    public function returnFromSadad()
    {
        echo '<pre>';
        print_r($_POST);
        exit();
    }

    public function terms_and_conditions() // this is for mobile use
    {
        try {
            $cdw_plus_terms = isset($_REQUEST['cdw_plus_terms']);
            $lang = (isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'en');
            $site_settings = custom::site_settings();
            if ($cdw_plus_terms) {
                $data['terms'] = ($lang == 'en' ? $site_settings->eng_terms_for_cdw_plus : $site_settings->arb_terms_for_cdw_plus);
            } else {
                $data['terms'] = ($lang == 'en' ? $site_settings->eng_terms : $site_settings->arb_terms);
            }
            $data['social'] = custom::social_links();
            $data['lang'] = $lang;
            $data['show_terms'] = true;
            return view('frontend.emails.terms_and_conditions')->with($data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getLocationAndCheck()
    {
        try {
            $adminPage = new App\Models\Admin\Page();
            $lat_long = trim($_REQUEST['lat_long']);
            $branch_id = $_REQUEST['branch_id'];
            $coordinates = $adminPage->getDeliveryCoordinatesForBranch($branch_id);
            $location_details = custom::getLocationDetails($lat_long);
            if ($coordinates) {
                foreach ($coordinates as $coordinate) {
                    $coord_arra[] = $coordinate->coordinates;
                }
                $coord_str = implode('|', $coord_arra);
                $status = true;
                $loc_details = $location_details;
            } else {
                $coord_str = '';
                $status = false;
                $loc_details = '';
            }
            $responseArray['return_status'] = $status;
            $responseArray['branch_coordinates'] = $coord_str;
            $responseArray['selected_lat_long'] = $lat_long;
            $responseArray['location_details'] = $loc_details;
            echo json_encode($responseArray);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    // send email to the branch agent to confirm booking
    private function send_email_to_branch_agent($booking_id, $toEmail, $toFName = '', $toLName = '')
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
            $emailData['booking_content']['first_name'] = $toFName;
            $emailData['booking_content']['last_name'] = $toLName;
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
            $emailData['booking_content']['first_name'] = $toFName;
            $emailData['booking_content']['last_name'] = $toLName;
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
        $fileName = $this->bookingPdf($emailData, $emailObj->reservation_code);
        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';
        $email['attachment'] = $attachment;
        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    // sending sms to branch agent
    private function send_sms_to_branch_agent($reservation_code, $userPhoneNo, $toName = '')
    {
        $lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Dear " . $toName . "\n" . "a booking is made at key.sa, Reservation# ";
        } else {
            $smsMsg = "Dear " . $toName . "\n" . "a booking is made at key.sa, Reservation# ";
        }
        //reservation thankyou sms
        $smsMsg .= $reservation_code . "\n";
        $smsMsg .= "Click link to see reservation details.\n";
        $smsMsg .= $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id);
        $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);
        custom::sendSMS($smsPhone, $smsMsg, $lang);
        //=================
    }
    /*public function survey($booking_id)
    {
        // TODO: need to keep this function
        $surveyData = array();
        $i = 0;
        //$booking_id = base64_decode($booking_id);
        $booking_detail = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
        $survey_emojis = $this->page->getAll('survey_emoji', 'sort_col');
        foreach ($survey_emojis as $emoji)
        {
            $surveyData[$i]['emoji_id'] = $emoji->id;
            $surveyData[$i]['emoji_eng_title'] = $emoji->eng_title;
            $surveyData[$i]['emoji_arb_title'] = $emoji->arb_title;
            $surveyData[$i]['class_name'] = $emoji->class_name;
            $categories = $this->page->getMultipleRows('survey_category', array('emoji_id' => $emoji->id), 'sort_col');
            $j = 0;
            foreach ($categories as $category)
            {
                $surveyData[$i]['categories'][$j] = (array)$category;
                $options = $this->page->getMultipleRows('survey_category_options', array('category_id' => $category->id), 'sort_col');
                foreach ($options as $option)
                {
                    $surveyData[$i]['categories'][$j]['options'][] = (array)$option;
                }
                $j++;
            }
            $i++;
        }
        $data['emojis'] = $this->page->getAll('survey_emoji', 'sort_col');
        //echo '<pre>';print_r($surveyData);exit();
        $data['survey_data'] = $surveyData;
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['booking_detail'] = $booking_detail;
        $data['active_menu'] = 'home';
        return view('frontend/survey', $data);
    }*/
    // survey to be filled is being set in bookings controller in import function and cronjob controller
    public function checkIfSurveyPendingToFill(Request $request) // only being used in frontend
    {
        // removing old session id
        try {
            Session::forget('booking_id_for_survey');
            Session::forget('last_segment_for_survey_redirect');
            Session::save();
            $customer_email = $request->input('email');
            $customer_id_no = $request->input('id_no');
            $last_segment = $request->input('last_segment');
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
                if ($survey_to_fill) {
                    $booking_id_for_survey = $survey_to_fill->booking_id;
                    Session::put('booking_id_for_survey', $booking_id_for_survey); // setting booking id here to use in survey function below and page and page
                    Session::put('last_segment_for_survey_redirect', $last_segment);
                    Session::save();
                    $responseArray['status'] = true; // using this in ajax response in functions.js
                    $responseArray['customerId'] = $individual_customer_id;
                    echo json_encode($responseArray);
                    exit();
                } else {
                    $responseArray['status'] = false;
                    $responseArray['bookingId'] = '';
                    $responseArray['customerId'] = '';
                    echo json_encode($responseArray);
                    exit();
                }
            } else {
                $responseArray['status'] = false;
                $responseArray['bookingId'] = '';
                $responseArray['customerId'] = '';
                echo json_encode($responseArray);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function survey() // being used for frontend and in mobile and sms too
    {
        // TODO: need to keep this function
        try {
            if (isset($_GET['ref']) && $_GET['ref'] != '') // put this here to be used in emails and sms
            {
                //$booking_id = base64_decode('NDExMw==');
                $booking_id = base64_decode($_GET['ref']);
            } else {
                $booking_id = Session::get('booking_id_for_survey'); // to be used for live
            }
            $booking_detail = $this->page->getSingleBookingDetails($booking_id, 'individual_user');
            if (empty($booking_detail)) {
                //echo "Sorry. We didn't find any booking against this query.";
                exit();
            }
            if ($booking_detail->type == 'individual_customer') {
                $individual_booking_details = $this->page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
                $individual_customer_details = $this->page->getSingle('individual_customer', array('uid' => $individual_booking_details->uid));
                $customer_id = $individual_customer_details->id;
            } else {
                $guest_booking_details = $this->page->getSingle('booking_individual_guest', array('booking_id' => $booking_id));
                $customer_id = $guest_booking_details->individual_customer_id;
            }
            Session::put('unfilled_survey_customer_id', $customer_id);
            Session::save();
            $data['emojis'] = $this->page->getAll('survey_emoji', 'sort_col');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['booking_detail'] = $booking_detail;
            $data['booking_id_for_survey'] = $booking_id;
            $data['customer_id_for_survey'] = $customer_id;
            $data['active_menu'] = 'home';
            return view('frontend.survey', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getCategoriesForEmoji(Request $request)
    {
        try {
            $html = '';
            $emoji_id = $request->input('emoji_id');
            $categories_count = $this->page->getRowsCount('survey_category', array('emoji_id' => $emoji_id, 'publish' => 'yes'), 'sort_col');
            if ($categories_count > 0) {
                $categories = $this->page->getMultipleRows('survey_category', array('emoji_id' => $emoji_id, 'publish' => 'yes'), 'sort_col');
                foreach ($categories as $category) {
                    $html .= '<a href="javascript:void(0);" class="getOptions" data-category-id="' . $category->id . '" data-category-title="' . ($this->lang == 'eng' ? $category->eng_title : $category->arb_title) . '" data-question-title="' . ($this->lang == 'eng' ? $category->eng_question : $category->arb_question) . '"><button class="edBtn yellowish">' . ($this->lang == 'eng' ? $category->eng_title : $category->arb_title) . '</button></a>';
                }
            } else {
                $html .= '<a href="javascript:void(0);" onclick="post_feedback();"><button class="edBtn yellowish">' . \Lang::get('labels.submit_btn') . '</button></a>';
            }
            $responseArr['html'] = $html;
            echo json_encode($responseArr);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getOptionsForCategory(Request $request)
    {
        try {
            $html = '';
            $i = 0;
            $category_id = $request->input('category_id');
            $category_detail = $this->page->getSingle('survey_category', array('id' => $category_id));
            $options_count = $this->page->getRowsCount('survey_category_options', array('category_id' => $category_id, 'publish' => 'yes'), 'sort_col');
            if ($options_count > 0) {
                $options = $this->page->getMultipleRows('survey_category_options', array('category_id' => $category_id, 'publish' => 'yes'), 'sort_col');
                $html .= '<ul class="reviewList">';
                foreach ($options as $option) {
                    $html .= '<li class="">
                            <input id="list_opt-' . $i . '" class="feedback_option" name="feedback_option" data-option-id="' . $option->id . '" value="' . ($this->lang == 'eng' ? $option->eng_title : $option->arb_title) . '" type="radio">
                            <label for="list_opt-' . $i . '">' . ($this->lang == 'eng' ? $option->eng_title : $option->arb_title) . '</label>
                          </li>';
                    $i++;
                }
            }
            if ($category_detail->is_other_type == 'yes') {
                $html .= '<li class="">
                            <input id="list_opt-' . $i . '" class="feedback_option" data-option-id="0" name="feedback_option" value="Other" type="radio">
                            <label for="list_opt-' . $i . '">' . ($this->lang == 'eng' ? 'Other' : 'آخر') . '</label>
                          </li>';
            }
            $html .= '</ul>';
            if ($category_detail->is_other_type == 'yes') {
                $html .= '<div class="textBox"><input placeholder="' . ($this->lang == 'eng' ? 'Enter Your Text' : 'أدخل النص الخاص بك') . '" class="feedback_textfield" name="feedback_textfield" type="text" disabled /></div>';
            }
            $html .= '<div class="emojiCat">
                    <a href="javascript:void(0);" onclick="post_feedback();">
                        <button class="edBtn yellowish">' . \Lang::get('labels.submit_btn') . '</button>
                    </a>
                </div>';
            $responseArr['html'] = $html;
            echo json_encode($responseArr);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveSurveyFeedback(Request $request) // customer_id, booking_id, emoji_desc, category_desc, question_desc, answer_desc, emoji_id, category_id, option_id
    {
        try {
            $posted_data = $request->input();
            $posted_data['created_at'] = date('Y-m-d H:i:s');
            if ($posted_data['option_id'] == '0') {
                $posted_data['answer_desc'] = $posted_data['feedback_textfield'];
            }
            unset($posted_data['feedback_textfield']);
            $emoji_categories_count = $this->page->getRowsCount('survey_category', array('emoji_id' => $posted_data['emoji_id'], 'publish' => 'yes'));
            $category_options_count = $this->page->getRowsCount('survey_category_options', array('category_id' => $posted_data['category_id'], 'publish' => 'yes'));
            if (($posted_data['category_id'] == '' && $emoji_categories_count > 0) || ($posted_data['option_id'] == '' && $category_options_count > 0)) {
                $responseArray['status'] = 0;
                $responseArray['message'] = ($this->lang == 'eng' ? 'Please fill the survey correctly.' : 'يرجى ملء الاستبيان بشكل صحيح. ');
                echo json_encode($responseArray);
                exit();
            }
            // checking if user has already submitted this survey
            $feedbackAlreadySubmitted = $this->page->getRowsCount('survey_feedback', array('customer_id' => $posted_data['customer_id'], 'booking_id' => $posted_data['booking_id']));
            if ($feedbackAlreadySubmitted > 0) {
                $responseArray['status'] = 2;
                $responseArray['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
                echo json_encode($responseArray);
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
            $saved_id = $this->page->saveData('survey_feedback', $posted_data);
            if ($saved_id > 0) {
                $updateBy['customer_id'] = $posted_data['customer_id'];
                $updateBy['booking_id'] = $posted_data['booking_id'];
                $update['survey_filled_status'] = 'yes';
                $update['updated_at'] = date('Y-m-d H:i:s');
                $this->page->updateData('survey_filled_status', $update, $updateBy);
                $responseArray['status'] = 1;
                $responseArray['redirect_url'] = Session::get('last_segment_for_survey_redirect');
                $responseArray['message'] = ($this->lang == 'eng' ? 'Thank You For Taking Your Time To Fill The Survey.' : 'شكرا ً لأخذ وقتك لملء هذا الاستبيان.');
                echo json_encode($responseArray);
                exit();
            } else {
                $responseArray['status'] = 0;
                $responseArray['redirect_url'] = '';
                $responseArray['message'] = ($this->lang == 'eng' ? 'Survey failed to submit. Please try again.' : 'لقد فشلت في تقديم الاستبيان. يرجى إعادة المحاولة مرة اخرى');
                echo json_encode($responseArray);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function skipSurvey(Request $request)
    {
        try {
            $update_by['customer_id'] = $request->input('customer_id');
            $update_by['survey_filled_status'] = 'no';
            $data_to_update['survey_filled_status'] = 'yes';
            $data_to_update['is_skipped'] = 'yes';
            $data_to_update['updated_at'] = date('Y-m-d H:i:s');
            $this->page->updateData('survey_filled_status', $data_to_update, $update_by);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function oasisSurvey()
    {
        try {
            $contract_no = base64_decode($_GET['ref']);
            $booking_status = $_GET['booking_status'];
            // checking if user has already submitted this survey
            $data['filled_status'] = 0;
            $data['lang'] = $this->lang;
            $feedbackAlreadySubmitted = $this->page->getRowsCount('oasis_survey_feedback', array('contract_no' => $contract_no, 'booking_status' => $booking_status));
            if ($feedbackAlreadySubmitted > 0) {
                $data['filled_status'] = 1;
                $data['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
            }
            $data['emojis'] = $this->page->getAll('survey_emoji', 'sort_col');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['active_menu'] = 'home';
            $data['contract_no'] = $contract_no;
            $data['booking_status'] = $booking_status;
            return view('frontend.oasis_survey', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function oasisSurveyNew()
    {
        try {
            $contract_no = base64_decode($_GET['ref']);
            $booking_status = $_GET['booking_status'];
            // checking if user has already submitted this survey
            $data['filled_status'] = 0;
            $data['lang'] = $this->lang;
            $feedbackAlreadySubmitted = $this->page->getRowsCount('oasis_survey_feedback', array('contract_no' => $contract_no, 'booking_status' => $booking_status));
            if ($feedbackAlreadySubmitted > 0) {
                $data['filled_status'] = 1;
                $data['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
            }
            $data['emojis'] = $this->page->getAll('survey_emoji', 'sort_col');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['active_menu'] = 'home';
            $data['contract_no'] = $contract_no;
            $data['booking_status'] = $booking_status;
            return view('frontend.oasis_survey_new', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function setOasisSurveyPendingToFill()
    {
        try {
            $lang = $_REQUEST['lang']; // AR, EN
            $data['contract_no'] = $_REQUEST['contract_no'];
            $data['name'] = $_REQUEST['name'];
            $data['mobile_no'] = $_REQUEST['mobile_no'];
            if (isset($_REQUEST['id_no'])) {
                $data['id_no'] = $_REQUEST['id_no'];
                $data['from_location'] = $_REQUEST['from_location'];
                $data['to_location'] = $_REQUEST['to_location'];
                $data['car_type'] = $_REQUEST['car_type'];
                $data['car_model'] = $_REQUEST['car_model'];
                $data['from_date'] = $_REQUEST['from_date'];
                $data['to_date'] = $_REQUEST['to_date'];
                $data['total_payment'] = $_REQUEST['total_payment'];
                //05-Nov-2018
                //We need to add new column in oasis survey called (status) and it will be (C from close) (O from open)
                $data['booking_status'] = $_REQUEST['booking_status'];
            }
            $data['survey_filled_status'] = 'no';
            $data['is_skipped'] = 'no';
            $data['created_at'] = date('Y-m-d H:i:s');
            $alreadyExist = $this->page->getSingle('oasis_survey_filled_status', array('contract_no' => $data['contract_no'], 'booking_status' => $data['booking_status']));
            if ($alreadyExist) {
                $status = 0;
            } else {
                $saved = $this->page->saveData('oasis_survey_filled_status', $data);
                if ($saved > 0) {
                    $status = 1;
                    $this->sendSmsToCustomer($data['name'], $data['mobile_no'], $data['contract_no'], $lang, $data['booking_status']);
                } else {
                    $status = 0;
                }
            }
            return $status;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function setOasisSurveyPendingToFillLink()
    {
        try {
            $lang = $_REQUEST['lang']; // AR, EN
            $data['contract_no'] = $_REQUEST['contract_no'];
            $data['name'] = $_REQUEST['name'];
            $data['mobile_no'] = $_REQUEST['mobile_no'];
            if (isset($_REQUEST['id_no'])) {
                $data['id_no'] = $_REQUEST['id_no'];
                $data['from_location'] = $_REQUEST['from_location'];
                $data['to_location'] = $_REQUEST['to_location'];
                $data['car_type'] = $_REQUEST['car_type'];
                $data['car_model'] = $_REQUEST['car_model'];
                $data['from_date'] = $_REQUEST['from_date'];
                $data['to_date'] = $_REQUEST['to_date'];
                $data['total_payment'] = $_REQUEST['total_payment'];
                //05-Nov-2018
                //We need to add new column in oasis survey called (status) and it will be (C from close) (O from open)
                $data['booking_status'] = $_REQUEST['booking_status'];

                // new keys
                $data['opened_region'] = $_REQUEST['opened_region'];
                $data['opened_city'] = $_REQUEST['opened_city'];
                $data['close_region'] = $_REQUEST['close_region'];
                $data['close_city'] = $_REQUEST['close_city'];
                $data['booking_id'] = $_REQUEST['booking_id']; // NULL in case of walk-in
                $data['booking_source'] = $_REQUEST['booking_source'];
                $data['is_delivery'] = $_REQUEST['is_delivery'];
                $data['is_subscription'] = $_REQUEST['is_subscription'];
                $data['staff_id'] = $_REQUEST['staff_id'];
                $data['staff_name'] = $_REQUEST['staff_name'];
                $data['contract_opened_date_time'] = $_REQUEST['contract_opened_date_time'];
                $data['contract_closed_date_time'] = $_REQUEST['contract_closed_date_time'];
            }
            $data['survey_filled_status'] = 'no';
            $data['is_skipped'] = 'no';
            $data['created_at'] = date('Y-m-d H:i:s');
            $alreadyExist = $this->page->getSingle('oasis_survey_filled_status', array('contract_no' => $data['contract_no'], 'booking_status' => $data['booking_status']));
            if (!$alreadyExist) {
                $saved = $this->page->saveData('oasis_survey_filled_status', $data);
                if ($saved > 0) {
                    if (strtolower($lang) == 'en') {
                        $surveyLink = custom::baseurl('/') . '/en/oasis-survey?ref=' . base64_encode($data['contract_no']) . '&booking_status=' . $data['booking_status'];
                    } else {
                        $surveyLink = custom::baseurl('/') . '/oasis-survey?ref=' . base64_encode($data['contract_no']) . '&booking_status=' . $data['booking_status'];
                    }
                    return $surveyLink;
                }
            }
            return 0;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveOasisSurveyFeedback(Request $request) // customer_id, booking_id, emoji_desc, category_desc, question_desc, answer_desc, emoji_id, category_id, option_id
    {
        try {
            $posted_data = $request->input();

            // checking if user has already submitted this survey
            $feedbackAlreadySubmitted = $this->page->getRowsCount('oasis_survey_feedback', array('contract_no' => $posted_data['contract_no'], 'booking_status' => $posted_data['booking_status']));
            if ($feedbackAlreadySubmitted > 0) {
                $responseArray['status'] = 2;
                $responseArray['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
                echo json_encode($responseArray);
                exit();
            } else {

                $questions_count = $posted_data['questions_count'];
                unset($posted_data['questions_count']);

                for ($i = 1; $i <= $questions_count; $i++) {
                    $question_val = $posted_data['question-' . $i];
                    $question_val_exploded = explode('-', $question_val);
                    $posted_data[$question_val_exploded[0]] = $question_val_exploded[1];
                    unset($posted_data['question-' . $i]);
                }

                if (isset($posted_data['comment']) && $posted_data['comment']) {
                    $posted_data['comment'] = custom::removeEmojis($posted_data['comment']);
                }

                $posted_data['created_at'] = date('Y-m-d H:i:s');

                $saved_id = $this->page->saveData('oasis_survey_feedback', $posted_data);
                if ($saved_id > 0) {
                    $updateBy['contract_no'] = $posted_data['contract_no'];
                    $updateBy['booking_status'] = $posted_data['booking_status'];
                    $update['survey_filled_status'] = 'yes';
                    $update['updated_at'] = date('Y-m-d H:i:s');
                    $check1 = $this->page->updateData('oasis_survey_filled_status', $update, $updateBy);
                    $responseArray['status'] = 1;
                    $responseArray['message'] = ($this->lang == 'eng' ? 'Thank You For Taking Your Time To Fill The Survey.' : 'شكرا ً لأخذ وقتك لملء هذا الاستبيان.');
                    echo json_encode($responseArray);
                    exit();
                } else {
                    $responseArray['status'] = 0;
                    $responseArray['message'] = ($this->lang == 'eng' ? 'Survey failed to submit. Please try again.' : 'لقد فشلت في تقديم الاستبيان. يرجى إعادة المحاولة مرة اخرى');
                    echo json_encode($responseArray);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendSmsToCustomer($customer_name, $customer_mobile_no, $contract_no, $lang = 'EN', $booking_status)
    {
        $contract_no = base64_encode($contract_no);
        $smsPhone = str_replace(array('+', ' '), '', $customer_mobile_no);
        if ($lang == 'EN') {
            $userSms = "Dear $customer_name, \n Thanks for choosing " . custom::getSiteName($this->lang) . ".";
            $userSms .= "Share your experience with our services by using the below link: \n";
            $userSms .= custom::baseurl('/') . '/en/oasis-survey?ref=' . $contract_no . '&booking_status=' . $booking_status;
        } elseif ($lang == 'AR') {
            $userSms = "عميلنا $customer_name,\n";
            $userSms .= "شكرا لإختيارك شركة " . custom::getSiteName($this->lang) . ".شاركنا رأيك وقيم خدماتنا بإستخدام الرابط التالي,  ";
            $userSms .= "\n";
            $userSms .= custom::baseurl('/') . '/oasis-survey?ref=' . $contract_no . '&booking_status=' . $booking_status;
        }
        custom::sendSMS($smsPhone, $userSms);
    }

    public function book_now_for_corporate(Request $request)
    {
        try {
            $request_data_params = $request->all();
            $car_payment_data_to_check = Session::get('car_payment_data_to_check');

            $request_data_params['total_rent_after_discount_on_promo'] = round($request_data_params['total_rent_after_discount_on_promo'], 2);
            $car_payment_data_to_check['total_rent_after_discount_on_promo'] = round($car_payment_data_to_check['total_rent_after_discount_on_promo'], 2);

            $request_data_params['discount_amount_per_day'] = $request_data_params['discount_amount_per_day'] > 0 ? round($request_data_params['discount_amount_per_day'], 2) : 0;
            $car_payment_data_to_check['discount_amount_per_day'] = $car_payment_data_to_check['discount_amount_per_day'] > 0 ? round($car_payment_data_to_check['discount_amount_per_day'], 2) : 0;

            $site_settings = custom::site_settings();
            if (
                ($site_settings->forcefully_recheck_prices == 0) ||
                ($site_settings->forcefully_recheck_prices == 1 &&
                $request_data_params['total_rent_after_discount_on_promo'] == $car_payment_data_to_check['total_rent_after_discount_on_promo'] &&
                $request_data_params['discount_amount_per_day'] == $car_payment_data_to_check['discount_amount_per_day'])
            ) {
                Session::forget('car_payment_data_to_check');
                Session::save();

                ini_set('max_execution_time', 600);
                $site_settings = custom::site_settings();
                $created_at = date('Y-m-d H:i:s');
                $sessionVals = Session::get('search_data');
                $rent_per_day = Session::get('rent_per_day');
                $days = $sessionVals['days'];

                if ($sessionVals['is_delivery_mode'] == 4) {
                    $sessionVals['days'] = 30; // because 1 month is to be charged
                    $days = $sessionVals['days'];
                }

                $cdw = Session::get('cdw_charges');
                $cdw_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

                $cdw_plus = Session::get('cdw_plus_charges');
                $cdw_plus_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

                $gps = Session::get('gps_charges');
                $gps_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

                $extra_driver = Session::get('extra_driver_charges');
                $extra_driver_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

                $baby_seat = Session::get('baby_seat_charges');
                $baby_seat_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

                if ($cdw_is_one_time_applicable_on_booking == 1) {
                    $cdw_multiply_factor = 1;
                } else {
                    $cdw_multiply_factor = $days;
                }

                if ($cdw_plus_is_one_time_applicable_on_booking == 1) {
                    $cdw_plus_multiply_factor = 1;
                } else {
                    $cdw_plus_multiply_factor = $days;
                }

                if ($gps_is_one_time_applicable_on_booking == 1) {
                    $gps_multiply_factor = 1;
                } else {
                    $gps_multiply_factor = $days;
                }

                if ($extra_driver_is_one_time_applicable_on_booking == 1) {
                    $extra_driver_multiply_factor = 1;
                } else {
                    $extra_driver_multiply_factor = $days;
                }

                if ($baby_seat_is_one_time_applicable_on_booking == 1) {
                    $baby_seat_multiply_factor = 1;
                } else {
                    $baby_seat_multiply_factor = $days;
                }

                $total_rent_for_all_days = Session::get('total_rent_for_all_days'); // Only rent multiplied by days
                $parking_fee = $sessionVals['parking_fee'];
                $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];
                $limousine_extra_charges = custom::get_limousine_extra_charges();
                $waiting_extra_hours = $limousine_extra_charges['waiting_extra_hours'];
                $waiting_extra_hours_charges = $limousine_extra_charges['waiting_extra_hours_charges'];
                if ($sessionVals['is_delivery_mode'] == 1) {
                    $pickup_delivery_coordinate = $sessionVals['pickup_delivery_coordinate'];
                    $dropoff_delivery_coordinate = $sessionVals['dropoff_delivery_coordinate'];
                    $is_delivery_mode = 'yes';
                } elseif ($sessionVals['is_subscription_with_delivery_flow'] == 1) {
                    $pickup_delivery_coordinate = $sessionVals['pickup_delivery_coordinate'];
                    $dropoff_delivery_coordinate = $sessionVals['dropoff_delivery_coordinate'];
                    $is_delivery_mode = 'yes';
                } elseif (isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1) {
                    $pickup_delivery_coordinate = $sessionVals['pickup_delivery_coordinate'];
                    $dropoff_delivery_coordinate = $sessionVals['dropoff_delivery_coordinate'];
                    $is_delivery_mode = 'no';
                } else {
                    $pickup_delivery_coordinate = '';
                    $dropoff_delivery_coordinate = '';
                    $is_delivery_mode = 'no';
                }
                if (($sessionVals['is_delivery_mode'] == 1 && Session::get('search_data')['delivery_charges'] > 0) || ($sessionVals['is_subscription_with_delivery_flow'] == 1 && Session::get('search_data')['delivery_charges'] > 0)) {
                    $delivery_charges = Session::get('search_data')['delivery_charges'];
                } else {
                    $delivery_charges = 0.00;
                }
// save extra information of driver if corporate customer
                $corporate_driver['first_name'] = htmlspecialchars($request->input('first_name'));
                $corporate_driver['last_name'] = htmlspecialchars($request->input('last_name'));
                $corporate_driver['email'] = $request->input('email');
                $corporate_driver['mobile_no'] = custom::convertArabicNumbersToEnglish($request->input('mobile_no'));
                $corporate_driver['id_type'] = $request->input('id_type');
                //$convert_id_no = custom::convertArabicNumbersToEnglish($request->input('id_no'));
                $corporate_driver['id_no'] = custom::convertArabicNumbersToEnglish($request->input('id_no'));
                $corporate_driver['gender'] = $request->input('gender');
                $corporate_driver['sponsor'] = htmlspecialchars($request->input('sponsor'));
                $corporate_driver['license_no'] = custom::convertArabicNumbersToEnglish($request->input('license_no'));
                $session_data = $corporate_driver;
                $session_data['agent_emp_number'] = custom::convertArabicNumbersToEnglish($request->input('agent_emp_number'));
                $session_data['id_version'] = $id_version = ($request->input('id_type') == '68' || $request->input('id_type') == '243' ? '1' : '');

                $session_data['address_street'] = $corporate_user_data['address_street'] = htmlspecialchars($request->input('address_street'));
                $session_data['address_city'] = $corporate_user_data['address_city'] = htmlspecialchars($request->input('address_city'));
                $session_data['address_state'] = $corporate_user_data['address_state'] = htmlspecialchars($request->input('address_state'));
                $session_data['address_country'] = $corporate_user_data['address_country'] = $request->input('address_country');
                $session_data['address_post_code'] = $corporate_user_data['address_post_code'] = htmlspecialchars(custom::convertArabicNumbersToEnglish($request->input('address_post_code')));

                $this->page->updateData('corporate_customer', $corporate_user_data, array('id' => Session::get('corporate_customer_id')));

                $session_data['promo_code'] = $promo_code = $request->input('promo_code');
                $session_data['payment_method'] = $payment_method = $request->input('payment_method');
                $session_data['isMada'] = $isMada = $request->input('isMada');
                $session_data['car_id'] = $car_id = Session::get('car_id');
                $session_data['rent_per_day'] = $rent_per_day;
                $session_data['total_rent_for_all_days'] = $total_rent_for_all_days; // Only rent multiplied by days
                $session_data['total_rent_after_discount_on_promo'] = $request->input('total_rent_after_discount_on_promo');
                $session_data['discount_amount_per_day'] = $request->input('discount_amount_per_day');
                $session_data['promotion_id'] = $request->input('promotion_id');
                $session_data['renting_type_id'] = Session::get('renting_type_id');
                Session::put('payment_form_data', $session_data);
                Session::save();
                // Getting branch data to get its prefix
                $get_branch_data_by['id'] = $sessionVals['from_branch_id'];
                $branch_info = $this->page->getSingle('branch', $get_branch_data_by);
                // Saving booking information
                $booking_info['car_model_id'] = $car_id;
                $booking_info['from_location'] = $sessionVals['from_branch_id'];
                $booking_info['to_location'] = $sessionVals['to_branch_id'];
                $booking_info['from_date'] = date('Y-m-d', strtotime($sessionVals['pickup_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['pickup_time']));
                $booking_info['to_date'] = date('Y-m-d', strtotime($sessionVals['dropoff_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['dropoff_time']));
                $booking_info['booking_status'] = 'Not Picked';
                $booking_info['sync'] = 'N';
                $booking_info['renting_type_id'] = Session::get('renting_type_id');
                $user_type = 'corporate_customer';
                $booking_info['type'] = $user_type;
                $booking_info['pickup_delivery_lat_long'] = $pickup_delivery_coordinate;
                $booking_info['dropoff_delivery_lat_long'] = $dropoff_delivery_coordinate;
                $booking_info['is_delivery_mode'] = $is_delivery_mode;
                $booking_info['booking_source'] = (custom::is_mobile() ? 'mobile' : 'website');
                $booking_info['lang'] = $this->lang;
                $booking_info['subscription_with_delivery_flow'] = ($sessionVals['is_subscription_with_delivery_flow'] == 1 ? 'on' : 'off');
                $booking_info['browser_os'] = custom::get_browser_os();
                $booking_info['created_at'] = $created_at;

                $booking_info['is_limousine'] = (isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1 ? 'Yes' : 'No');
                $booking_info['is_round_trip'] = (isset($sessionVals['isRoundTripForLimousine']) && $sessionVals['isRoundTripForLimousine'] == 1 ? 'Yes' : 'No');
                $booking_info['flight_no'] = $request->input('flight_number');
                $booking_info['limousine_cost_center'] = $request->input('limousine_cost_center');
                $booking_info['waiting_extra_hours'] = $waiting_extra_hours;
                $booking_info['waiting_extra_hours_charges'] = $waiting_extra_hours_charges;

                $savedBookingId = $this->page->saveData('booking', $booking_info);
                // Generating reservation code and updating in database
                $booking_info_extra['reservation_code'] = custom::generateReservationCode($branch_info->prefix, $savedBookingId, 'W');
                $updateBookingInfoBy['id'] = $savedBookingId;
                $bookingUpdated = $this->page->updateData('booking', $booking_info_extra, $updateBookingInfoBy);
                Session::put('booking_gen_reference_code', $booking_info_extra['reservation_code']);
                Session::put('booking_id', $savedBookingId);
                Session::save();
                // Saving booking payment method
                if ($payment_method == 'cc') {
                    $method_is = 'Credit Card';
                } elseif ($payment_method == 'corporate_credit') {
                    $method_is = 'Corporate Credit';
                } elseif ($payment_method == 'pay_later') {
                    $method_is = 'Pay Later';
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
                $booking_corporate_user_data['uid'] = Session::get('user_id');
                /*
                 * This is loyalty_id which customer enter in loyalty popup in main search
                 * It is saving in booking_corporate_customer table
                 * We are showing this in excel export when paylater booking is done with call center on
                 * customer_id_no_for_loyalty
                */
                if (custom::isCorporateLoyalty() && session::get('customer_id_no_for_loyalty') != '') {
                    //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
                    $booking_corporate_user_data['customer_id_no_for_loyalty'] = session()->get('customer_id_no_for_loyalty');
                }
                $booking_corporate_user_data['agent_emp_number'] = session()->get('payment_form_data')['agent_emp_number'];
                $this->page->saveData('booking_corporate_customer', $booking_corporate_user_data);

                // Saving booking payment details
                $booking_payment_data['booking_id'] = $savedBookingId;
                if ($rent_per_day != Session::get('old_price')) {
                    $booking_payment_data['rent_price'] = $rent_per_day;
                    $booking_payment_data['original_rent'] = Session::get('old_price');
                } else {
                    $payment_form_data = session()->get('payment_form_data');
                    $rent_per_day = $rent_per_day - $payment_form_data['discount_amount_per_day'];
                    $booking_payment_data['rent_price'] = $rent_per_day;
                    $booking_payment_data['original_rent'] = Session::get('old_price');
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

                $booking_payment_data['promotion_offer_id'] = $request->input('promotion_id');
                $booking_payment_data['promotion_offer_code_used'] = Session::has('coupon_code') ? Session::get('coupon_code') : "";
                $booking_payment_data['discount_price'] = $discount_price = $request->input('discount_amount_per_day');
                $booking_payment_data['total_rent_after_discount'] = $request->input('total_rent_after_discount_on_promo');
                $booking_payment_data['dropoff_charges'] = $dropoff_charges = Session::get('dropoff_charges_amount');
                $booking_payment_data['delivery_charges'] = $delivery_charges;
                $booking_payment_data['parking_fee'] = $parking_fee;
                $booking_payment_data['tamm_charges_for_branch'] = $tamm_charges_for_branch;
                $booking_payment_data['no_of_days'] = $days;
                $booking_payment_data['loyalty_type_used'] = Session::get('loyalty_type_used');

                $booking_payment_data['qitaf_request'] = (Session::has('qitaf_request') ? str_replace('.', ',', Session::get('qitaf_request')) : '');
                $booking_payment_data['qitaf_amount'] = $qitaf_amount = (Session::has('qitaf_amount') ? Session::get('qitaf_amount') : 0);

                $booking_payment_data['niqaty_request'] = (Session::has('niqaty_request') ? Session::get('niqaty_request') : '');
                $booking_payment_data['niqaty_amount'] = $niqaty_amount = (Session::has('niqaty_amount') ? Session::get('niqaty_amount') : 0);

                $booking_payment_data['mokafaa_request'] = (Session::has('mokafaa_request') ? Session::get('mokafaa_request') : '');
                $booking_payment_data['mokafaa_amount'] = $mokafaa_amount = (Session::has('mokafaa_amount') ? Session::get('mokafaa_amount') : 0);

                $booking_payment_data['anb_request'] = (Session::has('anb_request') ? Session::get('anb_request') : '');
                $booking_payment_data['anb_amount'] = $anb_amount = (Session::has('anb_amount') ? Session::get('anb_amount') : 0);

                $loyalty_program_id = (isset($_REQUEST['loyalty_program_id']) && $_REQUEST['loyalty_program_id'] > 0 ? $_REQUEST['loyalty_program_id'] : '');
                $booking_payment_data['loyalty_program_for_oracle'] = custom::get_loyalty_program_used_for_booking($loyalty_program_id);
                $booking_payment_data['is_promo_discount_on_total'] = $is_promo_discount_on_total = (Session::has('is_promo_discount_on_total') ? 1 : 0);

                $pre_total_discount = 0;
                $post_total_discount = 0;

                if ($is_promo_discount_on_total == 1) {
                    $post_total_discount = $discount_price;
                } else {
                    $pre_total_discount = $discount_price;
                }

                if (Session::get('minus_discount') == true) { // in case of promo code discount
                    $total_sum_without_vat = (($rent_per_day * $days) - ($pre_total_discount * $days)) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges;
                } else {
                    $total_sum_without_vat = ($rent_per_day * $days) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges;
                }
                $vat_to_add = (Session::get('vat_percentage') / 100) * $total_sum_without_vat;
                Session::put('vat', $vat_to_add);
                Session::save();
                $booking_payment_data['total_sum'] = $total_sum_without_vat + $vat_to_add - $qitaf_amount - $niqaty_amount - $mokafaa_amount - $anb_amount - $post_total_discount;
                $booking_payment_data['vat_percentage'] = Session::get('vat_percentage');
                $booking_payment_data['vat_applied'] = $vat_to_add;

                $booking_payment_data['car_rate_is_with_additional_utilization_rate'] = (Session::has('car_rate_is_with_additional_utilization_rate') ? Session::get('car_rate_is_with_additional_utilization_rate') : 0);

                if ($booking_payment_data['car_rate_is_with_additional_utilization_rate'] > 0) {
                    $car_utilization_setup = $this->page->getSingle('setting_car_utilization_setup', ['id' => $booking_payment_data['car_rate_is_with_additional_utilization_rate']]);
                    if ($car_utilization_setup) {
                        $booking_payment_data['utilization_percentage'] = $car_utilization_setup->utilization_percentage;
                        $booking_payment_data['utilization_percentage_rate'] = $car_utilization_setup->addition_or_subtraction_percentage;
                        $booking_payment_data['utilization_record_time'] = $car_utilization_setup->last_amend_date;
                    }
                }

                $booking_payment_data['is_free_cdw_promo_applied'] = (Session::has('is_free_cdw_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_cdw_plus_promo_applied'] = (Session::has('is_free_cdw_plus_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_baby_seat_promo_applied'] = (Session::has('is_free_baby_seat_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_driver_promo_applied'] = (Session::has('is_free_driver_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_open_km_promo_applied'] = (Session::has('is_free_open_km_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_delivery_promo_applied'] = (Session::has('is_free_delivery_promo_applied') ? 1 : 0);
                $booking_payment_data['is_free_dropoff_promo_applied'] = (Session::has('is_free_dropoff_promo_applied') ? 1 : 0);

                // workaround fix for discount field getting 0 for loyalty discount case, in offer apply case this is working already fine so only did it for no offer
                if ($booking_payment_data['promotion_offer_id'] == 0) {
                    $booking_payment_data['discount_price'] = $booking_payment_data['original_rent'] - $booking_payment_data['rent_price'];
                }

                $booking_payment_data['cpid'] = Session::get('cpid'); // if its null it means price is from quotation otherwise its from car prices

                $this->page->saveData('booking_payment', $booking_payment_data);
                Session::put('total_amount_for_transaction', $booking_payment_data['total_sum']);
                Session::save();

                $car_info = $this->page->getSingleCarDetail($car_id);
                $items = [];
                $item_data = [
                    'item_id' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
                    'item_name' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
                    'item_brand' => $method_is
                ];
                $items[] = $item_data;
                $event = 'begin_checkout';
                $event_data = [
                    'currency' => 'SAR',
                    'value' => Session::get('total_amount_for_transaction'),
                    'items' => $items
                ];
                custom::sendEventToGA4($event, $event_data);
                
                // If credit card method is used than redirecting to payment page

                $corporate_customer_id = Session::get('corporate_customer_id');
                $get_by['id'] = $corporate_customer_id;
                $corporate_customer_data = $this->page->getSingle('corporate_customer', $get_by);

                if ($payment_method == 'cc') {
                    $booking_cc['booking_id'] = Session::get('booking_id');
                    $booking_cc['status'] = 'pending';

                    if ($corporate_customer_data->cc_company == 'sts') {
                        $booking_cc['payment_company'] = 'sts';
                    } elseif ($corporate_customer_data->cc_company == 'hyper_pay') {
                        $booking_cc['payment_company'] = 'hyper_pay';
                    }

                    $this->page->saveData('booking_cc_payment', $booking_cc);
                    if ($this->lang == "arb") {
                        $bPayPath = "cc-payment";
                    } else {
                        $bPayPath = "en/cc-payment";
                    }
                    return redirect($bPayPath);
                } elseif ($payment_method == 'pay_later') {

                    $corporate_customer_id = Session::get('corporate_customer_id');
                    $get_by['id'] = $corporate_customer_id;
                    $corporate_customer_data = $this->page->getSingle('corporate_customer', $get_by);
                    $corporate_driver_data = $this->page->getSingle('corporate_driver', array('id' => $corporate_driver_id));
                    $get_expiry_period = $corporate_customer_data->expiry_period;
                    $payLater_expiry_period = date('Y-m-d H:i:s', strtotime("+" . $get_expiry_period . " hours"));
                    $booking_corporate_invoice_data['booking_id'] = $savedBookingId;
                    $booking_corporate_invoice_data['cc_company'] = $corporate_customer_data->cc_company;
                    $booking_corporate_invoice_data['expiry'] = $payLater_expiry_period;
                    $booking_corporate_invoice_data['payment_status'] = "pending";
                    $booking_corporate_invoice_data['attempts'] = "0";
                    $booking_corporate_invoice_data['created_at'] = date('Y-m-d H:i:s');
                    $this->page->saveData('booking_corporate_invoice', $booking_corporate_invoice_data);

                    if ($corporate_customer_data->cc_company == 'hyper_pay') {
                        // code to generate and send pay later invoice with hyper bill api
                        $hb_auth_response = $this->hb_authentication();
                        if ($hb_auth_response['status']) {

                            $hb_access_token = $hb_auth_response['data']['accessToken'];
                            $hb_amount = number_format(Session::get('total_amount_for_transaction'), 2, '.', '');
                            $hb_name = $corporate_customer_data->company_name_en;
                            $hb_email = $corporate_customer_data->primary_email;
                            $hb_phone = str_replace("+", "", $corporate_customer_data->primary_phone);
                            $hb_merchant_invoice_number = $this->hb_generate_invoice_number($savedBookingId);
                            $hb_expiration_date = $booking_corporate_invoice_data['expiry'];
                            $hb_booking_reference_number = DB::table('booking')->where('id', $savedBookingId)->value('reservation_code');
                            $hb_generate_invoice_response = $this->hb_generate_invoice($hb_access_token, $hb_amount, $hb_name, $hb_email, $hb_phone, $hb_merchant_invoice_number, $hb_expiration_date, $hb_booking_reference_number);

                            if ($hb_generate_invoice_response['status']) {

                                $hb_access_token = $hb_auth_response['data']['accessToken'];
                                $hb_invoice_number = $hb_generate_invoice_response['data']['invoice_no'];
                                $hb_send_invoice_response = $this->hb_send_invoice($hb_access_token, $hb_invoice_number);

                                if ($hb_send_invoice_response['status']) {

                                    // update things in our db as invoice is sent to corporate customer
                                    $payment_link = $hb_generate_invoice_response['url'];
                                    $payment_short_link = custom::fireBaseShortLink($payment_link);
                                    $update_corporate_invoice_by['payment_link'] = $payment_short_link;
                                    $update_corporate_invoice_by['description'] = json_encode($hb_generate_invoice_response);
                                    $update_corporate_invoice_by['invoice_id'] = $hb_generate_invoice_response['data']['invoice_no'];
                                    $corporate_booking['booking_id'] = $savedBookingId;
                                    $this->page->updateData('booking_corporate_invoice', $update_corporate_invoice_by, $corporate_booking);
                                    return redirect($this->lang_base_url . '/booking-done');

                                } else {
                                    $error_message = $this->hb_get_error_string($hb_generate_invoice_response['errors']);
                                    session()->put('hyper_pay_transaction_error', sprintf(trans('labels.hyper_pay_payment_error'), $error_message));
                                    return redirect($this->lang_base_url . '/payment');
                                }

                            } else {
                                $error_message = $this->hb_get_error_string($hb_generate_invoice_response['errors']);
                                session()->put('hyper_pay_transaction_error', sprintf(trans('labels.hyper_pay_payment_error'), $error_message));
                                return redirect($this->lang_base_url . '/payment');
                            }
                        } else {
                            $error_message = $this->hb_get_error_string($hb_auth_response['errors']);
                            session()->put('hyper_pay_transaction_error', sprintf(trans('labels.hyper_pay_payment_error'), $error_message));
                            return redirect($this->lang_base_url . '/payment');
                        }

                    } else {
                        //curl for sending invoice and sms to user
                        $expiryperiod = $get_expiry_period . "H";
                        $customerEmailAddress = $corporate_customer_data->primary_email;
                        //$companyName = $this->lang == 'eng'?$corporate_customer_data->company_name_en:$corporate_customer_data->company_name_ar;
                        $driverName = $corporate_driver_data->first_name . ' ' . $corporate_driver_data->last_name;
                        //$customerEmailAddress = 'ahsan@astutesol.com';
                        $lang = $this->lang == 'eng' ? "en" : "ar";
                        $get_ammount = number_format(Session::get('total_amount_for_transaction'), 2, '.', '');
                        $amount = $get_ammount * 100;
                        $invoiceID = $savedBookingId;
                        $customerMobileNumber = str_replace("+", "", $corporate_customer_data->primary_phone);
                        //$customerMobileNumber = '923347462671';

                        //$invoiceID actually is our booking_id
                        $sendInvoice = $this->curlRequestForSTSPayLaterInvoice($expiryperiod, $driverName, $customerEmailAddress, $lang, $amount, $invoiceID, $customerMobileNumber, $resendInvoice = false);

                        if ($sendInvoice['status'] == 1) {

                            //send email of invoice to corporate user and drive both
                            //$invoice_payment_link = "https://www.sts-payment-ivoice.com";
                            $invoice_payment_link = $sendInvoice['payment_link'];
                            $this->sendSTSInvoice($savedBookingId, $invoice_payment_link);
                            if ($this->lang == "arb")
                                $bDonePath = "booking-done";
                            else
                                $bDonePath = "en/booking-done";

                        } else {
                            Session::put('error_message_payment', $sendInvoice['ErrorMessage']);
                            Session::save();
                            if ($this->lang == "arb")
                                $bDonePath = "payment";
                            else
                                $bDonePath = "en/payment";
                        }

                        return redirect($bDonePath);
                    }
                } else {

                    if ($this->lang == "arb")
                        $bDonePath = "booking-done";
                    else
                        $bDonePath = "en/booking-done";
                    // send email to admin, primary, secondary emails and driver email
                    // $this->sendToKeyAdmin($savedBookingId);
                    // $this->sendToCorporateUser($savedBookingId);
                    // $this->sendToDriver($savedBookingId);
                    // send sms to driver
                    $this->sendCorporateBookingSms($booking_info_extra['reservation_code'], $corporate_driver['mobile_no']);
                    // code to send email and sms to branch agent starts here
                    if (Session::get('search_data')['is_delivery_mode'] == 1) {
                        $customer_name = $corporate_driver['first_name'] . ' ' . $corporate_driver['last_name'];
                        // $this->send_email_to_branch_agent(Session::get('booking_id'), $branch_info->email, $corporate_driver['first_name'], $corporate_driver['last_name']);
                        $this->send_sms_to_branch_agent($booking_info_extra['reservation_code'], $branch_info->mobile, $customer_name);
                    }
                    // ends here
                    return redirect($bDonePath);
                }
            } else {
                return redirect($this->lang_base_url);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function resendPayLaterInvoice(Request $request)
    {
        //$invoiceID actually is our booking_id
        try {
            $site_settings = custom::site_settings();
            $booking_id = $request->input('bookingId');
            $total_sum = $request->input('total_sum');

            $booking_corporate_invoice_data = $this->page->getSingle('booking_corporate_invoice', array('booking_id' => $booking_id));
            if ($booking_corporate_invoice_data->cc_company == 'hyper_pay') {
                // code to generate and send pay later invoice with hyper bill api
                $hb_auth_response = $this->hb_authentication();
                if ($hb_auth_response['status']) {

                    $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
                    $uid = $booking_corporate_customer->uid;
                    // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                    $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                    $booking_payment = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));

                    $hb_access_token = $hb_auth_response['data']['accessToken'];
                    $hb_amount = number_format($booking_payment->total_sum, 2, '.', '');
                    $hb_name = $corporate_customer->company_name_en;
                    $hb_email = $corporate_customer->primary_email;
                    $hb_phone = str_replace("+", "", $corporate_customer->primary_phone);
                    $hb_merchant_invoice_number = $this->hb_generate_invoice_number($booking_id, true);
                    $hb_expiration_date = date('Y-m-d H:i:s', strtotime("+" . $corporate_customer->expiry_period . " hours"));
                    $hb_booking_reference_number = DB::table('booking')->where('id', $booking_id)->value('reservation_code');
                    $hb_generate_invoice_response = $this->hb_generate_invoice($hb_access_token, $hb_amount, $hb_name, $hb_email, $hb_phone, $hb_merchant_invoice_number, $hb_expiration_date, $hb_booking_reference_number);

                    if ($hb_generate_invoice_response['status']) {

                        $hb_access_token = $hb_auth_response['data']['accessToken'];
                        $hb_invoice_number = $hb_generate_invoice_response['data']['invoice_no'];
                        $hb_send_invoice_response = $this->hb_send_invoice($hb_access_token, $hb_invoice_number);

                        if ($hb_send_invoice_response['status']) {

                            // update things in our db as invoice is sent to corporate customer
                            $payment_link = $hb_generate_invoice_response['url'];
                            $payment_short_link = custom::fireBaseShortLink($payment_link);

                            $update_corporate_invoice_by['expiry'] = $hb_generate_invoice_response['data']['expiration_date'];
                            $update_corporate_invoice_by['attempts'] = $booking_corporate_invoice_data->attempts + 1;
                            $update_corporate_invoice_by['payment_link'] = $payment_short_link;
                            $update_corporate_invoice_by['description'] = json_encode($hb_generate_invoice_response);
                            $update_corporate_invoice_by['invoice_id'] = $hb_generate_invoice_response['data']['invoice_no'];
                            $corporate_booking['booking_id'] = $booking_id;
                            $this->page->updateData('booking_corporate_invoice', $update_corporate_invoice_by, $corporate_booking);
                            $response['status'] = true;
                            $response['title'] = 'Pay Later Invoice Resent';
                            $response['message'] = 'Invoice has been sent successfully.';
                            echo json_encode($response);
                            exit();
                        } else {
                            $response['status'] = false;
                            $response['title'] = trans('labels.error');
                            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), $this->hb_get_error_string($hb_auth_response['errors']));
                            echo json_encode($response);
                            exit();
                        }

                    } else {
                        $response['status'] = false;
                        $response['title'] = trans('labels.error');
                        $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), $this->hb_get_error_string($hb_auth_response['errors']));
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $response['status'] = false;
                    $response['title'] = trans('labels.error');
                    $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), $this->hb_get_error_string($hb_auth_response['errors']));
                    echo json_encode($response);
                    exit();
                }

            } else {
                $amount = $total_sum * 100;
                $lang = $request->input('lang') == 'eng' ? "en" : "ar";
                $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_id));
                $uid = $booking_corporate_customer->uid;
                // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                $customerEmailAddress = $corporate_customer->primary_email;
                $companyName = $request->input('lang') ? $corporate_customer->company_name_en : $corporate_customer->company_name_ar;
                //$customerEmailAddress = 'ahsan@astutesol.com';
                $customerMobileNumber = str_replace("+", "", $corporate_customer->primary_phone);
                $expiryperiod = $corporate_customer->expiry_period . "H";
                $sendInvoice = $this->curlRequestForSTSPayLaterInvoice($expiryperiod, $companyName, $customerEmailAddress, $lang, $amount, $booking_id, $customerMobileNumber, $resendInvoice = true);
                //$updated = false;
                if ($sendInvoice['status'] == 1) {
                    $response['status'] = true;
                    $response['title'] = 'STS Pay Later Invoice Resend';
                    $response['message'] = 'Invoice has been sent successfully.';
                } else {
                    $response['status'] = false;
                    $response['title'] = trans('labels.error');
                    $response['message'] = 'Invoice is not being sent successfully. Please try again.';
                }
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }


    private function generatePayLaterDesc($companyName, $invoiceID)
    {
        //set empty strings
        $description = "";
        $booking_number = $invoiceID;
        $customer_name = $companyName; //its a corporate company name
        $from_location = "";
        $to_location = "";
        $pickup_datetime = "";
        $dropoff_datetime = "";
        $rent_amount = "";
        $cdw = "";
        $cdw_plus = "";
        $gps = "";
        $extra_driver = "";
        $baby_seat = "";
        $vat = "";

        //set values
        $sessionVals = Session::get('search_data');
        if ($sessionVals['is_delivery_mode'] == 1 && $sessionVals['pickup_delivery_coordinate'] != '' && $sessionVals['dropoff_delivery_coordinate'] != '') {
            //do nothing
        } else {
            //get the  branch names by id
            $from_location = "";
            $to_location = "";
        }
        $pickup_datetime = date('Y-m-d', strtotime($sessionVals['pickup_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['pickup_time']));
        $dropoff_datetime = date('Y-m-d', strtotime($sessionVals['dropoff_date'])) . ' ' . date('H:i:s', strtotime($sessionVals['dropoff_time']));
        $rent_amount = Session::get('total_rent_for_all_days');

        $cdw = Session::get('cdw_charges');
        $cdw_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

        $cdw_plus = Session::get('cdw_plus_charges');
        $cdw_plus_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

        $gps = Session::get('gps_charges');
        $gps_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

        $extra_driver = Session::get('extra_driver_charges');
        $extra_driver_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

        $baby_seat = Session::get('baby_seat_charges');
        $baby_seat_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

        $vat = Session::get('vat');

        //concatenate
        if ($this->lang == "eng") {
            $description .= "Booking #: " . $booking_number . ", ";
            //$description .= "Company: " . $customer_name . ", ";
            $description .= $customer_name . ", ";
            if ($from_location) $description .= "Pickup Location: " . $from_location . ", ";
            if ($to_location) $description .= "Return Location: " . $to_location . ", ";
            $description .= "Pickup Date/Time: " . $pickup_datetime . ", ";
            $description .= "Return Date/Time: " . $dropoff_datetime . ", ";
            $description .= "Rent: " . $rent_amount . ", ";
            if ($cdw) $description .= "CDW: " . $cdw . ", ";
            if ($cdw_plus) $description .= "CDW Plus: " . $cdw_plus . ", ";
            if ($gps) $description .= "GPS: " . $gps . ", ";
            if ($extra_driver) $description .= "Extra Driver: " . $extra_driver . ", ";
            if ($baby_seat) $description .= "Baby Seat: " . $baby_seat . ", ";
            $description .= "VAT: " . $vat;
        } else {
            $description .= "رقم الحجز: " . $booking_number . ", ";
            //$description .= "العميل: " . $customer_name . ", ";
            $description .= $customer_name . ", ";
            if ($from_location) $description .= "موقع الاستلام: " . $from_location . ", ";
            if ($to_location) $description .= "موقع التسليم: " . $to_location . ", ";
            $description .= "تاريخ / وقت الإستلام: " . $pickup_datetime . ", ";
            $description .= "تاريخ / وقت التسليم: " . $dropoff_datetime . ", ";
            $description .= "تأجير: " . $rent_amount . ", ";
            if ($cdw) $description .= "تأمين شامل: " . $cdw . ", ";
            if ($cdw_plus) $description .= "تأمين شامل بلس: " . $cdw_plus . ", ";
            if ($gps) $description .= "جهاز تحديد المواقع: " . $gps . ", ";
            if ($extra_driver) $description .= "سائق إضافي: " . $extra_driver . ", ";
            if ($baby_seat) $description .= "مقعد الطفل: " . $baby_seat . ", ";
            $description .= "ضريبة القيمة المضافة: " . $vat;
        }

        return $description;
    }

    private function curlRequestForSTSPayLaterInvoice($expiryperiod, $companyName, $customerEmailAddress, $lang, $amount, $invoiceID, $customerMobileNumber, $resendInvoice)
    {
        $description = $this->generatePayLaterDesc($companyName, $invoiceID);

        $apiSettings = custom::api_settings();
        $SECRET_KEY = $apiSettings->sts_paylater_secret_key;
        $MERCHANT_ID = $apiSettings->sts_paylater_merchant_id;
        $sts_paylater_send_invoice_link = $apiSettings->sts_paylater_send_invoice_link;
        $attempts = 0;
        $getInvoiceId = 'KEY' . str_pad($invoiceID, 17, '0', STR_PAD_LEFT);

        if ($resendInvoice) {
            $invoiceInfo = $this->page->getSingle('booking_corporate_invoice', array('booking_id' => $invoiceID));
            $get_attempts = $invoiceInfo->attempts;
            $get_expiry_period = explode('H', $expiryperiod);
            $payLater_expiry_period = date('Y-m-d H:i:s', strtotime("+" . $get_expiry_period[0] . " hours"));
            $update_corporate_invoice_by['expiry'] = $payLater_expiry_period;
            $update_corporate_invoice_by['description'] = $invoiceInfo->description;
            $attempts = $get_attempts + 1;
            $get_attempts = str_pad($attempts, 3, "0", STR_PAD_LEFT);
            $getInvoiceId = 'KEY' . $get_attempts . str_pad($invoiceID, 14, '0', STR_PAD_LEFT);
        }

        $requestQueryArr = [
            "authenticationToken" => $SECRET_KEY,
            "merchantID" => $MERCHANT_ID,
            "invoicesDetails" => array(
                array(
                    "currency" => "682",
                    "expiryperiod" => $expiryperiod,
                    "customerEmailAddress" => $customerEmailAddress,
                    "paymentDescription" => $description,
                    "dynamicFields" => array(array(
                        "ItemID" => "186"
                    )),
                    "language" => $lang,
                    "amount" => intval($amount), // had to intval this because STS was receiving amount with long decimal value
                    "invoiceID" => $getInvoiceId,
                    "customerMobileNumber" => $customerMobileNumber
                )
            )
        ];

        $str_length = (int)strlen(json_encode($requestQueryArr)) + 9;

        $ch = curl_init($sts_paylater_send_invoice_link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "invoices=" . json_encode($requestQueryArr));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . $str_length)
        );

        /*echo "URL=" . $sts_paylater_send_invoice_link . "<br>";
        echo "invoices=" . json_encode($requestQueryArr) . "<br>";
        echo 'Content-Length: ' . $str_length . "<br>";
        die();*/

        $output = curl_exec($ch);
        $paylater_service_down = false;
        if ($output === false) {
            //echo 'Curl error: ' . curl_error($ch);
            //exit;
            $paylater_service_down = true;
        }
        $get_outout = json_decode($output);
        /*echo '<pre>';
        print_r($get_outout);
        exit;*/
        $response_arr = array();
        if (isset($get_outout->invoicesDetails)) {
            $getInvoiceDetails = $get_outout->invoicesDetails;
            //on success we need to save payment link in `booking_corporate_invoice` table
            //here will be query to update 'payment_link' column in `booking_corporate_invoice` table
            //$payment_link = "https://www.sts-payment-ivoice.com"; //get it from curl output
            $payment_link = $getInvoiceDetails[0]->paymentLink;
            $payment_short_link = custom::fireBaseShortLink($payment_link);
            $update_corporate_invoice_by['attempts'] = $attempts;
            $update_corporate_invoice_by['payment_link'] = $payment_short_link;
            $update_corporate_invoice_by['description'] = $description;
            $corporate_booking['booking_id'] = $invoiceID;
            $this->page->updateData('booking_corporate_invoice', $update_corporate_invoice_by, $corporate_booking);
            $response_arr['status'] = "1";
            $response_arr['payment_link'] = $payment_short_link;
        } elseif ($paylater_service_down) {
            $response_arr['status'] = "0";
            $response_arr['ErrorCode'] = "";
            $response_arr['ErrorMessage'] = "Something went wrong please try again later!";
        } else {
            $response_arr['status'] = "0";
            $response_arr['ErrorCode'] = $get_outout->Error;
            $response_arr['ErrorMessage'] = $get_outout->ErrorMessage;
        }
        return $response_arr;

    }

    private function sendCorporateBookingSms($reservation_code, $userPhoneNo)
    {
        //$lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        $lang = $bookingInfo->lang;
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Thank you for booking at key.sa Reservation# ";
            $linkMsg = "Click below link to see this reservation details ";
        } else {
            $smsMsg = "شكرا لحجزكم في المفتاح لتأجير السيارات، رقم الحجر # ";
            $linkMsg = "لتفاصيل الحجز، الرجاء إستخدام الظغط التالي";
        }
        //reservation thankyou sms
        $smsMsg .= $reservation_code . "\n";
        $smsMsg .= $linkMsg . "\n";
        $smsMsg .= $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id);
        $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);
        custom::sendSMS($smsPhone, $smsMsg, $lang);
    }

    private function sendCorporateInvoiceSms($reservation_code, $userPhoneNo, $invoice_payment_link)
    {
        //$lang = $this->lang;
        $lang_base_url = $this->lang_base_url;
        $bookingInfo = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        $lang = $bookingInfo->lang;
        //========send thank you sms with reservation number
        if ($lang == "eng") {
            $smsMsg = "Thank you for booking at key.sa Reservation# ";
            $invoiceMsg = "To pay and confirm your booking please click on the following link";
            $linkMsg = "For booking details please click on the following link";
        } else {
            $smsMsg = "شكرا لحجزكم في المفتاح لتأجير السيارات، رقم الحجر # ";
            $invoiceMsg = "للدفع وتأكيد الحجز الرجاء الضغط على الرابط التالي";
            $linkMsg = "لمعرفة تفاصيل الحجز الرجاء الضغط على الرابط التالي";
        }
        //reservation thankyou sms
        $smsMsg .= $reservation_code . "\n";
        $smsMsg .= $linkMsg . "\n";
        $smsMsg .= $lang_base_url . '/manage-booking/' . custom::encode_with_jwt($bookingInfo->id) . "\n";
        $smsMsg .= $invoiceMsg . "\n";
        $smsMsg .= $invoice_payment_link;
        $smsPhone = str_replace(array('+', ' '), '', $userPhoneNo);
        custom::sendSMS($smsPhone, $smsMsg, $lang);
    }

    private function sendToKeyAdmin($bid)
    {
        // return true; // put it here as Fozan asked to stop sending emails to admin on 07-02-2018.
        //$lang = $this->lang;
        $smtp_settings = custom::smtp_settings();
        $site_settings = custom::site_settings();
        $emailData = array();
        $email = array();
        if ($bid == "") {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = $bid;
        }
        $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));

        $lang = $booking_detail->lang;

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
        $fileName = $this->bookingPdf($emailData, $emailObj->reservation_code);
        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';
        $email['attachment'] = $attachment;
        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendToCorporateUser($bid)
    {
        //$lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($bid == "") {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = $bid;
        }
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $lang = $emailObj->lang;
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
        $fileName = $this->bookingPdf($emailData, $emailObj->reservation_code);
        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';
        $email['attachment'] = $attachment;
        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    private function sendSTSInvoice($bid, $invoice_payment_link)
    {
        //$lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($bid == "") {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = $bid;
        }
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $lang = $emailObj->lang;

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
        $emailData['booking_content']['invoice_payment_link'] = $invoice_payment_link;
        $emailAddress_corporate_user = $emailObj->corporate_user_email;
        $emailAddress_driver = $emailObj->email;

        if ($lang == "eng") {
            $subject = "Pending Payment";
        } else {
            $subject = "شركة المفتاح (حجز معلق)"; //get arabic123 for this subject.
        }
        $email['subject'] = $subject;
        $email['fromEmail'] = $site_settings->username;
        $email['fromName'] = "no-reply";
        $email['toEmail'] = $emailAddress_corporate_user;
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';

        //sending email to corporate user
        custom::sendEmail('paylater', $emailData, $email, $lang);

        //sending email to driver
        $email['toEmail'] = $emailAddress_driver;
        custom::sendEmail('paylater', $emailData, $email, $lang);

        $this->sendCorporateInvoiceSms($emailObj->reservation_code, $emailObj->mobile_no, $invoice_payment_link);
    }

    private function sendToDriver($bid)
    {
        //$lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($bid == "") {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = $bid;
        }
        $emailObj = $this->page->getSingleBookingDetailsForCorporate($booking_id, 'individual_user');
        $lang = $emailObj->lang;
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
        $fileName = $this->bookingPdf($emailData, $emailObj->reservation_code);
        $attachment = public_path('pdf/') . $fileName;
        $email['pdf'] = 'pdf';
        $email['attachment'] = $attachment;
        custom::sendEmail('booking', $emailData, $email, $lang);
    }

    public function getDriverInfo(Request $request)
    {
        try {
            $get_driver_by = $request->input('get_driver_by');
            if (custom::isCorporateLoyalty() && session::get('customer_id_no_for_loyalty') != '') {
                //Actually this corporate driver is now loaded with individual user data, but it will save in the driver table
                $corporate_driver = $this->page->getSingle('individual_customer', array('id_no' => $get_driver_by));
            } else {
                $corporate_driver = $this->page->getDriverInfo($get_driver_by);
            }

            if ($corporate_driver) {
                $corporate_driver->mobile_no_seperated = custom::getPhoneNumber($corporate_driver->mobile_no);
                $status = true;
                $data = $corporate_driver;
            } else {
                $status = false;
                $data = "";
            }
            $responseArray['status'] = $status;
            $responseArray['data'] = $data;
            echo json_encode($responseArray);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendCancellationEmailToCorporateUser($bid)
    {
        $lang = $this->lang;
        $site_settings = custom::smtp_settings();
        //echo "<pre>"; print_r($site_settings); exit;
        $emailData = array();
        $email = array();
        if ($bid == "") {
            $booking_id = Session::get('booking_id');
        } else {
            $booking_id = $bid;
        }
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

    public function closeOasisPricing()
    {
        try {
            $id = $_REQUEST['id'];
            $closing_date = $_REQUEST['closing_date'];
            $record = $this->page->getSingle('car_price', array('id' => $id));
            if ($record) {
                $update_by['id'] = $id;
                $data['applies_to'] = date('Y-m-d', strtotime($closing_date));
                $this->page->updateData('car_price', $data, $update_by);
                echo 1;
                exit();
                /*$response['status'] = 1;
                $response['message'] = "Record Updated Successfully.";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();*/
            } else {
                echo 0;
                exit();
                /*$response['status'] = 0;
                $response['message'] = "No Record Found Against This ID.";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();*/
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function updateBookingStatus()
    {
        try {
            $data = array();
            $bookingStatus = '';
            $reservation_code = $_REQUEST['ID'];
            $oasis_booking_status = $_REQUEST['Status'];
            if ($oasis_booking_status == 'A') {
                $bookingStatus = 'Not Picked';
            } elseif ($oasis_booking_status == 'O') {
                $bookingStatus = 'Picked';
            } elseif ($oasis_booking_status == 'H') {
                $bookingStatus = 'Completed with Overdue';
            } elseif ($oasis_booking_status == 'C') {
                $bookingStatus = 'Completed';
            } elseif ($oasis_booking_status == 'I') {
                $bookingStatus = 'Cancelled';
            } elseif ($oasis_booking_status == 'E') {
                $bookingStatus = 'Expired';
            } elseif ($oasis_booking_status == 'V') {
                $bookingStatus = 'Walk in';
            }
            $data['booking_status'] = $bookingStatus;
            if ($reservation_code) {
                $update_by['reservation_code'] = $reservation_code;
                $this->page->updateData('booking', $data, $update_by);
                echo 1;
                exit();
            } else {
                echo 0;
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    /*Human Less functions*/
    /*app/Http/Controllers/Front/HumanLessController.php*/
    /*end Human Less functions*/

    public function setOasisPricing()
    {
        try {
            $site_settings = custom::site_settings();
            if ($site_settings->pricing_api == 'off') {
                exit();
            }
            $token = sha1(md5(date('YmdHi')));
            $created_at = date('Y-m-d H:i:s');
            $posted_record_id = (isset($_REQUEST['RECORD_ID']) && $_REQUEST['RECORD_ID'] != "" ? $_REQUEST['RECORD_ID'] : "");
            $request_arr['car_model_id'] = $_REQUEST['CAR_MODEL']; // this is car model year
            $request_arr['car_type_id'] = $_REQUEST['CAR_TYPE']; // this is car model oasis reference number
            $request_arr['charge_element'] = $_REQUEST['CONTRACT_CHARGE_ELMENT_ID']; // this is oracle ref number
            $request_arr['renting_type_id'] = $_REQUEST['RENTING_TYPE']; // this is oracle ref number
            $request_arr['price'] = $_REQUEST['CHARGE_VALUE'];
            $request_arr['applies_from'] = date("Y-m-d", strtotime($_REQUEST['APPLIES_FROM']));
            $request_arr['applies_to'] = (isset($_REQUEST['APPLIES_TO']) && $_REQUEST['APPLIES_TO'] != "" ? date('Y-m-d', strtotime($_REQUEST['APPLIES_TO'])) : ""); // Not Mandatory
            $request_arr['region_id'] = $_REQUEST['REGION']; // this is oracle ref number
            $request_arr['branch_id'] = $_REQUEST['BRANCH']; // this is oracle ref number
            $request_arr['customer_type'] = (isset($_REQUEST['CUSTOMER_TYPE']) && $_REQUEST['CUSTOMER_TYPE'] != "" ? $_REQUEST['CUSTOMER_TYPE'] : ""); // P = Individual, I = Corporate, empty = BOTH // OPTIONAL
            $request_arr['created_at'] = $created_at;
            $request_arr['type'] = 'api';
            //echo '<pre>';print_r($request_arr);exit();
            $validator = $this->validateRequestData($request_arr);
            if ($validator['hasError']) {
                echo "0 " . $validator['error_is'];
                exit();
                /*$response['status'] = 0;
                $response['message'] = $validator['error_is'];
                $response['record_id'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();*/
            } else {
                if ($request_arr['renting_type_id'] == '26757') {
                    $renting_type_id = 1;
                } else if ($request_arr['renting_type_id'] == '26759') {
                    $renting_type_id = 4;
                }
                if ($request_arr['customer_type'] == 'P') {
                    $customer_type = 'Individual';
                } else if ($request_arr['customer_type'] == 'I') {
                    $customer_type = 'Corporate';
                } else {
                    $customer_type = '';
                }
                if ($request_arr['charge_element'] == '26723') {
                    $charge_element = 'Rent';
                } else if ($request_arr['charge_element'] == '31061') {
                    $charge_element = 'CDW';
                }
                if (isset($request_arr['region_id']) && $request_arr['region_id'] != "") {
                    $region_details = $this->page->getSingle('region', array('oracle_reference_number' => $request_arr['region_id']));
                }
                $car_model_details = $this->page->getSingle('car_model', array('year' => $request_arr['car_model_id'], 'oracle_reference_number' => $request_arr['car_type_id']));
                $branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $request_arr['branch_id']));
                $toCheckConflict['car_model_id'] = $car_model_details->id;
                $toCheckConflict['charge_element'] = $charge_element;
                $toCheckConflict['renting_type_id'] = $renting_type_id;
                $toCheckConflict['applies_from'] = date('Y-m-d', strtotime($request_arr['applies_from']));
                $toCheckConflict['applies_to'] = (isset($request_arr['applies_to']) && $request_arr['applies_to'] != "" ? date('Y-m-d', strtotime($request_arr['applies_to'])) : "");
                $toCheckConflict['region_id'] = (isset($region_details) ? $region_details->id : ""); // as its also optional
                if (isset($request_arr['branch_id']) && $request_arr['branch_id'] != "") // if branch ID is being posted, we will save it to branch level+city
                {
                    $toCheckConflict['city_id'] = $branch_details->city_id;
                    $toCheckConflict['branch_id'] = $branch_details->id;
                }
                $toCheckConflict['customer_type'] = $customer_type;
                $conflicts = $this->page->checkIfConflictDataExist($toCheckConflict);
                if (count($conflicts) > 0) {
                    $bad_log_data = $request_arr;
                    if (isset($request_arr['applies_to']) && $request_arr['applies_to'] != "") {
                        $bad_log_data['applies_to'] = date('Y-m-d', strtotime($request_arr['applies_to']));
                    } else {
                        unset($bad_log_data['applies_to']); // to make it null
                    }
                    if (isset($request_arr['branch_id']) && $request_arr['branch_id'] != "") // if branch ID is being posted, we will save it to branch level+city
                    {
                        $bad_log_data['branch_id'] = $request_arr['branch_id'];
                    } else {
                        unset($bad_log_data['branch_id']); // to make it null
                    }
                    $this->page->saveData('car_price_bad_log', $bad_log_data);
                    echo "0";
                    exit();
                    /*$message = 'Conflicting data found. Moved to bad log.';
                    $saved_record_id = "";
                    $status = 0;
                    $response['status'] = $status;
                    $response['message'] = $message;
                    $response['record_id'] = $saved_record_id;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();*/
                } else {
                    $pricing_data['car_model_id'] = $car_model_details->id;
                    $pricing_data['charge_element'] = $charge_element;
                    $pricing_data['renting_type_id'] = $renting_type_id;
                    $pricing_data['price'] = $request_arr['price'];
                    $pricing_data['applies_from'] = date('Y-m-d', strtotime($request_arr['applies_from']));
                    if (isset($request_arr['applies_to']) && $request_arr['applies_to'] != "") {
                        $pricing_data['applies_to'] = date('Y-m-d', strtotime($request_arr['applies_to']));
                    }
                    $pricing_data['region_id'] = (isset($region_details) ? $region_details->id : 0); // as its also optional
                    if (isset($request_arr['branch_id']) && $request_arr['branch_id'] != "") // if branch ID is being posted, we will save it to branch level+city
                    {
                        $pricing_data['city_id'] = $branch_details->city_id;
                        $pricing_data['branch_id'] = $branch_details->id;
                    }
                    $pricing_data['customer_type'] = $customer_type;
                    $pricing_data['created_at'] = $created_at;
                    if ($posted_record_id != "") {
                        $this->page->updateData('car_price', $pricing_data, array("id" => $posted_record_id));
                        $saved_record_id = $posted_record_id;
                    } else {
                        $saved_id = $this->page->saveData('car_price', $pricing_data);
                        $saved_record_id = $saved_id;
                    }
                    echo $saved_record_id;
                    exit();
                    /*$message = 'Data saved successfully.';
                    $status = 1;
                    $response['status'] = $status;
                    $response['message'] = $message;
                    $response['record_id'] = $saved_record_id;
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();*/
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function validateRequestData($data_arr)
    {
        $hasError = false;
        $error_is = '';
        $region_id = $data_arr['region_id'];
        $branch_id = $data_arr['branch_id'];
        $car_type_id = $data_arr['car_type_id']; // this is car model oracle reference number
        $car_model_id = $data_arr['car_model_id']; // this is car model year
        $renting_type_id = $data_arr['renting_type_id'];
        $charge_element = $data_arr['charge_element'];
        $region_details = $this->page->getSingle('region', array('oracle_reference_number' => $region_id));
        $branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $branch_id));
        $car_model_details = $this->page->getSingle('car_model', array('year' => $car_model_id, 'oracle_reference_number' => $car_type_id));
        $renting_type_details = $this->page->getSingle('setting_renting_type', array('oracle_reference_number' => $renting_type_id));
        // checking if all data exists for these oracle reference numbers in our system
        if ($region_details && $branch_details && $car_model_details && $renting_type_details) {
            // doing nothing as all is fine
        } else {
            $hasError = true;
            $error_is .= 'Data not existing for atleast one of these (Region, Branch, Car Model, Renting Type), ';
        }
        // checking if region and branch are belonging to each other
        $branch_city_details = $this->page->getSingle('city', array('id' => $branch_details->city_id));
        $city_region_details = $this->page->getSingle('region', array('id' => $branch_city_details->region_id));
        if (isset($region_id) && ($region_id != "") && ($city_region_details->oracle_reference_number != $region_id)) {
            $hasError = true;
            $error_is .= 'Region and branch are not from same group, ';
        }
        // checking if charge element is not out of those two mentioned in excel provided
        if ($charge_element != '26723' && $charge_element != '31061') // 26723 = Rent, 31061 = CDW
        {
            $hasError = true;
            $error_is .= 'Given charge element can not be inserted, ';
        }
        // checking if renting type ids are not out of existing ones in our system
        if ($renting_type_id != '26757' && $renting_type_id != '26759') // 26757 = 1, 26759 = 4
        {
            $hasError = true;
            $error_is .= 'Given renting type not found in our system, ';
        }
        if ($hasError) {
            $error_is = 'ERROR: ' . rtrim($error_is, ',') . '.';
        }
        return array('hasError' => $hasError, 'error_is' => $error_is);
    }

    public function filterSellingCars(Request $request)
    {
        try {
            $offset = 0;
            $limit = 9999999;
            $car_brand_id = $request->input('car_brand_id');
            $car_year = $request->input('car_year');
            $cars = $this->page->getCarsToSell($car_brand_id, $car_year, $offset, $limit, $this->lang);
            if (count($cars) > 0) {
                $html = custom::sellCarsHtml($cars, $this->base_url, $this->lang_base_url, $this->lang);
            } else {
                $html = '<div class="noRecordFoundDiv"><div class="noResultFound"><span>' . ($this->lang == 'eng' ? 'No Record Found.' : 'لايوجد نتائج') . '</span></div></div>';
            }
            echo $html;
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function interestedInCar(Request $request)
    {
        try {
            $is_email_valid = custom::validate_email($request->input('email'));
            if (!$is_email_valid) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.enter_valid_email_msg');
                echo json_encode($response);
                exit();
            }

            // captcha verification function
            $siteKey = $request->input('g-recaptcha-response');
            $res = $this->reCaptcha($siteKey);
            if ($res == false) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = trans('labels.captcha_msg');
                echo json_encode($response);
                exit();
            }

            $lang = $this->lang;
            $data['name'] = $request->input('name');
            $data['mobile_no'] = $request->input('mobile_no');
            $data['email'] = $request->input('email');
            $data['car_id'] = $request->input('car_id');
            $data['created_at'] = date('Y-m-d H:i:s');
            $checkIfHeAlreadySentRequest = $this->page->getRowsCount('car_selling_response', array('car_id' => $data['car_id'], 'mobile_no' => $data['mobile_no']));
            if ($checkIfHeAlreadySentRequest > 0) {
                $response['status'] = false;
                $response['title'] = trans('labels.error');
                $response['message'] = ($this->lang == 'eng' ? 'Sorry. You have already requested against this car model.' : 'آسف. كنت قد طلبت بالفعل ضد هذا النموذج سيارة.');
                echo json_encode($response);
                exit();
            } else {
                $saved_id = $this->page->saveData('car_selling_response', $data);
                if ($saved_id > 0) {
                    $page_content = $this->page->getSingle('car_selling', array('id' => '1'));
                    $car_details = (array)$this->page->getSingle('car_selling_model', array('id' => $data['car_id']));
                    $car_brand_details = (array)$this->page->getSingle('car_selling_brand', array('id' => $car_details['car_brand_id']));

                    $items = [];
                    $item_data = [
                        'item_id' => $car_brand_details['eng_title'] . " " . $car_details['eng_title'] . ' ' . $car_details['year'],
                        'item_name' => $car_brand_details['eng_title'] . " " . $car_details['eng_title'] . ' ' . $car_details['year']
                    ];
                    $items[] = $item_data;
                    $event = 'generate_lead';
                    $event_data = [
                        'currency' => 'SAR',
                        'value' => $car_details['eng_car_desc'],
                        'items' => $items
                    ];
                    custom::sendEventToGA4($event, $event_data);

                    // send email to admin
                    $site = custom::site_settings();
                    $smtp = custom::smtp_settings();
                    $email['subject'] = ($this->lang == 'eng' ? 'Interested In Car Selling' : 'مهتم في بيع السيارات');
                    $email['fromEmail'] = $smtp->username;
                    $email['fromName'] = "no-reply";
                    $email['toEmail'] = $page_content->recipient_email;
                    $email['ccEmail'] = ""; //$smtp->username;
                    $email['bccEmail'] = '';
                    $email['attachment'] = '';
                    $mail_data['data']['name'] = "Admin";
                    $mail_data['data']['gender'] = "male";
                    $mail_data['data']['contact_no'] = $site->site_phone;
                    $mail_data['data']['lang_base_url'] = $this->lang_base_url;
                    $mail_data['data']['message'] = ($this->lang == 'eng' ? 'A request has been received for selling a car. Below are the details of the interested person.' :
                        'تم استلام طلب لبيع سيارة. وفيما يلي تفاصيل الشخص المعني.');
                    $info['name'] = $data['name'];
                    $info['phone'] = $data['mobile_no'];
                    $info['email'] = $data['email'];
                    $info['car_brand'] = $car_brand_details[$lang . '_title'];
                    $info['car_model'] = $car_details[$lang . '_title'];
                    $info['year'] = $car_details['year'];
                    $info['details'] = $car_details[$lang . '_car_desc'];
                    //$info['image'] = '<img src="' . $this->base_url . '/public/uploads/' . $car_details['image1'] . '" alt="' . $car_details[$lang . '_title'] . '">';
                    $mail_data['data']['info'] = $info;
                    $sent = custom::sendEmail('form', $mail_data, $email, "eng");
                    $response['status'] = true;
                    $response['title'] = 'Success';
                    $response['message'] = ($this->lang == 'eng' ? 'Thanks for having interest in buying this car, one of our representative will contact you soon!' : 'شكرا لاهتمامك بشراء هذه السيارة، سيقوم أحد ممثلينا بالاتصال بك قريبا!');
                    echo json_encode($response);
                    exit();
                } else {
                    $response['status'] = false;
                    $response['title'] = trans('labels.error');
                    $response['message'] = ($this->lang == 'eng' ? 'Sorry. You request failed to be submitted. Please try again.' : 'نعتذر. لم يتم إرسال طلبك. حاول مرة اخرى.');
                    echo json_encode($response);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getMoreSellingCars(Request $request)
    {
        try {
            $offset = $request->offset;
            $limit = $request->limit;
            $cars = $this->page->getCarsToSell("", "", $offset, $limit, $this->lang);
            $html = custom::sellCarsHtml($cars, $this->base_url, $this->lang_base_url, $this->lang);
            $response['offset'] = (int)$offset + $limit;
            $response['show_load_more'] = (count($cars) < $limit ? false : true);
            $response['html'] = $html;
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function calculateRedeemPointsFromAmount(Request $request)
    {
        try {
            $site = custom::site_settings();
            $customer_total_points = $request->input('total_points');
            $amount_to_redeem = $request->input('value_to_convert');
            $customer_redeem_loyalty_type = $request->input('customer_redeem_loyalty_type');
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
            $session_data = Session::get('search_data');
            $rent_per_day = Session::get('rent_per_day');
            $days_for_checkout = $session_data['days'];
            $allowed_days_for_redeem = $site->days_for_redeem;
            /*  Validation how much riyal can be redeemed in a single checkout.
                % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
            if ($days_for_checkout > $allowed_days_for_redeem) {
                $days_to_calculate_and_check = $allowed_days_for_redeem;
            } else {
                $days_to_calculate_and_check = $days_for_checkout;
            }
            $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
            $redeemOffer = $this->page->checkIfRedeemAllowed($session_data['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $session_data['pickup_date']);
            if ($redeemOffer->type_of_redeem == 'Percentage') {
                $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
                $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
                $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
            } else {
                $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
            }
            //$amount_redeemable_divided_factor = $amount_redeemable / $factor_to_divide;
            if ($amount_to_redeem > $amount_redeemable) {

                $response['status'] = false;
                $response['message'] = ($this->lang == 'eng' ?
                    'The amount you have entered can not be redeemed. Please reduce the amount. (Max: ' . $amount_redeemable . ' SAR)' :
                    'لايمكن استخدام المبلغ المدخل، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                $response['total_redeemed_amount'] = 0;
                $response['total_redeemed_points'] = 0;
                echo json_encode($response);
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

                        $response['status'] = false;
                        $response['message'] = ($this->lang == 'eng' ?
                            'The amount you have entered can not be redeemed. Please reduce the amount. (Max: ' . $amount_redeemable . ' SAR)' :
                            'لايمكن استخدام المبلغ المدخل، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                        $response['total_redeemed_amount'] = 0;
                        $response['total_redeemed_points'] = 0;
                        echo json_encode($response);
                        exit();
                    } else {

                        $total_rent_after_discount_on_promo = Session::get('car_payment_data_to_check.total_rent_after_discount_on_promo');
                        Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_rent_after_discount_on_promo - $amount_to_redeem);
                        Session::put('car_payment_data_to_check.redeem_discount_availed', $amount_to_redeem);
                        Session::save();

                        $response['status'] = true;
                        $response['message'] = ($this->lang == 'eng' ? 'Redeem Applied Successfully.' : 'تم إضافة خصم نقاط الولاء');
                        $response['total_redeemed_amount'] = $amount_to_redeem;
                        $response['total_redeemed_points'] = $total_redeemed_points;
                        echo json_encode($response);
                        exit();
                    }
                } else {

                    $response['status'] = false;
                    $response['message'] = ($this->lang == 'eng' ? 'Sorry. The redeem can not be applied as no redeem factors are added in backend.' : 'عفوا لا يمكن إضافة عرض الاسترداد');
                    $response['total_redeemed_amount'] = 0;
                    $response['total_redeemed_points'] = 0;
                    echo json_encode($response);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function calculateRedeemAmountFromPoints(Request $request)
    {
        try {
            $site = custom::site_settings();
            $customer_total_points = $request->input('total_points');
            $points_to_redeem = $request->input('value_to_convert');
            $customer_redeem_loyalty_type = $request->input('customer_redeem_loyalty_type');
            if ($customer_redeem_loyalty_type == 'Silver') {
                $factor_to_multiply = $site->silver_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Golden') {
                $factor_to_multiply = $site->golden_redeem_factor;
            } elseif ($customer_redeem_loyalty_type == 'Platinum') {
                $factor_to_multiply = $site->platinum_redeem_factor;
            } else {
                $factor_to_multiply = 1;
            }
            $session_data = Session::get('search_data');
            $rent_per_day = Session::get('rent_per_day');
            $days_for_checkout = $session_data['days'];
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
                $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
                $redeemOffer = $this->page->checkIfRedeemAllowed($session_data['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $session_data['pickup_date']);
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

                    $response['status'] = false;
                    $response['message'] = ($this->lang == 'eng' ?
                        'The number of points you have entered can not be redeemed. You can only redeem this maximum amount. (Max: ' . $amount_redeemable . ' SAR)' :
                        'لايمكن استخدام عدد النقاط المدخلة، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                    $response['total_redeemed_amount'] = 0;
                    $response['total_redeemed_points'] = 0;
                    echo json_encode($response);
                    exit();
                } elseif ($points_to_redeem > $customer_total_points) {

                    $response['status'] = false;
                    $response['message'] = ($this->lang == 'eng' ?
                        'The number of points you have entered exceeding the number of points you have. PLease reduce the points you want to use. (Max: ' . $amount_redeemable . ' SAR)' :
                        'لايمكن استخدام عدد النقاط المدخلة، بإمكانكم استخدام (' . $amount_redeemable . ' ريال سعودي) كحد اعلى');
                    $response['total_redeemed_amount'] = 0;
                    $response['total_redeemed_points'] = 0;
                    echo json_encode($response);
                    exit();
                } else {

                    $total_rent_after_discount_on_promo = Session::get('car_payment_data_to_check.total_rent_after_discount_on_promo');
                    Session::put('car_payment_data_to_check.total_rent_after_discount_on_promo', $total_rent_after_discount_on_promo - $total_redeemed_amount_multiplied_factor);
                    Session::put('car_payment_data_to_check.redeem_discount_availed', $total_redeemed_amount_multiplied_factor);
                    Session::save();

                    $response['status'] = true;
                    $response['message'] = ($this->lang == 'eng' ? 'Redeem Applied Successfully.' : 'تم إضافة خصم نقاط الولاء');
                    $response['total_redeemed_amount'] = $total_redeemed_amount_multiplied_factor;
                    $response['total_redeemed_points'] = $points_to_redeem;
                    echo json_encode($response);
                    exit();
                }
            } else {

                $response['status'] = false;
                $response['message'] = ($this->lang == 'eng' ? 'Sorry. The redeem can not be applied as no redeem factors are added in backend.' : 'عفوا لا يمكن إضافة عرض الاسترداد');
                $response['total_redeemed_amount'] = 0;
                $response['total_redeemed_points'] = 0;
                echo json_encode($response);
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function register_walkin()
    {
        try {
            // checking if api's are turned off from backend then don't run the api's here
            $api_settings = custom::api_settings();
            if ($api_settings->walkin_api_on_off == 'off') {
                /*$response['status'] = 0;
                $response['message'] = 'Walkin api is off from backend.';
                $response['data'] = array();
                echo json_encode($response);*/
                echo 0;
                exit();
            }
            $posted_data = $_REQUEST;
            $posted_data['from_date'] = date('Y-m-d', strtotime($posted_data['from_date']));
            $posted_data['to_date'] = date('Y-m-d', strtotime($posted_data['to_date']));
            $walkin_id = $this->page->saveData('walkin_data', $posted_data);
            $customer_id_no = $posted_data['customer_id'];
            $from_branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $posted_data['from_branch']));
            $to_branch_details = $this->page->getSingle('branch', array('oracle_reference_number' => $posted_data['to_branch']));
            $car_details = $this->page->getSingle('car_model', array('oracle_reference_number' => $posted_data['car_model_oracle_number'], 'year' => $posted_data['model_year']));
            if ($from_branch_details == false || $to_branch_details == false || $car_details == false) {
                /* $response['status'] = 0;
                 $response['message'] = 'Your posted data is not right,please check it again.';
                 $response['data'] = array();
                 echo json_encode($response);*/
                echo 0;
                exit();
            }
            // 1- if customer is already in our website then add the booking we are getting from api and create a booking for him
            $customerExistingWithIdNo = $this->page->getSingle('individual_customer', array('id_no' => $customer_id_no));
            if ($customerExistingWithIdNo && $customerExistingWithIdNo->uid > 0) {
                $customerAlreadyExists = true;
            } else {
                $customerAlreadyExists = false;
            }
            // they will provide an api to send them this customer's information, we may set that here.
            // 5- we will send sms right away when we get the information from client, not by setting up the time from backend.
            // If customer is already registered then we are going to send him an sms for signup.
            if ($customerAlreadyExists) {
                $this->sendConfirmationSmsForWalkinSignup($walkin_id);
            } else {
                $this->sendRegisterSmsForWalkinSignup($walkin_id);
            }
            /*$response['status'] =1;
            $response['message'] = 'Walkin api hit successfully.';
            $response['data'] = array();
            echo json_encode($response);*/
            echo 1;
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    private function sendRegisterSmsForWalkinSignup($walkin_id)
    {
        $site = custom::site_settings();
        $walkin_discount_percent = $site->walkin_discount_percent;
        $walkin_detail = $this->page->getSingle('walkin_data', array('id' => $walkin_id));
        $customer_mobile_no = $walkin_detail->customer_mobile;
        if ($customer_mobile_no != '' && $customer_mobile_no != null) {
            // send him sms with registration link
            if ($this->lang == 'eng') {
                $sms_text = 'Thank you for booking with ' . custom::getSiteName($this->lang) . ', transfer your booking to website and get ' . $walkin_discount_percent . '% discount ';
                $sms_text .= 'by clicking on the below link. ' . "\n";
            } else {
                $sms_text = " للإستفادة من خصم $walkin_discount_percent% ابتداءا من اليوم الثاني يمكنك تحويل حجزك إلى حجز عن طريق الموقع بالضغط على الرابط التالي  ";
            }
            $sms_text .= $this->lang_base_url . '/walkin-signup/?r=' . $walkin_id;
            custom::sendSMS($customer_mobile_no, $sms_text);
        }
    }

    private function sendConfirmationSmsForWalkinSignup($walkin_id)
    {
        $site = custom::site_settings();
        $walkin_discount_percent = $site->walkin_discount_percent;
        $walkin_detail = $this->page->getSingle('walkin_data', array('id' => $walkin_id));
        $customer_mobile_no = $walkin_detail->customer_mobile;
        if ($customer_mobile_no != '' && $customer_mobile_no != null) {
            // send him sms with registration link
            if ($this->lang == 'eng') {
                $sms_text = 'Thank you for your reservation with ' . custom::getSiteName($this->lang) . '. You can register with us to avail ' . $walkin_discount_percent . '% discount.';
                $sms_text .= 'Click the below link to move your booking to website. ' . "\n";
            } else {
                $sms_text = " للإستفادة من خصم $walkin_discount_percent% ابتداءا من اليوم الثاني يمكنك تحويل حجزك إلى حجز عن طريق الموقع بالضغط على الرابط التالي  ";
            }
            $sms_text .= $this->lang_base_url . '/confirm-booking/?r=' . $walkin_id;
            custom::sendSMS($customer_mobile_no, $sms_text);
        }
    }

    public function walkin_customer_signup()
    {
        try {
            $walkin_id = $_REQUEST['r'];
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $walkin_data = $this->page->getSingle('walkin_data', array('id' => $walkin_id));
            if ($walkin_data) {
                $customerId = $walkin_data->customer_id;
                /*here check if user is already registered and hitting this link again then show message to him*/
                $isExist = $this->page->getSingle('individual_customer', array('id_no' => $customerId));
                if ($isExist && $isExist->uid != 0) {
                    echo "This link has been expired.";
                    exit();
                }
            } else {
                echo "This link has been expired.";
                exit();
            }
            $user_detail['id_type'] = $walkin_data->customer_id_type;
            $user_detail['id_no'] = $walkin_data->customer_id;
            $user_detail['first_name'] = $walkin_data->customer_first_name;
            $user_detail['last_name'] = $walkin_data->customer_last_name;
            $user_detail['mobile_no'] = "+" . $walkin_data->customer_mobile;
            $user_detail['gender'] = $walkin_data->gender;
            $user_detail['license_no'] = $walkin_data->customer_license_number;
            $user_detail['email'] = "";
            $data['user_data'] = (object)$user_detail;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            $data['nationalities'] = $this->page->getAllNationalities($this->lang);
            $data['countries'] = $this->page->getAllCountries($this->lang);
            $data['job_titles'] = $this->page->getAll('job_title', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['site_settings'] = custom::site_settings();
            $data['id_types'] = $this->page->getAll('customer_id_types', 'sort_col');
            $data['license_id_types'] = $this->page->getAll('driving_license_id_types', ($this->lang == 'eng' ? 'eng_title' : 'arb_title'));
            $data['send_extra_sms'] = true; // using this in view
            $data['walkin_id'] = $walkin_id; // using this in view
            return view('frontend/create_login_step_2', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function confirm_walkin_booking()
    {
        try {
            $walkin_id = $_REQUEST['r'];
            $data['site'] = custom::site_settings();
            $data['walkin_discount_percent'] = $data['site']->walkin_discount_percent;
            $data['social'] = custom::social_links();
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['walkin_data'] = $this->page->getSingle('walkin_data', array('id' => $walkin_id)); // using this in view
            /*here check if user booking move temp to booking table or not*/
            if (!$data['walkin_data']) {
                echo "This link has been expired.";
                exit();
            }
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'loyalty';
            return view('frontend/confirm_walkin_booking', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveWalkinBookingOnConfirm()
    {
        try {
            $walkin_id = $_REQUEST['walkin_id'];
            $full_name = $_REQUEST['full_name'];
            $posted_mobile_no = $_REQUEST['mobile_no'];
            $this->createWalkin($walkin_id, $full_name, $posted_mobile_no);
            return redirect($this->lang_base_url . '/home');
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
        /*echo "Your booking is confirmed, than kyou for taking your time.";
        exit;*/
    }

    private function MaxRedeemablePoints($customer_redeem_loyalty_type)
    {
        $points = array();
        $amount_sar = array();
        $remaining_amount = array();
        $used_amount = array();
        $used_points = array();
        $site = custom::site_settings();
        $session_data = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days_for_checkout = $session_data['days'];
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
        $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
        $redeemOffer = $this->page->checkIfRedeemAllowed($session_data['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $session_data['pickup_date']);
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

    private function MaxRedeemableAmount()
    {
        $site = custom::site_settings();
        $rent_per_day = Session::get('rent_per_day');
        $session_data = Session::get('search_data');
        $days_for_checkout = $session_data['days'];
        $allowed_days_for_redeem = $site->days_for_redeem;
        /*  Validation how much riyal can be redeemed in a single checkout.
        % of riyal he can use during checkout (e.g 30%)  but first checking general number of days setting.*/
        if ($days_for_checkout > $allowed_days_for_redeem) {
            $days_to_calculate_and_check = $allowed_days_for_redeem;
        } else {
            $days_to_calculate_and_check = $days_for_checkout;
        }
        $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
        $redeemOffer = $this->page->checkIfRedeemAllowed($session_data['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $session_data['pickup_date']);
        if ($redeemOffer->type_of_redeem == 'Percentage') {
            $redeem_applicable_amount = $rent_per_day * $days_to_calculate_and_check;
            $percentage_of_amount_usable = $redeemOffer->percentage_of_points_usable; // basically percentage of riyal usable.
            $amount_redeemable = ($percentage_of_amount_usable / 100) * $redeem_applicable_amount;
        } else {
            $amount_redeemable = $redeemOffer->percentage_of_points_usable * $days_to_calculate_and_check; // Redeem's percentage_of_points_usable is handled as rent per day
        }
        return $amount_redeemable;
    }

    private function add_or_deduct_loyalty_points_for_customer($booking_id, $add_or_deduct = 'add')
    {
        $booking_detail = $this->page->getSingle("booking", array("id" => $booking_id));
        if ($booking_detail->type == "individual_customer" || $booking_detail->type == "guest")// it will not work for corporate customer
        {
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
    }

    private function convertRedeemPointsToAmount($customer_redeem_loyalty_type, $points_to_redeem)
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
        $session_data = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days_for_checkout = $session_data['days'];
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
        $car_info_for_redeem = $this->page->getSingle('car_model', array('id' => Session::get('car_id')));
        $redeemOffer = $this->page->checkIfRedeemAllowed($session_data['from_region_id'], $car_info_for_redeem->car_type_id, Session::get('car_id'), $session_data['pickup_date']);
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

    private function runBookingsSyncCronjob($booking_id)
    {
        $cronjob_url = custom::baseurl('/') . '/cronjob/setDataCronJob?walkin_api=1&bid=' . $booking_id;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $cronjob_url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    /*this is for sending custom sms by clicking the url directly from bwser*/
    /*public function send_custom_sms()
    {
        try {
            if (isset($_REQUEST['mobile']) && isset($_REQUEST['text'])) {
                custom::sendCostumSms($_REQUEST['mobile'], $_REQUEST['text']);
            } else {
                echo 0;
            }
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }*/

    /*Ahsan*/
    public function getSpecialCarDesc(Request $request)
    {
        try {
            $response = array();
            $car_id['id'] = $request->input('car_id');

            $stdObj = $this->page->getSingle('car_model', $car_id);
            if ($this->lang == "eng") {
                $desc = $stdObj->eng_special_car_desc;
            } else {
                $desc = $stdObj->arb_special_car_desc;
            }
            $response['status'] = true;
            $response['desc'] = $desc;
            echo json_encode($response);
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    /*Ahsan*/

    /*
     * This function is only for mobile view and this manage booking page is not for main website
     */

    public function manageBookings()
    {
        try {
            $data = array();
            $data['home_content'] = (array)$this->page->getSingle('home', array('id' => '1'));
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'manage_booking';
            return view('frontend/mobile/manage_booking', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function getBranchesByCity(Request $request)
    {

        try {
            $branches_html = "";
            $sessionVals = Session::get('search_data');
            $city_id = $request->input('city_id');
            $is_delivery_mode = $request->input('is_delivery_mode');
            $branches = $this->page->getBranchesByCity($city_id, $is_delivery_mode);

            $branches_html .= '<option value="">' . ($this->lang == 'eng' ? 'Select Branch ' : 'اختار فرع') . '</option>';
            foreach ($branches as $branch):
                $get_sess_branch_id = isset($sessionVals['from_branch_id']) ? $sessionVals['from_branch_id'] : '';
                if ($branch->id == $get_sess_branch_id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $branches_html .= '<option value="' . $branch->id . '" ' . $selected . '>' . ($this->lang == 'eng' ? $branch->eng_title : $branch->arb_title) . '</option>';
            endforeach;

            echo $branches_html;
            exit();
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    /*New APIs 06-08-2019*/

    public function setOasisCustomer()
    {
        $booking = new Booking();
        try {
            $data['id_no'] = $_REQUEST['id_number'];
            $data['id_type'] = $_REQUEST['id_type'];
            $data['first_name'] = $_REQUEST['first_name'];
            $data['last_name'] = $_REQUEST['last_name'];
            $data['email'] = $_REQUEST['email'];
            $data['mobile_no'] = $_REQUEST['mobile'];
            $data['nationality'] = $_REQUEST['nationality'];
            $data['dob'] = date("Y-m-d", strtotime($_REQUEST['date_of_birth']));
            $data['id_expiry_date'] = date("Y-m-d", strtotime($_REQUEST['id_expiry_date']));
            $data['id_date_type'] = $_REQUEST['id_date_type'];
            $data['id_version'] = $_REQUEST['id_copy'];
            $data['id_country'] = $_REQUEST['id_country'];
            $data['license_no'] = $_REQUEST['dl_id_number'];
            $data['license_id_type'] = $_REQUEST['dl_id_type'];
            $data['license_expiry_date'] = date("Y-m-d", strtotime($_REQUEST['dl_expiry']));
            $data['license_country'] = $_REQUEST['dl_country'];
            $data['job_title'] = $_REQUEST['job_title'];
            $data['sponsor'] = $_REQUEST['sponsor'];
            $data['district_address'] = $_REQUEST['address_district'];
            $data['street_address'] = $_REQUEST['address_street'];
            if ($_REQUEST['loyalty_class'] == 0) $_REQUEST['loyalty_class'] = 'Bronze';
            if ($_REQUEST['loyalty_class'] == 1) $_REQUEST['loyalty_class'] = 'Silver';
            if ($_REQUEST['loyalty_class'] == 2) $_REQUEST['loyalty_class'] = 'Golden';
            if ($_REQUEST['loyalty_class'] == 3) $_REQUEST['loyalty_class'] = 'Platinum';
            $data['loyalty_card_type'] = $_REQUEST['loyalty_class'];
            $data['loyalty_points'] = $_REQUEST['loyalty_points'];
            $data['black_listed'] = ($_REQUEST['black_listed'] != '' ? $_REQUEST['black_listed'] : '');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            //$is_valid_id = custom::validateSaudiID($_REQUEST['id_number'], 'eng');
            if (isset($_REQUEST['id_number'])) {
                $isIDExist = $booking->getSingleCustomerData(array('id_no' => $_REQUEST['id_number']));
                if ($isIDExist) {
                    if ($data['email'] == '' || is_null($data['email'])) {
                        unset($data['email']);
                    }
                    $update_by['id_no'] = $_REQUEST['id_number'];
                    $booking->updateCustomerData($data, $update_by);
                    $message = 'Customer is updated successfully';
                } else {
                    $booking->saveCustomerData($data);
                    $message = 'Customer is added successfully';
                }
                $status = 1;
                $response['status'] = 'true';
                $response['message'] = $message;
            } else {
                $status = 0;
                $response['status'] = 'Error';
                $response['message'] = 'Customer id is invalid';
            }

            return $status;

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function update_customer()
    {
        $booking = new Booking();
        try {
            $data['id_no'] = $_REQUEST['id_number'];

            if ($_REQUEST['loyalty_class'] == 0) $_REQUEST['loyalty_class'] = 'Bronze';
            if ($_REQUEST['loyalty_class'] == 1) $_REQUEST['loyalty_class'] = 'Silver';
            if ($_REQUEST['loyalty_class'] == 2) $_REQUEST['loyalty_class'] = 'Golden';
            if ($_REQUEST['loyalty_class'] == 3) $_REQUEST['loyalty_class'] = 'Platinum';
            $data['loyalty_card_type'] = $_REQUEST['loyalty_class'];

            $data['loyalty_points'] = $_REQUEST['loyalty_points'];
            $data['black_listed'] = $_REQUEST['black_list'];
            $data['simah_block'] = $_REQUEST['simah_block'];
            $isIDExist = $booking->getSingleCustomerData(array('id_no' => $_REQUEST['id_number']));
            if ($isIDExist) {
                $update_by['id_no'] = $_REQUEST['id_number'];
                $booking->updateCustomerData($data, $update_by);
                $status = 1;
            } else {
                $status = 0;
            }

            return $status;

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function booking_availability_api()
    {
        try {
            $update_by['id'] = $_REQUEST['id']; // booking_availability table primary key
            $record = $this->page->getSingle('booking_availability', array('id' => $update_by['id']));
            if ($record) {
                $update['from_date'] = date('Y-m-d', strtotime($_REQUEST['from'])); // booking_availability table from_date
                $update['to_date'] = (isset($_REQUEST['to']) && $_REQUEST['to'] != '' ? date('Y-m-d', strtotime($_REQUEST['to'])) : NULL);
                $update['booking_per_day'] = (int)$_REQUEST['set']; // booking_availability table booking_per_day
                $update['updated_at'] = date('Y-m-d H:i:s');
                $this->page->updateData('booking_availability', $update, $update_by);
                echo 1;
                exit();
            } else {
                echo 0;
                exit();
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function testPDF()
    {
        try {
            $data = array();
            $filename = rand() . '-DummyPDF.pdf';
            $template = 'frontend.emails.dummy_pdf';
            $this->pdf->loadView($template, $data)
                ->setPaper('a4')
                ->setOrientation('portrait')
                ->setOption('margin-bottom', 0)
                ->save('public/pdf/dummy/' . $filename);
            return $filename;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function qitafSendOTP(Request $request)
    {
        $response = array();
        $api_settings = custom::api_settings();
        $search_data = session()->get('search_data');
        $branch_info = $this->page->getSingle('branch', array('id' => $search_data['from_branch_id']));
        $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/GenerateOtp?mobile=' . $request->qitaf_mobile_number . '&branch=' . $branch_info->oracle_reference_number;
        $this->logQitafResponse($api_url, 'GenerateOtpRequest');
        $curlResponse = $this->sendCurlRequest($api_url);
        $this->logQitafResponse($curlResponse, 'GenerateOtpResponse');
        $response['status'] = (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false);
        $response['message'] = $this->qitaf_error($curlResponse);
        echo json_encode($response);
        die();
    }

    public function qitafRedeem(Request $request)
    {
        $response = array();
        $sessionVals = Session::get('search_data');
        $api_settings = custom::api_settings();
        $search_data = session()->get('search_data');
        $branch_info = $this->page->getSingle('branch', array('id' => $search_data['from_branch_id']));
        $payable_amount_after_qitaf_deducted = $this->checkIfQitafRedeemable($request->qitaf_amount);
        $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/OnlineRedeemPoints?mobile=' . $request->qitaf_mobile_number . '&branch=' . $branch_info->oracle_reference_number . '&otp=' . $request->qitaf_otp . '&points=' . $request->qitaf_amount;
        $this->logQitafResponse($api_url, 'OnlineRedeemPointsRequest');
        $curlResponse = $this->sendCurlRequest($api_url);
        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            $curlResponse .= ',' . $request->qitaf_mobile_number . ',' . $branch_info->oracle_reference_number;

            /*$curlResponse = explode(',', $curlResponse);
            $curlResponse[] = $request->qitaf_mobile_number;
            $curlResponse[] = $branch_info->oracle_reference_number;
            $curlResponse = implode(',', $curlResponse);*/

            $curlResponse = str_replace('.', ',', $curlResponse);
            $this->logQitafResponse($curlResponse, 'OnlineRedeemPointsResponse');
            Session::put('qitaf_request', $curlResponse);
            Session::put('qitaf_amount', $request->qitaf_amount);
            Session::save();

            // saving the data in database so that we can use it to redeem back the qitaf amount if booking not completed
            $this->page->saveData('qitaf_logs', array('status' => 'New', 'qitaf_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            // $this->callQitafReverseRedeemAPI($curlResponse); #todo: we will remove it once we move it to live
            $response['status'] = true;
            $response['message'] = $curlResponse;
            $response['amount_remaining'] = $payable_amount_after_qitaf_deducted > 0;
            $response['total_payable_amount_after_qitaf'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($payable_amount_after_qitaf_deducted, 2) . ' ' . Lang::get('labels.currency');
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.qitaf_partial_redeem_text_to_show'), $request->qitaf_amount, round($payable_amount_after_qitaf_deducted, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.qitaf_full_redeem_text_to_show'), $request->qitaf_amount);
            }
            echo json_encode($response);
            die();
        } else {
            $this->logQitafResponse($curlResponse, 'OnlineRedeemPointsResponse');
            $response['status'] = false;
            $response['message'] = $this->qitaf_error($curlResponse);
            echo json_encode($response);
            die();
        }
    }

    private function checkIfQitafRedeemable($qitaf_amount)
    {
        $site_settings = custom::site_settings();
        $sessionVals = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days = $sessionVals['days'];

        if ($sessionVals['is_delivery_mode'] == 4) {
            $sessionVals['days'] = 30; // because 1 month is to be charged
            $days = $sessionVals['days'];
        }

        $cdw_charges = Session::get('cdw_charges');
        $cdw_charges_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

        $cdw_plus_charges = Session::get('cdw_plus_charges');
        $cdw_plus_charges_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

        $gps_charges = Session::get('gps_charges');
        $gps_charges_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

        $extra_driver_charges = Session::get('extra_driver_charges');
        $extra_driver_charges_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

        $baby_seat_charges = Session::get('baby_seat_charges');
        $baby_seat_charges_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

        if ($cdw_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_multiply_factor = 1;
        } else {
            $cdw_multiply_factor = $days;
        }

        if ($cdw_plus_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_plus_multiply_factor = 1;
        } else {
            $cdw_plus_multiply_factor = $days;
        }

        if ($gps_charges_is_one_time_applicable_on_booking == 1) {
            $gps_multiply_factor = 1;
        } else {
            $gps_multiply_factor = $days;
        }

        if ($extra_driver_charges_is_one_time_applicable_on_booking == 1) {
            $extra_driver_multiply_factor = 1;
        } else {
            $extra_driver_multiply_factor = $days;
        }

        if ($baby_seat_charges_is_one_time_applicable_on_booking == 1) {
            $baby_seat_multiply_factor = 1;
        } else {
            $baby_seat_multiply_factor = $days;
        }

        $dropoff_charges_amount = Session::get('dropoff_charges_amount');
        $delivery_charges = ($sessionVals['delivery_charges'] ? $sessionVals['delivery_charges'] : 0);
        $parking_fee = $sessionVals['parking_fee'];
        $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];

        $total_amount_without_vat = ($rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
        $vat_applicable = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
        $total_amount_with_vat = $total_amount_without_vat + $vat_applicable;

        $max_amount_redeemable_with_qitaf = round(($site_settings->amount_to_be_redeemed_by_qitaf_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($qitaf_amount > $max_amount_redeemable_with_qitaf) {
            $response['status'] = false;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_qitaf);
            echo json_encode($response);
            die();
        }

        // below commented code is when we needed to minus qitaf first than calculate vat, but Kholoud asked us to not do this.
        // $total_amount_without_vat_after_qitaf = $total_amount_without_vat - $qitaf_amount;
        // $vat_applicable = (Session::get('vat_percentage') / 100) * $total_amount_without_vat_after_qitaf;
        // $total_amount_with_vat_after_qitaf = $total_amount_without_vat_after_qitaf + $vat_applicable;

        // return $total_amount_with_vat_after_qitaf;

        return $total_amount_with_vat - $qitaf_amount;
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

    public function reverse_qitaf_for_temp_cancelled_expired_bookings_cronjob()
    {
        $reversed_count = 0;
        $problematic_rows = [];

        // reversing qitaf temp table logs
        $qitaf_logs = $this->page->getAllPendingRedeemRequests();
        if ($qitaf_logs) {
            foreach ($qitaf_logs as $log) {
                $res = $this->callQitafReverseRedeemAPI(str_replace('.', ',', $log->qitaf_request));
                if ($res && strpos($res, 'Error') === false && strpos(strip_tags($res), 'Origin Down') === false) {
                    $this->page->updateData('qitaf_logs', ['status' => 'Reversed'], array('qitaf_request' => str_replace('.', ',', $log->qitaf_request)));
                    $reversed_count++;
                } else {
                    $problematic_rows[] = $log->id;
                }
            }
        }


        // reversing qitaf for cancelled/expired bookings
        $booking_date = '2021-01-08 00:00:00'; // we will only check bookings which were placed after this datetime
        $query = DB::table('booking')
            ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
            ->where('booking.sync', '!=', 'N')
            ->where('booking_payment.qitaf_request', '!=', '')
            ->where('booking_payment.is_qitaf_reversed', 0)
            ->where('booking.created_at', '>=', $booking_date)
            ->where(function ($query) {
                $query->where('booking.booking_status', 'Cancelled');
                $query->orWhere('booking.booking_status', 'Expired');
            })
            ->select('booking.id', 'booking_payment.qitaf_request');
        $bookings = $query->get();
        foreach ($bookings as $booking) {
            $res = $this->callQitafReverseRedeemAPI(str_replace('.', ',', $booking->qitaf_request));
            if ($res && strpos($res, 'Error') === false && strpos(strip_tags($res), 'Origin Down') === false) {
                $this->page->updateData('booking_payment', ['is_qitaf_reversed' => 1], ['booking_id' => $booking->id]);
                $this->page->updateData('qitaf_logs', ['status' => 'Reversed after booking'], ['qitaf_request' => $booking->qitaf_request]);
                $reversed_count++;
            } else {
                $log = $this->page->getSingle('qitaf_logs', ['qitaf_request' => $booking->qitaf_request]);
                if ($log) {
                    $problematic_rows[] = $log->id;
                }
            }
        }

        if ($reversed_count > 0) {
            $message = 'Total ' . $reversed_count . ' transactions reversed successfully.';
            if (!empty($problematic_rows)) {
                $message .= ' - Problematic Rows: ' . implode(', ', $problematic_rows);
            }
        } elseif ($reversed_count == 0 && !empty($problematic_rows)) {
            $message = 'Nothing reversed. - Problematic Rows: ' . implode(', ', $problematic_rows);
        } else {
            $message = 'No transactions to reverse.';
        }

        echo $message;
        exit();
    }

    private function autoReverseQitafRedeem($booking_id)
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        $this->callQitafReverseRedeemAPI(str_replace('.', ',', $booking_payment_detail->qitaf_request));
    }

    private function callQitafReverseRedeemAPI($data)
    {
        try {
            if ($data != "") {
                $data = str_replace('.', ',', $data);
                $api_settings = custom::api_settings();
                $data = explode(',', $data);
                $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/ReverseRedeem?mobile=' . ltrim($data[2], '0') . '&branch=' . $data[3] . '&request_id=' . $data[1] . '&request_date=' . $data[0];
                $this->logQitafResponse($api_url, 'ReverseRedeemRequest');
                $curlResponse = $this->sendCurlRequest($api_url);
                $this->logQitafResponse($curlResponse, 'ReverseRedeemResponse');
                return $curlResponse;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear values from qitaf_logs temp table after booking confirmed.
     * @param $booking_id
     */
    private function clearQitafAfterBookingConfirmed($booking_id, $updated_from = '')
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        if ($booking_payment_detail && $booking_payment_detail->qitaf_request != "") {
            $this->page->updateData('qitaf_logs', ['status' => 'Copied', 'updated_from' => $updated_from], array('qitaf_request' => str_replace('.', ',', $booking_payment_detail->qitaf_request)));
        }
    }

    private function qitaf_error($string)
    {
        if ($string != strip_tags($string)) {
            return trans('labels.qitaf_input_not_fine');
        } else {
            return $string;
        }
    }

    public function check_fcm_token_status_cronjob()
    {
        $total = $active = $expired = 0;
        $api_settings = custom::api_settings();
        $api_key = $api_settings->fcm_key;
        $current_date = date("Y-m-d");
        $past_date_to_check = date("Y-m-d", strtotime("-3 months"));
        $query = DB::table('device_token')->whereRaw("(token_status = 'Not Checked' OR token_status_checked_date <= '" . $past_date_to_check . "')")->limit(1000);
        // custom::enable_query_log();
        $records = $query->get();
        // custom::get_query_log();
        //custom::dump($records);
        if (count($records) > 0) {
            foreach ($records as $record) {
                $response = $this->check_fcm_token_status($record->fcm_token, $api_key);
                if (isset($response['error'])) {
                    $this->page->updateData('device_token', array('token_status' => 'Expired', 'token_status_checked_date' => $current_date), array('id' => $record->id));
                    $expired++;
                } elseif (isset($response['authorizedEntity'])) {
                    $this->page->updateData('device_token', array('token_status' => 'Active', 'token_status_checked_date' => $current_date), array('id' => $record->id));
                    $active++;
                }
                $total++;
            }
        }

        echo 'Cronjob run successfully. Total records: ' . $total . " - Active: " . $active . " - Expired: " . $expired;
        exit();
    }

    private function check_fcm_token_status($fcm_token, $api_key)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://iid.googleapis.com/iid/info/" . $fcm_token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: key=" . $api_key,
                "Content-Type: application/json",
                "details: true"
            ),
        ));

        $result = curl_exec($curl);
        $result = json_decode($result, TRUE);
        curl_close($curl);
        return $result;
    }

    /**
     * @param $booking_id
     * this function is being used to generate checkout id needed for hyper pay first step "Create Checkout"
     * @return mixed
     */
    private function hp_generate_checkout_id($booking_id, $isMada = 0, $extend = false, $amount = false, $number_of_days = false)
    {
        $hp_response = custom::hp_generate_checkout_id_api($booking_id, $isMada, $extend, $amount, $number_of_days);
        if (isset($hp_response['id'])) {
            $response['status'] = true;
            $response['message'] = $hp_response['result']['description'];
            $response['checkout_id'] = $hp_response['id'];
            $response['hp_response'] = $hp_response;
            return $response;
        } else {
            $response['status'] = false;
            $response['message'] = sprintf(trans('labels.hyper_pay_payment_error'), ucfirst($hp_response['result']['description']));
            $response['hp_response'] = $hp_response;
            return $response;
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

        $hp_response = custom::hp_check_payment_status_api($request->resourcePath, Session::get('payment_form_data')['isMada'], Session::get('booking_id'));

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
                    $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
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
            return redirect($this->lang_base_url . '/booked');
        } else {
            session()->put('hyper_pay_transaction_error', sprintf(trans('labels.hyper_pay_payment_error'), trans('labels.transaction_failed')));
            return redirect($this->lang_base_url . '/payment');
        }
    }

    public function hp_ipn(Request $request)
    {
        try {

            // Generate checkout ID response (generate_checkout_id_response)
            // {"result":{"code":"000.200.100","description":"successfully created checkout"},"buildNumber":"8502c74f571e6ad85c4b6f3a0e0bd637c74fb451@2022-05-25 13:36:55 +0000","timestamp":"2022-05-26 14:15:15+0000","ndc":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","id":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","action_performed_at":"2022-05-26 17:15:15"}

            // Check payment status request
            // {"resource_path":"\/v1\/checkouts\/8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01\/payment","entity_id":"8ac7a4c774c45b1c0174cf9271df0ccb","action_performed_at":"2022-05-26 17:16:30"}

            // IPN Request
            // {"id":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","resourcePath":"\/v1\/checkouts\/8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01\/payment","entity_id":"8ac7a4c774c45b1c0174cf9271df0ccb","action_performed_at":"2022-05-26 17:45:42"}

            $this->send_hyper_pay_debug_email("Hyper Pay IPN Hit", 'Hit at the top of the IPN function');


            // checking DB against resource path for entity id
            $data = explode('/', ltrim($request->resourcePath, '/'));
            $hyper_pay_log_for_entity_id_check = DB::table('booking_hyper_pay_log')->where('generate_checkout_id_response', 'like', '%' . $data[2] . '%')->first();

            if ($hyper_pay_log_for_entity_id_check && $hyper_pay_log_for_entity_id_check->generate_checkout_id_request) {
                $hp_generate_checkout_id_request_from_log = json_decode($hyper_pay_log_for_entity_id_check->generate_checkout_id_request, true);
                $entity_id = $hp_generate_checkout_id_request_from_log['entityId'];

                $api_setting = custom::api_settings();

                $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . $request->resourcePath;
                $url .= "?entityId=" . $entity_id;
                // echo $url;die;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $hp_response = curl_exec($ch);
                curl_close($ch);
                $hp_response = json_decode($hp_response, true);

                if ($request->has('debug')) {
                    custom::dump($hp_response);
                }

                // sending debugging email
                $mail_res['received_request_params'] = $_REQUEST;
                $mail_res['received_response_params'] = $hp_response;
                $this->send_hyper_pay_debug_email("Hyper Pay IPN Hit", json_encode($mail_res));

                $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";

                /* if payment is successfully done then update hyperpay details in db against booking id and sending confirmation to custom/admin/agent */
                /* Start */
                if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {

                    // saving it inside of success case because there is no merchant transaction id in error case
                    $booking = DB::table('booking')->where('reservation_code', $hp_response['merchantTransactionId'])->first();

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
                        $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
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

                echo 'success';
                die;
            }

            echo 'Error: Entity ID not found.';
            die;

        } catch (Exception $ex) {
            $this->send_hyper_pay_debug_email("Hyper Pay IPN Exception Occurred", $ex->getMessage());
            echo 'Error: ' . $ex->getMessage();
            die;
        }
    }

    public function hp_check_payment_status_cronjob()
    {
        $success_count = 0;
        $api_setting = custom::api_settings();

        /* Below are for booking payment hyper pay logs */
        $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";
        $query = DB::table('booking_hyper_pay_log')->whereRaw("is_payment_status_inquired_successfully = 0 AND check_payment_status_response IS NOT NULL AND created_at <= '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'")->orderBy('id', 'DESC');
        $hp_logs = $query->get();
        foreach ($hp_logs as $hp_log) {

            $hp_generate_checkout_id_request_from_log = json_decode($hp_log->generate_checkout_id_request, true);
            $hp_check_payment_status_response_from_log = json_decode($hp_log->check_payment_status_response, true);

            if (isset($hp_check_payment_status_response_from_log['id']) && isset($hp_generate_checkout_id_request_from_log['entityId']) && !preg_match($successful_transaction_regex, $hp_check_payment_status_response_from_log['result']['code'])) {

                $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . "/v1/query/" . $hp_check_payment_status_response_from_log['id'];
                $url .= "?entityId=" . $hp_generate_checkout_id_request_from_log['entityId'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $hp_response = curl_exec($ch);
                curl_close($ch);
                $hp_response = json_decode($hp_response, true);

                /* if payment is successfully done then update hyperpay details in db against booking id and sending confirmation to custom/admin/agent */
                /* Start */
                if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {

                    $booking = DB::table('booking')->where('reservation_code', $hp_response['merchantTransactionId'])->first();

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
                        $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
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

                    $success_count++;
                }
                /* End */
                // custom::dump($hp_response);
            }
            // $this->page->deleteData('booking_hyper_pay_log', array('id' => $hp_log->id));
            $this->page->updateData('booking_hyper_pay_log', ['is_payment_status_inquired_successfully' => 1], ['id' => $hp_log->id]);
        }

        /* Below are for add payment hyper pay logs */
        $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";
        $query = DB::table('booking_added_payments_hyper_pay_log')->whereRaw("is_payment_status_inquired_successfully = 0 AND check_payment_status_response IS NOT NULL AND created_at <= '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'")->orderBy('id', 'DESC');
        $hp_logs = $query->get();
        foreach ($hp_logs as $hp_log) {

            $hp_generate_checkout_id_request_from_log = json_decode($hp_log->generate_checkout_id_request, true);
            $hp_check_payment_status_response_from_log = json_decode($hp_log->check_payment_status_response, true);

            if (isset($hp_check_payment_status_response_from_log['id']) && isset($hp_generate_checkout_id_request_from_log['entityId']) && !preg_match($successful_transaction_regex, $hp_check_payment_status_response_from_log['result']['code'])) {

                $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . "/v1/query/" . $hp_check_payment_status_response_from_log['id'];
                $url .= "?entityId=" . $hp_generate_checkout_id_request_from_log['entityId'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $hp_response = curl_exec($ch);
                curl_close($ch);
                $hp_response = json_decode($hp_response, true);

                /* if payment is successfully done then update hyperpay details in db against booking id and sending confirmation to custom/admin/agent */
                /* Start */
                if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {

                    $booking_id = explode('E', $hp_log->booking_id)[0];
                    $booking = $this->page->getSingle('booking', ['id' => $booking_id]);

                    if ($booking->booking_source == 'website') {
                        $payment_source = 'Website';
                    } elseif ($booking->booking_source == 'mobile') {
                        $payment_source = 'Mobile Website';
                    } else {
                        $payment_source = ucfirst($booking->booking_source);
                    }


                    $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

                    $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
                    $booking_add_payment['extended_days'] = $hp_generate_checkout_id_request_from_log['extended_days'];
                    $booking_add_payment['payment_company'] = 'HP';
                    $booking_add_payment['payment_method'] = $this->hp_payment_method($hp_response['paymentBrand']);
                    $booking_add_payment['transaction_reference'] = $hp_response['id'];
                    $booking_add_payment['card_number'] = (isset($hp_response['card']) ? $hp_response['card']['bin'] : '') . '********' . (isset($hp_response['card']) ? $hp_response['card']['last4Digits'] : '');
                    $booking_add_payment['amount'] = $hp_generate_checkout_id_request_from_log['amount'];
                    $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
                    $booking_add_payment['payment_source'] = $payment_source;
                    $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
                    $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
                    $id = $this->page->saveData('booking_added_payments', $booking_add_payment);

                    // sending confirmation SMS and email to customer
                    $this->send_add_payment_confirmation_to_customer($id);

                    // syncing added payment data with OASIS
                    $this->sync_booking_added_payments_with_oasis($id);

                    $success_count++;
                }
                /* End */
                // custom::dump($hp_response);
            }
            // $this->page->deleteData('booking_added_payments_hyper_pay_log', array('id' => $hp_log->id));
            $this->page->updateData('booking_added_payments_hyper_pay_log', ['is_payment_status_inquired_successfully' => 1], ['id' => $hp_log->id]);
        }

        echo "Cronjob completed successfully. " . ($success_count > 0 ? "Total updated records: " . $success_count : "");
    }

    private function send_hyper_pay_debug_email($subject, $msg) // this functions is being used for debug emails
    {
        // send email
        $site = custom::site_settings();
        $smtp = custom::smtp_settings();

        $email['subject'] = $subject;

        $email['fromEmail'] = $smtp->username;
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = "bilal_ejaz@astutesol.com";
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = $this->lang_base_url;
        $content['name'] = "Bilal";
        $content['msg'] = $msg;
        $content['gender'] = "male";
        custom::sendEmail('general', $content, $email, $this->lang);
    }

    private function hb_authentication()
    {
        $api_settings = custom::api_settings();
        $auth_fields['email'] = $api_settings->hyper_bill_username;
        $auth_fields['password'] = $api_settings->hyper_bill_password;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim($api_settings->hyper_bill_endpoint_url, '/') . "/api/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth_fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        $hb_response = curl_exec($ch);
        curl_close($ch);
        $hb_response = json_decode($hb_response, true);
        return $hb_response;
    }

    private function hb_generate_invoice($access_token, $amount, $name, $email, $phone, $merchant_invoice_number, $expiration_date, $booking_reference_number)
    {
        $api_settings = custom::api_settings();
        $invoice_data['amount'] = $amount;
        $invoice_data['name'] = $name;
        $invoice_data['email'] = $email;
        $invoice_data['phone'] = $phone;
        $invoice_data['merchant_invoice_number'] = $merchant_invoice_number;
        $invoice_data['expiration_date'] = $expiration_date;
        $invoice_data['booking_reference_number'] = $booking_reference_number;

        $invoice_data['currency'] = "SAR";
        $invoice_data['payment_type'] = "DB";
        $invoice_data['lang'] = ($this->lang == 'eng' ? 'en' : 'ar');

        $invoice_data['email_template'] = "test_email_template";
        $invoice_data['sms_template'] = "test_sms_template";
        // $invoice_data['sms_template'] = "";
        $invoice_data['invoice_template'] = "test_invoice_template";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim($api_settings->hyper_bill_endpoint_url, '/') . "/api/simpleInvoice");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($invoice_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        ));
        $hb_response = curl_exec($ch);
        curl_close($ch);
        $hb_response = json_decode($hb_response, true);
        return $hb_response;
    }

    private function hb_send_invoice($access_token, $invoice_number)
    {
        $api_settings = custom::api_settings();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim($api_settings->hyper_bill_endpoint_url, '/') . "/api/simpleInvoice/send/" . $invoice_number);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        ));
        $hb_response = curl_exec($ch);
        curl_close($ch);
        $hb_response = json_decode($hb_response, true);
        return $hb_response;
    }

    private function hb_get_error_string($errors)
    {
        try {
            $errors_arr = [];
            foreach ($errors as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $errors_arr[] = $v;
                    }
                } else {
                    $errors_arr[] = $val;
                }
            }
            return implode(', ', $errors_arr);
        } catch (\Exception $e) {
            return "";
        }
    }

    private function hb_generate_invoice_number($booking_id, $resend = false)
    {
        $hb_invoice_id = 'KEY' . str_pad($booking_id, 17, '0', STR_PAD_LEFT);
        if ($resend) {
            $invoiceInfo = $this->page->getSingle('booking_corporate_invoice', array('booking_id' => $booking_id));
            $attempts = str_pad($invoiceInfo->attempts + 1, 3, "0", STR_PAD_LEFT);
            $hb_invoice_id = 'KEY' . $attempts . str_pad($booking_id, 14, '0', STR_PAD_LEFT);
        }
        return $hb_invoice_id;
    }

    public function hb_check_invoice_status_cronjob()
    {
        try {
            set_time_limit(0);

            $hb_auth_response = $this->hb_authentication();
            if ($hb_auth_response['status']) {
                $hb_access_token = $hb_auth_response['data']['accessToken'];
                $booking_corporate_invoices = $this->page->getMultipleRows('booking_corporate_invoice', array('cc_company' => 'hyper_pay', 'payment_status' => 'pending'), 'booking_id', 'desc');
                foreach ($booking_corporate_invoices as $booking_corporate_invoice) {

                    // increasing attempts
                    $attempts = $booking_corporate_invoice->attempts + 1;
                    $this->page->updateData('booking_corporate_invoice', ['attempts' => $attempts], array('booking_id' => $booking_corporate_invoice->booking_id));

                    $hb_check_invoice_status_response = $this->hb_check_invoice_status($hb_access_token, $booking_corporate_invoice->invoice_id);
                    if ($hb_check_invoice_status_response['status'] && $hb_check_invoice_status_response['data']['status'] == 'paid') {

                        $hb_booking = $this->page->getSingle('booking', array('id' => $booking_corporate_invoice->booking_id));

                        $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $booking_corporate_invoice->booking_id));
                        $driver_id = $booking_corporate_customer->driver_id;
                        $corporate_driver = $this->page->getSingle("corporate_driver", array("id" => $driver_id));
                        $driver_mobile_no = $corporate_driver->mobile_no;

                        $booking_invoice_update['payment_status'] = 'paid';
                        $booking_invoice_update['continue_inquiry'] = '0';
                        $booking_invoice_update['transaction_id'] = $hb_check_invoice_status_response['data']['trn_details']['payload']['merchantTransactionId'];
                        $booking_invoice_update['invoice_id'] = $hb_check_invoice_status_response['data']['invoice_no'];
                        $booking_invoice_update['last_4_digits'] = $hb_check_invoice_status_response['data']['trn_details']['payload']['card']['last4Digits'];
                        $booking_invoice_update['card_brand'] = $hb_check_invoice_status_response['data']['trn_details']['payload']['paymentBrand'];
                        $booking_invoice_update['transaction_date'] = date('Y-m-d H:i:s');
                        $this->page->updateData('booking_corporate_invoice', $booking_invoice_update, array('booking_id' => $booking_corporate_invoice->booking_id));

                        // $this->sendToKeyAdmin($booking_corporate_invoice->booking_id);
                        // $this->sendToCorporateUser($booking_corporate_invoice->booking_id);
                        // $this->sendToDriver($booking_corporate_invoice->booking_id);

                        // send sms to driver
                        $this->sendCorporateBookingSms($hb_booking->reservation_code, $driver_mobile_no);

                        // code to send email and sms to branch agent starts here
                        if ($hb_booking->is_delivery_mode == "yes") {
                            $customer_name = $corporate_driver->first_name . ' ' . $corporate_driver->last_name;
                            $branch_info = $this->page->getSingle('branch', array('id' => $hb_booking->from_location));
                            // $this->send_email_to_branch_agent($booking_corporate_invoice->booking_id, $branch_info->email, $corporate_driver->first_name, $corporate_driver->last_name);
                            $this->send_sms_to_branch_agent($hb_booking->reservation_code, $branch_info->mobile, $customer_name);
                        }

                    } else {
                        if (time() > strtotime($booking_corporate_invoice->expiry)) {
                            $this->page->updateData('booking_corporate_invoice', ['continue_inquiry' => 0], array('booking_id' => $booking_corporate_invoice->booking_id));
                        }
                    }
                }
            }

            echo 'Cronjob completed successfully.';
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function hb_check_invoice_status($access_token, $invoice_number)
    {
        $api_settings = custom::api_settings();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim($api_settings->hyper_bill_endpoint_url, '/') . "/api/simpleInvoice/retrieve/" . $invoice_number);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $access_token
        ));

        $hb_response = curl_exec($ch);
        curl_close($ch);
        $hb_response = json_decode($hb_response, true);
        return $hb_response;
    }

    public function edit_booking(Request $request, $booking_id)
    {
        if ($request->isMethod('post')) {
            $posted_data = $request->all(); // booking_id, pickup_date, pickup_time

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
            $booking_edit_history['type'] = (custom::is_mobile() ? 'mobile' : 'website');
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

            $response['status'] = true;
            $response['title'] = Lang::get('labels.success');
            $response['message'] = trans('labels.booking_edited_successfully');
            $response['booking_id'] = custom::encode_with_jwt($posted_data['booking_id']);
            $response['is_logged_in'] = custom::checkIfUserLoggedin($this->lang) != "" ? true : false;
            echo json_encode($response);
            die();
        } else {
            $booking_id = custom::decode_with_jwt($booking_id);
            $data['booking_detail'] = $this->page->getSingle('booking', array('id' => $booking_id));
            if ($data['booking_detail']) {
                $data['base_url'] = $this->base_url;
                $data['lang_base_url'] = $this->lang_base_url;
                $data['lang'] = $this->lang;
                $data['active_menu'] = 'home';
                if (custom::is_mobile()) {
                    return view('frontend/mobile/edit_booking', $data);
                } else {
                    return view('frontend/edit_booking', $data);
                }
            } else {
                return redirect($this->lang_base_url . '/home');
            }
        }
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
            $response['status'] = false;
            $response['title'] = trans('labels.error');
            $response['message'] = $error_msg;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        }

    }

    public function get_niqaty_redeem_options(Request $request)
    {
        $RedeemOptionsResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemOptions?mobile=$request->niqaty_mobile_number");
        $RedeemOptionsResponse = rtrim($RedeemOptionsResponse, "\r\n");
        if (strtoupper($RedeemOptionsResponse) == 'Y') {
            $soapclient = new SoapClient("http://api.keyrac.sa:8080/NeqatyRedeemAPI/RedeemAPI?WSDL", ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
            $xml = simplexml_load_string('
            <getRedeemOption>
                <customerMobile>' . $request->niqaty_mobile_number . '</customerMobile>
            </getRedeemOption>
        ');
            $soap_response = $soapclient->getRedeemOption($xml);
            if (isset($soap_response->return->returnCode) && isset($soap_response->return->returnResult) && $soap_response->return->returnCode == 0) {
                $html = '<h5>' . trans('labels.select_any_of_the_redeem_options_from_below_to_avail_discount') . '</h5>';
                $html .= '<table class="table">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th class="text-center">&nbsp;</th>';
                $html .= '<th class="text-center">' . trans('labels.redeem_amount') . '</th>';
                $html .= '<th class="text-center">' . trans('labels.points_to_be_used') . '</th>';
                $html .= '</tr>';
                $html .= '</thead>';

                $html .= '<tbody>';
                if (is_array($soap_response->return->returnResult)) {
                    foreach ($soap_response->return->returnResult as $item) {
                        $html .= '<tr>';
                        $html .= '<td><input type="radio" name="niqaty_points_to_use" class="niqaty-redeem-option" value="' . $item->redeemPoints . '" data-amount="' . $item->redeemAmount . '" data-code="' . $item->redeemCode . '" data-points="' . $item->redeemPoints . '" data-token="' . $item->token . '" data-mobile="' . $request->niqaty_mobile_number . '"></td>';
                        $html .= '<td>' . $item->redeemAmount . '</td>';
                        $html .= '<td>' . $item->redeemPoints . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr>';
                    $html .= '<td><input type="radio" name="niqaty_points_to_use" class="niqaty-redeem-option" value="' . $soap_response->return->returnResult->redeemPoints . '" data-amount="' . $soap_response->return->returnResult->redeemAmount . '" data-code="' . $soap_response->return->returnResult->redeemCode . '" data-points="' . $soap_response->return->returnResult->redeemPoints . '" data-token="' . $soap_response->return->returnResult->token . '" data-mobile="' . $request->niqaty_mobile_number . '"></td>';
                    $html .= '<td>' . $soap_response->return->returnResult->redeemAmount . '</td>';
                    $html .= '<td>' . $soap_response->return->returnResult->redeemPoints . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</tbody>';

                $html .= '</table>';

                $response['status'] = true;
                $response['message'] = $html;
                echo json_encode($response);
                die();
            } else {
                $response['status'] = false;
                $response['message'] = (isset($soap_response->return->returnMessage) ? $soap_response->return->returnMessage : trans('labels.niqaty_input_not_fine'));
                echo json_encode($response);
                die();
            }
        } else {
            $response['status'] = false;
            $response['message'] = $RedeemOptionsResponse;
            echo json_encode($response);
            die();
        }
    }

    public function authorize_niqaty_redeem_request(Request $request)
    {
        $this->checkIfNiqatyRedeemable($request->amount);
        $posted_data = $request->all();
        $RedeemAuthorizeResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemAuthorize?" . http_build_query($posted_data));
        $RedeemAuthorizeResponse = rtrim($RedeemAuthorizeResponse, "\r\n");
        if (stripos($RedeemAuthorizeResponse, 'Error') === false && stripos(strip_tags($RedeemAuthorizeResponse), 'Origin Down') === false && stripos(strip_tags($RedeemAuthorizeResponse), 'Origin Connection Time-out') === false && stripos($RedeemAuthorizeResponse, '-') === false) {
            $posted_data['transaction_reference'] = $RedeemAuthorizeResponse;
            $response['status'] = true;
            $response['message'] = $RedeemAuthorizeResponse;
            $response['request_data'] = http_build_query($posted_data);
            echo json_encode($response);
            die();
        } else {
            $response['status'] = false;
            $response['message'] = $RedeemAuthorizeResponse;
            echo json_encode($response);
            die();
        }
    }

    private function checkIfNiqatyRedeemable($niqaty_amount)
    {
        $site_settings = custom::site_settings();
        $sessionVals = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days = $sessionVals['days'];

        if ($sessionVals['is_delivery_mode'] == 4) {
            $sessionVals['days'] = 30; // because 1 month is to be charged
            $days = $sessionVals['days'];
        }

        $cdw_charges = Session::get('cdw_charges');
        $cdw_charges_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

        $cdw_plus_charges = Session::get('cdw_plus_charges');
        $cdw_plus_charges_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

        $gps_charges = Session::get('gps_charges');
        $gps_charges_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

        $extra_driver_charges = Session::get('extra_driver_charges');
        $extra_driver_charges_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

        $baby_seat_charges = Session::get('baby_seat_charges');
        $baby_seat_charges_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

        if ($cdw_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_multiply_factor = 1;
        } else {
            $cdw_multiply_factor = $days;
        }

        if ($cdw_plus_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_plus_multiply_factor = 1;
        } else {
            $cdw_plus_multiply_factor = $days;
        }

        if ($gps_charges_is_one_time_applicable_on_booking == 1) {
            $gps_multiply_factor = 1;
        } else {
            $gps_multiply_factor = $days;
        }

        if ($extra_driver_charges_is_one_time_applicable_on_booking == 1) {
            $extra_driver_multiply_factor = 1;
        } else {
            $extra_driver_multiply_factor = $days;
        }

        if ($baby_seat_charges_is_one_time_applicable_on_booking == 1) {
            $baby_seat_multiply_factor = 1;
        } else {
            $baby_seat_multiply_factor = $days;
        }

        $dropoff_charges_amount = Session::get('dropoff_charges_amount');
        $delivery_charges = ($sessionVals['delivery_charges'] ? $sessionVals['delivery_charges'] : 0);
        $parking_fee = $sessionVals['parking_fee'];
        $tamm_charges_for_branch = $sessionVals['tamm_charges_for_branch'];

        $total_amount_without_vat = ($rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
        $vat_applicable = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
        $total_amount_with_vat = $total_amount_without_vat + $vat_applicable;

        $max_amount_redeemable_with_niqaty = round(($site_settings->amount_to_be_redeemed_by_niqaty_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($niqaty_amount > $max_amount_redeemable_with_niqaty) {
            $response['status'] = false;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_niqaty);
            echo json_encode($response);
            die();
        }

        return $total_amount_with_vat - $niqaty_amount;
    }

    public function confirm_niqaty_redeem_request(Request $request)
    {
        $sessionVals = Session::get('search_data');
        $posted_data = $request->all();
        $request_data = htmlspecialchars_decode($posted_data['request_data']);
        unset($posted_data['request_data']);
        $data = http_build_query($posted_data);
        $RedeemConfirmResponse = $this->sendCurlRequest("http://api2.keyrac.sa:8080/NeqatyService/RedeemConfirm?$data");
        $RedeemConfirmResponse = rtrim($RedeemConfirmResponse, "\r\n");
        if (strtoupper($RedeemConfirmResponse) == 'Y') {

            $this->page->saveData('niqaty_logs', array('status' => 'New', 'niqaty_request' => htmlspecialchars_decode($request_data), 'created_at' => date('Y-m-d H:i:s')));

            parse_str(htmlspecialchars_decode($request_data), $niqaty_request_data);

            $payable_amount_after_niqaty_deducted = $this->checkIfNiqatyRedeemable($niqaty_request_data['amount']);

            Session::put('niqaty_request', htmlspecialchars_decode($request_data));
            Session::put('niqaty_amount', $niqaty_request_data['amount']);
            Session::save();

            $response['status'] = true;
            $response['message'] = "";
            $response['amount_remaining'] = $payable_amount_after_niqaty_deducted > 0;
            $response['total_payable_amount_after_niqaty'] = $sessionVals['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($payable_amount_after_niqaty_deducted, 2) . ' ' . Lang::get('labels.currency');
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.niqaty_partial_redeem_text_to_show'), $niqaty_request_data['amount'], round($payable_amount_after_niqaty_deducted, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.niqaty_full_redeem_text_to_show'), $niqaty_request_data['amount']);
            }
            echo json_encode($response);
            die();
        } else {
            $response['status'] = false;
            $response['message'] = $RedeemConfirmResponse;
            echo json_encode($response);
            die();
        }
    }

    public function reverse_niqaty_for_temp_cancelled_expired_bookings_cronjob()
    {
        $count = 0;

        // reversing niqaty temp table logs
        $niqaty_logs = $this->page->getAllPendingNiqatyRedeemRequests();
        if ($niqaty_logs) {
            foreach ($niqaty_logs as $niqaty_log) {
                $niqaty_reverse_redeem_api_response = $this->callNiqatyReverseRedeemAPI($niqaty_log->niqaty_request);
                if ($niqaty_reverse_redeem_api_response) {
                    $niqaty_request_after_refund = htmlspecialchars_decode($niqaty_log->niqaty_request) . '&refundTransactionReference=' . $niqaty_reverse_redeem_api_response;
                    $this->page->updateData('niqaty_logs', ['niqaty_request' => htmlspecialchars_decode($niqaty_request_after_refund), 'status' => 'Reversed'], ['id' => $niqaty_log->id]);
                    $count++;
                }
            }
        }

        // reversing niqaty for cancelled/expired bookings
        $booking_date = '2021-09-09 00:00:00'; // we will only check bookings which were placed after this datetime
        $query = DB::table('booking')
            ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
            ->where('booking.sync', '!=', 'N')
            ->where('booking_payment.niqaty_request', '!=', '')
            ->where('booking_payment.is_niqaty_reversed', 0)
            ->where('booking.created_at', '>=', $booking_date)
            ->where(function ($query) {
                $query->where('booking.booking_status', 'Cancelled');
                $query->orWhere('booking.booking_status', 'Expired');
            })
            ->select('booking.id', 'booking_payment.niqaty_request');
        $bookings = $query->get();
        foreach ($bookings as $booking) {
            $niqaty_reverse_redeem_api_response = $this->callNiqatyReverseRedeemAPI($booking->niqaty_request);

            if ($niqaty_reverse_redeem_api_response) {
                $niqaty_request_after_refund = htmlspecialchars_decode($booking->niqaty_request) . '&refundTransactionReference=' . $niqaty_reverse_redeem_api_response;
                $this->page->updateData('booking_payment', ['niqaty_request' => htmlspecialchars_decode($niqaty_request_after_refund), 'is_niqaty_reversed' => 1], ['booking_id' => $booking->id]);
                $this->page->updateData('niqaty_logs', ['niqaty_request' => htmlspecialchars_decode($niqaty_request_after_refund), 'status' => 'Reversed after booking'], ['niqaty_request' => $booking->niqaty_request]);
                $count++;
            }
        }

        if ($count > 0) {
            echo 'Total ' . $count . ' transactions reversed successfully.';
            exit();
        } else {
            echo 'No transactions to reverse.';
            exit();
        }
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

    private function clearNiqatyAfterBookingConfirmed($booking_id, $updated_from = '')
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        if ($booking_payment_detail && $booking_payment_detail->niqaty_request != "") {
            $this->page->updateData('niqaty_logs', ['status' => 'Copied', 'updated_from' => $updated_from], array('niqaty_request' => htmlspecialchars_decode($booking_payment_detail->niqaty_request)));
        }
    }

    private function logNiqaty($txt)
    {
        // file_put_contents('niqaty.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function refunds(Request $request)
    {
        if ($request->isMethod('post')) {
            $posted_data = $request->all();
            $captcha_response = $this->reCaptcha($posted_data["g-recaptcha-response"]);
            if ($captcha_response) {
                unset($posted_data["g-recaptcha-response"]);

                $rules = [
                    'first_name' => 'bail|required|alpha_spaces|max:25',
                    'father_name' => 'bail|required|alpha_spaces|max:25',
                    'last_name' => 'bail|required|alpha_spaces|max:25',
                    'customer_id' => 'bail|required|alpha_num|max:15',
                    'mobile' => 'bail|required|min:12|max:12',
                    'bank_name' => 'bail|required|alpha_spaces|max:30',
                    'iban' => 'bail|required|alpha_num|max:24',
                    'booking_id' => 'bail|required|alpha_num|min:10|max:11',
                ];

                $messages = [
                    'first_name.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'First Name' : 'الاسم الاول')),
                    'first_name.alpha_spaces' => sprintf(trans('labels.alpha_spaces_field'), ($this->lang == 'eng' ? 'First Name' : 'الاسم الاول')),
                    'first_name.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'First Name' : 'الاسم الاول'), 25),

                    'father_name.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Father Name' : 'اسم الاب')),
                    'father_name.alpha_spaces' => sprintf(trans('labels.alpha_spaces_field'), ($this->lang == 'eng' ? 'Father Name' : 'اسم الاب')),
                    'father_name.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Father Name' : 'اسم الاب'), 25),

                    'last_name.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Last Name' : 'اسم العائلة')),
                    'last_name.alpha_spaces' => sprintf(trans('labels.alpha_spaces_field'), ($this->lang == 'eng' ? 'Last Name' : 'اسم العائلة')),
                    'last_name.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Last Name' : 'اسم العائلة'), 25),

                    'customer_id.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Customer ID Number' : 'رقم الهوية')),
                    'customer_id.alpha_num' => sprintf(trans('labels.alpha_num_field'), ($this->lang == 'eng' ? 'Customer ID Number' : 'رقم الهوية')),
                    'customer_id.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Customer ID Number' : 'رقم الهوية'), 15),

                    'mobile.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Mobile Number' : 'رقم الهاتف')),
                    'mobile.min' => sprintf(trans('labels.min_length_field'), ($this->lang == 'eng' ? 'Mobile Number' : 'رقم الهاتف'), 12),
                    'mobile.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Mobile Number' : 'رقم الهاتف'), 12),

                    'bank_name.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Bank Name' : 'اسم البنك')),
                    'bank_name.alpha_spaces' => sprintf(trans('labels.alpha_spaces_field'), ($this->lang == 'eng' ? 'Bank Name' : 'اسم البنك')),
                    'bank_name.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Bank Name' : 'اسم البنك'), 30),

                    'iban.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'IBAN' : 'رقم الآيبان')),
                    'iban.alpha_num' => sprintf(trans('labels.alpha_num_field'), ($this->lang == 'eng' ? 'IBAN' : 'رقم الآيبان')),
                    'iban.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'IBAN' : 'رقم الآيبان'), 24),

                    'booking_id.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Booking Number' : 'رقم الحجز')),
                    'booking_id.alpha_num' => sprintf(trans('labels.alpha_num_field'), ($this->lang == 'eng' ? 'Booking Number' : 'رقم الحجز')),
                    'booking_id.min' => sprintf(trans('labels.min_length_field'), ($this->lang == 'eng' ? 'Booking Number' : 'رقم الحجز'), 10),
                    'booking_id.max' => sprintf(trans('labels.max_length_field'), ($this->lang == 'eng' ? 'Booking Number' : 'رقم الحجز'), 11),
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    $response['status'] = false;
                    $response['message'] = str_replace('.', '.<br>', $validator->errors()->all());
                    return response()->json($response);
                }

                $posted_data['customer_name'] = $posted_data['first_name'] . ' ' . $posted_data['father_name'] . ' ' . $posted_data['last_name'];
                unset($posted_data['first_name'], $posted_data['father_name'], $posted_data['last_name']);

                $api_url = 'http://api2.keyrac.sa:8080/KeyBookingService/RequestRefund?' . http_build_query($posted_data);
                $curlResponse = $this->sendCurlRequest($api_url);
                if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false && stripos($curlResponse, '-') === false) {
                    $response['status'] = true;
                    $response['message'] = Lang::get('labels.form_submitted_msg');
                    return response()->json($response);
                } else {
                    $response['status'] = false;
                    $response['message'] = Lang::get('labels.form_submitting_error_msg');
                    return response()->json($response);
                }
            } else {
                $response['status'] = false;
                $response['message'] = Lang::get('labels.captcha_msg');
                return response()->json($response);
            }
        } else {
            $api = custom::api_settings();
            $data['captcha_site_key'] = $api->captcha_site_key;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('refunds', array('id' => '1'));
            $data['active_menu'] = 'refunds';
            if (custom::is_mobile()) {
                return view('frontend.mobile.refunds', $data);
            } else {
                return view('frontend.refunds', $data);
            }
        }
    }

    public function guar_refunds(Request $request)
    {
        if ($request->isMethod('post')) {
            $posted_data = $request->all();
            $captcha_response = $this->reCaptcha($posted_data["g-recaptcha-response"]);
            if ($captcha_response) {
                unset($posted_data["g-recaptcha-response"]);

                $rules = [
                    'contract_number' => 'bail|required',
                    'bank_name' => 'bail|required',
                    'iban' => 'bail|required|alpha_num',
                    'bank_country' => 'bail|required',
                    'bank_address' => 'bail|required',
                    'bank_swift_code' => 'bail|required'
                ];

                $messages = [
                    'contract_number.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Contract Number' : 'رقم الحجز')),
                    'bank_name.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Bank Name' : 'اسم البنك')),

                    'iban.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'IBAN' : 'رقم الآيبان')),
                    'iban.alpha_num' => sprintf(trans('labels.alpha_num_field'), ($this->lang == 'eng' ? 'IBAN' : 'رقم الآيبان')),

                    'bank_country.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Bank Country' : 'بنك الدولة')),
                    'bank_address.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Bank Address' : 'عنوان البنك')),
                    'bank_swift_code.required' => sprintf(trans('labels.required_field'), ($this->lang == 'eng' ? 'Bank Swift Code' : 'كود Swift للمصرف')),
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    $response['status'] = false;
                    $response['message'] = str_replace('.', '.<br>', $validator->errors()->all());
                    return response()->json($response);
                }

                $soapclient = new SoapClient('http://api.keyrac.sa:8080/CustomerInfoAPI/CustomerAPI?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
                $xml_string = '
                            <setBankInfo>
                                <bankInfo>
                                    <contractNo>' . $posted_data['contract_number'] . '</contractNo>
                                    <bankName>' . $posted_data['bank_name'] . '</bankName>
                                    <iban>' . $posted_data['iban'] . '</iban>
                                    <bankCountry>' . $posted_data['bank_country'] . '</bankCountry>
                                    <bankAddress>' . $posted_data['bank_address'] . '</bankAddress>
                                    <swiftCode>' . $posted_data['bank_swift_code'] . '</swiftCode>
                                </bankInfo>
                            </setBankInfo>
                        ';
                $xml = simplexml_load_string($xml_string);
                // echo $xml_string;die;
                $soap_response = $soapclient->setBankInfo($xml);
                if ($soap_response && strtolower($soap_response->return) == 'success') {
                    $response['status'] = true;
                    $response['message'] = Lang::get('labels.form_submitted_msg');
                    return response()->json($response);
                } else {
                    $response['status'] = false;
                    $response['message'] = Lang::get('labels.form_submitting_error_msg');
                    return response()->json($response);
                }
            } else {
                $response['status'] = false;
                $response['message'] = Lang::get('labels.captcha_msg');
                return response()->json($response);
            }
        } else {
            if (!$request->contract_number) {
                echo 'Contract Number missing in URL!';
                die;
            }
            $api = custom::api_settings();
            $data['captcha_site_key'] = $api->captcha_site_key;
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('guar_refunds', array('id' => '1'));
            $data['countries'] = $this->page->getAll('country', 'oracle_reference_number', 'ASC');
            $data['contract_number'] = $request->contract_number;
            $data['active_menu'] = 'guar_refunds';
            if (custom::is_mobile()) {
                return view('frontend.mobile.guar_refunds', $data);
            } else {
                return view('frontend.guar_refunds', $data);
            }
        }
    }

    private function validate_career_form($data)
    {
        $rules = [
            'name' => 'bail|required|alpha_spaces|max:25',
            'dob' => 'bail|required',
            'nationality' => 'bail|required',
            'email' => 'bail|required|email',
            'id_number' => 'bail|required|alpha_num',
            'mobile' => 'bail|required|min:9|max:9',
            'department_id' => 'bail|required',
            'city' => 'bail|required',
            'language' => 'bail|required',
            'qualification' => 'bail|required',
            'linkedin_profile_url' => 'nullable|url',
        ];

        $name_lbl = trans('labels.full_name');
        $dob_lbl = trans('labels.date_of_birth');
        $nationality_lbl = trans('labels.nationality');
        $email_lbl = trans('labels.email');
        $id_number_lbl = trans('labels.id_number');
        $mobile_lbl = trans('labels.mobile_no');
        $department_id_lbl = trans('labels.department');
        $city_lbl = ($this->lang == 'eng' ? 'City' : 'المدينة');
        $language_lbl = ($this->lang == 'eng' ? 'Language(s)' : 'اللغات');
        $qualification_lbl = ($this->lang == 'eng' ? 'Education /  Qualification' : 'التعليم / الخبرات');
        $linkedin_profile_url_lbl = ($this->lang == 'eng' ? 'LinkedIn Profile URL' : 'وترتبط ارتباط الملف');

        $messages = [
            'name.required' => sprintf(trans('labels.required_field'), $name_lbl),
            'name.alpha_spaces' => sprintf(trans('labels.alpha_spaces_field'), $name_lbl),
            'name.max' => sprintf(trans('labels.max_length_field'), $name_lbl, 25),

            'dob.required' => sprintf(trans('labels.required_field'), $dob_lbl),

            'nationality.required' => sprintf(trans('labels.required_field'), $nationality_lbl),

            'email.required' => sprintf(trans('labels.required_field'), $email_lbl),
            'email.email' => sprintf(trans('labels.email_field'), $email_lbl),

            'id_number.required' => sprintf(trans('labels.required_field'), $id_number_lbl),

            'mobile.required' => sprintf(trans('labels.required_field'), $mobile_lbl),
            'mobile.min' => sprintf(trans('labels.min_length_field'), $mobile_lbl, 9),
            'mobile.max' => sprintf(trans('labels.max_length_field'), $mobile_lbl, 9),

            'department_id.required' => sprintf(trans('labels.required_field'), $department_id_lbl),

            'city.required' => sprintf(trans('labels.required_field'), $city_lbl),

            'language.required' => sprintf(trans('labels.required_field'), $language_lbl),

            'qualification.required' => sprintf(trans('labels.required_field'), $qualification_lbl),

            'linkedin_profile_url.alpha_spaces' => sprintf(trans('labels.url_field'), $linkedin_profile_url_lbl),
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $response['success'] = false;
            $response['message'] = str_replace('.', '.<br>', $validator->errors()->all());
            echo json_encode($response);
            exit();

        }
    }

    public function fetch_nearest_delivery_branch(Request $request)
    {
        $current_latitude = $request->current_latitude;
        $current_longitude = $request->current_longitude;
        $isLimousine = $request->has('isLimousine') ? 1 : 0;
        $branch_id = $request->has('branch_id') ? $request->branch_id : false;

        $branch = $this->page->get_nearest_branch($current_latitude, $current_longitude, $isLimousine, $branch_id);

        if ($branch) {
            $result['status'] = true;
            $result['branch'] = $branch;
            echo json_encode($result);
            exit();
        } else {
            $result['status'] = false;
            $result['message'] = "No branches found!";
            echo json_encode($result);
            exit();
        }

    }

    public function add_payment(Request $request)
    {
        try {
            $site_settings = custom::site_settings();
            $api_settings = custom::api_settings();
            if ($request->has('s')) {
                if ($request->s == 1) { // show add payment view with step 1 and step 2

                    // clearind old session
                    session()->forget('add_payment');
                    session()->save();

                    $booking = DB::table('booking')
                        ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
                        ->where('id', custom::decode_with_jwt($request->q))
                        ->select('booking.*', 'booking_payment.*')
                        ->first();

                    if ($booking) {
                        // $reservation_code = 'WJMB583503'; // hardcoded just for testing
                        $reservation_code = $booking->reservation_code;
                        if (custom::is_oasis_api_enabled()) {
                            $soapclient = new SoapClient($api_settings->oasis_api_url . '?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
                            $xml = simplexml_load_string('<getContractBalance><getContractBalance>' . $reservation_code . '</getContractBalance></getContractBalance>');
                            $response = $soapclient->getContractBalance($xml);
                        }

                        /*$response = new stdClass();
                        $response->return = 100;*/

                        if (isset($response) && $response && strpos($response->return, 'Error') === false) {
                            $data['balance_amount'] = $response->return;
                            $amount_per_day = $booking->rent_price + $booking->cdw_price + $booking->cdw_plus_price + $booking->gps_price + $booking->extra_driver_price + $booking->baby_seat_price;
                            $vat = ($site_settings->vat_percentage / 100) * $amount_per_day;
                            $data['booking_total_per_day'] = round($amount_per_day + $vat, 2);
                            $data['booking'] = $booking;
                            $data['base_url'] = $this->base_url;
                            $data['lang_base_url'] = $this->lang_base_url;
                            $data['lang'] = $this->lang;
                            $data['api_settings'] = $api_settings;
                            $data['site_settings'] = $site_settings;
                            $data['active_menu'] = 'home';
                            if (custom::is_mobile()) {
                                return view('frontend/mobile/add-payment', $data);
                            } else {
                                return view('frontend/add-payment', $data);
                            }
                        } else {
                            $this->send_debug_email('Add Payment: Response error in getContractBalance OASIS API', $response->return);
                            return redirect($this->lang_base_url);
                        }
                    }

                    return redirect($this->lang_base_url);

                } elseif ($request->s == 2) { // putting selected data into sessions to be used next

                    $posted_data = $request->all();
                    $posted_data['amount'] = str_replace(',', '', number_format($posted_data['amount'], 2));
                    session()->put('add_payment', $posted_data);
                    session()->save();
                    echo json_encode(['status' => true, 'data' => $posted_data]);
                    die;

                } elseif ($request->s == 3) { // show cc view step 3

                    $booking = DB::table('booking')
                        ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
                        ->where('id', session()->get('add_payment.booking_id'))
                        ->select('booking.*', 'booking_payment.*')
                        ->first();

                    if ($site_settings->cc_company == 'hyper_pay') {
                        $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();
                        $hp_response = $this->hp_generate_checkout_id(session()->get('add_payment.booking_id'), session()->get('add_payment.is_mada'), 'E' . ($booking_added_payments + 1), Session::get('add_payment.amount'), Session::get('add_payment.number_of_days'));
                        session()->put('add_payment.checkout_id', $hp_response['checkout_id']);
                        session()->save();
                    }

                    $data['booking'] = $booking;
                    $data['base_url'] = $this->base_url;
                    $data['lang_base_url'] = $this->lang_base_url;
                    $data['lang'] = $this->lang;
                    $data['api_settings'] = $api_settings;
                    $data['site_settings'] = $site_settings;
                    $data['active_menu'] = 'home';
                    if (custom::is_mobile()) {
                        return view('frontend/mobile/add-payment', $data);
                    } else {
                        return view('frontend/add-payment', $data);
                    }

                } elseif ($request->s == 4) { // check payment status

                    if ($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds > 0) {
                        sleep($site_settings->hyper_pay_check_payment_status_api_wait_time_in_seconds);
                    }

                    $booking = $this->page->getSingle('booking', ['id' => Session::get('add_payment.booking_id')]);
                    $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();
                    $hp_response = custom::hp_check_payment_status_api($request->resourcePath, Session::get('add_payment.is_mada'), Session::get('add_payment.booking_id'), 'E' . ($booking_added_payments + 1));

                    $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";

                    $pending_transaction_regex = "/^(000\.200)/";
                    $manually_pending_transaction_regex = "/^(000\.400\.0[^3]|000\.400\.100)/";

                    if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {
                        $booking = $this->page->getSingle('booking', ['id' => Session::get('add_payment.booking_id')]);

                        $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

                        $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
                        $booking_add_payment['extended_days'] = Session::get('add_payment.number_of_days');
                        $booking_add_payment['payment_company'] = 'HP';
                        $booking_add_payment['payment_method'] = $this->hp_payment_method($hp_response['paymentBrand']);
                        $booking_add_payment['transaction_reference'] = $hp_response['id'];
                        $booking_add_payment['card_number'] = (isset($hp_response['card']) ? $hp_response['card']['bin'] : '') . '********' . (isset($hp_response['card']) ? $hp_response['card']['last4Digits'] : '');
                        $booking_add_payment['amount'] = Session::get('add_payment.amount');
                        $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
                        $booking_add_payment['payment_source'] = (custom::is_mobile() ? 'Mobile Website' : 'Website');
                        $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
                        $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
                        $id = $this->page->saveData('booking_added_payments', $booking_add_payment);
                        session()->put('add_payment.booking_add_payment_id', $id);
                        session()->save();

                        // sending confirmation SMS and email to customer
                        $this->send_add_payment_confirmation_to_customer($id);

                        // syncing added payment data with OASIS
                        $this->sync_booking_added_payments_with_oasis($id);

                        return redirect($this->lang_base_url . '/add-payment?s=5');
                    } elseif (
                        preg_match($pending_transaction_regex, $hp_response['result']['code']) ||
                        preg_match($manually_pending_transaction_regex, $hp_response['result']['code'])
                    ) {
                        session()->put('jc-message', trans('labels.hyper_pay_pending_payment_error'));
                        session()->save();
                        return redirect($this->lang_base_url . '/add-payment?s=1&q=' . custom::encode_with_jwt(Session::get('add_payment.booking_id')));
                    } else {
                        session()->put('jc-message', sprintf(trans('labels.hyper_pay_payment_error'), trans('labels.transaction_failed')));
                        session()->save();
                        return redirect($this->lang_base_url . '/add-payment?s=1&q=' . custom::encode_with_jwt(Session::get('add_payment.booking_id')));
                    }

                } elseif ($request->s == 5) { // show thank you page
                    $booking_added_payment = $this->page->getSingle('booking_added_payments', ['id' => session()->get('add_payment.booking_add_payment_id')]);
                    $booking = DB::table('booking')
                        ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
                        ->where('id', session()->get('add_payment.booking_id'))
                        ->select('booking.*', 'booking_payment.*')
                        ->first();
                    $data['booking'] = $booking;
                    $data['booking_added_payment'] = $booking_added_payment;
                    $data['base_url'] = $this->base_url;
                    $data['lang_base_url'] = $this->lang_base_url;
                    $data['lang'] = $this->lang;
                    $data['api_settings'] = $api_settings;
                    $data['site_settings'] = $site_settings;
                    $data['active_menu'] = 'home';
                    if (custom::is_mobile()) {
                        return view('frontend/mobile/add-payment', $data);
                    } else {
                        return view('frontend/add-payment', $data);
                    }
                } elseif ($request->s == 6) {
                    // check for previous payment for this booking and generate proper data to save to booking added payments table
                    $posted_data = $request->all();
                    $booking = $this->page->getSingle('booking', ['id' => $posted_data['booking_id']]);

                    $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

                    $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
                    $booking_add_payment['extended_days'] = $posted_data['number_of_days'];
                    $booking_add_payment['payment_company'] = 'HP';
                    $booking_add_payment['payment_method'] = 'PROMOCODE';
                    $booking_add_payment['transaction_reference'] = $posted_data['coupon_code_for_add_payment'];
                    $booking_add_payment['card_number'] = '';
                    $booking_add_payment['amount'] = $posted_data['amount'];
                    $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
                    $booking_add_payment['payment_source'] = (custom::is_mobile() ? 'Mobile Website' : 'Website');
                    $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
                    $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
                    $id = $this->page->saveData('booking_added_payments', $booking_add_payment);
                    session()->put('add_payment.booking_add_payment_id', $id);
                    session()->save();

                    // sending confirmation SMS and email to customer
                    $this->send_add_payment_confirmation_to_customer($id);

                    // syncing added payment data with OASIS
                    $this->sync_booking_added_payments_with_oasis($id);

                    echo json_encode(['status' => true]);die;
                }
            }

            return redirect($this->lang_base_url . '/my-profile');
        } catch (Exception $e) {
            $this->send_debug_email('Add Payment: Catch error in add_payment function for website', $e->getMessage());
            $response['status'] = 0;
            $response['message'] = "Something went wrong.";
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

    public function hp_ipn_for_add_payment(Request $request)
    {
        try {

            // Generate checkout ID response (generate_checkout_id_response)
            // {"result":{"code":"000.200.100","description":"successfully created checkout"},"buildNumber":"8502c74f571e6ad85c4b6f3a0e0bd637c74fb451@2022-05-25 13:36:55 +0000","timestamp":"2022-05-26 14:15:15+0000","ndc":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","id":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","action_performed_at":"2022-05-26 17:15:15"}

            // Check payment status request
            // {"resource_path":"\/v1\/checkouts\/8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01\/payment","entity_id":"8ac7a4c774c45b1c0174cf9271df0ccb","action_performed_at":"2022-05-26 17:16:30"}

            // IPN Request
            // {"id":"8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01","resourcePath":"\/v1\/checkouts\/8DE934C58D8C58538092909AA58FA7AC.uat01-vm-tx01\/payment","entity_id":"8ac7a4c774c45b1c0174cf9271df0ccb","action_performed_at":"2022-05-26 17:45:42"}

            $this->send_hyper_pay_debug_email("Hyper Pay Add Payment IPN Hit", 'Hit at the top of the IPN function');


            // checking DB against resource path for entity id
            $data = explode('/', ltrim($request->resourcePath, '/'));
            $hyper_pay_log_for_entity_id_check = DB::table('booking_added_payments_hyper_pay_log')->where('generate_checkout_id_response', 'like', '%' . $data[2] . '%')->first();

            if ($hyper_pay_log_for_entity_id_check && $hyper_pay_log_for_entity_id_check->generate_checkout_id_request) {
                $hp_generate_checkout_id_request_from_log = json_decode($hyper_pay_log_for_entity_id_check->generate_checkout_id_request, true);
                $entity_id = $hp_generate_checkout_id_request_from_log['entityId'];

                $api_setting = custom::api_settings();

                $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . $request->resourcePath;
                $url .= "?entityId=" . $entity_id;
                // echo $url;die;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $hp_response = curl_exec($ch);
                curl_close($ch);
                $hp_response = json_decode($hp_response, true);

                if ($request->has('debug')) {
                    custom::dump($hp_response);
                }

                // sending debugging email
                $mail_res['received_request_params'] = $_REQUEST;
                $mail_res['received_response_params'] = $hp_response;
                $this->send_hyper_pay_debug_email("Hyper Pay Add Payment IPN Hit", json_encode($mail_res));

                $successful_transaction_regex = "/^(000\.000\.|000\.100\.1|000\.[36])/";

                /* if payment is successfully done then update hyperpay details in db against booking id and sending confirmation to custom/admin/agent */
                /* Start */
                if (preg_match($successful_transaction_regex, $hp_response['result']['code'])) {

                    // saving it inside of success case because there is no merchant transaction id in error case
                    $last_occurance_of_e = strripos($hp_response['merchantTransactionId'], 'e');
                    $reservation_code = substr($hp_response['merchantTransactionId'], 0, $last_occurance_of_e);

                    $booking = DB::table('booking')->where('reservation_code', $reservation_code)->first();

                    $booking_added_payments = DB::table('booking_added_payments')->where('booking_reservation_code', $booking->reservation_code)->count();

                    if ($booking->booking_source == 'website') {
                        $payment_source = 'Website';
                    } elseif ($booking->booking_source == 'mobile') {
                        $payment_source = 'Mobile Website';
                    } else {
                        $payment_source = ucfirst($booking->booking_source);
                    }

                    $booking_add_payment['booking_reservation_code'] = $booking->reservation_code;
                    $booking_add_payment['extended_days'] = (isset($hp_generate_checkout_id_request_from_log['extended_days']) ? $hp_generate_checkout_id_request_from_log['extended_days'] : 0);
                    $booking_add_payment['payment_company'] = 'HP';
                    $booking_add_payment['payment_method'] = $this->hp_payment_method($hp_response['paymentBrand']);
                    $booking_add_payment['transaction_reference'] = $hp_response['id'];
                    $booking_add_payment['card_number'] = (isset($hp_response['card']) ? $hp_response['card']['bin'] : '') . '********' . (isset($hp_response['card']) ? $hp_response['card']['last4Digits'] : '');
                    $booking_add_payment['amount'] = $hp_generate_checkout_id_request_from_log['amount'];
                    $booking_add_payment['transaction_created_at'] = date('Y-m-d H:i:s');
                    $booking_add_payment['payment_source'] = $payment_source;
                    $booking_add_payment['number_of_payment'] = $booking_added_payments + 1;
                    $booking_add_payment['payment_booking_id'] = $booking->reservation_code . 'E' . ($booking_added_payments + 1);
                    $id = $this->page->saveData('booking_added_payments', $booking_add_payment);

                    // sending confirmation SMS and email to customer
                    $this->send_add_payment_confirmation_to_customer($id);

                    // syncing added payment data with OASIS
                    $this->sync_booking_added_payments_with_oasis($id);

                }
                /* End */

                echo 'success';
                die;
            }

            echo 'Error: Entity ID not found.';
            die;

        } catch (Exception $ex) {
            $this->send_hyper_pay_debug_email("Hyper Pay Add Payment IPN Exception Occurred", $ex->getMessage());
            echo 'Error: ' . $ex->getMessage();
            die;
        }
    }

    public function sync_booking_added_payments_with_oasis_cronjob()
    {
        $booking_added_payments = $this->page->getMultipleRows('booking_added_payments', ['sync_status' => 'N']);
        if ($booking_added_payments) {
            foreach ($booking_added_payments as $booking_added_payment) {
                $this->sync_booking_added_payments_with_oasis($booking_added_payment->id);
            }
        }

        echo 'Cronjob completed successfully!';
        die();
    }

    private function send_add_payment_confirmation_to_customer($id)
    {
        $booking_added_payment = $this->page->getSingle('booking_added_payments', ['id' => $id]);
        $booking = $this->page->getSingle('booking', ['reservation_code' => $booking_added_payment->booking_reservation_code]);
        if ($booking->type == 'corporate_customer') {
            $details = $this->page->getSingle("booking_corporate_customer", array('booking_id' => $booking->id));
            $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
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

    public function check_app_popup_promo_codes()
    {
        $number_of_days_to_add = 2;
        $where = "seen_at IS NOT NULL AND DATE_ADD(seen_at, INTERVAL ".$number_of_days_to_add." DAY) < '".date('Y-m-d H:i:s')."'";
        $query = DB::table('app_popup_promo_codes_list')->where('is_used', 0)->whereRaw($where);
        $app_popup_promo_codes_list = $query->get();
        foreach ($app_popup_promo_codes_list as $app_popup_promo_code) {
            DB::table('app_popup_promo_codes_list')->where('id', $app_popup_promo_code->id)->update(['seen_by' => '', 'seen_at' => NULL]);
        }

        echo 'Cronjob completed successfully!';
        die();
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

    public function send_booking_email_and_pdf_to_customers() {

        $site_settings = custom::site_settings();

        $query = "
        SELECT b.* 
        FROM booking b
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method bipm on b.id=bipm.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        WHERE b.is_email_and_pdf_sent_to_customer = 0
        AND ( (b.booking_status='Not Picked') or (b.booking_status='Picked') or (b.booking_status='Walk in') )
        AND (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit' or bci.payment_status='paid')          ORDER BY b.id ASC LIMIT " . $site_settings->send_booking_email_and_pdf_to_customers_limit;

        $bookings = DB::select($query);

        foreach($bookings as $booking) {
            if ($booking->type == "corporate_customer") {
                $this->sendToKeyAdmin($booking->id);
                $this->sendToCorporateUser($booking->id);
                $this->sendToDriver($booking->id);
            } else {
                $this->sendToKeyAdmin($booking->id);
                $this->sendEmailToUser($booking->id);
            }

            if ($booking->is_delivery_mode == 'yes') {
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
                $from_branch = $this->page->getSingle('branch', ['id' => $booking->from_location]);
                $this->send_email_to_branch_agent($booking->id, $from_branch->email, $first_name, $last_name);
            }

            $this->page->updateData('booking', ['is_email_and_pdf_sent_to_customer' => 1], ['id' => $booking->id]);
        }

        echo 'Cronjob completed successfully!';

    }

    public function move_bookings_from_main_to_backup_tables()
    {
        try {
            $site_settings = custom::site_settings();

            $query = "SELECT booking.id, booking.reservation_code FROM booking 
                    WHERE DATE(booking.created_at) <= DATE_SUB(now(), INTERVAL 3 MONTH) AND (
                    (booking.booking_status IN ('Completed', 'Cancelled', 'Expired') AND booking.sync in ('A', 'M', 'N')) OR 
                    (booking.booking_status = 'Not Picked' AND booking.sync = 'N')
                    ) ORDER BY booking.id ASC LIMIT " . $site_settings->move_bookings_from_main_to_backup_tables_limit;
            $bookings = DB::select($query);
            // custom::dump($bookings);

            $booking_ids = $booking_reservation_codes = [];
            foreach ($bookings as $booking) {
                $booking_ids[] = $booking->id;
                $booking_reservation_codes[] = "'" . $booking->reservation_code . "'";
            }

            if (!empty($booking_ids)) {

                DB::insert("INSERT INTO `booking_bk` SELECT * FROM `booking` WHERE booking.id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_added_payments_bk` SELECT * FROM `booking_added_payments` WHERE booking_added_payments.booking_reservation_code IN (" . implode(',', $booking_reservation_codes) . ")");

                DB::insert("INSERT INTO `booking_added_payments_hyper_pay_log_bk` SELECT * FROM booking_added_payments_hyper_pay_log WHERE DATE(booking_added_payments_hyper_pay_log.created_at) <= DATE_SUB(now(), INTERVAL 3 MONTH)");

                DB::insert("INSERT INTO `booking_cancel_bk` SELECT * FROM `booking_cancel` WHERE booking_cancel.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_cc_payment_bk` SELECT * FROM `booking_cc_payment` WHERE booking_cc_payment.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_corporate_customer_bk` SELECT * FROM `booking_corporate_customer` WHERE booking_corporate_customer.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_corporate_invoice_bk` SELECT * FROM `booking_corporate_invoice` WHERE booking_corporate_invoice.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_edit_history_bk` SELECT * FROM `booking_edit_history` WHERE booking_edit_history.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_hyper_pay_log_bk` SELECT * FROM `booking_hyper_pay_log` WHERE booking_hyper_pay_log.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_individual_guest_bk` SELECT * FROM `booking_individual_guest` WHERE booking_individual_guest.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_individual_payment_method_bk` SELECT * FROM `booking_individual_payment_method` WHERE booking_individual_payment_method.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_individual_user_bk` SELECT * FROM `booking_individual_user` WHERE booking_individual_user.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_payment_bk` SELECT * FROM `booking_payment` WHERE booking_payment.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_sadad_payment_bk` SELECT * FROM `booking_sadad_payment` WHERE booking_sadad_payment.s_booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::insert("INSERT INTO `booking_sts_log_bk` SELECT * FROM booking_sts_log WHERE DATE(booking_sts_log.created_at) <= DATE_SUB(now(), INTERVAL 3 MONTH)");

                DB::delete("DELETE FROM `booking_added_payments` WHERE booking_added_payments.booking_reservation_code IN (" . implode(',', $booking_reservation_codes) . ")");

                DB::delete("DELETE FROM `booking_added_payments_hyper_pay_log` WHERE DATE(booking_added_payments_hyper_pay_log.created_at) <= DATE_SUB(now(), INTERVAL 3 MONTH)");

                DB::delete("DELETE FROM `booking_cancel` WHERE booking_cancel.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_cc_payment` WHERE booking_cc_payment.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_corporate_customer` WHERE booking_corporate_customer.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_corporate_invoice` WHERE booking_corporate_invoice.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_edit_history` WHERE booking_edit_history.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_hyper_pay_log` WHERE booking_hyper_pay_log.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_individual_guest` WHERE booking_individual_guest.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_individual_payment_method` WHERE booking_individual_payment_method.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_individual_user` WHERE booking_individual_user.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_payment` WHERE booking_payment.booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM `booking_sadad_payment` WHERE booking_sadad_payment.s_booking_id IN (" . implode(',', $booking_ids) . ")");

                DB::delete("DELETE FROM booking_sts_log WHERE DATE(booking_sts_log.created_at) <= DATE_SUB(now(), INTERVAL 3 MONTH)");

                DB::delete("DELETE FROM booking WHERE booking.id IN (" . implode(',', $booking_ids) . ")");

                echo 'Cronjob completed successfully. Total bookings moved: ' . count($booking_ids);die;

            } else {
                echo "Nothing to move!";die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function mokafaa_get_access_token(Request $request)
    {
        $api_settings = custom::api_settings();
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'GetAccessToken');

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => true, 'message' => "", 'data' => ['access_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function mokafaa_send_otp(Request $request)
    {
        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->mokafaa_api_base_url, 'test') === false ? $request->mobile_number : '966002072675');
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'IssueOtp?mobile=' . $mobile_number . '&accessToken=' . $request->access_token);

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => true, 'message' => "", 'data' => ['otp_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function mokafaa_initiate_redeem_request(Request $request)
    {
        $payable_amount_after_mokafaa_deducted = $this->mokafaa_check_if_redeemable($request->mokafaa_amount);

        $search_data = Session::get('search_data');
        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->mokafaa_api_base_url, 'test') === false ? $request->mobile_number : '966002072675');
        $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'RedeemAmount?mobile=' . $mobile_number . '&amount=' . $request->mokafaa_amount . '&otp=' . $request->otp_code . '&otpToken=' . $request->otp_token . '&accessToken=' . $request->access_token);

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {

            Session::put('mokafaa_request', $curlResponse);
            Session::put('mokafaa_amount', $request->mokafaa_amount);
            Session::save();

            $this->page->saveData('mokafaa_logs', array('status' => 'New', 'mokafaa_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            $response['status'] = true;
            $response['message'] = "";
            $response['amount_remaining'] = $payable_amount_after_mokafaa_deducted > 0;
            $response['total_payable_amount_after_mokafaa'] = $search_data['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($payable_amount_after_mokafaa_deducted, 2) . ' ' . Lang::get('labels.currency');
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.mokafaa_partial_redeem_text_to_show'), $request->mokafaa_amount, round($payable_amount_after_mokafaa_deducted, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.mokafaa_full_redeem_text_to_show'), $request->mokafaa_amount);
            }
            echo json_encode($response);
            die();
        } else {
            echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later')]);
            die();
        }
    }

    private function mokafaa_check_if_redeemable($mokafaa_amount)
    {
        $site_settings = custom::site_settings();
        $search_data = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days = $search_data['days'];

        if ($search_data['is_delivery_mode'] == 4) {
            $search_data['days'] = 30; // because 1 month is to be charged
            $days = $search_data['days'];
        }

        $cdw_charges = Session::get('cdw_charges');
        $cdw_charges_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

        $cdw_plus_charges = Session::get('cdw_plus_charges');
        $cdw_plus_charges_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

        $gps_charges = Session::get('gps_charges');
        $gps_charges_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

        $extra_driver_charges = Session::get('extra_driver_charges');
        $extra_driver_charges_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

        $baby_seat_charges = Session::get('baby_seat_charges');
        $baby_seat_charges_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

        if ($cdw_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_multiply_factor = 1;
        } else {
            $cdw_multiply_factor = $days;
        }

        if ($cdw_plus_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_plus_multiply_factor = 1;
        } else {
            $cdw_plus_multiply_factor = $days;
        }

        if ($gps_charges_is_one_time_applicable_on_booking == 1) {
            $gps_multiply_factor = 1;
        } else {
            $gps_multiply_factor = $days;
        }

        if ($extra_driver_charges_is_one_time_applicable_on_booking == 1) {
            $extra_driver_multiply_factor = 1;
        } else {
            $extra_driver_multiply_factor = $days;
        }

        if ($baby_seat_charges_is_one_time_applicable_on_booking == 1) {
            $baby_seat_multiply_factor = 1;
        } else {
            $baby_seat_multiply_factor = $days;
        }

        $dropoff_charges_amount = Session::get('dropoff_charges_amount');
        $delivery_charges = ($search_data['delivery_charges'] ? $search_data['delivery_charges'] : 0);
        $parking_fee = $search_data['parking_fee'];
        $tamm_charges_for_branch = $search_data['tamm_charges_for_branch'];

        $total_amount_without_vat = ($rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
        $vat_applicable = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
        $total_amount_with_vat = $total_amount_without_vat + $vat_applicable;

        $max_amount_redeemable_with_mokafaa = round(($site_settings->amount_to_be_redeemed_by_mokafaa_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($mokafaa_amount > $max_amount_redeemable_with_mokafaa) {
            $response['status'] = false;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_mokafaa);
            echo json_encode($response);
            die();
        }

        return $total_amount_with_vat - $mokafaa_amount;
    }

    public function anb_get_access_token(Request $request)
    {
        $api_settings = custom::api_settings();
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'AuthorizeToken');

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
            echo json_encode(['status' => true, 'message' => "", 'data' => ['access_token' => $curlResponse]]);
            die();
        } else {
            echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
            die();
        }
    }

    public function anb_send_otp(Request $request)
    {
        $api_settings = custom::api_settings();
        $search_data = Session::get('search_data');
        $branch_info = $this->page->getSingle('branch', array('id' => $search_data['from_branch_id']));
        $mobile_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $request->mobile_number : '966504652456');
        $oracle_reference_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $branch_info->oracle_reference_number : 'RR5001');
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'AuthorizeOtp?mobile=' . $mobile_number . '&branch=' . $oracle_reference_number . '&access_token=' . $request->access_token);

        if (stripos($curlResponse, 'Error') === false) {
            echo json_encode(['status' => true, 'message' => "", 'data' => ['otp_token' => $curlResponse]]);
            die();
        } else {
            if (stripos($curlResponse, 'Last generated OTP is still valid') !== false) {
                echo json_encode(['status' => true, 'message' => trans('labels.last_sent_otp_is_still_valid'), 'data' => []]);
                die();
            } else {
                echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later'), 'data' => []]);
                die();
            }
        }
    }

    public function anb_initiate_redeem_request(Request $request)
    {
        $payable_amount_after_anb_deducted = $this->anb_check_if_redeemable($request->anb_amount);

        $search_data = Session::get('search_data');
        $api_settings = custom::api_settings();
        $mobile_number = (stripos($api_settings->anb_api_base_url, 'test') === false ? $request->mobile_number : '966504652456');
        $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'Redemption?mobile=' . $mobile_number . '&amount=' . $request->anb_amount . '&otp_value=' . $request->otp_code . '&otp_token=' . $request->otp_token . '&access_token=' . $request->access_token);

        // custom::dump($curlResponse);

        if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {

            Session::put('anb_request', $curlResponse);
            Session::put('anb_amount', $request->anb_amount);
            Session::save();

            $this->page->saveData('anb_logs', array('status' => 'New', 'anb_request' => $curlResponse, 'created_at' => date('Y-m-d H:i:s')));

            $response['status'] = true;
            $response['message'] = "";
            $response['amount_remaining'] = $payable_amount_after_anb_deducted > 0;
            $response['total_payable_amount_after_anb'] = $search_data['days'] . ' ' . Lang::get('labels.days') . ' ' . number_format($payable_amount_after_anb_deducted, 2) . ' ' . Lang::get('labels.currency');
            if ($response['amount_remaining']) {
                $response['text_to_show'] = sprintf(trans('labels.anb_partial_redeem_text_to_show'), $request->anb_amount, round($payable_amount_after_anb_deducted, 2));
            } else {
                $response['text_to_show'] = sprintf(trans('labels.anb_full_redeem_text_to_show'), $request->anb_amount);
            }
            echo json_encode($response);
            die();
        } else {
            echo json_encode(['status' => false, 'message' => trans('labels.something_went_wrong_please_try_again_later')]);
            die();
        }
    }

    private function anb_check_if_redeemable($anb_amount)
    {
        $site_settings = custom::site_settings();
        $search_data = Session::get('search_data');
        $rent_per_day = Session::get('rent_per_day');
        $days = $search_data['days'];

        if ($search_data['is_delivery_mode'] == 4) {
            $search_data['days'] = 30; // because 1 month is to be charged
            $days = $search_data['days'];
        }

        $cdw_charges = Session::get('cdw_charges');
        $cdw_charges_is_one_time_applicable_on_booking = Session::get('cdw_charges_is_one_time_applicable_on_booking');

        $cdw_plus_charges = Session::get('cdw_plus_charges');
        $cdw_plus_charges_is_one_time_applicable_on_booking = Session::get('cdw_plus_charges_is_one_time_applicable_on_booking');

        $gps_charges = Session::get('gps_charges');
        $gps_charges_is_one_time_applicable_on_booking = Session::get('gps_charges_is_one_time_applicable_on_booking');

        $extra_driver_charges = Session::get('extra_driver_charges');
        $extra_driver_charges_is_one_time_applicable_on_booking = Session::get('extra_driver_charges_is_one_time_applicable_on_booking');

        $baby_seat_charges = Session::get('baby_seat_charges');
        $baby_seat_charges_is_one_time_applicable_on_booking = Session::get('baby_seat_charges_is_one_time_applicable_on_booking');

        if ($cdw_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_multiply_factor = 1;
        } else {
            $cdw_multiply_factor = $days;
        }

        if ($cdw_plus_charges_is_one_time_applicable_on_booking == 1) {
            $cdw_plus_multiply_factor = 1;
        } else {
            $cdw_plus_multiply_factor = $days;
        }

        if ($gps_charges_is_one_time_applicable_on_booking == 1) {
            $gps_multiply_factor = 1;
        } else {
            $gps_multiply_factor = $days;
        }

        if ($extra_driver_charges_is_one_time_applicable_on_booking == 1) {
            $extra_driver_multiply_factor = 1;
        } else {
            $extra_driver_multiply_factor = $days;
        }

        if ($baby_seat_charges_is_one_time_applicable_on_booking == 1) {
            $baby_seat_multiply_factor = 1;
        } else {
            $baby_seat_multiply_factor = $days;
        }

        $dropoff_charges_amount = Session::get('dropoff_charges_amount');
        $delivery_charges = ($search_data['delivery_charges'] ? $search_data['delivery_charges'] : 0);
        $parking_fee = $search_data['parking_fee'];
        $tamm_charges_for_branch = $search_data['tamm_charges_for_branch'];

        $total_amount_without_vat = ($rent_per_day * $days) + ($cdw_charges * $cdw_multiply_factor) + ($cdw_plus_charges * $cdw_plus_multiply_factor) + ($gps_charges * $gps_multiply_factor) + ($extra_driver_charges * $extra_driver_multiply_factor) + ($baby_seat_charges * $baby_seat_multiply_factor) + $dropoff_charges_amount + $delivery_charges + $parking_fee + $tamm_charges_for_branch;
        $vat_applicable = (Session::get('vat_percentage') / 100) * $total_amount_without_vat;
        $total_amount_with_vat = $total_amount_without_vat + $vat_applicable;

        $max_amount_redeemable_with_anb = round(($site_settings->amount_to_be_redeemed_by_anb_as_percentage / 100) * $total_amount_with_vat, 2);

        if ($anb_amount > $max_amount_redeemable_with_anb) {
            $response['status'] = false;
            $response['message'] = sprintf(trans('labels.you_can_redeem_maximum_x_sar'), $max_amount_redeemable_with_anb);
            echo json_encode($response);
            die();
        }

        return $total_amount_with_vat - $anb_amount;
    }

    public function reverse_mokafaa_for_temp_cancelled_expired_bookings_cronjob()
    {
        $count = 0;

        // reversing mokafaa temp table logs
        $mokafaa_logs = $this->page->get_all_pending_mokafaa_redeem_requests();
        if ($mokafaa_logs) {
            foreach ($mokafaa_logs as $log) {
                $res = $this->call_mokafaa_reverse_redeem_api($log->mokafaa_request);
                if ($res) {
                    $this->page->updateData('mokafaa_logs', ['status' => 'Reversed'], array('mokafaa_request' => $log->mokafaa_request));
                    $count++;
                }
            }
        }


        // reversing mokafaa for cancelled/expired bookings
        $booking_date = '2023-08-01 00:00:00'; // we will only check bookings which were placed after this datetime
        $query = DB::table('booking')
            ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
            ->where('booking.sync', '!=', 'N')
            ->where('booking_payment.mokafaa_request', '!=', '')
            ->where('booking_payment.is_mokafaa_reversed', 0)
            ->where('booking.created_at', '>=', $booking_date)
            ->where(function ($query) {
                $query->where('booking.booking_status', 'Cancelled');
                $query->orWhere('booking.booking_status', 'Expired');
            })
            ->select('booking.id', 'booking_payment.mokafaa_request');
        $bookings = $query->get();
        foreach ($bookings as $booking) {
            $res = $this->call_mokafaa_reverse_redeem_api($booking->mokafaa_request);
            if ($res) {
                $this->page->updateData('booking_payment', ['is_mokafaa_reversed' => 1], ['booking_id' => $booking->id]);
                $this->page->updateData('mokafaa_logs', ['status' => 'Reversed after booking'], ['mokafaa_request' => $booking->mokafaa_request]);
                $count++;
            }
        }

        if ($count > 0) {
            echo 'Total ' . $count . ' transactions reversed successfully.';
            exit();
        } else {
            echo 'No transactions to reverse.';
            exit();
        }
    }

    private function call_mokafaa_reverse_redeem_api($transaction_id)
    {
        $return = false;
        if ($transaction_id != "") {
            $api_settings = custom::api_settings();
            $curlResponse = $this->sendCurlRequest($api_settings->mokafaa_api_base_url . 'ReverseRedeem?transactionId=' . $transaction_id);
            if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
                $return = true;
            }
        }
        return $return;
    }

    private function clear_mokafaa_after_booking_confirmed($booking_id, $updated_from = '')
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        if ($booking_payment_detail && $booking_payment_detail->mokafaa_request != "") {
            $this->page->updateData('mokafaa_logs', ['status' => 'Copied', 'updated_from' => $updated_from], array('mokafaa_request' => $booking_payment_detail->mokafaa_request));
        }
    }

    public function reverse_anb_for_temp_cancelled_expired_bookings_cronjob()
    {
        $count = 0;

        // reversing anb temp table logs
        $anb_logs = $this->page->get_all_pending_anb_redeem_requests();
        if ($anb_logs) {
            foreach ($anb_logs as $log) {
                $res = $this->call_anb_reverse_redeem_api($log->anb_request);
                if ($res) {
                    $this->page->updateData('anb_logs', ['status' => 'Reversed'], array('anb_request' => $log->anb_request));
                    $count++;
                }
            }
        }


        // reversing anb for cancelled/expired bookings
        $booking_date = '2023-08-01 00:00:00'; // we will only check bookings which were placed after this datetime
        $query = DB::table('booking')
            ->join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
            ->where('booking.sync', '!=', 'N')
            ->where('booking_payment.anb_request', '!=', '')
            ->where('booking_payment.is_anb_reversed', 0)
            ->where('booking.created_at', '>=', $booking_date)
            ->where(function ($query) {
                $query->where('booking.booking_status', 'Cancelled');
                $query->orWhere('booking.booking_status', 'Expired');
            })
            ->select('booking.id', 'booking_payment.anb_request');
        $bookings = $query->get();
        foreach ($bookings as $booking) {
            $res = $this->call_anb_reverse_redeem_api($booking->anb_request);
            if ($res) {
                $this->page->updateData('booking_payment', ['is_anb_reversed' => 1], ['booking_id' => $booking->id]);
                $this->page->updateData('anb_logs', ['status' => 'Reversed after booking'], ['anb_request' => $booking->anb_request]);
                $count++;
            }
        }

        if ($count > 0) {
            echo 'Total ' . $count . ' transactions reversed successfully.';
            exit();
        } else {
            echo 'No transactions to reverse.';
            exit();
        }
    }

    private function call_anb_reverse_redeem_api($transaction_id)
    {
        $return = false;
        if ($transaction_id != "") {
            $api_settings = custom::api_settings();
            $curlResponse = $this->sendCurlRequest($api_settings->anb_api_base_url . 'ReverseRedeem?transaction_id=' . $transaction_id);
            if (stripos($curlResponse, 'Error') === false && stripos(strip_tags($curlResponse), 'Origin Down') === false && stripos(strip_tags($curlResponse), 'Origin Connection Time-out') === false) {
                $return = true;
            }
        }
        return $return;
    }

    private function clear_anb_after_booking_confirmed($booking_id, $updated_from = '')
    {
        $booking_payment_detail = $this->page->getSingle('booking_payment', array('booking_id' => $booking_id));
        if ($booking_payment_detail && $booking_payment_detail->anb_request != "") {
            $this->page->updateData('anb_logs', ['status' => 'Copied', 'updated_from' => $updated_from], array('anb_request' => $booking_payment_detail->anb_request));
        }
    }

    public function sta()
    {
        try {
            $data['content'] = (array)$this->page->getSingle('sta_page', array('id' => '1'));
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['active_menu'] = 'sta';
            return view('frontend.sta', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function verify(Request $request) {
        try {
            $user_id = custom::jwt_decode($request->user_id);

            if ($request->type == 'email') $update['is_email_verified'] = 1;
            if ($request->type == 'phone') $update['is_phone_verified'] = 1;
            if (isset($update)) $this->page->updateData('users', $update, ['id' => $user_id]);

            $message = '';
            $user = $this->page->getSingle('users', ['id' => $user_id]);
            if ($user->is_email_verified == 1 && $user->is_phone_verified == 1) {
                $message = ($this->lang == 'eng' ? 'Your email address and mobile number are verified successfully. Your account is activated.' : 'تم التحقق من عنوان بريدك الإلكتروني ورقم هاتفك المحمول بنجاح. تم تفعيل حسابك.');
            } elseif ($user->is_email_verified == 1 && $user->is_phone_verified == 0) {
                $message = ($this->lang == 'eng' ? 'Your email address is verified successfully.' : 'تم التحقق من عنوان بريدك الإلكتروني بنجاح.');
            } elseif ($user->is_email_verified == 0 && $user->is_phone_verified == 1) {
                $message = ($this->lang == 'eng' ? 'Your mobile number is verified successfully.' : 'تم التحقق من رقم هاتفك المحمول بنجاح.');
            }

            Session::put('flash_message', $message);
            Session::save();

            return redirect($this->lang_base_url);
        } catch (\Exception $e) {
            echo 'Something went wrong!';die;
        }
    }

    public function refer_and_earn()
    {
        try {
            $toBack = custom::checkIfUserLoggedin($this->lang);
            if ($toBack != "") {
                return redirect($this->lang_base_url . '/my-profile');
            }
            $siteSettings = custom::site_settings();
            if($siteSettings->refer_and_earn_option == 'off') {
                return redirect($this->lang_base_url . '/my-profile');
            }
            $user_type = Session::get('user_type');
            if ($user_type == 'individual_customer') {
                $customer_id = Session::get('individual_customer_id');
            } else {
                $customer_id = Session::get('corporate_customer_id');
            }
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['lang'] = $this->lang;
            $data['content'] = (array)$this->page->getSingle('meta_pages', array('page' => 'refer_and_earn'));
            $data['active_menu'] = 'profile';
            $data['data'] = custom::refer_and_earn_data($customer_id, $this->lang, 'website', true);
            if (!$data['data']['status']) {
                return redirect($this->lang_base_url . '/my-profile');
            }
            if (custom::is_mobile()) {
                return view('frontend/mobile/refer_and_earn', $data);
            } else {
                return view('frontend/refer_and_earn', $data);
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function applyCouponForAddPayment(Request $request) {
        $coupon = DB::table('promotion_offer_coupon')->where('code', $request->coupon_code_for_add_payment)->first();
        if ($coupon) {
            $promotion_offer = DB::table('promotion_offer')->where('id', $coupon->promotion_offer_id)->where('is_for_refer_and_earn', 0)->first();
            if ($promotion_offer && ($promotion_offer->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types') && $promotion_offer->allow_on_extend_payment == 1) {

                $pickup_date = date('Y-m-d');
                $customer_id_no = false;
                $coupon_is_valid_for_pickup_day = custom::is_promotion_valid_for_pickup_day($promotion_offer, $pickup_date);

                $booking_individual_guest = $this->page->getSingle('booking_individual_guest', ['booking_id' => $request->booking_id]);
                $booking_individual_user = $this->page->getSingle('booking_individual_user', ['booking_id' => $request->booking_id]);

                if ($booking_individual_guest) {
                    $individual_guest = $this->page->getSingle('individual_customer', ['id' => $booking_individual_guest->individual_customer_id]);
                    $customer_id_no = $individual_guest->id_no;
                } elseif ($booking_individual_user) {
                    $individual_user = $this->page->getSingle('individual_customer', ['uid' => $booking_individual_user->uid]);
                    $customer_id_no = $individual_user->id_no;
                }

                if ($customer_id_no) {
                    $is_coupon_usage_fine = custom::is_coupon_usage_fine($request->coupon_code_for_add_payment, $customer_id_no, $this->lang);

                    if ($coupon_is_valid_for_pickup_day && $is_coupon_usage_fine['status'] == true) {

                        $booking_payment_data['promotion_offer_id'] = $promotion_offer->id;
                        $booking_payment_data['promotion_offer_code_used'] = $request->coupon_code_for_add_payment;
                        $this->page->updateData('booking_payment', $booking_payment_data, ['booking_id' => $request->booking_id]);

                        echo json_encode(['status' => true, 'amount' => $promotion_offer->discount]);
                        die;
                    }
                }
            }
        }
        echo json_encode(['status' => false, 'amount' => false]);die;
    }

    public function sendEditBookingOTP(Request $request) {
        try {
            session()->put('bidForEdit', $request->bid);
            $bid = custom::decode_with_jwt($request->bid);
            if (session()->get('individual_customer_id') != null) {
                $userId = session()->get('individual_customer_id');
            } else {
                $booking_details = $this->page->getSingle('booking', array('id' => $bid));
                if ($booking_details->type == "corporate_customer") {

                    $booking_corporate_customer = $this->page->getSingle("booking_corporate_customer", array("booking_id" => $bid));
                    $uid = $booking_corporate_customer->uid;
                    // $corporate_customer = $this->page->getSingle("corporate_customer", array("uid" => $uid));
                    $corporate_customer = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$uid.', uid)')->first();
                    $userId = $corporate_customer->id;

                } elseif ($booking_details->type == "individual_customer") {
                    $booking_individual_user = $this->page->getSingle("booking_individual_user", array("booking_id" => $bid));
                    $uid = $booking_individual_user->uid;
                    $individual_customer = $this->page->getSingle("individual_customer", array("uid" => $uid));
                    $userId = $individual_customer->id;
                } else {
                    $booking_individual_guest = $this->page->getSingle("booking_individual_guest", array("booking_id" => $bid));
                    $userId = $booking_individual_guest->individual_customer_id;
                }
            }
            $smsSent = $this->sendVerificationCodeViaWhatsapp($userId, $bid, false);
            if (is_bool($smsSent) == true && $smsSent == true) {
                $response['status'] = true;
                $response['message'] = ($this->lang == 'eng' ? 'A verification code is sent via SMS' : 'تم إرسال رقم التحقق عن طريق رسالة قصيرة');
            } else {
                $response['status'] = false;
                $response['message'] = $smsSent;
            }
            return response()->json($response);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function create_corporate_invoice_api(Request $request) {
        try {
            $invoice = $request->all();
            // custom::dump($invoice);

            $isCustomerExist = $this->page->getSingle('corporate_customer', array('company_code' => $invoice['customer_code']));
            if ($isCustomerExist) {

                $corporate_invoices_contract_exist_with_oasis_id = $this->page->getSingle('corporate_invoices_contract', ['oasis_id' => $invoice['oasis_id']]);

                if ($corporate_invoices_contract_exist_with_oasis_id) {
                    echo 'Error: OASIS ID already exist!';die;
                }

                // Preparing data for customer information
                $customer_data['company_code'] = $invoice['customer_code'];
                $customer_data['inv_customer_name'] = $invoice['customer_name'];
                $customer_data['inv_customer_address'] = $invoice['customer_address'];
                $customer_data['inv_customer_vat_id'] = $invoice['customer_vat_id'];
                $customer_data['inv_customer_po_box'] = $invoice['customer_po_box'];
                $customer_data['created_at'] = date('Y-m-d H:i:s');
                $customer_data['updated_at'] = date('Y-m-d H:i:s');

                // Updating customer information
                $this->page->updateData('corporate_customer', $customer_data, array('company_code' => $invoice['customer_code']));

                // Preparing data for corporate_invoices table
                $invoiceData['customer_id'] = $isCustomerExist->id;
                $invoiceData['invoice_no'] = $invoice['invoice_no'];
                $invoiceData['invoice_issue_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_issue_date'])));
                $invoiceData['invoice_deserved_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_deserved_date'])));
                $invoiceData['created_at'] = date('Y-m-d H:i:s');
                $invoiceData['updated_at'] = date('Y-m-d H:i:s');

                // Saving/Updating data into corporate_invoices table
                $isInvoiceExist = $this->page->getSingle('corporate_invoices', array('invoice_no' => $invoice['invoice_no']));
                if ($isInvoiceExist) {
                    $this->page->updateData('corporate_invoices', $invoiceData, array('invoice_no' => $invoice['invoice_no']));
                    $contractData['invoice_id'] = $isInvoiceExist->id;
                } else {
                    $contractData['invoice_id'] = $this->page->saveData('corporate_invoices', $invoiceData);
                }

                // Preparing data for corporate_invoices_contract table
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
                $contractData['oasis_id'] = $invoice['oasis_id'];

                // Saving/Updating data into corporate_invoices_contract table
                $corporate_invoices_contract_id = $this->page->saveData('corporate_invoices_contract', $contractData);

                echo $corporate_invoices_contract_id;die;

            } else {
                echo 'Error: Company does not exist in the database!';die;
            }

        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();die;
        }
    }

    public function update_corporate_invoice_api(Request $request) {
        try {
            $invoice = $request->all();

            $corporate_invoices_contract = $this->page->getSingle('corporate_invoices_contract', array('id' => $invoice['corporate_invoices_contract_id']));

            if ($corporate_invoices_contract) {

                $customer_data['company_code'] = $invoice['customer_code'];
                $customer_data['inv_customer_name'] = $invoice['customer_name'];
                $customer_data['inv_customer_address'] = $invoice['customer_address'];
                $customer_data['inv_customer_vat_id'] = $invoice['customer_vat_id'];
                $customer_data['inv_customer_po_box'] = $invoice['customer_po_box'];
                $customer_data['updated_at'] = date('Y-m-d H:i:s');
                $this->page->updateData('corporate_customer', $customer_data, array('company_code' => $invoice['customer_code']));

                $invoiceData['invoice_no'] = $invoice['invoice_no'];
                $invoiceData['invoice_issue_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_issue_date'])));
                $invoiceData['invoice_deserved_date'] = date("Y-m-d", strtotime(custom::convertDateFormat($invoice['invoice_deserved_date'])));
                $invoiceData['updated_at'] = date('Y-m-d H:i:s');
                $this->page->updateData('corporate_invoices', $invoiceData, array('id' => $corporate_invoices_contract->invoice_id));

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
                $this->page->updateData('corporate_invoices_contract', $contractData, array('id' => $corporate_invoices_contract->id));

                echo 1;die;
            } else {
                echo "Error: No data found against this ID!";
            }

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function subscribe_device_tokens_to_fcm_topic()
    {
        $ids_array = [];
        $device_tokens_array = [];

        $device_tokens = $this->page->getMultipleRowsWithLimit('device_token', ['token_status' => 'Active', 'is_subscribed_to_topic' => 0], 'id', 'asc', 999);
        foreach ($device_tokens as $device_token) {
            $ids_array[] = $device_token->id;
            $device_tokens_array[] = $device_token->fcm_token;
        }

        $response = custom::subscribe_device_to_fcm_topic($device_tokens_array, true);
        if ($response['status'] == true) {
            $this->page->updateDataInBatch('device_token', ['is_subscribed_to_topic' => 1], 'id', $ids_array);
            echo 'Cronjob completed successfully!.';
            exit();
        } else {
            echo $response['message'];
            exit();
        }
    }

    public function login_api(Request $request)
    {
        $credentials = $request->all();
        $credentials['email'] = urldecode($credentials['email']);
        $credentials['password'] = urldecode($credentials['password']);
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'type' => 'admin', 'active_status' => 'active'])) {
            $user = Auth::user();

            return response()->json([
                'success' => 1,
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'success' => 0,
            'user_id' => '',
        ]);
    }

    public function update_delivery_booking_status(Request $request)
    {
        $data = $request->all();
        $booking = $this->page->getSingle('booking', ['id' => $data['booking_id']]);
        if ($booking) {
            $delivery_booking_status = (isset($data['status']) && $data['status'] && custom::delivery_booking_statuses($data['status']) ? $data['status'] : '');
            $updated = $this->page->updateData('booking', ['delivery_booking_status' => $delivery_booking_status ? strtoupper($delivery_booking_status) : null], ['id' => $data['booking_id']]);
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Delivery booking status updated successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found!',
            ]);
        }
    }

    public function get_branch_delivery_coordinates(Request $request) {
        $page = new \App\Models\Admin\Page();
        $coord_arra = array();
        $branch_id = $request->branch_id;
        $coordinates = $page->getDeliveryCoordinatesForBranch($branch_id);
        if ($coordinates) {
            foreach ($coordinates as $coordinate) {
                $coord_arra[] = $coordinate->coordinates;
            }
            $coord_str = implode('|', $coord_arra);
            $status = true;
        } else {
            $coord_str = '';
            $status = false;
        }

        $responseArray['status'] = $status;
        $responseArray['coordinates'] = $coord_str;
        echo json_encode($responseArray);
        exit();
    }

    public function set_utilization(Request $request) { // branch, car_type, car_model, utilization_percentage, addition_or_subtraction_percentage
        try {

            $rules = [
                'branch' => 'bail|required',
                'car_type' => 'bail|required',
                'car_model' => 'bail|required',
                'utilization_percentage' => 'bail|required',
                'addition_or_subtraction_percentage' => 'bail|required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                echo 0;die;
            }

            $data = $request->all();
            $already_exists = $this->page->getSingle('setting_car_utilization_setup', ['branch' => $data['branch'], 'car_type' => $data['car_type'], 'car_model' => $data['car_model']]);
            if ($already_exists) { // then update
                $update_data = ['utilization_percentage' => $data['utilization_percentage'], 'addition_or_subtraction_percentage' => $data['addition_or_subtraction_percentage'], 'last_amend_date' => date('Y-m-d H:i:s')];
                $this->page->updateData('setting_car_utilization_setup', $update_data, ['id' => $already_exists->id]);
                echo 1;die;
            } else { // save
                $data['last_amend_date'] = date('Y-m-d H:i:s');
                $saved = $this->page->saveData('setting_car_utilization_setup', $data);
                if ($saved) {
                    echo 1;die;
                } else {
                    echo 0;die;
                }
            }
        } catch (\Exception $e) {
            echo 0;die;
        }
    }

    public function setOasisLimoSurveyPendingToFillLink()
    {
        try {
            $lang = $_REQUEST['lang']; // AR, EN
            $data['contract_no'] = $_REQUEST['contract_no'];
            $data['name'] = $_REQUEST['name'];
            $data['mobile_no'] = $_REQUEST['mobile_no'];
            if (isset($_REQUEST['id_no'])) {
                $data['id_no'] = $_REQUEST['id_no'];
                $data['from_location'] = $_REQUEST['from_location'];
                $data['to_location'] = $_REQUEST['to_location'];
                $data['car_type'] = $_REQUEST['car_type'];
                $data['car_model'] = $_REQUEST['car_model'];
                $data['from_date'] = $_REQUEST['from_date'];
                $data['to_date'] = $_REQUEST['to_date'];
                $data['total_payment'] = $_REQUEST['total_payment'];
                //05-Nov-2018
                //We need to add new column in oasis survey called (status) and it will be (C from close) (O from open)
                $data['booking_status'] = $_REQUEST['booking_status'];

                // new keys
                $data['opened_region'] = $_REQUEST['opened_region'];
                $data['opened_city'] = $_REQUEST['opened_city'];
                $data['close_region'] = $_REQUEST['close_region'];
                $data['close_city'] = $_REQUEST['close_city'];
                $data['booking_id'] = $_REQUEST['booking_id']; // NULL in case of walk-in
                $data['booking_source'] = $_REQUEST['booking_source'];
                $data['is_delivery'] = $_REQUEST['is_delivery'];
                $data['is_subscription'] = $_REQUEST['is_subscription'];
                $data['staff_id'] = $_REQUEST['staff_id'];
                $data['staff_name'] = $_REQUEST['staff_name'];
                $data['contract_opened_date_time'] = $_REQUEST['contract_opened_date_time'];
                $data['contract_closed_date_time'] = $_REQUEST['contract_closed_date_time'];

                $data['company_id_no'] = $_REQUEST['company_id_no'];
                $data['company_name'] = $_REQUEST['company_name'];
                $data['driver_id'] = $_REQUEST['driver_id'];
                $data['driver_name'] = $_REQUEST['driver_name'];
            }
            $data['survey_filled_status'] = 'no';
            $data['is_skipped'] = 'no';
            $data['created_at'] = date('Y-m-d H:i:s');
            $alreadyExist = $this->page->getSingle('oasis_survey_filled_status_for_limousine', array('contract_no' => $data['contract_no'], 'booking_status' => $data['booking_status']));
            if (!$alreadyExist) {
                $saved = $this->page->saveData('oasis_survey_filled_status_for_limousine', $data);
                if ($saved > 0) {
                    if (strtolower($lang) == 'en') {
                        $surveyLink = custom::baseurl('/') . '/en/limousine-oasis-survey?ref=' . base64_encode($data['contract_no']) . '&booking_status=' . $data['booking_status'];
                    } else {
                        $surveyLink = custom::baseurl('/') . '/limousine-oasis-survey?ref=' . base64_encode($data['contract_no']) . '&booking_status=' . $data['booking_status'];
                    }
                    return $surveyLink;
                }
            }
            return 0;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function oasisSurveyForLimousine()
    {
        try {
            $contract_no = base64_decode($_GET['ref']);
            $booking_status = $_GET['booking_status'];
            // checking if user has already submitted this survey
            $data['filled_status'] = 0;
            $data['lang'] = $this->lang;
            $feedbackAlreadySubmitted = $this->page->getRowsCount('oasis_survey_feedback_for_limousine', array('contract_no' => $contract_no, 'booking_status' => $booking_status));
            if ($feedbackAlreadySubmitted > 0) {
                $data['filled_status'] = 1;
                $data['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
            }
            $data['emojis'] = $this->page->getAll('survey_emoji', 'sort_col');
            $data['base_url'] = $this->base_url;
            $data['lang_base_url'] = $this->lang_base_url;
            $data['active_menu'] = 'home';
            $data['contract_no'] = $contract_no;
            $data['booking_status'] = $booking_status;
            return view('frontend.oasis_survey_for_limousine', $data);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function saveOasisSurveyFeedbackForLimousine(Request $request) // customer_id, booking_id, emoji_desc, category_desc, question_desc, answer_desc, emoji_id, category_id, option_id
    {
        try {
            $posted_data = $request->input();

            // checking if user has already submitted this survey
            $feedbackAlreadySubmitted = $this->page->getRowsCount('oasis_survey_feedback_for_limousine', array('contract_no' => $posted_data['contract_no'], 'booking_status' => $posted_data['booking_status']));
            if ($feedbackAlreadySubmitted > 0) {
                $responseArray['status'] = 2;
                $responseArray['message'] = ($this->lang == 'eng' ? 'Feedback already submitted against this survey.' : 'شكرا لوقتك، تم تعبئة التقييم من قبل');
                echo json_encode($responseArray);
                exit();
            } else {

                $questions_count = $posted_data['questions_count'];
                unset($posted_data['questions_count']);

                for ($i = 1; $i <= $questions_count; $i++) {
                    $question_val = $posted_data['question-' . $i];
                    $question_val_exploded = explode('-', $question_val);
                    $posted_data[$question_val_exploded[0]] = $question_val_exploded[1];
                    unset($posted_data['question-' . $i]);
                }

                if (isset($posted_data['comment']) && $posted_data['comment']) {
                    $posted_data['comment'] = custom::removeEmojis($posted_data['comment']);
                }

                $posted_data['created_at'] = date('Y-m-d H:i:s');

                // syncing data with oasis
                $api_data['contract_no'] = $posted_data['contract_no'];
                $api_data['contract_status'] = $posted_data['booking_status'];
                $api_data['q1_answer'] = $posted_data['cleanliness_of_the_vehicle_internally_and_externally'];
                $api_data['q2_answer'] = $posted_data['driver_commitment_to_uniform'];
                $api_data['q3_answer'] = $posted_data['driver_commitment_to_personal_hygiene'];
                $api_data['q4_answer'] = $posted_data['commitment_to_timing'];
                $api_data['q5_answer'] = $posted_data['commitment_to_traffic_rules_and_safety'];
                $query_params = http_build_query($api_data);
                $api_url = 'http://api2.keyrac.sa:8080/KeyBookingService/SaveLimousineSurvay?' . $query_params;

                $saved_id = $this->page->saveData('oasis_survey_feedback_for_limousine', $posted_data);
                if ($saved_id > 0) {
                    $updateBy['contract_no'] = $posted_data['contract_no'];
                    $updateBy['booking_status'] = $posted_data['booking_status'];
                    $update['survey_filled_status'] = 'yes';
                    $update['updated_at'] = date('Y-m-d H:i:s');
                    $check1 = $this->page->updateData('oasis_survey_filled_status_for_limousine', $update, $updateBy);

                    custom::call_oasis_api_with_url($api_url);

                    $responseArray['status'] = 1;
                    $responseArray['message'] = ($this->lang == 'eng' ? 'Thank You For Taking Your Time To Fill The Survey.' : 'شكرا ً لأخذ وقتك لملء هذا الاستبيان.');
                    echo json_encode($responseArray);
                    exit();
                } else {
                    $responseArray['status'] = 0;
                    $responseArray['message'] = ($this->lang == 'eng' ? 'Survey failed to submit. Please try again.' : 'لقد فشلت في تقديم الاستبيان. يرجى إعادة المحاولة مرة اخرى');
                    echo json_encode($responseArray);
                    exit();
                }
            }
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function carSellingFormEventForGtag(Request $request) {
        $event = 'form_start';
        $event_data = [
            'form_id' => 'interestedInCarForm'. $request->car_id,
            'form_name' => 'interestedInCarForm'. $request->car_id,
            'form_destination' => $this->lang_base_url . '/interestedInCar',
        ];
        custom::sendEventToGA4($event, $event_data);
        echo 1;die;
    }

    public function offerDetail(Request $request, $offerId) {
        $offer_details = $this->page->getSingle('promotion_offer', ['id' => $offerId]);
        if (!$offer_details) {
            die;
        }
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['content'] = (array)$offer_details;
        $data['lang'] = $this->lang;
        $data['active_menu'] = 'fleet';
        return view('frontend.offer-detail', $data);
    }

}

?>