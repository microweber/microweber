@include('modules.sharer::components.custom-css')

<div class="mw-social-share-links">
    @if($facebook_enabled)
        {{-- AI-215 (cycle-167 2026-05-10): cycle-N had `rel="noopener
             noreferrer"` accidentally injected INSIDE the PHP
             expression as `mw()- rel="noopener noreferrer">url->current()`,
             which broke the `->` chain and produced "syntax error,
             unexpected token =" at blade-compile time on every
             post-detail page render (FrontendController->frontend()
             called for any /post-slug). Restored to `mw()->url->
             current()` and moved the rel attribute onto the <a> tag. --}}
        <a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(mw()->url->current()) }}">
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
        <a href="javascript:void(0);" data-mw-pinmarklet target="_self">
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
        <a target="_blank" href="#" id="viber_share" rel="noopener noreferrer">
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
        {{-- AI-215 (cycle-167): same `mw()- rel="..."` corruption as
             facebook above. --}}
        <a target="_blank" rel="noopener noreferrer" href="whatsapp://send?text=Check this out: {{ mw()->url->current() }}"
           data-action="share/whatsapp/share">
            @svg('modules.social_links-whatsapp')
        </a>
    @endif

    @if($telegram_enabled)
        {{-- AI-215 (cycle-167): same `mw()- rel="..."` corruption. --}}
        <a target="_blank" rel="noopener noreferrer" href="tg://msg_url?url={{ mw()->url->current() }}&text=Check this out: {{ mw()->url->current() }}">
            @svg('modules.social_links-telegram')
        </a>
    @endif

    @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
        {!! lnotif('No sharing options enabled. Please enable at least one sharing option in the settings.') !!}
    @endif
</div>
