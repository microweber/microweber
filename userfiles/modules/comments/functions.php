<?

/**
 * post_comment

 */
api_expose('post_comment');

function post_comment($data) {


    $cms_db_tables = c('db_tables');
    $table = $cms_db_tables['table_comments'];
    define('FORCE_SAVE', $table);

    if (isset($data['id'])) {
        if (is_admin() == false) {
            error('Error: Only admin can edit comments!');
        }
    }
    if (!isset($data['to_table'])) {
        error('Error: invalid data');
    }
    if (!isset($data['to_table_id'])) {
        error('Error: invalid data');
    }

    if (!isset($data ['captcha'])) {
        return array('error' => 'Please enter the captcha answer!');
    } else {
        $cap = session_get('captcha');
        if ($cap == false) {
            return array('error' => 'You must load a captcha first!');
        }
        if ($data ['captcha'] != $cap) {
            return array('error' => 'Invalid captcha answer!');
        }
    }


    $data = save_data($table, $data);
    return $data;
}

function get_comments($data) {


    $cms_db_tables = c('db_tables');
    $table = $cms_db_tables['table_comments'];

    $data['table'] = $table;

    $data = get($data);
    return $data;
}