<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Redeem;
use App\Helpers\Custom;
use App\Models\Admin\Page;
use App\Models\Admin\CarModel;

class RedeemController extends Controller
{

    public function index()
    {
        if (!custom::rights(41, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'redeem_setup';
        return view('admin/redeem_setup/manage', $data);
    }


    public function getAll()
    {
        $rows = array();
        $redeem = new Redeem();
        $records = $redeem->getAll();
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
        $group = new Redeem();
        $data = $request->input();
        $data['created_at'] = date('Y-m-d H:i:s');
        /*if ($data['car_model_id'] == 0) // if all option is selected than don't save anything in db for car_model_id
        {
            $data['car_model_id'] = '';
        }*/
        // When we select amount as type of redeem needs to make it a validation which the field should not accept more than 50% from the car price the original price
        if ($data['type_of_redeem'] == 'Amount') {
            $posted_amount = $data['percentage_of_points_usable'];
            $applies_from = $data['applies_from'];
            $car_model_id = $data['car_model_id'];
            $renting_type = 1;
            $customer_type = 'Individual';
            $pricing = $group->getPricing($applies_from, $car_model_id, $renting_type, $customer_type);

            if (isset($pricing[0])) {
                $rent_per_day = $pricing[0]->price;
                $usable_amount = (50 / 100) * $rent_per_day;
                if ($posted_amount > $usable_amount) {
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = 'You can not use more than ' . $usable_amount . ' SAR as redeemable amount.';
                    print json_encode($jTableResult);
                    exit();
                }
                $id = $group->saveData($data);
                if ($id > 0) {
                    custom::log('Redeem Setup', 'add');
                    $responseData = $group->getSingle($id);
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    $jTableResult['Record'] = $responseData;
                } else {
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
                    print json_encode($jTableResult);
                    exit();
                }
            } else {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Sorry this car model does\'t  have a price entry in "Car Pricing & Availability"';
                print json_encode($jTableResult);
                exit();
            }

        }

        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $group = new Redeem();
        $id = $request->input('id');
        $data = $request->input();
        //echo $data['car_model_id'];exit();
        $data['updated_at'] = date('Y-m-d H:i:s');
        /*if ($data['car_model_id'] == 0)
        {
            $data['car_model_id'] = '';
        }*/
        $updated = $group->updateData($data, $id);
        if ($updated) {
            custom::log('Redeem Setup', 'update');
            $responseData = $group->getSingle($id);
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
        $group = new Redeem();
        $group->deleteData($id);
        custom::log('Redeem Setup', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllModelsByType(Request $request)
    {
        $car_type_id = $request->input('car_type_id');
        $rentingType = new CarModel();
        $cities = $rentingType->getAllModelsByType($car_type_id);
        $rows = array();
        foreach ($cities as $city) {
            $rows[] = $city;
        }
        echo json_encode($rows);
        exit();
    }


}

?>