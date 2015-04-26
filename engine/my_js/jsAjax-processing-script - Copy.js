/*
 * JavaScript Dashboard Class
 * Created On: 15-JULY-2013
 * Created By: Ogbuitepu O. Patrick
 *
 *
*/
$( document ).ready( function() {
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
	
	var pagepointer = './';
	
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
	
	//Last Position of Mouse on mouseup event
	var last_position_of_mouse_on_mouse_up_x = 0;
	var last_position_of_mouse_on_mouse_up_y = 0;
	
	//Variable to determine if entity is being renamed
	var renaming_entity_in_progress = 0;
	
	//Currently Opened Label
	var currently_opened_label_id_in_report_letters_memo = '';
	
	var editted_entity_source = '';
	
	//Determine if Menus have been bound to actions after initialization
	var bound_menu_items_to_actions = 0;
	
	var test_view_entity = 0;
	
	//Variable that determines the number of times notifications have to be closed prior to status update
	var update_notifications_to_read = 0;
	
	//Variable that determines whether to archive dataset
	var archive_dataset = 0;
	
	//Variable that determines the currently edited textarea
	var editing_textarea;
	
	//Variable that determines the currently view port opened in reports , letters & memo
	var report_letters_memo_current_view = '';
	
	//Request Modules
	function generate_modules(){
		ajax_data = {action:'modules', todo:'display'};
		form_method = 'get';
		ajax_data_type = 'json';
		ajax_action = 'generate_modules';
		ajax_container = $('#login-form');
		ajax_send();
		
	};
	
	get_properties_of_school();
	function get_properties_of_school(){
		ajax_data = {action:'appsettings', todo:'get_appsettings'};
		form_method = 'get';
		ajax_data_type = 'json';
		ajax_action = 'request_function_output';
		ajax_container = $('#login-form');
		ajax_send();
		
	};
	
	//Rehighlight Selected Record
	function rehighlight_selected_record_function(){
		
		var $selected_record = $('#'+single_selected_record).parents('tr');
		
		if($selected_record){
			
			$selected_record.removeClass('row_selected');
			
			//Select Record Click Event
			$clicked_element_parent = $('#example');
			$clicked_element_group_selector = 'tr';
			
			var shiftctrlKey = false;
			select_record_click_event($clicked_element_parent, $selected_record , $clicked_element_group_selector, shiftctrlKey);
			
		}
	};
	
	//Form Submission
	function select_record_click_function(){
		$('#example').find('tr').off('click');
		
		//Bind Row Click Event
		$('#example')
		.find('tr')
		.on('click',function(e){
			//Select Record Click Event
			$clicked_element_parent = $('#example');
			$clicked_element_group_selector = 'tr';
			
			var shiftctrlKey = false;
			if( e.ctrlKey || e.shiftKey )
				shiftctrlKey = true;
				
			select_record_click_event($clicked_element_parent, $(this) , $clicked_element_group_selector, shiftctrlKey);
		});
		
		$('#example_wrapper')
		.find('.dataTables_scrollBody')
		.bind('scroll' , function(){
			var scrollpos = $(this).scrollLeft();
			
			var scrollwidth_lower = $(this).width();
			var scrollwidth_upper = $('#example_wrapper').find('.dataTables_scrollHeadInner').find('table').width();
			
			var scrollwidth = scrollwidth_upper - scrollwidth_lower;
			
			if( scrollpos > scrollwidth ){
				$(this)
				.scrollLeft( scrollwidth );
			}
		});
		
	};
	
	function select_record_click_event($clicked_element_parent, $clicked_element, $clicked_element_group_selector, shiftctrlKey ){
		/*
		if( multiple_selected_record_id ){
			$('#example')
			.find('tr')
			.removeClass('row_selected');
			multiple_selected_record_id = '';
		}
		*/
		
		if($clicked_element.hasClass('row_selected')){
			//Mark DataTable Row as Selected
			$clicked_element.removeClass('row_selected');
			
			//Store ID of Selected Row
			single_selected_record = $clicked_element.find('.datatables-record-id').attr('id');
			//console.log('before', single_selected_record+'	-	'+multiple_selected_record_id);
			
			multiple_selected_record_id = multiple_selected_record_id.replace( single_selected_record+':::' , '' );
			single_selected_record = '';
			
			//console.log('after', single_selected_record+'	-	'+multiple_selected_record_id);
		}else{
			if( shiftctrlKey ){
				if( single_selected_record && multiple_selected_record_id == '' )
					multiple_selected_record_id = single_selected_record;
					
				multiple_selected_record_id += ':::'+$clicked_element.find('.datatables-record-id').attr('id');
			}else{
				//Clear All Previously Selected Rows in DataTable
				$clicked_element_parent.find($clicked_element_group_selector).removeClass('row_selected');
				
				multiple_selected_record_id = '';
			}
			//Mark DataTable Row as Selected
			$clicked_element.addClass('row_selected');
			
			//Store ID of Selected Row
			single_selected_record = $clicked_element.find('.datatables-record-id').attr('id');
			
			//multiple_selected_record_id
		}
		
		//CHECK WHETHER OR NOT TO DISPLAY DETAILS
		//console.log('single',single_selected_record);
		//console.log('multiple', multiple_selected_record_id);
		
		if( $('#record-details-home').is(':visible') ){
			if( single_selected_record && ( multiple_selected_record_id == '' || multiple_selected_record_id == single_selected_record )  ){
				//Replace Container Content with entire record details
				$('#record-details-home')
				.html( $('#main-details-table-'+single_selected_record).html() );
			}
			
			if( multiple_selected_record_id ){
				var array_of_selected_records = multiple_selected_record_id.split(':::');
				
				var count = array_of_selected_records.length;
				
				details_of_multiple_selected_records = '';
				
				for( var i = 0; i < count; i++ ){
					//Push All Details to display container
					details_of_multiple_selected_records += $('#main-details-table-' + array_of_selected_records[i]).html();
				}
				
				if( $('#record-details-home').is(':visible') && details_of_multiple_selected_records ){
					$('#record-details-home')
					.html( details_of_multiple_selected_records );
				}
			}
		}
		//return false;
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
			ajax_container = $('#login-form');
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
	
	function ajax_send(){
		if(function_click_process){
		//Send Data to Server
		$.ajax({
			dataType:ajax_data_type,
			type:form_method,
			data:ajax_data,
			url: pagepointer+'php/ajax_request_processing_script.php'+ajax_get_url,
			timeout:30000,
			beforeSend:function(){
				//Display Loading Gif
				function_click_process = 0;
				
				cancel_ajax_recursive_function = false;
				
				confirm_action_prompt = 0;
				
				/*ajax_container.html('<div id="loading-gif" class="no-print">Please Wait</div>');*/
				
				$('#generate-report-progress-bar')
				.html('<div id="virtual-progress-bar" class="progress progress-warning progress-striped"><div class="progress-bar bar"></div></div>');
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
	};
	
	function ajax_generate_modules(data){
		if(data.fullname.length > 1){
			//PREPARE DATA FOR DISPLAY TO BROWSER
			var html = '<ul id="module-menu" class="nav nav-list">';
			$.each(data.modules, function(key, value){
				//Exclude user details
				if(key!='user_details'){
					$.each(value, function(ki, vi){
						html = html + '<li class="nav-header">'+ki+'</li>';
						$.each(vi, function(k, v){
							html = html + '<li  data-mini="true"><a href="" id="'+v.id+'" function-id="'+v.id+'" function-class="'+v.phpclass+'" function-name="'+v.todo+'" module-id="'+key+'" module-name="'+ki+'" title="'+v.name+'">'+v.name+'</a></li>';
						});
					});
				}
			});
			html = html + '</ul>';
			
			$('#alternate-menu')
			.html(html);
			//Bind Validation Commands
			
			//Bind Actions
			set_function_click_event();
			
			//Obtain Functiions of Selected Module
			//generate_functions();
			
			//Update Name of Currently Logged In User
			$('#current-user-name-module')
			.add('#user-info-user-name')
			.text(data.fullname);
			
			var user_info = '';
			
			user_info += '<div class="row-fluid"><div class="span6">Role</div><div class="span6"><b>'+data.role+'</b></div></div>';
			user_info += '<div class="row-fluid"><div class="span6">Login Time</div><div class="span6"><b>'+data.login_time+'</b></div></div>';
			user_info += '<div class="row-fluid"><div class="span6">Zidoff Merchant ID</div><div class="span6"><b>'+data.remote_user_id+'</b></div></div>';
			
			$('#app-user-information').html( user_info );
			
			$('#appsettings-name').text( data.project_title );
			
			$('title').text( data.project_title );
			
		}else{
			//Redirect to login page
			$.mobile.navigate('#authentication-page');
		}
	};
	
	function set_function_click_event(){
		if(!bound_menu_items_to_actions){
			//Ensure that Menus are bound only once
			$('#module-menu')
			.find('a')
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event($(this));
				
			});
			
			bound_menu_items_to_actions = 1;
		}
		
		
		$('a#add-new-record')
		.add('a#back-to-products-button')
		.add('a#get-images-from-url')
		.add('a#push-my-records')
		.add('a#push-all-records')
		.add('a#fetch-all-records')
		.add('a#fetch-my-records')
		.add('a#import-excel-table')
		.add('a#navigation-pane')
		.add('a#advance-search')
		.add('a#clear-search')
		.add('a#delete-forever')
		.bind('click',function( e ){
			e.preventDefault();
			set_the_function_click_event($(this));
		});
		
		//Bind Edit Buttons Event and entity right click menu buttons events
		$('a#edit-selected-record')
		.add('#add-product-variations-button')
		.add('#manage-product-variations-button')
		.bind('click',function( e ){
			e.preventDefault();
			
			if( single_selected_record ){
				clicked_action_button = $(this);
			
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record};
				form_method = 'post';
				
				ajax_data_type = 'json';
				
				ajax_action = 'request_function_output';
				ajax_container = $('#login-form');
				ajax_get_url = $(this).attr('action');
				
				switch( $(this).attr('id') ){
				case "manage-product-variations-button":
					class_action = $(this).attr('function-name');
					class_name = $(this).attr('function-class');
				break;
				}
				
				ajax_send();
				
			}else{
				no_record_selected_prompt();
			}
		});
		
		//Bind Generate Report Buttons Event
		$('a#generate-report')
		.add('a#generate-report-first-term')
		.bind('click',function(e){
			 
			e.preventDefault();
			
			if(single_selected_record || multiple_selected_record_id){
				clicked_action_button = $(this);
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id};
				form_method = 'post';
				
				ajax_data_type = 'json';
				
				ajax_action = 'request_function_output';
				ajax_container = $('#login-form');
				ajax_get_url = $(this).attr('action');
			
				ajax_send();
			}else{
				no_record_selected_prompt();
			}
		});
		
		//Bind Delete Buttons Event
		$('a#delete-selected-record')
		.bind('click',function(e){
			e.preventDefault();
			
			if(single_selected_record || multiple_selected_record_id){
				
				clicked_action_button = $(this);
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id};
				form_method = 'post';
				ajax_data_type = 'json';
				
				//ajax_action = 'action_button_submit_form';
				ajax_action = 'request_function_output';
				ajax_container = $('#login-form');
				ajax_get_url = $(this).attr('action');
				
				confirm_action_prompt = 1;
				
				$(this).popover('show');
			}else{
				no_record_selected_prompt();
			}
		});
		
		
	};
	
	function set_the_function_click_event($me){
		//Get HTML ID
		clicked_menu = $me.attr('id');
		
		//Last Clicked function from main menu
		switch($me.attr('id')){
		case 'add-new-record':
		case 'generate-report':
		case 'import_excel_table':
		case 'restore-all':
		case 'advance-search':
		case 'navigation-pane':
		case 'clear-search':
		case 'add-new-memo-report-letter':
		break;
		default:
			clicked_main_menu = $me.attr('id');
		break;
		}
		
		//Get function id
		var function_id = $me.attr('function-id');
		var function_name = $me.attr('function-name');
		var function_class = $me.attr('function-class');
		
		column_toggle_table = '';
		column_toggle_num = '';
		column_toggle_name = '';
		
		search_table = '';
		if($me.attr('search-table'))
			search_table = $me.attr('search-table');
		
		if($me.attr('column-toggle-table')){
			column_toggle_table = $me.attr('column-toggle-table');
			column_toggle_name = $me.attr('name');
			column_toggle_num = $me.attr('column-num');
		}	
			
		var module_id = $me.attr('module-id');
		
		
		if(function_id && function_id!='do-nothing'){
			//Request Function Output
			request_function_output(function_name, function_class, module_id, function_id);
			
			//Update name of the active function
			if($me.attr('module-name').length > 3 && $me.text()){
				$('#active-function-name')
				.attr('function-class', function_class)
				.attr('function-id', function_id)
				.html($me.attr('module-name') + ' &rarr; ' + $me.text());
				
				$('#secondary-display-title').html( '<i class="icon-info-sign"></i> ' + $me.attr('module-name') + ' &rarr; ' + $me.text() );
				
				$('title').html($me.attr('module-name') + ' &rarr; ' + $me.text());
			}
		}
	};
	
	function request_function_output(function_name, function_class, module_id, function_id){
		//IF ADVANCE SEARCH - THEN PASS VALUE OF CURRENT TABLE
		
		switch(function_class){
		case "myexcel":
		case "search":
			ajax_data = {action:function_class, todo:function_name, module:module_id, search_table:search_table};
		break;
		case "column_toggle":
			ajax_data = {action:function_class, todo:function_name, module:module_id, column_toggle_table:column_toggle_table, column_toggle_name:column_toggle_name, column_toggle_num:column_toggle_num};
		break;
		default:
			if(function_id){
				ajax_data = {action:function_class, todo:function_name, module:module_id, id:function_id };
			}else{
				ajax_data = {action:function_class, todo:function_name, module:module_id};
			}
			
			if( function_name == 'create_new_record' && single_selected_record ){
				ajax_data = {action:function_class, todo:function_name, module:module_id, id:single_selected_record };
			}
		break;
		}
		
		class_action = function_name;
		class_name = function_class;
		module_id = module_id;
		
		form_method = 'get';
		ajax_data_type = 'json';
		
		//if(function_name=='rename_entity' && before==1)ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='new')ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='select_audit_trail')ajax_data_type = 'text'; //NEW RECORD
		//if(function_name=='search')ajax_data_type = 'text'; //SEARCH RECORD
		//if(function_name=='clear_search')ajax_data_type = 'text'; //SEARCH RECORD
		//if(function_name=="column_toggle")ajax_data_type = 'text'; //TOGGLE COLUMN RECORD
		//if(function_name=="delete_forever")ajax_data_type = 'text'; //TOGGLE COLUMN RECORD
		
		ajax_action = 'request_function_output';
		ajax_container = $('#login-form');
		ajax_get_url = '';
		ajax_send();
	};
	
	function re_process_previous_request( data ){
		if( data.re_process && ! cancel_ajax_recursive_function ){
			set_the_function_click_event( $( data.re_process ) );
		}else{
			//Reload DataTable
			if( oTable ){
				oTable.fnReloadAjax();
			}
		}
	}
	
	function ajax_request_function_output(data){
		//alert(data);
		//Close Pop-up Menu
		if( data.status ){
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
				$('#collapseTop')
				.collapse('show');
				
				//Display Form Tab
				$('#form-home-control-handle')
				.click();
			break;
			case "display-advance-search-form":
				//Update Create New School Button Attributes
				prepare_new_record_form(data);
				
				//Display Top Accordion
				$('#collapseTop')
				.collapse('show');
				
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
				$('#myTab a')
				.bind('click', function (e) {
				  e.preventDefault();
				  $(this).tab('show');
				  
				  //Link Tab Clicks to Accordion
					$('#collapseTop')
					.collapse('show');
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
			case "deleted-records":
				if( oTable ){
					oTable.fnReloadAjax();
				}
			break;
			case "reload-datatable":
				//Activate DataTables Plugin
				if( data.searched_table )
					class_name = data.searched_table;
				
				$('#search-query-display-container')
				.html( data.search_query )
				.attr( 'title', $('#search-query-display-container').text() );
				/*
				if( oTable ){
					oTable.fnReloadAjax();
				}else{
				*/
					//recreateDataTables();
					oTable.fnReloadAjax();
				//}
			break;
			case "display-datatable":
				//Display HTML
				$('#data-table-container')
				.html( data.html );
				
				if( data.search_query )
					$('#search-query-display-container')
					.html( data.search_query )
					.attr( 'title', $('#search-query-display-container').text() );
				
				
				//Activate DataTables Plugin
				recreateDataTables();
				
				set_function_click_event();
				
				//Collapse Top Accordion
				$('#collapseTop')
				.collapse('hide');
				
				//UPDATE HIDDEN / SHOWN COLUMNS
				update_column_view_state();
			break;
			case "saved-form-data":
				//Refresh Token
				//refresh_form_token( data );
				
				//Check for saved record id
				if(data.saved_record_id){
					single_selected_record = data.saved_record_id;
				}
				
				//Reload DataTable
				oTable.fnReloadAjax();
				//recreateDataTables();
				
				//Bind form submission event
				select_record_click_function();
				
				//Bind details open and close event to table
				bind_details_button_click();
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
			}
		}
		
		//Handle / Display Error Messages / Notifications
		display_notification( data );
		
		//Check for re-process command
		re_process_previous_request( data );
		
		switch(class_action){
		case "select_audit_trail":
			prepare_new_record_form(data);
		break;
		case "navigation_pane":
			prepare_new_record_form(data);
			
			$('ul.navigation-quick-views')
			.find('a')
			.bind('click',function(){
				set_the_function_click_event($(this));
			});
			
			//Bind Navigation Events for Side Pane
			$('.document-child-link')
			.bind('click', function(){
				//Set Current Selected Record
				var new_data ={currently_opened_entity:$(this).attr('id'), html:0};
				refresh_entity_and_display_notification(new_data);
				
				//Trigger View Only for File
			});
		break;
		case "generate_report":
			//alert(data); //NEW RECORD
			prepare_new_record_form(data);
			
			//oTable.fnDraw();
			oTable.fnReloadAjax();
			
			//Bind details open and close event to table
			bind_details_button_click();
			
			//Bind Uploader Function
			//createUploader();
		break;
		case "new_public_scanned_file":
		case "new_scanned_file":
		case "new_memo_report_letter":
			//Prepare New memo, report, letter form
			prepare_new_record_form(data);
			
			//Bind Uploader Function
			createUploader();
		break;
		case "column_toggle":
			//Toggle Column
			ajax_hide_show_column_checkbox(data);
		break;	
		case "view":
		
		case "verify_view":
		case "daily_gas_price_view":
		/* Global Gas Market */
			//Destroy Existing Column Selection Popup
			$('#popupHideShowColumns-popup')
			.remove();
				
			//Display received output
			$('#content-area')
			.html(data.html)
			.trigger('create');
			
			//Bind Show / Hide Columns
			bind_show_hide_column_checkbox();
			
			//Bind Actions
			set_function_click_event();
			
			//Optimize and Activate DataTables
			recreateDataTables();
			/******************************************************/
			
			//BIND TIMELINE EVENTS FOR DAILY GAS PRICES
			update_timeline();
			
			//Bind Click Events of Unit Selector
			bind_unit_selector_click_event();
			
			//UPDATE HIDDEN / SHOWN COLUMNS
			update_column_view_state();
			
		break;
		case "map":
			//Display received output
			$('#content-area')
			.html(data.html)
			.trigger('create');
			
			$.each($('.global-gas-market-marker'), function(){
				$(this).css({
					position:'absolute',
					top:($(this).attr('jtop')*1)+ $('#global-gas-market-map').position().top - ($(this).height()/2)+'px',
					left:($(this).attr('jleft')*1)+$('#global-gas-market-map').position().left - ($(this).width()/2)+'px',
				});
			});
			
			//BIND TIMELINE EVENTS FOR DAILY GAS PRICES
			update_timeline();
		break;
		case "historical_trend":
			//Display Highstock with received data
			$('#content-area')
			.html('<div id="highcharts-container"></div>');
			
			var seriesOptions = [],
			yAxisOptions = [],
			seriesCounter = 0,
			names = data.dataset,
			colors = Highcharts.getOptions().colors;

			
			$.each(names, function(i, data) {
					seriesOptions[i] = {
						name: data.name,
						data: data.data
					};

					// As we're loading the data asynchronously, we don't know what order it will arrive. So
					// we keep a counter and create the chart when all the data is loaded.
					seriesCounter++;

					if (seriesCounter == names.length) {
						createChart();
					}
			});



			// create the chart when all data is loaded
			function createChart() {

				$('#highcharts-container').highcharts('StockChart', {
					chart: {
					},
					
					rangeSelector: {
						selected: 4
					},

					yAxis: {
						labels: {
							formatter: function() {
								return (this.value > 0 ? '' : '') + this.value + ' USD / MMBtu';
								//return (this.value > 0 ? '+' : '') + this.value + '%';
							}
						},
						plotLines: [{
							value: 0,
							width: 2,
							color: 'silver'
						}]
					},
					
					legend: {
						enabled: true
					},
					
					plotOptions: {
						series: {
							//compare: 'percent',
							marker: {
								enabled: false,
								states: {
									hover: {
										enabled: true,
										radius: 3
									}
								}
							}
						}
					},
					
					tooltip: {
						//pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
						pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
						valueDecimals: 2
					},
					
					series: seriesOptions
				});
			};
		break;
		case "gas_sold_cost_bar_chart":
		case "gas_sold_bar_chart":
		case "gas_purchase_cost_bar_chart":
		case "gas_purchase_bar_chart":
			
			//Display Highstock with received data
			$('#content-area')
			.html('<div id="highcharts-container"></div>');
			
			$('#highcharts-container').highcharts({
				chart: {
					type: 'column',
					margin: [ 50, 50, 100, 80]
				},
				title: {
					text: data.dataset.title
				},
				xAxis: {
					categories: data.dataset.datalabels,
					labels: {
						align: 'center',
						style: {
							fontSize: '11px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: data.dataset.yaxislabel
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					pointFormat: '<b>{point.y:.4f} '+data.dataset.volumeunits+'</b>',
				},
				series: [{
					name: data.dataset.yaxisname,
					data: data.dataset.datapoints,
					dataLabels: {
						enabled: true,
						rotation: -90,
						color: '#FFFFFF',
						align: 'right',
						x: 4,
						y: 10,
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif',
							textShadow: '0 0 3px black'
						}
					}
				}]
			});
			
			//Prepend Units Selector to Bar Chart Container
			$('#highcharts-container')
			.prepend(data.html + data.dataset.unitsselector)
			.trigger('create');
			
			//BIND TIMELINE EVENTS FOR DAILY GAS PRICES
			update_timeline();
			
			//Bind Click Events of Unit Selector
			bind_unit_selector_click_event();
		break;
		};
	};
	
	//Function to Update Application with School Properties
	function update_application_with_school_properties( data ){
		//Update school properties if set
		if( data.appsettings_properties ){
			$('#appsettings-name')
			.text( data.appsettings_properties.appsettings_name );
		}
	};
	
	//Function to Refresh Form Token After Processing
	function refresh_form_token( data ){
		//Update school properties if set
		if( data.tok && $('form') ){
			$('form')
			.find('#processing')
			.val( data.tok );
		}
	};
	
	/******************************************************/
	/****************DISPLAY DETAILS OF ROW****************/
	/******************************************************/
	function fnFormatDetails ( oTable, nTr, details, img, duration ){
		var aData = oTable.fnGetData( nTr );
		sOut = '<div class="grid-inner-content">'+details+'</div>';
		
		return sOut;
	};
	/******************************************************/
	
	function bind_details_button_click(){
		/******************************************************/
		/********LISTENER FOR OPENING & CLOSING DETAILS********/
		/******************************************************/
		
		$('.datatables-details').off('click');
		
		$('.datatables-details').on('click', function () {
			
			if($(this).data('details')!='true'){
				$(this)
				.data('details','true');
				
				var nTr = $(this).parents('tr')[0];
				oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr, $(this).next('div').html(), $(this).next('div').next('div').html(),$(this).next('div').next('div').next('div').html()), 'details' );
				
				if($(this).hasClass('pull-location-differential')){
					//Remove location differential class so request is made once
					$(this).removeClass('show-location-differential');
					
					//Make data request to the server
					class_name = 'global_g_m';
					ajax_data = {action:class_name, todo:'location_differential', module:module_id, record_id:$(this).attr('jid')};
					
					form_method = 'get';
					ajax_data_type = 'json';
					ajax_action = 'evaluate_location_differential';
					ajax_container = $(this).parents('tr').next().find('.location-differential');
					
					ajax_send();	
				}
			}else{
				$(this)
				.data('details','false');
				
				var nTr = $(this).parents('tr')[0];
				oTable.fnClose( nTr );
			}
		} );
	};
	
	//Display Notification Message
	function display_notification( data ){
		if( data.typ ){
			var theme = 'alert-error';
			
			if( data.theme ){
				theme = data.theme;
			}
			
			switch(data.typ){
			case "search_cleared":
			case "searched":
			case "saved":
			case "jsuerror":
			case "uerror":
			case "serror":
				//Refresh Token
				refresh_form_token( data );
				
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
		.bind('submit', function(){
			
			if( $(this).data('do-not-submit') ){
				return false;
			}
			
			ajax_data = $(this).serialize();
		
			form_method = 'post';
			ajax_data_type = 'json';	//SAVE CHANGES, SEARCH
			ajax_action = 'request_function_output';
			//ajax_action = 'submit_form_data';
			
			ajax_container = $('#login-form');
			ajax_get_url = $(this).attr('action');
			
			ajax_notice_container = 'window';
			
			ajax_send();
			return false;
		});
		
		//Bind form submission event
		action_button_submit_form();
		select_record_click_function();
		
		//Activate Ajax file upload
		createUploader();
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
	
	//Bind Show/Hide Column Checkboxes to Show/Hide Column Menu Button
	bind_show_hide_column_checkbox();
	function bind_show_hide_column_checkbox(){
		
		$('ul.show-hide-column-con')
		.find('input[type="checkbox"]')
		.live('click',function(){
			//get current column
			var col = $(this).parents('li').index();
			
			$(this).attr('column-num',col);
			
			set_the_function_click_event($(this));
		});
		
		//bind delete popover buttons
		$('input#delete-button-yes')
		.live('click',function(e){
			$('a#delete-selected-record').popover('hide');
			
			if( confirm_action_prompt ){
				ajax_send();
				
				//Reset confirmation value once pop-up closes
				confirm_action_prompt = 0;
			}
		});
		
		$('input#delete-button-no')
		.live('click',function(e){
			$('a#delete-selected-record').popover('hide');
		});
		
		//Bind Cancel Operation for recursive ajax requests
		$('button.stop-current-operation')
		.live('click',function(){
			cancel_ajax_recursive_function = true;
		});
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
	
	//Hide / Show Column after serverside processing
	function ajax_hide_show_column_checkbox(data){
		//get current column
		var col = data.column_num;
		++col;
		++col;
		fnShowHide( col );
		
		//Toggle Check Box State
		$('#show-hide-column-con')
		.find('input[name="'+data.column_name+'"]')
		.attr('checked', data.column_state);
		
		var parent = $('#show-hide-column-con').find('input[name="'+data.column_name+'"]').parents('.ui-checkbox');
		
		/*		
		if(data.column_state=='unchecked'){
			parent
			.find('label')
			.removeClass('ui-checkbox-on')
			.addClass('ui-checkbox-off')
			.attr('dataIcon','checkbox-off');
			
			parent
			.find('.ui-icon-checkbox-on')
			.removeClass('ui-icon-checkbox-on')
			.addClass('ui-icon-checkbox-off');
		}else{
			parent
			.find('label')
			.removeClass('ui-checkbox-off')
			.addClass('ui-checkbox-on')
			.attr('dataIcon','checkbox-on');
			
			parent
			.find('.ui-icon-checkbox-off')
			.removeClass('ui-icon-checkbox-off')
			.addClass('ui-icon-checkbox-on');
		}
		*/
	};
		
	//Update Hidden / Show Columns
	function update_column_view_state(){
		$('ul.show-hide-column-con')
		.find('input[type="checkbox"]')
		.each(function(){
			if(!$(this).is(':checked')){
				//get current column
				var col = $(this).parents('li').index();
				col += 2;
				fnHide(col);
			}
		});
	};
	
	//Column Selection
	function fnShowHide( iCol )
	{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = $('#example').dataTable();
		 
		var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		oTable.fnSetColumnVis( iCol, bVis ? false : true );
	};
	
	//Column Selection
	function fnHide( iCol )
	{
		
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = $('#example').dataTable();
		oTable.fnSetColumnVis( iCol, false);
	};
	
	//File Uploader
	function createUploader(){
		if($('.upload-box').hasClass('cell-element')){
			
			$('.upload-box').each(function(){
				var id = $(this).attr('id');
				var name = $(this).find('input').attr('name');
				var acceptable_files_format = $(this).find('input').attr('acceptable-files-format');
				
				var uploader = new qq.FileUploader({
					element: document.getElementById(id),
					listElement: document.getElementById('separate-list'),
					action: pagepointer+'php/upload.php',
					params: {
						tableID: $('form').find('input[name="table"]').val(),
						name:name,
						acceptable_files_format:acceptable_files_format,
					},
					onComplete: function(id, fileName, responseJSON){
						if(responseJSON.success=='true'){
							$('.qq-upload-success')
							.find('.qq-upload-failed-text')
							.text('Success')
							.css('color','#ff6600');
						}else{
							//alert('failed');
						}
					}
				});
			});
			
		}
	};
	
	initiate_tiny_mce_for_popup_textarea();
	function initiate_tiny_mce_for_popup_textarea(){
		
		$('textarea#popTextArea').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tinymce.min.js',
			
			// General options
			theme: "modern",
			height : 280,
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
	};
	
	bind_quick_print_function();
	function bind_quick_print_function(){
		$('button.quick-print')
		.live('click', function(){
			
			if( $(this).attr('merge-and-clean-data') && $(this).attr('merge-and-clean-data') == 'true' ){
				var $content = $( $(this).attr('target') ).clone();
				
				//Get Records
				var tbody = $content.find('#example').find('tbody');
				
				//Remove Action Button Column
				tbody.find('.view-port-hidden-table-row').remove();
				tbody.find('.remove-before-export').parents('td').remove();
				
				//Get Heading
				var thead = $content.find('.dataTables_scrollHeadInner').find('thead');
				thead.find('th').css('width','auto');
				
				//Remove Action Button Column
				thead.find('.remove-before-export').parents('th').remove();
				thead.find('.remove-before-export').remove();
				
				//Adjust Colspan
				thead
				.find('.change-column-span-before-export')
				.attr('colspan', thead.find('.change-column-span-before-export').attr('exportspan') );
				
				//Get Screen Data
				var html = '<div id="dynamic"><table width="100%" style="position:relative;" cellspacing="0" cellpadding="0"><thead>'+thead.html()+'</thead><tbody>'+tbody.html()+'</tbody></table></div>';
			}else{
				var html = $( $(this).attr('target') ).html();
			}
			
			var x=window.open();
			x.document.open();
			x.document.write( '<link href="'+ $('#print-css').attr('href') +'" rel="stylesheet" />' + '<body style="padding:0;">' + $('title').text() + html + '</body>' );
			x.document.close();
			x.print();
		});
	};
	
	initiate_tiny_mce_for_popup_textarea();
	function initiate_tiny_mce_for_popup_textarea(){
		
		$('textarea#popTextArea').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tinymce.min.js',
			
			// General options
			theme: "modern",
			height : 280,
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
	
	function recreateDataTables(){
		
			
		//INITIALIZE DATA TABLES
		oTable = $('#example').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "ajax_server/"+class_name+"_server.php",
			"sScrollY": "350",
			"sPaginationType": "full_numbers",
			"sScrollX": "100%",
			"bJQueryUI": true,
			"bDestroy": true,
			//"sDom": "Rlfrtip",
			"sDom": 'R<"H"lfr>t<"F"ip>',
			"bStateSave": true,
			"iDisplayLength": 100,
			 "aoColumnDefs": [ 
				 { "bSortable": false, "aTargets": [ 0 ] }
			   ],
			"fnInitComplete": function () {
				//Store Normal Table
				//$('#cloned-content-area').html($('#content-area').html());
				/*
				new FixedColumns( oTable, {
					"iLeftColumns": 4,
					"iLeftWidth": 450,
				} );
				*/
				
				$('#example_filter')
				.find('input')
				.attr({
					placeholder:"Search",
					title:"Perform quick search",
				});
				
				$('#example_length')
				.find('select')
				.css('width', 'auto');
				
				//UPDATE MORE DETAILS COLUMN SELECTOR
				create_field_selector_control();
				
				$('.pop-up-button').popover({
					html:true,
					container:'body',
					content:function(){
						var html = $(this).find('div.pop-up-content').html();
						return html;
					},
				});
				
			},
			"fnDrawCallback": function() {
			   //Optimize Search Field
				alert(1);
				
				//Bind form submission event
				action_button_submit_form();
				select_record_click_function();
		
				//Bind details open and close event to table
				bind_details_button_click();
				
				//Rehighlight Selected Record
				rehighlight_selected_record_function();
				
			}
		
		});
		
		
	};
	
	bind_page_up_down_scrolling();
	function bind_page_up_down_scrolling(){
		
		$(document)
		.bind('keydown',function(e){
			var scrollTopAmountLarge = 300;
		
			var scrollTopAmountSmall = 50;
			
			var scrollTopAmount = 0;
			var scrollAlongAmount = 0;
			
			var scrollTopToSelectedRow = 0;
			
			var current_view;
			var $element;
			var $element_to_scroll;
			
			if( ( $('#example_wrapper') && $('#example_wrapper').is(':visible') ) ){
				$element = $('#example') ;
				$element_to_scroll = $('#example_wrapper').find('.dataTables_scrollBody');
				current_view = 'table';
			}
			
			if( $('#entities-wrapper') && $('#entities-wrapper').is(':visible') ){
				$element = $('#entities-wrapper');
				$element_to_scroll = $('#entities-wrapper');
				current_view = 'entities';
			}
			
			if( $element && $element_to_scroll ){
				
				switch(e.keyCode){
				case 35:	//End key
					scrollAlongAmount = $element.width();
				break;
				case 36:	//Home key
					scrollAlongAmount = $element.width() * -1;
				break;
				case 37:	//Left arrow
					scrollAlongAmount = (scrollTopAmountSmall * -1);
				break;
				case 38:	//Up arrow
					scrollTopAmount = (scrollTopAmountSmall * -1);
					scrollTopToSelectedRow = 2;
				break;
				case 39:	//Right arrow
					scrollAlongAmount = (scrollTopAmountSmall);
				break;
				case 40:	//Down arrow
					scrollTopAmount = scrollTopAmountSmall;
					scrollTopToSelectedRow = 1;
				break;
				case 34:	//Page down button
					scrollTopAmount = scrollTopAmountLarge;
				break;
				case 33:	//Page up button
					scrollTopAmount = (scrollTopAmountLarge * -1);
				break;
				case 65:	//A Ctrl [17]
					if(e.ctrlKey){
						e.preventDefault();
						
						multiple_selected_record_id = '';
						
						details_of_multiple_selected_records = '';
						
						$element
						.find('tr:visible')
						.each(function(){
							var id_of_record = $(this).find('.datatables-record-id').attr('id');
							multiple_selected_record_id = multiple_selected_record_id +':::'+id_of_record;
							
							//Push All Details to display container
							var passed_value = $('#main-details-table-'+id_of_record).html();
							if( passed_value )
								details_of_multiple_selected_records += passed_value;
						});
						
						if( $('#record-details-home').is(':visible') && details_of_multiple_selected_records ){
							$('#record-details-home')
							.html( details_of_multiple_selected_records );
						}
						
						$element
						.find('tr')
						.addClass('row_selected');
						
						return false;
					}
				break;
				}
				
				if(scrollTopAmount){
					var scrollPosition = $element_to_scroll.scrollTop();
					
					if( scrollTopToSelectedRow && single_selected_record ){
						if( $('#'+single_selected_record) ){
						
							switch( scrollTopToSelectedRow ){
							case 1:
								if( current_view == 'table' ){
									$('#'+single_selected_record).parents('tr').next().click();
									scrollTopToSelectedRow = $('#'+single_selected_record).parents('tr').height();
								}
								
								if( current_view == 'entities' ){
									$('#'+single_selected_record).parents('li').next().mouseup();
									scrollTopToSelectedRow = $('#'+single_selected_record).parents('li').height();
								}
							break;
							case 2:
								if( current_view == 'table' ){
									$('#'+single_selected_record).parents('tr').prev().click();
									scrollTopToSelectedRow = $('#'+single_selected_record).parents('tr').height() * -1;
								}
								
								if( current_view == 'entities' ){
									$('#'+single_selected_record).parents('li').prev().mouseup();
									scrollTopToSelectedRow = $('#'+single_selected_record).parents('li').height() * -1;
								}
							break;
							}
						}else{
							scrollTopToSelectedRow = 0;
						}
					}else{
						scrollTopToSelectedRow = 0;
					}
					
					if( scrollTopToSelectedRow ){
						$element_to_scroll
						.scrollTop( scrollPosition + scrollTopToSelectedRow );
					}else{
						$element_to_scroll
						.scrollTop(scrollPosition + scrollTopAmount);
					}
				}
				
				if(scrollAlongAmount){
					var scrollPosition = $element_to_scroll.scrollLeft();
					
					$element_to_scroll
					.scrollLeft(scrollPosition + scrollAlongAmount);
				}
				
			}
			scrollTopAmount = 0;
			scrollAlongAmount = 0;
		});
		/*
		$('#example_wrapper')
		.find('.dataTables_scrollBody')
		.bind('keydown',function(e){
			alert(e.code);
			$(this).scrollTop($(this).scrollTop()+scrollTopAmountLarge);
		});
		*/
	};
	
	bind_create_field_selector_control();
	function bind_create_field_selector_control(){
		//bind click events
		$('ul#record-details-field-selector')
		.find('input[type="checkbox"]')
		.live('change',function(){
			if( $(this).attr('checked') )
				$( '.details-section-container-row-'+$(this).val() ).show();
			else
				$( '.details-section-container-row-'+$(this).val() ).hide();
		});
	};
	
	function create_field_selector_control(){
		
		var $details_table = $('table.main-details-table:first');
		
		var list_elements = '';
		
		if( $details_table ){
			$details_table
			.find('tr')
			.each(function(){
				
				list_elements += '<li><label class="checkbox"><input type="checkbox" value="'+$(this).attr('jid')+'" checked="checked" />'+$(this).find('td.details-section-container-label').text()+'</label></li>';
				
			});
			
			$('ul#record-details-field-selector')
			.html( list_elements );
			
		}
	};
	
	//Bind Multi-select option tooltip
	var timer_interval;
	var mouse_vertical_position;
	
	var progress_bar_timer_id;
	function progress_bar_change(){
		var total = 85;
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
	
	$('<style>.invalid-data{ background:#faa !important; }</style><div id="ogbuitepu-tip-con"><div></div></div>').prependTo('body');
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
			
			$(this)
			.find('.form-element-required-field')
			.blur();
			
			if( $(this).find('.form-element-required-field').hasClass('invalid-data') ){
				$(this)
				.find('.invalid-element:first')
				.focus();
				
				$(this).data('do-not-submit', true);
				
				//display notification to fill all required fields
				var data = {
					typ:'jsuerror',
					err:'Invalid Form Field',
					msg:'Please do ensure to correctly fill all required fields with appropriate values',
				};
				display_notification( data );
				
				return false;
			}
			
			$(this).data('do-not-submit', false);
			
		});
		
	};
	
	function validate( me ){
		//var e = $('#required'+name);
		//alert(e.attr('alt'));
		
		if( testdata( me.val() , me.attr('alt') ) ){
			if(me.hasClass('invalid-data')){
				me.removeClass('invalid-data').addClass('valid-data');
			}else{
				me.addClass('valid-data');
			}
		}else{
			if(me.hasClass('valid-data')){
				me.addClass('invalid-data').removeClass('valid-data');
			}else{
				me.addClass('invalid-data');
			}
		}
	};
	
	function testdata(data,id){
		switch (id){
		case 'text':
		case 'textarea':
		case 'upload':
			if(data.length>1)return 1;
			else return 0;
		break;
		case 'category':
			if(data.length>11)return 1;
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
			return vpassword(data);
		break;
			if(data.length>6)return 1;
			else return 0;
		break;
		case 'phone':
			return vphone(data);
			break;
		case 'select':
		case 'multi-select':
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
		if($('input[type="password"]:first').val()!=pass){
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
	/******************************************************/
		
});