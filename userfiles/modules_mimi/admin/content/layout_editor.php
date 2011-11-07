<? $file_to_edit = ($params['layout_file']);

$src = CI::model('template')->layoutGet($file_to_edit);

?>
<? print $src ; ?>