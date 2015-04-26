
    <div class="row-fluid">
		
		<?php
            $image_type = 4;
            
            if( ! ( isset( $span ) && $span ) ){
                $span = 4;
                $span_num = 3;
            }
            $count = 0;
            
			if( isset( $data['products'] ) && is_array( $data['products'] ) ){
				foreach( $data['products'] as $k => $v ){
                    
                    if( ! ( $count % $span ) ){
        ?>
                    </div><div class="row-fluid">
        <?php
                    }
                    ++$count;
                    
                    $p_name = '';
                    $field = 'product_name'; 
                    if( isset($v[ $field ]) )
                        $p_name = translate( $v[ $field ] );
		?>
		<div class="span<?php echo $span_num; ?>">
			<div style="position:relative; width:88%; margin-right:12%;">
			
			<a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>product/<?php $field = 'product_name'; if( isset($v[ $field ]) )echo clean1( $v[ $field ] ); ?>-<?php $field = 'serial_num'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" data-href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=product-details&data=<?php $field = 'id'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" title="<?php echo $p_name; ?>">
			
			<div style="width:100%; height:198px; display:table; padding: 4px; background-color: #fff; border: 1px solid #ccc; border: 1px solid rgba(0, 0, 0, 0.2); -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);  vertical-align:middle; text-align:center; ">
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
				
                <table class="image-table-grid">
                    <tr>
                    <td>
					<img style="max-width:181px; max-height:196px;" src="<?php $field = 'primary_image'; if( isset($v[ $field ]) ){
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
			?>" title="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" alt="<?php $field = 'country'; if( isset($v[ $field ]) )echo $v[ $field ]; ?>" class="pull-right" style="margin-right:-5px; margin-left:5px;" />
			
			<?php
			}
			?>
			
			<?php echo return_first_few_characters_of_a_string( $p_name , 34 ); ?>
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
		  
		  </div>
		  <!-- ./span3 -->
		<?php
				}
			}
		?>
    </div>
	<!-- ./row -->