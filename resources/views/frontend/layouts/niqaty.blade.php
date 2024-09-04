<?php $site_settings = custom::site_settings(); ?>
<style>
    .niqatyArea > div {
        width: 100%;
    }
    .niqatyContent {
        display: flex !important;
        align-items: center;
        margin-left: 24px;
    }
    .niqatyContent .proCdSec {
        margin-right: 20px;
        margin-left: 20px;
    }
    .niqatyContent .BtnNtXT{
        padding-top: 0;
    }
    .niqatyLogo {
        width: 7%;
    }

    .niqaty-redeem-option {
        opacity: 1 !important;
    }
</style>
<div class="promoCodeArea niqatyArea forWebsite" onclick="show_niqaty_modal();">
    <div class="niqatyContent">
        <img src="{{custom::baseurl('public/frontend/images/niqaty-logo.png?v=0.2')}}" class="niqatyLogo">
        <div class="proCdSec">
            <span class="niqatyMsg" style="text-transform: none;">
                <?php echo sprintf(trans('labels.use_niqaty_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_niqaty_as_percentage . '%'); ?>
            </span>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="@lang('labels.niqaty_redeem')" class="edBtn redishButtonRound" style="width: 110px;"/>
        </div>

    </div>
    <div class="clearfix"></div>
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
                    <p>@lang('labels.enter_niqaty_registered_mobile')</p>
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