    <tr id="social-default-friend-field-<?php echo $default_friend; ?>" class="form-field">
      <td><?php _e('Default Friend', 'social'); ?>: </td>
      <td>
        <?php socialOptionsHelper::users_dropdown($social_options->default_friends_str .'[]', $default_friend); ?>
      </td>
      <td>
        <a href="javascript:social_remove_tag('#social-default-friend-field-<?php echo $default_friend; ?>');"><img src="<?php echo social_IMAGES_URL . '/remove.png'; ?>" /></a>
      </td>
    </tr>