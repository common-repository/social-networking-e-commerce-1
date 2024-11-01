<p><a href="<?php echo $social_message->get_messages_url(); ?>">&laquo;&nbsp;<?php _e("Back to Messages", 'social'); ?></a></p>
<h3><?php echo socialAppHelper::format_text($thread->subject); ?></h3>
<p><?php echo socialMessagesHelper::format_party_list(explode(',',$thread->parties)); ?></p>

<table cellspacing="0" cellpadding="0" id="social_messages_table">
<?php

if(is_array($messages) and !empty($messages))
{
  foreach($messages as $message)
    $this->display_single_message($message);
}
else
{
?>
  <tr><td><?php _e('No Messages Were Found','social'); ?></td></tr>
<?php
}
?>
</table>
<table width="100%" class="social_form_table">
  <tr>
    <td valign="top" style="width: 60px;"><?php _e('Reply', 'social'); ?>:</td>
    <td valign="top"><textarea name="social_reply" id="social_reply" class="social-profile-edit-field social-growable"></textarea></td>
  </tr>
</table>
<div style="text-align: right;">
  <input type="submit" class="social-share-button" id="social_reply_button" onclick="javascript:social_reply_to_message( <?php echo $thread_id; ?>, document.getElementById('social_reply').value )" name="Reply" value="<?php _e('Reply', 'social'); ?>"/><img id="social_reply_loading" class="social-hidden" src="<?php echo social_IMAGES_URL; ?>/ajax-loader.gif" alt="<?php _e('Loading...', 'social'); ?>" />
</div>
