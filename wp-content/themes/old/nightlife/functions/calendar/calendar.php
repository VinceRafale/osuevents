<?php
/* File for calendar widget function will call tha calendar ajax file */
if(!function_exists('get_my_event_calendar'))
{


function get_my_event_calendar()
{
	
	?> 
    <div class="widget calendar widget-calendar" id="eventcal" align="center">
	
    </div>
  
<?php }}?>
<script type="text/javascript">
function change_calendar(mnth,yr)
{  	

	  document.getElementById("eventcal").innerHTML="<img src='<?php echo get_stylesheet_directory_uri()."/functions/calendar/process.gif"; ?>' alt='Processing....'/>";
	
	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	   
	   xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("eventcal").innerHTML=xmlhttp.responseText;
		}
	  } 
	  url = "<?php echo get_stylesheet_directory_uri(); ?>/functions/calendar/ajax_calendar.php?mnth="+mnth+"&yr="+yr

	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
} 
</script>
<script language='javascript'>
function show_calendar()
{  	

	  document.getElementById("eventcal").innerHTML="<img src='<?php echo get_stylesheet_directory_uri()."/functions/calendar/process.gif"; ?>' alt='Processing....'/>";
	  url = "<?php echo get_stylesheet_directory_uri(); ?>/functions/calendar/ajax_calendar.php"
	  var data = 'page=' + document.location.hash.replace(/^.*#/, '');
    	jQuery.ajax({
        url: url, 
        type: "GET",       
        data: data,    
        cache: false,
        success: function (html) { 
            jQuery('#eventcal').html(html);
        }      
    });
} 
</script>
<script>
//call after page loaded
window.onload = show_calendar ; 
</script>