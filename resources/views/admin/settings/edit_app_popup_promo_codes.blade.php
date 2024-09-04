@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h4 class="heading_a uk-margin-bottom">
                Update App Popup Promo Codes
            </h4>
            <div class="uk-width-medium-2-3 uk-row-first">
                <div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <form method="POST" action="{{custom::baseurl('admin/app-popup-promo-codes/update')}}">
                                    <input type="hidden" name="id" value="{{$row->id}}">
                                    <div class="uk-form-row">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>Title (En) *</label>
                                                <input name="eng_title" type="text" value="{{$row->eng_title}}" class="md-input" required/>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <label>Title (Ar) *</label>
                                                <input name="arb_title" type="text" value="{{$row->arb_title}}" class="md-input" required/>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>Sub Title (En) *</label>
                                                <input name="eng_sub_title" type="text" value="{{$row->eng_sub_title}}" class="md-input" required/>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <label>Sub Title (Ar) *</label>
                                                <input name="arb_sub_title" type="text" value="{{$row->arb_sub_title}}" class="md-input" required/>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>Description (En)</label>
                                                <input name="eng_description" type="text" value="{{$row->eng_description}}" class="md-input"/>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <label>Description (Ar)</label>
                                                <input name="arb_description" type="text" value="{{$row->arb_description}}" class="md-input"/>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>Error Message (En) *</label>
                                                <input name="eng_error_message" type="text" value="{{$row->eng_error_message}}" class="md-input" required/>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <label>Error Message (Ar) *</label>
                                                <input name="arb_error_message" type="text" value="{{$row->arb_error_message}}" class="md-input" required/>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>No. of codes to generate (Optional)</label>
                                                <input name="no_of_codes" type="number" class="md-input"/>
                                                <small style="color: red;">(If added then it will generate additional new codes for its prefix)</small>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
                                                <label>Status (In-Active / Active)</label><br>
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3" name="status" value="1" {{$row->status == 1 ? 'checked' : ''}}/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md-fab-wrapper">
                                        <button type="submit" class="md-fab md-fab-accent">
                                            <i class="material-icons">save</i>
                                        </button>
                                        <a class="md-fab md-fab-accent" href="<?php echo custom::baseurl('admin/app-popup-promo-codes'); ?>">
                                            <i class="material-icons">arrow_back</i>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
