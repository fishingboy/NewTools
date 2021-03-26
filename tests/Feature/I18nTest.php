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
        $i18nService = new I18nService();
        $response = $i18nService->getCsv("20210316-QuWan-i18n.csv");
        $this->assertIsString($response);
    }

    public function test_getData()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210316-QuWan-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
    }

    public function test_getData2()
    {
        $i18nService = new I18nService();

        $response = $i18nService->getData("20210316-QuWan-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);
        $this->assertArrayHasKey('th_th', $response['i18n'][0]);
    }

    public function test_getSwI18nFile()
    {
        $i18nService = new I18nService();
        $i18n_code = "th_th";
        $file = $i18nService->getFilePath($i18n_code);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        $this->assertIsString($file);
        $this->assertEquals("/mnt/c/Users/Leo Kuo/Code/software-store/app/i18n/Mageplaza/th_th/github_contributions.csv", $file);

        $i18n_code = "ro_ro";
        $file = $i18nService->getFilePath($i18n_code);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        $this->assertIsString($file);
        $this->assertEquals("/mnt/c/Users/Leo Kuo/Code/software-store/app/i18n/eadesigndev/ro_ro/ro_RO.csv", $file);
    }

    public function test_get_write_csv_line()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210316-QuWan-i18n.csv");
        $i18n = $response['i18n'][0];
        $line = $i18nService->getWriteLine($i18n, 'th_th');
        echo "<pre>line = " . print_r($line, true) . "</pre>\n";

        $this->assertIsString($line);
    }

    public function test_get_write_csv_row()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210316-QuWan-i18n.csv");
        $i18n = $response['i18n'][0];
        $row = $i18nService->getWriteRow($i18n, 'th_th');
        echo "<pre>row = " . print_r($row, true) . "</pre>\n";

        $this->assertIsArray($row);
    }

    public function test_writeI18n()
    {
        $i18nService = new I18nService();
        $csv_file = "20210316-QuWan-i18n.csv";
        $response = $i18nService->getData($csv_file);
        $i18n = $response['i18n'];
        echo "<pre>i18n = " . print_r($i18n, true) . "</pre>\n";

        $env = "sw";
        $response = $i18nService->writeFiles($i18n, $env);
        $this->assertTrue($response);
    }

    public function test_isNeedNewLine()
    {
        $i18nService = new I18nService();

        // 寫檔案
        $file = "/tmp/have_new_line.txt";
        $fp = fopen($file, "w");
        fwrite($fp, "aaa123\n");
        fclose($fp);

        $response = $i18nService->isNeedNewLine($file);
        $this->assertFalse($response);

        // 寫檔案
        $file = "/tmp/no_have_new_line.txt";
        $fp = fopen($file, "w");
        fwrite($fp, "bbb456");
        fclose($fp);

        $response = $i18nService->isNeedNewLine($file);
        $this->assertTrue($response);
    }
}
