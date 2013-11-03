
jQuery(document).ready(function()
{
    var userform = jQuery("#reg_form"); 

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
            user_email_error.removeClass("message_error2");
            return true;
        }
    }
    user_email.blur(validate_user_email);
    user_email.keyup(validate_user_email); 
    var user_fname = jQuery("#user_login"); 
	
    var user_fname_error = jQuery("#user_error"); 
	
    function validate_user_fname()
    {
        if(jQuery("#user_login").val() == "")
			
        {
            user_fname.addClass("error");
            user_fname_error.text("Please Enter User name");
            user_fname_error.addClass("error");
            return false;
        }
        else{
            user_fname.removeClass("error");
            user_fname_error.text("");
            user_fname_error.removeClass("error");
            return true;
        }
    }
    user_fname.blur(validate_user_fname);
    user_fname.keyup(validate_user_fname);
    var pwd = jQuery("#rpassword"); 
	
    var pwd_error = jQuery("#pw_error"); 
	
    function validate_pwd()
    {
        if(jQuery("#rpassword").val() == "")
			
        {
            pwd.addClass("error");
            pwd_error.text("Please enter password");
            pwd_error.addClass("error");
            return false;
        }
        else{
            pwd.removeClass("error");
            pwd_error.text("");
            pwd_error.removeClass("#error");
            return true;
        }
    }
    pwd.blur(validate_pwd);
    pwd.keyup(validate_pwd); 
    var cpwd = jQuery("#password2"); 
	
    var cpwd_error = jQuery("#pw_error2"); 
	
    function validate_cpwd()
    {
        if(jQuery("#password2").val() == "")
			
        {
            cpwd.addClass("error");
            cpwd_error.text("Please enter confirm password");
            cpwd_error.addClass("error");
            return false;
        } else if(jQuery("#rpassword").val() != jQuery("#password2").val()) {
            cpwd.addClass("error");
            cpwd_error.text("Please confirm your password");
            cpwd_error.addClass("error");
            return false;
        }
        else{
            cpwd.removeClass("error");
            cpwd_error.text("");
            cpwd_error.removeClass("error");
            return true;
        }
    }
    cpwd.blur(validate_cpwd);
    cpwd.keyup(validate_cpwd);
			
    userform.submit(function()
    {
        if(validate_user_email() & validate_user_fname() & validate_pwd() & validate_cpwd())
        {
            return true;
        }
        else
        {
            return false;
        }
    });

});
