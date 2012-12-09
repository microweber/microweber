<? if(!is_admin()){error("must be admin");}; ?>
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
	

	 
      	// Print the message
	print('The backup has been created successfully. <br />You can get <b>MySQL dump file</b> <a href="' . $filess . '.sql" class="view">here</a>.<br>' . "\n");
	print('You can get <b>Backed-up files archive</b> <a href="' . $filess . '.zip" class="view">here</a>.<br>' . "\n");
?></td>
  </table>
  <br />
</form>
