<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		/*
		 * AI-132 / SEC-01 (cycle-114 2026-05-09): test-fixture seed gate.
		 *
		 * Test fixtures (test admin accounts, seeded products, demo
		 * customers) MUST only be created when the seeder is invoked
		 * with the explicit `--seeding=test` flag (or SEEDING_MODE=test
		 * env). Production seed runs (`php artisan db:seed` without
		 * the flag) MUST NOT create test accounts.
		 *
		 * Pattern for any seeder adding test fixtures:
		 *
		 *   public function run() {
		 *       if (env('SEEDING_MODE') !== 'test') {
		 *           return;
		 *       }
		 *       // create test fixtures here
		 *   }
		 *
		 * Currently this seeder is empty (no test fixtures exist
		 * yet), but the gate is documented + enforceable so the
		 * convention is in place when fixtures are added.
		 */
		if (env('SEEDING_MODE') === 'test') {
			// $this->call('TestUserSeeder');
			// $this->call('TestProductSeeder');
		}

		// Production seed entries go here (none yet — `module:install`
		// + `cms:install` create the production schema baseline).
	}

}
