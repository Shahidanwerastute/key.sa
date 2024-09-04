<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class City extends Model {

	protected $table = 'city';
	//public $timestamps = true;

	public function getAll($region_id,$count_only=false,$sort_by="",$jtStartIndex="",$jtPageSize="")
	{
		/*$models = DB::table('city')
			->where('region_id', $region_id)
			->select('*')
			->get();
		/*$query = DB::getQueryLog();
		$lastQuery = end($query);
		print_r($lastQuery);
		exit();*/
		//return $models;*/

        $limit = "";
        $selectCols = "count(*) as tcount";
        if(!$count_only) {
            $selectCols = "*";
            $sort_by = "ORDER BY ".$sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }

        $records = DB::select("SELECT $selectCols FROM `city` WHERE region_id = $region_id $sort_by $limit");
        return $records;




    }

	/*public function getAllCities()
	{
		$models = DB::table('city')
			->select('*')
			->groupBy('eng_title')
			->get();
		return $models;
	}*/

    public function getAllCities($formCityId=0)
    {

        $query = DB::table('city');
        $query->select('eng_title as DisplayText','id as Value');
        if($formCityId !== 0)
            $query->where('id','!=',$formCityId);
        $cities = $query->get();
        return $cities;
    }

	public function saveData($data)
	{
		$id = DB::table('city')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('city')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('city')->where('id', $id)->delete();
	}

    public function checkIfCityInUse($id)
    {
        $records = DB::table('branch')
            ->select('*')
            ->where('city_id', $id)
            ->get();
        return $records;
    }

    public function checkIfCityInUseInPricing($id)
    {
        $records = DB::table('car_price')
            ->select('*')
            ->where('city_id', $id)
            ->get();
        return $records;
    }

    public function checkIfCityInUseInPromotions($id)
    {
        $records = DB::table('promotion_offer')
            ->select('*')
            ->where('city_id', $id)
            ->get();
        return $records;
    }

    public function getAllCitiesByRegion($region_id)
    {
        $allCities = DB::table('city')
            ->where('region_id',$region_id)
            ->select(DB::raw('CONCAT(eng_title) as text'), 'id as value')->get();

        return $allCities;
    }

}