<div class="container">
<div class="row-fluid">
	<div class="span9">
	
	<div class="row-fluid">
		<div class="span12">
		<h4><?php echo $title; ?></h4>
		<hr />
		</div>
	</div>
	<!-- ./row -->
	<?php 
		//check for error
		if( isset( $data['typ'] ) && $data['typ'] ){
			switch( $data['typ'] ){
			case 'serror':
				echo $data['html'];
	?>
			<button class="btn btn-info">Consider Browsing Our Catalog</button>
			
	<?php
			break;
			}
		}else{
	?>
    <div class="row-fluid">
		<?php
			$nav = '';
			if( isset( $data[ 'content' ] ) && is_array( $data[ 'content' ] ) ){
				foreach( $data[ 'content' ] as $i => $content ){
					if( isset( $content['serial_number'] ) && $content['serial_number'] == 1 ){
						echo '<div id="'.$content['id'].'">';
						echo $content['content'];
						echo '</div><br /><hr /><br />';
						
						unset( $data[ 'content' ][$i] );
					}else{
						$nav .= '<li><a href="#'.$content['id'].'">'.$content['content_title'].'</a></li>';
					}
				}
				
				if( $nav ){
					echo '<h4>'.WEBSITE_TABLE_OF_CONTENT_HEADING.'</h4>';
					
					echo '<ul>'.$nav.'</ul><br /><hr /><br />';
				}
				
				foreach( $data[ 'content' ] as $content ){
					if( isset( $content['content'] ) ){
						echo '<div id="'.$content['id'].'">';
						echo $content['content'];
						echo '</div><br /><hr /><br />';
					}
				}
			}
		?>
    </div>
	<!-- ./row -->
	<?php
		}
	?>
	
	</div>
	<!-- ./span9 -->
	
	<div class="span3 well">
		<?php
			if( isset( $data[ 'sidebars' ] ) && is_array( $data[ 'sidebars' ] ) ){
				foreach( $data[ 'sidebars' ] as $k => $sidebar ){
					echo $sidebar['content_html'];
				}
			}
		?>
	</div>
	<!-- ./span3 -->
	
	</div>
<!-- ./row -->
</div>
<!-- ./container -->