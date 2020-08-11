<?php

use Doctrine\Common\Collections\ArrayCollection;

require_once(__DIR__ . '/src/CalendarManager.php');
require_once(__DIR__ . '/src/CalendarDatesHelper.php');

function calendar_module_get_config()
{
    return require_once(__DIR__ . '/config.php');
}

function calendar_get_events($params = [])
{
    return (new CalendarManager())->get_events($params);
}

api_expose_admin('calendar_save_event');
function calendar_save_event($params = [])
{
    return (new CalendarManager())->save_event($params);
}

api_expose_admin('calendar_export_to_csv_file', function ($params) {
    $data = calendar_get_events('no_limit=true');

    if (!$data) {
        return ['error' => 'You do not have any events'];
    }

    $allowed = [
        'id', 'created_at', 'created_at', 'content_id', 'title', 'startdate', 'enddate',
        'description', 'short_description', 'allDay', 'calendar_group_id', 'calendar_group_name', 'image_url', 'link_url', 'allDay',
    ];

    $export = [];

    foreach ($data as $item) {
        foreach ($item as $key => $value) {
            if (!in_array($key, $allowed)) {
                unset($item[$key]);
            }
        }
        $export[] = $item;
    }

    if (empty($export)) {
        return;
    }

    $filename = 'events' . '_' . date('Y-m-d_H-i', time()) . uniqid() . '.csv';
    $filename_path = userfiles_path() . 'export' . DS . 'events' . DS;
    $filename_path_index = userfiles_path() . 'export' . DS . 'events' . DS . 'index.php';

    if (!is_dir($filename_path)) {
        mkdir_recursive($filename_path);
    }

    if (!is_file($filename_path_index)) {
        @touch($filename_path_index);
    }

    $filename_path_full = $filename_path . $filename;

    $csv = \League\Csv\Writer::createFromPath($filename_path_full, 'w'); //to work make sure you have the write permission
    $csv->setEncodingFrom('UTF-8'); // this probably is not needed?

    $csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8); //adding the BOM sequence on output

    //we insert the CSV header
    $csv->insertOne(array_keys(reset($export)));

    $csv->insertAll($export);

    return response()->download($filename_path_full)->deleteFileAfterSend(true);
});


api_expose_admin('calendar_import_by_csv', function ($params) {
    if (Input::hasFile('csv_file')) {
        $file = Input::file('csv_file');

        $path = $file->getRealPath();


        $file = fopen($path, 'r') or die('Unable to open file!');

        $returnVal = array();
        $header = null;

        while (($row = fgetcsv($file, 1000, ",", '"')) !== false) {
            if ($header === null) {
                $header = $row;
                continue;
            }

            $newRow = array();
            for ($i = 0; $i < count($row); $i++) {
                $newRow[$header[$i]] = $row[$i];
            }

            $returnVal[] = $newRow;
        }

        fclose($file);

        $csv_data = $returnVal;


        if (!$csv_data) {
            return (['error' => 'Cannot parse the CSV file']);
        }


        $save_count = 0;
        $collect = [];

        if ($csv_data) {
            $header = [];
            foreach ($csv_data as $event) {

                $save = $event;


                if ($save) {
                    if (isset($save['startdate']) and isset($save['enddate'])) {

                        $save['startdate'] = str_replace('/', '-', $save['startdate']);
                        $save['enddate'] = str_replace('/', '-', $save['enddate']);


                        $collect[] = $save;
                    }
                }
            }
        }

        if ($collect) {
            \DB::table('calendar')->truncate();

            foreach ($collect as $save) {
                $save['id'] = 0;
                calendar_save_event($save);
                $save_count++;
            }
        }

        if (is_file($path)) {
            @unlink($path);
        }

        return (['success' => $save_count . ' items were imported']);
    }
});


function calendar_get_events_by_group($params = [])
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

    $calendar_group_id = 0;

    if (isset($params['calendar_group_id'])) {
        $calendar_group_id = intval($params['calendar_group_id']);
    }

    $date = date("Y-m-d");
    if (isset($params['date'])) {
        $date = $params['date'];
    } elseif (isset($_POST['date'])) {
        $date = $_POST['date'];
    }

    $params['table'] = "calendar";
    $params['no_cache'] = true; // disable cache whilst testing

    $events = [];

    if ($data = DB::table($params['table'])->select('id', 'title', 'description', 'start_date', 'end_date', 'all_day', 'content_id', 'calendar_group_id', 'image_url', 'link_url')
        ->where('start_date', '>', $date . ' 00:00:00')
        ->where('start_date', '<', $date . ' 23:59:59')
        ->where('calendar_group_id', $calendar_group_id)
        ->orderBy('start_date')
        ->get()
    ) {
        foreach ($data as $event) {
            if (!empty($event->id) && !empty($event->title) && !empty($event->start_date)) {
                $e = (array)$event;


//                $e['id'] = $event->id;
//                $e['title'] = $event->title;
//                $e['description'] = $event->description;
//                $e['start'] = $event->start_date;
//                $e['end'] = $event->end_date;
//                $e['all_day'] = ((isset($event->all_day) and ($event->all_day)) == 1 ? true : false);
//                $e['content_id'] = ($event->content_id);
//                $e['image_url'] = ($event->image_url);
//                $e['link_url'] = ($event->link_url);
                array_push($events, $e);
            }
        }

        return $events;
    } else {
        // no event data
        if (is_ajax()) {
            return print lnotif(_e('Click here to edit Calendar', true));
        }
    }
}

api_expose('calendar_get_events_groups_api');

function calendar_get_events_groups_api($params = [])
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

    $yearmonth = date("Y-m");
    if (isset($params['yearmonth'])) {
        $yearmonth = $params['yearmonth'];
    } elseif (isset($_POST['yearmonth'])) {
        $yearmonth = $_POST['yearmonth'];
    }

    $params['table'] = "calendar";
    $params['no_cache'] = true; // disable cache whilst testing

    $groups = [];
    $data = DB::table($params['table'])->select('id', 'start_date');

    if ($yearmonth) {
        $data = $data->where('start_date', 'like', $yearmonth . '%');
    }

    $data = $data->groupBy(DB::raw('DATE(start_date)'))->get();

    if ($data) {
        foreach ($data as $group) {
            $start = $group->start_date;
            $groups[] = date('Y-m-d', strtotime($start));
        }
        return $groups;
    } else {
        if (is_ajax()) {
            // no event data
            return print lnotif(_e('Click here to edit Calendar', true));
        }
    }
}

function calendar_get_events_days($params = [])
{
    calendar_get_events_api($params);
}

api_expose('calendar_get_events_api');
function calendar_get_events_api($params = [])
{
    $timeZone = date_default_timezone_get();

    if (is_string($params)) {
        $params = parse_params($params);
    }
    $dates_helper = new CalendarDatesHelper();

    $calendar_group_id = 0;

    if (isset($params['calendar_group_id'])) {
        $calendar_group_id = intval($params['calendar_group_id']);
    }

    $month = date("m");
    $year = date("Y");


    $selected_date = false;

    // Set up Month
    if (isset($params['month'])) {
        $month = $params['month'];
    } elseif (isset($_POST['month'])) {
        $month = $_POST['month'];
    }


    if (isset($params['date'])) {
        $selected_date = $params['date'];
    }


    // Set up Year
    if (isset($params['year'])) {
        $year = $params['year'];
    } elseif (isset($_POST['year'])) {
        $year = $_POST['year'];
    }

    $params['table'] = "calendar";
    $params['no_cache'] = true; // disable cache whilst testing

    $events = [];
    $findEvents = DB::table($params['table'])
        // ->whereRaw('DATE_FORMAT(start_date,"%m") = ?', $month)
        ->where('calendar_group_id', $calendar_group_id)
        ->where('active', 1)
        ->get();


    if ($findEvents) {

        foreach ($findEvents as $event) {

            //  && ! empty($event->title)
            if (!empty($event->id) && !empty($event->start_date)) {

                // Example event
                $eventReady = [];
                $eventReady = (array)$event;

                $eventReady['id'] = $event->id;
                $eventReady['title'] = $event->title;
                $eventReady['description'] = $event->description;

                $eventReady['all_day'] = ((isset($event->all_day) and ($event->all_day)) == 1 ? true : false);
                $eventReady['content_id'] = ($event->content_id);
                $eventReady['calendar_group_id'] = ($event->calendar_group_id);
                $eventReady['image_url'] = ($event->image_url);
                $eventReady['link_url'] = ($event->link_url);

                // Example event dates
                $startDate = $event->start_date;
                $endDate = $event->end_date;

                $startTime = $event->start_time;
                $endTime = $event->end_time;

                $recurrenceRepeatOn = json_decode($event->recurrence_repeat_on, TRUE);

                if ($event->recurrence_type == "weekly_on_all_days" || $event->recurrence_type == "every_weekday") {
                    $event->recurrence_type = "custom";
                }

                if ($event->recurrence_type == "custom" && $event->recurrence_repeat_type == "year") {
                    $event->recurrence_type = 'annually_on_the_month_name_day_number';
                }

                if ($event->recurrence_type == "custom" && $event->recurrence_repeat_type == "month") {
                    $event->recurrence_type = 'monthly_on_the_week_number_day_name';
                }

                if ($event->recurrence_type == "weekly_on_the_day_name") {
                    $event->recurrence_type = "custom";
                    $event->recurrence_repeat_type = "week";
                }

                if ($event->recurrence_type == "doesnt_repeat") {

                    if ($event->all_day == 1) {
                        $eventReady['start'] = $startDate;
                        //         $eventReady['start_time'] = $startDate;
                        $eventReady['end'] = $endDate;
                    } else {
                        $eventReady['start'] = $startDate . " " . $startTime;
                        //     $eventReady['start_time'] = $startDate . " " . $startTime;
                        $eventReady['end'] = $endDate . " " . $endTime;
                    }

                    $events[] = $eventReady;
                }

                if ($event->recurrence_type == "annually_on_the_month_name_day_number") {

                    // For Every Year Repeating
                    if ($event->all_day == 1) {
                        $startDate = $year . date('-m-d', strtotime($event->start_date));
                        $endDate = $year . date('-m-d', strtotime($event->end_date));
                    } else {
                        $startDate = $year . date('-m-d H:i:s', strtotime($event->start_date));
                        $endDate = $year . date('-m-d H:i:s', strtotime($event->end_date));
                    }

                    $eventReady['start'] = $startDate;
                    $eventReady['end'] = $endDate;
                    $events[] = $eventReady;
                }

                if ($event->recurrence_type == "monthly_on_the_day_number" || $event->recurrence_type == "monthly_on_the_week_number_day_name") {

                    $currentMonth = $year . '-' . $month . '-01 ';
                    $nextMonth = date('m', strtotime("+1 month", strtotime($year . '-' . $month)));
                    $nextMonth = $year . '-' . $nextMonth . '-01 ';

                    $previousMonth = date('m', strtotime("-1 month", strtotime($year . '-' . $month)));
                    $previousMonth = $year . '-' . $previousMonth . '-01 ';

                    $datesOfTheCurrentMonth = $dates_helper->getDatesOfMonth($timeZone, $currentMonth);
                    $datesOfTheNextMonth = $dates_helper->getDatesOfMonth($timeZone, $nextMonth);
                    $datesOfThePreviousMonth = $dates_helper->getDatesOfMonth($timeZone, $previousMonth);


                    $datesOfTheMonthCombine = new ArrayCollection(
                        array_merge($datesOfTheNextMonth->toArray(), $datesOfThePreviousMonth->toArray())
                    );

                    $datesOfTheMonthAll = new ArrayCollection(
                        array_merge($datesOfTheCurrentMonth->toArray(), $datesOfTheMonthCombine->toArray())
                    );

                    foreach ($datesOfTheMonthAll as $dateOfTheMonth) {

                        $startDateReady = $dateOfTheMonth->getStart()->format('Y-m-d');
                        if (date("Y-m-d", strtotime($startDate)) > $startDateReady) {
                            continue;
                        }

                        if ($event->recurrence_type == "monthly_on_the_day_number") {
                            $dayNumber = date('d', strtotime($event->start_date));
                            if ($dayNumber !== $dateOfTheMonth->getStart()->format('d')) {
                                continue;
                            }
                        }

                        if ($event->recurrence_type == "monthly_on_the_week_number_day_name") {
                            $dayName = date('l', strtotime($startDate));
                            if ($dayName !== $dateOfTheMonth->getStart()->format('l')) {
                                continue;
                            }

                            if ($dates_helper->getWeekOfMonth($startDateReady) !== $dates_helper->getWeekOfMonth($startDate)) {
                                continue;
                            }
                        }

                        if ($event->all_day == 1) {
                            $eventReady['start'] = $startDateReady;
                            $eventReady['end'] = $startDateReady;
                        } else {
                            $eventReady['start'] = $startDateReady . " " . $startTime;
                            $eventReady['end'] = $startDateReady . " " . $endTime;
                        }

                        $events[] = $eventReady;
                    }

                }

                if ($event->recurrence_type == "daily") {

                    $selectedStartDateAndTime = $year . '-' . $month . '-01 ';
                    $datesOfTheMonth = $dates_helper->getDatesOfMonth($timeZone, $selectedStartDateAndTime);

                    foreach ($datesOfTheMonth as $dateOfTheMonth) {

                        $startDateReady = $dateOfTheMonth->getStart()->format('Y-m-d');

                        if (date("Y-m-d", strtotime($startDate)) > $startDateReady) {
                            continue;
                        }

                        if ($event->all_day == 1) {
                            $eventReady['start'] = $startDateReady;
                            $eventReady['end'] = $startDateReady;
                        } else {
                            $eventReady['start'] = $startDateReady . " " . $startTime;
                            $eventReady['end'] = $startDateReady . " " . $endTime;
                        }

                        $events[] = $eventReady;
                    }


                }

                if ($event->recurrence_type == "custom") {

                    if ($event->recurrence_repeat_type == "week") {
                        if (!empty($recurrenceRepeatOn)) {
                            $eventRecurrences = get_generated_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $year, $month);

                            //   dd($eventRecurrences);

                            foreach ($eventRecurrences as $eventRecurrence) {
                                $eventReady['start'] = $eventRecurrence['start'];
                                $eventReady['end'] = $eventRecurrence['end'];
                                $events[] = $eventReady;
                            }
                        }
                    } else if ($event->recurrence_repeat_type == "day") {

                        $selectedStartDateAndTime = $year . '-' . $month . '-01';
                        if ($event->all_day !== 1) {
                            $selectedStartDateAndTime .= $startTime;
                        }

                        $datesOfTheMonth = $dates_helper->getDatesOfMonthWithInterval($timeZone, $selectedStartDateAndTime, $event->recurrence_repeat_every);

                        foreach ($datesOfTheMonth as $dateOfTheMonth) {

                            $startDateReady = $dateOfTheMonth->getStart()->format('Y-m-d');
                            if (date("Y-m-d", strtotime($startDate)) > $startDateReady) {
                                continue;
                            }

                            if ($event->all_day == 1) {
                                $eventReady['start'] = $startDateReady;
                                $eventReady['end'] = $startDateReady;
                            } else {
                                $eventReady['start'] = $startDateReady . " " . $startTime;
                                $eventReady['end'] = $startDateReady . " " . $endTime;
                            }

                            $events[] = $eventReady;
                        }
                    }
                }

            }

        }


        if ($selected_date and $events) {
            $filter_events = array();

            //dd($events,$selected_date);

            //   $selected_date_f = date('Y-m-d', strtotime($selected_date));
            $selected_date_f = strtotime($selected_date);


            foreach ($events as $event) {
                $ev_start = false;
                $ev_end = false;

                if (isset($event['start'])) {
                    // $ev_start =    strtotime($event['start']);
                    $ev_start = strtotime(date('Y-m-d 00:00:01', strtotime($event['start'])));

                }
                if (isset($event['end'])) {
                    $ev_end = strtotime($event['end']);
                    $ev_end_sel = strtotime($selected_date);
                    //  $ev_end = strtotime( date('Y-m-d 00:00:01', $ev_end));
                    $ev_end = strtotime(date('Y-m-d 23:59:59', $ev_end));
                    $ev_end_sel = strtotime(date('Y-m-d 23:59:59', strtotime($selected_date)));

                }

                if ($ev_start and $ev_start <= $ev_end_sel) {
                    if ($ev_end and $ev_end >= $ev_end_sel) {
                        $filter_events[] = $event;
                    }

                }


            }
 

            return $filter_events;


        }


        return $events;
    } else {
        if (is_ajax()) {
            // no event data
            return print lnotif(_e('Click here to edit Calendar', true));
        }
    }
}

function get_generated_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $year, $month)
{

    $previousYear = date('Y', strtotime("-1 month", strtotime($year . '-' . $month)));
    $previousMonth = date('m', strtotime("-1 month", strtotime($year . '-' . $month)));
    $previousMonthEvents = generate_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $previousYear, $previousMonth);

    $nextYear = date('Y', strtotime("+1 month", strtotime($year . '-' . $month)));
    $nextMonth = date('m', strtotime("+1 month", strtotime($year . '-' . $month)));
    $nextMonthEvents = generate_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $nextYear, $nextMonth);

    $currentMonthEvents = generate_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $year, $month);

    $mergedEvents = array_merge($previousMonthEvents, $nextMonthEvents);


    return array_merge($currentMonthEvents, $mergedEvents);
}

function generate_recurrence_repeat($event, $recurrenceRepeatOn, $timeZone, $year, $month)
{
    $dates_helper = new CalendarDatesHelper();

    $startDate = $event->start_date;
    $endDate = $event->end_date;

    $startTime = $event->start_time;
    $endTime = $event->end_time;

    $events = array();

    foreach ($recurrenceRepeatOn as $repeatOnDayName => $repeatOndayNameEnabled) {

        $selectedStartDateAndTime = $year . '-' . $month . '-01';
        $datesOfTheMonth = $dates_helper->getDatesOfMonthByDayName($timeZone, $selectedStartDateAndTime, ucfirst($repeatOnDayName));

        foreach ($datesOfTheMonth as $dateOfTheMonth) {

            $startDateReady = $dateOfTheMonth->getStart()->format('Y-m-d');

            if (date("Y-m-d", strtotime($startDate)) > $startDateReady) {
                continue;
            }

            $eventReady = array();

            if ($event->all_day == 1) {
                $eventReady['start'] = $startDateReady;
                $eventReady['end'] = $startDateReady;
            } else {
                $eventReady['start'] = $startDateReady . " " . $startTime;
                $eventReady['end'] = $startDateReady . " " . $endTime;
            }

            $events[] = $eventReady;
        }
    }

    return $events;
}

api_expose('calendar_remove_event');
function calendar_remove_event()
{
    if (!is_admin()) {
        return;
    }

    $table = "calendar";
    $eventid = $_POST['eventid'];

    $delete = db_delete($table, $eventid);

    if ($delete) {
        return (['status' => 'success']);
    } else {
        return (['status' => 'failed']);
    }
}

function calendar_get_event_by_id($event_id)
{
    $data = ['id' => $event_id, 'single' => true];
    $table = "calendar";

    return db_get($table, $data);
}


// SAVE GROUP
api_expose_admin('calendar_save_group');

function calendar_save_group($params)
{
    $save = [];

    if (isset($params['id'])) {
        $save['id'] = $params['id'];
    }

    if (isset($params['title'])) {
        $save['title'] = $params['title'];
    }

    if (!$save) {
        return;
    }

    return db_save("calendar_groups", $save);
}

function calendar_get_groups($params = false)
{
    return db_get("calendar_groups", $params);
}

// DELETE GROUP
api_expose_admin('calendar_delete_group');

function calendar_delete_group($params = false)
{
    if (!isset($params['id'])) {
        return 'Error';
    }

    return db_delete("calendar_groups", intval($params['id']));
}