<?php

namespace Tests\Unit\TextEditor;

use App\Services\TextEditorService;
use PHPUnit\Framework\TestCase;

class Base64Test extends TestCase
{
    public function test_convert_encode()
    {
        $editor = new TextEditorService();
        $response = $editor->convert('Hello NewTools', TextEditorService::ACTION_BASE64ENCODE);
        $this->assertEquals('SGVsbG8gTmV3VG9vbHM=', $response);
    }

    public function test_convert_decode()
    {
        $editor = new TextEditorService();
        $response = $editor->convert('SGVsbG8gTmV3VG9vbHM=', TextEditorService::ACTION_BASE64DECODE);
        $this->assertEquals('Hello NewTools', $response);
    }

    public function test_convert_encode_utf8()
    {
        $editor = new TextEditorService();
        $response = $editor->convert('哈囉', TextEditorService::ACTION_BASE64ENCODE);
        $this->assertEquals('5ZOI5ZuJ', $response);
    }

    public function test_convert_decode_utf8()
    {
        $editor = new TextEditorService();
        $response = $editor->convert('5ZOI5ZuJ', TextEditorService::ACTION_BASE64DECODE);
        $this->assertEquals('哈囉', $response);
    }

    /**
     * encode 之後再 decode 要回到原文；encode/decode 接反的話這個測試會失敗。
     */
    public function test_encode_then_decode_returns_original()
    {
        $editor = new TextEditorService();
        $original = 'a b&c?d=1';

        $encoded = $editor->convert($original, TextEditorService::ACTION_BASE64ENCODE);
        $this->assertNotEquals($original, $encoded);

        $decoded = $editor->convert($encoded, TextEditorService::ACTION_BASE64DECODE);
        $this->assertEquals($original, $decoded);
    }
}
