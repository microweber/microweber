<?php
/**
 * @author Bobi microweber
 */
namespace MicroweberPackages\Multilanguage\Http\Middleware;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MultilanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isLocaleChangedFromLink = false;
        //  Change language if user request language with LINK has lang abr
        if (MultilanguageHelpers::multilanguageIsEnabled()) {
            $currentUri = $request->path();
            $linkSegments = url_segment(-1, $currentUri);
            $linkSegments = array_filter($linkSegments, 'trim');
            if (!empty($linkSegments)) {
                if (isset($linkSegments[0]) and $linkSegments[0]) {
                    $localeSettings = app()->multilanguage_repository->getSupportedLocaleByDisplayLocale($linkSegments[0]);
                    if (!$localeSettings) {
                        $localeSettings = app()->multilanguage_repository->getSupportedLocaleByLocale($linkSegments[0]);
                    }
                    if ($localeSettings and isset($localeSettings['locale'])) {
                        $isLocaleChangedFromLink = true;
                        change_language_by_locale($localeSettings['locale'], true);
                    }
                }
            }
        }

        //  dd($_COOKIE, $_REQUEST);

        // If locale is not changed from link
        if (!$isLocaleChangedFromLink) {
            // If we have a lang cookie read from theere
            if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang'])) {
                $setCurrentLangTo = $_COOKIE['lang'];
            } else {
                if (MultilanguageHelpers::multilanguageIsEnabled()) {
                    // Set from default homepage lang settings
                    $setCurrentLangTo = get_option('homepage_language', 'website');
                } else {
                    // Set from default language language settings
                    $setCurrentLangTo = get_option('language', 'website');
                }
            }
            if ($setCurrentLangTo && is_lang_correct($setCurrentLangTo)) {
                set_current_lang($setCurrentLangTo);
            }
        }

        return $next($request);
    }
}
