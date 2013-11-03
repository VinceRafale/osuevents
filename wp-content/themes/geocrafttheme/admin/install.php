<?php
/**
 * This class is used for installing and
 * Setting predefined options and values.
 */
define('GC_SEARCH', 'search');
function cc_reset_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
}

add_action('all_admin_notices', 'cc_reset_permalinks');
class Geocraft_Install {

// check if theme is activated by admin.
    //calling contructor
    function __construct() {
        global $pagenow;
        if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php') {
            $this->userid = get_current_user_id();
            $this->create_page();
            $this->geocract_default_values();
            $this->geocraft_default_widgets();
        }
    }

    /**
     * Create default pages 
     */
    function create_page() {
        //reset options
//        delete_option('geo_featured_listing');
//        delete_option('geo_listing');
//        delete_option('geo_submit_listing');
//        delete_option('geo_contact_us');
//        delete_option('geo_notify_page');
//        delete_option('geo_dashboard_page');
           /**
         * Create featured listing page
         */
        $pages = get_option('geo_featured_listing');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'featured-listing',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Premium Listings', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_featured_listing', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_featured.php');
            }
        }
        /**
         * Create listing page
         */
        $pages = get_option('geo_listing');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'all-listing',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('All Listings', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_listing', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_listing.php');
            }
        }
        
        /**
         * Create Add listing page
         */
        $pages = get_option('geo_submit_listing');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'submit-listing',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Add New Listing', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_submit_listing', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_submit.php');
            }
        }
         /**
         * Create Add listing page
         */
        $pages = get_option('geo_contact_us');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'contact-us',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Contact Us', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_contact_us', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_contact.php');
            }
        }
        /**
         * Create Transation show page
         */
        $pages = get_option('geo_notify_page');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'trans-notify-page',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                'post_content' => '[pay-status]',
                'post_title' => __('Payment Status', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_notify_page', $pages);
            }
        }
        /**
         * Create Dashboard page
         */
        $pages = get_option('geo_dashboard_page');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'dasboard',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '[pay-status]',
                'post_title' => __('Dashboard', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_dashboard_page', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_dashboard.php');
            }
        }
        /**
         * Create Search page
         */
            $pages = get_option('geo_search_page');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => GC_SEARCH,
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => 
                'post_title' => __('Search', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages = wp_insert_post($my_page);
            if ($pages) {
                update_option('geo_search_page', $pages);
                update_post_meta($pages, '_wp_page_template', 'template_search.php');
            }
        }
    }

//end create_page
    function geocract_default_values() {
        //check the membership box to enable wordpress registration
        if (get_option('users_can_register') == 0)
            update_option('users_can_register', 1);
        //Set default leadcapture on
        if(geocraft_get_option('lead_capture') == ''){
            geocraft_update_option('lead_capture', 'on');
        } 
        if(geocraft_get_option('lead_capture') == ''){
            geocraft_update_option('lead_capture', 'on');
        }  
    }

    /**
     * Activate and set 
     * Default widgets 
     */
    function geocraft_default_widgets() {

        $widget_recent_post = array();
        $widget_recent_post[1] = array(
            "title" => 'Recent Listing',
            "sort_by" => 'date',
            "show_type" => 'listing',
            "number" => '5',
            "excerpt_length" => '20',
        );
        $widget_recent_post['_multiwidget'] = '1';
        update_option('widget_advanced-recent-posts', $widget_recent_post);
        $widget_recent_post = get_option('widget_advanced-recent-posts');
        krsort($widget_recent_post);
        foreach ($widget_recent_post as $key1 => $val1) {
            $widget_recent_post_key = $key1;
            if (is_int($widget_recent_post_key)) {
                break;
            }
        }

        $widget_recent_review = array();
        $widget_recent_review[1] = array(
            "title" => 'Recent Reviews',
            "number" => '5',
        );
        $widget_recent_review['_multiwidget'] = '1';
        update_option('widget_recent-review', $widget_recent_review);
        $widget_recent_review = get_option('widget_recent-review');
        krsort($widget_recent_review);
        foreach ($widget_recent_review as $key2 => $val1) {
            $widget_recent_review_key = $key2;
            if (is_int($widget_recent_review_key)) {
                break;
            }
        }

        $widget_listing_category = array();
        $widget_listing_category[1] = array(
            "title" => 'Categories',
        );
        $widget_listing_category['_multiwidget'] = '1';
        update_option('widget_custom-categories', $widget_listing_category);
        $widget_listing_category = get_option('widget_custom-categories');
        krsort($widget_listing_category);
        foreach ($widget_listing_category as $key3 => $val1) {
            $widget_listing_category_key = $key3;
            if (is_int($widget_listing_category_key)) {
                break;
            }
        }

        $sidebars_widgets["home-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key", "recent-review-$widget_recent_review_key");
        $sidebars_widgets["listing-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key", "recent-review-$widget_recent_review_key");
        $sidebars_widgets["contact-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key", "recent-review-$widget_recent_review_key");
        $sidebars_widgets["blog-widget-area"] = array(0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2',);
        $sidebars_widgets["pages-widget-area"] = array(0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2',);
        update_option('sidebars_widgets', $sidebars_widgets);  //save widget iformations
    }

}

//end class
new Geocraft_Install();
?>
