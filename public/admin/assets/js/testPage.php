public function paytabsIPN(Request $request)
{
//mail('bilal_ejaz@astutesol.com', 'paytabs IPN file hit at start at 4600', 'email sent');
$emailString = '';
foreach ($_POST as $key => $value) {
$emailString .= $key . '|' . $value . ',';
}
//mail('bilal_ejaz@astutesol.com', 'Paytabs IPN Response at 4605', $emailString);

/*$email['subject'] = 'paytabs IPN file hit at start at 4607';
$email['fromEmail'] = 'key@key.sa';
$email['fromName'] = 'no-reply';
$email['toEmail'] = 'kholoud.j@edesign.com.sa';
$email['ccEmail'] = '';
$email['bccEmail'] = '';
$email['attachment'] = '';

$content['contact_no'] = '0321';
$content['lang_base_url'] = $this->lang_base_url;
$content['name'] = 'Bilal';
$content['msg'] = 'email sent';
$content['gender'] = 'male';*/
//custom::sendEmail('general', $content, $email, 'eng');


$email['subject'] = 'Paytabs IPN Response at 4623';
$email['fromEmail'] = 'key@key.sa';
$email['fromName'] = 'no-reply';
$email['toEmail'] = 'kholoud.j@edesign.com.sa';
$email['ccEmail'] = '';
$email['bccEmail'] = '';
$email['attachment'] = '';

$content['contact_no'] = '0321';
$content['lang_base_url'] = $this->lang_base_url;
$content['name'] = 'Bilal';
$content['msg'] = $emailString;
$content['gender'] = 'male';
//custom::sendEmail('general', $content, $email, 'eng');

//exit();
//mail('bilal_ejaz@astutesol.com', 'paytabs IPN response testing', 'email sent');
//echo 'function test successful';
/* if ($request->isMethod('post'))
{*/
//echo 'IPN Processing';
$response_from_api = $_POST;
//custom::testEmail($response_from_api);

if (isset($response_from_api['first_4_digits']) && $response_from_api['first_4_digits'] > 0 && $response_from_api['card_brand'] != 'Unknown') { // this is a case of credit card
//print_r($response_from_api);exit();
//mail('bilal_ejaz@astutesol.com', 'Paytabs IPN File Is Called', 'This is just for testing that IPN is calling');

// response code 5001: Payment has been accepted successfully
// response code 5002: Payment has been forcefully accepted
if (isset($response_from_api['response_code'])) {
//mail('bilal_ejaz@astutesol.com', 'paytabs IPN file hit in credit card section at 4654', 'email sent');
/*$email['subject'] = 'paytabs IPN file hit in credit card section at 4656';
$email['fromEmail'] = 'key@key.sa';
$email['fromName'] = 'no-reply';
$email['toEmail'] = 'kholoud.j@edesign.com.sa';
$email['ccEmail'] = '';
$email['bccEmail'] = '';
$email['attachment'] = '';*/

/*$content['contact_no'] = '0321';
$content['lang_base_url'] = $this->lang_base_url;
$content['name'] = 'Bilal';
$content['msg'] = 'email sent';
$content['gender'] = 'male';*/
//custom::sendEmail('general', $content, $email, 'eng');

$res = explode('-', $response_from_api['order_id']);
$booking_id = $res[0];
$lang = $this->lang = $res[1];
$user_mobile_no = $response_from_api['customer_phone'];

//$booking_id = 29;
//$user_mobile_no = "923368809300";

/*$emailString = '';
foreach ($response_from_api as $key => $value) {
$emailString .= $key . '|' . $value . ',';
}*/
//mail('bilal_ejaz@astutesol.com', 'Paytabs IPN Response', $emailString);

if ($response_from_api['response_code'] == 100 || $response_from_api['response_code'] == 5002) {
//if (true) {
//mail('bilal_ejaz@astutesol.com', 'Before Update at the start of response code if at 4686', 'Before Update');
/*$email['subject'] = 'Before Update at the start of response code if at 4688';
$email['fromEmail'] = 'key@key.sa';
$email['fromName'] = 'no-reply';
$email['toEmail'] = 'kholoud.j@edesign.com.sa';
$email['ccEmail'] = '';
$email['bccEmail'] = '';
$email['attachment'] = '';

$content['contact_no'] = '0321';
$content['lang_base_url'] = $this->lang_base_url;
$content['name'] = 'Bilal';
$content['msg'] = 'Before Update';
$content['gender'] = 'male';
custom::sendEmail('general', $content, $email, 'eng');*/

$booking_cc_update['status'] = 'completed';
$booking_cc_update['transaction_id'] = $response_from_api['transaction_id'];
$booking_cc_update['first_4_digits'] = $response_from_api['first_4_digits'];
$booking_cc_update['last_4_digits'] = $response_from_api['last_4_digits'];
$booking_cc_update['card_brand'] = $response_from_api['card_brand'];
$booking_cc_update['trans_date'] = date('Y-m-d H:i:s', strtotime($response_from_api['datetime']));

/*$booking_cc_update['status'] = 'completed';
$booking_cc_update['transaction_id'] = '123321';
$booking_cc_update['first_4_digits'] = "4111";
$booking_cc_update['last_4_digits'] = "1111";
$booking_cc_update['card_brand'] = "Visa";
$booking_cc_update['trans_date'] = date('Y-m-d H:i:s');*/

$this->page->updateData('booking_cc_payment', $booking_cc_update, array('booking_id' => $booking_id));
//mail('bilal_ejaz@astutesol.com', 'After Update', 'After Update');
// moved this email function here to send email after successful credit card transaction

$this->sendEmailToUser($booking_id);

$bookingInfo = $this->page->getSingle('booking', array("id" => $booking_id));

$this->sendThankYouSMS($bookingInfo->reservation_code, $user_mobile_no);
}
}

} else {
// this is a case of sadad
if ($response_from_api['response_code'] == 5001) {
$res = explode('-', $response_from_api['order_id']);
$booking_id = $res[0];
$lang = $this->lang = $res[1];
$user_mobile_no = $response_from_api['customer_phone'];
//mail('bilal_ejaz@astutesol.com', 'paytabs IPN response in sadad section else section at 4736', 'email sent');
//echo 'function test successful';
$booking_sadad_update['s_status'] = 'completed';
$booking_sadad_update['s_transaction_id'] = $response_from_api['transaction_id'];
$booking_sadad_update['s_invoice_id'] = $response_from_api['invoice_id'];
$booking_sadad_update['s_trans_date'] = date('Y-m-d H:i:s', strtotime($response_from_api['datetime']));
$this->page->updateData('booking_sadad_payment', $booking_sadad_update, array('s_booking_id' => $booking_id));

$this->sendEmailToUser($booking_id);

$bookingInfo = $this->page->getSingle('booking', array("id" => $booking_id));

$this->sendThankYouSMS($bookingInfo->reservation_code, $user_mobile_no);

//$response_from_api['from'] = 'sadad';
//custom::testEmail($response_from_api);
/*$email['subject'] = 'paytabs IPN response in sadad section else section at 4740';
$email['fromEmail'] = 'key@key.sa';
$email['fromName'] = 'no-reply';
$email['toEmail'] = 'kholoud.j@edesign.com.sa';
$email['ccEmail'] = '';
$email['bccEmail'] = '';
$email['attachment'] = '';

$content['contact_no'] = '0321';
$content['lang_base_url'] = $this->lang_base_url;
$content['name'] = 'Bilal';
$content['msg'] = 'email sent';
$content['gender'] = 'male';
custom::sendEmail('general', $content, $email, 'eng');*/
}
}

// hitting cronjob to sync bookings
$cronjob_url = url('/') . '/cronjob/setDataCronJob';
$response = file_get_contents($cronjob_url);
var_dump($response);
exit();
/*} else {

}*/
}