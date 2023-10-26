<div class="about-container">
	<?php if ($repeater): ?>
		<?php foreach ($repeater as $i => $rep): ?>
			<div class="inner <?php echo $i % 2 ? 'even' : 'odd'; ?> yes-it-is-reversed">
			    <?php if($rep['image']): ?>
    				<div class="images" data-aos="flip-left">
    					<div class="inner-image">
    						<img src="image/<?=$rep['image'];?>" alt="image">
    					</div>
    				</div>
    			<?php endif ?>
				<div class="text" data-aos="fade-right">
					<div>
						<h3><?php echo $rep['title']; ?></h3>
						<?= html_entity_decode($rep['text']);?>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>