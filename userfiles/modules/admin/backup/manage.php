<? if(!is_admin()){error("must be admin");}; ?>
<? include('nav.php'); ?>
<form action="" class="jNice">
  <h3>Available Backups</h3>
  <table cellpadding="0" cellspacing="0">
    <?php
		//  d($config);
// List the files

$here = dirname(__FILE__).DS;


$dir = opendir ($here ."backup".DS); 
while (false !== ($file = readdir($dir))) { 

	// Print the filenames that have .sql extension
	if (strpos($file,'.sql',1)) { 

	// Get time and date from filename
	$date = substr($file, 9, 10);
	$time = substr($file, 20, 8);

	// Remove the sql extension part in the filename
	$filenameboth = str_replace('.sql', '', $file);
                        
	// Print the cells
		print("<tr>\n");
		print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time . "</td>\n");
		
		
		 
		print("  <td class='action'><a href='". curent_url()."?do=restore&id=" . $filenameboth . "' class='edit'>Restore</a>\n");
		//print("<a href='backup/" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
//		print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
//		print("<a href='delete.php?file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
		print("</tr>\n");
	} 
} 
?>
  </table>
  <br />
</form>
