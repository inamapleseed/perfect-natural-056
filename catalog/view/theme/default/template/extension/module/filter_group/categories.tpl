<?php if($categories) { ?>
	<div id="side-categories">
		<div class="list-group-item item-header" >Filter By</div>
		<div class="list-group-item">
			<?php foreach($categories as $category){ ?>
				
				<div class="side-categories-level-1">
					<div class="group">
							<div class="item level-1 <?= $category['active']; ?>" data-path="<?= $category['path']; ?>" >
								<a href="<?= $category['href']; ?>" ><?= $category['name']; ?></a>
								<?php if($category['child']){ ?>
								<div class="toggle level-1 pointer"><div class="plus">+<span class="minus">-</span></div></div>
								<?php } ?>
							</div>
						
							<?php if($category['child']){ ?>
								<div class="sub level-2">
										<?php foreach($category['child'] as $child_1){ ?>
											<div class="group" >
												<div class="item level-2 <?= $child_1['active']; ?>" data-path="<?= $child_1['path']; ?>" >
													<a href="<?= $child_1['href']; ?>" class="" ><?= $child_1['name']; ?></a>
													<?php if($child_1['child']){ ?>
														<div class="toggle level-2 pointer"><div class="plus">+<span class="minus">-</span></div></div>
													<?php } ?>
												</div>
											
											<?php if($child_1['child']){ ?>
											<div class="sub level-3">
												<?php foreach($child_1['child'] as $child_2){ ?>
													<div class="item level-3 <?= $child_2['active']; ?>" data-path="<?= $child_2['path']; ?>" >
														<a href="<?= $child_2['href']; ?>" class="<?= $child_2['active']; ?>" ><?= $child_2['name']; ?></a>
													</div>
												<?php } ?>
											</div>
											<?php } ?>
											<!---->

											</div>
										<?php } ?>
								</div>
							<?php } ?>
							<!---->
						</div>
					</div>
					
			<?php } ?>
		</div>
	</div>
<?php } ?>