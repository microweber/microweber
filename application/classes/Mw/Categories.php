<?php
namespace mw;


class Categories extends Category {




    public function save($data, $preserve_cache = false) {

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
            $save = mw('db')->save($table_c, $cs);

        }

        $save = mw('db')->save($table, $data);

        mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . $save);
        if (isset($data['id'])) {
            mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . intval($data['id']));
        }
        if (isset($data['parent_id'])) {
            mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . intval($data['parent_id']));
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


        mw('db')->q($clean);
        mw('cache')->clear('custom_fields');

        $media_table =  MW_TABLE_PREFIX . 'media';

        $clean = " update $media_table set

	rel_id =\"{$id}\"
	where
	session_id =\"{$sid}\"
	and rel =\"categories\" and (rel_id=0 or rel_id IS NULL)

	";


        mw('cache')->clear('media');

        mw('db')->q($clean);




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

            mw('db')->q($q);

            foreach ($content_ids as $id) {

                $item_save = array();

                $item_save['rel'] = 'content';

                $item_save['rel_id'] = $id;

                $item_save['data_type'] = $data_type;

                $item_save['content_type'] = 'post';

                $item_save['parent_id'] = intval($save);

                $item_save = mw('db')->save($table_items, $item_save);

                mw('cache')->clear('content' . DIRECTORY_SEPARATOR . $id);
            }
        }









        if ($preserve_cache == false) {

            mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . $save);
            mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . '0');
            mw('cache')->clear('categories' . DIRECTORY_SEPARATOR . 'global');
        }

        return $save;
    }

    public function delete($data) {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.'.__FILE__.__LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            mw('db')->delete_by_id('categories', $c_id);
            mw('db')->delete_by_id('categories', $c_id, 'parent_id');
            mw('db')->delete_by_id('categories_items', $c_id, 'parent_id');
            if (defined("MODULE_DB_MENUS")) {
                mw('db')->delete_by_id('menus', $c_id, 'categories_id');
            }


            //d($c_id);
        }
    }




   public function reorder($data) {

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

                mw('Mw\DbUtils')->update_position_field($table, $indx);
                return true;
                // d($indx);
            }
        }
    }

}