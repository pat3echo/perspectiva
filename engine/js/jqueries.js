$(document).ready(function()
	{
	
	
	var pagepointer = $('#pagepointer').text();
	
	//GET MENU SELECTION AND REDIRECT USER
	$('#personal-menu').live('change',function(){
		document.location = pagepointer+$(this).val();
	});
	
	var get = getUrlVars();
	var mod = decodeURIComponent(get["po"]);
	var not = decodeURIComponent(get["not"]);
	var timeline = decodeURIComponent(get["timeline"]);
	var action = decodeURIComponent(get["action"]);
	
	//Request Menu
	$.ajax({
		dataType:"text",
		type:"get",
		url: pagepointer+'menu.php',
		success: function(d){
			$('.content-secondary')
			.append(d)
			.trigger('create');
			
			//mm();
		}
	});
	
	
	function mm(){
		$('#menu-j')
		.find('a')
		.click(function(e){
			e.preventDefault();
			
			return false;
		});
	};
	$('#my-pdf').click(function(){
		$.ajax({
			type:"get",
			url: pagepointer+'ajax_server/pdf_server.php?action='+action,
			success: function(d){
				var h = $(document).height();
				h = h * 0.88;
				$('#my-pdf-file')
				.attr('src',d)
				.attr('height',h);
				document.location = document.location+'#my-pdf-display';
			}
		});
		return false;
	});
	
	var target;
	var formdata;
	var destinate;
	var idi;
	
	if(action=='view_page' || action=='view_page#'){
		$('.new_element').click(function(){
			$.ajax({
				type:"get",
				url: 'php/backend.php?new_element='+$(this).attr('alt'),
				success: function(d){
					//alert(d);
					$('#pages_view').attr('src',d);
				}
			});
		});
	}
	
	//DISPLAY TEMPLATE PREVIEW
	$('input[name="template"]').click(function(){
		$('#temppreview').css('backgroundImage','url(images/templates/'+$(this).val()+'.jpg)');
	});
	
	//DISPLAY COMPONENT TARGET
	$('#fake-link-parent').change(function(){
		//Get my page id
		var pageid = $(this).val().split(':::');
		
		//Hide All Fake radios currently displayed
		$('.fake-radio').hide();
		
		//Display my Components radio buttons
		$('#'+pageid[0]).show();
	});
	
	//DISPLAY COMPONENT PREVIEW
	$('input[name="fake-link-radio"]').click(function(){
		var comid = $(this).val();
		$('#fake-preview').html($('#'+comid).html());
	});
	
	$('#psubmit').live('click',function(){
		var t = 0;
		if($('#pass').val().length > 5 && $('#cpass').val().length > 5){
			
		}else{
			notify(8);
			return false;
		}
		
	});
	
	
	$('.post-title-input').focus(function(){
		if($(this).val()=='Enter Title')$(this).val('');
	}).blur(function(){
		if($(this).val()=='')$(this).val('Enter Title');
	});
	
	$('#post-comment').focus(function(){
		if($(this).val()=='Comment')$(this).val('');
	}).blur(function(){
		if($(this).val()=='')$(this).val('Comment');
	});
	
	$('#link').focus(function(){
		if($(this).val()=='Insert Link')$(this).val('');
	}).blur(function(){
		if($(this).val()=='')$(this).val('Insert Link');
	});
	
	$('#push').live('click',function(){
		var b = $('#x7 option:selected');
		$('#x8').prepend(b).find('> option').attr('selected',true);
	});
	$('#pull').live('click',function(){
		var b = $('#x8 option:selected');
		$('#x7').prepend(b);
		$('#x8').find('> option').attr('selected',true);
	});
	
	if(timeline=='y'){
		$('.blog-block').not('#timeline').hide();
	}else{	
		if($('.blog-block').is(':visible'))
		$('.blog-block').not(':first').hide();
	}
	
	$('.jqSidebar')
	.css('cursor','pointer')
	.click(function(){
		if($('.blog-block').is(':visible'))
		$('.blog-block').slideUp();
		
		$(this).find('.blog-block:first').slideDown();
	});
		
	 $('.message').click(function(e) {
		var me = $(this);
		var title = me.attr('title');
		  var chart;
			  chart = new Highcharts.Chart({
					chart: {
					renderTo: 'tab1',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: title
				},
				xAxis: {
					categories: dan_isa_patrick(me.attr('id'))
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Votes'
					}
				},
				exporting:{
					enabled: false
				},
				plotOptions: {
					bar: {
						allowPointSelect: true,
						cursor: 'pointer',
						size: '45%',
						dataLabels: {
							enabled: true,
							color: '#000000',
							connectorColor: '#000000'
						},
						showInLegend:true
					}					
				},
				series: [{
					type: 'column',
					name: 'Votes',
					data: dan_isa_patrick_ogbuitepu(me.attr('id'))
				}]
			});			
			
		});
		
		$('.stat1:first').click();
		
		
	//1. jquery saving
	//2. validation
	
	var xodus = 0;
	var pic = 0;
	
	//Upload Publications Form Validator
	$('.admin-submit').live('click',function(){
		
			if(xodus==0){ 
				$('.admin-text-box, .admin-text-upload, .admin-text-area').each(function(){
					if($(this).val().length < 1){
						$(this).addClass('error-field');
						pic = 1;
					}
				});
			}

			if($('.admin-text-upload').val=='' || pic==1){
				notify(2);
				pic=0;
				return false;
			}
		
	});
	
	notify(not);
	
	$('.published').change(function(){
		var me = $(this);
		var name = me.attr('name').split('&');
		if($(this).is(':checked')){
			$(this).parent().parent().addClass('public');
			$.ajax({
				type:"get",
				url: './php/backend.php?'+$(this).attr('alt')+'=true&id='+$(this).attr('id'),
				success: function(data) {
					if(data==1)notify(data);
				}
			});
						
			//ELIMINATE ALL OTHER ACTIVE
			if($(this).attr('type')=='radio'){
				$('input[type=radio]').next().text(name[1]);
			}
			
			$(this).next().text(name[0]);
		}else{
			$(this).parent().parent().removeClass('public');
			$.ajax({
				type:"get",
				url: './php/backend.php?'+$(this).attr('alt')+'=false&id='+$(this).attr('id'),
				success: function(data) {
					if(data==1)notify(data);
				}
			});
			$(this).next().text(name[1]);
		}		
	});
	
	$('.published-all').change(function(){
		var me = $(this);
		var name = me.attr('name').split('&');
		if($(this).is(':checked')){
			$('.published').attr('checked',true).next().text(name[0]);
			
			$.ajax({
				type:"get",
				url: './php/backend.php?all=1&'+$(this).attr('alt')+'=true&id='+$(this).attr('id'),
				success: function(data) {
					if(data==1)notify(data);
				}
			});
			
		}else{
			$('.published').attr('checked',false).next().text(name[1]);
			$.ajax({
				type:"get",
				url: './php/backend.php?all=1&'+$(this).attr('alt')+'=false&id='+$(this).attr('id'),
				success: function(data) {
					if(data==1)notify(data);
				}
			});
		}
	});
	
	var delfiy;
	$('.del').click(function(){
		//Javascript Modal
		xodus = 1;
		$('#over').css({
			top:$(document).scrollTop(),
			left:$(document).scrollLeft()
		})
		.show();
		
		//$('body').css('overflow','hidden');
		
		$('#over-text').append('<div style="clear:both;"></div><br /><br />Are you sure you want to delete the selected record?<br /><br /><br /><br /><div style="float:right;"><input type="submit" class="alt_btn" alt="'+$(this).attr('alt')+'&id='+$(this).attr('id')+'" value="Yes" id="yeso"><input type="reset" class="" value="No" id="noo"></div>');
		$('#over-con-title').text('Confirm Dialog');
		
		var t = $('#over').offset().top+(($('#over').height()- $('#over-con').height())/2);
		var w = ($(window).width() - $('#over-con').width())/2;
		delfiy = $(this);
		
		$('#over-con').css({
			'left':w,
			'top':t
		})
		.show()
		.appendTo('body');
	});
	
	$('#yeso').live('click',function(){
		$.ajax({
			type:"get",
			url: './php/backend.php?'+$(this).attr('alt'),
			success: function(data) {
				if(data==1){					
					delfiy.parent().parent().remove();
				}
				notify(data);
			}
		});
		
		$('#noo').click();
	});
	
	
	var ion = 0;
	$('.admin-text-box, .admin-text-area').live('keyup',function(){
		if(ion==0){
			$('.xpub'+$(this).attr('alt')+' tr:last').clone().addClass('typing').insertAfter('.hd-row'+$(this).attr('alt'));
			
			$('.typing .ini').each(function(){
				$(this).text($(this).attr('alt'));
			});
			
			$('.typing').find('.'+$(this).attr('id')).text($(this).val());
		}else{
			$('.typing').find('.'+$(this).attr('id')).text($(this).val());
		}
		ion =1;
		
		if($(this).val().length>0)$(this).removeClass('error-field');
	});
	
	$('.admin-text-upload').live('change',function(){
		if($(this).val().length>0)$(this).removeClass('error-field');
	});
	
	function clear(){
		ion =0;
		$('.typing').remove();
	}
	
	var idt;
	function notify(val){
		
		$('.notification').stop().hide();
		
		if(val==1){
			$('.notification')
			.addClass('alert_success')
			.text('The changes have been saved.')
			.css({
				'top':0
			})
			.show('fast',function(){
				idt = setTimeout(function(){
					$('.notification').fadeOut(2200);
				},3000);
			});
		}if(val==0){
			$('.notification')
			.addClass('alert_error')
			.text('Oops something went wrong.')
			.css({
				'top':0
			})
			.show('fast',function(){
				idt = setTimeout(function(){
					$('.notification').fadeOut(2200);
				},3000);
			});
		}
		if(val==8){
			$('.notification')
			.addClass('alert_warning')
			.text('Ensure your password is over 6 characters long')
			.css({
				'top':0
			})
			.show('fast',function(){
				idt = setTimeout(function(){
					$('#notification').fadeOut(2200);
				},4000);
			});
		}
		if(val==2){
			$('.notification')
			.addClass('alert_warning')
			.text('Please ensure that all fields are completed')
			.css({
				'top':0
			})
			.show('fast',function(){
				idt = setTimeout(function(){
					$('.notification').fadeOut(2200);
				},3000);
			});
		}
	}
	
	$(document).scroll(function(){
		if(xodus){
			$('#over').css({
				top:$(document).scrollTop(),
				left:$(document).scrollLeft()
			})
		}
	});
	
	$('.edit').live('click',function(){
		xodus = 1;
		$('#over').css({
			top:$(document).scrollTop(),
			left:$(document).scrollLeft()
		})
		.show();
		
		//$('body').css('overflow','hidden');
		
		$.ajax({
			type:"get",
			url: './php/backend.php?'+$(this).attr('alt')+'&id='+$(this).attr('id'),
			success: function(data) {
				var dat = data.split('spanz1988tepu');
				$('#over-text').append(dat[0]);
				$('#over-con-title').text(dat[1]);
				
				var t = $('#over').offset().top+(($('#over').height()- $('#over-con').height())/2);
				var w = ($(window).width() - $('#over-con').width())/2;
				
				$('#over-con').css({
					'left':w,
					'top':t
				})
				.show()
				.appendTo('body');
			},
			callback: function(){
				$('input[name="enddate"]').datepick();
			}
		});
	});
	
	$('.hover').live('click',function(){
	
		if($(this).hasClass('asc')){
			var order = 'desc';
			$(this).removeClass('asc').addClass('desc');
		}else{ 
			var order = 'asc';
			$(this).addClass('asc').removeClass('desc');
		}
		var title = $(this).attr('title');
		var alt = $(this).attr('alt');
		$(this).attr('title',alt);
		$(this).attr('alt',title);
			
		var tb = $(this).parent().attr('alt');
		
		$(this).siblings().removeClass('asc').removeClass('desc');
		$.ajax({
			type:"get",
			url: './php/backend.php?'+tb+'=sort&table='+$(this).text()+'&order='+order,
			success: function(data) {
				$('.xpuby1 tbody').empty();
				$('.xpuby1 tbody').html(data);
			}
		});
	});
	
	$('#exit-over, #noo').live('click',function(){
		$('#over').hide();
		$('#over-text').empty();
		$('#over-con').hide();
		$('body').css('overflow','auto');
		xodus = 0;
	});
	
	//alert($(document).height());
	function ajaxFileUpload()
	{
		$("#fmj-form")
		.ajaxStart(function(){
			alert('hisd');
		})
		.ajaxComplete(function(){
			alert('hi');
		});

		$.ajaxFileUpload
		(
			{
				type:'POST',
				url:'doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{dest:destinate, id:idi},
				success: function (data)
				{
					
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		);
		
		return false;

	}
	
	function getUrlVars(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}
		
});