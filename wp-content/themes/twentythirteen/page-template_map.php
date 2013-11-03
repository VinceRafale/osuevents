<?php
/*
Template Name: Page - Map Display
*/
get_header();
global $post;
?>
<div id="post-<?php the_ID()?>" class="hentry page publish">
     <h1 class="page-title entry-title">
          <a href="<?php the_permalink();?>"><?php the_title()?></a>
     </h1>
      <div class="post_image">
     	<?php echo get_the_post_thumbnail($page->ID, 'large'); ?>
     </div>
     <div class="entry-content">   
		<?php 
          $content = $post->post_content;
          $content = apply_filters('the_content', $content);	
          echo $content;
          ?>        
     </div><!-- .entry-content -->

<?php
$post_type = get_post_meta($post->ID,'template_post_type',true);
$map_image_size=get_post_meta($post->ID,'map_image_size',true);
$postid=$post->ID;
$width = get_post_meta($post->ID,'map_width',true)?get_post_meta($post->ID,'map_width',true) :950;
$heigh = get_post_meta($post->ID,'map_height',true)?get_post_meta($post->ID,'map_height',true) :425;
function get_categories_name($post_type)
{
	$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));	
	if($post_type!='post'){		
		$taxo=$taxonomies[0];		
	}else
		$taxo='category';			
	
	$cat_args = array(
				'taxonomy'=>$taxo,
				'orderby' => 'name', 				
				'hierarchical' => 'true',
				'title_li'=>''
			);	
		
	$tax_terms = get_terms($cat_args);
	$categories_name=apply_filters('widget_categories_args', $cat_args);	
	$r = wp_parse_args( $categories_name);
	
	return get_categories( $r );
	
}
function get_categories_post_info($catname_arr,$post_type,$map_image_size='thumbnail')
{
	foreach($catname_arr as $cat)
	{	
		$catname=$cat->slug;
		$cat_ID=$cat->term_id;		
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));	
		if($post_type!='post'){		
			$taxo=$taxonomies[0];		
		}else
			$taxo='category';			
		remove_all_actions('posts_where');
		$args=array( 
        'post_type' => trim($post_type),
        'posts_per_page' => -1    ,
        'post_status' => 'publish',             
        'tax_query' => array(                
            array(
                'taxonomy' =>$taxo,
                'field' => 'id',
                'terms' => $cat_ID,
                'operator'  => 'IN'
            )            
         ),        
        'order_by'=>'date',
        'order' => 'ASC'
        );		  	  		 
		$post_details= new WP_Query($args);	
		$content_data='';
		if ($post_details->have_posts()) :
			$srcharr = array("'");
			$replarr = array("\'");
			while ( $post_details->have_posts() ) : $post_details->the_post();									
					$ID =get_the_ID();				
					$title = get_the_title($ID);
					$plink = get_permalink($ID);
					$lat = get_post_meta($ID,'geo_latitude',true);
					$lng = get_post_meta($ID,'geo_longitude',true);					
					$address = str_replace($srcharr,$replarr,(get_post_meta($ID,'address',true)));
					$contact = str_replace($srcharr,$replarr,(get_post_meta($ID,'contact',true)));
					$timing = str_replace($srcharr,$replarr,(get_post_meta($ID,'timing',true)));						
					$post_img = bdw_get_images_plugin($ID,$map_image_size);					
					$post_images = $post_img[0]['file'];
					if($post_images)
						$post_image='<img src="'.$post_images.'" />';
					else
						$post_image='<img src="'.TEMPL_PLUGIN_URL.'tmplconnector/monetize/images/no-image.png" />';
					
					$term_icon=TEMPL_PLUGIN_URL."tmplconnector/monetize/images/pin.png";					
					if($lat && $lng)
					{ 
						$retstr ="{";
						$retstr .= "'name':'$title',";
						$retstr .= "'location': [$lat,$lng],";
						$retstr .= "'message':'<div class=\"forrent\">$post_image";
						$retstr .= "<h6><a href=\"$plink\" class=\"ptitle\" style=\"color:#444444;font-size:14px;\"><span>$title</span></a></h6>";
						if($address){$retstr .= "<span style=\"font-size:10px;\">$address</span>";}
						$retstr .= "<p class=\"link-style1\"><a href=\"$plink\" class=\"$title\">$more</a></p></div>";
						$retstr .= "',";
						$retstr .= "'icons':'$term_icon',";
						$retstr .= "'pid':'$ID'";
						$retstr .= "}";						
						$content_data[] = $retstr;
					}				
			endwhile;		
		endif;
		if($content_data)	
			$cat_content_info[]= "'$catname':[".implode(',',$content_data)."]";			
	}			
	if($cat_content_info!="")	
		return implode(',',$cat_content_info);
	else
		return '';
		exit;
}
$catname_arr=get_categories_name($post_type);
$catinfo_arr = get_categories_post_info($catname_arr,$post_type,$map_image_size);
if(is_ssl()){
	$url = "https://maps.googleapis.com/maps/api/js?sensor=false&v=3.5";
}else{
	$url = "http://maps.googleapis.com/maps/api/js?sensor=false&v=3.5";
}
?>
<script type="text/javascript" src="<?php echo $url; ?>"></script>
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-custom_fields/js/markermanager.js"></script>
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-custom_fields/js/markerclusterer_packed.js"></script>
<script type="text/javascript">
	<?php
		//map type
		if(get_post_meta($postid,'map_type',true)!=""){
			$maptype=get_post_meta($postid,'map_type',true);
		}
		else { 
			$maptype = 'ROADMAP';
		}
		//map display
		if(get_post_meta($postid,'map_display',true)=='Fit all available listing'){
			$fmaptype=1;
		}
		else { 
			$fmaptype = 0;
		}
		//map zoom level
		if(get_post_meta($postid,'map_zoom_level',true)!=""){
			$mapzoom = get_post_meta($postid,'map_zoom_level',true);
		}else{	
			$mapzoom = 13;	
		}
		//map center latitude
		if(get_post_meta($postid,'map_center_latitude',true)!=""){
			$ma_lat=get_post_meta($postid,'map_center_latitude',true);
		}else{
			$ma_lat = 21.167086220869788;
		}
		//map center longitude
		if(get_post_meta($postid,'map_center_longitude',true)!=""){
			$ma_long=get_post_meta($postid,'map_center_longitude',true);
		}else{
			$ma_long =72.82231945000001;
		}	
	?>
	var CITY_MAP_CENTER_LAT= '<?php echo $ma_lat?>';
	var CITY_MAP_CENTER_LNG= '<?php echo $ma_long?>';
	var CITY_MAP_ZOOMING_FACT= <?php echo $mapzoom;?>;
	var infowindow;
	<?php if($fmaptype == 1) { ?>
	 var multimarkerdata = new Array();
	<?php }?>
	var zoom_option = '<?php echo $fmaptype; ?>';
	var markers = {<?php echo $catinfo_arr;?>};
	
	//var markers = '';
	var map = null;
	var mgr = null;
	var mc = null;
	var markerClusterer = null;
	var showMarketManager = false;
	if(CITY_MAP_CENTER_LAT=='')
	{
		var CITY_MAP_CENTER_LAT = 34;	
	}
	if(CITY_MAP_CENTER_LNG=='')
	{
		var CITY_MAP_CENTER_LNG = 0;	
	}
	if(CITY_MAP_CENTER_LAT!='' && CITY_MAP_CENTER_LNG!='' && CITY_MAP_ZOOMING_FACT =='')
	{
		var CITY_MAP_ZOOMING_FACT = 13;
	}else if(CITY_MAP_ZOOMING_FACT == '')
	{
		var CITY_MAP_ZOOMING_FACT = 3;
	} 
	var PIN_POINT_ICON_HEIGHT = 32;
	var PIN_POINT_ICON_WIDTH = 20;
	
	if(MAP_DISABLE_SCROLL_WHEEL_FLAG)
	{
		var MAP_DISABLE_SCROLL_WHEEL_FLAG = 'No';	
	}
	
	function setCategoryVisiblity( category, visible ) {		
	   var i;
	   if ( mgr && category in markers ) {
		  for( i = 0; i < markers[category].length; i += 1 ) {
			 if ( visible ) {
				mgr.addMarker( markers[category][i], 0 );
			 } else {
				mgr.removeMarker( markers[category][i], 0 );
			 }
		  }
		  mgr.refresh();
	   }
	}
	
	function initialize() {

		  var myOptions = {
			zoom: CITY_MAP_ZOOMING_FACT,
			center: new google.maps.LatLng(CITY_MAP_CENTER_LAT, CITY_MAP_CENTER_LNG),
			mapTypeId: google.maps.MapTypeId.<?php echo $maptype;?>
		  }
		   map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
		   mgr = new MarkerManager( map );
		   google.maps.event.addListener(mgr, 'loaded', function() {
			  if (markers) {				  
				 for (var level in markers) {					 	
					google.maps.event.addDomListener( document.getElementById( level ), 'click', function() {
					   setCategoryVisiblity( this.id, this.checked );
					});	
					
					for (var i = 0; i < markers[level].length; i++) {						
					   var details = markers[level][i];					  
					   var image = new google.maps.MarkerImage(details.icons,new google.maps.Size(PIN_POINT_ICON_WIDTH, PIN_POINT_ICON_HEIGHT));
					   var myLatLng = new google.maps.LatLng(details.location[0], details.location[1]);
					   <?php if($fmaptype == 1) { ?>
						 multimarkerdata[i]  = new google.maps.LatLng(details.location[0], details.location[1]);
					   <?php } ?>
					   markers[level][i] = new google.maps.Marker({
						  title: details.name,
						  position: myLatLng,
						  icon: image,
						  clickable: true,
						  draggable: false,
						  flat: true
					   });					   
					   
					attachMessage(markers[level][i], details.message);
					}
					mgr.addMarkers( markers[level], 0 );
				 }
				  <?php if($fmaptype == 1) { ?>
					 var latlngbounds = new google.maps.LatLngBounds();
					for ( var j = 0; j < multimarkerdata.length; j++ )
						{
						 latlngbounds.extend( multimarkerdata[ j ] );
						}
					   map.fitBounds( latlngbounds );
				  <?php } ?>
				 mgr.refresh();
			  }
		   });
		   
			// but that message is not within the marker's instance data 
			function attachMessage(marker, msg) {
			  var myEventListener = google.maps.event.addListener(marker, 'click', function() {
				 if (infowindow) infowindow.close();
				infowindow = new google.maps.InfoWindow(
				  { content: String(msg) 
				  });
				 infowindow.open(map,marker);
			  });
			}
			
	}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
     <div class="top_banner_section_in clearfix">
     
             <div id="map_canvas" style="width: 100%; height:<?php echo $heigh;?>px" class="map_canvas"></div>        
             <?php if($catname_arr){?>
             <div class="map_category" id="toggleID">
             <?php foreach($catname_arr as $catname): ?>
               <label>
                    <input type="checkbox" value="<?php echo $catname->name;?>" checked="checked" id="<?php echo $catname->slug;?>" name="<?php echo $catname->slug;?>">
                     <img height="14" width="8" alt="" src="<?php echo TEMPL_PLUGIN_URL."tmplconnector/monetize/images/pin.png";?>"> <?php echo $catname->slug;?>
                 </label> 
               <?php endforeach;?>
             </div>
               <div id="toggle" class="toggleoff" onclick="toggle();"></div>
             <?php }?>	
     </div>
</div>
<?php get_footer();?>