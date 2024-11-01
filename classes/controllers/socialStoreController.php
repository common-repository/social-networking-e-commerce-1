<?php

class socialStoreController
{
  function display_product($checkout_page)
  {
      require( social_VIEWS_PATH . '/social-store/product.php' );
  }
  function checkout_page()
  {
      require( social_VIEWS_PATH . '/social-store/checkout.php' );
  }
  function display_puchase_history()
  {
      require( social_VIEWS_PATH . '/social-store/purchase_history.php' );
  }
  function display_photo()
  {
      require( social_VIEWS_PATH . '/social-photo/photo.php' );
  }
}
?>