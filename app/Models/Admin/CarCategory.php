<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarCategory extends Model {

	protected $table = 'car_category';
	//public $timestamps = true;

	public function getAllCarCategories($count_only=false,$jtStartIndex="",$jtPageSize="",$sort_by="id",$sort_as="asc")
	{
	    if($count_only){
            $categories = DB::table('car_category')->count();
        }else {

            $categories = DB::table('car_category')
                ->select('*')
                ->orderBy($sort_by,$sort_as)
                ->offset($jtStartIndex)
                ->limit($jtPageSize)
                ->get();
        }
		return $categories;
	}

	public function saveData($data)
	{
		/*echo 'in model';
		print_r($data);
		exit();*/
		$id = DB::table('car_category')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
		DB::table('car_category')
			->where('id', $id)
			->update($data);
		return $id;
	}

	public function deleteData($id)
	{
		DB::table('car_category')->where('id', $id)->delete();
	}

	// checkIfCategoryInUse
	public function checkIfCategoryInUse($id)
	{
		$records = DB::table('car_group')
			->select('*')
			->where('car_category_id', $id)
			->get();
		return $records;
	}

}