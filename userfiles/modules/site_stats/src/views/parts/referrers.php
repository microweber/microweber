<?php
if (!$data) {
    return;
}
?>

<ul class="">
    <?php foreach ($data as $item): ?>
        <?php
        if ($item['referrer_domain_id'] != null) {
            $referrer = $item['referrer_domain'];
            $referrerIcon = '<img class="icon" src="https://favicon.microweberapi.com/' . $referrer . '" alt="">';
        } else {
            $referrer = _e('Direct', true);
            $referrerIcon = '<i class="fa fa-globe"></i>';
        }
        ?>
        <li class="source">
            <a href="javascript:;" class="source-title">
                <div class="source-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>

                <span class="arrow"></span>
                <div class="icon-wrapper"><?php print $referrerIcon; ?></div>
                <div class="source-name"><span><?php print $referrer; ?></span></div>
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </a>

            <ul class="subsources">
                <?php if ($item['referrer_domain_id'] != null AND $item['referrer_paths']): ?>

                    <?php foreach ($item['referrer_paths'] as $path): ?>
                        <li>
                            <span class="subsource-url"><a href="<?php print $path['referrer_url']; ?>" target="_blank"><?php print $path['referrer_path']; ?></a></span>
                            <span class="subsource-cnt"><?php print $path['path_sessions_count']; ?></span>
                            <div class="clearfix"></div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>
                        <span class="subsource-url"><?php print _e('No paths'); ?></span>
                        <span class="subsource-cnt"></span>
                        <div class="clearfix"></div>
                    </li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>