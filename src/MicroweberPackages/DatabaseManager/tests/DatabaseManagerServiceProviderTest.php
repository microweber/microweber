<?php

class DatabaseManagerServiceProviderTest extends BaseTest
{
	public function testDatabaseManagerWhenUsing(){

		$this->assertInstanceOf(\MicroweberPackages\DatabaseManager\DatabaseManager::class, app('database_manager'));
	}

}