<?php


namespace MicroweberPackages\App\Utils;


use MicroweberPackages\App\Utils\ParserHelpers\ParserLayoutItem;
use MicroweberPackages\App\Utils\ParserHelpers\ParserModuleItem;

Trait ParserEditFieldsTrait
{


    public $_mw_parser_passed_hashes = array();
    public $_mw_parser_passed_hashes_rel = array();
    public $_mw_parser_passed_replaces = array();
    public $_mw_edit_field_map = array();
    public $_mw_parser_passed_replaces_inner = array();


    // _replaced_codes  tags
    public $_replaced_codes = array();
    public $_mw_parser_replaced_html_comments = array();
    public $_replaced_input_tags = array();
    public $_replaced_input_tags_inner_loops = array();
    public $_replaced_tags = array();


    public $_current_parser_rel = false;
    public $_current_parser_field = false;
    public $_current_parser_from_parent = false;
    public $_current_parser_from_parent_id = false;


    public $_current_parser_rel_prevoius = false;
    public $_current_parser_field_previous = false;

    public function _replace_editable_fields($layout, $no_cache = false, $from_parent = false, $coming_from_parent_id = false,$prevous_mod_obj=false)
    {

        global $mw_replaced_edit_fields_vals;
        global $mw_parser_nest_counter_level;
        global $mw_replaced_edit_fields_vals_inner;
        if (!isset($parser_mem_crc)) {
            $parser_mem_crc = 'parser_' . crc32($layout) . content_id();
            //   $parser_modules_crc = 'parser_modules' . crc32($layout) . content_id();
        }
       // $prevous_mod_obj = false;

        if (isset($this->_mw_parser_passed_replaces[$parser_mem_crc]) and !$no_cache) {
            if (isset($this->_mw_edit_field_map[$parser_mem_crc]) and isset($this->_mw_edit_field_map[$parser_mem_crc]['field']) and isset($this->_mw_edit_field_map[$parser_mem_crc]['rel'])) {
                $this->_current_parser_field = $this->_mw_edit_field_map[$parser_mem_crc]['field'];
                $this->_current_parser_rel = $this->_mw_edit_field_map[$parser_mem_crc]['rel'];
                $this->_current_parser_rel_prevoius = $this->_mw_edit_field_map[$parser_mem_crc]['rel_parent'];
                $this->_current_parser_field_previous = $this->_mw_edit_field_map[$parser_mem_crc]['field_parent'];
            }

           return $this->_mw_parser_passed_replaces[$parser_mem_crc];
        }





        if ($layout != '') {

            ++$mw_parser_nest_counter_level;
            $replaced_code_tags = array();
            $replaced_html_comment_tags = array();
            if ($this->_mw_parser_passed_replaces == null) {
                $this->_mw_parser_passed_replaces = array();
            }

            $mw_found_elems = '';
            $mw_found_elems_arr = array();
            $mw_elements_array = array('orig', $layout);
            $cached = false;
            $layout = $this->_replace_tags_with_placeholders($layout);


            $ch = mw_var($parser_mem_crc);
            if ($cached != false) {
                $mw_elements_array = $cached;
            } elseif ($ch != false) {
                $layout = $ch;
            } else {
                $pq = \phpQuery::newDocument($layout);
                $els = $pq['.edit'];
                $is_editable = true;
                foreach ($els as $elem) {

                    // iteration returns PLAIN dom nodes, NOT phpQuery objects
                    $tagName = $elem->tagName;
                    $name = pq($elem)->attr('field');
                    if (strval($name) == '') {
                        $name = pq($elem)->attr('data-field');
                    }
                    $rel = pq($elem)->attr('rel');
                    if ($rel == false) {
                        $rel = pq($elem)->attr('data-rel');
                        if ($rel == false) {
                            $rel = 'page';
                        }
                    }
                    $option_group = pq($elem)->attr('data-option_group');
                    if ($option_group == false) {
                        $option_group = 'editable_region';
                    }
                    $data_id = pq($elem)->attr('data-id');
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('rel-id');
                    }
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('rel_id');
                    }
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('data-rel-id');
                    }
                    $option_mod = pq($elem)->attr('data-module');
                    if ($option_mod == false) {
                        $option_mod = pq($elem)->attr('data-type');
                    }

                    if ($option_mod == false) {
                        $option_mod = pq($elem)->attr('type');
                    }
                    $name = trim($name);
                    $get_global = false;
                    $field = $name;
                    $use_id_as_field = $name;



                  /*
                    if ($rel == 'global') {

                        $get_global = true;
                    } else {
                        $get_global = false;
                    }

                    $try_inherited = false;

                    if ($rel == 'content' or $rel == 'page' or $rel == 'post') {


                        if ($rel == 'page') {
                            if (!isset($data_id) or $data_id == false) {
                                $data_id = page_id();
                            }
                        }
                        if ($rel == 'post') {
                            if (!isset($data_id) or $data_id == false) {
                                $data_id = post_id();;
                            }
                            if (!isset($data_id) or $data_id == false) {
                                $data_id = page_id();
                            }
                        }
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = content_id();
                        }

                        $get_global = false;
                        $data_id = intval($data_id);
                        $data = app()->content_manager->get_by_id($data_id);
                        if ($data == false) {
                            $data = array();
                        }
//                        if (!$this->_is_native_content_table_field($field)) {
//                            $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=" . $data_id);
//
//                        }


                    } elseif ($rel == 'inherit') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = page_id();
                        }
//                        $data_inh_check = app()->content_manager->get_by_id($data_id);
//
//                        if (isset($data_inh_check['id']) and isset($data_inh_check['layout_file']) and (trim($data_inh_check['layout_file']) != '') and $data_inh_check['layout_file'] != 'inherit') {
//                            $inh = $data_inh_check['id'];
//                        } else {
//                            $inh = app()->content_manager->get_inherited_parent($data_id);
//                        }
                        $inh = app()->content_manager->get_inherited_parent($data_id);

                        if ($inh != false and intval($inh) != 0) {
                            $try_inherited = true;
                            $data_id = $inh;
                            // $rel = 'content';
                            $data = app()->content_manager->get_by_id($data_id);
                        } else {
                            // $rel = 'content';

                            $data = app()->content_manager->get_page($data_id);
                        }

                        if (!$this->_is_native_content_table_field($field)) {
                            $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=" . $data_id);
                        }
                    } elseif ($rel == 'global') {
                        $get_global = 1;
                        $cont_field = false;
                    } elseif ($rel == 'module') {

                        $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}");

                    }*/

                    /* elseif (isset($attr['post'])) {
                        $get_global = false;
                        $data = app()->content_manager->get_by_id($attr['post']);
                        if ($data == false) {
                            $data = app()->content_manager->get_page($attr['post']);
                        }
                    } elseif (isset($attr['category'])) {
                        $get_global = false;
                        $data = app()->category_manager->get_by_id($attr['category']);
                    } elseif (isset($attr['global'])) {
                    } elseif (isset($attr['global'])) {
                        $get_global = true;
                    }*/
                    $cf = false;
                    $field_content = false;
                    $field_content_modified_date = false;
                    $orig_rel = $rel;









                    $isReg = $this->registry->isParsedEditField($field, $rel, $data_id);

                    if ($isReg) {
                        continue;
                    }
                    $this->_current_parser_rel_prevoius =  $this->_current_parser_rel;
                    $this->_current_parser_field_previous =  $this->_current_parser_field;

                    $this->_current_parser_rel = $rel;
                    $this->_current_parser_field = $field;


                    $this->registry->registerParsedEditField($field, $rel, $data_id);





                    if (!empty($this->filter)) {
                        foreach ($this->filter as $filter) {
                            if (isset($data)) {
                                $new_data = call_user_func($filter, $data, $elem);
                                if (is_array($new_data) and !empty($new_data)) {
                                    $data = array_merge($data, $new_data);
                                }
                            }
                        }
                    }







                    $edit_field_content = $this->_edit_field_content_get($field,$rel,$data_id);

                    if (isset($data['updated_at'])) {
                        $field_content_modified_date = $data['updated_at'];
                    }



                    $this->_current_parser_rel = $rel;
                    $this->_current_parser_field = $field;


                    $no_edit = false;




                    //$mw_replaced_edit_fields_vals[$parser_mem_crc] = $edit_field_content;

                    if ($edit_field_content) {
                        $field_content = $edit_field_content;

                    }





                    if ($field_content != false and $field_content != '' and is_string($field_content)) {


                        $parser_mem_crc2 = 'parser_field_content_' . $field . $rel . $data_id . crc32($field_content);

                        $ch2 = mw_var($parser_mem_crc);

                        if ($ch2 == false) {
                            $this->_mw_parser_passed_hashes[] = $parser_mem_crc2;
                            $this->_mw_parser_passed_hashes_rel[$rel][] = $parser_mem_crc2;
                            if ($field_content != false and $field_content != '') {
                                $mw_replaced_edit_fields_vals[$parser_mem_crc2] = $ch2;
                                $parser_mem_crc3 = 'mw_replace_back_this_editable_' . $parser_mem_crc2 . '';

                                $elem_clone = $elem->cloneNode();

                                $mw_found_elems = ',' . $parser_mem_crc2;
                                $mw_found_elems_arr[$parser_mem_crc2] = $field_content;
                                // $rep = pq($elem)->html();
                                // $rep = pq($elem)->html();
                                //  $rep = trim($rep);
                                //   $rep = preg_replace("/(^\s+)|(\s+$)/us", "", $rep);


                                if ($no_edit != false or (isset($data) and isset($data['no_edit']) and $data['no_edit'] != false)) {
                                    $is_editable = false;
                                    if ($is_editable === false) {
                                        pq($elem)->removeClass('edit');
                                    } else {
                                    }
                                    $is_editable = 1;
                                }
                                //   $parser_mem_crc2_inner = 'parser_' . crc32($rep) . content_id();

                                if (strstr($field_content, '<inner-edit-tag>mw_saved_inner_edit_from_parent_edit_field</inner-edit-tag>')) {
                                    // $field_content = $this->_replace_editable_fields($field_content);
                                    $field_content = $this->_replace_editable_fields($field_content, $no_cache = false, $from_parent = $layout,$coming_from_parent_id,$prevous_mod_obj);
                                    if ($field_content) {
                                        // $mw_replaced_edit_fields_vals_inner[$parser_mem_crc3] = array('s' => $rep, 'r' => $field_content, 'rel' => $rel, 'field' => $field);
                                        pq($elem_clone)->html($field_content);
                                    }
                                } else {
                                    pq($elem_clone)->html($field_content);
                                }

                                if (defined('IN_EDIT') and IN_EDIT == true and $field_content_modified_date) {
                                    pq($elem_clone)->attr('itemprop', 'dateModified');
                                    pq($elem_clone)->attr('content', date("Y M d", strtotime($field_content_modified_date)));
                                    //  pq($elem_clone)->attr('itemscope','');
                                    //  pq($elem_clone)->attr('itemtype','http://schema.org/CreativeWork');

                                }

                                if (!$elem->parentNode) {
                                  continue;
                                }

                                $elem_clone_content = pq($elem_clone)->htmlOuter();
                                $elem_clone_content= $this->_edit_field_add_modules_for_processing($elem_clone_content, $field,$rel,$data_id,$prevous_mod_obj);


                                pq($elem)->replaceWith($elem_clone_content);


                                $this->_current_parser_rel_prevoius =  $this->_current_parser_rel;
                                $this->_current_parser_field_previous =  $this->_current_parser_field;

                                $this->_current_parser_rel = $rel;
                                $this->_current_parser_field = $field;



                        //        $this->_edit_field_add_modules_for_processing();


                                // $mw_replaced_edit_fields_vals_inner[$parser_mem_crc3] = array('s' => $rep, 'r' => $field_content, 'rel' => $rel, 'field' => $field);
                                $this->_mw_edit_field_map[$parser_mem_crc] = array(
                                    'field' => $field,
                                    'rel' => $rel,
                                    'rel_parent' => $this->_current_parser_rel_prevoius,
                                    'field_parent' => $this->_current_parser_field_previous,
                                );
                            }
                        } else {

                        }
                        mw_var($parser_mem_crc2, 1);


                    } else {


                     //   $elem_clone = $elem->cloneNode();

                        $el_html = pq($elem)->htmlOuter();

                       $elem_clone_content= $this->_edit_field_add_modules_for_processing($el_html, $field,$rel,$data_id,$prevous_mod_obj);

                       pq($elem)->replaceWith($elem_clone_content);


                        if (strstr($el_html, '<inner-edit-tag>mw_saved_inner_edit_from_parent_edit_field</inner-edit-tag>')) {
                            pq($elem)->html('<!-- edit_field_not_found_in_database -->');
                        }
                        $this->_mw_edit_field_map[$parser_mem_crc] = array(
                            'field' => $field,
                            'rel' => $rel,
                            'rel_parent' => $this->_current_parser_rel_prevoius,
                            'field_parent' => $this->_current_parser_field_previous,
                        );

                    }
                }

                $layout = $pq->htmlOuter();

                $pq->__destruct();


                $pq = null;

                unset($pq);
                if (!empty($mw_replaced_edit_fields_vals_inner)) {
                    $reps_arr = array();
                    $reps_arr2 = array();

                    foreach ($mw_replaced_edit_fields_vals_inner as $k => $v) {
                        if (isset($v['s'])) {
                            $reps_arr[] = $v['s'];
                            $reps_arr2[] = $v['r'];

                            $layout = $this->_str_replace_first($v['s'], $v['r'], $layout);
                            // $layout = str_ireplace($v['s'], $v['r'], $layout, $repc);

                            unset($mw_replaced_edit_fields_vals_inner[$k]);
                        }
                    }
                    // $layout = str_replace($reps_arr, $reps_arr2, $layout,$repc);
                }

                mw_var($parser_mem_crc, $layout);
                if ($mw_found_elems != '') {
                    $mw_elements_array['new'] = $layout;
                    $mw_elements_array['to_replace'] = $mw_found_elems;
                    $mw_elements_array['elems'] = $mw_found_elems_arr;
                } else {
                    $mw_elements_array['new'] = $layout;
                }
            }
        }

        if (isset($mw_elements_array) and !empty($mw_elements_array)) {
            if (isset($mw_elements_array['elems']) and isset($mw_elements_array['to_replace']) and isset($mw_elements_array['new'])) {
                $modified_layout = $mw_elements_array['new'];
                $reps = $mw_elements_array['elems'];

                $c = 1;
                foreach ($reps as $elk => $value) {
                    $elk_crc = crc32($elk);

                    $global_holder_hash = 'replaced' . $elk_crc;

                    if (!isset($mw_replaced_edit_fields_vals[$global_holder_hash])) {
                        $this->_mw_parser_passed_replaces[] = $elk_crc;
                        $mw_replaced_edit_fields_vals[$global_holder_hash] = $modified_layout;

                        if ($value != '') {
                            $val_rep = $value;
                            $have_more = $this->_do_we_have_more_edit_fields_for_parse($value);
                            if ($have_more) {
                                $val_rep = $this->_replace_editable_fields($val_rep, $no_cache = false, $from_parent = $layout,$coming_from_parent_id,$prevous_mod_obj);
                            }


                            $rep = 'mw_replace_back_this_editable_' . $elk . '';
                            $ct = 1;

                            //   $modified_layout = str_replace($rep, $val_rep, $modified_layout,$ct);
                            $modified_layout = $this->_str_replace_first($rep, $val_rep, $modified_layout);
                        }
                    } else {
                        $rep = 'mw_replace_back_this_editable_' . $elk . '';
                        $modified_layout = $this->_str_replace_first($rep, $value, $modified_layout);

                        // $modified_layout = str_replace($rep, $value, $modified_layout);
                    }
                }

                $layout = $modified_layout;
                $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;
            }

            if (!empty($replaced_code_tags)) {
                foreach ($replaced_code_tags as $key => $value) {
                    if ($value != '') {
                        $layout = str_replace($key, $value, $layout);
                    }
                    unset($replaced_code_tags[$key]);
                }
            }

            if ($no_cache == false) {
                //    app()->cache_manager->save($layout, $parser_mem_crc, 'content_fields/global/parser');
            }
        }

        $this->_mw_parser_passed_replaces[$parser_mem_crc] = $layout;
        $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;

        return $layout;
    }


    private function _do_we_have_more_edit_fields_for_parse($layout)
    {
        $proceed_with_parse = false;
        preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $modinner);

        if (!empty($modinner) and isset($modinner[0][0])) {
            foreach ($modinner[0] as $item) {

                //  preg_match_all($pattern,$item,$matches,PREG_SET_ORDER);
                $result = $this->utils->parseAttributes($item);

                $ed_fields_attr = $this->utils->getEditFieldAttributesFromParsedAttributes($result);


                if (isset($ed_fields_attr['field']) and isset($ed_fields_attr['rel'])) {
                    $proceed_with_parse = true;

                    //$reg = $this->registry->editFieldRegistry;
                    $reg = $this->registry->isParsedEditField($ed_fields_attr['field'], $ed_fields_attr['rel'], $ed_fields_attr['rel_id']);
                    if ($reg) {
                        $proceed_with_parse = false;
                    }
                }
//               if(isset($result['field']) or isset($result['data-field'])){
//                   $proceed_with_parse = true;
//               }

            }
        }

        return $proceed_with_parse;
    }


    private function _str_replace_first($search, $replace, $subject)
    {
        if ($search == false || $replace == false) {
            return $subject;
        }
        if (!is_string($search)) {
            return $subject;
        }

        $pos = strpos($subject, (string)$search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }



    public function _edit_field_add_modules_for_processing($layout, $field = false,$rel = false,$rel_id=false,$prevous_mod_obj=false )
    {

        $parser_mem_crc = 'parser_' . crc32($layout);
        if (isset($this->_mw_parser_passed_replaces[$parser_mem_crc])) {
            return $this->_mw_parser_passed_replaces[$parser_mem_crc];
        }

        $layout_obj_elem = new ParserLayoutItem($layout);

        $pq = $layout_obj_elem->getPq();

        $els = $pq['module'];

        if($els) {

            foreach ($els as $key => $elem) {
                // $elem_clone = $elem->cloneNode();

                $value = pq($elem)->htmlOuter();

                if ($value != '') {
                    $v1 = crc32($value) . '-' .  $key . '-' . $rel . '-' . $field;


                    if ($prevous_mod_obj) {
                        $v1 = $v1 . '-' . $prevous_mod_obj->getId();

                    }

                    $v1 = '<mw-unprocessed-module-tag>mw_replace_back_this_module_for_processing_' . $v1 . '</mw-unprocessed-module-tag>';
                    if (!$this->parser_modules_collection->has($v1)) {

                        $attrs = $this->utils->parseAttributes($value);



                        pq($elem)->replaceWith($v1);


                        $newItem = new ParserModuleItem();
                        $newItem->setEditFieldRel($rel);
                        $newItem->setEditFieldRelId($rel_id);
                        $newItem->setEditField($field);
                        $newItem->setReplaceKey($v1);
                        $newItem->setReplaceValue($value);
                        $newItem->setAttributes($attrs);
                        if ($prevous_mod_obj) {
                            $newItem->setParent($prevous_mod_obj);
                        }
                        $this->parser_modules_collection->add($v1, $newItem);
                    }

                }


            }
        }

        $pq = $layout_obj_elem->getPq();
        $layout =$pq->htmlOuter();


        $this->_mw_parser_passed_replaces[$parser_mem_crc] = $layout;

        $pq->__destruct();
        $layout_obj_elem = null;
        unset($layout_obj_elem);
        return $layout;

    }

    /**
     * _replace_tags_with_placeholders

     */
    private function _replace_tags_with_placeholders($mod_content)
    {







        $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";
        preg_match_all($script_pattern, $mod_content, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<tag>mw_replace_back_this_script_mod_inner112_' . $v1 . '</tag>';
                    $mod_content = str_replace($value, $v1, $mod_content);
                    if (!isset( $this->_replaced_tags[$v1])) {
                        $this->_replaced_tags[$v1] = $value;

                    }
                }
            }
        }



   $script_pattern = "/<textarea[^>]*>(.*)<\/textarea>/Uis";
        preg_match_all($script_pattern, $mod_content, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<tag>mw_replace_back_this_textarea_mod_inner_' . $v1 . '</tag>';
                    $mod_content = str_replace($value, $v1, $mod_content);
                    if (!isset( $this->_replaced_tags[$v1])) {
                        $this->_replaced_tags[$v1] = $value;

                    }
                }
            }
        }








//        $pq_mod_inner = \phpQuery::newDocument($mod_content);
//        $els_mod_inner = $pq_mod_inner['.edit'];


        $tags = ['script','code','textarea','style','select'];

        foreach ($tags as $tag){

          //  $script_pattern = "/<".$tag."[^>]*>(.*)<\/.$tag.>/Uis";
            $script_pattern =  "/\<".$tag."(.*?)?\>(.|\s)*?\<\/".$tag."\>/i";

            preg_match_all($script_pattern, $mod_content, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value);
                        $v1 = '<tag>mw_replace_back_this_tag_' . $tag .$v1 . '</tag>';
                        $mod_content = str_replace($value, $v1, $mod_content);

                        if (!isset( $this->_replaced_tags[$v1])) {
                            $this->_replaced_tags[$v1] = $value;

                        }
                    }
                }
            }

        }






        $script_pattern = "/<!--(?!<!)[^\[>].*?-->/";
        preg_match_all($script_pattern, $mod_content, $mw_script_matches);
        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<tag-comment>mw_replace_back_this_html_comment_code_' . $v1 . '</tag-comment>';
                    $mod_content = str_replace($value, $v1, $mod_content);
                    if (!isset($this->_mw_parser_replaced_html_comments[$v1])) {
                        $this->_mw_parser_replaced_html_comments[$v1] = $value;
                    }
                }
            }
        }





        return $mod_content;
    }

    /**
     * _replace_tags_with_placeholders_back
     */
    public function _replace_tags_with_placeholders_back($layout){


        if (!empty($this->_replaced_tags)) {
            foreach ($this->_replaced_tags as $key => $value) {
                if ($value != '') {
                    $layout = str_replace($key, $value, $layout);
                }
            }
        }


        if (!empty($this->_mw_parser_replaced_html_comments)) {
            foreach ($this->_mw_parser_replaced_html_comments as $key => $value) {
                if ($value != '') {
                    $layout = str_replace($key, $value, $layout);
                }
             }
        }




        return $layout;



    }



    public function module_name_decode($module_name)
    {
        $module_name = str_replace('__', '/', $module_name);

        return $module_name;
    }

    public function module_name_encode($module_name)
    {
        $module_name = str_replace('/', '__', $module_name);
        $module_name = str_replace('\\', '__', $module_name);

        return $module_name;
    }

    public function module_css_class($module_name)
    {
        $module_class = str_replace('/', '-', $module_name);
        $module_class = str_replace('\\', '-', $module_class);
        $module_class = str_replace(' ', '-', $module_class);
        $module_class = str_replace('%20', '-', $module_class);
        $module_class = str_replace('_', '-', $module_class);
        $module_class = 'module-' . strtolower($module_class);

        return $module_class;
    }


    public function _is_native_content_table_field($field)
    {
        if($field == 'content' or
            $field == 'content_body' or
            $field == 'description' or
            $field == 'title'){
            return true;
        }
        return false;
    }

    public function _is_native_category_table_field($field)
    {
        if($field == 'description' or
            $field == 'title'){
            return true;
        }
        return false;
    }
    private function _str_clean_mod_id($mod_id)
    {
        $mod_id = str_replace(' ', '-', $mod_id);
        $mod_id = str_replace('/', '-', $mod_id);
        $mod_id = str_replace('\\', '-', $mod_id);
        $mod_id = str_replace('_', '-', $mod_id);
        $mod_id = str_replace(';', '-', $mod_id);
        $mod_id = str_replace('.', '-', $mod_id);
        $mod_id = str_replace('#', '-', $mod_id);
        //   $mod_id = str_replace('--', '', $mod_id);
        $mod_id = strtolower($mod_id);
        $mod_id = trim($mod_id);
        return $mod_id;
    }


    public function replace_url_placeholders($layout)
    {
        if (defined('TEMPLATE_URL')) {
            $replaces = array(
                '{TEMPLATE_URL}',
                '{THIS_TEMPLATE_URL}',
                '{DEFAULT_TEMPLATE_URL}',
                '%7BTEMPLATE_URL%7D',
                '%7BTHIS_TEMPLATE_URL%7D',
                '%7BDEFAULT_TEMPLATE_URL%7D',
            );


            $replaces_vals = array(
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL,
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL
            );

            //        $layout = str_replace($replaces, $replaces_vals, $layout);
            $layout = str_replace_bulk($replaces, $replaces_vals, $layout);
        }
        return $layout;
    }


    public function _edit_field_content_get($field, $rel, $data_id){




        $field_content = false;

        $use_id_as_field = $field;
        $option_mod = $rel;
        if ($rel == 'global') {

            $get_global = true;
        } else {
            $get_global = false;
        }

        $try_inherited = false;

        if ($rel == 'content' or $rel == 'page' or $rel == 'post') {


            if ($rel == 'page') {
                if (!isset($data_id) or $data_id == false) {
                    $data_id = page_id();
                }
            }
            if ($rel == 'post') {
                if (!isset($data_id) or $data_id == false) {
                    $data_id = post_id();;
                }
                if (!isset($data_id) or $data_id == false) {
                    $data_id = page_id();
                }
            }
            if (!isset($data_id) or $data_id == false) {
                $data_id = content_id();
            }

            $get_global = false;
            $data_id = intval($data_id);
            $data = array();
            if($data_id != 0){
                $data = app()->content_manager->get_by_id($data_id);
            } else {
                return false;
            }



            if (!$this->_is_native_content_table_field($field)) {
                $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=" . $data_id);
            }


        } elseif ($rel == 'category' or $rel == 'categories') {
            $get_global = false;
            if (!isset($data_id) or $data_id == false) {
                $data_id = category_id();
            }
            if($data_id != 0){
                $data = app()->category_repository->getById($data_id);
            } else {
                return false;
            }


            if (!$this->_is_native_category_table_field($field)) {
                $data[$field] = app()->content_manager->edit_field("rel_type=categories&field={$field}&rel_id=" . $data_id);
            }



        } elseif ($rel == 'inherit') {

            $get_global = false;
            if (!isset($data_id) or $data_id == false) {
                $data_id = page_id();
            }
//                        $data_inh_check = app()->content_manager->get_by_id($data_id);
//
//                        if (isset($data_inh_check['id']) and isset($data_inh_check['layout_file']) and (trim($data_inh_check['layout_file']) != '') and $data_inh_check['layout_file'] != 'inherit') {
//                            $inh = $data_inh_check['id'];
//                        } else {
//                            $inh = app()->content_manager->get_inherited_parent($data_id);
//                        }
            $inh = app()->content_manager->get_inherited_parent($data_id);

            if ($inh != false and intval($inh) != 0) {
                $try_inherited = true;
                $data_id = $inh;
                // $rel = 'content';
                $data = app()->content_manager->get_by_id($data_id);
            } else {
                // $rel = 'content';

                $data = app()->content_manager->get_page($data_id);
            }

            if (!$this->_is_native_content_table_field($field)) {
                $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=" . $data_id);
            }
        } elseif ($rel == 'global') {
            $get_global = 1;
            $cont_field = false;
        } elseif ($rel == 'module') {
if($data_id != false){

    $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=" . $data_id);

} else {
    $data[$field] = app()->content_manager->edit_field("rel_type={$rel}&field={$field}");

}

        }

        if (isset($data[$field])) {

               return $data[$field];

        } else {


            if ($rel == 'page') {
                $rel = 'content';
            }
            if ($rel == 'post') {
                $rel = 'content';
            }


            $cont_field = false;
            if (isset($data_id) and $data_id != 0 and trim($data_id) != '' and trim($field) != '') {
                $cont_field = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");
                if ($cont_field == false and $try_inherited == true) {
                    $inh = app()->content_manager->get_inherited_parent($data_id);
                    if ($inh != false and intval($inh) != 0 and $inh != $data_id) {
                        $data_id = $inh;
                        $cont_field2 = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$inh");
                        if ($cont_field2 != false) {
                            $rel = 'content';
                            $data = app()->content_manager->get_by_id($inh);
                            $cont_field = $cont_field2;
                            return $cont_field;
                        }
                    }
                }
            } else {

                if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {

                    if ($rel == 'content' and $this->_is_native_content_table_field($field)) {

                        if (isset($data) and isset($data[$field])) {
                            $field_content = $data[$field];

                            return $field_content;
                        }
                        return false;
                    } else {
                        $cont_field = app()->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");

                        return $cont_field;

                    }



                } else {



                    $field_content = $cont_field = app()->content_manager->edit_field("rel_type={$rel}&field={$field}");
return $field_content;

                }
            }

            if ($cont_field != false) {
                //  $field_content = $cont_field;
            }

        }
        if ($rel == 'global') {
            $field_content = false;
            $get_global = 1;


            $cont_field = app()->content_manager->edit_field("rel_type={$rel}&field={$field}");
            return $cont_field;

//            if ($cont_field == false) {
//                if ($option_mod != false) {
//                    $cont_field = app()->content_manager->edit_field("rel_type={$option_group}&field={$field}");
//                } else {
//                    $cont_field = app()->content_manager->edit_field("rel_type={$option_group}&field={$field}");
//                }
//            } else {
//                $cont_field = $field_content = $cont_field;
//            }
        }

        $edit_field_content = false;

        if (isset($data[$field])) {

            $edit_field_content = $data[$field];
        }
        if ($use_id_as_field != false) {

            if (isset($data[$use_id_as_field])) {
                $edit_field_content = $data[$use_id_as_field];

            }
        }
        if (!$edit_field_content) {
            if (isset($cont_field['value'])) {

                $edit_field_content = $cont_field['value'];
            }
        }

        return $edit_field_content;


    }


}
