<?php $site_settings = custom::site_settings(); ?>
<style>
    .anbArea > div {
        width: 100%;
    }

    .anbLogoDiv {
        text-align: center;
    }

    .mobileLogo .anbLogo {
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
    .anbLogoDiv{
        margin-bottom: 5px;
    }

    .anbLogoDiv.mobileLogo {
    {{--position: absolute;--}}
    {{--top: 50%;--}}
    {{--<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 6px;--}}
    {{--z-index: 1;--}}
    {{--text-align: <?php echo ($lang == 'eng' ? 'left' : 'right'); ?>;--}}
    {{--transform: translate(0px, -64%);--}}
}
    .anbMsg{
        text-align: center;
        margin-bottom: 5px;
    }
    .anbApplied {
        max-width: 72% !important;
        margin-right: 0 !important;
    }
</style>
<div class="promoCodeArea anbArea forMobile">

    <div class="proCdSec">
        <div class="anbLogoDiv mobileLogo">
            <img src="{{custom::baseurl('public/frontend/images/anb.png?v=0.3')}}" class="anbLogo">
        </div>
        <div class="anbMsg">
            <?php echo sprintf(trans('labels.use_anb_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_anb_as_percentage . '%'); ?>
        </div>
        <div class="BtnNtXT customBtn">
            <input type="button" value="@lang('labels.anb_redeem')" class="edBtn redishButtonRound" onclick="anb_get_access_token();" style="min-width: 100px;"/>
        </div>
    </div>
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
                    <span>@lang('labels.enter_anb_registered_mobile')</span>
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
                    <span>@lang('labels.enter_anb_otp_received')</span>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="anb_otp"/>
                </div>
                <br>
                <div>
                    <span>@lang('labels.anb_amount_to_redeem')</span>
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