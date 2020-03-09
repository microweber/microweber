$(document).ready(function () {
    setTimeout(function () {
        var track = {referrer: document.referrer}
        $.ajax({
            url: mw.settings.api_url+'pingstats',
            data: track,
            type: "POST",
            dataType: "json"
        });
    }, 1337);
});



