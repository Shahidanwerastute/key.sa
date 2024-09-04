@extends('admin.layouts.template')

@section('content')
<div id="page_content">
  <div id="page_content_inner">
    <div class="md-card">
      <div class="md-card-content">
        <h3 class="heading_a">Edit a Category</h3>
        <form action="<?php echo custom::baseurl('/');?>/admin/category/action" method="post" onsubmit="return false" class="ajax_form">
		<div class="uk-grid" data-uk-grid-margin>
		
		<input type="hidden" name="form_type" value="update">
		<input type="hidden" name="id" value="<?php echo $category->id;?>">
		<div class="uk-width-medium-1-2">
            <select id="select_demo_5" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip" name="parent_id">
              <option value="">Select Parent Category</option>
			  <option value="0" <?php echo ($category->parent_id == '0' ? 'selected' : ''); ?>>Top</option>
			  <?php
			  getCategoriesOptionListing(0,'',$category->parent_id)
			 /* if($categories)
			  {
				  foreach($categories as $cat)
				  {
					 $selected = '';
					 if($cat->id == $category->parent_id)
					 {
						 $selected = 'selected';
					 }
					 echo '<option value="'.$cat->id.'" '.$selected.'>'.$cat->eng_name.'</option>'; 
				  }
			  }*/
			  ?>
              
            </select>
        </div>
			<div class="uk-width-medium-1-4">
			<?php 
			$active_check = '';
			if($category->active == 'On')
			{
				$active_check = 'checked';
			}
			?>
            <input type="checkbox" data-switchery data-switchery-size="large" <?php echo $active_check ;?> id="switch_demo_large" name="active" />
            <label for="switch_demo_large" class="inline-label">Active</label>
           
			</div> 
          <div class="uk-width-medium-1-2">
            <div class="uk-form-row">
              <label>English Name</label>
              <input type="text" class="md-input" value="<?php echo $category->eng_name;?>" name="eng_name" />
            </div>
			
          </div>
          <div class="uk-width-medium-1-2">
            <div class="uk-form-row">
              <label>Arabic Name</label>
              <input type="text" class="md-input" value="<?php echo $category->arb_name;?>" name="arb_name" />
            </div>
            
          </div>
		  
		  <div class="uk-width-medium-1-2">
            
            <div class="uk-form-row">
              <label>English Description</label>
              <textarea cols="30" rows="4" class="md-input" name="eng_content"><?php echo $category->eng_content;?></textarea>
            </div>
          </div>
          <div class="uk-width-medium-1-2">
            
            <div class="uk-form-row">
              <label>Arabic Description</label>
              <textarea cols="30" rows="4" class="md-input" name="arb_content"><?php echo $category->arb_content;?></textarea>
            </div>
          </div>
		  <div class="uk-width-medium-1-1">
            
            <div class="uk-form-row">
              <label>Category Image</label>
               <img src="<?php echo base_url().'uploads/images/categories/'.$category->eng_image; ?>" alt="Category Image" data-uk-modal="{target:'#modal_lightbox'}" width="100">
            </div>
          </div>
		  
			
			  <div class="uk-width-large-1-1">
                    <h3 class="heading_a">
                        Upload Category Image
                       
                    </h3>
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Drop file to upload</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file" name="image"></a>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                        </div>
                    </div>
                </div>
			
			<div class="uk-width-large-1-1">
           
              <span class="uk-input-group-addon"><a class="md-btn submit_ajax_form" href="javascript:void(0);">Update</a></span> 
          </div>
	
        </div>
       </form> 
      </div>
    </div>
   
   <!-- light box for image -->
		   <div class="uk-modal" id="modal_lightbox">
                                <div class="uk-modal-dialog uk-modal-dialog-lightbox">
                                    <button type="button" class="uk-modal-close uk-close uk-close-alt"></button>
                                    <img src="<?php echo base_url().'uploads/images/categories/'.$category->eng_image; ?>" alt=""/>
                                    
                                </div>
           </div>
			<!-- end light box for image -->   
    
    
    
  </div>
</div>
    @endsection