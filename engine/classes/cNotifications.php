<?php
	/**
	 * notifications Class
	 *
	 * @used in  				Notifications Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	notifications
	 */

	/*
	|--------------------------------------------------------------------------
	| Notifications Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cNotifications{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'notifications';
		
		private $table_fields = array(
			'recipients' => 'notifications007',
			'send email' => 'notifications006',
			'detailed message' => 'notifications005',
			
			'type' => 'notifications004',
			'target user' => 'notifications003',
			'trigger function' => 'notifications002',
			
			'generating class' => 'notifications008',
			'generating method' => 'notifications009',
			
			'title' => 'notifications001',
			
			'status' => 'notifications010',
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
        
		function notifications(){
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
				
				//Add ID of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'add_notification':
				$returned_value = $this->_add_notification();
			break;
			case 'get_unread_notification':
				$returned_value = $this->_get_unread_notification();
			break;
			case 'get_unread_notification_count':
				$returned_value = $this->_get_unread_notification_count();
			break;
			case 'get_read_notification':
				$returned_value = $this->_get_read_notification();
			break;
			case 'get_all_notification':
				$returned_value = $this->_get_all_notification();
			break;
			case 'mark_as_read':
				$returned_value = $this->_mark_as_read();
			break;
			case 'delete_notification':
				$returned_value = $this->_delete_notification();
			break;
			case 'site_notifications_manager':
				$returned_value = $this->_site_notifications_manager();
			break;
			}
			
			return $returned_value;
		}
		
		private function _delete_notification(){
			
			$this->class_settings['record_id'] = 0;
			
			if( isset( $_GET[ 'record_id' ] ) ){
				$this->class_settings['record_id'] = doubleval( $_GET['record_id'] );
			}
			
			if( $this->class_settings['record_id'] ){
				
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$_POST['id'] = $this->class_settings['record_id'];
				
				$returning_html_data = $this->_delete_records();
				
				$returning_html_data['deleted_record_id'] = $this->class_settings['record_id'];
				
				return $returning_html_data;
			}else{
				//INVALID RECORD ID
				$err = new cError(000101);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid input data';
				
				return $err->error();
			}
			
		}
		
		private function _get_unread_notification(){
			
			$this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='unread' ORDER BY `creation_date` DESC LIMIT 0, 10 ";
			
			return $this->_get_notification();
		}
        
		private function _get_unread_notification_count(){
			
            $query = "SELECT COUNT(`id`) as 'count' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='unread' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( isset($sql_result[0]['count']) && $sql_result[0]['count'] ){
				return doubleval($sql_result[0]['count']);
			}
			
			return 0;
            
		}
		
		private function _get_read_notification(){
			
			$this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='read' ORDER BY `creation_date` desc LIMIT 0, 10 ";
			
			return $this->_get_notification();
		}
		
		private function _get_all_notification(){
			
			$this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' ORDER BY `creation_date` desc LIMIT 0, 10 ";
			
			return $this->_get_notification();
		}
		
		private function _get_notification(){
			$returned_data = array();
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "title":
				case "detailed message":
				case "generating class":
				case "generating method":
				case "status":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `creation_date`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
				}
			}
			
			return $returned_data;
		}
		
		private function _mark_as_read(){
			
			$this->class_settings['record_id'] = 0;
			
			if( isset( $_GET[ 'record_id' ] ) ){
				$this->class_settings['record_id'] = doubleval( $_GET['record_id'] );
			}
			
			if( $this->class_settings['record_id'] ){
				//ADD NOTIFICATION
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						
						$this->table_fields['status'] => array( 'value' => 'read' ),
						
						'modified_by' => array( 'value' => $this->class_settings['user_id'] ),
						'modification_date' => array( 'value' => date("U") ),
						'ip_address' => array( 'value' => get_ip_address() ),
					) ,
					'where_fields' => 'serial_num',
					'where_values' => $this->class_settings['record_id'],
				);
				
				$save = update( $settings_array );
			
				if( $save ){
					//RETURN SUCCESS IN UPDATE PROCESS
					$err = new cError(010002);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = 'could not update record on line 168';
					
					$returning_html_data = $err->error();
					
					$returning_html_data['saved_record_id'] = $this->class_settings['record_id'];
					$returning_html_data['status'] = 'notification-marked-as-read';
				}else{
					//RETURN ERROR IN RECORD UPDATE PROCESS
					$err = new cError(000006);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = 'could not update record on line 168';
					
					$returning_html_data = $err->error();
				}
			}else{
				//INVALID RECORD ID
				$err = new cError(000101);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid input data';
				
				$returning_html_data = $err->error();
			}
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			
			return $returning_html_data;
		}
		
		private function _add_notification(){
			$this->class_settings['notification_data'][ 'id' ] = get_new_id();
			
			//ADD NOTIFICATION
			$settings_array = array(
				'database_name' => $this->class_settings['database_name'] ,
				'database_connection' => $this->class_settings['database_connection'] ,
				'table_name' => $this->table_name ,
				'field_and_values' => array(
					
					'id' => array( 'value' => $this->class_settings['notification_data'][ 'id' ] ),
					
					$this->table_fields['title'] => array( 'value' => $this->class_settings['notification_data'][ 'title' ] ),
					$this->table_fields['detailed message'] => array( 'value' => $this->class_settings['notification_data'][ 'detailed_message' ] ),
					$this->table_fields['send email'] => array( 'value' => $this->class_settings['notification_data'][ 'send_email' ] ),
					$this->table_fields['type'] => array( 'value' => $this->class_settings['notification_data'][ 'notification_type' ] ),
					$this->table_fields['target user'] => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
					
					$this->table_fields['generating class'] => array( 'value' => $this->class_settings['notification_data'][ 'class_name' ] ),
					$this->table_fields['generating method'] => array( 'value' => $this->class_settings['notification_data'][ 'method_name' ] ),
					
					$this->table_fields['status'] => array( 'value' => 'unread' ),
					//$this->table_fields['recipients'] => array( 'value' => ),
					
					'created_by' => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
					'creation_date' => array( 'value' => date("U") ),
					'modified_by' => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
					'modification_date' => array( 'value' => date("U") ),
					'ip_address' => array( 'value' => get_ip_address() ),
					'record_status' => array( 'value' => 1 ),
				) ,
			);
			
			$save = create( $settings_array );
			
			if( $save ){
				if( $this->class_settings['notification_data'][ 'notification_type' ] == 'pending_task' ){
					//CREATE PENDING TASK
					$tasks = new cTasks();
					$tasks->class_settings = $this->class_settings;
					$tasks->class_settings[ 'action_to_perform' ] = 'add_task';
					$tasks->tasks();
				}
				
				if( $this->class_settings['notification_data'][ 'notification_type' ] == 'completed_task' ){
					//UPDATE PENDING TASK STATUS IF ANY
					$tasks = new cTasks();
					$tasks->class_settings = $this->class_settings;
					$tasks->class_settings[ 'action_to_perform' ] = 'update_task_status';
					$tasks->tasks();
				}
				
				if( $this->class_settings['notification_data'][ 'send_email' ] == 'yes' ){
                    //GET USER DETAILS
                    /*
                    $user_details = get_users_details( array( 'id' => $this->class_settings['notification_data'][ 'target_user_id' ] ) );
                    
                    if( isset( $user_details['email'] ) && $user_details['email'] ){
                        //SEND EMAIL MESSAGE
                        $email = new cEmails();
                        $email->class_settings = $this->class_settings;
                        
                        $email->class_settings[ 'action_to_perform' ] = 'send_mail';
                        
                        $email->class_settings[ 'destination' ]['email'][] = $user_details['email'];
                        $email->class_settings[ 'destination' ]['full_name'][] = $user_details['firstname']." ".$user_details['lastname'];
                        $email->class_settings[ 'destination' ]['id'][] = $this->class_settings['notification_data'][ 'target_user_id' ];
                        
                        $email->emails();
                    }else{
                        //LOG INTERNAL ERROR - FAILED TO RETRIEVE USER DETAILS
                    }
                    */
				}
			}
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
				//REPORT INVALID TABLE ERROR
				$err = new cError(000001);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'corder.php';
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
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			return $returning_html_data;
		}
		
        private function _site_notifications_manager(){
            $additional_html = '';
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js_lib' ][] = 'js/jquery.dataTables.js';
			$this->class_settings[ 'js' ][] = 'my_js/activate-datatable.js';
			
			//$this->class_settings[ 'css' ][] = 'css/template-1/order_manager.css';
			
			$dashboard = new cDashboard();
			$dashboard->class_settings = $this->class_settings;
			$dashboard->class_settings[ 'action_to_perform' ] = 'setup_dashboard';
			$returning = $dashboard->dashboard();
			
			$this->class_settings = $dashboard->class_settings;
			
			$returning[ 'html' ] = '<div id="page-wrapper"><br />';
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
            $this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 1;
			$this->datatable_settings[ 'show_advance_search' ] = 1;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			/*
			$script_compiler->class_settings[ 'data' ] = '';
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-edit-button.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$this->datatable_settings[ 'custom_edit_button' ] = $script_compiler->script_compiler();
			*/
			$script_compiler->class_settings[ 'data' ] = $this->_display_data_table();
			$script_compiler->class_settings[ 'data' ]['title'] = 'Notifications';
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notifications-manager.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] .= $script_compiler->script_compiler();
			
			$returning[ 'html' ] .= '</div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
        }
	
    }
?>