<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class WarehouseInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warehouse:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure all dependencies for working enviroment';

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
        $this->warn('Calling migrate...');
        Artisan::call('migrate');
        $this->warn('Seeding db...');
        Artisan::call('db:seed');
        $this->warn('Clearing permission cache');
        Artisan::call('permission:cache-reset');
        $this->warn('Installing telescope');
        Artisan::call('telescope:install');
        $this->warn('Configure account');

        // Assign role to user
        $role = Role::findById(1);
        $role->syncPermissions(Permission::all());

        $user = User::find(1);
        $user->syncRoles($role);

        $this->info('All done');
        $this->info('email: admin@admin.com');
        $this->info('password: admin');

        $this->info('Please change credentials after login to system');

    }
}
