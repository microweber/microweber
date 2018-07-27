<?php

require_once(__DIR__ . '/CalendarEventsCrud.php');
require_once(__DIR__ . '/CalendarGroupsCrud.php');


class CalendarManager
{

    /** @var CalendarEventsCrud */
    //var $events = false;
    /** @var CalendarGroupsCrud */
    // var $groups = false;

    /** @var \Microweber\Application */
    public $app;

    public $table = 'calendar';
    public $table_groups = 'calendar_groups';


    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
        //  $this->events = new CalendarEventsCrud($this->app);
        // $this->groups = new CalendarGroupsCrud($this->app);


    }

    function get_events($params = array())
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $yearmonth = false;
        $calendar_group_id = false;
        $group_by_date = false;
        if (isset($params['group_by_date'])) {
            $group_by_date = $params['group_by_date'];
        }


        $events = array();

        $data = DB::table($this->table)->select('*');


        if ($yearmonth) {
            $data = $data->where('startdate', 'like', $yearmonth . '%');
        }
        if ($calendar_group_id) {
            $data = $data->where('calendar_group_id', $calendar_group_id);
        }
        if ($group_by_date) {
            //  $data = $data->groupBy(DB::raw('DATE(startdate)'));
        }
        $data = $data->get();

        if ($data) {


            foreach ($data as $event) {
                if (!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
                    $e = array();
                    $e['id'] = $event->id;
                    $e['title'] = $event->title;
                    $e['description'] = $event->description;
                    $e['startdate'] = $event->startdate;
                    $e['enddate'] = $event->enddate;

                    $e['calendar_group_id'] = ($event->calendar_group_id);
                    $e['calendar_group_name'] = 'Default';

                    if ($event->calendar_group_id) {
                        $calendar_group_name = DB::table($this->table_groups)->select('title')->where('id', $event->calendar_group_id)->first();
                        if ($calendar_group_name and $calendar_group_name->title) {
                            $e['calendar_group_name'] = $calendar_group_name->title;
                        }
                    }

                    $e['image_url'] = ($event->image_url);
                    $e['link_url'] = ($event->link_url);
                    $e['date_day'] = date('Y-m-d', @strtotime($event->startdate));
                    $e['allDay'] = ((isset($event->allDay) and ($event->allDay)) == 1 ? true : false);
                    $e['content_id'] = ($event->content_id);
                    array_push($events, $e);
                } else {
                    // blank data
                }
            }

            if ($events) {
                if ($group_by_date) {
                    $groups = array();
                    foreach ($events as $group) {
                        $grp_date = $group['date_day'];
                        if (!isset($groups[$grp_date])) {
                            $groups[$grp_date] = array();
                        }
                        $groups[$grp_date][] = $group;
                    }
                    return $groups;
                }
            }


            return $events;

        }


    }

    function save_event($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $eventid = 0;
        if (!isset($params['zone'])) {
            $params['zone'] = '00:00';
        }
        if (isset($params['eventid'])) {
            $eventid = $params['eventid'];
        }
        if (!isset($params['eventid']) and isset($params['id'])) {
            $eventid = $params['id'];
        }
        if ($eventid) {
            $check = calendar_get_event_by_id($eventid);
            if (!$check) {
                $eventid = false;
            }
        }
        if (!$eventid) {
            //    return false;
        }

        $table = "calendar";
        $title = false;
        $description = false;
        $imageUrl = false;
        $linkUrl = false;
        if (isset($params['title'])) {
            $title = (trim($params['title']));
        }

        if (isset($params['description'])) {
            $description = (trim($params['description']));
        }


        if (isset($params['image_url'])) {
            $imageUrl = $params['image_url'];
        }
        if (isset($params['link_url'])) {
            $linkUrl = $params['link_url'];
        }

        if (isset($params['startdate'])) {
            $startdate = $params['startdate'];
        } else {
            //  $startdate = mw()->database_manager->escape_string(trim($params['start'] . '+' . trim($params['zone'])));

        }
        if (isset($params['enddate'])) {
            $enddate = $params['enddate'];
        } else {
            //   $enddate = mw()->database_manager->escape_string(trim($params['end'] . '+' . trim($params['zone'])));

        }

        if (isset($params['content_id'])) {
            $content_id = $params['content_id'];
        } else {
            $content_id = null;

        }

        $startdate = date('Y-m-d H:i:s', strtotime($startdate));
        $enddate = date('Y-m-d H:i:s', strtotime($enddate));


        $data = array(

            'startdate' => $startdate,
            'enddate' => $enddate,
            'content_id' => $content_id,
            'image_url' => $imageUrl,
            'link_url' => $linkUrl);
        if ($eventid) {
            $data['id'] = $eventid;
        }
        if ($title) {
            $data['title'] = $title;
        }
        if ($description) {
            $data['description'] = $description;
        }
        if (isset($params['calendar_group_id'])) {
            $data['calendar_group_id'] = intval($params['calendar_group_id']);
        }

        $update = db_save($table, $data);

        return $update;
    }


}