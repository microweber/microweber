

;(function(){

var defaultTimeout = 2000;

var targetWindow = window.top;
var targetDocument = window.top.document;

var errorsHandlePrev = [];
mw.errorsHandle = function (obj) {
    while (errorsHandlePrev.length) {
        errorsHandlePrev[errorsHandlePrev.length-1].remove();
        errorsHandlePrev.pop();
    }

    if(!obj) return;
    if(obj.status === 401) {

        mw.session.checkPause = false;
        mw.session.checkPauseExplicitly = false;
        mw.session.logRequest();

    }
    obj.errors = obj.errors || obj.form_errors;
    if(!obj.errors) {
        $('.invalid-feedback', targetDocument).hide();
        $('.valid-feedback', targetDocument).hide();
    }
    if(obj.errors) {
        var html = [];
        for (var key in obj.errors) {
            var bsel = $('.custom-file-input[name="' + key + '"], .form-control[name="' + key + '"]').last()[0]
            if(!bsel) {
                var err = obj.errors[key].map ? obj.errors[key][0] : obj.errors[key];
                html.push(err);
            } else if ( bsel ) {
                var next = bsel.nextElementSibling;
                if (!next || !next.classList.contains('invalid-feedback')) {
                    next = targetDocument.createElement('div');
                    next.classList.add('invalid-feedback');
                    bsel.parentNode.insertBefore(next, bsel.nextSibling);
                    errorsHandlePrev.push(next);
                }
                next.style.display = 'block';

                $(next, targetDocument).css('display','block');
                next.innerHTML = obj.errors[key];
            }
        }
        if (html.length) {

            mw.notification.warning(html.join('<br>'))
        }
    }
    if (obj.errors && obj.message) {
        mw.notification.warning(obj.message);
    }
};
mw.notification = {
    msg: function (data, timeout, alert) {
        timeout = timeout || defaultTimeout;
        alert = alert || false;
        if (data) {

            if(data.status) {
                if(data.responseJSON && data.responseJSON.message) {
                    var conf = {};
                    if(data.status === 200) {
                        conf.success = data.responseJSON.message || data.statusText;
                    } else {
                        conf.warning = data.responseJSON.message || data.statusText;
                    }
                    mw.notification.msg(conf, timeout, alert);

                }

            } else if (data.success) {
                if (alert) {
                    mw.notification.success(data.success, timeout);
                }
                else {
                    mw.alert(data.success);
                }
            } else if (data.error) {
                mw.notification.error(data.error, timeout);
            } else if (data.warning) {
                mw.notification.warning(data.warning, timeout);
            }
        }
    },
    build: function (type, text, name) {
        var div = targetDocument.createElement('div');
        div.id = name;
        div.className = 'mw-notification mw-' + type;
        div.innerHTML = '<div>' + text + '</div>';
        return div;
    },
    append: function (type, text, timeout, name) {

        if(typeof type === 'object') {
            text = type.text;
            timeout = type.timeout;
            name = type.name;
            type = type.type;
        }

        var tpl = `
        
        <div class="position-fixed bottom-0 end-0 p-3 mw-tblr-notification">
            <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                <div class="toast-body">
                    ${text}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        
        `;
        var last = $('.mw-tblr-notification', targetDocument).last();
        if(last.length) {
            var btm = parseFloat(last.css('bottom'));
             
            if(isNaN(btm)) {
                btm = 0;
            }
            btm += last.outerHeight();
        }

        tpl = $(tpl, targetDocument).appendTo(targetDocument.body);
        tpl.css('bottom', btm);
        
        var toast = new window.top.bootstrap.Toast(tpl.children().get(0), {
            delay: timeout
        });
        toast.show();
        
    },
    success: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || defaultTimeout;
        mw.notification.append('success', text, timeout, name);
    },
    error: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || defaultTimeout;
        mw.notification.append('error', text, timeout, name);
    },
    warning: function (text, timeout, name) {
        if ( typeof text === 'object' ) {
            timeout = text.timeout;
            name = text.name;
            text = text.text;
        }
        timeout = timeout || defaultTimeout;
        mw.notification.append('warning', text, timeout, name);
    }
};


})();


