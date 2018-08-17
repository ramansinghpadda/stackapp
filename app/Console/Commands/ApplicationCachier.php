<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cache;
use App\Application;
class ApplicationCachier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:cache {organizationID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create cache for application by organization id';

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
        $organizationID = $this->argument('organizationID');
        Cache::forget('organization-'.$organizationID.'-applications');
        Cache::remember('organization-'.$organizationID.'-applications', 10, function() use ($organizationID) {
            return Application::where('statusID',1)->where('oID',$organizationID)->get();
        });
    }
}
