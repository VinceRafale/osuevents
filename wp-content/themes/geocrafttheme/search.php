<?php
/**
 * The Search Page.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */
get_header();
?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">
            <?php
            $search = $_REQUEST['s'];
            $search_location = $_REQUEST['location'];
            ?>
            <h1>
                <?php
                if ($_REQUEST['location'] == "") {
                    printf(__(U_SRC_FR . ' %s', THEME_SLUG), '' . get_search_query() . '');
                } else {
                    printf(__(U_SRC_FR . ' %s ' . NEAR . ' %s', THEME_SLUG), '' . get_search_query() . '', $search_location);
                }
                ?>
            </h1>     
            <?php if (have_posts() && $search_location == '') : ?>  
                <!--Start Post-->               
                <?php
                get_template_part('loop');
                ?>
                <!--End Post-->
                <?php inkthemes_pagination(); ?>
            <?php elseif (have_posts() && $search_location != '') : ?>            
                <?php get_template_part('loop-search'); ?>
            <?php else: ?>
                <article id="post-0" class="post no-results not-found">
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <?php echo NTH_FND; ?>
                        </h1>
                    </header>
                    <!-- .entry-header -->
                    <div class="entry-content">
                        <p>
                            <?php echo SRY_NT_FND; ?>
                        </p>
                        <?php get_search_form(); ?>                        
                    </div>
                    <!-- .entry-content -->
                </article>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar(POST_TYPE); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>