<style>
    .customBtn {
        position: relative !important;
        text-align: center;
        top: inherit !important;
        right: inherit !important;
        margin-bottom: 5px;
    }


    .loyaltyProgArea > div {
        width: 100%;
    }
    .paymentsOptionsList {
        flex-wrap: nowrap !important;
    }
</style>
<div class="promoCodeArea loyaltyProgArea forMobile MobLoyaltyEarnSec">
    <h4 class="payment-page-labels">@lang('labels.loyalty_program_lbl')</h4>
    <div class="proCdSec"
         style="border: none; box-shadow: none; background: none;">
        <div class="BtnNtXT customBtn" style="left: 0;right: 0;">
            <div class="paymentOption objects">

                    <?php
                    foreach ($loyalty_programs as $loyalty_program)
                    {
                        if($loyalty_program->is_default == 1){
                    ?>
                            <ul class="paymentsOptionsList">
                                <li class="p-method" style="<?php echo($loyalty_program->is_default == 1 ? 'width: 100% !important;' : ''); ?>">
                                    <input id="loyalty_program_<?php echo $loyalty_program->id; ?>" name="loyalty_program_id"
                                           value="<?php echo $loyalty_program->id; ?>"
                                           type="radio" <?php echo($loyalty_program->is_default == 1 ? 'checked' : ''); ?>>
                                    <label for="loyalty_program_<?php echo $loyalty_program->id; ?>">
                                        <div class="imgBox" style="margin-top: 10px;">
                                            <img src="<?php echo custom::baseurl() . '/public/uploads/' . $loyalty_program->image; ?>"
                                                 alt="<?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?>"
                                                 width="35" height="26">
                                        </div>
                                        <p style="margin-top: 7px;"><?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?></p>
                                    </label>
                                </li>
                            </ul>
                    <?php }
                        }
                    ?>

                    <ul class="paymentsOptionsList">
                        <?php
                        foreach ($loyalty_programs as $loyalty_program)
                        {
                            if($loyalty_program->is_default != 1){
                                ?>
                                <li class="p-method">
                                    <input id="loyalty_program_<?php echo $loyalty_program->id; ?>" name="loyalty_program_id"
                                           value="<?php echo $loyalty_program->id; ?>"
                                           type="radio" <?php echo($loyalty_program->is_default == 1 ? 'checked' : ''); ?>>
                                    <label for="loyalty_program_<?php echo $loyalty_program->id; ?>">
                                        <div class="imgBox" style="margin-top: 10px;">
                                            <img src="<?php echo custom::baseurl() . '/public/uploads/' . $loyalty_program->image; ?>"
                                                 alt="<?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?>"
                                                 width="35" height="26">
                                        </div>
                                        <p style="margin-top: 7px;"><?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?></p>
                                    </label>
                                </li>
                            <?php }
                        }
                        ?>
                    </ul>
            </div>
        </div>
    </div>
</div>