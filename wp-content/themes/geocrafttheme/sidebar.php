<!--Start Sidebar-->
<div class="sidebar">
    <?php
    // Place widget area
    if (is_active_sidebar('pages-widget-area')) :
       dynamic_sidebar('pages-widget-area'); 
     endif; 
     ?>   
</div>
<!--End Sidebar-->