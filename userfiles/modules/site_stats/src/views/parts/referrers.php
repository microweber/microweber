<?php
if (!$data) {
    return;
}

if (!is_array($data) or empty($data)) {
    return;
}

?>

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
    <li class="nav-item dropdown">
        <a href=" https://templates.microweber.com/mw/admin/customers " class="nav-link fs-3 dropdown-toggle show" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
           Open
        </a>
        <div class="dropdown-menu" data-bs-popper="static">
            <div class="dropdown-menu-columns">
                <div class="dropdown-menu-column">
                    <a href=" https://templates.microweber.com/mw/admin/shop/product " class="dropdown-item justify-content-between ">
                        <div class="source-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>

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
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>

