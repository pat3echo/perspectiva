<?php
	/**
	 * Gas Helix Database Configuration File
	 *
	 * @used in  				php/ajax_request_processing_script.php, ajax_server/*.php, *./index.php
	 * @created  				07:45 | 05-01-2012
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Gas Helix Database Configuration File
	|--------------------------------------------------------------------------
	|
	| Stores information that enables the Gas Helix to establish a persistent 
	| connection with a database server using a valid user account details 
	| that have been granted all privileges on the database
	|
	*/
	
	//Initialize PHP Sessions
	ini_set('session.use_cookies', 1);
	session_start();
	
	// IP Adddress of Database Server Host PC
	$database_host_ip_address = 'localhost';
	
	// Username of the database user account that would be used in establishing 
	// connection with the database
	$database_user = 'root';
	
	// Password of the database user account that would be used in establishing 
	// connection with the database
	$database_user_password = '';
	
	// Name of the database to connect to
	$database_name = 'perspectiva';
	
    //$default_country_id = '1228';
    $default_country_id = '1';
    
    $default_country = get_default_country_details();
	
	// Connect to database and return connection information for subsequent
	// actions
	$database_connection = mysql_pconnect($database_host_ip_address, $database_user, $database_user_password );
	
	// Display error message if connection fail
	if ( ! $database_connection ) {
		$e = oci_error();
		trigger_error('Could not connect to dtbase: '. $e['message'],E_USER_ERROR);
	}
	
	// Determine whether or not all errors should be reported 
	//error_reporting (~E_ALL);
    function get_default_country_details(){
        return array( 
            'id' => '1',
            'country' => 'Worldwide',
            'iso_code' => 'GLOBAL', 
            'flag' => 'GLOBAL', 
            'conversion_rate' => 1,
            'currency' => '$',
            'language' => 'US',
        );
    }
?>