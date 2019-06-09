/*
	Solid State by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function($) {

	var	$window = $(window),
		$body = $('body'),
		$header = $('#header'),
		$banner = $('#banner');

	// Breakpoints.
		breakpoints({
			xlarge:	'(max-width: 1680px)',
			large:	'(max-width: 1280px)',
			medium:	'(max-width: 980px)',
			small:	'(max-width: 736px)',
			xsmall:	'(max-width: 480px)'
		});

	// Play initial animations on page load.
		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-preload');
			}, 100);
		});

	// Header.
		if ($banner.length > 0
		&&	$header.hasClass('alt')) {

			$window.on('resize', function() { $window.trigger('scroll'); });

			$banner.scrollex({
				bottom:		$header.outerHeight(),
				terminate:	function() { $header.removeClass('alt'); },
				enter:		function() { $header.addClass('alt'); },
				leave:		function() { $header.removeClass('alt'); }
			});

		}

	// Menu.
		var $menu = $('#menu');

		$menu._locked = false;

		$menu._lock = function() {

			if ($menu._locked)
				return false;

			$menu._locked = true;

			window.setTimeout(function() {
				$menu._locked = false;
			}, 350);

			return true;

		};

		$menu._show = function() {

			if ($menu._lock())
				$body.addClass('is-menu-visible');

		};

		$menu._hide = function() {

			if ($menu._lock())
				$body.removeClass('is-menu-visible');

		};

		$menu._toggle = function() {

			if ($menu._lock())
				$body.toggleClass('is-menu-visible');

		};

		$menu
			.appendTo($body)
			.on('click', function(event) {

				event.stopPropagation();

				// Hide.
					$menu._hide();

			})
			.find('.inner')
				.on('click', '.close', function(event) {

					event.preventDefault();
					event.stopPropagation();
					event.stopImmediatePropagation();

					// Hide.
						$menu._hide();

				})
				.on('click', function(event) {
					event.stopPropagation();
				})
				.on('click', 'a', function(event) {

					var href = $(this).attr('href');

					event.preventDefault();
					event.stopPropagation();

					// Hide.
						$menu._hide();

					// Redirect.
						window.setTimeout(function() {
							window.location.href = href;
						}, 350);

				});

		$body
			.on('click', 'a[href="#menu"]', function(event) {

				event.stopPropagation();
				event.preventDefault();

				// Toggle.
					$menu._toggle();

			})
			.on('keydown', function(event) {

				// Hide on escape.
					if (event.keyCode == 27)
						$menu._hide();

			});

		$('.videolist_next').each(function () {
			$(this).click(function () {
				let button = $(this);
				let chanid = button.attr('chanid');
				let page = parseInt(button.attr('page'));
				let url = '/youtube_channel_videos/' + chanid + '/' + page;
				let prev_button = $("a[chanid='" + chanid +"'].videolist_prev");

				if (page==0) return;
				$.getJSON(url,function (response) {
					let json = JSON.parse(response);
					let index = 0;
					$('#videos_'+chanid+' section article').each(function() {
						let item = json[index];
						index++;
						let articleObj = $(this);
						articleObj.fadeTo("slow",0.1,function () {
							if (item == undefined) {
								articleObj.slideUp("slow");
								button.addClass('disabled');
								button.attr('page',0);
							} else {
								articleObj.children('a.image_feature').children('img').attr('src',item.thumb);
								articleObj.children('a.image_feature').children('img').attr('title',item.title);
								articleObj.children('h3').html(item.title);
								articleObj.children('a.special').attr('href','https://www.youtube.com/watch?v='+item.videoid);
								let publish = new Date (item.date_published);
								articleObj.children('p').html('Published on ' + publish.getDate() + '-' + publish.getMonth() + '-' + publish.getFullYear());
								button.attr('page',page+1);
								articleObj.fadeTo("slow",1);
							}
						});
						prev_button.attr('page',page-1);
						prev_button.removeClass('disabled');
					});
				});
			});
		});
	$('.videolist_prev').each(function () {
		$(this).click(function () {
			let button = $(this);
			let chanid = button.attr('chanid');
			let page = parseInt(button.attr('page'));
			let url = '/youtube_channel_videos/' + chanid + '/' + page;
			let next_button = $("a[chanid='" + chanid +"'].videolist_next");

			if (page==0) return;
			$.getJSON(url,function (response) {
				let json = JSON.parse(response);
				let index = 0;
				next_button.attr('page',page+1);
				next_button.removeClass('disabled');
				$('#videos_'+chanid+' section article').each(function() {
					let item = json[index];
					index++;
					let articleObj = $(this);
					let populate = function() {
						articleObj.children('a.image_feature').children('img').attr('src',item.thumb);
						articleObj.children('a.image_feature').children('img').attr('title',item.title);
						articleObj.children('h3').html(item.title);
						let publish = new Date (item.date_published);
						articleObj.children('p').html('Published on ' + publish.getDate() + '-' + publish.getMonth() + '-' + publish.getFullYear());
						articleObj.children('a.special').attr('href','https://www.youtube.com/watch?v='+item.videoid);
					};
					if (articleObj.is(':hidden')) {
						populate();
						articleObj.slideDown('slow').fadeTo('slow',1);
					} else {
						articleObj.fadeTo('slow',0.1,function () {
							populate();
							articleObj.fadeTo('slow',1);
						});
					}
					button.attr('page',page-1);
					if (page==1) {
						button.addClass('disabled');
					}
				});
			});
		});
	});

})(jQuery);