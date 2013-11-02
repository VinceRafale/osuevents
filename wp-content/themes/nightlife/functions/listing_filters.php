<?php /* For filtering of event listing */
add_action('init', 'tmpl_set_pregetposts');


/*
Name :tmpl_set_pregetposts
Description : to set the priority of filters on different pages 
*/
function tmpl_set_pregetposts(){
	if(is_home()){ 
	add_action('pre_get_posts', 'custom_post_author_archive',11);
	}else{
	add_action('pre_get_posts', 'custom_post_author_archive');
	}
}


/*
Name : custom_post_author_archive
Description : To filter home page listing with events only 
*/
function custom_post_author_archive( &$query )
{
	
	if(isset($_REQUEST['slider_search']) && $_REQUEST['slider_search']!=''){				
		remove_filter('posts_where', 'slider_searching_filter_where');		
		add_filter('posts_where', 'nightlife_searching_filter_where');		
	}
	if(isset($_REQUEST['search_template']) && $_REQUEST['search_template']==1)
	{
		remove_filter('posts_where', 'advance_search_template_where');	
		add_filter('posts_where', 'nightlife_recurring_search');		
	}
	$nav = array('nav-menu');
	if(isset($_REQUEST['search_type']) && $_REQUEST['search_type'] !='' && is_search()){
	
		$nav = array('nav-menu','attachment');
		$ptypes = array_merge($nav,explode(',',$_REQUEST['search_type']));
		$query->set('post_type', $ptypes); // set post type events 
		remove_action('pre_get_posts', 'custom_post_author_archive');
	}
	if(isset($_REQUEST['is_search']) && isset($_REQUEST['post_type']) && $_REQUEST['post_type']==CUSTOM_POST_TYPE_EVENT){
		/* filter work only with advance search */			
		remove_filter('posts_where', 'event_where');
		$query->set('post_type', $_REQUEST['post_type']); // set post type events 
		add_filter('posts_where', 'adv_searching_filter_where');
	}elseif(is_home() && !isset($_REQUEST['adv_search'])){
		$query->set('post_status', array('publish','private','inherit')); 
		add_filter('posts_where', 'event_where',11);
		add_filter('posts_orderby', 'event_orderby');
	}elseif(is_author())
	{		
		add_filter('posts_where', 'author_filter_where');
	}else if(is_archive() && !is_search())
	{	
		$query->set('post_status', array('publish','private','inherit'));
		add_filter('posts_orderby', 'category_filter_orderby');
		add_filter('posts_where', 'event_where');
	}
	elseif(is_search() && $_REQUEST['t']=='event')
	{	
		add_filter('posts_orderby', 'searching_filter_orderby');
		add_filter('posts_where', 'searching_filter_where');
	}
	elseif(is_search() && $_REQUEST['s']=='Calender-Event' && !isset($_REQUEST['adv_search']))
	{	
		add_filter('posts_where', 'search_cal_event_where');
	}
	elseif(isset($_REQUEST['adv_search']) && !isset($_REQUEST['slider_search']))
	{	
		remove_all_actions('posts_where');
		add_filter('posts_where', 'templ_event_searching_filter_where');
	}elseif(is_search() && !isset($_REQUEST['search_template']) && !isset($_REQUEST['adv_search']) && !isset($_REQUEST['slider_search']) && $_REQUEST['search_type']=='')
	{	
		$query->set('post_type',array('post','page','nav-menu','event')); // set post type events 		
	}		
}
/*
 * Function Name: search_cal_event_where
 * Return : apply filter when click on event date
 */
function search_cal_event_where($where)
{
	global $wpdb,$wp_query;
	$m = @$wp_query->query_vars['m'];
	$py = substr($m,0,4);
	$pm = substr($m,4,2);
	$pd = substr($m,6,2);
	$the_req_date = "$py-$pm-$pd";
	$event_of_month_sql = "select p.ID from $wpdb->posts p where (p.post_type='event' || p.post_type ='ads') and p.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'st_date' and pm.meta_value <= \"$the_req_date\" and pm.post_id in ((select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'end_date' and pm.meta_value>=\"$the_req_date\")))";
	$where = " AND ($wpdb->posts.post_type='event' || $wpdb->posts.post_type='ads') AND $wpdb->posts.ID in ($event_of_month_sql) and $wpdb->posts.post_status in ('publish','private')";

	return $where;
}
/*
 * Function Name: event_where
 * Return : add where clues in query post
 */
function event_where($where)
{	
	global $wpdb,$wp_query;	
	$current_term = $wp_query->get_queried_object();	
	remove_action('templ_after_post_content','event_custom_fields'); // remove thi because custom fields comes and concat with current where condition
	$tmpdata = get_option('templatic_settings');
	$templatic_current_tab =  $tmpdata['templatic-current_tab'];
	if( isset($current_term) && ($current_term->taxonomy == CUSTOM_CATEGORY_TYPE_EVENT || $current_term->taxonomy == CUSTOM_TAG_TYPE_EVENT) || is_home() || (is_archive() && !get_query_var('cat')))
	{
		if(!isset($_REQUEST['etype']))			
		{	
			$_REQUEST['etype']=($templatic_current_tab == '')?'current':$templatic_current_tab;
			$to_day = date('Y-m-d');
		}
		
		if(isset($_REQUEST['etype']) && $_REQUEST['etype']=='upcoming')
		{				
			$today = date('Y-m-d');
			$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='st_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') >'".$today."')) ";
		}			
		elseif(isset($_REQUEST['etype']) && $_REQUEST['etype']=='past')
		{				
			$today = date('Y-m-d');
			$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') < '".$today."')) ";
		}elseif($_REQUEST['etype']=='current')
		{
			$today = date('Y-m-d');
			$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='st_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') <='".$today."')) AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') >= '".$today."')) ";
		}
		$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value = 'Regular event'))";
	}	
	//echo $where;	
	return $where;
}
/*
Name : order by
Desc : posts order by
*/
function event_orderby($orderby){
	global $wpdb;
	$tmpdata = get_option('templatic_settings');
	$sorting =  @$tmpdata['templatic-sort_order'];
	/*if(isset($_REQUEST['etype']) && $_REQUEST['etype']=='upcoming'){				
			$today = date('Y-m-d');
			$orderby = " (select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") asc"; 
	}*/	
	if ( $sorting != '' )
	{
		global $wpdb;
		if ( $sorting == 'random' && isset($_REQUEST['etype']) && $_REQUEST['etype'] !='' )
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") ASC, rand()";
		}
		elseif ( $sorting == 'alphabetical' && isset($_REQUEST['etype']) && $_REQUEST['etype'] !='')
		{
			$orderby = "$wpdb->posts.post_title ASC";
		}elseif($sorting =='s_date' && isset($_REQUEST['etype']) && $_REQUEST['etype'] !=''){
			 $today = date('Y-m-d');
			 $orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") ASC";
		}
		elseif(isset($_REQUEST['etype']) && $_REQUEST['etype'] !='')
		{
			$today = date('Y-m-d');
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key = 'featured_h') ASC, $wpdb->posts.post_date DESC";
		}
	}
//	echo $orderby;
	return $orderby ;
}
/*
 * Function Name: category_filter_orderby
 * Return: pass orderby in post where clue
 */
function category_filter_orderby($orderby)
{      
	global $wpdb,$wp_query;	
	$current_term = $wp_query->get_queried_object();
	if(($current_term->taxonomy == CUSTOM_CATEGORY_TYPE_EVENT || $current_term->taxonomy == CUSTOM_TAG_TYPE_EVENT) || is_home() || (is_archive() && !get_query_var('cat')))
	{
		if (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'title_asc' )
		{
			$orderby = "$wpdb->posts.post_title ASC,(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key = 'featured_c') ASC";
		}
		elseif (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'title_desc' )
		{
			$orderby = "$wpdb->posts.post_title DESC,(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key = 'featured_c') ASC";
		}
		elseif (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'stdate_low_high' )
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") ASC";
		}
		elseif (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'stdate_high_low' )
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") DESC";
		}
		elseif (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'address_high_low' )
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"address\") ASC";
		}
		elseif (isset($_REQUEST['sortby']) && $_REQUEST['sortby'] == 'address_low_high' )
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"address\") DESC";
		}
		else
		{
			$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->postmeta.meta_key like \"st_date\") ASC ,(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key = 'featured_c') ASC,$wpdb->posts.ID DESC";
		}
	}

	return $orderby;
}

function searching_filter_orderby($orderby) {
	global $wpdb;
	$orderby = "  (select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=$wpdb->posts.ID and $wpdb->postmeta.meta_key like \"is_featured\") desc,$wpdb->posts.post_title ";
	return $orderby;	
}
function searching_filter_where($where) {
	global $wpdb;
	$skw = trim($_REQUEST['skw']);
	$scat = trim($_REQUEST['scat']);
	$saddress = trim($_REQUEST['saddress']);
	$sdate = trim($_REQUEST['sdate']);	
	
	$where = '';
	$where = " AND $wpdb->posts.post_type in ('event') AND ($wpdb->posts.post_status = 'publish') ";
	if($skw)
	{
		$where .= " AND (($wpdb->posts.post_title LIKE \"%$skw%\") OR ($wpdb->posts.post_content LIKE \"%$skw%\")) ";
	}
	if($sdate)
	{
		$where .= " AND $wpdb->posts.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'st_date' and pm.meta_value <= \"$sdate\" and pm.post_id in ((select pm2.post_id from $wpdb->postmeta pm2 where pm2.meta_key like 'end_date' and pm2.meta_value>=\"$sdate\"))) ";
	}
	if($scat>0)
	{
		$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
	}
	if($saddress)
	{
		$where .= " AND $wpdb->posts.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'address' and pm.meta_value like \"%$saddress%\") ";
	}

			 $post_meta_info = $wpdb->get_results("select * from $wpdb->posts where $wpdb->posts.post_type = 'custom_fields' AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID )) AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='is_search' and ($wpdb->postmeta.meta_value ='1' )))");
		 $return_arr = array();
		 if($post_meta_info){
			
			foreach($post_meta_info as $post_meta_info_obj){	
				if($post_meta_info_obj->ctype){
					$options = explode(',',$post_meta_info_obj->option_values);
				}
				$custom_fields = array(
						"name"		=> get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true),
						"label" 	=> $post_meta_info_obj->post_title,
						"htmlvar_name" 	=> get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true),
						"default" 	=> get_post_meta($post_meta_info_obj->ID,"default_value",true),
						"type" 		=> get_post_meta($post_meta_info_obj->ID,"ctype",true),
						"desc"      => $post_meta_info_obj->post_content,
						"option_values" => get_post_meta($post_meta_info_obj->ID,"option_values",true),
						"is_require"  => get_post_meta($post_meta_info_obj->ID,"is_require",true),
						"is_active"  => get_post_meta($post_meta_info_obj->ID,"is_active",true),
						"show_on_listing"  => get_post_meta($post_meta_info_obj->ID,"show_on_listing",true),
						"show_on_detail"  => get_post_meta($post_meta_info_obj->ID,"show_on_detail",true),
						"validation_type"  => get_post_meta($post_meta_info_obj->ID,"validation_type",true),
						"style_class"  => get_post_meta($post_meta_info_obj->ID,"style_class",true),
						"extra_parameter"  => get_post_meta($post_meta_info_obj->ID,"extra_parameter",true),
						);
				if($options)
				{
					$custom_fields["options"]=$options;
				}
				$return_arr[get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true)] = $custom_fields;
			}
		}

		$custom_metaboxes = $return_arr;

	foreach($custom_metaboxes as $key=>$val) {
	$name = $key;
		if($_REQUEST[$name]){
			$value = $_REQUEST[$name];
			if($name == 'event_desc'){
				$where .= " AND ($wpdb->posts.post_content like \"%$value%\" )";
			} else if($name == 'event_name'){
				$where .= " AND ($wpdb->posts.post_title like \"%$value%\" )";
			}else {
				$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$name' and ($wpdb->postmeta.meta_value like \"%$value%\" ))) ";
			}
		}
	}
	if(is_search()){
	$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$skw."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
	
	$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value = 'Regular event'))";
	}
	return $where;
}
function searching_no_filter_where($where) {
	global $wpdb;
	$s = trim($_REQUEST['s']);
	$where = " AND $wpdb->posts.post_type  in ('post','event') AND (($wpdb->posts.post_title LIKE \"%$s%\") OR ($wpdb->posts.post_content LIKE \"%$s%\") OR ($wpdb->postmeta.meta_key like 'address' and $wpdb->postmeta.meta_value like \"%$s%\"))) ";
	return $where;
}
/**-- serching fiter where for location and radious wise searching --**/
function adv_searching_filter_where($where){

	global $wpdb,$wp_query;
	remove_action('posts_where','get_search_post_fields_templ_plugin');
	if(strtolower($_REQUEST['location'])!='')
	{
		$address = urlencode($_REQUEST['location']);
		$saddress = $_REQUEST['location'];
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&v=3.5');
	}
	//$wp_query->set('post_type', array('event','attachment'));
	$output= json_decode($geocode);
	$lat = $output->results[0]->geometry->location->lat;
	$long = $output->results[0]->geometry->location->lng;
	$miles = $_REQUEST['radius'];
	$s_tag = $_REQUEST['search'];
	
	if(strtolower($_REQUEST['distance']) == strtolower('Kilometer')){
		$miles = $_REQUEST['radius'] * 0.621;
	}else{
		$miles = $_REQUEST['radius'];	
	}
	
	if($_REQUEST['radius'] == '')
		$miles = 100;
	
	$tbl_postcodes = $wpdb->prefix . "postcodes";
	$adv_search = $_REQUEST['adv_search'];
	if(strtolower($_REQUEST['date'])!='')
		$todate = $_REQUEST['date'];		
	if($todate!="")
	{
		$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='st_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') <='".$todate."')) AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') >= '".$todate."')) ";
	}
	
	if($address)
	{
		$where .= " AND (($wpdb->posts.ID in (SELECT post_id FROM  $tbl_postcodes WHERE truncate((degrees(acos( sin(radians(`latitude`)) * sin( radians('".$lat."')) + cos(radians(`latitude`)) * cos( radians('".$lat."')) * cos( radians(`longitude` - '".$long."') ) ) ) * 69.09),1) <= ".$miles." ORDER BY truncate((degrees(acos( sin(radians(`latitude`)) * sin( radians('".$lat."')) + cos(radians(`latitude`)) * cos( radians('".$lat."')) * cos( radians(`longitude` - '".$long."') ) ) ) * 69.09),1) ASC))";
			$where .= " OR $wpdb->posts.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'address' and pm.meta_value like \"%$saddress%\") )";
	}
	// Added for tags
	if(is_search() && $s_tag!=''){
		$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '%".$s_tag."%' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
	}	
	
	if(isset($_REQUEST['event_type']) && $_REQUEST['event_type']=='recurring')
	{
		$where.=" AND ($wpdb->posts.ID in ( select $wpdb->postmeta.post_id from $wpdb->postmeta inner join $wpdb->postmeta p1 on $wpdb->postmeta.post_id=p1.post_id where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value LIKE '%Recurring event%' AND p1.meta_key='recurring_search_date' AND p1.meta_value LIKE '%$todate%') )";
		
	}else
	{
		$where.=" AND ($wpdb->posts.ID in ( select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value ='Regular event') )";
	}
	return $where;
	
}
function add_author_photo($content)
{
	global $post;
    echo get_avatar($post->post_author, 75 );
}

function author_filter_where($where)
{
	global $wpdb,$current_user,$curauth,$wp_query;
	$query_var = @$wp_query->query_vars;

	$user_id = $query_var['author'];
	$where = " AND ($wpdb->posts.post_author = $user_id) ";
	$post_ids = get_user_meta($user_id,'user_attend_event',true);
	$final_ids = '';
	if($post_ids)
	  {
		foreach($post_ids as $key=>$value)
		 {
		  if($value != '')
		    {
			 $final_ids .= str_replace('#','',$value).',';
		    }
	    }
		$post_ids = substr($final_ids,0,-1);
	 }
	 
	 if(is_plugin_active('wpml-translation-management/plugin.php')){
			$language = ICL_LANGUAGE_CODE;
			$language_where=" AND t.language_code='".$language."'";
	 }
	if(isset($_REQUEST['list']) && $_REQUEST['list'] == 'attend')	{		
			$where = '';
			$where .= " AND ($wpdb->posts.ID in ($post_ids)) AND ($wpdb->posts.post_type = 'event') AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private') ".$language_where;
	}
	else
	{
		if($current_user->ID==$user_id)
		{
			$where .= " AND ($wpdb->posts.post_type = '".CUSTOM_POST_TYPE_EVENT."') AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'draft') ".$language_where;
		}else
		{
			$where .= " AND ($wpdb->posts.post_type = '".CUSTOM_POST_TYPE_EVENT."')AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'draft') ".$language_where;
		}
	}
	return $where;
}
function templ_event_searching_filter_where($where)
{
	global $wpdb;
		$serch_post_types = $_REQUEST['post_type'];
		$s = get_search_query();
	//	$custom_metaboxes = get_search_event_fields_templ_plugin($serch_post_types,'','user_side','1');
		 $post_meta_info = $wpdb->get_results("select * from $wpdb->posts where $wpdb->posts.post_type = 'custom_fields' AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.post_id = $wpdb->posts.ID )) AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='is_search' and ($wpdb->postmeta.meta_value ='1' )))");
		 $return_arr = array();
		 if($post_meta_info){
			
			foreach($post_meta_info as $post_meta_info_obj){	
				if($post_meta_info_obj->ctype){
					$options = explode(',',$post_meta_info_obj->option_values);
				}
				$custom_fields = array(
						"name"		=> get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true),
						"label" 	=> $post_meta_info_obj->post_title,
						"htmlvar_name" 	=> get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true),
						"default" 	=> get_post_meta($post_meta_info_obj->ID,"default_value",true),
						"type" 		=> get_post_meta($post_meta_info_obj->ID,"ctype",true),
						"desc"      => $post_meta_info_obj->post_content,
						"option_values" => get_post_meta($post_meta_info_obj->ID,"option_values",true),
						"is_require"  => get_post_meta($post_meta_info_obj->ID,"is_require",true),
						"is_active"  => get_post_meta($post_meta_info_obj->ID,"is_active",true),
						"show_on_listing"  => get_post_meta($post_meta_info_obj->ID,"show_on_listing",true),
						"show_on_detail"  => get_post_meta($post_meta_info_obj->ID,"show_on_detail",true),
						"validation_type"  => get_post_meta($post_meta_info_obj->ID,"validation_type",true),
						"style_class"  => get_post_meta($post_meta_info_obj->ID,"style_class",true),
						"extra_parameter"  => get_post_meta($post_meta_info_obj->ID,"extra_parameter",true),
						);
				if($options)
				{
					$custom_fields["options"]=$options;
				}
				$return_arr[get_post_meta($post_meta_info_obj->ID,"htmlvar_name",true)] = $custom_fields;
			}
		}
		
		$todate = trim($_REQUEST['todate']);
		//$todate = date('Y-m-d G:i:s');
		$frmdate = trim($_REQUEST['frmdate']);
		$articleauthor = trim($_REQUEST['articleauthor']);
		$exactyes = trim($_REQUEST['exactyes']);
		
		if($todate!="" && $frmdate=="")
		{
			$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') >='".$todate."'";
		}
		else if($frmdate!="" && $todate=="")
		{
			
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') <='".$frmdate."'";
		}
		else if($todate!="" && $frmdate!="")
		{
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') BETWEEN '".$todate."' and '".$frmdate."'";
			
		}
		if($articleauthor!="" && $exactyes!=1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  like '".$articleauthor."') ";
		}
		if($articleauthor!="" && $exactyes==1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  = '".$articleauthor."') ";
		}
		$custom_metaboxes = $return_arr;
		foreach($custom_metaboxes as $key=>$val) {
		$name = $key;
			if($_REQUEST[$name]){ 
				$value = $_REQUEST[$name];
				if($name == 'proprty_desc' || $name == 'event_desc'){
					$where .= " AND ($wpdb->posts.post_content like \"%$value%\" )";
				} else if($name == 'property_name'){
					$where .= " AND ($wpdb->posts.post_title like \"%$value%\" )";
				}else {
					$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$name' and ($wpdb->postmeta.meta_value like \"%$value%\" ))) ";
					/* Placed "AND" instead of "OR" because of Vedran said results are ignoring address field */
				}
			}
		}
		
		if(isset($_REQUEST['category']))
		{
			$scat = $_REQUEST['category'];
			$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
		}
		
		 /* Added for tags searching */
		if(is_search() && !@$_REQUEST['category']){
			$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$s."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
		}	
		
	return $where;
}
/*
Name :nightlife_searching_filter_where
Description : slider search widget for regular/recurring
*/
function nightlife_searching_filter_where($where){
		global $wpdb,$wp_query;	
	
	remove_action('posts_where','get_search_post_fields_templ_plugin');
	if((isset($_REQUEST['date']) && $_REQUEST['date']!='') || (isset($_REQUEST['location']) && $_REQUEST['location']!=''))
	{
		if(strtolower($_REQUEST['location'])!='')
		{
			$address = urlencode($_REQUEST['location']);
			$saddress = $_REQUEST['location'];
			$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&v=3.5');
		}
		//$wp_query->set('post_type', array('event','attachment'));
		$output= json_decode($geocode);
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;
		$miles = $_REQUEST['radius'];
		if(isset($_REQUEST['s']) && trim($_REQUEST['s']) != '')
		{
			$s_tag = $_REQUEST['s'];
		}
		$event_type = $_REQUEST['event_type'];
		if(!$event_type){
			$event_type = 'regular';
		}
		if(strtolower($_REQUEST['distance']) == strtolower('Kilometer')){
			$miles = $_REQUEST['radius'] * 0.621;
		}else{
			$miles = $_REQUEST['radius'];	
		}
		
		if($_REQUEST['radius'] == '')
			$miles = 100;
		
		$tbl_postcodes = $wpdb->prefix . "postcodes";
		$adv_search = $_REQUEST['adv_search'];
		if(strtolower($_REQUEST['date'])!='')
			$todate = $_REQUEST['date'];	
		$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value = 'Regular event'))";			
		if($todate!="")
		{
			$where .= "  AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='st_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') <='".$todate."')) AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='end_date' and date_format($wpdb->postmeta.meta_value,'%Y-%m-%d') >= '".$todate."')) ";
		}
		/* make address with OR because user will search from only address too - suggested by vedran*/
		if($address)
		{			
			$where .= " AND (($wpdb->posts.ID in (SELECT post_id FROM  $tbl_postcodes WHERE truncate((degrees(acos( sin(radians(`latitude`)) * sin( radians('".$lat."')) + cos(radians(`latitude`)) * cos( radians('".$lat."')) * cos( radians(`longitude` - '".$long."') ) ) ) * 69.09),1) <= ".$miles." ORDER BY truncate((degrees(acos( sin(radians(`latitude`)) * sin( radians('".$lat."')) + cos(radians(`latitude`)) * cos( radians('".$lat."')) * cos( radians(`longitude` - '".$long."') ) ) ) * 69.09),1) ASC))";
			$where .= " OR $wpdb->posts.ID in (select pm.post_id from $wpdb->postmeta pm where pm.meta_key like 'address' and pm.meta_value like \"%$saddress%\") )";
		}
		// Added for tags
		if(is_search() && $s_tag){
			$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '%".$s_tag."%' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
		}		
	

		return $where;	
	}

	return $where;	
}

function nightlife_recurring_search($where)
{	
	if(isset($_REQUEST['search_template']) && $_REQUEST['search_template']==1 && is_search())
	{		
		global $wpdb;
		$todate = trim($_REQUEST['todate']);		
		$frmdate = trim($_REQUEST['frmdate']);
		$articleauthor = trim($_REQUEST['articleauthor']);
		$exactyes = trim($_REQUEST['exactyes']);
		
		if(isset($_REQUEST['todate']) && $_REQUEST['todate'] != ""){
			$todate = $_REQUEST['todate'];
			$todate= explode('/',$todate);
			$todate = $todate[2]."-".$todate[0]."-".$todate[1];
			
		}
		if(isset($_REQUEST['frmdate']) && $_REQUEST['frmdate'] != ""){
			$frmdate = $_REQUEST['frmdate'];
			$frmdate= explode('/',$frmdate);
			$frmdate = $frmdate[2]."-".$frmdate[0]."-".$frmdate[1];
		}
		
		
		
		if($todate!="" && $frmdate=="")
		{
			$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') >='".$todate."'";
		}
		else if($frmdate!="" && $todate=="")
		{
			
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') <='".$frmdate."'";
		}
		else if($todate!="" && $frmdate!="")
		{
			$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d %G:%i:%s') BETWEEN '".$todate."' and '".$frmdate."'";
			
		}
		if($articleauthor!="" && $exactyes!=1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  like '".$articleauthor."') ";
		}
		if($articleauthor!="" && $exactyes==1)
		{
			$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  = '".$articleauthor."') ";
		}		
		//search custom field
		if(isset($_REQUEST['search_custom']) && is_array($_REQUEST['search_custom']))
		{
			foreach($_REQUEST['search_custom'] as $key=>$value)
			{		
				if($_REQUEST[$key]!="" && $key != 'category')
				{
					$where .= " AND ($wpdb->posts.ID in (select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='$key' and ($wpdb->postmeta.meta_value like \"%$_REQUEST[$key]%\" ))) ";
					
				}
			}
		}
		//finish custom field			
		
		if(isset($_REQUEST['category']) && $_REQUEST['category']!="")
		{
			$scat = $_REQUEST['category'];
			$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
		}
		
		 /* Added for tags searching */
		if(is_search() && $_REQUEST['category']!=""){
			$where .= " OR  ($wpdb->posts.ID in (select p.ID from $wpdb->terms c,$wpdb->term_taxonomy tt,$wpdb->term_relationships tr,$wpdb->posts p ,$wpdb->postmeta t where c.name like '".$s."' and c.term_id=tt.term_id and tt.term_taxonomy_id=tr.term_taxonomy_id and tr.object_id=p.ID and p.ID = t.post_id and p.post_status = 'publish' group by  p.ID))";
		}	
		if(isset($_REQUEST['todate']) && $_REQUEST['todate']!="")
			$todate = $todate;
		else
			$todate = date("Y-m-d");			
		
		if(isset($_REQUEST['event_type']) && $_REQUEST['event_type']=='recurring')
		{
			$where.=" AND ($wpdb->posts.ID in ( select $wpdb->postmeta.post_id from $wpdb->postmeta inner join $wpdb->postmeta p1 on $wpdb->postmeta.post_id=p1.post_id where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value LIKE '%Recurring event%' AND p1.meta_key='recurring_search_date' AND p1.meta_value NOT LIKE '%$todate%') )";
			
		}else
		{
			$where.=" AND ($wpdb->posts.ID in ( select $wpdb->postmeta.post_id from $wpdb->postmeta where $wpdb->postmeta.meta_key='event_type' and $wpdb->postmeta.meta_value ='Regular event') )";
		}					
		return $where;
	}
	
	return $where;
}

/*
NAME : FUNCTION TO FETCH CUSTOM POST TYPE IN RSS FEEDS
DESCRIPTION : THIS FUNCTION FETCHES THE DATA IN RSS FEEDS
*/
function myfeed_request($qv) {
	if (isset($qv['feed']))
		$qv['post_type'] = get_post_types();
	return $qv;
}
add_filter('request', 'myfeed_request');
/* EOF - FETCH EVENTS IN RSS FEEDS */
function remove_recurring_event( $clauses, $wp_query ) {
	
	global $wpdb;

	$clauses['join'] .= " INNER JOIN $wpdb->postmeta m3 ON (ID = m3.post_id AND m3.meta_key = 'event_type')";

	$clauses['where'] .= " AND (m3.meta_value = 'Regular event' )";

  return $clauses;
}
?>