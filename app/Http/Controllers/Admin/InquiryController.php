<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Page;
use App\Helpers\Custom;

class InquiryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!custom::rights(7, 'view'))
        {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'inquiries';
        $data['inner_section'] = 'inquiries';
        return view('admin.inquiries.inquiries', $data);
    }

    public function getAllInquiries()
    {
        $inquiries = new Page();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $count_only = true;
        $totalRecordCount = $inquiries->getAllInquiries($count_only);
        $count_only = false;
        $recs = $inquiries->getAllInquiries($count_only,$sort_by,$jtStartIndex,$jtPageSize);
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

    public function getInquiryDetail(Request $request){
        $id = $request->input('id');
        $inquiries = new Page();
        $record = $inquiries->getInquiryMsg($id);
        $rows = array();
        foreach ($record as $rec) {
            $rows[] = $rec;
        }
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $rows;
        return response()->json($jTableResult);

    }
}

?>