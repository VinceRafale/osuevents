<?php ob_start();
	$file = dirname(__FILE__);
	$file = substr($file,0,stripos($file, "wp-content"));
	require($file . "/wp-load.php");
	global $wpdb;
	if(function_exists('hybrid_get_setting')){
		$color1 = hybrid_get_setting( 'color_picker_color1' );
		$color2 = hybrid_get_setting( 'color_picker_color2' );
		$color3 = hybrid_get_setting( 'color_picker_color3' );
		$color4 = hybrid_get_setting( 'color_picker_color4' );
		$color5 = hybrid_get_setting( 'color_picker_color5' );
		$color6 = hybrid_get_setting( 'color_picker_color6' );
	}else{
		$supreme_theme_settings = get_option('supreme_theme_settings');
		$color1 = $supreme_theme_settings[ 'color_picker_color1' ];
		$color2 = $supreme_theme_settings[ 'color_picker_color2' ];
		$color3 = $supreme_theme_settings[ 'color_picker_color3' ];
		$color4 = $supreme_theme_settings[ 'color_picker_color4' ];
		$color5 = $supreme_theme_settings[ 'color_picker_color5' ];
		$color6 = $supreme_theme_settings[ 'color_picker_color6' ];
	}
if($color1 != "#" || $color1 != ""){?>

	.index_column h4,
	.eventlist li .content span.date,
	.indexlist div.content span.date,
	.taxonomy #loop_taxonomy h2.date, 
	#content .event_search .entry h2.date,
	.widget #wp-calendar td a,
	.event_detail p,
	div#menu-primary .sub_event ul li a,
	div#menu-secondary .menu li a:hover, 
	div#menu-secondary .menu li.current-menu-item a, 
	div#menu-secondary .menu li.current_page_item a, 
	div#menu-subsidiary .menu li a:hover, 
	div#menu-subsidiary .menu li.current-menu-item a, 
	div#menu-subsidiary .menu li.current_page_item a,
	div#menu-primary .menu li a:hover,
	div#menu-primary .menu li.current-menu-item a, 
	div#menu-primary .menu li.current_page_item a,
	#content .avatar, .top_line .category a,
	.entry-meta .moretag,
	.eventlist div .content span.title a:hover,
	.footer_widget .widget ul li a:hover,
	.widget ul li a:hover,
	#content .attending_event .addtofav:hover,
	.realated_post a:hover,
	.eventlist li .content span.title a:hover,
	.copyright a:hover,
	.singular-event #content .calendar a:hover,
	#breadcrumb a:hover,
	.breadcrumb a:hover,
	.bbp-breadcrumb a:hover,
	.form_row label:hover,
	.entry-title a:hover,
	.taxonomy #loop_taxonomy .entry .bottom_line a:hover, 
	#content .event_search .entry .bottom_line span a:hover,
	.byline a:hover, .entry-meta a:hover,
	.sidebar .widget .newsletter a:hover, 
	.sidebar .widget .news_subscribe input[type="submit"]:hover,
	.arclist ul li a:hover,
	.sidebar .event a:hover,
	.pagination span.current,
	.comment-pagination .current,
	.bbp-pagination .current,
	.widget-title, .sidebar h3 {
		color: <?php echo $color1;?>;
	}
	.widget #wp-calendar caption,
	button, input[type="reset"],
	input[type="submit"], 
	input[type="button"], 
	.upload, 
	.button,
	.ui-datepicker .ui-datepicker-header,
	div#menu-secondary .menu li a:hover:before,
	#content .flexslider_inner .flex-control-nav a:hover, #content .flexslider_inner .flex-control-nav a.flex-active,
	.flexslider_inner .slider_content,
	.slide_event_info .image,
	.slider_content .search_box input.submit:hover,
	.slider_content .search_box,
	.postpagination a:hover, 
	.postpagination a.active {
		background-color: <?php echo $color1;?>;
	}
    
    .widget #wp-calendar caption {
    	border-color: <?php echo $color1;?>;
        }
        
    .reverse:hover,
	.priview_post_btn:hover {
		background-color: <?php echo $color1;?>;
	}
    
	input[type="date"]:focus, 
	input[type="datetime"]:focus, 
	input[type="datetime-local"]:focus, 
	input[type="email"]:focus, input[type="month"]:focus, 
	input[type="number"]:focus, input[type="password"]:focus, 
	input[type="search"]:focus, 
	input[type="tel"]:focus, input[type="text"]:focus, 
	input.input-text:focus, input[type="time"]:focus,
	input[type="url"]:focus, 
	input[type="week"]:focus,
	select:focus, textarea:focus,
	.loop-nav span.previous:hover, 
	.loop-nav span.next:hover, 
	.pagination .page-numbers:hover, 
	.comment-pagination .page-numbers:hover, 
	.bbp-pagination .page-numbers:hover, 
	#content .pos_navigation .post_right a:hover, 
	#content .pos_navigation .post_left a:hover,
	.image_title_space ul.more_photos li img:hover,
	.slider_content .search_box .input_grey,
	.pagination .current,
	.comment-pagination .current,
	.bbp-pagination .current,
	.related_post_grid_view li a.post_img img:hover {
		border-color: <?php echo $color1;?>;
	}
	.index_column h4 {
		border-bottom: 3px solid <?php echo $color1;?> !important;
	}
	

<?php }



if($color2 != "#" || $color2 != ""){?>

	a,
	.widget ul li a,
	div#menu-secondary .menu li a, 
	div#menu-subsidiary .menu li a,
	.recent_comments li a.title,
	.taxonomy #loop_taxonomy .entry .bottom_line a, #content .event_search .entry .bottom_line span a {
		color: <?php echo $color2;?>;
	}
	.slider_content .search_box input.submit {
		background-color: <?php echo $color2;?>;
	}
    
    
<?php }



if($color3 != "#" || $color3 != ""){?>

	body,
	.sidebar .widget .recent_comments a.comment_excerpt,
	.templatic_about_us p.line01,
	.templatic_about_us h2,
	.eventlist li.content span.title b,
	.eventlist div.content span.title b,
	.widget ul li,
	.smart_tab a,
	.loop-nav span.previous,
	.loop-nav span.next,
	.pagination .page-numbers,
	.comment-pagination .page-numbers,
	.bbp-pagination .page-numbers,
	#content .pos_navigation .post_right a,
	#content .pos_navigation .post_left a,
	.slider_content .search_box .input_white,
	.slider_content .search_box .input_grey,
	.widget #wp-calendar td a:hover,
	.singular-event #content .calendar a,
	.get_direction a.getdir, .get_direction a.large_map,
	.singular-event #content .event_social_media .post_views,
	.singular-event #content h2, #content h2,
	#container h1,
	#content h3,
	.comment-author cite,
	.comment-text,
	.comment-meta .published:hover, .comment-meta a:hover,
	.comment-reply-link, .comment-reply-login,
	.index_column .viewall:hover,
	.singular-event #content .description h3,
	.sidebar .widget .newsletter a,
	.taxonomy #loop_taxonomy .entry .postmetadata ul li label, .taxonomy #loop_taxonomy .entry .bottom_line, .taxonomy #loop_taxonomy .entry p.date span, #content .event_search .entry p.date span, #content .event_search .entry .bottom_line span, .news_subscribe p, ul.ui-tabs-nav li a, .tabber ul li a {
		color: <?php echo $color3;?>;
	}
	.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited,
	.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
		color: <?php echo $color3;?> !important;
	}
    
    

<?php }




if($color4 != "#" || $color4 != ""){?>

	input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input.input-text, input[type="time"], input[type="url"], input[type="week"], select, textarea, .footer_widget .column01 .news_subscribe input[type="text"], #menu-primary .widget form input[type="text"], #menu-primary .widget form input[type="text"]:focus, .get_direction input[type="text"],
	.index_column .viewall,
	div#menu-primary .menu li a,
	.footer_widget h3,
	.footer_widget .widget ul li a,
	.footer_widget .widget ul li,
	#footer p,
	.copyright a,
	.widget #wp-calendar th,
	.widget #wp-calendar .calendar_tooltip .event_title,
	#breadcrumb, .breadcrumb, .bbp-breadcrumb,
	#breadcrumb a, .breadcrumb a, .bbp-breadcrumb a,
	.event_detail p span,
	form#commentform textarea:focus,
	.comment-meta abbr,
	.comment-meta .published,
	.comment-meta a,
	.comment-reply-link:hover,
	.comment-reply-login:hover,
	div#menu-primary .sub_event ul li a:hover,
	.sidebar .widget .newsletter h3,
	.sidebar .widget .news_subscribe h3 {
		color: <?php echo $color4;?>;
	}
	.sidebar .calendar_widget td.date_n small,
	.sidebar .calendar_widget td.date_n small b,
	.ui-datepicker th {
		color: <?php echo $color4;?> !important;
	}

<?php }


if($color5 != "#" || $color5 != ""){?>

	body,
	.header_bg2,
	.loop-nav,
	.widget #wp-calendar td,
	.search_box,
	.slider_content .search_box .input_white {
		background-color: <?php echo $color5;?>;
	}

<?php }

if($color6 != "#" || $color6 != ""){?>

	
	.footer_bg1,
	.footer_bg2,
	.widget #wp-calendar th,
	div#menu-primary .menu li li a,
	div#menu-primary .menu li li a:hover,
	.pagination .current,
	.comment-pagination .current,
	.bbp-pagination .current,
	.loop-nav span.previous,
	.loop-nav span.next,
	.pagination .page-numbers,
	.comment-pagination .page-numbers,
	.bbp-pagination .page-numbers,
	#content .pos_navigation .post_right a,
	#content .pos_navigation .post_left a,
	table.calendar_widget td.date_n div span.calendar_tooltip,
	form#commentform p.log-in-out {
		background-color: <?php echo $color6;?>;
	}
	.header_bg1 {
		background: <?php echo $color6;?>;
	}

<?php } ?>


<?php
$color_data = ob_get_contents();
ob_clean();
if(isset($color_data) && $color_data !=''){?>
	<style type="text/css">
		<?php echo $color_data;?>
	</style>
<?php 
}
?>





