<?php
/**
 * Includes all files from controls folder 
 */
$file_name = array(
    '_main',
    'module_functions',
    'shortcodes',
    'dynamic-image',
    'post_rating',//In rating folder
    'listing_metabox',//In metabox folder 
    'custom_metabox',//Custom metabox       
    'listing_type',//In custom post type folder
    'listing_type_lang',//In custom post type folder
    'listing_expiry', //listing expiry
    'dashboard_main'
);

foreach ($file_name as $files):
    if(file_exists(SETTINGPATH .$files . '.php')):
        require_once(SETTINGPATH . $files . '.php');
    elseif (file_exists(CONTROLPATH . $files . '.php')):
        //Include all files from controls folder
        require_once(CONTROLPATH . $files . '.php');
    elseif (file_exists(RATINGPATH . $files . '.php')):
        //Include all files from rating folder
        require_once(RATINGPATH . $files . '.php');
    elseif (file_exists(METABOXPATH . $files . '.php')):
        //Include all files from metabox folder
        require_once(METABOXPATH . $files . '.php');
    elseif (file_exists(CTMPTPATH . $files . '.php')):
        //Include all files from custom post type folder
        require_once(CTMPTPATH . $files . '.php');
    elseif (file_exists(DASHBOARDPATH . $files . '.php')):
        //Include all files from dashboard folder
        require_once(DASHBOARDPATH . $files . '.php');
    endif;
endforeach;
?>
