<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package InkThemes
 * 
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">         
            <?php
            /* Queue the first post, that way we know
             * what date we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop
             * properly with a call to rewind_posts().
             */
            if (have_posts())
                the_post();
            ?>
            <h1>
                <?php if (is_day()) : ?>
                    <?php printf(__(DLY_ARC . ' %s', THEME_SLUG), get_the_date()); ?>
                <?php elseif (is_month()) : ?>
                    <?php printf(__(MTHL_ARC . ' %s', THEME_SLUG), get_the_date('F Y')); ?>
                <?php elseif (is_year()) : ?>
                    <?php printf(__(YRL_ARC . ' %s', THEME_SLUG), get_the_date('Y')); ?>
                <?php else : ?>
                    <?php
                    $search = $_REQUEST['s'];
                    $search_location = $_REQUEST['location'];
                    if ($_REQUEST['location'] == "") {
                        printf(__(U_SRC_FR . ' %s', THEME_SLUG), '' . get_search_query() . '');
                    } else {
                        printf(__(U_SRC_FR . ' %s ' . NEAR . ' %s', THEME_SLUG), '' . get_search_query() . '', $search_location);
                    }
                    ?>
                <?php endif; ?>
            </h1>
            <?php
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();
            /* Run the loop for the archives page to output the posts.
             * If you want to overload this in a child theme then include a file
             * called loop-archives.php and that will be used instead.
             */
            if ($search_location == '') {
                get_template_part('loop-blog', POST_TYPE);
            } else {
                get_template_part('loop-search', POST_TYPE);
            }
            ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php
        global $post;
        $post_type = $post->post_type;
        if ($post_type == 'post'):
            get_sidebar('blog');
        elseif ($post_type == POST_TYPE):
            get_sidebar(POST_TYPE);
        elseif ($post_type == 'page'):
            get_sidebar();
        endif;
        ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>