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
        margin-<?php echo ($lang == 'eng' ? 'right' : 'left'); ?>: 30px;
    }
    .loyaltyProgContent .BtnNtXT{
        padding-top: 0;
        width: 25%;
    }
</style>
<div class="promoCodeArea loyaltyProgArea forWebsite">
    <div class="loyaltyProgContent">
        <div class="proCdSec">
            <span class="loyaltyProgMsg" style="text-transform: none;">@lang('labels.loyalty_program_lbl')</span>
        </div>
        <div class="BtnNtXT">
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
    <div class="clearfix"></div>
</div>