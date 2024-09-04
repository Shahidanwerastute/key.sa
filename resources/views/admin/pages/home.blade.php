@extends('admin.layouts.template')

@section('content')
<div id="page_content">
  <div id="page_content_inner">
  	<form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false" class="ajax_form">
    	<input type="hidden" name="table_name" value="home">
        <input type="hidden" name="id" value="1">
        <input type="hidden" name="type" value="hm">

        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Content</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-2">
                        <label>English Description</label>
                        <div class="uk-form-row">

                            <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_home_des" id="eng_home_des">@if($content->eng_home_des) {{ $content->eng_home_des }}@endif</textarea>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <label>Arabic Description</label>
                        <div class="uk-form-row">

                            <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_home_des" id="arb_home_des">@if($content->arb_home_des) {{ $content->arb_home_des }}@endif</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Content Mobile Version</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-2">
                        <label>Manage Booking English Description</label>
                        <div class="uk-form-row">

                            <textarea cols="30" rows="4" class="md-input eng_desc" name="mng_booking_mob_desc_eng" id="mng_booking_mob_desc_eng">@if($content->mng_booking_mob_desc_eng) {{ $content->mng_booking_mob_desc_eng }}@endif</textarea>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <label>Manage Booking Arabic Description</label>
                        <div class="uk-form-row">

                            <textarea cols="30" rows="4" class="md-input arb_desc" name="mng_booking_mob_desc_arb" id="mng_booking_mob_desc_arb">@if($content->mng_booking_mob_desc_arb) {{ $content->mng_booking_mob_desc_arb }}@endif</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Banner 1</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Banner Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="b1_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(459 x 542 pixels)</p>
                            </div>

                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">

                        <div class="uk-form-row">
                            @if($content->b1_image)
                                <img width="200" src="{{ custom::baseurl('/public/uploads') . '/' . $content->b1_image }}" alt="Banner 1 Image" data-uk-modal="{target:'#modal_lightbox'}">
                            @endif
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>English Alt</label>
                            <input type="text" class="md-input" value="@if($content->b1_image_eng_alt) {{ $content->b1_image_eng_alt }}@endif" name="b1_image_eng_alt" />
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>Arabic Alt</label>
                            <input type="text" class="md-input" value="@if($content->b1_image_arb_alt) {{ $content->b1_image_arb_alt }}@endif" name="b1_image_arb_alt" />
                        </div>
                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <label>English Title</label>
                            <input type="text" class="md-input" value="@if($content->b1_eng_title) {{ $content->b1_eng_title }}@endif" name="b1_eng_title" />
                        </div>
                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <input type="text" class="md-input" value="@if($content->b1_eng_small_title) {{ $content->b1_eng_small_title }}@endif" name="b1_eng_small_title" style="font-size: 12px;" />
                        </div>
                    </div>


                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <label>Arabic Title</label>
                            <input type="text" class="md-input" value="@if($content->b1_arb_title) {{ $content->b1_arb_title }}@endif" name="b1_arb_title" />

                        </div>
                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <input type="text" class="md-input" value="@if($content->b1_arb_small_title) {{ $content->b1_arb_small_title }}@endif" name="b1_arb_small_title" style="font-size: 12px;" />

                        </div>

                    </div>

                    <div class="uk-width-medium-1-2" style="padding-top:20px;">
                        <div class="uk-form-row">
                            <label>English Read More Link</label>
                            <input type="text" class="md-input" value="@if($content->b1_eng_url) {{ $content->b1_eng_url }}@endif" name="b1_eng_url" />

                        </div>
                    </div>

                    <div class="uk-width-medium-1-2" style="padding-top:20px;">
                        <div class="uk-form-row">
                            <label>Arabic Read More Link</label>
                            <input type="text" class="md-input" value="@if($content->b1_arb_url) {{ $content->b1_arb_url }}@endif" name="b1_arb_url" />

                        </div>
                    </div>

                </div>

            </div>
        </div>

    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Banner 2</h3>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                  <div class="uk-width-1-1">
                      <div id="file_upload-drop" class="uk-file-upload">
                          <p class="uk-text">Upload Banner Image</p>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="b2_image"></a>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <p style="color:red; font-size:12px">(459 x 542 pixels)</p>
                      </div>
                      <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                          <div class="uk-progress-bar" style="width:0">0%</div>
                      </div>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  
                  <div class="uk-form-row">
                     @if($content->b2_image)
                     	<img width="200" src="{{ custom::baseurl('/public/uploads') . '/' . $content->b2_image }}" alt="Banner 2 Image" data-uk-modal="{target:'#modal_lightbox'}" width="400">
                     @endif
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="uk-form-row">
                        <label>English Alt</label>
                        <input type="text" class="md-input" value="@if($content->b2_image_eng_alt) {{ $content->b2_image_eng_alt }}@endif" name="b2_image_eng_alt" />
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="uk-form-row">
                        <label>Arabic Alt</label>
                        <input type="text" class="md-input" value="@if($content->b2_image_arb_alt) {{ $content->b2_image_arb_alt }}@endif" name="b2_image_arb_alt" />
                    </div>
                </div>
                <div class="uk-width-medium-1-4">
                  <div class="uk-form-row">
                    <label>English Title</label>
                      <input type="text" class="md-input" value="@if($content->b2_eng_title) {{ $content->b2_eng_title }}@endif" name="b2_eng_title" />
                  </div>
                </div>

                <div class="uk-width-medium-1-4">
                  <div class="uk-form-row">
                      <input type="text" class="md-input" value="@if($content->b2_eng_small_title) {{ $content->b2_eng_small_title }}@endif" name="b2_eng_small_title" style="font-size: 12px;" />
                  </div>
                </div>
                <div class="uk-width-medium-1-4">
                  <div class="uk-form-row">
                    <label>Arabic Title</label>
                      <input type="text" class="md-input" value="@if($content->b2_arb_title) {{ $content->b2_arb_title }}@endif" name="b2_arb_title" />

                  </div>
                </div>

                <div class="uk-width-medium-1-4">
                  <div class="uk-form-row">
                      <input type="text" class="md-input" value="@if($content->b2_arb_small_title) {{ $content->b2_arb_small_title }}@endif" name="b2_arb_small_title" style="font-size: 12px;" />

                  </div>
                  
                </div>

                <div class="uk-width-medium-1-2" style="padding-top:20px;">
                    <div class="uk-form-row">
                        <label>English Read More Link</label>
                        <input type="text" class="md-input" value="@if($content->b2_eng_url) {{ $content->b2_eng_url }}@endif" name="b2_eng_url" />

                    </div>
                </div>

                <div class="uk-width-medium-1-2" style="padding-top:20px;">
                    <div class="uk-form-row">
                        <label>Arabic Read More Link</label>
                        <input type="text" class="md-input" value="@if($content->b2_arb_url) {{ $content->b2_arb_url }}@endif" name="b2_arb_url" />

                    </div>
                </div>

            </div>

      </div>
    </div>


    <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Banner 3</h3>
                <div class="uk-grid" data-uk-grid-margin>


                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Banner Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="b3_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(459 x 542 pixels)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">

                        <div class="uk-form-row">
                            @if($content->b3_image)
                                <img width="200" src="{{ custom::baseurl('/public/uploads') . '/' . $content->b3_image }}" alt="Banner 3 Image" data-uk-modal="{target:'#modal_lightbox'}">
                            @endif
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>English Alt</label>
                            <input type="text" class="md-input" value="@if($content->b3_image_eng_alt) {{ $content->b3_image_eng_alt }}@endif" name="b3_image_eng_alt" />
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>Arabic Alt</label>
                            <input type="text" class="md-input" value="@if($content->b3_image_arb_alt) {{ $content->b3_image_arb_alt }}@endif" name="b3_image_arb_alt" />
                        </div>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <label>English Title</label>
                            <input type="text" class="md-input" value="@if($content->b3_eng_title) {{ $content->b3_eng_title }}@endif" name="b3_eng_title" />
                        </div>
                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <input type="text" class="md-input" value="@if($content->b3_eng_small_title) {{ $content->b3_eng_small_title }}@endif" name="b3_eng_small_title" style="font-size: 12px;" />

                        </div>

                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <label>Arabic Title</label>
                            <input type="text" class="md-input" value="@if($content->b3_arb_title) {{ $content->b3_arb_title }}@endif" name="b3_arb_title" />

                        </div>
                    </div>

                    <div class="uk-width-medium-1-4">
                        <div class="uk-form-row">
                            <input type="text" class="md-input" value="@if($content->b3_arb_small_title) {{ $content->b3_arb_small_title }}@endif" name="b3_arb_small_title" style="font-size: 12px;" />

                        </div>

                    </div>

                    <div class="uk-width-medium-1-2" style="padding-top:20px;">
                        <div class="uk-form-row">
                            <label>English Read More Link</label>
                            <input type="text" class="md-input" value="@if($content->b3_eng_url) {{ $content->b3_eng_url }}@endif" name="b3_eng_url" />

                        </div>
                    </div>

                    <div class="uk-width-medium-1-2" style="padding-top:20px;">
                        <div class="uk-form-row">
                            <label>Arabic Read More Link</label>
                            <input type="text" class="md-input" value="@if($content->b3_arb_url) {{ $content->b3_arb_url }}@endif" name="b3_arb_url" />

                        </div>
                    </div>



                </div>

            </div>
        </div>


        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Mobile Eng Image</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="mobile_eng_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(1080 x 603)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">

                        <div class="uk-form-row">
                            @if($content->mobile_eng_image)
                                <img width="200" src="{{ custom::baseurl('/public/uploads') . '/' . $content->mobile_eng_image }}" alt="Mobile Eng Image" data-uk-modal="{target:'#modal_lightbox'}">
                            @endif
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>English Alt</label>
                            <input type="text" class="md-input" value="@if($content->mobile_eng_image_alt) {{ $content->mobile_eng_image_alt }}@endif" name="mobile_eng_image_alt" />
                        </div>

                    </div>

                </div>

            </div>
        </div>


        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Mobile Arb Image</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="mobile_arb_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(1080 x 603)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">

                        <div class="uk-form-row">
                            @if($content->mobile_arb_image)
                                <img width="200" src="{{ custom::baseurl('/public/uploads') . '/' . $content->mobile_arb_image }}" alt="Mobile Arb Image" data-uk-modal="{target:'#modal_lightbox'}">
                            @endif
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>Arabic Alt</label>
                            <input type="text" class="md-input" value="@if($content->mobile_arb_image_alt) {{ $content->mobile_arb_image_alt }}@endif" name="mobile_arb_image_alt" />
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="md-card">
            <div class="md-card-toolbar">
                <h3 class="md-card-toolbar-heading-text">
                    Keyword
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
                            <label>English Meta Description</label>
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
                            <label>English Meta Keyword</label>
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



        <?php if (custom::rights(11, 'edit'))
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