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
		<div class="latestNewsSec">
        <?php 
			foreach($content_listing as $newsObj)
			{
				$news = (array)$newsObj;
		?>
                <div class="whiteBox1240">
                    <div class="imgBox">
                        <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $news['image1']; ?>" alt="Picture" height="173" width="302" />
                    </div>
                    <div class="textSec">
                        <h1><?php echo $news[$lang.'_title']; ?></h1>
                        <div class="shortDisc">
                            <?php echo $news[$lang.'_short_desc']; ?>
                        </div>
                        <div class="readMrText">
                            <?php echo $news[$lang.'_desc']; ?>
                        </div>
                        <div class="edBtnRM"><input type="button" value="@lang('labels.read_more')" onClick="readMoreText(this)" /></div>
                    </div>
                </div>
        <?php 
			}
		?>
		</div>
	</div>
</section>
@endsection