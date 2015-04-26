/*
 *  Login/Register Page jQuery File
 *
 * 	All the jquery functions that controls interactivity
 *	written by Patrick Ogbuitepu
 *
 *	Copyright (c) November 2012
 */
(function(jQuery) {
	$(document).ready(function()
	{	
		var pagepointer = $('#pagepointer').text();
		
		//Activate Client Side Validation / Tooltips
		activate_tooltip_for_form_element( $('#form-content-area').find('form') );
		activate_validation_for_required_form_element( $('#form-content-area').find('form') );
		
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
			
			if( testdata( me ) ){
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
		
		function testdata( $element ){
			var data = $element.val();
			var id = $element.attr('alt');
			
			switch (id){
			case 'text':
			case 'textarea':
			case 'textarea-unlimited':
			case 'upload':
				if(data.length>0)return 1;
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
		
	});
})(jQuery);