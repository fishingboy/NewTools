<?php

namespace WcStudio\Tool\Http\Resources;

use NewLog;

class ServiceResponse
{
    private $apiInfo = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        504 => 'Gateway Timeout',
    ];

    /**
     *
     * @param  int  $status
     * @param  string  $msg
     * @param  object  $post
     *
     * @return array
     *
     */
    public function parseStatus($status = 404, $msg = null, $post = null)
    {
        if ($msg === null && isset($this->apiInfo[$status])) {
            $msg = $this->apiInfo[$status];
        }
        
        return [
            'status' => $status,
            'msg' => $msg,
            'data' => $post,
        ];
    }
}
