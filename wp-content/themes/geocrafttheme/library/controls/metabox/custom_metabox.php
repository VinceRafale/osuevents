<?php

function geocraft_custom_meta_box() {
    global $key;
    if (function_exists('add_meta_box')) {
        add_meta_box('custom-type-meta-boxes', __('Business Details', THEME_SLUG), 'custom_metabox', POST_TYPE, 'normal', 'high');
    }
}

$custom_meta = get_custom_field();
global $post, $custom_meta;
function custom_metabox() {
    global $post, $custom_meta;
    ?>
    <div class="panel-wrap">
        <script type="text/javascript">
                                                                            
            // -- NO CONFLICT MODE --
            var $s = jQuery.noConflict();
            $s(function(){
                //AJAX Upload
                jQuery('.image_upload_button').each(function(){
                                                                        			
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');	
                    new AjaxUpload(clickedID, {
                        action: '<?php echo admin_url("admin-ajax.php"); ?>',
                        name: clickedID, // File upload name
                        data: { // Additional data to send
                            action: 'of_ajax_post_action',
                            type: 'upload',
                            data: clickedID },
                        autoSubmit: true, // Submit file after selection
                        responseType: false,
                        onChange: function(file, extension){},
                        onSubmit: function(file, extension){
                            clickedObject.text('Uploading'); // change button text, when user selects file	
                            this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
                            interval = window.setInterval(function(){
                                var text = clickedObject.text();
                                if (text.length < 13){	clickedObject.text(text + '.'); }
                                else { clickedObject.text('Uploading'); } 
                            }, 200);
                        },
                        onComplete: function(file, response) {
                                                                        				   
                            window.clearInterval(interval);
                            clickedObject.text('Upload Image');	
                            this.enable(); // enable upload button
                                                                        					
                            // If there was an error
                            if(response.search('Upload Error') > -1){
                                var buildReturn = '<span class="upload-error">' + response + '</span>';
                                jQuery(".upload-error").remove();
                                clickedObject.parent().after(buildReturn);
                                                                        					
                            }
                            else{
                                var buildReturn = '<img class="hide meta-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
                                jQuery(".upload-error").remove();
                                jQuery("#image_" + clickedID).remove();	
                                clickedObject.parent().after(buildReturn);
                                jQuery('img#image_'+clickedID).fadeIn();
                                clickedObject.next('span').fadeIn();
                                clickedObject.parent().prev('input').val(response);
                            }
                        }
                    });
                                                                        			
                });
                //AJAX Remove (clear option value)
                jQuery('.image_reset_button').click(function(){
                                                                    			
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');
                    var theID = jQuery(this).attr('title');	
                                                                    	
                    var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
                                                                    				
                    var data = {
                        action: 'of_ajax_post_action',
                        type: 'image_reset',
                        data: theID
                    };
                                                                    					
                    jQuery.post(ajax_url, data, function(response) {
                        var image_to_remove = jQuery('#image_' + theID);
                        var button_to_hide = jQuery('#reset_' + theID);
                        image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
                        button_to_hide.fadeOut();
                        clickedObject.parent().prev('input').val('');
                    });
                                                                    					
                    return false; 
                                                                    					
                }); 
                                                                		
                                                                	
                                                                	
            });
        </script>
        <div class="form-wrap">
            <?php
            foreach ($custom_meta as $meta_box) {
                $data = get_post_meta($post->ID, $meta_box['name'], true);
                ?>
                <div class="form-field form-required" style="margin:0; padding: 0 8px">                   
                    <?php
                    if (!isset($meta_box['type']))
                        $meta_box['type'] = 'input';
                    switch ($meta_box['type']) :
                          case "geo_map" :
                            $metaboxvalue = get_post_meta($post->ID, $meta_box["name"], true);
                            if ($metaboxvalue == "" || !isset($metaboxvalue)) {
                                $metaboxvalue = $meta_box['default'];
                            }
                            ?>
                            <div class="row">
                                <label for="<?php echo $meta_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box['title']; ?></label>
                                <p><input id="<?php echo $meta_box['name']; ?>" size="100" style="width:320px; margin-right: 10px; float:left" type="text" value="<?php echo $metaboxvalue; ?>" name="<?php echo $meta_box['name']; ?>"/></p> 
                                <?php
                                include_once(TEMPLATEPATH . "/library/map/address_map.php");
                                echo '<p class="info">' . __('Click on "Set Address on Map" and then you can also drag pinpoint to locate the correct address', 'gc') . '</p>';
                                echo "</div>";
                                break;
                        case "geo_map_input" :
                            $ext_script = '';
                            if ($meta_box["name"] == 'geo_latitude' || $meta_box["name"] == 'geo_longitude') {
                                //$ext_script = 'onblur="changeMap();"';
                            } else {
                                $ext_script = '';
                            }
                            
                                $defaultvalue = htmlspecialchars($data);
                            
                            ?>
                            <input id="<?php echo $meta_box['name']; ?>" type="hidden" style="width:320px; margin-right: 10px; float:left" <?php echo $ext_script; ?> name="<?php echo $meta_box['name']; ?>" value="<?php echo $defaultvalue; ?>" /><?php
               
                break;
            case "date" :
                if ($post->post_status <> 'publish') :
                    echo '<p>' . __('Post is not yet published', THEME_SLUG) . '</p>';
                else :
                    $date = $data;
                    if (!$data) {
                        // Date is 30 days after publish date (this is for backwards compatibility)
                        $date = strtotime('+30 day', strtotime($post->post_date));
                    }
                                ?>							
                                <div style="float:left; margin-right: 10px; min-width: 320px;">
                                    <select name="<?php echo $meta_box['name']; ?>_month">
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) :
                                            echo '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" ';
                                            if (date_i18n('F', $date) == date_i18n('F', strtotime('+' . $i . ' month', mktime(0, 0, 0, 12, 1, 2010))))
                                                echo 'selected="selected"';
                                            echo '>' . date_i18n('F', strtotime('+' . $i . ' month', mktime(0, 0, 0, 12, 1, 2010))) . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <select name="<?php echo $meta_box['name']; ?>_day">
                                        <?php
                                        for ($i = 1; $i <= 31; $i++) :
                                            echo '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '" ';
                                            if (date_i18n('d', $date) == str_pad($i, 2, '0', STR_PAD_LEFT))
                                                echo 'selected="selected"';
                                            echo '>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <select name="<?php echo $meta_box['name']; ?>_year">
                                        <?php
                                        for ($i = 2010; $i <= 2020; $i++) :
                                            echo '<option value="' . $i . '" ';
                                            if (date_i18n('Y', $date) == $i)
                                                echo 'selected="selected"';
                                            echo '>' . $i . '</option>';
                                        endfor;
                                        ?>
                                    </select>@<input type="text" name="<?php echo $meta_box['name']; ?>_hour" size="2" maxlength="2" style="width:2.5em" value="<?php echo date_i18n('H', $date) ?>" />:<input type="text" name="<?php echo $meta_box['name']; ?>_min" size="2" maxlength="2" style="width:2.5em" value="<?php echo date_i18n('i', $date) ?>" /></div><?php
                    if ($meta_box['description'])
                        echo wpautop(wptexturize($meta_box['description']));
                                        ?>
                            <?php
                            endif;
                            break;
                        case "textarea" :
                            ?>
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <textarea rows="4" cols="40" name="<?php echo $meta_box['name']; ?>" style="width:98%; height:75px; margin-right: 10px; none"><?php echo htmlspecialchars($data); ?> </textarea>
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            break;
                        case "texteditor" :
                            ?>
<!--                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <textarea rows="4" cols="40" name="<?php echo $meta_box['name']; ?>" style="width:98%; height:75px; margin-right: 10px; none"><?php echo htmlspecialchars($data); ?> </textarea>
                            </div>-->
                            <?php
                            if ($meta_box['description'])
                                //echo wpautop(wptexturize($meta_box['description']));
                            break;
                        case "radio":
                            ?>
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <label for="<?php echo $meta_box['name']; ?>"><?php echo $meta_box['title']; ?></label>
                                <?php
                                $array = $meta_box['options'];
                                if ($array) {
                                    foreach ($array as $id => $option) {

                                        $checked = '';
                                        if ($meta_box['default'] == $option) {
                                            $checked = 'checked="checked"';
                                        }
                                        if (($data) == ($option)) {
                                            $checked = 'checked="checked"';
                                        }
                                        echo "\t\t" . '<input style="width:20px;" class="radio of-input" type="radio" ' . $checked . ' value="' . $option . '" name="' . $meta_box["name"] . '" />  ' . $option . '' . "<br/>";
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));

                            break;
                        case "checkbox" :
                            if ($data == '1') {
                                $checked = 'checked="checked"';
                            } else {
                                $checked = '';
                            }
                            ?>
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <input style="float:left; width:20px;" <?php echo $checked; ?> type="checkbox" value="1" name="<?php echo $meta_box['name']; ?>"/>
                                <label class="check-label"><?php echo $meta_box['title']; ?></label>
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            break;
                        case "multicheckbox" :
                            echo '<div style="float:left; margin-right: 10px; min-width: 320px;">';
                            echo '<label>' . $meta_box['title'] . '</label>' . "\n";
                            $array = $meta_box['options'];
                            if ($array) {
                                foreach ($array as $id => $option) {

                                    $checked = '';
                                    if ($data != "") {
                                        $fval_arr = $data;
                                        if (in_array($option, $fval_arr)) {
                                            $checked = 'checked="checked"';
                                        }
                                    } else {
                                        $fval_arr = $meta_box['default'];
                                        if ($fval_arr != "") {
                                            if (in_array($option, $fval_arr)) {
                                                $checked = 'checked="checked"';
                                            }
                                        }
                                    }

                                    echo '<div  class="multicheckbox"><input style="float:left; width:20px;" type="checkbox" ' . $checked . ' value="' . $option . '" name="' . $meta_box["name"] . '[]" />  ' . $option . '</div>' . "<br/>";
                                }
                            }
                            echo ' </div>';
                            ?>

                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            break;
                        case "text" :
                            if($meta_box['name'] != 'list_title' && $meta_box['name'] != 'geocraft_tag'){
                            ?>
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <label for="<?php echo $meta_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box['title']; ?></label>
                                <input id="<?php echo $meta_box['name']; ?>" type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $meta_box['name']; ?>" value="<?php echo $data; ?>" />
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            ?>
                            <?php
                            }
                            break;
                        case "select" :
                            ?>
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <?php
                                echo '<label>' . $meta_box['title'] . '</label>' . "\n";
                                echo '<select name="' . $meta_box["name"] . '">' . "\n";
                                echo '<option>Select a ' . $meta_box['title'] . '</option>';

                                $array = $meta_box['options'];

                                if ($array) {
                                    foreach ($array as $id => $option) {
                                        $selected = '';
                                        if ($meta_box['default'] == $option) {
                                            $selected = 'selected="selected"';
                                        }
                                        if ($data == $option) {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                                    }
                                }
                                echo '</select>';
                                ?>
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            break;
                        case "image_uploader" :
                            ?>  
                            <div style="float:left; margin-right: 10px; min-width: 320px;">
                                <label for="<?php echo $meta_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box['title']; ?></label>
                                <input class='of-input' style="width:500px; margin-bottom: 10px;" name='<?php echo $meta_box['name']; ?>' id='<?php echo $meta_box['name']; ?>_upload' type='text' value='<?php echo htmlspecialchars($data); ?>' />
                                <div class="upload_button_div"><span class="button image_upload_button" id="<?php echo $meta_box['name']; ?>">Upload Image</span>
                                    <?php $hide = ($data != '') ? '' : 'hide'; ?>                               
                                    <span class="button image_reset_button <?php echo $hide; ?>" id="reset_<?php echo $meta_box['name']; ?>" title="<?php echo $meta_box['name']; ?>"><?php echo REMOVE; ?></span>
                                </div>
                                <?php if ($data != '') { ?>
                                    <img src="<?php echo $data; ?>" class="meta-image" id="image_<?php echo $meta_box['name']; ?>" style="display: inline; "/>
                                <?php } ?>
                            </div>
                            <?php
                            if ($meta_box['description'])
                                echo wpautop(wptexturize($meta_box['description']));
                            break;
                    endswitch;
                    ?>				
                    <div class="clear"></div>
                </div>
            <?php } ?>
        </div>
    </div>	
    <?php
}

function geocraft_save_custommeta_box($post_id) {
    global $post, $meta_boxes, $key, $custom_meta;
    if (!isset($_POST[$key . '_wpnonce']))
        return $post_id;  
    if (!current_user_can('edit_post', $post_id))
        return $post_id;
    foreach ($custom_meta as $custom_type) {
        //if ($_POST[$listing_type['name']] != '') {
        update_post_meta($post_id, $custom_type['name'], $_POST[$custom_type['name']]);
        if ($custom_type['type'] == 'date') {
            $year = $_POST[$custom_type['name'] . '_year'];
            $month = $_POST[$custom_type['name'] . '_month'];
            $day = $_POST[$custom_type['name'] . '_day'];
            $hour = $_POST[$custom_type['name'] . '_hour'];
            $min = $_POST[$custom_type['name'] . '_min'];
            if (!$hour)
                $hour = '00';
            if (!$min)
                $min = '00';
            if (checkdate($month, $day, $year)) :
                $date = $year . $month . $day . ' ' . $hour . ':' . $min;
                update_post_meta($post_id, $custom_type['name'], strtotime($date));
            endif;
        }
        //}
    }
}

add_action('admin_menu', 'geocraft_custom_meta_box');
add_action('save_post', 'geocraft_save_custommeta_box');