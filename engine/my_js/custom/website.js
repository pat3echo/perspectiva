$('select#country-select-box')
.on('change', function(){
	$(this)
    .attr( 'data-record-id', $(this).val() )
    .css( 'backgroundImage', 'url('+$(this).attr('data-pointer')+')' );
	
	trigger_ajax_request( $(this) );
});

/*
$('#country-select-form')
.on('submit', function(e){
    e.preventDefault();
    
    $('#country-popover-container').hide();
    
    $('#country-select-flag')
    .attr( 'src', $('#country-select-flag').attr('data-pointer') );
	
    $(this)
    .attr( 'data-record-id', $(this).find('select#country-select-box').val() )
    
    trigger_ajax_request( $(this) );
    
});
*/

$('a#country-select-popover')
.on('click', function(e){
    e.preventDefault();
    
    $('#country-popover-container')
    .toggle();
});

$('#country-popover-container')
.on('mouseleave', function(e){
    //$(this).hide();
});

var not_shown_back_top_button = true;

$('#back-to-top-button-container')
.on('click', function(e){
    $(document).scrollTop(0);
    not_shown_back_top_button = true;
    $(this).hide();
});


$(window)
.on('scroll', function(){
    if( $(document).scrollTop() > 300 ){
        if( not_shown_back_top_button ){
            $('#back-to-top-button-container')
            .show();
            not_shown_back_top_button = false;
        }
    }else{
        $('#back-to-top-button-container')
        .hide();
        not_shown_back_top_button = true;
    }
});

get_shopping_cart_items();
function get_shopping_cart_items(){
    trigger_ajax_request( $('#shopping-cart-dropdown-box') );
};

call_custom_function_after_ajax_processing = 1;
function custom_function_after_ajax_processing( data ){
	if( data.status ){
		switch( data.status ){
		case "country-changed":
			custom_navigate('');
		break;
        case "items-added-to-cart":
		case "cart-items-update":
			if( data.total_items && data.html ){
                $('#shopping-cart-dropdown-box-count')
                .html('('+data.total_items+')');
                
                $('#shopping-cart-dropdown-box-container')
                .html( data.html );
            }else{
                $('#shopping-cart-dropdown-box-count')
                .html('');
                
                if( data.html ){
                    $('#shopping-cart-dropdown-box-container')
                    .html( data.html );
                }
            }
		break;
		}
	}
};