<style>
    .mw-module-skills-list .progress-bar {
        flex-direction: row;
    }
</style>

<div class="mw-module-skills-list">
    <div class="skillst">
        <script>
            //Skill
            $(document).ready(function () {
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


                <div class="progress mb-3">
                    <div class="progress-bar bg-{{ $skill['style'] }}" role="progressbar" style="width: {{ $skill['percent'] }}%;" aria-valuenow="{{ $skill['percent'] }}" aria-valuemin="0" aria-valuemax="100">
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
