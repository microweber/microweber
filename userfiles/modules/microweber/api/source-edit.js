mw.editSource = function (node) {

    if (!mw._editSource) {
        mw._editSource = {
            wrapper: document.createElement('div'),
            overlay: document.createElement('div'),
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
        mw._editSource.overlay.className = 'mw-inline-source-editor-overlay';
        document.body.appendChild(mw._editSource.overlay);
        document.body.appendChild(mw._editSource.wrapper);
        mw.$(mw._editSource.cancel).on('click', function () {
            mw.$(mw._editSource.target).html(mw._editSource.area.value);
            mw.$(mw._editSource.wrapper).removeClass('active');
            mw.$(mw._editSource.overlay).removeClass('active');
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
                hasErr.remove();
            }
        });
        mw.$(mw._editSource.ok).on('click', function () {
            if(!mw._editSource.ok.disabled){
                mw.$(mw._editSource.target).html(mw._editSource.area.value);
                mw.$(mw._editSource.wrapper).removeClass('active');
                mw.$(mw._editSource.overlay).removeClass('active');
                mw.wysiwyg.change(mw._editSource.target);
            }
        });


    }
    mw._editSource.area.value = node.innerHTML;
    mw._editSource.target = node;
    mw.$(mw._editSource.wrapper).addClass('active');
    mw.$(mw._editSource.overlay).addClass('active');
    if(mw._initHandles){
        mw._initHandles.hideAll();
    }

};
