<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model {

	protected $table = 'branch';
	//public $timestamps = true;

	public function getAll($city_id,$count_only=false,$sort_by="",$jtStartIndex="",$jtPageSize="", $only_limousine_branches = 0)
	{

        $limit = "";
        $selectCols = "count(*) as tcount";
        if(!$count_only) {
            $selectCols = "*";
            $sort_by = "ORDER BY ".$sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }

        $where = "city_id = " . $city_id;
        if ($only_limousine_branches == 1) {
            $where .= " AND is_for_limousine_mode_only = 'yes'";
        } else {
            $where .= " AND is_for_limousine_mode_only = 'no'";
        }

        $query = "SELECT $selectCols FROM `branch` WHERE $where $sort_by $limit";

        $records = DB::select($query);
        return $records;

        //old code
       /* $models = DB::table('branch')
			->where('city_id', $city_id)
			->select('*')
			->get();
		/*$query = DB::getQueryLog();
		$lastQuery = end($query);
		print_r($lastQuery);
		exit();*/
		//return $models;*/
	}

	public function getAllSchedule($branch_id)
	{
		$records = DB::select("SELECT * FROM `branch_schedule` WHERE branch_id = $branch_id");
		return $records;
	}

	public function getAllScheduleDateRange($branch_id)
	{
		$records = DB::select("SELECT * FROM `branch_schedule_date_range` WHERE branch_id = $branch_id");
		return $records;
	}

	public function saveData($data)
	{
		$id = DB::table('branch')->insertGetId($data);
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('branch')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function updateSchedule($data, $id)
	{
		DB::table('branch_schedule')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function updateScheduleDateRange($data, $id)
	{
		DB::table('branch_schedule_date_range')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('branch')->where('id', $id)->delete();
	}

	public function checkIfBranchInUse($id)
	{
		$records = DB::table('booking')
			->select('*')
			->where('from_location', $id)
			->orWhere('to_location', $id)
			->get();
		return $records;
	}
	// checkIfBranchInUseInPricing
	public function checkIfBranchInUseInPricing($id)
	{
		$records = DB::table('car_price')
			->select('*')
			->where('branch_id', $id)
			->get();
		return $records;
	}
	// checkIfBranchInUseInPromotions
	public function checkIfBranchInUseInPromotions($id)
	{
		$records = DB::table('promotion_offer')
			->select('*')
			->where('branch_id', $id)
			->get();
		return $records;
	}

	public function getAllModels()
	{
		$models = DB::table('car_model')
			->where('active_status', '1')
			->select('*')
			->get();
		return $models;
	}

	public function saveAvailability($data)
	{
		$id = DB::table('car_availability')->insertGetId($data);
		return $id;
	}

	public function saveSchedule($data)
	{
		$id = DB::table('branch_schedule')->insertGetId($data);
		return $id;
	}
	public function saveScheduleDateRange($data)
	{
		$id = DB::table('branch_schedule_date_range')->insertGetId($data);
		return $id;
	}

	public function getBranchesByRegion($region_id)
	{
		$query = "select b.* FROM branch b LEFT JOIN city c ON b.city_id=c.id LEFT JOIN region r ON c.region_id=r.id AND r.id = $region_id";

		//echo $query;exit();
		$records = DB::select($query);
		if ($records) {
			return $records;
		} else {
			return false;
		}
	}


}