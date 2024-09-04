<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Page;
use App\Helpers\Custom;
use Excel;
use Auth;
use DB;


class CareerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!custom::rights(8, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'career_inquiry';
        $data['inner_section'] = 'career_inquiry';
        return view('admin.career.career', $data);
    }


    public function getAllCareers()
    {
        $inquiries = new Page();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $count_only = true;
        $totalRecordCount = $inquiries->getAllCareers($count_only);
        $count_only = false;
        $recs = $inquiries->getAllCareers($count_only,$sort_by,$jtStartIndex,$jtPageSize);
        $rows = array();
        foreach ($recs as $rec) {
            $rows[] = $rec;
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $totalRecordCount[0]->tcount;
        $jTableResult['Records'] = $rows;
        return response()->json($jTableResult);

    }

    public function getCareerDetail(Request $request){
        $id = $request->input('id');
        $inquiries = new Page();
        $record = $inquiries->getCareerDetail($id);
        //echo '<pre>';print_r($record);exit();
        $rows = array();
        foreach ($record as $rec) {
            $rows[] = $rec;
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $rows;
        return response()->json($jTableResult);

    }

    public function export_career(Request $request)
    {
        $start_date = '';
        $end_date = '';
        $inquiries = new Page();
        $filename = 'Career-details-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        if(!empty($request->from_date))
        {
            $start_date = date('Y-m-d', strtotime($request->from_date));
        }
        if(!empty($request->to_date))
        {
            $end_date = date('Y-m-d', strtotime($request->to_date));
        }
        $responses_data = $inquiries->getAllCareersForExport($start_date, $end_date);

        // echo "<pre>";print_r($responses_data);exit();
        foreach ($responses_data as $responses_datum) {
            $data_for_export[] = (array)$responses_datum;
        }
        //echo "<pre>";print_r($data_for_export);exit();

        return custom::excelExport($filename, $data_for_export);
    }
}

?>