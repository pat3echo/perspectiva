<div class="row">
	<div class="col-lg-12">
		<br style="line-height:0.5;"/>
		<div class="alert alert-info">
			<h4>Cancelled Visit Schedule</h4>
			<p>
			Hi <?php if( isset( $data[ 'visitor_info' ][ 'full_name' ] ) )echo '<b>'.$data[ 'visitor_info' ][ 'full_name' ].'</b>'; ?>,
            <br />
            <?php if( isset( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) && $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ){ ?>
                Your visit for: <?php echo '<b>'.date("d-M-Y", doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ).'</b>'; ?> has been cancelled
            <?php } ?>
			</p>
            
            <hr />
            <p>&nbsp;</p>
            <p>
                <a href="" class="pull-right" title="Schedule Another Appointment">Schedule Another Visit?</a>
            </p>
            <p>&nbsp;</p>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>