<?php
/**
 * Geocraft expiry listings
 * This file is the backbone for expire listing
 * Modifying this will void your warranty and could cause
 * problems with your instance of CP. Proceed at your own risk!
 *
 * @package Geocraft
 * @author InkThemes
 * @since version: 1.7.1
 * 
 * Terms for expire listing:
 * 
 * 1. Free listing and onetime listing expire based on listing
 *    active period on payment setting.
 * 
 * 2. Recurring listing expire based on first period of payment +
 *    Second periond of payment.
 * 
 * 3. If listings are loder than current version, lisstings are expiry  
 *    period will be set by based on current active period.
 * 
 * 4. If admin post the listings, The expiry duration will be set based on
 *    current free package active period.
 * 
 * 5. After the listing expired, an email notification would be send on
 *    listing author.
 */
//Expiry term for older and front end listings.
global $wpdb, $expiry_tbl_name;
$sql_expiry = "SELECT * FROM " . $expiry_tbl_name . "";
$expiry_query = $wpdb->get_results($sql_expiry);
if ($expiry_query):
    foreach ($expiry_query as $expiry):
        $last_post_date = get_post_date_gmt($expiry->pid);
        $listing_type = get_post_meta($expiry->pid, 'geocraft_listing_type', true);
        $pkg_type = $expiry->package_type;
        $vper = $expiry->validity_per;
        $validity = $expiry->validity;
        gc_set_expiry($expiry->pid, $pkg_type);
        $expire = gc_has_ad_expired($expiry->pid);
        if ($expire == true) {
            //Send notification to user
            global $wpdb, $post;
            $slq_notify = "SELECT post_author FROM $wpdb->posts WHERE ID = $expiry->pid";
            $post_result = $wpdb->get_row($slq_notify);
            $post_author = $post_result->post_author;
            $site_name = get_option('blogname');
            $email = get_option('admin_email');
            $website_link = get_option('siteurl');
            $listing_title = $listing->post_title;
            $lisgint_guid = $listing->guid;
            $login_url = site_url("/wp-login.php?action=login");
            $listing_user_name = get_the_author_meta('user_login', $post_author);
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Dear $listing_user_name, \r";
            $message .= "Your listing is expired. We inform you that, if you are interested to reactivate your listing, \r";
            $message .= "Login in our website and reactivate it. \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Listing Title: $listing_title \r";
            $message .= "Login On: $login_url \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Website: $site_name\r";
            $message .= "Website URL: $website_link\r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message = __($message, THEME_SLUG);
            //get listing author email
            $to = get_the_author_meta('user_email', $post_author);
            $subject = 'Your listing reactivation notice';
            $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            //$is_mail_send = get_post_meta($listing->ID, 'gc_prevent_multiple_email', true);
            //check whether email already send
            //if ($is_mail_send !== $listing->ID) {
            wp_mail($to, $subject, $message, $headers);
            add_post_meta($listing->ID, 'gc_prevent_multiple_email', $listing->ID);
            //}
        }
    endforeach;
endif;

//Expiry term for older and admin's listings. 
global $wpdb;
$post_type = POST_TYPE;
$listing_query = "SELECT * FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status = 'publish'";
$listing_result = $wpdb->get_results($listing_query);
if (!empty($listing_result)) {
    foreach ($listing_result as $listing) {
        $is_expiry_set = get_post_meta($listing->ID, 'gc_listing_duration', true);
        /**
         * Check if listing expiry date is already set. 
         * If not set, it will set the expiry date based on current free package active period.
         */
        if (!$is_expiry_set) {
            gc_set_expiry($listing->ID);
        }
        //getting listing status
        $expire = gc_has_ad_expired($listing->ID);
        //if listing expired
        if ($expire == true) {
            $post_author = $listing->post_author;
            $site_name = get_option('blogname');
            $email = get_option('admin_email');
            $website_link = get_option('siteurl');
            $listing_title = $listing->post_title;
            $lisgint_guid = $listing->guid;
            $login_url = site_url("/wp-login.php?action=login");
            $listing_user_name = get_the_author_meta('user_login', $post_author);
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Dear $listing_user_name, \r";
            $message .= "Your listing is expired. We inform you that, if you are interested to reactivate your listing, \r";
            $message .= "Login in our website and reactivate it. \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Listing Title: $listing_title \r";
            $message .= "Login On: $login_url \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Website: $site_name\r";
            $message .= "Website URL: $website_link\r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message = __($message, THEME_SLUG);
            //get listing author email
            $to = get_the_author_meta('user_email', $post_author);
            $subject = 'Your listing reactivation notice';
            $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            //$is_mail_send = get_post_meta($listing->ID, 'gc_prevent_multiple_email', true);
            //check whether email already send
            //if ($is_mail_send !== $listing->ID) {
            wp_mail($to, $subject, $message, $headers);
            //add_post_meta($listing->ID, 'gc_prevent_multiple_email', $listing->ID);
            //}
        }
    }
}