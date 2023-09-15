( function ( $ ) {
    'use strict';
$(window).keydown(function(event){
  if(event.keyCode == 13) {
    event.preventDefault();
    return false;
  }
});

$.puerto_multiple = function(eventID, eventHTTP){
	$(eventID).livequery('click', function(){
		var inp = $('[name="pl-check[]"]:checked');
		var inpIds = [];
		if(inp.length){
			inp.each(function(){
				inpIds.push($(this).val());
			});
			console.log(inpIds);
			$.post(path+"/ajax.php?request="+eventHTTP, {id: inpIds}, function(puerto){
				console.log(puerto);
				if(puerto.type == 'success') {
					switch(eventHTTP){
						case 'mmo&type=reject-users':
						case 'mmo&type=reject-questions':
						case 'mmo&type=reject-comments':
							inp.each(function(){
								$('#pt-obj-'+$(this).val()).addClass('bg-banned');
							});
						break;
						case 'mmo&type=approve-users':
						case 'mmo&type=approve-questions':
						case 'mmo&type=approve-comments':
							inp.each(function(){
								$('#pt-obj-'+$(this).val()).removeClass('bg-banned');
							});
						break;
					}

					$.puerto_alert('success', puerto.alert);
				} else {
					$.puerto_alert('error', puerto.alert);
				}
			}, 'json');
		} else {
			$.puerto_alert('error', '<strong>Oh Snap!</strong>this is an error');
		}

		return false;
	});
};

$.puerto_droped('.pl-options');
$.puerto_droped('.pl-side-drop');


/** Wysibb Editor **/
if($("#wysibb-editor").length){
	var textarea = document.getElementById('wysibb-editor');
	sceditor.create(textarea, {
		format: 'bbcode',
		style: path+'/assets/js/minified/themes/content/default.min.css',
		emoticonsRoot: path+'/assets/js/minified/',
		height: 400,
		toolbarExclude: 'indent,outdent,email,date,time,ltr,rtl,print,subscript,superscript',
		icons: 'material',
		rtl: rtl
	});
}
if($("#wysibb-editor1").length){
	var textarea1 = document.getElementById('wysibb-editor1');
	sceditor.create(textarea1, {
		format: 'bbcode',
		style: path+'/assets/js/minified/themes/content/default.min.css',
		emoticonsRoot: path+'/assets/js/minified/',
		height: 400,
		toolbarExclude: 'indent,outdent,email,date,time,ltr,rtl,print,subscript,superscript',
		icons: 'material',
		rtl: rtl
	});
}

$("[name=check_all]").livequery('click', function(){
	var pr = $(this).prop('checked');
	$('[name="pl-check[]"]').each(function(){
		$(this).prop('checked', pr);
	});
});


/*
#-------------------------------------------------------------------------
# + 5) Comments
#-------------------------------------------------------------------------
*/

$.list_action('pl-comments-trash', '', 'actions&type=comments-trash');
$.list_action('pl-comments-reject', '', 'actions&type=comments-reject');
$.list_action('pl-comments-approve', '', 'actions&type=comments-approve');


/*
#-------------------------------------------------------------------------
# + 5) Subscribers
#-------------------------------------------------------------------------
*/

$.list_action('pl-subscribers-trash', '', 'actions&type=subscribers-trash');

$.list_action('pl-payout-approve', '', 'actions&type=payout-approved');


/*
#-------------------------------------------------------------------------
# + 5) Members
#-------------------------------------------------------------------------
*/


$.puertoAnswerImage = function(id, choose, thumb, thumb2, crop = true, type=false){
	var id = id, choose = choose, name = $("input[rel='"+choose+"']"), thumb = thumb;
	$(id).imageUploader({
	  fileField: choose,
	  urlField: '#url',
	  hideFileField: false,
	  hideUrlField: false,
	  url: path+'/ajax.php?request=imageupload'+(!crop ? '&pg=crop' : '')+(type ? '&t='+type : ''),
	  thumbnails: {
	    div: thumb,
	    crop: 'zoom',
	    width: 150,
	    height: 150
	  },
	  afterUpload: function (data) {
			try {
				var puerto = JSON.parse(data);
				if(puerto.error){
				} else {
					name.val(puerto.url);
					$(thumb2).attr("src",puerto.url);
				}

			} catch (e) {
				console.log('after uploadee', data);
				console.log(data);
			}

		},
	  beforeUpload: function()           { console.log('before upload'); $(thumb).html("");  return true; },
	  error: function(msg) { alert(msg); },
	});
}

$.puertoAnswerImage('#dropZone', '#chooseFile', '#thumbnails', '#thumbnails', false);
$.puertoAnswerImage('#dropZone1', '#chooseFile1', '#thumbnails1', '#thumbnails1', false);
$.puertoAnswerImage('#dropZone2', '#chooseFile2', '#thumbnails2', '#thumbnails2', false);


/** Member Statistics **/

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl, {
	  container: 'body',
		html: true
	})
})



/** Members Actions **/
$.list_action('pl-members-trash', '', 'actions&type=members-trash');
$.list_action('pl-members-reject', '', 'actions&type=members-reject');
$.list_action('pl-members-approve', '', 'actions&type=members-approve');


/** Member Verified **/
$.list_action('pl-member-verified', 'pl-member-unverified', 'actions&type=members-verified');
$.list_action('pl-member-unverified', 'pl-member-verified', 'actions&type=members-unverified');

/** Member Ban **/
$.list_action('pl-member-ban', 'pl-member-unban', 'actions&type=members-ban');
$.list_action('pl-member-unban', 'pl-member-ban', 'actions&type=members-unban');

/** Member Admin/User **/
$.list_action('pl-member-admin', 'pl-member-user', 'actions&type=members-admin');
$.list_action('pl-member-user', 'pl-member-admin', 'actions&type=members-user');


/*
#-------------------------------------------------------------------------
# + 5) Pages
#-------------------------------------------------------------------------
*/

$.list_action('pl-page-trash', '', 'actions&type=page-trash');

/** Pages Send **/
$.puerto_send( "#pl-send-page", "send-page", "index.php?pg=cpanel&type=pages" );

/*
#-------------------------------------------------------------------------
# + 5) Categories
#-------------------------------------------------------------------------
*/


/** Category Trash **/
$.list_action('pl-category-trash', '', 'actions&type=category-trash');
$.list_action('pl-plan-trash', '', 'actions&type=plan-trash');

if($('#colorpicker-popup').length){
$("#colorpicker-popup").spectrum({
    color: ($('[name=pg_bg]').val() ? $('[name=pg_bg]').val() : "#f00"),
		showInput: true,
    allowEmpty:true,
		preferredFormat: "hex",
		change: function(rr){
			$('[name=pg_bg_v]').val(rr.toHexString());
			console.log(rr.toHexString());
		}
});
}


if($('.my').length){
$('.my').iconpicker({placement: 'bottom'});
}

/** Category Send **/
$.puerto_send( "#pl-send-category", "cpanel&type=send-category", "index.php?pg=cpanel&type=categories" );


/*
#-------------------------------------------------------------------------
# + 5) Questions
#-------------------------------------------------------------------------
*/


/** Question Trash **/
$.list_action('pl-questions-trash', '', 'actions&type=questions-trash');
$.list_action('pl-questions-reject', '', 'actions&type=questions-reject');
$.list_action('pl-questions-approve', '', 'actions&type=questions-approve');




/*
#-------------------------------------------------------------------------
# + 5) Reports
#-------------------------------------------------------------------------
*/

$(".pl-report-reply").livequery("click", function(){
	var id = $(this).attr("rel");
	$(".pl-number").text(id);
	$("[name=report_id]").val(id);
	$("#report-modal").modal('show');
});



/** Setting Send **/
$.puerto_send( "#pl-send-setting", "send-setting" );


$.puerto_send( "#pl-send-plans", "send-plan", "index.php?pg=cpanel&type=plans" );
$.puerto_send( "#pl-send-lang", "send-lang", "index.php?pg=cpanel&type=languages" );

$.list_action('pl-lang-trash', '', 'actions&type=lang-trash');



if($(".pt-adminstats").length){
	$.get(path+"/ajax.php?request=adminstats&pg=monthly", function(puerto) {
		var ass = JSON.parse(puerto);
		var DataLabelss = ass.labels;
		var DataCnts = ass.data;
		var DataTitle = ass.title;
		$.lineChart(DataLabelss,DataCnts,DataTitle);
		console.log(puerto);

	});

	$.get(path+"/ajax.php?request=adminstatsbars&pg=monthly", function(puerto) {
		var ass = JSON.parse(puerto);
		var DataLabelss = ass.labels;
		var DataCnts = ass.data;
		var DataTitle = ass.title;
		var DataClrs = ass.colors;

		$.barChart("bar-chart", DataLabelss, DataCnts, DataClrs, DataTitle);
		console.log(puerto);

	});

	$.get(path+"/ajax.php?request=adminstatspie&pg=monthly", function(puerto) {
		var ass = JSON.parse(puerto);
		var DataLabelss = ass.labels;
		var DataCnts = ass.data;
		var DataTitle = ass.title;
		var DataClrs = ass.colors;

		$.pieChart("pie-chart", DataLabelss, DataCnts, DataClrs, DataTitle)
		console.log(puerto);

	});

	$.get(path+"/ajax.php?request=adminstatshbars", function(puerto) {
		var ass = JSON.parse(puerto);
		var DataLabelss = ass.labels;
		var DataCnts = ass.data;
		var DataTitle = ass.title;
		var DataClrs = ass.colors;

		$.barChart("hbar-chart", DataLabelss, DataCnts, DataClrs, DataTitle, 'horizontalBar');
		console.log(puerto);

	});


	$(".pt-adminlines a").on("click", function(){
		var t = $(this).attr('href').replace('#','');
		var ids = $(this).attr('rel');
		$.get(path+"/ajax.php?request=adminstats&pg="+t, function(puerto) {
			var ass = JSON.parse(puerto);
			var DataLabelss = ass.labels;
			var DataCnts = ass.data;
			var DataTitle = ass.title;

			$.lineChart(DataLabelss,DataCnts, DataTitle);
			console.log(puerto);
		});
		return false;
	});


	$(".pt-adminbars a").on("click", function(){
		var t = $(this).attr('href').replace('#','');
		var ids = $(this).attr('rel');
		$.get(path+"/ajax.php?request=adminstatsbars&pg="+t, function(puerto) {
			var ass = JSON.parse(puerto);
			var DataLabelss = ass.labels;
			var DataCnts = ass.data;
			var DataTitle = ass.title;
			var DataClrs = ass.colors;

			$.barChart("bar-chart", DataLabelss, DataCnts, DataClrs, DataTitle);
			console.log(puerto);

		});
		return false;
	});
}

} ( jQuery ) )