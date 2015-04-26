<?php
    if( isset( $data['view'] ) && $data['view'] == 'list' ){
        include "all-products-list-view.php";
    }else{
        include "all-products-grid-view.php";
    }
?>
<?php
    $limit_size = 20;
    if( isset( $data['limit_size'] ) ){
        $limit_size = $data['limit_size'];
    }
    
    if( isset( $data['products'] ) && count( $data['products'] ) > ( $limit_size - 1 ) ){
 ?>
<div class="row-fluid" style="text-align:center;" id="load-more-button-container">
    <button class="btn" title="Load More Items..." id="load-more-button" limit-size="<?php echo $limit_size; ?>">Load More</button>
</div>
<!-- ./row -->
<?php
    }
?>