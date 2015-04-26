<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Farmtech Process AJAX Request File
	|--------------------------------------------------------------------------
	|
	| Receives and processes all AJAX Server requests from the clients
	|
	*/
	
	
	//CONFIGURATION
	$pagepointer = '../';
	require_once $pagepointer . "settings/Config.php";
	require_once $pagepointer . "settings/Setup.php";
	
	$page = 'ajax_request_processing_script';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	//REQUEST FILE OUTPUT
	if((isset($_GET['action']) && isset($_GET['todo'])) && $_GET['todo'] && $_GET['action']){
		$classname = $_GET['action'];
		$action = $_GET['todo'];
		
		if(isset($_GET['module']))$_SESSION['module'] = $_GET['module'];
		
		$settings = array(
			'pagepointer' => $pagepointer ,
			'user_cert' => $current_user_session_details ,
			'database_connection' => $database_connection ,
			'database_name' => $database_name , 
			'classname' => $classname , 
			'action' => $action ,
			'skip_authentication' => true,
		);
		echo reuse_class( $settings );
		
		exit;
	}
?>