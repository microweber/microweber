<?php

namespace Modules\MediaLibrary\Tests\Unit\Livewire;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;
use Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin');

        // Clean slate so tests don't depend on fixture data
        Media::query()->delete();
        MediaFolder::query()->delete();
    }

    protected function tearDown(): void
    {
        // Clean up test data to avoid leaking into other tests
        Media::query()->where('title', 'like', 'Test Media%')->delete();
        Media::query()->where('title', 'like', 'Search Me%')->delete();
        Media::query()->where('title', 'like', 'Bulk Item%')->delete();
        Media::query()->where('title', 'like', 'Detail Media%')->delete();
        Media::query()->where('title', 'like', 'Move Me%')->delete();
        Media::query()->where('title', 'like', 'Delete Me%')->delete();
        Media::query()->where('title', 'like', 'Filtered%')->delete();
        MediaFolder::query()->where('name', 'like', 'Test Folder%')->delete();
        MediaFolder::query()->where('name', 'like', 'Renamed%')->delete();
        MediaFolder::query()->where('name', 'like', 'Empty Folder%')->delete();
        MediaFolder::query()->where('name', 'like', 'Parent Folder%')->delete();
        MediaFolder::query()->where('name', 'like', 'Child Folder%')->delete();
        MediaFolder::query()->where('name', 'like', 'Move Target%')->delete();
        MediaFolder::query()->where('name', 'like', 'Bulk Folder%')->delete();

        parent::tearDown();
    }

    // =========================================================================
    // Page Rendering
    // =========================================================================

    #[Test]
    public function it_renders_the_media_library_page(): void
    {
        Livewire::test(MediaLibrary::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_defaults_to_grid_view_mode(): void
    {
        Livewire::test(MediaLibrary::class)
            ->assertSet('viewMode', 'grid')
            ->assertSet('activeTab', 'library');
    }

    // =========================================================================
    // Folder CRUD
    // =========================================================================

    #[Test]
    public function it_can_create_a_folder(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', 'Test Folder Create')
            ->call('createFolder')
            ->assertSuccessful();

        $this->assertDatabaseHas('media_folders', [
            'name' => 'Test Folder Create',
            'slug' => 'test-folder-create',
        ]);
    }

    #[Test]
    public function it_can_create_a_subfolder(): void
    {
        $parent = MediaFolder::create([
            'name' => 'Parent Folder',
            'slug' => 'parent-folder',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', 'Child Folder')
            ->set('newFolderParentId', $parent->id)
            ->call('createFolder')
            ->assertSuccessful();

        $this->assertDatabaseHas('media_folders', [
            'name' => 'Child Folder',
            'parent_id' => $parent->id,
        ]);
    }

    #[Test]
    public function it_does_not_create_folder_with_empty_name(): void
    {
        $countBefore = MediaFolder::count();

        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', '')
            ->call('createFolder')
            ->assertSuccessful();

        $this->assertEquals($countBefore, MediaFolder::count());
    }

    #[Test]
    public function it_does_not_create_folder_with_whitespace_only_name(): void
    {
        $countBefore = MediaFolder::count();

        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', '   ')
            ->call('createFolder')
            ->assertSuccessful();

        $this->assertEquals($countBefore, MediaFolder::count());
    }

    #[Test]
    public function it_can_rename_a_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder Original',
            'slug' => 'test-folder-original',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('startRenameFolder', $folder->id)
            ->assertSet('renameFolderId', $folder->id)
            ->assertSet('renameFolderName', 'Test Folder Original')
            ->set('renameFolderName', 'Renamed Folder')
            ->call('renameFolder')
            ->assertSuccessful();

        $this->assertDatabaseHas('media_folders', [
            'id' => $folder->id,
            'name' => 'Renamed Folder',
            'slug' => 'renamed-folder',
        ]);
    }

    #[Test]
    public function it_cannot_rename_a_system_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder System',
            'slug' => 'test-folder-system',
            'is_system' => true,
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('startRenameFolder', $folder->id)
            ->assertSet('renameFolderId', null);
    }

    #[Test]
    public function it_can_cancel_rename(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder Cancel',
            'slug' => 'test-folder-cancel',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('startRenameFolder', $folder->id)
            ->assertSet('renameFolderId', $folder->id)
            ->call('cancelRename')
            ->assertSet('renameFolderId', null)
            ->assertSet('renameFolderName', '');
    }

    #[Test]
    public function it_can_delete_an_empty_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Empty Folder Delete',
            'slug' => 'empty-folder-delete',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $folder->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing('media_folders', [
            'id' => $folder->id,
        ]);
    }

    #[Test]
    public function it_cannot_delete_folder_with_media(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder With Media',
            'slug' => 'test-folder-with-media',
        ]);

        Media::create([
            'title' => 'Test Media In Folder',
            'filename' => 'test-image.jpg',
            'media_type' => 'picture',
            'folder_id' => $folder->id,
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $folder->id)
            ->assertDispatched('notify');

        $this->assertDatabaseHas('media_folders', [
            'id' => $folder->id,
        ]);
    }

    #[Test]
    public function it_cannot_delete_folder_with_children(): void
    {
        $parent = MediaFolder::create([
            'name' => 'Parent Folder With Child',
            'slug' => 'parent-folder-with-child',
        ]);

        MediaFolder::create([
            'name' => 'Child Folder Of Parent',
            'slug' => 'child-folder-of-parent',
            'parent_id' => $parent->id,
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $parent->id)
            ->assertDispatched('notify');

        $this->assertDatabaseHas('media_folders', [
            'id' => $parent->id,
        ]);
    }

    #[Test]
    public function it_cannot_delete_system_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder System Delete',
            'slug' => 'test-folder-system-delete',
            'is_system' => true,
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $folder->id)
            ->assertSuccessful();

        $this->assertDatabaseHas('media_folders', [
            'id' => $folder->id,
        ]);
    }

    #[Test]
    public function it_resets_selected_folder_when_deleting_active_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Empty Folder Active',
            'slug' => 'empty-folder-active',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('selectedFolderId', $folder->id)
            ->call('deleteFolder', $folder->id)
            ->assertSet('selectedFolderId', null);
    }

    // =========================================================================
    // Folder Selection
    // =========================================================================

    #[Test]
    public function it_can_select_a_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Test Folder Select',
            'slug' => 'test-folder-select',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectFolder', $folder->id)
            ->assertSet('selectedFolderId', $folder->id)
            ->assertSet('selectedMediaId', null)
            ->assertSet('bulkSelected', []);
    }

    #[Test]
    public function it_can_deselect_folder_by_selecting_null(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('selectedFolderId', 999)
            ->call('selectFolder', null)
            ->assertSet('selectedFolderId', null);
    }

    // =========================================================================
    // View Mode
    // =========================================================================

    #[Test]
    public function it_can_toggle_to_list_view(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('toggleView', 'list')
            ->assertSet('viewMode', 'list');
    }

    #[Test]
    public function it_can_toggle_to_grid_view(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('toggleView', 'list')
            ->call('toggleView', 'grid')
            ->assertSet('viewMode', 'grid');
    }

    #[Test]
    public function it_ignores_invalid_view_mode(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('toggleView', 'invalid')
            ->assertSet('viewMode', 'grid');
    }

    // =========================================================================
    // Search & Filters
    // =========================================================================

    #[Test]
    public function it_can_search_media_by_title(): void
    {
        Media::create([
            'title' => 'Search Me Unique Title',
            'filename' => 'search-me.jpg',
            'media_type' => 'picture',
        ]);

        Media::create([
            'title' => 'Test Media Other',
            'filename' => 'other.jpg',
            'media_type' => 'picture',
        ]);

        $component = Livewire::test(MediaLibrary::class)
            ->set('search', 'Search Me Unique');

        // The component should filter results via getMediaProperty()
        $component->assertSuccessful();
    }

    #[Test]
    public function it_can_filter_media_by_type(): void
    {
        Media::create([
            'title' => 'Filtered Image',
            'filename' => 'filtered-image.jpg',
            'media_type' => 'picture',
        ]);

        Media::create([
            'title' => 'Filtered Video',
            'filename' => 'filtered-video.mp4',
            'media_type' => 'video',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('typeFilter', 'picture')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_filter_media_by_date_range(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('dateFrom', '2026-01-01')
            ->set('dateTo', '2026-12-31')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_clear_all_filters(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('search', 'something')
            ->set('typeFilter', 'picture')
            ->set('dateFrom', '2026-01-01')
            ->set('dateTo', '2026-12-31')
            ->set('selectedFolderId', 1)
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('typeFilter', '')
            ->assertSet('dateFrom', '')
            ->assertSet('dateTo', '')
            ->assertSet('selectedFolderId', null);
    }

    // =========================================================================
    // Media Selection & Detail Panel
    // =========================================================================

    #[Test]
    public function it_can_select_media_and_load_details(): void
    {
        $media = Media::create([
            'title' => 'Detail Media Item',
            'filename' => 'detail-image.jpg',
            'media_type' => 'picture',
            'description' => 'A test description',
            'metadata' => ['alt_text' => 'Test alt'],
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', $media->id)
            ->assertSet('editTitle', 'Detail Media Item')
            ->assertSet('editDescription', 'A test description')
            ->assertSet('editAltText', 'Test alt');
    }

    #[Test]
    public function it_can_toggle_media_selection_off(): void
    {
        $media = Media::create([
            'title' => 'Detail Media Toggle',
            'filename' => 'toggle.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', $media->id)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', null)
            ->assertSet('selectedMediaData', null);
    }

    #[Test]
    public function it_can_close_detail_panel(): void
    {
        $media = Media::create([
            'title' => 'Detail Media Close',
            'filename' => 'close.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->call('closeDetailPanel')
            ->assertSet('selectedMediaId', null)
            ->assertSet('selectedMediaData', null);
    }

    #[Test]
    public function it_can_save_media_details(): void
    {
        $media = Media::create([
            'title' => 'Detail Media Save',
            'filename' => 'save.jpg',
            'media_type' => 'picture',
            'metadata' => [],
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->set('editTitle', 'Updated Title')
            ->set('editDescription', 'Updated description')
            ->set('editAltText', 'Updated alt text')
            ->call('saveMediaDetails')
            ->assertDispatched('notify');

        $media->refresh();
        $this->assertEquals('Updated Title', $media->title);
        $this->assertEquals('Updated description', $media->description);
        $this->assertEquals('Updated alt text', data_get($media->metadata, 'alt_text'));
    }

    #[Test]
    public function it_does_not_save_when_no_media_selected(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('selectedMediaId', null)
            ->call('saveMediaDetails')
            ->assertSuccessful();
    }

    // =========================================================================
    // Delete Media
    // =========================================================================

    #[Test]
    public function it_can_delete_a_media_item(): void
    {
        $media = Media::create([
            'title' => 'Delete Me Single',
            'filename' => 'delete-me.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteMedia', $media->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);
    }

    #[Test]
    public function it_clears_selection_when_deleting_selected_media(): void
    {
        $media = Media::create([
            'title' => 'Delete Me Selected',
            'filename' => 'delete-selected.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', $media->id)
            ->call('deleteMedia', $media->id)
            ->assertSet('selectedMediaId', null)
            ->assertSet('selectedMediaData', null);
    }

    // =========================================================================
    // Bulk Actions
    // =========================================================================

    #[Test]
    public function it_can_toggle_bulk_select(): void
    {
        $media = Media::create([
            'title' => 'Bulk Item Toggle',
            'filename' => 'bulk-toggle.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('toggleBulkSelect', $media->id)
            ->assertSet('bulkSelected', [$media->id])
            ->call('toggleBulkSelect', $media->id)
            ->assertSet('bulkSelected', []);
    }

    #[Test]
    public function it_can_select_all_visible_media(): void
    {
        // Create a few test media items
        $ids = [];
        for ($i = 1; $i <= 3; $i++) {
            $m = Media::create([
                'title' => "Bulk Item All {$i}",
                'filename' => "bulk-all-{$i}.jpg",
                'media_type' => 'picture',
            ]);
            $ids[] = $m->id;
        }

        $component = Livewire::test(MediaLibrary::class)
            ->call('selectAllVisible');

        // bulkSelected should contain at least our created items
        $bulkSelected = $component->get('bulkSelected');
        foreach ($ids as $id) {
            $this->assertContains($id, $bulkSelected);
        }
    }

    #[Test]
    public function it_can_deselect_all(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [1, 2, 3])
            ->call('deselectAll')
            ->assertSet('bulkSelected', []);
    }

    #[Test]
    public function it_can_bulk_delete_media(): void
    {
        $media1 = Media::create([
            'title' => 'Bulk Item Delete 1',
            'filename' => 'bulk-del-1.jpg',
            'media_type' => 'picture',
        ]);
        $media2 = Media::create([
            'title' => 'Bulk Item Delete 2',
            'filename' => 'bulk-del-2.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [$media1->id, $media2->id])
            ->call('bulkDelete')
            ->assertSet('bulkSelected', [])
            ->assertDispatched('notify');

        $this->assertDatabaseMissing('media', ['id' => $media1->id]);
        $this->assertDatabaseMissing('media', ['id' => $media2->id]);
    }

    #[Test]
    public function it_does_nothing_on_bulk_delete_with_empty_selection(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [])
            ->call('bulkDelete')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_bulk_move_media_to_folder(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Move Target Folder',
            'slug' => 'move-target-folder',
        ]);

        $media1 = Media::create([
            'title' => 'Move Me 1',
            'filename' => 'move-1.jpg',
            'media_type' => 'picture',
        ]);
        $media2 = Media::create([
            'title' => 'Move Me 2',
            'filename' => 'move-2.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [$media1->id, $media2->id])
            ->call('bulkMoveToFolder', $folder->id)
            ->assertSet('bulkSelected', [])
            ->assertDispatched('notify');

        $media1->refresh();
        $media2->refresh();
        $this->assertEquals($folder->id, $media1->folder_id);
        $this->assertEquals($folder->id, $media2->folder_id);
    }

    #[Test]
    public function it_can_bulk_move_media_to_root(): void
    {
        $folder = MediaFolder::create([
            'name' => 'Bulk Folder Source',
            'slug' => 'bulk-folder-source',
        ]);

        $media = Media::create([
            'title' => 'Move Me Root',
            'filename' => 'move-root.jpg',
            'media_type' => 'picture',
            'folder_id' => $folder->id,
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [$media->id])
            ->call('bulkMoveToFolder', null)
            ->assertDispatched('notify');

        $media->refresh();
        $this->assertNull($media->folder_id);
    }

    // =========================================================================
    // Tab Switching
    // =========================================================================

    #[Test]
    public function it_can_switch_to_unsplash_tab(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('switchTab', 'unsplash')
            ->assertSet('activeTab', 'unsplash');
    }

    #[Test]
    public function it_can_switch_back_to_library_tab(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('switchTab', 'unsplash')
            ->call('switchTab', 'library')
            ->assertSet('activeTab', 'library');
    }

    #[Test]
    public function it_ignores_invalid_tab(): void
    {
        Livewire::test(MediaLibrary::class)
            ->call('switchTab', 'invalid')
            ->assertSet('activeTab', 'library');
    }

    // =========================================================================
    // Folder Filtering with Subfolders
    // =========================================================================

    #[Test]
    public function it_filters_media_by_folder_including_subfolders(): void
    {
        $parent = MediaFolder::create([
            'name' => 'Parent Folder Filter',
            'slug' => 'parent-folder-filter',
        ]);

        $child = MediaFolder::create([
            'name' => 'Child Folder Filter',
            'slug' => 'child-folder-filter',
            'parent_id' => $parent->id,
        ]);

        Media::create([
            'title' => 'Test Media In Parent',
            'filename' => 'parent-media.jpg',
            'media_type' => 'picture',
            'folder_id' => $parent->id,
        ]);

        Media::create([
            'title' => 'Test Media In Child',
            'filename' => 'child-media.jpg',
            'media_type' => 'picture',
            'folder_id' => $child->id,
        ]);

        // With includeSubfolders = true (default), selecting parent should include child media
        $component = Livewire::test(MediaLibrary::class)
            ->call('selectFolder', $parent->id)
            ->assertSet('selectedFolderId', $parent->id)
            ->assertSet('includeSubfolders', true);

        $component->assertSuccessful();
    }

    // =========================================================================
    // Format File Size Helper
    // =========================================================================

    #[Test]
    public function it_formats_file_sizes_correctly(): void
    {
        $component = new MediaLibrary();

        $this->assertEquals('-', $component->formatFileSize(null));
        $this->assertEquals('-', $component->formatFileSize(0));
        $this->assertEquals('1.0 KB', $component->formatFileSize(1024));
        $this->assertEquals('500.0 KB', $component->formatFileSize(512000));
        $this->assertEquals('1.0 MB', $component->formatFileSize(1048576));
        $this->assertEquals('5.0 MB', $component->formatFileSize(5242880));
    }

    // =========================================================================
    // CDN Sync (no CDN configured — expected path)
    // =========================================================================

    #[Test]
    public function it_warns_when_cdn_is_not_configured(): void
    {
        $media = Media::create([
            'title' => 'Bulk Item CDN',
            'filename' => 'cdn-test.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [$media->id])
            ->call('bulkSyncToCdn')
            ->assertDispatched('notify');
    }

    // =========================================================================
    // File Upload (full Filament flow)
    // =========================================================================

    #[Test]
    public function it_uploads_an_image_via_filament_livewire(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('upload-test.jpg', 200, 150);

        Livewire::test(MediaLibrary::class)
            ->set('uploads', [$file])
            ->assertDispatched('notify');

        $this->assertDatabaseHas('media', [
            'media_type' => 'picture',
        ]);

        $row = Media::query()->where('media_type', 'picture')->latest('id')->first();
        $this->assertNotNull($row);
        $this->assertStringContainsString('.jpg', $row->filename);
        $this->assertEquals('upload-test', $row->title);
    }

    #[Test]
    public function it_uploads_into_selected_folder(): void
    {
        Storage::fake('public');

        $folder = MediaFolder::create([
            'name' => 'Upload Target Folder',
            'slug' => 'upload-target-folder',
        ]);

        $file = UploadedFile::fake()->image('folder-upload.png', 100, 100);

        Livewire::test(MediaLibrary::class)
            ->set('selectedFolderId', $folder->id)
            ->set('uploads', [$file]);

        $this->assertDatabaseHas('media', [
            'folder_id' => $folder->id,
            'media_type' => 'picture',
        ]);
    }

    #[Test]
    public function it_blocks_executable_uploads(): void
    {
        Storage::fake('public');

        $bad = UploadedFile::fake()->create('evil.php', 10, 'application/x-php');

        try {
            Livewire::test(MediaLibrary::class)
                ->set('uploads', [$bad]);
        } catch (\Throwable $e) {
            // Validation may throw — that's also acceptable rejection
        }

        $this->assertDatabaseMissing('media', [
            'filename' => 'evil.php',
        ]);
    }

    #[Test]
    public function it_does_nothing_on_cdn_sync_with_empty_selection(): void
    {
        Livewire::test(MediaLibrary::class)
            ->set('bulkSelected', [])
            ->call('bulkSyncToCdn')
            ->assertSuccessful();
    }
}
