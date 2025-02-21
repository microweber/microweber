var __slice = [].slice;

(function($, window) {
  var Starrr;
  Starrr = (function() {
    Starrr.prototype.defaults = {
      rating: void 0,
      emptyStarClass: 'fa fa-star-o',
      fullStarClass: 'fa fa-star',
      numStars: 5,
      change: function(e, value) {}
    };

    function Starrr($el, options) {
      var i, _, _ref;
      this.options = $.extend({}, this.defaults, options);
      this.$el = $el;
      _ref = this.defaults;
      for (i in _ref) {
        _ = _ref[i];
        if (this.$el.data(i) != null) {
          this.options[i] = this.$el.data(i);
        }
      }
      this.createStars();
      this.syncRating();
      this.$el.on('mouseover.starrr', 'i', (function(_this) {
        return function(e) {
          return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
        };
      })(this));
      this.$el.on('mouseout.starrr', (function(_this) {
        return function() {
          return _this.syncRating();
        };
      })(this));
      this.$el.on('click.starrr', 'i', (function(_this) {
        return function(e) {
          return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
        };
      })(this));
      this.$el.on('starrr:change', this.options.change);
    }

    Starrr.prototype.createStars = function() {
      var _i, _ref, _results;
      _results = [];
      for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
        _results.push(this.$el.append("<i class='" + this.options.emptyStarClass + "'></i>"));
      }
      return _results;
    };

    Starrr.prototype.setRating = function(rating) {
      this.options.rating = this.options.rating === rating ? void 0 : rating;
      this.syncRating();
      return this.$el.trigger('starrr:change', this.options.rating);
    };

    Starrr.prototype.syncRating = function(rating) {
      var i, _i, _j, _ref;
      rating || (rating = this.options.rating);
      if (rating) {
        for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
          this.$el.find('i').eq(i).removeClass(this.options.emptyStarClass).addClass(this.options.fullStarClass);
        }
      }
      if (rating && rating < 5) {
        for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
          this.$el.find('i').eq(i).removeClass(this.options.fullStarClass).addClass(this.options.emptyStarClass);
        }
      }
      if (!rating) {
        return this.$el.find('i').removeClass(this.options.fullStarClass).addClass(this.options.emptyStarClass);
      }
    };

    return Starrr;

  })();
  return $.fn.extend({
    starrr: function() {
      var args, option;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      return this.each(function() {
        var data;
        data = $(this).data('star-rating');
        if (!data) {
          $(this).data('star-rating', (data = new Starrr($(this), option)));
        }
        if (typeof option === 'string') {
          return data[option].apply(data, args);
        }
      });
    }
  });
})(window.jQuery, window);

$(function() {
  return $(".starrr").starrr();
});
