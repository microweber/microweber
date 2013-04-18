<? only_admin_access(); ?>


<?	set_time_limit(0);

	$check = get_log("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&user_ip=" . USER_IP);
	if(isset($check['value'])){
		  print "Uploaded: ".file_size_nice($check['value']) ; 
	}
	
	
	
	
	$check = get_log("order_by=created_on desc&one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);
	if(isset($check['value'])){
		  print "Action: ".($check['value']) ; 
	}
	
	
	//
//d($check);
 ?>