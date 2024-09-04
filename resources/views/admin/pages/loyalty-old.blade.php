@extends('admin.layouts.template')
@section('content')
<div id="page_content">
  <div id="page_content_inner">
  	<form action="{{ custom::baseurl('/') }}/admin/page/update" method="post" onsubmit="return false" class="ajax_form">
    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Edit Loyalty</h3>
            <div class="uk-grid" data-uk-grid-margin>



                <input type="hidden" name="table_name" value="loyalty_program">
                <input type="hidden" name="id" value="1">
                <input type="hidden" name="type" value="ly">
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
                
                <div class="uk-width-medium-1-2">
                  <label>English Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_desc" id="eng_desc">@if($content->eng_desc) {{ $content->eng_desc }} @endif</textarea>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <label>Arabic Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_desc" id="arb_desc">@if($content->arb_desc) {{ $content->arb_desc }} @endif</textarea>
                  </div>
                </div> 
            </div>
      </div>
    </div>

        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">Bronze</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Bronze Program Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image4"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(244 x 135 pixels)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            @if($content->image4)
                                <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image4 }}" alt="Banner Image" data-uk-modal="{target:'#modal_lightbox1'}" width="200">
                            @endif
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>English Bronze Title</label>
                            <input type="text" class="md-input" value="@if($content->eng_bronze_prgm_title) {{ $content->eng_bronze_prgm_title }}@endif" name="eng_bronze_prgm_title" />
                        </div>

                    </div>
                    <div class="uk-width-medium-1-2">
                        <div class="uk-form-row">
                            <label>Arabic Bronze Title</label>
                            <input type="text" class="md-input" value="@if($content->arb_bronze_prgm_title) {{ $content->arb_bronze_prgm_title }}@endif" name="arb_bronze_prgm_title" />
                        </div>

                    </div>

                    <div class="uk-width-medium-1-2">
                        <label>English Bronze Description</label>
                        <div class="uk-form-row">
                            <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_bronze_prgm_desc" id="eng_desc5">@if($content->eng_bronze_prgm_desc) {{ $content->eng_bronze_prgm_desc }}@endif</textarea>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <label>Arabic Bronze Description</label>
                        <div class="uk-form-row">
                            <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_bronze_prgm_desc" id="arb_desc5">@if($content->arb_bronze_prgm_desc) {{ $content->arb_bronze_prgm_desc }}@endif</textarea>
                        </div>
                    </div>
                    <!--
                    <div class="uk-width-medium-1-2">
                       <div class="uk-form-row">
                         <label>Silver Value</label>
                         <input type="text" class="md-input" value="" name="silver_prgm_value" />
                       </div>

                     </div>
                     <div class="uk-width-medium-1-2">
                       <div class="uk-form-row">
                         <label>Silver Value Type</label>
                         <select class="md-input" id="silver_prgm_value_type" name="silver_prgm_value_type" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip">
                             <option value="Fixed">Fixed</option>
                             <option value="Percentage">Percentage</option>
                          </select>
                       </div>

                     </div>
                     <div class="uk-form-row daily_offer">

                         <input type="checkbox" data-switchery data-switchery-size="large" id="silver_prgm_active_status" name="silver_prgm_active_status" value="1"  />
                         <label for="silver_prgm_active_status" class="inline-label">Active</label>

                     </div>-->

                </div>
            </div>
        </div>

    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Silver</h3>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-1-2">                       
                  <div class="uk-width-1-1">
                      <div id="file_upload-drop" class="uk-file-upload">
                          <p class="uk-text">Upload Silver Program Image</p>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image1"></a>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <p style="color:red; font-size:12px">(244 x 135 pixels)</p>
                      </div>
                      <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                          <div class="uk-progress-bar" style="width:0">0%</div>
                      </div>
                  </div>
                 </div>

                <div class="uk-width-medium-1-2">                  
                  <div class="uk-form-row">
                    @if($content->image1)
                     	<img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image1 }}" alt="Banner Image" data-uk-modal="{target:'#modal_lightbox1'}" width="200">
					@endif
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>English Silver Title</label>
                    <input type="text" class="md-input" value="@if($content->eng_silver_prgm_title) {{ $content->eng_silver_prgm_title }}@endif" name="eng_silver_prgm_title" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Arabic Silver Title</label>
                    <input type="text" class="md-input" value="@if($content->arb_silver_prgm_title) {{ $content->arb_silver_prgm_title }}@endif" name="arb_silver_prgm_title" />
                  </div>
                  
                </div>
                
                <div class="uk-width-medium-1-2">
                  <label>English Silver Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_silver_prgm_desc" id="eng_desc2">@if($content->eng_silver_prgm_desc) {{ $content->eng_silver_prgm_desc }}@endif</textarea>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <label>Arabic Silver Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_silver_prgm_desc" id="arb_desc2">@if($content->arb_silver_prgm_desc) {{ $content->arb_silver_prgm_desc }}@endif</textarea>
                  </div>
                </div>
               <!-- 
               <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Silver Value</label>
                    <input type="text" class="md-input" value="" name="silver_prgm_value" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Silver Value Type</label>
                    <select class="md-input" id="silver_prgm_value_type" name="silver_prgm_value_type" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip">
                        <option value="Fixed">Fixed</option>
                        <option value="Percentage">Percentage</option>
                     </select>
                  </div>
                  
                </div>
                <div class="uk-form-row daily_offer">
                                            
                    <input type="checkbox" data-switchery data-switchery-size="large" id="silver_prgm_active_status" name="silver_prgm_active_status" value="1"  />
                    <label for="silver_prgm_active_status" class="inline-label">Active</label>
                    
                </div>-->
                
            </div>
      </div>
    </div>
    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Golden</h3>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                      
                    <div class="uk-width-1-1">
                        <div id="file_upload-drop" class="uk-file-upload">
                            <p class="uk-text">Upload Golden Program Image</p>
                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                            <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image2"></a>
                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                            <p style="color:red; font-size:12px">(244 x 135 pixels)</p>
                        </div>
                        <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                            <div class="uk-progress-bar" style="width:0">0%</div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">                  
                  <div class="uk-form-row">
                   @if($content->image2)
                   		<img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image2 }}" alt="Banner Image" data-uk-modal="{target:'#modal_lightbox2'}" width="200">
                   @endif
                  </div>
                </div>              
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>English Golden Title</label>
                    <input type="text" class="md-input" value="@if($content->eng_gold_prgm_title) {{ $content->eng_gold_prgm_title }}@endif" name="eng_gold_prgm_title" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Arabic Golden Title</label>
                    <input type="text" class="md-input" value="@if($content->arb_gold_prgm_title) {{ $content->arb_gold_prgm_title }}@endif" name="arb_gold_prgm_title" />
                  </div>
                  
                </div>
                
                <div class="uk-width-medium-1-2">
                  <label>English Golden Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_gold_prgm_desc" id="eng_desc3">@if($content->eng_gold_prgm_desc) {{ $content->eng_gold_prgm_desc }}@endif</textarea>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <label>Arabic Golden Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_gold_prgm_desc" id="arb_desc3">@if($content->arb_gold_prgm_desc) {{ $content->arb_gold_prgm_desc }}@endif</textarea>
                  </div>
                </div>
                <!--
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Golden Value</label>
                    <input type="text" class="md-input" value="" name="gold_prgm_value" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Golden Value Type</label>
                    <select class="md-input" id="gold_prgm_value_type" name="gold_prgm_value_type" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip">
                        <option value="Fixed">Fixed</option>
                        <option value="Percentage">Percentage</option>
                     </select>
                  </div>
                  
                </div>
                <div class="uk-form-row daily_offer">
                                            
                    <input type="checkbox" data-switchery data-switchery-size="large" id="gold_prgm_active_status" name="gold_prgm_active_status" value="1" />
                    <label for="gold_prgm_active_status" class="inline-label">Active</label>
                    
                </div>-->
            </div>
      </div>
    </div>
    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Platinum</h3>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                  <div class="uk-width-1-1">
                      <div id="file_upload-drop" class="uk-file-upload">
                          <p class="uk-text">Upload Platinum Program Image</p>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image3"></a>
                          <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                          <p style="color:red; font-size:12px">(244 x 135 pixels)</p>
                      </div>
                      <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                          <div class="uk-progress-bar" style="width:0">0%</div>
                      </div>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">                  
                  <div class="uk-form-row">
                     @if($content->image3) 
                     	<img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image3}}" alt="Banner Image" data-uk-modal="{target:'#modal_lightbox3'}" width="200">
                     @endif
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>English Platinum Title</label>
                    <input type="text" class="md-input" value="@if($content->eng_platinum_prgm_title) {{ $content->eng_platinum_prgm_title }}@endif" name="eng_platinum_prgm_title" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Arabic Platinum Title</label>
                    <input type="text" class="md-input" value="@if($content->arb_platinum_prgm_title) {{ $content->arb_platinum_prgm_title }}@endif" name="arb_platinum_prgm_title" />
                  </div>                  
                </div>
                
                <div class="uk-width-medium-1-2">
                  <label>English Platinum Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_platinum_prgm_desc" id="eng_desc4">@if($content->eng_platinum_prgm_desc) {{ $content->eng_platinum_prgm_desc }}@endif</textarea>
                  </div>
                </div>
                <div class="uk-width-medium-1-2">
                  <label>Arabic Platinum Description</label>
                  <div class="uk-form-row">                    
                    <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_platinum_prgm_desc" id="arb_desc4">@if($content->arb_platinum_prgm_desc) {{ $content->arb_platinum_prgm_desc }}@endif</textarea>
                  </div>
                </div>
                <!--
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Platinum Value</label>
                    <input type="text" class="md-input" value="" name="platinum_prgm_value" />
                  </div>
                  
                </div>
                <div class="uk-width-medium-1-2">
                  <div class="uk-form-row">
                    <label>Platinum Value Type</label>
                    <select class="md-input" id="platinum_prgm_value_type" name="platinum_prgm_value_type" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip">
                        <option value="Fixed">Fixed</option>
                        <option value="Percentage">Percentage</option>
                     </select>
                  </div>
                  
                </div>
                <div class="uk-form-row daily_offer">
                                            
                    <input type="checkbox" data-switchery data-switchery-size="large" id="platinum_prgm_active_status" name="platinum_prgm_active_status" value="1" />
                    <label for="platinum_prgm_active_status" class="inline-label">Active</label>
                    
                </div>-->
                                       
            </div>
      </div>
    </div>


        <div class="md-card">
            <div class="md-card-content">
                <h3 class="heading_a">English Image</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload English Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="eng_big_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(1268 x 489 pixels)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2" id="eng_big_img">
                        @if($content->eng_big_image)
                        <div class="uk-form-row">
                            <div style="cursor: pointer;"><i class="material-icons md-color-light-red-600 md-24" onclick="deletLoyaltyImg('<?php echo $content->id; ?>','eng');">highlight_off</i></div>
                                <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->eng_big_image}}" alt="English Image" data-uk-modal="{target:'#modal_lightbox4'}" width="200">

                        </div>

                        @endif
                    </div>

                </div>
            </div>

            <div class="md-card-content">
                <h3 class="heading_a">Arabic Image</h3>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Upload Arabic Image</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="arb_big_image"></a>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p>
                                <p style="color:red; font-size:12px">(1268 x 489 pixels)</p>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2" id="arb_big_img">
                        @if($content->arb_big_image)
                        <div class="uk-form-row">
                            <div style="cursor: pointer;"><i class="material-icons md-color-light-red-600 md-24" onclick="deletLoyaltyImg('<?php echo $content->id; ?>','arb');">highlight_off</i></div>
                                <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->arb_big_image}}" alt="Arabic Image" data-uk-modal="{target:'#modal_lightbox5'}" width="200">

                            @endif
                        </div>

                    </div>

                    <div class="uk-width-medium-1-2">
                        <label>English Text</label>
                        <div class="uk-form-row">
                            <textarea cols="30" rows="4" class="md-input eng_desc" name="eng_text" id="eng_text">@if($content->eng_text) {{ $content->eng_text }}@endif</textarea>
                        </div>
                    </div>

                    <div class="uk-width-medium-1-2">
                        <label>Arabic Text</label>
                        <div class="uk-form-row">
                            <textarea cols="30" rows="4" class="md-input arb_desc" name="arb_text" id="arb_text">@if($content->arb_text) {{ $content->arb_text }}@endif</textarea>
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
   		@if($content->banner_image)
		   <div class="uk-modal" id="modal_lightbox">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->banner_image }}" alt=""/>
                  
              </div>
           </div>
        @endif
		@if($content->image1)
           <div class="uk-modal" id="modal_lightbox1">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image1 }}" alt=""/>
                  
              </div>
           </div>
        @endif
		@if($content->image2)
		   <div class="uk-modal" id="modal_lightbox2">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image2 }}" alt=""/>
                  
              </div>
           </div>
        @endif
		@if($content->image3)
           <div class="uk-modal" id="modal_lightbox3">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image3}}" alt=""/>
                  
              </div>
           </div>
        @endif

      {{--new images--}}

      @if($content->eng_big_image)
          <div class="uk-modal" id="modal_lightbox4">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->eng_big_image}}" alt=""/>

              </div>
          </div>
      @endif
      @if($content->arb_big_image)
          <div class="uk-modal" id="modal_lightbox5">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->arb_big_image}}" alt=""/>

              </div>
          </div>
      @endif


      @if($content->image_phone)
          <div class="uk-modal" id="modal_image_phone">
              <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                  <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                  <img src="{{ custom::baseurl('/public/uploads') . '/' . $content->image_phone }}" alt=""/>

              </div>
          </div>
          @endif
			<!-- end light box for image -->   
    
    
    
  </div>
</div>
    @endsection