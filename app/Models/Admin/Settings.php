<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;

class Settings extends Model
{

    protected $table = 'settings';
    public $timestamps = false;
    // DB::enableQueryLog();


    //protected $fillable = ['user_id','role_id','title'];

    public function get_all($tbl, $order_by = "id", $sort = "desc")
    {
        $cards = DB::table($tbl)
            ->select('*')
            ->orderBy($order_by, $sort)
            ->get();
        return $cards;

    }

    public function get_single_row($tbl)
    { 
        $cards = DB::table($tbl)
                    ->select('*')
                    ->first();
        return $cards;

    }

    public function get_single_record($tbl, $where)
    {
        $cards = DB::table($tbl)
            ->where($where)
            ->select('*')
            ->first();
        return $cards;

    }

    public function add_data($tbl, $data)
    {
        $id = DB::table($tbl)->insertGetId($data);
        return $id;
    }

    public function update_data($tbl, $data, $id, $log = true)
    {
        if ($log)
    {
        custom::log('Site Settings', 'update');
        }
        DB::table($tbl)
            ->where('id', $id)
            ->update($data);
        return $id;
    }

    public function delete_data($tbl, $id)
    {
        custom::log('Site Settings', 'delete');
        DB::table($tbl)->where('id', $id)->delete();
        return true;
    }

    public function log($data)
    {
        $id = DB::table('logs')->insertGetId($data);
        return $id;
    }

    public function get_latest_logs($date)
    {
        $logs = DB::table('logs')
            ->where('created_at', '>=', $date)
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();
        return $logs;

    }

    public function get_all_logs()
    {
        $logs = DB::table('logs')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();
        return $logs;

    }

    public function get_loyalty_card_types()
    {
        $models = DB::table('setting_loyalty_cards as slc')
            ->leftjoin('setting_renting_type as srt', 'slc.renting_type_id', '=', 'srt.id')
            ->select('slc.*', 'srt.type as renting_type')
            ->get();
        return $models;
    }

    public function get_single($id)
    {
        $models = DB::table('setting_loyalty_cards as slc')
            ->leftjoin('setting_renting_type as srt', 'slc.renting_type_id', '=', 'srt.id')
            ->where('slc.id', $id)
            ->select('slc.*', 'srt.type as renting_type')
            ->first();
        return $models;
    }

    public function saveData($data)
    {
        $id = DB::table('setting_loyalty_cards')->insertGetId($data);
        return $id;
    }

    public function updateData($data, $id)
    {
        DB::table('setting_loyalty_cards')
            ->where('id', $id)
            ->update($data);
        return $id;
    }

    public function deleteData($id)
    {
        DB::table('setting_loyalty_cards')->where('id', $id)->delete();
    }


    public function checkIfConflictDataExist($data, $id = "")
    {
        $query = "SELECT * FROM setting_loyalty_cards cp 
        WHERE
        (
        (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
            OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

        if ($data['applies_to'] != null)
            $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

        $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
        )        
        and cp.car_model_id='" . $data['car_model_id'] . "' and charge_element='" . $data['charge_element'] . "' 
        and cp.renting_type_id='" . $data['renting_type_id'] . "'";

        if (isset($data['region_id']) && $data['region_id'] != "")
            $query .= " and cp.region_id='" . $data['region_id'] . "'";
        else
            $query .= " and cp.region_id=''";
        if (isset($data['city_id']) && $data['city_id'] != "")
            $query .= " and cp.city_id='" . $data['city_id'] . "'";
        else
            $query .= " and cp.city_id=''";
        if (isset($data['branch_id']) && $data['branch_id'] != "")
            $query .= " and cp.branch_id='" . $data['branch_id'] . "'";
        else
            $query .= " and cp.branch_id=''";
        if (isset($data['customer_type']) && $data['customer_type'] != "")
            $query .= " and cp.customer_type='" . $data['customer_type'] . "'";
        else
            $query .= " and cp.customer_type=''";

        if (isset($data['id']))//update case
            $query .= " and cp.id!='" . $data['id'] . "'";

        if ($id != "") {
            $query .= " and cp.id != $id";
        }

        $records = DB::select($query);

        return $records;
    }

    public function get_renting_types()
    {
        $models = DB::table('setting_renting_type')
            ->select('*')
            ->get();
        return $models;
    }

    public function get_single_renting_type($id)
    {
        $models = DB::table('setting_renting_type')
            ->where('id', $id)
            ->select('*')
            ->first();
        return $models;
    }

    public function save_renting_type($data)
    {
        $id = DB::table('setting_renting_type')->insertGetId($data);
        return $id;
    }

    public function update_renting_type($data, $id)
    {
        DB::table('setting_renting_type')
            ->where('id', $id)
            ->update($data);
        return $id;
    }

    public function delete_renting_type($id)
    {
        DB::table('setting_renting_type')->where('id', $id)->delete();
    }

    public function get_bookings_done($date, $operator = ">=")
    {
        /*$query = "SELECT * FROM `booking` AS b LEFT JOIN `booking_cc_payment` as bccp ON b.id = bccp.booking_id AND bccp.status = 'completed' AND DATE(b.created_at) >= '".$date."'";
        $bookings = DB::select($query);*/
        $records = DB::table('booking as b')
            ->leftjoin('booking_cc_payment as bccp', 'b.id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->whereDate('b.created_at', $operator, $date)
            ->select('*')
            ->count();
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        return $records;

    }

    public function get_bookings_done_from_branch($from_location)
    {
        $records = DB::table('booking as b')
            ->select('*')
            ->leftjoin('booking_cc_payment as bccp', 'b.id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->where('b.from_location', $from_location)
            ->count();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function get_bookings_cancelled($date, $operator = ">=")
    {
        $records = DB::table('booking_cancel as bc')
            ->leftjoin('booking as b', 'bc.booking_id', '=', 'b.id')
            ->leftjoin('booking_cc_payment as bccp', 'b.id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->whereDate('cancel_time', $operator, $date)
            ->select('bc.*')
            ->count();
        return $records;

    }

    public function get_bookings_total_sale($date, $operator = ">=")
    {
        $records = DB::table('booking_payment as bp')
            ->leftjoin('booking as b', 'bp.booking_id', '=', 'b.id')
            ->leftjoin('booking_cc_payment as bccp', 'b.id', '=', 'bccp.booking_id')
            ->leftjoin('booking_corporate_invoice as bci', 'b.id', '=', 'bci.booking_id')
            ->leftjoin('booking_sadad_payment as bsp', 'b.id', '=', 'bsp.s_booking_id')
            ->leftjoin('booking_individual_payment_method as bipm', 'b.id', '=', 'bipm.booking_id')
            ->whereRaw("(bccp.status='completed' OR bci.payment_status='paid' OR bsp.s_status='completed' OR bipm.payment_method='cash' or bipm.payment_method='Corporate Credit')")
            ->whereDate('b.created_at', $operator, $date)
            ->sum('bp.total_sum');
        return $records;

    }

    public function get_inquiries_count($date, $operator = ">=")
    {
        $records = DB::table('inquiries')
            ->whereDate('created_at', $operator, $date)
            ->select('*')
            ->count();
        return $records;

    }

    public function get_users_count($date, $operator = ">=")
    {
        $records = DB::table('users')
            ->where('type', '!=', 'admin')
            ->whereDate('created_at', $operator, $date)
            ->select('*')
            ->count();
        return $records;

    }

    public function getTokens()
    {
        $records = DB::table('survey_notification_tokens as snt')
            ->select('snt.*', DB::raw('CONCAT(ic.first_name," ",ic.last_name) as name'), 'ic.email as email', 'ic.id_no as id_number')
            ->leftjoin('individual_customer as ic', 'snt.customer_id', '=', 'ic.id')
            ->get();
        return $records;
    }


}