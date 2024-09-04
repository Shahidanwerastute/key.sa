<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Redeem extends Model {

	protected $table = 'redeem_setup';
	public $timestamps = true;

	public function getAll()
	{
		$models = DB::table('redeem_setup as rs')
			->leftjoin('region as r', 'rs.region_id', '=', 'r.id')
            ->leftjoin('car_model as cm', 'rs.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'rs.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', 'cc.id')
			->select('rs.*', 'r.eng_title as region_title',DB::raw('CONCAT(ct.eng_title, \' (\', cc.eng_title, \')\') as car_type_title'), DB::raw('CONCAT(cm.eng_title, \' \', cm.year) as car_model_title'))
			->get();
		return $models;
	}

    public function getSingle($redeem_id)
    {
        $models = DB::table('redeem_setup as rs')
            ->leftjoin('region as r', 'rs.region_id', '=', 'r.id')
            ->leftjoin('car_model as cm', 'rs.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'rs.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', 'cc.id')
            ->where('rs.id', $redeem_id)
            ->select('rs.*', 'r.eng_title as region_title',DB::raw('CONCAT(ct.eng_title, \' (\', cc.eng_title, \')\') as car_type_title'), DB::raw('CONCAT(cm.eng_title, \' \', cm.year) as car_model_title'))
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
			->get();
		return $models;
	}

	public function saveData($data)
	{
		$id = DB::table('redeem_setup')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
        $updated = DB::table('redeem_setup')
            ->where('id', $id)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
	}

	public function deleteData($id)
	{
		DB::table('redeem_setup')->where('id', $id)->delete();
	}

	public function savePromoCode($data)
	{
		$id = DB::table('promotion_offer_coupon')->insertGetId($data);
		return $id;
	}

	public function updatePromoCode($data, $id)
	{

		DB::table('promotion_offer_coupon')
			->where('promotion_offer_id', $id)
			->update($data);

		return $id;
	}

	public function deletePromoCode($id)
	{
		DB::table('promotion_offer_coupon')->where('promotion_offer_id', $id)->delete();
	}

	public function checkIfConflictDataExist($data)
	{

        if ($data['type'] == "Fixed Price by Using Coupon" or $data['type'] == "Percentage by Using Coupon" or $data['type'] == "Fixed Daily Rate Coupon" or $data['type'] == "Percentage by Using Coupon on Loyalty" or $data['type'] == "Fixed Discount on Booking Total Using Coupon" or $data['type'] == "Percentage Discount on Booking Total Using Coupon" or $data['type'] == "Subscription - Fixed Discount on Booking Total Using Coupon") {
            $query = "SELECT * FROM promotion_offer cp join promotion_offer_coupon poc on cp.id=poc.promotion_offer_id  
            WHERE
            (
                (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
                OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

                if($data['applies_to']!=null)
                $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

                $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
            )            
            and cp.car_model_id='" . $data['car_model_id'] . "' and poc.code='" . $data['code'] ."' and cp.renting_type_id='".$data['renting_type_id']."'";


            if(isset($data['region_id']) && $data['region_id']!="")
                $query .= " and cp.region_id='".$data['region_id']."'";
            else
                $query .= " and cp.region_id=''";
            if(isset($data['city_id']) && $data['city_id']!="")
                $query .= " and cp.city_id='".$data['city_id']."'";
            else
                $query .= " and cp.city_id=''";
            if(isset($data['branch_id']) && $data['branch_id']!="")
                $query .= " and cp.branch_id='".$data['branch_id']."'";
            else
                $query .= " and cp.branch_id=''";
            if(isset($data['customer_type']) && $data['customer_type']!="")
                $query .= " and cp.customer_type='".$data['customer_type']."'";
            else
                $query .= " and cp.customer_type=''";

            if(isset($data['id']))//update case
                $query .= " and cp.id!='" . $data['id'] . "'";

        }else
        {
            $query = "SELECT * FROM promotion_offer cp WHERE                  
                (
                    (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
                    OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

                    if($data['applies_to']!=null)
                    $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

                    $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
                )
                and cp.car_model_id='".$data['car_model_id']."' and cp.renting_type_id='".$data['renting_type_id']."'";

            if(isset($data['region_id']) && $data['region_id']!="")
                $query .= " and cp.region_id='".$data['region_id']."'";
            else
                $query .= " and cp.region_id=''";
            if(isset($data['city_id']) && $data['city_id']!="")
                $query .= " and cp.city_id='".$data['city_id']."'";
            else
                $query .= " and cp.city_id=''";
            if(isset($data['branch_id']) && $data['branch_id']!="")
                $query .= " and cp.branch_id='".$data['branch_id']."'";
            else
                $query .= " and cp.branch_id=''";
            if(isset($data['customer_type']) && $data['customer_type']!="")
                $query .= " and cp.customer_type='".$data['customer_type']."'";
            else
                $query .= " and cp.customer_type=''";

            if(isset($data['id']))//update case
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
			->where('cph.promo_id', $id)
			->select('cph.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type')
			->get();
		//print_r($models); exit;
		return $priceHistory;
	}

	public function getPricing($applies_from, $car_model_id, $renting_type, $customer_type)
    {
        $query = "SELECT * FROM car_price WHERE applies_from <= '$applies_from' AND car_model_id = $car_model_id AND charge_element = 'Rent' AND renting_type_id = $renting_type AND (customer_type = '$customer_type' || customer_type = '') ORDER BY applies_from DESC LIMIT 1";
        //echo $query;exit();
        $records = DB::select($query);
        return $records;
    }


}