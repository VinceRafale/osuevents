<?php

/**
 * Price talbe creation
 * This table stores the all 
 * Price value  
 */
global $wpdb, $table_prefix, $price_table_name;
$price_table_name = $table_prefix . "price";


if ($wpdb->get_var("SHOW TABLES LIKE \"$price_table_name\"") != $price_table_name) {
    $price_table = 'CREATE TABLE IF NOT EXISTS ' . $price_table_name . ' (
	  `pid` int(11) NOT NULL AUTO_INCREMENT,
	  `price_title` varchar(255) NOT NULL,
	  `price_desc` varchar(1000) NOT NULL,
	  `price_post_cat` varchar(100) NOT NULL,
	  `is_show` varchar(10) NOT NULL,
	  `package_cost` int(255) NOT NULL,
	  `validity` int(10) NOT NULL,
	  `validity_per` varchar(10) NOT NULL,
	  `status` int(10) NOT NULL ,
	  `is_recurring` int(10) NOT NULL ,
          `first_billing_per` int(10) NOT NULL,
	  `first_billing_cycle` varchar(10) NOT NULL,
	  `rebill_time` int(10) NOT NULL,
          `rebill_period` int(10) NOT NULL,
          `second_price` int(255) NOT NULL,
          `second_billing_per` int(10) NOT NULL,
          `second_billing_cycle` varchar(10) NOT NULL,          	  
	  `is_featured` int(10) NOT NULL,
	  `feature_amount` int(10) NOT NULL,
	  `feature_cat_amount` int(10) NOT NULL,
          `package_type` varchar(100) NOT NULL,
          `package_type_period` varchar(100) NOT NULL,
	  PRIMARY KEY (`pid`)
	)';
    $wpdb->query($price_table);
    $insert_value1 = array(
        'pid' => 1,
        'price_title' => 'Free Business Listing',
        'price_desc' => "No Charge for placing your listing. You won't receive any leads. Listing Expires after 4 Months. You can reactivate listing later",
        'price_post_cat' => '',
        'is_show' => '1',
        'package_cost' => 0,
        'validity' => 4,
        'validity_per' => 'M',
        'status' => 1,
        'is_recurring' => '',
        'first_billing_per' => '',
        'first_billing_cycle' => '',
        'rebill_time' => '',
        'rebill_period' => '',
        'second_price' => '',
        'second_billing_per' => '',
        'second_billing_cycle' => '',
        'is_featured' => '',
        'feature_amount' => '',
        'feature_cat_amount' => '',
        'package_type' => 'pkg_free',
        'package_type_period' => 'pkg_period_one'
    );
    $insert_value2 = array(
        'pid' => 2,
        'price_title' => 'One Time Listing Payment',
        'price_desc' => "One time charge for placing your listing. You can feature the listing on Homepage or Category Page. You will receive leads in your Dashboard. Listing Expires after 6 Months. You can reactivate listing later for no extra charge.",
        'price_post_cat' => '',
        'is_show' => 1,
        'package_cost' => 26,
        'validity' => 6,
        'validity_per' => 'M',
        'status' => 1,
        'is_recurring' => '',
        'first_billing_per' => '',
        'first_billing_cycle' => '',
        'rebill_time' => '',
        'rebill_period' => '',
        'second_price' => '',
        'second_billing_per' => '',
        'second_billing_cycle' => '',
        'is_featured' => 1,
        'feature_amount' => 12,
        'feature_cat_amount' => 10,
        'package_type' => 'pkg_one_time',
        'package_type_period' => 'pkg_period_two'
    );
    $insert_value3 = array(
        'pid' => 3,
        'price_title' => "Recurring Listing Payment",
        'price_desc' => "Small recurring charge for placing your listing. You can feature the listing on Homepage or Category Page. You will receive leads in your Dashboard. No Listing expiry till you have active subscription.",
        'price_post_cat' => '',
        'is_show' => 1,
        'package_cost' => 36,
        'validity' => 6,
        'validity_per' => 'M',
        'status' => 1,
        'is_recurring' => 1,
        'first_billing_per' => 1,
        'first_billing_cycle' => 'M',
        'rebill_time' => 1,
        'rebill_period' => 12,
        'second_price' => 15,
        'second_billing_per' => 6,
        'second_billing_cycle' => 'M',
        'is_featured' => 1,
        'feature_amount' => 22,
        'feature_cat_amount' => 15,
        'package_type' => 'pkg_recurring',
        'package_type_period' => 'pkg_period_three'
    );
    $wpdb->insert($price_table_name, $insert_value1);
    $wpdb->insert($price_table_name, $insert_value2);
    $wpdb->insert($price_table_name, $insert_value3);
}

/**
 * Transaction table for recording all transaction 
 */
global $wpdb, $table_prefix;
$transection_table_name = $table_prefix . "transactions";
global $transection_table_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$transection_table_name\"") != $transection_table_name) {
    $transaction_table = 'CREATE TABLE IF NOT EXISTS `' . $transection_table_name . '` (
	`trans_id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL,
	`post_id` bigint(20) NOT NULL,
	`post_title` varchar(255) NOT NULL,
	`status` int(2) NOT NULL,
	`payment_method` varchar(255) NOT NULL,
	`payable_amt` float(25,2) NOT NULL,
	`payment_date` datetime NOT NULL,
	`paypal_transection_id` varchar(255) NOT NULL,
	`user_name` varchar(255) NOT NULL,
	`pay_email` varchar(255) NOT NULL,
	`billing_name` varchar(255) NOT NULL,
	`billing_add` text NOT NULL,
	PRIMARY KEY (`trans_id`)
	)';
    $wpdb->query($transaction_table);
}

/**
 * Create Payment methods and their each value 
 * Store in wp_options 
 */
//Paypal
$payout = array();
$payout[] = array(
    "title" => 'Merchant Id',
    "fieldname" => "merchantid",
    "value" => "myaccount@paypal.com",
    "description" => MERCHANT_DES,
);
$payout[] = array(
    "title" => 'Cancel Url',
    "fieldname" => "cancel_return",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);
$payout[] = array(
    "title" => 'Return Url',
    "fieldname" => "returnUrl",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);
$payout[] = array(
    "title" => 'Notify Url',
    "fieldname" => "notify_url",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);

$paymethods[] = array(
    "name" => __('Paypal', THEME_SLUG),
    "key" => 'paypal',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '1',
    "paypal_sandbox" => '0',
    "payOpts" => $payout,
);
//Google checkout
$payout = array();
$payout[] = array(
    "title" => MERCHANT_ID_TEXT,
    "fieldname" => "merchantid",
    "value" => "1234567890",
    "description" => __(EXMPL . " 1234567890", THEME_SLUG)
);

$paymethods[] = array(
    "name" => 'Google Checkout',
    "key" => 'googlechkout',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '2',
    "payOpts" => $payout,
);

//Authorize.net
$payout = array();
$payout[] = array(
    "title" => LOGIN_ID_TEXT,
    "fieldname" => "loginid",
    "value" => "yourname@domain.com",
    "description" => LOGIN_ID_NOTE
);
$payout[] = array(
    "title" => TRANS_KEY_TEXT,
    "fieldname" => "transkey",
    "value" => "1234567890",
    "description" => TRANS_KEY_NOTE,
);

$paymethods[] = array(
    "name" => __('Authorize.net', THEME_SLUG),
    "key" => 'authorizenet',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '3',
    "payOpts" => $payout,
);

//Worldpay
$payout = array();
$payout[] = array(
    "title" => INSTANT_ID_TEXT,
    "fieldname" => "instId",
    "value" => "123456",
    "description" => INSTANT_ID_NOTE
);
$payout[] = array(
    "title" => ACCOUNT_ID_TEXT,
    "fieldname" => "accId1",
    "value" => "12345",
    "description" => ACCOUNT_ID_NOTE
);

$paymethods[] = array(
    "name" => WORLD_PAY_TEXT,
    "key" => 'worldpay',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '4',
    "payOpts" => $payout,
);
//////////worldpay end////////
//////////2co start////////

$payout = array();
$payout[] = array(
    "title" => VENDOR_ID_TEXT,
    "fieldname" => "vendorid",
    "value" => "1303908",
    "description" => VENDOR_ID_NOTE
);
$payout[] = array(
    "title" => NOTIFY_URL_TEXT,
    "fieldname" => "ipnfilepath",
    "value" => site_url(""),
    "description" => sprintf(__("Example : %s", THEME_SLUG), site_url("")),
);

$paymethods[] = array(
    "name" => __('2CO (2Checkout)', THEME_SLUG),
    "key" => '2co',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '5',
    "payOpts" => $payout,
);

//Pre bank transfer
$payout = array();
$payout[] = array(
    "title" => BANK_INFO_TEXT,
    "fieldname" => "bankinfo",
    "value" => "State Bank Of India",
    "description" => BANK_INFO_NOTE
);
$payout[] = array(
    "title" => ACCOUNT_ID_TEXT,
    "fieldname" => "bank_accountid",
    "value" => "AB1234567890",
    "description" => ACCOUNT_ID_NOTE2,
);

$paymethods[] = array(
    "name" => PRE_BANK_TRANSFER_TEXT,
    "key" => 'prebanktransfer',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '6',
    "payOpts" => $payout,
);

//Pay cash on delivery
$payout = array();
$paymethods[] = array(
    "name" => PAY_CASH_TEXT,
    "key" => 'payondelevary',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '7',
    "payOpts" => $payout,
);

//Insert options in payment optiions

for ($i = 0; $i < count($paymethods); $i++) {
    $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_" . $paymethods[$i]['key'] . "' order by option_id asc";
    $paymentinfo = $wpdb->get_results($paymentsql);
    if (count($paymentinfo) == 0) {
        $paymethodArray = array(
            "option_name" => 'pay_method_' . $paymethods[$i]['key'],
            "option_value" => serialize($paymethods[$i]),
        );
        $wpdb->insert($wpdb->options, $paymethodArray);
    }
}
//Create table for inquiry form
global $wpdb, $table_prefix;
$inquiry_tbl_name = $table_prefix . 'inquiry';
global $inquiry_tbl_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$inquiry_tbl_name\"") != $inquiry_tbl_name) {
    $inquiry_table = "CREATE TABLE " . $inquiry_tbl_name . "(
  ID INT(11) NOT NULL AUTO_INCREMENT,
  listing_author VARCHAR(255) DEFAULT NULL,
  listing_id DECIMAL(10, 0) DEFAULT NULL,
  user_name VARCHAR(255) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  phone_no VARCHAR(15) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  listing_title VARCHAR(255) DEFAULT NULL,
  inquiry_date datetime DEFAULT NULL,
  form_valid INT(12),
  PRIMARY KEY (ID)
)";
    $wpdb->query($inquiry_table);
}

/**
 * Create table for listing activation
 * Authentication.
 */
/**
 * This table is not used since 1.7.1
  global $wpdb, $table_prefix;
  $expiry_tbl_name = $table_prefix . 'listing_expiry';
  global $expiry_tbl_name;
  if ($wpdb->get_var("SHOW TABLES LIKE \"$expiry_tbl_name\"") != $expiry_tbl_name) {
  $activation_table = "CREATE TABLE " . $expiry_tbl_name . "(
  ID INT(100) NOT NULL AUTO_INCREMENT,
  pid INT(100) DEFAULT NULL,
  listing_title VARCHAR(100) DEFAULT NULL,
  validity INT(11) DEFAULT NULL,
  validity_per VARCHAR(11) DEFAULT NULL,
  listing_date datetime DEFAULT NULL,
  `package_type` varchar(100) NOT NULL,
  PRIMARY KEY (ID)
  )";
  $wpdb->query($activation_table);
  }
 */
/**
 * Create table for payment currencies
 * 
 */
if (!get_option('currency_symbol')) {
    add_option('currency_symbol', '$');
}

if (!get_option('currency_code')) {
    add_option('currency_code', 'USD');
}

global $wpdb, $table_prefix;
$currency_tbl_name = $table_prefix . 'currency';
global $currency_tbl_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$currency_tbl_name\"") != $currency_tbl_name) {
    $currency_table = "CREATE TABLE " . $currency_tbl_name . "(
                c_id int(8) NOT NULL AUTO_INCREMENT,
                c_name varchar(100) NOT NULL,
                c_code varchar(10) NOT NULL,
                c_symbol varchar(10) NOT NULL,
                c_des varchar(500) NOT NULL,
                PRIMARY KEY c_id(c_id)
        )";
    $wpdb->query($currency_table);

    $currency_file = CSVPATH . "currency.csv";
    $currency = fopen($currency_file, 'r');
    $theData = fgets($currency);
    $i = 0;
    while (!feof($currency)) {

        $currency_data[] = fgets($currency, 1024);
        $currency_array = explode(",", $currency_data[$i]);
        $insert_currency = array();
        $insert_currency['c_id'] = $currency_array[0];
        $insert_currency['c_name'] = $currency_array[1];
        $insert_currency['c_code'] = $currency_array[2];
        $insert_currency['c_symbol'] = $currency_array[3];
        $insert_currency['c_des'] = $currency_array[4];
        $wpdb->insert($currency_tbl_name, $insert_currency);
        $i++;
    }
    fclose($currency);
}

/**
 * Create Table for custom field 
 */
$cfield_tbl_name = $table_prefix . 'custom_field';
global $cfield_tbl_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$cfield_tbl_name\"") != $cfield_tbl_name) {
    $cfield_table = "CREATE TABLE " . $cfield_tbl_name . "(
        fid int(8) NOT NULL AUTO_INCREMENT,
                field_cate varchar(200) NOT NULL,
                f_type varchar(255) NOT NULL,
                opt_value text NOT NULL,
                f_des text NOT NULL,
                f_title varchar(500) NOT NULL,
                f_var_nm varchar(255) NOT NULL,
                dft_value text NOT NULL,
                p_order int(12) NOT NULL,
                is_active int(10) NOT NULL,
                is_require int(10) NOT NULL,
                show_on_detail int(10) NOT NULL,
                show_free varchar(10) NOT NULL,
                PRIMARY KEY fid(fid)
        )";
    $wpdb->query($cfield_table);

    $custom_field_file = CSVPATH . "custom_field.csv";
    $custom_field = fopen($custom_field_file, 'r');
    $theData = fgets($custom_field);
    $i = 0;
    while (!feof($custom_field)) {

        $custom_field_data[] = fgets($custom_field, 111024);
        $custom_field_array = explode(",", $custom_field_data[$i]);
        $insert_custom_field = array();
        $insert_custom_field['fid'] = $custom_field_array[0];
        $insert_custom_field['field_cate'] = $custom_field_array[1];
        $insert_custom_field['f_type'] = $custom_field_array[2];
        $insert_custom_field['opt_value'] = $custom_field_array[3];
        $insert_custom_field['f_des'] = $custom_field_array[4];
        $insert_custom_field['f_title'] = $custom_field_array[5];
        $insert_custom_field['f_var_nm'] = $custom_field_array[6];
        $insert_custom_field['dft_value'] = $custom_field_array[7];
        $insert_custom_field['p_order'] = $custom_field_array[8];
        $insert_custom_field['is_active'] = $custom_field_array[9];
        $insert_custom_field['is_require'] = $custom_field_array[10];
        $insert_custom_field['show_on_detail'] = $custom_field_array[11];
        $wpdb->insert($cfield_tbl_name, $insert_custom_field);
        $i++;
    }
    fclose($custom_field);
}

//Add column for show/hide in free listing
$show_free = $wpdb->get_var("SHOW COLUMNS FROM $cfield_tbl_name LIKE 'show_free'");
if (!isset($show_free)) {
    $wpdb->query("ALTER TABLE $cfield_tbl_name  ADD `show_free` VARCHAR(10) NOT NULL AFTER `show_on_detail`");
}
//Add column renewal_per for renew listing
$renewal_per = $wpdb->get_var("SHOW COLUMNS FROM $price_table_name LIKE 'renewal_per'");
if (!isset($renewal_per)) {
    $wpdb->query("ALTER TABLE $price_table_name  ADD `renewal_per` INT(10) NOT NULL AFTER `is_recurring`");
}
//Add column renewal_cycle for renew listing
$renewal_cycle = $wpdb->get_var("SHOW COLUMNS FROM $price_table_name LIKE 'renewal_cycle'");
if (!isset($renewal_cycle)) {
    $wpdb->query("ALTER TABLE $price_table_name  ADD `renewal_cycle` VARCHAR(10) NOT NULL AFTER `renewal_per`");
}

