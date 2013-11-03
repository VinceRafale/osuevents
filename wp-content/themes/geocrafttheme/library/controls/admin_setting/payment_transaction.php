<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function geocraft_transaction_opt() {
    ?>
    <div class="wrap" id="of_container" style="width:1050px;">
        <div id="header">
            <div class="logo">
                <h2><?php echo TRANSACTION; ?></h2>
            </div>
            <a href="http://www.inkthemes.com" target="_new">
                <div class="icon-option"> </div>
            </a>
            <div class="clear"></div>
        </div>
        <table id="tblspacer" class="widefat fixed">

            <thead>
                <tr>
                    <th scope="col"><?php echo ID; ?></th>
                    <th scope="col"><?php echo LISTING_ID; ?></th>
                    <th scope="col"><?php echo USER_NAME; ?></th>
                    <th scope="col" style="width: 210px !important;"><?php echo PAYER_EMAIL; ?></th>
                    <th scope="col"><?php echo LISTING_TITLE; ?></th>                    
                    <th scope="col" style="width:125px;"><?php echo TRANSACTION_ID; ?></th>                    
                    <th scope="col"><?php echo PAYMENT_STATUS; ?></th>
                    <th scope="col"><?php echo TOTAL_AMT; ?></th>
                    <th scope="col" style="width:150px;"><?php echo PAID_DATE; ?></th>
                </tr>
            </thead>
            <?php
            global $wpdb, $transection_table_name;
            $query = "SELECT * FROM $transection_table_name ORDER BY trans_id ASC ";
            $results = $wpdb->get_results($query);
            if ($results):
                ?>
                <tbody id="trans_list">
                    <?php
                    foreach ($results as $result):
                        ?>
                        <tr>
                            <td><?php echo $result->trans_id; ?></td>
                            <td><?php echo $result->post_id; ?></td>
                            <td><?php echo $result->user_name; ?></td>
                            <td><a target="_blank" href="mailto:<?php echo $result->pay_email; ?>"><?php echo $result->pay_email; ?></a></td>
                            <td><?php echo $result->post_title; ?></td>                            
                            <td><?php echo $result->paypal_transection_id; ?></td>                 
                            <td><?php if ($result->status == 1) echo 'Paid'; if ($result->status == 0) echo 'Pending'; ?></td>
                            <td><?php echo $result->payable_amt; ?></td>
                            <td><?php echo mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $result->payment_date); ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            <?php else: ?>
                <tr>
                    <td colspan="7"><?php echo NO_TRANS_FOUND; ?></td>
                </tr>
            <?php endif; ?>

        </table> <!-- this is ok -->

    </div>
    <?php
}
?>
