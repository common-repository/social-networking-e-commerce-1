function social_request_friend( social_url, user_id, friend_id, friend_requested_text )
{
  social_replace_id_with_loading_indicator('friend_request_button-' + friend_id);
  jQuery.ajax( {
    type: "POST",
    url: social_url,
    data: "controller=friends&action=friend_request&user_id=" + user_id + "&friend_id=" + friend_id,
    success: function(html) {
      jQuery("#friend_request_button-" + friend_id ).replaceWith( friend_requested_text );
    }
  });
}

function social_escape(message)
{
  // escape problematic characters -- don't escape utf8 chars
  return message.replace(/&/g,'%26').replace(/=/g,'%3D').replace(/ /g, '%20').replace(/\?/g, '%3F');
}

function social_post_to_board( social_url, owner_id, author_id, message, controller )
{  
  var socialparams = jQuery('#social-board-post-button').attr('socialparams');
  social_replace_id_with_loading_indicator('social-board-post-button');
  
  if(socialparams==undefined)
  {
    socialparams = '';
  }

  jQuery.ajax( {
    type: "POST",
    url: social_url,
    data: "controller=" + controller + "&action=post&owner_id=" + owner_id + "&author_id=" + author_id + "&message=" + social_escape(message) + socialparams,
    success: function(html) {
      jQuery('.social-board').replaceWith('<div class="social-board">'+html+'</div>');
      social_load_growables();
    }
  });
}

function social_clear_status(user_id)
{
  social_replace_id_with_loading_indicator('social-clear-status-button');
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=boards&action=clear_status&u=" + user_id,
    success: function(html) {
      jQuery('.social-profile-status').slideUp();
    }
  });
}

function social_show_older_posts( pagenum, loc, screenname )
{
  social_replace_id_with_loading_indicator('social-older-posts');
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=boards&action=older_posts&mdp=" + pagenum + "&loc=" + loc + "&u=" + screenname,
    success: function(html) {
      jQuery('#social-older-posts').replaceWith(html);
      social_load_growables();
    }
  });
}

function social_comment_on_post( social_url, author_id, board_post_id, message, controller )
{
  social_replace_id_with_loading_indicator('social-comment-button-' + board_post_id);
  jQuery.ajax( {
    type: "POST",
    url: social_url,
    data: "controller=" + controller + "&action=comment&author_id=" + author_id + "&board_post_id=" + board_post_id + "&message=" + social_escape(message),
    success: function(html) {
      jQuery('#social-comment-form-wrap-'+board_post_id).replaceWith(html);
      social_load_growables();
      jQuery("#social-board-comment-list-" + board_post_id).show();
      jQuery("#social-fake-board-comment-" + board_post_id).show();
    }
  });
}

function social_delete_board_post( social_url, board_post_id, controller )
{
  if(confirm("<?php _e('Are you sure you want to delete this post?', 'social'); ?>"))
  {
    jQuery.ajax( {
      type: "POST",
      url: social_url,
      data: "controller=" + controller + "&action=delete_post&board_post_id=" + board_post_id,
      success: function(html) {
        jQuery('#social-board-comment-list-' + board_post_id).slideUp();
        jQuery('.social-board-post-' + board_post_id).slideUp();
      }
    });
  }
}

function social_delete_board_comment( social_url, board_comment_id, controller )
{
  if(confirm("<?php _e('Are you sure you want to delete this comment?', 'social'); ?>"))
  {
    jQuery.ajax( {
      type: "POST",
      url: social_url,
      data: "controller=" + controller + "&action=delete_comment&board_comment_id=" + board_comment_id,
      success: function(html) {
        jQuery('#social-board-comment-' + board_comment_id).slideUp();
      }
    });
  }
}

function social_toggle_comment_form( update_id )
{
  jQuery('#social-board-comment-list-' + update_id).show();
  jQuery('#social-comment-form-' + update_id).toggle();
  jQuery('#social-fake-board-comment-' + update_id).toggle();
  jQuery('#social-board-comment-input-' + update_id).focus();
}

function social_show_board_post_form()
{
  jQuery('#social-fake-board-post-form').toggle();
  jQuery('#social-board-post-form').toggle();
  jQuery('#social-board-post-input').focus();
}

function social_toggle_hidden_comments(board_post_id)
{
  jQuery('.social-hidden-comment-'+board_post_id).show();
  jQuery('#social-show-hidden-comments-'+board_post_id).hide();
}

function social_delete_friend( social_url, user_id, friend_id )
{
  if(confirm("<?php _e('Are you sure you want to delete this friend?', 'social'); ?>"))
  {
    jQuery.ajax( {
      type: "POST",
      url: social_url,
      data: "controller=friends&action=delete_friend&user_id=" + user_id + "&friend_id=" + friend_id,
      success: function(html) {
        jQuery('#social-friend-'+friend_id).slideUp();
      }
    });
  }
}

function social_accept_friend_request( social_url, request_id, requestor_name )
{
  social_replace_id_with_loading_indicator('request-' + request_id);
  jQuery.ajax( {
    type: "POST",
    url: social_url,
    data: "controller=friends&action=accept_friend&request_id=" + request_id,
    success: function(html) {
      jQuery( '#request-' + request_id ).replaceWith( 'You\'re now friends with ' + requestor_name );
    }
  });
}

function social_ignore_friend_request( social_url, request_id )
{
  social_replace_id_with_loading_indicator('request-' + request_id);
  jQuery.ajax( {
    type: "POST",
    url: social_url,
    data: "controller=friends&action=ignore_friend&request_id=" + request_id,
    success: function(html) {
      jQuery( '#request-' + request_id ).slideUp();
    }
  });
}

function social_search_directory( search_query )
{
  social_replace_id_with_loading_indicator('social-profile-results');
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "&controller=profile&action=search&q=" + search_query,
    success: function(html) {
      jQuery( '#social-profile-results' ).replaceWith(html);
      if( search_query != '' )
      {
        jQuery( '.social-search-reset-button' ).show();
      }
      else
      {
        jQuery( '.social-search-reset-button' ).hide();
      }
    }
  });
}

function social_search_friends( search_query, page_params )
{
  social_replace_id_with_loading_indicator('social-friends-directory');
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "&controller=friends&action=search&q=" + search_query + page_params,
    success: function(html) {
      jQuery( '#social-friends-directory' ).replaceWith(html);
    }
  });
}
function social_delete_profile_avatar( social_url, user_id )
{
  if(confirm("<?php _e('Are you sure you want to delete your avatar?', 'social'); ?>"))
  {
    jQuery.ajax( {
      type: "POST",
      url: social_url,
      data: "controller=profile&action=delete_avatar&user_id=" + user_id,
      success: function(html) {
        jQuery('#social-avatar-edit-display').replaceWith(html);
      }
    });
  }
}

function social_toggle_two_ids( first_id, second_id )
{
  jQuery(first_id).toggle();
  jQuery(second_id).toggle();
}

function social_show_search_form()
{
  jQuery('#social-fake-search-form').hide();
  jQuery('#social-search-form').show();
  jQuery('#social-search-input').focus();
}

function social_remove_tag( html_tag )
{
  jQuery( html_tag ).remove();
}

function social_add_default_user()
{
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=options&action=add_default_user",
    success: function(html) {
      jQuery('.social-default-friends-table').append(html);
    }
  });
}

function social_replace_id_with_loading_indicator(tagname)
{
  jQuery('#'+tagname).replaceWith('<img id="' + tagname + '" src="<?php echo social_IMAGES_URL; ?>/ajax-loader.gif" alt="<?php _e('Loading...', 'social'); ?>" />');
}

function social_replace_class_with_loading_indicator(tagname)
{
  jQuery('.'+tagname).replaceWith('<img class="' + tagname + '" src="<?php echo social_IMAGES_URL; ?>/ajax-loader.gif" alt="<?php _e('Loading...', 'social'); ?>" />');
}

function social_load_growables()
{
  jQuery(".social-growable-hidden").show();
  jQuery(".social-growable").elastic();
  jQuery(".social-growable-hidden").hide();
}

function social_show_tooltip( tooltip_content, tooltip_element )
{
  jQuery(tooltip_element).qtip({
    content: tooltip_content
  });
}

function social_set_active_tab( tab )
{
  jQuery('#social-profile-tab-control li').removeClass('social-active-profile-tab');
  jQuery('#social-' + tab + '-tab-button').addClass('social-active-profile-tab');
  jQuery('.social-profile-tab').hide();
  jQuery('.social-photo-tab').hide();
  jQuery('#social-' + tab + '-tab').show();
}

function social_mailer_options()
{
  if( jQuery('#social_mailer-type').val() == 'smtp' )
  {
    jQuery('#social-sendmail-form').slideUp( 'normal', function() {
      jQuery('#social-smtp-form').slideDown();
    } );
  }
  else if( jQuery('#social_mailer-type').val() == 'sendmail' )
  {
    jQuery('#social-smtp-form').slideUp( 'normal', function() {
      jQuery('#social-sendmail-form').slideDown();
    } );
  }
  else
  {
    jQuery('#social-sendmail-form').slideUp();
    jQuery('#social-smtp-form').slideUp();
  }
}

function social_center_image( curr_obj )
{
  var obj_height = jQuery( curr_obj ).height();
  var img_height = jQuery( curr_obj ).find('img').height();
  
  var img_tb_margin = (obj_height - img_height) / 2;
  
  //alert( "obj height: " + obj_height + " img height: " + img_height + " img_tb_margin " + img_tb_margin );
  
  jQuery( curr_obj ).find('img').css('margin-top', img_tb_margin);
  jQuery( curr_obj ).find('img').css('margin-bottom', img_tb_margin);
}

function social_add_field( field_index )
{
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=options&action=add_custom_field&index=" + field_index,
    success: function(html) {
      jQuery('#social-add-button').replaceWith(html);
    }
  });
}

function social_add_field_option( field_index, option_index )
{
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=options&action=add_custom_field_option&field_index=" + field_index + "&option_index=" + option_index,
    success: function(html) {
      jQuery('#social-add-option-button-' + field_index).replaceWith(html);
    }
  });
}

function social_show_field_options( field_index, type )
{
  if(type == 'dropdown')
  {
    jQuery('#social_field_options_wrapper_' + field_index).show();
  }
  else
  {
    jQuery('#social_field_options_wrapper_' + field_index).hide();
  }
}

function social_reply_to_message( thread_id, message )
{
  jQuery('#social_reply_button').toggle();
  jQuery('#social_reply_loading').toggle();
  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=messages&action=social_process_reply_form&social_thread_id=" + thread_id + "&social_reply=" + social_escape(message),
    success: function(html) {
      jQuery('#social_messages_table').append(html);
      jQuery('#social_reply').val(''); // clear the textarea
      jQuery('#social_reply').elastic();
      jQuery('#social_reply_button').toggle();
      jQuery('#social_reply_loading').toggle();
    }
  });
}

function social_delete_thread( thread_id )
{
  if(confirm('<?php _e('Are you sure you want to delete this message?', 'social'); ?>'))
  {
    jQuery.ajax( {
      type: "POST",
      url: '<?php echo social_SCRIPT_URL; ?>',
      data: "controller=messages&action=delete_thread&t=" + thread_id,
      success: function(html) {
        jQuery('#social_thread_' + thread_id).fadeOut('slow');
      }
    });
  }
}

function social_bulk_action()
{
  var action = jQuery('#social_message_actions').val();
  
  if(action == 'delete_threads')
  {
    if(!confirm('<?php _e('Are you sure you want to delete these messages?', 'social'); ?>'))
    {
      return;
    }
  }

  var thread_ids = jQuery(".social_message_checkbox:checked").map(function(){
                     return jQuery(this).val();
                   }).get();

  jQuery.ajax( {
    type: "POST",
    url: '<?php echo social_SCRIPT_URL; ?>',
    data: "controller=messages&action=" + action + "&ts=" + thread_ids.join(","),
    success: function(html) {
      if(action=='delete_threads')
      {
        jQuery('.social_message_checkbox:checked').parent().parent().fadeOut('slow');
      }
      else if(action=='mark_unread')
      {
        jQuery('.social_message_checkbox:checked').parent().parent().children().css('background-color','lightgray');
      }
      else if(action=='mark_read')
      {
        jQuery('.social_message_checkbox:checked').parent().parent().children().css('background-color','white');
      }
      
      jQuery('.social_message_checkbox:checked').removeAttr('checked');
    }
  });
}

function social_toggle_message_composer()
{
  jQuery('#social_message_composer').slideToggle();
}

jQuery(document).ready(function() {
  social_load_growables();

<?php // translators: Please don't translate this string ... You can see what this date format means here http://docs.jquery.com/UI/Datepicker/formatDate ... try to select a relevant format for those using social in your language.
  $date_format = __('MM d, yy', 'social');
  
  // translators: Please don't translate this string ... This option indicates whether the first day on the calendar is a Sunday or Monday -- Sunday is represented as a '0' and Monday is represented as a '1'
  $first_day = __('0', 'social');
  
  $month_names = "['" . __('January', 'social') . "','" . __('February', 'social') . "','" . __('March', 'social') . "','" . __('April', 'social') . "','" . __('May', 'social') . "','" . __('June', 'social') . "','" . __('July', 'social') . "','" . __('August', 'social') . "','" . __('September', 'social') . "','" . __('October', 'social') . "','" . __('November', 'social') . "','" . __('December', 'social') . "']";
  
  // translators: This is the short version of the month name...
  $short_month_names = "['" . __('Jan', 'social') . "','" . __('Feb', 'social') . "','" . __('Mar', 'social') . "','" . __('Apr', 'social') . "','" . __('May', 'social') . "','" . __('Jun', 'social') . "','" . __('Jul', 'social') . "','" . __('Aug', 'social') . "','" . __('Sept', 'social') . "','" . __('Oct', 'social') . "','" . __('Nov', 'social') . "','" . __('Dec', 'social') . "']";
  
  $day_names = "['" . __('Sunday', 'social') . "','" . __('Monday', 'social') . "','" . __('Tuesday', 'social') . "','" . __('Wednesday', 'social') . "','" . __('Thursday', 'social') . "','" . __('Friday', 'social') . "','" . __('Saturday', 'social') . "']";
  
  // translators: This is the short version of the day name...
  $short_day_names = "['" . __('Sun', 'social') . "','" . __('Mon', 'social') . "','" . __('Tue', 'social') . "','" . __('Wed', 'social') . "','" . __('Thu', 'social') . "','" . __('Fri', 'social') . "','" . __('Sat', 'social') . "']";
  
  // translators: This is the minimized short version of the day name
  $min_day_names = "['" . __('Su', 'social') . "','" . __('Mo', 'social') . "','" . __('Tu', 'social') . "','" . __('We', 'social') . "','" . __('Th', 'social') . "','" . __('Fr', 'social') . "','" . __('Sa', 'social') . "']";
  
  // translators: Please don't translate this string ... Just set it to 'isRTL: true' if your language is drawn from Right-to-Left
  $rtl = __('isRTL: false', 'social');
  
  $datepicker_options = "dateFormat: '{$date_format}', changeMonth: true, changeYear: true, firstDay: {$first_day}, monthNames: {$month_names}, monthNamesShort: {$short_month_names}, dayNames: {$day_names}, dayNamesShort: {$short_day_names}, dayNamesMin: {$min_day_names}, {$rtl}, minDate: '-100y', maxDate: '+5y', yearRange: '-100y:+5y'";
?>

  jQuery(".social-datepicker").datepicker({ <?php echo $datepicker_options; ?> });
  
  // By suppling no content attribute, the library uses each elements title attribute by default

  jQuery('.social-grid-cell a').each(function()
  {
    jQuery(this).qtip({
      content: {
        text: jQuery(this).parent().attr('rel')
      },
      position: {
        corner: {
          target: 'bottomMiddle',
          tooltip: 'topMiddle'
        }
      },
      style: {
        border: {
            width: 5,
            radius: 5
        },
        padding: 5, 
        textAlign: 'center',
        tip: true
      }
    });
  });
});
