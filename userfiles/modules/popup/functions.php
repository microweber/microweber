<?php
api_expose('popup_module_get_content_by_id');
function popup_module_get_content_by_id(){
    $page_id = $_GET['page_id'];
    //$page_id = $_POST['page_id'];
    return get_content_by_id($page_id);
}
?>