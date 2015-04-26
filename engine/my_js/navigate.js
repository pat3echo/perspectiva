var processing_custom_navigate = 0;

activate_custom_navigate();
function activate_custom_navigate(){
	$('a')
	.on('click', function(e){
		if( ! processing_custom_navigate ){
			processing_custom_navigate = 1;
			
			if( ( ( ! $(this).attr('navigate-not') ) || $(this).attr('navigate-not') == true ) && $(this).attr('href').charAt(0) != '#' ){
				e.preventDefault();
				
				window.location.href = $(this).attr('href');
				/*$('<iframe src="'+$(this).attr('href')+'" style="width:100%; height:100%;></iframe>"')
				.insertBefore('body');*/
				//amplify.store( 'navigate' , $(this).attr('href') );
				
				processing_custom_navigate = 0;
			}
		}
	});
	
	$('form.form-navigate')
	.on('submit', function(e){
		if( ! processing_custom_navigate ){
			processing_custom_navigate = 1;
			
			e.preventDefault();
			var url = ( $(this).attr('action') + $(this).serialize() );
			
			window.location.href = url;
			
			processing_custom_navigate = 0;
		}
	});
};

function custom_navigate( href ){
	if (!window.location.origin) {
      window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
    }
    
	if( href ){
        
		//amplify.store( 'navigate' , href );
		
		/*$('<iframe src="'+$(this).attr('href')+'" style="width:100%; height:100%;></iframe>"')
		.insertBefore('body');*/
		window.location.href = href;
		
		processing_custom_navigate = 0;
	}else{
        window.location.href = window.location.origin + window.location.pathname + window.location.search;
    }
	
};