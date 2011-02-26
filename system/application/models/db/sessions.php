<?php



$column_for_not_drop = array('session_id');
$table_name = false;
			$table_name = TABLE_PREFIX . "sessions";
			$query = CI::db()->query("show tables like '$table_name'");
			$query = $query->row_array();
			$query = (array_values($query));
			if($query[0] != $table_name) {
				$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
						session_id varchar(40) DEFAULT '0' NOT NULL
						PRIMARY KEY (session_id)
						);";
				CI::db()->query($sql);
			}


			$sql = "show tables like '$table_name'";
			$query = CI::db()->query($sql);
			$query = $query->row_array();
			$query = (array_values($query));

			if($query[0] == $table_name) {
				//$columns = $db->fetchAll("show columns from $table_name");
				/*
				$sql = "show columns from $table_name";
				$query = CI::db()->query($sql);
				$columns = $query->result_array();


				$exisiting_fields = array();
				foreach ($columns as $fivesdraft) {
					$fivesdraft = array_change_key_case($fivesdraft,CASE_LOWER);
					$exisiting_fields[strtolower($fivesdraft['field'])] = true;
				}
				*/
				$fields_to_add = array();
				$fields_to_add[] = array( 'ip_address',   "varchar(16) DEFAULT '0' NOT NULL");
				$fields_to_add[] = array( 'user_agent',   'varchar(50) NOT NULL');
				$fields_to_add[] = array( 'last_activity',   'int(10) unsigned DEFAULT 0 NOT NULL');

				/*
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
				*/
				$this->set_db_tables($table_name, $fields_to_add , $column_for_not_drop);
			}


?>