<?php

namespace Modules\Newsletter\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use App\Models\User;

abstract class NewsletterTestCase extends TestCase
{
    use LazilyRefreshDatabase;


}
