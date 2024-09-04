<?php $site_settings = custom::site_settings(); ?>
<style>
    .qitafArea > div {
        width: 100%;
    }

    .qitafLogoDiv {
        text-align: center;
    }

    .mobileLogo .qitafLogo {
        width: 100%;
        max-width: 90px;
    }
    .customBtn{
        position: relative !important;
        text-align: center;
        top: inherit !important;
        right: inherit !important;
        margin-bottom: 5px;
    }
    .customBtn .edBtn{
        float: none !important;
    }
    .qitafLogoDiv{
        margin-bottom: 5px;
    }

    .qitafLogoDiv.mobileLogo {
        {{--position: absolute;--}}
        {{--top: 50%;--}}
        {{--<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 6px;--}}
        {{--z-index: 1;--}}
        {{--text-align: <?php echo ($lang == 'eng' ? 'left' : 'right'); ?>;--}}
        {{--transform: translate(0px, -64%);--}}
    }
    .qitafMsg{
        text-align: center;
        margin-bottom: 5px;
    }
    .qitafApplied {
        max-width: 72% !important;
        margin-right: 0 !important;
    }
</style>
<div class="promoCodeArea qitafArea forMobile">

    <div class="proCdSec">
        <div class="qitafLogoDiv mobileLogo">
            <img src="{{custom::baseurl('public/frontend/images/qitaf-by-stc-logo-1.png?v=0.2')}}" class="qitafLogo">
        </div>
        <div class="qitafMsg">
            <?php echo sprintf(trans('labels.use_qitaf_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_qitaf_as_percentage . '%'); ?>
        </div>
        <div class="BtnNtXT customBtn">
            <input type="button" value="@lang('labels.qitaf_redeem')" class="edBtn redishButtonRound" onclick="showQitafModal();" style="min-width: 100px;"/>
        </div>
    </div>
</div>

<div class="modal fade" id="qitafModal" tabindex="-1" role="dialog" aria-labelledby="qitafModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="qitafModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <span>@lang('labels.enter_qitaf_registered_mobile')</span>
                </div>
                <input type="text" placeholder="@lang('labels.write') (e.g 512345678)" class="number" id="qitaf_mobile_number" maxlength="9"/>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="qitafSendOTP();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qitafOTPModal" tabindex="-1" role="dialog" aria-labelledby="qitafOTPModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="qitafOTPModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <span>@lang('labels.enter_qitaf_otp_received')</span>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="qitaf_otp"/>
                </div>
                <br>
                <div>
                    <span>@lang('labels.qitaf_amount_to_redeem')</span>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="qitaf_amount"/>
                </div>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="qitafSendRedeemRequest();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>