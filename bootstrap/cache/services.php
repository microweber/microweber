<?php return array (
  'providers' => 
  array (
    0 => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    1 => 'Barryvdh\\Debugbar\\ServiceProvider',
    2 => 'Barryvdh\\DomPDF\\ServiceProvider',
    3 => 'Facade\\Ignition\\IgnitionServiceProvider',
    4 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    5 => 'Fruitcake\\Cors\\CorsServiceProvider',
    6 => 'GrahamCampbell\\Markdown\\MarkdownServiceProvider',
    7 => 'Jenssegers\\Agent\\AgentServiceProvider',
    8 => 'Laravel\\Socialite\\SocialiteServiceProvider',
    9 => 'Collective\\Html\\HtmlServiceProvider',
    10 => 'Carbon\\Laravel\\ServiceProvider',
    11 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    12 => 'Conner\\Tagging\\Providers\\TaggingServiceProvider',
    13 => 'Spatie\\Permission\\PermissionServiceProvider',
    14 => 'Microweber\\App\\Providers\\AppServiceProvider',
    15 => 'Microweber\\App\\Providers\\EventServiceProvider',
    16 => 'Microweber\\App\\Providers\\RouteServiceProvider',
    17 => 'Microweber\\MicroweberServiceProvider',
  ),
  'eager' => 
  array (
    0 => 'Barryvdh\\Debugbar\\ServiceProvider',
    1 => 'Barryvdh\\DomPDF\\ServiceProvider',
    2 => 'Facade\\Ignition\\IgnitionServiceProvider',
    3 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    4 => 'Fruitcake\\Cors\\CorsServiceProvider',
    5 => 'GrahamCampbell\\Markdown\\MarkdownServiceProvider',
    6 => 'Jenssegers\\Agent\\AgentServiceProvider',
    7 => 'Carbon\\Laravel\\ServiceProvider',
    8 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    9 => 'Conner\\Tagging\\Providers\\TaggingServiceProvider',
    10 => 'Spatie\\Permission\\PermissionServiceProvider',
    11 => 'Microweber\\App\\Providers\\AppServiceProvider',
    12 => 'Microweber\\App\\Providers\\EventServiceProvider',
    13 => 'Microweber\\App\\Providers\\RouteServiceProvider',
    14 => 'Microweber\\MicroweberServiceProvider',
  ),
  'deferred' => 
  array (
    'Arcanedev\\SeoHelper\\Contracts\\SeoHelper' => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    'Arcanedev\\SeoHelper\\Contracts\\SeoMeta' => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    'Arcanedev\\SeoHelper\\Contracts\\SeoOpenGraph' => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    'Arcanedev\\SeoHelper\\Contracts\\SeoTwitter' => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    'Laravel\\Socialite\\Contracts\\Factory' => 'Laravel\\Socialite\\SocialiteServiceProvider',
    'html' => 'Collective\\Html\\HtmlServiceProvider',
    'form' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\HtmlBuilder' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\FormBuilder' => 'Collective\\Html\\HtmlServiceProvider',
  ),
  'when' => 
  array (
    'Arcanedev\\SeoHelper\\SeoHelperServiceProvider' => 
    array (
    ),
    'Laravel\\Socialite\\SocialiteServiceProvider' => 
    array (
    ),
    'Collective\\Html\\HtmlServiceProvider' => 
    array (
    ),
  ),
);