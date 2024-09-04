<?php $social = custom::social_links(); ?>
<section class="social-block">
	<div class="container maxWidth1330">
		<div class="col">
			<strong class="title">FOLLOW OUR</strong>
			<h3>SOCIAL MEDIA</h3>
			<ul class="social-networks">
				<li><a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook"></a></li>
				<li><a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter"></a></li>
				<li><a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin"></a></li>
				<li><a href="<?php echo $social->instagram_link; ?>" class="instagram"></a></li>
				<li><a href="<?php echo $social->youtube_link; ?>" class="youtube"></a></li>
			</ul>
		</div>
		<div class="col">
			<strong class="title">Subscribe to our</strong>
			<h3>Newsletter</h3>
			<form class="newsLetter" action="javascript:void(0);" method="get">
				<input type="email" placeholder="Email Address" />
				<input type="submit" class="btn-contact" value="Subscribe" />
			</form>
		</div>
		<div class="col">
			<strong class="title">CONTACT 24/7</strong>
			<h3>TOLL FREE</h3>
			<div class="contact-details">
				<a href="tel:<?php echo custom::site_phone(); ?>" class="tel"><?php echo custom::site_phone(); ?></a>
				<span class="or">OR</span>
				<a href="http://key.ed.sa/contact.php" class="btn-contact">CONTACT US</a>
			</div>
		</div>
	</div>
</section>