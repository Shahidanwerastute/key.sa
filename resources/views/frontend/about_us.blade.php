@extends('frontend.layouts.template')

@section('content')
<style>
	.image-section p{
		font-size: 14px;
	}
	.whiteBox1240.image-section img {
		height: 300px;
		object-fit: cover;
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
		<div class="whiteBox1240">
			<div class="aboutUsPg">
				<div class="textDtl">
					<?php echo $content[$lang.'_desc']; ?>

				</div>
				<div class="imgBox">
					<img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['image1']; ?>" alt="{{ $lang == 'eng' ? $content['image1_eng_alt'] : $content['image1_arb_alt'] }}" height="332" width="298" />
				</div>
			</div>
		</div>
		<div class="whiteBox1240 image-section">
			<div class="textDtl">
				<h1 class="text-center pb-5"><?php echo ($lang == 'eng' ? 'AWARDS AND CERTIFICATES' : 'الجوائز والشهادات'); ?></h1>
			</div>
			<div class="row  justify-content-evenly ">
				<div class="col-md-3">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/ISO-9001.jpg" alt="ISO 9001">
					<div class="text-content text-center">
						<p class="mb-0 py-1 mt-2"><?php echo ($lang == 'eng' ? 'ISO 9001:2015' : 'آيزو 9001:2015'); ?></p>
						<p><?php echo ($lang == 'eng' ? 'Quality management systems' : 'نظام إدارة الجودة'); ?></p>
					</div>
				</div>
				<div class="col-md-3">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/ISO-14001.jpg" alt="ISO 14001">
					<div class="text-content text-center">
						<p class="mb-0 py-1 mt-2"><?php echo ($lang == 'eng' ? 'ISO 14001:2015' : 'آيزو 14001:2015 '); ?></p>
						<p><?php echo ($lang == 'eng' ? 'Environmental management systems ' : 'نظام إدارة البيئة'); ?></p>
					</div>
				</div>
				<div class="col-md-3">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/ISO-45001.jpg" alt="ISO 45001">
					<div class="text-content text-center">
						<p class="mb-0 py-1 mt-2"><?php echo ($lang == 'eng' ? 'ISO 45001:2018' : 'آيزو 45001:2018 '); ?></p>
						<p><?php echo ($lang == 'eng' ? ' Occupational Health and Safety Management Systems ' : 'نظام إدارة السلامة والصحة المهنية'); ?></p>
					</div>
				</div>
			</div>

			<div class="row  justify-content-evenly mt-5">
				<div class="col-md-2 text-center">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/Loyalty-award.jpg" alt="ISO 14001">
					<div class="text-content text-center">
						<p><?php echo ($lang == 'eng' ? 'Loyalty And Rewards Program ' : 'برنامج الولاء و المكافآت الأكثر ابتكارًا'); ?></p>

					</div>
				</div>
				<div class="col-md-2 text-center">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/Great-Place-To-Work-logo.png" alt="ISO 9001">
					<div class="text-content text-center">
						<p><?php echo ($lang == 'eng' ? 'Great Place to Work Certified ' : 'اعتماد أفضل بيئة عمل'); ?></p>

					</div>
				</div>
				<div class="col-md-2 text-center">
					<img class="img-fluid" src="<?php echo $base_url; ?>/public/award-images/Services-award.jpg" alt="ISO 45001">
					<div class="text-content text-center">
						<p><?php echo ($lang == 'eng' ? ' Car Renter Service Provider ' : 'خدمة التأجير الأكثر ابتكارًا'); ?></p>

					</div>
				</div>
			</div>

		</div>
	</div>
	
</section>
@endsection