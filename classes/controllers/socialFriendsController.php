<?php

class socialFriendsController
{
  function socialFriendsController()
  {  
    add_filter('social-activity-types', array( &$this, 'add_activity_types' ));
    add_action('user_register', array(&$this, 'add_default_friends') );
  }
  
  function add_activity_types($activity_types)
  {
    $activity_types['friendship_confirmation'] = array( 'name'    => 'Friend Confirmation',
                                                        'message' => __('%1$s is now friends with %2$s', 'social') . '|$owner->screenname, $author_profile_link',
                                                        'icon'    => social_URL . "/images/smiley.png" );
    return $activity_types;
  }

  function list_friends($dir_page,$user_param,$user_search=false,$search_query='',$dir_page_size=25)
  {
    global $social_friends_controller, $social_friend, $social_user, $social_blogurl, $social_options;

    if(!empty($search_query))
      $dir_page = '';

    if(socialUser::is_logged_in_and_visible() and (!isset($user_param) OR empty($user_param) OR !$user_param))
    {
      $user = $social_user;
      $page_params = '';
    }
    else if( isset($user_param) and 
             !empty($user_param) and
             $user = socialUser::get_stored_profile_by_screenname($user_param) )
      $page_params = '&u=' . $user_param;
    else
    {
      require social_VIEWS_PATH . "/shared/unauthorized.php";
      return;
    }
    
    $user_avatar = $user->get_avatar(75);
    
    $where_clause = (!empty($search_query)?"user_login LIKE '%{$search_query}%'":'');
    $where_clause_wn = (!empty($search_query)?" AND {$where_clause}":'');

    $dir_page = (empty($dir_page)?0:($dir_page-1));
    $dir_offset = $dir_page * $dir_page_size;
    $friends = socialFriend::get_friends_user_array($user->id, $where_clause, $dir_offset,$dir_page_size);

    $record_count = socialFriend::get_friend_count($user->id, "status='verified'{$where_clause_wn}", $search_query );
    $num_pages = $record_count / $dir_page_size;

    $prev_page = $dir_page;
    $next_page = ((($dir_page+1) >= $num_pages)?0:($dir_page + 2));
    
    $friends_page_url = get_permalink($social_options->friend_page_id);
    $param_char = ((preg_match("#\?#",$friends_page_url))?'&':'?');
  
    require social_VIEWS_PATH . "/social-friends/list.php";
  }
  
  function list_friend_requests()
  {  
    global $current_user, $social_friend;
    if(socialUser::is_logged_in_and_visible())
    {
      socialUtils::get_currentuserinfo();
    
      $requests = $social_friend->get_friend_requests($current_user->ID);
    
      require social_VIEWS_PATH . "/social-friends/requests.php"; 
    }
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }
  
  function accept_friend($request_id)
  {
    if(socialUser::is_logged_in_and_visible())
    {
      global $social_friend;
      
      $social_board_post =& socialBoardPost::get_stored_object();
      
      $social_friend->accept_friend($request_id);
      
      $request = $social_friend->get_friend_request($request_id);
      
      if( socialFriend::can_friend($request->user_id, $request->friend_id) )
      {
        // Put an activity entry on both boards...
        $social_board_post->add_activity_by_id( $request->user_id, $request->friend_id, 'friendship_confirmation' );
        $social_board_post->add_activity_by_id( $request->friend_id, $request->user_id, 'friendship_confirmation' );
        
        $social_friend->delete_request( $request_id );
      }
    }
  }
  
  function ignore_friend($request_id)
  {
    if(socialUser::is_logged_in_and_visible())
    {
      global $social_friend;
      
      $social_friend->ignore_friend($request_id, $current_user);
    }
  }
  
  function display_add_friend_button($user_id, $friend_id)
  {
    if( socialUser::is_logged_in_and_visible() and 
        socialFriend::can_friend($user_id, $friend_id))
    {
      global $current_user, $social_friend;
    
      if($user_id == $friend_id)
      {
        require social_VIEWS_PATH . "/social-friends/me.php";
        return;
      }
    
      $friend = $social_friend->get_one_by_user_ids($user_id, $friend_id);
  
      if($friend)
      {
        if($friend->status == 'verified')
          require social_VIEWS_PATH . "/social-friends/already_friend.php";
        else 
          require social_VIEWS_PATH . "/social-friends/friend_requested.php";
      }
      else
      {
        if(  socialUser::user_exists_and_visible($user_id) and
             socialUser::user_exists_and_visible($friend_id) )
          require social_VIEWS_PATH . "/social-friends/add_button.php";
      }
    }
  }
  
  function friend_request($user_id,$friend_id)
  {
    global $social_user, $social_friend;
    
    if($social_user->is_logged_in_and_current_user($user_id) and socialFriend::can_friend($user_id, $friend_id))
      return $social_friend->request_friend( $user_id, $friend_id );
  }
  
  function delete_friend($user_id,$friend_id)
  {
    global $social_user, $social_friend;
    
    if($social_user->is_logged_in_and_current_user($user_id) and
       socialFriend::can_delete_friend($user_id, $friend_id))
      return $social_friend->delete_both($user_id,$friend_id);
  }
  
  function display_friends_grid($user_id,$cols=3,$rows=2)
  {
    global $social_friend;
    
    $grid_cell_count = $cols * $rows;
    $user_count = $social_friend->get_friend_count($user_id, "status='verified'");
    $owner = socialUser::get_stored_profile_by_id($user_id);
    if($owner)
    {
      $user_type = __('Friends', 'social');
      $all_users_url = $owner->get_friends_url();
      
      // Grab a random selection of friends from the database
      $users = $social_friend->get_friends_user_array( $user_id, '', 0, $grid_cell_count, 'RAND()' );
      require social_VIEWS_PATH . "/shared/user_grid.php";
    }
  }
  
  /** Add default friends to all existing users. */
  function add_default_friends_to_all_users()
  {  
    if(socialUser::is_logged_in_and_an_admin())
    {
      $users = socialUser::get_all();

      foreach($users as $user)
        $this->add_default_friends($user->ID);
    }
  }
  
  function add_default_friends($user_id)
  {
    global $social_options, $social_friend;

    if(count($social_options->default_friends) > 0)
    {   
      foreach($social_options->default_friends as $friend_id)
      {
        $friend_id = (int)$friend_id;
        if( $friend_id and
            !empty($friend_id) and
            ( $user_id != $friend_id ) and
            !$social_friend->is_friend($user_id,$friend_id) 
            )
        {
          $social_friend->create_both($user_id, $friend_id, 'verified');
        }
      }
    }
  }
}
?>