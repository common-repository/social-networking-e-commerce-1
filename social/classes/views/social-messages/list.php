<?php 
global $current_user;
global $social_options;
      get_currentuserinfo();
	  if($current_user->display_name == $_GET['u'] || $_GET['u']=='')
      {
	  $avatar = socialUtils::get_avatar( $current_user->ID, $size );
	  }
	  else
      {
	  $avatar = socialUtils::get_avatar( $_GET['u'], $size );
	  }
	  ?>
		  <div id="mainsidebar"> 
	<div id="avatar1">
            <a href="?page_id=<?php echo $social_options->profile_page_id; ?>"><?php echo $avatar; ?></a>
        </div>
<?php require( social_VIEWS_PATH . '/social-profiles/social_sidebar1.php' ) ?>
 <div id="rightsidebar">
		<div id="menuheader">
	  <ul>
	  <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Home', 'social'); ?></a> | </li>
     <li><a href="<?php echo get_permalink($social_options->profile_edit_page_id); ?>"><?php _e('Setting', 'social'); ?></a> | </li>
	 <li><a href="<?php echo wp_logout_url(get_permalink($social_options->login_page_id)); ?>"><?php _e('Logout', 'social'); ?></a></li>
	 </ul>
	</div>
	 <div id="bodycontent">
<div id="tablebordermsg">
       
          <div id="rightsize"><p><a href="javascript:social_toggle_message_composer()" id="social_message_composer_button"><?php _e('Compose a Message', 'social'); ?></a>
<?php $this->display_composer(); ?>
</p>
<div id="combomsg">
  <select id="social_message_actions" name="social_message_actions">
    <option>&nbsp;</option>
    <option value="mark_read"><?php _e('Mark as Read', 'social'); ?></option>
    <option value="mark_unread"><?php _e('Mark as Unread', 'social'); ?></option>
    <option value="delete_threads"><?php _e('Delete', 'social'); ?></option>
  </select>
  </div>
  <div id="applymsg">
  <input type="submit" class="social-share-button" style="height: 24px;" value="<?php _e('Apply', 'social'); ?>" name="social_message_action_button" id="social_message_action_button" onclick="javascript:social_bulk_action()" />
</div>
<?php
if($prev_page > 0)
{
  ?>
    <div id="social_prev_page" style="display: inline;"><a href="<?php echo "{$permalink}{$param_char}mgp={$prev_page}"; ?>">&laquo; <?php _e('Previous Page', 'social'); ?></a></div>
  <?php
}

if($next_page > 0)
{
  ?>
    <div id="social_next_page" style="float: right;"><a href="<?php echo "{$permalink}{$param_char}mgp={$next_page}"; ?>"><?php _e('Next Page', 'social'); ?> &raquo;</a></div>
  <?php
}
?>
<table cellspacing="0" cellpadding="0" class="social_messages_table">
<?php
if(is_array($messages) and !empty($messages))
{
  foreach($messages as $message)
  {
    $author = socialUser::get_stored_profile_by_id($message['latest']->author_id);
    $avatar = $author->get_avatar(48);
  
    $body = strip_tags(socialBoardsHelper::format_message($message['latest']->body, true));

    $body_excerpt = substr($body, 0, 50);
    
    $body_excerpt .= ((strcmp($body,$body_excerpt) > 0)?"...":'');

  ?>
    <tr id="social_thread_<?php echo $message['thread']->id; ?>" class="social_message_row<?php echo (($message['latest']->unread)?" social_message_unread":''); ?>">
      <td style="width: 30px; padding-right: 0px; margin-right: 0px;"><input type="checkbox" name="social_message_checkbox" class="social_message_checkbox" value="<?php echo $message['thread']->id; ?>" /></td>
      <td style="width: 50px;"><?php echo $avatar; ?></td>
      <td style="width: 120px;"><p class="social_message_listing"><a href="<?php echo $author->get_profile_url(); ?>"><?php echo "{$author->screenname}"; ?></a><br/><span class="social_small_gray"><?php echo date('F j \a\t g:ia', $message['latest']->created_at_ts); ?></span></p>
      </td>
      <td><p class="social_message_listing"><strong><a href="<?php echo $social_message->get_message_url( $message['thread']->id ); ?>"><?php echo socialAppHelper::format_text($message['thread']->subject); ?></a></strong><br/><span class="social_small_gray"><?php echo $body_excerpt; ?></span></p>
      </td>
      <td style="width: 20px;" id="crosssize">
        <a href="javascript:social_delete_thread(<?php echo $message['thread']->id; ?>);"><img src="<?php echo social_IMAGES_URL . '/remove.png'; ?>" /></a>
      </td>
    </tr>
  <?php
  }
}
else
{
?>
  <tr><td><?php _e('No Messages Were Found','social'); ?></td></tr>
<?php
}
?>
</table>
<?php
if($prev_page > 0)
{
  ?>
    <div id="social_prev_page" style="display: inline;"><a href="<?php echo "{$permalink}{$param_char}mgp={$prev_page}"; ?>">&laquo; <?php _e('Previous Page', 'social'); ?></a></div>
  <?php
}

if($next_page > 0)
{
  ?>
    <div id="social_next_page" style="float: right;"><a href="<?php echo "{$permalink}{$param_char}mgp={$next_page}"; ?>"><?php _e('Next Page', 'social'); ?> &raquo;</a></div>
  <?php
} 
?>
</td>
</tr>
</table>
</td>
</table>
</div>
</div>
</div>
</div>