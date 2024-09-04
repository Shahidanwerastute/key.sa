@extends('frontend.layouts.template')

@section('content')

	<style>
		.careerForm input[type="radio"] {
			opacity: 1;
		}

		.careerForm label {
			font-size: 14px;
		}
		.select2-results__option,
		.select2-results__option[aria-selected]{
			max-width: unset;
		}
	</style>
	<style>
		.standardPageSec input::placeholder{
			text-transform: unset !important;
		}
	</style>

<section class="textBannerSec" >
	<div class="container-md">
		<?php //echo "<pre>"; print_r($content); ?>
		<h1>
			<?php echo $content[$lang.'_title']; ?>
		</h1>
		<p><?php echo $content[$lang.'_desc']; ?></p>
	</div>
</section>
<section class="standardPageSec BannerTextSty">
	<div class="container-md">
		<form action="<?php echo $lang_base_url.'/saveCareerForm'; ?>" method="post" class="careerForm" enctype="multipart/form-data">
			<div class="regisNewUserPg">
				<div class="whiteBox1240">
					<div class="regFormOne">

						<div class="row noFloatingRow">
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.full_name') *</label>
								<input type="text" name="name" placeholder="@lang('labels.write')">
							</div>
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.date_of_birth') *</label>
								<input type="text" name="dob" placeholder="@lang('labels.select')" class="dob_dp">
							</div>
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.nationality') *</label>
								<select name="nationality" class="searchable">
									<option value="">@lang('labels.select')</option>
									@foreach($nationalities as $nationality)
										<?php
										if($lang == "eng")
										    $title = $nationality->eng_country_name;
										else
											$title = $nationality->arb_country_name;
										?>
									<option value="{{$nationality->oracle_reference_number.'|'.$nationality->eng_country_name}}">{{$title}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="row noFloatingRow">
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.email') *</label>
								<input type="email" name="email" placeholder="@lang('labels.write')">
							</div>
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.id_number') *</label>
								<input type="text" name="id_number">
							</div>
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.mobile_no') *</label>
								<input type="text" name="mobile" class="phone number">
							</div>
						</div>
						<div class="row noFloatingRow">
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label>@lang('labels.department') *</label>
								<select name="department_id">
									<option value="">@lang('labels.select')</option>
								<?php foreach ($departments as $department){ ?>
                                    <?php
                                    if($lang == "eng")
                                        $dTitle = $department->eng_title;
                                    else
                                        $dTitle = $department->arb_title;
                                    ?>
									<option value="<?php echo $department->id."|".$department->email.'|'.$department->eng_title;?>"><?php echo $dTitle; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label for="city"><?php echo ($lang == 'eng' ? 'City' : 'المدينة'); ?> *</label>
								<select name="city" id="city">
                                    <option value="">@lang('labels.select')</option>
                                    <?php foreach ($career_cities as $city)
                                    { ?>
                                    <option value="<?php echo ($lang == 'eng' ? $city->eng_title : $city->arb_title); ?>"><?php echo ($lang == 'eng' ? $city->eng_title : $city->arb_title); ?></option>
                                    <?php } ?>
                                </select>
							</div>
                            <div class="col-lg-4 col-md-6 isNoFloat">
                                <label for="language"><?php echo ($lang == 'eng' ? 'Language(s)' : 'اللغات'); ?> *</label>
                                <select name="language[]" id="language" class="multiselect" multiple>
                                    <?php foreach ($languages as $language)
                                    { ?>
                                    <option value="<?php echo ($lang == 'eng' ? $language->eng_title : $language->arb_title); ?>"><?php echo ($lang == 'eng' ? $language->eng_title : $language->arb_title); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
						</div>
						<br>
						<div class="row noFloatingRow">
							<div class="col-md-6 col-md-6 isNoFloat">
								<label>@lang('labels.do_you_have_work_experience')</label>
								<input type="radio" name="do_you_have_experience" value="0" checked>@lang('labels.walkin_no')
								<input type="radio" name="do_you_have_experience" value="1">@lang('labels.walkin_yes')
							</div>
						</div>
						<div class="row noFloatingRow append_here" style="display: none;">
							<div class="col-md-12 isNoFloat">
								<a href="javascript:void(0);" style="color: #868686;float: <?php echo ($lang == 'eng' ? 'right' : 'left'); ?>;" onclick="add_experience_section_for_career_page();"><h6>@lang('labels.add_more')</h6></a>
							</div>
							<div class="col-md-3 col-md-6 isNoFloat">
								<label><?php echo ($lang == 'eng' ? 'Company Name' : 'اسم الشركة'); ?></label>
								<input type="text" name="company_name[]" placeholder="@lang('labels.write')">
							</div>
							<div class="col-md-3 col-md-6 isNoFloat">
								<label><?php echo ($lang == 'eng' ? 'Job Title' : 'المسمى الوظيفي'); ?></label>
								<input type="text" name="job_title[]" placeholder="@lang('labels.write')">
							</div>
							<div class="col-md-3 col-md-6 isNoFloat">
								<label><?php echo ($lang == 'eng' ? 'From Date' : 'من تاريخ'); ?></label>
								<input type="text" name="from_date[]" placeholder="@lang('labels.select')" class="career_page_experience_datepicker">
							</div>
							<div class="col-md-3 col-md-6 isNoFloat">
								<label><?php echo ($lang == 'eng' ? 'To Date' : 'إلى تاريخ'); ?></label>
								<input type="text" name="to_date[]" placeholder="@lang('labels.select')" class="career_page_experience_datepicker">
							</div>
						</div>
						<br>
						<div class="row noFloatingRow">
							<div class="col-md-12 col-md-12 isNoFloat">
								<label for="qualification"><?php echo ($lang == 'eng' ? 'Education /  Qualification' : 'التعليم / الخبرات'); ?> *</label>
								<textarea name="qualification" id="qualification" rows="2" placeholder="@lang('labels.write')" style="height: 60px;"></textarea>
							</div>
						</div>
						<div class="row noFloatingRow">
							<div class="col-lg-4 col-md-6 isNoFloat">
								<label><?php echo ($lang == 'eng' ? 'LinkedIn Profile URL' : 'رابط LinkedIn'); ?></label>
								<input type="text" name="linkedin_profile_url" placeholder="@lang('labels.write')" />
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-md-12 subBtnSec">
								<div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
							</div>
							<div class="col-md-12 subBtnSec text-left">
								<br /><br />
								<input type="submit" class="edBtn submit_btn" value="@lang('labels.submit_btn')" />
								<span class="successMsg" style="display: none;"><img src="{{custom::baseurl('public/frontend/images/loader.gif')}}" style="height: 70px; width: 70px;"></span>

								<br /><br />
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>

@endsection