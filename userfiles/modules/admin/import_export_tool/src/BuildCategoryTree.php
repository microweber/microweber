<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

class BuildCategoryTree {

    public $txt = '';
    public $ids = [];
    public $category = [];

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function build($category)
    {
        if (isset($category['childs'])) {
            foreach ($category['childs'] as $child) {
                $this->txt .= ' > ' . $child['title'];
                $this->ids[] = $child['id'];
                if (isset($child['childs']) && !empty($child['childs'])) {
                    $this->build($child);
                }
            }
        }
    }

    public function get()
    {
        $this->txt .= $this->category['title'];
        $this->ids[] = $this->category['id'];

        $this->build($this->category);

        return ['plain'=>$this->txt,'ids'=>$this->ids];
    }
}
