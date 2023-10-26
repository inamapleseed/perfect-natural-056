<?php /* ?>
<h3><?php echo $headtitle; ?></h3>
<?php if ($showimg == 1) { ?>
<div class="row">
<?php foreach ($gallalbums as $gallalbum) { ?>
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
<div class="box-album transition gall<?php echo $boxstyle; ?>">
<?php if ($thumblist == 'style3') { ?>  
<div class="caption alb<?php echo $module; ?>">
<h4 class="<?php echo $titlepos; ?>"><a href="<?php echo $gallalbum['href']; ?>"><?php echo $gallalbum['name']; ?></a></h4>
</div>        
<?php } ?>      
<div class="image">
	<?php foreach($gallalbum['gallalbum'] as $k => $g) { ?>
	<a href="<?php echo $g['image']; ?>" data-lightbox="lightbox<?php echo $g['gallimage_id']; ?>"<?= $k > 0 ? ' class="hide"' : '' ?>><img src="<?php echo $g['image']; ?>" alt="<?php echo $g['title'] ? $g['title'] : 'image'.$k ?>" class="img-responsive" /></a>
	<?php } ?>
</div>
<?php if ($thumblist != 'style3') { ?>  
<div class="caption alb<?php echo $module; ?>">
<h3 class="<?php echo $titlepos; ?>"><?php echo $gallalbum['name']; ?></h3>
<?php if ($descstat == 1) { ?>
<p><?php echo $gallalbum['description']; ?></p>
<?php } ?>
</div>
<?php } else { ?>
<?php if ($descstat == 1) { ?>
<div class="caption">    
<p><?php echo $gallalbum['description']; ?></p>
</div>    
<?php } ?> 
<?php } ?>    
</div>
</div>
<?php } ?>
</div>
<?php } else { ?>
<div class="list-group">
<?php foreach ($gallalbums as $gallalbum) { ?>
<a href="<?php echo $gallalbum['href']; ?>" class="list-group-item"><?php echo $gallalbum['name']; ?></a>
<?php } ?>
</div>
<?php } */ ?>
<div class="gallery-listing">
	<div class="row">
	<?php foreach ($gallalbums as $k => $gallalbum) { ?>
	  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 text-center g-thumb-item pd-b15" data-toggle="modal" data-target="#myModal<?php echo $k ?>">
		<a href="#" data-slide-to="0" class="g-thumb-href"><img class="img-responsive" src="<?php echo $gallalbum['gallalbum'][0]['thumb'] ?>"><div class="text-center caption pd-t15 pd-b15"><div class="f16 bold"><?php echo $gallalbum['name']; ?></div></div></a>
	  </div>
	<?php } ?>
	<!--end of thumbnails-->
	</div>
</div>

<?php foreach ($gallalbums as $k => $gallalbum) { ?>
<!--begin modal window-->
<div class="modal fade galleryModal" id="myModal<?php echo $k ?>">

		<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
		<div class="modal-header no-border-b">
		<button type="button" class="close" data-dismiss="modal" title="Close"> <span class="glyphicon glyphicon-remove"></span></button>
		</div>
		<div class="modal-body product-product">

		<div class="product-image-main-container">
			<div id="product-image-main-<?php echo $k ?>" class="product-image-main">
			<?php foreach($gallalbum['gallalbum'] as $k2 => $g) { ?>
					<img src="<?= $g['image']; ?>" alt="<?= $g['title'] ? $g['title'] : $gallalbum['name'] ?>" title="<?= $g['title'] ? $g['title'] : $gallalbum['name'] ?>"
						class="main_images pointer"
					/>
			<?php } ?>
			</div>
		</div>

		<div class="product-image-additional-container">
			<div id="product-image-additional-<?php echo $k ?>" class="product-image-additional">
			<?php foreach($gallalbum['gallalbum'] as $k2 => $g) { ?>
			<img src="<?= $g['image2']; ?>" alt="<?= $g['title'] ? $g['title'] : $gallalbum['name'] ?>" title="<?= $g['title'] ? $g['title'] : $gallalbum['name'] ?>" class="pointer" />
			<?php } ?>
			</div>
		</div>

		<?php /* ?>
		<!--begin carousel-->
		<div id="myGallery<?php echo $k ?>" class="carousel slide" data-interval="false">
		<div class="carousel-inner">
		<!--<div class="item active"> 
		<iframe width="560" height="315" src="https://www.youtube.com/embed/d3JVkes8o4k" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		</div> -->
		
		<?php foreach($gallalbum['gallalbum'] as $k2 => $g) { ?>
			<div class="item<?php echo $k2 == 0 ? ' active' : '' ?>">
				<?php if($g['link']) {
						echo $g['link'];
					}else{ ?>
				<img class="img-responsive" src="<?php echo $g['image'] ?>">
				<?php } ?>
			</div>
		<?php } ?>
		<!--end carousel-inner--></div>
		<!--Begin Previous and Next buttons-->
		<a class="left carousel-control" href="#myGallery<?php echo $k ?>" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#myGallery<?php echo $k ?>" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span></a>
		<!--end carousel--></div>
		<?php */ ?>

		<!--end modal-body--></div>
		<?php /* ?>
		<div class="modal-footer">
		<!--end modal-footer--></div><?php */ ?>
		<!--end modal-content--></div>
		<!--end modal-dialoge--></div>
	

	<script type="text/javascript"><!--
	$('#myModal<?php echo $k ?>').on('shown.bs.modal', function (e) {
		$('#product-image-main-<?php echo $k ?>').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			fade: true,
			infinite: false,
			asNavFor: '#product-image-additional-<?php echo $k ?>',
			prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='icon left-arrow-icon'></i></div></div>",
			nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='icon right-arrow-icon'></i></div></div>",
		});

		$('#product-image-additional-<?php echo $k ?>').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '#product-image-main-<?php echo $k ?>',
			dots: false,
			centerMode: false,
			focusOnSelect: true,
			infinite: false,
			prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='icon left-arrow-icon'></i></div></div>",
			nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='icon right-arrow-icon'></i></div></div>",
		});
		
		$('#myModal<?php echo $k ?> .modal-dialog').css('margin-top', $('.fixed-header').height() + 40);
	});
	--></script>
})
</div>
<!--end myModal-->
<?php } ?>

<script type="text/javascript"><!--
function setEqualHeight(columns) { 
		var tallestcolumn = 0;
 			columns.each( function() {
 				currentHeight = $(this).height();
 				if(currentHeight > tallestcolumn)  {
 					tallestcolumn  = currentHeight; } 
 				});
 				columns.height(tallestcolumn);  }
	$(document).ready(function() {
 		setEqualHeight($(".alb<?php echo $module; ?>"));
	
		$('.g-thumb-href').on('click', function(e){
			e.preventDefault();
		});
	});	
--></script>
