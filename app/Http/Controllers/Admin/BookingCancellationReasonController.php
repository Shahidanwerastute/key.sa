<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Region;
use Illuminate\Http\Request;
use App\Models\Admin\BookingCancellationReason;
use App\Helpers\Custom;
use App\Models\Admin\City;
use App\Models\Admin\CarModel;

class BookingCancellationReasonController extends Controller
{
    public function index()
    {
        if (!custom::rights(53, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'booking_cancellation_reasons';
        return view('admin/settings/booking_cancellation_reasons', $data);
    }

    public function getAll()
    {
        $rows = array();
        $obj = new BookingCancellationReason();
        $records = $obj->getAll();
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
        $obj = new BookingCancellationReason();
        $data = $request->input();
        if (isset($data['is_active']))
        {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }
        $id = $obj->saveData($data);
        if ($id > 0) {
            custom::log('Booking Cancellation Reason', 'add');
            $responseData = $obj->getSingle($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $obj = new BookingCancellationReason();
        $id = $request->input('id');
        $data = $request->input();
        if (isset($data['is_active']))
        {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }
        $updated = $obj->updateData($data, $id);
        if ($updated) {
            custom::log('Booking Cancellation Reason', 'update');
            $responseData = $obj->getSingle($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $obj = new BookingCancellationReason();
        $obj->deleteData($id);
        custom::log('Booking Cancellation Reason', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


}

?>