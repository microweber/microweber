<?php


event_bind('mw.front', function ($params) {
    if (!defined('MW_DYNAMIC_TEXT_SHOULD_REPLACE')) {
        define('MW_DYNAMIC_TEXT_SHOULD_REPLACE', 1);
    }
});

document_ready('exec_dynamic_text_replace_in_layout');
function exec_dynamic_text_replace_in_layout($layout)
{
    if (defined('MW_DYNAMIC_TEXT_SHOULD_REPLACE')) {
        if (!in_live_edit()) {
            $texts = get_dynamic_text('nolimit=1');
            if ($texts) {
                $replaces = array();
                $searches = array();
                foreach ($texts as $text) {
                    if (isset($text['name']) and $text['name']) {
                        $searches[] = '[' . $text['name'] . ']';
                        $replaces[] = $text['content'];
                    }
                
                    if (isset($text['name']) and $text['name']) {
                        $searches[] = '%5B' . $text['name'] . '%5D';
                        $replaces[] = $text['content'];
                    }
                }
                if ($searches) {
                    $layout = str_replace($searches, $replaces, $layout);
                    // nested replace
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    $layout = str_replace($searches, $replaces, $layout);
                    return $layout;
                }
            }
        }
    }
    return $layout;
}






event_bind('parser.process', function ($layout) {
    if (defined('MW_DYNAMIC_TEXT_SHOULD_REPLACE')) {

        $texts = get_dynamic_text('nolimit=1');
        if ($texts) {
            $replaces = array();
            $searches = array();
            foreach ($texts as $text) {
                if (isset($text['name']) and $text['name']) {
                    $searches[] = '[' . $text['name'] . ']';
                    $replaces[] = $text['name'];
                }
            }
            if ($searches) {
                $layout = str_replace($searches, $replaces, $layout);
//dd($layout);
                return $layout;
            }
        }
    }
});

api_expose_admin('save_dynamic_text');
function save_dynamic_text($data)
{
    if (!is_admin()) {
        return;
    }

    if (isset($data['id']) && $data['id'] == 0) {
        unset($data['id']);
    }
    if (isset($data['name'])) {
        $data['name'] = url_title($data['name']);
        $check = get_dynamic_text('single=1&name=' . $data['name']);
        if ($check and isset($check['id'])) {
            $data['id'] = $check['id'];
        }
    }
    $data['allow_html'] = true;
    $table = "dynamic_text_variables";
    return db_save($table, $data);
}

api_expose_admin('get_dynamic_text');
function get_dynamic_text($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }
    $params['table'] = "dynamic_text_variables";
    return db_get($params);
}

api_expose_admin('delete_dynamic_text');
function delete_dynamic_text($params)
{
    if (!is_admin()) {
        return;
    }
    if (isset($params['id'])) {
        $table = "dynamic_text_variables";
        $id = $params['id'];
        return db_delete($table, $id);
    }
}
