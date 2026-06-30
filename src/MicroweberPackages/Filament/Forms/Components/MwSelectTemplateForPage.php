<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Closure;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View as SchemaView;
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

        // task-2026-06-06-pglayoutmodal — when false, the live template
        // PREVIEW iframe block is omitted and the reactive selects stop
        // dispatching `dynamicPreviewLayoutChange`. The preview machinery
        // (mw.templatePreview.rend() → /api/module/layout-preview) is the
        // engine of a client-side render cascade that tears the host modal
        // out of the DOM when this field lives inside a Filament mounted-
        // action modal (the live-edit "Create page" compact form). The
        // full template-customizer + admin create/edit pages keep the
        // preview (default true); only the compact live-edit modal opts out.
        bool $withPreview = true,

        // task-2026-06-06-pglayoutmodal — when false, the Template + Layout
        // selects are NOT `->reactive()`. Inside a Filament mounted-action
        // modal a reactive commit re-renders the `action-modals.0` partial;
        // morphing it re-initialises the modal's Alpine `x-data` so `isOpen`
        // resets to false and the modal vanishes (Filament opens modals via a
        // one-shot `open-modal` event that is never re-fired after the morph).
        // Non-reactive selects submit their value on Save with no round-trip,
        // so the modal can't be torn down. is_shop / subtype are derived from
        // the chosen layout at save time by the caller instead of via the
        // (now non-firing) afterStateUpdated hook. Full pages keep reactive.
        bool $reactive = true,

    ): Group
    {
        $activeSiteTemplateInputName = $activeSiteTemplateInputName ?? 'active_site_template';
        $layoutFileInputName = $layoutFileInputName ?? 'layout_file';


        $templates = site_templates();
        $active_site_template_default = template_name();
        // Ensure the default template matches an actual installed template dir_name.
        // template_name() can return "default" which is not a valid dir_name.
        if ($templates) {
            $templateDirNames = array_column($templates, 'dir_name');
            if (!in_array($active_site_template_default, $templateDirNames) && !empty($templateDirNames)) {
                $active_site_template_default = $templateDirNames[0];
            }
        }


        $selectTemplateInput = Forms\Components\Select::make($activeSiteTemplateInputName)
            ->label('Template')
            // task-2026-06-06-pglayoutmodal — reactive only when the host opts
            // in; the compact live-edit modal disables it to avoid the
            // partial-morph that closes the modal (see make() docblock).
            ->live(condition: $reactive)
             ->afterStateHydrated(
                function (Get $get, Set $set) use ($activeSiteTemplateInputName, $layoutFileInputName, $active_site_template_default) {
                    $activeSiteTemplate = $get($activeSiteTemplateInputName);

                    if (!$activeSiteTemplate || $activeSiteTemplate === 'default') {
                        $activeSiteTemplate = $active_site_template_default;
                        if ($activeSiteTemplate) {
                            $set($activeSiteTemplateInputName, $activeSiteTemplate);
                        }

                    }

                    $layoutFile = $get($layoutFileInputName);
                    if (!$layoutFile) {
                        $layoutFile = 'clean.blade.php';
                        $set($layoutFileInputName, $layoutFile);
                    }

                }
            )
            ->default(function (Get $get) use ($activeSiteTemplateInputName, $active_site_template_default) {

                $activeSiteTemplate = $get($activeSiteTemplateInputName);


                if (!$activeSiteTemplate || $activeSiteTemplate === 'default') {
                    $activeSiteTemplate = $active_site_template_default;
                }


                if ($activeSiteTemplate) {
                    return $activeSiteTemplate;
                }

            })
            ->options(function (Get $get, Set $set) use ($templates) {
                return collect($templates)->mapWithKeys(function ($template) {
                    return [$template['dir_name'] => $template['name']];
                });
            })
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName, $withPreview) {

                // Only trigger preview update when template changes
                $activeSiteTemplate = $state;
                $layoutFile = $get($layoutFileInputName);

                if ($withPreview && $activeSiteTemplate && $layoutFile) {
                    $layoutOptions = array();
                    $layoutOptions['layout_file'] = $layoutFile;
                    $layoutOptions['no_cache'] = true;
                    $layoutOptions['no_folder_sort'] = true;
                    $layoutOptions['active_site_template'] = $activeSiteTemplate;

                    $layout = app()->layouts_manager->get_layout_details($layoutOptions);
                    $url = '';

                    if (isset($layout['layout_file_preview_url'])) {
                        $url = $layout['layout_file_preview_url'];
                    }

                    $livewire->dispatch('dynamicPreviewLayoutChange', data: $livewire->data ?? [], iframePreviewUrl: $url);
                }
            })
            ->columnSpanFull();


        $selectLayoutInputInput = Forms\Components\Select::make($layoutFileInputName)
            ->label('Layout')
            ->default(function (Get $get) use ($activeSiteTemplateInputName, $active_site_template_default) {

                $activeSiteTemplate = $get($activeSiteTemplateInputName);

                if (!$activeSiteTemplate || $activeSiteTemplate === 'default') {
                    $activeSiteTemplate = $active_site_template_default;
                }

                if (!$activeSiteTemplate) {
                    return [];
                }

                $layoutOptions = [];
                $layoutOptions['site_template'] = $activeSiteTemplate;
                $layoutOptions['no_cache'] = true;
                $layoutOptions['no_folder_sort'] = true;

                $layouts = app()->layouts_manager->get_all($layoutOptions);
                if (isset($layouts[0])) {
                    return $layouts[0]['layout_file'];
                }

            })
            // task-2026-06-06-pglayoutmodal — live only when the host opts in.
            ->live(condition: $reactive)
             ->options(function (Get $get, Set $set) use ($layoutFileInputName, $activeSiteTemplateInputName, $active_site_template_default) {
                $activeSiteTemplate = $get($activeSiteTemplateInputName);

                if (!$activeSiteTemplate || $activeSiteTemplate === 'default') {
                    $activeSiteTemplate = $active_site_template_default;
                }

                if (!$activeSiteTemplate) {
                    return [];
                }

                $layoutOptions = [];
                $layoutOptions['site_template'] = $activeSiteTemplate;
                $layoutOptions['no_cache'] = true;
                $layoutOptions['no_folder_sort'] = true;

                $layouts = app()->layouts_manager->get_all($layoutOptions);


                return collect($layouts)->mapWithKeys(function ($layout) use ($layoutFileInputName) {
                    return [$layout[$layoutFileInputName] => $layout['name']];
                });

            })
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state, Component $livewire) use ($layoutFileInputName, $activeSiteTemplateInputName, $active_site_template_default, $withPreview) {

                $data = $livewire->data ?? [];


                $activeSiteTemplate = $get($activeSiteTemplateInputName);
                if (!$activeSiteTemplate || $activeSiteTemplate === 'default') {
                    $activeSiteTemplate = isset($data['active_site_template']) && $data['active_site_template'] !== 'default'
                        ? $data['active_site_template']
                        : $active_site_template_default;
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

                $layout = app()->layouts_manager->get_layout_details($layoutOptions);
                $url = '';

                if (isset($layout['layout_file_preview_url'])) {
                    $url = $layout['layout_file_preview_url'];
                }

                if (isset($layout['content_type']) and $layout['content_type']) {
                    if (array_key_exists('subtype', $data)) {
                        $set('subtype', $layout['content_type']);
                    }
                }
                if (isset($layout['is_shop']) and ($layout['is_shop'] == 1 or $layout['is_shop'] == 'y')) {
                    if (array_key_exists('is_shop', $data)) {
                        $set('is_shop', 1);
                    }
                } else if (array_key_exists('is_shop', $data)) {
                    $set('is_shop', 0);
                }

                // task-2026-06-06-pglayoutmodal — skip the preview broadcast
                // when the host has opted out of the preview iframe. The
                // dispatch drives mw.templatePreview.rend() which, inside a
                // Filament mounted-action modal, kicks off a render cascade
                // that closes the modal. Selecting a layout still updates
                // subtype/is_shop above; only the visual preview is dropped.
                if ($withPreview) {
                    $livewire->dispatch('dynamicPreviewLayoutChange', data: $data, iframePreviewUrl: $url);
                }

            })
            ->key('dynamicSelectLayout')
            ->columnSpanFull();


        $templatePreviewBlock = SchemaView::make('mw-filament::components.mw-render-template-preview-iframe')
            ->viewData([
                'url' => '',
                'layoutFileInputName' => $layoutFileInputName,
                'activeSiteTemplateInputName' => $activeSiteTemplateInputName
            ])


            ->key('dynamicPreviewLayout')
            ->columnSpanFull();


        // task-2026-06-06-pglayoutmodal — the preview iframe is opt-out so
        // the compact live-edit "Create page" modal can drop it (its render
        // cascade closes the modal). Template + Layout selects stay intact.
        $schema = [$selectTemplateInput, $selectLayoutInputInput];
        if ($withPreview) {
            $schema[] = $templatePreviewBlock;
        }

        return Group::make()->schema($schema);

    }


}
