<script type="text/javascript">
jQuery.noConflict();
var xmlHTTP;
var checkclick = false;
function GetXmlHttpObject()
{
	xmlHTTP=null;
	try
	{
		xmlhttp=new XMLHttpRequest();
	}
	catch (e)
	{
		try
		{
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");			
		}
		catch (e)
		{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
}
function chkemail()
{
	if (window.XMLHttpRequest)
	{
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
	 	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  	if(xmlhttp == null)
	{
		alert("Your browser not support the AJAX");	
		return;
	}
	if(document.getElementById("user_email"))
		user_email = document.getElementById("user_email").value;
	var url = "<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-registration/ajax_check_user_email.php?user_email="+user_email;

	xmlhttp.open("GET",url,true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(null);
	xmlhttp.onreadystatechange=function()
	{
	   	if(xmlhttp.readyState==4 && xmlhttp.status==200)
	   	{
			var email = xmlhttp.responseText.split(","); 
			if(email[1] == 'email')
			{
				if(email[0] > 0)
				{
					document.getElementById("user_email_error").innerHTML = 'Email Id already exist.Please enter another email';
					document.getElementById("user_email_already_exist").value = 0;
					jQuery("#user_email_error").removeClass('available_tick');
					jQuery("#user_email_error").addClass('message_error2');
				}
				else
				{
					document.getElementById("user_email_error").innerHTML = 'Your email address is verified.';
					document.getElementById("user_email_already_exist").value = 1;
					jQuery("#user_email_error").removeClass('message_error2');
					jQuery("#user_email_error").addClass('available_tick');
				}
			}
		}
	}
	return true;
}
function chkname()
{
	
	if (window.XMLHttpRequest)
	{
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
	 	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  	if(xmlhttp == null)
	{
		alert("Your browser not support the AJAX");	
		return;
	}
	if(document.getElementById("user_fname"))
		user_fname = document.getElementById("user_fname").value;
	var url = "<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-registration/ajax_check_user_email.php?user_fname="+user_fname;
	jQuery("#registernow_form").click(function(){
			checkclick = true;
   });

	xmlhttp.open("GET",url,true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(null);
	xmlhttp.onreadystatechange=function()
	{
	   	if(xmlhttp.readyState==4 && xmlhttp.status==200)
	   	{
			var fname = xmlhttp.responseText.split(","); 
			if(fname[1] == 'fname')
			{
				if(fname[0] > 0)
				{
					document.getElementById("user_fname_error").innerHTML = 'User name already exist.Please enter another user name';
					document.getElementById("user_fname_already_exist").value = 0;
					jQuery("#user_fname_error").addClass('message_error2');
					jQuery("#user_fname_error").removeClass('available_tick');
				}
				else
				{
					document.getElementById("user_fname_error").innerHTML = 'Your user name is verified.';
					document.getElementById("user_fname_already_exist").value = 1;
					jQuery("#user_fname_error").removeClass('message_error2');
					jQuery("#user_fname_error").addClass('available_tick');
					if(jQuery("#userform div").size() == 2 && checkclick)
					 {
						 document.userform.submit();
					 }
				}
			}
		}
	}
	return true;
}
</script>