<?php

namespace VCComponent\Laravel\EmailConfig\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class EmailConfigAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-email-config', 'VCComponent\Laravel\Mail\Contracts\EmailConfigPolicyInterface@ableToUse');
        //
    }
}
