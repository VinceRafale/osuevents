<?php
/**
 * Include all files from widget folder 
 */

$file_name = array(
    //'archive_widget',
    'category_widget',
    'google_map',
    'latest_review',
    'recent_listing_post'
);
foreach($file_name as $files):
    if(file_exists(WIDGETPATH.$files.'.php')):
        require_once(WIDGETPATH.$files.'.php');
    endif;
endforeach; 
?>
