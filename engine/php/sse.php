<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

    
$pagepointer = '../';
//require_once $pagepointer . "settings/Config.php";
require_once $pagepointer."settings/All_other_general_functions.php";

/*********CACHING**********/
// Unccomment this block of code to enable files caching of database records
require_once $pagepointer."settings/php_fast_cache.php";

$_POST['app_memcache'] = new phpFastCache();
    
phpFastCache::$storage = "files";

if(isset($fakepointer)){
    phpFastCache::$path = $fakepointer.$pagepointer."tmp/filescache";
}else{
    phpFastCache::$path = $pagepointer."tmp/filescache";
}


/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($id , $settings = array()  , $val = array() ) {
  $title = $settings['title'];
  $msg = $settings['msg'];
  $pic = $settings['img'];
  //$pic = 'http://localhost/dev-3/engine/frontend-assets/img/people/img1-small.jpg';
  $host = $settings['host'];
  
  echo "id: $id" . PHP_EOL;
  echo "data: {\n";
  echo "data: \"pic\": \"$pic\", \n";
  echo "data: \"title\": \"$title\", \n";
  echo "data: \"msg\": \"$msg\", \n";
  echo "data: \"host\": \"$host\", \n";
  
  foreach( $val as $k => $v ){
    if( ! is_array( $v )  ){
        echo "data: \"$k\": \"$v\", \n";
    }
  }
  echo "data: \"id\": $id\n";
  echo "data: }\n";
  echo PHP_EOL;
  ob_flush();
  flush();
}

$startedAt = time();
session_write_close();

do {
  // Cap connections at 60 seconds. The browser will reopen the connection on close
  if ((time() - $startedAt) > 30) {
    die();
  }
  
    //check for cached data
    $fsettings = array(
        'cache_key' => 'signin-push-notifications',
        'cache_values' => array(),
    );
    $not = get_cache_for_special_values( $fsettings );
    
    if( is_array( $not ) && !empty( $not ) ){
        
        foreach( $not as $kk => & $val ){
            //if( isset( $val['sent'] ) )unset( $not[$kk] );
            $settings = array(
                'title' => ( $val['entry'] == 'in' )?'Entry...':'Exit...',
                'msg' => $val['full_name'] . ' - ' . strtoupper($val['name_of_organization']),
                'img' => 'http://localhost/dev-3/engine/' . $val['photograph'],
                'host' => $val['whom_to_see'] . ' for ' . strtoupper( $val['reason_for_visit'] ),
                'addr' => $val['street_address'],
            );
            $val['sent'] = true;
            sendMsg( $startedAt , $settings , $val );
        }
        $startedAt = time();
        //$fsettings['cache_values'] = $not;
        sleep(4);
        set_cache_for_special_values( $fsettings );
    }else{
        echo "id: 0" . PHP_EOL;
        echo "data: \n".PHP_EOL;
        ob_flush();
        flush();
    }
    sleep(2);
    
    
  // If we didn't use a while loop, the browser would essentially do polling
  // every ~3seconds. Using the while, we keep the connection open and only make
  // one request.
} while(true);
?>