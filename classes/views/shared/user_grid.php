<?php $col_width = (int)((float)$cols/100.00); ?>
<div class="social-user-grid">
  <div class="social-user-grid-header">
  	<div class="social-user-grid-header1"><?php echo number_format( (float)$user_count ); ?></div>
	<div class="social-user-grid-header2"><?php echo $user_type; ?></div>
	<div id="showalluser"><a href="<?php echo $all_users_url; ?>"><?php _e('Show All', 'social'); ?></a></div>
  </div>
  <div id="usertable">
    <?php
      for ($i=0; $i < $rows; $i++) { 
            for ($j=0; $j < $cols; $j++) { 
              $user_index = ($i * $cols) + $j;
              
              if($user_index >= $user_count)
                break;

              $user = $users[$user_index];
              $avatar = $user->get_avatar(50);
              ?>
                <div class="social-grid-cell" rel="<strong><?php echo $user->screenname; ?></strong><br/><?php echo $user->full_name; ?>"><?php echo $avatar; ?></div></center>
              <?php
            }
            ?>
        <?php
      }
    ?>
  </div>
</div>
