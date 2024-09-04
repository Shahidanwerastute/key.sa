@extends('frontend.layouts.template')

@section('content')

<section class="bannerHeadingSec" style="background-image: url('<?php echo $base_url; ?>/public/uploads/<?php echo $content['banner_image']; ?>');">
	<div class="container-md">
		<h1>
			<span><?php echo $content[$lang.'_small_title']; ?></span>
			<?php echo $content[$lang.'_title']; ?>
		</h1>
	</div>
</section>

<section class="standardPageSec">
	<div class="container-md">

        <?php if($content[$lang.'_description'] != '') { ?>
		<div class="servicesSec">
			<div class="whiteBox1240 second">
				<?php echo $content[$lang.'_description']; ?>
				<br>
					<button type="button" class="redishButtonRound" onclick="interested_in_corporate_sales();"><?php echo $lang == 'eng'?'Contact us':'تواصل معنا'; ?></button>
			</div>
		</div>
			<?php } ?>

		<div class="servicesSec">
            <?php foreach ($corporate_sales as $corporate_sale){ ?>

			<div class="whiteBox1240 second">
                <?php echo $corporate_sale[$lang.'_description']; ?>
				<p>&nbsp;</p>
				<div class="">
					<img class="img-responsive" src="<?php echo $base_url; ?>/public/uploads/<?php echo $corporate_sale['image']; ?>" alt="Car Rental" height="" width="" />
				</div>

			</div>
            <?php } ?>
		</div>
	</div>
</section>

@endsection