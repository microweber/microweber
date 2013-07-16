<?php
// Get the filename to be deleted
$file=$_GET['file'];

// Check if the file has needed args
if ($file==NULL){
  print("<script type='text/javascript'>window.alert('" . _e("You have not provided a file to delete", true) . ".');</script>");
  print("<script type='text/javascript'>window.location='manage.php'</script>");
  print( _e("You have not provided a file to delete", true) . ".<br>". _e("Click", true)." <a href='manage.php'>"._e("here", true). " .</a> "._e("if your browser doesn't automatically redirect you", true).".");
  die();
}

// Delete the SQL file
if (!is_dir("backup/" . $file . '.sql')) {
unlink("backup/" . $file . '.sql');
}

// Delete the ZIP file
if (!is_dir("backup/" . $file . '.zip')) {
unlink("backup/" . $file . '.zip');
}

// Redirect
header("Location: manage.php");
?>
