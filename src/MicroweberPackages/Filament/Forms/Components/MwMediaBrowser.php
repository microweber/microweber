<?php

namespace MicroweberPackages\Filament\Forms\Components;


use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Livewire\Attributes\On;
use MicroweberPackages\Media\Models\Media;

class MwMediaBrowser extends Field
{
    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $createdBy = '';
    public $mediaItems = [];
    public $mediaIds = [];

    protected string $view = 'filament-forms::components.mw-media-browser';

    protected function setUp(): void
    {
        parent::setUp();
        $this->registerListeners([
            'mwMediaBrowser::addMediaItem' => [
                function ($component, $statePath, $params) {
                    if (isset($params['data'])) {
                        return $this->addMediaItem($params['data']);
                    }
                },
            ],
            'mwMediaBrowser::deleteMediaItemById' => [
                function ($component, $statePath, $params) {
                    if (isset($params['id'])) {
                        return $this->deleteMediaItemById($params['id']);
                    }
                },
            ],
            'mwMediaBrowser::deleteMediaItemsByIds' => [
                function ($component, $statePath, $params) {
                    if (isset($params['ids'])) {
                        return $this->deleteMediaItemsByIds($params['ids']);
                    }
                },
            ],
            'mwMediaBrowser::updateImageFilename' => [
                function ($component, $statePath, $params) {
                    if (isset($params['id']) && isset($params['data'])) {
                        return $this->updateImageFilename($params['id'],$params['data']);
                    }
                },
            ],
            'mwMediaBrowser::mediaItemsSort' => [
                function ($component, $statePath, $params) {
                    if (isset($params['itemsSortedIds'])) {
                        return $this->mediaItemsSort($params['itemsSortedIds']);
                    }
                },
            ],
            'mwMediaBrowser::getMediaItemsArray' => [
                function ($component, $statePath, $params) {
                    return $this->getMediaItemsArray();
                },
            ],
        ]);

        $this->registerActions([
            fn (MwMediaBrowser $component): Action => $component->editAction(),
            fn (MwMediaBrowser $component): Action => $component->deleteAction(),
        ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->icon('mw-media-item-edit-small')
            ->mountUsing(function (Form $form, array $arguments) {
                $record = Media::find($arguments['id']);
                $form->fill($record->toArray());
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

    public function addMediaItem($data = [])
    {
        $url = false;

        if(isset($data['url'])){
            $url = $data['url'];
        }

        if (!$url) {
            return;
        }


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

        $this->refreshMediaData();

        $this->state($this->mediaIds);
    }

    public function updateImageFilename($id = false,$data=[])
    {
        $mediaId = $id;
        if (!$mediaId) {
            return;
        }
        $media = Media::where('id', $mediaId)->first();
        if (!$media) {
            return;
        }


        if(isset($data['src'])){
            $media->filename = $data['src'];
            $media->save();
            $this->refreshMediaData();
        }

    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('mw-media-item-delete-small')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                Media::where('id', $arguments['id'])->delete();
            });
    }

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

       if (!empty(trim($this->sessionId))) {
            $itemsQuery->where('session_id', $this->sessionId);
        } else if ($this->createdBy) {
            $itemsQuery->where('created_by', $this->createdBy);
        }

        if (!empty(trim($this->relId))) {
            $itemsQuery->where('rel_id', $this->relId);
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

}
