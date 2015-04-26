<?php
$image_type = 4;
if( isset( $data['products'] ) && is_array( $data['products'] ) ){
    foreach( $data['products'] as $k => $v ){
        
        $p_name = '';
        $field = 'product_name'; 
        if( isset($v[ $field ]) )
            $p_name = translate( $v[ $field ] );
?>
    <div class="row-fluid">
     
     
    <div class="span6">
        
        <h5 style="min-height:40px;">
        
        <?php
            $field = 'country_code'; 
            if( isset($v[ $field ]) ){
        ?>
        <img src="<?php 
            if( isset($v[ $field ]) ){
                if( isset( $display_pagepointer ) && $display_pagepointer )echo $display_pagepointer; else echo $data['pagepointer'];
                
                if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.jpg' ) ){
                    echo 'images/country_flag/' . $v[ $field ] . '.jpg';
                }else{
                    //if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.png' ) ){
                        echo 'images/country_flag/' . $v[ $field ] . '.png';
                    //}
                }
            }
        ?>" title="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" alt="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" class="pull-right" />
        
        <?php
        }
        ?>
        
        <a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>product/<?php $field = 'product_name'; if( isset($v[ $field ]) )echo clean1( $v[ $field ] ); ?>-<?php $field = 'serial_num'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" data-href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=product-details&data=<?php $field = 'id'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" title="<?php echo $p_name; ?>">
        <?php echo return_first_few_characters_of_a_string( $p_name , 55 ); ?>
        </a>
        
        <br />
        <small style="line-height:2;"><?php
				$field = 'manufacturer'; 
				if( isset($v[ $field ]) ){
					echo ucwords( $v[ $field ] );
				}
			?></small>
        <small style="float:right; line-height:2;">SKU: <?php
				$field = 'sku'; 
				if( isset($v[ $field ]) ){
					echo strtoupper( $v[ $field ] );
				}
			?></small>        
        </h5>
        
        
        
        <?php
            $fsp = 0;
            $key1 = 'pricing_data'; 
            $field = 'final_selling_price'; 
            if( isset($v[ $key1 ][ $field ]) ){
                $fsp = doubleval( $v[ $key1 ][ $field ] );
            } 
            
            $msp = 0;
            $key1 = 'pricing_data'; 
            $field = 'final_selling_price_without_discount'; 
            if( isset($v[ $key1 ][ $field ]) ){
                $msp = doubleval( $v[ $key1 ][ $field ] );
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
        
        <div class="pull-right" style="margin-right:-5px; margin-left:5px; text-align:right;">
        <span class="label"><?php $field = 'listing_type'; if( isset($v[ $field ]) )echo strtolower( get_select_option_value( array( 'id' => $v[ $field ], 'function_name' => 'get_listing_types' ) ) ); ?></span>
        <?php 
            $field = 'shipping_option'; 
            if( isset($v[ $field ]) && $v[ $field ] != 'zidoff_handles_shipping' ){ 
                $so = get_custom_shipping_options( array( 'id' => $v['merchant_id'] ) );
                
                if( isset( $so[ $v[ $field ] ][ 'shipping_fee_type' ] ) && $so[ $v[ $field ] ][ 'shipping_fee_type' ] == 'free' ){
        ?>
        <br />
        <i><small><?php echo GLOBALS_FREE_SHIPPING_TEXT; ?></small></i>
        <?php
                }
            }
        ?>
        </div>
        <p class="text-error" style="min-height:40px;">
        <?php 
            echo '<strong>'.convert_currency( $fsp ).'</strong>';
            if( $fsp != $msp ){
                echo '<small style="text-decoration:line-through;"><br />' . convert_currency( $msp ).'</small>';
            }
        ?>
        </p>
        
  </div>
  <!-- ./span6 -->
  
  <div class="span6">
        <a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>product/<?php $field = 'product_name'; if( isset($v[ $field ]) )echo clean1( $v[ $field ] ); ?>-<?php $field = 'serial_num'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" data-href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=product-details&data=<?php $field = 'id'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" title="<?php echo $p_name; ?>">
        
        <div style="width:100%; height:120px; display:table; padding: 4px; background-color: #fff; vertical-align:middle; text-align:center;">
                
            <div style="width:33%; height:120px; display:table-cell; vertical-align:middle; text-align:center; padding:5px; border:1px solid #ccc; position:relative;">
                <?php
            $discount = 0;
            $field = 'percentage_discount'; 
            if( isset($v[ $field ]) && intval( $v[ $field ] ) ){ 
                //re-evaluate discount based on final selling price(fsp)
                ?>
                <div class="discount-patch-tag">-<?php echo intval( $v[ $field ] ); ?>%</div>
                <?php
            }
            ?>
                <table class="image-table-list">
                <tr>
                <td>
                <img style="max-width:140px; max-height:116px;" src="<?php $field = 'primary_image'; if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        if( isset( $display_pagepointer ) && $display_pagepointer )echo $display_pagepointer; else echo $data['pagepointer'];
                        echo $dest; 
                        }
                    ?>" />
                 </td>
                </tr>
                </table>
            </div>
            <div style="width:33%; height:120px; display:table-cell; vertical-align:middle; text-align:center;  padding:5px; border:1px solid #ccc;">
                <table class="image-table-list">
                <tr>
                <td>
                <img style="max-width:140px; max-height:116px;" src="<?php $field = 'image_variant_1'; if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        if( isset( $display_pagepointer ) && $display_pagepointer )echo $display_pagepointer; else echo $data['pagepointer'];
                        echo $dest; 
                        }
                    ?>" />
                    </td>
                </tr>
                </table>
            </div>
            <div style="width:33%; height:120px; display:table-cell; vertical-align:middle; text-align:center; padding:5px; border:1px solid #ccc;">
                <table class="image-table-list">
                <tr>
                <td>
                <img style="max-width:140px; max-height:116px;" src="<?php $field = 'image_variant_2'; if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        
                        if( isset( $display_pagepointer ) && $display_pagepointer )echo $display_pagepointer; else echo $data['pagepointer'];
                        echo $dest; 
                        }
                    ?>" />
                    </td>
                </tr>
                </table>
            </div>
            
        </div>
        
        
        </a>
  </div>
  <!-- ./span6 -->
      
</div>
<!-- ./row -->

<hr />
    <?php
            }
        }
    ?>
