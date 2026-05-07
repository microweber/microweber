<?php
/*

type: layout

name: Skin-2

description: Skin-2

*/
?>

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick();
    });
</script>

<style>
    /* audit-test 2026-05-07 PM TICKET-AV bundle: was background-size/position
       on a div, now object-fit on inner <img>. aspect-ratio:1 keeps the square
       shape that padding-top:100% used to give. */
    .team-card-item-image {
        aspect-ratio: 1 / 1;
        overflow: hidden;
    }
    .team-card-item-image > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    <?php echo '#'.$params['id']; ?>
    .slick-dots {
        position: relative;
        height: 100%;
        display: flex;
        flex-flow: column;
        bottom: 0;
    }

    @media screen and (max-width: 991px) {
        <?php echo '#'.$params['id']; ?>
        .slick-dots {
            display: block;
        }
    }
</style>

<div class="team-card-holder d-flex flex-wrap">
    @php
        $count = 0;
    @endphp

    @if ($teamcard->count() > 0)
        <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": true, "appendDots": ".slick-paging", "vertical" : true, "verticalSwiping" : true, "arrows": false}'>

            @foreach ($teamcard as $member)
                @php
                    $count++;
                @endphp
                <div class="team-card-item col-md-6 col-12 mb-3 overflow-hidden text-start my-5 d-flex flex-wrap">
                    <div class="col-md-6 pe-2">
                        @if ($member['file'])
                            {{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>`
                                 to real `<img>` (closes CSS-injection vector + adds alt + lazy). --}}
                            <div class="team-card-item-image">
                                <img src="{{ thumbnail($member['file'], 800) }}"
                                     alt="{{ $member['name'] ?? __('Team member') }}"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                        @else
                            <div class="rounded-circle">
                                <img width="300" height="300" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}" alt="{{ $member['name'] ?? __('Team member') }}" class="img-fluid"/>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 ps-2">
                        <h3 class="team-card-item-name">
                            {{$member['name']}}
                        </h3>
                        <p class="team-card-item-position">
                            {{$member['role']}}
                        </p>
                        {{-- task-2026-05-05-90021f — rel=noopener noreferrer for security --}}
                        <a class="d-block mb-3" href="{{ $member['website'] }}" target="_blank" rel="noopener noreferrer">
                            {{$member['website']}}
                        </a>
                        <p class="team-card-item-bio italic">
                            {{$member['bio']}}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif
</div>
