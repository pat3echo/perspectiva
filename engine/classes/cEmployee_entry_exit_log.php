<?php
	/**
	 * employee_entry_exit_log Class
	 *
	 * @used in  				employee_entry_exit_log Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	employee_entry_exit_log
	 */

	/*
	|--------------------------------------------------------------------------
	| employee_entry_exit_log Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cEmployee_entry_exit_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'employee_entry_exit_log';
		
		private $associated_cache_keys = array(
			'employee_entry_exit_log',
		);
		
		private $table_fields = array(
			'visit_schedule_id' => 'employee_entry_exit_log001',
			'entry_or_exit' => 'employee_entry_exit_log002',
			'time' => 'employee_entry_exit_log003',
			'photograph' => 'employee_entry_exit_log004',
			'previous_time' => 'employee_entry_exit_log005',
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
	
		function employee_entry_exit_log(){
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
			
			switch( $this->class_settings['action_to_perform'] ){
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
			case 'get_employee_entry_exit_log':
				$returned_value = $this->_get_employee_entry_exit_log();
			break;
			case 'log_visitor_entry_or_exit':
				$returned_value = $this->_log_visitor_entry_or_exit();
			break;
			case 'get_recent_activties':
				$returned_value = $this->_get_recent_activties();
			break;
			case 'get_visitor_previous_visits':
				$returned_value = $this->_get_visitor_previous_visits();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
            $this->class_settings['do_not_show_headings'] = 1;
            
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
				'record_id' => ( isset( $returning_html_data[ 'record_id' ] ) )?$returning_html_data[ 'record_id' ]:'',
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
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->class_settings['current_record_id'] = $_POST['id'];
				$this->_get_employee_entry_exit_log();
			}
			
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
				
				$err->class_that_triggered_error = 'cemployee_entry_exit_log.php';
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
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$this->_get_employee_entry_exit_log();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_employee_entry_exit_log(){
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['current_record_id'] ) && $this->class_settings['current_record_id'] ){
				
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				$returning_array = array(
					'html' => '',
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'get-product-features',
				);
				
				//CHECK WHETHER TO CHECK FOR CACHE VALUES
				if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
					
					//CHECK FOR CACHED VALUES
					
					//CHECK IF CACHE IS SET
					$cached_values = get_cache_for_special_values( $settings );
					if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
						
						return $cached_values;
					}
					
				}
				$select = "";
				
				foreach( $this->table_fields as $key => $val ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `modification_date`, `".$val."` as '".$key."'";
				}
				
				//PREPARE FROM DATABASE
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id`='".$this->class_settings['current_record_id']."' ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_employee_entry_exit_log = execute_sql_query($query_settings);
				
				$employee_entry_exit_log = array();
				
				if( isset( $all_employee_entry_exit_log[0] ) && is_array( $all_employee_entry_exit_log[0] ) && ! empty( $all_employee_entry_exit_log[0] ) ){
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
						'directory_name' => $cache_key,
						'cache_values' => $all_employee_entry_exit_log[0],
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-currentstate-'.$all_employee_entry_exit_log[0]['visit_schedule_id'],
						'directory_name' => $cache_key,
						'cache_values' => $this->class_settings['current_record_id'],
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					return $all_employee_entry_exit_log[0];
				}
			}
		}
		
        private function _log_visitor_entry_or_exit(){
            
            if( isset( $this->class_settings['visit_schedule_id'] ) && $this->class_settings['visit_schedule_id'] ){
                $default_state = 'entry';
                $previous_time = date("U");
                
                //CHECK FOR CURRENT STATE
                $last_state = get_employee_entry_exit_log_last_state( array( 'id' => $this->class_settings['visit_schedule_id'] ) );
                if( isset( $last_state ) && $last_state ){
                    $last_log_details = get_employee_entry_exit_log_details( array( 'id' => $last_state ) );
                    if( isset( $last_log_details['entry_or_exit'] ) && $last_log_details['entry_or_exit'] ){
                        switch( $last_log_details['entry_or_exit'] ){
                        case 'entry':
                            $previous_time = $last_log_details['time'];
                            $default_state = 'exit';
                        break;
                        }
                    }
                }
                $id = get_new_id();
                
                //ADD LOG
                $settings_array = array(
                    'database_name' => $this->class_settings['database_name'] ,
                    'database_connection' => $this->class_settings['database_connection'] ,
                    'table_name' => $this->table_name ,
                    'field_and_values' => array(
                        
                        'id' => array( 'value' => $id ),
                        $this->table_fields['visit_schedule_id'] => array( 'value' => $this->class_settings['visit_schedule_id'] ),
                        $this->table_fields['entry_or_exit'] => array( 'value' => $default_state ),
                        $this->table_fields['time'] => array( 'value' => date("U") ),
                        $this->table_fields['photograph'] => array( 'value' => '' ),
                        $this->table_fields['previous_time'] => array( 'value' => $previous_time ),
                        
                        'created_by' => array( 'value' => $this->class_settings['visit_schedule_id'] ),
                        'creation_date' => array( 'value' => date("U") ),
                        'modified_by' => array( 'value' => $this->class_settings['visit_schedule_id'] ),
                        'modification_date' => array( 'value' => date("U") ),
                        'ip_address' => array( 'value' => get_ip_address() ),
                        'record_status' => array( 'value' => 1 ),
                    ),
                );
                
                $save = create( $settings_array );
                
                if( $save ){
                    //REFRESH CACHE && LAST ENTRY STATE
                    $this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $id;
					return $this->_get_employee_entry_exit_log();
                }
            }
            
            $err_code = 000021;
            $err = new cError($err_code);
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            $err->additional_details_of_error = 'Sorry, the visitor could not be signed-in or out due to an internal error<br /><br />ERROR CODE: '.convert_table_name_to_code( $this->table_name.$this->class_settings['action_to_perform'] ).$err_code;
            return $err->error();
        }
        
        private function _get_recent_activties(){
            
            //PREPARE FROM DATABASE
            $query = "SELECT `id`, `creation_date`  FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ORDER BY `creation_date` DESC LIMIT 10 ";
            $query_settings = array(
                'database' => $this->class_settings['database_name'] ,
                'connect' => $this->class_settings['database_connection'] ,
                'query' => $query,
                'query_type' => 'SELECT',
                'set_memcache' => 1,
                'tables' => array( $this->table_name ),
            );
            
            $all_entry_exit_log = execute_sql_query($query_settings);
            $data = array();
            
            if( is_array( $all_entry_exit_log ) && ! empty( $all_entry_exit_log ) ){
                foreach( $all_entry_exit_log as $val ){
                    $details = get_employee_entry_exit_log_details( array( 'id' => $val['id'] ) );
                    if( isset( $details['visit_schedule_id'] ) && $details['visit_schedule_id'] ){
                        
                        $employee = get_users( array( 'id' => $details['visit_schedule_id'] ) );
                        if( is_array( $employee ) ){
                            $i = doubleval( $val['creation_date'] );
                            $data[ $i ]['full_name'] = $employee['firstname'] . ' ' . $employee['lastname'];
                            $data[ $i ]['photograph'] = $employee['photograph'];
                            $data[ $i ]['job_role'] = get_select_option_value( array( 'id' => $employee['job_role'], 'function_name' => 'get_job_roles' ) );
                            
                            $data[ $i ]['date_time'] = date( "M d, Y h:ia" , doubleval( $details['time'] ) );
                            $data[ $i ]['entry'] = ( $details[ 'entry_or_exit' ] == 'entry' )?'in':'out';
                            
                        }
                    }
                }
                return array(
                    'status' => 'got-recent-activities',
                    'data' => $data,
                );
            }
        }
        
        private function _get_visitor_previous_visits(){
            if( isset( $this->class_settings[ 'previous_visit_schedule_id' ] ) && is_array( $this->class_settings[ 'previous_visit_schedule_id' ] ) && !empty( $this->class_settings[ 'previous_visit_schedule_id' ] ) ){
                
                //PREPARE FROM DATABASE
                $query = "SELECT `id`  FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['visit_schedule_id']."` IN ( ".implode(',', $this->class_settings[ 'previous_visit_schedule_id' ] )." ) ORDER BY `creation_date` DESC LIMIT 3 ";
                $query_settings = array(
                    'database' => $this->class_settings['database_name'] ,
                    'connect' => $this->class_settings['database_connection'] ,
                    'query' => $query,
                    'query_type' => 'SELECT',
                    'set_memcache' => 1,
                    'tables' => array( $this->table_name ),
                );
                
                $all_employee_entry_exit_log = execute_sql_query($query_settings);
                $data = array();
                
                if( is_array( $all_employee_entry_exit_log ) && ! empty( $all_employee_entry_exit_log ) ){
                    foreach( $all_employee_entry_exit_log as $val ){
                        $details = get_employee_entry_exit_log_details( array( 'id' => $val['id'] ) );
                        if( isset( $details['visit_schedule_id'] ) && $details['visit_schedule_id'] ){
                                
                            $date = date( "d-M-y" , doubleval( $details['time'] ) );
                            $time = date( "h:ia" , doubleval( $details['time'] ) );
                            
                            $data[ $val['id'] ]['date'] = $date;
                            $data[ $val['id'] ]['time'] = $time;
                            $data[ $val['id'] ]['entry'] = ( $details[ 'entry_or_exit' ] == 'entry' )?'in':'out';
                            
                            $data[ $val['id'] ]['whom_to_see'] = ' ';
                            $data[ $val['id'] ]['reason_for_visit'] = ' ';
                            
                        }
                    }
                    return array(
                        'status' => 'got-previous-visits',
                        'data' => $data,
                    );
                }
            }
        }
        
    }
?>