mw.responsive = {
    table: function (selector, options) {
        options = options || {};
        options.breakPoint = options.breakPoint || 768;
        options.breakPoints = options.breakPoints || false;
        mw.$(selector).each(function () {
            var css = mwd.createElement('style');
            var cls = 'responsive-table-' + mw.random();
            var sel = function (c) {
                var final = '', arr = c.split(',');
                arr.forEach(function (c, i) {
                    arr[i] = '.' + cls + ' ' + c.trim()
                })
                return arr.join(',');
            };
            mw.tools.addClass(this, cls);
            var el = mw.$(this);
            if (!options.breakPoints) {
                css.innerHTML = '@media (max-width:' + (options.breakPoint) + 'px) { '
                    + '.' + cls + '{ display: block; width:100%}'
                    + sel('tbody tr') + '{ margin-bottom: 20px;display: block; clear:both;overflow:hidden; }'
                    + sel('thead, tfoot') + '{ display: none; }'
                    + sel('.th-in-td, tbody,tr,td') + '{ display: block; width:100% }'
                    + sel('tbody td') + '{ text-align: left;display: block;width: 100%; }'
                    + '}';
            }
            else {
                var html = '';
                var arr = [];
                $.each(options.breakPoints, function (key, val) {
                    arr.push(key);
                })
                arr.sort(function (a, b) {
                    return b - a
                });
                $.each(arr, function (key) {
                    var val = options.breakPoints[this];
                    html += '\n @media (max-width:' + (this) + 'px) { '
                        + '.' + cls + '{ display: block; width:100%}'
                        + sel('tbody tr') + '{ margin-bottom: 20px;display: block;clear:both; }'
                        + sel('thead, tfoot') + '{ display: none; }'
                        + sel('.th-in-td, tbody,tr') + '{ display: block; width:100% }'
                        + sel('tbody td') + '{ display: block;width:' + (100 / val) + '%;float:left; }'
                        + '}';
                });
                mw.$('tr', el).each(function () {
                    var max = 0;
                    mw.$('td', this).height('auto').each(function () {
                        var h = mw.$(this).outerHeight();
                        if (h > max) {
                            max = h
                        }
                    })
                        .height(max)
                });
                mw.$(window).on('resize orientationchange', function () {
                    mw.$('tr', el).each(function () {
                        var max = 0;
                        mw.$('td', this).height('auto').each(function () {
                            var h = mw.$(this).outerHeight();
                            if (h > max) {
                                max = h
                            }
                        })
                            .height(max)
                    });
                })
                css.innerHTML = html
            }
            el.prepend(css);
            if (!el.hasClass('mw-mobile-table')) {
                el.addClass('mw-mobile-table');
                var th = mw.$('th', this)
                mw.$('tr', this).each(function () {
                    mw.$('td', this).each(function (i) {
                        mw.$(this).prepend('<span class="th-in-td">' + th.eq(i).html() + '</span>');
                    });
                })
            }
        });
    }
}