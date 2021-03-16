<?php

namespace WcStudio\AdminUi\Tests;

use WcStudio\Tool\CommonServiceProvider;
use WcStudio\AdminUi\AdminUiProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');

        // additional setup

    }

    protected function getPackageProviders($app)
    {
        return [
            CommonServiceProvider::class,
            AdminUiProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app,locale', 'en');

        $app['config']->set('database.reportsql', 'mysql');
        $app['config']->set('database.connections.reportsql', [
            'driver' => 'mysql',
            'host' => 'ECMW_db',
            'port' => '3306',
            'database' => 'report',
            'username' => 'root',
            'password' => 'root',
        ]);
    }

    protected function getPackageAliases($app)
    {
        return [
            "NewLog" => "WcStudio\Tool\Facades\LogFacade",
            "ServiceResponse" => "WcStudio\Tool\Facades\ServiceResponseFacade",
            "ApiResponse" => "WcStudio\Tool\Facades\ApiResponseFacade"
        ];
    }


}
