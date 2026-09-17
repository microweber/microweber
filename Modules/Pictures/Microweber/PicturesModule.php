<?php

namespace Modules\Pictures\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;
use Modules\Pictures\Filament\PicturesModuleSettings;

class PicturesModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Pictures';
    public static string $module = 'pictures';
    public static string $icon = 'modules.pictures-icon';
    public static string $categories = 'media';
    public static int $position = 3;
    public static string $settingsComponent = PicturesModuleSettings::class;
    public static string $templatesNamespace = 'modules.pictures::templates';

    /**
     * Render the pictures module
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $viewData = $this->prepareViewData();
        $relationData = $this->determineRelationData();

        $pictures = $this->fetchPictures($relationData);
        $defaultPicturesCreated = $this->getOption('default_pictures_created', 'n') === 'y';
        if (!$defaultPicturesCreated and empty($pictures)) {
            $this->saveOption('default_pictures_created', 'y');
            $pictures = $this->makeDefaultPictures($relationData['type'], $relationData['id']);
        }
        if (!$defaultPicturesCreated) {
            $this->saveOption('default_pictures_created', 'y');
        }

        return $this->buildView($viewData, $pictures);
    }

    /**
     * Prepare initial view data
     *
     * @return array
     */
    private function prepareViewData(): array
    {
        $viewData = $this->getViewData();
        $viewData['template'] = $viewData['template'] ?? 'default';

        return $viewData;
    }

    /**
     * Determine relation type and ID
     *
     * @return array
     */
    private function determineRelationData(): array
    {
        $params = $this->getParams();
        $relType = $params['rel'] ?? $params['data-rel'] ?? $params['rel-type'] ?? $params['rel_type'] ?? 'module';
        $relId = $params['rel-id'] ?? $params['data-rel-id'] ?? $params['rel_id'] ?? false;
        $moduleId = $params['module_id'] ?? $params['data-module-id'] ?? $params['module_id'] ?? $this->getModuleId();


        if ($relType == 'content') {
            $relType = morph_name(Content::class);
            if (!$relId) {
                $relId = content_id();
            }

        } else {
            if (!$relId) {
                $relId = $moduleId;
            }
        }


        return [
            'type' => $relType,
            'id' => $relId
        ];
    }

    /**
     * Check if content relation should be used
     *
     * @param array $params
     * @param int|null $contentId
     * @return bool
     */
    private function shouldUseContentRelation(array $params, ?int $contentId): bool
    {
        if (isset($params['rel']) && $params['rel'] === 'content') {
            return true;
        }


        if (isset($params['content_id'])) {
            return true;
        }

        if ($this->getOption('data-use-from-post') == 1 && $contentId) {
            return true;
        }

        return false;
    }

    /**
     * Fetch pictures from database
     *
     * @param array $relationData
     * @return array
     */
    private function fetchPictures(array $relationData): array
    {
        $pictures = Media::query()
            ->where('rel_type', $relationData['type'])
            ->where('rel_id', $relationData['id'])
            ->orderBy('position', 'asc')
            ->get()
            ->toArray();

        return self::withCropPositions($pictures);
    }

    /**
     * task-2026-09-17 — Thumbnail crop, honored across every skin.
     *
     * The media browser stores a per-image crop focal point in
     * image_options: crop = center | top | custom (+ crop-position "x% y%"
     * for custom). Precompute a single CSS position string per item —
     * `crop_position` — so each skin can drop it straight into an <img>'s
     * `object-position` (cover images) or a holder's `background-position`
     * without re-deriving the logic. Empty string = center (the CSS default),
     * so a skin can skip emitting the property entirely.
     *
     * @param array $pictures
     * @return array
     */
    public static function withCropPositions(array $pictures): array
    {
        foreach ($pictures as &$item) {
            if (!is_array($item)) {
                continue;
            }
            $opts = $item['image_options'] ?? [];
            if (!is_array($opts)) {
                $opts = [];
            }
            $crop = $opts['crop'] ?? 'center';
            $cropPos = trim((string) ($opts['crop-position'] ?? ''));

            if ($crop === 'top') {
                $item['crop_position'] = 'center top';
            } elseif ($crop === 'custom' && $cropPos !== '') {
                $item['crop_position'] = $cropPos;
            } else {
                $item['crop_position'] = '';
            }
        }
        unset($item);

        return $pictures;
    }

    /**
     * Build and return the final view
     *
     * @param array $viewData
     * @param array $pictures
     * @return \Illuminate\Contracts\View\View
     */
    private function buildView(array $viewData, array $pictures)
    {
        $viewName = $this->getViewName($viewData['template']);
        $viewData['data'] = $pictures;
        $viewData['no_img'] = empty($pictures);

        return view($viewName, $viewData);
    }

    /**
     * Build default pictures for the module
     *
     * @param string $relType
     * @param $relId
     *
     * @return array
     */
    private function makeDefaultPictures(string $relType, $relId): array
    {
        $defaults = [];
        for ($i = 1; $i <= 3; $i++) {
            $media = new Media([

                'filename' => asset("modules/pictures/default-images/gallery-1-{$i}.jpg"),
                'media_type' => 'picture',
                'rel_type' => $relType,
                'rel_id' => $relId,
                'position' => $i - 1,
            ]);
            $media->save();

            $defaults[] = $media;
        }
        return $defaults;
    }
}
