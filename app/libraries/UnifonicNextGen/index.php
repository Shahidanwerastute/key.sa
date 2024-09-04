<?php

require_once "vendor/autoload.php";

use App\libraries\UnifonicNextGen\src\Models;
use App\libraries\UnifonicNextGen\src\Exceptions;

$basicAuthUserName = 'it@key.sa';
$basicAuthPassword = 'SMS!key$en8';

$client = new App\libraries\UnifonicNextGen\src\UnifonicNextGenClient($basicAuthUserName, $basicAuthPassword);

$restController = $client->getRest();

$appSid = 'kAaak2qvUIArLiXYxT77skey9QbEEj';
$senderID = 'KEY';
$body = 'Test';
$recipient = 966505980169;
$responseType = 'JSON';
$correlationID = 'CorrelationID';
$baseEncode = true;
$statusCallback = 'sent';
$async = false;

try {
    $result = $restController->createSendMessage($appSid, $senderID, $body, $recipient, $responseType, $correlationID, $baseEncode, $statusCallback, $async);
} catch (App\libraries\UnifonicNextGen\src\APIException $e) {
    echo 'Caught APIException: ',  $e->getMessage(), "\n";
}