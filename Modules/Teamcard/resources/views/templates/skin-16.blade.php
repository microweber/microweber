<?php
/*

type: layout

name: Skin-16

description: Skin-16

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
                        <div style="width: 100px;">
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

                    <div>
                        <h6>{{ $member['name'] }}</h6>
                        <p>{{ $member['role'] }}</p>
                        <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
                    </div>

                    <div class="mt-3">
                        <module type="social_links" id="teamcard-socials-{{ $params['id'] }}" template="skin-1"/>
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
