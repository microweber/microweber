<?php
/*

type: layout

name: Skin-6

description: Skin-6

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

<div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="d-block d-sm-flex align-items-center h-100">
                <div class="me-sm-4 mb-5 mb-sm-0 mx-auto mx-sm-0">
                    <div class="w-175 h-200 mx-auto">
                        @if ($member['file'])
                            <div class="img-as-background square">
                                <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}"/>
                            </div>
                        @else
                            <div class="img-as-background square">
                                <img loading="lazy" src="{{ asset('templates/big2/modules/teamcard/templates/default-image.svg') }}"/>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ms-3">
                    <h4 class="mb-1">{{ $member['name'] }}</h4>
                    <p class="mb-3">{{ $member['role'] }}</p>
                    <p class="mw-big-team-bio mb-1">{{ $member['bio'] }}</p>

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
<?php endif; ?>
