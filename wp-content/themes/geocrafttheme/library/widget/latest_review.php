<?php

class GeoCraft_recent_comment extends WP_Widget {

    function GeoCraft_recent_comment() {
        $widget_ops = array('classname' => 'widget_recent_review', 'description' => __('The most recent review'));
        $this->WP_Widget('recent-review', __('Geocraft Recent Review'), $widget_ops);
        $this->alt_option_name = 'widget_recent_review';


        add_action('comment_post', array(&$this, 'geocraft_widget_cache'));
        add_action('transition_comment_status', array(&$this, 'geocraft_widget_cache'));
    }

    function geocraft_widget_cache() {
        wp_cache_delete('recent_review', 'widget');
    }

    function widget($args, $instance) {
        global $wpdb, $comments, $comment, $rating_table_name;
        $post_type = POST_TYPE;
        extract($args, EXTR_SKIP);
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Review') : $instance['title']);
        if (!$number = (int) $instance['number'])
            $number = 5;
        else if ($number < 1)
            $number = 1;
        else if ($number > 150)
            $number = 150;

        if (!$comments = wp_cache_get('recent_comments', 'widget')) {
            $comments = $wpdb->get_results("SELECT $wpdb->comments.* FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID WHERE comment_approved = '1' AND post_status = 'publish' AND post_type='$post_type' ORDER BY comment_date_gmt DESC LIMIT 150");
            wp_cache_add('recent_comments', $comments, 'widget');
        }

        $comments = array_slice((array) $comments, 0, $number);
        //how many characters in length should the comment excerpts be?
        $excerptLen = 50;
        ?>
        <?php echo $before_widget; ?>     
        <!--Start Review Thumb-->
        <div class="review_thumb">
            <h4 class="r_thumb_title"><?php if ($title)
            echo $title; ?></h4>
            <?php
            if ($comments) : foreach ((array) $comments as $comment) :                    
                    $aRecentComment = get_comment($comment->comment_ID);
                    $aRecentCommentTxt = trim(mb_substr(strip_tags(apply_filters('comment_text', $aRecentComment->comment_content)), 0, $excerptLen));
                    if (strlen($aRecentComment->comment_content) > $excerptLen) {
                        $aRecentCommentTxt .= "\t[...]";
                    }
                    ?>
                    <!--Start Review Element-->
                    <div class="r_element">
                    <?php echo get_avatar($comment, 38); ?>
                        <div class="r_content">
                            <h6 class="r_title"><?php echo sprintf(_x('%2$s', 'widgets'), '', '<a href="' . esc_url(get_comment_link($comment->comment_ID)) . '">' . $aRecentCommentTxt . '</a>'); ?></h6>
                            <ul class="r_rating">
                                <?php
                                $post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment->comment_ID\"");
                                echo geocraft_display_rating_star($post_rating);
                                ?>
                            </ul>
                            <p class="r_excerpt"><?php _e('Review left by&nbsp;',THEME_SLUG);  printf(__('<cite class="fn"><a href="?author=%d">%s</a></cite>'),$comment->user_id,$comment->comment_author) ?>
                                <?php _e('On ',THEME_SLUG); ?><?php  echo mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $comment->comment_date); ?>
                            </p>
                        </div>
                    </div>
                    <!--End Review Element-->
                    <?php endforeach;
            endif; ?>
        </div>
        <!--End Review Thumb-->

        <?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $this->geocraft_widget_cache();

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_recent_comments']))
            delete_option('widget_recent_comments');

        return $instance;
    }

    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of review to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
            <small><?php _e('(at most 150)'); ?></small></p>
        <?php
    }

}

function Geocraft_recent_comment() {
    register_widget('GeoCraft_recent_comment');
}

add_action('widgets_init', 'Geocraft_recent_comment');
