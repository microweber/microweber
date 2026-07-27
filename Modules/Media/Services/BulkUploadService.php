<?php

namespace Modules\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;

class BulkUploadService
{
    /**
     * @var array
     */
    protected $results = [];

    /**
     * @var int|null
     */
    protected $folderId = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Upload multiple files in batch.
     *
     * @param array $files Array of UploadedFile instances
     * @param array $options Upload options (folder_id, rel_type, rel_id, etc.)
     * @return array Results of the upload operation
     */
    public function uploadBatch(array $files, array $options = []): array
    {
        $this->options = $options;
        $this->folderId = $options['folder_id'] ?? null;
        $this->results = [
            'success' => [],
            'failed' => [],
            'total' => count($files),
            'processed' => 0,
        ];

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile) {
                $this->results['failed'][] = [
                    'index' => $index,
                    'name' => is_string($file) ? $file : 'unknown',
                    'error' => 'Invalid file instance',
                ];
                continue;
            }

            try {
                $result = $this->uploadSingle($file, $options);
                $this->results['success'][] = $result;
            } catch (\Exception $e) {
                $this->results['failed'][] = [
                    'index' => $index,
                    'name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ];
                Log::error('Bulk upload failed for file', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
            }

            $this->results['processed']++;
        }

        return $this->results;
    }

    /**
     * Upload a single file.
     *
     * @param UploadedFile $file
     * @param array $options
     * @return array
     * @throws \Exception
     */
    protected function uploadSingle(UploadedFile $file, array $options): array
    {
        // Validate file
        if (!$file->isValid()) {
            throw new \Exception('File upload failed: ' . $file->getErrorMessage());
        }

        // Generate filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = $this->generateFileName($originalName, $extension);

        // Determine storage path
        $folder = $this->getFolder();
        $storagePath = $this->getStoragePath($folder);

        // Store file
        $path = $file->storeAs($storagePath, $fileName, 'public');

        if (!$path) {
            throw new \Exception('Failed to store file');
        }

        // Get file metadata
        $fileSize = $file->getSize();
        $fileHash = md5_file($file->getRealPath());
        $mediaType = $this->guessMediaType($extension);

        // Create media record
        $mediaData = [
            'filename' => Storage::disk('public')->url($path),
            'title' => $options['title'] ?? pathinfo($originalName, PATHINFO_FILENAME),
            'description' => $options['description'] ?? null,
            'folder_id' => $this->folderId,
            'media_type' => $mediaType,
            'file_size' => $fileSize,
            'file_hash' => $fileHash,
            'rel_type' => $options['rel_type'] ?? null,
            'rel_id' => $options['rel_id'] ?? null,
            'session_id' => $options['session_id'] ?? null,
            'created_by' => $options['created_by'] ?? user_id(),
        ];

        // Process image-specific data
        if ($mediaType === 'picture') {
            $imageInfo = $this->getImageInfo($file);
            if ($imageInfo) {
                $mediaData['image_options'] = $imageInfo;
            }

            // Auto-resize if enabled
            if (!empty($options['auto_resize']) && $fileSize > ($options['resize_threshold'] ?? 2000000)) {
                $this->autoResizeImage($path, $options['max_dimension'] ?? 1980);
            }
        }

        $media = Media::create($mediaData);

        return [
            'id' => $media->id,
            'filename' => $media->filename,
            'title' => $media->title,
            'media_type' => $media->media_type,
            'file_size' => $fileSize,
            'file_size_human' => $this->humanFileSize($fileSize),
            'path' => $path,
        ];
    }

    /**
     * Generate a unique filename.
     *
     * @param string $originalName
     * @param string $extension
     * @return string
     */
    protected function generateFileName(string $originalName, string $extension): string
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = \MicroweberPackages\Url\URLify::filter($baseName);
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $baseName);
        $baseName = strtolower($baseName);

        $timestamp = date('YmdHis');
        $uniqueId = Str::random(6);

        return "{$baseName}-{$timestamp}-{$uniqueId}.{$extension}";
    }

    /**
     * Get folder model if folder_id is set.
     *
     * @return MediaFolder|null
     */
    protected function getFolder(): ?MediaFolder
    {
        if (!$this->folderId) {
            return null;
        }

        return MediaFolder::find($this->folderId);
    }

    /**
     * Get storage path based on folder.
     *
     * @param MediaFolder|null $folder
     * @return string
     */
    protected function getStoragePath(?MediaFolder $folder): string
    {
        $basePath = 'uploaded';

        if ($folder) {
            $folderPath = $this->getFolderPath($folder);
            return $basePath . '/' . $folderPath;
        }

        return $basePath;
    }

    /**
     * Get folder path recursively.
     *
     * @param MediaFolder $folder
     * @return string
     */
    protected function getFolderPath(MediaFolder $folder): string
    {
        $path = [$folder->slug];
        $parent = $folder->parent;

        while ($parent) {
            $path[] = $parent->slug;
            $parent = $parent->parent;
        }

        return implode('/', array_reverse($path));
    }

    /**
     * Guess media type from file extension.
     *
     * @param string $ext
     * @return string
     */
    protected function guessMediaType(string $ext): string
    {
        $ext = strtolower($ext);

        $types = [
            'picture' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'ogv'],
            'audio' => ['mp3', 'wav', 'ogg', 'flac', 'm4a', 'aac'],
        ];

        foreach ($types as $type => $extensions) {
            if (in_array($ext, $extensions)) {
                return $type;
            }
        }

        return 'file';
    }

    /**
     * Get image information.
     *
     * @param UploadedFile $file
     * @return array|null
     */
    protected function getImageInfo(UploadedFile $file): ?array
    {
        $path = $file->getRealPath();
        $info = getimagesize($path);

        if (!$info) {
            return null;
        }

        return [
            'width' => $info[0],
            'height' => $info[1],
            'mime_type' => $info['mime'],
        ];
    }

    /**
     * Auto-resize image.
     *
     * @param string $path
     * @param int $maxDimension
     * @return void
     */
    protected function autoResizeImage(string $path, int $maxDimension): void
    {
        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            return;
        }

        $info = getimagesize($fullPath);
        if (!$info) {
            return;
        }

        $width = $info[0];
        $height = $info[1];

        if ($width <= $maxDimension && $height <= $maxDimension) {
            return;
        }

        $ratio = $width / $height;
        if ($ratio > 1) {
            $newWidth = $maxDimension;
            $newHeight = $maxDimension / $ratio;
        } else {
            $newWidth = $maxDimension * $ratio;
            $newHeight = $maxDimension;
        }

        $src = imagecreatefromstring(file_get_contents($fullPath));
        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Handle transparency for PNG
        if ($info['mime'] === 'image/png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        if ($info['mime'] === 'image/png') {
            imagepng($dst, $fullPath, 9);
        } else {
            imagejpeg($dst, $fullPath, 90);
        }

        imagedestroy($dst);
    }

    /**
     * Convert bytes to human-readable format.
     *
     * @param int $bytes
     * @return string
     */
    protected function humanFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Get upload progress.
     *
     * @return array
     */
    public function getProgress(): array
    {
        return [
            'total' => $this->results['total'] ?? 0,
            'processed' => $this->results['processed'] ?? 0,
            'success_count' => count($this->results['success'] ?? []),
            'failed_count' => count($this->results['failed'] ?? []),
        ];
    }

    /**
     * Create default folders.
     *
     * @param int|null $userId Optional user ID, defaults to authenticated user
     * @return Collection
     */
    public static function createDefaultFolders(?int $userId = null): Collection
    {
        $folders = collect();
        $userId = $userId ?? auth()->id() ?? 1;

        $defaultFolders = [
            [
                'name' => 'Images',
                'slug' => 'images',
                'description' => 'Image files (JPG, PNG, GIF, etc.)',
                'is_system' => true,
            ],
            [
                'name' => 'Videos',
                'slug' => 'videos',
                'description' => 'Video files (MP4, AVI, MOV, etc.)',
                'is_system' => true,
            ],
            [
                'name' => 'Documents',
                'slug' => 'documents',
                'description' => 'Document files (PDF, DOC, XLS, etc.)',
                'is_system' => true,
            ],
            [
                'name' => 'Audio',
                'slug' => 'audio',
                'description' => 'Audio files (MP3, WAV, OGG, etc.)',
                'is_system' => true,
            ],
            [
                'name' => 'Uploads',
                'slug' => 'uploads',
                'description' => 'General uploads',
                'is_system' => true,
            ],
        ];

        foreach ($defaultFolders as $folderData) {
            $folderData['created_by'] = $userId;
            $folder = MediaFolder::firstOrCreate(
                ['slug' => $folderData['slug']],
                $folderData
            );
            $folders->push($folder);
        }

        return $folders;
    }
}
