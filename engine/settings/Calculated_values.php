<?php 
	/**
	 * Calculated Values
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Calculated Values
	|--------------------------------------------------------------------------
	|
	| Functions that define which functions to use in populating select combo 
	| boxes during form generation and dataTables population
	|
	*/
	function evaluate_calculated_value( $settings = array() ){
		$return = '-';
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ] ) && isset( $settings[ 'row_data' ] ) && is_array( $settings[ 'row_data' ] ) ){
			
			$var1 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 0 ];
			
            $extra = '';
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
            case 'coupon-code':
                if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
                    return $settings[ 'row_data' ][ $var1 ].'<br />{Coupon Code: <b>'.$settings[ 'row_data' ]['id'].'</b>}';
                }
            break;
			case 'auction-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $a = get_auctioned_product_details( array( 'id' => $id ) );
                    
                    unset( $settings[ 'row_data' ][ $var1 ] );
                    if( isset( $a[ $id ]['product'] ) && $a[ $id ]['product'] ){
                        $settings[ 'row_data' ][ $var1 ] = $a[ $id ]['product'];
                        $extra = '<br /><small>'.$a[ $id ]['product'].'</small>';
                    }
				}
            case 'product-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $cached_values = get_product_details( array( 'id' => $id ) );
                    
                    if( isset( $cached_values[$id]['product_name'] ) ){
                        return '<a href="?page=product-details&data='.$id.'" target="_blank" class="back-end-preview" title="'.$cached_values[$id]['product_name'].'">'.$cached_values[$id]['product_name'].'</a>'.$extra;
                    }
				}
			break;
            case 'store-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $cached_values = get_store_details( array( 'id' => $id ) );
                    
                    if( isset( $cached_values['store_name'] ) ){
                        return '<a href="?page=store-page&data='.$id.'" target="_blank" class="back-end-preview" title="'.$cached_values['store_name'].'">'.$cached_values['store_name'].'</a>'.$extra;
                    }
				}
			break;
            case 'site-user-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $cached_values = get_site_user_details( array( 'id' => $id ) );
                    
                    if( isset( $cached_values['email'] ) ){
                        return $cached_values['email'].$extra;
                    }
				}
			break;
            case 'support-enquiry-id':
                //return $settings[ 'row_data' ][ $var1 ];
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    $cached_values = get_support_enquiry_details( array( 'id' => $id ) );
                    
                    if( isset( $cached_values['ask_us_anything'] ) ){
                        $a = '';
                        if( isset( $cached_values['creation_date'] ) && $cached_values['creation_date'] ){
                            $a = '<strong>Created On: '.date("d-M-Y H:i", doubleval( $cached_values['creation_date'] ) ).'</strong><br />';
                        }
                        return $cached_values['ask_us_anything'];
                    }
				}
			break;
            case 'state-id':
                $var2 = '';
                if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
					$sid = $settings[ 'row_data' ][ $var2 ];
                    
                    return get_state_name( array( 'country_id' => $id, 'state_id' => $sid ) );
				}
			break;
			case 'difference':
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$return = format_and_convert_numbers( $settings[ 'row_data' ][ $var1 ] , 3 );
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ][ 0 ] ) ){
					$var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ][ 0 ];
					
					if( isset( $settings[ 'row_data' ][ $var2 ] ) ){
						$return = $return - format_and_convert_numbers( $settings[ 'row_data' ][ $var2 ] , 3 );
					}
				}	
				
			break;
			case 'value-from-cache':
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'cache_key' ] ) ){
					$cache_key = $settings[ 'form_field_data' ][ 'calculations' ][ 'cache_key' ];
					
					if( $settings[ 'row_data' ][ 'id' ] == '10' ){
						switch( $cache_key ){
						case 'obtained-budgets':
						case 'obtained-cash-calls-year-to-date':
							return format_and_convert_numbers( $settings[ 'row_data' ][ $settings[ 'add_class' ] ] , 4 );
						break;
						}
					}
					
					$cached_values = get_cache_for_special_values( array( 'cache_key' => $cache_key ) );
					
					if( is_array( $cached_values ) ){
						$eval_cached_values = $cached_values;
						
						foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $var ){
							
							$array_key = $var[0];
							
							if( isset( $settings[ 'row_data' ][ $var[0] ] ) ){
								
								$array_key = $settings[ 'row_data' ][ $var[0] ];
								
								if( isset( $var[ 'php_functions' ] ) && is_array( $var[ 'php_functions' ] ) ){
									foreach( $var[ 'php_functions' ] as $php_function ){
										switch( $php_function ){
										case 'trim':
											$array_key = trim( $array_key );
										break;
										case 'md5':
											$array_key = md5( $array_key );
										break;
										}
									}
								}
								
							}
							
							if( isset( $eval_cached_values[ $array_key ] ) ){
								$eval_cached_values = $eval_cached_values[ $array_key ];
							}
						}
						
						if( ! is_array( $eval_cached_values ) ){
							$return = $eval_cached_values;
						}
					}
				}
			break;
			default:
				$return = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ];
			break;
			}
			
		}
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ) ){
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ){
			case 'decimal':
				$return = format_and_convert_numbers( $return , 4 );
			break;
			case 'currency':
                $a = $return;
                
                if( isset( $settings[ 'form_field_data' ]['default_currency_field'] ) && isset( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] ) && $settings['row_data'][  $settings[ 'form_field_data' ]['default_currency_field'] ] && $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] != 'undefined' ){
                    $direction = 'from ' . trim( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] );
                    $a = convert_currency( $return , $direction , 1 );
                }
				$return = convert_currency( $a );
			break;
			}
		}
		
		if( isset( $cache_key ) && $cache_key && isset( $settings[ 'add_class' ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ]['added_class'] ) ){
			switch( $cache_key ){
			case 'obtained-budgets':
				if( ! doubleval( $return ) ){
					$return .= $settings[ 'form_field_data' ][ 'calculations' ]['added_class'];
				}
			break;
			}
		}
		return $return;
	}
	
	function obtain_budgets_from_budget_details_table( $settings = array() ){
		$budgets = array();
		
		$cache_key = 'obtained-budgets';
		
		//use cache values or update lot
		if( ! ( isset( $settings[ 'reset' ] ) && $settings[ 'reset' ] ) ){
			$cached_budgets = get_cache_for_special_values( array( 'cache_key' => $cache_key ) );
			
			if( is_array( $cached_budgets ) && ! empty( $cached_budgets ) ){
				return 1;
			}
		}
		
		$budget_table = 'budget_details';
		if( isset( $settings[ 'database_connection' ] ) && isset( $settings[ 'database_name' ] ) && $settings[ 'database_name' ] && $settings[ 'database_connection' ] ){
			$query = "SELECT * FROM `".$settings[ 'database_name' ]."`.`".$budget_table."` WHERE `record_status`='1'";
			$query_settings = array(
				'database'=> $settings[ 'database_name' ],
				'connect'=> $settings[ 'database_connection' ],
				'query'=> $query,
				'query_type'=>'SELECT',
				'set_memcache'=>1,
				'tables'=>array($budget_table),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( isset($sql_result) && is_array($sql_result) ){
				
				foreach( $sql_result as $s_val ){
					$budgets[ $s_val['budget_details001'] ][ md5( trim( $s_val[ 'budget_details002' ] ) ) ] = array(
						'approved' => array(
							'ngn' => $s_val[ 'budget_details006' ],
							'usd' => $s_val[ 'budget_details007' ],
						),
					);
				}
				
				//cache in files
				set_cache_for_special_values( array(
					'cache_key' => $cache_key,
					'cache_values' => $budgets,
				) );
			}
		}
		
	}
	
	function obtain_cash_calls_year_to_date_values( $settings = array() ){
		$budgets = array();
		
		$cache_key = 'obtained-cash-calls-year-to-date';
		
		//use cache values or update lot
		/*
		if( ! ( isset( $settings[ 'reset' ] ) && $settings[ 'reset' ] ) ){
			$cached_budgets = get_cache_for_special_values( array( 'cache_key' => $cache_key ) );
			
			if( is_array( $cached_budgets ) && ! empty( $cached_budgets ) ){
				return 1;
			}
		}
		*/
		
		$budget_table = 'cash_calls';
		
		if( isset( $_SESSION[ 'filter' ][ $budget_table ][ 'budget_id' ] ) ){
			$budget_id = $_SESSION[ 'filter' ][ $budget_table ][ 'budget_id' ];
			
			if( isset( $settings[ 'database_connection' ] ) && isset( $settings[ 'database_name' ] ) && $settings[ 'database_name' ] && $settings[ 'database_connection' ] ){
				$query = "SELECT `cash_calls001`, `cash_calls002`, SUM(`cash_calls012`), SUM(`cash_calls013`), SUM(`cash_calls016`), SUM(`cash_calls017`) FROM `".$settings[ 'database_name' ]."`.`".$budget_table."` WHERE `record_status`='1' AND `cash_calls001`='".$budget_id."' GROUP BY `cash_calls002`";
				$query_settings = array(
					'database'=> $settings[ 'database_name' ],
					'connect'=> $settings[ 'database_connection' ],
					'query'=> $query,
					'query_type'=>'SELECT',
					'set_memcache'=>1,
					'tables'=>array($budget_table),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( isset($sql_result) && is_array($sql_result) ){
					
					foreach( $sql_result as $s_val ){
						$budgets[ $s_val['cash_calls001'] ][ md5( trim( $s_val[ 'cash_calls002' ] ) ) ] = array(
							'proposed' => array(
								'ngn' => $s_val[ 'SUM(`cash_calls012`)' ],
								'usd' => $s_val[ 'SUM(`cash_calls013`)' ],
							),
							'recommended' => array(
								'ngn' => $s_val[ 'SUM(`cash_calls016`)' ],
								'usd' => $s_val[ 'SUM(`cash_calls017`)' ],
							),
						);
					}
					
					//cache in files
					set_cache_for_special_values( array(
						'cache_key' => $cache_key,
						'cache_values' => $budgets,
					) );
				}
			}
		}
	}
	
	//Returns formatted value that would be displayed for each record of the monthly cash calls table
	function prepare_line_items_for_row_data( $settings = array() ){
		$dataset = array();
		
		$modified_dataset = array();
		
		$code_properties = array();
		
		$table = $settings[ 'table' ];
		$func = $settings[ 'table' ];
		
		if(function_exists($func))
			$form_label = $func();
		else
			$form_label = array();
		
		if( isset( $settings[ 'dataset' ] ) && is_array( $settings[ 'dataset' ] ) && isset( $settings[ 'code_field' ] ) && $settings[ 'code_field' ] ){
			$dataset = $settings[ 'dataset' ];
			
			$insert_total_row = false;
			
			$previous_group_parent = '';
			
			$total_row_index = array();
			
			foreach( $dataset as $key => & $data ){
				//get code
				$code = $data[ $settings[ 'code_field' ] ];
				
				//break code
				$code_childs = explode( '.', trim( $code ) );
				
				if( isset( $code_childs[0] ) ){
					
					if( ! $code_childs[0] )$code_childs[0] = 'n/a';
					
					$code_base_parent_key = $code_childs[0];
					
					$total_rows = count( $modified_dataset );
					
					if( count( $code_childs ) > 1 ){
						$tmp_codes = $code_childs;
						unset( $tmp_codes[ count( $tmp_codes ) - 1 ] );
						$code_parent_key = implode( $tmp_codes );
						
						if( isset( $code_properties[ 'base_parents' ][ $code_base_parent_key ] ) ){
							
							//$data[ $settings[ 'code_field' ] ] .= '**';
							
							$code_properties[ 'base_parents' ][ $code_base_parent_key ][ 'childs' ][ implode( $code_childs ) ] = array( 'id' => $data[ 'id' ], 'key' => $total_rows );
							
						}else{
							if( isset( $code_properties[ 'base_parents' ][ $code_parent_key ] ) ){
								
								$code_properties[ 'base_parents' ][ $code_parent_key ][ 'childs' ][ implode( $code_childs ) ] = array( 'id' => $data[ 'id' ] , 'key' => $total_rows );
								
								//$data[ $settings[ 'code_field' ] ] .= '**';
								
							}else{
								//base parent code
								$code_properties[ 'base_parents' ][ implode( $code_childs ) ] = array(
									'id' => $data[ 'id' ],
									'key' => $total_rows + 1,
								);
								
								$total_row_index[] = $total_rows;
								
								$data[ $settings[ 'code_field' ] ] .= '*';
								$data[ 'ip_address' ] = 'total-row';
								
								$insert_total_row = true; 
							}
						}
					}else{
						//base parent code
						$code_properties[ 'base_parents' ][ implode( $code_childs ) ] = array(
							'id' => $data[ 'id' ],
							'key' => $total_rows + 1,
						);
						
						$total_row_index[] = $total_rows;
						
						$data[ $settings[ 'code_field' ] ] .= '*';
						$data[ 'ip_address' ] = 'total-row';
						
						$insert_total_row = true;
					}
					
				}
				
				foreach( $data as $k => $v ){
					if( $insert_total_row ){
						switch( $k ){
						case 'id':
						case 'creation_date':
						case 'modification_date':
						case 'created_by':
						case 'modified_by':
						case "record_status":
						case "ip_address":
						case $table.'001':
						case $table.'002':
						case $table.'003':
						break;
						default:
							$data[ $k ] = 'null';
						break;
						}
					}else{
						//evaluate calculated values
						$field = array(
							'form_field' => '',
							'display_position' => '',
							'field_label' => 'undefined',
						);
						if( isset( $form_label[ $k ] ) && is_array( $form_label[ $k ] ) ){
							$field = $form_label[ $k ];
						}
						if( isset( $field['form_field'] ) && $field['form_field'] == 'calculated' ){
							$data[ $k ] = evaluate_calculated_value( 
								array(
									'row_data' => $data,
									'form_field_data' => $field,
								) 
							);
						}
					}
				}
					
				if( $insert_total_row ){
					
					$new_data = $data;
					
					$new_data['id'] = '10';
					$new_data[$table.'002'] = '';
					$new_data[$table.'003'] = '';
					
					$modified_dataset[] = $new_data;
					
					$insert_total_row = false;
				}
				
				$modified_dataset[] = $data;
				
				
			}
			
			if( isset( $data ) && ! empty( $data ) ){
				$new_data = $data;
			
				$new_data['id'] = '10';
				$new_data[$table.'002'] = '';
				$new_data[$table.'003'] = '';
				
				$total_row_index[] = count( $modified_dataset );
				
				$modified_dataset[] = $new_data;
			}
			
			if( isset( $code_properties[ 'base_parents' ] ) ){
				foreach( $code_properties[ 'base_parents' ] as $key => $val ){
					$total_row = ($val[ 'key' ] - 1);
					$index = 0;
					foreach( $total_row_index as $k => $v ){
						if( $total_row == $v ){
							$index = $k + 1;
						}
					}
					
					if( isset( $total_row_index[ $index ] ) ){
						$total_row = $total_row_index[ $index ];
					}
					
					//get children of parent
					$summed_values = array();
					if( isset( $val[ 'childs' ] ) && is_array( $val[ 'childs' ] ) ){
						foreach( $val[ 'childs' ] as $k => $v ){
							
							foreach( $modified_dataset[ $v[ 'key' ] ] as $ki => $vi ){
								switch( $ki ){
								case 'id':
								case 'creation_date':
								case 'modification_date':
								case 'created_by':
								case 'modified_by':
								case "record_status":
								case "ip_address":
								case $table.'001':
								case $table.'002':
								case $table.'003':
								break;
								default:
									if( isset( $summed_values[ $ki ] ) ){
										$summed_values[ $ki ] += format_and_convert_numbers( $vi , 3);
									}else{
										$summed_values[ $ki ] = format_and_convert_numbers( $vi, 3 );
									}
								break;
								}
							}
						}
					}
					
					$modified_dataset[ $total_row ][ $table.'003' ] = $modified_dataset[ $val[ 'key' ] ][ $table.'002' ] . ' TOTAL';
					
					foreach( $modified_dataset[ $total_row ] as $ki => $vi ){
						switch( $ki ){
						case 'id':
						case 'creation_date':
						case 'modification_date':
						case 'created_by':
						case 'modified_by':
						case "record_status":
						case "ip_address":
						case $table.'001':
						case $table.'002':
						case $table.'003':
						break;
						default:
							if( isset( $summed_values[ $ki ] ) ){
								$modified_dataset[ $total_row ][ $ki ] = $summed_values[ $ki ];
							}
						break;
						}
					}
				}
			}
		}
		
		return $modified_dataset;
	}
	
	function recursively_test_array(  ){
		
	}
?>