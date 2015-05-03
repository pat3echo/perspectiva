<?php
	/**
	 * users Class
	 *
	 * @used in  				users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	users
	 */

	/*
	|--------------------------------------------------------------------------
	| users Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cUsers{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'users';
		
        private $table_fields = array(
			'email' => 'users004',
			'other_names' => 'users003',
			
			'firstname' => 'users001',
			'lastname' => 'users002',
			
			'password' => 'users006',
			'confirmpassword' => 'users007',
			'oldpassword' => 'users008',
			
			'phone_number' => 'users005',
            
			'division' => 'users010',
			'department' => 'users011',
			'unit' => 'users012',
			'job_role' => 'users013',
			'branch_office' => 'users014',
			'ref_no' => 'users015',
			'pass_code' => 'users016',
			'assistant' => 'users017',
            
			'photograph' => 'users018',
			'push_notification_id' => 'users019',
            
			'role' => 'users009',
		);
        
		function users(){
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
			case 'display':
				$returned_value = $this->_display();
			break;
			case 'users_registration':
				$returned_value = $this->_users_registration_process();
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
			case 'get_user_details':
				$returned_value = $this->_get_user_details();
			break;
			case 'authenticate_employee':
				$returned_value = $this->_authenticate_employee();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			if( ! isset( $this->class_settings['hidden_records'] ) ){
				$this->class_settings['hidden_records'] = array(
					$this->table_fields['oldpassword'] => 1,
					$this->table_fields['push_notification_id'] => 1,
				);
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
				
				$err->class_that_triggered_error = 'cusers.php';
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
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
            $this->class_settings['user_id'] = $returning_html_data['saved_record_id'];
            
            $this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_user_details();
            
			return $returning_html_data;
		}
		
		private function _display_user_details(){
			
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			$processing_status = $this->_save_changes();
			
			if( is_array( $processing_status ) && !empty( $processing_status ) ){
				$result_of_all_processing = $processing_status;
			}
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'users003' => 1,
				'users004' => 1,
				'users005' => 1,
				'users014' => 1,
				'users015' => 1,
				'users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _change_user_password(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST['users008'] ) ){
				
				if( $_POST['users008'] ){
				
					//TEST OLD PASSWORD TO ENSURE IT MATCHES STORED PASSWORD
					$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`id`='" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`users006`='" . md5( $_POST['users008'] . get_websalter() ) . "' AND `" . $this->table_name . "`.`record_status`='1' ";
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
						unset( $_POST['users008'] );
						
						$processing_status = $this->_save_changes();
						
						if( is_array( $processing_status ) && !empty( $processing_status ) ){
							$result_of_all_processing = $processing_status;
							
							if( isset( $result_of_all_processing['typ'] ) && $result_of_all_processing['typ'] == 'saved' ){
								//TRANSFORM SUCCESS MESSAGE
								$err = new cError(010008);
								$err->action_to_perform = 'notify';
								$err->class_that_triggered_error = 'cusers.php';
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
				$err->class_that_triggered_error = 'cusers.php';
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
				'users001' => 1,
				'users002' => 1,
				'users006' => 1,
				'users007' => 1,
				'users008' => 1,
				'users009' => 1,
				'users010' => 1,
				'users011' => 1,
				'users012' => 1,
				'users013' => 1,
				'users014' => 1,
				'users015' => 1,
				'users016' => 1,
				'users017' => 1,
				'users018' => 1,
				'users019' => 1,
				'users020' => 1,
				'users021' => 1,
			);
			
			//Hide certain form fields
			$this->class_settings[ 'hidden_records_css' ] = array(
				'users016' => 1,
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
		
		private function _verify_email_address(){
			//PROJECT DATA
			$project = get_project_data();
			
			$generate_form = true;
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			if(isset($_GET['verify']) && $_GET['verify']){
				
				//Get User Details prior to verification / authentication
				$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`users016` = '10'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				$email_address = '';
				$password = '';
				$user_id = '';
				$first_name = '';
				$last_name = '';
				
				if(isset($sql_result[0])){
					
					$email_address = $sql_result[0]['users007'];
					$password = $sql_result[0]['users004'];
					$user_id = $sql_result[0]['id'];
					
					$first_name = $sql_result[0]['users001'];
					$last_name = $sql_result[0]['users002'];
					
					//Verify user account if not already verified
					$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`users016`='20', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1'";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					
					$save = execute_sql_query($query_settings);
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError(000001);
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_verify_email_address';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 160';
					
					$result_of_all_processing = $err->error();
				}
				
				if( isset( $save['success'] ) && $save['success'] == 1 ){
					//SUCCESSFUL VERIFICATION
					
					if( $email_address && $user_id && $password ){
						
						//Send Successful Email Verification Message
						$email = new cEmails();
						$email->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'user_id' => $user_id ,
							
							'action_to_perform' => 'send_mail',
						);
						
						$email->destination['email'][] = $email_address;
						$email->destination['full_name'][] = ucwords( $first_name . ' ' . $last_name );
						$email->destination['id'][] = $user_id;
						
						$email->message_type = 30;	//Successful Registration template
						$email->sender = $project;
						
						$email->emails();
					}
					
					//Prevent Form Generation
					$generate_form = false;
					
					//Login New User
					$classname = 'authentication';
					$actual_name_of_class = 'c'.ucwords($classname);
					
					$module = new $actual_name_of_class();
					
					$module->class_settings = array(
						'database_connection' => $this->class_settings[ 'database_connection' ],
						'database_name' => $this->class_settings[ 'database_name' ],
						'calling_page' => $this->class_settings[ 'calling_page' ],
						
						'username' => $email_address ,
						'password' => $password ,
						
						'action_to_perform' => 'confirm_username_and_password',
					);
					$returned_data = $module->$classname();
					
					if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
						//LOG SUCCESSFUL OPERATION IN AUDIT TRAIL
						//Auditor
						auditor( $this->class_settings['calling_page'] , '' , $user_id , $email_address , $this->class_settings['database_connection'] , $this->class_settings['database_name'] , 'verified_account' , $this->table_name , 'user account with id ' . $user_id . ' in the table was verified ' );
						
						//Redirect to appropriate page
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
						exit;
					}else{
						//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
						$err = new cError(000014);
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cusers.php';
						$err->method_in_class_that_triggered_error = '_users_registration_process';
						$err->additional_details_of_error = '';
						$result_of_all_processing = $err->error();
						
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
						exit;
					}
				}else{
					//UNSUCCESSFUL VERIFICATION
					$err = new cError(000015);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_users_registration_process';
					$err->additional_details_of_error = '';
					$result_of_all_processing = $err->error();
				}
				
			}
			
			//2. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					'users008' => 1,
					
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					'users009' => 1,
				);
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
		}
		
		private function _users_registration_process(){
			//PROJECT DATA
			$project = get_project_data();
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			//SET VARIABLE TO GENERATE FORM
			$generate_form = true;
			
			$email_exists = false;
			
			//1. CHECK FOR EXISTING EMAIL ADDRESS
			if( isset( $_POST['users004'] ) && $_POST['users004'] ){
			
				$query = "SELECT * FROM `" . $this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE `" . $this->table_name . "`.`users007`='" . $_POST['users004'] . "' AND `" . $this->table_name . "`.`record_status`='1' ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( isset($sql_result[0]) ){
				
					$email_exists = true;
					
				}
				
			}
			
			$processing_status = null;
			
			if( $email_exists ){
				//EXISTING EMAIL ADDRESS ERROR
				$err = new cError(000102);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_users_registration_process';
				$err->additional_details_of_error = '';
				$processing_status = $err->error();
				
			}else{
				//CHECK FOR SUBMITTED FORM DATA
				$processing_status = $this->_save_changes();
			}
			
			//2. FOUND SUBMITTED FORM DATA - PROCESS THE DATA
			if( $processing_status && is_array( $processing_status ) ){
				//Process Response
				if( isset( $processing_status[ 'typ' ] ) ){
				
					switch( $processing_status[ 'typ' ] ){
					case 'saved':
						//Prevent Form Generation
						$generate_form = false;
						
						//Login New User
						$classname = 'authentication';
						$actual_name_of_class = 'c'.ucwords($classname);
						
						$module = new $actual_name_of_class();
						
						$module->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'username' => $_POST[ 'users004' ],
							'password' => md5( $_POST[ 'users006' ] . get_websalter() ) ,
							
							'action_to_perform' => 'confirm_username_and_password',
						);
						$returned_data = $module->$classname();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							//Get Logged in User Details
							$user_details = array();
							$key = md5('ucert'.$_SESSION['key']);
							if( isset($_SESSION[$key]) ){
								$user_details = $_SESSION[$key];
								
								//Send Email Verification Message
								$email = new cEmails();
								$email->class_settings = array(
									'database_connection' => $this->class_settings[ 'database_connection' ],
									'database_name' => $this->class_settings[ 'database_name' ],
									'calling_page' => $this->class_settings[ 'calling_page' ],
									
									'user_id' => $user_details[ 'id' ],
									
									'action_to_perform' => 'send_mail',
								);
								
								$email->destination['email'][] = $user_details[ 'email' ];
								$email->destination['full_name'][] = ucwords($user_details[ 'fname' ] . ' ' . $user_details[ 'lname' ] );
								$email->destination['id'][] = $user_details[ 'id' ];
								
								$email->message_type = 1;	//Successful Registration template
								$email->sender = $project;
								
								$email->emails();
								
								$email->message_type = 2;	//Verification template
								$email->emails();
								
								//Redirect to appropriate page
								header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
								exit;
							}
						}else{
							//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
							$err = new cError(000014);
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cusers.php';
							$err->method_in_class_that_triggered_error = '_users_registration_process';
							$err->additional_details_of_error = '';
							$processing_status = $err->error();
							
							header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
							exit;
						}
						
						//Return Saved Succcessfully Message
						return $processing_status;
					break;
					default:
						//Return Error Message
						$result_of_all_processing = $processing_status;
					break;
					}
					
				}
			}
			
			//3. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					'users008' => 1,
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					'users009' => 1,
				);
				
				$this->class_settings[ 'form_submit_button' ] = 'Register';
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
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
				if( $key != 'oldpassword' ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				}
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
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$s_val['id'],
						'cache_values' => $s_val,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
                    
				}
			}
			
            if( isset( $s_val ) )return $s_val;
		}
		
        private function _authenticate_employee(){
            $return = array();
            
            //employee id from passcode
            if( ! ( isset( $this->class_settings['passcode'] ) && $this->class_settings['passcode'] ) ){
                $err_code = 000021;
                $err = new cError($err_code);
                $err->action_to_perform = 'notify';
                
                $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                $err->additional_details_of_error = 'Invalid Pass Code<br /><br />ERROR CODE: '.convert_table_name_to_code( $this->table_name . $this->class_settings['action_to_perform'] );
                return $err->error();
            }
            
            $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['pass_code']."` = '".$this->class_settings['passcode']."' ";
            $query_settings = array(
                'database' => $this->class_settings['database_name'] ,
                'connect' => $this->class_settings['database_connection'] ,
                'query' => $query,
                'query_type' => 'SELECT',
                'set_memcache' => 1,
                'tables' => array( $this->table_name ),
            );
            
            $a = execute_sql_query($query_settings);
            
            $entry_exit_log = array();
            
            if( isset( $a[0]['id'] ) && $a[0]['id'] ){
                $this->class_settings['user_id'] = $a[0]['id'];
                $return['visitor_data'] = $this->_get_user_details();
                
                $return['visitor_data']['full_name'] = $return['visitor_data']['firstname'] . ' ' . $return['visitor_data']['lastname'];
                $return['visitor_data']['street_address'] = get_select_option_value( array( 'id' => $return['visitor_data']['department'], 'function_name' => 'get_departments' ) );
                
                $return['visitor_data']['job_role'] = get_select_option_value( array( 'id' => $return['visitor_data']['job_role'], 'function_name' => 'get_job_roles' ) );
                
                $return['visitor_data']['name_of_organization'] = $return['visitor_data']['job_role'];
                
                $return['visitor_data']['branch_office'] = get_select_option_value( array( 'id' => $return['visitor_data']['branch_office'], 'function_name' => 'get_branch_offices' ) );
                
                $this->class_settings['current_record_id'] = $this->class_settings['user_id'];
                $this->class_settings['visit_schedule_id'] = $this->class_settings['current_record_id'];
                
                //LOG ACTIVITY
                $employee_entry_exit_log = new cEmployee_entry_exit_log();
                $employee_entry_exit_log->class_settings = $this->class_settings;
                $employee_entry_exit_log->class_settings[ 'action_to_perform' ] = 'log_visitor_entry_or_exit';
                
                //$return['visitor_data']
                $log = $employee_entry_exit_log->employee_entry_exit_log();
                
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
                    $return['visitor_data']['approved_start_date_time'] = date( "d-M-y" , doubleval( $log['time'] ) );
                    
                    $this->class_settings['current_schedule_email'] = $return['visitor_data']['email'];
                    
                    $previous_visits_ids = $this->_get_employees_previous_visit_schedule();
                    if( is_array( $previous_visits_ids ) && ! empty( $previous_visits_ids ) ){
                        $employee_entry_exit_log->class_settings[ 'previous_visit_schedule_id' ] = $previous_visits_ids;
                        $employee_entry_exit_log->class_settings[ 'action_to_perform' ] = 'get_visitor_previous_visits';
                        $return['visitor_data'][ 'previous_visits' ] = $employee_entry_exit_log->employee_entry_exit_log();
                        
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
            }
        }
        
        private function _get_employees_previous_visit_schedule(){
            if( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id'] ){
                return array( $this->class_settings['user_id'] );
            }
        }
	}
?>