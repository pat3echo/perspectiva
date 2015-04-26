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
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    if( isset( $_GET['browser'] ) && $_GET['browser'] == 'ie' ){
        
        if( isset( $_GET['iefixsid'] ) && $_GET['iefixsid'] )
            $_COOKIE["PHPSESSID"] = $_GET['iefixsid'];
        
        $postmethod = 1;
        if( isset( $_GET['formmethod'] ) && $_GET['formmethod'] == 'get' )$postmethod = 0;
        
        if( isset( $HTTP_RAW_POST_DATA ) && $HTTP_RAW_POST_DATA ){
            $post = explode('&',$HTTP_RAW_POST_DATA);
            foreach( $post as $pv ){
                $p = explode('=',$pv);
                if( isset( $p[1] ) ){
                    $key = urldecode($p[0]);
                    if( preg_match('/product_filters/', $key ) && ! preg_match('/product_filters_static/', $key ) ){
                        $string = str_replace( 'product_filters','', $key );
                        if( $postmethod )
                            $_POST[ 'product_filters' ][ preg_replace('/[^A-Za-z0-9\-]/', '', $string ) ][] = urldecode($p[1]);
                        else
                            $_GET[ 'product_filters' ][ preg_replace('/[^A-Za-z0-9\-]/', '', $string ) ][] = urldecode($p[1]);
                    }else{
                        if( $postmethod )
                            $_POST[ $key ] = urldecode($p[1]);
                        else
                            $_GET[ $key ] = urldecode($p[1]);
                    }
                }
            }
        }
        
        if( isset( $_GET['ietokenfix'] ) && $_GET['ietokenfix'] ){
            $_POST['processing'] = $_GET['ietokenfix'];
            //define('SKIP_USE_OF_FORM_TOKEN', 1);
        }
    }
    
    /*
    print_r($_GET);
    echo '<hr />';
    print_r($_POST);
    exit;
    */
	//CONFIGURATION
	if( ! ( isset( $skip_required_files ) && $skip_required_files ) ){
		$pagepointer = '../';
        $display_pagepointer = '';
        
		require_once $pagepointer . "settings/Config.php";
		require_once $pagepointer . "settings/Setup.php";
        
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
	}
	
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
			'display_pagepointer' => $display_pagepointer,
			'pagepointer' => $pagepointer,
			'user_cert' => $current_user_session_details,
			'database_connection' => $database_connection,
			'database_name' => $database_name, 
			'classname' => $classname, 
			'action' => $action,
			'language' => SELECTED_COUNTRY_LANGUAGE,
		);
		
        $_SESSION['reuse_settings'] = $settings;
        
		if( isset( $return_website_object ) && $return_website_object ){
			$website_object = reuse_class( $settings );
		}else{
            //header("Content-Type: application/json; charset-utf-8");
            //header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
			echo reuse_class( $settings );
		}
	}
?>