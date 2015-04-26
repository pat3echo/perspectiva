<?php
	/**
	 * Update Payment Status Class
	 *
	 * @used in  				Success | Cancel | Pending | Failed Pages
	 * @created  				15:29 | 05-01-2013
	 * @database table name   	payment
	 */

	/*
	|--------------------------------------------------------------------------
	| Update the payment status for orders placed by users
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cPayment_handler{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'payment_handler';
		private $order_table = 'order';
		
		function payment_handler(){
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
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'payment_process':
            break;
			case 'check_payment_status':
				$returned_value = $this->_check_payment_status();
			break;
			}
			
			return $returned_value;
		}
		
		private function _check_payment_status(){
			
            //1. CHECK IF USER IS LOGGED IN
			if( isset( $this->class_settings[ 'user_id' ] ) && $this->class_settings[ 'user_id' ] && isset( $this->class_settings[ 'user_email' ] ) && $this->class_settings[ 'user_email' ] ){
                
                $payment_gateway = '';
                if(isset($_GET['PaymentGateway']))
                    $payment_gateway = $_GET['PaymentGateway'];
                
                if( ! $payment_gateway ){
                    //RETURN INVALID PAYMENT GATEWAY
                    $err = new cError(000025);
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = PAYMENT_HANDLER_MISSING_PAYMENT_GATEWAY_MSG;
					return $err->error();
                }
                $this->class_settings['payment_gateway'] = $payment_gateway;
                
                switch( $this->class_settings['payment_gateway'] ){
                case 'firstbank':
                
                    $firstbank_order_id = '';
                    if(isset($_GET['OrderID']))
                        $firstbank_order_id = $_GET['OrderID'];
                    
                    $firstbank_transaction_reference = '';
                    if(isset($_GET['TransactionReference']))
                        $firstbank_transaction_reference = $_GET['TransactionReference'];
                    
                    if( ! $firstbank_order_id ){
                        //RETURN INVALID ORDER ID ERROR
                        $err = new cError(000019);
                        $err->action_to_perform = 'notify';
                        $err->html_format = 2;
                        
                        $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                        $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                        $err->additional_details_of_error = PAYMENT_HANDLER_INVALID_ORDER_ID_ERROR_MSG;
                        return $err->error();
                    }
                    
                    $firstbank_data = get_first_bank_data();
                    
                    //GET Transaction Status from First Bank
                    $fileContents = file_get_contents( $firstbank_data['payment_status_url'] . $firstbank_order_id );
                    /*
                    $fileContents = '<?xml version="1.0" encoding="utf-8" ?><UPay><MerchantID> 1389211453_104 </MerchantID><OrderID> 6200412161 </OrderID><Status> status </Status><StatusCode>00 </StatusCode><Amount> 17 </Amount><Date>date</Date><TransactionRef> transactionRef </TransactionRef><PaymentRef> 38409965z1 </PaymentRef><PaymentGateway> paymentGateway </PaymentGateway><ResponseCode> responseCode </ResponseCode><ResponseDescription> responseDescription</ResponseDescription><CurrencyCode> currencyCode </CurrencyCode></UPay>';
                    */
                    
                    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

                    $fileContents = trim( str_replace('"', "'", $fileContents) );

                    $simpleXml = simplexml_load_string( $fileContents );

                    $json = json_decode( json_encode($simpleXml) , true );
                    
                    if( !( is_array( $json ) && isset( $json['OrderID'] ) && $json['OrderID'] && isset( $json['StatusCode'] ) && $json['StatusCode'] ) ){
                        //RETURN INVALID ORDER DATA FROM PAYMENT GATEWAY ERROR
                        $err = new cError(000019);
                        $err->action_to_perform = 'notify';
                        $err->html_format = 2;
                        
                        $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                        $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                        $err->additional_details_of_error = PAYMENT_HANDLER_INVALID_ORDER_DATA_FROM_PAYMENT_GATEWAY_ERROR_MSG;
                        $err->additional_details_of_error .= PAYMENT_HANDLER_ORDER_ID_LABEL.': <b>'.$firstbank_order_id.'</b><br />';
                        $err->additional_details_of_error .= PAYMENT_HANDLER_PAYMENT_GATEWAY_LABEL.': <b>'.$payment_gateway.'</b><br />';
                        return $err->error();
                    }
                    
                    foreach($json as $k => & $v){
                        if( ! is_array( $v ) )$v = trim($v);
                    }
                    
                    $this->class_settings['payment_data'] = $json;
                    
                    //check payment status
                    $this->class_settings['order_state'] = $this->_firstbank_response_data_transformation();
                    
                break;
                }
                
                if( isset( $this->class_settings['order_state'] ) && $this->class_settings['order_state'] && isset( $this->class_settings['payment_data']['OrderID'] ) && $this->class_settings['payment_data']['OrderID'] && isset( $this->class_settings['payment_data']['TransactionRef'] ) && $this->class_settings['payment_data']['TransactionRef'] ){
                    
                    $order = new cOrder();
                    $order->class_settings = $this->class_settings;
                    $order->class_settings[ 'action_to_perform' ] = 'update_order_status';
                    return $order->order();
                }
				//RETURN NO PAYMENT DATA ERROR
				
				
			}else{
				//2. REDIRECT TO LOGIN PAGE
				$redirection_page = 'login';
				header( 'location: ' . $this->class_settings[ 'calling_page' ] . $redirection_page );
				exit;
			}
			
		}
		
		private function _firstbank_response_data_transformation(){
			
			$new_state = '';
			
			//CHECK FOR SUCCESSFUL PAYMENT
			if( isset( $this->class_settings[ 'payment_data' ]['StatusCode'] ) ){
				switch( $this->class_settings[ 'payment_data' ]['StatusCode'] ){
				case "00":
					/*-----------------*/
					//Successful Payment
					/*-----------------*/
					
                    return 'paid';
				break;
				case "02":
					//$notify->action = 'payment_pending' and move to account panel
					$new_state = 'pending_payment';
				break;
				case "01":
					//Failed Payment
					$new_state = 'failed';
                break;
				case "03":
                    $new_state = 'payment_cancelled';
                break;
				case "04":
                    $new_state = 'payment_not_processed';
                break;
				case "05":
					$new_state = 'payment_invalid_merchant';
                break;
				case "06":
					$new_state = 'payment_inactive_merchant';
                break;
				case "07":
					$new_state = 'payment_invalid_order_id';
                break;
				case "08":
                    $new_state = 'payment_duplicate_order_id';
                break;
				case "09":
					$new_state = 'payment_invalid_amount';
				break;
				}
				
			}
			
			return $new_state;
		}
		
	}
?>