jQuery(document).ready(function() {
	jQuery('#organizer_logo_button').click(function(html) {
	 formfield = jQuery('#organizer_logo').attr('name');
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery('#organizer_logo').val(imgurl);
		 tb_remove();
	}
	return false;
 });
});
