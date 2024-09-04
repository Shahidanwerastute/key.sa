<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;
use App\Helpers\Custom;

class Services extends Model
{

    public function getAllBranches($lat, $long, $city_id = false)
    {

        $records = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'no')
            ->where('branch.is_for_limousine_mode_only', '=', 'no')
            ->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name', 'branch.phone1 as phone1', DB::raw('SUBSTRING_INDEX(branch.phone2 , \'-\', 1 ) AS phone2'), 'branch.address_line_1 as eng_address', 'branch.address_line_2 as arb_address', 'branch.opening_hours as branch_opening_hours', 'branch.opening_hours_arb as branch_opening_hours_arb', DB::raw('0+SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ) AS latitude'), DB::raw('0+SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1) AS longitude'), DB::raw('ROUND(( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1)) - radians(' . $long . ')) + sin(radians(' . $lat . ')) 
   * sin( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ))))), 2) AS distance'), 'branch.is_delivery_branch as is_delivery_branch', 'branch.delivery_charges as branch_delivery_charges', 'branch.is_airport as is_airport');
        if ($city_id) {
            $records = $records->where('branch.city_id', $city_id);
        }
        $records = $records->orderBy('distance', 'asc')->get();
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }

    public function getAllDeliveryBranches($lat, $long, $city_id = false)
    {
        $records = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'yes')
            ->where('branch.is_for_limousine_mode_only', '=', 'no')
            ->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name', 'branch.phone1 as phone1', DB::raw('SUBSTRING_INDEX(branch.phone2 , \'-\', 1 ) AS phone2'), 'branch.address_line_1 as eng_address', 'branch.address_line_2 as arb_address', 'branch.opening_hours as branch_opening_hours', 'branch.opening_hours_arb as branch_opening_hours_arb', DB::raw('0+SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ) AS latitude'), DB::raw('0+SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1) AS longitude'), DB::raw('ROUND(( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1)) - radians(' . $long . ')) + sin(radians(' . $lat . ')) 
   * sin( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ))))), 2) AS distance'), 'branch.is_delivery_branch as is_delivery_branch', 'branch.delivery_charges as branch_delivery_charges', 'branch.is_airport as is_airport');
            if ($city_id) {
                $records = $records->where('branch.city_id', $city_id);
            }
        $records = $records->orderBy('distance', 'asc')->get();
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }


    public function getAllBranchesForMap($lat, $long)
    {

        $records = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'no')
            ->where('branch.is_for_limousine_mode_only', '=', 'no')
            ->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name', 'branch.phone1 as phone1', DB::raw('SUBSTRING_INDEX(branch.phone2 , \'-\', 1 ) AS phone2'), 'branch.address_line_1 as eng_address', 'branch.address_line_2 as arb_address', 'branch.opening_hours as branch_opening_hours', 'branch.opening_hours_arb as branch_opening_hours_arb', DB::raw('0+SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ) AS latitude'), DB::raw('0+SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1) AS longitude'), DB::raw('ROUND(( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1)) - radians(' . $long . ')) + sin(radians(' . $lat . ')) 
   * sin( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ))))), 2) AS distance'), 'branch.is_delivery_branch as is_delivery_branch', 'branch.delivery_charges as branch_delivery_charges', 'branch.is_airport as is_airport')
            ->orderBy('distance', 'asc')->get();
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }

    public function getBranchesBySearch($keyword, $lat, $long)
    {

        $query = "SELECT * FROM (
select b.id as branch_id, c.id as city_id, r.id as region_id, b.eng_title as eng_branch_name, b.arb_title as arb_branch_name, c.eng_title as eng_city_name, c.arb_title as arb_city_name, b.phone1 as phone1, SUBSTRING_INDEX(b.phone2 , '-', 1 ) AS phone2, b.address_line_1 as eng_address, b.address_line_2 as arb_address, b.opening_hours as branch_opening_hours, b.opening_hours_arb as branch_opening_hours_arb, 0+SUBSTRING_INDEX(b.map_latlng , ',', 1 ) AS latitude, 0+SUBSTRING_INDEX(SUBSTRING_INDEX( b.map_latlng , ',', 2 ),',',-1) AS longitude, ROUND(( 6371 * acos( cos( radians($lat) ) * cos( radians(SUBSTRING_INDEX(b.map_latlng , ',', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( b.map_latlng , ',', 2 ),',',-1)) - radians($long)) + sin(radians($lat)) 
   * sin( radians(SUBSTRING_INDEX(b.map_latlng , ',', 1 ))))), 2) AS distance FROM branch b JOIN city c ON b.city_id=c.id JOIN region r ON c.region_id=r.id and (b.eng_title LIKE \"%$keyword%\" OR b.arb_title LIKE \"%$keyword%\" OR c.eng_title LIKE \"%$keyword%\" OR c.arb_title LIKE \"%$keyword%\") AND b.active_status = '1' AND b.is_delivery_branch = 'no' and b.is_for_limousine_mode_only = 'no'
   
UNION

select b2.id as branch_id, c2.id as city_id, r2.id as region_id, b2.eng_title as eng_branch_name, b2.arb_title as arb_branch_name, c2.eng_title as eng_city_name, c2.arb_title as arb_city_name, b2.phone1 as phone1, SUBSTRING_INDEX(b2.phone2 , '-', 1 ) AS phone2, b2.address_line_1 as eng_address, b2.address_line_2 as arb_address, b2.opening_hours as branch_opening_hours, b2.opening_hours_arb as branch_opening_hours_arb, 0+SUBSTRING_INDEX(b2.map_latlng , ',', 1 ) AS latitude, 0+SUBSTRING_INDEX(SUBSTRING_INDEX( b2.map_latlng , ',', 2 ),',',-1) AS longitude, ROUND(( 6371 * acos( cos( radians($lat) ) * cos( radians(SUBSTRING_INDEX(b2.map_latlng , ',', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( b2.map_latlng , ',', 2 ),',',-1)) - radians($long)) + sin(radians($lat)) 
   * sin( radians(SUBSTRING_INDEX(b2.map_latlng , ',', 1 ))))), 2) AS distance FROM branch b2 JOIN city c2 ON b2.city_id=c2.id JOIN region r2 ON c2.region_id=r2.id JOIN car_availability ca ON b2.id=ca.branch_id JOIN car_model cm ON ca.car_model_id=cm.id JOIN car_type ct ON cm.car_type_id=ct.id and (cm.eng_title LIKE \"%$keyword%\" OR cm.arb_title LIKE \"%$keyword%\" OR ct.eng_title LIKE \"%$keyword%\" OR ct.arb_title LIKE \"%$keyword%\" OR CONCAT (ct.eng_title, ' ', cm.eng_title) LIKE \"%$keyword%\" OR CONCAT (ct.arb_title, ' ', cm.arb_title) LIKE \"%$keyword%\" OR CONCAT (ct.eng_title, cm.eng_title) LIKE \"%$keyword%\" OR CONCAT (ct.arb_title, cm.arb_title) LIKE \"%$keyword%\") AND b2.active_status = '1' AND b2.is_delivery_branch = 'no' and b.is_for_limousine_mode_only = 'no'
   ) filtered_branches ORDER BY distance ASC";

        //echo $query;exit();
        $records = DB::select($query);
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }

    public function getSavedBranches($lat, $long, $savedBranches)
    {

        $records = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            // ->where('branch.is_delivery_branch', '=', 'no')
            ->whereIn('branch.id', $savedBranches)
            //->whereIn('branch.id', [1, 2, 3])
            ->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name', 'branch.phone1 as phone1', DB::raw('SUBSTRING_INDEX(branch.phone2 , \'-\', 1 ) AS phone2'), 'branch.address_line_1 as eng_address', 'branch.address_line_2 as arb_address', 'branch.opening_hours as branch_opening_hours', 'branch.opening_hours_arb as branch_opening_hours_arb', DB::raw('0+SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ) AS latitude'), DB::raw('0+SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1) AS longitude'), DB::raw('ROUND(( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 )) ) * cos( radians(SUBSTRING_INDEX(SUBSTRING_INDEX( branch.map_latlng , \',\', 2 ),\',\',-1)) - radians(' . $long . ')) + sin(radians(' . $lat . ')) 
   * sin( radians(SUBSTRING_INDEX(branch.map_latlng , \',\', 1 ))))), 2) AS distance'))
            ->orderBy('distance', 'asc')->get();
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }

    public function getAllCarModels($search_by, $type, $modelId = "", $offset = 0, $limit = "", $loyalty_discount_percent = 0, $car_price_sort = '', $car_ids_already_shown = false)
    {
        $image_path = custom::baseurl('/') . '/public/uploads/';
        $query = "SELECT cm.id as car_id,cm.car_type_id,booking_days_limit,
       cm.eng_title AS eng_car_model,
       cm.arb_title AS arb_car_model,
       cm.year AS model_year,
       cm.is_for_disabled AS is_for_disabled,
       CASE
       WHEN cm.transmission = 'Auto' THEN 'A'
       WHEN cm.transmission = 'Manual' THEN 'M'
       END AS transmission_type,
       cm.transmission as transmission,
       cm.no_of_bags AS no_of_bags,
       cm.no_of_passengers AS no_of_passengers,
       cm.no_of_doors AS no_of_doors,
       cm.min_age AS min_age,
       cm.eng_description AS eng_car_description,
       cm.arb_description AS arb_car_description,
       cm.is_special_car AS is_special_car,
       cm.eng_special_car_desc AS eng_special_car_desc,
       cm.arb_special_car_desc AS arb_special_car_desc,
       CASE
       WHEN '" . $loyalty_discount_percent . "' = 0 THEN '0'
       WHEN '" . $loyalty_discount_percent . "' != 0 THEN $loyalty_discount_percent
       END AS loyalty_discount_percentage,
       CONCAT ('$image_path', cm.image1) AS image_path,
       cm.image1 as car_image,
       cpt.id AS cpid,
       cpt.charge_element,
       cpt.is_one_time_applicable_on_booking,
       CASE
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '2' THEN ROUND(cpt.two_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '3' THEN ROUND(cpt.three_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '4' THEN ROUND(cpt.four_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '5' THEN ROUND(cpt.five_hourly_price,2)
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '3' THEN (cpt.three_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '6' THEN (cpt.six_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '9' THEN (cpt.nine_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '12' THEN (cpt.twelve_month_subscription_price / 30)
       
       WHEN '" . $loyalty_discount_percent . "' = 0 THEN ROUND(cpt.price,2)
       WHEN '" . $loyalty_discount_percent . "' != 0 THEN ROUND(cpt.price-((cpt.price*'" . $loyalty_discount_percent . "')/100),2)
       
       END as discounted_price,
       
       cpt.renting_type_id,
       ROUND(cpt.three_month_subscription_price) as three_month_subscription_price,
       ROUND(cpt.six_month_subscription_price) as six_month_subscription_price,
       ROUND(cpt.nine_month_subscription_price) as nine_month_subscription_price,
       ROUND(cpt.twelve_month_subscription_price) as twelve_month_subscription_price,
       
       CASE
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '2' THEN ROUND(cpt.two_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '3' THEN ROUND(cpt.three_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '4' THEN ROUND(cpt.four_hourly_price,2)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '2' AND '".$search_by['book_for_hours']."' = '5' THEN ROUND(cpt.five_hourly_price,2)
       
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '3' THEN (cpt.three_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '6' THEN (cpt.six_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '9' THEN (cpt.nine_month_subscription_price / 30)
       WHEN '" . $search_by['is_delivery_mode'] . "' = '4' AND '".$search_by['subscribe_for_months']."' = '12' THEN (cpt.twelve_month_subscription_price / 30)
       
       WHEN '" . $search_by['is_delivery_mode'] . "' NOT IN ('2', '4') THEN ROUND(cpt.price,2)
       
       END as actual_price,
       
       ct.eng_title AS eng_car_type,
       ct.arb_title AS arb_car_type,
       cg.eng_title AS eng_car_group,
       cg.arb_title AS arb_car_group,
       cc.eng_title AS eng_car_category,
       cc.arb_title AS arb_car_category,
       '".$search_by['days']."' * 1 as no_of_days
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

        if ($search_by['is_delivery_mode'] == 2)
        {
            $query .= " and t1.hourly_rate = 'Yes' ";
        }

        if ($search_by['is_delivery_mode'] == 4)
        {
            $query .= " and t1.subscription_rate = 'Yes' ";
        }

        $query .= " and t1.is_for_limousine_mode_only = 'no' ";

        if ($search_by['customer_type'] == 'Individual')
            $query .= " and ca.is_indi_avail = 1 ";
        if ($search_by['customer_type'] == 'Corporate')
            $query .= " and ca.is_corp_avail = 1 ";

        $query .= " WHERE rt.from_days <= '" . $search_by['days'] . "'";

        if ($type == 'rent') {
            $query .= " AND charge_element ='Rent'";
        } else {
            $query .= " AND charge_element !='Rent'";
        }

        if (isset($search_by['car_model']) && $search_by['car_model'] != '')
        {
            $query .= " AND t1.car_model_id=" . $search_by['car_model'];
        }

        $query .= " AND (t1.applies_from <= '" . $search_by['pickup_date'] . "')
        AND (t1.applies_to IS NULL
             OR t1.applies_to >= '" . $search_by['pickup_date'] . "')";

        $query .= " AND (t1.customer_type = \"\"
             OR t1.customer_type = '" . $search_by['customer_type'] . "')";

        /*here is company code for corporate customers*/
        if(isset($search_by['company_code']))
        {
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

        // kashif work
        if ($modelId != "") {
            $query .= " AND cm.id = $modelId";
        }

        if ($car_ids_already_shown) {
            // $query .= " AND cm.id NOT IN (".$car_ids_already_shown.")";
        }

        //$query .= " ORDER BY cm.year desc, cc.sort_col asc";
        if ($car_price_sort != '')
        {
             $query .= " ORDER BY cpt.price $car_price_sort";
            //$query .= " ORDER BY cm.id $car_price_sort";
        }else{
            $query .= " ORDER BY cm.sort_col asc";
        }


        if ($limit) {
            $query .= " LIMIT $limit OFFSET $offset";
        }

        // echo $query; exit();
        $models = DB::select("$query");

        if (count($models) > 0) {
            return $models;
        } else {
            return false;
        }

    }

    public function getUserInfo($customer_id)
    {
        $upload_path = custom::baseurl('public/uploads');
        $front_imgs_path = custom::baseurl('public/frontend/images');

        $loyalty_card_url = "CASE
                                WHEN ic.loyalty_card_type='' THEN CONCAT('$front_imgs_path','/','bronze_card_img.png')
                                WHEN ic.loyalty_card_type='Bronze' THEN CONCAT('$front_imgs_path','/','bronze_card_img.png')
                                WHEN ic.loyalty_card_type='Silver' THEN CONCAT('$front_imgs_path','/','silver_card_img.png')
                                WHEN ic.loyalty_card_type='Golden' THEN CONCAT('$front_imgs_path','/','golden_card_img.png')
                                WHEN ic.loyalty_card_type='Platinum' THEN CONCAT('$front_imgs_path','/','platinum_card_img.png')
                             END as loyalty_card_url";

        $record = DB::table('individual_customer as ic')
            ->leftjoin('nationalities as n', 'ic.nationality', '=', 'n.oracle_reference_number')
            ->leftjoin('customer_id_types as cidt', 'ic.id_type', '=', 'cidt.ref_id')
            ->leftjoin('driving_license_id_types as dlit', 'ic.license_id_type', '=', 'dlit.ref_id')
            ->leftjoin('job_title as jt', 'ic.job_title', '=', 'jt.oracle_reference_number')
            ->where('ic.id', $customer_id)
            ->select('ic.*',DB::raw('CONCAT("'.$upload_path.'","/",ic.id_image) as id_image'), DB::raw('CONCAT("'.$upload_path.'","/",ic.license_image) as license_image'), DB::raw($loyalty_card_url), 'n.eng_country_name as eng_nationality', 'n.arb_country_name as arb_nationality', 'cidt.eng_title as eng_id_title', 'cidt.arb_title as arb_id_title', 'dlit.eng_title as eng_license_title', 'dlit.arb_title as arb_license_title', 'jt.eng_title as eng_job_title', 'jt.arb_title as arb_job_title')
            ->first();
        if ($record)
        {
            return $record;
        }else{
            return false;
        }
    }

    public function getCorporateUserInfo($customer_id)
    {
        $upload_path = custom::baseurl('public/uploads');
        $front_imgs_path = custom::baseurl('public/frontend/images');

        $loyalty_card_url = "CASE
                                WHEN cc.membership_level='' THEN CONCAT('$front_imgs_path','/','bronze_card_img.png')
                                WHEN cc.membership_level='Bronze' THEN CONCAT('$front_imgs_path','/','bronze_card_img.png')
                                WHEN cc.membership_level='Silver' THEN CONCAT('$front_imgs_path','/','silver_card_img.png')
                                WHEN cc.membership_level='Golden' THEN CONCAT('$front_imgs_path','/','golden_card_img.png')
                                WHEN cc.membership_level='Platinum' THEN CONCAT('$front_imgs_path','/','platinum_card_img.png')
                             END as loyalty_card_url";

        $record = DB::table('corporate_customer as cc')
            ->where('cc.id', $customer_id)
            ->select('cc.*',DB::raw($loyalty_card_url))
            ->first();
        if ($record)
        {
            return $record;
        }else{
            return false;
        }
    }

    public function getSingleBooking($booking_code)
    {
        $this->sendEmail("getSingleBooking called in services model at line no. 358");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        $select_color = "CASE
                            WHEN b.booking_status='Not Picked' THEN 'blue'
                            WHEN b.booking_status='Picked' THEN 'green'
                            WHEN b.booking_status='Completed' THEN 'yellow'
                            WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                            WHEN b.booking_status='Cancelled' THEN 'red'
                            WHEN b.booking_status='Expired' THEN 'red'
                         END as color";

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
            ->select('b.id as id', 'ic.id_no as id_no', 'icg.id_no as icg_id_no', 'ic.mobile_no as mobile_no', 'icg.mobile_no as icg_mobile_no', 'ic.email as email', 'icg.email as icg_email', DB::raw($select_color), 'city_f.image1 as pickup_city_image', DB::raw("CONCAT ('$image_path', city_f.image1) AS pickup_city_image_path"))
            ->first();
        return $record;
    }

    public function getSingleBookingCorporate($booking_code)
    {
        $this->sendEmail("getSingleBookingCorporate called in services model at line no. 398");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        $select_color = "CASE
                            WHEN b.booking_status='Not Picked' THEN 'blue'
                            WHEN b.booking_status='Picked' THEN 'green'
                            WHEN b.booking_status='Completed' THEN 'yellow'
                            WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                            WHEN b.booking_status='Cancelled' THEN 'red'
                            WHEN b.booking_status='Expired' THEN 'red'
                         END as color";

        $record = DB::table('booking as b')
            ->leftjoin('car_model as cm', 'b.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'cm.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->leftjoin('car_category as c_cat', 'cg.car_category_id', '=', 'c_cat.id')

            ->leftjoin('booking_corporate_customer as bcc', 'b.id', '=', 'bcc.booking_id')
            ->leftjoin('corporate_driver as cd', 'bcc.driver_id', '=', 'cd.id')
            ->leftjoin('corporate_customer as cc', 'bcc.uid', '=', 'cc.uid')

            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->leftjoin('booking_payment as bp', 'b.id', '=', 'bp.booking_id')
            ->leftjoin('branch as bf', 'b.from_location', '=', 'bf.id')
            ->leftjoin('branch as bt', 'b.to_location', '=', 'bt.id')

            ->leftjoin('city as city_f', 'bf.city_id', '=', 'city_f.id')
            ->leftjoin('city as city_t', 'bt.city_id', '=', 'city_t.id')
            ->leftjoin('region as r', 'city_f.region_id', '=', 'r.id')

            ->where('b.reservation_code', $booking_code)
            ->select('b.id as id', 'cd.id_no as id_no', 'cc.company_code as company_code', 'cd.mobile_no as mobile_no', 'cd.email as email', DB::raw($select_color), 'city_f.image1 as pickup_city_image', DB::raw("CONCAT ('$image_path', city_f.image1) AS pickup_city_image_path"))
            ->first();
        return $record;
    }

    public function getSingleBookingDetails($booking_id, $user_type="individual_user", $lang="eng")
    {
        $this->sendEmail("getSingleBookingDetails called in services model at line no. 437");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        if ($lang == "eng")
        {
            $lang_base_url = custom::baseurl('/') . '/en';
        }else{
            $lang_base_url = custom::baseurl('/');
        }

        $select_color = "CASE
                            WHEN b.booking_status='Not Picked' THEN 'blue'
                            WHEN b.booking_status='Picked' THEN 'green'
                            WHEN b.booking_status='Completed' THEN 'yellow'
                            WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                            WHEN b.booking_status='Cancelled' THEN 'red'
                            WHEN b.booking_status='Expired' THEN 'red'
                         END as color";

        $print_url = $lang_base_url.'/print-booking/';
        $str_url = '||EDxjrybEuppO';
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
            ->select('b.*', 'bp.*', 'bipm.payment_method as payment_method', 'cm.id as car_model_id', DB::raw("CONCAT ('$image_path', cm.image1) AS image_path"), 'cm.image1 as car_image', 'cm.year as year', 'cm.eng_title as car_model_eng_title', 'cm.arb_title as car_model_arb_title', 'cm.no_of_passengers as no_of_passengers', 'cm.no_of_bags as no_of_bags', 'cm.no_of_doors as no_of_doors', DB::raw("CASE WHEN cm.transmission = 'Auto' THEN 'A' WHEN cm.transmission = 'Manual' THEN 'M' END AS transmission_type"), 'cm.transmission as transmission', 'cm.min_age as min_age', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'cc.eng_title as car_category_eng_title', 'cc.arb_title as car_category_arb_title', 'ic.first_name as first_name', 'ic.last_name as last_name', 'ic.gender as gender', 'ic.id_no as id_no', 'ic.mobile_no as mobile_no', 'ic.email as email', 'bf.phone1 as branch_mobile', 'bf.eng_title as branch_from_eng_title', 'bf.arb_title as branch_from_arb_title', 'bt.eng_title as branch_to_eng_title', 'bt.arb_title as branch_to_arb_title', 'city_f.id as from_city_id', 'city_t.id as to_city_id', 'r.id as from_region_id', 'icg.first_name as icg_first_name', 'icg.last_name as icg_last_name', 'icg.gender as icg_gender', 'icg.id_no as icg_id_no', 'icg.mobile_no as icg_mobile_no', 'icg.email as icg_email', 'bc.cancel_time as cancel_time', 'bc.cancel_charges as cancel_charges', 'slk.km as km', DB::raw('DATE_FORMAT(b.from_date, \'%a\') as day_from_date'), DB::raw('DATE_FORMAT(b.from_date, \'%b\') as month_from_date'), DB::raw('DATE_FORMAT(b.from_date, \'%d\') as date_from_date'), DB::raw('DATE_FORMAT(b.to_date, \'%a\') as day_to_date'), DB::raw('DATE_FORMAT(b.to_date, \'%b\') as month_to_date'), DB::raw('DATE_FORMAT(b.to_date, \'%d\') as date_to_date'), DB::raw('DATE_FORMAT(b.from_date, \'%r\') as from_time'), DB::raw('DATE_FORMAT(b.to_date, \'%r\') as to_time'), DB::raw("CONCAT(b.reservation_code,'$str_url') as print_url"), DB::raw($select_color), 'city_f.image1 as pickup_city_image', DB::raw("CONCAT ('$image_path', city_f.image1) AS pickup_city_image_path"))
            ->first();

        if ($record) {
            foreach ($record as $key => $val) {
                if ($key == 'print_url') {
                    $record->print_url = $lang_base_url . '/print-booking/' . custom::encode_with_jwt($val);
                }
            }
        }

        return $record;
    }

    public function getSingleBookingDetailsForCorporate($booking_id, $user_type="individual_user", $lang="eng")
    {
        $this->sendEmail("getSingleBookingDetailsForCorporate called in services model at line no. 492");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        if ($lang == "eng")
        {
            $lang_base_url = custom::baseurl('/') . '/en';
        }else{
            $lang_base_url = custom::baseurl('/');
        }

        $select_color = "CASE
                            WHEN b.booking_status='Not Picked' THEN 'blue'
                            WHEN b.booking_status='Picked' THEN 'green'
                            WHEN b.booking_status='Completed' THEN 'yellow'
                            WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                            WHEN b.booking_status='Cancelled' THEN 'red'
                            WHEN b.booking_status='Expired' THEN 'red'
                         END as color";

        $print_url = $lang_base_url.'/print-booking/';
        $str_url = '||EDxjrybEuppO';
        $record = DB::table('booking as b')
            ->leftjoin('car_model as cm', 'b.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'cm.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', '=', 'cg.id')
            ->leftjoin('car_category as car_cat', 'cg.car_category_id', '=', 'car_cat.id')

            ->leftjoin('booking_corporate_customer as bcc', 'b.id', '=', 'bcc.booking_id')
            ->leftjoin('corporate_driver as cd', 'bcc.driver_id', '=', 'cd.id')
            ->leftjoin('corporate_customer as cc', 'bcc.uid', '=', 'cc.uid')

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
            ->select('b.*', 'bp.*', 'bipm.payment_method as payment_method', 'cm.id as car_model_id', DB::raw("CONCAT ('$image_path', cm.image1) AS image_path"), 'cm.image1 as car_image', 'cm.image1_eng_alt as car_image_eng_alt', 'cm.image1_arb_alt as car_image_arb_alt',  'cm.year as year', 'cm.eng_title as car_model_eng_title', 'cm.arb_title as car_model_arb_title', 'cm.no_of_passengers as no_of_passengers', 'cm.no_of_bags as no_of_bags', 'cm.no_of_doors as no_of_doors', 'cm.transmission as transmission', 'cm.min_age as min_age', 'ct.eng_title as car_type_eng_title', 'ct.arb_title as car_type_arb_title', 'car_cat.eng_title as car_category_eng_title', 'car_cat.arb_title as car_category_arb_title', 'cd.first_name as first_name', 'cd.last_name as last_name', 'cd.gender as gender', 'cd.id_no as id_no', 'cd.mobile_no as mobile_no', 'cd.email as email', 'bf.phone1 as branch_mobile', 'bf.eng_title as branch_from_eng_title', 'bf.arb_title as branch_from_arb_title', 'bt.eng_title as branch_to_eng_title', 'bt.arb_title as branch_to_arb_title', 'city_f.id as from_city_id', 'city_t.id as to_city_id', 'r.id as from_region_id', 'bc.cancel_time as cancel_time', 'bc.cancel_charges as cancel_charges', 'slk.km as km', 'cc.membership_level as ic_loyalty_card_type','city_f.eng_title as city_from_eng_title','city_f.arb_title as city_from_arb_title','city_t.eng_title as city_to_eng_title','city_t.arb_title as city_to_arb_title','cc.primary_email as corporate_user_email','cc.company_name_en','cc.company_name_ar','cc.company_code', DB::raw('DATE_FORMAT(b.from_date, \'%a\') as day_from_date'), DB::raw('DATE_FORMAT(b.from_date, \'%b\') as month_from_date'), DB::raw('DATE_FORMAT(b.from_date, \'%d\') as date_from_date'), DB::raw('DATE_FORMAT(b.to_date, \'%a\') as day_to_date'), DB::raw('DATE_FORMAT(b.to_date, \'%b\') as month_to_date'), DB::raw('DATE_FORMAT(b.to_date, \'%d\') as date_to_date'), DB::raw('DATE_FORMAT(b.from_date, \'%r\') as from_time'), DB::raw('DATE_FORMAT(b.to_date, \'%r\') as to_time'), DB::raw("CONCAT(b.reservation_code,'$str_url') as print_url"), DB::raw($select_color), 'city_f.image1 as pickup_city_image', DB::raw("CONCAT ('$image_path', city_f.image1) AS pickup_city_image_path"))
            ->first();

        if ($record) {
            foreach ($record as $key => $val) {
                if ($key == 'print_url') {
                    $record->print_url = $lang_base_url . '/print-booking/' . custom::encode_with_jwt($val);
                }
            }
        }

        return $record;
    }


    public function getLatestBookingForEachUserIos($user_uid, $records_for = "current_bookings", $search_keyword = "", $lang = "eng")
    {
        $this->sendEmail("getLatestBookingForEachUserIos called for uid $user_uid in services model at line no. 546");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        if ($lang == "eng")
        {
            $lang_base_url = custom::baseurl('/') . '/en';
        }else{
            $lang_base_url = custom::baseurl('/');
        }

        $print_url = $lang_base_url.'/print-booking/';
        $str_url = '||EDxjrybEuppO';
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
            /*$criteriaForGettingBookings = "
			(b.booking_status='Not Picked')
			or (b.booking_status='Picked')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";*/

            $criteriaForGettingBookings = "
			(b.booking_status='Not Picked') 
			or (b.booking_status='Picked') 
			or (b.booking_status='Completed') 
			or (b.booking_status='Completed with Overdue') 
			or (b.booking_status='Cancelled') 
			or (b.booking_status='Expired') 
			";
        }

        $selectCols = "CASE
                        WHEN b.booking_status='Not Picked' THEN 'blue'
                        WHEN b.booking_status='Picked' THEN 'green'
                        WHEN b.booking_status='Completed' THEN 'yellow'
                        WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                        WHEN b.booking_status='Cancelled' THEN 'red'
                        WHEN b.booking_status='Expired' THEN 'red'
                       END as color,
                        ";
            $selectCols .= "b.*,bipm.payment_method as payment_method,ct.eng_title as car_type_eng_title, ct.arb_title as car_type_arb_title,
             car_cat.eng_title as car_category_eng_title, car_cat.arb_title as car_category_arb_title, bp.rent_price as rent_per_day, cm.year as year, cm.image1 as car_image,
              CONCAT ('$image_path', cm.image1) AS image_path, cm.no_of_passengers as no_of_passengers, cm.min_age as min_age, 
              cm.no_of_bags as no_of_bags, cm.no_of_doors as no_of_doors,
               CASE
                   WHEN cm.transmission = 'Auto' THEN 'A'
                   WHEN cm.transmission = 'Manual' THEN 'M'
               END AS transmission_type,
               cm.transmission as transmission,
               cm.eng_title as car_eng_title,
               cm.arb_title as car_arb_title,
               bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from,
               bf.phone1 as branch_mobile,
               bt.eng_title as branch_eng_to,
               bt.arb_title as branch_arb_to, 
                (select br.eng_title from branch br 
                 where br.id=b.from_location) as branch_eng_from,
                 DATE_FORMAT(b.from_date, '%a') as day_from_date,
                 DATE_FORMAT(b.from_date, '%b') as month_from_date,
                 DATE_FORMAT(b.from_date, '%d') as date_from_date,
                 DATE_FORMAT(b.to_date, '%a') as day_to_date,
                 DATE_FORMAT(b.to_date, '%b') as month_to_date,
                 DATE_FORMAT(b.to_date, '%d') as date_to_date,
                 DATE_FORMAT(b.from_date, '%r') as from_time,
                 DATE_FORMAT(b.to_date, '%r') as to_time,
                 CONCAT(b.reservation_code,'$str_url') as print_url,
                 city_f.image1 as pickup_city_image,
                 CONCAT('$image_path', city_f.image1) as pickup_city_image_path,
                 bp.*
                 ";


        // case when b.type='individual_customer'
        //then concat(ic.first_name,' ', ic.last_name)


        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )

        left join branch bf on b.from_location=bf.id
        left join branch bt on b.to_location=bt.id
        left join city city_f on bf.city_id=city_f.id
        
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method bipm on b.id=bipm.booking_id
        left join booking_individual_user biu on b.id=biu.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        
        ";

        $query .= " left join car_type ct on cm.car_type_id = ct.id
        left join car_group cg on ct.car_group_id = cg.id
        left join car_category car_cat on cg.car_category_id = car_cat.id
        left join booking_payment bp on b.id = bp.booking_id
        where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        $query .= " and (biu.uid = ".$user_uid." OR bcc.uid = ".$user_uid." )";

        $query .= " ORDER BY b.id DESC";
        $bookings = DB::select($query);
        return $bookings;
    }


    public function getLatestBookingForEachUserAndroid($user_uid, $records_for = "current_bookings", $search_keyword = "", $lang = "eng")
    {
        $this->sendEmail("getLatestBookingForEachUserAndroid called for uid $user_uid in services model at line no. 662");

        $image_path = custom::baseurl('/') . '/public/uploads/';
        if ($lang == "eng")
        {
            $lang_base_url = custom::baseurl('/') . '/en';
        }else{
            $lang_base_url = custom::baseurl('/');
        }

        $print_url = $lang_base_url.'/print-booking/';
        $str_url = '||EDxjrybEuppO';
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
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Cancelled')
			or (b.from_date >= '" . date('Y-m-d H:i:s') . "' and b.booking_status='Expired')
			or b.sync = 'N'
			";
        }

        $selectCols = "CASE
                        WHEN b.booking_status='Not Picked' THEN 'blue'
                        WHEN b.booking_status='Picked' THEN 'green'
                        WHEN b.booking_status='Completed' THEN 'yellow'
                        WHEN b.booking_status='Completed with Overdue' THEN 'yellow'
                        WHEN b.booking_status='Cancelled' THEN 'red'
                        WHEN b.booking_status='Expired' THEN 'red'
                       END as color,
                        ";
        $selectCols .= "b.*,ct.eng_title as car_type_eng_title, ct.arb_title as car_type_arb_title,
             car_cat.eng_title as car_category_eng_title, car_cat.arb_title as car_category_arb_title, bp.rent_price as rent_per_day,
              bp.total_sum as total_sum, bp.no_of_days as no_of_days, cm.year as year, cm.image1 as car_image,
              CONCAT ('$image_path', cm.image1) AS image_path, cm.no_of_passengers as no_of_passengers, cm.min_age as min_age, 
              cm.no_of_bags as no_of_bags, cm.no_of_doors as no_of_doors,
               CASE
                   WHEN cm.transmission = 'Auto' THEN 'A'
                   WHEN cm.transmission = 'Manual' THEN 'M'
               END AS transmission_type,
               cm.transmission as transmission,
               cm.eng_title as car_eng_title,
               cm.arb_title as car_arb_title, bf.eng_title as branch_eng_from,
               bf.arb_title as branch_arb_from, bt.eng_title as branch_eng_to,bt.arb_title as branch_arb_to, 
                (select br.eng_title from branch br 
                 where br.id=b.from_location) as branch_eng_from,
                 DATE_FORMAT(b.from_date, '%a') as day_from_date,
                 DATE_FORMAT(b.from_date, '%b') as month_from_date,
                 DATE_FORMAT(b.from_date, '%d') as date_from_date,
                 DATE_FORMAT(b.to_date, '%a') as day_to_date,
                 DATE_FORMAT(b.to_date, '%b') as month_to_date,
                 DATE_FORMAT(b.to_date, '%d') as date_to_date,
                 DATE_FORMAT(b.from_date, '%r') as from_time,
                 DATE_FORMAT(b.to_date, '%r') as to_time,
                 CONCAT(b.reservation_code,'$str_url') as print_url";

        // case when b.type='individual_customer'
        //then concat(ic.first_name,' ', ic.last_name)



        $query = "SELECT " . $selectCols . " FROM `booking` b join car_model cm on b.car_model_id=cm.id and
    (
        " . $criteriaForGettingBookings . "
    )

        left join branch bf on b.from_location=bf.id
        left join branch bt on b.to_location=bt.id
        left join city city_f on bf.city_id=city_f.id
        left join booking_cc_payment bcp on b.id=bcp.booking_id
        left join booking_sadad_payment bsp on b.id=bsp.s_booking_id
        left join booking_individual_payment_method bipm on b.id=bipm.booking_id
        left join booking_corporate_invoice bci on b.id=bci.booking_id
        left join booking_individual_user biu on b.id=biu.booking_id
        left join booking_corporate_customer bcc on b.id=bcc.booking_id
        left join corporate_customer cc on FIND_IN_SET(bcc.uid, cc.uid) > 0
        left join corporate_driver cd on bcc.driver_id=cd.id
        ";

        $query .= " left join car_type ct on cm.car_type_id = ct.id
        left join car_group cg on ct.car_group_id = cg.id
        left join car_category car_cat on cg.car_category_id = car_cat.id
        left join booking_payment bp on b.id = bp.booking_id
        where (bcp.status='completed' or bci.payment_status='paid' or bsp.s_status='completed' or bipm.payment_method='Cash' or bipm.payment_method='Corporate Credit') ";

        $query .= " and (biu.uid = ".$user_uid." OR bcc.uid = ".$user_uid." )";

        $query .= " ORDER BY b.id DESC";
        //echo $query;exit();
        $bookings = DB::select($query);
        return $bookings;
    }

    private function sendEmail($message)
    {
        return true;
        $message .= " - Function is called from " . $_SERVER['SERVER_NAME'];

        $email['subject'] = "Blue query debug email";
        $email['fromEmail'] = 'admin@key.sa';
        $email['fromName'] = 'no-reply';
        $email['toEmail'] = 'bilal_ejaz@astutesol.com';
        $email['ccEmail'] = 'f.baghdadi@key.sa';
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $content['contact_no'] = '0321';
        $content['lang_base_url'] = custom::baseurl('/');
        $content['name'] = 'Admin';
        $content['msg'] = $message;
        $content['gender'] = 'male';
        custom::sendEmail2('general', $content, $email, 'eng');
    }

}