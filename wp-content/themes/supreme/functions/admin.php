<?php
/*
 * Theme Settings
 * 
 * @package supreme
 * @subpackage Template
 */
add_action( 'admin_menu', 'supreme_theme_admin_setup' );

function supreme_theme_admin_setup() {
    
	global $theme_settings_page;
	
	/* Get the theme settings page name */
	$theme_settings_page = 'appearance_page_theme-settings';

	/* Get the theme prefix. */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'supreme_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'supreme_theme_validate_settings' );
	
	/* Enqueue styles */
	add_action( 'admin_enqueue_scripts', 'supreme_admin_scripts' );

}

/* Adds custom meta boxes to the theme settings page. */
function supreme_theme_settings_meta_boxes() {

	/* Add a custom meta box. */
	add_meta_box(
		'supreme-theme-meta-box',			// Name/ID
		__( 'General', 'supreme' ),	// Label
		'supreme_theme_meta_box',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'normal',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);
	
	/* Add a custom meta box. */
	add_meta_box(
		'supreme-theme-meta-box-2',			// Name/ID
		__( 'Layout', 'supreme' ),	// Label
		'supreme_theme_meta_box_2',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'side',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);	

	/* Add additional add_meta_box() calls here. */
}

/* Function for displaying the first meta box. */
function supreme_theme_meta_box() { ?>

	<table class="form-table">
	
		<!-- Logo upload -->

		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'supreme_logo_url' ); ?>"><?php _e( 'Logo:', 'supreme' ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( 'supreme_logo_url' ); ?>" name="<?php echo hybrid_settings_field_name( 'supreme_logo_url' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'supreme_logo_url' ) ); ?>" />
				<input id="supreme_logo_upload_button" class="button" type="button" value="Upload" />
				<p class="description"><?php _e( 'Upload image for logo. Once uploaded, click the Insert Into Post button. If that does not work, copy the address of the image and paste it in the input field above. Next, click on Save Settings buton at the bottom of this page. The image will automatically display here after settings are saved.', 'supreme' ); ?></p>
				
				<?php /* Display uploaded image */
				if ( hybrid_get_setting( 'supreme_logo_url' ) ) { ?>
                    <p><img src="<?php echo hybrid_get_setting( 'supreme_logo_url' ); ?>" alt=""/></p>
				<?php } ?>
			</td>
		</tr>
		
		<!-- Show Site Description -->
		<tr>
			<th><label for="<?php echo hybrid_settings_field_id( 'supreme_site_description' ); ?>"><?php _e( 'Site Description:', 'supreme' ); ?></label></th>
			<td>
				<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_site_description' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_site_description' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_site_description' ) ); ?>" />
				<label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_site_description' ) ); ?>"><?php _e( 'Hide Site Description', 'supreme' ); ?></label>
			</td>
		</tr>
	
		<!-- Content Display -->
	
		<tr>
			<th><?php _e( 'Display Excerpts', 'supreme' ); ?></th>
			<td>
				<!-- Archive Page Display Excerpt -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_archive_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_archive_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_archive_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_archive_display_excerpt' ) ); ?>"><?php _e( 'Display excerpts on archive pages.', 'supreme' ); ?></label>
				</p>
				
				<!-- Front Page Display Excerpt -->
				<?php /* <p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_frontpage_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_frontpage_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_frontpage_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_frontpage_display_excerpt' ) ); ?>"><?php _e( 'Display Excerpts on front (blog) page.', 'supreme' ); ?></label>
				</p> */ ?>
				
				<!-- Search Results Page Display Excerpt -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_search_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_search_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_search_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_search_display_excerpt' ) ); ?>"><?php _e( 'Display Excerpts on search result pages.', 'supreme' ); ?></label>
				</p>

				<p class="description"><?php _e( 'By default, the archive pages and search result page display the full post.', 'supreme' ); ?></p>
			</td>
		</tr>
		
		<!-- Header Search -->
		<?php /* 
		<tr>
			<th><?php _e( 'Search Forms In Header', 'supreme' ); ?></th>
			<td>
				<!-- Show Primary Search -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_header_primary_search' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_header_primary_search' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_header_primary_search' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_header_primary_search' ) ); ?>"><?php _e( 'Show search form under Header Primary Menu', 'supreme' ); ?>
				</p>
				
				<!-- Show Secondary Search -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_header_secondary_search' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_header_secondary_search' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_header_secondary_search' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_header_secondary_search' ) ); ?>"><?php _e( 'Show search form under Header Secondary Menu', 'supreme' ); ?></label>
				</p>
				
				<p class="description"><?php _e( 'By default, search forms in the header are not displayed. Use this option to make them visible.', 'supreme' ); ?></p>
				<p class="description"><?php _e( 'Primary Search is dependent on whether Header Primary Menu is active. If Header Primary Menu is not active, Primary Search will not show regardless of your setting. The same goes for Secondary Search and Header Secondary Menu.', 'supreme' ); ?></p>
			</td>
		</tr>
		 */ ?>
		<!-- Author Biography -->

		<tr>
			<th><?php _e( 'Author Biography and Avatar', 'supreme' ); ?></th>
			<td>
				<!-- Show On Posts -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_author_bio_posts' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_author_bio_posts' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_author_bio_posts' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_author_bio_posts' ) ); ?>"><?php _e( 'Show On Posts:', 'supreme' ); ?></label>
				</p>
				
				<!-- Show On Pages -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'supreme_author_bio_pages' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_author_bio_pages' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_author_bio_pages' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_author_bio_pages' ) ); ?>"><?php _e( 'Show On Pages:', 'supreme' ); ?></label>
				</p>
				<p class="description"><?php _e( 'This controls the display of the author biography box in the loop-entry-author.php file, which includes an avatar and biography (from your user profile page).', 'supreme' ); ?></p>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table --><?php
	
}

/* Function for displaying the second meta box. */
function supreme_theme_meta_box_2() { 
	$support_layout = get_theme_support('theme-layouts');
	$support_bbpress = get_theme_support('bbpress');
	$support_buddypress = get_theme_support('buddypress');
?>

	<table class="form-table">
		
		<!-- Global Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_global_layout' ) ); ?>"><?php _e( 'Global Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_global_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_global_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_default' ); ?>> <?php echo __( 'Default', 'supreme' ) ?> </option>
				<?php if(@in_array('1c',$support_layout[0])) : ?>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_1c' ); ?>> <?php echo __( 'One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-l',$support_layout[0])) : ?>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-r',$support_layout[0])) : ?>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-c',$support_layout[0])) : ?>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'Three Columns, Center', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-l',$support_layout[0])) : ?>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'Three Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-r',$support_layout[0])) : ?>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'Three Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-1c',$support_layout[0])) : ?>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'Header Left One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-l',$support_layout[0])) : ?>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'Header Left Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-r',$support_layout[0])) : ?>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'Header Left, Two Columns Right', 'supreme' ) ?>
				</option>
				<?php endif; ?>
				<?php if(@in_array('hr-1c',$support_layout[0])) : ?>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'Header Right One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-2c-l',$support_layout[0])) : ?>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'Header Right Two Columns, Left', 'supreme' ) ?>
				</option>
				<?php endif; ?>	
				<?php if(@in_array('hr-2c-r',$support_layout[0])) : ?>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'supreme_global_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'Header Right Two Columns, Right', 'supreme' ) ?>
				</option>
				<?php endif; ?>	
			    </select>
			    <p class="description"><?php _e( 'Set the layout for the entire site. The default design is Two Columns Left, therefore, Default and Two Columns Left will display the same layout.', 'supreme' ); ?></p>
			</td>
		</tr>	
		<?php if($support_bbpress):?>
		<!-- bbPress Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_bbpress_layout' ) ); ?>"><?php _e( 'bbPress Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_bbpress_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_bbpress_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_default' ); ?>> <?php echo __( 'Default', 'supreme' ) ?> </option>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_1c' ); ?>> <?php echo __( 'One Column', 'supreme' ) ?> </option>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'Two Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'Three Columns, Center', 'supreme' ) ?> </option>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'Three Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'Three Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'Header Left One Column', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'Header Left Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'Header Left Two Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'Header Right One Column', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'Header Right Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'supreme_bbpress_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'Header Right Two Columns, Right', 'supreme' ) ?> </option>
			    </select>
			    <span class="description"><?php _e( 'If bbPress (message board) is installed then use this option if you want to set a custom layout for all bbPress pages. For setting per forum or per topic layout, find the forum or topic to edit via the All Forums or All Topics management page, click on the Edit link, then set the custom layout.', 'supreme' ); ?></span>			
			</td>
		</tr>
		<?php 
			endif;
			if($support_buddypress):
		?>
		<!-- BuddyPress Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_buddypress_layout' ) ); ?>"><?php _e( 'BuddyPress Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_buddypress_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_buddypress_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_default' ); ?>> <?php echo __( 'Default', 'supreme' ) ?> </option>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_1c' ); ?>> <?php echo __( 'One Column', 'supreme' ) ?> </option>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'Two Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'Three Columns, Center', 'supreme' ) ?> </option>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'Three Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'Three Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'Header Left One Column', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'Header Left Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'Header Left Two Columns, Right', 'supreme' ) ?> </option>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'Header Right One Column', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'Header Right Two Columns, Left', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'supreme_buddypress_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'Header Right Two Columns, Right', 'supreme' ) ?> </option>
			    </select>
			    <span class="description"><?php _e( 'If BuddyPress (social networking) is installed then use this option if you want to set a custom layout for all BuddyPress pages.', 'supreme' ); ?></span>
			</td>
		</tr>
		<?php endif;?>
		<!-- Jigoshop Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_jigoshop_layout' ) ); ?>"><?php _e( 'Jigoshop Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_jigoshop_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_jigoshop_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_default' ); ?>> <?php echo __( 'Default', 'supreme' ) ?> </option>
				<?php if(@in_array('1c',$support_layout[0])) : ?>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_1c' ); ?>> <?php echo __( 'One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-l',$support_layout[0])) : ?>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-r',$support_layout[0])) : ?>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-c',$support_layout[0])) : ?>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'Three Columns, Center', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-l',$support_layout[0])) : ?>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'Three Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-r',$support_layout[0])) : ?>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'Three Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-1c',$support_layout[0])) : ?>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'Header Left One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-l',$support_layout[0])) : ?>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'Header Left Two Columns Left', 'supreme' ) ?>
				</option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-r',$support_layout[0])) : ?>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'Header Left Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-1c',$support_layout[0])) : ?>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'Header Right One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-2c-l',$support_layout[0])) : ?>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'Header Right Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>	
				<?php if(@in_array('hr-2c-r',$support_layout[0])) : ?>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'supreme_jigoshop_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'Header Right Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>	
			    </select>
			    <span class="description"><?php _e( 'If Jigoshop (e-commerce) is installed then use this option if you want to set a custom layout for all Jigoshop pages.', 'supreme' ); ?></span>
			</td>
		</tr>
		
		<!-- Woocommerce Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_woocommerce_layout' ) ); ?>"><?php _e( 'WooCommerce Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'supreme_woocommerce_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'supreme_woocommerce_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_default' ); ?>> <?php echo __( 'Default', 'supreme' ) ?> </option>
				<?php if(@in_array('1c',$support_layout[0])) : ?>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_1c' ); ?>> <?php echo __( 'One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-l',$support_layout[0])) : ?>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-r',$support_layout[0])) : ?>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-c',$support_layout[0])) : ?>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'Three Columns, Center', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-l',$support_layout[0])) : ?>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'Three Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-r',$support_layout[0])) : ?>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'Three Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-1c',$support_layout[0])) : ?>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'Header Left One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-l',$support_layout[0])) : ?>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'Header Left Two Columns, Left', 'supreme' ) ?>
				</option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-r',$support_layout[0])) : ?>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'Header Left Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-1c',$support_layout[0])) : ?>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'Header Right One Column', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-2c-l',$support_layout[0])) : ?>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'Header Right Two Columns, Left', 'supreme' ) ?> </option>
				<?php endif; ?>	
				<?php if(@in_array('hr-2c-r',$support_layout[0])) : ?>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'supreme_woocommerce_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'Header Left Two Columns, Right', 'supreme' ) ?> </option>
				<?php endif; ?>
			    </select>
			    <span class="description"><?php _e( 'If WooCommerce (e-commerce) is installed then use this option if you want to set a custom layout for all WooCommerce pages.', 'supreme' ); ?></span>
			</td>
		</tr>	
		
		<!-- End custom form elements. -->
	</table><!-- .form-table -->		
	
<?php }		

/* Validate theme settings. */
function supreme_theme_validate_settings( $settings ) {

	$settings['supreme_logo_url'] = esc_url_raw( $settings['supreme_logo_url'] );
	$settings['supreme_site_description'] = ( isset( $settings['supreme_site_description'] ) ? 1 : 0 );

	$settings['supreme_archive_display_excerpt'] = ( isset( $settings['supreme_archive_display_excerpt'] ) ? 1 : 0 );
	$settings['supreme_frontpage_display_excerpt'] = ( isset( $settings['supreme_frontpage_display_excerpt'] ) ? 1 : 0 );
	$settings['supreme_search_display_excerpt'] = ( isset( $settings['supreme_search_display_excerpt'] ) ? 1 : 0 );

	$settings['supreme_header_primary_search'] = ( isset( $settings['supreme_header_primary_search'] ) ? 1 : 0 );
	$settings['supreme_header_secondary_search'] = ( isset( $settings['supreme_header_secondary_search'] ) ? 1 : 0 );

	$settings['supreme_author_bio_posts'] = ( isset( $settings['supreme_author_bio_posts'] ) ? 1 : 0 );
	$settings['supreme_author_bio_pages'] = ( isset( $settings['supreme_author_bio_pages'] ) ? 1 : 0 );
	
	$settings['supreme_global_layout'] = wp_filter_nohtml_kses( $settings['supreme_global_layout'] );
	$settings['supreme_bbpress_layout'] = wp_filter_nohtml_kses( $settings['supreme_bbpress_layout'] );
	$settings['supreme_buddypress_layout'] = wp_filter_nohtml_kses( $settings['supreme_buddypress_layout'] );
	$input['supreme_jigoshop_layout'] = wp_filter_nohtml_kses( $input['supreme_jigoshop_layout'] );
	$input['supreme_woocommerce_layout'] = wp_filter_nohtml_kses( $input['supreme_woocommerce_layout'] );

    /* Return the array of theme settings. */
    return $settings;
}

/* Enqueue scripts (and related stylesheets) */
function supreme_admin_scripts( $hook_suffix ) {
    
    global $theme_settings_page;
	
    if ( $theme_settings_page == $hook_suffix ) {

	    wp_enqueue_script( 'supreme_admin', get_template_directory_uri() . '/js/supreme-admin.js', array( 'jquery', 'media-upload' ), '20120607', false );

    }
}

?>