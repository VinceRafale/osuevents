<?php
/**
 * Archive widgets from different post type
 * 
 * @author InkThemes
 * @since 1.0
 *  
 */
if (!class_exists('Geocraft_Archives')) {

    class Geocraft_Archives extends WP_Widget {

        function __construct() {
            $widget_ops = array('classname' => 'geocraft_widget_archive', 'description' => __('A monthly archive of your site&#8217;s posts or custom pots'));
            parent::__construct('geocraft_archives', __('Geocraft Archives'), $widget_ops);
        }

        function widget($args, $instance) {
            extract($args);
            $c = !empty($instance['count']) ? '1' : '0';
            $d = !empty($instance['dropdown']) ? '1' : '0';
            $title = apply_filters('widget_title', empty($instance['title']) ? __('Archives') : $instance['title'], $instance, $this->id_base);
            if (!$show_type = $instance["show_type"])
                $show_type = 'post';
            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            $my_args = array(
                'post_type' => $show_type
            );
            $c_posts = new WP_Query($my_args);

            if ($c_posts->have_posts()):
                $c_posts->the_post();
                if ($d) {
                    ?>
                    <select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> <option value=""><?php echo esc_attr(__('Select Month')); ?></option> <?php wp_get_archives(apply_filters('widget_archives_dropdown_args', array('type' => 'monthly', 'format' => 'option', 'show_post_count' => $c))); ?> </select>
                    <?php
                } else {
                    ?>
                    <ul>
                        <?php wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'show_post_count' => $c))); ?>
                    </ul>
                    <?php
                }
            endif;
            wp_reset_query();
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $new_instance = wp_parse_args((array) $new_instance, array('title' => '', 'count' => 0, 'dropdown' => ''));
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['count'] = $new_instance['count'] ? 1 : 0;
            $instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;
            $instance['show_type'] = esc_attr($new_instance['show_type']);
            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => '', 'count' => 0, 'dropdown' => ''));
            $title = strip_tags($instance['title']);
            $count = $instance['count'] ? 'checked="checked"' : '';
            $dropdown = $instance['dropdown'] ? 'checked="checked"' : '';
            $show_type = isset($instance['show_type']) ? esc_attr($instance['show_type']) : 'post';
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p>
                <input class="checkbox" type="checkbox" <?php echo $dropdown; ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
                <br/>
                <input class="checkbox" type="checkbox" <?php echo $count; ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('show_type'); ?>"><?php echo R_P_TYPE; ?> 
                    <select class="widefat" id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
                        <?php
                        global $wp_post_types;
                        foreach ($wp_post_types as $k => $sa) {
                            if ($sa->exclude_from_search)
                                continue;
                            echo '<option value="' . $k . '"' . selected($k, $show_type, true) . '>' . $sa->labels->name . '</option>';
                        }
                        ?>
                    </select>
                </label>
            </p>
            <?php
        }

    }

    register_widget('Geocraft_Archives');
}
