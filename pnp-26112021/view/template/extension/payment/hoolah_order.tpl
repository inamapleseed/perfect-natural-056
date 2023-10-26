<fieldset>
  <legend><?php echo $text_transaction; ?></legend>
  <div id="hoolah-transaction"></div>
</fieldset>

<fieldset>
  <legend><?php echo $text_extension; ?></legend>
  <table class="table table-bordered">
    <tr>
      <td><?php echo $text_capture_status; ?></td>
      <td id="capture-status"><?php echo $status; ?></td>
    </tr>
    <tr>
        <td><?php echo $text_total_amount_captured; ?></td>
      <td id="total-capture-status"><?php echo $total_amount; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_captured; ?></td>
      <td id="hoolah-captured"><?php echo $captured; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_refunded; ?></td>
      <td id="hoolah-refund"><?php echo $refunded; ?></td>
    </tr>
  </table>
</fieldset>

<script type="text/javascript">
	$('#hoolah-transaction').load('index.php?route=extension/payment/hoolah/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
</script>