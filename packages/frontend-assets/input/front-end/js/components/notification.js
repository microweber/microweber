

;(function(){

var defaultTimeout = 5000;

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


        var closeBtn = `
            <button style="--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);" class="fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-no-notification-close-btn" type="button"  >
                <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"></path>
                </svg>
            </button>
        `;

        var tpl = `

        <div class="mw-notification">
            <div class="  align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="flex items-center justify-between">
                    <div>
                        ${text}
                    </div>
                    ${closeBtn}
                </div>
            </div>
        </div>

        `;
        var last = $('.mw-notification', targetDocument).last();
        if(last.length) {
            var btm = parseFloat(last.css('top'));

            if(isNaN(btm)) {
                btm = 0;
            }
            btm += last.outerHeight();
        }

        tpl = $(tpl, targetDocument).appendTo(targetDocument.body);
        tpl.css('top', btm);
        tpl.find('button').on('click', function () {
            tpl.remove()
        })


        setTimeout(function () {
            tpl.remove()
        }, timeout)





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
        mw.notification.append('danger', text, timeout, name);
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


