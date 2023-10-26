<div class="about-container">
	<div class="inner">
		<?php if($image): ?>
			<div class="images" data-aos="flip-left">
				<div class="inner-image">
					<img src="image/<?=$image;?>" alt="image">
				</div>
			</div>
		<?php endif ?>
		<div class="text" data-aos="fade-right">
			<div>
				<?= html_entity_decode($text);?>
				<a href="<?php echo $url; ?>" class="btn btn-primary"><?php echo $btntitle; ?></a>
			</div>
		</div>
	</div>
</div>