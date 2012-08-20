<?

/**
 * make_custom_field
 *
 * @desc make_custom_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */
api_expose('make_custom_field');

function make_custom_field($field_id = 0, $field_type = 'text', $settings = false) {
    $data = false;
    $form_data = array();
    if (is_array($field_id)) {
        if (!empty($field_id)) {
            $data = $field_id;
        }
    } else {
        if ($field_id != 0) {
//
            error('can steal data', __FILE__, __LINE__);
            $form_data = db_get_id('table_custom_fields', $id = $field_id, $is_this_field = false);
        }
    }

    if (isset($data) and is_array($data)) {
        if (!empty($data)) {
            if (isset($data['custom_field_type'])) {
                $field_type = $data['custom_field_type'];
            }
            if (isset($data['type'])) {
                $field_type = $data['type'];
            }
        }
    }
    if (isset($data['settings'])) {
        $settings = $data['settings'];
    }
    $dir = dirname(__FILE__);
    $dir = $dir . DS . 'custom_fields' . DS;
    $field_type = str_replace('..', '', $field_type);
    if ($settings == true) {
        $file = $dir . $field_type . '_settings.php';
    } else {
        $file = $dir . $field_type . '.php';
    }





    define_constants();
    $l = new View($file);

    $l->params = $data;
    $l->data = $form_data;
    // var_dump($l);
    //$l->set($l);

    $l = $l->__toString();
    // var_dump($l);
    $l = parse_micrwober_tags($l, $options = array('parse_only_vars' => 1));






    return $l;
}

api_expose('save_custom_field');

function save_custom_field($data) {
    $id = user_id();
    if ($id == 0) {
        error('Error: not logged in.');
    }
    $id = is_admin();
    if ($id == false) {
        error('Error: not logged in as admin.');
    }
    $data_to_save = ($data);


    $cms_db_tables = c('db_tables');

    $table_custom_field = $cms_db_tables ['table_custom_fields'];

    if (isset($data_to_save ['for'])) {
        $data_to_save ['to_table'] = guess_table_name($data_to_save ['for']);
    }

    //  $data_to_save['debug'] = 1;


    $save = save_data($table_custom_field, $data_to_save);

    cache_clean_group('custom_fields');

    return $save;




    //exit
}

api_expose('remove_field');

function remove_field($id) {
    $uid = user_id();
    if ($uid == 0) {
        error('Error: not logged in.');
    }
    $uid = is_admin();
    if ($uid == false) {
        exit('Error: not logged in as admin.');
    }
    if (is_array($id)) {
        extract($id);
    } else {

    }

    $id = intval($id);




    if ($id == 0) {

        return false;
    }
    $cms_db_tables = c('db_tables');
    $custom_field_table = $cms_db_tables ['table_custom_fields'];
    $q = "DELETE FROM $custom_field_table where id='$id'";

    db_q($q);


    cache_clean_group('custom_fields');


    return true;
}

/**
 * make_field
 *
 * @desc make_field
 * @access      public
 * @category    forms
 * @author      Microweber
 * @link        http://microweber.com
 * @param string $field_type
 * @param string $field_id
 * @param array $settings
 */
function make_field($field_id = 0, $field_type = 'text', $settings = false) {
    if (is_array($field_id)) {
        if (!empty($field_id)) {
            $data = $field_id;
        }
    } else {
        if ($field_id != 0) {

            //print $field_id;

            $data = db_get_id('table_custom_fields', $id = $field_id, $is_this_field = false);
            //p($data);
            //getById($table, $id = 0, $is_this_field = false)
            //exit('$field_id' . $field_id);
        }
    }
    if (isset($data['type'])) {
        $field_type = $data['type'];
    }


    $dir = dirname(__FILE__);
    $dir = $dir . DS . 'custom_fields' . DS;
    $field_type = str_replace('..', '', $field_type);
    if ($settings == true) {
        $file = $dir . $field_type . '_settings.php';
    } else {
        $file = $dir . $field_type . '.php';
    }




    $l = new View($file);
    //

    if (isset($data) and !empty($data)) {
        $l->data = $data;
    } else {
        $l->data = array();
    }

    $layout = $l->__toString();




    return $layout;
}