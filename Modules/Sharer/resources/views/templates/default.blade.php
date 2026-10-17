
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

        {{-- Copy link — ICON button (link icon, matching the social icons). On copy
             it swaps to a checkmark for 2s via a CSS class toggle (see
             csp-skin-handlers.js); the class toggle preserves the <svg> (a
             textContent swap would wipe it). sr-only label keeps it accessible. --}}
        {{-- Rendered as an <a role="button"> (not a <button>) so it inherits the
             template's link colour exactly like the social <a> icons next to it;
             a <button> would fall back to the default text colour. The copy JS
             (csp-skin-handlers.js) calls preventDefault(), so the href="#" never
             navigates. --}}
        <a href="#"
           role="button"
           class="mw-share-copy-link"
           data-mw-copy-link="{{ e(app()->url->current()) }}"
           data-mw-copy-link-done="{{ e(__('Copied!')) }}"
           aria-label="{{ __('Copy link to clipboard') }}"
           title="{{ __('Copy link to clipboard') }}">
            <span class="mw-share-copy-link-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
            </span>
            <span class="mw-share-copy-link-check" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </span>
            <span class="visually-hidden">{{ __('Copy link') }}</span>
        </a>

        @if(!$facebook_enabled && !$x_enabled && !$pinterest_enabled && !$linkedin_enabled && !$viber_enabled && !$whatsapp_enabled)
            {!! lnotif('No sharing options enabled. Please enable at least one sharing option in the settings.') !!}
        @endif
    </div>
</section>
