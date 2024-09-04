<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarGroup extends Model {

	protected $table = 'car_group';
	//public $timestamps = true;

	public function getAllCarGroups($category_id,$count_only=false,$jtStartIndex="",$jtPageSize="",$sort_by="id",$sort_as="asc")
	{
        if($count_only){
            $groups = DB::table('car_group')
                ->where('car_category_id', $category_id)
                ->count();
        }else {
            $groups = DB::table('car_group')
                ->select('*')
                ->where('car_category_id', $category_id)
                ->orderBy($sort_by,$sort_as)
                ->offset($jtStartIndex)
                ->limit($jtPageSize)
                ->get();
        }
		return $groups;
	}

	public function saveData($data)
	{
		$id = DB::table('car_group')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('car_group')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('car_group')->where('id', $id)->delete();
	}

	public function checkIfGroupInUse($id)
	{
		$records = DB::table('car_type')
			->select('*')
			->where('car_group_id', $id)
			->get();
		return $records;
	}

}