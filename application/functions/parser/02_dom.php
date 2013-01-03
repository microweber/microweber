<?php

$dom = new MwDom();
libxml_use_internal_errors(true);
libxml_clear_errors();
$dom->preserveWhiteSpace = true;
$dom->strictErrorChecking = false;
$dom->formatOutput = false;
$dom->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $layout);
$xpath = new DOMXPath($dom);
$dom_edits = $xpath->query('//div[contains(@class,"edit")]');
// $dom_edits = $dom->getElementByClass("edit");

if ($dom_edits != false) {

    foreach ($dom_edits as $node) {

        $name = $node->getAttribute('field');

        if (strval($name) == '') {
            $name = $node->getAttribute('id');
        }

        if (strval($name) == '') {
            $name = $node->getAttribute('data-field');
        }


        // $fld_id = pq($elem)->attr('data-field-id');

        $rel = $node->getAttribute('rel');
        if ($rel == false) {
            $rel = 'page';
        }


        $option_group = $node->getAttribute('data-option_group');
        if ($option_group == false) {
            $option_group = 'editable_region';
        }
        $data_id = $node->getAttribute('data-id');


        $option_mod = $node->getAttribute('data-module');
        if ($option_mod == false) {
            $option_mod = $node->getAttribute('data-type');
        }
        if ($option_mod == false) {
            $option_mod = $node->getAttribute('type');
        }



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



        if ($field_content != false and $field_content != '') {
            $field_content = htmlspecialchars_decode($field_content);

            $field_content = parse_micrwober_tags($field_content);
        }
    }
}

unset($dom);