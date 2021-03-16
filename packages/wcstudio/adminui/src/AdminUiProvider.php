<?php

namespace WcStudio\AdminUi;

use WcStudio\AdminUi\Http\Middleware\Localization;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


class AdminUiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group(['middleware' => 'web'], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/adminui.php');
        });

        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', Localization::class);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'adminui');
        $this->loadViewsFrom(__DIR__.'/../resources/views/auth', 'adminui_auth');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang'),
        ]);

        $this->publishes([
            __DIR__.'/../resources/views/auth' => resource_path('views/auth'),
        ]);

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('static/'),
        ], 'assets');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');
    }

}
