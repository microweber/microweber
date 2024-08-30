<?php

require_once(__DIR__ . DS . 'lib/php-diff/vendor/autoload.php');


//spl_autoload_register(function ($class) {
//    if (0===strpos($class, 'PhpDiff\\')){
//
//        //return require(__DIR__ . DS . 'lib' . DS . 'php-diff' . DS . 'src' . DS . '' . substr($class, 8) . '.php');
//    }
//});

event_bind('mw.admin.content.edit.advanced_settings', function ($data) {
    if (isset($data['id']) and isset($data['id'])!=0){
// @todo
//        $arr = array();
//        $arr['html'] = load_module('editor/content_revisions/btn_rev_count_for_content', array('content_id' => $data['id']));
//
//        mw()->module_manager->ui('mw.admin.content.edit.advanced_settings.end', $arr);
    }

});

event_bind('mw.content.save_edit', function ($data) {
    //@todo move on event listener
    if (!isset($data['is_draft']) and isset($data['field']) and isset($data['value']) and isset($data['rel_type']) and isset($data['rel_id'])){
        $table = 'content_revisions_history';
        $save = array();
        $save['field'] = $data['field'];
        $save['value'] = $data['value'];
        $save['rel_type'] = $data['rel_type'];
        $save['rel_id'] = $data['rel_id'];
        $save['checksum'] = md5($save['value']);
        $save['allow_html'] = true;
        $check = db_get('no_cache=true&count=1&table=' . $table . '&checksum=' . $save['checksum'] . '&rel_type=' . $save['rel_type'] . '&rel_id=' . $save['rel_id']);
        if (!$check){
            $old = DB::table($table)
                ->where('rel_type', $data['rel_type'])
                ->where('rel_id', $data['rel_id'])
                ->where('field', $data['field'])
                ->take(1000)
                ->skip(1000)
                ->get();
            if (!empty($old)){
                foreach ($old as $item) {
                    DB::table($table)->where('id', $item->id)->delete();
                }
            }

            db_save($table, $save);

        }

    }
});


function mw_text_render_diff_from_string($a, $b, $mode = 'inline') {

    $a = explode("\n", $a);
    $b = explode("\n", $b);

    // Options for generating the diff
    $options = array(
        'ignoreWhitespace' => true,
        'ignoreCase'       => true,
    );

    // Initialize the diff class
    $diff = new PhpDiff\Diff($a, $b, $options);
    switch ($mode) {
        case 'unified':
            $renderer = new PhpDiff\Renderer\Text\Unified;
            break;
        case 'inline':
            $renderer = new PhpDiff\Renderer\Html\Inline;
            break;
        case 'context':
            $renderer = new PhpDiff\Renderer\Text\Context;
            break;
        default:
            $renderer = new PhpDiff\Renderer\Html\SideBySide;
            break;

    }

    return $diff->render($renderer);
}


api_expose_admin('mw_drafts_load_content_field_to_editor', function ($params) {
    if (isset($params['id'])){
        $item = db_get('single=1&table=content_revisions_history&id=' . $params['id']);
        if ($item and isset($item['value'])){

            if(isset($params['return_full'])){
                $item['value'] = mw()->parser->process($item['value'], $options = false);
                return $item;
            }

            if (isset($item['value'])){
                $field_content = mw()->parser->process($item['value'], $options = false);

                return $field_content;
            }
        }
    }
});