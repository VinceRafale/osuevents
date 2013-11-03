<?php
/**
 * Custom functions for front end 
 * Work performance 
 */
/**
 * Function Name: geocraft_lost_pw
 * Description: This function creates the 
 * Forgot password form 
 */
function geocraft_lost_pw() {
    ?>
    <div id="fotget_pw">
        <div class="line" style="margin-top: 15px; margin-bottom: 15px;"></div>
        <h3><?php echo FORGOT_PW; ?></h3>        
        <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
            <div class="row">
                <label for="user_login" class="hide"><?php echo ENTER_USR_NM; ?>: </label><br/>               
                <input type="text" name="user_login" value="" size="20" id="user_login" />
            </div>
            <div class="row">
                <?php do_action('login_form', 'resetpass'); ?>
                <input type="submit" name="user-submit" value="<?php echo RST_PW; ?>" class="user-submit" />
                <?php
                $reset = $_GET['reset'];
                if ($reset == true) {
                    echo '<p>' . A_MSG_ST_EMAIL . '</p>';
                }
                ?>
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
                <input type="hidden" name="user-cookie" value="1" />
            </div>
        </form>
    </div>
    <?php
}

global $pagenow;

// check to prevent php "notice: undefined index" msg
if (isset($_GET['action']))
    $theaction = $_GET['action']; else
    $theaction = '';

// if the user is on the login page, then let the games begin
if ($pagenow == 'wp-login.php' && $theaction != 'logout' && !isset($_GET['key'])) :
    add_action('init', 'geocraft_login_init', 98);
endif;

/**
 * Function Name:  geocraft_login_init
 * Description: This function gets the 
 * Page request and routes to 
 * Particular page
 */
function geocraft_login_init() {
    nocache_headers(); //cache clear

    if (isset($_REQUEST['action'])) :
        $action = $_REQUEST['action'];
    else :
        $action = 'login';
    endif;
    switch ($action) :
        case 'add_place' :
            geocraft_submit_place();
            break;
        case 'lostpassword' :
        case 'retrievepassword' :
            geocraft_show_password();
            break;
        case 'register':
        case 'login':
        default:
            geocraft_show_login();
            break;
    endswitch;
    exit;
}

/**
 * Function Name: geocraft_show_login
 * Description: This function creates
 * User log in page
 * @global type $posted 
 */
function geocraft_show_login() {

    global $posted;

    if (isset($_POST['register']) && $_POST['register']) {
        $result = geocraft_reg_proceed_form();

        $errors = $result['errors'];
        $posted = $result['posted'];
    } elseif (isset($_POST['login']) && $_POST['login']) {

        $errors = geocraft_login_proceed_form();
    }

    // Clear errors if loggedout is set.
    if (!empty($_GET['loggedout']))
        $errors = new WP_Error();

    // If cookies are disabled we can't log in even with a valid user+pass
    if (isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]))
        $errors->add('test_cookie', TEST_COOKIE);

    if (isset($_GET['loggedout']) && TRUE == $_GET['loggedout'])
        $notify = LOGGED_OUT;

    elseif (isset($_GET['registration']) && 'disabled' == $_GET['registration'])
        $errors->add('registerdisabled', USR_REG_NT);

    elseif (isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'])
        $notify = CHK_EMAIL_CNF;

    elseif (isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'])
        $notify = CHK_EMAIL_PW;

    elseif (isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'])
        $notify = REG_CPL_EMAIL;
    if (is_user_logged_in()) {
        wp_redirect(site_url());
    }
    if (is_user_logged_in()) {
        global $wpdb, $current_user;
        $userRole = ($current_user->data->wp_capabilities);
        $role = key($userRole);
        unset($userRole);
        $edit_anchr = '';
        switch ($role) {
            case ('administrator' || 'editor' || 'contributor' || 'author'):
                break;
            default:
                break;
        }
    }
//Call header.php
    get_header();
    ?>
    <!--Start Content Wrapper-->
    <div class="content_wrapper">
        <div class="grid_16 alpha">
            <div id="login">
                <div class="content">
                    <?php
                    if (isset($notify) && !empty($notify)) {
                        echo '<p class="success">' . $notify . '</p>';
                    }
                    ?>
                    <?php
                    if (isset($errors) && sizeof($errors) > 0 && $errors->get_error_code()) :
                        echo '<ul class="error">';
                        foreach ($errors->errors as $error) {
                            echo '<li>' . $error[0] . '</li>';
                        }
                        echo '</ul>';
                    endif;
                    //call login form
                    geocraft_login_form();
                    //call registration form
                    geocraft_register_form(get_permalink($submitID));
                    ?>   
                </div>
            </div>
        </div>
        <div class="grid_8 omega">
            <?php get_sidebar(); ?>
        </div>
    </div>
    <!--End Content Wrapper-->
    <?php
//Call footer.php
    get_footer();
}

/**
 * Function Name:  geocraft_show_password
 * Description: This function creates
 * The forgot password page
 */
function geocraft_show_password() {
    $errors = new WP_Error();

    if (isset($_POST['user_login']) && $_POST['user_login']) {
        $errors = retrieve_password();

        if (!is_wp_error($errors)) {
            wp_redirect('wp-login.php?checkemail=confirm');
            exit();
        }
    }

    if (isset($_GET['error']) && 'invalidkey' == $_GET['error'])
        $errors->add('invalidkey', SRY_KEY_VALID);

    do_action('lost_password');
    do_action('lostpassword_post');
//Call header.php
    get_header();
    ?>
    <!--Start Content Wrapper-->
    <div class="content_wrapper">
        <div class="grid_16 alpha">
            <div class="content">
                <h1><?php echo PW_REC; ?></h1>

                <?php
                if (isset($notify) && !empty($notify)) {
                    echo '<p class="success">' . $notify . '</p>';
                }
                ?>
                <?php
                if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) :
                    echo '<ul class="error">';
                    foreach ($errors->errors as $error) {
                        echo '<li>' . $error[0] . '</li>';
                    }
                    echo '</ul>';
                endif;
                ?>
                <?php geocraft_lost_pw(); ?>  
            </div>
        </div>
        <div class="grid_8 omega">
            <?php get_sidebar(); ?>
        </div>
    </div>
    <!--End Content Wrapper-->
    <?php
//Call footer.php
    get_footer();
}

/**
 * Get payment values
 * @global type $wpdb
 * @param type $method
 * @return type 
 */
function get_payment_optins($method) {
    global $wpdb;
    $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_$method'";
    $paymentinfo = $wpdb->get_results($paymentsql);
    if ($paymentinfo) {
        foreach ($paymentinfo as $paymentinfoObj) {
            $option_value = unserialize($paymentinfoObj->option_value);
            $paymentOpts = $option_value['payOpts'];
            $optReturnarr = array();
            for ($i = 0; $i < count($paymentOpts); $i++) {
                $optReturnarr[$paymentOpts[$i]['fieldname']] = $paymentOpts[$i]['value'];
            }
            return $optReturnarr;
        }
    }
}

function listing_step() {
    global $step;
    for ($i = 1; $i <= 3; $i++) :
        echo '<li class="';
        if ($step == $i)
            echo 'current';
        if (($step - 1) == $i)
            echo 'previous';
        if (($step - 2) == $i)
            echo 'lastprev';
        echo ' ';
        if ($i == 1)
            echo 'one';
        else if ($i == 2)
            echo 'two';
        else if ($i == 3)
            echo 'three';
        echo '">';
        switch ($i):
            case 1 :
                echo STEP1;
                break;
            case 2 :
                echo STEP2;
                break;
            case 3 :
                echo STEP3;
                break;
        endswitch;
        echo '</li>';
    endfor;
}
/**
 * Function Name: geocraft_login_proceed_form
 * Description: This function validates login field and
 * Redirect to admin page
 * @global type $posted
 * @return type 
 */
function geocraft_login_proceed_form() {

	global $posted;	
	if ( isset( $_REQUEST['redirect_to'] ) )
		$redirect_to = $_REQUEST['redirect_to'];
	else
		$redirect_to = admin_url();	
	if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;
	else
		$secure_cookie = '';

	$user = wp_signon('', $secure_cookie);
	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);
	if ( !is_wp_error($user) ) {	
		if (user_can($user, 'manage_options')) :
			$redirect_to = admin_url();
		endif;			
		wp_safe_redirect($redirect_to);
		exit;
	}
	$errors = $user;
	return $errors;
}
/**
 * Function Name:  geocraft_login_form
 * Description: This function creates user login form
 */
function geocraft_login_form($class=null) {
    ?>
    <div id="loginform" class="<?php echo $class; ?>">
        <h4><?php echo SIGN; ?></h4>
        <form name="loginform" id="login_form" action="<?php bloginfo('url') ?>/wp-login.php" method="post">
            <div class="row">
                <label for="username"><?php echo USR_NM; ?><span class="required">*</span></label>
                <input type="text" name="log" id="username" value="<?php echo esc_attr(stripslashes($user_login)); ?>"/>                
            </div>
            <div class="row password">
                <label for="password"><?php echo PW; ?><span class="required">*</span></label>
                <input style=" width: 248px !important;
                       height: 28px !important;
                       border: 1px solid #dddcdc;
                       padding-left: 5px;
                       -webkit-border-radius: 3px;
                       -moz-border-radius: 3px;
                       border-radius: 3px;
                       margin-bottom: 3px;" type="password" name="pwd" id="password" value=""/> 
            </div>
             <?php ?>
            <input class="submit" type="submit" name="login" value="Log In"/>
            <a href="<?php echo site_url('wp-login.php?action=lostpassword'); ?>" class="forgot_password" ><?php echo LOST_PW; ?></a>
<!--            <a href="javascript:void(0);geocraft_forgetpw();" class="forgot_password" >Lost your password?</a>-->
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
            <input type="hidden" name="user-cookie" value="1" />									
        </form>
    </div>
    <?php
}