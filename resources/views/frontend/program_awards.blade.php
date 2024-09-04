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
		<div class="servicesSec">
			<?php foreach ($program_awards as $program_award){ ?>

				<div class="whiteBox1240 second">
					<?php echo $program_award[$lang.'_description']; ?>
					<p>&nbsp;</p>
					<div class="">
						<img class="img-responsive" src="<?php echo $base_url; ?>/public/uploads/<?php echo $program_award['image']; ?>" alt="Car Rental" height="" width="" />
					</div>

				</div>
			<?php } ?>
		</div>
	</div>
</section>
@endsection