<?php

namespace MicroweberPackages\Filament\Forms\Components;


use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\ExposedLivewireMethod;
use Modules\Media\Models\Media;

class MwMediaBrowser extends Field
{
    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $createdBy = '';
    public $mediaItems = [];
    public $mediaIds = [];

    protected string $view = 'mw-filament::components.mw-media-browser';

    protected function setUp(): void
    {
        parent::setUp();
        // Media browser methods are exposed to JS via #[ExposedLivewireMethod]
        // and called from JS using $wire.callSchemaComponentMethod().

        $this->registerActions([
            fn (MwMediaBrowser $component): Action => $component->editAction(),
            fn (MwMediaBrowser $component): Action => $component->deleteAction(),
        ]);
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->mountUsing(function (Schema $schema, array $arguments) {
                $record = Media::find($arguments['id']);
                $schema->fill($record->toArray());
            })
            ->form([
                Hidden::make('id')
                    ->required(),
                TextInput::make('title')
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(2550),
            ])
            ->modalSubmitActionLabel('Save')
            ->action(function (array $data) {
                $record = Media::find($data['id']);
                $record->update($data);
            })->size('xs');
    }

    #[ExposedLivewireMethod]
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
        $this->reflectOnCanvas();

    }
    #[ExposedLivewireMethod]
    public function deleteMediaItemById($id = false)
    {
        if (!$id) {
            return;
        }
        Media::where('id', $id)->delete();

        $this->refreshMediaData();
        $this->reflectOnCanvas();
    }
    public function addMediaItemSingle($url) {
        $itemsQuery = $this->getQueryBuilder();
        $itemsQuery = $itemsQuery->where('filename', $url);
        $mediaItem = $itemsQuery->first();

        //check if exists

        if (!$mediaItem) {
            $mediaItem = new Media();
            if ($this->relType) {
                $mediaItem->rel_type = $this->relType;
            }
            if ($this->relId) {
                $mediaItem->rel_id = $this->relId;
            }
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

    }
    #[ExposedLivewireMethod]
    public function addMediaItemMultiple($data = [])
    {
        $urls = $data['urls'] ?? $data;
        if (!is_array($urls) || empty($urls)) {
            return;
        }

        foreach ($urls as $url) {
            $this->addMediaItemSingle($url);
        }

        $this->refreshMediaData();
        $this->reflectOnCanvas();

        $this->state($this->mediaIds);
    }
    #[ExposedLivewireMethod]
    public function addMediaItem($data = [])
    {
        $url = false;

        if(isset($data['url'])){
            $url = $data['url'];
        }



        if (!$url) {
            return;
        }

        if(is_array($url)) {

            foreach ($url as $u) {
                $this->addMediaItemSingle($u);
            }

            return;
        }

        $this->addMediaItemSingle($url);

        $this->refreshMediaData();
        $this->reflectOnCanvas();

        $this->state($this->mediaIds);
    }

    #[ExposedLivewireMethod]
    public function updateImageFilename($data = [])
    {
        $mediaId = $data['id'] ?? false;
        if (!$mediaId) {
            return;
        }
        $media = Media::where('id', $mediaId)->first();
        if (!$media) {
            return;
        }

        $filename = $data['filename'] ?? ($data['src'] ?? null);
        if ($filename) {
            $media->filename = $filename;
            $media->save();
            $this->refreshMediaData();
            $this->reflectOnCanvas();
        }
    }

    /**
     * Persist the per-image detail-panel fields (Caption / Alt text / Link) into
     * the Media `image_options` JSON — the SAME keys the Pictures skins read
     * (`caption`, `alt-text`, `link`, `title`). The legacy edit action only wrote
     * the `title`/`description` DB columns, which the frontend never reads, so
     * captions/alt/links silently never rendered. Only keys present in $data are
     * touched, so a blur on one field never wipes the others.
     */
    #[ExposedLivewireMethod]
    public function updateMediaItemMeta($data = [])
    {
        $id = is_array($data) ? ($data['id'] ?? false) : false;
        if (!$id) {
            return;
        }
        $media = Media::find($id);
        if (!$media) {
            return;
        }

        $opts = is_array($media->image_options) ? $media->image_options : [];

        if (array_key_exists('caption', $data)) {
            $opts['caption'] = (string) $data['caption'];
        }
        if (array_key_exists('altText', $data)) {
            $opts['alt-text'] = (string) $data['altText'];
        }
        if (array_key_exists('link', $data)) {
            $opts['link'] = (string) $data['link'];
        }
        if (array_key_exists('title', $data)) {
            $opts['title'] = (string) $data['title'];
        }
        // Thumbnail crop focal point: 'center' | 'top' | 'custom'. When custom,
        // an explicit object-position string (e.g. "40% 65%") rides in cropPosition.
        if (array_key_exists('crop', $data)) {
            $opts['crop'] = (string) $data['crop'];
        }
        if (array_key_exists('cropPosition', $data)) {
            $opts['crop-position'] = (string) $data['cropPosition'];
        }

        $media->image_options = $opts;
        $media->save();

        $this->refreshMediaData();
        $this->reflectOnCanvas();
    }

    /**
     * Re-render the module on the Live Edit canvas after a change here, so the
     * template reflects it (new/removed images, reorder, caption/alt/link/crop).
     * Reuses the module-settings layout's `mw-option-saved` listener, which
     * reloads `#<optionGroup>`; for a module-scoped gallery relId IS that
     * module's id. No-op outside the module context (e.g. use-from-post).
     */
    protected function reflectOnCanvas(): void
    {
        if ($this->relType === 'module' && !empty(trim((string) $this->relId))) {
            try {
                $this->getLivewire()->dispatch('mw-option-saved', optionGroup: $this->relId);
            } catch (\Throwable $e) {
                // best-effort canvas refresh
            }
        }
    }

    /**
     * "+ Generate" — turn a text prompt into a gallery image via the configured
     * AI image driver (replicate / fal), then attach the returned URL like any
     * other media item. Returns a {success, message?} payload the panel surfaces
     * (e.g. "driver not enabled" when no image driver is configured).
     */
    #[ExposedLivewireMethod]
    public function generateMediaItem($data = [])
    {
        $prompt = is_array($data) ? trim((string) ($data['prompt'] ?? '')) : trim((string) $data);
        if ($prompt === '') {
            return ['success' => false, 'message' => 'Describe the image you want to generate.'];
        }

        try {
            $response = \Modules\Ai\Facades\AiImages::generateImage(
                [['role' => 'user', 'content' => $prompt]],
                []
            );

            $url = null;
            if (is_string($response)) {
                $url = $response;
            } elseif (is_array($response)) {
                $url = $response['url'] ?? $response['image'] ?? ($response['data'] ?? null);
                if (is_array($url)) {
                    $url = $url['url'] ?? ($url[0] ?? null);
                }
            }

            if (!$url || !is_string($url)) {
                return ['success' => false, 'message' => 'Image generation did not return a URL.'];
            }

            $this->addMediaItemSingle($url);
            $this->refreshMediaData();
            $this->reflectOnCanvas();
            $this->state($this->mediaIds);

            return ['success' => true, 'url' => $url];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * "Write for me" — ask the AI chat driver for a concise alt text (seeded with
     * the caption + filename), persist it into image_options['alt-text'], and
     * return it so the panel field updates. Surfaces the driver error otherwise.
     */
    #[ExposedLivewireMethod]
    public function generateAltText($data = [])
    {
        $id = is_array($data) ? ($data['id'] ?? false) : $data;
        if (!$id) {
            return ['success' => false, 'message' => 'No image selected.'];
        }
        $media = Media::find($id);
        if (!$media) {
            return ['success' => false, 'message' => 'Image not found.'];
        }

        $opts = is_array($media->image_options) ? $media->image_options : [];
        $caption = $opts['caption'] ?? '';
        $path = parse_url((string) $media->filename, PHP_URL_PATH) ?: (string) $media->filename;
        $name = basename($path);

        $prompt = 'Write one concise, descriptive alt text (max 120 characters, plain text, '
            . "no surrounding quotes, do not start with \"image of\") for a website gallery image.";
        if ($caption !== '') {
            $prompt .= " Caption: \"{$caption}\".";
        }
        $prompt .= " Filename: \"{$name}\". Reply with only the alt text.";

        try {
            $response = \Modules\Ai\Facades\Ai::sendToChat(
                [['role' => 'user', 'content' => $prompt]],
                []
            );

            $text = is_string($response)
                ? $response
                : ($response['content'] ?? ($response['message'] ?? ($response['data'] ?? '')));
            $text = trim(trim((string) $text), "\"'");

            if ($text === '') {
                return ['success' => false, 'message' => 'The AI did not return any alt text.'];
            }

            $opts['alt-text'] = $text;
            $media->image_options = $opts;
            $media->save();
            $this->refreshMediaData();

            return ['success' => true, 'altText' => $text];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Site pages for the Link → "Page" picker dropdown: [{title, url}, …].
     */
    #[ExposedLivewireMethod]
    public function getSitePages()
    {
        $pages = \Modules\Content\Models\Content::query()
            ->where('content_type', 'page')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('position', 'asc')
            ->orderBy('title', 'asc')
            ->limit(300)
            ->get(['id', 'title', 'url']);

        return $pages->map(function ($p) {
            $url = $p->url;
            if (!$url) {
                $url = site_url();
            } elseif (!preg_match('#^https?://#i', $url)) {
                $url = site_url($url);
            }

            return [
                'title' => $p->title ?: ('Page #' . $p->id),
                'url' => $url,
            ];
        })->values()->toArray();
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                Media::where('id', $arguments['id'])->delete();
            });
    }

    #[ExposedLivewireMethod]
    public function deleteMediaItemsByIds($ids = false)
    {
        $mediaId = $ids;
        if (!$mediaId) {
            return;
        }
        Media::whereIn('id', $mediaId)->delete();

        $this->refreshMediaData();
        $this->reflectOnCanvas();
    }


    public function getQueryBuilder()
    {

        $record = $this->getRecord();
        if ($record) {
            $this->relType = morph_name($record->getMorphClass());
            $this->relId = $record->id;
        } else {
            $this->createdBy = user_id();
        }

        $itemsQuery = Media::query();
        if (empty(trim($this->relId ?? ''))) {
            if (!empty(trim($this->sessionId ?? ''))) {
                $itemsQuery->where('session_id', $this->sessionId);
            } else if ($this->createdBy) {
                $itemsQuery->where('created_by', $this->createdBy);
            }
        }
        if (!empty(trim($this->relId ?? ''))) {
            $itemsQuery->where('rel_id', $this->relId);
        } else {
            $itemsQuery->whereNull('rel_id');
        }

        if (!empty(trim($this->relType ?? ''))) {
            $itemsQuery->where('rel_type', $this->relType);
        }

        $itemsQuery->orderBy('position', 'asc');

        return $itemsQuery;
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

    public function getMediaItemsArray()
    {
        $this->refreshMediaData();

        // Enrich each row with the per-image detail-panel fields (read from the
        // image_options JSON the skins use) plus best-effort intrinsic
        // dimensions / file size, so the blade can render the right-hand panel
        // and the "1920 × 1280 · 412 KB" line without a per-select round-trip.
        foreach ($this->mediaItems as $item) {
            $opts = is_array($item->image_options) ? $item->image_options : [];
            $item->mw_caption = $opts['caption'] ?? '';
            $item->mw_alt = $opts['alt-text'] ?? '';
            $item->mw_link = $opts['link'] ?? '';
            $item->mw_crop = $opts['crop'] ?? 'center';
            $item->mw_crop_position = $opts['crop-position'] ?? '';

            $meta = $this->mediaFileMeta($item->filename);
            $item->mw_w = $meta['w'];
            $item->mw_h = $meta['h'];
            $item->mw_size = $meta['size'];
        }

        return $this->mediaItems;
    }

    /**
     * Resolve a stored media filename (usually an absolute site URL) back to a
     * local public path, or null when it isn't a local file (external URL /
     * missing). Used only for best-effort intrinsic dimensions + size.
     */
    protected function resolveLocalPath($filename)
    {
        if (!$filename || !is_string($filename)) {
            return null;
        }

        $filename = preg_replace('/[?#].*$/', '', $filename);

        foreach ([function_exists('site_url') ? site_url() : null, rtrim((string) config('app.url'), '/') . '/'] as $base) {
            if ($base && str_starts_with($filename, $base)) {
                $filename = ltrim(substr($filename, strlen($base)), '/');
                break;
            }
        }

        if (preg_match('#^https?://#i', $filename)) {
            return null; // still an external URL
        }

        $path = public_path(ltrim($filename, '/'));

        return is_file($path) ? $path : null;
    }

    public function mediaFileMeta($filename)
    {
        $out = ['w' => null, 'h' => null, 'size' => null];

        $path = $this->resolveLocalPath($filename);
        if ($path) {
            $info = @getimagesize($path);
            if ($info) {
                $out['w'] = $info[0] ?? null;
                $out['h'] = $info[1] ?? null;
            }
            $bytes = @filesize($path);
            if ($bytes !== false) {
                $out['size'] = $bytes;
            }
        }

        return $out;
    }


    /**
     * Whether the "+ Generate" button should render — only when a working AI
     * image driver (replicate / fal) is configured and enabled. Fails closed.
     */
    public function isGenerateAvailable(): bool
    {
        try {
            return (bool) \Modules\Ai\Facades\AiImages::isImageGenerationAvailable();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function setRelType($relType)
    {
        $this->relType = $relType;
        return $this;
    }
    public function setRelId($relId)
    {
        $this->relId = $relId;
        return $this;
    }

}
