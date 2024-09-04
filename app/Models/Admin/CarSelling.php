<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;

class CarSelling extends Model
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
            $selectCols = " csr.*, csm.*, csb.eng_title as brand_title ";

            $join = " JOIN car_selling_model csm on csr.car_id = csm.id ";
            $join .= " JOIN car_selling_brand csb on csm.car_brand_id = csb.id ";
            $sort_by = "ORDER BY " . $sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }

        $records = DB::select("SELECT $selectCols FROM car_selling_response csr $join $sort_by $limit");
        return $records;
    }

    public function getDataToExport()
    {
        $selectCols = " csb.eng_title as CAR_BRAND, CONCAT(csm.eng_title, ' (', csm.year, ')') as CAR_MODEL, csr.name as CONTACT_NAME, csr.mobile_no as CONTACT_MOBILE_NO, csr.email as CONTACT_EMAIL, csr.created_at as RECEIVED_AT ";
        $join = " JOIN car_selling_model csm on csr.car_id = csm.id ";
        $join .= " JOIN car_selling_brand csb on csm.car_brand_id = csb.id ";

        $records = DB::select("SELECT $selectCols FROM car_selling_response csr $join");
        return $records;
    }

}