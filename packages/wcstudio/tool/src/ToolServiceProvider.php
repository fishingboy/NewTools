<?php

namespace WcStudio\Tool;


use WcStudio\Tool\Http\Resources\ApiResponse;
use WcStudio\Tool\Http\Resources\ServiceResponse;
use WcStudio\Tool\Services\LogService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * $this->app->singleton() 會產生同一個實例
     * $this->app->bind() 每次都產生不同實例
     * @return void
     */
    public function register()
    {
        $this->app->singleton('apiResource', function () {
            return new ApiResponse();
        });

        $this->app->singleton('serviceResource', function () {
            return new ServiceResponse();
        });
        $this->app->singleton('LogService', function () {
            return new LogService();
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
