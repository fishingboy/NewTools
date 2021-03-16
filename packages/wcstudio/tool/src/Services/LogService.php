<?php

namespace WcStudio\Tool\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function __construct()
    {
    }

    /**
     * @param  string  $statusCode  = http status code
     * @param  string  $msg  = 訊息
     * @param  string  $path  = 'PATH：'.__FILE__.'；FUNCTION：'.__FUNCTION__.'；LINE：'.__LINE__.'.';
     * @param  array  $data  = api data
     *
     */
    public function log4php($statusCode, $msg, $data = null)
    {
        //$level = 'info';
        // if ($statusCode >= 500)
        //     $level = 'alert';
        // else
        if ($statusCode >= 500) {
            $level = 'critical';
        } elseif ($statusCode > 300) {
            $level = 'warning';
        } elseif ($statusCode == 200) {
            if ($msg == 'API-LOG') {
                $level = 'notice';
            } else {
                $level = 'info';
            }
        } else {
            $level = 'debug';
        }

        $body['status_code'] = $statusCode;
        $body['msg'] = $msg;
        //$body['path'] = $path;
        $body['path'] = $this->get_caller_info();
        $body['data'] = $data;

        Log::$level(json_encode($body, JSON_UNESCAPED_UNICODE)); // add json options by Rock
    }

    //caller path = 'PATH：'.__FILE__.'；FUNCTION：'.__FUNCTION__.'；
    private function get_caller_info()
    {
        $c = '';
        $file = '';
        $func = '';
        $class = '';
        $trace = debug_backtrace();
        if (isset($trace[2])) {
            $file = $trace[1]['file'];
            $func = $trace[2]['function'];
            if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
                $func = '';
            }
        } else {
            if (isset($trace[1])) {
                $file = $trace[1]['file'];
                $func = '';
            }
        }
        if (isset($trace[3]['class'])) {
            $class = $trace[3]['class'];
            $func = $trace[3]['function'];
            $file = $trace[2]['file'];
        } else {
            if (isset($trace[2]['class'])) {
                $class = $trace[2]['class'];
                $func = $trace[2]['function'];
                $file = $trace[1]['file'];
            }
        }
        if ($file != '') {
            $file = basename($file);
        }
        $c = $file.": ";
        $c .= ($class != '') ? ":".$class."->" : "";
        $c .= ($func != '') ? $func."(): " : "";
        return ($c);
    }
}
