<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_transaction; ?></td>
      <td class="text-left"><?php echo $column_amount; ?></td>
      <td class="text-left"><?php echo $column_type; ?></td>
      <td class="text-left"><?php echo $column_status; ?></td>
      <td class="text-left"><?php echo $column_date_added; ?></td>
      <td class="text-left"><?php echo $column_action; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($transactions) { ?>
      <?php foreach($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['uuid']; ?></td>
        <td class="text-left"><?php if($total_refund > 0) { echo $total_refund; } else { echo $total_amount; } ?></td>
        <td class="text-left"><?php echo $transaction['orderType']; ?></td>
        <td class="text-left"><?php echo $transaction['status']; ?></td>
        <td class="text-left"><?php echo $transaction['date_added']; ?></td>
        <td class="text-left">
          <?php if ($transaction['uuid']) { ?>
            <a href="<?php echo $transaction['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
            <?php if ($total_amount != $total_refund) { ?>
                <a href="<?php echo $transaction['refund']; ?>" data-toggle="tooltip" title="<?php echo $button_refund; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>&nbsp;
            <?php } ?>
          <?php }?>
        </td>
      </tr>
      <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
