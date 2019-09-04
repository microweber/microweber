mw.editSource = function (node) {
    if (!mw._editSource) {
        mw._editSource = {
            wrapper: document.createElement('div'),
            area: document.createElement('textarea'),
            ok: document.createElement('mwbtn'),
            cancel: document.createElement('mwbtn'),
            nav:document.createElement('div'),
            validator:document.createElement('div')
        };
        mw.$(mw._editSource.ok).addClass('mw-ui-btn mw-ui-btn-medium mw-ui-btn-info').html(mw.lang('OK'));
        mw.$(mw._editSource.cancel).addClass('mw-ui-btn mw-ui-btn-medium').html(mw.lang('Cancel'));

        mw._editSource.wrapper.appendChild(mw._editSource.area);
        mw._editSource.wrapper.appendChild(mw._editSource.nav);
        mw._editSource.nav.appendChild(mw._editSource.cancel);
        mw._editSource.nav.appendChild(mw._editSource.ok);
        mw._editSource.nav.className = 'mw-inline-source-editor-buttons';
        mw._editSource.wrapper.className = 'mw-inline-source-editor';
        document.body.appendChild(mw._editSource.wrapper);
        mw.$(mw._editSource.cancel).on('click', function () {
            mw.$(mw._editSource.target).html(mw._editSource.area.value);
            mw.$(mw._editSource.wrapper).removeClass('active');
            mw._editSource.ok.disabled = false;
        });
        mw.$(mw._editSource.area).on('input', function () {
            mw._editSource.validator.innerHTML = mw._editSource.area.value;
            mw._editSource.ok.disabled = mw._editSource.validator.innerHTML !== mw._editSource.area.value;
            mw._editSource.ok.classList[mw._editSource.ok.disabled ? 'add' : 'remove']('disabled');
            var hasErr = mw.$('.mw-inline-source-editor-error', mw._editSource.nav);
            if(mw._editSource.ok.disabled) {
                if(!hasErr.length) {
                    mw.$(mw._editSource.nav).prepend('<span class="mw-inline-source-editor-error">' + mw.lang('Invalid HTML') + '</span>');
                }
            }
            else {
                hasErr.remove()
            }
        });
        mw.$(mw._editSource.ok).on('click', function () {
            if(!mw._editSource.ok.disabled){
                mw.$(mw._editSource.target).html(mw._editSource.area.value);
                mw.$(mw._editSource.wrapper).removeClass('active');
                mw.wysiwyg.change(mw._editSource.target);
            }
        });
    }
    mw._editSource.area.value = node.innerHTML;
    mw._editSource.target = node;
    var $node = mw.$(node), off = $node.offset(), nwidth = $node.outerWidth();
    var sl = mw.$('.mw-live-edit-sidebar-tabs-wrapper').offset();
    if (off.left + nwidth > sl.left) {
        off.left -= ((off.left + nwidth) - sl.left + 10);
    }
    mw.$(mw._editSource.area)
        .height($node.outerHeight())
        .width(nwidth);

    mw.$(mw._editSource.wrapper)
        .css(off)
        .addClass('active');


};
