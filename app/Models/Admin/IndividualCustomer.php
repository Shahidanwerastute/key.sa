<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;


class IndividualCustomer extends Model
{

    protected $table = 'individual_customer';

    public $timestamps = true;


    public function getAll()
    {
        $admins = DB::table('individual_customer')
            ->select('*')
            ->get();
        return $admins;
    }

    /*public function getAllCustomers($start, $length,$search)
    {
        $records = DB::table('individual_customer as ic');
        $records->leftjoin('nationalities as n', 'ic.nationality', '=', 'n.oracle_reference_number')
                ->leftjoin('customer_id_types as cit', 'ic.id_type', '=', 'cit.ref_id')
                ->leftjoin('driving_license_id_types as dlit', 'ic.license_id_type', '=', 'dlit.ref_id')
                ->leftjoin('country as c', 'ic.id_country', '=', 'c.oracle_reference_number')
                ->leftjoin('country as lc', 'ic.license_country', '=', 'lc.oracle_reference_number')
                ->leftjoin('job_title as jt', 'ic.job_title', '=', 'jt.oracle_reference_number')
                ->select('ic.*', 'n.eng_country_name as nationality_name', 'cit.eng_title as id_type_name', 'dlit.eng_title as license_id_type_name', 'c.eng_country as id_country_name', 'lc.eng_country as license_country_name', 'jt.eng_title as job_title_name', DB::raw('CONCAT("REG",ic.uid) AS user_id'));

        if($search != null && $search != ""){
            $records->where("ic.mobile_no","LIKE","%".$search."%");
            $records->orWhere("ic.email","LIKE","%".$search."%");
            $records->orWhere("ic.id_no","LIKE","%".$search."%");
            $records->orWhere("ic.id","LIKE","%".$search."%");
            $records->orWhere(DB::raw('CONCAT("REG","",ic.uid)'),"LIKE","%".$search."%");
        }
            $records->orderBy("ic.id", "DESC");

        $records->offset($start);
        $records->limit($length);
        $result = $records->get();
        return $result;
    }*/

    public function getAllCustomers($customer_id = "", $start = "", $length = "", $sort_by = "", $search = "")
    {
        $records = DB::table('individual_customer as ic');
        $records->leftjoin('nationalities as n', 'ic.nationality', '=', 'n.oracle_reference_number')
            ->leftjoin('customer_id_types as cit', 'ic.id_type', '=', 'cit.ref_id')
            ->leftjoin('driving_license_id_types as dlit', 'ic.license_id_type', '=', 'dlit.ref_id')
            ->leftjoin('country as c', 'ic.id_country', '=', 'c.oracle_reference_number')
            ->leftjoin('country as lc', 'ic.license_country', '=', 'lc.oracle_reference_number')
            ->leftjoin('job_title as jt', 'ic.job_title', '=', 'jt.oracle_reference_number')
            ->select('ic.*', 'n.eng_country_name as nationality_name', 'cit.eng_title as id_type_name', 'dlit.eng_title as license_id_type_name', 'c.eng_country as id_country_name', 'lc.eng_country as license_country_name', 'jt.eng_title as job_title_name', DB::raw('CONCAT("REG",ic.uid) AS user_id'));

        if ($search != null && $search != "") {
            $records->where("ic.mobile_no", "LIKE", "%" . $search . "%");
            $records->orWhere("ic.email", "LIKE", "%" . $search . "%");
            $records->orWhere("ic.id_no", "LIKE", "%" . $search . "%");
            $records->orWhere("ic.id", "LIKE", "%" . $search . "%");
            $records->orWhere(DB::raw('CONCAT("REG","",ic.uid)'), "LIKE", "%" . $search . "%");
            $records->orWhere(DB::raw('CONCAT(ic.first_name," ",ic.last_name)'), "LIKE", "%" . $search . "%");
        }

        if ($customer_id != "") {
            $records->where("ic.id", $customer_id);
        }

        if ($sort_by != "")
        {
            $sort_by = explode(" ", $sort_by);
            $records->orderBy("ic.".$sort_by[0], $sort_by[1]);
        }

        if ($start != "") {
            $records->offset($start);
        }
        if ($length != "") {
            $records->limit($length);
        }
        $result = $records->get();

        return $result;
    }

    public function getCount($search)
    {
        $record = DB::table('individual_customer');
        /*if($search != null && $search != ""){
            $record->where("first_name","LIKE","%".$search."%");
        }*/
        if ($search != null && $search != "") {
            $record->where("mobile_no", "LIKE", "%" . $search . "%");
            $record->orWhere("email", "LIKE", "%" . $search . "%");
            $record->orWhere("id_no", "LIKE", "%" . $search . "%");
        }
        $count = $record->count();
        return $count;
    }


    public function getSingleUserInfo($user_id)
    {
        $user_detail = DB::table('individual_customer')
            ->select('*')
            ->where('id', $user_id)
            ->get();
        return $user_detail;
    }

    public function exportUsers()
    {
        $records = DB::table('individual_customer as ic')
            ->join('users as u', 'ic.uid', '=', 'u.id')
            ->where('uid', '>', 0)
            ->select('ic.*', 'u.id as user_id')
            ->orderBy("ic.first_name", "ASC")
            ->get();
        return $records;
    }

    public function exportCustomers()
    {
        $user_detail = DB::table('individual_customer')
            ->select('*')
            ->offset(0)
            ->limit(20000)
            ->get();
        return $user_detail;
    }

    public function checkIfCustomerAlreadyExistWithIdNo($id, $id_no)
    {
        DB::enableQueryLog();
        $count = DB::table('individual_customer')
            ->where('id_no', $id_no)
            ->where('id', "!=", $id)
            ->select('*')
            ->count();
        //Custom::logQuery();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }


}