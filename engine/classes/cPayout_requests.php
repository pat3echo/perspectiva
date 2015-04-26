<?php
	/**
	 * payout_requests Class
	 *
	 * @used in  				payout_requests Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	payout_requests
	 */

	/*
	|--------------------------------------------------------------------------
	| payout_requests Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cPayout_requests{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'payout_requests';
		
		private $associated_cache_keys = array(
			'payout_requests',
		);
		
		private $table_fields = array(
			'request_id' => 'payout_requests001',
			'request_amount' => 'payout_requests002',
			'request_status' => 'payout_requests003',
			'merchant_id' => 'payout_requests004',
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
	
		function payout_requests(){
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
			case 'payout_requests_manager':
				$returned_value = $this->_payout_requests_manager();
			break;
			case 'site_save_payout_requests':
				$returned_value = $this->_save_site_changes();
			break;
			}
			
			return $returned_value;
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
				
				$err->class_that_triggered_error = 'cpayout_requests.php';
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
		
        private function _payout_requests_manager(){
            $additional_html = '';
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js_lib' ][] = 'js/jquery.dataTables.js';
			$this->class_settings[ 'js' ][] = 'my_js/activate-datatable.js';
			$this->class_settings[ 'js' ][] = 'my_js/custom/payout_requests-manager.js';
			
			$this->class_settings[ 'css' ][] = 'css/template-1/payout_requests_manager.css';
			
			$dashboard = new cDashboard();
			$dashboard->class_settings = $this->class_settings;
			$dashboard->class_settings[ 'action_to_perform' ] = 'setup_dashboard';
			$returning = $dashboard->dashboard();
			
			$this->class_settings = $dashboard->class_settings;
			
			$returning[ 'html' ] = '<div id="page-wrapper"><br />';
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$this->datatable_settings[ 'show_add_new' ] = 0;
            /*
			$this->datatable_settings[ 'show_add_new' ] = array(
				'function-name' => 'site_custom_store_banner_form',
				'function-text' => '<i class="fa fa-plus fa-fw"></i> Request A Payout',
				'function-title' => 'Request A Payout',
			);
			*/
			$this->datatable_settings[ 'show_delete_button' ] = 0;
			$this->datatable_settings[ 'show_edit_button' ] = 0;
			$this->datatable_settings[ 'show_advance_search' ] = 0;
			/*
			$script_compiler->class_settings[ 'data' ] = '';
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-button.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$this->datatable_settings[ 'custom_view_button' ] = $script_compiler->script_compiler();
			*/
            $this->class_settings['total_sales_amount'] = $this->_get_total_amount_sold();
            $this->class_settings['total_payout_amount_paid'] = $this->_get_payout_amount_paid();
            
			$script_compiler->class_settings[ 'data' ] = $this->_display_data_table();
			$script_compiler->class_settings[ 'data' ]['title'] = 'My Payout Requests Manager';
			
			$script_compiler->class_settings[ 'data' ]['sidebar_title'] = 'Payout Requests';
			$script_compiler->class_settings[ 'data' ]['sidebar'] = $this->_site_new_account_form();
            
            
			$script_compiler->class_settings[ 'data' ]['active_bank_account'] = get_merchant_accounts_details( array( 'id' => $this->class_settings['user_id'] ) );
			$script_compiler->class_settings[ 'data' ]['total_sales_amount'] = $this->class_settings['total_sales_amount'];
			$script_compiler->class_settings[ 'data' ]['total_payout_amount_paid'] = $this->class_settings['total_payout_amount_paid'];
            
			$script_compiler->class_settings[ 'data' ]['minimum_balance'] = $this->_get_minimum_payout_request_amount();
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/payout-requests-manager.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] .= $script_compiler->script_compiler();
			
			$returning[ 'html' ] .= '</div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
        }
        
		private function _site_new_account_form(){
			$this->class_settings['do_not_show_headings'] = 1;
			
			$this->class_settings['hidden_records_css'] = array(
				$this->table_fields[ 'merchant_id' ] => 1,
				$this->table_fields[ 'request_status' ] => 1,
				$this->table_fields[ 'request_amount' ] => 1,
			);
			
			$this->class_settings['hidden_records'] = array(
				$this->table_fields[ 'request_id' ] => 1,
			);
			
            $balance = ( $this->class_settings['total_sales_amount'] - $this->class_settings['total_payout_amount_paid'] );
            //FREE ACCOUNT ACTIVATION
            $this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=site_save_payout_requests';
            
            $this->class_settings['form_submit_button'] = 'Request Payout';
            
            $this->class_settings['form_values'] = array(
                $this->table_fields[ 'merchant_id' ] => $this->class_settings['user_id'],
                $this->table_fields[ 'request_status' ] => 'unprocessed',
                $this->table_fields[ 'request_amount' ] => $balance,
            );
            
            $this->class_settings['merchant_id'] = $this->class_settings['user_id'];
            
            $return = $this->_generate_new_data_capture_form();
            
            return $return['html'];
		}
		
        private function _save_site_changes(){
            $returning = $this->_save_changes();
			
			if( $returning['typ'] == 'saved' ){
                if( isset( $returning['saved_record_id'] ) && $returning['saved_record_id'] ){
                //update request id
                    $query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `".$this->table_fields['request_id']."` = `id` WHERE `id`='".$returning['saved_record_id']."'";
                    
                    $query_settings = array(
                        'database' => $this->class_settings['database_name'] ,
                        'connect' => $this->class_settings['database_connection'] ,
                        'query' => $query,
                        'query_type' => 'UPDATE',
                        'set_memcache' => 1,
                        'tables' => array( $this->table_name ),
                    );
                    execute_sql_query($query_settings);
                }
                /*
                $this->class_settings['current_record_id'] = $returning['saved_record_id'];
                $this->class_settings[ 'do_not_check_cache' ] = 1;
                $this->class_settings['where'] = " WHERE `id` = '". $this->class_settings['current_record_id'] ."'";
                $this->_get_coupon_code_details();
				*/
				
			}
			
			return $returning;
        }
        
        private function _get_minimum_payout_request_amount(){
            $user_details = get_site_user_details( array( 'id' => $this->class_settings['user_id'] ) );
            $country = '';
            if( isset( $user_details[ 'country' ] ) ){
                $country = $user_details[ 'country' ];
            }
            
            return get_general_settings_value( array(
                'table' => $this->table_name,
                'key' => 'MINIMUM PAYOUT REQUEST AMOUNT',
                'country' => $country,
            ) );
        }
        
        private function _get_total_amount_sold(){
            
            $cl = new cSales_statistics();
            $cl->class_settings = $this->class_settings;
            $cl->class_settings['merchant_id'] = $this->class_settings['user_id'];
            $cl->class_settings['action_to_perform'] = 'get_total_sales_data_by_merchant';
            $sales_data = $cl->sales_statistics();
            
            if( isset( $sales_data['total_amount'] ) )return $sales_data['total_amount'];
            
            return 0;
        }
        
        private function _get_payout_amount_paid(){
            
            $query = "SELECT SUM( `".$this->table_fields[ 'request_amount' ] ."` ) as 'total_request_paid' FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'request_status' ]."` = 'paid' AND  `".$this->table_name."`.`".$this->table_fields[ 'merchant_id' ]."` = '" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`record_status`='1' ";
            $query_settings = array(
                'database' => $this->class_settings['database_name'] ,
                'connect' => $this->class_settings['database_connection'] ,
                'query' => $query,
                'query_type' => 'SELECT',
                'set_memcache' => 1,
                'tables' => array( $this->table_name ),
            );
            $sql_result = execute_sql_query( $query_settings );
            
            if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
                //ORDERD ITEM ALREADY EXISTS - SET FOR UPDATE
                return doubleval( $sql_result[0]['total_request_paid'] );
            }
        }
    }
?>