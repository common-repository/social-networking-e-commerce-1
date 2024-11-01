<?php
$owner_profile_url   = $owner->get_profile_url();
$author_profile_url  = $author->get_profile_url();
$author_profile_link = "<a href=\"{$author_profile_url}\">{$author->screenname}</a>";
$owner_profile_link  = "<a href=\"{$owner_profile_url}\">{$owner->screenname}</a>";

$posted_on = '';
if($public)
  $posted_on = sprintf(__("Posted on %s's Board","social"),$owner_profile_link) . " ";

if(isset($_GET['mbpost']))
{
  $owner_avatar = $owner->get_avatar(75);
?>
    <div style="float: left;"><?php echo $owner_avatar; ?></div>
    <p class="social-friend-list-profile-text" style="height: 75px;"><?php printf(__("Post from %s's Board:", 'social'), $owner_profile_link); ?></p>
    <hr style="margin-top: 10px;"/>
<?php
}

if($board_post->type == 'post')
{
?>
  <div class="social-board-post-<?php echo $board_post->id; ?> social-board-post social-board-post-post">
      <div id="post-image"><?php echo $author->get_avatar(48); ?></div>
      <div class="social-valign-top">
        <div class="social-board-post-message">
          <?php echo $author_profile_link; ?> <?php socialBoardsHelper::display_message('social-board-post-message-'.$board_post->id, $board_post->message); ?><br/><?php do_action( 'social-board-post-message-display', $board_post->id ); ?>
          <span class="social-board-post-second-row">
		  <ul>
          <?php echo $posted_on; ?><span class="social-time-ago"><?php echo $social_app_helper->time_ago($board_post->created_at_ts); ?></span><?php 
          if(socialUser::is_logged_in_and_visible() and ( $social_friend->is_friend( $board_post->owner_id, $author_id ) or $social_friend->is_self( $board_post->owner_id, $author_id ) ) )
          {
            ?> <li> <a href="javascript:social_toggle_comment_form('<?php echo $board_post->id; ?>')"><?php _e('Comment', 'social'); ?></a> </li><?php
          }
         if(((($social_user->id == $board_post->owner_id) or ($social_user->id == $board_post->author_id)) and !$public) or current_user_can('level_10'))
          {
            ?> <li> <a href="javascript:social_delete_board_post( '<?php echo social_SCRIPT_URL; ?>', <?php echo $board_post->id; ?>, '<?php echo (($public)?'activity':'boards'); ?>' )"><?php _e('Delete', 'social'); ?></a></li><?php
          }
          ?></ul></span>
        </div>
      </div>
  </div>
  <?php do_action( 'social-board-post-display', $board_post->id ); ?>
<?php
}
else if($board_post->type == 'activity')
{ 
  
  $message_str = $social_options->activity_types[$board_post->source]['message']; //$board_post->message;
  
  $message_parts  = explode('|', $message_str);
  $message_format = preg_replace("#'#", "\\'", $message_parts[0]);
  $message_vars   = ((empty($message_parts[1]))?'':', '.$message_parts[1]);
  
  $message = 'sprintf(\''.$message_format.'\'' . $message_vars . ')';
  
  $vars = unserialize($board_post->message); // in an activity call the message is the serialized vars array
  
  $eval_code = '$message = '.$message.';';

  // Replace fields
  eval( $eval_code );

?>
  <div class="social-board-post-<?php echo $board_post->id; ?> social-board-post social-board-activity">
    <img class="social-profile-image social-board-activity-image" src="<?php echo $social_options->activity_types[$board_post->source]['icon']; ?>" />&nbsp;<?php echo stripslashes($message); ?> - <span class="social-board-post-second-row"><span class="social-time-ago"><?php echo $social_app_helper->time_ago($board_post->created_at_ts); ?></span><?php
    if(socialUser::is_logged_in_and_visible() and ( $social_friend->is_friend( $board_post->owner_id, $author_id ) or $social_friend->is_self( $board_post->owner_id, $author_id ) ) )
    {
    ?> - <a href="javascript:social_toggle_comment_form('<?php echo $board_post->id; ?>')"><?php _e('Comment', 'social'); ?></a><?php
    }
    ?> - <a href="<?php echo socialBoardsHelper::board_post_url($board_post->id); ?>"><?php _e('View', 'social'); ?></a><?php
    if((($social_user->id == $board_post->owner_id) and !$public) or current_user_can('level_10'))
    {
      ?> - <a href="javascript:social_delete_board_post( '<?php echo social_SCRIPT_URL; ?>', <?php echo $board_post->id; ?>, '<?php echo (($public)?'activity':'boards'); ?>' )"><?php _e('Delete', 'social'); ?></a><?php
    }
    ?></span>
  </div>
<?php
}

?>
    <div id="social-board-comment-list-<?php echo $board_post->id; ?>" class="social-board-comment-list<?php echo ((count($board_post->comments) >= 1)?"":" social-growable-hidden"); ?>">
    <?php
      if(count($board_post->comments) > 3)
      {
        ?>
          <a href="javascript:social_toggle_hidden_comments('<?php echo $board_post->id; ?>')">
            <div id="social-show-hidden-comments-<?php echo $board_post->id; ?>" class="social-board-comments">&nbsp;<?php printf(__('Show all %d comments', 'social'), count($board_post->comments)); ?></div>
          </a>
        <?php
      }

      foreach ($board_post->comments as $index => $comment)
      {
        $comment_hidden_class = (($index < (count($board_post->comments) - 3))?" social-hidden social-hidden-comment-{$board_post->id}":'');
        $social_boards_controller->display_comment($comment, $public, $comment_hidden_class);
      }
      
      $this->display_comment_form( $author_id, $board_post, $public, true );
      ?>
    </div>
