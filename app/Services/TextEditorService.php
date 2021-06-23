<?php

namespace App\Services;

class TextEditorService
{
    const ACTION_JSON_PRETTY = "json_pretty";
    const ACTION_URLENCODE = "urlencode";
    const ACTION_URLDECODE = "urldecode";

    /**
     * @param $input
     * @param $action
     * @return false|string
     */
    public function convert($input, $action)
    {
        switch ($action) {
            case self::ACTION_URLENCODE:
                $output = $this->doUrlEncode($input);
                break;
            case self::ACTION_URLDECODE:
                $output = $this->doUrlDecode($input);
                break;
            case self::ACTION_JSON_PRETTY:
            default:
                $output = $this->doJsonPretty($input);
                break;
        }
        return $output;
    }

    /**
     * @param $input
     * @return false|string
     */
    public function doJsonPretty($input)
    {
        $output = json_encode(json_decode($input), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $output;
    }

    public function doUrlEncode(string $str): string
    {
        return urlencode($str);
    }

    public function doUrlDecode(string $str)
    {
        return urldecode($str);
    }
}
