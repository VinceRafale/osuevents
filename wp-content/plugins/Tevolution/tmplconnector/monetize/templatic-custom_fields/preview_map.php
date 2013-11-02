<?php
if(!function_exists('preview_address_google_map'))
{
    function preview_address_google_map_plugin($latitute,$longitute,$address,$map_type='Road Map')
    {		
	 
		if($map_type=='Satellite Map') { $map_type = SATELLITE; } elseif($map_type=='Terrain Map') { $map_type = TERRAIN; } else { $map_type = ROADMAP; }
	 
	$term_icon = get_bloginfo('template_directory').'/library/map/icons/pin.png';
	
	if(is_ssl()){
		$url = "https://maps.googleapis.com/maps/api/js?v=3.5&sensor=false";
	}else{
		$url = "http://maps.googleapis.com/maps/api/js?v=3.5&sensor=false";
	}
    ?>
    <script src="<?php echo $url; ?>" type="text/javascript"></script>
    <script type="text/javascript">
	/* <![CDATA[ */
	function initialize() {	
    var map = null;
    var geocoder = null;
	
    var lat = <?php echo $latitute;?>;
    var lng = <?php echo $longitute;?>;
	var latLng = new google.maps.LatLng(<?php echo $latitute;?>, <?php echo $longitute;?>);
	var myOptions = {
      zoom: 13,
	  mapTypeId: google.maps.MapTypeId.<?php echo $map_type;?>,
      center: latLng 
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
         
   
	var myLatLng = new google.maps.LatLng(<?php echo $latitute;?>, <?php echo $longitute;?>);
	var Marker = new google.maps.Marker({
	  position: latLng,
	  map: map
	});
	var content = '<?php echo $address;?>';
	infowindow = new google.maps.InfoWindow({
	  content: content
	});
	
	google.maps.event.addListener(Marker, 'click', function() {
      infowindow.open(map,Marker);
    });

 }
	google.maps.event.addDomListener(window, 'load', initialize);
	/* ]]> */
    </script>
    <div class="map" id="map_canvas" style="width:100%; height:358px;" ></div>
    <?php
    }
}
?>