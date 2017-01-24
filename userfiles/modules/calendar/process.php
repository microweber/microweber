<?php
//include('config.php');

$type = $_POST['type'];

if($type == 'new')
{
	$startdate = $_POST['startdate'].'+'.$_POST['zone'];
	$title = $_POST['title'];
	$q = "INSERT INTO calendar(`title`, `startdate`, `enddate`, `allDay`) VALUES('$title','$startdate','$startdate','false')";
	$insert = mw()->database_manager->query($q);
	$lastid = mw()->database_manager->last_id('calendar');;
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'changetitle')
{
	$eventid = $_POST['eventid'];
	$title = $_POST['title'];
	$q = "UPDATE calendar SET title='$title' where id='$eventid'";
	$update = mw()->database_manager->query($q);
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'resetdate')
{
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
	$q = "UPDATE calendar SET title='$title', startdate = '$startdate', enddate = '$enddate' where id='$eventid'";
	$update = mw()->database_manager->query($q);
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'remove')
{
	$eventid = $_POST['eventid'];
	$q = "DELETE FROM calendar where id='$eventid'";
	$delete = mw()->database_manager->query($q);
	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'fetch')
{
	$events = array();

	$q = "SELECT * FROM calendar";
	$events_array = mw()->database_manager->query($q);
	/*
	if(is_array($events_array) && count($events_array)>0){
		foreach($events_array as $k->$v) {
			$e = array();
			$e['id'] = $v['id'];
			$e['title'] = $v['title'];
			$e['start'] = $v['startdate'];
			$e['end'] = $v['enddate'];

     		$allday = ($v['allDay'] == "true") ? true : false;
     		$e['allDay'] = $allday;

     		//array_push($events, $e);
		}
	}

	while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['title'];
    $e['start'] = $fetch['startdate'];
    $e['end'] = $fetch['enddate'];

    $allday = ($fetch['allDay'] == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
	}
	*/
	echo json_encode($events);
}


?>