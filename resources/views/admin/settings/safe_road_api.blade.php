@extends('admin.layouts.template')
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/safeRoadApi" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Safe Road Mobile API <span style="float: right;"><img src="http://kra.ced.sa/public/admin/assets/images/ajax-loader.gif" alt="Loader" height="24" width="24" class="md-card-loader" id="loader" style="display: none;"></span></h3>
                                <br>
                                <div class="uk-form-row">
                                    <div class="uk-grid uk-grid-width-1-3 uk-grid-width-medium-1-4" data-uk-grid-margin>
                                        <div class="md-input-filled">
                                            <label>Vehicle ID</label>
                                            <input type="text" class="md-input" name="vehicle_id" value="" required>
                                        </div >
                                        <div class="md-input-filled" style="display: none;">
                                            <label>Value</label>
                                            <input type="text" class="md-input" name="value" value="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <input type="hidden" name="command" value="" id="safe_road_command">
                                        <div class="uk-width-1-2">
                                            <input type="submit" onclick="$('#safe_road_command').val('Demobilize')" value="Demobilize" class="md-btn md-btn-danger md-btn-block">
                                        </div>
                                        <div class="uk-width-1-2"></div>

                                        <div class="uk-width-1-2">
                                            <input type="submit" onclick="$('#safe_road_command').val('Mobilize')" value="Mobilize" class="md-btn md-btn-primary md-btn-block">
                                        </div>
                                        <div class="uk-width-1-2"></div>

                                        <div class="uk-width-1-2">
                                            <input type="submit" onclick="$('#safe_road_command').val('CloseDoors')" value="Close Doors" class="md-btn md-btn-danger md-btn-block">
                                        </div>
                                        <div class="uk-width-1-2"></div>

                                        <div class="uk-width-1-2">
                                            <input type="submit" onclick="$('#safe_road_command').val('OpenDoors')" value="Open Doors" class="md-btn md-btn-success md-btn-block">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection