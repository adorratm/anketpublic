/** *************Init JS*********************
	
	TABLE OF CONTENTS
	---------------------------
	1.Ready function
	2.Load function
	3.Full height function
	4.pinkman function
	5.Chat App function
	6.Resize function
 ** ***************************************/

"use strict";


/*****Ready function start*****/
$(document).ready(function () {
	

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
	pinkman();
	/*Disabled*/
	$(document).on("click", "a.disabled,a:disabled", function (e) {
		return false;
	});
});
/*****Ready function end*****/

/*****Load function start*****/
$(window).on("load", function () {
	$(".preloader-it").delay(500).fadeOut("slow");
});
/*****Load function* end*****/

/*Variables*/
var height, width,
	$wrapper = $(".hk-wrapper"),
	$nav = $(".hk-nav"),
	$vertnaltNav = $(".hk-wrapper.hk-vertical-nav,.hk-wrapper.hk-alt-nav"),
	$horizontalNav = $(".hk-wrapper.hk-horizontal-nav"),
	$navbar = $(".hk-navbar");

/***** pinkman function start *****/
var pinkman = function () {

	/*Navbar Collapse Animation*/
	var navbarNavCollapse = $('.hk-nav .navbar-nav  li');
	var navbarNavAnchor = '.hk-nav .navbar-nav  li a';
	$(document).on("click", navbarNavAnchor, function (e) {
		if ($(this).attr('aria-expanded') === "false")
			$(this).blur();
		$(this).parent().siblings().find('.collapse').collapse('hide');
		$(this).parent().find('.collapse').collapse('hide');
	});

	/*Card Remove*/
	$(document).on('click', '.card-close', function (e) {
		var effect = $(this).data('effect');
		$(this).closest('.card')[effect]();
		return false;
	});

	/*Accordion js*/
	$(document).on('show.bs.collapse', '.accordion .collapse', function (e) {
		$(this).siblings('.card-header').addClass('activestate');
	});

	$(document).on('hide.bs.collapse', '.accordion .collapse', function (e) {
		$(this).siblings('.card-header').removeClass('activestate');
	});

	/*Navbar Toggle*/
	$(document).on('click', '#navbar_toggle_btn', function (e) {
		$wrapper.toggleClass('hk-nav-toggle');
		$(window).trigger("resize");
		return false;
	});
	$(document).on('click', '#hk_nav_backdrop,#hk_nav_close', function (e) {
		$wrapper.removeClass('hk-nav-toggle');
		return false;
	});

	/*Settings panel Toggle*/
	$(document).on('click', '#settings_toggle_btn', function (e) {
		$wrapper.toggleClass('hk-settings-toggle');
		return false;
	});
	$(document).on('click', '#settings_panel_close', function (e) {
		$wrapper.removeClass('hk-settings-toggle');
		return false;
	});
	$(document).on('click', '#nav_light_select', function (e) {
		$nav.removeClass('hk-nav-dark').addClass('hk-nav-light');
		return false;
	});
	$(document).on('click', '#nav_dark_select', function (e) {
		$nav.removeClass('hk-nav-light').addClass('hk-nav-dark');
		return false;
	});
	$(document).on('click', '#nav_light_select,#nav_dark_select', function (e) {
		$('.hk-nav-select').find('.btn').removeClass('btn-outline-primary').addClass('btn-outline-light');
		$(this).removeClass('btn-outline-light').addClass('btn-outline-primary').blur();
		return false;
	});
	$(document).on('click', '#navtop_light_select,#navtop_dark_select', function (e) {
		$('.hk-navbar-select').find('.btn').removeClass('btn-outline-primary').addClass('btn-outline-light');
		$(this).removeClass('btn-outline-light').addClass('btn-outline-primary').blur();
		return false;
	});
	$(document).on('click', '#navtop_light_select', function (e) {
		$navbar.removeClass('navbar-dark').addClass('navbar-light').find('img.brand-img').attr('src', 'vendor/img/logo-light.png');
		return false;
	});
	$(document).on('click', '#navtop_dark_select', function (e) {
		$navbar.removeClass('navbar-light').addClass('navbar-dark').find('img.brand-img').attr('src', 'vendor/img/logo-dark.png');
		return false;
	});

	/*Search form Collapse*/
	$(document).on('click', '#navbar_search_btn', function (e) {
		$('html,body').animate({ scrollTop: 0 }, 'slow');
		$(".navbar-search input").focus();
		$wrapper.addClass('navbar-search-toggle');
		$(window).trigger("resize");
	});
	$(document).on('click', '#navbar_search_close', function (e) {
		$wrapper.removeClass('navbar-search-toggle');
		$(window).trigger("resize");
		return false;
	});

	/*Refresh Init Js*/
	var refreshMe = '.refresh';
	$(document).on("click", refreshMe, function (e) {
		var panelToRefresh = $(this).closest('.card').find('.refresh-container');
		var dataToRefresh = $(this).closest('.card').find('.panel-wrapper');
		var loadingAnim = panelToRefresh.find('.la-anim-1');
		panelToRefresh.show();
		setTimeout(function () {
			loadingAnim.addClass('la-animate');
		}, 100);
		function started() { } //function before timeout
		setTimeout(function () {
			function completed() { } //function after timeout
			panelToRefresh.fadeOut(800);
			setTimeout(function () {
				loadingAnim.removeClass('la-animate');
			}, 800);
		}, 1500);
		return false;
	});

	/*Fullscreen Init Js*/
	$(document).on("click", ".full-screen", function (e) {
		$(this).parents('.card').toggleClass('fullscreen');
		$(window).trigger("resize");
		return false;
	});

};
/***** pinkman function end *****/

/***** Full height function start *****/
var setHeightWidth = function () {
	height = window.innerHeight;
	width = window.innerWidth;
	$('.full-height').css('height', (height));
	$('.hk-pg-wrapper').css('min-height', (height));

};
/***** Full height function end *****/

/***** Resize function start *****/
$(window).on("resize", function () {
	setHeightWidth();
});
$(window).trigger("resize");
/***** Resize function end *****/

/** TinyMCE */
function TinyMCEInit(base_url = null,height = 400, fullpage = false, selector = '.tinymce') {
    /* TinyMCE */
    if ($("textarea" + selector).length <= 0) {
        return false;
    }
    tinymce.remove();
    tinymce.init({
        selector: selector,
        entity_encoding: (fullpage ? "''" : "'raw'"),
        paste_auto_cleanup_on_paste: true,
        language: 'tr_TR', // select language
        language_url: 'https://cdn.jsdelivr.net/npm/tinymce-lang/langs/tr_TR.js',
        branding: false,
        image_advtab: true,
		resize:true,
        promotion: false,
        plugins: (fullpage ? "fullpage " : "") + 'advlist anchor autolink autoresize charmap code codesample directionality emoticons fullscreen image importcss insertdatetime link lists media nonbreaking pagebreak preview quickbars save searchreplace table visualblocks visualchars wordcount',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        height: height,
		min_height:height,
        mobile: {
            theme: 'silver'
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
				editor.getElement().dispatchEvent(new Event('input'));
            });
        },
        // without images_upload_url set, Upload tab won't show up
        images_upload_url: base_url + 'settings/uploadimage',
        convert_urls: false,
        // override default upload handler to simulate successful upload
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', base_url + 'settings/uploadimage');
			const authToken = localStorage.getItem('auth._token.admin');
			xhr.setRequestHeader("Authorization", authToken);
            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = () => {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }),
    });
    /* TinyMCE */
}
/** TinyMCE */