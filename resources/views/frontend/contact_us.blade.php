@extends('frontend.layouts.template')

@section('content')
	<style>
		.formAreaC_Us input::placeholder{
			text-transform: unset !important;
		}
	</style>
<section class="bannerHeadingSec" style="background-image: url('<?php echo $base_url; ?>/public/uploads/<?php echo $content['banner_image']; ?>');">
	<div class="container-md">
		<h1>
			<?php echo $content[$lang.'_title']; ?>
			<span><?php echo $content[$lang.'_small_title']; ?></span>
		</h1>
	</div>
</section>
<section class="standardPageSec">
	<div class="container-md">
		<div class="contactUsSec">
			<div class="whiteBox1240">

				<h1>@lang('labels.addresses_and_locations')</h1>
				<div class="row">
					<div class="col-lg-4">
						<?php echo $content[$lang.'_address_1']; ?>
					</div>
					<div class="col-lg-4">
                        <?php echo $content[$lang.'_address_2']; ?>
					</div>
					<div class="col-lg-4">
                        <?php echo $content[$lang.'_address_3']; ?>
					</div>
				</div>

				<div class="formAreaC_Us">
					<h1>@lang('labels.contact_form')</h1>
					<p><?php echo $content[$lang.'_contact_form_desc']; ?></p>
					<form method="post" action="<?php echo $lang_base_url.'/saveContactUs'; ?>" class="contact_us_frm">

						<div class="row">
							<div class="col-lg-3">
								<label>@lang('labels.name')</label>
								<input type="text" name="name" placeholder="@lang('labels.write')" required />
							</div>
							<div class="col-lg-3">
								<label>@lang('labels.email')</label>
								<input type="email" name="email" class="checkEmailValid" placeholder="@lang('labels.write')" required/>
							</div>

							<div class="col-lg-3">
								<label>@lang('labels.mobile')</label>
								<input type="text" class="phone number" name="mobile" placeholder="@lang('labels.write')" required />
							</div>
							<div class="col-lg-3">
								<label>@lang('labels.country')</label>

								<select name="country" required>
									<option value="">@lang('labels.select')</option>
                                    <?php foreach ($countries as $country){ ?>
									<option value="<?php echo $country->oracle_reference_number; ?>"><?php if($lang == 'eng'){echo $country->eng_country;}else { echo $country->arb_country; } ?></option>
                                    <?php } ?>
								</select>

							</div>
							<div class="col-md-12">
								<label>@lang('labels.enquiry_type')</label>
								{{--<input type="text" placeholder="@lang('labels.select')" />--}}

								<select name="inquiry_type_id" required>
									<option value="">@lang('labels.select')</option>
								@foreach ($inquiries as $inquiry)
                                        <?php
                                        if($lang == 'eng')
                                            $title = $inquiry->eng_title;
                                        else
                                            $title = $inquiry->arb_title;
                                        ?>
									<option value="{{$inquiry->id.'|'.$inquiry->email.'|'.$title}}">{{$title}}</option>
								@endforeach
								</select>
							</div>
							<div class="col-md-12">
								<label>@lang('labels.message')</label>
							<textarea rows="4" name="message" placeholder="@lang('labels.message')" required></textarea>
							<br><br>
							</div>

							<div class="col-md-12">
								<div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
							</div>

							<div class="col-md-12">
								<input type="submit" value="@lang('labels.submit_btn')" class="edBtn submit_btn" />
								<span class="successMsg" style="display: none;"><img src="{{custom::baseurl('public/frontend/images/loader.gif')}}" style="height: 70px; width: 70px;"></span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</section>
@include('frontend/layouts/footer_social_block')
@endsection