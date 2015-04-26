<?php
	/**
	 * Search Class
	 *
	 * @used in  				All Advance Search / Clear Search Functions
	 * @created  				21:11 | 17-08-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Advance Search / Clear Search in all Modules
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSearch{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'search';
		
		function search(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'search':
				//ADVANCE SEARCH AND FILTER
				$returned_value = $this->_search();
			break;
			case 'perform_search':
				//ADVANCE SEARCH AND FILTER
				$returned_value = $this->_perform_search();
			break;
			case 'clear_search':
				//CLEAR ADVANCE SEARCH AND FILTER
				$returned_value = $this->_clear_search();
			break;
			}
			
			return $returned_value;
		}
		
		private function _search(){
			$returning_html_data = '';
			
			//Check if edit mode is on
			$values = array();
			$values[3] = 'none';
			
			//Check if Table to Search isset
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$classname = $_GET['search_table'];
				
				//Return Searchable Form by directly inheriting new method of class
				$result_of_sql_query = 'c'.ucwords($classname);
				
				$module = new $result_of_sql_query();
				$module->class_settings = $this->class_settings;
				
				$module->class_settings['action_to_perform'] = 'create_new_record';
				$module->class_settings['searching'] = 1;
				
				$module->class_settings['form_action'] = '?action='.$this->table_name.'&todo=perform_search&search_table='.$classname;
				$module->class_settings['form_html_id'] = 'searchable';
				$module->class_settings['form_submit_button'] = 'Search';
				
				$data = $module->$classname();
				
				return array(
					'html' => $returning_html_data.$data['html'],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'display-advance-search-form',
					'message' => 'Returned advance search form',
				);
				
			}else{
				//Log Error and return error message
				$returning_html_data .= '<h3>No Searchable Dataset was found!</h3>';
				return array('html' => $returning_html_data);
			}
		}
		
		private function _perform_search(){
			//SET TABLE
			$save = 0;
			$query = '';
			
			$search_table = $this->table_name;
			
			//Check if Table to Search was set
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$search_table = $_GET['search_table'];
			}
			
            $classname = $search_table;
            //Return Searchable Form by directly inheriting new method of class
            $result_of_sql_query = 'c'.ucwords($classname);
            
            $module = new $result_of_sql_query();
            $module->class_settings = $this->class_settings;
            $module->class_settings['action_to_perform'] = '';
            $module->$classname();
			/**************************************************************************/
			/*****************RECIEVE USER INPUT FROM FILLED FORM**********************/
			/**************************************************************************/
			//Validate Table
			if(isset($_POST['table']) && $_POST['table']){
				//CHANGE TABLE TO SEARCHABLE TABLE
				$this->table_name = $_POST['table'];
				
				$multiple_search_condition = '';
				if(isset($_POST['multiple_search_condition']) && ($_POST['multiple_search_condition']=='OR' || $_POST['multiple_search_condition']=='AND' ))
					$multiple_search_condition = $_POST['multiple_search_condition'];
				
				$single_search_condition = '';
				if(isset($_POST['single_search_condition']) && ($_POST['single_search_condition']=='OR' || $_POST['single_search_condition']=='AND' ))
					$single_search_condition = $_POST['single_search_condition'];
				
				//2. PREPARE FORM OPTIONS
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
					
					$err->class_that_triggered_error = 'cSearch.php';
					$err->method_in_class_that_triggered_error = '_perform_search';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 125';
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
				
				$form->searching = 1;	//Set Search Mode
				
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				//3. Update the current step
				$form->step = $form->nextstep;
				
				//4. Pick current record id
				$this->record_id = $form->record_id;
				$values[0] = $form->record_id;
				
				
				//5. Insert array into database
				if(isset($field_values_pair) && is_array($field_values_pair)){
					//print_r($field_values_pair['field'].'--'.$field_values_pair['value']);
					//exit;
					
					//6. Update existing record
					$settings_array = array(
						'database_name' => $this->class_settings['database_name'] ,
						'database_connection' => $this->class_settings['database_connection'] ,
						'table_name' => $this->table_name ,
						'field_and_values' => $field_values_pair['form_data'] ,
						'where_fields' => 'id' ,
						'where_values' => $field_values_pair['id'] ,
						'where_condition' => $single_search_condition ,
					);
					$save = search( $settings_array );
					
					//$save = search( $this->class_settings['database_name'],$this->table_name,$this->class_settings['database_connection'] , $field_values_pair['search_condition'],$field_values_pair['field'],$field_values_pair['value'],$single_search_condition );
					
					if(is_array($save) && isset($save[0]) && isset($save[1]) && isset($save[2])){
						//Set Temporary Search Query for Ajax_server
						$sq = md5('search_query'.$_SESSION['key']);
						$_SESSION[$sq][$search_table]['select'] = $save[0];
						$_SESSION[$sq][$search_table]['from'] = $save[1];
						
						//Check if query was previously set
						if(isset($_SESSION[$sq][$search_table]['where']) && $_SESSION[$sq][$search_table]['where']){
							$save[2] = str_replace("WHERE","",$save[2]);
							$_SESSION[$sq][$search_table]['where'] .= " ".$multiple_search_condition.str_replace("AND `".$this->table_name."`.`record_status`='1'","",$save[2]);
						}else{
							$_SESSION[$sq][$search_table]['where'] = $save[2];
						}
						
						$query = convert_to_highlevel_query($_SESSION[$sq][$search_table]['where'],$this->table_name);
					}
				}else{
					if($field_values_pair == '-1'){
						//RETURN INVALID TOKEN ERROR
						$err = new cError(000002);
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cSearch.php';
						$err->method_in_class_that_triggered_error = '_perform_search';
						$err->additional_details_of_error = 'invalid token on line 167 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError(000101);
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cSearch.php';
						$err->method_in_class_that_triggered_error = '_perform_search';
						$err->additional_details_of_error = 'invalid data submitted via data capture form';
						
						return $err->error();
					}
				}
			}
			
			if($save){
				$_SESSION['search_trigger'] = 1;
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError(040101);
				$err->action_to_perform = 'notify';
				
				$array = $err->error();
			
				
				$sq = md5('search_query'.$_SESSION['key']);
				$_SESSION[$sq][$search_table]['query'] = $query;
				
				//Append Search Query to Returned Value
				$array['search_query'] = strip_tags( $query );
				$array['searched_table'] = $search_table;
				$array['status'] = 'reload-datatable';
				
				return $array;
			}
			
			return $save;
		}

		private function _clear_search(){
			$returning_html_data = '';
			
			//Check if Table to Search isset
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$classname = $_GET['search_table'];
				
				//Clear Search Query
				$sq = md5('search_query'.$_SESSION['key']);
				if(isset($_SESSION[$sq][$classname])){
					unset($_SESSION[$sq][$classname]);
					
					$_SESSION['search_trigger'] = 1;
					
					//RETURN SEARCH QUERY SUCCESSFULLY CLEARED NOTIFICATION
					$err = new cError(040201);
					$err->action_to_perform = 'notify';
					
					$array = $err->error();
					$array['searched_table'] = $classname;
					$array['search_query'] = '';
					$array['status'] = 'reload-datatable';
					
					return $array;
				}
			}
			
			//RETURN NO SEARCH QUERY FOUND NOTIFICATION
			$err = new cError(040202);
			$err->action_to_perform = 'notify';
			
			return $err->error();
		}
		
	}
?>