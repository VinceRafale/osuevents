<?php 
if(!function_exists('manage_custom_field')){
function manage_custom_field(){
    if (isset($_REQUEST['of_save']) && 'reset' == $_REQUEST['of_save']) {
    global $wpdb,$cfield_tbl_name;
    $sql2 = "DROP TABLE IF EXISTS $cfield_tbl_name";
    $wpdb->query($wpdb->prepare($sql2));         
}
    ?>
<div class="wrap" id="of_container">
    <div id="of-popup-save" class="of-save-popup">
        <div class="of-save-save"></div>
    </div>
    <div id="of-popup-reset" class="of-save-popup">
        <div class="of-save-reset"></div>
    </div>
    <div id="header">
        <div class="logo">
            <h2><?php echo C_FIELD; ?> <?php echo OPTIONS; ?></h2>
        </div>
        <a href="http://www.inkthemes.com" target="_new">
            <div class="icon-option"> </div>
        </a>
        <div class="clear"></div>
    </div>
    <form enctype="multipart/form-data" id="ofform" name="price_form" method="post">
        <input type="hidden" name="price_id" value="<?php echo $_REQUEST['price_id']; ?>">
        <input type="hidden" id="addpackage" name="priceval" value="addpackage">
        <input type="hidden" name="package1" value="package1" />

        <div id="main">
            <div id="of-nav">
                <ul>
                    <li> <a  class="pn-view-a" href="#of-option-customfields" title="Custom Fields"><?php echo C_FIELD; ?></a></li> 
                </ul>
            </div>
            <div id="content">
                <?php
                $file_name = array(
                    'manage_custom_field',
                    'add_custom_field',
                    'edit_custom_field'
                );
                foreach ($file_name as $files):
                    if (file_exists(CFIELDPATH . $files . '.php')):
                        require_once(CFIELDPATH . $files . '.php');
                    endif;
                endforeach;
                ?>                    
                <div class="group" id="of-option-import">
                    <?php
                    //require_once(SETTINGPATH . 'admin_bulk_upload' . '.php');
                    ?>
                </div> 
            </div>
            <div class="clear"></div>
        </div>
        <div class="save_bar_top">
            <img style="display:none" src="<?php echo ADMINURL; ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
            <input type="submit" id="submit" name="submit" value="<?php echo SAVE_ALL_CHNG; ?>" class="button-primary" />       
    </form> 
    <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']) ?>" method="post" style="display:inline" id="ofform-reset">
        <span class="submit-footer-reset">
            <input name="reset" type="submit" value="Reset Options" class="button submit-button reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
            <input type="hidden" name="of_save" value="reset" />
        </span>
    </form>
</div>            
<div style="clear:both;"></div>

</div>
<!--wrap-->
<?php
}
}
?>