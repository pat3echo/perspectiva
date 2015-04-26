<style type="text/css">form .form-box .input-row{ width:100% !important; clear:both !important; }</style>
<div class="row-fluid">
<?php
	if( isset( $data['verification_data'] ) && $data['verification_data'] && ( ! is_array($data['verification_data']) ) ){
?>
	<div class="span7">
<?php
		echo $data['verification_data'];
?>
	</div>
<?php
	}else{
?>
	<div class="span7 hide-in-mobile" style="text-align:center; display:table-cell;">
        <?php
            $key = 'page_banners';
            $login_banners = array();
            if( isset( $data[$key] ) && $data[$key] ){
                //print_r($data[$key]);
                foreach( $data[$key] as $a ){
                    if( $a['status'] != 'active' ){
                        continue;
                    }
                    if( $a['page_type'] != 'login' ){
                        continue;
                    }
                    
                    if( file_exists( $data['pagepointer'].$a['banner_image'] ) ){
                        $login_banners[] = '<img src="'.$data['pagepointer'].$a['banner_image'].'" />';
                    }
                }
            }
            
            shuffle( $login_banners );
            if( isset( $login_banners[0] ) )
                echo $login_banners[0];
        ?>
    </div>
<?php
	}
?>
	<div class="span5 shade-outer">
		<div class="shade">
			<h4><div class="pull-left-desktop"><?php echo SITE_USERS_SIGN_IN; ?> &rarr;</div></h4>
			<?php if( isset( $data['html'] ) )echo $data['html']; ?>
			<div style="clear:both;"></div>
			<hr />
			<br />
			<div class="btn-group pull-right-desktop">
				<a href="?page=register" class="btn btn-inverse"><?php echo SITE_USERS_REGISTER; ?></a>
				<?php
					$field = 'ALLOW FACEBOOK SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE' && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" class="btn btn-primary"><?php echo SITE_USERS_SIGN_IN_WITH_FACEBOOK; ?></a>
				<?php
				}
				?>
				<?php
					$field = 'ALLOW GMAIL SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE' && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" id="click-me" class="btn btn-danger"><?php echo SITE_USERS_SIGN_IN_WITH_GMAIL; ?></a>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>