@extends('frontend.layouts.template')
@section('content')
<section class="pricePageSec section-ack">
	<div class="div-table">
		<div class="div-cell">
			 <div class="container-md">
				<h1 class="detailHeading">@lang('labels.key_human_less')</h1>
				 <?php
                 if($lang == 'eng'){
                     $acknowledge_text = 'Thanks for the acknowledgment of human less feature. Please login and go to your booking details to get your car.';
                 }else{
                     $acknowledge_text = 'شكرا لاعتراف الإنسان أقل الميزة. يرجى الذهاب إلى تفاصيل الحجز الخاصة بك للحصول على سيارتك';
                 }
				 ?>
				<p><?php echo $acknowledge_text; ?></p>
			 </div>
		</div>
	</div>
</section>
@endsection