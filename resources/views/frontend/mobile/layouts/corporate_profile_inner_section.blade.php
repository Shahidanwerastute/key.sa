<?php
$profileActive = '';
$myBookingsActive = '';
$myHistoryActive = '';
$segments = Request::segments();
$last_segment = end($segments);
/*echo prev($segments);
echo '<pre>';print_r($segments);exit();*/
if ($last_segment == 'my-profile' || $last_segment == 'edit-my-profile') {
    $profileActive = 'active';
} elseif ($last_segment == 'my-bookings' || prev($segments) == 'booking-detail' || prev($segments) == 'manage-booking') {
    $myBookingsActive = 'active';
} elseif ($last_segment == 'my-history') {
    $myHistoryActive = 'active';
}
$data = custom::loggedInUserProfileInnerInfo("corporate");
if ($data['user_data']) {
    $user_data = $data['user_data'];
    if ($user_data->membership_level == 'Bronze' || $user_data->membership_level == '') // For Silver Type
    {
        $label = ($lang == 'eng' ? 'Bronze Points' : 'نقاط برونزية');
        $image_path = $base_url.'/public/frontend/images/bronze_card_img.png?v=0.1';
    }elseif ($user_data->membership_level == 'Silver') // For Silver Type
    {
        $label = ($lang == 'eng' ? 'Silver Points' : 'مجموع النقاط');
        $image_path = $base_url.'/public/frontend/images/silver_card_img.png?v=0.1';
    }elseif ($user_data->membership_level == 'Golden') // For Golden Type
    {
        $label = ($lang == 'eng' ? 'Golden Points' : 'غولدنبوانتس');
        $image_path = $base_url.'/public/frontend/images/golden_card_img.png?v=0.1';
    }elseif ($user_data->membership_level == 'Platinum') // For Platinum Type
    {
        $label = ($lang == 'eng' ? 'Platinum Points' : 'النقاط البلاتينية');
        $image_path = $base_url.'/public/frontend/images/platinum_card_img.png?v=0.1';
    }
} else {
    $label = ($lang == 'eng' ? 'Bronze Points' : 'نقاط برونزية');
    $image_path = $base_url.'/public/frontend/images/bronze_card_img.png?v=0.1';
}
?>

<div class="accountTopHead">
    <div class="accMeny_Details">
        <div class="rightDetails">
            <ul>
                <li>
                    <h3>
                        <strong><?php echo Session::get('user_name'); ?></strong>
                    </h3>
                </li>
                <?php if (isset($user_data->loyalty_points)) { ?>
                <li>
                    <h3><?php echo $label; ?></h3>
                    <p><?php echo $user_data->loyalty_points; ?></p>
                </li>
                <?php } ?>
                <?php if(1 == 2){ ?>
                <li>
                    <h3><?php echo($lang == 'eng' ? 'EXPIRES' : 'إكسيريس'); ?></h3>
                    <p>200</p>
                </li>
                <li>
                    <h3><?php echo($lang == 'eng' ? 'In' : 'في'); ?></h3>
                    <p>1 <?php echo($lang == 'eng' ? 'Month' : 'شهر'); ?></p>
                </li>
                <?php } ?>
            </ul>
            <div class="booking-count">
                <h3><?php echo($lang == 'eng' ? 'BOOKINGS' : 'الحجز'); ?></h3>
                <p><?php echo $data['user_bookings_count']; ?></p>
            </div>
        </div>
        <div class="leftMenu">
            <ul>
                <li class="<?php echo $profileActive; ?>">
                    <a href="<?php echo $lang_base_url; ?>/my-profile">
                        <?php echo($lang == 'eng' ? 'Profile' : 'الملف الشخصي'); ?>
                    </a>
                </li>
                <li class="<?php echo $myBookingsActive; ?>">
                    <a href="<?php echo $lang_base_url; ?>/my-bookings">
                        <?php echo($lang == 'eng' ? 'Bookings' : 'الحجوزات'); ?>
                    </a>
                </li>
<!--                <li class="<?php echo $myHistoryActive; ?>">
                    <a href="<?php echo $lang_base_url; ?>/my-history">
                        <?php echo($lang == 'eng' ? 'History' : 'الحجوزات السابقة'); ?>
                    </a>
                </li>-->
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>