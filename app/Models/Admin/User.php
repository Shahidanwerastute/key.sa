<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use custom;

class User extends Model {

	protected $table = 'users';
	public $timestamps = true;

	/*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/

	public function getSingleUserInfo($user_id)
	{
		$user_detail = DB::table('users')
			->select('*')
			->where('id', $user_id)
			->get();
		return $user_detail;
	}

    public function getSingleCorporateUserInfo($booking_id)
    {
        $query = "SELECT cc.company_code, cc.company_name_en, cc.primary_name, cc.primary_position, cc.primary_email, cc.primary_phone, cc.secondary_name, cc.secondary_position, cc.secondary_email, cc.secondary_phone, cc.membership_level, cc.active_status, b.type AS user_type, cd.*, cit.eng_title AS eng_id_type, u.email as booking_username FROM booking AS b LEFT JOIN booking_corporate_customer AS bcc ON b.id = bcc.booking_id LEFT JOIN corporate_customer AS cc ON FIND_IN_SET(bcc.uid, cc.uid) > 0 LEFT JOIN corporate_driver AS cd ON bcc.driver_id = cd.id LEFT JOIN customer_id_types AS cit ON cd.id_type = cit.ref_id LEFT JOIN users u ON bcc.uid = u.id WHERE b.id = $booking_id";

        $bookings = DB::select($query);

        return $bookings;
    }

	public function getSingleIndividualUserInfo($booking_id)
	{
		$bookings = DB::table('booking as b')
			->leftjoin('booking_individual_user as biu', 'b.id', '=', 'biu.booking_id')
			->leftjoin('individual_customer as ic', 'biu.uid', '=', 'ic.uid')
			->leftjoin('customer_id_types as cit', 'ic.id_type', '=', 'cit.ref_id')
			->leftjoin('driving_license_id_types as dlit', 'ic.license_id_type', '=', 'dlit.ref_id')
			->leftjoin('nationalities as n', 'ic.nationality', '=', 'n.oracle_reference_number')
			->leftjoin('country as id_c', 'ic.id_country', '=', 'id_c.oracle_reference_number')
			->leftjoin('country as lic_c', 'ic.license_country', '=', 'lic_c.oracle_reference_number')
			->leftjoin('job_title as jt', 'ic.job_title', '=', 'jt.oracle_reference_number')
			->leftjoin('users as u', 'ic.uid', '=', 'u.id')
			->select('ic.*', 'b.type as user_type', 'cit.eng_title as id_type_title', 'dlit.eng_title as license_id_type_title', 'n.eng_country_name as nationality_title', 'id_c.eng_country as id_country', 'lic_c.eng_country as license_country', 'jt.eng_title as job_title', 'u.email as booking_username')
			->where('b.id', $booking_id)
			->get();
		return $bookings;
	}

	public function getSingleGuestUserInfo($booking_id)
	{
		$bookings = DB::table('booking as b')
			->leftjoin('booking_individual_guest as big', 'b.id', '=', 'big.booking_id')
			->leftjoin('individual_customer as ic', 'big.individual_customer_id', '=', 'ic.id')
			->leftjoin('customer_id_types as cit', 'ic.id_type', '=', 'cit.ref_id')
			->leftjoin('driving_license_id_types as dlit', 'ic.license_id_type', '=', 'dlit.ref_id')
			->leftjoin('nationalities as n', 'ic.nationality', '=', 'n.oracle_reference_number')
			->leftjoin('country as id_c', 'ic.id_country', '=', 'id_c.oracle_reference_number')
			->leftjoin('country as lic_c', 'ic.license_country', '=', 'lic_c.oracle_reference_number')
			->leftjoin('job_title as jt', 'ic.job_title', '=', 'jt.oracle_reference_number')
			->select('ic.*', 'b.type as user_type', 'cit.eng_title as id_type_title', 'dlit.eng_title as license_id_type_title', 'n.eng_country_name as nationality_title', 'id_c.eng_country as id_country', 'lic_c.eng_country as license_country', 'jt.eng_title as job_title')
			->where('b.id', $booking_id)
			->get();
		return $bookings;
	}



}