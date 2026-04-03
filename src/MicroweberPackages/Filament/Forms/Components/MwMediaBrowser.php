<?php

namespace MicroweberPackages\Filament\Forms\Components;


use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Modules\Media\Models\Media;

class MwMediaBrowser extends Field
{
    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $createdBy = '';
    public $mediaItems = [];
    public $mediaIds = [];

    protected string $view = 'mw-filament::components.mw-media-browser';

    protected function setUp(): void
    {
        parent::setUp();
        // Media browser methods are exposed to JS via #[ExposedLivewireMethod]
        // and called from JS using $wire.callSchemaComponentMethod().

        $this->registerActions([
            fn (MwMediaBrowser $component): Action => $component->editAction(),
            fn (MwMediaBrowser $component): Action => $component->deleteAction(),
        ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->mountUsing(function (Schema $schema, array $arguments) {
                $record = Media::find($arguments['id']);
                $schema->fill($record->toArray());
            })
            ->form([
                Hidden::make('id')
                    ->required(),
                TextInput::make('title')
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(2550),
            ])
            ->modalSubmitActionLabel('Save')
            ->action(function (array $data) {
                $record = Media::find($data['id']);
                $record->update($data);
            })->size('xs');
    }

    #[ExposedLivewireMethod]
    public function mediaItemsSort($itemsSortedIds)
    {
        if (!$itemsSortedIds) {
            return;
        }
        $itemsQuery = $this->getQueryBuilder();

        //sort by position

        $position = 0;
        foreach ($itemsSortedIds as $itemsSortedId) {
            $position++;
            Media::where('id', $itemsSortedId)->update(['position' => $position]);
        }

        $this->refreshMediaData();

    }
    #[ExposedLivewireMethod]
    public function deleteMediaItemById($id = false)
    {
        if (!$id) {
            return;
        }
        Media::where('id', $id)->delete();

        $this->refreshMediaData();
    }
    public function addMediaItemSingle($url) {
        $itemsQuery = $this->getQueryBuilder();
        $itemsQuery = $itemsQuery->where('filename', $url);
        $mediaItem = $itemsQuery->first();

        //check if exists

        if (!$mediaItem) {
            $mediaItem = new Media();
            if ($this->relType) {
                $mediaItem->rel_type = $this->relType;
            }
            if ($this->relId) {
                $mediaItem->rel_id = $this->relId;
            }
            $mediaItem->filename = $url;
            if ($this->createdBy) {
                $mediaItem->created_by = $this->createdBy;
            }
            if (!$this->relId) {
                if ($this->sessionId) {
                    $mediaItem->session_id = $this->sessionId;
                }

            }

            $mediaItem->save();
        }

    }
    #[ExposedLivewireMethod]
    public function addMediaItemMultiple($data = [])
    {
        $urls = $data['urls'] ?? $data;
        if (!is_array($urls) || empty($urls)) {
            return;
        }

        foreach ($urls as $url) {
            $this->addMediaItemSingle($url);
        }

        $this->refreshMediaData();

        $this->state($this->mediaIds);
    }
    #[ExposedLivewireMethod]
    public function addMediaItem($data = [])
    {
        $url = false;

        if(isset($data['url'])){
            $url = $data['url'];
        }



        if (!$url) {
            return;
        }

        if(is_array($url)) {

            foreach ($url as $u) {
                $this->addMediaItemSingle($u);
            }

            return;
        }

        $this->addMediaItemSingle($url);

        $this->refreshMediaData();

        $this->state($this->mediaIds);
    }

    #[ExposedLivewireMethod]
    public function updateImageFilename($data = [])
    {
        $mediaId = $data['id'] ?? false;
        if (!$mediaId) {
            return;
        }
        $media = Media::where('id', $mediaId)->first();
        if (!$media) {
            return;
        }

        $filename = $data['filename'] ?? ($data['src'] ?? null);
        if ($filename) {
            $media->filename = $filename;
            $media->save();
            $this->refreshMediaData();
        }
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                Media::where('id', $arguments['id'])->delete();
            });
    }

    #[ExposedLivewireMethod]
    public function deleteMediaItemsByIds($ids = false)
    {
        $mediaId = $ids;
        if (!$mediaId) {
            return;
        }
        Media::whereIn('id', $mediaId)->delete();

        $this->refreshMediaData();
    }


    public function getQueryBuilder()
    {

        $record = $this->getRecord();
        if ($record) {
            $this->relType = morph_name($record->getMorphClass());
            $this->relId = $record->id;
        } else {
            $this->createdBy = user_id();
        }

        $itemsQuery = Media::query();
        if (empty(trim($this->relId))) {
            if (!empty(trim($this->sessionId))) {
                $itemsQuery->where('session_id', $this->sessionId);
            } else if ($this->createdBy) {
                $itemsQuery->where('created_by', $this->createdBy);
            }
        }
        if (!empty(trim($this->relId))) {
            $itemsQuery->where('rel_id', $this->relId);
        } else {
            $itemsQuery->whereNull('rel_id');
        }

        if (!empty(trim($this->relType))) {
            $itemsQuery->where('rel_type', $this->relType);
        }

        $itemsQuery->orderBy('position', 'asc');

        return $itemsQuery;
    }

    public function refreshMediaData()
    {
        $itemsQuery = $this->getQueryBuilder();

        $this->mediaItems = $itemsQuery->get();
        if ($this->mediaItems) {
            $this->mediaIds = $this->mediaItems->pluck('id')->toArray();
        } else {
            $this->mediaIds = [];
        }

    }

    public function getMediaItemsArray()
    {
        $this->refreshMediaData();

        return $this->mediaItems;
    }


    public function setRelType($relType)
    {
        $this->relType = $relType;
        return $this;
    }
    public function setRelId($relId)
    {
        $this->relId = $relId;
        return $this;
    }

}
