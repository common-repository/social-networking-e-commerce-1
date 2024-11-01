<?php
class socialBoardApi
{
  /** Before your plugin can add activity, it must register itself as a source by using the social-activity-types filter
    * to add a code (the key of your array entry), name and icon url. Here's an example from the socialBoardsController class:
    *
    * function socialBoardsController()
    * {
    *   add_filter('social-activity-types', array( $this, 'add_activity_types' ));
    * }
    * 
    * function add_activity_types($activity_types)
    * {
    *   $activity_types['board_posts'] = array( 'name' => 'Board Posts',
    *                                             'message' => '%1$s commented on %2$s\'s post';
    *                                             'icon' => social_URL . '/images/board_post.png' );
    *   $activity_types['board_comments'] = array( 'name' => 'Board Comments',
    *                                                'message' => '{$owner->screenname} commented on {$author_profile_link}\'s post',
    *                                                'icon' => social_URL . '/images/board_comment.png' );
    *   return $activity_types;
    * }
    *
    * Also, note that in the message variable above you can actually specify placeholders in normal php syntax for the
    * following variables:
    * 
    * $owner_profile_url (the url of the owner's profile)
    * $author_profile_url (the url of the author's profile)
    * $author_profile_link (a full hyperlink to the author's profile, using his or her name as the anchor text)
    * $owner_profile_link (a full hyperlink to the owner's profile, using his or her name as the anchor text)
    * 
    * $author->avatar (url to the avatar)
    * $author->full_name
    * $author->first_name
    * $author->last_name
    * $author->screenname
    * $author->email
    * $author->url (website url)
    * $author->bio
    * $author->sex
    * $author->location
    * $author->his_her
    * $author->him_her
    * $author->he_she
    * 
    * $owner->avatar (url to the avatar)
    * $owner->full_name
    * $owner->first_name
    * $owner->last_name
    * $owner->screenname
    * $owner->email
    * $owner->url (website url)
    * $owner->bio
    * $owner->sex
    * $owner->location
    * $owner->his_her
    * $owner->him_her
    * $owner->he_she
    * 
    * $vars['whatever']
    */
  function add_activity($owner_login, $author_login, $message_type, $vars='', $visibility='personal')
  {
    $social_board_post =& socialBoardPost::get_stored_object();
    $social_board_post->add_activity_by_screenname( $owner_login, $author_login, $message_type, $vars, $visibility );
  }
}
?>
