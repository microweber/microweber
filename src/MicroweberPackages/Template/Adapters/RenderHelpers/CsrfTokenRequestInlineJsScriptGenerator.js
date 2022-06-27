$(document).ready(function () {

    if (typeof (mw.cookie) === 'undefined') {
        return;
    }


    if ($('meta[name="csrf-token"]').length === 0) {
        $("head").append("<meta name=csrf-token />");
    }
    var _csrf_from_local_storage = null;

    var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");



    if (tokenFromCookie) {
        _csrf_from_local_storage = tokenFromCookie;

    }

    if (_csrf_from_local_storage) {
        $('meta[name="csrf-token"]').attr('content', _csrf_from_local_storage);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        return;
    } else {

        setTimeout(function () {
            $.post(route('csrf'), function (data) {
                $('meta[name="csrf-token"]').attr('content', data.token);

            });
        }, 1337);
    }
});

