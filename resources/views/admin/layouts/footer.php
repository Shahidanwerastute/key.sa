<!-- secondary sidebar -->
<?php include('jtable_actions.php'); ?>
<aside id="sidebar_secondary" class="tabbed_sidebar">
    <ul class="uk-tab uk-tab-icons uk-tab-grid"
        data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
        <li class="uk-active uk-width-1-3"><a href="#"><i class="material-icons">&#xE422;</i></a></li>
        <li class="uk-width-1-3 chat_sidebar_tab"><a href="#"><i class="material-icons">&#xE0B7;</i></a></li>
        <li class="uk-width-1-3"><a href="#"><i class="material-icons">&#xE8B9;</i></a></li>
    </ul>

    <div class="scrollbar-inner">
        <ul id="dashboard_sidebar_tabs" class="uk-switcher">
            <li>
                <div class="timeline timeline_small uk-margin-bottom">
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                        <div class="timeline_date">
                            09<span>Jun</span>
                        </div>
                        <div class="timeline_content">Created ticket <a href="#"><strong>#3289</strong></a></div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_danger"><i class="material-icons">&#xE5CD;</i></div>
                        <div class="timeline_date">
                            15<span>Jun</span>
                        </div>
                        <div class="timeline_content">Deleted post <a href="#"><strong>Rem non odit eaque consectetur
                                    occaecati et.</strong></a></div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                        <div class="timeline_date">
                            19<span>Jun</span>
                        </div>
                        <div class="timeline_content">
                            Added photo
                            <div class="timeline_content_addon">
                                <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/gallery/Image16.jpg"
                                     alt=""/>
                            </div>
                        </div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                        <div class="timeline_date">
                            21<span>Jun</span>
                        </div>
                        <div class="timeline_content">
                            New comment on post <a href="#"><strong>Id quaerat consequatur dolores qui
                                    adipisci.</strong></a>
                            <div class="timeline_content_addon">
                                <blockquote>
                                    Quo est facilis quia sit qui suscipit quidem voluptas doloremque nihil placeat modi
                                    temporibus qui.&hellip;
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div class="timeline_item">
                        <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                        <div class="timeline_date">
                            29<span>Jun</span>
                        </div>
                        <div class="timeline_content">
                            Added to Friends
                            <div class="timeline_content_addon">
                                <ul class="md-list md-list-addon">
                                    <li>
                                        <div class="md-list-addon-element">
                                            <img class="md-user-image md-list-addon-avatar"
                                                 src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_02_tn.png"
                                                 alt=""/>
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading">Teagan Parisian</span>
                                            <span
                                                    class="uk-text-small uk-text-muted">Nesciunt enim dolores facilis.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <ul class="md-list md-list-addon chat_users">
                    <li>
                        <div class="md-list-addon-element">
                            <span class="element-status element-status-success"></span>
                            <img class="md-user-image md-list-addon-avatar"
                                 src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_02_tn.png"
                                 alt=""/>
                        </div>
                        <div class="md-list-content">
                            <div class="md-list-action-placeholder"></div>
                            <span class="md-list-heading">Nova Gorczany</span>
                            <span
                                    class="uk-text-small uk-text-muted uk-text-truncate">Quibusdam soluta laboriosam.</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-addon-element">
                            <span class="element-status element-status-success"></span>
                            <img class="md-user-image md-list-addon-avatar"
                                 src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_09_tn.png"
                                 alt=""/>
                        </div>
                        <div class="md-list-content">
                            <div class="md-list-action-placeholder"></div>
                            <span class="md-list-heading">Audreanne McCullough</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">Molestiae recusandae.</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-addon-element">
                            <span class="element-status element-status-danger"></span>
                            <img class="md-user-image md-list-addon-avatar"
                                 src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_04_tn.png"
                                 alt=""/>
                        </div>
                        <div class="md-list-content">
                            <div class="md-list-action-placeholder"></div>
                            <span class="md-list-heading">Callie Schneider</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">Aut molestiae ex.</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-addon-element">
                            <span class="element-status element-status-warning"></span>
                            <img class="md-user-image md-list-addon-avatar"
                                 src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_07_tn.png"
                                 alt=""/>
                        </div>
                        <div class="md-list-content">
                            <div class="md-list-action-placeholder"></div>
                            <span class="md-list-heading">Ora Block</span>
                            <span class="uk-text-small uk-text-muted uk-text-truncate">Expedita id.</span>
                        </div>
                    </li>
                </ul>
                <div class="chat_box_wrapper chat_box_small" id="chat">
                    <div class="chat_box chat_box_colors_a">
                        <div class="chat_message_wrapper">
                            <div class="chat_user_avatar">
                                <img class="md-user-image"
                                     src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_11_tn.png"
                                     alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio, eum? </p>
                                </li>
                                <li>
                                    <p> Lorem ipsum dolor sit amet.<span class="chat_message_time">13:38</span></p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper chat_message_right">
                            <div class="chat_user_avatar">
                                <img class="md-user-image"
                                     src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_03_tn.png"
                                     alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem delectus
                                        distinctio dolor earum est hic id impedit ipsum minima mollitia natus nulla
                                        perspiciatis quae quasi, quis recusandae, saepe, sunt totam.
                                        <span class="chat_message_time">13:34</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper">
                            <div class="chat_user_avatar">
                                <img class="md-user-image"
                                     src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_11_tn.png"
                                     alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque ea mollitia
                                        pariatur porro quae sed sequi sint tenetur ut veritatis.
                                        <span class="chat_message_time">23 Jun 1:10am</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="chat_message_wrapper chat_message_right">
                            <div class="chat_user_avatar">
                                <img class="md-user-image"
                                     src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_03_tn.png"
                                     alt=""/>
                            </div>
                            <ul class="chat_message">
                                <li>
                                    <p> Lorem ipsum dolor sit amet, consectetur. </p>
                                </li>
                                <li>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                        <span class="chat_message_time">Friday 13:34</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <h4 class="heading_c uk-margin-small-bottom uk-margin-top">General Settings</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" checked
                                       id="settings_site_online" name="settings_site_online"/>
                            </div>
                            <span class="md-list-heading">Site Online</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small" id="settings_seo"
                                       name="settings_seo"/>
                            </div>
                            <span class="md-list-heading">Search Engine Friendly URLs</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small"
                                       id="settings_url_rewrite" name="settings_url_rewrite"/>
                            </div>
                            <span class="md-list-heading">Use URL rewriting</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                </ul>
                <hr class="md-hr">
                <h4 class="heading_c uk-margin-small-bottom uk-margin-top">Other Settings</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small"
                                       data-switchery-color="#7cb342" checked id="settings_top_bar"
                                       name="settings_top_bar"/>
                            </div>
                            <span class="md-list-heading">Top Bar Enabled</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small"
                                       data-switchery-color="#7cb342" id="settings_api" name="settings_api"/>
                            </div>
                            <span class="md-list-heading">Api Enabled</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <div class="uk-float-right">
                                <input type="checkbox" data-switchery data-switchery-size="small"
                                       data-switchery-color="#d32f2f" id="settings_minify_static" checked
                                       name="settings_minify_static"/>
                            </div>
                            <span class="md-list-heading">Minify JS files automatically</span>
                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <button type="button" class="chat_sidebar_close uk-close"></button>
    <div class="chat_submit_box">
        <div class="uk-input-group">
            <input type="text" class="md-input" name="submit_message" id="submit_message" placeholder="Send message">
            <span class="uk-input-group-addon">
                    <a href="#"><i class="material-icons md-24">&#xE163;</i></a>
                </span>
        </div>
    </div>

</aside>
<!-- secondary sidebar end -->

<div class="uk-width-medium-1-3">
    <button class="md-btn" id="open_manage_booking_add_booking_form" data-uk-modal="{target:'#manage_booking_add_booking_form'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="manage_booking_add_booking_form">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/add_booking_payment_in_manage_bookings" method="POST">
                <input class="md-input" type="text" name="booking_id" placeholder="Booking ID" required/>
                <input class="md-input" type="text" name="transaction_id" placeholder="Transaction Number" required/>
                <input class="md-input" type="text" name="first_4_digits" placeholder="First 4 Digits" required/>
                <input class="md-input" type="text" name="last_4_digits" placeholder="Last 4 Digits" required/>
                <select name="card_brand" class="md-input" required>
                    <option>VISA</option>
                    <option>MASTER</option>
                    <option>MADA</option>
                    <option>AMEX</option>
                </select>
                <input class="md-input kendo-date-time-picker" type="text" name="trans_date" placeholder="Transaction Date & Time" required/>
                <button type="submit" class="md-btn md-btn-primary md-btn-medium">Submit</button>
            </form>
        </div>
    </div>
</div>

<div class="uk-width-medium-1-3">
    <button class="md-btn" id="open_manage_booking_edit_booking_form" data-uk-modal="{target:'#manage_booking_edit_booking_form'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="manage_booking_edit_booking_form">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/update_booking_payment_in_manage_bookings" method="POST">
                <input type="hidden" name="booking_id"/>
                <input class="md-input kendo-date-time-picker" type="text" name="trans_date" required/>
                <button type="submit" class="md-btn md-btn-primary md-btn-medium">Submit</button>
            </form>
        </div>
    </div>
</div>


<!--asdas-->

<div class="uk-width-medium-1-3">
    <button class="md-btn" id="open_manage_booking_add_added_booking_form" data-uk-modal="{target:'#manage_booking_add_added_booking_form'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="manage_booking_add_added_booking_form">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/add_extended_booking_payment_in_manage_bookings" method="POST">
                <input class="md-input" type="text" name="booking_reservation_code" placeholder="Booking ID" required/>
                <input class="md-input" type="text" name="extended_days" placeholder="Extended Days" required/>
                <select name="payment_company" class="md-input" required>
                    <option>HP</option>
                    <option>STS</option>
                </select>
                <select name="payment_method" class="md-input" required>
                    <option>HP_Visa</option>
                    <option>HP_MC</option>
                    <option>HP_Mada</option>
                </select>
                <input class="md-input" type="text" name="transaction_reference" placeholder="Transaction Number" required/>
                <input class="md-input" type="text" name="card_number" placeholder="Card Number" required/>
                <input class="md-input" type="text" name="amount" placeholder="Amount" required/>
                <input class="md-input kendo-date-time-picker" type="text" name="transaction_created_at" placeholder="Transaction Date & Time" required/>
                <select name="payment_source" class="md-input" required>
                    <option>Website</option>
                    <option>Mobile Website</option>
                    <option>iOS</option>
                    <option>Android</option>
                </select>
                <input class="md-input" type="text" name="number_of_payment" placeholder="No. of Payment" required/>
                <input class="md-input" type="text" name="payment_booking_id" placeholder="Payment Booking ID" required/>
                <button type="submit" class="md-btn md-btn-primary md-btn-medium">Submit</button>
            </form>
        </div>
    </div>
</div>

<div class="uk-width-medium-1-3">
    <button class="md-btn" id="open_manage_booking_edit_added_booking_form" data-uk-modal="{target:'#manage_booking_edit_added_booking_form'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="manage_booking_edit_added_booking_form">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/update_extended_booking_payment_in_manage_bookings" method="POST">
                <input type="hidden" name="id"/>
                <input class="md-input kendo-date-time-picker" type="text" name="transaction_created_at" required/>
                <input class="md-input" type="text" name="amount" placeholder="Amount" required/>
                <button type="submit" class="md-btn md-btn-primary md-btn-medium">Submit</button>
            </form>
        </div>
    </div>
</div>

<div class="uk-width-medium-1-3">
    <button class="md-btn alert-message-button" data-uk-modal="{target:'#modal_default'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="modal_default">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <h2 id="alert-message-heading"></h2>
            <div id="alert-message" style="overflow-wrap: break-word;"></div>
            <br/>
            <!--<button class="md-btn md-btn-primary md-btn-medium" onclick="window.location.reload();">Add New</button>-->
            <button class="md-btn md-btn-primary md-btn-medium uk-modal-close">Ok</button>
        </div>
    </div>
</div>

<div class="uk-width-medium-1-3">
    <button class="md-btn showUserDetails" data-uk-modal="{target:'#user_details_modal'}" style="display:none;">Open
    </button>
    <div class="uk-modal" id="user_details_modal">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <div id="contains_user_data"></div>
            <br/>
            <button class="md-btn md-btn-primary md-btn-medium uk-modal-close">Close</button>
        </div>
    </div>
</div>
<div class="uk-width-medium-1-3">
    <div class="uk-modal" id="bulk_add_pricing">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/smtp_settings" method="post" class="settings_ajax_form"
                  onsubmit="return false;">
                <h3 class="heading_a">SMTP Settings</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Car Category">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Car Group">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Car Type">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Car Model">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Renting Type">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <div class="md-input-wrapper md-input-filled"><label>Price Value</label><input
                                            type="text" name="password" class="md-input"><span
                                            class="md-input-bar"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <div class="md-input-wrapper md-input-filled"><label>Applies From</label><input
                                            type="text" name="password" class="md-input"><span
                                            class="md-input-bar"></span></div>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <div class="md-input-wrapper md-input-filled"><label>Applies To</label><input
                                            type="text" name="password" class="md-input"><span
                                            class="md-input-bar"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Region">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select City">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="uk-form-row uk-width-1-1">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select Branch">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                            <div class="uk-width-medium-1-2 uk-row-first">
                                <select id="" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                        title="Select User Type">
                                    <option value="">Select...</option>
                                    <option value="a">Item A</option>
                                    <option value="b">Item B</option>
                                    <option value="c">Item C</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <br>

                </div>
                <div class="uk-width-1-1">
                    <button type="submit" href="#" class="md-btn md-btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!--Scripts-->
<!-- google web fonts -->
<script>
    WebFontConfig = {
        google: {
            families: [
                'Source+Code+Pro:400,700:latin',
                'Roboto:400,300,500,700,400italic:latin'
            ]
        }
    };
    (function () {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
</script>


<!-- common functions -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/common.min.js"></script>

<!-- uikit functions -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/uikit_custom.min.js"></script>
<!-- altair common functions/helpers -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/altair_admin_common.min.js"></script>

<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/kendoui_custom.min.js"></script>

<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/kendoui.min.js?v=<?php echo rand(); ?>"></script>

<!-- page specific plugins -->
<!-- d3 -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/d3/d3.min.js"></script>
<!-- metrics graphics (charts) -->
<!-- <script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>-->
<!-- chartist (charts) -->
<?php if (Session::get('last_segment') == 'dashboard') { ?>
    <script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/chartist/dist/chartist.min.js"></script>
<?php } ?>
<!-- maplace (google maps) -->
<!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/maplace-js/dist/maplace.min.js"></script>-->
<!-- peity (small charts) -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/peity/jquery.peity.min.js"></script>
<!-- easy-pie-chart (circular statistics) -->
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
<!-- countUp -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/countUp.js/dist/countUp.min.js"></script>
<!-- handlebars.js -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/handlebars/handlebars.min.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/custom/handlebars_helpers.min.js"></script>
<!-- CLNDR -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/clndr/clndr.min.js"></script>
<!-- fitvids -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/fitvids/jquery.fitvids.js"></script>


<!-- page specific plugins -->
<!-- datatables -->
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>
<!-- datatables colVis-->

<!-- datatables custom integration -->
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/custom/datatables_uikit.min.js"></script>

<!--  datatables functions -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/plugins_datatables.min.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/ecommerce_product_edit.min.js"></script>
<!-- JQuery-UI -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!--<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jtable/lib/jquery.jtable.min.js"></script>-->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/jtable_custom.min.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jtable/lib/jquery.validationEngine.js"></script>
<script
        src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jtable/lib/jquery.validationEngine-en.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/forms_advanced.min.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/plugins_crud_table.min.js"></script>
<script
    src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/jqueryUpload/jquery.uploadfile.js"></script>

<!--  IntTel Input Plugin -->
<script src="<?php echo custom::baseurl('/'); ?>/public/frontend/intTelInput/js/intlTelInput.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/frontend/intTelInput/js/intlTelInput.min.js"></script>
<script src="<?php echo custom::baseurl('/'); ?>/public/frontend/intTelInput/js/utils.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/locationpicker.jquery.min.js"></script>

<?php
// $manual_version = '5.0';
$manual_version = rand();
?>

<script>
    var show_company_code_dropdown_in_car_prices = '<?php echo (custom::rights(61, 'view') ? 1 : 0);  ?>';
</script>
<script
    src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/script.js?v=<?php echo $manual_version;  ?>"></script> <!--being used for Jtable scripts for backend-->

<!-- ckeditor -->
<script src="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/ckeditor/ckeditor.js"></script>

<script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/functions.js?v=<?php echo $manual_version;  ?>"></script>
<script>
    $(document).ready(function () {
        $('#kUI_datepicker_a1').on('change', function (e) {
            $('#end_date_a1').val(this.value);
        });
    $('#kUI_datepicker_a').on('change', function (e) {
        $('#start_date_a').val(this.value);
    });
})
</script>
<!--  dashbord functions -->
<?php if (Session::get('last_segment') == 'dashboard' || Session::get('last_segment') == 'reports' || Session::get('last_segment') == 'oasis-reports') { ?>
    <script src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/js/pages/dashboard.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php } ?>

<script type="text/javascript">CKEDITOR_BASEPATH = '<?php echo custom::baseurl('/');?>/public/admin/bower_components/ckeditor/';</script>
<script type="text/javascript">
    $('.eng_desc, .eng_description').each(function (e) {
        CKEDITOR.replace(this.id, {toolbar: 'Full', width: '95%', height: '200px'});
    });
    $('.arb_desc, .arb_description').each(function (e) {
        CKEDITOR.replace(this.id, {language: 'ar', toolbar: 'Full', width: '95%', height: '200px'});
    });
    $('#terms_eng').each(function (e) {
        CKEDITOR.replace(this.id, {toolbar: 'Full', width: '95%', height: '200px'});
    });
    $('#terms_arb').each(function (e) {
        CKEDITOR.replace(this.id, {language: 'ar', toolbar: 'Full', width: '95%', height: '200px'});
    });
    $('#terms_eng_for_cdw_plus').each(function (e) {
        CKEDITOR.replace(this.id, {toolbar: 'Full', width: '95%', height: '200px'});
    });
    $('#terms_arb_for_cdw_plus').each(function (e) {
        CKEDITOR.replace(this.id, {language: 'ar', toolbar: 'Full', width: '95%', height: '200px'});
    });
</script>


<script>
    $(function () {
        if (isHighDensity) {
            // enable hires images
            altair_helpers.retina_images();
        }
        if (Modernizr.touch) {
            // fastClick (touch devices)
            FastClick.attach(document.body);
        }
    });
    $window.load(function () {
        // ie fixes
        altair_helpers.ie_fix();
    });
</script>


<script>
    $(function () {
        var $switcher = $('#style_switcher'),
            $switcher_toggle = $('#style_switcher_toggle'),
            $theme_switcher = $('#theme_switcher'),
            $mini_sidebar_toggle = $('#style_sidebar_mini'),
            $slim_sidebar_toggle = $('#style_sidebar_slim'),
            $boxed_layout_toggle = $('#style_layout_boxed'),
            $accordion_mode_toggle = $('#accordion_mode_main_menu'),
            $html = $('html'),
            $body = $('body');


        $switcher_toggle.click(function (e) {
            e.preventDefault();
            $switcher.toggleClass('switcher_active');
        });

        $theme_switcher.children('li').click(function (e) {
            e.preventDefault();
            var $this = $(this),
                this_theme = $this.attr('data-app-theme');

            $theme_switcher.children('li').removeClass('active_theme');
            $(this).addClass('active_theme');
            $html
                .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g app_theme_h app_theme_i app_theme_dark')
                .addClass(this_theme);

            if (this_theme == '') {
                localStorage.removeItem('altair_theme');
            } else {
                localStorage.setItem("altair_theme", this_theme);
                if (this_theme == 'app_theme_dark') {
                    $('#kendoCSS').attr('href', 'bower_components/kendo-ui/styles/kendo.materialblack.min.css')
                }
            }

        });

        // hide style switcher
        $document.on('click keyup', function (e) {
            if ($switcher.hasClass('switcher_active')) {
                if (
                    ( !$(e.target).closest($switcher).length )
                    || ( e.keyCode == 27 )
                ) {
                    $switcher.removeClass('switcher_active');
                }
            }
        });

        // get theme from local storage
        if (localStorage.getItem("altair_theme") !== null) {
            $theme_switcher.children('li[data-app-theme=' + localStorage.getItem("altair_theme") + ']').click();
        }


        // toggle mini sidebar

        // change input's state to checked if mini sidebar is active
        if ((localStorage.getItem("altair_sidebar_mini") !== null && localStorage.getItem("altair_sidebar_mini") == '1') || $body.hasClass('sidebar_mini')) {
            $mini_sidebar_toggle.iCheck('check');
        }

        $mini_sidebar_toggle
            .on('ifChecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.setItem("altair_sidebar_mini", '1');
                localStorage.removeItem('altair_sidebar_slim');
                location.reload(true);
            })
            .on('ifUnchecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.removeItem('altair_sidebar_mini');
                location.reload(true);
            });

        // toggle slim sidebar

        // change input's state to checked if mini sidebar is active
        if ((localStorage.getItem("altair_sidebar_slim") !== null && localStorage.getItem("altair_sidebar_slim") == '1') || $body.hasClass('sidebar_slim')) {
            $slim_sidebar_toggle.iCheck('check');
        }

        $slim_sidebar_toggle
            .on('ifChecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.setItem("altair_sidebar_slim", '1');
                localStorage.removeItem('altair_sidebar_mini');
                location.reload(true);
            })
            .on('ifUnchecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.removeItem('altair_sidebar_slim');
                location.reload(true);
            });

        // toggle boxed layout

        if ((localStorage.getItem("altair_layout") !== null && localStorage.getItem("altair_layout") == 'boxed') || $body.hasClass('boxed_layout')) {
            $boxed_layout_toggle.iCheck('check');
            $body.addClass('boxed_layout');
            $(window).resize();
        }

        $boxed_layout_toggle
            .on('ifChecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.setItem("altair_layout", 'boxed');
                location.reload(true);
            })
            .on('ifUnchecked', function (event) {
                $switcher.removeClass('switcher_active');
                localStorage.removeItem('altair_layout');
                location.reload(true);
            });

        // main menu accordion mode
        if ($sidebar_main.hasClass('accordion_mode')) {
            $accordion_mode_toggle.iCheck('check');
        }

        $accordion_mode_toggle
            .on('ifChecked', function () {
                $sidebar_main.addClass('accordion_mode');
            })
            .on('ifUnchecked', function () {
                $sidebar_main.removeClass('accordion_mode');
            });


    });


    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
            '<tr>' +
            '<td>ID Version:</td>' +
            '<td>' + d.id_version + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Date of Birth:</td>' +
            '<td>' + d.dob + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Id Expiry Date:</td>' +
            '<td>' + d.id_expiry_date + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Id Date Type:</td>' +
            '<td>' + (d.id_date_type == 'G' ? 'Gregorian' : 'Hijri') + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Id Country:</td>' +
            '<td>' + d.id_country_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>License ID Type:</td>' +
            '<td>' + d.license_id_type_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>License No:</td>' +
            '<td>' + d.license_no + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>License Expiry Date:</td>' +
            '<td>' + d.license_expiry_date + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>License Country:</td>' +
            '<td>' + d.license_country_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Job Title:</td>' +
            '<td>' + d.job_title_name + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Sponsor:</td>' +
            '<td>' + (d.sponsor != null ? d.sponsor : '') + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Street Address:</td>' +
            '<td>' + d.street_address + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>District Address:</td>' +
            '<td>' + d.district_address + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Blacklisted?</td>' +
            '<td>' + (d.black_listed == 'Y' ? 'Yes' : 'No') + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td>Simah Blocked?</td>' +
            '<td>' + (d.simah_block == 'yes' ? 'Yes' : 'No') + '</td>' +
            '</tr>' +
            '</table>';
    }

    $(document).ready(function () {
        var table = $('#dt_colVis1').DataTable({
            "bSort": false,
            "processing": true,
            "serverSide": true,
            "ajax": "<?php echo custom::baseurl('admin/getCustomerForDataTable')?>",
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<img src="https://datatables.net/examples/resources/details_open.png" alt="detail" width="20" height="20">'
                },
                {"data": "user_id"},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "mobile_no"},
                {"data": "email"},
                {"data": "id_no"},
                {"data": "id_type_name"},
                {"data": "nationality_name"},
                {"data": "loyalty_card_type"},
                {"data": "loyalty_points"},
                {"data": "created_at"}

            ]
        });

        // Add event listener for opening and closing details
        $('#dt_colVis1 tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });

    // for corporate customers table
    $(document).ready(function() {
        var table = $('#dt_colVis').DataTable();
        $('#dt_colVis tbody').on( 'click', 'tr', function () {
           var row_id = $(this).attr('id');
            var edit_url = base_url+'/admin/corporate_customer/edit/'+row_id;
            var delete_url = base_url+'/admin/corporate_customer/delete/'+row_id;
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                $("#edit_corporate_customer_btn").attr('href', 'javascript:void(0);');
                $("#delete_corporate_customer_btn").data('url', '');
                $("#delete_corporate_customer_btn").data('deleteable', 'no');
                $("#delete_corporate_customer_btn").data('row_id', '');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $("#edit_corporate_customer_btn").attr('href', edit_url);
                $("#delete_corporate_customer_btn").data('url', delete_url);
                $("#delete_corporate_customer_btn").data('deleteable', 'yes');
                $("#delete_corporate_customer_btn").data('row_id', row_id);
            }
        });

        var table = $('#dt_colVis2').DataTable();
        $('#dt_colVis2 tbody').on( 'click', 'tr', function () {
           var row_id = $(this).attr('id');
            var edit_url = base_url+'/admin/super_corporate_customer/edit/'+row_id;
            var delete_url = base_url+'/admin/super_corporate_customer/delete/'+row_id;
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
                $("#edit_corporate_customer_btn").attr('href', 'javascript:void(0);');
                $("#delete_corporate_customer_btn").data('url', '');
                $("#delete_corporate_customer_btn").data('deleteable', 'no');
                $("#delete_corporate_customer_btn").data('row_id', '');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $("#edit_corporate_customer_btn").attr('href', edit_url);
                $("#delete_corporate_customer_btn").data('url', delete_url);
                $("#delete_corporate_customer_btn").data('deleteable', 'yes');
                $("#delete_corporate_customer_btn").data('row_id', row_id);
            }
        });
    } );

    $(document).on('click', '#delete_corporate_customer_btn', function () {
        if ($(this).data('deleteable') == 'yes')
        {
            if (confirm("Are you sure to delete this?"))
            {
                var row_id = $(this).data('row_id');
                var url = $(this).data('url');
                $.get(url, function (response) {
                    if (response == 1)
                    {
                        $('#' +row_id ).animate({width: 'toggle'}, 350);
                        alert("Record deleted successfully.");
                    }else{
                        alert("Record not deleted. Please try again.");
                    }
                });
            }
        }else{
            alert("Please click on a row to select a record to delete.");
        }
    });

    <?php if (isset($show_charts) && $show_charts == true)
    { ?>
    // Load the Visualization API and the chart packages.
    google.charts.load('current', {'packages': ['bar']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    // Callback that creates and populates a data table,
    // instantiates the charts, passes in the data and
    // draws them.
    function drawChart() {
        // Create the data table.
        var InqData = new google.visualization.arrayToDataTable([
            ['Duration', 'Inquiries'],
            ['Today', <?php echo $inquiries_count_for_today; ?>],
            ['Yesterday', <?php echo $inquiries_count_for_yesterday; ?>],
            ['Last 7 Days', <?php echo $inquiries_count_for_last_week; ?>],
            ['Last Month', <?php echo $inquiries_count_for_last_month; ?>],
            ['Total', <?php echo $inquiries_count_total; ?>]
        ]);
        // Create the data table.
        var RegData = new google.visualization.arrayToDataTable([
            ['Duration', 'Registered Users'],
            ['Today', <?php echo $registered_users_for_today; ?>],
            ['Yesterday', <?php echo $registered_users_for_yesterday; ?>],
            ['Last 7 Days', <?php echo $registered_users_for_last_week; ?>],
            ['Last Month', <?php echo $registered_users_for_last_month; ?>],
            ['Total', <?php echo $registered_users_total; ?>]
        ]);
        // Set chart options
        var InqOptions = {
            chart: {
                title: 'Inquiries'
            },
            legend: {position: 'none', labeledValueText: 'both'},
            width: 'auto',
            height: 'auto',
            bars: 'vertical' // Required for Material Bar Charts.
        };
        // Set chart options
        var RegOptions = {
            chart: {
                title: 'Registered Users'
            },
            legend: {position: 'none', labeledValueText: 'both'},
            width: 'auto',
            height: 'auto',
            bars: 'vertical' // Required for Material Bar Charts.
        };
        // Instantiate and draw our chart, passing in some options.
        var InqChart = new google.charts.Bar(document.getElementById('reg_users_chart'));
        google.visualization.events.addOneTimeListener(InqChart, 'ready', function () {
            var RegChart = new google.charts.Bar(document.getElementById('inquiries_chart'));
            RegChart.draw(RegData, RegOptions);
        });
        InqChart.draw(InqData, InqOptions);
    };
    <?php } ?>


</script>

<?php if (isset($show_charts) && $show_charts == true) {
    $i = 0;
    $arrString = "['Branch', 'No Of Bookings'],";
    foreach ($branch_labels as $label) {
        $bookingCount = $bookings_count[$i];
        $arrString .= "['" . $label . "', " . $bookingCount . "],";
        $i++;
    }
    ?>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($arrString, ','); ?>
            ]);

            var options = {
                title: 'Bookings % Based On Branches',
                pieSliceText: 'value-and-percentage',
                is3D: true
                /*legend: {
                 position: 'labeled',
                 labeledValueText: 'both'
                 }*/
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler2);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler3);

            chart.draw(data, options);
            function uselessHandler2() {
                $('#piechart').css('cursor', 'pointer')
            }

            function uselessHandler3() {
                $('#piechart').css('cursor', 'default')
            }
        }
    </script>
<?php } ?>

<?php if (isset($show_survey_charts) && $show_survey_charts == true) { ?>
    <script type="text/javascript">

        // Load the Visualization API and the chart packages.
        google.charts.load('current', {'packages': ['bar']});
        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChartForSubmittedSurveys);
        // Callback that creates and populates a data table,
        // instantiates the charts, passes in the data and
        // draws them.
        function drawChartForSubmittedSurveys() {
            // Create the data table.
            var InqData = new google.visualization.arrayToDataTable([
                ['Duration', 'Feedbacks'],
                ['Today', <?php echo $surveys_count_for_today; ?>],
                ['Yesterday', <?php echo $surveys_count_for_yesterday; ?>],
                ['Last 7 Days', <?php echo $surveys_count_for_last_week; ?>],
                ['Last Month', <?php echo $surveys_count_for_last_month; ?>],
                ['Total', <?php echo $surveys_count_total; ?>]
            ]);

            // Set chart options
            var InqOptions = {
                chart: {
                    title: 'Feedbacks'
                },
                legend: {position: 'none', labeledValueText: 'both'},
                width: '100%',
                height: 'auto',
                bars: 'vertical' // Required for Material Bar Charts.
            };

            // Instantiate and draw our chart, passing in some options.
            var InqChart = new google.charts.Bar(document.getElementById('total_feedbacks_chart'));
            InqChart.draw(InqData, InqOptions);
        };


        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $car_quality_and_cleanliness_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($car_quality_and_cleanliness_titles)) {
            foreach ($car_quality_and_cleanliness_titles as $car_quality_and_cleanliness_title) {
                $car_quality_and_cleanliness_chart_data .= "['" . $car_quality_and_cleanliness_title . " Star', " . $car_quality_and_cleanliness_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(car_quality_and_cleanliness_pie_chart);

        function car_quality_and_cleanliness_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($car_quality_and_cleanliness_chart_data, ','); ?>
            ]);

            var options = {
                title: 'Car Quality And Cleanliness',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('car_quality_and_cleanliness_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#car_quality_and_cleanliness_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#car_quality_and_cleanliness_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $employee_performance_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($employee_performance_titles)) {
            foreach ($employee_performance_titles as $employee_performance_title) {
                $employee_performance_chart_data .= "['" . $employee_performance_title . " Star', " . $employee_performance_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(employee_performance_pie_chart);

        function employee_performance_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($employee_performance_chart_data, ','); ?>
            ]);

            var options = {
                title: 'Employee Performance',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('employee_performance_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#employee_performance_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#employee_performance_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        // NEW START

        <?php
        $i = 0;
        $branch_employees_behavior_and_performance_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($branch_employees_behavior_and_performance_titles)) {
            foreach ($branch_employees_behavior_and_performance_titles as $branch_employees_behavior_and_performance_title) {
                $branch_employees_behavior_and_performance_chart_data .= "['" . $branch_employees_behavior_and_performance_title . " Star', " . $branch_employees_behavior_and_performance_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(branch_employees_behavior_and_performance_pie_chart);

        function branch_employees_behavior_and_performance_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($branch_employees_behavior_and_performance_chart_data, ','); ?>
            ]);

            var options = {
                title: 'Branch Employees Behavior And Performance',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('branch_employees_behavior_and_performance_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#branch_employees_behavior_and_performance_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#branch_employees_behavior_and_performance_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $the_quickness_and_efficiency_of_completing_your_rental_procedure_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($the_quickness_and_efficiency_of_completing_your_rental_procedure_titles)) {
            foreach ($the_quickness_and_efficiency_of_completing_your_rental_procedure_titles as $the_quickness_and_efficiency_of_completing_your_rental_procedure_title) {
                $the_quickness_and_efficiency_of_completing_your_rental_procedure_chart_data .= "['" . $the_quickness_and_efficiency_of_completing_your_rental_procedure_title . " Star', " . $the_quickness_and_efficiency_of_completing_your_rental_procedure_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart);

        function the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($the_quickness_and_efficiency_of_completing_your_rental_procedure_chart_data, ','); ?>
            ]);

            var options = {
                title: 'The Quickness and Efficiency of completing rental procedures',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $the_accuracy_of_the_rental_information_provided_to_you_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($the_accuracy_of_the_rental_information_provided_to_you_titles)) {
            foreach ($the_accuracy_of_the_rental_information_provided_to_you_titles as $the_accuracy_of_the_rental_information_provided_to_you_title) {
                $the_accuracy_of_the_rental_information_provided_to_you_chart_data .= "['" . $the_accuracy_of_the_rental_information_provided_to_you_title . " Star', " . $the_accuracy_of_the_rental_information_provided_to_you_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(the_accuracy_of_the_rental_information_provided_to_you_pie_chart);

        function the_accuracy_of_the_rental_information_provided_to_you_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($the_accuracy_of_the_rental_information_provided_to_you_chart_data, ','); ?>
            ]);

            var options = {
                title: 'The accuracy of the rental information provided',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('the_accuracy_of_the_rental_information_provided_to_you_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#the_accuracy_of_the_rental_information_provided_to_you_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#the_accuracy_of_the_rental_information_provided_to_you_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $the_safety_and_the_quality_of_the_vehicle_structure_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($the_safety_and_the_quality_of_the_vehicle_structure_titles)) {
            foreach ($the_safety_and_the_quality_of_the_vehicle_structure_titles as $the_safety_and_the_quality_of_the_vehicle_structure_title) {
                $the_safety_and_the_quality_of_the_vehicle_structure_chart_data .= "['" . $the_safety_and_the_quality_of_the_vehicle_structure_title . " Star', " . $the_safety_and_the_quality_of_the_vehicle_structure_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(the_safety_and_the_quality_of_the_vehicle_structure_pie_chart);

        function the_safety_and_the_quality_of_the_vehicle_structure_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($the_safety_and_the_quality_of_the_vehicle_structure_chart_data, ','); ?>
            ]);

            var options = {
                title: 'The Safety and the Quality of the Vehicle',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('the_safety_and_the_quality_of_the_vehicle_structure_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#the_safety_and_the_quality_of_the_vehicle_structure_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#the_safety_and_the_quality_of_the_vehicle_structure_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $the_cleanliness_of_the_vehicle_externally_and_internally_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($the_cleanliness_of_the_vehicle_externally_and_internally_titles)) {
            foreach ($the_cleanliness_of_the_vehicle_externally_and_internally_titles as $the_cleanliness_of_the_vehicle_externally_and_internally_title) {
                $the_cleanliness_of_the_vehicle_externally_and_internally_chart_data .= "['" . $the_cleanliness_of_the_vehicle_externally_and_internally_title . " Star', " . $the_cleanliness_of_the_vehicle_externally_and_internally_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart);

        function the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($the_cleanliness_of_the_vehicle_externally_and_internally_chart_data, ','); ?>
            ]);

            var options = {
                title: 'The cleanliness of the vehicle externally and internally',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart').css('cursor', 'default')
            }
        }

        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $how_likely_are_you_to_recommend_our_company_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($how_likely_are_you_to_recommend_our_company_titles)) {
            foreach ($how_likely_are_you_to_recommend_our_company_titles as $how_likely_are_you_to_recommend_our_company_title) {
                $how_likely_are_you_to_recommend_our_company_chart_data .= "['" . $how_likely_are_you_to_recommend_our_company_title . " Star', " . $how_likely_are_you_to_recommend_our_company_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(how_likely_are_you_to_recommend_our_company_pie_chart);

        function how_likely_are_you_to_recommend_our_company_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($how_likely_are_you_to_recommend_our_company_chart_data, ','); ?>
            ]);

            var options = {
                title: 'How likely to recommend our company',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('how_likely_are_you_to_recommend_our_company_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#how_likely_are_you_to_recommend_our_company_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#how_likely_are_you_to_recommend_our_company_pie_chart').css('cursor', 'default')
            }
        }

        // NEW END
        /////////////////////////////////////////////////////

        <?php
        $i = 0;
        $your_experience_with_key_chart_data = "['Feedback', 'No Of Responses'],";
        if (isset($your_experience_with_key_titles)) {
            foreach ($your_experience_with_key_titles as $your_experience_with_key_title) {
                $your_experience_with_key_chart_data .= "['" . $your_experience_with_key_title . " Star', " . $your_experience_with_key_count[$i] . "],";
                $i++;
            }
        }
        ?>

        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(your_experience_with_key_pie_chart);

        function your_experience_with_key_pie_chart() {

            var data = google.visualization.arrayToDataTable([
                <?php echo rtrim($your_experience_with_key_chart_data, ','); ?>
            ]);

            var options = {
                title: 'Your Experience With KEY',
                pieSliceText: 'value-and-percentage',
                colors: ['#86DE2D', '#E8F305', '#FFBF00', '#FF870F', '#000000'],
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('your_experience_with_key_pie_chart'));
            google.visualization.events.addListener(chart, 'onmouseover', uselessHandler4);
            google.visualization.events.addListener(chart, 'onmouseout', uselessHandler5);

            chart.draw(data, options);
            function uselessHandler4() {
                $('#your_experience_with_key_pie_chart').css('cursor', 'pointer')
            }

            function uselessHandler5() {
                $('#your_experience_with_key_pie_chart').css('cursor', 'default')
            }
        }

    </script>
<?php } ?>

<?php if (isset($is_password_protected)) { ?>
    <script>
        $.confirm({
            title: 'Protected!',
            content: "You must provide password to access this page.<br><input type='password' name='posted_password' class='md-input'>",
            buttons: {
                confirm: function () {
                    var password = this.$content.find('input[name="posted_password"]').val();
                    $.ajax({
                        type: "POST",
                        url: base_url + '/admin/settings/verify_password',
                        data: {'password': password},
                        cache: false,
                        beforeSend: function () {
                            altair_helpers.content_preloader_show();
                        },
                        complete: function () {
                            altair_helpers.content_preloader_hide();
                        },
                        success: function (result) {
                            if (result == 1) {
                                $('.is_password_protected').show();
                            } else {
                                $.alert("Wrong password!");
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    });
                }
            }
        });
    </script>
<?php } ?>

</body>
</html>