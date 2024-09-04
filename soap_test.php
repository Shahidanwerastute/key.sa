<?php
try {
    $soap_obj = new SoapClient('http://api.keyrac.sa:8080/BookingApi/BookingApi?WSDL', ['trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY]);
    echo "<pre>";print_r($soap_obj);exit();
    echo "here";
} catch (SoapFault $fault) {
    $errorMsg = "Exception occured:<br>";
    echo $errorMsg .= $fault->faultcode . " " . $fault->faultstring;
    // $this->sendEmail('Connection Refused Error For '.custom::getSiteName("eng").' Dev API', $errorMsg, true);
    // echo 'Unable to call soap client in constructor';
    exit();
}
?>