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
                    if ($localeSettingsOld['locale'] != $localeSettingsNew['locale']) {
                        $link = str_replace($segment, $localeSettingsNew['locale'], $link);
                        return redirect($link);
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
