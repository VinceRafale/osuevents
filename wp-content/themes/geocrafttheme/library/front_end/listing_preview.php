<?php

function geocraft_preview_place_form() {
    global $post, $posted;

    //Get price info
    function geocraft_listing_price_info($pro_type = '', $price = '') {
        global $price_table_name, $wpdb, $posted;
        if ($pro_type != "") {
            $subsql = " and pid=\"$pro_type\"";
        }
        $pricesql = "select * from $price_table_name where status=1";
        $one_timesql = "select * from $price_table_name where status=1 and package_type = 'pkg_one_time'";
        $one_time_data = $wpdb->get_row($one_timesql);
        $priceinfo = $wpdb->get_results($pricesql);
        $price_info = array();
        if ($priceinfo != "") {
            foreach ($priceinfo as $priceinfoObj) {
                $info = array();
                $vper = $priceinfoObj->validity_per;
                $validity = $priceinfoObj->validity;

                $first_billing_per = $priceinfoObj->first_billing_per;
                $first_billing_cycle = $priceinfoObj->first_billing_cycle;
                $second_billing_per = $priceinfoObj->second_billing_per;
                $second_billing_cycle = $priceinfoObj->second_billing_cycle;
                $tvalidity = 0;
                $rvalidity = 0;
                if ($posted['package_type'] == 'pkg_recurring') {
                    if (($priceinfoObj->validity != "" || $priceinfoObj->validity != 0)) {
                        $rvalidity = get_billing_period($first_billing_per, $first_billing_cycle, $second_billing_per, $second_billing_cycle);
                        $tvalidity = $rvalidity;
                    }
                }
                $info['title'] = $priceinfoObj->price_title;
                $info['price'] = $price;
                $info['days'] = $tvalidity;
                $info['alive_days'] = $tvalidity;
                $info['cat'] = $priceinfoObj->price_post_cat;
                $info['is_featured'] = $priceinfoObj->is_featured;
                $info['title_desc'] = $priceinfoObj->title_desc;
                $info['is_recurring'] = $priceinfoObj->is_recurring;
                $price_info[] = $info;
            }
        }


        if ($posted['package_type'] == 'pkg_one_time') {
            if (($one_time_data->validity != "" || $one_time_data->validity != 0)) {
                if ($one_time_data->validity_per == 'M') {
                    $tvalidity = $one_time_data->validity * 30;
                } else if ($one_time_data->validity_per == 'Y') {
                    $tvalidity = $one_time_data->validity * 365;
                } else {
                    $tvalidity = $one_time_data->validity;
                }
            }
        }



        return $tvalidity;
    }

    global $paypalamount, $post_title;
    $property_price_info = geocraft_listing_price_info('', $posted['total_cost']);
    $payble_amt = $posted['total_cost'];
    $list_title = $posted['package_title'];
    $alive_days = $property_price_info;
    if($_POST['billing'] == 1){
    $is_recuring = "until cancelled";
    }
    ?>
    <form action="<?php echo get_permalink($post->ID); ?>" method="post" enctype="multipart/form-data" id="submit_form" class="preview_form">
        <?php
        if (isset($payble_amt) && $payble_amt > 0) {
            ?>
            <div class="preview_payment_approve" style="padding: 15px; margin-bottom: 15px; background:#98def9;border: 1px solid #015c89;">
                <p><?php
                if($_POST['billing'] == 1){
                printf(PREVIEW_MSG1, get_option('currency_symbol'), $payble_amt, $is_recuring, $list_title);
                }else{
                 printf(PREVIEW_MSG, get_option('currency_symbol'), $payble_amt, $alive_days, $list_title);   
                }
            ?></p>

                <?php
                global $wpdb;
                $is_recurring = $is_recurring[0]['is_recurring'];

                $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_%' order by option_id limit 1";
                $paymentinfo = $wpdb->get_results($paymentsql);
                if ($paymentinfo) {
                    ?>
                    <br/>
                    <h4>Your payment method</h4>                             
                    <ul id="payments">
                        <?php
                        $paymentOptionArray = array();
                        $paymethodKeyarray = array();
                        foreach ($paymentinfo as $paymentinfoObj) {
                            $paymentInfo = unserialize($paymentinfoObj->option_value);
                            if ($paymentInfo['isactive']) {
                                $paymethodKeyarray[] = $paymentInfo['key'];
                                $paymentOptionArray[$paymentInfo['display_order']][] = $paymentInfo;
                            }
                        }
                        ksort($paymentOptionArray);
                        if ($paymentOptionArray) {
                            foreach ($paymentOptionArray as $key => $paymentInfoval) {
                                for ($i = 0; $i < count($paymentInfoval); $i++) {
                                    $paymentInfo = $paymentInfoval[$i];
                                    $jsfunction = 'onclick="showoptions(this.value);"';
                                    $chked = '';
                                    $chked = 'checked="checked"';
                                    // if ($paymentInfo['isactive'] == 1):
                                    ?>

                                    <li id="<?php echo $paymentInfo['key']; ?>">
                                        <label class="r_lbl">                                        
                                            <input  type="radio" value="<?php echo $paymentInfo['key']; ?>" id="<?php echo $paymentInfo['key']; ?>_id" name="pay_method" <?php echo $chked; ?> />  
                                            <?php echo $paymentInfo['name'] ?>
                                        </label>                       

                                    </li>
                                    <?php
                                    // endif;
                                }
                            }
                        }
                        ?>
                    </ul>
                <?php } ?> 
            </div> 
        <?php } ?>
        <div class="depth_article">
            <div class="grid_12 alpha">
                <!--Start Article Slider-->
                <div class="article_slider">  
                    <div class="flexslider">
                        <ul class="slides">
                            <?php
                            $custom_meta = get_custom_field();
                            foreach ($custom_meta as $meta) {
                                if ($meta['type'] == 'image_uploader') {
                                    $field = $posted[$meta['name']];
                                    if (isset($field) && $field != ''):
                                        echo '<li><img src="' . $field . '" /> </li>';
                                    endif;
                                }
                            }
                            ?>                      
                        </ul>
                    </div>
                </div>
                <!--End Article Slider-->
            </div>
            <div class="grid_12 omega">
                <!--Start Article Details-->
                <div class="article_detail">                
                    <div class="tbl_des">
                        <table class="ar_desc">

                            <?php
                            $custom_meta = get_custom_field();
                            foreach ($custom_meta as $meta) {
                                if ($meta['type'] != 'image_uploader' && $meta['type'] != 'geo_map_input' && $meta['type'] != 'geo_map' && $meta['type'] != 'texteditor') {
                                    $field = $posted[$meta['name']];

                                    if (isset($field) && $field != ''):
                                        echo '<tr>';
                                        echo '<td class="label">' . $meta['title'] . '</td>';
                                        echo '<td>' . $field . '</td>';
                                        echo '</tr>';
                                    endif;
                                    if ($meta['type'] == 'image_uploader' && $meta['name'] != 'geocraft_meta_image1' && $meta['name'] != 'geocraft_meta_image2' && $meta['name'] != 'geocraft_meta_image3' && $meta['name'] != 'geocraft_meta_image4' && $meta['name'] != 'geocraft_meta_image5') {
                                        echo '<tr>';
                                        echo '<td class="label">' . $meta['title'] . '</td>';
                                        echo '<td><img src="' . $field . '" alt="Field Image" /></td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                            ?>   

                        </table>
                    </div>
                </div>
                <!--End Article Details-->
            </div>
        </div>
        <div class="clear"></div>
        <div class="grid_16 alpha">
            <div class="featured_content">            
                <div id="gc_tab" class="tabbed">
                    <ul class="tabnav">
                        <li><a href="#popular"><?php echo S_DESCRIPTION; ?></a></li>
                    </ul>
                    <div id="popular" class="tabdiv">
                        <div class="tab_content">
                            <?php if (isset($posted['geocraft_description'])) { ?>
                                <?php echo $posted['geocraft_description']; ?>
                            <?php } ?>
                        </div>
                    </div>              
                </div>
                <!--/widget-->
                <div class="clear"></div>
                <h2><?php echo S_L_MAP; ?></h2>
                <div style="border:1px solid #ccc;" class="map">
                    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
                    <?php
                    if (isset($posted['geo_latitude'])) {
                        $address_latitude = $posted['geo_latitude'];
                    }
                    if (isset($posted['geo_longitude'])) {
                        $address_longitude = $posted['geo_longitude'];
                    }
                    if (isset($posted['geo_address'])) {
                        $address = $posted['geo_address'];
                    }
                    $scale = 14;
                    $map_type = 'ROADMAP';
                    ?>
                    <script type="text/javascript">
                        /* <![CDATA[ */

                        var basicsetting = {
                            draggable: true
                        };
                        var directionsDisplay = new google.maps.DirectionsRenderer(basicsetting);
                        var directionsService = new google.maps.DirectionsService();
                        var map;
                                                                                             
                        var latLng = new google.maps.LatLng(<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>);
                                                                                             
                        function initialize() {
                                                                                             
                            var myOptions = {
                                zoom: <?php echo $scale; ?>,
                                mapTypeId: google.maps.MapTypeId.<?php echo $map_type; ?>,
                                zoomControl: true,
                                center: latLng	  
                            };
                            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
                            directionsDisplay.setMap(map);
                            directionsDisplay.setPanel(document.getElementById("directionsPanel"));
                                                                                             
                            var image = '<?php echo $catinfo; ?>';
                            var myLatLng = new google.maps.LatLng(<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>);
                            var Marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                                icon: image
                            });
                            var content = '<?php echo $tooltip_message; ?>';
                            infowindow = new google.maps.InfoWindow({
                                content: content
                            });
                                                                                            	
                            google.maps.event.addListener(Marker, 'click', function() {
                                infowindow.open(map,Marker);
                            });
                            google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
                                                                                               
                            });
                        }
                                                                                              
                        function getSelectedTravelMode() {
                            var travelvalue =  document.getElementById('travel-mode-input').value;
                                                                                            	
                            if (travelvalue == 'driving') {
                                travelvalue = google.maps.DirectionsTravelMode.DRIVING;
                            } else if (travelvalue == 'bicycling') {
                                travelvalue = google.maps.DirectionsTravelMode.BICYCLING;
                            } else if (travelvalue == 'walking') {
                                travelvalue = google.maps.DirectionsTravelMode.WALKING;
                            } else {
                                alert('Unsupported travel mode.');
                            }
                            return travelvalue;
                        }
                                                                                              
                        function calcRoute() {
                            var destination_val = document.getElementById('fromAddress').value;
                                                                                             
                            var request = {
                                origin: destination_val,
                                destination: "<?php echo $address_latitude; ?>, <?php echo $address_longitude; ?>",
                                travelMode: google.maps.DirectionsTravelMode.DRIVING
                            };
                            directionsService.route(request, function(response, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    directionsDisplay.setDirections(response);
                                }else {alert('<?php _e('Address not found for:', THEME_SLUG); ?>'+ destination_val);}
                            });
                        }
                                                                                             
                        google.maps.event.addDomListener(window, 'load', initialize);

                        /* ]]> */
                    </script>
                    <div id="map-canvas" style="height:360px;"></div>
                </div>
            </div>
            <div class="clear"></div>

            <p>               
                <input type="button" name="goback" class="goback" value="<?php _e('Go Back', THEME_SLUG) ?>" onclick="history.back()" />  
                <?php
                $get_paypal = get_option('pay_method_paypal');
                if (is_array($get_paypal)):
                    $paymethod = $get_paypal['key'];
                    $paypal_mode_status = $get_paypal['paypal_sandbox'];
                endif;
                $method = '';
                if ($paymethod == 'paypal') {
                    if ($paypal_mode_status == 1)
                        $method = "sandbox";
                    else
                        $method = "paypal";
                }
                ?>
                <input type="hidden" name="paypal_mode" value="<?php if ($method != '') echo $method; ?>"/>
                <input type="submit" class="submit" name="submit" value="Check Out & Publish" />
                <input type="hidden" value="<?php echo base64_encode(serialize($posted)); ?>" name="posted" />                 
            </p>
        </div>  
    </form>


    <?php
}
?>
