<?php

namespace Tests\Feature;

use App\Services\CsvService;
use App\Services\I18nService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class JsonTest extends TestCase
{
    public function test_1()
    {
        $data = ["a" => 1];
        $json = json_encode($data);
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";
    }
}
