<?php
/*

type: layout

name: Skin-15

description: Skin-15

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
    <div class="d-flex justify-content-center align-items-center mt-5 flex-wrap">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
            <div class="col-xl-3 col-md-6 col-sm-8 col-12 mx-auto d-flex justify-content-center align-items-center py-4">
                <div class="flower-card card w-100 mx-2" style="border-radius: 0 20px 0 20px;">
                    @if ($member['file'])
                        <img loading="lazy" class="flower-team-card-img card-img-top" style="height: 350px;
    object-fit: cover;" src="{{ thumbnail($member['file'], 800) }}"/>
                    @else
                        <img loading="lazy" class="flower-team-card-img card-img-top" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                    @endif

                    <div class="card-body">
                        <h5>{{ $member['name'] }}</h5>
                        <p>{{ $member['role'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
        @else
            <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
        @endif
    </div>
@endif
