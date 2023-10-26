<?php if($module && $on_off_popup){ ?>
	<style>
		#popup-modal .modal-dialog {
			width: 80vw;
			max-width: 900px;
		}
		#popup-modal .modal-body {
			overflow-y: auto;
			max-height: 90vh;
			width: 100%;
		}
		#popup-modal .mailchimp-integration .mi-button {
		    margin-top: 5px;
		}
		#popup-modal .mailchimp-integration .box-heading {
		    text-align: center;
		}
	</style>
	<!-- Pop up modal -->
	<div id="popup-modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="absolute position-center-center">
			<div class="modal-dialog modal-sm modal-lg" role="document">
				<div class="modal-content" <?php if($background_img) { ?>style="background-image:url('<?= $background_img ?>'); background-size:cover; background-position:center center; background-repeat:no-repeat;"<?php } ?>>
					<div class="modal-header" style="border-bottom:none;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title hide"></h4>
					</div>
					<div class="modal-body"><?= $module ?></div>
				</div>
			</div>
		</div>
	</div>	

	<?php if(($show_page && ($current_route == 'common/home' || $current_route == '')) || !$show_page) { ?>
	<script type="text/javascript">
		$(window).load(function(){
			setTimeout(function(){
				<?php if($show_mode == 2) { ?>
				if(localStorage.getItem('<?= $popup_state_name ?>') != 'shown'){ 
				<?php } ?>
					$('#popup-modal').modal('show');
					<?php if($show_mode == 2) { ?>
					localStorage.setItem('<?= $popup_state_name ?>','shown');
				}
				<?php } ?>
			}, <?= $delay_time; ?>);
		});
	</script>
	<?php } ?>

<?php } ?>