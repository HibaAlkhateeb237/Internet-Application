<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserDAOInterface;
use App\Repositories\UserRepository;
use App\Repositories\AdminDAOInterface;
use App\Repositories\AdminRepository;



class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserDAOInterface::class, UserRepository::class);
        $this->app->bind(AdminDAOInterface::class, AdminRepository::class);
    }

    public function boot()
    {

    }
}
