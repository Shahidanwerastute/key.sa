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
                <form action="<?php echo $lang_base_url.'/guar-refunds'; ?>" method="post" class="refundForm">
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