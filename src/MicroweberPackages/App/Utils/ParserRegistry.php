<?php


namespace MicroweberPackages\App\Utils;


class ParserRegistry
{

    public $modulesRegistry = [];

    public $editFieldRegistry = [];


    public function registerParsedModule($module_name, $module_id)
    {
        if (!isset($this->modulesRegistry[$module_name])) {
            $this->modulesRegistry[$module_name] = [];
        }
        $this->modulesRegistry[$module_name][$module_id] = true;

    }


    public function isParsedModule($module_name, $module_id)
    {
        if (isset($this->modulesRegistry[$module_name]) and isset($this->modulesRegistry[$module_name][$module_id])) {
            return true;
        }
        return false;
    }



    public function registerParsedEditField($field, $rel, $rel_id = false)
    {
        if (!$rel_id) {
            $rel_id = 0;
        }
        if (is_numeric($rel_id)) {
            $rel_id = intval($rel_id);
        }

        if (!isset($this->editFieldRegistry[$field])) {
            $this->editFieldRegistry[$field] = [];
        }

        if (!isset($this->editFieldRegistry[$field][$rel])) {
            $this->editFieldRegistry[$field][$rel] = [];
        }
        $this->editFieldRegistry[$field][$rel][$rel_id] = true;

    }

    public function isParsedEditField($field, $rel, $rel_id = false)
    {

        if (!$rel_id) {
            $rel_id = 0;
        }
        if (is_numeric($rel_id)) {
            $rel_id = intval($rel_id);
        }

        if (isset($this->editFieldRegistry[$field]) and isset($this->editFieldRegistry[$field][$rel]) and isset($this->editFieldRegistry[$field][$rel][$rel_id])) {
          return true;
        }

        return false;
    }


}