<?php
	/**
	 * KOBOKO General Functions File
	 *
	 * @used in  				settings/Setup.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| KOBOKO General Functions File
	|--------------------------------------------------------------------------
	|
	| General functions would to perform several task in the KOBOKO
	|
	*/
	
//Project Data
function get_project_data(){
	
	$project['development_mode'] = true;		//Set true/false to toggle dev/production modes
	
	$project['company_name'] = 'Zidoff E-commerce Limited';
	
	$project['project_title'] = 'Zidoff.com';
	
	//$project['slogan'] = '...Africa\'s Global Marketplace';
	$project['slogan'] = '...Your Global Marketplace';
	
	if( isset( $_SESSION['project_slogan'] ) && $_SESSION['project_slogan'] )
		$project['slogan'] = $_SESSION['project_slogan'];
		
	$project['project_name'] = 'zidoff.com';
    
    $protocol = ( isset( $_SERVER["https"] ) || isset( $_SERVER["HTTPS"] ) ) ? 'https' : 'http';
	$project['domain_name'] = $protocol.'://localhost/dev-3/';
	//$project['domain_name'] = 'http://www.zidoff.com/loading/dev-2014/';
	
    //$project['domain_name'] = 'http://www.zidoff.com/';
	//$project['domain_name'] = 'http://www.zidoff.com/test18/public_html';
	$project['domain_name_only'] = 'www.zidoff.com';
	
	$project['remote_server_request_url'] = 'http://inventory.challydoff.com/php/inventory_manager_processor.php';
	
	//comma-sperated list or zidoff stores id's
	$project['zidoff_store_id'] = '1400747106_366';

	$project['street_address'] = 'B24 TJ 1406 Plaza, Bozoum Close, Wuse II';
	$project['full_street_address'] = 'B24 TJ 1406 Plaza,<br />Bozoum Close,<br />Wuse II, Abuja, 900288';
	$project['city'] = 'Abuja';
	$project['state'] = 'F.C.T';
	$project['country'] = 'Nigeria';
	
	//$project['phone'] = '+234 700-7467-943633<br />+234 0700-SHOP-ZIDOFF';
	$project['phone'] = '+234 700-SHOP-ZIDOFF<br />+234 700-7467-943633';
	//$project['support_line'] = '+234 700-SHOP-ZIDOFF<br />+234 700-7467-943633';
	$project['support_line'] = '+234 814-9906-150';
	
	$project['admin_email'] = 'zlog@challydoff.com';
	$project['accounts_email'] = 'zmail@challydoff.com';
	
	$project['email'] = 'info@zidoff.com';
	$project['support_email'] = 'support@zidoff.com';
	$project['payment_reciept_email'] = 'support@zidoff.com';
	
	$project['delivery_email'] = 'info@challydoff.com';
	
	$project['admin_login_form_passkey'] = '19881011988';
	
	$project['facebook_link'] = 'https://www.facebook.com/pages/Zidoffcom/524269607644769';
	$project['twitter_link'] = 'https://twitter.com/ZidoffGlobal';
	$project['googleplus_link'] = 'https://plus.google.com/u/0/106125070712614311944/posts';
	
	$project['dhl'] = array(
		'account_number' => array(
			0 => '365091693', //Local Account Number / Export [Outbound]
			1 => '955260289', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('nginquiry@dhl.com'),
				'full_name' => array('DHL'),
				'id' => array('dhl1'),
			),
			1 => array(	//International
				'email' => array('ngtcollect@dhl.com'),
				'full_name' => array('DHL'),
				'id' => array('dhl1'),
			),
		),
	);
	
	$project['nipost'] = array(
		'account_number' => array(
			0 => 'xxx', //Local Account Number / Export [Outbound]
			1 => 'xxx', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('yyy@nipost.com'),
				'full_name' => array('NIPOST'),
				'id' => array('nipost1'),
			),
			1 => array(	//International
				'email' => array('yyy@nipost.com'),
				'full_name' => array('NIPOST'),
				'id' => array('nipost1'),
			),
		),
	);
	
	$project['fedex'] = array(
		'account_number' => array(
			0 => 'none', //Local Account Number / Export [Outbound]
			1 => 'none', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('none'),
				'full_name' => array('FEDEX'),
				'id' => array('fedex1'),
			),
			1 => array(	//International
				'email' => array('none'),
				'full_name' => array('FEDEX'),
				'id' => array('fedex1'),
			),
		),
	);
	
	$project['custom_shipping'] = array(
		'account_number' => array(
			0 => 'none', //Local Account Number / Export [Outbound]
			1 => 'none', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('none'),
				'full_name' => array('CUSTOM SHIPPING'),
				'id' => array('customshipping1'),
			),
			1 => array(	//International
				'email' => array('none'),
				'full_name' => array('CUSTOM SHIPPING'),
				'id' => array('customshipping1'),
			),
		),
	);
	$project['custom_shipping_free'] = $project['custom_shipping'];
	
	$project['china_post'] = array(
		'account_number' => array(
			0 => 'none', //Local Account Number / Export [Outbound]
			1 => 'none', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('none'),
				'full_name' => array('CHINA POST'),
				'id' => array('china_post'),
			),
			1 => array(	//International
				'email' => array('none'),
				'full_name' => array('CHINA POST'),
				'id' => array('china_post'),
			),
		),
	);
	
	$project['usps'] = array(
		'account_number' => array(
			0 => 'none', //Local Account Number / Export [Outbound]
			1 => 'none', //Import Account Number [Inbound]
		),
		'email_address' => array(
			0 => array(		//Local
				'email' => array('none'),
				'full_name' => array('USPS'),
				'id' => array('usps'),
			),
			1 => array(	//International
				'email' => array('none'),
				'full_name' => array('USPS'),
				'id' => array('usps'),
			),
		),
	);
	
	return $project;
}

function __date(){
    //
    $settings = array(
        'cache_key' => 'today-date',
    );
    $date = get_cache_for_special_values( $settings );
    if( ! $date ){
        $date = date("U");
        $settings = array(
            'cache_key' => 'today-date',
            'cache_values' => $date,
        );
        set_cache_for_special_values( $settings );
    }
    return $date;
}

function __cleardate(){
    $settings = array(
        'cache_key' => 'today-date',
    );
    clear_cache_for_special_values( $settings );
}

function clean1( $string ) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return $string; // Removes special chars.
}

function clean2( $string ) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return str_replace('-', ' ', $string); // Removes special chars.
}

//removes malicious code from data
function clean($value="", $data, $pagepointer){
	if($value){
		$value = trim($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
		$value = addslashes($value);
		$value = strtolower($value);
		
		switch($data){
		case "email":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','@','_','.');
			
			if(strlen($value) < 50){
				$value = strtolower($value);
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
						
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "number":
			$email = array('1','2','3','4','5','6','7','8','9','0',',','.');
			
			if(strlen($value) < 50){
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "id":
			$email = array('a','b','c','d','e','f','1','2','3','4','5','6','7','8','9','0','_');
			
			if(strlen($value) < 50){
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "url":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_','.','/','=');
			
			if(strlen($value) < 50){
				$value = strtolower($value);
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
						
					}
				}
				
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "alphabets":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','_','-');
			
			for($x=0; $x<strlen($value); $x++){
				if(!(in_array($value[$x],$email))){
					//LOG MALICIOUS CODE AND MAIL ADMIN
					$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
					
					//REMOVE MALICIOUS CHARACTER
					$value[$x] = '';
				}
			}
		break;
		}
		
		if(isset($mal['value']) && is_array($mal['value'])){
			//Record Threat in Audit Trail
			auditor_threat($pagepointer,$mal['value']);
			//Mail Threat to ADMIN
		}
		
		return $value;
	}
	return 0;
}

	//Returns name of all values passed via HTTP GET REQUEST
	function get_get_variables($no){
		$returning_html_data = '';
		if(isset($_GET)){
			foreach($_GET as $k => $v){
				if(!in_array($k,$no)){
					$returning_html_data .= '&'.$k.'='.$v;
				}
			}
		}
		return $returning_html_data;
	}
	
	//Returns the currently set unit of a Physical Quantity
	function get_units_converting_to($physical_quantity){
		$un = md5('units'.$_SESSION['key']);
		if(isset($_SESSION[$un][$physical_quantity]) && $_SESSION[$un][$physical_quantity]){
			return str_replace(' ','_',$_SESSION[$un][$physical_quantity]);
		}
		return 0;
	}
	
	//Returns the default value of the naira equivalent of 1 USD
	function get_naira_equivalent_of_one_us_dollar(){
		return 155;
	}

	//Converts Numbers from one format to another
	function format_and_convert_numbers( $value_to_be_converted , $conversion_type = 0, $from_units = "", $currency_conversion_rate = 0 ){
		
		$value_to_be_converted = strip_tags($value_to_be_converted);
		$value_to_be_converted = trim($value_to_be_converted);
		$value_to_be_converted = str_replace(',','',$value_to_be_converted);
		
		$value_to_be_converted = doubleval($value_to_be_converted);
		
		if($conversion_type==2){
			return number_format($value_to_be_converted,4);
		}
		
		if($conversion_type==1){
			return number_format($value_to_be_converted,0);
		}
		
		if($conversion_type==3){
			return $value_to_be_converted;
		}
		
		if($conversion_type==4){
			return number_format($value_to_be_converted,2);
		}
		
		//Volume
		if($conversion_type>10){
			$value_to_be_converted = $value_to_be_converted / 1;
			
			switch($conversion_type){
			case 24:	//Time
				//Obtain Based on Value Set while entering record ** tricky
				$from_units_array = get_time_units();
				if(isset($from_units_array[$from_units]))
					$from_units = $from_units_array[$from_units];
				else
					$from_units = 'months';
					
				$physical_quantity = 'time';
			break;
			case 18:	//Currency
				//Obtain Based on Value Set while entering record ** tricky
				$from_units_array = get_currency();
				
				if((isset($from_units) && !is_array($from_units)) && isset($from_units_array[$from_units]))
					$from_units = $from_units_array[$from_units];
				else
					$from_units = 'usd';
					
				$physical_quantity = 'currency';
			break;
			}
			
			if($from_units){
				//Get Current Unit of Volume
				$to_units = get_units_converting_to($physical_quantity);
				
				if(!$to_units){
					$to_units = $from_units;
				}
				
				switch($conversion_type){
				case 18:	//Currency
					$value_to_be_converted = currency_converter($value_to_be_converted, $from_units, $to_units, $currency_conversion_rate);
				break;
				case 24:	//Time
					$value_to_be_converted = time_converter($value_to_be_converted, $from_units, $to_units);
				break;
				}
				//Determine Number of Decimal Places to Display
				$deci_places = 2;
				if(abs($value_to_be_converted) < 0.01){
					$deci_places = 4;
				}
				if(abs($value_to_be_converted) < 0.0001){
					$deci_places = 8;
				}
				if($value_to_be_converted==0){
					$deci_places = 2;
				}
				
				return number_format($value_to_be_converted, $deci_places).' <span style="font-size:0.8em;">'.str_replace('_',' ',$to_units).'</span>';
			}
		}
		
		return number_format($value_to_be_converted,2);
	}
	
    function convert_currency( $usd_value , $direction = 'from usd' , $eliminate_symbol = 0 ){
        $usd_value = strip_tags($usd_value);
		$usd_value = trim($usd_value);
		$usd_value = str_replace(',','',$usd_value);
        
		$usd_value = doubleval( $usd_value );
        
        $country_id = SELECTED_COUNTRY_ID;
        $currency = SELECTED_COUNTRY_CURRENCY.' ';
        $rate = SELECTED_COUNTRY_CONVERSION_RATE;
        
        $ngn_rate = NIGERIAN_NAIRA_CONVERSION_RATE * 1;
        if( ! doubleval( $ngn_rate ) ){
            $ngn_rate = 1;
        }
        
        if( ! doubleval( $rate ) ){
            $rate = 1;
            $currency = '$ ';
            $country_id = '1228';
        }
        
        switch( $direction ){
        case 'get symbol':
            return $currency;
        break;
        case 'to usd':
            return ( $usd_value / $rate );
        break;
        case 'to ngn':
            $dollar = $usd_value / $rate;
            return ( $dollar * $ngn_rate );
        break;
        case 'from ngn':
            //naira to usd first
            $converted_value = $usd_value / $ngn_rate;
            
            //$converted_value = $converted_value * $rate;
            
            if($eliminate_symbol){
                return round( $converted_value , 2 );
            }
            
            switch( $country_id ){
            case '1157':
                return $currency . number_format( $converted_value , 0 );
            break;
            default:
                return $currency . number_format( $converted_value , 2 );
            break;
            }
        break;
        default:
            $converted_value = $usd_value * $rate;
            
            if($eliminate_symbol){
                return round( $converted_value , 2 );
            }
            
            switch( $country_id ){
            case '1157':
                return $currency . number_format( $converted_value , 0 );
            break;
            default:
                return $currency . number_format( $converted_value , 2 );
            break;
            }
        break;
        }
        
    }
    
	//Returns generated codes
	function generateCodes( $plength, $include_letters , $include_capitals , $include_numbers , $include_punctuation ){

        // First we need to validate the argument that was given to this function
		$pwd = '';
        // If need be, we will change it to a more appropriate value.
        if(!is_numeric($plength) || $plength <= 0)
        {
            $plength = 8;
        }
        if($plength > 32)
        {
            $plength = 32;
        }

        // This is the array of allowable characters.
                $chars = "";

                if ($include_letters == true) { $chars .= 'abcdefghijklmnopqrstuvwxyz'; }
                if ($include_capitals == true) { $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
                if ($include_numbers == true) { $chars .= '0123456789'; }
                if ($include_punctuation == true) { $chars .= '`¬£$%^&*()-_=+[{]};:@#~,<.>/?'; }

                // If nothing selected just display 0's
                if ($include_letters == false AND $include_capitals == false AND $include_numbers == false AND $include_punctuation == false) {
                    $chars .= '0';
                }

        // This is important:  we need to seed the random number generator
        mt_srand(microtime() * 1000000);

        // Now we simply generate a random string based on the length that was
        // requested in the function argument
        for($i = 0; $i < $plength; $i++)
        {
            $key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars{$key};
        }

        // Finally to make it a bit more random, we switch some characters around
        for($i = 0; $i < $plength; $i++)
        {
            $key1 = mt_rand(0,strlen($pwd)-1);
            $key2 = mt_rand(0,strlen($pwd)-1);

            $tmp = $pwd{$key1};
            $pwd{$key1} = $pwd{$key2};
            $pwd{$key2} = $tmp;
        }

        // Convert into HTML
        $pwd = htmlentities($pwd, ENT_QUOTES);

        return $pwd;
    }
	
	//Get Client IP Address
	function get_ip_address() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
	}
	
	//Return the first few characters of a string
	function return_first_few_characters_of_a_string( $data , $len = 158 ){
		
		$result_of_sql_queryaw = $data;
		$data = strip_tags($data);
		//Determine if string length is greater than 132
		if(strlen($data) > $len){
			/*
			//Get the character at position 132
			$char = substr($data,($len-1),1);
			
			//Check if $char is not spacebar; then seek next position of spacebar
			if(!($char==' ')){
				while($char!=' '){
					$char = substr($data,(++$len-1),1);
				}
			}
			*/
			$data = substr($data,0,$len);
			
			return $data.'...';
		}else{
			return $data;
		}
		//Take the last 12 characters of the short string
		///$locate = substr($data,-12,12);
		//Break original string by this amount
		///$pieces = explode($locate,$result_of_sql_queryaw);
		//Put back short string with html formatting
		///$data = $pieces[0].$locate;
		
	}
	
	//Write a file to disk
	function write_file( $url , $file_name , $content_of_file ){
		$full_file_name = $url . $file_name;
		
		$fp = fopen( $full_file_name , "w" );
		fputs( $fp , $content_of_file );
		fclose( $fp );
	}
	
	//Return contents of a file from disk
	function read_file( $url = null , $file_name ){
		$full_file_name = $url.$file_name;
		
		if( file_exists( $full_file_name ) ){
			
			$fp = fopen($full_file_name, "r");
			
			$contents_of_file = '';
			
			if( $fp ){
				while( ! feof( $fp ) ) {
					$contents_of_file .= fgets( $fp , 2024 );
				}
			}
			fclose( $fp );
			
			return $contents_of_file;
		}
	}
	
	//Insert a new record into a database table and return response of such operation as boolean
	function create( $settings ){
		$database_name = $settings[ 'database_name' ]; 
		$table_name = $settings[ 'table_name' ]; 
		$database_connection = $settings[ 'database_connection' ];
		
		$fields = array();
		$values = array();
		
		foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
			$fields[] = $field_name;
			$values[] = addslashes( $field_properties[ 'value' ] );
		}
		$fields_of_database_table = implode(",", $fields);
		
		$values_to_be_inserted_into_each_field = "'".implode("','", $values)."'";
		
		
		$query = "INSERT INTO `".$database_name."`.`".$table_name."` ($fields_of_database_table) VALUES ($values_to_be_inserted_into_each_field)";
		
        //echo $query;
        //return 1;
		//2. Run Query
		/***********************/
		//1 - SINGLE
		$query_settings = array(
			'database'=>$database_name,
			'connect'=>$database_connection,
			'query'=>$query,
			'query_type'=>'INSERT',
			'set_memcache'=>1,
			'tables'=>array($table_name),
		);
		/***********************/
		
		$result_of_sql_query = execute_sql_query($query_settings);
		
		if(isset($result_of_sql_query) && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
			return 1;
		}else{
			return 0;
		}
	}
	
	function update( $settings = array() ){
		
		$database_name = $settings[ 'database_name' ]; 
		$table_name = $settings[ 'table_name' ]; 
		$database_connection = $settings[ 'database_connection' ];
		
		$where = $settings[ 'where_fields' ];
		$id = $settings[ 'where_values' ];
		
		$condition = "AND";
		if( isset( $settings[ 'condition' ] ) )
			$condition = $settings[ 'condition' ];
		
		$fields = array();
		$values = array();
		
		foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
			$fields[] = $field_name;
			$values[] = addslashes( $field_properties[ 'value' ] );
		}
		
		$fields_of_database_table = $fields;
		
		$value = $values;
		
		$allow_zeros = array(
			'record_status', 
			'product010', 
			'product012', 
			'product014', 
		);
		
		$retrieve_value2 = "";
		$retrieve_value = "";
		
		$k = 0;
		$deduct = 1;
		foreach($fields_of_database_table as $fields_of_database_tables){
			if( $k < count( $fields_of_database_table ) - 1 ){
				if(in_array($fields_of_database_table[$k], $allow_zeros)){
					if( isset($value[$k]) && $value[$k] !='undefined' ){
						if( $retrieve_value2 )
							$retrieve_value2 .= ", ";
							
						$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."' ";
						
					}
				}else{
					//if( $value[$k] && $value[$k] !='undefined' ){
					if( $value[$k] !='undefined' ){
						if( $retrieve_value2 )
							$retrieve_value2 .= ", ";
							
						$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."' ";
					}
				}
			}else{
				if(in_array($fields_of_database_table[$k], $allow_zeros)){
					if( isset($value[$k]) && $value[$k] !='undefined' ){
						if( $retrieve_value2 )
							$retrieve_value2 .= ", ";
							
						$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."'";
					}
				}else{
					//if( $value[$k] && $value[$k] !='undefined' ){
					if( $value[$k] !='undefined' ){
						if( $retrieve_value2 )
							$retrieve_value2 .= ", ";
							
						$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."'";
					}
				}
			}
			$k++;
		}
		$fields_of_database_table_value = $retrieve_value2;
		if(!empty($where)){
			$ids = explode("<>", $id);
			$wherens = explode(",", $where);
			$k = 0;
			foreach($wherens as $value){
				if($k<count($wherens)-1){
					$retrieve_value .= "`".$wherens[$k]."` = '".$ids[$k]."' ".$condition." ";
				}
				else{
					$retrieve_value .= "`".$wherens[$k]."` = '".$ids[$k]."'";
				}
				$k++;
			}
			$where = "WHERE $retrieve_value";
		}
		$query = "UPDATE `".$database_name."`.`".$table_name."` SET $fields_of_database_table_value $where";
		
		//if( $table_name == 'tasks' ){
		//echo $query;
		//return $query;
		//exit;
		//}
		//2. Run Query
		/***********************/
		//1 - SINGLE
		$query_settings = array(
			'database' => $database_name,
			'connect' => $database_connection,
			'query' => $query,
			'query_type' => 'UPDATE',
			'set_memcache' => 1,
			'tables' => array($table_name),
		);
		/***********************/
	
		$result_of_sql_query = execute_sql_query($query_settings);
		
		if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
            return 1;
		}else{
			return 0;
		}
	}
	
	//Return Search Query to be executed when particular records are being searched for in the database
	//function search( $database , $table_name , $database_connection, $search_condition, $where, $value, $where_condition = "OR" ){
	function search( $settings = array() ){
		
		$database_name = $settings[ 'database_name' ]; 
		$table_name = $settings[ 'table_name' ]; 
		$database_connection = $settings[ 'database_connection' ];
		
		$where = $settings[ 'where_fields' ];
		$id = $settings[ 'where_values' ];
		
		$condition = "AND";
		if( isset( $settings[ 'condition' ] ) )
			$condition = $settings[ 'condition' ];
		
		$where_condition = "AND";
		if( isset( $settings[ 'where_condition' ] ) )
			$where_condition = $settings[ 'where_condition' ];
		
		$fields = array();
		$values = array();
		$search_conditions = array();
		
		foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
			$fields[] = $field_name;
			$values[] = $field_properties[ 'value' ];
			
			$search_conditions[] = $field_properties[ 'search_condition' ];
		}
		
		$fields_of_database_table = $fields;
		$wherens = $fields_of_database_table;
		
		$value = $values;
		
		$retrieve_value="";
		//$search_conditions = explode(",", $search_condition);
		
		if(!empty($where)){
			$values_to_be_inserted_into_each_field = $value;
			//$values_to_be_inserted_into_each_field = explode("<>", $value);
			
			//$wherens = explode(",", $where);
			$k = 0;
			foreach($wherens as $val){
				if($retrieve_value){
					if(isset($wherens[$k]) && isset($values_to_be_inserted_into_each_field[$k]) && isset($search_conditions[$k]) && $wherens[$k] && $values_to_be_inserted_into_each_field[$k] && $search_conditions[$k]){
						//Check the type of condition
						switch($search_conditions[$k]){
						case ">":
						case "<":
							$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."`/1 ) ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
						break;
						/*
						case "REGEXP":
						case "regexp":
							$search_conditions[$k] = 'LIKE';
							$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."` ) ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
						break;
						case "NOT REGEXP":
						case "not regexp":
							$search_conditions[$k] = 'NOT LIKE';
							$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."` ) ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
						break;
						*/
						default:
							$retrieve_value .= " ".$where_condition." `".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
						break;
						}
					}
				}
				else{
					if(isset($wherens[$k]) && isset($values_to_be_inserted_into_each_field[$k]) && isset($search_conditions[$k]) && $wherens[$k] && $values_to_be_inserted_into_each_field[$k] && $search_conditions[$k]){
						//Check the type of condition
						switch($search_conditions[$k]){
						case ">":
						case "<":
							$retrieve_value = "( `".$table_name."`.`".$wherens[$k]."`/1 ) ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
						break;
						/*
						case "REGEXP":
						case "regexp":
							$search_conditions[$k] = 'LIKE';
							$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
						break;
						case "NOT REGEXP":
						case "not regexp":
							$search_conditions[$k] = 'NOT LIKE';
							$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
						break;
						*/
						default:
							$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
						break;
						}
					}
				}
				$k++;
			}
			$where = $retrieve_value;
			
			if($where){
				$query = "SELECT * FROM `".$database_name."`.`".$table_name."` WHERE ".$where;
				
				//Return Search Query that would be used by ajax_server file
				return array("SELECT"," * FROM `".$database_name."`.`".$table_name."`"," WHERE ( ".$where." ) AND `".$table_name."`.`record_status`='1'");
				
				//$x = rand(876566,346353422224445432564398765);
				//write_file('','sql/'.$x.'update.php',$query);
			
				//return mysql_query($query,$database_connection);
			}
		}
		
		//Return Select a Valid Search Criteria
		return 0;
	}
	
	//Generates and returns new unique number
	function get_new_id(){
		$_SESSION['last_generated_id'] = doubleval( get_cache_for_special_values( array( 'cache_key' => 'last_id' ) ) );
	
		//Initialize generated id serial number
		if( ! isset($_SESSION['generated_id_serial_number']) ){
			$_SESSION['generated_id_serial_number'] = 1;
			
		}
		
		$timestamp = mktime(date('G'),date('i'),date('s'),date('n'),date('j'),(date("Y")-43));
		$timestamp += 99;
		
		if(isset($_SESSION['last_generated_id'])){
			if($timestamp==$_SESSION['last_generated_id']){
				++$_SESSION['generated_id_serial_number'];
			}else{
				$_SESSION['generated_id_serial_number'] = rand( 10 , 79 );
			}
		}
		
		//Set Last Generated ID
		$_SESSION['last_generated_id'] = $timestamp;
		
		$settings = array(
			'cache_key' => 'last_id',
			'cache_values' => $timestamp,
		);
		set_cache_for_special_values( $settings );
		
		return $timestamp.$_SESSION['generated_id_serial_number'];
	}
	
	//Create A New Directory
	function create_folder( $directory_name_1 , $directory_name_2 , $directory_name_3 ){
		//CREATE ITEM FOLDER
		
		if(!(is_dir( $directory_name_1 . $directory_name_2 . $directory_name_3 ))){
			$oldumask = umask(0);
			
			mkdir(( $directory_name_1 . $directory_name_2 . $directory_name_3 ),0755);
			
			umask( $oldumask );
		}
		
		//Folder URL
		return $directory_name_1 . $directory_name_2 . $directory_name_3;
	}
	
	//Returns the time after an action occur
	function time_passed_since_action_occurred( $seconds , $format = 0 ){
		
		//VALID LISTING
		if($seconds < 0){
			return 0;
		}else{
		
			//Initialization
			$t = $seconds.' seconds left';
			switch($format){
			case 2:
				$t = 'just now';
			break;
			}
			
			$div = array(60,60,24,30,12);
			$comp = array(1,2,2,2,2);
			$lbl = array('seconds left','minutes left','hours left','days left','months left');
			
			$label_for_time_only = array('seconds','minutes','hours','days','months');
			
			$label_for_ago = array('seconds ago','minutes ago','hours ago','days ago','months ago');
			
			//Test if time is in seconds
			$curve = 1;
			for($x=0; $x<sizeof($div); $x++){
				$ti = $seconds / $curve;
				if($ti > $comp[$x]){
					switch($format){
					case 0:
						$t = round($ti,1).'<span class="pleft"> '.$lbl[$x].'</span>';
					break;
					case 1:
						$t = round($ti,1).'<span class="pleft"> '.$lbl[$x].'</span>';
						$t .= '<div class="expire-date">('.date('jS M Y H:m:s',(date('U') + $seconds)).')</div>';
					break;
					case 2:
						$t = round($ti,1).'<span class="pleft"> '.$label_for_ago[$x].'</span>';
					break;
					case 3:		//Label for time only
						$t = round($ti,1).'<span class="pleft"> '.$label_for_time_only[$x].'</span>';
					break;
					}
				}
				$curve *= $div[$x];
			}
		}
		return $t;
	}
	
	//Send Emails to an Email Address
	function send_email_notification( $settings = array() ){
		
		$pagepointer = '';
		if( isset( $settings['pagepointer'] ) )
			$pagepointer = $settings['pagepointer'];
			
		$result_of_sql_queryecipient_emails = array();
		if( isset( $settings['recipient_emails'] ) )
			$result_of_sql_queryecipient_emails = $settings['recipient_emails'];
			
		$result_of_sql_queryecipient_fullnames = array();
		if( isset( $settings['recipient_fullnames'] ) )
			$result_of_sql_queryecipient_fullnames = $settings['recipient_fullnames'];
			
		$subject = '';
		if( isset( $settings['subject'] ) )
			$subject = $settings['subject'];
			
		$message = '';
		if( isset( $settings['message'] ) ){
			if( file_exists( $pagepointer . 'css/email-notification.css' ) ){
				$message .= '<style>' . read_file( '' , $pagepointer . 'css/email-notification.css' ) . '</style>';
			}
			$message .= '<div id="message-content">';
			$message .= '<div id="message-content-header">Gas Helix - Notification</div>';
			$message .= '<div id="message-content-body">' . $settings['message'] . '</div>';
			$message .= '</div>';
		}
		
		$sender_email = '';
		if( isset( $settings['sender_email'] ) )
			$sender_email = $settings['sender_email'];
			
		$sender_fullname = '';
		if( isset( $settings['sender_fullname'] ) )
			$sender_fullname = $settings['sender_fullname'];
		
		$admin_email = 'gashelix.admin@gmail.com';
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: ';
		
		$email_address_count = 0;
		foreach( $result_of_sql_queryecipient_emails as $result_of_sql_queryecipient_id => $result_of_sql_queryecipient_email ){
			if( $email_address_count )$headers .= ', '.$result_of_sql_queryecipient_fullnames[$result_of_sql_queryecipient_id].' <'.$result_of_sql_queryecipient_email.'>';
			else $headers .= $result_of_sql_queryecipient_fullnames[$result_of_sql_queryecipient_id].' <'.$result_of_sql_queryecipient_email.'>';
			
			++$email_address_count;
		}
		$headers .= "\r\n";
		
		$headers .= 'From: '.$sender_fullname.' <'.$sender_email.'>' . "\r\n";
		$headers .= 'Bcc: '.$admin_email. "\r\n";		
		
		//Write mail to tmp folder for reference
		$mail = '<?php echo "<h1>Access Denied</h1>";'."\n\n";
			$mail .= '$email = "'.implode(' , ' , $result_of_sql_queryecipient_emails).'";'."\n\n";
			$mail .= '$subject = "'.$subject.'";'."\n\n";
			$mail .= '$message = "'.$message.'";'."\n\n";
			$mail .= '$headers = "'.$headers.'";'."\n\n";
			$mail .= '$timestamp = "'.date("U").'";'."\n\n";
		$mail .= '?>';		
		write_file('',$pagepointer.'tmp/sent_mails/'.implode('_' , $result_of_sql_queryecipient_emails).date("jS M Y Hms").'.php',$mail);
		
		//Activate to send mail to email account when testing online
		//mail($email,$subject,$message, $headers);
	}
	
	//Function to Insert a new record into a database table during a multiple write operation
	function insert_new_record_into_table( $function_settings = array() ){
		if(isset($function_settings['table']) && isset($function_settings['database'])  && isset($function_settings['connect']) ){
			//GET FIELD COUNT
			$fields_of_database_table_count = 0;
			$query = "DESCRIBE `".$function_settings['database']."`.`".$function_settings['table']."`";
			$query_settings = array(
				'database'=>$function_settings['database'],
				'connect'=>$function_settings['connect'],
				'query'=>$query,
				'query_type'=>'DESCRIBE',
				'set_memcache'=>1,
				'tables'=>array($function_settings['table']),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$data_to_be_wriiten_to_database = array();
			
			$data_to_update_database = array();
			$update_conditions = array();
			
			$columns_count = 0;
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval){
					
					foreach( $function_settings[ 'dataset' ] as $key => $value ){
						if( isset( $value[ $sval[0] ] ) ){
							$data_to_be_wriiten_to_database[$key][] = $value[ $sval[0] ];
							
						}else{
							$data_to_be_wriiten_to_database[$key][] = '';
							
						}
					}
					
					++$columns_count;
				}
				
				$settings = $function_settings;
				
				//update operation instead of insert
				
				if( isset( $function_settings[ 'update_conditions' ] ) ){
					$settings['update_conditions'] = $function_settings[ 'update_conditions' ];
					$settings['update_dataset'] = $function_settings[ 'dataset' ];
					
					return _update_multiple_records( $settings );
				}
				
				$settings['dataset'] = $data_to_be_wriiten_to_database;
				$settings['column_count'] = $columns_count;
				
				return _put_multiple_records( $settings );
				
			}
		}
	}
	
	//Function that actually performs the write operation in the database 
	function _put( $conn , $database_name , $database_table, $data_to_be_written, $x11 ){
		for($x=0; $x<$x11; $x++){
			$data_to_be_written[$x]= addslashes($data_to_be_written[$x]);
		}
		$_dat = "";
		foreach($data_to_be_written as $key => & $value){
			if($key == 0)$_dat = "'". $value ."'";
			else $_dat = $_dat.","."'". $value ."'";
		}
		
		//$x = rand(876566,346353422224445432564398765);
		//write_file('','sql/'.$x.'put_id.php',"INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )");
		
		$q = "INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )";
		
		$query_settings = array(
			'database'=>$database_name,
			'connect'=>$conn,
			'query'=>$q,
			'query_type'=>'INSERT',
			'set_memcache'=>1,
			'tables'=>array($database_table),
		);
		/***********************/
	
		$result_of_sql_query = execute_sql_query($query_settings);
		
		if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
			return 1; //"Database selected successfully<br>";
		}else{
			return 0;
		}
		
	}
	
	//Function to insert multiple records to database
	function _put_multiple_records( $function_settings = array() ){
		
		if(isset( $function_settings['table'] ) && isset($function_settings['database'])  && isset($function_settings['connect']) && isset( $function_settings['dataset'] ) && isset( $function_settings['column_count'] ) ){
		
			$table_records_set = $function_settings['dataset'];
			$_dat = '';
			
			foreach($table_records_set as $key => $data_to_be_written){
				$_record_to_be_inserted = '';
				
				for($x=0; $x<count($data_to_be_written); $x++){
					$data_to_be_written[$x] = addslashes($data_to_be_written[$x]);
					
					if($_record_to_be_inserted)$_record_to_be_inserted .= ","."'". $data_to_be_written[$x] ."'";
					else $_record_to_be_inserted = "'". $data_to_be_written[$x] ."'";
					
				}
				
				//Oracle Fix
				/*
				$q = "INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_record_to_be_inserted )";
				$query_settings = array(
					'database'=>$database_name,
					'connect'=>$conn,
					'query'=>$q,
					'query_type'=>'INSERT',
					'set_memcache'=>1,
					'tables'=>array($database_table),
				);
			
				$result_of_sql_query = execute_sql_query($query_settings);
				*/
				//Enable When on Mysql
				if($_dat)$_dat .= ", (".$_record_to_be_inserted.")";
				else $_dat = " (".$_record_to_be_inserted.")";
				
			}
			
			//$x = rand(876566,346353422224445432564398765);
			//write_file('','sql/'.$x.'put_id.php',"INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )");
			//Enable for mysql
			$q = "INSERT INTO `" . $function_settings['database'] . "`.`" . $function_settings['table'] . "` VALUES $_dat";
			$query_settings = array(
				'database' => $function_settings['database'],
				'connect' => $function_settings['connect'],
				'query' => $q,
				'query_type' => 'INSERT',
				'set_memcache' => 1,
				'tables' => array( $function_settings['table'] ),
			);
			
			$result_of_sql_query = execute_sql_query($query_settings);
			
			if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
				return 1; //"Database selected successfully<br>";
			}else{
				return 0;
			}
		}
	}

	//Function to insert multiple records to database
	function _update_multiple_records( $function_settings = array() ){
		
		if(isset( $function_settings['table'] ) && isset($function_settings['database'])  && isset($function_settings['connect']) && isset( $function_settings['dataset'] ) && isset( $function_settings['update_conditions'] ) ){
		
			$database_name = $function_settings[ 'database' ]; 
			$database_table = $function_settings[ 'table' ]; 
			$database_connection = $function_settings[ 'connect' ];
		
			$table_records_set = $function_settings[ 'update_dataset' ];
			
			$update_conditions = $function_settings[ 'update_conditions' ];
			
			$_dat = '';
			
			$all_update_queries = "";
			
			foreach($table_records_set as $index => $fields_to_be_written){
				$_record_to_be_inserted = "";
				
				foreach( $fields_to_be_written as $key => $value ){
					switch( $key ){
					case "id":
					case "created_role":
					case "created_by":
					case "creation_date":
					case "ip_address":
					case "record_status":
					break;
					default:
						if( $_record_to_be_inserted )
							$_record_to_be_inserted .= ", `".$database_table."`.`".$key."`='".$value."'";
						else
							$_record_to_be_inserted = "UPDATE `".$database_name."`.`".$database_table."` SET `".$database_table."`.`".$key."`='".$value."'";
					break;
					}
				}
				
				if( $_record_to_be_inserted ){
					//where condition
					if( isset( $update_conditions[ $index ]['where_fields'] ) && isset( $update_conditions[ $index ]['where_values'] ) ){
						
						$_record_to_be_inserted .= " WHERE `".$database_table."`.`".$update_conditions[ $index ]['where_fields']."` = '".$update_conditions[ $index ]['where_values']."' ";
						
						$query_settings = array(
							'database' => $function_settings['database'],
							'connect' => $function_settings['connect'],
							'query' => $_record_to_be_inserted,
							'query_type' => 'UPDATE',
							'set_memcache' => 1,
							'tables' => array( $function_settings['table'] ),
						);
						
						$result_of_sql_query = execute_sql_query($query_settings);
						
						/*
						if( $all_update_queries )
							$all_update_queries .= "; ".$_record_to_be_inserted;
						else
							$all_update_queries = $_record_to_be_inserted;
						*/
					}
				}
			}
			
			//echo $all_update_queries;
			//exit;
			
			return 1;
			/*
			$query_settings = array(
				'database' => $function_settings['database'],
				'connect' => $function_settings['connect'],
				'query' => $all_update_queries,
				'query_type' => 'UPDATE',
				'set_memcache' => 1,
				'tables' => array( $function_settings['table'] ),
			);
			
			$result_of_sql_query = execute_sql_query($query_settings);
			
			if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
				return 1; //"Database selected successfully<br>";
			}else{
				return 0;
			}
			*/
		}
	}

//Initialize and Reuse a PHP Class to perform an action
	function reuse_class( $settings = array() ){
	
	$pagepointer = '';
	if( isset( $settings[ 'pagepointer' ] ) )
		$pagepointer = $settings[ 'pagepointer' ];
	
	$display_pagepointer = '';
	if( isset( $settings[ 'display_pagepointer' ] ) )
		$display_pagepointer = $settings[ 'display_pagepointer' ];
	
	$user_cert = array();
	if( isset( $settings[ 'user_cert' ] ) )
		$user_cert = $settings[ 'user_cert' ];
	
	$database_connection = '';
	if( isset( $settings[ 'database_connection' ] ) )
		$database_connection = $settings[ 'database_connection' ];
		
	$database_name = '';
	if( isset( $settings[ 'database_name' ] ) )
		$database_name = $settings[ 'database_name' ];
		
	$classname = '';
	if( isset( $settings[ 'classname' ] ) )
		$classname = $settings[ 'classname' ];
	
	$action = '';
	if( isset( $settings[ 'action' ] ) )
		$action = $settings[ 'action' ];
		
	$language = '';
	if( isset( $settings[ 'language' ] ) )
		$language = $settings[ 'language' ];
		
	//Check for Permission
	//echo permission($userid,$action,$classname,$action,$database_connection,$database_name);
	if( permission( $user_cert , $action , $classname , $database_connection , $database_name ) || ( isset( $settings[ 'skip_authentication' ] ) && $settings[ 'skip_authentication' ] ) ){
		
		$actual_name_of_class = 'c'.ucwords($classname);
		
		$module = new $actual_name_of_class();
		
		$module->class_settings = array(
			'database_connection' => $database_connection,
			'database_name' => $database_name,
			'calling_page' => $pagepointer,
			'calling_page_display' => $display_pagepointer,
			
			'user_id' => $user_cert['id'],
			'user_full_name' => $user_cert['fname'] . ' ' . $user_cert['lname'],
			'user_fname' => $user_cert['fname'],
			'user_lname' => $user_cert['lname'],
			'user_email' => $user_cert['email'],
			'priv_id' => $user_cert['privilege'],
			'verification_status' => $user_cert[ 'verification_status' ],
			'remote_user_id' => $user_cert[ 'remote_user_id' ],
			
			'action_to_perform' => $action,
			
			'language' => $language,
		);
		
		$data = $module->$classname();
		
		//CHECK FOR SEARCH QUERY
		$sq = md5('search_query'.$_SESSION['key']);
		if( isset($_SESSION[$sq][$classname]['query']) && $_SESSION[$sq][$classname]['query'] ){
			$data['search_query'] = $_SESSION[$sq][$classname]['query'];
		}
		
		if( is_array( $data ) ){
			return json_encode($data);
		}else{
			return $data;
		}
	}else{
		
		$error['typ'] = 'serror';
		$error['err'] = 'Restricted Access';
		$error['msg'] = 'Access denied';
		$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
			$error['html'] .= '<h3>Restricted Access</h3>';
			$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
		$error['html'] .= '</div>';
		
		return json_encode($error);
	}
}

	//Check if Current User Permitted to perform certain actions
	function permission( $user_cert , $action , $classname , $database_connection , $database_name ){
		//ENSURE YOU HAS ACCESS TO THE SELECTED FUNCTION
		return 1;
		
		$table = 'functions';
		$f_id = '';
		
		$function_to_bypass['class'][0] = 'modules';
		$function_to_bypass['action'][0] = 'display';
		
		$function_to_bypass['class'][1] = 'functions';
		$function_to_bypass['action'][1] = 'display';
		
		$function_to_bypass['class'][2] = 'messages';
		$function_to_bypass['action'][2] = 'messages';
		
		$function_to_bypass['class'][3] = 'authentication';
		$function_to_bypass['action'][3] = 'users_login_process';
		
		$function_to_bypass['class'][4] = 'mypdf';
		$function_to_bypass['action'][4] = 'generate_pdf';
		
		$function_to_bypass['class'][5] = 'search';
		$function_to_bypass['action'][5] = 'search';
		
		$function_to_bypass['class'][6] = 'search';
		$function_to_bypass['action'][6] = 'perform_search';
		
		$function_to_bypass['class'][7] = 'search';
		$function_to_bypass['action'][7] = 'clear_search';
		
		$function_to_bypass['class'][8] = 'column_toggle';
		$function_to_bypass['action'][8] = 'column_toggle';
		
		$function_to_bypass['class'][9] = 'appsettings';
		$function_to_bypass['action'][9] = 'get_appsettings';
		
		$function_to_bypass['class'][10] = 'product';
		$function_to_bypass['action'][10] = 'back_to_products_button';
		
		$function_to_bypass['class'][11] = 'data_synchronization';
		$function_to_bypass['action'][11] = 'get_data_to_push';
		
		$function_to_bypass['class'][12] = 'myexcel';
		$function_to_bypass['action'][12] = 'import_excel_table';
		
		$function_to_bypass['class'][13] = 'myexcel';
		$function_to_bypass['action'][13] = 'save_imported_excel_data';
		
		$function_to_bypass['class'][14] = 'myexcel';
		$function_to_bypass['action'][14] = 'generate_excel';
		
		//CHECK FOR PASS CLASS
		for($x=0; $x<(count($function_to_bypass['class'])); $x++){
			if($function_to_bypass['class'][$x]==$classname && $function_to_bypass['action'][$x]==$action){
				return 1;
			}
		}
		
		//1. Get function id
		$query = "SELECT * FROM `".$database_name."`.`".$table."` WHERE `functions004`='".$classname."' AND `functions003`='".$action."' AND `record_status`='1'";
		
		$query_settings = array(
			'database'=>$database_name,
			'connect'=>$database_connection,
			'query'=>$query,
			'query_type'=>'SELECT',
			'set_memcache'=>1,
			'tables'=>array($table),
		);
		/***********************/
	
		$result_of_sql_query = execute_sql_query($query_settings);
		
		if(isset($result_of_sql_query) && is_array($result_of_sql_query) && isset($result_of_sql_query[0])){
			$a = $result_of_sql_query[0];
			
			//Set function id
			$f_id = $a['id'];
		}
		
		//2. Test function id against users accessible functions;
		
		if($f_id){
			if( isset ($user_cert['functions']) ){
				$f_arr = explode(':::',$user_cert['functions']);
				if(in_array($f_id, $f_arr) || $user_cert['functions']=='universal'){
					return $f_id;
				}
			}
		}
		return 0;
	}
	
	//Record an action in the audit trail
	function auditor( $pagepointer , $userpriv , $user , $mail , $database_connection , $database_names , $user_action , $table , $comment ){
		$module = new cAudit();
		$module->database_connection = $database_connection;
		$module->database_name = $database_names;
		$module->calling_page = $pagepointer;
		
		$module->user = $user;
		$module->user_mail = $mail;
		$module->priv_id = $userpriv;
		
		$module->user_action = $user_action;
		$module->table = $table;
		$module->comment = $comment;
		
		$module->action_to_perform = 'record';
		
		return $module->audit();
	}
	
	//CONVERT KEY VALUE PAIR OF AN ARRAY INTO TWO ARRAYS FOR DROP DOWN LIST BOX
	function convert_array_to_key_value_pair_for_selectbox( $function_name = '' ){
		$key = array();
		$val = array();
		if( function_exists( $function_name ) ){
		
			$array = $function_name();
			
			foreach($array as $array_key => $array_value){
				$key[] = $array_key;
				$val[] = $array_value;
			}
			
			return array($key,$val);
			
		}
	}
	
	//Returns the properties of a database field name to be used in form generation
	function get_form_field_type( $fields_of_database_table ){
		$fields_of_database_tables = array(
			'form_type' => 0,
			'field_id' => '',
			'view' => 0,
		);
		
		//1. Get Form Field Value
		$data = explode('_DT',$fields_of_database_table);
		
		//2. Return Form Field Value
		if(isset($data[0])){
			$fields_of_database_tables['field_id'] = $data[0];
		}
		if(isset($data[1])){
			$fields_of_database_tables['form_type'] = $data[1];
		}
		if(isset($data[3])){
			$fields_of_database_tables['view'] = $data[3];
		}
		
		return $fields_of_database_tables;
	}
	
	//Transform input data from search field of datatables to human readable format
	function transform_search_value( $search_value, $form_field_dt, $table ){
		/*
		 *Transform input data from search field of datatables to understandable database format
		*/
		$search_value = trim(strtolower($search_value));
		
		$transformed_dt = 'okpanachiogbuitepu';
		if($form_field_dt['form_field']){
			//Check data type
			switch($form_field_dt['form_field']){
			case "select":
				//Get Corresponding Array
				//Get options function name
				$option_function_name = $form_field_dt['form_field_options'];
				
				if(function_exists($option_function_name)){
					
					$options = $option_function_name();
						
					if(isset($options) && is_array($options)){
						foreach($options as $k_opt => $v_opt){
							if($search_value==trim(strtolower($v_opt))){
								$transformed_dt = $k_opt;
							}
						}
					}
				}
			break;
			case "date":
				//Transform date
				$search_value = explode('-',$search_value);
				
				if(is_array($search_value) && count($search_value)==3){
					$day = 1;
					$month = 'jan';
					$year = date("Y");
					
					if(isset($search_value[0])){
						$day = $search_value[0];
					}else{
						//Set Session Variable for Timeline of All Day of Month
						
					}
					
					if(isset($search_value[1])){
						$month = $search_value[1];
					}else{
						//Set Session Variable for Timeline of All Months of Year
						
					}
					
					if(isset($search_value[2])){
						$year = $search_value[2];
					}else{
						//Set Session Variable for Timeline of All Years
						
					}
					$mon = array(
						'jan' => 1,
						'feb' => 2,
						'mar' => 3,
						'apr' => 4,
						'may' => 5,
						'jun' => 6,
						'jul' => 7,
						'aug' => 8,
						'sep' => 9,
						'oct' => 10,
						'nov' => 11,
						'dec' => 12,
					);
					
					//COMPILE DATE
					if(isset($mon[$month]))
						$transformed_dt = mktime(0,0,0,$mon[$month],$day,$year);
				}
			break;
			}
		}
		return $transformed_dt;
	}
	
	//Execute a SQL Query
	function execute_sql_query( $settings = array() ){
		$cache_table_key = 'database_tables';
		
		//Uncomment to enable caching of queries result
		if( isset( $settings['set_memcache'] ) && $settings['set_memcache'] ){
			if( ( isset($_POST['app_memcache'] ) && $_POST['app_memcache'] ) ){
				$settings['set_memcache'] = $_POST['app_memcache'];
			}else{
				unset( $settings['set_memcache'] );
			}
		}
		
		//10 Hours Expiry Time
		$cache_time = 3600*10;
		
		//Universal SQl QUERY FUNCTION
		if(isset($settings['database']) && isset($settings['connect']) && isset($settings['query']) && isset($settings['query_type'])){
			//Check Query Type
			switch($settings['query_type']){
			case "SELECT":
			
				//Get Query Key
				$cache_key = md5($settings['query']);
				
				//Check if Memcache is turned on
				if(isset($settings['set_memcache']) && $settings['set_memcache'] ){
					
					//Check if Cache Exists
					
					//$settings['set_memcache']->delete($cache_key);
					
					$array = $settings['set_memcache']->get($cache_key);
					if(isset($array) && is_array($array)){
						//Return Cached Data
						//$array[0]['type'] = 'memcache';
						//echo 'memcache';
						return $array;
					}
				}
				
				//Clean SQL QUERY
				$result_of_sql_query = mysql_query( $settings['query'] , $settings['connect'] );
				
				if (!$result_of_sql_query) {
					//$e = mysql_error($oracle_query);
					//echo $query;
					//trigger_error('Could not execute statement: '. $e['message'], E_USER_ERROR);
				}

				//$result_of_sql_query = mysql_query($settings['query'],$settings['connect']);
				if($result_of_sql_query){
					
					$array = array();
					//, OCI_ASSOC+OCI_RETURN_NULLS
					//OCI_NUM
					//while (($row = mysql_fetch_array( $result_of_sql_query )) != false) {
                    while (($row = mysql_fetch_assoc( $result_of_sql_query )) != false) {
						$array[] = $row;
					}
					
					
					//Check if Memcache is turned on
					if(isset($settings['set_memcache']) && $settings['set_memcache']){
						
						if(isset($settings['set_memcache_time']) && $settings['set_memcache_time']){
							$cache_time = $settings['set_memcache_time'];
						}
						
						//Cache Query Result
						$settings['set_memcache']->set($cache_key,$array,$cache_time);
						
					
						//Update Cached Tables Array
						if(isset($settings['tables']) && is_array($settings['tables'])){
							//Get Array of All Cached Tables Keys
							$table_keys = $settings['set_memcache']->get($cache_table_key);
							
							if(is_array($table_keys)){
								foreach($settings['tables'] as $table){
									$table_keys[ strtolower($table) ][ $cache_key ] = true;
								}
							}else{
								foreach($settings['tables'] as $table){
									$table_keys[ strtolower($table) ][ $cache_key ] = true;
								}
							}
							
							//Update Table Keys
							$settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
						}
						
						//$settings['set_memcache']->delete($cache_table_key);	//Set for two hours
						//$settings['set_memcache']->delete($cache_key);	//Set for two hours
					}
					//echo 'database';
					//$array[0]['type'] = 'database';
					
					return $array;
				}
			break;
			case "INSERT":
			case "DELETE":
			case "UPDATE":
				
				$result_of_sql_query = mysql_query( $settings['query'] , $settings['connect'] );
				
				if (!$result_of_sql_query) {
					//$e = mysql_error($oracle_query);
					//trigger_error('Could not execute statement: '. $e['message'], E_USER_ERROR);
				}else{
					
					//Clear Cache / Temporary Session 
					if( isset( $settings['tables'] ) && is_array( $settings['tables'] ) ){
						
						//Clear Session Variable of Options
						foreach( $settings['tables'] as $table_name){
						
							$table_name_in_lower_case = strtolower($table_name); 
							
							switch( $table_name_in_lower_case ){
							case 'functions':
							case 'functions_tip':
							case 'modules':
							case 'users_role':
							case 'site_users':
							case 'users':
							case 'access_roles':
							case 'budgets':
								if( isset( $_SESSION['temp_storage'][$table_name_in_lower_case] ) )
									unset( $_SESSION['temp_storage'][$table_name_in_lower_case] );
								
								get_options_for_caching( $settings['database'] , $settings['connect'] , array( $table_name_in_lower_case ) );
							break;
							}
						}
						
						if(isset($settings['set_memcache']) && $settings['set_memcache']){
							//Get Array of All Cached Tables Keys
							$table_keys = $settings['set_memcache']->get($cache_table_key);
							
							if(is_array($table_keys)){
								//print_r($table_keys);
								
								foreach($settings['tables'] as $table_in_random_case){
									
									$table = strtolower($table_in_random_case); 
									
									if(isset($table_keys[$table]) && is_array($table_keys[$table])){
										
										//Clear All Cached Keys linked to updated table
										foreach($table_keys[$table] as $linked_cache_key => $linked_cache_value){
											
											//Delete Cache
											$settings['set_memcache']->delete($linked_cache_key);	
										}
										
										//Unset Table Key
										unset($table_keys[$table]);
									}
								}
							}
							
							//Update Table Keys
							$settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
						}
					}
					
					return array('success'=>1);
				}
			break;
			case 'DESCRIBE':
				
				$cache_key = md5($settings['query']);
				
				if(isset($settings['set_memcache']) && $settings['set_memcache'])
					$settings['set_memcache']->delete($cache_key);
				
				$result_of_sql_query = mysql_query( $settings['query'] , $settings['connect'] );
				
				if (!$result_of_sql_query) {
					//$e = mysql_error($oracle_query);
					//trigger_error('Could not execute statement: '. $e['message'], E_USER_ERROR);
				}

				$array = array();
				while (($row = mysql_fetch_array( $result_of_sql_query )) != false) {
					$array[] = $row;
				}
				
				return $array;
			break;
			}
		}
		return 0;
	}
	
	//Cache Special Values to Files
	function set_cache_for_special_values( $settings = array() ){
		if( ( isset( $_POST['app_memcache'] ) && $_POST['app_memcache'] ) ){
			//3 days
			$cache_time = 3600*3*24;
			
			if( isset( $settings['cache_time'] ) && $settings['cache_time'] ){
				switch( $settings['cache_time'] ){
				case 'mini-time':
					//1 day
					$cache_time = 3600*1*24;
				break;
                case 'load-time':
					//5 minutes
					$cache_time = 60 * 60;
                    $all_keys = get_cache_for_special_values( array('cache_key' => 'website-keys', 'permanent' => true ) );
                    if( ! is_array( $all_keys ) )$all_keys = array();
                    if( ! isset( $all_keys[ $settings['cache_key'] ] ) ){
                        $all_keys[ $settings['cache_key'] ] = $settings['cache_key'];
                        set_cache_for_special_values( array('cache_key' => 'website-keys', 'permanent' => true, 'cache_values' => $all_keys ) );
                    }
				break;
                case 'token-time':
					//10 minutes
					$cache_time = 60 * 10;
				break;
				}
			}
			
			$settings['set_memcache'] = $_POST['app_memcache'];
			
			$settings['set_memcache']->set( $settings['cache_key'] , $settings['cache_values'] , $cache_time );
			
			if( isset( $settings['permanent'] ) && $settings['permanent'] ){
				$dir = '';
				if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
					$dir = $settings['directory_name'] . '/';
					create_folder('' , $settings['set_memcache']::$path . '/' . $dir , '' );
				}
				
				$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
				file_put_contents( $filename , json_encode( $settings['cache_values'] ) );
			}
			
			return 1;
		}
		
		return 0;
	}
	
    function clear_load_time_cache(){
        $all_keys = get_cache_for_special_values( array('cache_key' => 'website-keys' ) );
        if( isset( $all_keys ) && is_array( $all_keys ) ){
            foreach( $all_keys as $k => $v )clear_cache_for_special_values( array('cache_key' => $k ) );
            set_cache_for_special_values( array('cache_key' => 'website-keys', 'permanent' => true, 'cache_values' => array() ) );
        }
    }
    
    //cache_reload_urls
    function get_cache_reload_url( $key ){
        $key = str_replace('/','',$key);
        $array = array(
            'state_list' => array(
                'url' => '?action=state_list&todo=get_state_list&data=',
                'action' => 'state_list',
                'todo' => 'cache',
            ),
            'cities_list' => array(
                'url' => '?action=cities_list&todo=get_cities_list&data=',
                'action' => 'cities_list',
                'todo' => 'cache',
            ),
        );
        
        if( isset( $array[ $key ] ) && $array[ $key ] ){
            return $array[ $key ];
        }
    }
    
	//Cache Special Values to Files
	function get_cache_for_special_values( $settings = array() ){
		if( ( isset( $_POST['app_memcache'] ) && $_POST['app_memcache'] ) ){
			
            $p = get_project_data();
            $url = $p['domain_name'].'engine/php/ajax_request_processing_script.php';
            //$url = 'http://localhost/dev-1/engine/php/ajax_request_processing_script.php';
            //$url = './engine/php/ajax_request_processing_script.php';
            $data = '';
            if( isset( $settings['data'] ) && $settings['data'] ){
                $data = $settings['data'];
            }
            
			$settings['set_memcache'] = $_POST['app_memcache'];
			
			if( isset( $settings['permanent'] ) && $settings['permanent'] ){
				$dir = '';
				if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
					$dir = $settings['directory_name'] . '/';
				}
				
				$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
				if( file_exists( $filename ) ){
					return json_decode( file_get_contents( $filename ) , true );
				}else{
					//use file get content as fallback to recreate cache
                    //for permanent cached values
                    
                    if( $dir ){
                        switch( $dir ){
                        case 'state_list':
                        case 'state_list/':
                        case 'cities_list/':
                        case 'cities_list':
                            $d = get_cache_reload_url( $dir );
                            if( isset( $d['url'] ) && isset( $d['action'] ) && isset( $d['todo'] ) ){
                                $url .= $d['url'].$data;
                                
                                if( isset( $_SESSION['reuse_settings'] ) && $_SESSION['reuse_settings'] ){
                                    $_SESSION['reuse_settings']['action'] = $d['todo'];
                                    $_SESSION['reuse_settings']['classname'] = $d['action'];
                                    reuse_class( $_SESSION['reuse_settings'] );
                                }
                                //file_get_contents( $url );
                            }
                        break;  
                        }
                        
                        $filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
                        if( file_exists( $filename ) ){
                            return json_decode( file_get_contents( $filename ) , true );
                        }
                    }
				}
			}
			$returning = $settings['set_memcache']->get( $settings['cache_key'] );
			
			if( empty( $returning ) ){
				//use file get content as fallback to recreate cache
				switch( $settings['cache_key'] ){
				case 'categories':
					$url .= '?action=categories&todo=get_categories';
				break;
                case 'site_users-all-users-countries':
					$url .= '?action=site_users&todo=get_all_users_countries';
                    file_get_contents( $url );
				break;
				}
				//file_get_contents( $url );
			}else{
				return $returning;
			}
			
			return $settings['set_memcache']->get( $settings['cache_key'] );
		}
		
		return 0;
	}
	
	//Cache Special Values to Files
	function clear_cache_for_special_values( $settings = array() ){
		if( ( isset( $_POST['app_memcache'] ) && $_POST['app_memcache'] ) ){
			
			$settings['set_memcache'] = $_POST['app_memcache'];
			
			if( isset( $settings['permanent'] ) && $settings['permanent'] ){
				$dir = '';
				if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
					$dir = $settings['directory_name'] . '/';
				}
				
				$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
				if( file_exists( $filename ) ){
					unlink($filename);
				}
				
			}
			
			return $settings['set_memcache']->delete( $settings['cache_key'] );
		}
		
		return 0;
	}
	
	function get_divisional_report_key(){
		return 'divisional-reports';
	}
	
	//Convert money from one currency to another
	function currency_converter( $amount, $from_units, $to_units, $currency_conversion_rate = 0 ){
		/*
		 *Converts currency from one unit to another
		*/
		$value_of_one_usd_in_naira = get_naira_equivalent_of_one_us_dollar();
		
		if(doubleval($currency_conversion_rate)){
			$value_of_one_usd_in_naira = $currency_conversion_rate;
		}
		
		$values_to_be_inserted_into_each_field = array(
			//Converting from usd to [usd, ngn, million usd, million ngn]
			'usd' => array(
				'usd'=>1,
				'ngn'=>$value_of_one_usd_in_naira,
				'million_usd'=>0.000001,
				'million_ngn'=>($value_of_one_usd_in_naira/1000000),
			),
			//Converting from ngn to [usd, ngn, million usd, million ngn]
			'ngn' => array(
				'ngn'=>1,
				'usd'=>(1/$value_of_one_usd_in_naira),
				'million_usd'=>((1/$value_of_one_usd_in_naira)/1000000),
				'million_ngn'=>0.000001,
			),
			//Converting from millionngn to [usd, ngn, million usd, million ngn]
			'million_ngn' => array(
				'ngn'=>1000000,
				'usd'=>((1/$value_of_one_usd_in_naira)*1000000),
				'million_usd'=>(1/$value_of_one_usd_in_naira),
				'million_ngn'=>1,
			),
			//Converting from millionusd to [usd, ngn, million usd, million ngn]
			'million_usd' => array(
				'ngn'=>($value_of_one_usd_in_naira * 1000000),
				'usd'=>1000000,
				'million_usd'=>1,
				'million_ngn'=>$value_of_one_usd_in_naira,
			),
		);
		//Check Base Unit
		if($amount && is_numeric($amount)){
			$from_units = strtolower(str_replace(" ","_",$from_units));
			$to_units = strtolower(str_replace(" ","_",$to_units));
			if(isset($values_to_be_inserted_into_each_field[$from_units]) && isset($values_to_be_inserted_into_each_field[$from_units][$to_units])){
				$amount = $amount * $values_to_be_inserted_into_each_field[$from_units][$to_units];
			}
		}
		
		if( $amount > 0 ){
			return round($amount , 2);
		}
		
		return $amount;
	}
	
	//Returns HTML for Select Combo Box that would be used in selecting 
	//units of physical quantities for conversion
	function units_select_box( $type , $defaultunits = "" ){
		/*
		 *Prepare Combo box that would be used in selecting units
		*/
		$returning_html_data = '';
		
		switch($type){
		case "volume":
			//Get all units of gas volumes
			$units = get_gas_volume_units();
		break;
		case "currency":
			//Get all units of currency
			$units = get_currency();
		break;
		case "kvalue":
			//Get all units of calorific values
			$units = get_calorific_units();
		break;
		case "time":
			//Get all units of time
			$units = get_time_units();
		break;
		case "currency_per_unit_kvalue":
			//Get all cuurency per unit kvalue
			$units = get_currencies_per_unit_kvalue();
		break;
		case "pressure":
			//Get all cuurency per unit kvalue
			$units = get_gas_pressure_units();
		break;
		case "volume_per_day":
			//Get all volume per day units
			$units = get_gas_volume_per_day_units();
		break;
		case "heating_value":
			//Get all heating content units
			$units = get_heating_value_units();
		break;
		}
		
		
		$un = md5('units'.$_SESSION['key']);
		
		//Check if user has selected an appropriate unit
		if(isset($_GET['selected_unit']) && $_GET['selected_unit'] && isset($_GET['physical_quantity']) && $_GET['physical_quantity']==$type){
			$defaultunits = $units[$_GET['selected_unit']];
			
			//Update Default Unit Stored in Session
			$_SESSION[$un][$_GET['physical_quantity']] = $defaultunits;
		}
		
		//Check for Stored Units in Session Variable
		if(isset($_SESSION[$un][$type]) && $_SESSION[$un][$type]){
			$defaultunits = $_SESSION[$un][$type];
		}
		
		$returning_html_data .= '<select name="'.$type.'" class="units-select-box" data-inline="true" data-mini="true" data-corners="false">';
			foreach($units as $k_unit => $v_unit){
				if(strtolower($defaultunits)==strtolower($v_unit)){
					$returning_html_data .= '<option value="'.$k_unit.'" selected="selected">'.$v_unit.'</option>';
				}else{
					$returning_html_data .= '<option value="'.$k_unit.'">'.$v_unit.'</option>';
				}
			}
		$returning_html_data .= '</select>';
		
		return $returning_html_data;
	}
	
	//Returns HTML for Select Combo Box that would be used in selecting 
	//custom view options
	function get_custom_view_options_select_box( $settings = array() ){
		/*
		 *Prepare Combo box that would be used in selecting custom view options
		*/
		$un = md5('viewport'.$_SESSION['key']);
		$default_view_port = '';
		
		//Check if user has selected an appropriate unit
		if(isset($_GET['viewport_selected_view']) && $_GET['viewport_selected_view'] && isset( $_GET['viewport_class_name'] ) && $_GET['viewport_class_name'] ){
			$default_view_port =  $_GET['viewport_selected_view'];
			
			//Update Default Unit Stored in Session
			$_SESSION[$un][ $_GET['viewport_class_name'] ] = $default_view_port;
		}
			
		if( isset( $settings['class_name'] ) && isset( $settings['option_list'] ) && is_array( $settings['option_list'] ) ){
			$returning_html_data = '';
			
			//Check for Stored Units in Session Variable
			if(isset($_SESSION[$un][ $settings['class_name'] ]) && $_SESSION[$un][ $settings['class_name'] ]){
				$default_view_port = $_SESSION[$un][ $settings['class_name'] ];
			}
			
			$returning_html_data .= '<select name="'.$settings['class_name'].'" id="view-options-select-box" data-inline="true" data-mini="true" data-corners="false" data-theme="f">';
				foreach( $settings['option_list'] as $key => $value ){
					if( strtolower( $default_view_port ) == strtolower( $key ) ){
						$returning_html_data .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
					}else{
						$returning_html_data .= '<option value="'.$key.'">'.$value.'</option>';
					}
				}
			$returning_html_data .= '</select>';
			
			return $returning_html_data;
		}
	}
	
	//Returns human readable form of advance search query
	function convert_to_highlevel_query( $low_level_query , $table ){
		$high_level_query = '';
		
		
		//Store value of last field
		$last_field = array();
		
		//Get values of table fields
		$func = $table;
		if(function_exists($func))
			$form_label = $func();
		else
			$form_label = array();
		
		//ADD CREATION AND MODIFIED DATE FIELD
		$form_label['MODIFIED_DATE'] = 'Modified Date';
		$form_label['CREATION_DATE'] = 'Creation Date';
		
		if($low_level_query){
			//1. Remove all status conditions
			$high_level_query = str_replace(" AND `".$table."`.`record_status`='1'","",$low_level_query);
			$high_level_query = str_replace("`".$table."`.","",$high_level_query);
			
			//2. Remove all divisions
			$high_level_query = str_replace("/1","",$high_level_query);
			
			//3. Remove all `
			$high_level_query = str_replace("`","",$high_level_query);
			
			//4. Break Statement into words
			$words = explode(" ",$high_level_query);
			$high_level_query = '';
			if(is_array($words)){
				//5. Loop via all words and search for words with _dt in them
				foreach($words as & $word){
					if (preg_match("/".$table."/", $word)) {
						//Get Global Value for Field Names
						//$fields_of_database_table = get_form_field_type($word);
						
						if( isset( $form_label[$word] ) ){
							//Set form type of last field
							$last_field = $form_label[$word];
							
							//Check for label
							$word =  '<label>'.$form_label[$word][ 'field_label' ].'</label>';
						}
					}
					
					if( isset( $last_field[ 'form_field' ] ) ){
						//Check for Combo Value
						switch($last_field[ 'form_field' ]){
						case 'date':
							//Convert to date
							$clean_word = str_replace("'","",$word);
							$clean_word = str_replace("%","",$clean_word);
							if(is_numeric($clean_word)){
								$word = "'".date("d-M-Y",($clean_word/1))."'";
								
								//Clear Last Field
								$last_field = array();
							}
						break;
						case 'select':
							//Get options function name
							$option_function_name = $last_field[ 'form_field_options' ];
							
							if( function_exists($option_function_name) ){
								
								$options = $option_function_name();
								
								$clean_word = str_replace("'","",$word);
								$clean_word = str_replace("%","",$clean_word);
								
								if(isset($options[$clean_word])){
									$word = "'".ucwords($options[$clean_word])."'";	
									
									//Clear Last Field
									$last_field = array();
								}
								
							}
						break;
						case 'multi-select':
							//Get options function name
							$option_function_name = $last_field[ 'form_field_options' ];
							
							if(function_exists($option_function_name)){
								
								$options = $option_function_name();
								
								$cleaned = str_replace("'","",$word);
								$cleaned = str_replace("%","",$cleaned);
								$clean_words = explode(":::",$cleaned);
								$newword = '';
								foreach($clean_words as $clean_word){
									if(isset($options[$clean_word])){
										if($newword)$newword .= ", '".ucwords($options[$clean_word])."'";	
										else $newword = "'".ucwords($options[$clean_word])."'";
										
										//Clear Last Field
										$last_field = array();
									}
								}
								
								if($newword)
									$word = $newword;
							}
						break;
						}
					}
					
					if (preg_match("/REGEXP/", $word)) {
						$word = 'CONTAINS';
					}
				}
			}
			
			$high_level_query = '<label>Filtered Records: </label>'.implode(" ",$words);
		}
		
		return $high_level_query;
	}
	
	//Returns HTML of checkboxes for use in selecting columns to be displayed in dataTables
	function get_column_toggler_checkboxes( $column_name , $table , $module , $field_details ){
		//Determine Current State of Column
		//Toggle Column
		
		if( isset( $field_details['field_label'] ) && $field_details['field_label'] )
			$display_label = $field_details['field_label'];
		else
			$display_label = ucwords( str_replace( '_', ' ', $column_name ) );
		
		$sq = md5('column_toggle'.$_SESSION['key']);
		
		//Hide Columns by default
		if( ! ( isset( $field_details['default_appearance_in_table_fields'] ) && $field_details['default_appearance_in_table_fields'] == 'show' ) ){
			$_SESSION[$sq][$table][$column_name] = 1;
		}
		
		if( isset($_SESSION[$sq][$table][$column_name]) ){
			$column_state = '';
		}else{
			$column_state = 'checked="checked"';
		}
		
		//Return Checkboxes of Columns to Toggle Display Status
		return '<li><label data-corners="false" class="checkbox"><input type="checkbox" name="'.$column_name.'" function-id="column_toggle" function-class="column_toggle" column-toggle-table="'.$table.'" function-name="column_toggle" module-name="'.$module.'" module-id="'.$module.'" '.$column_state.'>'.$display_label.'</label></li>';
	}
	
	//Sets Session variable that would be used in caching list of values used in populating select
	
	function get_external_options_for_caching( $settings = array() ){
		$project_data = get_project_data();
		
		if( isset( $settings[ 'request' ] ) && $settings[ 'request' ] ){
			
			$json = file_get_contents( $project_data['remote_server_request_url'] . '?request='.$settings[ 'request' ] );
			
			if($json){
				$returned_array = json_decode($json, true);
				
				asort($returned_array);
				
				//CACHE ALL OPTIONS LOADED FROM DATABASE
				$_SESSION['temp_storage'][ $settings[ 'request' ] ][ $settings[ 'request' ] ] = $returned_array;
				
			}
			
		}
		
	}
	
	//combo boxes that are populated from the database tables
	function get_options_for_caching( $database_names , $database_connection , $selected = "all" ){
		//CACHE ALL OPTIONS LOADED FROM DATABASE
		
		$options = array(
			array(
				'table'=>'functions',
				'field'=>'accessible_functions',
				'value'=>'functions001',
			),
			array(
				'table'=>'budgets',
				'field'=>'get_budgets',
				'value'=>'budgets001',
			),
			array(
				'table'=>'modules',
				'field'=>'modules_in_application',
				'value'=>'modules001',
			),
			array(
				'table'=>'access_roles',
				'field'=>'access_roles',
				'value'=>'access_roles001',
			),
			array(
				'table'=>'users',
				'field'=>'users_names',
				'value'=>'users001',
			),
			array(
				'table'=>'site_users',
				'field'=>'users_names',
				'value'=>'site_users001',
			),
		);
		
		foreach($options as $option){
			if((is_array($selected) && in_array($option['table'],$selected)) || $selected=='all'){
				//GET UNIQUE OPTIONS FOR CACHING
				
				//Prepare Database Query
				switch($option['table']){
				default:
					$query = "SELECT * FROM `".$database_names."`.`".$option['table']."` WHERE `".$option['table']."`.`record_status`='1'";
				break;
				}
				
				$query_settings = array(
					'database'=>$database_names,
					'connect'=>$database_connection,
					'query'=>$query,
					'query_type'=>'SELECT',
					'set_memcache'=>1,
					'tables'=>array( $option['table'] ),
				);
				$sql_result = execute_sql_query( $query_settings );
				
				if(isset($sql_result) && is_array($sql_result)){
					
					foreach($sql_result as $k_sql => $v_sql){
						if(is_array($v_sql)){
							
							switch($option['table']){
							case "users":
							case "site_users":
								//cache session value used for select box options
								$_SESSION['temp_storage'][ $option['table'] ][$option['field']][$v_sql['id']] = strtoupper(substr($v_sql[ $option['table'].'001'],0,1)).'. '.$v_sql[ $option['table'].'002'];
								
								//cache session value used for select box options
								$_SESSION['temp_storage'][ $option['table'] ]['users_names_full'][$v_sql['id']] = $v_sql[ $option['table'].'001'].' '.$v_sql[ $option['table'].'002'];
								
								//cache session value used for select box options
								if( $option['table'] == "site_users" ){
									$_SESSION['temp_storage'][ $option['table'] ]['users_email_addresses'][$v_sql['id']] = $v_sql[ $option['table'].'004'];
								}else{
									$_SESSION['temp_storage'][ $option['table'] ]['users_email_addresses'][$v_sql['id']] = $v_sql[ $option['table'].'003'];
								}
							break;
							case "budgets":
								//cache session value used for select box options
								$_SESSION['temp_storage'][ $option['table'] ][ $option['field'] ][$v_sql['id']] = strtoupper( $v_sql[ 'budgets001' ] ) . '-' . $v_sql[ 'budgets002' ] . '-' . get_select_option_value( array( 'id' => $v_sql[ 'budgets003' ], 'function_name' => 'get_cash_call_types' ) ). '-' . $v_sql[ 'budgets004' ];
							break;
							default:
								//cache session value used for select box options
								$_SESSION['temp_storage'][$option['field']][$v_sql['id']] = $v_sql[$option['value']];
								
								$_SESSION['temp_storage'][ $option['table'] ][$option['field']][$v_sql['id']] = $v_sql[$option['value']];
								
							break;
							}
						}
					}
					
					
				}
				
			}
			
		}
	}
	
	//Returns Application Password Salter
	function get_websalter(){
		//Application Salter
		//return '10839hxec,.#02<@d439adsaSD05a7dcNSCIVue7^%FXtr^$£"£*(&"!£244SDFF##';
		return '10839h#<@ddcNSCIVu';
	}
	
	//Returns the Gas Helix Project Settings
	function get_project_settings(){
		$project['company_name'] = 'Zidoff E-commerce Limited';
		
		$project['project_title'] = 'Zidoff.com';
		
		//$project['slogan'] = '...Africa\'s Global Marketplace';
		$project['slogan'] = '...Your Global Marketplace';
		
		if( isset( $_SESSION['project_slogan'] ) && $_SESSION['project_slogan'] )
			$project['slogan'] = $_SESSION['project_slogan'];
			
		$project['project_name'] = 'zidoff.com';
		$project['domain_name'] = 'http://www.zidoff.com/';
		//$project['domain_name'] = 'http://www.zidoff.com/test18/public_html';
		$project['domain_name_only'] = 'www.zidoff.com';
		
		$project['remote_server_request_url'] = 'http://inventory.challydoff.com/php/inventory_manager_processor.php';
		
		//comma-sperated list or zidoff stores id's
		$project['zidoff_store_id'] = '1400747106_366';

		$project['street_address'] = 'B24 TJ 1406 Plaza, Bozoum Close, Wuse II';
		$project['full_street_address'] = 'B24 TJ 1406 Plaza,<br />Bozoum Close,<br />Wuse II, Abuja, 900288';
		$project['city'] = 'Abuja';
		$project['state'] = 'F.C.T';
		$project['country'] = 'Nigeria';
		
		//$project['phone'] = '+234 700-7467-943633<br />+234 0700-SHOP-ZIDOFF';
		$project['phone'] = '+234 700-SHOP-ZIDOFF<br />+234 700-7467-943633';
		//$project['support_line'] = '+234 700-SHOP-ZIDOFF<br />+234 700-7467-943633';
		$project['support_line'] = '+234 814-9906-150';
		
		$project['admin_email'] = 'zlog@challydoff.com';
		$project['accounts_email'] = 'zmail@challydoff.com';
		
		$project['email'] = 'info@zidoff.com';
		$project['support_email'] = 'support@zidoff.com';
		
		$project['delivery_email'] = 'info@challydoff.com';
		
		$project['admin_login_form_passkey'] = '19881011988';
		
		$project['facebook_link'] = 'https://www.facebook.com/pages/Zidoffcom/524269607644769';
		$project['twitter_link'] = 'https://twitter.com/ZidoffGlobal';
		$project['googleplus_link'] = 'https://plus.google.com/u/0/106125070712614311944/posts';
		
		$project['dhl'] = array(
			'account_number' => array(
				0 => '365091693', //Local Account Number / Export [Outbound]
				1 => '955260289', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('nginquiry@dhl.com'),
					'full_name' => array('DHL'),
					'id' => array('dhl1'),
				),
				1 => array(	//International
					'email' => array('ngtcollect@dhl.com'),
					'full_name' => array('DHL'),
					'id' => array('dhl1'),
				),
			),
		);
		
		$project['nipost'] = array(
			'account_number' => array(
				0 => 'xxx', //Local Account Number / Export [Outbound]
				1 => 'xxx', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('yyy@nipost.com'),
					'full_name' => array('NIPOST'),
					'id' => array('nipost1'),
				),
				1 => array(	//International
					'email' => array('yyy@nipost.com'),
					'full_name' => array('NIPOST'),
					'id' => array('nipost1'),
				),
			),
		);
		
		$project['fedex'] = array(
			'account_number' => array(
				0 => 'none', //Local Account Number / Export [Outbound]
				1 => 'none', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('none'),
					'full_name' => array('FEDEX'),
					'id' => array('fedex1'),
				),
				1 => array(	//International
					'email' => array('none'),
					'full_name' => array('FEDEX'),
					'id' => array('fedex1'),
				),
			),
		);
		
		$project['custom_shipping'] = array(
			'account_number' => array(
				0 => 'none', //Local Account Number / Export [Outbound]
				1 => 'none', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('none'),
					'full_name' => array('CUSTOM SHIPPING'),
					'id' => array('customshipping1'),
				),
				1 => array(	//International
					'email' => array('none'),
					'full_name' => array('CUSTOM SHIPPING'),
					'id' => array('customshipping1'),
				),
			),
		);
		$project['custom_shipping_free'] = $project['custom_shipping'];
		
		$project['china_post'] = array(
			'account_number' => array(
				0 => 'none', //Local Account Number / Export [Outbound]
				1 => 'none', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('none'),
					'full_name' => array('CHINA POST'),
					'id' => array('china_post'),
				),
				1 => array(	//International
					'email' => array('none'),
					'full_name' => array('CHINA POST'),
					'id' => array('china_post'),
				),
			),
		);
		
		$project['usps'] = array(
			'account_number' => array(
				0 => 'none', //Local Account Number / Export [Outbound]
				1 => 'none', //Import Account Number [Inbound]
			),
			'email_address' => array(
				0 => array(		//Local
					'email' => array('none'),
					'full_name' => array('USPS'),
					'id' => array('usps'),
				),
				1 => array(	//International
					'email' => array('none'),
					'full_name' => array('USPS'),
					'id' => array('usps'),
				),
			),
		);
		
		return $project;
	}
	
	//First Bank Project Data
	function get_first_bank_data(){
		$project['mercid'] = '06663';
		$project['currcode'] = array(
			'naira'=> 566, 
			'dollar'=> 840,
			'pound' => 826,
		);
		//$project['url'] = 'https://firstpaylink.firstbanknigeria.com/MerchantServices/MakePayment.aspx';
		$project['url'] = 'https://firstpaylink.firstbanknigeria.com:553/MerchantServices/MakePayment.aspx';
		
		$project['history_link'] = 'https://firstpaylink.firstbanknigeria.com:553/MerchantCustomerView/MerchantCustomerReport.aspx?mercID='.$project['mercid'].'&email=';
		
		$project['payment_status_url'] = 'https://firstpaylink.firstbanknigeria.com:553/MerchantServices/UpayTransactionStatus.ashx?MERCHANT_ID='.$project['mercid'].'&ORDER_ID=';
		
		return $project;
	}
	
	function get_fisrt_bank_payment_form($settings = array()){
		$h = '';
		$firstbank = get_first_bank_data();
		
        $method = 'POST';
        $action = $firstbank['url'];
        $payment_type = '';
        
        if( isset( $settings['payment_type'] ) )$payment_type = $settings['payment_type'];
        
        switch($payment_type){
        case 'wire_transfer':
            $o_id = '';
            if( isset($settings['actual_order_id']) )$o_id = $settings['actual_order_id'];
            $action = '?action=order&todo=wire_transfer_payment_info_page&record_id='.$o_id;
        break;
        case 'pay_on_delivery':
            $o_id = '';
            if( isset($settings['actual_order_id']) )$o_id = $settings['actual_order_id'];
            $action = '?action=order&todo=pay_on_delivery_payment_info_page&record_id='.$o_id;
        break;
        }
        
		if( isset($settings['total_total']) && isset($settings['order_id']) && isset($settings['first_bank_item_title']) && isset($settings['email']) && isset($settings['caption']) && isset( $settings['currency'] ) ){
			
            $amt = round( $settings['total_total'] , 2 );
            
            switch( $settings['currency'] ){
            case 'naira':
                $amt = convert_currency( $settings['total_total'] , 'from usd' , 1 );
            break;
            }
            
			$h .= '<form method="'.$method.'" id="upay_form" name="upay_form" action="'.$action.'" target="_top" class="skip-validation" data-ajax="false">';
				$h .= '<input type="hidden" name="mercId" value="'.$firstbank['mercid'].'">';
				$h .= '<input type="hidden" name="currCode" value="'.$firstbank['currcode'][ $settings['currency'] ].'">';
				$h .= '<input type="hidden" name="amt" value="'.$amt.'">';
				$h .= '<input type="hidden" name="orderId" value="'.$settings['order_id'].'">';
				$h .= '<input type="hidden" name="prod" value="'.strip_tags( $settings['first_bank_item_title'] ).'">';
				$h .= '<input type="hidden" name="email" value="'.$settings['email'].'">';
				$h .= '<input type="submit" name="submit" class="btn btn-primary pay-now" value="'.$settings['caption'].'">';
			$h .= '</form>';
		}
		
		return $h;
	}
	
	//Returns HTML data for Data Capture Form Titles
	function get_add_new_record_form_heading_title( $title = '' ){
		return '<h3>'.$title.'</h3>';
	}
	
	//Generate Token form Authenticating Form
	function generate_token( $settings = array() ){
		
		$token = md5(md5(get_new_id( $settings['table'] )));
		
		//Store duplicate value for comparison
		if( isset($_SESSION['key']) ){
			$frmtok = md5('form_token'.$_SESSION['key']);
			$_SESSION[$frmtok][] = $token;
			$_SESSION[$frmtok]['last'] = $token;
		}
		
		return $token;
	}

	function send_mail( $pagepointer = '../' , $email , $subject , $message , $headers ){
		//Write mail to tmp folder for reference
		$mail = '<?php echo "<h1>Its Confidential</h1>";'."\n\n";
			$mail .= '$email = "'.$email.'";'."\n\n";
			$mail .= '$subject = "'.$subject.'";'."\n\n";
			$mail .= '$message = '."'".$message."'".';'."\n\n";
			$mail .= '$headers = "'.$headers.'";'."\n\n";
			$mail .= '$timestamp = "'.date("U").'";'."\n\n";
			$mail .= 'echo "<br /><br /><br /><br /><br /><h6>Written by Patrick Ogbuitepu</h6>";'."\n\n";
		$mail .= '?>';		
		write_file('',$pagepointer.'tmp/sent_mails/'.$email.date("jS M Y H i").'.php',$mail);
		
		//Activate to send mail to email account when testing online
		//mail($email,$subject,$message, $headers);
	}
	
	function get_records_from_database(){
		
	}
	
	function get_select_option_value( $settings = array() ){
		if( isset( $settings[ 'id' ] ) && $settings[ 'id' ] && isset( $settings[ 'function_name' ] ) && $settings[ 'function_name' ] ){
			
			if( function_exists( $settings[ 'function_name' ] ) ){
				if( isset( $settings[ 'settings' ] ) && $settings[ 'settings' ] ){
					$primary_categories = $settings[ 'function_name' ]( $settings[ 'settings' ] );
				}else{
					$primary_categories = $settings[ 'function_name' ]();
				}
			
				if( isset( $primary_categories[ $settings[ 'id' ] ] ) ){
					return $primary_categories[ $settings[ 'id' ] ];
				}
			
			}
		}
		
		return 'not available';
	}
	
	function create_report_directory( $settings = array() ){
		
		$entity_directory = 'files';
		
		if( isset( $settings[ 'calling_page' ] ) && isset( $settings[ 'user_id' ] ) ){
			$directory = $settings[ 'calling_page' ] . $entity_directory . '/' . $settings[ 'user_id' ];
			$dir = create_folder('' , $directory , '' );
			
			//Create User Folder
			if( isset( $_POST[ 'current_module' ] ) && $_POST[ 'current_module' ] ){
				$directory = $directory . '/' . $_POST[ 'current_module' ];
				$dir = create_folder('' , $directory , '' );
			}
			
			//Check year
			$directory = $directory . '/' . date("Y");
			$dir = create_folder('' , $directory , '' );
			
			//Check month
			$directory = $directory . '/' . date("F");
			$dir = create_folder('' , $directory , '' );
			
			return $dir;
		}
		
	}
	
	function load_language_file( $settings ){
		if( isset( $settings['id'] ) && isset( $settings['pointer'] ) && isset( $settings['language'] ) ){
		
			if( file_exists( $settings['pointer'] . "locale/" . $settings['language'] . "/" . strtoupper( $settings['id'] ) . ".php" ) ){
				include $settings['pointer'] . "locale/" . $settings['language'] . "/" . strtoupper( $settings['id'] ) . ".php";
				return 1;
			}else{
				//load default language file
				return 0;
				//die("No language file");
			}
			
		}
	}
	
	function get_successful_authentication_url(){
		if( isset( $_SESSION[ 'successful_authentication_url'] ) ){
			$return = $_SESSION[ 'successful_authentication_url'];
			unset( $_SESSION[ 'successful_authentication_url'] );
			return $return;
		}
			
		return '?page=user-dashboard';
	}
	
	function set_successful_authentication_url( $url ){
		$_SESSION[ 'successful_authentication_url'] = $url;
	}
	
	function generatePassword($plength,$include_letters,$include_capitals,$include_numbers,$include_punctuation){

        // First we need to validate the argument that was given to this function
        // If need be, we will change it to a more appropriate value.
        if(!is_numeric($plength) || $plength <= 0)
        {
            $plength = 8;
        }
        if($plength > 32)
        {
            $plength = 32;
        }

        // This is the array of allowable characters.
                $chars = "";

                if ($include_letters == true) { $chars .= 'abcdefghijklmnopqrstuvwxyz'; }
                if ($include_capitals == true) { $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
                if ($include_numbers == true) { $chars .= '0123456789'; }
                if ($include_punctuation == true) { $chars .= '`¬£$%^&*()-_=+[{]};:@#~,<.>/?'; }

                // If nothing selected just display 0's
                if ($include_letters == false AND $include_capitals == false AND $include_numbers == false AND $include_punctuation == false) {
                    $chars .= '0';
                }

        // This is important:  we need to seed the random number generator
        mt_srand(microtime() * 1000000);

        // Now we simply generate a random string based on the length that was
        // requested in the function argument
		$pwd = '';
        for($i = 0; $i < $plength; $i++)
        {
            $key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars{$key};
        }

        // Finally to make it a bit more random, we switch some characters around
        for($i = 0; $i < $plength; $i++)
        {
            $key1 = mt_rand(0,strlen($pwd)-1);
            $key2 = mt_rand(0,strlen($pwd)-1);

            $tmp = $pwd{$key1};
            $pwd{$key1} = $pwd{$key2};
            $pwd{$key2} = $tmp;
        }

        // Convert into HTML
        $pwd = htmlentities($pwd, ENT_QUOTES);

        return $pwd;
    }
	
	function get_from_cached( $fsettings = array() ){
		if( isset( $fsettings[ 'cache_key' ] ) ){
			/*
			switch( $fsettings[ 'cache_key' ] ){
			case 'categories':
			break;
			}
			*/
			$cache_key = $fsettings[ 'cache_key' ];
			$settings = array(
				'cache_key' => $cache_key,
				'permanent' => true,
			);
			
			if( isset( $fsettings[ 'directory_name' ] ) && $fsettings[ 'directory_name' ] )
				$settings[ 'directory_name' ] = $fsettings[ 'directory_name' ];
			
			if( isset( $fsettings[ 'data' ] ) && $fsettings[ 'data' ] )
				$settings[ 'data' ] = $fsettings[ 'data' ];
			
			//CHECK IF CACHE IS SET
			return get_cache_for_special_values( $settings );
		}
	}
	
	function get_general_settings_value( $settings = array() ){
		if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && $settings[ 'key' ] ){
			$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
			
			if( isset( $settings[ 'country' ] ) && $settings[ 'country' ] && isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ $settings[ 'country' ] ] ) ){
				return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ $settings[ 'country' ] ];
			}
			
			if( isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ 'default' ] ) ){
				return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ 'default' ];
			}
		}
		
		return 0;
	}
	
	function get_general_settings_value_array( $settings = array() ){
		if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && is_array( $settings[ 'key' ] ) ){
			$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
			
			foreach( $settings[ 'key' ] as & $val ){
				if( isset( $settings[ 'country' ] ) && $settings[ 'country' ] && isset( $general_settings[ $settings[ 'table' ] ][ $val ][ $settings[ 'country' ] ] ) ){
					$val = $general_settings[ $settings[ 'table' ] ][ $val ][ $settings[ 'country' ] ];
				}else{
					if( isset( $general_settings[ $settings[ 'table' ] ][ $val ][ 'default' ] ) ){
						$val = $general_settings[ $settings[ 'table' ] ][ $val ][ 'default' ];
					}else{
						$val = '';
					}
				}
			}
			
			return $settings[ 'key' ];
		}
		
		return array();
	}
	
	function get_general_settings_object( $settings = array() ){
		if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && $settings[ 'key' ] ){
			$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
			
			if( isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ] ) ){
				return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ];
			}
		}
		return 0;
	}
	
    function get_shopping_cart_percentage_discount( $settings = array() ){
        $g = get_general_settings_object( array( 'table' => 'cart', 'key' => 'SHOPPING CART DISCOUNT' ) );
        if( isset( $g['default'] ) && $g['default'] && isset( $g['s_date'] ) && doubleval( $g['s_date'] ) && isset( $g['e_date'] ) && doubleval( $g['e_date'] ) ){
            $today = date("U");
            if( $today > doubleval($g['s_date']) && $today < doubleval($g['e_date']) ){
                return doubleval($g['default']);
            }
        }
        return 0;
    }
    
	function get_paynow_button( $settings = array() ){
		if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && isset( $settings[ 'price' ] ) && $settings[ 'table' ] && $settings[ 'key' ] && $settings[ 'price' ] ){
			$table = $settings[ 'table' ];
			$price = $settings[ 'price' ];
			$key = $settings[ 'key' ];
			
			$caption = 'Pay Now';
			if( isset( $settings[ 'caption' ] ) && $settings[ 'caption' ] )
				$caption = $settings[ 'caption' ];
			
			$title = 'Pay Now';
			if( isset( $settings[ 'title' ] ) && $settings[ 'title' ] )
				$title = $settings[ 'title' ];
				
			$desc = '';
			if( isset( $settings[ 'desc' ] ) && $settings[ 'desc' ] )
				$desc = strip_tags( $settings[ 'desc' ] );
				
			$class = 'btn-primary ';
			if( isset( $settings[ 'class' ] ) && $settings[ 'class' ] )
				$class .= $settings[ 'class' ];
				
			$pagepointer = '';
			if( isset( $settings[ 'pagepointer' ] ) && $settings[ 'pagepointer' ] )
				$pagepointer = $settings[ 'pagepointer' ];
				
			//define what is being paid for
			$html = '<form method="get" action="'.$pagepointer.'engine/php/site_request_processing_script.php?" id="pay-now-navigation" class="form-navigate">';
				$html .= '<input type="hidden" name="action" value="checkout" />';
				$html .= '<input type="hidden" name="todo" value="billing_and_shipping_page" />';
				$html .= '<input type="hidden" name="table" value="'.$table.'" />';
				$html .= '<input type="hidden" name="price" value="'.$price.'" />';
				$html .= '<input type="hidden" name="key" value="'.$key.'" />';
				$html .= '<input type="hidden" name="desc" value="'.$desc.'" />';
				$html .= '<input type="submit" class="white-text btn '.$class.'" value="'.$caption.'" title="'.$title.'" />';
			$html .= '</form>';
			
			return $html;
		}
		
	}
	
	function get_user_geolocation_data(){
		//echo 'Set-up geoip_record_by_name in all_other_general_function.php on line 2910';
        return array();
        //$r = geoip_record_by_name( '154.120.71.175' );
        $ip = '';
        if( isset( $_SESSION['temp_location']['ip_address'] ) && isset( $_SESSION['temp_location']['country_name'] ) ){
            return $_SESSION['temp_location'];
        }else{
           $ip = get_ip_address();
        }
        
		$r = geoip_record_by_name( $ip );
		if( isset( $r['country_name'] ) && isset( $r['country_code'] ) && isset( $r['region'] ) && isset( $r['city'] ) ){
			//get country id from name
            $countries = get_countries();
            $c_id = '';
            foreach( $countries as $id => $val ){
                if( strtolower( trim( $val ) ) == strtolower( trim( $r['country_name'] ) ) ){
                    $c_id = $id;
                    break;
                }
            }
            $_SESSION['temp_location'] = array(
				'country' => $r['country_name'],
				'country_id' => $c_id,
				'country_code' => $r['country_code'],
				
				'state_id' => '',
				'state_code' => '',
				'state' => $r['region'],
				
				'city' => $r['city'],
                'ip_address' => $ip,
			);
            return $_SESSION['temp_location'];
		}
		return array();
	}
	
	function interprete_website_page_url(){
		
		if( isset( $_GET['page'] ) && $_GET['page'] ){
			switch( $_GET['page'] ){
			case "register":
				$frame_id = $_GET['page'];
				
				$action = 'site_users';
				$todo = 'site_registration';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "welcome-new-user":
				$frame_id = $_GET['page'];
				
				$action = 'welcome_new_user';
				$todo = 'dashboard_welcome_message';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "store-page":
				$frame_id = $_GET['page'];
				
				$action = 'store';
				$todo = 'site_store_page';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "user-dashboard":
				$frame_id = $_GET['page'];
				
				$action = 'welcome_new_user';
				$todo = 'user_dashboard';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "hot_deals":
			case "hot-deals":
			case "marketplace":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'all_products';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "deals":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'deals';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
            case "product-category":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'category_products';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "product-details":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'product';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "payment-handler":
				$frame_id = $_GET['page'];
				unset( $_GET['page'] );
                
				$action = 'website';
				$todo = 'payment_handler';
				
                $getparams = '';
                foreach( $_GET as $key => $val ){
                    if($getparams)$getparams .= '&'.$key.'='.$val;
                    else $getparams = $key.'='.$val;
                }
                
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
			break;
			case "stores":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'stores';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "auctions":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'auctions';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "report_an_issue":
			case "support":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'support';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "faq":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'faq';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "newsletter":
				$frame_id = $_GET['page'];
				
				$action = 'website';
				$todo = 'newsletter';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "view-my-profile":
				$frame_id = $_GET['page'];
				
				$action = 'site_users';
				$todo = 'display_user_details';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "login":
				$frame_id = $_GET['page'];
				
				$action = 'site_users';
				$todo = 'site_users_authentication';
				
                $getparams = '';
                foreach( $_GET as $key => $val ){
                    if($getparams)$getparams .= '&'.$key.'='.$val;
                    else $getparams = $key.'='.$val;
                }
                
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
			break;
			case "google-login":
				$frame_id = $_GET['page'];
				unset( $_GET['page'] );
                
				$action = 'site_users';
				$todo = 'site_users_google_authentication';
                
                $getparams = '';
                foreach( $_GET as $key => $val ){
                    if($getparams)$getparams .= '&'.$key.'='.$val;
                    else $getparams = $key.'='.$val;
                }
                
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
			break;
			case "facebook-login":
				$frame_id = $_GET['page'];
				unset( $_GET['page'] );
                
				$action = 'site_users';
				$todo = 'site_users_facebook_authentication';
                
                $getparams = '';
                foreach( $_GET as $key => $val ){
                    if($getparams)$getparams .= '&'.$key.'='.$val;
                    else $getparams = $key.'='.$val;
                }
                
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
			break;
			case "reset-password":
				$frame_id = $_GET['page'];
				
				$action = 'site_users';
				$todo = 'site_users_reset_password';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "inventory-manager":
				$frame_id = $_GET['page'];
				
				$action = 'product';
				$todo = 'site_inventory_manager';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "billing-and-shipping-page":
				$frame_id = $_GET['page'];
				
				$action = 'checkout';
				$todo = 'billing_and_shipping_page';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			break;
			case "sign-out":
				session_destroy();
                header('Location: ./');
			break;
			}
			
			if( ! isset( $frame_src ) ){
				$pages = get_website_pages();
				if( isset( $pages[ $_GET['page'] ]['page_name'] ) && $pages[ $_GET['page'] ]['page_name'] ){
					$frame_id = $_GET['page'];
					
					$todo = $pages[ $_GET['page'] ]['page_name'];
					$action = 'website';
					
					$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
				}
			}
			
		}
		
		if( isset( $action ) && isset( $todo ) ){
			$_GET['action'] = $action;
			$_GET['todo'] = $todo;
		}else{
            if( ! ( isset( $_GET['action'] ) && $_GET['action'] && isset( $_GET['todo'] ) && $_GET['todo'] ) ){
                $_GET['action'] = 'website'; 
                $_GET['todo'] = 'homepage';
            }
		}
	}
    
    function tidy($html, $userConfig = FALSE ) {
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
    
    function reorder_fields_based_on_serial_number( $fields = array() , $form_label = array() ){
        
        $new_fields = array();
        $serial_num = count( $form_label ) + 1;
        
        foreach( $fields as $field_ids ){
            
            $field_id = $field_ids[0];
            
            if( $field_id == 'id' ){
                $new_fields[0] = $field_ids;
                continue;
            }
            
            if( isset( $form_label[$field_id]['serial_number'] ) && intval( $form_label[$field_id]['serial_number'] ) ){
                $i = intval( $form_label[$field_id]['serial_number'] );
                $new_fields[ $i ] = $field_ids;
            }else{
                $new_fields[ $serial_num ] = $field_ids;
            }
            
            ++$serial_num;
        }
        ksort($new_fields);
        
        return $new_fields;
    }
    
    function translate( $words ){
       //remove to enable translation
        return $words;
        
       $default_country_id = 'US'; 
       $lang = getenv("LANG");
       //$lang = 'FR';
       
       if( $lang && $default_country_id != $lang ){
            $return = get_words_translation( array( 'id' => md5( strtolower( trim( $words ) . $default_country_id . $lang ) ) ) );
            
            if( $return )return ucwords( $return );
       }
       
       return $words;
    }
    
    function translate_2( $settings = array() ){
        $default_country_id = 'US'; 
        $lang = getenv("LANG");
       
        $words = '';
        if( isset( $settings['words'] ) ){
            $words = $settings['words'];
        }
        
        //remove to enable translation
        return $words;
        
        if( isset( $settings['table'] ) && $settings['table'] && isset( $settings['field'] ) && $settings['field'] && isset( $settings['record_id'] ) && $settings['record_id'] ){
        
            if( $lang && $default_country_id != $lang ){
                
                $return = get_fields_translation( array( 'id' => md5( strtolower( $settings['table'] . $settings['field'] . $settings['record_id'] . $default_country_id . $lang ) ) ) );
            
                if( $return )return $return;
            }
            
        }
        
        return translate( $words );
    }
    
    function convert_kg_to_pounds_and_ounces($weight_in_kg = 1){
	//Convert Weight from kg to pounds and ounces
	$pounds = 0;
	$pounds = ($weight_in_kg * 2.20462262);
	
	$decimals = explode('.', $pounds);
	
	if(isset($decimals[0])){
		$pounds = $decimals[0];
	}
	
	$ounces = 0;
	if(isset($decimals[1])){
		$ounce = '0.'.$decimals[1];
		
		$ounces = (($ounce/1) * 16);
	}
	
	return array(
		'pounds'=>round($pounds),
		'ounces'=>round($ounces),
	);
}

function convert_cm_to_inches($dimension = 1){
	$inches = round($dimension * 0.393700787);
	
	return $inches;
}

function convert_table_name_to_code( $table_name ){
	$t = explode('_', $table_name);
    $code = '';
    foreach( $t as $tt ){
        if( isset( $tt[0] ) && $tt[0] )
            $code .= $tt[0];
    }
    return strtoupper($code);
}
?>