<?php
/*

type: layout

name: Skin-8

description: Skin-8

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

<?php if (isset($teamcard) and $teamcard): ?>

<div class="row text-center text-sm-center d-flex justify-content-center justify-content-lg-center">
    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)

        <div class="col-sm-6 col-md-4 col-lg-4 mb-3">
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

                <h5>{{ $member['name'] }}</h5>
                <p>{{ $member['role'] }}</p>
                <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
            </div>
        </div>

    @endforeach
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif
</div>

<?php endif; ?>
