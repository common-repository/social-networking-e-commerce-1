      <?php if (socialUtils::is_user_logged_in()) {
              global $social_user, $social_friend, $social_message;
              $request_count = $social_friend->get_friend_requests_count( $social_user->id );
              $request_count_str = (($request_count > 0)?" [{$request_count}]":'');
              
              $unread_count = $social_message->get_unread_count();

              $unread_count_str = '';
              if($unread_count)
                $unread_count_str = " [{$unread_count}]";
        ?>
        <ul style="list-style-type: none;" class="social-login-widget-nav">
          <li><a href="<?php echo get_permalink($social_options->activity_page_id); ?>"><?php _e('Activity', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->profile_page_id); ?>"><?php _e('Profile', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->profile_edit_page_id); ?>"><?php _e('Account', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->friends_page_id); ?>"><?php _e('Friends', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->friend_requests_page_id); ?>"><?php _e('Friend Requests', 'social'); ?><?php echo $request_count_str; ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->inbox_page_id); ?>"><?php _e('Inbox', 'social'); ?><?php echo $unread_count_str; ?></a></li>
		  <li><a href="<?php echo get_permalink($social_options->photo_page_id); ?>"><?php _e('Photo', 'social'); ?></a></li>
          <?php do_action('social_login_widget_pages'); ?>
          <?php
            if (!empty($social_options->directory_page_id)) {
              ?>
                <li><a href="<?php echo get_permalink($social_options->directory_page_id); ?>"><?php _e('Find Friends', 'social'); ?></a></li>
              <?php
            }
          ?>
          <li><a href="<?php echo wp_logout_url(get_permalink($social_options->login_page_id)); ?>"><?php _e('Logout', 'social'); ?></a></li>
        </ul>
        <?php
      } else { ?>
        <p><?php printf(__('Login to connect with Others on %s', 'social'), $social_blogname); ?>:</p>
        <form name="loginform" id="loginform" action="<?php echo $login_url; ?>" method="post">
        	<p>
        		<label><strong><?php _e('Username', 'social'); ?></strong><br />
        		<input type="text" name="log" id="user_login" class="input" value="" tabindex="710" style="width: 100%; font-size: 12px; padding: 4px;" /></label>
        		<label><strong><?php _e('Password', 'social'); ?></strong><br />
        		<input type="password" name="pwd" id="user_pass" class="input" value="" tabindex="720" style="width: 100%; line-height: 12px; padding: 4px;" /></label><br/>
        	  <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="730" style="width: 15px;" /> <?php _e('Remember Me', 'social'); ?></label>
        	</p>
        	<p class="submit">
        		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary social-share-button" value="<?php _e('Log In', 'social'); ?>" tabindex="740" />
        		<input type="hidden" name="redirect_to" value="<?php echo get_permalink($social_options->profile_page_id); ?>" />
        		<input type="hidden" name="testcookie" value="1" />
        		<input type="hidden" name="social_process_login_form" value="true" />
        	</p>
        </form>
        <p style="font-size: 10px" class="social-login-actions">
            <a href="<?php echo $signup_url; ?>"><?php _e('Register', 'social'); ?></a>&nbsp;|
        <a href="<?php echo $forgot_password_url; ?>"><?php _e('Lost Password?', 'social'); ?></a>
<?php } ?>
        </p>
      <?php socialAppHelper::powered_by(); ?>
