$(window).load(function () {
    mw.loaded = true;
    mw.tools.addClass(document.body, 'loaded');
    mw.tools.removeClass(document.body, 'loading');
    mw.$('div.mw-ui-field').click(function (e) {
        if (e.target.type != 'text') {
            try {
                this.querySelector('input[type="text"]').focus();
            }
            catch (e) {
            }
        }
    });

    mw.dropdown();
});
$(mwd).ready(function () {
    mw.tools.constructions();
    mw.dropdown();
    mw.$(document.body).ajaxStop(function () {
        setTimeout(function () {
            mw.dropdown();
        }, 1222);
    });
    mw.on('mwDialogShow', function(){
        mw.$(document.documentElement).addClass('mw-dialog-opened');
    });
    mw.on('mwDialogHide', function(){
        mw.$(document.documentElement).removeClass('mw-dialog-opened');
    });
    mw.$(document.body).on('mousemove touchmove touchstart', function (event) {
        var has = mw.tools.firstParentOrCurrentWithClass(event.target, 'tip');
        if (has && (!has.dataset.trigger || has.dataset.trigger === 'move')) {
            mw.tools.titleTip(has);
        }
        else {
            mw.$(mw.tools._titleTip).hide();
        }
    }).on('click', function (event) {
        var has = mw.tools.firstParentOrCurrentWithClass(event.target, 'tip');
        if (has && has.dataset.trigger === 'click') {
            mw.tools.titleTip(has, '_titleTipClick');
        }
        else {
            mw.$(mw.tools._titleTipClick).hide();
        }
    });
    mw.$(".mw-onoff").each(function () {
        if (!$(this).hasClass('activated')) {
            mw.$(this).addClass('activated');
            mw.$(this).mousedown(function () {
                var el = this;
                if (mw.tools.hasClass(el, 'active')) {
                    mw.tools.removeClass(el, 'active');
                    el.querySelector('.is_active_n').checked = true;
                }
                else {
                    mw.tools.addClass(el, 'active');
                    el.querySelector('.is_active_y').checked = true;
                }
            });
        }
    });
    mw.$(".wysiwyg-convertible-toggler").click(function () {
        var el = mw.$(this), next = el.next();
        mw.$(".wysiwyg-convertible").not(next).removeClass("active");
        mw.$(".wysiwyg-convertible-toggler").not(el).removeClass("active");
        next.toggleClass("active");
        el.toggleClass("active");
        if (el.hasClass("active")) {
            if (typeof mw.liveEditWYSIWYG === 'object') {
                mw.liveedit.toolbar.editor.fixConvertible(next);
            }
        }
    });
    mw.$(".mw-dropdown-search")
        .on('focus', function (e) {
            $(this).parents('.create-content-dropdown').addClass('focus');
        })
        .on('blur', function (e) {
            $(this).parents('.create-content-dropdown').removeClass('focus');
        })
        .on('keyup', function (e) {
        if (e.keyCode === '27') {
            mw.$(this.parentNode.parentNode).hide();
        }
        if (e.keyCode !== '13' && e.keyCode !== '27' && e.keyCode !== '32') {
            var el = mw.$(this);
            el.addClass('mw-dropdown-searchSearching');
            mw.tools.ajaxSearch({keyword: this.value, limit: 20}, function () {
                var html = "<ul>", l = this.length, i = 0;
                for (; i < l; i++) {
                    var a = this[i];
                    html += '<li class="' + a.content_type + ' ' + a.subtype + '"><a href="' + a.url + '" title="' + a.title + '">' + a.title + '</a></li>';
                }
                html += '</ul>';
                el.parent().next("ul").replaceWith(html);
                el.removeClass('mw-dropdown-searchSearching');
            });
        }
    });
    var _mwoldww = mw.$(window).width();
    mw.$(window).resize(function () {
        if ($(window).width() > _mwoldww) {
            mw.trigger("increaseWidth");
        }
        else if ($(window).width() < _mwoldww) {
            mw.trigger("decreaseWidth");
        }
        $.noop();
        _mwoldww = mw.$(window).width();
    });
    mw.$(document.body).on("keydown", function (e) {
        var isgal = document.querySelector('.mw_modal_gallery') !== null;
        if (isgal) {
            if (e.keyCode === 27) {  /* escape */
                mw.dialog.remove(mw.$(".mw_modal_gallery"))
                mw.tools.cancelFullscreen()
            }
            else if (e.keyCode === 37) { /* left */
                mw.tools.gallery.prev(mw.$(".mw_modal_gallery")[0].modal)
            }
            else if (e.keyCode === 39) { /* right */
                mw.tools.gallery.next(mw.$(".mw_modal_gallery")[0].modal)
            }
            else if (e.keyCode === 122) {/* F11 */
                mw.event.cancel(e, true);
                mw.tools.toggleFullscreen(mw.$(".mw_modal_gallery")[0]);
                return false;
            }
        }
        else {
            if (e.keyCode === 27) {
                var modal = mw.$(".mw_modal:last")[0];
                if (modal) modal.modal.remove();
            }
        }
    });

    mw.$(".mw-image-holder").each(function () {
        if ($(".mw-image-holder-overlay", this).length === 0) {
            mw.$('img', this).eq(0).after('<span class="mw-image-holder-overlay"></span>');
        }
    });

    mw.$(".mw-ui-dropdown").on('touchstart mousedown', function(){
        mw.$(this).toggleClass('active')
    });
    mw.$(document.body).on('touchend mouseup', function(e){
        if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['mw-ui-dropdown'])){
            mw.$(".mw-ui-dropdown.active").removeClass('active')
        }
    });
    mw.$(document.body).on('click', 'a', function(e){
        if(location.hash.indexOf('#mw@') !== -1 && (e.target.href || '').indexOf('#mw@') !== -1){
            if(location.href === e.target.href){
                var el = mw.$('#' + e.target.href.split('mw@')[1])[0];
                if(el){
                    mw.tools.scrollTo(el)
                }
            }
        }
    })


});
