<style>
    .qitafArea > div {
        width: 100%;
    }

    .qitafLogoDiv {
        text-align: center;
    }

    .mobileLogo .qitafLogo {
        width: 100%;
        max-width: 86px;
    }

    .customBtn {
        position: relative !important;
        text-align: center;
        top: inherit !important;
        right: inherit !important;
        margin-bottom: 5px;
    }

    .customBtn .edBtn {
        float: none !important;
    }

    .qitafLogoDiv {
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

    .qitafMsg {
        text-align: center;
        margin-bottom: 5px;
    }

    .qitafApplied {
        max-width: 72% !important;
        margin-right: 0 !important;
    }
</style>
<style>
    .loyaltyProgArea > div {
        width: 100%;
    }

    .loyaltyProgContent {
        display: flex !important;
        align-items: center;
        margin-left: 24px;
    }

    .loyaltyProgContent .proCdSec {
        margin- <?php echo ($lang == 'eng' ? 'right' : 'left'); ?>: 30px;
    }

    .loyaltyProgContent .BtnNtXT {
        padding-top: 0;
        width: 25%;
    }
</style>
<div class="promoCodeArea loyaltyProgArea forMobile">
    <h4>@lang('labels.loyalty_program_lbl')</h4>
    <div class="proCdSec"
         style="padding: 5px !important; overflow: hidden !important; position: relative !important; border-radius: 4px;border: none; box-shadow: none; background: none;">
        <div class="BtnNtXT customBtn" style="left: 0;right: 0;">
            <select class="selectpicker" name="loyalty_program_id">
                <?php
                foreach ($loyalty_programs as $loyalty_program)
                {
                if ($loyalty_program->is_default == '1') {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                ?>
                <option value="<?php echo $loyalty_program->id; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?></option>
                <?php }
                ?>
            </select>
        </div>
    </div>
</div>