<?php
if (!$data) {
    return;
}

if (!is_array($data) or empty($data)) {
    return;
}

?>

<script>
    $(document).ready(function () {
        $('.referrers-more-info-show').on('click', function () {
            var showID = $(this).data('id');

            if ($(this).hasClass('active')) {
                $('#' + showID).slideUp();
                $(this).removeClass('active');
            } else {
                $('.referrers-more-info-show').removeClass('active');
                $('.referrers-more-info').slideUp();

                $('#' + showID).slideDown();
                $(this).addClass('active');
            }
        });

    });
</script>


<?php foreach ($data as $item): ?>
    <?php
    if (isset($item['referrer_domain_id']) and isset($item['referrer_domain']) and $item['referrer_domain_id'] != null) {
        $referrer = $item['referrer_domain'];
        $referrerIcon = '<img class="icon" src="https://favicon.microweberapi.com/' . $referrer . '" alt="">';
    } else {
        $referrer = _e('Direct', true);
        $referrerIcon = '<i class="fa fa-globe"></i>';
    }
    ?>


        <div class="nav-link fs-3 my-3" data-bs-toggle="collapse" data-bs-target="#refferers-show-more<?php print $item['id'] ?>">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-1"><?php print $referrerIcon; ?></div>
                <small class="text-muted ms-1"><a href="<?php print $referrer; ?>"><?php print $referrer; ?></a></small>
            </div>
        </div>
        <div class="collapse" id="refferers-show-more<?php print $item['id'] ?>">

            <div class="d-flex align-items-center justify-content-between" href="https://templates.microweber.com/mw/admin/shop/product ">
                <div class="col-xxl-10 col-lg-9 my-2">
                    <div class="progress progress-xs mb-2" style="width: 80%;">
                        <div class="progress-bar bg-primary" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
                    </div>
                </div>

                <div class="cnt my-2 col-xxl-2 col-lg-2 text-center">
                    <?php print $item['sessions_count']; ?>
                </div>

            </div>

            <div class="subsources">
                <?php if (isset($item['referrer_domain_id'])  and isset($item['referrer_paths'])  and $item['referrer_domain_id'] != null AND $item['referrer_paths']): ?>
                    <?php foreach ($item['referrer_paths'] as $path): ?>

                        <?php
                        if(!isset($path['referrer_url'])){
                            $path['referrer_url'] = '';
                        }
                        $ref_url_display =  $path['referrer_url'];
                        if(isset($path['referrer_path']) and $path['referrer_path']){
                            $ref_url_display =  $path['referrer_path'];
                        }
                        ?>

                        <div class="d-flex flex-wrap align-items-center justify-content-between my-3">
                            <span class="subsource-url my-2 col-xxl-10 col-lg-9"><a href="<?php print $path['referrer_url']; ?>" target="_blank"><?php print $ref_url_display; ?></a></span>
                            <span class="subsource-cnt my-2 col-xxl-2 col-lg-2 text-center"><?php print $path['path_sessions_count']; ?></span>
                            <div class="clearfix"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>
                        <span class="subsource-url my-2"><?php _e('No paths'); ?></span>
                        <span class="subsource-cnt my-2"></span>
                        <div class="clearfix"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>


<?php endforeach; ?>



