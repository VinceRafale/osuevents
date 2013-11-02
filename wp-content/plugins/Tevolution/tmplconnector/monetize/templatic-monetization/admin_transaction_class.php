<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class wp_list_transaction extends WP_List_Table 
{
	/***** FETCH ALL THE DATA AND STORE THEM IN AN ARRAY *****
	* Call a function that will return all the data in an array and we will assign that result to a variable $transaction_data. FIRST OF ALL WE WILL FETCH DATA FROM TRANSACTION TABLE STORE THEM IN AN ARRAY $transaction_data */
	
	/* fetch all the transaction data */
	function fetch_transction()
	{
		global $post,$wpdb,$transection_db_table_name;
		$post_table = $wpdb->prefix."posts";
		if(@$_REQUEST['post_types'])
			$select_post_table = " , $post_table as p ";
		$transsql_select = "select * ";
		$transsql_count = "select count(t.trans_id) ";
		$transsql_from= " from $transection_db_table_name as t $select_post_table";
		$transsql_conditions= " where (t.status=1 OR  t.status=0 OR  t.status=2) ";
		if(@$_REQUEST['id'])
		{
			$id = @$_REQUEST['id'];
			$transsql_conditions .= " and t.post_id = $id";
		}
		if(@$_REQUEST['srch_orderno'])
		{
			$srch_orderno = @$_REQUEST['srch_orderno'];
			$transsql_conditions .= " and t.trans_id = $srch_orderno";
		}
		if(@$_REQUEST['srch_name'])
		{
			$srch_name = @$_REQUEST['srch_name'];
			$transsql_conditions .= " and (t.billing_name like '%$srch_name%' OR t.pay_email like '%$srch_name%')";
		}
		if(@$_REQUEST['srch_payment'])
		{
			$srch_payment = @$_REQUEST['srch_payment'];
			$transsql_conditions .= " and t.payment_method like \"$srch_payment\"";
		}
		
		if(@$_REQUEST['srch_payid'])
		{
			$srch_payid = @$_REQUEST['srch_payid'];
			$transsql_conditions .= " and t.paypal_transection_id like '%$srch_payid%'";
		}
		
		if(@$_REQUEST['post_types'])
		{
			$post_type = @$_REQUEST['post_types'];
			$transsql_conditions .= " and p.post_type like '%$post_type%' and p.ID = t.post_id";
		}
		//$transsql_limit=" order by t.trans_id desc limit $strtlimit,$transrecordsperpage";
		
		$_SESSION['query_string'] = $transsql_select.$transsql_from.$transsql_conditions;
		$transsql_select.$transsql_from.$transsql_conditions;
		$transinfo_count = $wpdb->get_results($transsql_select.$transsql_from.$transsql_conditions);

		$transinfo = $wpdb->get_results($transsql_select.$transsql_from.$transsql_conditions);
		$trans_total_pages = count($transinfo_count);
		$tmpdata = get_option('templatic_settings');
		if($transinfo)
		{ 
			 foreach($transinfo as $transinfoObj) :
			 	$post = get_post($transinfoObj->post_id);
				$post_type = $post->post_type;
				$post_type_object = get_post_type_object($post_type);
				$post_type_label = $post_type_object->labels->name;
				$color_taxonomy = 'trans_post_type_colour_'.$post_type;
				$color_taxonomy_value = '';
				if(isset($tmpdata[$color_taxonomy]) && $tmpdata[$color_taxonomy]!= '') { $color_taxonomy_value = $tmpdata[$color_taxonomy]; } 
				$transaction_data[] =  array(
										'ID'				=> $transinfoObj->trans_id,
										'post_id' 			=> $transinfoObj->post_id,
										'title'				=> '<a href="'.site_url().'/wp-admin/admin.php?page=transcation&action=edit&trans_id='.$transinfoObj->trans_id.'">'.$transinfoObj->billing_name.'</a>',
										'post_title'		=> '<a href="'.site_url().'/wp-admin/post.php?post='.$transinfoObj->post_id.'&action=edit">'.$transinfoObj->post_title.'</a>',
										'email' 			=> $transinfoObj->pay_email,
										'payment_method' 	=> $transinfoObj->payment_method,
										'amount' 			=> fetch_currency_with_position(number_format($transinfoObj->payable_amt,2)),
										'post_type'			=> '<label style="color:'.$color_taxonomy_value.'">'.$post_type_label.'<label>',
										'status' 			=> tmpl_get_transaction_status($transinfoObj->trans_id,$transinfoObj->post_id)
										);
			endforeach;
		}
		return $transaction_data;
	}
	/* EOF - FETCH TRANSACTION DATA */
	
	/* DEFINE THE COLUMNS FOR THE TABLE */
	function get_columns()
	{
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' =>  __('Name',DOMAIN),
			'post_title' =>  __('Post title',DOMAIN),
			'email' => __('Email',DOMAIN),
			'payment_method' => __('Payment method',DOMAIN),
			'amount' => __('Amount',DOMAIN),
			'post_type' => __('Post Type',DOMAIN),
			'status' =>  __('status',DOMAIN)
			);
		return $columns;
	}
	/**/
	function process_bulk_action()
	{ 
		//Detect when a bulk action is being triggered...
		if( 'pending' === $this->current_action() )
		{
			global $post,$wpdb,$transection_db_table_name;
			$cids = $_REQUEST['cf'];
			foreach( $cids as $cid )
			{
				$cid = explode(",",$cid);
				$my_post['ID'] = $cid[1];
				$my_post['post_status'] = 'draft';
				wp_update_post( $my_post );
				$trans_status = $wpdb->query("update $transection_db_table_name SET status = 0 where trans_id = '".$cid[0]."'");
			}
			$url = site_url().'/wp-admin/admin.php';
			?>
			
			
			<input type="hidden" value="transcation" name="page"><input type="hidden" value="delsuccess" name="usermetamsg">
			
			<script type="text/javascript">document.frm_transaction.submit();</script>
	<?php		
		}
		elseif( 'confirm' === $this->current_action() )
		{
			global $post,$wpdb,$transection_db_table_name;
			$cids = $_REQUEST['cf'];
			foreach( $cids as $cid )
			{
				$cid = explode(",",$cid);
				$my_post['ID'] = $cid[1];
				$my_post['post_status'] = 'publish';
				wp_update_post( $my_post );
				$trans_status = $wpdb->query("update $transection_db_table_name SET status = 1 where trans_id = '".$cid[0]."'");
			}
			$url = site_url().'/wp-admin/admin.php';
			?>
			
			
			<input type="hidden" value="transcation" name="page"><input type="hidden" value="delsuccess" name="usermetamsg">
			
			<script type="text/javascript">document.frm_transaction.submit();</script>
	<?php		
		}
	}
        
	function prepare_items()
	{
		$per_page = $this->get_items_per_page('transaction_per_page', 10);
		$columns = $this->get_columns(); /* CALL FUNCTION TO GET THE COLUMNS */
		
        $hidden = array();
		$sortable = array();
        $sortable = $this->get_sortable_columns(); /* GET THE SORTABLE COLUMNS */
		
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$this->process_bulk_action(); /* FUNCTION TO PROCESS THE BULK ACTIONS */
		//$action = $this->current_action();
		$data = $this->fetch_transction(); /* RETIRIVE THE TRANSACTION DATA */
		
		/* FUNCTION THAT SORTS THE COLUMNS */
		function usort_reorder($a,$b)
		{
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='desc') ? $result : -$result; //Send final sort direction to usort
        }
		if(is_array($data))
       		usort( $data, 'usort_reorder');
		
		$current_page = $this->get_pagenum(); /* GET THE PAGINATION */
		$total_items = count($data); /* CALCULATE THE TOTAL ITEMS */
		if(is_array($data))
			$this->found_data = array_slice($data,(($current_page-1)*$per_page),$per_page); /* TRIM DATA FOR PAGINATION*/
		$this->items = $this->found_data; /* ASSIGN SORTED DATA TO ITEMS TO BE USED ELSEWHERE IN CLASS */
		
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
			case 'post_id':
			case 'title':
			case 'post_title':
			case 'email':
			case 'payment_method':
			case 'amount':
			case 'post_type':
			case 'status':

			return $item[ $column_name ];
			default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/* DEFINE THE COLUMNS TO BE SORTED */
	function get_sortable_columns()
	{
		$sortable_columns = array(
			'status' => array('status',true),
			'title' => array('title',true),
			'post_title'=>array('post_title',true),
			'post_type'=>array('post_type',true),
			'payment_method' => array('payment_method',true)
			);
		return $sortable_columns;
	}
	
	function get_bulk_actions()
	{
		$actions = array(
			'pending' => 'Pending',
			'confirm' => 'Confirmed'
			);
		return $actions;
	}
	
	function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="cf[]" value="%2s" />', $item['ID'].",".$item['post_id']
			);
	}
} ?>