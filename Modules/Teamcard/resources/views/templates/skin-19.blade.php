<?php
/*

type: layout

name: Skin-19

description: Skin-19

*/
?>

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '<?php print '#'.$params['id']; ?>').slick({
            fade: true
        });
    });

</script>

<style>
    <?php echo '#'.$params['id']; ?>
    .slick-arrow {
        bottom: 45px;
        top: unset;
        z-index: 30;
        left: unset !important;
    }

    @media screen and (max-width: 1350px) {
        <?php echo '#'.$params['id']; ?>
        .slick-arrow {
            bottom: 25px !important;
        }

        <?php echo '#'.$params['id']; ?>
        .slick-arrow:before {
            font-size: 30px !important;
        }
    }

    <?php echo '#'.$params['id']; ?>
    .slick-arrow:before {
        font-size: 40px;
    }

    <?php echo '#'.$params['id']; ?>
    .slick-prev {
        right: 135px;
    }

    <?php echo '#'.$params['id']; ?>
    .slick-next {
        right: 90px !important;
    }

    <?php echo '#'.$params['id']; ?>
    .team-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    <?php echo '#'.$params['id']; ?>
    .slick-initialized .slick-slide {
        max-height: 635px;
        min-height: 475px;
    }

    <?php echo '#'.$params['id']; ?>
    .team-thumb {
        background: var(--mw-primary-color);
        position: absolute;
        bottom: 0;
        right: 0;
        width: 65%;
        padding: 22px 32px 32px 32px;
    }



    <?php echo '#'.$params['id']; ?>
    .mw-team-19-text {
        color: var(--mw-text-on-dark-background-color);
    }

    .transition-on-hover {
        transition: all .2s ease-in-out; /* Animation */
    }

    .transition-on-hover:hover {
        transform: scale(1.1); /* (110% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>

@if (isset($teamcard) and $teamcard)
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $member)
            <div class="position-relative" style="max-height: 635px;">
                @if ($member['file'])
                    <img class="transition-on-hover team-image" loading="lazy" src="{{ thumbnail($member['file'], 1350, 1350) }}"/>
                @else
                    <img class="transition-on-hover team-image" loading="lazy" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                @endif

                <div class="team-thumb">
                    <h5 class="mw-team-19-text my-1 font-weight-bold">{{ $member['name'] }}</h5>
                    <p class="mw-team-19-text mb-0">{{ $member['role'] }}</p>
                </div>
            </div>
        @endforeach
        @else
            <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
        @endif
    </div>
@endif
