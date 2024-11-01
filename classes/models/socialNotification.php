<?php
class socialNotification
{
  function socialNotification()
  {
    add_filter('social-notification-types', array( &$this, 'add_notification_types' ));
  }
  
  function add_notification_types($notification_types)
  {
    $notification_types['friend_request']      = array( 'name'        => __('Friendship Request', 'social'),
                                                        'description' => __('Sent when someone requests you as a friend', 'social') );
    $notification_types['friend_verification'] = array( 'name'        => __('Friendship Verification', 'social'),
                                                        'description' => __('Sent when a friendship has been confirmed', 'social') );
    $notification_types['board_post']          = array( 'name'        => __('Board Post', 'social'),
                                                        'description' => __('Sent when someone posts to your Board', 'social') );
    $notification_types['board_comment']       = array( 'name'        => __('Board Comment', 'social'),
                                                        'description' => __("Sent when someone posts to one of your Board Posts or on a comment thread that you've participated in", 'social') );
    $notification_types['tagged_in_post']      = array( 'name'        => __('Tagged In a Post', 'social'),
                                                        'description' => __("Sent when someone tags you in a Board post", 'social') );

    $notification_types['tagged_in_comment']   = array( 'name'        => __('Tagged In a Comment', 'social'),
                                                        'description' => __("Sent when someone tags you in a Board comment", 'social') );
    
    return $notification_types;
  }
  
  /** Send notification that someone has requested your friendship
    */
  function friendship_requested($requestor_id, $friend_id)
  {
    global $social_options, $social_blogname, $social_blogurl;
    
    $requestor = socialUser::get_stored_profile_by_id($requestor_id);
    $friend    = socialUser::get_stored_profile_by_id($friend_id);

    if($requestor and $friend)
    {
      $friend_requests_url = get_permalink($social_options->friend_requests_page_id);
      
      $opener = sprintf(__('%1$s has requested you as a friend on %2$s!', 'social'), $requestor->screenname, $social_blogname);
      $closer = sprintf(__('Please visit %s to Accept or Ignore this request.', 'social'), $friend_requests_url );

      $mail_body =<<<MAIL_BODY
{$opener}

{$closer}
MAIL_BODY;
      $subject = sprintf(__('%1$s wants to be your Friend on %2$s', 'social'), $requestor->screenname, $social_blogname); //subject
      
      socialNotification::send_notification_email($friend, $subject, $mail_body, 'friend_request');
    }
  }
  
  /** Send notification that the friendship has been verified/confirmed
    */
  function friendship_verified($verifier_id, $requestor_id)
  {
    global $social_blogname;
    
    $requestor = socialUser::get_stored_profile_by_id($requestor_id);
    $verifier  = socialUser::get_stored_profile_by_id($verifier_id);
    
    if($requestor and $verifier)
    {
      $requestor_profile_url = $requestor->get_profile_url();
      $verifier_profile_url = $verifier->get_profile_url();
      
/*** Notify the requestor ***/
      $opener = sprintf(__('You\'re now friends with %1$s on %2$s!', 'social'), $verifier->screenname, $social_blogname);
      $closer = sprintf(__('Visit %1$s to see %2$s profile.', 'social'), $verifier_profile_url, $verifier->his_her);

      $mail_body =<<<MAIL_BODY
{$opener}

{$closer}
MAIL_BODY;

      $subject = sprintf(__('%1$s Has Confirmed Your Friend Request on %2$s', 'social'), $verifier->screenname, $social_blogname); //subject
      socialNotification::send_notification_email($requestor, $subject, $mail_body, 'friend_verification');

/*** Notify the verifier ***/
      $opener = sprintf(__('You\'re now friends with %1$s on %2$s!', 'social'), $requestor->screenname, $social_blogname);
      $closer = sprintf(__("Visit %s to see %s profile.", 'social'), $requestor_profile_url, $requestor->his_her);

      $mail_body =<<<MAIL_BODY
{$opener}

{$closer}
MAIL_BODY;
    
      $subject = sprintf(__('You\'re Now Friends With %1$s on %2$s', 'social'), $requestor->screenname, $social_blogname); //subject
      socialNotification::send_notification_email($verifier, $subject, $mail_body, 'friend_verification');
    }
  }
  
  /** Send notification that the your board was posted to
    */
  function board_posted($board_post_id)
  {
    global $social_blogname, $social_options;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $board_post = $social_board_post->get_one($board_post_id);
    
    $owner  = socialUser::get_stored_profile_by_id( $board_post->owner_id );
    $author = socialUser::get_stored_profile_by_id( $board_post->author_id );
    
    if( $board_post->owner_id != $board_post->author_id )
    {
      if($owner and $author)
      {
        $owner_profile_url = get_permalink($social_options->profile_page_id);
        
        $opener = sprintf(__('%1$s posted this on your Board at %2$s', 'social'), $author->screenname, $social_blogname);
        $closer = sprintf(__("Visit %s to see your Board", 'social'), $owner_profile_url);
        $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_post->message}"

{$closer}.
MAIL_BODY;
        $subject = sprintf(__('%1$s Posted to Your Board on %2$s', 'social'), $author->screenname, $social_blogname); //subject
        socialNotification::send_notification_email($owner, $subject, $mail_body, 'board_post');
      }
    }
    
    $tagged_users =& socialBoardsHelper::get_tagged_users($board_post->message);
    
    if($tagged_users and is_array($tagged_users))
    {
      foreach ($tagged_users as $key => $tagged_user)
      {
        if( $tagged_user->id != $owner->id and 
            $tagged_user->id != $author->id )
        {
          $opener = sprintf(__("You were tagged in %1\$s's post on %2\$s's Board at %3\$s", 'social'), $author->screenname, $owner->screenname, $social_blogname);
          $closer = sprintf(__("Visit %s to see the post you were tagged in.", 'social'), socialBoardsHelper::board_post_url($board_post->id));
          $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_post->message}"

{$closer}.
MAIL_BODY;
          $subject = sprintf(__('You were tagged in a Post on %s', 'social'), $social_blogname); //subject
          socialNotification::send_notification_email($tagged_user, $subject, $mail_body, 'tagged_in_post');
        }
      }
    }
  }

  /** Send notification that the your board post or a board post you've commented on was commented on
    */
  function board_commented($board_comment_id)
  {
    global $social_blogname, $social_board_comment;
    
    $social_board_post =& socialBoardPost::get_stored_object();
    
    $board_comment = $social_board_comment->get_one($board_comment_id);
    $board_post    = $social_board_post->get_one($board_comment->board_post_id);
    $owner         = socialUser::get_stored_profile_by_id($board_post->owner_id);
    $post_author   = socialUser::get_stored_profile_by_id($board_post->author_id);
    $author        = socialUser::get_stored_profile_by_id($board_comment->author_id);

    if($owner and $author)
    {
      $comments      = $social_board_comment->get_all_by_board_post_id($board_post->id);
      $commentor_ids = array();
      $commentors    = array();
      $owner_profile_url = $owner->get_profile_url();
      
      foreach ($comments as $comment)
      {
        if( $comment->author_id != $owner->id and
            $comment->author_id != $author->id and
            $comment->author_id != $post_author->id and
            !in_array($comment->author_id, $commentor_ids))
        {
          $commentor_ids[] = $comment->author_id;
          $curr_commentor  = socialUser::get_stored_profile_by_id($comment->author_id);

          if($curr_commentor)
            $commentors[] = $curr_commentor;
        }
      }
      
      /*** Send notification to board owner ***/      
      if($owner->id != $author->id)
      {
        $opener = sprintf(__('%1$s commented on your Board Post on %2$s', 'social'), $author->screenname, $social_blogname);
        $closer = sprintf(__('View this comment from %1$s\'s Board at %2$s.', 'social'), $owner->screenname, socialBoardsHelper::board_post_url($board_post->id));

        $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_comment->message}"

{$closer}
MAIL_BODY;
        $subject = sprintf(__('%1$s commented on a Post on your Board on %2$s', 'social'), $author->screenname, $social_blogname); //subject
      
        socialNotification::send_notification_email($owner, $subject, $mail_body, 'board_comment');
      }
        
      /*** Send notification to the board post author ***/
      if( $owner->id != $post_author->id and
          $post_author->id != $author->id )
      {
        $opener = sprintf(__('%1$s commented on your Post on %2$s\'s Board on %3$s', 'social'), $author->screenname, $owner->screenname, $social_blogname);
        $closer = sprintf(__('View this comment from %1$s\'s Board at %2$s.', 'social'), $owner->screenname, socialBoardsHelper::board_post_url($board_post->id));

        $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_comment->message}"

{$closer}
MAIL_BODY;
        $subject = sprintf(__('%1$s commented on your Post on %2$s\'s Board on %3$s', 'social'), $author->screenname, $owner->screenname, $social_blogname); //subject

        socialNotification::send_notification_email($post_author, $subject, $mail_body, 'board_comment');
      }
      
      /*** Send notification to other commentors ***/
      $opener = sprintf(__('%1$s commented on %2$s\'s Board Post', 'social'), $author->screenname, $owner->screenname);
      $closer = sprintf(__('View this comment from %1$s\'s Board at %2$s.', 'social'), $owner->screenname, socialBoardsHelper::board_post_url($board_post->id));
      
      $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_comment->message}"

{$closer}
MAIL_BODY;

      $subject = sprintf(__('%1$s commented on %2$s\'s Board Post on %3$s', 'social'), $author->screenname, $owner->screenname, $social_blogname);

      foreach ($commentors as $commentor)
        socialNotification::send_notification_email($commentor, $subject, $mail_body, 'board_comment');

      /*** Send notification to users tagged in the comment ***/
      $tagged_users =& socialBoardsHelper::get_tagged_users($board_comment->message);
      
      if($tagged_users and is_array($tagged_users))
      {
        foreach ($tagged_users as $key => $tagged_user)
        {
          if( $tagged_user->id != $owner->id and 
              $tagged_user->id != $author->id and
              $tagged_user->id != $post_author->id and
              !in_array($tagged_user->id, $commentor_ids) )
          {
            $opener = sprintf(__("You were tagged in %1\$s's comment on %2\$s's Board at %3\$s", 'social'), $author->screenname, $owner->screenname, $social_blogname);
          $closer = sprintf(__("Visit %s to see the comment you were tagged in.", 'social'), socialBoardsHelper::board_post_url($board_post->id));
          $mail_body =<<<MAIL_BODY
{$opener}:

"{$board_comment->message}"

{$closer}.
MAIL_BODY;
            $subject = sprintf(__('You were tagged in a comment on %s', 'social'), $social_blogname); //subject
            socialNotification::send_notification_email($tagged_user, $subject, $mail_body, 'tagged_in_comment');
          }
        }
      }
    }
  }
  
  function send_notification_email_by_screenname($screenname, $subject, $message, $message_type)
  {
    $user = socialUser::get_stored_profile_by_screenname($screenname);
    
    if($user)
      socialNotification::send_notification_email($user, $subject, $message, $message_type);
  }
  
  function send_notification_email($user, $subject, $message, $message_type)
  {
    global $social_blogname, $social_options;
    
    if(isset($user->hide_notifications[$message_type]))
      return;
    
    $from_name     = $social_blogname; //senders name
    $from_email    = get_option('admin_email'); //senders e-mail address
    $recipient     = "{$to_name} <{$to_email}>"; //recipient
    $header        = "From: {$from_name} <{$from_email}>"; //optional headerfields
    $subject       = html_entity_decode(strip_tags(stripslashes($subject)));
    $message       = html_entity_decode(strip_tags(stripslashes($message)));
    $signature     = socialNotification::get_mail_signature();
    
    $to_email      = $user->email;
    $to_name       = $user->screenname;

    if( $social_options->mailer['type'] == 'smtp' )
      $full_to_email = $to_email;
    else
      $full_to_email = "{$to_name} <{$to_email}>";
    
    socialUtils::wp_mail($full_to_email, $subject, $message.$signature, $header);
    
    do_action('social_notification', $user, $subject, $message.$signature);
  }
  
  function get_mail_signature()
  {
    global $social_options, $social_blogname;
    
    $admin_email = get_option('admin_email');
    $settings_url = get_permalink($social_options->profile_edit_page_id);
    
    $thanks              = __('Thanks!', 'social');
    $team                = sprintf(__('%s Team', 'social'), $social_blogname);
    $manage_subscription = sprintf(__('If you want to stop future emails like this from coming to you, please modify your notification settings at %1$s or contact the system administrator at %2$s.', 'social'), $settings_url, $admin_email);

    $signature =<<<MAIL_SIGNATURE


{$thanks}

{$team}

------

{$manage_subscription}
MAIL_SIGNATURE;

    return $signature;
  }
}
?>
