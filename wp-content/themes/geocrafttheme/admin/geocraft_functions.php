<?php
//Load languages file
load_theme_textdomain('geocraft', get_template_directory() . '/lang');
$locale = get_locale();
$locale_file = get_template_directory() . "/lang/$locale.php";
if (is_readable($locale_file))
    require_once($locale_file);

// This theme styles the visual editor with editor-style.css to match the theme style.
function inkthemes_editor_style() {
    add_editor_style();
}

add_action('after_setup_theme', 'inkthemes_editor_style');
/* ----------------------------------------------------------------------------------- */
/* Post Thumbnail Support
  /*----------------------------------------------------------------------------------- */

function inkthemes_post_thumbnail() {
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'inkthemes_post_thumbnail');

function inkthemes_add_thumbnail() {
    add_image_size('post_thumbnail', 472, 280, true);
    add_image_size('home_post_thumbnail', 193, 138, true);
}

add_action('after_setup_theme', 'inkthemes_add_thumbnail');
/* ----------------------------------------------------------------------------------- */
/* Get Category Id from category name
  /*----------------------------------------------------------------------------------- */

function inkthemes_get_category_id($cat_name) {
    $term = get_term_by('name', $cat_name, 'category');
    return $term->term_id;
}

/* ----------------------------------------------------------------------------------- */
/* Auto Feed Links Support
  /*----------------------------------------------------------------------------------- */

function inkthemes_theme_support() {
    add_theme_support('automatic-feed-links');
}

add_action('after_setup_theme', 'inkthemes_theme_support');
/* ----------------------------------------------------------------------------------- */
/* Custom Menus Function
  /*----------------------------------------------------------------------------------- */

// Add CLASS attributes to the first <ul> occurence in wp_page_menu
function inkthemes_add_menuclass($ulclass) {
    return preg_replace('/<ul>/', '<ul class="ddsmoothmenu">', $ulclass, 1);
}

add_filter('wp_page_menu', 'inkthemes_add_menuclass');

function inkthemes_register_custom_menu() {
    register_nav_menu('custom_menu', __('Main Menu', 'themia'));
}

add_action('after_setup_theme', 'inkthemes_register_custom_menu');

function inkthemes_nav() {
    if (function_exists('wp_nav_menu')) {
        echo '<div id="menu">';
        echo '<a href="#" class="mobile_nav closed">' . PAGE_NAVIGATION . '<span></span></a>';
        wp_nav_menu(array('theme_location' => 'custom_menu', 'menu_class' => 'ddsmoothmenu', 'fallback_cb' => 'inkthemes_nav_fallback'));
        echo "</div>";
    } else {
        inkthemes_nav_fallback();
    }
}

function inkthemes_nav_fallback() {
    ?>
    <div id="menu">
        <ul class="ddsmoothmenu">
            <?php
            $notify_pid = get_option('geo_notify_page');
            $dashboard_pid = get_option('geo_dashboard_page');
            $search = get_option('geo_search_page');
            wp_list_pages("title_li=&show_home=1&sort_column=menu_order&exclude=$notify_pid,$dashboard_pid,$dashboard_pid");
            ?>
        </ul>
    </div>
    <?php
}

function inkthemes_home_nav_menu_items($items) {
    if (is_home()) {
        $homelink = '<li class="current_page_item">' . '<a href="' . home_url('/') . '">' . HOME . '</a></li>';
    } else {
        $homelink = '<li>' . '<a href="' . home_url('/') . '">' . HOME . '</a></li>';
    }
    $items = $homelink . $items;
    return $items;
}

add_filter('wp_list_pages', 'inkthemes_home_nav_menu_items');
/* ----------------------------------------------------------------------------------- */
/* Breadcrumbs Plugin
  /*------------------------------------------------------------------------------------ */

function inkthemes_breadcrumbs() {
    $delimiter = '&raquo;';
    $home = 'Home'; // text for the 'Home' link
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    echo '<div id="crumbs">';
    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
    if (is_category()) {
        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();
        $thisCat = $cat_obj->term_id;
        $thisCat = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);
        if ($thisCat->parent != 0)
            echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
        echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
    } elseif (is_day()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('d') . $after;
    } elseif (is_month()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('F') . $after;
    } elseif (is_year()) {
        echo $before . get_the_time('Y') . $after;
    } elseif (is_single() && !is_attachment()) {
        if (get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } else {
            $cat = get_the_category();
            $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo $before . get_the_title() . $after;
        }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post') {
        $post_type = get_post_type_object(get_post_type());
        echo $before . $post_type->labels->singular_name . $after;
    } elseif (is_attachment()) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID);
        $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
    } elseif (is_page() && !$post->post_parent) {
        echo $before . get_the_title() . $after;
    } elseif (is_page() && $post->post_parent) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb)
            echo $crumb . ' ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
    } elseif (is_search()) {
        echo $before . 'Search results for "' . get_search_query() . '"' . $after;
    } elseif (is_tag()) {
        echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
    } elseif (is_author()) {
        global $author;
        $userdata = get_userdata($author);
        echo $before . 'Articles posted by ' . $userdata->display_name . $after;
    } elseif (is_404()) {
        echo $before . 'Error 404' . $after;
    }
    if (get_query_var('paged')) {
        if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
            echo ' (';
        echo __('Page', 'themia') . ' ' . get_query_var('paged');
        if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
            echo ')';
    }
    echo '</div>';
}

/* ----------------------------------------------------------------------------------- */
/* Function to call first uploaded image in functions file
  /*----------------------------------------------------------------------------------- */

/**
 * This function thumbnail id and
 * returns thumbnail image
 * @param type $iw
 * @param type $ih 
 */
function inkthemes_get_thumbnail($iw, $ih) {
    $permalink = get_permalink($id);
    $thumb = get_post_thumbnail_id();
    $image = inkthemes_thumbnail_resize($thumb, '', $iw, $ih, true, 90);
    if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
        print "<a href='$permalink'><img class='postimg f_thumb' src='$image[url]' width='$image[width]' height='$image[height]' /></a>";
    }
}

/**
 * This function gets image width and height and
 * Prints attached images from the post        
 */
function inkthemes_get_image($width, $height, $class = '', $img_meta = '') {
    $w = $width;
    $h = $height;
    $default = TEMPLATEURL . '/images/default.png';
    global $post, $posts;
//This is required to set to Null
    $img_source = '';
    $permalink = get_permalink();
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    //check wheather img_meta have data or null, if it is not null, image path will me img_meta
    if ($img_meta != '') {
        $img_source = $img_meta;
    } elseif (isset($matches [1] [0])) {
        $img_source = $matches [1] [0];
    }
    $img_path = inkthemes_image_resize($img_source, $width, $height, true, 90);
    if (!empty($img_path[url])) {
        print "<a href='$permalink'><img src='$img_path[url]' class='postimg f_thumb $class' alt='Post Image'/></a>";
    } else {
        print "<a href='$permalink'><img src='$default' class='postimg f_thumb $class' alt='Post Image'/></a>";
    }
}
function search_get_image($width, $height, $class = '', $img_meta = '',$perma_link = '') {
    $w = $width;
    $h = $height;
    $default = TEMPLATEURL . '/images/default.png';
    global $post, $posts;
//This is required to set to Null
    $img_source = '';
    $permalink = $perma_link;
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    //check wheather img_meta have data or null, if it is not null, image path will me img_meta
    if ($img_meta != '') {
        $img_source = $img_meta;
    } elseif (isset($matches [1] [0])) {
        $img_source = $matches [1] [0];
    }
    $img_path = inkthemes_image_resize($img_source, $width, $height, true, 90);
    if (!empty($img_path[url])) {
        print "<a href='$permalink'><img src='$img_path[url]' class='postimg f_thumb $class' alt='Post Image'/></a>";
    } else {
        print "<a href='$permalink'><img src='$default' class='postimg f_thumb $class' alt='Post Image'/></a>";
    }
}
/* ----------------------------------------------------------------------------------- */
/* Function to change the excerpt length
  /*----------------------------------------------------------------------------------- */

function inkthemes_excerpt_length($length) {
    return 50;
}

add_filter('excerpt_length', 'inkthemes_excerpt_length');
/* ----------------------------------------------------------------------------------- */
/* Attachment Page Design
  /*----------------------------------------------------------------------------------- */
//For Attachment Page
if (!function_exists('inkthemes_posted_in')) :

    /**
     * Prints HTML with meta information for the current post (category, tags and permalink).
     *
     */
    function inkthemes_posted_in() {
        // Retrieves tag list of current post, separated by commas.
        $tag_list = get_the_tag_list('', ', ');
        if ($tag_list) {
            $posted_in = THIS_ENTRY_POSTEDIN;
        } elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
            $posted_in = THIS_ENTRY_POSTEDIN2;
        } else {
            $posted_in = BOOKMARK_THE;
        }
        // Prints the string, replacing the placeholders.
        printf(
                $posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0')
        );
    }

endif;
?>
<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if (!isset($content_width))
    $content_width = 472;
?>
<?php

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override inkthemes_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
function inkthemes_widgets_init() {

    // Area 1, located at the home page sidebar.
    register_sidebar(array(
        'name' => HOME_PAGE_WIDGET_AREA,
        'id' => 'home-widget-area',
        'description' => HOME_PAGE_WIDGET_AREA,
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    // Area 2, located at the place page sidebar.
    register_sidebar(array(
        'name' => LISTING_WIDGET_AREA,
        'id' => 'listing-widget-area',
        'description' => LISTING_WIDGET_AREA,
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    // Area 3, located at the blog page sidebar.
    register_sidebar(array(
        'name' => BLOG_WIDGET_ARA,
        'id' => 'blog-widget-area',
        'description' => BLOG_WIDGET_ARA,
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    //Area 4 Default widget area for pages
    register_sidebar(array(
        'name' => PAGE_WIDGET_AREA,
        'id' => 'pages-widget-area',
        'description' => DEFAULT_PAGES,
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    // Area 5, located at the contact page sidebar.
    register_sidebar(array(
        'name' => CONTACT_PAGE_WIDGET,
        'id' => 'contact-widget-area',
        'description' => CONTACT_PAGE_WIDGET,
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    // Area 6, located in the footer. Sample content by default.
    register_sidebar(array(
        'name' => FIRST_FOOTER_WIDGET,
        'id' => 'first-footer-widget-area',
        'description' => FIRST_FOOTER_WIDGET,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
    ));
    // Area 7, located in the footer. Sample content by default.
    register_sidebar(array(
        'name' => SECOND_FOOTER_WIDGET,
        'id' => 'second-footer-widget-area',
        'description' => SECOND_FOOTER_WIDGET,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
    ));
    // Area 8, located in the footer. Sample content by default.
    register_sidebar(array(
        'name' => THIRD_FOOTER_WIDGET,
        'id' => 'third-footer-widget-area',
        'description' => THIRD_FOOTER_WIDGET,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
    ));
    // Area 9, located in the footer. Sample content by default.
    register_sidebar(array(
        'name' => FOURTH_FOOTER_WIDGET,
        'id' => 'fourth-footer-widget-area',
        'description' => FOURTH_FOOTER_WIDGET,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
    ));
}

/** Register sidebars by running inkthemes_widgets_init() on the widgets_init hook. */
add_action('widgets_init', 'inkthemes_widgets_init');
?>
<?php

/**
 * inkthemes_inkthemes_pagination
 *
 */
function inkthemes_pagination($pages = '', $range = 2) {
    $showitems = ($range * 2) + 1;
    global $paged;
    if (empty($paged))
        $paged = 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    if (1 != $pages) {
        echo "<ul class='paging'>";
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link(1) . "'>&laquo;</a></li>";
        if ($paged > 1 && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a></li>";
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                echo ($paged == $i) ? "<li><a href='" . get_pagenum_link($i) . "' class='current' >" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a></li>";
            }
        }
        if ($paged < $pages && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a></li>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($pages) . "'>&raquo;</a></li>";
        echo "</ul>\n";
    }
}
?>
<?php

/////////Theme Options
/* ----------------------------------------------------------------------------------- */
/* Add Favicon
  /*----------------------------------------------------------------------------------- */
function inkthemes_childtheme_favicon() {
    if (geocraft_get_option('inkthemes_favicon') != '') {
        echo '<link rel="shortcut icon" href="' . geocraft_get_option('inkthemes_favicon') . '"/>' . "\n";
    } else {
        ?>
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" />
        <?php
    }
}

add_action('wp_head', 'inkthemes_childtheme_favicon');
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ---------------------------------------------------------------------------------- */

function inkthemes_childtheme_analytics() {
    $output = geocraft_get_option('inkthemes_analytics');
    if ($output <> "")
        echo stripslashes($output) . "\n";
}

add_action('wp_head', 'inkthemes_childtheme_analytics');
/* ----------------------------------------------------------------------------------- */
/* Custom CSS Styles */
/* ----------------------------------------------------------------------------------- */

function inkthemes_of_head_css() {
    $output = '';
    $custom_css = geocraft_get_option('customcss');
    if ($custom_css <> '') {
        $output .= $custom_css . "\n";
    }
    // Output styles
    if ($output <> '') {
        $output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
        echo $output;
    }
}

add_action('wp_head', 'inkthemes_of_head_css');

/* ----------------------------------------------------------------------------------- */
/* Styles Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_add_stylesheet() {
    wp_enqueue_style('shortcodes', get_template_directory_uri() . "/css/shortcode.css", '', '', 'all');
    wp_enqueue_style('slider', get_template_directory_uri() . "/css/slider.css", '', '', 'all');
    wp_enqueue_style('media-screen', get_template_directory_uri() . "/css/media-screen.css", '', '', 'all');
    wp_enqueue_style('coloroptions', get_template_directory_uri() . "/css/color/" . geocraft_get_option('altstylesheet') . ".css", '', '', 'all');
    wp_enqueue_style('custom', get_template_directory_uri() . "/css/custom.css", '', '', 'all');
	if(geocraft_get_option('lan_stylesheets') == 'rtl'){
	 wp_enqueue_style('rtl', get_template_directory_uri() . "/css/color/".geocraft_get_option('lan_stylesheets').".css", '', '', 'all');
	 }
}

add_action('init', 'inkthemes_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script(THEME_SLUG . '-ddsmoothmenu', get_template_directory_uri() . '/js/ddsmoothmenu.js', array('jquery'));
        wp_enqueue_script(THEME_SLUG .'_jquery-ui', get_template_directory_uri() . '/js/jquery-ui-personalized-1.5.2.packed.js', array('jquery'));
        wp_enqueue_script(THEME_SLUG .'_flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'));
        wp_enqueue_script(THEME_SLUG . '-crousel', get_template_directory_uri() . '/js/simple.carousel.js', array('jquery'));
        wp_enqueue_script(THEME_SLUG . '-modernizr', get_template_directory_uri() . '/js/modernizr.js', array('jquery'));
        wp_enqueue_script(THEME_SLUG . '-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'));
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_wp_enqueue_scripts');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */

function colorway_custom_jquery() {
    wp_enqueue_script(THEME_SLUG . 'mobile-menu', get_template_directory_uri() . "/js/mobile-menu.js", array('jquery'));
}

add_action('wp_footer', 'colorway_custom_jquery');

//Enqueue comment thread js
function inkthemes_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_enqueue_scripts');

function geocraft_bodybg() {
    if (geocraft_get_option('bodybg') != '') {
        ?>
        <style type="text/css">
            body{
                background-image: url('<?php echo geocraft_get_option('bodybg'); ?>');
            }
        </style>
        <?php
    }
}

add_action('wp_head', 'geocraft_bodybg');
//Custom excerpt function
function inkthemes_custom_trim_excerpt($length) {
    global $post;
    $explicit_excerpt = $post->post_excerpt;
    if ('' == $explicit_excerpt) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    } else {
        $text = apply_filters('the_content', $explicit_excerpt);
    }
    $text = strip_shortcodes($text); // optional
    $text = strip_tags($text);
    $excerpt_length = $length;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words) > $excerpt_length) {
        array_pop($words);
        array_push($words, '[&hellip;]');
        $text = implode(' ', $words);
        $text = apply_filters('the_excerpt', $text);
    }
    return $text;
}

//Excerpt length for posts
function post_type_excerpt_length($length) {
    global $post;
    if ($post->post_type == POST_TYPE) {
        return 15;
    } else {
        return 50;
    }
}

add_filter('excerpt_length', 'post_type_excerpt_length');

//Preventing access to admin for other users
add_action('admin_init', 'prevent_admin_access', 1);

function prevent_admin_access() {
    $isAjax = (defined('DOING_AJAX') && true === DOING_AJAX) ? true : false;

    if (!$isAjax) {
        if (!current_user_can('administrator')) {
            wp_die("You are not allowed to access this part of the site");
        }
    }
}

function replace_howdy($wp_admin_bar) {
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle = str_replace('Howdy,', 'Logged in as', $my_account->title);
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $newtitle,
    ));
}

add_filter('admin_bar_menu', 'replace_howdy', 25);

//Front Page Rename
$get_status = geocraft_get_option('re_nm');
$get_file_ac = TEMPLATEPATH . '/front-page.php';
$get_file_dl = TEMPLATEPATH . '/front-page-hold.php';
//True Part
if ($get_status === 'off' && file_exists($get_file_ac)) {
    rename("$get_file_ac", "$get_file_dl");
}
//False Part
if ($get_status === 'on' && file_exists($get_file_dl)) {
    rename("$get_file_dl", "$get_file_ac");
}
/**
 * Function: geocraft_get_terms_dropdown()
 * Description : returns costom taxonomy dropdown list
 * @param type $taxonomies
 * @param type $args
 * @return string 
 */
function geocraft_get_terms_dropdown($taxonomies, $args){
	$myterms = get_terms($taxonomies, $args);
	$output ="<select id='cat'>";
	foreach($myterms as $term){
		$root_url = get_bloginfo('url');
		$term_taxonomy=$term->taxonomy;
		$term_slug=$term->slug;
		$term_name =$term->name;
		$link = $root_url.'/'.$term_taxonomy.'/'.$term_slug;
		$output .="<option value='".$term_slug."'>".$term_name."</option>";
	}
	$output .="</select>";
return $output;
}
//Set sticky post to run homepage and all listing page
if(!get_option('sticky_posts')){
update_option('sticky_posts', array(100000000000));
}

function my_function_admin_bar(){ return false; }
  add_filter( 'show_admin_bar' , 'my_function_admin_bar');
