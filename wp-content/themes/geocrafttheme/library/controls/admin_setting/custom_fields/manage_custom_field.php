<?php
if (!isset($_REQUEST['ref'])):

    if (!isset($_REQUEST['ref']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'del'):
        $id = $_REQUEST['fid'];
        global $wpdb, $cfield_tbl_name;
        $query = "DELETE FROM  $cfield_tbl_name WHERE fid = $id";
        $wpdb->query($wpdb->prepare($query));
    endif;
    global $wpdb, $cfield_tbl_name;
    $sql = "SELECT * FROM  $cfield_tbl_name";
    $cfields = $wpdb->get_results($sql);
    ?>       
    <div class="group" id="of-option-customfields"> 
        <div class="section section-text ">
            <h3 class="heading"><?php echo MNG_CFIELD; ?></h3>
            <a class="pn-view-a" href="<?php echo admin_url("admin.php?page=customfield&ref=c_field#of-option-customfields"); ?>"><?php echo ADD_CFIELD; ?></a>
        </div>
        <div class="section section-text ">
            <div class="option">
                <table id="tblspacer" class="widefat fixed">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo MNG_CFIELD; ?></th>
                            <th scope="col"><?php echo TYPE; ?></th>
                            <th scope="col"><?php echo ACVIGE; ?></th>
                            <th scope="col"><?php echo ACTION; ?></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cfields as $cfield):
                            $id = $cfield->fid;
                            if ($cfield->f_type != '' && $cfield->f_type !== 'geo_map_input') {
                                ?>
                                <tr>
                                    <td><?php echo $cfield->f_title; ?></td>
                                    <td><?php echo $cfield->f_type; ?></td>
                                    <td><?php if ($cfield->is_active == 1) echo "Yes"; if ($cfield->is_active == 0) echo "No"; ?></td>
                                    <td><a href="<?php echo admin_url("admin.php?page=customfield&ref=cedit&action=edit&fid=$id"); ?>" class="edit">  <img src="<?php echo TEMPLATEURL . '/images/edit.png' ?>"/></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                        if ($cfield->f_var_nm == "geo_address") {
                                            ?>
                                            <img title="<?php echo "This field can not be deleted.(Needed on your forms for Google maps to work.)"; ?>" src="<?php echo TEMPLATEURL . '/images/undelete.png' ?>"/>
                                        <?php }elseif($cfield->f_var_nm == "list_title" || $cfield->f_var_nm == "geocraft_description"){ ?>
                                            <img title="<?php echo "This field can not be deleted.(This is required field)"; ?>" src="<?php echo TEMPLATEURL . '/images/undelete.png' ?>"/>
                                            <?php
                                        }
                                        else { ?>
                                            <a title="Delete" href="<?php echo admin_url("admin.php?page=customfield&action=del&fid=$id"); ?>" class="delete"><img src="<?php echo TEMPLATEURL . '/images/delete.png' ?>"/></a>
                                        <?php } ?>                                    
                                    </td>
                                </tr>
                            <?php } endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>                        
        <div class="clear"> </div>
    </div>
<?php endif; ?>