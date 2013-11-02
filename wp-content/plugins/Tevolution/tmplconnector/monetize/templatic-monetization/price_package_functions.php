<?php
if (!class_exists('monetization')) {
class monetization
{
	/* NAME : INSERT PACKAGE DATA
	DESCRIPTION : THIS FUNCTION INSERTS PACKAGE DATA INTO POSTMETA TABLE CREATING A POST WITH POST TYPE PACKAGE */
	function insert_package_data($post_details)
	{
		$package_name = $post_details['package_name'];
		$package_desc = $post_details['package_desc'];
		
		$package_type = $post_details['package_type'];
		$package_post_type = $post_details['package_post_type'];
		$ptype = explode(',',$post_details['package_post_type']);

		$package_post_type = $ptype[0];
		if($package_post_type == 'all')
		{
			 $custom_taxonomy = get_option('templatic_custom_taxonomy',true);
			 $custm_category_type = array_keys($custom_taxonomy);
			 $post_category = array('category');
			 $package_taxonomy_type = array_merge($custm_category_type,$post_category);
		}
		else
		{
			$package_taxonomy_type = $ptype[1];
		}
		$package_categories = implode(',',$post_details['category']);
		
		$package_post = array(
			'post_title' 	=> $package_name,
			'post_content'  => $package_desc,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'monetization_package' );
				
		/* CREATING A POST OBJECT AND INSERT THE POST INTO THE DATABAE */
		if($_REQUEST['package_id'])
		{
			$package_id = $_REQUEST['package_id'];
			$package_post['ID'] = $_REQUEST['package_id'];
			$last_postid = wp_insert_post( $package_post );
			/* Finish the place geo_latitude and geo_longitude in postcodes table*/
			if(is_plugin_active('wpml-translation-management/plugin.php')){
				if(function_exists('wpml_insert_templ_post'))
					wpml_insert_templ_post($last_postid,'monetization_package'); /* insert post in language */
			}
			if($package_post_type == 'all')
			{
				foreach($package_taxonomy_type as $key=> $_tax)
				{
					wp_set_post_terms($last_postid,'',$_tax,false);
					foreach($_POST['category'] as $category)
					 {
						wp_set_post_terms($last_postid,$category,$_tax,true);
					 }
				}
			}
			else
			{
				foreach($post_details['category'] as $category)
				{
					wp_set_post_terms($last_postid,$category,$package_taxonomy_type,true);
				}
			}
			$msg_type = 'edit';
		}
		else
		{
			$last_postid = wp_insert_post( $package_post );
			/* Finish the place geo_latitude and geo_longitude in postcodes table*/
			if(is_plugin_active('wpml-translation-management/plugin.php')){
				if(function_exists('wpml_insert_templ_post'))
					wpml_insert_templ_post($last_postid,'monetization_package'); /* insert post in language */
			}
			if($package_post_type == 'all')
			{
				foreach($package_taxonomy_type as $key=> $_tax)
				{
					foreach($_POST['category'] as $category)
					 {
						wp_set_post_terms($last_postid,$category,$_tax,true);
					 }
				}
			}
			else
			{
				foreach($post_details['category'] as $category)
				{
					wp_set_post_terms($last_postid,$category,$package_taxonomy_type,true);
				}
			}
			$msg_type = 'add';
		}

		/* INSERT THE PACKAGE DATA INTO THE POSTMETA TABLE */

		$show_package = $post_details['show_package'];
		$package_amount = $post_details['package_amount'];
		$package_validity = $post_details['validity'];
		$package_validity_per = $post_details['validity_per'];
		$package_status = $post_details['package_status'];
		$package_is_recurring = $post_details['recurring'];
		$package_billing_num = $post_details['billing_num'];
		$package_billing_per = $post_details['billing_per'];
		$package_billing_cycle = $post_details['billing_cycle'];
		$package_is_featured = $post_details['is_featured'];
		$package_feature_amount = $post_details['feature_amount'];
		$package_feature_cat_amount = $post_details['feature_cat_amount'];
		$limit_no_post = $post_details['limit_no_post'];
		
		$custom = array('package_type' => $package_type,
						'package_post_type' => $package_post_type,
						'category' => $package_categories,
						'show_package' => $show_package,
						'package_amount' => $package_amount,
						'validity' => $package_validity,
						'validity_per' => $package_validity_per,
						'package_status' => $package_status,
						'recurring' => $package_is_recurring,
						'billing_num' => $package_billing_num,
						'billing_per' => $package_billing_per,
						'billing_cycle' => $package_billing_cycle,
						'is_featured' => $package_is_featured,
						'feature_amount' => $package_feature_amount,
						'feature_cat_amount' => $package_feature_cat_amount,
						'limit_no_post'=>$limit_no_post,
						);
		foreach($custom as $key=>$val)
		{				
			update_post_meta($last_postid, $key, $val);
		}
		
		
		$url = site_url().'/wp-admin/admin.php?page=monetization';
		echo '<form action="'.$url.'" method="get" id="frm_edit_package" name="frm_edit_package">
					<input type="hidden" value="monetization" name="page"><input type="hidden" value="success" name="package_msg"><input type="hidden" value="'.$msg_type.'" name="package_msg_type">
					<input type="hidden" value="packages" name="tab">
			  </form>
			  <script>document.frm_edit_package.submit();</script>';
			  exit;
	}
	/* EOF - INSERT PACKAGE DATA INTO THE DATABASE */
	/* EOF - DELETE PACKAGE DATA */

	/* NAME : FETCH PACKAGE IN FRONT END
	DESCRIPTION : THIS FUNCTION WILL FETCH ALL THE PACKAGES IN FRONT END */
	function fetch_monetization_packages_front_end($pkg_id,$div_id,$post_type,$taxonomy_slug,$post_cat)
	{
		global $wpdb,$post;
		$post_categories = explode(',',$post_cat);
		/* FETCH ALL THE POSTS WITH POST TYPE PACKAGE */
		if($div_id != 'ajax_packages_checkbox'){ $class ='form_row_pkg clearfix'; }
		$package_ids = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'monetization_package' AND post_status = 'publish'");
			if($div_id !='all_packages'){ /* this query will execute only for category wise packages */
			$pargs = array('post_type' => 'monetization_package','posts_per_page' => -1	,'post_status' => array('publish'),
				  'meta_query' => array('relation' => 'AND',array('key' => 'package_post_type','value' => array($post_type,'all'),'compare' => 'IN','type'=> 'text'),array('key' => 'show_package','value' =>  array(''),'compare' => 'IN','type'=> 'text'),array('key' => 'package_status','value' =>  '1','compare' => '=')),
				  'tax_query' => array( array('taxonomy' => $taxonomy_slug,'field' => 'id','terms' => $post_categories,'operator'  => 'IN') ),
			'orderby' => 'ID',
			'order' => 'DESC'
			);
			}else{ /* this query will execute for all package need to show even no category selected */
			$pargs = array('post_type' => 'monetization_package','posts_per_page' => -1	,'post_status' => array('publish'),
				  'meta_query' => array('relation' => 'AND',array('key' => 'package_post_type','value' => array($post_type,'all'),'compare' => 'IN','type'=> 'text'),array('key' => 'show_package','value' =>  array(1),'compare' => 'IN','type'=> 'text'),array('key' => 'package_status','value' =>  '1','compare' => '=')),
			'orderby' => 'ID',
			'order' => 'DESC'
			);
			}
			wp_reset_query();
			$package_query = null;
			$package_query = new WP_Query($pargs); 			
			if($div_id =='all_packages'){
			/* display this fields only when no deiv ID argument pass from funnction, so the intention is to display this fields only once */
			if(isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] !=''){
				$cat_price = $_SESSION['custom_fields']['all_cat_price'];
				}else{ $cat_price =''; }
				$cats_of = count($_REQUEST['category']);
			 ?>
			<input type="hidden" name="all_cat" id="all_cat" value="0"/>
            <?php 
				$tmpdata = get_option('templatic_settings');
				if($tmpdata['templatic-category_type'] == 'select'):
			?>	
			<input type="hidden" name="all_cat_price" id="all_cat_price" value="<?php if(isset($_REQUEST['category']) && $_REQUEST['category'] !=""){ if(is_array($_REQUEST['category']) && $cats_of >0){ $cat = explode(",",$_REQUEST['category'][0]); echo $cat[1]; }else{ echo $_REQUEST['category'];  }  }else{ if(isset($cat_price) && $cat_price !=''){ echo $cat_price; }else{ echo "0"; } }  ?>"/>
            <?php else: ?>
            <input type="hidden" name="all_cat_price" id="all_cat_price" value="<?php if(isset($_REQUEST['category']) && $_REQUEST['category'] !=""){ if(is_array($_REQUEST['category']) && $cats_of >1){ $cat = implode(",",$_REQUEST['category']); echo $cat[2]; }else{ echo $_REQUEST['category'];  }  }else{ if(isset($cat_price) && $cat_price !=''){ echo $cat_price; }else{ echo "0"; } }  ?>"/>
            <?php endif; ?>
			<span id='process2' style='display:none;'><img src="<?php echo TEMPL_PLUGIN_URL."tmplconnector/monetize/templatic-monetization/images/process.gif"; ?>" alt='Processing..' /></span>
			<?php } ?>
			
			<div id="<?php echo $div_id; ?>" class="<?php echo $class; ?>">
			<?php
			if( $package_query->have_posts() && (!isset($_REQUEST['action']) && $_REQUEST['action'] !='edit'))
			{
				if($div_id =='all_packages'){ ?>
				<div class="sec_title"><h3 id="package_data"><?php _e(SELECT_PACKAGE_TEXT,DOMAIN); ?></h3></div>
				<?php }
		
		/* FETCH ALL THE PACKAGE DATA FROM POST META TABLE */
		$selected_pkg = $_SESSION['custom_fields']['package_select'];
		if($pkg_id !=''){
			$selected_pkg = $pkg_id;
		}
		while($package_query->have_posts())
		{ 
			$package_query->the_post();
			$package_type = get_post_meta($post->ID,'package_type',true);
			$package_post_type = get_post_meta($post->ID,'package_post_type',true);
			$package_categories = get_post_meta($post->ID,'category',true);
			$show_package = get_post_meta($post->ID,'show_package',true);
			$package_amount = get_post_meta($post->ID,'package_amount',true);
			if($package_type == 2){
				$package_validity = get_post_meta($post->ID,'billing_num',true);
				$package_validity_per =get_post_meta($post->ID,'billing_per',true);
			}else{
				$package_validity = get_post_meta($post->ID,'validity',true);
				$package_validity_per = get_post_meta($post->ID,'validity_per',true);
			}
			$package_status = get_post_meta($post->ID,'package_status',true);
			$recurring = get_post_meta($post->ID,'recurring',true);
			$billing_num = get_post_meta($post->ID,'billing_num',true);
			$billing_per = get_post_meta($post->ID,'billing_per',true);
			$billing_cycle = get_post_meta($post->ID,'billing_cycle',true);
			$is_featured = get_post_meta($post->ID,'is_featured',true);
			$feature_amount_home = get_post_meta($post->ID,'feature_amount',true);
			$feature_cat_amount = get_post_meta($post->ID,'feature_cat_amount',true);  
			$featured_h = get_post_meta($post->ID,'home_featured_type',true); 
			$featured_c = get_post_meta($post->ID,'featured_type',true);
			$package_is_recurring = get_post_meta($post->ID,'recurring',true);
			$package_billing_num = get_post_meta($post->ID,'billing_num',true);
			$package_billing_per =get_post_meta($post->ID,'billing_per',true);
			$package_billing_cycle =get_post_meta($post->ID,'billing_cycle',true);
			
				if(isset($category_id)){ $catid = $category_id; }else{ $catid =''; }
				if(isset($cat_array) && $cat_array != "")
				{
					$catid = $cat_array;
				}
				else
				{
					if(isset($_REQUEST['category'])){
					$catid = $_REQUEST['category'];
					}else{ $catid =''; }
				} ?>
				
			<!-- DISPLAY THE PACKAGE IN FRONT END -->	
				<div class="package">
				 <label><input type="radio" onclick="show_featuredprice(<?php echo $post->ID; ?>);" id="price_select_<?php echo $post->ID; ?>" name="package_select" value="<?php echo $post->ID; ?>" <?php if($selected_pkg == $post->ID) {?>  checked="checked" <?php  } ?>>
				 <h3><?php echo $post->post_title; ?></h3>
				 <p><?php echo $post->post_content; ?></p>
				 <p><?php _e('Package type',DOMAIN);  echo " : ";  if($package_type  ==2){ _e('Pay per subscription',DOMAIN); }else{ _e('Pay per post',DOMAIN); } ?></p>
				 <?php
				 if($package_type  == 2 && get_post_meta($post->ID,'limit_no_post',true) !=''){
					echo LIMIT_NO_POST." : ".get_post_meta($post->ID,'limit_no_post',true);
				 }
				 ?>
				 <p class="cost"><span><?php _e(PACKAGE_COST,DOMAIN); ?> : <?php echo fetch_currency_with_position($package_amount); ?></span>&nbsp;&nbsp; <span><?php _e(VALIDITY_TEXT,DOMAIN); ?> : <?php echo $package_validity; ?>&nbsp;
				 <?php  if($package_validity_per == 'D')
						{
							 _e(DAYS_TEXT,DOMAIN);
						}
						elseif($package_validity_per == 'M')
						{
							_e(MONTHS_TEXT,DOMAIN);
						}
						else
						{
							_e(YEAR_TEXT,DOMAIN);
						} ?></span></p>
                     <?php					
					 if($package_is_recurring=='1')
					 {
						echo '<p class=""><span>'.PACKAGE_CYCLE.':&nbsp;</span><span>'.$package_billing_num ."&nbsp;";
						if($package_billing_per == 'D')
						{
							_e(DAYS_TEXT,DOMAIN);
						}
						elseif($package_billing_per == 'M')
						{
							_e(MONTHS_TEXT,DOMAIN);
						}
						else
						{
							_e(YEAR_TEXT,DOMAIN);
						}
						echo "&nbsp;".$package_billing_cycle."&nbsp;Cycle. </span>";
					 }
					  
					 ?>
                        </label>
				 </div>		
	<?php } 
	} ?>    		 
    		
		</div>        
        
	<?php
	}
	/*
	Name : templ_fetch_category_price
	Desc : calculate pricing as per category selection
	*/
	function templ_fetch_category_price($category_id){
		if(isset($category_id))
			foreach($category_id as $_category_arr)
			{
			$category[] = explode(",",$_category_arr);
			}
		if(isset($category))
			foreach($category as $_category){
				$arr_category[] = $_category[0];
				$arr_category_price[] = $_category[1];
			}
			
		return $cat_price = @array_sum($arr_category_price);	
	}
	
	/*
	Name : templ_get_selected_category_id
	Desc : get selected category ID
	*/
	function templ_get_selected_category_id($category_id){
		if(isset($category_id))
			foreach($category_id as $_category_arr)
			{
				$category[] = explode(",",$_category_arr);
			}
		if(isset($category))
			foreach($category as $_category){
				$arr_category[] = $_category[0];
				$arr_category_price[] = $_category[1];
			}
			
		return $cat_array = $arr_category;	
	}
	/*
	Name : templ_total_selected_cats_price
	Desc : get selected category ID
	*/
	function templ_total_selected_cats_price($category_id){
		global $wpdb;
		$cat_price = $wpdb->get_var("select sum(t.term_price) from $wpdb->terms t ,$wpdb->term_taxonomy tt where t.term_id = tt.term_taxonomy_id and tt.term_taxonomy_id in($category_id)");
		return $cat_price;	
	}
	
	/*
	Name :fetch_package_feature_details
	Description : To display the feature details of the price packages 
	*/
	function fetch_package_feature_details($edit_id='',$png_id='',$all_cat_id){
		/* set feature price when Go back and edit */
		if(isset($edit_id) && $edit_id !=''){
			$price_select =  get_post_meta($edit_id,'package_select',true); /* selected package */
			$is_featured = get_post_meta($price_select,'is_featured',true); // package is featured or not 
			if($is_featured ==1){
				$featured_h = get_post_meta($price_select,'feature_amount',true); //
				$featured_c = get_post_meta($price_select,'feature_cat_amount',true); //
			}
		}elseif(isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] !=''){
			$pkg_id = $_SESSION['custom_fields']['package_select'];
			$is_featured = get_post_meta($pkg_id,'is_featured',true);
			if($is_featured ==1){
				
				$featured_h = get_post_meta($pkg_id,'feature_amount',true); //
				$featured_c = get_post_meta($pkg_id,'feature_cat_amount',true); //
				if(!$featured_h){ $featured_h =0; }
				if(!$featured_c){ $featured_c =0; }
			}	
		}else{
			$featured_h =0;
			$featured_c =0;
		}	?>
			<!-- FETCH FEATURED POST PRICES IN FRONT END -->
            <?php global $post; $post_type = get_post_meta($post->ID,'template_post_type',true); ?>
			<div class="form_row clearfix" id="is_featured" <?php if($is_featured ==1){ }else{ ?>style="display:none; } <?php }?>">
				<label><?php echo sprintf(__('Would you like to make this %s featured?',DOMAIN) ,$post_type);?> </label>
				<div class="feature_label">
				
					<label><input type="checkbox" name="featured_h" id="featured_h" value="<?php echo $featured_h; ?>" onclick="featured_list(this.id)" <?php if(@$_SESSION['custom_fields']['featured_h'] !=""){ echo "checked=checked"; } ?>/><?php _e(FEATURED_H,DOMAIN); ?> <span id="ftrhome"><?php if(isset($featured_h) && $featured_h !=""){ echo "(".fetch_currency_with_position($featured_h).")"; }else{ echo "(".fetch_currency_with_position('0').")"; } ?></span></label>
					
					<label><input type="checkbox" name="featured_c" id="featured_c" value="0" onclick="featured_list(this.id)" <?php if(@$_SESSION['custom_fields']['featured_c'] !=""){ echo "checked=checked"; } ?>/><?php _e(FEATURED_C,DOMAIN); ?><span id="ftrcat"><?php if(isset($featured_c) && $featured_c !=""){ echo "(".fetch_currency_with_position($featured_c).")"; }else{ echo "(".fetch_currency_with_position('0').")"; } ?></span></label>
					
					<input type="hidden" name="featured_type" id="featured_type" value="n"/>
					<span id='process' style='display:none;'><img src="<?php echo get_template_directory_uri()."/images/process.gif"; ?>" alt='Processing..' /></span>
					
				</div>
				<span class="message_note"><?php echo sprintf(__('An additional amount will be charged to make this %s featured.You have the option to feature your $post_type on home page or category page or both.',T_DOMAIN),$post_type);?></span>
				<span id="category_span" class="message_error2"></span>
			</div>
			<!-- END - FETCH FEATURED POST PRICE -->
			
			<!-- FETCH THE TOTAL AMOUNT TO BE PAID IN FRONT END -->
			<div class="form_row clearfix">
				<label><?php _e(TOTAL_TEXT,DOMAIN);?> <span>*</span> </label>
				<div class="form_cat">
				<?php if(isset($category_id)){ $catid = $category_id; }else{ $catid =''; }
					if(isset($cat_array) && $cat_array != "")
					{
						$catid = $cat_array;
					}
					else
					{
						 $catid =''; 
					} 
					if(!isset($total_price))
					{
						$total_price = 0;
					}
				
					/* variables to set category pricing */

					if(isset($_SESSION['custom_fields']) && $_REQUEST['category'] ==''){ /* selection when go back and edit */

						$cat_price = $_SESSION['custom_fields']['all_cat_price'];
						$price_select = $_SESSION['custom_fields']['package_select'];
						$packprice = get_post_meta($_SESSION['custom_fields']['package_select'],'package_amount',true);
						if(isset($_SESSION['custom_fields']['total_price'])){
							$total_price = $_SESSION['custom_fields']['total_price'];
						}
						$featured_type = $_SESSION['custom_fields']['featured_type'];
						
						if($featured_type =='both'){
							$fprice = floatval($_SESSION['custom_fields']['featured_h']) + floatval($_SESSION['custom_fields']['featured_c']) ;
						}elseif($featured_type = 'c'){
							$fprice = floatval(floatval($_SESSION['custom_fields']['featured_c'])) ;
						}elseif($featured_type = 'h'){
							$fprice = floatval($_SESSION['custom_fields']['featured_h']) ;
						}else{
							$fprice =0;
						}
					}elseif(isset($_REQUEST['category'])){
						$cat_price = $this->templ_fetch_category_price($_REQUEST['category']);
						$total_price = $this->templ_fetch_category_price($_REQUEST['category']);
					}elseif(isset($edit_id) && $edit_id !='' && $_REQUEST['category'] ==''){
				
						$cat_price = $this->templ_total_selected_cats_price($all_cat_id);
						$packprice = get_post_meta($price_select,'package_amount',true);/* package amount */
						
						$featured_type = get_post_meta($edit_id,'featured_type',true);
						$featured_h_price = get_post_meta($price_select,'feature_amount',true);
						$featured_c_price = get_post_meta($price_select,'feature_cat_amount',true);
						$total_price = get_post_meta($edit_id,'total_price',true);
						/* featured prices when comes for edit */
						if($featured_type =='both'){
							$fprice = floatval($featured_h_price + $featured_c_price) ;
						}elseif($featured_type = 'c'){
							$fprice = floatval($featured_c_price) ;
						}elseif($featured_type = 'h'){
							$fprice = floatval($featured_h_price) ;
						}else{
							$fprice =0;
						}
					}else{
						$cat_price = 0;
						$price_select = '';
						$packprice = 0;
					}
					$currency = get_option('currency_symbol');
					$position = get_option('currency_pos');
					global  $wpdb;
					
					if($position == '1'){ echo $currency; }else if($position == '2'){ echo $currency.'&nbsp;';}?>
					<span id="cat_price">
					<?php if($catid != "") { $catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt,  $wpdb->terms t where tt.term_taxonomy_id = '".$catid."' and tt.term_id = t.term_id"); if($catprice->term_price !=""){ echo $catprice->term_price; }else{ echo "0"; } }else{ if($cat_price !="") { echo $cat_price; }else{ echo '0'; } } ?></span>
					<?php if($position == '3'){ echo $currency; }else if($position != 1 && $position != 2 && $position !=3){ echo '&nbsp;'.$currency; } ?>					
					+ 					
					<?php if($position == '1'){ echo $currency; }else if($position == '2'){ echo $currency.'&nbsp;'; } ?>
					<span id="pkg_price"><?php if(isset($price_select) && $price_select !=""){ echo $packprice; } else{ echo "0";}?></span>
					<?php if($position == '3'){ echo $currency; }else if($position != 1 && $position != 2 && $position !=3){ echo '&nbsp;'.$currency; } ?>					
					+ 					
					<?php if($position == '1'){ echo $currency; }else if($position == '2'){ echo $currency.'&nbsp;'; } ?>
					<span id="feture_price"><?php if($fprice !=""){ echo $fprice ; }else{ echo "0"; }?></span>
					<?php if($position == '3'){ echo $currency; }else if($position != 1 && $position != 2 && $position !=3){ echo '&nbsp;'.$currency; } ?>				
					= 				
					<?php if($position == '1'){ echo $currency; }else if($position == '2'){ echo $currency.'&nbsp;'; } ?>
					<span id="result_price"><?php if($total_price != ""){ echo $total_price; }else if($catid != ""){  echo $catprice->term_price; }else{ echo "0";} ?></span>
					<?php if($position == '3'){ echo $currency; }else if($position != 1 && $position != 2 && $position !=3){ echo '&nbsp;'.$currency; } ?>
					
					<input type="hidden" name="total_price" id="total_price" value="<?php if($total_price != ""){ echo $total_price; }else if($catid != ""){  echo $catprice->term_price; }else{ echo "0";} ?>"/>
				</div>
				<span class="message_note"> </span>
				<span id="category_span" class="message_error2"></span>
			<!-- END - FETCH TOTAL PRICE -->
			</div>
	<?php
	}
	/* EOF - FETCH featured DATA */
	/*
	Name : templ_get_price_info
	Argument : pkg_id - selected price package id, total price for listing going to submit.
	Desc : return selected price package information.
	*/
	function templ_get_price_info($pkg_id='',$price='')
	{ 
		global $wpdb,$recurring,$billing_num,$billing_per,$billing_cycle;
		if($pkg_id !="")
		{
			$subsql = " and p.ID =\"$pkg_id\"";	
		}
		
		wp_reset_query();
		$post = get_post($pkg_id); 
		
		if($post)
		{
			$info = array();
			$recurring = get_post_meta($post->ID,'recurring',true);
			if($recurring ==1){
			$validity = get_post_meta($post->ID,'billing_num',true);
			$vper = get_post_meta($post->ID,'billing_per',true);
			}else{
			$vper = get_post_meta($post->ID,'validity_per',true);
			$validity = get_post_meta($post->ID,'validity',true);
			}
			$cats = get_post_meta($post->ID,'category',true);
			$is_featured = get_post_meta($post->ID,'is_featured',true);
			
			$billing_num = get_post_meta($post->ID,'billing_num',true);
			$billing_per = get_post_meta($post->ID,'billing_per',true);
			$billing_cycle = get_post_meta($post->ID,'billing_cycle',true);

			if(($validity != "" || $validity != 0))
			{
				if($vper == 'M')
				{
					$tvalidity = $validity*30 ;
				}else if($vper == 'Y'){
					$tvalidity = $validity*365 ;
				}else{
					$tvalidity = $validity ;
				}
			}
			$info['title'] = $post->post_title;
			$info['price'] = get_post_meta($post->ID,'package_amount',true);
			$info['days'] = $tvalidity;
			$info['alive_days'] = $tvalidity;
			$info['cat'] = $cats;
			$info['is_featured'] = $is_featured;
			
			$info['title_desc'] =$post->post_content;
			$info['is_recurring'] =$recurring;
			if($recurring == '1') {
				$info['billing_num'] = $billing_num;
				$info['billing_per'] = $billing_per;
				$info['billing_cycle'] = $billing_cycle;
			}
			$price_info[] = $info;
		}
		return $price_info;
	}
	
	/*
	Name : templ_set_price_info
	Desc : set the price information of listing
	*/
	function templ_set_price_info($last_postid,$pid,$payable_amount,$alive_days,$payment_method,$coupon,$featured_type){
		$monetize_settings = array();
		$monetize_settings['paid_amount'] = $payable_amount;
		if($pid !='' && $alive_days ==""){
			$monetize_settings['alive_days'] = 'Unlimited'; }

		$monetize_settings['alive_days'] = $alive_days;
		$monetize_settings['paymentmethod'] = $payment_method;
		$monetize_settings['coupon_code'] = $coupon;
				$monetize_settings["paid_amount"] = $payable_amount;
		$monetize_settings["coupon_code"] = $coupon;
		if(!$featured_type){
			  $monetize_settings['featured_type'] = 'none';
			  $monetize_settings['featured_c'] = 'n';
			  $monetize_settings['featured_h'] = 'n';
		}
		if($featured_type == 'c'){
			 $monetize_settings['featured_h'] = 'n';
			 $monetize_settings['featured_c'] = 'c';
		}
		if($featured_type == 'h')
		 {
			 $monetize_settings['featured_c'] = 'n';
			 $monetize_settings['featured_h'] = 'h';
		 }
 		if($featured_type == 'both')
		 {
			 $monetize_settings['featured_c'] = 'c';
			 $monetize_settings['featured_h'] = 'h';
		 }
 		if($featured_type == 'none')
		 {
			 $monetize_settings['featured_c'] = 'n';
			 $monetize_settings['featured_h'] = 'n';
		 }

		foreach($monetize_settings as $key=>$val)
		{
				update_post_meta($last_postid, $key, $val);
		}
	
	}
	
	/*
	Name : templ_total_price
	Args : taxonomy name
	Desc : return the total price of selected categories
	*/
	function templ_total_price($taxonomy){
		$args = array('hierarchical' => true ,'hide_empty' => 0, 'orderby' => 'term_group');
		$terms = get_terms($taxonomy, $args);
		$total_price=0;
		foreach($terms as $term){
				$total_price += $term->term_price;
			
		}
		return $total_price;
	}
	/*
	Name : templ_get_featured_type
	Args : $cur_user_id = current user id
	Desc : return the user last post featured type
	*/	
	function templ_get_featured_type($cur_user_id , $post_type){
		global $wpdb;
		//package_select - package id of last post in database
		
		$user_last_post = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
		$user_last_post_id = @$user_last_post->ID; /* last inserted post */		
		$featured_type=get_post_meta($user_last_post_id,'featured_type',true);
		return $featured_type;
	}
	
	
	/*
	Name : templ_get_packagetype
	Args : $cur_user_id = current user id
	Desc : return the package type of current user
	*/	
	function templ_get_packagetype($cur_user_id , $post_type){
		global $wpdb;
		//package_select - package id of last post in database
		
		$user_last_post = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
		$user_last_post_id = @$user_last_post->ID; /* last inserted post */
		
		$selected_pkg = get_post_meta($user_last_post_id,'package_select',true);/* selected package id to fetch package type*/
		
		$package_type = get_post_meta($selected_pkg,'package_type',true); /* 1- pay per posy, 2- pay per subscription */
		if(!$package_type){ $package_type =1; }
		return $package_type;
	}	
	
	/*
	Name : templ_get_packaget_post_status
	Args : $cur_user_id = current user id
	Desc : return the post status of current user
	*/	
	function templ_get_packaget_post_status($cur_user_id , $post_type){
		global $wpdb;
		//package_select - package id of last post in database
		
		$user_last_post = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
		if($user_last_post){ echo "asdsad";
		$post_status = $user_last_post->post_status;
		}else{
		$post_status = fetch_posts_default_status();
		}
		return $post_status;
	}
	/*
	Name : templ_days_for_packagetype
	Args : $cur_user_id = current user id
	Desc : fetch the details of package type user selected when come to submit the listing
	*/
	function templ_days_for_packagetype($cur_user_id , $post_type){
		global $wpdb;		
		$package_type = $this->templ_get_packagetype($cur_user_id , $post_type); /* 1- pay per posy, 2- pay per subscription */
		if($package_type == 2){
			if($cur_user_id){ 
	
			$adays = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
			if($adays->ID){ 
			$alive_day = get_post_meta($adays->ID,'alive_days',true);
			$publish_date =  strtotime($adays->post_date);
			$publish_date =  date('Y-m-d',$publish_date);
			$curdate = date('Y-m-d');
			
			$days = templ_number_of_days($publish_date,$curdate);
			if(($days == $alive_days && $days < $alive_days) || $days ==0){ $alive_days = $alive_day - $days; }else{ $alive_days =0; }
			return $alive_days;
			}
		}}
	}
	
	/*
	Name : templ_days_for_user_packagetype
	Args : $cur_user_id = current user id
	Desc : fetch the details of package type user selected when come to submit the listing
	*/
	function templ_days_for_user_packagetype($cur_user_id , $post_type){
		global $wpdb;		
		$package_id = get_user_meta($cur_user_id ,'package_select',true); 
		$package_type = get_post_meta($package_id,'package_type',true);/* 1- pay per posy, 2- pay per subscription */		
		if($package_type == 2){
			if($cur_user_id){ 	
				$adays = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
				if($adays->ID){ 
					$alive_day = get_post_meta($adays->ID,'alive_days',true);
					$publish_date =  strtotime($adays->post_date);
					$publish_date =  date('Y-m-d',$publish_date);
					$curdate = date('Y-m-d');
					
					$days = templ_number_of_days($publish_date,$curdate);
					if(($days == $alive_days && $days < $alive_days) || $days ==0){ $alive_days = $alive_day - $days; }else{ $alive_days =0; }
					return $alive_days;
				}
			}
		}
	}
	/*
	Name : is_user_have_alivedays
	Args : $cur_user_id = current user id
	Desc : fetch the details of package type user selected when come to submit the listing
	*/
	function is_user_have_alivedays($cur_user_id , $post_type){
		global $wpdb;
		
		$package_type = $this->templ_get_packagetype($cur_user_id , $post_type); /* 1- pay per posy, 2- pay per subscription */
		if($package_type == 2){
			if($cur_user_id){ 

			$adays = $wpdb->get_row("select * from $wpdb->posts p where p.post_type='".$post_type."' and p.post_author = '".$cur_user_id."' order by p.ID DESC LIMIT 0,1");
			if($adays->ID){
			$alive_day = get_post_meta($adays->ID,'alive_days',true);
			$publish_date =  strtotime($adays->post_date);
			$publish_date =  date('Y-m-d',$publish_date);
			$curdate = date('Y-m-d');
			
			$days = templ_number_of_days($publish_date,$curdate);
			//echo $alive_day."=".$days;
				if($alive_day > $days && $days == 0){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
} /* class end */
}
if(!isset($monetization))
{
	$monetization = new monetization();
}

/*
NAme : recent_transactions_dashboard_widgets
Desc : admin dashboard transaction widgte setup
*/

function recent_transactions_dashboard_widgets() {
	global $current_user;
	if(is_super_admin($current_user->ID)) {

		wp_add_dashboard_widget('recent_transactions_dashboard_widgets', RECENT_TRANSACTION_TEXT, 'recent_transactions_dashboard_widget');
		
		global $wp_meta_boxes;
	
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
		@$example_widget_backup = array('recent_transactions_dashboard_widgets' => $normal_dashboard['recent_transactions_dashboard_widgets']);
		unset($normal_dashboard['recent_transactions_dashboard_widgets']);
	
		$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
	
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	}
}
add_action('wp_dashboard_setup', 'recent_transactions_dashboard_widgets' );

/*
NAme : recent_transactions_dashboard_widget
Desc : admin dashboard transaction widgte display
*/
function recent_transactions_dashboard_widget(){?>
<script type="text/javascript">
function change_poststatus(str)
{ 
	if (str=="")
	  {
	  document.getElementById("p_status_"+str).innerHTML="";
	  return;
	  }
	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }else{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("p_status_"+str).innerHTML=xmlhttp.responseText;
		}
	  }
	  url = "<?php echo plugin_dir_url( __FILE__ ); ?>ajax_update_status.php?post_id="+str;
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
}
</script>
<?php
	$tmpdata = get_option('templatic_settings');
	if(isset($tmpdata['trans_post_type_value']) && count($tmpdata['trans_post_type_value']) > 0)
	{
		$post_args = array('post_status'=>'draft,publish','post_type' => $tmpdata['trans_post_type_value'],'order'=>'DESC','numberposts'=>-1);
		$recent_posts = get_posts( $post_args );

	if($recent_posts){
		echo '<table class="widefat"  width="100%" >
			<thead>	';
		$th='	<tr>
				<th valign="top" align="left">'.__('ID',DOMAIN).'</th>
				<th valign="top" align="left">'.__('Title',DOMAIN).'</th>
				<th valign="top" align="left">'.__('Paid amount',DOMAIN).'</th>
				<th valign="top" align="left">'.__('Alive days',DOMAIN).'</th>
				<th valign="top" align="left">'.__('Status',DOMAIN).'</th>
				<th valign="top" align="left">'.__('Post type',DOMAIN).'</th>';
		$th .=	'</tr>';   
		echo $th;
		foreach($recent_posts as $posts) {
		$color_taxonomy = 'trans_post_type_colour_'.$posts->post_type;
			if(isset($tmpdata[$color_taxonomy]) && $tmpdata[$color_taxonomy]!= '') { $color_taxonomy_value = $tmpdata[$color_taxonomy]; } 
			echo '<tr>
				<td valign="top" align="left">'.$posts->ID.'</td>
				<td valign="top" align="left"><a href="'.site_url().'/wp-admin/post.php?post='.$posts->ID.'&action=edit">'.$posts->post_title.'</a></td>';
				
			echo '<td valign="top" align="left">';
				if(get_post_meta($posts->ID,'total_price',true)) { echo fetch_currency_with_position(get_post_meta($posts->ID,'total_price',true));} else { echo fetch_currency_with_position('0'); } echo '</td>
				<td valign="top" align="left">';
				if(get_post_meta($posts->ID,'alive_days',true)) { echo get_post_meta($posts->ID,'alive_days',true);} else { echo '0';} echo '</td>';
			if(get_post_status($posts->ID) =='draft'){
			echo '<td valign="top" align="left" id="p_status_'.$posts->ID.'"><a href="javascript:void(0);" onclick="change_poststatus('.$posts->ID.')"  style="color:#E66F00">Pending</a></td>';
			}else if(get_post_status($posts->ID) =='publish'){
			echo '<td valign="top" align="left" style="color:green" id="p_status_'.$posts->ID.'">'.APPROVED_TEXT.'</td>';
            }
			echo '<td valign="top" style="color:'.$color_taxonomy_value.'" align="left">'.$posts->post_type.'</td>';
			echo '</tr>';
			
			} 
		echo '</thead>	</table>';	
		} else {
			_e('No recent transaction available.',DOMAIN);
		}
	}
	else {
		_e('No transaction type selected from transaction settings.',DOMAIN);
	}
}

/* DELETE THE PACKAGE DATA */
if( (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && (isset($_REQUEST['package_id']) && $_REQUEST['package_id'] != '')))
{
	global $wpdb,$post;
	$id = $_REQUEST['package_id'];
	/* DELETING THE PACKAGE ON CLICK OF DELETE BUTTON OF DASHBOARD METABOX */
	delete_post_meta($id, 'package_type');
	delete_post_meta($id, 'package_post_type');
	delete_post_meta($id, 'category');
	delete_post_meta($id, 'show_package');
	delete_post_meta($id, 'package_amount');
	delete_post_meta($id, 'validity');
	delete_post_meta($id, 'validity_per');
	delete_post_meta($id, 'package_status');
	delete_post_meta($id, 'recurring');
	delete_post_meta($id, 'billing_num');
	delete_post_meta($id, 'billing_per');
	delete_post_meta($id, 'billing_cycle');
	delete_post_meta($id, 'is_featured');
	delete_post_meta($id, 'feature_amount');
	delete_post_meta($id, 'feature_cat_amount');
	wp_delete_post($id);
	$url = site_url().'/wp-admin/admin.php?page=monetization';
	echo '<form action="'.$url.'" method="get" id="frm_package" name="frm_package">
	<input type="hidden" value="monetization" name="page"><input type="hidden" value="delete" name="package_msg">
	<input type="hidden" value="packages" name="tab">
	</form>
	<script>document.frm_package.submit();</script>
	';exit;	
}

/* 
name : tmpl_get_transaction_status
description : FUNCTION TO FETCH TRANSACTIONS */
function tmpl_get_transaction_status($tid,$pid){
	global $wpdb,$transection_db_table_name;
	$trans_status = $wpdb->get_var("select status from $transection_db_table_name where trans_id = '".$tid."'");
	$result = '';
	if($trans_status == 0){
		$result = '<a style="color:#E66F00; font-weight:normal;"  href="javascript:void(0);">Pending</a>';
	}else if($trans_status == 1){
		$result = '<span style="color:green; font-weight:normal;">Approved</span>';
	}
	else if($trans_status == 2){
		$result = '<span style="color:green; font-weight:normal;">Cancel</span>';
	}
	return $result;;	
}
//END OF FUNCTION

/*
name : fetch_payment_description
description : fetch payment option
*/
function fetch_payment_description($pid)
{
	global $wpdb,$transection_db_table_name;
			$transsql_select = "select * from $transection_db_table_name where post_id = ". $pid;
			$transsql_result = $wpdb->get_row($transsql_select);
			
			$payment_options = get_option('payment_method_'.$transsql_result->payment_method);
			$payment_method_name = $payment_options['name'];
			if($transsql_result->status)
			  {
				$status = "Approved";
			  }
			else
			  {
				$status = "Pending";
			  }
			echo "<li><p>Paid amount: </p> <p> ".fetch_currency_with_position(number_format(@$transsql_result->payable_amt,2))."</p></li>";
			
			if($transsql_result->payment_method!="")
				echo "<li><p>Payment Method: </p> <p> ".@$payment_method_name."</p></li>";
			echo "<li><p>Status: </p> <p> ".$status."</p></li>";
}

/*
name : insert_transaction_detail
description : insert transaction detail in transaction table.
*/
function insert_transaction_detail($paymentmethod='',$last_postid)
{
		/* Transaction Reoprt */
		global $wpdb,$payable_amount;
		if($last_postid !=""){
		$post_author  = $wpdb->get_row("select * from $wpdb->posts where ID = '".$last_postid."'") ;
		$post_title  = $post_author->post_title ;
		$post_author  = $post_author->post_author ;
		$uinfo = get_userdata($post_author);
		$user_fname = $uinfo->display_name;
		$user_email = $uinfo->user_email;
		$user_billing_name = $uinfo->display_name;
		$billing_Address = '';
		global $transection_db_table_name;
		$transaction_insert = 'INSERT INTO '.$transection_db_table_name.' set 
		post_id="'.$last_postid.'",
		user_id = "'.$post_author.'",
		post_title ="'.$post_title.'",
		payment_method="'.$paymentmethod.'",
		payable_amt='.$payable_amount.',
		payment_date="'.date("Y-m-d H:i:s").'",
		paypal_transection_id="",
		status="0",
		user_name="'.$user_fname.'",
		pay_email="'.$user_email.'",
		billing_name="'.$user_billing_name.'",
		billing_add="'.$billing_Address.'"';
		}
		$wpdb->query($transaction_insert);
		return mysql_insert_id();
		/* End Transaction Report */
}

/*
name : get_payment_method
description : fetch payment mehotd name.
*/
function get_payment_method($method)
{
	global $wpdb;
	$paymentsql = "select * from $wpdb->options where option_name like 'payment_method_$method'";
	$paymentinfo = $wpdb->get_results($paymentsql);
	if($paymentinfo)
	{
		foreach($paymentinfo as $paymentinfoObj)
		{
			$paymentInfo = unserialize($paymentinfoObj->option_value);
			return 'Pay with '.$paymentInfo['name'];
		}
	}
}
/*
name : get_order_detailinfo_tableformat
description : fetch order information as a table format.
*/
function get_order_detailinfo_tableformat($orderId,$isshow_paydetail=0)
{
	global $Cart,$General,$wpdb,$prd_db_table_name,$transection_db_table_name;
	$ordersql = "select * from $transection_db_table_name where trans_id=\"$orderId\"";
	$orderinfo = $wpdb->get_results($ordersql);
	$orderinfo = $orderinfo[0];
	$post_id = $orderinfo->post_id;
	$package_select_id = get_post_meta($post_id,'package_select',true);
	$package_select_name = get_the_title($package_select_id);
	$coupon_code = get_post_meta($post_id,'coupon_code',true);	

	if($isshow_paydetail)
	{
		//$message = '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/style.css" media="screen" />';
		$message .= '<style>.address_info {width:400px;}</style>';
	}
	//$billing_address = $orderinfo->billing_add;
	//$billing_address = str_replace(',',',<br />',$billing_address);
	
	
	
	//$shipping_address = $orderinfo->shipping_add;
	//$shipping_address = str_replace(',',',<br />',$shipping_address);

	$message .='
		<table class="widefat post" width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="2" align="left">		
					<div class="order_info">
						<p> <span class="span"> '. __('Order Number').' </span> : <strong>'.$orderinfo->trans_id.'  </strong>  <br />
							<span class="span"> '. __('Order Date').' </span> : '.date(get_option('date_format').' '.get_option('time_format'),strtotime($orderinfo->payment_date)).' </p>
							<p><span class="span">'. __('Order Status') .'</span>  : <strong>'. tmpl_get_transaction_status($orderinfo->trans_id,$orderinfo->post_id).'</strong> </p>
					</div> <!--order_info -->
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2">
					<div class="checkout_address" >
						<div class="address_info address_info2  fl">
							<h3>'.__('User Information').'</h3>
							<div class="address_row"> <b>'.$orderinfo->billing_name.' </b></div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2">
					<div class="checkout_address" >
						<div class="address_info address_info2 fr">
							<h3> '. __('Payment Information').'  </h3>									
							<div class="address_row">'.get_payment_method($orderinfo->payment_method).'  </div>
						</div>
					</div><!-- checkout Address -->
				 </td>
			</tr>';
			if($package_select_name)
			{
				$message .='<tr>
					<td align="left" valign="top" colspan="2">
						<div class="checkout_address" >
							<div class="address_info address_info2 fr">
								<h3> '. __('Price Package').'  </h3>									
								<div class="address_row">'.$package_select_name.'  </div>
							</div>
						</div><!-- checkout Address -->
					 </td>
				</tr>';
			}
			if($coupon_code)
			{
			$message .='<tr>
					<td align="left" valign="top" colspan="2">
						<div class="checkout_address" >
							<div class="address_info address_info2 fr">
								<h3> '. __('Coupon Code').'  </h3>									
								<div class="address_row">'.$coupon_code.'  </div>
							</div>
						</div><!-- checkout Address -->
					 </td>
				</tr>';
			}
		$message .='</table><br /><br />
	 
			 
			  
			  <h3>  '. __('Products Information').' </h3>
			 
			  <table width="100%" class="table widefat post" >
			  <tr>
			  <td width="5%" align="left" class="title" ><strong> '. __('Image').'</strong></td>
			  <td width="45%" align="left" class="title" ><strong> '. __('Product Name').'</strong></td>
			  <td width="23%" align="left" class="title" ><strong> '. __('Price Total').'</strong></td>
			  </tr>';
			 
					$product_image_arr = bdw_get_images_plugin($orderinfo->post_id,'thumb');
					$product_image = $product_image_arr[0];

					$message .= '<tr>
							<td class="row1"><a href="'.get_permalink($orderinfo->post_id).'"><img src="'.$product_image.'" width=60 height=60 /></a></td>
							  <td class="row1" ><strong><a href="'.get_permalink($orderinfo->post_id).'">'.$orderinfo->post_title.'</a>
							  <td class="row1 tprice"  align="left">'.fetch_currency_with_position(number_format($orderinfo->payable_amt,2)).'</td>
							  </tr>';
			 
	$message .='</table>
			  ';
	return $message;
}
?>