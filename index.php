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
	$pagepointer = './engine/';
    $display_pagepointer = '';
    
	require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
    if( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] ){
        $s = explode("/", $_SERVER['REQUEST_URI'] );
        $pr = get_project_data();
        foreach( $s as $ss ){
            if( $ss && ! strrchr( $ss, '?' ) ){
                $display_pagepointer = $pr['domain_name'] . 'engine/';
                break;
            }
        }
    }
    
	interprete_website_page_url();
	
	$return_website_object = true;
	$skip_required_files = true;
	
	require_once "engine/php/ajax_request_processing_script.php";
	
	$app = json_decode( $website_object );
	
?>	
    <!DOCTYPE html>
	<html>
	<head>
	<style id="pagepointer"><?php if( $display_pagepointer )echo $display_pagepointer; else echo $pagepointer; ?></style>
<?php	
	if( isset( $app->stylesheet ) )
		echo $app->stylesheet;
		
	if( isset( $app->html_head_tag ) )
		echo $app->html_head_tag;
?>	
	</head>
	<body class="<?php if( isset( $app->action_performed ) ) echo $app->action_performed; ?>"><div class="page-wrapper overflow-x-none " id="wrapper">
<?php	
	if( isset( $app->html_markup ) )
		echo $app->html_markup;
	
	if( isset( $app->html_header ) )
		echo $app->html_header;
	
	if( isset( $app->html ) )	
		echo $app->html;
		
	if( isset( $app->html_footer ) )	
		echo $app->html_footer;
?>	
	</div>
	
<?php
	if( isset( $app->javascript ) )
		echo $app->javascript;
?>	
	</body>
	</html>