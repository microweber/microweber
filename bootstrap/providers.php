<?php
//$this->register(new ViewServiceProvider($this));
//        $this->register(new SessionServiceProvider($this));
//        $this->register(new FilesystemServiceProvider($this));
//        $this->register(new TaggableFileCacheServiceProvider($this));

use MicroweberPackages\Template\TemplateManagerServiceProvider;

return [

    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Notifications\NotificationServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
      Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,


    //  \MicroweberPackages\View\ViewServiceProvider::class,
    //  \Illuminate\Filesystem\FilesystemServiceProvider::class,
    //\Illuminate\Cache\CacheServiceProvider::class,

    \MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class,

    // \MicroweberPackages\Translation\Providers\TranslationServiceProvider::class,
    \MicroweberPackages\Cache\TaggableFileCacheServiceProvider::class,

    //  \MicroweberPackages\Module\ModuleServiceProvider::class,


    \MicroweberPackages\Database\DatabaseManagerServiceProvider::class,


    \MicroweberPackages\Multilanguage\MultilanguageServiceProvider::class,
    \MicroweberPackages\Event\EventManagerServiceProvider::class,
    \MicroweberPackages\Option\Providers\OptionServiceProvider::class,
    TemplateManagerServiceProvider::class,

    // \MicroweberPackages\Helper\HelpersServiceProvider::class,

    \MicroweberPackages\Microweber\Providers\MicroweberServiceProvider::class,
    \MicroweberPackages\App\Providers\AppServiceProvider::class,
    //App\Providers\AppServiceProvider::class,
    //\App\Providers\Filament\AdminPanelProvider::class,
];
