<div id="social-comment-form-wrap-<?php echo $board_post->id; ?>">
  <?php
  if($show_fake_form)
  {
    if(socialUser::is_logged_in_and_visible() and 
        ( $social_friend->is_friend( $board_post->owner_id, $author_id ) or
          $social_friend->is_friend( $board_post->author_id, $author_id ) or
          ( $board_post->author_id == $author_id ) or
          ( $board_post->owner_id == $author_id ) ) )
    {
      if(count($board_post->comments) > 0)
      {
        ?>
          <div id="social-fake-board-comment-<?php echo $board_post->id; ?>" class="social-board-comments">
            <a href="javascript:social_toggle_comment_form('<?php echo $board_post->id; ?>')"><div class="social-board-fake-input"><?php _e('Write a comment.', 'social'); ?></div></a>
          </div>
        <?php
      }
    }
  }
  ?>
  <div id="social-comment-form-<?php echo $board_post_id; ?>" class="social-board-comments social-growable-hidden">
    <table class="social-comment-table">
     <tr>
       <td valign="top" style="width: 36px;"><?php echo $avatar; ?></a>
       </td>
       <td valign="top" class="social-comment-table-col-2">
         <textarea id="social-board-comment-input-<?php echo $board_post_id; ?>" class="social-board-input social-comment-board-input social-growable social-twolines" style="width:318px !important;"></textarea>
       </td>
     </tr>
    </table>
    <table class="social-comment-table">
      <tr>
        <td width="100%">&nbsp;</td>
        <td width="0%" id="commbutt">
          <input type="submit" class="social-share-button" id="social-comment-button-<?php echo $board_post_id; ?>" onclick="javascript:social_comment_on_post( '<?php echo social_SCRIPT_URL; ?>', <?php echo $author->id; ?>, <?php echo $board_post_id; ?>, document.getElementById('social-board-comment-input-<?php echo $board_post_id; ?>').value, '<?php echo (($public)?'activity':'boards'); ?>' )" name="Comment" value="<?php _e('Comment', 'social'); ?>"/>
        </td>
      </tr>
    </table>
  </div>
</div>
