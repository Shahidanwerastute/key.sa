<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Api extends Model
{

    public function getBookings($booking_id = "", $force_resync = false)
    {
        if ($booking_id != "")
        {
            $whereBookingId = " AND b.id = $booking_id";
        }else{
            $whereBookingId = "";
        }

        $criteriaForGettingBookings = "(b.booking_status='Not Picked') or
        (b.booking_status='Picked') or
        (b.booking_status='Walk in') or
        (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')";

        $selectCols = "b.id as DB_ID, b.reservation_code as BOOKING_ID, b.created_at as BOOKING_CREATED_AT, 
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
            if (bp.discount_price > 0 , bp.discount_price, '') as DISCOUNT_PRICE,
            
            bp.promotion_offer_id as PROMOTION_OFFER_ID,
            bp.promotion_offer_code_used as PROMOTION_OFFER_CODE_USED,
            
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
            end
            as IS_DELIVERY_TYPE,
            case
            when b.is_delivery_mode = 'no' then 'N'
            when b.is_delivery_mode = 'yes' then 'N'
            when b.is_delivery_mode = 'hourly' then 'Y'
            end
            as IS_HOURLY_TYPE,
            
            bp.qitaf_request as QITAF_REDEEM_ID,
            bp.loyalty_program_for_oracle as LOYALTY_PROGRAM_ID,
            
            SUBSTRING_INDEX(b.pickup_delivery_lat_long , ',', 1 ) AS PICKUP_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.pickup_delivery_lat_long , ',', 2 ),',',-1) AS PICKUP_LONGITUDE,
            
            SUBSTRING_INDEX(b.dropoff_delivery_lat_long , ',', 1 ) AS DROPOFF_LATITUDE,
            SUBSTRING_INDEX(SUBSTRING_INDEX( b.dropoff_delivery_lat_long , ',', 2 ),',',-1) AS DROPOFF_LONGITUDE, 
            if (bp.cdw_plus_price > 0 , bp.cdw_plus_price, '') as CDW_PLUS,
            bp.niqaty_request as NIQATY_REDEEM_ID,
            bp.is_promo_discount_on_total as IS_PROMO_DISCOUNT_ON_TOTAL,
            
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
                        
            case
            when b.is_limousine = 'No' then 'N'
            when b.is_limousine = 'Yes' then 'Y'
            end
            as IS_LIMOUSINE,
            
            case
            when b.is_round_trip = 'No' then 'N'
            when b.is_round_trip = 'Yes' then 'Y'
            end
            as IS_ROUND_TRIP,
            
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
    )";

        if (!$force_resync) {
            $query .= " and b.sync='N'";
        }


        $query .= "left join branch bf on b.from_location=bf.id
        left join branch bt on b.to_location=bt.id
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
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
         left join individual_customer ic2 on big.individual_customer_id=ic2.id where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') $whereBookingId";

        $bookings = DB::select($query);

        return $bookings;
    }

    public function getUsers($booking_id)
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
        b.type as booking_type";


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
        $query .= "AND b.reservation_code = '" . $booking_id . "'";

        $query .= " group by ic.id_no, icg.id_no";

        $bookings = DB::select($query);

        return $bookings[0];
    }

    public function getActiveBookingsCollectionInfo($booking_id)
    {
        $selectCols = "b.id, b.reservation_code as BOOKING_ID,
        case
           WHEN b.booking_status = 'Walk in' THEN 'V'
           WHEN b.booking_status != 'Walk in' THEN 'A'
        end
        as BOOKING_STATUS,
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
          WHEN bipm.payment_method = 'Corporate Credit' THEN 'CREDIT'
          WHEN bipm.payment_method = 'Sadad' THEN 'PT_SD'
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
        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_payment as bp on b.id=bp.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id";

        $query .= " WHERE b.reservation_code = '" . $booking_id . "' AND (bccp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit')";


        $query = "select BOOKING_ID, BOOKING_STATUS,TRANS_TYPE,TRANS_METHOD,TRANS_REFERENCE,ACCOUNT_CARD_NO,TRANS_DATE,TRANS_AMOUNT
                  from (" . $query . ") t1 group by t1.id, t1.BOOKING_STATUS";

        $booking = DB::select($query);

        return $booking[0];

    }

    public function getCancelledBookingsCollectionInfo($booking_id)
    {

        $selectCols = "b.id, b.reservation_code as BOOKING_ID,
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
          WHEN bipm.payment_method = 'Sadad' THEN 'PT_SD'
          WHEN bipm.payment_method = 'Cash' THEN 'CASH'
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
        
           DATE_FORMAT(bc.cancel_time, '%d-%m-%Y %T') as TRANS_DATE,
        case
           WHEN bc.cancel_charges = '0.00' THEN bp.total_sum
           WHEN bc.cancel_charges != '0.00' THEN (bp.total_sum - bc.cancel_charges)
           end
        as TRANS_AMOUNT
         ";
        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id 
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        left join booking_payment as bp on b.id=bp.booking_id
        where (b.booking_status = 'Cancelled' OR b.booking_status = 'Expired') AND (bccp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit')
        ";
        $query .= " AND b.reservation_code = '" . $booking_id . "'";

        $query = "select BOOKING_ID, BOOKING_STATUS,TRANS_TYPE,TRANS_METHOD,TRANS_REFERENCE,ACCOUNT_CARD_NO,TRANS_DATE,TRANS_AMOUNT
                  from (" . $query . ") t1 group by t1.id, t1.BOOKING_STATUS";

        $booking = DB::select($query);

        return $booking;

    }


    public function getExpiredBookingsCollectionInfo($booking_id)
    {

        $selectCols = "b.id, b.reservation_code as BOOKING_ID,
        'E' as BOOKING_STATUS,
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
          WHEN bipm.payment_method = 'Sadad' THEN 'PT_SD'
          WHEN bipm.payment_method = 'Cash' THEN 'CASH'
          WHEN bipm.payment_method = 'Corporate Credit' THEN 'CREDIT'
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
        $query = "SELECT " . $selectCols . " FROM `booking` b

        left join booking_cancel as bc on b.id=bc.booking_id 
        left join booking_cc_payment as bccp on b.id=bccp.booking_id
        left join booking_sadad_payment as bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method as bipm on b.id=bipm.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        left join booking_payment as bp on b.id=bp.booking_id
        where b.booking_status = 'Expired' AND bc.sync = 'N' AND (bccp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit')
        ";
        $query .= " AND b.reservation_code = '" . $booking_id . "'";

        $query = "select BOOKING_ID, BOOKING_STATUS,TRANS_TYPE,TRANS_METHOD,TRANS_REFERENCE,ACCOUNT_CARD_NO,TRANS_DATE,TRANS_AMOUNT
                  from (" . $query . ") t1 group by t1.id, t1.BOOKING_STATUS";

        $booking = DB::select($query);

        return $booking;

    }


    public function updateBookingSyncStatus($booking_id)
    {
        $query = "UPDATE booking set sync='A', synced_at='" . date('Y-m-d H:i:s') . "' where reservation_code = '" . $booking_id . "'";
        $bookings = DB::statement($query);
        return true;
    }

    public function updateCancelledSyncStatus($booking_id)
    {
        $query = "UPDATE booking_cancel set sync='A', synced_at='" . date('Y-m-d H:i:s') . "' where booking_id = '" . $booking_id . "'";
        $bookings = DB::statement($query);
        return true;
    }

    public function getBookingsToUpdateStatus()
    {
        $query = "SELECT * FROM booking WHERE (booking_status = 'Not Picked' OR booking_status = 'Picked' OR booking_status = 'Walk in') AND sync != 'N'";
        $bookings = DB::select($query);
        return $bookings;
    }

    public function getRedeemInfo($booking_id)
    {
        $query = "SELECT b.reservation_code, DATE_FORMAT(b.created_at, '%d-%m-%Y %T') as created_at, bp.redeem_points, bp.redeem_discount_availed FROM booking b JOIN booking_payment bp on b.id=bp.booking_id AND bp.booking_id = $booking_id LIMIT 1";
        $bookings = DB::select($query);
        //echo '<pre>';print_r($bookings);exit();
        return $bookings[0];
    }

    public function getCountOfNotPickedBookingsForUser($uid)
    {
        $records = DB::table('booking as b')
            ->leftjoin('booking_individual_user as biu', 'b.id', '=', 'biu.booking_id')
            ->leftjoin('individual_customer as ic', 'biu.uid', '=', 'ic.uid')
            ->where('biu.uid', $uid)
            ->where('b.booking_status', 'Not Picked')
            ->select('b.*')
            ->count();
        return $records;
    }

    public function getCountOfNotPickedBookingsForCustomer($id)
    {
        $records = DB::table('booking as b')
            ->leftjoin('booking_individual_guest as big', 'b.id', '=', 'big.booking_id')
            ->leftjoin('individual_customer as ic', 'big.individual_customer_id', '=', 'ic.id')
            ->where('big.booking_id', $id)
            ->where('b.booking_status', 'Not Picked')
            ->select('b.*')
            ->count();
        return $records;
    }

    // DATE_FORMAT(bccp.trans_date, '%d-%m-%Y %T')

}

?>