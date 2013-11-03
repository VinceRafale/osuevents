jQuery(document).ready(function(){
    //<![CDATA[
    var placeform = jQuery("#placeform"); 

    dml = document.forms['placeform'];
    //Validate list title
    var property_title = jQuery("#list_title"); 
    var property_title_error = jQuery("#list_title_rr"); 
	
    function validate_place_title()
    {
        if(jQuery("#list_title").val() == "")

        {
            property_title.addClass("error");
            property_title_error.text("Please Enter Listing Title");
            property_title_error.addClass("error");
            return false;
        }
        else{
            property_title.removeClass("error");
            property_title_error.text("");
            property_title_error.removeClass("error");
            return true;
        }
    }
    property_title.blur(validate_place_title);
    property_title.keyup(validate_place_title); 
    dml = document.forms['placeform'];
    //Validate Geo address
    var geo_address = jQuery("#geo_address"); 
    var geo_address_error = jQuery("#geo_address_rr"); 
	
    function validate_geo_address()
    {
        if(jQuery("#geo_address").val() == "")

        {
            geo_address.addClass("error");
            geo_address_error.text("Please enter address to locate your location on map.");
            geo_address_error.addClass("error");
            return false;
        }
        else{
            geo_address.removeClass("error");
            geo_address_error.text("");
            geo_address_error.removeClass("error");
            return true;
        }
    }
    geo_address.blur(validate_geo_address);
    geo_address.keyup(validate_geo_address);      
        
    dml = document.forms['placeform'];        
   
    //Validate email
    dml = document.forms['placeform'];
    var email = jQuery("#email"); 
    var email_error = jQuery("#email_error"); 
	
    function validate_email()
    {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if(jQuery("#email").val() == "") {
            email.addClass("error");
            email_error.text("Please provide your email address");
            email_error.addClass("error");
            return false;
        } else if(!emailReg.test(jQuery("#email").val())) {
            email.addClass("error");
            email_error.text("Please provide valid email address");
            email_error.addClass("error");
            return false;
        } else {
            email.removeClass("error");
            email_error.text("");
            email_error.removeClass("error");
            return true;
        }
        if(jQuery("#email").val() == "")

        {
            email.addClass("error");
            email_error.text("Please provide valid email address");
            email_error.addClass("error");
            return false;
        }
        else{
            email.removeClass("error");
            email_error.text("");
            email_error.removeClass("error");
            return true;
        }
    }
    email.blur(validate_email);
    email.keyup(validate_email); 	
    placeform.submit(function()
    {
        if(validate_geo_address() & validate_place_title())
        {
            return true
        }
        else
        {
            return false;
        }
    });

});//]]>

