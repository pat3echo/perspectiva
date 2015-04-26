<?php
	//CONFIGURATION
	$pagepointer = '../';
	require_once $pagepointer . "settings/Config.php";
	require_once $pagepointer . "settings/Setup.php";
	
	$page = 'upload';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	//GET DETAILS OF CURRENTLY LOGGED IN USER
	require_once $pagepointer . "settings/Current_user_details_session_settings.php";
	
	// list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$allowedExtensions = array();
	
	// max file size in bytes
	$sizeLimit = 2 * 1024 * 1024;

	$uploader = new cUploader($allowedExtensions, $sizeLimit);
	$uploader->calling_page = $pagepointer;

	$uploader->user_id = $current_user_session_details['id'];
	$uploader->priv_id = $current_user_session_details['privilege'];
	
	create_folder( $pagepointer.'files/'.$_GET['tableID'], '', '' );
	
	if( isset($_GET['tableID']) && $_GET['tableID'] && is_dir($pagepointer.'files/'.$_GET['tableID']) && isset($_GET['formID']) && $_GET['formID'] ){
	
		$uploader->form_id = $_GET['formID'];
		$uploader->table_id = $_GET['tableID'];
		
		$formControlElementName = 'default';
		if( isset($_GET['name']) && $_GET['name'] ){
			$formControlElementName = $_GET['name'];
		}
		
		$allowedExtensions = array("jpg","jpeg","png","bmp","gif","tiff","pdf","xls","doc","txt","docx","ppt","pptx","rtf","xlsx","odt");
		
		if( isset($_GET['acceptable_files_format']) && $_GET['acceptable_files_format'] ){
			$allowedExtensions = explode( ':::', $_GET['acceptable_files_format'] );
		}
		
		$returned_value = $uploader->handleUpload( $pagepointer . 'files/' . $_GET['tableID'] . '/', $formControlElementName, $allowedExtensions );
		
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($returned_value), ENT_NOQUOTES);
	}else{
		//Invalid Table
		$returned_value = array("error"=>'Invalid Directory to Upload File');
		echo htmlspecialchars(json_encode($returned_value), ENT_NOQUOTES);
	}
	exit;
?>