<?php
if(class_exists('WP_Widget'))
{
  class socialUsersWidget extends WP_Widget {
    /** constructor */
    function socialUsersWidget() {
        parent::WP_Widget(false, $name = __('social Users Grid', 'social'));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        global $social_options;
        $title = apply_filters('widget_title', $instance['title']);
        $cols = $instance['cols'];
        $rows = $instance['rows'];
        $type = $instance['type'];

        $grid_cell_count = $cols * $rows;
        $user_count = socialUser::get_count();
        $user_type = __('Users', 'social');
        $all_users_url = get_permalink($social_options->directory_page_id);
        
        // Grab a random selection of friends from the database
        if( $type == 'random' )
          $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'RAND()' );
        else if( $type == 'recent' )
          $users = socialUser::get_stored_profiles( '', 0, $grid_cell_count, 'ID DESC' );
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                  <?php require social_VIEWS_PATH . "/shared/user_grid.php"; ?>
                  <?php socialAppHelper::powered_by(); ?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        $cols  = esc_attr($instance['cols']);
        $rows  = esc_attr($instance['rows']);
        $type  = esc_attr($instance['type']);
        
        $title = ((empty($title))?__("social Users", 'social'):$title);
        $cols  = ((empty($cols))?"3":$cols);
        $rows  = ((empty($rows))?"2":$rows);
        $type  = ((empty($type))?"random":$type); 
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'social'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('cols'); ?>"><?php _e('# of Grid Columns', 'social'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('cols'); ?>" name="<?php echo $this->get_field_name('cols'); ?>" type="text" value="<?php echo $cols; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('rows'); ?>"><?php _e('# of Grid Rows', 'social'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('rows'); ?>" name="<?php echo $this->get_field_name('rows'); ?>" type="text" value="<?php echo $rows; ?>" /></label></p>
            <p>
              <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Grid Type', 'social'); ?>:
                <select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
                  <option value="random"<?php echo (($type=='random')?' selected="selected"':''); ?>><?php _e('Random social Users', 'social'); ?></option>
                  <option value="recent"<?php echo (($type=='recent')?' selected="selected"':''); ?>><?php _e('Recent social Users', 'social'); ?></option>
                </select>
              </label>
            </p>
        <?php 
    }
  }
}
?>
