<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarModel extends Model
{

    protected $table = 'car_model';

    //public $timestamps = true;

    public function getAllCarModels($type_id, $count_only, $jtStartIndex = "", $jtPageSize = "", $sort_by = "id", $sort_as = "asc")
    {

        if ($count_only) {
            $models = DB::table('car_model')
                ->where('car_type_id', $type_id)
                ->count();
        } else {

            $models = DB::table('car_model')
                ->select('*')
                ->where('car_type_id', $type_id)
                ->orderBy($sort_by, $sort_as)
                ->offset($jtStartIndex)
                ->limit($jtPageSize)
                ->get();
        }
        return $models;
    }

    public function saveData($data)
    {
        $id = DB::table('car_model')->insertGetId($data);
        return $id;
    }

    public function updateData($data, $id)
    {
        DB::table('car_model')
            ->where('id', $id)
            ->update($data);
        return $id;
    }

    public function deleteData($id)
    {
        DB::table('car_model')->where('id', $id)->delete();
    }

    public function checkIfCarInUse($id)
    {
        /*$records = DB::select("SELECT cm.id FROM car_model cm LEFT JOIN car_price ep ON cm.id=ep.car_model_id LEFT JOIN promotion_offer promo ON cm.id=promo.car_model_id LEFT JOIN bookings b ON cm.id=b.car_model_id WHERE cm.id='".$id."' and (b.car_model_id='".$id."' or ep.car_model_id='".$id."' or promo.car_model_id='".$id."') group by cm.id");*/
        // left join booking_individual_user biu on b.id=biu.booking_id and b.type='individual_customer'
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();*/
        $records = DB::table('car_price')
            ->select('*')
            ->where('car_model_id', $id)
            ->get();
        return $records;
    }

    public function getAllBranches()
    {
        $models = DB::table('branch')
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

    public function getAllModelsByType($car_type_id){
        $allCities = DB::table('car_model')
            ->where('car_type_id',$car_type_id)
            //->select('eng_title as DisplayText','id as Value')->get();
            ->select(DB::raw('CONCAT(eng_title,\' \',year) as text'), 'id as value')->get();
        return $allCities;
    }

    public function getAllCarModelsForDropdown()
    {
        $models = DB::table('car_model as cm')
            ->join('car_type as ct', 'cm.car_type_id', 'ct.id')
            ->where('cm.active_status', '1')
            ->select(DB::raw('CONCAT(ct.eng_title,\' \',cm.eng_title,\' \',cm.year) as DisplayText'), 'cm.id as Value')->orderBy('ct.eng_title', 'ASC')->get();
        return $models;
    }

}