<?php
	/**
	 * Gas Helix Prepare JSON dataset for Populating DataTables File
	 *
	 * @used in  				ajax_server/*.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Prepare JSON dataset for Populating DataTables File
	|--------------------------------------------------------------------------
	|
	| Read array of database columns which would be sent back to DataTables.
	|
	*/
	
	/* Total data set length */
	$iTotal = count($arr);
	
	if(!isset($_GET['sEcho']))$_GET['sEcho']=1;
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	//Get Application Users Names [F. Last Name]
	
	//write_file('','ds.txt',$id2val->txt);
	if(isset($_GET['iDisplayStart']))$sn = $_GET['iDisplayStart'];
	else $sn = 0;
	
	//GET ARRAY OF VALUES FOR FORM LABELS
	$func = $table;
    if( isset( $table_real ) )$func = $table_real;
    
	if(function_exists($func))
		$form_label = $func();
	else
		$form_label = array();
	
	//CHECK FOR CONTROLLER TABLE
	if( isset( $field_controller_function ) ){
		if( function_exists( $field_controller_function ) ){
			$form_label = $field_controller_function();
		}
	}
	
	//CHECK WHETHER OR NOT TO DISPLAY TOTAL FOR SUMS
	if(isset($json_settings['show_total']) && $json_settings['show_total']){
		//Display Further Details
		if(isset($json_settings['show_total']) && $json_settings['show_total'] && isset($json_settings['show_total_function']) && $json_settings['show_total_function']){
			//Set Fixed First Row for Table
			$output['aaData'][0] = array();
		}
	}
	
	$record_id = '';
	
	$special_summed_values = array();
	
	$summed_values = array();
	$sub_summed_values = array();
	
	$summed_values_units = array();
	$summed_values_form_type = array();
	
	$special_table_formatting_top_row = array();
	$special_table_formatting_bottom_summed_values = array();
	
    
	for($count_records=0; $count_records<count($arr); $count_records++ )
	{
		$aRow = $arr[$count_records];
		
		$record_id = '<span style="font-size:0px; display:none;>'.$aRow['id'].'</span>';
		
		$skip_loop = 1;
		
		$row = array();
		
		//Check if Special Table Styling & Data Population is turned on
		if(isset($json_settings['special_table_formatting_top_row']) && $json_settings['special_table_formatting_top_row']){
			$special_table_formatting_function = $json_settings['special_table_formatting_top_row']."_function";
			
			if(function_exists($special_table_formatting_function)){
				//Set data to be sent
				$function_settings = array(
					'row_dt'=>$aRow,
					'record_id'=>$record_id,
					'visible_columns'=>$json_settings['special_table_formatting_visible_columns'],
					'serial_number'=>'<b id="'.$aRow['id'].'" class="datatables-record-id" style="font-size:0.8em;">'.++$sn.'</b>',
				);
				
				$special_row_output = $special_table_formatting_function($function_settings);
				
				if(isset($special_row_output['top']) && is_array($special_row_output['top'])){
					
					//Insert Blank Row
					if(isset($special_row_output['blank']) && is_array($special_row_output['blank'])){
						$special_row_output['blank']["DT_RowClass"] = 'displayed-row';
						$output['aaData'][] = $special_row_output['blank'];
						++$count_records;
						
						if(isset($arr[$count_records]))
							$aRow = $arr[$count_records];
						else
							$skip_loop = 0;
					}
					
					$special_row_output['top']["DT_RowClass"] = 'displayed-row'; 
					$output['aaData'][] = $special_row_output['top'];
				}
			}
		}
		
		if($skip_loop){
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if($json_settings['show_details']){
				$row[0] = '';
			
				//$returning_html_data = '<img src="'.$pagepointer.'images/icons/details_open.png" class="datatables-details" >';
				$returning_html_data = '<button href="" data-role="button" data-theme="e" data-icon="arrow-d" data-mini="true" data-iconpos="notext" data-inline="true" class="datatables-details btn '.$future_request.' remove-before-export" title="Click to View Details" jid="'.$aRow['id'].'" ><span class="caret"></span></button>';
			
				$returning_html_data .= '<div style="display:none;"><div id="main-details-table-'.$aRow['id'].'"><table id="the-main-details-table-'.$aRow['id'].'" class="main-details-table table" style="max-width:920px; width:99%;"><tbody>';
				
			}
			
			//for ( $i=0 ; $i<count($aColumns) ; $i++ )
			foreach ( $aColumns as $i => $val_i)
			{
				//Get Field Info
				$field = array(
					'form_field' => '',
					'display_position' => '',
					'field_label' => 'undefined',
				);
				if( isset( $form_label[ $aColumns[$i] ] ) && is_array( $form_label[ $aColumns[$i] ] ) ){
					$field = $form_label[ $aColumns[$i] ];
				}
				
				$show_field = false;
				
				switch($aColumns[$i]){
				case 'created_by':
				case 'modified_by':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'select';
					$field[ 'form_field_options' ] = 'get_users_names';
                    
					$field[ 'form_field' ] = 'calculated';
                    $field[ 'calculations' ] = array(
                        'type' => 'site-user-id',
                        'form_field' => 'text',
                        'variables' => array( array( $aColumns[$i] ) ),
                    );
				break;
				case 'creation_date':
				case 'modification_date':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'date';
				break;
				}
				
				switch($aColumns[$i]){
				case "id":
					//CHECK WHETHER OR NOT TO DISPLAY SERIAL NUMBER
					if($json_settings['show_serial_number']){
						$row[] = '<b id="'.$aRow['id'].'" class="datatables-record-id" style="font-size:0.8em; ">'.++$sn.'</b>';
					}
                    $returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
                        $returning_html_data .= '<td class="details-section-container-label" width="30%">ID';
                        $returning_html_data .= '</td>';
                        $returning_html_data .= '<td class="details-section-container-value">';
                            $returning_html_data .= $aRow['id'];
                        $returning_html_data .= '</td>';
                    $returning_html_data .= '</tr>';
				break;
				case "record_status":
				case "ip_address":
				break;
				default:
					
					//Check to skip field
					if( ( ( isset( $field['display_position'] ) && ( $field['display_position'] == 'more-details' || $field['display_position'] != 'do-not-display-in-table' || ( $field['display_position'] == 'display-in-admin-table' && isset($admin_user) ) ) ) && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ) || $show_field ){
						//START - CHECK WHETHER OR NOT TO DISPLAY DETAILS
						if($json_settings['show_details']){
							
							
							//Display Field in Details Section (name_dtX_dtY_dtZ | where Z = 5)
							if(isset( $field['field_label'] ) ){
							$returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
								$returning_html_data .= '<td class="details-section-container-label" width="30%">';
										$returning_html_data .= $field['field_label'];
								$returning_html_data .= '</td>';
								
								$returning_html_data .= '<td class="details-section-container-value">';
								
								//Check for Combo Box Interpretation
								switch($field['form_field']){
								case 'select':
									//Get options function name
									if( isset( $field['form_field_options'] ) ){
										$function_to_call = $field['form_field_options'];
                                        switch($function_to_call){
                                        case "get_states_in_country":
                                            if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                $_SESSION['temp_storage']['selected_country_id'] = $aRow[ $aColumns[$i - 1] ];
                                            }
                                        break;
                                        case "get_cities_in_state":
                                            if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                            }
                                        break;
                                        }
                                            
										$options = $function_to_call();
										
										if(isset($options[$aRow[ $aColumns[$i] ]])){
											$interpreted_option = ucwords($options[$aRow[ $aColumns[$i] ]]);
											$returning_html_data .= $interpreted_option;
										}else{
											if($aRow[ $aColumns[$i] ])
												$returning_html_data .= ucwords($aRow[ $aColumns[$i] ]);
											else
												$returning_html_data .= 'not available';
										}
									}else{
										$returning_html_data .= 'not available';
									}
									
								break;
								case "file":
								case 'text-file':
									//Uploaded Document
									if($aRow[ $aColumns[$i] ]){
										$docs = explode(':::',$aRow[ $aColumns[$i] ]);
										if ( is_array($docs) ){
											foreach($docs as $k_doc => $v_doc){
												if(file_exists($pagepointer.$v_doc)){
													$get_ext[1] = '';
													$get_ext = explode(".",$v_doc);
													
													switch( $get_ext[1] ){
													case "jpg":
													case "jpeg":
													case "png":
													case "gif":
													case "svg":
														if( isset( $skip_page_pointers_for_files ) )
															$returning_html_data .= '<img src="'.$skip_page_pointers_for_files.$v_doc.'" width="120" />';
														else
															$returning_html_data .= '<img src="'.$pagepointer.$v_doc.'" width="120" />';
													break;
													}
													
													if( isset( $skip_page_pointers_for_files ) )
														$returning_html_data .= '<a href="'.$skip_page_pointers_for_files.$v_doc.'"  class="view-file-link-1" target="_blank"  title="Click here to view uploaded files">&rarr; '.ucwords(str_replace('_',' ',$table)).' File '.($k_doc+1).' [ '.$get_ext[1].' ] '.number_format((filesize($pagepointer.$v_doc)/(1024*1024)),2).' MB</a>';
													else
														$returning_html_data .= '<a href="'.$pagepointer.$v_doc.'"  class="view-file-link-1" target="_blank"  title="Click here to view uploaded files">&rarr; '.ucwords(str_replace('_',' ',$table)).' File '.($k_doc+1).' [ '.$get_ext[1].' ] '.number_format((filesize($pagepointer.$v_doc)/(1024*1024)),2).' MB</a>';
													
												}
											}
										}
									}else{
										$returning_html_data .= 'not available';
									}
								break;
								case "multi-select":
									//Get Options For Multiple Select Box
									if( isset( $field['form_field_options'] ) ){
										$function_to_call = $field['form_field_options'];
										
										if( function_exists($function_to_call) ){
                                            switch($function_to_call){
                                            case "get_cities_in_state_pay_on_delivery":
                                                if( isset( $aRow[ $aColumns[$i - 2] ] ) && $aRow[ $aColumns[$i - 2] ] ){
                                                    $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 2] ];
                                                }
                                            break;
                                            case "get_cities_in_state":
                                                if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                                    $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                                }
                                            break;
                                            }
											$options = $function_to_call();
											
											$fun = explode(':::',$aRow[$aColumns[$i]]);
											foreach($fun as $f){
												if($f && isset( $options[$f] ) ){
													if($func)$func .= '<br />'.ucwords( $options[$f] );
													else $func = ucwords( $options[$f] );
												}
											}
											$returning_html_data .= $func;
											
										}else{
											$returning_html_data .= 'not available';
										}
									}else{
										$returning_html_data .= 'not available';
									}
                                    
								break;
								case 'date':
                                case 'date-5':
									//Format date
									$returning_html_data .= date("d-M-Y", doubleval($aRow[ $aColumns[$i] ]) );
								break;
								case 'number':
								case 'decimal':
									//Format Numbers
									$returning_html_data .= format_and_convert_numbers($aRow[ $aColumns[$i] ],3);
								break;
								case 'currency':
									/* Format Currency */
                                    $a = $aRow[ $aColumns[$i] ];
                                    if( isset( $field['default_currency_field'] ) && isset( $aRow[ $field['default_currency_field'] ] ) && $aRow[ $field['default_currency_field'] ] && $aRow[ $field['default_currency_field'] ] != 'undefined' ){
                                        $direction = 'from ' . trim( $aRow[ $field['default_currency_field'] ] );
                                        $a = convert_currency( $aRow[ $aColumns[$i] ] , $direction , 1 );
                                    }
									$returning_html_data .= convert_currency( $a );
								break;
                                case 'calculated':
                                    
                                    if( $aRow[ $aColumns[$i] ] == 'null' )
                                        $returning_html_data .=  '&nbsp;';
                                    else
                                        $returning_html_data .= evaluate_calculated_value( 
                                            array(
                                                'add_class' => $aColumns[$i],
                                                'row_data' => $aRow,
                                                'form_field_data' => $field,
                                            ) 
                                        );
                                break;
                                case 'textarea':
                                case 'textarea-unlimited':
                                    $returning_html_data .= $aRow[ $aColumns[$i] ];
                                break;
								default:
									/* General output */
									if($aRow[ $aColumns[$i] ])
										$returning_html_data .= ucwords($aRow[ $aColumns[$i] ]);
									else
										$returning_html_data .= 'not available';
                                    
								break;
								}
							$returning_html_data .= '</td>';
							
							$returning_html_data .= '</tr>';
							}
							
						}//END - CHECK WHETHER OR NOT TO DISPLAY DETAILS
					
					}
					
					if( $field['display_position'] == 'do-not-display-in-table'  ){
						//Do not display field at all
					}
					
					if( $field['display_position'] != 'more-details' && $field['display_position'] != 'do-not-display-in-table' && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ){
					/***************************************************/
					/***************************************************/
					/***************************************************/
						$cell_data = '';
						$real_cell_data = '';
						
						//Check for Combo Box Interpretation
						switch($field['form_field']){
						case 'select':
							//Get options function name
							$real_cell_data = $aRow[ $aColumns[$i] ];
							
							if( isset( $field['form_field_options'] ) ){
								$function_to_call = $field['form_field_options'];
                                
                                switch($function_to_call){
                                case "get_states_in_country":
                                    if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                        $_SESSION['temp_storage']['selected_country_id'] = $aRow[ $aColumns[$i - 1] ];
                                    }
                                break;
                                case "get_cities_in_state":
                                    if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                        $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                    }
                                break;
                                }
                                
								$options = $function_to_call();
								
								if( isset($options[$aRow[ $aColumns[$i] ]]) ){
									$interpreted_option = ucwords($options[$aRow[ $aColumns[$i] ]]);
									$cell_data = $interpreted_option;
								}else{
									if($aRow[ $aColumns[$i] ])
										$cell_data = ucwords($aRow[ $aColumns[$i] ]);
									else
										$cell_data = 'not available';
								}
							}else{
								$cell_data = 'not available';
							}
                            include "quick_details_field.php";
						break;
						case 'multi-select':
							//Get Options For Multiple Select Box
							if( isset( $field['form_field_options'] ) ){
								$function_to_call = $field['form_field_options'];
								
								if( function_exists($function_to_call) ){
									switch($function_to_call){
                                    case "get_cities_in_state_pay_on_delivery":
                                        if( isset( $aRow[ $aColumns[$i - 2] ] ) && $aRow[ $aColumns[$i - 2] ] ){
                                            $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 2] ];
                                        }
                                    break;
                                    case "get_cities_in_state":
                                        if( isset( $aRow[ $aColumns[$i - 1] ] ) && $aRow[ $aColumns[$i - 1] ] ){
                                            $_SESSION['temp_storage']['selected_state_id'] = $aRow[ $aColumns[$i - 1] ];
                                        }
                                    break;
                                    }
                                    $options = $function_to_call();
									
									$fun = explode(':::',$aRow[$aColumns[$i]]);
									$func = '';
									foreach($fun as $f){
										if($f && isset( $options[$f] ) ){
											if($func)$func .= '<br />'.ucwords( $options[$f] );
											else $func = ucwords( $options[$f] );
										}
									}
									$cell_data = $func;
									
								}else{
									$cell_data = 'not available';
								}
							}else{
								$cell_data = 'not available';
							}
							
						break;
						case 'file':
						case 'text-file':
							//Uploaded Document
							$files = '';
							if($aRow[ $aColumns[$i] ]){
								$docs = explode(':::',$aRow[ $aColumns[$i] ]);
								if ( is_array($docs) ){
									foreach($docs as $k_doc => $v_doc){
										if(file_exists($pagepointer.$v_doc)){
											$get_ext[1] = '';
											$get_ext = explode(".",$v_doc);
											
											switch( $get_ext[1] ){
											case "jpg":
											case "jpeg":
											case "png":
											case "gif":
											case "svg":
												if( isset( $skip_page_pointers_for_files ) )
													$files .= '<img src="'.$skip_page_pointers_for_files.$v_doc.'" width="120" />';
												else
													$files .= '<img src="'.$pagepointer.$v_doc.'" width="120" />';
											break;
											}
											
											if( isset( $skip_page_pointers_for_files ) )
												$files .= '<a href="'.$skip_page_pointers_for_files.$v_doc.'"  class="view-file-link-1" target="_blank"  title="Click here to view uploaded files">&rarr; '.ucwords(str_replace('_',' ',$table)).' File '.($k_doc+1).' [ '.$get_ext[1].' ] '.number_format((filesize($pagepointer.$v_doc)/(1024*1024)),2).' MB</a>';
											else
												$files .= '<a href="'.$pagepointer.$v_doc.'"  class="view-file-link-1" target="_blank"  title="Click here to view uploaded files">&rarr; '.ucwords(str_replace('_',' ',$table)).' File '.($k_doc+1).' [ '.$get_ext[1].' ] '.number_format((filesize($pagepointer.$v_doc)/(1024*1024)),2).' MB</a>';
										}
									}
								}
							}else{
								$files .= 'not available';
							}
							$cell_data = $files;
						break;
						case 'date':
						case 'date-5':
							//Format date
							$cell_data = date("d-M-Y", doubleval( $aRow[ $aColumns[$i] ] ) );
						break;
						case 'number':
							//Format Numbers
							$cell_data =  format_and_convert_numbers( $aRow[ $aColumns[$i] ] , 4 );
						break;
						case 'decimal':
							//Format Decimals
							if( $aRow[ $aColumns[$i] ] == 'null' )
								$cell_data =  '&nbsp;';
							else
								$cell_data =  format_and_convert_numbers( $aRow[ $aColumns[$i] ] , 4 );
						break;
						case 'currency':
							/* Format Currency */
                            $a = $aRow[ $aColumns[$i] ];
                            if( isset( $field['default_currency_field'] ) && isset( $aRow[ $field['default_currency_field'] ] ) && $aRow[ $field['default_currency_field'] ] && $aRow[ $field['default_currency_field'] ] != 'undefined' ){
                                $direction = 'from ' . trim( $aRow[ $field['default_currency_field'] ] );
                                $a = convert_currency( $aRow[ $aColumns[$i] ] , $direction , 1 );
                            }
							$cell_data = convert_currency( $a );
						break;
						case 'calculated':
							
							if( $aRow[ $aColumns[$i] ] == 'null' )
								$cell_data =  '&nbsp;';
							else
								$cell_data = evaluate_calculated_value( 
									array(
										'add_class' => $aColumns[$i],
										'row_data' => $aRow,
										'form_field_data' => $field,
									) 
								);
                                
                            include "quick_details_field.php";
						break;
                        case 'textarea':
                        case 'textarea-unlimited':
                            $cell_data = $aRow[ $aColumns[$i] ];
                        break;
						default:
							/* General output */
							//if($aRow[ $aColumns[$i] ])
								$cell_data = ucwords($aRow[ $aColumns[$i] ]);
                                include "quick_details_field.php";
							//else
								//$cell_data = 'not available';
						break;
						}
						
						if( $field['form_field'] == 'calculated' ){
							$row[] = $cell_data;
						}else{
							$row[] = $cell_data . '<input type="hidden" id="'.$aRow['id'].'-'.$aColumns[$i].'" value="'.$aColumns[$i].'" class="datatables-cell-id" jid="'.$aRow['id'].'" real-value="'.$real_cell_data.'" />';
						}
					}
				break;
				}
			}
			
			
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if($json_settings['show_details']){
				//Display Further Details
				if(isset($json_settings['show_details_more']) && $json_settings['show_details_more'] && isset($json_settings['special_details_functions']) && is_array($json_settings['special_details_functions'])){
					foreach($json_settings['special_details_functions'] as $function_name_to_call){
						$more_details = $function_name_to_call."_more_details";
						if(function_exists($more_details)){
							$returning_html_data .= '<tr>';
								$returning_html_data .= '<td colspan="2">';
									$returning_html_data .= $more_details( $aRow , $database_name , $database_connection , $function_name_to_call , $pagepointer );
								$returning_html_data .= '</td>';
							$returning_html_data .= '</tr>';
						}
					}
				}
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				$returning_html_data .= '</div>';
				$returning_html_data .= '</div>';
				
				$row[0] = $returning_html_data;
			}
			
			//Modify Row Data Prior to Displaying it
			if(isset($json_settings['special_table_formatting_modify_row_data']) && $json_settings['special_table_formatting_modify_row_data']){
				$special_table_formatting_function = $json_settings['special_table_formatting_modify_row_data']."_function";
			
				if(function_exists($special_table_formatting_function)){
					//Set data to be sent
					$function_settings = array(
						'row_dt'=>$row,
						'original_row_dt'=>$aRow,
						'previous_sum'=>$special_summed_values,
						'summed_values_units'=>$summed_values_units,
					);
					
					$special_row_output = $special_table_formatting_function($function_settings);
					
					if(isset($special_row_output['row_dt']) && is_array($special_row_output['row_dt'])){
						$row = $special_row_output['row_dt'];
					}
				}
			}
			
			//Summation Row Data
			if(isset($json_settings['special_table_formatting_summation_row']) && $json_settings['special_table_formatting_summation_row']){
				$special_table_formatting_function = $json_settings['special_table_formatting_summation_row']."_function";
			
				if(function_exists($special_table_formatting_function)){
					//Check For Previous Row
					if(isset($arr[$count_records-1])){
						//Previous Row
						$prRow = $arr[$count_records-1];
					}else{
						$prRow = 0;
					}
					
					//Set data to be sent
					$function_settings = array(
						'row_dt'=>$row,
						'original_row_dt'=>$aRow,
						'previous_row_dt'=>$prRow,
						'visible_columns'=>$json_settings['special_table_formatting_visible_columns'],
						'previous_sum'=>$special_summed_values,
						'summed_values_units'=>$summed_values_units,
					);
					
					$special_row_output = $special_table_formatting_function($function_settings);
					
					if(isset($special_row_output['bottom']) && is_array($special_row_output['bottom'])){
						foreach($special_row_output['bottom'] as $returned_row){
							$returned_row["DT_RowClass"] = 'displayed-row';
							$output['aaData'][] = $returned_row;
							
							//Insert Blank Row
							if(isset($special_row_output['blank']) && is_array($special_row_output['blank'])){
								$special_row_output['blank']["DT_RowClass"] = 'displayed-row';
								$output['aaData'][] = $special_row_output['blank'];
							}
						}
					}
					
					//Get Summed Values
					if(isset($special_row_output['summed_values']) && is_array($special_row_output['summed_values'])){
						$special_summed_values = $special_row_output['summed_values'];
					}
					
				}
			}
			
			$row["DT_RowClass"] = 'displayed-row';
			if( $aRow['id'] == '10' || ( isset( $aRow[ 'ip_address' ] ) && $aRow[ 'ip_address' ] == 'total-row' ) ){
				$row["DT_RowClass"] = 'total-row displayed-row';
			}
			if( $table == 'product' && ! ( $aRow['product018'] + ( $aRow['product019']*3600 ) > __date() && $aRow['product018'] + 1 < __date() ) ){
                //&& $aRow['product'] == '10' || ( isset( $aRow[ 'ip_address' ] ) && $aRow[ 'ip_address' ] == 'total-row' )
				$row["DT_RowClass"] = 'expired-product';
			}
			if( $table == 'product' && $aRow['product026'] != 'active' ){
				$row["DT_RowClass"] = 'expired-product';
			}
			$output['aaData'][] = $row;
			
			//Summation Row Data - LAST RECORD
			if(isset($json_settings['special_table_formatting_summation_row']) && $json_settings['special_table_formatting_summation_row']){
				$special_table_formatting_function = $json_settings['special_table_formatting_summation_row']."_function";
			
				if(function_exists($special_table_formatting_function)){
				
					//Check For Last Record
					if(($count_records+1) == count($arr) && isset($arr[$count_records])){
						//Bottom Row
						$zRow = $arr[$count_records];
						
						$function_settings = array(
							'row_dt'=>$row,
							'original_row_dt'=>$zRow,
							'previous_row_dt'=>$aRow,
							'visible_columns'=>$json_settings['special_table_formatting_visible_columns'],
							'previous_sum'=>$special_summed_values,
							'summed_values_units'=>$summed_values_units,
							'last_record'=>1,
						);
						
						$special_row_output = $special_table_formatting_function($function_settings);
						
						if(isset($special_row_output['bottom']) && is_array($special_row_output['bottom'])){
							foreach($special_row_output['bottom'] as $returned_row){
								$returned_row["DT_RowClass"] = 'displayed-row';
								$output['aaData'][] = $returned_row;
								
								//Insert Blank Row
								if(isset($special_row_output['blank']) && is_array($special_row_output['blank'])){
									$special_row_output['blank']["DT_RowClass"] = 'displayed-row';
									$output['aaData'][] = $special_row_output['blank'];
								}
							}
						}
					}
				}
			}
		}
		
		//Check if Special Table Styling & Data Population is turned on
		if(isset($json_settings['special_table_formatting_bottom_row']) && $json_settings['special_table_formatting_bottom_row']){
			$special_table_formatting_function = $json_settings['special_table_formatting_bottom_row']."_function";
			
			if(function_exists($special_table_formatting_function)){
				//Set data to be sent
				if(isset($arr[$count_records+1])){
					//Bottom Row
					$zRow = $arr[$count_records+1];
					
					$function_settings = array(
						'row_dt'=>$zRow,
						'original_row_dt'=>$zRow,
						'record_id'=>$record_id,
						'visible_columns'=>$json_settings['special_table_formatting_visible_columns'],
						'summed_values'=>$sub_summed_values,
						'summed_values_units'=>$summed_values_units,
					);
					
					$special_row_output = $special_table_formatting_function($function_settings);
					
				}
				
				if(($count_records+1) == count($arr) && isset($arr[$count_records])){
					//Bottom Row
					$zRow = $arr[$count_records];
					
					$function_settings = array(
						'row_dt'=>$zRow,
						'original_row_dt'=>$aRow,
						'record_id'=>$record_id,
						'visible_columns'=>$json_settings['special_table_formatting_visible_columns'],
						'summed_values'=>$sub_summed_values,
						'summed_values_units'=>$summed_values_units,
						'total_summed_values'=>$summed_values,
						'last_record'=>1,
					);
					
					$special_row_output = $special_table_formatting_function($function_settings);
				}
				
				
				if(isset($special_row_output['bottom']) && is_array($special_row_output['bottom'])){
					foreach($special_row_output['bottom'] as $returned_row){
						$returned_row["DT_RowClass"] = 'displayed-row';
						$output['aaData'][] = $returned_row;
						
						//Insert Blank Row
						if(isset($special_row_output['blank']) && is_array($special_row_output['blank'])){
							$special_row_output['blank']["DT_RowClass"] = 'displayed-row';
							$output['aaData'][] = $special_row_output['blank'];
						}
					}
					//$output['aaData'][] = $special_row_output['bottom'];
					
					//Reset Summed Values
					$sub_summed_values = array();
				}
			}
		}
	}
?>