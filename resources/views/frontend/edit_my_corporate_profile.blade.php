@extends('frontend.layouts.template')

@section('content')
    <section class="textBannerSec">
        <div class="container-md">
        </div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                @include('frontend.layouts.corporate_profile_inner_section')
                <div class="myProfDetail">
                    <a href="<?php echo $lang_base_url; ?>/my-profile" class="back2Page"><img
                                src="<?php echo $base_url; ?>/public/frontend/images/goBack.png" alt="<--" height="26"
                                width="26"/> @lang('labels.go_back')</a>
                    <h1><strong><?php echo ($lang == 'eng' ? 'Edit' : 'تعديل'); ?> </strong> <?php echo ($lang == 'eng' ? 'My Profile' : 'ملفي الشخصي'); ?></h1>
                    <form action="<?php echo $lang_base_url; ?>/update_corporate_profile" method="post" class="edit_corporate_profile_form"
                          onsubmit="return false;">
                        <div class="row noFloatingRow">
                            <div class="col-lg-6 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Company Name En" : "اسم الشركة بالإنجليزي") ?></label>
                                <input type="text" name="company_name_en" value="<?php echo $user_data->company_name_en; ?>" class="required"/>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Company Name Ar" : "اسم الشركة بالعربي") ?></label>
                                <input type="text" name="company_name_ar" value="<?php echo $user_data->company_name_ar; ?>"  class="required"/>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Primary Contact Name" : "اسم المسؤول الأول") ?></label>
                                <input type="text" name="primary_name" value="<?php echo $user_data->primary_name; ?>" class="required"/>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Primary Position" : "منصب المسؤول الأول") ?></label>
                                <input type="text" name="primary_position" value="<?php echo $user_data->primary_position; ?>" class="required"/>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Primary Email" : "ايميل المسؤول الأول") ?></label>
                                <input type="text" name="primary_email" value="<?php echo $user_data->primary_email; ?>" class="required checkEmailValid"/>
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Primary Phone" : "رقم هاتف المسؤول الأول") ?></label>
                                <input type="text" value="<?php echo $user_data->primary_phone; ?>" class="corporate-phone-primary required number"/>
                                <input type="hidden" value="<?php echo $user_data->primary_phone; ?>" name="primary_phone" class="primary_inttelno">
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Secondary Contact Name" : "اسم المسؤول الثاني") ?></label>
                                <input type="text" name="secondary_name" value="<?php echo $user_data->secondary_name; ?>" />
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Secondary Position" : "منصب المسؤول الثاني") ?></label>
                                <input type="text" name="secondary_position" value="<?php echo $user_data->secondary_position; ?>" />
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Secondary Email" : "ايميل المسؤول الثاني") ?></label>
                                <input type="email" name="secondary_email" value="<?php echo $user_data->secondary_email; ?>" />
                            </div>
                            <div class="col-lg-3 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Secondary Phone" : "رقم هاتف المسؤول الثاني") ?></label>
                                <input type="text" value="<?php echo $user_data->secondary_phone; ?>" class="corporate-phone-secondary required number"/>
                                <input type="hidden" value="<?php echo $user_data->secondary_phone; ?>" name="secondary_phone" class="secondary_inttelno">
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label><?php echo ($lang == "eng" ? "Username" : "اسم المستخدم"); ?> </label>
                                <input type="email" name="username" value="<?php echo Session::get('user_email'); ?>"/>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 isNoFloat">
                                <label>@lang('labels.password') </label>
                                <input type="password" name="password"/>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $user_data->id; ?>">
                            <div class="col-sm-12 isNoFloat buttonArea">
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