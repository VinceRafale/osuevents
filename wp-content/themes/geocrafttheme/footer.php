<!--End Content Wrapper-->
<div class="clear"></div>
<div class="page_line"></div>
<div class="clear"></div>
</div>
</div>
<!--End Container-->
</div>
<div class="clear"></div>
<!--Start Footer Wrapper-->
<div class="footer_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <?php
            /**
             * Footer widgets 
             */
            get_sidebar('footer');
            ?>
        </div>
    </div>
</div>
<!--End Footer Wrapper-->
<div class="clear"></div>
<!--Start Footer Bottom-->
<div class="footer_bottom">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_7 alpha">
                    <ul class="social_icon">                    
                        <?php if (geocraft_get_option('yahoo') != '') { ?>
                            <li class="dribble"><a target="new" href="<?php echo geocraft_get_option('yahoo'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/yahoo.png'; ?>" alt="yahoo" title="Yahoo"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('blogger') != '') { ?>
                            <li class="dribble"><a target="new" href="<?php echo geocraft_get_option('blogger'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/blogger.png'; ?>" alt="blogger" title="Blogger"/>
                                </a></li>
                        <?php } ?>        
                        <?php if (geocraft_get_option('facebook') != '') { ?>
                            <li class="facebook"><a target="new" href="<?php echo geocraft_get_option('facebook'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/facebook.png'; ?>" alt="facebook" title="Facebook"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('twitter') != '') { ?>
                            <li class="twitter"><a target="new" href="<?php echo geocraft_get_option('twitter'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/twitter.png'; ?>" alt="twitter" title="Twitter"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('rss') != '') { ?>
                            <li class="vimeo"><a target="new" href="<?php echo geocraft_get_option('rss'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/rss.png'; ?>" alt="rss" title="Rss"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('youtube') != '') { ?>
                            <li class="youtube"><a target="new" href="<?php echo geocraft_get_option('youtube'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/youtube.png'; ?>" alt="youtube" title="Youtube"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('plusone') != '') { ?>
                            <li class="google"><a target="new" href="<?php echo geocraft_get_option('plusone'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/google.png'; ?>" alt="google+" title="Google+"/>
                                </a></li>
                        <?php } ?>
                        <?php if (geocraft_get_option('pinterest') != '') { ?>
                            <li class="pinterest"><a target="new" href="<?php echo geocraft_get_option('pinterest'); ?>">
                                    <img src="<?php echo TEMPLATEURL . '/images/pinterest.png'; ?>" alt="pinterest" title="Pinterest"/>
                                </a></li>
                        <?php } ?>
                                &nbsp;
                    </ul>             
            </div>
            <div class="grid_17 omega">
                <?php if (geocraft_get_option('inkthemes_footertext') != '') { ?>
                    <p class="copy_right"><?php echo geocraft_get_option('inkthemes_footertext'); ?></p>
                <?php } else { ?>
                    <a href="<?php echo AUTHORURL; ?>"><p class="copy_right">&COPY; 2012 GeoCraft Theme by InkThemes</p></a> Downloaded from <a href="http://www.freewptools.com/">freewptools</a>.
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!--End Footer Bottom-->
<?php wp_footer(); ?>
</body>
</html>
