@extends('frontend.layouts.template')
@section('content')
    <style>
        .is-mada-card{
            background:url("https://kra.ced.sa/public/frontend/images/icon-mada-logo.png?v=0.3") no-repeat {{$lang == 'eng' ? 'right' : 'left'}} center;
            background-position-x: {{$lang == 'eng' ? '98' : '2'}}%;
        }
    </style>
<section class="pricePageSec">
    <div class="container" style="max-width: 615px;">
        <div class="pricePgWrapper ">
            <?php
            $getSegment = $lang == 'eng'?Request::segment(2):Request::segment(1);
            if(isset($_GET['mobile'])) {
                $transaction_id = $_GET['TransactionID'];
                $merchantID = $merchantID;
                $amount = $_GET['Amount'];
                $currencyISOCode = "682";
                $messageID = "1";
                $quantity = "1";
                $channel = "0";
                $itemID = "130";
                $paymentMethod = "1";
                $paymentDescription = "Payment";
                $lang = $_GET['Language'];
                $themeID = "1000000001";
                $get_ammount = (number_format($amount, 2, '.', ''))*100;
                $button_price_text = (number_format($amount, 2, '.', ''));

            }elseif(isset($sts_error) && $sts_error!=""){

                $transaction_id = $Response_TransactionID;
                $merchantID = $merchantID; //"010000066";
                $amount = $_REQUEST['Response_Amount'];
                $currencyISOCode = "682";
                $messageID = "1";
                $quantity = "1";
                $channel = "0";
                $itemID = "130";
                $paymentMethod = "1";
                $paymentDescription = "Payment";
                $lang = $lang;
                $themeID = "1000000001";
                $get_ammount = $amount; //in response from sts we are already getting it by 100 multiplied.
                $button_price_text = $amount/100;
            }
            ?>
            <div class="rightCol" style="width: 100%;">
                <?php
                $PaymentParams = [];
                $paymentParameters = [];
                $paymentParameters["TransactionID"] = $transaction_id;
                $paymentParameters["MerchantID"] = $merchantID;
                $paymentParameters["Amount"] = $get_ammount;
                $paymentParameters["CurrencyISOCode"] = $currencyISOCode;
                $paymentParameters["MessageID"] = $messageID;
                $paymentParameters["Quantity"] = $quantity;
                $paymentParameters["Channel"] = $channel;
                $paymentParameters["ItemID"] = $itemID;
                $paymentParameters["PaymentMethod"] = $paymentMethod;
                $paymentParameters["PaymentDescription"] = $paymentDescription;
                $paymentParameters["Language"] = $lang == "eng"?"En":"Ar";
                $paymentParameters["ThemeID"] = $themeID;

                if($lang == 'arb'){
                    $paymentParameters["ResponseBackURL"] = $base_url."/mada-payment";
                } else {
                    $paymentParameters["ResponseBackURL"] = $base_url."/en/mada-payment";
                }

                $paymentParameters["Version"] = "2.0";
                $paymentParameters["RedirectURL"] = $sts_payment_link;

                $PaymentParams = $paymentParameters;
                $PaymentParams["SecureHash"] = custom::generate_token(array(
                    'Amount' => $paymentParameters["Amount"],
                    'Channel' => $paymentParameters["Channel"],
                    'CurrencyISOCode' => $paymentParameters["CurrencyISOCode"],
                    'Language' => $paymentParameters["Language"],
                    'MerchantID' => $paymentParameters["MerchantID"],
                    'MessageID' => $paymentParameters["MessageID"],
                    'ThemeID' => $paymentParameters["ThemeID"],
                    'ItemID' => $paymentParameters["ItemID"],
                    'PaymentDescription' => urlencode($paymentParameters["PaymentDescription"]),
                    'PaymentMethod' => $paymentParameters["PaymentMethod"],
                    'Quantity' => $paymentParameters["Quantity"],
                    'ResponseBackURL' => $paymentParameters["ResponseBackURL"],
                    'TransactionID' => $paymentParameters["TransactionID"],
                    'Version' => $paymentParameters["Version"],
                ));

                $redirectURL = (String) $PaymentParams["RedirectURL"];
                $amount = (String) $PaymentParams["Amount"];
                $currencyCode = (String) $PaymentParams["CurrencyISOCode"];
                $transactionID = (String) $PaymentParams["TransactionID"];
                $merchantID = (String) $PaymentParams["MerchantID"];
                $language = (String) $PaymentParams["Language"];
                $messageID = (String) $PaymentParams["MessageID"];
                $secureHash = (String) $PaymentParams["SecureHash"];
                $themeID = (String) $PaymentParams["ThemeID"];
                $ItemID = (String) $PaymentParams["ItemID"];
                $PaymentDescription = (String) $PaymentParams["PaymentDescription"];
                $responseBackURL = (String) $PaymentParams["ResponseBackURL"];
                $channel = (String) $PaymentParams["Channel"];
                $quantity = (String) $PaymentParams["Quantity"];
                $version = (String) $PaymentParams["Version"];
                $paymentMethod = (String) $PaymentParams["PaymentMethod"];
                ?>
                <!--<h1>
                    <?php //echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                    <img src="<?php //echo $base_url;?>/public/frontend/images/payment_method_icon.png" style="width: 212px;margin-left: 10px;">
                </h1>-->
                <div class="container-fluid STS_express_checkout">
                    <?php if(isset($sts_error)){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $sts_error; ?>
                    </div>
                    <?php } ?>
                    <div class="creditCardForm">
                        <div class="payment">
                            <form action="<?php echo $redirectURL?>" name="redirectForm" id="stsPayOne" method="post">
                                <input name="MerchantID" type="hidden" value="<?php echo $merchantID; ?>"/>
                                <input name="Amount" type="hidden" value="<?php echo $amount; ?>"/>
                                <input name="CurrencyISOCode" type="hidden" value="<?php echo $currencyCode?>"/>
                                <input name="Language" type="hidden" value="<?php echo $language?>"/>
                                <input name="MessageID" type="hidden" value="<?php echo $messageID?>"/>
                                <input name="TransactionID" type="hidden" value="<?php echo $transactionID?>"/>
                                <input name="ItemID" type="hidden" value="<?php echo $ItemID?>"/>
                                <input name="ThemeID" type="hidden" value="<?php echo $themeID?>"/>
                                <input name="ResponseBackURL" type="hidden" value="<?php echo $responseBackURL?>"/>
                                <input name="Quantity" type="hidden" value="<?php echo $quantity?>"/>
                                <input name="Channel" type="hidden" value="<?php echo $channel?>"/>
                                <input name="Version" type="hidden" value="<?php echo $version?>"/>
                                <input name="PaymentMethod" type="hidden" value="<?php echo $paymentMethod?>"/>
                                <input name="PaymentDescription" type="hidden" value="<?php echo $PaymentDescription?>"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group owner">
                                            <label for="owner">@lang('labels.cardholders_full_name')</label>
                                            <input type="text" style="font-size:16px;" name="CardHolderName" class="form-control" id="owner">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="cardNumber">@lang('labels.credit_card_number')</label>
                                            <input type="text" style="font-size:16px;" name="CardNumber" class="form-control" id="cardNumber" maxlength="16" onkeyup="check_if_mada_card($(this).val());">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group CVV">
                                            <label for="cvv">@lang('labels.cvc_number')</label>
                                            <input type="text" style="font-size:16px;" name="SecurityCode" class="form-control" id="cvv" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <label>@lang('labels.expiry_date')</label>
                                        <div class="form-group" id="expiration-date">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select name="ExpiryDateMonth" style="font-size:16px;">
                                                        <?php
                                                        $startMonth = '1';
                                                        $endMonth = '12';
                                                        $currentMonth = date('m');
                                                        for($startMonth;$startMonth<=$endMonth;$startMonth++){
                                                        $startMonth = $startMonth < 10?'0'.$startMonth:$startMonth;
                                                        $monthName = custom::month_english($startMonth);
                                                        if($lang == 'ar'){
                                                            $monthName = custom::month_arabic($monthName);
                                                        }
                                                        $selectedMonth = $startMonth == $currentMonth?'selected':'';
                                                        ?>
                                                        <option <?php echo $selectedMonth; ?> value="<?php echo $startMonth; ?>"><?php echo $startMonth; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select name="ExpiryDateYear" style="font-size:16px;">
                                                        <?php
                                                        $startYear = date('Y');
                                                        $endYear = $startYear+10;
                                                        for($startYear;$startYear<=$endYear;$startYear++){
                                                        ?>
                                                        <option value="<?php echo substr($startYear, -2); ?>"><?php echo $startYear; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input name="SecureHash" type="hidden" value="<?php echo $secureHash ?>"/>
                                    <div class="col-md-12">
                                        <div class="form-group" id="pay-now">
                                            <input type="submit" class="btn yellowButton" id="confirm-purchase" value="@lang('labels.pay_now') <?php echo $button_price_text; ?> @lang('labels.currency_text')">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="hdn_booking_id" value="<?php echo Session::get('booking_id'); ?>">
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection