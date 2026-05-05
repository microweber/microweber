<style>
    .mw-module-skills-list .progress-bar {
        flex-direction: row;
    }
    /*
     * task-2026-05-05-d71799 — drunk-designer audit (skills.md QW#1):
     * snap fill animation when user has prefers-reduced-motion: reduce.
     * The width is already set inline at render time, so we just need
     * to suppress any CSS transition/animation on the bar.
     */
    @media (prefers-reduced-motion: reduce) {
        .mw-module-skills-list .progress-bar {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<div class="mw-module-skills-list">
    <div class="skillst">
        <script>
            //Skill
            $(document).ready(function () {
                /*
                 * task-2026-05-05-d71799 — honor prefers-reduced-motion:
                 * skip the .skillbar fill-animation; the rendered bars
                 * already carry the correct width inline so users see
                 * the final value without a moving fill.
                 */
                if (window.matchMedia
                    && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    $('.skillbar').each(function () {
                        var percent = $(this).attr('data-percent');
                        $(this).find('.count-bar').css('width', percent);
                        $(this).find('.count').html('<span>' + percent + '</span>');
                    });
                    return;
                }
                $(window).on('load scroll resize', function () {
                    $('.skillbar').each(function () {
                        if (mw.tools.inview(this)) {
                            $(this).find('.count-bar').animate({
                                width: $(this).attr('data-percent')
                            }, 3000);
                        }
                        var percent = $(this).attr('data-percent');
                        $(this).find('.count').html('<span>' + percent + '</span>');
                    });
                });
            });
        </script>

        @if(!empty($skills))
            @foreach ($skills as $skill)

                @if(!isset($skill['style']))
                    @php
                    $skill['style'] = 'primary'
                    @endphp
                @endif

                @if(!isset($skill['percent']))
                    @php
                    $skill['percent'] = 100
                    @endphp
                @endif


                {{-- task-2026-05-05-d71799 (skills.md QW#2): set
                     aria-label so screen readers announce the
                     skill name alongside the percentage. The
                     skill name was previously only inside a span
                     after the role=progressbar — many SR users
                     hear "85 percent" with no context. --}}
                <div class="progress mb-3">
                    <div class="progress-bar bg-{{ $skill['style'] }}" role="progressbar" style="width: {{ $skill['percent'] }}%;" aria-valuenow="{{ $skill['percent'] }}" aria-valuemin="0" aria-valuemax="100" @if(isset($skill['skill']) && $skill['skill']) aria-label="{{ $skill['skill'] }}: {{ $skill['percent'] }}%" @endif>
                        @if(isset($skill['skill']) and $skill['skill'])
                        <span class="title">{{ $skill['skill'] }}</span>
                        @endif
                        @if(isset($skill['percent'])) &nbsp;
                        <span class="count">{{ $skill['percent'] }}%</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">{{ __('Click to insert skills') }}</p>
        @endif
    </div>
</div>
