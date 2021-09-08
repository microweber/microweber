<?php

namespace MicroweberPackages\Multilanguage;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use MicroweberPackages\Admin\MailTemplates\Models\MailTemplate;
use MicroweberPackages\Category\Events\CategoryWasCreated;
use MicroweberPackages\Category\Events\CategoryWasUpdated;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\Events\ContentWasCreated;
use MicroweberPackages\Content\Events\ContentWasUpdated;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Menu\Events\MenuWasCreated;
use MicroweberPackages\Menu\Events\MenuWasUpdated;
use MicroweberPackages\Menu\Menu;
use MicroweberPackages\Multilanguage\Listeners\MultilanguageEventListener;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Page\Events\PageWasCreated;
use MicroweberPackages\Page\Events\PageWasUpdated;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Events\PostWasCreated;
use MicroweberPackages\Post\Events\PostWasUpdated;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasUpdated;
use MicroweberPackages\Product\Models\Product;


class MultilanguageEventServiceProvider extends EventServiceProvider
{

    public function boot()
    {
        parent::boot();
/*
        Content::observe(MultilanguageObserver::class);
        Category::observe(MultilanguageObserver::class);
        Post::observe(MultilanguageObserver::class);
        Menu::observe(MultilanguageObserver::class);
        Product::observe(MultilanguageObserver::class);
        Page::observe(MultilanguageObserver::class);
        ModuleOption::observe(MultilanguageObserver::class);
        MailTemplate::observe(MultilanguageObserver::class);
        CustomField::observe(MultilanguageObserver::class);
        CustomFieldValue::observe(MultilanguageObserver::class);*/
/*
        // Created
        Event::listen(
            ContentWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            PostWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            PageWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            ProductWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            CategoryWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            MenuWasCreated::class,
            [MultilanguageEventListener::class, 'handle']
        );

        // Updated
        Event::listen(
            ContentWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            PostWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            PageWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            ProductWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            CategoryWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );
        Event::listen(
            MenuWasUpdated::class,
            [MultilanguageEventListener::class, 'handle']
        );*/
    }

}

