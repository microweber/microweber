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
        <div class="d-flex flex-wrap align-items-center justify-content-between info-holder my-3">
            <div class="col-xxl-10 col-lg-9">
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

            <div class="col-xxl-2 col-lg-2 text-center">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>

        <div class="progress progress-xs my-2" style="width: 80%;">
            <div class="progress-bar bg-primary" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
        </div>
    </div>
<?php endforeach; ?>
