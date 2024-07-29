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

        // Model fields
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
            //->default($active_site_template)
            ->live()
            ->reactive()
            ->options(function (Forms\Get $get, Forms\Set $set) use ($templates) {
                return collect($templates)->mapWithKeys(function ($template) {
                    return [$template['dir_name'] => $template['name']];
                });
            })
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName) {


            })
            ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                ->getContainer()
                ->getComponent('dynamicSelectLayout')
                ->getChildComponentContainer()
                ->fill())
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName) {

                $data = $livewire->data;

                $layout_options = array();
                $active_site_template = $get($activeSiteTemplateInputName);
                if (!$active_site_template) {
                    $active_site_template = isset($data[$activeSiteTemplateInputName]) ? $data[$activeSiteTemplateInputName] : template_name();
                }
                //
                $layout_file = $get($layoutFileInputName);
                if (!$layout_file) {
                    $layout_file = isset($data[$layoutFileInputName]) ? $data[$layoutFileInputName] : 'clean.php';
                }
                $layout_options = array();
                $layout_options['layout_file'] = $layout_file;
                $layout_options['no_cache'] = true;
                $layout_options['no_folder_sort'] = true;
                $layout_options['active_site_template'] = $active_site_template;
                $layout = mw()->layouts_manager->get_layout_details($layout_options);
                $url = '';


                if (isset($layout['layout_file_preview_url'])) {
                    $url = $layout['layout_file_preview_url'];
                }

                $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);


            })
            ->columnSpanFull();


        $selectLayoutInputInput = Forms\Components\Select::make($layoutFileInputName)
            ->label('Layout')
            ->live()
            ->reactive()
            ->options(function (Forms\Get $get, Forms\Set $set) use ($layoutFileInputName, $activeSiteTemplateInputName) {
                $active_site_template = $get($activeSiteTemplateInputName);

                $layout_options = [];
                $layout_options['site_template'] = $active_site_template;
                $layout_options['no_cache'] = true;
                $layout_options['no_folder_sort'] = true;

                $layouts = mw()->layouts_manager->get_all($layout_options);


                return collect($layouts)->mapWithKeys(function ($layout) use ($layoutFileInputName) {
                    return [$layout[$layoutFileInputName] => $layout['name']];
                });

            })
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName) {

                $data = $livewire->data;

                $layout_options = array();
                $active_site_template = $get($activeSiteTemplateInputName);
                if (!$active_site_template) {
                    $active_site_template = isset($data['active_site_template']) ? $data['active_site_template'] : template_name();
                }


                // $layout_options['active_site_template'] = $active_site_template;

                //$layout_file_from_data = isset($data['layout_file']) ? $data['layout_file'] : 'clean.php';
                // $layout_file = isset($state) ? $state : $layout_file_from_data;
                $layout_file = $get($layoutFileInputName);


                $layout_options['layout_file'] = $layout_file;
                $layout_options['no_cache'] = true;
                $layout_options['no_folder_sort'] = true;

                $layout_options['active_site_template'] = $active_site_template;


                $layout = mw()->layouts_manager->get_layout_details($layout_options);
                $url = '';

                if (isset($layout['layout_file_preview_url'])) {
                    $url = $layout['layout_file_preview_url'];
                }


                $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);


            })
            ->key('dynamicSelectLayout')
            ->columnSpanFull();


        $templatePreviewBlock = Forms\Components\View::make('filament-forms::components.mw-render-template-preview-iframe')
            ->viewData(['url' => '', 'layoutFileInputName' => $layoutFileInputName, 'activeSiteTemplateInputName' => $activeSiteTemplateInputName])
//                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state) {
//                                            dd($get);
//                                        })
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
