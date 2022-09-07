mw.tools.alert = function (text) {
    var html = ''
        + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw-alert-holder">' + text + '</div></td>'
        + '</tr>'
        + '<tr>'
        + '<td align="center" height="25"><span class="mw-ui-btn mw-ui-btn-medium" onclick="mw.dialog.remove(this);"><b>' + mw.msg.ok + '</b></span></td>'
        + '</tr>'
        + '</table>';
    if (mw.$("#mw_alert").length === 0) {
        return mw.dialog({
            html: html,
            width: 400,
            height: 200,
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


mw.tools.prompt = function (q, callback, currentVal) {
    if(!q) return ;
     var input = document.createElement('input');
    input.className = 'mw-ui-field w100';


    var question = mw.$('<div class="mw-prompt-input-container"><label class="mw-ui-label">'+q+'</label></div>');
    question.append(input)
    var footer = mw.$('<div class="mw-prompt-input-footer">');
    var ok = mw.$('<button type="button" disabled class="mw-ui-btn mw-ui-btn-info">'+mw.lang('OK')+'</button>');
    var cancel = mw.$('<span class="mw-ui-btn">'+mw.lang('Cancel')+'</span>');
    footer.append(cancel);
    footer.append(ok);
    var dialog = mw.dialog({
        content: question,
        title: q,
        footer: footer
    });
    ok.on('click', function (){
        callback.call(window, input.value);
        dialog.remove();
    });
    cancel.on('click', function (){
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
                callback.call(window, input.value);
                dialog.remove();
            }

        }
    };

    return dialog;
};
mw.tools.confirm = function (question, callback, onCancel) {
    if(typeof question === 'function') {
        callback = question;
        question = 'Are you sure?';
    }
    question = question || 'Are you sure?';
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td valign="middle"><div class="mw-alert-holder">' + question + '</div></td>'
            + '</tr>'
            + '</table>';

        var ok = mw.top().$('<span tabindex="99999" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">' + mw.msg.ok + '</span>');
        var cancel = mw.top().$('<span class="mw-ui-btn mw-ui-btn-medium ">' + mw.msg.cancel + '</span>');
        var modal;

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
            modal = mw.$("#mw_confirm_modal")[0].modal;
        }

        ok.on('keydown', function (e) {
            if (e.keyCode === 13 || e.keyCode === 32) {
                callback.call(window);
                modal.remove();
                e.preventDefault();
            }
        });
        cancel.on('click', function () {
            if(onCancel) {
                onCancel.call()
            }
            modal.remove();
        });
        ok.on('click', function () {
            callback.call(window);
            setTimeout(function () {
                modal.remove();
            }, 78);
        });
        setTimeout(function () {
            mw.$(ok).focus();
        }, 120);
        return modal;
    };
