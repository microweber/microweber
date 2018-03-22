<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>
    <div class="item content">
        <div class="content-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
        <div class="mw-ui-row">
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
                <div class="slug"><?php print $item['url_slug']; ?></div>
            </div>

            <div class=" mw-ui-col" style="width:30px;">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>