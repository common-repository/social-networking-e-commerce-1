<?php

class socialProfileHelper
{
  function sex_dropdown($field_name, $field_value, $classes='', $tabindex='')
  { 
    if(!empty($classes))
      $classes = " {$classes}";
    
    if(!empty($tabindex))
      $tabindex = " tabindex=\"{$tabindex}\"";

    ?>
      <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="social-dropdown social-sex-dropdown<?php echo $classes; ?>"<?php echo $tabindex; ?>>
        <option value="">- - - Select - -</option>
        <option value="female"<?php socialAppHelper::value_is_selected($field_name, $field_value, "female"); ?>><?php _e('Female', 'social'); ?>&nbsp;</option>
        <option value="male"<?php socialAppHelper::value_is_selected($field_name, $field_value, "male"); ?>><?php _e('Male', 'social'); ?>&nbsp;</option>
      </select>
    <?php
  }
  
  function privacy_dropdown($field_name, $field_value)
  {
    ?>
      <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="social-dropdown social-privacy-dropdown">
        <option value="private"<?php socialAppHelper::value_is_selected($field_name, $field_value, "private"); ?>><?php _e('I Want My Profile to Only Be Visible To Me and My Friends', 'social'); ?>&nbsp;</option>
        <option value="public"<?php socialAppHelper::value_is_selected($field_name, $field_value, "public"); ?>><?php _e('I Want My Profile to Be Visible To The World', 'social'); ?>&nbsp;</option>
      </select>
    <?php
  }
}
?>
