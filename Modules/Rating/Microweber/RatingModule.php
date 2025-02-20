<?php

namespace Modules\Rating\Microweber;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Rating\Filament\RatingModuleSettings;
use Modules\Rating\Models\Rating;

class RatingModule extends BaseModule
{
    public static string $name = 'Rating';
    public static string $module = 'rating';
    public static string $icon = 'modules.rating-icon';
    public static string $categories = 'content';
    public static int $position = 100;
    public static string $settingsComponent = RatingModuleSettings::class;
    public static string $templatesNamespace = 'modules.rating::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $require_comment = false;
        //  $rel_type = morph_name(\Modules\Content\Models\Content::class);
        $rel_type = 'module';
        //  $rel_id = content_id();
        $rel_id = $this->params['id'];

        if (isset($this->params['content_id'])) {
            $rel_id = $this->params['content_id'];
            $rel_type = morph_name(\Modules\Content\Models\Content::class);
        } elseif (isset($this->params['content-id'])) {
            $rel_id = $this->params['content-id'];
            $rel_type = morph_name(\Modules\Content\Models\Content::class);
        } else if (isset($this->params['rel_id'])) {
            $rel_id = $this->params['rel_id'];
        } elseif (isset($this->params['rel-id'])) {
            $rel_id = $this->params['rel-id'];
        }

        if (isset($this->params['rel_type'])) {
            $rel_type = $this->params['rel_type'];
        } elseif (isset($this->params['rel-type'])) {
            $rel_type = $this->params['rel-type'];
        }

        if (isset($this->params['rel_type']) && $this->params['rel_type'] == 'content') {
            $rel_type = morph_name(\Modules\Content\Models\Content::class);
        }

        if (isset($this->params['comment'])) {
            $require_comment = $this->params['comment'];
        }

        if ($rel_id == false) {
            return;
        }

        $rating = 0;
        $rel_id = trim($rel_id);

        $cache_key = md5($rel_type . $rel_id . 'avg');
        $rating_cache = Cache::tags('rating')->get($cache_key);

        if ($rating_cache == NULL) {
            $rating = Rating::where('rel_type', $rel_type)
                ->where('rel_id', $rel_id)
                ->avg('rating');
            if ($rating == NULL) {
                $rating = '_';
            }
            Cache::tags('rating')->put($cache_key, $rating);
        } else {
            $rating = $rating_cache;
        }

        $rating = (int)$rating;

        $template = isset($viewData['template']) ? $viewData['template'] : 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        $viewData['rating'] = $rating;
        $viewData['rel_type'] = $rel_type;
        $viewData['rel_id'] = $rel_id;
        $viewData['require_comment'] = $require_comment;

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
