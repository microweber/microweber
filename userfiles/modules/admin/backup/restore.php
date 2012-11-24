<? if(!is_admin()){error("must be admin");}; ?>
<? include('nav.php'); ?>
<form action="" class="jNice">
  <h3>Restore Log</h3>
  <table cellpadding="0" cellspacing="0">
    
      <td><?php
// Get the provided arg
$id=$_GET['id'];

// Check if the file has needed args
if ($id==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a backup to restore.')</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print("You have not provided a backup to restore.<br>Click <a href='manage.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Include settings
include("config.php");
$here = dirname(__FILE__).DS;
// Generate filename and set error variables
$filename = $here.'backup/' . $id . '.sql';
$sqlErrorText = '';
$sqlErrorCode = 0;
$sqlStmt      = '';

// Restore the backup
$con = mysql_connect($DBhost,$DBuser,$DBpass);
if ($con !== false){
  // Load and explode the sql file
  mysql_select_db("$DBName");
  $f = fopen($filename,"r+");
  $sqlFile = fread($f,filesize($filename));
  $sqlArray = explode(';<|||||||>',$sqlFile);
          
  // Process the sql file by statements
  foreach ($sqlArray as $stmt) {
    if (strlen($stmt)>3){
         $result = mysql_query($stmt);
    }
  }
}

// Print message (error or success)
if ($sqlErrorCode == 0){
   print("Database restored successfully!<br>\n");
   print("Backup used: " . $filename . "<br>\n");
} else {
   print("An error occurred while restoring backup!<br><br>\n");
   print("Error code: $sqlErrorCode<br>\n");
   print("Error text: $sqlErrorText<br>\n");
   print("Statement:<br/> $sqlStmt<br>");
}

// Close the connection
//mysql_close($con);

// Change the filename from sql to zip
$filename = str_replace('.sql', '.zip', $filename);
 
// Files restored successfully
print("Files restored successfully!<br>\n");
print("Backup used: " . $filename . "<br>\n");
?></td>
  </table>
  <br />
</form>
