<span id="social-avatar-edit-display"><?php echo $avatar; ?>
<?php if(!empty($social_user->avatar)) { ?>
  <a href="javascript:social_delete_profile_avatar( '<?php echo social_SCRIPT_URL ?>', <?php echo $social_user->id; ?> )">[<?php _e('delete', 'social'); ?>]</a>
<?php } ?>
</span>
