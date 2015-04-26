
<div class="container">
<div class="row-fluid">
	<div class="span12">
	<h4><?php echo $title; ?></h4>
	</div>
</div>
<!-- ./row -->
	
<div class="row-fluid">
	
	<div class="span3 well">
		<?php
            $table = '';
            if( isset( $data[ 'database_table' ] ) )
                $table = $data[ 'database_table' ];
                
			$nav = '';
			if( isset( $data[ 'content' ] ) && is_array( $data[ 'content' ] ) ){
				foreach( $data[ 'content' ] as $i => $content ){
					$nav .= '<li><a href="#'.$content['id'].'">'.$content['content_title'].'</a></li>';
				}
				
				if( $nav ){
					echo '<h4>'.WEBSITE_TABLE_OF_ARTICLES_HEADING.'</h4>';
					
					echo '<ul>'.$nav.'</ul><br /><hr /><br />';
				}
			}
			
			if( isset( $data[ 'sidebars' ] ) && is_array( $data[ 'sidebars' ] ) ){
				foreach( $data[ 'sidebars' ] as $k => $sidebar ){
					echo $sidebar['content_html'];
				}
			}
		?>
	</div>
	<!-- ./span3 -->
	
	<div class="span9">
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
			if( isset( $data[ 'content' ] ) && is_array( $data[ 'content' ] ) ){
				
				foreach( $data[ 'content' ] as $content ){
					if( isset( $content['content'] ) ){
						echo '<div id="'.$content['id'].'">';
						echo translate_2( 
                            array(
                                'words' => $content['content'],
                                'table' => $table,
                                'field' => 'content',
                                'record_id' => $content['id'],
                            ) 
                        );
						echo '</div><br />';
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
	
	
	</div>
<!-- ./row -->
</div>
<!-- ./container -->