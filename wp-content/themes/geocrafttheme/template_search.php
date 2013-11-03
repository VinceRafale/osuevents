<?php
/**
 * Template Name: Template Search
 * 
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">
            <h1>
                <?php
                $sfrom = $_REQUEST['sfrom'];
                $location = $_REQUEST['location'];
                if ($sfrom !== "" && $location == "") {
                    printf(__(U_SRC_FR . ' %s', THEME_SLUG), '' . $sfrom . '');
                } elseif ($sfrom == "" && $location !== "") {
                    printf(__(NEAR . ' %s', THEME_SLUG), $location);
                } elseif ($sfrom !== "" && $location !== "") {
                    printf(__(U_SRC_FR . ' %s ' . NEAR . ' %s', THEME_SLUG), '' . $sfrom . '', $location);
                }
                ?>
            </h1>     
            <?php
            $results = gc_multi_search($sfrom, $location);
            if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
                $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
            } else { // If the pn URL variable is not present force it to be value of page number 1
                $pn = 1;
            }

            $itemsPerPage = get_option('posts_per_page');

            $lastPage = ceil($results['query'] / $itemsPerPage);

            if ($pn < 1) { // If it is less than 1
                $pn = 1; // force if to be 1
            } else if ($pn > $lastPage) { // if it is greater than $lastpage
                $pn = $lastPage; // force it to be $lastpage's value
            }

            $centerPages = "";
            $sub1 = $pn - 1;
            $sub2 = $pn - 2;
            $add1 = $pn + 1;
            $add2 = $pn + 2;
            if ($pn == 1) {
                $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                $centerPages .= '<li> <a href="' . site_url(GC_SEARCH . "/?pn=$add1&sfrom=$sfrom&location=$location") . '">' . $add1 . '</a></li>';
            } else if ($pn == $lastPage) {
                $centerPages .= '<li> <a href="' . site_url(GC_SEARCH . "/?pn=$sub1&sfrom=$sfrom&location=$location") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$sub2&sfrom=$sfrom&location=$location") . '">' . $sub2 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$sub1&sfrom=$sfrom&location=$location") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$add2&sfrom=$sfrom&location=$location") . '">' . $add1 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$add2&sfrom=$sfrom&location=$location") . '">' . $add2 . '</a></li>';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '<li> <a href="' . site_url(GC_SEARCH . "/?pn=$sub1&sfrom=$sfrom&location=$location") . '">' . $sub1 . '</a> </li>';
                $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$add1&sfrom=$sfrom&location=$location") . '">' . $add1 . '</a></li>';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
            $paginationDisplay = "<ul class='paginate'>"; // Initialize the pagination output variable
            if ($lastPage != "1") {
                //$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$previous&sfrom=$sfrom&location=$location") . '">&laquo;</a></li>';
                }
                $paginationDisplay .= $centerPages;
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '<li><a href="' . site_url(GC_SEARCH . "/?pn=$nextPage&sfrom=$sfrom&location=$location") . '">&raquo;</a></li> ';
                }                
            }
            $paginationDisplay .= '</ul>';
            if($pn < 1){
                $paginationDisplay = '';
            }


            $results = gc_multi_search($sfrom, $location, $limit);
            if ($results['result']) {
                foreach ($results['result'] as $q) {
                    $featured_class = '';
                    $is_pro = get_post_meta($q->ID, 'geocraft_listing_type', true);
                    if ($is_pro == 'pro') {
                        $featured_class = 'featured';
                    }
                    $img_meta = get_post_meta($q->ID, 'geocraft_meta_image1', true);
                    ?>
                    <!--Start Featured Post-->
                    <div class="featured_post">
                        <div class="<?php echo $featured_class; ?>">
                            <!--Start Featured thumb-->
                            <div class="featured_thumb">
                                <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                    <?php inkthemes_get_thumbnail(128, 108, '', $img_meta); ?>                    
                                <?php } else { ?>
                                    <?php search_get_image(128, 108, '', $img_meta, get_permalink($q->ID)); ?> 
                                    <?php
                                }
                                ?>
                                <?php if ($is_pro == 'pro') { ?>
                                    <img class="ribbon" src="<?php echo get_template_directory_uri(); ?>/images/ribbon.png"/>                   
                                <?php } ?>
                                <ul class="star_rating">
                                    <?php
                                    echo geocraft_get_post_rating_star($q->ID);
                                    ?>
                                </ul>
                                <span class="review_desc"><?php comments_popup_link(N_RV, _RV, '% ' . REVIEW); ?></span> </div>
                            <!--End Featured thumb-->
                            <div class="f_post_content">
                                <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php echo get_permalink($q->ID); ?>" rel="bookmark" ><?php echo get_the_title($q->ID); ?></a></h4>
                                <?php if (get_post_meta($q->ID, 'geo_address', true)): ?>
                                    <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($q->ID, 'geo_address', true); ?></p>                               
                                <?php endif; ?>
                                <?php
                                $excerpt = preg_replace("/<img[^>]+\>/i", "", $q->post_content);
                                $excerpt = substr($excerpt, 0, 111);
                                printf("%s", $excerpt);
                                if (strlen($excerpt) > 110)
                                    echo '&nbsp;<a href="' . get_permalink($q->ID) . '">' . '[...] Read More' . '</a>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--End Featured Post-->
                    <?php
                }
            }else {
                ?>            
                <div class = "featured_post">
                    <p class = "place"><?php echo NO_LST_FND; ?></p>
                </div>
                <?php
            }
            ?>
            <div class="paging"><span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php
        global $post;
        get_sidebar(POST_TYPE);
        ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>