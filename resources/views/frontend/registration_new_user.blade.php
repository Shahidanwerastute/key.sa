@extends('frontend.layouts.template')

@section('content')
    <style>
        .regFormOne input::placeholder{
            text-transform: unset !important;
        }
    </style>
    <?php
    $api = custom::api_settings();
    ?>
    <section class="textBannerSec">
        <div class="container-md">
            <h1>
                @lang('labels.register')
                        <!--<strong>s</strong>-->
                <span>@lang('labels.new_user')</span>
            </h1>
            <p><?php echo($lang == 'eng' ? 'Register now to start making reservations and use the services efficiently. It would help your process of
                making reservations faster and easier.' : 'سجل الآن للحجز واستخدام الخدمات الخاصة بالموقع. حيث ان التسجيل في الموقع يسرع عملية الحجز و توفر لكم الوقت.
'); ?> </p>
        </div>
    </section>
    <section class="standardPageSec BannerTextSty">
        <div class="container-md">
            <form action="<?php echo $lang_base_url; ?>/new_ind_user_signup" method="post" enctype="multipart/form-data"
                  id="new_registration_form" onsubmit="return false;" class="signup_form_new_user">
                <div class="regisNewUserPg">
                    <div class="whiteBox1240">
                        <p><?php echo($lang == 'eng' ? 'Please Fill the form to Register.' : 'يرجى ملء النموذج للتسجيل.
'); ?></p>
                        <div class="regFormOne">


                            <div class="row noFloatingRow">

                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.first_name') *</label>
                                    <input type="text" placeholder="@lang('labels.write')" name="first_name"
                                           class="required first_name" maxlength="20"/>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.last_name') *</label>
                                    <input type="text" placeholder="@lang('labels.write')" name="last_name"
                                           class="required last_name" maxlength="20"/>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.mobile_no') *</label>
                                    <input type="text"
                                           class="phone required mobile_no number"/>
                                </div>
                                <input type="hidden" name="mobile_no" class="intTelNo">

                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.gender') *</label>
                                    <select class="selectpicker required" name="gender" onchange="hideShowLicenseField(this.value);">
                                        <option value="">@lang('labels.select')</option>
                                        <option value="male"><?php echo ($lang == 'eng' ? 'Male' : 'ذكر'); ?></option>
                                        <option value="female"><?php echo ($lang == 'eng' ? 'Female' : 'أنثى'); ?></option>
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.email_address') *</label>
                                    <input type="email" placeholder="@lang('labels.write')" name="email"
                                           class="required email checkEmailValid" id="email_required_for_validation" />
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.password') *</label>
                                    <input type="password" placeholder="@lang('labels.write')" name="password"
                                           class="required password"/>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.confirm_password') *</label>
                                    <input type="password" placeholder="@lang('labels.write')" name="confirm_password"
                                           class="required confirm_password"/>
                                </div>

                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.id_type') *</label>
                                    <select class="selectpicker required id_type" name="id_type"
                                            onchange="showIdVersionField($(this).val());validateIDNoField(this.value);">
                                        <option value="" selected>@lang('labels.select')</option>
                                        <?php
                                        foreach ($id_types as $id_type)
                                        { ?>
                                        <option value="<?php echo $id_type->ref_id; ?>"><?php echo($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat">
                                    <label>@lang('labels.id_number') *</label>
                                    <input type="text" placeholder="@lang('labels.write')" name="id_no" class="id_no" id="id_no_required_for_validation"/>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat license_no_for_hide_show">
                                    <label>@lang('labels.driving_license_number') *</label>
                                    <input type="text" placeholder="@lang('labels.write')" name="license_no"
                                           class="required license_no validate_license_number"/>
                                </div>
                                <div class="col-lg-4 col-md-6 isNoFloat sponsor" style="display: none;">
                                    <label>@lang('labels.sponsor') *</label>
                                    <input type="text" placeholder="@lang('labels.write')" name="sponsor"
                                            id="sponsor_field"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="checkBox">
                                        <input id="iAccept" type="checkbox" name="accept_terms" value="1"
                                               class="required accept_terms"><label
                                                for="iAccept">@lang('labels.i_accept_the') </label>
                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                           data-bs-target="#term_n_Cond">@lang('labels.terms_and_conditions'). *</a>
                                    </p>
                                </div>
                                <div class="col-md-12 subBtnSec">
                                    <div class="g-recaptcha" data-sitekey="<?php echo $api->captcha_site_key;?>"></div>
                                </div>
                                <div class="col-md-12 subBtnSec">
                                    <input type="submit" class="edBtn registerBtn submitBtn submit_btn license_validate_btn" id="oldRegister"
                                           value="@lang('labels.register')"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection