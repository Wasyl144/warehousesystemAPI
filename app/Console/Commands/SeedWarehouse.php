<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedWarehouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warehouse:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed warehouse with some random items';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('Seeding db...');
//        Artisan::call('db:seed --class=AdminSeeder ');
        Artisan::call('db:seed --class=CategorySeeder ');
        Artisan::call('db:seed --class=ItemSeeder ');
    }
}
