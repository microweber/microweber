<?php

namespace Modules\Pictures\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Modules\Media\Models\Media;

class PicturesModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'pictures';
    public array $mediaIds = [];

    public function form(Form $form): Form
    {
        $relType = 'module';
        $relId = $this->params['id'];

        $picturesFromContent = $this->getOption('data-use-from-post');

        $openedFromContentPageId= 0;
        if(isset($this->liveEditIframeData['content']) and $this->liveEditIframeData['content']['id']){
            $openedFromContentPageId =  $this->liveEditIframeData['content']['id'];
        }

        if($picturesFromContent == 'y' and $openedFromContentPageId) {
            $relType =morph_name(\Modules\Content\Models\Content::class);
            $relId = $openedFromContentPageId;
        }


         return $form
            ->schema([
                Tabs::make('Pictures')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([

                                ToggleButtons::make('options.data-use-from-post')
                                    ->label('Use images from post')
                                    ->helperText('Use images from the post')
                                    ->live()
                                    ->inline()
                                    ->options([
                                        'n' => 'No',
                                        'y' => 'Yes',
                                    ])
                                    ->default('n'),


                                MwMediaBrowser::make('mediaIds')
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
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
