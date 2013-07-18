<?php



class CategoryUtils {




    static function save($data, $preserve_cache = false) {

        $adm = is_admin();
        if ($adm == false) {
            error('Ony admin can save category');
        }

        $table = MW_TABLE_PREFIX . 'categories';
        $table_items = MW_TABLE_PREFIX . 'categories_items';

        $content_ids = false;

        if (isset($data['rel']) and ($data['rel'] == '') or !isset($data['rel'])) {
            $data['rel'] = 'content';
        }
        if (isset($data['content_id'])) {

            if (is_array($data['content_id']) and !empty($data['content_id']) and trim($data['data_type']) != '') {
                $content_ids = $data['content_id'];
            }
        }
        $no_position_fix = false;
        if (isset($data['rel']) and isset($data['rel_id']) and trim($data['rel']) != '' and trim($data['rel_id']) != '') {

            $table = $table_items;
            $no_position_fix = true;
        }
        if (isset($data['table']) and ($data['table'] != '')) {
            $table = $data['table'];
        }
        //$data['debug'] = '1';
        //d($data);

        if (isset($data['rel']) and isset($data['rel_id']) and trim($data['rel']) == 'content' and intval($data['rel_id']) != 0) {

            $cs = array();
            $cs['id'] = intval($data['rel_id']);
            $cs['subtype'] = 'dynamic';
            $table_c = MW_TABLE_PREFIX . 'content';
            $save = save_data($table_c, $cs);

        }

        $save = save_data($table, $data);

        cache_clean_group('categories' . DIRECTORY_SEPARATOR . $save);
        if (isset($data['id'])) {
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . intval($data['id']));
        }
        if (isset($data['parent_id'])) {
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . intval($data['parent_id']));
        }
        if (intval($save) == 0) {

            return false;
        }

        $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';

        $sid = session_id();

        $id = $save;

        $clean = " update $custom_field_table set
	rel =\"categories\"
	, rel_id =\"{$id}\"
	where
	session_id =\"{$sid}\"
	and (rel_id=0 or rel_id IS NULL) and rel =\"categories\"

	";


        db_q($clean);
        cache_clean_group('custom_fields');

        $media_table =  MW_TABLE_PREFIX . 'media';

        $clean = " update $media_table set

	rel_id =\"{$id}\"
	where
	session_id =\"{$sid}\"
	and rel =\"categories\" and (rel_id=0 or rel_id IS NULL)

	";


        cache_clean_group('media');

        db_q($clean);




        if (isset($content_ids) and !empty($content_ids)) {

            $content_ids = array_unique($content_ids);

            // p($content_ids, 1);

            $data_type = trim($data['data_type']) . '_item';

            $content_ids_all = implode(',', $content_ids);

            $q = "delete from $table where rel='content'
		and content_type='post'
		and parent_id=$save
		and  data_type ='{$data_type}' ";

            // p($q,1);

            dq_q($q);

            foreach ($content_ids as $id) {

                $item_save = array();

                $item_save['rel'] = 'content';

                $item_save['rel_id'] = $id;

                $item_save['data_type'] = $data_type;

                $item_save['content_type'] = 'post';

                $item_save['parent_id'] = intval($save);

                $item_save = save_data($table_items, $item_save);

                cache_clean_group('content' . DIRECTORY_SEPARATOR . $id);
            }
        }









        if ($preserve_cache == false) {

            cache_clean_group('categories' . DIRECTORY_SEPARATOR . $save);
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . '0');
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . 'global');
        }

        return $save;
    }

    static function delete($data) {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.'.__FILE__.__LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            db_delete_by_id('categories', $c_id);
            db_delete_by_id('categories', $c_id, 'parent_id');
            db_delete_by_id('categories_items', $c_id, 'parent_id');
            if (defined("MODULE_DB_MENUS")) {
                db_delete_by_id('menus', $c_id, 'categories_id');
            }


            //d($c_id);
        }
    }




   static function reorder($data) {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.'.__FILE__.__LINE__);
        }

        $table = MW_TABLE_PREFIX . 'categories';
        foreach ($data as $value) {
            if (is_arr($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $i++;
                }

                db_update_position($table, $indx);
                return true;
                // d($indx);
            }
        }
    }

}