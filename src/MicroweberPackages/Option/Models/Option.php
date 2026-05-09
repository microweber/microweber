<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Option\Events\OptionIsCreating;
use MicroweberPackages\Option\Events\OptionIsUpdating;
use MicroweberPackages\Option\Events\OptionWasCreated;
use MicroweberPackages\Option\Events\OptionWasDeleted;
use MicroweberPackages\Option\Events\OptionWasRetrieved;
use MicroweberPackages\Option\Events\OptionWasUpdated;
use MicroweberPackages\Repository\Traits\FilterableByParams;

class Option extends Model
{
    /*
     * AI-108 / TICKET-BG (cycle-133 2026-05-09): expanded $fillable so the
     * Option Eloquent model is the canonical write path (it was previously
     * limited to option_group/option_value, which is why production
     * controllers and installers fell back to raw DB::table('options')
     * queries to avoid silently dropping option_key/module). The Settings
     * REST API and the install-time DefaultOptionsInstaller now route
     * through the model so OptionWasCreated/Updated/Deleted events fire,
     * which fixes a stale-cache bug: TemplateClearCachedCssListener was
     * never invoked when settings were changed via /api/module/settings.
     */
    protected $fillable = ['option_key', 'option_group', 'option_value', 'module', 'is_system'];
    public $cacheTagsToClear = ['global','content','frontend'];

    use CacheableQueryBuilderTrait;
    use HasSearchableTrait;
    use FilterableByParams;

    protected $dispatchesEvents = [
        'retrieved' =>  OptionWasRetrieved::class,
        'creating'   => OptionIsCreating::class,
        'created'   => OptionWasCreated::class,
        'updating'   => OptionIsUpdating::class,
        'updated'   => OptionWasUpdated::class,
        'saved'   => OptionWasUpdated::class,
        'deleted'   => OptionWasDeleted::class,
    ];


    protected $casts = [
        'option_value' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

    protected $searchable = [
        'id',
        'option_group',
        'option_value',
        'module',
        'is_system',
    ];

    public function getValue($key, $group = false)
    {
        if ($group) {
            $value = get_option($key, $group);
        } else {
            $value = get_option($key);
        }

        switch ($value) {
            case "y":
            case "yes":
                $value = true;
                break;
            case "n":
            case "no":
                $value = false;
                break;
        }

        if (is_numeric($value)) {
            $value = intval($value);
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        return $value;
    }

    public function setValue($key, $value, $group = false)
    {
        $saveOption = [];
        $saveOption['option_key'] = $key;
        $saveOption['option_value'] = $value;

        if ($group) {
            $saveOption['option_group'] = $group;
        }

        return save_option($saveOption);
    }

    public static function fetchFromCollection($optionsCollection, $key)
    {
        if(empty($optionsCollection) || empty($key)) {
            return null;
        }

        return $optionsCollection->where('option_key', $key)->pluck('option_value')->first();
    }

    /**
     * Get all distinct option groups from the database.
     */
    public static function queryAllExistingOptionGroups(): array
    {
        if (!Schema::hasTable('options')) {
            return [];
        }

        // AI-108 / TICKET-BG (cycle-133 2026-05-09): this helper is invoked
        // during early app boot via OptionRepository, before Eloquent's
        // connection resolver is ready. self::query() throws "Call to
        // a member function connection() on null" at that stage. The
        // raw DB::table query bypasses that bootstrap dependency.
        // Production WRITE paths (Settings API, installers) all migrated
        // to the Option model — see SettingsApiController docstring.
        $groups = DB::table('options')
            ->select('option_group')
            ->whereNotNull('option_group')
            ->groupBy('option_group')
            ->get();

        $groups = collect($groups)->map(fn($option) => (array) $option)->toArray();

        if ($groups && is_array($groups)) {
            return array_flatten($groups);
        }

        return [];
    }

    /**
     * Get all options for a given option group.
     */
    public static function queryOptionsByGroup(string $optionGroup): array
    {
        // AI-108 / TICKET-BG (cycle-133 2026-05-09): same early-boot
        // constraint as queryAllExistingOptionGroups — must remain a raw
        // DB::table query because OptionRepository invokes this helper
        // during early boot before Eloquent's connection resolver is ready.
        $allOptions = DB::table('options')
            ->where('option_group', $optionGroup)
            ->whereNotNull('option_value')
            ->get();

        if ($allOptions === null) {
            return [];
        }

        $allOptions = collect($allOptions)->map(fn($option) => (array) $option)->toArray();

        $allOptions = app()->url_manager->replace_site_url_back($allOptions);

        if ($allOptions === null) {
            return [];
        }

        return $allOptions;
    }

    /**
     * Get website options with defaults.
     */
    public static function queryWebsiteOptions(array $groupOptions): array
    {
        $websiteOptions = [
            'favicon_image' => '',
            'website_footer' => '',
            'website_head' => '',
            'website_title' => '',
            'website_keywords' => '',
            'website_description' => '',
            'date_format' => '',
            'enable_full_page_cache' => '',
            'google-site-verification-code' => '',
            'bing-site-verification-code' => '',
            'alexa-site-verification-code' => '',
            'pinterest-site-verification-code' => '',
            'yandex-site-verification-code' => '',
            'google-analytics-id' => '',
            'google-tag-manager-id' => '',
            'google-tag-manager-enable-events' => '',
            'facebook-pixel-id' => '',
            'robots_txt' => '',
            'app_version' => '',
            'maintenance_mode' => '',
            'maintenance_mode_text' => '',
        ];

        if (!empty($groupOptions)) {
            foreach ($groupOptions as $websiteOption) {
                $websiteOption = app()->url_manager->replace_site_url_back($websiteOption);
                $websiteOptions[$websiteOption['option_key']] = $websiteOption['option_value'];
            }
        }

        return $websiteOptions;
    }

}
