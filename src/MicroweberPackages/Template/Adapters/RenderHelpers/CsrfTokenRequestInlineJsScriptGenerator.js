$(document).ready(function () {

    if (typeof (mw.cookie) === 'undefined') {
        return;
    }


    if ($('meta[name="csrf-token"]').length === 0) {
        $("head").append("<meta name=csrf-token />");
    }
    var _csrf_from_local_storage = null;

    var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");



    if (tokenFromCookie === null) {
        var csrf_from_local_storage_data = mw.cookie.get("csrf-token-data");
        if (csrf_from_local_storage_data) {
            csrf_from_local_storage_data = JSON.parse(csrf_from_local_storage_data);

            if (csrf_from_local_storage_data && csrf_from_local_storage_data.value && (new Date()).getTime() < csrf_from_local_storage_data.expiry) {
                _csrf_from_local_storage = csrf_from_local_storage_data.value
            }
        }
    } else {
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        }, 1337);
    }
});

