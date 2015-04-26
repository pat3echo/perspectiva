<form method="post" id="store-category-form" action="?action=store&todo=filter_store_by_category">
    <input type="hidden" value="<?php if( isset( $_POST['data_sort_field'] ) )echo $_POST['data_sort_field']; else echo 'both'; ?>" name="data_sort_field" />
    <input type="hidden" value="<?php if( isset( $_POST['data_view_field'] ) )echo $_POST['data_view_field']; else echo 'grid'; ?>" name="data_view_field" />
    <input type="hidden" value="<?php if( isset( $_POST['data_limit_field'] ) )echo $_POST['data_limit_field']; else echo 0; ?>" name="data_limit_field" />
    <input type="hidden" value="" name="s" />
    <input type="hidden" value="" name="categories" />
    <input type="hidden" value="" name="data_loading_more_field" />
<ul class="nav nav-pills" style="border-top:1px solid #eee; border-bottom:1px solid #eee;">
<?php
    if( isset( $data[ 'categories' ] ) && is_array( $data[ 'categories' ] ) && isset( $data[ 'terminating_categories' ] ) && is_array( $data[ 'terminating_categories' ] ) ){
        
        $catgeories = $data[ 'terminating_categories' ];
?>
    <li class="active"><a href="?page=stores" class="store-category-filter11" data-record-id="">All Stores</a></li>
    <?php
        $transform_cat = array();
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
        
        foreach( $transform_cat as $key => $val ){
    ?>			
            <li>
                <a href="#" class="store-category-filter" data-record-id="<?php echo $val['key'] . ':::' . implode( ':::', $val['record_id'] ); ?>">
                <?php echo $val['title']; ?>
                <!--<span class="label pull-right"><small><?php //echo $val['count']; ?></small></span>-->
                </a>
            </li>
    <?php
        }
    }
?>
</ul>
</form>