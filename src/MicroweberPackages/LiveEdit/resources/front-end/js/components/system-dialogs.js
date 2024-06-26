export const Alert = function (text) {

    var html = ''
        + '<div class="mw_alert">'
        + '<div class="mw-alert-holder">' + text + '</div>'
        + '<div class="mw-dialog-footer"><span class="btn btn-primary mb-2" onclick="mw.dialog.remove(this);"><b>' + mw.msg.ok + '</b></span></div>'
        + '</div>';

    if (mw.$("#mw_alert").length === 0) {
        return mw.dialog({
            html: html,
            width: 400,
            height: "auto",
            overlay: false,
            name: "mw_alert",
            template: "mw_modal_basic"
        });
    }
    else {
        mw.$("#mw_alert .mw-alert-holder").html(text);
        return mw.$("#mw_alert")[0].modal;
    }
};


export const Prompt = function (q, callback, currentVal) {
    if(!q) return ;

     var input = document.createElement('input');
    input.className = 'mw-ui-field w100';


    var question = mw.$('<div class="mw-dialog-container"><label class="live-edit-label">'+q+'</label></div>');
    question.append(input)
    var footer = mw.$('<div class="mw-dialog-footer">');
    var ok = mw.$('<button type="button" disabled class="btn-primary">'+mw.lang('OK')+'</button>');
    var cancel = mw.$('<span class="btn">'+mw.lang('Cancel')+'</span>');
    footer.append(cancel);
    footer.append(ok);
    console.log(mw.top())
    var dialog = mw.top().dialog({
        content: question,
        title: q,
        footer: footer
    });
    var qresolve, promise = new Promise(resolve => {
        qresolve = resolve;
    });
    ok.on('click', function (){
        if (callback) {
            callback.call(window, input.value);
        }
        qresolve(input.value)
        dialog.remove();
    });
    cancel.on('click', function (){
        qresolve(false)
        dialog.remove();
    });
    if(currentVal) {
        input.value = currentVal.trim()
    }
    setTimeout(function (){
        input.focus();
    }, 50);
    input.oninput = function () {
        var val = this.value.trim();
        ok[0].disabled = !val;
    };
    input.onkeydown = function (e) {
        if (mw.event.is.enter(e)) {
            var val = this.value.trim();
            if (val) {
                if (callback) {
                    callback.call(window, input.value);
                }
                qresolve(input.value)
                dialog.remove();

            }

        }
    };

    return {
        dialog,
        promise: () => promise
    };
};
export const Confirm = function (question, callback, onCancel) {
    if(typeof question === 'function') {
        callback = question;
        question = 'Are you sure?';
    }
    question = question || 'Are you sure?';
        var html = ''
            + '<div class="mw_alert">'
            + '<div class="mw-alert-holder">' + question + '</div>'
            + '</div>';

        var ok = mw.top().$('<span tabindex="99999" class="btn btn-primary mb-3">' + mw.msg.ok + '</span>');
        var cancel = mw.top().$('<span class="btn btn-link ">' + mw.msg.cancel + '</span>');
        var modal, qresolve, promise = new Promise(resolve => {
            qresolve = resolve;
        });

        if (mw.$("#mw_confirm_modal").length === 0) {
            modal = mw.top().dialog({
                content: html,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: true,
                name: "mw_confirm_modal",
                footer: [cancel, ok],
                title: mw.lang('Confirm')
            });

        }
        else {
            mw.$("#mw_confirm_modal .mw-alert-holder").html(question);
            modal = mw.$("#mw_confirm_modal")[0]._dialog;
        }

        ok.on('keydown', function (e) {
            if (e.keyCode === 13 || e.keyCode === 32) {
            if (callback) {
                callback.call(window);
            }
                modal.remove();
                qresolve(true)
                e.preventDefault();
            }
        });
        if(modal.dialogHeader) {
            var close = modal.dialogHeader.querySelector('.mw-dialog-close');
            if(close){
                close.addEventListener('click', function (){
                    qresolve(false)
                    if(onCancel) {
                        onCancel.call();
                    }
                });
            }
        }


        cancel.on('click', function () {
            if(onCancel) {

                onCancel.call()
            }
            qresolve(false)
            modal.remove();
        });
        ok.on('click', function () {
            if(callback) {
                callback.call(window);
            }

            qresolve(true)
            setTimeout(function () {
                modal.remove();
            }, 78);
        });
        setTimeout(function () {
            mw.$(ok).focus();
        }, 120);
        return {
            dialog: modal,
            promise: () => promise
        };
    };
