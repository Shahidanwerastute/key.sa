@extends('frontend.layouts.template')
@section('content')
    <section class="myAccountSec">
        <div class="myAccountWrapper">
            <div class="myProfDetail">
                <div style="margin-bottom: 20px;">
                    <h4>
                        <?php echo $content[$lang.'_title']; ?>
                    </h4>
                    <p><?php echo $content[$lang.'_desc']; ?></p>
                </div>
                <form action="<?php echo $lang_base_url.'/refunds'; ?>" method="post" class="refundForm">
                    <div class="row noFloatingRow">
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'First Name' : 'الاسم الاول'); ?> *</label>
                            <input type="text" name="first_name" placeholder="@lang('labels.write')" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Father Name' : 'اسم الاب'); ?> *</label>
                            <input type="text" name="father_name" placeholder="@lang('labels.write')" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Last Name' : 'اسم العائلة'); ?> *</label>
                            <input type="text" name="last_name" placeholder="@lang('labels.write')" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Customer ID Number' : 'رقم الهوية'); ?> *</label>
                            <input type="text" name="customer_id" placeholder="@lang('labels.write')" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Mobile Number' : 'رقم الهاتف'); ?> *</label>
                            <input type="text" name="mobile" class="number" placeholder="966xxxxxxxxx" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Bank Name' : 'اسم البنك'); ?> *</label>
                            <input type="text" name="bank_name" placeholder="@lang('labels.write')" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'IBAN' : 'رقم الآيبان'); ?> *</label>
                            <input type="text" name="iban" class="number fixIBANText" placeholder="@lang('labels.write')" value="SA" />
                        </div>
                        <div class="col-md-4 col-sm-6 isNoFloat">
                            <label><?php echo ($lang == 'eng' ? 'Booking Number' : 'رقم الحجز'); ?> *</label>
                            <input type="text" name="booking_id" placeholder="@lang('labels.write')" value="{{isset($_REQUEST['booking']) && $_REQUEST['booking'] != '' ? $_REQUEST['booking'] : ''}}" {{isset($_REQUEST['booking']) && $_REQUEST['booking'] != '' ? 'readonly' : ''}} />
                        </div>
                        <div class="col-sm-12 col-xs-12 isNoFloat" style="margin-bottom: 20px;">
                            <div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
                        </div>
                        <div class="col-sm-12 col-xs-12 isNoFloat buttonArea" style="margin-bottom: 20px;">
                            <input type="submit" value="@lang('labels.submit_btn')" class="edBtn submit_btn redishButtonRound">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection