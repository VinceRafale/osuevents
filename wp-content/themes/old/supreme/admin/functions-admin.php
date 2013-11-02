<?php
/*
 * Theme Settings
 * 
 * @package rainbow
 * @subpackage Template
 */
add_action( 'admin_menu', 'rainbow_theme_admin_setup' );

function rainbow_theme_admin_setup() {
    
	global $theme_settings_page;
	
	/* Get the theme settings page name */
	$theme_settings_page = 'appearance_page_theme-settings';

	/* Get the theme prefix. */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'rainbow_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'rainbow_theme_validate_settings' );
	
	/* Enqueue styles */
	add_action( 'admin_enqueue_scripts', 'rainbow_admin_scripts' );

}

/* Adds custom meta boxes to the theme settings page. */
function rainbow_theme_settings_meta_boxes() {

	/* Add a custom meta box. */
	add_meta_box(
		'rainbow-theme-meta-box',			// Name/ID
		__( 'General', 'supreme' ),	// Label
		'rainbow_theme_meta_box',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'normal',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);
	
	/* Add a custom meta box. */
	add_meta_box(
		'rainbow-theme-meta-box-2',			// Name/ID
		__( 'Layout', 'supreme' ),	// Label
		'rainbow_theme_meta_box_2',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'side',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);	

	/* Add additional add_meta_box() calls here. */
}

/* Function for displaying the first meta box. */
function rainbow_theme_meta_box() { ?>

	<table class="form-table">
	
		<!-- Logo upload -->

		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'rainbow_logo_url' ); ?>"><?php _e( 'Logo:', 'supreme' ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( 'rainbow_logo_url' ); ?>" name="<?php echo hybrid_settings_field_name( 'rainbow_logo_url' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'rainbow_logo_url' ) ); ?>" />
				<input id="rainbow_logo_upload_button" class="button" type="button" value="Upload" />
				<p class="description"><?php _e( 'Upload image for logo. Once uploaded, click the Insert Into Post button. If that does not work, copy the address of the image and paste it in the input field above. Next, click on Save Settings buton at the bottom of this page. The image will automatically display here after settings are saved.', 'supreme' ); ?></p>
				
				<?php /* Display uploaded image */
				if ( hybrid_get_setting( 'rainbow_logo_url' ) ) { ?>
                    <p><img src="<?php echo hybrid_get_setting( 'rainbow_logo_url' ); ?>" alt=""/></p>
				<?php } ?>
			</td>
		</tr>
		
		<!-- Show Site Description -->
		<tr>
			<th></th>
			<td>
				<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_site_description' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_site_description' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_site_description' ) ); ?>" />
				<label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_site_description' ) ); ?>"><?php _e( 'Hide Site Description', 'supreme' ); ?></label>
			</td>
		</tr>
	
		<!-- Content Display -->
	
		<tr>
			<th><?php _e( 'Display Excerpts', 'supreme' ); ?></th>
			<td>
				<!-- Archive Page Display Excerpt -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_archive_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_archive_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_archive_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_archive_display_excerpt' ) ); ?>"><?php _e( 'Display excerpts on archive pages.', 'supreme' ); ?></label>
				</p>
				
				<!-- Front Page Display Excerpt -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_frontpage_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_frontpage_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_frontpage_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_frontpage_display_excerpt' ) ); ?>"><?php _e( 'Display Excerpts on front (blog) page.', 'supreme' ); ?></label>
				</p>
				
				<!-- Search Results Page Display Excerpt -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_search_display_excerpt' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_search_display_excerpt' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_search_display_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_search_display_excerpt' ) ); ?>"><?php _e( 'Display Excerpts on search result pages.', 'supreme' ); ?></label>
				</p>

				<p class="description"><?php _e( 'By default, the front/blog, archive pages, and search result page display the excerpt.', 'supreme' ); ?></p>
			</td>
		</tr>
		
		<!-- Header Search -->

		<tr>
			<th><?php _e( 'Search Forms In Header', 'supreme' ); ?></th>
			<td>
				<!-- Show Primary Search -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_header_primary_search' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_header_primary_search' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_header_primary_search' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_header_primary_search' ) ); ?>"><?php _e( 'Show search form under Header Primary Menu', 'supreme' ); ?>
				</p>
				
				<!-- Show Secondary Search -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_header_secondary_search' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_header_secondary_search' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_header_secondary_search' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_header_secondary_search' ) ); ?>"><?php _e( 'Show search form under Header Secondary Menu', 'supreme' ); ?></label>
				</p>
				
				<p class="description"><?php _e( 'By default, search forms in the header are not displayed. Use this option to make them visible.', 'supreme' ); ?></p>
				<p class="description"><?php _e( 'Primary Search is dependent on whether Header Primary Menu is active. If Header Primary Menu is not active, Primary Search will not show regardless of your setting. The same goes for Secondary Search and Header Secondary Menu.', 'supreme' ); ?></p>
			</td>
		</tr>

		<!-- Author Biography -->

		<tr>
			<th><?php _e( 'Author Biography and Avatar', 'supreme' ); ?></th>
			<td>
				<!-- Show On Posts -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_author_bio_posts' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_author_bio_posts' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_author_bio_posts' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_author_bio_posts' ) ); ?>"><?php _e( 'Show On Posts:', 'supreme' ); ?></label>
				</p>
				
				<!-- Show On Pages -->
				<p>
					<input class="checkbox" type="checkbox" <?php checked( hybrid_get_setting( 'rainbow_author_bio_pages' ), true ); ?> id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_author_bio_pages' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_author_bio_pages' ) ); ?>" /> <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_author_bio_pages' ) ); ?>"><?php _e( 'Show On Pages:', 'supreme' ); ?></label>
				</p>
				<p class="description"><?php _e( 'This controls the display of the author biography box in the loop-entry-author.php file, which includes an avatar and biography (from your user profile page).', 'supreme' ); ?></p>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table --><?php
	
}

/* Function for displaying the second meta box. */
function rainbow_theme_meta_box_2() {
$support_layout = get_theme_support('theme-layouts');
$support_bbpress = get_theme_support('bbpress');
$support_buddypress = get_theme_support('buddypress');
 ?>

	<table class="form-table">
		
		<!-- Global Layout -->
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_global_layout' ) ); ?>"><?php _e( 'Global Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_global_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_global_layout' ) ); ?>">
			
				<option value="layout_default" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_default' ); ?>> <?php echo __( 'layout_default', 'supreme' ) ?> </option>
				<?php if(@in_array('1c',$support_layout[0])) : ?>
					<option value="layout_1c" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_1c' ); ?>> <?php echo __( 'layout_1c', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-l',$support_layout[0])) : ?>
					<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'layout_2c_l', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('2c-r',$support_layout[0])) : ?>
					<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'layout_2c_r', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-c',$support_layout[0])) : ?>
					<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'layout_3c_c', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-l',$support_layout[0])) : ?>
					<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'layout_3c_l', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('3c-r',$support_layout[0])) : ?>
					<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'layout_3c_r', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-1c',$support_layout[0])) : ?>
					<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'layout_hl_1c', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-l',$support_layout[0])) : ?>
					<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'layout_hl_2c_l', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hl-2c-r',$support_layout[0])) : ?>
					<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'layout_hl_2c_r', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-1c',$support_layout[0])) : ?>
					<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'layout_hr_1c', 'supreme' ) ?> </option>
				<?php endif; ?>
				<?php if(@in_array('hr-2c-l',$support_layout[0])) : ?>
					<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'layout_hr_2c_l', 'supreme' ) ?> </option>
				<?php endif; ?>	
				<?php if(@in_array('hr-2c-r',$support_layout[0])) : ?>
					<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'rainbow_global_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'layout_hr_2c_r', 'supreme' ) ?> </option>
				<?php endif; ?>	
			    </select>
			    <p class="description"><?php _e( 'Set the layout for the entire site. The default layout is 2 columns with content on the left, therefore, layout_default and layout_2c_l will display the same layout.', 'supreme' ); ?></p>
				<ul>
					<li><strong><?php _e( 'Layout Guide', 'supreme' ); ?></strong></li>
					<li><?php _e( 'layout_default: Default', 'supreme' ); ?></li>
					<?php if(@in_array('1c',$support_layout[0])) : ?><li><?php _e( 'layout_1c: 1 Column', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('2c-l',$support_layout[0])) : ?><li><?php _e( 'layout_2c_l: 2 Columns, Left', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('2c-r',$support_layout[0])) : ?><li><?php _e( 'layout_2c_r: 2 Columns, Right', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('3c-c',$support_layout[0])) : ?><li><?php _e( 'layout_3c_c: 3 Columns, Center', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('3c-l',$support_layout[0])) : ?><li><?php _e( 'layout_3c_l: 3 Columns, Left', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('3c-r',$support_layout[0])) : ?><li><?php _e( 'layout_3c_r: 3 Columns, Right', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('h1-lc',$support_layout[0])) : ?><li><?php _e( 'layout_hl_1c: Header Left 1 Column', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('h1-2c-l',$support_layout[0])) : ?><li><?php _e( 'layout_hl_2c_l: Header Left 2 Columns, Left', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('h1-2c-r',$support_layout[0])) : ?><li><?php _e( 'layout_hl_2c_r: Header Left 2 Columns, Right', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('hr-1c',$support_layout[0])) : ?><li><?php _e( 'layout_hr_1c: Header Right 1 Column', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('hr-2c-l',$support_layout[0])) : ?><li><?php _e( 'layout_hr_2c_l: Header Right 2 Columns, Left', 'supreme' ); ?></li><?php endif; ?>
					<?php if(@in_array('hr-2c-r',$support_layout[0])) : ?><li><?php _e( 'layout_hr_2c_r: Header Right 2 Columns, Right', 'supreme' ); ?></li><?php endif; ?>
				</ul>
			</td>
		</tr>	

		<!-- bbPress Layout -->
		<?php if($support_bbpress ): ?>
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_bbpress_layout' ) ); ?>"><?php _e( 'bbPress Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_bbpress_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_bbpress_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_default' ); ?>> <?php echo __( 'layout_default', 'supreme' ) ?> </option>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_1c' ); ?>> <?php echo __( 'layout_1c', 'supreme' ) ?> </option>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'layout_2c_l', 'supreme' ) ?> </option>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'layout_2c_r', 'supreme' ) ?> </option>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'layout_3c_c', 'supreme' ) ?> </option>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'layout_3c_l', 'supreme' ) ?> </option>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'layout_3c_r', 'supreme' ) ?> </option>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'layout_hl_1c', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'layout_hl_2c_l', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'layout_hl_2c_r', 'supreme' ) ?> </option>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'layout_hr_1c', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'layout_hr_2c_l', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'rainbow_bbpress_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'layout_hr_2c_r', 'supreme' ) ?> </option>
			    </select>
			    <span class="description"><?php _e( 'If bbPress (message board) is installed then use this option if you want to set a custom layout for all bbPress pages. For setting per forum or per topic layout, find the forum or topic to edit via the All Forums or All Topics management page, click on the Edit link, then set the custom layout.', 'supreme' ); ?></span>			
			</td>
		</tr>
		<?php endif; ?>
		<!-- BuddyPress Layout -->
		<?php if($support_buddypress): ?>
		<tr>
			<th>
			    <label for="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_buddypress_layout' ) ); ?>"><?php _e( 'BuddyPress Layout:', 'supreme' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo esc_attr( hybrid_settings_field_id( 'rainbow_buddypress_layout' ) ); ?>" name="<?php echo esc_attr( hybrid_settings_field_name( 'rainbow_buddypress_layout' ) ); ?>">
				<option value="layout_default" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_default' ); ?>> <?php echo __( 'layout_default', 'supreme' ) ?> </option>
				<option value="layout_1c" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_1c' ); ?>> <?php echo __( 'layout_1c', 'supreme' ) ?> </option>
				<option value="layout_2c_l" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_2c_l' ); ?>> <?php echo __( 'layout_2c_l', 'supreme' ) ?> </option>
				<option value="layout_2c_r" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_2c_r' ); ?>> <?php echo __( 'layout_2c_r', 'supreme' ) ?> </option>
				<option value="layout_3c_c" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_3c_c' ); ?>> <?php echo __( 'layout_3c_c', 'supreme' ) ?> </option>
				<option value="layout_3c_l" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_3c_l' ); ?>> <?php echo __( 'layout_3c_l', 'supreme' ) ?> </option>
				<option value="layout_3c_r" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_3c_r' ); ?>> <?php echo __( 'layout_3c_r', 'supreme' ) ?> </option>
				<option value="layout_hl_1c" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hl_1c' ); ?>> <?php echo __( 'layout_hl_1c', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_l" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hl_2c_l' ); ?>> <?php echo __( 'layout_hl_2c_l', 'supreme' ) ?> </option>
				<option value="layout_hl_2c_r" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hl_2c_r' ); ?>> <?php echo __( 'layout_hl_2c_r', 'supreme' ) ?> </option>
				<option value="layout_hr_1c" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hr_1c' ); ?>> <?php echo __( 'layout_hr_1c', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_l" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hr_2c_l' ); ?>> <?php echo __( 'layout_hr_2c_l', 'supreme' ) ?> </option>
				<option value="layout_hr_2c_r" <?php selected( hybrid_get_setting( 'rainbow_buddypress_layout' ), 'layout_hr_2c_r' ); ?>> <?php echo __( 'layout_hr_2c_r', 'supreme' ) ?> </option>
			    </select>
			    <span class="description"><?php _e( 'If BuddyPress (social networking) is installed then use this option if you want to set a custom layout for all BuddyPress pages.', 'supreme' ); ?></span>
			</td>
		</tr>		
		<?php endif; ?>
		<!-- End custom form elements. -->
	</table><!-- .form-table -->		
	
<?php }		

/* Validate theme settings. */
function rainbow_theme_validate_settings( $input ) {

	$input['rainbow_logo_url'] = esc_url_raw( $input['rainbow_logo_url'] );
	$input['rainbow_site_description'] = ( isset( $input['rainbow_site_description'] ) ? 1 : 0 );

	$input['rainbow_archive_display_excerpt'] = ( isset( $input['rainbow_archive_display_excerpt'] ) ? 1 : 0 );
	$input['rainbow_frontpage_display_excerpt'] = ( isset( $input['rainbow_frontpage_display_excerpt'] ) ? 1 : 0 );
	$input['rainbow_search_display_excerpt'] = ( isset( $input['rainbow_search_display_excerpt'] ) ? 1 : 0 );

	$input['rainbow_header_primary_search'] = ( isset( $input['rainbow_header_primary_search'] ) ? 1 : 0 );
	$input['rainbow_header_secondary_search'] = ( isset( $input['rainbow_header_secondary_search'] ) ? 1 : 0 );

	$input['rainbow_author_bio_posts'] = ( isset( $input['rainbow_author_bio_posts'] ) ? 1 : 0 );
	$input['rainbow_author_bio_pages'] = ( isset( $input['rainbow_author_bio_pages'] ) ? 1 : 0 );
	$input['rainbow_global_layout'] = wp_filter_nohtml_kses( $input['rainbow_global_layout'] );
	
	$input['rainbow_bbpress_layout'] = wp_filter_nohtml_kses( $input['rainbow_bbpress_layout'] );
	$input['rainbow_buddypress_layout'] = wp_filter_nohtml_kses( $input['rainbow_buddypress_layout'] );

    /* Return the array of theme settings. */
    return $input;
}

/* Enqueue scripts (and related stylesheets) */
function rainbow_admin_scripts( $hook_suffix ) {
    
    global $theme_settings_page;
	
    if ( $theme_settings_page == $hook_suffix ) {

	    wp_enqueue_script( 'rainbow_functions-admin', get_template_directory_uri() . '/admin/functions-admin.js', array( 'jquery', 'media-upload' ), '20120607', false );

    }
}

?>