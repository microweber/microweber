<?php





	$table_name = false;
			$table_name = TABLE_PREFIX . "cacaomail_mail_accounts";
			$query = CI::db()->query("show tables like '$table_name'");
			$query = $query->row_array();
			$query = (array_values($query));
			if($query[0] != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
						id int(11) NOT NULL auto_increment,
						UNIQUE KEY id (id)
						);";
				CI::db()->query($sql);
			}


			$sql = "show tables like '$table_name'";
			$query = CI::db()->query($sql);
			$query = $query->row_array();
			$query = (array_values($query));

			if($query[0] == $table_name) {
				//$columns = $db->fetchAll("show columns from $table_name");
				$sql = "show columns from $table_name";
				$query = CI::db()->query($sql);
				$columns = $query->result_array();


				$exisiting_fields = array();
				foreach ($columns as $fivesdraft) {
					$fivesdraft = array_change_key_case($fivesdraft,CASE_LOWER);
					$exisiting_fields[strtolower($fivesdraft['field'])] = true;
				}
				$fields_to_add = array();
				$fields_to_add[] = array( 'your_name',   "varchar(1500) default NULL");
				$fields_to_add[] = array( 'your_email',   'varchar(1500) default NULL');
				$fields_to_add[] = array( 'group_id',  " int(11) default  NULL  ");
				$fields_to_add[] = array( 'account_type',  "varchar(1500) default 'pop3' ");
				$fields_to_add[] = array( 'incoming_mail_server',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'outgoing_mail_server',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'pop3_port',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'smtp_port',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'pop3_requires_ssl',  "int(1) default 1 ");
				$fields_to_add[] = array( 'smtp_encryption_type',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'mail_username',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'mail_password',  "varchar(1500) default NULL ");
				$fields_to_add[] = array( 'smtp_requires_auth',  "int(1) default 1 ");
				$fields_to_add[] = array( 'updated_on',   'datetime default NULL');
				$fields_to_add[] = array( 'is_active',   'int(1) default 1');
				$fields_to_add[] = array( 'limit_per_day',   'int(100) default NULL');

				foreach($fields_to_add as $the_field){
					$sql = false;
					$the_field[0] = strtolower($the_field[0]);
					if ($exisiting_fields[$the_field[0]] != true) {
						$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
						CI::db()->query($sql);
					} else {
						$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
						CI::db()->query($sql);
					}

				}
			}




?>