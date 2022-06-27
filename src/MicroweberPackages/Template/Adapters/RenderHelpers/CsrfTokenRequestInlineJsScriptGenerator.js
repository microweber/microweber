$(document).ready(function () {

    if (typeof (mw.cookie) === 'undefined') {
        return;
    }

    if ($('meta[name="csrf-token"]').length === 0) {
        $("head").append("<meta name=csrf-token />");
    }

    var _csrf_from_cookie = null;

    var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");

    if (tokenFromCookie) {
        _csrf_from_cookie = tokenFromCookie;
    }

    if (!_csrf_from_cookie) {
        setTimeout(function () {
            $.post(route('csrf'), function (data) {
                var _csrf_from_local_storage = mw.cookie.get("XSRF-TOKEN");
                if (_csrf_from_local_storage) {
                    $('meta[name="csrf-token"]').attr('content',tokenFromCookie);
                }
                 $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            });
        }, 1337);
    } else {
        $('meta[name="csrf-token"]').attr('content', _csrf_from_cookie);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

});

