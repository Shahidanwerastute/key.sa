@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false" class="ajax_form">
                <input type="hidden" name="table_name" value="car_selling">
                <input type="hidden" name="id" value="1">
                <input type="hidden" name="type" value="car_sell">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_a">Manage Car Selling Page</h3>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <div id="CarsSellingTopSlider"></div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English Top Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_top_desc" id="eng_top_desc">@if($content->eng_top_desc) {{ $content->eng_top_desc }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic Top Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_top_desc" id="arb_top_desc">@if($content->arb_top_desc) {{ $content->arb_top_desc }}@endif</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_a">Company Images</h3>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-large-1-2">
                                <div class="uk-width-1-1">
                                    <div id="file_upload-drop" class="uk-file-upload">
                                        <p class="uk-text">Upload Company Images</p>
                                        <p><small>(Press Ctrl key to select multiple images for this field)</small></p>
                                        <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                        <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="company_images[]" multiple></a>
                                        <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                        <p style="color:red; font-size:12px">(1500 x 280 pixels)</p>
                                    </div>
                                    <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                        <div class="uk-progress-bar" style="width:0">0%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    @if($content->company_images)
                                        @php $company_images = explode(',', $content->company_images); @endphp
                                        @foreach($company_images as $company_image)
                                            <img src="{{ custom::baseurl('/public/uploads') . '/' . $company_image }}"
                                                 alt="Company Image" width="400">
                                            <br>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">
                                    <label>English Alt</label>
                                    <input type="text" class="md-input" value="@if($content->company_images_eng_alt) {{ $content->company_images_eng_alt }}@endif" name="company_images_eng_alt" style="font-size: 12px;" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">
                                    <label>Arabic Alt</label>
                                    <input type="text" class="md-input" value="@if($content->company_images_arb_alt) {{ $content->company_images_arb_alt }}@endif" name="company_images_arb_alt" style="font-size: 12px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <div id="CarsSellingServices"></div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_desc" id="eng_desc">@if($content->eng_desc) {{ $content->eng_desc }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic Description</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_desc" id="arb_desc">@if($content->arb_desc) {{ $content->arb_desc }}@endif</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Recipient Email</label>
                                    <input type="text" class="md-input" value="@if($content->recipient_email) {{ $content->recipient_email }}@endif" name="recipient_email" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Page Meta
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Meta Title</label>
                                    <input type="text" class="md-input" value="@if($content->eng_meta_title) {{ $content->eng_meta_title }}@endif" name="eng_meta_title" />
                                </div>

                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Title</label>
                                    <input type="text" class="md-input" value="@if($content->arb_meta_title) {{ $content->arb_meta_title }}@endif" name="arb_meta_title" />
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Englishn Meta Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="eng_meta_description">@if($content->eng_meta_description) {{ $content->eng_meta_description }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="arb_meta_description">@if($content->arb_meta_description) {{ $content->arb_meta_description }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Englishn Meta Keyword</label>
                                    <textarea cols="30" rows="4" class="md-input" name="eng_meta_keyword">@if($content->eng_meta_keyword) {{ $content->eng_meta_keyword }}@endif</textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Meta Keyword</label>
                                    <textarea cols="30" rows="4" class="md-input" name="arb_meta_keyword">@if($content->arb_meta_keyword) {{ $content->arb_meta_keyword }}@endif</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (custom::rights(37, 'edit'))
                { ?>
                <div class="md-fab-wrapper">
                    <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">
                        <i class="material-icons"></i>
                    </a>
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
@endsection