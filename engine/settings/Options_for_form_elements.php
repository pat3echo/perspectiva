<?php 
	/**
	 * Gas Helix Select Combo Box Population File
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Select Combo Box Population File
	|--------------------------------------------------------------------------
	|
	| Functions that define which functions to use in populating select combo 
	| boxes during form generation and dataTables population
	|
	*/
	
	function get_months_of_year(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		);
	}
	
	function get_appsettings(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'appsettings';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
    
	function get_site_user_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_calendar_years(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$this_year = date("Y")+1;
		
		$return = array();
		for( $i = $this_year; $i > 1997; --$i ){
			$return[ $i ] = $i;
		}
		return $return;
	}
	
	function get_approval_status(){
		$return = array(
			'cancelled' => 'Cancelled',
			're_scheduled' => 'Re-scheduled',
            
            'pending' => 'Pending Approval',
			
            'approved' => 'Approved',
			'declined' => 'Declined',
			'postponed' => 'Postponed',
			
			'complete' => 'Completed',
			'missed' => 'Missed',
		);
		return $return;
	}
	
	function get_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'login' => 'Login Page',
			'register' => 'Register Page',
		);
	}
	
	function get_class_names(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'advertised_product' => 'Advertised Product',
			'auctioned_product' => 'Auctioned Product',
			'product' => 'Product',
			'site_users' => 'Site Users',
			'store' => 'Store',
			'store_subscription' => 'Store Subscription',
			'store_banners' => 'Store Banners',
			'dhl' => 'DHL',
			'china_post' => 'China Post',
			'nipost' => 'Nipost',
			'usps' => 'USPS',
			'fedex' => 'Fedex',
			'custom_shipping' => 'Custom Shipping',
			'payout_requests' => 'Payout Requests',
			'cart' => 'Cart',
		);
	}
	
	function get_website_pages_width(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'wide' => 'Wide',
			'narrow' => 'Narrow',
		);
	}
	
	function get_website_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'website_pages';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
	
	function get_website_pages_options(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'website_pages';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key,
		) );
		
		$pages = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			
			foreach( $cached_values as $id => $val ){
				$pages[ $id ] = $val['page_name'];
			}
			asort( $pages );
		}
		return $pages;
	}
	
	function get_website_page_content( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'website_page_content';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_website_sidebars( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'website_sidebars';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_website_sidebars_type(){
		return array(
			'user_defined' => 'User Defined',
			'system_defined' => 'System Defined',
		);
	}
	
	function get_menu_positions(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'top_menu_bar_right' => 'Top Menu Bar Right',
			'top_menu_bar_left' => 'Top Menu Bar Left',
			'bottom_menu_bar_left' => 'Bottom Menu Bar Left',
			'bottom_menu_bar_right' => 'Bottom Menu Bar Right',
			
			'footer_menu_item_1' => 'Footer Menu Item Col 1',
			'footer_menu_item_2' => 'Footer Menu Item Col 2',
			'footer_menu_item_3' => 'Footer Menu Item Col 3',
			'footer_menu_item_4' => 'Footer Menu Item Col 4',
			'footer_menu_item_5' => 'Footer Menu Item Col 5',
		);
	}
	
	function get_menu_title_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'text' => 'Text',
			'image' => 'Image',
		);
	}
	
	function get_menu_type(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'menu_item' => 'Menu Item',
			'seperator' => 'Seperator',
			'header' => 'Label / Header',
		);
	}
	
	function get_site_users_access_roles(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'buyer' => 'Buyer',
			'seller' => 'Seller',
		);
	}
	
	function get_notification_states(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'read' => 'Read',
			'unread' => 'Unread',
		);
	}
	
	function get_task_status(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending' => 'Pending',
			'complete' => 'Complete',
		);
	}

	function get_unit_of_time(){
		//RETURN ARRAY OF TIME UNITS
		return array(
			'1' => 'Seconds',
			'60' => 'Minutes',
			'3600' => 'Hours',
			'86400' => 'Days',
			'2592000' => 'Months',
			'31536000' => 'Years',
		);
	}

	function get_notification_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending_task' => 'Pending Task',
			'completed_task' => 'Completed Task',
			'no_task' => 'No Task',
		);
	}

	function get_yes_no(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'yes' => 'Yes',
			'no' => 'No',
		);
	}
    
	function get_entry_exit(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'entry' => 'Entry',
			'exit' => 'Exit',
		);
	}
    
	function get_form_field_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'text' => 'Textbox',
			'number' => 'Number',
			'decimal' => 'Decimal',
			'tel' => 'Phone Number',
			'url' => 'URL',
			'email' => 'Email Address',
			'date' => 'Date',
			'textarea' => 'Textarea',
			'file' => 'File Upload',
		);
	}

	function get_access_roles(){
		//RETURN ARRAY OF USERS ROLES
		if(isset($_SESSION['temp_storage']['access_roles']['access_roles']) && is_array($_SESSION['temp_storage']['access_roles']['access_roles'])){
			//$_SESSION['temp_storage']['access_roles']['access_roles']['1300130013'] = 'System Super Administrators';
			//$_SESSION['temp_storage']['access_roles']['access_roles']['2300230023'] = 'Student';
			unset( $_SESSION['temp_storage']['access_roles']['access_roles'][ '1300130013' ] );
			
			return $_SESSION['temp_storage']['access_roles']['access_roles'];
		}else{
			return array(
				//'1300130013' => 'System Super Administrators',
				//'2300230023' => 'Student',
			);
		}
	}

	function get_staff_roles(){
		//RETURN ARRAY OF STAFF ROLES
		
		if(isset($_SESSION['temp_storage']['access_roles']['access_roles']) && is_array($_SESSION['temp_storage']['access_roles']['access_roles'])){
			return $_SESSION['temp_storage']['access_roles']['access_roles'];
		}else{
			return array();
		}
	}

	function get_file_import_options(){
		//RETURN ARRAY OF FILE IMPORT OPTIONS
		return array(
			'100' => 'Insert Data As New Records',
			'200' => 'Update Existing Records',
		);
	}

	function get_import_file_field_mapping_options(){
		//RETURN ARRAY OF IMPORT FILE FIELD MAPPING OPTIONS
		return array(
			'100' => 'Serial Import of Excel Fields',
			'200' => 'Use Field Names Defined in First Row of Excel Table',
			'400' => 'Use NAPIMS Cash Calls Template-1',
		);
	}

	function get_import_table_fields(){
		//RETURN ARRAY OF IMPORT TABLE FIELDS
		$returning_array = array();
		
		if( isset( $_SESSION['temp_storage'][ 'excel_import_table' ] ) && $_SESSION['temp_storage'][ 'excel_import_table' ] ){
			$function_name = $_SESSION['temp_storage'][ 'excel_import_table' ];
			
			if( function_exists( $function_name ) ){
				$untransformed_field_data = $function_name();
				
				foreach( $untransformed_field_data as $key => $value ){
					$returning_array[$key] = $value[ 'field_label' ];
				}
			}
		}
		
		return $returning_array;
	}

	function get_security_questions(){
		//RETURN ARRAY OF SECURITY QUESTIONS
		return array(
			'30' => 'Mother\'s Maiden Name',
			'40' => 'Name of First Pet',
			'50' => 'Favorite Uncle\'s Name',
		);
	}

	function get_sex(){
		//RETURN ARRAY OF SEX
		return array(
			'male' => 'Male',
			'female' => 'Female',
		);
	}

	function get_marital_status(){
		//RETURN ARRAY OF MARITAL STATUS
		return array(
			'30' => 'Single',
			'40' => 'Married',
		);
	}

	function get_accessible_functions(){
		//RETURN ARRAY OF ACCESSIBLE FUNCTIONS
		if(isset($_SESSION['temp_storage']['accessible_functions']) && is_array($_SESSION['temp_storage']['accessible_functions'])){
			unset( $_SESSION['temp_storage']['accessible_functions'][ '30632233134' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '30636618450' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '37628636108' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '37628698200' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '3762873466' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '37628757412' ] );
			
			unset( $_SESSION['temp_storage']['accessible_functions'][ '3970770861' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '3970771896' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '39707728122' ] );
			unset( $_SESSION['temp_storage']['accessible_functions'][ '397077379' ] );
			
			return $_SESSION['temp_storage']['accessible_functions'];
		}else{
			return array();
		}
	}

	function get_accessible_functions_tooltips(){
		//RETURN ARRAY OF ACCESSIBLE FUNCTIONS TOOLTIPS
		if(isset($_SESSION['temp_storage']['accessible_functions_tooltips']) && is_array($_SESSION['temp_storage']['accessible_functions_tooltips'])){
			return $_SESSION['temp_storage']['accessible_functions_tooltips'];
		}else{
			return array();
		}
	}

	function get_users_email_addresses(){
		//RETURN ARRAY OF USERS EMAIL ADDRESS
		if(isset($_SESSION['temp_storage']["users"]['users_email_addresses']) && is_array($_SESSION['temp_storage']["users"]['users_email_addresses'])){
			return $_SESSION['temp_storage']["users"]['users_email_addresses'];
		}else{
			return array();
		}
	}

	function get_site_users_email_addresses(){
		//RETURN ARRAY OF USERS EMAIL ADDRESS
		if(isset($_SESSION['temp_storage']["site_users"]['users_email_addresses']) && is_array($_SESSION['temp_storage']["site_users"]['users_email_addresses'])){
			return $_SESSION['temp_storage']["site_users"]['users_email_addresses'];
		}else{
			return array();
		}
	}

	function get_categories_parents(){
		//RETURN ARRAY OF CATEGORIES PARENTS
		$return[ '100' ] = '--None--';
		
		if(isset($_SESSION['temp_storage']["categories"][ 'category_headings' ]) && is_array($_SESSION['temp_storage']["categories"][ 'category_headings' ])){
			
			$_SESSION['temp_storage']["categories"][ 'category_headings' ][ '100' ] = '--None--';
			$return = $_SESSION['temp_storage']["categories"][ 'category_headings' ];
			
		}
		
		return $return;
	}

	function get_primary_categories(){
		//RETURN ARRAY OF PRIMARY CATEGORIES PARENTS
		
		if(isset($_SESSION['temp_storage']["categories"][ 'primary_categories' ]) && is_array($_SESSION['temp_storage']["categories"][ 'primary_categories' ])){
			
			return $_SESSION['temp_storage']["categories"][ 'primary_categories' ];
			
		}else{
			return array();
		}
		
	}

	function get_users_names(){
		//RETURN ARRAY OF USERS NAMES
		if(isset($_SESSION['temp_storage']["users"]['users_names']) && is_array($_SESSION['temp_storage']["users"]['users_names'])){
			return $_SESSION['temp_storage']["users"]['users_names'];
		}else{
			return array();
		}
	}
	
	function get_users_names_full(){
		//RETURN ARRAY OF USERS NAMES
		if(isset($_SESSION['temp_storage']["users"]['users_names_full']) && is_array($_SESSION['temp_storage']["users"]['users_names_full'])){
			return $_SESSION['temp_storage']["users"]['users_names_full'];
		}else{
			return array();
		}
	}
	
	function get_site_users_names_full(){
		//RETURN ARRAY OF USERS NAMES
		if(isset($_SESSION['temp_storage']["site_users"]['users_names_full']) && is_array($_SESSION['temp_storage']["site_users"]['users_names_full'])){
			return $_SESSION['temp_storage']["site_users"]['users_names_full'];
		}else{
			return array();
		}
	}
	
	function get_modules_in_application(){
		//RETURN ARRAY OF MODULES IN APPLICATION
		
		if(isset($_SESSION['temp_storage']['modules_in_application']) && is_array($_SESSION['temp_storage']['modules_in_application'])){
			return $_SESSION['temp_storage']['modules_in_application'];
		}else{
			return array();
		}
	}

	function get_paper_size(){
		//RETURN ARRAY OF PAPER SIZE
		return array(
			'a4' => 'A4',
			'a0' => 'A0',
			'a1' => 'A1',
			'a2' => 'A2',
			'a3' => 'A3',
			'a5' => 'A5',
			'a6' => 'A6',
			'a7' => 'A7',
			'a8' => 'A8',
			'a9' => 'A9',
			'a10' => 'A10',
			'b0' => 'B0',
			'b1' => 'B1',
			'b2' => 'B2',
			'b3' => 'B3',
			'b4' => 'B4',
			'b5' => 'B5',
			'b6' => 'B6',
			'b7' => 'B7',
			'b8' => 'B8',
			'b9' => 'B9',
			'b10' => 'B10',
			'c0' => 'C0',
			'c1' => 'C1',
			'c2' => 'C2',
			'c3' => 'C3',
			'c4' => 'C4',
			'c5' => 'C5',
			'c6' => 'C6',
			'c7' => 'C7',
			'c8' => 'C8',
			'c9' => 'C9',
			'c10' => 'C10',
			'ra0' => 'RA0',
			'ra1' => 'RA1',
			'ra2' => 'RA2',
			'ra3' => 'RA3',
			'ra4' => 'RA4',
			'sra0' => 'SRA0',
			'sra1' => 'SRA1',
			'sra2' => 'SRA2',
			'sra3' => 'SRA3',
			'sra4' => 'SRA4',
			'letter' => 'LETTER',
			'legal' => 'LEGAL',
			'ledger' => 'LEDGER',
			'tabloid' => 'TABLOID',
			'executive' => 'EXECUTIVE',
			'folio' => 'FOLIO',
			/*
			'commercial #10 envelope' => 'COMMERCIAL #10 ENVELOPE',
			'catalog #10 1/2 envelope' => 'CATALOG #10 1/2 ENVELOPE',
			*/
			'8.5x11' => '8.5x11',
			'8.5x14' => '8.5x14',
			'11x17' => '11x17',
		);
	}

	function get_orientation(){
		//RETURN ARRAY OF PAPER ORIENTATION
		return array(
			'portrait' => 'Portrait',
			'landscape' => 'Landscape',
		);
	}

	function get_report_css_styling(){
		//RETURN ARRAY OF CSS STYLE SHEET FOR REPORT
		return array(
			'pdf-report-plain' => 'Plain No Borders',
			'pdf-report' => 'Plain with Borders',
			'pdf-report-grid' => 'Show Grids',
		);
	}
	
	//Returns an array of all countries
	function get_countries(){
		$cache_key = 'country_list';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			
			foreach( $cached_values as $id => $val ){
				$country[ $id ] = $val['country'];
			}
			asort( $country );
		}
		return $country;
	}
	
	//Returns an array of all countries
	function get_countries_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'country_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['id'] ] ) && $cached_values[ $settings['id'] ] ){
				return $cached_values[ $settings['id'] ];
			}
		}
	}
	
	//Returns an array of all countries
	function get_countries_data(){
        $cache_key = 'country_list';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        if( isset( $cached_values ) && $cached_values ){
            return $cached_values;
        }
        
		return array();
	}
	
	//Returns an array of all states in a country
	function get_state_details( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
			if( isset( $cached_values[ $settings['state_id'] ] ) && $cached_values[ $settings['state_id'] ] ){
				return $cached_values[ $settings['state_id'] ];
			}
		}
	}
	
    function get_currency(){
		$currency = array(
			'usd' => '$',
			'ngn' => '&#8358;',
		);
		return $currency;
	}
    
    //Returns an array of all states in a country
	function get_all_states_in_country( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			return get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
		}
	}
	
	function get_city_details( $settings = array() ){
		if( isset( $settings['city_id'] ) && $settings['city_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['city_id'] ] ) && $cached_values[ $settings['city_id'] ] ){
				return $cached_values[ $settings['city_id'] ];
			}
		}
	}
	
	function get_all_cities_in_state( $settings = array() ){
		if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			return get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
		}
	}
	
	function get_state_name( $settings = array() ){
        $state = get_state_details( $settings );
		
        if( isset( $state['state'] ) ){
            return $state['state'];
        }
        
        return $settings['state_id'];
	}
	
	function get_city_name( $settings = array() ){
        $state = get_city_details( $settings );
		
        if( isset( $state['city'] ) ){
            return $state['city'];
        }
        
        return $settings['city_id'];
	}
	
	//Returns an array of all states in a country
	function get_states_in_country( $settings = array() ){
		if( isset( $_SESSION['temp_storage']['selected_country_id'] ) && $_SESSION['temp_storage']['selected_country_id'] ){
            $settings['country_id'] = $_SESSION['temp_storage']['selected_country_id'];
        }
        $return = array();
        if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['state'] ) )
                        $return[ $id ] = $val['state'];
                }
            }
			
		}
        return $return;
	}
	
	//Returns an array of all states in a country
	function get_cities_in_state( $settings = array() ){
		if( isset( $_SESSION['temp_storage']['selected_state_id'] ) && $_SESSION['temp_storage']['selected_state_id'] ){
            $settings['state_id'] = $_SESSION['temp_storage']['selected_state_id'];
        }
        $return = array();
        if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['city'] ) )
                        $return[ $id ] = $val['city'];
                }
            }
		}
        return $return;
	}
    
	function get_cities_in_state_pay_on_delivery( $settings = array() ){
        return get_cities_in_state( $settings );
    }
    
	//Returns an array of all countries
	function get_countries_general_settings(){
		
		$country = get_countries();
		$country['default'] = '-default-';
		asort($country);
		return $country;
	}
	
	//Returns an array of all countries
	function get_active_inactive(){
		return array(
			'active' => 'Active',
			'in_active' => 'In Active',
		);
	}
	
	function get_states(){
		//RETURN ARRAY OF STATES IN THE FEDERATION
		$states = array(
			1 => 'Abia',
			10 => 'Adamawa',
			20 => 'Akwa Ibom',
			30 => 'Anambra',
			40 => 'Bauchi',
			50 => 'Bayelsa',
			60 => 'Benue',
			70 => 'Borno',
			80 => 'Cross River',
			90 => 'Delta',
			100 => 'Ebonyi',
			110 => 'Edo',
			120 => 'Ekiti',
			130 => 'Enugu',
			140 => 'Federal Capital Territory',
			145 => 'Gombe',
			150 => 'Imo',
			160 => 'Kaduna',
			170 => 'Kano',
			180 => 'Katsina',
			190 => 'Kogi',
			200 => 'Kwara',
			210 => 'Lagos',
			220 => 'Nassarawa',
			230 => 'Niger',
			240 => 'Ogun',
			250 => 'Ondo',
			260 => 'Osun',
			270 => 'Oyo',
			280 => 'Plateau',
			290 => 'Rivers',
			300 => 'Sokoto',
			310 => 'Taraba',
			320 => 'Jigawa',
			330 => 'Yobe',
			340 => 'Zamfara',
		);
		return $states;
	}

	function get_audit_trail_logs(){
		//RETURN ARRAY OF AUDIT TRAILS
		$pagepointer = '';
		if(isset($_SESSION['temp_storage']['pagepointer']) &&  $_SESSION['temp_storage']['pagepointer']){
			$pagepointer = $_SESSION['temp_storage']['pagepointer'];
		}
		
		$oldurl = 'tmp/audit_logs/';
		
		$array_to_return = array();
		
		if(is_dir($pagepointer.$oldurl)){
			//3. Open & Read all files in directory
			$cdir = opendir($pagepointer.$oldurl);
			while($cfile = readdir($cdir)){
				if(!($cfile=='.' || $cfile=='..')){
					//Check if report exists
					$get_name = explode('.',$cfile);
					if(isset($get_name[0]) && $get_name[0]){
						$array_to_return[$get_name[0]] = date('j-M-Y',($get_name[0]/1)).' Log';
					}
				}
			}
			closedir($cdir);
		}
		
		return $array_to_return;
	}
    
	//Returns an array of all states in a country
	function get_users_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_users( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_entry_exit_log_last_state( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'entry_exit_log-currentstate';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_entry_exit_log_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'entry_exit_log';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
    
	function get_employee_entry_exit_log_last_state( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'employee_entry_exit_log-currentstate';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_employee_entry_exit_log_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'employee_entry_exit_log';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_visit_schedule_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'visit_schedule';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
    function enquiry_processing_status(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            '1' => 'open ticket',
            '2' => 'processing',
            '3' => 'resolved ticket',
        );
    }
    
    function enquiry_category(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            '1001' => 'Do not include in FAQ',
            '1' => 'buying',
            '2' => 'selling',
            '3' => 'payment',
            '4' => 'delivery',
            '5' => 'technical issues',
            '6' => 'usability',
        );
    }
    
    function enquiry_category_frontend(){
        $return = enquiry_category();
        unset($return['1001']);
        return $return;
    }
    
	//Returns an array of all states in a country
	function get_all_users_countries(){
		$cache_key = 'site_users'.'-all-users-countries';
        return get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
	}
	
	function get_divisions(){
		$cache_key = 'division';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
    
	function get_job_roles(){
		$cache_key = 'job_roles';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_branch_offices(){
		$cache_key = 'branch_offices';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_units(){
		$cache_key = 'units';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_departments(){
		$cache_key = 'departments';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
    function get_station_nigeria(){
        $province = array(
            1 => 'Aba',
            2 => 'Umuahia',
            10 => 'Yola',
            20 => 'Uyo',
            21 => 'Eket',
            30 => 'Nnewi',
            31 => 'Onitsha',
            40 => 'Bauchi',
            50 => 'Yenagoa',
            60 => 'Makurdi',
            70 => 'Maiduguri',
            80 => 'Calabar',
            90 => 'Asaba',
            91 => 'Warri',
            92 => 'Sapele',
            100 => 'Abakaliki',
            110 => 'Benin',
            120 => 'Ado Ekiti',
            130 => 'Enugu',
            131 => 'Nsukka',
            140 => 'Abuja',
            141 => 'Gwagwalada',
            150 => 'Owerri',
            160 => 'Kaduna',
            161 => 'Zaria',
            170 => 'Kano',
            180 => 'Katsina',
            190 => 'Lokoja',
            200 => 'Ilorin',
            210 => 'Lagos',
            220 => 'Lafia',
            230 => 'Minna',
            240 => 'Abeokuta',
            241 => 'Ijebu Ode',
            250 => 'Akure',
            260 => 'Oshogbo',
            261 => 'Ife',
            270 => 'Ibadan',
            280 => 'Jos',
            290 => 'Port Harcourt',
            291 => 'Bonny',
            300 => 'Sokoto',
            310 => 'Jalingo',
            320 => 'Gusau',
        );
        
        return $province;
    }
    
    function get_website_menu_items( $settings ){
        $cache_key = 'website_menu';
        $cached = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        $returned = array();
        
        foreach( $settings as $set ){
            if( isset( $cached[ $set ] ) ){
                $returned[ $set ] = $cached[ $set ];
            }
        }
        
        return $returned;
    }
    
	function get_languages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'US' => 'English',
			'FR' => 'French',
			'SA' => 'Arabic',
			'ZA' => 'Afrikanas',
		);
	}
?>