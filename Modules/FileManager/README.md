# FileManager

Server-side file browser and manager. Upload, organize, browse, and select files from the server filesystem through the admin panel.

## Key Features

- File browsing with directory navigation
- File upload support
- File selection for content editing
- Integrated into admin panel and settings
- Web routes for file operations

## Key Classes

| Class | Purpose |
|---|---|
| `Filament\Pages\FileManagerPageAdmin` | Admin file manager page |
| `Http\Controllers\*` | File operation controllers |

## Admin Panel (Filament)

- **FileManagerPageAdmin** -- full file browser page, registered on both the main admin and Settings pages

## Routes

Defined in `routes/web.php` for file browsing and upload operations.

## Usage

Access the file manager through the admin panel under the Settings section. It provides a visual interface for managing media and other files on the server.
