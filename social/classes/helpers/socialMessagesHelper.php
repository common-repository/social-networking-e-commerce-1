<?php

class socialMessagesHelper {
  function format_party_list($parties)
  {
    global $social_user;
    
    $user_list = $social_user->get_profile_link(__('You', 'social'));

    foreach($parties as $index => $user_id)
    {
      if($user_id == $social_user->id)
        continue;
        
      $other_parties[] = $user_id;
    }
      
    $num_parties = count($other_parties);
    foreach($other_parties as $index => $user_id)
    {
      $user = socialUser::get_stored_profile_by_id($user_id);
      if((int)$index < ((int)$num_parties - 1))
        $user_list .= __(', ', 'social');
      else
        $user_list .= __(' and ', 'social');
      
      $user_list .= $user->get_profile_link();
    }
    
    return sprintf(__("Between %s", 'social'), $user_list);
  }

  function get_pre_populate_tokens()
  {
    global $social_user, $social_friend;

    if(isset($_POST['social_message_recipients']) and !empty($_POST['social_message_recipients']))
    {
      $recipients = explode(',',preg_replace('#,\s*$#','',$_POST['social_message_recipients']));

      // Assume the message went through if we have a message body
      if(isset($_POST['social_message_body']) and !empty($_POST['social_message_body']))
        return '';
    }
    else if(isset($_GET['u']) and !empty($_GET['u']))
      $recipients = explode(',',$_GET['u']);
    else
      return '';
    
    $where_clause = "status='verified' AND friend_id" . ((count($recipients) > 1)?' IN ('.implode(',',$recipients).')':'='.$recipients[0]);

    $friends_array = $social_friend->get_all_by_user_id( $social_user->id, $where_clause );
    $fmt_friends_array = array();
    foreach($friends_array as $friend)
    {
      $avatar = get_avatar($friend->ID, 20);
      $screenname = $friend->user_login;
      
      $fmt_friends_array[] = array("id" => $friend->ID, "name" => "<span class='social_friend_dropdown_item'>{$avatar}&nbsp;<span class='social_friend_dropdown_text'>{$screenname}</span></span>");
    }
      
    return ",prePopulate:" . socialAppHelper::json_encode($fmt_friends_array);
  }
}

?>