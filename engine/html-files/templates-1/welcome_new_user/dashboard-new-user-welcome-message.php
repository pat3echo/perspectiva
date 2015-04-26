<div class="row">
	<div class="col-lg-12">
		<br style="line-height:0.5;"/>
		<div class="alert alert-info alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4>Welcome!!! <?php if( isset( $user_info['user_full_name'] ) )echo $user_info['user_full_name']; ?></h4>
			<p>Thank you for signing up with zidoff.com</p>
            <?php if( isset( $data['verified_email_address'] ) && strtolower( $data['verified_email_address'] ) != 'yes' ){ ?>
			<p>A verification email has been sent to your email address, please do open the email and click on the link to activate your account.</p>
			<p><strong>NB:</strong> Please check your <strong>spam folder</strong> if you fail to find this email</p>
            <?php } ?>
		</div>
	</div>
	<!-- /.col-lg-12 -->
</div>