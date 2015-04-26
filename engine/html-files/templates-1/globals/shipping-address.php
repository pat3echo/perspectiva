<?php
    if( isset( $data['country'] ) && $data['country'] && isset( $data['firstname'] ) && $data['firstname'] ){
    ?>
        <strong><?php echo $data['firstname'] .' '. $data['lastname']; ?></strong><br />
        <?php echo $data['email'] .', '. $data['phonenumber']; ?><br /><br />
    <?php
        if( $data['country'] && $data['country']!='undefined' ){
            echo '<strong>'.get_select_option_value( $settings = array( 'id' => $data['country'], 'function_name' => 'get_countries' ) ).'</strong><br />';
        }
        
        $state = get_state_details( array( 'country_id' => $data['country'], 'state_id' => $data['state'] ) );
        
        if( isset($state['state']) && $state['state'] ){
            echo $state['state'] . ', ';
        }
        
        $city = get_city_name( array( 'city_id' => $data['city'], 'state_id' => $data['state'] ) );
        if( $city ){
            echo $city . '<br />';
        }else{
            if( $data['city'] && $data['city']!='undefined' ){
                echo $data['city'] . '<br />';
            }
        }
        
        if( $data['street 1'] && $data['street 1']!='undefined' ){
            echo $data['street 1'];
        }
        if( $data['street 2'] && $data['street 2']!='undefined' ){
            echo ', ' . $data['street 2'] . '<br />';
        }else{
            echo '<br />';
        }
        if( $data['postal code'] && $data['postal code']!='undefined' ){
            echo $data['postal code'];
        }
        
        if( isset( $data['delivery_instructions'] ) && $data['delivery_instructions'] ){
    ?>
        <p><small><hr /><?php echo $data['delivery_instructions']; ?></small></p>
    <?php
        }
    }
?>