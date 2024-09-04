<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Front\Api;
use App\Models\Front\Page;
use App\Helpers\Custom;
use Session;
use App;
use Lang;
use Carbon\Carbon;
use SoapClient;
use SoapFault, DB;


class ApiController extends Controller
{

    private $api = '';
    private $soapclient = '';

    public function __construct(Request $request)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // checking if api's are turned off from backend then don't run the api's here
        $api_settings = custom::api_settings();

        if (isset($_REQUEST['walkin_api']) || isset($_REQUEST['from_mobile'])) // coming from walkin api
        {
            // If coming from walkin api then don't check for oasis apis off or on
        } else {
            if (!custom::is_oasis_api_enabled()) {
                die;
            }
        }

        $this->api = new Api();
        try {
            $this->soapclient = new SoapClient($api_settings->oasis_api_url . '?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
        } catch (SoapFault $fault) {
            $errorMsg = "Exception occured:<br>";
            $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
            // echo $errorMsg;die();
            $this->sendEmail('Connection Refused Error For ' . custom::getSiteName("eng") . ' Dev API', $errorMsg, true);
            echo 'Unable to call soap client in constructor';
            exit();
        }
    }

    // 1 here
    public function setDataCronJob()
    {
        if (isset($_REQUEST['bid']) && $_REQUEST['bid'] > 0) {
            $booking_id_from_walkin = $_REQUEST['bid'];
        } else {
            $booking_id_from_walkin = '';
        }

        $force_resync = isset($_REQUEST['force_resync']);

        $records = $this->api->getBookings($booking_id_from_walkin, $force_resync);

        // dd($records);

        $bookingsData = json_decode(json_encode($records), true);
        // custom::dump($bookingsData);
        if (count($bookingsData) > 0) {
            foreach ($bookingsData as $bookingInfo) {
                $reservation_code = $bookingInfo['BOOKING_ID'];
                //$cancelledCollectionInfo = $this->getCancelledBookingsCollectionInfo($reservation_code);
                //echo '<pre>';print_r($activeCollectionInfo);exit();
                $bookingResponse = $this->setBookingInfo($bookingInfo);

                if ($bookingResponse === true || ($bookingResponse === false && $force_resync)) {
                    $customerInfo = $this->getCustomersInfo($reservation_code);
                    $customerResponse = $this->setCustomerInfo($customerInfo);
                    if ($customerResponse === true) {
                        $activeCollectionInfo = $this->getActiveCollectionsInfo($reservation_code);
                        $collectionResponse = $this->setCollectionInfo($activeCollectionInfo);
                        /*if (!empty($cancelledCollectionInfo))
                        {
                            $cancelledCollectionResponse = $this->setCollectionInfo($cancelledCollectionInfo);
                        }*/
                        if ($collectionResponse === true) {
                            // setup setRedeemInfo api here to sync data to oasis system
                            $redeemResponse = true; // specified default value so if not used redeem than update data in db
                            $db_id = $bookingInfo['DB_ID'];
                            $redeemInfo = $this->getRedeemInfo($db_id);
                            //echo '<pre>';print_r($redeemInfo);
                            if ($redeemInfo['redeem_points'] > 0) { // if redeem offer is used then sync
                                $redeemInfoToSet['BOOKING_ID'] = $redeemInfo['reservation_code'];
                                $redeemInfoToSet['REDEEM_DATE'] = $redeemInfo['created_at'];
                                $redeemInfoToSet['REDEEM_POINTS'] = $redeemInfo['redeem_points'];
                                $redeemInfoToSet['REDEEM_AMOUNT'] = $redeemInfo['redeem_discount_availed'];
                                $redeemResponse = $this->setRedeemInfo($redeemInfoToSet);
                            }
                            if ($redeemResponse === true) {
                                $this->api->updateBookingSyncStatus($reservation_code);
                                if (isset($cancelledCollectionResponse) && $cancelledCollectionResponse == true) {
                                    $page = new Page();
                                    $booking_details = $page->getSingle('booking', array('reservation_code' => $reservation_code));
                                    $this->api->updateCancelledSyncStatus($booking_details->id);
                                }
                            } else {
                                continue;
                            }

                        } else {
                            continue;
                        }

                    } else {
                        continue;
                    }
                } else {
                    continue;
                }

            }
            $response_message = 'Cronjob Completed Successfully';
        } else {
            $response_message = 'No data to sync.';
        }

        if (isset($_REQUEST['cancelcron']) && $_REQUEST['cancelcron'] == 1) {
            $this->setCancelledBookingCollectionCronJob();
        }


        echo $response_message;
        exit();

    }

    public function getBookingStatusCronJob()
    {
        $site_settings = custom::site_settings();
        $bookingObj = new App\Models\Admin\Booking();
        $bookings = $this->api->getBookingsToUpdateStatus();
        foreach ($bookings as $booking) {
            $bookingStatus = '';
            $getBookingStatusResponse = $this->getBookingStatus($booking->reservation_code);
            if (isset($getBookingStatusResponse->return)) {
                if ($getBookingStatusResponse->return == 'A') {
                    $bookingStatus = 'Not Picked';
                } elseif ($getBookingStatusResponse->return == 'O') {
                    $bookingStatus = 'Picked';
                } elseif ($getBookingStatusResponse->return == 'H') {
                    $bookingStatus = 'Completed with Overdue';
                } elseif ($getBookingStatusResponse->return == 'C') {
                    $bookingStatus = 'Completed';
                } elseif ($getBookingStatusResponse->return == 'I') {
                    $bookingStatus = 'Cancelled';
                } elseif ($getBookingStatusResponse->return == 'E') {
                    $bookingStatus = 'Expired';
                } elseif ($getBookingStatusResponse->return == 'V') {
                    $bookingStatus = 'Walk in';
                }

                if ($bookingStatus != $booking->booking_status) {
                    $data['booking_status'] = $bookingStatus;
                    $update_by['reservation_code'] = $booking->reservation_code;
                    $updated = $bookingObj->updateData($data, $update_by);
                    if ($updated && $getBookingStatusResponse->return == 'C') // if booking status is changed to completed through import
                    {
                        if ($site_settings->survey_on_off == 'on') {
                            $this->setSurveyPendingToFill($update_by['reservation_code']);
                        }
                    }
                }
            } else {
                continue;
            }
        }

        $response_message = 'Cronjob Completed Successfully';
        echo $response_message;
        exit();
    }

    // 2 here
    public function setCancelledBookingCollectionCronJob()
    {
        $page = new Page();
        //$cancelledBookingsNotSynced = $page->getMultipleRows('booking_cancel', array('sync' => 'N'), 'booking_id');
        $cancelledBookingsNotSynced = $page->getCancelledBookings();

        if (!is_bool($cancelledBookingsNotSynced) && $cancelledBookingsNotSynced) {
            foreach ($cancelledBookingsNotSynced as $cancelledBooking) {
                $booking_details = $page->getSingle('booking', array('id' => $cancelledBooking->booking_id));
                $cancelledCollectionInfo = $this->getCancelledBookingsCollectionInfo($booking_details->reservation_code);
                //echo '<pre>';print_r($cancelledCollectionInfo);exit();
                if (!empty($cancelledCollectionInfo)) {
                    $cancelledCollectionResponse = $this->setCollectionInfo($cancelledCollectionInfo[0]);
                    if (isset($cancelledCollectionResponse) && $cancelledCollectionResponse == true) {
                        $page->updateData('booking', array('sync' => 'A', 'synced_at' => date('Y-m-d H:i:s')), array('id' => $booking_details->id));
                        $this->api->updateCancelledSyncStatus($booking_details->id);
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
            $response_message = 'Cronjob Completed Successfully';
        } else {
            $response_message = 'No data to sync.';
        }
        echo $response_message;
        exit();
    }

    public function loyaltySyncCronJob()
    {
        $page = new Page();
        $bookingObj = new App\Models\Admin\Booking();
        $user_id_no = $_REQUEST['user_id_no'];
        $user_id_no = custom::convertArabicNumbersToEnglish($user_id_no);
        $getCustomerLoyalityXML = simplexml_load_string('
		    <getCustomerLoyality>
               <idNumber>' . $user_id_no . '</idNumber>
            </getCustomerLoyality>');

        $loyaltyResponse = $this->soapclient->getCustomerLoyality($getCustomerLoyalityXML);

        $getCustomerLoyalityXML = simplexml_load_string('
		    <getLoyalityPoints>
                <idNumber>' . $user_id_no . '</idNumber>
            </getLoyalityPoints>');

        $pointsResponse = $this->soapclient->getLoyalityPoints($getCustomerLoyalityXML);
        // custom::dump($pointsResponse);


        $loyalty_card_type = '';
        //if ($loyaltyResponse->return == -1) $loyalty_card_type = 'Bronze'; //not exist in there database
        if ($loyaltyResponse->return == 1) $loyalty_card_type = 'Silver';
        if ($loyaltyResponse->return == 2) $loyalty_card_type = 'Golden';
        if ($loyaltyResponse->return == 3) $loyalty_card_type = 'Platinum';
        if ($loyaltyResponse->return == 4) $loyalty_card_type = 'Bronze';

        if ($loyalty_card_type != '') {
            $data['loyalty_card_type'] = $loyalty_card_type;
            $data['loyalty_points'] = $points_got_from_oasis = (isset($pointsResponse->return) && $pointsResponse->return > 0 ? $pointsResponse->return : 0);
            $update_by['id_no'] = $user_id_no;

            // New Logic Fozan asked for on 23-02-2018
            $customer_info = $page->getSingle('individual_customer', array('id_no' => $user_id_no));
            if (!$customer_info) {
                echo 1;
                exit();
            }
            if ($customer_info->uid > 0) {
                $count_of_not_opened_bookings = $this->api->getCountOfNotPickedBookingsForUser($customer_info->uid); // for registered customer
            } else {
                $count_of_not_opened_bookings = $this->api->getCountOfNotPickedBookingsForCustomer($customer_info->id); // for guest customer
            }
            $points_in_our_system = $customer_info->loyalty_points;
            if ($count_of_not_opened_bookings > 0) {
                // don't update customer loyalty info
            } else {
                $bookingObj->updateCustomerData($data, $update_by);
            }
        }
        echo 1;
        exit();
    }

    public function updateStatusToExpiredCronJob()
    {
        //echo 'here';exit();
        $page = new Page();
        $site = custom::site_settings();
        $cancel_hours_after_pickup_from_db = $site->post_booking_cancellation_hours;
        $bookings = $page->getNotPickedBookings('booking', 'Not Picked');
        //dd($bookings);

        //echo '<pre>';print_r($bookings);exit();
        foreach ($bookings as $booking) {
            /*(b.booking_status='Not Picked')
            or (b.booking_status='Picked')
            or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";*/
            $booking_old_status = $booking->booking_status;


            $booking_payment_rec = $page->getSingle("booking_payment", array('booking_id' => $booking->id));

            if ($booking_payment_rec) {

                $cancel_time = date("Y-m-d H:i:s");
                $cancel_time = new \DateTime($cancel_time);

                $pickup_date = $booking->from_date;
                $pickup_date = new \DateTime($pickup_date);

                $start = Carbon::now();
                $end = new Carbon($booking->from_date);
                $difference = custom::getDateDifference($start, $end);

                if ($cancel_time->getTimestamp() > $pickup_date->getTimestamp() && (int)$cancel_hours_after_pickup_from_db * 60 < $difference['minutes']) {
                    $data_cancel_charges['cancel_charges'] = $booking_payment_rec->rent_price;
                    $data_cancel_charges['booking_id'] = $booking->id;
                    $data_cancel_charges['sync'] = 'N';
                    $data_cancel_charges['cancel_time'] = date("Y-m-d H:i:s");
                    $booking_status['booking_status'] = "Expired";
                    $page->saveData("booking_cancel", $data_cancel_charges);
                    $page->updateData('booking', $booking_status, array('id' => $booking->id));
                    // $this->autoReverseQitafRedeem($booking->id);

                    $collectionInfo = $this->getExpiredBookingsCollectionInfo($booking->reservation_code);
                    //echo '<pre>';print_r($collectionInfo);exit();
                    if (!empty($collectionInfo)) {
                        $collectionResponse = $this->setCollectionInfo($collectionInfo[0]);
                        if ($collectionResponse == true) {
                            $sync_status['sync'] = 'A';
                            $sync_status['synced_at'] = date("Y-m-d H:i:s");
                            $page->updateData('booking', $sync_status, array('id' => $booking->id));
                            $page->updateData('booking_cancel', $sync_status, array('booking_id' => $booking->id));

                            // Adding and updating customer's redeem points
                            if ($booking->type == "individual_customer" || $booking->type == "guest") {
                                $this->add_or_deduct_loyalty_points_for_customer($booking->id, 'add');
                            }
                        } else {
                            $booking_status['booking_status'] = $booking_old_status;
                            $page->updateData('booking', $booking_status, array('id' => $booking->id));
                            $page->deleteData('booking_cancel', array('booking_id' => $booking->id));
                        }
                    } else {
                        continue;
                    }

                } else {
                    continue;
                }
            }
        }

        $response_message = 'Cronjob Completed Successfully';
        echo $response_message;
        exit();
    }


    private function getCustomersInfo($booking_id)
    {
        $usersDataArr = array();
        $users = $this->api->getUsers($booking_id);
        $usersData = json_decode(json_encode($users), true);
        if ($usersData['booking_type'] == 'guest') {

            if ($usersData['icg_dob'] == "0000-00-00" || $usersData['icg_dob'] == "1970-01-01") $usersData['icg_dob'] = "";
            if ($usersData['icg_license_expiry_date'] == "0000-00-00" || $usersData['icg_license_expiry_date'] == "1970-01-01") $usersData['icg_license_expiry_date'] = "";
            if ($usersData['icg_id_expiry_date'] == "0000-00-00" || $usersData['icg_id_expiry_date'] == "1970-01-01") $usersData['icg_id_expiry_date'] = "";

            if ($usersData['icg_id_expiry_date'] != "0000-00-00" && $usersData['icg_id_expiry_date'] != "1970-01-01" && $usersData['icg_id_date_type'] == "H") {

                $date = explode('-', $usersData['icg_id_expiry_date']);
                $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                $usersData['icg_id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
            }


            $usersDataArr['ID_NUMBER'] = $usersData['icg_id_no'];
            $usersDataArr['ID_TYPE'] = $usersData['icg_id_type'];
            $usersDataArr['FIRST_NAME'] = $usersData['icg_first_name'];
            $usersDataArr['LAST_NAME'] = $usersData['icg_last_name'];
            $mobile = str_replace(' ', '', $usersData['icg_mobile_no']);
            $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
            $usersDataArr['EMAIL'] = $usersData['icg_email'];
            $usersDataArr['NATIONALITY'] = $usersData['icg_nationality'];
            $usersDataArr['DOB_G'] = $usersData['icg_dob'];

            $usersDataArr['ID_EXPIRY_DATE'] = $usersData['icg_id_expiry_date'];

            $usersDataArr['ID_DATE_TYPE'] = $usersData['icg_id_date_type'];
            $usersDataArr['ID_COPY'] = $usersData['icg_id_copy'];
            $usersDataArr['ID_COUNTRY'] = $usersData['icg_id_country'];
            $usersDataArr['DL_ID_NUMBER'] = $usersData['icg_license_no'];
            $usersDataArr['DL_ID_TYPE'] = $usersData['icg_license_id_type'];
            $usersDataArr['DL_EXPIRY_DATE'] = $usersData['icg_license_expiry_date'];
            $usersDataArr['DL_COUNTRY'] = $usersData['icg_license_id_country'];
            $usersDataArr['JOB_TITLE'] = $usersData['icg_job_title'];
            $usersDataArr['SPONSOR'] = $usersData['icg_sponsor'];
            $usersDataArr['ADDRESS_DISTRICT'] = $usersData['icg_district_address'];
            $usersDataArr['ADDRESS_STREET'] = $usersData['icg_street_address'];
            $usersDataArr['ID_NAME_COPY'] = $usersData['icg_id_image'];
            $usersDataArr['DL_NAME_COPY'] = $usersData['icg_license_copy'];
        } elseif ($usersData['booking_type'] == 'individual_customer') {

            if ($usersData['dob'] == "0000-00-00" || $usersData['dob'] == "1970-01-01") $usersData['dob'] = "";
            if ($usersData['license_expiry_date'] == "0000-00-00" || $usersData['license_expiry_date'] == "1970-01-01") $usersData['license_expiry_date'] = "";
            if ($usersData['id_expiry_date'] == "0000-00-00" || $usersData['id_expiry_date'] == "1970-01-01") $usersData['id_expiry_date'] = "";

            if ($usersData['id_expiry_date'] != "0000-00-00" && $usersData['id_expiry_date'] != "1970-01-01" && $usersData['id_date_type'] == "H") {
                $date = explode('-', $usersData['id_expiry_date']);
                $gregorianDate = custom::Hijri2Greg($date[0], $date[1], $date[2], true);
                $usersData['id_expiry_date'] = date('d-m-Y', strtotime($gregorianDate));
            }

            $usersDataArr['ID_NUMBER'] = $usersData['id_no'];
            $usersDataArr['ID_TYPE'] = $usersData['id_type'];
            $usersDataArr['FIRST_NAME'] = $usersData['first_name'];
            $usersDataArr['LAST_NAME'] = $usersData['last_name'];
            $mobile = str_replace(' ', '', $usersData['mobile_no']);
            $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
            $usersDataArr['EMAIL'] = $usersData['email'];
            $usersDataArr['NATIONALITY'] = $usersData['nationality'];
            $usersDataArr['DOB_G'] = $usersData['dob'];
            $usersDataArr['ID_EXPIRY_DATE'] = $usersData['id_expiry_date'];
            $usersDataArr['ID_DATE_TYPE'] = $usersData['id_date_type'];
            $usersDataArr['ID_COPY'] = $usersData['id_copy'];
            $usersDataArr['ID_COUNTRY'] = $usersData['id_country'];
            $usersDataArr['DL_ID_NUMBER'] = $usersData['license_no'];
            $usersDataArr['DL_ID_TYPE'] = $usersData['license_id_type'];
            $usersDataArr['DL_EXPIRY_DATE'] = $usersData['license_expiry_date'];
            $usersDataArr['DL_COUNTRY'] = $usersData['license_id_country'];
            $usersDataArr['JOB_TITLE'] = $usersData['job_title'];
            $usersDataArr['SPONSOR'] = $usersData['sponsor'];
            $usersDataArr['ADDRESS_DISTRICT'] = $usersData['district_address'];
            $usersDataArr['ADDRESS_STREET'] = $usersData['street_address'];
            $usersDataArr['ID_NAME_COPY'] = $usersData['id_image'];
            $usersDataArr['DL_NAME_COPY'] = $usersData['license_copy'];
        } elseif ($usersData['booking_type'] == 'corporate_customer') {

            //echo 'ICG '.$usersData['icg_first_name'].' '.$usersData['icg_last_name'].' '.$usersData['icg_id_no'].' '.$usersData['icg_id_date_type'].'<br>';

            //$usersDataArr['DB_ID'] = $usersData['cd_db_id'];
            $usersDataArr['ID_NUMBER'] = $usersData['cd_id_no'];
            $usersDataArr['ID_TYPE'] = $usersData['cd_id_type'];
            $usersDataArr['FIRST_NAME'] = $usersData['cd_first_name'];
            $usersDataArr['LAST_NAME'] = $usersData['cd_last_name'];
            $mobile = str_replace(' ', '', $usersData['cd_mobile_no']);
            $usersDataArr['MOBILE'] = str_replace('+', '', $mobile);
            $usersDataArr['EMAIL'] = $usersData['cd_email'];
            $usersDataArr['NATIONALITY'] = $usersData['cd_nationality'];
            $usersDataArr['DOB_G'] = $usersData['cd_dob'];

            $usersDataArr['ID_EXPIRY_DATE'] = $usersData['cd_id_expiry_date'];

            $usersDataArr['ID_DATE_TYPE'] = $usersData['cd_id_date_type'];
            $usersDataArr['ID_COPY'] = $usersData['cd_id_copy'];
            $usersDataArr['ID_COUNTRY'] = $usersData['cd_id_country'];
            $usersDataArr['DL_ID_NUMBER'] = $usersData['cd_license_no'];
            $usersDataArr['DL_ID_TYPE'] = $usersData['cd_license_id_type'];
            $usersDataArr['DL_EXPIRY_DATE'] = $usersData['cd_license_expiry_date'];
            $usersDataArr['DL_COUNTRY'] = $usersData['cd_license_id_country'];
            $usersDataArr['JOB_TITLE'] = $usersData['cd_job_title'];
            $usersDataArr['SPONSOR'] = $usersData['cd_sponsor'];
            $usersDataArr['ADDRESS_DISTRICT'] = $usersData['cd_district_address'];
            $usersDataArr['ADDRESS_STREET'] = $usersData['cd_street_address'];
            $usersDataArr['ID_NAME_COPY'] = $usersData['cd_id_image']; //new added
            $usersDataArr['DL_NAME_COPY'] = $usersData['cd_license_copy']; //new added
        }


        return $usersDataArr;
    }

    private function getActiveCollectionsInfo($booking_id)
    {
        $payments = $this->api->getActiveBookingsCollectionInfo($booking_id);
        $paymentData = json_decode(json_encode($payments), true);
        return $paymentData;
    }

    private function getRedeemInfo($booking_id)
    {
        $payment = $this->api->getRedeemInfo($booking_id);
        $redeemData = json_decode(json_encode($payment), true);
        //$messageForMail = $this->keyValPair($redeemData);
        //$this->sendEmail('Get Redeem Info Function Response: '.$booking_id, $messageForMail);
        return $redeemData;
    }

    private function getCancelledBookingsCollectionInfo($booking_id)
    {
        $payments = $this->api->getCancelledBookingsCollectionInfo($booking_id);
        $paymentData = json_decode(json_encode($payments), true);
        return $paymentData;
    }

    private function getExpiredBookingsCollectionInfo($booking_id)
    {
        $payments = $this->api->getExpiredBookingsCollectionInfo($booking_id);
        $paymentData = json_decode(json_encode($payments), true);
        return $paymentData;
    }

    private function setBookingInfo($data)
    {
        $promotion_id = ($data['PROMOTION_OFFER_CODE_USED'] != '' ? $data['PROMOTION_OFFER_CODE_USED'] : $data['PROMOTION_OFFER_ID']);

        if ($data['QITAF_REDEEM_ID'] != '') {
            $data['QITAF_REDEEM_ID'] = str_replace('.', ',', $data['QITAF_REDEEM_ID']);
            $data['QITAF_REDEEM_ID'] = explode(',', $data['QITAF_REDEEM_ID'])[1];
        }

        if ($data['NIQATY_REDEEM_ID'] != '') {
            parse_str($data['NIQATY_REDEEM_ID'], $niqaty_request_data);
            $data['NIQATY_REDEEM_ID'] = $niqaty_request_data['transaction_reference'];
        }

        $is_promo_discount_on_total = $data['IS_PROMO_DISCOUNT_ON_TOTAL'];

        $pre_discount = 0;
        $post_discount = 0;

        if ($is_promo_discount_on_total == 1) {
            $post_discount = $data['DISCOUNT_PRICE'];
        } else {
            $pre_discount = $data['DISCOUNT_PRICE'];
        }

        $data['CDW_PLUS_PRICE'] = (float)$data['CDW_PLUS'];
        unset($data['CDW_PLUS']);
        //echo "<pre>";print_r($data);exit();
        $isError = false;
        $xml_str = '
		  <setBookingInfo>
			<bookingInfo>
            <appliesFrom>' . $data['APPLIES_FROM'] . '</appliesFrom>
            <appliesTo>' . $data['APPLIES_TO'] . '</appliesTo>
            <babaySeatPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['BABY_SEAT_PRICE'] > 0 ? round($data['BABY_SEAT_PRICE'] * 30) : $data['BABY_SEAT_PRICE']) . '</babaySeatPrice>
            <bookingId>' . $data['BOOKING_ID'] . '</bookingId>
            <bookingSource>' . $data['BOOKING_SOURCE'] . '</bookingSource>
            <carModel>' . $data['CAR_MODEL'] . '</carModel>
            <carType>' . $data['CAR_TYPE'] . '</carType>
            <cdwPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['CDW_PRICE'] > 0 ? round($data['CDW_PRICE'] * 30) : $data['CDW_PRICE']) . '</cdwPrice>
            <cdwPlusPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['CDW_PLUS_PRICE'] > 0 ? round($data['CDW_PLUS_PRICE'] * 30) : $data['CDW_PLUS_PRICE']) . '</cdwPlusPrice>
            <closingBranch>' . $data['CLOSING_BRANCH'] . '</closingBranch>
            <customerId>' . $data['CUSTOMER_ID'] . '</customerId>
            <customerType>' . $data['CUSTOMER_TYPE'] . '</customerType>
            <deliveryCharges>' . $data['DELIVERY_CHARGES'] . '</deliveryCharges>
            <discountPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $pre_discount > 0 ? $pre_discount * 30 : $pre_discount) . '</discountPrice>
            <driverId>' . $data['DRIVER_ID'] . '</driverId>
            <dropoffLatitude>' . $data['DROPOFF_LATITUDE'] . '</dropoffLatitude>
            <dropoffLongitude>' . $data['DROPOFF_LONGITUDE'] . '</dropoffLongitude>
            <extraDriverPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['EXTRA_DRIVER_PRICE'] > 0 ? round($data['EXTRA_DRIVER_PRICE'] * 30) : $data['EXTRA_DRIVER_PRICE']) . '</extraDriverPrice>
            <gpsPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['GPS_PRICE'] > 0 ? round($data['GPS_PRICE'] * 30) : $data['GPS_PRICE']) . '</gpsPrice>
            <halfDay>' . $data['IS_HOURLY_TYPE'] . '</halfDay>
            <isDeliveryType>' . $data['IS_DELIVERY_TYPE'] . '</isDeliveryType>
            <mobile>' . $data['MOBILE'] . '</mobile>
            <openingBranch>' . $data['OPENING_BRANCH'] . '</openingBranch>
            <pickupLatitude>' . $data['PICKUP_LATITUDE'] . '</pickupLatitude>
            <pickupLongitude>' . $data['PICKUP_LONGITUDE'] . '</pickupLongitude>
            <promotionOfferId>' . $promotion_id . '</promotionOfferId>
            <rentPrice>' . ($data['SUBSCRIBE_FOR_MONTHS'] > 0 && $data['RENT_PRICE'] > 0 ? round($data['RENT_PRICE'] * 30) : $data['RENT_PRICE']) . '</rentPrice>
            <rentingTypeId>' . $data['RENTING_TYPE_ID'] . '</rentingTypeId>
            <qitafRedeemId>' . $data['QITAF_REDEEM_ID'] . '</qitafRedeemId>
            <loyaltyProgram>' . $data['LOYALTY_PROGRAM_ID'] . '</loyaltyProgram>
            <neqatyRedeemId>' . $data['NIQATY_REDEEM_ID'] . '</neqatyRedeemId>
            <promoDiscountAmount>' . $post_discount . '</promoDiscountAmount>
            <additionalPrice>' . $data['IS_CAR_RATE_WITH_ADDITIONAL_UTILIZATION_RATE'] . '</additionalPrice>
            <subscription>' . $data['SUBSCRIBE_FOR_MONTHS'] . '</subscription>
            <price1Month>' . $data['THREE_MONTH_SUBSCRIPTION_PRICE'] . '</price1Month>
            <price3Month>' . $data['SIX_MONTH_SUBSCRIPTION_PRICE'] . '</price3Month>
            <price6Month>' . $data['NINE_MONTH_SUBSCRIPTION_PRICE'] . '</price6Month>
            <price9Month>' . $data['TWELVE_MONTH_SUBSCRIPTION_PRICE'] . '</price9Month>
            <freeCdw>' . $data['IS_FREE_CDW_PROMO_APPLIED'] . '</freeCdw>
            <freeCdwPlus>' . $data['IS_FREE_CDW_PLUS_PROMO_APPLIED'] . '</freeCdwPlus>
            <freeOpenKm>' . $data['IS_FREE_OPEN_KM_PROMO_APPLIED'] . '</freeOpenKm>
            <freeBabySeat>' . $data['IS_FREE_BABY_SEAT_PROMO_APPLIED'] . '</freeBabySeat>
            <freeDriver>' . $data['IS_FREE_DRIVER_PROMO_APPLIED'] . '</freeDriver>
            <freeDelivery>' . $data['IS_FREE_DELIVERY_PROMO_APPLIED'] . '</freeDelivery>
            <freeDropOff>' . $data['IS_FREE_DROPOFF_PROMO_APPLIED'] . '</freeDropOff>
            <alrajhiRedeemId>' . $data['MOKAFAA_REDEEM_ID'] . '</alrajhiRedeemId>
            <anbRedeemId>' . $data['ANB_REDEEM_ID'] . '</anbRedeemId>
            <isLimousine>' . $data['IS_LIMOUSINE'] . '</isLimousine>
            <isRoundTrip>' . $data['IS_ROUND_TRIP'] . '</isRoundTrip>
            <flightNo>' . $data['FLIGHT_NUMBER'] . '</flightNo>
            <waitingExtraHours>' . $data['WAITING_EXTRA_HOURS'] . '</waitingExtraHours>
            <limousinCostCenter>' . $data['LIMOUSINE_COST_CENTER'] . '</limousinCostCenter>
            <utilPerc>' . $data['UTILIZATION_PERCENTAGE'] . '</utilPerc>
            <percRate>' . $data['UTILIZATION_PERCENTAGE_RATE'] . '</percRate>
            <utilRecordTime>' . date('d-m-Y H:i:s', strtotime($data['UTILIZATION_RECORD_TIME'])) . '</utilRecordTime>
         </bookingInfo>
		  </setBookingInfo>';
        $xmlr1 = simplexml_load_string($xml_str);

        try {
            $response = $this->soapclient->setBookingInfo($xmlr1);

        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair($data);
            $this->sendEmail('Catch Error In Set Booking Info API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        // fetching booking info
        $page = new Page();
        $booking = $page->getSingle('booking', ['id' => $data['DB_ID']]);

        if (isset($response) && strpos($response->return, 'Error') === false && !$isError) {
            //this is success case
            //print_r($response);
        } elseif (isset($response) && strpos($response->return, 'Error: -1, ORA-00001: unique constraint (FLEET.WM_BOOKING_PK) violated') !== false && $booking->booking_status == 'Not Picked' && $booking->sync == 'N' && !$isError) { // email "Modification on Syncing Online booking with OASIS"
            //this is success case
            //print_r($response);
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            $emailMsg = $this->keyValPair($data, $responseMsg);
            $this->sendEmail('Error In Set Booking Info API', $emailMsg);
            $isError = true;
        }


        if ($isError)
            return false;
        else
            return true;

    }

    private function setCustomerInfo($data)
    {
        $isError = false;
        $xmlr1 = simplexml_load_string('
		  <setCustomerInfo>
			<customerInfo>
            <addressDistrict>' . str_replace('&', 'and', $data['ADDRESS_DISTRICT']) . '</addressDistrict>
            <addressStreet>' . str_replace('&', 'and', $data['ADDRESS_STREET']) . '</addressStreet>
            <dlAttachmentName>' . $data['DL_NAME_COPY'] . '</dlAttachmentName>
            <dlCountry>' . $data['DL_COUNTRY'] . '</dlCountry>
            <dlExpiryDate>' . $data['DL_EXPIRY_DATE'] . '</dlExpiryDate>
            <dlIdNumber>' . $data['DL_ID_NUMBER'] . '</dlIdNumber>
            <dlIdType>' . $data['DL_ID_TYPE'] . '</dlIdType>
            <dobG>' . $data['DOB_G'] . '</dobG>
            <email>' . $data['EMAIL'] . '</email>
            <firstName>' . $data['FIRST_NAME'] . '</firstName>
            <idAttachmentName>' . $data['ID_NAME_COPY'] . '</idAttachmentName>
            <idCopy>' . $data['ID_COPY'] . '</idCopy>
            <idCountry>' . $data['ID_COUNTRY'] . '</idCountry>
            <idDateType>' . $data['ID_DATE_TYPE'] . '</idDateType>
            <idExpiryDate>' . $data['ID_EXPIRY_DATE'] . '</idExpiryDate>
            <idNumber>' . $data['ID_NUMBER'] . '</idNumber>
            <idType>' . $data['ID_TYPE'] . '</idType>
            <jobTitle>' . $data['JOB_TITLE'] . '</jobTitle>
            <lastName>' . $data['LAST_NAME'] . '</lastName>
            <mobile>' . $data['MOBILE'] . '</mobile>
            <nationality>' . $data['NATIONALITY'] . '</nationality>
            <sponsor>' . str_replace('&', 'and', $data['SPONSOR']) . '</sponsor>
         </customerInfo>
		  </setCustomerInfo>');

        try {
            $response = $this->soapclient->setCustomerInfo($xmlr1);

        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair($data);
            $this->sendEmail('Catch Error In Set Customer Info API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        if (isset($response) && strpos($response->return, 'Error') === false && !$isError) {
            //this is success case
            //print_r($response);
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            $emailMsg = $this->keyValPair($data, $responseMsg);
            $this->sendEmail('Error In Set Customer Info API', $emailMsg);
            $isError = true;
        }


        if ($isError)
            return false;
        else
            return true;

    }

    private function setCollectionInfo($data)
    {
        $isError = false;
        $xmlr1 = simplexml_load_string('
		  <setCollectionInfo>
			<collectionInfo>
            <accountCardNo>' . $data['ACCOUNT_CARD_NO'] . '</accountCardNo>
            <bookingId>' . $data['BOOKING_ID'] . '</bookingId>
            <bookingStatus>' . $data['BOOKING_STATUS'] . '</bookingStatus>
            <transAmount>' . $data['TRANS_AMOUNT'] . '</transAmount>
            <transDate>' . $data['TRANS_DATE'] . '</transDate>
            <transMethod>' . $data['TRANS_METHOD'] . '</transMethod>
            <transReference>' . $data['TRANS_REFERENCE'] . '</transReference>
            <transType>' . $data['TRANS_TYPE'] . '</transType>
         </collectionInfo>
		  </setCollectionInfo>');

        try {
            $response = $this->soapclient->setCollectionInfo($xmlr1);

        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair($data);
            $this->sendEmail('Catch Error In Set Collection Info API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        if (isset($response) && strpos($response->return, 'Error') === false && !$isError) {
            //this is success case
            //print_r($response);
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            $emailMsg = $this->keyValPair($data, $responseMsg);
            $this->sendEmail('Error In Set Collection Info API', $emailMsg);
            $isError = true;
        }


        if ($isError)
            return false;
        else
            return true;

    }

    private function setRedeemInfo($data)
    {
        $isError = false;
        $xmlr1 = simplexml_load_string('
		  <setRedeemInfo>
             <redeemInfo>
                <bookingId>' . $data['BOOKING_ID'] . '</bookingId>
                <redeemAmount>' . $data['REDEEM_AMOUNT'] . '</redeemAmount>
                <redeemDate>' . $data['REDEEM_DATE'] . '</redeemDate>
                <redeemPoints>' . $data['REDEEM_POINTS'] . '</redeemPoints>
             </redeemInfo>
		  </setRedeemInfo>');

        try {
            $response = $this->soapclient->setRedeemInfo($xmlr1);

            //$emailMsgASD = $this->keyValPair($response, $data['BOOKING_ID']);
            //$this->sendEmail('SetRedeemResponse', $emailMsgASD);

        } catch (SoapFault $fault) {
            $emailMsg = $this->keyValPair($data);
            $this->sendEmail('Catch Error In Set Redeem Info API', $emailMsg);
            echo "Exception occured:<br>";
            echo $fault->faultcode . " " . $fault->faultstring;
            echo "<br><br>";
            $isError = true;
        }

        if (isset($response) && strpos($response->return, 'Error') === false && !$isError) {
            //this is success case
            //print_r($response);
        } else {
            //this is fail case
            $responseMsg = "";
            if (isset($response)) {
                $responseMsg = $response->return;
            }
            $emailMsg = $this->keyValPair($data, $responseMsg);
            $this->sendEmail('Error In Set Redeem Info API', $emailMsg);
            $isError = true;
        }


        if ($isError)
            return false;
        else
            return true;

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
            $email['ccEmail'] = 'bilal_ejaz@astutesol.com';
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

    private function getBookingStatus($booking_code)
    {
        $xmlr1 = simplexml_load_string('
		    <getBookingStatus>
             <bookingId>' . $booking_code . '</bookingId>
            </getBookingStatus>
        ');

        $response = $this->soapclient->getBookingStatus($xmlr1);
        return $response;

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
            if ($customer_details->mobile_no != '') {
                $this->sendSmsToCustomer($customer_details, $booking_id, $lang);
            }
            if ($customer_details->email != '') {
                $this->sendEmailToCustomer($customer_details, $booking_id, $lang);
            }
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
            $emailMsg = "يوجد لديك استبيان معلق من المفتاح.يرجى إستخدام الرابط لتقييم خدماتنا ";
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
        if ($customer_device_tokens) {
            foreach ($customer_device_tokens as $device_token) {
                $tokens[] = $device_token->token;
            }
        }

        if (count($tokens) > 0) {
            custom::sendPushNotification($title, $message, $tokens, $booking_id);
        }
    }

    private function add_or_deduct_loyalty_points_for_customer($booking_id, $add_or_deduct = 'add')
    {
        $page = new Page();
        $booking_detail = $page->getSingle("booking", array("id" => $booking_id));
        $booking_payment_detail = $page->getSingle("booking_payment", array("booking_id" => $booking_id));
        $redeem_points_used = ($booking_payment_detail->redeem_points > 0 ? $booking_payment_detail->redeem_points : 0);
        if ($booking_detail->type == "individual_customer") {
            $checkForCustomerUid = $page->getSingle('booking_individual_user', array('booking_id' => $booking_id));
            $checkForCustomerId = $page->getSingle('individual_customer', array('uid' => $checkForCustomerUid->uid));
            $ind_customer_id = $checkForCustomerId->id;
        } else {
            $checkForCustomerId = $page->getSingle('booking_individual_guest', array('booking_id' => $booking_id));
            $ind_customer_id = $checkForCustomerId->individual_customer_id;
        }
        // updating customer loyalty points
        $customer_info = $page->getSingle('individual_customer', array('id' => $ind_customer_id));
        if ($customer_info && $customer_info->loyalty_points > 0) {
            $customer_old_points = $customer_info->loyalty_points;
            if ($add_or_deduct == 'add') {
                $customer_new_points = round((int)$customer_old_points, 2) + round((int)$redeem_points_used, 2);
            } elseif ($add_or_deduct == 'deduct') {
                $customer_new_points = round((int)$customer_old_points, 2) - round((int)$redeem_points_used, 2);
            }
            $page->updateData('individual_customer', array('loyalty_points' => $customer_new_points), array('id' => $ind_customer_id));
            // an api call will be here to sync customer loyalty points with oasis system
        }
    }

    private function autoReverseQitafRedeem($booking_id)
    {
        $page = new Page();
        $booking_payment_detail = $page->getSingle('booking_payment', array('booking_id' => $booking_id));
        if ($booking_payment_detail->qitaf_request != "") {
            $data = str_replace('.', ',', $booking_payment_detail->qitaf_request);
            $api_settings = custom::api_settings();
            $data = explode(',', $data);
            $api_url = rtrim($api_settings->qitaf_api_base_url, '/') . '/StcQitafService/ReverseRedeem?mobile=' . ltrim($data[2], '0') . '&branch=' . $data[3] . '&request_id=' . $data[1] . '&request_date=' . $data[0];
            $this->logQitafResponse($api_url, 'ReverseRedeemRequest');
            $curlResponse = $this->sendCurlRequest($api_url);
            $this->logQitafResponse($curlResponse, 'ReverseRedeemResponse');
        }
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

    public function mark_app_popup_promo_as_used(Request $request) {
        $updated = DB::table('app_popup_promo_codes_list')->where('promo_code', $request->UsedPromo)->update(['is_used' => 1, 'used_at' => date('Y-m-d H:i:s')]);
        if ($updated) {
            echo 'Promo Code Marked As Used!';die;
        } else {
            echo 'Something Went Wrong!';die;
        }
    }


}

?>