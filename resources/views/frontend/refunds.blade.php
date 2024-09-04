@extends('frontend.layouts.template')
@section('content')
    <section class="textBannerSec" >
        <div class="container-md">
            <h1>
                <?php echo $content[$lang.'_title']; ?>
            </h1>
            <p><?php echo $content[$lang.'_desc']; ?></p>
        </div>
    </section>
    <section class="standardPageSec BannerTextSty">
        <div class="container-md">
            <form action="<?php echo $lang_base_url.'/refunds'; ?>" method="post" class="refundForm">
                <div class="regisNewUserPg">
                    <div class="whiteBox1240">
                        <div class="regFormOne">
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
                            </div>
                            <div class="row" style="margin-top: 26px;">
                                <div class="col-md-12 subBtnSec">
                                    <div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
                                </div>
                                <div class="col-md-12 subBtnSec text-left">
                                    <input type="submit" class="edBtn submit_btn" value="@lang('labels.submit_btn')" />
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection