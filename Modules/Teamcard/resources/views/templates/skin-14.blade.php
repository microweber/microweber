<?php
/*

type: layout

name: Skin-14

description: Skin-14

*/
?>

<script>
    $(document).ready(function ()
    { $(".mw-big-team-bio").each(function(i){
        var len=$(this).text().trim().length;
        if(len>100)
        {
            $(this).text($(this).text().substr(0,120)+'...');
        }
    });
    });
</script>

@if (isset($teamcard) and $teamcard)
    <div class="row d-flex justify-content-center justify-content-lg-between">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
                <div class="col-sm-12 col-lg-6 mb-3">
                    <div class="d-block">
                        @if ($member['file'])
                            <div class="img-as-background square mb-3">
                                <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}"/>
                            </div>
                        @else
                            <div class="img-as-background square mb-3">
                                <img loading="lazy" src="{{ asset('templates/big2/modules/teamcard/templates/default-image.svg') }}"/>
                            </div>
                        @endif
                        <div>
                            <h3>{{ $member['name'] }}</h3>
                            <p>{{ $member['role'] }}</p>
                            <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
        @endif
    </div>
@endif
