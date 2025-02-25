<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\LiveEdit\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditSidebarElementStyleEditorPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditSidebarTemplateSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\AddContentModalPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\CodeEditorModuleSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\FontsManagerModuleSettingsPage;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools\ResetContentModuleSettingsPage;
use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorEditItemComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorListComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\LiveEditSidebarAdmin\LiveEditSidebarAdminComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\LiveEditSidebarAdmin\LiveEditSidebarAdminModulesListComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleTemplateSelectComponent;
use MicroweberPackages\LiveEdit\Http\Livewire\Presets\ModulePresetsManager;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingLiveEdit;
use MicroweberPackages\LiveEdit\Http\Middleware\DispatchServingModuleSettings;
use MicroweberPackages\LiveEdit\Services\LiveEditManagerService;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LiveEditServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-live-edit');
        $package->hasViews('microweber-live-edit');


    }

    public function register()
    {
        parent::register();
        View::addNamespace('microweber-live-edit', __DIR__ . '/resources/views');

        app()->singleton('live_edit_manager', function () {
            return new LiveEditManagerService();
        });

        Livewire::component('microweber-live-edit::module-select-template', ModuleTemplateSelectComponent::class);
        Livewire::component('microweber-live-edit::module-items-editor', ModuleSettingsItemsEditorComponent::class);
        Livewire::component('microweber-live-edit::module-items-editor-list', ModuleSettingsItemsEditorListComponent::class);
        Livewire::component('microweber-live-edit::module-items-editor-edit-item', ModuleSettingsItemsEditorEditItemComponent::class);
        Livewire::component('microweber-live-edit::sidebar-admin', LiveEditSidebarAdminComponent::class);
        Livewire::component('microweber-live-edit::sidebar-admin-modules-list', LiveEditSidebarAdminModulesListComponent::class);
        Livewire::component('microweber-live-edit::module-presets-manager', ModulePresetsManager::class);

      //  Event::listen(ServingLiveEdit::class, [$this, 'registerMenu']);





        // Event::listen(ServingFilament::class, function () {
        FilamentRegistry::registerPage(AdminLiveEditPage::class);
        FilamentRegistry::registerPage(AdminLiveEditSidebarTemplateSettingsPage::class);
        FilamentRegistry::registerPage(AdminLiveEditSidebarElementStyleEditorPage::class);


        //editor tools
        FilamentRegistry::registerPage(ResetContentModuleSettingsPage::class);
        FilamentRegistry::registerPage(CodeEditorModuleSettingsPage::class);
        FilamentRegistry::registerPage(FontsManagerModuleSettingsPage::class);
        FilamentRegistry::registerPage(AddContentModalPage::class);


        //  });


        //


    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $router = $this->app['router'];
//
        $router->middlewareGroup('live_edit', [
            DispatchServingLiveEdit::class,
        ]);

        $router->middlewareGroup('module_settings', [
            DispatchServingModuleSettings::class,
        ]);

        $this->registerMenu();


        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('editor/reset_content', ResetContentModuleSettingsPage::getUrl());
                ModuleAdmin::registerLiveEditSettingsUrl('editor/code_editor', CodeEditorModuleSettingsPage::getUrl());
                ModuleAdmin::registerLiveEditSettingsUrl('editor/fonts/font-manager-modal', FontsManagerModuleSettingsPage::getUrl());

                ModuleAdmin::registerLiveEditSettingsUrl('editor/sidebar_template_settings', AdminLiveEditSidebarTemplateSettingsPage::getUrl());
                ModuleAdmin::registerLiveEditSettingsUrl('microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue', AdminLiveEditSidebarElementStyleEditorPage::getUrl());
                ModuleAdmin::registerLiveEditSettingsUrl('editor/add_content_modal', AddContentModalPage::getUrl());

            }
        });


    }

    public function registerMenu()
    {
        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('Back to Admin', [
                // 'uri' => admin_url(),

                'attributes' => [
                    'id' => 'js-live-edit-back-to-admin-link',
                    'href' => admin_url(),
                    'icon' => '<svg viewBox="0 0 40 40">
                                                        <path
                                                            d="M20 27.3l2.1-2.1-3.7-3.7h9.1v-3h-9.1l3.7-3.7-2.1-2.1-7.3 7.3 7.3 7.3zM20 40c-2.73 0-5.32-.52-7.75-1.58-2.43-1.05-4.56-2.48-6.38-4.3s-3.25-3.94-4.3-6.38S0 22.73 0 20c0-2.77.53-5.37 1.57-7.8s2.48-4.55 4.3-6.35 3.94-3.22 6.38-4.28S17.27 0 20 0c2.77 0 5.37.53 7.8 1.57s4.55 2.48 6.35 4.28c1.8 1.8 3.23 3.92 4.28 6.35C39.48 14.63 40 17.23 40 20c0 2.73-.52 5.32-1.58 7.75-1.05 2.43-2.48 4.56-4.28 6.38-1.8 1.82-3.92 3.25-6.35 4.3C25.37 39.48 22.77 40 20 40zm0-3c4.73 0 8.75-1.66 12.05-4.97C35.35 28.71 37 24.7 37 20c0-4.73-1.65-8.75-4.95-12.05C28.75 4.65 24.73 3 20 3c-4.7 0-8.71 1.65-12.02 4.95S3 15.27 3 20c0 4.7 1.66 8.71 4.98 12.03C11.29 35.34 15.3 37 20 37z"/>
                                                    </svg>'
                ]
            ]);


        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('Back to Admin')
            ->setExtra('orderNumber', 10);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('Template Settings', [
                // 'uri' => admin_url(),
                'attributes' => [
                    'id' => 'js-live-edit-open-template-settings-link',
                    'ref' => 'openTemplateSettingsRef',

                    'onclick' => 'mw.top().app.dispatch(\'mw.open-template-settings\')',

                    'icon' => '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22"><path
                d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/></svg>'
                ]
            ]);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('Back to Admin')
            ->setExtra('orderNumber', 11);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('Users', [
                'attributes' => [
                    'id' => 'js-live-edit-admin-users-link',
                    'url' => admin_url('users'),
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 40 40" xml:space="preserve"
                                                         enable-background="new 0 0 40 40"><path
                                                        d="M14.7 23c-2 0-3.6-.7-5-2-1.3-1.4-2-3-2-4.9 0-1.9.7-3.5 2-4.9 1.4-1.3 3-2 5-2 1.8 0 3.5.7 4.8 2 1.4 1.4 2 3 2 4.9 0 1.9-.6 3.5-2 4.9-1.3 1.3-3 2-4.8 2zm0-3a3.8 3.8 0 0 0 3.9-3.9c0-1.1-.4-2-1.2-2.8a3.8 3.8 0 0 0-2.7-1c-1.1 0-2 .3-2.8 1-.8.8-1.1 1.7-1.1 2.8 0 1 .3 2 1.1 2.8.8.7 1.7 1.1 2.8 1.1zm15 5.3c-1.5 0-2.7-.5-3.8-1.6-1-1-1.5-2.2-1.5-3.7s.5-2.7 1.6-3.8 2.2-1.5 3.7-1.5 2.7.5 3.8 1.6S35 18.4 35 20s-.5 2.7-1.6 3.8-2.2 1.5-3.7 1.5zM17.1 36.8c1.6-3 3.6-5 6.1-6S28 29 29.7 29a12.6 12.6 0 0 1 4.2.6A18.3 18.3 0 0 0 37 20c0-4.7-1.6-8.8-5-12-3.3-3.3-7.3-5-12-5S11.2 4.7 8 8a16.8 16.8 0 0 0-2.2 21.2 19.2 19.2 0 0 1 13.8-1.4 13.6 13.6 0 0 0-3.2 2.2H14.8a16.2 16.2 0 0 0-7.1 1.6c1.2 1.4 2.7 2.5 4.3 3.4s3.4 1.5 5.2 1.8zM20 40A20.3 20.3 0 0 1 1.6 27.7 19.4 19.4 0 0 1 5.9 5.8a20.2 20.2 0 0 1 21.9-4.2A20.3 20.3 0 0 1 40 20a20.3 20.3 0 0 1-12.2 18.4c-2.4 1-5 1.6-7.8 1.6z"/>
                                                        </svg>'
                ]
            ]);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('Users')
            ->setExtra('orderNumber', 12);

        /*        \MicroweberPackages\LiveEdit\Facades\LiveEditManagerService::getMenuInstance('top_right_menu')
                    ->addChild('Plans and Payments', [
                        'attributes' => [
                            'route' => 'admin.settings.index',
                            'icon' => '<svg viewBox="0 0 40 32.29">
                                                                <path
                                                                    d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/>
                                                            </svg>'
                        ]
                    ]);*/

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('Website Settings', [
                'attributes' => [
                    'id' => 'js-live-edit-admin-settings-link',
                    'route' => 'admin.settings.index',
                    'target' => '_blank',
                    'icon' => '<svg viewBox="0 0 40 40">
                                                        <path
                                                            d="M15.4 40l-1-6.3c-.63-.23-1.3-.55-2-.95-.7-.4-1.32-.82-1.85-1.25l-5.9 2.7L0 26l5.4-3.95a5.1 5.1 0 01-.12-1.02c-.02-.39-.03-.73-.03-1.03s.01-.64.02-1.02c.02-.38.06-.73.12-1.02L0 14l4.65-8.2 5.9 2.7c.53-.43 1.15-.85 1.85-1.25.7-.4 1.37-.7 2-.9l1-6.35h9.2l1 6.3c.63.23 1.31.54 2.02.93.72.38 1.33.81 1.83 1.27l5.9-2.7L40 14l-5.4 3.85c.07.33.11.69.12 1.08a19.5 19.5 0 010 2.13c-.02.37-.06.72-.12 1.05L40 26l-4.65 8.2-5.9-2.7c-.53.43-1.14.86-1.83 1.28-.68.42-1.36.72-2.02.92l-1 6.3h-9.2zM20 26.5c1.8 0 3.33-.63 4.6-1.9s1.9-2.8 1.9-4.6-.63-3.33-1.9-4.6-2.8-1.9-4.6-1.9-3.33.63-4.6 1.9-1.9 2.8-1.9 4.6.63 3.33 1.9 4.6 2.8 1.9 4.6 1.9zm0-3c-.97 0-1.79-.34-2.48-1.02-.68-.68-1.02-1.51-1.02-2.48s.34-1.79 1.02-2.48c.68-.68 1.51-1.02 2.48-1.02s1.79.34 2.48 1.02c.68.68 1.02 1.51 1.02 2.48s-.34 1.79-1.02 2.48c-.69.68-1.51 1.02-2.48 1.02zM17.8 37h4.4l.7-5.6c1.1-.27 2.14-.68 3.12-1.25s1.88-1.25 2.68-2.05l5.3 2.3 2-3.6-4.7-3.45c.13-.57.24-1.12.33-1.67s.12-1.11.12-1.67-.03-1.12-.1-1.67-.18-1.11-.35-1.67L36 13.2l-2-3.6-5.3 2.3c-.77-.87-1.63-1.59-2.6-2.17s-2.03-.96-3.2-1.12L22.2 3h-4.4l-.7 5.6c-1.13.23-2.19.63-3.17 1.2s-1.86 1.27-2.62 2.1L6 9.6l-2 3.6 4.7 3.45c-.13.57-.24 1.12-.32 1.67s-.13 1.11-.13 1.68.04 1.12.12 1.67c.08.55.19 1.11.32 1.67L4 26.8l2 3.6 5.3-2.3c.8.8 1.69 1.48 2.68 2.05s2.02.98 3.12 1.25l.7 5.6z"/>
                                                    </svg>'
                ]
            ]);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('Website Settings')
            ->setExtra('orderNumber', 13);


        if (mw()->ui->enable_service_links()) {
            \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
                ->addChild('Report issue', [
                    'attributes' => [
                        'id' => 'js-live-report-issue-link',

                        'onclick' => 'mw.top().app.dispatch(\'mw.open-report-issue-modal\')',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>'
                    ]
                ]);


            \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
                ->menuItems
                ->getChild('Report issue')
                ->setExtra('orderNumber', 13);

        }


        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('See website', [
                'attributes' => [
                    'id' => 'js-live-edit-website-preview-link',
                    'href' => site_url() . '?editmode=n',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm-40-82v-78q-33 0-56.5-23.5T360-320v-40L168-552q-3 18-5.5 36t-2.5 36q0 121 79.5 212T440-162Zm276-102q20-22 36-47.5t26.5-53q10.5-27.5 16-56.5t5.5-59q0-98-54.5-179T600-776v16q0 33-23.5 56.5T520-680h-80v80q0 17-11.5 28.5T400-560h-80v80h240q17 0 28.5 11.5T600-440v120h40q26 0 47 15.5t29 40.5Z"/></svg>'
                ]
            ]);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('See website')
            ->setExtra('orderNumber', 14);


        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->addChild('Log out', [
                'attributes' => [
                    'id' => 'js-live-edit-website-log-out-link',
                    'href' => logout_url(),
                    'icon' => '<svg viewBox="0 0 36 36.1">
                                                        <path
                                                            d="M3 36.1c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V22.6h3v10.5h30V3H3v10.6H0V3C0 2.2.3 1.5.9.9S2.2 0 3 0h30c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1v30.1c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3zm11.65-8.35L12.4 25.5l5.9-5.9H0v-3h18.3l-5.9-5.9 2.25-2.25 9.65 9.65-9.65 9.65z"/>
                                                    </svg>'
                ]
            ]);

        \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
            ->menuItems
            ->getChild('Log out')
            ->setExtra('orderNumber', 15);

    }

}
