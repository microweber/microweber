<?php
/*

type: layout

name: Agenda

description: Calendar Agenda

*/
?>

<?php
$mod_id = $params['id'];
$mod_suffix = md5($params['id']);

$dayGroups = calendar_get_events_groups_api();

$groups = calendar_get_groups();
?>

<!--<link rel="stylesheet" type="text/css" href="--><?php //print $config['url_to_module'] ?><!--css/reset.css"/>-->
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/style.css"/>

<div id="calendar-<?php echo $mod_suffix; ?>-tabsnav">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <?php foreach($dayGroups as $g => $dayGroup): ?>
        <a href="javascript:;" class="mw-ui-btn tabnav <?php echo $g==0 ? 'active' : ''; ?>"><?php echo $dayGroup; ?></a>
        <?php endforeach; ?>
    </div>
    <div class="mw-ui-box">
        <?php foreach($dayGroups as $g => $dayGroup): ?>
        <div class="mw-ui-box-content tabitem" <?php echo $g==0 ? '' : 'style="display: none"'; ?>>
            <div class="cd-schedule loading">
                <div class="timeline">
                    <ul>
                        <li><span>09:00</span></li>
                        <li><span>09:30</span></li>
                        <li><span>10:00</span></li>
                        <li><span>10:30</span></li>
                        <li><span>11:00</span></li>
                        <li><span>11:30</span></li>
                        <li><span>12:00</span></li>
                        <li><span>12:30</span></li>
                        <li><span>13:00</span></li>
                        <li><span>13:30</span></li>
                        <li><span>14:00</span></li>
                        <li><span>14:30</span></li>
                        <li><span>15:00</span></li>
                        <li><span>15:30</span></li>
                        <li><span>16:00</span></li>
                        <li><span>16:30</span></li>
                        <li><span>17:00</span></li>
                        <li><span>17:30</span></li>
                        <li><span>18:00</span></li>
                    </ul>
                </div> <!-- .timeline -->

                <div class="events">
                    <ul>
                        <?php if($groups): ?>
                        <?php foreach($groups as $group): ?>
                            <li class="events-group">
                                <div class="top-info"><span><?php echo $group['title']; ?></span></div>

                                <ul>
                                    <?php $events = calendar_get_events_by_group(array('date' => $dayGroup, 'calendar_group_id' => $group['id'])); ?>
                                    <?php foreach($events as $e => $event): ?>
                                        <li class="single-event" data-start="<?php echo date('H:i', strtotime($event['start'])); ?>" data-end="<?php echo date('H:i', strtotime($event['end'])); ?>" data-content="event-abs-circuit" data-event="event-<?php echo 1 + ($e % 4); ?>">
                                            <a href="#0">
                                                <em class="event-name"><?php echo $event['title']; ?></em>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="event-modal">
                    <header class="cd-header">
                        <div class="cd-content">
                            <span class="event-date"></span>
                            <h3 class="event-name"></h3>
                        </div>

                        <div class="header-bg"></div>
                    </header>

                    <div class="cd-body">
                        <div class="event-info"></div>
                        <div class="body-bg"></div>
                    </div>

                    <a href="#0" class="close">Close</a>
                </div>

                <div class="cover-layer"></div>
            </div> <!-- .cd-schedule -->
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?php print $config['url_to_module'] ?>js/modernizr.js"></script>
<script src="<?php print $config['url_to_module'] ?>js/main.js"></script>
<script>mw.lib.require('bootstrap3ns');</script>
<script>
$(document).ready(function(){
    mw.tabs({
        nav: '#calendar-<?php echo $mod_suffix; ?>-tabsnav .tabnav',
        tabs: '#calendar-<?php echo $mod_suffix; ?>-tabsnav .tabitem'
    });
});
</script>
