<div class="mw-tweets-embed" id="tweet-embed-{{ $params['id'] }}">
    <script src="https://platform.twitter.com/widgets.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $.getJSON("https://api.twitter.com/1/statuses/oembed.json?id={{ $status_id }}&align=center&callback=?",
                function (data) {
                    $('#tweet-embed-{{ $params['id'] }} .tweet-embeded').html(data.html);
                });
        });
    </script>

    <div class="tweet-embeded"></div>
</div>
