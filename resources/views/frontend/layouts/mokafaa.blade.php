<?php $site_settings = custom::site_settings(); ?>
<style>
    .mokafaaArea > div {
        width: 100%;
    }
    .mokafaaContent {
        display: flex !important;
        align-items: center;
        margin-left: 24px;
    }
    .mokafaaContent .proCdSec {
        margin-right: 20px;
        margin-left: 20px;
    }
    .mokafaaContent .BtnNtXT{
        padding-top: 0;
    }
    .mokafaaLogo {
        height: 36px;
        width: 100px;
    }
</style>
<div class="promoCodeArea mokafaaArea forWebsite" onclick="mokafaa_get_access_token();">
    <div class="mokafaaContent">
        <div class="logo-box-sec">
            <img src="{{custom::baseurl('public/frontend/images/mokafaa.png?v=0.1')}}" class="mokafaaLogo">
        </div>
        <div class="proCdSec">
            <span class="mokafaaMsg" style="text-transform: none;">
                <?php echo sprintf(trans('labels.use_mokafaa_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_mokafaa_as_percentage . '%'); ?>
            </span>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="@lang('labels.mokafaa_redeem')" class="edBtn redishButtonRound" style="width: 110px;"/>
        </div>

    </div>
    <div class="clearfix"></div>
</div>

<div class="modal fade" id="mokafaaModal" tabindex="-1" role="dialog" aria-labelledby="mokafaaModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="mokafaaModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <p>@lang('labels.enter_mokafaa_registered_mobile')</p>
                </div>
                <input type="text" placeholder="@lang('labels.write') (e.g 966xxxxxxxxx)" class="number" id="mokafaa_mobile_number" maxlength="12"/>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="mokafaa_send_otp();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mokafaaOTPModal" tabindex="-1" role="dialog" aria-labelledby="mokafaaOTPModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="mokafaaOTPModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <p>@lang('labels.enter_mokafaa_otp_received')</p>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="mokafaa_otp"/>
                </div>
                <div>
                    <p>@lang('labels.mokafaa_amount_to_redeem')</p>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="mokafaa_amount"/>
                </div>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="mokafaa_initiate_redeem_request();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>