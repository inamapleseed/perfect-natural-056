<style>	
    .deleteWaitingRecord{	
        color: red;	
        cursor: pointer;	
    }	
    .deleteWaitingRecord:hover{	
        color: #000;	
        text-decoration: underline;	
        cursor: pointer;	
    }	
</style>
<div class="form-group"> 
    <label class="control-label col-sm-2"><?= $entry_status; ?></label>
    <div class="col-sm-10">
        <select class="form-control" name="waiting_list_status">
            <option value="0"><?= $text_disabled; ?></option>
            <option value="1" <?= $waiting_list_status?'selected':''; ?> ><?= $text_enabled; ?></option>
        </select>
    </div>
</div>

<div class="form-group required"> 
    <label class="control-label col-sm-2">
        <span data-toggle="tooltip" title="<?= $entry_response_success_help; ?>" ><?= $entry_response_success; ?></span>
    </label>
    <div class="col-sm-10">
        <input class="form-control" name="waiting_list_success" value="<?= $waiting_list_success; ?>" />
        <?php if($error_waiting_list_success){ ?>
            <div class="text-danger" ><?= $error_waiting_list_success; ?></div>    
        <?php } ?>
    </div>
</div>

<div class="form-group required"> 
    <label class="control-label col-sm-2">
        <span data-toggle="tooltip" title="<?= $entry_response_error_help; ?>" ><?= $entry_response_error; ?></span>
    </label>
    <div class="col-sm-10">
        <input class="form-control" name="waiting_list_error" value="<?= $waiting_list_error; ?>" />
        <?php if($error_waiting_list_error){ ?>
            <div class="text-danger" ><?= $error_waiting_list_error; ?></div>    
        <?php } ?>
    </div>
</div>

<div class="form-group"> 
    <label class="control-label col-sm-2"><?= $entry_description; ?></label>
    <div class="col-sm-10">
        <textarea name="waiting_list_description" id="waiting_list_description"><?= $waiting_list_description; ?></textarea>
    </div>
</div>

<h3><?= $text_waiters; ?></h3>
<hr/>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <td><?= $column_product_name; ?></td>
            <td class="text-center" ><?= $column_no_request; ?></td>
            <td class="text-left"><?= $column_email_list; ?></td>	
            <td class="text-center"><?= $column_action; ?></td>
        </tr>
    </thead>
    <tbody>
        <?php if($waiting_products) {?>
            <?php foreach($waiting_products as $product){ ?>
                <tr>	
                    <td>	
                        <a href="<?= $product['product_link']; ?>" target="_blank"><?= $product['name']; ?></a><br>	
                        <?= $product['option_list']; ?>	
                    </td>	
                    <td class="text-center"><?= $product['request']; ?></td>	
                    <td class="text-left"><?= $product['email_list']; ?></td>	
                    <td class="text-center"><a class="btn btn-danger" title="Delete Request" data-toggle="tooltip" data-ids="" onclick="deleteRecord('<?= $product['waiting_id_list']; ?>')"><i class="fa fa-trash-o"></i></a></td>	
                </tr>    
            <?php } ?>
        <?php } else {?>	
             <tr>	
                 <td colspan="4" class="text-center"><?= $text_no_results ?></td>	
             </tr>	
        <?php }?>
    </tbody>
</table>

<div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

<script>
CKEDITOR.replace("waiting_list_description", { baseHref: "<?= $base_url; ?>", language: "en", filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', height: 350 });

function deleteRecord(waiting_ids){	
    if(confirm('Confirm to delete ?')){	
        $.ajax({	
                url: 'index.php?route=extension/module/waiting_list/deleteWaitingRecord&token=<?php echo $token; ?>',	
                type: 'post',			
                data: {'waiting_ids':waiting_ids},			
                beforeSend: function() {	
                },	
                complete: function() {	
                },		
                success: function(json) {	
                    if(json['success'] == 1){	
                        location.reload();	
                    } else {	
                        alert('Something went wrong!');	
                    }	
                },				
                error: function(xhr, ajaxOptions, thrownError) {	
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);	
                }	
        });	
    }	
}
</script>