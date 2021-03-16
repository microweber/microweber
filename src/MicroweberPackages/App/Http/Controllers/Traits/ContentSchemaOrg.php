<?php

namespace MicroweberPackages\App\Http\Controllers\Traits;

trait ContentSchemaOrg
{
    public function appendContentSchemaOrg()
    {
        $schema_org_item_type = false;
        $schema_org_item_type_tag = false;

        if (isset($this->moduleParams['content_type']) and $this->moduleParams['content_type'] == 'page') {
            $schema_org_item_type = 'WebPage';

        } else if (isset($this->moduleParams['content_type']) and $this->moduleParams['content_type'] == 'post') {
            if (isset($this->moduleParams['subtype']) and $this->moduleParams['subtype'] != $this->moduleParams['content_type']) {
                $schema_org_item_type = $this->moduleParams['subtype'];

            } else {
                $schema_org_item_type = 'Article';
            }
        } else if (isset($this->moduleParams['content_type']) and $this->moduleParams['content_type'] == 'product') {
            if (isset($this->moduleParams['subtype']) and $this->moduleParams['subtype'] != $this->moduleParams['content_type']) {
                $schema_org_item_type = $this->moduleParams['subtype'];

            } else {
                $schema_org_item_type = 'Product';
            }
        }


        if ($schema_org_item_type != false) {
            $schema_org_item_type = ucfirst($schema_org_item_type);
            //$schema_org_item_type_tag = ' itemtype="http://schema.org/' . $schema_org_item_type . '" ';
            $schema_org_item_type_tag = 'http://schema.org/' . $schema_org_item_type;
        }

        $this->viewData['schema_org_item_type_tag'] = $schema_org_item_type_tag;
    }
}
