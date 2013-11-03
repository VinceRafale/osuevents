<?php

/* Custom Post type for directory
 * Listing
 */
add_action("init", "listing_post_type");

function listing_post_type() {

    register_post_type(POST_TYPE, array('label' => CUSTOM_MENU_TITLE,
        'labels' => array('name' => CUSTOM_MENU_NAME,
            'singular_name' => CUSTOM_MENU_SIGULAR_NAME,
            'add_new' => CUSTOM_MENU_ADD_NEW,
            'add_new_item' => CUSTOM_MENU_ADD_NEW_ITEM,
            'edit' => CUSTOM_MENU_EDIT,
            'edit_item' => CUSTOM_MENU_EDIT_ITEM,
            'new_item' => CUSTOM_MENU_NEW,
            'view_item' => CUSTOM_MENU_VIEW,
            'search_items' => CUSTOM_MENU_SEARCH,
            'not_found' => CUSTOM_MENU_NOT_FOUND,
            'not_found_in_trash' => CUSTOM_MENU_NOT_FOUND_TRASH),
        'public' => true,
        'can_export' => true,
        'has_archive' => TRUE,
        'show_ui' => true, // UI in admin panel
        '_builtin' => false, // It's a custom post type, not built in
        '_edit_link' => 'post.php?post=%d',
        'capability_type' => 'post',
        'menu_icon' => get_template_directory_uri() . '/images/icon.png',
        'hierarchical' => false,
        'rewrite' => array("slug" => POST_TYPE), // Permalinks
        'has_archive' => true,
        'menu_position' => 3,
        'query_var' => POST_TYPE, // This goes to the WP_Query schema
        'supports' => array('title',
            'author',
//            'excerpt',
            'thumbnail',
            'comments',
            'editor',
            //'trackbacks',
            //'custom-fields',
            'revisions'),
        'show_in_nav_menus' => true,
        'taxonomies' => array(CUSTOM_CAT_TYPE, CUSTOM_TAG_TYPE)
            )
    );

// Register custom taxonomy for category
    register_taxonomy(CUSTOM_CAT_TYPE, array(POST_TYPE), array("hierarchical" => true,
        "label" => CUSTOM_MENU_CAT_LABEL,
        'labels' => array('name' => CUSTOM_MENU_CAT_TITLE,
            'singular_name' => CUSTOM_MENU_SIGULAR_CAT,
            'search_items' => CUSTOM_MENU_CAT_SEARCH,
            'popular_items' => CUSTOM_MENU_CAT_SEARCH,
            'all_items' => CUSTOM_MENU_CAT_ALL,
            'parent_item' => CUSTOM_MENU_CAT_PARENT,
            'parent_item_colon' => CUSTOM_MENU_CAT_PARENT_COL,
            'edit_item' => CUSTOM_MENU_CAT_EDIT,
            'update_item' => CUSTOM_MENU_CAT_UPDATE,
            'add_new_item' => CUSTOM_MENU_CAT_ADDNEW,
            'new_item_name' => CUSTOM_MENU_CAT_NEW_NAME,),
        'public' => true,
        'show_ui' => true,
        "rewrite" => true)
    );
    //Register custom taxonomy for tags
    register_taxonomy(CUSTOM_TAG_TYPE, array(POST_TYPE), array("hierarchical" => false,
        "label" => CUSTOM_MENU_TAG_LABEL,
        'labels' => array('name' => CUSTOM_MENU_TAG_TITLE,
            'singular_name' => CUSTOM_MENU_TAG_NAME,
            'search_items' => CUSTOM_MENU_TAG_SEARCH,
            'popular_items' => CUSTOM_MENU_TAG_POPULAR,
            'all_items' => CUSTOM_MENU_TAG_ALL,
            'parent_item' => CUSTOM_MENU_TAG_PARENT,
            'parent_item_colon' => CUSTOM_MENU_TAG_PARENT_COL,
            'edit_item' => CUSTOM_MENU_TAG_EDIT,
            'update_item' => CUSTOM_MENU_TAG_UPDATE,
            'add_new_item' => CUSTOM_MENU_TAG_ADD_NEW,
            'new_item_name' => CUSTOM_MENU_TAG_NEW_ADD,),
        'public' => true,
        'show_ui' => true,
        'rewrite' => array('slug' => CUSTOM_TAG_TYPE)
            )
    );


    add_filter('manage_edit-listing_columns', 'edit_listing_columns');

    function edit_listing_columns($columns) {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Listings'),
            'author' => __('Author'),
            'geo_address' => __('Address'),
            'post_category' => __('Categories'),
            'post_tags' => __('Tags'),
            'date' => __('Date'),
            'listing_type' => __('Listing Type'),
            'listing_expiry' => __('Expires'),
        );

        return $columns;
    }

    add_action('manage_listing_posts_custom_column', 'manage_listing_columns', 10, 2);

    function manage_listing_columns($column, $post_id) {
        global $post;

        switch ($column) {
            case 'post_category' :
                /* Get the post_category for the listing. */

                $geo_listings = get_the_terms($post_id, CUSTOM_CAT_TYPE);
                if (is_array($geo_listings)) {
                    foreach ($geo_listings as $key => $geo_listing) {
                        $edit_link = site_url() . "/wp-admin/edit.php?" . CUSTOM_CAT_TYPE . "=" . $geo_listing->slug . "&post_type=" . POST_TYPE;
                        $geo_listings[$key] = '<a href="' . $edit_link . '">' . $geo_listing->name . '</a>';
                    }
                    echo implode(' , ', $geo_listings);
                } else {
                    _e('Uncategorized');
                }

                break;

            case 'post_tags' :
                /* Get the post_tags for the listing. */
                $geo_listing_tags = get_the_terms($post_id, CUSTOM_TAG_TYPE);
                if (is_array($geo_listing_tags)) {
                    foreach ($geo_listing_tags as $key => $geo_listing_tag) {
                        $edit_link = site_url() . "/wp-admin/edit.php?" . CUSTOM_TAG_TYPE . "=" . $geo_listing_tag->slug . "&post_type=" . POST_TYPE;
                        $geo_listing_tags[$key] = '<a href="' . $edit_link . '">' . $geo_listing_tag->name . '</a>';
                    }
                    echo implode(' , ', $geo_listing_tags);
                } else {
                    _e('');
                }

                break;
            case 'geo_address' :
                /* Get the address for the post. */
                $geo_address = get_post_meta($post_id, 'geo_address', true);
                if ($geo_address != '') {
                    $geo_address = $geo_address;
                } else {
                    $geo_address = ' ';
                }
                echo $geo_address;
                break;
            case 'listing_type' :
                $listing_type = get_post_meta($post_id, 'geocraft_listing_type', true);
                if ($listing_type == 'pro'):
                    echo "Paid";
                else:
                    echo "Free";
                endif;
                break;
                case 'listing_expiry':
                  $listing_expiry =  get_post_meta($post_id, 'gc_listing_duration', true);
                    if($listing_expiry)
                        printf("%s \n",$listing_expiry);
                    echo "<br/>";
                   printf("\n%s",gc_timeleft(strtotime($listing_expiry)));
                break;
            default :
                break;
        }
    }

}

/////The filter code to get the custom post type in the RSS feed
function myfeed_request($qv) {
    if (isset($qv['feed']))
        $qv['post_type'] = get_post_types();
    return $qv;
}

add_filter('request', 'myfeed_request');