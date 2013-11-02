<?php
/* show map on detail page */
 if($geo_latitude && $geo_longitude){?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.5&sensor=false"></script>

<div id="map-container"></div>
<div id="dir-container"></div>
<div class=" get_direction clearfix">
<input id="to-input" type="hidden" value="<?php echo get_post_meta($post->ID,'address',true);?>"/>
<select onchange="Demo.getDirections();" id="travel-mode-input" style="display:none;">
  <option value="driving" selected="selected"><?php _e("By car","templatic");?></option>
  <option value="bicycling"><?php _e("Bicycling","templatic");?></option>
  <option value="walking"><?php _e("Walking","templatic");?></option>
</select>
<select onchange="Demo.getDirections();" id="unit-input" style="display:none;">
  <option value="metric"  selected="selected"><?php _e("Metric","templatic");?></option>
  <option value="imperial"><?php _e("Imperial","templatic");?></option>
</select>
<input id="from-input" type="text" onblur="if (this.value == '') {this.value = '<?php _e('Enter Location',T_DOMAIN);?>';}" onfocus="if (this.value == '<?php _e('Enter Location',T_DOMAIN);?>') {this.value = '';}" value="<?php _e('Enter Location',T_DOMAIN);?>" /> 
<a href="javascript:void(0);" onclick="return set_direction_map()" class="b_getdirection getdir" > <?php _e('Get Direction',T_DOMAIN);?> </a>
<a class="large_map b_getdirection" target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo $address;?>&sll=<?php echo $geo_latitude;?>,<?php echo $geo_longitude;?>&ie=UTF8&hq=&ll=<?php echo $geo_latitude;?>,<?php echo $geo_longitude;?>&spn=0.368483,0.891953&z=14&iwloc=A"><?php _e('View Large Map',T_DOMAIN);?></a>
<?php
$address = get_post_meta($post->ID,'address',true);
$address = str_replace('++','+',str_replace(' ','+',str_replace(',','+',$address)));
?>

</div>
<script type="text/javascript">
function set_direction_map()
{
	if(document.getElementById('from-input').value=='<?php _e('Enter Location',T_DOMAIN);?>' || document.getElementById('from-input').value=='')
	{
		alert('<?php _e('Please enter your address to get the direction map.',T_DOMAIN);?>');return false;
	}else
	{
		Demo.getDirections();	
	}
}
var Demo = {
  // HTML Nodes
  mapContainer: document.getElementById('map-container'),
  dirContainer: document.getElementById('dir-container'),
  fromInput: document.getElementById('from-input'),
  toInput: document.getElementById('to-input'),
  travelModeInput: document.getElementById('travel-mode-input'),
  unitInput: document.getElementById('unit-input'),

  // API Objects
  dirService: new google.maps.DirectionsService(),
  dirRenderer: new google.maps.DirectionsRenderer(),
  map: null,

  showDirections: function(dirResult, dirStatus) {
    if (dirStatus != google.maps.DirectionsStatus.OK) {
      alert('Directions failed: ' + dirStatus);
      return;
    }

    // Show directions
    Demo.dirRenderer.setMap(Demo.map);
    Demo.dirRenderer.setPanel(Demo.dirContainer);
    Demo.dirRenderer.setDirections(dirResult);
  },

  getSelectedTravelMode: function() {
    var value =
        Demo.travelModeInput.options[Demo.travelModeInput.selectedIndex].value;
    if (value == 'driving') {
      value = google.maps.DirectionsTravelMode.DRIVING;
    } else if (value == 'bicycling') {
      value = google.maps.DirectionsTravelMode.BICYCLING;
    } else if (value == 'walking') {
      value = google.maps.DirectionsTravelMode.WALKING;
    } else {
      alert('Unsupported travel mode.');
    }
    return value;
  },

  getSelectedUnitSystem: function() {
    return Demo.unitInput.options[Demo.unitInput.selectedIndex].value == 'metric' ?
        google.maps.DirectionsUnitSystem.METRIC :
        google.maps.DirectionsUnitSystem.IMPERIAL;
  },

  getDirections: function() {
    var fromStr = Demo.fromInput.value;
    var toStr = Demo.toInput.value;
    var dirRequest = {
      origin: fromStr,
      destination: toStr,
      travelMode: Demo.getSelectedTravelMode(),
      unitSystem: Demo.getSelectedUnitSystem(),
      provideRouteAlternatives: true
    };
    Demo.dirService.route(dirRequest, Demo.showDirections);
  },

  init: function() {
    var latLng = new google.maps.LatLng(<?php echo $geo_latitude;?>, <?php echo $geo_longitude;?>);
    Demo.map = new google.maps.Map(Demo.mapContainer, {
   <?php if(get_option('ptthemes_scale_factor')){ $ptthemes_scale_factor = get_option('ptthemes_scale_factor');}
   else{$ptthemes_scale_factor = 13;}?>
      zoom: <?php echo $ptthemes_scale_factor;?>,
      center: latLng,
     <?php if($map_type=='Road Map' || $map_type=='Satellite Map'|| $map_type=='Terrain Map'){
		if($map_type=='Satellite Map') { $map_type = SATELLITE; } elseif($map_type=='Terrain Map') { $map_type = TERRAIN; } else { $map_type = ROADMAP; } ?>
	 mapTypeId: google.maps.MapTypeId.<?php echo $map_type;?>
	 <?php }else{?>
	 mapTypeId: google.maps.MapTypeId.ROADMAP
	 <?php }?>
    });
 	var marker = new google.maps.Marker({
        position: latLng, 
        map: Demo.map,
        title:"<?php echo trim($post->post_title);?>"
    });   
	
    // Show directions onload
  //  Demo.getDirections();
  }
};

// Onload handler to fire off the app.
google.maps.event.addDomListener(window, 'load', Demo.init);
</script>
<?php }else{ 
$address = get_post_meta($post->ID,'address',true);
$address = str_replace('++','+',str_replace(' ','+',str_replace(',','+',$address)));
$address = "Surat,Gujarat,India";
?>
<iframe width="580" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="
http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo $address;?>&ie=UTF8&z=10"></iframe>



<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $address;?>&amp;ie=UTF8&amp;hq=&amp;hnear=Surat,+Gujarat,+India&amp;ll=21.194655,72.557831&amp;spn=0.906514,1.783905&amp;z=10&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=<?php echo $address;?>&amp;ie=UTF8&amp;hq=&amp;hnear=Surat,+Gujarat,+India&amp;ll=21.194655,72.557831&amp;spn=0.906514,1.783905&amp;z=10" style="color:#0000FF;text-align:left"><?php _e("View Larger Map","templatic");?></a></small>
<?php }?>