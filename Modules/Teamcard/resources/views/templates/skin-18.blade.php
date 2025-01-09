<?php
/*

type: layout

name: Skin-18

description: Skin-18

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


<style>

    .mw-team-18-job-role, .mw-team-18-team-title {
        font-size: 17px;
        font-weight: 500;
        margin-bottom: 0;
    }

    .mw-team-18-team-title {
        font-size: 21px;
        font-weight: 600;
        line-height: 1;
        margin-top: 0;
        text-transform: capitalize;
    }

    .team-name-center {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .mw-team-18-team-grid {
        column-gap: 30px;
        display: grid;
        grid-auto-columns: 1fr;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: auto;
        row-gap: 30px;
    }

    .photo-line-team {
        border-radius: 30px;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        overflow: hidden;
        position: relative;
    }

    .social-content.center {
        justify-content: center;
    }

    @media screen and (max-width: 991px) {
        .mw-team-18-team-grid {
            column-gap: 15px;
            row-gap: 15px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-team-18-team-grid {
            column-gap: 25px;
            grid-template-columns: 1fr 1fr;
            row-gap: 25px;
        }
    }

    @media screen and (max-width: 479px) {
        .social-content {
            display: none;
        }

        .mw-team-18-team-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php if (isset($teamcard) and $teamcard): ?>

    <div class="mw-team-18-team-grid">
        <?php foreach ($teamcard as  $member): ?>
            <div class="mw-team-18-team-wrapper">
                <div class="mw-team-18-team-member">
                    <div class="photo-line-team">

                        <?php if ($slide['file']) { ?>
                            <img loading="lazy" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                        <?php } else { ?>
                            <img loading="lazy"
                                 src="<?php print asset('templates/big2/modules/teamcard/templates/default-image.svg'); ?>"/>
                        <?php } ?>
                    </div>

                    <div class="team-name-center my-4">
                        <h2 class="mw-team-18-team-title"> <?php print array_get($slide, 'name'); ?> </h2>
                        <p class="mw-team-18-job-role my-3"><?php print array_get($slide, 'role'); ?></p>
                        <p class="mw-big-team-bio col-xl-8 text-center"><?php print array_get($slide, 'bio'); ?></p>

                        <module class="d-flex justify-content-center" type="social_links" id="teamcard-socials-{{ $params['id'] }}"
                                template="skin-6"/>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
