<?php
$date = date('Y-m-d');
if (isset($params['data-date'])) {
    $date = $params['data-date'];
}
//$events = calendar_get_events('yearmonth=' . $date);




$events = calendar_get_events_api('date=' . $date);

?>

<?php if ($events): ?>


    <?php foreach ($events as $event): ?>
        <?php
        $event_link = $event['link_url'];

        if ($event_link == '' AND $event['content_id'] != 0) {
            $event_link = content_link($event['content_id']);
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="col-sm-4">
                    <img src="<?php print thumbnail($event['image_url'], 200); ?>"/>
                </div>
                <div class="col-sm-8">
                    <h5><?php print $event['start_time'] . 'h - ' . $event['end_time'] . 'h'; ?>, <?php print $event['start_date'] . ' - ' . $event['end_date']; ?>, <?php print $event['short_description']; ?></h5>
                    <h3><?php print $event['title']; ?></h3>
                     <div>
                        <p><?php print $event['description']; ?></p>
                        <?php if ($event_link): ?>
                            <a href="<?php print $event_link; ?>">View details</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr/>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    no events for <?php print $date; ?>
<?php endif; ?>
