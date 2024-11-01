<?php
$comment_author = socialUser::get_stored_profile_by_id($comment->author_id);

if($comment_author)
{
?>
  <div id="social-board-comment-<?php echo $comment->id; ?>" class="social-real-board-comment social-board-comments<?php echo $comment_hidden_class; ?>">
    <div class="social-comment-table">
    <div id="post-image1"><?php echo $comment_author->get_avatar(36); ?></div>
        <div>
          <div class="social-board-comment-message">
            <a href="<?php echo $comment_author->get_profile_url(); ?>"><?php echo "{$comment_author->screenname}"; ?></a> <?php socialBoardsHelper::display_message('social-board-comment-message-'.$comment->id, $comment->message, false); ?><br/>
            <span class="social-board-post-second-row"><span class="social-time-ago"><?php echo $social_app_helper->time_ago($comment->created_at_ts); ?></span><?php
            if($social_user and (($social_user->id == $board_post->owner_id) or ($social_user->id == $comment->author_id) or current_user_can('level_10')))
            {
              ?> - <a href="javascript:social_delete_board_comment( '<?php echo social_SCRIPT_URL; ?>', <?php echo $comment->id; ?>, '<?php echo (($public)?'activity':'boards'); ?>' )"><?php _e('Delete', 'social'); ?></a><?php
            }
            ?>
            </span>
          </div>
       </div>
    </div>
  </div>
<?php
}
?>
