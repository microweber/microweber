<?php

namespace Modules\Pictures\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;
use Modules\Pictures\Filament\PicturesModuleSettings;

class PicturesModule extends BaseModule
{
    /**
     * Module configuration
     */
    public static string $name = 'Pictures Module';
    public static string $module = 'pictures';
    public static string $icon = 'modules.pictures-icon';
    public static string $categories = 'media';
    public static int $position = 30;
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

        if (empty($pictures)) {
            $pictures = $this->getDefaultPictures($relationData['type'], $relationData['id']);
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
        $relType = $params['rel'] ?? $params['data-rel'] ?? 'module';


        $relId = $this->getModuleId();
        $contentId = content_id();

        if ($this->shouldUseContentRelation($params, $contentId)) {
            $relType = morph_name(Content::class);
            $relId = $params['content_id'] ?? $contentId;
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

        if ($this->getOption('data-use-from-post') === 'y' && $contentId) {
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
        return Media::query()
            ->where('rel_type', $relationData['type'])
            ->where('rel_id', $relationData['id'])
            ->orderBy('position', 'asc')
            ->get()
            ->toArray();
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
     * @param  string  $relType
     * @param $relId
     *
     * @return array
     */
    private function getDefaultPictures(string $relType, $relId): array
    {
        $defaults = [];
        for ($i = 1; $i <= 3; $i++) {
            $defaults[] = new Media([
                'id' => $i,
                'filename' => asset("modules/pictures/default-images/gallery-1-{$i}.jpg"),
                'media_type' => 'picture',
                'rel_type' => $relType,
                'rel_id' => $relId,
                'position' => $i - 1,
            ]);
        }
        return $defaults;
    }
}
