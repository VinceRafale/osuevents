<?php
/**
 * This is core file for geocraft library
 * It includes all funtional and visual files  
 */
/**
 * This files contains all constaints 
 * variables.  
 */
if(file_exists(LIBRARYPATH . 'constants.php'))
{
	include_once(LIBRARYPATH.'constants.php');
}
/**
 * Include textdomain file for language translation
 * This file contains all textdomain variables.
 * These textdomains are used to global theme
 * Translation.
 */
if(file_exists(LIBRARYPATH . 'textdomain.php'))
{
	include_once(LIBRARYPATH.'textdomain.php');
}
/**
 * Include all files from model folder. 
 * These files are used to interaction 
 * with database
 */

if(file_exists(MODELPATH.'db_main.php'))
{
	include_once(MODELPATH.'db_main.php');
}
/**
 * Include all files from control folder
 * These files are used for admin settings,
 * Payment settings etc.
 */

if(file_exists(CONTROLPATH . 'main_control.php'))
{
	include_once(CONTROLPATH.'main_control.php');
}

/**
 * Include all files from widget folder
 * This file include all widget files.
 */

if(file_exists(WIDGETPATH . 'widget_main.php'))
{
	include_once(WIDGETPATH.'widget_main.php');
}

/**
 * This file is used for display
 * Google map.  
 */
if(file_exists(MAPPATH . 'single_map.php'))
{
	include_once(MAPPATH.'single_map.php');
}
/**
 * Include all files from front end folder
 * These files are used to perform user
 * Registration, listing submition and login
 *  
 */
if(file_exists(FRONTENDPATH . 'front_end_main.php'))
{
	include_once(FRONTENDPATH.'front_end_main.php');
}

/**
 * Include files from getway folder
 * These files are used for manage payment transactions. 
 */
if(file_exists(GETWAYPATH . 'paypal/paypaltrans.php'))
{
	include_once(GETWAYPATH.'paypal/paypaltrans.php');
}  
/**
 * Include taxonomic seo permalinks file  
 */
if(!file_exists(ABSPATH.'wp-content/plugins/taxonomic-seo-permalinks/taxonomic-seo-permalink.php') &&  file_exists(LIBRARYPATH . 'seo-permalinks/taxonomic-seo-permalink.php')) {
	include_once (LIBRARYPATH . 'seo-permalinks/taxonomic-seo-permalink.php');
}
