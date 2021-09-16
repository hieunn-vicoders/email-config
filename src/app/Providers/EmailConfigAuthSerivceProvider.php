<?php

namespace VCComponent\Laravel\EmailConfig\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use VCComponent\Laravel\Mail\Contracts\EmailConfigPolicyInterface;
use VCComponent\Laravel\Mail\Entities\Mail;

class EmailConfigAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Mail::class => EmailConfigPolicyInterface::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
