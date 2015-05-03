(function($) {
    $.fn.cProcessForm = {
        requestURL: 'http://192.168.1.7/dev-3/',
        //requestURL: 'http://localhost/dev-3/',
        handleSubmission: function( $form ){
            $form.on('submit', function(e){
                e.preventDefault();
                var d = $.fn.cProcessForm.transformData( $(this) );
                
                if( d.error ){
                    var settings = {
                        message_title:d.title,
                        message_message: d.message,
                        auto_close: 'no'
                    };
                    display_popup_notice( settings );
                }else{
                    var local_store = 1;
                
                    d[ 'object' ] = $(this).attr('name');
                    
                    if( $(this).attr('remote-storage') ){
                        local_store = 0;
                        internetConnection = true;
                        
                        $.fn.cProcessForm.post_form_data( $(this) );
                        tempData = d;
                    }
                    
                    if( local_store ){
                        //store data
                        //var stored = store_record( data );
                        //successful_submit_action( stored );
                        
                        alert('local storage');
                    }
                    
                    $form
                    .find('input')
                    .not('.do-not-clear')
                    .val('');
                }
                return d;
            });
        },
        transformData: function( $form ){
            
            var data = $form.serializeArray();
            
            var error = {};
            var txData = { error:false };
            var unfocused = true;
            
            $.each( data , function( key , value ){
                var $input = $form.find('#'+value.name+'-field');
                if( $input ){
                    if( $input.attr('data-validate') ){
                        var validated = $.fn.cProcessForm.validate.call( $input , unfocused );
                        
                        if( ! ( error.error ) && validated.error ){
                            //throw error & display message
                            error = validated;
                            unfocused = false;
                        }else{
                            //start storing object
                            txData[ value.name ] = value.value;
                        }
                        
                    }else{
                        txData[ value.name ] = value.value;
                    }
                }
            });
            
            if( error.error ){
                return error;
            }
            
            return txData;
        },
        validate: function( $element , unfocused ){
            var tit = "Error Title";
            var msg = "Error Msg";
            var err = false;
            
            if( $element.attr('data-validate') && $element.attr('required') ){
                switch( $element.attr('data-validate') ){
                case 'date':
                case 'text':
                case 'number':
                case 'tel':
                    if( $element.val().length < 1 ){
                        err = true;
                    }
                break;
                case 'email':
                    var email = $element.val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if( email.length<1 || !emailReg.test( email ) ) {
                        err = true;
                    }
                break;
                case 'confirm-email':
                    var email = $element.val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if( email.length<1 || !emailReg.test( email ) ) {
                        err = true;
                    }
                    
                    var $emails = $element.parents('form').find('input[type="email"]');
                    if( $emails ){
                        if( $emails.length > 1 ){
                            $emails.each(function(){
                                if( email != $(this).val() )err = true;
                            });
                        }else{
                            if( email != $emails.val() )err = true;
                        }
                    }
                break;
                case 'password':
                    if( $element.val().length < 6 ) {
                        err = true;
                    }
                break;
                case 'confirm-password':
                    var pw = $element.val();
                    var $pws = $element.parents('form').find('input[type="password"]');
                    if( $pws ){
                        if( $pws.length > 1 ) {
                            $pws.each(function(){
                                if( pw != $(this).val() )err = true;
                            });
                        }else{
                            if( pw != $pws.val() )err = true;
                        }
                    }
                break;
                }
                
                if( err ){
                    if( $element.attr('data-error-title') )tit = $element.attr('data-error-title');
                    if( $element.attr('data-error-msg') )msg = $element.attr('data-error-msg');
                    if( unfocused )$element.focus().select();
                }
            }
            
            var validated = {
                error: err,
                title: tit,
                message: msg,
            };
            return validated;
        },
        ajax_data: {},
        post_form_data: function( $form ){
            
            $.fn.cProcessForm.ajax_data = {
                ajax_data: $form.serialize(),
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: $form.attr('action'),
            };
            $.fn.cProcessForm.ajax_send.call();
        },
        function_click_process: 1,
        ajax_send: function( settings ){
            //Send Data to Server
            
            if( $.fn.cProcessForm.function_click_process ){
                $.ajax({
                    dataType: $.fn.cProcessForm.ajax_data.ajax_data_type,
                    type:$.fn.cProcessForm.ajax_data.form_method,
                    data:$.fn.cProcessForm.ajax_data.ajax_data,
                    crossDomain:true,
                    url: $.fn.cProcessForm.requestURL + 'engine/php/ajax_request_processing_script.php' + $.fn.cProcessForm.ajax_data.ajax_get_url,
                    timeout:80000,
                    beforeSend:function(){
                        $.fn.cProcessForm.function_click_process = 0;
                        $('div.progress-bar-container')
                        .html('<div class="virtual-progress-bar"><div class="progress-bar"></div></div>');
                        
                        $.fn.cProcessForm.progress_bar_change.call();
                    },
                    error: function(event, request, settings, ex) {
                        $.fn.cProcessForm.ajaxError.call( event, request, settings, ex );
                    },
                    success: function(data){
                        $.fn.cProcessForm.requestRetryCount = 0;
                        $.fn.cProcessForm.function_click_process = 1;
                        if( data.status ){
                            switch(data.status){
                            case 'authenticated-visitor':
                                data.url = $.fn.cProcessForm.requestURL;
                                authenticated_visitor( data );
                                return;
                            break;
                            case 'got-recent-activities':
                                data.url = $.fn.cProcessForm.requestURL;
                                got_recent_activities( data );
                                return;
                            break;
                            }
                        }
                        
                        if( data.typ ){
                            switch(data.typ){
                            case 'serror':
                            case 'uerror':
                            case 'saved':
                            case 'generated-report':
                                if( data.err && data.msg ){
                                    var settings = {
                                        'message_title':data.err,
                                        'message_message':data.msg,
                                    };
                                    display_popup_notice( settings );
                                }
                            break;
                            }
                        }
                    }
                });
            }
        },
        ajaxError: function( event, request, settings, ex ){
            
        },
        populateRecentActivities: function( $container ){
            $.fn.cProcessForm.ajax_data = {
                ajax_data: {},
                form_method: 'post',
                ajax_data_type: 'json',
                ajax_action: 'request_function_output',
                ajax_container: '',
                ajax_get_url: '?todo=get_recent_activties&action=entry_exit_log',
            };
            $.fn.cProcessForm.ajax_send.call();
        },
        requestRetryCount: 0,
        progress_bar_timer_id: 0,
        progress_bar_change: function(){
            var total = 80;
            var step = 1;
            
            if( $.fn.cProcessForm.progress_bar_timer_id )
                clearTimeout( $.fn.cProcessForm.progress_bar_timer_id );
                
            if( $.fn.cProcessForm.function_click_process == 0 ){
                var $progress = $('.virtual-progress-bar:visible').find('.progress-bar');
                
                if($progress.data('step') && $progress.data('step')!='undefined'){
                    step = $progress.data('step');
                }
                
                var percentage_step = ( step / total ) * 100;
                ++step;
                
                if( percentage_step > 100 ){
                    $progress
                    .css('width', '100%');
                    
                    $('.virtual-progress-bar')
                    .remove();
                    
                    $('.progress-bar-container')
                    .html('');
                    
                    //Refresh Page
                    $.fn.cProcessForm.function_click_process = 1;
                    
                    ++$.fn.cProcessForm.requestRetryCount;
                    
                    //Stop All Processing
                    window.stop();
                    
                    //check retry count
                    if( $.fn.cProcessForm.requestRetryCount > 1 ){
                        //display no network access msg
                        //requestRetryCount = 0;
                        
                        var settings = {
                            message_title:'No Network Access',
                            message_message: 'The request was taking too long!',
                            auto_close: 'no'
                        };
                        display_popup_notice( settings );
                        
                        internetConnection = false;
                    }else{
                        //display retrying msg
                        
                        var settings = {
                            message_title:'Refreshing...',
                            message_message: 'Please Wait.',
                            auto_close: 'yes'
                        };
                        //$.fn.cProcessForm.display_popup_notice.call( settings );
                        
                        //request resources again
                        $.fn.cProcessForm.ajax_send.call();
                        
                    }
                    
                }else{
                    $progress
                    .data('step',step)
                    .css('width', percentage_step+'%');
                    
                    $.fn.cProcessForm.progress_bar_timer_id = setTimeout(function(){
                        $.fn.cProcessForm.progress_bar_change.call();
                    },1000);
                }
            }else{
                $('.virtual-progress-bar')
                .find('.progress-bar')
                .css('width', '100%');
                
                setTimeout(function(){
                    $('.virtual-progress-bar')
                    .remove();
                    
                    $('.progress-bar-container')
                    .html('');
                },800);
            }
        },
    }
}(jQuery));

function display_popup_notice( settings ){
    var theme = 'a';
    var html = settings.message_title + "\n" + settings.message_message;
    alert( html );
    
    $('.pass-code-auth').slideDown();
    $('.processing-pass-code-auth').hide();
    $('.successful-pass-code-auth').hide();
};

var gCheck_sum = '';

function authenticated_visitor( data ){
    if( data.visitor_data.check_sum )gCheck_sum = data.visitor_data.check_sum;
    
    $('.optional-value').hide();
    
    $('.visitor-value').each(function(){
        if( $(this).attr('data-name') && $(this).attr('data-type') ){
            switch( $(this).attr('data-type') ){
            case 'text':
                if( data.visitor_data[ $(this).attr('data-name') ] ){
                    $(this).text( data.visitor_data[ $(this).attr('data-name') ] );
                    if( $(this).parent().hasClass('optional-value') ){
                        $(this).parent().show();
                    }
                }
            break;
            case 'src':
                if( data.visitor_data[ $(this).attr('data-name') ] ){
                    $(this).attr( $(this).attr('data-type') , data.url + 'engine/' + data.visitor_data[ $(this).attr('data-name') ] );
                }
            break;
            }
        }
    });
    $('.pass-code-auth').hide();
    $('.processing-pass-code-auth').hide();
    $('.successful-pass-code-auth').slideDown();
    
    //add info to recent visitors log
    var h = get_recent_visitors_html( data );
    if( h ){
        $('#chats').find('.chats').prepend( h );
    }
    
    if( data && data.visitor_data && data.visitor_data.previous_visits && data.visitor_data.previous_visits.data && Object.getOwnPropertyNames(data.visitor_data.previous_visits.data).length ){
        var h = '';
        $.each( data.visitor_data.previous_visits.data , function( key, val ){
            h += get_previous_visits_html( val );
        });
        if( h ){
            $('tbody#previous-visits').html(h);
            $('.previous-visits-container').show();
        }
    }else{
        $('.previous-visits-container').hide();
    }
};

function got_recent_activities( d ){
    var h = '';
    if( d && d.data && Object.getOwnPropertyNames(d.data).length ){
        
        $.each( d.data, function( key, val ){
            h += get_recent_visitors_html( { visitor_data: val, url:d.url } );
        });
    }
    
    if( h ){
        $('#chats').find('.chats').html( h );
    }
};

function get_recent_visitors_html( d ){
    var data = d.visitor_data;
    
    var label = 'Host: ';
    if( ! ( data.whom_to_see ) && data.job_role ){
        data.whom_to_see = data.job_role;
        label = 'Job Role: ';
    }
    //&& data.entry_time
    if( data.entry && data.date_time && data.full_name && data.photograph && data.whom_to_see  ){
        var html = '<li class="'+data.entry+'">';
        html += '<img class="avatar img-responsive" alt="" src="' + d.url + 'engine/' + data.photograph + '">';
        html += '<div class="message">';
            html += '<span class="arrow"></span>';
            html += '<a href="#" class="name">'+data.full_name+'</a>';
            html += '<span class="datetime"> at '+data.date_time+'</span>';
            html += '<span class="body">';
            html += label + data.whom_to_see;
            html += '</span>';
          html += '</div>';
       html += '</li>';
       return html;
   }
};

function get_previous_visits_html( data ){
    if( data.entry && data.date && data.time && data.reason_for_visit && data.whom_to_see  ){
        var html = '<tr>';
        html += '<td class="hidden-xs">'+data.date+'</td>';
        html += '<td><a href="#">'+data.time+'</a> ('+data.entry+')</td>';
        html += '<td class="hidden-xs">Host: '+data.whom_to_see+'<br />'+data.reason_for_visit+'</td>';
        html += '</tr>';
       return html;
   }
};

if (!window.DOMTokenList) {
  Element.prototype.containsClass = function(name) {
    return new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)").test(this.className);
  };

  Element.prototype.addClass = function(name) {
    if (!this.containsClass(name)) {
      var c = this.className;
      this.className = c ? [c, name].join(' ') : name;
    }
  };

  Element.prototype.removeClass = function(name) {
    if (this.containsClass(name)) {
      var c = this.className;
      this.className = c.replace(
          new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)", "g"), "");
    }
  };
}

// sse.php sends messages with text/event-stream mimetype.
var source = new EventSource('../engine/php/sse.php');

function closeConnection() {
  source.close();
  updateConnectionStatus('Disconnected', false);
}

function updateConnectionStatus(msg, connected) {
  var el = document.querySelector('#connection');
  if (connected) {
    if (el.classList) {
      el.classList.add('connected');
      el.classList.remove('disconnected');
    } else {
      el.addClass('connected');
      el.removeClass('disconnected');
    }
  } else {
    if (el.classList) {
      el.classList.remove('connected');
      el.classList.add('disconnected');
    } else {
      el.removeClass('connected');
      el.addClass('disconnected');
    }
  }
  el.innerHTML = msg + '<div></div>';
}

source.addEventListener('message', function(event) {
  if( event.data ){
  var data = JSON.parse(event.data);

  var options = {
        iconUrl: data.pic,
        title: data.title,
        body: data.msg+"\n"+data.host,
        timeout: 5000, // close notification in 1 sec
        onclick: function () {
            //console.log('Pewpew');
        }
    };
    var notification = $.notification(options)
    .then(function (notification) {
        //window.focus();
        //console.log('Ok!');
    }, function (error) {
        console.error('Rejected with status ' + error);
    });
    console.log('receive', data.check_sum );
    console.log('receiveG', gCheck_sum );
    
    if( data.check_sum && gCheck_sum != data.check_sum )
        authenticated_visitor( {visitor_data: data, url:$.fn.cProcessForm.requestURL } );
        
    $('.b-level').text('Notifications are ' + $.notification.permissionLevel());
  }
}, false);

source.addEventListener('open', function(event) {
  updateConnectionStatus('Connected', true);
}, false);

source.addEventListener('error', function(event) {
  if (event.eventPhase == 2) { //EventSource.CLOSED
    updateConnectionStatus('Disconnected', false);
  }
}, false);