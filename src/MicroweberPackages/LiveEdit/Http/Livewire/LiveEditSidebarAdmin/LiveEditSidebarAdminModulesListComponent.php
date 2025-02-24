<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\LiveEditSidebarAdmin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

/**
 * @deprecated
 */
class LiveEditSidebarAdminModulesListComponent extends AdminComponent
{
    public string $view = 'microweber-live-edit::sidebar-admin.sidebar-admin-modules-list';


    public $modulesData = [];
    public $modulesMenuRender = '';
    public $modulesMenuItemsInstance = false;

    public function buildModulesTree()
    {
        $this->modulesMenuRender = '';
        $modulesData = $this->modulesData;
        $modules = [];
        if ($modulesData && isset($modulesData['modules'])) {
            $modules = $modulesData['modules'];
        }

        if (!empty($modules)) {

            $recursiveArray = [];
            foreach ($modules as $module) {
                if (isset($module['data-type']) && $module['data-type'] == 'layouts') {
                    $module['title'] = _e('Layouts', true);
                    $recursiveArray[] = $module;
                }
            }

            if (!empty($recursiveArray)) {
                $this->buildRecursiveTree($recursiveArray);
            }


        }
    }

    private function buildRecursiveTree($recursiveArray)
    {


        $options =
            [
                'allow_safe_labels' => true,
            ];

        $mwMenu = new \MicroweberPackages\LiveEdit\MenuBuilder\LiveEditMenu('SIDEBAR_ADMIN_MODULES_TREE', $options);
        $mwMenu->setRendererOptions($options);

        // Recursively add child items to the menu
        $this->addChildItems($mwMenu, $recursiveArray);
        $this->modulesMenuRender = $mwMenu->render();
    }

    private function addChildItems($parentMenu, $items)
    {
        foreach ($items as $item) {
            $attributes = isset($item['attributes']) ? $item['attributes'] : [];
            $uri = isset($item['uri']) ? $item['uri'] : '';
            $title = isset($item['title']) ? $item['title'] : '';
            if (!$title) {
                $title = isset($item['id']) ? $item['id'] : '';
            }

            $uri = 'javascript:window.selectModule("' . $item['id'] . '")';
            $content = view('microweber-live-edit::sidebar-admin.partials.modules-tree-link-item', ['item' => $item])->render();
            $itemOptions = [
                //  'uri' => $uri,
                'label' => $content,
                'attributes' => $attributes,
                'extras' => [
                    'safe_label' => true, // This prevents the HTML from being escaped
                ],
            ];
            if (empty($item['childModules'])) {
                $itemOptions['attributes']['class'] = 'list-group-item';
            }

            // Add the current item as a child to the parent menu
            $parentMenu->addChild($item['id'], $itemOptions);

            // If the current item has childModules, call the addChildItems function recursively
            if (!empty($item['childModules'])) {
                $childMenu = $parentMenu->getChild($item['id']);
                $childs = [];
                foreach ($item['childModules'] as $childModule) {
                    $childModule['attributes']['class'] = 'list-group-item';
                    $childModule['extras'] = [
                        'safe_label' => true, // This prevents the HTML from being escaped

                    ];
                    $childs[] = $childModule;
                }

                $this->addChildItems($childMenu, $childs);
            }
        }
    }

    function render()
    {
        $this->buildModulesTree();

        return view($this->view);
    }
}
