<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;

class Promotions extends Model
{

    protected $table = 'promotion_offer';
    public $timestamps = true;

    public function getAll($expired, $sorting, $offset, $limit)
    {
        $sorting = explode(' ', $sorting);
        if ($expired == 1) {
            $models = DB::table('promotion_offer as po')
                ->leftjoin('promotion_offer_coupon as poc', 'po.id', '=', 'poc.promotion_offer_id')
                ->leftjoin('users as u', 'po.created_by', '=', 'u.id')
                ->where('po.applies_to', '<', date('Y-m-d H:i:s'))
                ->select('po.*', 'poc.*', 'u.name as created_by')
                ->offset($offset)
                ->limit($limit)
                ->groupBy('po.id')
                ->orderBy($sorting[0], $sorting[1])
                ->get();
        } else {
            $models = DB::table('promotion_offer as po')
                ->leftjoin('promotion_offer_coupon as poc', 'po.id', '=', 'poc.promotion_offer_id')
                ->leftjoin('users as u', 'po.created_by', '=', 'u.id')
                ->where(function ($query) {
                    $query->where('po.applies_to', '>=', date('Y-m-d H:i:s'))
                        ->orWhere('po.applies_to', null);
                })
                ->select('po.*', 'poc.*', 'u.name as created_by')
                ->offset($offset)
                ->limit($limit)
                ->groupBy('po.id')
                ->orderBy($sorting[0], $sorting[1])
                ->get();
        }
        return $models;
    }

    public function getAllCount($expired)
    {
        if ($expired == 1) {
            $models = DB::table('promotion_offer as po')
                ->where('po.applies_to', '<', date('Y-m-d H:i:s'))
                ->select('po.*')
                ->count();
        } else {
            $models = DB::table('promotion_offer as po')
                    ->where(function ($query) {
                        $query->where('po.applies_to', '>=', date('Y-m-d H:i:s'))
                            ->orWhere('po.applies_to', null);
                    })
                    ->select('po.*')
                    ->count();
        }
        return $models;
    }

    public function getSingle($promotion_id)
    {
        $models = DB::table('promotion_offer as po')
            ->leftjoin('promotion_offer_coupon as poc', 'po.id', '=', 'poc.promotion_offer_id')
            ->where('po.id', $promotion_id)
            ->select('po.*', 'poc.*')
            ->groupBy('po.id')
            ->first();
        return $models;
    }

    public function getSinlgeDetail($promo_id)
    {
        $models = DB::table('promotion_offer as po')
            ->leftjoin('promotion_offer_coupon as poc', 'po.id', '=', 'poc.promotion_offer_id')
            ->leftjoin('region as r', 'po.region_id', '=', 'r.id')
            ->leftjoin('city as c', 'po.city_id', '=', 'c.id')
            ->leftjoin('branch as b', 'po.branch_id', '=', 'b.id')
            ->where('po.id', $promo_id)
            ->select('po.*', 'poc.code as promo_code', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch')
            ->groupBy('po.id')
            ->get();
        return $models;
    }

    public function saveData($data)
    {
        $id = DB::table('promotion_offer')->insertGetId($data);
        return $id;
    }

    public function updateData($data, $id)
    {
        DB::table('promotion_offer')
            ->where('id', $id)
            ->update($data);
        return $id;
    }

    public function deleteData($id)
    {
        DB::table('promotion_offer_coupon')->where('promotion_offer_id', $id)->delete();
        DB::table('promotion_offer')->where('id', $id)->delete();
    }

    public function checkIfConflictDataExist($data)
    {
        /*$records = DB::select("SELECT * FROM `promotion_offer` where `car_model_id` = '".$data['car_model_id']."' and `type` = '".$data['type']."' and `discount` = '".$data['discount']."' and `region_id` = '".$data['region_id']."' and `city_id` = '".$data['city_id']."' and `branch_id` = '".$data['branch_id']."' and `customer_type` = '".$data['customer_type']."' and (
        (`applies_from` between '".$data['applies_from']."' and '".$data['applies_to']."')
        or (`applies_to` between '".$data['applies_from']."' and '".$data['applies_to']."')
        or ('".$data['applies_from']."' between `applies_from` and `applies_to`)
        or ('".$data['applies_to']."' between `applies_from` and `applies_to`)
        )");*/

        if ($data['type'] == "Fixed Price by Using Coupon" or $data['type'] == "Percentage by Using Coupon" or $data['type'] == "Fixed Daily Rate Coupon" or $data['type'] == "Percentage by Using Coupon on Loyalty" or $data['type'] == "Fixed Discount on Booking Total Using Coupon" or $data['type'] == "Percentage Discount on Booking Total Using Coupon" or $data['type'] == "Subscription - Fixed Discount on Booking Total Using Coupon") {
            $query = "SELECT * FROM promotion_offer cp join promotion_offer_coupon poc on cp.id=poc.promotion_offer_id  
            WHERE
            (
                (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
                OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

            if ($data['applies_to'] != null)
                $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

            $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
            )";

            if ($data['no_of_coupons'] == 'Unlimited') {
                $query .= " and poc.code='" . $data['code'] . "'";
            } elseif ($data['no_of_coupons'] == 'Series') {
                $query .= " and cp.coupon_prefix='" . $data['coupon_prefix'] . "'";
            }

            $query .= " and cp.renting_type_id=" . $data['renting_type_id'];


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

        } else {
            $query = "SELECT * FROM promotion_offer cp WHERE                  
                (
                    (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
                    OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

            if ($data['applies_to'] != null)
                $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

            $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
                )
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

        }

        //echo $query; exit();
        $records = DB::select($query);

        return $records;
    }


    public function checkIfInUse($id)
    {
        $records = DB::table('booking_payment')
            ->select('*')
            ->where('promotion_offer_id', $id)
            ->get();
        return $records;
    }

    public function getPromotionHistory($id)
    {
        $priceHistory = DB::table('promotion_offer_history as cph')
            ->leftjoin('setting_renting_type as rt', 'cph.renting_type_id', '=', 'rt.id')
            ->leftjoin('region as r', 'cph.region_id', '=', 'r.id')
            ->leftjoin('city as c', 'cph.city_id', '=', 'c.id')
            ->leftjoin('branch as b', 'cph.branch_id', '=', 'b.id')
            ->leftjoin('users as u', 'cph.created_by', '=', 'u.id')
            ->where('cph.promo_id', $id)
            ->select('cph.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
            ->get();
        //print_r($models); exit;
        return $priceHistory;
    }


}