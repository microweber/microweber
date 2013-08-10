<?php
exit('To run this example remove line ' . __LINE__ . ' in file ' . basename(__FILE__));

require_once ('../bootstrap.php');

$data = array();
$data['title'] = 'My title';
$data['content'] = 'My content';
$data['url'] = 'my-link';


$check_if_saved = get('table=content&title=' . $data['title']);
if (empty($check_if_saved)) {
    $saved_id = save('content', $data);
    $saved_id = save('content', $data);
    $check_if_saved = get('table=content&id=' . $saved_id);
}
var_dump($check_if_saved);

?>