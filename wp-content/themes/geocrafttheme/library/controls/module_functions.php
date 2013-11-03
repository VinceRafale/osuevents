<?php

/**
 * Function for enqueue stylesheet
 */
function geocraft_module_style() {
    wp_enqueue_style('geocraft-module-style', LIBRARYURL . "css/geo_module_style.css", '', '', 'all');
}

add_action('init', 'geocraft_module_style');

/**
 * Function for enqueue java script
 */
function geocraft_module_js() {
    wp_enqueue_script('geocraft-ajaxupload', LIBRARYURL . 'js/ajaxupload.js', array('jquery'));
}

add_action('init', 'geocraft_module_js');

/**
 * Function Name: geocraft_commentslist
 * Description: Callback to commentlist
 * @global type $wpdb
 * @global type $post
 * @global type $rating_table_name
 * @param type $comment
 * @param type $args
 * @param type $depth 
 */
function geocraft_commentslist($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    global $wpdb, $post, $rating_table_name;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    $post_type = POST_TYPE;
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php
        endif;
        global $post;
        if ($post->post_type == $post_type) {
            ?>
            <ul class="rating">
                <?php
                $post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment->comment_ID\"");
                echo geocraft_display_rating_star($post_rating);
                ?>
            </ul>
        <?php } ?>
        <div class="comment-author vcard"><img class="cmt_frame" src="<?php echo TEMPLATEURL; ?>/images/cmt-frame.png" />
            <?php
            if ($args['avatar_size'] != 0)
                echo get_avatar($comment, $args['avatar_size']);
            ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">-</span>'), get_comment_author_link()) ?>
            <a class="comment-meta" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time())
                ?></a><?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
        </div>
        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php echo UR_CMT_AWAIT; ?></em>
            <br />
        <?php endif; ?>

        <?php comment_text() ?>

        <div class="reply">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

/**
 * Function Name: geocraft_get_related_post
 * Description: This function shows related post
 * From custom post type 
 * @global type $post 
 */
function geocraft_get_related_post() {
    global $post;
    //for in the loop, display all "content", regardless of post_type,
    //that have the same custom taxonomy (e.g. genre) terms as the current post
    $taxonomy = CUSTOM_CAT_TYPE; //  e.g. post_tag, category, custom taxonomy
    $param_type = CUSTOM_CAT_TYPE; //  e.g. tag__in, category__in, but genre__in will NOT work
    $tax_args = array('orderby' => 'date');
    $tags = wp_get_post_terms($post->ID, $taxonomy, $tax_args);
    if ($tags) {
        foreach ($tags as $tag) {

            $args = array(
                $param_type => $tag->slug,
                'post__not_in' => array($post->ID),
                'post_type' => POST_TYPE,
                'posts_per_page' => 3,
                'caller_get_posts' => 1
            );
        }
        $my_query = null;
        $my_query = new WP_Query($args);
        $loop_count = 0;
        $col_class = '';
        if ($my_query->have_posts()) {
            echo '<ul class="slides">';
            echo '<li>';
            while ($my_query->have_posts() && $loop_count != 3) : $my_query->the_post();
                $loop_count++;
                if ($loop_count == 3) {
                    $col_class = 'last';
                }
                $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
                ?>
                <div class="related <?php echo $col_class; ?>" >
                    <div class="r_item">

                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                            <?php inkthemes_get_thumbnail(175, 150, '', $img_meta); ?>                    
                        <?php } else { ?>
                            <?php inkthemes_get_image(175, 150, '', $img_meta); ?> 
                            <?php
                        }
                        ?>
                        <h5><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
                        <ul class="rating">
                            <?php echo geocraft_get_post_rating_star($post->ID); ?>
                        </ul>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</li>';
            echo '</ul>';
        }
    }
    wp_reset_query(); // to use the original query again   
}

/**
 * Function Name: geocraft_excerpt
 * Description: It returns linked elipse
 * @param type $text
 * @return type 
 */
function geocraft_excerpt($text) {
    return str_replace('[...]', '<a href="' . get_permalink($post->ID) . '">' . '[...] Read More' . '</a>', $text);
}

add_filter('the_excerpt', 'geocraft_excerpt');

/**
 * Function Name: geocraft_auth_menu
 * Description: Displays user menu
 * @global type $current_user 
 */
function geocraft_auth_menu() {
    global $current_user;
    global $wpdb;

    $dashboard_pid = get_option('geo_dashboard_page');
    $ink = home_url("/?page_id=$dashboard_pid");

    $geo_val = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =$dashboard_pid ", ARRAY_N);
    if (!$geo_val[0]) {
        $wpdb->insert($wpdb->posts, array('ID' => $dashboard_pid, 'post_author' => 1, 'post_date_gmt' => $dat, 'post_title' => 'Dashboard', 'post_status' => 'publish', 'comment_status' => 'closed', 'ping_status' => 'closed', 'post_name' => 'dasboard', 'guid' => $ink, 'post_type' => 'page'));

        $mylink = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id =$dashboard_pid ", ARRAY_N);
        $wpdb->insert($wpdb->postmeta, array('meta_id' => $mylink[0], 'post_id' => $dashboard_pid, 'meta_key' => '_wp_page_template', 'meta_value' => 'template_dashboard.php'));
    }
    if ($geo_val[7] == 'trash') {
        $wpdb->query("UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID =$dashboard_pid");
    }

    $geo_list = get_option('geo_submit_listing');
    $geo_url = site_url('/?page_id=' . get_option('geo_submit_listing'));
    $geo_val_list = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID =$geo_list ", ARRAY_N);
    if (!$geo_val_list[0]) {
        $wpdb->insert($wpdb->posts, array('ID' => $geo_list, 'post_author' => 1, 'post_date_gmt' => $dat, 'post_title' => 'Add New Listing', 'post_status' => 'publish', 'comment_status' => 'closed', 'ping_status' => 'closed', 'post_name' => 'submit-listing', 'guid' => $geo_url, 'post_type' => 'page', 'post_excerpt' => ''));
        $listlink = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id =$geo_list ", ARRAY_N);
        $wpdb->insert($wpdb->postmeta, array('meta_id' => $listlink[0], 'post_id' => $geo_list, 'meta_key' => '_wp_page_template', 'meta_value' => 'template_submit.php',));
    }
    if ($geo_val_list[7] == 'trash') {
        $wpdb->query("UPDATE $wpdb->posts SET post_status = 'publish' WHERE ID =$geo_list");
    }
    ?>
    <ul class="associative_link">
        <?php
        if (is_user_logged_in()) {
            ?> 
            <li><?php echo WLCOME; ?>&nbsp;&nbsp;<?php echo $current_user->display_name; ?></li>            
            <li><a href="<?php echo home_url("/?page_id=$dashboard_pid"); ?>"><?php echo DASHBOARD; ?></a></li>
        <?php } else { ?>  
            <li><a href="<?php echo site_url('wp-login.php?action=login'); ?>"><?php echo SIGN; ?></a></li> 
        <?php } ?>		
        <li><a href="<?php echo site_url('/?page_id=' . get_option('geo_submit_listing')); ?>"><?php echo ADD_LISTING; ?></a></li>
        <?php if (is_user_logged_in()) { ?>
            <li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo LOG_OUT; ?></a></li> 
        <?php } ?>
    </ul>
    <?php
}

add_action('geocraft_auth_menu', 'geocraft_auth_menu');

function get_wp_cat_checklist($post_taxonomy, $pid) {
    $pid = explode(',', $pid);
    global $wpdb;
    $taxonomy = $post_taxonomy;
    $table_prefix = $wpdb->prefix;
    $wpcat_id = NULL;
    //Fetch category                          
    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");

    $wpcategories = array_values($wpcategories);
    $wpcat2 = NULL;
    if ($wpcategories) {
        echo "<ul class=\"select-cat\">";
        if ($taxonomy == CUSTOM_CATEGORY_TYPE) {
            ?>
            <li><label><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo SLT_ALL; ?></li>

            <?php
        }
        foreach ($wpcategories as $wpcat) {
            $counter++;
            $termid = $wpcat->term_id;
            $name = ucfirst($wpcat->name);
            $termprice = $wpcat->term_price;
            $tparent = $wpcat->parent;
            ?>
            <li><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" <?php
            if ($pid[0]) {
                if (in_array($termid, $pid)) {
                    echo "checked=checked";
                }
            } else {
                
            }
            ?> /><?php echo $name; ?></label></li>
                <?php
                if ($taxonomy != "") {
                    $child = get_term_children($termid, $post_taxonomy);
                    foreach ($child as $child_of) {
                        $term = get_term_by('id', $child_of, $post_taxonomy);
                        $termid = $term->term_taxonomy_id;
                        $term_tax_id = $term->term_id;
                        $termprice = $term->term_price;
                        $name = $term->name;
                        $catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where t.term_id='" . $child_of->term_id . "' and t.term_id = tt.term_id");
                        ?>
                    <li style="margin-left:15px;"><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" <?php
                    if ($pid[0]) {
                        if (in_array($termid, $pid)) {
                            echo "checked=checked";
                        }
                    }
                        ?> /><?php echo $name; ?></label></li>
                        <?php
                    }
                }
            }
            echo "</ul>";
        }
    }

// Output errors
    function geocraft_show_errors($errors, $id = '') {
        if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) :
            echo '<ul class="errors" id="' . $id . '">';
            foreach ($errors->errors as $error) {
                echo '<li>' . $error[0] . '</li>';
            }
            echo '</ul>';
        endif;
    }

    /**
     * Function for get author info
     * @global type $wpdb
     * @param type $post_id
     * @return type 
     */
    function get_author_info($post_id) {
        global $wpdb;
        $sql = "SELECT $wpdb->users.*
            FROM
            $wpdb->users
            INNER JOIN $wpdb->posts
            ON $wpdb->users.ID = $wpdb->posts.post_author where $wpdb->posts.post_author=$post_id";

        $returninfo = $wpdb->get_row($sql);
        return $returninfo;
    }

    /**
     * Function Name: custom_post_author_archive
     * Description: Displaying custom post type author archives
     * @param type $query 
     */
    function custom_post_author_archive(&$query) {
        if ($query->is_author)
            $query->set('post_type', POST_TYPE);
        remove_action('pre_get_posts', 'custom_post_author_archive'); // run once!
    }

    add_action('pre_get_posts', 'custom_post_author_archive');

    function filter_search($query) {
        if ($query->is_search) {
            $query->set('post_type', POST_TYPE);
        };
        return $query;
    }

    ;

//add_filter('pre_get_posts', 'filter_search');
    /**
     * Function Name: get_custom_search()
     * Description: multisearch
     * @global type $wpdb global database
     * @param type $post_id current post id
     * @param type $addvalue
     * @return boolean 
     */
    function get_custom_search($post_id, $addvalue) {
        global $wpdb;
        $sql = "SELECT *
            FROM
            $wpdb->posts, $wpdb->postmeta
            WHERE
            $wpdb->posts.ID = $post_id
            AND $wpdb->postmeta.post_id = $post_id
            AND $wpdb->postmeta.meta_key = 'geocraft_meta_address'
            AND $wpdb->postmeta.meta_value LIKE '%$addvalue %'";

        $data = $wpdb->get_row($sql);
        if ($data) {
            return true;
        }
    }

    function my_custom_post_type_archive_where($where, $args) {
        $post_type = POST_TYPE;
        $where = "WHERE post_type = '$post_type' AND post_status = 'publish'";
        return $where;
    }

    add_filter('getarchives_where', 'my_custom_post_type_archive_where', 10, 2);

    /**
     * Function Name: posts_for_current_author()
     * Description: Filter all listing and post by current auther
     * @global type $user_ID
     * @param type $query
     * @return type 
     */
    function posts_for_current_author($query) {
        if ($query->is_admin) {

            global $user_ID;
            $query->set('author', $user_ID);
        }
        return $query;
    }

    if (!current_user_can('administrator')) :
        add_filter('pre_get_posts', 'posts_for_current_author');
    endif;

    /**
     * Show admin bar only for admins 
     */
    if (!current_user_can('manage_options')) {
        add_filter('show_admin_bar', '__return_false');
    }

    /**
     * Function for remove post status count in others users admin area 
     */
    function jquery_remove_counts() {
        ?>
    <script type="text/javascript">
        jQuery(function(){
            jQuery("li.all").remove();
            jQuery("li.publish").find("span.count").remove();
            jQuery("li.trash").find("span.count").remove();
            jQuery("li.mine").remove();
        });
    </script>
    <?php
}

if (!current_user_can('administrator')) :
    add_action('admin_footer', 'jquery_remove_counts');
endif;

/**
 * Function Name: save_inquiry()
 * Description: To get the users feedback and 
 * store that feedback in backend
 * 
 * @global type $post
 * @global type $wpdb
 * @global type $inquiry_tbl_name 
 */
function save_inquiry() {
    ?>
    <script type="text/javascript">                             
                                                                                                                                                                            
        jQuery(function(){ 
            var name = jQuery("#name");
            var name_error = jQuery('#name_error');
            function validate_name(){                        
                if(name.val() == ''){
                    name.addClass("error");
                    name_error.text("Please enter your name");
                    name_error.addClass("error1");
                    return false;
                }
                else{
                    name.removeClass("error");
                    name_error.text("");
                    name_error.removeClass("error1");
                    return true;
                }
            }
                                                                                                                                                                                
            name.blur(validate_name);
            name.keyup(validate_name); 
            var email = jQuery("#semail"); 
            var email_error = jQuery("#email_error");    
            function validate_email(){
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if(jQuery("#semail").val() == "") {
                    email.addClass("error");
                    email_error.text("Please provide your email address");
                    email_error.addClass("error1");
                    return false;
                } else if(!emailReg.test(jQuery("#semail").val())) {
                    email.addClass("error");
                    email_error.text("Please provide valid email address");
                    email_error.addClass("error1");
                    return false;
                } else {
                    email.removeClass("error");
                    email_error.text("");
                    email_error.removeClass("error1");
                    return true;
                }
            }                        
            email.blur(validate_email);
            email.keyup(validate_email); 
                                                                                                                                                                                
            var message = jQuery("#message");
            var message_error = jQuery('#message_error');
            function validate_message(){
                if(message.val() == ''){
                    message.addClass("error");
                    message_error.text("Please enter description");
                    message_error.addClass("error1");
                    return false;
                }
                else{
                    message.removeClass("error");
                    message_error.text("");
                    message_error.removeClass("error1");
                    return true;
                } 
            }
            message.blur(validate_message);
            message.keyup(validate_message);
                                                         
            jQuery('#inquiry').submit(function()
            {  
                if(validate_name() && validate_email() && validate_message())
                {  
                    var captcha_val = jQuery('#captcha').val();
                    var get_captcha = jQuery('#capcode').val(); 
                    if(captcha_val === get_captcha){
                        return true;       
                    }else{
                        alert("Enter Valid Captcha");
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            });
        });           
                                                                    
    </script>
    <?php
    if (isset($_POST['submit'])):
        global $post, $wpdb, $inquiry_tbl_name;
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $messate = $_POST['message'];
        $validate = $_POST['form_validate'];
        $sql = "SELECT * FROM $inquiry_tbl_name WHERE form_valid = $validate";
        $valid_query = $wpdb->get_row($sql);
        $listing_id = $post->ID;
        $listing_author = $post->post_author;
        $listing_title = $post->post_title;
        $date = date("Y-m-d H:i:s");
        $success = '';
        if (!$valid_query) {
            if ($name != '' && $messate != '') {
                $wpdb->insert(
                        $inquiry_tbl_name, array(
                    'listing_author' => $listing_author,
                    'listing_id' => $listing_id,
                    'user_name' => $name,
                    'email' => $email,
                    'phone_no' => $contact,
                    'message' => $messate,
                    'listing_title' => $listing_title,
                    'inquiry_date' => $date,
                    'form_valid' => $validate
                        )
                );
                $success = "Your Message Submitted - Thank You";
            }
            $email_message .="Listing Title: " . $listing_title . "\n";
            $email_message .="User Name: " . $name . "\n";
            $email_message .="Message: " . $messate . "\n";
            $email_message .="Email: " . $email . "\n";
            $email_message .="Contact Number: " . $contact . "\n";
            $to = get_the_author_meta('user_email', $post->post_author);
            $subject = 'Your listing subscription notification';
            $headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            wp_mail($to, $subject, $email_message, $headers);
        }
    endif;

    function geocraft_captcha1() {
        return rand(0, 9);
    }

    function geocraft_captcha2() {
        return rand(0, 9);
    }

    $captcha_value1 = geocraft_captcha1();
    $captcha_value2 = geocraft_captcha2();
    $geocraft_sbs_captcha = $captcha_value1 + $captcha_value2;
    ?>
    <div class="sidebar">
        <div id="inquiry_form">
            <div class="inquiry_form_title">
                <!--                <h6>Contact this business</h6>-->
            </div>
            <form method="post" id="inquiry" name="inquiry">
                <div class="field"><input placeholder="<?php echo LEADN; ?>" id="name" type="text" name="name" value=""/><span id="name_error"></span></div>
                <div class="field"><input placeholder="<?php echo LEADE; ?>" id="semail" type="text" name="email" value=""/><span id="email_error"></span></div>
                <div class="field"><input placeholder="<?php echo LEADP; ?>" type="text" name="contact" value=""/></div>
                <div class="field"><textarea placeholder="<?php echo LEADM; ?>" id="message" name="message"></textarea><span id="message_error"></span></div>
                <input type="hidden"  name="captcha" id="captcha" value="<?php echo $geocraft_sbs_captcha; ?>"/>
                <input type="text" placeholder="<?php echo LEADCP; ?>"  name="capcode" id="capcode"  value=""/>
                <span style="display:block; 
                      background:#000;
                      color: #fff;
                      margin-bottom: 10px; 
                      width: 40px; 
                      text-align: center;
                      -webkit-border-radius: 3px;
                      -moz-border-radius: 3px;
                      border-radius: 3px; " class="captcha_img"><?php echo $captcha_value1 . '+' . $captcha_value2; ?></span>	
                <input type="hidden" name="form_validate" value="<?php echo rand(); ?>"/>
                <input id="submit" type="submit" name="submit" value="send"/>
            </form>
            <?php
            if (isset($_POST['submit'])) {
                echo $success;
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Function Name: user_inquiry()
 * Description: To delete user's feedback
 * 
 * @global type $wpdb
 * @global type $inquiry_tbl_name
 * @global type $current_user
 * @global type $wpdb
 * @global type $inquiry_tbl_name 
 */
function user_inquiry() {
    if (isset($_REQUEST['uid']) && $_REQUEST['uid'] != '') {
        $id = $_REQUEST['uid'];
        global $wpdb, $inquiry_tbl_name;
        $query = "DELETE FROM $inquiry_tbl_name WHERE ID = $id";
        $wpdb->query($query);
    }
    ?>
    <table id="tblspacer" class="widefat fixed">

        <thead>
            <tr>
                <th scope="col"><?php echo ID; ?></th>
                <th scope="col"><?php echo NAME; ?></th>
                <th scope="col"><?php echo EMAIL; ?></th>
                <th scope="col"><?php echo CONTACT_NUM; ?></th>
                <th scope="col" style="width:125px;"><?php echo MESSAGE; ?></th>
                <th scope="col"><?php echo LISTING_TITLE; ?></th> 
                <th scope="col"><?php echo ACTION; ?></th> 
            </tr>
        </thead>
        <?php
        global $current_user;
        $user_ID = $current_user->ID;
        global $wpdb, $inquiry_tbl_name;
        $query = "SELECT * FROM $inquiry_tbl_name";
        $results = $wpdb->get_results($query);
        if ($results):
            ?>
            <tbody id="trans_list">
                <?php
                foreach ($results as $result):
                    if ($user_ID == $result->listing_author || current_user_can('create_users')) {
                        ?>
                        <tr>
                            <td><?php echo $result->ID; ?></td>
                            <td><?php echo $result->user_name; ?></td>
                            <td><a target="_blank" href="mailto:<?php echo $result->email; ?>"><?php echo $result->email; ?></a></td>
                            <td><?php echo $result->phone_no; ?></td>
                            <td><?php echo $result->message; ?></td>
                            <td><?php echo $result->listing_title; ?></td>
                            <td><a href="<?php echo admin_url("admin.php?page=inquiry&uid=$result->ID"); ?>"><?php echo DELETE; ?></a></td>
                        </tr>
                        <?php
                    }
                endforeach;
                ?>
            </tbody>
        <?php else: ?>
            <tr>
                <td colspan="7">No inquiries found.</td>
            </tr>
        <?php endif; ?>

    </table> <!-- this is ok -->					
    <?php
}

/**
 * Function Name: delete_dummy_data()
 * Description: To delete the dummy data
 * 
 * @global type $wpdb 
 */
function delete_dummy_data() {
    global $wpdb;
    $productArray = array();
    $pids_sql = "SELECT $wpdb->postmeta.post_id , $wpdb->postmeta.meta_id , $wpdb->postmeta.meta_key FROM  $wpdb->postmeta
                WHERE
                $wpdb->postmeta.meta_key = 'geocraft_dummy_content'
                AND $wpdb->postmeta.meta_value = 1";
    $pids_info = $wpdb->get_results($pids_sql);
    foreach ($pids_info as $pids_info_obj) {
        wp_delete_post($pids_info_obj->post_id);
    }
}

/**
 * Function Name: geocraft_dummydata_notify()
 * Description: To insert and delete dummy data
 * 
 * @global type $wpdb 
 */
function geocraft_dummydata_notify() {
    global $wpdb;
    if (strstr($_SERVER['REQUEST_URI'], 'themes.php') && $_REQUEST['template'] == '' && $_GET['page'] == '') {

        if ($_REQUEST['dummy'] == 'delete') {
            delete_dummy_data();
            $dummy_deleted = '<p><b>' . DUMMY_DT_DLT . '</b></p>';
        }
        if ($_REQUEST['dummy_insert']) {
            include_once (CONTROLPATH . 'install_data.php'); //Install dummy data
        }
        if ($_REQUEST['activated'] == 'true') {
            $theme_activate_success = '<p class="message">' . THEME_ACTIVATED . '</p>';
        }
        $post_counts = $wpdb->get_var("SELECT count($wpdb->postmeta.post_id) FROM $wpdb->postmeta
                                        WHERE
                                        $wpdb->postmeta.meta_key = 'geocraft_dummy_content'
                                        AND $wpdb->postmeta.meta_value = 1");
        if ($post_counts > 0) {
            $dummy_data_notify = '<p> <b>' . SAMPLE_DT_PAPU . '</b><p>';
            $button = '<a class="btn_delete" href="' . admin_url('/themes.php?dummy=delete') . '">' . YES_DEL . '</a>';
        } else {
            $dummy_data_notify = '<p> <b>' . LIKE_TOP_POPULATED . '</b></p>';
            $button = '<a class="btn_insert" href="' . admin_url('/themes.php?dummy_insert=1') . '">Yes Insert Sample Data</a>';
        }
        $dummy_msg = "<div class='btn'>$button</div>";
        ?>
        <style type="text/css">
            .dummy_data_notify{
                background: #f1f1f1;
                border:1px solid #089bd0;
                margin-top: 20px;
                width:700px;
                padding-left: 20px;
                color: #282829;
                height:100px;
                position:relative;
                margin-bottom:25px;
            }
            .dummy_data_notify .btn{
                background: url('<?php echo TEMPLATEURL . '/images/dummy_msg.png'; ?>') no-repeat; 
                width:276px;
                height:58px;
                position:absolute;
                bottom:-13px;
                text-align:center;
                right:15px;
            }
            .dummy_data_notify .btn a{
                color: #000;
                display:block;
                text-decoration:none;
                margin-top:22px;
                font-size:22px;
                text-shadow:0 1px 0 #3fc6f3;
            }
        </style>
        <?php
        echo '<div class="dummy_data_notify"> ' . $theme_activate_success . $dummy_deleted . $dummy_data_notify . $dummy_msg . '</div>';
    }
}

add_action('admin_notices', 'geocraft_dummydata_notify');

/**
 * Function Name: custom_search()
 * Description: returns results from search or search location 
 * @global type $wpdb
 * @param type $search_string
 * @param type $search_loc
 * @return type 
 */
function custom_search($search_string, $search_loc) {
    global $wpdb;
    $meta_key = 'geo_address';
    $meta_key2 = 'geocraft_listing_type';
    $meta_val = 'pro';
    $search_string = stripslashes($search_string);
    $search_loc = stripslashes($search_loc);
    $post_type = POST_TYPE;
    $n = '%';
    $query_field .= "(($wpdb->posts.post_title LIKE '{$n}{$search_string}{$n}')";
    $query_field .= "OR";
    $query_field .= "($wpdb->posts.post_content LIKE '{$n}{$search_string}{$n}'))";
    $query_field .= "AND";
    $query_field .= "(($wpdb->postmeta.meta_key = '$meta_key')";
    $query_field .= "AND";
    $query_field .= "($wpdb->postmeta.meta_value LIKE '{$n}{$search_loc}{$n}')";
    $query_field .= "AND ($wpdb->posts.post_status = 'publish')";
    $query_field .= "AND";
    $query_field .= "($wpdb->posts.post_type = '$post_type'))";
    $sql = "SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta where ({$query_field})";
    $query = $wpdb->get_results($sql);
    return $query;
}

/**
 * Function Name: custom_search_location()
 * Description: returns results from location
 * @global type $wpdb
 * @param type $search_string
 * @param type $search_loc
 * @return type 
 */
function custom_search_location($search_loc) {
    global $wpdb;
    $meta_key = 'geo_address';
    $meta_key2 = 'geocraft_listing_type';
    $meta_val = 'pro';
    $search_string = stripslashes($search_string);
    $search_loc = stripslashes($search_loc);
    $post_type = POST_TYPE;
    $n = '%';
    $query_field .= "($wpdb->postmeta.meta_key = '$meta_key')";
    $query_field .= "AND";
    $query_field .= "($wpdb->postmeta.meta_value LIKE '{$n}{$search_loc}{$n}')";
    $query_field .= "AND ($wpdb->posts.post_status = 'publish')";
    $query_field .= "AND";
    $query_field .= "($wpdb->posts.post_type = '$post_type')";
    $sql = "SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta where ({$query_field})";
    $query = $wpdb->get_results($sql);
    return $query;
}

/**
 * Function Name: get_date_time_difference()
 * Description: returns differences between two dates
 * @param type $start_date - last post date
 * @param type $current_date - current date
 * @return type array
 */
function get_date_time_difference($start_date, $current_date) {
    $diffrence = abs(strtotime($current_date) - strtotime($start_date));
    $years = floor($diffrence / (365 * 60 * 60 * 24));
    $months = floor(($diffrence - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diffrence - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($diffrence - $years * (365 * 60 * 60 * 24) - $months * (30 * 60 * 60 * 24) - $days * (60 * 60 * 24)) / (60 * 60));
    $mins = floor(($diffrence - ($years * (365 * 60 * 60 * 24)) - $months * (30 * 60 * 60 * 24) - ($days * (60 * 60 * 24)) - ($hours * (60 * 60))) / 60); #floor($difference / 60);
    $secs = floor(($diffrence - ($years * (365 * 60 * 60 * 24)) - $months * (30 * 60 * 60 * 24) - ($days * (60 * 60 * 24)) - ($hours * (60 * 60)) - ($mins * 60))); #floor($difference / 60);

    $dateExtract = array(
        'year' => $years,
        'months' => $months,
        'days' => $days,
        'hours' => $hours,
        'mins' => $mins,
        'secs' => $secs
    );

    return $dateExtract;
}

function get_category_list() {
    global $wpdb;
    $taxonomy = CUSTOM_CAT_TYPE;
    $table_prefix = $wpdb->prefix;
    $wpcat_id = NULL;
    //Fetch category                          
    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");

    $wpcategories = array_values($wpcategories);
    if ($wpcategories) {
        echo "<ul class=\"select_cat\">";
        if ($taxonomy == CUSTOM_CAT_TYPE) {
            ?>
            <li><label><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo SLT_ALL; ?></li>

            <?php
        }
        foreach ($wpcategories as $wpcat) {
            $counter++;
            $termid = $wpcat->term_id;
            $name = $wpcat->name;
            $termprice = $wpcat->term_price;
            $tparent = $wpcat->parent;
            ?>
            <li><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $termid; ?>" class="checkbox" /><?php echo $name; ?></label></li>
            <?php
        }
        echo "</ul>";
    }
}

/**
 * Function Name: get_post_date_gmt()
 * Description: returns post date gmt by post id
 * @global type $wpdb
 * @param type $post_id
 * @return type date
 */
function get_post_date_gmt($post_id) {
    global $wpdb, $expiry_tbl_name;
    $sql = "SELECT " . $expiry_tbl_name . ".listing_date FROM " . $expiry_tbl_name . " WHERE  " . $expiry_tbl_name . ".pid = $post_id";
    $query = $wpdb->get_row($sql);
    $last_post_date = $query->listing_date;
    return $last_post_date;
}

/**
 * Function : get_billing_period()
 * Description: Returns total days of billing cycle.
 * @param type $first_billing_per - first billing period quantity
 * @param type $first_billing_cycle - first billing cycle day, month, year
 * @param type $second_billing_per - second billing period quantity
 * @param type $second_billing_cycle - second billing cycle day, month, year
 * @return type - total billing period
 */
function get_billing_period($first_billing_per, $first_billing_cycle, $second_billing_per, $second_billing_cycle) {
    if ($first_billing_cycle == 'M'):
        if ($second_billing_cycle == 'M'):
            $rvalidity = ($first_billing_per * 30) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $rvalidity = ($first_billing_per * 30) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $rvalidity = ($first_billing_per * 30) + ($second_billing_per);
        endif;
    elseif ($first_billing_cycle == 'Y'):
        if ($second_billing_cycle == 'M'):
            $rvalidity = ($first_billing_per * 365) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $rvalidity = ($first_billing_per * 365) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $rvalidity = ($first_billing_per * 365) + ($second_billing_per);
        endif;
    elseif ($first_billing_cycle == 'D'):
        if ($second_billing_cycle == 'M'):
            $rvalidity = ($first_billing_per) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $rvalidity = ($first_billing_per) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $rvalidity = ($first_billing_per) + ($second_billing_per);
        endif;
    endif;
    return $rvalidity;
}

/**
 * Function: get_billing_period_by()
 * Description: Returns array of days, months and years billing cycle
 * @param type $first_billing_per
 * @param type $first_billing_cycle
 * @param type $second_billing_per
 * @param type $second_billing_cycle
 * @return type 
 */
function get_billing_period_by($first_billing_per, $first_billing_cycle, $second_billing_per, $second_billing_cycle) {
    $days = 0;
    $months = 0;
    $years = 0;
    $billing_period = array();
    if ($first_billing_cycle == 'M'):
        if ($second_billing_cycle == 'M'):
            $months += ($first_billing_per * 30) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $years += ($first_billing_per * 30) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $days += ($first_billing_per * 30) + ($second_billing_per);
        endif;
    elseif ($first_billing_cycle == 'Y'):
        if ($second_billing_cycle == 'M'):
            $months += ($first_billing_per * 365) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $years += ($first_billing_per * 365) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $days += ($first_billing_per * 365) + ($second_billing_per);
        endif;
    elseif ($first_billing_cycle == 'D'):
        if ($second_billing_cycle == 'M'):
            $months += ($first_billing_per) + ($second_billing_per * 30);
        elseif ($second_billing_cycle == 'Y'):
            $years += ($first_billing_per) + ($second_billing_per * 365);
        elseif ($second_billing_cycle == 'D'):
            $days += ($first_billing_per) + ($second_billing_per);
        endif;
    endif;
    $months = $months / 30;
    $years = $years / 365;
    $billing_period['days'] = $days;
    $billing_period['months'] = $months;
    $billing_period['years'] = $years;
    return $billing_period;
}

/**
 * Function: get_day_difference($start_date, $end_date)
 * Description: Returns total days of start date to end date
 * @param type $start_date
 * @param type $end_date
 * @return type 
 */
function get_day_difference($start_date, $end_date) {
    list($date, $time) = explode(' ', $start_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $startdate = explode("-", $date);
    $starttime = explode(":", $time);

    list($date, $time) = explode(' ', $end_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $enddate = explode("-", $date);
    $endtime = explode(":", $time);

    $secons_dif = mktime($endtime[0], $endtime[1], $endtime[2], $enddate[1], $enddate[2], $enddate[0]) -
            mktime($starttime[0], $starttime[1], $starttime[2], $startdate[1], $startdate[2], $startdate[0]);

    //Different can be returned in many formats
    //In Minutes: floor($secons_dif/60);
    //In Hours: floor($secons_dif/60/60);
    //In days: floor($secons_dif/60/60/24);
    //In weeks: floor($secons_dif/60/60/24/7;
    //In Months: floor($secons_dif/60/60/24/7/4);
    //In years: floor($secons_dif/365/60/24);
    //We will return it in hours
    $difference = floor($secons_dif / 60 / 60 / 24);

    return $difference;
}

/**
 * Function: get_minute_difference($start_date, $end_date)
 * Description: Returns total days of start date to end date
 * @param type $start_date
 * @param type $end_date
 * @return type 
 */
function get_minute_difference($start_date, $end_date) {
    list($date, $time) = explode(' ', $start_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $startdate = explode("-", $date);
    $starttime = explode(":", $time);

    list($date, $time) = explode(' ', $end_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $enddate = explode("-", $date);
    $endtime = explode(":", $time);

    $secons_dif = mktime($endtime[0], $endtime[1], $endtime[2], $enddate[1], $enddate[2], $enddate[0]) -
            mktime($starttime[0], $starttime[1], $starttime[2], $startdate[1], $startdate[2], $startdate[0]);

    //Different can be returned in many formats
    //In Minutes: floor($secons_dif/60);
    //In Hours: floor($secons_dif/60/60);
    //In days: floor($secons_dif/60/60/24);
    //In weeks: floor($secons_dif/60/60/24/7;
    //In Months: floor($secons_dif/60/60/24/7/4);
    //In years: floor($secons_dif/365/60/24);
    //We will return it in hours
    $difference_minute = floor($secons_dif / 60);

    return $difference_minute;
}

/**
 * Function: get_recurring_period()
 * Description: Returns total days of recurring period
 * @global type $wpdb
 * @global type $price_table_name
 * @return type 
 */
function get_recurring_period() {
    global $wpdb, $price_table_name;
    $pricesql = "SELECT * FROM $price_table_name WHERE status=1";
    $priceinfo = $wpdb->get_results($pricesql);
    foreach ($priceinfo as $priceinfoObj) {
        if ($priceinfoObj->package_type == 'pkg_recurring') {
            $first_billing_per = $priceinfoObj->first_billing_per;
            $first_billing_cycle = $priceinfoObj->first_billing_cycle;
            $second_billing_per = $priceinfoObj->second_billing_per;
            $second_billing_cycle = $priceinfoObj->second_billing_cycle;

            if (($priceinfoObj->first_billing_per != "" || $priceinfoObj->first_billing_per != 0)) {
                $recurring_period = get_billing_period($first_billing_per, $first_billing_cycle, $second_billing_per, $second_billing_cycle);
            }
        }
    }
    return $recurring_period;
}

/**
 * Function: get_onetime_pkg_price()
 * Description: Returns package cost of one time payment package
 * @global type $wpdb
 * @global type $price_table_name
 * @return type 
 */
function get_onetime_pkg_price() {
    global $wpdb, $price_table_name;
    $sql = "SELECT package_cost FROM $price_table_name WHERE pid = 2";
    $pkg_cost = $wpdb->get_row($sql);
    return $pkg_cost;
}

function get_billingtime() {
    global $wpdb, $price_table_name;
    $sql = "SELECT rebill_time FROM $price_table_name WHERE package_type = 'pkg_recurring'";
    $billing_time = $wpdb->get_results($sql);
    return $billing_time;
}

/**
 * Function : get_currency_symbol()
 * Description: returns currency symbol.
 * @global type $wpdb
 * @global type $currency_tbl_name
 * @return type 
 */
function get_currency_symbol() {
    $currency_code = get_option('currency_code');
    global $wpdb, $currency_tbl_name;
    $sql = "SELECT c_symbol 
        FROM  `$currency_tbl_name` 
        WHERE  `c_code` =  '$currency_code'";
    $symbol = $wpdb->get_row($sql);
    return $symbol->c_symbol;
}

// Update the currency_symbol
$currency_symbol = get_currency_symbol();
if ($currency_symbol):
    update_option('currency_symbol', $currency_symbol);
endif;

function custom_search_by_paidlisting($search, $loc) {
    global $wpdb;
    $n = '%';
    $meta_value = 'pro';
    $meta_key1 = 'geocraft_meta_address';
    $meta_key2 = 'geocraft_listing_type';
    $search_string = stripslashes($search);
    $search_loc = stripslashes($loc);
    $query .= "(($wpdb->posts.post_title LIKE '{$n}{$search_string}{$n}')";
    $query .= "OR";
    $query .= "($wpdb->posts.post_content LIKE '{$n}{$search_string}{$n}')";
    $query .= "AND";
    $query .= "(($wpdb->postmeta.meta_key = '$meta_key1')";
    $query .= "AND";
    $query .= "($wpdb->postmeta.meta_value LIKE '{$n}{$search_loc}{$n}'))";
    $query .= "AND";
    $query .= "(($wpdb->postmeta.meta_key = '$meta_key2')";
    $query .= "AND";
    $query .= "($wpdb->postmeta.meta_value = '$meta_value')))";
    $query .= "AND";
    $query .= "($wpdb->posts.post_status = 'publish')";
    $query .= "AND";
    $query .= "($wpdb->posts.post_type = 'listing')";

    $sql = "SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta where ({$query}) ";
    $query = $wpdb->get_results($sql);

    return ($query);
}

function custom_search_by_freelisting($search, $loc) {
    global $wpdb;
    $n = '%';
    $meta_value = 'free';
    $meta_key1 = 'geocraft_meta_address';
    $meta_key2 = 'geocraft_listing_type';
    $search_string = stripslashes($search);
    $search_loc = stripslashes($loc);
    $query .= "(($wpdb->posts.post_title LIKE '{$n}{$search_string}{$n}')";
    $query .= "OR";
    $query .= "($wpdb->posts.post_content LIKE '{$n}{$search_string}{$n}')";
    $query .= "AND";
    $query .= "(($wpdb->postmeta.meta_key = '$meta_key1')";
    $query .= "AND";
    $query .= "($wpdb->postmeta.meta_value LIKE '{$n}{$search_loc}{$n}'))";
    $query .= "AND";
    $query .= "(($wpdb->postmeta.meta_key = '$meta_key2')";
    $query .= "AND";
    $query .= "($wpdb->postmeta.meta_value = '$meta_value')))";
    $query .= "AND";
    $query .= "($wpdb->posts.post_status = 'publish')";
    $query .= "AND";
    $query .= "($wpdb->posts.post_type = 'listing')";

    $sql = "SELECT DISTINCT $wpdb->posts.* FROM $wpdb->posts INNER JOIN $wpdb->postmeta where ({$query}) ";
    $query = $wpdb->get_results($sql);

    return ($query);
}

function get_custom_field() {
    global $wpdb, $cfield_tbl_name;
    $query = "SELECT * FROM $cfield_tbl_name WHERE is_active = 1 ORDER BY p_order asc,fid asc";
    $fields = $wpdb->get_results($query);
    $returnarray = array();
    foreach ($fields as $field) {
        if ($field->f_type) {
            $options = explode(',', $field->opt_value);
        }
        $custom_fields = array(
            "name" => $field->f_var_nm,
            "title" => $field->f_title,
            "field_category" => $field->field_cate,
            "htmlvar_name" => $field->f_var_nm,
            "default" => $field->dft_value,
            "type" => $field->f_type,
            "description" => $field->f_des,
            "option_values" => $field->opt_value,
            "order" => $field->p_order,
            "is_require" => $field->is_require,
            "is_active" => $field->is_active,
            "show_on_listing" => $field->show_on_detail,
            "show_free" => $field->show_free,
        );
        if ($options) {
            $custom_fields["options"] = $options;
        }
        $returnarray[$field->f_var_nm] = $custom_fields;
    }
    return $returnarray;
}

function gc_renewal_periond() {
    global $wpdb, $price_table_name;
    $sql = "SELECT * FROM $price_table_name";
    $QUERY = $wpdb->get_results($wpdb->prepare($sql));
    foreach ($QUERY as $q) {
        if ($q->package_type == "pkg_free") {
            if ($q->renewal_cycle == "M")
                return $q->renewal_per * 30;
            if ($q->renewal_cycle == "Y")
                return $q->renewal_per * 365;
            if ($q->renewal_cycle == "D")
                return $q->renewal_per;
        }
    }
}

function gc_set_expiry($post_id, $pkg_type = '') {
    global $wpdb, $price_table_name;
    $sql = "SELECT * FROM $price_table_name";
    $QUERY = $wpdb->get_results($wpdb->prepare($sql));
    foreach ($QUERY as $q) {
        $ad_length = $q->validity;
        if ($q->validity_per == 'D') {
            
        } elseif ($q->validity_per == 'M') {
            $ad_length = $ad_length * 30;
        } elseif ($q->validity_per == 'Y') {
            $ad_length = $ad_length * 365;
        }
        if ($q->package_type == 'pkg_free' && $pkg_type == 'pkg_free') {
            $free_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
            add_post_meta($post_id, 'gc_listing_duration', $free_ad_duration, true);
        } elseif ($q->package_type == 'pkg_one_time' && $pkg_type == 'pkg_one_time') {
            $onetime_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
            add_post_meta($post_id, 'gc_listing_duration', $onetime_ad_duration, true);
        } elseif ($q->package_type == 'pkg_recurring' && $pkg_type == 'pkg_recurring') {
            //Calculate first billing period
            $first_billing_cycle = $q->first_billing_per;
            if ($q->first_billing_cycle == 'M') {
                $first_billing_cycle = $q->first_billing_per * 30;
            } elseif ($q->first_billing_cycle == 'Y') {
                $first_billing_cycle = $q->first_billing_per * 365;
            } else {
                $first_billing_cycle = $q->first_billing_per;
            }
            //Calculate second billing period
            $second_billing_cycle = $q->second_billing_per;
            if ($q->second_billing_cycle == 'M') {
                $second_billing_cycle = $q->second_billing_per * 30;
            } elseif ($q->second_billing_cycle == 'Y') {
                $second_billing_cycle = $q->second_billing_per * 365;
            } else {
                $second_billing_cycle = $q->second_billing_per;
            }
            $ad_length = $second_billing_cycle + $first_billing_cycle;
            $recurring_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
            add_post_meta($post_id, 'gc_listing_duration', $recurring_ad_duration, true);
        } elseif ($q->package_type == 'pkg_free' && $pkg_type == '') {
            $admin_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
            add_post_meta($post_id, 'gc_listing_duration', $admin_ad_duration, true);
        }
    }
}

// change ad to draft if it's expired
function gc_has_ad_expired($post_id) {
    global $wpdb;

    $expire_date = get_post_meta($post_id, 'gc_listing_duration', true);

    // debugging variables
    // echo date_i18n('m/d/Y H:i:s') . ' <-- current date/time GMT<br/>';
    // echo $expire_date . ' <-- expires date/time<br/>';
    // if current date is past the expires date, change post status to draft
    if ($expire_date) {
        if (strtotime(date('Y-m-d H:i:s')) > (strtotime($expire_date))) :
            $my_post = array();
            $my_post['ID'] = $post_id;
            $my_post['post_status'] = 'draft';
            wp_update_post($my_post);
            //After expired, listing will be set premium to free listing
            $listing_type = get_post_meta($post_id, 'geocraft_listing_type', true);
            if ($listing_type == "pro") {
                update_post_meta($post_id, 'geocraft_listing_type', 'free');
            }
            return true;
        endif;
    }
}

// shows how much time is left before the ad expires
function gc_timeleft($theTime) {
    $now = strtotime("now");
    $timeLeft = $theTime - $now;

    $days_label = __('days', THEME_SLUG);
    $day_label = __('day', THEME_SLUG);
    $hours_label = __('hours', THEME_SLUG);
    $hour_label = __('hour', THEME_SLUG);
    $mins_label = __('mins', THEME_SLUG);
    $min_label = __('min', THEME_SLUG);
    $secs_label = __('secs', THEME_SLUG);
    $r_label = __('remaining', THEME_SLUG);
    $expired_label = __('This listing has expired', THEME_SLUG);

    if ($timeLeft > 0) {
        $days = floor($timeLeft / 60 / 60 / 24);
        $hours = $timeLeft / 60 / 60 % 24;
        $mins = $timeLeft / 60 % 60;
        $secs = $timeLeft % 60;

        if ($days == 01) {
            $d_label = $day_label;
        } else {
            $d_label = $days_label;
        }
        if ($hours == 01) {
            $h_label = $hour_label;
        } else {
            $h_label = $hours_label;
        }
        if ($mins == 01) {
            $m_label = $min_label;
        } else {
            $m_label = $mins_label;
        }

        if ($days) {
            $theText = $days . " " . $d_label;
            if ($hours) {
                $theText .= ", " . $hours . " " . $h_label . " left";
            }
        } elseif ($hours) {
            $theText = $hours . " " . $h_label;
            if ($mins) {
                $theText .= ", " . $mins . " " . $m_label . " left";
            }
        } elseif ($mins) {
            $theText = $mins . " " . $m_label;
            if ($secs) {
                $theText .= ", " . $secs . " " . $secs_label . " left";
            }
        } elseif ($secs) {
            $theText = $secs . " " . $secs_label . " left";
        }
    } else {
        $theText = $expired_label;
    }
    return $theText;
}

function gc_renew_listing($listing_id) {
    $renewal_period = gc_renewal_periond();

    if ($renewal_period > 0) {
        // set the ad listing expiration date
        $ad_expire_date = date_i18n('m/d/Y H:i:s', strtotime('+' . $renewal_period . ' days'));
        //now update the expiration date on the ad
        update_post_meta($listing_id, 'gc_listing_duration', $ad_expire_date);
        wp_update_post(array('ID' => $listing_id, 'post_date' => date('Y-m-d H:i:s'), 'edit_date' => true, 'post_status' => 'publish'));
        return true;
    }
    //attempt to relist a paid ad
    else {
        return false;
    }
}

function gc_multi_search($sfrom, $location, $limit = null) {
    global $wpdb;
    if (!empty($sfrom) || !empty($location))
        $n = '%';
    $post_type = POST_TYPE;
    $post_status = 'publish';
    $meta_key = "geo_address";
    $$query = '';
    if ($sfrom !== '' && $location == '') {
        $query = "SELECT $wpdb->posts.*  
        FROM
        $wpdb->posts
        INNER JOIN $wpdb->term_relationships
        ON $wpdb->term_relationships.object_id  = $wpdb->posts.ID
        INNER JOIN $wpdb->term_taxonomy 
        ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id	
        INNER JOIN $wpdb->terms 
        ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
        WHERE post_status = '$post_status' AND post_type = '$post_type' AND 
        (post_title like '{$n}$sfrom{$n}' OR post_content like '{$n}$sfrom{$n}' OR $wpdb->terms.name = '$sfrom') GROUP BY $wpdb->posts.ID {$limit}";
    } elseif ($sfrom == '' && $location !== '') {
        $query = "SELECT *
        FROM
        $wpdb->posts
        INNER JOIN $wpdb->postmeta
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id
        WHERE post_status = '$post_status' AND post_type = '$post_type' AND 
        (meta_key = '$meta_key' AND meta_value like '{$n}$location{$n}')
        GROUP BY $wpdb->posts.ID {$limit}";
    } elseif ($sfrom !== '' && $location !== '') {
        $query = "SELECT $wpdb->posts.*
        FROM
        $wpdb->posts
        INNER JOIN $wpdb->postmeta
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id
        INNER JOIN $wpdb->term_relationships
        ON $wpdb->term_relationships.object_id  = $wpdb->posts.ID
        INNER JOIN $wpdb->term_taxonomy 
        ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id	
        INNER JOIN $wpdb->terms 
        ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id 
        WHERE post_status = '$post_status' AND post_type = '$post_type' AND 
        (post_title like '{$n}$sfrom{$n}' OR post_content like '{$n}$sfrom{$n}' OR wp_terms.name = '$sfrom') AND
        (meta_key = '$meta_key' AND meta_value like '{$n}$location{$n}')
         GROUP BY $wpdb->posts.ID {$limit}";
    }
    $results = array();
    $results['result'] = $wpdb->get_results($query);
    $results['query'] = $wpdb->query($query);
    return $results;
}

function gc_insert_spage() {
    global $wpdb;
    $search = GC_SEARCH;
    $sql = "SELECT * from $wpdb->posts WHERE post_name = '$search'";
    $query = $wpdb->get_row($sql);
    if (!$query) {
        $my_page = array(
            'ID' => false,
            'post_type' => 'page',
            'post_name' => GC_SEARCH,
            'ping_status' => 'closed',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            //'post_content' => '[pay-status]',
            'post_title' => __('Search', THEME_SLUG),
            'post_excerpt' => ''
        );
        $pages = wp_insert_post($my_page);
        if ($pages) {
            update_post_meta($pages, '_wp_page_template', 'template_search.php');
        }
    }
}
add_action('init', 'gc_insert_spage');

function upgrade_listing($uid, $total_amount) {
    global $wpdb;
    $post_id = $uid;
    //Updating listing type
    if ($total_amount > 0) {
        update_post_meta($post_id, 'geocraft_listing_type', 'pro');
    } else {
        update_post_meta($post_id, 'geocraft_listing_type', 'free');
    }
}