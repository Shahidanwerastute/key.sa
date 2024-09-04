<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\LoyaltyProgram;
use App\Helpers\Custom;

class LoyaltyProgramController extends Controller
{
    public function index()
    {
        if (!custom::rights(54, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'loyalty_programs';
        return view('admin/settings/loyalty_programs', $data);
    }

    public function getAll()
    {
        $rows = array();
        $obj = new LoyaltyProgram();
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
        $obj = new LoyaltyProgram();
        $data = $request->input();
        if (isset($data['is_default']))
        {
            LoyaltyProgram::where('is_default', 1)->update(['is_default' => 0]); // making all other defaults 0
            $data['is_default'] = 1; // marking current one as default
        } else {
            $data['is_default'] = 0;
        }

        if (isset($data['is_active']))
        {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }
        $id = $obj->saveData($data);
        if ($id > 0) {
            custom::log('Settings Loyalty Programs', 'add');
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
        $obj = new LoyaltyProgram();
        $id = $request->input('id');
        $data = $request->input();
        if (isset($data['is_default']))
        {
            LoyaltyProgram::where('is_default', 1)->update(['is_default' => 0]); // making all other defaults 0
            $data['is_default'] = 1; // marking current one as default
        } else {
            $data['is_default'] = 0;
        }

        if (isset($data['is_active']))
        {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }
        $updated = $obj->updateData($data, $id);
        if ($updated) {
            custom::log('Settings Loyalty Programs', 'update');
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
        $obj = new LoyaltyProgram();
        $obj->deleteData($id);
        custom::log('Settings Loyalty Programs', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


}

?>