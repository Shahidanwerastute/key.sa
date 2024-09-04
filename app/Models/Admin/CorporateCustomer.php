<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CorporateCustomer extends Model {

    protected $table = 'corporate_customer';

    public $timestamps = true;


    public function getAll()
    {
        $admins = DB::table('corporate_customer')
            ->select('*')
            ->where('is_super','0')
            ->get();
        return $admins;
    }

    public function getAllSuper()
    {
        $admins = DB::table('corporate_customer')
            ->select('*')
            ->where('is_super','1')
            ->get();
        return $admins;
    }


    public function getSingleUserInfo($user_id)
    {
        $user_detail = DB::table('corporate_customer')
            ->select('*')
            ->where('id', $user_id)
            ->get();
        return $user_detail;
    }

    public function getAllCorporateCustomers()
    {
        $models = DB::table('corporate_customer')
            ->select('company_name_en as DisplayText','company_code as Value')
            ->get();
        return $models;
    }
    

}