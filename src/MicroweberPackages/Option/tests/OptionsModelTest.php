<?php

namespace MicroweberPackages\Option\tests;

use Illuminate\Support\Facades\Event;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Option\Events\OptionWasCreated;
use MicroweberPackages\Option\Events\OptionIsCreating;
use MicroweberPackages\Option\Events\OptionIsUpdating;
use MicroweberPackages\Option\Events\OptionWasDeleted;
use MicroweberPackages\Option\Events\OptionWasRetrieved;
use MicroweberPackages\Option\Events\OptionWasUpdated;
use MicroweberPackages\Option\Facades\Option as OptionFacade;
use MicroweberPackages\Option\Models\Option;

class OptionsModelTest extends TestCase
{
    public function testOptionsEvents()
    {
        Event::fake();
        $model = new Option();
        $model->option_key = 'test';
        $model->option_value = 'test';
        $model->option_group = 'test';
        $model->save();
        Event::assertDispatched(OptionIsCreating::class);
        Event::assertDispatched(OptionWasCreated::class);

        //test OptionIsUpdating
        $model->option_value = 'test2';
        $model->save();
        Event::assertDispatched(OptionWasUpdated::class);
        Event::assertDispatched(OptionIsUpdating::class);

        //test OptionWasRetrieved
        $model->get();
        Event::assertDispatched(OptionWasRetrieved::class);

        //test OptionWasDeleted
        $model->delete();
        Event::assertDispatched(OptionWasDeleted::class);


    }
}
