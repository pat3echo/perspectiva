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
			'birthday' => 'site_users010',
			'sex' => 'site_users011',
			'photograph' => 'site_users018',
			
			'role' => 'site_users009',
			
			'updated_primary_address' => 'site_users019',
			'verified_email_address' => 'site_users020',
			'functional_email_address' => 'site_users021',
			
			'address_fields' => array(
				'country' => 'site_users012',
				'state' => 'site_users013',
				'city' => 'site_users014',
				'street 1' => 'site_users015',
				'street 2' => 'site_users016',
				'postal code' => 'site_users017',
			),
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
			case 'get_all_users_countries':
				$returned_value = $this->_get_all_users_countries();
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
			case 'get_bank_account_verification_fee':
				$returned_value = $this->_get_bank_account_verification_fee();
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
			case 'site_users_google_authentication':
				$returned_value = $this->_site_users_google_authentication();
			break;
			case 'site_users_facebook_authentication':
				$returned_value = $this->_site_users_facebook_authentication();
			break;
			case 'create_new_user_account_and_authenticate_user':
				$returned_value = $this->_create_new_user_account_and_authenticate();
			break;
            case 'get_newly_registered_users':
			case 'get_all_registered_users':
				$returned_value = $this->_get_all_registered_users();
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
		
		private function _get_registration_form(){
			
			$general_settings_data = $this->_get_general_settings();
			
			$this->class_settings['form_heading_title'] = '';
			
			$this->class_settings['agreement_text'] = SITE_USERS_REGISTER_GET_STARTED;
			
			if( isset( $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] ){
				$this->class_settings['agreement_text'] .= ' <a href="' . $general_settings_data[ $this->table_name ][ 'TERMS OF SERVICE URL' ][ 'default' ] . '" target="_blank" title="' . SITE_USERS_TERMS_OF_SERVICE . '">' . SITE_USERS_TERMS_OF_SERVICE .'</a>';
			}
			
			if( isset( $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] ){
				$this->class_settings['agreement_text'] .= ' <a href="' . $general_settings_data[ $this->table_name ][ 'PRIVACY POLICY URL' ][ 'default' ] . '" target="_blank" title="' . SITE_USERS_PRIVACY_POLICY . '">' . SITE_USERS_PRIVACY_POLICY .'</a>';
			}
			
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW RECAPTCHA' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW RECAPTCHA' ][ 'default' ] == 'TRUE' ){
				$this->class_settings['show_recaptcha'] = 1;
			}
			//print_r($general_settings_data);
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW ADDRESS FIELDS' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW ADDRESS FIELDS' ][ 'default' ] == 'TRUE' ){
				$this->class_settings['unset_hidden_records_css'] = $this->table_fields['address_fields'];
			}
            
			
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW NAME FIELDS' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW NAME FIELDS' ][ 'default' ] != 'TRUE' ){
				$this->class_settings['hidden_records'][$this->table_fields['firstname']] = 1;
				$this->class_settings['hidden_records'][$this->table_fields['lastname']] = 1;
			}
			
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW PHONE NUMBER FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW PHONE NUMBER FIELD' ][ 'default' ] != 'TRUE' ){
				$this->class_settings['hidden_records'][$this->table_fields['phonenumber']] = 1;
			}
			
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW BIRTHDAY FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW BIRTHDAY FIELD' ][ 'default' ] != 'TRUE' ){
				$this->class_settings['hidden_records'][$this->table_fields['birthday']] = 1;
			}
			
			if( isset( $general_settings_data[ $this->table_name ][ 'SHOW SEX FIELD' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SHOW SEX FIELD' ][ 'default' ] != 'TRUE' ){
				$this->class_settings['hidden_records'][$this->table_fields['sex']] = 1;
			}
			
            if( defined( 'SELECTED_COUNTRY_ID' ) && SELECTED_COUNTRY_ID != '1' ){
                $this->class_settings['form_values_important'][ $this->table_fields['address_fields']['country'] ] = SELECTED_COUNTRY_ID;
            }
               
			$this->class_settings['hidden_records']['site_users006'] = 1;
			$this->class_settings['hidden_records']['site_users018'] = 1;
			$this->class_settings['hidden_records']['site_users019'] = 1;
			$this->class_settings['hidden_records']['site_users020'] = 1;
			$this->class_settings['hidden_records']['site_users021'] = 1;
			
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'site_users_guest_registration_form':
				$this->class_settings['form_heading_title'] = 'Guest Checkout';
				$this->class_settings['hidden_records'][$this->table_fields['sex']] = 1;
				$this->class_settings['hidden_records'][$this->table_fields['birthday']] = 1;
				//$this->class_settings['unset_hidden_records_css'] = $this->table_fields['address_fields'];
				$this->class_settings['show_recaptcha'] = 0;
			break;
			}
			
            $this->class_settings[ 'form_submit_button' ] = 'Sign Up';
            
			return $this->_generate_new_data_capture_form();
		}
		
		private function _site_registration(){
			$general_settings_data = $this->_get_general_settings();
			
			$this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=save_registration';
			
			$form_data = $this->_get_registration_form();
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/registration.js';
			$this->class_settings[ 'js' ][] = 'my_js/custom/authentication.js';
            $this->class_settings[ 'js' ][] = 'my_js/custom/website.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/site_registration.css';
			
            $serviceURL = array();
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW GMAIL SIGNIN']['default'] ) ){
                //get google url
                $serviceURL['ALLOW GMAIL SIGNIN'] = $this->google( array( 'type' => "getURL" ) );
            }
            
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW FACEBOOK SIGNIN']['default'] ) ){
                //get facebook url
                $serviceURL['ALLOW FACEBOOK SIGNIN'] = $this->facebook( array( 'type' => "getURL" ) );
            }
            
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings[ 'action_to_perform' ] = 'setup_website';
			$returning = $website->website();
			
			$this->class_settings = $website->class_settings;
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'html' => $form_data['html'],
				'table_fields' => $this->table_fields,
				'general_settings' => $general_settings_data[ $this->table_name ],
                'service_url' => $serviceURL,
                'pagepointer' => $this->class_settings['calling_page'],
				'page_banners' => get_page_banners(),
			);
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sign-up.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning['html'] = '<div class="container"><div id="page-wrapper"><br />' . $script_compiler->script_compiler() . '</div></div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function _get_authentication_form(){
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'site_users_authentication_form_only':
				$this->class_settings['form_heading_title'] = '';
				
				if( ! isset( $this->class_settings[ 'form_action' ] ) )
					$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=authenticate_user';
					
				$this->class_settings[ 'form_submit_button' ] = 'Sign In';
			break;
			case 'site_users_authentication':
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=authenticate_user';
				
				$this->class_settings[ 'forgot_password_link' ] = '<a href="?action='.$this->table_name.'&todo=site_users_reset_password" class="special" rel="external">Forgot your password?</a>';
					
				$this->class_settings[ 'form_submit_button' ] = 'Sign In';
			break;
			case 'site_users_reset_password':
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=reset_user_password';
				
				$this->class_settings[ 'forgot_password_link' ] = '<a href="?action='.$this->table_name.'&todo=site_users_authentication" class="special" rel="external">Sign in</a>';
				
				$this->class_settings['hidden_records']['site_users007'] = 1;
				
				$this->class_settings[ 'form_submit_button' ] = 'Reset Password';
			break;
			}
			
			$this->class_settings['hidden_records']['site_users001'] = 1;
			$this->class_settings['hidden_records']['site_users002'] = 1;
			$this->class_settings['hidden_records']['site_users004'] = 1;
			$this->class_settings['hidden_records']['site_users005'] = 1;
			$this->class_settings['hidden_records']['site_users006'] = 1;
			$this->class_settings['hidden_records']['site_users008'] = 1;
			$this->class_settings['hidden_records']['site_users009'] = 1;
			$this->class_settings['hidden_records']['site_users010'] = 1;
			$this->class_settings['hidden_records']['site_users011'] = 1;
			$this->class_settings['hidden_records']['site_users012'] = 1;
			$this->class_settings['hidden_records']['site_users013'] = 1;
			$this->class_settings['hidden_records']['site_users014'] = 1;
			$this->class_settings['hidden_records']['site_users015'] = 1;
			$this->class_settings['hidden_records']['site_users016'] = 1;
			$this->class_settings['hidden_records']['site_users017'] = 1;
			$this->class_settings['hidden_records']['site_users018'] = 1;
			$this->class_settings['hidden_records']['site_users019'] = 1;
			$this->class_settings['hidden_records']['site_users020'] = 1;
			$this->class_settings['hidden_records']['site_users021'] = 1;
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _site_users_authentication(){
			
			$general_settings_data = $this->_get_general_settings();
			
			$verification_data = $this->_verify_email_address();
			
			if( is_array($verification_data) && isset( $verification_data['typ'] ) && $verification_data['typ'] == 'authenticated' && isset( $verification_data['redirect_url'] ) ){
				header( 'location: ' . $verification_data['redirect_url'] );
				exit;
			}
			
			$this->class_settings['form_heading_title'] = '';
			
			//$this->class_settings['form_class'] = 'skip-validation';
			$form_data = $this->_get_authentication_form();
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/authentication.js';
			$this->class_settings[ 'js' ][] = 'my_js/custom/website.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/site_registration.css';
			
            $serviceURL = array();
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW GMAIL SIGNIN']['default'] ) ){
                //get google url
                $serviceURL['ALLOW GMAIL SIGNIN'] = $this->google( array( 'type' => "getURL" ) );
            }
            if( isset( $general_settings_data[ $this->table_name ]['ALLOW FACEBOOK SIGNIN']['default'] ) ){
                //get facebook url
                $serviceURL['ALLOW FACEBOOK SIGNIN'] = $this->facebook( array( 'type' => "getURL" ) );
            }
            
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings[ 'action_to_perform' ] = 'setup_website';
			$returning = $website->website();
			
			$this->class_settings = $website->class_settings;
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'html' => $form_data['html'],
				'verification_data' => $verification_data,
				'general_settings' => $general_settings_data[ $this->table_name ],
				'service_url' => $serviceURL,
				'pagepointer' => $this->class_settings['calling_page'],
				'page_banners' => get_page_banners(),
			);
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sign-in.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning['html'] = '<div class="container"><div id="page-wrapper"><br />' . $script_compiler->script_compiler() . '</div></div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
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
		
		private function _save_registration(){
			$returning_html_data = array();
			
			//check for duplicate email
			if( isset( $this->table_fields[ 'email' ] ) && $this->table_fields[ 'email' ] ){
				if( isset( $_POST[ $this->table_fields[ 'email' ] ] ) && $_POST[ $this->table_fields[ 'email' ] ] ){
					$email = $_POST[ $this->table_fields[ 'email' ] ];
					$hashed_email = md5( $_POST[ $this->table_fields[ 'email' ] ] );
					
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE MD5( `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` ) = '" . $hashed_email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query( $query_settings );
					
					if( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ){
						//DUPLICATE EMAIL ADDRESS
						$err = new cError(000102);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cSite_users.php';
						$err->method_in_class_that_triggered_error = '_save_registration';
						$err->additional_details_of_error = 'Duplicate Email Address';
						
						$returning_html_data = $err->error();
						$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
						$returning_html_data['status'] = 'saved-form-data';
						
						return $returning_html_data;
					}
				}
			}
			
			$returning_html_data = $this->_save_changes();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'saved' ){
				
                $settings = array(
					'cache_key' => $this->table_name . '-' . 'newly-registered-users',
					'directory_name' => $this->table_name,
					'permanent' => true,
				);
				clear_cache_for_special_values( $settings );
                
                $returning_html_data['err'] = 'Welcome!';
				$returning_html_data['msg'] = 'Please wait while we set-up your account';
				
				//CHECK IF USER ADDRESS IS SET
				$this->class_settings['id'] = $returning_html_data['saved_record_id'];
				$this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
				$user_details = $this->_get_user_details();
				
				if( isset( $user_details['email'] ) && isset( $user_details[ 'password' ] ) ){
					$this->class_settings['username'] = $user_details['email'];
					$this->class_settings['password'] = $user_details[ 'password' ];	//hashed password
					
					$auth_data = $this->_confirm_user_authentication_details();
					
					if( isset( $auth_data['user_details'] ) )$returning_html_data['user_details'] = $auth_data['user_details'];
					
					//Send Welcome Email
					$this->class_settings['message_type'] = 1;
					$this->_send_email();
				}
				
				//create pending task
				$this->_create_pending_task( $user_details );
                
				$general_settings_data = $this->_get_general_settings();
				
				if( isset( $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ){
					
					$returning_html_data['status'] = 'redirect-to-successful-registration-url';
					$returning_html_data['redirect_url'] = $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ]['default'];
					
				}
				
				if( isset( $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'ALLOW EMAIL ACCOUNT VERIFICATION' ][ 'default' ] == 'TRUE' ){
					
					if( ! ( isset( $user_details[ $this->class_settings['id'] ]['verified_email_address'] ) && $user_details[ $this->class_settings['id'] ][ 'verified_email_address' ] == 'yes' ) ){
						
						$this->class_settings['message_type'] = 2;	//Send Verification Email
						$this->_send_email();

						//CREATE NOTIFICATION && //CREATE PENDING TASKS
						$this->class_settings['notification_data'] = array(
							'title' => 'Verify Your Email Address',
							'detailed_message' => 'A verification email was sent to your email address, you should click the link in the email to verify your email account<br /><br />Check your <b>spam folder</b> if you cannot find the email',
							'send_email' => 'no',
							'notification_type' => 'pending_task',
							'target_user_id' => $this->class_settings['id'],
							'class_name' => $this->table_name,
							'method_name' => 'verify_email_address',
							
							'task_status' => 'pending',
							'task_type' => 'system_generated',
							
							'trigger_function' => '?action=site_users&todo=display_user_details',
						);
						
						$notifications = new cNotifications();
						$notifications->class_settings = $this->class_settings;
						$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
						$notifications->notifications();
					}else{
						//CREATE ACCOUNT VERIFICATION TASK
						$returning_html_data['redirect_url'] = $this->_activate_merchant_account_verification();
					}
					
				}else{
					//CREATE ACCOUNT VERIFICATION TASK
					$returning_html_data['redirect_url'] = $this->_activate_merchant_account_verification();
				}
			}
			
			return $returning_html_data;
		}
		
        private function _create_pending_task( $user_details = array() ){
            if( ! ( isset( $user_details['address_status'] ) && $user_details[ 'address_status' ] ) ){
                
                $address = '';
                if( isset( $user_details['address_html'] ) )
                    $address = $user_details['address_html'];
                
                //CREATE NOTIFICATION && //CREATE PENDING TASKS
                $this->class_settings['notification_data'] = array(
                    'title' => 'Update Primary Contact Address',
                    'detailed_message' => 'Your primary contact address is incomplete, endeavour to update your address<br /><br />Your current address is<address>' . $address . '</address>',
                    'send_email' => 'no',
                    'notification_type' => 'pending_task',
                    'target_user_id' => $this->class_settings['id'],
                    'class_name' => $this->table_name,
                    'method_name' => 'save_contact_info',
                    
                    'task_status' => 'pending',
                    'task_type' => 'system_generated',
                    
                    'trigger_function' => '?action=site_users&todo=display_user_details',
                );
                
                $notifications = new cNotifications();
                $notifications->class_settings = $this->class_settings;
                $notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
                $notifications->notifications();
                
                //email verification task
                if( $user_details['verified_email_address'] != 'yes' ){
                    $this->class_settings['notification_data'] = array(
                        'title' => 'Verify Your Email Address',
                        'detailed_message' => 'A verification email was sent to your email address, you should click the link in the email to verify your email account<br /><br />Check your <b>spam folder</b> if you cannot find the email',
                        'send_email' => 'no',
                        'notification_type' => 'pending_task',
                        'target_user_id' => $this->class_settings['id'],
                        'class_name' => $this->table_name,
                        'method_name' => 'verify_email_address',
                        
                        'task_status' => 'pending',
                        'task_type' => 'system_generated',
                        
                        'trigger_function' => '?action=site_users&todo=display_user_details',
                    );
                    
                    $notifications->class_settings = $this->class_settings;
                    $notifications->notifications();
                }
            }
        }
        
		private function _save_user_info(){
			$returning_html_data = array();
			
			//check for matching old password
			if( isset( $this->table_fields[ 'oldpassword' ] ) && $this->table_fields[ 'oldpassword' ] ){
				if( isset( $_POST[ $this->table_fields[ 'oldpassword' ] ] ) && $_POST[ $this->table_fields[ 'oldpassword' ] ] ){
					
					$hashed_password = md5( $_POST[ $this->table_fields[ 'oldpassword' ] ] . get_websalter() );
					
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'password' ]."` = '" . $hashed_password . "' AND `" . $this->table_name . "`.`record_status`='1' AND `id`='".$this->class_settings['user_id']."'";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query( $query_settings );
					
					if( ! ( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty ( $sql_result[0] ) ) ){
						$err = new cError(000103);
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cSite_users.php';
						$err->method_in_class_that_triggered_error = '_save_user_info';
						$err->additional_details_of_error = 'Incorrect Password';
						
						$returning_html_data = $err->error();
						$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
						$returning_html_data['status'] = 'saved-form-data';
						
						return $returning_html_data;
					}
				}
			}
			
			$returning_html_data = $this->_save_changes();
			
			if( isset( $returning_html_data['typ'] ) && $returning_html_data['typ'] == 'saved' ){
				
				switch ( $this->class_settings['action_to_perform'] ){
					//update task to complete
				case 'save_contact_info':
				case 'save_personal_info':
					$returning_html_data['status'] = 'saved-personal-info';
                    
                    $this->class_settings['hacked_calling_page'] = './engine/'; //hacked page pointer
					$returning_html_data['html'] = $this->_get_user_info_for_display();
					
					if( ! ( isset( $this->class_settings['user_details'][ 'updated_primary_address' ] ) && $this->class_settings['user_details'][ 'updated_primary_address' ] == 'yes' ) ){
						
						if( isset( $this->class_settings['user_details'][ 'address_status' ] ) && $this->class_settings['user_details'][ 'address_status' ] ){
						
							$this->class_settings['notification_data'] = array(
								'title' => 'Primary Contact Address Updated',
								'detailed_message' => 'Congratulations, you\'ve successfully updated your primary contact address',
								'send_email' => 'no',
								'notification_type' => 'completed_task',
								'target_user_id' => $this->class_settings['user_id'],
								'class_name' => $this->table_name,
								'method_name' => 'save_contact_info',
								
								'task_status' => 'complete',
								'task_type' => 'system_generated',
							);
							
							$notifications = new cNotifications();
							$notifications->class_settings = $this->class_settings;
							$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
							$notifications->notifications();
							
							//update address status
							$settings_array = array(
								'database_name' => $this->class_settings['database_name'] ,
								'database_connection' => $this->class_settings['database_connection'] ,
								'table_name' => $this->table_name ,
								'field_and_values' => array(
									
									$this->table_fields['updated_primary_address'] => array( 'value' => 'yes' ),
									
								) ,
								'where_fields' => 'id',
								'where_values' => $returning_html_data['saved_record_id'],
							);
							
							$save = update( $settings_array );
						}
						
					}
					
					$returning_html_data['form_id'] = $this->table_name . '-form';
					
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_user_details();
				break;
				case 'save_password_info':
					$returning_html_data['status'] = 'saved-password-info';
					$returning_html_data['form_id'] = $this->table_name . '-form';
					
					$returning_html_data['err'] = 'Password Change Successful!';
					$returning_html_data['msg'] = 'Your password has been successfully changed';
				break;
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
		
		private function _get_all_users_countries(){
			$returned_data = array();
			
			$cache_key = $this->table_name . '-all-users-countries';
			
            $settings = array(
                'cache_key' => $cache_key,
				'permanent' => true,
            );
            
            //CHECK IF CACHE IS SET
            $cached_values = get_cache_for_special_values( $settings );
            if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
                return $cached_values;
            }
				
			$this->class_settings['where'] = " WHERE `record_status`='1' ";
			
            $select = "`id`, `".$this->table_fields['address_fields']['country']."` as 'country', `".$this->table_fields['email']."` as 'email' ";
            
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
				}
                
                //Cache Settings
                $settings = array(
                    'cache_key' => $cache_key,
                    'cache_values' => $returned_data,
                    'permanent' => true,
                );
                set_cache_for_special_values( $settings );
                
			}
			
            return $returned_data;
		}
		
		private function _display_user_details_data_capture_form(){
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				//'site_users006' => 1,
				'site_users009' => 1,
				'site_users019' => 1,
				'site_users020' => 1,
			);
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_site_users_address_data_capture_form':
				foreach( $this->table_fields as $key => $val ){
					if( $key != 'address_fields' ){
						$this->class_settings[ 'hidden_records' ][ $val ] = 1;
					}
				}
			break;
			}
			
			$this->class_settings[ 'hidden_records_css' ] = array();
			
            //$this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=save_contact_info';
            
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes';
			
			return $this->_generate_new_data_capture_form();
		}
		
		private function _display_user_details(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			$processing_status = $this->_save_changes();
			
			if( is_array( $processing_status ) && !empty( $processing_status ) ){
				$result_of_all_processing = $processing_status;
			}
			
			$returned_data = $this->_display_user_details_data_capture_form();
			
			$this->class_settings[ 'js' ][] = 'my_js/custom/display-user-details.js';
			$this->class_settings[ 'css' ][] = 'css/template-1/contact-details.css';
			
			$dashboard = new cDashboard();
			$dashboard->class_settings = $this->class_settings;
			$dashboard->class_settings[ 'action_to_perform' ] = 'setup_dashboard';
			$returning = $dashboard->dashboard();
			
			$this->class_settings = $dashboard->class_settings;
			
			$returning[ 'html' ] = '<div id="page-wrapper">';
				$returning[ 'html' ] .= '<br /><div class="row"><div class="col-lg-9"><div class="panel panel-default"><div class="panel-heading"><i class="fa fa-user fa-fw"></i> User Profile</div><div class="panel-body">';
				
					$returning[ 'html' ] .= '<style type="text/css">#site_users-form{ display:none;	}</style>';
					$returning[ 'html' ] .= '<div class="row-fluid" id="display-user-details-view">';
						$returning[ 'html' ] .= $this->_get_user_info_for_display();
					$returning[ 'html' ] .= '</div><hr />';
					
					$returning[ 'html' ] .= $returned_data['html'];
					
				$returning[ 'html' ] .= '</div></div></div></div>';
			$returning[ 'html' ] .= '</div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function _get_user_info_for_display(){
			//GET USER DETAILS
			//$this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['user_id'] . "'";
			$this->class_settings['user_details'] = $this->_get_user_details();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/site_users/display-user-details.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			$script_compiler->class_settings[ 'data' ] = array(
				'user_details' => $this->class_settings['user_details'],
				'save_password_info' => '?action='.$this->table_name.'&todo=save_password_info',
				'save_personal_info' => '?action='.$this->table_name.'&todo=save_personal_info',
				'save_contact_info' => '?action='.$this->table_name.'&todo=save_contact_info',
				//'page_pointer' => $this->class_settings['calling_page'],
				'page_pointer' => isset( $this->class_settings['hacked_calling_page'] )?$this->class_settings['hacked_calling_page']:$this->class_settings['calling_page'],
			);
			
			return $script_compiler->script_compiler();
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
		
		private function _activate_merchant_account_verification(){
			
			//1. if activation is required based on user country
			$activation = $this->_get_bank_account_verification_fee();
			
			$role = '';
			if( isset( $this->class_settings['user_details'][ ''.$this->class_settings['user_id'] ][ 'role' ] ) ){
				$role = $this->class_settings['user_details'][ ''.$this->class_settings['user_id'] ][ 'role' ];
			}
			
			if( $activation ){
				//2. check gen settings for user role
				if( $role == 'seller' ){
					
					//CREATE NOTIFICATION && //CREATE PENDING TASKS
					$this->class_settings['notification_data'] = array(
						'title' => 'Verify Your Bank Account',
						'detailed_message' => 'Your don\'t have a verified bank account, that would enable us send payment to you once your items have been purchased',
						'send_email' => 'no',
						'notification_type' => 'pending_task',
						'target_user_id' => $this->class_settings['user_id'],
						'class_name' => $this->table_name,
						'method_name' => 'bank_account_verified',
						
						'task_status' => 'pending',
						'task_type' => 'system_generated',
						
						'trigger_function' => '?action=site_users&todo=display_user_details',
					);
					
					$notifications = new cNotifications();
					$notifications->class_settings = $this->class_settings;
					$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
					$notifications->notifications();
					
					//Send Verify Bank Account Email
					/*
					$this->class_settings['message_type'] = 1;
					$this->_send_email();
					*/
					
					//. redirect to activation menu - ACTIVATION MENU + Welcome Message
					return '?action=merchant_accounts&todo=bank_account_manager';
				}
			}
			
		}
		
		private function _get_bank_account_verification_fee(){
			$user_details = get_site_user_details( array( 'id' => $this->class_settings['user_id'] ) );
			
			$country = '';
			if( isset( $user_details[ ''.$this->class_settings['user_id'] ][ 'country' ] ) ){
				$country = $user_details[ ''.$this->class_settings['user_id'] ][ 'country' ];
			}
			
			//1. if activation is required based on user country
			return get_general_settings_value( array(
				'table' => $this->table_name,
				'key' => 'MERCHANT BANK ACCOUNT VERIFICATION FEE',
				'country' => $country,
			) );
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
		
        private function _site_users_google_authentication(){
            $this->google( array( 'type' => 'process' ) );
        }
        
        private function _site_users_facebook_authentication(){
            $this->facebook( array( 'type' => 'process' ) );
        }
        
        private function google( $settings = array() ){
            require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/apiClient.php";
            require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/contrib/apiOauth2Service.php";
                
            $client = new apiClient();
            $client->setApplicationName("Zidoff");
            // Visit https://code.google.com/apis/console?api=plus to generate your
            // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
             $client->setClientId('339311571981-ead3q8g2sq5ts61fudkmhvcn0khlp43f.apps.googleusercontent.com');

             $client->setClientSecret('OB2am2KOxRDhIX5H6sEdio80');

             $client->setRedirectUri('https://www.zidoff.com/google/');
            // $client->setDeveloperKey('insert_your_developer_key');
            $oauth2 = new apiOauth2Service($client);
            
            if( isset( $settings['type'] ) ){
                switch( $settings['type'] ){
                case "getURL":
                    return $client->createAuthUrl();
                break;
                case "process":
                    if (isset($_GET['code'])) {
                      $client->authenticate();
                      $_SESSION['token'] = $client->getAccessToken();
                      
                      unset($_GET['code']);
                      $getparams = '';
                        foreach( $_GET as $key => $val ){
                            if($getparams)$getparams .= '&'.$key.'='.$val;
                            else $getparams = $key.'='.$val;
                        }
                        
                      $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?toks='.$_SESSION['token'] .'&'.$getparams;
                      
                      header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
                    }

                    if (isset($_SESSION['token']) || isset($_GET['toks'])) {
                    
                     if($_GET['toks']!=null)$clientTok = $_GET['toks'];
                     if(isset($_SESSION['token']) && $_SESSION['token']!=null)$clientTok = $_SESSION['token'];
                        $clientTok = stripslashes($clientTok);
                        $client->setAccessToken($clientTok);
                    }

                    if (isset($_REQUEST['logout'])) {
                      unset($_SESSION['token']);
                      $client->revokeToken();
                    }

                    if ($client->getAccessToken()) {
                      $user = $oauth2->userinfo->get();
                        
                        $email = trim( filter_var($user['email'], FILTER_SANITIZE_EMAIL) );
                        
                        //check for existing email
                        $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` = '" . $email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
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
                            //REGISTERED EMAIL ADDRESS
                            $this->class_settings['username'] = $email;
                            $this->class_settings['password'] = $this->default_password;
                            
                            $this->class_settings['skip_password'] = true;
                            
                            //Authenticate user with email only
                            //redirect to successful auth url
                            $returning_html_data = $this->_confirm_user_authentication_details();
                            if( isset( $returning_html_data['redirect_url'] ) ){
                                header('Location: '.$returning_html_data['redirect_url'] );
                                exit;
                            }
                        }
                        //set user email verification as verified
                        
                        /*
                        Array ( [id] => 111325829366867272256 [email] => pat2echo@gmail.com [verified_email] => 1 [name] => Patrick Ogbuitepu [given_name] => Patrick [family_name] => Ogbuitepu [link] => https://plus.google.com/111325829366867272256 [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [gender] => male [locale] => en ) Array ( [email] => pat2echo@gmail.com [name] => Patrick Ogbuitepu [id] => 111325829366867272256 [gender] => male [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [locale] => en )
                        */
                        //create user account & mail user default password
                        $data['firstname'] = filter_var($user['given_name'], FILTER_SANITIZE_STRING);
                        $data['lastname'] = filter_var($user['family_name'], FILTER_SANITIZE_STRING);
                        
                        $data['email'] = $email;
                        $data['sex'] = filter_var($user['gender'], FILTER_SANITIZE_STRING);
                        $data['verified_email_address'] = 'yes';
                        
                        $this->class_settings['user_data'] = $data;
                        
                        $this->_create_new_user_account_and_authenticate();
                        
                        // The access token may have been updated lazily.
                        $_SESSION['token'] = $client->getAccessToken();
                    }
                break;
                }
            }
            
        }
        
        private function facebook( $settings = array() ){
            
            //require_once $this->class_settings['calling_page']."classes/google-api-php-client/src/apiClient.php";
            
            if( isset( $settings['type'] ) ){
                
                switch( $settings['type'] ){
                case "getURL":
                    //echo $helper->getLoginUrl();exit;
                    return '/facebook';
                break;
                case "process":
                    if ( isset( $_SESSION['facebook_object']['fbid'] ) && $_SESSION['facebook_object']['fbid'] ) {
                        
                        $email = trim( filter_var( $_SESSION['facebook_object']['email'] , FILTER_SANITIZE_EMAIL) );
                        
                        if ( $email ) {
                            //check for existing email
                            $query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`".$this->table_fields[ 'email' ]."` = '" . $email . "' AND `" . $this->table_name . "`.`record_status`='1' ";
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
                                //REGISTERED EMAIL ADDRESS
                                $this->class_settings['username'] = $email;
                                $this->class_settings['password'] = $this->default_password;
                                
                                $this->class_settings['skip_password'] = true;
                                
                                //Authenticate user with email only
                                //redirect to successful auth url
                                $returning_html_data = $this->_confirm_user_authentication_details();
                                if( isset( $returning_html_data['redirect_url'] ) ){
                                    header('Location: '.$returning_html_data['redirect_url'] );
                                    exit;
                                }
                            }
                            //set user email verification as verified
                            
                            /*
                            Array ( [id] => 111325829366867272256 [email] => pat2echo@gmail.com [verified_email] => 1 [name] => Patrick Ogbuitepu [given_name] => Patrick [family_name] => Ogbuitepu [link] => https://plus.google.com/111325829366867272256 [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [gender] => male [locale] => en ) Array ( [email] => pat2echo@gmail.com [name] => Patrick Ogbuitepu [id] => 111325829366867272256 [gender] => male [picture] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg [locale] => en )
                            */
                            //create user account & mail user default password
                            $data['firstname'] = filter_var( $_SESSION['facebook_object']['first_name'] , FILTER_SANITIZE_STRING);
                            $data['lastname'] = filter_var( $_SESSION['facebook_object']['last_name'] , FILTER_SANITIZE_STRING);
                            
                            $data['email'] = $email;
                            $data['sex'] = filter_var( $_SESSION['facebook_object']['gender'] , FILTER_SANITIZE_STRING);
                            
                            $data['verified_email_address'] = 'no';
                            if( $_SESSION['facebook_object']['email_source'] )
                                $data['verified_email_address'] = 'yes';
                            
                            $this->class_settings['user_data'] = $data;
                            
                            $this->_create_new_user_account_and_authenticate();
                            
                            unset($_SESSION['facebook_object']);
                        }else{
                            //prompt for user email
                            $script_compiler = new cScript_compiler();
                            $script_compiler->class_settings = $this->class_settings;
                            
                            $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                            $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/facebook-no-email-prompt.php' );
                            echo $script_compiler->script_compiler();
                            exit;
                        }
                    }else{
                        //not logged in
                    }
                break;
                }
            }
            
        }
        
        private function _create_new_user_account_and_authenticate(){
            if( ! ( isset( $this->class_settings['user_data'] ) && $this->class_settings['user_data'] ) )return 0;
            
            $data = $this->class_settings['user_data'];
            
            $record_id = get_new_id();
            
            $settings_array = array(
                'database_name' => $this->class_settings['database_name'] ,
                'database_connection' => $this->class_settings['database_connection'] ,
                'table_name' => $this->table_name ,
                'field_and_values' => array(
                    
                    'id' => array( 'value' => $record_id ),
                    
                    'created_by' => array( 'value' => $record_id ),
                    'creation_date' => array( 'value' => date("U") ),
                    'modified_by' => array( 'value' => $record_id ),
                    'modification_date' => array( 'value' => date("U") ),
                    'ip_address' => array( 'value' => get_ip_address() ),
                    'record_status' => array( 'value' => 1 ),
                ) ,
            );
            
            if( ! ( isset( $data['password'] ) && $data['password'] ) ){
                $data['password'] = md5( $this->default_password . get_websalter());
                $data['confirmpassword'] = md5( $this->default_password . get_websalter());
            }
            
            if( ! ( isset( $data['role'] ) && $data['role'] ) ){
                $data['role'] = 'buyer';
            }
            
            foreach( $data as $k => $v ){
                if( isset( $this->table_fields[ 'address_fields' ][ $k ] ) && $this->table_fields[ 'address_fields' ][ $k ] )
                    $settings_array['field_and_values'][ $this->table_fields[ 'address_fields' ][ $k ] ] = array( 'value' => $v );
                else
                    $settings_array['field_and_values'][ $this->table_fields[ $k ] ] = array( 'value' => $v );
            }
            
            //if email does not exists register user
            $save = create( $settings_array );
            
            if( $save ){
                $this->class_settings['id'] = $record_id;
                $this->class_settings['where'] = "WHERE `id`='" . $this->class_settings['id'] . "'";
                $user_details = $this->_get_user_details();
                
                //redirect to successful registration url
                $this->class_settings['username'] = $user_details['email'];
                $this->class_settings['password'] = $user_details[ 'password' ];	
                $this->class_settings['skip_password'] = true;
                
                $auth_data = $this->_confirm_user_authentication_details();
                
                //send user account creation details + password
                //Send Welcome Email
                $this->class_settings[ 'mail_certificate' ]['body_start'] = 'DEFAULT PASSWORD: <b>'.$this->default_password.'</b><br />';
                $this->class_settings['message_type'] = 1;
                $this->_send_email();
                
                //create pending task
                $this->_create_pending_task( $user_details );
                
                if( isset( $this->class_settings['skip_redirection'] ) && $this->class_settings['skip_redirection'] ){
                    return $record_id;
                }
                
                $general_settings_data = $this->_get_general_settings();
                
                if( isset( $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ) && $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ][ 'default' ] ){
                    
                    $redirect_url = $general_settings_data[ $this->table_name ][ 'SUCCESSFUL REGISTRATION URL' ]['default'];
                    header('Location: '.$redirect_url );
                    exit;
                }
    
                return $record_id;
            }
        }
        
        private function _get_all_registered_users(){
            $topic = 'all-registered-users';   //default all topics
            $limit = "";
            
            switch ( $this->class_settings['action_to_perform'] ){
            case 'get_newly_registered_users':
                $topic = 'newly-registered-users';
                $limit = " ORDER BY `creation_date` DESC LIMIT 0, 20 ";
            break;
            }
            
            $cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'directory' => $this->table_name,
				'permanent' => true,
			);
			$cached_values = get_cache_for_special_values( $cache_settings );
			if( $cached_values ){
				return $cached_values;
			}
			
			//Pull up user role record to get functions data
			$query = "SELECT `id`, `serial_num`, `creation_date`, `".$this->table_fields['email']."` as 'email' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields['role']."` != 'admin_seller' ".$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$cached_values = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
                $cached_values = $sql_result;
			}
			
			$cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'cache_values' => $cached_values,
                'directory' => $this->table_name,
				'permanent' => true,
			);
			set_cache_for_special_values( $cache_settings );
			
			return $cached_values;
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