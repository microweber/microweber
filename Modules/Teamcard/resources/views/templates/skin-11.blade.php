<?php
/*

type: layout

name: Skin-11

description: Skin-11

*/
?>

<script>
    $(document).ready(function ()
    { $(".mw-big-team-bio").each(function(i){
        var len=$(this).text().trim().length;
        if(len>10000)
        {
            $(this).text($(this).text().substr(0,12000)+'...');
        }
    });
    });
</script>

@if (isset($teamcard) and $teamcard)
    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
        <div class="col-12 col-sm-10 col-lg-6 col-lg-6 mx-auto">
            <div class="d-flex align-items-center position-relative">
                <div class="w-175 mx-auto position-absolute left-0">
                    @if ($member['file'])
                        <div class="img-as-background square rounded-circle">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($member['file'], 800, null, [
                                'alt' => $member['name'] ?? __('Team member'),
                                'class' => 'img-fluid',
                            ]) !!}
                        </div>
                    @else
                        <div class="img-as-background square rounded-circle">
                            <img loading="lazy" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}" alt="{{ $member['name'] ?? __('Team member') }}" class="img-fluid"/>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif

@endif
