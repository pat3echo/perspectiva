<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">
		<ul class="nav" id="side-menu">
            
			<li class="sidebar-search" >
                Menu
			</li>
			<?php 
				if( isset( $data['dashboard_menu'] ) && is_array( $data['dashboard_menu'] ) ){	
					foreach( $data['dashboard_menu'] as $key => $menu ){	//start loop 1
			?>
			<li>
				<a class="active1" href="<?php echo $menu['link']; ?>" title="<?php echo $menu['title']; ?>"><i class="<?php echo $menu['icon-class']; ?>"></i> <?php echo $menu['text']; ?></a>
				<?php if( isset( $menu['children'] ) && is_array( $menu['children'] ) ){ //start children ?>
					<ul class="nav nav-second-level">
					<?php	foreach( $menu['children'] as $ckey => $cmenu ){	//start loop 2	?>
				
					<li>
						<a href="<?php echo $cmenu['link']; ?>" title="<?php echo $cmenu['title']; ?>"><?php echo $cmenu['text']; ?></a>
					</li>
					<?php }	//end loop 2 ?>
				</ul>
				<!-- /.nav-second-level -->
				<?php } //end children ?>
			</li>
			<?php 
					}	//end loop 1 
				}
			?>
		</ul>
	</div>
	<!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->