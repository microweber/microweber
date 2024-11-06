<div class="mw-social-share-links">
    @if($facebook_enabled)
        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(mw()->url->current()) }}">
            @svg('modules.social_links-facebook')

        </a>
    @endif

    @if($twitter_enabled)
        <a href="https://twitter.com/intent/tweet?text={{ urlencode(content_title()) }}&url={{ urlencode(mw()->url->current()) }}"
           target="_blank">
            @svg('modules.social_links-twitter')

        </a>
    @endif

    @if($pinterest_enabled)
        <a href="javascript:void(0);" onclick="mw.pinMarklet();" target="_self">
            <span class="mdi mdi-pinterest"></span>
        </a>
        <script type="text/javascript">
            if (!mw.pinMarklet) {
                mw.pinMarklet = function () {
                    var script = document.createElement('script');
                    script.src = '//assets.pinterest.com/js/pinmarklet.js';
                    document.body.appendChild(script)
                }
            }
        </script>
    @endif

    @if($linkedin_enabled)
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(mw()->url->current()) }}&title={{ urlencode(page_title()) }}"
           target="_blank">
            @svg('modules.social_links-linkedin')

        </a>
    @endif

    @if($viber_enabled)
        <a target="_blank" href="#" id="viber_share">
            <span class="mdi mdi-viber"></span>
        </a>
        <script>
            var buttonID = "viber_share";
            var text = "Check this out: ";
            document.getElementById(buttonID)
                .setAttribute('href', "https://3p3x.adj.st/?adjust_t=u783g1_kw9yml&adjust_fallback=https%3A%2F%2Fwww.viber.com%2F%3Futm_source%3DPartner%26utm_medium%3DSharebutton%26utm_campaign%3DDefualt&adjust_campaign=Sharebutton&adjust_deeplink=" + encodeURIComponent("viber://forward?text=" + encodeURIComponent(text + " " + window.location.href)));
        </script>
    @endif

    @if($whatsapp_enabled)
        <a target="_blank" href="whatsapp://send?text=Check this out: {{ mw()->url->current() }}"
           data-action="share/whatsapp/share">
            @svg('modules.social_links-whatsapp')
        </a>
    @endif

    @if(!$facebook_enabled && !$twitter_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
        {!! lnotif('No sharing options enabled. Please enable at least one sharing option in the settings.') !!}
    @endif
</div>
