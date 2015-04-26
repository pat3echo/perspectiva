<?php
    $key = 'featured_products';
    if( isset($data[ $key ]) && is_array( $data[ $key ] ) && !empty( $data[ $key ] ) ){
        
        shuffle( $data[ $key ] );
        
        foreach( $data[ $key ] as $merchant_product_id => $merchant_product ){
        
        $p_name = '';
        $field = 'product_name'; 
        if( isset($merchant_product[ $field ]) )
            $p_name = translate( $merchant_product[ $field ] );
?>
        
        <div class="pull-left featured-product featured-product-home-scroll">
        
            <div class="row-fluid">
            
            <div class="span6">
            <a href="?page=product-details&data=<?php echo $merchant_product['id']; ?>" title="<?php echo $p_name; ?>" target="_blank">
            <?php
                $field = 'primary_image';
                if( file_exists( $data['pagepointer'] . $merchant_product[ $field ] ) ){ 
            ?>
                
                <div style="width:90%; height:130px; display:table; padding: 4px; background-color: #fff; border: 1px solid #eee; border: 1px solid rgba(0, 0, 0, 0.1); -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);  vertical-align:middle; text-align:center;" >
                
                <table class="image-table-slider">
                    <tr>
                    <td>
                        <img style="max-width:83px; max-height:128px;" src="<?php 
                        $pathinfo = pathinfo( $merchant_product[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb1'.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest;
                        ?>" />
                    </td>
                    </tr>
                </table>
                </div>
                
            <?php } ?>
            </a>
            </div>
            <!-- ./span6 -->
            
            <div class="span6 tablet-margin-5" style="position:relative; height:138px;">
            
            <p style="height:60px;">
            <small>
            <a href="?page=product-details&data=<?php echo $merchant_product['id']; ?>" title="<?php echo $p_name; ?>" target="_blank">
            <?php echo return_first_few_characters_of_a_string( $p_name , 28 ); ?>
            </a>
            </small>
            </p>
            
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
            
            <p class="text-error">
            <small>
            <?php 
                echo '<strong>'.convert_currency( $fsp ).'</strong>';
                if( $fsp != $msp ){
                    echo '<small style="text-decoration:line-through;"><br />' . convert_currency( $msp ).'</small>';
                }
            ?>
            </small>
            </p>
            
            <?php 
                $q_sold = 0;
                $q_avai = 0;
                
                $field = 'quantity_sold'; if( isset($merchant_product[ $field ]) )$q_sold = $merchant_product[ $field ]; 
                $field = 'quantity_available'; if( isset($merchant_product[ $field ]) )$q_avai = $merchant_product[ $field ]; 
                
                //$q_sold = 5;
                //$q_avai = 8;
                if( $q_sold ){
            ?>
            
            <div class="sold-meter-container">
                <div class="progress progress-striped active" style="margin-bottom:0; text-align:center; color:#333;">
                  <div class="bar" style="width: <?php echo round( ( $q_sold * 100 / ( $q_sold + $q_avai ) ), 2 ); ?>%;">
                  </div>
                  <small><small style="position: absolute; width: 100%; text-shadow: 1px 1px 1px #fff; color: black;"><?php echo round( ( $q_sold * 100 / ( $q_sold + $q_avai ) ), 1 ); ?>% sold</small></small>
                </div>
            </div>
            <?php
                }
            ?>
            
            </div>
            <!-- ./span6 -->
            
            </div>
            <!-- ./row -->
        
        </div>
        <!-- ./span3 -->
        
<?php
        }
        if( count($data[ $key ]) > 4 ){
 ?>
    <input type="hidden" id="smoothscroll" value="<?php echo count($data[ $key ]); ?>" />
 <?php
        }
        
    }
?>