<?php
	//Logout Function
	if(isset($_GET['action']) && $_GET['action']=='signout'){
		$pagepointer = '../';
		require_once $pagepointer."settings/Config.php";
	
		//Auditor
		//auditor($pagepointer,'',$_SESSION['ucert']['id'],$_SESSION['ucert']['fname'].' '.$_SESSION['ucert']['lname'],$database_connection,$database,'log out','USERS','logged out');
		
		$url = '../';
		
		session_destroy();
		header('location: '.$url);
		exit;
	}
?>