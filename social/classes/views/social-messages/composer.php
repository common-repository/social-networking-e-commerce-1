<div id="social_message_composer" class="social_gray_box">
<form action="" enctype="multipart/form-data" method="post" autocomplete="off">
  <input type="hidden" name="action" id="action" value="social_process_composer_form" />
  <input type="hidden" name="<?php echo $social_user->id_str; ?>" id="<?php echo $social_user->id_str; ?>" value="<?php echo $social_user->id; ?>" />
  <input type="hidden" name="<?php echo $social_user->screenname_str; ?>" id="<?php echo $social_user->screenname_str; ?>" value="<?php echo $social_user->screenname; ?>" />
  <table width="100%" class="profile-edit-table1">
    <tr>
      <td valign="top" width="145px" style="float:left;"><?php _e('Recipients', 'social'); ?>:</td>
      <td valign="top">
				<input type="text" name="social_message_recipients" id="social_message_recipients" class="social-profile-edit-field" value="<?php echo $to; ?>" />
      </td>
    </tr>
    <tr>
      <td valign="top" style="float:left;"><?php _e('Subject', 'social'); ?>:</td>
      <td valign="top"><input type="input" name="social_message_subject" id="social_message_subject" value="<?php echo socialAppHelper::format_text($_POST['social_message_subject']); ?>" class="social-profile-edit-field" /></td>
    </tr>
    <tr>
      <td valign="top" style="margin: 0 0 24px; float:left;"><?php _e('Message', 'social'); ?>:</td>
      <td valign="top"><textarea name="social_message_body" id="social_message_body" class="social-profile-edit-field social-growable"><?php echo socialAppHelper::format_text($_POST['social_message_body']); ?></textarea></td>
    <tr>
	<td colspan="2">
  <input type="submit" class="social-share-button" name="Update" value="<?php _e('Send', 'social'); ?>" /></td>
  </tr>
  </table>
</form>
</div>

<script type="text/javascript">
jQuery(function() {
	function split(val) {
		return val.split(/,\s*/);
	}

	function extractLast(term) {
		return split(term).pop();
	}
	
	jQuery("#social_message_recipients").autocomplete({
		source: function(request, response) {
			jQuery.getJSON("<?php echo social_SCRIPT_URL . "&controller=messages&action=lookup_friends"; ?>", {
				q: extractLast(request.term)
			}, response);
		},
		search: function() {
			// custom minLength
			var term = extractLast(this.value);
			if (term.length < 2) {
				return false;
			}
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function(event, ui) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push("");
			this.value = terms.join(", ");
			return false;
		}
	});
	
	<?php echo (((isset($_POST['action']) and ($_POST['action'] == 'social_process_composer_form')) or (isset($_GET['u']) and !empty($_GET['u'])))?'':"jQuery('#social_message_composer').hide();"); ?>
});
</script>