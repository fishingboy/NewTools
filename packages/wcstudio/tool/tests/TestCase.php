<?php

namespace WcStudio\Tool\Tests;

use WcStudio\Tool\MacroServiceProvider;
use WcStudio\Tool\CommonServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        // additional setup

    }

    protected function getPackageProviders($app)
    {
        return [
            CommonServiceProvider::class,
            MacroServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
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
