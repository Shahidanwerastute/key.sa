<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pricing extends Model {

	protected $table = 'car_price';
	//public $timestamps = true;


	/*$query = DB::getQueryLog();
		$lastQuery = end($query);
		print_r($lastQuery);
		exit();*/


	public function getAllCarModels($count_only=false,$jtStartIndex="",$jtPageSize="",$sort_by="id",$sort_as="asc")
	{

        if($count_only){
            /*$models = DB::table('car_model')
                ->join('car_type','car_model.car_type_id','=','car_type.id')
                ->select('*')
                ->get();*/

            $models = DB::select('select count(cm.id) as count from car_type ct join car_model cm on ct.id=cm.car_type_id');

        }else {

            $models = DB::table('car_model')
                ->join('car_type','car_model.car_type_id','=','car_type.id')
                ->select('car_model.*','car_type.eng_title as type_eng_title')
                ->orderBy($sort_by,$sort_as)
                ->offset($jtStartIndex)
                ->limit($jtPageSize)
                ->get();

        }
		return $models;
	}

	public function getAllPricesForModel($model_id, $expired = false)
	{
        if ($expired) {
            $models = DB::table('car_price as cp')
                ->leftjoin('setting_renting_type as rt', 'cp.renting_type_id', '=', 'rt.id')
                ->leftjoin('region as r', 'cp.region_id', '=', 'r.id')
                ->leftjoin('city as c', 'cp.city_id', '=', 'c.id')
                ->leftjoin('branch as b', 'cp.branch_id', '=', 'b.id')
                ->leftjoin('users as u', 'cp.created_by', '=', 'u.id')
                ->where('car_model_id', $model_id)
                ->where('charge_element', 'Rent')
                ->where('cp.is_for_limousine_mode_only', 'no')
                ->where('cp.applies_to', '<', date('Y-m-d'))
                ->select('cp.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
                ->get();
        } else {
            $models = DB::table('car_price as cp')
                ->leftjoin('setting_renting_type as rt', 'cp.renting_type_id', '=', 'rt.id')
                ->leftjoin('region as r', 'cp.region_id', '=', 'r.id')
                ->leftjoin('city as c', 'cp.city_id', '=', 'c.id')
                ->leftjoin('branch as b', 'cp.branch_id', '=', 'b.id')
                ->leftjoin('users as u', 'cp.created_by', '=', 'u.id')
                ->where('car_model_id', $model_id)
                ->where('charge_element', 'Rent')
                ->where('cp.is_for_limousine_mode_only', 'no')
                ->where(function ($query) {
                    $query->where('cp.applies_to', '>=', date('Y-m-d'))
                        ->orWhere('cp.applies_to', null);
                })
                ->select('cp.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
                ->get();
        }
		return $models;
	}

    public function getPriceHistory($id)
    {
        $priceHistory = DB::table('car_price_history as cph')
            ->leftjoin('setting_renting_type as rt', 'cph.renting_type_id', '=', 'rt.id')
            ->leftjoin('region as r', 'cph.region_id', '=', 'r.id')
            ->leftjoin('city as c', 'cph.city_id', '=', 'c.id')
            ->leftjoin('branch as b', 'cph.branch_id', '=', 'b.id')
            ->leftjoin('users as u', 'cph.created_by', '=', 'u.id')
            ->where('cph.car_price_id', $id)
            ->select('cph.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
            ->get();
        //print_r($models); exit;
        return $priceHistory;
    }



	public function getAllExtrasForModel($model_id, $expired = false)
	{
	    if ($expired) {
            $models = DB::table('car_price as cp')
                ->leftjoin('setting_renting_type as rt', 'cp.renting_type_id', '=', 'rt.id')
                ->leftjoin('region as r', 'cp.region_id', '=', 'r.id')
                ->leftjoin('city as c', 'cp.city_id', '=', 'c.id')
                ->leftjoin('branch as b', 'cp.branch_id', '=', 'b.id')
                ->leftjoin('users as u', 'cp.created_by', '=', 'u.id')
                ->where('car_model_id', $model_id)
                ->where('charge_element', '!=', 'Rent')
                ->where('cp.applies_to', '<', date('Y-m-d'))
                ->select('cp.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
                ->get();
        } else {
            $models = DB::table('car_price as cp')
                ->leftjoin('setting_renting_type as rt', 'cp.renting_type_id', '=', 'rt.id')
                ->leftjoin('region as r', 'cp.region_id', '=', 'r.id')
                ->leftjoin('city as c', 'cp.city_id', '=', 'c.id')
                ->leftjoin('branch as b', 'cp.branch_id', '=', 'b.id')
                ->leftjoin('users as u', 'cp.created_by', '=', 'u.id')
                ->where('car_model_id', $model_id)
                ->where('charge_element', '!=', 'Rent')
                ->where(function ($query) {
                    $query->where('cp.applies_to', '>=', date('Y-m-d'))
                        ->orWhere('cp.applies_to', null);
                })
                ->select('cp.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type', 'u.name as created_by')
                ->get();
        }
		return $models;
	}


	public function saveData($data)
	{
		$id = DB::table('car_price')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('car_price')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('car_price')->where('id', $id)->delete();
	}

	public function checkIfConflictDataExist($data,$id="")
	{
		/*$records = DB::select("SELECT * FROM `car_price` where `car_model_id` = '".$data['car_model_id']."' and `charge_element` = '".$data['charge_element']."' and `renting_type_id` = '".$data['renting_type_id']."' and `region_id` = '".$data['region_id']."' and `city_id` = '".$data['city_id']."' and `branch_id` = '".$data['branch_id']."' and `customer_type` = '".$data['customer_type']."' and `price` = '".$data['price']."' and (
		(`applies_from` between '".$data['applies_from']."' and '".$data['applies_to']."')
		or (`applies_to` between '".$data['applies_from']."' and '".$data['applies_to']."')
		or ('".$data['applies_from']."' between `applies_from` and `applies_to`)
		or ('".$data['applies_to']."' between `applies_from` and `applies_to`)
		)");*/

		/*$records = DB::table('car_price')
			->where('charge_element', $data['charge_element'])
			->where('renting_type_id', $data['renting_type_id'])
			->where('region_id', $data['region_id'])
			->where('city_id', $data['city_id'])
			->where('branch_id', $data['branch_id'])
			->where('car_model_id', $data['car_model_id'])
			->where('customer_type', $data['customer_type'])
			->where('price', $data['price'])
			->orWhere(function ($query, $data) {
					$query->whereBetween('applies_from', [$data['applies_from'], $data['applies_to']])
					->whereBetween('applies_to', [$data['applies_from'], $data['applies_to']])
					->whereBetween($data['applies_from'], ['applies_from', 'applies_to'])
					->whereBetween($data['applies_to'], ['applies_from', 'applies_to']);
			})*/
			/*->whereBetween('applies_from', [$data['applies_from'], $data['applies_to']])
			->whereBetween('applies_to', [$data['applies_from'], $data['applies_to']])
			->where('applies_from', '>=', $data['applies_from'])
			->where('applies_to', '<=', $data['applies_to'])*/

			/*->select('*')
			->get();*/
		//echo count($records);exit();
		/*$query = DB::getQueryLog();
		$lastQuery = end($query);
		print_r($lastQuery);
		exit();*/

        $query = "SELECT * FROM car_price cp 
        WHERE
        (
        (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_to'] . "') 
            OR (cp.applies_to >= '" . $data['applies_from'] . "' AND cp.applies_to <= '" . $data['applies_to'] . "')";

            if($data['applies_to']!=null)
            $query .= " OR (cp.applies_from <= '" . $data['applies_from'] . "' AND cp.applies_to >= '" . $data['applies_to'] . "')";

            $query .= "OR (cp.applies_from >= '" . $data['applies_from'] . "' AND cp.applies_from <= '" . $data['applies_from'] . "')
        )        
        and cp.car_model_id='".$data['car_model_id']."' and charge_element='".$data['charge_element']."' 
        and cp.renting_type_id='".$data['renting_type_id']."'";

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
        /*if company_code enter or not check here*/
        if(isset($data['company_code']) && $data['company_code']!="")
            $query .= " and cp.company_code='".$data['company_code']."'";
        else
            $query .= " and cp.company_code=''";

        if(isset($data['id'])) //update case
            $query .= " and cp.id!='" . $data['id'] . "'";

        if($id != ""){
            $query .= " and cp.id != $id";
        }

        $records = DB::select($query);

		return $records;
	}

	public function get_single_record($id)
	{

		$models = DB::table('car_price as cp')
			->leftjoin('setting_renting_type as rt', 'cp.renting_type_id', '=', 'rt.id')
			->leftjoin('region as r', 'cp.region_id', '=', 'r.id')
			->leftjoin('city as c', 'cp.city_id', '=', 'c.id')
			->leftjoin('branch as b', 'cp.branch_id', '=', 'b.id')
			->where('cp.id', $id)
			->select('cp.*', 'r.eng_title as region', 'c.eng_title as city', 'b.eng_title as branch', 'rt.type as renting_type')
			->first();
		return $models;

	}

	public function getAllBranches()
	{
		$models = DB::table('branch as b')
			->leftjoin('city as c', 'b.city_id', '=', 'c.id')
			->leftjoin('region as r', 'c.region_id', '=', 'r.id')
			->select('b.eng_title as branch_title', 'b.id as branch_id', 'c.eng_title as city_title', 'r.eng_title as region_title', 'b.is_for_limousine_mode_only')
			->get();
		return $models;
	}

	public function deleteAvailability($car_model_id)
	{
		DB::table('car_availability')->where('car_model_id', $car_model_id)->delete();
	}

	public function saveAvailability($data)
	{
		$id = DB::table('car_availability')->insertGetId($data);
		return $id;
	}

	public function updateAvailability($data)
	{
        DB::table('car_availability')
            ->where('branch_id', $data['branch_id'])
            ->where('car_model_id', $data['car_model_id'])
            ->update($data);
	}

	public function checkIfAvailbleForBranch($car_model_id, $branch_id)
	{
		$models = DB::table('car_availability')
			->where('car_model_id', $car_model_id)
			->where('branch_id', $branch_id)
			->select('*')
			->get();
		return $models;
	}


	public function getCarAllModels($filter)
	{

		$query = "select b.id as branch_id, b.city_id as city_id FROM branch b JOIN city c ON b.city_id=c.id and (b.eng_title LIKE \"%$filter%\" OR b.arb_title LIKE \"%$filter%\" OR c.eng_title LIKE \"%$filter%\" OR c.arb_title LIKE \"%$filter%\")
UNION
select b.id as branch_id, b.city_id as city_id FROM branch b JOIN car_availability ca ON b.id=ca.branch_id JOIN car_model cm ON ca.car_model_id=cm.id JOIN car_type ct ON cm.car_type_id=ct.id and (cm.eng_title LIKE \"%$filter%\" OR cm.arb_title LIKE \"%$filter%\" OR ct.eng_title LIKE \"%$filter%\" OR ct.arb_title LIKE \"%$filter%\" OR CONCAT (ct.eng_title, ' ', cm.eng_title) LIKE \"%$filter%\" OR CONCAT (ct.arb_title, ' ', cm.arb_title) LIKE \"%$filter%\" OR CONCAT (ct.eng_title, cm.eng_title) LIKE \"%$filter%\" OR CONCAT (ct.arb_title, cm.arb_title) LIKE \"%$filter%\")";

		//echo $query;exit();
		$records = DB::select($query);
		if ($records)
		{
			return $records;
		}else{
			return false;
		}
	}

	


}