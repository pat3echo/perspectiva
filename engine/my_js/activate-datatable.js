recreateDataTables();
update_column_view_state();
bind_show_hide_column_checkbox();
set_function_click_event();

function recreateDataTables(){
	if( ! class_name ){
		class_name = $('#example').attr('class-name');
	}
	//INITIALIZE DATA TABLES
	oTable = $('#example').dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": pagepointer+"ajax_server/"+class_name+"_server.php",
		"sScrollY": "300",
		"sPaginationType": "full_numbers",
		"sScrollX": "100%",
		"bJQueryUI": true,
		"bDestroy": true,
		//"sDom": "Rlfrtip",
		"sDom": 'R<"H"lfr>t<"F"ip>',
		"bStateSave": true,
		"iDisplayLength": 25,
		 "aoColumnDefs": [ 
			 { "bSortable": false, "aTargets": [ 0 ] }
		   ],
		/*
		THIS CODE HAS TO BE INSERTED BEFORE THE TABLE REFRESHES
			//Return inline-edit fields to their container
			if( $('#inline-edit-form-wrapper') && $('.inline-form-element') ){
				$('.inline-form-element')
				.removeClass('inline-form-element')
				.addClass('form-gen-element')
				.appendTo(	$('#inline-edit-form-wrapper').find('form') );
			}
		*/
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
				placeholder:"Quick Search",
				title:"Perform quick search",
			})
			.css({
				'padding': '2px',
				'margin': '2px',
			});
			
			$('#example_length')
			.find('select')
			.css({
				'padding': '2px',
				'margin': '2px',
				'width': 'auto',
			});
			
			//UPDATE MORE DETAILS COLUMN SELECTOR
			create_field_selector_control();
			
			$('.pop-up-button').popover({
				html:true,
				container:'body',
				content:function(){
					var html = $(this).find('div.pop-up-content').html();
					$(this).find('div.pop-up-content').html('');
					return html;
				},
			});
			
			oTable.fnAdjustColumnSizing();
			
			
		},
		"fnDrawCallback": function() {
		   //Optimize Search Field
		   
			//Bind form submission event
			//action_button_submit_form();
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
		
		if( ! $('input, select, textarea').is(':focus') ){
		
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
		}
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

	var reload_table_after_form_submit = 1;
	
	function submit_form_data( $me ){
		if( $me.data('do-not-submit') ){
			return false;
		}
		
		reload_table_after_form_submit = 1;
		if( $me.data('reload-table') ){
			reload_table_after_form_submit = $me.data('reload-table');
		}
		//console.log('r',reload_table_after_form_submit);
		
		ajax_data = $me.serialize();
	
		form_method = 'post';
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
	
	//Bind Show/Hide Column Checkboxes to Show/Hide Column Menu Button
	function bind_show_hide_column_checkbox(){
		
		$('ul.show-hide-column-con')
		.find('input[type="checkbox"]')
		.live('click',function(){
			
			//get current column
			var col = $(this).parents('li').index();
			
			$(this).attr('column-num',col);
			
			set_the_function_click_event($(this));
			
			oTable.fnAdjustColumnSizing();
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
		
		$('input#restore-button-yes')
		.live('click',function(e){
			$('a#restore-selected-record').popover('hide');
			
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
		
		$('input#restore-button-no')
		.live('click',function(e){
			$('a#restore-selected-record').popover('hide');
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
		
		oTable.fnAdjustColumnSizing();
		
	};
	
	//Column Selection
	function fnShowHide( iCol )
	{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable1 = $('#example').dataTable();
		 
		var bVis = oTable1.fnSettings().aoColumns[iCol].bVisible;
		oTable1.fnSetColumnVis( iCol, bVis ? false : true );
		
	};
	
	//Column Selection
	function fnHide( iCol )
	{
		
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = $('#example').dataTable();
		oTable.fnSetColumnVis( iCol, false, false);
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
		$('#example').find('tr td').off('dblclick');
		
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
		
		$('#example')
		.find('tr')
		.find('td')
		.on('dblclick', function(e){
			//check if edit record button is visible
			if( $('a#edit-selected-record') && $('a#edit-selected-record').is(':visible') ){
				//clear normal form content
				$('#form-content-area').html('');
				
				$clicked_element_parent = $('#example');
				$clicked_element_group_selector = 'tr';
			
				var shiftctrlKey = false;
				
				select_record_click_event($clicked_element_parent, $(this).parents('tr') , $clicked_element_group_selector, shiftctrlKey);
				
				//display inline edit form
				$(this)
				.parents('tr')
				.addClass('inline-edit-in-progress');
				
				var input_id = $(this).find('.datatables-cell-id').val();
				var record_id = $(this).find('.datatables-cell-id').attr('jid');
				
				$('#inline-edit-form-wrapper')
				.find('input[name="id"]')
				.val( record_id );
					
				$('#inline-edit-form-wrapper')
				.find('.form-gen-element')
				.attr('disabled', true);
				
				var $input = $('#inline-edit-form-wrapper').find('.'+input_id);
				
				//check input type
				switch( $input.attr('alt') ){
				case 'select':
					$input
					.val(  $(this).find('.datatables-cell-id').attr('real-value') );
				break;
				case 'number':
					$input
					.val( text.replace(',','') );
				break;
				case 'date':
					var real_values = text.split('-');
							
					if( real_values.length == 3 && real_values[1] ){
						text = real_values[2]+'-'+months_reverse[ real_values[1] ]+'-'+real_values[0];
					}
					$input
					.val( text );
				break;
				default:
					$input
					.val( $(this).text() );
				break;
				}
				
				if( $(this).find('.datatables-cell-id') && $(this).find('.datatables-cell-id').val() ){
					var text = $(this).text();
					var html = $(this).html().replace( text , '' );
					
					$(this)
					.data('cell-html', html )
					.empty();
				
					$input
					.css( 'width' , ($(this).width() - 20)+'px')
					.appendTo( $(this) )
					.removeClass('form-gen-element')
					.addClass('inline-form-element')
					.attr('disabled', false)
					.focus();
				}
				
				$(this).siblings().each( function(){
					var $e = $(this);
					if( $e.find('.datatables-cell-id') ){
					
						var input_id = $e.find('.datatables-cell-id').val();
						
						if( input_id ){
							var $input = $('#inline-edit-form-wrapper').find('.'+input_id);
							
							var text = $e.text();
							var html = $e.html().replace( text , '' );
							
							//check input type
							switch( $input.attr('alt') ){
							case 'select':
								$input
								.val(  $e.find('.datatables-cell-id').attr('real-value') );
							break;
							case 'number':
								$input
								.val( text.replace(',','') );
							break;
							case 'date':
								var real_values = text.split('-');
								
								if( real_values.length == 3 && real_values[1] ){
									text = real_values[2]+'-'+months_reverse[ real_values[1] ]+'-'+real_values[0];
								}
								$input
								.val( text );
							break;
							default:
								$input
								.val( text );
							break;
							}
							
							$e
							.data('cell-html', html )
							.empty();
							
							$input
							.css( 'width' , ($e.width() - 20)+'px')
							.appendTo( $e )
							.removeClass('form-gen-element')
							.addClass('inline-form-element')
							.attr('disabled', false);
						}
					}
				});
				
				//readjust table width
				//oTable.fnAdjustColumnSizing();
			}
		});
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
	
	var call_custom_function_after_record_selection_event = 0;
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
			
			if( ! $clicked_element.hasClass('inline-edit-in-progress') ){
				//Mark DataTable Row as Selected
				$clicked_element.removeClass('row_selected');
				
				//Store ID of Selected Row
				single_selected_record = $clicked_element.find('.datatables-record-id').attr('id');
				//console.log('before', single_selected_record+'	-	'+multiple_selected_record_id);
				
				multiple_selected_record_id = multiple_selected_record_id.replace( single_selected_record+':::' , '' );
				single_selected_record = '';
			}
			//console.log('after', single_selected_record+'	-	'+multiple_selected_record_id);
		}else{
			if( shiftctrlKey ){
				if( single_selected_record && multiple_selected_record_id == '' )
					multiple_selected_record_id = single_selected_record;
					
				multiple_selected_record_id += ':::'+$clicked_element.find('.datatables-record-id').attr('id');
			}else{
				//Clear All Previously Selected Rows in DataTable
				$clicked_element_parent.find($clicked_element_group_selector).removeClass('row_selected');
				
				if( $('.inline-form-element') && $('.inline-form-element').length > 0 ){
					$('.inline-form-element')
					.each(function(){
						var value = '';
						var real_value = '';
						
						switch( $(this).attr('alt') ){
						case 'select':
							real_value = $(this).val();
							value = $(this).find('option[value="'+real_value+'"]').text()+''+$(this).parent( 'td' ).data( 'cell-html' );
						break;
						case 'date':
							var real_values = $(this).val().split('-');
							
							if( real_values.length == 3 && real_values[1] ){
								value = real_values[2]+'-'+months[ real_values[1] ]+'-'+real_values[0]+''+$(this).parent( 'td' ).data( 'cell-html' );
							}
							
						break;
						default:
							value = $(this).val()+''+$(this).parent( 'td' ).data( 'cell-html' );
						break;
						}
							
						var $parent_cell = $(this).parent( 'td' );
						
						$parent_cell
						.html( value );
						
						switch( $(this).attr('alt') ){
						case 'select':
							$parent_cell.find('.datatables-cell-id').attr('real-value', real_value );
						break;
						}
						
						$(this)
						.removeClass('inline-form-element')
						.addClass('form-gen-element')
						.appendTo(	$('#inline-edit-form-wrapper').find('form') );
					});
					
					$clicked_element_parent
					.find('.inline-edit-in-progress')
					.removeClass('inline-edit-in-progress');
					
					$('#inline-edit-form-wrapper')
					.find('input[name="skip_validation"]')
					.val('true');
					
					$('#inline-edit-form-wrapper')
					.find('form')
					.data('reload-table', 2);
					
					submit_form_data( $('#inline-edit-form-wrapper').find('form') );
				}
				
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
		
		if( call_custom_function_after_record_selection_event ){
			if( single_selected_record || multiple_selected_record_id ){
				called_custom_function_after_record_selection_event( single_selected_record , multiple_selected_record_id );
			}
		}
		//return false;
	};
	
	function set_function_click_event(){
		if(!bound_menu_items_to_actions){
			//Ensure that Menus are bound only once
			$('#module-menu')
			.find('a')
			.not('.drop-down')
			.bind('click',function( e ){
				e.preventDefault();
				set_the_function_click_event($(this));
				
			});
			
			bound_menu_items_to_actions = 1;
		}
		
		
		$('a#add-new-record')
		.add('a#generate-report')
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
        .add('a.trigger-action')
		.bind('click',function( e ){
			e.preventDefault();
			set_the_function_click_event($(this));
		});
		
		$('#refresh-datatable')
		.bind('click',function( e ){
			e.preventDefault();
			
			if( oTable ){
				oTable.fnReloadAjax();
				
				$(this).hide();
			}
		});
		
		//Bind Edit Buttons Event and entity right click menu buttons events
		$('a#edit-selected-record')
		.add('#add-product-variations-button')
		.add('a.trigger-single-select-action')
		.add('#manage-product-variations-button')
		.bind('click',function( e ){
			e.preventDefault();
			
			if( single_selected_record ){
				clicked_action_button = $(this);
			
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record};
				form_method = 'post';
				
				ajax_data_type = 'json';
				
				ajax_action = 'request_function_output';
				ajax_container = '';
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
		
		$('a#generate-report-first-term')
		.add('a.trigger-multi-select-action')
		.bind('click',function(e){
			 
			e.preventDefault();
			
			if(single_selected_record || multiple_selected_record_id){
				clicked_action_button = $(this);
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id};
				form_method = 'post';
				
				ajax_data_type = 'json';
				
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_get_url = $(this).attr('action');
			
				ajax_send();
			}else{
				no_record_selected_prompt();
			}
		});
		
		//Bind Delete Buttons Event
		$('a#restore-selected-record')
		.add('a#delete-selected-record')
		.bind('click',function(e){
			e.preventDefault();
			if(single_selected_record || multiple_selected_record_id){
				
				clicked_action_button = $(this);
				
				ajax_data = {mod:$(this).attr('mod'), id:single_selected_record, ids:multiple_selected_record_id};
				form_method = 'post';
				ajax_data_type = 'json';
				
				//ajax_action = 'action_button_submit_form';
				ajax_action = 'request_function_output';
				ajax_container = '';
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
		
		var budget_id = '';
		var month_id = '';
		
		if( $me.attr('budget-id') && $me.attr('month-id') ){
			budget_id = $me.attr('budget-id');
			month_id = $me.attr('month-id');
		}
		
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
			request_function_output(function_name, function_class, module_id, function_id , budget_id, month_id );
			
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
	
    var g_all_signatories_html = '';
    var g_report_title = '';
    
    bind_quick_print_function();
	function bind_quick_print_function(){
		$('button#summary-view')
		.live('click', function(){
			$('#example')
			.find('tbody')
			.find('tr')
			.not('.total-row')
			.toggle();
		});
		
		$('button.quick-print')
		.live('click', function(){
			
			var html = get_printable_contents( $(this) );
			
			if( ! g_report_title ){
				g_report_title = $('title').text();
			}
			
			var x = window.open();
			x.document.open();
			x.document.write( '<link href="'+ $('#print-css').attr('href') +'" rel="stylesheet" />' + '<body style="padding:0;">' + g_report_title + html + g_all_signatories_html + '</body>' );
			x.document.close();
			x.print();
		});
		
		$('input.advance-print-preview, input.advance-print')
		.live('click', function(){
			
			var html = get_printable_contents( $(this) );
			
			var report_title = $('title').html();
			
			var $form = $('.popover-content').find('form.report-settings-form');
			
			var r_title = $form.find('input[name="report_title"]').val();
			var r_sub_title = $form.find('input[name="report_sub_title"]').val();
			
			var orientation = $form.find('select[name="orientation"]').val();
			var paper = $form.find('select[name="paper"]').val();
			
			var r_type = '';
			var r_user_info = '';
			
			if( $(this).hasClass( 'advance-print' ) ){
				var r_type = $form.find('input[name="report_type"]').filter(':checked').val();
				
				if( $form.find('input[name="report_show_user_info"]').is(':checked') ){
					var r_user_info = 'yes';
				}
			}
			
			var r_signatory = $form.find('input[name="report_signatories"]').val();
			
			g_all_signatories_html = '';
			g_report_title = '';
			
			if( r_title ){
				report_title = '<h3 style="text-align:center;">' + r_title;
				
				if( r_sub_title ){
					report_title += '<small style="display:block;">' + r_sub_title + '</small>';
				}
				
				report_title += '</h3>';
				
				g_report_title = report_title;
			}
			
			var all_signatories_html = '';
			var signatories_html = '';
			if( r_signatory ){
				if( $form.find('#report-signatory-fields').is(':visible') ){
					
					signatories_html = '<table width="100%">';
					
					$form
					.find('.signatory-fields')
					.each( function(){
						if( $(this).val() ){
							signatories_html += '<tr><td width="20%">' + $(this).val() + '</td><td style="border-bottom:1px solid #dddddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
						}
					} );
					
					signatories_html += '</table>';
				}
				
				all_signatories_html = '<div><table width="100%"><tr>';
				
				for( var i = 0; i < r_signatory; i++ ){
					all_signatories_html += '<td style="padding:10px;">';
						all_signatories_html += signatories_html;
					all_signatories_html += '</td>';
				}
				
				all_signatories_html += '</tr></table></div>';
				
				g_all_signatories_html = all_signatories_html;
			}
			
			switch( r_type ){
			case "mypdf":
				ajax_get_url = '?action='+r_type+'&todo=generate_pdf';
				
				ajax_data = {html:report_title + html + all_signatories_html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id'), report_title:report_title, report_show_user_info:r_user_info , orientation:orientation, paper:paper };
		
				form_method = 'post';
				ajax_data_type = 'json';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_send();
			break;
			case "myexcel":
				ajax_get_url = '?action='+r_type+'&todo=generate_excel';
				ajax_data = {html:html, current_module:$('#active-function-name').attr('function-class'), current_function:$('#active-function-name').attr('function-id') , report_title:report_title };
		
				form_method = 'post';
				ajax_data_type = 'json';
				ajax_action = 'request_function_output';
				ajax_container = '';
				ajax_send();
			break;
			default:
				var x=window.open();
				x.document.open();
				x.document.write( '<link href="'+ $('#print-css').attr('href') +'" rel="stylesheet" />' + '<body style="padding:0;">' + report_title + html + all_signatories_html + '</body>' );
				x.document.close();
				
				if( $(this).hasClass( 'advance-print' ) ){
					x.print();
				}
			break;
			}
		});
	};
	
	function get_printable_contents( $printbutton ){
		var html = '';
		
		if( $printbutton.attr('merge-and-clean-data') && $printbutton.attr('merge-and-clean-data') == 'true' ){
			var $content = $( $printbutton.attr('target') ).clone();
			
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
			html = '<div id="dynamic"><table class="'+$content.find('#example').attr('class')+'" width="100%" style="position:relative;" cellspacing="0" cellpadding="0"><thead>'+thead.html()+'</thead><tbody>'+tbody.html()+'</tbody></table></div>';
		}else{
			html = $( $printbutton.attr('target') ).html();
		}
		
		return html;
	};

	function request_function_output(function_name, function_class, module_id, function_id, budget_id, month_id ){
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
				ajax_data = {action:function_class, todo:function_name, module:module_id, id:function_id, budget:budget_id, month:month_id };
			}else{
				ajax_data = {action:function_class, todo:function_name, module:module_id, budget:budget_id, month:month_id };
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
		ajax_container = '';
		ajax_get_url = '';
		ajax_send();
	};