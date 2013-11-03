<?php
add_action('init', 'inkthemes_options');
if (!function_exists('inkthemes_options')) {

    function inkthemes_options() {
        // VARIABLES
        $themename = "Geocraft Pro Theme";
        $shortname = "of";
        // Populate OptionsFramework option in array for use in theme
        global $of_options;
        $of_options = geocraft_get_option('of_options');

        // Background Defaults
        $file_rename = array("on" => "On", "off" => "Off");
        $lead_capture = array("on" => "On", "off" => "Off");
        $background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat', 'position' => 'top center', 'attachment' => 'scroll');
        //Stylesheet Reader
        $alt_stylesheets = array("green" => "green", "black" => "black", "blue" => "blue", "orange" => "orange", "pink" => "pink", "red" => "red", "skyblue" => "skyblue");
        //Listing publish mode
		
		//RTL Stylesheet Reader
       $lan_stylesheets = array("default" => "default", "rtl" => "rtl");
	   
        $post_mode = array('pending' => 'Pending', 'publish' => 'Publish');

        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }

        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }

        // If using image radio buttons, define a directory path
        $imagepath = get_template_directory_uri() . '/images/';

        $options = array(
            array("name" => GEN_SETTING,
                "type" => "heading"),
            array("name" => CUSTOM_LOGO,
                "desc" => CUSTOM_LOGO_DES,
                "id" => "inkthemes_logo",
                "type" => "upload"),
            array("name" => CUSTOM_FAVICON,
                "desc" => FAVICON_DES,
                "id" => "inkthemes_favicon",
                "type" => "upload"),
            array("name" => TRACKING_CODE,
                "desc" => TRACKING_CODE_DES,
                "id" => "inkthemes_analytics",
                "std" => "",
                "type" => "textarea"),
            array("name" => BG_IMG,
                "desc" => BG_IMG_DES,
                "id" => "bodybg",
                "std" => "",
                "type" => "upload"),
            array("name" => "Front Page On/Off",
                "desc" => "Check on for enabling front page or check off for enabling blog page in front page",
                "id" => "re_nm",
                "std" => "on",
                "type" => "radio",
                "options" => $file_rename),
            array("name" => HOME_SETTING,
                "type" => "heading"),
            array("name" => POST_BTN,
                "desc" => POST_BTN_DES,
                "id" => "post_btn",
                "std" => "",
                "type" => "text"),
            array("name" => HOME_F_SLIDER,
                "desc" => HOME_F_SLIDER_DES,
                "id" => "home_feature_txt",
                "std" => "PREMIUM BUSINESS LISTINGS",
                "type" => "text"),
            array("name" => HOME_RECENT,
                "desc" => HOME_RECENT_DES,
                "id" => "home_recent_txt",
                "std" => "Recently Added",
                "type" => "text"),
//****=============================================================================****//
//****-----------This code is used for creating home page feature content----------****//							
//****=============================================================================****//	
            array("name" => LISTING_SETTING,
                "type" => "heading"),
            array("name" => FREE_LISTING,
                "desc" => FREE_LISTING_DES,
                "id" => "free_post_mode",
                "std" => "pending",
                "type" => "select",
                "options" => $post_mode),
            array("name" => PAID_LISTING,
                "desc" => PAID_LISTING_DES,
                "id" => "paid_post_mode",
                "std" => "publish",
                "type" => "select",
                "options" => $post_mode),
            array("name" => "Lead Capture Form On/Off",
                "desc" => "Check on for enabling lead capture form or check off for desabling it.",
                "id" => "lead_capture",
                "std" => "",
                "type" => "radio",
                "options" => $lead_capture),
             array("name" => "Lead Capture Form On/Off For Free Listing",
                "desc" => "Select your choice for enabling and desabling lead capture for free listing.",
                "id" => "lead_capture_free",
                "std" => "",
                "type" => "radio",
                "options" => $lead_capture),
            array("name" => "Slider Limit",
                "desc" => "Enter your digit for limitation the slider. Default is 20. Note: It will be applied for both homepage and category page slider.",
                "id" => "slider_limit",
                "std" => 20,
                "type" => "text"),
//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//							
//****=============================================================================****//				
            array("name" => STYLING_OPTION,
                "type" => "heading"),
            array("name" => THEME_STYLESHEET,
                "desc" => STYLESHEET_DES,
                "id" => "altstylesheet",
                "std" => "black",
                "type" => "select",
                "options" => $alt_stylesheets),
			array("name" => "Theme Language",
				"desc" => "Select your themes Language",
				"id" => "lan_stylesheets",
				"std" => "Default",
				"type" => "select",
				"options" => $lan_stylesheets),
			array("name" => CUSTOM_CSS,
                "desc" => CUSTOM_CSS_DES,
                "id" => "customcss",
                "std" => "",
                "type" => "textarea"),
            array("name" => SOCIAL_ICON,
                "type" => "heading"),
            array("name" => YAHOO,
                "desc" => YAHOO_DES,
                "id" => "yahoo",
                "type" => "text"),
            array("name" => BLOGGER,
                "desc" => BLOGGER_DES,
                "id" => "blogger",
                "type" => "text"),
            array("name" => FACEBOOK,
                "desc" => FACEBOOK_DES,
                "id" => "facebook",
                "type" => "text"),
            array("name" => TWITTER,
                "desc" => TWITTER_DES,
                "id" => "twitter",
                "type" => "text"),
            array("name" => RSS,
                "desc" => RSS_DES,
                "id" => "rss",
                "type" => "text"),
            array("name" => YOUTUBE,
                "desc" => YOUTUBE_DES,
                "id" => "youtube",
                "type" => "text"),
            array("name" => GOOGLE,
                "desc" => GOOGLE_DES,
                "id" => "plusone",
                "type" => "text"),
            array("name" => PINTEREST,
                "desc" => PINTEREST_DES,
                "id" => "pinterest",
                "type" => "text"),
//****=============================================================================****//
//****-------------This code is used for creating Bottom Footer Setting options-------------****//					
//****=============================================================================****//			
            array("name" => FOOTER_SETTINGS,
                "type" => "heading"),
            array("name" => "Footer Text",
                "desc" => FOOTER_DES,
                "id" => "inkthemes_footertext",
                "std" => "",
                "type" => "text"),
            //------------------------------------------------------------------//
//-------------This code is used for creating SEO description-------//							
//------------------------------------------------------------------//						
            array("name" => SEO_OPTIONS,
                "type" => "heading"),
            array("name" => META_KEY_WORD,
                "desc" => META_KEY_DES,
                "id" => "inkthemes_keyword",
                "std" => "",
                "type" => "textarea"),
            array("name" => META,
                "desc" => META_DES,
                "id" => "inkthemes_description",
                "std" => "",
                "type" => "textarea"),
            array("name" => META_AUTHOR,
                "desc" => META_AUTHOR_DES,
                "id" => "inkthemes_author",
                "std" => "",
                "type" => "textarea"),
        );
        geocraft_update_option('of_template', $options);
        geocraft_update_option('of_themename', $themename);
        geocraft_update_option('of_shortname', $shortname);
    }

}
?>