<button id="articles-filter-trigger-open" class="btn btn-primary" onclick="$('#articles-column-left').addClass('open');" ><?= $button_filter; ?></button>
<div id="articles-column-left">
	<button id="articles-filter-trigger-close" class="btn btn-danger fixed position-right-top" onclick="$('#articles-column-left').removeClass('open');"> <i class="fa fa-times"></i> </button>
	<h3><?= $text_categories ?></h3>
	<div class="pd-b15">
		<div class="list-group news-ctgr">
		<?php foreach ($categories as $c) { ?>
			<div class="list-group-item">
				<a href="<?= $c['url'] ?>" class="block <?= $ncategory_id == $c['ncategory_id'] ? 'active' : '' ?>"><?= $c['name'] ?></a>
			</div>
		<?php } ?>
		</div>
	</div>
	<h3><?= $text_year ?></h3>
	<div class="list-group pd-b15">
		<?php $index = 0; ?>
				<?php foreach ($archives as $archive) { ?>
			<?php $index++ ?>
			<?php //if ($index > 1 && (count($archive['month']) > 3 || count($archive) > 4) && (count($archive) > 2 || count($archive['month']) > 5)) { ?>
				<span class="list-group-item hidesthemonths <?= $achive_yr == $archive['year'] ? 'active' : '' ?>">
					<div class="year-wrap">
						<a href="<?php echo $archive['yr_href']; ?>"><?php echo $archive['year']; ?></a>
						<div class="toggle level-1 pointer"><div class="caret"></div></div>
					</div>
					<div class="list-group">
						<?php foreach ($archive['month'] as $month) { ?>
							<a class="list-group-item <?= $archive_query == ($archive['year'].'-'.$month['num']) ? 'active' : '' ?>" href="<?php echo $month['href']; ?>"><?php echo $month['name']; ?></a>
						<?php } ?>
					</div>
				</span>
			<?php /*} else { ?>
				<?php foreach ($archive['month'] as $month) { ?>
					<a href="<?php echo $month['href']; ?>" class="list-group-item"><?php echo $month['name']; ?></a>
				<?php } ?>
			<?php }*/ ?>
				<?php } ?>
			<?php echo !$archives ? 'No articles' : ''; ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	/*
	$('.hidesthemonths').on('click', function () {
		$(this).find('div').slideToggle('fast');
	});
	*/

	$('#articles-column-left .toggle').on('click', function(e){
	
		e.preventDefault();
		ele = $(this).parents('.list-group-item');
		
		if(ele.hasClass('active')){
			ele.removeClass('active');
		}
		else{ 
			if(ele.hasClass('.level-1')){
				$('.level-1.active').removeClass('active');
			}
			else if(ele.hasClass('.level-2')){
				$('.level-2.active').removeClass('active');
			}
			
			ele.addClass('active');
		}
	});

});
</script>