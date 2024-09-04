@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Reset Password</h3>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-form-row uk-width-1-1">
                            <div class="uk-grid" data-uk-grid-margin="">
                                    <div class="uk-width-1-2">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <div class="uk-width-1-1">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Enter Password</label>
                                                    <input type="password" class="md-input required" id="admin_password" autocomplete="off"/>
                                                    <a href="" class="uk-form-password-toggle" data-uk-form-password>show</a>
                                                    <span class="md-input-bar"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="uk-width-1-1">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Re-Enter Password</label>
                                                    <input type="password" class="md-input required" id="admin_confirm_password" autocomplete="off">
                                                    <a href="" class="uk-form-password-toggle" data-uk-form-password>show</a>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <br>
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary" id="resetAdminPassword">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection