<?php
define('TITLE_TEXT',__('Title','supreme'));
define('SET_TIME_OUT_TEXT',__('Set Time Out','supreme'));
define('SET_THE_SPEED_TEXT',__('Set the speed','supreme'));
define('QUOTE_TEXT',__('Quote text','supreme'));
define('AUTHOR_NAME_TEXT',__('Author name','supreme'));
// =============================== Testimonials  Widget======================================
if(!class_exists('templ_testimonials_widget')){
class templ_testimonials_widget extends WP_Widget {
	function templ_testimonials_widget() {
	//Constructor
		$widget_ops = array('classname' => 'widget testimonials', 'description' => 'Displays Users Testimonials.');		
		$this->WP_Widget('testimonials_widget',apply_filters('templ_testimonial_widget_title_filter',__('T &rarr; Testimonials','supreme')), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		
		$link_text = empty($instance['link_text']) ? '' : apply_filters('widget_title', $instance['link_text']);
		$link_url = empty($instance['link_url']) ? '' : apply_filters('widget_title', $instance['link_url']);
		
		$fadin = empty($instance['fadin']) ? '3000' : apply_filters('widget_fadin', $instance['fadin']);
		$fadout = empty($instance['fadout']) ? '2000' : apply_filters('widget_fadout', $instance['fadout']);
		$transition = empty($instance['transition']) ? 'fade' : apply_filters('widget_fadout', $instance['transition']);
		$author_text = empty($instance['author']) ? '' : apply_filters('widget_author', $instance['author']);
		$quote_text = empty($instance['quotetext']) ? '' : apply_filters('widget_quotetext', $instance['quotetext']);
		 ?>	
 
<?php if($quote_text ){?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.cycle.all.latest.js"></script>
<script type="text/javascript">
var $testimonials = jQuery.noConflict();
$testimonials(document).ready(function() {
  $testimonials('#testimonials')
	.cycle({
        fx: '<?php echo $transition; ?>', // choose your transition type, ex: fade, scrollUp, scrollRight, shuffle
		pager:  '#nav',
		 timeout: '<?php echo $fadin; ?>',
         speed:'<?php echo $fadout; ?>'

     });
});
</script>
<?php }?>
	<div class="testimonials">
		 
  		<?php if($title){?><h3 class="widget-title i_testimonials"><span><?php echo sprintf(__('%s','supreme'), $title); ?></span></h3><?php }?>        
         
         <div id="testimonials" class="testimonials_wrap">
         <?php for($c=0; $c < count($author_text); $c++){
			if($author_text[$c] !=''){?>	
         	<span class="active">
                
				  	<?php echo sprintf(__('%s','supreme'), $quote_text[$c]);?>
              		<?php if($author_text[$c]){?> <cite> - <?php echo sprintf(__('%s','supreme'), $author_text[$c]); ?></cite><?php }?>
                
            </span>
         <?php }
		 } ?>
        
       
		
     </div>
	 <?php 
		if($link_url!="" && $link_text!=""){
	 ?>
			<a href="<?php echo $link_url; ?>" class="testimonial_external_link"><?php echo $link_text; ?></a>
	 <?php } ?>
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
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'link_text' => '', 'link_url' => '', 'author' => '','quotetext' => '','fadin' => '','fadout' => '','transition' => '' ) );		
		$title = strip_tags($instance['title']);
		$link_text = strip_tags($instance['link_text']);
		$link_url = strip_tags($instance['link_url']);
		$fadin = ($instance['fadin']);
		$fadout = ($instance['fadout']);
		$transition = ($instance['transition']);
		$author1 = ($instance['author']);
		$quotetext1 = ($instance['quotetext']);

		global $author,$quotetext;
		$text_author=$this->get_field_name('author');
		$text_quotetext=$this->get_field_name('quotetext');
?>

<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT;?> :<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('fadin'); ?>"><?php echo SET_TIME_OUT_TEXT;?> :<input class="widefat" id="<?php echo $this->get_field_id('fadin'); ?>" name="<?php echo $this->get_field_name('fadin'); ?>" type="text" value="<?php echo esc_attr($fadin); ?>" /></label></p>
<p><label for="<?php echo $this->get_field_id('fadout'); ?>"><?php echo SET_THE_SPEED_TEXT;?> :<input class="widefat" id="<?php echo $this->get_field_id('fadout'); ?>" name="<?php echo $this->get_field_name('fadout'); ?>" type="text" value="<?php echo esc_attr($fadout); ?>" /></label></p>
<p>
	<label for="<?php echo $this->get_field_id('transition'); ?>"><?php _e("Transition type","supreme");?> :
		<select id="<?php echo $this->get_field_id('transition'); ?>" name="<?php echo $this->get_field_name('transition'); ?>" >
			<option value="fade" <?php if("fade"==$transition){echo "selected=selected";}?>><?php _e("Fade","supreme");?></option>
			<option value="scrollUp" <?php if("scrollUp"==$transition){echo "selected=selected";}?>><?php _e("Scroll Up","supreme");?></option>
			<option value="scrollRight" <?php if("scrollRight"==$transition){echo "selected=selected";}?>><?php _e("Scroll Right","supreme");?></option>
			<option value="shuffle" <?php if("shuffle"==$transition){echo "selected=selected";}?>><?php _e("Shuffle","supreme");?></option>
		</select>
	</label>
</p>
			<p>
			  <label for="<?php echo $this->get_field_id('quotetext'); ?>"><?php _e('Quote text: ','supreme');?>
			  <textarea class="widefat" id="<?php echo $this->get_field_id('quotetext'); ?>" name="<?php echo $text_quotetext; ?>[]" type="text" ><?php echo esc_attr($quotetext1[0]); ?> </textarea>
			  </label>
			</p>

			<p>
			  <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author name: ','supreme');?>
			  <input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $text_author; ?>[]" type="text" value="<?php echo esc_attr($author1[0]); ?>" />
			  </label>
			</p>
		<div id="tGroup" class="tGroup">
		<?php
		for($i=1;$i<count($author1);$i++)
		{							
			if($author1[$i]!="")
			{
				$j=$i+1;
				echo '<div  class="TextDiv'.$j.'">';
				echo '<p>';
				
				echo '<label>'.QUOTE_TEXT.$j;
				echo ' <textarea class="widefat"  name="'.$text_quotetext.'[]" >'.esc_attr($quotetext1[$i]).'</textarea>';
				echo '</label>';
			
				echo '</p>';
				echo '<p>';
				echo '<label>'.AUTHOR_NAME_TEXT.$j;
				echo ' <input type="text" class="widefat"  name="'.$text_author.'[]" value="'.esc_attr($author1[$i]).'"></label>';
				echo '</label>';
				echo '</p>';
				echo '</div>';
			}
		}
		?>
		</div>
		<input value="+ Add more" id="addtButton" class="addButton" type="button" onclick="add_tfields('<?php echo $text_author; ?>','<?php echo $text_quotetext; ?>');"/>
		<input value="- Remove" id="removetButton" class="removeButton" type="button" onclick="remove_tfields();" />
		

	<p>
		<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e("Link Text",'supreme');?> :
			<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e("Link Url",'supreme');?> :
			<input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url'); ?>" type="text" value="<?php echo esc_attr($link_url); ?>" />
		</label>
	</p>
	
<?php
	
	}
}
register_widget('templ_testimonials_widget');
}

add_action('admin_head','tmpl_add_script_addnew_');
if(!function_exists('tmpl_add_script_addnew_')){
function tmpl_add_script_addnew_()
{
	global $author,$quotetext;
	?>
      <script type="application/javascript">			
		var counter = 2;
		function add_tfields(name,ilname)
		{
			var newTextBoxDiv = jQuery(document.createElement('div')).attr("class", 'TextBoxDiv' + counter);
			
			newTextBoxDiv.html('<p><label>Quote text '+ counter +' </label>'+'<textarea  class="widefat" name="'+ilname+'[]" id="textbox' + counter + '" value="" ></textarea></p>');
			
			newTextBoxDiv.append('<p><label>Author name '+ counter + '</label>'+'<input type="text" class="widefat" name="'+name+'[]" id="textbox' + counter + '" value="" ></p>');
			newTextBoxDiv.appendTo(".tGroup");
				
		    counter++;
		}
		function remove_tfields()
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
}
?>