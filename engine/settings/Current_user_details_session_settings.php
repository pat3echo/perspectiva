<?php 
	/**
	 * Gas Helix Current User Details File
	 *
	 * @used in  				php/ajax_request_processing_script.php, ajax_server/*.php, *./index.php
	 * @created  				09:35 | 05-01-2012
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Current User Details File
	|--------------------------------------------------------------------------
	|
	| Gets the stored session settings of the Gas Helix currently logged in
	| user details such as user id, user name, email, user role, privileges
	|
	*/
	
	// Initialize current user details array
	
	$current_user_session_details = array(
		'id' => '',
		'email' => '',
		'fname' => '',
		'lname' => '',
		'privilege' => '',
		'login_time' => '',
		'verification_status' => '',
		'remote_user_id' => '',
		'country' => '',
	);
	
	// Get current user details session key variable
	$current_user_details_session_key = md5( 'ucert' . $_SESSION['key'] );
	
    $user_country_id = '';
    
	// Get current user details session settings
	if( isset( $_SESSION[ $current_user_details_session_key ] ) ) {
		
		$current_user_session_details = $_SESSION[ $current_user_details_session_key ];
        
		if( isset( $current_user_session_details['country'] ) && $current_user_session_details['country'] ){
            $user_country_id = $current_user_session_details['country'];
        }
	}
    
    if( ! $user_country_id ){
        //estimate user location as user is not logged in
        $user_loc_data = get_user_geolocation_data();
        if( isset( $user_loc_data['country_id'] ) && $user_loc_data['country_id'] )
            $user_country_id = $user_loc_data['country_id'];
    }
    
    $default_country_id = $user_country_id;
    
    //get country details
    if( isset( $_SESSION['country']['id'] ) ){
        $country_details = $_SESSION['country'];
    }else{
        if( $default_country_id == '1' ){
            $country_details = $default_country;
        }else{
            $country_details = get_countries_details( array( 'id' => $default_country_id ) );
        }
    }
    
    $a = array( 'id', 'country', 'iso_code', 'conversion_rate', 'currency', 'language' );
    foreach( $a as $key ){
        switch( $key ){
        case 'language':
            include "Language_translator.php";
        break;
        case 'iso_code':    
            if( isset( $country_details[ $key ] ) && $country_details[ $key ] ){
                define( strtoupper( 'selected_country_flag' ) , $country_details[ $key ] );
                define( strtoupper( 'selected_country_'.$key ) , $country_details[ $key ] );
            }else{
                define( strtoupper( 'selected_country_flag' ) , $default_country[ $key ] );
                define( strtoupper( 'selected_country_'.$key ) , $default_country[ $key ] );
            }
        break;
        default:
            if( isset( $country_details[ $key ] ) && $country_details[ $key ] ){
                define( strtoupper( 'selected_country_'.$key ) , $country_details[ $key ] );
            }else{
                define( strtoupper( 'selected_country_'.$key ) , $default_country[ $key ] );
            }
        break;
        }
    }
    
    $nigeria_details = get_countries_details( array( 'id' => '1157' ) );
    define( 'NIGERIAN_NAIRA_CONVERSION_RATE', doubleval( $nigeria_details['conversion_rate'] ) );
	//print_r($current_user_session_details);
?>