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
		<?php $curauth = get_userdata($user_id); //wp_get_current_user($user_id);?>
          <?php if($curauth->user_photo != '') : ?>
         			<img src="<?php echo $curauth->user_photo; ?>" width="75" height="75" />
          <?php else :
				echo get_avatar($curauth->ID, 75 ); 
			endif; 
			?>
          <?php if(get_current_user_id()==$user_id):
				$profile_page_id=get_option('tevolution_profile');				
				$profile_url=get_permalink($profile_page_id);
				if($profile_url!=''):
				?>
	          		<div class="editProfile"><a href="<?php echo $profile_url;?>" ><?php echo PROFILE_EDIT_TEXT;?> </a> </div>
                    <?php endif;?>
          <?php endif; ?>
    </div>
	<div class="author_content">
		<div class="agent_biodata">        
        <?php
		global $form_fields_usermeta;
		if(is_array($form_fields_usermeta) && !empty($form_fields_usermeta)){
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
		  }
		 } // finish display the user custom field display?>
		<?php if($curauth->user_url):
       	      	$website = $curauth->user_url;				
				if(!strstr($website,'http'))				
					 $website = 'http://'.$curauth->user_url;	?>
                 <span><a href="<?php echo $website; ?>" target="_blank"><?php echo PRO_WEBSITE_TEXT;?> </a></span>      	
        		<br class="clearfix"  />
		<?php endif;//finish check current author user url		?>
        
        <span class="i_agent_others"><?php echo PRO_PROPERTY_LIST_TEXT;?> : <b>
						 <?php /* Fetch the total post of the user */
							if($user_id)
							{ echo get_authorlisting_evnets($user_id); } ?></b></span></p>
		<?php
		/* payment type details */
		$price_pkg = get_user_meta($curauth->ID,'package_select',true);
		$pagd_data = get_post($price_pkg);
		$package_name = $pagd_data->post_title;

		$pkg_type = get_post_meta($price_pkg,'package_type',true);
		$limit_no_post = get_post_meta($price_pkg,'limit_no_post',true);
		
		$submited =get_user_meta($curauth->ID,'list_of_post',true);
		$remaining = intval($limit_no_post) - intval($submited);
		if($pkg_type == 2){
			echo "<p>"; 
			$msg = __('You have subscribed to ',DOMAIN)."<strong>".$package_name."</strong> ";
			$msg .= __(' which allows you to submit ',DOMAIN)." <strong>".$limit_no_post."</strong> "." events";
			if($remaining >0){
			$msg .= " You have submitted <strong>".$submited." </strong> ";
			$msg .= __('events till now, go and submit remaining',DOMAIN)." ";
			$msg .= "  <strong>".$remaining." </strong> ";
			$msg .= __('events',DOMAIN);  
			}else{
			$msg .= " and your have already sumitted"." <strong>".$limit_no_post."</strong> "." to continue the listing Click on add/submit events.";
			}
			echo $msg; 
			echo ".</p>";

		}//get_the_author_meta( 'description', $userID );
		?> 
        </div>
		<?php 
			do_action('after_author_description');
		?>
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