<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Blog\FrontendFilter\FilterHelper;
use MicroweberPackages\Blog\Http\Controllers\Traits\CustomFieldsRenderTrait;
use MicroweberPackages\Content\Models\Content;

class LiveEditAdminController
{
    use CustomFieldsRenderTrait;

    public function index(Request $request)
    {
        $pages = \MicroweberPackages\Content\Content::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 0)
            ->get();

        $getCustomFieldsTableRoute = route('admin.blog.filter.get-custom-fields-table');

        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'pages'=>$pages,
            'getCustomFieldsTableRoute'=>$getCustomFieldsTableRoute,
        ]);
    }

    public function getCustomFieldsTableFromPage(Request $request)
    {
        $contentFromId = $request->get('contentFromId', false);
        $moduleId = $request->get('moduleId');

        $customFieldNames = $this->getCustomFieldByParentIdAndModuleId($contentFromId, $moduleId);

        return view('blog::admin.ajax_custom_fields_table', [
            'moduleId'=>$moduleId,
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
