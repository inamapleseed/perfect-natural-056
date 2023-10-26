<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-gall-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-gall-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-gall-12'; ?>
    <?php } ?> 
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($thumb || $description) { ?>   
      <div class="row albdesc">
        <div class="col-gall-12">  
        <?php if ($thumb) { ?>
        <?php if ($position == 'center') { ?>  
        <div class="gallcenter"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>  
        <?php } elseif ($position == 'left')  { ?>  
        <div class="gallleft"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } else { ?>  
        <div class="gallright"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>  
        <?php } ?>  
        <?php } ?>
        <?php if ($description) { ?>
        <?php echo $description; ?>
        <?php } ?>
        </div>    
      </div> 
      <hr />    
      <?php } ?> 
      <div class="row">
  <?php foreach ($gallimages as $gallimage) { ?>
  <div class="col-gall-<?php echo ($imgperrow == '5' ? '15' : $imgperrow ); ?>">         
  <div class="box-gallery gallheight<?php echo $gallimage_id; ?> bum<?php echo $boxstyle; ?>">
  <?php if ($thumbstyle == 'style3') { ?>         
  <?php if ($gallimage['title']) { ?>      
  <div class="caption">    
  <?php if ($gallimage['link']) { ?>
  <h4><a href="<?php echo $gallimage['link']; ?>"><?php echo $gallimage['title']; ?></a></h4>
  <?php } else { ?>
  <h4><?php echo $gallimage['title']; ?></h4>
  <?php } ?>     
  </div>
  <?php } ?> 
  <?php } ?>      
      <div class="image img-gallery"><a href="<?php echo $gallimage['popup']; ?>" title="<?php echo $gallimage['title']; ?>" <?php if ($popstyle == 'blueimp') { ?> data-gallery="#blueimp-gallery-links" <?php }  ?><?php if ($popstyle == 'lightgall') { ?> data-lightbox="lightbox<?php echo $gallimage_id; ?>" <?php } ?>><img src="<?php echo $gallimage['image']; ?>" alt="<?php echo $gallimage['title']; ?>" title="<?php echo $gallimage['title']; ?>" class="img-responsive" /><i class="fa fa-search-plus" data-toggle="tooltip" title="zoom"></i></a></div>
  <?php if ($thumbstyle != 'style3') { ?>       
  <?php if ($gallimage['title']) { ?>      
  <div class="caption">    
  <?php if ($gallimage['link']) { ?>
  <h4><a href="<?php echo $gallimage['link']; ?>"><?php echo $gallimage['title']; ?></a></h4>
  <?php } else { ?>
  <h4><?php echo $gallimage['title']; ?></h4>
  <?php } ?>     
  </div>
  <?php } ?>
  <?php } ?>      
  </div>
  </div>
  <?php } ?> 
<?php if ($popstyle == 'blueimp') { ?>          
  <!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 
<?php } ?>       
  </div>  
    <?php if ($pagination) { ?>    
      <div class="row gallpage">
        <div class="col-gall-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-gall-6 text-right gpresult"><?php echo $results; ?></div>
      </div>
    <?php } ?>    
  <?php echo $content_bottom; ?>    
  </div>
  <?php echo $column_right; ?></div>
</div>
<?php if ($popstyle == 'blueimp') { ?>     
<script type="text/javascript"><!--
$('#blueimp-gallery').data('useBootstrapModal', 0);
$('#blueimp-gallery').toggleClass('blueimp-gallery-controls', 0);
--></script>  
<?php } else if ($popstyle == 'lightgall') { ?>

<?php } else { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.img-gallery').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		},
        image: {
            titleSrc: 'title'
        }
	});
});
//--></script>
<?php } ?>  
<script type="text/javascript"><!--
$(function() {
	$('.gallheight<?php echo $gallimage_id; ?>').matchHeight();
});
//--></script>	
<?php echo $footer; ?> 