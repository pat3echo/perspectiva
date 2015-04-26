
    <div class="row-fluid">
		
		<?php
            $spanTablet = 4;
            $itemLimit = 10;
            if( ! ( isset( $span ) && $span ) ){
                $span = 4;
                $span_num = 3;
            }
            $count = 0;
            
            $image_type1 = 1;
            $image_type2 = 2;
            $image_type = 3;
            
			if( isset( $data['products'] ) && is_array( $data['products'] ) ){
				foreach( $data['products'] as $k => $v ){
                    
                    if( isset( $_POST[ 'displayed_products' ][ $k ] ) ){ continue; }
                    
                    if( $count == $itemLimit ){
                        break;
                    }
                    
                    $_POST[ 'displayed_products' ][ $k ] = 1;
                    
                    if( ! ( $count % $span ) ){
        ?>
                    </div><hr /><div class="row-fluid">
        <?php
                    }
                    
                    ++$count;
                    
                    $tabletClass = '';
                    if( ! ( $count % $spanTablet ) ){
                        $tabletClass = ' hide-in-tablet ';
                    }
		?>
		<div class="span<?php echo $span_num; ?> product-item tablet-24-width <?php echo $tabletClass; ?>" style="position:relative;">
			<?php
                $discount = 0;
				$field = 'percentage_discount'; 
				if( isset($v[ $field ]) && intval( $v[ $field ] ) ){ 
					//re-evaluate discount based on final selling price(fsp)
					?>
                    <div class="discount-patch-tag-home">-<?php echo intval( $v[ $field ] ); ?>%</div>
                    <?php
				}
                ?>
                
            <div style="position:relative; width:91%; margin-right:5%;">
                
			<a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>product/<?php $field = 'product_name'; if( isset($v[ $field ]) )echo clean1( $v[ $field ] ); ?>-<?php $field = 'serial_num'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" data-href="?page=product-details&data=<?php $field = 'id'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" title="<?php $field = 'product_name'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>">
			
			<div style="width:100%; height:198px; display:table; padding: 4px; background-color: #fff; vertical-align:middle; text-align:center;">
				
                
				<div style="width:100%; height:198px; display:table-cell; vertical-align:middle; text-align:center;">
                    <div style="position: absolute; top: 0px; left: -68px; display:none; z-index:30; width:60px;" class="image-options">
                        <img src="<?php 
                        $field = 'image_variant_1'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type2.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>" data-href="<?php 
                        $field = 'image_variant_1'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>"style="max-height:70px; width:48px; display:table-cell; border-right: 1px solid #fff;" class="img-polaroid optional-image"><img src="<?php 
                        $field = 'image_variant_2'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type2.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>"  data-href="<?php 
                        $field = 'image_variant_2'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>" style="max-height:70px; width:48px; display:table-cell; border-right: 1px solid #fff;" title="Click to View" class="img-polaroid optional-image">
                    </div>
                    
                    <table class="image-table">
                    <tr>
                    <td>
					<img style="max-width:159px; /*max-width:100%;*/ max-height:196px;" class="main-display-image" title="Click to View" src="<?php 
                        $field = 'primary_image'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>" data-href="<?php 
                        $field = 'primary_image'; 
                        if( isset($v[ $field ]) ){
                        $pathinfo = pathinfo( $v[ $field ] );
                        $dest = $pathinfo['dirname'].'/thumb'.$image_type1.'-'.$pathinfo['basename'];
                        echo $data['pagepointer'] . $dest; }
                    ?>" />
                    </td>
                    </tr>
                    </table>
				</div>
			</div>
			</a>
            
            <div class="buy-now-button">
                <div class="buy-now-button-child hidden-button">
                <?php
                    $key3 = 'buy_now_button';
                    if( isset( $v[ $key3 ] ) ){
                        echo $v[ $key3 ];
                    }
                ?>
                </div>
            </div>
            
            <a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>product/<?php $field = 'product_name'; if( isset($v[ $field ]) )echo clean1( $v[ $field ] ); ?>-<?php $field = 'serial_num'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" data-href="?page=product-details&data=<?php $field = 'id'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" title="<?php $field = 'product_name'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>">
			<h5 style="min-height:60px; font-weight:normal;">
			<?php $field = 'product_name'; if( isset($v[ $field ]) )echo return_first_few_characters_of_a_string( translate( $v[ $field ] ) , 34 ); ?>
			</h5>
			</a>
			
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
			
            <?php 
                $field = 'shipping_option'; 
                if( isset($v[ $field ]) && $v[ $field ] != 'zidoff_handles_shipping' ){ 
                    $so = get_custom_shipping_options( array( 'id' => $v['merchant_id'] ) );
                    
                    if( isset( $so[ $v[ $field ] ][ 'shipping_fee_type' ] ) && $so[ $v[ $field ] ][ 'shipping_fee_type' ] == 'free' ){
            ?>
			<i><small><?php echo GLOBALS_FREE_SHIPPING_TEXT; ?></small></i>
            <?php
                    }
                }
            ?>
            <br />
			<?php
				$field = 'country_code'; 
				if( isset($v[ $field ]) ){
			?>
			<img src="<?php 
				if( isset($v[ $field ]) ){
					
					if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.jpg' ) ){
						echo $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.jpg';
					}else{
						//if( file_exists( $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.png' ) ){
							echo $data['pagepointer'] . 'images/country_flag/' . $v[ $field ] . '.png';
						//}
					}
				}
			?>" title="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" alt="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" class="pull-right" style="margin-top:4px; margin-left:5px;" />
			
			<?php
			}
			?>
            
            </div>
			<p class="text-error" style="min-height:40px;">
			<?php 
				if( $fsp != $msp ){
					echo '<small style="text-decoration:line-through;">' . convert_currency( $msp ).'</small>';
				}
                echo '<br /><strong>'.convert_currency( $fsp ).'</strong>';
				
			?>
			</p>
			
		  </div>
		  
		  </div>
		  <!-- ./span3 -->
		<?php
				}
			}
		?>
    </div>
	<!-- ./row -->