/* Lazyvideo v1.0.0 Copyright 2014 Lazy Bear Creations */
(function( $, window, document, undefined ) {
	
	"use strict";
	
	function LazyVideo(node, options) {
		this.node = node;
		this.$node = $(node);
		this.options = $.extend({}, LazyVideo._defaults, options);
		this.options.fullscreen = ((document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled) && this.options.fullscreen);
		this.init();
	}

	LazyVideo._defaults = {
		fade : true,
		fullscreen : true,
		rewind : false,
		forward : false,
		timer : false,
		volume : true,
		draggable : true,
		skipRate : 0.5,
		progress : true
	}

	LazyVideo.prototype.init = function() {
		var self = this;
		self.volume = self.node.volume;
		self.build();
	}

	LazyVideo.prototype.build = function () {

		var self = this;
		var id = "lazyvideo-player-" + Math.floor((Math.random()*100000000)+1);

		self.container = $(document.createElement('div')).attr('id', id).addClass('lazyvideo-player ready')

		if (self.$node.attr('width')) { self.container.width(self.$node.attr('width')); }
		if (self.$node.attr('height')) { self.container.height(self.$node.attr('height')); }
		
		self.$node.wrapAll(self.container);
		self.container = $('#' + id);

		self.overlay = $(document.createElement('div')).addClass('overlay');
		self.$node.after(self.overlay);

		self.loading = $(document.createElement('div')).addClass('loading fa fa-spinner fa-spin');
		self.play_me = $(document.createElement('div')).addClass('play-me fa fa-play pointer').hide();
		self.overlay.append(self.loading);
		self.overlay.append(self.play_me);

		if (self.node.networkState === 3) {
			self.show_error("Error retrieving " + self.node.src);
			return false;
		}
		if (!self.$node.attr('controls')) { self.build_controls(); }

	}

	LazyVideo.prototype.build_controls = function () {

		var self = this;

		self.controls = {};
		self.controls.$node = $(document.createElement('div')).addClass('controls').hide();
		self.$node.after(self.controls.$node);

		if (self.options.progress) {
			self.controls.video_progress = $(document.createElement('div')).addClass('control video-progress');
			self.controls.video_tracker = $(document.createElement('div')).addClass('tracker');
			self.controls.video_progress.html(self.controls.video_tracker);
			self.controls.$node.append(self.controls.video_progress);
		}

		if (self.options.rewind) {
			self.controls.rewind = $(document.createElement('div')).addClass('control btn btn-backward fa fa-backward');
			self.controls.$node.append(self.controls.rewind);
		}
		
		self.controls.play = $(document.createElement('div')).addClass('control btn btn-play fa fa-play');
		self.controls.$node.append(self.controls.play);
		
		if (self.options.forward) {
			self.controls.forward = $(document.createElement('div')).addClass('control btn btn-forward fa fa-forward');
			self.controls.$node.append(self.controls.forward);
		}

		if (self.options.fullscreen) {
			self.controls.expand = $(document.createElement('div')).addClass('control btn btn-fullscreen fa fa-expand');
			self.controls.$node.append(self.controls.expand);
		}

		if (self.options.timer) {
			self.controls.timer = $(document.createElement('div')).addClass('control fa timer').html("00:00");
			self.controls.$node.append(self.controls.timer);
		}

		if (self.options.volume) {
			self.controls.volume_btn = $(document.createElement('div')).addClass('control btn btn-volume fa fa-volume-up');
			self.controls.$node.append(self.controls.volume_btn);

			self.controls.volume_bar = $(document.createElement('div')).addClass('control volume-bar');
			self.controls.volume_tracker = $(document.createElement('div')).addClass('tracker');
			self.controls.volume_bar.html(self.controls.volume_tracker);

			self.controls.$node.append(self.controls.volume_bar);

		}

		self.events();
		if (self.options.fade) {  self.fade_controls(); }
	}

	LazyVideo.prototype.fade_controls = function () {
		var self = this;
		clearTimeout(self.controls.fade);
		if (self.container.hasClass('ready')) {
			return false;
		} else {
			return self.controls.fade = setTimeout(function() {
				self.controls.$node.fadeOut();
			}, 1500);
		}
	}

	LazyVideo.prototype.fullscreen = function () {
		var self = this;
		var fullscreen = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement;
		if (self.options.fullscreen && !fullscreen) {
			if (self.node.requestFullScreen) { self.node.requestFullScreen(); }
			else if (self.node.mozRequestFullScreen) { self.node.mozRequestFullScreen(); }
			else if (self.node.webkitRequestFullScreen) { self.node.webkitRequestFullScreen(); }
			self.container.addClass('fullscreen');
		} else {
			if (document.exitFullscreen) { document.exitFullscreen(); }
			else if (document.mozCancelFullScreen) { document.mozCancelFullScreen(); }
			else if (document.webkitCancelFullScreen) { document.webkitCancelFullScreen(); }
			self.container.removeClass('fullscreen');
		}
		return true;
	}

	LazyVideo.prototype.rewind = function () {
		var self = this;
		return setInterval( function() {
			self.jump(self.node.currentTime - self.options.skipRate);
		}, 1000 / 10);
	}

	LazyVideo.prototype.forward = function () {
		var self = this;
		return setInterval( function() {
			self.jump(self.node.currentTime + self.options.skipRate);
		}, 1000 / 10);
	}

	LazyVideo.prototype.jump = function (time) { this.node.currentTime = time; }
	LazyVideo.prototype.events = function () {

		var self = this;

		var controls = function() {
			self.$node.add(self.controls.$node).off("mousemove");
			self.$node.on("mousemove", function() {
				clearTimeout(self.controls.fade);
				self.controls.$node.fadeIn();
				if (self.options.fade) { self.fade_controls(); }
			});
		}

		self.overlay.click(function() {
			if (self.play_me.is(':visible')) {
				self.node.play();
				$(this).fadeOut();
				self.controls.$node.fadeIn();
				self.play_me.hide();
				self.loading.show();

				controls();

				if (self.options.fade) {

					self.controls.$node.on("mouseenter", function() {
						clearTimeout(self.controls.fade);
						self.controls.$node.fadeIn();
					});
					self.$node.add(self.controls.$node).on("mouseleave", function() {
						clearTimeout(self.controls.fade);
						self.fade_controls();
					});

				}
			}
		});

		self.$node.on("canplay", function() {
			self.loading.hide();
			self.play_me.show();
		});

		self.$node.on("seeking", function() { self.overlay.fadeIn(); });
		self.$node.on("seeked", function() { self.overlay.fadeOut(); });
		self.$node.on("error", function() {
			self.show_error("The media type is not supported by this browser");
			return false;
		});

		self.$node.on("timeupdate", function(e) {
			if (self.options.timer) {
				var minutes = Math.floor(self.node.currentTime / 60).toString();
				var seconds = Math.floor(self.node.currentTime - (60 * minutes)).toString();
					minutes = (minutes.length < 2) ? "0" + minutes : minutes ;
					seconds = (seconds.length < 2) ? "0" + seconds : seconds ;
				self.timer = (self.node.currentTime > 60) ? minutes : 0 ;
				self.timer += ":" + seconds;
				self.controls.timer.html(self.timer);
			}

			var percent = (self.node.currentTime / self.node.duration) * 100;
			self.controls.video_tracker.css({'width' : percent + '%'});
		});
		
		self.controls.play.on("click", function() {
			if ($(this).hasClass('fa-play')) { self.node.play(); }
			else { self.node.pause(); }
		});

		self.$node.on("play", function() {
			self.playing = true;
			self.controls.play.removeClass('fa-play').addClass('fa-pause');
			self.container.attr('class', 'lazyvideo-player playing');
		});

		self.$node.on("pause", function() {
			self.playing = false;
			self.controls.play.removeClass('fa-pause').addClass('fa-play');
			self.container.attr('class', 'lazyvideo-player played');
		});

		self.controls.volume_btn.on("click", function() {
			if (self.node.volume > 0) { self.node.volume = 0; }
			else if (self.volume === 0) { self.node.volume = 1; }
			else  { self.node.volume = self.volume; }
		});

		self.controls.volume_bar.add(self.controls.volume_tracker).on("mousedown", function(down) {
			var position = down.pageX - self.controls.volume_bar.offset().left;
			var percent = (position / self.controls.volume_bar.width()) * 100;
			var volume = percent / 100;
			self.node.volume = self.volume = volume;
			self.$node.add(self.controls.$node).on("mousemove", function(move) {
				var new_volume = ((move.pageX - down.pageX) / 100) + volume;
				if (new_volume < 0) { self.node.volume = self.volume = 0; }
				else if (new_volume > 1) { self.node.volume = self.volume = 1; }
				else { self.node.volume = self.volume = new_volume; }
				return false;
			});
		});

		self.$node.add(self.controls.$node).on("mouseup", function() { controls(); });

		self.$node.on("volumechange", function() {
			var percent = self.node.volume * 100;
			self.controls.volume_tracker.css({'width' : percent + '%'});
			if (self.node.volume > 0) { self.controls.volume_btn.attr('class', 'control btn btn-volume fa fa-volume-down'); }
			else { self.controls.volume_btn.attr('class', 'control btn btn-volume fa fa-volume-off'); }
			if (self.node.volume > 0.5) { self.controls.volume_btn.attr('class', 'control btn btn-volume fa fa-volume-up'); }
		});

		$(document).on("fullscreenchange mozfullscreenchange webkitfullscreenchange", function(e) {
			var fullscreen = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement;
			if (self.options.fullscreen && fullscreen) { self.controls.expand.removeClass('fa-expand').addClass('fa-compress'); }
			else { self.controls.expand.removeClass('fa-compress').addClass('fa-expand'); }
		});

		if (self.options.fullscreen) { self.controls.expand.on("click", function() { self.fullscreen(); }); }

		if (self.options.rewind) {
			self.controls.rewind.on("mousedown", function() {
				self.jump(self.node.currentTime - self.options.skipRate);
				self.seeker = self.rewind();
			});
			self.controls.rewind.on("mouseup mouseleave", function() {
				clearInterval(self.seeker);
			});
		}

		if (self.options.forward) {
			self.controls.forward.on("mousedown", function() {
				self.jump(self.node.currentTime + self.options.skipRate);
				self.seeker = self.forward();
			});
			self.controls.forward.on("mouseup mouseleave", function() {
				clearInterval(self.seeker);
			});
		}

		self.controls.video_progress.add(self.controls.video_tracker).on("mousedown", function(e) {
			
			var position = e.pageX - self.controls.video_progress.offset().left;
			var percent = (position / self.controls.video_progress.width()) * 100;
			self.controls.video_tracker.css({'width' : percent + '%'});

			var time = (percent / 100) * self.node.duration;
			self.jump(time);
			
		});
	}

	LazyVideo.prototype.show_error = function(error) {
		
		var self = this;
		
		error = (error === undefined) ? 'An error has occured' : error ;
		self.container.css({
			'padding-top' : self.container.height() / 2
		}).html(error);
		self.$node.hide();
	}

    $.fn.lazyvideo = function (option) {
		return this.each(function () {
			var $this = $(this);
			var data = $this.data('lazyvideo');
			var options = typeof option == 'object' && option;
			if (!data) $this.data('lazyvideo', (data = new LazyVideo(this, options)))
				if (typeof option == 'string') { data[option](); }
			})
	}

	$.fn.lazyvideo.Constructor = LazyVideo;

	$(document).ready(function () {
		$('.lazyvideo').each(function () {
			var $video = $(this);
			$video.lazyvideo($video.data());
		});
	});

}( jQuery, window, document, undefined ));