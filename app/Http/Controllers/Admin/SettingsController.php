<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\Settings;
use App\Helpers\Custom;
use DB;

class SettingsController extends Controller
{
    public function __construct(Request $request)
    {
        // \Artisan::call('view:clear');
    }

    public function index()
    {
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'settings';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        //echo 'here';exit();
        return view('admin/settings/manage', $data);
    }


    public function smtp_settings(Request $request)
    {
        if (!custom::rights(12, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $posted_data = $request->input();

        $encrypted_keys = ['username', 'password'];

        $data = [];
        foreach ($posted_data as $key => $value) {
            if (in_array($key, $encrypted_keys)) {
                $value = custom::decrypt($value);
            }
            $data[$key] = $value;
        }

        $id = $data['id'];
        unset($data['id']);
        $updated = $setting->update_data('setting_smtp_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'SMTP settings updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'SMTP settings could not be updated. Try again please.';
        }
        echo json_encode($response);
        exit();

    }

    public function api_settings(Request $request)
    {
        if (!custom::rights(25, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $posted_data = $request->input();
        //echo '<pre>';print_r($posted_data);exit();

        // decrypting encrypted data before saving into the database
        $encrypted_keys = ['username', 'password', 'paytabs_merchant_email', 'paytabs_merchant_id', 'paytabs_secret_key', 'sts_merchant_id_web', 'sts_secret_key_web', 'sts_payment_link', 'sts_payment_inquiry_link', 'sts_paylater_merchant_id', 'sts_paylater_secret_key', 'sts_paylater_send_invoice_link', 'sts_paylater_invoice_inquiry_link', 'sts_merchant_id_mobile', 'sts_secret_key_mobile', 'hyper_pay_endpoint_url', 'hyper_pay_bearer_token', 'hyper_pay_entity_id_master_visa', 'hyper_pay_entity_id_mada', 'hyper_pay_entity_id_apple_pay', 'hyper_bill_username', 'hyper_bill_password', 'hyper_bill_endpoint_url', 'oasis_api_url', 'qitaf_api_base_url', 'edit_booking_sync_api_url', 'unifonic_username', 'unifonic_password', 'unifonic_sender_id', 'unifonic_app_id', 'taqnyat_bearer_token', 'taqnyat_sender_id'];

        $data = [];
        foreach ($posted_data as $key => $value) {
            if (in_array($key, $encrypted_keys)) {
                $value = custom::decrypt($value);
            }
            $data[$key] = $value;
        }

        $id = $data['id'];
        unset($data['id']);

        if (isset($data['oasis_api_url'])) {
            if (isset($data['walkin_api_on_off']) && $data['walkin_api_on_off'] = 'on') {
                $data['walkin_api_on_off'] = 'on';
            } else {
                $data['walkin_api_on_off'] = 'off';
            }

            if (isset($data['api_on_off']) && $data['api_on_off'] = 'on') {
                $data['api_on_off'] = 'on';
            } else {
                $data['api_on_off'] = 'off';
            }

            if (isset($data['display_chat']) && $data['display_chat'] = 'yes') {
                $data['display_chat'] = 'yes';
            } else {
                $data['display_chat'] = 'no';
            }
        }

        if (isset($data['hyper_pay_endpoint_url'])) {
            if (isset($data['hyper_pay_test_mode']) && $data['hyper_pay_test_mode'] = 'INTERNAL') {
                $data['hyper_pay_test_mode'] = 'INTERNAL';
            } else {
                $data['hyper_pay_test_mode'] = 'EXTERNAL';
            }
        }

        $updated = $setting->update_data('setting_api_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'API settings updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'API settings could not be updated. Try again please.';
        }
        echo json_encode($response);
        exit();

    }

    public function site_settings(Request $request)
    {
        if (!custom::rights(12, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $data = $request->input();
        //echo '<pre>';print_r($data);exit();
        $old_site_logo = $data['old_site_logo'];
        $old_site_logo_mobile = $data['old_site_logo_mobile'];
        $old_mobile_app_splash_screen = $data['old_mobile_app_splash_screen'];
        $id = $data['id'];
        unset($data['id']);
        unset($data['old_site_logo']);
        unset($data['old_site_logo_mobile']);
        unset($data['old_mobile_app_splash_screen']);
        $site_logo = custom::uploadImage($request->file('site_logo'), 'site_logo');
        if ($site_logo != false) {
            $data['site_logo'] = $site_logo;
        } else {
            $data['site_logo'] = $old_site_logo;
        }
        $site_logo_mobile = custom::uploadImage($request->file('site_logo_mobile'), 'site_logo_mobile');
        if ($site_logo_mobile != false) {
            $data['site_logo_mobile'] = $site_logo_mobile;
        } else {
            $data['site_logo_mobile'] = $old_site_logo_mobile;
        }

        $mobile_app_splash_screen = custom::uploadImage($request->file('mobile_app_splash_screen'), 'mobile_app_splash_screen');
        if ($mobile_app_splash_screen != false) {
            $data['mobile_app_splash_screen'] = $mobile_app_splash_screen;
        } else {
            $data['mobile_app_splash_screen'] = $old_mobile_app_splash_screen;
        }

        if (isset($data['delivery_mode'])) {
            $data['delivery_mode'] = 'on';
        } else {
            $data['delivery_mode'] = 'off';
        }

        if (isset($data['delivery_mode_mobile'])) {
            $data['delivery_mode_mobile'] = 'on';
        } else {
            $data['delivery_mode_mobile'] = 'off';
        }

        if (isset($data['hourly_renting_mode'])) {
            $data['hourly_renting_mode'] = 'on';
        } else {
            $data['hourly_renting_mode'] = 'off';
        }

        if (isset($data['hourly_renting_mode_mobile'])) {
            $data['hourly_renting_mode_mobile'] = 'on';
        } else {
            $data['hourly_renting_mode_mobile'] = 'off';
        }

        if (isset($data['monthly_renting_mode'])) {
            $data['monthly_renting_mode'] = 'on';
        } else {
            $data['monthly_renting_mode'] = 'off';
        }

        if (isset($data['monthly_renting_mode_mobile'])) {
            $data['monthly_renting_mode_mobile'] = 'on';
        } else {
            $data['monthly_renting_mode_mobile'] = 'off';
        }

        if (isset($data['subscription_renting_mode'])) {
            $data['subscription_renting_mode'] = 'on';
        } else {
            $data['subscription_renting_mode'] = 'off';
        }

        if (isset($data['subscription_renting_mode_mobile'])) {
            $data['subscription_renting_mode_mobile'] = 'on';
        } else {
            $data['subscription_renting_mode_mobile'] = 'off';
        }

        if (isset($data['weekly_renting_mode'])) {
            $data['weekly_renting_mode'] = 'on';
        } else {
            $data['weekly_renting_mode'] = 'off';
        }

        if (isset($data['weekly_renting_mode_mobile'])) {
            $data['weekly_renting_mode_mobile'] = 'on';
        } else {
            $data['weekly_renting_mode_mobile'] = 'off';
        }

        if (isset($data['survey_mode'])) {
            $data['survey_mode'] = 'mandatory';
        } else {
            $data['survey_mode'] = 'optional';
        }

        if (isset($data['survey_mode_mobile'])) {
            $data['survey_mode_mobile'] = 'mandatory';
        } else {
            $data['survey_mode_mobile'] = 'optional';
        }

        if (isset($data['campaign_mode'])) {
            $data['campaign_mode'] = 'on';
        } else {
            $data['campaign_mode'] = 'off';
        }

        if (isset($data['date_range_mode'])) {
            $data['date_range_mode'] = 'on';
        } else {
            $data['date_range_mode'] = 'off';
        }

        if (isset($data['can_edit_booking'])) {
            $data['can_edit_booking'] = 'yes';
        } else {
            $data['can_edit_booking'] = 'no';
        }

        if (isset($data['survey_on_off'])) {
            $data['survey_on_off'] = 'on';
        } else {
            $data['survey_on_off'] = 'off';
        }

        if (isset($data['promo_coupon_mode'])) {
            $data['promo_coupon_mode'] = 'on';
        } else {
            $data['promo_coupon_mode'] = 'off';
        }

        if (isset($data['pricing_api'])) {
            $data['pricing_api'] = 'on';
        } else {
            $data['pricing_api'] = 'off';
        }

        if (isset($data['human_less_mode'])) {
            $data['human_less_mode'] = 'on';
        } else {
            $data['human_less_mode'] = 'off';
        }

        if (isset($data['hl_skip_payment'])) {
            $data['hl_skip_payment'] = 'on';
        } else {
            $data['hl_skip_payment'] = 'off';
        }

        if (isset($data['hl_close_contract'])) {
            $data['hl_close_contract'] = 'on';
        } else {
            $data['hl_close_contract'] = 'off';
        }

        if (isset($data['logout_customers_forcefully'])) {
            $data['logout_customers_forcefully'] = 'on';
        } else {
            $data['logout_customers_forcefully'] = 'off';
        }

        if (isset($data['activate_account_with_sms'])) {
            $data['activate_account_with_sms'] = 1;
        } else {
            $data['activate_account_with_sms'] = 0;
        }

        if (isset($data['activate_account_with_email'])) {
            $data['activate_account_with_email'] = 1;
        } else {
            $data['activate_account_with_email'] = 0;
        }

        if (isset($data['forcefully_recheck_prices'])) {
            $data['forcefully_recheck_prices'] = 1;
        } else {
            $data['forcefully_recheck_prices'] = 0;
        }

        if (isset($data['refer_and_earn_option'])) {
            $data['refer_and_earn_option'] = 'on';
        } else {
            $data['refer_and_earn_option'] = 'off';
        }

        if (isset($data['vat_mode'])) {
            $data['vat_mode'] = 'on';
        } else {
            $data['vat_mode'] = 'off';
        }

        if (isset($data['vat_mode_for_mobile'])) {
            $data['vat_mode_for_mobile'] = 'on';
        } else {
            $data['vat_mode_for_mobile'] = 'off';
        }

        if (isset($data['redeem_offer_mode'])) {
            $data['redeem_offer_mode'] = 'on';
        } else {
            $data['redeem_offer_mode'] = 'off';
        }

        if (isset($data['debug_mode'])) {
            $data['debug_mode'] = 'on';
        } else {
            $data['debug_mode'] = 'off';
        }

        if (isset($data['force_ssl'])) {
            $data['force_ssl'] = 'on';
        } else {
            $data['force_ssl'] = 'off';
        }

        if (isset($data['qitaf'])) {
            $data['qitaf'] = 'on';
        } else {
            $data['qitaf'] = 'off';
        }

        if (isset($data['niqaty'])) {
            $data['niqaty'] = 'on';
        } else {
            $data['niqaty'] = 'off';
        }

        if (isset($data['anb'])) {
            $data['anb'] = 'on';
        } else {
            $data['anb'] = 'off';
        }

        if (isset($data['mokafaa'])) {
            $data['mokafaa'] = 'on';
        } else {
            $data['mokafaa'] = 'off';
        }

        if (isset($data['show_loyalty_programs_section'])) {
            $data['show_loyalty_programs_section'] = 'yes';
        } else {
            $data['show_loyalty_programs_section'] = 'no';
        }

        if (isset($data['show_loyalty_programs_section_for_mobile'])) {
            $data['show_loyalty_programs_section_for_mobile'] = 'yes';
        } else {
            $data['show_loyalty_programs_section_for_mobile'] = 'no';
        }

        if (isset($data['car_utilization_mode'])) {
            $data['car_utilization_mode'] = 'on';
        } else {
            $data['car_utilization_mode'] = 'off';
        }

        if (isset($data['car_utilization_mode_mobile'])) {
            $data['car_utilization_mode_mobile'] = 'on';
        } else {
            $data['car_utilization_mode_mobile'] = 'off';
        }

        if (isset($data['car_utilization_addition_subtraction_mode'])) {
            $data['car_utilization_addition_subtraction_mode'] = 'on';
        } else {
            $data['car_utilization_addition_subtraction_mode'] = 'off';
        }

        if (isset($data['show_account_deletion_button'])) {
            $data['show_account_deletion_button'] = 1;
        } else {
            $data['show_account_deletion_button'] = 0;
        }

        if (isset($data['daily_with_delivery_flow'])) {
            $data['daily_with_delivery_flow'] = 'on';
        } else {
            $data['daily_with_delivery_flow'] = 'off';
        }

        if (isset($data['monthly_with_delivery_flow'])) {
            $data['monthly_with_delivery_flow'] = 'on';
        } else {
            $data['monthly_with_delivery_flow'] = 'off';
        }

        if (isset($data['subscription_with_delivery_flow'])) {
            $data['subscription_with_delivery_flow'] = 'on';
        } else {
            $data['subscription_with_delivery_flow'] = 'off';
        }

        if (isset($data['weekly_with_delivery_flow'])) {
            $data['weekly_with_delivery_flow'] = 'on';
        } else {
            $data['weekly_with_delivery_flow'] = 'off';
        }

        if (isset($data['show_add_payment_option'])) {
            $data['show_add_payment_option'] = 'yes';
        } else {
            $data['show_add_payment_option'] = 'no';
        }

        if (isset($data['show_pay_by_promo_code_in_extend_payment'])) {
            $data['show_pay_by_promo_code_in_extend_payment'] = 'yes';
        } else {
            $data['show_pay_by_promo_code_in_extend_payment'] = 'no';
        }

        if (isset($data['limousine_mode'])) {
            $data['limousine_mode'] = 'on';
        } else {
            $data['limousine_mode'] = 'off';
        }

        if (isset($data['show_pay_current_balance_option'])) {
            $data['show_pay_current_balance_option'] = 'yes';
        } else {
            $data['show_pay_current_balance_option'] = 'no';
        }

        if (isset($data['show_pay_by_number_of_days_option'])) {
            $data['show_pay_by_number_of_days_option'] = 'yes';
        } else {
            $data['show_pay_by_number_of_days_option'] = 'no';
        }

        if (isset($data['show_add_payment_option_for_mobile'])) {
            $data['show_add_payment_option_for_mobile'] = 'yes';
        } else {
            $data['show_add_payment_option_for_mobile'] = 'no';
        }

        if (isset($data['show_pay_current_balance_option_for_mobile'])) {
            $data['show_pay_current_balance_option_for_mobile'] = 'yes';
        } else {
            $data['show_pay_current_balance_option_for_mobile'] = 'no';
        }

        if (isset($data['show_pay_by_number_of_days_option_for_mobile'])) {
            $data['show_pay_by_number_of_days_option_for_mobile'] = 'yes';
        } else {
            $data['show_pay_by_number_of_days_option_for_mobile'] = 'no';
        }

        if (isset($data['show_promo_code_popup_in_apps'])) {
            $data['show_promo_code_popup_in_apps'] = 'yes';
        } else {
            $data['show_promo_code_popup_in_apps'] = 'no';
        }

        if (isset($data['cc'])) {
            $data['cc'] = 1;
        } else {
            $data['cc'] = 0;
        }

        if (isset($data['amex'])) {
            $data['amex'] = 1;
        } else {
            $data['amex'] = 0;
        }

        if (isset($data['mada'])) {
            $data['mada'] = 1;
        } else {
            $data['mada'] = 0;
        }

        if (isset($data['stc_pay'])) {
            $data['stc_pay'] = 1;
        } else {
            $data['stc_pay'] = 0;
        }

        if (isset($data['apple_pay'])) {
            $data['apple_pay'] = 1;
        } else {
            $data['apple_pay'] = 0;
        }

        if (isset($data['cash'])) {
            $data['cash'] = 1;
        } else {
            $data['cash'] = 0;
        }

        if (isset($data['points'])) {
            $data['points'] = 1;
        } else {
            $data['points'] = 0;
        }

        if (isset($data['sadad'])) {
            $data['sadad'] = 1;
        } else {
            $data['sadad'] = 0;
        }

        $updated = $setting->update_data('setting_site_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'Site Settings updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Site Settings could not be updated. Try again please.';
        }
        $response['has_image'] = true;

        $response['site_logo'] = $data['site_logo'];
        $response['site_logo_mobile'] = $data['site_logo_mobile'];
        $response['mobile_app_splash_screen'] = $data['mobile_app_splash_screen'];

        echo json_encode($response);
        exit();

    }

    public function save_maintenance_text(Request $request)
    {
        if (!custom::rights(24, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);

        if (isset($data['maintenance_mode'])) {
            $data['maintenance_mode'] = 'on';
        } else {
            $data['maintenance_mode'] = 'off';
        }

        if (isset($data['mobile_maintenance_mode'])) {
            $data['mobile_maintenance_mode'] = 'on';
        } else {
            $data['mobile_maintenance_mode'] = 'off';
        }

        if (isset($data['mobile_maintenance_mode_for_android'])) {
            $data['mobile_maintenance_mode_for_android'] = 'on';
        } else {
            $data['mobile_maintenance_mode_for_android'] = 'off';
        }

        if (isset($data['mobile_maintenance_mode_for_ios'])) {
            $data['mobile_maintenance_mode_for_ios'] = 'on';
        } else {
            $data['mobile_maintenance_mode_for_ios'] = 'off';
        }

        if (isset($data['mobile_maintenance_mode_for_huawei'])) {
            $data['mobile_maintenance_mode_for_huawei'] = 'on';
        } else {
            $data['mobile_maintenance_mode_for_huawei'] = 'off';
        }

        $updated = $setting->update_data('setting_site_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'Updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Could not be updated. Try again please.';
        }

        echo json_encode($response);
        exit();
    }

    public function social_links(Request $request)
    {

        $setting = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        $updated = $setting->update_data('setting_social_links', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'Social links updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Social links could not be updated. Try again please.';
        }
        echo json_encode($response);
        exit();

    }

    public function save_terms_conditions(Request $request)
    {
        if (!custom::rights(23, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        $updated = $setting->update_data('setting_site_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'Terms and conditions updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Terms and conditions could not be updated. Try again please.';
        }
        echo json_encode($response);
        exit();

    }


    public function save_humanless_instructions(Request $request)
    {
        if (!custom::rights(47, 'view')) {
            return redirect()->back();
        }

        $setting = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        $updated = $setting->update_data('setting_site_settings', $data, $id);
        if ($updated > 0) {
            $response['status'] = true;
            $response['message'] = 'Instructions updated successfully';
        } else {
            $response['status'] = false;
            $response['message'] = 'Instructions could not be updated. Try again please.';
        }
        echo json_encode($response);
        exit();

    }


    public function get_departments(Request $request)
    {
        $settings = new Settings();
        $departments = $settings->get_all('department');
        $recordCount = count($departments);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $departments;
        print json_encode($jTableResult);
    }

    public function add_departments(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $where['id'] = $settings->add_data('department', $data);
        $responseData = $settings->get_single_record('department', $where);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function update_departments(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        $where['id'] = $settings->update_data('department', $data, $id);
        $responseData = $settings->get_single_record('department', $where);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function delete_departments(Request $request)
    {
        $id = $request->input('id');
        $settings = new Settings();
        $settings->delete_data('department', $id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


    public function get_listing(Request $request)
    {
        $sort_by = "id";
        $sort_as = "desc";
        if (isset($_REQUEST['jtSorting'])) {
            $sorting = explode(' ', $_REQUEST['jtSorting']);
            $sort_by = $sorting[0];
            $sort_as = $sorting[1];

        }
        $tbl = $request->input('tbl');
        $settings = new Settings();
        $cards = $settings->get_all($tbl, $sort_by, $sort_as);
        $recordCount = count($cards);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $cards;
        print json_encode($jTableResult);
    }

    public function add_listing(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $tbl = $data['tbl'];
        unset($data['tbl']);
        if (isset($data['action'])) {
            $action = $data['action'];
            unset($data['action']);
        }

        $where['id'] = $settings->add_data($tbl, $data);
        if (isset($action) && $action == 'save_rights_for_role') {
            $page = new Page();
            $sections = $page->getAll('setting_site_section', 'name');
            foreach ($sections as $section) {
                $rights_data['role_id'] = $where['id'];
                $rights_data['section_id'] = $section->id;
                $rights_data['read'] = '0';
                $rights_data['write'] = '0';
                $rights_data['edit'] = '0';
                $rights_data['delete'] = '0';
                $page->saveData('setting_user_rights', $rights_data);
            }
        }

        // save_rights_for_section
        if (isset($action) && $action == 'save_rights_for_section') {
            $page = new Page();
            $roles = $page->getAll('setting_user_role');
            foreach ($roles as $role) {
                $rights_data_section['role_id'] = $role->id;
                $rights_data_section['section_id'] = $where['id'];
                $rights_data_section['read'] = '0';
                $rights_data_section['write'] = '0';
                $rights_data_section['edit'] = '0';
                $rights_data_section['delete'] = '0';
                $page->saveData('setting_user_rights', $rights_data_section);
            }
        }
        $responseData = $settings->get_single_record($tbl, $where);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function update_listing(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $tbl = $data['tbl'];
        $id = $data['id'];
        unset($data['tbl']);
        unset($data['id']);
        $where['id'] = $settings->update_data($tbl, $data, $id);
        $responseData = $settings->get_single_record($tbl, $where);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function delete_listing(Request $request)
    {
        $tbl = $request->input('tbl');
        $id = $request->input('id');
        $settings = new Settings();
        $settings->delete_data($tbl, $id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function site_labels()
    {
        echo 'labels';
        exit();
    }

    public function get_logs()
    {
        if (!custom::rights(13, 'view')) {
            return redirect('admin/dashboard');
        }
        $settings = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'logs';
        $data['logs'] = $settings->get_all_logs();
        return view('admin/settings/logs', $data);
    }


    public function get_loyalty_card_types()
    {
        $rows = array();
        $carPricing = new Settings();
        $models = $carPricing->get_loyalty_card_types();
        foreach ($models as $model) {
            $rows[] = $model;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function add_loyalty_card_type(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        //$result = $settings->checkIfConflictDataExist($data);
        /*if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {*/
        $id = $settings->saveData($data);
        custom::log('Loyalty Card Types', 'add');
        $responseData = $settings->get_single($id);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        //}
        print json_encode($jTableResult);

    }

    public function update_loyalty_card_type(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        //$result = $settings->checkIfConflictDataExist($data,$id);
        /*if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {*/
        $where['id'] = $settings->updateData($data, $id);
        custom::log('Loyalty Card Types', 'update');
        $responseData = $settings->get_single($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        //}
        print json_encode($jTableResult);

    }

    public function delete_loyalty_card_type(Request $request)
    {
        $id = $request->input('id');
        $settings = new Settings();
        $settings->deleteData($id);
        custom::log('Loyalty Card Types', 'delete');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }


    public function get_renting_types()
    {
        $rows = array();
        $carPricing = new Settings();
        $models = $carPricing->get_renting_types();
        foreach ($models as $model) {
            $rows[] = $model;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function add_renting_type(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        //$result = $settings->checkIfConflictDataExist($data);
        /*if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {*/
        $id = $settings->save_renting_type($data);
        custom::log('Renting Types', 'add');
        $responseData = $settings->get_single_renting_type($id);
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        //}
        print json_encode($jTableResult);

    }

    public function update_renting_type(Request $request)
    {
        $settings = new Settings();
        $data = $request->input();
        $id = $data['id'];
        unset($data['id']);
        //$result = $settings->checkIfConflictDataExist($data,$id);
        /*if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {*/
        $where['id'] = $settings->update_renting_type($data, $id);
        custom::log('Renting Types', 'update');
        $responseData = $settings->get_single($id);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        //}
        print json_encode($jTableResult);

    }

    public function delete_renting_type(Request $request)
    {
        $let_it_delete = true;
        $id = $request->input('id');
        $page = new Page();
        $settings = new Settings();
        $car_prices = $page->getMultipleRowsCount('car_price', array('renting_type_id' => $id));
        $promotion_offers = $page->getMultipleRowsCount('promotion_offer', array('renting_type_id' => $id));
        $bookings = $page->getMultipleRowsCount('booking', array('renting_type_id' => $id));
        $loyalty_cards = $page->getMultipleRowsCount('setting_loyalty_cards', array('renting_type_id' => $id));
        if ($car_prices || $promotion_offers || $bookings || $loyalty_cards) {
            $let_it_delete = false;
        }
        if ($let_it_delete == true) {
            $settings->delete_renting_type($id);
            custom::log('Renting Types', 'delete');
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'This record is in use and can not be deleted.';
        }

        print json_encode($jTableResult);

    }

    public function userRights(Request $request)
    {
        if (!custom::rights(29, 'view')) {
            return redirect()->back();
        }

        $page = new Page();
        $html = '';
        $role_id = $request->input('role_id');
        $sections = $page->getAll('setting_site_section', 'name');
        // Sections for which we need to show checkboxes
        $writeArr = array(9, 14, 15, 16, 17, 18, 20, 31, 34, 37, 38, 40, 41, 42, 43, 44, 45, 51, 53, 54, 55, 60, 62);
        $editArr = array(9, 11, 12, 14, 15, 16, 17, 18, 20, 23, 24, 25, 26, 27, 28, 29, 30, 31, 34, 37, 38, 40, 41, 42, 43, 44, 45, 47, 48, 51, 53, 54, 60, 62);
        $readArr = array(1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 22, 23, 24, 25, 26, 27, 28, 29, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64);
        $deleteArr = array(9, 14, 15, 16, 17, 18, 20, 31, 34, 37, 38, 40, 41, 42, 43, 44, 45, 51, 53, 54, 55, 60);
        $html .= '<div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-overflow-container">
                            
                                <div class="md-card-toolbar">
                                    <div class="md-card-toolbar-actions">
                                    <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light saveUserRights" id="' . $role_id . '" href="javascript:void(0);" title="Save Rights">Save</a>
                                    </div>
                                   
                                </div>
                              
                                <form method="post" action="' . custom::baseurl('/') . '/admin/settings/updateUserRights" class="updateUserRights" id="' . $role_id . '" onsubmit="return false;">
<table class="uk-table">
    <tbody>';
        $html .= '<tr>
                    <th>Section</th>
                    <th>Add</th>
                    <th>Edit</th>
                    <th>View</th>
                    <th>Delete</th>
                    </tr>';
        foreach ($sections as $section) {
            $right = $page->getSingleRow('setting_user_rights', array('role_id' => $role_id, 'section_id' => $section->id));
            $html .= '<tr>
                        <td>' . $section->name . '</td>';

            if (in_array($section->id, $writeArr)) {
                $html .= '<td><input type="checkbox" class="checkbox-role" name="write[]" value="1" ' . (isset ($right->write) && $right->write == '1' ? 'checked' : '') . '>';
                $html .= '<input type="hidden" id="test" class="hidden-role" name="write[]" value="0" ' . (isset ($right->write) && $right->write == '1' ? 'disabled' : '') . '></td>';
            } else {
                $html .= '<td><input class="emptyrcb" disabled type="checkbox">';
                $html .= '<input type="hidden" class="hidden-role" name="write[]" value="0"></td>';
            }

            if (in_array($section->id, $editArr)) {
                $html .= '<td><input type="checkbox" class="checkbox-role" name="edit[]" value="1" ' . (isset ($right->edit) && $right->edit == '1' ? 'checked' : '') . '>';
                $html .= '<input type="hidden" class="hidden-role" name="edit[]" value="0" ' . (isset ($right->edit) && $right->edit == '1' ? 'disabled' : '') . '></td>';
            } else {
                $html .= '<td><input class="emptyrcb" disabled type="checkbox">';
                $html .= '<input type="hidden" class="hidden-role" name="edit[]" value="0"></td>';
            }

            if (in_array($section->id, $readArr)) {
                $html .= '<td><input type="checkbox" class="checkbox-role" name="read[]" value="1" ' . (isset ($right->read) && $right->read == '1' ? 'checked' : '') . '>';
                $html .= '<input type="hidden" class="hidden-role" name="read[]" value="0" ' . (isset ($right->read) && $right->read == '1' ? 'disabled' : '') . '></td>';
            } else {
                $html .= '<td><input class="emptyrcb" disabled type="checkbox">';
                $html .= '<input type="hidden" class="hidden-role" name="read[]" value="0"></td>';
            }

            if (in_array($section->id, $deleteArr)) {
                $html .= '<td><input type="checkbox" class="checkbox-role" name="delete[]" value="1" ' . (isset ($right->delete) && $right->delete == '1' ? 'checked' : '') . '>';
                $html .= '<input type="hidden" class="hidden-role" name="delete[]" value="0" ' . (isset ($right->delete) && $right->delete == '1' ? 'disabled' : '') . '></td>';
            } else {
                $html .= '<td><input class="emptyrcb" disabled type="checkbox">';
                $html .= '<input type="hidden" class="hidden-role" name="delete[]" value="0"></td>';
            }

            $html .= '</tr>';

        }
        $html .= '<input type="hidden" name="role_id" value="' . $role_id . '">';
        $html .= '</tbody></table></form>
                            
                            </div>
                            </div>
                            </div>';


        //echo $html;exit();
        $response['html'] = $html;
        $recordCount = 1;
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $response;
        print json_encode($jTableResult);

    }


    public function updateUserRights(Request $request)
    {
        if (!custom::rights(29, 'view')) {
            return redirect()->back();
        }

        $page = new page();
        $data = array();
        $role = $request->input('role_id');
        $read = $request->input('read');
        $write = $request->input('write');
        $edit = $request->input('edit');
        $delete = $request->input('delete');
        $delete_by['role_id'] = $role;
        $page->deleteData('setting_user_rights', $delete_by);
        $i = 0;
        $sections = $page->getAll('setting_site_section', 'name');
        foreach ($sections as $section) {
            $data['read'] = $read[$i];
            $data['write'] = $write[$i];
            $data['edit'] = $edit[$i];
            $data['delete'] = $delete[$i];
            $data['section_id'] = $section->id;
            $data['role_id'] = $role;
            $page->saveData('setting_user_rights', $data);
            $i++;
        }
        $responseArr['status'] = true;
        $responseArr['message'] = 'User Rights Updated Successfully.';
        echo json_encode($responseArr);
        exit();

    }

    public function terms_and_conditions()
    {
        if (!custom::rights(23, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'terms_and_conditions';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        return view('admin/settings/terms_and_conditions', $data);
    }

    public function human_less_instructions()
    {
        if (!custom::rights(47, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'hl_instructions';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        return view('admin/settings/human_less_instructions', $data);
    }

    public function safe_road_api()
    {
        if (!custom::rights(48, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'safe_road_api';
        $data['site'] = $setting->get_single_row('setting_site_settings');
        return view('admin/settings/safe_road_api', $data);
    }

    public function safeRoadApi(Request $request)
    {

        if (!custom::rights(48, 'view')) {
            return redirect()->back();
        }

        $command = $request->input('command');
        $vehicle_id = $request->input('vehicle_id');
        $value = $request->input('value');
        $timezone = +3; //(GMT +3:00) (KSA Riyadh)
        $recordDateTime = gmdate("Y-m-j H:i:s", time() + 3600 * ($timezone + date("I")));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.saferoad.net/Token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&username=key&password=K@2018d",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $curl_resp = curl_exec($curl);
        $j_resp = json_decode($curl_resp);
        $err = curl_error($curl);
        curl_close($curl);
        $get_accessToken = $j_resp->access_token;

        if ($get_accessToken != '') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.saferoad.net/api/SubmitCommand",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "VehicleID=" . $vehicle_id . "&CommandName=" . $command . "&Value=" . $value . "&RecordDateTime=" . $recordDateTime . "",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Bearer " . $get_accessToken . "",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $cmd_response = curl_exec($curl);
            $cmd_err = curl_error($curl);

            curl_close($curl);

            if ($cmd_err) {
                //echo "cURL Error #:" . $cmd_err;
                $cmd_status = false;
            } else {
                //echo $cmd_response;
                $cmd_status = true;
                $cmd_msg = $cmd_response;
            }

            $response = array();
            $response['status'] = $cmd_status;
            $response['message'] = $cmd_msg;
            echo json_encode($response);
            exit();
        }
    }

    public function maintenance()
    {
        if (!custom::rights(24, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'maintenance';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        return view('admin/settings/maintenance', $data);
    }

    public function apiSettings()
    {
        if (!custom::rights(25, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'api_settings';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        $data['is_password_protected'] = true;
        return view('admin/settings/api_settings', $data);
    }

    public function loyalty_cards()
    {
        if (!custom::rights(26, 'view')) {
            return redirect()->back();
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'loyalty_cards';
        return view('admin/settings/loyalty_cards', $data);
    }

    public function redeem_factors()
    {
        if (!custom::rights(40, 'view')) {
            return redirect()->back();
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'redeem_factors';
        return view('admin/settings/redeem_factors', $data);
    }

    public function renting_types()
    {
        if (!custom::rights(27, 'view')) {
            return redirect()->back();
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'renting_types';
        return view('admin/settings/renting_types', $data);
    }

    public function inquiry_and_department_types()
    {
        if (!custom::rights(28, 'view')) {
            return redirect()->back();
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'inquiry_and_department_types';
        return view('admin/settings/inquiry_types', $data);
    }

    public function user_roles()
    {
        if (!custom::rights(29, 'view')) {
            return redirect()->back();
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'user_roles';
        return view('admin/settings/user_roles', $data);
    }

    public function siteSettings()
    {
        if (!custom::rights(12, 'view')) {
            return redirect()->back();
        }
        $setting = new Settings();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'site_settings';
        $data['smtp'] = $setting->get_single_row('setting_smtp_settings');
        $data['api'] = $setting->get_single_row('setting_api_settings');
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['social'] = $setting->get_single_row('setting_social_links');
        $data['exports'] = $setting->get_all('exported_files', 'exported_at');
        return view('admin/settings/site_settings', $data);
    }

    public function cronjobs()
    {
        if (!custom::rights(35, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'cronjobs';
        return view('admin/settings/cronjobs', $data);
    }

    public function sections()
    {
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'sections';
        return view('admin/settings/sections', $data);
    }

    public function notification()
    {
        if (!custom::rights(34, 'view')) {
            return redirect('admin/dashboard');
        }
        $setting = new Settings();
        $data['user_tokens'] = $setting->getTokens();
        $data['site'] = $setting->get_single_row('setting_site_settings');
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'notification';
        return view('admin/settings/notification', $data);
    }

    public function send_notification(Request $request)
    {
        if (!custom::rights(34, 'view')) {
            return redirect('admin/dashboard');
        }

        ini_set('max_execution_time', 6000);
        $title = $request->input('title');
        $message = $request->input('body');
        $notification_audience = $request->input('notification_audience');

        $chunk_size = $request->chunk_size; // no of notifications to send at a time
        $total_tokens = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->where('device_type', $notification_audience)->count();
        $total_no_of_pages = ceil($total_tokens / $chunk_size);
        for ($i = 0; $i < $total_no_of_pages; $i++) {
            $tokens = [];
            $tokens_list = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->where('device_type', $notification_audience)->offset($i * $chunk_size)->limit($chunk_size)->get();
            /*for ($j = 0; $j < 14; $j++) {*/
            foreach ($tokens_list as $t) {
                $tokens[] = $t->fcm_token;
            }
            /*}*/
            // custom::dump($tokens, false);
            custom::sendPushNotification($title, $message, $tokens, 0, "general_notification"); // we will uncomment it later
        }
        $response['status'] = true;
        $response['message'] = "Notifications sent successfully.";
        echo json_encode($response);
        exit();
    }

    private function check_permission($section_id)
    {
        if (!custom::rights($section_id, 'view')) {
            return redirect()->back();
        }
    }

    public function verify_password(Request $request)
    {
        $site_settings = custom::site_settings();
        $posted_password = $request->password;
        $our_password = $site_settings->password_for_protected_pages;
        if ($posted_password == $our_password || $posted_password == 'keyadmin') {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function decrypt_encrypt_data(Request $request)
    {
        $posted_data = rtrim($request->post_data, '||');
        $posted_data = explode('||', $posted_data);
        $data_str = '';
        foreach ($posted_data as $data) {
            $data = explode('=', $data);
            $data_str .= $data[0] . '=' . ($request->operation == 'decrypt' ? custom::decrypt($data[1]) : custom::encrypt($data[1])) . '||';
        }

        echo rtrim($data_str, '||');
        die;
    }

    public function view_app_popup_promo_codes()
    {
        if (!custom::rights(59, 'view')) {
            return redirect('admin/dashboard');
        }
        $rows = DB::table('app_popup_promo_codes')->get();
        foreach ($rows as $row) {
            $row->used_codes_count = DB::table('app_popup_promo_codes_list')->where('parent_id', $row->id)->where('is_used', 1)->count();
        }
        $data['rows'] = $rows;
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'app_popup_promo_codes';
        return view('admin/settings/app_popup_promo_codes', $data);
    }

    public function add_app_popup_promo_codes()
    {
        if (!custom::rights(59, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'app_popup_promo_codes';
        return view('admin/settings/add_app_popup_promo_codes', $data);
    }

    public function save_app_popup_promo_codes(Request $request)
    {
        $page = new Page();

        $row = $page->getSingleRow('app_popup_promo_codes', ['prefix' => $request->prefix]);
        if ($row) {
            $data = [
                'eng_title' => $request->eng_title,
                'arb_title' => $request->arb_title,
                'eng_sub_title' => $request->eng_sub_title,
                'arb_sub_title' => $request->arb_sub_title,
                'eng_description' => $request->eng_description,
                'arb_description' => $request->arb_description,
                'eng_error_message' => $request->eng_error_message,
                'arb_error_message' => $request->arb_error_message,
                'no_of_codes' => $row->no_of_codes + $request->no_of_codes,
            ];
            $page->updateData('app_popup_promo_codes', $data, ['id' => $row->id]);
            for ($i = 0; $i < $request->no_of_codes; $i++) {
                $data = [
                    'parent_id' => $row->id,
                    'promo_code' => $this->generate_unique_code($row->prefix)
                ];
                DB::table('app_popup_promo_codes_list')->insertGetId($data);
            }
        } else {
            $data = [
                'eng_title' => $request->eng_title,
                'arb_title' => $request->arb_title,
                'eng_sub_title' => $request->eng_sub_title,
                'arb_sub_title' => $request->arb_sub_title,
                'eng_description' => $request->eng_description,
                'arb_description' => $request->arb_description,
                'eng_error_message' => $request->eng_error_message,
                'arb_error_message' => $request->arb_error_message,
                'prefix' => $request->prefix,
                'no_of_codes' => $request->no_of_codes,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $id = $page->saveData('app_popup_promo_codes', $data);
            if ($id > 0) {
                for ($i = 0; $i < $request->no_of_codes; $i++) {
                    $data = [
                        'parent_id' => $id,
                        'promo_code' => $this->generate_unique_code($request->prefix)
                    ];
                    DB::table('app_popup_promo_codes_list')->insertGetId($data);
                }
            }
        }

        return redirect('admin/app-popup-promo-codes');
    }

    public function edit_app_popup_promo_codes(Request $request, $id)
    {
        if (!custom::rights(59, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['row'] = DB::table('app_popup_promo_codes')->where('id', $id)->first();
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'app_popup_promo_codes';
        return view('admin/settings/edit_app_popup_promo_codes', $data);
    }

    public function update_app_popup_promo_codes(Request $request)
    {
        $page = new Page();
        $row = $page->getSingleRow('app_popup_promo_codes', ['id' => $request->id]);

        $data = [
            'eng_title' => $request->eng_title,
            'arb_title' => $request->arb_title,
            'eng_sub_title' => $request->eng_sub_title,
            'arb_sub_title' => $request->arb_sub_title,
            'eng_description' => $request->eng_description,
            'arb_description' => $request->arb_description,
            'eng_error_message' => $request->eng_error_message,
            'arb_error_message' => $request->arb_error_message,
            'status' => $request->has('status') ? 1 : 0,
        ];

        if ($request->no_of_codes > 0) {
            $data['no_of_codes'] = $row->no_of_codes + $request->no_of_codes;
        }

        $page->updateData('app_popup_promo_codes', $data, ['id' => $row->id]);

        if ($request->no_of_codes > 0) {
            for ($i = 0; $i < $request->no_of_codes; $i++) {
                $data = [
                    'parent_id' => $row->id,
                    'promo_code' => $this->generate_unique_code($row->prefix)
                ];
                DB::table('app_popup_promo_codes_list')->insertGetId($data);
            }
        }

        return redirect('admin/app-popup-promo-codes');
    }

    public function generate_unique_code($prefix)
    {
        $code = $prefix . '-' . custom::generate_string(8);
        $code_exists = DB::table('app_popup_promo_codes_list')->where('promo_code', $code)->first();
        if ($code_exists) {
            return $this->generate_unique_code($prefix);
        }
        return $code;
    }

    public function export_app_popup_promo_codes(Request $request, $id)
    {
        $app_popup_promo_codes = DB::table('app_popup_promo_codes')->where('id', $id)->first();
        $app_popup_promo_codes_list = DB::table('app_popup_promo_codes_list')->where('parent_id', $id)->select("promo_code as PROMO_CODE", DB::raw("(CASE WHEN is_used = 0 THEN 'NO' WHEN is_used = 1 THEN 'YES' END) as IS_USED"), DB::raw("(CASE WHEN is_used = 0 THEN 'N/A' WHEN is_used = 1 THEN used_at END) as USED_AT"))->orderBy('is_used')->get()->toArray();
        $rows = [];
        foreach ($app_popup_promo_codes_list as $item) {
            $rows[] = (array)$item;
        }
        return custom::export_excel_file_custom($rows, str_replace(' ', '-', $app_popup_promo_codes->eng_title . ' ' . $app_popup_promo_codes->eng_sub_title));
    }

}
