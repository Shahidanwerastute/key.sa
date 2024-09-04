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
            <form action="<?php echo $lang_base_url.'/guar-refunds'; ?>" method="post" class="refundForm">
                <div class="regisNewUserPg">
                    <div class="whiteBox1240">
                        <div class="regFormOne">
                            <div class="row noFloatingRow">
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'Contract Number' : 'رقم الحجز'); ?> *</label>
                                    <input type="text" name="contract_number" placeholder="@lang('labels.write')" value="{{$contract_number}}" readonly/>
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'Bank Name' : 'اسم البنك'); ?> *</label>
                                    <input type="text" name="bank_name" placeholder="@lang('labels.write')" />
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'IBAN' : 'رقم الآيبان'); ?> *</label>
                                    <input type="text" name="iban" placeholder="@lang('labels.write')" />
                                </div>

                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'Bank Country' : 'بنك الدولة'); ?> *</label>
                                    <select name="bank_country">
                                        <option value="">{{($lang == 'eng' ? 'Select Country' : 'حدد الدولة')}}</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->eng_country}}">{{($lang == 'eng' ? $country->eng_country : $country->arb_country)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'Bank Address' : 'عنوان البنك'); ?> *</label>
                                    <input type="text" name="bank_address" placeholder="@lang('labels.write')" />
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label><?php echo ($lang == 'eng' ? 'Bank Swift Code' : 'كود Swift للمصرف'); ?> *</label>
                                    <input type="text" name="bank_swift_code" placeholder="@lang('labels.write')" />
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