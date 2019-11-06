mw.tools.alert = function (text) {
    var html = ''
        + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
        + '<tr>'
        + '<td align="center" valign="middle"><div class="mw_alert_holder">' + text + '</div></td>'
        + '</tr>'
        + '<tr>'
        + '<td align="center" height="25"><span class="mw-ui-btn" onclick="mw.tools.modal.remove(\'mw_alert\');"><b>' + mw.msg.ok + '</b></span></td>'
        + '</tr>'
        + '</table>';
    if (mw.$("#mw_alert").length === 0) {
        return mw.modal({
            html: html,
            width: 400,
            height: 200,
            overlay: false,
            name: "mw_alert",
            template: "mw_modal_basic"
        });
    }
    else {
        mw.$("#mw_alert .mw_alert_holder").html(text);
        return mw.$("#mw_alert")[0].modal;
    }
};


mw.tools.confirm = function (question, callback) {
        var html = ''
            + '<table class="mw_alert" width="100%" height="140" cellpadding="0" cellspacing="0">'
            + '<tr>'
            + '<td align="center" valign="middle"><div class="mw_alert_holder">' + question + '</div></td>'
            + '</tr>'
            + '</table>';

        var ok = $('<span tabindex="99999" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">'+mw.msg.ok+'</span>');
        var cancel = $('<span class="mw-ui-btn mw-ui-btn-medium ">' + mw.msg.cancel + '</span>');

        if (mw.$("#mw_confirm_modal").length === 0) {
            var modal = mw.top().dialog({
                content: html,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: false,
                name: "mw_confirm_modal",
                footer: [cancel, ok]
            });
        }
        else {
            mw.$("#mw_confirm_modal .mw_alert_holder").html(question);
            var modal = mw.$("#mw_confirm_modal")[0].modal;
        }


        ok.on('keydown', function (e) {
            if (e.keyCode === 13 || e.keyCode === 32) {
                callback.call(window);
                modal.remove();
                e.preventDefault();
            }
        });
        cancel.on('click', function () {
            modal.remove();
        });
        ok.on('click', function () {
            callback.call(window);
            modal.remove();
        });
        setTimeout(function () {
            mw.$(ok).focus();
        }, 120);
        return modal;
    };