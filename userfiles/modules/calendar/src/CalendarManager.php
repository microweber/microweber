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
        $group_by_type = false;
        $events = [];

        $data = DB::table($this->table);

        if (isset($params['group_by_date'])) {
            $group_by_date = $params['group_by_date'];
        }
        if (isset($params['yearmonth'])) {
            $yearmonth = $params['yearmonth'];
        }
        if (isset($params['group_by_type'])) {
            $group_by_type = $params['group_by_type'];
        }

        if ($yearmonth) {
            $data = $data->where('start_date', 'like', $yearmonth . '%');
        }
        if ($calendar_group_id) {
            $data = $data->where('calendar_group_id', $calendar_group_id);
        }

        $data = $data->get();

        if ($data) {
            foreach ($data as $event) {
                if (!empty($event->id) && !empty($event->title) && !empty($event->start_date)) {

                    $eventReady = [];
                    $eventReady['id'] = $event->id;
                    $eventReady['title'] = $event->title;
                    $eventReady['description'] = $event->description;
                    $eventReady['short_description'] = $event->short_description;
                    $eventReady['start_date'] = $event->start_date;
                    $eventReady['end_date'] = $event->end_date;

                    $eventReady['start_time'] = $event->start_time;
                    $eventReady['end_time'] = $event->end_time;

                    $eventReady['recurrence_type'] = $event->recurrence_type;
                    $eventReady['recurrence_repeat_type'] = $event->recurrence_repeat_type;
                    $eventReady['recurrence_repeat_every'] = $event->recurrence_repeat_every;
                    $eventReady['recurrence_repeat_on'] = $event->recurrence_repeat_on;

                    $eventReady['calendar_group_id'] = ($event->calendar_group_id);
                    $eventReady['calendar_group_name'] = 'Default';

                    if ($event->calendar_group_id) {
                        $calendar_group_name = DB::table($this->table_groups)->select('title')
                            ->where('id', $event->calendar_group_id)
                            ->first();
                        if ($calendar_group_name and $calendar_group_name->title) {
                            $eventReady['calendar_group_name'] = $calendar_group_name->title;
                        }
                    }

                    $eventReady['image_url'] = ($event->image_url);
                    $eventReady['link_url'] = ($event->link_url);
                    $eventReady['date_day'] = date('Y-m-d', @strtotime($event->start_date));
                    $eventReady['all_day'] = ((isset($event->all_day) and ($event->all_day)) == 1 ? true : false);
                    $eventReady['content_id'] = ($event->content_id);
                    $events[] = $eventReady;
                }
            }

            if ($events) {
                if ($group_by_date && $group_by_type == false) {
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

                if ($group_by_type && $group_by_date) {
                    $groups = [];
                    foreach ($events as $group) {
                        $grp_date = $group['date_day'];
                        $recurrence_type = $group['recurrence_type'];
                        if (!isset($groups[$recurrence_type])) {
                            $groups[$recurrence_type] = [];
                        }
                        $groups[$recurrence_type][$grp_date][] = $group;
                    }
                    return $groups;
                }

                if ($group_by_type) {
                    $groups = [];
                    foreach ($events as $group) {
                        $recurrence_type = $group['recurrence_type'];
                        if (!isset($groups[$recurrence_type])) {
                            $groups[$recurrence_type] = [];
                        }
                        $groups[$recurrence_type][] = $group;
                    }
                    return $groups;
                }
            }

            return $events;
        }
    }

    public function save_event($eventData)
    {
        if (is_string($eventData)) {
            $eventData = parse_params($eventData);
        }

        if (!isset($eventData['active'])) {
            $eventData['active'] = 0;
        } else {
            $eventData['active'] = intval($eventData['active']);
        }
        if (!isset($eventData['calendar_group_id'])) {
            $eventData['calendar_group_id'] = 0;
        }


        if (!isset($eventData['all_day'])) {
            $eventData['all_day'] = 0;
        } else {
            $eventData['all_day'] = intval($eventData['all_day']);
        }

        $dateObj= \DateTime::createFromFormat('Y-m-d', $eventData['end_date']);
        $end_date = $dateObj->format('Y-m-d');


        $dateObj= \DateTime::createFromFormat('Y-m-d', $eventData['start_date']);
        $start_date = $dateObj->format('Y-m-d');

         $eventData['content_id'] = intval($eventData['content_id']);
        $eventData['calendar_group_id'] = intval($eventData['calendar_group_id']);
        $eventData['recurrence_repeat_every'] = intval($eventData['recurrence_repeat_every']);

        $eventData['title'] = trim($eventData['title']);
        $eventData['description'] = trim($eventData['description']);
        $eventData['image_url'] = trim($eventData['image_url']);
        $eventData['link_url'] = trim($eventData['link_url']);
        $eventData['recurrence_type'] = trim($eventData['recurrence_type']);

//        $eventData['start_date'] = date('Y-m-d', strtotime($eventData['start_date']));
//        $eventData['end_date'] = date('Y-m-d', strtotime($eventData['end_date']));


        $eventData['start_date'] = $start_date;
        $eventData['end_date'] = $end_date;
        $eventData['start_time'] = date('H:i:s', strtotime($eventData['start_time']));
        $eventData['end_time'] = date('H:i:s', strtotime($eventData['end_time']));


        if (isset($eventData['recurrence_repeat_on'])) {
            $eventData['recurrence_repeat_on'] = json_encode($eventData['recurrence_repeat_on']);
        }

        return db_save($this->table, $eventData);
    }
}
