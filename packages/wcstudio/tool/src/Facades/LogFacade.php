<?php

namespace WcStudio\Tool\Facades;

use Illuminate\Support\Facades\Facade;

class LogFacade extends Facade
{
    /**
     * @mixed service provider 中註冊的別名
     */
    protected static function getFacadeAccessor()
    {
        return 'LogService';
    }
}
