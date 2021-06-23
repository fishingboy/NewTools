<?php

namespace Tests\Unit;

use App\Services\TextEditorService;
use PHPUnit\Framework\TestCase;

class JsonPrettyTest extends TestCase
{
    public function test_doJsonPretty()
    {
        $editor = new TextEditorService();
        $json = '{"name":"Leo"}';
        $response = $editor->doJsonPretty($json);
        $this->assertEquals("{\n    \"name\": \"Leo\"\n}", $response);
    }

    public function test_convert()
    {
        $editor = new TextEditorService();
        $json = '{"name":"Leo"}';
        $response = $editor->convert($json, TextEditorService::ACTION_JSON_PRETTY);
        $this->assertEquals("{\n    \"name\": \"Leo\"\n}", $response);
    }
}
