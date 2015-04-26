
	<?php 
        $search_string = '';
		//check for error
		if( isset( $data['typ'] ) && $data['typ'] ){
			switch( $data['typ'] ){
			case 'uerror':
			case 'serror':
				echo $data['html'];
	?>
			<a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=marketplace" class="btn btn-info">Consider Browsing Our Catalog</a>
			
	<?php
			break;
			}
		}else{
	?>
    <div class="row-fluid">
		<div class="span12">
		<small>
		  <a href="<?php if( isset( $site_url ) && $site_url )echo $site_url; ?>?page=<?php 
            if( isset( $_POST['store_id'] ) && $_POST['store_id'] ){
                ?>store-page&<?php echo $_POST['store_id']; 
            }else{
                if( isset( $_POST['product_filters_static'] ) && $_POST['product_filters_static'] ){ echo $_POST['product_filters_static']; }
                else{ ?>marketplace<?php }
            }
            
            if( isset( $_GET['s'] ) && $_GET['s'] ){
                ?>&s=<?php echo $_GET['s']; $search_string = $_GET['s']; ?>&categories=all&data_view_field=list&data_sort_field=both<?php
            }else{
                if( isset( $_POST['s'] ) && $_POST['s'] ){
                    ?>&s=<?php echo $_POST['s']; $search_string = $_POST['s']; ?>&categories=all&data_view_field=list&data_sort_field=both<?php
                }
            }
            ?>" >All Categories</a>
		</small>
		<small id="category-breadcrum">
		  <?php
            if( isset( $data['selected_category_data'] ) ){
                $breadcrum = '';
                $prev_parent = '';
                
                $si = '';
                if( isset( $site_url ) && $site_url )$si = $site_url;
                
                if( isset( $data['selected_category_data']['parents'] ) && ! empty( $data['selected_category_data']['parents'] ) ){
                    foreach( $data['selected_category_data']['parents'] as $parent_id => & $parent ){
                        $parent = get_selected_category( array( 'id' => $parent_id, 'function_name' => 'get_all_categories' ) );
                        if( $parent_id ){
                            $breadcrum = ' > <a data-href="'.$si.'?page=product-category&record_id='.$parent_id.'" href="'.$si.'category/'.clean1($parent['title']).'-'.$parent['serial_num'].'" title="'.$parent['title'].'">' . $parent['title'] . '</a>' . $breadcrum;
                        }
                    }
                }
                
                echo $breadcrum . ' > ' . $data['selected_category_data']['title'];
            }
          ?>
		</small>
	  </div>
      <div class="row-fluid">
		<div class="span12">
            <?php if( $search_string )echo '<span class="muted">Search results for</span> &quot;'.$search_string.'&quot;'; ?>
        </div>
      </div>
	  <div class="row-fluid">
		<div class="span12" id="filtered-label">
		<small>
		
        <?php
            $show_clear_all = 0;
            
            $f = array();
            $filtered = array();
            if( isset( $data[ 'filtered' ] ) && $data[ 'filtered' ] ){
                $f = $data[ 'filtered' ];
                $filtered = $data[ 'filtered' ];
            }
            
            if( isset( $data[ 'filtered_special' ] ) && $data[ 'filtered_special' ] ){
                $filtered = array_merge( $f , $data[ 'filtered_special' ] );
            }
            
            if( ! empty( $filtered ) ){
                $show_clear_all = 1;
            }
            
            if( isset( $_POST['price_range_min'] ) && $_POST['price_range_min'] && isset( $_POST['price_range_max'] ) && doubleval( $_POST['price_range_max'] ) > 1 ){
                if( isset( $_POST['price_range_min_default'] ) && isset( $_POST['price_range_max_default'] ) && ( $_POST['price_range_min_default'] != $_POST['price_range_min'] || $_POST['price_range_max_default'] != $_POST['price_range_max'] ) ){
                    $show_clear_all = 1;
                }
            }
            
            if( $show_clear_all ){
            ?>
        <div style="white-space:nowrap; margin-right:5px; padding:4px 27px 4px 7px;" class="alert alert-danger pull-left">
			<button type="button" class="close clear-all-label" data-dismiss="alert">&times;</button>
            Clear All
		</div>
        <?php
            }
            
            if( isset( $_POST['price_range_min'] ) && $_POST['price_range_min'] && isset( $_POST['price_range_max'] ) && doubleval( $_POST['price_range_max'] ) > 1 ){
                if( isset( $_POST['price_range_min_default'] ) && isset( $_POST['price_range_max_default'] ) && ( $_POST['price_range_min_default'] != $_POST['price_range_min'] || $_POST['price_range_max_default'] != $_POST['price_range_max'] ) ){
        ?>
        <div style="white-space:nowrap; margin-right:5px; padding:4px 27px 4px 7px;" class="filtered-label alert alert-info pull-left" data-class="price_range_max">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo convert_currency( convert_currency( $_POST['price_range_min'], 'to usd', 1) ); ?> - <?php echo convert_currency( convert_currency( $_POST['price_range_max'], 'to usd', 1 ) ); ?>
		</div>
        <?php
                }
            }
            
            $country = array();
            foreach( $filtered as $filter ){
                foreach( $filter as $k => $v ){
        ?>
        <div style="white-space:nowrap; margin-right:5px; padding:4px 27px 4px 7px;" class="filtered-label alert alert-info pull-left" data-class="<?php if( isset( $v['country'] ) ) echo str_replace(' ', '-',  trim( strtolower( $v['country'] ) ) ); else echo str_replace(' ', '-',  trim($k) ); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php if( isset( $v['country'] ) ){ echo ucwords( $v['country'] ); } else echo ucwords($k); ?>
		</div>
        <?php
                }
            }
        ?>
			
		</small>
		</div>
	  </div>
	</div>
    <!-- ./row -->
    <?php include "load-more-products.php"; ?>
    
	<?php
	}
	?>