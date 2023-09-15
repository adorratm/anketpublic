(function ($) {
	const tooltipTriggerList = document.querySelectorAll(
		'[data-bs-toggle="tooltip"]'
	);
	const tooltipList = [...tooltipTriggerList].map(
		(tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
	);
	'use strict';


	/**
	 * * 1) Functions
	 * * 2) Header
	 * * 3) File Upload
	 * * 4) Sidebar
	 * * 5) Members
	 * * 6) Voting
	 * * 7) Questions
	 * * 8) ASK
	 * * 9) Comments
	 * * 10) Notifications
	 */


	/*
	#-------------------------------------------------------------------------
	# + 1) Functions
	#-------------------------------------------------------------------------
	*/


	$(window).scroll(function () {
		if ($(window).scrollTop() >= 230) {
			$('.pl-new-header').addClass('fixed');
		}
		else {
			$('.pl-new-header').removeClass('fixed');
		}
	});



	$(document).on('click', ".pt-newmobilebtn a", function () {
		var ul = $(".pt-newmobilemenu");
		if (ul.hasClass('open')) {
			ul.removeClass('open');
			$(this).removeClass('active');
		} else {
			ul.addClass('open');
			$(this).addClass('active');
		}

		return false;
	});

	$.puerto_droped = function (prtclick, prtlist = "ul.dropdown") {
		$(prtclick).livequery('click', function () {
			var ul = $(this).parent();
			if (ul.find(prtlist).hasClass('open')) {
				ul.find(prtlist).removeClass('open');
				$(this).removeClass('active');
				if (prtclick == ".pl-mobile-menu") $('body').removeClass('active');
			} else {
				ul.find(prtlist).addClass('open');
				$(this).addClass('active');
				if (prtclick == ".pl-mobile-menu") $('body').addClass('active');
			}
			return false;
		});
		$("html, body").livequery('click', function () {
			$(prtclick).parent().find(prtlist).removeClass('open');
			$(prtclick).removeClass('active');
			if (prtclick == ".pl-mobile-menu") $('body').removeClass('active');
		});
	}

	$.puerto_droped2 = function (prtclick, prtlist = "ul.dropdown") {
		$(prtclick).livequery('click', function () {
			var ul = $(this);
			if ($(ul.next("ul.dropdown")).hasClass('open')) {
				$(ul.next("ul.dropdown")).removeClass('open');
				$(this).removeClass('active');
				if (prtclick == ".pl-mobile-menu") $('body').removeClass('active');
			} else {
				$(ul.next("ul.dropdown")).addClass('open');
				$(this).addClass('active');
				if (prtclick == ".pl-mobile-menu") $('body').addClass('active');
			}
			return false;
		});
		$("html, body").livequery('click', function () {
			$(prtclick).parent().find(prtlist).removeClass('open');
			$(prtclick).removeClass('active');
			if (prtclick == ".pl-mobile-menu") $('body').removeClass('active');
		});
	}

	$.puerto_timer = function (counterId) {
		$('.' + counterId).each(function () {
			var $this = $(this),
				countTo = $this.attr('data-count');

			$({ countNum: $this.text() }).animate({
				countNum: countTo
			},
				{
					duration: 2000,
					easing: 'linear',
					step: function () {
						$this.text(Math.floor(this.countNum) + '%');
					},
					complete: function () {
						$this.text(this.countNum + '%');
					}
				});
		});
	};

	$.puerto_alert = function (type, text) {
		var n = noty({
			text: text,
			type: type,
			dismissQueue: true,
			theme: false,
			closeWith: ['button', 'click'],
			maxVisible: 20,
			timeout: 4000,
			animation: {
				open: 'animated bounceInRight',
				close: 'animated bounceOutRight',
				easing: 'swing',
				speed: 200
			}
		});
	}

	$.list_action_rp = function (ths, clss, id, fi, txt = false) {
		return ths.replaceWith('<a href="javascript: void(0)" class="' + clss + '" rel="' + id + '"><i class="' + fi + '"></i>' + txt + '</a>');
	}

	$.list_action = function (listID, unlistID, listURL, listCOUNT = false, plus = true) {
		$('.' + listID).livequery("click", function () {
			if (actions == "1") {
				var id = $(this).attr("rel");
				var multi = $(this).attr("data-multi");
				var $ths = $(this);
				var $text = $ths.text();
				var $html = $ths.html();
				var $count = (listCOUNT) ? $ths.parent().parent().parent().parent().find('.' + listCOUNT) : '';
				var multi_ids = [];

				$ths.html('<i class="fas fa-spinner fa-pulse fa-fw"></i> ' + "Yükleniyor")
					.css('cursor', 'not-allowed')
					.removeClass(listID);

				if (multi == 'true') {
					if ($('[name="pl-check[]"]:checked').length) {
						$('[name="pl-check[]"]:checked').each(function () {
							multi_ids.push($(this).val());
						});
					}
				}
			} else {
				$("#sign-modal").modal('show');
			}
		});
	}

	$(".pt-plan form").on("submit", function () {
		if (actions == 0) {
			$("#sign-modal").modal('show');
			return false;
		}
		else {
			return $(this).submit();
		}
	});


	$("li, ul").prev("br").remove();
	$("li, ul").next("br").remove();


	/*
	#-------------------------------------------------------------------------
	# + 2) Header
	#-------------------------------------------------------------------------
	*/

	/** Search **/
	if ($(".search").length) {
		$(document).ready(function () {
			$("input.search").blur();
		});
	}
	if ($(".pt-scroll").length) {
		$('.pt-scroll').scrollbar();
	}

	/** Notifications **/
	$.puerto_droped(".pl-notifications-show");

	/** Categories **/
	$.puerto_droped2(".cats-links i");

	/** User Details **/
	$.puerto_droped(".show-user-details");

	/** Lang **/
	$.puerto_droped(".pt-show-lang");

	/** Forget Modal **/
	$('.forget-modal').livequery('click', function () {
		$('#sign-modal').modal('hide');
		$('#forget-modal').modal('show');
	});

	/** Logout **/

	/** Language Change **/
	$('.pl-lang a').livequery("click", function () {
		var $th = $(this);
		var $id = $th.attr('rel');
		var $ht = $th.html();
		$th.html('<i class="fas fa-spinner fa-pulse fa-fw"></i>');


		return false;
	});


	/*
	#-------------------------------------------------------------------------
	# + 3) File Upload
	#-------------------------------------------------------------------------
	*/


	/*
	#-------------------------------------------------------------------------
	# + 4) Sidebar
	#-------------------------------------------------------------------------
	*/


	/** Questions **/
	$.puerto_droped(".questions-filter-link");


	/*
	#-------------------------------------------------------------------------
	# + 5) Members
	#-------------------------------------------------------------------------
	*/

	/** Profile options **/
	$.puerto_droped('.pl-user-options');

	/** Small Desc: Letters Count  **/
	$('.pl-small-desk small').html(50 + ' letter left');

	/** Small Desc: Letters Keyup Count  **/
	$('.pl-small-desk textarea').keyup(function () {
		var text_length = $(this).val().length;
		var text_remaining = 50 - text_length;
		if (text_length <= 50)
			$('.pl-small-desk small').html(text_remaining + ' letter left');
		else
			$('.pl-small-desk small').html('<span class="red">' + text_remaining + ' more than you need</span>');
	});

	if ($(".datepicker").length) {
		$('#datepicker').datepicker();
	}


	/*
	#-------------------------------------------------------------------------
	# + Voting
	#-------------------------------------------------------------------------
	*/

	/*
	#-------------------------------------------------------------------------
	# + Questions
	#-------------------------------------------------------------------------
	*/

	/** Question Share Button **/

	$.puerto_droped('.pl-share-button');

	function windowPopup(url, width, height) {
		var left = (screen.width / 2) - (width / 2),
			top = (screen.height / 2) - (height / 2);
		window.open(url, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left);
	}

	$(".puerto-popup").on("click", function (e) {
		e.preventDefault();
		windowPopup($(this).attr("href"), 500, 300);
	});

	/** Question Follow Button **/

	/** Question Unfollow Button **/


	/** Embed Form **/
	$('.pl-show-embed').livequery("click", function () {
		var $ths = $(this);
		var $height = $ths.parent().parent().parent().parent().parent().parent().parent().parent();
		var $form = $ths.parent().parent().parent().parent().parent().parent().parent().find('.pt-embed-form');
		console.log($height.height());
		$form.find('pre').text(function () {
			return $(this).text().replace('315', ($height.height() + 23)).replace('460px', '100%');
		});
		$form.show();
		return false;
	});

	$('.pl-hide-embed').livequery("click", function () {
		var $ths = $(this);
		$ths.parent().hide();
		return false;
	});


	/** Question Follow Button (Send as List) **/

	/** Question Trash **/
	$.list_action('pl-question-trash', '', 'actions&type=question-trash', '');



	/** Question Repports **/
	$("[data-bs-target='#report-modal']").livequery("click", function () {
		if (actions == 1) {
			var id = $(this).attr("rel");
			$("[name=report_id]").val(id);
		} else {
			$("#sign-modal").modal('show');
		}
	});

	/** Stats **/
	$('.stats-download').livequery('click', function () {

		var title = $(this).data("name");
		var element = document.getElementById('root');


		// Choose pagebreak options based on mode.
		var mode = 'avoid-all';
		var pagebreak = (mode === 'specify') ?
			{ mode: '', before: '.before', after: '.after', avoid: '.avoid' } :
			{ mode: mode };

		// Generate the PDF.
		html2pdf().from(element).set({
			filename: title + '.pdf',
			margin: 1,
			pagebreak: 'legacy',
			jsPDF: { orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true }
		}).save();

		return false;
	});



	//- Rapport Stats

	$.barChart = function (ChartID, DataLabelss, DataCnts, DataClrs, DataTitle, DataType = 'bar') {
		new Chart(document.getElementById(ChartID), {
			type: DataType, //horizontalBar
			data: {
				labels: DataLabelss,
				datasets: [
					{
						label: DataTitle,
						backgroundColor: DataClrs,
						data: DataCnts
					}
				]
			},
			options: {
				legend: { display: false },
				title: {
					display: (DataTitle ? true : false),
					text: DataTitle
				},
				scales: {
					xAxes: [{
						ticks: { beginAtZero: true }
					}]
				}
			}
		});
	}

	$.lineChart = function (DataLabelss, DataCnts, DataTitle) {
		new Chart(document.getElementById("line-chart"), {
			type: 'line',
			data: {
				labels: DataLabelss,
				datasets: [{
					data: DataCnts,
					label: false,
					borderColor: "#5f90fa",
					backgroundColor: 'rgba(95, 144, 250, 0.65)'
				}
				]
			},
			options: {
				legend: {
					display: false
				},
				title: {
					display: (DataTitle ? true : false),
					text: DataTitle
				},
				scales: {
					xAxes: [{
						ticks: {
							autoSkip: false,
							maxRotation: 40,
							minRotation: 40
						}
					}]
				}
			}
		});
	}

	$.pieChart = function (DataId, DataLabels, DataCnt, DataClrs, DataTitle) {
		new Chart(document.getElementById(DataId), {
			type: 'doughnut',
			data: {
				labels: DataLabels,
				datasets: [
					{
						label: "Partisipate of",
						backgroundColor: DataClrs,
						data: DataCnt
					}
				]
			},
			options: {
				legend: { display: true },
				title: {
					display: (DataTitle ? true : false),
					text: DataTitle
				}
			}
		});
	}

	/*
	#-------------------------------------------------------------------------
	# + Ask
	#-------------------------------------------------------------------------
	*/

	/** Change answers by question type **/
	$('[name=qs_type]').livequery('change', function () {
		var val = $(this).val();
		if (val == 1) {
			$('#poll-type-append').html('<p></p><label class="pl-answer-label">' +
				'' + "Bu ney amk" + ' <small class="pl-add-answer">(' + "Bu ney amk" + ')</small> <b class="red">*</b>' +
				'<input type="text" name="qs_answers[]" placeholder="' + "Bu ney amk" + '">' +
				'<input type="text" name="qs_answers[]" placeholder="' + "Bu ney amk" + '">' +
				'</label>');
		} else if (val == 2) {
			$('#poll-type-append').html('');
		} else {
			$('#poll-type-append').html('<p></p>' +
				'<div class="pl-row">' +
				'<div class="pl-col-8">' +
				'<label class="pl-answer-label">' +
				'' + "Bu ney amk" + ' <small class="pl-add-answer-image">(' + "Bu ney amk" + ')</small> <b class="red">*</b>' +
				'<input type="text" name="qs_answers[]" placeholder="' + "Bu ney amk" + '">' +
				'</label>' +
				'</div>' +
				'<div class="pl-col-4">' +
				'<label class="pl-answer-label">' +
				'' + "Bu ney amk" + ' <b class="red">*</b>' +
				'<div class="pl-select">' +
				'<select name="qs_answers_images[]"' + ($('[name=qs_thumb]').hasClass('disabled') ? ' class="disabled pl-select-append" disabled' : '') + '>' +
				$('[name=qs_thumb]').html() +
				'</select>' +
				'</div>' +
				'</label>' +
				'</div>' +
				'</div>');
		}
	})

	/** Add New answer field **/
	$(".pl-add-answer").livequery('click', function () {
		if ($('input[name="qs_answers[]"]').length < 8) {
			$("#poll-type-append").append('<div class="pl-answer-inp"><input type="text" name="qs_answers[]" placeholder="' + "Bu ney amk" + '"><a class="pl-buttons pl-icon bg-8 pl-close-inp"><i class="fas fa-times"></i></a></div>');
		}
	});

	$(".pl-close-inp").livequery('click', function () {
		if (confirm('Are you sure you want to take this action?'))
			$(this).parent().remove();
	});

	/** Add New answer field with image **/
	$(".pl-add-answer-image").livequery('click', function () {
		if ($('input[name="qs_answers[]"]').length < 9) {
			$("#poll-type-append").append('<div class="pl-answer-inp">' +
				'<div class="pl-row">' +
				'<div class="pl-col-8">' +
				'<label class="pl-answer-label pl-answer-inp">' +
				'<input type="text" name="qs_answers[]" placeholder="' + "Bu ney amk" + '">' +
				'<a class="pl-buttons pl-icon bg-8 pl-close-inpImg"><i class="fas fa-times"></i></a>' +
				'</label>' +
				'</div>' +
				'<div class="pl-col-4">' +
				'<label class="pl-answer-label">' +
				'<div class="pl-select">' +
				'<select name="qs_answers_images[]"' + ($('[name=qs_thumb]').hasClass('disabled') ? ' class="disabled" disabled' : '') + '>' +
				$('[name=qs_thumb]').html() +
				'</select>' +
				'</div>' +
				'</label>' +
				'</div>' +
				'</div>' +
				'</div>');
		}
	});

	$(".pl-close-inpImg").livequery('click', function () {
		if (confirm('Are you sure you want to take this action?'))
			$(this).parent().parent().parent().parent().remove();
	});

	$("[name=qs_multiple]").on("change", function () {
		if ($(this).is(":checked")) {
			$("#qs_type_2").prop("disabled", true);
		} else {
			$("#qs_type_2").prop("disabled", false);
		}
	});

	/*
	#-------------------------------------------------------------------------
	# + Comments
	#-------------------------------------------------------------------------
	*/

	/** View More Comments **/


	/** Comment form Toggle **/

	$(".pl-write-comment textarea").livequery("click", function () {
		$(this).parent().parent().parent().parent().addClass('pl-active');
		return false;
	});

	$(".pl-write-comment .cancel").livequery("click", function () {
		$(this).parent().parent().parent().removeClass('pl-active');
		return false;
	});


	/*
	#-------------------------------------------------------------------------
	# + Notifications
	#-------------------------------------------------------------------------
	*/


	$('.pl-notifications ul').livequery('click', function (e) {
		e.preventDefault();
		return false;
	});

	$.list_action('pl-read-all-noty', '', 'actions&type=read-all-noty', 'pl-notifications-show small');

	if ($('.jscroll').length) {
		$('.jscroll').jscroll({
			loadingHtml: '<span class="pl-more"><i class="fas fa-spinner fa-pulse fa-fw"></i> ' + "Yükleniyor" + '</span>',
			padding: 20,
			nextSelector: 'a.jscroll-next:last',
			contentSelector: 'li'
		});
	}

	if ($('.jscroll-polls').length) {
		$('.jscroll-polls').jscroll({
			loadingHtml: '<span class="pl-more"><i class="fas fa-spinner fa-pulse fa-fw"></i> ' + "Yükleniyor" + '</span>',
			padding: 20,
			autoTrigger: false,
			nextSelector: 'span.jscroll-next-polls:last',
			contentSelector: '.lishows'
		});
	}









	if ($('#zero').length) {

		anime({
			targets: '.row svg',
			translateY: 10,
			autoplay: true,
			loop: true,
			easing: 'easeInOutSine',
			direction: 'alternate'
		});

		anime({
			targets: '#zero',
			translateX: 10,
			autoplay: true,
			loop: true,
			easing: 'easeInOutSine',
			direction: 'alternate',
			scale: [{ value: 1 }, { value: 1.4 }, { value: 1, delay: 250 }],
			rotateY: { value: '+=180', delay: 200 },
		});

	}




	if ($('#masonry').length) {

		let msnry = new Masonry('#masonry', {
			// Masonry options...
			itemSelector: '.question_id',
		});

		// init Infinite Scroll
		let infScroll = new InfiniteScroll('#masonry', {
			// Infinite Scroll options...
			path: '.jscroll-next-polls',
			append: '.question_id',
			outlayer: msnry,
			history: false
		});

	}





}(jQuery))
