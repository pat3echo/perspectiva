App.init();    
App.initBxSlider();
Index.initRevolutionSlider();

function activate_ajax_request_elements(){
	$('.ajax-request')
	.on('click', function(e){
		if( $(this).attr('data-toggle') == 'tab' ){
			$('#inventory-manager-form-holder')
			.find('.tab-pane')
			.removeClass('in')
			.removeClass('active');
		}
		
		e.preventDefault();
		trigger_ajax_request( $(this) );
	});
};

call_custom_function_before_ajax_processing = 1;
function custom_function_before_ajax_processing( data ){
	data.reload_table = 0;
	
	if( data.status ){
		switch( data.status ){
		case "display-data-capture-form":
		case "second-step-form":
		case "third-step-form":
			if( data.html ){
                $('#dynamic-form-container').html( data.html );
                
                //Activate Client Side Validation / Tooltips
                activate_tooltip_for_form_element( $('#dynamic-form-container').find('form') );
                activate_validation_for_required_form_element( $('#dynamic-form-container').find('form') );
                
                //Bind Form Submit Event
                $('#dynamic-form-container')
                .find('form')
                .bind('submit', function( e ){
                    e.preventDefault();
                    
                    submit_form_data( $(this) );
                    
                });
                
                //Activate Ajax file upload
                createUploader();
            }
		break;
        case "sign-up-complete":
        case "visit-schedule-cancelled":
            if( data.html ){
                $('#dynamic-form-container').html( data.html );
                activate_ajax_request_elements();
            }
		break;
		}
	}
};