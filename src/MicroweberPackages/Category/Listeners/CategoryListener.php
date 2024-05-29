<?php
namespace MicroweberPackages\Category\Listeners;


use Illuminate\Support\Facades\Session;
use MicroweberPackages\Media\Models\Media;

class CategoryListener
{
    public function handle($event)
    {
        $data = $event->getData();
        $model = $event->getModel();

        Media::where('session_id', Session::getId())
            ->where('rel_id', 0)
            ->where('rel_type', $model->getMorphClass())
            ->update(['rel_id' => $model->id]);

            cache_clear('repositories');
            cache_clear('media');
            cache_clear('categories');
            cache_clear('content');
    }
}
