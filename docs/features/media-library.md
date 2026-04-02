# Feature: Media Library — Full Admin UI

## Problem Statement

The Microweber admin panel's Media Library page (`/admin/media`) is currently a basic Filament table view with no visual browsing experience. Admins need to:

1. **Browse media visually** — the current table shows 60px thumbnails in rows, making it hard to scan images quickly. There is no grid/gallery view.
2. **Upload files efficiently** — there is a separate "Create" page for uploads, but no drag-and-drop zone on the main listing or batch upload capability from the list view.
3. **Organize media into folders** — the `media_folders` table and `MediaFolder` model exist but there is no folder navigation UI in the library. Folders are only selectable as a flat dropdown filter.
4. **Perform bulk operations** — selecting dozens of images for deletion or folder moves requires tedious checkbox-per-row interaction.
5. **Find media quickly** — there is basic text search on title, but no filtering by dimensions, date range, or file size, and no sort-by-size option.

Every content editor (pages, posts, products) already uses `MwMediaBrowser` for inline media selection. The standalone Media Library page should be the central hub for managing all uploaded assets.

## Proposed Solution

Replace the current table-only `MediaResource` list page with a rich media browser that provides:

- **Grid + List toggle** — default to a thumbnail grid view (4-6 columns) with a list-view fallback
- **Folder sidebar** — left-panel tree showing folder hierarchy with create/rename/delete actions
- **Drag-and-drop upload** — drop zone at the top of the main content area for quick batch uploads
- **Bulk actions toolbar** — select multiple items for delete, move-to-folder, or CDN sync
- **Metadata panel** — click an image to see/edit title, description, alt text, dimensions, file size, and usage info (which content items reference it)
- **Search and filters** — keyword search, type filter (image/video/audio/document), date range, size range
- **Unsplash integration** — retain existing stock photo search as a tab or panel within the library

## User Stories

- As an **admin**, I want to browse all uploaded media in a thumbnail grid, so that I can visually find the image I need without opening each one individually.
- As an **admin**, I want to drag files from my desktop onto the media library page, so that I can upload multiple images without navigating to a separate form.
- As an **admin**, I want to organize media into folders with a visible tree structure, so that I can keep assets categorized by project, page, or campaign.
- As an **admin**, I want to select multiple media items and move them to a folder in one action, so that I can reorganize assets efficiently.
- As an **admin**, I want to click a media item and see its metadata (dimensions, size, alt text, which pages use it), so that I can audit and maintain media quality.
- As an **admin**, I want to search media by keyword and filter by type (image, video, document), so that I can find specific assets in a large library.
- As an **admin**, I want to switch between grid and list views, so that I can choose the layout that fits my workflow.

## Acceptance Criteria

Each criterion is binary — pass or fail.

### Grid View
- [ ] Given the admin visits `/admin/media`, when the page loads, then media items display as a responsive thumbnail grid (3-6 columns depending on viewport width)
- [ ] Given a media item is an image, when it appears in the grid, then a proportionally-scaled thumbnail is shown (not stretched or cropped to square)
- [ ] Given the admin clicks the list-view toggle, when the view changes, then media items display in a table format with columns: preview, title, folder, type, size, date
- [ ] Given the admin's view preference (grid/list), when they return to the page later in the same session, then the preference is preserved

### Folder Navigation
- [ ] Given the admin visits the media library, when the page loads, then a left sidebar shows a folder tree with "All Media" root node and any created folders
- [ ] Given the admin clicks a folder in the sidebar, when the folder is selected, then only media items in that folder (and optionally its subfolders) are displayed
- [ ] Given the admin clicks "New Folder" in the sidebar, when a name is entered, then a new folder is created and appears in the tree
- [ ] Given the admin right-clicks (or uses a menu) on a folder, when they select "Rename" or "Delete", then the folder is renamed or deleted (with confirmation for delete)
- [ ] Given a folder has child folders, when displayed in the tree, then child folders are nested under the parent with expand/collapse controls

### Upload
- [ ] Given the admin is on the media library page, when they drag files from their desktop onto the drop zone, then files are uploaded with a progress indicator per file
- [ ] Given the admin uses the drop zone, when multiple files are dropped simultaneously, then all files are uploaded in parallel (up to a reasonable limit)
- [ ] Given a file exceeds 10MB, when the upload is attempted, then the file is rejected with a clear error message
- [ ] Given an upload completes, when the grid refreshes, then the new media item appears at the top of the list without full page reload

### Bulk Actions
- [ ] Given the admin selects multiple media items (via checkboxes or shift-click), when they click "Delete", then all selected items are deleted after confirmation
- [ ] Given the admin selects multiple media items, when they click "Move to folder" and choose a destination, then all selected items are moved to that folder
- [ ] Given the admin selects multiple media items, when they click "Sync to CDN", then all selected items are queued for CDN sync (existing behavior preserved)

### Metadata Panel
- [ ] Given the admin clicks a media item, when the detail panel opens, then it shows: thumbnail preview, title (editable), description (editable), alt text (editable), filename/URL, dimensions (width x height for images), file size, upload date, uploaded by
- [ ] Given the admin edits the title or description in the metadata panel, when they save, then the changes persist to the database
- [ ] Given the admin views a media item's detail panel, when the item is referenced by content, then a "Used in" section lists the content items (with links) that reference this media

### Search and Filters
- [ ] Given the admin types in the search bar, when at least 2 characters are entered, then results filter in real-time by title match
- [ ] Given the admin selects a type filter (Images, Videos, Audio, Documents), when applied, then only media of that type is displayed
- [ ] Given the admin applies a date range filter, when both start and end dates are set, then only media uploaded within that range is shown

## Out of Scope

- **Image editor** (crop, rotate, resize within the browser) — separate feature for a future phase
- **Video/audio player** — media items will show file-type icons for non-image types, not inline players
- **Public-facing media gallery** — this is admin-only; frontend gallery modules are separate
- **S3/CDN configuration UI** — CDN settings are managed in Settings pages, not in the media library
- **Media versioning/history** — no version tracking for replaced files
- **AI-powered tagging or auto-categorization** — future feature
- **Drag-and-drop reordering within the grid** — ordering is by upload date or alphabetical only

## Data Requirements

### Existing tables (no changes needed)
- `media` — core media table with title, filename, media_type, rel_type, rel_id, folder_id, file_size, metadata, CDN fields
- `media_folders` — hierarchical folder table with parent_id, name, slug, sort_order
- `media_thumbnails` — thumbnail cache table

### Potential additions
- `media.alt_text` column (string, nullable) — if not already stored in `metadata` JSON. Check whether `image_options` or `metadata` is used for alt text. If `metadata` JSON already supports arbitrary keys, no migration needed — use `metadata->alt_text`.

**Decision:** Use the existing `metadata` JSON column for alt text (`metadata->alt_text`). No new migration required.

## API Changes

### Existing endpoints (no changes needed)
- `POST /api/media/upload` — file upload endpoint (used by MwMediaBrowser)
- `POST /api/save_media` — save/update media records
- `GET /api/thumbnail_img` — thumbnail generation

### New Livewire component methods (not REST endpoints)
The Media Library will be implemented as a **Livewire full-page component** (Filament custom page), not a standard Filament Resource. This gives full control over the layout while staying within Filament's architecture.

Key Livewire methods:
- `loadMedia()` — paginated query with folder, type, search, date filters
- `uploadFiles()` — handle drag-and-drop uploads via Livewire file upload
- `deleteSelected()` — bulk delete
- `moveToFolder($folderId)` — bulk move
- `createFolder($name, $parentId)` — create folder
- `renameFolder($folderId, $name)` — rename folder
- `deleteFolder($folderId)` — delete folder (with empty check)
- `updateMediaMeta($mediaId, $data)` — update title, description, alt_text
- `toggleView($mode)` — switch grid/list

## UI Changes

### Layout (3-panel)
```
┌─────────────────────────────────────────────────────────┐
│ [Search...] [Type ▾] [Date ▾]   [Grid|List] [Upload ▲] │
├──────────┬──────────────────────────────┬───────────────┤
│ Folders  │  Media Grid / List           │  Detail Panel │
│          │                              │  (on select)  │
│ All Media│  ┌────┐ ┌────┐ ┌────┐       │               │
│ ▶ Photos │  │    │ │    │ │    │       │  Preview      │
│ ▶ Blog   │  └────┘ └────┘ └────┘       │  Title: ...   │
│   Icons  │  ┌────┐ ┌────┐ ┌────┐       │  Size: 1.2MB  │
│          │  │    │ │    │ │    │       │  Dims: 1920×  │
│          │  └────┘ └────┘ └────┘       │  Used in: ... │
│          │                              │               │
│ [+Folder]│  ◀ 1 2 3 4 5 ▶             │  [Save] [Del] │
└──────────┴──────────────────────────────┴───────────────┘
```

- **Folder sidebar:** ~200px wide, scrollable tree with expand/collapse
- **Main content:** responsive grid with 120-160px thumbnails, or table rows in list mode
- **Detail panel:** ~300px slide-in from right on item click, closeable
- **Upload zone:** appears at top of main content on drag-over, or via button click

### Responsive behavior
- **Desktop (>1024px):** 3-panel layout as shown
- **Tablet (768-1024px):** Folder sidebar collapses to dropdown; detail panel becomes modal
- **Mobile (<768px):** Single column; folder as dropdown; grid 2 columns; detail as full-screen modal

## Security Considerations

- **Admin-only access:** `canAccess()` returns `is_admin()` (already implemented)
- **File upload validation:** Enforce allowed MIME types (images: jpg/png/gif/svg/webp; documents: pdf/doc/docx; video: mp4/webm; audio: mp3/wav/ogg). Reject executable files (.php, .js, .sh, .exe, .bat).
- **Path traversal:** Uploads go to `userfiles/media/` — ensure filenames are sanitized (no `../` sequences)
- **File size limit:** 10MB per file enforced server-side (existing validation in upload endpoint)
- **CSRF protection:** All Livewire methods are CSRF-protected by default
- **Folder deletion:** Only allow deletion of empty folders or prompt for confirmation with item count

## Dependencies

- **Existing:** Media model, MediaFolder model, MediaManager service, BulkUploadService, CdnIntegrationService — all exist and are functional
- **Filament 5:** Custom page with Livewire v4 full-page component
- **Alpine.js:** For grid interactions, drag-and-drop, and view toggling (already available via Filament)
- **No new packages required**

## Open Questions

1. **Subfolder filtering:** When a folder is selected, should media in subfolders also be shown? Proposed: yes, with a toggle "Include subfolders" (default on).
2. **Orphaned media:** Should media items not associated with any content (`rel_id = null`) have a special filter? Proposed: yes, add an "Unattached" filter.
3. **Upload to folder:** When a folder is selected and the admin uploads, should media automatically go into that folder? Proposed: yes.
4. **Drag to folder:** Should admins be able to drag media items onto folders in the sidebar to move them? Proposed: defer to Phase 2.5 enhancement — start with bulk "Move to folder" action.

## Risks

- **Performance with large media libraries:** Sites with 10,000+ media items need efficient pagination. Mitigation: use cursor-based pagination, lazy-load thumbnails with `loading="lazy"`, and index on `folder_id + created_at`.
- **Livewire file upload size limits:** Livewire has its own upload temp directory and size constraints. Mitigation: configure `livewire.temporary_file_upload.rules` for the 10MB limit, and use chunked uploads for large files if needed.
- **Thumbnail generation lag:** First load of a large grid may trigger many thumbnail generations. Mitigation: use existing `media_thumbnails` cache table and generate thumbnails asynchronously on upload (existing behavior).
