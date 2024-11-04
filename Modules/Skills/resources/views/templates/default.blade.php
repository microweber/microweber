<div class="container mw-module-skills-list">
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
                <div class="progress mb-3">
                    <div class="progress-bar bg-{{ $skill['style'] }}" role="progressbar" style="width: {{ $skill['percent'] }}%;" aria-valuenow="{{ $skill['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                        <span class="title">{{ $skill['skill'] }}</span>
                        <span class="count">{{ $skill['percent'] }}%</span>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted">{{ __('Click to insert skills') }}</p>
        @endif
    </div>
</div>
