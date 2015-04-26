<div class="row">
	<div class="col-lg-12">
		<br style="line-height:0.5;"/>
		<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4>Successful Email Account Verification</h4>
			<p>
			Congratulations! <?php if( isset( $user_info['user_full_name'] ) )echo '<b>'.$user_info['user_full_name'].'</b>'; ?> who have successfully verified your email address <?php if( isset( $user_info['user_email'] ) )echo '<a href="mailto:'.$user_info['user_email'].'">'.$user_info['user_email'].'</a>'; ?>
			</p>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>