<?php /* INSERT DUMMY PACKAGES IN MONETIZATION PRICE PACKAGES */
$post_info = array(
					"post_title"	=>	'Free',
					"post_content"	=>	'This is a Free package. You will not get charged anything on selecting this package.',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'monetization_package'
					);
$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='monetization_package' AND post_title='Free'");
if(count($results) == '')
{
	$last_postid = wp_insert_post( $post_info );

	if(is_plugin_active('wpml-translation-management/plugin.php')){
		if(function_exists('wpml_insert_templ_post'))
			wpml_insert_templ_post($last_postid,'monetization_package'); /* insert post in language */
	}
}
$post_info1 = array(
					"post_title"	=>	'Christmas',
					"post_content"	=>	'',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'monetization_package'
					);
$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='monetization_package' AND post_title='Christmas'");
if(count($results) == '')
{
	$last_postid1 = wp_insert_post( $post_info1 );

	if(is_plugin_active('wpml-translation-management/plugin.php')){
		if(function_exists('wpml_insert_templ_post'))
			wpml_insert_templ_post($last_postid1,'monetization_package'); /* insert post in language */
	}
}
$post_meta = array(
					"package_type"			=> '1',
					"package_post_type"		=> 'all',
					"category"				=> '',
					"show_package"			=> '1',
					"package_amount"		=> '0',
					"validity" 				=> '12',
					"validity_per" 			=> 'M',
					"package_status"		=> '1',
					"recurring"				=> '0',
					"billing_num"			=> '',
					"billing_per"			=> '',
					"billing_cycle"			=> '',
					"is_featured"			=> '',
					"feature_amount"		=> '',
					"feature_cat_amount"	=> '');
foreach($post_meta as $key=>$val)
{
	add_post_meta($last_postid, $key, $val);
}
$post_meta1 = array(
					"package_type"			=> '2',
					"package_post_type"		=> 'all',
					"category"				=> '',
					"show_package"			=> '',
					"package_amount"		=> '100',
					"validity" 				=> '18',
					"validity_per" 			=> 'M',
					"package_status"		=> '1',
					"recurring"				=> '1',
					"billing_num"			=> '1',
					"billing_per"			=> 'M',
					"billing_cycle"			=> '12',
					"is_featured"			=> '1',
					"feature_amount"		=> '15',
					"feature_cat_amount"	=> '15');
foreach($post_meta1 as $key=>$val)
{
	add_post_meta($last_postid1, $key, $val);
} ?>