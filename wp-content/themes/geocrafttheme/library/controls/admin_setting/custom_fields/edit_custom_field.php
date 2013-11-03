<?php
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'customfield' && isset($_REQUEST['ref']) && $_REQUEST['ref'] == 'cedit' && $_REQUEST['action'] == 'edit'):
    $id = $_REQUEST['fid'];
    if (isset($_POST['submit'])) {
        $custom_field = array();
        $custom_field['field_cate'] = $_POST['category'];
        if ($custom_field['field_cate']) {
            $custom_field['field_cate'] = implode(',', $custom_field['field_cate']);
        }
        $custom_field['f_type'] = esc_attr($_POST['feild_type']);
        $custom_field['opt_value'] = esc_attr($_POST['option_value']);
        $custom_field['f_title'] = esc_attr($_POST['field_title']);
        $custom_field['f_var_nm'] = esc_attr($_POST['htmlvar_name']);
        $custom_field['f_des'] = esc_attr($_POST['f_des']);
        $custom_field['dft_value'] = esc_attr($_POST['default_value']);
        $custom_field['p_order'] = esc_attr($_POST['sort_order']);
        $custom_field['is_active'] = esc_attr($_POST['is_active']);
        $custom_field['is_require'] = esc_attr($_POST['is_require']);
        $custom_field['show_on_detail'] = esc_attr($_POST['show_on_detail']);
        $custom_field['show_free'] = esc_attr($_POST['show_free']);
        global $wpdb, $cfield_tbl_name;
        $wpdb->update($cfield_tbl_name, $custom_field, array('fid' => $id));
    }
    global $wpdb, $cfield_tbl_name;
    
    $sql = "SELECT * FROM  $cfield_tbl_name WHERE fid = $id";
    $cfields = $wpdb->get_row($sql);
    ?>
    <script type="text/javascript">
        function displaychk_frm(){
            dom = document.forms['price_form'];
            chk = dom.elements['category[]'];
            len = dom.elements['category[]'].length;
                                                                                                    	
            if(document.getElementById('selectall').checked == true) { 
                for (i = 0; i < len; i++)
                    chk[i].checked = true ;
            } else { 
                for (i = 0; i < len; i++)
                    chk[i].checked = false ;
            }
        }
                                                        
                                                        
        function show_option_add(htmltype){
            if(htmltype=='select' || htmltype=='multiselect' || htmltype=='radio' || htmltype=='multicheckbox')	{
                document.getElementById('opt_value').style.display='';		
            }else{
                document.getElementById('opt_value').style.display='none';	
            }                            
        }
        if(document.getElementById('feild_type').value){
            show_option_add(document.getElementById('feild_type').value)	;
        }
    </script>
    <div class="group" id="of-option-customfields"> 
        <div class="section section-text">
            <h3 class="heading"><?php echo "Edit Custom Field"; ?></h3>         
            <a href="<?php echo admin_url("admin.php?page=customfield#of-option-customfields"); ?>"><?php echo "&laquo; Back to manage custom field list"; ?></a>      
        </div>
        <!--
    <div class="section section-text ">
    <h3 class="heading"><?php echo SHOW_CATE; ?></h3>
    <div class="option">
        <div class="controls" style="height:150px;overflow-y:scroll;">
      
            <style type="text/css">
                .select_cat li{
                    margin: 0;
                    padding: 0
                }
            </style>
        <?php
        $cates = array();
        $cates = explode(',', $cfields->field_cate);
        global $wpdb;
        $taxonomy = CUSTOM_CAT_TYPE;
        $table_prefix = $wpdb->prefix;
        $wpcat_id = NULL;
        //Fetch category                          
        $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name");

        $wpcategories = array_values($wpcategories);
        if ($wpcategories) {
            echo "<ul class=\"select_cat\">";
            if ($taxonomy == CUSTOM_CAT_TYPE) {
                ?>
                                                <li><label><input type="checkbox" name="selectall" id="selectall" class="checkbox" onclick="displaychk_frm();" /></label><?php echo SLT_ALL; ?></li>

                <?php
            }
            foreach ($wpcategories as $wpcat) {
                $counter++;
                $termid = $wpcat->term_id;
                $name = $wpcat->name;
                $tparent = $wpcat->parent;
                ?>
                                                <li><label><input type="checkbox" name="category[]" id="<?php echo $termid; ?>" <?php
            if (in_array($termid, $cates)) {
                echo 'checked="checked"';
            }
                ?> value="<?php echo $termid; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                <?php
            }
            echo "</ul>";
        }
        ?>  
        </div>
        <div class="explain"><?php echo SHOW_CATE_DES; ?></div>
        <div class="clear"> </div>
    </div>
    </div>   -->                     
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo FLD_TYPE; ?></h3>
            <div class="option">
                <div class="controls">
                    <select name="feild_type" class="of-input" id="feild_type" onchange="show_option_add(this.value)" >
                        <option value="text" <?php if ($cfields->f_type == 'text') echo 'selected="selected"' ?>><?php echo TXT; ?></option> 
                        <option value="geo_map" <?php if ($cfields->f_type == 'geo_map') echo 'selected="selected"' ?>><?php _e('Geo Map'); ?></option>
                        <option value="radio" <?php if ($cfields->f_type == 'radio') echo 'selected="selected"' ?>><?php echo RADIO; ?></option>
                        <option value="select" <?php if ($cfields->f_type == 'select') echo 'selected="selected"' ?>><?php echo SELECT; ?></option>
    <!--                        <option value="texteditor" <?php if ($cfields->f_type == 'texteditor') echo 'selected="selected"' ?>><?php _e('Text Editor'); ?></option>-->
                        <option value="textarea" <?php if ($cfields->f_type == 'textarea') echo 'selected="selected"' ?>><?php echo TEXTAREA; ?></option>                      
                        <option value="image_uploader" <?php if ($cfields->f_type == 'image_uploader') echo 'selected="selected"' ?>><?php echo IMG_UPLOADER; ?></option>                      
                    </select>
                </div>
                <div class="explain"><?php echo FLD_TYPE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text" id="opt_value" style="display:none;">
            <h3 class="heading"><?php echo OPTION_VALUE; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="option_value" id="option_value" value="<?php echo $cfields->opt_value; ?>" />
                </div>
                <div class="explain"><?php echo OPTION_VALUE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo NAME_OF_FIELD; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="field_title" id="field_title" value="<?php echo $cfields->f_title; ?>" />
                </div>
                <div class="explain"><?php echo NAME_OF_FIELD_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo HTML_NAME; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="htmlvar_name" id="htmlvar_name" value="<?php echo $cfields->f_var_nm; ?>" />
                </div>
                <div class="explain"><?php echo HTML_NAME_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div> 
        <div class="section section-text ">
            <h3 class="heading"><?php echo DES; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="f_des" id="f_des" value="<?php echo $cfields->f_des; ?>" />
                </div>
                <div class="explain"><?php echo DES_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo DFLD_VALUE; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="default_value" id="default_value" value="<?php echo $cfields->dft_value; ?>" />
                </div>
                <div class="explain"><?php echo DFLD_VALUE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo POSITION; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="sort_order" id="sort_order" value="<?php echo $cfields->p_order; ?>" />
                </div>
                <div class="explain"><?php echo POSITION_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo ACTIVE; ?></h3>
            <div class="option">
                <div class="controls">

                    <select name="is_active" id="is_active">
                        <option value="1" <?php
    if ($cfields->is_active == '1') {
        echo 'selected="selected"';
    }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($cfields->is_active == '0') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('No'); ?></option>
                    </select>
                </div>
                <div class="explain"><?php echo ACTIVE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo REQUIRED; ?></h3>
            <div class="option">
                <div class="controls">
                    <select name="is_require" id="is_require" >
                        <option value="1" <?php
                            if ($cfields->is_require == '1') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($cfields->is_require == '0') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('No'); ?></option>
                    </select>
                </div>
                <div class="explain"><?php echo REQUIRED_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo SHOW_ON_DLTS; ?></h3>
            <div class="option">
                <div class="controls">
                    <select name="show_on_detail" id="show_on_detail">
                        <option value="1" <?php
                            if ($cfields->show_on_detail == '1') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($cfields->show_on_detail == '0') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('No'); ?></option>
                    </select>
                </div>
                <div class="explain"><?php echo SHOW_ON_DLTS_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
		<?php  global $wpdb, $cfield_tbl_name;
		 $social = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE fid = $id", ARRAY_A);
		if(($social['f_var_nm']!='geocraft_twitter') && ($social['f_var_nm']!='geocraft_facebook')&& ($social['f_var_nm']!='geocraft_googleplus')&& ($social['f_var_nm']!='geocraft_meta_image1')){
				?>
        <div class="section section-text ">
            <h3 class="heading"><?php echo "Enable for free listing"; ?></h3>
            <div class="option">
                <div class="controls">
                    <label><input style="width: auto;" class="of-input" type="checkbox" name="show_free" <?php if($cfields->show_free == 'true') echo 'checked="checked"'; ?> value="true"/>&nbsp;Free Listing</label>
                </div>
                <div class="explain"><?php echo "Check if you want to display field for Free Listing. Unchecking this option will display this field only for Paid Listing. For Eg: You can hide website or phone number of Free Listed businesses, which will act as a motivator for them to upgrade their listing to paid."; ?></div>
                <div class="clear"> </div>
            </div>
        </div> 
		<?php } ?>
    </div>
<?php endif; ?>