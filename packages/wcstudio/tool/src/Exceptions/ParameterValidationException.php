<?php


namespace WcStudio\Tool\Exceptions;

use Exception;
use Log;
use ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ParameterValidationException extends Exception
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
     * @param $request Request
     * @param $exception Exception
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        return ApiResponse::parseStatus($exception->getCode(), $exception->getMessage(), []);
    }
}
