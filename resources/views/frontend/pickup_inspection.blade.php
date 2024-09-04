@extends('frontend.layouts.template')
@section('content')
<section class="pricePageSec section-ack">
	<div class="div-table">
		<div class="div-cell">
			 <div class="container-md">
				<h1 class="detailHeading">@lang('labels.inspection_picture')</h1>
				<p>@lang('labels.inspection_date_time') : <?php echo $inspectionDetail->updated_at;?></p>
				<div class="car-pictures">
					<img class="carInpec-img" src="<?php echo $inspectionDetail->image;?>">
				</div>
			 </div>
		</div>
	</div>
</section>
@endsection