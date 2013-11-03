<?php
global $wpdb;
$dbprefix = $wpdb->prefix;
global $dbprefix;
/**
 * Include all files for db interaction
 */
$file_name = array(
    'db_function',
    'ajax_db',
    'table_creation'
);
foreach($file_name as $files):
    if(file_exists(MODELPATH.$files.'.php')):
        require_once(MODELPATH.$files.'.php');
    endif;
endforeach; 
?>
