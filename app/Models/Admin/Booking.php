<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;

class Booking extends Model
{

    protected $table = 'booking';

    public function getLatestBookingForEachUser($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $search_keyword = "", $getOrderLimit = true, $getNullCount = false)
    {
        //echo $frontEndUserId;exit();
        // checking if we are getting bookings as current bookings or history bookings
        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "
            (
            (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			)
			and (b.sync != 'N')
			";
        } else {
            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked') 
			or (b.booking_status='Walk in') 
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";
        }
        // if $frontEndUserId is greater than 0 than its coming from frontend else its from backend
        //echo $sort_by;exit();
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*,
               cm.eng_title as car_eng_title,
               bf.eng_title as branch_eng_from,
               bt.eng_title as branch_eng_to";
            if ($frontEndUserId == 0 && $getOrderLimit == true) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )

left join branch bf on b.from_location=bf.id
left join branch bt on b.to_location=bt.id
left join booking_cc_payment bcp on b.id=bcp.booking_id
left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
left join booking_individual_payment_method bipm on b.id=bipm.booking_id
left join booking_corporate_invoice bci on b.id=bci.booking_id";


        if ($search_keyword != "" && $frontEndUserId == 0) // being used for searching in backend
        {
            $query .= " 
        left join booking_individual_guest big on b.id=big.booking_id
        left join individual_customer ic2 on big.individual_customer_id=ic2.id
        
        left join booking_individual_user biu on b.id=biu.booking_id
        left join individual_customer ic on biu.uid=ic.uid
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        ";
        } elseif ($search_keyword == "" && $frontEndUserId > 0) { // being used for frontend user
            $customer_type = Session::get('user_type'); // logged in user type from session
            if ($customer_type == "corporate_customer") {
                $query .= "         
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        ";
            } else {
                $query .= " 
        left join booking_individual_user biu on b.id=biu.booking_id
        ";
            }
        }


        $query .= " where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        if ($frontEndUserId > 0) {
            $customer_type = Session::get('user_type');
            if ($customer_type == "corporate_customer") {
                $query .= " and bcc.uid = " . $frontEndUserId;
            } else {
                $query .= " and biu.uid = " . $frontEndUserId;
            }
        }
        if ($getNullCount == true) {
            $query .= " and b.sync = 'N' ";
        }

        if ($search_keyword != "") {
            $query .= " and (b.reservation_code = '" . $search_keyword . "' OR b.type = '" . $search_keyword . "' OR ic.id_no = '" . $search_keyword . "' OR ic2.id_no = '" . $search_keyword . "' OR ic.mobile_no = '" . $search_keyword . "' OR ic2.mobile_no = '" . $search_keyword . "' OR bcp.transaction_id = '" . $search_keyword . "' OR bsp.s_transaction_id = '" . $search_keyword . "' OR cc.company_code = '" . $search_keyword . "' OR cd.id_no = '" . $search_keyword . "' OR cd.mobile_no = '" . $search_keyword . "') ";
        }

        $query .= $sort_by . " " . $limit;
        //.$sort_by." ".$limit." ";
        if (!$getNullCount && !$count_only) {
            // echo $query;exit();
        }
        $bookings = DB::select($query);

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }

    public function getLatestBookingForEachUserNew($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $search_keyword_customer = "", $getOrderLimit = true, $getNullCount = false, $search_keyword_booking = "",$search_type = "")
    {
        //echo $frontEndUserId;exit();
        // checking if we are getting bookings as current bookings or history bookings
        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "
            (
            (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			)
			and (b.sync != 'N')
			";
        } else if ($records_for == 'human_less_bookings') {
            $criteriaForGettingBookings = "
            (
            (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			)
			and (b.sync != 'N')
			and (b.oasis_contract_id != '') 
			";
        } else {
            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked') 
			or (b.booking_status='Walk in') 
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";
        }
        // if $frontEndUserId is greater than 0 than its coming from frontend else its from backend
        //echo $sort_by;exit();
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*, cm.eng_title as car_eng_title, cm.year as car_model_year, ct.eng_title as car_type_eng_title, bf.eng_title as branch_eng_from, bt.eng_title as branch_eng_to, cc.company_name_en as eng_company_name, bp.cpid, bp.subscribe_for_months ";
            if ($frontEndUserId == 0 && $getOrderLimit == true) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b
                  left join booking_payment bp on b.id=bp.booking_id
                  left join booking_cc_payment bcp on b.id=bcp.booking_id
                  left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
                  left join booking_individual_payment_method bipm on b.id=bipm.booking_id
                  left join booking_corporate_invoice bci on b.id=bci.booking_id
                  left join car_model cm on b.car_model_id=cm.id
                  left join car_type ct on cm.car_type_id = ct.id
                  left join branch bf on b.from_location=bf.id
                  left join branch bt on b.to_location=bt.id
    

        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
";


        if ($search_keyword_customer != "" && $frontEndUserId == 0) // being used for searching in backend
        {
            /*$query .= "
        left join booking_individual_guest big on b.id=big.booking_id
        left join individual_customer ic2 on big.individual_customer_id=ic2.id

        left join booking_individual_user biu on b.id=biu.booking_id
        left join individual_customer ic on biu.uid=ic.uid


        left join corporate_driver cd on bcc.driver_id=cd.id
        ";*/

            $query .= " 
        left join booking_individual_guest big on b.id=big.booking_id        
        
        left join booking_individual_user biu on b.id=biu.booking_id ";

            if ($search_keyword_customer == "" && $search_type == "") {

                $query .= " left join individual_customer ic on (biu.uid=ic.uid or big.individual_customer_id=ic.id) ";

            }


            $query .= " left join corporate_driver cd on bcc.driver_id=cd.id
        ";

        } elseif ($search_keyword_customer == "" && $frontEndUserId > 0) { // being used for frontend user
            $customer_type = Session::get('user_type'); // logged in user type from session
            if ($customer_type == "corporate_customer") {
                $query .= "         
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        ";
            } else {
                $query .= " 
        left join booking_individual_user biu on b.id=biu.booking_id
        ";
            }
        }


        $query .= " WHERE
         (
        " . $criteriaForGettingBookings . "
            ) AND (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit' or bci.payment_status='paid') ";

        if ($frontEndUserId > 0) {
            $customer_type = Session::get('user_type');
            if ($customer_type == "corporate_customer") {
                $query .= " and bcc.uid = " . $frontEndUserId;
            } else {
                $query .= " and biu.uid = " . $frontEndUserId;
            }
        }
        if ($getNullCount == true) {
            $query .= " and b.sync = 'N' ";
        }

        if ($search_keyword_customer != "") {
            /*$query .= " and (ic.id_no = '" . $search_keyword_customer . "' OR ic2.id_no = '" . $search_keyword_customer . "' OR ic.mobile_no = '" . $search_keyword_customer . "' OR ic2.mobile_no = '" . $search_keyword_customer . "' OR bcp.transaction_id = '" . $search_keyword_customer . "' OR bsp.s_transaction_id = '" . $search_keyword_customer . "' OR cc.company_code = '" . $search_keyword_customer . "' OR cd.id_no = '" . $search_keyword_customer . "' OR cd.mobile_no = '" . $search_keyword_customer . "') ";*/

            if($search_type == "ind_id_no"){
                $row = $this->getCustomerIDBy($search_keyword_customer);
                /*echo '<pre>';
                print_r($row);
                exit();*/
                if($row && $row[0]->uid == 0){
                    $query .= " and big.individual_customer_id = '".$row[0]->id."' ";
                }else{
                    $query .= " and biu.uid = '".(isset($row[0]->uid)?$row[0]->uid:$search_keyword_customer)."' ";
                }

            }elseif ($search_type == "corp_id_no"){
                $query .= " and cd.id_no = '" . $search_keyword_customer . "' ";
            }elseif ($search_type == "mobile_no"){
                $query .= " and (ic.mobile_no = '" . $search_keyword_customer . "' OR cd.mobile_no = '" . $search_keyword_customer . "' ) ";
            }elseif ($search_type == "transaction_id"){
                $query .= " and (bcp.transaction_id = '" . $search_keyword_customer . "' OR bsp.s_transaction_id = '" . $search_keyword_customer . "' ) ";
            }elseif ($search_type == "company_code"){
                $query .= " and (cc.company_code = '" . $search_keyword_customer . "' ) ";
            }else{
                $query .= " and (ic.id_no = '" . $search_keyword_customer . "' OR ic.mobile_no = '" . $search_keyword_customer . "' 
                OR bcp.transaction_id = '" . $search_keyword_customer . "' OR bsp.s_transaction_id = '" . $search_keyword_customer . "' 
                OR cc.company_code = '" . $search_keyword_customer . "' OR cd.id_no = '" . $search_keyword_customer . "' 
                OR cd.mobile_no = '" . $search_keyword_customer . "' ) ";
            }

        } elseif ($search_keyword_booking != "") {
            $query .= " and (b.reservation_code = '" . $search_keyword_booking . "' OR b.type = '" . $search_keyword_booking . "') ";
        }

        $query .= $sort_by . " " . $limit;
        //.$sort_by." ".$limit." ";
        if (!$getNullCount && !$count_only) {
            // echo $query;exit();
        }
        $bookings = DB::select($query);
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }

    private function getCustomerIDBy($id_no){
        $result = "SELECT * FROM `individual_customer` WHERE `id_no` = '".$id_no."' ";
        $result = DB::select($result);
        return $result;
    }

    public function getLatestBookingForEachUserForFrontend($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $search_keyword = "", $getOrderLimit = true, $getNullCount = false,$booking_id=0)
    {
        //echo $frontEndUserId;exit();
        // checking if we are getting bookings as current bookings or history bookings
        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "
            (
            (b.booking_status='Completed') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			)
			and (b.sync != 'N')
			";
        } else {
            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked')
			or (b.booking_status='Completed with Overdue')  
			or (b.booking_status='Walk in') 
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";
        }
        // if $frontEndUserId is greater than 0 than its coming from frontend else its from backend
        //echo $sort_by;exit();
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*,
            ct.eng_title as car_type_eng_title, 
            ct.arb_title as car_type_arb_title,
            car_cat.eng_title as car_category_eng_title, 
            car_cat.arb_title as car_category_arb_title,
              bp.total_sum as total_sum, 
              bp.no_of_days as no_of_days, 
              cm.year as year, 
              cm.image1 as image1, 
              cm.image1_eng_alt as image1_eng_alt,
              cm.image1_arb_alt as image1_arb_alt, 
              cm.no_of_passengers as no_of_passengers, 
              cm.min_age as min_age, 
              cm.no_of_bags as no_of_bags, 
              cm.no_of_doors as no_of_doors,
               cm.transmission as transmission, 
               cm.eng_title as car_eng_title,
               cm.arb_title as car_arb_title, 
               bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from,
               bf.city_id as city_from, 
               bt.eng_title as branch_eng_to,
               bt.arb_title as branch_arb_to, 
               bf.city_id as city_to,
                 (select br.eng_title from branch br 
                where br.id=b.from_location) as branch_eng_from";
            if ($frontEndUserId == 0 && $getOrderLimit == true) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )

left join branch bf on b.from_location=bf.id
left join branch bt on b.to_location=bt.id
left join booking_cc_payment bcp on b.id=bcp.booking_id
left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
left join booking_individual_payment_method bipm on b.id=bipm.booking_id
left join booking_corporate_invoice bci on b.id=bci.booking_id
left join car_type ct on cm.car_type_id = ct.id 
left join car_group cg on ct.car_group_id = cg.id 
left join car_category car_cat on cg.car_category_id = car_cat.id 
left join booking_payment bp on b.id = bp.booking_id
";


        if ($search_keyword != "" && $frontEndUserId == 0) // being used for searching in backend
        {
            $query .= " 
        left join booking_individual_guest big on b.id=big.booking_id
        left join individual_customer ic2 on big.individual_customer_id=ic2.id
        
        left join booking_individual_user biu on b.id=biu.booking_id
        left join individual_customer ic on biu.uid=ic.uid
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        ";
        } elseif ($search_keyword == "" && $frontEndUserId > 0) { // being used for frontend user
            $customer_type = Session::get('user_type'); // logged in user type from session
            if ($customer_type == "corporate_customer") {
                $query .= "         
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        ";
            } else {
                $query .= " 
        left join booking_individual_user biu on b.id=biu.booking_id
        ";
            }
        }


        $query .= " where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        if ($frontEndUserId > 0) {
            $customer_type = Session::get('user_type');
            if ($customer_type == "corporate_customer") {
                $query .= " and bcc.uid = " . $frontEndUserId;
            } else {
                $query .= " and biu.uid = " . $frontEndUserId;
            }
        }
        if ($getNullCount == true) {
            $query .= " and b.sync = 'N' ";
        }

        if ($search_keyword != "") {
            $query .= " and (b.reservation_code = '" . $search_keyword . "' OR b.type = '" . $search_keyword . "' OR ic.id_no = '" . $search_keyword . "' OR ic2.id_no = '" . $search_keyword . "' OR ic.mobile_no = '" . $search_keyword . "' OR ic2.mobile_no = '" . $search_keyword . "' OR bcp.transaction_id = '" . $search_keyword . "' OR bsp.s_transaction_id = '" . $search_keyword . "' OR cc.company_code = '" . $search_keyword . "' OR cd.id_no = '" . $search_keyword . "' OR cd.mobile_no = '" . $search_keyword . "') ";
        }

        if($booking_id > 0){
            $query .= " and b.id = '". $booking_id ."'";
        }
        $query .= $sort_by . " " . $limit;
        //.$sort_by." ".$limit." ";

        //echo $query;exit();
        $bookings = DB::select($query);

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }


    public function exportBooking($booking_ids = "")
    {

        $criteriaForGettingBookings = "(b.booking_status='Not Picked')
        or (b.booking_status='Picked')
        or (b.booking_status='Walk in')
        or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
        or (b.booking_status='Expired' and b.sync = 'N')
        ";


        //note: we added new DROPOFF_CHARGES
        $selectCols = "b.reservation_code as BOOKING_ID,
            case
            when b.booking_source = 'website' then 'W'
            when b.booking_source = 'mobile' then 'M'
            when b.booking_source = 'android' then 'A'
            when b.booking_source = 'ios' then 'I'
            end
            as BOOKING_SOURCE,
            case
            when b.type = 'guest' then ic2.id_no
            when b.type = 'individual_customer' then ic.id_no
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' AND bcc.customer_id_no_for_loyalty is not null AND bcc.customer_id_no_for_loyalty !='' then bcc.customer_id_no_for_loyalty
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' AND (bcc.customer_id_no_for_loyalty is null || bcc.customer_id_no_for_loyalty ='') then cd.id_no
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') AND (bcc.customer_id_no_for_loyalty is null || bcc.customer_id_no_for_loyalty ='') then cc.company_code
            end
            as CUSTOMER_ID,
            case
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' then 'P'
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') then 'I'
            when b.type != 'corporate_customer' then 'P'
            end
            as CUSTOMER_TYPE,
            case
            when b.type = 'guest' then ic2.id_no
            when b.type = 'individual_customer' then ic.id_no
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' then 'Call Center'
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') then cd.id_no
            end
            as DRIVER_ID,
            cm.oracle_reference_number as CAR_TYPE,
            cm.year as CAR_MODEL,
            bf.oracle_reference_number as OPENING_BRANCH,
            DATE_FORMAT(b.from_date, '%d-%m-%Y %T') as APPLIES_FROM,
            bt.oracle_reference_number as CLOSING_BRANCH,
            DATE_FORMAT(b.to_date, '%d-%m-%Y %T') as APPLIES_TO,
            srt.oracle_reference_number as RENTING_TYPE_ID,			
            bp.rent_price as RENT_PRICE,
            
            if (bp.cdw_price > 0 , bp.cdw_price, '') as CDW_PRICE,
            if (bp.gps_price > 0 , bp.gps_price, '') as GPS_PRICE,
            if (bp.extra_driver_price > 0 , bp.extra_driver_price, '') as EXTRA_DRIVER_PRICE,
            if (bp.baby_seat_price > 0 , bp.baby_seat_price, '') as BABY_SEAT_PRICE,
            if (bp.delivery_charges > 0 , bp.delivery_charges, '') as DELIVERY_CHARGES,
            if (bp.discount_price > 0 AND bp.is_promo_discount_on_total = 0 , bp.discount_price, '') as DISCOUNT_PRICE,
            if (bp.promotion_offer_code_used is null OR bp.promotion_offer_code_used = '', bp.promotion_offer_id, bp.promotion_offer_code_used) as PROMOTION_OFFER_ID,
            
            case
            when b.type = 'guest' then replace(ic2.mobile_no, '+', '')
            when b.type = 'individual_customer' then replace(ic.mobile_no, '+', '')
            when b.type = 'corporate_customer' then replace(cd.mobile_no, '+', '')
            end
            as MOBILE,
            
            case
            when b.is_delivery_mode = 'no' then 'NO'
            when b.is_delivery_mode = 'yes' then 'YES'
            when b.is_delivery_mode = 'hourly' then 'NO'
            when b.is_delivery_mode = 'subscription' then 'NO'
            end
            as IS_DELIVERY_TYPE,
            
            SUBSTRING_INDEX(b.pickup_delivery_lat_long , ',', 1 ) AS PICKUP_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.pickup_delivery_lat_long , ',', 2 ),',',-1) AS PICKUP_LONGITUDE,
            
            SUBSTRING_INDEX(b.dropoff_delivery_lat_long , ',', 1 ) AS DROPOFF_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.dropoff_delivery_lat_long , ',', 2 ),',',-1) AS DROPOFF_LONGITUDE,
             
            case
            when b.is_delivery_mode = 'no' then 'N'
            when b.is_delivery_mode = 'yes' then 'N'
            when b.is_delivery_mode = 'hourly' then 'Y'
            when b.is_delivery_mode = 'subscription' then 'N'
            end
            as IS_HOURLY_TYPE,
            
            bp.qitaf_request as QITAF_REDEEM_ID,
            
            bp.loyalty_program_for_oracle as LOYALTY_PROGRAM_ID,
            
            bp.qitaf_amount as QITAF_AMOUNT,
            
            if (bp.cdw_plus_price > 0 , bp.cdw_plus_price, '') as CDW_PLUS,
            
            bp.niqaty_request as NIQATY_REDEEM_ID,
            
            bp.niqaty_amount as NIQATY_AMOUNT,
            
            if (bp.discount_price > 0 AND bp.is_promo_discount_on_total = 1 , bp.discount_price, '') as DISCOUNT_PRICE_ON_TOTAL,
            
            case
            when bp.car_rate_is_with_additional_utilization_rate = 0 then 'N'
            when bp.car_rate_is_with_additional_utilization_rate = 1 then 'Y'
            end
            as IS_CAR_RATE_WITH_ADDITIONAL_UTILIZATION_RATE,
            
            case
            when b.is_delivery_mode = 'subscription' then bp.subscribe_for_months
            when b.is_delivery_mode = 'yes' AND b.subscription_with_delivery_flow = 'on' then bp.subscribe_for_months
            when b.is_delivery_mode != 'subscription' then 0
            end
            as SUBSCRIBE_FOR_MONTHS,
            
            bp.three_month_subscription_price_for_car as THREE_MONTH_SUBSCRIPTION_PRICE,
            bp.six_month_subscription_price_for_car as SIX_MONTH_SUBSCRIPTION_PRICE,
            bp.nine_month_subscription_price_for_car as NINE_MONTH_SUBSCRIPTION_PRICE,
            bp.twelve_month_subscription_price_for_car as TWELVE_MONTH_SUBSCRIPTION_PRICE,
            
            case
            when bp.is_free_cdw_promo_applied = 0 then 'N'
            when bp.is_free_cdw_promo_applied = 1 then 'Y'
            end
            as IS_FREE_CDW_PROMO_APPLIED,
            
            case
            when bp.is_free_cdw_plus_promo_applied = 0 then 'N'
            when bp.is_free_cdw_plus_promo_applied = 1 then 'Y'
            end
            as IS_FREE_CDW_PLUS_PROMO_APPLIED,
            
            case
            when bp.is_free_baby_seat_promo_applied = 0 then 'N'
            when bp.is_free_baby_seat_promo_applied = 1 then 'Y'
            end
            as IS_FREE_BABY_SEAT_PROMO_APPLIED,
            
            case
            when bp.is_free_driver_promo_applied = 0 then 'N'
            when bp.is_free_driver_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DRIVER_PROMO_APPLIED,
            
            case
            when bp.is_free_open_km_promo_applied = 0 then 'N'
            when bp.is_free_open_km_promo_applied = 1 then 'Y'
            end
            as IS_FREE_OPEN_KM_PROMO_APPLIED,
            
            case
            when bp.is_free_delivery_promo_applied = 0 then 'N'
            when bp.is_free_delivery_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DELIVERY_PROMO_APPLIED,
            
            case
            when bp.is_free_dropoff_promo_applied = 0 then 'N'
            when bp.is_free_dropoff_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DROPOFF_PROMO_APPLIED,
            
            bp.mokafaa_request as MOKAFAA_REDEEM_ID,
            
            bp.mokafaa_amount as MOKAFAA_AMOUNT,
            
            bp.anb_request as ANB_REDEEM_ID,
            
            bp.anb_amount as ANB_AMOUNT,
            
            b.is_limousine as IS_LIMOUSINE,
            
            b.is_round_trip as IS_ROUND_TRIP,
            
            b.flight_no as FLIGHT_NUMBER,
            
            b.waiting_extra_hours as WAITING_EXTRA_HOURS,
            
            b.waiting_extra_hours_charges as WAITING_EXTRA_HOURS_CHARGES,
            
            b.limousine_cost_center as LIMOUSINE_COST_CENTER,
            
            bp.utilization_percentage as UTILIZATION_PERCENTAGE,
            
            bp.utilization_percentage_rate as UTILIZATION_PERCENTAGE_RATE,
            
            bp.utilization_record_time as UTILIZATION_RECORD_TIME
            
             ";


        $query = "SELECT " . $selectCols . " FROM `booking` b";


        $query .= " join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )
        and b.sync='N'";

        if ($booking_ids != '') {
            $query .= "and b.id in (" . $booking_ids . ")";
        }

        $query .= "left join branch bf on b.from_location=bf.id
        
        left join branch bt on b.to_location=bt.id
        left join booking_cancel book_c on b.id=book_c.booking_id and book_c.sync='N'
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
		left join booking_individual_payment_method bipm on b.id=bipm.booking_id
        left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'
        left join booking_corporate_invoice bci on b.id=bci.booking_id";


        $query .= " left join car_type ct on cm.car_type_id = ct.id
         left join car_group cg on ct.car_group_id = cg.id
         left join car_category car_cat on cg.car_category_id = car_cat.id
         left join booking_payment bp on b.id = bp.booking_id
         left join users u on biu.uid=u.id
         left join individual_customer ic on u.id=ic.uid		 
		 left join setting_renting_type srt on b.renting_type_id=srt.id		 
         left join booking_corporate_customer bcc on b.id=bcc.booking_id and b.type='corporate_customer'
         left join users u2 on bcc.uid=u2.id
         left join corporate_customer cc on FIND_IN_SET(u2.id, cc.uid) > 0
         left join corporate_driver cd on bcc.driver_id=cd.id
         left join booking_individual_guest big on b.id=big.booking_id and b.type='guest'
         left join individual_customer ic2 on big.individual_customer_id=ic2.id where (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit' or bci.payment_status='paid') ";

        /*echo $query;
        exit();*/

        $bookings = DB::select($query);

        return $bookings;
    }

    public function exportBookingSyncStatus($commaSepBIds)
    {
        $query = "UPDATE booking set sync='M', synced_at='" . date('Y-m-d H:i:s') . "' where reservation_code in (" . $commaSepBIds . ")";
        $bookings = DB::statement($query);
        return true;
    }

    public function exportCancelledBookingSyncStatus($booking_id)
    {
        $query = "UPDATE booking_cancel set sync='M', synced_at='" . date('Y-m-d H:i:s') . "' where booking_id = '" . $booking_id . "'";
        $bookings = DB::statement($query);
        return true;
    }

    public function exportUsers($booking_ids = "")
    {
        $selectCols = "ic.id as db_id,
        ic.id_no as id_no,
        ic.id_type as id_type,
        ic.first_name as first_name,
        ic.last_name as last_name,
        ic.mobile_no as mobile_no,
        ic.email as email,
        ic.nationality as nationality,
        if (ic.dob = '00-00-0000' , '', DATE_FORMAT(ic.dob, '%d-%m-%Y')) as dob,
        if (ic.id_expiry_date = '00-00-0000' , '', DATE_FORMAT(ic.id_expiry_date, '%d-%m-%Y')) as id_expiry_date,
        ic.id_version as id_copy,		
		ic.id_image as id_image,
        ic.id_country as id_country,
        ic.license_no as license_no,
        ic.license_id_type as license_id_type,
        if (ic.license_expiry_date = '00-00-0000' , '', DATE_FORMAT(ic.license_expiry_date, '%d-%m-%Y')) as license_expiry_date,
        ic.license_image as license_copy,
        ic.id_date_type as id_date_type,
        ic.license_country as license_id_country,
        ic.job_title as job_title,
        ic.sponsor as sponsor,
        ic.street_address as street_address,
        ic.district_address as district_address,
        
        icg.id as icg_db_id,
        icg.id_no as icg_id_no,
        icg.id_type as icg_id_type,
        icg.first_name as icg_first_name,
        icg.last_name as icg_last_name,
        icg.mobile_no as icg_mobile_no,
        icg.email as icg_email,
        icg.nationality as icg_nationality,
        if (icg.dob = '00-00-0000' , '', DATE_FORMAT(icg.dob, '%d-%m-%Y')) as icg_dob,
        if (icg.id_expiry_date = '00-00-0000' , '', DATE_FORMAT(icg.id_expiry_date, '%d-%m-%Y')) as icg_id_expiry_date,
        icg.id_version as icg_id_copy,		
		icg.id_image as icg_id_image,		
        icg.id_country as icg_id_country,
        icg.license_no as icg_license_no,
        icg.license_id_type as icg_license_id_type,
        if (icg.license_expiry_date = '00-00-0000' , '', DATE_FORMAT(icg.license_expiry_date, '%d-%m-%Y')) as icg_license_expiry_date,
        icg.license_image as icg_license_copy,
        icg.id_date_type as icg_id_date_type,
        icg.license_country as icg_license_id_country,
        icg.job_title as icg_job_title,
        icg.sponsor as icg_sponsor,
        icg.street_address as icg_street_address,
        icg.district_address as icg_district_address,
        
        cd.id as cd_db_id,
        cd.id_no as cd_id_no,
        cd.id_type as cd_id_type,
        cd.first_name as cd_first_name,
        cd.last_name as cd_last_name,
        cd.mobile_no as cd_mobile_no,
        cd.email as cd_email,
        '' as cd_nationality,
        '' as cd_dob,
        '' as cd_id_expiry_date,
        '' as cd_id_copy,		
        '' as cd_id_country,
        '' as cd_id_image,	
        cd.license_no as cd_license_no,
        '' as cd_license_id_type,
        '' as cd_license_expiry_date,
        '' as cd_license_copy,
        'H' as cd_id_date_type,
        '' as cd_license_id_country,
        '' as cd_job_title,
        cd.sponsor as cd_sponsor,
        '' as cd_street_address,
        '' as cd_district_address,
        b.type as booking_type
        ";


        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_individual_user as biu on b.id=biu.booking_id
        left join individual_customer as ic on biu.uid=ic.uid
        
        left join booking_individual_guest as big on b.id=big.booking_id
        left join individual_customer as icg on big.individual_customer_id=icg.id
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        
        WHERE 1=1 
        
        ";

        if ($booking_ids != '') {
            $query .= "AND b.reservation_code in (" . $booking_ids . ")";
        }

        /*$query .= " group by
        case
        when ic.id_no = '' then icg.id_no
        when icg.id_no = '' then ic.id_no
        end";*/

        $query .= " group by ic.id_no, icg.id_no";

        //echo $query;
        //exit();

        $bookings = DB::select($query);

        return $bookings;
    }


    public function exportCorporatePayLaterUsers($booking_ids = "")
    {
        $selectCols = "ic.id as db_id,
        ic.id_no as id_no,
        ic.id_type as id_type,
        ic.first_name as first_name,
        ic.last_name as last_name,
        ic.mobile_no as mobile_no,
        ic.email as email,
        ic.nationality as nationality,
        if (ic.dob = '00-00-0000' , '', DATE_FORMAT(ic.dob, '%d-%m-%Y')) as dob,
        if (ic.id_expiry_date = '00-00-0000' , '', DATE_FORMAT(ic.id_expiry_date, '%d-%m-%Y')) as id_expiry_date,
        ic.id_version as id_copy,		
		ic.id_image as id_image,
        ic.id_country as id_country,
        ic.license_no as license_no,
        ic.license_id_type as license_id_type,
        if (ic.license_expiry_date = '00-00-0000' , '', DATE_FORMAT(ic.license_expiry_date, '%d-%m-%Y')) as license_expiry_date,
        ic.license_image as license_copy,
        ic.id_date_type as id_date_type,
        ic.license_country as license_id_country,
        ic.job_title as job_title,
        ic.sponsor as sponsor,
        ic.street_address as street_address,
        ic.district_address as district_address,
        
        icg.id as icg_db_id,
        icg.id_no as icg_id_no,
        icg.id_type as icg_id_type,
        icg.first_name as icg_first_name,
        icg.last_name as icg_last_name,
        icg.mobile_no as icg_mobile_no,
        icg.email as icg_email,
        icg.nationality as icg_nationality,
        if (icg.dob = '00-00-0000' , '', DATE_FORMAT(icg.dob, '%d-%m-%Y')) as icg_dob,
        if (icg.id_expiry_date = '00-00-0000' , '', DATE_FORMAT(icg.id_expiry_date, '%d-%m-%Y')) as icg_id_expiry_date,
        icg.id_version as icg_id_copy,		
		icg.id_image as icg_id_image,		
        icg.id_country as icg_id_country,
        icg.license_no as icg_license_no,
        icg.license_id_type as icg_license_id_type,
        if (icg.license_expiry_date = '00-00-0000' , '', DATE_FORMAT(icg.license_expiry_date, '%d-%m-%Y')) as icg_license_expiry_date,
        icg.license_image as icg_license_copy,
        icg.id_date_type as icg_id_date_type,
        icg.license_country as icg_license_id_country,
        icg.job_title as icg_job_title,
        icg.sponsor as icg_sponsor,
        icg.street_address as icg_street_address,
        icg.district_address as icg_district_address,
        
        cd.id as cd_db_id,
        cd.id_no as cd_id_no,
        cd.id_type as cd_id_type,
        cd.first_name as cd_first_name,
        cd.last_name as cd_last_name,
        cd.mobile_no as cd_mobile_no,
        cd.email as cd_email,
        '' as cd_nationality,
        '' as cd_dob,
        '' as cd_id_expiry_date,
        '' as cd_id_copy,		
        '' as cd_id_country,
        '' as cd_id_image,	
        cd.license_no as cd_license_no,
        '' as cd_license_id_type,
        '' as cd_license_expiry_date,
        '' as cd_license_copy,
        'H' as cd_id_date_type,
        '' as cd_license_id_country,
        '' as cd_job_title,
        cd.sponsor as cd_sponsor,
        '' as cd_street_address,
        '' as cd_district_address,
        b.type as booking_type
        ";


        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_individual_user as biu on b.id=biu.booking_id
        left join individual_customer as ic on biu.uid=ic.uid
        
        left join booking_individual_guest as big on b.id=big.booking_id
        left join individual_customer as icg on big.individual_customer_id=icg.id
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        
        WHERE 1=1 
        
        ";

        if ($booking_ids != '') {
            $query .= "AND b.reservation_code in (" . $booking_ids . ")";
        }

        /*$query .= " group by
        case
        when ic.id_no = '' then icg.id_no
        when icg.id_no = '' then ic.id_no
        end";*/

        $query .= " group by cd_id_no";

        //echo $query;
        //exit();

        $bookings = DB::select($query);

        return $bookings;
    }


    /*public function exportPaymentCollection($booking_ids = "")
    {
        $selectCols = "b.reservation_code as BOOKING_ID,
        case
        when b.booking_status = 'Cancelled' then 'C'
        when b.booking_status != 'Cancelled' then 'A'
        end
        as BOOKING_STATUS,
        'P' as TRANS_TYPE,


        case
          WHEN bipm.payment_method = 'Credit Card' then
           case
           WHEN bccp.card_brand = 'Visa' THEN 'PT_VISA'
           WHEN bccp.card_brand = 'Master Card' THEN 'PT_MC'
           end
          WHEN bipm.payment_method = 'Cash' THEN 'CASH'
          end
                as TRANS_METHOD,

          case
           WHEN bipm.payment_method = 'Credit Card' then bccp.transaction_id
           WHEN bipm.payment_method = 'Cash' then ''
          end
          as TRANS_REFERENCE,

                 case
           WHEN bipm.payment_method = 'Credit Card' then CONCAT(bccp.first_4_digits,'********', bccp.last_4_digits)
           WHEN bipm.payment_method = 'Cash' then ''
           end
           as ACCOUNT_CARD_NO,
    
          case
           WHEN bipm.payment_method = 'Credit Card' then DATE_FORMAT(bccp.trans_date, '%d-%m-%Y %T')
           WHEN bipm.payment_method = 'Cash' then DATE_FORMAT(b.created_at, '%d-%m-%Y %T')
           end
           as TRANS_DATE,

         bp.total_sum as TRANS_AMOUNT
         ";
        // , bc.cancel_time as CANCELLED_DATE_TIME,
        // bc.cancel_charges as CANCELLED_CHARGES
        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_payment as bp on b.id=bp.booking_id";

        if ($booking_ids != '') {
            $query .= " WHERE b.reservation_code in (" . $booking_ids . ")";
        }

        echo $query;
        exit();

        $bookings = DB::select($query);

        return $bookings;

    }*/


    public function exportPaymentCollection($booking_ids = "")
    {
        $selectCols1 = "b.id, b.reservation_code as BOOKING_ID,
        'A' as BOOKING_STATUS,
        'P' as TRANS_TYPE,
  
  
        case
          WHEN bipm.payment_method = 'Credit Card' then
           case
                WHEN bccp.payment_company is null AND bccp.card_brand = 'Visa' THEN 'PT_VISA'
                WHEN bccp.payment_company is null AND bccp.card_brand = 'Master Card' THEN 'PT_MC'
                WHEN bccp.payment_company is null AND bccp.card_brand = 'MasterCard' THEN 'PT_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Visa' THEN 'ST_Visa'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Master Card' THEN 'ST_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'MasterCard' THEN 'ST_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Master' THEN 'ST_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Mada' THEN 'ST_MADA'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-MADA' THEN 'ST_MADA'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-MasterCard' THEN 'ST_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-Visa' THEN 'ST_Visa'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ST_MC' THEN 'ST_MC'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ST_Visa' THEN 'ST_Visa'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Electron' THEN 'ST_Visa'
                WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-MADA' THEN 'ST_MADA'
               WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-MasterCard' THEN 'ST_MC'
               WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-Visa' THEN 'ST_Visa'
                
                WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'VISA' OR bccp.card_brand = 'Visa') THEN 'HP_Visa'
                WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'Master Card' OR bccp.card_brand = 'MasterCard' OR bccp.card_brand = 'Master' OR bccp.card_brand = 'MASTER') THEN 'HP_MC'
                WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'MADA' OR bccp.card_brand = 'Mada') THEN 'HP_Mada'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'STC_PAY' THEN 'HP_STCP'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'AMEX' THEN 'HP_Amex'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'APPLE_PAY' THEN 'HP_Apple'
           end
           
          WHEN bipm.payment_method = 'Cash' THEN 'CASH'
          WHEN bipm.payment_method = 'Sadad' THEN 'PT_SD'
          WHEN bipm.payment_method = 'Corporate Credit' THEN 'CREDIT'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'Master Card' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'Master' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'Mada' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'ApplePay-MADA' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'ApplePay-MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'ApplePay-Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'Electron' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'ST_MC' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'ST_Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'GooglePay-MADA' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'GooglePay-MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'sts' AND bci.card_brand = 'GooglePay-Visa' THEN 'ST_Visa'
          
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND (bci.card_brand = 'VISA' OR bci.card_brand = 'Visa') THEN 'HP_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND (bci.card_brand = 'Master Card' OR bci.card_brand = 'MasterCard' OR bci.card_brand = 'Master' OR bci.card_brand = 'MASTER') THEN 'HP_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND (bci.card_brand = 'MADA' OR bci.card_brand = 'Mada') THEN 'HP_Mada'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND bci.card_brand = 'STC_PAY' THEN 'HP_STCP'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND bci.card_brand = 'AMEX' THEN 'HP_Amex'
          WHEN bipm.payment_method = 'Pay Later' AND bci.cc_company = 'hyper_pay' AND bci.card_brand = 'APPLE_PAY' THEN 'HP_Apple'
          end
                as TRANS_METHOD,
          
          case
           WHEN bipm.payment_method = 'Credit Card' then bccp.transaction_id 
           WHEN bipm.payment_method = 'Sadad' then bsp.s_transaction_id 
           WHEN bipm.payment_method = 'Cash' then '' 
           WHEN bipm.payment_method = 'Corporate Credit' then ''
           WHEN bipm.payment_method = 'Pay Later' then bci.invoice_id  
          end
          as TRANS_REFERENCE,
              
           case
               WHEN bipm.payment_method = 'Credit Card' then CONCAT(bccp.first_4_digits,'********', bccp.last_4_digits) 
               WHEN bipm.payment_method = 'Sadad' then bsp.s_olp_id 
               WHEN bipm.payment_method = 'Cash' then '' 
               WHEN bipm.payment_method = 'Corporate Credit' then '' 
               WHEN bipm.payment_method = 'Pay Later' then CONCAT(bci.first_4_digits,'********', bci.last_4_digits) 
           end
           as ACCOUNT_CARD_NO,
           
          case
           WHEN bipm.payment_method = 'Credit Card' then DATE_FORMAT(bccp.trans_date, '%d-%m-%Y %T')
           WHEN bipm.payment_method = 'Sadad' then DATE_FORMAT(bsp.s_trans_date, '%d-%m-%Y %T')
           WHEN bipm.payment_method = 'Cash' then DATE_FORMAT(b.created_at, '%d-%m-%Y %T')
           WHEN bipm.payment_method = 'Corporate Credit' then DATE_FORMAT(b.created_at, '%d-%m-%Y %T')
           WHEN bipm.payment_method = 'Pay Later' then DATE_FORMAT(bci.transaction_date, '%d-%m-%Y %T')
           end
           as TRANS_DATE,
   
         bp.total_sum as TRANS_AMOUNT
         ";
        // , bc.cancel_time as CANCELLED_DATE_TIME,
        // bc.cancel_charges as CANCELLED_CHARGES
        $query1 = "SELECT " . $selectCols1 . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_payment as bp on b.id=bp.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        ";

        if ($booking_ids != '') {
            $query1 .= " WHERE b.reservation_code in (" . $booking_ids . ")";
        }


        $selectCols2 = "b.id, b.reservation_code as BOOKING_ID,
        
        case
           WHEN b.booking_status = 'Cancelled' THEN 'I'
           WHEN b.booking_status = 'Expired' THEN 'E'
        end
        as BOOKING_STATUS,
        'R' as TRANS_TYPE,
 
  
        case
          WHEN bipm.payment_method = 'Credit Card' then
           case
            WHEN bccp.payment_company is null AND bccp.card_brand = 'Visa' THEN 'PT_VISA'
           WHEN bccp.payment_company is null AND bccp.card_brand = 'Master Card' THEN 'PT_MC'
           WHEN bccp.payment_company is null AND bccp.card_brand = 'MasterCard' THEN 'PT_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Visa' THEN 'ST_Visa'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Master Card' THEN 'ST_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'MasterCard' THEN 'ST_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Master' THEN 'ST_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Mada' THEN 'ST_MADA'  
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-MADA' THEN 'ST_MADA'  
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-MasterCard' THEN 'ST_MC'  
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ApplePay-Visa' THEN 'ST_Visa'  
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ST_MC' THEN 'ST_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'ST_Visa' THEN 'ST_Visa' 
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'Electron' THEN 'ST_Visa' 
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-MADA' THEN 'ST_MADA'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-MasterCard' THEN 'ST_MC'
           WHEN bccp.payment_company = 'sts' AND bccp.card_brand = 'GooglePay-Visa' THEN 'ST_Visa'
           
           WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'VISA' OR bccp.card_brand = 'Visa') THEN 'HP_Visa'
                WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'Master Card' OR bccp.card_brand = 'MasterCard' OR bccp.card_brand = 'Master' OR bccp.card_brand = 'MASTER') THEN 'HP_MC'
                WHEN bccp.payment_company = 'hyper_pay' AND (bccp.card_brand = 'MADA' OR bccp.card_brand = 'Mada') THEN 'HP_Mada'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'STC_PAY' THEN 'HP_STCP'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'AMEX' THEN 'HP_Amex'
                WHEN bccp.payment_company = 'hyper_pay' AND bccp.card_brand = 'APPLE_PAY' THEN 'HP_Apple'       
           end
          WHEN bipm.payment_method = 'Cash' THEN 'CASH'
          WHEN bipm.payment_method = 'Corporate Credit' THEN 'CREDIT'
          WHEN bipm.payment_method = 'Sadad' THEN 'PT_SD'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'Master Card' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'Master' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'Mada' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'ApplePay-MADA' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'ApplePay-MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'ApplePay-Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'Electron' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'ST_MC' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'ST_Visa' THEN 'ST_Visa'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'GooglePay-MADA' THEN 'ST_MADA'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'GooglePay-MasterCard' THEN 'ST_MC'
          WHEN bipm.payment_method = 'Pay Later' AND bci.card_brand = 'GooglePay-Visa' THEN 'ST_Visa'
          end
                as TRANS_METHOD,
          
          case
           WHEN bipm.payment_method = 'Credit Card' then bccp.transaction_id
           WHEN bipm.payment_method = 'Sadad' then bsp.s_transaction_id 
           WHEN bipm.payment_method = 'Cash' then '' 
           WHEN bipm.payment_method = 'Corporate Credit' then '' 
           WHEN bipm.payment_method = 'Pay Later' then bci.invoice_id 
          end
          as TRANS_REFERENCE,
              
                 case
           WHEN bipm.payment_method = 'Credit Card' then CONCAT(bccp.first_4_digits,'********', bccp.last_4_digits) 
           WHEN bipm.payment_method = 'Sadad' then bsp.s_olp_id 
           WHEN bipm.payment_method = 'Cash' then '' 
           WHEN bipm.payment_method = 'Corporate Credit' then '' 
           WHEN bipm.payment_method = 'Pay Later' then CONCAT(bci.first_4_digits,'********', bci.last_4_digits)  
           end
           as ACCOUNT_CARD_NO,
        
           DATE_FORMAT(bc.cancel_time, '%d-%m-%Y %T') as TRANS_DATE,
        case
           WHEN bc.cancel_charges = '0.00' THEN bp.total_sum
           WHEN bc.cancel_charges != '0.00' THEN (bp.total_sum - bc.cancel_charges)
           end
        as TRANS_AMOUNT
         ";
        // , bc.cancel_time as CANCELLED_DATE_TIME,
        // bc.cancel_charges as CANCELLED_CHARGES,
        // if (bc.cancel_charges = '0.00' , '', bc.cancel_charges) as TRANS_AMOUNT
        $query2 = "SELECT " . $selectCols2 . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id 
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_payment as bp on b.id=bp.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        where (b.booking_status = 'Cancelled' OR b.booking_status = 'Expired') and b.sync = 'N'
        ";

        if ($booking_ids != '') {
            $query2 .= " AND b.reservation_code in (" . $booking_ids . ")"; // this line was commented somehow, so uncommented this one
        }

        $query = $query1 . " UNION " . $query2;

        $query = "select BOOKING_ID, BOOKING_STATUS,TRANS_TYPE,TRANS_METHOD,TRANS_REFERENCE,ACCOUNT_CARD_NO,TRANS_DATE,TRANS_AMOUNT
                  from (" . $query . ") t1 group by t1.id, t1.BOOKING_STATUS";


        //echo $query;
        //exit();

        $bookings = DB::select($query);

        return $bookings;

    }

    public function getAll($user_id)
    {
        $bookings = DB::table('booking')
            ->join('customer', 'booking.user_id', '=', 'customer.id')
            ->join('branch as bf', 'booking.from_location', '=', 'bf.id')
            ->join('branch as bt', 'booking.to_location', '=', 'bt.id')
            ->join('car_model', 'booking.car_model', '=', 'car_model.id')
            ->select('customer.*', 'car_model.eng_title as car_eng_title', 'car_model.arb_title as car_arb_title', 'bf.eng_title as branch_eng_from', 'bf.arb_title as branch_arb_from', 'bt.eng_title as branch_eng_to', 'bt.arb_title as branch_arb_to', 'booking.*')
            ->where('booking_status', '!=', 'Completed')
            ->where('booking_status', '!=', 'Cancelled')
            ->where('booking.user_id', $user_id)
            ->get();
        //$users = DB::table('users')->select('name', 'number')->get();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        /*print_r($bookings);
        exit();*/
        return $bookings;
    }


    public function getUserIdFromCorporateBookings($booking_id)
    {
        $data = DB::table('booking_corporate_customer')->where('booking_id', $booking_id)->first();
        return $data->uid;
    }

    public function getBookingsForCorporateUserBk($user_id, $sort, $booking_id = "")
    {
        $query = "SELECT 
                    b.*,
                    b.type as user_type,
                    car_model.eng_title as car_eng_title,
                    car_model.arb_title as car_arb_title,
                    car_model.year as car_model_year,
                    bf.eng_title as branch_eng_from,
                    bf.arb_title as branch_arb_from,
                    bt.eng_title as branch_eng_to,
                    bt.arb_title as branch_arb_to,
                    cc.company_name_en as name,
                    bipm.payment_method as payment_method
                FROM booking b
                JOIN branch bf ON b.from_location = bf.id
                JOIN branch bt ON b.to_location = bt.id
                JOIN car_model ON b.car_model_id = car_model.id
                LEFT JOIN booking_corporate_customer bcc ON b.id = bcc.booking_id
                LEFT JOIN corporate_customer cc ON FIND_IN_SET(bcc.uid, cc.uid) > 0
                LEFT JOIN booking_individual_payment_method bipm ON b.id = bipm.booking_id
                WHERE bcc.uid = $user_id AND b.id != $booking_id
                ORDER BY b.$sort";
        $bookings = DB::select($query);
        return $bookings;
    }


    public function getBookingsForCorporateUser($user_id, $sort = 'id', $booking_id = "")
    {
        $query = "SELECT 
                    b.*,
                    b.type as user_type,
                    bp.*,
                    car_model.*,
                    car_model.eng_title as car_eng_title,
                    car_model.arb_title as car_arb_title,
                    car_model.year as car_model_year,
                    bf.eng_title as branch_eng_from,
                    bf.arb_title as branch_arb_from,
                    bt.eng_title as branch_eng_to,
                    bt.arb_title as branch_arb_to,
                    bipm.payment_method as payment_method,
                    cc.eng_title as car_category_eng_title,
                    cc.arb_title as car_category_arb_title,
                    ct.eng_title as car_type_eng_title,
                    ct.arb_title as car_type_arb_title,
                    city_from.eng_title as city_eng_title_from,
                    city_to.eng_title as city_eng_title_to,
                    city_from.arb_title as city_arb_title_from,
                    city_to.arb_title as city_arb_title_to,
                    bcp.*,
                    bsp.*,
                    bci.*
                FROM booking b
                JOIN branch bf ON b.from_location = bf.id
                JOIN branch bt ON b.to_location = bt.id
                JOIN city city_from ON bf.city_id = city_from.id
                JOIN city city_to ON bt.city_id = city_to.id
                JOIN car_model ON b.car_model_id = car_model.id
                JOIN car_type ct ON car_model.car_type_id = ct.id
                JOIN car_group cg ON ct.car_group_id = cg.id
                JOIN car_category cc ON cg.car_category_id = cc.id
                LEFT JOIN booking_corporate_customer bcc ON b.id = bcc.booking_id
                LEFT JOIN booking_payment bp ON b.id = bp.booking_id
                LEFT JOIN booking_cc_payment bcp ON b.id = bcp.booking_id
                LEFT JOIN booking_corporate_invoice bci ON b.id = bci.booking_id
                LEFT JOIN booking_sadad_payment bsp ON b.id = bsp.s_booking_id
                LEFT JOIN booking_individual_payment_method bipm ON b.id = bipm.booking_id
                LEFT JOIN corporate_customer corp_customer ON FIND_IN_SET(bcc.uid, corp_customer.uid) > 0
                WHERE corp_customer.uid = $user_id AND b.id != $booking_id
                ORDER BY b.$sort";
        $bookings = DB::select($query);
        return $bookings;

    }


    public function getUserIdFromIndividualBookings($booking_id)
    {
        $data = DB::table('booking_individual_user')->where('booking_id', $booking_id)->first();
        return $data->uid;
    }

    public function getBookingsForIndividualUser($user_id, $sort = 'id', $booking_id = "")
    {
        $bookings = DB::table('booking as b')
            ->join('branch as bf', 'b.from_location', '=', 'bf.id')
            ->join('branch as bt', 'b.to_location', '=', 'bt.id')
            ->join('city as city_from', 'bf.city_id', '=', 'city_from.id')
            ->join('city as city_to', 'bt.city_id', '=', 'city_to.id')
            ->join('car_model', 'b.car_model_id', '=', 'car_model.id')
            ->join('car_type as ct', 'car_model.car_type_id', '=', 'ct.id')
            ->join('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->join('car_category as cc', 'cg.car_category_id', '=', 'cc.id')
            ->leftjoin('booking_individual_user as bic', 'b.id', '=', 'bic.booking_id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('booking_cc_payment as bcp', 'b.id', '=', 'bcp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('individual_customer as ic', 'bic.uid', '=', 'ic.uid')
            ->select('b.*', 'b.type as user_type', 'bp.*', 'car_model.*', 'car_model.eng_title as car_eng_title', 'car_model.arb_title as car_arb_title', 'car_model.year as car_model_year', 'bf.eng_title as branch_eng_from', 'bf.arb_title as branch_arb_from', 'bt.eng_title as branch_eng_to', 'bt.arb_title as branch_arb_to', 'ic.first_name as name', 'bipm.payment_method as payment_method', 'cc.eng_title as car_category_eng_title', 'cc.arb_title as car_category_arb_title', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'city_from.eng_title as city_eng_title_from', 'city_to.eng_title as city_eng_title_to', 'city_from.arb_title as city_arb_title_from', 'city_to.arb_title as city_arb_title_to', 'bcp.*', 'bsp.*', 'bci.*')
            ->where('bic.uid', $user_id)
            ->where('b.id', '!=', $booking_id)
            ->orderBy('b.' . $sort)
            ->get();
        return $bookings;
    }

    public function getUserIdFromGuestBookings($booking_id)
    {
        $data = DB::table('booking_individual_guest')->where('booking_id', $booking_id)->first();
        return $data->individual_customer_id;
    }

    public function getBookingsForGuestUser($user_id, $sort, $booking_id = "")
    {
        $bookings = DB::table('booking as b')
            ->join('branch as bf', 'b.from_location', '=', 'bf.id')
            ->join('branch as bt', 'b.to_location', '=', 'bt.id')
            ->join('car_model', 'b.car_model_id', '=', 'car_model.id')
            ->leftjoin('booking_individual_guest as big', 'b.id', '=', 'big.booking_id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('booking_cc_payment as bcp', 'b.id', '=', 'bcp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('individual_customer as ic', 'big.individual_customer_id', '=', 'ic.id')
            ->select('b.*', 'b.type as user_type', 'bp.*', 'car_model.eng_title as car_eng_title', 'car_model.arb_title as car_arb_title', 'car_model.year as car_model_year', 'bf.eng_title as branch_eng_from', 'bf.arb_title as branch_arb_from', 'bt.eng_title as branch_eng_to', 'bt.arb_title as branch_arb_to', 'ic.first_name as name', 'bipm.payment_method as payment_method', 'bcp.*', 'bsp.*', 'bci.*')
            ->where('big.individual_customer_id', $user_id)
            ->where('b.id', '!=', $booking_id)
            ->orderBy($sort)
            ->get();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }


    public function getSingleBookingInfo($booking_id)
    {
        $booking = DB::table('booking as b')
            ->join('branch as bf', 'b.from_location', '=', 'bf.id')
            ->join('branch as bt', 'b.to_location', '=', 'bt.id')
            ->join('city as cf', 'bf.city_id', '=', 'cf.id')
            ->join('city as ct', 'bt.city_id', '=', 'ct.id')
            ->join('region as rf', 'cf.region_id', '=', 'rf.id')
            ->join('region as rt', 'ct.region_id', '=', 'rt.id')
            ->join('car_model', 'b.car_model_id', '=', 'car_model.id')
            ->leftjoin('booking_corporate_customer as bcc', 'bcc.booking_id', '=', 'b.id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->select('car_model.eng_title as car_eng_title', 'car_model.arb_title as car_arb_title', 'car_model.year as car_model_year', 'car_model.oracle_reference_number as oracle_ref_no', 'bf.eng_title as branch_eng_from', 'bf.arb_title as branch_arb_from', 'bt.eng_title as branch_eng_to', 'bt.arb_title as branch_arb_to', 'b.*', 'cf.eng_title as eng_city_from', 'ct.eng_title as eng_city_to', 'rf.eng_title as eng_region_from', 'rt.eng_title as eng_region_to', 'bcc.agent_emp_number as employee_number', 'bp.qitaf_amount', 'bp.qitaf_request', 'bp.niqaty_amount', 'bp.niqaty_request', 'bp.car_rate_is_with_additional_utilization_rate', 'bp.subscribe_for_months', 'bp.mokafaa_amount', 'bp.mokafaa_request', 'bp.anb_amount', 'bp.anb_request')
            ->where('b.id', $booking_id)
            ->get();
        return $booking;
    }

    public function getBookingDetail($booking_id)
    {
        $data = DB::table('booking')->where('id', $booking_id)->first();
        return $data;
    }

    public function getPaymentInfoForBooking($booking_id)
    {
        /*$data['booking_info'] = DB::table('booking')->where('id', $booking_id)->first();
        $data['payment_info'] = DB::table('booking_individual_payment')->where('booking_id', $booking_id)->first();
        $data['payment_confirmation'] = DB::table('booking_cc_payment')->where('booking_id', $booking_id)->first();*/
        //return $data;

        $bookings = DB::table('booking as b')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('setting_loyalty_cards as slc', 'bp.loyalty_card_id', '=', 'slc.id')
            ->leftjoin('promotion_offer as po', 'bp.promotion_offer_id', '=', 'po.id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('booking_cc_payment as bcp', 'b.id', '=', 'bcp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_cancel as bc', 'b.id', '=', 'bc.booking_id')
            ->select('b.*', 'bp.*', 'bcp.*', 'bsp.*', 'po.*', 'bipm.*', 'bci.*','bci.transaction_id as bci_transaction_id','bci.card_brand as bci_card_brand','bcp.transaction_id as bcp_transaction_id','bcp.card_brand as bcp_card_brand', 'bp.promotion_offer_code_used as promotion_code_used', 'slc.loyalty_type as loyalty_card_used', 'bc.*')
            ->where('b.id', $booking_id)
            ->get();
        return $bookings;
    }

    public function saveData($data)
    {
        $savedId = DB::table('booking')->insertGetId($data);
        if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }
    }

    public function getSingle($get_by)
    {
        $record = DB::table('booking')
            ->where($get_by)
            ->select('*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function updateData($data, $update_by)
    {
        $updated = DB::table('booking')
            ->where($update_by)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }


    /*public function updateCustomerData($data, $update_by)
    {
        $updated = DB::table('individual_customer')
            ->where($update_by)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }*/


    /*public function saveCustomerData($data)
    {
        $savedId = DB::table('individual_customer')->insertGetId($data);
        if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }
    }*/

    public function saveCustomerData($data)
    {

        $insVupdQ = "INSERT INTO `individual_customer` (`first_name`, `last_name`, `mobile_no`, `email`, `id_type`, `id_no`, `id_version`, `nationality`, `dob`, `id_expiry_date`, `id_date_type`, `id_country`, `license_id_type`, `license_no`, `license_expiry_date`, `license_country`, `loyalty_card_type`, `loyalty_points`, `job_title`, `sponsor`, `street_address`, `district_address`, `black_listed`, `created_at`, `updated_at`) VALUES (:first_name, :last_name, :mobile_no, :email, :id_type, :id_no, :id_version, :nationality, :dob, :id_expiry_date, :id_date_type, :id_country, :license_id_type, :license_no, :license_expiry_date, :license_country, :loyalty_card_type, :loyalty_points, :job_title, :sponsor, :street_address, :district_address, :black_listed, :created_at, :updated_at) 
 
 ON DUPLICATE KEY UPDATE 
 
 `first_name` = :first_name1, `last_name` = :last_name1, `mobile_no` = :mobile_no1, `email` = :email1, `id_type` = :id_type1, `id_no` = :id_no1, `id_version` = :id_version1, `nationality` = :nationality1, `dob` = :dob1, `id_expiry_date` = :id_expiry_date1, `id_date_type` = :id_date_type1, `id_country` = :id_country1, `license_id_type` = :license_id_type1, `license_no` = :license_no1, `license_expiry_date` = :license_expiry_date1, `license_country` = :license_country1, `loyalty_card_type` = :loyalty_card_type1, `loyalty_points` = :loyalty_points1, `job_title` = :job_title1, `sponsor` = :sponsor1, `street_address` = :street_address1, `district_address` = :district_address1, `black_listed` = :black_listed1, `created_at` = :created_at1, `updated_at` = :updated_at1 
 ";

        $data1 = array();
        foreach ($data as $key => $val) {
            $data1[$key . "1"] = $val;
        }

        $dataMerged = array_merge($data, $data1);

        //$results = DB::select( DB::raw($insVupdQ), $dataMerged);
        DB::statement($insVupdQ, $dataMerged);

        /*if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }*/
    }

    public function saveCorporateInvoices($data)
    {

        $insVupdQ = "INSERT INTO `individual_customer` (`first_name`, `last_name`, `mobile_no`, `email`, `id_type`, `id_no`, `id_version`, `nationality`, `dob`, `id_expiry_date`, `id_date_type`, `id_country`, `license_id_type`, `license_no`, `license_expiry_date`, `license_country`, `loyalty_card_type`, `loyalty_points`, `job_title`, `sponsor`, `street_address`, `district_address`, `black_listed`, `created_at`, `updated_at`) VALUES (:first_name, :last_name, :mobile_no, :email, :id_type, :id_no, :id_version, :nationality, :dob, :id_expiry_date, :id_date_type, :id_country, :license_id_type, :license_no, :license_expiry_date, :license_country, :loyalty_card_type, :loyalty_points, :job_title, :sponsor, :street_address, :district_address, :black_listed, :created_at, :updated_at) 
 
 ON DUPLICATE KEY UPDATE 
 
 `first_name` = :first_name1, `last_name` = :last_name1, `mobile_no` = :mobile_no1, `email` = :email1, `id_type` = :id_type1, `id_no` = :id_no1, `id_version` = :id_version1, `nationality` = :nationality1, `dob` = :dob1, `id_expiry_date` = :id_expiry_date1, `id_date_type` = :id_date_type1, `id_country` = :id_country1, `license_id_type` = :license_id_type1, `license_no` = :license_no1, `license_expiry_date` = :license_expiry_date1, `license_country` = :license_country1, `loyalty_card_type` = :loyalty_card_type1, `loyalty_points` = :loyalty_points1, `job_title` = :job_title1, `sponsor` = :sponsor1, `street_address` = :street_address1, `district_address` = :district_address1, `black_listed` = :black_listed1, `created_at` = :created_at1, `updated_at` = :updated_at1 
 ";

        $data1 = array();
        foreach ($data as $key => $val) {
            $data1[$key . "1"] = $val;
        }

        $dataMerged = array_merge($data, $data1);

        //$results = DB::select( DB::raw($insVupdQ), $dataMerged);
        DB::statement($insVupdQ, $dataMerged);

        /*if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }*/
    }

    public function getSingleCustomerData($get_by)
    {
        $record = DB::table('individual_customer')
            ->where($get_by)
            ->select('*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function updateCustomerData($data, $update_by)
    {
        $updated = DB::table('individual_customer')
            ->where($update_by)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    // saveCustomerData
    // updateCustomerData
    // getSingleCustomerData
    public function getAllPendingBookings($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $filter_date = "", $pending_search_keyword_customer = "", $pending_search_keyword_booking = "",$search_type = "")
    {
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*, cm.eng_title as car_eng_title, cm.year as car_model_year, ct.eng_title as car_type_eng_title, bf.eng_title as branch_eng_from, bt.eng_title as branch_eng_to, cc.company_name_en as eng_company_name ";
            if ($frontEndUserId == 0) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b 

left join booking_cc_payment bcp on b.id=bcp.booking_id
left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
left join car_model cm on b.car_model_id=cm.id
                  left join car_type ct on cm.car_type_id = ct.id
                  left join branch bf on b.from_location=bf.id
                  left join branch bt on b.to_location=bt.id
                  
left join booking_corporate_customer bcc on b.id=bcc.booking_id
left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
";

        if ($pending_search_keyword_customer != "") {
            $query .= " 
        left join booking_individual_guest big on b.id=big.booking_id
        
        left join booking_individual_user biu on b.id=biu.booking_id
        
        left join individual_customer ic on (biu.uid=ic.uid or big.individual_customer_id=ic.id)
        
       
        left join corporate_driver cd on bcc.driver_id=cd.id
        ";
        }
        /* Ahsan
         * These 2 lines moved from if condition to above below the select to get company name from corporate_customer table
         *
         * left join booking_corporate_customer bcc on b.id=bcc.booking_id
         * left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
         *
         * */

        $query .= " where (bcp.status='pending' OR bsp.s_status='pending') ";

        if ($filter_date != "") {
            $query .= " and DATE(b.created_at) >= '" . $filter_date . "'";
        } elseif ($pending_search_keyword_customer != "") {
            //$query .= " and (ic.id_no = '" . $pending_search_keyword_customer . "' OR ic2.id_no = '" . $pending_search_keyword_customer . "' OR ic.mobile_no = '" . $pending_search_keyword_customer . "' OR ic2.mobile_no = '" . $pending_search_keyword_customer . "' OR bcp.transaction_id = '" . $pending_search_keyword_customer . "' OR bsp.s_transaction_id = '" . $pending_search_keyword_customer . "' OR cc.company_code = '" . $pending_search_keyword_customer . "' OR cd.id_no = '" . $pending_search_keyword_customer . "' OR cd.mobile_no = '" . $pending_search_keyword_customer . "') ";

            if($search_type == "ind_id_no"){
                $row = $this->getCustomerIDBy($pending_search_keyword_customer);
                /*echo '<pre>';
                print_r($row);
                exit();*/
                if($row && $row[0]->uid == 0){
                    $query .= " and big.individual_customer_id = '".$row[0]->id."' ";
                }else{
                    $query .= " and biu.uid = '".(isset($row[0]->uid)?$row[0]->uid:$search_keyword_customer)."' ";
                }

            }elseif ($search_type == "corp_id_no"){
                $query .= " and cd.id_no = '" . $pending_search_keyword_customer . "' ";
            }elseif ($search_type == "mobile_no"){
                $query .= " and (ic.mobile_no = '" . $pending_search_keyword_customer . "' OR cd.mobile_no = '" . $pending_search_keyword_customer . "' ) ";
            }elseif ($search_type == "transaction_id"){
                $query .= " and (bcp.transaction_id = '" . $pending_search_keyword_customer . "' OR bsp.s_transaction_id = '" . $pending_search_keyword_customer . "' ) ";
            }elseif ($search_type == "company_code"){
                $query .= " and (cc.company_code = '" . $pending_search_keyword_customer . "' ) ";
            }else{
                $query .= " and (ic.id_no = '" . $pending_search_keyword_customer . "' OR ic.mobile_no = '" . $pending_search_keyword_customer . "' 
                OR bcp.transaction_id = '" . $pending_search_keyword_customer . "' OR bsp.s_transaction_id = '" . $pending_search_keyword_customer . "' 
                OR cc.company_code = '" . $pending_search_keyword_customer . "' OR cd.id_no = '" . $pending_search_keyword_customer . "' 
                OR cd.mobile_no = '" . $pending_search_keyword_customer . "' ) ";
            }
        } elseif ($pending_search_keyword_booking != "") {
            $query .= " and (b.reservation_code = '" . $pending_search_keyword_booking . "' OR b.type = '" . $pending_search_keyword_booking . "') ";
        }

        $query .= $sort_by . " " . $limit;

        $bookings = DB::select($query);

        return $bookings;
    }

    public function getSTSInvoicePendingBookings($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $filter_date = "", $pending_search_keyword_customer = "", $pending_search_keyword_booking = "")
    {
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*, cm.eng_title as car_eng_title, cm.year as car_model_year, ct.eng_title as car_type_eng_title, bf.eng_title as branch_eng_from, bt.eng_title as branch_eng_to, cc.company_name_en as eng_company_name ";
            if ($frontEndUserId == 0) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b 

                  join booking_individual_payment_method bipm on b.id=bipm.booking_id
                  join booking_corporate_invoice bci on b.id=bci.booking_id
                  left join car_model cm on b.car_model_id=cm.id
                  left join car_type ct on cm.car_type_id = ct.id
                  left join branch bf on b.from_location=bf.id
                  left join branch bt on b.to_location=bt.id
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
";

        if ($pending_search_keyword_customer != "") {
            $query .= " 
        left join booking_individual_guest big on b.id=big.booking_id
        left join individual_customer ic2 on big.individual_customer_id=ic2.id
        
        left join booking_individual_user biu on b.id=biu.booking_id
        left join individual_customer ic on biu.uid=ic.uid
        
        
        left join corporate_driver cd on bcc.driver_id=cd.id
        ";
        }
        /* Ahsan
         * These 2 lines moved from if condition to above below the select to get company name from corporate_customer table
         *
         * left join booking_corporate_customer bcc on b.id=bcc.booking_id
         * left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
         *
         * */


        $query .= " where bipm.payment_method='Pay Later' and bci.payment_status='pending' ";

        if ($filter_date != "") {
            $query .= " and DATE(b.created_at) >= '" . $filter_date . "'";
        } elseif ($pending_search_keyword_customer != "") {
            $query .= " and (ic.id_no = '" . $pending_search_keyword_customer . "' OR ic2.id_no = '" . $pending_search_keyword_customer . "' OR ic.mobile_no = '" . $pending_search_keyword_customer . "' OR ic2.mobile_no = '" . $pending_search_keyword_customer . "' OR cc.company_code = '" . $pending_search_keyword_customer . "' OR cd.id_no = '" . $pending_search_keyword_customer . "' OR cd.mobile_no = '" . $pending_search_keyword_customer . "') ";
        } elseif ($pending_search_keyword_booking != "") {
            $query .= " and (b.reservation_code = '" . $pending_search_keyword_booking . "' OR b.type = '" . $pending_search_keyword_booking . "') ";
        }

        $query .= $sort_by . " " . $limit;

        $bookings = DB::select($query);

        return $bookings;
    }


    public function exportPendingBookings($filter_date = "", $booking_ids = "")
    {

        $selectCols = "b.reservation_code as BOOKING_ID,
            'W' as BOOKING_SOURCE,
            case
            when b.type = 'guest' then ic2.id_no
            when b.type = 'individual_customer' then ic.id_no
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' AND bcc.customer_id_no_for_loyalty is not null AND bcc.customer_id_no_for_loyalty !='' then bcc.customer_id_no_for_loyalty
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' AND (bcc.customer_id_no_for_loyalty is null || bcc.customer_id_no_for_loyalty ='') then cd.id_no
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') AND (bcc.customer_id_no_for_loyalty is null || bcc.customer_id_no_for_loyalty ='') then cc.company_code
            end
            as CUSTOMER_ID,
            case
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' then 'P'
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') then 'I'
            when b.type != 'corporate_customer' then 'P'
            end
            as CUSTOMER_TYPE,
            case
            when b.type = 'guest' then ic2.id_no
            when b.type = 'individual_customer' then ic.id_no
            when b.type = 'corporate_customer' AND bcc.agent_emp_number is not null AND bcc.agent_emp_number !='' then 'Call Center'
            when b.type = 'corporate_customer' AND (bcc.agent_emp_number is null || bcc.agent_emp_number ='') then cd.id_no
            end
            as DRIVER_ID,
            cm.oracle_reference_number as CAR_TYPE,
            cm.year as CAR_MODEL,
            bf.oracle_reference_number as OPENING_BRANCH,
            DATE_FORMAT(b.from_date, '%d-%m-%Y %T') as APPLIES_FROM,
            bt.oracle_reference_number as CLOSING_BRANCH,
            DATE_FORMAT(b.to_date, '%d-%m-%Y %T') as APPLIES_TO,
            srt.oracle_reference_number as RENTING_TYPE_ID,			
            bp.rent_price as RENT_PRICE,
            if (bp.cdw_price = '0.00' , '', bp.cdw_price) as CDW_PRICE,
            if (bp.gps_price = '0.00' , '', bp.gps_price) as GPS_PRICE,
            if (bp.extra_driver_price = '0.00' , '', bp.extra_driver_price) as EXTRA_DRIVER_PRICE,
            if (bp.baby_seat_price = '0.00' , '', bp.baby_seat_price) as BABY_SEAT_PRICE,
            if (bp.discount_price > 0 AND bp.is_promo_discount_on_total = 0 , bp.discount_price, '') as DISCOUNT_PRICE,
            if (bp.promotion_offer_code_used is null OR bp.promotion_offer_code_used = '', bp.promotion_offer_id, bp.promotion_offer_code_used) as PROMOTION_OFFER_ID,
            
            case
            when b.type = 'guest' then replace(ic2.mobile_no, '+', '')
            when b.type = 'individual_customer' then replace(ic.mobile_no, '+', '')
            when b.type = 'corporate_customer' then replace(cd.mobile_no, '+', '')
            end
            as MOBILE,
            
            case
            when b.is_delivery_mode = 'no' then 'NO'
            when b.is_delivery_mode = 'yes' then 'YES'
            when b.is_delivery_mode = 'hourly' then 'NO'
            when b.is_delivery_mode = 'subscription' then 'NO'
            end
            as IS_DELIVERY_TYPE,
            
            SUBSTRING_INDEX(b.pickup_delivery_lat_long , ',', 1 ) AS PICKUP_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.pickup_delivery_lat_long , ',', 2 ),',',-1) AS PICKUP_LONGITUDE,
            
            SUBSTRING_INDEX(b.dropoff_delivery_lat_long , ',', 1 ) AS DROPOFF_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.dropoff_delivery_lat_long , ',', 2 ),',',-1) AS DROPOFF_LONGITUDE,
             
            case
            when b.is_delivery_mode = 'no' then 'N'
            when b.is_delivery_mode = 'yes' then 'N'
            when b.is_delivery_mode = 'hourly' then 'Y'
            when b.is_delivery_mode = 'subscription' then 'N'
            end
            as IS_HOURLY_TYPE,
            
            bp.qitaf_request as QITAF_REDEEM_ID,
            
            bp.loyalty_program_for_oracle as LOYALTY_PROGRAM_ID,
            
            bp.qitaf_amount as QITAF_AMOUNT,
            
            if (bp.cdw_plus_price > 0 , bp.cdw_plus_price, '') as CDW_PLUS,
            
            bp.niqaty_request as NIQATY_REDEEM_ID,
            
            bp.niqaty_amount as NIQATY_AMOUNT,
            
            if (bp.discount_price > 0 AND bp.is_promo_discount_on_total = 1 , bp.discount_price, '') as DISCOUNT_PRICE_ON_TOTAL,
            
            case
            when bp.car_rate_is_with_additional_utilization_rate = 0 then 'N'
            when bp.car_rate_is_with_additional_utilization_rate = 1 then 'Y'
            end
            as IS_CAR_RATE_WITH_ADDITIONAL_UTILIZATION_RATE,
            
            case
            when b.is_delivery_mode = 'subscription' then bp.subscribe_for_months
            when b.is_delivery_mode = 'yes' AND b.subscription_with_delivery_flow = 'on' then bp.subscribe_for_months
            when b.is_delivery_mode != 'subscription' then 0
            end
            as SUBSCRIBE_FOR_MONTHS,
            
            bp.three_month_subscription_price_for_car as THREE_MONTH_SUBSCRIPTION_PRICE,
            bp.six_month_subscription_price_for_car as SIX_MONTH_SUBSCRIPTION_PRICE,
            bp.nine_month_subscription_price_for_car as NINE_MONTH_SUBSCRIPTION_PRICE,
            bp.twelve_month_subscription_price_for_car as TWELVE_MONTH_SUBSCRIPTION_PRICE,
            
            case
            when bp.is_free_cdw_promo_applied = 0 then 'N'
            when bp.is_free_cdw_promo_applied = 1 then 'Y'
            end
            as IS_FREE_CDW_PROMO_APPLIED,
            
            case
            when bp.is_free_cdw_plus_promo_applied = 0 then 'N'
            when bp.is_free_cdw_plus_promo_applied = 1 then 'Y'
            end
            as IS_FREE_CDW_PLUS_PROMO_APPLIED,
            
            case
            when bp.is_free_baby_seat_promo_applied = 0 then 'N'
            when bp.is_free_baby_seat_promo_applied = 1 then 'Y'
            end
            as IS_FREE_BABY_SEAT_PROMO_APPLIED,
            
            case
            when bp.is_free_driver_promo_applied = 0 then 'N'
            when bp.is_free_driver_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DRIVER_PROMO_APPLIED,
            
            case
            when bp.is_free_open_km_promo_applied = 0 then 'N'
            when bp.is_free_open_km_promo_applied = 1 then 'Y'
            end
            as IS_FREE_OPEN_KM_PROMO_APPLIED,
            
            case
            when bp.is_free_delivery_promo_applied = 0 then 'N'
            when bp.is_free_delivery_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DELIVERY_PROMO_APPLIED,
            
            case
            when bp.is_free_dropoff_promo_applied = 0 then 'N'
            when bp.is_free_dropoff_promo_applied = 1 then 'Y'
            end
            as IS_FREE_DROPOFF_PROMO_APPLIED,
            
            bp.mokafaa_request as MOKAFAA_REDEEM_ID,
            
            bp.mokafaa_amount as MOKAFAA_AMOUNT,
            
            bp.anb_request as ANB_REDEEM_ID,
            
            bp.anb_amount as ANB_AMOUNT,
            
            b.is_limousine as IS_LIMOUSINE,
            
            b.is_round_trip as IS_ROUND_TRIP,
            
            b.flight_no as FLIGHT_NUMBER,
            
            b.waiting_extra_hours as WAITING_EXTRA_HOURS,
            
            b.waiting_extra_hours_charges as WAITING_EXTRA_HOURS_CHARGES,
            
            b.limousine_cost_center as LIMOUSINE_COST_CENTER,
            
            bp.utilization_percentage as UTILIZATION_PERCENTAGE,
            
            bp.utilization_percentage_rate as UTILIZATION_PERCENTAGE_RATE,
            
            bp.utilization_record_time as UTILIZATION_RECORD_TIME
            
             ";


        $query = "SELECT " . $selectCols . " FROM `booking` b";


        $query .= " join car_model cm on b.car_model_id=cm.id ";

        if ($filter_date != '') {
            $query .= " and DATE(b.created_at) >= '" . $filter_date . "'";
        }

        if ($booking_ids != '') {
            $query .= " and b.id in (" . $booking_ids . ")";
        }

        $query .= "left join branch bf on b.from_location=bf.id
        
        left join branch bt on b.to_location=bt.id
        left join booking_cancel book_c on b.id=book_c.booking_id and book_c.sync='N'        
		left join booking_individual_payment_method bipm on b.id=bipm.booking_id ";

        if(isset($_POST['paylater']))
            $query .= " left join booking_corporate_invoice bci on b.id=bci.booking_id ";
        else
            $query .= " left join booking_cc_payment bcp on b.id=bcp.booking_id
                       left join booking_sadad_payment bsp on b.id=bsp.s_booking_id ";

        $query .= " left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'";

        $query .= " left join car_type ct on cm.car_type_id = ct.id
         left join car_group cg on ct.car_group_id = cg.id
         left join car_category car_cat on cg.car_category_id = car_cat.id
         left join booking_payment bp on b.id = bp.booking_id
         left join users u on biu.uid=u.id
         left join individual_customer ic on u.id=ic.uid		 
		 left join setting_renting_type srt on b.renting_type_id=srt.id		 
         left join booking_corporate_customer bcc on b.id=bcc.booking_id and b.type='corporate_customer'
         left join users u2 on bcc.uid=u2.id
         left join corporate_customer cc on FIND_IN_SET(u2.id, cc.uid) > 0
         left join corporate_driver cd on bcc.driver_id=cd.id
         left join booking_individual_guest big on b.id=big.booking_id and b.type='guest'        
         left join individual_customer ic2 on big.individual_customer_id=ic2.id ";

        if(isset($_POST['paylater']))
            $query .= " where (bci.payment_status='pending') ";
        else
            $query .= " where (bcp.status='pending' OR bsp.s_status='pending') ";



        /*echo $query;
        exit();*/

        $bookings = DB::select($query);

        return $bookings;
    }

    public function getLatestBookingForEachUserBk($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $search_keyword = "", $getOrderLimit = true, $getNullCount = false)
    {
        //echo $frontEndUserId;exit();
        // checking if we are getting bookings as current bookings or history bookings
        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "
            (
            (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			)
			and (b.sync != 'N')
			";
        } else {
            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked') 
			or (b.booking_status='Walk in') 
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";
        }
        // if $frontEndUserId is greater than 0 than its coming from frontend else its from backend
        //echo $sort_by;exit();
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*,
            ct.eng_title as car_type_eng_title, 
            ct.arb_title as car_type_arb_title,
            car_cat.eng_title as car_category_eng_title, 
            car_cat.arb_title as car_category_arb_title,
              bp.total_sum as total_sum, 
              bp.no_of_days as no_of_days, 
              cm.year as year, 
              cm.image1 as image1, 
              cm.no_of_passengers as no_of_passengers, 
              cm.min_age as min_age, 
              cm.no_of_bags as no_of_bags, 
              cm.no_of_doors as no_of_doors,
               cm.transmission as transmission, 
               cm.eng_title as car_eng_title,
               cm.arb_title as car_arb_title, 
               bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from, 
               bt.eng_title as branch_eng_to,
               bt.arb_title as branch_arb_to, 
               u.id as logged_in_uid, 
               u2.id as logged_in_corporid, 
               ic2.id as guest_ind_cust_id, 
               case 
                   when b.type='individual_customer' then concat(ic.first_name,' ', ic.last_name)
                   when b.type='corporate_customer' then concat(cd.first_name,' ',cd.last_name)
                   when b.type='guest' then concat(ic2.first_name,' ', ic2.last_name)
                 end 
                 as name,
                 (select br.eng_title from branch br 
                where br.id=b.from_location) as branch_eng_from";
            if ($frontEndUserId == 0 && $getOrderLimit == true) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )

left join branch bf on b.from_location=bf.id

left join branch bt on b.to_location=bt.id
left join booking_cc_payment bcp on b.id=bcp.booking_id
left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
left join booking_individual_payment_method bipm on b.id=bipm.booking_id
left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'";


        $query .= " left join car_type ct on cm.car_type_id = ct.id 
        left join car_group cg on ct.car_group_id = cg.id 
        left join car_category car_cat on cg.car_category_id = car_cat.id 
        left join booking_payment bp on b.id = bp.booking_id left join users u on biu.uid=u.id 
        left join individual_customer ic on u.id=ic.uid
left join booking_corporate_customer bcc on b.id=bcc.booking_id and b.type='corporate_customer' 
left join users u2 on bcc.uid=u2.id 
left join corporate_customer cc on FIND_IN_SET(u2.id, cc.uid) > 0
left join corporate_driver cd on bcc.driver_id=cd.id
left join booking_individual_guest big on b.id=big.booking_id and b.type='guest' 
left join individual_customer ic2 on big.individual_customer_id=ic2.id where (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        if ($frontEndUserId > 0) {
            $query .= " and (biu.uid = " . $frontEndUserId . " OR bcc.uid = " . $frontEndUserId . ") ";
        }
        if ($getNullCount == true) {
            $query .= " and b.sync = 'N' ";
        }

        if ($search_keyword != "") {
            $query .= " and (b.reservation_code = '" . $search_keyword . "' OR b.type = '" . $search_keyword . "' OR ic.id_no = '" . $search_keyword . "' OR ic2.id_no = '" . $search_keyword . "' OR ic.mobile_no = '" . $search_keyword . "' OR ic2.mobile_no = '" . $search_keyword . "' OR bcp.transaction_id = '" . $search_keyword . "' OR bsp.s_transaction_id = '" . $search_keyword . "') ";
        }

        $query .= $sort_by . " " . $limit;
        //.$sort_by." ".$limit." ";

        //echo $query;exit();
        $bookings = DB::select($query);

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }

    public function getAllPendingBookingsBk($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0, $frontEndUserId = 0, $records_for = "current_bookings", $filter_date = "")
    {
        $searchCriteriaForBookings = "";
        //echo $frontEndUserId;exit();
        // checking if we are getting bookings as current bookings or history bookings
        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "
            (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')			
			";
        } else {
            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked') 
			or (b.booking_status='Walk in') 
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			";
        }

        if ($filter_date != "") {
            $searchCriteriaForBookings = " and DATE(b.created_at) >= '" . $filter_date . "'";
        }
        // if $frontEndUserId is greater than 0 than its coming from frontend else its from backend
        //echo $sort_by;exit();
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {

            $selectCols = "b.*,ct.eng_title as car_type_eng_title, ct.arb_title as car_type_arb_title,
             car_cat.eng_title as car_category_eng_title, car_cat.arb_title as car_category_arb_title,
              bp.total_sum as total_sum, bp.no_of_days as no_of_days, cm.year as year, 
              cm.image1 as image1, cm.no_of_passengers as no_of_passengers, cm.min_age as min_age, 
              cm.no_of_bags as no_of_bags, cm.no_of_doors as no_of_doors,
               cm.transmission as transmission, cm.eng_title as car_eng_title,
               cm.arb_title as car_arb_title, bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from, bt.eng_title as branch_eng_to,bt.arb_title as branch_arb_to, 
               u.id as logged_in_uid, u2.id as logged_in_corporid, 
               ic2.id as guest_ind_cust_id, 
               case 
               when b.type='individual_customer' then concat(ic.first_name,' ', ic.last_name)
               when b.type='corporate_customer' then concat(cd.first_name,' ',cd.last_name)
               when b.type='guest' then concat(ic2.first_name,' ', ic2.last_name)
                 end as name, (select br.eng_title from branch br 
                 where br.id=b.from_location) as branch_eng_from";
            if ($frontEndUserId == 0) {
                $sort_by = "ORDER BY " . $sort_by;
                $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
            }
        }

        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id 
        " . $searchCriteriaForBookings . "

left join branch bf on b.from_location=bf.id

left join branch bt on b.to_location=bt.id
left join booking_cc_payment bcp on b.id=bcp.booking_id
left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
left join booking_individual_payment_method bipm on b.id=bipm.booking_id
left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'";


        $query .= " left join car_type ct on cm.car_type_id = ct.id 
        left join car_group cg on ct.car_group_id = cg.id 
        left join car_category car_cat on cg.car_category_id = car_cat.id 
        left join booking_payment bp on b.id = bp.booking_id left join users u on biu.uid=u.id 
        left join individual_customer ic on u.id=ic.uid
        left join booking_corporate_customer bcc on b.id=bcc.booking_id and b.type='corporate_customer' 
        left join users u2 on bcc.uid=u2.id 
        left join corporate_customer cc on FIND_IN_SET(u2.id, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        left join booking_individual_guest big on b.id=big.booking_id and b.type='guest' 
        left join individual_customer ic2 on big.individual_customer_id=ic2.id where (bcp.status='pending' OR bsp.s_status='pending') ";

        if ($frontEndUserId > 0) {
            $query .= " and biu.uid = " . $frontEndUserId . " ";
        }

        $query .= $sort_by . " " . $limit;
        //.$sort_by." ".$limit." ";
        if (!$count_only) {
            //echo $query;exit();
        }

        $bookings = DB::select($query);

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $bookings;
    }

    public function getDataToExport()
    {
        $selectCols = " full_name as FULL_NAME, mobile_no as MOBILE_NO, mreg_no as MREG_NO, device_type as DEVICE_TYPE, created_at as RECEIVED_AT ";
        $records = DB::select("SELECT $selectCols FROM mreg_campaign");
        return $records;
    }

    public function getCancelledBookingsToExport($from_date= '', $to_date = '') {
        $query = "SELECT booking.reservation_code, booking.created_at as booking_received_at, booking.from_date, booking.to_date, booking_cancel.cancel_time as booking_cancellated_at, booking_cancel.cancellation_reason, CONCAT(car_model.eng_title, ' ',car_model.year) as car_model_name FROM booking JOIN booking_cancel ON booking.id = booking_cancel.booking_id LEFT JOIN car_model ON booking.car_model_id = car_model.id WHERE booking.booking_status = 'Cancelled'";
        if ($from_date != '') {
            $query .= " AND DATE(booking_cancel.cancel_time) >= '".date('Y-m-d', strtotime($from_date))."'";
        }
        if ($to_date != '') {
            $query .= " AND DATE(booking_cancel.cancel_time) <= '".date('Y-m-d', strtotime($to_date))."'";
        }
        // echo $query;die;
        $records = DB::select($query);
        return $records;
    }


}