<?php

namespace Modules\Newsletter\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

abstract class NewsletterTestCase extends TestCase
{
    use DatabaseTransactions;


}
