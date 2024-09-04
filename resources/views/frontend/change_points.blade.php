@extends('frontend.layouts.template')
@section('content')
<!--<section class="bannerHeadingSec add">
	<div class="container-md">
		<h1>
			<?php //echo $content[$lang.'_title']; ?>
		</h1>
	</div>
</section>-->
<section class="standardPageSec add">
	<div class="container-md">
		<div class="row">
			<div class="col-xs-12">
				<div class="whiteBox1240">
					<div class="aboutUsPg">
						<div class="imgBox">
							<img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['image']; ?>" alt="image" height="332" width="298" />
						</div>
						<div class="textDtl">
							<?php echo $content[$lang.'_desc']; ?>
						</div>
					</div>
					<div class="formAreaC_Us">
						<!--<h1>Chang Your Points Form</h1>-->
						<form method="post" action="<?php echo $lang_base_url.'/saveChangePoints'; ?>" class="change_points_form" onsubmit="return false;">

							<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12 col">
									<label>@lang('labels.name')</label>
									<input type="text" id="name" name="name" placeholder="@lang('labels.write')"  />
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12 col">
									<label>@lang('labels.email')</label>
									<input type="text" id="email" name="email" class="" placeholder="@lang('labels.write')" />
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12 col">
									<label>@lang('labels.id_number')</label>
									<input type="text" id="id_number" name="id_number" class="" placeholder="@lang('labels.write')" />
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12 col">
									<label>@lang('labels.mobile')</label>
									<input type="text" id="mobile" class="phone" name="mobile" placeholder="@lang('labels.write')"  />
								</div>

								<div class="col-xs-12 col">
									<label for="checkbox1" class="lbl-agree">
										<input id="checkbox1" type="checkbox" class="accept_terms" name="checkbox" value="1">
										@lang("labels.i_accept_the") @lang("labels.terms_and_conditions"). *
									</label>
								</div>

								<div class="col-xs-12 col">
									<div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
								</div>

								<div class="col-xs-12">
									<input type="submit" value="@lang('labels.submit_btn')" class="edBtn submit_btn" />
									<span class="successMsg" style="display: none;"><img src="{{custom::baseurl('public/frontend/images/loader.gif')}}" style="height: 70px; width: 70px;"></span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection