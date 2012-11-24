<? if(!is_admin()){error("must be admin");}; ?>
<? include('nav.php'); ?>

<!-- h2 stays for breadcrumbs -->
<h2><a href="#" class="active">Create a Backup</a></h2>
<form action="" class="jNice">
  <h3>Backup Log</h3>
  <table cellpadding="0" cellspacing="0">
    
      <td><?php
// Include settings
include("config.php");

// Set the suffix of the backup filename
if ($table == '*') {
	$extname = 'all';
}else{
	$extname = str_replace(",", "_", $table);
	$extname = str_replace(" ", "_", $extname);
}
$here = dirname(__FILE__).DS;
// Generate the filename for the backup file
$index1 = $here. 'backup'.DS.'index.php';
$filess =  $here. 'backup'.DS.'dbbackup_' . date("d.m.Y_H_i_s") .uniqid(). '_' . $extname;
touch($filess );
touch($index1 );
// Call the backup function for all tables in a DB
backup_tables($DBhost,$DBuser,$DBpass,$DBName,$table,$extname,$filess);

// Backup the table and save it to a sql file
	function backup_tables($host,$user,$pass,$name,$tables,$bckextname,$filess)
{
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
	$return = "";	

		// Get all of the tables
		if($tables == '*') {
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result)) {
				$tables[] = $row[0];
			}
		} else {
			if (is_array($tables)) {
				$tables = explode(',', $tables);
			}
	}

		// Cycle through each provided table
		foreach($tables as $table) {
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
		
			// First part of the output - remove the table
			$return .= 'DROP TABLE ' . $table . ';<|||||||>';

			// Second part of the output - create table
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return .= "\n\n" . $row2[1] . ";<|||||||>\n\n";

			// Third part of the output - insert values into new table
			for ($i = 0; $i < $num_fields; $i++) {
				while($row = mysql_fetch_row($result)) {
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) {
						$row[$j] = addslashes($row[$j]);
						$row[$j] = str_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { 
$return .= '"' . $row[$j] . '"'; 
} else { 
$return .= '""'; 
}
						if ($j<($num_fields-1)) { 
$return.= ','; 
}
					}
					$return.= ");<|||||||>\n";
				}
			}
			$return.="\n\n\n";
		}

		// Save the sql file
		$handle = fopen($filess.'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);

	// Close MySQL Connection
//	mysql_close($link);
} 

	 
      	// Print the message
	print('The backup has been created successfully. <br />You can get <b>MySQL dump file</b> <a href="' . $filess . '.sql" class="view">here</a>.<br>' . "\n");
	print('You can get <b>Backed-up files archive</b> <a href="' . $filess . '.zip" class="view">here</a>.<br>' . "\n");
?></td>
  </table>
  <br />
</form>
