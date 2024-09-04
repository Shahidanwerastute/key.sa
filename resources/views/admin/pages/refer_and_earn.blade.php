@extends('admin.layouts.template')
@section('content')

    <div id="page_content">

        <div id="page_content_inner">

            <form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false" class="ajax_form">

                <input type="hidden" name="table_name" value="refer_and_earn_content">

                <input type="hidden" name="id" value="1">

                <input type="hidden" name="type" value="rf">

                <div class="md-card">

                    <div class="md-card-content">

                        <h3 class="heading_a">Edit Refer & Earn Page Content</h3>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">

                                    <label>English Top Heading</label>

                                    <input type="text" class="md-input" value="@if($content->eng_top_heading) {{ $content->eng_top_heading }}@endif" name="eng_top_heading" />

                                </div>



                            </div>
                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">

                                    <label>Arabic Top Heading</label>

                                    <input type="text" class="md-input" value="@if($content->arb_top_heading) {{ $content->arb_top_heading }}@endif" name="arb_top_heading" />

                                </div>



                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English Top Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_top_description" id="eng_top_description">@if($content->eng_top_description) {{ $content->eng_top_description }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic Top Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_top_description" id="arb_top_description">@if($content->arb_top_description) {{ $content->arb_top_description }}@endif</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Share Message</label>
                                    <input type="text" class="md-input" value="@if($content->eng_share_message) {{ $content->eng_share_message }}@endif" name="eng_share_message" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Share Message</label>
                                    <input type="text" class="md-input" value="@if($content->arb_share_message) {{ $content->arb_share_message }}@endif" name="arb_share_message" />
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Share and Earn Button Bottom Text</label>
                                    <input type="text" class="md-input" value="@if($content->eng_share_and_earn_button_amount_text) {{ $content->eng_share_and_earn_button_amount_text }}@endif" name="eng_share_and_earn_button_amount_text" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Share and Earn Button Bottom Text</label>
                                    <input type="text" class="md-input" value="@if($content->arb_share_and_earn_button_amount_text) {{ $content->arb_share_and_earn_button_amount_text }}@endif" name="arb_share_and_earn_button_amount_text" />
                                </div>
                            </div>
                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English How It Works Title 1</label>
                                    <input type="text" class="md-input" value="@if($content->eng_how_it_works_title_1) {{ $content->eng_how_it_works_title_1 }}@endif" name="eng_how_it_works_title_1" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic How It Works Title 1</label>
                                    <input type="text" class="md-input" value="@if($content->arb_how_it_works_title_1) {{ $content->arb_how_it_works_title_1 }}@endif" name="arb_how_it_works_title_1" />
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English How It Works Description 1</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_how_it_works_description_1" id="eng_how_it_works_description_1">@if($content->eng_how_it_works_description_1) {{ $content->eng_how_it_works_description_1 }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic How It Works Description 1</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_how_it_works_description_1" id="arb_how_it_works_description_1">@if($content->arb_how_it_works_description_1) {{ $content->arb_how_it_works_description_1 }}@endif</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English How It Works Title 2</label>
                                    <input type="text" class="md-input" value="@if($content->eng_how_it_works_title_2) {{ $content->eng_how_it_works_title_2 }}@endif" name="eng_how_it_works_title_2" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic How It Works Title 2</label>
                                    <input type="text" class="md-input" value="@if($content->arb_how_it_works_title_2) {{ $content->arb_how_it_works_title_2 }}@endif" name="arb_how_it_works_title_2" />
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English How It Works Description 2</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_how_it_works_description_2" id="eng_how_it_works_description_2">@if($content->eng_how_it_works_description_2) {{ $content->eng_how_it_works_description_2 }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic How It Works Description 2</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_how_it_works_description_2" id="arb_how_it_works_description_2">@if($content->arb_how_it_works_description_2) {{ $content->arb_how_it_works_description_2 }}@endif</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English How It Works Title 3</label>
                                    <input type="text" class="md-input" value="@if($content->eng_how_it_works_title_3) {{ $content->eng_how_it_works_title_3 }}@endif" name="eng_how_it_works_title_3" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic How It Works Title 3</label>
                                    <input type="text" class="md-input" value="@if($content->arb_how_it_works_title_3) {{ $content->arb_how_it_works_title_3 }}@endif" name="arb_how_it_works_title_3" />
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English How It Works Description 3</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_how_it_works_description_3" id="eng_how_it_works_description_3">@if($content->eng_how_it_works_description_3) {{ $content->eng_how_it_works_description_3 }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic How It Works Description 3</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_how_it_works_description_3" id="arb_how_it_works_description_3">@if($content->arb_how_it_works_description_3) {{ $content->arb_how_it_works_description_3 }}@endif</textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

            </form>

            <?php if (custom::rights(11, 'edit'))
            { ?>
            <div class="md-fab-wrapper">

                <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">

                    <i class="material-icons"></i>

                </a>

            </div>
            <?php } ?>

        </div>

    </div>

@endsection