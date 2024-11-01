<?php

class socialBoardsController
{
  function socialBoardsController()
  {
    add_filter('social-activity-types', array( &$this, 'add_activity_types' ));
  }
  
  function add_activity_types($activity_types)
  {
    $activity_types['board_posts'] = array( 'name'         => __('Board Posts', 'social'),
                                            'message'      => __('%1$s posted to %2$s\'s board', 'social') . '|$owner->screenname, $author_profile_link',
                                            'icon'         => social_URL . '/images/board_post.png' );
    $activity_types['board_comments'] = array( 'name'         => __('Board Comments', 'social'),
                                               'message'      => __('%1$s commented on %2$s\'s post', 'social') . '|$owner->screenname, $author_profile_link',
                                               'icon'         => social_URL . '/images/board_comment.png' );
    return $activity_types;
  }

  function display($user_id, $page=1, $page_size=15)
  {
    global $social_options, $social_friend, $social_user, $social_app_helper, $social_board_comment, $current_user;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $page_start_index = (($page-1)*$page_size);
    
    $owner_id  = $user_id;
    $author_id = $social_user->id;
    
    $user = socialUser::get_stored_profile_by_id($user_id);

    if($user)
    {
      $messages = "'" . implode("','",array_keys($social_options->activity_types)) . "'";
      $board_posts = $social_board_post->get_all_by_user_id( $user_id, true, " AND (type='post' or (type='activity' and source IN ({$messages})))", 'created_at DESC', "{$page_start_index},{$page_size}" );
      
      if($page==1) // only show the status on the first page
        require social_VIEWS_PATH . "/social-profiles/profile_status.php";
      require social_VIEWS_PATH . "/social-boards/display.php";
    }
  }
  
  function activity_display($user_id, $page=1, $page_size=15)
  {
    global $social_friend, $social_user, $social_app_helper, $social_board_comment, $current_user;
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $page_start_index = (($page-1)*$page_size);
    
    $owner_id  = $user_id;
    $author_id = $social_user->id;
    $public = true;
    
    $board_posts = $social_board_post->get_all_public_by_user_id( $user_id, true, '', 'created_at DESC', "{$page_start_index},{$page_size}" );
    
    require social_VIEWS_PATH . "/social-boards/display.php";
  }
  
  /** This displays a board post with all it's comments */
  function display_board_post($board_post, $public=false)
  {
    global $social_options, $social_friend, $social_user, $social_app_helper, $social_board_comment, $current_user, $social_boards_controller;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $author_id = $social_user->id;
    $author = socialUser::get_stored_profile_by_id($board_post->author_id);
    $owner  = socialUser::get_stored_profile_by_id($board_post->owner_id);
    
    if($author and $owner)
    {
      if(isset($_GET['mbpost']))
      {
        if($owner->privacy == 'public' or 
                  ( socialUser::is_logged_in_and_visible() and 
                    ( ($social_user->id == $owner->id) or 
                      ($social_user->id == $author->id) or 
                      $social_friend->is_friend($social_user->id,$owner->id) or
                      $social_friend->is_friend($social_user->id,$author->id))))
          require social_VIEWS_PATH . "/social-boards/board_post.php";
        else
        {
          $user =& $owner;
          require( social_VIEWS_PATH . '/social-boards/private.php' );
        }
        return;
      }
      else
        require social_VIEWS_PATH . "/social-boards/board_post.php";
    }
  }

  function display_comment($comment, $public=false, $comment_hidden_class='')
  {
    global $social_options, $social_friend, $social_user, $social_app_helper, $social_board_comment, $current_user;
    $social_board_post =& socialBoardPost::get_stored_object();
    $author_id = $social_user->id;
    require social_VIEWS_PATH . "/social-boards/board_comment.php";
  }
  
  function clear_status($user_id)
  {
    global $social_user;
    
    if( socialUser::is_logged_in_and_visible() and $social_user->id == $user_id )
      $social_user->clear_status();
  }

  function post($owner_id, $author_id, $message, $public=false)
  {
    global $social_friend, $social_user;
    
    $social_board_post =& socialBoardPost::get_stored_object();

    if( socialUser::is_logged_in_and_visible() and
        ( ($owner_id==$author_id) or
          $social_friend->is_friend($owner_id, $author_id) ) )
    {
      $board_post_id = $social_board_post->create($owner_id, $author_id, strip_tags(socialBoardsHelper::escape_code_blocks(stripslashes(socialAppHelper::decode_unicode($message)))));

      if(isset($board_post_id) and !empty($board_post_id) and is_numeric($board_post_id))
        do_action('social_post_to_board', $board_post_id);
    }
      
    if( socialUser::is_logged_in_and_visible() and ($owner_id==$author_id) )
      $social_user->update_status(strip_tags(socialBoardsHelper::escape_code_blocks(stripslashes(socialAppHelper::decode_unicode($message)))));
    
    if( socialUser::is_logged_in_and_visible() and ($owner_id!=$author_id) )
      $social_board_post->add_activity_by_id( $author_id, $owner_id, 'board_posts' );

    if($public)
      $this->activity_display($owner_id);
    else
      $this->display($owner_id);
  }
  
  function comment($board_post_id, $author_id, $message, $public=false)
  {
    global $social_friend, $social_board_comment, $social_user;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $board_post = $social_board_post->get_one($board_post_id);
    
    if( socialUser::is_logged_in_and_visible() and
        ( ($board_post->owner_id == $author_id) or
          ($board_post->author_id == $author_id) or
          $social_friend->is_friend($author_id, $board_post->owner_id) or 
          $social_friend->is_friend($author_id, $board_post->author_id) ) )
      $comment_id = $social_board_comment->create($board_post_id, $author_id, strip_tags(socialBoardsHelper::escape_code_blocks(stripslashes(socialAppHelper::decode_unicode($message)))));

    if( socialUser::is_logged_in_and_visible() and 
        ( $board_post->owner_id != $author_id ) and
        ( $board_post->author_id != $author_id ) )
      $social_board_post->add_activity_by_id( $author_id, $board_post->author_id, 'board_comments' );
    
    $comment = $social_board_comment->get_one($comment_id);
    $board_post = $social_board_post->get_one($comment->board_post_id, true);
    
    $this->display_comment($comment, $public);
    $this->display_comment_form( $social_user->id, $board_post, $public, true );
  }
  
  function delete_post($board_post_id, $public=false)
  {
    global $social_user;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $board_post = $social_board_post->get_one($board_post_id);

    if( $social_user->is_logged_in_and_current_user($board_post->owner_id) or
        $social_user->is_logged_in_and_current_user($board_post->author_id) or
        $social_user->is_logged_in_and_an_admin())
      $social_board_post->delete($board_post_id);

    if($public)
      $this->activity_display($board_post->owner_id);
    else
      $this->display($board_post->owner_id);
  }
  
  function delete_comment($board_comment_id, $public=false)
  {
    global $social_board_comment, $social_user;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $board_comment = $social_board_comment->get_one($board_comment_id);
    $board_post = $social_board_post->get_one($board_comment->board_post_id);

    if( $social_user->is_logged_in_and_current_user($board_post->owner_id) or
        $social_user->is_logged_in_and_current_user($board_comment->author_id) or
        $social_user->is_logged_in_and_an_admin())
      $social_board_comment->delete($board_comment_id);

    if($public)
      $this->activity_display($social_user->id);
    else
      $this->display($board_post->owner_id);
  }
  
  function display_comment_form( $author_id, $board_post, $public=false, $show_fake_form=true )
  {
    global $social_friend;
    $author = socialUser::get_stored_profile_by_id($author_id);

    if($author)
    {
      $board_post_id = $board_post->id;

      $avatar = $author->get_avatar(36);

      require( social_VIEWS_PATH . "/social-boards/comment_form.php" );
    }
  }
  
  function show_older_posts($screenname,$page,$location)
  {
    $user = false;
    if(!empty($screenname))
      $user = socialUser::get_stored_profile_by_screenname($screenname);

    if($user)
    {
      if($location=='boards')
        $this->display($user->id, $page);
      else if($location=='activity')
        $this->activity_display($user->id, $page);
    }
  }
}
?>