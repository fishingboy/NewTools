<?php

namespace WcStudio\Tool\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use NewLog;

class ApiResponse
{
    /**
     * status code array.
     */
    private $apiInfo = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        504 => 'Gateway Timeout',
    ];

    private $status; // status
    private $msg;// api message

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        if ($request) {
            return response()->json([
                'code' => $this->status, 'comment' => $this->msg, 'data' => $request, 'date' => date('Y/m/d H:i:s'),
            ], $this->status);
        } else {
            return response()->json(['code' => $this->status, 'comment' => $this->msg, 'date' => date('Y/m/d H:i:s')],
                $this->status);
        }

    }

    /**
     * set current api msg.
     *
     * @param  string  $msg
     *
     * @return array
     */
    private function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * set current api status.
     *
     * @param  string  $status
     *
     * @return array
     */
    private function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * parse post, status, msg into response json.
     *
     * @param  obj  $post
     * @param  string  $status
     * @param  string  $pomsgst
     *
     * @return array
     */
    public function parseStatus($status = 404, $msg = null, $post = null)
    {
        // $parse = new $this($request);

        if ($status < 100) {
            $status = 500;  //因 http code 不支援小於 100 狀態，因此統一回傳 500 Internal Server Error
            //通用錯誤訊息，伺服器遇到了一個未曾預料的狀況，導致了它無法完成對請求的處理。沒有給出具體錯誤資訊
        }
        $this->setStatus($status);
        if ($msg === null && isset($this->apiInfo[$status])) {
            $this->setMsg($this->apiInfo[$status]);
        } else {
            $this->setMsg($msg);
        }

        NewLog::log4php($this->status, $this->msg, $post);

        return $this->toArray($post);
    }
}
