<?php
/**
 * Template Name: Template: Submit Listing
 * 
 * @package Geocraft
 * since 1.0
 *  
 */
### Prevent Caching
nocache_headers();
//$submitObj = new SubmitPlace();
global $post, $posted;

$posted = array();
$errors = new WP_Error();
if (!is_user_logged_in())
    $step = 0; else
    $step = 1;
if (isset($_POST['review_next']) && $_POST['review_next']) {
    $value = geocraft_place_form_process();
    $errors = $value['errors'];
    $posted = $value['posted'];
    if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) {
        $step = 4;
    } else {
        $step = 2;
        $jump = 2;
    }
} elseif (isset($_POST['submit']) && $_POST['submit']) {
    geocraft_place_submmition();
    if (isset($_POST['pay_method'])) {
        $step = 5;
    } else {
        $step = 3;
    }
}
global $step;
if ($step != 5) {
//Call header.php
    get_header();
    ?>
    <!--Start Content Wrapper-->
    <div class="content_wrapper">
        <style type="text/css">
            .content_wrapper img{
                max-width: none;
            }
        </style>
        <?php if ((is_user_logged_in()) && ($step != 5)): ?>
            <div id="place_header">
                <h1 class="title"><?php echo ADD_LISTING; ?></h1>
                <ul class="step">
                    <?php
                    listing_step();
                    ?>                
                </ul>
            </div> 
        <?php endif; ?>
        <?php
        if ($step == 2):
            geocraft_preview_place_form();
        endif;
        if ($jump != 2):
            ?>
            <div class="grid_16 alpha">
                <div id="add_place">
                    <?php
                    if (!is_user_logged_in()):
                        //call login form
                        geocraft_login_form();
                        //call registration form
                        $redirect = geocraft_get_option('user_redirect');
                        geocraft_register_form($redirect);
                    endif;
                    if ($step == 1):
                        geocraft_submit_form();
                    endif;
                    if ($step == 4):
                        geocraft_show_errors($errors);
                    endif;
                    ?>
                </div>
                <?php
                if ($step == 3):
                    ?>
                    <div class="submit_successful">
                        <?php echo SBMITION_MSG; ?>
                    </div>   
                    <?php
                endif;
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="grid_8 omega">
        <?php //get_sidebar();  ?>
    </div>
    </div>
    <!--End Content Wrapper-->
    <?php
//Call footer.php
    get_footer();
}
?>
