<?php
add_action( 'wp_dashboard_setup', 'TemplaticDashboardWidgetSetup');
function TemplaticDashboardWidgetSetup() {
	add_meta_box( 'templatic_dashboard_news_widget', 'News From Templatic', 'TemplaticDashboardWidgetFunction', 'dashboard', 'normal', 'high' );
}

function TemplaticDashboardWidgetFunction() {
	//error_reporting(E_ALL);
	?>
	<div class="table table_tnews">
    <p class="sub"><strong><?php _e('Templatic News','supreme'); ?></strong></p>
    <div class="trss-widget">
	<?php
	$items = get_transient('templatic_dashboard_news');

    if (empty($items)) {
	include_once(ABSPATH . WPINC . '/class-simplepie.php');
    $trss = new SimplePie();
	$trss->set_timeout(5);
    $trss->set_feed_url('http://feeds.feedburner.com/Templatic');
    $trss->strip_htmltags(array_merge($trss->strip_htmltags, array('h1', 'a')));
    $trss->enable_cache(false);
    $trss->init();
    $items = $trss->get_items(0, 3);
	$cached = array();
	
    foreach ($items as $item) { 
        preg_match('/(.{128}.*?)\b/', $item->get_content(), $matches);
        $cached[] = array(
            'url' => $item->get_permalink(),
            'title' => $item->get_title(),
            'date' => $item->get_date("d M Y"),
            'content' => rtrim($matches[1]) . '...'
        );
    }
	 $items = $cached;
    set_transient('templatic_dashboard_news', $cached, 60 * 60 * 24);
	}
   
	?>
	<ul class="news">
            <?php 
                foreach ($items as $item) {
            ?>
            
                <li class="post">
                    <a href="<?php echo $item['url']; ?>" class="rsswidget"><?php echo $item['title']; ?></a>
                    <span class="rss-date"><?php echo $item['date']; ?></span>
                    <div class="rssSummary"><?php echo strip_tags($item['content']); ?></div>
                </li>
    <?php
                } 
            
            ?>
    </ul>
	</div>
	</div>
	<div class="t_theme">
		<div class="t_thumb">
        <?php

		
            $lastTheme = get_transient('templatic_dashboard_theme');
            if (!$lastTheme) {
               $lastTheme = file_get_contents('http://templatic.com/latest-theme/');
                if ($lastTheme) {
                    set_transient('templatic_dashboard_theme', $lastTheme, 60 * 60 * 24);
                }
           } 
		

        ?>
        <?php if ($lastTheme) echo $lastTheme; ?>

		</div>
    <div class="clearfix"></div>
    <a href="http://templatic.com/wordpress-themes-store/" target="_blank" alt="Browse our wide selection of WordPress themes to find the right one for you" class="button">Browse more &rarr;</a>
	
	</div>
    <div class="clearfix"></div>
	<?php
}
?>