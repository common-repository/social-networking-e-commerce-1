<?php

if(isset($fields) and !empty($fields) and is_array($fields))
{
  foreach($fields as $field)
  {
    $field_value = $social_custom_field->get_value( $social_user->id, $field['id'] );

    $private = (($field['visibility'] == 'private')?"<span class=\"description\"> (".__("private", 'social').")</span>":'');

    ?>
      <tr>
        <td valign="top"><?php echo stripslashes($field['name']); echo $private; ?>:</td>
        <td valign="top"><?php socialCustomFieldsHelper::custom_field($field, $field_value->value, 'social-profile-edit-field'); ?></td>
      </tr>
    <?php
  }
}
?>
