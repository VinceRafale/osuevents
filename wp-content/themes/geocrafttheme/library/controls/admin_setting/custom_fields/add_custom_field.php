<?php
if (isset($_REQUEST['ref']) && $_REQUEST['ref'] == 'c_field'):
    if (isset($_POST['submit']) && isset($_POST['field_title']) && $_POST['field_title'] != '') {
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
        $wpdb->insert($cfield_tbl_name, $custom_field);
    }
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
            <h3 class="heading"><?php echo ADD_NEW_CUSTOM; ?></h3>         
            <a href="<?php echo admin_url("admin.php?page=customfield#of-option-customfields"); ?>"><?php echo BACK_TO_MANAGE; ?></a>      
        </div><!--
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
        <?php //get_category_list(); ?>
                </div>
                <div class="explain"><?php echo SHOW_CATE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div> -->                       
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo FLD_TYPE; ?></h3>
            <div class="option">
                <div class="controls">
                    <select name="feild_type" class="of-input" id="feild_type" onchange="show_option_add(this.value)" >                        
                        <option value="text"><?php echo TXT; ?></option>
                            <option value="geo_map"><?php _e('Geo Map'); ?></option>                        
    <!--                        <option value="multicheckbox"><?php _e('Multi Checkbox'); ?></option>-->
                        <option value="radio"><?php echo RADIO; ?></option>
                        <option value="select"><?php echo SELECT; ?></option>
    <!--                        <option value="texteditor"><?php _e('Text Editor'); ?></option>-->
                        <option value="textarea"><?php echo TEXTAREA; ?></option>                      
                        <option value="image_uploader"><?php echo IMG_UPLOADER; ?></option>                      
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
                    <input class="of-input" type="text" name="option_value" id="option_value" value="" />
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
                    <input class="of-input" type="text" name="field_title" id="field_title" value="" />
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
                    <input class="of-input" type="text" name="htmlvar_name" id="htmlvar_name" value="" />
                </div>
                <div class="explain"><?php echo HTML_NAME_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo DES; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="f_des" id="f_des" value="" />
                </div>
                <div class="explain"><?php echo DES_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div>                        
        <div class="clear"> </div>
        <div class="section section-text ">
            <h3 class="heading"><?php echo DFLD_VALUE; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="default_value" id="default_value" value="" />
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
                    <input class="of-input" type="text" name="sort_order" id="sort_order" value="" />
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
    if ($post_val->is_active == '1') {
        echo 'selected="selected"';
    }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($post_val->is_active == '0') {
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
                            if ($post_val->is_require == '1') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($post_val->is_require == '0') {
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
                            if ($post_val->show_on_detail == '1') {
                                echo 'selected="selected"';
                            }
        ?>><?php _e('Yes'); ?></option>
                        <option value="0" <?php
                            if ($post_val->show_on_detail == '0') {
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
         <div class="section section-text ">
            <h3 class="heading"><?php echo "Enable for free listing"; ?></h3>
            <div class="option">
                <div class="controls">
                    <label><input style="width: auto;" class="of-input" type="checkbox" name="show_free" value="true"/>
                    Free Listing</label>
                </div>
                <div class="explain"><?php echo "Check if you want to display field for Free Listing. Unchecking this option will display this field only for Paid Listing. For Eg: You can hide website or phone number of Free Listed businesses, which will act as a motivator for them to upgrade their listing to paid."; ?></div>
                <div class="clear"> </div>
            </div>
        </div> 
    </div>
<?php endif; ?>