  <h4><?php _e('Custom Fields', 'social'); ?>:</h4>
  <div class="social-options-pane">
  <?php
    $index = 0;
    if(count($custom_fields) > 0)
    {
      foreach($custom_fields as $field)
      {
        if($field and !empty($field) and is_array($field))
          $this->display_custom_field($index,$field,false);
        $index++;
      }
    }

    $this->display_add_custom_field_button($index);
  ?>
  </div>