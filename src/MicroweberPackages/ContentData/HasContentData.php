<?php

namespace MicroweberPackages\ContentData;


trait HasContentData
{
    public $contentData = [];

    public function setContentData($values)
    {
        foreach ($values as $key => $val) {
            $this->contentData[$key] = $val;
        }
    }

    public function getContentData($values)
    {
        $res = [];
        $arrData = !empty($this->data) ? $this->data->toArray() : [];

        foreach ($values as $value) {
            if (array_key_exists($value, $this->contentData)) {
                $res[$value] = $this->contentData[$value];
            } else {
                foreach ($arrData as $key => $val) {
                    if ($val['field_name'] == $value) {
                        $res[$value] = $val['field_value'];
                    }
                }
            }
        }

        return $res;
    }

    public function deleteContentData(array $values)
    {

        foreach ($this->data as $key => $contentDataInstance) {
            if (in_array($contentDataInstance->field_name, $values)) {
                $contentDataInstance->delete();
                $this->refresh();
            }
        }
        foreach ($this->contentData as $key => $value) {
            foreach ($values as $del_key => $del_value) {
                if (isset($this->contentData[$del_key])) {
                    unset($this->contentData[$del_key]);
                }
            }
        }

    }

}