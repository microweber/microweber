(function ($, window, undefined) {
	"use strict";

	function Vector2D(x, y) {
		this.x = x;
		this.y = y;

		this.add = function (vec2d) {
			this.x += vec2d.x;
			this.y += vec2d.y;
			return this;
		};
		
		this.subtract = function (vec2d) {
			this.x -= vec2d.x;
			this.y -= vec2d.y;
			return this;
		};

		this.scale = function (scale) {
			this.x *= scale;
			this.y *= scale;
			return this;
		};
		
		this.normalize = function () {
			var len = this.length();
			this.x /= len;
			this.y /= len;
			return this;
		};

		this.dot = function (vec2d) {
			return this.x * vec2d.x + this.y * vec2d.y;
		};
		
		this.getAdded = function (vec2d) {
			return this.copy().add(vec2d);
		};
		
		this.getSubtracted = function (vec2d) {
			return this.copy().subtract(vec2d);
		};

		this.getRotated = function (r) {
			var x = (this.x * Math.cos(r) + this.y * -Math.sin(r)),
				y = (this.x * Math.sin(r) + this.y * Math.cos(r));

			return new Vector2D(x, y);
		};
		
		this.getNormalized = function () {
			return this.copy().normalize();
		};
				
		this.length = function () {
			return Math.sqrt(this.x * this.x + this.y * this.y);
		};
		
		this.angle = function (vec2d) {
			return Math.acos(this.getNormalized().dot(vec2d.getNormalized()));
		};
		
		this.copy = function() {
			return new Vector2D(this.x, this.y);
		};
	}
	
	var defaultSettings = {
			'max-insects': 3,
			'chance': 0.5,
			'max-speed': 15,
			'min-speed': 5,
			'update-freq': 50,
			'mouse-trigger': true,
			'mouse-distance': 50,
			'scared': true,
			'squishable': true
		},
		mousePos = new Vector2D(0, 0);

	function Insect($layer, settings, $window) {
		var lastUpdate = new Date().getTime();

		this.create = function () {
			this.$element = $('<div class="jq-insect"></div>').css({
				position: 'absolute',
				top: this.position.y + 'px',
				left: this.position.x + 'px',
				'z-index': $layer.css('z-index') - 1
			});

			$('body').append(this.$element);

			if (settings.squishable) {
				var obj = this;
				this.$element.click(function () {
					obj.squish();
				});
			}

			this.randomRotate();
			this.update();
		};

		this.rotateElement = function () {
			var rotate = 'rotate(' + this.rotation + 'rad)';

			this.$element.css({
				'-webkit-transform': rotate,
				'-moz-transform': rotate,
				'-ms-transform': rotate
			});
		};

		this.randomRotate = function () {
			if (!this.isAlive()) {
				return;
			}

			this.rotation += Math.random() * 1.5 - 0.75;
			this.rotateElement();

			var insect = this;
			setTimeout(function () {
				insect.randomRotate();
			}, Math.random() * 1000);
		};

		this.isAlive = function () {
			return this.alive;
		};

		this.kill = function () {
			this.alive = false;
			this.$element.remove();
		};

		this.squish = function () {
			this.alive = false;
			this.$element.addClass('jq-insect-dead');
		}

		this.isOffscreen = function () {
			var scrollTop = $window.scrollTop();
			if (this.position.x < -50
					|| this.position.y < scrollTop - 50
					|| this.position.x > $window.width()
					|| this.position.y > scrollTop + $window.height()) {
				this.kill();
			}
		};

		this.update = function () {
			if (!this.alive) {
				return false;
			}

			var now = new Date().getTime(),
				delta = now - lastUpdate;

			lastUpdate = now;

			this.position.add(this.direction.getRotated(this.rotation).scale(delta / 50));
			
			if (mousePos.getSubtracted(this.position).length() < settings['mouse-distance']) {
				this.direction.y = settings['max-speed'];

				if (settings['scared']) {
					var mouseDir = mousePos.getSubtracted(this.position),
						angle = Math.abs(mouseDir.angle(this.direction.getRotated(this.rotation)));
	
					if (angle < Math.PI / 4) {
						this.rotation += Math.random(Math.PI / 2) + Math.PI / 2;
						this.rotateElement();
					}
				}
			}
			
			if (this.isOffscreen(window.width, window.height)) {
				this.kill();
				return;
			}

			this.$element.css({
				top: this.position.y + 'px',
				left: this.position.x + 'px'
			});

			var insect = this;
			setTimeout(function () {
				insect.update();
			}, settings['update-freq']);
		};

		var offset = $layer.offset(),
			x = offset.left + $layer.width() / 2,
			y = offset.top + $layer.height() / 2,
			speed = Math.random() *
				(settings["max-speed"] - settings["min-speed"]) +
				settings["min-speed"];

		this.position = new Vector2D(x, y);
		this.direction = new Vector2D(0, speed);
		this.rotation = (Math.random() * 2 * Math.PI);
		this.alive = true;
		this.create();
	}
	
	function validateSettings(settings) {
		if (settings['max-insects'] < 1) {
			settings['max-insects'] = 1;
		}
		
		if (settings['chance'] < 0) {
			settings['chance'] = 0;
		}
		if (settings['chance'] > 1) {
			settings['chance'] = 1;
		}

		if (settings['min-speed'] < 1) {
			settings['min-speed'] = 1;
		}
		if (settings['max-speed'] < settings['min-speed']) {
			settings['max-speed'] = settings['min-speed'];
		}

		return settings;
	}

	$.fn.insectify = function (options) {
		var settings = validateSettings($.extend(defaultSettings, options));

		if (settings['mouse-trigger']) {
			$(window).bind("mousemove", function (event) {
				mousePos.x = event.pageX;
				mousePos.y = event.pageY;
			});
		}

		if (Math.random() > settings.chance) {
			return this;
		}

		return this.each(function (index, element) {
			var count = Math.floor(Math.random() * settings['max-insects']),
				insects = [],
				i;

			for (i = 0; i <= count; i += 1) {
				insects.push(new Insect($(element), settings, $(window)));
			}
		});
	};
}(jQuery, window));
