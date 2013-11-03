<?php
/**
 * Template Name: Template: Contact
 * 
 * Description: This template can be used for getting 
 * Users views, ideas or reviews.
 * 
 * @since 1.0
 * @package Geocraft
 */
?>
<?php
$nameError = '';
$emailError = '';
$commentError = '';
if (isset($_POST['submitted'])) {
    if (trim($_POST['contactName']) === '') {
        $nameError = 'Please enter your name.';
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }
    if (trim($_POST['email']) === '') {
        $emailError = 'Please enter your email address.';
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $emailError = 'You entered an invalid email address.';
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }
    if (trim($_POST['comments']) === '') {
        $commentError = 'Please enter a message.';
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }
    if (trim($_POST['website']) != '') {
        $website = trim($_POST['website']);
    }
    if (!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '')) {
            $emailTo = get_option('admin_email');
        }
        $subject = 'From ' . $name;
        $body = "Name: $name \n\nEmail: $email \n\nWebsite: $website \n\nComments: $comments";
        $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<script type="text/javascript" src="<?php echo TEMPLATEURL . '/js/form-validation.js'; ?>"></script>
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="content">
            <h1 class="page-title"><?php the_title(); ?></h1>

            <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            <?php if (isset($emailSent) && $emailSent == true) { ?>
                <div class="thanks">
                    <p><?php echo THANKS_SENT; ?></p>
                </div>
            <?php } else { ?>
                <?php if (isset($hasError) || isset($captchaError)) { ?>
                    <p class="error"><?php echo SRY_ERROR; ?></p>
                <?php } ?>
                <form action="<?php the_permalink(); ?>" class="contactform" method="post" id="contactForm">
                    <label for="contactName"><?php echo U_NAME; ?> <span class="required"><?php echo REQUIRED; ?></span>:</label>
                    <br/>
                    <?php if ($nameError != '') { ?>
                        <span class="error"> <?php echo $nameError; ?> </span>
                        <br/>
                    <?php } ?>                        
                    <input type="text" name="contactName" id="contactName" value="<?php if (isset($_POST['contactName'])) echo $_POST['contactName']; ?>" class="required requiredField" />
                    <span id="username_error"></span>
                    <br/>
                    <label for="email"><?php echo U_EMAIL; ?> <span class="required"><?php echo REQUIRED; ?></span>:</label>
                    <br/>
                    <?php if ($emailError != '') { ?>
                        <span class="error"> <?php echo $emailError; ?> </span>
                        <br/>
                    <?php } ?>                        
                    <input type="text" name="email" id="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" class="required requiredField email" />
                    <span id="email_error"></span>
                    <br/>
                    <label for="website"><?php echo WEBSITE_TEXT; ?><span><?php echo OPTIONAL; ?></span></label>
                    <br/>
                    <input class="text" type="text" id="website" name="website"  value="<?php if (isset($_POST['website'])) echo $_POST['website']; ?>"/>
                    <br/>
                    <label for="commentsText"><?php echo U_MSG; ?> <span class="required"><?php echo REQUIRED; ?></span>:</label>
                    <br/>
                    <?php if ($commentError != '') { ?>
                        <span class="error"> <?php echo $commentError; ?> </span>
                        <br/>
                    <?php } ?>
                    <textarea name="comments" id="commentsText" rows="20" cols="30" class="required requiredField"><?php
                if (isset($_POST['comments'])) {
                    if (function_exists('stripslashes')) {
                        echo stripslashes($_POST['comments']);
                    } else {
                        echo $_POST['comments'];
                    }
                }
                    ?></textarea>
                    <span id="comment_error"></span>
                    <br/>
                    <input  class="btnSubmit" type="submit" name="submit" value="<?php echo SUBMIT_U_MSG; ?>"/>
                    <input type="hidden" name="submitted" id="submitted" value="true" />
                </form>
            <?php } ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar('contact'); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>