<?php

/*

type: layout

name: Events List

description: Calendar Events List

*/

?>

<?php
$mod_id = $params['id'];
$mod_suffix = md5($params['id']);
?>

<script>
    $(document).ready(function () {
        mw.lib.require('bootstrap_datepicker');

        $('.mw-events-calendar').datepicker().on('changeDate', function (event) {
            //console.log(event.format('yyyy-mm-dd'));
            $('.mw-events-list-selected-date').attr('data-date', event.format('yyyy-mm-dd'));
            mw.reload_module('.mw-events-list-selected-date')
        });


    });

</script>

<div class="row">
    <div class="col-sm-3">
        <div class="mw-events-calendar"></div>
    </div>
    <div class="col-sm-9">
        <module class="mw-events-list-selected-date" type="calendar/templates/events_list/list" template="events_list/list" id="inner-calendar-<?php print $params['id'] ?>" />
    </div>
</div>




