<?php

class socialHelpController
{
  function socialHelpController()
  {
    //add_action('install_plugins_table_header', array($this, 'get_started_message'));
    add_action('after_plugin_row', array(&$this, 'get_started_message'));
    add_action('admin_notices', array(&$this, 'get_started_headline_message'));
  }

  function get_started_message( $plugin )
  {
    global $social_options;
    
    if( $plugin == 'social/social.php' and
        !$social_options->setup_complete )
    {
  ?>
    <td colspan="5" class="plugin-update">&nbsp;&nbsp;<?php printf(__('social must be configured. Go to the %1$sadmin page%2$s to setup your new social network!', 'social'), '<a href="?page=social/social.php">', '</a>'); ?></td>
  <?php
    }
  }
  
  function get_started_headline_message()
  {
    global $social_options;
    
    if( !$social_options->setup_complete )
      require(social_VIEWS_PATH . '/shared/must_configure.php');
  }
}
?>
