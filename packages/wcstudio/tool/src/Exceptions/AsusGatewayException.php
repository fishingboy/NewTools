<?php

namespace WcStudio\Tool\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;
use ApiResponse;

class AsusGatewayException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //custom log
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        return ApiResponse::parseStatus($exception->getCode(), $exception->getMessage(), []);
    }
}
