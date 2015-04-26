<?php
	/**
	 * Emails Sending Class
	 *
	 * @used in  				/classes/*.php
	 * @created  				19:11 | 27-12-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Sends emails to users on execution of various actions
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cEmails{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'emails';
		
		public $mail_certificate = array();
		public $mail_template = '';
		
		public $sender = array();
		
		private $table = 'emails';
		
		public $saved = 0;
		
		public $message_type = 0;
		
		function emails(){
			//INITIALIZE RETURN VALUE
			$return = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'send_mail':
				$return = $this->_send_mail();
			break;
			}
			
			return $return;
		}
		
		private function _send_mail(){
			$h = '';
			$hbc = '';
			
			foreach($this->class_settings['destination']['email'] as $k => $to){
				$message = $this->_template_1($k);
				$subject = $this->class_settings['subject'];
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Additional headers
				$headers .= 'To: '.$this->class_settings['destination']['full_name'][$k].' <'.$to.'>' . "\r\n";
				$headers .= 'From: '.$this->class_settings['project_data']['project_name'].' <'.$this->class_settings['project_data']['email'].'>' . "\r\n";
				$headers .= 'Bcc: '.$this->class_settings['project_data']['admin_email'] . "\r\n";
				
				//Exempt Particular Emails
				switch($this->class_settings['message_type']){
				case 2:		//Account Verification Email after registration
				case 15:	//Admin Login Token
				case 23:	//Send Pick-up Email to Delivery Agent
				case 40:	//Send Pick-up Email to Delivery Agent
				break;
				default:
					//Log Notification
					$this->class_settings['notification_data'] = array(
						'title' => $this->class_settings['subject'],
						'detailed_message' => strip_tags( $message , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><b><strong><i><u><small><i><u><super><sub>' ),
						'send_email' => 'no',
						'notification_type' => 'no_task',
						'target_user_id' => $this->class_settings['user_id'],
						'class_name' => $this->table_name,
						'method_name' => 'send_mail',
					);
					
					$notifications = new cNotifications();
					$notifications->class_settings = $this->class_settings;
					$notifications->class_settings[ 'action_to_perform' ] = 'add_notification';
					$notifications->notifications();
				break;
				}
				send_mail( $this->class_settings[ 'calling_page' ] , $to , $subject , $message , $headers );
			}
		}
	
		private function _template_1($k){
			$h = '';
			
			//if( file_exists( $this->class_settings[ 'calling_page' ] . 'css/email-notification.css' ) ){
				//$h .= '<style>' . read_file( '' , $this->class_settings[ 'calling_page' ] . 'css/email-notification.css' ) . '</style>';
			//}
			
			$h .= '<div id="body-message-content">';
			$h .= '<div id="message-content">';
			//$h .= '<div id="message-content-header">Gas Helix - Notification</div>';
			
			$h .= '<div style="width:auto; padding:10px; font:12px Verdana; color:#333;" id="message-content-body">';
				$h .= '<div>';
				$h .= 'Dear <b>'.$this->class_settings['destination']['full_name'][$k].',</b>';
				$h .= '</div>';
				
				$h .= '<div style="margin-top:30px;">';
				
                if( isset( $this->mail_certificate['body_start'] ) && $this->mail_certificate['body_start'] ){
                    $h .= $this->mail_certificate['body_start'];
                }
                
				$temp_id = 0;
				$extra_data = '';
				
				//Get Email Message Body
				switch($this->class_settings['message_type']){
				case 1:
					//New User Registration Welcome Message
					$temp_id = '1361864988_1';
				break;
				case 2:
					//Account Registration Verification
					$temp_id = '1361866429_1';
					
					//Get verification link
					//$extra_data = '<a href="'.$this->class_settings['project_data']['domain_name_only'].'/register/?data='.md5($this->class_settings['destination']['id'][$k]).'" style="font-size:16px; font-family:verdana, arial; color:#115798; clear:both; display:block; margin:auto; width:auto;">Click Here to verify your account</a>';
					$extra_data = '<a href="'.$this->class_settings['project_data']['domain_name_only'].'/?page=login&data='.md5($this->class_settings['destination']['id'][$k]).'" style="font-size:16px; font-family:verdana, arial; color:#115798; clear:both; display:block; margin:auto; width:auto;">Click Here to verify your account</a>';
				break;
				case 3:
					//Lost Bid Notification
					$temp_id = '1361869930_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 4:
					//Purchase Invoice
					$temp_id = '1361873946_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					if(isset($this->mail_certificate['head']) && is_array($this->mail_certificate['head'])){
						foreach($this->mail_certificate['head'] as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
					}
					$extra_data .= '</tr>';
					
					if(isset($this->mail_certificate['body']) && is_array($this->mail_certificate['body'])){
						foreach($this->mail_certificate['body'] as $arr){
							$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
							foreach($arr as $v){
								$extra_data .= '<td>';
									$extra_data .= ucwords($v);
								$extra_data .= '</td>';
							}
							$extra_data .= '</tr>';
						}
					}
					$extra_data .= '</table><br />';
				break;
				case 5:
					//Bank Account Verification Invoice
					$temp_id = '1361880649_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 6:
					//Successful Listing
					$temp_id = '1361883485_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 7:
					//Approved Listing Notification
					$temp_id = '1361886974_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 8:
					//Disapproved Listing Notification
					$temp_id = '1361887472_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 9:
					//Payment Verification
					$temp_id = '1361888937_1';
					
                    $extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					foreach( $this->mail_certificate['body'] as $k => $v ){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords( str_replace( '_', ' ', $k ) );
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
                    
				break;
				case 10:
					//Successful Delivery Notification
					$temp_id = '1361893023_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach($this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 11:
					//En-route
					$temp_id = '1361893189_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 12:
					//Returned Products Notification
					$temp_id = '1361893348_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach($this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 13:
					//Advert Payment Reciept
					$temp_id = '1361957413_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 14:
					//Featured Product Payment Reciept
					$temp_id = '1361958001_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 15:
					//Admin user login token
					$temp_id = '1361964190_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 16:
					//Merchant Store Contact Email
					$temp_id = '1362062082_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
					
				break;
				case 17:
					//Auction Listing Payment Verification
					$temp_id = '1364220431_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 18:
					//Password Reset
					$temp_id = '1364688808_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 19:
					//Custom Duty Fee
					$temp_id = '1367602515_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 20:
					//Customized Banners Upload
					$temp_id = '1368101694_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 21:
					//Won Bid
					$temp_id = '1369310519_1';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					foreach( $this->mail_certificate['body'] as $k => $v ){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords( str_replace( '_', ' ', $k ) );
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 22:
					//Merchant Account Verification Success
					$temp_id = '1376494104';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
					
				break;
				case 23:
					//Pick-up Email to Delivery Agent
					$temp_id = '1377423748_333';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="padding:4px; font-weight:bold; background:#f1f1f1;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 24:
					//Shipping Label to Merchants [DHL]
					$temp_id = '1377423921_340';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 25:
					//Shipping Label to Merchants [FEDEX]
					$temp_id = '1377424368_895';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 26:
					//Merchant Store Set-up Fee
					$temp_id = '1376494105';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					foreach($this->mail_certificate as $k => $v){
						$extra_data .= '<tr>';
							$extra_data .= '<td style="width:15%; padding:4px 0; font-weight:bold;">';
								$extra_data .= ucwords(str_replace('_',' ',$k));
							$extra_data .= '</td>';
							$extra_data .= '<td>';
								$extra_data .= $v;
							$extra_data .= '</td>';
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
					
				break;
				case 27:
					//Shipping Label to Merchants [CHINA POST]
					$temp_id = '1381750799_970';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 28:
					//Shipping Label to Merchants [NIPOST POST]
					$temp_id = '1381751040_101';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 29:
					//Shipping Label to Merchants [USPS POST]
					$temp_id = '1381751104_412';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 30:
					//Successful email address verification after registration Message
					/*Change Later*/ //$temp_id = '1361864988_1';	/*Change Later*/
                    $temp_id = '1389047731_766';
                    
                    $extra_data = '<div style="clear:both; text-align:center;" align="center"><a href="'.$this->class_settings['project_data']['domain_name'].'/account/?account_a=become_a_seller&g=1&m=account" target="_blank" style="color: #fff; text-decoration: none; background: #00509D; background: -webkit-gradient(linear,left top,left bottom,from(#0079BC),to(#00509D)); background: -moz-linear-gradient(top,#0079BC,#00509D); -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none; border: none; border-bottom: 4px solid #00509D; width: 210px; cursor: pointer; min-height: 38px; line-height: 18px; font-size:12px; font-family:verdana, arial; clear:both; text-align:center; display:inline-block; margin:auto; padding: 4px;" title="Click to Become a Seller">Click here to Become A Seller and start selling</a>&nbsp;<a href="'.$this->class_settings['project_data']['domain_name'].'" target="_blank" style="color: #fff; text-decoration: none; background: #00509D; background: -webkit-gradient(linear,left top,left bottom,from(#0079BC),to(#00509D)); background: -moz-linear-gradient(top,#0079BC,#00509D); -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none; border: none; border-bottom: 4px solid #00509D; width: 210px; cursor: pointer; min-height: 38px; line-height: 18px; font-size:12px; font-family:verdana, arial; text-align:center; display:inline-block; margin:auto; padding: 4px;" title="Return to Home Page">Click here to browse our product catalogue</a></div>';
				break;
				case 32:
					//Support Auto Responder
					$temp_id = '1392897839_364';
				break;
				case 33:
					//Unsuccessful Payment Notification
					$temp_id = '1407380913_502';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;" >';
					//Get verification link
					$extra_data .= '<tr style="min-height:48px; background-image: linear-gradient(to bottom, #ee3138, #ac3a3c) !important;	font:12px Verdana, Arial; font-weight:bold;	color:#fff; line-height:19px; text-align:center; padding:8px 3px;" >';
					foreach($this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="min-height:48px; background-image: linear-gradient(to bottom, #d2edf8, #94d9f6) !important;	font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; text-align:center; padding:8px 3px;" >';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 31:
					//Shipping Label to Merchants [Custom Shipping]
					$temp_id = '1381751104_400';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;">';
					//Get verification link
					$extra_data .= '<tr style="background:#f1f1f1; min-height:20px; font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; padding:5px 3px;">';
					foreach( $this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="font:12px Verdana, Arial; color:#333; line-height:19px; padding:5px 3px;">';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 34:
					//Pending Payment Notification
					$temp_id = '1407380985_117';
					
					$extra_data = '<table style="width:100%; border:none; margin-bottom:30px; font-size:12px; font-family:verdana;" >';
					//Get verification link
					$extra_data .= '<tr style="min-height:48px; background-image: linear-gradient(to bottom, #ee3138, #ac3a3c) !important;	font:12px Verdana, Arial; font-weight:bold;	color:#fff; line-height:19px; text-align:center; padding:8px 3px;" >';
					foreach($this->mail_certificate['head'] as $v){
						$extra_data .= '<td>';
							$extra_data .= ucwords($v);
						$extra_data .= '</td>';
					}
					$extra_data .= '</tr>';
					
					foreach($this->mail_certificate['body'] as $arr){
						$extra_data .= '<tr style="min-height:48px; background-image: linear-gradient(to bottom, #d2edf8, #94d9f6) !important;	font:12px Verdana, Arial; font-weight:bold;	color:#333; line-height:19px; text-align:center; padding:8px 3px;" >';
						foreach($arr as $v){
							$extra_data .= '<td>';
								$extra_data .= ucwords($v);
							$extra_data .= '</td>';
						}
						$extra_data .= '</tr>';
					}
					$extra_data .= '</table><br />';
				break;
				case 35:
					//User Change Password
					$temp_id = '1407380985_118';
				break;
				case 40:
					//Send Newsletter
					$temp_id = '';
                    return $this->class_settings['message'];
				break;
				}
				
                if( $temp_id ){
                    $email_template = new cEmail_template();
					
                    $q = "SELECT * FROM `".$this->class_settings[ 'database_name' ]."`.`email_template` WHERE `record_status`='1' AND `id`='".$temp_id."'";
                    //1 - SINGLE
                    $query_settings = array(
                        'database' => $this->class_settings[ 'database_name' ],
                        'connect' => $this->class_settings[ 'database_connection' ],
                        'query' => $q,
                        'query_type' => 'SELECT',
                        'set_memcache' => 1 ,
                        'tables' => array('email_template') ,
                    );
                    
                    $r = execute_sql_query($query_settings);
                    if($r && is_array($r)){
                        $a = $r[0];
                        $this->message = $extra_data . stripslashes( $a[ $email_template->table_fields['email'] ] );
                        $this->class_settings['subject'] = $a[ $email_template->table_fields['subject'] ];
                    }
				}
                
				$h .= $this->message;
                
                if( isset( $this->mail_certificate['body_end'] ) && $this->mail_certificate['body_end'] ){
                    $h .= $this->mail_certificate['body_end'];
                }
                
				$h .= '</div>';
				
				$h .= '<div style="margin-top:30px; margin-bottom:10px; font-size:18px; color:#115798;">';
					$h .= $this->class_settings['project_data']['project_name'];
					
					$h .= '<div style="font-size:11px; color:#115798; font-style:italic;">';
						$h .= $this->class_settings['project_data']['slogan'];
					$h .= '</div>';
				$h .= '</div>';
					
				$h .= '<div style="border-top:1px solid #ddd; padding-top:5px; font-size:11px; line-height:1.25;">';
				$h .= $this->class_settings['project_data']['street_address'].',<br />';
				$h .= $this->class_settings['project_data']['city'].', '.$this->class_settings['project_data']['state'].'<br />';
				$h .= $this->class_settings['project_data']['country'];
				$h .= '</div>';
					
				$h .= '<div style="padding-top:10px; font-size:11px; line-height:1.25;">';
					$h .= $this->class_settings['project_data']['phone'];
				$h .= '</div>';
			$h .= '</div>';
			$h .= '</div>';
			$h .= '</div>';
			
			return $h;
		}
		
	}
?>