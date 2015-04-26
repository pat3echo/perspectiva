<div class="row-fluid">
	<div class="span7 hide-in-mobile" style="text-align:center;">
        <?php
            $key = 'page_banners';
            $login_banners = array();
            if( isset( $data[$key] ) && $data[$key] ){
                //print_r($data[$key]);
                foreach( $data[$key] as $a ){
                    if( $a['status'] != 'active' ){
                        continue;
                    }
                    if( $a['page_type'] != 'register' ){
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
	<div class="span5 shade-outer" id="site-registration">
		<div class="shade">
			<div class="btn-group btn-group-responsive pull-right-desktop">
				<a href="?page=login" class="btn btn-inverse"><?php echo SITE_USERS_SIGN_IN; ?></a>
				<?php
					$field = 'ALLOW FACEBOOK SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE' && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" class="btn btn-primary"><?php echo SITE_USERS_SIGN_IN_WITH_FACEBOOK; ?></a>
				<?php
				}
					$field = 'ALLOW GMAIL SIGNIN';
					if( isset( $data[ 'general_settings' ][ $field ][ 'default' ] ) && $data[ 'general_settings' ][ $field ][ 'default' ] == 'TRUE'  && isset( $data['service_url'][$field] ) && $data['service_url'][$field] ){
				?>
				<a href="<?php echo $data['service_url'][$field]; ?>" id="click-me" class="btn btn-danger"><?php echo SITE_USERS_SIGN_IN_WITH_GMAIL; ?></a>
				<?php
				}
				?>
			</div>
			<div style="clear:both;"></div>
			<br style="line-height:30px;"/>
			<hr />
			<div style=" margin: 0 auto;  position: relative; width: 40px; text-align: center; height: 40px;  background: #f5f5f5; line-height: 40px;  border-radius: 50%;  margin-top: -32px;   color: #555;  font-size: 0.6em; border:1px solid #eee;">
				<?php echo SITE_USERS_OR; ?>
			</div>
			<h4><div class="pull-left-desktop"><?php echo SITE_USERS_SIGN_UP_AS; ?> &rarr;</div></h4>
			<div class="pull-right-desktop ">
				<label class="radio inline" style="white-space:nowrap;">
					<input type="radio" name="role" value="buyer" data-toggle="radio" checked="checked" data-control-id="<?php echo $data[ 'table_fields' ]['role']; ?>"><?php echo SITE_USERS_A_BUYER; ?>
				</label>
				<label class="radio inline" style="white-space:nowrap;">
					<input type="radio" name="role" value="seller" data-toggle="radio" data-control-id="<?php echo $data[ 'table_fields' ]['role']; ?>"><?php echo SITE_USERS_A_SELLER; ?> 
				</label>
			</div>
			<div style="clear:both;"></div>
			
			<?php if( isset( $data['html'] ) )echo $data['html']; ?>
		</div>
	</div>
</div>