<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;

class TestNoSql extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'test:nosql';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command line NoSql tester';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle()
	{
    try {
  		// testing nosql logging
  		$person = new Person();
      $person->name = "Davy Jones";
      $person->birthdate = "1990-10-1";
      $person->timezone = "America/Los_Angeles";
  		$person->save();
      $this->info("NoSql connection works");
    } catch (\Exception $e) {
      $this->error("NoSql connection fails");
      $this->info($e->getMessage());
    }

	}
}
