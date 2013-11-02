<?php
/**
 * Loop Author Template
 *
 * Displays information at the top of the page about author pages.  
 * This is not shown on the front page or singular views.
 *
 * @package supreme
 * @subpackage Template
 */

?>
<?php $user_id = get_query_var('author'); ?>

<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard">

    <h1 class="loop-title fn n"><?php the_author_meta( 'display_name', $user_id ); ?></h1>

    <div class="loop-description">
        <?php $desc = get_the_author_meta( 'description', $user_id ); ?>

        <?php if ( !empty( $desc ) ) { ?>
            <?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), '60', '', get_the_author_meta( 'display_name', $user_id ) ); ?>

            <p class="user-bio">
                <?php echo $desc; ?>
            </p><!-- .user-bio -->
        <?php } ?>
    </div><!-- .loop-description -->

</div><!-- .loop-author -->
<div class="author_details clearfix">
    <div class="author_photo">
    <?php $curauth = wp_get_current_user();?>
			<?php if($curauth->user_photo != '') : ?>
			<img src="<?php echo $curauth->user_photo; ?>" width="75" height="75" />
			<?php else : echo get_avatar($curauth->ID, 75 ); endif; ?>
			<?php if($curauth->ID):?>
	        	<div class="editProfile"><a href="<?php echo get_option('home');?>/?ptype=profile" ><?php echo PROFILE_EDIT_TEXT;?> </a> </div>
            <?php endif; ?>
    </div>
	<div class="author_content">
		<div class="agent_biodata">        
        <?php
		global $form_fields_usermeta;		
		 foreach($form_fields_usermeta as $key=> $_form_fields_usermeta)
		  {			
				 if(get_user_meta($user_id,$key,true) != ""): 
					if($_form_fields_usermeta['on_author_page']): 
					if($_form_fields_usermeta['type']!='upload') :
		 ?>	  
		 <?php if($_form_fields_usermeta['type']=='multicheckbox'):  ?>
				<?php
					$checkbox = '';
					foreach(get_user_meta($user_id,$key,true) as $check):
							$checkbox .= $check.",";
					endforeach; ?>
					<p><label><?php echo $_form_fields_usermeta['label']; ?></label> : <?php echo substr($checkbox,0,-1); 
				?></p>
				<?php else:  ?>
					<p><label><?php echo $_form_fields_usermeta['label']; ?></label> : <?php echo get_user_meta($user_id,$key,true); ?></p>
					
				<?php endif;
				endif;
				if($_form_fields_usermeta['type']=='upload')
				{?>
				<p><label  style="vertical-align:top;"><?php echo $_form_fields_usermeta['label']." : "; ?></label> <img src="<?php echo get_user_meta($user_id,$key,true);?>" style="width:150px;height:150px" /></p>
				<?php }

					
				endif;
			endif;
		  }// finish display the user custom field display?>
		<?php if($curauth->user_url):
       	      	$website = $curauth->user_url;				
				if(!strstr($website,'http'))				
					 $website = 'http://'.$curauth->user_url;	?>
                 <span><a href="<?php echo $website; ?>" target="_blank"><?php echo PRO_WEBSITE_TEXT;?> </a></span>      	
        		<br class="clearfix"  />
		<?php endif;//finish check current author user url?>
        
        <span class="i_agent_others"><?php echo PRO_PROPERTY_LIST_TEXT;?> : <b>
						 <?php /* Fetch the total post of the user */
							if($user_id)
							{ echo get_authorlisting_evnets($user_id); } ?></b></span></p>
						<?php $user_meta_info = $wpdb->get_results("select * from $custom_usermeta_db_table_name where is_active = 1 and post_type='registration' and htmlvar_name = 'description' order by sort_order asc,admin_title asc");
							foreach($user_meta_info as $post_meta_info_obj)
							{
								if($post_meta_info_obj->htmlvar_name == 'description'){ ?>
								<p><?php echo $curauth->user_description; ?></p>
								<?php }
							} ?> 
        </div>
    </div>
</div>
<?php
$posts_per_page=get_option('posts_per_page');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args=array(
		'post_type'  =>CUSTOM_POST_TYPE_EVENT,
		'author'=>$user_id,
		'post_status' => 'publish',
		'paged'=>$paged,
		'order_by'=>'date',
		'order' => 'DESC'
	);					
query_posts( $args );
?>