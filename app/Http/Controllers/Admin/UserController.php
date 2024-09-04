<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\IndividualCustomer;
use App\Models\Admin\User;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller {


  public function getSingleUserInfo()
  {
    $user = new User();
    $booking_id = $_REQUEST['booking_id'];
    $type = $_REQUEST['user_type'];
    if ($type == 'corporate_customer')
    {
      $row = $user->getSingleCorporateUserInfo($booking_id);
    }elseif ($type == 'individual_customer')
    {
      $row = $user->getSingleIndividualUserInfo($booking_id);
    }elseif ($type == 'guest')
    {
      $row = $user->getSingleGuestUserInfo($booking_id);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['TotalRecordCount'] = 1;
    $jTableResult['Records'] = $row;
    print json_encode($jTableResult);

  }

  
}

?>