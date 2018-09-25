<?php

class CalendarManager
{
    public $table = 'calendar';

    public $table_groups = 'calendar_groups';

    public function get_events($params = [])
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $yearmonth = false;
        $calendar_group_id = false;
        $group_by_date = false;
        $events = [];

        $data = DB::table($this->table)->select('*');

        if (isset($params['group_by_date'])) {
            $group_by_date = $params['group_by_date'];
        }

        if ($yearmonth) {
            $data = $data->where('startdate', 'like', $yearmonth . '%');
        }
        if ($calendar_group_id) {
            $data = $data->where('calendar_group_id', $calendar_group_id);
        }

        $data = $data->orderBy('startdate', 'asc');

        $data = $data->get();

        if ($data) {
            foreach ($data as $event) {
                if (!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
                    $e = [];
                    $e['id'] = $event->id;
                    $e['title'] = $event->title;
                    $e['short_description'] = $event->short_description;
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
                }
            }

            if ($events) {
                if ($group_by_date) {
                    $groups = [];
                    foreach ($events as $group) {
                        $grp_date = $group['date_day'];
                        if (!isset($groups[$grp_date])) {
                            $groups[$grp_date] = [];
                        }
                        $groups[$grp_date][] = $group;
                    }
                    return $groups;
                }
            }

            return $events;
        }
    }

    public function save_event($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $eventid = 0;
        $table = "calendar";
        $title = false;
        $description = false;
        $short_description = false;
        $imageUrl = false;
        $linkUrl = false;
        $startdate = false;
        $enddate = false;
        $linkUrl = false;

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

        if (isset($params['title'])) {
            $title = (trim($params['title']));
        }

        if (isset($params['description'])) {
            $description = (trim($params['description']));
        }

        if (isset($params['short_description'])) {
            $short_description = (trim($params['short_description']));
        }

        if (isset($params['image_url'])) {
            $imageUrl = $params['image_url'];
        }

        if (isset($params['link_url'])) {
            $linkUrl = $params['link_url'];
        }

        if (isset($params['startdate'])) {
            $startdate = $params['startdate'];
        }

        if (isset($params['enddate'])) {
            $enddate = $params['enddate'];
        }

        if (isset($params['content_id'])) {
            $content_id = $params['content_id'];
        } else {
            $content_id = null;
        }


        $startdate = date('Y-m-d H:i:s', strtotime($startdate));

        $enddate = date('Y-m-d H:i:s', strtotime($enddate));




        $data = [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'content_id' => $content_id,
            'image_url' => $imageUrl,
            'link_url' => $linkUrl,
        ];

        if ($eventid) {
            $data['id'] = $eventid;
        }

        if ($title) {
            $data['title'] = $title;
        }

        if ($description) {
            $data['description'] = $description;
        }
        if ($short_description) {
            $data['short_description'] = $short_description;
        }

        if (isset($params['calendar_group_id'])) {
            $data['calendar_group_id'] = intval($params['calendar_group_id']);
        }







        return db_save($table, $data);
    }
}
