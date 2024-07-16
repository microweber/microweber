<?php

namespace MicroweberPackages\Media\Http\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Media\Models\Media;
use Livewire\Attributes\On;
use Filament\Forms\Get;
use Filament\Forms\Set;


class ListMediaForModel extends AdminComponent implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;


    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $createdBy = '';
    public $mediaItems = [];
    public $mediaIds = [];

    public $parentComponentName = '';


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
    public function addMediaItem($url = false)
    {
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

    #[On('rotateMediaById')]
    public function rotateMediaById($id = false)
    {
        $mediaId = $id;
        if (!$mediaId) {
            return;
        }
        $media = Media::where('id', $mediaId)->first();
        if (!$media) {
            return;
        }

        app()->media_manager->rotate_media_file_by_id($mediaId);

        $this->dispatch('imageIsRotated', $mediaId);
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


    public function render(): View
    {

        $this->refreshMediaData();

        //   $this->parentComponent->data['mediaIds'] = $this->mediaIds;
        return view('media::admin.livewire.list-media-for-model');
    }
}
