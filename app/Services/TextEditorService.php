<?php

namespace App\Services;

class TextEditorService
{
    const ACTION_JSON_PRETTY = "json_pretty";
    const ACTION_URLENCODE = "urlencode";
    const ACTION_URLDECODE = "urldecode";
    const ACTION_BASE64ENCODE = "base64encode";
    const ACTION_BASE64DECODE = "base64decode";

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
            case self::ACTION_BASE64DECODE:
                $output = $this->doBase64Encode($input);
                break;
            case self::ACTION_BASE64ENCODE:
                $output = $this->doBase64Decode($input);
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
        $data = json_decode($input, true);
        $output = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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

    private function doBase64Encode($str)
    {
        return base64_encode($str);
    }

    private function doBase64Decode($str)
    {
        return base64_decode($str);
    }
}
