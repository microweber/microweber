$(document).ready(function () {
    setTimeout(function () {
        var track = {referrer: document.referrer}
        $.ajax({
            url: mw.settings.api_url+'pingstats',
            data: track,
            type: "POST",
            success: function (data) {

                var a = document.createElement("script");
                a.type = "text/javascript";
                a.innerHTML = data;

                document.getElementsByTagName("head")[0].appendChild(a)

            }
        });

    }, 1337);
});



