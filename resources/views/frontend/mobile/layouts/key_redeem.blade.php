<!-- Start of section for redeem offer -->
<style>
    .payLoginFrm .keyRedeemPointsArea input,
    .payLoginFrm .keyRedeemAmountArea input {
        width: 100%;
    }
</style>

<?php if ($redeem_offer_mode_type_from_backend == 'tabular') { ?>
<div class="promoCodeArea redeemApplySection forWebsite redeemPaymentOption keyArea">
    <div class="qitafContent" style="width: 100%;">
        <div class="logo-box-sec">
            <img src="https://kra.ced.sa/public/frontend/images/key-redeem.png" class="qitafLogo">
        </div>

        <?php if ($booking_info['is_delivery_mode'] != 2 && $booking_info['is_delivery_mode'] != 4 && $redeem_offer_mode_from_backend == 'on' && $canUseRedeemOffer && $redeemPointsAvailableForUser > 0 && !Session::has('coupon_applied'))
        { ?>
        <div class="qitafMsg" style="text-transform: none;">
            <?php
            if ($lang == 'eng') {
                echo 'Use ' . $customer_redeemable_amount . ' SAR for redeem of ' . $customer_redeemable_points . ' points';
            } else {
                echo 'استخدم ' . $customer_redeemable_amount . ' ريال باستبدال ' . $customer_redeemable_points . ' نقطة';
            }
            ?>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="<?php echo($lang == 'eng' ? 'Key Redeem' : 'نقاط المفتاح'); ?>"
                   class="edBtn redishButtonRound" style="width: 110px;"
                   autocomplete="off" data-bs-toggle="modal" data-bs-target="#redeem-modal">
        </div>
        <?php } ?>

    </div>
    <div class="clearfix"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="redeem-modal" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- modal header  -->
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">
                    <?php
                    if ($lang == 'eng')
                    { ?>
                    You have <span
                            style="font-size: 19px;"><?php echo number_format($redeemPointsAvailableForUser); ?></span>
                    points
                    <?php }else{ ?>
                    لديك <span
                            style="font-size: 19px;"><?php echo number_format($redeemPointsAvailableForUser); ?></span>
                    نقطة
                    <?php } ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="payLoginFrm">
                    <form action="<?php echo $lang_base_url; ?>/loginOnPayment" method="post" class="innerWrapFrm"
                          id="loginOnPayment"
                          onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-6 keyRedeemPointsArea">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><?php echo($lang == 'eng' ? 'Points' : 'نقطة'); ?></label>
                                        <input type="text"
                                               title="<?php echo($lang == 'eng' ? 'Enter number of points you want to use for redeem.' : 'يمكنك إدخال النقاط المراد إستبدالها'); ?>"
                                               class="amount_or_points number"
                                               value="<?php echo($customer_redeemable_points > 0 ? $customer_redeemable_points : ''); ?>"
                                               placeholder="<?php echo($lang == 'eng' ? 'REDEEM POINTS' : 'نقاط الخصم'); ?>"
                                               id="points_to_redeem"/>
                                    </div>
                                    <div class="col-md-12">
                                        <p id="showPointsAgainstAmount" style="    font-size: 11px;margin-top: 12px;">
                                            <?php
                                            if ($lang == 'eng') {
                                                echo 'You can enter the number of points you want to redeem. <span style="color: #9F073F;font-weight: bold;">(Max: ' . (int)$MaxRedeemablePoints . ' Points)</span>';
                                            } else {
                                                echo 'يمكنك إدخال النقاط المراد إستبدالها <span style="color: #9F073F;font-weight: bold;">(كحد اعلى: ' . (int)$MaxRedeemablePoints . ' النقاط)</span>';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 keyRedeemAmountArea">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><?php echo($lang == 'eng' ? 'Amount (SAR)' : 'المبلغ (ريال سعودي)'); ?></label>
                                        <input type="text"
                                               title="<?php echo($lang == 'eng' ? 'Enter amount you want to redeem.' : 'يمكنك إدخال المبلغ المراد إستبداله ليتم حساب النقاط'); ?>"
                                               class="amount_or_points number"
                                               value="<?php echo($customer_redeemable_amount > 0 ? $customer_redeemable_amount : ''); ?>"
                                               placeholder="<?php echo($lang == 'eng' ? 'REDEEM AMOUNT' : 'مبلغ الخصم'); ?>"
                                               id="amount_to_redeem"/>
                                    </div>
                                    <div class="col-md-12">
                                        <p id="showPointsAgainstAmount" style="    font-size: 11px;margin-top: 12px;">
                                            <?php
                                            if ($lang == 'eng') {
                                                echo 'You can enter the amount you want to redeem. <span style="color: #9F073F;font-weight: bold;">(Max: ' . $MaxRedeemableAmount . ' SAR)</span>';
                                            } else {
                                                echo 'يمكنك إدخال المبلغ المراد إستبداله ليتم حساب النقاط <span style="color: #9F073F;font-weight: bold;">(كحد اعلى: ' . $MaxRedeemableAmount . ' ريال سعودي)</span>';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <strong class="text-center btn-block" style="color: #444;font-size:17px;margin:5px 0;">
                                    <?php
                                    if ($lang == 'eng') {
                                        echo 'The Maximum amount you can redeem is ' . $MaxRedeemableAmount . ' SAR for ' . $DaysForRedeem . ' days.';
                                    } else {
                                        echo 'أكبر مبلغ للإسترداد ' . $MaxRedeemableAmount . ' ريال لـ ' . $DaysForRedeem . ' يوم';
                                    }
                                    ?>
                                </strong>
                            </div>
                            <div class="col-md-12">
                                <input type="button"
                                       value="<?php echo($lang == 'eng' ? 'Calculate' : 'حساب'); ?>"
                                       class="edBtn redishButtonRound hide"
                                       style="padding-left: 5px; padding-right: 5px;"
                                       id="calculateRedeemBtn"/>
                                <input type="button" value="@lang("labels.apply")"
                                       class="edBtn redishButtonRound "
                                       id="applyRedeemBtn"
                                       style="<?php echo($showApplyBtn ? '' : 'display: none;'); ?>"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
<?php } else { ?>
<div class="promoCodeArea redeemApplySection forWebsite redeemPaymentOption keyArea">
    <div class="qitafContent" style="width: 100%;">
        <div class="logo-box-sec">
            <img src="https://kra.ced.sa/public/frontend/images/key-redeem.png" class="qitafLogo">
        </div>

        <?php if ($booking_info['is_delivery_mode'] != 2 && $booking_info['is_delivery_mode'] != 4 && $redeem_offer_mode_from_backend == 'on' && $canUseRedeemOffer && $redeemPointsAvailableForUser > 0 && !Session::has('coupon_applied'))
        { ?>
        <div>
            <div class="qitafMsg" style="text-transform: none;">
                <?php
                if ($lang == 'eng') {
                    echo 'Use ' . $customer_redeemable_amount . ' SAR for redeem of ' . $customer_redeemable_points . ' points';
                } else {
                    echo 'استخدم ' . $customer_redeemable_amount . ' ريال باستبدال ' . $customer_redeemable_points . ' نقطة';
                }
                ?>
            </div>
        </div>
        <div class="BtnNtXT">
            <input type="button" value="<?php echo ($lang == 'eng' ? 'Key Redeem' : 'نقاط المفتاح'); ?>"
                   class="edBtn redishButtonRound "
                   id="applyRedeemBtn"
                   style="<?php echo($showApplyBtn ? 'display: block;' : 'display: none;'); ?>"/>
            <input type="hidden" id="points_to_redeem"
                   value="<?php echo($customer_redeemable_points > 0 ? $customer_redeemable_points : ''); ?>"/>
            <input type="hidden" id="amount_to_redeem"
                   value="<?php echo($customer_redeemable_amount > 0 ? $customer_redeemable_amount : ''); ?>"/>
        </div>
        <?php } ?>

    </div>
    <div class="clearfix"></div>
</div>
<?php } ?>


<input type="hidden" id="total_points" value="<?php echo $redeemPointsAvailableForUser; ?>">
<input type="hidden" name="conversion_type" id="conversion_type" value="amount_to_points">
<input type="hidden" name="all_ok_to_redeem" id="all_ok_to_redeem" value="no">
<input type="hidden" id="customer_redeem_loyalty_type"
       value="<?php echo $redeemLoyaltyTypeForUser; ?>">
<!-- End of section for redeem offer -->

