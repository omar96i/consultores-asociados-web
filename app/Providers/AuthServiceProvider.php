<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Diligenciamiento;
use App\Models\Role;
use App\Models\User;
use App\Models\Logo;
use App\Models\Configuration;
use App\Policies\DiligenciamientoPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\LogoPolicy;
use App\Policies\ConfigurationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        Diligenciamiento::class => DiligenciamientoPolicy::class,
        User::class => UserPolicy::class,
        Logo::class => LogoPolicy::class,
        Configuration::class => ConfigurationPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
