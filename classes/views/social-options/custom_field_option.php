<tr class="form-field social_custom_field" id="social-custom-field-option-<?php echo "{$field_index}-{$option_index}"; ?>">
  <td><strong><?php echo ($option_index + 1); ?>.</strong>&nbsp;</td>
  <td>
  <input type="hidden" name="<?php echo $social_options->custom_fields_str . "[{$field_index}][options][{$option_index}][id]"; ?>" value="<?php echo $option['id']; ?>" />
  <strong><?php _e('Label', 'social'); ?>:</strong><br/><input type="text" id="<?php echo $social_options->custom_fields_str . "[{$field_index}][options][{$option_index}][label]"; ?>" name="<?php echo $social_options->custom_fields_str . "[{$field_index}][options][{$option_index}][label]"; ?>" value="<?php echo stripslashes($option['label']); ?>" /></td>
  <td valign="top"><strong><?php _e('Value', 'social'); ?>:</strong><br/><input type="text" id="<?php echo $social_options->custom_fields_str . "[{$field_index}][options][{$option_index}][value]"; ?>" name="<?php echo $social_options->custom_fields_str . "[{$field_index}][options][{$option_index}][value]"; ?>" value="<?php echo stripslashes($option['value']); ?>" /></td>
  <td>
    <a href="javascript:social_remove_tag('#social-custom-field-option-<?php echo "{$field_index}-{$option_index}"; ?>');"><img src="<?php echo social_IMAGES_URL . '/remove.png'; ?>" /></a>
  </td>
</tr>
<?php

if($show_add_button)
  $this->display_add_custom_field_option_button($field_index,$option_index+1);
  
?>