<?php
    $key = 'products';
    if( isset($data[ $key ]) && is_array( $data[ $key ] ) && !empty( $data[ $key ] ) ){
?>
    <h4>You may also consider</h4>
<?php
        foreach( $data[ $key ] as $merchant_product_id => $merchant_product ){
        
        $p_name = '';
        $field = 'product_name'; 
        if( isset($merchant_product[ $field ]) )
            $p_name = translate( $merchant_product[ $field ] );
?>
        
			<hr />
            <div class="row-fluid">
            
            <div class="span6">
            <a href="?page=product-details&data=<?php echo $merchant_product_id; ?>" title="<?php echo $p_name; ?>" target="_blank">
            <?php
                $field = 'primary_image';
                if( file_exists( $data['pagepointer'] . $merchant_product[ $field ] ) ){ 
            ?>
                
                <div style="width:90%; height:120px; display:table; padding: 4px; background-color: #fff; border: 1px solid #ccc; border: 1px solid rgba(0, 0, 0, 0.2); -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);  vertical-align:middle; text-align:center;" >
                
                <div style="width:100%; height:120px; display:table-cell; vertical-align:middle; text-align:center; ">
                    <img style="max-width:118px; max-height:118px;" src="<?php 
                        $pathinfo = pathinfo( $merchant_product[ $field ] );
                            $dest = $pathinfo['dirname'].'/thumb3-'.$pathinfo['basename'];
                            echo $data['pagepointer'] . $dest;
                        ?>" />
                </div>
                </div>
                
            <?php } ?>
            </a>
            </div>
            <!-- ./span6 -->
            
            <div class="span6">
            
            <?php
            $field = 'country_code'; 
                if( isset($merchant_product[ $field ]) ){
            ?>
            <img src="<?php 
                if( isset($merchant_product[ $field ]) ){
                    if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $merchant_product[ $field ] . '.jpg' ) ){
                        echo $data['pagepointer'] . 'images/country_flag/' . $merchant_product[ $field ] . '.jpg';
                    }else{
                        if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $merchant_product[ $field ] . '.png' ) ){
                            echo $data['pagepointer'] . 'images/country_flag/' . $merchant_product[ $field ] . '.png';
                        }
                    }
                }
            ?>" title="<?php $field = 'country'; if( isset($merchant_product[ $field ]) )echo $merchant_product[ $field ]; ?>" alt="<?php $field = 'country'; if( isset($merchant_product[ $field ]) )echo $merchant_product[ $field ]; ?>" class="pull-right" />
            <?php
            }
            ?>
            
            <h5>
            <a href="?page=product-details&data=<?php echo $merchant_product_id; ?>" title="<?php echo $p_name; ?>" target="_blank">
            <?php echo return_first_few_characters_of_a_string( $p_name , 34 ); ?>
            </a>
            </h5>
            
            <?php
                $fsp = 0;
                $key1 = 'pricing_data'; 
                $field = 'final_selling_price'; 
                if( isset($merchant_product[ $key1 ][ $field ]) ){
                    $fsp = doubleval( $merchant_product[ $key1 ][ $field ] );
                } 
                
                $msp = 0;
                $key1 = 'pricing_data'; 
                $field = 'final_selling_price_without_discount'; 
                if( isset($merchant_product[ $key1 ][ $field ]) ){
                    $msp = doubleval( $merchant_product[ $key1 ][ $field ] );
                } 
                /*
                $discount = 0;
                $field = 'percentage_discount'; 
                if( isset($v[ $field ]) ){ 
                    //re-evaluate discount based on final selling price(fsp)
                    echo doubleval( $v[ $field ] );
                }
                */
            ?>
            
            <p class="text-error"><?php 
                echo '<strong>'.convert_currency( $fsp ).'</strong>';
                if( $fsp != $msp ){
                    echo '<small style="text-decoration:line-through;"><br />' . convert_currency( $msp ).'</small>';
                }
            ?>
            </p>
            </div>
            <!-- ./span6 -->
            
            </div>
            <!-- ./row -->
        
<?php
        }
    }
?>