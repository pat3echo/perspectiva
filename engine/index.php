<?php
	//CONFIGURATION
	$pagepointer = './';
	require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
	$page_id = 'myschool-admin';
	
	//INCLUDE CLASSES
	$class = $classes[$page_id];
	foreach($class as $require){
		require_once $pagepointer."classes/".$require.".php";
	}
	
?>
<!--START PAGE HEAD-->
<?php
	require_once $pagepointer."html-files/html-head-tag.php"; 
	
	require_once $pagepointer."html-files/templates-1/school-admin.php";
	
	require_once $pagepointer."html-files/html-jquery-files.php";
?>
<!--END PAGE HEAD-->
</html>