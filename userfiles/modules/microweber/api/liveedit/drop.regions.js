mw.drop_regions = {
    enabled: mw.settings.regions,
    ContainsDisabledSideClass: function(el) {
        var cls = ['edit', 'mw-col', 'mw-row', 'mw-col-container'],
            i = 0,
            l = cls.length;
        var elcls = el.className;
        if (elcls === '') {
            return true;
        }
        for (; i < l; i++) {
            if (mw.tools.hasClass(elcls, cls[i])) {
                return true;
            }
        }
        return false;
    },
    dropTimeout: null,
    global_drop_is_in_region: false,
    which: 'none',
    create: function(element) {
        var el = mw.$(element);
        var height = el.height();
        var width = el.width();
        var offset = el.offset();
        var region_left = {
            l: offset.left,
            r: offset.left + width * 0.1,
            t: offset.top,
            b: offset.top + height
        }
        var region_right = {
            l: offset.left + width - width * 0.1,
            r: offset.left + width,
            t: offset.top,
            b: offset.top + height
        }
        return {
            left: region_left,
            right: region_right
        }
    },
    is_in_region: function(regions, event) {

        var l = regions.left;
        var r = regions.right;
        var ep = mw.event.page(event);
        var mx = ep.x;
        var my = ep.y;
        if (mx > l.l && mx < l.r && my > l.t && my < l.b) {
            return 'left';
        } else if (mx > r.l && mx < r.r && my > r.t && my < r.b) {
            return 'right';
        } else {
            return 'none'
        }
    },
    init: function(element, event, callback) {

        if (mw.drop_regions.dropTimeout == null) {
            mw.drop_regions.dropTimeout = setTimeout(function() {
                if (mw.drop_regions.enabled) {
                    var regions = mw.drop_regions.create(element);
                    var is_in_region = mw.drop_regions.is_in_region(regions, event);
                    if (is_in_region == 'left' && !mw.drop_regions.ContainsDisabledSideClass(element)) {

                        callback.call(this, 'left');
                        mw.drop_regions.global_drop_is_in_region = true;
                        mw.drop_regions.which = 'left';
                    } else if (is_in_region == 'right' && !mw.drop_regions.ContainsDisabledSideClass(element)) {
                        callback.call(this, 'right');
                        mw.drop_regions.global_drop_is_in_region = true;
                        mw.drop_regions.which = 'right';
                    } else {
                        mw.drop_regions.global_drop_is_in_region = false;
                        mw.drop_regions.which = 'none';
                    }
                } else {
                    mw.drop_regions.global_drop_is_in_region = false;
                    mw.drop_regions.which = 'none';
                }
                mw.drop_regions.dropTimeout = null;
            }, 37);
        }
    }
}
