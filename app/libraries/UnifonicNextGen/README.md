# Getting started

## Introduction

Send SMS messages using Unifonic Messaging API. Get your dedicated Universal number, and start sending messages today.

Unifonic NextGen Restful and HTTP **API's** uses The basic Authentication protocol. All request and response bodies are formatted in JSON.


## Get an account
To start using  the API you need to send an email to Unifonic to create Appsid for you.

## Base URL
All URLs referenced in the documentation have the following base:

**el.cloud.unifonic.com**
## Security
To ensure privacy we recommend you to use HTTPS for all Unifonic API requests.
you can download our HTTPS certificate.

 [Download] (https://api.unifonic.com/udm/https.zip)





## Formats

  Unifonic API only supports JSON format. All request must use the Content-type header set to application/json.

## Support
We’re here to help! Get in touch with support at <support@unifonic.com> and we’ll get back to you as soon as we can or you can contact us throw live chat on our [website] (www.unifonic.com).

## How to Build

The generated code has dependencies over external libraries like UniRest. These dependencies are defined in the ```composer.json``` file that comes with the SDK. 
To resolve these dependencies, we use the Composer package manager which requires PHP greater than 5.3.2 installed in your system. 
Visit [https://getcomposer.org/download/](https://getcomposer.org/download/) to download the installer file for Composer and run it in your system. 
Open command prompt and type ```composer --version```. This should display the current version of the Composer installed if the installation was successful.

* Using command line, navigate to the directory containing the generated files (including ```composer.json```) for the SDK. 
* Run the command ```composer install```. This should install all the required dependencies and create the ```vendor``` directory in your project directory.

![Building SDK - Step 1](https://apidocs.io/illustration/php?step=installDependencies&workspaceFolder=Unifonic%20NextGen-PHP)

### [For Windows Users Only] Configuring CURL Certificate Path in php.ini

CURL used to include a list of accepted CAs, but no longer bundles ANY CA certs. So by default it will reject all SSL certificates as unverifiable. You will have to get your CA's cert and point curl at it. The steps are as follows:

1. Download the certificate bundle (.pem file) from [https://curl.haxx.se/docs/caextract.html](https://curl.haxx.se/docs/caextract.html) on to your system.
2. Add curl.cainfo = "PATH_TO/cacert.pem" to your php.ini file located in your php installation. “PATH_TO” must be an absolute path containing the .pem file.

```ini
[curl]
; A default value for the CURLOPT_CAINFO option. This is required to be an
; absolute path.
;curl.cainfo =
```

## How to Use

The following section explains how to use the UnifonicNextGen library in a new project.

### 1. Open Project in an IDE

Open an IDE for PHP like PhpStorm. The basic workflow presented here is also applicable if you prefer using a different editor or IDE.

![Open project in PHPStorm - Step 1](https://apidocs.io/illustration/php?step=openIDE&workspaceFolder=Unifonic%20NextGen-PHP)

Click on ```Open``` in PhpStorm to browse to your generated SDK directory and then click ```OK```.

![Open project in PHPStorm - Step 2](https://apidocs.io/illustration/php?step=openProject0&workspaceFolder=Unifonic%20NextGen-PHP)     

### 2. Add a new Test Project

Create a new directory by right clicking on the solution name as shown below:

![Add a new project in PHPStorm - Step 1](https://apidocs.io/illustration/php?step=createDirectory&workspaceFolder=Unifonic%20NextGen-PHP)

Name the directory as "test"

![Add a new project in PHPStorm - Step 2](https://apidocs.io/illustration/php?step=nameDirectory&workspaceFolder=Unifonic%20NextGen-PHP)
   
Add a PHP file to this project

![Add a new project in PHPStorm - Step 3](https://apidocs.io/illustration/php?step=createFile&workspaceFolder=Unifonic%20NextGen-PHP)

Name it "testSDK"

![Add a new project in PHPStorm - Step 4](https://apidocs.io/illustration/php?step=nameFile&workspaceFolder=Unifonic%20NextGen-PHP)

Depending on your project setup, you might need to include composer's autoloader in your PHP code to enable auto loading of classes.

```PHP
require_once "../vendor/autoload.php";
```

It is important that the path inside require_once correctly points to the file ```autoload.php``` inside the vendor directory created during dependency installations.

![Add a new project in PHPStorm - Step 4](https://apidocs.io/illustration/php?step=projectFiles&workspaceFolder=Unifonic%20NextGen-PHP)

After this you can add code to initialize the client library and acquire the instance of a Controller class. Sample code to initialize the client library and using controller methods is given in the subsequent sections.

### 3. Run the Test Project

To run your project you must set the Interpreter for your project. Interpreter is the PHP engine installed on your computer.

Open ```Settings``` from ```File``` menu.

![Run Test Project - Step 1](https://apidocs.io/illustration/php?step=openSettings&workspaceFolder=Unifonic%20NextGen-PHP)

Select ```PHP``` from within ```Languages & Frameworks```

![Run Test Project - Step 2](https://apidocs.io/illustration/php?step=setInterpreter0&workspaceFolder=Unifonic%20NextGen-PHP)

Browse for Interpreters near the ```Interpreter``` option and choose your interpreter.

![Run Test Project - Step 3](https://apidocs.io/illustration/php?step=setInterpreter1&workspaceFolder=Unifonic%20NextGen-PHP)

Once the interpreter is selected, click ```OK```

![Run Test Project - Step 4](https://apidocs.io/illustration/php?step=setInterpreter2&workspaceFolder=Unifonic%20NextGen-PHP)

To run your project, right click on your PHP file inside your Test project and click on ```Run```

![Run Test Project - Step 5](https://apidocs.io/illustration/php?step=runProject&workspaceFolder=Unifonic%20NextGen-PHP)

## How to Test

Unit tests in this SDK can be run using PHPUnit. 

1. First install the dependencies using composer including the `require-dev` dependencies.
2. Run `vendor\bin\phpunit --verbose` from commandline to execute tests. If you have 
   installed PHPUnit globally, run tests using `phpunit --verbose` instead.

You can change the PHPUnit test configuration in the `phpunit.xml` file.

## Initialization

### Authentication
In order to setup authentication and initialization of the API client, you need the following information.

| Parameter | Description |
|-----------|-------------|
| basicAuthUserName | The username to use with basic authentication |
| basicAuthPassword | The password to use with basic authentication |



API client can be initialized as following.

```php
$basicAuthUserName = 'basicAuthUserName'; // The username to use with basic authentication
$basicAuthPassword = 'basicAuthPassword'; // The password to use with basic authentication

$client = new App\libraries\UnifonicNextGen\src\UnifonicNextGenClient($basicAuthUserName, $basicAuthPassword);
```


# Class Reference

## <a name="list_of_controllers"></a>List of Controllers

* [RestController](#rest_controller)
* [WrapperController](#wrapper_controller)

## <a name="rest_controller"></a>![Class: ](https://apidocs.io/img/class.png ".RestController") RestController

### Get singleton instance

The singleton instance of the ``` RestController ``` class can be accessed from the API Client.

```php
$rest = $client->getRest();
```

### <a name="delete_stop_scheduled_messages"></a>![Method: ](https://apidocs.io/img/method.png ".RestController.deleteStopScheduledMessages") deleteStopScheduledMessages

> Unifonic Stop scheduled messages API allows you to Delete (Stops) scheduled message,If MessageID is specified only one message is stopped, Otherwise all messages are stopped through simple RESTful APIs.


```php
function deleteStopScheduledMessages(
        $appSid,
        $messageID = null,
        $responseFormat = null,
        $baseEncode = null)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appSid |  ``` Required ```  | A character string that uniquely identifies your app |
| messageID |  ``` Optional ```  | A unique ID that identifies a message |
| responseFormat |  ``` Optional ```  | support json format only |
| baseEncode |  ``` Optional ```  | Binary-to-text encoding schemes that represent binary data in an ASCII string format |



#### Example Usage

```php
$appSid = '6v253153s1g7831s5';
$messageID = 3200017889502;
$responseFormat = 'responseFormat';
$baseEncode = true;

$result = $rest->deleteStopScheduledMessages($appSid, $messageID, $responseFormat, $baseEncode);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 455 | Scheduled message not found for this User |



### <a name="create_send_scheduled_messages"></a>![Method: ](https://apidocs.io/img/method.png ".RestController.createSendScheduledMessages") createSendScheduledMessages

> Unifonic Send Scheduled API allows you to schedule text messages to users around the globe through simple RESTful API to be sent in future.


```php
function createSendScheduledMessages(
        $appSid,
        $senderID,
        $recipient,
        $body,
        $timeScheduled,
        $responseType = null,
        $correlationID = null,
        $baseEncode = null)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appSid |  ``` Required ```  | A character string that uniquely identifies your app |
| senderID |  ``` Required ```  | The SenderID to send from, App default SenderID is used unless else stated |
| recipient |  ``` Required ```  | Destination mobile number, mobile numbers must be in international format without 00 or + Example: (4452023498) |
| body |  ``` Required ```  | Message body supports both English and unicodes characters, concatenated messages is supported |
| timeScheduled |  ``` Required ```  | Schedule send messages, in the following format yyyy-mm-dd HH:mm:ss |
| responseType |  ``` Optional ```  | Support json format only |
| correlationID |  ``` Optional ```  | Is a unique identifier value that is attached to requests and messages |
| baseEncode |  ``` Optional ```  | Binary-to-text encoding schemes that represent binary data in an ASCII string format |



#### Example Usage

```php
$appSid = '6v253153s1g7831s5';
$senderID = 'sender';
$recipient = 966505980169;
$body = 'Test';
$timeScheduled = 2020-04-12 11:50:00;
$responseType = 'responseType';
$correlationID = 'CorrelationID';
$baseEncode = true;

$result = $rest->createSendScheduledMessages($appSid, $senderID, $recipient, $body, $timeScheduled, $responseType, $correlationID, $baseEncode);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 406 | Wrong parameter format |
| 449 | Message body is empty |
| 451 | TimeScheduled parameter must indicate time in the future |
| 480 | This user cannot use specified SenderID |
| 482 | Invalid dest num |



### <a name="create_get_message_details"></a>![Method: ](https://apidocs.io/img/method.png ".RestController.createGetMessageDetails") createGetMessageDetails

> Unifonic Get message details API allows you to get details of messages with optional filters,returns paginated messages, next page or previous page through simple RESTful APIs


```php
function createGetMessageDetails(
        $appSid,
        $messageID = null,
        $senderID = null,
        $recipient = null,
        $dateFrom = null,
        $dateTo = null,
        $correlationID = null,
        $limit = null,
        $baseEncode = null)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appSid |  ``` Required ```  | A character string that uniquely identifies your app |
| messageID |  ``` Optional ```  | A unique ID that identifies a message |
| senderID |  ``` Optional ```  | The SenderID to send from, App default SenderID is used unless else stated |
| recipient |  ``` Optional ```  | Destination mobile number, mobile numbers must be in international format without 00 or + Example: (4452023498) |
| dateFrom |  ``` Optional ```  | The start date for the report time interval, date format should be yyyy-mm-dd |
| dateTo |  ``` Optional ```  | The end date for the report time interval, date format should be yyyy-mm-dd |
| correlationID |  ``` Optional ```  | Is a unique identifier value that is attached to requests and messages |
| limit |  ``` Optional ```  | The maximum number of messages details |
| baseEncode |  ``` Optional ```  | Binary-to-text encoding schemes that represent binary data in an ASCII string format |



#### Example Usage

```php
$appSid = '6v253153s1g7831s5';
$messageID = 2000000172800;
$senderID = 'sender';
$recipient = 966505980169;
$dateFrom = 2018-04-12;
$dateTo = 2018-09-12;
$correlationID = 'CorrelationID';
$limit = 20;
$baseEncode = true;

$result = $rest->createGetMessageDetails($appSid, $messageID, $senderID, $recipient, $dateFrom, $dateTo, $correlationID, $limit, $baseEncode);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 432 | MessageId must be numeric |
| 599 | Request failed |



### <a name="get_scheduled_message_details"></a>![Method: ](https://apidocs.io/img/method.png ".RestController.getScheduledMessageDetails") getScheduledMessageDetails

> Unifonic Scheduled message details allows you to get details of scheduled messages through simple RESTful APIs


```php
function getScheduledMessageDetails($appSid)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appSid |  ``` Required ```  | A character string that uniquely identifies your app |



#### Example Usage

```php
$appSid = '6v253153s1g7831s5';

$result = $rest->getScheduledMessageDetails($appSid);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |



### <a name="create_send_message"></a>![Method: ](https://apidocs.io/img/method.png ".RestController.createSendMessage") createSendMessage

> Unifonic Send API allows you to send  text messages to users around the globe through simple RESTful APIs


```php
function createSendMessage(
        $appSid,
        $senderID,
        $body,
        $recipient,
        $responseType = null,
        $correlationID = null,
        $baseEncode = null,
        $statusCallback = null,
        $async = false)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appSid |  ``` Required ```  | A character string that uniquely identifies your app |
| senderID |  ``` Required ```  | The SenderID to send from, App default SenderID is used unless else stated |
| body |  ``` Required ```  | Message body supports both English and unicodes characters, concatenated messages is supported |
| recipient |  ``` Required ```  | Destination mobile number, mobile numbers must be in international format without 00 or + Example: (4452023498) |
| responseType |  ``` Optional ```  | Support json format only |
| correlationID |  ``` Optional ```  | Is a unique identifier value that is attached to requests and messages |
| baseEncode |  ``` Optional ```  | Binary-to-text encoding schemes that represent binary data in an ASCII string format |
| statusCallback |  ``` Optional ```  | Filter messages report according to a specific message status, "Sent", "Queued", "Rejected" or "Failed |
| async |  ``` Optional ```  ``` DefaultValue ```  | It specifies that the request will be executed asynchronously as soon as it is sent |



#### Example Usage

```php
$appSid = '6v253153s1g7831s5';
$senderID = 'sender';
$body = 'Test';
$recipient = 966505980169;
$responseType = 'responseType';
$correlationID = 'CorrelationID';
$baseEncode = true;
$statusCallback = 'statusCallback';
$async = false;

$result = $rest->createSendMessage($appSid, $senderID, $body, $recipient, $responseType, $correlationID, $baseEncode, $statusCallback, $async);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 449 | Message body is empty |
| 480 | This user cannot use specified SenderID |
| 482 | Invalid dest num |



[Back to List of Controllers](#list_of_controllers)

## <a name="wrapper_controller"></a>![Class: ](https://apidocs.io/img/class.png ".WrapperController") WrapperController

### Get singleton instance

The singleton instance of the ``` WrapperController ``` class can be accessed from the API Client.

```php
$wrapper = $client->getWrapper();
```

### <a name="create_get_msg_query"></a>![Method: ](https://apidocs.io/img/method.png ".WrapperController.createGetMsgQuery") createGetMsgQuery

> Unifonic Get message query API allows you to get details of specified message.


```php
function createGetMsgQuery(
        $appsid,
        $msgid,
        $to = null)
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appsid |  ``` Required ```  | A character string that uniquely identifies your app |
| msgid |  ``` Required ```  | A unique ID that identifies a message |
| to |  ``` Optional ```  | Destination mobile number, mobile numbers must be in international format without 00 or + Example: (4452023498) |



#### Example Usage

```php
$appsid = '6v253153s1g7831s5';
$msgid = 3200017891630;
$to = 966505980169;

$result = $wrapper->createGetMsgQuery($appsid, $msgid, $to);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 402 | Missing parameter AppSid |
| 432 | MessageId must be numeric |
| 452 | User must specify either messageId or recipient parameter |



### <a name="create_send_message"></a>![Method: ](https://apidocs.io/img/method.png ".WrapperController.createSendMessage") createSendMessage

> Unifonic Send Wrapper API allows you to send  text messages to  multiple users at the same time


```php
function createSendMessage(
        $appsid,
        $msg,
        $to,
        $sender,
        $baseEncode = false,
        $encoding = 'UCS2')
```

#### Parameters

| Parameter | Tags | Description |
|-----------|------|-------------|
| appsid |  ``` Required ```  | A character string that uniquely identifies your app |
| msg |  ``` Required ```  | Message body supports both English and unicodes characters, concatenated messages is supported |
| to |  ``` Required ```  | Destination mobile number, mobile numbers must be in international format without 00 or + Example: (4452023498) |
| sender |  ``` Required ```  | The SenderID to send from, App default SenderID is used unless else stated |
| baseEncode |  ``` Optional ```  ``` DefaultValue ```  | Binary-to-text encoding schemes that represent binary data in an ASCII string format |
| encoding |  ``` Optional ```  ``` DefaultValue ```  | Converts information from a source into symbols for communication or storage, GSM7 for English and UCS2 for Arabic |



#### Example Usage

```php
$appsid = '6v253153s1g7831s5';
$msg = 'Test';
$to = 966505980169;
$sender = 'sender';
$baseEncode = false;
$encoding = 'UCS2';

$result = $wrapper->createSendMessage($appsid, $msg, $to, $sender, $baseEncode, $encoding);

```

#### Errors

| Error Code | Error Description |
|------------|-------------------|
| 401 | Authentication failed |
| 402 | Missing parameter AppSid |
| 459 | Authentication parameters are incorrectly base64 encoded |



[Back to List of Controllers](#list_of_controllers)



