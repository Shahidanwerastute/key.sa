<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Promotions;
use App\Helpers\Custom;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\DB;

class PromotionsController extends Controller
{


    public function index()
    {
        if (!custom::rights(17, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'promotions_offers';
        return view('admin/promotions/manage', $data);
    }

    public function redeem_setup()
    {
        $data['main_section'] = 'booking_engine';
        $data['inner_section'] = 'redeem_setup';
        return view('admin/promotions/redeem_setup', $data);
    }


    public function getAll()
    {
        $expired = $_REQUEST['expired'];
        $jtSorting = $_REQUEST['jtSorting'];
        $jtStartIndex = $_REQUEST['jtStartIndex'];
        $jtPageSize = $_REQUEST['jtPageSize'];
        $rows = array();
        $promotion_obj = new Promotions();
        $promotions = $promotion_obj->getAll($expired, $jtSorting, $jtStartIndex, $jtPageSize);
        $promotions_count = $promotion_obj->getAllCount($expired);
        foreach ($promotions as $promotion) {

            $car_models_arr = [];
            $promotion_car_models = DB::table('promotion_offer_car_model')->where('promotion_offer_id', $promotion->id)->get();
            if ($promotion_car_models) {
                foreach ($promotion_car_models as $promotion_car_model) {
                    $promotion_car_model_detail = DB::table('car_model')->where('id', $promotion_car_model->car_model_id)->first();
                    if ($promotion_car_model_detail) {
                        $car_models_arr[] = $promotion_car_model->car_model_id;
                    }
                }
            }

            $promotion->car_models = $car_models_arr;
            $rows[] = $promotion;
        }

        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $promotions_count;
        $jTableResult['Records'] = $rows;
        echo json_encode($jTableResult);die;
    }

    public function getSinlgeDetail()
    {
        $promo_id = $_REQUEST['promo_id'];
        $rows = array();
        $promotion_obj = new Promotions();
        $promotions = $promotion_obj->getSinlgeDetail($promo_id);
        foreach ($promotions as $promotion) {

            $car_models_arr = [];
            $promotion_car_models = DB::table('promotion_offer_car_model')->where('promotion_offer_id', $promotion->id)->get();
            if ($promotion_car_models) {
                foreach ($promotion_car_models as $promotion_car_model) {
                    $promotion_car_model_detail = DB::table('car_model')->where('id', $promotion_car_model->car_model_id)->first();
                    if ($promotion_car_model_detail) {
                        $promotion_car_type_detail = DB::table('car_type')->where('id', $promotion_car_model_detail->car_type_id)->first();
                        if ($promotion_car_type_detail) {
                            $car_models_arr[] = $promotion_car_type_detail->eng_title . ' ' . $promotion_car_model_detail->eng_title.' '.$promotion_car_model_detail->year;
                        }
                    }
                }
            }

            $promotion->car_models = count($car_models_arr) > 0 ? implode(', ', $car_models_arr) : 'All Car Models';

            $promo_codes_arr = [];
            if (stripos($promotion->type, 'Coupon') !== false && $promotion->no_of_coupons == 'Series') {
                $promotion_coupons = DB::table('promotion_offer_coupon')->where('promotion_offer_id', $promotion->id)->get();
                if ($promotion_coupons) {
                    foreach ($promotion_coupons as $promotion_coupon) {
                        $promo_codes_arr[] = $promotion_coupon->code;
                    }
                }

                $promotion->promo_codes = implode(', ', $promo_codes_arr);
            }

            $query = DB::table('booking_payment as bp')
                ->join('booking as b', 'bp.booking_id', 'b.id')
                ->join('booking_individual_payment_method as bipm', 'b.id', 'bipm.booking_id')
                ->leftjoin('booking_cc_payment as bcp', 'b.id', 'bcp.booking_id')
                ->leftjoin('booking_sadad_payment as bsp', 'b.id', 'bsp.s_booking_id');

            $query->where('bp.promotion_offer_id', $promotion->id);
            $query->where(function ($query) {
                $query->where('bipm.payment_method', 'Cash');
                $query->orWhere('bipm.payment_method', 'Corporate Credit');
                $query->orWhere('bcp.status', 'completed');
                $query->orWhere('bsp.s_status', 'completed');
            });
            $usage_count_from_bookings = $query->count();

            $promotion->promotion_usage_count = $usage_count_from_bookings;

            $rows[] = $promotion;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        echo json_encode($jTableResult);die;
    }

    public function saveData(Request $request)
    {
        $promotion_obj = new Promotions();
        $posted_data = $request->input();
        $posted_data = custom::isNullToEmpty($posted_data);

        if (isset($posted_data['applies_to']) && $posted_data['applies_to'] != "" && strtotime($posted_data['applies_to']) < strtotime(date('Y-m-d H:i:s'))) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Adding offer for a past date is not allowed!';
            echo json_encode($jTableResult);die;
        }

        if ($posted_data['applies_to'] == "") {
            $posted_data['applies_to'] = null;
        }

        if (stripos($posted_data['type'], 'Coupon') !== false) {
            if (!trim($posted_data['no_of_coupons'])) {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Coupon type required.';
                echo json_encode($jTableResult);die;
            }

            if ($posted_data['no_of_coupons'] == 'Unlimited' && !trim($posted_data['code'])) {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Coupon code required.';
                echo json_encode($jTableResult);die;
            }

            if ($posted_data['no_of_coupons'] == 'Series' && !trim($posted_data['coupon_prefix'])) {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Coupon prefix required.';
                echo json_encode($jTableResult);die;
            }

            if ($posted_data['no_of_coupons'] == 'Series' && !trim($posted_data['no_of_coupons_limit'])) {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'No. of coupons in series required.';
                echo json_encode($jTableResult);die;
            }

            if ($posted_data['no_of_coupons'] == 'Series') {
                $check_if_series_already_exist = DB::table('promotion_offer')->where('coupon_prefix', $posted_data['coupon_prefix'])->first();
                if ($check_if_series_already_exist) {
                    $jTableResult['Result'] = "ERROR";
                    $jTableResult['Message'] = 'This coupon series prefix already exist.';
                    echo json_encode($jTableResult);die;
                }
            }
        }

        if (stripos($posted_data['type'], 'Coupon') === false) {
            if ($posted_data['no_of_coupons'] == 'Unlimited') {
                $coupon_specific_fields_with_unlimited = ['code'];
                foreach ($coupon_specific_fields_with_unlimited as $field) {
                    unset($posted_data[$field]);
                }
            } elseif ($posted_data['no_of_coupons'] == 'Series') {
                $coupon_specific_fields_with_series = ['coupon_prefix', 'no_of_coupons_limit', 'no_of_uses_per_coupon', 'no_of_uses_per_customer_id', 'minimum_booking_days', 'maximum_booking_days'];
                foreach ($coupon_specific_fields_with_series as $field) {
                    unset($posted_data[$field]);
                }
            }
            $posted_data['no_of_coupons'] = 'Unlimited';
        }

        $result = $promotion_obj->checkIfConflictDataExist($posted_data);

        if (count($result) > 0 && false) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
            echo json_encode($jTableResult);die;
        } else {

            $promotion_offer_data = [];
            $other_fields = ['code', 'car_models'];
            foreach ($posted_data as $key => $val) {
                if (!in_array($key, $other_fields)) {
                    $promotion_offer_data[$key] = $val;
                }
            }

            // saving days bits here
            $days = ['for_sunday', 'for_monday', 'for_tuesday', 'for_wednesday', 'for_thursday', 'for_friday', 'for_saturday'];
            foreach ($days as $day) {
                $promotion_offer_data[$day] = isset($promotion_offer_data[$day]) ? 1 : 0;
            }

            if ($promotion_offer_data['applies_from'] == "") {
                unset($promotion_offer_data['applies_from']);
            }

            if ($promotion_offer_data['applies_to'] == "") {
                unset($promotion_offer_data['applies_to']);
            }

            $promotion_offer_data['created_by'] = auth()->user()->id;
            $promotion_offer_data['created_at'] = date('Y-m-d H:i:s');

            $id = $promotion_obj->saveData($promotion_offer_data);
            if ($id > 0) {

                // saving promo codes
                if (stripos($promotion_offer_data['type'], 'Coupon') !== false) {
                    if ($posted_data['no_of_coupons'] == 'Unlimited') {
                        DB::table('promotion_offer_coupon')->insertGetId(['promotion_offer_id' => $id, 'code' => $posted_data['code']]);
                    } else if ($posted_data['no_of_coupons'] == 'Series') {
                        for ($i = 0; $i < $posted_data['no_of_coupons_limit']; $i++) {
                            $code = $this->generate_unique_coupon_code($posted_data['coupon_prefix']);
                            DB::table('promotion_offer_coupon')->insertGetId(['promotion_offer_id' => $id, 'code' => $code]);
                        }
                    }
                }

                // saving car models against this promotion
                if (isset($posted_data['car_models']) && count($posted_data['car_models']) > 0) {
                    foreach ($posted_data['car_models'] as $car_model) {
                        DB::table('promotion_offer_car_model')->insertGetId(['promotion_offer_id' => $id, 'car_model_id' => $car_model]);
                    }
                } else {
                    DB::table('promotion_offer_car_model')->insertGetId(['promotion_offer_id' => $id, 'car_model_id' => -1]);
                }

                custom::log('Promotions & Offers', 'add');
                $responseData = $promotion_obj->getSingle($id);
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                $jTableResult['Record'] = $responseData;
                echo json_encode($jTableResult);die;
            } else {
                $jTableResult['Result'] = "ERROR";
                $jTableResult['Message'] = 'Something went wrong.';
                echo json_encode($jTableResult);die;
            }
        }

    }

    public function updateData(Request $request)
    {
        $promotion_obj = new Promotions();
        $posted_data = $request->input();
        $posted_data = custom::isNullToEmpty($posted_data);

        if (isset($posted_data['applies_to']) && $posted_data['applies_to'] != "" && strtotime($posted_data['applies_to']) < strtotime(date('Y-m-d H:i:s'))) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Adding offer for a past date is not allowed!';
            echo json_encode($jTableResult);die;
        }

        if ($posted_data['applies_to'] == "") {
            $posted_data['applies_to'] = null;
        }

        $existing_promo_data  = $promotion_obj->getSingle($posted_data['id']);
        $posted_data['type'] = $existing_promo_data->type;
        $posted_data['no_of_coupons'] = $existing_promo_data->no_of_coupons;
        $posted_data['coupon_prefix'] = $existing_promo_data->coupon_prefix;
        $posted_data['no_of_coupons_limit'] = $existing_promo_data->no_of_coupons_limit;

        if (stripos($posted_data['type'], 'Coupon') !== false && $posted_data['no_of_coupons'] == 'Unlimited') {
            $posted_data['code'] = DB::table('promotion_offer_coupon')->where('promotion_offer_id', $posted_data['id'])->value('code');
        }

        $result = $promotion_obj->checkIfConflictDataExist($posted_data);

        if (count($result) > 0 && false) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
            echo json_encode($jTableResult);die;
        } else {

            $promotion_offer_data = [];
            $other_fields = ['id', 'code', 'car_models'];
            foreach ($posted_data as $key => $val) {
                if (!in_array($key, $other_fields)) {
                    $promotion_offer_data[$key] = $val;
                }
            }

            // saving days bits here
            $days = ['for_sunday', 'for_monday', 'for_tuesday', 'for_wednesday', 'for_thursday', 'for_friday', 'for_saturday'];
            foreach ($days as $day) {
                $promotion_offer_data[$day] = isset($promotion_offer_data[$day]) ? 1 : 0;
            }

            // saving old promotion data as history
            $this->create_promotion_edit_history($posted_data['id']);

            $promotion_offer_data['created_by'] = auth()->user()->id;
            $promotion_offer_data['created_at'] = date('Y-m-d H:i:s');
            
            $promotion_obj->updateData($promotion_offer_data, $posted_data['id']);

            // deleting old car models and saving new ones
            DB::table('promotion_offer_car_model')->where('promotion_offer_id', $posted_data['id'])->delete();
            if (isset($posted_data['car_models']) && count($posted_data['car_models']) > 0) {
                foreach ($posted_data['car_models'] as $car_model) {
                    DB::table('promotion_offer_car_model')->insertGetId(['promotion_offer_id' => $posted_data['id'], 'car_model_id' => $car_model]);
                }
            } else {
                DB::table('promotion_offer_car_model')->insertGetId(['promotion_offer_id' => $posted_data['id'], 'car_model_id' => -1]);
            }

            custom::log('Promotions & Offers', 'update');
            $responseData = $promotion_obj->getSingle($posted_data['id']);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
            echo json_encode($jTableResult);die;
        }

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $promotion_obj = new Promotions();
        $rows = $promotion_obj->checkIfInUse($id);
        if (count($rows) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = "This promotion cannot be deleted as this is in use.";
        } else {
            $promotion_obj->deleteData($id);
            custom::log('Promotions & Offers', 'delete');
            $jTableResult['Result'] = "OK";
        }
        echo json_encode($jTableResult);die;

    }

    public function getPromotionHistory()
    {

        $id = $_REQUEST['id'];
        $rows = array();
        $promotion = new Promotions();
        $pricing = $promotion->getPromotionHistory($id);

        foreach ($pricing as $price) {
            $rows[] = $price;
        }

        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;

        return response()->json($jTableResult);
    }
    
    private function create_promotion_edit_history($id) {
        $page = new Page();
        $data_for_promotion_offer_history = $page->getSingleRow('promotion_offer', ['id' => $id]);
        $data_for_promotion_offer_history = json_decode(json_encode($data_for_promotion_offer_history), true);
        $data_for_promotion_offer_history['promo_id'] = $id;
        unset($data_for_promotion_offer_history['id']);
        $page->saveData('promotion_offer_history', $data_for_promotion_offer_history);
    }

    public function generate_unique_coupon_code($prefix)
    {
        $code = $prefix.'-'.custom::generate_string(4);
        $code_exists = DB::table('promotion_offer_coupon')->where('code', $code)->first();
        if ($code_exists) {
            return $this->generate_unique_coupon_code($prefix);
        }
        return $code;
    }

    public function export(Request $request)
    {
        $offset = ($request->has('start_from') && $request->start_from > 0 ? $request->start_from - 1 : 0);
        $limit = 1000000;
        $promotions = array();
        $query = DB::table('promotion_offer as po')->leftjoin('promotion_offer_coupon as poc', 'po.id', '=', 'poc.promotion_offer_id');
        if ($request->promotion_id > 0) {
            $query->where('po.id', $request->promotion_id);
            // $query->offset($offset)->limit($limit);
        }
        $records = $query->select('po.eng_title', 'poc.code')->get();
        foreach ($records as $record) {
            // $usage_count = DB::table('booking_payment')->where('promotion_offer_code_used', $record->code)->count();
            $data['OFFER_NAME'] = strtoupper($record->eng_title);
            $data['COUPON_CODE'] = $record->code;
            // $data['USAGE_COUNT'] = $usage_count > 0 ? $usage_count : '0';
            // $data['USAGE_COUNT'] = '0';
            $promotions[] = $data;
        }

        if ($request->promotion_id > 0) {
            $promotion = DB::table('promotion_offer')->where('id', $request->promotion_id)->first();
            $file_name = $promotion ? str_replace(' ', '-', $promotion->eng_title) : 'coupon-usage-detail';
            // $file_name .= "-export-from-".($offset + 1).'-to-'.($limit + $offset);
        } else {
            $file_name = "all-coupons";
        }

        return custom::export_excel_file_custom($promotions, $file_name);
    }

}

?>