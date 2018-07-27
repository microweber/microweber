<?php

require_once(__DIR__ . '/src/CalendarManager.php');

function calendar_get_events($params = array())
{
    $data = new CalendarManager();
    $data = $data->get_events($params);
    return $data;
}

api_expose_admin('calendar_save_event');
function calendar_save_event($params = array())
{
    $data = new CalendarManager();
    $data = $data->save_event($params);
    return $data;
}


api_expose_admin('calendar_export_to_csv_file', function ($params) {

    $data = calendar_get_events('no_limit=true');
    if (!$data) {
        return array('error' => 'You do not have any events');
    }
    $allowed = array(
        'id', 'created_at', 'created_at', 'content_id', 'title', 'shipping', 'startdate', 'enddate',
        'description', 'allDay', 'calendar_group_id', 'calendar_group_name', 'image_url', 'link_url', 'allDay',

    );


    $export = array();
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

    // $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
    $csv = \League\Csv\Writer::createFromPath($filename_path_full, 'w'); //to work make sure you have the write permission
    $csv->setEncodingFrom('UTF-8'); // this probably is not needed?

    $csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8); //adding the BOM sequence on output

    //we insert the CSV header
    $csv->insertOne(array_keys(reset($export)));

    $csv->insertAll($export);
    return response()->download($filename_path_full)->deleteFileAfterSend(true);

//    $download = mw()->url_manager->link_to_file($filename_path_full);
//    return response()->download($filePath, $fileName, $headers)->deleteFileAfterSend(true);
//
//    return redirect($download);
});

api_expose_admin('calendar_import_by_csv', function ($params) {

    if (Input::hasFile('csv_file')) {

        $file = Input::file('csv_file');
        $name = time() . '-' . $file->getClientOriginalName();

        $path = $file->getRealPath();


        $data = array_map('str_getcsv', file($path));
        $csv_data = $data;

        if (!$csv_data) {
            return (array('error' => 'Cannot parse the CSV file'));
        }
        $save_count = 0;
        $collect = array();
        if ($csv_data) {
            $header = array();
            foreach ($csv_data as $event) {
                if (isset($event[0]) and strtolower($event[0]) == 'id') {
                    $header = $event;
                    continue;
                }
                if (!$header) {
                    return (array('error' => 'Cannot parse column names.'));
                }

                $save = array();
                foreach ($header as $i => $col) {
                    if (isset($event[$i])) {
                        $save[$col] = $event[$i];
                    }

                }
                if ($save) {
                    $collect[] = $save;
                }


            }
        }

//dd($csv_data);

        if ($collect) {
            foreach ($collect as $save) {
                calendar_save_event($save);
                $save_count++;
            }
        }

        if (is_file($path)) {
            @unlink($path);
        }
        return (array('success' => $save_count . ' items were imported'));

    }

});


function calendar_get_events_by_group($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $calendar_group_id = 0;
    if (isset($params['calendar_group_id'])) {
        $calendar_group_id = intval($params['calendar_group_id']);
    }
    if (isset($params['date'])) {
        $date = $params['date'];
    } elseif (isset($_POST['date'])) {
        $date = $_POST['date'];
    } else {
        $date = date("Y-m-d");
    }

    $params['table'] = "calendar";
    $params ['no_cache'] = true; // disable cache whilst testing

    $events = array();

    if ($data = DB::table($params['table'])->select('id', 'title', 'description', 'startdate', 'enddate', 'allDay', 'content_id', 'calendar_group_id', 'image_url', 'link_url')
        ->where('startdate', '>', $date . ' 00:00:00')
        ->where('startdate', '<', $date . ' 23:59:59')
        ->where('calendar_group_id', $calendar_group_id)
        ->orderBy('startdate')
        ->get()
    ) {

        foreach ($data as $event) {
            if (!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
                $e = array();
                $e['id'] = $event->id;
                $e['title'] = $event->title;
                $e['description'] = $event->description;
                $e['start'] = $event->startdate;
                $e['end'] = $event->enddate;
                $e['allDay'] = ((isset($event->allDay) and ($event->allDay)) == 1 ? true : false);
                $e['content_id'] = ($event->content_id);
                $e['image_url'] = ($event->image_url);
                $e['link_url'] = ($event->link_url);
                array_push($events, $e);
            } else {
                // blank data
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
function calendar_get_events_groups_api($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    if (isset($params['yearmonth'])) {
        $yearmonth = $params['yearmonth'];
    } elseif (isset($_POST['yearmonth'])) {
        $yearmonth = $_POST['yearmonth'];
    } else {
        $yearmonth = date("Y-m");
    }

    $params['table'] = "calendar";
    $params ['no_cache'] = true; // disable cache whilst testing

    $groups = array();
    $data = DB::table($params['table'])->select('id', 'startdate');
    if ($yearmonth) {
        $data = $data->where('startdate', 'like', $yearmonth . '%');
    }
    $data = $data->groupBy(DB::raw('DATE(startdate)'))
        ->get();
    if ($data) {
        foreach ($data as $group) {
            $start = $group->startdate;
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

function calendar_get_events_days($params = array())
{
    $events = calendar_get_events_api();

}

api_expose('calendar_get_events_api');
function calendar_get_events_api($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $calendar_group_id = 0;
    if (isset($params['calendar_group_id'])) {
        $calendar_group_id = intval($params['calendar_group_id']);
    }
    if (isset($params['yearmonth'])) {
        $yearmonth = $params['yearmonth'];
    } elseif (isset($_POST['yearmonth'])) {
        $yearmonth = $_POST['yearmonth'];
    } else {
        $yearmonth = date("Y-m");
    }

    $params['table'] = "calendar";
    $params ['no_cache'] = true; // disable cache whilst testing

    $events = array();

    if ($data = DB::table($params['table'])->select('id', 'title', 'description', 'startdate', 'enddate', 'allDay', 'content_id', 'calendar_group_id', 'image_url', 'link_url')
        ->where('startdate', 'like', $yearmonth . '%')
        ->where('calendar_group_id', $calendar_group_id)
        ->get()
    ) {

        foreach ($data as $event) {
            if (!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
                $e = array();
                $e['id'] = $event->id;
                $e['title'] = $event->title;
                $e['description'] = $event->description;
                $e['start'] = $event->startdate;
                $e['end'] = $event->enddate;
                $e['allDay'] = ((isset($event->allDay) and ($event->allDay)) == 1 ? true : false);
                $e['content_id'] = ($event->content_id);
                $e['calendar_group_id'] = ($event->calendar_group_id);
                $e['image_url'] = ($event->image_url);
                $e['link_url'] = ($event->link_url);
                array_push($events, $e);
            } else {
                // blank data
            }
        }

        return $events;

    } else {
        if (is_ajax()) {
            // no event data
            return print lnotif(_e('Click here to edit Calendar', true));
        }
    }
}

api_expose('calendar_new_event');
function calendar_new_event()
{


    if (!is_admin()) return;


    $enddate = false;
    $table = "calendar";
    $startdate = mw()->database_manager->escape_string(trim($_POST['startdate']) . '+' . trim($_POST['zone']));
    if (isset($_POST['enddate'])) {
        $enddate = mw()->database_manager->escape_string(trim($_POST['enddate']) . '+' . trim($_POST['zone']));
    }
    $title = mw()->database_manager->escape_string(trim($_POST['title']));
    $imageUrl = isset($_POST['image_url']) ? $_POST['image_url'] : '';
    $linkUrl = isset($_POST['link_url']) ? $_POST['link_url'] : '';
    $calendar_group_id = 0;
    if (isset($_POST['calendar_group_id'])) {
        $calendar_group_id = intval($_POST['calendar_group_id']);
    }
    $data = array('title' => $title,
        'startdate' => $startdate,
        'enddate' => $enddate ? $enddate : $startdate,
        'calendar_group_id' => $calendar_group_id,
        'allDay' => 0,
        'image_url' => $imageUrl,
        'link_url' => $linkUrl
    );

    $lastid = db_save($table, $data);

    if ($lastid)
        return (array('status' => 'success', 'eventid' => $lastid));
    else
        return (array('status' => 'failed'));
}

api_expose('calendar_change_title');
function calendar_change_title()
{
    if (!is_admin()) return;

    $eventid = false;
    if (!isset($_POST['zone'])) {
        $_POST['zone'] = '00:00';
    }
    if (isset($_POST['eventid'])) {
        $eventid = $_POST['eventid'];
    }
    if (!isset($_POST['eventid']) and isset($_POST['id'])) {
        $eventid = $_POST['id'];
    }

    if (!$eventid) {
        return false;
    }

    $table = "calendar";
    $title =  (trim($_POST['title']));
    $description =  (trim($_POST['description']));

    if (isset($_POST['startdate'])) {
        $startdate = $_POST['startdate'];
    } else {
        $startdate =  (trim($_POST['start'] . '+' . trim($_POST['zone'])));

    }
    if (isset($_POST['enddate'])) {
        $enddate = $_POST['enddate'];
    } else {
        $enddate =  (trim($_POST['end'] . '+' . trim($_POST['zone'])));

    }

    if (isset($_POST['content_id'])) {
        $content_id = $_POST['content_id'];
    } else {
        $content_id = null;

    }

    $startdate = date('Y-m-d H:i:s', strtotime($startdate));
    $enddate = date('Y-m-d H:i:s', strtotime($enddate));

    if (isset($_POST['image_url'])) {
        $imageUrl = $_POST['image_url'];
    }
    if (isset($_POST['link_url'])) {
        $linkUrl = $_POST['link_url'];
    }

    $data = array('id' => $eventid, 'title' => $title, 'description' => $description, 'startdate' => $startdate, 'enddate' => $enddate, 'content_id' => $content_id, 'image_url' => $imageUrl, 'link_url' => $linkUrl);

    if (isset($_POST['calendar_group_id'])) {
        $data['calendar_group_id'] = intval($_POST['calendar_group_id']);
    }

    $update = db_save($table, $data);

    if ($update)
        return array('status' => 'success');
    else
        return array('status' => 'failed');
}

api_expose('calendar_reset_date');
function calendar_reset_date($params)
{

    if (!is_admin()) return;

// INTERNAL SERVER ERROR 500
    if (!isset($params['zone'])) {
        $params['zone'] = '00:00';
    }

    if (isset($params['event_id'])) {
        $params['eventid'] = $params['event_id'];
    }


    $table = "calendar";
    $title =  trim($params['title']);
    $startdate =  trim($params['start'] . '+' . trim($params['zone']));
    $enddate =  trim($params['end'] . '+' . trim($params['zone']));
    $eventid = $params['eventid'];

    $data = array('id' => $eventid, 'title' => $title, 'startdate' => $startdate, 'enddate' => $enddate);

    $update = db_save($table, $data);

    if ($update)
        return (array('status' => 'success'));
    else
        return (array('status' => 'failed'));
}

api_expose('calendar_remove_event');
function calendar_remove_event()
{

    if (!is_admin()) return;

    $table = "calendar";
    $eventid = $_POST['eventid'];

    $delete = db_delete($table, $eventid);

    if ($delete)
        return (array('status' => 'success'));
    else
        return (array('status' => 'failed'));
}


function calendar_get_event_by_id($event_id)
{
    $data = array('id' => $event_id, 'single' => true);
    $table = "calendar";

    return db_get($table, $data);
}


api_expose_admin('calendar_save_group');

function calendar_save_group($params)
{

    $save = array();
    if (isset($params['id'])) {
        $save['id'] = $params['id'];
    }

    if (isset($params['title'])) {
        $save['title'] = $params['title'];
    }

    if (!$save) {
        return;
    }

    $table = "calendar_groups";

    return db_save($table, $save);
}


function calendar_get_groups($params = false)
{
    $table = "calendar_groups";

    return db_get($table, $params);
}

api_expose_admin('calendar_delete_group');
function calendar_delete_group($params = false)
{
    if (!isset($params['id'])) {
        return 'Error';
    }

    $id = intval($params['id']);
    $table = "calendar_groups";

    return db_delete($table, $id);
}