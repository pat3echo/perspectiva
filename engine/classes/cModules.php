<?php
	/**
	 * modules Class
	 *
	 * @used in  				modules Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	modules
	 */

	/*
	|--------------------------------------------------------------------------
	| modules Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cModules{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'modules';
		
		function modules(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
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
				
				//Add id of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'dual-edit':
				$returned_value = $this->_dual_edit();
			break;
			case 'display':
				$returned_value = $this->_display();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
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
				//REPORT INVALid TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cmodules.php';
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
			
			return $returning_html_data;
		}
		
		private function _display(){
			$returning_html_data = '';
			$hr = array();
			$modules_used_in_entity_right_click_menu = array();
			
			$query1 = '';
			//Pull up user role record to get functions data
			$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`access_roles` WHERE `id`='" . $this->class_settings['priv_id'] . "' AND `record_status`='1'";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array('access_roles'),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$a = $sql_result[0];
				
				//Check if functions data = access all
				if($a['access_roles002']=='universal' ){
					//If access all = retrieve all modules 
					$query1 = "SELECT `" . $this->table_name . "`.`modules001`, `" . $this->table_name . "`.`id` as 'id_', `functions`.* FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "`, `" . $this->class_settings['database_name'] . "`.`functions` WHERE `" . $this->table_name . "`.`record_status`='1'  AND `functions`.`record_status`='1' AND `" . $this->table_name . "`.`id`=`functions`.`functions002`";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query1,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array($this->table_name, 'functions'),
					);
					
					
					$sql_result1 = execute_sql_query($query_settings);
					
				}else{
					//IF not explode functions data
					$funs = explode(':::',$a['access_roles002']);
					
					if(is_array($funs)){
						foreach($funs as $fun){
							if($fun){
								//Prepare query
								if($query1)$query1 .= " OR `functions`.`id`='".$fun."'";
								else $query1 = "SELECT `" . $this->table_name . "`.`modules001`, `" . $this->table_name . "`.`id` as 'id_', `functions`.* FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "`, `" . $this->class_settings['database_name'] . "`.`functions` WHERE `" . $this->table_name . "`.`record_status`='1' AND `functions`.`record_status`='1' AND `" . $this->table_name . "`.`id`=`functions`.`functions002` AND ( `functions`.`id`='".$fun."' ";
								
							}
						}
						//Retrieve only accessible modules
						$query1 .= ")";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							
							'query' => $query1,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
							'tables' => array($this->table_name, 'functions'),
						);
						
						$sql_result1 = execute_sql_query($query_settings);
					}
				}
				
				//Retrieve Members
				$nn = 0;
				if(isset($sql_result1) && is_array($sql_result1)){
					$nn = count($sql_result1);
					
					if($nn){
						
						
						foreach($sql_result1 as $a1){
							
							//Exclude save functions
							switch($a1['functions003']){
							case 'reply_ticket':
							case 'add_product_variations_button':
							case 'manage_product_variations_button':
							
							case 'restore':
							case 'fetch_all_records':
							case 'fetch_my_records':
							case 'push_all_records':
							case 'push_my_records':
							case 'new':
							case 'create_new_record':
							case 'edit':
							case 'delete':
							case 'save':
							case 'verify':
							case 'timeline':
							case 'location_differential':
							
							case 'edit_password':
							
							case 'attach_files_to_gas_sales_agreements':
							
							case 'generate_report':
							case 'save_generated_report':
							
							case 'approve':
							case 'check':
							case 'status_update':
							case 'record_assign':
							case 'add_new_cash_call':
							case 'add_new_line_item':
							case 'add_new_child_work_program':
							case 'import_excel_table':
							
							case 'save_rename':
							case 'save_entity_edit':
							case 'new_memo_report_letter':
							case 'new_scanned_file':
							case 'save_new_memo_report_letter':
							case 'save_send_a_copy':
							case 'save_move_to_label':
							case 'save_edit_entity_properties':
							
							case 'delete_forever':
							case 'restore_all':
							case 'restore_selected':
							
							case 'print_authenticated_report':
							case 'select_audit_trail':
							case 'navigation_pane':
							
							break;
							
							case 'status_update_monthly_returns_recommended':
							case 'status_update_monthly_returns_operator_returns':
							case 'status_update_monthly_returns_payment_by_fad':
							
							case 'status_update_projects_weekly_status':
							case 'status_update_projects_monthly_status':
							
							case 'status_update_monthly_cash_call_submitted':
							case 'status_update_monthly_cash_call_approved':
							case 'status_update_monthly_cash_call_payment_by_fad':
							case 'status_update_monthly_cash_call_crosschecked':
							case 'status_update_monthly_cash_call_reviewed':
							case 'status_update_monthly_cash_call_operator_proposal':
							
							case 'status_update_annual_budget':
							case 'status_update_budget_realignment':
							case 'status_update_budget_performance_return':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key][$a1['functions004']][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
								);
							break;
							
							case 'status_update_annual_budget_opcom_approval':
							case 'status_update_annual_budget_operator_proposal':
							case 'status_update_annual_budget_subcom_review':
							case 'status_update_annual_budget_tecom_recommendation':
							
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_annual_budget'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							case 'status_update_budget_realignment_opcom_approval':
							case 'status_update_budget_realignment_operator_proposal':
							case 'status_update_budget_realignment_subcom_review':
							case 'status_update_budget_realignment_tecom_recommendation':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_budget_realignment'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							case 'status_update_budget_performance_return_opcom_approval':
							case 'status_update_budget_performance_return_operator_proposal':
							case 'status_update_budget_performance_return_subcom_review':
							case 'status_update_budget_performance_return_tecom_recommendation':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_budget_performance_return'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							
							case 'mark_as_public':
							case 'move_to_label':
							case 'rename_entity':
							case 'view_entity':
							case 'edit_entity':
							case 'edit_entity_properties':
							case 'delete_entity':
							case 'send_a_copy_to':
								//Return Functions for Entity[Labels | Documents] Right-click
								$modules_used_in_entity_right_click_menu[$a1['id_']][$a1['modules001']][] = array(
									'todo' => $a1['functions003'],
									'phpclass' => $a1['functions004'],
									'name' => $a1['functions001'],
									'id' => $a1['id'],
								);
							break;
							default:
								
								
								$hr[$a1['id_']][$a1['modules001']][] = array(
									'todo' => $a1['functions003'],
									'phpclass' => $a1['functions004'],
									'name' => $a1['functions001'],
									'id' => $a1['id'],
								);
							break;
							}
						}
					}
				}
			}
			
			//Update Currently Logged In User Name
			$hr['user_details']['full_name'] = 'N/A';
			
			//Get user certificate session variable
			$current_user_session_details = array(
				'fname'=>'',
				'lname'=>'',
				'login_time'=>'',
				'role'=>'',
				'remote_user_id' => '',
				'email' => '',
			);
			
			$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
			if(isset($_SESSION[$current_user_details_session_key]))$current_user_session_details = $_SESSION[$current_user_details_session_key];
			
			if($current_user_session_details['fname'] && $current_user_session_details['lname']){
				$hr['user_details']['full_name'] = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
			}
			
			$project_title = '';
			
			$settings = array(
				'cache_key' => 'appsettings_properties',
			);
			$cached_values = get_cache_for_special_values( $settings );
			if( $cached_values ){
				$project_title = $cached_values[ 'appsettings_name' ];
			}
			
			//4. CHECK FOR APPLICATION VERSION
			$application_version = '';
			if( is_dir( $this->class_settings[ 'calling_page' ] ) ){
				//3. Open & Read all files in directory
				$cdir = opendir( $this->class_settings[ 'calling_page' ] );
				while($cfile = readdir($cdir)){
					if(!($cfile=='.' || $cfile=='..')){
						if ( preg_match("/BUILD/", $cfile ) ) {
							
							$application_version = trim( str_replace( "BUILD", "", $cfile ) );
							$application_version = str_replace( ".php", "", $application_version );
							$application_version = str_replace( "-", ".", $application_version );
							
						}
					}
				}
				closedir($cdir);
			}
			
			return array(
				'modules' => $hr,
				'entity_modules' => $modules_used_in_entity_right_click_menu,
				'login_time' => date("d-M-Y H:i",($current_user_session_details['login_time']/1)),
				'role' => $current_user_session_details['role'],
				'fullname' => $current_user_session_details['fname'].' '.$current_user_session_details['lname'],
				'remote_user_id' => $current_user_session_details['remote_user_id'],
				'email' => $current_user_session_details['email'],
				'project_title' => $project_title,
				'project_version' => $application_version,
				/*
				'cash_calls' => $this->_generate_cash_calls_breakdown(),
				'cash_calls_summary' => $this->_generate_cash_calls_summary_breakdown(),
				'budget_details' => $this->_generate_budgets_breakdown(),
				
				'pipeline_vandalism' => $this->_generate_pipeline_vandalism_breakdown(),
				*/
			);
		}
		
		private function _generate_cash_calls_breakdown(){
			//get operators
			$operators = get_jv_operators();
			
			$months = get_months_of_year();
			
			//get all budgets with cash calls
			$budget_table = 'budgets';
			$cash_calls_table = 'cash_calls';
			
			$query = "SELECT *, TRIM(`".$budget_table."`.`id`) FROM `" . $this->class_settings['database_name'] . "`.`".$budget_table."`, `" . $this->class_settings['database_name'] . "`.`".$cash_calls_table."` WHERE `".$cash_calls_table."`.`".$cash_calls_table."001` = `".$budget_table."`.`id` AND `".$budget_table."`.`record_status`='1' AND `".$cash_calls_table."`.`record_status`='1' GROUP BY `".$budget_table."`.`id`";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $budget_table, $cash_calls_table),
			);
			$sql_result = execute_sql_query($query_settings);
			
			//print_r($sql_result);
			
			$html = '';
			
			if(isset($sql_result) && is_array($sql_result) && ! empty( $sql_result ) ){
				
				$html .= '<ul class="sub-menu">';
				
				foreach( $sql_result as $s_val ){
					//get budget years
					if( isset( $operators[ $s_val[ "budgets001" ] ] ) ){
						//iterate for each month
						
						$title = strtoupper( $s_val[ 'budgets001' ] ) . '-' . $s_val[ 'budgets002' ] . '-' . get_select_option_value( array( 'id' => $s_val[ 'budgets003' ], 'function_name' => 'get_cash_call_types' ) ). '-' . $s_val[ 'budgets004' ];
						
						$html .= '<li class="nav-header"><a href="" class="drop-down" id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" function-id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" function-class="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" function-name="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" module-id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" module-name="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" title="'.$title.'"><i class="icon-angle-right"></i>'.$title.'</a></li>';
							
						//get all available months
						$html .= '<ul class="sub-menu">';
						foreach( $months as $k => $month ){
							$html .= '<li data-mini="true"><a href="" id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" function-id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" function-class="'.$cash_calls_table.'" function-name="get_segmented_data" module-id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" module-name="'.$title.'" title="'.$month.'" budget-id="'.$s_val[ "TRIM(`".$budget_table."`.`id`)" ].'" month-id="'.$k.'"><i class="icon-angle-right"></i>'.$month.'</a></li>';
						}
						$html .= '</ul>';
					}
				}
				$html .= '</ul>';
				
			}
			
			return $html;
		}
	
		private function _generate_cash_calls_summary_breakdown(){
			//get operators
			$months = get_months_of_year();
			
			//get all budgets with cash calls
			$budget_table = 'budgets';
			$cash_calls_table = 'cash_calls';
			
			$function_cash_calls_table = 'operators_cash_calls_summary';
			
			$query = "SELECT *, TRIM(`".$budget_table."`.`id`) FROM `" . $this->class_settings['database_name'] . "`.`".$budget_table."`, `" . $this->class_settings['database_name'] . "`.`".$cash_calls_table."` WHERE `".$cash_calls_table."`.`".$cash_calls_table."001` = `".$budget_table."`.`id` AND `".$budget_table."`.`record_status`='1' AND `".$cash_calls_table."`.`record_status`='1' GROUP BY `".$budget_table."`.`".$budget_table."002` ORDER BY `".$budget_table."`.`".$budget_table."002` desc";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $budget_table, $cash_calls_table),
			);
			$sql_result = execute_sql_query($query_settings);
			
			//print_r($sql_result);
			
			$html = '';
			
			if(isset($sql_result) && is_array($sql_result) && ! empty( $sql_result ) ){
				
				$html .= '<ul class="sub-menu">';
				
				foreach( $sql_result as $s_val ){
					//get budget years
					if( isset( $s_val[ "budgets002" ] ) && $s_val[ "budgets002" ] ){
						//iterate for each month
						
						$title = $s_val[ "budgets002" ];
						
						$html .= '<li class="nav-header"><a href="" class="drop-down" id="'.$s_val[ "budgets002" ].'" function-id="'.$s_val[ "budgets002" ].'" function-class="'.$s_val[ "budgets002" ].'" function-name="'.$s_val[ "budgets002" ].'" module-id="'.$s_val[ "budgets002" ].'" module-name="'.$s_val[ "budgets002" ].' Cash Calls Summary" title="'.$title.' Cash Calls Summary"><i class="icon-angle-right"></i>'.$title.'</a></li>';
							
						//get all available months
						$html .= '<ul class="sub-menu">';
							//bar chart
							$html .= '<li data-mini="true"><a href="" id="cash-calls-bar-chart" function-id="cash-calls-bar-chart" function-class="'.$function_cash_calls_table.'" function-name="bar_chart" module-id="cash-calls-bar-chart" module-name="'.$title.' Cash Calls Bar Chart" title="Cash Calls Bar Chart" budget-id="'.$s_val[ "budgets002" ].'" month-id="all"><i class="icon-angle-right"></i>Cash Calls Bar Chart</a></li>';
							
						foreach( $months as $k => $month ){
							$html .= '<li data-mini="true"><a href="" id="'.$s_val[ "budgets002" ].'" function-id="'.$s_val[ "budgets002" ].'" function-class="'.$function_cash_calls_table.'" function-name="get_segmented_data" module-id="'.$s_val[ "budgets002" ].'" module-name="'.$title.' Cash Calls Summary" title="'.$month.' Cash Calls Summary" budget-id="'.$s_val[ "budgets002" ].'" month-id="'.$k.'"><i class="icon-angle-right"></i>'.$month.'</a></li>';
						}
						$html .= '</ul>';
					}
				}
				$html .= '</ul>';
				
			}
			
			return $html;
		}
	
		private function _generate_budgets_breakdown(){
			//get operators
			$operators = get_jv_operators();
			
			$months = get_months_of_year();
			
			//get all budgets with cash calls
			$budget_table = 'budgets';
			$cash_calls_table = 'budget_details';
			
			$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`".$budget_table."` WHERE `".$budget_table."`.`record_status`='1'";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $budget_table ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			//print_r($sql_result);
			
			$html = '';
			
			if(isset($sql_result) && is_array($sql_result) && ! empty( $sql_result ) ){
				
				$html .= '<ul class="sub-menu">';
				
				foreach( $sql_result as $s_val ){
					//get budget years
					if( isset( $operators[ $s_val[ "budgets001" ] ] ) ){
						//iterate for each month
						
						$title = strtoupper( $s_val[ 'budgets001' ] ) . '-' . $s_val[ 'budgets002' ] . '-' . get_select_option_value( array( 'id' => $s_val[ 'budgets003' ], 'function_name' => 'get_cash_call_types' ) ). '-' . $s_val[ 'budgets004' ];
						
						$html .= '<li><a href="" id="'.$s_val[ "id" ].'" function-id="'.$s_val[ "id" ].'" function-class="'.$cash_calls_table.'" function-name="get_segmented_data" module-id="'.$s_val[ "id" ].'" module-name="Budget Details" title="'.$title.'" budget-id="'.$s_val[ "id" ].'" month-id="1"><i class="icon-angle-right"></i>'.$title.'</a></li>';
						
					}
				}
				$html .= '</ul>';
				
			}
			
			return $html;
		}
		
		private function _generate_pipeline_vandalism_breakdown(){
			//get operators
			$months = get_months_of_year();
			
			//get all budgets with cash calls
			$table = 'pipeline_vandalism';
			
			$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`".$table."` WHERE `".$table."`.`record_status`='1' GROUP BY `".$table."`.`".$table."007` ORDER BY `".$table."`.`".$table."007` desc";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $table ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			//print_r($sql_result);
			
			$html = '';
			
			if(isset($sql_result) && is_array($sql_result) && ! empty( $sql_result ) ){
				
				$html .= '<ul class="sub-menu">';
				
				foreach( $sql_result as $s_val ){
					//get budget years
					if( isset( $s_val[ $table."007" ] ) && $s_val[ $table."007" ] ){
						//iterate for each month
						
						$title = $s_val[ $table."007" ];
						
						$html .= '<li class="nav-header"><a href="" class="drop-down" id="'.$s_val[ $table."007" ].'" function-id="'.$s_val[ $table."007" ].'" function-class="'.$s_val[ $table."007" ].'" function-name="'.$s_val[ $table."007" ].'" module-id="'.$s_val[ $table."007" ].'" module-name="'.$s_val[ $table."007" ].' " title="'.$title.' "><i class="icon-angle-right"></i>'.$title.'</a></li>';
							
						//get all available months
						$html .= '<ul class="sub-menu">';
							//bar chart
							$html .= '<li data-mini="true"><a href="" id="cash-calls-bar-chart" function-id="cash-calls-bar-chart" function-class="'.$table.'" function-name="bar_chart" module-id="cash-calls-bar-chart" module-name="'.$title.' Cash Calls Bar Chart" title="Cash Calls Bar Chart" budget-id="'.$s_val[ $table."002" ].'" month-id="all"><i class="icon-angle-right"></i>Cash Calls Bar Chart</a></li>';
							
						foreach( $months as $k => $month ){
							$html .= '<li data-mini="true"><a href="" id="'.$s_val[ $table."002" ].'" function-id="'.$s_val[ $table."002" ].'" function-class="'.$table.'" function-name="get_segmented_data" module-id="'.$s_val[ $table."002" ].'" module-name="'.$title.' Cash Calls Summary" title="'.$month.' Cash Calls Summary" budget-id="'.$s_val[ $table."002" ].'" month-id="'.$k.'"><i class="icon-angle-right"></i>'.$month.'</a></li>';
						}
						$html .= '</ul>';
					}
				}
				$html .= '</ul>';
				
			}
			
			return $html;
		}
		
	}
?>