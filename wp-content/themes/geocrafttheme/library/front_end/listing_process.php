<?php

function geocraft_place_form_process() {
    
    
    global $post, $posted;
    if (isset($_POST['review_next']) && $_POST['review_next']) {
        $custom_meta = get_custom_field();        
        foreach ($custom_meta as $key => $meta) {
            $field = $meta['name'];
            if (isset($_POST[$field])) {
                $posted[$field] = stripcslashes(trim($_POST[$field]));
            }
        }
    }
    
    
    $errors = new WP_Error();
    if (isset($_POST['review_next']) && $_POST['review_next']) {
        //Get field data
        $cat = $_POST['category'];
        if ($cat) {
            $category = implode(',', $cat);
        }
        $fields = array(         
            'feature_h',
            'feature_c',
            'package_validity',
            'package_validity_per',
            'pkg_free',            
            'pkg_one_time',
            'pkg_recurring',
            'pkg_period_one',
            'pkg_period_two',            
            'pkg_period_three',
            'package_type'
        );
        //Fecth form values
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $posted[$field] = stripcslashes(trim($_POST[$field]));
            }
        }
        $posted['category'] = $category;
        $posted['total_cost'] = $_POST['total_price'];
        //Add billing cycle fields
        if($_POST['billing'] == 1):
        if(isset($_POST['f_period']) && $_POST['f_period'] !=''):
        $posted['f_period'] = $_POST['f_period'];
        endif;
        if(isset($_POST['f_cycle']) && $_POST['f_cycle'] !=''):
        $posted['f_cycle'] = $_POST['f_cycle'];
        endif;
        if(isset($_POST['installment']) && $_POST['installment'] !=''):
        $posted['installment'] = $_POST['installment'];
        endif;
        if(isset($_POST['s_price']) && $_POST['s_price'] !=''):
        $posted['s_price'] = $_POST['s_price'];
        endif;
        if(isset($_POST['s_period']) && $_POST['s_period'] !=''):
        $posted['s_period'] = $_POST['s_period'];
        endif;
        if(isset($_POST['s_cycle']) && $_POST['s_cycle'] !=''):
        $posted['s_cycle'] = $_POST['s_cycle'];
        endif;
        $posted['billing'] = $_POST['billing'];
        endif;
        $posted['package_title'] = $_POST['package_title'];
        if(($posted['package_validity'] == 0 || $posted['package_validity'] == '') && ($posted['package_validity_per'] == '')):
            $posted['package_validity'] = $posted['pkg_free'];
            $posted['package_validity_per'] = $posted['pkg_period_one'];
        endif;
       
        // Check required fields
        $required = array(
            'contact_name',
            'place_title',
            'address',
            'description'
        );
 
    }

    $submit_form_results = array(
        'errors' => $errors,
        'posted' => $posted
    );
    return $submit_form_results;
}

?>
