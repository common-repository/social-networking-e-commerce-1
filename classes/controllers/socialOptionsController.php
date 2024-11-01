<?php

class socialOptionsController
{
  function socialOptionsController()
  {
    add_action('social_custom_fields', array($this,'display_custom_fields'));
  }

  function route()
  {
    $action = (isset($_POST['action'])?$_POST['action']:$_GET['action']);
    if($action=='process-form')
      return $this->process_form();
    else if($action=='add_default_friends_to_all_users')
      $this->add_default_friends_to_all_users();
    else
      return $this->display_form();
  }
  function route1()
  {
    $action = (isset($_POST['action'])?$_POST['action']:$_GET['action']);
    if($action=='process-form')
      return $this->process_form1();
   
    else
      return $this->display_form1();
  }
  function route2()
  {
    $action = (isset($_POST['action'])?$_POST['action']:$_GET['action']);
    if($action=='process-form')
      return $this->process_form2();
    
    else
      return $this->display_form2();
  }
  function display_form()
  {
    global $social_options, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {    
      if(!$social_options->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      require(social_VIEWS_PATH . '/social-options/form.php');
	  
    }
  }
   function display_form1()
  {
    global $social_category, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {    
      if(!$social_category->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      
	  require(social_VIEWS_PATH . '/social-options/form_cat.php');
    }
  }

	function display_form2()
  {
    global $social_products, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {    
      if(!$social_products->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      require(social_VIEWS_PATH . '/social-options/form_product.php');
	  
    }
  }
  function process_form()
  {
    global $social_options, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {
      $errors = array();
      
      $errors = $social_options->validate($_POST,$errors);
      
      $social_options->update($_POST);
      
      if( count($errors) > 0 )
        require(social_VIEWS_PATH . '/shared/errors.php');
      else
      {
        $social_options->store();
        require(social_VIEWS_PATH . '/social-options/options_saved.php');
      }
      
      if(!$social_options->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      require(social_VIEWS_PATH . '/social-options/form.php');
    }
  }
  
  
  function process_form1()
  {
    global $social_category, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {
      $errors = array();
      
      $errors = $social_category->validate($_POST,$errors);
      
      $social_category->update($_POST);
      
      if( count($errors) > 0 )
        require(social_VIEWS_PATH . '/shared/errors.php');
      else
      {
        $social_options->store();
        require(social_VIEWS_PATH . '/social-options/options_saved.php');
      }
      
      if(!$social_options->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      require(social_VIEWS_PATH . '/social-options/form_cat.php');
    }
  }
  
   function process_form2()
  {
    global $social_products, $social_app_helper;
    
    if(socialUser::is_logged_in_and_an_admin())
    {
      $errors = array();
      
      $errors = $social_products->validate($_POST,$errors);
      
      $social_category->update($_POST);
      
      if( count($errors) > 0 )
        require(social_VIEWS_PATH . '/shared/errors.php');
      else
      {
        $social_options->store();
        require(social_VIEWS_PATH . '/social-options/options_saved.php');
      }
      
      if(!$social_options->setup_complete)
        require(social_VIEWS_PATH . '/shared/must_configure.php');
      
      require(social_VIEWS_PATH . '/social-options/form_product.php');
    }
  }
  
  
  function display_default_friend_drop_down($default_friend='')
  {
    global $social_options;
    
    if(socialUser::is_logged_in_and_an_admin())
      require(social_VIEWS_PATH . '/social-options/default_friend.php');
  }
  
  function add_default_friends_to_all_users()
  {
    global $social_friends_controller;
    
    $social_friends_controller->add_default_friends_to_all_users();
    
    require(social_VIEWS_PATH . '/social-options/default_friends_added.php');
      
    $this->display_form();
  }
  function display_custom_fields()
  {
    global $social_options, $social_custom_field;

    $custom_fields = $social_custom_field->get_all(ARRAY_A);

    require(social_VIEWS_PATH . "/social-options/custom_fields.php");
  }
  
  function display_custom_field($index, $field=NULL, $show_add_button=true)
  {
    global $social_options;

    if(empty($field))
      $field = array();

    require( social_VIEWS_PATH . "/social-options/custom_field.php");
  }
  
  function display_add_custom_field_button($index=0)
  {
    require( social_VIEWS_PATH . "/social-options/add_custom_field_button.php");
  }
  
  function display_custom_field_options($field_index, $field_id=0)
  {
    global $social_options, $social_custom_field;

    if(isset($_POST[$social_options->custom_fields_str][$field_index]))
      $field = $_POST[$social_options->custom_fields_str][$field_index];
    else if($field_id and !empty($field_id))
      $field = $social_custom_field->get_field($field_id, ARRAY_A);
    else
      $field = false;

    if($field_id and !empty($field_id))
      $options = $social_custom_field->get_options($field_id, ARRAY_A);
    else
      $options = array();

    require(social_VIEWS_PATH . "/social-options/custom_field_options.php");
  }
  
  function display_custom_field_option($field_index, $option_index, $option=NULL, $show_add_button=true)
  {
    global $social_options;

    if(empty($option))
      $option = array();

    require( social_VIEWS_PATH . "/social-options/custom_field_option.php");
  }
  
  function display_add_custom_field_option_button($field_index, $option_index=0)
  {
    require( social_VIEWS_PATH . "/social-options/add_custom_field_option_button.php");
  }
}
?>
