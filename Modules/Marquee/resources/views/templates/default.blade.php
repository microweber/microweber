
<style>
    #v-marquee-<?php echo $id; ?>{
        overflow: hidden;
        font-size: <?php echo $fontSize; ?>px;
        line-height: normal;
        color: <?php echo $textColor; ?>;
        font-weight: <?php echo $textWeight; ?>;
        font-style: <?php echo $textStyle; ?>;


    }
    @media only screen and (max-width: 1399px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.8 ?>px);

        }
    }
    @media only screen and (max-width: 1199px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.65 ?>px);

        }
    }
    @media only screen and (max-width: 991px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.55 ?>px);

        }
    }
    @media only screen and (max-width: 767px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.45 ?>px);

        }
    }
    @media only screen and (max-width: 575px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.4 ?>px);

        }
    }
    @media only screen and (max-width: 479px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.3 ?>px);

        }
    }
    @media only screen and (max-width: 375px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.2 ?>px);

        }
    }
</style>

<div id="v-marquee-{{ $id }}">
    {{ $text }}
</div>

<script type="module">
    /*
     * task-2026-05-05-d71799 — drunk-designer audit (marquee.md QW#1):
     * honor `prefers-reduced-motion: reduce`. Users who set the OS
     * preference (vestibular disorder, motion sickness, focus) get a
     * static, readable marquee instead of the looping animation. We
     * detect the preference once at mount + listen for changes.
     */
    (function () {
        var el = document.getElementById('v-marquee-{{ $id }}');
        if (!el) return;
        var mql = window.matchMedia('(prefers-reduced-motion: reduce)');
        var marqueeInstance = null;
        function start() {
            if (marqueeInstance) return;
            import('{{ asset('modules/marquee/js/vanilla-marquee.min.js') }}').then(function (m) {
                marqueeInstance = new (m.default || m)(el, {
                    speed: '{{ $animationSpeed }}',
                });
            });
        }
        if (mql.matches) {
            // Reduced motion: render the text statically, no scroll.
            el.style.whiteSpace = 'normal';
        } else {
            start();
        }
        // React to preference flips at runtime.
        var addListener = mql.addEventListener ? mql.addEventListener.bind(mql, 'change')
                                                : mql.addListener && mql.addListener.bind(mql);
        if (addListener) addListener(function (e) {
            if (e.matches) {
                if (marqueeInstance && typeof marqueeInstance.destroy === 'function') {
                    try { marqueeInstance.destroy(); } catch (err) {}
                    marqueeInstance = null;
                }
                el.style.whiteSpace = 'normal';
            } else {
                el.style.whiteSpace = '';
                start();
            }
        });
    })();
</script>
