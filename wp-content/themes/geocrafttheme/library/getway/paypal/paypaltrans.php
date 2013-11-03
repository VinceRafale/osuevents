<?php

class Paypal {
    public $item_name = '';
    public $item_number = 0;
    public $payment_status = '';
    public $payment_amount = 0;
    public $payment_currency = '';
    public $txn_id = '';
    public $receiver_email = '';
    public $payer_email = '';
    public $userid = 0;
    public $post_id = 0;
    public $post_title = '';
    public $status = 0;
    public $payment_method = '';
    public $pay_date = '';
    public $user_name = '';
    public $billing_name = '';
    public $billing_add = '';
    //Recurring variable
    var $pending_reason = '';
    var $recurring_payment_id = '';
    var $payment_cycle = '';
    var $recurring_payment = '';
    function __construct() {
        // parent::__construct();
        $this->geocraft_paypal_trans();
    }

    public function geocraft_paypal_trans() {
        global $current_user;
        get_currentuserinfo();
        // assign posted variables to local variables
        if (isset($_REQUEST['ptype']) && $_REQUEST['ptype'] == 'pstatus'):
            $to_admin = get_option('admin_email');
            $store_name = get_option('blogname');
            $this->item_name = $_POST['item_name'];
            $this->item_number = $_POST['item_number'];
            $this->payment_status = $_POST['payment_status'];
            $this->payment_amount = $_POST['mc_gross'];
            $this->payment_currency = $_POST['mc_currency'];
            $this->txn_id = $_POST['txn_id'];
            $this->receiver_email = $_POST['receiver_email'];
            $this->payer_email = $_POST['payer_email'];
            global $wpdb, $transection_table_name;
            $this->post_id = $_REQUEST['post_id'];
            if ($this->post_id != '') {
                $post_id = $this->post_id;
                $post_author = $wpdb->get_row("select * from $wpdb->posts where ID = '" . $post_id . "'");
            }
            $post_author = $post_author->post_author;
            $this->userid = $current_user->ID;
            $userinfo = get_userdata($post_author);
            $this->post_title = $_REQUEST['post_title'];
            if ($this->payment_status == 'Completed' || $this->payment_status == 'Pending') {
                $this->status = 1;
                $this->payment_status = 'Completed';
                $post_status_to_admin = "Payment Received";
                $post_status_to_client = "Your @" . $store_name . " is successfully completed.";
            }
            $this->payment_method = $_REQUEST['pay_method'];
            $this->pay_date = current_time('mysql');
            $this->user_name = $current_user->user_login;
            $this->billing_name = $current_user->display_name;
            $this->billing_add = '';
            $sql = "select * from $transection_table_name where paypal_transection_id='$this->txn_id'";
            $sql_stat = $wpdb->get_row($sql);
            if (empty($sql_stat) && $this->status == 1):
                $wpdb->insert($transection_table_name, array(
                    "user_id" => $this->userid,
                    "post_id" => $this->post_id,
                    "post_title" => $this->post_title,
                    "status" => $this->status,
                    "payment_method" => $this->payment_method,
                    "payable_amt" => $this->payment_amount,
                    "payment_date" => $this->pay_date,
                    "paypal_transection_id" => $this->txn_id,
                    "user_name" => $this->user_name,
                    "pay_email" => $this->payer_email,
                    "billing_name" => $this->billing_name,
                    "billing_add" => $this->billing_add
                ));
            endif;
            //set post pending to publish
            if (($this->post_id != '') && ($this->status == 1)):
                global $wpdb;
                $post_status = geocraft_get_option('paid_post_mode');
                if (strtolower($post_status) == 'pending'):
                    $post_status = 'pending';
                elseif (strtolower($post_status) == 'publish'):
                    $post_status = 'publish';
                elseif (strtolower($post_status) == ''):
                    $post_status = 'publish';
                endif;
                $my_post = array(
                    'ID' => $this->post_id,
                    'post_status' => 'publish'
                );
                wp_update_post($my_post);
            endif;
            /**
             * Send transaction notification to admin or client
             */
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details .= "Payment Details for Listing ID #$this->post_id\r";
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details .= "Listing Title: $this->post_title \r";
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details .= "Trans ID: $this->txn_id\r";
            $transaction_details .= "Status: $this->payment_status\r";
            $transaction_details .= "Date: $this->pay_date\r";
            $transaction_details .= "Payment Method: $this->payment_method\r";
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details = __($transaction_details, THEME_SLUG);
            $subject = __("Listing Submitted and Payment Success Confirmation Email", THEME_SLUG);
            $content = get_option('post_payment_success_admin_email_content');
            $site_name = get_option('blogname');
            $fromEmail = 'Admin';
            $filecontent = $transaction_details;
            global $wpdb;
            $placeinfosql = "SELECT ID, post_title, guid, post_author from $wpdb->posts where ID =$this->post_id";
            $placeinfo = $wpdb->get_results($placeinfosql);
            foreach ($placeinfo as $placeinfoObj) {
                $post_link = $placeinfoObj->guid;
                $post_title = '<a href="' . $post_link . '">' . $placeinfoObj->post_title . '</a>';
                $authorinfo = $placeinfoObj->post_author;
                $userInfo = get_author_info($authorinfo);
                $to_name = $userInfo->user_nicename;
                $to_email = $userInfo->user_email;
                $user_email = $userInfo->user_email;
            }

            $headers = 'From: ' . $fromEmail . ' <' . $to_admin . '>' . "\r\n" . 'Reply-To: ' . $fromEmail;
            //mail($to_admin, $subject, $filecontent, $headers);
            wp_mail($to_admin, $subject, $filecontent, $headers); //email to admin

            $subject = "Listing Submitted and Payment Success Confirmation Email";
            $content = get_option('post_payment_success_client_email_content');
            $transaction_details .= "Information Submitted URL\r";
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details .= "Site Name: $store_name\r";
            $transaction_details .= "--------------------------------------------------------------------------------\r";
            $transaction_details .= "$post_title\r";
            $transaction_details = __($transaction_details, THEME_SLUG);
            $content = $transaction_details;
            $headers = 'From: ' . $to_admin . ' <' . $user_email . '>' . "\r\n" . 'Reply-To: ' . $to_admin;
            wp_mail($user_email, $subject, $content, $headers); //email to client

        endif;
    }

}

add_shortcode('pay-status', 'trans_display');

function trans_display($atts) {
    $paypal_init = new Paypal();
    echo "Your Post Name:&nbsp;&nbsp;<b>" . $paypal_init->item_name . '</b><br/>';
    //echo "Your Post Number:&nbsp;&nbsp;<b>" . $item_number . '</b><br/>';
    echo "Your Payment Status:&nbsp;&nbsp;<b>" . $paypal_init->payment_status . '</b><br/>';
    echo "Your Payment Amount:&nbsp;&nbsp;<b>" . $paypal_init->payment_amount . '</b><br/>';
    echo "Your Payment Currency:&nbsp;&nbsp;<b>" . $paypal_init->payment_currency . '</b><br/>';
    echo "Your Transaction ID:&nbsp;&nbsp;<b>" . $paypal_init->txn_id . '</b><br/>';
    echo "Payment Receiver Email:&nbsp;&nbsp;<b>" . $paypal_init->receiver_email . '</b><br/>';
    echo "Payment Payer Email:&nbsp;&nbsp;<b>" . $paypal_init->payer_email . '</b><br/>';
    $post_id = $paypal_init->post_id;
    $total_amount = $paypal_init->payment_amount;
    $upgrade_meta_values = get_post_meta($post_id, 'geocraft_listing_type', true);
    if ($upgrade_meta_values == "free") {
        upgrade_listing($post_id, $total_amount);
    }
}

?>
