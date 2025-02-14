<?php

class socialUsersController
{
  function display_login_form()
  {
    global $social_options, $social_blogurl;

    extract($_POST);
    
    $redirect_to = ( (isset($redirect_to) and !empty($redirect_to) )?$redirect_to:get_permalink( $social_options->profile_page_id ) );
    $redirect_to = apply_filters( 'social-login-redirect-url', $redirect_to );
      
    if(!empty($social_options->login_page_id) and $social_options->login_page_id > 0)
    {
      $login_url = get_permalink($social_options->login_page_id);
      $login_delim = socialAppController::get_param_delimiter_char($login_url);
      $forgot_password_url = "{$login_url}{$login_delim}action=forgot_password";
    }
    else
    {
      $login_url = "{$social_blogurl}/wp-login.php";
      $forgot_password_url = "{$social_blogurl}/wp-login.php?action=lostpassword";
    }
    
    if(!empty($social_options->signup_page_id) and $social_options->signup_page_id > 0)
      $signup_url = get_permalink($social_options->signup_page_id);
    else
      $signup_url = $social_blogurl . '/wp-login.php?action=register';
    
    if(socialUser::is_logged_in_and_visible())
      require( social_VIEWS_PATH . '/shared/already_logged_in.php' );
    else
    {
      if( !empty($social_process_login_form) and !empty($errors) )
        require( social_VIEWS_PATH . "/shared/errors.php" );

      require( social_VIEWS_PATH . '/shared/login_form.php' );
    }
  }
  
  function process_login_form()
  {
    global $social_options, $social_profiles_controller;

    $errors = socialUser::validate_login($_POST,array());
    
    $errors = apply_filters('social-validate-login', $errors);

    extract($_POST);
    
    if(empty($errors))
    {
      $creds = array();
      $creds['user_login'] = $log;
      $creds['user_password'] = $pwd;
      $creds['remember'] = $rememberme;

      if(!function_exists('wp_signon'))
        require_once(ABSPATH . WPINC . '/user.php');
      
      wp_signon($creds);

      $redirect_to = ((!empty($redirect_to))?$redirect_to:get_permalink($social_options->activity_page_id));

      socialUtils::wp_redirect($redirect_to);
      exit;
    }
    else
      $_POST['errors'] = $errors;
  }
  
  function display_signup_form()
  {
    global $social_options, $social_blogurl;
    
    $process = socialAppController::get_param('social-process-form');
    
    if(empty($process))
    {
      if(socialUser::is_logged_in_and_visible())
        require( social_VIEWS_PATH . '/shared/already_logged_in.php' );
      else
        require( social_VIEWS_PATH . '/shared/signup_form.php' );
    }
    else
      $this->process_signup_form();
  }
  
  function process_signup_form()
  {
    global $social_options;

    $errors = socialUser::validate_signup($_POST,array());
    
    $errors = apply_filters('social-validate-signup', $errors);
    
    extract($_POST);
    
    if(empty($errors))
    {
      if(isset($social_options->field_visibilities['signup_page']['password']))
        $new_password = $social_user_password;
      else
        $new_password = apply_filters('social-create-signup-password', socialUtils::wp_generate_password( 12, false ));

      $user_id = wp_create_user( $user_login, $new_password, $user_email );
      $user = socialUser::get_stored_profile_by_id($user_id);
      
      if($user)
      { 
        if(isset($social_options->field_visibilities['signup_page']['name']))
        {
          if(isset($user_first_name) and !empty($user_first_name))
            $user->first_name = $user_first_name;
          
          if(isset($user_last_name) and !empty($user_last_name))
            $user->last_name = $user_last_name;
        }
          
        if(isset($social_options->field_visibilities['signup_page']['sex']))
          $user->sex = $social_user_sex;
        
        if(isset($social_options->field_visibilities['signup_page']['url']))
          $user->url = $social_user_url;

        if(isset($social_options->field_visibilities['signup_page']['location']))
          $user->location = $social_user_location;

        if(isset($social_options->field_visibilities['signup_page']['bio']))
          $user->bio = $social_user_bio;
        
        $user->store(true);
        
        $user->send_account_notifications($new_password);
        
        do_action('social-process-signup',$user_id);
        
        global $social_blogname;
        require( social_VIEWS_PATH . "/social-users/signup_thankyou.php" );
      }
      else
        require( social_VIEWS_PATH . "/shared/unknown_error.php" );
    }
    else
    {
      require( social_VIEWS_PATH . "/shared/errors.php" );
      require( social_VIEWS_PATH . '/shared/signup_form.php' );
    }
  }
  
  function display_forgot_password_form()
  {
    global $social_options, $social_blogurl;
    
    $process = socialAppController::get_param('social_process_forgot_password_form');
    
    if(empty($process))
      require( social_VIEWS_PATH . '/social-users/forgot_password.php' );
    else
      $this->process_forgot_password_form();
  }
  
  function process_forgot_password_form()
  {
    global $social_options;

    $errors = socialUser::validate_forgot_password($_POST,array());
    
    extract($_POST);
    
    if(empty($errors))
    {
      $is_email = (is_email($social_user_or_email) and email_exists($social_user_or_email));
      
      if(!function_exists('username_exists'))
        require_once(ABSPATH . WPINC . '/registration.php');

      $is_username = username_exists($social_user_or_email);
      
      $user = false;

      // If the username & email are identical then let's rely on it as a username first and foremost
      if($is_username)
        $user = socialUser::get_stored_profile_by_screenname( $social_user_or_email );
      else if($is_email)
        $user = socialUser::get_stored_profile_by_id( socialUtils::get_user_id_by_email( $social_user_or_email ) );
      
      if($user)
      {
        $user->send_reset_password_requested_notification();

        require( social_VIEWS_PATH . "/social-users/forgot_password_requested.php" );
      }
      else
        require( social_VIEWS_PATH . "/shared/unknown_error.php" );
    }
    else
    {
      require( social_VIEWS_PATH . "/shared/errors.php" );
      require( social_VIEWS_PATH . '/social-users/forgot_password.php' );
    }
  }
  
  function display_reset_password_form($social_key,$social_screenname)
  {
    $user = socialUser::get_stored_profile_by_screenname($social_screenname);

    if($user)
    {
      if($user->reset_form_key_is_valid($social_key))
        require( social_VIEWS_PATH . '/social-users/reset_password.php' );
      else
        require( social_VIEWS_PATH . '/shared/unauthorized.php' );
    }
    else
      require( social_VIEWS_PATH . '/shared/unauthorized.php' );
  }
  
  function process_reset_password_form()
  {
    global $social_options;
    $errors = socialUser::validate_reset_password($_POST,array());
    
    extract($_POST);
    
    if(empty($errors))
    {
      $user = socialUser::get_stored_profile_by_screenname( $social_screenname );
      
      if($user)
      {
        $user->set_password_and_send_notifications($social_key, $social_user_password);

        require( social_VIEWS_PATH . "/social-users/reset_password_thankyou.php" );
      }
      else
        require( social_VIEWS_PATH . "/shared/unknown_error.php" );
    }
    else
    {
      require( social_VIEWS_PATH . "/shared/errors.php" );
      require( social_VIEWS_PATH . '/social-users/reset_password.php' );
    }
  }
}
?>
