<?php
	/**
	 * website_page_content Class
	 *
	 * @used in  				website_page_content Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	website_page_content
	 */

	/*
	|--------------------------------------------------------------------------
	| website_page_content Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cWebsite_page_content{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'website_page_content';
		
		private $associated_cache_keys = array(
			'website_page_content',
		);
		
		private $table_fields = array(
			'page' => 'website_page_content001',
			'content_title' => 'website_page_content002',
			'content' => 'website_page_content003',
			'serial_number' => 'website_page_content004',
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
	
		function website_page_content(){
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
			case 'get_website_page_content':
				$returned_value = $this->_get_website_page_content();
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
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->class_settings['current_record_id'] = $_POST['id'];
				$this->_get_website_page_content();
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
				
				$err->class_that_triggered_error = 'cwebsite_page_content.php';
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
					$this->_get_website_page_content();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_page_id(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['current_record_id'] ) && $this->class_settings['current_record_id'] ){
				
				if( ! isset( $this->class_settings['where'] ) ){
					$this->class_settings['where'] = " WHERE `id`='".$this->class_settings['current_record_id']."' AND `record_status`='1'";
				}
				
				$select = " `".$this->table_fields['page']."` as 'page_id' ";
				
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
				
				if(isset($sql_result) && is_array($sql_result) && isset( $sql_result[0]['page_id'] ) ){
					return $sql_result[0]['page_id'];
				}
			}
			
		}
		
		private function _get_website_page_content(){
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['current_record_id'] ) && $this->class_settings['current_record_id'] ){
				$page_id = $this->_get_page_id();
				
				$settings = array(
					'cache_key' => $cache_key.'-'.$page_id,
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
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['page']."`='".$page_id."' order by `".$this->table_fields['serial_number']."` asc ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_website_page_content = execute_sql_query($query_settings);
				
				$website_page_content = array();
				
				if( is_array( $all_website_page_content ) && ! empty( $all_website_page_content ) ){
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$page_id,
						'directory_name' => $cache_key,
						'cache_values' => $all_website_page_content,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					return $website_page_content;
				}
			}
		}
		
	}
?>