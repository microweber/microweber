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

<ul class="">
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
    <li class="nav-item referrers-more-info-show" data-id="referrers-more-<?php print $item['id']; ?>">
        <a href=" https://templates.microweber.com/mw/admin/customers " class="nav-link fs-3 dropdown-toggle show" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
           Open
        </a>
        <div class="referrers-more-info" id="referrers-more-<?php print $item['id']; ?>">
            <a href=" https://templates.microweber.com/mw/admin/shop/product " class="dropdown-item justify-content-between ">
                <div class="progress progress-xs my-2" style="width: 80%;">
                    <div class="progress-bar bg-primary" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
                </div>

                <span class="arrow"></span>
                <div class="icon-wrapper"><?php print $referrerIcon; ?></div>
                <div class="source-name"><span><?php print $referrer; ?></span></div>
                <div class="cnt"><?php print $item['sessions_count']; ?></div>

            </a>

            <ul class="subsources">
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



                        <li>
                            <span class="subsource-url"><a href="<?php print $path['referrer_url']; ?>" target="_blank"><?php print $ref_url_display; ?></a></span>
                            <span class="subsource-cnt"><?php print $path['path_sessions_count']; ?></span>
                            <div class="clearfix"></div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>
                        <span class="subsource-url"><?php _e('No paths'); ?></span>
                        <span class="subsource-cnt"></span>
                        <div class="clearfix"></div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </li>
    <?php endforeach; ?>
</ul>

