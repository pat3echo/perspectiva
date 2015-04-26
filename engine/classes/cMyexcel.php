<?php
	/**
	 * My Excel Class
	 *
	 * @used in  				Generating Excel Spreadsheet
	 * @created  				18:14 | 31-10-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| My Excel Class
	|--------------------------------------------------------------------------
	|
	| Generate excel reports from data in the Gas Helix datatables
	|
	*/
	
	class cMyexcel{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'myexcel';
		
		//Directory for Entities
		private $entity_directory = 'files';
		
		function myexcel(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create':
				$returned_value = $this->_create();
			break;
			case 'generate_excel':
				$returned_value = $this->_generate_excel();
			break;
			case 'save_imported_excel_data':
				$returned_value = $this->_save_imported_excel_data();
			break;
			case 'import_excel_table':
				$returned_value = $this->_display_import_data_capture_form();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_excel(){
			$returning_html_data = 'Generating Excel';
			$html_only = '';
			
			$authenticate_report = 0;
			$authenticated_report_id = 0;
			
			//CHECK IF HTML DATA HAS BEEN RECEIVED
			if(isset($_POST['html']) && $_POST['html']){
				
				//Set Records Id
				$new_record_id_base = get_new_id();
				
				//Set Current Timestamp
				$current_date_time = date("U");
				
				//DATE REPORT WAS GENERATED
				$date_of_generation = date("jS F Y H:i");
				
				//Get Users Full Name
				$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
				if(isset($_SESSION[$current_user_details_session_key])){
					$current_user_session_details = $_SESSION[$current_user_details_session_key];
				}
				
				$creator = '';
				$created_by = '';
				if(isset($current_user_session_details['fname']) && isset($current_user_session_details['lname'])){
					$creator = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
					
					$created_by = '<br />Created By: ' . $creator;
				}
				
				//Set html Data
				$this->html .= $_POST['html'];
				
				$dir = create_report_directory( $this->class_settings );
				
				//Set Output File Name
				$this->filename = $dir.'/'.$new_record_id_base.'.xls';
				
				//Generate Report
				$this->_create();
				
				//Check if report was created
				if( file_exists( $this->filename ) ){
					//ADD REPORT TO DATABASE
					$reports_handler = new cReports();
					$reports_handler->class_settings = $this->class_settings;
					$reports_handler->class_settings[ 'action_to_perform' ] = 'add_generated_report_record';
					
					$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/'.$new_record_id_base . '.xls';
					
					$report_title = 'undefined';
					if( isset( $_POST[ 'report_title' ] ) && $_POST[ 'report_title' ] ){
						$report_title = strip_tags( $_POST[ 'report_title' ] );
					}
					
					$report_reference = 'undefined';
					if( isset( $_POST[ 'current_module' ] ) && $_POST[ 'current_module' ] ){
						$report_reference = $_POST[ 'current_module' ];	//table name
					}
					
					$reports_handler->class_settings[ 'file_properties' ] = array( 
						'file_name' => $report_title,
						'file_url' => $file_url,
						'file_reference' => $report_reference,
						'file_source' => $report_reference,
						'file_keywords' => $report_title,
						'file_description' => 'system generated report',
					);
					
					if( $reports_handler->reports() ){
						//successful notification
						$err = new cError(010009);
					}else{
						//record was not created
						$err = new cError(010010);
					}
					
					//SUCCESSFUL REPORT CREATION
					$returning_html_data = 'File Created On: '.$date_of_generation . $created_by . '<br /><a class="btn btn-primary" href="' . $file_url . '" target="_blank" ><i class="icon-download-alt icon-white">&nbsp;&nbsp;</i> Download Excel File</a>';
					
					$err->action_to_perform = 'notify';
						
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_generate_excel';
					$err->additional_details_of_error = '';
						
					$return = $err->error();
					
					$return[ 'msg' ] = $returning_html_data;
					
					return $return;
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError(000008);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cmyexcel.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
				}
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError(000009);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cmyexcel.php';
				$err->method_in_class_that_triggered_error = '_generate_pdf';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _display_import_data_capture_form(){
			
			$import_table = '';
			unset( $_SESSION['temp_storage'][ 'excel_import_table' ] );
			if( isset($_GET[ 'search_table' ]) && $_GET[ 'search_table' ] ){
				$import_table = $_GET[ 'search_table' ];
				
				$_SESSION['temp_storage'][ 'excel_import_table' ] = $import_table;
			}
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'database_table_temp' ] = '_' . $import_table;
			
			$process_handler->class_settings[ 'form_heading_title' ] = 'Excel File Import';
			
			$process_handler->class_settings[ 'form_action_todo' ] = 'save_imported_excel_data';
			$process_handler->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo='.$process_handler->class_settings[ 'form_action_todo' ];
			
			$process_handler->class_settings['form_submit_button'] = 'Import Excel File';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			$process_handler->class_settings[ 'form_class' ] = 'form-horizontal';
			
			$process_handler->class_settings[ 'hidden_records_css' ] = array(
				'myexcel006' => $import_table,
			);
			
			$process_handler->class_settings[ 'form_values' ] = array(
				'myexcel006' => $import_table,
			);
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
			);
		}
		
		private function _save_imported_excel_data(){
			$returning_html_data = '';
			
			$import_table = '';
			
			if(isset($_POST['table']) && $_POST['table'] == $this->table_name ){
				
				//GET ALL FIELDS IN TABLE
				$fields = array();
				$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect'=>$this->class_settings['database_connection'],
					'query'=>$query,
					'query_type'=>'DESCRIBE',
					'set_memcache'=>1,
					'tables'=>array($this->table_name),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if($sql_result && is_array($sql_result)){
					foreach($sql_result as $sval)
						$fields[] = $sval[0];
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError(000001);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_save';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 293';
					return $err->error();
				}
				
				/**************************************************************************/
				/**************************SELECT FORM GENERATOR***************************/
				/**************************************************************************/
				$form = new cForms();
				$form->setDatabase($this->class_settings['database_connection'],$this->table_name);
				$form->setFormActionMethod('','post');
				$form->uid = $this->class_settings['user_id']; //Currently logged in user id
				$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
				$form->maxstep = 1;
				$form->step = 1;
				
				if( isset( $_POST[ 'myexcel006' ] ) && $_POST[ 'myexcel006' ] )
					$form->table_field_temp = '_' . $_POST[ 'myexcel006' ];
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				//3. Update the current step
				$form->step = $form->nextstep;
				
				//4. Pick current record id
				$this->record_id = $form->record_id;
				
				if( isset( $field_values_pair['form_data']['myexcel005'] ) && is_array( $field_values_pair['form_data']['myexcel005'] ) ){
					
					$file = explode( ':::', $field_values_pair['form_data']['myexcel005']['value'] );
					
					$name_of_file_to_be_imported = $this->class_settings['calling_page'] . $file[0];
					
				}
				
				if( isset( $field_values_pair['form_data']['myexcel006'] ) && $field_values_pair['form_data']['myexcel006'] ){
					
					$import_table = $field_values_pair['form_data']['myexcel006'][ 'value' ];
					
				}
				
				$mapping_options = '';
				if( isset( $field_values_pair['form_data']['myexcel007'] ) && $field_values_pair['form_data']['myexcel007'] ){
					
					$mapping_options = $field_values_pair['form_data']['myexcel007'][ 'value' ];
					
				}
				
				$update_existing_records_based_on_fields = '';
				
				if( isset( $field_values_pair['form_data']['myexcel008'][ 'value' ] ) && $field_values_pair['form_data']['myexcel008'][ 'value' ] == '200' && isset( $field_values_pair['form_data']['myexcel009'][ 'value' ] ) && $field_values_pair['form_data']['myexcel009'][ 'value' ] ){
					
					$update_existing_records_based_on_fields = $field_values_pair['form_data']['myexcel009'][ 'value' ];
					
				}
				
				if( isset( $name_of_file_to_be_imported ) && file_exists( $name_of_file_to_be_imported ) ){
					//Get Excel File And Create Insert Queries
					
					/** PHPExcel_IOFactory */
					require_once $this->class_settings['calling_page'].'classes/PHPExcel/IOFactory.php';
					
					$imported_records_details = array();
					
					$file_information = array();
					
					$file_information = pathinfo($name_of_file_to_be_imported);
					
					if ( isset( $file_information['extension'] ) && $file_information['extension'] == 'xls'  ) {
						/** Load File to be imported **/
						
						//Set function name that would be used to retrieve mapped fields for import
						$mapped_fields_function_name = $import_table;
						
						//Initialize mapped fields array
						$mapped_fields = array();
						
						if( function_exists( $mapped_fields_function_name ) ){
							$mapped_fields = $mapped_fields_function_name();
							
							//Add Field Id's Property
							$field_counter = 0;
							foreach( $mapped_fields as $key => $value ){
								$mapped_fields[$key][ 'field_id' ] = $key;
								
								//USE SERIAL IMPORT OPTION
								if( $mapping_options == '100' )
									$mapped_fields[ $field_counter ] = $mapped_fields[$key];
								
								++$field_counter;
							}
						}
						
						if(! empty($mapped_fields) ){
							$objPHPExcel = PHPExcel_IOFactory::load($name_of_file_to_be_imported);
							
							$array_of_dataset = array();
							$array_of_update_conditions = array();
							
							$new_record_id = get_new_id();
							$new_record_id_serial = 0;
							
							$ip_address = get_ip_address();
							$date = date("U");
							
							//$mapping_options == 200
							$row_counter = 0;
							
							$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
							foreach($rowIterator as $row){
								
								$dataset_to_be_inserted = array();
								$update_conditions_to_be_inserted = array();
								
								$cellIterator = $row->getCellIterator();
								
								$cell_counter = 0;
								
								$insert_recordset = false;
								
								foreach( $cellIterator as $cell ){
									$excel_column_name = $cell->getColumn();
									
									if( ( $mapping_options == '200' || $mapping_options == '400' ) && $row_counter == 0 ){
										
										$cell_value = strtolower( trim( $cell->getCalculatedValue() ) );
										
										if( $mapping_options == '400' && $cell_counter == 13 || $cell_counter == 14 ){
											switch( $cell_counter ){
											case 13:
												$cell_value = "current month request n'000";
											break;
											case 14:
												$cell_value = "current month request $'000";
											break;
											}
										}
										
										//Map Headings
										foreach( $mapped_fields as $key => $value ){
											
											if( strtolower( $mapped_fields[ $key ][ 'field_label' ] ) == $cell_value ){
												$mapped_fields[ $cell_counter ] = $mapped_fields[$key];
												break;
											}
										}
										
									}else{
										
										if( isset( $mapped_fields[ $cell_counter ] ) ){
											
											$form_field_data = $mapped_fields[ $cell_counter ];
											
											//Special Processing for Individual Tables
											switch( $form_field_data[ 'form_field' ] ){
											case 'select':
												$select_value_key = '';
												
												$select_value = ucwords( strtolower( trim( $cell->getCalculatedValue() ) ) );
												
												if( isset( $form_field_data[ 'form_field_options' ] ) ){
												
													$select_value_array_function = $form_field_data[ 'form_field_options' ];
													
													if( function_exists( $select_value_array_function ) ){
														
														$select_value_array = $select_value_array_function();
														
														if( is_array( $select_value_array ) ){
														
															if( in_array( $select_value , $select_value_array ) ){
																foreach( $select_value_array as $key => $value ){
																	if( strtolower( $value ) == strtolower ( $select_value ) ){
																		$select_value_key = $key;
																		break;
																	}
																}
															}
															
														}
													}
													
												}
												
												if( $select_value_key ){
													$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $select_value_key;
												}else{
													$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $cell->getCalculatedValue();
												}
												
											break;
											default:
												//$function_settings[$field_name] = strtoupper(htmlspecialchars($cell->getCalculatedValue()));
												$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $cell->getCalculatedValue();
											break;
											}
											
											//CHECK IF UPDATE RECORDS MODE IS ACTIVE
											if( $update_existing_records_based_on_fields && $update_existing_records_based_on_fields == $form_field_data[ 'field_id' ] ){
												$update_conditions_to_be_inserted[ 'where_fields' ] = $update_existing_records_based_on_fields;
												$update_conditions_to_be_inserted[ 'where_values' ] = $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ];
											}
											
											if( !( $insert_recordset ) && $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] ){
												$insert_recordset = true;
											}
											
										}
									}	
									
									++$cell_counter;
								}
								
								
								switch( $import_table ){
								case 'cash_calls':
									if( $mapping_options == '400' ){
										if( ! ( isset( $dataset_to_be_inserted['cash_calls003'] ) && isset( $dataset_to_be_inserted['cash_calls002'] ) ) ){
											$insert_recordset = false;
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls003'] ) && isset( $dataset_to_be_inserted['cash_calls002'] ) && ! ( $dataset_to_be_inserted['cash_calls002'] && $dataset_to_be_inserted['cash_calls003'] ) ){
											$insert_recordset = false;
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls002'] ) && preg_match( "/total/" , strtolower($dataset_to_be_inserted['cash_calls002']) ) ){
											$insert_recordset = false;
											//$dataset_to_be_inserted['cash_calls003'] = 'remove me';
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls003'] ) && preg_match( "/total/" , strtolower($dataset_to_be_inserted['cash_calls003']) ) ){
											$insert_recordset = false;
											//$dataset_to_be_inserted['cash_calls003'] = 'remove me';
										}
									}
								break;
								}
								
								if( $insert_recordset ){
									$dataset_to_be_inserted['id'] = $new_record_id . ++$new_record_id_serial;
									
									$dataset_to_be_inserted['created_role'] = $this->class_settings[ 'priv_id' ];
									$dataset_to_be_inserted['created_by'] = $this->class_settings[ 'user_id' ];
									$dataset_to_be_inserted['creation_date'] = $date;
									
									$dataset_to_be_inserted['modified_by'] = $this->class_settings[ 'user_id' ];
									$dataset_to_be_inserted['modification_date'] = $date;
									
									$dataset_to_be_inserted['ip_address'] = $ip_address;
									$dataset_to_be_inserted['record_status'] = 1;
									
									switch( $import_table ){
									case 'budget_details':
										//budget id
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['budget_details001'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//set approved = proposed by default
										$dataset_to_be_inserted['budget_details006'] = $dataset_to_be_inserted['budget_details004'];
										$dataset_to_be_inserted['budget_details007'] = $dataset_to_be_inserted['budget_details005'];
									break;
									case 'pipeline_vandalism':
										//year
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['pipeline_vandalism007'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//month
										if( isset( $field_values_pair['form_data']['myexcel002'][ 'value' ] ) && $field_values_pair['form_data']['myexcel002'][ 'value' ] ){
											$dataset_to_be_inserted['pipeline_vandalism006'] = $field_values_pair['form_data']['myexcel002'][ 'value' ];
										}
									break;
									case 'cash_calls':
										//budget id
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['cash_calls001'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//current month
										if( isset( $field_values_pair['form_data']['myexcel002'][ 'value' ] ) && $field_values_pair['form_data']['myexcel002'][ 'value' ] ){
											$dataset_to_be_inserted['cash_calls025'] = $field_values_pair['form_data']['myexcel002'][ 'value' ];
										}
										
										//set approved = proposed by default
										if( isset( $dataset_to_be_inserted['cash_calls012'] ) )
											$dataset_to_be_inserted['cash_calls016'] = $dataset_to_be_inserted['cash_calls012'];
										
										if( isset( $dataset_to_be_inserted['cash_calls012'] ) )
											$dataset_to_be_inserted['cash_calls017'] = $dataset_to_be_inserted['cash_calls013'];
										
										//clear out all other data
										for( $i = 4; $i < 24; $i++ ){
											switch($i){
											case 4:	case 5: case 6: case 7: case 8: case 9:
												$dataset_to_be_inserted['cash_calls00'.$i] = '';
											break;
											case 12: case 13: case 16: case 17: case 24:
											break;
											default:
												$dataset_to_be_inserted['cash_calls0'.$i] = '';
											break;
											}
										}
									break;
									}
									
									$array_of_dataset[] = $dataset_to_be_inserted;
									
									$array_of_update_conditions[] = $update_conditions_to_be_inserted;
								}
								
								
								++$row_counter;
							}
							
							
							//Run Insert of Multiple Records in One Query
							$save = 0;
							if( !empty( $array_of_dataset ) ){
								
								$function_settings = array(
									'database' => $this->class_settings['database_name'],
									'connect' => $this->class_settings['database_connection'],
									'table' => $import_table,
									
									'dataset' => $array_of_dataset,
								);
								
								if( $update_existing_records_based_on_fields && ! empty( $array_of_update_conditions ) ){
									$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
								}
								
								$returned_data = insert_new_record_into_table( $function_settings );
								
							}
							
							//DELETE FILE
							unlink($name_of_file_to_be_imported);
							
							//RETURN SUCCESS NOTIFICATION
							$err = new cError(010005);
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cMyexcel.php';
							$err->method_in_class_that_triggered_error = '_save_imported_excel_data';
							$err->additional_details_of_error = 'records successful imported to database';
							
							$returning_html_data = $err->error();
							
							$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
							$returning_html_data['status'] = 'saved-form-data';
			
							return $returning_html_data;
							
							/*
							//RETURN ERROR IN RECORD CREATION PROCESS
							$err = new cError(000007);
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cAnnual_w_p.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not create record on line 473';
							
							return $err->error();
							*/
						}	
					}
					
					//RETURN INVALID FILE TO UPLOAD ERROR
					$err = new cError(000010);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_save';
					$err->additional_details_of_error = 'could not create record on line 473';
					
					$returning_html_data = $err->error();
							
					$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
					$returning_html_data['status'] = 'saved-form-data';
	
					return $returning_html_data;
				}
			}
		}
		
		private function _create(){
			define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

			//date_default_timezone_set('Europe/London');

			
			/** Simple HTML DOM */
			require($this->class_settings['calling_page'].'classes/simplehtmldom/simple_html_dom.php');
			
			/** Include PHPExcel */
			require_once $this->class_settings['calling_page'].'classes/PHPExcel.php';
			
			/** PHPExcel_IOFactory */
			require_once $this->class_settings['calling_page'].'classes/PHPExcel/IOFactory.php';
			
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load($this->class_settings['calling_page']."classes/PHPExcel/templates/30template.xls");
			
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Gas Helix")
										 ->setLastModifiedBy("Gas Helix")
										 ->setTitle("Office 2007 XLSX Test Document")
										 ->setSubject("Office 2007 XLSX Test Document")
										 ->setDescription("Gas Helix Report")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Gas Gelix Report");


			//Traverse HTML
			$html_dom = str_get_html($this->html);
			
			
			/*
			// Create a first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "Firstname");
			$objPHPExcel->getActiveSheet()->setCellValue('B1', "Lastname");
			$objPHPExcel->getActiveSheet()->setCellValue('C1', "Phone");
			$objPHPExcel->getActiveSheet()->setCellValue('D1', "Fax");
			$objPHPExcel->getActiveSheet()->setCellValue('E1', "Is Client ?");
			*/
			
			$i = 2;
			
			$thead = $html_dom->find('thead tr');
			$col_span = 0;
			
			$start_col[$i] = 0;
			
			foreach($thead as $tr) {
				
				$cols = $start_col[$i];
				
				if( ! isset($start_col[$i+1]) )
					$start_col[$i+1] = 0;
				
				$col_alphabet_count = 1;
				$col_span_alphabet_count = 1;
				$col_aplhabets = array();
				$col_span_aplhabets = array();
				
				foreach($tr->find('th') as $td){
					++$cols;
					
					if( isset($td->colspan) && intval($td->colspan) > 1 ){
						$col_span = $cols + intval($td->colspan) - 1;
					}
					
					if($cols  > 26){
						$cols = 1;
						
						$col_aplhabets[$col_alphabet_count] = chr(64+$col_alphabet_count);
						++$col_alphabet_count;
					}
					
					$col_aplhabets[$col_alphabet_count] = chr($cols+64);
					
					if( $col_span ){
						if($col_span  > 26){
							$col_span_aplhabets[$col_span_alphabet_count] = chr(64+$col_span_alphabet_count);
							++$col_span_alphabet_count;
						}
						$col_span_aplhabets[$col_span_alphabet_count] = chr($col_span+64);
						
						$objPHPExcel->getActiveSheet()->mergeCells(implode($col_aplhabets). $i.':'.implode($col_span_aplhabets). $i);
						
						$cols = $col_span;
						$col_span = 0;
					}
					
					if( isset($td->rowspan) && intval($td->rowspan) > 1 ){
						for( $x = 1; $x < intval($td->rowspan); $x++ ){
							if( isset( $start_col[$i+$x] ) )
								++$start_col[$i+$x];
							else
								$start_col[$i+$x] = 1;
						}
						
						$objPHPExcel->getActiveSheet()->mergeCells(implode($col_aplhabets). $i.':'.implode($col_aplhabets). ( $i + intval($td->rowspan) - 1) );
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue( implode($col_aplhabets). $i, strip_tags($td->innertext));
					
					switch($cols){
					case 1:
						$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setWidth(10);
					break;
					default:
						$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getAlignment()->setWrapText(true);

						//$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setWidth(32);
					break;
					}
				}
				
				++$i;
			}

			// Rows to repeat at top
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $i);
			
			
			//$i = 3;
			
			$rows = $html_dom->find('tr');
			foreach($rows as $tr) {
				
				$cols = 0;
				
				$col_alphabet_count = 1;
				$col_aplhabets = array();
				
				foreach($tr->find('td') as $td){
					++$cols;
					
					if($cols  > 26){
						$cols = 1;
						
						$col_aplhabets[$col_alphabet_count] = chr(64+$col_alphabet_count);
						++$col_alphabet_count;
					}
					
					$col_aplhabets[$col_alphabet_count] = chr($cols+64);
					
					$objPHPExcel->getActiveSheet()->setCellValue( implode($col_aplhabets). $i, trim(html_entity_decode(strip_tags($td->plaintext))) );
					
				}
				
				++$i;
			}
			
			// Add data
			/*
			for ($i = 2; $i <= 5000; $i++) {
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "FName $i")
											  ->setCellValue('B' . $i, "LName $i")
											  ->setCellValue('C' . $i, "PhoneNo $i")
											  ->setCellValue('D' . $i, "FaxNo $i")
											  ->setCellValue('E' . $i, true);
			}
			*/

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save($this->filename);
			
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter->save( $this->filename);

		}
	
		private function _temp($title,$creator){
			$returning_html_data = '';
			
			$returning_html_data .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">';
				$returning_html_data .= '<tr><td style="float:left;">';
					$returning_html_data .= $creator;
					$returning_html_data .= ' | '.date("j-M-y H:i");
			$returning_html_data .= '</td>';
			$returning_html_data .= '</tr><tr>';
			$returning_html_data .= '<td style="font-size:20px;  padding-top:5pt; text-align:center;" valign="top">';
				$returning_html_data .= $title;
			$returning_html_data .= '</td>';
			$returning_html_data .= '</tr></table>';
			
			return $returning_html_data;
		}
		
		private function _html_head($authenticated_report_id = ''){
			if(!$authenticated_report_id)
				$authenticated_report_id = '';
				
			$returning_html_data = '<head>';
			$returning_html_data .= '<style>';
				if(file_exists($this->class_settings['calling_page'].'css/'.$this->report_css_file.'.css'))
					$returning_html_data .= read_file('',$this->class_settings['calling_page'].'css/'.$this->report_css_file.'.css');
			$returning_html_data .= '</style>';
			$returning_html_data .= '</head>';
			$returning_html_data .= '<body><div id="watermark"><br />'.$authenticated_report_id.'&nbsp;&nbsp;&nbsp;</div>';
			return $returning_html_data;
		}
	}		
?>