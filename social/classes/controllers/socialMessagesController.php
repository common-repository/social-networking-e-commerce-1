<?php

class socialMessagesController
{
  function socialMessagesController()
  {
    add_action('social-profile-display', array( $this, 'display_message_button' ), 1);
    //add_action('social-profile-list-name-display', array( $this, 'display_message_button'), 1);
  }

  function display_composer()
  {
    global $social_user;
    
    if (isset($_GET['u']) && !empty($_GET['u']))
    {
      $to_id = $_GET['u'];
      $curr_user =& socialUser::get_stored_profile_by_id($to_id);
      $to = $curr_user->screenname.", ";
    }

    require( social_VIEWS_PATH . "/social-messages/composer.php" );
  }
  
  function display_messages($pagenum=1,$scrub_values=false)
  {
    global $social_message, $social_user, $social_options;
    if( socialUser::is_logged_in_and_visible() )
    {
      $page_count = $social_message->get_page_count();

      if( !isset($pagenum) or 
          empty($pagenum) or 
          ($pagenum > $page_count) )
        $pagenum = 1;

      $messages = $social_message->get_all_recieved_messages($pagenum);
      
      if($scrub_values)
      {
        unset($_POST['action']);
        unset($_POST['social_message_subject']);
        unset($_POST['social_message_body']);
        unset($_POST['social_message_recipients']);
      }
      
      $permalink = get_permalink($social_options->inbox_page_id);
      $param_char = socialAppController::get_param_delimiter_char($permalink);

      $prev_page = (int)$pagenum - 1;
      $next_page = (($pagenum < $page_count)?((int)$pagenum + 1):0);

      require( social_VIEWS_PATH . "/social-messages/list.php" );
    }
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }
  
  function display_message_button($user_id)
  {
    global $social_options, $social_friend, $social_user;
    
    if( socialUtils::is_user_logged_in() and
        socialUser::user_exists_and_visible($social_user->id) and
        $social_friend->is_friend($social_user->id, $user_id))
    {
      $user = socialUser::get_stored_profile_by_id($user_id);
      
      $permalink = get_permalink($social_options->inbox_page_id);
      $param_char = socialAppController::get_param_delimiter_char($permalink);
      
      require( social_VIEWS_PATH . "/social-messages/button.php" );
    }
  }
  
  function display_message($thread_id)
  {
    global $social_message;
    
    if( socialUser::is_logged_in_and_visible() )
    {
      $messages = $social_message->get_all_messages_by_thread_id($thread_id, true);
      $thread   = $social_message->get_thread($thread_id);
      
      $social_message->mark_unread_status($thread_id, false);
      
      require( social_VIEWS_PATH . "/social-messages/view.php" );
    }
    else
      require social_VIEWS_PATH . "/shared/unauthorized.php";
  }
  
  function display_single_message($message)
  {
    $author = socialUser::get_stored_profile_by_id($message->author_id);
    $avatar = $author->get_avatar(48);
    $body = socialBoardsHelper::format_message($message->body, false);
    
    require( social_VIEWS_PATH . "/social-messages/single.php" );
  }
  
  function create_message($user_id, $subject, $body, $parties)
  {
    global $social_message, $social_user;
    
    $ids = array();
    $screennames = explode(',',preg_replace('#,$#','',$parties));
    
    if(is_array($screennames))
    {
      foreach($screennames as $screenname)
      {
        $curr_user =& socialUser::get_stored_profile_by_screenname($screenname);
        
        if($curr_user and is_object($curr_user))
          $ids[] = $curr_user->id;
      }
  
      $ids[] = $user_id;
      $ids = array_unique($ids); // Remove any duplicates
    }

    $errors = $social_message->validate($_POST, $ids, array());

    if(empty($errors))
    {
      if($this->send_initial_message( $social_user->id, $ids, $subject, $body ))
        require( social_VIEWS_PATH . "/social-messages/message_sent.php" );
      $this->display_messages(1,true);
    }
    else
    {
      require( social_VIEWS_PATH . "/shared/errors.php" );
      $this->display_messages();
    }
  }
  
  function create_reply($thread_id, $body)
  {
    global $social_message, $social_user;

    if(!empty($body) and $message_id = $this->send_message( $thread_id, $social_user->id, $body, "reply" ))
    {
      $message = new stdClass;
      $message->author_id     = $social_user->id;
      $message->body          = $body;
      $message->created_at_ts = time();

      $this->display_single_message($message);
    }
    
    return '';
  }
  
  function delete_thread($thread_id)
  {
    global $social_message;
    
    $social_message->delete_messages($thread_id);
  }

  function delete_threads($threads)
  {
    global $social_message;
    
    $threads = explode(',', $threads);
    $social_message->delete_messages_mult_threads($threads);
  }

  function mark_unread_statuses($threads, $unread=0)
  {
    global $social_message;
    
    $threads = explode(',', $threads);
    $social_message->mark_unread_status_mult_threads($threads, $unread);
  }
  
  function send_initial_message( $author_id, $parties, $subject, $body )
  {
    global $social_message;
    
    if(is_array($parties))
    {
      $thread_id = $social_message->create_thread($author_id, $parties, $subject);
    
      if($thread_id)
        return $this->send_message( $thread_id, $author_id, $body );
    }

    return false;
  }
  
  
  function send_message($thread_id, $author_id, $body, $type="message" )
  {
    global $social_message, $social_user;

    $thread = $social_message->get_thread($thread_id);
    
    $parties = explode(',',$thread->parties);
    
    if(is_array($parties) and !empty($parties))
    {
      foreach($parties as $recipient_id)
      {
        $unread = (($author_id == $recipient_id)?0:1);
        $social_message->create_message($thread_id, $author_id, $recipient_id, $body, $unread);
      
        if( $recipient_id != $social_user->id )
        {
          if($type=="message")
            $social_message->message_notification( $recipient_id, $thread_id, $thread->subject, $body );
          else if($type=="reply")
            $social_message->message_reply_notification( $recipient_id, $thread_id, $thread->subject, $body );
        }
      }
      
      return true;
    }
    else
     return false;
  }
  
  function lookup_friends($query_str,$output='json')
  {
    global $social_user, $social_friend, $social_utils, $social_message;

    if($output=='json')
    {
      if ($social_user->is_admin()) //Allows admins to message all users regardless of friendship status
        $friends_array = $social_message->get_all_users_if_admin("user_login LIKE '%{$query_str}%' AND user_login <> '{$social_user->screenname}'");
      else
        $friends_array = $social_friend->get_all_by_user_id( $social_user->id, "status='verified' AND user_login LIKE '%{$query_str}%'" );
      $fmt_friends_array = array();
      foreach($friends_array as $friend)
      {
        $avatar = get_avatar($friend->ID, 20);
        $screenname = $friend->user_login;
        $higlighted_screenname = preg_replace("#(" . preg_quote($query_str) . ")#", '<strong>$1</strong>', $screenname);
        
        $fmt_friends_array[] = array("id" => $friend->ID, "value" => $screenname, "label" => "<span class='social_friend_dropdown_item'>{$avatar}&nbsp;<span class='social_friend_dropdown_text'>{$higlighted_screenname}</span></span>");
      }
      echo socialAppHelper::json_encode($fmt_friends_array);
    }
  }
}
?>