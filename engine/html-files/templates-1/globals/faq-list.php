<?php
$key = 'faq_list';
if( isset( $data[$key] ) && is_array( $data[$key] ) && ! empty( $data[$key] ) ){
    if( isset( $data[ 'faq_list_title' ] ) && $data[ 'faq_list_title' ] ){
    ?>
    <h4><?php echo $data[ 'faq_list_title' ]; ?></h4>
    <?php
    }
?>
<ul class="nav nav-list bs-docs-sidenav" style="max-height: 362px; overflow-y: scroll;">
    <?php
        foreach( $data[$key] as $k => $val ){
    ?>
        <li><a href="#" class="ajax-request" ajax-request="true" data-action="support_enquiry" data-todo="get_faq" data-record-id="<?php echo $val['serial_num']; ?>" title="<?php echo $val['faq_title']; ?>"><i class="icon-chevron-right"></i> <?php echo $val['faq_title']; ?></a></li>
    <?php
        }
?>
</ul>
<?php 
}else{
?>
<div class="alert alert-danger">
	<?php
    if( isset( $data[ 'faq_list_title' ] ) && $data[ 'faq_list_title' ] ){
    ?>
    <h4><?php echo $data[ 'faq_list_title' ]; ?></h4>
    <?php
    }
    ?>
	<p>No result was found</p>
    <p>Please try using a different search term.</p>
</div>
<br />
<a href="?page=faq" class="btn btn-info" title="View All FAQ">View All FAQ</a>
<?php
}
?>