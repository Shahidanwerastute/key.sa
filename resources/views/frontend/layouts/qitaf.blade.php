<?php $site_settings = custom::site_settings(); ?>
<style>
    .qitafArea > div {
        width: 100%;
    }
    .qitafContent {
        display: flex !important;
        align-items: center;
        margin-left: 24px;
    }
    .qitafContent .proCdSec {
        margin-right: 20px;
        margin-left: 20px;
    }
    .qitafContent .BtnNtXT{
        padding-top: 0;
    }
    .qitafLogo {
        height: 36px;
        width: 100px;
    }
</style>
<div class="promoCodeArea qitafArea forWebsite" onclick="showQitafModal();">
    <div class="qitafContent">
        <div class="logo-box-sec">
            <img src="{{custom::baseurl('public/frontend/images/qitaf-by-stc-logo-1.png?v=0.2')}}" class="qitafLogo">
        </div>
        <div class="proCdSec">
            <span class="qitafMsg" style="text-transform: none;">
                <?php echo sprintf(trans('labels.use_qitaf_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_qitaf_as_percentage . '%'); ?>
            </span>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="@lang('labels.qitaf_redeem')" class="edBtn redishButtonRound" style="width: 110px;"/>
        </div>

    </div>
    <div class="clearfix"></div>
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
                    <p>@lang('labels.enter_qitaf_registered_mobile')</p>
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
                    <p>@lang('labels.enter_qitaf_otp_received')</p>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="qitaf_otp"/>
                </div>
                <div>
                    <p>@lang('labels.qitaf_amount_to_redeem')</p>
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