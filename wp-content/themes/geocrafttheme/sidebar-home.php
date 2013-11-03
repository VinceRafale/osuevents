<?php
/**
 * Sidebar widget for front page 
 */
?>
<div class="sidebar">
    <?php
    if (is_active_sidebar('home-widget-area')):
    dynamic_sidebar('home-widget-area');
endif;
    ?>
</div>