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
        if(len>100)
        {
            $(this).text($(this).text().substr(0,120)+'...');
        }
    });
    });
</script>

@if (isset($teamcard) and $teamcard)
    @foreach ($teamcard as $member)
        <div class="col-12 col-sm-10 col-lg-6 col-lg-6 mx-auto">
            <div class="d-flex align-items-center position-relative">
                <div class="w-175 mx-auto position-absolute left-0">
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
        </div>
    @endforeach
@endif
