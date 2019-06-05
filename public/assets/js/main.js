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
				var button = $(this);
				var chanid = button.attr('chanid');
				var videodiv = $('#videos_'+chanid);
				var page = parseInt(button.attr('page'));
				let url = '/youtube_channel_videos/' + chanid + '/' + page;
				if (page==0) return;
				$.get(url,function (response) {
					if (response.length>100) {
						let selector = "a[chanid='" + chanid +"'].videolist_prev";
						let prev_button = $(selector);
						videodiv.fadeTo("slow",0.1, function () {
							videodiv.html(response).fadeTo("slow",1);
						});
						prev_button.attr('page',page-1);
						prev_button.removeClass('disabled');
						button.attr('page',page + 1);
					} else {
						button.attr('page',0)
						button.addClass('disabled');
					}
				});
			});
		});
		$('.videolist_prev').each(function () {
			$(this).click(function () {
				var button = $(this);
				var chanid = button.attr('chanid');
				var videodiv = $('#videos_'+chanid);
				var page = parseInt(button.attr('page'));
				let url = '/youtube_channel_videos/' + chanid + '/' + page;
				if (page==0) return;
				$.get(url,function (response) {
					let selector = "a[chanid='" + chanid +"'].videolist_next";
					let next_button = $(selector);
					videodiv.fadeTo("slow",0.1, function () {
						videodiv.html(response).fadeTo("slow",1);
					});
					next_button.attr('page',page+1);
					next_button.removeClass('disabled');
					button.attr('page',page-1);
					if (page==1) {
						button.attr('page',0);
						button.addClass('disabled');
					}
				});
			});
		});


})(jQuery);