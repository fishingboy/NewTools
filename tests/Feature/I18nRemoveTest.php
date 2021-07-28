<?php

namespace Tests\Feature;

use App\Services\CsvService;
use App\Services\I18nService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class I18nRemoveTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_Remove()
    {
        $i18nService = new I18nService();
        $i18n_key = "Payment Method";
        $site = "sw";
        $module = "all";

        $status = $i18nService->deleteKey($i18n_key, $site, $module);



    }

    public function test_getI18nCodes()
    {
        $i18nService = new I18nService();
        $site = "sw";
        $codes = $i18nService->getI18nCodes($site);
        echo "<pre>codes = " . print_r($codes, true) . "</pre>\n";
        $this->assertIsArray($codes);

    }
}
