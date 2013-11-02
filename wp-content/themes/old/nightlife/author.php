<?php
/**
 * Author page Template
 *
 * This is the author template.  Technically, it is the "author page" template.  It is used when a visitor is on the 
 * page assigned to show a site's latest blog posts.
 *
 * @package supreme
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

<?php do_atomic( 'before_content' ); // supreme_before_content ?>

<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>

<div id="content">
	
	<?php do_atomic( 'open_content' ); // supreme_open_content ?>	
	<div class="hfeed">
	
		<?php get_template_part( 'loop-author' ); // Loads the loop-author.php template. ?>
	
		<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>
		<div class="tabber">
            <ul class="tab">
                    <?php 
					global $current_user;
                        //	if($current_user->ID == $curauth->ID)
                            {
                                $curauth = wp_get_current_user();							
                                $user_id = get_query_var('author');
                            ?>
                            <li <?php if(!isset($_REQUEST['list'])){ echo 'class="active" ';}?> >  <a href="<?php echo get_author_posts_url($user_id, $author_nicename = '');?>"> <?php echo PRO_LISTED_EVENT_TEXT;?></a></li>
                            
                            <?php
                               $user_link = get_author_posts_url($user_id, $author_nicename = '');
                            ?>
                            <li <?php if(isset($_REQUEST['list']) && $_REQUEST['list']=='attend'){ echo 'class="active" ';}?>>  <a href="<?php if(strstr($user_link,'?') ){echo $user_link.'&amp;list=attend';}else{echo $user_link.'?list=attend';}?>"> <?php echo PRO_ATTEND_EVENT_TEXT;?> </a></li>
                            <li <?php if(isset($_REQUEST['list']) && $_REQUEST['list']=='facebook_event'){ echo 'class="active" ';}?>>  <a href="<?php if(strstr($user_link,'?') ){echo $user_link.'&amp;list=facebook_event';}else{echo $user_link.'?list=facebook_event';}?>"> <?php echo FACEBOOK_EVENT_TEXT;?> </a></li>
                            <?php 
                            } ?>
            </ul>
        </div>
		<div class="category_list_view" id="widget_index_upcomming_events_id">
        <?php if((isset($_REQUEST['list']) && $_REQUEST['list']!='facebook_event') || !isset($_REQUEST['list'])):?>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>			
					<div id="post-<?php the_ID(); ?>" class="post <?php hybrid_entry_class(); ?>">						
                        <div class="post_img img listimg"><?php
						get_the_image(array('post_id'=> get_the_ID(),'link_to_post'=>'false','size'=>'thumbnail','image_class'=>'post_img img listimg','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));
                        ?></div>
                        <!-- List view image -->
                        
						<?php get_sidebar( 'entry' ); // Loads the sidebar-entry.php template. ?>
						
						<div class="entry-content">
                        <?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
						<?php do_action('templ_show_edit_renew_delete_link');/* Display edit ,reenew,delete link for user wise */?>
						<?php //echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __('Published by [entry-author] on [entry-published] [entry-comments-link zero="Respond" one="%1$s" more="%1$s"] [entry-edit-link] [entry-permalink]', 'supreme' ) . '</div>'); ?>
                        <div class="custom_meta clearfix">
                        	<div class="col1">
                            <?php
							$st_time=get_post_meta($post->ID,'st_time',true);
							$en_time=get_post_meta($post->ID,'end_time',true);
							?>
							
                            <?php if(get_post_meta($post->ID,'st_date',true)!=""):?><p class="date"><span><?php _e('STARTING DATE',T_DOMAIN)?> : </span> <?php echo date("M dS,Y",strtotime(get_post_meta($post->ID,'st_date',true)));?></p><?php endif;?>
							<?php if(get_post_meta($post->ID,'end_date',true)!=""):?><p class="date"><span><?php _e('ENDING TIME',T_DOMAIN)?> : </span> <?php echo date("M dS,Y",strtotime(get_post_meta($post->ID,'end_date',true)));?></p><?php endif;?>
                            <?php if($st_time!="" && $en_time!=""):?> <p class="time"><span><?php _e('TIME',T_DOMAIN)?> : </span> <?php echo $st_time." - ".$en_time;?></p><?php endif;?>                        	
                            </div>
                            <div class="col2">
                            	<?php if(get_post_meta($post->ID,'address',true)!=""):?><p class="location"><span><?php _e('LOCATION',T_DOMAIN)?> : </span> <?php echo get_post_meta($post->ID,'address',true);?></p><?php endif;?>
                            </div>
                        </div>
							<?php the_taxonomies(array('before'=>'<p class="bottom_line"><span class="i_category">','sep'=>'</span>&nbsp;&nbsp;<span class="i_tag">','after'=>'</span></p>'));?>
							<?php //the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'supreme' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'supreme' ), 'after' => '</p>' ) ); ?>

						</div><!-- .entry-content -->						

						<?php do_atomic( 'close_entry' ); // supreme_close_entry ?>

					</div><!-- .hentry -->
			
			<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>
			
				<?php endwhile; ?>
			<?php else : ?>			
				<div class="<?php hybrid_entry_class(); ?>">
					<h2 class="entry-title"><?php _e( 'No Entries', 'supreme' ); ?></h2>				
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'supreme' ); ?></p>
					</div>					
				</div><!-- .hentry .error -->
		<?php endif; ?>
        <?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>
        <?php elseif($_REQUEST['list']=='facebook_event'):
					if(_iscurlinstalled() && $current_user->ID == $curauth->ID){
						?>
                        <div class="setting_tab">
                        <button id="hide_fb_fields" class="reverse" style="<?php if(get_user_meta($curauth->ID,'appID')){?> display:none; <?php } else { ?> <?php } ?>" onclick="return showFacebookSetting('hide_facebook_setting');">
							<?php echo HIDE_FACEBOOK_SETTING; ?>
                        </button>
                        <button id="edit_fb_fields" class="reverse" style="<?php if(get_user_meta($curauth->ID,'appID')){?> <?php } ?>" onclick="return showFacebookSetting('show_facebook_setting');">
							<?php echo SHOW_FACEBOOK_SETTING; ?>
                        </button>
                        </div>
                        <div id="show_api_fields" <?php if(get_user_meta($curauth->ID,'appID',true)){?> style="display:none;" <?php } ?>>
                        	<div class="form_row">
                            	<label for="appid"> <?php _e('AppID',T_DOMAIN); ?> : </label>
                                <input type="text" name="appid" id="appid" value="<?php echo get_user_meta($curauth->ID,'appID',true); ?>" />
                            </div>
                            <div class="form_row">
                            	<label for="secretid"> <?php _e('Secret ID',T_DOMAIN); ?> : </label>
                                <input type="text" name="secret_id" id="secret_id" value="<?php echo get_user_meta($curauth->ID,'secret',true); ?>" />
                            </div>
                            <div class="form_row">
                            	<label for="pageid"><?php _e('Page ID',T_DOMAIN); ?> : </label>
                                 <input type="text" name="page_id" id="page_id" value="<?php echo get_user_meta($curauth->ID,'pageID',true); ?>"/>
                            </div>
                            <div class="form_row">
                            	<input type="submit" name="submit" id="submit" value="Submit" onclick="return save_FbSetting(<?php echo $curauth->ID; ?>);" />
                            </div>                        	  	
                        </div>
                        
                        <?php	
					}
					?>
                    <div id="responsecontainer">
					  <?php $appID = get_user_meta($curauth->ID,'appID');
                        if(_iscurlinstalled())
                        {
                            if($appID)
                             facebook_events($curauth->ID); 
                             else { ?>
                                <p class="message" ><?php echo NO_FACEBOOK_EVENT;?> </p> <?php
                            }
                        }else{
                           _e('<p class="error">CURL is not installed on your server, please enbale CURL to use Facebook evenst API.<p>',T_DOMAIN);
                        }?>
                    </div>                    
        		
        <?php endif;// check request list facebook_event not?>
        </div>
		
		<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>
		
	</div><!-- .hfeed -->
	
	<?php do_atomic( 'close_content' ); // supreme_close_content ?>
	
	

</div><!-- #content -->

<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>
<script>
function showFacebookSetting(val)
{
	if(val == 'show_facebook_setting')
	{
		document.getElementById('hide_fb_fields').style.display = '';
		document.getElementById('edit_fb_fields').style.display = 'none';
		document.getElementById('show_api_fields').style.display = '';
	}
	else if(val == 'hide_facebook_setting')
	{
		document.getElementById('hide_fb_fields').style.display = 'none';
		document.getElementById('edit_fb_fields').style.display = '';
		document.getElementById('show_api_fields').style.display = 'none';
	}
	return true;
}
function save_FbSetting(user_id)
{
	var xmlHTTP;
	function GetXmlHttpObject()
	{
		xmlHTTP=null;
		try
		{
			xmlhttp=new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");			
			}
			catch (e)
			{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		
	}

	if (window.XMLHttpRequest)
	{
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
	 	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  	if(xmlhttp == null)
	{
		alert("Your browser not support the AJAX");	
		return;
	}
	

	var appid = document.getElementById("appid").value;
	var secret_id = document.getElementById("secret_id").value;
	var page_id = document.getElementById("page_id").value;
	  
	var url = "<?php echo get_stylesheet_directory_uri(); ?>/functions/ajax_save_fb_setting.php?appid="+appid+"&secret_id="+secret_id+"&page_id="+page_id;
	//xmlhttp.onreadystatechang = handleResponce();

	xmlhttp.onreadystatechange=function()
	{
	   	if(xmlhttp.readyState==4 && xmlhttp.status==200)
	   	{
			document.getElementById("responsecontainer").innerHTML=xmlhttp.responseText;
		}
	} 
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
</script>