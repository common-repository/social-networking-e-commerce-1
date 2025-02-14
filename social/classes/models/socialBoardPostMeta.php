<?php
class socialBoardPostMeta
{
  function get($board_post_id,$meta_key,$return_var=false)
  {
    global $wpdb, $social_db;
    $query_str = "SELECT meta_value FROM {$social_db->board_post_metas} WHERE meta_key=%s and board_post_id=%d";
    $query = $wpdb->prepare($query_str,$meta_key,$board_post_id);
    
    if($return_var)
      return $wpdb->get_var("{$query} LIMIT 1");
    else
      return $wpdb->get_col($query, 0);
  }

  function add($board_post_id, $meta_key, $meta_value)
  {
    global $wpdb, $social_db;

    $query_str = "INSERT INTO {$social_db->board_post_metas} " .
                 '(meta_key,meta_value,board_post_id,created_at) VALUES (%s,%s,%d,NOW())';
    $query = $wpdb->prepare($query_str, $meta_key, $meta_value, $board_post_id);
    return $wpdb->query($query);
  }

  function update($board_post_id, $meta_key, $meta_values)
  {
    global $wpdb, $social_db;
    socialBoardPostMeta::delete($board_post_id, $meta_key);

    if(!is_array($meta_values))
      $meta_values = array($meta_values);

    $status = false;
    foreach($meta_values as $meta_value)
      $status = socialBoardPostMeta::add($board_post_id, $meta_key, $meta_value);

    return $status;
  }

  function delete($board_post_id, $meta_key)
  {
    global $wpdb, $social_db;

    $query_str = "DELETE FROM {$social_db->board_post_metas} " .
                 "WHERE meta_key=%s AND board_post_id=%d";
    $query = $wpdb->prepare($query_str, $meta_key, $board_post_id);
    return $wpdb->query($query);
  }
  
  function delete_all_by_board_post_id($board_post_id)
  {
    global $social_db;
    $args = compact( 'board_post_id' );
    $social_db->delete_records( $social_db->board_post_metas, $args );
  }
}
?>