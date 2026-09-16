<?php

namespace Modules\Pictures\Tests\Unit;

use Livewire\Livewire;
use Modules\Media\Models\Media;
use Modules\Pictures\Filament\PicturesModuleSettings;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-09-16 — media-browser persistence.
 *
 * Guards the server side of the drag-drop upload + thumbnail reorder that the
 * Pictures settings modal relies on (MwMediaBrowser, driven from
 * mwMediaManagerComponent via $wire):
 *   - mediaItemsSort($ids) must write Media.position in the given order, so a
 *     reorder actually saves instead of snapping back.
 *   - addMediaItem(['url' => …]) must create a Media row for the module, so a
 *     dropped image is added to the gallery.
 *
 * Mounts the real PicturesModuleSettings host so the Filament schema component
 * (getRecord()/refreshMediaData()) is fully initialised — the methods can't run
 * on a bare component instance.
 */
class PicturesMediaBrowserSortTest extends TestCase
{
    #[Test]
    public function media_items_sort_persists_the_new_order(): void
    {
        $moduleId = 'pictures-utest-sort-' . uniqid();

        $ids = [];
        foreach ([1, 2, 3] as $position) {
            $media = new Media();
            $media->rel_type = 'module';
            $media->rel_id = $moduleId;
            $media->media_type = 'picture';
            $media->filename = "utest-sort-{$position}.jpg";
            $media->position = $position;
            $media->save();
            $ids[] = $media->id;
        }

        // Reverse the order: [id1,id2,id3] -> [id3,id2,id1]
        $reordered = array_reverse($ids);

        Livewire::test(PicturesModuleSettings::class)
            ->set(['params' => ['id' => $moduleId, 'type' => 'pictures']])
            ->call('callSchemaComponentMethod', 'form.mediaIds', 'mediaItemsSort', [
                'itemsSortedIds' => $reordered,
            ]);

        // Positions must now follow the reordered array (1-based).
        foreach ($reordered as $index => $id) {
            $this->assertSame(
                $index + 1,
                (int) Media::find($id)->position,
                "Media {$id} should be at position " . ($index + 1) . " after reorder"
            );
        }

        Media::whereIn('id', $ids)->delete();
    }

    #[Test]
    public function add_media_item_creates_a_picture_for_the_module(): void
    {
        $moduleId = 'pictures-utest-add-' . uniqid();

        $this->assertSame(
            0,
            Media::where('rel_type', 'module')->where('rel_id', $moduleId)->count()
        );

        Livewire::test(PicturesModuleSettings::class)
            ->set(['params' => ['id' => $moduleId, 'type' => 'pictures']])
            ->call('callSchemaComponentMethod', 'form.mediaIds', 'addMediaItem', [
                'data' => ['url' => 'utest-added-image.jpg'],
            ]);

        $rows = Media::where('rel_type', 'module')->where('rel_id', $moduleId)->get();

        $this->assertCount(1, $rows, 'addMediaItem should create exactly one Media row');
        $this->assertStringContainsString('utest-added-image.jpg', (string) $rows->first()->filename);

        Media::where('rel_type', 'module')->where('rel_id', $moduleId)->delete();
    }
}
