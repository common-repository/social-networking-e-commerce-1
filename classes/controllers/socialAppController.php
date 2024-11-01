<?php

class socialAppController
{
  function socialAppController()
  {
	  add_filter('social-show-powered-by', array(&$this, 'show_powered_by'));
    add_filter('the_content', array( &$this, 'page_route' ), 100);
    add_action('wp_enqueue_scripts', array(&$this, 'load_scripts'), 1);
    add_action('admin_enqueue_scripts', array(&$this,'load_admin_scripts'));
    register_activation_hook(social_PATH."/social.php", array( &$this, 'install' ));
    
    // Used to process standalone requests (make sure social_init comes before parse_standalone_request)
    add_action('init', array(&$this,'social_init'));
    add_action('init', array(&$this,'parse_standalone_request'));
    add_filter('request', array(&$this,'parse_pretty_profile_url'));
    
    add_action('phpmailer_init', array(&$this, 'set_wp_mail_return_path'));
    add_action('phpmailer_init', array(&$this, 'set_wp_mailer'));
    add_action('admin_init', array(&$this, 'prevent_admin_access'));
  }
  
  function show_powered_by($value)
  {
    global $social_options;
    return $social_options->show_powered_by;
  }

  function setup_menus()
  {
    add_action('admin_menu', array( &$this, 'menu' ));
  }
  
  /********* INSTALL PLUGIN ***********/
  function install()
  {
    global $social_db, $social_update;
    
    $social_db->upgrade();
  }
  
  function menu()
  {
    global $social_options_controller, $social_update;
  
    add_menu_page(__('social', 'social'), __('social', 'social'), 8, 'social-options', array(&$social_options_controller,'route'), social_URL . "/images/social_16.png");
    add_submenu_page( 'social-options', __('Options', 'social'), __('Options', 'social'), 8, 'social-options', array(&$social_options_controller,'route') );
	add_submenu_page( 'social-options', __('categories', 'social'), __('categories', 'social'), 8, 'social-category', array(&$social_options_controller,'route1') );
add_submenu_page( 'social-options', __('products', 'social'), __('products', 'social'), 8, 'social-products', array(&$social_options_controller,'route2') );

  }
  
  function prevent_admin_access()
  {
    global $social_options;
    
    // Only prevent subscribers from accessing admin pages...
    if( $social_options->prevent_admin_access and 
        current_user_can('level_0') and 
        !current_user_can('level_1') )
    {
      $activity_page = get_permalink($social_options->activity_page_id);
      die("<script type='text/javascript'>window.location='{$activity_page}' </script>");
    }
  }
  
  // Routes for wordpress pages -- we're just replacing content here folks.
  function page_route($content)
  {
    global $post, 
           $social_options, 
           $social_profiles_controller, 
           $social_boards_controller,
           $social_friends_controller,
           $social_messages_controller, 
           $social_users_controller,
		    $social_store_controller;
			$social_photos_controller;

    $social_board_post =& socialBoardPost::get_stored_object();

    switch( $post->ID )
    {  
      case $social_options->activity_page_id:
        // Start output buffering -- we want to return the output as a string
        ob_start();
        $social_profiles_controller->activity();
        // Pull all the output into this variable
        $content = ob_get_contents();
        // End and erase the output buffer (so we control where it's output)
        ob_end_clean();
        break;
      case $social_options->profile_page_id:
        ob_start();
        if($this->get_param('mbpost'))
          $social_boards_controller->display_board_post($social_board_post->get_one($this->get_param('mbpost'),true));
        else
          $social_profiles_controller->profile($this->get_param('u'));
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->directory_page_id:
        ob_start();
        $social_profiles_controller->directory($this->get_param('mdp'),false,$this->get_param('q'));
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->profile_edit_page_id:
        ob_start();
        $social_profiles_controller->edit();
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->friends_page_id:
        ob_start();
        $social_friends_controller->list_friends($this->get_param('mdp'), $this->get_param('u'));
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->friend_requests_page_id:
        ob_start();
        $social_friends_controller->list_friend_requests();
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->login_page_id:
        ob_start();
        $action = $this->get_param('action');

        if( $action and $action == 'forgot_password' )
          $social_users_controller->display_forgot_password_form();
        else if( $action and $action == 'social_process_forgot_password' )
          $social_users_controller->process_forgot_password_form();
        else if( $action and $action == 'reset_password')
          $social_users_controller->display_reset_password_form($this->get_param('mkey'),$this->get_param('u'));
        else if( $action and $action == 'social_process_reset_password_form')
          $social_users_controller->process_reset_password_form();
        else
          $social_users_controller->display_login_form();

        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->signup_page_id:
        ob_start();
        $social_users_controller->display_signup_form();
        $content = ob_get_contents();
        ob_end_clean();
        break;
      case $social_options->inbox_page_id:
        // Start output buffering -- we want to return the output as a string
        ob_start();
        $thread_id = $this->get_param('t');
        $action    = $this->get_param('action');
      
        if( isset($action) and 
            ($action == 'view') and
            isset($thread_id) )
          $social_messages_controller->display_message( $thread_id );
        else if( isset($action) and 
                 $action == 'social_process_composer_form')
          $social_messages_controller->create_message( $this->get_param('social_user_id'), 
                                                     $this->get_param('social_message_subject'),
                                                     $this->get_param('social_message_body'),
                                                     $this->get_param('social_message_recipients') );
        else
          $social_messages_controller->display_messages($this->get_param('mgp'));
      
        // Pull all the output into this variable
        $content = ob_get_contents();
        // End and erase the output buffer (so we control where it's output)
        ob_end_clean();
        break;
		
		case $social_options->product_page_id:
       	 ob_start();
       	 $social_store_controller->display_product($this->get_param('checkout_page_id'));
      	 $content = ob_get_contents();
         ob_end_clean();
         break;
		 
		case $social_options->checkout_page_id:
        ob_start();
        $social_store_controller->checkout_page();
        $content = ob_get_contents();
        ob_end_clean();
        break;
		
		case $social_options->purchase_history_page_id:
        ob_start();
        $social_store_controller->display_puchase_history();
        $content = ob_get_contents();
        ob_end_clean();
        break;
		
		case $social_options->photo_page_id:
        ob_start();
		 $social_store_controller->display_photo();
        $content = ob_get_contents();
        ob_end_clean();
        break;
    }
    
    return $content;
  }  

  function load_scripts()
  {
    $this->enqueue_social_scripts();
  }
  
  function load_admin_scripts()
  {
    $admin_pages = apply_filters('social_admin_pages',array('social-options'));
    
    $curr_page = $_GET['page'];

    if(in_array($curr_page,$admin_pages))
      $this->enqueue_social_scripts();
  }
  
  function enqueue_social_scripts()
  {
    global $social_blogurl;
    if(socialUtils::rewriting_on())
      $social_js = $social_blogurl . '/social-js/social.js';
    else
      $social_js = $social_blogurl . '/index.php?social_js=social';

    if(socialUtils::is_version_at_least( '3.0-beta2' ))
      wp_enqueue_style( 'jquery-ui-all', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
    else
      wp_enqueue_style( 'jquery-ui-all', social_CSS_URL . '/jquery/ui.all.css' );
        
    wp_enqueue_style( 'social',  social_CSS_URL . '/social.css' );

    wp_enqueue_script( 'jquery-elastic', social_JS_URL . '/jquery.elastic.js', array('jquery') );
    wp_enqueue_script( 'jquery-qtip', social_JS_URL . '/jquery.qtip-1.0.0-rc3.min.js', array('jquery') );
    
    if(socialUtils::is_version_at_least( '3.0-beta2' ))
    {
      wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js', array('jquery') );
      wp_enqueue_script( 'social', $social_js, array('jquery','jquery-elastic','jquery-qtip','jquery-ui') );
    }
    else
    { // load older javascript libraries
      wp_enqueue_script( 'jquery-new-ui-core',    social_JS_URL . '/ui.core.js', array('jquery') );
      wp_enqueue_script( 'jquery-ui-datepicker', social_JS_URL . '/ui.datepicker.js', array('jquery','jquery-new-ui-core') );
      wp_enqueue_script( 'social', $social_js, array('jquery','jquery-elastic','jquery-qtip','jquery-new-ui-core','jquery-ui-datepicker') );
    }



    do_action('social_enqueue_scripts');
  }
  
  function social_js()
  {
    header('Content-type: application/javascript');
    require_once( social_JS_PATH . '/social.js.php' );
  }

  // The tight way to process standalone requests dogg...
  function parse_standalone_request()
  {
    global $social_users_controller;

    $plugin     = $this->get_param('plugin');
    $action     = $this->get_param('action');
    $controller = $this->get_param('controller');
    $social_js  = $this->get_param('social_js');

    if( !empty($plugin) and $plugin == 'social' and 
        !empty($controller) and !empty($action) )
    {
      $this->standalone_route($controller, $action);
      exit;
    }
    else if( socialUtils::rewriting_on() and preg_match("#/social-js/(.+)\.js.*#", $_SERVER['REQUEST_URI'], $matches) )
    {
      $this->standalone_route('js', $matches[1]);
      exit;
    }
    else if( !socialUtils::rewriting_on() and !empty($social_js) )
    {
      $this->standalone_route('js', $social_js);
      exit;
    }
    else if( isset( $_POST ) and isset( $_POST['social_process_login_form'] ) )
      $social_users_controller->process_login_form();
  }
  
  function parse_pretty_profile_url($query_vars)
  {
    global $social_blogurl, $social_options, $wpdb;
    
    if( socialUtils::rewriting_on() and $social_options->pretty_profile_urls )
    {
      $request_uri = urldecode($_SERVER['REQUEST_URI']);
      
      // Resolve WP installs in sub-directories
      preg_match('#^https?://.*?(/.*)$#', $social_blogurl, $subdir);
      
      $struct = socialUtils::get_permalink_pre_slug_uri();
      
      $match_str = '#^'.$subdir[1].$struct.'(.*?)([\?/].*?)?$#';

      if(preg_match($match_str, $request_uri, $match_val))
      {
        // match short slugs (most common)
        if(isset($match_val[1]) and !empty($match_val[1]) and socialUser::screenname_exists_and_visible($match_val[1]))
        {
          // figure out the pagename var
          $pagename = get_permalink($social_options->profile_page_id);
          $pagename = str_replace( $social_blogurl . $struct, '', $pagename);
          $pagename = preg_replace( '#^/#', '', $pagename);
          $pagename = preg_replace( '#/$#', '', $pagename);

          // Resolve the pagename to the profile page
          $query_vars['pagename'] = $pagename;

          // Artificially set the GET variable
          $_GET['u'] = $match_val[1];

          // Unset the indeterminate query_var['name'] now that we have a pagename
          unset($query_vars['name']);
        }
      }  
    }
    
    return $query_vars;
  }

  // Routes for standalone / ajax requests
  function standalone_route($controller, $action)
  {
    global $social_friends_controller, 
           $social_boards_controller,
           $social_profiles_controller,
           $social_messages_controller,
           $social_captcha_controller,
           $social_options_controller;
    
    if($controller=='friends')
    {
      if($action=='friend_request')
        $social_friends_controller->friend_request($this->get_param('user_id'), $this->get_param('friend_id'));
      if($action=='delete_friend')
        $social_friends_controller->delete_friend($this->get_param('user_id'), $this->get_param('friend_id'));
      else if($action=='accept_friend')
        $social_friends_controller->accept_friend($this->get_param('request_id'));
      else if($action=='ignore_friend')
        $social_friends_controller->ignore_friend($this->get_param('request_id'));
      else if($action=='search')
        $social_friends_controller->list_friends($this->get_param('mdp'),$this->get_param('u'),true,$this->get_param('q'));
    }
    else if($controller=='boards')
    {
      if($action=='post')
        $social_boards_controller->post($this->get_param('owner_id'), $this->get_param('author_id'), $this->get_param('message'));
      else if($action=='comment')
        $social_boards_controller->comment($this->get_param('board_post_id'), $this->get_param('author_id'), $this->get_param('message'));
      else if($action=='delete_post')
        $social_boards_controller->delete_post($this->get_param('board_post_id'));
      else if($action=='delete_comment')
        $social_boards_controller->delete_comment($this->get_param('board_comment_id'));
      else if($action=='older_posts')
        $social_boards_controller->show_older_posts($this->get_param('u'),$this->get_param('mdp'),$this->get_param('loc'));
      else if($action=='clear_status')
        $social_boards_controller->clear_status($this->get_param('u'));
    }
    else if($controller=='activity')
    {
      if($action=='post')
        $social_boards_controller->post($this->get_param('owner_id'), $this->get_param('author_id'), $this->get_param('message'),true);
      else if($action=='comment')
        $social_boards_controller->comment($this->get_param('board_post_id'), $this->get_param('author_id'), $this->get_param('message'),true);
      else if($action=='delete_post')
        $social_boards_controller->delete_post($this->get_param('board_post_id'),true);
      else if($action=='delete_comment')
        $social_boards_controller->delete_comment($this->get_param('board_comment_id'),true);
    }
    else if($controller=='profile')
    {  
      if($action=='delete_avatar')
        $social_profiles_controller->delete_avatar($this->get_param('user_id'));
      else if($action=='search')
        $social_profiles_controller->directory($this->get_param('mdp'),true,$this->get_param('q'));
    }
    else if($controller=='options')
    {
      if($action=='add_default_user')
        $social_options_controller->display_default_friend_drop_down();
      else if($action=='add_custom_field')
        $social_options_controller->display_custom_field($this->get_param('index'));
      else if($action=='add_custom_field_option')
        $social_options_controller->display_custom_field_option( $this->get_param('field_index'), 
                                                               $this->get_param('option_index') );
    }
    else if($controller=='js')
    {
      if($action=='social')
        $this->social_js();
    }
    else if($controller=='captcha')
    {
      if($action=='display')
        $social_captcha_controller->display_captcha($this->get_param('width','120'), $this->get_param('height', '40'), $this->get_param('code', ''));
    }
    else if($controller=='messages')
    {
      if($action=='lookup_friends')
        $social_messages_controller->lookup_friends($this->get_param('q'));
      else if($action=='social_process_reply_form')
        $social_messages_controller->create_reply( $this->get_param('social_thread_id'),
                                                 $this->get_param('social_reply') );
      else if($action=='delete_thread')
        $social_messages_controller->delete_thread( $this->get_param('t') );
      else if($action=='delete_threads')
        $social_messages_controller->delete_threads( $this->get_param('ts') );
      else if($action=='mark_read')
        $social_messages_controller->mark_unread_statuses( $this->get_param('ts'), 0 );
      else if($action=='mark_unread')
        $social_messages_controller->mark_unread_statuses( $this->get_param('ts'), 1 );
    }
  }
  
  function load_language()
  {
    $path_from_plugins_folder = str_replace( ABSPATH, '', social_PATH ) . '/i18n/';
    
    load_plugin_textdomain( 'social', $path_from_plugins_folder );
  }
  
  function social_init()
  {
  	add_filter('get_avatar', array($this,'override_avatar'), 10, 4);
    add_filter('get_comment_author_url', array($this,'override_author_url'));
  }
  
  function override_author_url($url)
  {
    global $comment;
    
    $user = socialUser::get_stored_profile_by_id($comment->user_id, false);
    
    if($user)
      return $user->get_profile_url();
    else
      return $url;
  }
  
  function override_avatar($avatar, $id_or_email, $size, $default)
  {
    $user_id = false;

    if( is_object($id_or_email) and $id_or_email->comment_author_email )
      $user_id = (int)socialUtils::get_user_id_by_email($id_or_email->comment_author_email);
    else if( is_numeric($id_or_email) )
      $user_id = (int)$id_or_email;
    else if( is_string($id_or_email) )
      $user_id = (int)socialUtils::get_user_id_by_email($id_or_email);
    
    if(!$user_id or empty($user_id))
      return $avatar;

    $avatar = socialAppHelper::get_avatar_img_by_id($user_id, $size, $avatar);
    return $avatar;
  }

  // Utility function to grab the parameter whether it's a get or post
  function get_param($param, $default='')
  {
    return (isset($_POST[$param])?$_POST[$param]:(isset($_GET[$param])?$_GET[$param]:$default));
  }
  
  function get_param_delimiter_char($link)
  { 
    return ((preg_match("#\?#",$link))?'&':'?');
  }
  
  function set_wp_mail_return_path($args)
  {
    // Apparently wp_mail ignores the Return-Path
    // header so let's set it manually here...
    $args->Sender = get_option('admin_email');
  }

  function set_wp_mailer($args)
  {
    global $social_options;
    
    if( isset($social_options->mailer['type']) and 
        $social_options->mailer['type'] == 'sendmail' )
    {
      $args->IsSendmail();
      $args->Sendmail = $social_options->mailer['sendmail-path'];
    }
    else if( isset($social_options->mailer['type']) and 
             $social_options->mailer['type'] == 'smtp' )
    {
      $args->IsSMTP();
      $args->Host       = $social_options->mailer['smtp-host'];
      $args->Port       = $social_options->mailer['smtp-port'];
      $args->SMTPSecure = $social_options->mailer['smtp-secure'];
      
      if( !empty($social_options->mailer['smtp-username']) and
          !empty($social_options->mailer['smtp-password']) )
      {
        $args->SMTPAuth = true;
        $args->Username = $social_options->mailer['smtp-username'];
        $args->Password = $social_options->mailer['smtp-password'];
      }
    }
  }
}
?>
