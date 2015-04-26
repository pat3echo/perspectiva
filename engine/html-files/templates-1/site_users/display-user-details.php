<div class="span7">
<h4>Personal Info <button class="btn btn-mini btn-xs btn-info pull-right edit-button" data-fields="personal-info" data-form-id="site_users-form" data-form-action="<?php if( isset( $data['save_personal_info'] ) )echo $data['save_personal_info']; ?>">edit</button></h4>
<hr />
<div class="row-fluid">
	<div class="span5">
        <?php
            $sex = 'male';
            if( isset( $data['user_details']['sex'] ) && $data['user_details']['sex'] == 'female' )
                $sex = $data['user_details']['sex'];
                
                //&& file_exists( $data['page_pointer'] . $data['user_details']['photograph'] )
        ?>
		<img src="<?php if( isset( $data['user_details']['photograph'] ) && $data['user_details']['photograph']  )echo $data['page_pointer'] . $data['user_details']['photograph']; else echo $data['page_pointer'] . 'images/'.$sex.'.jpg';	?>" class="img-circle" style="border:1px solid #ddd;width:80%;"alt="<?php if( isset( $data['user_details']['firstname'] ) )echo $data['user_details']['firstname']; ?>" title="<?php if( isset( $data['user_details']['firstname'] ) )echo $data['user_details']['firstname']; if( isset( $data['user_details']['lastname'] ) )echo ' '.$data['user_details']['lastname'];?>" />
	</div>
	<div class="span7">
		<h4>
			<?php 
				if( isset( $data['user_details']['firstname'] ) )echo $data['user_details']['firstname'];
				
				if( isset( $data['user_details']['lastname'] ) )echo ' '.$data['user_details']['lastname'];
			?>
		</h4>
		<h5>
			<?php
				if( isset( $data['user_details']['sex'] ) )echo get_select_option_value( $settings = array( 'id' => $data['user_details']['sex'], 'function_name' => 'get_sex' ) );
				
				if( isset( $data['user_details']['birthday'] ) )echo ' - '.date( "jS-M-Y", $data['user_details']['birthday'] );
			?>
		</h5>
		<p>
			<?php 
				if( isset( $data['user_details']['email'] ) )echo 'e-mail: ' . $data['user_details']['email'];
			?>
			<br />
			<?php
				if( isset( $data['user_details']['phonenumber'] ) )echo 'phone: '.$data['user_details']['phonenumber'];
			?>
		</p>
		<button class="btn btn-mini btn-xs btn-info edit-button" data-fields="password-info" data-form-id="site_users-form" data-form-action="<?php if( isset( $data['save_password_info'] ) )echo $data['save_password_info']; ?>">change password</button>
	</div>
</div>
</div>
<div class="span5">
<h4>Contact Info <button class="btn btn-mini btn-xs btn-info pull-right edit-button" data-fields="contact-info" data-form-id="site_users-form" data-form-action="<?php if( isset( $data['save_contact_info'] ) )echo $data['save_contact_info']; ?>">edit</button></h4>
<hr />
<div class="row-fluid">
	<div class="span12">
		<p>
			<?php 
				if( isset( $data['user_details']['address_html'] ) )echo $data['user_details']['address_html'];
		?>
		</p>
		<?php
				if( ! ( isset( $data['user_details']['address_status'] ) && $data['user_details']['address_status'] ) ){
			?>
					<button class="btn btn-small btn-danger edit-button" data-fields="contact-info" data-form-id="site_users-form" data-form-action="<?php if( isset( $data['save_contact_info'] ) )echo $data['save_contact_info']; ?>">Update Your Contact Details</button>
			<?php } ?>
	</div>
</div>
</div>