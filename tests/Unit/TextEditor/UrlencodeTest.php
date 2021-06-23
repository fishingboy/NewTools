<?php

namespace Tests\Unit;

use App\Services\TextEditorService;
use PHPUnit\Framework\TestCase;

class UrlencodeTest extends TestCase
{
    public function test_action()
    {
        $editor = new TextEditorService();
        $str = '?a=123';
        $response = $editor->doUrlEncode($str);
        var_dump($response);
        $this->assertEquals("%3Fa%3D123", $response);
    }

    public function test_convert()
    {
        $editor = new TextEditorService();
        $str = '?a=123';
        $response = $editor->convert($str, TextEditorService::ACTION_URLENCODE);
        var_dump($response);
        $this->assertEquals("%3Fa%3D123", $response);
    }
}
