<?php
/**
 * Include all files for theme settings
 */
$file_name = array(
    'theme_settings',
    'payment_transaction',
    'custom_fields',
    'import_export'
);
foreach($file_name as $files):
    if(file_exists(SETTINGPATH.$files.'.php')):
        require_once(SETTINGPATH.$files.'.php');
    elseif(file_exists(CFIELDPATH.$files.'.php')):
        require_once(CFIELDPATH.$files.'.php');
    elseif(file_exists(IMPORTEXPORTPATH.$files.'.php')):
        require_once(IMPORTEXPORTPATH.$files.'.php');
    endif;
endforeach; 
?>
