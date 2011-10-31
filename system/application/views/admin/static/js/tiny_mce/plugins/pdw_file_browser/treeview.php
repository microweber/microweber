<?php

require_once('functions.php');

// Rootname is name of uploadfolder
$rootname = array_pop((explode("/", trim($uploadpath,"/"))));

// Get folders from uploadpath and create a list
$dirs = getDirTree(STARTINGPATH, false);
			
//Print treeview to screen
echo '<ul class="treeview">
            <li class="selected"><a class="root" href="'.$uploadpath.'">'.$rootname."</a>\n";
echo 		renderTree($dirs, $uploadpath);
echo "            </li>
       </ul>\n";

?>