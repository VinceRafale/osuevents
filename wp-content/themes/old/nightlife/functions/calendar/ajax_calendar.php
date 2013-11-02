<?php
/* Calendar code file will call through ajax using calendar.php */
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
global $post,$wpdb;
function get_calendar_month_name($number){
	
    $month = date("M", mktime(0, 0, 0, $number, 10));
	return  $month;
}
/* display calendar fetching all event */
$monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	if (!isset($_REQUEST["mnth"])) $_REQUEST["mnth"] = date("n");
	if (!isset($_REQUEST["yr"])) $_REQUEST["yr"] = date("Y");
	
	$cMonth = $_REQUEST["mnth"];
	$cYear = $_REQUEST["yr"];
	$prev_year = $cYear;
	$next_year = $cYear;
	$prev_month = $cMonth-1;
	$next_month = $cMonth+1;
	
	if ($prev_month == 0 ) {
		$prev_month = 12;
		$prev_year = $cYear - 1;
	}
	if ($next_month == 13 ) {
		$next_month = 1;
		$next_year = $cYear + 1;
	}
	$mainlink = $_SERVER['REQUEST_URI'];
	if(strstr($_SERVER['REQUEST_URI'],'?mnth') && strstr($_SERVER['REQUEST_URI'],'&yr'))
	{
		$replacestr = "?mnth=".$_REQUEST['mnth'].'&yr='.$_REQUEST['yr'];
		$mainlink = str_replace($replacestr,'',$mainlink);
	}elseif(strstr($_SERVER['REQUEST_URI'],'&mnth') && strstr($_SERVER['REQUEST_URI'],'&yr'))
	{
		$replacestr = "&mnth=".$_REQUEST['mnth'].'&yr='.$_REQUEST['yr'];
		$mainlink = str_replace($replacestr,'',$mainlink);
	}
	if(strstr($_SERVER['REQUEST_URI'],'?') && (!strstr($_SERVER['REQUEST_URI'],'?mnth')))
	{
		$pre_link = $mainlink."&mnth=". $prev_month . "&yr=" . $prev_year."#event_cal";
		$next_link = $mainlink."&mnth=". $next_month . "&yr=" . $next_year."#event_cal";
	}else
	{
		$pre_link = $mainlink."?mnth=". $prev_month . "&yr=" . $prev_year."#event_cal";	
		$next_link = $mainlink."?mnth=". $next_month . "&yr=" . $next_year."#event_cal";
	}
?>
<table id="wp-calendar" width="100%" class="calendar">
	
	<caption><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></caption>
		     
	<tr>
	<td style="padding:0px;">
	<table width="100%" border="0" cellpadding="2" cellspacing="2"  class="calendar_widget" style="padding:0px; margin:0px; border:none;">
	
	<thead>
		<th title="<?php _e('Monday','supreme'); ?>" class="days" >Mon</th>
		<th title="<?php _e('Tuesday','supreme'); ?>" class="days" >Tues</th>
		<th title="<?php _e('Wednesday','supreme'); ?>" class="days" >Wed</th>
		<th title="<?php _e('Thursday','supreme'); ?>" class="days" >Thur</th>
		<th title="<?php _e('Friday','supreme'); ?>" class="days" >Fri</th>
		<th title="<?php _e('Saturday','supreme'); ?>" class="days" >Sat</th>
		<th  title="<?php _e('Sunday','supreme'); ?>" class="days" >Sun</th>
	</thead> 
	<?php
	$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
	$maxday = date("t",$timestamp);
	$thismonth = getdate ($timestamp);
	$startday = $thismonth['wday'];
			
	if(@$_GET['m'])
	{
		$m = $_GET['m'];	
		$py=substr($m,0,4);
		$pm=substr($m,4,2);
		$pd=substr($m,6,2);
		$monthstdate = "$cYear-$cMonth-01";
		$monthenddate = "$cYear-$cMonth-$maxday";
		
	} ?>
	<tbody>
	<?php
	global $wpdb;
	for ($i=1; $i<($maxday+$startday); $i++) {
		if(($i % 7) == 1 ) echo "<tr>\n";
		if($i < $startday){
			echo "<td class='date_n'></td>\n";
		}
		else 
		{
			$cal_date = $i - $startday + 1;
			$calday = $cal_date;
			if(strlen($cal_date)==1)
			{
				$calday="0".$cal_date;
			}
			$the_cal_date = $cal_date;
			$cMonth_date = $cMonth;
			
			
			if(strlen($the_cal_date)==1){$the_cal_date = '0'.$the_cal_date;}
			if(strlen($cMonth_date)==1){$cMonth_date = '0'.$cMonth_date;}
			global $post,$wpdb;
			$urlddate = "$cYear$cMonth_date$calday";
			$thelink = get_option('home')."/?s=Calender-Event&amp;m=$urlddate";
			
			$todaydate = "$cYear-$cMonth_date-$the_cal_date";
			$date_num=date('N',strtotime($todaydate))."<br>";
			/* Set the style left as per par calendar */
			$style='';
			if($date_num==3)
				$style='style="left:-44px"';
			if($date_num==4)
				$style='style="left:-87px"';
			if($date_num==5)
				$style='style="left:-129px"';
			if($date_num==6)
				$style='style="left:-161px"';
			if($date_num==7)
				$style='style="left:-195px"';
			/* Finish the set the style left as per calendar*/
			global $todaydate;
				$args=
				array( 'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('publish','private')	,
				'meta_key' => 'st_date',
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'st_date',
						'value' => $todaydate,
						'compare' => '<=',
						'type' => 'DATE'
					),
			// this array results in no return for both arrays
					array(
						'key' => 'end_date',
						'value' => $todaydate,
						'compare' => '>=',
						'type' => 'DATE'
					)
				)
				);


				$my_query1 = null;
				$my_query1 = new WP_Query($args);
				
				//add_action('posts_orderby','wpcal_orederby');
				
				$post_info = '';
				global $posts;
				$c =0;
				if( $my_query1->have_posts() )
				{ 
					$post_info .='<span class="calendar_tooltip" '.$style.'><span class="shape"></span>';
					while ($my_query1->have_posts()) : $my_query1->the_post();
							/* separate out recurring events with regular events */
							$is_recurring = get_post_meta($post->ID,'event_type',true);
							if(strtolower(trim($is_recurring)) == strtolower(trim('Recurring event'))){
									$recurrence_occurs = get_post_meta($post->ID,'recurrence_occurs',true);
									$rec_date = templ_recurrence_dates($post->ID);
									if(strstr($rec_date,',')){
										$rec_dates = explode(',',$rec_date);
									}else{
										$rec_dates = $rec_date;
									}													
							}

							if(is_array($rec_dates) && strtolower(trim($is_recurring)) == strtolower(trim('Recurring event')) && in_array($todaydate,$rec_dates)){ /* if recurring event */
							$c = $counter++;
								$post_info .=' 
								<a class="event_title" href="'.get_permalink($post->ID).'">'.$post->post_title.'</a><small>'.
								__('<b>Location : </b>').get_post_meta($post->ID,'address',true) .'<br>'.
								__('<b>Start Date : </b>').get_formated_date(get_post_meta($post->ID,'st_date',true)).' '.get_formated_time(get_post_meta($post->ID,'st_time',true)) .'<br />'. 
								__('<b>End Date : </b>').get_formated_date(get_post_meta($post->ID,'end_date',true)).' '.get_formated_time(get_post_meta($post->ID,'end_time',true)) .'</small>';
							}else if(strtolower($is_recurring) == strtolower('Regular event')){ /* if regular event */
									$post_info .=' 
								<a class="event_title" href="'.get_permalink($post->ID).'">'.$post->post_title.'</a><small>'.
								__('<b>Location : </b>').get_post_meta($post->ID,'address',true) .'<br>'.
								__('<b>Start Date : </b>').get_formated_date(get_post_meta($post->ID,'st_date',true)).' '.get_formated_time(get_post_meta($post->ID,'st_time',true)) .'<br />'. 
								__('<b>End Date : </b>').get_formated_date(get_post_meta($post->ID,'end_date',true)).' '.get_formated_time(get_post_meta($post->ID,'end_time',true)) .'</small>';							
							}
					endwhile;
					$post_info .='</span>';
				}
				echo "<td class='date_n' >";
				if($my_query1->have_posts())
				{	
				
						/* separate out recurring events with regular events */
							$is_recurring = get_post_meta($post->ID,'event_type',true);
							if(strtolower(trim($is_recurring)) == strtolower(trim('Recurring event'))){
									$recurrence_occurs = get_post_meta($post->ID,'recurrence_occurs',true);
									$rec_date = templ_recurrence_dates($post->ID);
									if(strstr($rec_date,',')){
										$rec_dates = explode(',',$rec_date);
									}else{
										$rec_dates = $rec_date;
									}													
							}
							
						if(is_array($rec_dates) && strtolower(trim($is_recurring)) == strtolower(trim('Recurring event')) && in_array($todaydate,$rec_dates) && $c >=0){ /* if recurring event */
							echo "<div><a class=\"more_events\" href=\"$thelink\">". ($cal_date) . "</a>".$post_info;
						}elseif(strtolower(trim($is_recurring)) == strtolower(trim('Regular event'))){
							echo "<div><a class=\"more_events\" href=\"$thelink\">". ($cal_date) . "</a>".$post_info;
						}else{
							echo "<span class=\"no_event\" >". ($cal_date) . "</span>";
						}
				}else
				{	
						echo "<span class=\"no_event\" >". ($cal_date) . "</span>";
				}
				echo "</div></td>\n";
		}
		if(($i % 7) == 0 ) echo "</tr>\n";
	}
	?>
	</tr>
	</tbody>
	<tfoot>
	<tr>
	<td id="prev" colspan="3">
		<a href="javascript:void(0);" onclick="change_calendar(<?php echo $prev_month; ?>,<?php echo $prev_year; ?>)"> &laquo; <?php echo get_calendar_month_name($prev_month); ?></a>
	</td>
	<td class="pad">&nbsp;</td>
	<td class="pad" id="next" colspan="3">
		<a href="javascript:void(0);"  onclick="change_calendar(<?php echo $next_month; ?>,<?php echo $next_year; ?>)"> <?php echo get_calendar_month_name($next_month); ?> &raquo;</a>
	</td>
	</tr>
	</tfoot>
	</table>
	</td>
</tr>
</table>
