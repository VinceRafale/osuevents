<?php
global $post, $wpdb, $inquiry_tbl_name;
$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$messate = $_POST['message'];
global $wpdb;
$insert = array(
    'user_name' => $name,
    'email' => $email,
    'phone_no' => $contact,
    'message' => $messate
        );

insert($inquiry_tbl_name,$insert);
?>
