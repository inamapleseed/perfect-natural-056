<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?=$direction; ?>" lang="<?=$lang; ?>">
<!--<![endif]-->

<?= $head_tags ?>

<body class="<?=$class; ?> <?= $seo_enabled?'short_hand':''; ?> <?= $isMobile; ?>">

	<?php echo $spinwin; ?>
	
	<?php /* // not in use ?>
	<div id="loading_wrapper">
		<div class="spinner">
		  <div class="dot1"></div>
		  <div class="dot2"></div>
		</div>
	</div>
	<?php */ ?>
	<?php /*** loading page ***/ ?>
	<style>.loader{position:fixed;left:0;top:0;width:100%;height:100%;z-index:9999}</style>
	<div class="loader" style="background: url('<?=$logo; ?>') 50% 50% / auto 70px no-repeat rgb(255, 255, 255);"></div>
	<script tyle="text/javascript">$(window).load(function(){$(".loader").fadeOut("slow")});</script>

	<?php /*** loading page ***/ ?>

	<?= $fb_messanger; ?>
	<div class="x213"><h1 id="page_heading_title" ><?= $title; ?></h1></div>
	<header class="fixed-header" >

		<?php if($announcement_status){ ?>
		<div class="header_banner" style="background-color:<?= $header_top_background; ?>">
			<div class="container"><style>
				.header_title.mobile-sliding-text * {
					color: <?= $header_top_text_color; ?> !important;
					font-size: 13px;
				}
				.header_title.mobile-sliding-text p {
					margin-bottom: 0;
				}
			</style>
				<div class="header_title <?php if($header_top_running == 1){echo "sliding-text";}else if($header_top_running == 2){echo "mobile-sliding-text";} ?>" style="color:<?= $header_top_text_color; ?> !important;text-align:<?= $header_top_position; ?>;padding:<?= $header_top_padding; ?>">
					<?php if($header_top_icon){?><img src="image/<?= $header_top_icon; ?>" alt="<?= $header_top_title; ?>"><?php } ?>
					<?= html_entity_decode($header_top_title); ?>
				</div>
			</div>
		</div>
		<script>
		    $('.header_title > p').addClass('header_title-p');
		</script>
		<?php } ?>

		<div class="container">
			<div class="header-container">
				<div class="header-mobile-links visible-xs visible-sm">
					<div class="header-links">
						<a id="mobileNav" href="#sidr" class="pointer esc">
							<i class="fa fa-bars"></i>
						</a>
						<span class="hidden-xs hidden-sm">
							<?= $search; ?>
						</span>
					</div>
				</div>

				<div class="header-top">
					<div class="search_container">
						<span class="hidden-xs hidden-sm"><?= $pop_up_search; ?></span>
					</div>

					<span class="hidden-xs hidden-sm">
						<?= $login_part; ?>
					</span>
						<?=$wishlist; ?>
					<?= $cart; ?>
					<?php //echo $enquiry; ?>

					<span class="hidden" >
						<?=$currency; ?>
						<?=$language; ?>
					</span>
				</div>

				<div class="header-logo">
					<?php if ($logo) { ?>
						<a class="header-logo-image" href="<?=$home; ?>">
							<img src="<?=$logo; ?>" title="<?=$name; ?>" alt="<?=$name; ?>" class="img-responsive" />
						</a>
					<?php } else { ?>
						<a class="header-logo-text" href="<?=$home; ?>"><?=$name; ?></a>
					<?php } ?>
				</div>

				<div class="header-menu hidden-xs hidden-sm">
					<?= $menu; ?>
				</div>
			</div>
			
		</div>
	</header>

	<div id="sidr">
		<div class="header-mobile">
			<div class="mobile-account relative">
				<?php if($logged){ ?> 
				<a href="<?= $account; ?>">
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					<?= $text_account; ?></a>
				<a href="<?= $logout; ?>">
					<i class="fa fa-sign-out" aria-hidden="true"></i>
					<?= $text_logout; ?></a>
				<?php }else{ ?> 
				<a href="<?= $login; ?>">
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					<?= $text_login; ?></a>
				<a href="<?= $register; ?>">
					<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					<?= $text_register; ?></a>
				<?php } ?>
			</div>
			<div class="mobile-search">
				<?= $search; ?>
			</div>
		</div>
		<?= $mobile_menu; ?>
	</div>

	<div id="pg-banner-wrap">
	<?= $page_banner; ?>
	</div>

	<?php if($social_icons){ ?>
		<div class="floating-social-icons">
			<?php foreach($social_icons as $icon){ ?>
				<?php if ($icon['title'] == 'WhatsApp'): ?>
					<a class="web-whatsapp" style="display: none;" href="http://web.whatsapp.com/send?phone=<?= $icon['link']; ?>" title="<?= $icon['title']; ?>" alt="<?= $icon['title']; ?>" target="_blank">
						<img src="<?= $icon['icon']; ?>" title="<?= $icon['title']; ?>" class="img-responsive" alt="<?= $icon['title']; ?>" />
					</a>					
					<a class="phone-whatsapp" style="display: none;" href="http://api.whatsapp.com/send?phone=<?= $icon['link']; ?>" title="<?= $icon['title']; ?>" alt="<?= $icon['title']; ?>" target="_blank">
						<img src="<?= $icon['icon']; ?>" title="<?= $icon['title']; ?>" class="img-responsive" alt="<?= $icon['title']; ?>" />
					</a>					
				<?php endif ?>
			<?php } ?>
		</div>
	<?php } ?>
	<script type="text/javascript">
		if ($(window).width() < 768) {
			$('.phone-whatsapp').show();
		}
		if ($(window).width() > 768) {	
			$('.web-whatsapp').show();
		}
		
	</script>

	<style type="text/css">
		.floating-social-icons {
			position: fixed;
		    z-index: 4;
		    right: 20px;
		    width: 51px;
		    bottom: 80px;
		}
	</style>
