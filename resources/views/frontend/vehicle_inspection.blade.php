@extends('frontend.layouts.template')
@section('content')
<section class="pricePageSec sec-inspection">
	<div class="div-table">
		<div class="div-cell">
			<div class="container-md">
				<?php if($pickupInspection){ ?>
				<h1>@lang('labels.booking_reference') : <?php echo $pickupInspection->contract_id;?></h1>
				<h1>@lang('labels.car_plate_no') : <?php echo $pickupInspection->plate_no;?></h1>
				<div class="col-inspection">
					<h2 class="detailHeading">@lang('labels.pickup_inspection')</h2>
					<p>@lang('labels.km') : <?php echo $pickupInspection->km;?></p>
					<p>@lang('labels.fuel') : <?php echo $pickupInspection->fuel;?></p>
					<p>@lang('labels.inspection_date_time') : <?php echo $pickupInspection->updated_at;?></p>
				</div>
				<div class="car-container">
					<div class="car-pictures">
						<img class="carInpec-img" src="<?php echo $pickupInspection->image;?>">
					</div>
					<div class="col-images pickup_inspection">
						<?php foreach ($pickUpPhoto as $photo): ?>
						<img src="<?php echo $photo->image;?>" width="100" height="100" alt="Pick Up Inspection">
						<?php endforeach;?>
					</div>
				</div>
				<?php } if($dropOffInspection){ ?>
				<div class="col-inspection">
					<h2 class="detailHeading">@lang('labels.dropOff_inspection')</h2>
					<p>@lang('labels.km') : <?php echo $dropOffInspection->km;?></p>
					<p>@lang('labels.fuel') : <?php echo $dropOffInspection->fuel;?></p>
					<p>@lang('labels.inspection_date_time') : <?php echo $dropOffInspection->updated_at;?></p>
				</div>
				<div class="car-container">
					<div class="car-pictures">
						<img class="carInpec-img" src="<?php echo $dropOffInspection->image;?>">
					</div>
					<div class="col-images drop_off_inspection">
						<?php foreach ($dropOffPhoto as $photo): ?>
						<img src="<?php echo $photo->image;?>" width="100" height="100" alt="Drop off Inspection">
						<?php endforeach;?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<link rel="stylesheet" href="<?php echo $base_url; ?>/public/plugins/watermark/watermarker.css?v=3">
	<div class="optinFormWrapper">
		<div class="optinFormInerWrap">
			<div class="oFContainer">
				<div class="oFTextArea">
					<a class="close-icon" title="Close" href="javascript:void(0);"></a>
					<div class="optinForm">
						<div class="uk-width-medium-1-1 text-center">
							<img >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if($pickupInspection)
		<script>
            var pickup_image_width = '<?php echo $pickupInspection->w; ?>';
            var pickup_image_height = '<?php echo $pickupInspection->h; ?>';
            var pickup_photo = JSON.parse('<?php echo json_encode($pickUpPhoto); ?>');
		</script>
	@endif
	@if($dropOffInspection)
		<script>
            var dropoff_image_height = '<?php echo $dropOffInspection->h; ?>';
            var dropoff_image_width = '<?php echo $dropOffInspection->w; ?>';
            var dropoff_photo = JSON.parse('<?php echo json_encode($dropOffPhoto); ?>');
		</script>
	@endif
	<script src="<?php echo $base_url; ?>/public/plugins/watermark/watermarker.js"></script>
	<script src="<?php echo $base_url; ?>/public/plugins/watermark/report.js?v=8"></script>
</section>
@endsection