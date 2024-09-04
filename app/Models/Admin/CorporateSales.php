<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;

class CorporateSales extends Model
{

    public function getSingleRow($tbl, $get_by)
    {
        $record = DB::table($tbl)
            ->where($get_by)
            ->select('*')
            ->first();
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }

    public function get_all_car_models($brand_id)
    {
        $cards = DB::table("car_selling_model")
            ->select('*')
            ->where('car_brand_id', $brand_id)
            ->get();
        return $cards;

    }

    public function getAllResponses($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0)
    {

        $limit = "";
        $join = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {
            $selectCols = "*";

            $sort_by = "ORDER BY " . $sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }
        $records = DB::select("SELECT $selectCols FROM corporate_sales_response $join $sort_by $limit");

        return $records;
    }

    public function getDataToExport()
    {
        $records = DB::select("SELECT * FROM corporate_sales_response");
        return $records;
    }

}