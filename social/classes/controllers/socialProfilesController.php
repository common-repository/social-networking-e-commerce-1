<?php

class socialProfilesController
{
  function socialProfilesController()
  {
    add_filter('social-activity-types', array( &$this, 'add_activity_types' ));
  }
  
  function add_activity_types($activity_types)
  {
    $activity_types['profile_updates'] = array( 'name'    => __('Profile Updates', 'social'),
                                                'message' => __('%1$s updated %2$s profile', 'social') . '|$owner->screenname, $owner->his_her',
                                                'icon'    => social_URL . "/images/smiley.png" );
    return $activity_types;
  }
  
  function directory($dir_page,$user_search=false,$search_query='',$dir_page_size=25)
  {
    // Reset to page 1 if a search term is entered
    if(!empty($search_query) and $user_search)
      $dir_page = '';
    
    $dir_page = (empty($dir_page)?0:($dir_page-1));
    $dir_offset = $dir_page * $dir_page_size;
    $profiles = socialUser::get_stored_profiles($search_query,$dir_offset,$dir_page_size);
    
    $search_where = '';
    $search_params = '';

    if(!empty($search_query))
    {
      $search_where = "user_login like '%{$search_query}%'";
      $search_params = "&q={$search_query}";
    }

    $record_count = socialUser::get_count($search_where);
    $num_pages    = $record_count / $dir_page_size;
    
    $prev_page = $dir_page;
    $next_page = ((($dir_page+1) >= $num_pages)?0:($dir_page + 2));
    require social_VIEWS_PATH . "/social-profiles/directory.php";
  }

  function profile($user_screenname='')
  {
    global $social_friends_controller, $social_boards_controller, $social_app_helper, $social_blogurl, $social_options;

    if( socialUser::is_logged_in_and_visible() and 
        empty($user_screenname) and
        $user = socialUser::get_stored_profile())
    {
      $avatar = $user->get_avatar(200);
      require social_VIEWS_PATH . "/social-profiles/profile.php";
    }
    else if( !empty($user_screenname) and 
             $user = socialUser::get_stored_profile_by_screenname($user_screenname) )
    {
      $screenname = $user_screenname;
      $avatar = $user->get_avatar(200);

      require social_VIEWS_PATH . "/social-profiles/profile.php";
    }
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }
  
  function activity()
  {
    global $social_friends_controller, $social_boards_controller, $social_app_helper, $social_blogurl;

    if( socialUser::is_logged_in_and_visible() and 
        $user = socialUser::get_stored_profile() )
      require social_VIEWS_PATH . "/social-profiles/activity.php";
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }

  function edit()
  {  
    global $social_user, $social_blogurl;

    $social_board_post =& socialBoardPost::get_stored_object();

    if(socialUser::is_logged_in_and_visible())
    {
      $avatar_size = 100;
      $avatar = $social_user->get_avatar($avatar_size);
      
      if(isset($_POST['action']) and $_POST['action'] == 'process_form')
      {
        $errors = apply_filters('social-profile-validate',$social_user->validate($_POST,array()));
        
        if(count($errors) <= 0)
        {
          $social_user->update($_POST);
          $social_user->store();

          $avatar = $social_user->get_avatar($avatar_size);
        
          do_action('social-profile-update', $social_user->id);

          $social_board_post->add_activity_by_id( $social_user->id, $social_user->id, 'profile_updates' );

          require social_VIEWS_PATH . "/social-profiles/profile_saved.php";
        }
        else
          require social_VIEWS_PATH . "/shared/errors.php";
      }
      
      require social_VIEWS_PATH . "/social-profiles/edit.php";
    }
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }
  
  function display_status($user_id)
  {
    global $social_app_helper;
    
    $user = socialUser::get_stored_profile_by_id($user_id);

    if($user)
      require social_VIEWS_PATH . "/social-profiles/profile_status.php";
  }
  
  function delete_avatar($user_id)
  {
    global $social_user;
    
    if(socialUser::is_logged_in_and_visible() and $user_id==$social_user->id)
    {
      $social_user->delete_avatars();
      
      $avatar_size = 100;
      $avatar_url  = $social_user->get_avatar($avatar_size);
      require social_VIEWS_PATH . "/social-profiles/edit_avatar.php";
    }
  }
}
?>
