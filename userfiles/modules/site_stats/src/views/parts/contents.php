<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>

    <?php

    if (isset($item['url']) and $item['url'] != false) {
        $url = $item['url'];
    } else {
        $url = 'javascript:;';
    }
    ?>
    <div class="item content">
        <div class="mw-ui-row-nodrop info-holder">
            <div class="mw-ui-col">
                <div class="title">
                    <?php
                    if (isset($item['content_title'])) {
                        echo $item['content_title'];
                    } else {
                        echo 'Undefined';
                    }
                    ?>
                </div>
                <div class="slug"><a href="<?php print $url; ?>" target="_blank" rel="noreferrer noopener"><?php print $item['url_slug']; ?></a></div>
            </div>

            <div class=" mw-ui-col" style="width:30px;">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>

        <div class="content-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
    </div>
<?php endforeach; ?>