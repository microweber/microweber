$(document).ready(function () {

    if (typeof (mw.cookie) !== 'object') {
        return;
    }

    var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
    if (typeof (tokenFromCookie) === 'undefined') {
        setTimeout(function () {
            $.post(route('csrf'), function (data) {
                tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
                if (typeof tokenFromCookie !== 'undefined') {
                    $.ajaxSetup({
                        headers: {
                            'X-XSRF-TOKEN': tokenFromCookie
                        }
                    });
                }
            });
        }, 1337);
    } else {
        $.ajaxSetup({
            headers: {
                'X-XSRF-TOKEN': tokenFromCookie
            }
        });
    }

});

