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


    $data = save_data($table, $data);
    return $data;
}