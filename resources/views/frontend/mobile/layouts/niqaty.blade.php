<?php $site_settings = custom::site_settings(); ?>
<style>
    .niqatyArea > div {
        width: 100%;
    }

    .niqatyLogoDiv {
        text-align: center;
    }

    .mobileLogo .niqatyLogo {
        width: 100%;
        max-width: 89px;
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
    .niqatyLogoDiv{
        margin-bottom: 5px;
    }

    .niqatyLogoDiv.mobileLogo {
        {{--position: absolute;--}}
        {{--top: 50%;--}}
        {{--<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 6px;--}}
        {{--z-index: 1;--}}
        {{--text-align: <?php echo ($lang == 'eng' ? 'left' : 'right'); ?>;--}}
        {{--transform: translate(0px, -64%);--}}
    }

    .niqatyMsg{
        text-align: center;
        margin-bottom: 5px;
    }
    .niqatyApplied {
        max-width: 72% !important;
        margin-right: 0 !important;
    }

    .niqaty-redeem-option {
        opacity: 1 !important;
    }
</style>
<div class="promoCodeArea niqatyArea forMobile">
    <div class="proCdSec">
        <div class="niqatyLogoDiv mobileLogo">
            <img src="{{custom::baseurl('public/frontend/images/niqaty-logo.png?v=0.2')}}" class="niqatyLogo">
        </div>
        <div class="niqatyMsg">
            <?php echo sprintf(trans('labels.use_niqaty_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_niqaty_as_percentage . '%'); ?>
        </div>
        <div class="BtnNtXT customBtn">
            <input type="button" value="@lang('labels.niqaty_redeem')" class="edBtn redishButtonRound" onclick="show_niqaty_modal();" style="min-width: 100px;"/>
        </div>
    </div>
</div>

<div class="modal fade" id="niqatyModal" tabindex="-1" role="dialog" aria-labelledby="niqatyModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="niqatyModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <span>@lang('labels.enter_niqaty_registered_mobile')</span>
                </div>
                <input type="text" placeholder="@lang('labels.write') (e.g 966xxxxxxxxx)" class="number" id="niqaty_mobile_number" maxlength="12"/>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="get_niqaty_redeem_options();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="niqatyRedeemOptionsModal" tabindex="-1" role="dialog" aria-labelledby="niqatyRedeemOptionsModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="niqatyRedeemOptionsModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div id="niqatyRedeemOptions"></div>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="authorize_niqaty_redeem_request();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="niqatyOTPModal" tabindex="-1" role="dialog" aria-labelledby="niqatyOTPModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="niqatyOTPModalLabel">@lang('labels.continue')</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    <p>@lang('labels.enter_niqaty_otp_received')</p>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="niqaty_otp"/>
                    <input type="hidden" id="niqaty_transaction_reference_number"/>
                    <input type="hidden" id="request_data"/>
                </div>
                <div class="twoBtnEd">
                    <br/>
                    <button type="button" class="redishButtonRound" onclick="confirm_niqaty_redeem_request();">@lang('labels.submit_btn')</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>