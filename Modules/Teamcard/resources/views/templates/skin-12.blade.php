<?php
/*

type: layout

name: Skin-12

description: Skin-12

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
    <div class="row text-start text-sm-start d-flex justify-content-center justify-content-lg-between">
        @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
            <div class="col-sm-12 mx-auto">
                <div class="d-block d-sm-flex align-items-center">
                    <div class="my-4 me-md-5 d-flex justify-content-center position-relative">
                        <div class="w-250">
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

                    <div class="position-relative ps-5 py-3">
                        <div class="border-end position-absolute h-100 left-0 top-0 d-none d-sm-block"></div>

                        <h4>{{ $member['name'] }}</h4>
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
