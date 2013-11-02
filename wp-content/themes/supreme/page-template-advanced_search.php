<?php
/*
Template Name: Template - Advanced Search
*/
?>
<?php get_header(); ?>
<?php do_atomic( 'before_content' ); // supreme_before_content ?>
<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
<div id="content" class="multiple">
  <?php do_atomic( 'open_content' ); // supreme_open_content ?>
  <div class="hfeed">
    <!--  CONTENT AREA START -->
    <div class="entry">
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
        <div class="post-content">
          <?php the_content(); ?>
        </div>
        <div id="advancedsearch">
          <h4> <?php echo SEARCH_WEBSITE; ?></h4>
          <form method="get"  action="<?php echo bloginfo('url')."/"; ?>" name="searchform" onsubmit="return sformcheck();">
            <div class="advanced_left">
              <p class="form_row">
                <label><?php echo SEARCH;?></label>
                <input class="adv_input" name="s" id="adv_s" type="text" PLACEHOLDER="<?php echo SEARCH; ?>" value="" />
                <input class="adv_input" name="adv_search" id="adv_search" type="hidden" value="1"  />
              </p>
              <p class="form_row">
                <label><?php echo CATEGORY;?></label>
                <?php wp_dropdown_categories( array('name' => 'catdrop','orderby'=> 'name','show_option_all' => __('select category','templatic'), 'taxonomy'=>array('category','product_cat')) ); ?>
              </p>
              <p class="form_row">
              <script type="text/javascript">				
				jQuery(function(){
				var pickerOpts = {
					showOn: "button",
					buttonImage: "<?php echo get_stylesheet_directory_uri();?>/images/cal.gif",
					buttonText: "Show Datepicker"
				};	
				jQuery("#catelog_todate").datepicker(pickerOpts);
				jQuery("#catelog_frmdate").datepicker(pickerOpts);
			});
			</script>
                <label><?php echo DATE_TEXT;?></label>
                <input type="text" id="catelog_todate" name="catelog_todate" class="textfield date-feild">
                <input type="text" id="catelog_frmdate" name="catelog_frmdate" class="textfield date-feild">
                
              	<!--<input name="catelog_todate" type="text" class="textfield date-feild"/>
                <img src="<?php echo get_stylesheet_directory_uri();?>/images/cal.gif" alt="Calendar" class="adv_calendar" onclick="displayCalendar(document.searchform.catelog_todate,'yyyy-mm-dd',this)"  /> <?php echo TO;?>               
                <input name="catelog_frmdate" type="text" class="textfield date-feild"/>
                <img src="<?php echo get_stylesheet_directory_uri();?>/images/cal.gif" alt="Calendar"  class="adv_calendar" onclick="displayCalendar(document.searchform.catelog_frmdate,'yyyy-mm-dd',this)"  /> </p>-->
              <p class="form_row">
                <label><?php echo AUTHOR_TEXT;?> </label>
                <input name="articleauthor" type="text" class="textfield"  />
              </p>
              <p class="form_row adv-chk">
              	<input name="exactyes" type="checkbox" value="1" class="checkbox" />
                <span class="adv_author"> <?php echo EXACT_AUTHOR_TEXT;?> </span>
              </p>
              <p class="form_row"><input type="submit" value="Submit" class="adv_submit b_submit" /></p>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--  CONTENT AREA END -->
  </div>
  <?php get_template_part( 'loop-nav' ); ?>
  <?php do_atomic( 'close_content' ); // supreme_close_content ?>
</div>
<?php do_atomic( 'after_content' ); // supreme_after_content ?>
<script type="text/javascript" >
function sformcheck()
{
if(document.getElementById('adv_s').value=="")
{
	alert('<?php echo SEARCH_ALERT_MSG;?>');
	document.getElementById('adv_s').focus();
	return false;
}
if(document.getElementById('adv_s').value=='<?php echo SEARCH;?>')
{
document.getElementById('adv_s').value = ' ';
}
return true;
}
</script>
<?php get_footer(); ?>
