jQuery(function() {
	var cc = jQuery.cookie('display_view');	
	if (cc == 'eventgrid') {
		jQuery('#loop_listing').addClass('eventgrid');
		jQuery('#loop_listing').removeClass('eventlist');
		jQuery('#loop_taxonomy').addClass('eventgrid');
		jQuery('#loop_taxonomy').addClass('indexgrid');
		jQuery('#loop_taxonomy').removeClass('indexlist');	
		
		jQuery('#loop_archive').addClass('eventgrid');
		jQuery('#loop_archive').addClass('indexgrid');
		jQuery('#loop_archive').removeClass('indexlist');	
		
		jQuery("#gridview").addClass("active");	
		jQuery("#listview").removeClass("active");	
	} else {
		jQuery('#loop_listing').removeClass('eventgrid');	
		jQuery('#loop_taxonomy').removeClass('eventgrid');
		jQuery('#loop_taxonomy').removeClass('indexgrid');
		
		jQuery('#loop_archive').removeClass('eventgrid');
		jQuery('#loop_archive').removeClass('indexgrid');
		
		jQuery("#listview").addClass("active");	
		jQuery("#gridview").removeClass("active");	
	}
});
jQuery(document).ready(function() {
	jQuery("blockquote").before('<span class="before_quote"></span>').after('<span class="after_quote"></span>');

	jQuery('.viewsbox a.listview').click(function(e){	
		e.preventDefault();	
		jQuery('#loop_listing').addClass('eventlist');
		jQuery('#loop_listing').removeClass('eventgrid');
				
		jQuery('#loop_taxonomy').removeClass('eventgrid');				
		jQuery('#loop_taxonomy').addClass('indexlist');
		jQuery('#loop_taxonomy').removeClass('indexgrid');
		
		jQuery('#loop_archive').removeClass('eventgrid');				
		jQuery('#loop_archive').addClass('indexlist');
		jQuery('#loop_archive').removeClass('indexgrid');
		
		jQuery('.viewsbox a').attr('class','');
		jQuery(this).attr('class','active');
		
		jQuery('.viewsbox a.gridview').attr('class','');
		jQuery.cookie("display_view", "eventlist");
	});
	jQuery('.viewsbox a.gridview').click(function(e){	
		e.preventDefault();
		jQuery('#loop_listing').addClass('eventgrid');
		jQuery('#loop_listing').removeClass('eventlist');
		
		jQuery('#loop_taxonomy').addClass('eventgrid');
		jQuery('#loop_taxonomy').addClass('indexgrid');
		jQuery('#loop_taxonomy').removeClass('indexlist');
		
		jQuery('#loop_archive').addClass('eventgrid');		
		jQuery('#loop_archive').addClass('indexgrid');
		jQuery('#loop_archive').removeClass('indexlist');
		
		jQuery('.viewsbox a').attr('class','');
		jQuery(this).attr('class','active');
		
		jQuery('.viewsbox .listview a').attr('class','');
		jQuery.cookie("display_view", "eventgrid");
	});
});
