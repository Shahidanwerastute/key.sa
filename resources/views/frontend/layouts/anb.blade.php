<?php $site_settings = custom::site_settings(); ?>
<style>
    .anbArea > div {
        width: 100%;
    }
    .anbContent {
        display: flex !important;
        align-items: center;
        margin-left: 24px;
    }
    .anbContent .proCdSec {
        margin-right: 20px;
        margin-left: 20px;
    }
    .anbContent .BtnNtXT{
        padding-top: 0;
    }
    .anbLogo {
        height: 36px;
        width: 100px;
    }
    #sameOTPMessage {
        color: red;
    }
</style>
<div class="promoCodeArea anbArea forWebsite" onclick="anb_get_access_token();">
    <div class="anbContent">
        <div class="logo-box-sec">
            <img src="{{custom::baseurl('public/frontend/images/anb.png?v=0.3')}}" class="anbLogo">
        </div>
        <div class="proCdSec">
            <span class="anbMsg" style="text-transform: none;">
                <?php echo sprintf(trans('labels.use_anb_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_anb_as_percentage . '%'); ?>
            </span>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="@lang('labels.anb_redeem')" class="edBtn redishButtonRound" style="width: 110px;"/>
        </div>

    </div>
    <div class="clearfix"></div>
</div>

<div class="modal fade" id="anbModal" tabindex="-1" role="dialog" aria-labelledby="anbModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="anbModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <p>@lang('labels.enter_anb_registered_mobile')</p>
                </div>
                <input type="text" placeholder="@lang('labels.write') (e.g 966xxxxxxxxx)" class="number" id="anb_mobile_number" maxlength="12"/>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="anb_send_otp();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="anbOTPModal" tabindex="-1" role="dialog" aria-labelledby="anbOTPModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="anbOTPModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <p>@lang('labels.enter_anb_otp_received')</p>
                    <span id="sameOTPMessage" style="display: none;">@lang('labels.last_sent_otp_is_still_valid')</span>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="anb_otp"/>
                </div>
                <div>
                    <p>@lang('labels.anb_amount_to_redeem')</p>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="anb_amount"/>
                </div>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="anb_initiate_redeem_request();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>