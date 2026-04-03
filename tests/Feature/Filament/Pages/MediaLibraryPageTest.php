<?php

namespace Tests\Feature\Filament\Pages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;
use Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class MediaLibraryPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    // =========================================================================
    // Page Rendering
    // =========================================================================

    #[Test]
    public function it_can_render_media_library_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_defaults_to_grid_view(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->assertSet('viewMode', 'grid');
    }

    #[Test]
    public function it_can_toggle_to_list_view(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->call('toggleView', 'list')
            ->assertSet('viewMode', 'list');
    }

    // =========================================================================
    // Search & Filters
    // =========================================================================

    #[Test]
    public function it_can_search_media_by_title(): void
    {
        $this->actingAsAdmin();

        Media::create([
            'title' => 'Unique Test Image Alpha',
            'filename' => 'test-alpha.jpg',
            'media_type' => 'picture',
        ]);

        Media::create([
            'title' => 'Another Image Beta',
            'filename' => 'test-beta.jpg',
            'media_type' => 'picture',
        ]);

        $component = Livewire::test(MediaLibrary::class)
            ->set('search', 'Alpha')
            ->assertSuccessful();

        $media = $component->instance()->getMediaProperty();
        $titles = $media->pluck('title')->toArray();

        $this->assertContains('Unique Test Image Alpha', $titles);
        $this->assertNotContains('Another Image Beta', $titles);
    }

    #[Test]
    public function it_can_filter_by_media_type(): void
    {
        $this->actingAsAdmin();

        Media::create([
            'title' => 'Test Video',
            'filename' => 'test.mp4',
            'media_type' => 'video',
        ]);

        $component = Livewire::test(MediaLibrary::class)
            ->set('typeFilter', 'video')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_filter_by_date_range(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->set('dateFrom', '2026-01-01')
            ->set('dateTo', '2026-12-31')
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_clear_all_filters(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->set('search', 'test')
            ->set('typeFilter', 'video')
            ->set('dateFrom', '2026-01-01')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('typeFilter', '')
            ->assertSet('dateFrom', '')
            ->assertSet('dateTo', '');
    }

    // =========================================================================
    // Folder CRUD
    // =========================================================================

    #[Test]
    public function it_can_create_a_folder(): void
    {
        $this->actingAsAdmin();

        $folderName = 'Test Folder ' . uniqid();

        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', $folderName)
            ->call('createFolder')
            ->assertSet('newFolderName', '');

        $this->assertDatabaseHas('media_folders', [
            'name' => $folderName,
        ]);
    }

    #[Test]
    public function it_does_not_create_folder_with_empty_name(): void
    {
        $this->actingAsAdmin();

        $countBefore = MediaFolder::count();

        Livewire::test(MediaLibrary::class)
            ->set('newFolderName', '')
            ->call('createFolder');

        $this->assertEquals($countBefore, MediaFolder::count());
    }

    #[Test]
    public function it_can_rename_a_folder(): void
    {
        $this->actingAsAdmin();

        $folder = MediaFolder::create([
            'name' => 'Original Name',
            'slug' => 'original-name',
            'created_by' => auth()->id(),
        ]);

        $newName = 'Renamed Folder ' . uniqid();

        Livewire::test(MediaLibrary::class)
            ->call('startRenameFolder', $folder->id)
            ->assertSet('renameFolderId', $folder->id)
            ->set('renameFolderName', $newName)
            ->call('renameFolder')
            ->assertSet('renameFolderId', null);

        $this->assertDatabaseHas('media_folders', [
            'id' => $folder->id,
            'name' => $newName,
        ]);
    }

    #[Test]
    public function it_can_delete_an_empty_folder(): void
    {
        $this->actingAsAdmin();

        $folder = MediaFolder::create([
            'name' => 'Delete Me',
            'slug' => 'delete-me',
            'created_by' => auth()->id(),
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $folder->id);

        $this->assertDatabaseMissing('media_folders', [
            'id' => $folder->id,
        ]);
    }

    #[Test]
    public function it_cannot_delete_non_empty_folder(): void
    {
        $this->actingAsAdmin();

        $folder = MediaFolder::create([
            'name' => 'Has Media',
            'slug' => 'has-media',
            'created_by' => auth()->id(),
        ]);

        Media::create([
            'title' => 'Inside Folder',
            'filename' => 'inside.jpg',
            'media_type' => 'picture',
            'folder_id' => $folder->id,
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteFolder', $folder->id);

        // Folder should still exist
        $this->assertDatabaseHas('media_folders', [
            'id' => $folder->id,
        ]);
    }

    #[Test]
    public function it_can_select_a_folder(): void
    {
        $this->actingAsAdmin();

        $folder = MediaFolder::create([
            'name' => 'Select Me',
            'slug' => 'select-me',
            'created_by' => auth()->id(),
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectFolder', $folder->id)
            ->assertSet('selectedFolderId', $folder->id);
    }

    // =========================================================================
    // Media Selection & Detail Panel
    // =========================================================================

    #[Test]
    public function it_can_select_media_and_show_detail(): void
    {
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Selectable Image',
            'filename' => 'selectable.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', $media->id)
            ->assertNotSet('selectedMediaData', null);
    }

    #[Test]
    public function it_can_toggle_media_selection(): void
    {
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Toggle Image',
            'filename' => 'toggle.jpg',
            'media_type' => 'picture',
        ]);

        // Select then deselect
        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', $media->id)
            ->call('selectMedia', $media->id)
            ->assertSet('selectedMediaId', null);
    }

    #[Test]
    public function it_can_close_detail_panel(): void
    {
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Close Panel Test',
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
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Original Title',
            'filename' => 'details.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('selectMedia', $media->id)
            ->set('editTitle', 'Updated Title')
            ->set('editDescription', 'New description')
            ->set('editAltText', 'Alt text for image')
            ->call('saveMediaDetails');

        $media->refresh();
        $this->assertEquals('Updated Title', $media->title);
        $this->assertEquals('New description', $media->description);
    }

    // =========================================================================
    // Bulk Actions
    // =========================================================================

    #[Test]
    public function it_can_toggle_bulk_select(): void
    {
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Bulk Test',
            'filename' => 'bulk.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('toggleBulkSelect', $media->id)
            ->assertSet('bulkSelected', [$media->id])
            ->call('toggleBulkSelect', $media->id)
            ->assertSet('bulkSelected', []);
    }

    #[Test]
    public function it_can_deselect_all(): void
    {
        $this->actingAsAdmin();

        $media1 = Media::create(['title' => 'Bulk 1', 'filename' => 'bulk1.jpg', 'media_type' => 'picture']);
        $media2 = Media::create(['title' => 'Bulk 2', 'filename' => 'bulk2.jpg', 'media_type' => 'picture']);

        Livewire::test(MediaLibrary::class)
            ->call('toggleBulkSelect', $media1->id)
            ->call('toggleBulkSelect', $media2->id)
            ->call('deselectAll')
            ->assertSet('bulkSelected', []);
    }

    #[Test]
    public function it_can_bulk_delete_media(): void
    {
        $this->actingAsAdmin();

        $media1 = Media::create(['title' => 'Delete 1', 'filename' => 'del1.jpg', 'media_type' => 'picture']);
        $media2 = Media::create(['title' => 'Delete 2', 'filename' => 'del2.jpg', 'media_type' => 'picture']);

        Livewire::test(MediaLibrary::class)
            ->call('toggleBulkSelect', $media1->id)
            ->call('toggleBulkSelect', $media2->id)
            ->call('bulkDelete')
            ->assertSet('bulkSelected', []);

        $this->assertDatabaseMissing('media', ['id' => $media1->id]);
        $this->assertDatabaseMissing('media', ['id' => $media2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_folder(): void
    {
        $this->actingAsAdmin();

        $folder = MediaFolder::create([
            'name' => 'Target Folder',
            'slug' => 'target-folder',
            'created_by' => auth()->id(),
        ]);

        $media = Media::create(['title' => 'Move Me', 'filename' => 'move.jpg', 'media_type' => 'picture']);

        Livewire::test(MediaLibrary::class)
            ->call('toggleBulkSelect', $media->id)
            ->call('bulkMoveToFolder', $folder->id)
            ->assertSet('bulkSelected', []);

        $media->refresh();
        $this->assertEquals($folder->id, $media->folder_id);
    }

    // =========================================================================
    // Delete Media
    // =========================================================================

    #[Test]
    public function it_can_delete_single_media(): void
    {
        $this->actingAsAdmin();

        $media = Media::create([
            'title' => 'Delete Single',
            'filename' => 'delete-single.jpg',
            'media_type' => 'picture',
        ]);

        Livewire::test(MediaLibrary::class)
            ->call('deleteMedia', $media->id);

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    // =========================================================================
    // Unsplash Tab
    // =========================================================================

    #[Test]
    public function it_can_switch_to_unsplash_tab(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->assertSet('activeTab', 'library')
            ->call('switchTab', 'unsplash')
            ->assertSet('activeTab', 'unsplash')
            ->call('switchTab', 'library')
            ->assertSet('activeTab', 'library');
    }

    #[Test]
    public function it_rejects_invalid_tab_names(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->call('switchTab', 'invalid')
            ->assertSet('activeTab', 'library');
    }

    // =========================================================================
    // Upload Validation
    // =========================================================================

    #[Test]
    public function it_can_upload_valid_image(): void
    {
        $this->actingAsAdmin();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-upload.jpg', 100, 100);

        Livewire::test(MediaLibrary::class)
            ->set('uploads', [$file])
            ->assertSuccessful();
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    #[Test]
    public function it_formats_file_sizes_correctly(): void
    {
        $this->actingAsAdmin();

        $component = Livewire::test(MediaLibrary::class);
        $instance = $component->instance();

        $this->assertEquals('-', $instance->formatFileSize(null));
        $this->assertEquals('-', $instance->formatFileSize(0));
        $this->assertEquals('1.0 KB', $instance->formatFileSize(1024));
        $this->assertEquals('1.5 MB', $instance->formatFileSize(1572864));
    }
}
