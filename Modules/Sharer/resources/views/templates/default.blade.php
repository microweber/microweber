{{-- task-2026-05-22-f4a791 / AI-791 Slice E — Share section heading + wa.me URL + Copy link button --}}
@include('modules.sharer::components.custom-css')

<section class="mw-post-share-section" aria-labelledby="mw-sharer-heading-{{ e(md5(app()->url->current())) }}">
    <h2 id="mw-sharer-heading-{{ e(md5(app()->url->current())) }}" class="mw-post-share-heading h6 text-muted text-uppercase mb-3">
        {{ __('Share this post') }}
    </h2>

    <div class="mw-social-share-links d-flex flex-wrap align-items-center gap-2">
        @if($facebook_enabled)
            <a target="_blank" rel="noopener noreferrer" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(app()->url->current()) }}">
                @svg('modules.social_links-facebook')
            </a>
        @endif

        @if($x_enabled)
            <a href="https://x.com/intent/tweet?text={{ urlencode(content_title()) }}&url={{ urlencode(app()->url->current()) }}"
               target="_blank" rel="noopener noreferrer">
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
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(app()->url->current()) }}&title={{ urlencode(page_title()) }}"
               target="_blank" rel="noopener noreferrer">
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
            {{-- wa.me web URL works on all platforms (desktop + mobile) unlike the whatsapp:// deep-link --}}
            <a target="_blank" rel="noopener noreferrer"
               href="https://wa.me/?text={{ urlencode('Check this out: ' . app()->url->current()) }}">
                @svg('modules.social_links-whatsapp')
            </a>
        @endif

        @if($telegram_enabled)
            <a target="_blank" rel="noopener noreferrer" href="tg://msg_url?url={{ app()->url->current() }}&text=Check this out: {{ app()->url->current() }}">
                @svg('modules.social_links-telegram')
            </a>
        @endif

        {{-- Copy link button — uses the Clipboard API with a 2-second "Copied!" feedback state --}}
        <button type="button"
                class="mw-share-copy-link btn btn-sm btn-outline-secondary"
                data-mw-copy-link="{{ e(app()->url->current()) }}"
                data-mw-copy-link-done="{{ e(__('Copied!')) }}"
                aria-label="{{ __('Copy link to clipboard') }}">
            {{ __('Copy link') }}
        </button>

        @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
            {!! lnotif('No sharing options enabled. Please enable at least one sharing option in the settings.') !!}
        @endif
    </div>
</section>
