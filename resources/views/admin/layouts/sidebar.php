<aside id="sidebar_main">


    <div class="sidebar_main_header">


        <div class="sidebar_logo" style="height: 76px !important;">


            <a href="<?php echo custom::baseurl('/'); ?>/admin/dashboard" class="sSidebar_hide sidebar_logo_large">


                <img class="logo_regular" src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/logo_main.png?v=0.1" alt=""

                     style="height: auto; width: 100%; max-width: 200px; margin-top: 20px;"/>


                <img class="logo_light" src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/logo_main_white.png"

                     alt="" height="15" width="71"/>


            </a>


            <a href="<?php echo custom::baseurl('/'); ?>/admin/dashboard" class="sSidebar_show sidebar_logo_small">


                <img class="logo_regular" src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/logo_main_small.png?v=0.1"

                     alt="" height="32" width="32"/>


                <img class="logo_light"


                     src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/logo_main_small_light.png" alt=""

                     height="32" width="32"/>


            </a>


        </div>


        <!--<div class="sidebar_actions">



            <select id="lang_switcher" name="lang_switcher">



                <option value="gb" selected>English</option>



            </select>



        </div>-->


    </div>


    <div class="menu_section">


        <ul>


            <!--for dashboard-->

            <li class="<?php echo($main_section == 'dashboard' ? 'current_section' : ''); ?>" title="Dashboard">


                <a href="<?php echo custom::baseurl('/'); ?>/admin/dashboard" title="Dashboard">


                    <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>


                    <span class="menu_title">Dashboard</span>


                </a>


            </li>


            <!--for bookings-->
            <?php if (custom::rights(1, 'view')) { ?>
                <li class="<?php echo($main_section == 'bookings' ? 'current_section' : ''); ?>" title="Bookings">
                    <a href="<?php echo custom::baseurl('/'); ?>/admin/bookings" title="View All Bookings">
                        <span class="menu_icon"><i class="material-icons">&#xE85C;</i></span>
                        <span class="menu_title">Bookings</span>
                    </a>
                </li>
            <?php } ?>


            <?php if (custom::rights(58, 'view')) { ?>
                <li class="<?php echo($main_section == 'booking_added_payments' ? 'current_section' : ''); ?>" title="Booking Added Payments">
                    <a href="<?php echo custom::baseurl('/'); ?>/admin/booking-added-payments" title="View All Booking Added Payments">
                        <span class="menu_icon"><i class="material-icons">&#xE85C;</i></span>
                        <span class="menu_title">Added Payments</span>
                    </a>
                </li>
            <?php } ?>


            <?php if (custom::rights(62, 'view')) { ?>
                <li class="<?php echo($main_section == 'manage_bookings' ? 'current_section' : ''); ?>" title="Manage Booking">
                    <a href="<?php echo custom::baseurl('/'); ?>/admin/manage-bookings" title="Manage Booking">
                        <span class="menu_icon"><i class="material-icons">&#xE85C;</i></span>
                        <span class="menu_title">Manage Bookings</span>
                    </a>
                </li>
            <?php } ?>

            <?php if (custom::rights(7, 'view')) { ?>
                <li class="<?php echo($main_section == 'inquiries' ? 'current_section' : ''); ?>" title="inquiries">
                    <a href="<?php echo custom::baseurl('/admin/inquiries'); ?>" title="View All Inquiries">
                        <span class="menu_icon"><i class="material-icons">&#xE8AF;</i></span>
                        <span class="menu_title">Inquiries</span>
                    </a>
                </li>
            <?php } ?>

            <?php if (custom::rights(8, 'view')) { ?>
                <li class="<?php echo($main_section == 'career' ? 'current_section' : ''); ?>" title="career">
                    <a href="<?php echo custom::baseurl('admin/career'); ?>" title="View All Career Inquiries">
                        <span class="menu_icon"><i class="material-icons">&#xE8C3;</i></span>
                        <span class="menu_title">Careers</span>
                    </a>
                </li>
            <?php } ?>


            <!--for bookings engine-->
            <?php if (custom::rights(14, 'view') || custom::rights(15, 'view') || custom::rights(16, 'view') || custom::rights(17, 'view') || custom::rights(18, 'view')) { ?>
                <li class="<?php echo($main_section == 'booking_engine' ? 'current_section act_section' : ''); ?>"

                    title="Booking Engine">


                    <a href="javascript:void(0);">


                        <span class="menu_icon"><i class="material-icons">&#xE8C0;</i></span>


                        <span class="menu_title">Booking Engine</span>


                    </a>


                    <ul>


                        <?php if (custom::rights(14, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'branches' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/branch"
                                        title="Manage Branches">Branches</a></li>
                        <?php } ?>
                        <?php if (custom::rights(15, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'models' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/car_model" title="Manage Car Models">Car

                                    Models</a></li>
                        <?php } ?>
                        <?php if (custom::rights(18, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'pricing' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/pricing"
                                        title="Manage Car Related Data">Car

                                    Pricing & Availability</a></li>
                        <?php } ?>
                        <?php if (custom::rights(16, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'dropoff_charges' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/dropoff_charges"
                                        title="Manage Drop-off Charges">Dropoff

                                    Charges</a></li>
                        <?php } ?>
                        <?php if (custom::rights(17, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'promotions_offers' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/promotions_offers"
                                        title="Manage Promotions & Offers">Promotions & Offers</a></li>
                        <?php } ?>


                    </ul>


                </li>
            <?php } ?>

            <!--for car selling-->
            <?php if (custom::rights(37, 'view') || custom::rights(38, 'view') || custom::rights(39, 'view')) { ?>
                <li class="<?php echo($main_section == 'car_selling' ? 'current_section act_section' : ''); ?>"
                    title="Surveys" style="display: block;">
                    <a href="javascript:void(0);">
                        <span class="menu_icon"><i class="material-icons">&#xE54E;</i></span>
                        <span class="menu_title">Car Selling</span>
                    </a>
                    <ul>
                        <?php if (custom::rights(37, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'manage_page_content' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/car-selling/page-content"
                                   title="Manage Content">Manage Content</a></li>
                        <?php } ?>
                        <?php if (custom::rights(38, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'manage_cars' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/car-selling/manage-cars" title="Manage Cars">Manage
                                    Cars</a></li>
                        <?php } ?>
                        <?php if (custom::rights(39, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'selling_responses' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/car-selling/responses"
                                   title="View Responses">
                                    View Responses
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <!--corporate sales-->
            <!--Before Ahsan-->
            <?php /*if (custom::rights(37, 'view') || custom::rights(38, 'view') || custom::rights(39, 'view')) {*/ ?>
            <!--<li class="<?php //echo($main_section == 'corporate_sales' ? 'current_section act_section' : ''); ?>"
                    title="Surveys" style="display: block;">
                    <a href="javascript:void(0);">
                        <span class="menu_icon"><i class="material-icons">&#xE8B0;</i></span>
                        <span class="menu_title">Corporate Sales</span>
                    </a>
                    <ul>
                        <?php /*if (custom::rights(37, 'view')) { */ ?>
                            <li class="<?php //echo($inner_section == 'manage_sales_content' ? 'act_item' : ''); ?>">
                                <a href="<?php //echo custom::baseurl('/'); ?>/admin/corporate-sales/page-content" title="Manage Content">Manage Content</a></li>
                        <?php /*}*/ ?>

                        <?php /*if (custom::rights(39, 'view')) {*/ ?>
                            <li class="<?php //echo($inner_section == 'sales_responses' ? 'act_item' : ''); ?>">
                                <a href="<?php //echo custom::baseurl('/'); ?>/admin/corporate-sales/responses" title="View Responses">
                                    View Responses
                                </a>
                            </li>
                        <?php /*}*/ ?>
                    </ul>
                </li>-->
            <?php /*}*/ ?>
            <!--end corporate sales-->

            <!--corporate sales-->
            <!--After Ahsan-->
            <?php if (custom::rights(43, 'view')) { ?>
                <li class="<?php echo($main_section == 'corporate_sales' ? 'current_section act_section' : ''); ?>"
                    title="Surveys" style="display: block;">
                    <a href="javascript:void(0);">
                        <span class="menu_icon"><i class="material-icons">&#xE8B0;</i></span>
                        <span class="menu_title">Corporate Sales</span>
                    </a>
                    <ul>
                        <?php if (custom::rights(43, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'manage_sales_content' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/corporate-sales/page-content"
                                   title="Manage Content">Manage Content</a></li>
                        <?php } ?>

                        <?php if (custom::rights(43, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'sales_responses' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/corporate-sales/responses"
                                   title="View Responses">
                                    View Responses
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <!--end corporate sales-->

            <!--for surveys-->
            <?php if (custom::rights(31, 'view') || custom::rights(32, 'view') || custom::rights(33, 'view')) { ?>
                <li class="<?php echo($main_section == 'survey' ? 'current_section act_section' : ''); ?>"
                    title="Surveys" style="display: block;">
                    <a href="javascript:void(0);">
                        <span class="menu_icon"><i class="material-icons">&#xE85C;</i></span>
                        <span class="menu_title">Surveys</span>
                    </a>
                    <ul>

                        <?php if (custom::rights(31, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'manage_survey' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/survey" title="Manage Surveys">Manage
                                    Survey</a></li>
                        <?php } ?>

                        <?php if (custom::rights(32, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'survey_reports' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/survey/reports"
                                   title="See Website / Mobile Survey Reports">
                                    Survey Reports
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(33, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'oasis_survey_reports' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/survey/oasis-reports"
                                   title="See Oasis Survey Reports">
                                    Oasis Survey Reports
                                </a>
                            </li>
                        <?php } ?>


                    </ul>

                </li>
            <?php } ?>

            <?php if (custom::rights(9, 'view')) { ?>
                <li class="<?php echo($main_section == 'admin_users' ? 'current_section' : ''); ?>" title="Admins">


                    <a href="<?php echo custom::baseurl('/'); ?>/admin/admins" title="Manage Admin Users">


                        <span class="menu_icon"><i class="material-icons">&#xE853;</i></span>


                        <span class="menu_title">Admin Users</span>


                    </a>


                </li>
            <?php } ?>


            <!--for registered users-->
            <?php if (custom::rights(10, 'view') || custom::rights(20, 'view')) { ?>
                <li class="<?php echo($main_section == 'registered_users' ? 'current_section act_section' : ''); ?>"

                    title="Registered Users">


                    <a href="javascript:void(0);">


                        <span class="menu_icon"><i class="material-icons">&#xE87C;</i></span>


                        <span class="menu_title">Customers</span>


                    </a>


                    <ul>


                        <?php if (custom::rights(10, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'individual_customers' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/individual_customer"

                                        title="Manage Individual Customers Information">Individual</a></li>
                        <?php } ?>


                        <?php if (custom::rights(20, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'corporate_customers' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/corporate_customer"

                                        title="Manage Corporate Customers Information">Corporate</a></li>
                        <?php } ?>


                        <?php if (custom::rights(51, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'super_corporate_customers' ? 'act_item' : ''); ?>">
                                <a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/super_corporate_customer"

                                        title="Manage Super Corporate Customers Information">Super Corporate</a></li>
                        <?php } ?>


                    </ul>


                </li>
            <?php } ?>


            <!--for pages-->
            <?php if (custom::rights(11, 'view')) { ?>
                <li class="<?php echo($main_section == 'pages' ? 'current_section act_section' : ''); ?>"
                    title="CMS Pages">

                    <a href="javascript:void(0);">

                        <span class="menu_icon"><i class="material-icons">&#xE02F;</i></span>

                        <span class="menu_title">Pages</span>

                    </a>

                    <ul>

                        <li class="<?php echo($inner_section == 'home' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/home" title="Manage Home Page">Home</a>
                        </li>
                        <li class="<?php echo($inner_section == 'home_slider' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/home-slider"
                                    title="Manage Home Slider">Home Slider</a></li>

                        <li class="<?php echo($inner_section == 'loyalty' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/loyalty"
                                    title="Manage Loyalty Program Page">Loyalty Program</a></li>

                        <li class="<?php echo($inner_section == 'services' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/services"
                                    title="Manage <?php echo custom::getSiteName('eng'); ?> Services Page">Key
                                Services</a></li>

                        <li class="<?php echo($inner_section == 'about_us' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/about-us" title="Manage About Us Page">About
                                Us</a></li>

                        <li class="<?php echo($inner_section == 'news' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/news" title="Manage News Page">News</a>
                        </li>

                        <li class="<?php echo($inner_section == 'program_awards' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/program-rewards"
                                    title="Manage Program Rewards">Program Rewards</a>
                        </li>

                        <li class="<?php echo($inner_section == 'faqs' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/faqs" title="Manage FAQs Page">FAQs</a>
                        </li>

                        <li class="<?php echo($inner_section == 'career' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/career" title="Manage Careers Page">Careers</a>
                        </li>

                        <li class="<?php echo($inner_section == 'location' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/location"
                                    title="Manage Locations Page">Locations</a></li>

                        <li class="<?php echo($inner_section == 'contact_us' ? 'act_item' : ''); ?>"><a
                                    href="<?php echo custom::baseurl('/'); ?>/admin/page/contactUs"
                                    title="Manage Contact Us Page">Contact Us</a></li>

                        <li class="<?php echo($inner_section == 'change_points' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/page/change-points"
                               title="Manage Change Your Points Page">
                                Change Your Points
                            </a>
                        </li>
                        <li class="<?php echo($inner_section == 'refunds' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/page/refunds"
                               title="Manage Refunds Page">
                                Refunds
                            </a>
                        </li>
                        <li class="<?php echo($inner_section == 'guar_refunds' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/page/guar_refunds"
                               title="Manage Guarantee Refunds Page">
                                Guarantee Refunds
                            </a>
                        </li>
                        <li class="<?php echo($inner_section == 'sta' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/page/sta"
                               title="Manage STA Page">
                                STA
                            </a>
                        </li>
                        <li class="<?php echo($inner_section == 'refer_and_earn' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/page/refer-and-earn"
                               title="Manage Refer & Earn Page">
                                Refer & Earn
                            </a>
                        </li>
                    </ul>

                </li>
            <?php } ?>

            <!-- Meta Pages-->
            <?php if (custom::rights(9, 'view')) { ?>
                <li class="<?php echo($main_section == 'admin_users' ? 'current_section' : ''); ?>" title="Meta Pages">


                    <a href="<?php echo custom::baseurl('/'); ?>/admin/meta_pages" title="Meta Pages">


                        <span class="menu_icon"><i class="material-icons">&#xE02F;</i></span>


                        <span class="menu_title">Pages Meta</span>


                    </a>
                </li>
            <?php } ?>


            <!--for settings-->
            <?php if (
                custom::rights(12, 'view') ||
                custom::rights(25, 'view') ||
                custom::rights(24, 'view') ||
                custom::rights(29, 'view') ||
                custom::rights(41, 'view') ||
                custom::rights(40, 'view') ||
                custom::rights(44, 'view') ||
                custom::rights(26, 'view') ||
                custom::rights(27, 'view') ||
                custom::rights(28, 'view') ||
                custom::rights(23, 'view') ||
                custom::rights(47, 'view') ||
                custom::rights(48, 'view') ||
                custom::rights(34, 'view') ||
                custom::rights(55, 'view') ||
                custom::rights(35, 'view') ||
                custom::rights(13, 'view') ||
                custom::rights(59, 'view')
            ) { ?>
                <li class="<?php echo($main_section == 'settings' ? 'current_section act_section' : ''); ?>"

                    title="Settings">

                    <a href="javascript:void(0);">

                        <span class="menu_icon"><i class="material-icons">&#xE8B8;</i></span>

                        <span class="menu_title">Settings</span>

                    </a>

                    <ul>
                        <?php if (custom::rights(12, 'view')) { ?>
                            <!--<li class="<?php /*echo($inner_section == 'settings' ? 'act_item' : ''); */
                            ?>">
        <a href="<?php /*echo custom::baseurl('/'); */
                            ?>/admin/settings" title="View Site Sections">Site Settings</a>

    </li>-->
                        <?php } ?>

                        <!--<li class="<?php /*echo($inner_section == 'rights' ? 'act_item' : ''); */
                        ?>"><a

                            href="<?php /*echo custom::baseurl('/'); */
                        ?>/admin/rights" title="View Site Rights">User Rights</a>

                    </li>-->

                        <!--<li class="<?php /*echo($inner_section == 'sections' ? 'act_item' : ''); */
                        ?>">
                        <a href="<?php /*echo custom::baseurl('/'); */
                        ?>/admin/sections" title="Manage Site Sections">Site Sections</a>
                    </li>-->

                        <?php if (custom::rights(12, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'site_settings' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/site-settings" title="Manage Site Settings">Site
                                    Settings</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(25, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'api_settings' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/api-settings" title="Manage API Settings">API
                                    Settings</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(24, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'maintenance' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/maintenance"
                                   title="Manage Site Maintenance Settings">Maintenance Settings</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(29, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'user_roles' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/user-rights" title="Manage User Rights">User
                                    Rights</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(41, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'redeem_setup' ? 'act_item' : ''); ?>"><a

                                        href="<?php echo custom::baseurl('/'); ?>/admin/redeem_setup"
                                        title="Manage Redeems">Redeem Setup</a></li>
                        <?php } ?>

                        <?php if (custom::rights(40, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'redeem_factors' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/redeem-factors"
                                   title="Manage Redeem Factors Settings">Redeem Factors</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(44, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'availability' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/availability"
                                   title="Manage Availability Settings">Booking Availability</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(26, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'loyalty_cards' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/loyalty-cards"
                                   title="Manage Loyalty Cards Settings">Loyalty Cards Settings</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(27, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'renting_types' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/renting-types"
                                   title="Manage Renting Types Settings">Renting Types Settings</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(28, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'inquiry_and_department_types' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/inquiry-and-department-types"
                               title="Manage Inquiry & Department Types">Inquiry & Department Types</a>
                          </li>
                        <?php } ?>

                        <?php if (custom::rights(53, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'booking_cancellation_reasons' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/booking-cancellation-reasons"
                               title="Booking Cancellation Reasons">Booking Cancellation Reasons</a>
                          </li>
                        <?php } ?>

                        <?php if (custom::rights(54, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'loyalty_programs' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/loyalty-programs"
                               title="Loyalty Programs">Loyalty Programs</a>
                          </li>
                        <?php } ?>

                        <?php if (custom::rights(59, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'app_popup_promo_codes' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/app-popup-promo-codes"
                                   title="Loyalty Programs">App Popup Promo Codes</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(23, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'terms_and_conditions' ? 'act_item' : ''); ?>">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/terms-and-conditions"
                               title="Manage Terms & Conditions">Terms & Conditions</a>
                          </li>
                        <?php } ?>

                        <?php if (custom::rights(47, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'hl_instructions' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/human-less-instructions"
                                   title="Human Less Instructions">Human Less Instructions</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(48, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'safe_road_api' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/safe-road-api"
                                   title="Safe Road APIs">Safe Road APIs</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(34, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'notification' ? 'act_item' : ''); ?>" style="display: none;">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/notification"
                                   title="Send push notifications to mobile users">Send Notification</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(55, 'view')) { ?>
                          <li class="<?php echo($inner_section == 'notifications' ? 'act_item' : ''); ?>" style="display: none;">
                            <a href="<?php echo custom::baseurl('/'); ?>/admin/notifications"
                               title="Send push notifications to mobile users">Notifications</a>
                          </li>
                        <?php } ?>

                        <?php if (custom::rights(35, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'cronjobs' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/cronjobs" title="Manage Cron Jobs">Cron
                                    Jobs</a>
                            </li>
                        <?php } ?>

                        <?php if (custom::rights(13, 'view')) { ?>
                            <li class="<?php echo($inner_section == 'logs' ? 'act_item' : ''); ?>">
                                <a href="<?php echo custom::baseurl('/'); ?>/admin/logs" title="Manage Site Rights">Site
                                    Logs</a>
                            </li>
                        <?php } ?>

                    </ul>

                </li>
            <?php } ?>

            <?php if (custom::rights(64, 'view')) { ?>
            <li title="Server Logs">
                <a href="<?php echo custom::baseurl('/'); ?>/admin/server-logs" title="Server Logs">
                    <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>
                    <span class="menu_title">Server Logs</span>
                </a>
            </li>
            <?php } ?>

        </ul>

    </div>

</aside>


<!-- main sidebar end -->