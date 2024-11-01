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
          <li><a href="<?php echo get_permalink($social_options->product_page_id); ?>"><?php _e('Product Page', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->checkout_page_id); ?>"><?php _e('Checkout', 'social'); ?></a></li>
          <li><a href="<?php echo get_permalink($social_options->purchase_history_page_id); ?>"><?php _e('Purchse History', 'social'); ?></a></li>
          <?php do_action('social_login_widget_pages'); ?>
        </ul>
        <?php
      } ?>
        </p>
      <?php socialAppHelper::powered_by(); ?>
