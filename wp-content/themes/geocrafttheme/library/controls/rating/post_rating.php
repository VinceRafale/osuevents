<?php
/**
 * Define max post rating
 */
define('POSTRATINGS_MAX', 5);
$rating_path = LIBRARYURL . 'controls/rating/';
$rating_star_on = $rating_path . 'images/rating_star_on.png';
$rating_star_off = $rating_path . 'images/rating_star_off.png';
$rating_table_name = $wpdb->prefix . 'post_ratings';
/**
 * Define variable to further use 
 * Globally
 */
global $post, $rating_path, $rating_star_on, $rating_star_off, $rating_table_name, $wpdb;
/**
 * Creating table for post rating
 * 
 */
if ($wpdb->get_var("SHOW TABLES LIKE \"$rating_table_name\"") != $rating_table_name) {
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

/**
 * Function Name: geocraft_save_comment_rating
 * Description: Inserts rating values
 * @global type $wpdb
 * @global string $rating_table_name
 * @global type $post
 * @global type $user_ID
 * @global type $current_user
 * @param type $comment_id 
 */
function geocraft_save_comment_rating($comment_id = 0) {
    global $wpdb, $rating_table_name, $post, $user_ID, $current_user;
    $rate_user = $user_ID;
    $rate_userid = $user_ID;
    $post_id = $_REQUEST['post_id'];
    $post_title = $post->post_title;
    $rating_var = "post_" . $post_id . "_rating";
    $rating_val = $_REQUEST["$rating_var"];
    if (!$rating_val) {
        $rating_val = 0;
    }
    $rating_ip = getenv("REMOTE_ADDR");
    if (!$rate_userid) {
        $rate_userid = $current_user->ID;
    }   
    $wpdb->insert(
        $rating_table_name, array(
        'rating_postid' => $post_id,
        'rating_rating' => $rating_val,
        'comment_id' => $comment_id,
        'rating_ip' => $rating_ip,
        'rating_userid' => $rate_userid
        )
    );
}

add_action('wp_insert_comment', 'geocraft_save_comment_rating');
/**
 * Function Name: geocraft_del_comment_rating
 * Description: Delete the rating with comment
 * @global type $wpdb
 * @global string $rating_table_name
 * @global type $post
 * @global type $user_ID
 * @param type $comment_id 
 */
function geocraft_del_comment_rating($comment_id = 0)
{
	global $wpdb,$rating_table_name, $post, $user_ID;
	if($comment_id)
	{
		$wpdb->query("delete from $rating_table_name where comment_id=\"$comment_id\"");
	}	
}
add_action( 'wp_delete_comment', 'geocraft_del_comment_rating' );
/**
 * Function Name: geocraft_draw_rating_star
 * Description: Displaying users ratings
 * @global type $rating_star_on
 * @global type $rating_star_off
 * @param type $avg_rating
 * @return string 
 */
function geocraft_display_rating_star($avg_rating) {
        global $rating_star_on, $rating_star_off;
        $rtn_str = '';
        for ($i = 0; $i < $avg_rating; $i++) {
            $rtn_str .= '<li><img src="' . $rating_star_on . '" alt="" /></li>';
        }
        for ($i = $avg_rating; $i < POSTRATINGS_MAX; $i++) {
            $rtn_str .= '<li><img src="' . $rating_star_off . '" alt="" /></li>';
        }
    return $rtn_str;
}
/**
 * Function Name: geocraft_rating_js
 * Description: Create dynamic rating star
 * @global type $rating_star_on
 * @global string $rating_star_off 
 */
function geocraft_rating_js() {
    global $rating_star_on, $rating_star_off;
    ?>
    <script type="text/javascript">
        var rating_star_on = '<?php echo $rating_star_on; ?>';
        var rating_star_off = '<?php echo $rating_star_off; ?>';
        var postratings_max = '<?php echo POSTRATINGS_MAX; ?>';
        post_rating_max = postratings_max;
        function current_rating_star_on(post_id, rating, rating_text) {
    	
            for(i=1;i<=post_rating_max;i++)
            {
                document.getElementById('rating_' + post_id + '_' + i).src = rating_star_off;
            }
            for(i=1;i<=rating;i++)
            {
                document.getElementById('rating_' + post_id + '_' + i).src = rating_star_on;
            }
            document.getElementById('ratings_' + post_id + '_text').innerHTML = rating_text;
            document.getElementById('post_' + post_id + '_rating').value = rating;
        }

        function current_rating_star_off(post_id, rating) {
        }
    </script>
    <?php
}
/**
 * Function Name: geocraft_get_rating
 * Description: Getting users ratings
 * @global type $post
 * @global string $rating_path
 * @global string $rating_star_on
 * @global string $rating_star_off
 * @global string $rating_table_name 
 */
function geocraft_get_rating() {
    global $post, $rating_path, $rating_star_on, $rating_star_off, $rating_table_name;
    geocraft_rating_js();
	for($i=1;$i<=POSTRATINGS_MAX;$i++)
	{
		if($i==1){$rating_text = $i.' Rating';}else{$rating_text = $i.__(' Rating');}
		
		echo '<img src="'.$rating_image_off.'" class="rating_img" onmouseover="current_rating_star_on(\''.$post->ID.'\',\''.$i.'\',\''.$rating_text.'\');" onmousedown="current_rating_star_off(\''.$post->ID.'\',\''.$i.'\');" id="rating_'.$post->ID.'_'.$i.'"  alt="" />';							
	}
	echo '<span class="cmt_rating_label" id="ratings_'.$post->ID.'_text" style="display:inline-table; position:relative; top:8px; padding-left:10px; " ></span>';
	echo '<input type="hidden" name="post_id" id="post_id" value="'.$post->ID.'" />';
	echo '<input type="hidden" name="post_'.$post->ID.'_rating" id="post_'.$post->ID.'_rating" value="" />';
 	echo '<script type="text/javascript">current_rating_star_on(\''.$post->ID.'\',0,\'0 '.__('Rating').'\');</script>';
}
/**
 * Function Name: geocraft_get_post_average_rating
 * Description: Returns average of total rating
 * @global type $wpdb
 * @global string $rating_table_name
 * @param type $pid
 * @return type 
 */
function geocraft_get_post_average_rating($pid)
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
/**
 * Function Name: geocraft_get_post_rating_star
 * Description: Display rating in post
 * @param type $pid
 * @return type 
 */
function geocraft_get_post_rating_star($pid='')
{
	$rtn_str = '';
	$avg_rating = geocraft_get_post_average_rating($pid);
	$rtn_str = geocraft_display_rating_star($avg_rating);
	return $rtn_str;
}
/**
 * Function Name: geocraft_is_user_can_add_comment
 * Description: Allows user to add a comment
 * @global string $rating_table_name
 * @global type $wpdb
 * @param type $pid
 * @return type 
 */
function geocraft_is_user_can_add_comment($pid)
{
	global $rating_table_name, $wpdb;
	$rating_ip = getenv("REMOTE_ADDR");
	$avg_rating = $wpdb->get_var("select rating_id from $rating_table_name where rating_postid=\"$pid\" and rating_ip=\"$rating_ip\"");	
	return $avg_rating;

}
?>