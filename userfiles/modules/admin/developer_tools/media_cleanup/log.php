<?php

$job = mw('admin\developer_tools\media_cleanup\Worker')->run();

if (is_array($job) and !empty($job) and isset($job['remaining'])) {


    $total = $remaining = count($job);

    $perc = 100 - mw()->format->percent($remaining, $total);

    ?>
    <div class="mw-ui-progress" id="resore-progress">
        <div class="mw-ui-progress-bar" style="width: <?php print  $perc; ?>%;min-width:100px;"></div>
        <div class="mw-ui-progress-info">Progress</div>
        <span class="mw-ui-progress-percent"><?php print  $perc; ?>%</span>
    </div>

<?php
} else if (is_array($job) and !empty($job) and isset($job['success'])) {
    print $job['success'];
}
