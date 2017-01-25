<?php
api_expose('get_events');
function get_events($params=array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

	if(isset($params['yearmonth'])) {
		$yearmonth = $params['yearmonth'];
	} elseif(isset($_POST['yearmonth'])) {
		$yearmonth = $_POST['yearmonth'];
	} else {
		$yearmonth = date("Y-m");
	}

  $params['table'] = "calendar";
	$params ['no_cache'] = true; // disable cache whilst testing

	$events = array();

	if($data = DB::table($params['table'])->select('id','title','description','startdate','enddate','allDay')->where('startdate', 'like', $yearmonth . '%')->get()){
		foreach ($data as $event) {
			if(!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
				$e = array();
				$e['id'] = $event->id;
				$e['title'] = $event->title;
				$e['description'] = $event->description;
				$e['start'] = $event->startdate;
				$e['end'] = $event->enddate;
				$e['allDay'] = ($event->allDay=='true'?true:false);
				array_push($events, $e);
			} else {
				// blank data
			}
		}

		return json_encode($events);

	} else {
		// no event data
		return print lnotif("Click here to edit Calendar");
	}
}

api_expose('new_event');
function new_event(){

	if (!is_admin()) return;

	$table = "calendar";
	$startdate = mw()->database_manager->escape_string(trim($_POST['startdate']).'+'.trim($_POST['zone']));
	$title = mw()->database_manager->escape_string(trim($_POST['title']));

	$data = array('title'=>$title,
				  'startdate'=>$startdate,
				  'enddate'=>$startdate,
				  'allDay'=>'false'
				  );

	$lastid = db_save($table, $data);

	if($lastid)
		echo json_encode(array('status'=>'success','eventid'=>$lastid));
	else
		echo json_encode(array('status'=>'failed'));
}

api_expose('change_title');
function change_title(){

	if (!is_admin()) return;


  if(!isset($_POST['zone'])){
    $_POST['zone'] = '00:00';
  }

	$table = "calendar";
	$eventid = $_POST['eventid'];
	$title = mw()->database_manager->escape_string(trim($_POST['title']));
	$description = mw()->database_manager->escape_string(trim($_POST['description']));
	$startdate = mw()->database_manager->escape_string(trim($_POST['start'].'+'.trim($_POST['zone'])));
	$enddate = mw()->database_manager->escape_string(trim($_POST['end'].'+'.trim($_POST['zone'])));

	$data = array('id'=>$eventid,'title'=>$title,'description'=>$description,'startdate'=>$startdate,'enddate'=>$enddate);

    $update = db_save($table, $data);

	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

api_expose('reset_date');
function reset_date(){

	if (!is_admin()) return;

// INTERNAL SERVER ERROR 500
if(!isset($_POST['zone'])){
  $_POST['zone'] = '00:00';
}

	$table = "calendar";
	$title = mw()->database_manager->escape_string(trim($_POST['title']));
	$startdate = mw()->database_manager->escape_string(trim($_POST['start'].'+'.trim($_POST['zone'])));
	$enddate = mw()->database_manager->escape_string(trim($_POST['end'].'+'.trim($_POST['zone'])));
	$eventid = $_POST['eventid'];

	$data = array('id'=>$eventid,'title'=>$title,'startdate'=>$startdate,'enddate'=>$enddate);

    $update = db_save($table, $data);

	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

api_expose('remove_event');
function remove_event(){

	if (!is_admin()) return;

	$table = "calendar";
	$eventid = $_POST['eventid'];

	$delete = db_delete($table, $eventid);

	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}
?>
