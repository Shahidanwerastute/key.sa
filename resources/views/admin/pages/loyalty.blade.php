@extends('admin.layouts.template')
@section('content')
    <div id="page_content">
        <div id="page_content_inner">

            @if(!isset($_REQUEST['listings']))
                <a href="{{custom::baseurl('/admin/page/loyalty?listings=1')}}"><button type="button" class="md-btn md-btn-primary" style="margin-bottom: 20px;margin-left: 86%;">Go To Listings</button></a>
            @else
                <a href="{{custom::baseurl('/admin/page/loyalty')}}"><button type="button" class="md-btn md-btn-primary" style="margin-bottom: 20px;margin-left: 90%;">Go Back</button></a>
            @endif

            @if(!isset($_REQUEST['listings']))
                <form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false"
                      class="ajax_form">
                    <div class="md-card">
                        <div class="md-card-content large-padding">
                            <h3 class="heading_a">Edit Loyalty Page Content</h3>
                            <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                                <input type="hidden" name="table_name" value="loyalty_program">
                                <input type="hidden" name="id" value="1">
                                <input type="hidden" name="type" value="ly">

                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Title (Eng)</label>
                                        <input type="text" class="md-input" value="{{ $content->eng_title }}"
                                               name="eng_title"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Title (Arb)</label>
                                        <input type="text" class="md-input" value="{{ $content->arb_title }}"
                                               name="arb_title"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Sub Title (Eng)</label>
                                        <input type="text" class="md-input" value="{{ $content->eng_sub_title }}"
                                               name="eng_sub_title"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Sub Title (Arb)</label>
                                        <input type="text" class="md-input" value="{{ $content->arb_sub_title }}"
                                               name="arb_sub_title"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Reward Programs Heading (Eng)</label>
                                        <input type="text" class="md-input"
                                               value="{{ $content->eng_reward_programs_heading }}"
                                               name="eng_reward_programs_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Reward Programs Heading (Arb)</label>
                                        <input type="text" class="md-input"
                                               value="{{ $content->arb_reward_programs_heading }}"
                                               name="arb_reward_programs_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Terms & Conditions Heading (Eng)</label>
                                        <input type="text" class="md-input"
                                               value="{{ $content->eng_terms_and_conditions_heading }}"
                                               name="eng_terms_and_conditions_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Terms & Conditions Heading (Arb)</label>
                                        <input type="text" class="md-input"
                                               value="{{ $content->arb_terms_and_conditions_heading }}"
                                               name="arb_terms_and_conditions_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label>Terms & Conditions Description (Eng)</label>
                                    <div class="uk-form-row">
                                        <textarea cols="30" rows="4" class="md-input eng_desc"
                                                  name="eng_terms_and_conditions_description" id="eng_desc">{{ $content->eng_terms_and_conditions_description }}</textarea>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label>Terms & Conditions Description (Arb)</label>
                                    <div class="uk-form-row">
                                        <textarea cols="30" rows="4" class="md-input arb_desc"
                                                  name="arb_terms_and_conditions_description" id="arb_desc">{{ $content->arb_terms_and_conditions_description }}</textarea>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Perks Table Heading (Eng)</label>
                                        <input type="text" class="md-input" value="{{ $content->eng_table_heading }}"
                                               name="eng_table_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Perks Table Heading (Arb)</label>
                                        <input type="text" class="md-input" value="{{ $content->arb_table_heading }}"
                                               name="arb_table_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>FAQs Heading (Eng)</label>
                                        <input type="text" class="md-input" value="{{ $content->eng_faqs_heading }}"
                                               name="eng_faqs_heading"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>FAQs Heading (Arb)</label>
                                        <input type="text" class="md-input" value="{{ $content->arb_faqs_heading }}"
                                               name="arb_faqs_heading"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Page Meta Data
                            </h3>
                        </div>
                        <div class="md-card-content large-padding">
                            <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Title (Eng)</label>
                                        <input type="text" class="md-input" value="{{ $content->eng_meta_title }}"
                                               name="eng_meta_title"/>
                                    </div>

                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Title (Arb)</label>
                                        <input type="text" class="md-input" value="{{ $content->arb_meta_title }}"
                                               name="arb_meta_title"/>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Description (Eng)</label>
                                        <textarea cols="30" rows="4" class="md-input"
                                                  name="eng_meta_description">{{ $content->eng_meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Description (Arb)</label>
                                        <textarea cols="30" rows="4" class="md-input"
                                                  name="arb_meta_description">{{ $content->arb_meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Keyword (Eng)</label>
                                        <textarea cols="30" rows="4" class="md-input"
                                                  name="eng_meta_keyword">{{ $content->eng_meta_keyword }}</textarea>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-form-row">
                                        <label>Meta Keyword (Arb)</label>
                                        <textarea cols="30" rows="4" class="md-input"
                                                  name="arb_meta_keyword">{{ $content->arb_meta_keyword }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (custom::rights(11, 'edit'))
                    { ?>
                    <div class="md-fab-wrapper">
                        <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">
                            <i class="material-icons"></i>
                        </a>
                    </div>
                    <?php } ?>
                </form>
            @endif

            @if(isset($_REQUEST['listings']))
                <div class="md-card">
                    <div class="md-card-content large-padding">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div id="loyaltyCardsListing" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="md-card">
                    <div class="md-card-content large-padding">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div id="loyaltyRewardProgramsListing" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="md-card">
                    <div class="md-card-content large-padding">
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div id="loyaltyFaqsListing" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection