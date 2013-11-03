<?php
/**
 * Template Name: Template: Dashboard 
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<?php if (is_user_logged_in()): ?>
    <div class="content_wrapper">
        <div class="grid_16 alpha">
            <div class="content dashboard"> 

                <script type="text/javascript">
                    jQuery(document).ready(function(){
                    jQuery("#tblspacer tr:even").attr("class", "even");
                    jQuery("#tblspacer tr:odd").attr("class", "odd");
                    });
                </script>
                <?php
                //Get style
                dashboard_style();
                //Users listings
                if ((!isset($_REQUEST['action']) && $_REQUEST['action'] != 'edit') || $_REQUEST['ptype'] == 'listing' || $_REQUEST['action'] == 'delete'):
                    global $post;
                    user_listing($post->ID);
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete'):
                        $post_id = $_REQUEST['pid'];
                        wp_delete_post($post_id);
                    endif;
                endif;

                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'):
                    //Edit listing
                    $post_id = $_REQUEST['pid'];
                    edit_listing($post_id);
                endif;
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'review'):
                    //View reviews
                    listing_reviews();
                endif;
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'profile'):
                    edit_profile();
                endif;
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'subscribe'):
                    user_subscription();
                endif;
                if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'expired' || $_REQUEST['action'] == 'renew')):
                    if (isset($_REQUEST['d']) && $_REQUEST['d'] == 'del'):
                        $post_id = $_REQUEST['pid'];
                        wp_delete_post($post_id);
                    endif;
                    expired_listings();
                endif;
                ?>

            </div>
        </div>
        <div class="grid_8 omega">
            <div class="sidebar dashboard">
                <div id="author-info">            
                    <div id="author-avatar"> <?php echo get_avatar($current_user->ID, 40); ?> </div>
                    <!-- #author-avatar -->
                    <div id="author-description">
                        <?php
                        $user_info = get_userdata($current_user->ID);
                        $registered = ($user_info->user_registered . "\n");
                        ?>
                        <h6><?php printf(__('Welcome, %s', THEME_SLUG), $current_user->user_login); ?></h6>
                        <p><?php echo MEMBER_SINCE; ?>&nbsp;<?php
                    echo date("n/j/Y", strtotime($registered));
                        ?></p>
                    </div>
                    <!-- #author-description	-->
                    <div class="clear"></div>
                    <ul class="navigation">
                        <li><a href="<?php echo home_url("/?page_id=" . get_option('geo_submit_listing')); ?>"><?php echo ADD_N_LISTING; ?></a></li>
                        <li><a href="<?php echo home_url("/?page_id=$post->ID&ptype=listing"); ?>"><?php echo V_LISTING; ?></a></li>
                        <li><a href="<?php echo home_url("/?page_id=$post->ID&action=expired"); ?>"><?php echo EX_LISTING; ?></a></li>
                        <li><a href="<?php echo home_url("/?page_id=$post->ID&action=review"); ?>"><?php echo V_REVIEWS; ?></a></li>
                        <li><a href="<?php echo home_url("/?page_id=$post->ID&action=profile"); ?>"><?php echo EDIT_PROFILE; ?></a></li>                    
                        <li><a href="<?php echo home_url("/?page_id=$post->ID&action=subscribe"); ?>"><?php echo LEAD_CAPT; ?></a></li>
                    </ul>
                </div>

                <div class="widget">

                </div>
            </div>
        </div>
    </div>
    <?php
else:
    wp_redirect(home_url());
endif;
?>
<!--End Content Wrapper-->
<?php get_footer(); ?>