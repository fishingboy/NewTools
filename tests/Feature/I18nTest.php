<?php

namespace Tests\Feature;

use App\Services\I18nService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class I18nTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_getCSV()
    {
        $response = Storage::get("20210316-QuWan-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsString($response);
    }

    public function test_getCSV2()
    {
        $response = I18nService::getCsv("20210316-QuWan-i18n.csv");
        $this->assertIsString($response);
    }

    public function test_getData()
    {
        $response = I18nService::getData("20210316-QuWan-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
    }

    public function test_getData2()
    {
        $response = I18nService::getData("20210316-QuWan-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);
        $this->assertArrayHasKey('th_th', $response['i18n'][0]);
    }
}
