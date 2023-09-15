<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create-role', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('1', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('view-role', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('2', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('update-role', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('3', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('delete-role', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('4', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('create-permission', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('5', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('view-permission', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('6', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('update-permission', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('7', json_decode($permission->permission_id, true))) {
                return true;
            }
        });

        Gate::define('delete-permission', function ($user) {
            $permission = DB::table('role_permissions')->where('role_id', '=', $user->role_id)->first();

            if (in_array('8', json_decode($permission->permission_id, true))) {
                return true;
            }
        });
    }
}