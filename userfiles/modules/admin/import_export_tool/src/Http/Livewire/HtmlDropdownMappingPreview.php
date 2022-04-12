<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;

class HtmlDropdownMappingPreview extends Component
{
    public function render()
    {
        $request = request();
        $contentParentTags = $request->get('content_parent_tags','rss.channel.item');

        $contentXml = file_get_contents('https://templates.microweber.com/import_test/wp.xml');


        $newReader = new XmlToArray();
        $data = $newReader->readXml($contentXml);

        $dropdownMapping = new \MicroweberPackages\Import\ImportMapping\HtmlDropdownMappingPreview();
        $dropdownMapping->setContent($data);
        $dropdownMapping->setContentParentTags($contentParentTags);

        $data =  $dropdownMapping->render();

        return view('import_export_tool::admin.livewire-html-dropdown-mapping-preview',['data'=>$data]);
    }

}
