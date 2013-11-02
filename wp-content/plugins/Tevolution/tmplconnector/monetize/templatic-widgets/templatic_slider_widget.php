<?php
/*
 * Create the templatic Slider
 */
	
class templatic_slider extends WP_Widget {
	
	function templatic_slider() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic Slider', 'description' => __('Home page post slider with display selected post type or display custom images on home page slider') );
		$this->WP_Widget('templatic_slider', __('T &rarr; Home page Main Slider'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;		
		 /*
		  *  Add flexslider script and style sheet in head tag
		  */
		 wp_enqueue_script('flexslider_script', TEMPL_PLUGIN_URL."/js/jquery.flexslider-min.js");
		 wp_enqueue_script('flexslider_script', TEMPL_PLUGIN_URL."/js/modernizr.js");
		 wp_enqueue_style( 'flexslider_css', TEMPL_PLUGIN_URL.'/css/flexslider.css');
		 
				$custom_banner_temp = empty($instance['custom_banner_temp']) ? '' : $instance['custom_banner_temp'];
				$post_type = empty($instance['post_type']) ? 'post,1' : apply_filters('widget_category', $instance['post_type']);				
				$posttype = explode(',',$post_type);
				
				$post_type = $posttype[0];
				$cat_id = $posttype[1];
				$cat_name = $posttype[2];
						
				$s1 = empty($instance['s1']) ? '' : apply_filters('widget_s1', $instance['s1']);
				$s1_title = empty($instance['s1_title']) ? '' : apply_filters('widget_s1_title', $instance['s1_title']);
				$animation = empty($instance['animation']) ? 'slide' : apply_filters('widget_number', $instance['animation']);
				$number = empty($instance['number']) ? '5' : apply_filters('widget_number', $instance['number']);
				$height = empty($instance['height']) ? '' : apply_filters('widget_height', $instance['height']);
				$autoplay = empty($instance['autoplay']) ? '' : apply_filters('widget_autoplay', $instance['autoplay']);
				$slideshowSpeed =  empty($instance['slideshowSpeed']) ? '' : apply_filters('widget_autoplay', $instance['slideshowSpeed']);
				$sliding_direction = empty($instance['sliding_direction']) ? 'horizontal' : $instance['sliding_direction'];
				$reverse = empty($instance['reverse']) ? 'false' : $instance['reverse'];
				$animation_speed = empty($instance['animation_speed']) ? '2000' : $instance['animation_speed'];
				
				
				// Carousel Slider Settings
				$is_Carousel = empty($instance['is_Carousel']) ? '' : $instance['is_Carousel'];
				if($is_Carousel)
				{
					$item_width = empty($instance['item_width']) ? '0' : $instance['item_width'];
					//$item_margin = empty($instance['item_margin']) ? '0' : $instance['item_margin'];
					$min_item = empty($instance['min_item']) ? '0' : $instance['min_item'];
					$max_items = empty($instance['max_items']) ? '0' : $instance['max_items'];
					$item_move = empty($instance['item_move']) ? '0' : $instance['item_move'];
					
					$width=apply_filters('carousel_slider_width',$item_width);
					$height=apply_filters('carousel_slider_height','');
				}else{
					$item_width=0;
					$min_item = 0;
					$max_items =0;
					$item_move=0;
					$width=0;
					$height='';
				}
			
				if($autoplay==''){ $autoplay='false'; }
				if($slideshowSpeed==''){$slideshowSpeed='300000';}
				if($animation_speed==''){$animation_speed='2000';}
				if($autoplay=='false'){ $animation_speed='300000'; }
	?>
<script type="text/javascript">					
					 jQuery(window).load(function(){
					  jQuery('.flexslider').flexslider({
						animation: '<?php echo $animation;?>',
						slideshow: <?php echo $autoplay;?>,
						direction: "<?php echo $sliding_direction;?>",
						slideshowSpeed: <?php echo $slideshowSpeed;?>,						
						<?php if($autoplay=='true'):?>animationSpeed: <?php echo $animation_speed;?>,<?php endif;?>
						reverse: <?php echo $reverse;?>,
						animationLoop: true,
						startAt: 0,
						smoothHeight: true,
						easing: "swing",
						pauseOnHover: true,
						video: true,
						controlNav: true, 
						directionNav: true,
						prevText: "Previous",
						nextText: "Next",
						// Carousel Slider Options
						itemWidth: <?php echo $item_width;?>,                   //{NEW} Integer: Box-model width of individual carousel items, including horizontal borders and padding.
						itemMargin: <?php if($min_item!=""){echo $min_item;}else echo '0'?>,                  //{NEW} Integer: Margin between carousel items.
						minItems: <?php echo $min_item;?>,                    //{NEW} Integer: Minimum number of carousel items that should be visible. Items will resize fluidly when below this.
						maxItems: <?php echo $max_items;?>,                    //{NEW} Integer: Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.
						move: <?php echo $item_move;?>,                        //{NEW} Integer: Number of carousel items that should move on animation. If 0, slider will move all visible items.
					     start: function(slider){
							jQuery('body').removeClass('loading');
					   	}
						
					  });
					});
					//FlexSlider: Default Settings
				</script>

<div class="flexslider clearfix">
	<div class="slides_container clearfix">
		<?php do_action('templ_slider_search_widget',$instance);// add action for display additional field?>
		<ul class="slides">
			<?php if(isset($instance['custom_banner_temp']) && $instance['custom_banner_temp'] == 1):?>
			<?php if(is_array($s1)):?>
			<?php for($i=0;$i<count($s1);$i++):?>
			<?php if($s1[$i]!=""):
								   
									?>
			<li>
				<div class="post_list">
				<div class="post_img">
					<img src="<?php echo $s1[$i]; ?>"  alt="" />
				</div>
				<div class="slider-post">
					<h2><?php echo $s1_title[$i]; ?></h2>
				</div>
				</div>
			</li>
			<?php endif;?>
			<?php endfor;//finish forloop?>
			<?php endif;?>
			<?php else: 
						global $post,$wpdb;
						$counter=0;
						$postperslide = 1;
						$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));
						$term = get_term( $cat_id, $cat_name );
						$cat_id=$term->term_id;
						$args=array(												  
								  'post_type' => $post_type,
								  'posts_per_page' => $number,												  
								  'post_status' => 'publish' ,
								  'tax_query' => array(                
											array(
												'taxonomy' =>$taxonomies[0],
												'field' => 'id',
												'terms' => array($cat_id),
												'operator'  => 'IN'
											)            
										 )
								  );													
						$slide = null;	
						remove_all_actions('posts_where');
						$slide = new WP_Query($args);																				
						if( $slide->have_posts() ) {
							while ($slide->have_posts()) : $slide->the_post();
								global $post;	
									//check post thumbnail image if available
									if ( has_post_thumbnail()) {
										$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'home-page-slider');
										$post_images=$large_image_url[0];
									}else{
									
										$post_images=bdw_get_images_plugin($post->ID,'home-page-slider');													
										$post_images = $post_images[0]['file'];
									}
								//$post_images =  bdw_get_images_with_info($post->ID,'home_slider');
								if($counter=='0' || $counter%$postperslide==0){ echo "<li>";}
							?>
			<div class="post_list">
				<div class="post_img"> <a href="<?php the_permalink(); ?>">
					<?php if($post_images != ""){?>
					<img  src="<?php echo $post_images;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" />
					<?php }else{?>
					<img  src="<?php echo TEMPL_PLUGIN_URL;?>tmplconnector/monetize/templatic-widgets/widget_images/add_220x220.png"  alt="<?php the_title(); ?>" title="<?php the_title(); ?>" width="<?php echo ($width!="")?$width:'875';?>" height="<?php echo ($height!="")?$height:'400';?>" />
					<?php }?>
					</a>
				</div>
				<div class="slider-post">
					<h2>
						<a href="<?php the_permalink() ?>" rel="bookmark"> <?php the_title(); ?> </a>
					</h2>
					<?php echo print_excerpt(50); ?>
					<?php do_action('slider_extra_content',get_the_ID());// do action for display the extra content?>
				</div>
			</div>
			<?php
								$counter++; 
								if($counter%$postperslide==0){ echo "</li>"; }
							endwhile;
						}
					?>
			<?php endif;?>
		</ul>
	</div>
</div>
<?php		
		echo $after_widget;		
	}
	
	function update($new_instance, $old_instance) {
				//save the widget						
				return $new_instance;
			}
	
	function form($instance) {		
		//widgetform in backend
				$instance = wp_parse_args( (array) $instance, array( 'search'=>'','search_post_type'=>'','location'=>'','distance'=>'','radius'=>'', 'post_type' => '', 'number' => '', 'animation'=>'', 'slideshowSpeed'=>'', 'animation_speed'=>'', 'sliding_direction'=>'', 'reverse'=>'', 'item_width'=>'','is_Carousel_temp'=>'',  'min_item'=>'', 'max_items'=>'', 'item_move'=>'', 'custom_banner_temp'=>'','s1' => '', 's1_title' => '' ) );
				
				// Widget Get Posts settings
				
				$custom_banner_temp = strip_tags($instance['custom_banner_temp']);
				$post_type = strip_tags($instance['post_type']);
				$number = strip_tags($instance['number']);
				
				// Slider Basic Settings
				$autoplay = strip_tags($instance['autoplay']);
				$animation = strip_tags($instance['animation']);
				$slideshowSpeed = strip_tags($instance['slideshowSpeed']);
				$sliding_direction = strip_tags($instance['sliding_direction']);
				$reverse = strip_tags($instance['reverse']);
				$animation_speed = strip_tags($instance['animation_speed']);
				
				// Carousel Slider Settings
				// Carousel Slider Settings
				$is_Carousel = strip_tags($instance['is_Carousel']);
				$item_width = strip_tags($instance['item_width']);
				//$item_margin = strip_tags($instance['item_margin']);
				$min_item = strip_tags($instance['min_item']);
				$max_items = strip_tags($instance['max_items']);
				$item_move = strip_tags($instance['item_move']);
				
				$is_Carousel_temp = strip_tags($instance['is_Carousel_temp']);
				$item_width = strip_tags($instance['item_width']);
				//$item_margin = strip_tags($instance['item_margin']);
				$min_item = strip_tags($instance['min_item']);
				$max_items = strip_tags($instance['max_items']);
				$item_move = strip_tags($instance['item_move']);
				
				//  If Custom Banner Slider (Settings)				
				$s1 = ($instance['s1']);
				$s1_title = ($instance['s1_title']);
				
				
			?>
<script type="text/javascript">										
					function select_custom_image(id,div_def,div_custom)
					{
						var checked=id.checked;
						jQuery('#'+div_def).slideToggle('slow');
						jQuery('#'+div_custom).slideToggle('slow');
					}
					function select_is_Carousel(id,div_def)
					{
						var checked=id.checked;
						jQuery('#'+div_def).slideToggle('slow');						
					}
				</script>
<?php do_action('templ_search_slider_widget_form',$this,$instance); // add action for display additional field?>
<p>
	<label for="<?php echo $this->get_field_id('animation'); ?>">
		<?php _e('Animation',DOMAIN); ?>
		:
		<select class="widefat" name="<?php echo $this->get_field_name('animation'); ?>" id="<?php echo $this->get_field_id('animation'); ?>">
			<option <?php if(esc_attr($animation)=='fade'){?> selected="selected"<?php }?> value="fade">
			<?php _e("Fade","templatic");?>
			</option>
			<option <?php if(esc_attr($animation)=='slide'){?> selected="selected"<?php }?> value="slide">
			<?php _e("Slide","templatic");?>
			</option>
		</select>
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('autoplay'); ?>">
		<?php _e('Slide show',DOMAIN); ?>
		:
		<select class="widefat" name="<?php echo $this->get_field_name('autoplay'); ?>" id="<?php echo $this->get_field_id('autoplay'); ?>">
			<option <?php if(esc_attr($autoplay)=='true'){?> selected="selected"<?php }?> value="true">
			<?php _e("Yes","templatic");?>
			</option>
			<option <?php if(esc_attr($autoplay)=='false'){?> selected="selected"<?php }?> value="false">
			<?php _e("No","templatic");?>
			</option>
		</select>
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('sliding_direction'); ?>">
		<?php _e('Sliding Direction',DOMAIN); ?>
		:
		<select class="widefat" name="<?php echo $this->get_field_name('sliding_direction'); ?>" id="<?php echo $this->get_field_id('sliding_direction'); ?>">
			<option <?php if(esc_attr($sliding_direction)=='horizontal'){?> selected="selected"<?php }?> value="horizontal">
			<?php _e("Horizontal","templatic");?>
			</option>
			<option <?php if(esc_attr($sliding_direction)=='vertical'){?> selected="selected"<?php }?> value="vertical">
			<?php _e("Vertical","templatic");?>
			</option>
		</select>
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('reverse'); ?>">
		<?php _e('Reverse Animation Direction',DOMAIN); ?>
		:
		<select class="widefat" name="<?php echo $this->get_field_name('reverse'); ?>" id="<?php echo $this->get_field_id('reverse'); ?>">
			<option <?php if(esc_attr($reverse)=='false'){?> selected="selected"<?php }?> value="false">
			<?php _e("False","templatic");?>
			</option>
			<option <?php if(esc_attr($reverse)=='true'){?> selected="selected"<?php }?> value="true">
			<?php _e("True","templatic");?>
			</option>
		</select>
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('slideshowSpeed'); ?>">
		<?php _e('Slide Show Speed',DOMAIN); ?>
		:
		<input class="widefat" id="<?php echo $this->get_field_id('slideshowSpeed'); ?>" name="<?php echo $this->get_field_name('slideshowSpeed'); ?>" type="text" value="<?php echo esc_attr($slideshowSpeed); ?>" />
	</label>
</p>
<p>
	<label for="<?php echo $this->get_field_id('animation_speed'); ?>">
		<?php _e('Animation Speed',DOMAIN); ?>
		:
		<input class="widefat" id="<?php echo $this->get_field_id('animation_speed'); ?>" name="<?php echo $this->get_field_name('animation_speed'); ?>" type="text" value="<?php echo esc_attr($animation_speed); ?>" />
	</label>
</p>
<!--is_Carousel -->
<p><br/>
	<label for="<?php echo $this->get_field_id('is_Carousel'); ?>">
		<input id="<?php echo $this->get_field_id('is_Carousel'); ?>" name="<?php echo $this->get_field_name('is_Carousel'); ?>" type="checkbox" value="1" <?php if($is_Carousel =='1'){ ?>checked=checked<?php } 
?>style="width:10px;" onclick="select_is_Carousel(this,'<?php echo $this->get_field_id('home_slide_carousel'); ?>');"/>
		<?php _e("<b>Settings for Carousel slider option?</b>",
"templatic");?>
	</label>
</p>
<div id="<?php echo $this->get_field_id('home_slide_carousel'); ?>" style="<?php if($is_Carousel =='1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
	<p>
		<label for="<?php echo $this->get_field_id('item_width'); ?>">
			<?php _e('Item Width: <br/><small>(Box-model width of individual items, including horizontal borders and padding.)</small>',DOMAIN); ?>
			:
			<input class="widefat" id="<?php echo $this->get_field_id('item_width'); ?>" name="<?php echo $this->get_field_name('item_width'); ?>" type="text" value="<?php echo esc_attr($item_width); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('min_item'); ?>">
			<?php _e('Min Item <br/><small>(Minimum number of items that should be visible. Items will resize fluidly when below this.)</small>',DOMAIN); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('min_item'); ?>" name="<?php echo $this->get_field_name('min_item'); ?>" type="text" value="<?php echo esc_attr($min_item); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('max_items'); ?>">
			<?php _e('Max Item <br/><small>(Maxmimum number of items that should be visible. Items will resize fluidly when above this limit.)</small>',DOMAIN); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('max_items'); ?>" name="<?php echo $this->get_field_name('max_items'); ?>" type="text" value="<?php echo esc_attr($max_items); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('item_move'); ?>">
			<?php _e('Items Move <br/><small>(Number of items that should move on animation. If 0, slider will move all visible items.)</small>',DOMAIN); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('item_move'); ?>" name="<?php echo $this->get_field_name('item_move'); ?>" type="text" value="<?php echo esc_attr($item_move); ?>" />
		</label>
	</p>
</div>

<!-- Finish is_Carousel -->
<p><br/>
	<label for="<?php echo $this->get_field_id('custom_banner_temp'); ?>">
		<input id="<?php echo $this->get_field_id('custom_banner_temp'); ?>" name="<?php echo $this->get_field_name('custom_banner_temp'); ?>" type="checkbox" value="1" <?php if($custom_banner_temp =='1'){ ?>checked=checked<?php } ?>style="width:10px;" onclick="select_custom_image(this,'<?php echo $this->get_field_id('home_slide_default_temp'); ?>','<?php echo $this->get_field_id('home_slide_custom_temp'); ?>');" />
		<?php _e('<b>Use custom images?</b>',DOMAIN);?>
		<br/>
	</label>
	<br/>
</p>
<div id="<?php echo $this->get_field_id('home_slide_default_temp'); ?>" style="<?php if($custom_banner_temp =='1'){ ?>display:none;<?php }else{?>display:block;<?php }?>">
	<p>
		<label for="<?php echo $this->get_field_id('post_type');?>" >
			<?php _e('Select Taxonomy:');?>
			<select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat" >
				<?php
                                    $taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
                                    $taxonomies = array_filter( $taxonomies, 'templatic_exclude_taxonomies' );
							 
							
							 ?>
				<?php
						foreach ( $taxonomies as $taxonomy ) {	
							$query_label = '';
							if ( !empty( $taxonomy->query_var ) )
								$query_label = $taxonomy->query_var;
							else
								$query_label = $taxonomy->name;
							
							if($taxonomy->labels->name!='Tags' && $taxonomy->labels->name!='Format'):	
								?>
				<optgroup label="<?php echo esc_attr( $taxonomy->object_type[0])."-".esc_attr($taxonomy->labels->name); ?>">
				<?php
									$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=1' );
									foreach ( $terms as $term ) {		
									$term_value=esc_attr($taxonomy->object_type[0]). ',' .$term->term_id.','.$query_label;
				?>
				<option style="margin-left: 8px; padding-right:10px;" value="<?php echo $term_value ?>" <?php if($post_type==$term_value) echo "selected";?>><?php echo '-' . esc_attr( $term->name ); ?></option>
				<?php } ?>
				

									?> </optgroup>
				<?php
								endif;
								
						}
				
		?>
			</select>
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">
			<?php _e('Number of posts:',DOMAIN);?>
			<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
		</label>
	</p>
</div>
<div id="<?php echo $this->get_field_id('home_slide_custom_temp'); ?>" style="<?php if($custom_banner_temp =='1'){ ?>display:block;<?php }else{?>display:none;<?php }?>">
	<div id="TextBoxesGroup" class="TextBoxesGroup">
		<div id="TextBoxDiv1" class="TextBoxDiv1">
			<p>
				<?php global $textbox_title;
								$textbox_title=$this->get_field_name('s1_title');
							?>
				<label for="<?php echo $this->get_field_id('s1_title'); ?>">
					<?php _e('Banner Slider Title 1');?>
					<input type="text" class="widefat"  name="<?php echo $textbox_title; ?>[]" value="<?php echo esc_attr($s1_title[0]); ?>">
				</label>
			</p>
			<p>
				<?php global $textbox_name;
								$textbox_name=$this->get_field_name('s1');
							?>
				<label for="<?php echo $this->get_field_id('s1'); ?>">
					<?php _e('Banner Slider Image 1 full URL <small>(ex.http://templatic.com/images/banner1.png, Image size 980x425 )</small>  :');?>
					<input type="text" class="widefat"  name="<?php echo $textbox_name; ?>[]" value="<?php echo esc_attr($s1[0]); ?>">
				</label>
			</p>
		</div>
		<?php
						for($i=1;$i<count($s1);$i++)
						{							
							if($s1[$i]!="")
							{
								$j=$i+1;
								echo '<div  class="TextBoxDiv-'.$j.'">';
								echo '<p>';
								echo '<label>Banner Slider Text '.$j;
								echo ' <input type="text" class="widefat"  name="'.$textbox_title.'[]" value="'.esc_attr($s1_title[$i]).'"></label>';
								echo '</label>';
								echo '</p>';
								echo '<p>';
								echo '<label>Banner Slider Image '.$j.' full URL';
								echo ' <input type="text" class="widefat"  name="'.$textbox_name.'[]" value="'.esc_attr($s1[$i]).'"></label>';
								echo '</label>';
								echo '</p>';
								echo '</div>';
							}
						}
						?>
	</div>
	<input value="Add Textbox" id="addButton" class="addButton" type="button" onclick="add_textbox('<?php echo $textbox_name;?>','<?php echo $textbox_title;?>');"/>
	<input value="Remove Textbox" id="removeButton" class="removeButton" type="button" onclick="remove_textbox();" />
</div>
<?php
	}
}
/*
 * templatic Slider widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_slider");') );
add_action('admin_footer','multitext_box');

function multitext_box()
{
	global $textbox_name,$textbox_title;
	?>
<script type="application/javascript">			
		var counter = 2;
		function add_textbox(name,title)
		{
			var newTextBoxDiv = jQuery(document.createElement('div')).attr("class", 'TextBoxDiv' + counter);
			newTextBoxDiv.html('<p><label>Banner Slider Title '+ counter + ' </label>'+'<input type="text" class="widefat" name="'+title+'[]" id="textbox' + counter + '" value="" ></p><p><label>Banner Slider Image '+ counter + ' full URL : </label>'+'<input type="text" class="widefat" name="'+name+'[]" id="textbox' + counter + '" value="" ></p>');			  
			newTextBoxDiv.appendTo(".TextBoxesGroup");
				
		    counter++;
		}
		function remove_textbox()
		{
		    if(counter-1==1){
			   alert("you need one textbox required.");
			   return false;
		    }
		    counter--;							
		    jQuery(".TextBoxDiv" + counter).remove();
		}
	</script>
<?php
}
?>