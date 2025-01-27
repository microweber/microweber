<?php
/*

type: layout

name: Skin-4

description: Skin-4

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
<div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
            <div class="col-sm-6 col-md-4 col-lg-4 mb-8">
                <div class="d-block position-relative show-on-hover-root">
                    @if ($member['file'])
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}"/>
                        </div>
                    @else
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big2/modules/teamcard/templates/default-image.svg') }}"/>
                        </div>
                    @endif

                    <div class="show-on-hover position-absolute bg-body border border-color-primary mh-400 w-100 top-0 mb-3 p-5">
                        <i class="mdi mdi-format-quote-close icon-size-46px"></i>
                        <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
                    </div>

                    <div>
                        <h4 class="mb-1">{{ $member['name'] }}</h4>
                        <p class="mb-3">{{ $member['role'] }}</p>
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
