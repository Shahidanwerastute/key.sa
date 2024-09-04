<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model {

	protected $table = 'region';
	//public $timestamps = true;

	public function getAll($count_only=false,$sort_by="",$jtStartIndex="",$jtPageSize="")
	{

        $limit = "";
        $selectCols = "count(*) as tcount";
        if(!$count_only) {
            $selectCols = "*";
            $sort_by = "ORDER BY ".$sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }

        $records = DB::select("SELECT $selectCols FROM `region` $sort_by $limit");
        return $records;

	}

	public function getAllRegion()
	{
		$models = DB::table('region')
            ->select('eng_title as DisplayText','id as Value')
			->get();
		return $models;
	}

	public function saveData($data)
	{
		$id = DB::table('region')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('region')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('region')->where('id', $id)->delete();
	}

    public function checkIfRegionInUse($id)
    {
        $records = DB::table('city')
            ->select('*')
            ->where('region_id', $id)
            ->get();
        return $records;
    }

	public function checkIfRegionInUseInPricing($id)
	{
		$records = DB::table('car_price')
			->select('*')
			->where('region_id', $id)
			->get();
		return $records;
	}

	public function checkIfRegionInUseInPromotions($id)
	{
		$records = DB::table('promotion_offer')
			->select('*')
			->where('region_id', $id)
			->get();
		return $records;
	}

}