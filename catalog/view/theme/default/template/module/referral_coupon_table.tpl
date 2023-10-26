<h4><?php echo $lng['heading_referral_table']; ?></h3>
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <td><?php echo $lng['column_date']?></td>
      <td><?php echo $lng['column_name']?></td>
      <td><?php echo $lng['column_email']?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($referrals as $r) { ?>
    <tr>
      <td><?php echo $r['date_added']; ?></td>
      <td><?php echo $r['name']; ?></td>
      <td><?php echo $r['email']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </td>
    </tr>
  </tfoot>
</table>