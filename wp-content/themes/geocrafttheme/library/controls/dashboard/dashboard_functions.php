<?php

function user_listing($pid) {
    global $current_user, $wpdb, $expiry_tbl_name;
    $post_type = POST_TYPE;
    $sql = "SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'publish' OR " . $wpdb->posts . ".post_status = 'pending') ORDER BY  " . $wpdb->posts . ".`ID` DESC";

    $query = $wpdb->query($sql); // Get total of Num rows from the database query
    if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
        $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    } else { // If the pn URL variable is not present force it to be value of page number 1
        $pn = 1;
    }

    $itemsPerPage = 20;

    $lastPage = ceil($query / $itemsPerPage);

    if ($pn < 1) { // If it is less than 1
        $pn = 1; // force if to be 1
    } else if ($pn > $lastPage) { // if it is greater than $lastpage
        $pn = $lastPage; // force it to be $lastpage's value
    }

    $centerPages = "";
    $sub1 = $pn - 1;
    $sub2 = $pn - 2;
    $add1 = $pn + 1;
    $add2 = $pn + 2;
    if ($pn == 1) {
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    } else if ($pn == $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    } else if ($pn > 2 && $pn < ($lastPage - 1)) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
    } else if ($pn > 1 && $pn < $lastPage) {
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
        $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$pid&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
    }

    $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
    $listings = $wpdb->get_results("SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'publish' OR " . $wpdb->posts . ".post_status = 'pending') ORDER BY  " . $wpdb->posts . ".`ID` DESC $limit");
    $paginationDisplay = ""; // Initialize the pagination output variable
    if ($lastPage != "1") {
        $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

        if ($pn != 1) {
            $previous = $pn - 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$previous") . '"> Back</a> ';
        }
        $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
        if ($pn != $lastPage) {
            $nextPage = $pn + 1;
            $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$pid&pn=$nextPage") . '"> Next</a> ';
        }
    }
    ?>


    <h1><?php echo 'Welcome ' . $current_user->user_login . " - Your Listings"; ?></h1>   
    <table id="tblspacer" class="widefat fixed">
        <thead>
            <tr>
                <th scope="col">Listings</th>
    <!--                <th scope="col">Address</th>-->
    <!--                <th scope="col">Categories</th>-->
    <!--                <th scope="col">Tags</th>-->
                <th scope="col">Date</th>
                <th scope="col">Expires</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($listings as $listing):
                $address = get_post_meta($listing->ID, 'geocraft_meta_address', true);
                $categories = get_the_term_list($listing->ID, CUSTOM_CAT_TYPE, '', ' ', '');
                $tags = get_the_term_list($listing->ID, CUSTOM_TAG_TYPE, '', ' ', '');
                $listing_type = get_post_meta($listing->ID, 'geocraft_listing_type', true);
                ?>
                <tr>
                    <td><a href="<?php echo $listing->guid; ?>"><?php echo $listing->post_title; ?></a><br/>
                        <span class="modify"><a href="<?php echo home_url("/?page_id=$pid&action=edit&pid=" . $listing->ID); ?>"><?php echo EDIT; ?></a>&nbsp;|&nbsp;
                            <a href="<?php echo home_url("/?page_id=$pid&action=delete&pid=" . $listing->ID); ?>"><?php echo DELETE; ?></a>&nbsp;|&nbsp;<a target="new" href="<?php echo $listing->guid; ?>"><?php echo VIEW; ?></a></span></td>
        <!--                    <td><?php echo $categories; ?></td>-->
                    <td><?php echo date("n/j/Y", strtotime($listing->post_date)); ?>
                        <br/><?php echo ucwords($listing->post_status); ?></td>
                    <td><?php
        $listing_expiry = get_post_meta($listing->ID, 'gc_listing_duration', true);
        if ($listing_expiry) {
            printf("%s \n", $listing_expiry);
            echo "<br/>";
            printf("\n%s", gc_timeleft(strtotime($listing_expiry)));
        }
                ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><span style="float:left;"><?php echo ITEMS; ?>: <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
    <?php
}

function expired_listings() {
    global $current_user;
    $post_id = $_REQUEST['pid'];
    if ($post_id) {
        $listing_type = get_post_meta($post_id, 'geocraft_listing_type', true);
        if ($listing_type == "free") {
            $renew_response = gc_renew_listing($post_id);
            if ($renew_response == true) {
                $success = '<script type="text/javascript">';
                $success .= 'jQuery(document).ready(function(){';
                $success .= 'alert("Your listing has been renewed");';
                $success .= '});';
                $success .= '</script>';
                echo $success;
            }
        }
    }
    ?>
    <h1><?php echo "Expired Listings"; ?></h1>   
    <table id="tblspacer" class="widefat fixed">
        <thead>
            <tr>
                <th scope="col"><?php echo LISTINGS; ?></th>
    <!--        <th scope="col">Address</th>-->
                <th scope="col"><?php echo CATEGORIES; ?></th>
    <!--        <th scope="col">Tags</th>-->
                <th scope="col"><?php echo ACTION; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb, $expiry_tbl_name;
            $post_type = POST_TYPE;
            $page_id = get_option('geo_dashboard_page');
            $sql = "SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'draft') ORDER BY  " . $wpdb->posts . ".`ID` DESC";

            $query = $wpdb->query($sql); // Get total of Num rows from the database query
            if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
                $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
            } else { // If the pn URL variable is not present force it to be value of page number 1
                $pn = 1;
            }

            $itemsPerPage = 20;

            $lastPage = ceil($query / $itemsPerPage);

            if ($pn < 1) { // If it is less than 1
                $pn = 1; // force if to be 1
            } else if ($pn > $lastPage) { // if it is greater than $lastpage
                $pn = $lastPage; // force it to be $lastpage's value
            }

            $centerPages = "";
            $sub1 = $pn - 1;
            $sub2 = $pn - 2;
            $add1 = $pn + 1;
            $add2 = $pn + 2;
            if ($pn == 1) {
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
            } else if ($pn == $lastPage) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
            $listings = $wpdb->get_results("SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'draft') ORDER BY  " . $wpdb->posts . ".`ID` DESC $limit");
            $paginationDisplay = ""; // Initialize the pagination output variable
            if ($lastPage != "1") {
                $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$previous") . '"> Back</a> ';
                }
                $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=expired&pn=$nextPage") . '"> Next</a> ';
                }
            }


            foreach ($listings as $listing):
                $address = get_post_meta($listing->ID, 'geocraft_meta_address', true);
                $categories = get_the_term_list($listing->ID, CUSTOM_CAT_TYPE, '', ',', '');
                $tags = get_the_term_list($listing->ID, CUSTOM_TAG_TYPE, '', ' ', '');
                ?>
                <tr>
                    <td><a href="<?php echo $listing->guid; ?>"><?php echo $listing->post_title; ?></a><br/>
                        <span class="modify"><a href="<?php echo home_url("/?page_id=$page_id&action=edit&pid=" . $listing->ID); ?>"><?php echo EDIT; ?></a>&nbsp;|&nbsp;
                            <a href="<?php echo $_SERVER['PHP_SELF'] . ("/?page_id=$page_id&action=expired&d=del&pid=" . $listing->ID); ?>"><?php echo DELETE; ?></a></span></td>                    
                    <td><?php echo $categories; ?></td>   
                    <td><a href="<?php echo home_url("/?page_id=$page_id&action=renew&pid=" . $listing->ID); ?>"><?php echo "Renew"; ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><span style="float:left;"><?php echo ITEMS; ?>: <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
    <?php
}

function user_subscription() {
    ?>
    <h1><?php echo "My Subscriptions"; ?></h1>   
    <?php
    if (isset($_REQUEST['uid']) && $_REQUEST['uid'] != '' && isset($_REQUEST['subtype']) && $_REQUEST['subtype'] == 'delete') {
        $id = $_REQUEST['uid'];
        global $wpdb, $inquiry_tbl_name;
        $query = "DELETE FROM $inquiry_tbl_name WHERE ID = $id";
        $wpdb->query($query);
    }
    ?>
    <p><?php echo SUBS_NOTIFY; ?></p>
    <br/>
    <table id="tblspacer" class="widefat fixed">
        <thead>
            <tr>
                <th scope="col"><?php echo LISTING_TITLE; ?></th>
                <th scope="col"><?php echo NAME; ?></th>
                <th scope="col"><?php echo EMAIL; ?></th>
                <th scope="col"><?php echo CONTACT_NUM; ?></th>
                <th scope="col" style="width:125px;"><?php echo MESSAGE; ?></th>
            </tr>
        </thead>
        <?php
        global $current_user;
        $page_id = get_option('geo_dashboard_page');
        $user_ID = $current_user->ID;
        global $wpdb, $inquiry_tbl_name;
        $sql = "SELECT * FROM $inquiry_tbl_name WHERE listing_author = $user_ID";
        $query = $wpdb->query($sql); // Get total of Num rows from the database query  

        if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
            $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
        } else { // If the pn URL variable is not present force it to be value of page number 1
            $pn = 1;
        }

        $itemsPerPage = 20;

        $lastPage = ceil($query / $itemsPerPage);

        if ($pn < 1) { // If it is less than 1
            $pn = 1; // force if to be 1
        } else if ($pn > $lastPage) { // if it is greater than $lastpage
            $pn = $lastPage; // force it to be $lastpage's value
        }

        $centerPages = "";
        $sub1 = $pn - 1;
        $sub2 = $pn - 2;
        $add1 = $pn + 1;
        $add2 = $pn + 2;
        if ($pn == 1) {
            $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
        } else if ($pn == $lastPage) {
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
            $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
        } else if ($pn > 2 && $pn < ($lastPage - 1)) {
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
            $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
        } else if ($pn > 1 && $pn < $lastPage) {
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
            $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
            $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
        }

        $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
        $results = $wpdb->get_results("SELECT * FROM $inquiry_tbl_name $limit");
        $paginationDisplay = ""; // Initialize the pagination output variable
        if ($pn > 1) {
            if ($lastPage != "1") {
                $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$previous") . '"> Back</a> ';
                }
                $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=subscribe&pn=$nextPage") . '"> Next</a> ';
                }
            }
        }
        if ($results):
            ?>
            <tbody id="trans_list">
                <?php
                foreach ($results as $result):
                    if ($user_ID == $result->listing_author) {
                        $listing_type = get_post_meta($result->listing_id, 'geocraft_listing_type', true);
                        if ($listing_type == 'pro') {
                            ?>
                            <tr>
                                <td><?php echo $result->listing_title; ?>
                                    <br/><span class="modify"><a href="<?php echo home_url("/?page_id=$page_id&action=subscribe&subtype=delete&uid=" . $result->ID); ?>"><?php echo DELETE; ?></a></span></td>
                                <td><?php echo $result->user_name; ?></td>
                                <td><a target="_blank" href="mailto:<?php echo $result->email; ?>"><?php echo $result->email; ?></a></td>
                                <td><?php echo $result->phone_no; ?></td>
                                <td><?php echo $result->message; ?></td>                         
                            </tr>
                            <?php
                        }
                    }
                endforeach;
                ?>
            </tbody>
        <?php else: ?>
            <tr>
                <td colspan="5"><?php echo NO_SUBS_FOUND; ?></td>
            </tr>
        <?php endif; ?>
    </table>      
    <div class="paging"><span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
    <?php
}

function edit_profile() {
    global $current_user;
    $page_id = $_REQUEST['page_id'];
    $user_id = $_REQUEST['uid'];
    $action = $_REQUEST['uedit'];
    if (isset($_POST['edit_profile']) && isset($_REQUEST['uid']) && $_REQUEST['uid'] != '' && isset($_REQUEST['uedit']) && $_REQUEST['uedit'] == 1):
        $user_meta = array();
        $user_meta['first_name'] = esc_attr($_POST['fname']);
        $user_meta['last_name'] = esc_attr($_POST['lname']);
        $user_meta['nickname'] = esc_attr($_POST['nname']);
        $user_meta['user_email'] = esc_attr($_POST['email']);
        $user_meta['user_url'] = esc_attr($_POST['website']);
        $user_meta['aim'] = esc_attr($_POST['aim']);
        $user_meta['yim'] = esc_attr($_POST['yahoo']);
        $user_meta['jabber'] = esc_attr($_POST['gtalk']);
        $user_meta['description'] = esc_attr($_POST['abtme']);

        foreach ($user_meta as $meta_key => $meta_value):
            if ($meta_value != ''):
                update_user_meta($user_id, $meta_key, $meta_value);
            endif;
        endforeach;

        /* Update user password. */
        if (!empty($_POST['npass']) && !empty($_POST['passagain'])) {
            if ($_POST['npass'] == $_POST['passagain']) {
                wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['npass'])));
            } else {
                echo "<p class='error'>Password doesn't match!</p>";
            }
        }
    endif;
    ?>
    <h1><?php echo "Update Profile"; ?></h1>
    <div id="user_profile">
        <form method="post" action="<?php echo home_url("/?page_id=$page_id&action=profile&uedit=1&uid=" . $current_user->ID); ?>">
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="user"><?php echo USR_NM; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="user" name="user" readonly="readonly" value="<?php echo get_the_author_meta('user_login', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo UNAME_CANT_CHANGE; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="fname"><?php echo F_NAME; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="fname" name="fname" value="<?php echo get_the_author_meta('first_name', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo F_NM_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="lname"><?php echo L_NAME; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="lname" name="lname" value="<?php echo get_the_author_meta('last_name', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo L_NM_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="nname"><?php echo NICK_NAME; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="nname" name="nname" value="<?php echo get_the_author_meta('nickname', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo NICK_NM_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="email"><?php echo EMAIL; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="email" name="email" value="<?php echo get_the_author_meta('user_email', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo EMAIL_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="website"><?php echo WEBSITE_TEXT; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="website" name="website" value="<?php echo get_the_author_meta('user_url', $current_user->ID); ?>"/>
                    <br/><span class="description"><?php echo WEBSITE_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="aim"><?php echo AIM; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="aim" name="aim" value="<?php echo get_the_author_meta('aim', $current_user->ID); ?>"/>
                    <br/><span class="description"></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="yahoo"><?php echo YAHOO_IM; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="yahoo" name="yahoo" value="<?php echo get_the_author_meta('yim', $current_user->ID); ?>"/>
                    <br/><span class="description"></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="gtalk"><?php echo G_TALK; ?></label>
                </div>
                <div class="field">
                    <input type="text" id="gtalk" name="gtalk" value="<?php echo get_the_author_meta('jabber', $current_user->ID); ?>"/>
                    <br/><span class="description"></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="abtme"><?php echo ABOUT_URS; ?></label>
                </div>
                <div class="field">
                    <textarea id="abtme" name="abtme"><?php echo get_the_author_meta('description', $current_user->ID); ?></textarea>
                    <br/><span class="description"><?php echo ABOUT_URS_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="npass"><?php echo NEW_PW; ?></label>
                </div>
                <div class="field">
                    <input type="password" id="npass" name="npass" value=""/>
                    <br/><span class="description"><?php echo NEW_PW_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="passagain"><?php echo PW_AGAIN; ?></label>
                </div>
                <div class="field">
                    <input type="password" id="passagain" name="passagain" value=""/>
                    <br/><span class="description"><?php echo PW_AGAIN_DES; ?></span>
                </div>
            </div>
            <!--End Row-->
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    &nbsp;
                </div>
                <div class="field">
                    <input type="submit" name="edit_profile" value="<?php echo UPDATE_PROFILE; ?>" />
                </div>
            </div>
            <!--End Row-->
        </form> 
    </div>
    <?php
}

function listing_reviews() {
    if (isset($_REQUEST['cmtaction']) && $_REQUEST['cmtaction'] == 'delete' && isset($_REQUEST['cmtid']) && $_REQUEST['cmtid'] != ''):
        $cmtid = $_REQUEST['cmtid'];
        echo $cmtid;
        global $wpdb;
        //$sql = "DELETE FROM `$wpdb->comments` WHERE `$wpdb->comments`.`comment_ID` = $cmtid";
        wp_delete_comment($cmtid, true);
    endif;
    if (isset($_REQUEST['cmtaction']) && $_REQUEST['cmtaction'] == 'edit' && isset($_REQUEST['cmtid']) && $_REQUEST['cmtid'] != ''):
        $commentarr = get_comment($_REQUEST['cmtid'], ARRAY_A);
        $commentarr = array(
            "comment_ID" => $_REQUEST['cmtid'],
            "comment_approved" => $_REQUEST['cmtstd']
        );
    //wp_update_comment($commentarr);   
    endif;
    ?>
    <h1><?php echo "Your Reviews"; ?></h1>
    <table id="tblspacer" class="widefat fixed">
        <thead>
            <tr>
                <th scope="col"><?php echo AUTHOR; ?></th>
                <th scope="col"><?php echo COMMENT; ?></th>
                <th scope="col"><?php echo RESPONSE_TO; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb, $expiry_tbl_name, $current_user;
            $page_id = $_REQUEST['page_id'];
            $author = $current_user->ID;
            $post_type = POST_TYPE;

            $sql = "SELECT $wpdb->comments.* , $wpdb->posts.* FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID WHERE comment_approved = '1' AND post_status = 'publish' AND post_author = $author AND post_type='$post_type' ORDER BY comment_date_gmt DESC";

            $query = $wpdb->query($sql); // Get total of Num rows from the database query  

            if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
                $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
            } else { // If the pn URL variable is not present force it to be value of page number 1
                $pn = 1;
            }

            $itemsPerPage = 20;

            $lastPage = ceil($query / $itemsPerPage);

            if ($pn < 1) { // If it is less than 1
                $pn = 1; // force if to be 1
            } else if ($pn > $lastPage) { // if it is greater than $lastpage
                $pn = $lastPage; // force it to be $lastpage's value
            }

            $centerPages = "";
            $sub1 = $pn - 1;
            $sub2 = $pn - 2;
            $add1 = $pn + 1;
            $add2 = $pn + 2;
            if ($pn == 1) {
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
            } else if ($pn == $lastPage) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
                $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
                $centerPages .= '&nbsp; <a href="' . home_url("/?page_id=$page_id&action=review&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
            $comments = $wpdb->get_results("SELECT $wpdb->comments.* , $wpdb->posts.* FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID WHERE comment_approved = '1' AND post_status = 'publish' AND post_author = $author AND post_type='$post_type' ORDER BY comment_date_gmt DESC $limit");
            $paginationDisplay = ""; // Initialize the pagination output variable
            if ($lastPage != "1") {
                $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=review&pn=$previous") . '"> Back</a> ';
                }
                $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '&nbsp;  <a href="' . home_url("/?page_id=$page_id&action=review&pn=$nextPage") . '"> Next</a> ';
                }
            }
            foreach ($comments as $comment):
                ?>
                <tr>
                    <td><?php echo $comment->comment_author; ?>
                        <a class="author_email" href="mailto:<?php echo $comment->comment_author_email; ?>"><?php echo $comment->comment_author_email; ?></a>
        <!--                    <span class="modify">
                        <?php if ($comment->comment_approved == 1) { ?>
                                                                                                                                                                                            <a href="<?php echo home_url("/?page_id=$page_id&action=review&cmtstd=0&cmtaction=edit&cmtid=" . $comment->ID); ?>">Unapprove</a><?php } ?><?php if ($comment->comment_approved == 0) { ?><a href="<?php echo home_url("/?page_id=$page_id&action=review&cmtstd=1&cmtaction=edit&cmtid=" . $comment->ID); ?>">Approve</a><?php } ?>&nbsp;|&nbsp;<a href="<?php echo home_url("/?page_id=$page_id&action=review&cmtaction=delete&cmtid=" . $comment->ID); ?>">Delete</a>&nbsp;|&nbsp;<a target="new" href="<?php echo $comment->guid; ?>">View</a></span></td>-->
                    <td><span class="comment_date">Submitted on <?php echo $comment->comment_date; ?> </span><?php echo $comment->comment_content; ?></td>
                    <td><span class="comment_count"><?php echo $comment->comment_count; ?></span><a target="new" href="<?php echo $comment->guid; ?>"><?php echo $comment->post_title; ?></a></td>                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><span style="float:left;"><?php echo ITEMS; ?> <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
    <?php
}

function dashboard_style() {
    ?>
    <style type="text/css">
        .dashboard a{
            color: #0c5b7f;
        }
        .dashboard a:hover{
            color:#309ed1;
        }
        #tblspacer{
            width:100%;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border-width: 1px;
            border: solid 1px #dfdfdf;
            display: table;
            border-collapse: separate;                    
        }
        #tblspacer th{
            background-image: -webkit-linear-gradient(top,#F9F9F9,#ECECEC);
            background-image: linear-gradient(top,#F9F9F9,#ECECEC);
            padding: 7px 7px 8px;
            text-align: left;
            line-height: 1.3em;
            font-size: 14px;
        }
        #tblspacer td{
            padding: 7px 7px 8px;
            border: 1px solid;
            border-left: none;
            border-right: none;
            border-top-color: white;
            border-bottom-color: #DFDFDF;
            background-color: #f9f9f9;
        }
        #tblspacer tr{
            display: table-row;
            border: 1px solid #dfdfdf;
        }
        #tblspacer span.modify{
            font-size: 11px;
            visibility: hidden;
        }
        #tblspacer tr:hover span.modify{
            visibility: visible;
        }
        #tblspacer tr span.modify a:hover{
            color:brown;
        }
        #tblspacer td .author_email,
        #tblspacer td .comment_date{
            display: block;
            font-size: 12px;
        }
        #tblspacer td .comment_count{
            display: block;
            background: url('<?php echo TEMPLATEURL . '/images/comment-icon.png'; ?>') no-repeat 0 3px;;
            text-align:center;
            width:24px;
            height:24px;
            float:right;
            margin-left:8px;
            color:#7b7b7b;
            font-size:10px;
        }
        #author-info ul.navigation{
            list-style-type:none !important;
        }

        #author-info h6{
            margin-bottom:0;
        }
        .sidebar #author-info ul{
            margin-bottom:0;
        }
        #author-info #author-description{
            margin-bottom:20px;
        }
        .sidebar #author-info ul li{
            border-color:#EBEBEB;
        }
        .sidebar #author-info ul li:last-child{
            border:none;
            padding:0;
            margin:0;
        }
        .meta-image{
            margin-bottom:0;
        }
    </style>
    <?php
}

function edit_listing() {
    if (isset($_POST['update'])):
        $post_id = $_REQUEST['pid'];
        $fields = array(
            'place_title',
            'post_status',
            'geocraft_description'
        );
        //Fecth form values
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $posted[$field] = stripcslashes(trim($_POST[$field]));
            }
        }
        if ($posted['post_status'] != '') {
            $post_status = $posted['post_status'];
        } else {
            $post_status = 'pending';
        }
        $posted['category'] = $_POST['category'];
        $posted['tag'] = explode(',', $_POST['geocraft_tag']);
        $listing_data = array();
        $listing_data = array(
            "ID" => $post_id,
            "post_type" => POST_TYPE,
            "post_title" => $posted['place_title'],
            "post_status" => $post_status,
            "post_content" => $posted['geocraft_description'],
            "post_category" => $posted['category'],
            "tags_input" => $posted['tag'],
        );
        $last_postid = wp_update_post($listing_data);

        $custom_meta = get_custom_field();
        if ($custom_meta) {
            foreach ($custom_meta as $meta):
                $field = $meta['name'];
                $posted[$field] = stripcslashes(trim($_POST[$field]));
                update_post_meta($last_postid, $field, $posted[$field]);
            endforeach;
        }
        wp_set_object_terms($last_postid, $posted['category'], $taxonomy = CUSTOM_CAT_TYPE);
        wp_set_object_terms($last_postid, $posted['tag'], $taxonomy = CUSTOM_TAG_TYPE);

    endif;
    ?>

    <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/tiny_mce_init.js"></script>  
    <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/submit_validation.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo LIBRARYURL . 'css/geo_module_style.css'; ?>" />
    <script type="text/javascript" src="<?php echo LIBRARYURL . 'js/ajaxupload.js'; ?>"></script>    
    <?php
    if (file_exists(LIBRARYPATH . 'js/image_upload.php')) {
        require_once(LIBRARYPATH . 'js/image_upload.php');
    }
    ?>
    <?php
    global $wpdb;
    $post_type = POST_TYPE;
    $post_id = $_REQUEST['pid'];
    $page_id = $_REQUEST['page_id'];
    $sql = "SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_type = '$post_type' AND " . $wpdb->posts . ".ID = $post_id";
    $listing = $wpdb->get_row($sql);
    ?>
    <h1><?php echo "Edit Your Listings"; ?></h1>
    <style type="text/css">
        .content_wrapper img{
            max-width: none;
        }
    </style>
    <div id="add_place">
        <form name="listing_edit" id="listing_edit" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data"> 
            <inpput type="hidden" name="postid" value="<?php echo $post_id; ?>"/>
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="list_title"><?php echo LISTING_TITLE; ?><span class="required">*</span></label>
                </div>
                <div class="field">
                    <input type="text" id="list_title" name="place_title" value="<?php if (isset($listing->post_title)) echo $listing->post_title; ?>"/>
                    <br/><span class="description"><?php echo ENTER_LISTING_TITLE; ?></span><br/>
                    <span id="list_title_rr" class="list_title_error"></span>
                </div>
            </div>
            <!--End Row-->          
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label for="category"><?php echo CATEGORY; ?><span class="required">*</span></label>
                </div>
                <div class="field">
                    <script type="text/javascript">
                        function displaychk_frm(){
                            dom = document.forms['listing_edit'];
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
                    <div style="height:100px; width: 290px; overflow-y:scroll; margin-bottom: 15px;">
                        <?php
                        global $wpdb;
                        $cates = array();
                        $cates = wp_get_post_terms($post_id, CUSTOM_CAT_TYPE);
                        $count = count($cates);
                        for ($i = 0; $i <= $count; $i++) {
                            $allc = explode(',', $cates[$i]->term_id);
                            if ($allc[0] != "") {
                                $locat .= $allc[0] . ",";
                            }
                        }
                        $cates = explode(',', $locat);

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
                                ?> value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                                  <?php
                                                  $child = get_term_children($termid, CUSTOM_CAT_TYPE);
                                                  if ($child) {
                                                      echo "<ul class=\"children\">";
                                                      foreach ($child as $child_of) {
                                                          $term = get_term_by('id', $child_of, CUSTOM_CAT_TYPE);
                                                          $termid = $term->term_taxonomy_id;
                                                          $term_tax_id = $term->term_id;
                                                          $name = $term->name;

                                                          $catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='" . $term->term_taxonomy_id . "' and t.term_id = tt.term_id");
                                                          $cp = $catprice->term_price;
                                                          ?>
                                        <li><label><input class="list_category"  <?php
                                      if (in_array($term_tax_id, $cates)) {
                                          echo 'checked="checked"';
                                      }
                                                          ?> type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
                                                          <?php
                                                          $child = get_term_children($term_tax_id, CUSTOM_CAT_TYPE);
                                                          if ($child) {
                                                              echo "<ul class=\"children\">";
                                                              foreach ($child as $child_of) {
                                                                  $term = get_term_by('id', $child_of, CUSTOM_CAT_TYPE);
                                                                  $termid = $term->term_taxonomy_id;
                                                                  $term_tax_id = $term->term_id;
                                                                  $name = $term->name;
                                                                  $cate = get_term_children($term_tax_id, CUSTOM_CAT_TYPE);
                                                                  $catprice = $wpdb->get_row("select * from $wpdb->term_taxonomy tt ,$wpdb->terms t where tt.term_taxonomy_id='" . $term->term_taxonomy_id . "' and t.term_id = tt.term_id");
                                                                  $cp = $catprice->term_price;
                                                                  ?>
                                                <li><label><input class="list_category" <?php
                                      if (in_array($term_tax_id, $cates)) {
                                          echo 'checked="checked"';
                                      }
                                                                  ?> type="checkbox" name="category[]" id="<?php echo $termid; ?>" value="<?php echo $name; ?>" class="checkbox" /><?php echo $name; ?></label></li>
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
            <?php if (get_post_meta($listing->ID, 'geocraft_listing_type', true) == 'pro') { ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="status"><?php echo STATUS; ?></label>
                    </div>
                    <div class="field">
                        <?php $post_status = $listing->post_status; ?>                    
                        <input type="radio" name="post_status" <?php if ($post_status == 'publish') echo 'checked="checked"' ?> value="publish"/><?php echo PUBLISH; ?><br/>                   
                        <input type="radio" name="post_status" <?php if ($post_status == 'pending') echo 'checked="checked"' ?> value="pending"/><?php echo PENDING; ?>
                    </div>
                </div>
                <!--End Row--> 
            <?php } ?>
            <?php
            $custom_meta = get_custom_field();
            global $validation_field;
            foreach ($custom_meta as $key => $meta) {
                $meta_value = get_post_meta($post_id, $meta['name'], true);
                $name = $meta['name'];
                $title = $meta['title'];
                $htmlnm = $meta['htmlvar_name'];
                $default = $meta['default'];
                $type = $meta['type'];
                $description = stripcslashes($meta['description']);
                $option_values = $meta['options'];
                $is_required = '';
                $field = $meta['name'];
                $title = $meta['title'];
                $field_type = "";
                if ($meta['is_require'] == 1) {
                    $validation_field[] = array(
                        'name' => $key,
                        'span' => $key . '_error',
                        'type' => $meta['type'],
                    );
                }
                if ($meta['show_on_listing'] == 1) {
                    if ($field != 'list_title') {
                        if ($type == 'text' || $type == 'geo_map_input') {
                            if ($name == 'geo_latitude' || $name == 'geo_longitude') {
                                $script = 'onblur="changeMap();"';
                                $field_type = "hidden";
                            } else {
                                $script = '';
                                $field_type = 'text';
                            }
                            if ($meta['is_require'] == 1) {
                                $is_required = '<span class="required">*</span>';
                            }
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <?php if ($type !== 'geo_map_input') { ?>
                                        <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                    <?php } ?>
                                </div>
                                <div class="field">
                                    <input type="<?php echo $field_type; ?>" id="<?php if ($name == 'geocraft_meta_email') echo "email"; else echo $name; ?>" name="<?php echo $name; ?>" <?php echo $script; ?> PLACEHOLDER="<?php echo $default; ?>" value="<?php echo $meta_value; ?>"/>
                                    <?php if ($type !== 'geo_map_input') { ?>
                                        <div class="clear"></div>
                                        <span class="description"><?php echo $description; ?></span><br/>
                                        <?php if ($name == 'list_title') { ?>
                                            <span id="list_title_rr" class="list_title_error"></span>
                                        <?php } elseif ($name == 'geocraft_meta_email') { ?>
                                            <span id="email_error" class="email_error"></span>
                                        <?php
                                        }
                                    }
                                    ?>  
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
                        if ($type == 'geo_map') {
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                </div>
                                <div class="field">
                                    <input id="geo_address" type="text" name="<?php echo $name; ?>" value="<?php echo $meta_value; ?>"/>
                                    <br/><span class="description"><?php echo ADDRESS_DES; ?></span><br/>
                                    <span id="geo_address_rr" class="geo_address_error"></span>
                                    <div class="clear"></div>
                    <?php include_once(TEMPLATEPATH . "/library/map/address_map.php"); ?> 
                                    <div class="clear"></div>
                                    <span class="description"><?php echo $description; ?></span>
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
                        if ($type == 'checkbox') {
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title; ?></label>
                                </div>
                                <div class="field">
                                    <input name="<?php echo $name; ?>" id="<?php echo $name; ?>" type="checkbox"  value="<?php echo $meta_value; ?>" />
                                </div>
                            </div>
                            <!--End Row--> 
                            <?php
                        }
                        if ($type == 'radio') {
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
                                            if (trim($meta_value) == trim($options[$i])) {
                                                $seled = 'checked="checked"';
                                            }
                                            echo '<label class="r_lbl">
							<input name="' . $key . '"  id="' . $key . '_' . $chkcounter . '" type="radio" value="' . $options[$i] . '" ' . $seled . '  /> ' . $options[$i] . '
						</label>';
                                        }
                                    }
                                    ?>
                                    <div class="clear"></div>
                                    <span class="description"><?php echo $description; ?></span>
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
                        if ($type == 'date') {
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                </div>
                                <div class="field">
                                    <input name="<?php echo $name; ?>" id="<?php echo $name; ?>" type="checkbox"  value="<?php echo $meta_value; ?>" />
                                </div>
                            </div>
                            <!--End Row--> 
                            <?php
                        }
                        if ($type == 'multicheckbox') {
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
                                                if (in_array($option_values[$i], $meta_value)) {
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
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                </div>
                                <div class="field">
                                    <textarea style="width:250px; height: 100px;" id="<?php echo $name; ?>" name="<?php echo $name; ?>" row="20" col="25"><?php echo $meta_value; ?></textarea>
                                    <span class="description"><?php echo LISTING_DESS; ?></span><br/>
                                    <span id="description_error" class="description_error"></span>
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
                        if ($type == 'textarea') {
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                </div>
                                <div class="field">
                                    <textarea style="width:250px; height: 100px;" id="<?php echo $name; ?>" name="<?php echo $name; ?>" row="20" col="25"><?php echo $meta_value; ?></textarea>
                                    <span class="description"><?php echo $description; ?></span><br/>
                                    <span id="description_error" class="description_error"></span>
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
                            if ($meta_value == $values) {
                                echo 'selected="selected"';
                            }
                                                ?>><?php echo $values; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                    <?php } ?>

                                    </select>
                                    <div class="clear"></div>
                                    <span class="description"><?php echo $description; ?></span>
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
                        $count = 1;
                        if ($type == 'image_uploader') {
                            ?>
                            <!--Start Row-->
                            <div class="form_row">
                                <div class="label">
                                    <label for="<?php echo $name; ?>"><?php echo $title . $is_required; ?></label>
                                </div>
                                <div class="field">
                                    <div style="margin-bottom: 0;">
                                        <input class='of-input' name='<?php echo $name; ?>' id='place_image1_upload' type='text' value='<?php echo $meta_value; ?>' />
                                        <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $name; ?>" value="<?php echo UPLOAD_IMG; ?>" />      
                                            <?php if ($meta_value): ?>
                                                <div class="button image_reset_button" id="reset_<?php echo $key; ?>" title="Remove Image"></div>
                                            <?php else: ?>
                                                <div class="button image_reset_button hide" id="reset_<?php echo $key; ?>" title="Remove Image"></div>
                    <?php endif; ?>
                                        </div>             
                                    </div>
                                    <?php if ($meta_value) : ?>
                                        <img class="hide meta-image" id="image_<?php echo $key; ?>" src="<?php if (isset($meta_value)) echo $meta_value; ?>" width="285" height="250" alt="">
                    <?php endif; ?>
                                    <div class="clear"></div>
                                    <span class="description"><?php echo $description; ?></span>
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
                            $count++;
                        }
                    }
                }
            }
            ?>

            <!--Start Row--> 
            <div class="form_row">
                <div class="label">
                    &nbsp;
                </div>
                <div class="field">                                            
                    <input type="submit" name="update" value="<?php echo UPADTE_LISTING; ?>"/>
                    <div class="clear"></div>
                    <span class="description"><?php echo 'Note: If your listing is pending, After editing, your listing will be queued for approval.'; ?></span>
                </div>
            </div>
            <!--End Row--> 
            <!--Start Row--> 
            <div class="form_row">               
                <label><?php echo UPGRD_TTL; ?></label>                
                <div class="field">                                            
                    <label><?php echo UPGRD; ?><input type="checkbox" id="be_paid" name="be_paid" /></label> <br/>
                    <span class="description"><?php echo UPGRD_DES; ?></span>
                </div>
            </div>
            <!--End Row--> 
        </form> 
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var list_title = jQuery('#list_title').val();
                jQuery('#listing_title').val(list_title);
                jQuery('input:checkbox[name=be_paid]').change(function(){  
                    if(this.checked){
                        jQuery("#upgrade_form").slideDown();
                    } 
                    else{
                        jQuery("#upgrade_form").slideUp();
                    }
                });         
                                                                                              
            });
        </script>
        <?php
        if (file_exists(FRONTENDPATH . 'submit_validation.php')) {
            require_once(FRONTENDPATH . 'submit_validation.php');
        }
        ?>
        <form style="display:none;" name="upgrade_form" id="upgrade_form" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data"> 
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
                        if ($package->package_type != 'pkg_free') {
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
                                        <p class="cost"><span><?php _e('Cost :', THEME_SLUG); ?><?php echo get_option('currency_symbol'); ?><?php echo $package->package_cost; ?></span>                       
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
                                                <span>Valid Upto : <?php echo $package->validity . "&nbsp;" . $valid_to; ?></span>
                <?php } ?>
                                        </p></div></label>

                            </div>               
                            <?php
                            $count++;
                        }
                    endforeach;
                endif;
                ?>
            </div>
            <!--Start Row-->
            <div class="form_row" id="featured">               
                <label><?php echo IS_FEATURED; ?></label>           
                <div class="field">              
                    <label><input id="feature_h"  type="checkbox" name="feature_h"  value="0" /><?php echo F_HOME; ?> <span><?php echo get_option('currency_symbol'); ?></span><span id="fhome">0</span></label>
                    <br/>                
                    <label><input id="feature_c"  type="checkbox" name="feature_c"  value="0" /><?php echo F_CAT; ?><span><?php echo get_option('currency_symbol'); ?></span><span id="fcat">0</span></label>                
                    <br/><br/><span style="display:block;" class="description"><?php echo FEATURED_DES; ?></span>                                 
                </div>
            </div>
            <!--End Row--> 
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    &nbsp;
                </div>
                <div class="field">
                    <span id='loading' style='display:none;'><img src="<?php echo TEMPLATEURL . "/images/loading.gif"; ?>" alt='Loading..' /></span>
                </div> 
            </div>
            <!--Start Row-->
            <div class="form_row">
                <div class="label">
                    <label><?php echo TLT_PRICE; ?> <span class="required">*</span></label>
                </div>
                <div class="field">                  
                    <style type="text/css">.field span{font-size: 14px;}</style>
    <?php echo get_option('currency_symbol'); ?><span id="pkg_price">0</span>&nbsp;+&nbsp;<?php echo get_option('currency_symbol'); ?><span id="feature_price">0</span>&nbsp;=&nbsp;<?php echo get_option('currency_symbol'); ?><span id="result_price">0</span>
                    <input type="hidden" name="listing_title" id="listing_title" value="0"/>
                    <input type="hidden" name="billing" id="billing" value="0"/>
                    <input type="hidden" name="total_price" id="total_price" value="0"/>  
                    <input type="hidden" name="package_title" id="package_title" value=""/>
                    <input type="hidden" name="package_validity" id="package_validity" value=""/>
                    <input type="hidden" name="package_validity_per" id="package_validity_per" value=""/>
                    <input type="hidden" name="package_type" id="package_type" value=""/>
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
                    <br/><br/>
                    <input type="submit" name="upgrade" value="Upgrade"/>
                    <br/><br/>
                    <span class="description"><?php echo "Click updrade button, if you want to upgrade your listing."; ?></span>
                </div>
            </div>
            <!--End Row-->  
        </form>
    </div>
    <?php
    if (isset($_POST['upgrade']) && $_POST['package_type'] != 'pkg_free') {
        global $post, $posted;
        $post_id = $_REQUEST['pid'];
        $posted['total_cost'] = $_POST['total_price'];
        //Add billing cycle fields
        if ($_POST['billing'] == 1):
            if (isset($_POST['f_period']) && $_POST['f_period'] != ''):
                $posted['f_period'] = $_POST['f_period'];
            endif;
            if (isset($_POST['f_cycle']) && $_POST['f_cycle'] != ''):
                $posted['f_cycle'] = $_POST['f_cycle'];
            endif;
            if (isset($_POST['installment']) && $_POST['installment'] != ''):
                $posted['installment'] = $_POST['installment'];
            endif;
            if (isset($_POST['s_price']) && $_POST['s_price'] != ''):
                $posted['s_price'] = $_POST['s_price'];
            endif;
            if (isset($_POST['s_period']) && $_POST['s_period'] != ''):
                $posted['s_period'] = $_POST['s_period'];
            endif;
            if (isset($_POST['s_cycle']) && $_POST['s_cycle'] != ''):
                $posted['s_cycle'] = $_POST['s_cycle'];
            endif;
            $posted['billing'] = $_POST['billing'];
        endif;
        $posted['package_title'] = $_POST['package_title'];
        if (($_POST['package_validity'] == 0 || $_POST['package_validity'] == '') && ($_POST['package_validity_per'] == '')):
            $posted['package_validity'] = $_POST['pkg_free'];
            $posted['package_validity_per'] = $_POST['pkg_period_one'];
        endif;

        global $posted_value;
        $posted_value = array(
            'listing_title' => $_POST['listing_title'],
            'total_cost' => $_POST['total_price'],
            'post_id' => $post_id,
            'p_method' => "paypal",
            'f_period' => $posted['f_period'],
            'f_cycle' => $posted['f_cycle'],
            'installment' => $posted['installment'],
            's_price' => $posted['s_price'],
            's_period' => $posted['s_period'],
            's_cycle' => $posted['s_cycle'],
            'billing' => $posted['billing']
        );

        //Updating expiry table
        if ($_POST['package_validity'] != '' && $_POST['package_validity_per'] != '') {
            global $wpdb, $expiry_tbl_name;
            $validity = $_POST['package_validity'];
            $validity_per = $_POST['package_validity_per'];
            $pkg_type = $_POST['package_type'];
            if ($pkg_type == '') {
                $pkg_type = 'pkg_free';
            }
            $current_date = date("Y-m-d H:i:s");
            $update_array = array(
                'listing_title' => $posted_value['listing_title'],
                'validity' => $validity,
                'validity_per' => $validity_per,
                'package_type' => $pkg_type
            );
            $wpdb->update($expiry_tbl_name, $update_array, array('pid' => $post_id));
        }
        //For featuring category or homepage
        $featured_home = '';
        $featured_cate = '';
        if ($_POST['feature_h']) {
            $featured_home = 'on';
        }
        if ($_POST['feature_c']) {
            $featured_cate = 'on';
        }
        $listing_meta = array(
            'geocraft_f_checkbox1' => esc_attr($featured_home),
            'geocraft_f_checkbox2' => esc_attr($featured_cate)
        );
        if ($listing_meta) {
            foreach ($listing_meta as $key => $meta):
                update_post_meta($post_id, $key, $meta);
            endforeach;
        }
        //Apling payment api
        if (isset($_POST['upgrade'])) {
            if (isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'paypal'):
                if (file_exists(LIBRARYPATH . "getway/paypal/paypal_response.php")):
                    global $user_ID, $post, $posted, $wpdb, $posted_value;
                    $paypalamount = $posted_value['total_cost'];
                    $post_title = $posted_value['listing_title'];
                    $paymentOpts = get_payment_optins1('paypal');
                    $merchantid = $paymentOpts['merchantid'];
                    $returnUrl = $paymentOpts['returnUrl'];
                    $cancel_return = $paymentOpts['cancel_return'];
                    $notify_url = $paymentOpts['notify_url'];
                    $currency_code = get_option('currency_code');
                    $return_page_id = get_option('geo_notify_page');
                    $post_id = $posted_value['post_id'];
                    $pay_method = $_REQUEST['pay_method'];
                    $returnUrl = site_url("?page_id=$return_page_id&ptype=pstatus&post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
                    $is_recurring = $paymentOpts['is_recurring'];
                    $f_period = $posted_value['f_period'];
                    $f_cycle = $posted_value['f_cycle'];
                    $installment = $posted_value['installment'];
                    $s_price = $posted_value['s_price'];
                    $s_period = $posted_value['s_period'];
                    $s_cycle = $posted_value['s_cycle'];
                    $billing = $posted_value['billing'];
                    $recurring = $posted_value['billing'];
                    $billing_time = get_billingtime();
                    ?> 
                    <form name="frm_payment_method" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="business" value="<?php echo $merchantid; ?>" />
                        <!-- Instant Payment Notification & Return Page Details -->
                        <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
                        <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
                        <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
                        <input type="hidden" name="rm" value="2" />
                        <!-- Configures Basic Checkout Fields -->
                        <input type="hidden" name="lc" value="" />
                        <input type="hidden" name="no_shipping" value="1" />
                        <input type="hidden" name="no_note" value="1" />
                       <!-- <input type="hidden" name="custom" value="localhost" />-->
                        <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
                        <input type="hidden" name="page_style" value="paypal" />
                        <input type="hidden" name="charset" value="utf-8" />
                        <input type="hidden" name="item_name" value="<?php echo $post_title; ?>" />
                    <?php if ($recurring == 1) { ?>
                                            <!-- <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />-->
                                            <!-- <input type="hidden" name="item_number" value="2" />-->
                            <input type="hidden" name="cmd" value="_xclick-subscriptions" />
                            <!-- Customizes Prices, Payments & Billing Cycle -->
                            <input type="hidden" name="src" value="1" />
                            <!-- Value for each installments -->
                            <?php if ($billing_time[0]->rebill_time == 1) { ?>
                                <input type="hidden" name="srt" value="<?php echo $installment; ?>" /> 
                        <?php } ?>
                        <!-- <input type="hidden" name="sra" value="5" />-->
                            <!-- First Price -->
                            <input type="hidden" name="a1" value="<?php echo $paypalamount; ?>" />
                            <!-- First Period -->
                            <input type="hidden" name="p1" value="<?php echo $f_period; ?>" />
                            <!-- First Period Cycle e.g: Days,Months-->
                            <input type="hidden" name="t1" value="<?php echo $f_cycle; ?>" />
                            <!-- Second Period Price-->
                            <input type="hidden" name="a3" value="<?php echo $s_price; ?>" />
                            <!-- Second Period -->
                            <input type="hidden" name="p3" value="<?php echo $s_period; ?>" />
                            <!-- Second Period Cycle -->
                            <input type="hidden" name="t3" value="<?php echo $s_cycle; ?>" />
                            <!-- Displays The PayPal® Image Button -->
                    <?php } else { ?>
                            <input type="hidden" value="_xclick" name="cmd"/>
                            <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
                    <?php } ?>
                    </form>             
                    <script>
                        setTimeout("document.frm_payment_method.submit()",10); 
                    </script> <?php
                endif; elseif (isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'sandbox'):
                if (file_exists(LIBRARYPATH . "getway/paypal/paypal_sandbox.php")):
                    global $user_ID, $post, $posted, $wpdb, $posted_value;
                    $paypalamount = $posted_value['total_cost'];
                    $post_title = $posted_value['listing_title'];
                    $paymentOpts = get_payment_optins1('paypal');
                    $merchantid = $paymentOpts['merchantid'];
                    $returnUrl = $paymentOpts['returnUrl'];
                    $cancel_return = $paymentOpts['cancel_return'];
                    $notify_url = $paymentOpts['notify_url'];
                    $currency_code = get_option('currency_code');
                    $return_page_id = get_option('geo_notify_page');
                    $post_id = $posted_value['post_id'];
                    $pay_method = $_REQUEST['pay_method'];
                    $returnUrl = site_url("?page_id=$return_page_id&ptype=pstatus&post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
                    $is_recurring = $paymentOpts['is_recurring'];
                    $f_period = $posted_value['f_period'];
                    $f_cycle = $posted_value['f_cycle'];
                    $installment = $posted_value['installment'];
                    $s_price = $posted_value['s_price'];
                    $s_period = $posted_value['s_period'];
                    $s_cycle = $posted_value['s_cycle'];
                    $billing = $posted_value['billing'];
                    $recurring = $posted_value['billing'];
                    ?>

                    <form name="paypal_sandbox" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="padding: 0; margin: 0;">
                        <input type="hidden" name="business" value="<?php echo $merchantid; ?>" />
                        <!-- Instant Payment Notification & Return Page Details -->
                        <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
                        <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
                        <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
                        <input type="hidden" name="rm" value="2" />
                        <!-- Configures Basic Checkout Fields -->
                        <input type="hidden" name="lc" value="" />
                        <input type="hidden" name="no_shipping" value="1" />
                        <input type="hidden" name="no_note" value="1" />
                       <!-- <input type="hidden" name="custom" value="localhost" />-->
                        <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
                        <input type="hidden" name="page_style" value="paypal" />
                        <input type="hidden" name="charset" value="utf-8" />
                        <input type="hidden" name="item_name" value="<?php echo $post_title; ?>" />
                        <?php if ($recurring == 1) { ?>
                            <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
                           <!-- <input type="hidden" name="item_number" value="2" />-->
                            <input type="hidden" name="cmd" value="_xclick-subscriptions" />
                            <!-- Customizes Prices, Payments & Billing Cycle -->
                           <!-- <input type="hidden" name="src" value="52" />-->
                            <!-- Value for each installments -->
                            <input type="hidden" name="srt" value="<?php echo $installment; ?>" /> 
                           <!-- <input type="hidden" name="sra" value="5" />-->
                            <!-- First Price -->
                            <input type="hidden" name="a1" value="<?php echo $paypalamount; ?>" />
                            <!-- First Period -->
                            <input type="hidden" name="p1" value="<?php echo $f_period; ?>" />
                            <!-- First Period Cycle e.g: Days,Months-->
                            <input type="hidden" name="t1" value="<?php echo $f_cycle; ?>" />
                            <!-- Second Period Price-->
                            <input type="hidden" name="a3" value="<?php echo $s_price; ?>" />
                            <!-- Second Period -->
                            <input type="hidden" name="p3" value="<?php echo $s_period; ?>" />
                            <!-- Second Period Cycle -->
                            <input type="hidden" name="t3" value="<?php echo $s_cycle; ?>" />
                            <!-- Displays The PayPal® Image Button -->
                        <?php } else { ?>
                            <input type="hidden" value="_xclick" name="cmd"/>
                            <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
                        <?php } ?>
                    </form>              
                    <script>
                        setTimeout("document.paypal_sandbox.submit()",10); 
                    </script>
                    <?php
                endif;

            endif;
        }
    }
}

function get_payment_optins1($method) {
    global $wpdb;
    $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_$method'";
    $paymentinfo = $wpdb->get_results($paymentsql);
    if ($paymentinfo) {
        foreach ($paymentinfo as $paymentinfoObj) {
            $option_value = unserialize($paymentinfoObj->option_value);
            $paymentOpts = $option_value['payOpts'];
            $optReturnarr = array();
            for ($i = 0; $i < count($paymentOpts); $i++) {
                $optReturnarr[$paymentOpts[$i]['fieldname']] = $paymentOpts[$i]['value'];
            }
            return $optReturnarr;
        }
    }
}
?> 