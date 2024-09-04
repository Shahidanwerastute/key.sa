@extends('admin.layouts.template')



@section('content')

<div id="page_content">

  <div id="page_content_inner">

  	<form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false" class="ajax_form">

    	<input type="hidden" name="table_name" value="contact_us">

        <input type="hidden" name="id" value="1">

        <input type="hidden" name="type" value="cu">

    <div class="md-card">

      <div class="md-card-content">

        <h3 class="heading_a">Edit About Us</h3>

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-1-2">

                    <div class="uk-width-1-1">

                        <div id="file_upload-drop" class="uk-file-upload">

                            <p class="uk-text">Upload Banner Image</p>

                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>

                            <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="banner_image"></a>

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

                        @if($content->banner_image)

                            <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->banner_image }}" alt="Banner Image" data-uk-modal="{target:'#modal_lightbox'}" width="400">

                        @endif

                    </div>

                </div>

                <div class="uk-width-medium-1-2">

                    <div class="uk-form-row">
                        <label>English Alt</label>
                        <input type="text" class="md-input" value="@if($content->banner_image_eng_alt) {{ $content->banner_image_eng_alt }}@endif" name="banner_image_eng_alt" style="font-size: 12px;" />
                    </div>
                </div>
                <div class="uk-width-medium-1-2">

                    <div class="uk-form-row">
                        <label>Arabic Alt</label>
                        <input type="text" class="md-input" value="@if($content->banner_image_arb_alt) {{ $content->banner_image_arb_alt }}@endif" name="banner_image_arb_alt" style="font-size: 12px;" />
                    </div>
                </div>
                <div class="uk-width-medium-1-2">

                    <div class="uk-form-row">
                        @if($content->image_phone)
                            <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image_phone }}" alt="Banner Image For Phone" data-uk-modal="{target:'#modal_image_phone'}" width="400">
                        @endif
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div class="uk-width-1-1">
                        <div id="file_upload-drop" class="uk-file-upload">
                            <p class="uk-text">Upload Banner Image For Phone</p>
                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                            <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image_phone"></a>
                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                            <p style="color:red; font-size:12px">(1080 x 592 pixels)</p>
                        </div>
                        <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                            <div class="uk-progress-bar" style="width:0">0%</div>
                        </div>
                    </div>
                </div>

                <div class="uk-width-medium-1-2">

                    <div class="uk-form-row">
                        <label>English Alt</label>
                        <input type="text" class="md-input" value="@if($content->image_phone_eng_alt) {{ $content->image_phone_eng_alt }}@endif" name="image_phone_eng_alt" style="font-size: 12px;" />
                    </div>
                </div>
                <div class="uk-width-medium-1-2">

                    <div class="uk-form-row">
                        <label>Arabic Alt</label>
                        <input type="text" class="md-input" value="@if($content->image_phone_arb_alt) {{ $content->image_phone_arb_alt }}@endif" name="image_phone_arb_alt" style="font-size: 12px;" />
                    </div>
                </div>



                <div class="uk-width-medium-1-6">

                  <div class="uk-form-row">

                    <label>English Title</label>

                    

                    <input type="text" class="md-input" value="@if($content->eng_title) {{ $content->eng_title }}@endif" name="eng_title" />

                  </div>

                  

                </div>

                <div class="uk-width-medium-1-6">

                  <div class="uk-form-row">

                    <input type="text" class="md-input" value="@if($content->eng_small_title) {{ $content->eng_small_title }}@endif" name="eng_small_title" style="font-size: 12px;" />

                  </div>

                  

                </div>

                <div class="uk-width-medium-1-6">

                </div>

                <div class="uk-width-medium-1-6">

                  <div class="uk-form-row">

                    <label>Arabic Title</label>

                    <input type="text" class="md-input" value="@if($content->arb_title) {{ $content->arb_title }}@endif" name="arb_title" />

                  </div>

                  

                </div>

                <div class="uk-width-medium-1-6">

                  <div class="uk-form-row">

                    <input type="text" class="md-input" value="@if($content->arb_small_title) {{ $content->arb_small_title }}@endif" name="arb_small_title" style="font-size: 12px;" />

                  </div>

                  

                </div>

                



                       

            </div>

      </div>

    </div>

    

    <div class="md-card">

      <div class="md-card-content">

        <h3 class="heading_a">Content</h3>

            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-1-2">

                  <label>English Address 1</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_address_1" id="eng_address_1">@if($content->eng_address_1) {{ $content->eng_address_1 }}@endif</textarea>

                  </div>

                </div>

                <div class="uk-width-medium-1-2">

                  <label>Arabic Address 1</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_address_1" id="arb_address_1">@if($content->arb_address_1) {{ $content->arb_address_1 }}@endif</textarea>

                  </div>

                </div>  
                
                <div class="uk-width-medium-1-2">

                  <label>English Address 2</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_address_2" id="eng_address_2">@if($content->eng_address_2) {{ $content->eng_address_2 }}@endif</textarea>

                  </div>

                </div>

                <div class="uk-width-medium-1-2">

                  <label>Arabic Address 2</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_address_2" id="arb_address_2">@if($content->arb_address_2) {{ $content->arb_address_2 }}@endif</textarea>

                  </div>

                </div>
                
                <div class="uk-width-medium-1-2">

                  <label>English Address 3</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_address_3" id="eng_address_3">@if($content->eng_address_3) {{ $content->eng_address_3 }}@endif</textarea>

                  </div>

                </div>

                <div class="uk-width-medium-1-2">

                  <label>Arabic Address 3</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_address_3" id="arb_address_3">@if($content->arb_address_3) {{ $content->arb_address_3 }}@endif</textarea>

                  </div>

                </div>
                
                <div class="uk-width-medium-1-2">

                  <label>English Contact Form Description</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_contact_form_desc" id="eng_contact_form_desc">@if($content->eng_contact_form_desc) {{ $content->eng_contact_form_desc }}@endif</textarea>

                  </div>

                </div>

                <div class="uk-width-medium-1-2">

                  <label>Arabic Contact Form Description</label>

                  <div class="uk-form-row">

                    

                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_contact_form_desc" id="arb_contact_form_desc">@if($content->arb_contact_form_desc) {{ $content->arb_contact_form_desc }}@endif</textarea>

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


        <?php if (custom::rights(11, 'edit'))
        { ?>
    <div class="md-fab-wrapper">

        <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">

            <i class="material-icons"></i>

        </a>

    </div>
        <?php } ?>

    </form> 

   

   <!-- light box for image -->
      @if($content->image_phone)
          <div class="uk-modal" id="modal_image_phone">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image_phone }}" alt=""/>

              </div>
          </div>
      @endif

		   @if($content->banner_image)

               <div class="uk-modal" id="modal_lightbox">

                  <div class="uk-modal-dialog uk-modal-dialog-lightbox">

                      <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>

                      <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->banner_image }}" alt=""/>

                      

                  </div>

               </div>

            @endif

			<!-- end light box for image -->    

  </div>

</div>

@endsection