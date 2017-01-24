<?php

/*

type: layout

name: Default

description: Calendar Default

*/

?>

<script>
	 mw.lib.require('jqueryui'); // hyphen removed due to mw bug
     mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/fullcalendar.min.css");
     mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/lib/moment.min.js");
     mw.require("<?php print $config['url_to_module'];?>fullcalendar-3.1.0/fullcalendar.min.js");
</script>

<style type="text/css">

	#wrap {
		width: 100%;
		margin: 0 auto;
	}

	#calendar {
		float: right;
		width: 100%;
	}

	.fc-event{
		cursor: pointer;
	}
</style>

<script>

	$(document).ready(function() {

		var zone = "05:30";  //Change this to your timezone

		var currentMousePos = {
			x: -1,
			y: -1
		};
		jQuery(document).on("mousemove", function (event) {
        	currentMousePos.x = event.pageX;
        	currentMousePos.y = event.pageY;
    	});


		/* initialize the calendar
		-----------------------------------------------------------------*/

		$('#calendar').fullCalendar({
			//events for selected month are loaded in viewRender event
			//events: JSON.parse(json_events),
			//test event
			//events: [{"id":"14","title":"New Event","start":"2017-01-24T16:00:00+04:00","allDay":false}],
			utc: true,
			header: {
				left: 'prev,next today',
				center: 'title',
                right: 'month,agendaWeek,agendaDay,listMonth' // other views: listWeek, basicWeek, basicDay, agendaWeek, agendaDay, listYear, listWeek, listDay
			},
			editable: false,
			droppable: false,

			eventMouseover: function(event, element) {
				// div causes jquery to display in non-live edit
				var tooltip = '<p class="tooltipevent" style="max-width:400px;width:auto;height:auto;background:#eee;position:absolute;z-index:10001;padding:10px 10px 10px 10px;line-height: 150%;">' + event.title + '<br />' + 'date: ' + moment(event.start).format('Do MMM') + '<br />' + 'from: ' + moment(event.start).format('h:mm A') + (event.end == null?'':'<br />' + 'to: ' + moment(event.end).format('h:mm A'))  + (event.description == null?'':'<br />' + event.description) + '</p>';
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
			viewDisplay: function () {
				$('.tooltipevent').remove();
        	},

			viewRender: function (view, element) {
				// getData for selected year-month
				getData();
				$('#calendar').fullCalendar('removeEvents');
				$('#calendar').fullCalendar('addEventSource',JSON.parse(json_events));
 			},
		});

		function getData(){
			var date = $("#calendar").fullCalendar('getDate');
			var y = date.year();
			var m = ("0" + (date.month() + 1)).slice(-2);
			var yearmonth = y+'-'+m;
			$.ajax({
				url: '<?php print api_url('get_events'); ?>',
				type: 'POST', // Send post data
				data: 'yearmonth='+yearmonth,
				async: false,
				success: function(s){
					json_events = s;
				}
			});
		}

	});

</script>

<div id="eventContent" title="Event Details" style="display:none;">
    Start: <span id="startTime"></span><br>
    End: <span id="endTime"></span><br><br>
    <p id="eventInfo"></p>
</div>

<div class="mw-calendar mw-calendar-default">
		<div id='calendar'></div>
</div>
