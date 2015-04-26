<?php
	/**
	 * Authentication Class
	 *
	 * @used in  				Authentication
	 * @created  				15:53 | 27-12-2013
	 * @database table name   	users | 
	 */

	/*
	|--------------------------------------------------------------------------
	| Authentication Function that occurs during login
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to authenticate users against database
	| records.
	|
	*/
	
	class cAuthentication{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'users';
		private $admin_table_name = '';
		
		public $table_fields = array(
			'email' => 'users004',
			'password' => 'users006',
		);
		
		function authentication(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'authenticate_user':
				$returned_value = $this->_authenticate_user();
			break;
			case 'confirm_username_and_password':
				$returned_value = $this->_confirm_username_and_password();
			break;
			case 'reset_user_password':
				$returned_value = $this->_reset_user_password();
			break;
			case 'users_login_process':
				$returned_value = $this->_users_login_process();
			break;
			}
			
			return $returned_value;
		}
		
		private function _users_login_process(){
			$returned_data = array();
			
			//DETERMINE TYPE OF FORM - LOGIN / RESET PASSWORD
			$login_form = true;
			if( isset( $_GET['form_type'] ) && $_GET['form_type'] == 'reset' ){
				$login_form = false;
			}
			
			//DETERMINE TYPE OF LOGIN - ADMIN / USERS
			//Users login
			$classname = 'users';
			
			//Get project data
			$project = get_project_data();
			
			//Check login type
			if( isset( $project['admin_login_form_variable_name'] ) && isset( $_GET[ $project['admin_login_form_variable_name'] ] ) && $_GET[ $project['admin_login_form_variable_name'] ] == $project['admin_login_form_passkey'] ){
				//Admin login
				$classname = 'administrators';
			}
			
			//CHECK FOR SUBMITTED FORM DATA AND AUTHENTICATE
			if( isset( $_POST['table'] ) && $_POST['table'] == $classname ){
				
				//Validate form token
				$validated_token = false;
				if(isset($_SESSION['key'])){
				$frmtok = md5('form_token'.$_SESSION['key']);
				
					if(isset($_SESSION[$frmtok]) && is_array($_SESSION[$frmtok]) && isset($_POST['processing']) && in_array($_POST['processing'],$_SESSION[$frmtok])){
						//TOKEN VALIDATED
						$validated_token = true;
						
						//CLEAR TOKEN
						foreach($_SESSION[$frmtok] as $ki => $vi){
							if( $_POST['processing'] == $vi ){
								unset($_SESSION[$frmtok][$ki]);
							}
						}
				
					}

				}
				
				if( $validated_token ){
					//Get User Details
					$email = '';
					$password = '';
					
					if( isset( $_POST[ $this->table_fields['email'] ] ) )
						$email = clean( $_POST[ $this->table_fields['email'] ] , "email" , $this->class_settings[ 'calling_page' ] );
						
					if( isset( $_POST[ $this->table_fields['password'] ] ) )
						$password = $_POST[ $this->table_fields['password'] ];
					
					$report_error = true;
					
					//Authenticate User
					if( $email && $password ){
						$report_error = false;
						
                        $valid_token = 1; //$valid_token = 0;
                        //check for valid token
                        $settings = array(
                            'cache_key' => 't-'.md5( $email ),
                        );
                        $stored_token = get_cache_for_special_values( $settings );
                        
                        if( isset( $_GET['n'] ) && $_GET['n'] ){
                            clear_cache_for_special_values( $settings );
                            
                            if( $_GET['n'] == $stored_token ){
                                $valid_token = 1;
                            }else{
                                if( $stored_token ){
                                    $err = new cError(000026);
                                    $err->action_to_perform = 'notify';
                                    $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                                    $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                                    $err->additional_details_of_error = 'invalid authentication token';
                                    
                                    return $err->error();
                                }
                            }
                        }
                        
                        if( ! $valid_token )$this->class_settings[ 'do_not_set_session' ] = 1;
                        
						$this->class_settings[ 'username' ] = $email;
						$this->class_settings[ 'password' ] = md5( $password . get_websalter() );
						
						$returned_data = $this->_confirm_username_and_password();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							
                            if( ! $valid_token ){
                                //set token
                                $token = md5( date("U") . get_websalter() );
                                //Cache Settings
                                $settings = array(
                                    'cache_key' => 't-'.md5( $email ),
                                    'cache_values' => $token,
                                    'cache_time' => 'token-time',
                                );
                                set_cache_for_special_values( $settings );
                                
                                //send email to user
                                $this->class_settings['message_type'] = 15;
                                $this->class_settings['project_data'] = get_project_data();
                                $this->class_settings[ 'mail_certificate' ]['login_link'] = '<a href="https://'.$this->class_settings['project_data']['domain_name_only'].'/engine/?t='.$token.'" target="_blank" title="Click here to Login">Click here to Login</a>';
                                $this->class_settings[ 'mail_certificate' ]['validity_period'] = '10 minutes';
                                
                                $this->class_settings[ 'user_full_name' ] = isset( $returned_data['user_details']['fname'] )?$returned_data['user_details']['fname'].' '.$returned_data['user_details']['lname']:'';
                                
                                $this->class_settings[ 'user_email' ] = $email;
                                $this->class_settings[ 'user_id' ] = isset( $returned_data['user_details']['user_id'] )?$returned_data['user_details']['user_id']:'';
                                
                                $this->_send_email();
                                
                                session_destroy();
                                
                                //return verification token sent to email address message
                                $err = new cError(010015);
                                $err->action_to_perform = 'notify';
                                return $err->error();
                            }    
                            
							//Redirect to dashboard
							$school_setup_page_html_content = '';
							if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' ) ) )
								$school_setup_page_html_content = read_file( '', $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' );
							
							//GET SCHOOL PROPERTIES
							$settings = array(
								'cache_key' => 'school_properties',
							);
							$cached_values = get_cache_for_special_values( $settings );
			
							$returning_array = array(
								'html' => $school_setup_page_html_content,
								'method_executed' => $this->class_settings['action_to_perform'],
								'school_properties' => $cached_values,
								'status' => 'redirect-to-dashboard',
								'message' => 'Successfully created and authenticated',
							);
							
							return $returning_array;
						}
						
					}
					
					//Reset User Password
					if( $email && ! $login_form ){
						$report_error = false;
						
						$this->class_settings[ 'username' ] = $email;
						$this->class_settings[ 'email_field' ] = $this->table_fields['email'];
						$this->class_settings[ 'password_field' ] = $this->table_fields['password'];
						$this->class_settings[ 'confirm_password_field' ] = 'users007';
						
						$this->class_settings[ 'firstname_field' ] = 'users001';
						$this->class_settings[ 'lastname_field' ] = 'users002';
						
						$this->table_name = $classname;
						
						$returned_data = $this->_reset_user_password();
					}
					
					if( $report_error ){
						
						//Failed Authentication - wrong username
						$err = new cError(010101);
						$err->action_to_perform = 'notify';
						
						$returned_data = $err->error();
					}
					
				}else{
					//RETURN INVALID TOKEN ERROR
					$err = new cError(000002);
					$err->action_to_perform = 'notify';
					
					$returned_data = $err->error();
				}
			}
			
			//GENERATE LOGIN FORM
			$actual_name_of_class = 'c'.ucwords($classname);
			
			$module = new $actual_name_of_class();
			
			$module->class_settings = array(
				'database_connection' => $this->class_settings[ 'database_connection' ],
				'database_name' => $this->class_settings[ 'database_name' ],
				'calling_page' => $this->class_settings[ 'calling_page' ],
				
				'user_id' => '',
				'priv_id' => '',
				
				//'do_not_show_headings' => true,
				'hidden_records' => array(
					'users001' => 1,
					'users002' => 1,
					'users003' => 1,
					'users005' => 1,
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
					'users022' => 1,
					'users023' => 1,
					'users024' => 1,
					'users025' => 1,
					'users026' => 1,
					'users027' => 1,
					'users028' => 1,
					'users029' => 1,
					'users030' => 1,
					'users031' => 1,
					'users032' => 1,
					'users033' => 1,
					'users034' => 1,
					'users035' => 1,
					'users036' => 1,
					'users037' => 1,
					'users038' => 1,
					'users039' => 1,
					'users040' => 1,
					'users041' => 1,
				),
				
				'form_class' => 'skip-validation',
				'form_action' => '?action=authentication&todo=users_login_process&form_type=reset',
				'form_submit_button' => 'Request Password Reset',
				'forgot_password_link' => '<a href="?form_type" class="special" rel="external">Login</a>',
                
				'form_heading_title' => 'Login to your account',
				
				'action_to_perform' => 'create_new_record',
			);
			
			if( $login_form ){
				//Display Password Field
				unset( $module->class_settings[ 'hidden_records' ][ 'users006' ] );
				
				$module->class_settings[ 'form_action' ] = '?action=authentication&todo=users_login_process';
				
                if( isset( $_GET['tt'] ) && $_GET['tt'] )$module->class_settings[ 'form_action' ] .= '&n='.$_GET['tt'];
                
				$module->class_settings[ 'forgot_password_link' ] = '<a href="?form_type=reset" class="special" rel="external">Forgot your password?</a>';
				
				$module->class_settings[ 'form_submit_button' ] = 'Sign In';
			}
			
			//Clear Previously Submitted Data
			unset( $_POST[ 'users004' ] );
			unset( $_POST[ 'users006' ] );
			
			$result_of_all_processing = $module->$classname();
			
			if( ! empty ( $returned_data ) && isset( $returned_data['html'] ) ){
				
				$returned_data['html'] = $result_of_all_processing['html'];
				$returned_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returned_data['status'] = 'authenticate-user';
				
				return $returned_data;
			}
			
			return $result_of_all_processing;
		}
		
		private function _confirm_username_and_password(){
			$table1 = 'users_role';
			
			$username = $this->class_settings[ 'username' ];
			$password = $this->class_settings[ 'password' ];
            
			$skip_password = false;
            if( isset( $this->class_settings[ 'skip_password' ] ) )
                $skip_password = $this->class_settings[ 'skip_password' ];
			
			$project = get_project_data();
			
			//1. DETERMINE LOGIN TYPE
			if( isset($_GET['shopdoff']) && isset($project['admin_login_form_passkey']) && $_GET['shopdoff']==$project['admin_login_form_passkey'] ){
				//administrators login
				
				//2. OBTAIN RECORD WITH SAME USERNAME
				$query = "SELECT `".$this->table_name."`.`users5_dt8_dt1_dt6`,"." `".$this->table_name."`.`id`, `".$this->table_name."`.`users8_dt9_dt1`, `".$table1."`.`USERS_ROLE1_DT1_DT1_DT1`, `".$table1."`.`USERS_ROLE2_DT11_DT1_DT5`, `".$this->table_name."`.`USERS1_DT1_DT1_DT1`, `" . $this->table_name . "`.`USERS2_DT1_DT1_DT1`, `".$this->table_name."`.`users3_dt2_dt1_dt1`  FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."`, `" . $this->class_settings[ 'database_name' ] . "`.`".$table1."` WHERE `".$this->table_name."`.`users3_dt2_dt1_dt1`='".$username."' AND `".$this->table_name."`.`users8_dt9_dt1`=`".$table1."`.`id` LIMIT 1";
				
			}else{
				//users login
				$table1 = 'access_roles';
				
				//2. OBTAIN RECORD WITH SAME USERNAME
				$query = "SELECT `".$this->table_name."`.`users006` as 'password', `".$this->table_name."`.`id`, `".$this->table_name."`.`users009` as 'privilege', `".$table1."`.`access_roles001` as 'role', `".$table1."`.`access_roles002` as 'accessible_functions', `".$this->table_name."`.`users001` as 'firstname', `".$this->table_name."`.`users002` as 'lastname', `".$this->table_name."`.`users004` as 'email', `".$this->table_name."`.`users003` as 'remote_user_id' FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."`, `" . $this->class_settings[ 'database_name' ] . "`.`".$table1."` WHERE `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`users009` =  `".$table1."`.`id` AND `".$this->table_name."`.`users004` = '" . $username . "' LIMIT 1";
				
			}
			
			$tables = array( $this->table_name, $table1 );
			if( isset( $this->class_settings[ 'tables' ] ) ){
				$tables = $this->class_settings[ 'tables' ];
			}
			
			if( isset( $this->class_settings[ 'query' ] ) ){
				$query = $this->class_settings[ 'query' ];
			}
			
			$query_settings = array(
				'database' => $this->class_settings[ 'database_name' ] ,
				'connect' => $this->class_settings[ 'database_connection' ] ,
				'query' => $query ,
				'query_type' => 'SELECT',
				'tables' => $tables,
			);
			$sql_result = execute_sql_query($query_settings);
	
			$stored_password = '';
			$user_id = '';
			$user_details = array();
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval){
					$stored_password = $sval['password'];
					$user_id = $sval['id'];
					
					$user_details = array(
						'user_id' => $user_id,
						'user_privilege' => isset( $sval['privilege'] ) ? $sval['privilege'] : '',
						'user_role' => isset( $sval['role'] ) ? $sval['role'] : '' ,
						'accessible_functions' => isset( $sval['accessible_functions'] ) ? $sval['accessible_functions'] : '',
						'fname' => isset( $sval['firstname'] ) ? $sval['firstname'] : '',
						'lname' => isset( $sval['lastname'] ) ? $sval['lastname'] : '',
						'email' => isset( $sval['email'] ) ? $sval['email'] : '',
						'verification_status' => isset( $sval['verification_status'] ) ? $sval['verification_status'] : '',
						'remote_user_id' => isset( $sval['remote_user_id'] ) ? $sval['remote_user_id'] : '',
					);
				}
			}else{
				//Failed Authentication - wrong username
				$err = new cError(010101);
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
			
			//4. COMPARE PASSWORD
			if( $skip_password || $stored_password == $password ){
				$err = new cError(010103);
				$err->action_to_perform = 'notify';
				
                if( isset( $this->class_settings[ 'do_not_set_session' ] ) && $this->class_settings[ 'do_not_set_session' ] ){
                    $returned_value_value = 1;
                }else{
                    //Set session variables for currently logged in user
                    $returned_value_value = $this->_set_session($user_details);
                }
				
				if($returned_value_value==1){
					//INSERT LOGIN TIME INTO ACCESS LOG TABLE
					$return = $err->error();
					
					$return['user_details'] = $user_details;
					
					return $return;
				}else{
					switch($returned_value_value){
					case -1:
						//report unset session key variable
						$err = new cError(000004);
						$err->action_to_perform = 'notify';
						return $err->error();
					break;
					default:
						$err = new cError(010104);
						$err->action_to_perform = 'notify';
						return $err->error();
					break;
					}
				}
			}else{
				//Failed Authentication - wrong password
				$err = new cError(010102);	//disabled for security reasons
				//$err = new cError(010101);
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
		}
		
		private function _reset_user_password(){
			if( isset( $this->class_settings['table_name'] ) ){
				$this->table_name = $this->class_settings['table_name'];
			}
			
			//Verify email against a database table
			$q = "SELECT `id`, `".$this->table_fields['firstname']."` as firstname, `".$this->table_fields['lastname']."` as lastname  FROM `" . $this->class_settings[ 'database_name' ] . "`.`" . $this->table_name . "` WHERE MD5(`" . $this->table_fields['email'] . "`)='" . md5( $this->class_settings[ 'user_email' ] ) . "' AND `record_status`='1' LIMIT 1";
			
			/***********************/
			//1 - SINGLE
			$query_settings = array(
				'database' => $this->class_settings[ 'database_name' ],
				'connect' => $this->class_settings[ 'database_connection' ],
				'query' => $q,
				'query_type' => 'SELECT',
				'tables' => array( $this->table_name ),
			);
			/***********************/
			
			$query_result = execute_sql_query($query_settings);
			
			if( $query_result && is_array( $query_result ) && $query_result[0] ){
				
				//SET USER ID
				$user_id = $query_result[0]['id'];
				
				//First Name & Last Name
				$user_fname = $query_result[0][ 'firstname' ];
				$user_lname = $query_result[0][ 'lastname' ];
				
				//Create new password
				$new_password = generatePassword(8,1,1,1,0);
				$hashed_password = md5( $new_password . get_websalter() );
				
				
				//Update records
				$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`" . $this->table_fields[ 'password' ] . "`='" . $hashed_password . "', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE `".$this->table_name."`.`id` = '" . $user_id . "' AND `".$this->table_name."`.`record_status`='1' LIMIT 1";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$saved = execute_sql_query($query_settings);
				
				//Auditor
				auditor( $this->class_settings[ 'calling_page' ] , '' , $user_id , $this->class_settings[ 'user_email' ] , $this->class_settings[ 'database_connection' ] , $this->class_settings[ 'database_name' ] , 'password reset' , $this->table_name , 'user ' . $this->class_settings[ 'user_email' ] . ' password reset at ' . date("j-M-Y H:i") );
				
				if($saved){
					//MAIL NEW PASSWORD
					$this->class_settings['message_type'] = 18;	//Password Reset
					
					$this->class_settings[ 'user_full_name' ] =  ucwords($user_fname.' '.$user_lname);
					$this->class_settings[ 'user_id' ] = $user_id;
					
					$this->class_settings[ 'mail_certificate' ] = array(
						'new_password' => $new_password,
						'created_on' => date("j-M-Y H:i"),
					);
					
					$this->_send_email();
				}
				
				//Return Successful password reset
				$err = new cError(010106);
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAuthentication.php';
				
				return $err->error();
				
			}
			
			//Auditor
			auditor( $this->class_settings[ 'calling_page' ] , '' , '' , $this->class_settings[ 'user_email' ] , $this->class_settings[ 'database_connection' ] , $this->class_settings[ 'database_name' ] , 'password reset failed' , $this->table_name , 'user ' . $this->class_settings[ 'user_email' ] . ' failed to reset password at ' . date("j-M-Y H:i") );
			
			//Return Failed Password Reset - invalid email
			$err = new cError(010105);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAuthentication.php';
			
			return $err->error();
		}
		
		private function _send_email(){
			
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'user_email' ];
			$email->class_settings[ 'destination' ]['full_name'][] = $this->class_settings[ 'user_full_name' ];
			$email->class_settings[ 'destination' ]['id'][] = $this->class_settings[ 'user_id' ];
			
			$email->mail_certificate = $this->class_settings[ 'mail_certificate' ];
			
			$email->emails();
			
		}
		
		private function _set_session($user_details){
			if(isset($_SESSION['key']) && $_SESSION['key']){
				
				//1. VALIADTE INPUT DATA
				if(isset($user_details['user_id']) && $user_details['user_id']){
					
					//2. ENCRYPT SESSION ID
					$key = md5('ucert'.$_SESSION['key']);
					if( isset( $_SESSION[$key] ) )
						unset($_SESSION[$key]);
					
					//3. GET USER BIO DETAILS FROM USERS CLASS
					
					//4. SET SESSION
					$_SESSION[$key] = array(
						//Set First Name and Last Name
						'fname' => ucwords($user_details['fname']),
						'lname' => ucwords($user_details['lname']),
						
						//Abbreviated Name
						'initials' => ucwords(substr($user_details['fname'],0,1).' '.$user_details['lname']),
						
						'email' => strtolower($user_details['email']),
						
						//Set User Role
						'role' => ucwords($user_details['user_role']),
						
						//Profile Picture
						//'picture' => str_replace('../','',$a['profile_picture_dt10_dt1_dt3']),
						
						//Set User ID
						'id' => $user_details['user_id'],
						
						//Set User Privilege
						'privilege' => $user_details['user_privilege'],
						
						//Accessible Functions
						'functions' => $user_details['accessible_functions'],
						
						//Set Login Time
						'login_time' => date("U"),
						
						//Set Account Verification Status
						'verification_status' => $user_details['verification_status'],
						
						'remote_user_id' => $user_details[ 'remote_user_id' ],
					);
				}
				//5. RETURN SUCCESS
				if(isset($_SESSION[$key]) && is_array($_SESSION[$key])){
					return 1;
				}else{
					//log error
					return 0;
				}
			}else{
				//unset session key variable
				return -1;
			}
		}
		
	}
?>