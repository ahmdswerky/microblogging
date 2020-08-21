<?php

namespace App\Console\Commands;

use Exception;
use Laravel\Passport\Passport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class ApplicationSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup your application with one simple command';

    protected $installed = false;

    // protected $package = 'Sanctum';

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
     * @return mixed
     */
    public function handle()
    {
		$this->warn("·· Setting up your application");
        $this->callSilent('config:cache');
        if (! App::environment('production')) {
            // 00.Turn off foreign key check
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            // 01.Clear DB
            $this->call('migrate:fresh');
            $this->info("🌱 Database Migration is Done");
            // 01.5.Turn foreign key check back on
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->warn("·· Dummy Data is being generated");
        $this->call('db:seed');

        $this->warn("·· Installing Passport");
        if ( !class_exists(Passport::class) ) {
            return $this->error('Passport isn\'t installed, run: composer require laravel/passport');
        };
        $this->callSilent('passport:install');
        $this->info("🌱 Passport installed");

        // 03.Change Passport Client Secret
        $this->warn("Updaing Client Secret ...");
        if ( !array_key_exists('passport_secret', config('app')) ) {
            return $this->error('please add the key "passport_secret" in your config/app.php first');
        };
        if ( $secret = config('app.passport_secret') ) {
            DB::table('oauth_clients')->where('id', 2)->update(compact('secret'));
        }
        $secret = optional(DB::table('oauth_clients')->find(2))->secret;
        $this->line('-------------');
        $this->line('Client Secret: ');
        $this->line( '🛡  ' . $secret );
        $this->line('-------------');

        if (! App::environment('production')) {
            $this->call('test');
        }

        $this->line( '🛡  ' . $secret );

		$this->comment("🎉 All Done 🎉");
    }
}
