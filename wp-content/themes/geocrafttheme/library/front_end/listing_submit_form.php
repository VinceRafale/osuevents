<?php

function geocraft_submit_form() {
    $custom_meta = get_custom_field();
    ?>
    <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/tiny_mce_init.js"></script>  
    <script type="text/javascript" src="<?php //echo LIBRARYURL;     ?>js/submit_validation.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo LIBRARYURL . 'css/geo_module_style.css'; ?>" />
    <script type="text/javascript" src="<?php echo LIBRARYURL . 'js/ajaxupload.js'; ?>"></script>    
    <?php
    if (file_exists(LIBRARYPATH . 'js/image_upload.php')) {
        require_once(LIBRARYPATH . 'js/image_upload.php');
    }
    ?>
    <h4 class="row_title"><?php echo ENTER_LISTING_DTL; ?></h4>
    <form name="placeform" id="placeform" action="<?php echo get_permalink($post->ID); ?>" method="post" enctype="multipart/form-data"> 
        <!--Start Row-->
        <div class="form_row">
            <div class="label">
                <label for="category"><?php echo CATEGORY; ?><span class="required">*</span></label>
            </div>
            <div class="field">
                <div style="height:100px; width: 290px; overflow-y:scroll; margin-bottom: 15px;">
                    <?php
                    global $wpdb;
                    $taxonomy = CUSTOM_CAT_TYPE;
                    $table_prefix = $wpdb->prefix;
                    $wpcat_id = NULL;
                    //Fetch category                          
                    $wpcategories = (array) $wpdb->get_results("
                            SELECT * FROM {$table_prefix}terms, {$table_prefix}term_taxonomy
                            WHERE {$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id
                            AND {$table_prefix}term_taxonomy.taxonomy ='" . $taxonomy . "' and  {$table_prefix}term_taxonomy.parent=0  ORDER BY {$table_prefix}terms.name ASC");

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
                            $termprice = $wpcat->term_price;
                            $tparent = $wpcat->parent;
                            ?>
                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                            <?php
                            $args = array(
                                'type' => POST_TYPE,
                                'child_of' => $termid,
                                'parent' => '',
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => 0,
                                'hierarchical' => 1,
                                'exclude' => '',
                                'include' => '',
                                'number' => '',
                                'taxonomy' => CUSTOM_CAT_TYPE,
                                'pad_counts' => false);
                            $acb_cat = get_categories($args);

                            if ($acb_cat) {
                                echo "<ul class=\"children\">";
                                foreach ($acb_cat as $child_of) {
                                    $term = get_term_by('id', $child_of->term_id, CUSTOM_CAT_TYPE);
                                    $termid = $term->term_taxonomy_id;
                                    $term_tax_id = $term->term_id;
                                    $name = $term->name;
                                    ?>
                                    <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                    <?php
                                    $args = array(
                                        'type' => POST_TYPE,
                                        'child_of' => $term_tax_id,
                                        'parent' => '',
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'hide_empty' => 0,
                                        'hierarchical' => 1,
                                        'exclude' => '',
                                        'include' => '',
                                        'number' => '',
                                        'taxonomy' => CUSTOM_CAT_TYPE,
                                        'pad_counts' => false);
                                    $acb_cat = get_categories($args);
                                    if ($acb_cat) {
                                        echo "<ul class=\"children\">";
                                        foreach ($acb_cat as $child_of) {
                                            $term = get_term_by('id', $child_of->term_id, CUSTOM_CAT_TYPE);
                                            $termid = $term->term_taxonomy_id;
                                            $term_tax_id = $term->term_id;
                                            $name = $term->name;
                                            ?>
                                            <li><label><input class="list_category" type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                            <?php
                                        }
                                        echo "</ul>";
                                    }
                                }
                                echo "</ul>";
                            }
                        }
                        echo "</ul>";
                    }
                    ?>   
                </div>                
            </div>
        </div>
        <!--End Row--> 
        <?php
        $custom_meta = get_custom_field();
        global $validation_field;
        $validation_field = array();
        foreach ($custom_meta as $key => $meta) {
            $name = $meta['name'];
            $title = $meta['title'];
            $htmlnm = $meta['htmlvar_name'];
            $default = $meta['default'];
            $type = $meta['type'];
            $description = stripcslashes($meta['description']);
            $option_values = $meta['options'];
            $is_required = '';
            if ($meta['is_require'] == 1) {
                $validation_field[] = array(
                    'name' => $key,
                    'span' => $key . '_error',
                    'type' => $meta['type'],
                );
            }
            if ($type == 'text' || $type == 'geo_map_input') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                if ($type == 'geo_map_input') {
                    $field_type = "hidden";
                } else {
                    $field_type = "text";
                }
                ?>
                <?php if ($type !== 'geo_map_input') { ?>
                    <!--Start Row-->
                    <div class="form_row">
                        <div class="label">
                            <?php if ($type !== 'geo_map_input') { ?>
                                <label for="<?php echo$name; ?>"><?php echo $title . $is_required; ?></label>
                            <?php } ?>
                        </div>
                        <div class="field">
                            <input type="<?php echo $field_type; ?>" id="<?php echo $name; ?>" name="<?php echo $name; ?>" <?php echo $script; ?> PLACEHOLDER="<?php echo $default; ?>" value=""/>
                            <?php if ($type !== 'geo_map_input') { ?>
                            <div class="clear"></div>
                               <span class="description"><?php echo $description; ?></span>
                            <?php } ?>
                            <?php
                            if ($meta['is_require'] == 1) {
                                echo '<br/>';
                                echo '<span id="' . $key . '_error"></span>';
                            }
                            ?>
                        </div>
                    </div>
                    <!--End Row--> 
                    <?php
                } elseif ($type == 'geo_map_input') {
                   echo '<input type = "hidden" id = "'.$name.'" name = "'.$name.'" value = ""/>';
                }
            }
            if ($type == 'geo_map') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <input id="geo_address" type="text" name="<?php echo $name; ?>" value=""/>
                        <?php include_once(TEMPLATEPATH . "/library/map/address_map.php"); ?>                         
                    <?php
                    if ($meta['is_require'] == 1) {
                        echo '<span id="' . $key . '_error"></span>';
                    }
                    ?> 
                    </div>                                      
                    <span class="description map"><?php echo stripslashes($description); ?></span>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'checkbox') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <input name="<?php echo $name; ?>" id="<?php echo $name; ?>" type="checkbox"  value="" />
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'radio') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>       
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <?php
                        $options = $meta['options'];
                        if ($options) {
                            $chkcounter = 0;

                            //$option_values_arr = explode(',', $options);
                            for ($i = 0; $i < count($options); $i++) {
                                $chkcounter++;
                                $seled = '';
                                if ($default_value == $options[$i]) {
                                    $seled = 'checked="checked"';
                                }
                                if (trim($value) == trim($options[$i])) {
                                    $seled = 'checked="checked"';
                                }
                                echo '<label class="r_lbl">
							<input name="' . $key . '"  id="' . $name . '" type="radio" value="' . $options[$i] . '" ' . $seled . '  /> ' . $options[$i] . '
						</label>';
                            }
                        }
                        ?>
                        <?php
                        if ($meta['is_require'] == 1) {
                            echo '<div class="clear"></div>';
                            echo '<span id="' . $key . '_error"></span>';
                        }
                        ?>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'date') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <input name="<?php echo $name; ?>" id="<?php echo $name; ?>" type="checkbox"  value="" />
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'multicheckbox') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <?php
                        $options = $meta['options'];
                        if ($option_values) {
                            $chkcounter = 0;
                            echo '<div class="multicheck">';
                            for ($i = 0; $i < count($option_values); $i++) {
                                $chkcounter++;
                                $seled = '';
                                if ($default != '') {
                                    if (in_array($option_values[$i], $default)) {
                                        $seled = 'checked="checked"';
                                    }
                                }

                                echo '
					<div class="multicheck_list">
						<label>
							<input name="' . $key . '[]"  id="' . $key . '_' . $chkcounter . '" type="checkbox" value="' . $option_values[$i] . '" ' . $seled . ' /> ' . $option_values[$i] . '
						</label>
					</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'texteditor') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <textarea style="width:250px; height: 100px;" id="<?php echo $name; ?>" name="<?php echo $name; ?>" row="20" col="25"></textarea>
                        <span class="description"><?php echo LISTING_DESS; ?></span><br/>
                        <?php
                        if ($meta['is_require'] == 1) {
                            echo '<span id="' . $key . '_error"></span>';
                        }
                        ?>
                    </div>
                </div>
                <!--End Row-->
                <?php
            }
            if ($type == 'textarea') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <textarea style="width:250px; height: 100px;" id="<?php echo $name; ?>" name="<?php echo $name; ?>" row="20" col="25"></textarea>
                        <span class="description"><?php echo LISTING_DESS; ?></span>
                        <?php
                        if ($meta['is_require'] == 1) {
                            echo '<br/>';
                            echo '<span id="' . $key . '_error"></span>';
                        }
                        ?>                        
                    </div>
                </div>
                <!--End Row-->
                <?php
            }
            if ($type == 'select') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">
                        <select name="<?php echo $name; ?>" id="<?php echo $name; ?>" class="textfield textfield_x <?php echo $style_class; ?>" <?php echo $extra_parameter; ?>>
                            <?php
                            if ($option_values) {
                                $option_values_arr = explode(',', $option_values);
                                foreach ($option_values as $values) {
                                    ?>
                                    <option value="<?php echo $values; ?>" <?php
                    if ($default == $values) {
                        echo 'selected="selected"';
                    }
                                    ?>><?php echo $values; ?></option>
                                            <?php
                                        }
                                        ?>
                                    <?php } ?>

                        </select>
                    </div>
                </div>
                <!--End Row-->
                <?php
            }
            $count = 1;
            if ($type == 'image_uploader') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="field">

                        <div style="margin-bottom: 20px;">
                            <input class='<?php echo $name; ?>' name='<?php echo $name; ?>' id='place_image1_upload' type='text' value='' />                           
                            <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $name; ?>" value="<?php echo UPLOAD_IMG; ?>" />                                                  
                                <div class="button image_reset_button hide" id="reset_<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
                            </div>  
                            <div class="clear"></div>
                            <span class="description"><?php echo $description; ?></span>
                            <?php
                            if ($meta['is_require'] == 1) {
                                echo '<div class="clear"></div>';
                                echo '<span id="' . $key . '_error"></span>';
                            }
                            ?>      
                        </div>
                    </div>
                </div>
                <!--End Row-->
                <?php
                $count++;
            }
        }
        ?>
        <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/package_price.js"></script>
        <h4 class="row_title"><?php echo SLT_PKG; ?></h4>
        <div class="form_row" id="packages_checkbox">                
            <?php
            global $wpdb, $price_table_name;
            $packages = $wpdb->get_results("SELECT * FROM $price_table_name WHERE status=1");
            $count = 1;
            if ($packages):
                foreach ($packages as $package):
                    $valid_to = $package->validity_per;
                    if ($valid_to == 'D'):
                        $valid_to = 'Days';
                    endif;
                    if ($valid_to == 'M'):
                        $valid_to = "Months";
                    endif;
                    if ($valid_to == 'Y'):
                        $valid_to = 'Years';
                    endif;
                    $rebill_time = $package->rebill_time;
                    ?>            
                    <div class="package">
                        <label><input type="radio" <?php if ($package->package_cost == 0) echo "checked='checked'"; ?> value="<?php echo $package->package_cost; ?>"  name="price_select" id="price_select<?php echo $count; ?>" />
                            <input type="hidden" class="f_home" name="f_home" value="<?php echo $package->feature_amount; ?>"/>
                            <input type="hidden" class="f_cate" name="f_cate" value="<?php echo $package->feature_cat_amount; ?>"/>
                            <input type="hidden" class="<?php echo $package->package_type; ?>" name="<?php echo $package->package_type; ?>" value="<?php echo $package->validity; ?>"/>
                            <input type="hidden" class="<?php echo $package->package_type_period; ?>" name="<?php echo $package->package_type_period; ?>" value="<?php echo $package->validity_per; ?>"/>
                            <input type="hidden" class="is_recurring" name="is_recurring" value="<?php echo $package->is_recurring; ?>"/>
                            <input type="hidden" class="validity" name="validity" value="<?php echo $package->validity; ?>"/>
                            <input type="hidden" class="validity_per" name="validity_per" value="<?php echo $package->validity_per; ?>"/>
                            <input type="hidden" class="pkg_type" name="pkg_type" value="<?php echo $package->package_type; ?>"/>
                            <?php
                            $is_recurring = $package->is_recurring;
                            if ($is_recurring == 1) {
                                ?>
                                <input type="hidden" class="f_period" name="f_period" value="<?php echo $package->first_billing_per; ?>"/>
                                <input type="hidden" class="f_cycle" name="f_cycle" value="<?php echo $package->first_billing_cycle; ?>"/>
                                <?php
                                $rebill_time = $package->rebill_time;
                                if ($rebill_time == 1) {
                                    ?>
                                    <input type="hidden" class="installment" name="installment" value="<?php echo $package->rebill_period; ?>"/>
                                <?php } ?>
                                <input type="hidden" class="s_price" name="s_price" value="<?php echo $package->second_price; ?>"/>
                                <input type="hidden" class="s_period" name="s_period" value="<?php echo $package->second_billing_per; ?>"/>
                                <input type="hidden" class="s_cycle" name="s_cycle" value="<?php echo $package->second_billing_cycle; ?>"/>
                            <?php } ?>     
                            <input type="hidden" class="is_featured" name="is_featured" value="<?php echo $package->is_featured; ?>"/>                            
                            <input type="hidden" id="price_title" name="price_title" value="<?php echo $package->price_title; ?>"/>
                            <div class="pkg_ct">                                
                                <h3><?php echo stripslashes($package->price_title); ?></h3>
                                <p><?php echo stripslashes($package->price_desc); ?></p>
                                <p class="cost"><span><?php _e('Cost : ', THEME_SLUG); ?><?php echo get_option('currency_symbol'); ?><?php echo $package->package_cost; ?></span>                       
                                    <?php
                                    if ($package->package_type == 'pkg_recurring') {
                                        if ($package->first_billing_cycle == "D"):
                                            $first_period = 'Day';
                                        elseif ($package->first_billing_cycle == 'M'):
                                            $first_period = 'Month';
                                        elseif ($package->first_billing_cycle == 'Y'):
                                            $first_period = 'Year';
                                        endif;
                                        if ($package->second_billing_cycle == "D"):
                                            $second_period = 'Days';
                                        elseif ($package->second_billing_cycle == 'M'):
                                            $second_period = 'Month';
                                        elseif ($package->second_billing_cycle == 'Y'):
                                            $second_period = 'Year';
                                        endif;
                                        if ($rebill_time == 1) {
                                            ?>
                                            <span><?php
                        //echo "$package->package_cost USD for first $first_period, then $package->second_price USD for every $package->second_billing_per $second_period, for $package->rebill_period installments";
                        printf(BILLING_TERM1, get_option('currency_symbol'), $package->package_cost, get_option('currency_code'), $package->first_billing_per, $first_period, get_option('currency_symbol'), $package->second_price, get_option('currency_code'), $package->second_billing_per, $second_period, $package->rebill_period);
                                            ?></span>  
                                                <?php
                                            } else {
                                                printf(BILLING_TERM2, get_option('currency_symbol'), $package->package_cost, get_option('currency_code'), $package->first_billing_per, $first_period, get_option('currency_symbol'), $package->second_price, get_option('currency_code'), $package->second_billing_per, $second_period);
                                            }
                                        } else {
                                            ?>
                                        <span>Valid Up to : <?php echo $package->validity . "&nbsp;" . $valid_to; ?></span>
                                    <?php } ?>
                                </p></div></label>

                    </div>               
                    <?php
                    $count++;
                endforeach;
            endif;
            ?>
        </div>
        <!--Start Row-->
        <div class="form_row" id="featured">
            <div class="label" style="width:100%;margin-bottom: 10px;">
                <label><?php echo IS_FEATURED; ?></label>
            </div>
            <div class="field">              
                <label><input id="feature_h"  type="checkbox" name="feature_h"  value="0" />&nbsp;<?php echo F_HOME; ?> <span><?php echo get_option('currency_symbol'); ?></span><span id="fhome">0</span></label>
                <br/>                
                <label><input id="feature_c"  type="checkbox" name="feature_c"  value="0" />&nbsp;<?php echo F_CAT; ?><span><?php echo get_option('currency_symbol'); ?></span><span id="fcat">0</span></label>                
                <br/><br/><span style="display:block;" class="description"><?php echo FEATURED_DES; ?></span>                                 
            </div>
        </div>
        <!--End Row--> 
        <!--Start Row-->
        <div class="form_row">
          <span id='loading' style='display:none;'><img src="<?php echo TEMPLATEURL . "/images/loading.gif"; ?>" alt='Loading..' /></span>
        </div>
        <!--Start Row-->
        <div class="form_row">
            <div class="label">
                <label><?php echo TLT_PRICE; ?> <span class="required">*</span></label>
            </div>
            <div class="field">                  
                <style type="text/css">.field span{font-size: 14px;}</style>
                <?php echo get_option('currency_symbol'); ?><span id="pkg_price">0</span>&nbsp;+&nbsp;<?php echo get_option('currency_symbol'); ?><span id="feature_price">0</span>&nbsp;=&nbsp;<?php echo get_option('currency_symbol'); ?><span id="result_price">0</span>
                <input type="hidden" name="billing" id="billing" value="0"/>
                <input type="hidden" name="total_price" id="total_price" value="0"/>  
                <input type="hidden" name="package_title" id="package_title" value=""/>
                <input type="hidden" name="package_validity" id="package_validity" value=""/>
                <input type="hidden" name="package_validity_per" id="package_validity_per" value=""/>
                <input type="hidden" name="package_type" id="package_type" value=""/>
                <br/><br/>
                <input type="submit" name="review_next" onclick="tinyMCE.triggerSave()" value="Review Your Listing"/>
                <br/><br/>
                <span class="description"><?php echo XT_PREV; ?></span>
            </div>
        </div>
        <!--End Row-->      
    </form>  
    <script type="text/javascript">
        function displaychk_frm(){
            dom = document.forms['placeform'];
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
    </script>
    
    <?php
    if (file_exists(FRONTENDPATH . 'submit_validation.php')) {
        require_once(FRONTENDPATH . 'submit_validation.php');
    }
}

