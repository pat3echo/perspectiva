(function(jQuery) {	$(document).ready(function(){ /*
 *  AJAX Requests Handler jQuery File
 *	written by Patrick Ogbuitepu
 *	Copyright (c) August 2014
 */
var current_module = '1357383943_1';
var current_record = '';

//Store HTML ID of Last Clicked Function
var clicked_menu = '';

//Store HTML ID of Last Clicked Popup Function
var clicked_main_menu = '';

//Name of Table to Search
var search_table = '';

//Name of Table to Toggle its Columns
var column_toggle_table = '';

//Number of Column to toggle
var column_toggle_num = '';

//Name of Column to toggle
var column_toggle_name = '';

//Selected Record ID
var single_selected_record = '';

//Selected Records IDs
var multiple_selected_record_id = '';

//Selected Records Details
var details_of_multiple_selected_records = '';

var class_action = '';
var class_name = '';
var module_id = '';

var pagepointer = $('#pagepointer').text();

var clicked_action_button = '';
var confirm_action_prompt = 1;

var form_method = 'get';
var ajax_data_type = 'json';
var ajax_data = '';
var ajax_get_url = '';
var ajax_action = '';
var ajax_container;
var ajax_notice_container;

//AJAX Request Data Before Sending
var ajax_request_data_before_sending_to_server = '';

var function_click_process = 1;

var cancel_ajax_recursive_function = false;

var oTable;

var oNormalTable;
var ie_token = '';

//Last Position of Mouse on mouseup event
var last_position_of_mouse_on_mouse_up_x = 0;
var last_position_of_mouse_on_mouse_up_y = 0;

//Determine if Menus have been bound to actions after initialization
var bound_menu_items_to_actions = 0;

//Variable that determines the currently edited textarea
var editing_textarea;

//Variable that determines whether or not to call function after ajax output processing
var call_custom_function_after_ajax_processing = 0;

//Variable that determines whether or not to call function before ajax output processing
var call_custom_function_before_ajax_processing = 0;

//Variable that determines whether or not to call function before ajax output processing
var call_custom_function_after_ajax_processing_second = 0;

function ajax_send(){
	if(function_click_process){
	//Send Data to Server
    
    if ($.browser.msie && parseInt($.browser.version, 10) >= 8 && window.XDomainRequest) {
            var cookies = document.cookie.split(';');
            $.each( cookies , function( key, value ){
                var cook = value.split('=');
                
                if( cook[0].trim() == 'PHPSESSID' ){
                    document.phpsid = cook[1];
                    return;
                }
            });
            
            // Use Microsoft XDR
            if( form_method == 'get' ){
                $.each( ajax_data, function(key, value){
                    ajax_get_url += '&'+key+'='+value;
                });
            }
            
            if( ie_token ){
                ajax_get_url += '&ietokenfix='+ie_token;
            }
            if( document.phpsid ){
                ajax_get_url += '&iefixsid='+document.phpsid;
            }
            if( ajax_get_url.charAt(0) != '?' )ajax_get_url = '?'+ajax_get_url;
            
            var xdr = new XDomainRequest();
            xdr.open(form_method, pagepointer+'php/ajax_request_processing_script.php'+ajax_get_url+'&browser=ie&formmethod='+form_method);
            xdr.onload = function () {
            function_click_process = 1;
            var JSON = $.parseJSON(xdr.responseText);
            
            if (JSON == null || typeof (JSON) == 'undefined')
            {
                JSON = $.parseJSON(data.firstChild.textContent);
            }
                ajax_request_function_output(JSON);
                if(JSON.tok){
                    ie_token = JSON.tok;
                }
                
            };
            xdr.onprogress = function(){ };
            xdr.ontimeout = function(){ };
            xdr.onerror = function () { };
            setTimeout(function(){
                function_click_process = 0;
                
                cancel_ajax_recursive_function = false;
                
                confirm_action_prompt = 0;
                
                if( form_method == 'post' ){
                    xdr.send( ajax_data );
                }else{
                    xdr.send();
                }
                
                $('#generate-report-progress-bar')
                .html('<div id="virtual-progress-bar" class="progress progress-striped"><div class="progress-bar bar"></div></div>');
                progress_bar_change();
                
            }, 0);
        
    }else{
        $.ajax({
            
            dataType:ajax_data_type,
            type:form_method,
            data:ajax_data,
            url: pagepointer+'php/ajax_request_processing_script.php'+ajax_get_url,
            timeout:30000,
            beforeSend:function(xhr){
                /*
                if( ajax_data_type == 'json' ){
                    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
                }*/
                //Display Loading Gif
                function_click_process = 0;
                
                cancel_ajax_recursive_function = false;
                
                confirm_action_prompt = 0;
                
                /*ajax_container.html('<div id="loading-gif" class="no-print">Please Wait</div>');*/
                
                $('#generate-report-progress-bar')
                .html('<div id="virtual-progress-bar" class="progress progress-striped"><div class="progress-bar bar"></div></div>');
                progress_bar_change();
                
                ajax_request_data_before_sending_to_server = '';
                ajax_request_data_before_sending_to_server += '<p><b>dataType:</b> '+ajax_data_type+'</p>';
                ajax_request_data_before_sending_to_server += '<p><b>type:</b> '+form_method+'</p>';
                if( typeof(ajax_data) == "object" )
                    ajax_request_data_before_sending_to_server += '<p><b>data:</b> '+ $.param( ajax_data ) +'</p>';
                else
                    ajax_request_data_before_sending_to_server += '<p><b>data:</b> '+ ajax_data +'</p>';
                
                ajax_request_data_before_sending_to_server += '<p><b>url:</b> '+pagepointer+'php/backend.php'+ajax_get_url+'</p>';
                
            },
            error: function(event, request, settings, ex) {
                
                if( function_click_process == 0 && event.responseText ){
                    
                    //Refresh Page
                    function_click_process = 1;
                    
                    //Display Timeout Error Message
                    var theme = 'a';
                    var message_title = 'AJAX Request Error';
                    var message_message = "Error requesting page!<br /><br /><h4>Request Parameters</h4>" + ajax_request_data_before_sending_to_server + "<br /><h4>Response Text</h4><p><textarea>" + event.responseText + "</textarea></p>";
                    var auto_close = 'no';
                    
                    no_function_selected_prompt(theme, message_title, message_message, auto_close);
                    
                }
            },
            success: function(data){
                
                function_click_process = 1;

                switch(ajax_action){
                case "generate_modules":
                    ajax_generate_modules(data);
                break;
                case "request_function_output":
                    ajax_request_function_output(data);
                break;
                case "action_button_submit_form":
                    ajax_action_button_submit_form(data);
                break;
                case "submit_form_data":
                    ajax_submit_form_data(data);
                break;
                }
                
                //CHECK FOR NOTIFICATION
                if(data.notification){
                    check_for_and_display_notifications(data.notification);
                }
            
            }
        });
        }
	}
};

function trigger_ajax_request( $element ){
	if( $element.attr('ajax-request') == 'true' ){
		if( $element.attr('data-action') && $element.attr('data-todo') ){
			
            ajax_data = {action:$element.attr('data-action') , todo:$element.attr('data-todo') };
			
			if( $element.attr('data-record-id') ){
				ajax_data.record_id = $element.attr('data-record-id');
			}
			
			if( $element.attr('data-fk-id') ){
				ajax_data.foreign_key = $element.attr('data-fk-id');
			}
            
			if( $element.attr('data-quantity') ){
				ajax_data.quantity = $element.attr('data-quantity');
			}
			
			if( $element.attr('data-value') ){
				ajax_data.value = $element.attr('data-value');
			}
			
			form_method = 'get';
			if( $element.attr('data-method') ){
				form_method = $element.attr('data-method');
			}
			
			ajax_data_type = 'json';
			if( $element.attr('data-return-type') ){
				ajax_data_type = $element.attr('data-return-type');
			}
			
			ajax_get_url = '';
			ajax_action = 'request_function_output';
			ajax_container = $('#login-form');
			ajax_send();
		}
	}
};
	
function re_process_previous_request( data ){
	if( data.re_process && ! cancel_ajax_recursive_function ){
		set_the_function_click_event( $( data.re_process ) );
	}else{
		//Reload DataTable
		if( data.reload_table && oTable ){
			oTable.fnReloadAjax();
		}
	}
}
	
function ajax_request_function_output(data){
	
	//Close Pop-up Menu
	
	data.reload_table = 1;
	
	if( data.status ){
		//alert(data.status);
        
		if( call_custom_function_before_ajax_processing ){
			custom_function_before_ajax_processing( data );
		}
		
		switch(data.status){
		case "display-appsettings-setup-page":
			//Update Create New School Button Attributes
			if( $('#create_new_appsettings') && data.create_new_appsettings_data ){
				$('#create_new_appsettings')
				.attr('function-class', data.create_new_appsettings_data.function_class )
				.attr('function-name', data.create_new_appsettings_data.function_name )
				.attr('module-id', data.create_new_appsettings_data.module_id )
				
				//Bind Click Event of Button
				$('#create_new_appsettings')
				.bind('click',function(){
					set_the_function_click_event($(this));
				});
			}
			
		break;
		case "display-data-capture-form":
			//Update Create New School Button Attributes
			prepare_new_record_form(data);
			
			//Display Top Accordion
			if( ! $('#collapseTwo').hasClass('in') ){
				$('#collapseTwo')
				.slideDown();
				//.collapse('show');
			}
			
			$('#collapseOne')
			.slideUp();
			//.collapse('hide');
			
			//Display Form Tab
			$('#form-home-control-handle')
			.click();
		break;
		case "display-advance-search-form":
			//Update Create New School Button Attributes
			prepare_new_record_form(data);
			
			//Display Top Accordion
			if( ! $('#collapseTop').hasClass('in') ){
				$('#collapseTop')
				.collapse('show');
			}
			
			$('#collapseBottom')
			.collapse('hide');
			
			//Display Form Tab
			$('#form-home-control-handle')
			.click();
			
			//bind advance search controls
			bind_search_field_select_control();
		break;
		case "modify-appsettings-settings":
			//Update Create New School Button Attributes
			prepare_new_record_form(data);
			
			//Update Application with School Properties
			update_application_with_school_properties( data );
		break;
		case "redirect-to-dashboard":
			//Redirect to dashboard page
			$('#page-body-wrapper')
			.html( data.html );
			
			//Update Application with School Properties
			update_application_with_school_properties( data );
			
			//Get Menus
			generate_modules();
			
			//Activate Tabs
			$('#myTab')
			.find('a')
			.bind('click', function (e) {
			  e.preventDefault();
			  $(this).tab('show');
			  
			   //Link Tab Clicks to Accordion
			   if( ! $('#collapseTop').hasClass('in') ){
					$('#collapseTop')
					.collapse('show');
				}
			});
			
			//bind clear tab contents button
			$('#clear-tab-contents')
			.bind('click', function (e) {
				e.preventDefault();
				
				$('.tab-content')
				.find('div.active.tab-content-to-clear')
				.empty();
					
				$('.tab-content')
				.find('div.active')
				.find('.tab-content-to-clear')
				.empty();
					
			});
		break;
		case "redirect-to-login":
			//Redirect to login page
			prepare_new_record_form(data);
			
			//Update Application with School Properties
			update_application_with_school_properties( data );
		break;
		case "authenticate-user":
			//Refresh Form Token
			//refresh_form_token( data );
		break;
		case "filtered-records":
		case "deleted-records":
			if( oTable ){
				oTable.fnReloadAjax();
			}
            if( data.title && $('#data-tables-accordion-handle') ){
                $('#data-tables-accordion-handle')
                .text( data.title );
            }
		break;
		case "column-toggle":
			ajax_hide_show_column_checkbox( data );
		break;
		case "reload-datatable":
			//Activate DataTables Plugin
			if( data.searched_table ){
				class_name = data.searched_table;
			
				$('#search-query-display-container')
				.html( data.search_query )
				.attr( 'title', $('#search-query-display-container').text() );
			}
			
			if( $('#collapseTop').hasClass('in') ){
				$('#collapseTop')
				.collapse('hide');
			}
			if( ! $('#collapseBottom').hasClass('in') ){
				$('#collapseBottom')
				.collapse('show');
			}
			
			if( oTable ){
				oTable.fnReloadAjax();
			}else{
				recreateDataTables();
			}
		break;
		case "display-datatable":
			if( $('#collapseTop').hasClass('in') ){
				$('#collapseTop')
				.collapse('hide');
			}
			if( ! $('#collapseBottom').hasClass('in') ){
				$('#collapseBottom')
				.collapse('show');
			}
			
			//Display HTML
			var html = data.html;
			
			if( data.inline_edit_form ){
				html += data.inline_edit_form;
			}
			
			$('#data-table-container')
			.html( html );
			
			if( data.search_query )
				$('#search-query-display-container')
				.html( data.search_query )
				.attr( 'title', $('#search-query-display-container').text() );
			
			
			//Activate DataTables Plugin
			recreateDataTables();
			
			set_function_click_event();
			
			//UPDATE HIDDEN / SHOWN COLUMNS
			update_column_view_state();
			
		break;
		case "saved-form-data":
			//Check for saved record id
			if(data.saved_record_id){
				single_selected_record = data.saved_record_id;
			}
		break;
		case "generated-report-card":
		case "got-images-from-url":
		case "pushed-records":
		case "fetched-records":
			//DISPLAY GENERATED REPORT CARD
			$('#report-card-home')
			.prepend( '<div class="processing-info">' + data.html + '</div>' );
			
			//Display Top Accordion
			if( ! $('#collapseTop').hasClass('in') ){
				$('#collapseTop')
				.collapse('show');
			}
			
			if( ! $('#report-card-home').is(':visible') ){
				//Display Reports Tab
				$('#report-card-home-control-handle')
				.click();
			}
			
			//Bind Quick Print Action
			//bind_quick_print_function();
		break;
		case 'grouped-bar-chart':
			//Display Top Accordion
			if( ! $('#collapseTop').hasClass('in') ){
				$('#collapseTop')
				.collapse('show');
			}
			
			$('#collapseBottom')
			.collapse('hide');
			
			if( ! $('#report-card-home').is(':visible') ){
				//Display Reports Tab
				$('#report-card-home-control-handle')
				.click();
			}
			data.reload_table = 0;
			
			$('#report-card-home')
			.html('<button id="export-chart" class="btn btn-small">Export Chart</button><div id="highcharts-container"></div><div id="highcharts-container-2"></div>');
		
			$('#highcharts-container').highcharts({
				chart: {
				},
				title: {
					text: data.dataset.title
				},
				yAxis: {
					min: 0,
					title: {
						text: data.dataset.yaxislabel
					}
				},
				xAxis: {
					categories: data.dataset.data_new_categories
				},
				tooltip: {
					formatter: function() {
						var s;
						if (this.point.name) { // the pie chart
							s = ''+
								this.point.name +': '+ this.y + data.dataset.volumeunits;
						} else {
							s = ''+
								this.x  +': '+ this.y + data.dataset.volumeunits;
						}
						return s;
					}
				},
				labels: {
					items: [{
						html: 'Total',
						style: {
							left: '50px',
							top: '0px',
							color: 'gray'
						}
					}]
				},
				series: data.dataset.new_series
			});
			
			var $currentChart = $('#highcharts-container').highcharts();
			
			$currentChart.exportChart({
				type: 'image/jpeg',
				filename: data.dataset.cache_filename,
				url: 'http://localhost/demo/highcharts.com-master/highcharts/exporting-server/php/php-batik/',
			});
			
			if( data.dataset_ ){
				$('#highcharts-container-2').highcharts({
					chart: {
					},
					title: {
						text: data.dataset_.title
					},
					yAxis: {
						min: 0,
						title: {
							text: data.dataset_.yaxislabel
						}
					},
					xAxis: {
						categories: data.dataset_.data_new_categories
					},
					tooltip: {
						formatter: function() {
							var s;
							if (this.point.name) { // the pie chart
								s = ''+
									this.point.name +': '+ this.y + data.dataset_.volumeunits;
							} else {
								s = ''+
									this.x  +': '+ this.y + data.dataset_.volumeunits;
							}
							return s;
						}
					},
					labels: {
						items: [{
							html: 'Total',
							style: {
								left: '50px',
								top: '0px',
								color: 'gray'
							}
						}]
					},
					series: data.dataset_.new_series
				});
				
				var $currentChart = $('#highcharts-container-2').highcharts();
			
				$currentChart.exportChart({
					type: 'image/jpeg',
					filename: data.dataset_.cache_filename,
					url: 'http://localhost/demo/highcharts.com-master/highcharts/exporting-server/php/php-batik/',
				});
			}
			
			$('button.export-chart')
			.bind('click', function(){
				var $currentChart = $('#highcharts-container').highcharts();
				
				$currentChart.exportChart({
					type: 'image/jpeg',
					filename: 'my-chart',
					url: 'http://localhost/demo/highcharts.com-master/highcharts/exporting-server/php/php-batik/',
				});
			});
		break;
		case "download-report":	
			if( $('#monthly-report-link-con') && $('#monthly-report-link-con').is(':visible') && data.html ){
				$('#monthly-report-link-con').html( data.html );
			}
		break;
		}
	}else{
		data.reload_table = 0;
	}
	
	//Handle / Display Error Messages / Notifications
	display_notification( data );
	
	//Check for re-process command
	re_process_previous_request( data );
	
	if( data.status ){
		if( call_custom_function_after_ajax_processing ){
			custom_function_after_ajax_processing( data );
		}
	}
};

//Display Notification Message
function display_notification( data ){
	if( data && data.tok ){
        //Refresh Token
        refresh_form_token( data );
    }
     
    if( data.typ ){
		var theme = 'alert-error alert-danger';
		
		if( data.theme && data.theme.length > 4 ){
			theme = data.theme;
		}
		
		switch(data.typ){
		case "search_cleared":
		case "report_generated":
		case "searched":
		case "saved":
		case "jsuerror":
		case "uerror":
		case "deleted":
		case "serror":
		case "authenticated":
		case "reset":
			var html = '<div class="alert ' + theme + ' alert-block">';
			  html += '<button type="button" class="close" id="alert-close-button" data-dismiss="alert">&times;</button>';
			  html += '<h4>' + data.err + '</h4>';
			  html += data.msg;
			html += '</div>';
			
			var $notification_container = $('#notification-container');
			
			$notification_container
			.html( html );
			
			$('#alert-close-button')
			.bind('click', function(){
				$('#notification-container')
				.empty();
			});
			
			/*
			$('#form-gen-submit').popover({
				"html": true,
				"content": data.msg,
				"title": data.err,
			})
			.popover('show');
			*/
		break;
		}
	}
};

//Function to Refresh Form Token After Processing
function refresh_form_token( data ){
	//Update school properties if set
    
	if( data.tok && $('form') ){
		$('form')
		.find('input[name="processing"]')
		.val( data.tok );
	}
};

function no_record_selected_prompt(){
	//alert('display prompt that no record was selected');
	var data = {theme:'alert-info', err:'No Selected Record', msg:'Please select a record by clicking on it', typ:'jsuerror' };
	display_notification( data );
};

function no_function_selected_prompt(theme, message_title, message_message, auto_close){
	//alert('display prompt that no record was selected');
	
	/*
	var html = '<div data-role="popup" data-dismissible="false" data-transition="slide" id="errorNotice" data-position-to="#" class="ui-content" data-theme="'+theme+'">';
		html += '<a href="" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right no-print">Close</a>';
		html = html + '<h3>'+message_title+'</h3>';
		html = html + '<p>'+message_message+'</p>';
	html = html + '</div>';
	
	ajax_container
	.append(html)
	.trigger("create");
	
	//Display Notification Popup
	display_popup_notice( auto_close );
	*/
};	


//Bind Multi-select option tooltip
var timer_interval;
var mouse_vertical_position;

var progress_bar_timer_id;
function progress_bar_change(){
	var total = 25;
	var step = 1;
	
	if(function_click_process==0){
		var $progress = $('#virtual-progress-bar').find('.progress-bar');
		
		if($progress.data('step') && $progress.data('step')!='undefined'){
			step = $progress.data('step');
		}
		
		var percentage_step = (step/total)*100;
		++step;
		
		if( percentage_step > 100 ){
			$progress
			.css('width', '100%');
			
			$('#virtual-progress-bar')
			.remove();
			
			//Refresh Page
			function_click_process = 1;
			
			//Stop All Processing
			window.stop();
			
			//Display Timeout Error Message
			var theme = 'a';
			var message_title = 'Server Script Timeout Error';
			var message_message = "The request was taking too long!<br /><br /><h4>Request Parameters</h4>" + ajax_request_data_before_sending_to_server;
			var auto_close = 'no';
			
			no_function_selected_prompt(theme, message_title, message_message, auto_close);
			
		}else{
			$progress
			.data('step',step)
			.css('width', percentage_step+'%');
			
			progress_bar_timer_id = setTimeout(function(){
				progress_bar_change();
			},1000);
		}
	}else{
		$('#virtual-progress-bar')
		.find('.progress-bar')
		.css('width', '100%');
		
		setTimeout(function(){
			$('#virtual-progress-bar')
			.remove();
		},1500);
	}
};
/*
 *  Form Handler jQuery File
 *	written by Patrick Ogbuitepu
 *	Copyright (c) August 2014
 */
 
//Activate Client Side Validation / Tooltips
activate_tooltip_for_form_element( $('form.activate-ajax') );
activate_validation_for_required_form_element( $('form.activate-ajax') );

//Bind Form Submit Event

//$('#form-gen-submit')
$('form.activate-ajax')
.live('submit', function( e ){
	e.preventDefault();
	
	submit_form_data( $(this) );
});

var uploaders = {};
//Activate Ajax file upload
createUploader();

function prepare_new_record_form(data){
	//Prepare and Display New Record Form
	$('#form-content-area')
	//.html('<div id="form-panel-wrapper1">'+data.html+'</div>')
	.html(data.html);
	
	if( data.status ){
		switch(data.status){
		case "display-data-capture-form":
			//Bind Html text-editor
			bind_html_text_editor_control();
			
			//Activate Client Side Validation / Tooltips
			activate_tooltip_for_form_element( $('#form-content-area').find('form') );
			activate_validation_for_required_form_element( $('#form-content-area').find('form') );
			
		break;
		}
	}
	
	//Bind Form Submit Event
	$('#form-content-area')
	.find('form')
	.bind('submit', function( e ){
		e.preventDefault();
		
		submit_form_data( $(this) );
		
	});
	
	//Bind form submission event
	action_button_submit_form();
	select_record_click_function();
	
	//Activate Ajax file upload
	createUploader();
};

function action_button_submit_form(){
		//Bind Action Button Submit Event
		$('form.action-button-form')
		.bind('submit',function(e){
			clicked_action_button = $(this);
			
			ajax_data = $(this).serialize();
			form_method = 'post';
			ajax_data_type = 'json';
			
			switch(clicked_action_button.attr('todo')){
			case "status_update":
			case "record_assign":
			case "edit":
			case "edit-check":
				//Clear All Previously Selected Rows in DataTable
				$('#example').find('tr').removeClass('row_selected');
				
				//Mark DataTable Row as Selected
				$(this).parents('tr').addClass('row_selected');
			break;
			case "delete":
				confirm_action_prompt = 0;
			break;
			}
			
			ajax_action = 'action_button_submit_form';
			ajax_container = '';
			ajax_get_url = $(this).attr('action');
			
			if(confirm_action_prompt){
				ajax_send();
			}else{
				//Display delete prompt dialog box
				display_delete_dialog_box();
			}
			return false;
		});
	};
	
function submit_form_data( $me ){
	
	if( $me.data('do-not-submit') ){
		return false;
	}
	
	if( $me.data('remove-hidden-rows') ){
		$form = $me.clone();
		
		$form
		.find('.hide')
		.remove();
		
		$me = $form;
	}
		
	reload_table_after_form_submit = 1;
	if( $me.data('reload-table') ){
		reload_table_after_form_submit = $me.data('reload-table');
	}
	//console.log('r',reload_table_after_form_submit);
	
	ajax_data = $me.serialize();
	
	form_method = 'post';
    if( $me.attr('method') && $me.attr('method') == 'get' ){
        form_method = 'get';
    }
	ajax_data_type = 'json';	//SAVE CHANGES, SEARCH
	ajax_action = 'request_function_output';
	//ajax_action = 'submit_form_data';
	
	ajax_container = '';
	ajax_get_url = $me.attr('action');
	
	ajax_notice_container = 'window';
	
	ajax_send();
};
	
function bind_html_text_editor_control(){
	$('textarea')
	.bind('keydown', function(e){
	
		switch(e.keyCode){
		case 69:	//E Ctrl [17]
			if(e.ctrlKey){
				e.preventDefault();
				
				editing_textarea = $(this);
				
				//Set Contents
				$('#myModal')
				.modal('show')
				.on('shown', function(){
					tinyMCE.activeEditor.setContent( editing_textarea.val() );
				})
				.on('hidden', function(){
					editing_textarea
					.val( $('#popTextArea').html() );
				});
				
				$(this).attr('tip', '');
				display_tooltip($(this), '');
			}
		break;
		}
		
	})
	.bind('focus', function(){
		$(this).attr('tip', 'Press Ctrl+E to display full text editor');
		
		display_tooltip($(this), '');
	})
	.bind('blur', function(){
		$(this).attr('tip', '');
		
		display_tooltip($(this), '');
	});
};

function display_tooltip(me, name, removetip){
	
	if( removetip ){
		$('#ogbuitepu-tip-con').fadeOut(800);
		return;
	}
	
	//Check if tooltip is set
	if(me.attr('tip') && me.attr('tip').length > 1){
		$('#ogbuitepu-tip-con')
		.find('> div')
		.html(me.attr('tip'));
		
		//Display tooltip
		var offsetY = 8;
		var offsetX = 12;
		
		//var left = me.offset().left - (offsetX + $('#ogbuitepu-tip-con').width() );
		//var top = (me.offset().top + ((me.height() + offsetY)/2)) - ($('#ogbuitepu-tip-con').height()/2);
		
		var left = me.offset().left;
		var top = (me.offset().top + ((me.height() + offsetY)));
		
		if( parseFloat( name ) ){
			top = (name) - ($('#ogbuitepu-tip-con').height()/2);
		}
		
		$('#ogbuitepu-tip-con')
		.find('> div')
		.css({
			padding:me.css('padding'),
		});
		
		$('#ogbuitepu-tip-con')
		.css({
			width:me.width()+'px',
			top:top,
			left:left,
		})
		.fadeIn(800);
	}else{
		//Hide tooltip container
		$('#ogbuitepu-tip-con').fadeOut(800);
	}
	
};

//Bind Search Field Select Control
function bind_search_field_select_control(){
	$('#search-field-select-combo')
	.on('change',function(){
		$('form')
		.find('.default-hidden-row')
		.hide();
		
		$('form')
		.find('.'+$(this).val())
		.show();
	});
};
function getImageUploadFormID( d ){
    if( d && $('#'+d) ){
        return $('#'+d).parents('form').find('input[name="processing"]').val();
    }
};

//File Uploader
function createUploader(){
	
	if($('.upload-box').hasClass('cell-element')){
		
		$('.upload-box').each(function(){
			var id = $(this).attr('id');
			var name = $(this).find('input').attr('name');
			var table = $(this).parents('form').find('input[name="table"]').val();
			
			var acceptable_files_format = $(this).find('input').attr('acceptable-files-format');
			var image_urls = $(this).find('input').attr('data-value');
			
			var uploader = new qq.FileUploader({
				element: document.getElementById(id),
				listElement: document.getElementById('separate-list'),
				action: pagepointer+'php/upload.php',
				params: {
					tableID: table,
					formID: getImageUploadFormID( id ),
					name:name,
					acceptable_files_format:acceptable_files_format,
				},
                onSubmit: function(id, fileName){
                    $.each( uploaders, function( k , v ){
                        v._options.params.formID = getImageUploadFormID( k );
                    });
                },
				onComplete: function(id, fileName, responseJSON){
					if(responseJSON.success=='true'){
						$('.qq-upload-success')
						.find('.qq-upload-failed-text')
						.text('Success')
						.css('color','#ff6600');
						
						$('img#'+responseJSON.element+'-img')
						.attr( 'src' , responseJSON.dir + responseJSON.filename +'.'+ responseJSON.ext )
						.fadeIn('slow');
					}else{
						//alert('failed');
					}
				}
			});
            
            //Modify to include multiple images
            var img_url = image_urls.split(':::');
            for( var i = 0; i < img_url.length; i++ ){
                $('img#'+name+'-img')
                .attr( 'src' , pagepointer+img_url[i] )
                .fadeIn('slow');
            }
            uploaders[id] = uploader;
		});
	}
};

//initiate_tiny_mce_for_popup_textarea();
function initiate_tiny_mce_for_popup_textarea(){
	if( $('textarea#popTextArea') ){
		$('textarea#popTextArea').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tinymce.min.js',
			
			// General options
			theme: "modern",
			height : 200,
			width : 450,
			plugins: [
					"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
			],

			toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink image | inserttime preview | forecolor backcolor",
			toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | spellchecker | pagebreak restoredraft",

			menubar: false,
			toolbar_items_size: 'small',

			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],

			templates: [
					{title: 'Test template 1', content: 'Test 1'},
					{title: 'Test template 2', content: 'Test 2'}
			]

		});
	}
};

function initiate_tiny_mce( $textarea ){
	if( $textarea ){
		$textarea.tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tinymce.min.js',
			
			// General options
			theme: "modern",
			height : 200,
			width : 450,
			plugins: [
					"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
			],

			toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink image | inserttime preview | forecolor backcolor",
			toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | spellchecker | pagebreak restoredraft",

			menubar: false,
			toolbar_items_size: 'small',

			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],

			templates: [
					{title: 'Test template 1', content: 'Test 1'},
					{title: 'Test template 2', content: 'Test 2'}
			]

		});
	}
};

$('<div id="ogbuitepu-tip-con"><div></div></div>').prependTo('body');
$('#ogbuitepu-tip-con')
.css({
	position:'absolute',
	display:'none',
	top:0,
	left:0,
	backgroundColor:'transparent',
	backgroundImage:'url('+pagepointer+'images/tip-arrow-r.png)',
	backgroundPosition:'top center',
	backgroundRepeat:'no-repeat',
	opacity:0.95,
	paddingTop:'11px',
	/*width:'220px',*/
	height:'auto',
	color:'#fff',
	zIndex:90000,
});
$('#ogbuitepu-tip-con')
.find('> div')
.css({
	position:'relative',
	background:'#ee1f19',
	opacity:0.95,
	/*padding:'5%',*/
	width:'100%',
	height:'95%',
	color:'#fff',
	textShadow:'none',
	borderRadius:'8px',
	boxShadow:'1px 1px 3px #222',
	fontSize:'0.85em',
	fontFamily:'arial',
});

function activate_tooltip_for_form_element( $form ){
	$form
	.find('.form-gen-element')
	.bind('focus',function(){
		display_tooltip($(this) , $(this).attr('name'), false);
	});
	
	$form
	.find('.form-gen-element')
	.bind('blur',function(){
		display_tooltip( $(this) , '', true );
	});
};

function activate_validation_for_required_form_element( $form ){
	$form
	.find('.form-element-required-field')
	.bind('blur',function(){
		validate( $(this) );
	});
	
	$form
	.not('.skip-validation')
	.bind('submit', function(){
		
		$form = $(this);
		
		if( $(this).data('remove-hidden-rows') ){
			$form = $(this).clone();
			
			$form
			.find('.hide')
			.remove();
		}
		
		$form
		.find('.form-element-required-field')
		.blur();
		
		if( $form.find('.form-element-required-field').hasClass('invalid-data') ){
			$form
			.find('.invalid-element:first')
			.focus();
			
			$form.data('do-not-submit', true);
			
			//display notification to fill all required fields
			var data = {
				typ:'jsuerror',
				err:'Invalid Form Field',
				msg:'Please do ensure to correctly fill all required fields with appropriate values',
			};
			display_notification( data );
			
			return false;
		}
		
		$form.data('do-not-submit', false);
		
	});
	
};

function validate( me ){
	//var e = $('#required'+name);
	//alert(e.attr('alt'));
	
	if( testdata( me ) ){
		if(me.hasClass('invalid-data')){
			me.removeClass('invalid-data').addClass('valid-data');
		}else{
			me.addClass('valid-data');
		}
		me
		.next('span.input-status')
		.addClass('valid-data')
		.removeClass('invalid-data');
		
	}else{
		if(me.hasClass('valid-data')){
			me.addClass('invalid-data').removeClass('valid-data');
		}else{
			me.addClass('invalid-data');
		}
		
		me
		.next('span.input-status')
		.removeClass('valid-data')
		.addClass('invalid-data');
	}
	
};

function testdata( $element ){
	var data = $element.val();
	var id = $element.attr('alt');
	
	switch (id){
	case 'text':
	case 'textarea':
	case 'upload':
		if(data.length > 0)return 1;
		else return 0;
	break;
	case 'category':
		if(data.length > 4)return 1;
		else return 0;
	break;
	case 'number':
	case 'currency':
		data = data.replace(',','');
		return (data - 0) == data && data.length > 0;
	break;
	case 'email':
		return vemail(data);
	break;
	case 'password':
		if( $element.hasClass('old-password') ){
			return 1;
		}else{
			return vpassword(data);
		}
	break;
	case 'phone':
	case 'tel':
		return vphone(data);
		break;
	case 'select':
	case 'multi-select':
		return data;
		break;
	case 'date':
		if( $element.attr('current-date') ){
			var user_date = data.split('-');
			var date = $element.attr('current-date').split('-');
			
			if( $element.attr('min-year') ){
				var year = date[0] - parseFloat( $element.attr('min-year') );
				if( user_date[0] > year ){
					return 0;
				}
			}
			
			if( $element.attr('max-year') ){
				var year = date[0] - parseFloat( $element.attr('max-year') );
				if( user_date[0] < year ){
					return 0;
				}
			}
		}
		
		return data;
	break;
	default:
		return 0;
	}
};

function vphone(phone) {
	var phoneReg = /[a-zA-Z]/;
	if( phone.length<5 || phoneReg.test( phone ) ) {
		return 0;
	} else {
		return 1;
	}
};

function vemail(email) {
	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( email.length<1 || !emailReg.test( email ) ) {
		return 0;
	} else {
		return 1;
	}
};

var pass = 0;
function vpassword(data){
	if( $('input[type="password"]').not('.old-password').filter(':first').val()!=pass ){
		pass = 0;
	}
	
	if(!pass){
		//VERIFY PASSWORD
		if( data.length > 6 ){
			/*
			//TEST FOR AT LEAST ONE NUMBER
			var passReg = /[0-9]/;
			if( passReg.test( data ) ) {
				//TEST FOR AT LEAST ONE UPPERCASE ALPHABET
				passReg = /[A-Z]/;
				if( passReg.test( data ) ){
					//TEST FOR AT LEAST ONE LOWERCASE ALPHABET
					passReg = /[a-z]/;
					if( passReg.test( data ) ){
						//STORE FIRST PASSWORD
						pass = data;
						return 1;
					}else{
						//NO LOWERCASE ALPHABET IN PASSWORD
						pass = 0;
						return 0;
					}
				}else{
					//NO UPPERCASE ALPHABET IN PASSWORD
					pass = 0;
					return 0;
				}
			}else{
				//NO NUMBER IN PASSWORD
				pass = 0;
				return 0;
			}
			*/
			pass = data;
			return 1;
		}else{ 
			pass = 0;
			return 0;
		}
		/*
		a.	User ID and password cannot match
		b.	Minimum of 1 upper case alphabetic character required
		c.	Minimum of 1 lower case alphabetic character required
		d.	Minimum of 1 numeric character required
		e.	Minimum of 8 characters will constitute the password
		*/
	}else{
		//CONFIRM SECOND PASSWORD
		if(data==pass)return 1;
		else return 0;
	}
};

function formatNum(n) {
  var n = ('' + n).split('.');
  var num = n[0];
  var dec = n[1];
  var r, s, t;

  if (num.length > 3) {
	s = num.length % 3;

	if (s) {
	  t = num.substring(0,s);
	  num = t + num.substring(s).replace(/(\d{3})/g, ",$1");
	} else {
	  num = num.substring(s).replace(/(\d{3})/g, ",$1").substring(1);
	}
  }

  if (dec && dec.length > 3) {
	dec = dec.replace(/(\d{3})/g, "$1 ");
  }

  return num + (dec? '.' + dec : '');
};
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
	
};App.init();    
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
};}); })(jQuery);