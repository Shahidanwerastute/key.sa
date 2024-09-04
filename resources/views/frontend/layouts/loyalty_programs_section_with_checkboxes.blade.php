<div class="payFrmUserInfo custom-border-bot">
    <div class="paymentOption heading">
        <ul>
            <li>
                <h4>@lang('labels.loyalty_program_lbl')</h4>
            </li>
            <li style="text-align: center;"></li>
        </ul>
    </div>
    <div class="paymentOption objects">
        <ul>
            <?php
            foreach ($loyalty_programs as $loyalty_program)
            {
            ?>
            <li class="p-method loyalty_programs_divs <?php echo($loyalty_program->is_default == 1 ? 'active' : ''); ?>" style="<?php echo($loyalty_program->is_default == 1 ? 'width: 100% !important;' : ''); ?>">
                <input id="loyalty_program_<?php echo $loyalty_program->id; ?>" name="loyalty_program_id"
                       value="<?php echo $loyalty_program->id; ?>"
                       type="radio" <?php echo($loyalty_program->is_default == 1 ? 'checked' : ''); ?>>
                <label for="loyalty_program_<?php echo $loyalty_program->id; ?>">
                    <div class="imgBox">
                        <img src="<?php echo custom::baseurl() . '/public/uploads/' . $loyalty_program->image; ?>"
                             alt="<?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?>"
                             width="35" height="26">
                    </div>
                </label>
                <p><?php echo($lang == 'eng' ? $loyalty_program->eng_title : $loyalty_program->arb_title); ?></p>
            </li>
            <?php }
            ?>
        </ul>
    </div>
</div>