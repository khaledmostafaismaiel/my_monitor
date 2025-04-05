<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\NormalTransaction' => 'App\Policies\ExpensePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(/*Gate $gate*/)
    {
        $this->registerPolicies();


//        $gate->before(function ($user){
//            return $user->id == 1 ; // this is an administrator ? if this is an admin it can do any thing
//        }) ;
    }
}
