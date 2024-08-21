<?php

namespace MicroweberPackages\Filament\Forms\Components;


use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
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

    public $parentComponentName = '';

    protected string $view = 'filament-forms::components.mw-media-browser';

    protected function setUp(): void
    {
        parent::setUp();
        $this->registerListeners([
            'mwMediaBrowser::addMediaItem' => [
                function (): void {
                   dd(333);
                },
            ]
        ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
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

            ])->record(function (array $arguments) {
                $record = Media::find($arguments['id']);
                return $record;
            })
            ->action(function (array $data) {
                $record = Media::find($data['id']);
                $record->update($data);
            })->slideOver();
    }


    #[On('mediaItemsSort')]
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

        $data = [
            'mediaIds' => $this->mediaIds
        ];
        if ($this->parentComponentName) {
            $this->dispatch('modifyComponentData', $data)->to($this->parentComponentName);
        }
        //  $this->dispatch('$refresh');

    }

    #[On('addMediaItem')]
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
            $mediaItem->rel_type = $this->relType;
            $mediaItem->rel_id = $this->relId;
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

        $data = [
            'mediaIds' => $this->mediaIds
        ];
        if ($this->parentComponentName) {
            $this->dispatch('modifyComponentData', $data)->to($this->parentComponentName);
        }
        //   $this->dispatch('$refresh');
    }

    #[On('updateImageFilename')]
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

    #[On('deleteMediaItemById')]
    public function deleteMediaItemById($id = false)
    {
        $mediaId = $id;
        if (!$mediaId) {
            return;
        }
        Media::where('id', $mediaId)->delete();

        $this->refreshMediaData();
    }

    #[On('deleteMediaItemsByIds')]
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
        $itemsQuery = Media::where('rel_type', $this->relType);

        if ($this->relId) {
            $itemsQuery->where('rel_id', $this->relId);
        } else if ($this->sessionId) {
            $itemsQuery->where('session_id', $this->sessionId);
            $itemsQuery->where('rel_id', 0);
        } else if ($this->createdBy) {
            $itemsQuery->where('created_by', $this->createdBy);
            $itemsQuery->where('rel_id', 0);


        }

        if ($this->relType) {
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
