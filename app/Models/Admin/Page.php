<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Custom;

class Page extends Model
{

    //public $timestamps = true;

    public function getAll($tbl, $orderBy = 'id', $sort = 'asc')
    {
        $records = DB::table($tbl)
            ->select('*')
            ->orderBy($orderBy, $sort)
            ->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

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

    public function getMultipleRows($tbl, $get_by, $orderBy = 'id', $sort = 'asc')
    {
        $records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            ->orderBy($orderBy, $sort)
            ->get();
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getMultipleRowsCount($tbl, $get_by, $orderBy = 'id', $sort = 'asc')
    {
        $records = DB::table($tbl)
            ->select('*')
            ->where($get_by)
            ->orderBy($orderBy, $sort)
            ->get();
        if ($records) {
            return count($records);
        } else {
            return false;
        }
    }

    public function saveData($tbl, $data)
    {
        $tbl_sec = str_replace('_', ' ', $tbl);
        $tbl_sec = ucfirst($tbl_sec);
        $section = 'Pages ('.$tbl_sec.')';
        custom::log($section,'add');

        $savedId = DB::table($tbl)->insertGetId($data);
        if ($savedId > 0) {
            return $savedId;
        } else {
            return false;
        }
    }

    public function updateData($tbl, $data, $update_by)
    {
        $tbl_sec = str_replace('_', ' ', $tbl);
        $tbl_sec = ucfirst($tbl_sec);
        $section = 'Pages ('.$tbl_sec.')';
        custom::log($section, 'update');

        $updated = DB::table($tbl)
            ->where($update_by)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData($tbl, $delete_by)
    {
        $tbl_sec = str_replace('_', ' ', $tbl);
        $tbl_sec = ucfirst($tbl_sec);
        $section = 'Pages ('.$tbl_sec.')';
        custom::log($section, 'delete');

        $deleted = DB::table($tbl)
            ->where($delete_by)
            ->delete();
        if ($deleted) {
            return true;
        } else {
            return false;
        }
    }

// kashif work
    public function getAllInquiries($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0)
    {
        $limit = "";
        $join = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {
            $selectCols = " inquiries.*, country.eng_country, setting_inquiry_type.eng_title as inquiry_type_title ";
            $join = " JOIN country on inquiries.country = country.oracle_reference_number ";
            $join .= " JOIN setting_inquiry_type on inquiries.inquiry_type_id = setting_inquiry_type.id ";
            $sort_by = "ORDER BY " . $sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }

        $records = DB::select("SELECT $selectCols FROM `inquiries` $join $sort_by $limit");
        return $records;
    }

    public function getInquiryMsg($id)
    {
        $message = DB::table('inquiries')->select('message')->where('id', $id)->get();
        return $message;
    }

    public function getAllCareers($count_only = false, $sort_by = "", $jtStartIndex = 0, $jtPageSize = 0)
    {
        $limit = "";
        $selectCols = "count(*) as tcount";
        if (!$count_only) {
            $selectCols = "career_inquiry.*, department.eng_title as department";
            $sort_by = "ORDER BY " . $sort_by;
            $limit = "LIMIT " . $jtStartIndex . "," . $jtPageSize;
        }
        $records = DB::select("SELECT $selectCols FROM `career_inquiry` left join `department` ON `department`.`id` = `career_inquiry`.`department_id` $sort_by $limit");
        return $records;
    }
    public function getAllCareersForExport($startDate = '', $endDate = '')
    {
        $limit = "";
        $where = " where ";
        $selectCols = "department.eng_title as department, career_inquiry.name, career_inquiry.dob, career_inquiry.nationality, career_inquiry.email, career_inquiry.id_number, career_inquiry.mobile, career_inquiry.city, career_inquiry.language, career_inquiry.qualification, career_inquiry.company_name, career_inquiry.job_title, career_inquiry.from_date, career_inquiry.to_date, career_inquiry.linkedin_profile_url, career_inquiry.created_at as received_at";
        if (!empty($startDate)) {
            if(!empty($endDate))
            {
                $where .= "created_at BETWEEN '".$startDate."'" . " AND '".$endDate."'";
            }
            else
            {
                $where .= "created_at >= '".$startDate."'";
            }
        }
        else
        {
            $where .= "created_at <= '".$endDate."'";
        }

        $records = DB::select("SELECT $selectCols FROM `career_inquiry` left join `department` ON `department`.`id` = `career_inquiry`.`department_id` $where");

        return $records;
    }

    public function getCareerDetail($id)
    {
        $remainingInfo = DB::table('career_inquiry as ci')
            ->join('department as dp', 'ci.department_id', '=', 'dp.id')
            ->select('ci.*', 'dp.eng_title', 'dp.arb_title')
            ->where('ci.id', $id)->get();
        return $remainingInfo;
    }

    // end kashif work

    // validation rules function
    public function checkValidation($inputs)
    {
        foreach ($inputs as $key => $value) {
            if ($value != "") {
                return true;
            } else {
                return false;
            }
        }

    }

    public function getAllAdmins()
    {
        $record = DB::table('users as u')
            ->leftjoin('admin_role as ar', 'u.id', '=', 'ar.uid')
            ->leftjoin('setting_user_role as sur', 'ar.role_id', '=', 'sur.id')
            ->select('u.*', 'sur.name as admin_role')
            ->where('u.type', 'admin')
            ->get();

        return $record;
    }

    public function getCarModelsByCategory($car_category)
    {
        $query = "select cm.* FROM car_model cm LEFT JOIN car_type ct ON cm.car_type_id=ct.id LEFT JOIN car_group cg ON ct.car_group_id=cg.id LEFT JOIN car_category cc ON cg.car_category_id=cc.id AND cc.id = $car_category";

        //echo $query;exit();
        $records = DB::select($query);
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }


    public function getCarModelsByGroup($car_category, $car_group)
    {
        $query = "select cm.* FROM car_model cm LEFT JOIN car_type ct ON cm.car_type_id=ct.id LEFT JOIN car_group cg ON ct.car_group_id=cg.id LEFT JOIN car_category cc ON cg.car_category_id=cc.id WHERE cc.id = $car_category AND cg.id= $car_group";

        //echo $query;exit();
        $records = DB::select($query);
        if ($records) {
            return $records;
        } else {
            return false;
        }
    }

    public function getDeliveryCoordinatesForBranch($branch_id)
    {
        $records = DB::table('branch_coverage_points')
            ->select('coordinates')
            ->where('branch_id', $branch_id)
            ->get();
        if (count($records) > 0) {
            return $records;
        } else {
            return false;
        }
    }


}