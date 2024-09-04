<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{

    protected $table = 'dropoff_charges';

    //public $timestamps = true;

    public function get_count_of_surveys_filled_for_emoji($emoji_id)
    {
        $records = DB::table('survey_feedback')
            ->select('*')
            ->where('emoji_id', $emoji_id)
            ->count();
        return $records;
    }

    public function get_count_of_surveys_filled_for_category($category_id)
    {
        $records = DB::table('survey_feedback')
            ->select('*')
            ->where('category_id', $category_id)
            ->count();
        return $records;
    }

    public function get_count_of_surveys_filled_for_option($option_id)
    {
        $records = DB::table('survey_feedback')
            ->select('*')
            ->where('option_id', $option_id)
            ->count();
        return $records;
    }

    public function get_surveys_count($column = "created_at", $operator = ">=", $date)
    {
        $records = DB::table('survey_feedback')
            ->whereDate($column, $operator, $date)
            ->select('*')
            ->count();
        return $records;

    }

    public function get_count_of_oasis_surveys_filled_for_emoji($emoji_id)
    {
        $records = DB::table('oasis_survey_feedback')
            ->select('*')
            ->where('emoji_id', $emoji_id)
            ->count();
        return $records;
    }

    public function get_count_of_oasis_surveys_filled_for_category($category_id)
    {
        $records = DB::table('oasis_survey_feedback')
            ->select('*')
            ->where('category_id', $category_id)
            ->count();
        return $records;
    }

    public function get_count_of_oasis_surveys_filled_for_option($option_id)
    {
        $records = DB::table('oasis_survey_feedback')
            ->select('*')
            ->where('option_id', $option_id)
            ->count();
        return $records;
    }

    public function get_oasis_surveys_count($column = "created_at", $operator = ">=", $date)
    {
        $records = DB::table('oasis_survey_feedback')
            ->whereDate($column, $operator, $date)
            ->select('*')
            ->count();
        return $records;

    }

    public function getAllQuestionsForSurvey($filter_date_start="",$filter_date_end="")
    {
        /*$query = "SELECT
concat(ic.first_name,\" \",ic.last_name) as `Customer Name`, 
ic.mobile_no as `Mobile No`, 
ic.email as `Customer Email`, 
ic.id_no as `Customer ID No`, 
b.reservation_code as `Reservation Code`, 
bf.eng_title as `Pickup Branch`, 
bt.eng_title as `Dropoff Branch`, 
if(emoji_id=4,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Bad\"),\"\") as `Bad`, 
if(emoji_id=3,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Average\"),\"\") as `Average`, 
if(emoji_id=2,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Good\"),\"\") as `Good`, 
if(emoji_id=1,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Very Good\"),\"\") as `Very Good` 
FROM `survey_feedback` sf , `individual_customer` ic, `booking` b, `branch` bf, `branch` bt where sf.customer_id=ic.id and sf.booking_id=b.id and b.from_location=bf.id and b.to_location=bt.id";*/ // old query

       /* $query = "SELECT
concat(ic.first_name,\" \",ic.last_name) as `Customer Name`, 
ic.mobile_no as `Mobile No`, 
ic.email as `Customer Email`, 
ic.id_no as `Customer ID No`, 
b.reservation_code as `Reservation Code`,
CONCAT(cf.eng_title,\", \", bf.eng_title) as `Pickup Branch`,
CONCAT(ct.eng_title,\", \", bt.eng_title) as `Dropoff Branch`, 
if(emoji_id=4,\"Bad\",\"\") as `Bad`,
if(emoji_id=3,\"Average\",\"\") as `Average`,
if(emoji_id=2,\"Good\",\"\") as `Good`,
if(emoji_id=1,\"Very Good\",\"\") as `Very Good`,
if(category_id>0, concat(category_desc,\", \",answer_desc), \"\") as `Comment`
FROM `survey_feedback` sf , `individual_customer` ic, `booking` b, `branch` bf, `branch` bt, `city` cf, `city` ct where sf.customer_id=ic.id and sf.booking_id=b.id and b.from_location=bf.id and b.to_location=bt.id and bf.city_id=cf.id and bt.city_id=ct.id";
       */

        $query = "SELECT 
ct.eng_title as `City`,
bt.eng_title as `Dropoff Branch`, 
concat(ic.first_name,\" \",ic.last_name) as `Customer Name`, 
ic.mobile_no as `Mobile No`, 
crt.eng_title as `Car Type`,
cm.eng_title as `Car Model`,
bp.total_sum as `Payment`,
if(category_id>0, concat(category_desc,\", \",answer_desc), \"\") as `Notes`,
ic.email as `Customer Email`, 
ic.id_no as `Customer ID No`, 
b.reservation_code as `Reservation Code`,
b.from_date as `Contract Opening Date & Time`,
b.to_date as `Contract Closing Date & Time`,
CASE
    WHEN is_delivery_mode = \"yes\" THEN \"Delivery\"
    WHEN is_delivery_mode = \"no\" THEN \"Pickup\"    
END as `Reservation Type`, 
b.booking_source as `Reservation Source`,
if(emoji_id=4,\"Bad\",\"\") as `Bad`,
if(emoji_id=3,\"Average\",\"\") as `Average`,
if(emoji_id=2,\"Good\",\"\") as `Good`,
if(emoji_id=1,\"Very Good\",\"\") as `Very Good`,
DATE_FORMAT(sf.created_at, '%d-%m-%Y %T') as `Survey Filled At`
FROM `survey_feedback` sf , `individual_customer` ic, `booking` b, `branch` bf, `branch` bt, `city` cf, `city` ct, car_model cm, car_type crt, booking_payment bp where bp.booking_id=b.id and cm.id=b.car_model_id and crt.id=cm.car_type_id and sf.customer_id=ic.id and sf.booking_id=b.id and b.from_location=bf.id and b.to_location=bt.id and bf.city_id=cf.id and bt.city_id=ct.id";

        if($filter_date_start)
        $query .= " and date(b.to_date) >= '".$filter_date_start."'";

        if($filter_date_end)
            $query .= " and date(b.to_date) <= '".$filter_date_end."'";

        $data = DB::select($query);
        return $data = array_map(function ($object) {
            return (array)$object;
        }, $data);
    }

    public function getAllQuestionsForOasisSurvey($filter_date_start="",$filter_date_end="")
    {
        /*$query = "SELECT
osfs.contract_no as `Contract No`, 
osfs.name as `Customer Name`, 
osfs.mobile_no as `Mobile No`, 
if(emoji_id=4, if(category_id>0, concat(category_desc,\", \",answer_desc), \"Bad\"),\"\") as `Bad`,
if(emoji_id=3,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Average\"),\"\") as `Average`,
if(emoji_id=2,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Good\"),\"\") as `Good`,
if(emoji_id=1,if(category_id>0, concat(category_desc,\", \",answer_desc), \"Very Good\"),\"\") as `Very Good`
FROM `oasis_survey_feedback` osf , `oasis_survey_filled_status` osfs where osf.contract_no=osfs.contract_no";*/ // old query

        $query = "SELECT 
osfs.contract_no as `Contract No`, 
osfs.booking_status as `Booking Status`, 
osfs.name as `Customer Name`, 
osfs.mobile_no as `Mobile No`,
osf.car_quality_and_cleanliness as `Car Quality and Cleanliness`,
osf.employee_performance as `Employee Performance`,
osf.comment as `Comment`,
osfs.id_no as `Customer Id No`,
osfs.from_location as `Pickup Branch`,
osfs.to_location as `Dropoff Branch`,
CONCAT(osfs.car_type,\", \", osfs.car_model) as `Car Type & Model`,
CONCAT(osfs.from_date,\", \", osfs.to_date) as `Contract Opening Date & Time`,
osfs.total_payment as `Total Payment`,
DATE_FORMAT(osfs.created_at, '%d-%m-%Y %T') as `Survey Filled At`,
       
osfs.opened_region as `Open Region`,
osfs.opened_city as `Open City`,
osfs.close_region as `Close Region`,
osfs.close_city as `Close City`,
osfs.booking_id as `Booking ID`,
osfs.booking_source as `Booking Source`,
osfs.is_delivery as `Is Delivery`,
osfs.is_subscription as `Is Subscription`,
osfs.staff_id as `Staff ID`,
osfs.staff_name as `Staff Name`,
DATE_FORMAT(osfs.contract_opened_date_time, '%d-%m-%Y %T') as `Contract Opened Date Time`,
DATE_FORMAT(osfs.contract_closed_date_time, '%d-%m-%Y %T') as `Contract Closed Date Time`,
osf.branch_employees_behavior_and_performance as `Branch Employee Behavior And Performance`,
osf.the_quickness_and_efficiency_of_completing_your_rental_procedure as `The Quickness and efficiency of completing your rental procedure`,
osf.the_accuracy_of_the_rental_information_provided_to_you as `The accuracy of the rental information provided to you`,
osf.the_safety_and_the_quality_of_the_vehicle_structure as `The safety and the quality of the vehicle structure`,
osf.the_cleanliness_of_the_vehicle_externally_and_internally as `The cleanliness of the vehicle externally and internally`,
osf.how_likely_are_you_to_recommend_our_company as `How likely are you to recommend our company`,
osf.your_experience_with_key as `Your your_experience_with_key with Key`,
osf.purpose_of_renting as `Purpose of renting`,
osf.suggestion_or_opinion_you_would_like_to_share as `Suggestion or opinion you would like to share`
       
FROM `oasis_survey_feedback` osf JOIN `oasis_survey_filled_status` osfs ON osf.contract_no=osfs.contract_no AND osf.booking_status=osfs.booking_status";

        if($filter_date_start)
            $query .= " and date(osfs.created_at) >= '".$filter_date_start."'";

        if($filter_date_end)
            $query .= " and date(osfs.created_at) <= '".$filter_date_end."'";

        $data = DB::select($query);
        return $data = array_map(function ($object) {
            return (array)$object;
        }, $data);
    }

    public function get_question_answers_count_in_oasis_survey_feedback($column, $value)
    {
        $records = DB::table('oasis_survey_feedback')
            ->select('*')
            ->where($column, $value)
            ->count();
        return $records;
    }

}