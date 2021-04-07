<?php

namespace MicroweberPackages\App;


use MicroweberPackages\App\Providers\AppServiceProvider;
use MicroweberPackages\App\Providers\EventServiceProvider;
use MicroweberPackages\App\Providers\RouteServiceProvider;

class MicroweberConfig
{

    static function providers()
    {
        return [
            \Illuminate\Session\SessionServiceProvider::class,
            \Illuminate\Filesystem\FilesystemServiceProvider::class,
            \Illuminate\Auth\AuthServiceProvider::class,
            \Illuminate\Broadcasting\BroadcastServiceProvider::class,
            \Illuminate\Bus\BusServiceProvider::class,
            \Illuminate\Cache\CacheServiceProvider::class,
            \Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
            \Illuminate\Cookie\CookieServiceProvider::class,
            \Illuminate\Database\DatabaseServiceProvider::class,
            \Illuminate\Encryption\EncryptionServiceProvider::class,
            \Illuminate\Foundation\Providers\FoundationServiceProvider::class,
            \Illuminate\Hashing\HashServiceProvider::class,
            \Illuminate\Mail\MailServiceProvider::class,
            \Illuminate\Notifications\NotificationServiceProvider::class,
            \MicroweberPackages\Pagination\PaginationServiceProvider::class,
            \Illuminate\Pipeline\PipelineServiceProvider::class,
            \Illuminate\Queue\QueueServiceProvider::class,
            \Illuminate\Redis\RedisServiceProvider::class,
            \Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
            \Illuminate\Translation\TranslationServiceProvider::class,
            \Illuminate\Validation\ValidationServiceProvider::class,
            \Illuminate\View\ViewServiceProvider::class,

            // Our providers
            AppServiceProvider::class,
            EventServiceProvider::class,
            RouteServiceProvider::class
        ];
    }

    static function aliases()
    {
        return [
            'App' => \Illuminate\Support\Facades\App::class,
            'Arr' => \Illuminate\Support\Arr::class,
            'Artisan' => \Illuminate\Support\Facades\Artisan::class,
            'Auth' => \Illuminate\Support\Facades\Auth::class,
            'Blade' => \Illuminate\Support\Facades\Blade::class,
            'Broadcast' => \Illuminate\Support\Facades\Broadcast::class,
            'Bus' => \Illuminate\Support\Facades\Bus::class,
            'Cache' => \Illuminate\Support\Facades\Cache::class,
            'Config' => \Illuminate\Support\Facades\Config::class,
            'Cookie' => \Illuminate\Support\Facades\Cookie::class,
            'Crypt' => \Illuminate\Support\Facades\Crypt::class,
            'DB' => \Illuminate\Support\Facades\DB::class,
            'Eloquent' => \Illuminate\Database\Eloquent\Model::class,
            'Event' => \Illuminate\Support\Facades\Event::class,
            'File' => \Illuminate\Support\Facades\File::class,
            'Gate' => \Illuminate\Support\Facades\Gate::class,
            'Hash' => \Illuminate\Support\Facades\Hash::class,
            'Http' => \Illuminate\Support\Facades\Http::class,
            'Lang' => \Illuminate\Support\Facades\Lang::class,
            'Log' => \Illuminate\Support\Facades\Log::class,
            'Mail' => \Illuminate\Support\Facades\Mail::class,
            'Notification' => \Illuminate\Support\Facades\Notification::class,
            'Password' => \Illuminate\Support\Facades\Password::class,
            'Queue' => \Illuminate\Support\Facades\Queue::class,
            'Redirect' => \Illuminate\Support\Facades\Redirect::class,
            'Redis' => \Illuminate\Support\Facades\Redis::class,
            'Request' => \Illuminate\Support\Facades\Request::class,
            'Response' => \Illuminate\Support\Facades\Response::class,
            'Route' => \Illuminate\Support\Facades\Route::class,
            'Schema' => \Illuminate\Support\Facades\Schema::class,
            'Session' => \Illuminate\Support\Facades\Session::class,
            'Storage' => \Illuminate\Support\Facades\Storage::class,
            'Str' => \Illuminate\Support\Str::class,
            'URL' => \Illuminate\Support\Facades\URL::class,
            'Validator' => \Illuminate\Support\Facades\Validator::class,
            'View' => \Illuminate\Support\Facades\View::class,
            'PDF' => \Barryvdh\DomPDF\Facade::class
        ];
    }
}
