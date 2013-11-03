<?php
$file_name = array(
    'dashboard_functions'
);
foreach ($file_name as $files):
    if(file_exists(DASHBOARDPATH .$files . '.php')):
        require_once(DASHBOARDPATH . $files . '.php');  
    endif;
endforeach;
