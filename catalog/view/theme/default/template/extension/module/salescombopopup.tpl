<div id="offerPopup" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal"><?php echo $offerclose; ?></button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
  var popupid = 0;
  function openOfferPopup(id) {
      popupid = id;
      $.ajax({
      url: 'index.php?route=offers/salescombopge/popp',
      type: 'post',
      dataType: 'json',
      data: {"page_id" : id} ,
      success: function(json) {
        if(json.html != undefined) {
          if(json.html.title != undefined) {
            $('#offerPopup .modal-title').html(json.html.title);
          }
          if(json.html.description != undefined) {
            $('#offerPopup .modal-body').html(json.html.description);
          }
          $('#offerPopup').modal('show'); 
        } 
      }
    });
  }
  function addOfferSession(popup_id) {
        $.ajax({
        url:"index.php?route=offers/salescombopge/addOfferSession",
        type:"POST",
        data : {'id':popup_id},
        success:function(){
          console.log("Popup id is now in session "+popup_id);
        }
        });
  }
  $('#offerPopup').on('shown.bs.modal', function () {
    addOfferSession(popupid);
  })
</script>
<?php if(!empty($autopopup)) { ?>
<?php foreach($autopopup as $offer) { ?>
<script type="text/javascript">
  $(document).ready( function() {
    openOfferPopup('<?php echo $offer; ?>');
  });
</script>
<?php } } ?>