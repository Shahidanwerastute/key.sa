<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;
use App\Helpers\Custom;

class Page extends Model
{

    protected $table = 'loyalty_program_listing';

    //public $timestamps = true;

    public function getAll($tbl, $orderBy = 'id', $sort = 'asc')
    {
        $records = DB::table($tbl)
            ->select('*')
            ->orderBy($orderBy, $sort)
            ->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    // kashif work
    public function getAllCars($car_ids = "", $orderBy = 'car_model.id', $sort = 'asc', $offset = "", $limit = "")
    {

        $query = DB::table('car_model');
        $query->join('car_type', 'car_model.car_type_id', 'car_type.id');
        $query->join('car_group', 'car_type.car_group_id', 'car_group.id');
        $query->join('car_category', 'car_group.car_category_id', 'car_category.id');
        $query->select('car_model.*', 'car_type.eng_title as ct_eng_title',
            'car_type.arb_title as ct_arb_title', 'car_group.eng_title as cg_eng_title',
            'car_group.arb_title as cg_arb_title', 'car_category.eng_title as cc_eng_title',
            'car_category.arb_title as cc_arb_title');
        $query->where('car_model.active_status', '=', '1');


        $query->whereIn('car_model.id', function ($query) {
            $query->select('car_model_id')
                ->from("car_price");
        });

        if ($car_ids != "") {
            $query->whereIn('car_model.id', explode(',', $car_ids));
        }

        //
        //$query->orderBy('car_model.year', 'desc');
        //$query->orderBy('car_category.sort_col', 'asc');
        $query->orderBy('car_model.sort_col', 'asc');

        if ($offset != "" && $limit != "") {
            $query->offset($offset);
            $query->limit($limit);
        }

        $records = $query->get();
        return $records;
    }


    /*public function getCarsPagination($loadMore,$limit,$where_id = "", $orderBy = 'cm.id', $sort = 'asc')
    {

        $query = DB::table('car_model as cm');
        $query->join('car_type as ct', 'cm.car_type_id', 'ct.id');
        $query->join('car_group as cg', 'ct.car_group_id', 'cg.id');
        $query->join('car_category as cc', 'cg.car_category_id', 'cc.id');
        $query->select('cm.*', 'ct.eng_title as ct_eng_title',
            'ct.arb_title as ct_arb_title', 'cg.eng_title as cg_eng_title',
            'cg.arb_title as cg_arb_title', 'cc.eng_title as cc_eng_title',
            'cc.arb_title as cc_arb_title');
        $query->where('cm.active_status', '=', '1');

        if ($where_id != "") {
            $query->where('cm.id', '=', $where_id);
        }

        // search filter wheres and load more
        if ($loadMore['branch'] != "") {
            $query->join('car_availability as ca', 'ca.car_model_id', '=', 'cm.id');
            $query->where('ca.branch_id', '=', $loadMore['branch']);
        }
        if ($loadMore['model'] != "")
            $query->where('cm.year', '=', $loadMore['model']);

        if ($loadMore['capacity'] != "")
            $query->where('cm.no_of_passengers', '=', $loadMore['capacity']);

        if ($loadMore['cat_id'] != "" && $loadMore['cat_id'] != 0)
            $query->where('cc.id', '=', $loadMore['cat_id']);
        //=====

        $query->orderBy($orderBy, $sort);

        $query->offset($loadMore['offset']);
        $query->limit($limit);

        $records = $query->get();
        return $records;
    }*/


    public function getCarsByAllFilters($srData, $limit)
    {
        $query = DB::table('car_model as cm');
        $query->join('car_type as ct', 'cm.car_type_id', 'ct.id');
        $query->join('car_group as cg', 'ct.car_group_id', 'cg.id');
        $query->join('car_category as cc', 'cg.car_category_id', 'cc.id');
        $query->select('cm.*', 'ct.eng_title as ct_eng_title',
            'ct.arb_title as ct_arb_title', 'cg.eng_title as cg_eng_title',
            'cg.arb_title as cg_arb_title', 'cc.eng_title as cc_eng_title',
            'cc.arb_title as cc_arb_title');

        $query->where('cm.active_status', '=', '1');

        // search filter wheres
        if ($srData['branch'] != "") {
            $query->join('car_availability as ca', 'ca.car_model_id', '=', 'cm.id');
            $query->where('ca.branch_id', '=', $srData['branch']);
        }
        if ($srData['model'] != "")
            $query->where('cm.year', '=', $srData['model']);

        /*if ($srData['capacity'] != "")
            $query->where('cm.no_of_passengers', '=', $srData['capacity']);*/

        if ($srData['cat_id'] != "" && $srData['cat_id'] != 0)
            $query->where('cc.id', '=', $srData['cat_id']);
        //=====

        //$query->orderBy('cm.year', 'desc');
        //$query->orderBy('cc.sort_col', 'asc');
        $query->orderBy('cm.sort_col', 'asc');

        $query->offset($srData['offset']);
        $query->limit($limit);

        $records = $query->get();

        return $records;
    }


    public function getMultipleRows($tbl, $get_by, $orderBy = 'id', $sort = 'asc')
    {
        //DB::enableQueryLog();
        $records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            ->orderBy($orderBy, $sort)
            ->get();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getMultipleRowsWithLimit($tbl, $get_by, $orderBy = 'id', $sort = 'asc', $limit = 10)
    {
        $records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            ->orderBy($orderBy, $sort)
            ->limit($limit)
            ->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getNotPickedBookings($tbl, $get_by)
    {
        //$query = " SELECT * FROM `booking` where `booking_status` = 'Not Picked' ORDER BY `id` ASC  ";
        //$records = DB::select($query);

        /*$result = array();
        DB::table($tbl)->select('*')->where('booking_status',$get_by)->orderBy('id', 'asc')->chunk(1500, function ($ch_records) use (&$result) {
            $result[] = $ch_records->toArray();
        });

        if (isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }*/

        $query = DB::table('booking as b');
        $query->join('booking_payment as bp', 'bp.booking_id', 'b.id');
        $query->select('*');
        $query->where('b.booking_status', 'Not Picked');
        $orderBy = 'b.id';
        $query->orderBy($orderBy, 'asc');

        $records = $query->get();

        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getCancelNotExpired()
    {
        $query = DB::table('booking_cc_payment as bcp');
        $query->join('booking as bk', 'bcp.booking_id', 'bk.id');
        $query->select('bcp.*', 'bk.booking_status as booking_status');

        $query->where('booking_status', 'Not Picked');
        $query->where('bcp.status', '=', 'pending');
        $orderBy = 'bcp.booking_id';
        $query->orderBy($orderBy, 'desc');

        $records = $query->get();

        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getStsBookingsForInquiry($orderBy = 'id', $sort = 'asc')
    {
        //DB::enableQueryLog();

        //previous query
        /*$records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            //->whereNotNull('sts_attempts')
            ->where(function ($query) {
                $query->where('booking_id', '>', 1550)
                    ->orWhere('booking_id', '>', 63500);
            })
            ->orderBy($orderBy, $sort)
            ->get();*/

        //New query
        $query = DB::table('booking_cc_payment as bcp');
        $query->join('booking as bk', 'bcp.booking_id', 'bk.id');
        $query->select('bcp.*', 'bk.booking_source as booking_source');

        $query->where('bcp.status', '=', 'pending');
        $query->where('bcp.payment_company', '=', 'sts');
        $query->where('bcp.is_sts_inquired', '=', '0');
        $query->where(function ($query) {
            $query->where('bcp.booking_id', '>', 1550)
                ->orWhere('bcp.booking_id', '>', 63500);
        });

        // Bilal work 27-11-2019, taking all bookings which are behind 10 minutes then current time
        // $query->where('bcp.trans_date', '<=', date('Y-m-d H:i:s', strtotime("-10 minutes")));
        $query->where('bk.created_at', '<=', date('Y-m-d H:i:s', strtotime("-10 minutes")));

        $orderBy = 'bcp.' . $orderBy;
        $query->orderBy($orderBy, $sort);

        // custom::enable_query_log();
        $records = $query->get();
        // custom::get_query_log();exit();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function isStsBookingsStatusPending($booking_id)
    {
        $query = DB::table('booking_cc_payment');

        $query->where('booking_id', '=', $booking_id);
        $query->where('status', '=', 'pending');
        $query->where('payment_company', '=', 'sts');

        $record = $query->first();

        if ($record) {
            return true;
        } else {
            return false;
        }
    }

    public function getCancelledBookings()
    {
        //DB::enableQueryLog();
        /*$record = DB::table('booking_cancel')
            ->where('booking_cancel.sync', '=', 'N')
            ->where('booking_cancel.cancel_time', '>', '2018-09-30 00:00:00')
            ->select('booking_cancel.*')->get();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/

        $query = " SELECT bc.* FROM `booking_cancel` bc
                  left join booking_cc_payment bcp on bc.booking_id=bcp.booking_id
                  left join booking_sadad_payment bsp on bc.booking_id=bsp.s_booking_id
                  left join booking_individual_payment_method bipm on bc.booking_id=bipm.booking_id
                  left join booking_corporate_invoice bci on bc.booking_id=bci.booking_id ";

        $query .= " WHERE bc.sync = 'N' AND bc.cancel_time > '2019-01-31 00:00:00' AND
         (bcp.status='completed' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit' or bci.payment_status='paid') ";
        $record = DB::select($query);
        return $record;
    }

    public function getStsPendingPayments()
    {
        //DB::enableQueryLog();
        $records = DB::table("booking_corporate_invoice")
            ->select('*')
            ->where("payment_status", "pending")
            ->where("continue_inquiry", "1")
            ->get();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getRowsCount($tbl, $get_by, $orderBy = 'id', $sort = 'asc')
    {
        $records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            ->orderBy($orderBy, $sort)
            ->count();
        return $records;
    }

    public function getSingle($tbl, $get_by)
    {
        $record = DB::table($tbl)
            ->where($get_by)
            ->select('*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function saveData($tbl, $data)
    {
        $savedId = DB::table($tbl)->insertGetId($data);
        if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }
    }

    public function updateData($tbl, $data, $update_by)
    {
        $updated = DB::table($tbl)
            ->where($update_by)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDataInBatch($tbl, $data, $where_in_column, $where_in_values)
    {
        $updated = DB::table($tbl)
            ->whereIn($where_in_column, $where_in_values)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData($tbl, $delete_by)
    {
        $deleted = DB::table($tbl)
            ->where($delete_by)
            ->delete();
        if ($deleted) {
            return true;
        } else {
            return false;
        }
    }

    public function truncateData($tbl)
    {
        $truncated = DB::table($tbl)
            ->truncate();
        if ($truncated) {
            return true;
        } else {
            return false;
        }
    }


    public function getAllNationalities($lang = 'eng')
    {
        if ($lang == 'eng') {
            $orderBy = 'eng_country_name';
        } else {
            $orderBy = 'arb_country_name';
        }

        $nationalitites = DB::table('nationalities')
            ->select('*')
            ->orderBy($orderBy)
            ->get();
        return $nationalitites;
    }

    public function getAllCountries($lang = 'eng')
    {
        if ($lang == 'eng') {
            $orderBy = 'eng_country';
        } else {
            $orderBy = 'arb_country';
        }

        $countries = DB::table('country')
            ->select('*')
            ->orderBy($orderBy)
            ->get();
        return $countries;
    }

    public function checkIfIndividualUserExist($where)
    {
        $users = DB::table('individual_customer')
            ->select('*')
            ->where($where)
            ->get();

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        if (count($users) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCorporateQuotationPrices($search_by, $corporate_customer_id, $modelId = "", $offset = "", $limit = "", $loyalty_discount_percent = "", $car_price_sort = '') {
                $query = "SELECT
                        cm.*, 
                        cqp.car_type as cqp_oracle_reference_number,
                        cqp.car_model as cqp_year,
                        NULL AS cpid, 
                        'Rent' as charge_element, 
                        0 as is_one_time_applicable_on_booking, 
                        
                        CASE
                        WHEN '" . $loyalty_discount_percent . "' = '' AND " . $search_by['days'] . " BETWEEN 1 AND 26 THEN cqp.daily_rent
                        WHEN '" . $loyalty_discount_percent . "' = '' AND " . $search_by['days'] . " > 26 THEN cqp.monthly_rent
                        WHEN '" . $loyalty_discount_percent . "' != '' AND " . $search_by['days'] . " BETWEEN 1 AND 26 THEN cqp.daily_rent-((cqp.daily_rent*'" . $loyalty_discount_percent . "')/100)
                        WHEN '" . $loyalty_discount_percent . "' != '' AND " . $search_by['days'] . " > 26 THEN cqp.monthly_rent-((cqp.monthly_rent*'" . $loyalty_discount_percent . "')/100)
                        END as price, 
                        
                        CASE
                        WHEN " . $search_by['days'] . " BETWEEN 1 AND 26 THEN 1
                        WHEN " . $search_by['days'] . " > 26 THEN 4
                        END as renting_type_id, 
                        
                        cqp.monthly_rent as three_month_subscription_price, 
                        cqp.monthly_rent as six_month_subscription_price, 
                        cqp.monthly_rent as nine_month_subscription_price, 
                        cqp.monthly_rent as twelve_month_subscription_price,
                        
                        CASE
                        WHEN " . $search_by['days'] . " BETWEEN 1 AND 26 THEN cqp.daily_rent
                        WHEN " . $search_by['days'] . " > 26 THEN cqp.monthly_rent
                        END as old_price, 
                        
                        ct.eng_title AS ct_eng_title, 
                        ct.arb_title AS ct_arb_title, 
                        cg.eng_title AS cg_eng_title, 
                        cg.arb_title AS cg_arb_title, 
                        cc.eng_title AS cc_eng_title, 
                        cc.arb_title AS cc_arb_title
                        
                        FROM corporate_quotations cq
                        
                        JOIN corporate_quotation_prices cqp ON cq.id = cqp.corporate_quotation_id
                        JOIN car_model cm ON cqp.car_type = cm.oracle_reference_number AND cqp.car_model = cm.year
                        JOIN car_type ct ON cm.car_type_id = ct.id
                        JOIN car_group cg ON ct.car_group_id = cg.id
                        JOIN car_category cc ON cg.car_category_id = cc.id
                        JOIN car_availability ca ON cm.id = ca.car_model_id
                        
                        WHERE
                        
                        cq.corporate_customer_id = " . $corporate_customer_id . " AND
                        (cq.applies_from IS NULL OR DATE(cq.applies_from) <= '" . $search_by['pickup_date'] . "') AND
                        (cq.applies_to IS NULL OR DATE(cq.applies_to) >= '" . $search_by['pickup_date'] . "') AND
                        cq.is_closed = 0 AND
                        ca.is_corp_avail = 1 AND
                        cm.active_status = '1'
                        ";

                if ($search_by['category'] > 0) {
                    $query .= " AND cc.id = '" . $search_by['category'] . "'";
                }

                // kashif work
                if ($modelId != "") {
                    $query .= " AND cm.id = $modelId";
                }

                $query .= "GROUP BY cm.id";

                //$query .= " ORDER BY cm.year desc, cc.sort_col asc";
                if ($car_price_sort != '') {
                    $query .= " ORDER BY cqp.daily_rent $car_price_sort";
                } else {
                    $query .= " ORDER BY cm.sort_col asc";
                }

                if ($limit != "" && $offset != "") {
                    // $query .= " LIMIT $limit OFFSET $offset";
                }

                // echo $query;die;

                $models = DB::select("$query");
                // custom::dump($models);
                return $models;
    }

    public function getAllCarModels($search_by, $type, $modelId = "", $offset = "", $limit = "", $loyalty_discount_percent = "", $car_price_sort = '', $corporate_quotation_cars_array = [])
    {
        $session_vars = Session::all();
        $cp_to = '';
        if (isset($search_by['isLimousine']) && $search_by['isLimousine'] == 1) {
            $cp_to .= " AND cp.to_branch = '" . $session_vars['search_data']['to_branch_id'] . "' ";
        } else {
            $search_by['isLimousine'] = 0;
            $search_by['isRoundTripForLimousine'] = 0;
        }

        $query = "SELECT cm.*,
       cpt.id AS cpid,
       cpt.charge_element,
       cpt.is_one_time_applicable_on_booking,
       CASE
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '2' THEN cpt.two_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '3' THEN cpt.three_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '4' THEN cpt.four_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '5' THEN cpt.five_hourly_price
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '3' THEN (cpt.three_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '6' THEN (cpt.six_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '9' THEN (cpt.nine_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '12' THEN (cpt.twelve_month_subscription_price / 30)
       
       WHEN '" . $search_by['isLimousine'] . "' = '1' AND '" . $search_by['isRoundTripForLimousine'] . "' = '0' THEN cpt.rate_per_one_trip
       WHEN '" . $search_by['isLimousine'] . "' = '1' AND '" . $search_by['isRoundTripForLimousine'] . "' = '1' THEN cpt.rate_per_round_trip
       
       WHEN '" . $loyalty_discount_percent . "' = '' THEN cpt.price
       WHEN '" . $loyalty_discount_percent . "' != '' THEN cpt.price-((cpt.price*'" . $loyalty_discount_percent . "')/100)
       
       END as price,
       cpt.renting_type_id,
       cpt.three_month_subscription_price,
       cpt.six_month_subscription_price,
       cpt.nine_month_subscription_price,
       cpt.twelve_month_subscription_price,
       cpt.extra_hours_rate_for_limousine,
       CASE
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '2' THEN cpt.two_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '3' THEN cpt.three_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '4' THEN cpt.four_hourly_price
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '" . $search_by['book_for_hours'] . "' = '5' THEN cpt.five_hourly_price
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '3' THEN (cpt.three_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '6' THEN (cpt.six_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '9' THEN (cpt.nine_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '" . $search_by['subscribe_for_months'] . "' = '12' THEN (cpt.twelve_month_subscription_price / 30)
       
       WHEN '" . $search_by['isLimousine'] . "' = '1' AND '" . $search_by['isRoundTripForLimousine'] . "' = '0' THEN cpt.rate_per_one_trip
       WHEN '" . $search_by['isLimousine'] . "' = '1' AND '" . $search_by['isRoundTripForLimousine'] . "' = '1' THEN cpt.rate_per_round_trip
       
       WHEN '" . $search_by['is_delivery_mode'] . "' NOT IN ('2', '4') THEN cpt.price
       
       END as old_price,
       ct.eng_title AS ct_eng_title,
       ct.arb_title AS ct_arb_title,
       cg.eng_title AS cg_eng_title,
       cg.arb_title AS cg_arb_title,
       cc.eng_title AS cc_eng_title,
       cc.arb_title AS cc_arb_title
FROM
  (SELECT *
   FROM
     (SELECT t1.*
      FROM
        (SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '" . $search_by['city_id'] . "'
           AND cp.branch_id = '" . $search_by['branch_id'] . "'
            $cp_to
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '" . $search_by['city_id'] . "'
           AND cp.branch_id = '0'
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '0'
           AND cp.branch_id = '0'
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '0'
           AND cp.city_id = '0'
           AND cp.branch_id = '0' ) t1
      JOIN setting_renting_type rt ON t1.renting_type_id = rt.id
      JOIN car_availability ca ON ca.car_model_id=t1.car_model_id and ca.branch_id='" . $search_by['branch_id'] . "' ";

        if ($search_by['is_delivery_mode'] == 2) {
            $query .= " and t1.hourly_rate = 'Yes' ";
        }

        if ($search_by['is_delivery_mode'] == 4) {
            $query .= " and t1.subscription_rate = 'Yes' ";
        }

        if (isset($search_by['isLimousine']) && $search_by['isLimousine'] == 1) {
            $query .= " and t1.is_for_limousine_mode_only = 'yes' ";
        } else {
            $query .= " and t1.is_for_limousine_mode_only = 'no' ";
        }

        if (Session::get('user_type') == null || Session::get('user_type') == '' || Session::get('user_type') == 'individual_customer')
            $query .= " and ca.is_indi_avail = 1 ";
        if (Session::get('user_type') == 'corporate_customer')
            $query .= " and ca.is_corp_avail = 1 ";


        $query .= " WHERE rt.from_days <= '" . $search_by['days'] . "'";

        if ($type == 'rent') {
            $query .= " AND charge_element ='Rent'";
        } else {
            $query .= " AND t1.car_model_id='" . Session::get('car_id') . "' AND charge_element !='Rent'";
        }

        $query .= " AND (t1.applies_from <= '" . $search_by['pickup_date'] . "')
        AND (t1.applies_to IS NULL
             OR t1.applies_to >= '" . $search_by['pickup_date'] . "') ";


        $query .= " AND (t1.customer_type = \"\"
             OR t1.customer_type = '" . $search_by['customer_type'] . "') ";

        if (isset($search_by['company_code'])) {
            $query .= " AND (t1.company_code = 0
             OR  t1.company_code = '" . $search_by['company_code'] . "') ";
        }
        /*else
        {
            $query .= " AND (t1.customer_type = \"\"
             OR t1.customer_type = '" . $search_by['customer_type'] . "') ";
        }*/


        $query .= " ORDER BY t1.car_model_id DESC, t1.region_id DESC, t1.city_id DESC, t1.branch_id DESC, t1.customer_type DESC, t1.company_code DESC ,rt.from_days DESC, t1.applies_from DESC
      LIMIT 18446744073709551615) t2
   GROUP BY charge_element, t2.car_model_id) cpt,
     car_model cm,
     car_type ct,
     car_group cg,
     car_category cc
     
     
     
WHERE cpt.car_model_id = cm.id
  AND cm.car_type_id = ct.id
  AND ct.car_group_id = cg.id
  AND cg.car_category_id = cc.id AND cm.active_status = '1'";
        if ($search_by['category'] > 0) {
            $query .= " AND cc.id = '" . $search_by['category'] . "'";
        }

        // kashif work
        if ($modelId != "") {
            $query .= " AND cm.id = $modelId";
        }

        if ($corporate_quotation_cars_array) {
            $query .= " AND CONCAT(cm.oracle_reference_number,'-',cm.year) NOT IN (" . implode(',', $corporate_quotation_cars_array) . ")";
        }

        //$query .= " ORDER BY cm.year desc, cc.sort_col asc";
        if ($car_price_sort != '') {
            $query .= " ORDER BY cpt.price $car_price_sort";
        } else {
            $query .= " ORDER BY cm.sort_col asc";
        }

        if ($limit != "" && $offset != "") {
            // $query .= " LIMIT $limit OFFSET $offset";
        }


        // echo $query; exit();
        $models = DB::select("$query");

        return $models;

    }


    public function getUpgradeHumanLessCars($search_by, $type, $whereQC = "", $offset = "", $limit = "", $loyalty_discount_percent = "", $car_price_sort = '')
    {
        $query = "SELECT cm.*,
       cpt.id AS cpid,
       cpt.charge_element,
       cpt.is_one_time_applicable_on_booking,
       CASE
       WHEN '" . $loyalty_discount_percent . "' = '' THEN cpt.price
       WHEN '" . $loyalty_discount_percent . "' != '' THEN cpt.price-((cpt.price*'" . $loyalty_discount_percent . "')/100)
       END as price,
       cpt.renting_type_id,
       cpt.three_month_subscription_price,
       cpt.six_month_subscription_price,
       cpt.nine_month_subscription_price,
       cpt.twelve_month_subscription_price,
       cpt.price as old_price,
       ct.eng_title AS ct_eng_title,
       ct.arb_title AS ct_arb_title,
       cg.eng_title AS cg_eng_title,
       cg.arb_title AS cg_arb_title,
       cc.eng_title AS cc_eng_title,
       cc.arb_title AS cc_arb_title
FROM
  (SELECT *
   FROM
     (SELECT t1.*
      FROM
        (SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '" . $search_by['city_id'] . "'
           AND cp.branch_id = '" . $search_by['branch_id'] . "'
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '" . $search_by['city_id'] . "'
           AND cp.branch_id = '0'
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '" . $search_by['region_id'] . "'
           AND cp.city_id = '0'
           AND cp.branch_id = '0'
         UNION SELECT cp.*
         FROM car_price cp
         WHERE cp.region_id = '0'
           AND cp.city_id = '0'
           AND cp.branch_id = '0' ) t1
      JOIN setting_renting_type rt ON t1.renting_type_id = rt.id
      JOIN car_availability ca ON ca.car_model_id=t1.car_model_id and ca.branch_id='" . $search_by['branch_id'] . "' ";

        if (Session::get('user_type') == 'individual_customer')
            $query .= " and ca.is_indi_avail = 1 ";
        if (Session::get('user_type') == 'corporate_customer')
            $query .= " and ca.is_corp_avail = 1 ";

        $query .= " WHERE rt.from_days <= '" . $search_by['days'] . "'";

        if ($type == 'rent') {
            $query .= " AND charge_element ='Rent'";
        } else {
            $query .= " AND t1.car_model_id='" . Session::get('car_id') . "' AND charge_element !='Rent'";
        }

        $query .= " AND (t1.applies_from <= '" . $search_by['pickup_date'] . "')
        AND (t1.applies_to IS NULL
             OR t1.applies_to >= '" . $search_by['pickup_date'] . "') ";


        $query .= " AND (t1.customer_type = \"\"
             OR t1.customer_type = '" . $search_by['customer_type'] . "') ";

        if (isset($search_by['company_code'])) {
            $query .= " AND (t1.company_code = 0
             OR  t1.company_code = '" . $search_by['company_code'] . "') ";
        }

        $query .= " ORDER BY t1.car_model_id DESC, t1.region_id DESC, t1.city_id DESC, t1.branch_id DESC, t1.customer_type DESC, t1.company_code DESC ,rt.from_days DESC, t1.applies_from DESC
                    LIMIT 18446744073709551615) t2
                    GROUP BY charge_element, t2.car_model_id) cpt,
                    car_model cm,
                    car_type ct,
                    car_group cg,
                    car_category cc
                    WHERE cpt.car_model_id = cm.id
                    AND cm.car_type_id = ct.id
                    AND ct.car_group_id = cg.id
                    AND cg.car_category_id = cc.id AND cm.active_status = '1'";
        if ($search_by['category'] > 0) {
            $query .= " AND cc.id = '" . $search_by['category'] . "'";
        }

        if ($whereQC != "") {
            $query .= " AND " . $whereQC;
        }
        //$query .= " ORDER BY cm.year desc, cc.sort_col asc";
        if ($car_price_sort != '') {
            $query .= " ORDER BY cpt.price $car_price_sort";
        } else {
            $query .= " ORDER BY cm.sort_col asc";
        }
        if ($limit != "" && $offset != "") {
            $query .= " LIMIT $limit OFFSET $offset";
        }

        //echo $query; exit();
        $models = DB::select("$query");
        return $models;
    }

    public function getRegions()
    {

        $record = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'no')
            ->where('branch.is_for_limousine_mode_only', '=', 'no')
            ->select('branch.*', 'region.id as region_id', 'city.eng_title as c_eng_title',
                'city.arb_title as c_arb_title', 'city.id as cit')
            ->orderBy('branch.eng_title')->orderBy('city.eng_title')->get();
        return $record;
    }

    public function getDeliveryRegions()
    {

        $record = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'yes')
            ->where('branch.is_for_limousine_mode_only', '=', 'no')
            ->select('branch.*', 'region.id as region_id', 'city.eng_title as c_eng_title',
                'city.arb_title as c_arb_title', 'city.id as cit')
            ->orderBy('branch.eng_title')->orderBy('city.eng_title')->get();
        return $record;
    }

    public function getAirportRegions()
    {

        $record = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            ->where('branch.is_airport', '=', '1')
            ->select('branch.*', 'region.id as region_id', 'city.eng_title as c_eng_title',
                'city.arb_title as c_arb_title', 'city.id as cit')
            ->orderBy('branch.eng_title')->orderBy('city.eng_title')->get();
        return $record;
    }

    public function getBranchesForSearchFilter($filter)
    {

        $query = "select b.id as branch_id, b.city_id as city_id FROM branch b JOIN city c ON b.city_id=c.id and (b.eng_title LIKE \"%$filter%\" OR b.arb_title LIKE \"%$filter%\" OR c.eng_title LIKE \"%$filter%\" OR c.arb_title LIKE \"%$filter%\")
UNION
select b.id as branch_id, b.city_id as city_id FROM branch b JOIN car_availability ca ON b.id=ca.branch_id JOIN car_model cm ON ca.car_model_id=cm.id JOIN car_type ct ON cm.car_type_id=ct.id and (cm.eng_title LIKE \"%$filter%\" OR cm.arb_title LIKE \"%$filter%\" OR ct.eng_title LIKE \"%$filter%\" OR ct.arb_title LIKE \"%$filter%\" OR CONCAT (ct.eng_title, ' ', cm.eng_title) LIKE \"%$filter%\" OR CONCAT (ct.arb_title, ' ', cm.arb_title) LIKE \"%$filter%\" OR CONCAT (ct.eng_title, cm.eng_title) LIKE \"%$filter%\" OR CONCAT (ct.arb_title, cm.arb_title) LIKE \"%$filter%\")";

        //echo $query;exit();
        $records = DB::select($query);
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getBranchIdsForPromo($branch_id)
    {

        $record = DB::table('branch')
            ->where('branch.active_status', '=', '1')
            ->where('branch.id', '=', $branch_id)
            ->select('id')
            ->orderBy('eng_title')->first();
        return $record;
    }


    /*public function getBranchesForOffer($branch_ids)
    {
        if (count($branch_ids) > 0)
        {
            $branch_ids_comma_seperated = "";
            foreach ($branch_ids as $row) {
                if ($branch_ids_comma_seperated != "") $branch_ids_comma_seperated .= ",";
                $branch_ids_comma_seperated .= "'" . $row. "'";
            }
        }

        $customQ = "SELECT region.id as region_id, city.eng_title as c_eng_title, city.arb_title as c_arb_title, branch.* FROM region LEFT JOIN city on region.id = city.region_id LEFT JOIN branch on city.id = branch.city_id WHERE branch.active_status = '1'";
        if ($branch_ids && count($branch_ids) > 0)
        {
            $customQ .= " AND branch.id in (" . $branch_ids_comma_seperated . ")";
        }
        $record = DB::select("$customQ");
        if (count($record) > 0)
        {
            return $record;
        }else{
            return array();;
        }

    }*/


    public function getBranchesForOffer($branch_ids)
    {
        if (count($branch_ids) > 0) {
            $branch_ids_comma_seperated = "";
            foreach ($branch_ids as $row) {
                if ($branch_ids_comma_seperated != "") $branch_ids_comma_seperated .= ",";
                $branch_ids_comma_seperated .= "'" . $row . "'";
            }
        }

        $customQ = "SELECT region.id as region_id, city.eng_title as c_eng_title, city.arb_title as c_arb_title, city.id as cit, branch.* FROM region LEFT JOIN city on region.id = city.region_id LEFT JOIN branch on city.id = branch.city_id WHERE branch.active_status = '1'";
        if ($branch_ids && count($branch_ids) > 0) {
            $customQ .= " AND branch.id in (" . $branch_ids_comma_seperated . ")";
        }
        $record = DB::select("$customQ");
        if (count($record) > 0) {
            return $record;
        } else {
            return array();
        }

    }

    public function getBranches()
    {

        $record = DB::table('branch')
            ->join('city', 'branch.city_id', '=', 'city.id')
            ->where('branch.active_status', '=', '1')
            ->select('branch.id as branch_id', 'branch.eng_title as br_eng_title',
                'branch.arb_title as br_arb_title', 'city.eng_title as c_eng_title',
                'city.arb_title as c_arb_title')
            ->orderBy('branch.eng_title')->get();
        return $record;
    }

    public function getBranchesByCity($city_id, $is_delivery_mode)
    {
        $record = DB::table('branch');
        $record->where('city_id', '=', $city_id);
        $record->where('active_status', '=', '1');
        if ($is_delivery_mode == 1) {
            // $record->where('branch.is_delivery_branch', '=', 'yes');
        } else {
            // $record->where('branch.is_delivery_branch', '=', 'no');
        }
        $record->where('branch.is_for_limousine_mode_only', '=', 'no');
        $record->select('*');
        $record->orderBy('eng_title');
        $result = $record->get();
        return $result;
    }

    public function getBranchesAndCities($where = "")
    {
        $record = DB::table('branch');
        $record->join('city', 'branch.city_id', '=', 'city.id');

        $record->where('branch.active_status', '=', '1');
        $record->whereNotIn('branch.id', [35,50,97,62,16,78,29]);
        if ($where != "") {
            $record->where('branch.id', '=', $where);
        }
        // $record->where('branch.is_delivery_branch', '=', 'no');
        $record->where('branch.is_for_limousine_mode_only', '=', 'no');

        $record->select('branch.*', 'branch.id as branch_id', 'city.eng_title as c_eng_title',
            'city.arb_title as c_arb_title');
        $record->orderBy('city.id');
        //$record->groupBy('c_eng_title');
        if ($where != "")
            $result = $record->first();
        else
            $result = $record->get();
        return $result;

    }


    public function getBranchesAndCitiesWithAirport()
    {
        $record = DB::table('branch');
        $record->join('city', 'branch.city_id', '=', 'city.id');

        $record->where('branch.active_status', '=', '1');
        $record->whereNotIn('branch.id', [35,50,97,62,16,78,29]);
        $record->where('branch.is_airport', '=', '1');
        // $record->where('branch.is_delivery_branch', '=', 'no');
        $record->where('branch.is_for_limousine_mode_only', '=', 'no');

        $record->select('branch.*', 'branch.id as branch_id', 'city.eng_title as c_eng_title',
            'city.arb_title as c_arb_title');
        $record->orderBy('city.id');
        //$record->groupBy('c_eng_title');

        $result = $record->get();
        return $result;

    }

    public function getBranchesOfCities()
    {
        $record = DB::table('branch')
            ->join('city', 'branch.city_id', '=', 'city.id')
            // ->where('branch.is_delivery_branch', '=', 'no')
            ->where('branch.active_status', '=', '1')
                ->where('branch.is_for_limousine_mode_only', '=', 'no')

            ->whereNotIn('branch.id', [35,50,97,62,16,78,29])
            ->select('branch.*', 'branch.eng_title as branch_title', 'branch.id as branch_id', 'city.eng_title as c_eng_title',
                'city.arb_title as c_arb_title')
            ->orderBy('city.id')->get();
        return $record;
    }

    public function getSingleCarDetail($car_model_id)
    {
        $record = DB::table('car_model as cm')
            ->leftjoin('car_type as ct', 'cm.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', '=', 'cc.id')
            ->where('cm.id', $car_model_id)
            ->select('cm.*', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'cc.eng_title as car_category_eng_title', 'cc.arb_title as car_category_arb_title')
            ->first();
        return $record;
    }

    public function getSingleBranchDetail($branch_id)
    {

        $record = DB::table('branch as b')
            ->leftjoin('city as c', 'b.city_id', '=', 'c.id')
            ->where('b.id', $branch_id)
            ->select('b.*', 'c.eng_title as city_eng_title', 'c.arb_title as city_arb_title')
            ->first();
        return $record;

    }

    public function getHomePageOffers()
    {
        $current = date('Y-m-d H:i:s');
        $record = DB::table('promotion_offer as po')
            ->join('promotion_offer_car_model as pocm', 'pocm.promotion_offer_id', 'po.id')
            ->where('po.display_on_home', '=', '1')
            ->where('po.active_status', 'Active')
            ->whereRaw("(po.applies_from is null or po.applies_from <= '" . $current . "') AND (po.applies_to is null or po.applies_to >= '" . $current . "')")
            ->select('po.*', 'pocm.car_model_id')
            ->groupBy('po.id')
            ->get();
        return $record;
    }

    public function getOfferPageOffers()
    {
        $current = date('Y-m-d H:i:s');
        $record = DB::table('promotion_offer as po')
            ->join('promotion_offer_car_model as pocm', 'pocm.promotion_offer_id', 'po.id')
            ->where('po.display_on_offer', '=', '1')
            ->where('po.active_status', 'Active')
            ->whereRaw("(po.applies_from is null or po.applies_from <= '" . $current . "') AND (po.applies_to is null or po.applies_to >= '" . $current . "')")
            ->select('po.*', 'pocm.car_model_id')
            ->groupBy('po.id');
        $record = $record->get();
        return $record;
    }

    public function getOffersForCarModel($car_model_id)
    {
        $current = date('Y-m-d H:i:s');
        $record = DB::table('promotion_offer as po')
            ->join('promotion_offer_car_model as pocm', 'pocm.promotion_offer_id', 'po.id')
            ->where('pocm.car_model_id', $car_model_id)
            ->where('po.active_status', 'Active')
            ->whereRaw("(po.applies_from is null or po.applies_from <= '" . $current . "') AND (po.applies_to is null or po.applies_to >= '" . $current . "')")
            ->select('po.*', 'pocm.car_model_id')
            ->get();
        return $record;
    }

    public function checkIfIDNoRegistered($posted_id_no)
    {
        $users = DB::table('individual_customer')
            ->select('*')
            ->where('id_no', $posted_id_no)
            ->where('uid', '>', '0')
            ->get();

        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        if (count($users) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getSingleBookingDetails($booking_id, $user_type = "individual_user", $lang = 'eng')
    {
        $record = DB::table('booking as b')
            ->leftjoin('car_model as cm', 'b.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'cm.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', '=', 'cc.id')
            ->leftjoin('booking_individual_user as biu', 'b.id', '=', 'biu.booking_id')
            ->leftjoin('individual_customer as ic', 'biu.uid', '=', 'ic.uid')
            ->leftjoin('booking_individual_guest as big', 'b.id', '=', 'big.booking_id')
            ->leftjoin('individual_customer as icg', 'big.individual_customer_id', '=', 'icg.id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('setting_loyalty_km as slk', 'bp.loyalty_type_used', '=', 'slk.loyalty_type')
            ->leftjoin('booking_cancel as bc', 'b.id', '=', 'bc.booking_id')
            ->leftjoin('branch as bf', 'b.from_location', '=', 'bf.id')
            ->leftjoin('branch as bt', 'b.to_location', '=', 'bt.id')
            ->leftjoin('city as city_f', 'bf.city_id', '=', 'city_f.id')
            ->leftjoin('city as city_t', 'bt.city_id', '=', 'city_t.id')
            ->leftjoin('region as r', 'city_f.region_id', '=', 'r.id')
            ->where('b.id', $booking_id)
            ->select('b.*', 'bp.*', 'bipm.payment_method as payment_method', 'cm.id as car_model_id', 'cm.image1 as car_image', 'cm.image1_eng_alt as car_image_eng_alt', 'cm.image1_arb_alt as car_image_arb_alt', 'cm.year as year', 'cm.eng_title as car_model_eng_title', 'cm.arb_title as car_model_arb_title', 'cm.no_of_passengers as no_of_passengers', 'cm.no_of_bags as no_of_bags', 'cm.no_of_doors as no_of_doors', 'cm.transmission as transmission', 'cm.min_age as min_age', 'cm.oracle_reference_number as car_type_oracle_id', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'cc.eng_title as car_category_eng_title', 'cc.arb_title as car_category_arb_title', 'ic.first_name as first_name', 'ic.last_name as last_name', 'ic.gender as gender', 'ic.id_no as id_no', 'ic.mobile_no as mobile_no', 'ic.email as email', 'bf.phone1 as branch_mobile', 'bf.eng_title as branch_from_eng_title', 'bf.arb_title as branch_from_arb_title', 'bf.oracle_reference_number as branch_from_oracle_id', 'bt.eng_title as branch_to_eng_title', 'bt.arb_title as branch_to_arb_title', 'city_f.id as from_city_id', 'city_t.id as to_city_id', 'r.id as from_region_id', 'icg.first_name as icg_first_name', 'icg.last_name as icg_last_name', 'icg.gender as icg_gender', 'icg.id_no as icg_id_no', 'icg.mobile_no as icg_mobile_no', 'icg.email as icg_email', 'bc.cancel_time as cancel_time', 'bc.cancel_charges as cancel_charges', 'slk.km as km', 'ic.loyalty_card_type as ic_loyalty_card_type', 'icg.loyalty_card_type as icg_loyalty_card_type', 'city_f.eng_title as city_from_eng_title', 'city_f.arb_title as city_from_arb_title', 'city_t.eng_title as city_to_eng_title', 'city_t.arb_title as city_to_arb_title')
            ->first();
        return $record;
    }

    public function getSingleBookingDetailsForCorporate($booking_id, $user_type = "individual_user", $lang = 'eng')
    {
        $query = "SELECT 
                    b.*,
                    bp.*,
                    bipm.payment_method as payment_method,
                    cm.id as car_model_id,
                    cm.image1 as car_image,
                    cm.image1_eng_alt as car_image_eng_alt,
                    cm.image1_arb_alt as car_image_arb_alt,
                    cm.year as year,
                    cm.eng_title as car_model_eng_title,
                    cm.arb_title as car_model_arb_title,
                    cm.no_of_passengers as no_of_passengers,
                    cm.no_of_bags as no_of_bags,
                    cm.no_of_doors as no_of_doors,
                    cm.transmission as transmission,
                    cm.min_age as min_age,
                    cm.oracle_reference_number as car_type_oracle_id,
                    ct.eng_title as car_type_eng_title,
                    ct.arb_title as car_type_arb_title,
                    car_cat.eng_title as car_category_eng_title,
                    car_cat.arb_title as car_category_arb_title,
                    cd.first_name as first_name,
                    cd.last_name as last_name,
                    cd.gender as gender,
                    cd.id_no as id_no,
                    cd.mobile_no as mobile_no,
                    cd.email as email,
                    bf.phone1 as branch_mobile,
                    bf.eng_title as branch_from_eng_title,
                    bf.arb_title as branch_from_arb_title,
                    bf.oracle_reference_number as branch_from_oracle_id,
                    bt.eng_title as branch_to_eng_title,
                    bt.arb_title as branch_to_arb_title,
                    city_f.id as from_city_id,
                    city_t.id as to_city_id,
                    r.id as from_region_id,
                    bc.cancel_time as cancel_time,
                    bc.cancel_charges as cancel_charges,
                    slk.km as km,
                    cc.membership_level as ic_loyalty_card_type,
                    city_f.eng_title as city_from_eng_title,
                    city_f.arb_title as city_from_arb_title,
                    city_t.eng_title as city_to_eng_title,
                    city_t.arb_title as city_to_arb_title,
                    cc.primary_email as corporate_user_email,
                    cc.company_name_en,
                    cc.company_name_ar,
                    cc.company_code
                FROM booking b
                LEFT JOIN car_model cm ON b.car_model_id = cm.id
                LEFT JOIN car_type ct ON cm.car_type_id = ct.id
                LEFT JOIN car_group cg ON ct.car_group_id = cg.id
                LEFT JOIN car_category car_cat ON cg.car_category_id = car_cat.id
                LEFT JOIN booking_corporate_customer bcc ON b.id = bcc.booking_id
                LEFT JOIN corporate_driver cd ON bcc.driver_id = cd.id
                LEFT JOIN corporate_customer cc ON FIND_IN_SET(bcc.uid, cc.uid) > 0
                LEFT JOIN booking_individual_payment_method bipm ON b.id = bipm.booking_id
                LEFT JOIN booking_payment bp ON b.id = bp.booking_id
                LEFT JOIN setting_loyalty_km slk ON bp.loyalty_type_used = slk.loyalty_type
                LEFT JOIN booking_cancel bc ON b.id = bc.booking_id
                LEFT JOIN branch bf ON b.from_location = bf.id
                LEFT JOIN branch bt ON b.to_location = bt.id
                LEFT JOIN city city_f ON bf.city_id = city_f.id
                LEFT JOIN city city_t ON bt.city_id = city_t.id
                LEFT JOIN region r ON city_f.region_id = r.id
                WHERE b.id = $booking_id
                LIMIT 1";
        $record = DB::select($query);
        return $record[0];
    }

    public function getDropoffCharges($pickupdate, $pickup_cityid, $dropoff_city, $user_loyalty)
    {
        $loyalty = '';
        $value = 0;
        if ($user_loyalty == 'Bronze') {
            $loyalty = 'bronze';
            $value = 1;
        } elseif ($user_loyalty == 'Silver') {
            $loyalty = 'silver';
            $value = 1;
        } elseif ($user_loyalty == 'Golden') {
            $loyalty = 'gold';
            $value = 1;
        } elseif ($user_loyalty == 'Platinum') {
            $loyalty = 'platinum';
            $value = 1;
        }
        if ($user_loyalty != '') {
            $customQ = "SELECT * FROM `dropoff_charges` doc where doc.applies_from <= '" . $pickupdate . "' and (doc.applies_to is null or doc.applies_to >= '" . $pickupdate . "') and doc.city_from='" . $pickup_cityid . "' and doc.city_to='" . $dropoff_city . "' and doc." . $loyalty . "='" . $value . "' order by doc.applies_from desc limit 1";
        } else {
            $customQ = "SELECT * FROM `dropoff_charges` doc where doc.applies_from <= '" . $pickupdate . "' and (doc.applies_to is null or doc.applies_to >= '" . $pickupdate . "') and doc.city_from='" . $pickup_cityid . "' and doc.city_to='" . $dropoff_city . "' order by doc.applies_from desc limit 1";
        }
        //echo $customQ;exit();
        $record = DB::select("$customQ");
        return $record;
    }

    public function checkAutoPromoDiscount($car_model_id, $pickupdate, $pickup_region_id, $pickup_city_id, $pickup_branch_id, $no_of_days = '', $customer_type = "Individual", $is_delivery_mode = '')
    {
        /*$customQ = "SELECT po.* FROM promotion_offer po join setting_renting_type srt on po.renting_type_id = srt.id and srt.from_days <= '" . $no_of_days . "' and (srt.to_days >= '" . $no_of_days . "' or srt.to_days = 0) and po.car_model_id='" . $car_model_id . "' and (po.applies_from <= '" . $pickupdate . "') and (po.applies_to is null or po.applies_to >= '" . $pickupdate . "') and (po.region_id = 0 or po.region_id = '" . $pickup_region_id . "') and (po.city_id = 0 or po.city_id = '" . $pickup_city_id . "') and (po.branch_id = 0 or po.branch_id = '" . $pickup_branch_id . "') and (po.customer_type = \"\" or po.customer_type = '" . $customer_type . "') order by po.region_id desc, po.city_id desc, po.branch_id desc, po.customer_type desc, po.applies_from desc, srt.from_days desc limit 1";*/
        $customQ = "SELECT po.* FROM promotion_offer po join setting_renting_type srt on po.renting_type_id = srt.id join promotion_offer_car_model pocm ON po.id = pocm.promotion_offer_id and srt.from_days <= '" . $no_of_days . "' and (srt.to_days >= '" . $no_of_days . "' or srt.to_days = 0) and (pocm.car_model_id='" . $car_model_id . "' or pocm.car_model_id='-1') and (po.applies_from is null or po.applies_from <= '" . $pickupdate . "') and (po.applies_to is null or po.applies_to >= '" . $pickupdate . "') and (po.region_id = 0 or po.region_id = '" . $pickup_region_id . "') and (po.city_id = 0 or po.city_id = '" . $pickup_city_id . "') and (po.branch_id = 0 or po.branch_id = '" . $pickup_branch_id . "') and (po.customer_type = \"\" or po.customer_type = '" . $customer_type . "') and po.type in ('Percentage Auto Apply', 'Percentage Auto Apply on Loyalty', 'Fixed Price Auto Apply', 'Fixed Daily Rate Auto Apply') and po.active_status = 'Active' order by po.region_id desc, po.city_id desc, po.branch_id desc, po.customer_type desc, po.applies_from desc, srt.from_days desc limit 1";
        //echo $customQ;exit();
        $record = DB::select("$customQ");
        if ($record && $is_delivery_mode != 2) { // means he is not coming from hourly rent tab
            return $record[0];
        } else {
            return false;
        }

    }


    public function checkIfPromoApplicable($car_model_id, $coupon_code, $pickupdate, $pickup_region_id, $pickup_city_id, $pickup_branch_id, $booking_total, $no_of_days = '', $is_delivery_mode = '')
    {
        /*$customQ = "SELECT po.* FROM promotion_offer po join promotion_offer_coupon poc on po.id=poc.promotion_offer_id and poc.code='" . $coupon_code . "' and po.car_model_id='" . $car_model_id . "' and (po.applies_from <= '" . $pickupdate . "') and (po.applies_to is null or po.applies_to >= '" . $pickupdate . "') and (po.region_id = 0 or po.region_id = '" . $pickup_region_id . "') and (po.city_id = 0 or po.city_id = '" . $pickup_city_id . "') and (po.branch_id = 0 or po.branch_id = '" . $pickup_branch_id . "') and (po.customer_type = \"\" or po.customer_type = \"Individual\") order by po.region_id desc, po.city_id desc, po.branch_id desc, po.customer_type desc, po.applies_from desc limit 1";
        //echo $customQ;exit();
        $record = DB::select("$customQ");
        if ($record) {
            return $record[0];
        } else {
            return false;
        }*/

        $customQ = "SELECT po.* FROM promotion_offer po 
                    join promotion_offer_coupon poc on po.id=poc.promotion_offer_id 
                    join promotion_offer_car_model pocm ON po.id = pocm.promotion_offer_id 
                    join setting_renting_type srt on po.renting_type_id = srt.id 
                    and poc.code='" . $coupon_code . "' 
                    and (po.minimum_booking_days <= '" . $no_of_days . "' or po.minimum_booking_days = 0 or po.minimum_booking_days is null) 
                    and (po.maximum_booking_days >= '" . $no_of_days . "' or po.maximum_booking_days = 0 or po.maximum_booking_days is null) 
                    and (po.minimum_booking_value <= '" . $booking_total . "' or po.minimum_booking_value = 0 or po.minimum_booking_value is null) 
                    and (po.maximum_booking_value >= '" . $booking_total . "' or po.maximum_booking_value = 0 or po.maximum_booking_value is null) 
                    and (pocm.car_model_id='" . $car_model_id . "' or pocm.car_model_id='-1') 
                    and (po.applies_from is null or po.applies_from <= '" . $pickupdate . "') 
                    and (po.applies_to is null or po.applies_to >= '" . $pickupdate . "') 
                    and (po.region_id = 0 or po.region_id = '" . $pickup_region_id . "') 
                    and (po.city_id = 0 or po.city_id = '" . $pickup_city_id . "') 
                    and (po.branch_id = 0 or po.branch_id = '" . $pickup_branch_id . "') 
                    and (po.customer_type = '' or po.customer_type = 'Individual') 
                    and (
                        (po.ignore_renting_type = 1) OR 
                            (
                                po.ignore_renting_type = 0 AND srt.from_days <= '" . $no_of_days . "' and 
                                (srt.to_days >= '" . $no_of_days . "' or srt.to_days = 0)
                            )
                    )
                    and po.active_status = 'Active' 
                    order by po.region_id desc, po.city_id desc, po.branch_id desc, po.customer_type desc, po.applies_from desc, srt.from_days desc limit 1";

        // $customQ = "SELECT po.* FROM promotion_offer po join promotion_offer_coupon poc on po.id=poc.promotion_offer_id join promotion_offer_car_model pocm ON po.id = pocm.promotion_offer_id join setting_renting_type srt on po.renting_type_id = srt.id and poc.code='" . $coupon_code . "' and (po.minimum_booking_days <= '" . $no_of_days . "' or po.minimum_booking_days = 0 or po.minimum_booking_days is null) and (po.maximum_booking_days >= '" . $no_of_days . "' or po.maximum_booking_days = 0 or po.maximum_booking_days is null) and (pocm.car_model_id='" . $car_model_id . "' or pocm.car_model_id='-1') and (po.applies_from <= '" . $pickupdate . "') and (po.applies_to is null or po.applies_to >= '" . $pickupdate . "') and (po.region_id = 0 or po.region_id = '" . $pickup_region_id . "') and (po.city_id = 0 or po.city_id = '" . $pickup_city_id . "') and (po.branch_id = 0 or po.branch_id = '" . $pickup_branch_id . "') and (po.customer_type = \"\" or po.customer_type = \"Individual\") and srt.from_days <= '" . $no_of_days . "' and (srt.to_days >= '" . $no_of_days . "' or srt.to_days = 0) and po.active_status = 'Active' order by po.region_id desc, po.city_id desc, po.branch_id desc, po.customer_type desc, po.applies_from desc, srt.from_days desc limit 1";

        // echo $customQ;exit();
        $record = DB::select("$customQ");
        if ($record && $is_delivery_mode != 2) {
            return $record[0];
        } else {
            return false;
        }

    }

    /*public function getSingleIndUserInfo($get_by)
    {
        $record = DB::table('individual_customer as ic')
            ->where($get_by)
            ->leftjoin('users as u', 'ic.uid', '=', 'u.id')
            ->select('ic.*', 'u.password')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }*/


    public function getGuestBookings($individual_customer_id)
    {
        $guestBookings = DB::table('booking_individual_guest')
            ->select('*')
            ->where('individual_customer_id', $individual_customer_id)
            ->get();

        if (count($guestBookings) > 0) {
            return $guestBookings;
        } else {
            return false;
        }
    }

    public function getFilteredBookings($key_name = "", $date = "", $records_for = "current_bookings", $user_type = "individual_customer")
    {

        if ($records_for == 'history_bookings') {
            $criteriaForGettingBookings = "(b.booking_status='Completed') or
        (b.from_date <= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')";
        } else {
            $criteriaForGettingBookings = "(b.booking_status='Not Picked') or
        (b.booking_status='Picked') or
        (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')";
        }
        $selectCols = "b.*,ct.eng_title as car_type_eng_title, ct.arb_title as car_type_arb_title,
             car_cat.eng_title as car_category_eng_title, car_cat.arb_title as car_category_arb_title,
              bp.total_sum as total_sum, bp.no_of_days as no_of_days, cm.year as year, 
              cm.image1 as image1, cm.no_of_passengers as no_of_passengers, 
              cm.no_of_bags as no_of_bags, cm.no_of_doors as no_of_doors,
               cm.transmission as transmission, cm.eng_title as car_eng_title,
               cm.min_age as min_age,
               cm.arb_title as car_arb_title, bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from, bt.eng_title as branch_eng_to,bt.arb_title as branch_arb_to, 
               u.id as logged_in_uid, u2.id as logged_in_corporid, 
               ic2.id as guest_ind_cust_id, case when b.type='individual_customer' 
               then concat(ic.first_name,' ', ic.last_name)
                when b.type='corporate_customer' then cc.company_name_en when 
                b.type='guest' then concat(ic2.first_name,' ', ic2.last_name)
                 end as name, (select br.eng_title from branch br 
                 where br.id=b.from_location) as branch_eng_from";

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
left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'";


        $query .= " left join car_type ct on cm.car_type_id = ct.id left join car_group cg on ct.car_group_id = cg.id left join car_category car_cat on cg.car_category_id = car_cat.id left join booking_payment bp on b.id = bp.booking_id left join users u on biu.uid=u.id left join individual_customer ic on u.id=ic.uid
left join booking_corporate_customer bcc on b.id=bcc.booking_id and b.type='corporate_customer' left join users u2 on bcc.uid=u2.id left join corporate_customer cc on FIND_IN_SET(u2.id, cc.uid) > 0
left join booking_individual_guest big on b.id=big.booking_id and b.type='guest' left join individual_customer ic2 on big.individual_customer_id=ic2.id where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        if ($key_name != "") {
            $query .= " and ( b.booking_status LIKE '%" . $key_name . "%' or cm.eng_title LIKE '%" . $key_name . "%' or cm.arb_title LIKE '%" . $key_name . "%' or ct.eng_title LIKE '%" . $key_name . "%' or ct.arb_title LIKE '%" . $key_name . "%' or b.reservation_code LIKE '%" . $key_name . "%' or bf.eng_title LIKE '%" . $key_name . "%' or bf.arb_title LIKE '%" . $key_name . "%' or bt.eng_title LIKE '%" . $key_name . "%' or bt.arb_title LIKE '%" . $key_name . "%' or car_cat.eng_title LIKE '%" . $key_name . "%' or car_cat.arb_title LIKE '%" . $key_name . "%' )";
        }

        if ($date != "") {
            $query .= " and (DATE(b.from_date) <= '" . $date . "' and DATE(b.to_date) >= '" . $date . "')";

        }

        if ($user_type == "corporate_customer") {
            $query .= " and bcc.uid = " . Session::get('user_id') . " ";
        } else {
            $query .= " and biu.uid = " . Session::get('user_id') . " ";
        }

        $query .= "ORDER BY b.id";
        //.$sort_by." ".$limit." ";

        //echo $query;exit();
        $bookings = DB::select($query);

        return $bookings;
    }

    public function getOneColumnGroupBy($tbl, $column, $id = 'id', $order = 'Desc')
    {
        $years = DB::table($tbl)->select($column)->groupBy($column)->get();
        return $years;
    }

    public function getSingleBooking($booking_code)
    {
        $record = DB::table('booking as b')
            ->leftjoin('car_model as cm', 'b.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'cm.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', '=', 'cc.id')
            ->leftjoin('booking_individual_user as biu', 'b.id', '=', 'biu.booking_id')
            ->leftjoin('individual_customer as ic', 'biu.uid', '=', 'ic.uid')
            ->leftjoin('booking_individual_guest as big', 'b.id', '=', 'big.booking_id')
            ->leftjoin('individual_customer as icg', 'big.individual_customer_id', '=', 'icg.id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('branch as bf', 'b.from_location', '=', 'bf.id')
            ->leftjoin('branch as bt', 'b.to_location', '=', 'bt.id')
            ->leftjoin('city as city_f', 'bf.city_id', '=', 'city_f.id')
            ->leftjoin('city as city_t', 'bt.city_id', '=', 'city_t.id')
            ->leftjoin('region as r', 'city_f.region_id', '=', 'r.id')
            ->where('b.reservation_code', $booking_code)
            ->select('b.*', 'bp.*', 'bipm.payment_method as payment_method', 'cm.id as car_model_id', 'cm.image1 as car_image', 'cm.image1_eng_alt as car_image_eng_alt','cm.image1_arb_alt as car_image_arb_alt', 'cm.year as year', 'cm.eng_title as car_model_eng_title', 'cm.arb_title as car_model_arb_title', 'cm.no_of_passengers as no_of_passengers', 'cm.no_of_bags as no_of_bags', 'cm.no_of_doors as no_of_doors', 'cm.transmission as transmission', 'cm.min_age as min_age', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'cc.eng_title as car_category_eng_title', 'cc.arb_title as car_category_arb_title', 'ic.first_name as first_name', 'ic.last_name as last_name', 'ic.id_no as id_no', 'ic.mobile_no as mobile_no', 'ic.email as email', 'bf.mobile as branch_mobile', 'bf.eng_title as branch_from_eng_title', 'bf.arb_title as branch_from_arb_title', 'bt.eng_title as branch_to_eng_title', 'bt.arb_title as branch_to_arb_title', 'city_f.id as from_city_id', 'city_t.id as to_city_id', 'r.id as from_region_id', 'icg.first_name as icg_first_name', 'icg.last_name as icg_last_name', 'icg.id_no as icg_id_no', 'icg.mobile_no as icg_mobile_no', 'icg.email as icg_email')
            ->first();
        return $record;
    }

    public function getLoyaltyInfo($days, $type, $customer_type = 'individual_customer')
    {
        $query = "SELECT * FROM setting_loyalty_cards as slc JOIN setting_renting_type as srt on slc.renting_type_id = srt.id and srt.from_days <= '" . $days . "' and (srt.to_days >= '" . $days . "' or srt.to_days = 0) and slc.loyalty_type='" . $type . "' AND srt.from_days <= $days AND (srt.to_days >= $days OR srt.to_days=0) AND slc.customer_type='" . $customer_type . "' AND slc.active_status=1";
        /*echo $query; exit;*/
        $record = DB::select($query);
        if ($record) {
            return $record[0];
        } else {
            return false;
        }
    }

    public function checkIfBranchIsOpen($branch_id, $day, $time, $date = "", $is_delivery_mode = false)
    {

        // working here to check general timings for delivery branches
        $site_settings = custom::site_settings();
        $branch_details = DB::table('branch')->where('id', $branch_id)->first();
        if ($site_settings->general_timings_for_delivery_branches == 1 && $is_delivery_mode) {
            $query = "SELECT * FROM branch_general_timings_for_delivery_branches WHERE day='" . $day . "' AND closed_day='No' AND 
            ( 
		
                (opening_time='00:00:00' AND closing_time='00:00:00') 
                
                or (opening_time<='" . $time . "' AND closing_time>='" . $time . "')
        
                or (
                
                        CASE 
                            WHEN (sec_shift='yes')            
                            THEN sec_shift_opening_time<='" . $time . "'  AND sec_shift_closing_time >='" . $time . "'          
                        END
                 
                )
                
                or (
                        CASE 
                            WHEN (third_shift='yes')            
                            THEN third_shift_opening_time<='" . $time . "'  AND third_shift_closing_time >='" . $time . "'          
                        END
                     
                    )
            
            )   ";

            $record = DB::select($query);

            if ($record) {
                return $record;
            } else {
                return false;
            }
        }

        /*
         * $query = "SELECT * FROM ".$table." WHERE branch_id='" . $branch_id . "' AND day='" . $day . "' AND closed_day='No' AND (

		(opening_time<='" . $time . "' AND closing_time>='" . $time . "')

		or (

        CASE
            WHEN (sec_shift_closing_time>'00:00:00' and sec_shift_closing_time<'6:00:00'  AND       sec_shift_closing_time>='" . $time . "')

            THEN TIME_TO_SEC(sec_shift_opening_time) <= TIME_TO_SEC('" . $time . "') OR  TIME_TO_SEC('" . $time . "') <= TIME_TO_SEC(sec_shift_closing_time)

            ELSE sec_shift_opening_time<='" . $time . "'
        END

        AND

        CASE
            WHEN sec_shift_closing_time>'00:00:00' and sec_shift_closing_time<'6:00:00'
            THEN TIME_TO_SEC(sec_shift_closing_time)+86400 >= TIME_TO_SEC('" . $time . "')

            ELSE sec_shift_closing_time >='" . $time . "'
        END

		)

		or (opening_time='00:00:00' AND closing_time='00:00:00')

		)   ";
        */
        $getDate = date('Y-m-d', strtotime($date));
        $checkDateRange = false;
        $isRangeOn = false;
        $table = "branch_schedule";

        $query1 = "SELECT * FROM setting_site_settings ";
        $siteSettings = DB::select($query1);
        $isRangeOn = $siteSettings[0]->date_range_mode == 'on' ? true : false;
        $startRange = $siteSettings[0]->start_date;
        $endRange = $siteSettings[0]->end_date;

        if ($isRangeOn && $getDate >= $startRange && $getDate <= $endRange) {
            $checkDateRange = true;
            $table = "branch_schedule_date_range";
        }
        $time .= ":00";
        //echo $time; exit;
        //$query = "SELECT * FROM `branch_schedule` WHERE branch_id=$branch_id AND day='".$day."' AND closed_day='No' AND ( (opening_time<='".$time."' AND closing_time>='".$time."') or (opening_time='00:00:00' AND closing_time='00:00:00') )   "

        $query = "SELECT * FROM " . $table . " WHERE branch_id='" . $branch_id . "' AND day='" . $day . "' AND closed_day='No' AND ( 
		
		(opening_time<='" . $time . "' AND closing_time>='" . $time . "')

		or (
		
        CASE 
            WHEN (sec_shift='yes')            
            THEN sec_shift_opening_time<='" . $time . "'  AND sec_shift_closing_time >='" . $time . "'          
        END
         
		) ";


        if ($checkDateRange) {
            $query .= " or (
            CASE 
                WHEN (third_shift='yes')            
                THEN third_shift_opening_time<='" . $time . "'  AND third_shift_closing_time >='" . $time . "'          
            END
             
            ) ";

        }

        $query .= " or (opening_time='00:00:00' AND closing_time='00:00:00') 
		
		)   ";

        /*echo $query;
        exit();*/
        $record = DB::select($query);

        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function checkLoginUserByIdNo($id_no, $password, $type = '')
    {
        $record = DB::table('users as u')
            ->leftjoin('individual_customer as ic', 'u.id', '=', 'ic.uid')
            ->where('ic.id_no', $id_no)
            ->where('u.password', $password);
        if ($type != '') {
            $record->where('u.type', $type);
        }
        $record->select('u.*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function validate_user($username, $password)
    {
        $query = "SELECT u.* FROM users u LEFT JOIN individual_customer ic on u.id=ic.uid LEFT JOIN corporate_customer cc on FIND_IN_SET(u.id, cc.uid) > 0 WHERE u.type != 'admin' and (u.email='$username' or ic.id_no = '$username' or cc.company_code = '$username') and u.password = '$password'";
        $record = DB::select($query);
        if ($record) {
            return $record[0];
        } else {
            return false;
        }
    }


    public function checkUser($id_no)
    {
        $record = DB::table('individual_customer')
            ->where('id_no', $id_no)
            ->where('uid', '>', '0')
            ->select('*')
            ->first();
        return $record;
    }

    public function getAllCarModelsPassengers()
    {
        $record = DB::table('car_model')
            ->select('no_of_passengers')
            ->groupBy('no_of_passengers')
            ->get();
        return $record;
    }

    public function getCarTypesByMake($data)
    {
        $records = DB::table('car_type')
            ->where('eng_title', $data)
            ->orWhere('arb_title', $data)
            ->select('*')
            ->get();
        if ($records) {
            return $records;
        } else {
            return array();
        }
    }

    public function getCarModelsByTitle($data)
    {
        $records = DB::table('car_model')
            ->where('eng_title', $data)
            ->orWhere('arb_title', $data)
            ->select('*')
            ->get();
        if ($records) {
            return $records;
        } else {
            return array();
        }
    }

    public function getCitiesByTitle($data)
    {
        $records = DB::table('city')
            ->where('eng_title', $data)
            ->orWhere('arb_title', $data)
            ->select('*')
            ->get();
        if ($records) {
            return $records;
        } else {
            return array();
        }
    }

    public function getBranchesByTitle($data)
    {
        $records = DB::table('branch')
            ->where('eng_title', $data)
            ->orWhere('arb_title', $data)
            ->select('*')
            ->get();
        if ($records) {
            return $records;
        } else {
            return array();
        }
    }

    public function getUserBookingsCount($user_id)
    {
        $records = DB::table('booking_individual_user as biu')
            ->leftjoin('booking_individual_payment_method as bipm', 'biu.booking_id', '=', 'bipm.booking_id')
            ->leftjoin('booking_cc_payment as bccp', 'biu.booking_id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'biu.booking_id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'biu.booking_id', '=', 'bsp.s_booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' OR bipm.payment_method='Corporate Credit')")
            ->where('biu.uid', $user_id)
            ->select('*')
            ->count();
        return $records;
    }

    public function getCorporateUserBookingsCount($user_id)
    {
        $records = DB::table('booking_corporate_customer as bcc')
            ->leftjoin('booking_individual_payment_method as bipm', 'bcc.booking_id', '=', 'bipm.booking_id')
            ->leftjoin('booking_cc_payment as bccp', 'bcc.booking_id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'bcc.booking_id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'bcc.booking_id', '=', 'bsp.s_booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->where('bcc.uid', $user_id)
            ->select('*')
            ->count();
        return $records;
    }

    public function getSurveyData()
    {
        $data = DB::table('survey_emoji as se')
            ->leftjoin('survey_category as sc', 'se.id', '=', 'sc.emoji_id')
            ->leftjoin('survey_category_options as sco', 'sc.id', '=', 'sco.category_id')
            ->select('se.eng_title as eng_emoji', 'se.arb_title as arb_emoji', 'sc.eng_title as eng_category', 'sc.arb_title as arb_category', 'sc.eng_question as eng_question', 'sc.arb_question as arb_question', 'sc.is_other_type as is_other_type', 'sco.eng_title as eng_option', 'sco.arb_title as arb_option', 'sco.value as option_value')
            ->get();
        return $data;
    }

    public function getDriverInfo($get_driver_by)
    {
        $record = DB::table('corporate_driver')
            ->where('id_no', 'like', '%' . $get_driver_by . '%')
            ->orWhere('email', 'like', '%' . $get_driver_by . '%')
            ->orWhere('mobile_no', 'like', '%' . $get_driver_by . '%')
            ->select('*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function getCountOfBookingsInTimeInterval($branch_id, $before_time, $after_time)
    {
        $records_count = DB::table('booking as b')
            ->leftjoin('booking_cc_payment as bccp', 'b.id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->where('is_delivery_mode', 'yes')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->where('b.from_location', $branch_id)
            ->where('b.from_date', '>=', $before_time)
            ->where('b.from_date', '<=', $after_time)
            ->select('*')
            ->count();
        return $records_count;
    }

    public function checkIfConflictDataExist($data)
    {
        $query = "SELECT * FROM car_price cp 
        WHERE
        (
        (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
            OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

        if (isset($data['applies_to']) && $data['applies_to'] != "" && $data['applies_to'] != null)
            $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

        $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
        )        
        and cp.car_model_id='" . $data['car_model_id'] . "' and charge_element='" . $data['charge_element'] . "' 
        and cp.renting_type_id='" . $data['renting_type_id'] . "'";

        if (isset($data['region_id']) && $data['region_id'] != "") {
            $query .= " and cp.region_id='" . $data['region_id'] . "'";
        } else {
            $query .= " and cp.region_id=''";
        }

        if (isset($data['city_id']) && $data['city_id'] != "") {
            $query .= " and cp.city_id='" . $data['city_id'] . "'";
        } else {
            $query .= " and cp.city_id=''";
        }

        if (isset($data['branch_id']) && $data['branch_id'] != "") {
            $query .= " and cp.branch_id='" . $data['branch_id'] . "'";
        } else {
            $query .= " and cp.branch_id=''";
        }

        if (isset($data['customer_type']) && $data['customer_type'] != "") {
            $query .= " and cp.customer_type='" . $data['customer_type'] . "'";
        } else {
            $query .= " and cp.customer_type=''";
        }

        $records = DB::select($query);

        return $records;
    }

    public function getCarsToSell($car_brand_id = "", $car_year = "", $offset = 0, $limit = 99999, $lang = "eng")
    {
        if ($lang == 'arb') {
            $orderBy1 = 'csb.arb_title';
            $orderBy2 = 'csm.arb_title';
        } else {
            $orderBy1 = 'csb.eng_title';
            $orderBy2 = 'csm.eng_title';
        }

        $query = DB::table('car_selling_model as csm')
            ->leftjoin('car_selling_brand as csb', 'csm.car_brand_id', '=', 'csb.id');
        if ($car_brand_id != "") {
            $query->where('csm.car_brand_id', $car_brand_id);
        }
        if ($car_year != "") {
            $query->where('csm.year', $car_year);
        }
        $query->where('csm.active_status', 1)
            ->select('csm.*', 'csb.eng_title as eng_brand_title', 'csb.arb_title as arb_brand_title')
            ->orderBy($orderBy1, 'asc')
            ->orderBy($orderBy2, 'asc');

        $query->offset($offset);
        $query->limit($limit);

        $records = $query->get();
        return $records;
    }

    public function getYearsOfCarsToSell()
    {
        $record = DB::table('car_selling_model')
            ->select('year')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();
        return $record;
    }

    public function getNoOfOpenContracts($car_id, $region_id)
    {
        $query = "select count(*) as no_of_open_contracts from `booking` where `car_model_id` = $car_id and (`booking_status` = 'Not Picked' or `booking_status` = 'Picked') and `from_location` in (SELECT id from branch  WHERE city_id in (SELECT id from city WHERE region_id = '" . $region_id . "'))";
        $models = DB::select($query);
        return $models[0]->no_of_open_contracts;
    }

    public function checkIfRedeemAllowed($region_id, $car_type_id, $car_model_id, $pickup_date)
    {
        $pickup_date = date('Y-m-d', strtotime($pickup_date)); // converting date ftom 22-02-2017 TO 2017-02-22 Format
        $query = "SELECT * FROM redeem_setup WHERE region_id = $region_id AND car_type_id = $car_type_id AND car_model_id = $car_model_id AND `active_status` = 'active' AND applies_from <= '$pickup_date' AND (applies_to IS NULL OR applies_to >= '$pickup_date') ORDER BY car_model_id DESC LIMIT 1";
        //echo $query;exit();
        $models = DB::select($query);
        if (count($models) > 0) {
            return $models[0];
        } else {
            return false;
        }

    }

    public function getAllCarCategories()
    {
        $records = DB::table('car_category')
            ->select('id', 'eng_title', 'arb_title', 'sort_col')
            ->orderBy('id', 'asc')
            ->get()->toArray();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getAllPendingRedeemRequests()
    {
        $query = DB::table('qitaf_logs')
                    ->select('*')
                    ->where('status', 'New')
                    ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime("-1 hour")));
        $records = $query->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getAllPendingNiqatyRedeemRequests()
    {
        $query = DB::table('niqaty_logs')
            ->select('*')
            ->where('status', 'New')
            ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime("-30 minutes")));
        $records = $query->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function get_all_pending_mokafaa_redeem_requests()
    {
        $query = DB::table('mokafaa_logs')
            ->select('*')
            ->where('status', 'New')
            ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime("-1 hour")));
        $records = $query->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function get_all_pending_anb_redeem_requests()
    {
        $query = DB::table('anb_logs')
            ->select('*')
            ->where('status', 'New')
            ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime("-1 hour")));
        $records = $query->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function get_nearest_branch($lat, $long, $isLimousine = 0, $branch_id = false)
    {
        $record = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            ->where('branch.is_delivery_branch', '=', 'yes');
        if ($isLimousine == 1) {
            $record = $record->where('branch.is_for_limousine_mode_only', '=', 'yes');
        } else {
            $record = $record->where('branch.is_for_limousine_mode_only', '=', 'no');
        }
        if ($branch_id) {
            $record = $record->where('branch.id', '=', $branch_id);
        }
            $record = $record->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name', 'branch.phone1 as phone1', DB::raw('SUBSTRING_INDEX(branch.phone2 , \'-\', 1 ) AS phone2'), 'branch.address_line_1 as eng_address', 'branch.address_line_2 as arb_address', 'branch.opening_hours as branch_opening_hours', 'branch.opening_hours_arb as branch_opening_hours_arb', DB::raw('0+SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ) AS latitude'), DB::raw('0+SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1) AS longitude'), DB::raw('ROUND(( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1)) - radians(' . $long . ')) + sin(radians(' . $lat . ')) 
   * sin( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ))))), 2) AS distance'), 'branch.is_delivery_branch as is_delivery_branch', 'branch.delivery_charges as branch_delivery_charges');
        if ($branch_id) {

        }
            $record = $record->orderBy('distance', 'asc')->first();
        return $record;
    }


}