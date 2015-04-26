<?php 
	/**
	 * FarmTech Database Table Field Name Interpretationn Function
	 *
	 * @used in  				php/ajax_request_processing_script.php, ajax_server/*.php, *./index.php
	 * @created  				09:35 | 05-01-2012
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| FarmTech Database Table Field Name Interpretationn Function File
	|--------------------------------------------------------------------------
	|
	| Gets the stored session settings of the Gas Helix currently logged in
	| user details such as user id, user name, email, user role, privileges
	|
	*/
	
	//SELECT @i :=0;# Rows: 1
	//UPDATE state_list SET serial_num = ( SELECT @i := @i +1 ) ;# 3715 rows affected.
	
	function appsettings(){
		return array(
			'appsettings001' => array(
				
				'field_label' => 'Name of App',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings002' => array(
				
				'field_label' => 'App Logo',
				'form_field' => 'file',
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings004' => array(
				
				'field_label' => 'Slogan',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings005' => array(
				
				'field_label' => 'Contact Address',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings006' => array(
				
				'field_label' => 'Contact Phone Number',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'appsettings007' => array(
				
				'field_label' => 'Contact Email Address',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings008' => array(
				
				'field_label' => 'Support Line',
				'form_field' => 'tel',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function general_settings(){
		return array(
			'general_settings001' => array(
				
				'field_label' => 'Key',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings002' => array(
				
				'field_label' => 'Value',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings003' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings004' => array(
				
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_form_field_types',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'general_settings005' => array(
				
				'field_label' => 'Class',
				'form_field' => 'select',
				'form_field_options' => 'get_class_names',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings006' => array(
				
				'field_label' => 'Country',
				'form_field' => 'select',
				'form_field_options' => 'get_countries_general_settings',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings007' => array(
				
				'field_label' => 'Start Date',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings008' => array(
				
				'field_label' => 'End Date',
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function access_roles(){
		return array(
			'access_roles001' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'access_roles002' => array(
				
				'field_label' => 'Accessible Functions',
				'form_field' => 'multi-select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_accessible_functions',
				
				//'display_position' => 'more-details',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
		);
	}
	
	function functions(){
		return array(
			'functions001' => array(
				
				'field_label' => 'Function Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions002' => array(
				
				'field_label' => 'Module Name',
				'form_field' => 'select',
				'form_field_options' => 'get_modules_in_application',
				
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions003' => array(
				
				'field_label' => 'Action',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions004' => array(
				
				'field_label' => 'Class Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions005' => array(
				
				'field_label' => 'Tooltip',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'functions006' => array(
				
				'field_label' => 'Help Topic',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'functions006' => array(
				
				'field_label' => 'Help Topic',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function modules(){
		return array(
			'modules001' => array(
				
				'field_label' => 'Module Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'modules002' => array(
				
				'field_label' => 'Module Description',
				'form_field' => 'textarea',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
		);
	}
	
	function myexcel(){
		return array(
			'myexcel005' => array(
				
				'field_label' => 'Excel File',
				'form_field' => 'file',
				'required_field' => 'no',
				
				'acceptable_files_format' => 'xls',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel006' => array(
				
				'field_label' => 'Import Table',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel007' => array(
				
				'field_label' => 'Mapping Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel008' => array(
				
				'field_label' => 'Import Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_file_import_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel009' => array(
				
				'field_label' => 'Equating Table Field for Update',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_table_fields',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function myexcel_cash_calls(){
		$return = myexcel();
		
		$custom = array(
			'myexcel001' => array(
				
				'field_label' => 'Budget',
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_budgets',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'myexcel002' => array(
				
				'field_label' => 'Month',
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_months_of_year',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
		);
		
		return array_merge( $custom , $return );
	}
	
	function site_users(){
		return array(
			'site_users001' => array(
				
				//'field_label' => 'First Name',
				'field_label' => SITE_USERS001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users002' => array(
				
				//'field_label' => 'Last Name',
				'field_label' => SITE_USERS002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users003' => array(
				
				//'field_label' => 'Email Address',
				'field_label' => SITE_USERS003,
				'form_field' => 'email',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users004' => array(
				
				//'field_label' => 'Phone Number',
				'field_label' => SITE_USERS004,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users006' => array(
				
				'field_label' => 'Current Password',
				'form_field' => 'old-password',
				'required_field' => 'yes',
				
				'class' => ' old-password password-info ',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users007' => array(
				
				'field_label' => 'Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'class' => ' password-info ',
				
				'tooltip' => 'Minimum of 7 characters required',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users008' => array(
				
				'field_label' => 'Confirm Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'class' => ' password-info ',
				
				'tooltip' => 'Re-type password',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users009' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'select',
				'form_field_options' => 'get_site_users_access_roles',
				//'form_field_options' => 'get_access_roles',
				
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users010' => array(
				
				'field_label' => 'Birthday',
				'form_field' => 'date-5',
				'required_field' => 'no',
				
				'class' => ' personal-info ',
				
				'tooltip' => 'You must be over 18years of age',
				
				'default_appearance_in_table_fields' => 'show',
				
				'custom_data' => array(
					'type' => 'birthday',
					'min-age-limit' => 18,
					'max-age-limit' => 0,
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users011' => array(
				
				'field_label' => 'Sex',
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'class' => ' personal-info ',
				
				'form_field_options' => 'get_sex',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users012' => array(
				
				'field_label' => 'Nationality',
				'form_field' => 'select',
				'form_field_options' => 'get_countries',
				
				'class' => ' contact-info country-select-to-state-field',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users013' => array(
				
				'field_label' => 'State / Province',
				'form_field' => 'text',
				
				'class' => ' contact-info state-select-to-city-field',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users014' => array(
				
				'field_label' => 'City',
				'form_field' => 'text',
				
				'class' => ' contact-info cities-select-field',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users015' => array(
				
				'field_label' => 'Street Address',
				'form_field' => 'text',
				
				'class' => ' contact-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users016' => array(
				
				'field_label' => 'Street Address',
				'form_field' => 'text',
				
				'class' => ' contact-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users017' => array(
				
				'field_label' => 'Postal / Zip Code',
				'form_field' => 'text',
				
				'class' => ' contact-info ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users018' => array(
				
				'field_label' => 'Photograph',
				'form_field' => 'file',
				
				'class' => ' personal-info ',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'site_users019' => array(
				
				'field_label' => 'Updated Primary Address',
				'form_field' => 'select',
				'form_field_options' => 'get_yes_no',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users020' => array(
				
				'field_label' => 'Verified Email Address',
				'form_field' => 'select',
				'form_field_options' => 'get_yes_no',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'site_users021' => array(
				
				'field_label' => 'Functional Email Address',
				'form_field' => 'email',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function users(){
		return array(
			'users001' => array(
				
				'field_label' => 'First Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users002' => array(
				
				'field_label' => 'Last Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users003' => array(
				
				'field_label' => 'Zidoff Merchant ID',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users004' => array(
				
				'field_label' => 'Email Address',
				'form_field' => 'email',
				'required_field' => 'yes',
				'placeholder' => 'Email Address',
				
                'icon' => '<i class="icon-user"></i>',
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users005' => array(
				
				'field_label' => 'Phone Number',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users006' => array(
				
				'field_label' => 'Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				'placeholder' => 'Password',
                
				'icon' => '<i class="icon-lock"></i>',
                
				'tooltip' => 'Minimum of 7 characters required',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users007' => array(
				
				'field_label' => 'Confirm Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'tooltip' => 'Re-type password',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users008' => array(
				
				'field_label' => 'Old Password',
				'form_field' => 'password',
				'required_field' => 'yes',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'users009' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'select',
				'form_field_options' => 'get_access_roles',
				
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users010' => array(
				
				'field_label' => 'Nationality',
				'form_field' => 'select',
				'form_field_options' => 'get_countries',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users011' => array(
				
				'field_label' => 'City',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'users012' => array(
				
				'field_label' => 'Photograph',
				'form_field' => 'file',
				
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function reports(){
		return array(
			'reports001' => array(
				
				'field_label' => 'File Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports002' => array(
				
				'field_label' => 'File URL',
				'form_field' => 'file',
				'required_field' => 'yes',
				
				//'acceptable_files_format' => 'xls',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports003' => array(
				
				'field_label' => 'Reference',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports004' => array(
				
				'field_label' => 'Source',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports005' => array(
				
				'field_label' => 'Keywords',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports006' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function website_menu(){
		return array(
			'website_menu001' => array(
				//menu position
				'field_label' => WEBSITE_MENU001,
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'form_field_options' => 'get_menu_positions',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu002' => array(
				//image / text
				'field_label' => WEBSITE_MENU002,
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_menu_title_types',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu003' => array(
				//title text
				'field_label' => WEBSITE_MENU003,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu004' => array(
				//image file
				'field_label' => WEBSITE_MENU004,
				'form_field' => 'file',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu005' => array(
				//serial num
				'field_label' => WEBSITE_MENU005,
				'form_field' => 'number',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu006' => array(
				//link
				'field_label' => WEBSITE_MENU006,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu007' => array(
				//tooltip
				'field_label' => WEBSITE_MENU007,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_menu008' => array(
				//type - label / menu
				'field_label' => WEBSITE_MENU008,
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'form_field_options' => 'get_menu_type',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function website_page_content(){
		return array(
			'website_page_content001' => array(
				//page
				'field_label' => WEBSITE_PAGE_CONTENT001,
				'form_field' => 'select',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'form_field_options' => 'get_website_pages_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_page_content002' => array(
				//content title
				'field_label' => WEBSITE_PAGE_CONTENT002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_page_content003' => array(
				//content
				'field_label' => WEBSITE_PAGE_CONTENT003,
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_page_content004' => array(
				//serial num
				'field_label' => WEBSITE_PAGE_CONTENT004,
				'form_field' => 'number',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function website_pages(){
		return array(
			'website_pages001' => array(
				//page name
				'field_label' => WEBSITE_PAGES001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_pages002' => array(
				//title
				'field_label' => WEBSITE_PAGES002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_pages003' => array(
				//times viewed
				'field_label' => WEBSITE_PAGES003,
				'form_field' => 'select',
				
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_pages004' => array(
				//times viewed
				'field_label' => WEBSITE_PAGES004,
				'form_field' => 'number',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function website_sidebars(){
		return array(
			'website_sidebars001' => array(
				//pages
				'field_label' => WEBSITE_SIDEBARS001,
				'form_field' => 'multi-select',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'form_field_options' => 'get_website_pages_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_sidebars002' => array(
				//title
				'field_label' => WEBSITE_SIDEBARS002,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_sidebars003' => array(
				//content type
				'field_label' => WEBSITE_SIDEBARS003,
				'form_field' => 'select',
				
				'form_field_options' => 'get_website_sidebars_type',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_sidebars004' => array(
				//content html
				'field_label' => WEBSITE_SIDEBARS004,
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'website_sidebars005' => array(
				//serial num
				'field_label' => WEBSITE_SIDEBARS005,
				'form_field' => 'number',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function visit_schedule(){
		return array(
			'visit_schedule001' => array(
				//name
				'field_label' => VISIT_SCHEDULE001,
				'form_field' => 'text',
				'required_field' => 'yes',
				'placeholder' => 'John Doe',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule002' => array(
				//phone
				'field_label' => VISIT_SCHEDULE002,
				'form_field' => 'tel',
				'placeholder' => '0805 XXX XXXX',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule003' => array(
				//email
				'field_label' => VISIT_SCHEDULE003,
				'form_field' => 'email',
				'placeholder' => 'gloria@perspectiva.com',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule004' => array(
				//street addr
				'field_label' => VISIT_SCHEDULE004,
				'form_field' => 'text',
				'placeholder' => 'No 42 Lobito Crescent',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule005' => array(
				//photograph
				'field_label' => VISIT_SCHEDULE005,
				'form_field' => 'file',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule006' => array(
				//reason for visit
				'field_label' => VISIT_SCHEDULE006,
				'form_field' => 'text',
				'required_field' => 'yes',
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule007' => array(
				//name of organization
				'field_label' => VISIT_SCHEDULE007,
				'form_field' => 'text',
				'required_field' => 'yes',
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule008' => array(
				//whom_to_see
				'field_label' => VISIT_SCHEDULE008,
				'form_field' => 'select',
				'required_field' => 'yes',
                
                'form_field_options' => 'get_yes_no',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule009' => array(
				//proposed start date
				'field_label' => VISIT_SCHEDULE009,
				'form_field' => 'date-5',
				'required_field' => 'yes',
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule010' => array(
				//proposed end date
				'field_label' => VISIT_SCHEDULE010,
				'form_field' => 'date-5',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule011' => array(
				//approved start date
				'field_label' => VISIT_SCHEDULE011,
				'form_field' => 'date-5',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule012' => array(
				//approved end date
				'field_label' => VISIT_SCHEDULE012,
				'form_field' => 'date-5',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule013' => array(
				//meeting venue
				'field_label' => VISIT_SCHEDULE013,
				'form_field' => 'text',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule014' => array(
				//other meeting venue
				'field_label' => VISIT_SCHEDULE014,
				'form_field' => 'text',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule015' => array(
				//approval status
				'field_label' => VISIT_SCHEDULE015,
				'form_field' => 'select',
				'required_field' => 'yes',
                
				'default_appearance_in_table_fields' => 'show',
				'form_field_options' => 'get_approval_status',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'visit_schedule016' => array(
				//approval code
				'field_label' => VISIT_SCHEDULE016,
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function resource_library(){
		return array(
			'resource_library001' => array(
				//pages
				'field_label' => RESOURCE_LIBRARY001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library002' => array(
				//title
				'field_label' => RESOURCE_LIBRARY002,
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library003' => array(
				//content type
				'field_label' => RESOURCE_LIBRARY003,
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	    
	function support_enquiry(){
		return array(
			'support_enquiry001' => array(
				//email address
				'field_label' => SUPPORT_ENQUIRY001,
				'form_field' => 'email',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
                'quick_details_field' => 'id',
                'quick_details_table' => 'support_response',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_enquiry002' => array(
				//ask us anything
				'field_label' => SUPPORT_ENQUIRY002,
				'form_field' => 'textarea',
                'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_enquiry003' => array(
				//faq title
				'field_label' => SUPPORT_ENQUIRY003,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_enquiry004' => array(
				//same enquiry
				'field_label' => SUPPORT_ENQUIRY004,
				'form_field' => 'select',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_enquiry005' => array(
				//processing status
				'field_label' => SUPPORT_ENQUIRY005,
				'form_field' => 'select',
				
                'form_field_options' => 'enquiry_processing_status',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'support_enquiry006' => array(
				//faq category
				'field_label' => SUPPORT_ENQUIRY006,
				'form_field' => 'select',
                
                'form_field_options' => 'enquiry_category',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_enquiry007' => array(
				//reply
				'field_label' => SUPPORT_ENQUIRY007,
				'form_field' => 'textarea',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
    function faq(){
        $r = array();
        $return = support_enquiry();
        foreach( $return as $id => $v ){
            switch($id){
            case 'support_enquiry006':
            case 'support_enquiry003':
                $r[ $id ] = $v;
                $r[ $id ]['default_appearance_in_table_fields'] = 'show';
            break;
            case 'support_enquiry002':
                $r[ $id ] = $v;
                $r[ $id ]['field_label'] = 'FAQ Answer';
                $r[ $id ]['default_appearance_in_table_fields'] = 'show';
            break;
            default:
                if( isset( $r[ $id ]['default_appearance_in_table_fields'] ) )
                    unset( $r[ $id ]['default_appearance_in_table_fields'] );
            break;
            }
        }
        return $r;
    }
    
	function support_response(){
		return array(
			'support_response001' => array(
				//request id
				'field_label' => SUPPORT_RESPONSE001,
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'support-enquiry-id',
					'form_field' => 'text',
					'variables' => array( array( 'support_response001' ) ),
				),
                
				'default_appearance_in_table_fields' => 'show',
				
                'quick_details_field' => 'support_response001',
                'quick_details_table' => 'support_response',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_response002' => array(
				//response data
				'field_label' => SUPPORT_RESPONSE002,
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'support_response003' => array(
				//source of response
				'field_label' => SUPPORT_RESPONSE003,
				'form_field' => 'text',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function fields_translation(){
		return array(
			'fields_translation001' => array(
				//DB Table Name
				'field_label' => 'DB Table Name',
				'form_field' => 'text',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'fields_translation002' => array(
				//DB Table Field
				'field_label' => 'DB Table Field',
				'form_field' => 'text',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'fields_translation003' => array(
				//DB Record Key
				'field_label' => 'DB Record Key',
				'form_field' => 'text',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'fields_translation004' => array(
				//Shipping States
				'field_label' => 'Source Lang',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_languages',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'fields_translation005' => array(
				//Shipping States
				'field_label' => 'Trans Lang',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_languages',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'fields_translation006' => array(
				//Shipping States
				'field_label' => 'Translated Text',
				'form_field' => 'textarea-unlimited',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function words_translation(){
		return array(
			'words_translation001' => array(
				//DB Table Name
				'field_label' => 'Word(s)',
				'form_field' => 'textarea-unlimited',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'words_translation002' => array(
				//DB Table Field
				'field_label' => 'Translated Words',
				'form_field' => 'textarea-unlimited',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'words_translation003' => array(
				//Shipping States
				'field_label' => 'Source Lang',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_languages',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'words_translation004' => array(
				//Shipping States
				'field_label' => 'Trans Lang',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_languages',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
    function email_template(){
		return array(
			'email_template001' => array(
				
				'field_label' => 'Template Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'email_template002' => array(
				
				'field_label' => 'Email Message',
				'form_field' => 'textarea',
				'required_field' => 'yes',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'email_template003' => array(
				
				'field_label' => 'Email Subject',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	/*
    SELECT @i :=0;
	UPDATE newsletters SET serial_num = ( SELECT @i := @i +1 ) ;
    ADD `serial_num` INT NOT NULL AFTER `dhl_international_cost004`,
    
	ALTER TABLE `newsletters`  ADD `serial_num` INT NOT NULL AFTER `receipients_data1_data13`, ADD `creator_role` VARCHAR(33) NOT NULL AFTER `serial_num`,  ADD `created_by` VARCHAR(33) NOT NULL AFTER `creator_role`,  ADD `creation_date` INT NOT NULL AFTER `created_by`,  ADD `modified_by` VARCHAR(33) NOT NULL AFTER `creation_date`,  ADD `modification_date` INT NOT NULL AFTER `modified_by`,  ADD `ip_address` INT NOT NULL AFTER `modification_date`
    
	ALTER TABLE `support_enquiry` ADD `creator_role` VARCHAR(33) NOT NULL AFTER `serial_num`,  ADD `created_by` VARCHAR(33) NOT NULL AFTER `creator_role`,  ADD `creation_date` INT NOT NULL AFTER `created_by`,  ADD `modified_by` VARCHAR(33) NOT NULL AFTER `creation_date`,  ADD `modification_date` INT NOT NULL AFTER `modified_by`,  ADD `ip_address` INT NOT NULL AFTER `modification_date`
	*/
?>