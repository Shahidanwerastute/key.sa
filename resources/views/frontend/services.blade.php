@extends('frontend.layouts.template')

@section('content')

<section class="bannerHeadingSec" style="background-image: url('<?php echo $base_url; ?>/public/uploads/<?php echo $content['banner_image']; ?>');">
	<div class="container">
		<h1>
			<span><?php echo $content[$lang.'_small_title']; ?></span>
			<?php echo $content[$lang.'_title']; ?>
		</h1>
	</div>
</section>
<section class="standardPageSec">
	<div class="container-md">
		<div class="servicesSec">
			<div class="whiteBox1240 first colored-li">
				<?php echo $content[$lang.'_desc1']; ?>
			</div>
			<div class="whiteBox1240 second">
				<div class="textSec">
					<?php echo $content[$lang.'_desc2']; ?>
				</div>
				<div class="imgBox">
					<img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['image1']; ?>" alt="{{ $lang == 'eng' ? $content['image1_eng_alt'] : $content['image1_arb_alt'] }}" height="366" width="340" />
				</div>
			</div>
			<div class="whiteBox1240 third">
				<div class="textSec">
					<?php echo $content[$lang.'_desc3']; ?>
				</div>
				<div class="imgBox">
					<img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['image2']; ?>" alt="{{ $lang == 'eng' ? $content['image2_eng_alt'] : $content['image2_arb_alt'] }}" height="636" width="340" />
				</div>
			</div>
			<div class="whiteBox1240 fourth">
				<div class="textSec">
					<?php echo $content[$lang.'_desc4']; ?>
				</div>
				<div class="imgBox">
					<img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['image2']; ?>" alt="{{ $lang == 'eng' ? $content['image2_eng_alt'] : $content['image2_arb_alt'] }}" height="188" width="340" />
				</div>
			</div>
		</div>
	</div>
</section>
@endsection