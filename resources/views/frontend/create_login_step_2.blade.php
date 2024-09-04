@extends('frontend.layouts.template')

@section('content')

    <style>
        #new_registration_form input::placeholder{
            text-transform: unset !important;
        }
    </style>

    <?php
            // Following are being used to check if customer is coming from create login or from walkin signup
    if (isset($send_extra_sms) && $send_extra_sms == true) { // in case of walkin signup
        $send_extra_sms = 'yes';
        $walkin_record_id = $walkin_id;
    } else {
        $send_extra_sms = 'no';
        $walkin_record_id = 0;
    }
    ?>
    <section class="textBannerSec">
        <div class="container-md">
            <?php if(!isset($_GET['r'])){ ?>
            <h1>
            @lang('labels.create')
            <!--<strong>s</strong>-->
                <span>@lang('labels.login') </span>
            </h1>
                <?php } ?>
            <?php
            $site = custom::site_settings();
            ?>
            <?php if(isset($_GET['r'])){ ?>
            <h3><p> <?php echo str_replace("{perc}",$site->walkin_discount_percent."%",Lang::get('labels.create_login_with_walkin')) ?></p></h3>
            <?php }else{?>
            <p>@lang('labels.create_login_already_a_customer')</p>
            <?php } ?>
        </div>
    </section>
    <section class="standardPageSec BannerTextSty">
        <div class="container-md">
            <form action="<?php echo $lang_base_url; ?>/new_ind_user_signup" method="post" enctype="multipart/form-data"
                  id="new_registration_form" onsubmit="return false;" class="signup_form_new_user">
                <input type="hidden" name="send_extra_sms" value="<?php echo $send_extra_sms; ?>">
                <input type="hidden" name="walkin_record_id" value="<?php echo $walkin_record_id; ?>">
                <div class="regisNewUserPg">
                    <div class="whiteBox1240">
                        <div class="row noFloatingRow">

                            <div class="col-md-4 col-sm-6 isNoFloat">
                                <label>@lang('labels.email_address')</label>
                                <input type="email" placeholder="@lang('labels.write')" value="<?php echo $user_data->email; ?>"
                                       class="required checkEmailValid"
                                       name="email" id="email_required_for_validation"/>
                            </div>
                            <div class="col-md-4 col-sm-6 isNoFloat">
                                <label>@lang('labels.password') *</label>
                                <input type="password" placeholder="@lang('labels.write')" name="password"
                                       class="required password"/>
                            </div>
                            <div class="col-md-4 col-sm-6 isNoFloat">
                                <label>@lang('labels.confirm_password') *</label>
                                <input type="password" placeholder="@lang('labels.write')" name="confirm_password"
                                       class="required confirm_password"/>
                            </div>

                            <div class="col-md-4 col-sm-6 isNoFloat">
                                <label>@lang('labels.id_type')</label>
                                <select class="selectpicker id_type" disabled>
                                    <?php
                                    foreach ($id_types as $id_type)
                                    {
                                    if ($user_data->id_type == $id_type->ref_id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $id_type->ref_id; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <input type="hidden" name="id_type" value="<?php echo $user_data->id_type; ?>">
                            </div>
                            <?php
                            if ((isset($user_data->id_type) && $user_data->id_type == '68') || (isset($user_data->id_type) && $user_data->id_type == '243')) {
                                $id_version_style = '';
                                $class = 'required';
                                $license_no_style = 'style="display: none;';
                                $license_no_class = '';

                            } else {
                                $id_version_style = 'style="display: none;"';
                                $class = '';
                                $license_no_style = '';
                                $license_no_class = 'required';
                            }
                            ?>
                            <div class="col-md-4 col-sm-6 isNoFloat">
                                <label>@lang('labels.id_number')</label>
                                <input type="text" placeholder="@lang('labels.write')" class="required id_no"
                                       name="id_no" id="id_no_required_for_validation"
                                       value="<?php echo $user_data->id_no; ?>" readonly/>
                            </div>
                        </div>
                        <div class="regFormOne loginStep2">
                            <div class="row noFloatingRow">
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label>@lang('labels.first_name')</label>
                                    <input type="text" value="<?php echo $user_data->first_name; ?>" class="required"
                                           name="first_name" maxlength="20"/>
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label>@lang('labels.last_name')</label>
                                    <input type="text" value="<?php echo $user_data->last_name; ?>" class="required"
                                           name="last_name" maxlength="20"/>
                                </div>
                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label>@lang('labels.mobile_no')</label>
                                    <input type="text" value="<?php echo $user_data->mobile_no; ?>"
                                           class="phone required mobile_no number"/>
                                    <input type="hidden" name="mobile_no" class="intTelNo"
                                           value="<?php echo $user_data->mobile_no; ?>">
                                </div>

                                <div class="col-md-4 col-sm-6 isNoFloat">
                                    <label>@lang('labels.gender') *</label>
                                    <select class="selectpicker required" name="gender"
                                            onchange="hideShowLicenseField(this.value);">
                                        <?php
                                        $maleSelected = '';
                                        $femaleSelected = '';
                                        if ($user_data->gender == 'female') {
                                            $femaleSelected = 'selected';
                                        } else {
                                            $maleSelected = 'selected';
                                        }
                                        ?>
                                        <option value="male" <?php echo $maleSelected; ?>><?php echo($lang == 'eng' ? 'Male' : 'ذكر'); ?></option>
                                        <option value="female" <?php echo $femaleSelected; ?>><?php echo($lang == 'eng' ? 'Female' : 'أنثى'); ?></option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-6 isNoFloat" style="<?php echo $license_no_style; ?>">
                                    <label>@lang('labels.driving_license_number')</label>
                                    <input type="text" value="<?php echo $user_data->license_no; ?>"
                                           class="<?php echo $license_no_class;?>"
                                           name="license_no" id="license_no"/>
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
                                    <?php $api = custom::api_settings(); ?>
                                    <div class="g-recaptcha" data-sitekey="<?php echo $api->captcha_site_key;?>"></div>
                                </div>
                                <div class="col-md-12 subBtnSec">
                                    <input type="submit" class="edBtn submit_btn <?php echo (isset($_GET['r'])?'walkin_register':''); ?>" id="oldRegister"
                                           value="@lang('labels.register')"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="acRegistered" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">Success!</h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <p>Your Account has been registered as your account.</p>
                    <div class="twoBtnEd">
                        <br/>
                        <input type="submit" class="redishButtonRound submitBtn" value="CLOSE" data-bs-dismiss="modal"/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection