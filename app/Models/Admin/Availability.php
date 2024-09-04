<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Availability extends Model {

	protected $table = 'booking_availability';
	public $timestamps = true;

    public function getAll()
    {
        $models = DB::table('booking_availability as ba')
            ->leftjoin('region as r', 'ba.region_id', '=', 'r.id')
            ->leftjoin('car_model as cm', 'ba.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'ba.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', 'cc.id')
            ->leftjoin('city as ci', 'ba.city_id', '=', 'ci.id')
            ->select('ba.*','ci.eng_title as city', 'r.eng_title as region_title',DB::raw('CONCAT(ct.eng_title, \' (\', cc.eng_title, \')\') as car_type_title'), DB::raw('CONCAT(cm.eng_title, \' \', cm.year) as car_model_title'))
            ->get();
        return $models;
    }

    public function getSingle($id)
    {
        $models = DB::table('booking_availability as ba')
            ->leftjoin('region as r', 'ba.region_id', '=', 'r.id')
            ->leftjoin('car_model as cm', 'ba.car_model_id', '=', 'cm.id')
            ->leftjoin('car_type as ct', 'ba.car_type_id', '=', 'ct.id')
            ->leftjoin('car_group as cg', 'ct.car_group_id', 'cg.id')
            ->leftjoin('car_category as cc', 'cg.car_category_id', 'cc.id')
            ->leftjoin('city as ci', 'ba.city_id', '=', 'ci.id')
            ->where('ba.id', $id)
            ->select('ba.*', 'r.eng_title as region_title',DB::raw('CONCAT(ct.eng_title, \' (\', cc.eng_title, \')\') as car_type_title'), DB::raw('CONCAT(cm.eng_title, \' \', cm.year) as car_model_title'))
            ->first();
        return $models;
    }

    public function checkDateConflict($data)
    {
        $models = DB::table('booking_availability')
            ->select('*')
            ->where('region_id',$data['region_id'])
            ->where('city_id',$data['city_id'])
            ->where('car_type_id',$data['car_type_id'])
            ->where('car_model_id',$data['car_model_id'])
            ->where('booking_per_day',$data['booking_per_day'])
            ->where('active_status',$data['active_status'])
            ->where(function($query) use ($data){
                if($data['to_date'] == '' || $data['to_date'] == null){
                    $query->whereRaw('from_date <= "'.$data['from_date'].'" AND to_date >= "'.$data['from_date'].'" ');
                }else{
                    $query->whereRaw('(from_date <= "'.$data['from_date'].'" AND to_date >= "'.$data['from_date'].'") OR 
                        (from_date <= "'.$data['to_date'].'" AND to_date >= "'.$data['to_date'].'") OR 
                        (from_date >= "'.$data['from_date'].'" AND to_date <= "'.$data['to_date'].'")
                    ');
                }
            })
            ->first();
        return $models?true:false;
    }

	public function saveData($data)
	{
		$id = DB::table('booking_availability')->insertGetId($data);
		return $id;
	}

	public function updateData($data, $id)
	{
        $updated = DB::table('booking_availability')
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
		DB::table('booking_availability')->where('id', $id)->delete();
	}
}