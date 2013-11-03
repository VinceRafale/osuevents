<?php
/**
 * The Footer widget areas.
 *
 * @package Geocraft
 * @since 1.0
 */
?>
<div class="grid_6 alpha">
    <div class="footer_widget">
         <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
      <?php dynamic_sidebar('first-footer-widget-area'); ?>
      <?php else : ?>
        <h5><?php _e('About This Site',THEME_SLUG); ?></h5>
        <p><?php _e('A cras tincidunt, ut  tellus et. Gravida scel ipsum sed iaculis, nunc non nam. Placerat sed phase llus, purus purus elit.',THEME_SLUG); ?></p>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6">
    <div class="footer_widget">
        <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
      <?php dynamic_sidebar('second-footer-widget-area'); ?>
      <?php else: ?>
        <h5><?php _e('Archives Widget',THEME_SLUG); ?></h5>
        <ul>
            <li><a href="#"><?php _e('January 2010',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('December 2009',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('November 2009',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('October 2009',THEME_SLUG); ?></a></li>
        </ul>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6">
    <div class="footer_widget">
        <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
      <?php dynamic_sidebar('third-footer-widget-area'); ?>
      <?php else: ?>
        <h5><?php _e('Categories',THEME_SLUG); ?></h5>
        <ul>
            <li><a href="#"><?php _e('Entertainment',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('Technology',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('Sports & Recreation',THEME_SLUG); ?></a></li>
            <li><a href="#"><?php _e('Jobs & Lifestyle',THEME_SLUG); ?></a></li>
        </ul>
        <?php endif; ?>
    </div>
</div>
<div class="grid_6 omega">
    <div class="footer_widget last">
        <?php if (is_active_sidebar('fourth-footer-widget-area')) : ?>
      <?php dynamic_sidebar('fourth-footer-widget-area'); ?>
      <?php else: ?>
        <h5>Search</h5>
    <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</div>