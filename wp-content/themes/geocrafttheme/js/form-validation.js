/**
 * Following code is used for contactform input validation
 * 
 */
jQuery(document).ready(function()
{
    var userform = jQuery("#contactForm"); 

    var userName = jQuery("#contactName"); 
	
    var user_name_error = jQuery("#username_error"); 
	
    function validate_user_name()
    {
        if(jQuery("#contactName").val() == "")
			
        {
            userName.addClass("error");
            user_name_error.text("Please Enter name");
            user_name_error.addClass("error");
            return false;
        }
        else{
            userName.removeClass("error");
            user_name_error.text("");
            user_name_error.removeClass("error");
            return true;
        }
    }
    userName.blur(validate_user_name);
    userName.keyup(validate_user_name);
    
    var user_email = jQuery("#email"); 
	
    var user_email_error = jQuery("#email_error"); 
	
    function validate_user_email()
    {
        if(jQuery("#email").val() == "")
			
        {
            user_email.addClass("error");
            user_email_error.text("Please Enter E-mail");
            user_email_error.addClass("error");
            return false;
        }
        else
			
        if(jQuery("#email").val() != "")
        {
            var a = jQuery("#email").val();
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if(jQuery("#email").val() == "") {
                user_email.addClass("error");
                user_email_error.text("Please provide your email address");
                user_email_error.addClass("error");
                return false;
            } else if(!emailReg.test(jQuery("#email").val())) {
                user_email.addClass("error");
                user_email_error.text("Please provide valid email address");
                user_email_error.addClass("error");
                return false;
            } else {
                user_email.removeClass("error");
                user_email_error.text("");
                user_email_error.removeClass("error");
                return true;
            }
				
				
        }else
        {
            user_email.removeClass("error");
            user_email_error.text("");
            user_email_error.removeClass("error");
            return true;
        }
    }
    user_email.blur(validate_user_email);
    user_email.keyup(validate_user_email); 

    var comment = jQuery("#commentsText"); 
	
    var comment_error = jQuery("#comment_error"); 
	
    function validate_comment()
    {
        if(jQuery("#commentsText").val() == "")
			
        {
            comment.addClass("error");
            comment_error.text("Please enter your message");
            comment_error.addClass("error");
            return false;
        }
        else{
            comment.removeClass("error");
            comment_error.text("");
            comment_error.removeClass("#error");
            return true;
        }
    }
    comment.blur(validate_comment);
    comment.keyup(validate_comment); 

		
    userform.submit(function()
    {
        if(validate_user_email() & validate_user_name() & validate_comment())
        {
            return true;
        }
        else
        {
            return false;
        }
    });

});

