<?php

namespace App\Helpers;
require_once(app_path() . '/libraries/Unifonic/Autoload.php');
require_once(app_path() . '/libraries/MobileDetect/Mobile_Detect.php');
require_once(app_path() . '/libraries/validateSAID.class.php');
require_once(app_path() . '/libraries/PhpMapContainCoords.php');

require_once(app_path() . '/libraries/UnifonicNextGen/vendor/autoload.php');

require_once(app_path() . '/libraries/htmlpurifier/library/HTMLPurifier.auto.php');

require_once(app_path() . '/libraries/firebase-jwt/vendor/autoload.php');

use App\Models\Admin\CarPrice;
use App\Models\Admin\Settings;
use Closure;
use Excel;
use Auth;
use DB;
use League\Flysystem\Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Mail;
use Config;
use Session;
use Carbon\Carbon;
use UnifonicNextGenLib\APIException;
use Validator;
use Request;
use App\Models\Front\Page as PageModel;
use App\Models\Admin\Page;
use App\Models\Admin\Booking;
use \Unifonic\API\Client;
use Jenssegers\Agent\Agent;

use Illuminate\Support\Facades\Crypt;

use stdClass;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

use App\Exports\ExportBookingsData;
use App\Exports\BookingsExport;
use App\Exports\CollectionsExport;
use App\Exports\CustomersExport;
use App\Exports\GeneralExport;

use App\Imports\GeneralImport;

use Maatwebsite\Excel\HeadingRowImport;

class Custom
{
    // this function is to get the dynamic path to using for js
    public static function getRootPath()
    {
        $full_url = $_SERVER['PHP_SELF'];
        $path_info = parse_url($full_url, PHP_URL_PATH);
        $appPath = str_replace('index.php', '', $path_info);
        return $appPath;
    }

    public static function common($var)
    {
        return $var;
    }

    public static function role()
    {
        $userID = Auth::id();
        $role = DB::table('users')
            ->where('id', '=', $userID)->select('type')->first();
        if ($role != "") {
            return $role->type;
        } else {
            return false;
        }
    }

    /*==this function is to save the logos of files globally==*/
    public static function saveImage($file)
    {
        $imagData = array();
        // for uploading the logo
        if ($file) {
            $destinationPath = 'assets/uploads';
            $orgName = $file->getClientOriginalName();
            $fileName = rand(11111, 99999) . '_' . $orgName;
            $file->move($destinationPath, $fileName);
            custom::compress_image($destinationPath, $fileName);
            $imagData['url'] = $fileName;
        }
        $imagData['owner'] = Auth::id();
        $imagData['uploaded_by'] = 0;
        $imagData['date_created'] = date("Y-m-d H:i:s");
        $imageId = Image::Create($imagData);
        return $imageId->id;
    }

    /*==this function is to save the address globally==*/
    public static function saveAddress($inputs)
    {
        $addressData = array();
        $addressData['street'] = $inputs['street'];
        $addressData['city'] = $inputs['city'];
        $addressData['zip'] = $inputs['zip'];
        $addressData['country'] = $inputs['country'];
        $addressId = Address::Create($addressData);
        return $addressId->id;

    }


    public static function getSingle($tb, $fetch_by)
    {
        $page = new \App\Models\Front\Page();
        return $page->getSingle($tb, $fetch_by);
    }


    public static function uploadImage($file_name, $pagename = 'ab')
    {
        // getting all of the post data
        $file = array('image' => $file_name);
        // setting up rules
        $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $rules);
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return false;
        } else {

            // checking file is valid.
            if ($file_name->isValid()) {
                $destinationPath = public_path('uploads'); // upload path
                $fileArray = explode('.', $file_name->getClientOriginalName()); // File Name
                $name = $fileArray[0];
                $name = str_replace(' ', '_', $name); // Did this to rename the file as replacing  ' ' with '_'
                $extension = $file_name->getClientOriginalExtension(); // getting image extension
                $fileName = $pagename . '-' . $name . time() . '.' . $extension; // renameing image
                $moved = $file_name->move($destinationPath, $fileName); // uploading file to given path
                if ($moved) {
                    // optimizing image
                    // custom::compress_image($destinationPath, $fileName);
                }
                // sending back with message
                return $fileName;
            } else {
                // sending back with error message.
                return false;
            }
        }
    }

    public static function optimizeImage($path_to_upload, $file)
    {
        $optimus = new \Optimus('1AUBDV468PD7KI0RUUBVRQP9');
        $img_name = $path_to_upload . '/' . $file;
        $opt_res = $optimus->optimize($img_name);
        file_put_contents($img_name, $opt_res);
    }

    public static function compress_image($path_to_upload, $file)
    {
        $path = $path_to_upload . '/' . $file;
        $destination_url = $path;

        $info = getimagesize($path);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($path);
            imagejpeg($image, $destination_url);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($path);
            imagegif($image, $destination_url);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($path);
            imagepng($image, $destination_url);
        }
    }

    public static function social_links()
    {
        $setting = new Settings();
        $data = $setting->get_single_row('setting_social_links');
        return $data;
    }

    public static function site_settings()
    {
        $setting = new Settings();
        $data = $setting->get_single_row('setting_site_settings');
        return $data;
    }

    public static function smtp_settings()
    {
        $setting = new Settings();
        $data = $setting->get_single_row('setting_smtp_settings');
        return $data;
    }

    public static function api_settings()
    {

        $setting = new Settings();

        $data = $setting->get_single_row('setting_api_settings');
        return $data;
    }


    public static function sendMail()
    {
        $email['subject'] = 'testing email';
        $email['fromEmail'] = 'key@ed.sa';
        $email['fromName'] = 'Key Rental';
        $email['toEmail'] = 'bilal_ejaz@astutesol.com';
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';
        $data['message'] = 'testing message';
        custom::sendEmail($type = 'general', $data, $email, $lang = 'eng');
    }

    public static function log($section, $type)
    {
        $message = '';
        if ($type == 'add') {
            $message = 'added a record';
        } elseif ($type == 'update') {
            $message = 'updated a record';
        } elseif ($type == 'delete') {
            $message = 'deleted a record';
        } elseif ($type == 'import') {
            $message = 'imported records';
        } elseif ($type == 'export') {
            $message = 'exported records';
        }

        if (Auth::check()) {
            $data['type'] = $type;
            $data['section'] = $section;
            $data['message'] = $message;
            $data['user_id'] = auth()->user()->id;
            $data['user_name'] = auth()->user()->name;
            $data['created_at'] = date('Y-m-d H:i:s');
            $settings = new Settings();
            $saved = $settings->log($data);
        }
    }


    public static function sendEmail($type = 'general', $data, $email, $lang = 'eng')
    {
        try {
            /*$email['subject'] = '';
        $email['fromEmail'] = '';
        $email['fromName'] = '';
        $email['toEmail'] = '';
        $email['ccEmail'] = '';
        $email['bccEmail'] = '';
        $email['attachment'] = '';*/

            $data['base_url'] = self::baseurl('/');
            if ($type == 'general') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.general_email';
                } else {
                    $template = 'frontend.emails.general_email_ar';
                }
            } elseif ($type == 'form') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.form_email';
                } else {
                    $template = 'frontend.emails.form_email_ar';
                }
            } elseif ($type == 'booking') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.booking_email_eng';
                } else {
                    $template = 'frontend.emails.booking_email_ar';
                }
            } elseif ($type == 'paylater') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.booking_invoice_email_eng';
                } else {
                    $template = 'frontend.emails.booking_invoice_email_ar';
                }
            } elseif ($type == 'cancel') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.booking_cancellation_email_eng';
                } else {
                    $template = 'frontend.emails.booking_cancellation_email_arb';
                }
            }

            // Setting custom email settings

            $smtp = custom::smtp_settings();

            Config::set('mail.host', $smtp->server);
            Config::set('mail.port', $smtp->port);
            Config::set('mail.username', $smtp->username);
            Config::set('mail.password', $smtp->password);
            Config::set('mail.encryption', $smtp->encryption);

            /*echo Config::get('mail.host');
            echo Config::get('mail.port');
            echo Config::get('mail.username');
            echo Config::get('mail.password');

            exit();*/

            $to_emails = explode(',', $email['toEmail']); // in case of multiple emails like for admin

            if ($to_emails) {
                foreach ($to_emails as $to_email) {
                    $to_email = trim($to_email);
                    Mail::send($template, $data, function ($mail) use ($email, $to_email) {
                        $mail->subject($email['subject']);
                        $mail->from($email['fromEmail'], $email['fromName']);
                        $mail->to($to_email);

                        if (isset($email['ccEmail']) && $email['ccEmail'] != '') {
                            $mail->cc($email['ccEmail']);
                        }

                        if (isset($email['bccEmail']) && $email['bccEmail'] != '') {
                            $mail->bcc($email['bccEmail']);
                        }

                        if (isset($email['attachment']) && $email['attachment'] != '') {
                            $mail->attach($email['attachment']);
                        }
                        // to attach multi files send array.
                        if (isset($email['multiAtach']) && count($email['multiAtach']) > 0) {
                            foreach ($email['multiAtach'] as $multiAtach) {
                                $mail->attach($multiAtach);
                            }
                        }

                    });
                }

                if (Mail::failures()) {
                    return false;
                }

                // delete pdf attachment if exist after attached to the email
                if (isset($email['pdf']) && $email['pdf'] == "pdf") {
                    \File::delete($email['attachment']);

                }
            }

            return true;
        } catch (\Exception $e) {
            return true;
        }

    }

    public static function sendEmail2($type, $data, $email, $lang = 'eng')
    {
        try {
            /*$email['subject'] = '';
            $email['fromEmail'] = '';
            $email['fromName'] = '';
            $email['toEmail'] = '';
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';
            $email['attachment'] = '';*/

            $data['base_url'] = self::baseurl('/');
            if ($type == 'general') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.general_email';
                } else {
                    $template = 'frontend.emails.general_email_ar';
                }
            } elseif ($type == 'form') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.form_email';
                } else {
                    $template = 'frontend.emails.form_email_ar';
                }
            } elseif ($type == 'booking') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.booking_email_eng';
                } else {
                    $template = 'frontend.emails.booking_email_ar';
                }
            } elseif ($type == 'cancel') {
                if ($lang == 'eng') {
                    $template = 'frontend.emails.booking_cancellation_email_eng';
                } else {
                    $template = 'frontend.emails.booking_cancellation_email_arb';
                }
            }

            // Setting custom email settings

            /*echo Config::get('mail.host');
            echo Config::get('mail.port');
            echo Config::get('mail.username');
            echo Config::get('mail.password');
            exit();*/

            if ($_SERVER['SERVER_NAME'] == 'www.key.sa' || $_SERVER['SERVER_NAME'] == 'key.sa') {

                Config::set('mail.host', 'mail.smtp2go.com');
                Config::set('mail.port', '587');
                Config::set('mail.username', 'api.not@key.sa');
                Config::set('mail.password', '#aPi$35');
                $email['fromEmail'] = 'api.not@key.sa';
            } else {
                $smtp = custom::smtp_settings();

                Config::set('mail.host', $smtp->server);
                Config::set('mail.port', $smtp->port);
                Config::set('mail.username', $smtp->username);
                Config::set('mail.password', $smtp->password);
                Config::set('mail.encryption', $smtp->encryption);
            }

            // echo Config::get('mail.username');
            // echo Config::get('mail.password');die();

            if ($email['fromEmail']) {
                Mail::send($template, $data, function ($mail) use ($email) {
                    $mail->subject($email['subject']);
                    $mail->from($email['fromEmail'], $email['fromName']);
                    //$mail->to($email['toEmail']);
                    $mail->to($email['toEmail']);

                    if (isset($email['ccEmail']) && $email['ccEmail'] != '') {
                        $mail->cc($email['ccEmail']);
                    }

                    if ($email['bccEmail'] != '') {
                        $mail->bcc($email['bccEmail']);
                    }

                    if ($email['attachment'] != '') {
                        $mail->attach($email['attachment']);
                    }
                    // to attach multi files send array.
                    if (isset($email['multiAtach']) && count($email['multiAtach']) > 0) {
                        foreach ($email['multiAtach'] as $multiAtach) {
                            $mail->attach($multiAtach);
                        }
                    }

                });

                // delete pdf attachment if exist after attached to the email
                if (isset($email['pdf']) && $email['pdf'] == "pdf") {
                    \File::delete($email['attachment']);

                }
            }
        } catch (\Exception $e) {
            return true;
        }

    }


    public function test()
    {
        echo $yesterday = Carbon::now()->subDays(1);
        echo $one_week_ago = Carbon::now()->subWeeks(1);
        exit();
    }


    public static function uploadFile($file_name)
    {
        $destinationPath = public_path('uploads'); // upload path
        $fileArray = explode('.', $file_name->getClientOriginalName()); // File Name
        $name = $fileArray[0];
        $extension = $file_name->getClientOriginalExtension(); // getting image extension
        $fileName = $name . '-' . time() . '.' . $extension; // renameing image
        $moved = $file_name->move($destinationPath, $fileName); // uploading file to given path
        if ($moved) {
            // optimizing image
            // custom::compress_image($destinationPath, $fileName);
        }
        // sending back with message
        return $fileName;
    }

    // kashif work function to change null values into empty string
    public static function isNullToEmpty($data)
    {
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $data[$key] = "";
            }
        }
        return $data;
    }

    public static function generateReservationCode($branch_prefix, $serial_no, $booking_source = 'W')
    {
        //$reservation_code = $booking_source . $branch_prefix . $serial_no;
        $reservation_code = $booking_source . $branch_prefix . str_pad($serial_no, 6, "0", STR_PAD_LEFT);
        return $reservation_code;

    }

    public static function checkIfUserLoggedin($lang)
    {

        $logged_in = Session::get('logged_in_from_frontend');
        if (is_null($logged_in)) {
            if ($lang == 'eng') {
                return '/home';
            } else {
                return '/ar/home';
            }
        } else {
            return "";
        }

    }

    public static function checkIfUserLoggedinHL($lang)/*Using for human less project*/
    {

        $logged_in = Session::get('logged_in_from_frontend');
        if (is_null($logged_in)) {
            if ($lang == 'eng') {
                return 'en/home';
            } else {
                return '/home';
            }
        } else {
            return "";
        }

    }


    public static function clearAllSessionsFromCheckout()
    {
        Session::forget('search_data');
        Session::forget('car_id');
        Session::forget('cpid');
        Session::forget('offer_car_model_id');
        Session::forget('rent_per_day');
        Session::forget('old_price');
        Session::forget('dropoff_charges_amount');
        Session::forget('total_rent_for_all_days');
        Session::forget('cdw_charges');
        Session::forget('cdw_plus_charges');
        Session::forget('gps_charges');
        Session::forget('extra_driver_charges');
        Session::forget('baby_seat_charges');
        Session::forget('promo_discount_amount');
        Session::forget('total_amount_after_discount');
        Session::forget('booking_id');
        Session::forget('total_amount_for_transaction');
        Session::forget('payment_form_data');
        Session::forget('found_user');
        Session::forget('customer_id_no_for_loyalty');
        Session::forget('loyalty_tried');
        Session::forget('found_user_verify');
        Session::forget('verify_at_search_field_for_loyalty');
        Session::forget('customer_loyalty_applied');
        Session::forget('verified_user_id');
        Session::forget('loyalty_discount_percent');
        Session::forget('daily_rate_coupon_applied');
        Session::forget('loyalty_type_used');

        Session::forget('coupon_code');
        Session::forget('coupon_applied');
        Session::forget('is_promo_discount_on_total');
        Session::forget('promo_auto_applied');
        Session::forget('promotion_id');
        Session::forget('renting_type_id');
        Session::forget('car_rate_is_with_additional_utilization_rate');
        Session::forget('minus_discount');

        Session::forget('unfilled_survey_customer_id');

        Session::forget('vat_mode');
        Session::forget('vat_percentage');
        Session::forget('vat');
        Session::forget('totalPriceWithVat');
        Session::forget('qitaf_request');
        Session::forget('qitaf_amount');
        Session::forget('hyper_pay_transaction_error');
        Session::forget('niqaty_request');
        Session::forget('niqaty_amount');

        Session::forget('is_free_cdw_promo_applied');
        Session::forget('is_free_cdw_plus_promo_applied');
        Session::forget('is_free_baby_seat_promo_applied');
        Session::forget('is_free_driver_promo_applied');
        Session::forget('is_free_open_km_promo_applied');
        Session::forget('is_free_delivery_promo_applied');
        Session::forget('is_free_dropoff_promo_applied');

        return true;
    }

    public static function clearSessionsFromCheckoutForGuest()
    {
        Session::forget('search_data');
        Session::forget('car_id');
        Session::forget('cpid');
        Session::forget('offer_car_model_id');
        Session::forget('rent_per_day');
        Session::forget('old_price');
        Session::forget('dropoff_charges_amount');
        Session::forget('total_rent_for_all_days');
        Session::forget('cdw_charges');
        Session::forget('cdw_plus_charges');
        Session::forget('gps_charges');
        Session::forget('extra_driver_charges');
        Session::forget('baby_seat_charges');
        Session::forget('promo_discount_amount');
        Session::forget('total_amount_after_discount');
        //Session::forget('booking_id');
        Session::forget('total_amount_for_transaction');
        //Session::forget('payment_form_data');
        Session::forget('found_user');
        Session::forget('customer_id_no_for_loyalty');
        Session::forget('loyalty_tried');
        Session::forget('found_user_verify');
        Session::forget('verify_at_search_field_for_loyalty');
        Session::forget('customer_loyalty_applied');
        Session::forget('verified_user_id');
        Session::forget('loyalty_discount_percent');
        Session::forget('daily_rate_coupon_applied');
        Session::forget('loyalty_type_used');

        Session::forget('coupon_code');
        Session::forget('coupon_applied');
        Session::forget('is_promo_discount_on_total');
        Session::forget('promo_auto_applied');
        Session::forget('promotion_id');
        Session::forget('renting_type_id');
        Session::forget('car_rate_is_with_additional_utilization_rate');
        Session::forget('minus_discount');

        Session::forget('unfilled_survey_customer_id');

        Session::forget('vat_mode');
        Session::forget('vat_percentage');
        Session::forget('vat');
        Session::forget('totalPriceWithVat');
        Session::forget('qitaf_request');
        Session::forget('qitaf_amount');
        Session::forget('hyper_pay_transaction_error');
        Session::forget('niqaty_request');
        Session::forget('niqaty_amount');

        Session::forget('is_free_cdw_promo_applied');
        Session::forget('is_free_cdw_plus_promo_applied');
        Session::forget('is_free_baby_seat_promo_applied');
        Session::forget('is_free_driver_promo_applied');
        Session::forget('is_free_open_km_promo_applied');
        Session::forget('is_free_delivery_promo_applied');
        Session::forget('is_free_dropoff_promo_applied');

        return true;

    }

    public static function clearPromoSessions()
    {
        Session::forget('promo_discount_amount');
        Session::forget('total_amount_after_discount');
        Session::forget('total_amount_for_transaction');
        Session::forget('daily_rate_coupon_applied');
        Session::forget('coupon_code');
        Session::forget('coupon_applied');
        Session::forget('is_promo_discount_on_total');
        Session::forget('minus_discount');
        return true;
    }

    public static function clearCheckoutSessions()
    {
        Session::forget('promo_discount_amount');
        Session::forget('total_amount_after_discount');
        Session::forget('total_amount_for_transaction');
        Session::forget('daily_rate_coupon_applied');
        Session::forget('coupon_code');
        Session::forget('coupon_applied');
        Session::forget('is_promo_discount_on_total');
        Session::forget('minus_discount');
        return true;
    }


    public static function changeTitles($lang, $title)
    {

        if ($lang == "eng") {
            return $title;
        } else {

            if ($title == "name") {
                $title = "الاسم";
            }
            if ($title == "email") {
                $title = "البريد الإلكتروني";
            }
            if ($title == "phone") {
                $title = "الهاتف";
            }
            if ($title == "id_no") {
                $title = "رقم بطاقة الهوية";
            }
            if ($title == "id_type") {
                $title = "نوع الهوية";
            }
            if ($title == "id_version") {
                $title = "نسخة الهوية";
            }
            if ($title == "id_version") {
                $title = "نسخة الهوية";
            }
            if ($title == "nationality") {
                $title = "الجنسية";
            }
            if ($title == "date_of_birth") {
                $title = "تاريخ الولادة";
            }
            if ($title == "id_expiry_date") {
                $title = "تاريخ انتهاء الصلاحية";
            }
            if ($title == "licence_id") {
                $title = "معرف الترخيص";
            }
            if ($title == "license_no") {
                $title = "رقم الرخصة";
            }
            if ($title == "license_expiry_date") {
                $title = "تاريخ انتهاء صلاحية الترخيص";
            }
            if ($title == "job_title") {
                $title = "المسمى الوظيفي";
            }
            if ($title == "street_address") {
                $title = "الشارع";
            }
            if ($title == "district_address") {
                $title = "الحي";
            }
            if ($title == "id_country") {
                $title = "دولة إصدار الهوية";
            }
            if ($title == "license_id_type") {
                $title = "نوع رخصة القيادة";
            }
            if ($title == "license_country") {
                $title = "دولة إصدار الرخصة";
            }
            if ($title == "sponsor") {
                $title = "الكفيل";
            }
            if ($title == "registration_number") {
                $title = "رقم التسجيل";
            }
            return $title;
        }


    }


    // load fleet page html
    public static function fleetPageHtml($car_models, $base_url, $lang_base_url, $lang)
    {
        $site = custom::site_settings();
        $html = "";
        $items = [];
        foreach ($car_models as $car_model) {
            $car_rac_rate = DB::table('car_price')
                ->where('car_model_id', $car_model['id'])
                ->where('charge_element', 'Rent')
                ->where('renting_type_id', 1)
                ->where('customer_type', 'Individual')->value('price');
            $carImage = $base_url . "/public/uploads/" . $car_model['image1'];
            $car = $car_model['ct_' . $lang . '_title'] . " " . $car_model[$lang . '_title'] . " " . $car_model['year'];
            $carTitle = $car_model['cc_' . $lang . '_title'];
            $description = ($lang == 'eng' ? $car_model['eng_description'] : $car_model['arb_description']);
            if ($lang == "eng") $label = "Features"; else $label = "الميزات";
            if ($lang == "eng") $bookNow = "Book Now"; else $bookNow = "احجز الآن";
            $bags = $car_model['no_of_bags'];
            $doors = $car_model['no_of_doors'];
            $passengers = $car_model['no_of_passengers'];
            $min_age = $car_model['min_age'];
            $trans = $car_model['transmission'] == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي');

            $url = $lang_base_url . '/fleet/booking/' . $car_model['id'] . '?car='.str_replace(' ', '-', $car);

            if ($site->maintenance_mode == 'on') {
                $bookNowButton = '<a href="javascript:void(0);" onclick="siteUnderMaintenance();"><input type="button" class="edBtn" value="' . $bookNow . '" ></a>';
            } else {
                $bookNowButton = '<a href="' . $url . '"><input type="button" class="edBtn" value="' . $bookNow . '" ></a>';
            }

            $currency_per_days = trans('labels.currency') . ' / ' . ($lang == "eng" ? "Day" : "يوم");

            $disablility_html = '';
            if ($car_model['is_for_disabled'] == 1) {
                $disablility_html = '<div class="disability-div"><img src="'.$base_url.'/public/frontend/images/web-disability-'.$lang.'.png"></div>';
            }

            if (!custom::is_mobile()) {
                $html .= '<div class="singleRow">
			<div class="imgBox">
			<div class="listViewCarImg">
				<img src="' . $carImage . '" alt="' . $car_model['image1_' . $lang . '_alt'] . '" height="132" width="274" />
				</div>
			</div>
			<div class="bookDtlSec">
				<div class="bookPSec">
					<div class="bookFeature ' . ($min_age > 0 ? 'contains-min-age' : '') . '">
                    <h2>' . $car . '
                    <span>' . trans('labels.or_similar') . '</span></h2>
                    <h3>' . $carTitle . '</h3>
                    <div class="gridViewCarImg">
                         <img src="' . $carImage . '" alt=""' . $car_model['image1_' . $lang . '_alt'] . '""/>
                    </div>
                    '.$disablility_html.'
                    <div class="fleet-car-price" style="display: flex;justify-content: center;align-items: center;gap: 5px;margin-bottom: 12px;">
                        <p style="font-size: 15px;font-weight: 500;">' . $car_rac_rate . " " . $currency_per_days . '</p>
                        <p style="font-size: 11px;">('.($lang == "eng" ? "Before Loyalty Discount" : "قبل خصم الولاء").')</p>
                    </div>
                    <ul>
							<li><div class="spIconF person"></div>		<p>' . $passengers . '</p>		</li>
							<li><div class="spIconF transmition"></div>	<p>' . $trans . '</p>		</li>
							<li><div class="spIconF door"></div>		<p>' . $doors . '</p>		</li>
							<li><div class="spIconF bag"></div>			<p>' . $bags . '</p>		</li>';
                if ($min_age > 0) {
                    $html .= '<li><div class="spIconF minAge"></div>		<p>' . $min_age . '</p>		</li>';
                }
                $html .= ' </ul>
                </div>
					
					<div class="col bookBtn">
						' . $bookNowButton . '
					</div>

					<div class="clearfix"></div>
				</div>
			</div>
			<div class="clearfix"></div>';
                if ($car_model['is_special_car'] == 'yes') {
                    $html .= ' <div class="tagSpecialCar" >
                            <img src="' . $base_url . '/public/frontend/images/' . $lang . '_specialFeature.png?v=?' . rand() . '"> 
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car_model['id'] . '" >
                            </a >
                        </div >';
                }
                $html .= '</div>';

            } else {

                if ($site->maintenance_mode == 'on') {
                    $bookNowButton_mobile = '<a href="javascript:void(0);" onclick="siteUnderMaintenance();">';
                } else {
                    $bookNowButton_mobile = '<a href="' . $url . '">';
                }

                $html .= '<div class="singleRow">';
                if ($car_model['is_special_car'] == 'yes') {
                    $html .= ' <div class="tagSpecialCar" >
                            <img src="' . $base_url . '/public/frontend/images/specialFeature_mobile.png?v=?' . rand() . '"> 
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car_model['id'] . '" >
                            </a >
                        </div >';
                }
                $html .= $bookNowButton_mobile . '<h2>' . $car . '</h2>
						<h3>' . $carTitle . '</h3>	
						<div class="imgBox">
                            <img src="' . $carImage . '" alt="' . $car_model['image1_' . $lang . '_alt'] . '" height="132" width="274" />
                        </div>
                        <div class="fleet-car-price" style="display: flex;justify-content: center;align-items: center;gap: 5px;color: black;">
                            <p style="font-size: 15px;font-weight: 500;">' . $car_rac_rate . " " . $currency_per_days . '</p>
                            <p style="font-size: 11px;">('.($lang == "eng" ? "Before Loyalty Discount" : "قبل خصم الولاء").')</p>
                        </div>
                        
                        ';


                $html .= '</a></div>';
            }

            $item_data = [
                'item_id' => $car_model['ct_eng_title'] . " " . $car_model['eng_title'] . " " . $car_model['year'],
                'item_name' => $car_model['ct_eng_title'] . " " . $car_model['eng_title'] . " " . $car_model['year']
            ];
            $items[] = $item_data;

        }

        $event = 'view_item_list';
        $event_data = [
            'items' => $items
        ];
        custom::sendEventToGA4($event, $event_data);

        return $html;
    }


    public static function searchResultPageHtml($cars, $base_url, $lang_base_url, $lang, $loyalty_discount_percent = 0)
    {
        $site_settings = custom::site_settings();
        $page = new \App\Models\Front\Page();
        $html = "";

        if (empty($cars)) {
            return $html;
        }

        $sessionVals = Session::get('search_data');
        $promo_discount_percent = 0;

        $query = "SELECT * FROM setting_renting_type WHERE from_days <= '" . $sessionVals['days'] . "' order by from_days desc limit 1";
        $result = DB::select($query);
        $renting_type_id = $result[0]->id;

        $renting_type_details = $page->getSingle('setting_renting_type', ['id' => $renting_type_id]);


        $round_prices = true;
        if ($sessionVals['is_delivery_mode'] == 4) {
            $sessionVals['days'] = 30;
            $round_prices = false;
        }

        $search_by['is_delivery_mode'] = $sessionVals['is_delivery_mode'];
        $search_by['subscribe_for_months'] = (isset($sessionVals['subscribe_for_months']) ? $sessionVals['subscribe_for_months'] : 0);
        $date_picked_up_for_hours_cal = new Carbon($sessionVals['pickup_date'] . ' ' . $sessionVals['pickup_time']);
        $date_dropped_off_for_hours_cal = new Carbon($sessionVals['dropoff_date'] . ' ' . $sessionVals['dropoff_time']);
        $sessionVals['hours_diff'] = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);

        $isLimousine = (isset($sessionVals['isLimousine']) ? $sessionVals['isLimousine'] : 0);

        /*this functionality to get pickup and current date difference to check booking days limit */
        $start = date('Y-m-d');
        $pickup_date = date('Y-m-d', strtotime($sessionVals['pickup_date']));
        $dateDiff = self::getDifferenceInDates($start, $pickup_date, 'days');
        $k = 0;

        $arrData['region_id'] = $sessionVals['from_region_id'];
        $arrData['city_id'] = $sessionVals['from_city_id'];
        $arrData['pickup_date'] = $sessionVals['pickup_date'];
        $arrData['dropoff_date'] = $sessionVals['dropoff_date'];

        if ($isLimousine == 0) {
            $avail_array_return = self::checkIfModelExistGetInArray($arrData);

            $avail_array_car_ids = $avail_array_return[0];
            $avail_array_per_day = $avail_array_return[1];

            $avail_array_utilization_percentage_1 = $avail_array_return[2];
            $avail_array_increase_price_percentage_1 = $avail_array_return[3];

            $avail_array_utilization_percentage_2 = $avail_array_return[4];
            $avail_array_increase_price_percentage_2 = $avail_array_return[5];

            $avail_array_utilization_percentage_3 = $avail_array_return[6];
            $avail_array_increase_price_percentage_3 = $avail_array_return[7];

            $car_ids_for_availability = array();
            foreach ($cars as $car) {
                $car_ids_for_availability[] = $car->id;
            }

            $arrDataBookings['city_id'] = $sessionVals['from_city_id'];
            $arrDataBookings['pickup_date'] = $sessionVals['pickup_date'];
            $arrDataBookings['car_ids_for_availability'] = implode(',', $car_ids_for_availability);

            $availabilityBookings = self::checkCarAvailabilityBookings($arrDataBookings);

            $booking_car_models = array();
            $booking_car_models_count = array();
            foreach ($availabilityBookings as $availabilityBooking) {
                $booking_car_models[] = $availabilityBooking->car_model_id;
                $booking_car_models_count[] = $availabilityBooking->count;
            }
        }

        $items = [];
        foreach ($cars as $car) {

            $disablility_html = '';
            if ($car->is_for_disabled == 1) {
                $disablility_html = '<div class="disability-div"><img src="'.$base_url.'/public/frontend/images/web-disability-'.$lang.'.png"></div>';
            }

            if ($isLimousine == 0) {

                $car_rate_is_with_additional_utilization_rate = 0;

                /*checking if car is available for booking, this will return true or false*/
                $booking_per_day_found_bookings = 0;
                $booking_car_id_key = array_search($car->id, $avail_array_car_ids);
                $booking_per_day_found = $avail_array_per_day[$booking_car_id_key];
                if ($booking_per_day_found !== false) {
                    if ($booking_per_day_found) {
                        $booking_car_id_key_booking = array_search($car->id, $booking_car_models);
                        if ($booking_car_id_key_booking !== false) {
                            $booking_per_day_found_bookings = $booking_car_models_count[$booking_car_id_key_booking];
                        }

                        if ($booking_per_day_found_bookings < $booking_per_day_found) {

                            $availability = true;

                            /*We will overwrite price and old price here depending upon car utilization*/

                            if ($site_settings->car_utilization_mode == 'on' && false) {

                                $price = $car->price;
                                $old_price = $car->old_price;

                                $no_of_bookings_allowed_per_day = $booking_per_day_found;
                                $no_of_currently_active_bookings = $booking_per_day_found_bookings;

                                $utilization_percentage_1 = $avail_array_utilization_percentage_1[$booking_car_id_key];
                                $increase_price_percentage_1 = $avail_array_increase_price_percentage_1[$booking_car_id_key];

                                $utilization_percentage_2 = $avail_array_utilization_percentage_2[$booking_car_id_key];
                                $increase_price_percentage_2 = $avail_array_increase_price_percentage_2[$booking_car_id_key];

                                $utilization_percentage_3 = $avail_array_utilization_percentage_3[$booking_car_id_key];
                                $increase_price_percentage_3 = $avail_array_increase_price_percentage_3[$booking_car_id_key];

                                if ($utilization_percentage_1 > 0 && $increase_price_percentage_1 > 0) {
                                    $bookings_allowed_as_per_factor_1 = ($utilization_percentage_1 / 100) * $no_of_bookings_allowed_per_day;
                                    if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_1) {
                                        $car->price = $price + (($increase_price_percentage_1 / 100) * $price);
                                        $car->old_price = $old_price + (($increase_price_percentage_1 / 100) * $old_price);
                                        $car_rate_is_with_additional_utilization_rate = 1;
                                    }
                                }

                                if ($utilization_percentage_2 > 0 && $increase_price_percentage_2 > 0) {
                                    $bookings_allowed_as_per_factor_2 = ($utilization_percentage_2 / 100) * $no_of_bookings_allowed_per_day;
                                    if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_2) {
                                        $car->price = $price + (($increase_price_percentage_2 / 100) * $price);
                                        $car->old_price = $old_price + (($increase_price_percentage_2 / 100) * $old_price);
                                        $car_rate_is_with_additional_utilization_rate = 1;
                                    }
                                }

                                if ($utilization_percentage_3 > 0 && $increase_price_percentage_3 > 0) {
                                    $bookings_allowed_as_per_factor_3 = ($utilization_percentage_3 / 100) * $no_of_bookings_allowed_per_day;
                                    if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_3) {
                                        $car->price = $price + (($increase_price_percentage_3 / 100) * $price);
                                        $car->old_price = $old_price + (($increase_price_percentage_3 / 100) * $old_price);
                                        $car_rate_is_with_additional_utilization_rate = 1;
                                    }
                                }

                            }

                            if (!Session::has('corporate_customer_id') && $renting_type_details->oracle_reference_number == '26757' && $sessionVals['is_delivery_mode'] == 0 && $site_settings->car_utilization_addition_subtraction_mode == 'on') {
                                $days_to_check_for_car_utilization_addition_subtraction_mode = $site_settings->days_to_check_for_car_utilization_addition_subtraction_mode;
                                if ($days_to_check_for_car_utilization_addition_subtraction_mode > 0) {

                                    $date1 = Carbon::parse(date('Y-m-d'));
                                    $date2 = Carbon::parse($sessionVals['pickup_date']);

                                    if ($date1->diffInDays($date2) < $days_to_check_for_car_utilization_addition_subtraction_mode) {

                                        $from_branch_details = $page->getSingle('branch', ['id' => $sessionVals['from_branch_id']]);
                                        $car_model_details = $page->getSingle('car_model', ['id' => $car->id]);

                                        $get_by = [
                                            'branch' => $from_branch_details->oracle_reference_number,
                                            'car_type' => $car_model_details->oracle_reference_number,
                                            'car_model' => $car_model_details->year,
                                        ];
                                        $car_utilization_setup = $page->getSingle('setting_car_utilization_setup', $get_by);
                                        if ($car_utilization_setup && $car_utilization_setup->addition_or_subtraction_percentage != 0) {
                                            $car->price = $car->price + (($car_utilization_setup->addition_or_subtraction_percentage / 100) * $car->price);
                                            $car->old_price = $car->old_price + (($car_utilization_setup->addition_or_subtraction_percentage / 100) * $car->old_price);
                                            $car_rate_is_with_additional_utilization_rate = $car_utilization_setup->id;
                                        }
                                    }

                                }
                            }

                        } else {
                            $availability = false;
                        }


                    } else {
                        $availability = false;
                    }
                } else {
                    $availability = true;
                }
            } else {
                $availability = true;
                $car_rate_is_with_additional_utilization_rate = 0;
            }


            $singleRowPaddingCss = '';
            // check if car has any redeem offer setup and active against it. Criteria for checking is as follows:
            // 1. It must be having the from region id as region id.
            // 2. It must be having the car id thats in search results.
            // 3. Percentage of open contracts, means to check how many number of open contracts are there for this car model, it must be less than prescribed ones.
            // $car_info = $page->getSingle('car_model', array('id' => $car->id)); // commented
            if ($site_settings->redeem_offer_mode == 'on' && $sessionVals['is_delivery_mode'] != 2 && $sessionVals['is_delivery_mode'] != 4) { // if redeem offer mode is enabled from backend
                // $sessionVals['from_region_id'], $car->id
                $redeemOfferAvailable = $page->checkIfRedeemAllowed($sessionVals['from_region_id'], $car->car_type_id, $car->id, $sessionVals['pickup_date']);
                //echo "Record is here: ";echo '<pre>';print_r($redeemOfferAvailable);
                if ($redeemOfferAvailable) {
                    $no_of_currently_open_contracts = $page->getNoOfOpenContracts($car->id, $sessionVals['from_region_id']); // Getting number of open contracts for the car model at present.
                    $no_of_cars_present = $redeemOfferAvailable->no_of_cars_present;
                    $percentage_of_open_contracts = $redeemOfferAvailable->percentage_of_open_contracts;
                    $no_of_open_contracts_allowed = ($percentage_of_open_contracts / 100) * $no_of_cars_present;
                    if ($no_of_currently_open_contracts < $no_of_open_contracts_allowed) {
                        $hasRedeemOffer = true;
                        $redeemHtml = '<div class="redeemText"><span><img src="' . $base_url . '/public/frontend/images/redeem_offer_logo.png?v=0.1" class="redeemOfferImage">' . ($lang == 'eng' ? 'Redeem Offer Available' : 'عرض استبدال نقاط الولاء') . '</span></div>';
                        $singleRowPaddingCss = 'padding-top: 36px;';
                    } else {
                        $hasRedeemOffer = false;
                        $redeemHtml = '';
                    }

                } else {
                    $hasRedeemOffer = false;
                    $redeemHtml = '';
                }
            } else {
                $hasRedeemOffer = false;
                $redeemHtml = '';
            }
            $type_of_discount = '% ';
            $new_rent_per_day = round($car->price, ($round_prices ? 2 : 1000));
            $old_rent_per_day = round($car->old_price, ($round_prices ? 2 : 1000));
            $loyalty_discount_percentage = ((float)Session::get('loyalty_discount_percent') != '' ? (float)Session::get('loyalty_discount_percent') : 0);
            $discount_percentage = $loyalty_discount_percentage;
            if (Session::get('logged_in_from_frontend') == true && Session::get('user_type') == "corporate_customer") {
                $customer_type_for_auto_promo = "Corporate";
            } else {
                $customer_type_for_auto_promo = "Individual";
            }
            $promo_discount = $page->checkAutoPromoDiscount($car->id, date('Y-m-d H:i:s', strtotime($sessionVals['pickup_date'])), $sessionVals['from_region_id'], $sessionVals['from_city_id'], $sessionVals['from_branch_id'], Session::get('search_data')['days'], $customer_type_for_auto_promo, $sessionVals['is_delivery_mode']);

            $coupon_is_valid_for_pickup_day = self::is_promotion_valid_for_pickup_day($promo_discount, $sessionVals['pickup_date']);

            $is_promotion_auto_apply = false;

            if ($promo_discount && $promo_discount->discount > 0 && $coupon_is_valid_for_pickup_day) {
                if ($promo_discount->type == 'Fixed Price Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $after_fixed_price_discount = round($old_rent_per_day - $promo_discount->discount, ($round_prices ? 2 : 1000));
                    if ($after_fixed_price_discount > $new_rent_per_day) {
                        $discount_amount = $promo_discount->discount;
                        $new_rent_per_day = $old_rent_per_day - $discount_amount;
                        $new_rent_per_day = round($new_rent_per_day, ($round_prices ? 2 : 1000));
                        $discount_percentage = (float)$discount_amount;
                        // $type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';
                        $discount_percentage = round(($discount_percentage / $old_rent_per_day) * 100, 2);
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $promo_discount_percent = $promo_discount->discount;

                    if ($promo_discount_percent > 0 && $promo_discount_percent > $loyalty_discount_percentage) {
                        $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                        $new_rent_per_day = $old_rent_per_day - $discount_amount;
                        $new_rent_per_day = round($new_rent_per_day, ($round_prices ? 2 : 1000));
                        $discount_percentage = (float)$promo_discount_percent;
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply on Loyalty') {
                    $is_promotion_auto_apply = true;
                    $promo_discount_percent = $promo_discount->discount + $loyalty_discount_percentage;
                    $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                    $new_rent_per_day = $old_rent_per_day - $discount_amount;
                    $new_rent_per_day = round($new_rent_per_day, ($round_prices ? 2 : 1000));
                    $discount_percentage = (float)$promo_discount_percent;
                } elseif ($promo_discount->type == 'Fixed Daily Rate Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $after_fixed_price_discount = $promo_discount->discount;
                    if ($after_fixed_price_discount < $new_rent_per_day) {
                        $discount_amount = $promo_discount->discount;
                        $new_rent_per_day = round($promo_discount->discount, ($round_prices ? 2 : 1000));
                        $discount_percentage = $old_rent_per_day - $discount_amount;
                        // $type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';
                        $discount_percentage = round(($discount_percentage / $old_rent_per_day) * 100, 2);
                    }
                }
            }

            if (Session::get('loyalty_discount_percent') != '' && $sessionVals['is_delivery_mode'] != 4) {
                $old_price = '<span class="less-price"><span class="del-price">' . number_format(round($car->old_price, ($round_prices ? 2 : 1000)), 2) . " " . \Lang::get('labels.currency') . '</span><span class="car_discount_factor"> ' . $discount_percentage . $type_of_discount . \Lang::get('labels.off') . '</span></span>';
            } else {
                $old_price = '';
            }

            if (!custom::is_mobile()) {
                $html .= '<div class="singleRow '.( $sessionVals['is_delivery_mode'] == 4 ? 'has_subscription_rates' : '').' '.($availability ? 'available' : 'sold_out').'" style="' . $singleRowPaddingCss . '" id="section_for_car_'.$car->id.'">
' . $redeemHtml . '
                    <div class="imgBox">';

                if ($car->image1 != '') {
                    $car_image_path = $base_url . '/public/uploads/' . $car->image1;
                } else {
                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                }
                $carName = ($lang == 'eng' ? $car->ct_eng_title : $car->ct_arb_title) . " " . ($lang == 'eng' ? $car->eng_title : $car->arb_title) . ' ' . $car->year;
                $lable = \Lang::get('labels.or_similar');

                $description = ($lang == 'eng' ? $car->eng_description : $car->arb_description);
                $specialCarDescription = '';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $specialCarDescription = ($lang == 'eng' ? $car->eng_special_car_desc : $car->arb_special_car_desc);
                }
                $title = ($lang == 'eng' ? $car->cc_eng_title : $car->cc_arb_title);
                $feature = \Lang::get('labels.features');
                $trans = ($car->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي'));
                $rent = ($search_by['is_delivery_mode'] == 2 ? \Lang::get('labels.rent_per_hour') : \Lang::get('labels.rent_per_day'));

                if (isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1) {
                    $rent =  \Lang::get('labels.rent_per_trip');
                }

                $currency = \Lang::get('labels.currency');
                $rentSmall = \Lang::get('labels.total_rent_for_small');

                $days = ($search_by['is_delivery_mode'] == 2 ? \Lang::get('labels.hours') : ($search_by['is_delivery_mode'] == 4 ? \Lang::get('labels.months') : \Lang::get('labels.days')));
                if ($site_settings->vat_mode == 'on') {
                    $vat_message = '<h4 style="color: #4B4B4B;">' . \Lang::get('labels.vat_not_included') . '</h4>';
                } else {
                    $vat_message = '';
                }
                $html .= '<div class="listViewCarImg"><img src="' . $car_image_path . '" alt="' . ($lang == 'eng' ? $car->image1_eng_alt : $car->image1_arb_alt) . '" /></div>
        </div>
        <div class="bookDtlSec">
            <div class="bookName">
                <input type="hidden" name="oracle_reference_number" value="' . $car->oracle_reference_number . '">
                </div>
            <div class="bookPSec">
                <div class="bookFeature ' . ($car->min_age > 0 ? 'contains-min-age' : '') . '">
                    <h2>' . $carName . '
                    <span>' . $lable . '</span></h2>
                    <h3>' . $title . '</h3>
                    <div class="gridViewCarImg">
                            <img src="' . $car_image_path . '" alt="' . ($lang == 'eng' ? $car->image1_eng_alt : $car->image1_arb_alt) . '"/>
                    </div>
                    '.$disablility_html.'
                    <ul>
                        <li>
                            <div class="spIconF person"></div>
                            <p>' . $car->no_of_passengers . '</p></li>
                        <li>
                            <div class="spIconF transmition"></div>
                            <p>' . $trans . '</p>
                        </li>
                        <li>
                            <div class="spIconF door"></div>
                            <p>' . $car->no_of_doors . '</p></li>
                        <li>
                            <div class="spIconF bag"></div>
                            <p>' . $car->no_of_bags . '</p></li>';
                if ($car->min_age > 0) {
                    $html .= '<li>
                            <div class="spIconF minAge"></div>
                            <p>' . $car->min_age . '</p></li>';
                }
                $html .= ' </ul>
                </div>';

                if ($search_by['is_delivery_mode'] == 4) {

                    $subscription_options = '';
                    $subscribe_for_months = [3, 6, 9, 12];
                    foreach ($subscribe_for_months as $for_month) {
                        if ($for_month == 3) {
                            $subscription_rent_price = $car->three_month_subscription_price;
                        }
                        if ($for_month == 6) {
                            $subscription_rent_price = $car->six_month_subscription_price;
                        }
                        if ($for_month == 9) {
                            $subscription_rent_price = $car->nine_month_subscription_price;
                        }
                        if ($for_month == 12) {
                            $subscription_rent_price = $car->twelve_month_subscription_price;
                        }
                        $subscription_options .= '<button class="grayishButton ' . ($search_by['subscribe_for_months'] == $for_month ? 'active' : '') . '" data-subscribe_for_months="' . $for_month . '" data-subscription_rent_price="' . $subscription_rent_price . '" data-car_id="'.$car->id.'"><small>' . $for_month . ' ' . trans('labels.months') . '</small><div>' . sprintf(trans('labels.subscription_price'), round($subscription_rent_price)) . '</div></button>';
                    }

                    $html .= '<div class="col rentPDay subscription_options">'.$subscription_options.'</div>';
                } else {
                    $html .= ' <div class="col rentPDay">
                     <div class="rent-per-day-box">
                            <h4>' . $rent . '</h4>
                            <ul class="rent_prices_sec">
                                <li>
                                ' . $old_price . '
                                </li>
                                <li><p>' . number_format($new_rent_per_day, 2) . " " . $currency . '</p></li>
                            </ul>
                    </div>
                    
                </div>';
                }

                $html .= ' <div class="col totalRent">';
                if (isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1) {
                    $html .= '<h4>' . ($lang == 'eng' ? 'Total Rent' : 'إجمالي الإيجار') . '</h4>';
                } else {
                    $html .= '<h4>' . $rentSmall . " " . ($search_by['is_delivery_mode'] == 2 ? $sessionVals['hours_diff'] : ($search_by['is_delivery_mode'] == 4 ? 1 : $sessionVals['days'])) . " " . $days . '</h4>';
                }

                // started putting data to session to check
                $car_prices_data_to_check = [
                    'price' => round($new_rent_per_day, ($round_prices ? 2 : 1000)),
                    'old_price' => round($car->old_price, ($round_prices ? 2 : 1000)),
                    'car_rate_is_with_additional_utilization_rate' => $car_rate_is_with_additional_utilization_rate,
                    'extra_hours_rate_for_limousine' => $car->extra_hours_rate_for_limousine,
                ];
                Session::put('car_prices_data_to_check_' . $car->id, $car_prices_data_to_check);
                Session::save();
                // end putting data to session to check

                    $html .= '<p>' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</p>
                    ' . $vat_message . '
                </div>
                <div class="col bookBtn">
                    <form action="' . $lang_base_url . '/redirectToExtraServicesPage" method="post">
                        <input type="hidden" name="car_model_id" value="' . $car->id . '">
                        <input type="hidden" name="renting_type_id" value="' . $car->renting_type_id . '">
                        <input type="hidden" name="car_type_id" value="' . $car->car_type_id . '">
                        <input type="hidden" name="price" value="' . round($new_rent_per_day, ($round_prices ? 2 : 1000)) . '">
                        <input type="hidden" name="old_price" value="' . round($car->old_price, ($round_prices ? 2 : 1000)) . '">
                        <input type="hidden" name="car_rate_is_with_additional_utilization_rate" value="' . $car_rate_is_with_additional_utilization_rate . '">
                        <input type="hidden" name="extra_hours_rate_for_limousine" value="' . $car->extra_hours_rate_for_limousine . '">
                        <input type="hidden" name="cpid" value="' . $car->cpid . '">';

                $submitForm = "$(this).closest('form').submit();";

                $btn = \Lang::get('labels.book_now_btn');

                /*this is for booking_days_limit*/
                $days_limit = self::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                if ($availability) {
                    if ($days_limit) {
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="Edit"></a>';
                        } else {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="' . $btn . '"></a>';
                        }
                    } else {
                        if ($lang == 'eng') {
                            $newMsg = \Lang::get('labels.booking_day_limit_label');
                        } else {
                            $newMsg = str_replace("days", $car->booking_days_limit, \Lang::get('labels.booking_day_limit_label'));
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<div>' . $newMsg . '</div>';
                        } else {
                            $html .= '<div style="font-size: 10px;">' . $newMsg . '</div>';
                        }
                    }
                } else {
                    if (Session::get('edit_booking_id') != '') {
                        $html .= '<a ><input style="background-color: #6D6E71; color: #ffffff; cursor: default;" type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    } else {
                        $html .= '<a ><input style="background-color: #6D6E71; color: #ffffff; cursor: default;" type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    }
                }

                $html .= '</form>
                </div>
        
                <div class="clearfix"></div>
                
            </div>
        </div>
        
        <div class="clearfix"></div>';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $html .= ' <div class="tagSpecialCar" >
                            <img src="' . $base_url . '/public/frontend/images/' . $lang . '_specialFeature.png?v=?' . rand() . '"> 
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car->id . '" >
                            </a >
                        </div >';
                }

                $html .= '</div>';

            } else {
                //mobile_design

                $disablility_html = '';
                if ($car->is_for_disabled == 1) {
                    $disablility_html = '<div class="disability-div-mobile"><img src="'.$base_url.'/public/frontend/images/mobile-disability-'.$lang.'.png"></div>';
                }

                $html .= '<div class="singleRow mobileView" style="' . $singleRowPaddingCss . '">' . $redeemHtml . '';

                $html .= '<div class="car-col-left">';
                if ($car->image1 != '') {
                    $car_image_path = $base_url . '/public/uploads/' . $car->image1;
                } else {
                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                }
                $carName = ($lang == 'eng' ? $car->ct_eng_title : $car->ct_arb_title) . " " . ($lang == 'eng' ? $car->eng_title : $car->arb_title) . ' ' . $car->year;
                $lable = \Lang::get('labels.or_similar');

                $description = ($lang == 'eng' ? $car->eng_description : $car->arb_description);
                $specialCarDescription = '';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $specialCarDescription = ($lang == 'eng' ? $car->eng_special_car_desc : $car->arb_special_car_desc);
                }
                $title = ($lang == 'eng' ? $car->cc_eng_title : $car->cc_arb_title);
                $feature = \Lang::get('labels.features');
                $trans = ($car->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي'));
                $rent = ($search_by['is_delivery_mode'] == 2 ? \Lang::get('labels.total_rent') : \Lang::get('labels.rent_per_day'));

                if (isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1) {
                    $rent =  \Lang::get('labels.rent_per_trip');
                }

                $currency = \Lang::get('labels.currency');
                $rentSmall = \Lang::get('labels.total_rent_for_small');
                $days = ($search_by['is_delivery_mode'] == 2 ? \Lang::get('labels.hours') : \Lang::get('labels.days'));
                if ($site_settings->vat_mode == 'on') {
                    $vat_message = '<h4 style="color: #4B4B4B;">' . \Lang::get('labels.vat_not_included') . '</h4>';
                } else {
                    $vat_message = '';
                }
                $html .= '<div class="bookName mobile-car-parent">
                            <input type="hidden" name="oracle_reference_number" value="' . $car->oracle_reference_number . '">
                            <h2>' . $carName . '<span>' . $lable . '</span></h2>
                            '.$disablility_html.'
                            </div>
                        <h3>' . $title . '</h3>';


                $html .= '</div>';


                $html .= '<div class="car-col-right"><div class="mobile-car-listing-main-box">';
                $html .= '<div class="imgBox"><img src="' . $car_image_path . '" alt="' . ($lang == 'eng' ? $car->image1_eng_alt : $car->image1_arb_alt) . '" height="132" width="274"/></div>';
                $html .= '<div class="col rentPDay">';
                $html .= '<h4>' . ($search_by['is_delivery_mode'] == 4 ? trans('labels.rent_for_1_month') : $rent) . '</h4>
                                                                            ' . $old_price . '
                                                                            <p>' . number_format($search_by['is_delivery_mode'] == 4 ? $new_rent_per_day * 30 : $new_rent_per_day, 2) . " " . $currency . '</p>
                                                                        </div>
</div></div>';

                // started putting data to session to check
                $car_prices_data_to_check = [
                    'price' => round($new_rent_per_day, ($round_prices ? 2 : 1000)),
                    'old_price' => round($car->old_price, ($round_prices ? 2 : 1000)),
                    'car_rate_is_with_additional_utilization_rate' => $car_rate_is_with_additional_utilization_rate,
                    'extra_hours_rate_for_limousine' => $car->extra_hours_rate_for_limousine,
                ];
                Session::put('car_prices_data_to_check_' . $car->id, $car_prices_data_to_check);
                Session::save();
                // end putting data to session to check

                $html .= '<form action="' . $lang_base_url . '/redirectToExtraServicesPage" method="post">
                <input type="hidden" name="car_model_id" value="' . $car->id . '">
                <input type="hidden" name="renting_type_id" value="' . $car->renting_type_id . '">
                <input type="hidden" name="car_type_id" value="' . $car->car_type_id . '">
                <input type="hidden" name="price" value="' . round($new_rent_per_day, ($round_prices ? 2 : 1000)) . '">
                <input type="hidden" name="old_price" value="' . round($car->old_price, ($round_prices ? 2 : 1000)) . '">
                <input type="hidden" name="car_rate_is_with_additional_utilization_rate" value="' . $car_rate_is_with_additional_utilization_rate . '">
                <input type="hidden" name="extra_hours_rate_for_limousine" value="' . $car->extra_hours_rate_for_limousine . '">
                <input type="hidden" name="cpid" value="' . $car->cpid . '">';

                $submitForm = "$(this).closest('form').submit();";

                $btn = \Lang::get('labels.book_now_btn');

                $subscription_options_html = '';
                if ($search_by['is_delivery_mode'] == 4) {

                    $subscription_options = '';
                    $subscribe_for_months = [3, 6, 9, 12];

                    foreach ($subscribe_for_months as $for_month) {
                        if ($for_month == 3) {
                            $subscription_rent_price = $car->three_month_subscription_price;
                        }
                        if ($for_month == 6) {
                            $subscription_rent_price = $car->six_month_subscription_price;
                        }
                        if ($for_month == 9) {
                            $subscription_rent_price = $car->nine_month_subscription_price;
                        }
                        if ($for_month == 12) {
                            $subscription_rent_price = $car->twelve_month_subscription_price;
                        }
                        $subscription_options .= '<button class="grayishButton ' . ($search_by['subscribe_for_months'] == $for_month ? 'active' : '') . '" data-subscribe_for_months="' . $for_month . '" data-subscription_rent_price="' . $subscription_rent_price . '"><small>' . $for_month . ' ' . trans('labels.months') . '</small><div>' . sprintf(trans('labels.subscription_price'), round($subscription_rent_price)) . '</div></button>';
                    }

                    $subscription_options_html .= '<div class="col rentPDay subscription_options">'.$subscription_options.'</div>';
                }

                /*this is for booking_days_limit*/
                $days_limit = self::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                if ($availability) {
                    if ($days_limit) {

                        if ($search_by['is_delivery_mode'] == 4) {
                            $html .= '<div class="btn-holder">' . $subscription_options_html . '<a href="javascript:void(0);" class="pick-up 1" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong> 1 '.($lang == 'eng' ? 'Month' : 'شهر').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                        } else {
                            $html .= '<div class="btn-holder">' . $subscription_options_html . '<a href="javascript:void(0);" class="pick-up 1" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong>' . $sessionVals['days'] . ' '.($lang == 'eng' ? 'Days' : 'ايام').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="Edit"></a>';
                        } else {
                            $html .= '<div class="edBtn" onclick="' . $submitForm . '" >';

                            if ($search_by['is_delivery_mode'] != 4) {

                                /*$html .= ' <div class="col rentPDay">
                            <h4>' . $rent . '</h4>
                            ' . $old_price . '
                            </div>';*/
                            }

                            $html .= '<div class="col totalRent">
                            <h4>'.($lang == 'eng' ? 'BOOK' : 'احجز').'</h4>
                            </div></div>';

                        }
                    } else {
                        if ($lang == 'eng') {
                            $newMsg = \Lang::get('labels.booking_day_limit_label');
                        } else {
                            $newMsg = str_replace("days", $car->booking_days_limit, \Lang::get('labels.booking_day_limit_label'));
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        } else {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        }
                        if ($search_by['is_delivery_mode'] == 4) {
                            $html .= '<div class="btn-holder">'.$subscription_options_html.'<a href="javascript:void(0);" class="pick-up 2" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong> 1 '.($lang == 'eng' ? 'Month' : 'شهر').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                        } else {
                            $html .= '<div class="btn-holder">'.$subscription_options_html.'<a href="javascript:void(0);" class="pick-up 2" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong>'.$sessionVals['days'].' '.($lang == 'eng' ? 'Days' : 'ايام').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                        }

                    }
                } else {
                    if ($search_by['is_delivery_mode'] == 4) {
                        $html .= '<div class="btn-holder">' . $subscription_options_html . '<a href="javascript:void(0);" class="pick-up 3" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong> 1 '.($lang == 'eng' ? 'Month' : 'شهر').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                    } else {
                        $html .= '<div class="btn-holder">' . $subscription_options_html . '<a href="javascript:void(0);" class="pick-up 3" id="' . $car->id . '">'.($lang == 'eng' ? 'Total rent for' : 'مجموع الايجار').' <strong>' . $sessionVals['days'] . ' '.($lang == 'eng' ? 'Days' : 'ايام').' / ' . number_format($new_rent_per_day * ($search_by['is_delivery_mode'] == 2 ? 1 : $sessionVals['days']), 2) . " " . \Lang::get("labels.currency") . '</strong><br>'.($lang == 'eng' ? '(VAT excluded)' : '(لايشمل الضريبة)').'</a>';
                    }
                    if (Session::get('edit_booking_id') != '') {
                        $html .= '<div class="edBtn" >';
                        $html .= '<div class="col totalRent sold">
                        <h4>' . \Lang::get('labels.sold_out') . '</h4>
                        </div></div>';
                    } else {
                        $html .= '<div class="edBtn" >';
                        $html .= '<div class="col totalRent sold">
                        <h4>' . \Lang::get('labels.sold_out') . '</h4>
                        </div></div>';
                    }
                }

                $html .= '</form></div>';


                /*$html .= '<div class="col rentPDay">';
                $html .= '<h4>' . $rent . '</h4>
                            ' . $old_price . '
                          </div>';*/

                //end sold out text new logic for yellow box

                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $html .= ' <div class="tagSpecialCar" >
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car->id . '" >
                                <img src="' . $base_url . '/public/frontend/images/specialFeature_mobile.png?v=?' . rand() . '"> 
                            </a >
                        </div >';
                }
                $html .= '</div>';

            }

            $k++;

            $item_data = [
                'item_id' => $car->ct_eng_title . " " . $car->eng_title . ' ' . $car->year,
                'item_name' => $car->ct_eng_title . " " . $car->eng_title . ' ' . $car->year
            ];
            $items[] = $item_data;
        }

        $event = 'view_item_list';
        $event_data = [
            'items' => $items
        ];
        custom::sendEventToGA4($event, $event_data);

        return $html;


    }


    public static function humanLessUpgradeCarsHtml($cars, $base_url, $lang_base_url, $lang, $booking_details)
    {
        $site_settings = custom::site_settings();
        $page = new \App\Models\Front\Page();
        $html = "";

        if (empty($cars)) {
            return $html;
        }

        $renting_type_details = $page->getSingle('setting_renting_type', ['id' => $cars[0]->renting_type_id]);

        $promo_discount_percent = 0;

        /*this functionality to get pickup and current date difference to check booking days limit */
        $start = date('Y-m-d');
        $pickup_date = date('Y-m-d', strtotime($booking_details->from_date));
        $dateDiff = self::getDifferenceInDates($start, $pickup_date, 'days');
        $k = 0;

        $arrData['region_id'] = $booking_details->from_region_id;
        $arrData['city_id'] = $booking_details->from_city_id;
        $arrData['pickup_date'] = $booking_details->from_date;
        $arrData['dropoff_date'] = $booking_details->to_date;

        $avail_array_return = self::checkIfModelExistGetInArray($arrData);

        $avail_array_car_ids = $avail_array_return[0];
        $avail_array_per_day = $avail_array_return[1];

        $avail_array_utilization_percentage_1 = $avail_array_return[2];
        $avail_array_increase_price_percentage_1 = $avail_array_return[3];

        $avail_array_utilization_percentage_2 = $avail_array_return[4];
        $avail_array_increase_price_percentage_2 = $avail_array_return[5];

        $avail_array_utilization_percentage_3 = $avail_array_return[6];
        $avail_array_increase_price_percentage_3 = $avail_array_return[7];

        $car_ids_for_availability = array();
        foreach ($cars as $car) {
            $car_ids_for_availability[] = $car->id;
        }

        $arrDataBookings['city_id'] = $booking_details->from_city_id;
        $arrDataBookings['pickup_date'] = $booking_details->from_date;
        $arrDataBookings['car_ids_for_availability'] = implode(',', $car_ids_for_availability);

        $availabilityBookings = self::checkCarAvailabilityBookings($arrDataBookings);

        $booking_car_models = array();
        $booking_car_models_count = array();
        foreach ($availabilityBookings as $availabilityBooking) {
            $booking_car_models[] = $availabilityBooking->car_model_id;
            $booking_car_models_count[] = $availabilityBooking->count;
        }

        foreach ($cars as $car) {

            $car_rate_is_with_additional_utilization_rate = 0;

            /*checking if car is available for booking, this will return true or false*/
            $booking_per_day_found_bookings = 0;
            $booking_car_id_key = array_search($car->id, $avail_array_car_ids);
            $booking_per_day_found = $avail_array_per_day[$booking_car_id_key];
            if ($booking_per_day_found !== false) {
                if ($booking_per_day_found) {
                    $booking_car_id_key_booking = array_search($car->id, $booking_car_models);
                    if ($booking_car_id_key_booking !== false) {
                        $booking_per_day_found_bookings = $booking_car_models_count[$booking_car_id_key_booking];
                    }

                    if ($booking_per_day_found_bookings < $booking_per_day_found) {

                        $availability = true;

                        /*We will overwrite price and old price here depending upon car utilization*/

                        if ($site_settings->car_utilization_mode == 'on' && false) {

                            $price = $car->price;
                            $old_price = $car->old_price;

                            $no_of_bookings_allowed_per_day = $booking_per_day_found;
                            $no_of_currently_active_bookings = $booking_per_day_found_bookings;

                            $utilization_percentage_1 = $avail_array_utilization_percentage_1[$booking_car_id_key];
                            $increase_price_percentage_1 = $avail_array_increase_price_percentage_1[$booking_car_id_key];

                            $utilization_percentage_2 = $avail_array_utilization_percentage_2[$booking_car_id_key];
                            $increase_price_percentage_2 = $avail_array_increase_price_percentage_2[$booking_car_id_key];

                            $utilization_percentage_3 = $avail_array_utilization_percentage_3[$booking_car_id_key];
                            $increase_price_percentage_3 = $avail_array_increase_price_percentage_3[$booking_car_id_key];

                            if ($utilization_percentage_1 > 0 && $increase_price_percentage_1 > 0) {
                                $bookings_allowed_as_per_factor_1 = ($utilization_percentage_1 / 100) * $no_of_bookings_allowed_per_day;
                                if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_1) {
                                    $car->price = $price + (($increase_price_percentage_1 / 100) * $price);
                                    $car->old_price = $old_price + (($increase_price_percentage_1 / 100) * $old_price);
                                    $car_rate_is_with_additional_utilization_rate = 1;
                                }
                            }

                            if ($utilization_percentage_2 > 0 && $increase_price_percentage_2 > 0) {
                                $bookings_allowed_as_per_factor_2 = ($utilization_percentage_2 / 100) * $no_of_bookings_allowed_per_day;
                                if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_2) {
                                    $car->price = $price + (($increase_price_percentage_2 / 100) * $price);
                                    $car->old_price = $old_price + (($increase_price_percentage_2 / 100) * $old_price);
                                    $car_rate_is_with_additional_utilization_rate = 1;
                                }
                            }

                            if ($utilization_percentage_3 > 0 && $increase_price_percentage_3 > 0) {
                                $bookings_allowed_as_per_factor_3 = ($utilization_percentage_3 / 100) * $no_of_bookings_allowed_per_day;
                                if ($no_of_currently_active_bookings >= $bookings_allowed_as_per_factor_3) {
                                    $car->price = $price + (($increase_price_percentage_3 / 100) * $price);
                                    $car->old_price = $old_price + (($increase_price_percentage_3 / 100) * $old_price);
                                    $car_rate_is_with_additional_utilization_rate = 1;
                                }
                            }

                        }

                        if (!Session::has('corporate_customer_id') && $renting_type_details->oracle_reference_number == '26757' && $site_settings->car_utilization_addition_subtraction_mode == 'on') {
                            $days_to_check_for_car_utilization_addition_subtraction_mode = $site_settings->days_to_check_for_car_utilization_addition_subtraction_mode;
                            if ($days_to_check_for_car_utilization_addition_subtraction_mode > 0) {

                                $from_date_arr = explode(' ', $booking_details->from_date);

                                $date1 = Carbon::parse(date('Y-m-d'));
                                $date2 = Carbon::parse($from_date_arr[0]);

                                if ($date1->diffInDays($date2) < $days_to_check_for_car_utilization_addition_subtraction_mode) {

                                    $from_branch_details = $page->getSingle('branch', ['id' => $booking_details->from_location]);
                                    $car_model_details = $page->getSingle('car_model', ['id' => $booking_details->car_model_id]);

                                    $get_by = [
                                        'branch' => $from_branch_details->oracle_reference_number,
                                        'car_type' => $car_model_details->oracle_reference_number,
                                        'car_model' => $car_model_details->year,
                                    ];
                                    $car_utilization_setup = $page->getSingle('setting_car_utilization_setup', $get_by);
                                    if ($car_utilization_setup && $car_utilization_setup->addition_or_subtraction_percentage != 0) {
                                        $car->price = $car->price + (($car_utilization_setup->addition_or_subtraction_percentage / 100) * $car->price);
                                        $car->old_price = $car->old_price + (($car_utilization_setup->addition_or_subtraction_percentage / 100) * $car->old_price);
                                        $car_rate_is_with_additional_utilization_rate = $car_utilization_setup->id;
                                    }
                                }

                            }
                        }

                    } else {
                        $availability = false;
                    }


                } else {
                    $availability = false;
                }
            } else {
                $availability = true;
            }


            $singleRowPaddingCss = '';
            $hasRedeemOffer = false;
            $redeemHtml = '';
            $type_of_discount = '% ';
            $new_rent_per_day = round($car->price, 2);
            $old_rent_per_day = round($car->old_price, 2);
            $loyalty_discount_percentage = ((float)Session::get('loyalty_discount_percent') != '' ? (float)Session::get('loyalty_discount_percent') : 0);
            $discount_percentage = $loyalty_discount_percentage;
            if (Session::get('logged_in_from_frontend') == true && Session::get('user_type') == "corporate_customer") {
                $customer_type_for_auto_promo = "Corporate";
            } else {
                $customer_type_for_auto_promo = "Individual";
            }
            $promo_discount = $page->checkAutoPromoDiscount($car->id, date('Y-m-d H:i:s', strtotime($booking_details->from_date)), $booking_details->from_region_id, $booking_details->from_city_id, $booking_details->from_location, $booking_details->no_of_days, $customer_type_for_auto_promo);

            $coupon_is_valid_for_pickup_day = self::is_promotion_valid_for_pickup_day($promo_discount, $booking_details->from_date);

            $is_promotion_auto_apply = false;

            if ($promo_discount && $promo_discount->discount > 0 && $coupon_is_valid_for_pickup_day) {
                if ($promo_discount->type == 'Fixed Price Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $after_fixed_price_discount = round($old_rent_per_day - $promo_discount->discount, 2);
                    if ($after_fixed_price_discount > $new_rent_per_day) {
                        $discount_amount = $promo_discount->discount;
                        $new_rent_per_day = $old_rent_per_day - $discount_amount;
                        $new_rent_per_day = round($new_rent_per_day, 2);
                        $discount_percentage = (float)$discount_amount;
                        $type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $promo_discount_percent = $promo_discount->discount;

                    if ($promo_discount_percent > 0 && $promo_discount_percent > $loyalty_discount_percentage) {
                        $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                        $new_rent_per_day = $old_rent_per_day - $discount_amount;
                        $new_rent_per_day = round($new_rent_per_day, 2);
                        $discount_percentage = (float)$promo_discount_percent;
                    }
                } elseif ($promo_discount->type == 'Percentage Auto Apply on Loyalty') {
                    $is_promotion_auto_apply = true;
                    $promo_discount_percent = $promo_discount->discount + $loyalty_discount_percentage;
                    $discount_amount = $promo_discount_percent * $old_rent_per_day / 100;
                    $new_rent_per_day = $old_rent_per_day - $discount_amount;
                    $new_rent_per_day = round($new_rent_per_day, 2);
                    $discount_percentage = (float)$promo_discount_percent;
                } elseif ($promo_discount->type == 'Fixed Daily Rate Auto Apply') {
                    $is_promotion_auto_apply = true;
                    $after_fixed_price_discount = $promo_discount->discount;
                    if ($after_fixed_price_discount < $new_rent_per_day) {
                        $discount_amount = $promo_discount->discount;
                        $new_rent_per_day = round($promo_discount->discount, 2);
                        $discount_percentage = $old_rent_per_day - $discount_amount;
                        $type_of_discount = ' ' . \Lang::get('labels.currency') . ' ';
                    }
                }
            }

            if (Session::get('loyalty_discount_percent') != '') {
                $old_price = '<span class="less-price"><span class="del-price">' . number_format(round($car->old_price, 2), 2) . " " . \Lang::get('labels.currency') . '</span><br> ' . $discount_percentage . $type_of_discount . \Lang::get('labels.off') . '</span>';
            } else {
                $old_price = '';
            }

            if (!custom::is_mobile()) {
                $html .= '<div class="singleRow" style="' . $singleRowPaddingCss . '">
' . $redeemHtml . '
                    <div class="imgBox">';

                if ($car->image1 != '') {
                    $car_image_path = $base_url . '/public/uploads/' . $car->image1;
                } else {
                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                }
                $carName = ($lang == 'eng' ? $car->ct_eng_title : $car->ct_arb_title) . " " . ($lang == 'eng' ? $car->eng_title : $car->arb_title) . ' ' . $car->year;
                $lable = \Lang::get('labels.or_similar');

                $description = ($lang == 'eng' ? $car->eng_description : $car->arb_description);
                $specialCarDescription = '';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $specialCarDescription = ($lang == 'eng' ? $car->eng_special_car_desc : $car->arb_special_car_desc);
                }
                $title = ($lang == 'eng' ? $car->cc_eng_title : $car->cc_arb_title);
                $feature = \Lang::get('labels.features');
                $trans = ($car->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي'));
                $rent = \Lang::get('labels.rent_per_day');
                $currency = \Lang::get('labels.currency');
                $rentSmall = \Lang::get('labels.total_rent_for_small');
                $days = \Lang::get('labels.days');
                if ($site_settings->vat_mode == 'on') {
                    $vat_message = '<h4 style="color: #4B4B4B;">' . \Lang::get('labels.vat_not_included') . '</h4>';
                } else {
                    $vat_message = '';
                }
                $html .= '<img src="' . $car_image_path . '" alt="' . ($lang == 'eng' ? $car->image1_eng_alt : $car->image1_arb_alt) . '" height="132"
             width="274"/>
        </div>
        <div class="bookDtlSec">
            <div class="bookName">
                <h2>' . $carName . '
                    <span>' . $lable . '</span></h2>
                <div class="helpBox">
                    <a class="click" href="javascript:void(0);">?</a>
                    <p class="popTextP">' . $description . '</p>
                </div>
                </div>
            <h3>' . $title . '</h3>
            <div class="bookPSec">
                <div class="bookFeature ' . ($car->min_age > 0 ? 'contains-min-age' : '') . '">
                    <h4>' . $feature . '</h4>
                    <ul>
                        <li>
                            <div class="spIconF person"></div>
                            <p>' . $car->no_of_passengers . '</p></li>
                        <li>
                            <div class="spIconF transmition"></div>
                            <p>' . $trans . '</p>
                        </li>
                        <li>
                            <div class="spIconF door"></div>
                            <p>' . $car->no_of_doors . '</p></li>
                        <li>
                            <div class="spIconF bag"></div>
                            <p>' . $car->no_of_bags . '</p></li>';
                if ($car->min_age > 0) {
                    $html .= '<li>
                            <div class="spIconF minAge"></div>
                            <p>' . $car->min_age . '</p></li>';
                }
                $html .= ' </ul>
                </div>
                <div class="col rentPDay">
                    <h4>' . $rent . '</h4>
					' . $old_price . '
                    <p>' . number_format($new_rent_per_day, 2) . " " . $currency . '</p>
                </div>
                <div class="col totalRent">
                    <h4>' . $rentSmall . " " . $sessionVals['days'] . " " . $days . '</h4>
                    <p>' . number_format($new_rent_per_day * $sessionVals['days'], 2) . " " . \Lang::get("labels.currency") . '</p>
                    ' . $vat_message . '
                </div>
                <div class="col bookBtn">
                    <form action="' . $lang_base_url . '/redirectToExtraServicesPage" method="post">
                        <input type="hidden" name="car_model_id" value="' . $car->id . '">
                        <input type="hidden" name="renting_type_id" value="' . $car->renting_type_id . '">
                        <input type="hidden" name="price" value="' . round($new_rent_per_day, 2) . '">
                        <input type="hidden" name="old_price" value="' . round($car->old_price, 2) . '">
                        <input type="hidden" name="car_rate_is_with_additional_utilization_rate" value="' . $car_rate_is_with_additional_utilization_rate . '">
                        <input type="hidden" name="extra_hours_rate_for_limousine" value="' . $car->extra_hours_rate_for_limousine . '">
                        <input type="hidden" name="cpid" value="' . $car->cpid . '">';

                $submitForm = "$(this).closest('form').submit();";
                $btn = \Lang::get('labels.book_now_btn');

                /*this is for booking_days_limit*/
                $days_limit = self::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                if ($availability) {
                    if ($days_limit) {
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="Edit"></a>';
                        } else {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="' . $btn . '"></a>';
                        }
                    } else {
                        if ($lang == 'eng') {
                            $newMsg = \Lang::get('labels.booking_day_limit_label');
                        } else {
                            $newMsg = str_replace("days", $car->booking_days_limit, \Lang::get('labels.booking_day_limit_label'));
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<div>' . $newMsg . '</div>';
                        } else {
                            $html .= '<div style="font-size: 10px;">' . $newMsg . '</div>';
                        }
                    }
                } else {
                    if (Session::get('edit_booking_id') != '') {
                        $html .= '<a ><input style="background-color: #6D6E71; color: #ffffff; cursor: default;" type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    } else {
                        $html .= '<a ><input style="background-color: #6D6E71; color: #ffffff; cursor: default;" type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    }
                }

                $html .= '</form>
                </div>
        
                <div class="clearfix"></div>
                
            </div>
        </div>
        
        <div class="clearfix"></div>';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $html .= ' <div class="tagSpecialCar" >
                            <img src="' . $base_url . '/public/frontend/images/' . $lang . '_specialFeature.png?v=?' . rand() . '"> 
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car->id . '" >
                            </a >
                        </div >';
                }

                $html .= '</div>';

            } else {
                //mobile_design

                $html .= '<div class="singleRow mobileView" style="">';

                if ($car->image1 != '') {
                    $car_image_path = $base_url . '/public/uploads/' . $car->image1;
                } else {
                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                }
                $carName = ($lang == 'eng' ? $car->ct_eng_title : $car->ct_arb_title) . " " . ($lang == 'eng' ? $car->eng_title : $car->arb_title) . ' ' . $car->year;
                $lable = \Lang::get('labels.or_similar');

                $description = ($lang == 'eng' ? $car->eng_description : $car->arb_description);
                $specialCarDescription = '';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $specialCarDescription = ($lang == 'eng' ? $car->eng_special_car_desc : $car->arb_special_car_desc);
                }
                $title = ($lang == 'eng' ? $car->cc_eng_title : $car->cc_arb_title);
                $feature = \Lang::get('labels.features');
                $trans = ($car->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي'));
                $rent = \Lang::get('labels.rent_per_day');
                $currency = \Lang::get('labels.currency');
                $rentSmall = \Lang::get('labels.total_rent_for_small');
                $days = \Lang::get('labels.days');
                if ($site_settings->vat_mode == 'on') {
                    $vat_message = '<h4 style="color: #4B4B4B;">' . \Lang::get('labels.vat_not_included') . '</h4>';
                } else {
                    $vat_message = '';
                }
                $html .= '<div class="p-holder">
                            <div class="imgBox">
                                <img src="' . $car_image_path . '" alt="' . ($lang == 'eng' ? $car->image1_eng_alt : $car->image1_arb_alt) . '" height="132" width="274"/>
                            </div>';
                $html .= '<div class="bookName">
                                <h2>(' . $car->oracle_reference_number . ') ' . $carName . '<span>' . $lable . '</span></h2>
                                <h3>' . $title . '</h3>
                            </div>';

                $html .= '</div>
                
                <form action="' . $lang_base_url . '/redirectToExtraServicesPage" method="post">
                <input type="hidden" name="car_model_id" value="' . $car->id . '">
                <input type="hidden" name="renting_type_id" value="' . $car->renting_type_id . '">
                <input type="hidden" name="price" value="' . round($new_rent_per_day, 2) . '">
                <input type="hidden" name="old_price" value="' . round($car->old_price, 2) . '">
                <input type="hidden" name="car_rate_is_with_additional_utilization_rate" value="' . $car_rate_is_with_additional_utilization_rate . '">
                <input type="hidden" name="extra_hours_rate_for_limousine" value="' . $car->extra_hours_rate_for_limousine . '">
                <input type="hidden" name="cpid" value="' . $car->cpid . '">';

                $submitForm = "$(this).closest('form').submit();";

                //to get cdw price if car is changed
                $new_cdw_price = 0;
                $is_cdw = self::checkIfCDWSelected($booking_details->id);
                if ($is_cdw) {
                    $new_cdw_price = self::getCDWPrice($customer_type_for_auto_promo, $car->id, $car->renting_type_id);
                }

                $upgradeCarFunction = "'" . $car->oracle_reference_number . "','" . $car->year . "','" . $carName . "','" . $car_image_path . "','" . $new_rent_per_day . "','" . $new_cdw_price . "'";

                $days_limit = self::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                if ($availability) {
                    if ($days_limit) {
                        $html .= '<div class="btn-holder"><a href="javascript:void(0);" class="pick-up" id="' . $car->id . '" data-toggle="modal" data-target="#carDescPopup' . $car->id . '" >' . ($lang == 'eng' ? 'View details' : 'عرض التفاصيل') . '</a>';
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="Edit"></a>';
                        } else {
                            $html .= '<div class="edBtn" onclick="upgradeCar(' . $upgradeCarFunction . ')" >
                            <div class="col rentPDay" data-rentType="' . $car->renting_type_id . '" data-carModel="' . $car->id . '">
                            <h4>' . $rent . '</h4>
                            ' . $old_price . '
                             <p>' . number_format($new_rent_per_day, 2) . " " . $currency . '</p>
                            </div>';
                            $html .= '<div class="col totalRent">
                            <h4>' . ($lang == 'eng' ? 'Starting from' : 'البدء من') . '</h4>
                            <p>' . number_format($new_rent_per_day * $booking_details->no_of_days, 2) . " " . \Lang::get("labels.currency") . '</p>
                            </div></div>';

                        }
                    } else {
                        if ($lang == 'eng') {
                            $newMsg = \Lang::get('labels.booking_day_limit_label');
                        } else {
                            $newMsg = str_replace("days", $car->booking_days_limit, \Lang::get('labels.booking_day_limit_label'));
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        } else {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        }
                        $html .= '<div class="btn-holder"><a href="javascript:void(0);" class="pick-up" data-toggle="modal" data-target="#carDescPopup' . $car->id . '" >' . ($lang == 'eng' ? 'View details' : 'عرض التفاصيل') . '</a>';
                    }
                } else {
                    $html .= '<div class="btn-holder"><a href="javascript:void(0);" class="pick-up" data-toggle="modal" data-target="#carDescPopup' . $car->id . '" >' . ($lang == 'eng' ? 'View details' : 'عرض التفاصيل') . '</a>';
                    if (Session::get('edit_booking_id') != '') {
                        $html .= '<div class="edBtn" >
                        <div class="col rentPDay sold" style="margin-top:12px !important;">
                            ' . \Lang::get('labels.sold_out') . '
                        </div>';
                        $html .= '<div class="col totalRent">
                        <h4>' . ($lang == 'eng' ? 'Starting from' : 'البدء من') . '</h4>
                        <p>' . number_format($new_rent_per_day * $booking_details->no_of_days, 2) . " " . \Lang::get("labels.currency") . '</p>
                        </div></div>';
                    } else {
                        $html .= '<div class="edBtn" >
                        <div class="col rentPDay sold" style="margin-top:12px !important;">
                            ' . \Lang::get('labels.sold_out') . '
                        </div>';
                        $html .= '<div class="col totalRent">
                        <h4>' . ($lang == 'eng' ? 'Starting from' : 'البدء من') . '</h4>
                        <p>' . number_format($new_rent_per_day * $booking_details->no_of_days, 2) . " " . \Lang::get("labels.currency") . '</p>
                        </div></div>';
                    }
                }

                $html .= '</form></div>';

                //start popup html
                $html .= '<div class="modal-mobile modal fade booking-modal" id="carDescPopup' . $car->id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">';
                $html .= '<div class="bookPSec">
                                                <a href="javascript:void(0)" class="btn-close" data-dismiss="modal" aria-label="Close" ></a>
                                                <h2>' . $carName . '<span>' . $lable . '</span></h2>
                                                        <h3>' . $title . '</h3>
                                                        </div>
                                                        <div class="bookFeature ' . ($car->min_age > 0 ? 'contains-min-age' : '') . '">
                                                            <h4>' . $feature . '</h4>
                                                            <ul>
                                                                <li>
                                                                    <div class="spIconF person"></div>
                                                                    <p>' . $car->no_of_passengers . '</p>
                                                                </li>
                                                                <li>
                                                                    <div class="spIconF transmition"></div>
                                                                    <p>' . $trans . '</p>
                                                                </li>
                                                                <li>
                                                                    <div class="spIconF door"></div>
                                                                    <p>' . $car->no_of_doors . '</p>
                                                                </li>
                                                                <li>
                                                                    <div class="spIconF bag"></div>
                                                                    <p>' . $car->no_of_bags . '</p>
                                                                </li>';
                if ($car->min_age > 0) {
                    $html .= '<li>
                                                                    <div class="spIconF minAge"></div>
                                                                    <p>' . $car->min_age . '</p>
                                                                    </li>';
                }
                $html .= ' </ul>
                                                        </div>';
                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $html .= ' <div class="keyPremier" >
                                                                <img src="' . $base_url . '/public/frontend/images/specialFeature_mobile.png?v=?' . rand() . '"> 
                                                                <div class="premierDesc">
                                                                    <p>' . ($lang == 'eng' ? $car->eng_special_car_desc : $car->arb_special_car_desc) . '</p>
                                                                </div>
                                                            </div >';
                }
                $html .= '<div class="col rentPDay">';
                $html .= '<h4>' . $rent . '</h4>
                                                            ' . $old_price . '
                                                            <p>' . number_format($new_rent_per_day, 2) . " " . $currency . '</p>
                                                        </div>
                                                        <div class="col totalRent">
                                                            <h4>' . $rentSmall . " " . $booking_details->no_of_days . " " . $days . '</h4>
                                                            <p>' . number_format($new_rent_per_day * $booking_details->no_of_days, 2) . " " . \Lang::get("labels.currency") . '</p>
                                                            ' . $vat_message . '
                                                        </div></div>';
                $html .= '<div class="bookDtlSec">
                                                    <div class="col bookBtn">';

                $submitForm = "$(this).closest('form').submit();";
                $btn = \Lang::get('labels.select_car');

                /*this is for booking_days_limit*/
                $days_limit = self::checkBookingDaysLimit($car->booking_days_limit, $dateDiff);
                if ($availability) {
                    if ($days_limit) {
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<a href="javascript:void(0);" onclick="' . $submitForm . '"><input type="button" class="edBtn" value="Edit"></a>';
                        } else {
                            $html .= '<a href="javascript:void(0);" onclick="upgradeCar(' . $car->oracle_reference_number . ',' . $car->year . ')"><input type="button" class="edBtn" value="' . $btn . '"></a>';
                        }
                    } else {
                        if ($lang == 'eng') {
                            $newMsg = \Lang::get('labels.booking_day_limit_label');
                        } else {
                            $newMsg = str_replace("days", $car->booking_days_limit, \Lang::get('labels.booking_day_limit_label'));
                        }
                        if (Session::get('edit_booking_id') != '') {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        } else {
                            $html .= '<div class="detail-text">' . $newMsg . '</div>';
                        }
                    }
                } else {
                    if (Session::get('edit_booking_id') != '') {
                        $html .= '<a ><input type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    } else {
                        $html .= '<a ><input type="button" class="edBtn" value="' . \Lang::get('labels.sold_out') . '"></a>';
                    }
                }

                $html .= '</div></div>
                                            
                                            <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>';
                //end popup html

                if ($car->is_special_car == 'yes' && $is_promotion_auto_apply) {
                    $html .= ' <div class="tagSpecialCar" >
                            <a class="click btnSpecialCar" href = "javascript:void(0);" data-id="' . $car->id . '" >
                                <img src="' . $base_url . '/public/frontend/images/specialFeature_mobile.png?v=?' . rand() . '"> 
                            </a >
                        </div >';
                }
                $html .= '</div>';

            }

            $k++;
        }

        return $html;


    }

    public static function branch_mob_divs($is_wiz, $lang, $from_br = "", $to_br = "", $from_cit = "", $to_cit = "", $pick_date = "", $drop_date = "", $pickup_time = "", $dropoff_time = "", $lang_base_url = "", $edit = "", $is_delivery_mode = "")
    {
        $page = new \App\Models\Front\Page();

        $sessionVals = Session::get('search_data');

        $check_wiz = 0;
        $dis_stl = $is_wiz ? 'display: none' : '';
        $branch_html = '';

        $br_same_stl = '';
        if (($is_wiz == false && $from_br == $to_br) || ($is_wiz == true && $from_br == $to_br && $edit == 1))
            $br_same_stl = 'display: none';

        if ($is_wiz == false || $edit == 1) {
            $from_city_info = $page->getSingle('city', array('id' => $from_cit));
            $to_city_info = $page->getSingle('city', array('id' => $to_cit));
            if (isset($from_city_info) && !empty($from_city_info)) {
                $from_cit = $lang == 'eng' ? $from_city_info->eng_title : $from_city_info->arb_title;
            }
            if (isset($to_city_info) && !empty($to_city_info)) {
                $to_cit = $lang == 'eng' ? $to_city_info->eng_title : $to_city_info->arb_title;
            }

            $from_br_info = $page->getSingle('branch', array('id' => $from_br));
            $to_br_info = $page->getSingle('branch', array('id' => $to_br));

            if (isset($from_br_info) && !empty($from_br_info)) {
                $from_br = $lang == 'eng' ? $from_br_info->eng_title : $from_br_info->arb_title;
            }
            if (isset($to_br_info) && !empty($to_br_info)) {
                $to_br = $lang == 'eng' ? $to_br_info->eng_title : $to_br_info->arb_title;
            }
            $check_wiz = 1;
        }

        if ($is_delivery_mode != "") {
            if ($is_delivery_mode == 1) {
                //when delivery mode will be selected
                $search_mode = 'delivery';
            } elseif ($is_delivery_mode == 2) {
                //when hourly mode will be selected
                $search_mode = 'hourly';
            } elseif ($is_delivery_mode == 0) {
                //when hourly mode will be selected
                $search_mode = 'pickup';
            } elseif ($is_delivery_mode == 3) {
                //when monthly mode will be selected
                $search_mode = 'monthly';
            } elseif ($is_delivery_mode == 5) {
                //when weekly mode will be selected
                $search_mode = 'weekly';
            } elseif ($is_delivery_mode == 4) {
                //when subscription mode will be selected
                $search_mode = 'subscription';
            }
        } else {
            if (isset($_REQUEST['delivery']) && $_REQUEST['delivery'] == 1) {
                //when delivery mode will be selected
                $search_mode = 'delivery';
            } elseif (isset($_REQUEST['hourly']) && $_REQUEST['hourly'] == 1) {
                //when hourly mode will be selected
                $search_mode = 'hourly';
            } elseif (isset($_REQUEST['monthly']) && $_REQUEST['monthly'] == 1) {
                //when monthly mode will be selected
                $search_mode = 'monthly';
            } elseif (isset($_REQUEST['weekly']) && $_REQUEST['weekly'] == 1) {
                //when weekly mode will be selected
                $search_mode = 'weekly';
            } elseif (isset($_REQUEST['subscription']) && $_REQUEST['subscription'] == 1) {
                //when hourly mode will be selected
                $search_mode = 'subscription';
            } else {
                $search_mode = 'pickup';
            }
        }

        $branch_html .= '<div class="branch_tabs" id="' . $check_wiz . '">
            <ul class="date-box add city_branch_tabs" style="' . $dis_stl . '">
                <li>
                    <div class="pick-up" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1\'">
                        <strong class="title" id="from_branch_ylw_bx">' . $from_br . '</strong>
                       <!-- <time id="from_city_ylw_bx">' . $from_cit . '</time> -->
                    </div>
                </li>';
        if (isset($_REQUEST['pickup']) || $from_br != $to_br) {
            $branch_html .= '<li class="if_branch_same" style="' . $br_same_stl . '">';
            if ($is_wiz == false) {
                $branch_html .= '<div class="drop-off" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=2\'">';
            } else {
                $branch_html .= '<div class="drop-off" onclick="getDropOffWiz()">';
            }
            $branch_html .= '<strong class="title" id="to_branch_ylw_bx">' . $to_br . '</strong>
                        <time id="to_city_ylw_bx">' . $to_cit . '</time>
                    </div>
                </li>';
        }
        $branch_html .= ' </ul>
            <ul class="date-box time_tabs" style="' . $dis_stl . '">
                <li>';
        if ($is_wiz == false) {
            $branch_html .= '<div class="pick-up" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=3\'">';
        } else {
            $branch_html .= '<div class="pick-up" onclick="getDatePickerWiz()">';
        }

        $branch_html .= ' <strong class="title">' . ($lang == 'eng' ? 'Pick Up Date' : 'تاريخ الإستلام') . '</strong>
                        <time id="pickup_date_gry_bx" datetime="26-10-2018">' . $pick_date . '</time>
                    </div>
                </li>';


        $branch_html .= '<li style="display: '.(($search_mode == 'hourly' || $search_mode == 'subscription') ? 'none' : 'inline-block').';">';
        if ($is_wiz == false) {
            $branch_html .= '<div class="drop-off" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=3\'">';
        } else {
            $branch_html .= '<div class="drop-off" onclick="getDatePickerWiz()">';
        }
        $branch_html .= '  <strong class="title">' . ($lang == 'eng' ? 'Drop Off Date' : 'تاريخ التسليم') . '</strong>
                        <time id="dropoff_date_gry_bx" datetime="26-10-2018">' . $drop_date . '</time>
                    </div>
                </li>';




        $branch_html .= '</ul>
            
            <ul class="date-box time_tabs2" style="' . $dis_stl . '">
                <li>';
        if ($is_wiz == false) {
            $branch_html .= ' <div class="pick-up" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=4\'">';
        } else {
            $branch_html .= ' <div class="pick-up">';
        }

        $branch_html .= '<strong class="title">' . ($lang == 'eng' ? 'Pick Up Time' : 'وقت الإستلام') . '</strong>
                        <time id="pickup_time_gry_bx" datetime="26-10-2018">' . $pickup_time . '</time>
                    </div>
                </li>';


        $branch_html .= '<li style="display: '.(($search_mode == 'hourly' || $search_mode == 'subscription') ? 'none' : 'inline-block').';">';
        if ($is_wiz == false) {
            $branch_html .= '<div class="drop-off" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=4\'">';
        } else {
            $branch_html .= '<div class="drop-off">';
        }
        $branch_html .= '   <strong class="title">' . ($lang == 'eng' ? 'Drop Off Time' : 'وقت التسليم') . '</strong>
                        <time id="dropoff_time_gry_bx" datetime="26-10-2018">' . $dropoff_time . '</time>
                    </div>
                </li>';

        $branch_html .= '<li style="display: '.($search_mode == 'hourly' ? 'inline-block' : 'none').';">';
        if ($is_wiz == false) {
            $branch_html .= '<div class="drop-off" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=3\'">';
        } else {
            $branch_html .= '<div class="drop-off" onclick="selectHoursWiz()">';
        }
        $branch_html .= '  <strong class="title">' . trans('labels.book_for_hours') . '</strong>
                        <span class="book_for_hours" style="font-size: 11px;">'.($search_mode == 'hourly' && isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] > 0 ? $sessionVals['book_for_hours'].' '.trans('labels.hours') : trans('labels.select_booking_hours')).'</span>
                    </div>
                </li>';

        $branch_html .= '<li style="display: '.($search_mode == 'subscription' ? 'inline-block' : 'none').';">';
        if ($is_wiz == false) {
            $branch_html .= '<div class="drop-off" onclick="javascript:location.href=\'' . $lang_base_url . '?' . $search_mode . '=1&sess=1&wiz=3\'">';
        } else {
            $branch_html .= '<div class="drop-off" onclick="selectSubscriptionMonthsWiz()">';
        }
        $branch_html .= '  <strong class="title">' . trans('labels.subscribe_for_months') . '</strong>
                        <span class="subscribe_for_months" style="font-size: 11px;">'.($search_mode == 'subscription' && isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] > 0 ? $sessionVals['subscribe_for_months'].' '.trans('labels.months') : trans('labels.select_subscribe_months')).'</span>
                    </div>
                </li>';


        $branch_html .= '</ul>
        </div>';

        echo $branch_html;
    }

    public static function exportExcel($type, $data1, $data2, $data3, $sheetTitle1, $sheetTitle2, $sheetTitle3)
    {
        $fileName = "Bookings-Excel-" . date('Y-m-d-H-i-s') . '.xlsx';

        for ($i = 0; $i < count($data1); $i++) {
            $data1[$i]['RENT_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['RENT_PRICE'] > 0 ? round($data1[$i]['RENT_PRICE'] * 30) : $data1[$i]['RENT_PRICE']);
            $data1[$i]['CDW_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['CDW_PRICE'] > 0 ? round($data1[$i]['CDW_PRICE'] * 30) : $data1[$i]['CDW_PRICE']);
            $data1[$i]['GPS_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['GPS_PRICE'] > 0 ? round($data1[$i]['GPS_PRICE'] * 30) : $data1[$i]['GPS_PRICE']);
            $data1[$i]['EXTRA_DRIVER_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['EXTRA_DRIVER_PRICE'] > 0 ? round($data1[$i]['EXTRA_DRIVER_PRICE'] * 30) : $data1[$i]['EXTRA_DRIVER_PRICE']);
            $data1[$i]['BABY_SEAT_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['BABY_SEAT_PRICE'] > 0 ? round($data1[$i]['BABY_SEAT_PRICE'] * 30) : $data1[$i]['BABY_SEAT_PRICE']);
            $data1[$i]['DISCOUNT_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['DISCOUNT_PRICE'] > 0 ? round($data1[$i]['DISCOUNT_PRICE'] * 30) : $data1[$i]['DISCOUNT_PRICE']);

            if (isset($data1[$i]['QITAF_REDEEM_ID']) && $data1[$i]['QITAF_REDEEM_ID'] != '' && $data1[$i]['QITAF_AMOUNT'] > 0) {
                $data1[$i]['QITAF_REDEEM_ID'] = str_replace('.', ',', $data1[$i]['QITAF_REDEEM_ID']);
                $data1[$i]['QITAF_REDEEM_ID'] = explode(',', $data1[$i]['QITAF_REDEEM_ID'])[1];
            }

            unset($data1[$i]['QITAF_AMOUNT']); // unsetting because not needed in excel

            if (isset($data1[$i]['NIQATY_REDEEM_ID']) && $data1[$i]['NIQATY_REDEEM_ID'] != '' && $data1[$i]['NIQATY_AMOUNT'] > 0) {
                parse_str($data1[$i]['NIQATY_REDEEM_ID'], $niqaty_request_data);
                $data1[$i]['NIQATY_REDEEM_ID'] = $niqaty_request_data['transaction_reference'];
            }

            unset($data1[$i]['NIQATY_AMOUNT']); // unsetting because not needed in excel

            // OLD_FROM_DATE work here
            $booking_id = DB::table('booking')->where('reservation_code', $data1[$i]['BOOKING_ID'])->value('id');
            $booking_history = DB::table('booking_edit_history')->where('booking_id', $booking_id)->orderBy('id', 'desc')->first();
            if ($booking_history) {
                $data1[$i]['OLD_FROM_DATE'] = date('d-m-Y H:i:s', strtotime($booking_history->old_from_date));
            } else {
                $data1[$i]['OLD_FROM_DATE'] = '';
            }

            $data1[$i]['CDW_PLUS_PRICE'] = (float)($data1[$i]['SUBSCRIBE_FOR_MONTHS'] > 0 && $data1[$i]['CDW_PLUS'] > 0 ? round($data1[$i]['CDW_PLUS'] * 30) : $data1[$i]['CDW_PLUS']);
            unset($data1[$i]['CDW_PLUS']);

            $data1[$i]['ADDITIONAL_PRICE'] = $data1[$i]['IS_CAR_RATE_WITH_ADDITIONAL_UTILIZATION_RATE'];
            unset($data1[$i]['IS_CAR_RATE_WITH_ADDITIONAL_UTILIZATION_RATE']);

            $NIQATY_REDEEM_ID = $data1[$i]['NIQATY_REDEEM_ID'];
            unset($data1[$i]['NIQATY_REDEEM_ID']);

            $DISCOUNT_PRICE_ON_TOTAL = $data1[$i]['DISCOUNT_PRICE_ON_TOTAL'];
            unset($data1[$i]['DISCOUNT_PRICE_ON_TOTAL']);

            $data1[$i]['NIQATY_REDEEM_ID'] = $NIQATY_REDEEM_ID;
            $data1[$i]['DISCOUNT_PRICE_ON_TOTAL'] = $DISCOUNT_PRICE_ON_TOTAL;

            ////////////////////

            $SUBSCRIBE_FOR_MONTHS = $data1[$i]['SUBSCRIBE_FOR_MONTHS'];
            unset($data1[$i]['SUBSCRIBE_FOR_MONTHS']);
            $data1[$i]['SUBSCRIBE_FOR_MONTHS'] = $SUBSCRIBE_FOR_MONTHS;

            $THREE_MONTH_SUBSCRIPTION_PRICE = $data1[$i]['THREE_MONTH_SUBSCRIPTION_PRICE'];
            unset($data1[$i]['THREE_MONTH_SUBSCRIPTION_PRICE']);
            $data1[$i]['THREE_MONTH_SUBSCRIPTION_PRICE'] = $THREE_MONTH_SUBSCRIPTION_PRICE;

            $SIX_MONTH_SUBSCRIPTION_PRICE = $data1[$i]['SIX_MONTH_SUBSCRIPTION_PRICE'];
            unset($data1[$i]['SIX_MONTH_SUBSCRIPTION_PRICE']);
            $data1[$i]['SIX_MONTH_SUBSCRIPTION_PRICE'] = $SIX_MONTH_SUBSCRIPTION_PRICE;

            $NINE_MONTH_SUBSCRIPTION_PRICE = $data1[$i]['NINE_MONTH_SUBSCRIPTION_PRICE'];
            unset($data1[$i]['NINE_MONTH_SUBSCRIPTION_PRICE']);
            $data1[$i]['NINE_MONTH_SUBSCRIPTION_PRICE'] = $NINE_MONTH_SUBSCRIPTION_PRICE;

            $TWELVE_MONTH_SUBSCRIPTION_PRICE = $data1[$i]['TWELVE_MONTH_SUBSCRIPTION_PRICE'];
            unset($data1[$i]['TWELVE_MONTH_SUBSCRIPTION_PRICE']);
            $data1[$i]['TWELVE_MONTH_SUBSCRIPTION_PRICE'] = $TWELVE_MONTH_SUBSCRIPTION_PRICE;

            //////////////////////////////
            $IS_FREE_CDW_PROMO_APPLIED = $data1[$i]['IS_FREE_CDW_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_CDW_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_CDW_PROMO_APPLIED'] = $IS_FREE_CDW_PROMO_APPLIED;

            $IS_FREE_CDW_PLUS_PROMO_APPLIED = $data1[$i]['IS_FREE_CDW_PLUS_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_CDW_PLUS_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_CDW_PLUS_PROMO_APPLIED'] = $IS_FREE_CDW_PLUS_PROMO_APPLIED;

            $IS_FREE_BABY_SEAT_PROMO_APPLIED = $data1[$i]['IS_FREE_BABY_SEAT_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_BABY_SEAT_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_BABY_SEAT_PROMO_APPLIED'] = $IS_FREE_BABY_SEAT_PROMO_APPLIED;

            $IS_FREE_DRIVER_PROMO_APPLIED = $data1[$i]['IS_FREE_DRIVER_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_DRIVER_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_DRIVER_PROMO_APPLIED'] = $IS_FREE_DRIVER_PROMO_APPLIED;

            $IS_FREE_OPEN_KM_PROMO_APPLIED = $data1[$i]['IS_FREE_OPEN_KM_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_OPEN_KM_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_OPEN_KM_PROMO_APPLIED'] = $IS_FREE_OPEN_KM_PROMO_APPLIED;

            $IS_FREE_DELIVERY_PROMO_APPLIED = $data1[$i]['IS_FREE_DELIVERY_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_DELIVERY_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_DELIVERY_PROMO_APPLIED'] = $IS_FREE_DELIVERY_PROMO_APPLIED;

            $IS_FREE_DROPOFF_PROMO_APPLIED = $data1[$i]['IS_FREE_DROPOFF_PROMO_APPLIED'];
            unset($data1[$i]['IS_FREE_DROPOFF_PROMO_APPLIED']);
            $data1[$i]['IS_FREE_DROPOFF_PROMO_APPLIED'] = $IS_FREE_DROPOFF_PROMO_APPLIED;

            unset($data1[$i]['MOKAFAA_AMOUNT']); // unsetting because not needed in excel

            unset($data1[$i]['ANB_AMOUNT']); // unsetting because not needed in excel

            $MOKAFAA_REDEEM_ID = $data1[$i]['MOKAFAA_REDEEM_ID'];
            unset($data1[$i]['MOKAFAA_REDEEM_ID']);
            $data1[$i]['MOKAFAA_REDEEM_ID'] = $MOKAFAA_REDEEM_ID;

            $ANB_REDEEM_ID = $data1[$i]['ANB_REDEEM_ID'];
            unset($data1[$i]['ANB_REDEEM_ID']);
            $data1[$i]['ANB_REDEEM_ID'] = $ANB_REDEEM_ID;

            /////

            $IS_LIMOUSINE = $data1[$i]['IS_LIMOUSINE'];
            unset($data1[$i]['IS_LIMOUSINE']);
            $data1[$i]['IS_LIMOUSINE'] = $IS_LIMOUSINE;

            $IS_ROUND_TRIP = $data1[$i]['IS_ROUND_TRIP'];
            unset($data1[$i]['IS_ROUND_TRIP']);
            $data1[$i]['IS_ROUND_TRIP'] = $IS_ROUND_TRIP;

            $FLIGHT_NUMBER = $data1[$i]['FLIGHT_NUMBER'];
            unset($data1[$i]['FLIGHT_NUMBER']);
            $data1[$i]['FLIGHT_NUMBER'] = $FLIGHT_NUMBER;

            $WAITING_EXTRA_HOURS = $data1[$i]['WAITING_EXTRA_HOURS'];
            unset($data1[$i]['WAITING_EXTRA_HOURS']);
            $data1[$i]['WAITING_EXTRA_HOURS'] = $WAITING_EXTRA_HOURS;

            $LIMOUSINE_COST_CENTER = $data1[$i]['LIMOUSINE_COST_CENTER'];
            unset($data1[$i]['LIMOUSINE_COST_CENTER']);
            $data1[$i]['LIMOUSINE_COST_CENTER'] = $LIMOUSINE_COST_CENTER;

            /*$WAITING_EXTRA_HOURS_CHARGES = $data1[$i]['WAITING_EXTRA_HOURS_CHARGES'];
            unset($data1[$i]['WAITING_EXTRA_HOURS_CHARGES']);
            $data1[$i]['WAITING_EXTRA_HOURS_CHARGES'] = $WAITING_EXTRA_HOURS_CHARGES;*/

            $UTILIZATION_PERCENTAGE = $data1[$i]['UTILIZATION_PERCENTAGE'];
            unset($data1[$i]['UTILIZATION_PERCENTAGE']);
            $data1[$i]['UTILIZATION_PERCENTAGE'] = $UTILIZATION_PERCENTAGE;

            $UTILIZATION_PERCENTAGE_RATE = $data1[$i]['UTILIZATION_PERCENTAGE_RATE'];
            unset($data1[$i]['UTILIZATION_PERCENTAGE_RATE']);
            $data1[$i]['UTILIZATION_PERCENTAGE_RATE'] = $UTILIZATION_PERCENTAGE_RATE;

            $UTILIZATION_RECORD_TIME = $data1[$i]['UTILIZATION_RECORD_TIME'];
            unset($data1[$i]['UTILIZATION_RECORD_TIME']);
            $data1[$i]['UTILIZATION_RECORD_TIME'] = $UTILIZATION_RECORD_TIME;
        }

        for ($i = 0; $i < count($data3); $i++) {
            $data3[$i]['TRANS_AMOUNT'] = (float)$data3[$i]['TRANS_AMOUNT'];
        }

        $sheets = [
            new BookingsExport($sheetTitle1, $data1),
            new CustomersExport($sheetTitle2, $data2),
            new CollectionsExport($sheetTitle3, $data3),
        ];

        if ($type == "download") {
            return Excel::download(new ExportBookingsData($sheets), $fileName);
        } else {
            Excel::store(new ExportBookingsData($sheets), $fileName, 'public');
            return $fileName;
        }
    }

    public static function export_excel_file_custom($data, $file_name = "Registered-Users", $sheetTitle = "Registered Users", $type = "download")
    {
        $fileName = $file_name. "-" . date('Y-m-d-H-i-s');
        return custom::excelExport($fileName, $data);
    }

    public static function importExcel($file)
    {
        $results = Excel::toArray(new GeneralImport(), $file);
        return (isset($results[0]) ? $results[0] : []);
    }

    public static function loggedInUserProfileInnerInfo($type = "individual") // individual, corporate
    {
        $page = new \App\Models\Front\Page();
        $booking = new Booking();
        $user_id = Session::get('user_id');
        if ($type == "corporate") {
            $data['user_data'] = DB::table('corporate_customer')->whereRaw('FIND_IN_SET(' . Session::get('user_id') . ', uid)')->first();
            // $data['user_bookings_count'] = $page->getCorporateUserBookingsCount(Session::get('user_id'));
            $data['user_bookings_count'] = 0;
        } else {
            $data['user_data'] = $page->getSingle('individual_customer', array('uid' => Session::get('user_id')));
            // $data['user_bookings_count'] = $page->getUserBookingsCount(Session::get('user_id'));
            $data['user_bookings_count'] = 0;
        }
        //$data['user_bookings_count'] = $page->getMultipleRowsCount('booking_individual_user', array('uid' => Session::get('user_id')), 'booking_id');
        return $data;
    }

    public static function isCorporateLoyalty() // individual, corporate
    {
        if (Session::get('user_type') != "corporate_customer")
            return 0;

        $page = new \App\Models\Front\Page();
        $user_id = Session::get('user_id');
        $user_data = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$user_id.', uid)')->first();

        return $user_data->call_center;
    }

    public static function getCheckoutDays($start, $end)
    {
        $seconds = strtotime($end) - strtotime($start);
        $days = floor($seconds / 86400);
        $hours = floor(($seconds - ($days * 86400)) / 3600);
        $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600)) / 60);
        if ($hours > 0 || $minutes > 0) {
            $days++;
        }
        return $days;
    }

    public static function getDifferenceInDates($start, $end, $type = 'mins')
    {
        $seconds = strtotime($end) - strtotime($start);
        $days = floor($seconds / 86400);
        $hours = floor(($seconds - ($days * 86400)) / 3600);
        $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600)) / 60);

        if ($type == 'days') {
            return $days;
        } elseif ($type == 'hours') {
            return $hours;
        } elseif ($type == 'mins') {
            return $minutes;
        } else {
            return $hours;
        }
    }

    public static function getDateDifference($start, $end)
    {

        $seconds = $start->diffInSeconds($end);
        $minutes = $start->diffInMinutes($end);
        $hours = $start->diffInHours($end);
        $days = $start->diffInDays($end);

        $dateArr['seconds'] = $seconds;
        $dateArr['minutes'] = $minutes;
        $dateArr['hours'] = $hours;
        $dateArr['days'] = $days;
        return $dateArr;
    }


    // send sms Unifonic API function
    //success case return true and error case return message.
    public static function sendSMSOld($phone_No, $message, $lang = "eng", $returnMsg = false)
    {
        $api = custom::api_settings();
        $result = array();
        $client = new Client();
        $phone_length = strlen((string)$phone_No);
        $returnStatus = false;
        try {
            // checking if multiple comma separated numbers are being sent for email
            if (strpos($phone_No, ',') !== false) {
                $phone_No_arr = explode(",", $phone_No);
                foreach ($phone_No_arr as $mobileNo) {
                    $result = $client->Messages->Send($mobileNo, $message, $api->unifonic_sender_id);
                }
            } else {
                $result = $client->Messages->Send($phone_No, $message, $api->unifonic_sender_id);
            }

            if ($returnMsg) {
                echo '<pre>';
                print_r($result);
                exit();
            }

            if (is_object($result)) {

                if (isset($result->MessageID)) {
                    $returnStatus = true;
                } else {
                    //if there would be any error of sms it will fall in this false
                    $returnStatus = false;
                }
            } else {
                $returnStatus = false;
            }

            if ($returnStatus == true) {
                return true;
            } else {
                if ($lang == "eng") {
                    $msg = "SMS couldn't be sent at (" . $phone_No . ")! Possible reason is the in-correct number. The correct format should be (966123456789)";
                } else {
                    //arabic translation pending
                    $msg = "SMS couldn't be sent at (" . $phone_No . ")! Possible reason is the in-correct number. The correct format should be (966123456789)";
                }
                return $msg;
            }

        } catch (Exception $e) {
            // do nothing
            return false;
        }


    }

    public static function sendSMS($mobile_nos, $message, $lang = "eng", $debug = false) {
        $site = custom::site_settings();
        $api = custom::api_settings();

        if ($site->sms_api == 'unifonic') {
            try {
                if ($api->unifonic_app_id) {
                    $mobile_nos = explode(",", $mobile_nos);

                    $basicAuthUserName = $api->unifonic_username;
                    $basicAuthPassword = $api->unifonic_password;

                    $client = new \UnifonicNextGenLib\UnifonicNextGenClient($basicAuthUserName, $basicAuthPassword);

                    $restController = $client->getRest();

                    foreach ($mobile_nos as $mobile_no) {
                        $result = $restController->createSendMessage($api->unifonic_app_id, $api->unifonic_sender_id, $message, $mobile_no, 'JSON', time(), true, 'sent', false);
                    }

                    if ($debug) {
                        self::dump($result);
                    }
                }

                return true;
            } catch (APIException $e) {
                return true;
            }
        } elseif ($site->sms_api == 'taqnyat') {
            try {
                $mobile_nos = explode(",", $mobile_nos);
                $data = [
                    "recipients" => $mobile_nos,
                    "body" => $message,
                    "sender" => $api->taqnyat_sender_id
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.taqnyat.sa/v1/messages');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $headers[] = 'Authorization: Bearer ' . $api->taqnyat_bearer_token;
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                $result = json_decode($result);
                if ($debug) {
                    self::dump($result);
                }
                curl_close($ch);
                return true;
            } catch (\Exception $e) {
                return true;
            }
        }
        return true;
    }


    public static function generateRand()
    {
        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'kra.ced.sa') {
            return 123456;
        } else {
            return rand(100000, 999999);
        }
    }


    public static function intPart($float)
    {
        if ($float < -0.0000001)
            return ceil($float - 0.0000001);
        else
            return floor($float + 0.0000001);
    }

    public static function Hijri2Greg($day, $month, $year, $string = false)
    {
        $day = (int)$day;
        $month = (int)$month;
        $year = (int)$year;

        $jd = custom::intPart((11 * $year + 3) / 30) + 354 * $year + 30 * $month - custom::intPart(($month - 1) / 2) + $day + 1948440 - 385;

        if ($jd > 2299160) {
            $l = $jd + 68569;
            $n = custom::intPart((4 * $l) / 146097);
            $l = $l - custom::intPart((146097 * $n + 3) / 4);
            $i = custom::intPart((4000 * ($l + 1)) / 1461001);
            $l = $l - custom::intPart((1461 * $i) / 4) + 31;
            $j = custom::intPart((80 * $l) / 2447);
            $day = $l - custom::intPart((2447 * $j) / 80);
            $l = custom::intPart($j / 11);
            $month = $j + 2 - 12 * $l;
            $year = 100 * ($n - 49) + $i + $l;
        } else {
            $j = $jd + 1402;
            $k = custom::intPart(($j - 1) / 1461);
            $l = $j - 1461 * $k;
            $n = custom::intPart(($l - 1) / 365) - custom::intPart($l / 1461);
            $i = $l - 365 * $n + 30;
            $j = custom::intPart((80 * $i) / 2447);
            $day = $i - custom::intPart((2447 * $j) / 80);
            $i = custom::intPart($j / 11);
            $month = $j + 2 - 12 * $i;
            $year = 4 * $k + $n + $i - 4716;
        }

        $data = array();
        $date['year'] = $year;
        $date['month'] = $month;
        $date['day'] = $day;

        if (!$string)
            return $date;
        else
            return "{$year}-{$month}-{$day}";
    }

    public static function Greg2Hijri($day, $month, $year, $string = false)
    {
        $day = (int)$day;
        $month = (int)$month;
        $year = (int)$year;

        if (($year > 1582) or (($year == 1582) and ($month > 10)) or (($year == 1582) and ($month == 10) and ($day > 14))) {
            $jd = custom::intPart((1461 * ($year + 4800 + custom::intPart(($month - 14) / 12))) / 4) + custom::intPart((367 * ($month - 2 - 12 * (custom::intPart(($month - 14) / 12)))) / 12) -
                custom::intPart((3 * (custom::intPart(($year + 4900 + custom::intPart(($month - 14) / 12)) / 100))) / 4) + $day - 32075;
        } else {
            $jd = 367 * $year - custom::intPart((7 * ($year + 5001 + custom::intPart(($month - 9) / 7))) / 4) + custom::intPart((275 * $month) / 9) + $day + 1729777;
        }

        $l = $jd - 1948440 + 10632;
        $n = custom::intPart(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (custom::intPart((10985 - $l) / 5316)) * (custom::intPart((50 * $l) / 17719)) + (custom::intPart($l / 5670)) * (custom::intPart((43 * $l) / 15238));
        $l = $l - (custom::intPart((30 - $j) / 15)) * (custom::intPart((17719 * $j) / 50)) - (custom::intPart($j / 16)) * (custom::intPart((15238 * $j) / 43)) + 29;

        $month = custom::intPart((24 * $l) / 709);
        $day = $l - custom::intPart((709 * $month) / 24);
        $year = 30 * $n + $j - 30;

        $date = array();
        $date['year'] = $year;
        $month = $month <= 9 ? '0' . $month : $month;
        $date['month'] = $month;
        $day = $day <= 9 ? '0' . $day : $day;
        $date['day'] = $day;

        if (!$string)
            return $date;
        else
            return "{$year}-{$month}-{$day}";

    }

    public static function checkIfMobielTabOrPC()
    {
        $mobile = new \Mobile_Detect();
        //$functionList = get_class_methods($mobile);
        //echo '<pre>';print_r($functionList);exit();
        if ($mobile->isMobile()) {
            $medium = 'mobile';
        } elseif ($mobile->isTablet()) {
            $medium = 'tablet';
        } else {
            $medium = 'pc';
        }
        return $medium;

    }

    public static function gen_random_string($length = 6)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $final_rand = '';
        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $final_rand;
    }

    public static function generateBarcodeHtml($string)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        return $generator->getBarcode($string, $generator::TYPE_CODE_128);
    }

    public static function generateBarcodeJPG($string)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();
        return '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128)) . '" width="227" height="61" alt="bar code" style="display:inline-block;">';
    }

    public static function generateBarcodeImage($string, $returnType = 'image')
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        if ($returnType == 'code') {
            return 'data:image/png;base64,' . base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128));
        } elseif ($returnType == 'code_only') {
            return base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128));
        } else {
            return '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128)) . '" width="227" height="61" alt="bar code" style="display:inline-block;">';
        }
    }

    public static function rights($section_id, $action)
    {
        $page = new \App\Models\Front\Page();
        $data = array();
        $data['section_id'] = $section_id;
        $adminRole = $page->getSingle('admin_role', array('uid' => Auth::id()));
        $data['role_id'] = $adminRole->role_id;
        $result = $page->getSingle('setting_user_rights', $data);
        //echo '<pre>';print_r($result);exit();
        switch ($action) {
            case 'view':
                if (isset($result->read) && $result->read == 1)
                    return true;
                else
                    return false;
                break;
            case 'add':
                if (isset($result->write) && $result->write == 1)
                    return true;
                else
                    return false;
                break;
            case 'edit':
                if (isset($result->edit) && $result->edit == 1)
                    return true;
                else
                    return false;
                break;
            case 'delete':
                if (isset($result->delete) && $result->delete == 1)
                    return true;
                else
                    return false;
                break;
            case 'all':
                if ((isset($result->read) && $result->read != 0) || (isset($result->write) && $result->write != 0) || (isset($result->edit) && $result->edit != 0) || (isset($result->delete) && $result->delete != 0)) {
                    return true;
                } else {
                    return false;
                }

                break;

        }

    }

    public static function validateSaudiIDNew($id_no, $lang = 'eng')
    {
        $validate = new \validateSAID();
        if ($validate->check($id_no) == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function validateSaudiID($id_no, $lang)
    {
        if ($lang == 'arb') {
            $hl = 'ar';
        } else {
            $hl = 'en';
        }
        $validate = new \validateSAID($hl);
        if ($validate->check($id_no) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function convertEngTimeToArbTime($time, $type = 'android')
    {
        // android_url = http://kra.ced.sa/service/getCars?k=ItykVex546VBeiXabxlExlyzErtc313&lang=ar&region_id=&city_id=&branch_id_from=&branch_id_to=&pickup_date=20-7-2017&pickup_time=٥٠:١٠م&dropoff_date=20-7-2017&dropoff_time=٥٠:٠٩م&customer_type=Individual&user_id=240305&category=0&id_no_for_loyalty_check=0

        // ios_url = http://kra.ced.sa/service/getCars?k=ItykVex546VBeiXabxlExlyzErtc313&lang=arb&region_id=3&city_id=7&branch_id_from=59&branch_id_to=66&pickup_date=21-07-2017&pickup_time=٥٠:١٠م&dropoff_date=22-07-2017&dropoff_time=٥٠:٠٩م&customer_type=Individual&user_id=0&category=0&id_no_for_loyalty_check=0
        if ($type == 'android') {
            $am_pm = 'AM';
            $standard = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
            $eastern_arabic_symbols = array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩");
            if (strpos($time, 'ص') !== false) {
                $am_pm = 'AM';
                $time = rtrim($time, "ص");
            } elseif (strpos($time, 'م') !== false) {
                $am_pm = 'PM';
                $time = rtrim($time, "م");
            }
            $time = explode(':', $time);
            $hours = str_replace($eastern_arabic_symbols, $standard, $time[1]);
            $mins = str_replace($eastern_arabic_symbols, $standard, $time[0]);
            $time = $hours . ':' . $mins . ' ' . $am_pm;
            return $time;
        } elseif ($type == 'ios') {
            $am_pm = 'AM';
            $standard = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
            $eastern_arabic_symbols = array("٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩");
            if (strpos($time, 'ص') !== false) {
                $am_pm = 'AM';
                $time = rtrim($time, "ص");
            } elseif (strpos($time, 'م') !== false) {
                $am_pm = 'PM';
                $time = rtrim($time, "م");
            }
            $time = explode(':', $time);
            $hours = str_replace($eastern_arabic_symbols, $standard, $time[0]);
            $mins = str_replace($eastern_arabic_symbols, $standard, $time[1]);
            $time = $hours . ':' . $mins . ' ' . $am_pm;
            return $time;
        }
    }

    // To get HTML of a file just like $this->load->view('html file', $data, true);
    public static function getHtml($view, $data)
    {
        $view = \View::make($view, $data);
        $contents = $view->render();
        return $contents;
    }

    public static function getPhoneNumber($phone_number)
    {
        $phone_number = trim($phone_number);
        $page = new \App\Models\Front\Page();
        $countries = $page->getAll('countries');
        if (strpos($phone_number, '+') !== false) {
            $phone_number = str_replace("+", "", $phone_number);
        }

        $country_name = "";
        $number_code = "";
        $mobile_no = $phone_number;

        foreach ($countries as $country) {
            $country_code = $country->phonecode;
            $country_code_length = strlen($country_code);
            $trimmed_country_code = substr($phone_number, 0, $country_code_length);
            if ($country_code == $trimmed_country_code) {
                $country_name = $country->nicename;
                $number_code = $trimmed_country_code;
                $mobile_no = substr($phone_number, $country_code_length);
                $mobile_no = ltrim($mobile_no, "0");
                $mobile_no = (int)$mobile_no;
            }

        }
        return array('country_name' => $country_name, 'country_code' => $number_code, 'number' => "$mobile_no", 'original_no' => $phone_number);
    }

    public static function testEmail($response)
    {
        $email['subject'] = 'test subject';
        $email['fromEmail'] = 'bilal_ejaz@astutesol.com';
        $email['fromName'] = "test from name";
        $email['toEmail'] = 'bilal_ejaz@astutesol.com';
        $email['ccEmail'] = ""; //$smtp->username;
        $email['bccEmail'] = '';
        $email['attachment'] = '';

        $data['data']['name'] = "Admin";
        $data['data']['gender'] = "male";
        $data['data']['contact_no'] = '03368809300';
        $data['data']['lang_base_url'] = self::baseurl('/');
        $data['data']['message'] = 'test message';

        $data['data']['info'] = $response;

        custom::sendEmail('form', $data, $email, "eng");
    }

    public static function paySadad()
    {
        $payment_data = array(
            'merchant_email' => 'onlinepayments@key.sa',
            'secret_key' => 'fEDxuCrm03dwZtsQvDk4lthLH8xLTNE8shFXT7jUawq90T0vxlATOREw0bhTZW1sdHp7OJ1Zgr9glaLSwlLkdqRdC97B593iQtqF',
            //'merchant_email' => 'waqas@astutesol.com',
            //'secret_key' => 'f1wRoLDCRy3ZAjCQ2y6uU5HtsEl1EzuUA1LZMpBDXFDyYfp0OvuIBcc3Qx5v6zpHAbHnAj1yYE7WUwXJ1srCEDGvbL0ilpZWBHjc',
            'site_url' => 'http://key.sa',
            'return_url' => 'http://key.sa/?order_id=-1',
            'title' => 'Renting Car Order No 123',
            'cc_first_name' => 'SADAD',
            'cc_last_name' => 'Test',
            'cc_phone_number' => '00966',
            'phone_number' => '123123123456',
            'email' => 'johndoe@example.com',
            //'product_per_title' => 'MobilePhone || Charger || Camera', //MERCEDES E300 2016 // note is it? products_per_title ?????
            'products_per_title' => 'MobilePhone || Charger || Camera', //MERCEDES E300 2016 // note is it? products_per_title ?????
            'unit_price' => '12.123 || 21.345 || 35.678 ',
            'quantity' => '2 || 3 || 1',
            'other_charges' => '12.123',
            'amount' => '136.082',
            'discount' => '10.123',
            'currency' => 'SAR',
            'reference_no' => 'ABC-123',
            'ip_customer' => '116.58.71.82',
            'ip_merchant' => '159.253.153.126',
            'address_shipping' => 'Flat 3021 Khobar',
            'state_shipping' => 'Khobar',
            'city_shipping' => 'Khobar',
            'postal_code_shipping' => '1234',
            'country_shipping' => 'SAU',
            'msg_lang' => 'English', //Arabic
            'cms_with_version' => 'PHP Laravel 5.4',
            'olp_id' => 'arun123'
        );

        $request_string1 = http_build_query($payment_data);

        $response_data = custom::sendRequest('https://www.paytabs.com/apiv2/create_sadad_payment', $request_string1);

        $object = json_decode($response_data);

        /*echo "Response:<br><pre>";
        print_r($object);

        exit();*/

        if (isset($object->payment_url) && $object->payment_url != '') {
            $url = $object->payment_url;
            $pid = $object->p_id;
            header("Location:" . $url);
            exit();
        }

    }

    public static function sendRequest($gateway_url, $request_string)
    {

        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_URL, $gateway_url);
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_HEADER, false);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        @curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = @curl_exec($ch);
        if (!$result)
            die(curl_error($ch));

        @curl_close($ch);

        return $result;
    }

    public static function validateIDNoForMobileBK($id_type, $id_no, $lang)
    {
        if (($id_type == '243' || $id_type == '68')) {
            if (strlen($id_no) !== 10) {
                $response['status'] = 0;
                $response['message'] = "ID number field must contain only 10 characters.";
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
    }

    public static function validateIDNoForMobile($id_type, $id_no, $lang)
    {
        if ($_SERVER['SERVER_NAME'] != 'www.key.sa') {
            return true; // remove this when we go live
        }
        if (($id_type == '243' || $id_type == '68')) {
            if (strlen($id_no) !== 10) {
                $response['status'] = 0;
                $response['message'] = "ID number field must contain only 10 characters.";
                $response['response'] = "";
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                if (!custom::validateSaudiID($id_no, $lang)) {
                    $response['status'] = 0;
                    $response['message'] = "ID number you entered is not valid.";
                    $response['response'] = "";
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit();
                }
            }
        }
    }

    public static function getLocationDetails($geolocation)
    {
        $api = custom::api_settings();
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&key=' . $api->google_api_key_for_delivery . '&sensor=false';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $json_decode = json_decode($response);
        // self::dump($json_decode);
        if (isset($json_decode->results[0])) {
            $response = array();
            foreach ($json_decode->results[0]->address_components as $addressComponet) {
                if (in_array('political', $addressComponet->types)) {
                    $response[] = $addressComponet->long_name;
                }
            }

            if (isset($response[0])) {
                $first = $response[0];
            } else {
                $first = 'null';
            }
            if (isset($response[1])) {
                $second = $response[1];
            } else {
                $second = 'null';
            }
            if (isset($response[2])) {
                $third = $response[2];
            } else {
                $third = 'null';
            }
            if (isset($response[3])) {
                $fourth = $response[3];
            } else {
                $fourth = 'null';
            }
            if (isset($response[4])) {
                $fifth = $response[4];
            } else {
                $fifth = 'null';
            }

            if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null') {
                /* echo "<br/>Address:: ".$first;
                 echo "<br/>City:: ".$second;
                 echo "<br/>State:: ".$fourth;
                 echo "<br/>Country:: ".$fifth;*/

                $locationArr['address'] = $first;
                $locationArr['city'] = $second;
                $locationArr['state'] = $fourth;
                $locationArr['country'] = $fifth;
            } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null') {
                /*echo "<br/>Address:: ".$first;
                echo "<br/>City:: ".$second;
                echo "<br/>State:: ".$third;
                echo "<br/>Country:: ".$fourth;*/

                $locationArr['address'] = $first;
                $locationArr['city'] = $second;
                $locationArr['state'] = $third;
                $locationArr['country'] = $fourth;
            } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null') {
                /*echo "<br/>City:: ".$first;
                echo "<br/>State:: ".$second;
                echo "<br/>Country:: ".$third;*/

                $locationArr['address'] = '';
                $locationArr['city'] = $first;
                $locationArr['state'] = $second;
                $locationArr['country'] = $third;
            } else if ($first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                /*echo "<br/>State:: ".$first;
                echo "<br/>Country:: ".$second;*/

                $locationArr['address'] = '';
                $locationArr['city'] = '';
                $locationArr['state'] = $first;
                $locationArr['country'] = $second;
            } else if ($first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
                //echo "<br/>Country:: ".$first;

                $locationArr['address'] = '';
                $locationArr['city'] = '';
                $locationArr['state'] = '';
                $locationArr['country'] = $first;
            }
            $locationArr['message'] = '';
            return $locationArr;
        } else {
            $locationArr['address'] = '';
            $locationArr['city'] = '';
            $locationArr['state'] = '';
            $locationArr['country'] = '';
            $locationArr['message'] = 'No record Found For This Location';
            return $locationArr;
        }
    }

    public static function getCleanLocationName($location_coordinates, $type = '')
    {
        $string = '';
        $response = custom::getLocationDetails($location_coordinates);
        unset($response['message']);
        if ($type == 'short') {
            unset($response['state']);
            unset($response['country']);
        }
        foreach ($response as $key => $val) {
            $string .= $val . ", ";
        }
        $string = rtrim($string, ", ");
        return $string;
    }

    public static function deliveryPickupTabsArea($lang)
    {
        //echo Session::get('search_data')['is_delivery_mode'];exit();
        // some code for this is also written in index.php view, please see there too
        $site_settings = custom::site_settings();
        $html = "";

        $html .= '<div class="searchButtons search-button-new-design">';
        $html .= '<ul>';

        $html .= '<li id="pickup_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn key-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->daily_tab_title_eng : $site_settings->daily_tab_title_arb) . '
                        </a>
                        </li>';

        if ($site_settings->weekly_renting_mode == 'on') {
            $html .= '<li id="weekly_renting_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->weekly_tab_title_eng : $site_settings->weekly_tab_title_arb) . '
                        </a>
                        </li>';
        }

        if ($site_settings->monthly_renting_mode == 'on') {
            $html .= '<li id="monthly_renting_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->monthly_tab_title_eng : $site_settings->monthly_tab_title_arb) . '
                        </a>
                        </li>';
        }

        if ($site_settings->hourly_renting_mode == 'on') {
            $html .= '<li id="hourly_renting_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->hourly_tab_title_eng : $site_settings->hourly_tab_title_arb) . '
                        </a>
                        </li>';
        }

        if ($site_settings->delivery_mode == 'on') {
            $html .= '<li id="delivery_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->delivery_tab_title_eng : $site_settings->delivery_tab_title_arb) . '
                        </a>
                        </li>';
        }

        if ($site_settings->subscription_renting_mode == 'on') {
            $html .= '<li id="subscription_renting_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->subscription_tab_title_eng : $site_settings->subscription_tab_title_arb) . '
                        </a>
                        </li>';
        }

        $has_limousine_option = self::has_limousine_option();
        if ($site_settings->limousine_mode == 'on' && $has_limousine_option) {
            $html .= '<li id="limousine_mode_tab">
                        <a href="javascript:void(0);">
                        <i class="serBtn car-icon"></i>
                        ' . ($lang == 'eng' ? $site_settings->limousine_tab_title_eng : $site_settings->limousine_tab_title_arb) . '
                        </a>
                        </li>';
        }

        $html .= '</ul>
                    <div class="clearfix"></div>
                </div>';

        return $html;

    }

    public static function has_limousine_option() {
        if (Session::has('user_type') && Session::get('user_type') == 'corporate_customer') {
            $corporate_customer_id = Session::get('corporate_customer_id');
            $corporate_user = DB::table('corporate_customer')->where('id', $corporate_customer_id)->first();
            if ($corporate_user && $corporate_user->has_limousine_option == 'Yes') {
                return true;
            }
        }
        return false;
    }

    public static function addClass() // to adjust design if delivery mode is turned off, we are adding a class to container
    {
        $site = custom::site_settings();
        if ($site->delivery_mode == 'on' || $site->hourly_renting_mode == 'on' || $site->monthly_renting_mode == 'on' || $site->weekly_renting_mode == 'on') {
            $class = 'delivery';
        } else {
            $class = 'noDelivery';
        }

        // $class = 'delivery';
        return $class;
    }

    public static function toInteger($data)
    {
        $data = (array)$data;
        echo "<pre>";
        print_r($data);
        exit();
        foreach ($data as $key => $value) {
            if (is_integer($value)) {
                $data[$key] = (int)$value;
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public static function checkIfPolygonContainsCoords()
    {
        $pointLocation = new \pointLocation();
        $points = array("50 70", "70 40", "-20 30", "100 10", "-10 -10", "40 -20", "110 -20");
        $polygon = array("-50 30", "50 70", "100 50", "80 10", "110 -10", "110 -30", "-20 -50", "-30 -40", "10 -10", "-10 10", "-30 -20", "-50 30");
// The last point's coordinates must be the same as the first one's, to "close the loop"
        foreach ($points as $key => $point) {
            echo "point " . ($key + 1) . " ($point): " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
        }
    }

    public static function sendPushNotification($title, $message, $registration_ids, $booking_id = '', $notification_type = "survey", $print = false)
    {
        $api = custom::api_settings();
        if (!defined('API_ACCESS_KEY')) define('API_ACCESS_KEY', $api->fcm_key);

        $fields = array(
            'registration_ids' => $registration_ids,
            "content_available" => true,
            "priority" => "high",
            "notification" => array
            (
                "title" => $title,
                "body" => $message,
                "sound" => "default",
                "largeIcon" => self::baseurl('/public/uploads/large-icon-notification.png'),
                "smallIcon" => self::baseurl('/public/uploads/small-icon-notification.png')
            ),
            "data" => array
            (
                "title" => $title,
                "body" => $message,
                "notification_type" => $notification_type,
                "booking_id" => $booking_id
            )
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, JSON_UNESCAPED_UNICODE));

        $result = curl_exec($ch);
        $result = json_decode($result, TRUE);
        curl_close($ch);

        if ($print) {
            echo '<pre>';
            print_r($result);
            exit();
        } else {
            return $result;
        }
    }

    public static function logQuery()
    {
        DB::enableQueryLog();
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        print_r($lastQuery);
        exit();
    }

    public static function convertArabicNumbersToEnglish($string)
    {
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;

    }

    public static function month_english($month)
    {
        $data = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');

        return $data[$month];

    }

    public static function month_arabic($month)
    {
        $data = array('January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس', 'April' => 'أبريل ', 'May' => 'مايو', 'June' => 'يونية', 'July' => 'يولية', 'August' => 'أغسطس', 'September' => 'سبتمبر', 'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر');

        return $data[$month];

    }

    public static function sellCarsHtml($cars, $base_url, $lang_base_url, $lang = 'eng')
    {
        $items = [];
        $html = '<style>
                    .imgArea {
                        position: relative;
                    }
                    .sold-img {
                        position: absolute;
                        width: 30%;
                    }
                </style>';
        $html .= '<div class="row">';
        foreach ($cars as $car) {
            if ($lang == 'arb') {
                $title = $car->arb_title;
                $description = $car->arb_car_desc;
                $brand_title = $car->arb_brand_title;
            } else {
                $title = $car->eng_title;
                $description = $car->eng_car_desc;
                $brand_title = $car->eng_brand_title;
            }

            $html .= '<div class="col-lg-4 col-md-6 col-12 car-to-sell">
                                <div class="bgPadBox">
                                    <div class="makeEqlheight" style="">
                                        <div class="imgArea">';
            if ($car->is_sold == 1) {
                $html .= '<div class="sold-img">
                                                <img src="' . $base_url . '/public/frontend/images/sold-' . $lang . '.png" alt="' . $title . '" height="" width=""/>
                                            </div>';
            }
            $html .= '<img src="' . $base_url . '/public/uploads/' . $car->image1 . '" alt="' . $title . '" height="" width=""/>
                                        </div>
                                        <h3>' . $brand_title . '</h3>
                                        <p>' . $title . '</p>
                                        <p> <strong>' . ($lang == 'eng' ? 'Year' : 'السنة') . ': </strong>&nbsp;' . $car->year . '</p>
                                        ' . $description . '
									</div>
									<button type="button" class="btn-contact" onclick="interested_in_buying(' . $car->id . ');">' . ($lang == 'eng' ? 'Interested in this' : 'مهتم في هذا') . '</button>
                                </div>                                
                            </div>';


            $item_data = [
                'item_id' => $car->eng_brand_title . " " . $car->eng_title . ' ' . $car->year,
                'item_name' => $car->eng_brand_title . " " . $car->eng_title . ' ' . $car->year
            ];
            $items[] = $item_data;
        }

        $event = 'view_item_list';
        $event_data = [
            'items' => $items
        ];
        custom::sendEventToGA4($event, $event_data);

        $html .= '</div>';
        return $html;
    }

    public static function getSiteName($lang)
    {
        $site_data = custom::site_settings();
        $site_title = ($lang == 'eng' ? $site_data->site_title : $site_data->arb_site_title);
        return $site_title;
    }

    public static function checkBookingDaysLimit($booking_days_limit, $date_difference)
    {

        if ($booking_days_limit != 0) {
            if ((int)$date_difference <= (int)$booking_days_limit) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function checkIfModelExistGetInArray($data)
    {
        /*here we will get all car models from booking_availability in
        array and check searched car model with in_array function*/
        // $availability = DB::table('booking_availability')->where('active_status', 'active')->select('car_model_id')->get();

        $availability = DB::table('booking_availability')
            ->where('region_id', $data['region_id'])
            ->where('city_id', $data['city_id'])
            /* ->where('car_type_id', $data['type_id'])
             ->where('car_model_id', $data['model_id'])*/
            ->where(function ($query) use ($data) {
                /*$query->whereRaw('(from_date is null AND to_date is null) OR
                     (from_date <= "'.date('Y-m-d H:i:s',strtotime($data['pickup_date'])).'" AND to_date is null) OR
                     (from_date >= "'.date('Y-m-d H:i:s',strtotime($data['pickup_date'])).'" AND to_date >= "'.date('Y-m-d H:i:s',strtotime($data['pickup_date'])).'")
                ');*/
                $query->whereRaw('(from_date is null AND to_date is null) OR
                        (from_date <= "' . date('Y-m-d H:i:s', strtotime($data['pickup_date'])) . '" AND to_date is null) OR 
                        (from_date <= "' . date('Y-m-d H:i:s', strtotime($data['pickup_date'])) . '" AND to_date >= "' . date('Y-m-d H:i:s', strtotime($data['pickup_date'])) . '") OR 
                        (from_date >= "' . date('Y-m-d H:i:s', strtotime($data['pickup_date'])) . '" AND to_date <= "' . date('Y-m-d H:i:s', strtotime($data['dropoff_date'])) . '")
                    ');
            })
            ->where('active_status', 'active')
            ->get();


        $avail_array = array();

        // custom::dump($availability);

        $avail_array_car_ids = array();
        $avail_array_per_day = array();

        $avail_array_utilization_percentage_1 = array();
        $avail_array_increase_price_percentage_1 = array();

        $avail_array_utilization_percentage_2 = array();
        $avail_array_increase_price_percentage_2 = array();

        $avail_array_utilization_percentage_3 = array();
        $avail_array_increase_price_percentage_3 = array();

        foreach ($availability as $key => $avail) {

            $avail_array_car_ids[] = $avail->car_model_id;
            $avail_array_per_day[] = $avail->booking_per_day;

            $avail_array_utilization_percentage_1[] = $avail->utilization_percentage_1;
            $avail_array_increase_price_percentage_1[] = $avail->increase_price_percentage_1;

            $avail_array_utilization_percentage_2[] = $avail->utilization_percentage_2;
            $avail_array_increase_price_percentage_2[] = $avail->increase_price_percentage_2;

            $avail_array_utilization_percentage_3[] = $avail->utilization_percentage_3;
            $avail_array_increase_price_percentage_3[] = $avail->increase_price_percentage_3;

        }

        $avail_array_return[] = $avail_array_car_ids;
        $avail_array_return[] = $avail_array_per_day;

        $avail_array_return[] = $avail_array_utilization_percentage_1;
        $avail_array_return[] = $avail_array_increase_price_percentage_1;

        $avail_array_return[] = $avail_array_utilization_percentage_2;
        $avail_array_return[] = $avail_array_increase_price_percentage_2;

        $avail_array_return[] = $avail_array_utilization_percentage_3;
        $avail_array_return[] = $avail_array_increase_price_percentage_3;

        return $avail_array_return;
    }

    public static function checkIfCDWSelected($booking_id)
    {
        $cdw_price = DB::table('booking_payment')->where('booking_id', $booking_id)->first();
        $is_cdw = false;
        if ($cdw_price->cdw_price > 0) {
            $is_cdw = true;
        }
        return $is_cdw;
    }

    public static function getCDWPrice($customer_type, $car_model_id, $renting_type_id)
    {
        $get_cdw_price = DB::table('car_price')->where('customer_type', $customer_type)->where('car_model_id', $car_model_id)->where('renting_type_id', $renting_type_id)->where('charge_element', 'CDW')->first();
        $cdw_price = 0;
        if ($get_cdw_price->price > 0) {
            $cdw_price = $get_cdw_price->price;
        }
        return $cdw_price;
    }

    public static function checkCarAvailabilityBookings($data)
    {
        $pickup_date = date('Y-m-d', strtotime($data['pickup_date']));

        $query = "select b.`car_model_id`, count(*) as count from `booking` b join booking_individual_payment_method bipm on b.id=bipm.booking_id left join booking_cc_payment bcp on b.id=bcp.booking_id left join booking_corporate_invoice bci on b.id=bci.booking_id where 
         DATE(b.from_date) = '" . $pickup_date . "' and (b.`booking_status` = 'Not Picked' or b.`booking_status` = 'Picked') and (bipm.payment_method='Cash' or bcp.status='completed' or bci.payment_status='paid' or bipm.payment_method='Corporate Credit') and b.`from_location` in (select id from branch where city_id=" . $data['city_id'] . ") AND b.car_model_id IN (" . $data['car_ids_for_availability'] . ") GROUP BY b.`car_model_id`";

        $models = DB::select($query);
        return $models;
    }

    public static function checkCarAvailability($data, $booking_per_days)
    {
        $pickup_date = date('Y-m-d', strtotime($data['pickup_date']));

        $query = "select count(*) as count from `booking` b join booking_individual_payment_method bipm on b.id=bipm.booking_id left join booking_cc_payment bcp on b.id=bcp.booking_id left join booking_corporate_invoice bci on b.id=bci.booking_id where b.`car_model_id` = " . $data['model_id'] . " 
        and DATE(b.from_date) = '" . $pickup_date . "' and (b.`booking_status` = 'Not Picked' or b.`booking_status` = 'Picked') and (bipm.payment_method='Cash' or bcp.status='completed' or bci.payment_status='paid') and b.`from_location` in (select id from branch where city_id=" . $data['city_id'] . ")";


        // echo $query; exit();
        $models = DB::select($query);

        // custom::dump($models);

        // if ($models && $booking_availability) {
        if ($models) {
            $count = (int)$models[0]->count;
            $booking_per_day = (int)$booking_per_days;
            if ($count < $booking_per_day) {
                $avail = true;
            } else {
                $avail = false;
            }
        } else {
            $avail = true;
        }


        return $avail;
    }

    public static function sendCostumSms($phone_no, $sms_text)
    {

        $api = custom::api_settings();

        $client = new Client();
        try {
            $result = $client->Messages->Send($phone_no, $sms_text, $api->unifonic_sender_id);

            if (is_object($result)) {

                if (isset($result->MessageID)) {
                    echo 1;
                } else {
                    //if there would be any error of sms it will fall in this false
                    echo 0;
                }
            } else {
                echo 0;
            }

        } catch (Exception $e) {
            // do nothing
            echo 0;
        }
    }

    public static function changeUrlWithEnAr($segments, $returnToHome = false)
    {
        $urlString = implode("/", $segments);
        $lagUrlStr = ltrim($urlString, 'en-/');
        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
            $query_str = "?" . $_SERVER['QUERY_STRING'];
            $url = $lagUrlStr . $query_str;
        } else {
            $url = $lagUrlStr;
        }
        if ($returnToHome) {
            return '';
        }
        return $url;
    }

    public static function generate_token($data, $sts_secret_key = '')
    {

        if ($sts_secret_key != '') {
            $SECRET_KEY = $sts_secret_key;
        } else {
            $apiSettings = custom::api_settings();
            $SECRET_KEY = $apiSettings->sts_secret_key_web;
        }

        $responseParameters = array();
        //$SECRET_KEY = "OWNkYWNhMGVmNDM3YWE1Mjc1OTc0OGE4";
        foreach ($data as $key => $paramName) {
            $responseParameters[$key] = $data[$key];
        }
        //order parameters by key using ksort
        ksort($responseParameters);
        //var_dump($responseParameters);
        $orderedString = $SECRET_KEY;
        foreach ($responseParameters as $k => $param) {
            $orderedString .= $param;
        }
        // Generate SecureHash with SHA256
        $secureHash = hash('sha256', $orderedString, false);
        return $secureHash;
    }

    public static function getCreditCardType($cardNumber)
    {
        // Brands regex
        $brands = array(
            'visa' => '/^4\d{12}(\d{3})?$/',
            'mastercard' => '/^(5[1-5]\d{4}|677189)\d{10}$/',
            'diners' => '/^3(0[0-5]|[68]\d)\d{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'elo' => '/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
            'amex' => '/^3[47]\d{13}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'aura' => '/^(5078\d{2})(\d{2})(\d{11})$/',
            'hipercard' => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
            'maestro' => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
        );

        // Run test
        $brand = 'undefined';
        foreach ($brands as $_brand => $regex) {
            if (preg_match($regex, $cardNumber)) {
                $brand = $_brand;
                break;
            }
        }
        return $brand;
    }


    public static function fireBaseShortLink($link)
    {
        return $link; // just did it for now temporarily because of firebase link shortner api used below giving error.
        //Firebase project @ project@astutesol.com
        $webApiKey = "AIzaSyCxMizRzbSeGCTUFw_iPtxew63OWuPjb9I";

        $link_parameters = array(

            "dynamicLinkInfo" => array(
                "dynamicLinkDomain" => "keycar.page.link",
                "link" => $link,
            ),
            "suffix" => array(
                "option" => "SHORT"
            )
        );

        $ch = curl_init("https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=" . $webApiKey . "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($link_parameters));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($link_parameters)))
        );

        $output = curl_exec($ch);
        $get_outout = json_decode($output);
        // echo json_encode($link_parameters);
        // echo "<br>";
        self::dump($get_outout);
        curl_close($ch);
        return $get_outout->shortLink;
    }

    public static function is_mobile()
    {
        $agent = new Agent();
        if ($agent->isMobile() || $agent->isTablet()) {
            $is_full_version = true;
        } else {
            $is_full_version = false;
        }

        if (Session::get('full_version') != '' && Session::get('full_version') == 1) {
            $is_full_version = false;
        } else {
            $is_full_version;
        }
        return $is_full_version;
    }

    public static function getFromToCityName($city_id, $lang)
    {
        $page = new \App\Models\Front\Page();
        $city_info = $page->getSingle('city', array('id' => $city_id));
        $city_name = $lang == 'eng' ? $city_info->eng_title : $city_info->arb_title;
        return $city_name;
    }

    public static function getDateFormat($date, $lang)
    {

        $day = date('d', strtotime($date));
        $month = date('F', strtotime($date));
        $month_ar = custom::month_arabic($month);
        $year = date('Y', strtotime($date));
        $date_format = $lang == 'eng' ? date('d M Y', strtotime($date)) : $day . ' ' . $month_ar . ' ' . $year;
        return $date_format;

    }

    public static function make_folder_empty($dir)
    {
        $files = glob($dir . '*'); //get all file names
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    }

    public static function get_branch_name($id, $lang)
    {
        $page = new \App\Models\Front\Page();
        $br_name = '';
        $br_info = $page->getSingle('branch', array('id' => $id));
        if (isset($br_info) && !empty($br_info)) {
            $br_name = ($lang == 'eng' ? $br_info->eng_title : $br_info->arb_title);
        }
        return $br_name;
    }

    public static function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int)$num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int)(($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int)($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int)($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int)($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

    public static function get_invoice_amount($invoice_id)
    {
        $page = new \App\Models\Front\Page();
        $contracts = array();
        $amount = 0;
        $paid = 0;
        $due_balance = 0;
        $get_contracts = $page->getMultipleRows('corporate_invoices_contract', array('invoice_id' => $invoice_id));
        foreach ($get_contracts as $contract) {
            $amount += $contract->bills;
            $paid += $contract->payments;
            $due_balance += $contract->balance;
        }
        $contracts['amount'] = $amount;
        $contracts['paid'] = $paid;
        $contracts['due_balance'] = $due_balance;
        return $contracts;
    }

    public static function get_lease_invoice_amount($invoice_id)
    {
        $page = new \App\Models\Front\Page();
        $contracts = array();
        $amount = 0;
        $paid = 0;
        $due_balance = 0;
        $get_contracts = $page->getMultipleRows('corporate_lease_transactions', array('invoice_id' => $invoice_id));
        foreach ($get_contracts as $contract) {
            $amount += $contract->bills;
        }
        $contracts['amount'] = $amount;
        return $contracts;
    }

    public static function convertNumberToArbWord($number)
    {
        if (($number < 0) || ($number > 999999999999)) {
            throw new Exception("العدد خارج النطاق");
        }
        $return = "";
        //convert number into array of (string) number each case
        // -------number: 121210002876-----------//
        // 	0		1		2		3  //
        //'121'	  '210'	  '002'	  '876'
        $english_format_number = number_format($number);
        $array_number = explode(',', $english_format_number);
        //convert each number(hundred) to arabic
        for ($i = 0; $i < count($array_number); $i++) {
            $place = count($array_number) - $i;
            $return .= custom::convert($array_number[$i], $place);
            if (isset($array_number[($i + 1)]) && $array_number[($i + 1)] > 0) $return .= ' و';
        }
        return $return;
    }

    public static function convertDateFormat($date)
    {
        $date = str_replace(['=','"'], ['',''], $date);

        $expl_date = explode('/', $date);
        $day = $expl_date[0];
        $month = $expl_date[1];
        $year = $expl_date[2];
        return $month . '/' . $day . '/' . $year;
    }

    //private function
    public static function convert($number, $place)
    {
        // take in charge the sex of NUMBERED
        $sex = 'male';
        //the number word in arabic for masculine and feminine
        $words = array(
            'male' => array(
                '0' => '', '1' => 'واحد', '2' => 'اثنان', '3' => 'ثلاثة', '4' => 'أربعة', '5' => 'خمسة',
                '6' => 'ستة', '7' => 'سبعة', '8' => 'ثمانية', '9' => 'تسعة', '10' => 'عشرة',
                '11' => 'أحد عشر', '12' => 'اثنا عشر', '13' => 'ثلاثة عشر', '14' => 'أربعة عشر', '15' => 'خمسة عشر',
                '16' => 'ستة عشر', '17' => 'سبعة عشر', '18' => 'ثمانية عشر', '19' => 'تسعة عشر', '20' => 'عشرون',
                '30' => 'ثلاثون', '40' => 'أربعون', '50' => 'خمسون', '60' => 'ستون', '70' => 'سبعون',
                '80' => 'ثمانون', '90' => 'تسعون', '100' => 'مئة', '200' => 'مئتان', '300' => 'ثلاثمئة', '400' => 'أربعمئة', '500' => 'خمسمئة',
                '600' => 'ستمئة', '700' => 'سبعمئة', '800' => 'ثمانمئة', '900' => 'تسعمئة'
            ),
            'female' => array(
                '0' => '', '1' => 'واحدة', '2' => 'اثنتان', '3' => 'ثلاث', '4' => 'أربع', '5' => 'خمس',
                '6' => 'ست', '7' => 'سبع', '8' => 'ثمان', '9' => 'تسع', '10' => 'عشر',
                '11' => 'إحدى عشرة', '12' => 'ثنتا عشرة', '13' => 'ثلاث عشرة', '14' => 'أربع عشرة', '15' => 'خمس عشرة',
                '16' => 'ست عشرة', '17' => 'سبع عشرة', '18' => 'ثمان عشرة', '19' => 'تسع عشرة', '20' => 'عشرون',
                '30' => 'ثلاثون', '40' => 'أربعون', '50' => 'خمسون', '60' => 'ستون', '70' => 'سبعون',
                '80' => 'ثمانون', '90' => 'تسعون', '100' => 'مئة', '200' => 'مئتان', '300' => 'ثلاثمئة', '400' => 'أربعمئة', '500' => 'خمسمئة',
                '600' => 'ستمئة', '700' => 'سبعمئة', '800' => 'ثمانمئة', '900' => 'تسعمئة'
            )
        );
        //take in charge the different way of writing the thousands and millions ...
        $mil = array(
            '2' => array('1' => 'ألف', '2' => 'ألفان', '3' => 'آلاف'),
            '3' => array('1' => 'مليون', '2' => 'مليونان', '3' => 'ملايين'),
            '4' => array('1' => 'مليار', '2' => 'ملياران', '3' => 'مليارات')
        );

        $mf = array('1' => $sex, '2' => 'male', '3' => 'male', '4' => 'male');
        $number_length = strlen((string)$number);
        if ($number == 0) return '';
        else if ($number[0] == 0) {
            if ($number[1] == 0) $number = (int)substr($number, -1);
            else $number = (int)substr($number, -2);
        }
        switch ($number_length) {
            case '1':
                {
                    switch ($place) {
                        case '1':
                            {
                                $return = $words[$mf[$place]][$number];
                            }
                            break;
                        case '2':
                            {

                                if ($number == 1) $return = 'ألف';
                                else if ($number == 2) $return = 'ألفان';
                                else {
                                    $return = $words[$mf[$place]][$number] . ' آلاف';
                                }
                            }
                            break;
                        case '3':
                            {
                                if ($number == 1) $return = 'مليون';
                                else if ($number == 2) $return = 'مليونان';
                                else $return = $words[$mf[$place]][$number] . ' ملايين';
                            }
                            break;
                        case '4':
                            {
                                if ($number == 1) $return = 'مليار';
                                else if ($number == 2) $return = 'ملياران';
                                else $return = $words[$mf[$place]][$number] . ' مليارات';
                            }
                            break;
                    }
                }
                break;
            case '2':
                {
                    if (isset($words[$mf[$place]][$number])) $return = $words[$mf[$place]][$number];
                    else {
                        $twoy = $number[0] * 10;
                        $ony = $number[1];
                        $return = $words[$mf[$place]][$ony] . ' و' . $words[$mf[$place]][$twoy];
                    }
                    switch ($place) {
                        case '2':
                            {
                                $return .= ' ألف';
                            }
                            break;
                        case '3':
                            {
                                $return .= ' مليون';
                            }
                            break;
                        case '4':
                            {
                                $return .= ' مليار';
                            }
                            break;
                    }
                }
                break;
            case '3':
                {
                    if (isset($words[$mf[$place]][$number])) {
                        $return = $words[$mf[$place]][$number];
                        if ($number == 200) $return = 'مئتا';
                        switch ($place) {
                            case '2':
                                {
                                    $return .= ' ألف';
                                }
                                break;
                            case '3':
                                {
                                    $return .= ' مليون';
                                }
                                break;
                            case '4':
                                {
                                    $return .= ' مليار';
                                }
                                break;
                        }
                        return $return;
                    } else {
                        $threey = $number[0] * 100;
                        if (isset($words[$mf[$place]][$threey])) {
                            $return = $words[$mf[$place]][$threey];
                        }
                        $twoyony = $number[1] * 10 + $number[2];
                        if ($twoyony == 2) {
                            switch ($place) {
                                case '1':
                                    $twoyony = $words[$mf[$place]]['2'];
                                    break;
                                case '2':
                                    $twoyony = 'ألفان';
                                    break;
                                case '3':
                                    $twoyony = 'مليونان';
                                    break;
                                case '4':
                                    $twoyony = 'ملياران';
                                    break;
                            }
                            if ($threey != 0) {
                                $twoyony = 'و' . $twoyony;
                            }
                            $return = $return . ' ' . $twoyony;
                        } else if ($twoyony == 1) {
                            switch ($place) {
                                case '1':
                                    $twoyony = $words[$mf[$place]]['1'];
                                    break;
                                case '2':
                                    $twoyony = 'ألف';
                                    break;
                                case '3':
                                    $twoyony = 'مليون';
                                    break;
                                case '4':
                                    $twoyony = 'مليار';
                                    break;
                            }
                            if ($threey != 0) {
                                $twoyony = 'و' . $twoyony;
                            }
                            $return = $return . ' ' . $twoyony;
                        } else {
                            if (isset($words[$mf[$place]][$twoyony])) $twoyony = $words[$mf[$place]][$twoyony];
                            else {
                                $twoy = $number[1] * 10;
                                $ony = $number[2];
                                $twoyony = $words[$mf[$place]][$ony] . ' و' . $words[$mf[$place]][$twoy];
                            }
                            if ($twoyony != '' && $threey != 0) $return = $return . ' و' . $twoyony;
                            switch ($place) {
                                case '2':
                                    {
                                        $return .= ' ألف';
                                    }
                                    break;
                                case '3':
                                    {
                                        $return .= ' مليون';
                                    }
                                    break;
                                case '4':
                                    {
                                        $return .= ' مليار';
                                    }
                                    break;
                            }
                        }
                    }
                }
                break;
        }
        return $return;
    }

    public static function dump($data, $exit = true)
    {
        echo '<pre>';
        print_r($data);
        if ($exit) {
            exit();
        }
    }

    public static function validate_email($email)
    {
        $email_characters_are_valid = true;
        $allowed_characters = array(
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
            "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d",
            "e", "f", "g", "h", "i", "j", "k", "l", "m", "n",
            "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
            "y", "z", "1", "2", "3", "4", "5", "6", "7", "8",
            "9", "0", ".", "_", "-", "+", "@"
        );
        $email_characters = str_split($email);

        foreach ($email_characters as $character) {
            if (!in_array($character, $allowed_characters)) {
                $email_characters_are_valid = false;
                break;
            }
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && $email_characters_are_valid) {
            return true;
        } else {
            return false;
        }
    }

    public static function validate_mobile_no($mobile_no)
    {
        $country_code = substr(str_replace('+', '', $mobile_no), 0, 3);
        if ($country_code == '966') {
            $match = preg_match("/^[0-9]{12}$/", $mobile_no);
            if ($match == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $match = preg_match("/^[0-9]{7,}$/", $mobile_no);
            if ($match == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function parking_fee_for_branch($branch_id)
    {
        $page = new \App\Models\Front\Page();
        $branch_detail = $page->getSingle('branch', array('id' => $branch_id));
        if ($branch_detail && $branch_detail->parking_fee > 0) { // && $branch_detail->is_delivery_branch == 'no' // for now we have added delivery charges for pickup and delivery branches
            return $branch_detail->parking_fee;
        } else {
            return 0;
        }
    }

    public static function tamm_charges_for_branch($branch_id)
    {
        $page = new \App\Models\Front\Page();
        $branch_detail = $page->getSingle('branch', array('id' => $branch_id));
        $site_settings = self::site_settings();
        if ($branch_detail && $branch_detail->has_tamm_charges == 'Yes' && $site_settings->site_tamm_charges > 0) {
            return $site_settings->site_tamm_charges;
        } else {
            return 0;
        }
    }

    public static function debug($array)
    {
        echo '<pre>';
        print_r($array);
        exit();
    }

    public static function enable_query_log()
    {
        app('db')->enableQueryLog();
        return true;
    }

    public static function get_query_log($show_all = false)
    {
        $q = app('db')->getQueryLog();
        foreach ($q as $key => $value) {
            $q[$key]["parsed_query"] = vsprintf(str_replace('?', "'%s'", $value['query']), $value['bindings']);
        }
        if ($show_all):
            dump_($q);
        else:
            echo $q[count($q) - 1]['parsed_query'];
            exit();
        endif;
    }

    public static function is_promotion_valid_for_pickup_day($promotion_detail, $pickup_date)
    {
        $coupon_is_allowed_for_this_day = 1;

        if ($promotion_detail) {
            $pickup_day = date('l', strtotime($pickup_date));
            if ($pickup_day == 'Sunday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_sunday;
            } else if ($pickup_day == 'Monday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_monday;
            } else if ($pickup_day == 'Tuesday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_tuesday;
            } else if ($pickup_day == 'Wednesday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_wednesday;
            } else if ($pickup_day == 'Thursday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_thursday;
            } else if ($pickup_day == 'Friday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_friday;
            } else if ($pickup_day == 'Saturday') {
                $coupon_is_allowed_for_this_day = $promotion_detail->for_saturday;
            }
        }

        return $coupon_is_allowed_for_this_day == 1;

    }

    public static function is_coupon_usage_fine($coupon_code, $id_no, $lang = 'eng') {
        $page = new Page();
        $no_of_uses_per_coupon_is_ok = true;
        $no_of_uses_per_customer_id_is_ok = true;
        $promotion_offer_coupon = $page->getSingleRow('promotion_offer_coupon', ['code' => $coupon_code]);
        if ($promotion_offer_coupon) {
            $promotion_offer = $page->getSingleRow('promotion_offer', ['id' => $promotion_offer_coupon->promotion_offer_id]);

            if ($promotion_offer->no_of_uses_per_coupon > 0) {
                $query = DB::table('booking_payment as bp')
                    ->join('booking as b', 'bp.booking_id', 'b.id')
                    ->join('booking_individual_payment_method as bipm', 'b.id', 'bipm.booking_id')
                    ->leftjoin('booking_cc_payment as bcp', 'b.id', 'bcp.booking_id')
                    ->leftjoin('booking_sadad_payment as bsp', 'b.id', 'bsp.s_booking_id');

                $query->where('bp.promotion_offer_code_used', $coupon_code);
                $query->where(function ($query) {
                    $query->where('bipm.payment_method', 'Cash');
                    $query->orWhere('bipm.payment_method', 'Corporate Credit');
                    $query->orWhere('bcp.status', 'completed');
                    $query->orWhere('bsp.s_status', 'completed');
                });
                $usage_count_from_bookings = $query->count();
                if ($usage_count_from_bookings < $promotion_offer->no_of_uses_per_coupon) {
                    $no_of_uses_per_coupon_is_ok = true;
                } else {
                    $no_of_uses_per_coupon_is_ok = false;
                }
            }

            if ($promotion_offer->no_of_uses_per_customer_id > 0) {
                if ($id_no == '') {
                    return ['status' => false, 'message' => ($lang == 'eng' ? 'Please enter the ID number first.' : 'الرجاء إدخال رقم الهوية أولاً')];
                } else {
                    if ($promotion_offer->customer_type == 'Corporate') {
                        // did not allow to corporate customer for now, we can do it later if needed
                        $no_of_uses_per_customer_id_is_ok = false;
                    } else {
                        $coupon_usage_count_for_customer_id = 0;
                        $customer_detail = DB::table('individual_customer')->where('id_no', $id_no)->first();
                        if ($customer_detail) {
                            if ($customer_detail->uid > 0) {
                                $query = DB::table('booking_individual_user as biu')
                                    ->join('booking as b', 'biu.booking_id', 'b.id')
                                    ->join('booking_payment as bp', 'b.id', 'bp.booking_id')
                                    ->join('booking_individual_payment_method as bipm', 'b.id', 'bipm.booking_id')
                                    ->leftjoin('booking_cc_payment as bcp', 'b.id', 'bcp.booking_id')
                                    ->leftjoin('booking_sadad_payment as bsp', 'b.id', 'bsp.s_booking_id')
                                    ->where('biu.uid', $customer_detail->uid)
                                    ->where('bp.promotion_offer_code_used', $coupon_code);
                                $query->where(function ($query) {
                                    $query->where('bipm.payment_method', 'Cash');
                                    $query->orWhere('bipm.payment_method', 'Corporate Credit');
                                    $query->orWhere('bcp.status', 'completed');
                                    $query->orWhere('bsp.s_status', 'completed');
                                });
                                $coupon_usage_count_for_customer_id = $query->count();
                            } else {
                                $query = DB::table('booking_individual_guest as big')
                                    ->join('booking as b', 'big.booking_id', 'b.id')
                                    ->join('booking_payment as bp', 'b.id', 'bp.booking_id')
                                    ->join('booking_individual_payment_method as bipm', 'b.id', 'bipm.booking_id')
                                    ->leftjoin('booking_cc_payment as bcp', 'b.id', 'bcp.booking_id')
                                    ->leftjoin('booking_sadad_payment as bsp', 'b.id', 'bsp.s_booking_id')
                                    ->where('big.individual_customer_id', $customer_detail->id)
                                    ->where('bp.promotion_offer_code_used', $coupon_code);
                                $query->where(function ($query) {
                                    $query->where('bipm.payment_method', 'Cash');
                                    $query->orWhere('bipm.payment_method', 'Corporate Credit');
                                    $query->orWhere('bcp.status', 'completed');
                                    $query->orWhere('bsp.s_status', 'completed');
                                });
                                $coupon_usage_count_for_customer_id = $query->count();
                            }
                        }
                        if ($coupon_usage_count_for_customer_id < $promotion_offer->no_of_uses_per_customer_id) {
                            $no_of_uses_per_customer_id_is_ok = true;
                        } else {
                            $no_of_uses_per_customer_id_is_ok = false;
                        }
                    }
                }
            }

            if ($no_of_uses_per_coupon_is_ok && $no_of_uses_per_customer_id_is_ok) {
                return ['status' => true, 'message' => ''];
            } else {
                return ['status' => false, 'message' => ($lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح')];
            }
        }
        return ['status' => false, 'message' => ($lang == 'eng' ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح')];
    }

    public static function get_promotion_code_and_increase_usage($promotion_offer_id)
    {
        if ($promotion_offer_id > 0) // if there is some promotion used
        {
            $setting = new Settings();
            $where['promotion_offer_id'] = $promotion_offer_id;
            $data = $setting->get_single_record('promotion_offer_coupon', $where);
            if ($data) // if we found its entry in "promotion_offer_coupon" table than it means its of coupon code type
            {
                return $data->code;
            } else { // else returning empty
                return "";
            }
        } else { // else returning empty
            return "";
        }
    }

    public static function baseurl($path = '')
    {
        $base_url = config('app.url');
        if ($path == '/') $path = '';
        if (strpos($base_url, 'localhost') !== false) {
            $explode_by = 'localhost/key-rental';
        } elseif (strpos($base_url, 'kra') !== false) {
            $explode_by = 'kra.';
        } else {
            $explode_by = 'key.';
        }
        $url = rtrim($base_url, '/') . '/' . ltrim($path, '/');
        $url_exp = explode($explode_by, $url);
        $url_exp[1] = str_replace('//', '/', $url_exp[1]);
        return rtrim($url_exp[0] . $explode_by . $url_exp[1], '/');
    }

    public static function get_base64_path($img_path)
    {
        $base_url = config('app.url');
        #todo we would need to fix this once we go live
        if (strpos($base_url, 'localhost') !== false || strpos($base_url, 'key.sa') !== false) {
            // because on key.sa linux old hosting it works like this
            $img_url = $base_url . $img_path;
        } elseif (strpos($base_url, 'key.ed.sa') !== false) {
            $img_url = $_SERVER['DOCUMENT_ROOT'] . '/dev' . $img_path;
        } elseif (strpos($base_url, 'key.sa') !== false) {
            $img_url = $_SERVER['DOCUMENT_ROOT'] . $img_path;
        } elseif (strpos($base_url, 'kra.ced.sa') !== false) {
            $img_url = $_SERVER['DOCUMENT_ROOT'] . $img_path;
        } else {
            $img_url = $base_url . $img_path;
        }
        // echo $img_url;die();
        $base_64_str = base64_encode(@file_get_contents($img_url));
        return "data:image/jpeg;base64," . $base_64_str;
    }

    public static function is_driver_available_for_this_branch($from_date, $branch_id, $no_of_drivers_per_day_for_branch = false)
    {
        if (!$no_of_drivers_per_day_for_branch) {
            $page = new Page();
            $branch_info = $page->getSingleRow('branch', array('id' => $branch_id));
            $no_of_drivers_per_day_for_branch = $branch_info->no_of_drivers_per_day;
        }
        $query = Booking::join('booking_payment', 'booking.id', '=', 'booking_payment.booking_id')
            ->join('booking_individual_payment_method', 'booking.id', '=', 'booking_individual_payment_method.booking_id')
            ->join('booking_cc_payment', 'booking.id', '=', 'booking_cc_payment.booking_id', 'left')
            ->join('booking_corporate_invoice', 'booking.id', '=', 'booking_corporate_invoice.booking_id', 'left');
        $query->whereDate('booking.from_date', '=', date('Y-m-d', strtotime($from_date)));
        $query->where('booking.from_location', '=', $branch_id);
        $query->where('booking_payment.extra_driver_price', '>', 0);
        $query->where(function ($query) {
            $query->where('booking.booking_status', 'Not Picked');
            $query->orWhere('booking.booking_status', 'Picked');
        });
        $query->where(function ($query) {
            $query->where('booking_individual_payment_method.payment_method', 'Cash');
            $query->orWhere('booking_cc_payment.status', 'completed');
            $query->orWhere('booking_corporate_invoice.payment_status', 'paid');
            $query->orWhere('booking_individual_payment_method.payment_method', 'Corporate Credit');
        });
        // self::enable_query_log();
        $bookings_count = $query->count();
        // self::get_query_log();
        // echo $bookings_count;die();
        // echo $no_of_drivers_per_day_for_branch;die();
        if ($bookings_count < $no_of_drivers_per_day_for_branch) {
            return true;
        } else {
            return false;
        }
    }

    public static function hp_generate_checkout_id_api($booking_id, $is_mada = 0, $extend = false, $amount = false, $number_of_days = false)
    {
        $page = new \App\Models\Front\Page();
        $api_setting = custom::api_settings();
        $booking = DB::table('booking')->where('id', $booking_id)->first();
        $booking_payment = DB::table('booking_payment')->where('booking_id', $booking_id)->first();
        if ($booking->type == 'corporate_customer') {
            $details = $page->getSingle("booking_corporate_customer", array('booking_id' => $booking->id));
            $user_details = DB::table('corporate_customer')->whereRaw('FIND_IN_SET('.$details->uid.', uid)')->first();
            $customer_email = $user_details->primary_email;
            $customer_name = $user_details->primary_name;
            $customer_surname = $user_details->company_name_en;
            $address_street = $user_details->address_street != '' ? $user_details->address_street : '';
            $address_city = $user_details->address_city != '' ? $user_details->address_city : '';
            $address_state = $user_details->address_state != '' ? $user_details->address_state : '';
            $address_country = $user_details->address_country != '' ? $user_details->address_country : '';
            $address_post_code = $user_details->address_post_code != '' ? $user_details->address_post_code : '';
        } elseif ($booking->type == 'individual_customer') {
            $details = $page->getSingle("booking_individual_user", array('booking_id' => $booking->id));
            $user_details = $page->getSingle("individual_customer", array('uid' => $details->uid));
            $customer_email = $user_details->email;
            $customer_name = $user_details->first_name;
            $customer_surname = $user_details->last_name;
            $address_street = $user_details->address_street != '' ? $user_details->address_street : '';
            $address_city = $user_details->address_city != '' ? $user_details->address_city : '';
            $address_state = $user_details->address_state != '' ? $user_details->address_state : '';
            $address_country = $user_details->address_country != '' ? $user_details->address_country : '';
            $address_post_code = $user_details->address_post_code != '' ? $user_details->address_post_code : '';
        } elseif ($booking->type == 'guest') {
            $details = $page->getSingle("booking_individual_guest", array('booking_id' => $booking->id));
            $user_details = $page->getSingle("individual_customer", array('id' => $details->individual_customer_id));
            $customer_email = $user_details->email;
            $customer_name = $user_details->first_name;
            $customer_surname = $user_details->last_name;
            $address_street = $user_details->address_street != '' ? $user_details->address_street : '';
            $address_city = $user_details->address_city != '' ? $user_details->address_city : '';
            $address_state = $user_details->address_state != '' ? $user_details->address_state : '';
            $address_country = $user_details->address_country != '' ? $user_details->address_country : '';
            $address_post_code = $user_details->address_post_code != '' ? $user_details->address_post_code : '';
        }

        $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . "/v1/checkouts";
        $data['entityId'] = self::entity_id($is_mada, $api_setting);
        $data['amount'] = ($amount ? $amount : $booking_payment->total_sum);
        $data['currency'] = 'SAR';
        $data['paymentType'] = 'DB';
        $data['notificationUrl'] = ($extend ? custom::baseurl('hp_ipn_for_add_payment') : custom::baseurl('hp_ipn'));

        // below keys are asked by Kholoud to be sent for live site otherwise it will give error
        // Kholoud again asked to not send testMode in case of STC_PAY and then again asked to not send it in case of AMEX as well on 13-04-2021
        if ($api_setting->hyper_pay_test_mode == 'EXTERNAL' && $is_mada != 3 && $is_mada != 4) {
            $data['testMode'] = $api_setting->hyper_pay_test_mode;
        }
        $data['merchantTransactionId'] = $booking->reservation_code . ($extend ?: '');
        $data['customer.email'] = $customer_email;
        $data['customer.givenName'] = $customer_name;
        $data['customer.surname'] = $customer_surname;
        $data['billing.street1'] = isset($address_street) && $address_street != '' ? $address_street : '';
        $data['billing.city'] = isset($address_city) && $address_city != '' ? $address_city : '';
        $data['billing.state'] = isset($address_state) && $address_state != '' ? $address_state : '';
        $data['billing.country'] = isset($address_country) && $address_country != '' ? $address_country : '';
        $data['billing.postcode'] = isset($address_post_code) && $address_post_code != '' ? $address_post_code : '';

        if ($extend) {
            $data['extended_days'] = ($number_of_days ?: '');
        }

        // saving request
        if ($extend) {
            custom::log_hyper_pay_request_and_response_for_added_payments(($booking_id . $extend), 'generate_checkout_id_request', $data);
        } else {
            custom::log_hyper_pay_request_and_response_for_booking($booking_id, 'generate_checkout_id_request', $data);
        }

        unset($data['extended_days']);

        $data_qry_str = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_qry_str);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $hp_response = curl_exec($ch);
        curl_close($ch);
        $hp_response = json_decode($hp_response, true);

        // saving response
        if ($extend) {
            custom::log_hyper_pay_request_and_response_for_added_payments(($booking_id . $extend), 'generate_checkout_id_response', $hp_response);
        } else {
            custom::log_hyper_pay_request_and_response_for_booking($booking_id, 'generate_checkout_id_response', $hp_response);
        }

        return $hp_response;
    }

    public static function hp_check_payment_status_api($resource_path, $is_mada = 0, $booking_id = false, $extend = false)
    {
        $api_setting = custom::api_settings();
        $entity_id = self::entity_id($is_mada, $api_setting);

        $url = rtrim($api_setting->hyper_pay_endpoint_url, '/') . $resource_path;
        $url .= "?entityId=" . $entity_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . $api_setting->hyper_pay_bearer_token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $api_setting->hyper_pay_test_mode == 'EXTERNAL' ? false : true);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $hp_response = curl_exec($ch);
        curl_close($ch);
        $hp_response = json_decode($hp_response, true);

        if ($booking_id) {

            $hp_request['resource_path'] = $resource_path;
            $hp_request['entity_id'] = $entity_id;
            if ($extend) {
                custom::log_hyper_pay_request_and_response_for_added_payments(($booking_id . $extend), 'check_payment_status_request', $hp_request);
                custom::log_hyper_pay_request_and_response_for_added_payments(($booking_id . $extend), 'check_payment_status_response', $hp_response);
            } else {
                custom::log_hyper_pay_request_and_response_for_booking($booking_id, 'check_payment_status_request', $hp_request);
                custom::log_hyper_pay_request_and_response_for_booking($booking_id, 'check_payment_status_response', $hp_response);
            }

        }

        return $hp_response;
    }

    public static function entity_id($is_mada, $api_settings, $cc_brands = false)
    {
        if ($is_mada == 0) { // visa, master
            return ($cc_brands ? 'VISA MASTER' : $api_settings->hyper_pay_entity_id_master_visa);
        } elseif ($is_mada == 1) { // mada
            return ($cc_brands ? 'MADA' : $api_settings->hyper_pay_entity_id_mada);
        } elseif ($is_mada == 2) { // apple pay
            return ($cc_brands ? 'APPLEPAY' : $api_settings->hyper_pay_entity_id_apple_pay);
        } elseif ($is_mada == 3) { // stc pay
            return ($cc_brands ? 'STC_PAY' : $api_settings->hyper_pay_entity_id_master_visa);
        } elseif ($is_mada == 4) { // amex
            return ($cc_brands ? 'AMEX' : $api_settings->hyper_pay_entity_id_master_visa);
        }
    }

    public static function cancellation_reasons($id = false, $lang = 'eng')
    {
        $page = new \App\Models\Front\Page();
        if ($id) {
            $cancellation_reason = $page->getSingle('setting_cancellation_reasons', array('id' => $id));
            return ($lang == 'eng' ? $cancellation_reason->cancellation_reason_en : $cancellation_reason->cancellation_reason_en);
        } else {
            $cancellation_reasons = $page->getMultipleRows('setting_cancellation_reasons', array('is_active' => 1));
            return $cancellation_reasons;
        }
    }

    public static function get_loyalty_program_used_for_booking($id = false)
    {
        if ($id > 0)
        {
            $page = new \App\Models\Front\Page();
            $row = $page->getSingle('setting_loyalty_programs', array('id' => $id));
            return $row->oracle_ref_no;
        } else {
            return '';
        }
    }

    public static function is_ios_device() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
            return true;
        } else {
            return false;
        }
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

        if( $iPod || $iPhone || $iPad || $webOS){
            return true;
        }else{
            return false;
        }
    }

    public static function is_booking_editable($booking_id, $for_mobile = false)
    {
        $page = new Page();
        $site_settings = self::site_settings();
        $booking_detail = $page->getSingleRow('booking', array('id' => $booking_id));
        $booking_can_be_edited = $for_mobile ? 0 : false;
        if ($booking_detail->booking_status == 'Not Picked' && $site_settings->can_edit_booking == 'yes') {

            $booking_edit_before_pickup_hours_in_mins = $site_settings->booking_edit_before_pickup_hours * 60;

            $current_datetime = date('Y-m-d H:i:s');
            $booking_pickup_datetime = $booking_detail->from_date;
            // $booking_pickup_datetime = '2021-03-03 00:00:00';

            $current_datetime_for_comp = new Carbon($current_datetime);
            $booking_pickup_datetime_for_comp = new Carbon($booking_pickup_datetime);

            $difference_between_current_time_and_pickup_in_mins = $booking_pickup_datetime_for_comp->diffInMinutes($current_datetime_for_comp);

            // echo $difference_between_current_time_and_pickup_in_mins;die;

            if ((strtotime($booking_pickup_datetime) > strtotime($current_datetime)) && ($difference_between_current_time_and_pickup_in_mins > $booking_edit_before_pickup_hours_in_mins)) {
                $booking_can_be_edited = $for_mobile ? 1 : true;
            }
        }

        // var_dump($booking_can_be_edited);die;

        return $booking_can_be_edited;
    }

    public static function notification_sent_to($notification_id) {
        $total_tokens = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->where('push_notifications_log_id', $notification_id)->count();
        return $total_tokens;
    }

    public static function total_active_device_tokens() {
        $total_tokens = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->count();
        return $total_tokens;
    }

    public static function generate_string($strength = 16) {
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, strlen($permitted_chars) - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public static function get_car_models_against_promotion($promotion_offer_id) {
        $car_models_arr = [];
        $car_models = DB::table('promotion_offer_car_model')->where('promotion_offer_id', $promotion_offer_id)->get();
        foreach ($car_models as $car_model) {
            $car_models_arr[] = $car_model->car_model_id;
        }
        return $car_models_arr;
    }

    public static function is_homepage() {
        $segments = Request::segments();
        // $segments = request()->segments();
        $home_page = false;
        $segments = request()->segments();
        if ((empty($segments) || end($segments) == 'home' || ($segments[0] == 'en' && !isset($segments[1])) || ($segments[0] == 'en' && end($segments) == 'home')) && !isset($_GET['hourly']) && !isset($_GET['pickup']) && !isset($_GET['delivery']) && !isset($_GET['monthly']) && !isset($_GET['weekly'])) {
            $home_page =  true;
        }
        return $home_page;
    }

    public static function show_smart_banner() {
        $segments = Request::segments();
        $show_smart_banner = true;
        $segments = request()->segments();
        if (end($segments) == 'mada-payment') {
            $show_smart_banner =  false;
        }
        return $show_smart_banner;
    }

    public static function is_promo_discount_on_total($promotion_offer_id)
    {
        $page = new Page();
        if ($promotion_offer_id > 0) {
            $promotion_offer = DB::table('promotion_offer')->where('id', $promotion_offer_id)->first();
            if ($promotion_offer && ($promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Percentage Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Subscription - Fixed Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon For All Booking Types')) {
                return 1;
            }
        }
        return 0;
    }

    public static function is_promo_discount_on_total_without_loyalty($promotion_offer_id)
    {
        $page = new Page();
        if ($promotion_offer_id > 0) {
            $promotion_offer = DB::table('promotion_offer')->where('id', $promotion_offer_id)->first();
            /*if ($promotion_offer && ($promotion_offer->type == 'Fixed Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Percentage Discount on Booking Total Using Coupon' || $promotion_offer->type == 'Subscription - Fixed Discount on Booking Total Using Coupon') && $promotion_offer->apply_discount_with_loyalty_discount == 0) {
                return true;
            }*/
            if ($promotion_offer && ($promotion_offer->type == 'Subscription - Fixed Discount on Booking Total Using Coupon') && $promotion_offer->apply_discount_with_loyalty_discount == 0) {
                return true;
            }
        }
        return false;
    }

    public static function site_phone() {
        $site_settings = self::site_settings();
        return ($site_settings->site_phone != '' ? $site_settings->site_phone : '920005211');
    }

    public static function send_booking_cancellation_otp_via_whatsapp($mobile_number, $otp, $lang = 'eng') {

        if ($lang == 'eng') {
            $url = "http://api2.keyrac.sa:8080/WhatsAppService/TemplateMessageP1?template_name=website_otp_en&template_lang=en&customer_mobile=$mobile_number&placeholder1=$otp";
        } else {
            $url = "http://api2.keyrac.sa:8080/WhatsAppService/TemplateMessageP1?template_name=website_otp&template_lang=ar&customer_mobile=$mobile_number&placeholder1=$otp";
        }

        /**Calling OASIS function to send Whatsapp message**/
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            // echo 'Curl Error: ' . curl_error($ch);die;
        }
        curl_close($ch);
        /*****************************************/

        return $response;
    }

    public static function clean_request_data($request) {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);

        $response = [];
        foreach ($request as $key => $value) {
            $response[$key] = str_replace(['<', '>'], ['', ''], $value);
            $response[$key] = $purifier->purify($response[$key]);
        }
        return $response;
    }

    public static function log_hyper_pay_request_and_response_for_booking($booking_id, $type, $data) {

        $current_date_time = date('Y-m-d H:i:s');

        // adding datatime to request/response
        $data['action_performed_at'] = $current_date_time;
        $data = json_encode($data);

        $page = new PageModel();
        $booking_hp_log = $page->getSingle('booking_hyper_pay_log', ['booking_id' => $booking_id]);
        if ($booking_hp_log) {
            if (!$booking_hp_log->$type) { // did it to not allow it to run 2 times
                $hp_log_data[$type] = $data;
                $page->updateData('booking_hyper_pay_log', $hp_log_data, ['booking_id' => $booking_id]);
            }
        } else {
            $booking = $page->getSingle('booking', ['id' => $booking_id]);
            $hp_log_data['booking_id'] = $booking->id;
            $hp_log_data['type'] = $booking->booking_source;
            $hp_log_data[$type] = $data;
            $hp_log_data['created_at'] = $current_date_time;
            $page->saveData('booking_hyper_pay_log', $hp_log_data);
        }
    }

    public static function log_hyper_pay_request_and_response_for_added_payments($booking_id, $type, $data) {

        $current_date_time = date('Y-m-d H:i:s');

        // adding datatime to request/response
        $data['action_performed_at'] = $current_date_time;
        $data = json_encode($data);

        $page = new PageModel();
        $booking_hp_log = $page->getSingle('booking_added_payments_hyper_pay_log', ['booking_id' => $booking_id]);
        if ($booking_hp_log) {
            $hp_log_data[$type] = $data;
            $page->updateData('booking_added_payments_hyper_pay_log', $hp_log_data, ['booking_id' => $booking_id]);
        } else {
            $booking_id_exploded = explode('E', $booking_id);
            $booking = $page->getSingle('booking', ['id' => $booking_id_exploded[0]]);
            $hp_log_data['booking_id'] = $booking_id;
            $hp_log_data['type'] = $booking->booking_source;
            $hp_log_data[$type] = $data;
            $hp_log_data['created_at'] = $current_date_time;
            $page->saveData('booking_added_payments_hyper_pay_log', $hp_log_data);
        }
    }

    public static function call_whatsapp_message_service_after_booking($mobile_number, $booking_number, $booking_url, $lang = 'eng') {

        if ($lang == 'eng') {
            $url = "http://api2.keyrac.sa:8080/WhatsAppService/TemplateMessageP2?template_name=online_res_en&template_lang=en&customer_mobile=$mobile_number&placeholder1=$booking_number&placeholder2=$booking_url";
        } else {
            $url = "http://api2.keyrac.sa:8080/WhatsAppService/TemplateMessageP2?template_name=online_res&template_lang=ar&customer_mobile=$mobile_number&placeholder1=$booking_number&placeholder2=$booking_url";
        }

        /**Calling OASIS function to send Whatsapp message**/
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            // echo 'Curl Error: ' . curl_error($ch);die;
        }
        curl_close($ch);
        /*****************************************/

        return $response;
    }

    public static function encrypt($str) {
        $encrypted = Crypt::encryptString($str);
        return $encrypted;
    }

    public static function decrypt($str) {
        $decrypted = Crypt::decryptString($str);
        return $decrypted;
    }

    public static function getAirportRegions($lang = "eng") {
        // getAirportRegions
        $page = new PageModel();
        $airport_regions = $page->getAirportRegions();
        $airport_regionArr = array();
        foreach ($airport_regions as $key => $region) {
            if ($lang == "eng") {
                $city = $region->cit . '|' . $region->c_eng_title;
            } else {
                $city = $region->cit . '|' . $region->c_arb_title;
            }
            $airport_regionArr[$city][] = $region;
        }
        // echo '<pre>';print_r($airport_regionArr);exit();
        $data['airport_pickup_regions'] = $airport_regionArr;
        $data['airport_dropoff_regions'] = $airport_regionArr;

        return $data;
    }

    public static function show_add_payment_option($booking_id, $is_for_mobile = false)
    {
        $page = new Page();
        $site_settings = self::site_settings();
        $booking_detail = $page->getSingleRow('booking', array('id' => $booking_id));
        $show_add_payment_option = false;
        $booking_status_to_show_add_payment_option = ['Picked', 'Completed with Overdue'];
        if (
            (
                ($is_for_mobile && $site_settings->show_add_payment_option_for_mobile == 'yes') ||
                (!$is_for_mobile && $site_settings->show_add_payment_option == 'yes')
            ) &&
            in_array($booking_detail->booking_status, $booking_status_to_show_add_payment_option)) {
            $show_add_payment_option = true;
        }

        return $show_add_payment_option;
    }

    public static function get_browser_os() {
        $operating_system = 'Unknown';

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'Windows') !== false) {
                $operating_system = 'Windows';
            } elseif (strpos($user_agent, 'Macintosh') !== false || strpos($user_agent, 'Mac OS X') !== false) {
                $operating_system = 'Mac OS';
            } elseif (strpos($user_agent, 'Linux') !== false) {
                $operating_system = 'Linux';
            } elseif (strpos($user_agent, 'Android') !== false) {
                $operating_system = 'Android';
            } elseif (strpos($user_agent, 'iOS') !== false) {
                $operating_system = 'iOS';
            }
        }

        return $operating_system;
    }

    public static function get_car_name($car_model_oasis_ref_no, $year) {
        $data = DB::table('corporate_quotation_prices as cqp')
            ->leftjoin('car_model as cm', function ($join) {
                $join->on('cqp.car_type', '=', 'cm.oracle_reference_number');
                $join->on('cqp.car_model', '=', 'cm.year');
            })
            ->leftjoin('car_type as ct', 'cm.car_type_id', 'ct.id')
            ->where('cqp.car_type', $car_model_oasis_ref_no)
            ->where('cqp.car_model', $year)
            ->select('ct.eng_title as car_type_title', 'cm.eng_title as car_model_title', 'cm.year')
            ->first();
        return ($data->car_type_title ? ($data->car_type_title . ' ' . $data->car_model_title . ' ' . $data->year) : 'N/A');
        // return ($data ? ($data->car_type_title . ' ' . $data->car_model_title . ' ' . ($year ? $year : $data->year)) : 'N/A');
    }

    public static function get_corporate_quotation_prices_extras_for_car($car_id, $corporate_customer_id, $search_by) {
        $query = DB::table('corporate_quotation_prices as cqp')
            ->join('car_model as cm', function ($join) {
                $join->on('cqp.car_type', '=', 'cm.oracle_reference_number');
                $join->on('cqp.car_model', '=', 'cm.year');
            })
            ->join('corporate_quotations as cq', 'cqp.corporate_quotation_id', 'cq.id')
            ->where('cq.corporate_customer_id', $corporate_customer_id)
            ->whereRaw("(cq.applies_from IS NULL OR DATE(cq.applies_from) <= '" . $search_by['pickup_date'] . "') AND (cq.applies_to IS NULL OR DATE(cq.applies_to) >= '" . $search_by['pickup_date'] . "')")
            ->where('cq.is_closed', 0)
            ->where('cm.id', $car_id)
            ->select('cqp.*');
        $data = $query->first();

        // self::dump($data);

        $extra_charges = [];
        if ($data) {

            if (
                ($search_by['days'] >= 1 && $search_by['days'] <= 26 && $data->daily_cdw_charges > 0) ||
                ($search_by['days'] > 26 && $data->monthly_cdw_charges > 0)
            ) {
                $cdw_charges = new stdClass();
                $cdw_charges->charge_element = 'CDW';
                $cdw_charges->is_one_time_applicable_on_booking = 0;
                $cdw_charges->price = ($search_by['days'] > 26 ? $data->monthly_cdw_charges : $data->daily_cdw_charges);
                $cdw_charges->is_from_quotation = 1;
                array_push($extra_charges, $cdw_charges);
            }

            if (
                ($search_by['days'] >= 1 && $search_by['days'] <= 26 && $data->daily_open_km_charges > 0) ||
                ($search_by['days'] > 26 && $data->monthly_open_km_charges > 0)
            ) {
                $open_km_charges = new stdClass();
                $open_km_charges->charge_element = 'GPS';
                $open_km_charges->is_one_time_applicable_on_booking = 0;
                $open_km_charges->price = ($search_by['days'] > 26 ? $data->monthly_open_km_charges : $data->daily_open_km_charges);
                $open_km_charges->is_from_quotation = 1;
                array_push($extra_charges, $open_km_charges);
            }

            if (
                ($search_by['days'] >= 1 && $search_by['days'] <= 26 && $data->daily_baby_seat_charges > 0) ||
                ($search_by['days'] > 26 && $data->monthly_baby_seat_charges > 0)
            ) {
                $baby_seat_charges = new stdClass();
                $baby_seat_charges->charge_element = 'Baby Seat';
                $baby_seat_charges->is_one_time_applicable_on_booking = 0;
                $baby_seat_charges->price = ($search_by['days'] > 26 ? $data->monthly_baby_seat_charges : $data->daily_baby_seat_charges);
                $baby_seat_charges->is_from_quotation = 1;
                array_push($extra_charges, $baby_seat_charges);
            }

            if (
                ($search_by['days'] >= 1 && $search_by['days'] <= 26 && $data->daily_extra_driver_charges > 0) ||
                ($search_by['days'] > 26 && $data->monthly_extra_driver_charges > 0)
            ) {
                $extra_driver_charges = new stdClass();
                $extra_driver_charges->charge_element = 'Extra Driver';
                $extra_driver_charges->is_one_time_applicable_on_booking = 0;
                $extra_driver_charges->price = ($search_by['days'] > 26 ? $data->monthly_extra_driver_charges : $data->daily_extra_driver_charges);
                $extra_driver_charges->is_from_quotation = 1;
                array_push($extra_charges, $extra_driver_charges);
            }

        }

        return $extra_charges;
    }

    public static function jwt_encode($user_id) {
        if ($user_id) {
            $key = Config::get('app.secret_key');
            $payload = [
                'iss' => config('app.url'),
                'aud' => config('app.url'),
                'iat' => time(),
                'nbf' => time(),
                // 'exp' => strtotime("+1 week"),
                'user_id' => $user_id,
            ];
            $jwt = JWT::encode($payload, $key, 'HS256');
            return $jwt;
        } else {
            return $user_id;
        }
    }

    public static function jwt_decode($jwt) {
        if ($jwt) {
            try {
                $key = Config::get('app.secret_key');
                $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
                return $decoded->user_id;
            } catch (InvalidArgumentException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            } catch (DomainException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            } catch (SignatureInvalidException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            } catch (BeforeValidException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            } catch (ExpiredException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            } catch (UnexpectedValueException $e) {
                echo json_encode(['status' => -1, 'message' => 'Authentication failed!', 'response' => []]);die;
            }
        } else {
            return $jwt;
        }
    }

    public static function isPasswordStrong($password, $lang = 'eng')
    {
        $status = true;

        // Minimum length requirement
        $minLength = 8;

        // Regular expressions for password strength checks
        $regexLowercase = '/[a-z]/';
        $regexUppercase = '/[A-Z]/';
        $regexNumber = '/[0-9]/';
        $regexSpecialChar = '/[!@#$%^&*()\-_=+{};:,<.>]/';

        // Check minimum length
        if (strlen($password) < $minLength) {
            $status = false;
        }

        // Check for lowercase letter
        if (!preg_match($regexLowercase, $password)) {
            $status = false;
        }

        // Check for uppercase letter
        if (!preg_match($regexUppercase, $password)) {
            $status = false;
        }

        // Check for numeric digit
        if (!preg_match($regexNumber, $password)) {
            $status = false;
        }

        // Check for special character
        if (!preg_match($regexSpecialChar, $password)) {
            $status = false;
        }

        // All checks passed, password is strong
        if ($status) {
            return ['status' => true, 'message' => ($lang == 'eng' ? 'Password is fine!' : 'كلمة السر جيدة!')];
        } else {
            return ['status' => false,
                'message' =>
                    ($lang == 'eng' ?
                        'The password must have a minimum length of ' . $minLength . ' characters and contain at least one lowercase letter, one uppercase letter, one numeric digit and one special character (e.g., !@#$%^&*()-_=+{};:,<.>)' :
                        'يجب ألا يقل طول كلمة المرور عن ' . $minLength . ' حرفًا وأن تحتوي على الأقل على حرف صغير واحد وحرف كبير واحد ورقم رقمي واحد وحرف خاص واحد (على سبيل المثال ،!@#$٪^&*()-_=+{}؛:،<.>)')
            ];
        }
    }

    public static function VerifySSLPinning() {
        $url = self::baseurl();
        $publicKeyHash = "sha256/MIIEFTCCAv2gAwIBAgIUYmgElli5p+GbhzmlB6dKiJvSNrIwDQYJKoZIhvcNAQEL
BQAwgagxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMRYwFAYDVQQH
Ew1TYW4gRnJhbmNpc2NvMRkwFwYDVQQKExBDbG91ZGZsYXJlLCBJbmMuMRswGQYD
VQQLExJ3d3cuY2xvdWRmbGFyZS5jb20xNDAyBgNVBAMTK01hbmFnZWQgQ0EgMTdi
MTgwN2NhNjUzNzQxMTk0MjA5YTMyNWM3ZWU0ZjAwHhcNMjMwNzEyMTI0MzAwWhcN
MzMwNzA5MTI0MzAwWjAiMQswCQYDVQQGEwJVUzETMBEGA1UEAxMKQ2xvdWRmbGFy
ZTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAOgQTjBCiSvRAB5qSb3p
NhPq6W2RkWZNkTa5huZVLQtAo84h7XdIR/f+KKC9BiRzTa/tlY4V4xwACqeq75VZ
J1Rl3cdMUoyGKcMtomwPaZ3lvqpiv+PUcBsYDhFQC6e6pX+lUzpNz5UxKSPDz+Io
5t3nLmyADi2Etx4bry1zOM+zDJ6YXNrOqNXjJXg9t2JZvLiAot10quHsIFVsBEkd
YJBeHqklrail3u2gj+8opqDZQfl5KMmqSTUkABFz1lLnnnwEy77/PT1XcmJbGj8i
KrQiVP2WuphReKIshQ+tBh8AqqAjFhEGGsPiUeRbpaJettZdp5xNZA8o4P/g6OE0
46cCAwEAAaOBuzCBuDATBgNVHSUEDDAKBggrBgEFBQcDAjAMBgNVHRMBAf8EAjAA
MB0GA1UdDgQWBBTn92imtCvYSjcr4CgomGG48RlmzTAfBgNVHSMEGDAWgBTe8q6H
8JJf22OUF4WooOnUZ4ftyzBTBgNVHR8ETDBKMEigRqBEhkJodHRwOi8vY3JsLmNs
b3VkZmxhcmUuY29tL2Y3MmE4ODgxLWIyODUtNGY2YS05MWEyLTViZGRmNmUyYjcz
Yy5jcmwwDQYJKoZIhvcNAQELBQADggEBAJa6rBHyc51785V8zlMQa+RxQLUHSri4
jV/1AFN2kBKw0q819YCMD7lryVIDA//CRTwB5nhnR7NkyK1z4xPJiJ0jYssGcnlU
n5YQo1Z7+sUb8Jr+3bGmtk2azQXu5jstduJRQLkxScro36kf4waW2rmuQXNZCJC5
o8kT0/VQeKXs0QYA0Ll4aNKvPobamcGHosC5FYml+c8Yoz94Ao+MMV8NVYh9Dt3a
nqkm2qUoR1EpM0SmJSayfaUqCCwPCP0NcpVTvHMqc0Wt/N/y81/T877VDuMjRbBG
4e7ygmUMIzTtGXEGcWbba3Og4P9SFKYIAzzctmnSUkd3rBqCwg9uO/8=";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, public_path('certificate.crt'));
        curl_setopt($curl, CURLOPT_PINNEDPUBLICKEY, $publicKeyHash);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public static function send_account_verification_links($user_id, $lang_base_url, $lang = 'eng') {

        $site_settings = self::site_settings();

        $user_slug = self::jwt_encode($user_id);

        $page = new PageModel();

        $customer = $page->getSingle('individual_customer', ['uid' => $user_id]);


        if ($site_settings->activate_account_with_email == 1) {
            // sending email verification email
            if ($lang == 'eng') {
                $email_text = "<br>Please click the link below to verify your email address.<br>";
            } else {
                $email_text = "<br>الرجاء النقر على الرابط أدناه للتحقق من عنوان بريدك الإلكتروني.<br>";
            }

            $email_text .= $lang_base_url . '/verify/email/' . $user_slug;

            $smtp = custom::smtp_settings();
            $site = custom::site_settings();
            $email['subject'] = ($lang == 'eng' ? 'Account email verification' : 'التحقق من البريد الإلكتروني للحساب');
            $email['fromEmail'] = $smtp->username;
            $email['fromName'] = 'no-reply';
            $email['toEmail'] = $customer->email ;
            $email['ccEmail'] = '';
            $email['bccEmail'] = '';
            $email['attachment'] = '';
            $content['contact_no'] = $site->site_phone;
            $content['lang_base_url'] = $lang_base_url;
            $content['name'] = $customer->first_name . " " . $customer->last_name;
            $content['msg'] = $email_text;
            $content['gender'] = $customer->gender;
            $email_sent = self::sendEmail('general', $content, $email, $lang);

            $page->updateData('users', ['is_email_verified' => 0], ['id' => $user_id]);
        } else {
            $page->updateData('users', ['is_email_verified' => 1], ['id' => $user_id]);
        }

        if ($site_settings->activate_account_with_sms == 1) {
            // sending phone verification SMS
            $mobile_no = str_replace(array('+', ' '), '', $customer->mobile_no);

            if ($lang == 'eng') {
                $sms_text = "Dear " . $customer->first_name . " " . $customer->last_name . ",\nPlease click the link below to verify your mobile number.\n";
            } else {
                $sms_text = "عزيزي " . $customer->first_name . " " . $customer->last_name . ",\nالرجاء الضغط على الرابط أدناه للتحقق من رقم هاتفك المحمول.\n";
            }

            $sms_text .= $lang_base_url . '/verify/phone/' . $user_slug;
            $sms_sent = self::sendSMS($mobile_no, $sms_text, $lang);

            $page->updateData('users', ['is_phone_verified' => 0], ['id' => $user_id]);
        } else {
            $page->updateData('users', ['is_phone_verified' => 1], ['id' => $user_id]);
        }
    }

    public static function excelExport($file_name, $data, $extension = 'xlsx') {
        $file_name .= '.' . $extension;
        return Excel::download(new GeneralExport($data), $file_name);
    }

    public static function baseurlNew() {
        return '';
    }

    public static function refer_and_earn_data($customer_id, $lang, $source, $add_to_db = false) {
        $coupon_code = false;
        $code_already_attached_to_customer = false;

        if ($customer_id > 0) {
            $c_code = DB::table('individual_customer_coupons')->where('customer_id', $customer_id)->first();
            if ($c_code) {
                $coupon_code = DB::table('promotion_offer_coupon')->where('promotion_offer_coupon_id', $c_code->promotion_offer_coupon_id)->value('code');
                $code_already_attached_to_customer = true;
            } else {
                $p_codes = DB::table('promotion_offer')
                    ->join('promotion_offer_coupon', 'promotion_offer.id', 'promotion_offer_coupon.promotion_offer_id')
                    ->where('promotion_offer.is_for_refer_and_earn', 1)
                    ->select('promotion_offer_coupon.*')
                    ->get();
                foreach ($p_codes as $p_code) {
                    $found = DB::table('individual_customer_coupons')->where('promotion_offer_coupon_id', $p_code->promotion_offer_coupon_id)->first();
                    if ($found) {
                        continue;
                    } else {
                        if ($add_to_db) {
                            $coupon_code = $p_code->code;
                            DB::table('individual_customer_coupons')->insertGetId(['promotion_offer_coupon_id' => $p_code->promotion_offer_coupon_id, 'customer_id' => $customer_id, 'source' => $source]);
                        }
                        break;
                    }
                }
            }
        }

        if (!$add_to_db) {
            $coupon_code = "ASD123";
        }

        if ($coupon_code) {

            if ($add_to_db && !$code_already_attached_to_customer) {
                $customer = DB::table('individual_customer')->where('id', $customer_id)->first();
                $api_url = 'http://api2.keyrac.sa:8080/KeyBookingService/AddReferralProgram?referral_code=' . $coupon_code . '&customer_id=' . $customer->id_no;
                $response = self::sendCurlRequest($api_url);
                // dd($response);
            }

            $refer_and_earn_content = DB::table('refer_and_earn_content')->where('id', 1)->first();

            $data = [
                'status' => true,
                'top_heading' => ($lang == 'eng' ? $refer_and_earn_content->eng_top_heading : $refer_and_earn_content->arb_top_heading),
                'top_description' => ($lang == 'eng' ? $refer_and_earn_content->eng_top_description : $refer_and_earn_content->arb_top_description),
                'share_message' => str_replace('{code}', $coupon_code, ($lang == 'eng' ? $refer_and_earn_content->eng_share_message : $refer_and_earn_content->arb_share_message)),
                'share_and_earn_button_amount_text' => ($lang == 'eng' ? $refer_and_earn_content->eng_share_and_earn_button_amount_text : $refer_and_earn_content->arb_share_and_earn_button_amount_text),
                'how_it_works' => [
                    [
                        'title' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_title_1 : $refer_and_earn_content->arb_how_it_works_title_1),
                        'description' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_description_1 : $refer_and_earn_content->arb_how_it_works_description_1),
                    ],
                    [
                        'title' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_title_2 : $refer_and_earn_content->arb_how_it_works_title_2),
                        'description' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_description_2 : $refer_and_earn_content->arb_how_it_works_description_2),
                    ],
                    [
                        'title' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_title_3 : $refer_and_earn_content->arb_how_it_works_title_3),
                        'description' => ($lang == 'eng' ? $refer_and_earn_content->eng_how_it_works_description_3 : $refer_and_earn_content->arb_how_it_works_description_3),
                    ],
                ],
                'coupon_code' => $coupon_code,
            ];
        } else {
            $data = [
                'status' => false
            ];
        }

        return $data;
    }

    public static function refer_and_earn_data_simple($lang = 'eng') {
        $refer_and_earn_content = DB::table('refer_and_earn_content')->where('id', 1)->first();
        $data = [
            'share_and_earn_button_amount_text' => ($lang == 'eng' ? $refer_and_earn_content->eng_share_and_earn_button_amount_text : $refer_and_earn_content->arb_share_and_earn_button_amount_text),
        ];

        return $data;
    }

    public static function sendCurlRequest($curl_url, $dump = false)
    {
        if ($dump) {
            echo $curl_url;die;
        }
        $curlConnection = curl_init();
        curl_setopt($curlConnection, CURLOPT_URL, $curl_url);
        curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
        $curlResponse = curl_exec($curlConnection);
        curl_close($curlConnection);
        return $curlResponse;
    }

    public static function removeEmojis($text) {
        // Remove emojis using a regular expression
        $cleanText = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text);

        // Remove symbols and other characters commonly used as emojis
        $cleanText = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $cleanText);

        // Remove transport and map symbols
        $cleanText = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $cleanText);

        // Remove miscellaneous symbols
        $cleanText = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $cleanText);

        // Remove emoticons
        $cleanText = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $cleanText);

        // Remove text presentation characters
        $cleanText = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $cleanText);

        // Remove variation selectors
        $cleanText = preg_replace('/[\x{E0100}-\x{E01EF}]/u', '', $cleanText);

        return $cleanText;
    }

    public static function is_oasis_api_enabled() {
        $api_settings = custom::api_settings();
        if ($api_settings->api_on_off == 'off') {
            return false;
        }
        return true;
    }

    public static function maskText($text, $length_to_keep_at_start = 4) {
        $length = strlen($text);

        $prefix = substr($text, 0, $length_to_keep_at_start);

        $mask = str_repeat('*', $length - $length_to_keep_at_start);

        return $prefix . $mask;
    }

    public static function subscribe_device_to_fcm_topic($device_token, $debug = false) {

        $endpoint = 'https://iid.googleapis.com/iid/v1:batchAdd';

        $serverKey = 'AAAAJ-H0_LQ:APA91bE9yZAOIJzmSnhBto-V4LrYDMSC4upvjO8hzA6JphutLqkYoV6qnzBSFhG9x9vhTwvMGCmqB_BN2hqr1X5xtbiyKgR1Bw4X2JnzcHnFW47W-GzJ_mJF-eVnZAzR_YiffSX4joBt';

        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == 'kra.ced.sa') {
            $topic = 'KeyCarRentalDev';
        } else {
            $topic = 'KeyCarRentalLive';
        }

        $registrationTokens = is_array($device_token) ? $device_token : [$device_token];

        $data = [
            'to' => '/topics/' . $topic,
            'registration_tokens' => $registrationTokens
        ];

        $jsonData = json_encode($data);

        $oauth_token = file_get_contents('https://nt.ced.sa/get-google-auth-token.php?response=1');

        $headers = array(
            'Authorization: Bearer ' . $oauth_token,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($ch);

        if ($debug) {
            var_dump($response);die;
        }

        if ($response === false) {
            $result = ['status' => false, 'message' => 'Error: ' . curl_error($ch)];
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['results'])) {
                foreach ($responseData['results'] as $result) {
                    if (isset($result['error'])) {
                        $result = ['status' => false, 'message' => 'Error subscribing device: ' . $result['error']];
                    } else {
                        $result = ['status' => true, 'message' => 'Device subscribed successfully'];
                    }
                }
            } else {
                $result = ['status' => false, 'message' => 'Unexpected response from FCM server'];
            }
        }
        curl_close($ch);
        return $result;
    }

    public static function encode_with_jwt($data) {
        $key = Config::get('app.secret_key');
        $payload = [
            'iss' => config('app.url'),
            'aud' => config('app.url'),
            'iat' => time(),
            'nbf' => time(),
            'data' => $data,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function decode_with_jwt($jwt) {
        try {
            $key = Config::get('app.secret_key');
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return $decoded->data;
        } catch (InvalidArgumentException $e) {
            abort(404);
        } catch (DomainException $e) {
            abort(404);
        } catch (SignatureInvalidException $e) {
            abort(404);
        } catch (BeforeValidException $e) {
            abort(404);
        } catch (ExpiredException $e) {
            abort(404);
        } catch (UnexpectedValueException $e) {
            abort(404);
        }
    }

    public static function delivery_booking_statuses($status, $lang = 'eng') {
        $delivery_booking_statuses = [
            'D' => ($lang == 'eng' ? 'Driver Assigned' : 'تم تعيين السائق'),
            'H' => ($lang == 'eng' ? 'Heading to customer location' : 'تم التوجه إلى مكان العميل'),
            'A' => ($lang == 'eng' ? 'Arrived at customer location' : 'تم الوصول إلى مكان العميل'),
            'B' => ($lang == 'eng' ? 'Back to office' : 'تم الرجوع للمكتب'),
            'C' => ($lang == 'eng' ? 'Closed trip' : 'الرحلة مغلقة'),
        ];

        return isset($delivery_booking_statuses[strtoupper($status)]) ? $delivery_booking_statuses[strtoupper($status)] : '';
    }

    public static function get_limousine_branches() {
        $branches = DB::table('region')
            ->join('city', 'region.id', '=', 'city.region_id')
            ->join('branch', 'city.id', '=', 'branch.city_id')
            ->where('branch.active_status', '=', '1')
            ->where('branch.is_for_limousine_mode_only', '=', 'yes')
            ->select('branch.id as branch_id', 'city.id as city_id', 'region.id as region_id', 'branch.eng_title as eng_branch_name', 'branch.arb_title as arb_branch_name', 'city.eng_title as eng_city_name', 'city.arb_title as arb_city_name')->get();
        return $branches;
    }

    public static function get_limousine_extra_charges() {
        $hDiff = 0;
        $extra_charges = 0;
        $session_vars = Session::all();
        if (isset($session_vars['search_data']['isLimousine']) && $session_vars['search_data']['isLimousine'] == 1 && $session_vars['search_data']['isRoundTripForLimousine'] == 1) {
            $cpid = $session_vars['cpid'];
            $extra_hours_rate_for_limousine = $session_vars['extra_hours_rate_for_limousine'];
            $pickup = $session_vars['search_data']['pickup_date'] . ' ' . $session_vars['search_data']['pickup_time'];
            $dropoff = $session_vars['search_data']['dropoff_date'] . ' ' . $session_vars['search_data']['dropoff_time'];
            $date_picked_up_for_hours_cal = new Carbon($pickup);
            $date_dropped_off_for_hours_cal = new Carbon($dropoff);
            $hours_diff = round($date_dropped_off_for_hours_cal->diffInMinutes($date_picked_up_for_hours_cal) / 60);
            $hDiff = ($hours_diff > 8 ? $hours_diff - 8 : 0);
            if ($hDiff > 0) {
                $extra_charges = $extra_hours_rate_for_limousine * $hDiff;
            }
        }
        return ['waiting_extra_hours' => $hDiff, 'waiting_extra_hours_charges' => $extra_charges];
    }

    public static function hide_only_for_limousine($is_limousine) {
        return ($is_limousine == 'Yes' ? 'hd-payment' : '');
    }

    public static function get_qr_code_for_invoice($invoice_no) {
        $url = "http://api2.keyrac.sa:8080/KeyBookingService/GetQrCode?invoice_no=" . $invoice_no;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            // echo 'Curl Error: ' . curl_error($ch);die;
        }
        curl_close($ch);
        /*****************************************/

        return $response;
    }

    public static function call_oasis_api_with_url($url) {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            // echo 'Curl Error: ' . curl_error($ch);die;
        }
        curl_close($ch);
        /*****************************************/

        return $response;
    }

    public static function sendEventToGA4($event_name, $event_params, $dump = false)
    {
        $measurement_id = 'G-0E497L2PXD';
        $api_secret = 'Cl6scZQHTOi9HqQ0eUz8Xg';
        $client_id = self::getClientId();

        $data = [
            'client_id' => $client_id,
            'events' => [
                [
                    'name' => $event_name,
                    'params' => $event_params
                ]
            ]
        ];

        $json_data = json_encode($data);

        if ($dump) {
            dd($json_data);
        }

        $url = "https://www.google-analytics.com/mp/collect?measurement_id={$measurement_id}&api_secret={$api_secret}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        return $response;
    }

    public static function getClientId() {
        if (!isset($_COOKIE['ga_client_id'])) {
            $client_id = uniqid();
            setcookie('ga_client_id', $client_id, time() + (86400 * 365)); // 1 year
        } else {
            $client_id = $_COOKIE['ga_client_id'];
        }
        return $client_id;
    }

}

?>