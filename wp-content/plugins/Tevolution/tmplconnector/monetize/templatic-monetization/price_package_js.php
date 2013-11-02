<?php ob_start(); ?>
<script type="text/javascript">
/*
Name :show_featuredprice
Description : Return the total prices and add the calculation in span.
*/
function show_featuredprice(pkid)
{
	if (pkid=="")
	  {
	  document.getElementById("featured_h").innerHTML="";
	  return;
	  }else{
	  //document.getElementById("featured_h").innerHTML="";
	  document.getElementById("process").style.display ="block";
	  }
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("process").style.display ="none";
		
		var myString =xmlhttp.responseText;
		var myStringArray = myString.split("###RAWR###");  /* split with ###RAWR### because return result is concated with ###RAWR###*/
		document.getElementById('alive_days').value = myStringArray[6];
		if(myStringArray[5] == 1){
		if(document.getElementById('is_featured').style.display == "none")
		{
			document.getElementById('is_featured').style.display="";
		}
			
			document.getElementById('featured_c').value = myStringArray[1];
			document.getElementById('featured_h').value = myStringArray[0];

			var positionof = '<?php echo get_option('currency_pos'); ?>';
			if(positionof == 1){ 
			document.getElementById('ftrhome').innerHTML = "(<?php echo tmpl_fetch_currency();?>"+myStringArray[0]+")";

			document.getElementById('ftrcat').innerHTML = "(<?php echo tmpl_fetch_currency();?>"+myStringArray[1]+")";
			}else if(positionof == 2){
			document.getElementById('ftrhome').innerHTML = "(<?php echo tmpl_fetch_currency();?> "+myStringArray[0]+")";
			document.getElementById('ftrcat').innerHTML = "(<?php echo tmpl_fetch_currency(get_option('currency_symbol'),'currency_symbol');?> "+myStringArray[1]+")";
			}else if(positionof == 3){
			document.getElementById('ftrhome').innerHTML = "("+myStringArray[0]+"<?php echo tmpl_fetch_currency();?>)";
			document.getElementById('ftrcat').innerHTML = "("+myStringArray[1]+"<?php echo tmpl_fetch_currency();?>)";
			}else{
			document.getElementById('ftrhome').innerHTML = "("+myStringArray[0]+" <?php echo tmpl_fetch_currency();?>)";
			document.getElementById('ftrcat').innerHTML = "("+myStringArray[1]+" <?php echo tmpl_fetch_currency();?>)";
			}
			
			document.getElementById('pkg_price').innerHTML = myStringArray[4];   
		}else{
			document.getElementById('pkg_price').innerHTML = myStringArray[4];  
			document.getElementById('featured_c').value=0;
			document.getElementById('ftrcat').innerHTML	= "<?php echo fetch_currency_with_position(0);?>";		
			document.getElementById('featured_h').value=0;
			document.getElementById('ftrhome').innerHTML = "<?php echo fetch_currency_with_position(0);?>";		
			document.getElementById('is_featured').style.display = "none"; 
		 	document.getElementById('total_price').value = parseFloat(myStringArray[0]) + parseFloat(myStringArray[1]) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			document.getElementById('result_price').innerHTML = parseFloat(myStringArray[0]) + parseFloat(myStringArray[1]) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
		
		}
		
		if((document.getElementById('featured_h').checked== true) && (document.getElementById('featured_c').checked== true))
		{			
			if(myStringArray[0]==""){myStringArray[0]=0}else{myStringArray[0]=myStringArray[0];}
			if(myStringArray[1]==""){myStringArray[1]=0}else{myStringArray[1]=myStringArray[1];}
			document.getElementById('feture_price').innerHTML = parseFloat(myStringArray[0]) + parseFloat(myStringArray[1]) ;
			
			document.getElementById('total_price').value = parseFloat(myStringArray[0]) + parseFloat(myStringArray[1]) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			
			document.getElementById('result_price').innerHTML = parseFloat(myStringArray[0]) + parseFloat(myStringArray[1]) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			
		}else if((document.getElementById('featured_h').checked == true) && (document.getElementById('featured_c').checked == false)){
			if(myStringArray[0]==""){myStringArray[0]=0}else{myStringArray[0]=myStringArray[0];}			
			document.getElementById('feture_price').innerHTML = parseFloat(myStringArray[0]);
			
			document.getElementById('total_price').value =parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			
			document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
		}else if((document.getElementById('featured_h').checked == false) && (document.getElementById('featured_c').checked == true)){
			if(myStringArray[1]==""){myStringArray[1]=0}else{myStringArray[1]=myStringArray[1];}
			document.getElementById('feture_price').innerHTML = parseFloat(myStringArray[1]);
			document.getElementById('total_price').value = parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			
			document.getElementById('result_price').innerHTML =  parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
		}else{
			document.getElementById('total_price').value = parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
			
			document.getElementById('result_price').innerHTML =parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML) + parseFloat(myStringArray[4]);
		}
	  } 
	  }	
	  url="<?php echo TEMPL_PLUGIN_URL;?>/tmplconnector/monetize/templatic-monetization/ajax_price.php?pkid="+pkid
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
	 
}
/*
Name :fetch_packages
Description : Retun package details and category pricing( term_price for category ) USE THIE IF IN FUTURE WE are going to give categorywise pricing
*/
function fetch_packages(pkgid,form,pri)
{ 
	var total = 0;
	var t=0;
	//var c= form['category[]'];
	<?php $tmpdata = get_option('templatic_settings'); ?>
    var cat_display = '<?php echo $tmpdata['templatic-category_type']; ?>';
	var cat_wise_display = '<?php echo $tmpdata['templatic-category_custom_fields']; ?>';
	var dml = document.forms['submit_form'];
	var c = document.getElementsByName('category[]');
	if(cat_wise_display == 'No')
	 {
		var cats = document.getElementById('all_cat').value;
		document.getElementById('all_cat').value = "";
		document.getElementById('all_cat_price').value = 0;
		document.getElementById('feture_price').innerHTML = 0;
		document.getElementById('cat_price').innerHTML = 0;
	 }
	
	if(cat_display =='checkbox' || cat_display==''){
		for(var i=0;i<c.length;i++){
			c[i].checked?t++:null;
			if(c[i].checked)
			{	
				var a = c[i].value.split(",");
				
				document.getElementById('all_cat').value += a[0]+"|";
				
				
				document.getElementById('all_cat_price').value = parseFloat(document.getElementById('all_cat_price').value) + parseFloat(a[1]);
				
				document.getElementById('cat_price').innerHTML = parseFloat(document.getElementById('all_cat_price').value);

			}
			
				document.getElementById('total_price').value =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);

				
				document.getElementById('result_price').innerHTML =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);
		}
	}else{
		if(cat_display == 'select' && cat_wise_display == 'No'){
			var s = document.getElementById('select_category'); /* var is use for select box */
			if(s.options[s.selectedIndex].value){
					var a = s.options[s.selectedIndex].value.split(",");
					document.getElementById('all_cat').value += a[0]+"|";
					document.getElementById('all_cat_price').value = parseFloat(document.getElementById('all_cat_price').value) + parseFloat(a[1]);
					document.getElementById('cat_price').innerHTML = parseFloat(document.getElementById('all_cat_price').value);
			}
			document.getElementById('total_price').value =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) + parseFloat(document.getElementById('pkg_price').innerHTML);
			document.getElementById('result_price').innerHTML =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);
		}else if(cat_display == 'multiselectbox'){
			var s = document.getElementById('select_category'); /* var is use for select box */
			
			for(var i=0;i < s.options.length;i++){
				s.options[i].selected?t++:null;
				
				if(s.options[ i ].selected){
						var a = s.options[ i ].value.split(",");
						document.getElementById('all_cat').value += a[0]+"|";
						document.getElementById('all_cat_price').value = parseFloat(document.getElementById('all_cat_price').value) + parseFloat(a[1]);
						document.getElementById('cat_price').innerHTML = parseFloat(document.getElementById('all_cat_price').value);
				}
				document.getElementById('total_price').value =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) + parseFloat(document.getElementById('pkg_price').innerHTML);
				document.getElementById('result_price').innerHTML =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);
			}
		}
	}
	var cats = document.getElementById('all_cat').value;
	var post_type = document.getElementById('cur_post_type').value ;
	var taxonomy = document.getElementById('cur_post_taxonomy').value ;
	/* Below code is for category wise packages */
	if(cat_wise_display == 'No')
	 {
		document.getElementById("packages_checkbox").innerHTML="";
	    document.getElementById("process2").style.display ="";
	 
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
			else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
			xmlhttp.onreadystatechange=function()
		  {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			document.getElementById("packages_checkbox").innerHTML =xmlhttp.responseText;
			document.getElementById("process2").style.display ="none";
			}
		  }
		  url="<?php echo TEMPL_PLUGIN_URL;?>/tmplconnector/monetize/templatic-monetization/ajax_price.php?pckid="+cats+"&post_type="+post_type+"&taxonomy="+taxonomy
		  xmlhttp.open("GET",url,true);
		  xmlhttp.send();
	 }
}
/*
Name :templ_all_categories
Description : function return the result when all categories selected
*/
function templ_all_categories(cp_price) {
	var total = 0;
	var t=0;
	//var c= form['category[]'];
	var dml = document.forms['submit_form'];
	var c = dml.elements['category[]'];
	var selectall = dml.elements['selectall'];
	if(selectall.checked == false){
		cp_price = 0;
	} else {
		cp_price = cp_price;
	}
	var post_type = document.getElementById('cur_post_type').value ;
	var taxonomy = document.getElementById('cur_post_taxonomy').value ;
	var cats = document.getElementById('all_cat').value;
	document.getElementById('all_cat').value = "";
	document.getElementById('all_cat_price').value = 0;
	document.getElementById('feture_price').innerHTML = 0;
	document.getElementById('cat_price').innerHTML = 0;
	
		for(var i=0 ;i < c.length;i++){
		c[i].checked?t++:null;
		if(c[i].checked){	
			var a = c[i].value.split(",");
			if(i ==  (c.length - 1) ){
				document.getElementById('all_cat').value += a[0];
			} else {
				document.getElementById('all_cat').value += a[0]+"|";
			}
		}
	}

	document.getElementById('all_cat_price').value = parseFloat(cp_price);
	document.getElementById('cat_price').innerHTML = parseFloat(document.getElementById('all_cat_price').value);
	document.getElementById('total_price').value =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);
	document.getElementById('result_price').innerHTML =  parseFloat(document.getElementById('all_cat_price').value) + parseFloat(document.getElementById('feture_price').innerHTML) +  parseFloat(document.getElementById('pkg_price').innerHTML);
	
	var cats = document.getElementById('all_cat').value ;
	
	  document.getElementById("packages_checkbox").innerHTML="";
	  document.getElementById("process2").style.display ="";
	
	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("packages_checkbox").innerHTML =xmlhttp.responseText;;
		document.getElementById("process2").style.display ="none";
		}
	  }

	   url="<?php echo TEMPL_PLUGIN_URL;?>/tmplconnector/monetize/templatic-monetization/ajax_price.php?pckid="+cats+"&post_type="+post_type+"&taxonomy="+taxonomy
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();	

	}
	
	function myfields(fid)
{
	document.getElementById(fid+'_hidden').value = document.getElementById(fid).value;
}
/*
Name :featured_list
Description : function return the result after user select feture listing type(check box)
*/
function featured_list(fid)
{
	if((document.getElementById('featured_h').checked== true) && (document.getElementById('featured_c').checked== true))
	{
		document.getElementById('featured_type').value = 'both';
		document.getElementById('feture_price').innerHTML = parseFloat(document.getElementById('featured_c').value) + parseFloat(document.getElementById('featured_h').value);
		document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('featured_c').value) + parseFloat(document.getElementById('featured_h').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
		document.getElementById('total_price').value = parseFloat(document.getElementById('featured_c').value) + parseFloat(document.getElementById('featured_h').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
	
	}else if((document.getElementById('featured_h').checked == true) && (document.getElementById('featured_c').checked == false)){
		document.getElementById('featured_type').value = 'h';
		document.getElementById('feture_price').innerHTML = document.getElementById('featured_h').value;
		
		document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('featured_h').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
		document.getElementById('total_price').value = parseFloat(document.getElementById('featured_h').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
	}else if((document.getElementById('featured_h').checked == false) && (document.getElementById('featured_c').checked == true)){
		document.getElementById('featured_type').value = 'c';
		document.getElementById('feture_price').innerHTML = document.getElementById('featured_c').value;
		
		document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('featured_c').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
		document.getElementById('total_price').value = parseFloat(document.getElementById('featured_c').value) +  parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
	}else if((document.getElementById('featured_h').checked == false) && (document.getElementById('featured_c').checked == false)){
		document.getElementById('featured_type').value = 'n';
		document.getElementById('feture_price').innerHTML = '0';
		document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
		document.getElementById('total_price').value = parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
	
	}else{
		document.getElementById('featured_type').value = 'n';
		document.getElementById('feture_price').innerHTML = '0';
		document.getElementById('result_price').innerHTML = parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
		
		document.getElementById('total_price').value = parseFloat(document.getElementById('pkg_price').innerHTML) +  parseFloat(document.getElementById('cat_price').innerHTML);
	}
}

</script>