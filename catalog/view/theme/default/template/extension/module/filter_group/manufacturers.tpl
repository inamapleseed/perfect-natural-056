<?php if($manufacturers){ ?>

<div id="side-manufacturer">
	<div class="list-group-item item-header">Brand</div>
	<div class="list-group-item con">
		<?php foreach($manufacturers as $i => $manufacturer){ ?>
			<label>
				<?php if($manufacturer['checked']){ ?>
				<input type="checkbox" name="manufacturer_ids[]" value="<?= $manufacturer['mid']; ?>" checked />
				<?php }else{ ?>
				<input type="checkbox" name="manufacturer_ids[]" value="<?= $manufacturer['mid']; ?>" />
				<?php } ?>
				<?= $manufacturer['name']; ?>
			</label>

			<?php if($i > 9):?>
				<style>
					#side-manufacturer .con {
						max-height: 250px;
						overflow: auto;
					}
				</style>
			<?php endif?>
		<?php } ?>
	</div>
</div>

<?php } ?>