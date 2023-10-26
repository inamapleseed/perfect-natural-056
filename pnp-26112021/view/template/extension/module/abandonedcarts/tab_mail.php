<div class="tabbable tabs-left" id="abcart_tabs">
    <ul class="nav nav-tabs mail-list">
        <li class="static"><a class="addNewMailTemplate"><i class="fa fa-plus"></i> <?php echo $btn_add_template; ?></a></li>
        <?php if (isset($moduleData['MailTemplate'])) { ?>
            <?php foreach ($moduleData['MailTemplate'] as $mailtemplate) { ?>
            <li>
                <a href="#mailtemplate_<?php echo $mailtemplate['id']; ?>" data-toggle="tab" data-mailtemplate-id="<?php echo $mailtemplate['id']; ?>"><i class="fa fa-pencil-square-o"></i> <?php echo (isset($mailtemplate['Name']) && !empty($mailtemplate['Name'])) ? $mailtemplate['Name'] : $text_tempalte.' '.$mailtemplate['id']; ?><i class="fa fa-minus-circle removeMailTemplate"></i> <input type="hidden" name="<?php echo $moduleName; ?>[MailTemplate][<?php echo $mailtemplate['id']; ?>][id]" value="<?php echo $mailtemplate['id']; ?>" />
                </a>
            </li>
            <?php } ?>
        <?php } ?>
    </ul>
    
    <div class="tab-content mail-settings">
    <?php if (isset($moduleData['MailTemplate'])) { ?>
        <?php foreach ($moduleData['MailTemplate'] as $mailtemplate) { 
            require(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_mailtab.tpl');
        } ?>
    <?php } ?>
    </div>
</div>
<script type="text/javascript" >
// Show the editor
<?php 
if (isset($moduleData['MailTemplate'])) { 
	foreach ($moduleData['MailTemplate'] as $mailtemplate) {
		foreach ($languages as $language) { ?>
			// $('#message_<?php echo $mailtemplate['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
			// 	height: 350
			// });
            CKEDITOR.replace('abandonedcarts[MailTemplate][<?= $mailtemplate['id'] ?>][Message][<?php echo $language['language_id']; ?>]', {
              filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
              filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
              filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
              skin : "moono-lisa",
              codemirror: {
                theme: "base16-dark",
              },
              height: 350
            });
<?php	}
	}
} 
?>
</script>