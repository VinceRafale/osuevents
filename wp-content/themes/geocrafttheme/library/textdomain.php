<?php
/**
 * Include all text domain files for translation  
 */
$file_name = array(
    'admin_path',
    'admin_setting_path',
    'controls_path',
    'dashboard_path',
    'front_end_path',
    'metabox_path',
    'model_path',
    'template_path',
    'widget_path',
    'import_export_path',
    'custom_field_path',
	'map_path'
);
foreach($file_name as $files):
    if(file_exists(TEXTDOMAINPATH.$files.'.php')):
        require_once(TEXTDOMAINPATH.$files.'.php');
    endif;
endforeach; 
?>
