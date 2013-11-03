<?php

function geocraft_place_submmition() {
    if (isset($_REQUEST['pay_method']) && $_REQUEST['paypal_mode'] == 'paypal') {
        $pay_method = $_REQUEST['pay_method'];
    }
    if (isset($_POST['submit'])) {
        global $user_ID, $post, $posted, $wpdb;

        $posted = unserialize(base64_decode($_POST['posted']));

        //Approval needed
        if (isset($_REQUEST['pay_method']) && $posted['total_cost'] > 0) {
            $post_status = 'pending';
        } elseif (!isset($pay_method) && $posted['total_cost'] == 0) {
            $status = strtolower(geocraft_get_option('free_post_mode'));
            if ($status == 'pending'):
                $post_status = 'pending';
            endif;
            if ($status == 'publish'):
                $post_status = 'publish';
            endif;
        }

        ## Create Post
        $post_title = $wpdb->escape($posted['list_title']);
        $data = array(
            'post_content' => $wpdb->escape($posted['geocraft_description']),
            'post_title' => $post_title,
            'post_status' => $post_status,
            'post_author' => $user_ID,
            'post_type' => POST_TYPE
        );

        $post_id = wp_insert_post($data);
        global $posted_value;
        $posted_value = array(
            'post_id' => $post_id,
            'p_method' => $pay_method,
            'f_period' => $posted['f_period'],
            'f_cycle' => $posted['f_cycle'],
            'installment' => $posted['installment'],
            's_price' => $posted['s_price'],
            's_period' => $posted['s_period'],
            's_cycle' => $posted['s_cycle'],
            'billing' => $posted['billing']
        );
        //Insert activation details in activation table
        if ($posted['package_validity'] != '' && $posted['package_validity_per'] != '') {
            global $wpdb, $expiry_tbl_name;
            $validity = $posted['package_validity'];
            $validity_per = $posted['package_validity_per'];
            $pkg_type = $posted['package_type'];
            if ($pkg_type == '') {
                $pkg_type = 'pkg_free';
            }
            //Not used expiry_tbl_name since 1.7.1
//            $current_date = date("Y-m-d H:i:s");
//            $insert_array = array(
//                'pid' => $post_id,
//                'listing_title' => $post_title,
//                'validity' => $validity,
//                'validity_per' => $validity_per,
//                'listing_date' => $current_date,
//                'package_type' => $pkg_type
//            );
//            //inserting expiry values
//            $wpdb->insert($expiry_tbl_name, $insert_array);
            //set listing expiry
            gc_set_expiry($post_id, $pkg_type);
        }
        update_post_meta($post_id, 'geocraft_listing_type', 'free');

        $free_listing = get_post_meta($post_id, 'geocraft_listing_type', true);
        //Add custom category
        $categories = explode(',', $posted['category']);
        if (sizeof($posted) > 0):
            wp_set_object_terms($post_id, $categories, CUSTOM_CAT_TYPE);
        endif;

        //Add custom tag
        $tags = explode(',', $posted['geocraft_tag']);
        if (sizeof($posted) > 0) {
            wp_set_object_terms($post_id, $tags, CUSTOM_TAG_TYPE);
        }
        // Check either post created or not
        if ($post_id == 0 || is_wp_error($post_id))
            wp_die(__('Error: Unable to create entry.', ''));
        //Add meta data
        $featured_home = '';
        $featured_cate = '';
        if ($posted['feature_h']) {
            $featured_home = 'on';
        }
        if ($posted['feature_c']) {
            $featured_cate = 'on';
        }
        $listing_meta = array(
            'geocraft_f_checkbox1' => esc_attr($featured_home),
            'geocraft_f_checkbox2' => esc_attr($featured_cate)
        );
        if ($listing_meta) {
            foreach ($listing_meta as $key => $meta):
                add_post_meta($post_id, $key, $meta, true);
            endforeach;
        }
        $custom_meta = get_custom_field();
        if ($custom_meta) {
            foreach ($custom_meta as $meta):
                add_post_meta($post_id, $meta['name'], $posted[$meta['name']], true);
            endforeach;
        }
        if (isset($_POST['submit']) && isset($_REQUEST['pay_method']) && $_REQUEST['pay_method'] != '') {
            if (isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'paypal'):
                if (file_exists(LIBRARYPATH . "getway/paypal/paypal_response.php")):
                    include_once(LIBRARYPATH . "getway/paypal/paypal_response.php");
                endif;
            elseif (isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'sandbox'):
                if (file_exists(LIBRARYPATH . "getway/paypal/paypal_sandbox.php")):
                    include_once(LIBRARYPATH . "getway/paypal/paypal_sandbox.php");
                endif;

            endif;
        }
        // this will redirects to your original
        // form's page but using GET method
        // so re-submitting will be no possible
        ?>
        <script type="text/javascript">
            alert("Thank You, Your listing has been successfully submitted");
        </script>
        <?php
        header("refresh:1;url={$_SERVER['PHP_SELF']}");
    }
}
