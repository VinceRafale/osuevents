<?php
define('POSTRATINGS_MAX',5);
$rating_image_on = plugin_dir_url( __FILE__ ).'images/rating_on.png';
$rating_image_off = plugin_dir_url( __FILE__ ).'images/rating_off.png';
$rating_table_name = $wpdb->prefix.'ratings';
global $post,$rating_image_on,$rating_image_off,$rating_table_name;
add_action('wp_footer', 'footer_rating_off');
function footer_rating_off()
{
	if(get_option('ptthemes_disable_rating') == 'Disable')
	{
		echo '<style type="text/css">#content .category_list_view li .content .rating{border-bottom:none; padding:0;}
		#sidebar .company_info2 p{padding:0; border-bottom:none;}
		#sidebar .company_info2 p span.i_rating{display:none;}
		</style>';
	}
}

global $wpdb, $rating_table_name;
if($wpdb->get_var("SHOW TABLES LIKE \"$rating_table_name\"") != $rating_table_name) {
$wpdb->query("CREATE TABLE IF NOT EXISTS $rating_table_name (
  rating_id int(11) NOT NULL AUTO_INCREMENT,
  rating_postid int(11) NOT NULL,
  rating_posttitle text NOT NULL,
  rating_rating int(2) NOT NULL,
  rating_timestamp varchar(15) NOT NULL,
  rating_ip varchar(40) NOT NULL,
  rating_host varchar(200) NOT NULL,
  rating_username varchar(50) NOT NULL,
  rating_userid int(10) NOT NULL DEFAULT '0',
  comment_id int(11) NOT NULL,
  PRIMARY KEY (rating_id)
) ENGINE=MyISAM");
}
for($i=1;$i<=POSTRATINGS_MAX;$i++)
{
	$postratings_ratingsvalue[] = $i;
}

function save_comment_rating( $comment_id = 0) {
	global $wpdb,$rating_table_name, $post, $user_ID, $current_user;
	$rate_user = $user_ID;
	$rate_userid = $user_ID;
	$post_id = $_REQUEST['post_id'];
	$post_title = $post->post_title;
	$rating_var = "post_".$post_id."_rating";
	$rating_val = $_REQUEST["$rating_var"];
	if(!$rating_val){$rating_val=0;}
	$rating_ip = getenv("REMOTE_ADDR");
	if(!$rate_userid){
	$rate_userid = $current_user->ID;
	}
	$wpdb->query("INSERT INTO $rating_table_name (rating_postid,rating_rating,comment_id,rating_ip,rating_userid) VALUES ( \"$post_id\", \"$rating_val\",\"$comment_id\",\"$rating_ip\",\"$rate_userid \")");
}

add_action( 'wp_insert_comment', 'save_comment_rating' );

function delete_comment_rating($comment_id = 0)
{
	global $wpdb,$rating_table_name, $post, $user_ID;
	if($comment_id)
	{
		$wpdb->query("delete from $rating_table_name where comment_id=\"$comment_id\"");
	}
	
}
add_action( 'wp_delete_comment', 'delete_comment_rating' );

function get_post_average_rating($pid)
{
	global $wpdb,$rating_table_name;
	$avg_rating = 0;
	if($pid)
	{
		$comments = $wpdb->get_var("select group_concat(comment_ID) from $wpdb->comments where comment_post_ID=\"$pid\" and comment_approved=1");
		if($comments)
		{
			$avg_rating = $wpdb->get_var("select avg(rating_rating) from $rating_table_name where comment_id in ($comments)");
		}
		$avg_rating = ceil($avg_rating);
	}
	return $avg_rating;
}

function draw_rating_star_plugin($avg_rating)
{
	if(get_option('ptthemes_disable_rating') == 'Disable')
	{
	}else
	{
		global $rating_image_on,$rating_image_off;
		$rtn_str = '';
		for($i=0;$i<$avg_rating;$i++)
		{
			$rtn_str .= '<img src="'.$rating_image_on.'" alt="" />';	
		}
		for($i=$avg_rating;$i<POSTRATINGS_MAX;$i++)
		{
			$rtn_str .= '<img src="'.$rating_image_off.'" alt="" />';	
		}
	}
	return $rtn_str;
}
function get_post_rating_star($pid='')
{
	$rtn_str = '';
	$avg_rating = get_post_average_rating($pid);
	$rtn_str =draw_rating_star($avg_rating);
	return $rtn_str;
}
function get_comment_rating_star($cid='')
{
	global $rating_table_name, $wpdb;
	$rtn_str = '';
	$avg_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$cid\"");
	$avg_rating = ceil($avg_rating);
	$rtn_str =draw_rating_star($avg_rating);
	return $rtn_str;
}

function is_user_can_add_comment($pid)
{

	global $rating_table_name, $wpdb;
	$rating_ip = getenv("REMOTE_ADDR");
	$avg_rating = $wpdb->get_var("select rating_id from $rating_table_name where rating_postid=\"$pid\" and rating_ip=\"$rating_ip\"");
	
	if(get_option('ptthemes_disable_rating_limit') == 'yes')
	{
		return '';	
	}
	return $avg_rating;

}
//REVIEW RATING SHORTING -- filters are from library/functions/listing_filters.php file.

function ratings_in_comments () {
	$tmpdata = get_option('templatic_settings');
	if($tmpdata['templatin_rating']=='yes'):?>
    <div>
        <span class="rating_text"><?php _e(RATING_MSG,DOMAIN);?> </span>
        <p class="commpadd"><span class="comments_rating"> <?php require_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-ratings/get_rating.php');?> </span> </p>
    </div>    
	<?php endif;
}

/************************************
//FUNCTION NAME : commentslist
//ARGUMENTS :comment data, arguments,depth level for comments reply
//RETURNS : Comment listing format
***************************************/
function ratings_list($comment) {
	global $wpdb,$post,$rating_table_name;
	?>
   <div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?> >
    <div class="comment-text">
        <span class="single_rating"> 
			<?php
                $post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment\"");
                echo draw_rating_star_plugin($post_rating);
            ?>
      	</span> 
      	 <?php if ($comment->comment_approved == '0') : ?>
        	 <div>
	        	<?php _e('Your comment is awaiting moderation.',DOMAIN) ?>
         	</div>   
    	 <?php endif; ?>
 	  <?php //edit_comment_link(__('+ Edit'),'  ','') ?>
    </div>
  </div>
<?php
}

function display_rating_star($text) {
$text .= '<div class="comment_list">';
   $text .= '<ol>';
   $text .=  ratings_list(get_comment_ID());
   $text .= '</ol>';
   $text .= '</div>';
   return $text;
}

?>