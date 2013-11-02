<?php
/*	Code By Templatic Start */

/**
 * The Google Map widget displays the google map to user. Users will able to see their own address on goole map.
 *
 * @author Templatic.com
 */

if(!class_exists('templatic_google_map'))
{
	class templatic_google_map extends WP_Widget {
		function __construct() {
		//Constructor
		
			$widget_options = array(
				'classname' => 'googlemap',
				'description' => esc_html__( 'Google Map widget displays the google map to user with their own address.', 'templatic' )
			);

			/* Set up the widget control options. */
			$control_options = array(
				'width' => 450,
				'height' => 350
			);

			/* Create the widget. */
			$this->WP_Widget(
				'templatic_google_map',		// $this->id_base
				__( 'T &rarr; Google Map Widget', 'templatic' ),	// $this->name
				$widget_options,			// $this->widget_options
				$control_options			// $this->control_options
			);
		}
		
		
		function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$address_latitude = empty($instance['address_latitude']) ? '0' : apply_filters('widget_address_latitude', $instance['address_latitude']);
			$address_longitude = empty($instance['address_longitude']) ? '34' : apply_filters('widget_address_longitude', $instance['address_longitude']);
			$address = empty($instance['address']) ? '' : apply_filters('widget_address', $instance['address']);
			$map_type = empty($instance['map_type']) ? 'ROADMAP' : apply_filters('widget_map_type', $instance['map_type']);
			$map_width = empty($instance['map_width']) ? '200' : apply_filters('widget_map_width', $instance['map_width']);
			$map_height = empty($instance['map_height']) ? '200' : apply_filters('widget_map_height', $instance['map_height']);
			$scale = empty($instance['scale']) ? '10' : apply_filters('widget_scale', $instance['scale']);
			echo $before_widget;
			if (!empty($instance['title']))
				echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
			?>						
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
            <script type="text/javascript">
              var geocoder;
              var map;
              function initialize() {
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(-34.397, 150.644);
                var myOptions = {
                zoom: <?php echo $scale; ?>,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>
                }
                map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
                codeAddress();
              }
            
              function codeAddress() {
                var address = '<?php echo $address; ?>';//document.getElementById("address").value;
                geocoder.geocode( { 'address': address}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map, 
                        position: results[0].geometry.location
                    });
                  } else {
                    alert("Geocode was not successful for the following reason: " + status);
                  }
                });
              }
             google.maps.event.addDomListener(window, 'load', initialize); 
            </script>
			<div class="wid_gmap graybox">
			<div id="map-canvas" style="height:<?php echo $map_height; ?>px;"></div></div>
       <?php 
	   	echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;		
			$instance['title'] = ($new_instance['title']);
			$instance['address'] = strip_tags($new_instance['address']);
			$instance['address_latitude'] = strip_tags($new_instance['address_latitude']);
			$instance['address_longitude'] = strip_tags($new_instance['address_longitude']);
			$instance['map_width'] = strip_tags($new_instance['map_width']);
			$instance['map_height'] = strip_tags($new_instance['map_height']);
			$instance['map_type'] = strip_tags($new_instance['map_type']);
			$instance['scale'] = strip_tags($new_instance['scale']);
			return $instance;
		}
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'title' => '') );		
			$title = ($instance['title']);
			$address = strip_tags($instance['address']);
			$address_latitude = strip_tags($instance['address_latitude']);
			$address_longitude = strip_tags($instance['address_longitude']);
			$map_width = strip_tags($instance['map_width']);
			$map_height = strip_tags($instance['map_height']);
			$map_type = strip_tags($instance['map_type']);
			$scale = strip_tags($instance['scale']);
			
	?>
	<p><label for="<?php  echo $this->get_field_id('title'); ?>"><?php _e('Title','supreme');?>: <input class="widefat" id="<?php  echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	
	<p><label for="<?php echo $this->get_field_id('address'); ?>"><?php  _e('Address <small>(eg: 230 Vine Street And locations throughout Old City, Philadelphia, PA 19106)</small>','supreme');?> : 
	<input type="text" class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>"  value="<?php echo esc_attr($address); ?>"></label></p>
	
	<p><label for="<?php echo $this->get_field_id('map_height'); ?>"><?php  _e('Map Height in pixcels <small>(eg: 200)</small>','supreme');?> : <input type="text" class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('map_height'); ?>" name="<?php echo $this->get_field_name('map_height'); ?>" value="<?php echo esc_attr($map_height); ?>"></label></p>
	
	
	<p>
	<label for="<?php echo $this->get_field_id('scale'); ?>"><?php  _e('Map Zooming Factor','supreme');?> : 
	<select id="<?php echo $this->get_field_id('scale'); ?>" name="<?php echo $this->get_field_name('scale'); ?>">
	<?php
	for($i=3;$i<20;$i++)
	{
	?>
	<option value="<?php echo $i;?>" <?php if(esc_attr($scale)==$i){echo 'selected="selected"';}?> ><?php echo $i;;?></option>
	<?php	
	}
	?>
	</select>
	</label></p>	
	<p>
	<label for="<?php echo $this->get_field_id('map_type'); ?>"><?php  _e('Select Map Type','supreme');?> : 
	<select id="<?php echo $this->get_field_id('map_type'); ?>" name="<?php echo $this->get_field_name('map_type'); ?>">
        <option value="ROADMAP" <?php if(esc_attr($map_type)=='ROADMAP'){echo 'selected="selected"';}?> ><?php  _e('Road Map','supreme');?></option>
        <option value="SATELLITE" <?php if(esc_attr($map_type)=='SATELLITE'){echo 'selected="selected"';}?>><?php  _e('Satellite Map','supreme');?></option>
	</select>
	</label>
	</p>
	<?php
	}}
}
/*	Code By Templatic End */
?>