<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DropoffCharges extends Model {

	protected $table = 'dropoff_charges';
	//public $timestamps = true;

	public function getAllCarModels()
	{
		$models = DB::table('dropoff_charges')
			->select('*')
			->get();
		return $models;
	}

	public function getAllDropoffCharges($region_id, $city_id)
	{
		$models = DB::table('dropoff_charges')
			->join('city as from_city', 'dropoff_charges.city_from', '=', 'from_city.id')
			->join('city as to_city', 'dropoff_charges.city_to', '=', 'to_city.id')
			->where('dropoff_charges.city_from', $city_id)
			->select('dropoff_charges.*', 'from_city.eng_title as from_city', 'to_city.eng_title as to_city')
			->get();
		return $models;
	}

	public function saveData($data)
	{
		$id = DB::table('dropoff_charges')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('dropoff_charges')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('dropoff_charges')->where('id', $id)->delete();
	}

	public function checkIfConflictDataExist($data)
	{
		/*$records = DB::select("SELECT * FROM `dropoff_charges` where `city_from` = '".$data['city_from']."' and `city_to` = '".$data['city_to']."' and `price` = '".$data['price']."' and (
		(`applies_from` between '".$data['applies_from']."' and '".$data['applies_to']."')
		or (`applies_to` between '".$data['applies_from']."' and '".$data['applies_to']."')
		or ('".$data['applies_from']."' between `applies_from` and `applies_to`)
		or ('".$data['applies_to']."' between `applies_from` and `applies_to`)
		)");*/



        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/

        $records = DB::select("SELECT * FROM dropoff_charges 
        doc where doc.applies_from <= '".$data['applies_from']."' and 
        doc.applies_to >= '".$data['applies_from']."' and doc.city_from='".$data['city_from']."' 
        and doc.city_to='".$data['city_to']."'");


		return $records;
	}

	public function get_single_record($id)
	{

        $models = DB::table('dropoff_charges as dc')
            ->join('city as from_city', 'dc.city_from', '=', 'from_city.id')
            ->join('city as to_city', 'dc.city_to', '=', 'to_city.id')
            ->where('dc.id', $id)
            ->select('dc.*', 'from_city.eng_title as from_city', 'to_city.eng_title as to_city')
            ->first();
        return $models;

	}


}