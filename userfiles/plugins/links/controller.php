<?php
$plugin_template = false;
error_log('LOADED plugins/links/controller.php');

$action = $this->core_model->getParamFromURL('action');
$action = ( $action ? $action : 'default' );

error_log('ACTION => ' . $action);

if(!$action)
    exit;

$range = array_merge(range('a','z'), range('A','Z'), range(0,9));
$trans = array_flip($range);

//$dispatcher[$action]();

switch($action) {
    case 'add':
        $this->load->vars($this->template);
        $plugin_template = $this->load->file(PLUGINS_DIRNAME . 'links/templates/add.php', true);
        
        break;
    default:
        exit;
        break;
}   


function list_links() {
    error_log('IN LIST LINKS');
}

function get_last_key() {
    // fetch last key and return
    return 'a';
}

function get_next_key($key = null, $tail = '') {
    global $range, $trans; // bad

    if($key === null)
        $key = get_last_key();

    if(empty($key))
        return "a{$tail}";

    $keylen = strlen($key);
    $last_char = $key[$keylen - 1];

    if($last_char != '9')
        return substr($key, 0, ($keylen - 1)) . $range[$trans[$last_char] + 1] . $tail;
    else
        return get_next_key(substr($key, 0, -1), "a{$tail}");
}

?>
