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
use Image;

class HumanLessController extends Controller
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
        DB::enableQueryLog();
        $this->page = new Page();
        $this->pdf = App::make('snappy.pdf.wrapper');
        $this->base_url = custom::baseurl('/');
        $site = custom::site_settings();
        if ($site->site_language == 'both') {
            $segments = $request->segments();
            if (isset($segments[0]) && $segments[0] == 'en') {
                $this->lang_base_url = custom::baseurl('/') . '/en';
                $language = 'eng';
            } else {
                $this->lang_base_url = custom::baseurl('/');
                $language = 'arb';
            }
        } else {
            $this->lang_base_url = custom::baseurl('/');
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

    public function humanLess_sendAcknowledgeSMS()
    {
        $data = array();
        $reservation_code = $_REQUEST['reservation_code'];
        $bookingDetail = $this->page->getSingle('booking', array('reservation_code' => $reservation_code));
        $booking_id = $bookingDetail ? $bookingDetail->id : 0;
        $mobile = $_REQUEST['mobile'];
        $lang = $_REQUEST['lang'];

        if ($reservation_code != '' && $mobile != '' && $lang != '') {
            if ($lang == 'eng') {
                $sms_text = 'Dear Customer, ' . "\n";
                $sms_text .= 'Now you can pick up & drop off your car without going to the counter, please click on the below link and follow the steps. ' . "\n";
                $lang_segment = '/en';
            } else {
                $sms_text = "عزيزي العميل، يمكنك الآن إستلام وتسليم سيارتك من دون الذهاب إلى مكتب التأجير، كل ما عليك الدخول على الرابط التالي و اتباع الخطوات الموضحة" . "\n";
                $lang_segment = '';
            }

            $detail_link = $this->base_url . $lang_segment . '/manage-booking/' . custom::encode_with_jwt($booking_id);
            //$acknowledge_short_link = custom::fireBaseShortLink($acknowledge_link);
            $sms_text .= $detail_link;

            custom::sendSMS($mobile, $sms_text);

            //$data['human_less_state'] = 'SMS Sent';
            $data['human_less_state'] = 'Acknowledged';
            $update_by['reservation_code'] = $reservation_code;
            $this->page->updateData('booking', $data, $update_by);
            echo 1;
            exit();
        } else {
            echo 0;
            exit();
        }
    }

    public function humanLessAcknowledge() //Not using now, human less state is updating via SMS (above function)
    {
        $data = array();
        //$reservation_code = base64_decode($_REQUEST['r']);
        $reservation_code = $_REQUEST['r'];
        if ($reservation_code != '') {
            $update_data['human_less_state'] = 'Acknowledged';
            $update_by['reservation_code'] = $reservation_code;
            $this->page->updateData('booking', $update_data, $update_by);
            echo 1;
            exit();
        } else {
            echo 0;
            exit();
        }

        /*$data['active_menu'] = '';
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        if(custom::is_mobile()){
            return view('frontend.mobile.human_less_acknowledge_page', $data);
        }else {
            return view('frontend.human_less_acknowledge_page', $data);
        }*/
    }

    /*Get Car*/
    public function get_car($booking_id) // This is for logged in user
    {
        $checkUserBooking = false;
        $user_id = Session::get('user_id');
        $booking_id = base64_decode($booking_id);
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $checkOpenContract = $this->page->getSingle('booking', array('id' => $booking_id, 'human_less_state' => 'Car Taken'));

        $toBack = custom::checkIfUserLoggedinHL($this->lang);
        if ($toBack != "") {
            return redirect()->to($toBack);
        }
        $data['booking_id'] = $booking_id;
        $data['oasis_booking_id'] = $bookingDetail->reservation_code;
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['active_menu'] = '';

        if ($bookingDetail->type == 'corporate_customer') {
            $data['booking_detail'] = $this->page->getSingleBookingDetailsForCorporate($booking_id);
            $checkUserBooking = $this->page->getSingle('booking_corporate_customer', array('uid' => $user_id, 'booking_id' => $booking_id));
        } else {
            $data['booking_detail'] = $this->page->getSingleBookingDetails($booking_id);
            $checkUserBooking = $this->page->getSingle('booking_individual_user', array('uid' => $user_id, 'booking_id' => $booking_id));
        }

        if ($checkUserBooking == false) {
            return redirect()->to($toBack);
        }
        if (!isset($data['booking_detail']) || $data['booking_detail'] == '' || $checkOpenContract) {
            return redirect()->to($toBack);
        }
        /*echo '<pre>';
        print_r($data['booking_detail']);exit;*/
        //To make dynamic flow get following 3 values from $bookingDetail
        //$branch_code = 'RR5001';
        //$car_type = '27226';
        //$car_model = '2018';

        //dynamic values
        $branch_code = $data['booking_detail']->branch_from_oracle_id;
        $car_type = $data['booking_detail']->car_type_oracle_id;
        $car_model = $data['booking_detail']->year;

        $from_branch_id = $data['booking_detail']->from_location;
        $branchDetail = $this->page->getSingle('branch', array('id' => $from_branch_id));
        $data['parking_area_image'] = $branchDetail->parking_area_image;

        $data['mobile_no'] = $data['booking_detail']->mobile_no;
        $data['branch_code'] = $branch_code;
        $data['car_type'] = $car_type;
        $data['car_model'] = $car_model;
        $data['inspection_mode'] = 'pickup';

        $data['inspection'] = DB::table('inspection')->where('contract_id', $data['booking_detail']->reservation_code)->where('type', 0)->first();
        if (!$data['inspection']) {
            $inspection_data['type'] = 0;
            $inspection_data['image'] = "";
            $inspection_data['contract_id'] = $data['booking_detail']->reservation_code;
            $inspection_data['created_at'] = date('Y-m-d H:i:s');
            $data['inspection_id'] = $this->page->saveData('inspection', $inspection_data);
        } else {
            $data['inspection_id'] = $data['inspection']->id;
        }

        $issue_tamm = DB::table('tamm')->where('booking_id', $booking_id)->first();
        if (!$issue_tamm) {
            $tamm_data['booking_id'] = $booking_id;
            $tamm_data['created_at'] = date('Y-m-d H:i:s');
            $this->page->saveData('tamm', $tamm_data);
        }

        if (custom::is_mobile()) {
            return view('frontend/mobile/human_less_get_car', $data);
        } else {
            return view('frontend/human_less_get_car', $data);
        }
    }

    public function getCarPlates(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $branch_code = $request->input('branchCode');
        $car_type = $request->input('carType');
        $car_model = $request->input('carModel');

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <getCarsList>
             <branch>' . $branch_code . '</branch>
             <carType>' . $car_type . '</carType>
             <carModel>' . $car_model . '</carModel>
            </getCarsList>
        ');
        try {
            $response = $soapclient->getCarsList($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Car List API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        if (isset($response) && $response->return->returnCode == 0 && $isError == false) {
            //this is success case
            $responseMsg = $response->return->returnMessage;
            $carsPlateList = $response->return->returnResult;
            $carsPlate_html = '';
            if (is_object($carsPlateList)) {
                $carColor = $this->page->getSingle('car_color_codes', array('oasis_code' => $carsPlateList->carColor));
                $css_color_code = $carColor->css_code != '' ? $carColor->css_code : 'FFFFFF';
                $carsPlate_html .= '<li>
                                        <input id="plate" class="carPlate" type="radio" name="plate_no" value="' . $carsPlateList->plateNo . '">
                                        <label for="plate" class="plateLable">
                                            <span class="car-color" style="background-color: ' . $css_color_code . '">' . $carColor->color_desc . '</span>
                                            <h3>' . $carsPlateList->plateNo . '</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>';
            } else {
                $k = 1;
                foreach ($carsPlateList as $car):
                    $carColor = $this->page->getSingle('car_color_codes', array('oasis_code' => $car->carColor));
                    $css_color_code = $carColor->css_code != '' ? $carColor->css_code : 'FFFFFF';
                    $carsPlate_html .= '<li>
                                        <input id="plate' . $k . '" class="carPlate" type="radio" name="plate_no" value="' . $car->plateNo . '">
                                        <label for="plate' . $k . '" class="plateLable">
                                            <span class="car-color" style="background-color: ' . $css_color_code . '">' . $carColor->color_desc . '</span>
                                            <h3>' . $car->plateNo . '</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>';
                    $k++;
                endforeach;
            }


        } else {
            //this is fail case
            $carsPlate_html = "";
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return->returnMessage;
            }

            if ($this->lang == 'eng') {
                $sms_text = "no cars available";
            } else {
                $sms_text = "no cars available";
            }

            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        $resp['results'] = $carsPlate_html;
        echo json_encode($resp);
        exit();
    }

    public function changeCar(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        if ($bookingDetail->type == 'corporate_customer') {
            $booking_detail = $this->page->getSingleBookingDetailsForCorporate($booking_id);
        } else {
            $booking_detail = $this->page->getSingleBookingDetails($booking_id);
        }
        if (!isset($booking_detail) || $booking_detail == '') {
            exit();
        }
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $branch_code = $request->input('branch_code');
        $car_type = $request->input('car_type');
        //$branch_code = 'RR5001';
        //$car_type = '32391';

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <getUpgradeCarTypes>
             <branch>' . $branch_code . '</branch>
             <carType>' . $car_type . '</carType>
            </getUpgradeCarTypes>
        ');
        try {
            $response = $soapclient->getUpgradeCarTypes($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Upgrade Car API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        //echo '<pre>';
        //print_r($response);
        //exit;
        if (isset($response) && $response->return->returnCode == 0 && $isError == false) {
            //this is success case


            $responseResult = $response->return->returnResult;
            $whereQC = " (";
            foreach ($responseResult as $k => $v) {
                if ($whereQC != " (") $whereQC .= " OR ";
                $whereQC .= " (cm.oracle_reference_number='" . $v->carType . "' and cm.year='" . $v->carModel . "') ";
            }
            $whereQC .= ") ";

            //fetch car models for change car option
            $search_by['region_id'] = $booking_detail->from_region_id;
            $search_by['city_id'] = $booking_detail->from_city_id;
            $search_by['branch_id'] = $booking_detail->from_location;
            $search_by['days'] = $booking_detail->no_of_days;
            $search_by['pickup_date'] = date('Y-m-d', strtotime($booking_detail->from_date));
            $search_by['customer_type'] = $booking_detail->type == 'individual_customer' ? 'Individual' : 'Corporate';
            $search_by['category'] = 0;
            //$modelId = $car_type;
            //$modelId = '32391';
            $limit = "10";
            $offset = 0;

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

            $loyalty_discount_percent = '';
            //overwrite corporate type to individual in this case
            if (custom::isCorporateLoyalty()) $customer_type_for_loyalty = "individual_customer";
            $loyalty_card_applicable = $this->page->getLoyaltyInfo($booking_detail->no_of_days, $loyalty_card_type_for_loyalty, $customer_type_for_loyalty);
            if ($loyalty_card_applicable) {
                $loyalty_discount_percent = $loyalty_card_applicable->discount_percent;
            }

            $car_price_sort = 'asc';
            $upgradeCars = $this->page->getUpgradeHumanLessCars($search_by, 'rent', $whereQC, $offset, $limit, $loyalty_discount_percent, $car_price_sort);
            $cars_html = custom::humanLessUpgradeCarsHtml($upgradeCars, $data['base_url'], $data['lang_base_url'], $data['lang'], $booking_detail);
            $responseMsg = $response->return->returnMessage;
        } else {
            //this is fail case
            $cars_html = "";
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return->returnMessage;
            }

            if ($this->lang == 'eng') {
                $sms_text = "no cars available";
            } else {
                $sms_text = "no cars available";
            }

            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        $resp['results'] = $cars_html;
        echo json_encode($resp);
        exit();
    }

    public function getCarInfo(Request $request)
    {
        $plate_no = $request->input('plate_no');
        $booking_id = $request->input('booking_id');
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <getCarInfo>
             <plateNo>' . $plate_no . '</plateNo>
            </getCarInfo>
        ');

        try {
            $response = $soapclient->getCarInfo($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Car List API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        if (isset($response) && $response->return->returnCode == 0 && $isError == false) {
            //this is success case
            $car_lastFuelTank = $response->return->returnResult->lastFuelTank;
            $car_lastKm = $response->return->returnResult->lastKm;
            $vehicleId = $response->return->returnResult->vehicleId;
            $responseMsg = $response->return->returnMessage;
            $fuel_html = '';
            $fuel_html .= '<div class="bar-active" style="direction: ltr;">';
            $fuel = (int)$car_lastFuelTank;
            //$fuel = 7;
            for ($f = 0; $f <= 8; $f++):
                $fuel_html .= '<div class="' . ($fuel > 0 && $f <= $fuel ? 'active' : '') . ' ' . ($fuel == $f ? 'current' : '') . '"><span>' . $f . '</span></div>';
            endfor;
            $fuel_html .= '</div>';

            $data['km'] = $car_lastKm;
            $data['fuel'] = $car_lastFuelTank;
            $data['plate_no'] = $plate_no;
            $update_by['contract_id'] = $bookingDetail->reservation_code;
            $this->page->updateData('inspection', $data, $update_by);
        } else {
            //this is fail case
            $fuel_html = '';
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return->returnMessage;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        $resp['lastFuelTank'] = $car_lastFuelTank;
        $resp['fuel_html'] = $fuel_html;
        $resp['lastKm'] = number_format($car_lastKm) . ' KM';
        $resp['vehicleId'] = $vehicleId;
        echo json_encode($resp);
        exit();
    }

    public function pickUpInspection($booking_id)
    {
        $booking_id = base64_decode($booking_id);
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $toBack = custom::checkIfUserLoggedinHL($this->lang);
        if ($toBack != "") {
            return redirect()->to($toBack);
        }

        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['active_menu'] = '';

        // $data['inspectionDetail'] = $this->page->getSingle('inspection', array('contract_id' => $bookingDetail->reservation_code,'type' => 0,'image !=',''));

        $data['inspectionDetail'] = DB::table('inspection')->where('contract_id', $bookingDetail->reservation_code)->where('type', 0)->where('image', '!=', '')->first();

        if ($data['inspectionDetail']) {
            if (custom::is_mobile()) {
                return view('frontend/mobile/pickup_inspection', $data);
            } else {
                return view('frontend/pickup_inspection', $data);
            }
        }
    }

    public function watermark(Request $request)
    {
        $plate_no = $request->input('getPlateNo');
        $lastKm = preg_replace('/[^0-9]/', '', $request->input('lastKm'));
        $lastFuelTank = $request->input('lastFuelTank');
        $inspection_id = $request->input('inspection_id');

        $watermark_array = json_decode($request->data);
        $obj = Image::make($request->src)->resize($request->org_img_width, null, function ($constraint) {
            //$obj = Image::make('public/frontend/inspection/images/mainImage.jpg')->resize($request->org_img_width, null, function ($constraint) { //video issue
            $constraint->aspectRatio();
        });
        foreach ($watermark_array as $array) {
            $watermark_detail = pathinfo($array->src);
            //$watermark_detail = pathinfo('public/frontend/inspection/images/testdhuka.jpeg'); //video issue
            $resize_watermark = 'public/frontend/inspection/images/watermark/resize/' . $watermark_detail['basename'];
            if (!in_array($watermark_detail['basename'], array("fade.png", "scratch.png", "damage.png"))) {
                Image::make($array->src)->resize((int)$array->w, (int)$array->h)->save($resize_watermark);
                //Image::make('public/frontend/inspection/images/testdhuka.jpeg')->resize((int)$array->w, (int)$array->h)->save($resize_watermark);//video issue
                DB::table('photo')->insert([
                    "inspection_id" => $inspection_id,
                    "image" => $array->src,
                    "x" => $array->x,
                    "y" => $array->y,
                    "w" => $array->w,
                    "h" => $array->h,
                ]);
            } else {
                Image::make('public/frontend/inspection/images/' . $watermark_detail['basename'])->resize((int)$array->w, (int)$array->h)->save($resize_watermark);
            }
            $obj = $obj->insert($resize_watermark, 'top-left', (int)$array->x, (int)$array->y);
        }

        custom::make_folder_empty('public/frontend/inspection/images/watermark/resize/');
        $base_64_encode = $obj->encode('data-url');
        $inspection = DB::table('inspection')->where('id', $request->inspection_id)->update([
            "image" => $base_64_encode,
            "w" => $request->w,
            "h" => $request->h,
            "km" => $lastKm,
            "fuel" => $lastFuelTank,
            "plate_no" => $plate_no,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);
        return response()->json([
            'success' => 1,
            'src' => $base_64_encode,
        ], 200);
    }

    public function issueTammOTP(Request $request)
    {
        $booking_id = $request->input('booking_id');
        //$id_no = $request->input('id_no');

        $booking_individual_user = $this->page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
        //use corporate customer details in future if human less will also required for corporate customers
        $individual_customer = $this->page->getSingle('individual_customer', array('uid' => $booking_individual_user->uid));
        $id_no = $individual_customer->id_no;
        $id_version = $individual_customer->id_version;
        $mobile_no = str_replace('+', '', $individual_customer->mobile_no);

        $resp = array();
        $responseMsg = "";
        $response = "";
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->tamm_otp_url, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev Human Less TAMM API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <sendOTP>
                 <nationalId>' . $id_no . '</nationalId>
                 <mobileNo>' . $mobile_no . '</mobileNo>
            </sendOTP>
        ');

        try {
            $response = $soapclient->sendOTP($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less TAMM'));
            $this->sendEmail('Catch Error In TAMM Send OTP API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        $returnCode = '';
        $returnCorrelationId = 0;
        if (isset($response) && $response->return->returnCode == 0 && $isError == false) {
            //this is success case
            $returnCode = $response->return->returnCode;
            $returnCorrelationId = $response->return->returnCorrelationId;
            $responseMsg = $response->return->returnMessage;
            $tamm_data['correlation_id'] = $returnCorrelationId;
            $tamm_data['updated_at'] = date('Y-m-d H:i:s');
            $update_by['booking_id'] = $booking_id;
            $this->page->updateData('tamm', $tamm_data, $update_by);
        } else if (isset($response) && $response->return->returnCode == 1 && $response->return->returnMessage == 'Invalid National Id') {
            $isError = true;
            $returnCode = 'invalidID';
            $responseMsg = $response->return->returnMessage;
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $returnCode = $response->return->returnCode;
                $responseMsg = $response->return->returnMessage;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['returnCode'] = $returnCode;
        $resp['message'] = $responseMsg;
        $resp['correlation_id'] = $returnCorrelationId;
        echo json_encode($resp);
        exit();
    }

    public function issueTammAuth(Request $request)
    {
        $lang = $this->lang;
        $booking_id = $request->input('booking_id');
        $input_id_version = $request->input('id_version');
        $input_id_no = $request->input('id_no');
        $changedPrice = $request->input('changedPrice');
        $changedCdw = $request->input('changedCdw');
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $contract_detail = $this->page->getSingle('inspection', array('contract_id' => $bookingDetail->reservation_code, 'type' => '0'));
        $booking_individual_user = $this->page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
        //use corporate customer details in future if human less will also required for corporate customers
        $individual_customer = $this->page->getSingle('individual_customer', array('uid' => $booking_individual_user->uid));
        $id_no = $input_id_no == '' ? $individual_customer->id_no : $input_id_no;
        $id_version = $input_id_version == '' ? $individual_customer->id_version : $input_id_version;
        $carPlate = $contract_detail->plate_no;
        $lastKm = $contract_detail->km;
        $lastFuelTank = $contract_detail->fuel;

        $greg_from_date = $bookingDetail->from_date;
        $hijri_from_date = custom::Greg2Hijri(date('d', strtotime($greg_from_date)), date('m', strtotime($greg_from_date)), date('Y', strtotime($greg_from_date)), true);
        $greg_to_date = $bookingDetail->to_date;
        $hijri_to_date = custom::Greg2Hijri(date('d', strtotime($greg_to_date)), date('m', strtotime($greg_to_date)), date('Y', strtotime($greg_to_date)), true);

        $mobile_no = str_replace('+', '', $individual_customer->mobile_no);
        $vehicle_id = $request->input('vehicle_id');
        $otp_code = $request->input('tamm_otp');
        $correlationId = $request->input('correlation_id');
        //for car plate, arabic characters length is 3 & numbers length is 4
        $plate_number = mb_substr($carPlate, 3, null, 'utf-8');
        $plate_text = mb_substr($carPlate, -7, 3, 'utf-8');
        $hijri_from_date = str_replace('-', '', $hijri_from_date);
        $hijri_to_date = str_replace('-', '', $hijri_to_date);

        $resp = array();
        $responseMsg = "";
        $response = "";
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->tamm_otp_url, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev Human Less Issue TAMM API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <issueAuthorization>
                 <authorizedId>' . $id_no . '</authorizedId>
                 <authorizedIdVersion>' . $id_version . '</authorizedIdVersion>
                 <authorizedMobileNo>' . $mobile_no . '</authorizedMobileNo>
                 <plateNumber>' . $plate_number . '</plateNumber>
                 <plateText>' . $plate_text . '</plateText>
                 <fromHijri>' . $hijri_from_date . '</fromHijri>
                 <toHijri>' . $hijri_to_date . '</toHijri>
                 <correlationId>' . $correlationId . '</correlationId>
                 <otpAuthenticationCode>' . $otp_code . '</otpAuthenticationCode>
              </issueAuthorization>
        ');
        try {
            $response = $soapclient->issueAuthorization($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less Issue TAMM'));
            $this->sendEmail('Catch Error In TAMM Issue API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        $returnCode = '';
        if (isset($response) && $response->return->returnCode == 0 && $isError == false) {
            //this is success case
            $returnCode = $response->return->returnCode;
            $returnAuthorizationNumber = $response->return->returnAuthorizationNumber;
            //$responseMsg = $response->return->returnMessage;
            $tamm_data['auth_number'] = $returnAuthorizationNumber;
            $tamm_data['status'] = 'issued';
            $tamm_data['updated_at'] = date('Y-m-d H:i:s');
            $update_by['booking_id'] = $booking_id;
            $this->page->updateData('tamm', $tamm_data, $update_by);

            //update id version in individual customer table
            if ($input_id_version != '') {
                $user_data['id_version'] = $id_version;
                $update_user_by['uid'] = $booking_individual_user->uid;
                $this->page->updateData('individual_customer', $user_data, $update_user_by);
            }
            if ($input_id_no != '') {
                $user_data['id_no'] = $input_id_no;
                $update_user_by['uid'] = $booking_individual_user->uid;
                $this->page->updateData('individual_customer', $user_data, $update_user_by);
            }
            $contract_no = $this->openContract($booking_id, $carPlate, $lastKm, $lastFuelTank, $correlationId, $returnAuthorizationNumber, $vehicle_id, $changedPrice, $changedCdw);
            if (ctype_alnum($contract_no)) {
                $isError = false;
                $resultMessage = '';
            } else {
                $isError = true;
                $resultMessage = $contract_no;
            }
            $responseMsg = $resultMessage;
        } else if (isset($response) && $response->return->returnCode == 6) {
            $isError = true;
            $returnCode = 'invalidID';
            $responseMsg = $lang == 'eng' ? $response->return->returnMessage : 'رقم الهوية/الإقامة غير صحيح';
        } else if (isset($response) && $response->return->returnCode == -1) {
            $isError = true;
            $returnCode = 'invalidIDVersion';
            $responseMsg = $lang == 'eng' ? $response->return->returnMessage : 'نسخة الهوية/ الإقامة غير صحيحة';
        } else {
            //this is fail case
            if (isset($response)) {
                $returnCode = $response->return->returnCode;
                $responseMsg = $response->return->returnMessage;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['returnCode'] = $returnCode;
        $resp['message'] = $responseMsg;
        echo json_encode($resp);
        exit();
    }

    private function openContract($booking_id, $carPlate, $lastKm, $lastFuelTank, $tammCorrelaionId, $tammAuthenticationNo, $vehicle_id, $changedPrice, $changedCdw)
    {
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $contract_id = $bookingDetail->reservation_code;
        $inspectionUrl = '';

        $inspectionDetail = DB::table('inspection')->where('contract_id', $contract_id)->where('type', 0)->where('image', '!=', '')->first();

        if ($inspectionDetail) {
            $lang_segment = $this->lang == 'eng' ? 'en/' : '';
            $inspectionUrl = $this->base_url . '/' . $lang_segment . 'vehicle-inspection/' . base64_encode($booking_id);
        }

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <openContract>
                <bookingId>' . $contract_id . '</bookingId>
                <plateNo>' . $carPlate . '</plateNo>
                <kmOut>' . $lastKm . '</kmOut>
                <fuelTankOut>' . $lastFuelTank . '</fuelTankOut>
                <tammCorrelaionId>' . $tammCorrelaionId . '</tammCorrelaionId>
                <tammAuthenticationNo>' . $tammAuthenticationNo . '</tammAuthenticationNo>
                <inspectionUrl>' . $inspectionUrl . '</inspectionUrl>
                <changedPrice>' . $changedPrice . '</changedPrice>
                <changedCdw>' . $changedCdw . '</changedCdw>
          </openContract>
        ');

        try {
            $response = $soapclient->openContract($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Open Contract API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        $contractNo = '';
        if (isset($response)) {
            //this is success case
            $contractNo = $response->return;
            if (ctype_alnum($contractNo)) {
                //Update human less state in API response
                $update_data['oasis_contract_id'] = $contractNo;
                $update_data['vehicle_id'] = $vehicle_id;
                $update_data['human_less_state'] = 'Car Taken';
                $update_by['id'] = $booking_id;
                $this->page->updateData('booking', $update_data, $update_by);

                $inspectionDetail = DB::table('inspection')->where('contract_id', $contract_id)->where('type', 0)->where('image', '=', '')->first();
                if ($inspectionDetail) {
                    $update_inspection_data['km'] = $lastKm;
                    $update_inspection_data['fuel'] = $lastFuelTank;
                    $update_inspection_data['plate_no'] = $carPlate;
                    $updateBy['contract_id'] = $contract_id;
                    $this->page->updateData('inspection', $update_inspection_data, $updateBy);
                }
            }
        }

        return $contractNo;
    }

    public function unlockCar(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $vehicle_id = $request->input('vehicleID');
        $command = 'OpenDoors';
        $value = 0;

        $site_settings = custom::site_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }

        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/Token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&username=key&password=K@2018d",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $curl_resp = curl_exec($curl);
        $j_resp = json_decode($curl_resp);
        $err = curl_error($curl);
        curl_close($curl);
        $get_accessToken = $j_resp->access_token;

        if ($get_accessToken != '') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Bearer " . $get_accessToken . "",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $cmd_response = curl_exec($curl);
            $cmd_err = curl_error($curl);

            curl_close($curl);

            if ($cmd_err) {
                $cmd_status = false;
            } else {
                $mobilizeStatus = $this->mobilize($get_accessToken, $vehicle_id);
                if ($mobilizeStatus) {
                    $cmd_status = true;
                    $cmd_msg = $cmd_response;

                    $update_data['human_less_state'] = 'Car Taken';
                    $update_by['id'] = $booking_id;
                    $this->page->updateData('booking', $update_data, $update_by);
                }
            }

            $response = array();
            $response['status'] = $cmd_status;
            $response['message'] = $cmd_msg;
            echo json_encode($response);
            exit();
        }
    }

    private function mobilize($get_accessToken, $vehicle_id)
    {
        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));
        $command = 'Mobilize';
        $value = '0';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Bearer " . $get_accessToken . "",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $cmd_response = curl_exec($curl);
        $cmd_err = curl_error($curl);

        curl_close($curl);

        if ($cmd_response) {
            $cmd_status = true;
        } else {
            $cmd_status = false;
        }

        return $cmd_status;
    }

    /*End Trip*/
    public function end_trip($booking_id) // This is for logged in user
    {
        $checkUserBooking = false;
        $user_id = Session::get('user_id');
        $booking_id = base64_decode($booking_id);
        $toBack = custom::checkIfUserLoggedinHL($this->lang);
        if ($toBack != "") {
            return redirect()->to($toBack);
        }
        $oasis_contract_no = '';
        $km = '';
        $fuel = '';
        $plate_no = '';
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $inspectionDetail = $this->page->getSingle('inspection', array('contract_id' => $bookingDetail->reservation_code, 'type' => '0'));
        $oasis_contract_no = $bookingDetail->oasis_contract_id;
        if (isset($inspectionDetail) && !empty($inspectionDetail)) {
            $km = $inspectionDetail->km;
            $fuel = $inspectionDetail->fuel;
            $plate_no = $inspectionDetail->plate_no;
        }
        //Start handling STS response.
        if (isset($_REQUEST['Response_SecureHash'])) {

            $apiSettings = custom::api_settings();
            $SECRET_KEY = $apiSettings->sts_secret_key_web;
            $MERCHANT_ID = $apiSettings->sts_merchant_id_web;

            $parameters = $_REQUEST;
            /*echo "<pre>";
            print_r($parameters);exit;*/
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
            }

            //echo ("Response Orderd String is: " . $responseOrderdString).chr(10);
            $generatedsecureHash = hash('sha256', $responseOrderdString, false);

            if ($parameters['Response_StatusCode'] == '00000' && $parameters['Response_GatewayStatusCode'] == '0000') {

                if ($receivedSecurehash !== $generatedsecureHash) {
                    echo "Received Secure Hash does not Equal generated Secure hash";
                    exit();
                }

                //sts credit card is success
                //echo "Status is: ".$_REQUEST['Response_StatusCode'];
                $data['sts_error'] = 'success';
                $data['close_type'] = 'C';
                //update sts attempts
                $Response_TransactionID = explode('HLKEY', $parameters['Response_TransactionID']);
                $getKeyId = substr($Response_TransactionID[1], 0, 3);
                $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side

                $card_brand = explode(':', $parameters['Response_PaymentMethod']);
                $st_card_brand = 'Cash';
                if ($card_brand[1] == 'Visa') {
                    $st_card_brand = 'ST_Visa';
                } else if ($card_brand[1] == 'Master Card') {
                    $st_card_brand = 'ST_MC';
                } else if ($card_brand[1] == 'MasterCard') {
                    $st_card_brand = 'ST_MC';
                } elseif ($card_brand[1] == 'Master') {
                    $st_card_brand = 'ST_MC';
                } else if ($card_brand[1] == 'Mada') {
                    $st_card_brand = 'ST_MADA';
                } else if ($card_brand[1] == 'ApplePay-MADA') {
                    $st_card_brand = 'ST_MADA';
                } else if ($card_brand[1] == 'ApplePay-MasterCard') {
                    $st_card_brand = 'ST_MC';
                } else if ($card_brand[1] == 'ApplePay-Visa') {
                    $st_card_brand = 'ST_Visa';
                } else if ($card_brand[1] == 'Electron') {
                    $st_card_brand = 'ST_Visa';
                }
                $data['transMethod'] = $st_card_brand;
                $data['accountCardNo'] = $parameters['Response_CardNumber'];
                $data['transReference'] = $parameters['Response_TransactionID'];
                $data['amount'] = $parameters['Response_Amount'] / 100;
                $data['responseMsg'] = $parameters['Response_StatusDescription'];

                //addPayment to OASIS
                $addPaymentStatus = $this->addPayment($oasis_contract_no, $data['transMethod'], $data['transReference'], $data['accountCardNo'], $data['amount']);
                if ($addPaymentStatus == false) {
                    echo 'Error in Add Payment API';
                    exit;
                }

            } else {
                //echo "Status is: failed";
                $bookingLabel = $this->lang == 'eng' ? 'Booking' : 'الحجز';
                $errorLabel = $this->lang == 'eng' ? 'Error' : 'خطأ';
                $data['sts_error'] = $bookingLabel . ' # : ' . $parameters['Response_TransactionID'] . ' - ' . $parameters['Response_StatusDescription'];
                $Response_TransactionID = explode('HLKEY', $parameters['Response_TransactionID']);
                $getKeyId = substr($Response_TransactionID[1], 0, 3);
                $getIncrement = str_pad($getKeyId + 1, 3, "0", STR_PAD_LEFT);
                $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side
                $getPrimaryId = substr($Response_TransactionID[1], 3);
                $data['Response_TransactionID'] = "HLKEY" . $getIncrement . str_pad($getPrimaryId, 12, '0', STR_PAD_LEFT);
                $data['amount'] = $parameters['Response_Amount'] / 100;
            }
        }
        //end STS Response

        $data['booking_id'] = $booking_id;
        $data['oasis_contract_no'] = $oasis_contract_no;
        $data['km'] = $km;
        $data['fuel'] = $fuel;
        $data['plate_no'] = $plate_no;
        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['active_menu'] = '';
        $data['end_trip'] = 1;
        $data['inspection_mode'] = 'dropOff';

        $checkCloseContract = $this->page->getSingle('booking', array('id' => $booking_id, 'human_less_state' => 'Returned'));
        if ($bookingDetail->type == 'corporate_customer') {
            $data['booking_detail'] = $this->page->getSingleBookingDetailsForCorporate($booking_id);
            $checkUserBooking = $this->page->getSingle('booking_corporate_customer', array('uid' => $user_id, 'booking_id' => $booking_id));
        } else {
            $data['booking_detail'] = $this->page->getSingleBookingDetails($booking_id);
            $checkUserBooking = $this->page->getSingle('booking_individual_user', array('uid' => $user_id, 'booking_id' => $booking_id));
        }

        if ($checkUserBooking == false) {
            return redirect()->to($toBack);
        }
        if (!isset($data['booking_detail']) || $data['booking_detail'] == '' || $checkCloseContract) {
            return redirect()->to($toBack);
        }

        $data['inspection'] = DB::table('inspection')->where('contract_id', $data['booking_detail']->reservation_code)->where('type', 1)->first();
        if (!$data['inspection']) {
            $inspection_data['type'] = 1;
            $inspection_data['plate_no'] = $plate_no;
            $inspection_data['image'] = "";
            $inspection_data['contract_id'] = $data['booking_detail']->reservation_code;
            $inspection_data['created_at'] = date('Y-m-d H:i:s');
            $data['inspection_id'] = $this->page->saveData('inspection', $inspection_data);

        } else {
            $data['inspection_id'] = $data['inspection']->id;
        }

        //get closing branch oracle reference number for closing contract
        $to_branch = $this->page->getSingle('branch', array('id' => $data['booking_detail']->to_location));
        $data['closing_branch'] = $to_branch->oracle_reference_number;

        if (custom::is_mobile()) {
            return view('frontend/mobile/human_less_end_trip', $data);
        } else {
            return view('frontend/human_less_end_trip', $data);
        }
    }

    public function dropOffInspection($booking_id)
    {
        $booking_id = base64_decode($booking_id);
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $toBack = custom::checkIfUserLoggedinHL($this->lang);
        if ($toBack != "") {
            return redirect()->to($toBack);
        }

        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['active_menu'] = '';

        $data['inspectionDetail'] = DB::table('inspection')->where('contract_id', $bookingDetail->reservation_code)->where('type', 1)->where('image', '!=', '')->first();

        if ($data['inspectionDetail']) {
            if (custom::is_mobile()) {
                return view('frontend/mobile/dropoff_inspection', $data);
            } else {
                return view('frontend/dropoff_inspection', $data);
            }
        }
    }

    public function watermark_endTrip(Request $request)
    {
        $kmIn = preg_replace('/[^0-9]/', '', $request->input('kmIn'));
        $fuelTankIn = $request->input('fuelTankIn');
        $inspection_id = $request->input('inspection_id');

        $watermark_array = json_decode($request->data);

        $obj = Image::make($request->src)->resize($request->org_img_width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        foreach ($watermark_array as $array) {
            $watermark_detail = pathinfo($array->src);
            $resize_watermark = 'public/frontend/inspection/images/watermark/resize/' . $watermark_detail['basename'];
            if (!in_array($watermark_detail['basename'], array("fade.png", "scratch.png", "damage.png"))) {
                Image::make($array->src)->resize((int)$array->w, (int)$array->h)->save($resize_watermark);
                DB::table('photo')->insert([
                    "inspection_id" => $inspection_id,
                    "image" => $array->src,
                    "x" => $array->x,
                    "y" => $array->y,
                    "w" => $array->w,
                    "h" => $array->h,
                ]);
            } else {
                Image::make(public_path('frontend/inspection/images/' . $watermark_detail['basename']))->resize((int)$array->w, (int)$array->h)->save($resize_watermark);
            }
            $obj = $obj->insert($resize_watermark, 'top-left', (int)$array->x, (int)$array->y);
        }
        custom::make_folder_empty('public/frontend/inspection/images/watermark/resize/');
        $base_64_encode = $obj->encode('data-url');
        $inspection = DB::table('inspection')->where('id', $request->inspection_id)->update([
            "image" => $base_64_encode,
            "w" => $request->w,
            "h" => $request->h,
            "km" => $kmIn,
            "fuel" => $fuelTankIn,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);
        return response()->json([
            'success' => 1,
            'src' => $base_64_encode,
        ], 200);
    }

    public function getContractBalance(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $reservation_code = $bookingDetail->reservation_code;
        $contract_no = $request->input('contract_no');
        $closing_branch = $request->input('closing_branch');
        $kmIn = $request->input('kmIn');
        $fuelTankIn = $request->input('fuelTankIn');
        $balance = '';

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <getContractBalance>
             <contractNo>' . $contract_no . '</contractNo>
             <closingBranch>' . $closing_branch . '</closingBranch>
             <kmIn>' . $kmIn . '</kmIn>
             <fuelTankIn>' . $fuelTankIn . '</fuelTankIn>
          </getContractBalance>
        ');

        try {
            $response = $soapclient->getContractBalance($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Get Contract Balance API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }
        /*echo '<pre>';
        print_r($response);
        exit;*/
        $formHtml = "";
        $closeType = "";
        $noPayment = false;
        if (isset($response) && $isError == false) {
            //this is success case
            $balance = $response->return;
            //$balance = '12';
            $responseMsg = "";
            if (is_numeric($balance)) {

                if ($balance == 0) {
                    $closeType = 'C';
                    $noPayment = true;
                } else if ($balance < 0) {
                    $closeType = 'H';
                    $noPayment = true;
                }
                $update_data['km'] = $kmIn;
                $update_data['fuel'] = $fuelTankIn;
                $update_data['contract_balance'] = $balance;
                $update_by['contract_id'] = $reservation_code;
                $update_by['type'] = 1;
                $this->page->updateData('inspection', $update_data, $update_by);
                //render STS payment form
                $formHtml = $this->stsPaymentForm($booking_id, $balance, $noPayment);
            }

        } else {
            //this is fail case
            $balance = "";
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return->returnMessage;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        $resp['balance'] = $balance;
        $resp['kmIn'] = $kmIn;
        $resp['fuelTankIn'] = $fuelTankIn;
        $resp['closeType'] = $closeType;
        $resp['formHtml'] = $formHtml;
        echo json_encode($resp);
        exit();
    }

    public function hlExtraPayment(Request $request)//Currently not using
    {
        $booking_id = $_REQUEST['r'];
        //Start handling STS response.
        if (isset($_REQUEST['Response_SecureHash'])) {

            $apiSettings = custom::api_settings();
            $SECRET_KEY = $apiSettings->sts_secret_key_web;
            $MERCHANT_ID = $apiSettings->sts_merchant_id_web;

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
            }

            //echo ("Response Orderd String is: " . $responseOrderdString).chr(10);
            $generatedsecureHash = hash('sha256', $responseOrderdString, false);

            if ($parameters['Response_StatusCode'] == '00000' && $parameters['Response_GatewayStatusCode'] == '0000') {

                if ($receivedSecurehash !== $generatedsecureHash) {
                    echo "Received Secure Hash does not Equal generated Secure hash";
                    exit();
                }

                //sts credit card is success

                //update sts attempts
                $Response_TransactionID = explode('HLKEY', $parameters['Response_TransactionID']);
                $getKeyId = substr($Response_TransactionID[1], 0, 3);
                $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this value is that is now saved into sts engine side

                $responseMsg = $parameters['Response_GatewayStatusDescription'];
                $card_brand = explode(':', $parameters['Response_PaymentMethod']); //Only returned if Version is 2.0 but we are using Version 1.0
                $transMethod = $card_brand[1];
                $accountCardNo = $parameters['Response_CardNumber'];
                $transReference = $parameters['Response_CardHolderName'];
                $amount = $parameters['Response_Amount'];

                //redirect to booked.
                if ($this->lang == 'arb') {
                    return redirect('end-trip?r=' . $booking_id . 'wiz=3');
                } else {
                    return redirect('en/end-trip?r=' . $booking_id . 'wiz=3');
                }

            } else {
                //echo "Status is: failed";
                $bookingLabel = $this->lang == 'eng' ? 'Booking' : 'الحجز';
                $errorLabel = $this->lang == 'eng' ? 'Error' : 'خطأ';
                $data['sts_error'] = $bookingLabel . ' # : ' . $parameters['Response_TransactionID'] . ' - ' . $parameters['Response_StatusDescription'];
                $Response_TransactionID = explode('HLKEY', $parameters['Response_TransactionID']);
                $getKeyId = substr($Response_TransactionID[1], 0, 3);
                $getIncrement = str_pad($getKeyId + 1, 3, "0", STR_PAD_LEFT);
                $sts_attempted = str_pad($getKeyId, 3, "0", STR_PAD_LEFT); //this val+ue is that is now saved into sts engine side
                $getPrimaryId = substr($Response_TransactionID[1], 3);
                $data['Response_TransactionID'] = "HLKEY" . $getIncrement . str_pad($getPrimaryId, 12, '0', STR_PAD_LEFT);
                //update sts attempts
                //$booking_cc_update['is_sts_inquired'] = 0;
                //$booking_cc_update['sts_attempts'] = ltrim($sts_attempted,'0');
                //$booking_id = Session::get('booking_id');
                //$this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
                if ($this->lang == 'arb') {
                    return redirect('end-trip?r=' . $booking_id . 'wiz=2');
                } else {
                    return redirect('en/end-trip?r=' . $booking_id . 'wiz=2');
                }
            }
        }
        //end STS Response
    }

    private function stsPaymentForm($booking_id, $contractBalance, $noPayment)
    {
        $lang = $this->lang;
        $base_url = $this->base_url;
        $apiSettings = custom::api_settings();
        $securityToken = $apiSettings->sts_secret_key_web;
        $merchantID = $apiSettings->sts_merchant_id_web;
        $sts_payment_link = $apiSettings->sts_payment_link;

        $noPaymentStyle = '';
        if ($noPayment) {
            $noPaymentStyle = 'display:none;';
        }
        $transaction_id = 'HLKEY' . str_pad($booking_id, 15, '0', STR_PAD_LEFT);
        $get_ammount = number_format($contractBalance, 2, '.', '');

        $PaymentParams = [];
        $paymentParameters = [];
        $paymentParameters["TransactionID"] = $transaction_id;
        $paymentParameters["MerchantID"] = $merchantID;
        $paymentParameters["Amount"] = $get_ammount * 100;
        $paymentParameters["CurrencyISOCode"] = "682";
        $paymentParameters["MessageID"] = "1";
        $paymentParameters["Quantity"] = "1";
        $paymentParameters["Channel"] = "0";
        $paymentParameters["ItemID"] = "130";
        $paymentParameters["PaymentMethod"] = "1";
        $paymentParameters["PaymentDescription"] = "Payment";
        $paymentParameters["Language"] = $lang == "eng" ? "En" : "Ar";
        $paymentParameters["ThemeID"] = "1000000001";

        if ($lang == 'arb') {
            $paymentParameters["ResponseBackURL"] = $base_url . "/end-trip/" . base64_encode($booking_id);
        } else {
            $paymentParameters["ResponseBackURL"] = $base_url . "/en/end-trip/" . base64_encode($booking_id);
        }

        $paymentParameters["Version"] = "2.0";
        $paymentParameters["RedirectURL"] = $sts_payment_link;

        $PaymentParams = $paymentParameters;
        $PaymentParams["SecureHash"] = custom::generate_token(array(
            'Amount' => $paymentParameters["Amount"],
            'Channel' => $paymentParameters["Channel"],
            'CurrencyISOCode' => $paymentParameters["CurrencyISOCode"],
            'Language' => $paymentParameters["Language"],
            'MerchantID' => $paymentParameters["MerchantID"],
            'MessageID' => $paymentParameters["MessageID"],
            'ThemeID' => $paymentParameters["ThemeID"],
            'ItemID' => $paymentParameters["ItemID"],
            'PaymentDescription' => urlencode($paymentParameters["PaymentDescription"]),
            'PaymentMethod' => $paymentParameters["PaymentMethod"],
            'Quantity' => $paymentParameters["Quantity"],
            'ResponseBackURL' => $paymentParameters["ResponseBackURL"],
            'TransactionID' => $paymentParameters["TransactionID"],
            'Version' => $paymentParameters["Version"],
        ));

        $redirectURL = (String)$PaymentParams["RedirectURL"];
        $amount = (String)$PaymentParams["Amount"];
        $currencyCode = (String)$PaymentParams["CurrencyISOCode"];
        $transactionID = (String)$PaymentParams["TransactionID"];
        $merchantID = (String)$PaymentParams["MerchantID"];
        $language = (String)$PaymentParams["Language"];
        $messageID = (String)$PaymentParams["MessageID"];
        $secureHash = (String)$PaymentParams["SecureHash"];
        $themeID = (String)$PaymentParams["ThemeID"];
        $ItemID = (String)$PaymentParams["ItemID"];
        $PaymentDescription = (String)$PaymentParams["PaymentDescription"];
        $responseBackURL = (String)$PaymentParams["ResponseBackURL"];
        $channel = (String)$PaymentParams["Channel"];
        $quantity = (String)$PaymentParams["Quantity"];
        $version = (String)$PaymentParams["Version"];
        $paymentMethod = (String)$PaymentParams["PaymentMethod"];

        $formHtml = '<div class="qr-holder">
                        <h3>' . Lang::get('labels.pay_extra') . '</h3>
                        <p class="extras-desc">' . Lang::get('labels.closing_contract_balance') . ' ' . $get_ammount . ' ' . Lang::get('labels.sar') . '</p>
                    </div>';
        $formHtml .= '<div class="pay-extras" style="' . $noPaymentStyle . '">
                        <div class="paymentOption objects">
                            <ul>
                                <li>
                                    <input id="CreditCard" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" checked="" autocomplete="off">
                                    <label for="CreditCard">
                                        <div class="imgBox">
                                            <img src="' . $base_url . '/public/frontend/images/ico-master.png" alt="Card" width="35" height="26">
                                        </div>
                                    </label>
                                </li>
                                <li style="">
                                    <input id="CreditCardMada" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" autocomplete="off">
                                    <label for="CreditCardMada" style="display: block !important;">
                                        <div class="imgBox">
                                            <img src="' . $base_url . '/public/frontend/images/ico-visa.png" alt="Card" width="35" height="26">
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <input id="cash" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" autocomplete="off">
                                    <label for="cash">
                                        <div class="imgBox">
                                            <img src="' . $base_url . '/public/frontend/images/ico-mada.png" alt="Mada" width="39" height="26">
                                        </div>
                                    </label>
                                </li>
                            </ul>
                        </div>';
        $formHtml .= '<div class="STS_express_checkout">
                        <div class="creditCardForm">
                            <div class="payment add">';
        $formHtml .= '<form action="' . $redirectURL . '" class="' . $transaction_id . '" name="redirectForm" id="stsPayOne" method="post">
                        <input name="MerchantID" type="hidden" value="' . $merchantID . '"/>
                        <input name="Amount" type="hidden" value="' . $amount . '"/>
                        <input name="CurrencyISOCode" type="hidden" value="' . $currencyCode . '"/>
                        <input name="Language" type="hidden" value="' . $language . '"/>
                        <input name="MessageID" type="hidden" value="' . $messageID . '"/>
                        <input name="TransactionID" type="hidden" value="' . $transactionID . '"/>
                        <input name="ItemID" type="hidden" value="' . $ItemID . '"/>
                        <input name="ThemeID" type="hidden" value="' . $themeID . '"/>
                        <input name="ResponseBackURL" type="hidden" value="' . $responseBackURL . '"/>
                        <input name="Quantity" type="hidden" value="' . $quantity . '"/>
                        <input name="Channel" type="hidden" value="' . $channel . '"/>
                        <input name="Version" type="hidden" value="' . $version . '"/>
                        <input name="PaymentMethod" type="hidden" value="' . $paymentMethod . '"/>
                        <input name="PaymentDescription" type="hidden" value="' . $PaymentDescription . '"/>
                        <div class="main-row">
                            <div class="form-group owner">
                                <input type="text" placeholder="' . Lang::get('labels.cardholders_full_name') . '" name="CardHolderName" class="form-control" id="owner">
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="' . Lang::get('labels.credit_card_number') . '" name="CardNumber" class="form-control" id="cardNumber" maxlength="16">
                            </div>
                            <div class="form-group CVV">
                                <input type="text" placeholder="' . Lang::get('labels.cvc_number') . '" name="SecurityCode" class="form-control" id="cvv" maxlength="3">
                            </div>
                            <div class="form-group add" id="expiration-date">
                                <div class="subcol">
                                    <select name="ExpiryDateMonth">
                                        <option value="">' . ($lang == 'eng' ? 'Expiry Month' : 'شهر انتهاء الصلاحية') . '</option>';
        $startMonth = '1';
        $endMonth = '12';
        $currentMonth = date('m');
        for ($startMonth; $startMonth <= $endMonth; $startMonth++) {
            $startMonth = $startMonth < 10 ? '0' . $startMonth : $startMonth;
            $monthName = custom::month_english($startMonth);
            if ($lang == 'arb') {
                $monthName = custom::month_arabic($monthName);
            }
            $selectedMonth = $startMonth == $currentMonth ? 'selected' : '';

            $formHtml .= '<option  value="' . $startMonth . '">' . $startMonth . '</option>';
        }
        $formHtml .= '</select>
                                </div>
                                <div class="subcol">
                                    <select name="ExpiryDateYear">
                                        <option value="">' . ($lang == 'eng' ? 'Expiry Year' : 'سنة انتهاء الصلاحية') . '</option>';

        $startYear = date('Y');
        $endYear = $startYear + 10;
        for ($startYear; $startYear <= $endYear; $startYear++) {

            $formHtml .= '<option value="' . substr($startYear, -2) . '">' . $startYear . '</option>';
        }
        $formHtml .= ' </select>
                                </div>
                            </div>
                            <input name="SecureHash" type="hidden" value="' . $secureHash . '"/>
                            <input type="submit" value="' . Lang::get('labels.pay_now') . ' ' . $get_ammount . ' ' . Lang::get('labels.sar_hl') . '" class="edBtn redishButtonRound" id="submitPayment"/>
                        </div>
                    </form>';
        $formHtml .= '</div></div></div></div>';

        return $formHtml;
    }

    private function addPayment($oasis_contract_no, $transMethod, $transReference, $accountCardNo, $amount)
    {
        $contract_no = $oasis_contract_no;
        $contractBalance = $amount;

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <addPayment>
             <contractNo>' . $contract_no . '</contractNo>
             <transMethod>' . $transMethod . '</transMethod>
             <transReference>' . $transReference . '</transReference>
             <accountCardNo>' . $accountCardNo . '</accountCardNo>
             <transAmount>' . $contractBalance . '</transAmount>
          </addPayment>

        ');

        try {
            $response = $soapclient->addPayment($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Add Payment API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        if (isset($response) && $response->return == 'Payment added successfully' && $isError == false) {
            //this is success case
            $responseMsg = $response->return;

        } else if (isset($response) && $response->return == 'Error: contract not available') {
            $isError = true;
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return->returnMessage;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }
        return $error_status;
    }

    public function closeContract(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $contract_no = $request->input('contract_no');
        $closing_branch = $request->input('closing_branch');
        $tammStatus = $request->input('tammStatus');
        $closeType = $request->input('closeType');

        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        $vehicle_id = $bookingDetail->vehicle_id;
        $reservation_code = $bookingDetail->reservation_code;
        $inspectionUrl = '';
        $inspectionDetail = DB::table('inspection')->where('contract_id', $bookingDetail->reservation_code)->where('type', 1)->where('image', '!=', '')->first();

        if ($inspectionDetail) {
            $lang_segment = $this->lang == 'eng' ? 'en/' : '';
            $inspectionUrl = $this->base_url . '/' . $lang_segment . 'vehicle-inspection/' . base64_encode($booking_id);
        }

        $getKmFuel = $this->page->getSingle('inspection', array('contract_id' => $reservation_code, 'type' => '1'));
        $kmIn = $getKmFuel->km;
        $fuelTankIn = $getKmFuel->fuel;
        $carPlate = $getKmFuel->plate_no;

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->human_less_api, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <closeContract>
             <contractNo>' . $contract_no . '</contractNo>
             <closingBranch>' . $closing_branch . '</closingBranch>
             <closeType>' . $closeType . '</closeType>
             <kmIn>' . $kmIn . '</kmIn>
             <fuelTankIn>' . $fuelTankIn . '</fuelTankIn>
             <tammStatus>' . $tammStatus . '</tammStatus>
             <inspectionUrl>' . $inspectionUrl . '</inspectionUrl>
          </closeContract>
        ');

        try {
            $response = $soapclient->closeContract($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less'));
            $this->sendEmail('Catch Error In Human Less Close Contract API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        if (isset($response) && $response->return == 'Contract Closed Successfully' && $isError == false) {

            //execute following query in API success response
            $update_data['human_less_state'] = 'Returned';
            $update_by['id'] = $booking_id;
            $this->page->updateData('booking', $update_data, $update_by);

            //this is success case
            $responseMsg = $response->return;

            //call safe road API for de-mobilize engine and close doors
            $this->deMobilize($vehicle_id);
            /*$deMobilize = $this->deMobilize($vehicle_id);
            if($deMobilize){
                $this->cancelTammAuth($booking_id,$carPlate);
            }*/

        } else if (isset($response) && $response->return == 'Error: contract not available' && $isError == false) {
            $responseMsg = $response->return;
            $isError = true;
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = true;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        echo json_encode($resp);
        exit();
    }

    public function cancelTammAuth(Request $request)
    {
        $booking_id = $request->input('booking_id');
        $carPlate = $request->input('plate_no');
        $tammDetail = $this->page->getSingle('tamm', array('booking_id' => $booking_id));
        $authorizationNumber = $tammDetail->auth_number;
        //for car plate, arabic characters length is 3 & numbers length is 4
        $plate_number = mb_substr($carPlate, 3, null, 'utf-8');
        $plate_text = mb_substr($carPlate, -7, 3, 'utf-8');

        $resp = array();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $isError = false;
        $site_settings = custom::site_settings();
        $api_settings = custom::api_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }
        try {
            $soapclient = new SoapClient($api_settings->tamm_otp_url, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev Human Less Cancel TAMM API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
        /*end soap api*/
        //$data = json_decode(json_encode($data), true);
        $xmlr1 = simplexml_load_string('
            <cancelAuthorization>
                 <plateNumber>' . $plate_number . '</plateNumber>
                 <plateText>' . $plate_text . '</plateText>
                 <authNo>' . $authorizationNumber . '</authNo>
              </cancelAuthorization>
        ');

        try {
            $response = $soapclient->cancelAuthorization($xmlr1);
        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair(array('name' => 'dev-testing for Human Less Cancel TAMM'));
            $this->sendEmail('Catch Error In TAMM Cancel API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        /*echo '<pre>';
        print_r($response);
        exit;*/
        $responseMsg = "";
        if (isset($response) && $response->return == 'Sucess' && $isError == false) {
            //this is success case
            $responseMsg = $response->return;
            $tamm_data['status'] = 'cancelled';
            $tamm_data['updated_at'] = date('Y-m-d H:i:s');
            $update_by['booking_id'] = $booking_id;
            $this->page->updateData('tamm', $tamm_data, $update_by);
            $isError = true;

        } else {
            //this is fail case
            //$smsSent = custom::sendSMS($data['customer_mobile'], $sms_text);
            //$emailMsg = $this->keyValPair(array('name'=>'dev-testing for Human Less APIs'), $responseMsg);
            //$this->sendEmail('Error In Human Less Car List API', $emailMsg);
            $isError = false;
        }
        if ($isError) {
            $error_status = false;
        } else {
            $error_status = true;
        }

        $resp['status'] = $error_status;
        $resp['message'] = $responseMsg;
        echo json_encode($resp);
        exit();
    }

    private function deMobilize($vehicle_id)
    {
        $command = 'Demobilize';
        $value = 0;

        $site_settings = custom::site_settings();
        if ($site_settings->human_less_mode == 'off') {
            exit();
        }

        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/Token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&username=key&password=K@2018d",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $curl_resp = curl_exec($curl);
        $j_resp = json_decode($curl_resp);
        $err = curl_error($curl);
        curl_close($curl);
        $get_accessToken = $j_resp->access_token;

        if ($get_accessToken != '') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Bearer " . $get_accessToken . "",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $cmd_response = curl_exec($curl);
            $cmd_err = curl_error($curl);

            curl_close($curl);

            if ($cmd_response) {
                $closeDoorsStatus = $this->closeDoors($get_accessToken, $vehicle_id);
                if ($closeDoorsStatus) {
                    $cmd_status = true;
                }
            } else {
                $cmd_status = false;
            }
            return $cmd_status;
        }
    }

    private function closeDoors($get_accessToken, $vehicle_id)
    {
        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));
        $command = 'CloseDoors';
        $value = '0';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Bearer " . $get_accessToken . "",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $cmd_response = curl_exec($curl);
        $cmd_err = curl_error($curl);

        curl_close($curl);

        if ($cmd_response) {
            $cmd_status = true;
        } else {
            $cmd_status = false;
        }

        return $cmd_status;
    }

    /*Clear Inspection*/
    public function clearInspection(Request $request)
    {
        if ($request->isMethod('post') && $request->inspection_id) {
            $inspection = DB::table('inspection')->where('id', $request->inspection_id)->first();
            $inspection = DB::table('inspection')->where('id', $request->inspection_id)->update([
                "image" => Image::make(public_path('frontend/inspection/images/mainImage.jpg'))->encode('data-url')
            ]);
            /*if(DB::table('photo')->where('inspection_id', $request->inspection_id)->count())
            {
                DB::table('photo')->where('inspection_id', $request->inspection_id)->delete();
            }*/
        }
    }

    /*Vehicle Inspection*/
    public function vehicleInspection($booking_id)
    {
        $booking_id = base64_decode($booking_id);
        $bookingDetail = $this->page->getSingle('booking', array('id' => $booking_id));
        /*$toBack = custom::checkIfUserLoggedinHL($this->lang);
        if($toBack != ""){
            return redirect()->to($toBack);
        }*/

        $data['base_url'] = $this->base_url;
        $data['lang_base_url'] = $this->lang_base_url;
        $data['lang'] = $this->lang;
        $data['active_menu'] = '';

        $data['pickupInspection'] = DB::table('inspection')->where('contract_id', $bookingDetail->reservation_code)->where('type', 0)->where('image', '!=', '')->first();
        $data['dropOffInspection'] = DB::table('inspection')->where('contract_id', $bookingDetail->reservation_code)->where('type', 1)->where('image', '!=', '')->first();
        $data['pickUpPhoto'] = isset($data['pickupInspection']) ? DB::table('photo')->where('inspection_id', $data['pickupInspection']->id)->get() : '';
        $data['dropOffPhoto'] = isset($data['dropOffInspection']) ? DB::table('photo')->where('inspection_id', $data['dropOffInspection']->id)->get() : '';

        if ($data['pickupInspection'] || $data['dropOffInspection']) {
            if (custom::is_mobile()) {
                return view('frontend/mobile/vehicle_inspection', $data);
            } else {
                return view('frontend/vehicle_inspection', $data);
            }
        }
    }

    /*Safe Road API*/
    public function safeRoadApi(Request $request)
    {
        $command = $request->input('command');
        $vehicle_id = $request->input('vehicle_id');
        $value = $request->input('value');
        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/Token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&username=key&password=K@2018d",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $curl_resp = curl_exec($curl);
        $j_resp = json_decode($curl_resp);
        $err = curl_error($curl);
        curl_close($curl);
        $get_accessToken = $j_resp->access_token;

        if ($get_accessToken != '') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Bearer " . $get_accessToken . "",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $cmd_response = curl_exec($curl);
            $cmd_err = curl_error($curl);

            curl_close($curl);

            if ($cmd_err) {
                //echo "cURL Error #:" . $cmd_err;
                $cmd_status = false;
            } else {
                //echo $cmd_response;
                $cmd_status = true;
                $cmd_msg = $cmd_response;
            }

            $response = array();
            $response['status'] = $cmd_status;
            $response['message'] = $cmd_msg;
            echo json_encode($response);
            exit();
        }
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

    private function sendEmail($subject, $message, $sendToFozan = false)
    {
        //exit();
        /*if ($sendToFozan == true)
        {
            $to_emails = 'bilal_ejaz@astutesol.com, waqas@astutesol.com, kholoud.j@edesign.com.sa';
        }else{
            $to_emails = 'bilal_ejaz@astutesol.com';
        }
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: '.custom::getSiteName($lang).' Rental <info@keytest.sa>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to_emails, $subject, $message, $headers);*/
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

    public function sendDummyEmail()
    {
        $site = custom::site_settings();
        $emailString = 'Email Testing';
        $email['subject'] = 'KEY Car Rental | STS IPN Email Test';
        $email['fromEmail'] = 'no-reply@paytabs.com';
        $email['fromName'] = 'no-reply';
        if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {
            $email['toEmail'] = 'ahsan@astutesol.com';
            $email['ccEmail'] = 'waqas@astutesol.com';
            $email['bccEmail'] = 'mohsin@astutesol.com';
        } else {
            $email['toEmail'] = 'ahsan@astutesol.com';
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';
        }
        $email['attachment'] = '';
        $content['contact_no'] = $site->site_phone;
        $content['lang_base_url'] = $this->lang_base_url;
        $content['name'] = 'Fozan Baghdadi';
        $content['msg'] = $emailString;
        $content['gender'] = 'male';
        custom::sendEmail2('general', $content, $email, 'eng');
    }

}

?>