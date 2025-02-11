@include('modules.sharer::components.custom-css')

<div class="mw-social-share-links">
    @if($facebook_enabled)
        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(mw()->url->current()) }}">
            @svg('modules.social_links-facebook')
        </a>
    @endif

    @if($x_enabled)
        <a href="https://x.com/intent/tweet?text={{ urlencode(content_title()) }}&url={{ urlencode(mw()->url->current()) }}"
           target="_blank">
            @svg('modules.social_links-x')
        </a>
    @endif

    @if($pinterest_enabled)
        <a href="javascript:void(0);" onclick="mw.pinMarklet();" target="_self">
            @svg('modules.social_links-pinterest')
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
            @svg('modules.social_links-viber')
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

    @if($telegram_enabled)
        <a target="_blank" href="tg://msg_url?url={{ mw()->url->current() }}&text=Check this out: {{ mw()->url->current() }}">
            @svg('modules.social_links-telegram')
        </a>
    @endif

    @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
        {!! lnotif('No sharing options enabled. Please enable at least one sharing option in the settings.') !!}
    @endif
</div>
