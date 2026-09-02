<?php

namespace Modules\Pictures\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Media\Models\Media;

class PicturesModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'pictures';
    public array $mediaIds = [];
    protected static bool $useMwDialog = true;

    public function form(Schema $schema): Schema
    {
        $relType = 'module';
        $relId = $this->params['id'] ?? null;

        $picturesFromContent = $this->getOption('data-use-from-post');

        $openedFromContentPageId= 0;
        if(isset($this->liveEditIframeData['content']) and $this->liveEditIframeData['content']['id']){
            $openedFromContentPageId =  $this->liveEditIframeData['content']['id'];
        }

        if($picturesFromContent == 1 and $openedFromContentPageId) {
            $relType =morph_name(\Modules\Content\Models\Content::class);
            $relId = $openedFromContentPageId;
        }



         return $schema
            ->schema([
                Tabs::make('Pictures')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([




                                MwMediaBrowser::make('mediaIds')
                                    ->label('Media')
                                    ->setRelType($relType)
                                    ->setRelId($relId)
                                    ->default(function () use ($relType, $relId) {
                                        $mediaIds = Media::query()
                                            ->where('rel_type', $relType)
                                            ->where('rel_id', $relId)
                                            ->orderBy('position', 'asc')
                                            ->pluck('id')->toArray();

                                        return $mediaIds;
                                    }),

                            ]),
                        // task-2026-06-13-letele2 — Columns / Aspect Ratio / Lightbox
                        // are NOT global Pictures settings: they only apply to skins
                        // that lay images out in a grid (currently the `default` skin).
                        // They were a global "Display" tab; moved into the per-skin
                        // Design tab via the JSON-schema mechanism (templates/default.json
                        // -> getTemplatesFormSchema), so a skin only shows the options it
                        // actually supports. See global_skin_settings.json for the pattern.
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                        Tabs\Tab::make('Advanced')
                            ->schema([ToggleButtons::make('options.data-use-from-post')
                                ->visible($relType == 'module' and $relId > 0)
                                ->label('Use images from post')
                                ->helperText('Use images from the post')
                                ->live()
                                ->inline()
                                ->options([
                                    '0' => 'No',
                                    '1' => 'Yes',
                                ])
                                ->default(fn () => $this->getOption('data-use-from-post', '0'))
                                ])
                    ]),
            ]);
    }
}
