<tr class="social_message_row">
  <td style="width: 50px;"><?php echo $avatar; ?></td>
  <td style="width: 100%;">
    <p class="social_message_listing"><?php echo $author->get_profile_link(); ?>&nbsp;<span class="social_small_gray"><?php echo date('F j \a\t g:ia', $message->created_at_ts); ?></span></p>
    <p class="social_message_listing"><?php echo $body ?></p>
    <?php do_action( 'social-message-display', $message->id ); ?>
  </td>
</tr>