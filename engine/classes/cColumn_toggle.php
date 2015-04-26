<?php
	/**
	 * Column Toggle Class
	 *
	 * @used in  				Show / Hide Column Function
	 * @created  				09:11 | 24-08-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Column Toggle in Toolbars
	|--------------------------------------------------------------------------
	|
	| Used to hide / show columns in the dataTables
	|
	*/
	
	class cColumn_toggle{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'column_toggle';
		
		function column_toggle(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'column_toggle':
				//Keeps Track of Hidden and Showing Columns
				$returned_value = $this->_column_toggle();
			break;
			}
			
			return $returned_value;
		}
		
		private function _column_toggle(){
			$returning_html_data = '';
			
			//Check if Table to column_toggle isset
			if(isset($_GET['column_toggle_table']) && $_GET['column_toggle_table'] && isset($_GET['column_toggle_name']) && $_GET['column_toggle_name'] && isset($_GET['column_toggle_num']) ){
				//Validate Table
				$classname = $_GET['column_toggle_table'];
				$column = $_GET['column_toggle_name'];
				$column_num = $_GET['column_toggle_num'];
				$column_state = 'unchecked';
				
				//Toggle Column
				$sq = md5('column_toggle'.$_SESSION['key']);
				if(isset($_SESSION[$sq][$classname][$column])){
					unset($_SESSION[$sq][$classname][$column]);
					$column_state = 'checked';
				}else{
					$_SESSION[$sq][$classname][$column] = 1;
					$column_state = 'unchecked';
				}
				
				//RETURN COLUMN TOGGLE SUCCESSFUL
				$err = new cError(050101);
				$err->action_to_perform = 'notify';
				
				$returned_value = $err->error();
				
				//Append Column Name & Number
				$returned_value['column_name'] = $column;
				$returned_value['column_num'] = $column_num;
				$returned_value['column_state'] = $column_state;
				$returned_value['status'] = 'column-toggle';
				
				return $returned_value;
			}
			
			//RETURN NO column_toggle QUERY FOUND NOTIFICATION
			$err = new cError(050102);
			$err->action_to_perform = 'notify';
			
			return $err->error();
		}
		
	}
?>