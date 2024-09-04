<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarType extends Model {

	protected $table = 'car_type';
	//public $timestamps = true;

	public function getAllCarTypes($category_id,$count_only=false,$jtStartIndex="",$jtPageSize="",$sort_by="id",$sort_as="asc")
	{
        if($count_only){
            $types = DB::table('car_type')
                ->where('car_group_id', $category_id)
                ->count();
        }else {

            $types = DB::table('car_type')
                ->select('*')
                ->where('car_group_id', $category_id)
                ->orderBy($sort_by,$sort_as)
                ->offset($jtStartIndex)
                ->limit($jtPageSize)
                ->get();
        }
		return $types;
	}

	public function saveData($data)
	{
		$id = DB::table('car_type')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('car_type')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('car_type')->where('id', $id)->delete();
	}

    public function checkIfTypeInUse($id)
    {
        $records = DB::table('car_model')
            ->select('*')
            ->where('car_type_id', $id)
            ->get();
        return $records;
    }

    public function getAllCarTypesForDropdown()
    {
        $models = DB::table('car_type as ct')
            ->join('car_group as cg', 'ct.car_group_id', 'cg.id')
            ->join('car_category as cc', 'cg.car_category_id', 'cc.id')
            ->select(DB::raw('CONCAT(ct.eng_title, \' (\', cc.eng_title, \')\') as DisplayText'),'ct.id as Value')
            ->get();
        return $models;
    }

}