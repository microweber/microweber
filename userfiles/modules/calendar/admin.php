<?php only_admin_access(); ?>


<?php $calendar_group_id = get_option('calendar_group_id',$params['id']); ?>

<div class="module-live-edit-settings">

    <style>
        .mw-mod-template-settings-holder {
            float: right
        }

        .mw-mod-template-settings-holder label, .mw-mod-template-settings-holder select {
            float: left
        }

        .mw-mod-template-settings-holder label {
            margin-top: 11px;
            margin-right: 10px
        }
    </style>

    <script>

        mw.require('ui.css');

        mw.lib.require('jqueryui');

        mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/fullcalendar.min.css");
        mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/lib/moment.min.js");
        mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/fullcalendar.min.js");
    </script>

    <style>
        #newevent {
            cursor: move;
            width: 80px;
            text-align: center;
            margin-top: 10px
        }

        #external-events {
            margin-bottom: 10px
        }

        #trash {
            float: left;
            width: 80px;
            text-align: center
        }

        #eventContent {
            display: table;
            width: 100% !important;
        }

        #eventContent .row {
            width: 100%;
            clear: both;
            padding-bottom: 10px;
        }

        #eventContent .row label {
            width: 80px;
            vertical-align: top;
            float: left;
            display: table-column;
        }

        #eventContent .row col {
            width: auto;
            float: left;
            display: table-column;
        }

        #eventContent .row .colElement {
            width: 220px;
        }

        .ui-dialog-buttonpane .leftButton {
            float: left
        }
    </style>
    <script>

        var editModal;

        $(document).ready(function () {

//            content_id = 0;
//
//            $("#postSearch").on("postSelected", function(event, data){
//                content_id = data.id;
//            })

            var zone = '<?php echo date('P');?>';

            var currentMousePos = {
                x: -1,
                y: -1
            };

            jQuery(document).on("mousemove", function (event) {
                currentMousePos.x = event.pageX;
                currentMousePos.y = event.pageY;
            });

            /* initialize the external events
             -----------------------------------------------------------------*/

            $('#external-events .fc-event').each(function () {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });

            /* initialize the calendar
             -----------------------------------------------------------------*/

            calendar = $('#calendar').fullCalendar({
                //events for selected month are loaded in viewRender event
                //events: JSON.parse(json_events),
                // test event
                //events: [{"id":"14","title":"New Event","start":"2017-01-17T16:00:00+04:00","allDay":false}],
                utc: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                editable: true,
                droppable: true,
                slotDuration: '00:30:00',

                eventReceive: function (event) {
                    var title = event.title;
                    var start = event.start.format("YYYY-MM-DD 12:00");
                    var end = event.start.format("YYYY-MM-DD 13:00");
                    $.ajax({
                        url: '<?php print api_url('calendar_new_event');?>',
                        data: 'title=' + title + '&startdate=' + start + '&enddate=' + end + '&zone=' + zone+'&calendar_group_id=<?php print $calendar_group_id ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            event.id = response.eventid;
                            $('#calendar').fullCalendar('updateEvent', event);
                            reload_calendar_after_save();
                        },
                        error: function (e) {
                            console.log(e.responseText);
                        }
                    });
                    $('#calendar').fullCalendar('updateEvent', event);

                },

                eventDrop: function (event, delta, revertFunc) {
                    var title = event.title;
                    var start = event.start.format();
                    var end = (event.end == null) ? start : event.end.format();
                    $.ajax({
                        url: '<?php print api_url('calendar_reset_date');?>',
                        data: 'title=' + title + '&start=' + start + '&end=' + end + '&event_id=' + event.id+'&calendar_group_id=<?php print $calendar_group_id ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') $('#calendar').fullCalendar('updateEvent', event);
                            if (response.status != 'success') revertFunc();

                            reload_calendar_after_save();
                        },
                        error: function (e) {
                            revertFunc();
                            alert('Error processing your request: ' + e.responseText);
                        }
                    });
                },

                eventClick: function (event, jsEvent, view) {
                    if(typeof(event.id) != 'undefined'){
                        var data = {};
                        data.event_id = event.id;
                        editModal = mw.tools.open_module_modal('calendar/edit_event',data, {overlay:true, skin:'simple'})
                    }
                },

                eventResize: function (event, delta, revertFunc) {
                    //console.log(event);
                    var title = event.title;
                    var end = event.end.format();
                    var start = event.start.format();
                    //console.log('end: '+end);
                    $.ajax({
                        url: '<?php print api_url('calendar_reset_date');?>',
                        data: 'title=' + title + '&start=' + start + '&end=' + end + '&eventid=' + event.id + '&zone=' + zone,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') $('#calendar').fullCalendar('updateEvent', event);
                            //if(response.status != 'success') revertFunc();
                        },
                        error: function (e) {
                            revertFunc();
                            alert('Error processing your request: ' + e.responseText);
                        }
                    });
                },

                eventDragStop: function (event, jsEvent, ui, view) {
                    if (isElemOverDiv()) {
                        deleteEvent(event);
                    }
                },

                viewRender: function (view, element) {
                    // getData for selected year-month
                    getData();
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('addEventSource', json_events);
                },

                eventMouseover: function (event, element) {
                    var tooltip = '<div class="tooltipevent" style="width:auto;height:auto;background:#eee;position:absolute;z-index:10001;padding:10px 10px 10px 10px;line-height: 150%;">' + event.title + '</br>' + 'date: ' + moment(event.start).format('Do MMM') + '</br>' + 'from: ' + moment(event.start).format('h:mm a') + (event.end == null ? '' : '</br>' + 'to: ' + moment(event.end).format('h:mm a')) + (event.description == null ? '' : '</br>' + event.description) + '</div>';

                    $("body").append(tooltip);
                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $('.tooltipevent').fadeIn('500');
                        $('.tooltipevent').fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $('.tooltipevent').css('top', e.pageY + 10);
                        $('.tooltipevent').css('left', e.pageX + 20);
                    });
                },
                eventMouseout: function (data, event, view) {
                    $(this).css('z-index', 8);
                    $('.tooltipevent').remove();
                },
                dayClick: function () {
                    $('.tooltipevent').remove();
                },
                eventResizeStart: function () {
                    $('.tooltipevent').remove();
                },
                eventDragStart: function () {
                    $('.tooltipevent').remove();
                },
                viewDisplay: function () {
                    $('.tooltipevent').remove();
                }

            });

            function deleteEvent(event) {
                var con = confirm('<?php _e('Are you sure to delete this event permanently?'); ?>');
                if (con == true) {
                    $.ajax({
                        url: '<?php print api_url('calendar_remove_event');?>',
                        data: 'eventid=' + event.id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            console.log(response);
                            if (response.status == 'success') {
                                $('#calendar').fullCalendar('removeEvents', event.id);  // not reliable
                                getData();
                                $('#calendar').fullCalendar('removeEvents');
                                $('#calendar').fullCalendar('addEventSource', json_events);
                                //getFreshEvents();
                                reload_calendar_after_save();
                            }
                        },
                        error: function (e) {
                            alert('Error processing your request: ' + e.responseText);
                        }
                    });
                }
            }
            function isElemOverDiv() {
                var trashEl = jQuery('#trash');
                var ofs = trashEl.offset();
                var x1 = ofs.left;
                var x2 = ofs.left + trashEl.outerWidth(true);
                var y1 = ofs.top;
                var y2 = ofs.top + trashEl.outerHeight(true);
                if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
                    currentMousePos.y >= y1 && currentMousePos.y <= y2) {
                    return true;
                }
                return false;
            }
            $('#newevent').tooltip();
            $('#trash').tooltip();
        });
        function getData(yearmonth) {
            // getData for selected year-month
            var date = $("#calendar").fullCalendar('getDate');

            var selected_group = $('select[name="calendar_group_id"] option:selected').val();

            var y = date.year();
            var m = ("0" + (date.month() + 1)).slice(-2);
            var yearmonth = y + '-' + m;
            $.ajax({
                url: '<?php print api_url('calendar_get_events_api');?>',
                type: 'POST', // Send post data
                data: 'yearmonth=' + yearmonth+'&calendar_group_id='+selected_group,
                async: false,
                success: function (s) {
                    json_events = s;

                }
            });
        }
        function reload_calendar_after_save() {
            //mw.reload_module_parent('#<?php print $params['id'] ?>');
            window.parent.$(window.parent.document).trigger('calendar.update');
            if(typeof(editModal) != 'undefined' && editModal.modal){
                editModal.modal.remove();
            }
            getData();
        //    $("#calendar").fullCalendar( 'updateEvents', json_events )
             calendar.fullCalendar( 'refetchEvents' )
             calendar.fullCalendar( 'rerenderEvents' )
        }
    </script>

    <div id="tabsnav">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn active tabnav">Events</a>
            <a href="javascript:;" class="mw-ui-btn tabnav">Groups</a>
            <a href="javascript:;" class="mw-ui-btn tabnav">Skin/Template</a>
        </div>
        <div class="mw-ui-box">
            <div class="mw-ui-box-content tabitem">
                <?php
                // TODO:
                // if start time changed then change end time options to be after
                // if start time is set then only show options before end time
                // if end time set then only show options after start time
                $time_options = '';
                $range = range(strtotime("00:00"), strtotime("23:30"), 30 * 60);
                foreach ($range as $time) {
                    $time_options .= '<option value="' . date("H:i", $time) . '">' . date("h:i a", $time) . "</option>\n";
                }
                ?>
                <div id="external-events">
                    <p id="trash"><img data-toggle="tooltip" data-placement="auto" title="<?php _e('Remove event by dragging to dustbin'); ?>"
                                       src="<?php print $config['url_to_module']; ?>trashcan.png" alt=""></p>
                    <div style="float:left">
                        <div id="newevent" draggable="true" data-toggle="tooltip" data-placement="right" class="fc-event"
                             title="<?php _e('Create new event by dragging to calendar'); ?>"><?php _e('New Event'); ?>
                        </div>
                    </div>
                    <module type="calendar/group_select" calendar-group-module-id="<?php print $params['id'] ?>"  />
                    <div style='clear:both'></div>
                </div>
                <div id='calendar'></div>
            </div>
            <div class="mw-ui-box-content tabitem" style="display: none"><module type="calendar/edit_groups"  /> </div>
            <div class="mw-ui-box-content tabitem" style="display: none">        <module type="admin/modules/templates" simple="true"/>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            mw.tabs({
                nav:'#tabsnav  .tabnav',
                tabs:'#tabsnav .tabitem'
            });
        });
    </script>
</div>