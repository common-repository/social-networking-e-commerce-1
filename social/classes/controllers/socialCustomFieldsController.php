<?php
class socialCustomFieldsController
{
  function socialCustomFieldsController()
  {
    // These just hook back into the user porfile
    add_action('social-edit-user-fields', array( &$this, 'display_edit_fields' ));
    add_action('social-profile-update', array( &$this, 'process_custom_fields' ));

    add_action('social-user-signup-fields', array( &$this, 'display_signup_fields' ));
    add_action('social-process-signup', array( &$this, 'process_custom_fields' ));
    
    add_action('social-profile-info', array( &$this, 'display_info_fields'));
  }
  
  function display_edit_fields()
  {
    global $social_custom_field, $social_user;
    
    $fields = $social_custom_field->get_all(ARRAY_A, "visibility <> 'hidden'");
    
    require( social_VIEWS_PATH . "/social-custom-fields/edit_fields.php" );
  }
  
  function display_signup_fields()
  {
    global $social_custom_field;

    $fields = $social_custom_field->get_all(ARRAY_A, "on_signup > 0");

    require( social_VIEWS_PATH . "/social-custom-fields/signup_fields.php" );
  }
  
  function display_info_fields($user_id)
  {
    global $social_custom_field;

    $fields = $social_custom_field->get_all(ARRAY_A, "visibility='public'");
    
    require( social_VIEWS_PATH . "/social-custom-fields/info_fields.php" );
  }
  
  function process_custom_fields($user_id)
  {

    global $social_custom_field;
    
    if(isset($_POST['social_custom']) and !empty($_POST['social_custom']))
    {
      $custom_values = $_POST['social_custom'];
      
      foreach( $custom_values as $field_id => $field_value )
        $social_custom_field->create_or_update_value($user_id, $field_id, $field_value);
    }
  }
}
?>