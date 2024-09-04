<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Custom;
use DB;

class LimousineChargesController extends Controller {

    public function getAll()
    {
        $rows = array();
        $records = DB::table('car_price')->leftjoin('users', 'car_price.created_by', '=', 'users.id')->where('is_for_limousine_mode_only', 'yes')->where('car_model_id', $_REQUEST['model_id'])->select('car_price.*', 'users.name as created_by')->get();
        foreach ($records as $record) {
            $rows[] = $record;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveData(Request $request)
    {
        $data = $request->input();
        /*$data = custom::isNullToEmpty($data);
        if($data['applies_to'] == ""){
            $data['applies_to'] = null;
        }*/
        $result = DB::table('car_price')->where('branch_id', $data['branch_id'])->where('to_branch', $data['to_branch'])->where('car_model_id', $data['car_model_id'])->count();
        if ($result > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this from and to branch for this car model.';
        } else {

            $branch = DB::table('branch')->where('id', $data['branch_id'])->first();
            $city = DB::table('city')->where('id', $branch->city_id)->first();
            $region = DB::table('region')->where('id', $city->region_id)->first();

            $price_data['car_model_id'] = $data['car_model_id'];
            $price_data['charge_element'] = 'Rent';
            $price_data['renting_type_id'] = 1;
            $price_data['price'] = 1;
            $price_data['region_id'] = $region->id;
            $price_data['city_id'] = $city->id;
            $price_data['branch_id'] = $data['branch_id'];
            $price_data['is_for_limousine_mode_only'] = 'yes';
            $price_data['rate_per_round_trip'] = $data['rate_per_round_trip'];
            $price_data['rate_per_one_trip'] = $data['rate_per_one_trip'];
            $price_data['extra_hours_rate_for_limousine'] = $data['extra_hours_rate_for_limousine'];
            $price_data['to_branch'] = $data['to_branch'];
            $price_data['applies_from'] = date('Y-m-d H:i:s');
            $price_data['customer_type'] = 'Corporate';
            $price_data['company_code'] = 0;
            $price_data['created_at'] = date('Y-m-d H:i:s');
            $price_data['created_by'] = auth()->user()->id;

            $savedId = DB::table('car_price')->insertGetId($price_data);
            $responseData = DB::table('car_price')->leftjoin('users', 'car_price.created_by', '=', 'users.id')->where('car_price.id', $savedId)->select('car_price.*', 'users.name as created_by')->first();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }
        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        DB::table('car_price')->where('id', $id)->update($data);
        $responseData = DB::table('car_price')->leftjoin('users', 'car_price.created_by', '=', 'users.id')->where('car_price.id', $id)->select('car_price.*', 'users.name as created_by')->first();
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        DB::table('car_price')->where('id', $id)->delete();
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


}

?>