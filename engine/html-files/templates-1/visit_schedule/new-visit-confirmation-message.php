<div class="row">
	<div class="col-lg-12">
		<br style="line-height:0.5;"/>
		<div class="alert alert-info">
			<h4>Successful Visit Schedule</h4>
            <?php if( isset( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) && $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ){ ?>
                <p>Proposed visit for: <?php echo '<b>'.date("d-M-Y", doubleval( $data[ 'visitor_info' ][ 'proposed_start_date_time' ] ) ).'</b>'; ?></p><hr />
            <?php } ?>
			<p>
			Hi <?php if( isset( $data[ 'visitor_info' ][ 'full_name' ] ) )echo '<b>'.$data[ 'visitor_info' ][ 'full_name' ].'</b>'; ?>,
            <br />A confirmatory email have been sent you
            <br />E: <?php if( isset( $data[ 'visitor_info' ][ 'email' ] ) )echo '<a href="mailto:'.$data[ 'visitor_info' ][ 'email' ].'">'.$data[ 'visitor_info' ][ 'email' ].'</a>'; ?>
			</p>
            
            <hr />
            
            <?php if( isset( $data[ 'visitor_info' ][ 'id' ] ) && $data[ 'visitor_info' ][ 'id' ] ){ ?>
            <p>
                <a href="#" class="btn btn-success ajax-request" ajax-request="true" data-action="visit_schedule" data-todo="reschedule_visit" data-record-id="<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>" title="Make Changes to the Information you provided"><i class="icon-refresh white"></i> Re-schedule Visit</a> 
                <a href="#" class="btn btn-danger ajax-request" ajax-request="true" data-action="visit_schedule" data-todo="cancel_visit" data-record-id="<?php echo $data[ 'visitor_info' ][ 'id' ]; ?>" title="Cancel Visit"><i class="icon-trash white"></i> Cancel Visit</a>
            </p>
            <?php } ?>
            
            <p>
                Upon approval of your visit, you'll be notified via email
            </p>
            <p>&nbsp;</p>
            <p>
                <a href="" class="pull-right" title="Schedule Another Appointment">Schedule Another Visit?</a>
            </p>
            <p>&nbsp;</p>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>