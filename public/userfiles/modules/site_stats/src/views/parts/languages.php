<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>
    <div class="item location my-3">
        <div class="location-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
        <div class="d-flex align-items-center flex-wrap">
            <div class="col-xxl-10 col-lg-9">
                <div class="title"><?php print $item['language']; ?></div>
            </div>

            <div class="col-xxl-2 col-lg-2 text-center">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>

        <div>
            <div class="progress progress-xs my-2" style="width: 80%;">
                <div class="progress-bar bg-primary" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
