<table class="form-table social-custom-fields-table" style="width: auto;" id="social-custom-field-<?php echo $index; ?>">
<tr class="form-field social_custom_field">
  <td><strong><?php echo ($index + 1); ?>.</strong>&nbsp;</td>
  <td>
  <input type="hidden" name="<?php echo $social_options->custom_fields_str . "[{$index}][id]"; ?>" value="<?php echo $field['id']; ?>" />
  <strong><?php _e('Name', 'social'); ?>:</strong><br/><input type="text" id="<?php echo $social_options->custom_fields_str . "[{$index}][name]"; ?>" name="<?php echo $social_options->custom_fields_str . "[{$index}][name]"; ?>" value="<?php echo stripslashes($field['name']); ?>" /></td>
  <td valign="top"><strong><?php _e('Type', 'social'); ?>:</strong><br/><?php echo socialOptionsHelper::field_type_dropdown($social_options->custom_fields_str . "[{$index}][type]", $field['type'], $index); ?></td>
  <td valign="top"><strong><?php _e('Default Value', 'social'); ?>:</strong><br/><input type="text" id="<?php echo $social_options->custom_fields_str . "[{$index}][default_value]"; ?>" name="<?php echo $social_options->custom_fields_str . "[{$index}][default_value]"; ?>" value="<?php echo stripslashes($field['default_value']); ?>" /></td>
  <td valign="top"><strong><?php _e('Privacy', 'social'); ?>:</strong><br/>
    <?php socialOptionsHelper::field_visibility_dropdown($social_options->custom_fields_str . "[{$index}][visibility]", $field['visibility']); ?>
  </td>
  <td>
    <input type="checkbox" style="width: 20px;" id="<?php echo $social_options->custom_fields_str . "[{$index}][on_signup]"; ?>" name="<?php echo $social_options->custom_fields_str . "[{$index}][on_signup]"; ?>"<?php echo ((isset($field['on_signup']) and !empty($field['on_signup']))?' checked="checked"':''); ?>/>&nbsp;<?php _e('Show on the Signup Page', 'social'); ?>
  </td>
  <td>
    <a href="javascript:social_remove_tag('#social-custom-field-<?php echo $index; ?>');"><img src="<?php echo social_IMAGES_URL . '/remove.png'; ?>" /></a>
  </td>
</tr>
<tr id="social_field_options_wrapper_<?php echo $index; ?>" class="<?php echo (($field and $field['type']=='dropdown')?'':' social-hidden'); ?>">
  <td colspan="7">
    <?php $this->display_custom_field_options($index, $field['id']); ?>
  </td>
</tr>
</table>
<?php

if($show_add_button)
  $this->display_add_custom_field_button($index+1);
  
?>