@extends('frontend.layouts.template')

@section('content')
<style>
    #create_individual_login input::placeholder{
        text-transform: unset !important;
    }
</style>
    <section class="textBannerSec">
        <div class="container-md">
            <h1>
                @lang('labels.create')
                <!--<strong>s</strong>-->
                <span>@lang('labels.login') </span>
            </h1>
            <p>@lang('labels.create_login_already_a_customer')</p>
        </div>
    </section>
    <section class="standardPageSec BannerTextSty">
        <div class="container-md">
            <!-- Form Start -->
            <form action="<?php echo $lang_base_url; ?>/create_login_find_user" method="post" id="create_individual_login" onsubmit="return false;">
                <div class="regisNewUserPg">
                    <div class="whiteBox1240">
                        <p>@lang('labels.enter_id_to_check_in_db')</p>
                        <div class="row noFloatingRow">
                            <div class="col-md-5 col-sm-6 isNoFloat">
                                <label>@lang('labels.id_type')</label>
                                <select class="selectpicker required id_type" name="id_type">
                                    <option value="" selected>@lang('labels.select')</option>
                                    <?php
                                    foreach ($id_types as $id_type)
                                    { ?>
                                    <option value="<?php echo $id_type->ref_id; ?>"><?php echo ($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-5 col-sm-6 isNoFloat">
                                <label>@lang('labels.id_number')</label>
                                <input type="text" placeholder="@lang('labels.write')" class="required id_no" name="id_no"/>
                            </div>
                            <div class="col-md-2 col-sm-12 isNoFloat createLogin subBtnSec">
                                <input type="submit" class="edBtn" id="" value="@lang('labels.search_my_profile')"/>
                                {{--<input type="button" class="edBtn" id="" value="SEARCH MY PROFILE" data-bs-toggle="modal" data-bs-target="#loginVerify"/>--}}
                            </div>

                        </div>
                    </div>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </section>


@endsection