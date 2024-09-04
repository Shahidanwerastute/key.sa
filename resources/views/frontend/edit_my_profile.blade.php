@extends('frontend.layouts.template')

@section('content')
    <section class="textBannerSec">
        <div class="container-md">

        </div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                @include('frontend.layouts.profile_inner_section')
                <div class="myProfDetail">
                    <a href="<?php echo $lang_base_url; ?>/my-profile" class="back2Page"><img
                                src="<?php echo $base_url; ?>/public/frontend/images/goBack.png" alt="<--" height="26"
                                width="26"/> @lang('labels.go_back')</a>
                    <h1><strong><?php echo ($lang == 'eng' ? 'Edit' : 'تعديل'); ?> </strong> <?php echo ($lang == 'eng' ? 'My Profile' : 'ملفي الشخصي'); ?></h1>
                    <form action="<?php echo $lang_base_url; ?>/update_profile" method="post" class="edit_profile_form"
                          onsubmit="return false;">
                        <div class="row noFloatingRow">
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.first_name') <?php echo(isset($user_data->first_name) && $user_data->first_name != '' ? '*' : '') ?></label>
                                <input type="text" value="<?php echo $user_data->first_name; ?>" class="required"
                                       name="first_name" maxlength="20"/>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.last_name') <?php echo(isset($user_data->last_name) && $user_data->last_name != '' ? '*' : '') ?></label>
                                <input type="text" value="<?php echo $user_data->last_name; ?>" class="required"
                                       name="last_name" maxlength="20"/>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.job_title') <?php echo(isset($user_data->job_title) && $user_data->job_title != '' ? '*' : '') ?></label>
                                <select name="job_title"
                                        class="<?php echo(isset($user_data->job_title) && $user_data->job_title != '' ? 'required' : '') ?>">
                                    <?php
                                    foreach ($job_titles as $job_title)
                                    {
                                    if ($user_data->job_title == $job_title->oracle_reference_number) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }?>
                                    <option value="<?php echo $job_title->oracle_reference_number; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $job_title->eng_title : $job_title->arb_title); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>


                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.street_address') <?php echo(isset($user_data->street_address) && $user_data->street_address != '' ? '*' : '') ?></label>
                                <input type="text" placeholder="@lang('labels.write')" name="street_address"
                                       value="<?php echo $user_data->street_address; ?>"
                                       class="<?php echo(isset($user_data->street_address) && $user_data->street_address != '' ? 'required' : '') ?>"/>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.district_address') <?php echo(isset($user_data->district_address) && $user_data->district_address != '' ? '*' : '') ?></label>
                                <input type="text" placeholder="@lang('labels.write')" name="district_address"
                                       value="<?php echo $user_data->district_address; ?>"
                                       class="<?php echo(isset($user_data->district_address) && $user_data->district_address != '' ? 'required' : '') ?>"/>
                            </div>

                            <?php if (isset($user_data->id_type) && $user_data->id_type == '243')
                            {
                                $nationalityStyle = "style='display:none;'";
                            }else{
                                $nationalityStyle = '';
                            } ?>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat nationality_container" <?php //echo $nationalityStyle; ?> style="display: none;">
                                <label>@lang('labels.nationality') <?php echo(isset($user_data->nationality) && $user_data->nationality != '' ? '*' : '') ?></label>
                                <select name="nationality"
                                        class="nationality <?php echo(isset($user_data->nationality) && $user_data->nationality != '' ? 'required' : '') ?>">
                                    <option value="" disabled selected>@lang('labels.select')</option>
                                    <?php
                                    foreach ($nationalities as  $nationality)
                                    {
                                    if ($user_data->nationality == $nationality->oracle_reference_number) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $nationality->oracle_reference_number; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $nationality->eng_country_name : $nationality->arb_country_name); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.date_of_birth') <?php echo(isset($user_data->dob) && $user_data->dob != '' ? '*' : '') ?></label>
                                <input type="text"
                                       value="<?php if ($user_data->dob != '0000-00-00' && $user_data->dob != '1970-01-01') echo date('d-m-Y', strtotime($user_data->dob)); ?>"
                                       class="calender <?php if (isset($user_data->dob) && $user_data->dob != '0000-00-00' && $user_data->dob != '1970-01-01') echo 'required'; ?>"
                                       name="dob"/>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="margin: 8px 0 25px;">
                                <label>@lang('labels.mobile_no') <?php echo(isset($user_data->mobile_no) && $user_data->mobile_no != '' ? '*' : '') ?></label>
                                <input type="text" value="<?php echo $user_data->mobile_no; ?>"
                                       class="phone required mobile_no number"/>
                                <input type="hidden" name="mobile_no" class="intTelNo"
                                       value="<?php echo $user_data->mobile_no; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.id_type') <?php echo(isset($user_data->id_type) && $user_data->id_type != '' ? '*' : '') ?></label>
                                <select class="selectpicker required id_type" name="id_type"
                                        onchange="showIdVersionField($(this).val());validateIDNoField(this);" disabled style="background-color: lightgrey;">
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
                            </div>
                            <input type="hidden" name="id_type" value="{{$user_data->id_type}}">
                            <?php
                            if ((isset($user_data->id_type) && $user_data->id_type == '68') || (isset($user_data->id_type) && $user_data->id_type == '243')) {
                                $id_version_style = '';
                                $class = 'required';
                            } else {
                                $id_version_style = 'style="display: none;"';
                                $class = '';
                            }
                            ?>
                            <?php
                            if ((isset($user_data->id_type) && $user_data->id_type == '68')) {
                                $sponsor_style = '';
                                $sponsor_class = 'required';
                            } else {
                                $sponsor_style = 'style="display: none;"';
                                $sponsor_class = '';
                            }
                            ?>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat sponsor" <?php echo $sponsor_style; ?>>
                                <label>@lang('labels.sponsor') <?php echo(isset($user_data->sponsor) && $user_data->sponsor != '' ? '*' : '') ?></label>
                                <input type="text" value="<?php echo $user_data->sponsor; ?>" name="sponsor"
                                       id="sponsor_field <?php echo $sponsor_class; ?>"/>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.id_number') <?php echo(isset($user_data->id_no) && $user_data->id_no != '' ? '*' : '') ?></label>
                                <!-- <input type="text" placeholder="@lang('labels.write')" class="id_no" name="id_no" value="<?php echo $user_data->id_no; ?>"/> -->
                                <input type="text" value="{{$user_data->id_no}}" name="id_no" readonly style="background-color: lightgray;">
                                <input type="hidden" name="old_id_no" value="<?php echo $user_data->id_no; ?>">
                            </div>

                            <?php
                            $gSelected = 'selected';
                            $hSelected = '';
                            $requiredClass = '';
                            $id_expiry_date_formatted = '';
                            if (isset($user_data->id_date_type) && $user_data->id_date_type == 'G') {
                                $gSelected = 'selected';
                                $requiredClass = 'required';
                                $id_expiry_date_formatted = date('d-m-Y', strtotime($user_data->id_expiry_date));
                            } elseif (isset($user_data->id_date_type) && $user_data->id_date_type == 'H') {
                                $hSelected = 'selected';
                                $requiredClass = 'required';
                                $date_for_hijri = explode('-', $user_data->id_expiry_date);
                                //echo '<pre>';print_r($date_for_hijri);exit();
                                $id_expiry_date_formatted = $date_for_hijri[2] . '-' . $date_for_hijri[1] . '-' . $date_for_hijri[0];
                            }

                            ?>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.id_expiry_date') <?php echo(isset($user_data->id_expiry_date) && $user_data->id_expiry_date != '' ? '*' : '') ?></label>
                                <div class="sub-row">
                                    <select id="selectCalendar" class="selectpicker <?php echo $requiredClass; ?>"
                                            name="id_date_type">
                                        <option value="gregorian" <?php echo $gSelected; ?>>@lang('labels.gregorian')</option>
                                        <option value="islamic" <?php echo $hSelected; ?>>@lang('labels.islamic')</option>
                                    </select>
                                    <div class="id_expiry_date_field">
                                        <input type="text"
                                               value="<?php if ($user_data->id_expiry_date != '0000-00-00' && $user_data->id_expiry_date != '1970-01-01') echo $id_expiry_date_formatted; ?>"
                                               class="custom_calendar <?php if ($user_data->id_expiry_date != '0000-00-00' && $user_data->id_expiry_date != '1970-01-01') echo 'required'; ?>"
                                               name="id_expiry_date"/>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.id_country') <?php echo(isset($user_data->id_country) && $user_data->id_country != '' ? '*' : '') ?></label>
                                <select name="id_country"
                                        class="<?php echo(isset($user_data->id_country) && $user_data->id_country != '' ? 'required' : '') ?> id_country">
                                    <option value="" selected>@lang('labels.select')</option>
                                    <?php
                                    foreach ($countries as  $country)
                                    {
                                    if ($user_data->id_country == $country->oracle_reference_number) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $country->oracle_reference_number; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $country->eng_country : $country->arb_country); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.copy_of_id_card') <?php echo(isset($user_data->id_image) && $user_data->id_image != '' ? '*' : '') ?>
                                    <span>&nbsp;(<?php echo($lang == 'eng' ? 'Only Images or PDF are allowed' : 'تقبل فقط صور و PDF'); ?>
                                        )</span></label>
                                <div class="fileUploader">
                                    <input type="text" class="showFileName" value="<?php echo $user_data->id_image; ?>"
                                           disabled>
                                    <input type="hidden" name="old_id_image"
                                           value="<?php echo $user_data->id_image; ?>">
                                    <input type="button" class="edBtn showFileType" value="@lang('labels.upload')"/>
                                    <input type="file" class="edBtn attachFile" name="id_image"
                                           accept="application/pdf, image/*">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.license_id_type') <?php echo(isset($user_data->license_id_type) && $user_data->license_id_type != '' ? '*' : '') ?></label>
                                <select class="selectpicker required-optional license_id_type" name="license_id_type">
                                    <option value="" selected>@lang('labels.select')</option>
                                    <?php
                                    foreach ($license_id_types as $license_id_type)
                                    {
                                    if ($user_data->license_id_type == $license_id_type->ref_id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $license_id_type->ref_id; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $license_id_type->eng_title : $license_id_type->arb_title); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.driving_license_number') <?php echo(isset($user_data->license_no) && $user_data->license_no != '' ? '*' : '') ?></label>
                                <input type="text" value="<?php echo $user_data->license_no; ?>" name="license_no"
                                       class="validate_license_number <?php echo($user_data->license_no != '' ? 'required' : ''); ?>"/>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.driving_license_expiry_date') <?php echo(isset($user_data->license_expiry_date) && $user_data->license_expiry_date != '' ? '*' : '') ?></label>
                                <input type="text"
                                       value="<?php if (isset($user_data->license_expiry_date) && $user_data->license_expiry_date != '0000-00-00' && $user_data->license_expiry_date != '1970-01-01') echo date('d-m-Y', strtotime($user_data->license_expiry_date)); ?>"
                                       class="datepicker_future_date <?php if ($user_data->license_expiry_date != '0000-00-00' && $user_data->license_expiry_date != '1970-01-01') echo 'required'; ?>"
                                       name="license_expiry_date"/>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.license_country') <?php echo(isset($user_data->license_country) && $user_data->license_country != '' ? '*' : '') ?></label>
                                <select name="license_country"
                                        class="<?php echo(isset($user_data->license_country) && $user_data->license_country != '' ? 'required' : '') ?> license_country">
                                    <option value="" selected>@lang('labels.select')</option>
                                    <?php
                                    foreach ($countries as  $country)
                                    {
                                    if ($user_data->license_country == $country->oracle_reference_number) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $country->oracle_reference_number; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $country->eng_country : $country->arb_country); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat" style="display: none;">
                                <label>@lang('labels.copy_of_driving_license') <?php echo(isset($user_data->license_image) && $user_data->license_image != '' ? '*' : '') ?>
                                    <span>&nbsp;(<?php echo($lang == 'eng' ? 'Only Images or PDF are allowed' : 'تقبل فقط صور و PDF'); ?>
                                        )</span></label>
                                <div class="fileUploader">
                                    <input type="text" class="showFileName"
                                           value="<?php echo $user_data->license_image; ?>" disabled>
                                    <input type="hidden" name="old_license_image"
                                           value="<?php echo $user_data->license_image; ?>">
                                    <input type="button" class="edBtn showFileType" value="@lang('labels.upload')"/>
                                    <input type="file" class="edBtn attachFile" name="license_image"
                                           accept="application/pdf, image/*">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.email_address') <?php echo(isset($user_data->email) && $user_data->email != '' ? '*' : '') ?></label>
                                <input type="email" value="<?php echo $user_data->email; ?>" class="required checkEmailValid"
                                       name="email"/>
                                <input type="hidden" name="old_email" value="<?php echo $user_data->email; ?>">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.password') </label>
                                <input type="password" name="password"/>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $user_data->id; ?>">

                            <div class="col-sm-12 col-12 isNoFloat buttonArea">
                                <input type="submit" value="<?php echo($lang == 'eng' ? 'Update' : 'تعديل'); ?>"
                                       class="edBtn redishButtonRound submitBtn submit_btn license_validate_btn">
                                <a href="<?php echo $lang_base_url; ?>/my-profile"><input type="button"
                                                                                          value="<?php echo($lang == 'eng' ? 'DISCARD' : 'إلغاء'); ?>"
                                                                                          class="edBtn grayishButton"/></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection