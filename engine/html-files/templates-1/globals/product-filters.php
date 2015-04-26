<style type="text/css">	
.icon-chevron-down {
    background-position: -313px -119px;
}
.icon-chevron-up {
background-position: -288px -120px;
}
.icon-chevron-right {
background-position: -456px -72px;
}
.filter-text{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 80%;
    display: inline-block;
    color: #0088cc;
}
.custom-accordion{
    display:none;
}
.nav-title-1,
.nav-title{
    cursor:pointer;
}
</style>

        <form method="post" id="product-category-form" action="?action=product&todo=filter_product_by_category" style="margin:0;">
            <input type="hidden" value="<?php if( isset( $_POST['data_sort_field'] ) )echo $_POST['data_sort_field']; else echo 'both'; ?>" name="data_sort_field" />
            <input type="hidden" value="<?php if( isset( $_POST['data_view_field'] ) )echo $_POST['data_view_field']; else echo 'grid'; ?>" name="data_view_field" />
            <input type="hidden" value="<?php if( isset( $_POST['data_limit_field'] ) )echo $_POST['data_limit_field']; else echo 0; ?>" name="data_limit_field" />
            <input type="hidden" value="<?php if( isset( $_POST['product_filters_static'] ) )echo $_POST['product_filters_static']; else echo 0; ?>" name="product_filters_static" />
            <input type="hidden" value="" name="categories" />
            <input type="hidden" value="" name="s" />
            <input type="hidden" value="" name="data_loading_more_field" />
            <input type="hidden" name="price_range_min" value="<?php if( isset( $_POST['price_range_min'] ) && $_POST['price_range_min']  )echo convert_currency( $_POST['price_range_min'] , 'from usd' , 1 ); ?>" />
			<input type="hidden" name="price_range_max" value="<?php if( isset( $_POST['price_range_max'] ) && doubleval( $_POST['price_range_max'] ) > 1 )echo convert_currency( $_POST['price_range_max'] , 'from usd' , 1 ); ?>" />
            
			<input type="hidden" name="price_range_max_default" value="<?php if( isset( $_POST['price_range_max_default'] ) && doubleval( $_POST['price_range_max_default'] ) > 1 )echo convert_currency( $_POST['price_range_max_default'] , 'from usd' , 1 ); ?>" />
			<input type="hidden" name="price_range_min_default" value="<?php if( isset( $_POST['price_range_min_default'] ) && $_POST['price_range_min_default'] )echo convert_currency( $_POST['price_range_min_default'] , 'from usd' , 1 ); ?>" />
            
            <input type="hidden" value="<?php if( isset( $_POST['merchant_id'] ) ) echo $_POST['merchant_id']; ?>" name="merchant_id" />
            <input type="hidden" value="<?php if( isset( $_POST['store_id'] ) ) echo $_POST['store_id']; ?>" name="store_id" />
            
		<?php
			if( isset( $data[ 'categories' ] ) && is_array( $data[ 'categories' ] ) && isset( $data[ 'terminating_categories' ] ) && is_array( $data[ 'terminating_categories' ] ) ){
				
				$catgeories = $data[ 'terminating_categories' ];
                $transform_cat = array();
                
                if( isset( $data['selected_category'] ) && $data['selected_category'] ){
                    $selected_category = $data['selected_category'];
             ?>
                <h5 class="nav-title"><i class="icon-chevron-right"></i> <?php echo $data['selected_category_data']['title']; ?></h5>
                <ul class="nav-max-heights-1 nav nav-list custom-accordion">
                <!--<li><h5><?php echo $data['selected_category_data']['title']; ?></h5></li>-->
             <?php
                    
                    foreach( $data[ 'categories' ] as $key => $val ){
                        //$parent_num = count( $catgeories[ $val['category'] ]['parents'] ) - 1;
                        $parent_num = 1;
                        
                        //print_r($catgeories[ $val['category'] ]['parents']);
                       // echo $parent_num;
                        //echo '<hr />';
                        if( isset( $catgeories[ $val['category'] ]['title'] ) ){
                        
                            $title = $catgeories[ $val['category'] ]['title'];
                            $key = $val['category'];
                            
                            if( $parent_num > 0 ){
                                if( isset( $catgeories[ $val['category'] ]['parents'] ) ){
                                    foreach( $catgeories[ $val['category'] ]['parents'] as $parent ){
                                        if( isset( $data['selected_category_data'][ 'children' ][ $parent['id'] ] ) ){
                                            $title = $parent['title'];
                                            $key = $parent['id'];
                                        }
                                    }
                                }
                            }
                            
                            if( isset( $transform_cat[ $title ]['count'] ) ){
                                $transform_cat[ $title ]['count'] += $val['count'];
                                $transform_cat[ $title ]['record_id'][ $val['category'] ] = $val['category'];
                            }else{
                                $transform_cat[ $title ]['key'] = $key;
                                $transform_cat[ $title ]['title'] = $title;
                                $transform_cat[ $title ]['count'] = $val['count'];
                                $transform_cat[ $title ]['record_id'][ $val['category'] ] = $val['category'];
                                
                                $selected_category .= ':::' . $val['category'];
                            }
                        }
                    }
                   // exit;
                    ksort($transform_cat);
                }else{
                ?>
                <h5 class="nav-title"><i class="icon-chevron-right"></i> Categories</h5>
                <ul class="nav-max-heights-1 nav nav-list custom-accordion">
                <!--<li><h5>Categories</h5></li>-->
                <?php
                    foreach( $data[ 'categories' ] as $key => $val ){
                        
                        if( isset( $catgeories[ $val['category'] ][ 'parents' ][1] ) && $catgeories[ $val['category'] ][ 'parents' ][1] ){
                            
                            if( isset( $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] ) ){
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] += $val['count'];
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['record_id'][ $val['category'] ] = $val['category'];
                            }else{
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['key'] = $catgeories[ $val['category'] ][ 'parents' ][1]['id'];
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['title'] = $catgeories[ $val['category'] ][ 'parents' ][1]['title'];
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['count'] = $val['count'];
                                $transform_cat[ $catgeories[ $val['category'] ][ 'parents' ][1]['title'] ]['record_id'][ $val['category'] ] = $val['category'];
                            }
                        }
                    }
                     
                     ksort( $transform_cat );
                }
                
                
                    foreach( $transform_cat as $key => $val ){
                ?>			
                        <li>
                            <label>
                            <a href="#" class="filter-category" data-categories="<?php echo $val['key'] . ':::' . implode( ':::', $val['record_id'] ); ?>">
                            <span class="filter-text" title="<?php echo $val['title']; ?>"><?php echo $val['title']; ?></span>
                            <span class="label pull-right" title="<?php echo round($val['count'], 0); ?>"><small><?php echo round($val['count'], 0); ?></small></span>
                            </a>
                            </label>
                            
                        </li>
                <?php
                    }
                 ?>
            
            <?php
			}
			
            $pfeatures = array();
            
			if( isset( $data[ 'sidebars' ] ) && is_array( $data[ 'sidebars' ] ) ){
				$oldfeature = '';
				foreach( $data[ 'sidebars' ] as $key => $val ){
					//print_r($val);
                    
                    $pfeatures[ $val['feature_title'] ]['title'] = $val['feature_title'];
                    
                    if( isset( $val['currency'] ) )
                        $pfeatures[ $val['feature_title'] ]['currency'] = $val['currency'];
                        
                    if( isset( $val['iso_code'] ) )
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['iso_code'] = $val['iso_code'];
                        
                    if( isset( $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['count'] ) ){
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['count'] += doubleval( $val['count'] );
                    }else{
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['feature'] = $val['feature'];
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['count'] = doubleval( $val['count'] );
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['value'] = $val['value'];
                        $pfeatures[ $val['feature_title'] ]['value'][ $val['value'] ]['id'] = $val['id'];
                    }
					
				}
                //sort to accomodate special features
                ksort($pfeatures);
                
                if( ! isset( $filters_form_url ) )
                    $filters_form_url = '?action=product&todo=filter_product_by_feature';
             ?>
             </ul>
             </form>
             <h5 class="nav-title-1 visible-phone"><i class="icon-chevron-right"></i> Show All Filters</h5>
<div class="hide-in-mobile nav-title-1-child">
            
            <form method="post" id="product-filters-form" action="<?php echo $filters_form_url; ?>">
                <input type="hidden" value="<?php if( isset( $_POST['data_sort_field'] ) )echo $_POST['data_sort_field']; else echo 'both'; ?>" name="data_sort_field" />
                <input type="hidden" value="<?php if( isset( $_POST['data_view_field'] ) )echo $_POST['data_view_field']; else echo 'grid'; ?>" name="data_view_field" />
                <input type="hidden" value="<?php if( isset( $_POST['data_limit_field'] ) )echo $_POST['data_limit_field']; else echo 0; ?>" name="data_limit_field" />
                <input type="hidden" value="<?php if( isset( $_POST['product_filters_static'] ) )echo $_POST['product_filters_static']; else echo 0; ?>" name="product_filters_static" />
                <input type="hidden" value="<?php if( isset( $_POST['categories'] ) )echo $_POST['categories']; else echo 'all'; ?>" name="categories" />
                <input type="hidden" value="" name="s" />
                <input type="hidden" value="<?php if( isset( $_POST['merchant_id'] ) ) echo $_POST['merchant_id']; ?>" name="merchant_id" />
                <input type="hidden" value="<?php if( isset( $_POST['store_id'] ) ) echo $_POST['store_id']; ?>" name="store_id" />
                <input type="hidden" value="" name="data_loading_more_field" />
                
                <ul class="nav nav-list custom-accordion">
             <?php
                //print_r($pfeatures);
                
                $f = array();
                $filtered = array();
                if( isset( $data[ 'filtered' ] ) && $data[ 'filtered' ] ){
                    $f = $data[ 'filtered' ];
                    $filtered = $data[ 'filtered' ];
                }
                
                if( isset( $data[ 'filtered_special' ] ) && $data[ 'filtered_special' ] ){
                    $filtered = array_merge( $f , $data[ 'filtered_special' ] );
                }
                
                foreach( $pfeatures as $k => $v ){
           ?>
            </ul><h5 class="nav-title"><i class="icon-chevron-right"></i> <?php echo $v['title']; if( isset( $v['currency'] ) )echo ' ('.$v['currency'].')'; ?></h5><ul class="nav-max-heights nav nav-list custom-accordion"><!--<li><h5><?php echo $v['title']; ?></h5></li>-->
		<?php
                    foreach( $v['value'] as $vv ){
                        if( $vv['value'] == 'price' ){
                            ?>
			<li>
               
               <label >
                <br />
                <?php
                    $min = convert_currency( $vv['feature']['min'] , 'from usd' , 1 );
                    $max = convert_currency( $vv['feature']['max'] , 'from usd' , 1 );
                ?>
                <input type="text" name="price_range_min" id="price-range-min" value="<?php echo $min; ?>" base-value="<?php echo $min; ?>" class="product-filters-checkbox" />
				<input type="text" name="price_range_max" id="price-range-max" value="<?php echo $max; ?>" base-value="<?php echo $max; ?>" class="product-filters-checkbox" />
				<div id="price-range-slider-container" ></div>
                
                
                <input type="hidden" name="price_range_max_default" value="<?php if( isset( $_POST['price_range_max_default'] ) && doubleval( $_POST['price_range_max_default'] ) > 1 )echo convert_currency( $_POST['price_range_max_default'] , 'from usd' , 1 ); else echo convert_currency( $vv['feature']['max'] , 'from usd' , 1 ); ?>" />
                <input type="hidden" name="price_range_min_default" value="<?php if( isset( $_POST['price_range_min_default'] ) && $_POST['price_range_min_default'] )echo convert_currency( $_POST['price_range_min_default'] , 'from usd' , 1 ); else echo convert_currency( $vv['feature']['min'] , 'from usd' , 1 ); ?>" />
                
                <?php //echo $vv['feature']['q']; ?>
                <!--</a>-->
            </label>
			
			</li>
		<?php
                            break;
                        }
                        if( $vv['count'] ){ 
        ?>
			<li>
               
               <label class="checkbox">
               <!--<a href="#">-->
                <input class="product-filters-checkbox <?php echo str_replace(' ', '-', strtolower( trim( $vv['value'] ) ) ); ?>" name="product_filters[<?php echo $vv['feature']; ?>][]" type="checkbox" value="<?php echo $vv['value']; ?>" <?php if( isset( $filtered[ $vv['feature'] ][ strtolower( $vv['value'] ) ] ) ){ ?> checked="checked" <?php } ?> />
                
                <span class="filter-text" title="<?php echo $vv['value']; ?>">
                    <?php if( isset( $vv['iso_code'] ) && $vv['iso_code'] ){ ?>
                    <img src="<?php 
                        //$pp = $data['pagepointer'];
                        $pp = '';
                        if( isset( $display_pagepointer ) && $display_pagepointer )$pp = $display_pagepointer;else $pp .= 'engine/';
                        
                        if( file_exists( $pp . 'images/country_flag/' . $vv['iso_code'] . '.jpg' ) ){
                            echo $pp . 'images/country_flag/' . $vv['iso_code'] . '.jpg';
                        }else{
                            echo $pp . 'images/country_flag/' . $vv['iso_code'] . '.png';
                        }
                    ?>" title="<?php echo $vv['value']; ?>" alt="<?php echo $vv['value']; ?>" class="pull-left" style="margin-right:5px; margin-top:4px; " />
                    <?php } ?>
                    
                    <?php echo $vv['value']; ?>
                </span>
                <span class="label pull-right" title="<?php echo round($vv['count'], 0); ?>"><small><?php echo round($vv['count'], 0); ?></small></span>
                <!--</a>-->
            </label>
			
			</li>
		<?php
                        }
                    }
                }
         ?>
         
         <?php
			}
		?>
		</ul>
        </form>
 </div>