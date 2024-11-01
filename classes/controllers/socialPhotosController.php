<?php
class socialPhotosController
{ 
  function display_photo()
  {
      require( social_VIEWS_PATH . '/social-photo/photo.php' );
	  echo social_VIEWS_PATH;
  }
}
?>