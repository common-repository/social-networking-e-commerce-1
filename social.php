<?php
/*
Plugin Name: social
Plugin URI: http://blairwilliams.com/social
Description: The simplest way to turn your standard WordPress website with a standard WordPress theme into a Social Network.
Version: 0.0.32
Author: Caseproof
Author URI: http://caseproof.com
Text Domain: social
Copyright: 2009-2010, Caseproof, LLC

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('social_PLUGIN_NAME',"social");
$social_script_url = get_option('home') . '/index.php?plugin=' . social_PLUGIN_NAME;
define('social_PATH',WP_PLUGIN_DIR.'/'.social_PLUGIN_NAME);
define('social_IMAGES_PATH',social_PATH.'/images');
define('social_CSS_PATH',social_PATH.'/css');
define('social_JS_PATH',social_PATH.'/js');
define('social_INCLUDES_PATH',social_PATH.'/includes');
define('social_I18N_PATH',social_PATH.'/i18n');
define('social_APIS_PATH',social_PATH.'/classes/apis');
define('social_MODELS_PATH',social_PATH.'/classes/models');
define('social_CONTROLLERS_PATH',social_PATH.'/classes/controllers');
define('social_VIEWS_PATH',social_PATH.'/classes/views');
define('social_WIDGETS_PATH',social_PATH.'/classes/widgets');
define('social_HELPERS_PATH',social_PATH.'/classes/helpers');
define('social_URL',plugins_url($path = '/'.social_PLUGIN_NAME));
define('social_IMAGES_URL',social_URL.'/images');
define('social_CSS_URL',social_URL.'/css');
define('social_JS_URL',social_URL.'/js');
define('social_INCLUDES_URL',social_URL.'/includes');
define('social_SCRIPT_URL',$social_script_url);


// Gotta load the language before everything else
require_once(social_CONTROLLERS_PATH . "/socialAppController.php");
socialAppController::load_language();

require_once(social_MODELS_PATH.'/socialOptions.php');

// For IIS compatibility
if (!function_exists('fnmatch'))
{
  function fnmatch($pattern, $string)
  {
    return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
  }
}

// More Global variables
global $social_blogurl;
global $social_siteurl;
global $social_blogname;
global $social_blogdescription;

$social_blogurl         = ((get_option('home'))?get_option('home'):get_option('siteurl'));
$social_siteurl         = get_option('siteurl');
$social_blogname        = get_option('blogname');
$social_blogdescription = get_option('blogdescription');

/***** SETUP OPTIONS OBJECT *****/
global $social_options;

$social_options = get_option('social_options');

// If unserializing didn't work
if(!$social_options)
{
  $social_options = new socialOptions();

  delete_option('social_options');
  add_option('social_options',$social_options);
}
else
  $social_options->set_default_options(); // Sets defaults for unset options

// Instansiate Models
require_once(social_MODELS_PATH . "/socialDb.php");
require_once(social_MODELS_PATH . "/socialUtils.php");
require_once(social_MODELS_PATH . "/socialUser.php");
require_once(social_MODELS_PATH . "/socialFriend.php");
require_once(social_MODELS_PATH . "/socialBoardComment.php");
require_once(social_MODELS_PATH . "/socialBoardPost.php");
require_once(social_MODELS_PATH . "/socialBoardPostMeta.php");
require_once(social_MODELS_PATH . "/socialNotification.php");
require_once(social_MODELS_PATH . "/socialCustomField.php");
require_once(social_MODELS_PATH . "/socialMessage.php");
require_once(social_MODELS_PATH . "/socialMessageMeta.php");

global $social_db;
global $social_user;
global $social_friend;
global $social_board_comment;
global $social_custom_field;
global $social_message;

$social_db            = new socialDb();
$social_user          = socialUser::get_stored_profile();
$social_friend        = new socialFriend();
$social_board_comment = new socialBoardComment();
$social_notification  = new socialNotification();
$social_custom_field  = new socialCustomField();
$social_message       = new socialMessage();

// Instansiate Controllers
require_once(social_CONTROLLERS_PATH . "/socialOptionsController.php");
require_once(social_CONTROLLERS_PATH . "/socialProfilesController.php");
require_once(social_CONTROLLERS_PATH . "/socialFriendsController.php");
require_once(social_CONTROLLERS_PATH . "/socialUsersController.php");
require_once(social_CONTROLLERS_PATH . "/socialBoardsController.php");
require_once(social_CONTROLLERS_PATH . "/socialHelpController.php");
require_once(social_CONTROLLERS_PATH . "/socialShortcodesController.php");
require_once(social_CONTROLLERS_PATH . "/socialCaptchaController.php");
require_once(social_CONTROLLERS_PATH . "/socialCustomFieldsController.php");
require_once(social_CONTROLLERS_PATH . "/socialMessagesController.php");
require_once(social_CONTROLLERS_PATH . "/socialStoreController.php");
require_once(social_CONTROLLERS_PATH . "/socialPhotosController.php");


global $social_app_controller;
global $social_options_controller;
global $social_profiles_controller;
global $social_friends_controller;
global $social_users_controller;
global $social_boards_controller;
global $social_help_controller;
global $social_shortcodes_controller;
global $social_captcha_controller;
global $social_custom_fields_controller;
global $social_messages_controller;
global $social_store_controller;
global $social_photos_controller;

$social_app_controller           = new socialAppController();
$social_options_controller       = new socialOptionsController();
$social_profiles_controller      = new socialProfilesController();
$social_friends_controller       = new socialFriendsController();
$social_users_controller         = new socialUsersController();
$social_boards_controller        = new socialBoardsController();
$social_help_controller          = new socialHelpController();
$social_shortcodes_controller    = new socialShortcodesController();
$social_captcha_controller       = new socialCaptchaController();
$social_custom_fields_controller = new socialCustomFieldsController();
$social_messages_controller      = new socialMessagesController();
$social_store_controller      = new socialStoreController();
$social_photos_controller      = new socialPhotosController();

// Instansiate Helpers
require_once(social_HELPERS_PATH. "/socialAppHelper.php");
require_once(social_HELPERS_PATH. "/socialOptionsHelper.php");
require_once(social_HELPERS_PATH. "/socialProfileHelper.php");
require_once(social_HELPERS_PATH. "/socialBoardsHelper.php");
require_once(social_HELPERS_PATH. "/socialCustomFieldsHelper.php");
require_once(social_HELPERS_PATH. "/socialMessagesHelper.php");

global $social_app_helper;

$social_app_helper = new socialAppHelper();

$social_options->set_activity_types();
$social_options->set_notification_types();
$social_options->set_default_friends();
$social_app_controller->setup_menus();

// Include Widgets
require_once(social_WIDGETS_PATH . "/socialLoginWidget.php");
require_once(social_WIDGETS_PATH . "/socialUsersWidget.php");
require_once(social_WIDGETS_PATH . "/socialCheckoutWidget.php");
require_once(social_WIDGETS_PATH . "/socialShoppingWidget.php");
require_once(social_WIDGETS_PATH . "/socialCategoryWidget.php");


// Register Widgets
if(class_exists('WP_Widget'))
{
  add_action('widgets_init', create_function('', 'return register_widget("socialLoginWidget");'));
  add_action('widgets_init', create_function('', 'return register_widget("socialUsersWidget");'));
  add_action('widgets_init', create_function('', 'return register_widget("socialCheckoutWidget");'));
  add_action('widgets_init', create_function('', 'return register_widget("socialShoppingWidget");'));
  add_action('widgets_init', create_function('', 'return register_widget("socialCategoryWidget");'));
  
}

// Include APIs
require_once(social_APIS_PATH . "/socialBoardApi.php");
require_once(social_APIS_PATH . "/socialNotificationApi.php");

// Template Tags
if(!function_exists('social_display_user_grid'))
{
  function social_display_user_grid($cols='3', $rows='2', $type='random')
  {
    echo socialShortcodesController::user_grid(array('cols' => $cols, 'rows' => $rows, 'type' => $type));
  }
}

if(!function_exists('social_display_login_nav'))
{
  function social_display_login_nav()
  {
    echo socialShortcodesController::login(array());
  }
}
if(!function_exists('social_display_checkout_nav'))
{
  function social_display_checkout_nav()
  {
    echo socialShortcodesController::checkout(array('cols' => $cols, 'rows' => $rows, 'type' => $type));
  }
}

?>