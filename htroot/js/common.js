$(document).ready(function(){
	load_options('','country');
	$('.tablesorter').tablesorter();

	$('.boolrg td').each(function() {
		var bool = $(this).text();
		if(bool == '0') {
			$(this).css('color', '#d12f19');
			$(this).css('text-align', 'center');
			$(this).text('No');
		}
		if(bool == '1') {
			$(this).css('color', '#529214');
			$(this).css('text-align', 'center');
			$(this).text('Yes');
		}
	});

	$('#takeIpc').click(function() {
		$('#takeRadioIpc').prop('checked', true);
	});
	$('#takeBinAddress').click(function(){
		$('#takeRadioBin').prop('checked', true);
	});

	$(".confirmClick").click( function() { 
    if ($(this).attr('title')) {
        var question = 'Are you sure you want to ' + $(this).attr('title').toLowerCase() + '?';
    } else {
        var question = 'Are you sure you want to do this action?';
    }
    if ( confirm( question ) ) {
        [removed].href = this.src;
    } else {
        return false;
    }



}); 




});

function load_options(id,index){
	$("#loading").show();
	if(index=="province") {
		$("#city").html('<option value="">Select City</option>');
		$("#province").html('<option value="">Select Province</option>');
		$("#loading").hide();
	}
	if(index=="city") {
		$("#city").html('<option value="">Select City</option>');
		$("#loading").hide();
	}

	$.post('/ajax/process_data',
	{ 'index': index, 'id': id },

	function(result) {
		$("#loading").hide();
		if(result) {
			$("#"+index).html(result);
		}
	}

	);
}

function add_region(regionName,regionDirection,regionDescription) {
	$("#loading").show();



}

$(function() {
	$(".draggable").draggable();
});

$(function() {
	$(".resizable").resizable();
})

