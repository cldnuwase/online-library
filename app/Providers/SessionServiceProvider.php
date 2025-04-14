<?php

namespace App\Providers;

use App\Session\DatabaseSessionHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

class SessionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Session::extend('database', function ($app) {
            $connection = $app['config']['session.connection'];
            $table = $app['config']['session.table'];
            $lifetime = $app['config']['session.lifetime'];
            
            return new DatabaseSessionHandler(
                $app['db']->connection($connection),
                $table,
                $lifetime,
                $app
            );
        });
    }
}
