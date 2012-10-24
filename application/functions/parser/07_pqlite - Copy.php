<?php

require_once (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'PQLite' . DIRECTORY_SEPARATOR . 'PQLite.php');


$pq = new PQLite($layout);


$els = $pq->find('.edit');



foreach ($els[0]  as $elem) {
    // iteration returns PLAIN dom nodes, NOT phpQuery objects
    //$tagName = $elem->tagName;
    $name = $elem->getAttr('field');

    if (strval($name) == '') {
        $name = $elem->getAttr('id');
    }

    if (strval($name) == '') {
        $name = $elem->getAttr('data-field');
    }


    // $fld_id = $elem->getAttr('data-field-id');

    $rel = $elem->getAttr('rel');
    if ($rel == false) {
        $rel = 'page';
    }


    $option_group = $elem->getAttr('data-option_group');
    if ($option_group == false) {
        $option_group = 'editable_region';
    }
    $data_id = $elem->getAttr('data-id');


    $option_mod = $elem->getAttr('data-module');
    if ($option_mod == false) {
        $option_mod = $elem->getAttr('data-type');
    }
    if ($option_mod == false) {
        $option_mod = $elem->getAttr('type');
    }

d($name);

    $get_global = false;
    //  $rel = 'page';
    $field = $name;
    $use_id_as_field = $name;

    if ($rel == 'global') {
        $get_global = true;
    } else {
        $get_global = false;
    }

    if ($rel == 'module') {
        $get_global = true;
    }
    if ($get_global == false) {
        //  $rel = 'page';
    }


    if ($rel == 'content') {
        if ($data_id != false) {
            $data_id = intval($data_id);
            $data = get_content_by_id($data_id);
            $data ['custom_fields'] = get_custom_fields_for_content($data_id, 0);
        }
    } else if ($rel == 'page') {
        $data = get_page(PAGE_ID);
        $data ['custom_fields'] = get_custom_fields_for_content($data ['id'], 0);
    } else if (isset($attr ['post'])) {
        $data = get_post($attr ['post']);
        if ($data == false) {
            $data = get_page($attr ['post']);
            $data ['custom_fields'] = get_custom_fields_for_content($data ['id'], 0);
        }
    } else if (isset($attr ['category'])) {
        $data = get_category($attr ['category']);
    } else if (isset($attr ['global'])) {
        $get_global = true;
    }
    $cf = false;
    $field_content = false;

    if ($get_global == true) {


        if ($option_mod != false) {
            //   d($field);

            $field_content = get_option($field, $option_group, $return_full = false, $orderby = false, $option_mod);
            //
        } else {
            $field_content = get_option($field, $option_group, $return_full = false, $orderby = false);
        }
    } else {

        if ($use_id_as_field != false) {
            if (isset($data [$use_id_as_field])) {
                $field_content = $data [$use_id_as_field];
            }
            if ($field_content == false) {
                if (isset($data ['custom_fields'] [$use_id_as_field])) {
                    $field_content = $data ['custom_fields'] [$use_id_as_field];
                }
                // d($field_content);
            }
        }

        //  if ($field_content == false) {
        if (isset($data [$field])) {

            $field_content = $data [$field];
        }
        //}
    }

    if ($field_content == false and isset($data ['custom_fields']) and !empty($data ['custom_fields'])) {
        foreach ($data ['custom_fields'] as $kf => $vf) {

            if ($kf == $field) {

                $field_content = ($vf);
            }
        }
    }

    //  d($field);

    if ($field_content != false and $field_content != '') {
        $field_content = htmlspecialchars_decode($field_content);

        //$field_content = html_entity_decode($field_content, ENT_COMPAT, "UTF-8");
        // d($field_content);
        $field_content = parse_micrwober_tags($field_content);

        $elem->setInnerHTML($field_content);
    } else {

    }
}
$layout = $pq->getHTML();
$pq = null;
unset($pq);