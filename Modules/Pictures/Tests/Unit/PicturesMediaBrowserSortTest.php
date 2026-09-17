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

    /**
     * task-2026-09-17 — the detail panel's Caption / Alt text / Link / Thumbnail
     * crop fields must persist into the Media `image_options` JSON using the SAME
     * keys the Pictures skins read (caption / alt-text / link / crop). The legacy
     * edit action only wrote the title/description columns, which never rendered.
     */
    #[Test]
    public function update_media_item_meta_persists_into_image_options(): void
    {
        $moduleId = 'pictures-utest-meta-' . uniqid();

        $media = new Media();
        $media->rel_type = 'module';
        $media->rel_id = $moduleId;
        $media->media_type = 'picture';
        $media->filename = 'utest-meta.jpg';
        $media->position = 1;
        $media->save();

        Livewire::test(PicturesModuleSettings::class)
            ->set(['params' => ['id' => $moduleId, 'type' => 'pictures']])
            ->call('callSchemaComponentMethod', 'form.mediaIds', 'updateMediaItemMeta', [
                'data' => [
                    'id' => $media->id,
                    'caption' => 'Sandstone canyon at dawn',
                    'altText' => 'Canyon at sunrise',
                    'link' => 'https://example.com/tour',
                    'crop' => 'top',
                ],
            ]);

        $opts = Media::find($media->id)->image_options;

        $this->assertIsArray($opts);
        $this->assertSame('Sandstone canyon at dawn', $opts['caption']);
        $this->assertSame('Canyon at sunrise', $opts['alt-text']);
        $this->assertSame('https://example.com/tour', $opts['link']);
        $this->assertSame('top', $opts['crop']);

        // A later blur on ONE field must not wipe the others (only present keys change).
        Livewire::test(PicturesModuleSettings::class)
            ->set(['params' => ['id' => $moduleId, 'type' => 'pictures']])
            ->call('callSchemaComponentMethod', 'form.mediaIds', 'updateMediaItemMeta', [
                'data' => ['id' => $media->id, 'caption' => 'Updated caption'],
            ]);

        $opts = Media::find($media->id)->image_options;
        $this->assertSame('Updated caption', $opts['caption']);
        $this->assertSame('Canyon at sunrise', $opts['alt-text'], 'alt-text must survive a caption-only update');
        $this->assertSame('top', $opts['crop'], 'crop must survive a caption-only update');

        Media::where('id', $media->id)->delete();
    }

    /**
     * The Link → "Page" picker feeds getSitePages(); it must return a list of
     * {title, url} for the site's active pages.
     */
    #[Test]
    public function get_site_pages_returns_a_titled_url_list(): void
    {
        // getSitePages() only queries Content, so it can run on a bare component.
        $pages = \MicroweberPackages\Filament\Forms\Components\MwMediaBrowser::make('mediaIds')
            ->getSitePages();

        $this->assertIsArray($pages, 'getSitePages should return an array');
        if (!empty($pages)) {
            $first = $pages[0];
            $this->assertArrayHasKey('title', $first);
            $this->assertArrayHasKey('url', $first);
            $this->assertNotSame('', (string) $first['url']);
        }
    }
}
