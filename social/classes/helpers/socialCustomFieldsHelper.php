<?php
class socialCustomFieldsHelper
{
  function custom_field($field,$field_value, $classes='')
  {
    switch( $field['type'] )
    {
      case 'input':
        socialCustomFieldsHelper::text_input($field,$field_value,$classes);
        break;
      case 'textarea':
        socialCustomFieldsHelper::text_area($field,$field_value,$classes);
        break;
      case 'dropdown':
        socialCustomFieldsHelper::dropdown($field,$field_value,$classes);
        break;  
      case 'date':
        socialCustomFieldsHelper::date_input($field,$field_value,$classes);
        break; 
      case 'checkbox':
        socialCustomFieldsHelper::checkbox($field,$field_value,$classes);
        break;
    }
  }
  
  function text_input($field, $value, $classes='')
  {
    $curr_val = socialCustomFieldsHelper::get_current_value($field, $value);
    ?>
      <input type="text" name="social_custom[<?php echo $field['id']; ?>]" id="social_custom[<?php echo $field['id']; ?>]" class="social-text-input <?php echo $classes; ?>" value="<?php echo stripslashes($curr_val); ?>" />
    <?php
  }
  
  function text_area($field, $value, $classes='')
  {  
    $curr_val = socialCustomFieldsHelper::get_current_value($field, $value);
    ?>
      <textarea name="social_custom[<?php echo $field['id']; ?>]" id="social_custom[<?php echo $field['id']; ?>]" class="social-textarea social-growable <?php echo $classes; ?>"><?php echo stripslashes($curr_val); ?></textarea>
    <?php
  }
  
  function dropdown($field, $value, $classes='')
  {
    global $social_custom_field;

    $options = $social_custom_field->get_options( $field['id'], ARRAY_A );

    $curr_val = socialCustomFieldsHelper::get_current_value($field, $value);
    
    ?>
      <select name="social_custom[<?php echo $field['id']; ?>]" id="social_custom[<?php echo $field['id']; ?>]" class="social-dropdown social-custom-dropdown">
    <?php
        if(!isset($field['default_value']) or empty($field['default_value']))
        {
          ?>
          <option>&nbsp;</option>
          <?php
        }

        if(isset($options) and !empty($options) and is_array($options))
        {
          foreach($options as $option)
          {
            if($curr_val and ($curr_val == $option['value']))
              $selected = ' selected="selected"';
            else
              $selected = '';
          
            ?>
              <option value="<?php echo $option['value']; ?>"<?php echo $selected; ?>><?php echo stripslashes($option['label']); ?>&nbsp;</option>
            <?php
          }
        }
    ?>
      </select>
    <?php    
  }
  
  function date_input($field, $value, $classes='')
  {
    $curr_val = socialCustomFieldsHelper::get_current_value($field, $value);
    ?>
      <input type="text" name="social_custom[<?php echo $field['id']; ?>]" id="social_custom[<?php echo $field['id']; ?>]" class="social-text-input social-datepicker <?php echo $classes; ?>" value="<?php echo $curr_val; ?>" />
    <?php 
  }
  
  function checkbox($field, $value, $classes='')
  {
    $curr_val = socialCustomFieldsHelper::get_current_value($field, $value);
    
    if(isset($curr_val) and !empty($curr_val) and $curr_val)
      $checked = ' checked="checked"';
    else
      $checked = '';

    ?>
      <input type="checkbox" name="social_custom[<?php echo $field['id']; ?>]" id="social_custom[<?php echo $field['id']; ?>]" class="social-checkbox <?php echo $classes; ?>"<?php echo $checked; ?> />
    <?php
  }
  
  function get_current_value($field, $value)
  {
    if(isset($_POST['social_custom'][$field['id']]) and !empty($_POST['social_custom'][$field['id']]))
      return $_POST['social_custom'][$field['id']];
    else if(isset($value) and !empty($value))
      return $value;
    else if(isset($field['default_value']) and !empty($field['default_value']))
      return $field['default_value'];
    else
      return false;
  }
}
?>