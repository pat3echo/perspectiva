<?php
	/**
	 * Koboko Application Set-up File
	 *
	 * @used in  				php/ajax_request_processing_script.php, ajax_server/*.php, *./index.php
	 * @created  				07:45 | 05-01-2012
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Application Set-up File
	|--------------------------------------------------------------------------
	|
	| Defines settings of global variables used through-out the application and 
	| controls the PHP classes that would be required for the operation of each
	| page, also includes other required application files
	|
	*/
	
	require_once "All_other_general_functions.php";
	
	/*********CACHING**********/
	// Unccomment this block of code to enable files caching of database records
	require_once "php_fast_cache.php";
	
	$_POST['app_memcache'] = new phpFastCache();
		
	phpFastCache::$storage = "files";
	
	if(isset($fakepointer)){
		phpFastCache::$path = $fakepointer.$pagepointer."tmp/filescache";
	}else{
		phpFastCache::$path = $pagepointer."tmp/filescache";
	}
	/*********CACHING**********/
	
	// Set random session key variable that would restrict unauthorised access
	if(!(isset($_SESSION['key']) && $_SESSION['key'])){
		$_SESSION['key'] = md5(md5(sha1('p34$34:Aavbiiavj9120KADN908£%$$^%&').md5(rand(0,100891022032))).sha1('pD2qwe$%.,:<') . get_new_id() );
	}
	
	$session_key = $_SESSION['key'];
	
	// Include all other required files by the application
    require_once "Options_for_form_elements.php";
	require_once "Current_user_details_session_settings.php";
	require_once "Calculated_values.php";
	require_once "Database_table_field_names_interpretation_functions.php";
	
	
	// Define Classes required for the functioning of each page
	$classes = array(
		//File Upload Script
		'myschool-admin' => array(
			
		),
		//PHP Backend Processing Script
		'ajax_request_processing_script' => array(
			'cAudit',
			'cForms',
			'cError',
			'cProcess_handler',
			'cScript_compiler',
			'cAuthentication',
			'cModules',
			'cUsers_role',
			'cUsers',
			'cSite_users',
			'cFunctions',
			'cFaq',
			'cMypdf',
			'cMyexcel',
			'cReports',
			'cSearch',
			'cColumn_toggle',
			'cAppsettings',
			'cData_search',
			'cAccess_roles',
			'cGeneral_settings',
			'cDashboard',
			'cEmails',
			'cEmail_template',
			'cWebsite',
			'recaptchalib',
			'cWebsite_menu',
			'cWebsite_pages',
			'cWebsite_page_content',
			'cWebsite_sidebars',
			'cResource_library',
			'cSimple_image',
			'cVisit_schedule',
			'cEntry_exit_log',
			'cEmployee_entry_exit_log',
			'cDivision',
			'cDepartments',
			'cUnits',
			'cJob_roles',
			'cBranch_offices',
		),
		//PHP File Upload Script
		'upload' => array(
			'cAudit',
			'cForms',
			'cError',
			'cProcess_handler',
			'cUploader',
			'cAuthentication',
			'cModules',
			'cUsers_role',
			'cUsers',
			'cFunctions',
			'cMypdf',
			'cMyexcel',
			'cSearch',
			'cColumn_toggle',
			'cNotifications',
			'cAppsettings',
		),
	);
	
	
	//Include required css files
	$css = array(
		//REGISTER
		'register' => array(
			'layout',
			'theme'
		),
	);

	
	//Include required jquery libraries
	$jqueries_lib = array(
		//REGISTER
		'myschool-admin' => array(
			'assets/plugins/jquery-migrate-1.2.1.min.js',
			'assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
			'assets/plugins/bootstrap/js/bootstrap.min.js',
			'assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js',
			'assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
			'assets/plugins/jquery.blockui.min.js',
			'assets/plugins/jquery.cookie.min.js',
			'assets/plugins/uniform/jquery.uniform.min.js',
			
			'my_js/jsAjax-processing-script.js',
			'js/FixedColumns.js',
			//'js/ColReorderWithResize.js',
			'js/jquery.dataTables.js',
			'js/highstock.js',
			'js/modules/exporting.js',
			'js/fileuploader.js',
			'js/tiny_mce/jquery.tinymce.min.js',
			'js/tiny_mce/tinymce.min.js',
			'js/ui/jquery-ui-1.10.3.custom.min.js',
			
			//Bootstrap
			'assets/scripts/app.js',
			'assets/scripts/index.js',
			'assets/scripts/tasks.js',
			
		),
	);
	
	
	// Initialize global variable that would act as a temporary storage for values that would be
	// used in populating select box list, if the list is to be populated from a database table
	
	//unset($_SESSION['temp_storage']);
	if( ! isset($_SESSION['temp_storage']) ){
	
		get_options_for_caching( $database_name , $database_connection, "all" );
		
	}
	
	//CHECK FOR PERMANENT CACHED VALUES
	//--cache_key = categories
	
	//netstat -ao -p tcp
	//netstat -ao |find /i "listening"
?>