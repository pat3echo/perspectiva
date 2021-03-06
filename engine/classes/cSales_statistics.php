<?php
	/**
	 * sales_statistics Class
	 *
	 * @used in  				sales_statistics Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	sales_statistics
	 */

	/*
	|--------------------------------------------------------------------------
	| sales_statistics Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSales_statistics{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'sales_statistics';
        
		private $order_table = 'order';
		private $product_table = 'product';
		private $purchased_product_table = 'purchased_product';
		
		private $associated_cache_keys = array(
			'sales_statistics',
		);
		
		private $table_fields = array(
			'request_id' => 'sales_statistics001',
			'request_amount' => 'sales_statistics002',
			'request_status' => 'sales_statistics003',
			'merchant_id' => 'sales_statistics004',
		);
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
		}
	
		function sales_statistics(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'sales_statistics_manager':
				$returned_value = $this->_sales_statistics_manager();
			break;
			case 'get_total_sales_data_by_merchant':
				$returned_value = $this->_get_total_sales_data_by_merchant();
			break;
			}
			
			return $returned_value;
		}
		
        private function _get_total_sales_data_by_merchant(){
            
            $merchant_sales_data = array(
                'sales_data' => array(),
                'total_amount' => 0,
                'total_units' => 0,
            );
            
            if( isset( $this->class_settings['merchant_id'] ) && $this->class_settings['merchant_id'] ){
                //get order details with status equals paid
                $cl = new cOrder();
                $cl->class_settings = $this->class_settings;
                $cl->class_settings['action_to_perform'] = 'get_table_fields';
                $order_fields = $cl->order();
                
                $cl = new cProduct();
                $cl->class_settings = $this->class_settings;
                $cl->class_settings['action_to_perform'] = 'get_table_fields';
                $product_fields = $cl->product();
                
                $cl = new cPurchased_product();
                $cl->class_settings = $this->class_settings;
                $cl->class_settings['action_to_perform'] = 'get_table_fields';
                $purchased_product_fields = $cl->purchased_product();
                
                $select = "";
			
                foreach( $order_fields as $key => $val ){
                    if( $select )$select .= ", `".$this->order_table."`.`".$val."` as '".$key."'";
                    else $select = "`".$this->order_table."`.`id`, `".$this->order_table."`.`creation_date`, `".$this->order_table."`.`modification_date`, `".$this->order_table."`.`".$val."` as '".$key."'";
                }
            
                $query = "SELECT ".$select." FROM  `".$this->class_settings['database_name']."`.`".$this->order_table."`, `".$this->class_settings['database_name']."`.`".$this->product_table."`, `".$this->class_settings['database_name']."`.`".$this->purchased_product_table."` 
                WHERE 
                `".$this->product_table."`.`".$product_fields['merchant_id']."` = '".$this->class_settings['merchant_id']."' 
                AND  `".$this->purchased_product_table."`.`".$purchased_product_fields['products']."` REGEXP `".$this->product_table."`.`id`
                AND `".$this->purchased_product_table."`.`id` = `".$this->order_table."`.`".$order_fields['item_id']."` 
                AND `".$this->order_table."`.`".$order_fields['payment_status']."` = 'paid'
                AND `".$this->order_table."`.`record_status` = '1' AND `".$this->purchased_product_table."`.`record_status` = '1' ";
                
                $query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->order_table ),
				);
				$sql_result = execute_sql_query( $query_settings );
				
				if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
                    foreach( $sql_result as $sval){
                        $order_data = json_decode( $sval['ordered_items_data'], true );
                        
                        foreach( $order_data as $key => $val ){
                            if( isset( $val['merchant_id'] ) && $val['merchant_id'] == $this->class_settings['merchant_id'] ){
                                $val['id'] = $key;
                                
                                if( isset( $val['pricing_data']['total_listing_price'] ) )
                                    $merchant_sales_data['total_amount'] += ( $val['pricing_data']['total_listing_price'] * $val['units'] );
                                    
                                $merchant_sales_data['total_units'] += $val['units'];
                                    
                                //$merchant_sales_data['sales_data'][] = $val;
              
                            }
                        }
                    }
                }
            }
            
            return $merchant_sales_data;
        }
        
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => $returning_html_data[ 'record_id' ],
			);
		}

		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'csales_statistics.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
			}
			
			return $returning_html_data;
		}
        
    }
?>