<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Survey;
use App\Models\Front\Page;
use Illuminate\Http\Request;
use App\Models\Admin\CorporateSales;
use App\Helpers\Custom;
use Excel;

class CorporateSalesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /*if (!custom::rights(37, 'view')) {
            return redirect('admin/dashboard');
        }*/
        $corporate_sales = new CorporateSales();
        $data['content'] = $corporate_sales->getSingleRow('corporate_sales', array('id' => '1'));
        $data['main_section'] = 'corporate_sales';
        $data['inner_section'] = 'manage_sales_content';
        return view('admin.corporate_sales.manage_content', $data);
    }

    public function manage_cars()
    {
        if (!custom::rights(38, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'car_selling';
        $data['inner_section'] = 'manage_cars';
        return view('admin.car_selling.manage_cars', $data);
    }

    public function responses()
    {
        if (!custom::rights(39, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'corporate_sales';
        $data['inner_section'] = 'sales_responses';
        return view('admin.corporate_sales.responses', $data);
    }

    public function get_car_models_listing(Request $request)
    {
        $car_selling = new CarSelling();
        $car_brand_id = $request->input('car_brand_id');
        $cards = $car_selling->get_all_car_models($car_brand_id);
        $recordCount = count($cards);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $cards;
        print json_encode($jTableResult);
    }

    public function getAllResponses()
    {
        $corporate_sales = new CorporateSales();
        $sort_by = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $count_only = true;
        $totalRecordCount = $corporate_sales->getAllResponses($count_only);
        $count_only = false;
        $recs = $corporate_sales->getAllResponses($count_only,$sort_by,$jtStartIndex,$jtPageSize);
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

    public function exportData()
    {
        $corporate_sales = new CorporateSales();
        $yourFileName = 'corporate-response-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        $responses_data = $corporate_sales->getDataToExport();
        foreach ($responses_data as $responses_datum) {
            $data_for_export[] = (array)$responses_datum;
        }
        //echo "<pre>";print_r($data_for_export);exit();

        return custom::excelExport($yourFileName, $data_for_export);
    }
}

?>