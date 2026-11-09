<?php

namespace MicroweberPackages\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GrahamCampbell\SecurityCore\Security;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class LocaleRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $localeRedirect = request()->get('localeRedirect');


        if ($localeRedirect and mw_is_installed() and MultilanguageHelpers::multilanguageIsEnabled()) {




            $localeSettingsNew = app()->multilanguage_repository->getSupportedLocale($localeRedirect);



            if (isset($localeSettingsNew['locale'])
                and $localeSettingsNew['locale']
                and $localeRedirect != app()->getLocale()
            ) {
                $request = request();

                $segment = $request->segment(1);
                $link = $request->fullUrlWithoutQuery(['localeRedirect', 'locale']);

                if ($segment) {
                    $localeSettingsOld = app()->multilanguage_repository->getSupportedLocale($segment);
                    // getSupportedLocale() returns FALSE when the first URL segment
                    // is not a locale — i.e. a normal un-prefixed URL like /shop,
                    // /about, etc. (only locale-prefixed URLs like /en_US/shop have
                    // a locale as segment 1). Guard before dereferencing it, or
                    // switching language on any inner page 500s ("Trying to access
                    // array offset on false").
                    $segmentIsLocale = is_array($localeSettingsOld)
                        && isset($localeSettingsOld['locale'])
                        && $localeSettingsOld['locale'];

                    if ($segmentIsLocale) {
                        // Locale-prefixed URL — swap the prefix for the new locale.
                        if ($localeSettingsOld['locale'] != $localeSettingsNew['locale']) {
                            $link = str_replace($segment, $localeSettingsNew['locale'], $link);
                            return redirect($link);
                        }
                    } else {
                        // Un-prefixed inner page — just switch the active language,
                        // same as the homepage case below.
                        change_language_by_locale($localeSettingsNew['locale'], true);
                    }
                } else {
                    // maybe we are on homepage

                    change_language_by_locale($localeSettingsNew['locale'],true);

                }
            }

        }


        return $next($request);

    }


}
