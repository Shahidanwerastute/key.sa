@extends('frontend.layouts.template')

@section('content')

<section class="textBannerSec" >
	<div class="container-md">
<?php //echo "<pre>"; print_r($content_listing); exit; ?>
		<h1>
			<?php echo rtrim($content[$lang.'_title'], 's'); ?>
			<strong>s</strong>
		</h1>
		<p><?php echo $content[$lang.'_desc']; ?>	</p>
	</div>
</section>
<section class="standardPageSec BannerTextSty">
	<div class="container-md">
		<!--<div class="whiteBox1240">
			FAQs
		</div>-->
		<div class="faqsAccordion">
	
			<div class="panel-group" id="accordion">
				<?php $count = 1;?>
		<?php foreach($content_listing as $content_list) { ?>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle <?php if($count > 1){echo 'collapsed ';}?>" data-bs-toggle="collapse" data-parent="#accordion" href="#collapse{{$count}}">
								<span class="qusno">Q{{$count}}.</span>
								<?php if($lang == 'eng'){echo $content_list->eng_question;} else{ echo $content_list->arb_question;} ?>

							</a>
						</h4>
					</div>
					<div id="collapse{{$count}}" class="panel-collapse collapse <?php if($count == 1){echo 'in';} ?>">
						<div class="panel-body">

                            <?php if($lang == 'eng') echo $content_list->eng_answer; else echo $content_list->arb_answer; ?>
						</div>
					</div>
				</div>
				
					<?php $count++;
			}
			?>
			
			
			</div>

		

		</div>
	</div>
</section>
@endsection