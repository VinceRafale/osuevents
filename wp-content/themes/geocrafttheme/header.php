<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title>
            <?php
            /*
             * Print the <title> tag based on what is being viewed.
             */
            global $page, $paged;
            wp_title('|', true, 'right');
            // Add the blog name.
            bloginfo('name');
            // Add the blog description for the home/front page.
            $site_description = get_bloginfo('description', 'display');
            if ($site_description && ( is_home() || is_front_page() ))
                echo " | $site_description";
            // Add a page number if necessary:
            if ($paged >= 2 || $page >= 2)
                echo ' | ' . sprintf(__('Page %s', THEME_SLUG), max($paged, $page));
            ?>
        </title>
        <?php if (is_front_page()) { ?>
            <?php if (geocraft_get_option('inkthemes_keyword') != '') { ?>
                <meta name="keywords" content="<?php echo geocraft_get_option('inkthemes_keyword'); ?>" />
            <?php
            } else {
                
            }
            ?>
            <?php if (geocraft_get_option('inkthemes_description') != '') { ?>
                <meta name="description" content="<?php echo geocraft_get_option('inkthemes_description'); ?>" />
            <?php
            } else {
                
            }
            ?>
            <?php if (geocraft_get_option('inkthemes_author') != '') { ?>
                <meta name="author" content="<?php echo geocraft_get_option('inkthemes_author'); ?>" />
            <?php
            } else {
                
            }
            ?>
        <?php } ?>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />       
<?php
wp_head();
?>
    </head>
    <body <?php body_class() ?>>
        <!--Start top strip-->
        <div class="top_strip">
            <div class="container_24">
                <div class="grid_24">
                    <div class="menu">
                    <?php do_action('geocraft_auth_menu'); ?>
                    </div>
                    <div class="clear"></div>               
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--End top strip-->
        <div class="clear"></div>
        <!--Start Header Wrapper-->
        <div class="header_wrapper">
            <div class="header">
                <!--Start Container-->
                <div class="container_24">
                    <div class="grid_24">
                        <div class="logo_wrapper grid_14 alpha">
                            <div class="logo"> <a href="<?php echo home_url(); ?>"><img src="<?php if (geocraft_get_option('inkthemes_logo') != '') { ?><?php echo geocraft_get_option('inkthemes_logo'); ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /></a></div>
                        </div>
                        <div class="grid_10 omega">
                            <?php 
                            $pkg_cost = get_onetime_pkg_price();
                            $pkg_cost = $pkg_cost->package_cost;
                            $currency_symbol = get_option('currency_symbol');
                            ?>
                            <a class="post_btn" href="<?php echo site_url('/?page_id=' . get_option('geo_submit_listing')); ?>"><span class="btn_left"></span><span class="btn_center"><?php if(geocraft_get_option('post_btn') != ''){ echo  geocraft_get_option('post_btn'); } else { ?>Post Your Business Listing at <?php if($pkg_cost) echo $currency_symbol.$pkg_cost; else echo '$0'; }?></span><span class="btn_right"></span></a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <!--End Container-->
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <!--Start Menu Wrapper-->
            <div class="menu_wrapper">
                <div class="top_arc"></div>
                <div class="menu-container">
                    <div class="container_24">
                        <div class="grid_24">
                        <?php inkthemes_nav(); ?> 
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div class="bottom_arc"></div>
            </div>
            <!--End Menu Wrapper-->
            <div class="clear"></div>
            <!--Start Container-->
            <div class="container_24">
                <div class="grid_24">
                    <?php
                    if (file_exists(TEMPLATE_PATH . '/home_searchform.php')):
                        require_once TEMPLATE_PATH . '/home_searchform.php';
                    endif;
                    ?>
                </div>
                <div class="clear"></div>
            </div>
            <!--End Container-->
            <div class="clear"></div>
        </div>
        <!--End Header Wrapper-->
        <div class="clear"></div>
        <div class="wrapper">
            <!--Start Container-->
            <div class="container_24">
                <div class="grid_24">