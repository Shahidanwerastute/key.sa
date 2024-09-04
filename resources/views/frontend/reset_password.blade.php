@extends('frontend.layouts.template')

@section('content')

<section class="textBannerSec" >
	<div class="container-md">
		<h1>
			 <?php /*echo 'Reset'; */?><!--
                <span><?php /*echo 'Password'; */?></span>-->
			<?php
				 if ($lang == "eng")
				     {
				         $reset_password_txt = "Reset <span>Password</span>";
					 }else{
                     $reset_password_txt = "إعادة تعيين الرقم السري";
				 }

				 echo $reset_password_txt;
				 ?>
		</h1>
		
	</div>
</section>

<section class="standardPageSec BannerTextSty">
	<div class="container-md">
		<form  name="resetForm" method="post" action="<?php echo $lang_base_url; ?>/change_password" class="changePassword" onsubmit="return false;">
			<div class="regisNewUserPg">
				<div class="whiteBox1240">
					<div class="regFormOne">
						<div class="row noFloatingRow">
							<div class="col-md-4 col-sm-6 isNoFloat">
								<label><?php echo ($lang == "eng" ? "Password" : "الرقم السري"); ?></label>
								<input type="password" placeholder="@lang('labels.write')" name="password" class="required password"/>
							</div>

							<div class="col-md-4 col-sm-6 isNoFloat">
								<label><?php echo ($lang == "eng" ? "Confirm Password" : "إعادة تأكيد الرقم السري"); ?></label>
								<input type="password" placeholder="@lang('labels.write')" name="confirm_password" class="required confirm_password"/>
							</div>
							<input type="hidden" name="email" value="<?php echo $user_email; ?>">
							<div class="col-md-12 subBtnSec text-left ">
								<label>&nbsp;</label>
								<input type="submit" class="edBtn redishButton" value="<?php echo ($lang == "eng" ? "Save" : "حفظ"); ?>"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>

@endsection