// JavaScript Document

var toggle = "none";

function toggle_check(begin, end){
	if(toggle == "none"){
		toggle = "all"
		select_all(begin, end);
	}
	else{
		toggle = "none";
		select_none(begin, end);
	}
}

function check(value){
	checkbox_clear_main();
	if(document.forms['gridForm'].elements['checkbox_'+value].checked == true){
		document.forms['gridForm'].elements['checkbox_'+value].checked = false;
		if(value%2 == 0){
			changeClass('tr_'+value, 'trClassEven');
		}
		else{
			changeClass('tr_'+value, 'trClassOdd');
		}
	}
	else{
		document.forms['gridForm'].elements['checkbox_'+value].checked = true;
		changeClass('tr_'+value, 'trSelected');
	}
}

function select_all(begin, end){
	for(i=begin; i<=end; i++){
		document.forms['gridForm'].elements['checkbox_'+i].checked = true;
		changeClass('tr_'+i, 'trSelected');
	}
}

function select_none(begin, end){
	for(i=begin; i<=end; i++){
		document.forms['gridForm'].elements['checkbox_'+i].checked = false;
		if(i%2 == 0){
			changeClass('tr_'+i, 'trClassEven');
		}
		else{
			changeClass('tr_'+i, 'trClassOdd');
		}
	}
}

function checkbox_clear_main(){
	document.forms['gridForm'].elements['checkbox_main'].checked = false;
	toggle = "none";
}

function with_selected(href, begin, end){
	var qid = "";
	for(i=begin; i<end; i++){
		if(document.forms['gridForm'].elements['checkbox_'+i].checked == true){
			qid += document.forms['gridForm'].elements['val_'+i].value+",";
		}
	}
	val = qid.length - 1;
	qid = qid.substring(0, val);
	href = href+""+qid;
	window.location.href=href;
}