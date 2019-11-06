mw.tools.tabGroup = function (obj, master, m) {
    var master = master || mwd.body;
    m = typeof m === 'undefined' ? true : m;
    if (m) {
        m = {
            set: function (i) {
                if (typeof i === 'number') {
                    if (!$(obj.nav).eq(i).hasClass(active)) {
                        mw.$(obj.nav).removeClass(active);
                        mw.$(obj.nav).eq(i).addClass(active);
                        mw.$(obj.tabs).hide().eq(i).show();
                    }
                }
            },
            setLastClicked: function () {
                if ((typeof(obj.lastClickedTabIndex) != 'undefined') && obj.lastClickedTabIndex !== null) {
                    this.set(obj.lastClickedTabIndex);
                }
            },
            unset: function (i) {
                if (typeof i === 'number') {
                    if ($(obj.nav).eq(i).hasClass(active)) {
                        mw.$(obj.nav).eq(i).removeClass(active);
                        mw.$(obj.tabs).hide().eq(i).hide();
                    }
                }
            },
            toggle: function (i) {
                if (typeof i === 'number') {
                    if ($(obj.nav).eq(i).hasClass(active)) {
                        this.unset(i);
                    }
                    else {
                        this.set(i);
                    }
                }
            }
        }
    }
    var active = obj.activeNav || obj.activeClass || "active active-info",
        firstActive = 0;

    obj.lastClickedTabIndex = null;


    mw.$(obj.nav).on('click', function (e) {
        if (!$(this).hasClass(active)) {
            var i = mw.tools.index(this, mw.$(obj.nav).get(), mw.$(obj.nav)[0].nodeName);
            mw.$(obj.nav).removeClass(active);
            mw.$(this).addClass(active);
            mw.$(obj.tabs).hide().eq(i).show();
            obj.lastClickedTabIndex = i;
            if (typeof obj.onclick == 'function') {
                obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
            }
        }
        else {
            if (obj.toggle == true) {
                mw.$(this).removeClass(active);
                mw.$(obj.tabs).hide();
                if (typeof obj.onclick == 'function') {
                    var i = mw.tools.index(this, master, obj.nav);
                    obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
                }
            }
        }
        return false;
    }).each(function (i) {
        if (mw.tools.hasClass(this, active)) {
            firstActive = i;
        }
    });
    m.set(firstActive);
    return m;
};