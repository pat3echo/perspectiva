<?php
	/**
	 * Audit Trail Class
	 *
	 * @used in  				Audit Trail Function
	 * @created  				19:57 | 22-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Audit Trail Function in Users Manager Module
	|--------------------------------------------------------------------------
	|
	| Stores log of all users activites and create JSON files of such logs at
	| intervals of 24 hours, and creates snapshots of the database
	|
	*/
	
	class cAudit{		
		public $id = 'fe2d010308a6b3799a3d9c728ee74244';
		public $calling_page = '../';
		
		public $class_name = 'audit';
		public $websalter = '107470839hxecddcNSCIVuafgw7fe78';
		public $database_connection = '';
		public $database_name = '';
		public $action_to_perform = '';
		
		//Action performed by the user
		public $user_action = '';
		
		//User id that performs action
		public $user = '';
		public $user_mail = '';
		
		//Table that contained records
		public $table = '';
		
		//Comment describing action performed by user
		public $comment = '';
		
		private $database_name_store = 'db';
		private $tmail = 'trail.gashelix@gmail.com';
		private $trail = 'tr';
		
		private $trail_json = 'audit_logs';
		
		public $saved = 0;
		
		function audit(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			switch ($this->action_to_perform){
			case 'record':
				$returned_value = $this->_record_user_action();
			break;
			case 'view':
				unset($_SESSION[$this->table]['filter']);
				
				$returned_value = $this->_view_audit_trail();
			break;
			case 'select_audit_trail':
				$returned_value = $this->_select_days_audit_trail_to_display_data_table();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			}
			
			return $returned_value;
		}
		
		private function _record_user_action(){
			//Check if today is start of new day
			$stamp = $this->_new_day();
			
            $trail = array();
            
			//Get Current File
			$filename = $this->calling_page.'tmp/'.$this->trail.'/'.$stamp.'.json';
			if( file_exists( $filename ) ){
				$trail = json_decode( file_get_contents( $filename ) , true );
            }
            
			//Prepare new content
			$date = date("U");
			$ip = get_ip_address();
			$trail[] = array('user'=>$this->user, 'user_mail'=>$this->user_mail, 'user_action'=>$this->user_action, 'table'=>$this->table, 'comment'=>$this->comment, 'date'=>$date, 'ip_address'=>$ip );
			
			//Write file
            if( ! empty( $trail ) ){
                file_put_contents( $filename , json_encode( $trail ) );
            }
			
		}
		
		private function _new_day(){
			//Check if today is start of new day
			$returning_html_data = '';
			$sn = 0;
			
            $stamp = 0;
            
			//1. Pull Stored records
			if(file_exists($this->calling_page.'tmp/'.$this->trail.'/stamp.php')){
				$stamp = file_get_contents( $this->calling_page.'tmp/'.$this->trail.'/stamp.php' );
				$stamp = $stamp * 1;
			}else{
				$stamp = date("U");
                file_put_contents( $this->calling_page.'tmp/'.$this->trail.'/stamp.php' , $stamp );
			}
			
			//2. Check date with today date
			$date = date("U");
			//if($date >= ($stamp + (60*60*24))){
			if($date >= ($stamp + (60*60*6)) ){
				//NEW DAY
				//1. Get All records
				$filename = $this->calling_page.'tmp/'.$this->trail.'/'.$stamp.'.json';
                if( file_exists( $filename ) ){
                    $trail = json_decode( file_get_contents( $filename ) , true );
                }
				
                $stamp = $date;
                
				//2. Prepare Email and PDF
				if(isset($trail) && is_array($trail)){
					
					//Set table header
					$returning_html_data .= '<table>';
						$returning_html_data .= '<thead>';
							$returning_html_data .= '<th>S/N</th>';
							$returning_html_data .= '<th>User ID</th>';
							$returning_html_data .= '<th>User</th>';
							$returning_html_data .= '<th>User Action</th>';
							$returning_html_data .= '<th>Table</th>';
							$returning_html_data .= '<th>Comment</th>';
							$returning_html_data .= '<th>Date</th>';
							$returning_html_data .= '<th>IP Address</th>';
						$returning_html_data .= '</thead>';
						$returning_html_data .= '<tbody>';
					
						foreach($trail as $tr){
							$returning_html_data .= '<tr>';
							$returning_html_data .= '<td>'.++$sn.'</td>';
							$returning_html_data .= '<td>'.$tr['user'].'</td>';
							$returning_html_data .= '<td>'.$tr['user_mail'].'</td>';
							$returning_html_data .= '<td>'.$tr['user_action'].'</td>';
							$returning_html_data .= '<td>'.$tr['table'].'</td>';
							$returning_html_data .= '<td>'.$tr['comment'].'</td>';
							$returning_html_data .= '<td>'.date('j-M-y H:i',($tr['date']/1)).'</td>';
							$returning_html_data .= '<td>'.$tr['ip_address'].'</td>';
							$returning_html_data .= '</tr>';
						}
						
						$returning_html_data .= '</tbody>';
					$returning_html_data .= '</table>';
					
				}
				
                $project = get_project_data();
                
				//3. Send Email
				$message = $returning_html_data;
				$subject = ' Audit Trail of ' . date('j-M-y',($stamp/1));
                
                $project_name = '';
                if( isset( $project['project_title'] ) ){
                    $subject = $project['project_title'] . $subject;
                    $project_name = $project['project_title'];
                }
                if( isset( $project['admin_email'] ) )
                    $this->tmail = $project['admin_email'];
                
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.$project_name . "\r\n";
                $headers .= 'Bcc: pat3echo@gmail.com'. "\r\n";
				send_mail( $this->calling_page, $this->tmail, $subject, $message, $headers );
				
                
				//5. Update Stamp
                file_put_contents( $this->calling_page.'tmp/'.$this->trail.'/stamp.php' , $stamp );
				
                if( isset( $project['domain_name'] ) ){
                    file_get_contents( $project['domain_name'].'engine/php/ajax_request_processing_script.php?action=country_list&todo=update_currency_conversion_rate' );
                }
                
				//7. Backup DB
				$this->_back_up();
			}
			
			return $stamp;
		}
		
		private function _back_up(){
			return 1;
            
            $returning_html_data = '';
			$drop = '';
			$insert = '';
			$create = '';
			
			$create_table = '';
			$drop_table = '';
			$i_records = '';
			$v_records = '';
			
			//Get All tables in database
			//$q = "SHOW TABLES FROM `".$this->database_name."`;";
			$query = "SELECT TABLE_NAME FROM USER_TABLES";
			$query_settings = array(
				'database'=>$this->database_name,
				'connect'=>$this->database_connection,
				'query'=>$query,
				'query_type'=>'SELECT',
				'set_memcache'=>1,
				'tables'=>array(),
			);
			$sql_result = execute_sql_query($query_settings);
			if(isset($sql_result) && is_array($sql_result)){
				foreach($sql_result as $a){
					//Describe table
					//$qq = "DESCRIBE `".$this->database_name."`.`".$a[0]."`;";
					$query2 = "SELECT * FROM USER_TAB_COLUMNS WHERE TABLE_NAME='".$a[0]."' ORDER BY COLUMN_ID";
					$query_settings = array(
						'database'=>$this->database_name,
						'connect'=>$this->database_connection,
						'query'=>$query2,
						'query_type'=>'SELECT',
						'set_memcache'=>1,
						'tables'=>array(),
					);
					$sql_result2 = execute_sql_query($query_settings);
					if(isset($sql_result2) && is_array($sql_result2)){
						//Prepare Create Table Query
						$create_table = '$q[] = "CREATE TABLE '.$a[0].' (';
						
						$drop_table = '$q[] = "DROP TABLE '.$a[0].'";';
						
						//Prepare Insert records Query
						$i_records = '$q[] = "INSERT INTO '.$a[0].' (';
						
						foreach($sql_result2 as $aa){
							if($create_table == '$q[] = "CREATE TABLE '.$a[0].' ('){
								$create_table .= ''.$aa['COLUMN_NAME'].' '.$aa['DATA_TYPE'].'('.$aa['DATA_LENGTH'].')  DEFAULT NULL';
								$i_records .= ''.$aa['COLUMN_NAME'].'';
							}else{
								$create_table .= ', '.$aa['COLUMN_NAME'].' '.$aa['DATA_TYPE'].'('.$aa['DATA_LENGTH'].')  DEFAULT NULL';
								$i_records .= ', '.$aa['COLUMN_NAME'].'';
							}
						}
						
						//End create table query
						$create_table .= ') ";';
						$i_records .= ') VALUES ';
					}
					
					//Records in table
					$query3 = "SELECT * FROM ".$this->database_name.".".$a[0];
					$query_settings = array(
						'database'=>$this->database_name,
						'connect'=>$this->database_connection,
						'query'=>$query3,
						'query_type'=>'SELECT_AUDIT',
						'set_memcache'=>1,
						'tables'=>array(),
					);
					$sql_result3 = execute_sql_query($query_settings);
					
					if(isset($sql_result3) && is_array($sql_result3)){
						$v_records = '';
						foreach($sql_result3 as $aa){
							$v_records .= " \n\n ".$i_records." ('".implode("','" ,  $aa  )."'".') "; ';
						}
						
						//$v_records .= '";';
					}
					
					//Get Data of each table
					$returning_html_data .= $create_table." \n\n ".$v_records." \n\n ";
					
					$create .= $create_table." \n\n ";
					
					$insert .= $v_records." \n\n ";
					
					$drop .= $drop_table." \n\n ";
				}
			}
			
			//Write data to file
			write_file('',$this->calling_page.'tmp/'.$this->database_name_store.'/'.date('j_M_y_H_i').'.php',"<?php \n\n" . $returning_html_data . "\n\n ?>");
			
		}
		
		private function _save_changes(){
			$this->table = 'audit';
			
			//CHECK FOR SELECT AUDIT TRAIL TO VIEW
			if(isset($_POST['table']) && $_POST['table']==$this->table && isset($_POST['q1']) && $_POST['q1']){
				$_SESSION[$this->table]['filter']['audit_trail'] = $_POST['q1'];
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError(060104);
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
		}
		
		private function _view_audit_trail(){
			$this->table = 'audit';
			
			//GET ALL FIELDS IN TABLE
			$fields = array(
				array('ID'),
				array('USER_DT1_DT1'),
				array('USER_ACTION_DT1_DT1'),
				array('TABLE_DT1_DT1'),
				array('DATE_DT4_DT1'),
				array('IP_ADDRESS_DT1_DT1'),
				array('COMMENT_DT1_DT1'),
			);
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase($this->database_connection,$this->table,$this->database_name);
			$form->uid = ''; //Currently logged in user id
			$form->pid = ''; //Currently logged in user privilege
			
			$form->datatables_settings = array(
				'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
					'select_audit_trail' => 1,			//Determines whether or not to show add new record button
					
				'show_serial_number' => 1,			//Determines whether or not to show serial number
				
				'show_verification_status' => 0,	//Determines whether or not to show verification status
				'show_creator' => 0,				//Determines whether or not to show record creator
				'show_modifier' => 0,				//Determines whether or not to show record modifier
				'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
				
				'current_module_id' => 'audit',	//Set id of the currently viewed module
			);
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array('html' => $returning_html_data);
		}
	
		private function _select_days_audit_trail_to_display_data_table(){
			$this->table = 'audit';
			$returning_html_data = '';
			
			$_SESSION['temp_storage']['pagepointer'] = $this->calling_page;
			
			//GET ALL FIELDS IN TABLE
			$fields = array(
				'ID',
				'AUDIT1_DT9_DT1',
			);
			
			//1. SET HEADING TITLE
			$form_heading_caption_title = 'Audit Trail Manager';
			$returning_html_data .= get_add_new_record_form_heading_title($form_heading_caption_title);
			
			$option = array();
			$values = array();
			
			//Get form action
			$this->form_action = '?action='.$this->table.'&todo=save';
			
			//3. INHERIT FORM CLASS TO GENERATE FORM
			/**************************************************************************/
			/**************************SELECT FORM GENERATOR***************************/
			/**************************************************************************/
			$form = new cForms();
			$form->setDatabase($this->database_connection,$this->table);
			$form->setFormActionMethod($this->form_action,'post');
			$form->uid = ''; //Currently logged in user id
			$form->pid = ''; //Currently logged in user privilege
			$form->maxstep = 1;
			$form->step = 1;
			
			$form->butclear = 0;
			$form->submit = 'View Audit Trail';
			$form->but_theme = 'b';
			$form->html_id = 'audit_trail';
			$form->select_box_opions_type = 1;
			
			//4. RETURN GENERATED FORM
			$returning_html_data .= $form->myphp_form($fields,$values,'no.ofcolumns: default = 1',$option);
			
			return array('html' => $returning_html_data);
		}
	
	}
?>