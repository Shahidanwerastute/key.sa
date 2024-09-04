<?php $site_settings = custom::site_settings(); ?>
<style>
    .mokafaaArea > div {
        width: 100%;
    }

    .mokafaaLogoDiv {
        text-align: center;
    }

    .mobileLogo .mokafaaLogo {
        width: 100%;
        max-width: 76px;
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
    .mokafaaLogoDiv{
        margin-bottom: 5px;
    }

    .mokafaaLogoDiv.mobileLogo {
    {{--position: absolute;--}}
    {{--top: 50%;--}}
    {{--<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 6px;--}}
    {{--z-index: 1;--}}
    {{--text-align: <?php echo ($lang == 'eng' ? 'left' : 'right'); ?>;--}}
    {{--transform: translate(0px, -64%);--}}
}
    .mokafaaMsg{
        text-align: center;
        margin-bottom: 5px;
    }
    .mokafaaApplied {
        max-width: 72% !important;
        margin-right: 0 !important;
    }
</style>
<div class="promoCodeArea mokafaaArea forMobile">

    <div class="proCdSec">
        <div class="mokafaaLogoDiv mobileLogo">
            <img src="{{custom::baseurl('public/frontend/images/mokafaa.png?v=0.1')}}" class="mokafaaLogo">
        </div>
        <div class="mokafaaMsg">
            <?php echo sprintf(trans('labels.use_mokafaa_account_to_redeem'), $site_settings->amount_to_be_redeemed_by_mokafaa_as_percentage . '%'); ?>
        </div>
        <div class="BtnNtXT customBtn">
            <input type="button" value="@lang('labels.mokafaa_redeem')" class="edBtn redishButtonRound" onclick="mokafaa_get_access_token();" style="min-width: 100px;"/>
        </div>
    </div>
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
                    <span>@lang('labels.enter_mokafaa_registered_mobile')</span>
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
                    <span>@lang('labels.enter_mokafaa_otp_received')</span>
                    <input type="text" placeholder="@lang('labels.write')" class="number" id="mokafaa_otp"/>
                </div>
                <br>
                <div>
                    <span>@lang('labels.mokafaa_amount_to_redeem')</span>
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