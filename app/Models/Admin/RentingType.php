<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RentingType extends Model {

	protected $table = 'setting_renting_type';
	//public $timestamps = true;

	public function getAll()
	{
		$models = DB::table('setting_renting_type')
			->select('*')
			->get();
		return $models;
	}

    public function getAllOptions()
    {
        $renting_type = DB::table('setting_renting_type')
            ->select('type as DisplayText','id as Value')
            ->orderBy('sort')->get();
        return $renting_type;
    }

    public function getAllCities(){
        $allCities = DB::table('city')
            ->select('eng_title as DisplayText','id as Value')->get();
        return $allCities;
    }

    public function getAllBranches($is_limousine = 0){

	    if ($is_limousine == 1) {
            $allBranches = DB::table('branch')->where('is_for_limousine_mode_only', 'yes')
                ->select('eng_title as DisplayText','id as Value')->get();
        } else {
            $allBranches = DB::table('branch')
                ->select('eng_title as DisplayText','id as Value')->get();
        }
        return $allBranches;
    }

    public function getAllCitiesById($region_id){
        $allCities = DB::table('city')
            ->where('region_id',$region_id)
            //->select('eng_title as DisplayText','id as Value')->get();
            ->select('eng_title as text','id as value')->get();
        return $allCities;
    }

    public function getAllBranchesById($city_id){
        $allBranches = DB::table('branch')
            ->where('city_id',$city_id)
            ->where('is_for_limousine_mode_only','no')
            //->select('eng_title as DisplayText','id as Value')->get();
            ->select('eng_title as text','id as value')->get();
        return $allBranches;
    }

	public function saveData($data)
	{
		$id = DB::table('setting_renting_type')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('setting_renting_type')
			->where('id', $id)
			->update($data);
		return $id;
	}

	
	public function deleteData($id)
	{
		DB::table('setting_renting_type')->where('id', $id)->delete();
	}

}