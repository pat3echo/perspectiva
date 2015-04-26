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
		