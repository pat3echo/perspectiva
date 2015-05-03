<?php
	/**
	 * visit_schedule Class
	 *
	 * @used in  				visit_schedule Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	visit_schedule
	 */

	/*
	|--------------------------------------------------------------------------
	| visit_schedule Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cVisit_schedule{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'visit_schedule';
		
		private $associated_cache_keys = array(
			'visit_schedule',
		);
		
		private $table_fields = array(
			'full_name' => 'visit_schedule001',
			'phone_number' => 'visit_schedule002',
			'email' => 'visit_schedule003',
			'street_address' => 'visit_schedule004',
			'photograph' => 'visit_schedule005',
			'reason_for_visit' => 'visit_schedule006',
			'name_of_organization' => 'visit_schedule007',
			'whom_to_see' => 'visit_schedule008',
			'proposed_start_date_time' => 'visit_schedule009',
			'proposed_end_date_time' => 'visit_schedule010',
			'approved_start_date_time' => 'visit_schedule011',
			'approved_end_date_time' => 'visit_schedule012',
			'meeting_venue' => 'visit_schedule013',
			'other_venue' => 'visit_schedule014',
			'approval_status' => 'visit_schedule015',
			'approval_code' => 'visit_schedule016',
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
	
		function visit_schedule(){
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
            case 'get_first_step_visit_schedule_form':
            case 'get_second_step_visit_schedule_form':
            case 'reschedule_visit':
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
			case 'get_visit_schedule':
				$returned_value = $this->_get_visit_schedule();
			break;
			case 'save_first_step':
			case 'save_second_step':
			case 'save_third_step':
				$returned_value = $this->_save_site_changes();
			break;
			case 'cancel_visit':
				$returned_value = $this->_cancel_visit();
			break;
			case 'authenticate_visitor':
				$returned_value = $this->_authenticate_visitor();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
            $this->class_settings['do_not_show_headings'] = 1;
            
            $active_class = ' class="active" ';
            $links[0] = $active_class;
            $links[1] = '';
            $links[2] = '';
            
            switch( $this->class_settings['action_to_perform'] ){
            case 'reschedule_visit':
                if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                    $_POST['id'] = $_GET['record_id'];
                    $_POST['mod'] = 'edit-'.md5($this->table_name);
                }
            case 'get_first_step_visit_schedule_form':
                $_POST['id'] = '7245987470';
                $_POST['mod'] = 'edit-'.md5($this->table_name);
                    
                $this->class_settings['hidden_records'] = array(
                    $this->table_fields['photograph'] => 1,
                    $this->table_fields['reason_for_visit'] => 1,
                    $this->table_fields['name_of_organization'] => 1,
                    $this->table_fields['whom_to_see'] => 1,
                    $this->table_fields['proposed_start_date_time'] => 1,
                    $this->table_fields['proposed_end_date_time'] => 1,
                    $this->table_fields['approved_start_date_time'] => 1,
                    $this->table_fields['approved_end_date_time'] => 1,
                    $this->table_fields['meeting_venue'] => 1,
                    $this->table_fields['other_venue'] => 1,
                    $this->table_fields['approval_code'] => 1,
                );
                
                $this->class_settings['hidden_records_css'] = array(
                    $this->table_fields['approval_status'] => 1,
                );
                
                $this->class_settings['form_values_important'] = array(
                    $this->table_fields['approval_status'] => 'pending',
                );
                
                $this->class_settings[ 'form_submit_button' ] = 'Next >>';
                //$this->class_settings[ 'form_heading_title' ] = 'Sign-up for a Visit';
                $this->class_settings[ 'form_action_todo' ] = 'save_first_step';
            break;
            case 'get_second_step_visit_schedule_form':
                $this->class_settings['hidden_records'] = array(
                    $this->table_fields['full_name'] => 1,
                    $this->table_fields['phone_number'] => 1,
                    $this->table_fields['email'] => 1,
                    $this->table_fields['street_address'] => 1,
                    $this->table_fields['photograph'] => 1,
                    //$this->table_fields['reason_for_visit'] => 1,
                    $this->table_fields['name_of_organization'] => 1,
                    //$this->table_fields['whom_to_see'] => 1,
                    //$this->table_fields['proposed_start_date_time'] => 1,
                    $this->table_fields['proposed_end_date_time'] => 1,
                    $this->table_fields['approved_start_date_time'] => 1,
                    $this->table_fields['approved_end_date_time'] => 1,
                    $this->table_fields['meeting_venue'] => 1,
                    $this->table_fields['other_venue'] => 1,
                    $this->table_fields['approval_status'] => 1,
                    $this->table_fields['approval_code'] => 1,
                );
                $this->class_settings[ 'form_submit_button' ] = 'Next >>';
                //$this->class_settings[ 'form_heading_title' ] = 'Host Information';
                $this->class_settings[ 'form_action_todo' ] = 'save_second_step';
                
                $links[0] = '';
                $links[1] = $active_class;
                $links[2] = '';
            break;
            case 'get_third_step_visit_schedule_form':
                $this->class_settings['hidden_records'] = array(
                    $this->table_fields['full_name'] => 1,
                    $this->table_fields['phone_number'] => 1,
                    $this->table_fields['email'] => 1,
                    $this->table_fields['street_address'] => 1,
                    //$this->table_fields['photograph'] => 1,
                    $this->table_fields['reason_for_visit'] => 1,
                    //$this->table_fields['name_of_organization'] => 1,
                    $this->table_fields['whom_to_see'] => 1,
                    $this->table_fields['proposed_start_date_time'] => 1,
                    $this->table_fields['proposed_end_date_time'] => 1,
                    $this->table_fields['approved_start_date_time'] => 1,
                    $this->table_fields['approved_end_date_time'] => 1,
                    $this->table_fields['meeting_venue'] => 1,
                    $this->table_fields['other_venue'] => 1,
                    $this->table_fields['approval_status'] => 1,
                    $this->table_fields['approval_code'] => 1,
                );
                $this->class_settings[ 'form_submit_button' ] = 'Finish';
                //$this->class_settings[ 'form_heading_title' ] = 'Identification Info';
                $this->class_settings[ 'form_action_todo' ] = 'save_third_step';
                
                $links[0] = '';
                $links[1] = '';
                $links[2] = $active_class;
            break;
            }
            
            switch( $this->class_settings['action_to_perform'] ){
            case 'reschedule_visit':
                $this->class_settings[ 'form_heading_title' ] = 'Re-schedule Visit';
                unset( $this->class_settings['do_not_show_headings'] );
            break;
            }
            
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			$a = '<ul class="nav nav-tabs">
                <li '.$links[0].'><a>1. Personal Info</a></li>
                <li '.$links[1].'><a>2. Host Info</a></li>
                <li '.$links[2].'><a>3. Identification Info</a></li>
            </ul>';
            
			return array(
				'html' => $a.$returning_html_data[ 'html' ],
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
				$this->_get_visit_schedule();
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
				
				$err->class_that_triggered_error = 'cvisit_schedule.php';
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
		
        private function _save_site_changes(){
            $return = $this->_save_changes();
            
            $status = array(
                'save_first_step' => 'second-step-form',
                'save_second_step' => 'third-step-form',
                'save_third_step' => 'sign-up-complete',
            );
            $actions = array(
                'save_first_step' => 'get_second_step_visit_schedule_form',
                'save_second_step' => 'get_third_step_visit_schedule_form',
            );
            
            switch( $this->class_settings['action_to_perform'] ){
            case 'save_first_step':
            case 'save_second_step':
                if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
                    
                    $return['status'] = $status[ $this->class_settings['action_to_perform'] ];
                    
                    $_POST['id'] = $return['saved_record_id'];
                    $_POST['mod'] = 'edit-'.md5($this->table_name);
                    
                    $this->class_settings['action_to_perform'] = $actions[ $this->class_settings['action_to_perform'] ];
                    $r = $this->_generate_new_data_capture_form();
                    $return['html'] = $r['html'];
                }
            break;
            case 'save_third_step':
                if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
                    $return['status'] = $status[ $this->class_settings['action_to_perform'] ];
                    
                    $script_compiler = new cScript_compiler();
                    $script_compiler->class_settings = $this->class_settings;
                    
                    unset( $this->class_settings[ 'do_not_check_cache' ] );
                    $this->class_settings['current_record_id'] = $return['saved_record_id'];
                    $script_compiler->class_settings[ 'data' ][ 'visitor_info' ] = $this->_get_visit_schedule();
					
                    $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/new-visit-confirmation-message.php' );
                    $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                    $return['html'] = $script_compiler->script_compiler();
                    
                    //send email notification
                }
            break;
            }
            
            if( isset( $return['typ'] ) )unset( $return['typ'] );
            
            return $return;
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
					$this->_get_visit_schedule();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_visit_schedule(){
			
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
				
				$all_visit_schedule = execute_sql_query($query_settings);
				
				$visit_schedule = array();
				
				if( isset( $all_visit_schedule[0] ) && is_array( $all_visit_schedule[0] ) && ! empty( $all_visit_schedule[0] ) ){
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
						'directory_name' => $cache_key,
						'cache_values' => $all_visit_schedule[0],
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					return $all_visit_schedule[0];
				}
			}
		}
		
        private function _cancel_visit(){
            if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                $record_id = $_GET['record_id'];
                
                //update visit status to cancelled
                
                $settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						'modification_date' => array(
							'value' => date("U") ,
						),
						'modified_by' => array(
							'value' => 'visitor' ,
						),
						$this->table_fields[ 'approval_status' ] => array(
							'value' => 'cancelled' ,
						),
					) ,
					'where_fields' => 'id' ,
					'where_values' =>  $record_id,
					'condition' => "" ,
				);
				
				$save = update( $settings_array );
                
                if( $save ){
                    $script_compiler = new cScript_compiler();
                    $script_compiler->class_settings = $this->class_settings;
                    
                    $this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $record_id;
					$script_compiler->class_settings[ 'data' ][ 'visitor_info' ] = $this->_get_visit_schedule();
                    
                    $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/visit-cancelled-message.php' );
                    $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                    
                    $return['status'] = 'visit-schedule-cancelled';
                    $return['html'] = $script_compiler->script_compiler();
                    return $return;
                }
            }
            
            $err_code = 000021;
            $err = new cError($err_code);
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            $err->additional_details_of_error = 'Sorry, the visit could not be cancelled due to an internal error<br /><br />ERROR CODE: '.convert_table_name_to_code( $this->table_name ).$err_code;
            return $err->error();
        }
        
        private function _authenticate_visitor(){
            $return = array();
            
            if( ! ( isset( $_POST['pass_code'] ) && $_POST['pass_code'] ) ){
                //CHECK FOR EMPLOYEE PASS CODE
                $err_code = 000021;
                $err = new cError($err_code);
                $err->action_to_perform = 'notify';
                
                $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                $err->additional_details_of_error = 'Invalid Pass Code<br /><br />ERROR CODE: '.convert_table_name_to_code( $this->table_name .'_'. $this->class_settings['action_to_perform'] );
                return $err->error();
            }
            
            if( strlen( $_POST['pass_code'] ) == 5 ){
                //CHECK FOR EMPLOYEE PASS CODE
                $users = new cUsers();
                $users->class_settings = $this->class_settings;
                $users->class_settings[ 'action_to_perform' ] = 'authenticate_employee';
                $users->class_settings['passcode'] = $_POST['pass_code'];
                return $users->users();
            }
            
            //GET ID FROM AUTHENTICATION CODE
            $this->class_settings['current_record_id'] = '7245987470';
            $this->class_settings['visit_schedule_id'] = $this->class_settings['current_record_id'];
            $return['visitor_data'] = $this->_get_visit_schedule();
            
            //LOG ACTIVITY
            $entry_exit_log = new cEntry_exit_log();
			$entry_exit_log->class_settings = $this->class_settings;
			$entry_exit_log->class_settings[ 'action_to_perform' ] = 'log_visitor_entry_or_exit';
            
            //$return['visitor_data']
            $log = $entry_exit_log->entry_exit_log();
            
            if( isset( $log['visit_schedule_id'] ) && $log['visit_schedule_id'] == $this->class_settings['current_record_id'] ){
                switch( $log['entry_or_exit'] ){
                case 'entry':
                    $return['visitor_data']['entry_time'] = date( "h:i:sa" , doubleval( $log['time'] ) );
                    $return['visitor_data']['exit_time'] = '-';
                    $return['visitor_data']['entry'] = 'in';
                break;
                default:
                    $return['visitor_data']['entry_time'] = date( "h:i:sa" , doubleval( $log['previous_time'] ) );
                    $return['visitor_data']['exit_time'] = date( "h:i:sa" , doubleval( $log['time'] ) );
                    $return['visitor_data']['entry'] = 'out';
                break;
                }
                $return['visitor_data']['date_time'] = date( "M d, Y h:ia" , doubleval( $log['time'] ) );
                
                $this->class_settings['current_schedule_email'] = $return['visitor_data']['email'];
                
                $previous_visits_ids = $this->_get_visitor_previous_visit_schedule();
                if( is_array( $previous_visits_ids ) && ! empty( $previous_visits_ids ) ){
                    
                    $entry_exit_log->class_settings[ 'previous_visit_schedule_id' ] = $previous_visits_ids;
                    $entry_exit_log->class_settings[ 'action_to_perform' ] = 'get_visitor_previous_visits';
                    $return['visitor_data'][ 'previous_visits' ] = $entry_exit_log->entry_exit_log();
                    
                }
            }else{
                return $log;
            }
            
            $return['visitor_data']['check_sum'] = md5( json_encode( $return['visitor_data'] ) );
            //log data for push notification
            $settings = array(
                'cache_key' => 'signin-push-notifications',
                //'permanent' => true,
            );
            $not = get_cache_for_special_values( $settings );
            if( ! is_array( $not ) )$not = array();
            $not[] = $return['visitor_data'];
            $settings = array(
                'cache_key' => 'signin-push-notifications',
                'cache_values' => $not,
                //'permanent' => true,
            );
            set_cache_for_special_values( $settings );
            
            $return['status'] = 'authenticated-visitor';
            
            return $return;
        
            $err_code = 000021;
            $err = new cError($err_code);
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            $err->additional_details_of_error = 'Sorry, the visit could not be cancelled due to an internal error<br /><br />ERROR CODE: '.convert_table_name_to_code( $this->table_name ).$err_code;
            return $err->error();
            
        }
        
        private function _get_visitor_previous_visit_schedule(){
            if( isset( $this->class_settings['current_schedule_email'] ) && $this->class_settings['current_schedule_email'] ){
                $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['email']."`='".$this->class_settings['current_schedule_email']."' ORDER BY `creation_date` DESC LIMIT 6 ";
                $query_settings = array(
                    'database' => $this->class_settings['database_name'] ,
                    'connect' => $this->class_settings['database_connection'] ,
                    'query' => $query,
                    'query_type' => 'SELECT',
                    'set_memcache' => 1,
                    'tables' => array( $this->table_name ),
                );
                
                $a = execute_sql_query($query_settings);
                $data = array();
                
                if( is_array( $a ) && ! empty( $a ) ){
                    foreach( $a as $val ){
                        $data[] = $val['id'];
                    }
                }
            }
            return $data;
        }
	}
?>