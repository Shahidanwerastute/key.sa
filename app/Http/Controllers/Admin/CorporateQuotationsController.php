<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Front\Page;
use Illuminate\Http\Request;
use DB;
use Lang;
use App\Helpers\Custom;


class CorporateQuotationsController extends Controller
{
    private $page;

    public function __construct(Request $request)
    {
        $this->page = new Page();
    }

    public function index(Request $request)
    {
        if (!custom::rights(60, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'registered_users';
        $data['inner_section'] = 'corporate_customers';
        $data['corporate_customer_id'] = $request->id;
        return view('admin/corporate_customer/manage_corporate_quotations', $data);
    }

    public function getCorporateQuotations(Request $request)
    {
        $rows = array();
        $corporate_quotations = $this->page->getMultipleRows('corporate_quotations', ['corporate_customer_id' => $request->corporate_customer_id], 'is_closed', 'ASC');
        foreach ($corporate_quotations as $corporate_quotation) {
            $last_activity_by = $this->page->getSingle('users', ['id' => $corporate_quotation->last_activity_by]);
            $corporate_quotation->last_activity_by = $last_activity_by->name;
            $rows[] = $corporate_quotation;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function updateCorporateQuotation(Request $request)
    {
        $corporate_quotation_data = $request->input();

        $id = $corporate_quotation_data['id'];
        unset($corporate_quotation_data['id']);

        $corporate_quotation_data['last_activity_by'] = auth()->user()->id;
        $corporate_quotation_data['last_activity_at'] = date('Y-m-d H:i:s');

        $corporate_quotation_data['applies_from'] = ($corporate_quotation_data['applies_from'] != "" ? date('Y-m-d', strtotime($corporate_quotation_data['applies_from'])) : NULL);
        $corporate_quotation_data['applies_to'] = ($corporate_quotation_data['applies_to'] != "" ? date('Y-m-d', strtotime($corporate_quotation_data['applies_to'])) : NULL);

        $updated = $this->page->updateData('corporate_quotations', $corporate_quotation_data, ['id' => $id]);
        if ($updated) {
            custom::log('Corporate Quotations', 'update');
            $responseData = $this->page->getSingle('corporate_quotations', ['id' => $id]);
            $last_activity_by = $this->page->getSingle('users', ['id' => $responseData->last_activity_by]);
            $responseData->last_activity_by = $last_activity_by->name;
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteCorporateQuotation(Request $request)
    {
        $id = $request->input('id');
        $this->page->deleteData('corporate_quotations', ['id' => $id]);
        $this->page->deleteData('corporate_quotation_prices', ['corporate_quotation_id' => $id]);
        custom::log('Corporate Quotations', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);
    }

    public function getCorporateQuotationPrices(Request $request)
    {
        $rows = array();
        $corporate_quotation_prices = $this->page->getMultipleRows('corporate_quotation_prices', ['corporate_quotation_id' => $request->corporate_quotation_id]);
        foreach ($corporate_quotation_prices as $corporate_quotation_price) {
            $corporate_quotation_price->car_name = custom::get_car_name($corporate_quotation_price->car_type, $corporate_quotation_price->car_model);
            $cqp_last_activity_by = $this->page->getSingle('users', ['id' => $corporate_quotation_price->cqp_last_activity_by]);
            $corporate_quotation_price->cqp_last_activity_by = $cqp_last_activity_by->name;
            $rows[] = $corporate_quotation_price;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function updateCorporateQuotationPrice(Request $request)
    {
        $corporate_quotation_price_data = $request->input();

        $id = $corporate_quotation_price_data['id'];
        unset($corporate_quotation_price_data['id']);

        $corporate_quotation_price_data['cqp_last_activity_by'] = auth()->user()->id;
        $corporate_quotation_price_data['cqp_last_activity_at'] = date('Y-m-d H:i:s');

        $updated = $this->page->updateData('corporate_quotation_prices', $corporate_quotation_price_data, ['id' => $id]);
        if ($updated) {
            custom::log('Corporate Quotation Prices', 'update');
            $responseData = $this->page->getSingle('corporate_quotation_prices', ['id' => $id]);
            $responseData->car_name = custom::get_car_name($responseData->car_type, $responseData->car_model);
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get updated. Please try again.';
        }

        print json_encode($jTableResult);
    }

    public function deleteCorporateQuotationPrice(Request $request)
    {
        $id = $request->input('id');

        $responseData = $this->page->getSingle('corporate_quotation_prices', ['id' => $id]);
        $corporate_quotation_data['last_activity_by'] = auth()->user()->id;
        $corporate_quotation_data['last_activity_at'] = date('Y-m-d H:i:s');
        $this->page->updateData('corporate_quotations', $corporate_quotation_data, ['id' => $responseData->corporate_quotation_id]);

        $this->page->deleteData('corporate_quotation_prices', ['id' => $id]);
        custom::log('Corporate Quotation Prices', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);
    }

    public function importCorporateQuotations(Request $request)
    {
        if ($request->file('import_file')) {

            $file = $request->file('import_file');
            $rows = custom::importExcel($file);

            if (count($rows) > 0) {
                // fetching data of corporate customer to check if the company code is the same
                $corporate_customer_data = $this->page->getSingle('corporate_customer', ['id' => $request->corporate_customer_id]);

                if ($corporate_customer_data->company_code == $rows[0]['company_code']) {
                    // if there is already data for this corporate customer in corporate quotations table, if exists, then closing the older quotation
                    $this->page->updateData('corporate_quotations', ['applies_to' => date('Y-m-d', strtotime('-1 day')), 'is_closed' => 1, 'last_activity_by' => auth()->user()->id, 'last_activity_at' => date('Y-m-d H:i:s')], ['company_code' => $rows[0]['company_code']]);

                    $corporate_quotation_data['corporate_customer_id'] = $request->corporate_customer_id;
                    $corporate_quotation_data['company_code'] = $rows[0]['company_code'];
                    $corporate_quotation_data['quotation_number'] = $rows[0]['quotation_no'];
                    $corporate_quotation_data['applies_from'] = (isset($rows[0]['applies_from']) && $rows[0]['applies_from'] != "" ? date('Y-m-d', strtotime($rows[0]['applies_from'])) : NULL);
                    $corporate_quotation_data['applies_to'] = (isset($rows[0]['applies_to']) && $rows[0]['applies_to'] != "" ? date('Y-m-d', strtotime($rows[0]['applies_to'])) : NULL);
                    $corporate_quotation_data['last_activity_by'] = auth()->user()->id;
                    $corporate_quotation_data['last_activity_at'] = date('Y-m-d H:i:s');
                    $corporate_quotation_id = $this->page->saveData('corporate_quotations', $corporate_quotation_data);

                    foreach ($rows as $row) {
                        $corporate_quotation_price_data['corporate_quotation_id'] = $corporate_quotation_id;
                        $corporate_quotation_price_data['car_type'] = $row['car_type'];
                        $corporate_quotation_price_data['car_model'] = $row['car_model'];
                        $corporate_quotation_price_data['daily_rent'] = $row['daily_rent'];
                        $corporate_quotation_price_data['monthly_rent'] = $row['monthly_rent'];
                        $corporate_quotation_price_data['daily_cdw_charges'] = $row['cdw_d_rate'];
                        $corporate_quotation_price_data['monthly_cdw_charges'] = $row['cdw_m_rate'];
                        $corporate_quotation_price_data['daily_open_km_charges'] = $row['open_km_d_rate'];
                        $corporate_quotation_price_data['monthly_open_km_charges'] = $row['open_km_m_rate'];
                        $corporate_quotation_price_data['daily_baby_seat_charges'] = $row['baby_seat_d_rate'];
                        $corporate_quotation_price_data['monthly_baby_seat_charges'] = $row['baby_seat_m_rate'];
                        $corporate_quotation_price_data['daily_extra_driver_charges'] = $row['extra_driver_d_rate'];
                        $corporate_quotation_price_data['monthly_extra_driver_charges'] = $row['extra_driver_m_rate'];
                        $corporate_quotation_price_data['daily_discount_percentage'] = $row['daily_discount'];
                        $corporate_quotation_price_data['monthly_discount_percentage'] = $row['monthly_discount'];
                        $corporate_quotation_price_data['cqp_last_activity_by'] = auth()->user()->id;
                        $corporate_quotation_price_data['cqp_last_activity_at'] = date('Y-m-d H:i:s');
                        $this->page->saveData('corporate_quotation_prices', $corporate_quotation_price_data);
                    }
                    custom::log('Corporate Quotations', 'add');
                    $msg = '?msg=Corporate Quotation Imported Successfully!';
                } else {
                    $msg = '?msg=Company code not matching!';
                }
            } else {
                $msg = '?msg=Imported File Is Empty!';
            }
        } else {
            $msg = '?msg=No File Chosen!';
        }
        return redirect('admin/corporate_quotations/' . $request->corporate_customer_id . $msg);
    }

}

?>