<? $file_to_edit = ($params['layout_file']);

$src = $this->template_model->layoutGet($file_to_edit);

?>
<? print $src ; ?>