<?php
if(get_post_meta($post->ID,'zooming_factor',true))
{
	$zooming_factor = get_post_meta($post->ID,'zooming_factor',true);
}
else
{
	$zooming_factor = 13;
}
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v=3.5&sensor=false&libraries=places"></script>
<script type="text/javascript">
/* <![CDATA[ */
var map;
var latlng;
var geocoder;
var address;
var lat;
var lng;
var centerChangedLast;
var reverseGeocodedLast;
var currentReverseGeocodeResponse;
<?php
	$maptype = 'ROADMAP';
?>
var CITY_MAP_CENTER_LAT = 40.714623;	
var CITY_MAP_CENTER_LNG = -74.006605;	
var CITY_MAP_ZOOMING_FACT = 13;
  function initialize() {
    var latlng = new google.maps.LatLng(CITY_MAP_CENTER_LAT,CITY_MAP_CENTER_LNG);
    var myOptions = {
      zoom: <?php echo $zooming_factor;?>,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.<?php echo $maptype;?>
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    geocoder = new google.maps.Geocoder();
	google.maps.event.addListener(map, 'zoom_changed', function() {
			document.getElementById("zooming_factor").value = map.getZoom();
		});
	setupEvents();
   // centerChanged();
  }

  function setupEvents() {
    reverseGeocodedLast = new Date();
    centerChangedLast = new Date();
	
    setInterval(function() {
      if((new Date()).getSeconds() - centerChangedLast.getSeconds() > 1) {
        if(reverseGeocodedLast.getTime() < centerChangedLast.getTime())
          reverseGeocode();
      }
    }, 1000);
google.maps.event.addListener(map, 'zoom_changed', function() {
			//document.getElementById("zooming_factor").value = map.getZoom();
		});
	}

  function getCenterLatLngText() {
    return '(' + map.getCenter().lat() +', '+ map.getCenter().lng() +')';
  }

  function centerChanged() {
    centerChangedLast = new Date();
    var latlng = getCenterLatLngText();
    //document.getElementById('latlng').innerHTML = latlng;
    document.getElementById('address').innerHTML = '';
    currentReverseGeocodeResponse = null;
  }

  function reverseGeocode() {
    reverseGeocodedLast = new Date();
    geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
  }

  function reverseGeocodeResult(results, status) {
    currentReverseGeocodeResponse = results;
    if(status == 'OK') {
      if(results.length == 0) {
        document.getElementById('address').innerHTML = 'None';
      } else {
        document.getElementById('address').innerHTML = results[0].formatted_address;
      }
    } else {
      document.getElementById('address').innerHTML = 'Error';
    }
  }


  function geocode() {
    var address = document.getElementById("address").value;
    if(address) {
		geocoder.geocode({
		'address': address,
		'partialmatch': true}, geocodeResult);
	 }
  }

  function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
      map.fitBounds(results[0].geometry.viewport);
	  map.setZoom(<?php echo $zooming_factor;?>);
	  addMarkerAtCenter();
	  
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
	
}

  function addMarkerAtCenter() {
	var marker = new google.maps.Marker({
        position: map.getCenter(),
		draggable: true,
        map: map
    });
	
	updateMarkerAddress('Dragging...');
	updateMarkerPosition(marker.getPosition());
	geocodePosition(marker.getPosition());

	google.maps.event.addListener(marker, 'dragstart', function() {
    	updateMarkerAddress('Dragging...');
    });
	
    google.maps.event.addListener(marker, 'drag', function() {
    	updateMarkerPosition(marker.getPosition());
    });
	
    google.maps.event.addListener(marker, 'dragend', function() {
    	geocodePosition(marker.getPosition());
   });



    var text = 'Lat/Lng: ' + getCenterLatLngText();
    if(currentReverseGeocodeResponse) {
      var addr = '';
      if(currentReverseGeocodeResponse.size == 0) {
        addr = 'None';
      } else {
        addr = currentReverseGeocodeResponse[0].formatted_address;
      }
      text = text + '<br>' + 'address: <br>' + addr;
    }

    var infowindow = new google.maps.InfoWindow({ content: text });

    google.maps.event.addListener(marker, 'click', function() {
      infowindow.open(map,marker);
    });
  }
  
  function updateMarkerAddress(str)
   {
	 //document.getElementById('address').value = str;
   }
   
  function updateMarkerStatus(str)
   {
  	 document.getElementById('markerStatus').innerHTML = str;
   }
   
  function updateMarkerPosition(latLng)
   {
	 document.getElementById('geo_latitude').value = latLng.lat();
	 document.getElementById('geo_longitude').value = latLng.lng();
  }
 
	var geocoder = new google.maps.Geocoder();

	function geocodePosition(pos) {
	  geocoder.geocode({
		latLng: pos
	  }, function(responses) {
		if (responses && responses.length > 0) {
		  updateMarkerAddress(responses[0].formatted_address);
		} else {
		  updateMarkerAddress('Cannot determine address at this location.');
		}
	  });
	}

  function changeMap()
   {
		var newlatlng = document.getElementById('geo_latitude').value;
		var newlong = document.getElementById('geo_longitude').value;
		var latlng = new google.maps.LatLng(newlatlng,newlong);
		var map = new google.maps.Map(document.getElementById('map_canvas'), {
		zoom: <?php echo $zooming_factor;?>,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.<?php echo $maptype;?>
	  });
	
		var marker = new google.maps.Marker({
		position: latlng,
		title: 'Point A',
		map: map,
		draggable: true
	  });
		
	updateMarkerAddress('Dragging...');
	updateMarkerPosition(marker.getPosition());
	geocodePosition(marker.getPosition());

    google.maps.event.addListener(marker, 'dragstart', function() {
    	updateMarkerAddress('Dragging...');
    });
	
    google.maps.event.addListener(marker, 'drag', function() {
    	//updateMarkerStatus('Dragging...');
    	updateMarkerPosition(marker.getPosition());
    });
	
    google.maps.event.addListener(marker, 'dragend', function() {
    	//updateMarkerStatus('Drag ended');
    	geocodePosition(marker.getPosition());
   });
	
   }
	
	
google.maps.event.addDomListener(window, 'load', initialize);
<?php if(isset($_REQUEST['pid']) || isset($_REQUEST['post']) || isset($_REQUEST['backandedit'])|| isset($_REQUEST['renew'])):?>
	google.maps.event.addDomListener(window, 'load', changeMap);
<?php else: ?>
	google.maps.event.addDomListener(window, 'load', geocode);
<?php endif; ?>

/* ]]> */
</script>
<?php if(is_templ_wp_admin()): ?>
<div class="form_row clearfix">
<label><?php echo $pt_metabox['label']; ?><span>*</span></label>
<input type="text" class="pt_input_text" value="<?php if(isset($_REQUEST['post']))echo get_post_meta($_REQUEST['post'],'address',true); ?>" id="address" name="address" />
<span class="message_error2" id="address_error"></span>
	<input type="button" class="btn_input_normal btn_spacer" value="<?php _e('Set Address on Map',DOMAIN);?>" onclick="geocode();initialize();" />
</div>  
<div class="form_row clearfix">
	<div id="map_canvas" class="map_wrap form_row clearfix" style="height:300px;margin-left:218px;position:relative;width:450px;"></div>
</div>
<div class="form_row clearfix">
  <label><?php _e("Address Latitude",DOMAIN); ?><span></span></label>
  <input type="text" onblur="changeMap();" class="textfield" value="<?php if(isset($_REQUEST['post']))echo get_post_meta($_REQUEST['post'],'geo_latitude',true); ?>" id="geo_latitude" name="geo_latitude" />
  <span class="message_note"><?php _e("Please enter latitude for google map perfection. eg. : 39.955823048131286",DOMAIN); ?></span><span class="" id="geo_latitude_error"></span>
</div>

<div class="form_row clearfix">
  <label><?php _e("Address Longitude",DOMAIN); ?><span></span></label>
  <input type="text" placeholder="" onblur="changeMap();" class="textfield" value="<?php if(isset($_REQUEST['post']))echo get_post_meta($_REQUEST['post'],'geo_longitude',true); ?>" id="geo_longitude" name="geo_longitude" />
  <span class="message_note"><?php _e("Please enter logngitude for google map perfection. eg. : -75.14408111572266",DOMAIN); ?></span><span class="" id="geo_longitude_error"></span>
  
</div>

<?php else: ?>
<label><?php echo $site_title; ?><span>*</span></label>
<?php
$addval = '';
$zoomval = '';
$latval = '';
$longval = '';
if(isset($_REQUEST['pid']))
{
	$addval = get_post_meta($_REQUEST['pid'],'address',true);
	$zoomval = get_post_meta($_REQUEST['pid'],'zooming_factor',true);
	$latval = get_post_meta($_REQUEST['pid'],'geo_latitude',true);
	$longval = get_post_meta($_REQUEST['pid'],'geo_longitude',true);
}
if(isset($_SESSION['custom_fields']) && isset($_REQUEST['backandedit']))
{
	$addval = $_SESSION['custom_fields']['address'];
	$zoomval = $_SESSION['custom_fields']['zooming_factor'];
	$latval = $_SESSION['custom_fields']['geo_latitude'];
	$longval = $_SESSION['custom_fields']['geo_longitude'];
}
?>
<input type="text" class="textfield" value="<?php echo $addval; ?>" id="address" name="address" />
<span class="message_error2" id="address_error"></span>
<input type="hidden" class="textfield" value="<?php echo $zoomval; ?>" id="zooming_factor" name="zooming_factor" />
<div class="form_row clearfix">
	<input type="button" class="btn_input_normal btn_spacer" value="<?php _e('Set Address on Map',DOMAIN);?>" onclick="geocode();initialize();" />
</div>    
<div class="form_row clearfix">
	<div id="map_canvas" class="form_row clearfix"></div>
</div>
<div class="form_row clearfix">
  <label><?php _e("Address Latitude",DOMAIN); ?><span></span></label>
  <input type="text" onblur="changeMap();" class="textfield" value="<?php echo $latval; ?>" id="geo_latitude" name="geo_latitude" />
  <span class="message_note"><?php _e("Please enter latitude for google map perfection. eg. : 39.955823048131286",DOMAIN); ?></span><span class="" id="geo_latitude_error"></span>
</div>

<div class="form_row clearfix">
  <label><?php _e("Address Longitude",DOMAIN); ?><span></span></label>
  <input type="text" placeholder="" onblur="changeMap();" class="textfield" value="<?php echo $longval; ?>" id="geo_longitude" name="geo_longitude" />
  <span class="message_note"><?php _e("Please enter logngitude for google map perfection. eg. : -75.14408111572266",DOMAIN); ?></span><span class="" id="geo_longitude_error"></span>
</div>

<?php endif; ?>