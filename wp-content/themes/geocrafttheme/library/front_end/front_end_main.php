<?php
/**
 * Include all front end relatied files
 * 
 * @package Geocraft
 * @since 1.0 
 */
$file_name = array(
    'custom_function',
    'listing_process',
    'listing_submit',
    'listing_preview',
    'registration',
    'listing_submit_form'
);
foreach($file_name as $files):
    if(file_exists(FRONTENDPATH.$files.'.php')):
        require_once(FRONTENDPATH.$files.'.php');
    endif;
endforeach; 
?>
