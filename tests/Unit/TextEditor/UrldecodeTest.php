<?php

namespace Tests\Unit;

use App\Services\TextEditorService;
use PHPUnit\Framework\TestCase;

class UrldecodeTest extends TestCase
{
    public function test_action()
    {
        $editor = new TextEditorService();
        $str = '%3Fa%3D123';
        $response = $editor->doUrlDecode($str);
        var_dump($response);
        $this->assertEquals("?a=123", $response);
    }

    public function test_convert()
    {
        $editor = new TextEditorService();
        $str = '%3Fa%3D123';
        $response = $editor->convert($str, TextEditorService::ACTION_URLDECODE);
        var_dump($response);
        $this->assertEquals("?a=123", $response);
    }
}
