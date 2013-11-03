<?php
global $query_string;

//add_action('admin_menu', 'geocraft_add_setting');
//add_action('admin_print_scripts', 'geocraft_ajax');

function geocraft_admin_menu() {
    add_menu_page(__('Geocraft', THEME_SLUG), __('Geocraft Settings', THEME_SLUG), 'read', basename(__FILE__), 'inkthemes_optionsframework_add_admin', TEMPLATEURL . '/images/setting.png', 4);
    add_submenu_page(basename(__FILE__), __('Theme Options', THEME_SLUG), __('Theme Options', THEME_SLUG), 'manage_options', 'optionsframework', 'inkthemes_optionsframework_options_page');
    add_submenu_page(basename(__FILE__), __('Setting', THEME_SLUG), __('Payment Settings', THEME_SLUG), 'manage_options', 'setting', 'geocraft_price_form');
    add_submenu_page(basename(__FILE__), __('Custom Field', THEME_SLUG), __('Custom Fields', THEME_SLUG), 'manage_options', 'customfield', 'manage_custom_field');
    add_submenu_page(basename(__FILE__), __('Transaction', THEME_SLUG), __('Transaction', THEME_SLUG), 'manage_options', 'transaction', 'geocraft_transaction_opt');
    add_submenu_page(basename(__FILE__), __('Leads', THEME_SLUG), __('Leads Capture', THEME_SLUG), 'manage_options', 'inquiry', 'user_inquiry');
    add_submenu_page(basename(__FILE__), __('Import Export', THEME_SLUG), __('Import Export', THEME_SLUG), 'manage_options', 'import', 'import_export');
    remove_submenu_page(basename(__FILE__), basename(__FILE__));
}

add_action('admin_menu', 'geocraft_admin_menu');

function geocraft_price_form() {

    global $wpdb, $price_table_name, $cfield_tbl_name;
    if (isset($_REQUEST['of_save']) && 'reset' == $_REQUEST['of_save']) {
        global $wpdb, $price_table_name, $cfield_tbl_name;
        $sql = "DROP TABLE IF EXISTS $price_table_name";
        $wpdb->query($wpdb->prepare($sql));
        //header("Location: admin.php?page=setting&reset=true");
        //die;           
    }
    $get_prices = $wpdb->get_results("SELECT * FROM $price_table_name");
    $freepid = $get_prices[0]->pid;
    $pid1 = $get_prices[1]->pid;
    $pid2 = $get_prices[2]->pid;
    //Free package
    if (isset($_POST['submit'])) {
        $free_package = array();
        $free_package['price_title'] = esc_attr($_POST['package_ftitle']);
        $free_package['price_desc'] = esc_attr($_POST['package_fdescription']);
        $free_package['package_cost'] = esc_attr($_POST['package_fcost']);
        $free_package['validity'] = esc_attr($_POST['package_fperiod']);
        $free_package['validity_per'] = esc_attr($_POST['package_fday']);
        $free_package['status'] = esc_attr($_POST['package_fstatus']);
        $free_package['renewal_per'] = esc_attr($_POST['renewal_per']);
        $free_package['renewal_cycle'] = esc_attr($_POST['renewal_cycle']);

        if ($freepid != ''):
            $wpdb->update($price_table_name, $free_package, array('pid' => $freepid));
        endif;
    }

    //One time payment package
    if (isset($_POST['submit'])) :
        $id = $_POST['price_id'];
        $onetime_package = array();
        $onetime_package['price_title'] = $_POST['package_title'];
        $onetime_package['price_desc'] = $_POST['package_description'];
        $cat = $_POST['category'];
        if ($cat) {
            $package_cat = implode(',', $cat);
        }
        $onetime_package['package_cost'] = $_POST['package_cost'];
        $onetime_package['validity'] = $_POST['package_period'];
        $onetime_package['validity_per'] = $_POST['package_day'];
        $onetime_package['status'] = $_POST['package_status'];
        $onetime_package['is_featured'] = $_POST['feature_status'];
        $onetime_package['feature_amount'] = $_POST['feature_home'];
        $onetime_package['feature_cat_amount'] = $_POST['feature_cat'];
        $onetime_package['renewal_per'] = esc_attr($_POST['renewal_per1']);
        $onetime_package['renewal_cycle'] = esc_attr($_POST['renewal_cycle1']);
        if ($pid1 != '') {
            if ($onetime_package['price_title'] != '' && $onetime_package['package_cost'] != 0) {
                $wpdb->update($price_table_name, $onetime_package, array('pid' => $pid1));
            }
        }
    endif;
    //Recurring Package
    if (isset($_POST['submit'])) {
        $id = $_POST['price_id'];
        $recurring_package = array();
        $recurring_package['price_title'] = $_POST['package_title1'];
        $recurring_package['price_desc'] = $_POST['package_description1'];
        $cat = $_POST['category1'];
        if ($cat) {
            $package_cat = implode(',', $cat);
        }
        $recurring_package['package_cost'] = $_POST['package_cost1'];
        $recurring_package['validity'] = $_POST['package_period1'];
        $recurring_package['validity_per'] = $_POST['package_day1'];
        $recurring_package['status'] = $_POST['package_status1'];
        $recurring_package['first_billing_per'] = $_POST['first_billing_per'];
        $recurring_package['first_billing_cycle'] = $_POST['first_billing_cycle'];
        $recurring_package['rebill_time'] = $_POST['rebill_time'];
        $recurring_package['rebill_period'] = $_POST['rebill_period'];
        $recurring_package['second_price'] = $_POST['second_price'];
        $recurring_package['second_billing_per'] = $_POST['second_billing_per'];
        $recurring_package['second_billing_cycle'] = $_POST['second_billing_cycle'];
        $recurring_package['is_featured'] = $_POST['feature_status1'];
        $recurring_package['feature_amount'] = $_POST['feature_home1'];
        $recurring_package['feature_cat_amount'] = $_POST['feature_cat1'];

        if ($pid2 != '') {
            if ($recurring_package['price_title'] != '' && $recurring_package['package_cost'] != 0) {
                $wpdb->update($price_table_name, $recurring_package, array('pid' => $pid2));
            }
        }
    }
    if ($freepid != '') {
        global $wpdb, $price_table_name;
        $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$freepid\"";
        $freeinfo = $wpdb->get_results($price_query);
    }
    if ($pid1 != '') {
        global $wpdb, $price_table_name;
        $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$pid1\"";
        $priceinfo = $wpdb->get_results($price_query);
    }
    if ($pid2 != '') {
        global $wpdb, $price_table_name;
        $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$pid2\"";
        $priceinfo2 = $wpdb->get_results($price_query);
    }
    ?>       
    <div class="wrap" id="of_container">
        <div id="of-popup-save" class="of-save-popup">
            <div class="of-save-save"></div>
        </div>
        <div id="of-popup-reset" class="of-save-popup">
            <div class="of-save-reset"></div>
        </div>
        <div id="header">
            <div class="logo">
                <h2><?php echo ADVANCE_SETTING; ?> <?php echo OPTIONS; ?></h2>
            </div>
            <a href="http://www.inkthemes.com" target="_new">
                <div class="icon-option"> </div>
            </a>
            <div class="clear"></div>
        </div>
        <form enctype="multipart/form-data" id="ofform" name="price_form" method="post">
            <input type="hidden" name="price_id" value="<?php echo $_REQUEST['price_id']; ?>">
            <input type="hidden" id="addpackage" name="priceval" value="addpackage">
            <input type="hidden" name="package1" value="package1" />

            <div id="main">
                <div id="of-nav">
                    <ul>
                        <li> <a  class="pn-view-a" href="#of-option-packagefree" title="Package free"><?php echo FREE_PAY_PKG; ?></a></li>
                        <li> <a  class="pn-view-a" href="#of-option-package1" title="Package 1"><?php echo OTIME_PAY_PKG; ?></a></li>
                        <li> <a  class="pn-view-a" href="#of-option-package2" title="Package 2"><?php echo RECURRING_PAY_PKG; ?></a></li> 
                        <li> <a  class="pn-view-a" href="#of-option-payment" title="Payment"><?php echo PAYMENT_SETTING; ?></a></li>
                        <li> <a  class="pn-view-a" href="#of-option-currency" title="Manage Currency"><?php echo "Manage Currency"; ?></a></li> 
                    </ul>
                </div>
                <div id="content">
                    <?php
                    $file_name = array(
                        'package_free',
                        'package_1',
                        'package_2',
                        'payment_option',
                        'manage_currency'
                    );
                    foreach ($file_name as $files):
                        if (file_exists(SETTINGPATH . $files . '.php')):
                            require_once(SETTINGPATH . $files . '.php');
                        elseif (file_exists(CFIELDPATH . $files . '.php')):
                            require_once(CFIELDPATH . $files . '.php');
                        endif;
                    endforeach;
                    ?> 
                </div>
                <div class="clear"></div>
            </div>
            <div class="save_bar_top">
                <img style="display:none" src="<?php echo ADMINURL; ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
                <input type="submit" id="submit" name="submit" value="<?php echo SAVE_ALL_CHNG; ?>" class="button-primary" />   
        </form> 
        <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']) ?>" method="post" style="display:inline" id="ofform-reset">
            <span class="submit-footer-reset">
                <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
                <input type="hidden" name="of_save" value="reset" />
            </span>
        </form>
    </div>
    </div> 
    <!--wrap-->
    <?php
}
