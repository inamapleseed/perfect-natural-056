<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered">
          <?php if (!empty($order_products)) { ?>
          <tr>
            <td><?php echo $text_product_lines; ?></td>
            <td>
                <table class="table table-bordered">
                  <?php
                    foreach($order_products as $k => $row) {
                      echo '<tr><td>'.$row['product_name'].'</td>';
                      echo '<td >'.$row['sku'].'</td>';
                      echo '<td >'.$row['quantity'].'</td>';
                      echo '<td >'.$transaction['currency'].'</td>';
                      echo '<td >'.number_format($row['total'], 2).'</td></tr>';
                    }
                  ?>
              </table></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['order_id'])) { ?>
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td><?php echo $transaction['order_id']; ?></td>
          </tr>
          <?php } ?>
          
          <?php if (isset($orders['payment_code'])) { ?>
          <tr>
            <td><?php echo $text_payment_method; ?></td>
            <td><?php echo ucwords($orders['payment_code']); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['email'])) { ?>
          <tr>
            <td><?php echo $text_buyer_email; ?></td>
            <td><?php echo $orders['email']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['payment_firstname'])) { ?>
          <tr>
            <td><?php echo $text_firstname; ?></td>
            <td><?php echo ucwords($orders['payment_firstname']); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['payment_lastname'])) { ?>
          <tr>
            <td><?php echo $text_lastname; ?></td>
            <td><?php echo ucwords($orders['payment_lastname']); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['shipping_address_1']) || isset($orders['shipping_address_2'])) { ?>
          <tr>
            <td><?php echo $text_shipping_address; ?></td>
            <td><?php echo $orders['shipping_address_1'] . ', ' . $orders['shipping_address_2']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['shipping_unit_no'])) { ?>
          <tr>
            <td><?php echo $text_shipping_unit_no; ?></td>
            <td><?php echo $orders['shipping_unit_no']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['shipping_postcode'])) { ?>
          <tr>
            <td><?php echo $text_shipping_postcode; ?></td>
            <td><?php echo $orders['shipping_postcode']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['shipping_country'])) { ?>
          <tr>
            <td><?php echo $text_shipping_country; ?></td>
            <td><?php echo $orders['shipping_country']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['uuid'])) { ?>
          <tr>
            <td><?php echo $text_uuid; ?></td>
            <td><?php echo $transaction['uuid']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['orderType'])) { ?>
          <tr>
            <td><?php echo $text_order_type; ?></td>
            <td><?php echo $transaction['orderType']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['createdAt'])) { ?>
          <tr>
            <td><?php echo $text_order_time; ?></td>
            <td><?php echo $transaction['createdAt']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($orders['total'])) { ?>
          <tr>
            <td><?php echo $text_amount; ?></td>
            <td><?php echo number_format($orders['total'], 2); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['tax'])) { ?>
          <tr>
            <td><?php echo $text_tax; ?></td>
            <td><?php echo number_format($transaction['tax'], 2); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['shipping_fee'])) { ?>
          <tr>
            <td><?php echo $text_shipping; ?></td>
            <td><?php echo number_format($transaction['shipping_fee'], 2); ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['currency'])) { ?>
          <tr>
            <td><?php echo $text_currency_code; ?></td>
            <td><?php echo $transaction['currency']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($success_status)) { ?>
          <tr>
            <td><?php echo $text_payment_status; ?></td>
            <td><?php echo $success_status; ?></td>
          </tr>
          <?php } ?>
          <?php if (!empty($total_refund)) { ?>
          <tr>
            <td><?php echo $text_refund_lines; ?></td>
            <td>
                <table class="table table-bordered">
                  <?php
                    echo '<tr><td>'.$refund_status.'</td>';
                    echo '<td >'.$total_refund.'</td></tr>';
                  ?>
              </table></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>