<?php
class Main_Connector_Class  {
	var $components;
	var $current_action_response;

	public function __construct() {		
		$this->sections = array(
								'tevolution_bundled' => array(
														'name' => __( 'Bundled Features', DOMAIN ), 
														'description' => __( 'Features bundled with Tevolution.', DOMAIN )
													), 								
								'standalone_plugin' => array(
														'name' => __( 'Templatic Plugins', DOMAIN ), 
														'description' => __( 'Plugins developed by Templatic.', DOMAIN )
													)
								);	
		
		//get the templtic stand alone plugin list
		$response = wp_remote_post( 'http://templatic.com/templatic-standalone-plugin.xml', array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true		
		    )
		);		
		if( is_wp_error( $response ) ) {		 
		} else {
		  $data = $response['body'];
		}			
		$doc = new DOMDocument();
		$doc->loadXML($data);
		$sourceNode = $doc->getElementsByTagName("templatic-standalone-plugin");
		foreach($sourceNode as $source)
		{
			$plugin_name = $source->getElementsByTagName("plugin-name");
			$pluginname = $plugin_name->item(0)->nodeValue; 
			$plugin_folder = $source->getElementsByTagName("plugin-folder");
			$pluginfolder = $plugin_folder->item(0)->nodeValue; 
			$plugin_image = $source->getElementsByTagName("plugin-image");
			$pluginimage = $plugin_image->item(0)->nodeValue; 
			$plugin_description = $source->getElementsByTagName("plugin-description");
			$plugindescription = $plugin_description->item(0)->nodeValue; 
			$plugin_path = $source->getElementsByTagName("plugin-path");
			$pluginpath = $plugin_path->item(0)->nodeValue; 
			$plugin_download_url = $source->getElementsByTagName("plugin-download-url");
			$plugindownloadurl = $plugin_download_url->item(0)->nodeValue; 
			$plugin_argument = $source->getElementsByTagName("plugin-argument");
			$pluginargument = $plugin_argument->item(0)->nodeValue; 
			$stand_alone_plugin[]=array(
								   'name'=>$pluginname,
								   'folder'=>$pluginfolder,
								   'image'=>$pluginimage,
								   'short_description'=>$plugindescription,
								   'filepath'=>$pluginpath,
								   'donwload_url'=>$plugindownloadurl,
								   'add_query_arg'=>$pluginargument
								   );
		}		
	//
		
		$this->templatic_components = array(
								'tevolution_bundled' => array(
											    array('name'=>__('Bulk Import / Export',DOMAIN)),
											    array('name'=>__('Claim Post Manager',DOMAIN)),
											    array('name'=>__('Custom Fields Manager',DOMAIN)),
											    array('name'=>__('Custom Post Types Manager',DOMAIN)),
											    array('name'=>__('Security Manager',DOMAIN)),
											    array('name'=>__('Monetization',DOMAIN)),
											    array('name'=>__('User registration/Login Management',DOMAIN)),
											    ),
								//add templatic standalone plugin information
								'standalone_plugin' => $stand_alone_plugin
								);
			
		$this->closed_components = array();

		
	} // End __construct()
	
	public function get_section_links () {
		$html = '';
		
		$total = 0;
		
		$sections = array(
						'all' => array( 'href' => '#all', 'name' => __( 'All', DOMAIN ), 'class' => 'current all tab' )
					);
					
		foreach ( $this->sections as $k => $v ) {			
			$total += count( $this->templatic_components[$k] );
			$sections[$k] = array( 'href' => '#' . esc_attr( $this->config->token . $k ), 'name' => $v['name'], 'class' => 'tab', 'count' => count( $this->templatic_components[$k] ) );			
		}		
		$sections['all']['count'] = $total;		
		$sections = apply_filters( $this->config->token . '_main_section_links_array', $sections );
		
			
		
		$count = 1;
		foreach ( $sections as $k => $v ) {
			
			$count++;
			if ( $v['count'] > 0 ) 
			{				
				$html .= '<li><a href="' . $v['href'] . '"';
				if ( isset( $v['class'] ) && ( $v['class'] != '' ) ) { $html .= ' class="' . esc_attr( $v['class'] ) . '"'; }
				$html .= '>' . $v['name'] . '</a>';
				$html .= ' <span>(' . $v['count'] . ')</span>';
				if ( $count <= count( $sections ) ) { $html .= ' | '; }
				$html .= '</li>' . "\n";
			}
		}
		
		echo $html;
		do_action( $this->config->token . '_main_get_section_links' );
	} // End get_section_links()	
		
}
/*
 * Add action display listing of templatic plugin
 */
add_action('tevolution_plugin_list','list_of_templatic_plugin');

/*
 * Function Name: list_of_templatic_plugin
 * Return Display link for Templatic bundle featur list and also templatic standalone plugin
 */
function list_of_templatic_plugin()
{	
	global $Main_Connector_Class;
	$Main_Connector_Class= new Main_Connector_Class();
	?>
     <ul class="subsubsub">
		<?php echo $Main_Connector_Class->get_section_links(); ?>
	</ul>     
     <?php
}

add_action('templconnector_bundle_box','templatic_standalone_bundle_box');

function templatic_standalone_bundle_box()
{
	global $Main_Connector_Class;
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$Main_Connector_Class= new Main_Connector_Class();		
	if(!empty($Main_Connector_Class->templatic_components['standalone_plugin']))
	{
		echo '</div>';		
		echo '<div id="standalone_plugin" class="metabox-holder wrapper widgets-holder-wrap">';?>
		<h3 class="section-title"><?php _e('Templatic Standalone Plugin',DOMAIN);?></h3>     
     <?php
	}
	//echo "<pre>";print_r($Main_Connector_Class);echo "</pre>";
	foreach($Main_Connector_Class->templatic_components as $k=> $v)
	{
		
		if($k=='standalone_plugin')
		{			
			for($i=0;$i<count($v);$i++)
			{
				$name=$v[$i]['name'];
				$fodler=$v[$i]['fodler'];
				$image=$v[$i]['image'];	
				$short_description=$v[$i]['short_description'];				
				$filepath=$v[$i]['filepath'];
				$donwload_url=$v[$i]['donwload_url'];
				$add_query_arg=$v[$i]['add_query_arg'];
				$filename=ABSPATH."wp-content/plugins/".$filepath;						
				if(!file_exists($filename))
				{
					?>
					<div id="templatic_<?php echo str_replace('-','',$name);?>" class="postbox widget_div">
						 <div title="Click to toggle" class="handlediv"></div>
						 <h3 class="hndle"><span><?php _e($name,DOMAIN); ?></span></h3>
						  <div class="inside">
						  	<p><img class="dashboard_img" src="<?php echo $image;?>" /><?php _e($short_description,DOMAIN);?></p>
                                   <div id="publishing-action">
                                         <a href="<?php _e($donwload_url,DOMAIN);?>" class="templatic-tooltip button-primary" target="_blank"><?php _e('Download & Activate &rarr;',DOMAIN); ?></a>
                                     </div>
						  </div>
					</div>
					<?php	
					
				}else if(!is_plugin_active($filepath))
				{
					?>
                         <div id="templatic_<?php echo str_replace('-','',$name);?>" class="postbox widget_div">
						 <div title="Click to toggle" class="handlediv"></div>
						 <h3 class="hndle"><span><?php _e($name,DOMAIN); ?></span></h3>
						  <div class="inside">
						  	<p><img class="dashboard_img" src="<?php echo $image;?>" /><?php _e($short_description,DOMAIN);?></p>
                                   <div id="publishing-action">                                   	
                                         <a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=$add_query_arg&plugin=".$filepath."&true=1";?>" class="templatic-tooltip button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a>
                                    </div>
						  </div>
					</div>
                         <?php
					if((isset($_REQUEST['activated']) && $_REQUEST['activated']!="") &&(isset($_REQUEST['plugin']) && $_REQUEST['plugin']!=""))
					{
						$current = get_option( 'active_plugins' );
						$plugin = plugin_basename( trim($_REQUEST['plugin'] ) );	
						if ( !in_array( $plugin, $current ) ) {
						   $current[] = $plugin;
						   sort( $current );		  
						   update_option( 'active_plugins', $current );		  
						}
						update_option($_REQUEST['activated'],'Active');
						if($i==0):
						?>
                        <script type="text/javascript">
							window.location='<?php echo $_SERVER['REQUEST_URI'];?>';
						</script>                              
						<?php endif;
					}
				}
			}
		}// standalone templatic plugin if condition
	}//templatic componests foreach
	
}
?>