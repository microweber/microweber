<?php

namespace Modules\Newsletter\EmailTemplateRendering\Elements;


abstract class DefaultElement {

    public $structure = [];
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    public function renderChildren($childrenId)
    {

        if (isset($this->structure[$childrenId])) {

            $childrenElement = $this->structure[$childrenId];

            try {
                $elementType = app()->make('Modules\\Newsletter\\EmailTemplateRendering\Elements\\' . $childrenElement['type']);
                $elementType->setStructure($this->structure);
                return $elementType->render($childrenElement);

            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage();
            }

            //    dd($childrenElement);
        }

    }

}
