<?php 
  global $social_user;
  $status_message = $user->get_status();
  
  if(!empty($status_message) and $status_message) {
?>
<div class="social-profile-status">
  <?php socialBoardsHelper::display_message( 'social-profile-status-message', $status_message, $false ); ?>
  <span class="social-time-ago social-board-post-second-row"><?php echo $social_app_helper->time_ago($user->get_status_time_ts()); ?>
  <?php if(socialUser::is_logged_in_and_visible() and $user->id == $social_user->id) { ?>
    - <a href="javascript:social_clear_status(<?php echo $social_user->id; ?>);" id="social-clear-status-button"><?php _e("Clear", 'social'); ?></a>
  <?php } ?>
  </span>
</div>
<?php } ?>