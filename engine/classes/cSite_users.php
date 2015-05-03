<?php
	/**
	 * Site_users Class
	 *
	 * @used in  				Site_users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	Site_users
	 */

	/*
	|--------------------------------------------------------------------------
	| Site_users Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSite_users{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'site_users';
		
        private $default_password = '1234567890';
        
		private $table_fields = array(
			'email' => 'site_users003',
			
			'firstname' => 'site_users001',
			'lastname' => 'site_users002',
			
			'oldpassword' => 'site_users006',
			'password' => 'site_users007',
			'confirmpassword' => 'site_users008',
			
			'phonenumber' => 'site_users004',
            
			'department' => 'site_users010',
			'unit' => 'site_users011',
			'job_role' => 'site_users012',
			'branch_office' => 'site_users013',
			'ref_no' => 'site_users014',
			'pass_code' => 'site_users015',
			'assistant' => 'site_users016',
            
			'photograph' => 'site_users018',
			'push_notification_id' => 'site_users019',
            
			'role' => 'site_users009',
		);
		
		function __construct(){
			
		}
	
		function site_users(){
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
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'save_personal_info':
			case 'save_contact_info':
			case 'save_password_info':
				$returned_value = $this->_save_user_info();
			break;
			case 'save_registration':
				$returned_value = $this->_save_registration();
			break;
			case 'display':
				$returned_value = $this->_display();
			break;
			case 'verify_email_address':
				$returned_value = $this->_verify_email_address();
			break;
			case 'display_user_details':
				$returned_value = $this->_display_user_details();
			break;
			case 'change_user_password':
				$returned_value = $this->_change_user_password();
			break;
			case 'edit_password':
				$returned_value = $this->_change_user_password_admin();
			break;
			case 'get_user_details':
				$returned_value = $this->_get_user_details();
			break;
			case 'site_registration':
				$returned_value = $this->_site_registration();
			break;
			case 'site_users_authentication':
			case 'site_users_reset_password':
				$returned_value = $this->_site_users_authentication();
			break;
			case 'authenticate_user':
				$returned_value = $this->_authenticate_user();
			break;
			case 'reset_user_password':
				$returned_value = $this->_reset_user_password();
			break;
			case 'display_user_details_data_capture_form':
			case 'display_site_users_address_data_capture_form':
				$returned_value = $this->_display_user_details_data_capture_form();
			break;
			case 'site_users_authentication_form_only':
				$returned_value = $this->_get_authentication_form();
			break;
			case 'site_users_registration_form_only':
			case 'site_users_guest_registration_form':
				$returned_value = $this->_get_registration_form();
			break;
			case 'create_new_user_account_and_authenticate_user':
				$returned_value = $this->_create_new_user_account_and_authenticate();
			break;
            case 'quick_details_view':
				$returned_value = $this->_quick_details_view();
			break;
			}
			
			return $returned_value;
		}
		
		private function _get_general_settings(){
			return get_from_cached( array( 'cache_key' => 'general_settings' ) );
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			if( ! isset( $this->class_settings['hidden_records'] ) ){
				$this->class_settings['hidden_records'] = array(
					'site_users006' => 1,
					'site_users018' => 1,
					'site_users019' => 1,
					'site_users020' => 1,
				);
			}
				
			if( ! isset( $this->class_settings['hidden_records_css'] ) ){
				$this->class_settings['hidden_records_css'] = array(
					'site_users009' => 1,
					'site_users012' => 1,
					'site_users013' => 1,
					'site_users014' => 1,
					'site_users015' => 1,
					'site_users016' => 1,
					'site_users017' => 1,
				);
			}
			
			if( isset( $this->class_settings['unset_hidden_records_css'] ) ){
				foreach( $this->class_settings['unset_hidden_records_css'] as $key ){
					unset( $this->class_settings['hidden_records_css'][$key] );
				}
			}
			
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
			
            $settings = array(
                'cache_key' => $this->table_name . '-' . 'newly-registered-users',
                'directory_name' => $this->table_name,
                'permanent' => true,
            );
            clear_cache_for_special_values( $settings );
            
			//IMPORTANT
			//make provision for array
			if( isset( $_POST['id'] ) ){
				$this->class_settings['user_id'] = $_POST['id'];
				$this->class_settings[ 'clear_cache' ] = 1;
				$this->_get_user_details();
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
				
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$form->datatables_settings = array(
				'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
					'show_add_new' => 1,			//Determines whether or not to show add new record button
					'show_advance_search' => 1,		//Determines whether or not to show advance search button
					'show_column_selector' => 1,	//Determines whether or not to show column selector button
					'show_units_converter' => 0,	//Determines whether or not to show units converter
						'show_units_converter_volume' => 0,
						'show_units_converter_currency' => 0,
						'show_units_converter_currency_per_unit_kvalue' => 0,
						'show_units_converter_kvalue' => 0,
						'show_units_converter_time' => 0,
						'show_units_converter_pressure' => 0,
					'show_edit_button' => 1,		//Determines whether or not to show edit button
					'show_delete_button' => 1,		//Determines whether or not to show delete button
                    'show_edit_password_button' => 1,
					
				'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
					//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
				
				'show_details' => 1,				//Determines whether or not to show details
				'show_serial_number' => 1,			//Determines whether or not to show serial number
				
				'show_verification_status' => 0,	//Determines whether or not to show verification status
				'show_creator' => 0,				//Determines whether or not to show record creator
				'show_modifier' => 0,				//Determines whether or not to show record modifier
				'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
				
				'current_module_id' => $this->class_settings['current_module'],	//Set id of the currently viewed module
			);
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _authenticate_user(){
			$returning_html_data = array();
			
			$this->class_settings['return_form_data_only'] = true;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'] ) && isset( $returning_html_data['form_data'][ $this->table_fields['password'] ]['value'] ) ){
				$this->class_settings['username'] = $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'];
				$this->class_settings['password'] = $returning_html_data['form_data'][ $this->table_fields['password'] ]['value'];	//hashed password
				
				return $this->_confirm_user_authentication_details();
			}
		}
		
		private function _reset_user_password(){
			$returning_html_data = array();
			
			$this->class_settings['return_form_data_only'] = true;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'] ) ){
				$this->class_settings['table_name'] = $this->table_name;
				$this->class_settings['user_email'] = $returning_html_data['form_data'][ $this->table_fields['email'] ]['value'];
				
				$authentication = new cAuthentication();
				$authentication->class_settings = $this->class_settings;
				
				$authentication->table_fields = $this->table_fields;
				$authentication->class_settings[ 'action_to_perform' ] = 'reset_user_password';
			
				return $authentication->authentication();
			}
		}
		
		private function _confirm_user_authentication_details(){
			$this->class_settings['tables'] = array( $this->table_name );
			
			$this->class_settings['query'] = "SELECT `".$this->table_name."`.`".$this->table_fields['password']."` as 'password', `".$this->table_name."`.`id`, `".$this->table_name."`.`".$this->table_fields['role']."` as 'role', `".$this->table_name."`.`".$this->table_fields['firstname']."` as 'firstname', `".$this->table_name."`.`".$this->table_fields['lastname']."` as 'lastname', `".$this->table_name."`.`".$this->table_fields['email']."` as 'email', `".$this->table_name."`.`".$this->table_fields['verified_email_address']."` as 'verification_status'  FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."` WHERE `record_status` = '1' AND `".$this->table_fields['email']."` = '" . $this->class_settings['username'] . "' LIMIT 1";
			
			$authentication = new cAuthentication();
			$authentication->class_settings = $this->class_settings;
			$authentication->class_settings[ 'action_to_perform' ] = 'confirm_username_and_password';
			
			$returning_html_data = $authentication->authentication();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'authenticated' ){
                if( isset( $this->class_settings['id'] ) && $this->class_settings['id'] ){
                    $this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
                    $user_details = $this->_get_user_details();
                    
                    $this->class_settings[ 'user_email' ] = $user_details['email'];
                    $this->class_settings[ 'user_full_name' ] = $user_details['firstname'] . ' ' . $user_details['lastname'];
                    $this->class_settings[ 'user_id' ] = $this->class_settings['id'];
                }
                
                //set redirection url
				$returning_html_data['status'] = 'redirect-to-successful-authentication-url';
				$returning_html_data['redirect_url'] = get_successful_authentication_url();
			}
			
			return $returning_html_data;
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
					$this->class_settings['user_id'] = $returning_html_data['saved_record_id'];
					
					switch ( $this->class_settings['action_to_perform'] ){
					case 'save_contact_info':
					case 'save_personal_info':
					//break;
					default:
                        unset( $this->class_settings[ 'where' ] );
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						$this->_get_user_details();
					break;
					}
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_user_details(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id'] ){
				$settings = array(
					'cache_key' => $cache_key . '-' . $this->class_settings['user_id'],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				//CHECK WHETHER TO CLEAR CACHE VALUES
				if( isset( $this->class_settings[ 'clear_cache' ] ) && $this->class_settings[ 'clear_cache' ] ){
					unset( $this->class_settings[ 'clear_cache' ] );
					return clear_cache_for_special_values( $settings );
				}
				
				//CHECK WHETHER TO CHECK FOR CACHE VALUES
				if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
					
					//CHECK IF CACHE IS SET
					$cached_values = get_cache_for_special_values( $settings );
					if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
						
						return $cached_values;
						
					}
					
				}
				
				if( ! isset( $this->class_settings['where'] ) ){
					$this->class_settings['where'] = " WHERE `id`='".$this->class_settings['user_id']."' ";
				}
			}
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $key != 'address_fields' && $key != 'oldpassword' ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				}
			}
			
			foreach( $this->table_fields[ 'address_fields' ] as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`".$val."` as '".$key."'";
			}
			
            if( ! isset( $this->class_settings['where'] ) )$this->class_settings['where'] = '';
            
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
            $id = '';
            
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
					
					$returned_data[ $s_val['id'] ]['address_status'] = false;
					
					$returned_data[ $s_val['id'] ]['address_html'] = '';
					
					if( $s_val['country'] && $s_val['country']!='undefined' && $s_val['state'] && $s_val['state']!='undefined' && $s_val['city'] && $s_val['city']!='undefined' && $s_val['street 1'] && $s_val['street 1']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_status'] = true;
					}
					
					if( $s_val['country'] && $s_val['country']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] = get_select_option_value( array( 'id' => $s_val['country'], 'function_name' => 'get_countries' ) );
					}
					if( $s_val['state'] && $s_val['state']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] .= ', ' . get_state_name( array( 'state_id' => $s_val['state'], 'country_id' => $s_val['country'] ) ). '<br />';
					}else{
						$returned_data[ $s_val['id'] ]['address_html'] .= '<br />';
					}
					if( $s_val['city'] && $s_val['city']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] .= get_city_name( array( 'city_id' => $s_val['city'], 'state_id' => $s_val['state'] ) ) . '<br />';
					}
					if( $s_val['street 1'] && $s_val['street 1']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] .= $s_val['street 1'];
					}
					if( $s_val['street 2'] && $s_val['street 2']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] .= ' ' . $s_val['street 2'] . '<br />';
					}else{
						$returned_data[ $s_val['id'] ]['address_html'] .= '<br />';
					}
					if( $s_val['postal code'] && $s_val['postal code']!='undefined' ){
						$returned_data[ $s_val['id'] ]['address_html'] .= $s_val['postal code'];
					}
					
					if( ! $returned_data[ $s_val['id'] ]['address_html'] ){
						$returned_data[ $s_val['id'] ]['address_html'] = 'Unavailable';
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$s_val['id'],
						'cache_values' => $returned_data[ $s_val['id'] ],
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
                    
                    $id  = $s_val['id'];
				}
			}
			
            if( isset( $returned_data[ $id ] ) )return array_merge( $returned_data[ $id ] , $returned_data );
            return $returned_data;
		}
		
		private function _change_user_password(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST[ $this->table_fields['oldpassword'] ] ) ){
				
				if( $_POST[ $this->table_fields['oldpassword'] ] ){
				
					//TEST OLD PASSWORD TO ENSURE IT MATCHES STORED PASSWORD
					$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`id`='" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`".$this->table_fields['oldpassword']."`='" . md5( $_POST[ $this->table_fields['oldpassword'] ] . get_websalter() ) . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query($query_settings);
					
					if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
						//DESTROY OLD PASSWORD FIELD
						unset( $_POST[ $this->table_fields['oldpassword'] ] );
						
						$processing_status = $this->_save_changes();
						
						if( is_array( $processing_status ) && !empty( $processing_status ) ){
							$result_of_all_processing = $processing_status;
							
							if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
								//TRANSFORM SUCCESS MESSAGE
								$err = new cError(010008);
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'cSite_users.php';
								$err->method_in_class_that_triggered_error = '_change_user_password';
								$err->additional_details_of_error = 'successful password change';
								
								$result_of_all_processing = $err->error();
							}
						}
						
					}
				
				}
				
				//RETURN NON-MATCHING OLD PASSWORD ERROR
				$err = new cError(000103);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cSite_users.php';
				$err->method_in_class_that_triggered_error = '_change_user_password';
				
				if( isset( $query ) && $query ){
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 138';
				}
				
				$result_of_all_processing = $err->error();
			}
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'site_users001' => 1,
				'site_users002' => 1,
				'site_users006' => 1,
				'site_users007' => 1,
				'site_users008' => 1,
				'site_users009' => 1,
				'site_users010' => 1,
				'site_users011' => 1,
				'site_users012' => 1,
				'site_users013' => 1,
				'site_users014' => 1,
				'site_users015' => 1,
				'site_users016' => 1,
				'site_users017' => 1,
				'site_users018' => 1,
				'site_users019' => 1,
				'site_users020' => 1,
				'site_users021' => 1,
			);
			
			//Hide certain form fields
			$this->class_settings[ 'hidden_records_css' ] = array(
				'site_users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _change_user_password_admin(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST[ $this->table_fields['password'] ] ) && isset( $_POST[ $this->table_fields['confirmpassword'] ] ) ){
				
                $processing_status = $this->_save_changes();
                
                if( is_array( $processing_status ) && !empty( $processing_status ) ){
                    $result_of_all_processing = $processing_status;
                    
                    if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
                        //TRANSFORM SUCCESS MESSAGE
                        $err = new cError(010008);
                        $err->action_to_perform = 'notify';
                        $err->class_that_triggered_error = 'cSite_users.php';
                        $err->method_in_class_that_triggered_error = '_change_user_password';
                        $err->additional_details_of_error = 'successful password change';
                        
                        return $err->error();
                    }
                    
                    return $processing_status;
                }
			}
			
			//SET VARIABLES FOR EDIT MODE
			//$_POST['id'] = $this->class_settings['user_id'];
			//$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'site_users001' => 1,
				'site_users002' => 1,
				'site_users003' => 1,
				'site_users004' => 1,
				'site_users005' => 1,
				'site_users006' => 1,
				//'site_users007' => 1,
				//'site_users008' => 1,
				'site_users009' => 1,
				'site_users010' => 1,
				'site_users011' => 1,
				'site_users012' => 1,
				'site_users013' => 1,
				'site_users014' => 1,
				'site_users015' => 1,
				'site_users016' => 1,
				'site_users017' => 1,
				'site_users018' => 1,
				'site_users019' => 1,
				'site_users020' => 1,
				'site_users021' => 1,
			);
			
			//Hide certain form fields
			$this->class_settings[ 'hidden_records_css' ] = array(
				'site_users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			return $returned_data;
		}
		
		private function _verify_email_address(){
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			$general_settings_data = $this->_get_general_settings();
			
			if( isset( $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] == 'TRUE' ){
				
				if( isset( $_GET['data'] ) && $_GET['data'] ){
					
					$this->class_settings['user_id'] = '';
					$this->class_settings['where'] = "WHERE MD5( MD5( `id` ) )='" . md5( $_GET['data'] ) . "' LIMIT 1";
					$user_details = $this->_get_user_details();
					
					if( empty( $user_details ) ){
						//FAILED VERIFICATION
						$script_compiler = new cScript_compiler();
						$script_compiler->class_settings = $this->class_settings;
						
						$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/site_users/failed-verification-message.php' );
						$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
						
						return $script_compiler->script_compiler();
					}else{
						
						set_successful_authentication_url( '?action=welcome_new_user&todo=user_dashboard' );
						
                        if( is_array( $user_details ) ){
                            foreach( $user_details as $id => $sval ){
                                if( isset( $sval[ 'verified_email_address' ] ) && $sval[ 'verified_email_address' ] == 'yes' ){
                                    //ALREADY VERIFIED EMAIL ADDRESS
                                    $this->class_settings['notification_data'] = array(
                                        'title' => 'Attempted Email Address Verification',
                                        'detailed_message' => 'There was an attempt to re-verify your already verified email address',
                                        'send_email' => 'no',
                                        'notification_type' => 'no_task',
                                        'target_user_id' => $sval['id'],
                                        'class_name' => $this->table_name,
                                        'method_name' => 'verify_email_address',
                                    );
                                    
                                    $notifications = new cNotifications();
                                    $notifications->class_settings = $this->class_settings;
                                    $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                                    $notifications->notifications();
                                }else{
                                    
                                    set_successful_authentication_url( '?action=welcome_new_user&todo=dashboard_email_verified_message' );
                                    
                                    if( isset( $sval['id'] ) ){
                                        //PERFORM VERIFICATION
                                        $query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`".$this->table_fields['verified_email_address']."`='yes', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE `".$this->table_name."`.`id` = '" . $sval['id'] . "'";
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
                                    unset( $this->class_settings[ 'where' ] );
                                    $this->class_settings[ 'user_id' ] = $id;
                                    $this->class_settings[ 'do_not_check_cache' ] = 1;
                                    $this->class_settings['user_details'] = $this->_get_user_details();
                                    
                                    //NOTIFY OF VERIFICATION EMAIL TASK COMPLETION
                                    if( isset( $sval['email'] ) ){
                                        $this->class_settings['notification_data'] = array(
                                            'title' => 'Successful Email Address Verification',
                                            'detailed_message' => 'Your email address <a href="mailto:'.$sval['email'].'">'.$sval['email'].'</a> has been successfully verified',
                                            'send_email' => 'no',
                                            'notification_type' => 'completed_task',
                                            'target_user_id' => $sval['id'],
                                            'class_name' => $this->table_name,
                                            'method_name' => 'verify_email_address',
                                            
                                            'task_status' => 'complete',
                                            'task_type' => 'system_generated',
                                        );
                                        
                                        $notifications = new cNotifications();
                                        $notifications->class_settings = $this->class_settings;
                                        $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                                        $notifications->notifications();
                                    }
                                    //Send Welcome Email
                                    $this->class_settings['message_type'] = 30;
                                    $this->_send_email();
                                    
                                    $return_url = $this->_activate_merchant_account_verification();
                                    if( $return_url ){
                                        set_successful_authentication_url( $return_url );
                                    }
                                }
                                
                                if( isset( $sval['email'] ) ){
                                //authenticate
                                $this->class_settings['username'] = $sval[ 'email' ];
                                $this->class_settings['password'] = $sval[ 'password' ];	//hashed password
                                $this->class_settings[ 'skip_password' ] = true;
                                }
                                
                                $return = $this->_confirm_user_authentication_details();
                                if( isset( $return['redirect_url'] ) && $return['redirect_url'] ){
                                    header('Location: '.$return['redirect_url']);
                                    exit;
                                }
                            }
                        }
					}
					
				}
				
			}
			
		}
		
		private function _send_email(){
			
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
            if( isset( $this->class_settings[ 'mail_certificate' ] ) )
                $this->mail_certificate = $this->class_settings[ 'mail_certificate' ];
            
			$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'user_email' ];
			$email->class_settings[ 'destination' ]['full_name'][] = $this->class_settings[ 'user_full_name' ];
			$email->class_settings[ 'destination' ]['id'][] = $this->class_settings[ 'user_id' ];
			
			$email->emails();
		}
		
        private function _quick_details_view(){
            $return = array();
            
            if( isset($_GET['id']) && $_GET['id'] ){
                $this->class_settings['user_id'] = $_GET['id'];
                
                $script_compiler = new cScript_compiler();
                $script_compiler->class_settings = $this->class_settings;
                $script_compiler->class_settings[ 'data' ]['user_details'] = $this->_get_user_details();
                
                $merchant_accounts = new cMerchant_accounts();
                $merchant_accounts->class_settings = $this->class_settings;
                $merchant_accounts->class_settings[ 'action_to_perform' ] = 'get_merchant_bank_accounts';
                $script_compiler->class_settings[ 'data' ]['merchant_accounts'] = $merchant_accounts->merchant_accounts();
                
                $script_compiler->class_settings[ 'data' ]['table_fields'] = $this->table_fields;
                
                $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/quick-details-view.php' );
                $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                $return[ 'html' ] = $script_compiler->script_compiler();
                $return[ 'status' ] = 'got-quick-details-view';
                
                return $return;
            }
        }
    }
?>