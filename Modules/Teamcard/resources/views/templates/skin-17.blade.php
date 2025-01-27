<?php
/*

type: layout

name: Skin-17

description: Skin-17

*/
?>

<script>
    $(document).ready(function () {
        $(".mw-big-team-bio").each(function (i) {
            var len = $(this).text().trim().length;
            if (len > 100) {
                $(this).text($(this).text().substr(0, 120) + '...');
            }
        });
    });
</script>

@if (isset($teamcard) and $teamcard)
    <div class="row">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
            <div class="col-xxl-3 col-lg-4 col-lg-6 col-12">
                <div class="team-member">
                    <div class="main-content">
                        @if ($member['file'])
                            <img loading="lazy" src="{{ thumbnail($member['file'], 800) }}" style="height: 300px;
    object-fit: cover;"/>
                        @else
                            <img loading="lazy" src="{{ asset('templates/big2/modules/teamcard/templates/default-image.svg') }}"/>
                        @endif
                        <span class="category">{{ $member['role'] }}</span>
                        <h4>{{ $member['name'] }}</h4>
                        <p class="mw-big-team-bio">{{ $member['bio'] }}</p>
                        <div class="social-icons">
                            <module type="social_links" id="teamcard-socials-{{ $params['id'] }}" template="skin-1"/>
                        </div>
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
