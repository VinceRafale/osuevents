//Ddsmoothmenu Init
ddsmoothmenu.init({
    mainmenuid: "menu", //menu DIV id
    orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
    classname: 'ddsmoothmenu', //class added to menu's outer DIV
    //customtheme: ["#1c5a80", "#18374a"],
    contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
});
//Tabbed
jQuery(document).ready(function() {
    jQuery('#gc_tab > ul').tabs({
        fx: {
            height: 'toggle', 
            opacity: 'toggle'
        }
    });
//jQuery('#featuredvid > ul').tabs();
});
//Flexslider
jQuery(window).load(function() {
    jQuery('.flexslider').flexslider();
});
//Portfolio Slider
jQuery(document).ready(function() {
    // initialize carousel
    jQuery("#carousels").simplecarousel({
        next: jQuery('.next'),
        prev: jQuery('.prev'),
        slidespeed: 700,
        auto: 5000,
        width: 950,
        height: 350
    });
});

jQuery(window).load(function() {
    jQuery('.slider-list').flexslider({
        controlNav: false,
        animation: "slide",
        directionNav: true
    });
    jQuery('.related_items').flexslider({
        controlNav: false,
        animation: "slide",
        directionNav: true
    });
});
//Fade images
 jQuery(document).ready(function(){
    jQuery("img.postimg,.post-thumb img").hover(function() {
      jQuery(this).stop().animate({opacity: "0.5"}, '500');
    },
    function() {
      jQuery(this).stop().animate({opacity: "1"}, '500');
    });
  });
//IE8 Place holder

jQuery(document).ready(function($){

if(!Modernizr.input.placeholder){

	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur();
	$('[placeholder]').parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	});

}

});