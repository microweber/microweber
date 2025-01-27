<?php
/*

type: layout

name: Skin-10

description: Skin-10

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
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
            <div class="col-sm-10 col-md-6 col-lg-6 mb-5">
                <div class="d-block d-sm-flex align-items-center justify-content-between border px-4 py-5 h-100">
                    <div class="col-3 me-sm-4 mb-5 mb-sm-0 mx-auto mx-sm-0 order-1 order-md-2">
                        <div class="w-150 mx-auto">
                            @if ($member['file'])
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}"/>
                                </div>
                            @else
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="{{ asset('templates/big2/modules/teamcard/templates/default-image.svg') }}"/>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-9 mx-auto order-2 order-md-1" style="max-width: 320px;">
                        <h3>{{ $member['name'] }}</h3>
                        <p>{{ $member['role'] }}</p>
                        <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
        @else
            <div>
                Add your teamcard.
            </div>
        @endif
    </div>
@endif
