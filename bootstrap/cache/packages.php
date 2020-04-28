<?php return array (
  'arcanedev/seo-helper' => 
  array (
    'providers' => 
    array (
      0 => 'Arcanedev\\SeoHelper\\SeoHelperServiceProvider',
    ),
  ),
  'barryvdh/laravel-debugbar' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\Debugbar\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'Debugbar' => 'Barryvdh\\Debugbar\\Facade',
    ),
  ),
  'facade/ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Facade\\Ignition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Facade\\Ignition\\Facades\\Flare',
    ),
  ),
  'fideloper/proxy' => 
  array (
    'providers' => 
    array (
      0 => 'Fideloper\\Proxy\\TrustedProxyServiceProvider',
    ),
  ),
  'fruitcake/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Fruitcake\\Cors\\CorsServiceProvider',
    ),
  ),
  'graham-campbell/markdown' => 
  array (
    'providers' => 
    array (
      0 => 'GrahamCampbell\\Markdown\\MarkdownServiceProvider',
    ),
  ),
  'jenssegers/agent' => 
  array (
    'providers' => 
    array (
      0 => 'Jenssegers\\Agent\\AgentServiceProvider',
    ),
    'aliases' => 
    array (
      'Agent' => 'Jenssegers\\Agent\\Facades\\Agent',
    ),
  ),
  'laravel/socialite' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Socialite\\SocialiteServiceProvider',
    ),
    'aliases' => 
    array (
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
    ),
  ),
  'laravelcollective/html' => 
  array (
    'providers' => 
    array (
      0 => 'Collective\\Html\\HtmlServiceProvider',
    ),
    'aliases' => 
    array (
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
    ),
  ),
  'microweber-packages/laravel-config-save' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\Config\\ConfigSaveServiceProvider',
    ),
    'aliases' => 
    array (
      'ConfigSave' => 'MicroweberPackages\\Config\\ConfigSaveFacade',
    ),
  ),
  'microweber-packages/microweber-captcha-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\CaptchaManager\\CaptchaManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'OptionManager' => 'MicroweberPackages\\CaptchaManager\\CaptchaManagerFacade',
    ),
  ),
  'microweber-packages/microweber-cms' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\ContentManager\\ContentManagerServiceProvider',
      1 => 'MicroweberPackages\\CategoryManager\\CategoryManagerServiceProvider',
      2 => 'MicroweberPackages\\TagsManager\\TagsManagerServiceProvider',
      3 => 'MicroweberPackages\\MediaManager\\MediaManagerServiceProvider',
      4 => 'MicroweberPackages\\MenuManager\\MenuManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'ContentManager' => 'MicroweberPackages\\ContentManager\\ContentManagerFacade',
      'CategoryManager' => 'MicroweberPackages\\CategoryManager\\CategoryManagerFacade',
      'MediaManager' => 'MicroweberPackages\\MediaManager\\MediaManagerFacade',
      'MenuManager' => 'MicroweberPackages\\MenuManager\\MenuManagerFacade',
      'TagsManager' => 'MicroweberPackages\\TagsManager\\TagsManagerFacade',
    ),
  ),
  'microweber-packages/microweber-database-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\DatabaseManager\\DatabaseManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'DatabaseManager' => 'MicroweberPackages\\DatabaseManager\\DatabaseManagerFacade',
    ),
  ),
  'microweber-packages/microweber-event-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\EventManager\\EventManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'EventManager' => 'MicroweberPackages\\EventManager\\EventManagerFacade',
    ),
  ),
  'microweber-packages/microweber-forms-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\FormsManager\\FormsManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'FormsManager' => 'MicroweberPackages\\FormsManager\\FormsManagerFacade',
    ),
  ),
  'microweber-packages/microweber-helpers' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\Helpers\\HelpersServiceProvider',
    ),
    'aliases' => 
    array (
      'Helpers' => 'MicroweberPackages\\Helpers\\HelpersFacade',
    ),
  ),
  'microweber-packages/microweber-option-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\OptionManager\\OptionManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'OptionManager' => 'MicroweberPackages\\OptionManager\\OptionManagerFacade',
    ),
  ),
  'microweber-packages/microweber-shop' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\CartManager\\CartManagerServiceProvider',
      1 => 'MicroweberPackages\\CheckoutManager\\CheckoutManagerServiceProvider',
      2 => 'MicroweberPackages\\ClientsManager\\ClientsManagerServiceProvider',
      3 => 'MicroweberPackages\\InvoicesManager\\InvoicesManagerServiceProvider',
      4 => 'MicroweberPackages\\OrderManager\\OrderManagerServiceProvider',
      5 => 'MicroweberPackages\\ShopManager\\ShopManagerServiceProvider',
      6 => 'MicroweberPackages\\TaxManager\\TaxManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'CatManager' => 'MicroweberPackages\\CatManager\\CartManagerFacade',
      'CheckoutManager' => 'MicroweberPackages\\CheckoutManager\\CheckoutManagerFacade',
      'ClientsManager' => 'MicroweberPackages\\ClientsManager\\ClientsManagerFacade',
      'InvoicesManager' => 'MicroweberPackages\\InvoicesManager\\InvoicesManagerFacade',
      'OrderManager' => 'MicroweberPackages\\OrderManager\\OrderManagerFacade',
      'ShopManager' => 'MicroweberPackages\\ShopManager\\ShopManagerFacade',
      'TaxManager' => 'MicroweberPackages\\TaxManager\\TaxManagerFacade',
    ),
  ),
  'microweber-packages/microweber-template-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\TemplateManager\\TemplateManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'OptionManager' => 'MicroweberPackages\\TemplateManager\\TemplateManagerFacade',
    ),
  ),
  'microweber-packages/microweber-user-manager' => 
  array (
    'providers' => 
    array (
      0 => 'MicroweberPackages\\UserManager\\UserManagerServiceProvider',
    ),
    'aliases' => 
    array (
      'OptionManager' => 'MicroweberPackages\\UserManager\\UserManagerFacade',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'rtconner/laravel-tagging' => 
  array (
    'providers' => 
    array (
      0 => 'Conner\\Tagging\\Providers\\TaggingServiceProvider',
    ),
  ),
);