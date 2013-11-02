<?php
/* to dele the payment options */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class wp_list_payment_options extends WP_List_Table 
{
	/***** FETCH ALL THE DATA AND STORE THEM IN AN ARRAY *****
	* Call a function that will return all the data in an array and we will assign that result to a variable $payment_options. FIRST OF ALL WE WILL FETCH DATA FROM POST META TABLE STORE THEM IN AN ARRAY $payment_options */
	function templ_get_pay_option_data($payment_method = '')
	{
		global $wpdb;
		$paymentsql = "select * from $wpdb->options where option_name like '%payment_method_$payment_method%'";
		$paymentInfo = $wpdb->get_row($paymentsql);
		if(!isset($paymentInfo )){ $paymentInfo  = array();}
		if(isset($paymentInfo->option_id)){
			$option_id = $paymentInfo->option_id; 
		}else{ $option_id ='<span class="error">Not installed</span>';}
		if(isset($post_id))
		{
			$amount = get_post_meta($post_id,'package_amount',true);
		}
		if(isset($paymentInfo->display_order))
		{
			$display_order = $paymentInfo->display_order; /* display order */
		}else{ $display_order = '-'; }
		if((isset($paymentInfo->isactive) && $paymentInfo->isactive !='') || (isset($paymentInfo->autoload) && $paymentInfo->autoload == 'yes')){ $status = __("Yes",DOMAIN); }else{ $status = __("No",DOMAIN); } /* display satus */
		/* show install/uninstall links */
		if(get_option('payment_method_'.$payment_method)){
				$action = '<a href="'.site_url('/wp-admin/admin.php?page=monetization&tab=payment_options&uninstall='.$payment_method).'">'. __("Deactivate",DOMAIN).'</a>';
		}else{
				$action = '<a href="'.site_url('/wp-admin/admin.php?page=monetization&tab=payment_options&install='.$payment_method).'">'. __("Activate",DOMAIN).'</a>';
		}
		
			/*$a = $this->get_table_classes();
			$a[] = $payment_method;
			echo "<pre>";print_r($a);echo "</pre>";*/
		
		$meta_data = array(
			'ID'	=> $option_id,
			'title'	=> $payment_method,
			'status' 	=> $status,
			'display_order' => $display_order,
			'action' => $action
			);
		return $meta_data;
	}
	/* fetch all the payment options */
	function payment_options()
	{
		if(isset($paymentOptionArray) && is_array($paymentOptionArray)){
		ksort($paymentOptionArray); }
		$no_include = array('.svn');
		if ($handle = opendir(ABSPATH . 'wp-content/plugins')) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) 
			{
				if($file=='.' || $file=='..')
				{
			
				}elseif(!in_array($file,$no_include))
				{
					$templatic_payment_option = explode('-',$file);
					if($templatic_payment_option[0] == 'Tevolution')
					{
						$templatic_payment_option_name = $templatic_payment_option[1];
						if(file_exists(ABSPATH.'wp-content/plugins/'.$file.'/'.$file.'.php') && is_plugin_active($file.'/'.$file.'.php'))
							$payment_options[] = $this->templ_get_pay_option_data($templatic_payment_option_name);
					}
				}
			}
		}
		if ($handle = opendir(plugin_dir_path( __FILE__ ).'payment')) {
				/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle))) 
				{
					if($file=='.' || $file=='..')
					{
				
					}elseif(!in_array($file,$no_include))
					{
						$payment_options[] = $this->templ_get_pay_option_data($file);
					}
				}
			}
		return $payment_options;
	}
	/* EOF - FETCH PACKAGE DATA */
	
	/* DEFINE THE COLUMNS FOR THE TABLE */
	function get_columns()
	{
		$columns = array(
			'ID' =>  __('ID',DOMAIN),
			'title' =>  __('Payment method',DOMAIN),
			'status' => 'status',
			'display_order' => __('Display order',DOMAIN),
			'action' => __('Action',DOMAIN)
			);
		return $columns;
	}
	
    
	function prepare_items()
	{
		//$per_page = 3; /* NUMBER OF POSTS PER PAGE */
		$per_page = $this->get_items_per_page('package_per_page', 10);
		$columns = $this->get_columns(); /* CALL FUNCTION TO GET THE COLUMNS */
        $hidden = array();
		$sortable = array();
        $sortable = $this->get_sortable_columns(); /* GET THE SORTABLE COLUMNS */
		$this->_column_headers = array($columns, $hidden, $sortable);
		$action = $this->current_action();
		$data = $this->payment_options(); /* RETIRIVE THE PACKAGE DATA */
		
		/* FUNCTION THAT SORTS THE COLUMNS */
		function usort_reorder($a,$b)
		{
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort( $data, 'usort_reorder');
		
		$current_page = $this->get_pagenum(); /* GET THE PAGINATION */
		$total_items = count($data); /* CALCULATE THE TOTAL ITEMS */
		$this->found_data = array_slice($data,(($current_page-1)*$per_page),$per_page); /* TRIM DATA FOR PAGINATION*/
		$this->items = $this->found_data; /* ASSIGN SORTED DATA TO ITEMS TO BE USED ELSEWHERE IN CLASS */
		/* REGISTER PAGINATION OPTIONS */
		
		$this->set_pagination_args( array(
            'total_items' => $total_items,      //WE have to calculate the total number of items
            'per_page'    => $per_page         //WE have to determine how many items to show on a page
        ) );
	}
	
	/* To avoid the need to create a method for each column there is column_default that will process any column for which no special method is defined */
	function column_default( $item, $column_name )
	{
		switch( $column_name )
		{
			case 'ID':
			case 'title':
			case 'status':
			case 'display_order':
			case 'action':

			return $item[ $column_name ];
			default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/* DEFINE THE COLUMNS TO BE SORTED */
	function get_sortable_columns()
	{
		$sortable_columns = array(
			'status' => array('status',true)
			);
		return $sortable_columns;
	}
	
	function column_title($item)
	{
		if(strtolower($item['status']) == strtolower('yes'))
		{
			$actions = array(
				'settings' => sprintf('<a href="?page=%s&action=%s&id=%s&tab=%s&payact=%s#option_payment">Settings</a>',$_REQUEST['page'],'settings',$item['ID'],'payment_options','setting')
				);
		}else{
			$actions = array();
		}
		
		return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions , $always_visible = false) );
	}
	
	
	function column_cb($item)
	{ 
		return sprintf('<input type="checkbox" name="op_id[]" value="%s" />', $item['ID']);
	}
} ?>