
		<h1>Order <small># <?php if( isset( $data['id'] ) ) echo $data['id']; ?></small></h1>
		<hr />
		<div class="row-fluid">
		<?php 
			$field = 'id';
			
			$project = get_project_settings();
			if( isset( $data[ $field ] ) ){
		?>
			<header>
			<h1><?php echo $title; ?></h1>
			
			<div class="pull-right">
				<div style="overflow:auto;">
					<img alt="" src="<?php if( $data['page_pointer'] ){ echo $data['page_pointer']; }else{ echo 'engine/'; } ?>images/logo.png" />
				</div>
				
				<div id="slogan"><?php echo $project['slogan']; ?></div>
				
				<div></div>
				<address>
					<?php echo $project['full_street_address']; ?>
				</address>
			</div>
			<div>
				<h5>
				<?php 
					if( $data['billing_address'] && $data['shipping_address'] && $data['billing_address'] == $data['shipping_address'] ){
						echo 'B & S Address';
				?>
				<?php
					}else{
						echo 'Billing Address';
					}
				?>
				</h5>
				<?php
					echo $data['billing_address'];
				?>
			</div>
		</header>
		<div class="pull-right">
			<?php
                if( $data['payment_status'] == 'processing' || $data['payment_status'] == 'payment_not_processed' || $data['payment_status'] == 'pending_payment' ){
                    $payment_currency = 'dollar';
                    if( defined( 'SELECTED_COUNTRY_ID' ) ){
                        switch( SELECTED_COUNTRY_ID ){
                        case '1157':
                            $payment_currency = 'naira';
                        break;
                        }
                    }
                    
                    echo get_fisrt_bank_payment_form(
                        array(
                            'total_total' => ( $data['total'] + $data['intl_processing_fee'] ),
                            'order_id' => $data['payment_ref'],
                            'actual_order_id' => $data['id'],
                            'first_bank_item_title' => $data['description'],
                            'email' => $user_info['user_email'],
                            'caption' => 'Pay Now',
                            'currency' => $payment_currency,
                            'payment_type' => $data['payment_type'],
                        )
                    );
                }
			?>
		</div>
		<div class="clearfix"></div>
		<article>
			<address>
				<p>Order ID<br />#<b><?php  echo $data['id']; ?></b></p>
				<div style="font-size:0.6em; font-weight:normal;"><?php echo $data['description']; ?></div>
			</address>
			
			<table class="meta">
				<tr>
					<th><span>Order Status</span></th>
					<td><span class="<?php  ?>"><?php echo ucwords( $data['payment_status'] ); ?></span></td>
				</tr>
				<tr>
					<th><span>Date</span></th>
					<td><span><?php echo date( 'd-M-Y H:i' , doubleval( $data['creation_date'] ) ); ?></span></td>
				</tr>
				<?php if( $data['subtotal'] != $data['total'] ){ ?>
				<tr>
					<th><span>Sub Total</span></th>
					<td><span><?php echo convert_currency( $data['subtotal'] ); ?></span></td>
				</tr>
                    <?php if( $data['shopping_cart_percentage_discount'] ){ ?>
                    <tr class="text-error">
                        <th><span><? echo $data['shopping_cart_percentage_discount']; ?>% Discount</span></th>
                        <td><span><?php echo convert_currency( $data['subtotal'] * $data['shopping_cart_percentage_discount'] / 100 ); ?></span></td>
                    </tr>
                    <tr class="text-error">
                        <th><span>Sub Total + Discount</span></th>
                        <td><span><?php echo convert_currency( $data['subtotal'] - ($data['subtotal'] * $data['shopping_cart_percentage_discount'] / 100) ); ?></span></td>
                    </tr>
                    <?php } ?>
				<?php } ?>
				<?php if( $data['shipping'] ){ ?>
				<tr>
					<th><span>Shipping Total</span></th>
					<td><span><?php echo convert_currency( $data['shipping'] ); ?></span></td>
				</tr>
				<?php } ?>
				<?php if( $data['intl_processing_fee'] ){ ?>
				<tr>
					<th><span>Total</span></th>
					<td><span><?php echo convert_currency( $data['total'] ); ?></span></td>
				</tr>
				<tr>
					<th><span>International Processing Fee</span></th>
					<td><span><?php echo convert_currency( $data['intl_processing_fee'] ); ?></span></td>
				</tr>
				<?php } ?>
				<tr>
					<th><span>Amount Due</span></th>
					<td><span><?php echo convert_currency( $data['total'] + $data['intl_processing_fee'] ); ?></span></td>
				</tr>
				<tr>
					<th><span>Payment Type</span></th>
					<td><span><?php echo $data['payment_type']; ?></span></td>
				</tr>
                <?php if( isset( $data['payment_ref'] ) && $data['payment_ref'] ){ ?>
				<tr>
					<th><span>Payment Ref.</span></th>
					<td><span><?php echo $data['payment_ref']; ?></span></td>
				</tr>
                <?php } ?>
                <?php if( isset( $data['transaction_ref'] ) && $data['transaction_ref'] ){ ?>
				<tr>
					<th><span>Transaction Ref.</span></th>
					<td><span><?php echo $data['transaction_ref']; ?></span></td>
				</tr>
                <?php } ?>
			</table>
			<table class="inventory">
				<?php
					switch( $data['table'] ){
					case "merchant_accounts":
						$ordered_items = json_decode( stripslashes( $data['ordered_items_data'] ) , true );
						
						$title = array(
							'desc',
							'price',
						);
						
						$items[] = array(
							'desc' => $ordered_items['desc'],
							'price' => convert_currency( $ordered_items['price'] ),
						);
					break;
					case "store_banners":
					case "store_subscription":
					case "auctioned_product":
					case "advertised_product":
						$ordered_items = json_decode( stripslashes( $data['ordered_items_data'] ) , true );
						
						$title = array(
							'desc',
							'units',
							'price',
							'total',
						);
						
						$items[] = array(
							'desc' => $ordered_items['desc'],
							'units' => $ordered_items['units'],
							'price' => convert_currency( $ordered_items['price'] ),
							'total' => convert_currency( $ordered_items['total'] ),
						);
					break;
					case "purchased_product":
						$ordered_items = json_decode( stripslashes( $data['ordered_items_data'] ) , true );
						
						$title = array(
							'desc',
							'units',
							'price',
							'shipping',
							'total',
						);
                        
						foreach( $ordered_items as $k => & $v ){
							$items[] = array(
								'desc' => $v['desc'],
								'units' => $v['units'],
								'price' => convert_currency( $v['price'] ),
								'shipping' => convert_currency( $v['shipping_cost'] ),
								'total' => convert_currency( ($v['price'] * $v['units']) + $v['shipping_cost'] ),
							);
						}
					break;
					}
				?>
				<thead>
					<tr>
					<th width="9%"><span>S/N</span></th>
					<?php
						foreach( $title as $k ){
					?>
						<th><span><?php echo strtoupper( $k ); ?></span></th>
					<?php
						}
					?>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach( $items as $sn => $item ){
					?>
					<tr>
						<td><span><?php echo $sn+1; ?></span></td>
						<?php foreach( $item as $i ){ ?>
						<td><span><?php echo $i; ?></span></td>
						<?php } ?>
					</tr>
					<?php
						}
					?>
				</tbody>
			</table>
			
			<table class="balance">
				<tr>
					<th><span>Total</span></th>
					<td><span><?php echo convert_currency( $data['total'] ); ?></span></td>
				</tr>
				<?php if( $data['intl_processing_fee'] ){ ?>
				<tr>
					<th><span>International Processing Fee</span></th>
					<td><span><?php echo convert_currency( $data['intl_processing_fee'] ); ?></span></td>
				</tr>
				<tr>
					<th><span>Amount Due</span></th>
					<td><span><?php echo convert_currency( $data['total'] + $data['intl_processing_fee'] ); ?></span></td>
				</tr>
				<?php } ?>
			</table>
		</article>
		<aside>
			<?php
				if( $data['shipping_address'] && $data['billing_address'] != $data['shipping_address'] ){
			?>
			<h1><span><?php echo 'Shipping Address'; ?></span></h1>
			<div>
				<?php echo $data['shipping_address']; ?>
			</div>
			<?php
				}
			?>
		</aside>
		<?php
		}
		?>
		</div>
		