<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Fields\MwSlugInput;
use Filament\Forms;

use Filament\Forms\Form;


class MwSelectTemplateForPage
{
    public static function make(

        // Models fields
        string $activeSiteTemplateInputName = null,
        string $layoutFileInputName = null,

    ): Group
    {
        $activeSiteTemplateInputName = $activeSiteTemplateInputName ?? 'active_site_template';
        $layoutFileInputName = $layoutFileInputName ?? 'layout_file';


        $templates = site_templates();
        $active_site_template_default = template_name();


        $selectTemplateInput = Forms\Components\Select::make($activeSiteTemplateInputName)
            ->label('Template')
            ->default($active_site_template_default)
            ->live()
            ->reactive()
            ->options(function (Forms\Get $get, Forms\Set $set) use ($templates) {
                return collect($templates)->mapWithKeys(function ($template) {
                    return [$template['dir_name'] => $template['name']];
                });
            })
//            ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
//                ->getContainer()
//                ->getComponent('dynamicSelectLayout')
//                ->getChildComponentContainer()
//                ->fill())
//            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state) use ($layoutFileInputName, $activeSiteTemplateInputName) {
//
//
//                $active_site_template = $get($activeSiteTemplateInputName);
//                if (!$active_site_template) {
//                    $active_site_template = template_name();
//                }
//
//                $layout_file = $get($layoutFileInputName);
//                if (!$layout_file) {
//                    $layout_file = 'clean.php';
//                }
//
//                $layout_options = array();
//                $layout_options['layout_file'] = $layout_file;
//                $layout_options['no_cache'] = true;
//                $layout_options['no_folder_sort'] = true;
//                $layout_options['active_site_template'] = $active_site_template;
//                $layout = mw()->layouts_manager->get_layout_details($layout_options);
//                $url = '';
//
//
//                if (isset($layout['layout_file_preview_url'])) {
//                    $url = $layout['layout_file_preview_url'];
//                }
//
//             //   $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);
//
//
//            })
            ->columnSpanFull();


        $selectLayoutInputInput = Forms\Components\Select::make($layoutFileInputName)
            ->label('Layout')
            ->live()
            ->default(function (Forms\Get $get) use($activeSiteTemplateInputName) {

                $activeSiteTemplate = $get($activeSiteTemplateInputName);

                if (!$activeSiteTemplate) {
                    return [];
                }

                $layoutOptions = [];
                $layoutOptions['site_template'] = $activeSiteTemplate;
                $layoutOptions['no_cache'] = true;
                $layoutOptions['no_folder_sort'] = true;

                $layouts = mw()->layouts_manager->get_all($layoutOptions);
                if (isset($layouts[0])) {
                    return $layouts[0]['layout_file'];
                }

            })
            ->reactive()
            ->options(function (Forms\Get $get, Forms\Set $set) use ($layoutFileInputName, $activeSiteTemplateInputName) {
                $activeSiteTemplate = $get($activeSiteTemplateInputName);

                if (!$activeSiteTemplate) {
                    return [];
                }

                $layoutOptions = [];
                $layoutOptions['site_template'] = $activeSiteTemplate;
                $layoutOptions['no_cache'] = true;
                $layoutOptions['no_folder_sort'] = true;

                $layouts = mw()->layouts_manager->get_all($layoutOptions);


                return collect($layouts)->mapWithKeys(function ($layout) use ($layoutFileInputName) {
                    return [$layout[$layoutFileInputName] => $layout['name']];
                });

            })
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName) {

                $data = $livewire->data ?? [];


                $activeSiteTemplate = $get($activeSiteTemplateInputName);
                if (!$activeSiteTemplate) {
                    $activeSiteTemplate = isset($data['active_site_template']) ? $data['active_site_template'] : template_name();
                }


                // $layout_options['active_site_template'] = $active_site_template;

                //$layout_file_from_data = isset($data['layout_file']) ? $data['layout_file'] : 'clean.php';
                // $layout_file = isset($state) ? $state : $layout_file_from_data;
                $layoutFile = $get($layoutFileInputName);

                $layoutOptions = array();
                $layoutOptions['layout_file'] = $layoutFile;
                $layoutOptions['no_cache'] = true;
                $layoutOptions['no_folder_sort'] = true;
                $layoutOptions['active_site_template'] = $activeSiteTemplate;

                $layout = mw()->layouts_manager->get_layout_details($layoutOptions);
                $url = '';

                if (isset($layout['layout_file_preview_url'])) {
                    $url = $layout['layout_file_preview_url'];
                }

                if (isset($layout['content_type']) and $layout['content_type']) {
                    if (array_key_exists('subtype', $data)) {
                        $set('subtype', $layout['content_type']);
                    }
                }
                if (isset($layout['is_shop']) and ($layout['is_shop'] == 1 or $layout['is_shop'] == 'y') ) {
                    if (array_key_exists('is_shop', $data)) {
                        $set('is_shop', 1);
                    }
                } else  if (array_key_exists('is_shop', $data)) {
                    $set('is_shop', 0);
                }

                $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);

            })
            ->key('dynamicSelectLayout')
            ->columnSpanFull();


        $templatePreviewBlock = Forms\Components\View::make('filament-forms::components.mw-render-template-preview-iframe')
            ->viewData([
                'url' => '',
                'layoutFileInputName' => $layoutFileInputName,
                'activeSiteTemplateInputName' => $activeSiteTemplateInputName
            ])
            ->live()
            ->key('dynamicPreviewLayout')
            ->columnSpanFull();


        return Group::make()
            ->schema([
                $selectTemplateInput,
                $selectLayoutInputInput,
                $templatePreviewBlock
            ]);

    }


}
