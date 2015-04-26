<?php
	/**
	 * My PDF Class
	 *
	 * @used in  				Generating PDF Reports
	 * @created  				14:51 | 27-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| My PDF
	|--------------------------------------------------------------------------
	|
	| Generates PDF reports in the Gas Helix
	|
	*/
	
	class cMypdf{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = '';
		
		//Directory for Entities
		private $entity_directory = 'files';
		
		//ID of Root Label
		private $current_label = '1300';	//Root Label
		
		//Report Orientation and paper size
		private $orientation = "portrait";
		private $paper = "a4";
		private $report_css_file = "pdf-report-grid";
		private $report_number_of_signatories = 0;
		
		private $type_of_report = 'pdf';
		
		function mypdf(){
			//INITIALIZE RETURN VALUE
			$returned_value = array();
			
			//CHECK FOR CURRENT MODULE
			if(isset($_GET['module']))$this->current_module = $_GET['module'];
			
			if((isset($_POST['orientation']) && $_POST['orientation']) ){
				$this->orientation = $_POST['orientation'];
			}
			
			if((isset($_POST['paper']) && $_POST['paper']) ){
				$this->paper = $_POST['paper'];
			}
			
			if((isset($_GET['orientation']) && $_GET['orientation'])){
				$this->orientation = $_GET['orientation'];
			}
			
			if(isset($_GET['paper']) && $_GET['paper']){
				$this->paper = $_GET['paper'];
			}
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create':
				$returned_value = $this->_create();
			break;
			case 'generate_pdf':
				$returned_value = $this->_generate_pdf();
			break;
			case 'generate_html':
				$returned_value = $this->_generate_html();
			break;
			case 'generate_text':
				$this->type_of_report = "text";
				
				$returned_value = $this->_generate_pdf();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_html(){
			$returning_html_data = 'Generating HTML';
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
				$date_of_generation = date("jS-F-Y-H-i");
				
				//Get Users Full Name
				$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
				if(isset($_SESSION[$current_user_details_session_key])){
					$current_user_session_details = $_SESSION[$current_user_details_session_key];
				}
				
				$creator = '';
				if(isset($current_user_session_details['fname']) && isset($current_user_session_details['lname'])){
					$creator = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
				}
				
				//Report Title
				$report_title = "Archived Dataset of ".$date_of_generation;
			
				if( isset( $_POST['report_title'] ) && $_POST['report_title']){
					$report_title = $_POST['report_title'];
				}
				
				$report_headers = '';
				
				//INSERT AUTHENTICATION ID FOR REPORT
				//if($authenticated_report_id){
					//$report_headers .= get_authenticated_report_header_file_contents($authenticated_report_id);
				//}
				
				//Set HTML HEADERS
				$temp_html = $this->_html_head($authenticated_report_id);
				$temp_temp = $this->_temp($report_title,$creator);
				
				$html_only = $temp_html.$temp_temp;
				
				if( file_exists($this->class_settings[ 'calling_page' ].$this->entity_directory.'/components/header/report.jpg') ){
					$report_headers .= '<table width="100%">';
					$report_headers .= '<thead>';
						$report_headers .= '<tr>';
							$report_headers .= '<th>';
								$report_headers .= '<img src="'.$this->class_settings[ 'calling_page' ].$this->entity_directory.'/components/header/report.jpg" alt="Header" />';
							$report_headers .= '</th>';
						$report_headers .= '</tr>';
					$report_headers .= '</thead>';
					$report_headers .= '</table>';
				}
				
				$this->html = $temp_html.$report_headers.$temp_temp;
				
				//Set html Data
				$html_only .= $_POST['html'] . '</body>';
				$this->html .= $_POST['html'] . '</body>';
				
				//Create Directory
				$directory_name = 'archives/'.date("Y");
				$directory = $this->class_settings[ 'calling_page' ].$directory_name;
				$dir = create_folder('',$directory,'');
				
				$directory_name .= '/'.date("F");
				$directory = $this->class_settings[ 'calling_page' ].$directory_name;
				$dir = create_folder('',$directory,'');
				
				write_file('',$dir.'/'.$new_record_id_base.'.php',"<?php \n\n //Authenticate User \n\n ".'$pagepointer'." = '../../../'; \n\n require_once ".'$pagepointer."includes/verify_user_privilege_to_view_archived_dataset.php"'."; \n\n echo str_replace('../entities','../../../entities',stripslashes('".addslashes($this->html)."')); \n\n ?>");
				
				if( file_exists( $dir.'/'.$new_record_id_base.'.php' ) ){
					$returning_html_data = '';
					
					$class_name = '';
					$description = 'Not Available';
					if(isset($_POST['current_module']) && $_POST['current_module']){
						$class_name = $_POST['current_module'];
						
						if( isset( $_SESSION[ $class_name ][ 'archived_dataset_description' ] ) && $_SESSION[ $class_name ][ 'archived_dataset_description' ] ){
							$description = $_SESSION[ $class_name ][ 'archived_dataset_description' ];
						}
					}
					
					$this->table = 'archived_dataset';
					
					//ARCHIVED DATA RECORD
					$function_settings = array(
						'database'=>$this->database_name,
						'connect'=>$this->database_connection,
						'table'=>$this->table,
						
						'ID' => $new_record_id_base,
						
						'ARCHIVED_DATASET1_DT10_DT1_DT1' => $directory_name.'/'.$new_record_id_base.'.php',
						'ARCHIVED_DATASET2_DT1_DT1' => $description,
						'ARCHIVED_DATASET3_DT1_DT1_DT5' => $class_name,
						
						'CREATOR9000_DT9_DT11' => $this->class_settings[ 'user_id' ],
						'CREATOR_ROLE_DT1_DT6' => $this->priv_id,
						'record_status' => '1',
						'CREATION_DATE_DT4_DT8' => $current_date_time,
						'modification_date' => $current_date_time,
						'IP_ADDRESS_DT1_DT10' => get_ip_address(),
						'modified_by' => $this->class_settings[ 'user_id' ],
					);
					insert_new_record_into_table($function_settings);
					
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError(000008);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_html';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
					
					/*
					//RECORD CREATION FAILED ERROR
					$err = new cError(000007);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_html';
					$err->additional_details_of_error = 'could not create record on line 253';
					
					return $err->error();
					*/
				}
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError(000009);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cMypdf.php';
				$err->method_in_class_that_triggered_error = '_generate_html';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _generate_pdf(){
			$returning_html_data = 'Generating PDF';
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
				
				$body_content_headers = '';
				
				$reference = 'NAP/GASHELIX';
			
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
				
				$creator_name = $creator;
				
				$report_headers = '';
				
				if( ! ( isset( $_POST[ 'report_show_user_info' ] ) && $_POST[ 'report_show_user_info' ] == 'yes' ) ){
					$_POST[ 'report_show_user_info' ] = $creator;
					$creator_name = '';
				}
				
				//Set HTML HEADERS
				$temp_html = $this->_html_head( $new_record_id_base , $creator_name );
				
				$html_only = $temp_html;
				
				$report_headers_file = '';
				$report_source_type = '';
				$report_description = '';
				$report_file_name = '';
				
				//Create User Folder
				$dir = create_report_directory( $this->class_settings );
			
				//Set Output File Name
				$this->filename = $dir.'/s'.$new_record_id_base.'.pdf';
				
				$report_headers_file = '<img src="'.$this->class_settings[ 'calling_page' ].$this->entity_directory.'/components/header/report.jpg" alt="Header" />';
				
				$this->html = $temp_html;
				
				//Set html Data
				$html_only .= $_POST['html'] . '</body>';
				$this->html .= $_POST['html'] . '</body>';
				
				//WRITE EDITABLE HTML FILE
				$editable_html_file_url = $dir.'/e'.$new_record_id_base.'.php';
				
				write_file('',$editable_html_file_url,$html_only);	//Insert only body content here
			
				//Generate Report
				$this->_create();
				
				//Check if report was created
				if(file_exists($this->filename)){
					//ADD REPORT TO DATABASE
					$reports_handler = new cReports();
					$reports_handler->class_settings = $this->class_settings;
					$reports_handler->class_settings[ 'action_to_perform' ] = 'add_generated_report_record';
					
					$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/s'.$new_record_id_base . '.pdf';
					
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
					
					$returning_html_data = 'File Created On: '.$date_of_generation . $created_by . '<br /><a class="btn btn-primary" href="' . $file_url . '" target="_blank" ><i class="icon-download-alt icon-white">&nbsp;&nbsp;</i> Download PDF Report</a>';
					
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = '';
	
					$return = $err->error();
					
					$return[ 'msg' ] = $returning_html_data;
					
					return $return;
					
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError(000008);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMypdf.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
				}
				
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError(000009);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cMypdf.php';
				$err->method_in_class_that_triggered_error = '_generate_pdf';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'status' => 'generated-report',
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _create(){
			require_once( $this->class_settings[ 'calling_page' ] . "classes/dompdf/dompdf_config.inc.php"); 
			spl_autoload_register("DOMPDF_autoload");
			
			//Create new pdf file
			$dompdf = new DOMPDF();
			//$dompdf->load_file( $this->class_settings[ 'calling_page' ].'testfile.html' );
			//$html = $this->tidy( $this->html );
			
			$dompdf->load_html( $this->html );
			
			
			$dompdf->set_paper($this->paper, $this->orientation);
			/*
				<select name="orientation">
				  <option value="portrait">portrait</option>
				  <option value="landscape">landscape</option>
				</select>
			*/
			$dompdf->render();
			
			if ($this->stream==1) {
				$dompdf->stream($this->filename);
			} elseif($this->stream==2) {
				return $dompdf->output();
			}else {
				$data = $dompdf->output();
				//Write data to file
				write_file('',$this->filename,$data);
			}
		}
	
		 /**
		 * Tidy the HTML for the PDF generation.
		 * @param type $html
		 * @param array $userConfig Configuration settings for Tidy. See the Tidy website for details.
		 * @return string The Tidy'd HTML
		 * @author The internets & jez
		 */  
		private function tidy($html, $userConfig = FALSE ) {       
			// default tidyConfig. Most of these are default settings.
			$config = array(
				'show-body-only' => false,
				'clean' => true,
				'char-encoding' => 'utf8',
				'add-xml-decl' => true,
				'add-xml-space' => true,
				'output-html' => false,
				'output-xml' => false,
				'output-xhtml' => true,
				'numeric-entities' => false,
				'ascii-chars' => false,
				'doctype' => 'strict',
				'bare' => true,
				'fix-uri' => true,
				'indent' => true,
				'indent-spaces' => 4,
				'tab-size' => 4,
				'wrap-attributes' => true,
				'wrap' => 0,
				'indent-attributes' => true,
				'join-classes' => false,
				'join-styles' => false,
				'enclose-block-text' => true,
				'fix-bad-comments' => true,
				'fix-backslash' => true,
				'replace-color' => false,
				'wrap-asp' => false,
				'wrap-jste' => false,
				'wrap-php' => false,
				'write-back' => true,
				'drop-proprietary-attributes' => false,
				'hide-comments' => false,
				'hide-endtags' => false,
				'literal-attributes' => false,
				'drop-empty-paras' => true,
				'enclose-text' => true,
				'quote-ampersand' => true,
				'quote-marks' => false,
				'quote-nbsp' => true,
				'vertical-space' => true,
				'wrap-script-literals' => false,
				'tidy-mark' => true,
				'merge-divs' => false,
				'repeated-attributes' => 'keep-last',
				'break-before-br' => true,
			);               
			
			if( is_array($userConfig) ) {
				$config = array_merge($config, $userConfig);           
			}

			$tidy = new tidy();
			$output = $tidy->repairString($html, $config, 'UTF8');        
			return($output);
		}
		
		private function _html_head($authenticated_report_id = '', $creator = ''){
			if(!$authenticated_report_id)
				$authenticated_report_id = '';
				
			$returning_html_data = '<head>';
			$returning_html_data .= '<style>';
				if(file_exists($this->class_settings[ 'calling_page' ].'css/'.$this->report_css_file.'.css'))
					$returning_html_data .= read_file('',$this->class_settings[ 'calling_page' ].'css/'.$this->report_css_file.'.css');
			$returning_html_data .= '</style>';
			$returning_html_data .= '</head>';
			$returning_html_data .= '<body>';
			
			//Check the type of report to be generated
			switch( $this->type_of_report ){
			case "text":
				$returning_html_data .= '<input type="button" value="Print" onclick=" window.print(); " class="no-print" />';
				$returning_html_data .= '<div id="report-authentication-id">&nbsp;&nbsp;&nbsp;'.$authenticated_report_id.'<span style="color:#aaa;"> - '.$creator.' | '.date('jS F Y H:i').'</span></div>';
			break;
			default:
				$returning_html_data .= '<!--gashelixremovethis--><div id="watermark"><div id="report-authentication-id">&nbsp;&nbsp;&nbsp;'.$authenticated_report_id.'<span style="color:#aaa;"> - '.$creator.' | '.date('jS F Y H:i').'</span></div></div><!--gashelixremovethis-->';
			break;
			}
			
			return $returning_html_data;
		}
		
		private function _append_signatories_to_reports( $settings = array() ){
			$returning_html_data = '';
			
			$array_of_fields_for_signatory = array(
				'Organization',
				'Name',
				'Signature',
				'Date',
			);
			
			if( isset( $settings[ 'number_of_signatories' ] ) && $settings[ 'number_of_signatories' ] ){
			
				$returning_html_data .= '<br /><br /><table width="100%">';
				$returning_html_data .= '<tbody>';	
				$returning_html_data .= '<tr>';
				$returning_html_data .= '<td>';
				
				$returning_html_data .= '<fieldset style="font-family:arial; font-size:8pt;">';
				$returning_html_data .= '<legend>Signatories</legend>';
				$returning_html_data .= '<table width="100%">';
					$returning_html_data .= '<tbody>';
						$returning_html_data .= '<tr>';
							
						$returning_html_data .= '<td vertical-align="top">';
							$returning_html_data .= '<table width="100%">';
								$returning_html_data .= '<tbody>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td><b>&nbsp;</b></td>';
									$returning_html_data .= '<td>&nbsp;</td>';
								$returning_html_data .= '</tr>';
								$returning_html_data .= '</tbody>';
							$returning_html_data .= '</table>';
						$returning_html_data .= '</td>';
						
						for( $signatories_count = 0; $signatories_count < $settings[ 'number_of_signatories' ]; $signatories_count++ ){
							$returning_html_data .= '<td vertical-align="top">';
								
								$returning_html_data .= '<table width="100%">';
								$returning_html_data .= '<tbody>';
								
								foreach( $array_of_fields_for_signatory as $signatory_field ){
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td width="10" ><b>' . $signatory_field . '</b></td>';
									$returning_html_data .= '<td style="border-bottom:1px solid #111;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
								$returning_html_data .= '</tr>';
								}
								
								$returning_html_data .= '</tbody>';
								$returning_html_data .= '</table>';
								
							$returning_html_data .= '</td>';
						}
							
						$returning_html_data .= '</tr>';
					$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				$returning_html_data .= '</fieldset>';
				
				$returning_html_data .= '</td>';
				$returning_html_data .= '</tr>';
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
			}
			
			return $returning_html_data;
		}
	
	}		
?>