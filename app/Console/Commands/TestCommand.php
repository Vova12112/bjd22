<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bjd:test-command';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		try {
		} catch (Exception $e) {
			echo $e->getMessage();
		}
        return 0;
    }
}
