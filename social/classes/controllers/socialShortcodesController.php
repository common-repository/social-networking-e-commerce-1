<?php

class socialShortcodesController
{
  function socialShortcodesController()
  {
    add_shortcode('social-user-grid',array(&$this,'user_grid'));
    add_shortcode('social-login', array(&$this,'login'));
	add_shortcode('social-login', array(&$this,'checkout'));
  }
  
  function user_grid($atts)
  {
    global $social_options;
    
    extract(shortcode_atts(array(
  		'cols' => '3',
  		'rows' => '2',
  		'type' => 'random'
  	), $atts));
  	
    $cols = (int)$cols;
    $rows = (int)$rows;

    $grid_cell_count = $cols * $rows;
    $user_count = socialUser::get_count();
    $user_type = 'Users';
    $all_users_url = get_permalink($social_options->directory_page_id);
    
    // Grab a random selection of friends from the database
    if( $type == 'random' )
      $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'RAND()' );
    else if( $type == 'recent' )
      $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'ID DESC' );

    ob_start();
    require social_VIEWS_PATH . "/shared/user_grid.php";
    
    // Pull all the output into this variable
    $shortcode = ob_get_contents();
    
    // End and erase the output buffer (so we control where it's output)
    ob_end_clean();
    
    return $shortcode;
  }
  
  function login($atts)
  {
    global $social_options, $social_blogurl, $social_blogname;	
    
    if(!empty($social_options->signup_page_id) and $social_options->signup_page_id > 0)
      $signup_url = get_permalink($social_options->signup_page_id);
    else
      $signup_url = $social_blogurl . '/wp-login.php?action=register';
    
    ob_start();
    require( social_VIEWS_PATH . '/shared/login_widget.php' );
    
    
    // Pull all the output into this variable
    $shortcode = ob_get_contents();
    
    // End and erase the output buffer (so we control where it's output)
    ob_end_clean();
    
    return $shortcode;
  }
  
  function checkout($atts)
  {
    global $social_options;
    
    extract(shortcode_atts(array(
  		'cols' => '3',
  		'rows' => '2',
  		'type' => 'random'
  	), $atts));
  	
    $cols = (int)$cols;
    $rows = (int)$rows;

    $grid_cell_count = $cols * $rows;
    $user_count = socialUser::get_count();
    $user_type = 'Users';
    $all_users_url = get_permalink($social_options->directory_page_id);
    
    // Grab a random selection of friends from the database
    if( $type == 'random' )
      $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'RAND()' );
    else if( $type == 'recent' )
      $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'ID DESC' );

    ob_start();
    require social_VIEWS_PATH . "/shared/user_grid.php";
    
    // Pull all the output into this variable
    $shortcode = ob_get_contents();
    
    // End and erase the output buffer (so we control where it's output)
    ob_end_clean();
    
    return $shortcode;
  }
  
}
?>