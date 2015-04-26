<?php
	/**
	 * Reports Class
	 *
	 * @used in  				Myexcel, Mypdf Function
	 * @created  				19:03 | 27-06-2014
	 * @database table name   	reports
	 */

	/*
	|--------------------------------------------------------------------------
	| Reports Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cReports{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'reports';
		
		function reports(){
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
			case 'add_generated_report_record':
				$returned_value = $this->_add_generated_report_record();
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
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			$process_handler->class_settings[ 'datatables_settings' ] = array(
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
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'display_data_table';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data[ 'method_executed' ] = $this->class_settings['action_to_perform'];
			$returning_html_data[ 'status' ] = 'display-datatable';
			$returning_html_data[ 'message' ] = 'Returned form data table';
			
			return $returning_html_data;
			
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
		
		private function _add_generated_report_record(){
			$returning_html_data = array();
			
			$save = 0;
			
			if( isset( $this->class_settings['database_name'] ) && isset( $this->class_settings['database_connection'] ) && isset( $this->class_settings['file_properties'] ) && $this->class_settings['database_name'] && $this->class_settings['database_connection']  ){
				
				$file_properties = array( 'file_name', 'file_url', 'file_reference' , 'file_source' , 'file_keywords' , 'file_description' );
				
				foreach( $file_properties as $value ){
					if( ! isset( $this->class_settings[ 'file_properties' ][ $value ] ) ){
						$this->class_settings[ 'file_properties' ][ $value ] = '';
					}
				}
				
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						'id' => array(
							'value' => get_new_id(),
						),
						'reports001' => array(	//file_name
							'value' => clean2( $this->class_settings[ 'file_properties' ][ 'file_name' ] ),
						),
						'reports002' => array(	//file_url
							'value' => $this->class_settings[ 'file_properties' ][ 'file_url' ],
						),
						'reports003' => array(	//file_reference
							'value' => $this->class_settings[ 'file_properties' ][ 'file_reference' ],
						),
						'reports004' => array(	//file_source
							'value' => $this->class_settings[ 'file_properties' ][ 'file_source' ],
						),
						'reports005' => array(	//file_keywords
							'value' => $this->class_settings[ 'file_properties' ][ 'file_keywords' ],
						),
						'reports006' => array(	//file_description
							'value' => $this->class_settings[ 'file_properties' ][ 'file_description' ],
						),
						'record_status' => array(
							'value' => '1',
						),
						'modification_date' => array(
							'value' => date("U"),
						),
						'modified_by' => array(
							'value' => $this->class_settings['user_id'] ,
						),
						'creator_role' => array(
							'value' => $this->class_settings['priv_id'] ,
						),
						'created_by' => array(
							'value' => $this->class_settings['user_id'] ,
						),
						'creation_date' => array(
							'value' => date("U"),
						),
					)
				);
				$save = create( $settings_array );
			}
			
			return $save;
		}
		
	}
?>