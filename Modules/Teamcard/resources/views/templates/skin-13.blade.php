<?php
/*

type: layout

name: Skin-13

description: Skin-13

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
    <div class="row py-4 text-start text-left text-sm-start d-flex justify-content-center justify-content-lg-between">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
            <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
                <div class="d-block text-md-start text-center">
                    <div class="mb-5 mx-auto text-center d-flex justify-content-center justify-content-md-start">
                        <div class="w-200">
                            @if ($member['file'])
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}"/>
                                </div>
                            @else
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h6>{{ $member['name'] }}</h6>
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
