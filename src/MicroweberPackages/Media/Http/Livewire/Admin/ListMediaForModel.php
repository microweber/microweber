<?php

namespace MicroweberPackages\Media\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Media\Models\Media;
use Livewire\Attributes\On;

class ListMediaForModel extends AdminComponent
{




    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $mediaItems = '';


    public function getQueryBuilder()
    {
        $itemsQuery = Media::where('rel_type', $this->relType);

        if ($this->relId) {
            $itemsQuery->where('rel_id', $this->relId);
        } else if ($this->sessionId) {
            $itemsQuery->where('session_id', $this->sessionId);
        }

        if ($this->relType) {
            $itemsQuery->where('rel_type', $this->relType);
        }
        return $itemsQuery;
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
            if (!$this->relId) {
                $mediaItem->session_id = $this->sessionId;
            }

            $mediaItem->save();
        }

        $this->dispatch('$refresh');


    }


    public function render(): View
    {
        $itemsQuery = $this->getQueryBuilder();

        $this->mediaItems = $itemsQuery->get();

        return view('media::admin.livewire.list-media-for-model');
    }
}
