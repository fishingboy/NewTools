<?php

namespace Tests\Feature;

use App\Services\I18nService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
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
        $response = Storage::get("20210324-SW-QVR-AI-Pack-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsString($response);
    }

    public function test_getCSV2()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getCsv("20210324-SW-QVR-AI-Pack-i18n.csv");
        $this->assertIsString($response);
    }

    public function test_getData()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210324-SW-QVR-AI-Pack-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
    }

    public function test_getData2()
    {
        $i18nService = new I18nService();

        $response = $i18nService->getData("20210324-SW-QVR-AI-Pack-i18n.csv");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);
        $this->assertArrayHasKey('th_th', $response['i18n'][0]);
    }

    public function test_getData_newLine()
    {
        $i18nService = new I18nService(["split_line" => true]);

        // 寫檔案
        $fp = tmpfile();
        $content = <<<CSV
Final ENG,Final CHT,SCH (Schinese)
en_us,zh_hant_tw,zh_hans_cn
"Hello<br> 
World!","哈囉<br>
世界","哈囉2<br>
世界2"
CSV;
        fwrite($fp, $content);
        $file = stream_get_meta_data($fp)['uri'];

        $response = $i18nService->getData($file);
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);

        $this->assertEquals([
            ['en_us' => 'Hello', 'zh_hant_tw' => '哈囉', 'zh_hans_cn' => '哈囉2',],
            ['en_us' => 'World!', 'zh_hant_tw' => '世界', 'zh_hans_cn' => '世界2',]
        ], $response['i18n']);
    }

    public function test_getData_newLine2()
    {
        $i18nService = new I18nService(["split_line" => true]);

        // 寫檔案
        $fp = tmpfile();
        $content = <<<CSV
Final ENG,Final CHT,SCH (Schinese)
en_us,zh_hant_tw,zh_hans_cn
"eng","zh","cn"
"Hello<br> 
World!","哈囉<br>
世界","哈囉2<br>
世界2"
"eng2","zh2","cn2"
CSV;
        fwrite($fp, $content);
        $file = stream_get_meta_data($fp)['uri'];

        $response = $i18nService->getData($file);
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);

        $this->assertEquals([
            ["en_us" => "eng", "zh_hant_tw" => "zh", "zh_hans_cn" => "cn",],
            ['en_us' => 'Hello', 'zh_hant_tw' => '哈囉', 'zh_hans_cn' => '哈囉2',],
            ['en_us' => 'World!', 'zh_hant_tw' => '世界', 'zh_hans_cn' => '世界2',],
            ["en_us" => "eng2", "zh_hant_tw" => "zh2", "zh_hans_cn" => "cn2",],
        ], $response['i18n']);
    }

    public function test_getData_empty_field()
    {
        $i18nService = new I18nService();

        // 寫檔案
        $fp = tmpfile();
        $content = <<<CSV
Final ENG,Final CHT,SCH (Schinese)
en_us,zh_hant_tw,zh_hans_cn
"eng","","cn"
"Hello","哈囉",""
CSV;
        fwrite($fp, $content);
        $file = stream_get_meta_data($fp)['uri'];

        $response = $i18nService->getData($file);
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);
        $this->assertArrayHasKey('raw_data', $response);
        $this->assertArrayHasKey('en_us', $response['i18n'][0]);

        $this->assertEquals([
            ["en_us" => "eng", "zh_hans_cn" => "cn",],
            ['en_us' => 'Hello', 'zh_hant_tw' => '哈囉',],
        ], $response['i18n']);
    }

    public function test_getCsvFileName()
    {
        $i18nService = new I18nService();
        $i18n_code = "th_th";
        $this->assertEquals("th_TH.csv", $i18nService->getCsvFileName($i18n_code));
        $i18n_code = "zh_hant_tw";
        $this->assertEquals("zh_Hant_TW.csv", $i18nService->getCsvFileName($i18n_code));
        $i18n_code = "zh_hans_cn";
        $this->assertEquals("zh_Hans_CN.csv", $i18nService->getCsvFileName($i18n_code));
    }
    
    public function test_getSwI18nFile()
    {
        $path = "/zzz/software-store";

        $mock = $this->createPartialMock(I18nService::class, ['isFileExists','getMagentoPath']);
        $mock->method('isFileExists')->willReturnCallback(function($file) use ($path) {
            if ($file == "{$path}/app/i18n/Mageplaza/th_th/th_TH.csv") {return true;}
            if ($file == "{$path}/app/i18n/eadesigndev/ro_ro/ro_RO.csv") {return true;}
            return false;
        });
        $mock->method('getMagentoPath')->willReturn($path);

        $i18n_code = "th_th";
        $file = $mock->getFilePath($i18n_code);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        $this->assertIsString($file);
        $this->assertEquals("{$path}/app/i18n/Mageplaza/th_th/th_TH.csv", $file);

        $i18n_code = "ro_ro";
        $file = $mock->getFilePath($i18n_code);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        $this->assertIsString($file);
        $this->assertEquals("{$path}/app/i18n/eadesigndev/ro_ro/ro_RO.csv", $file);
    }

    public function test_getSwI18nOriginFile()
    {
        $path = "/zzz/software-store";

        $mock = $this->createPartialMock(I18nService::class, ['isFileExists','getMagentoPath']);
        $mock->method('isFileExists')->willReturnCallback(function($file) use ($path) {
            if ($file == "{$path}/app/i18n/Mageplaza/th_th/github_contributions.csv") {return true;}
            return false;
        });
        $mock->method('getMagentoPath')->willReturn($path);

        $i18n_code = "th_th";
        $file = $mock->getOriginFilePath($i18n_code);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        $this->assertIsString($file);
        $this->assertEquals("{$path}/app/i18n/Mageplaza/th_th/github_contributions.csv", $file);
    }

    public function test_get_write_csv_line()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210324-SW-QVR-AI-Pack-i18n.csv");
        $i18n = $response['i18n'][0];
        $line = $i18nService->getWriteLine($i18n, 'th_th');
        echo "<pre>line = " . print_r($line, true) . "</pre>\n";

        $this->assertIsString($line);
    }

    public function test_get_write_csv_row()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getData("20210324-SW-QVR-AI-Pack-i18n.csv");
        $i18n = $response['i18n'][0];
        $row = $i18nService->getWriteRow($i18n, 'th_th');
        echo "<pre>row = " . print_r($row, true) . "</pre>\n";

        $this->assertIsArray($row);
    }

    public function test_writeI18n()
    {
        $fp = tmpfile();
        fwrite($fp, '"Hello","哈囉",,');
        $file = stream_get_meta_data($fp)['uri'];
        $mock_i18nService = $this->createPartialMock(I18nService::class, ['getFilePath']);
        $mock_i18nService->method('getFilePath')->willReturn($file);

        $csv_file = "20210324-SW-QVR-AI-Pack-i18n.csv";
        try {
            $response = $mock_i18nService->getData($csv_file);
        } catch (Exception $e) {
            echo "<pre>e = " . print_r($e->getMessage(), true) . "</pre>\n";
        }
        $i18n = $response['i18n'];
        echo "<pre>i18n = " . print_r($i18n, true) . "</pre>\n";

        $site = "sw";
        $response = $mock_i18nService->writeFiles($i18n, $site);
        $this->assertTrue($response);
    }

    public function test_isNeedNewLine_false()
    {
        $i18nService = new I18nService();

        // 寫檔案
        $fp = tmpfile();
        fwrite($fp, "aaa123\n");
        $file = stream_get_meta_data($fp)['uri'];

        // 判斷需不需要新行
        $response = $i18nService->isNeedNewLine($file);
        $this->assertFalse($response);
        fclose($fp);
    }

    public function test_isNeedNewLine_true()
    {
        $i18nService = new I18nService();

        // 寫檔案
        $fp = tmpfile();
        fwrite($fp, "abc123");
        $file = stream_get_meta_data($fp)['uri'];

        // 判斷需不需要新行
        $response = $i18nService->isNeedNewLine($file);
        $this->assertTrue($response);
        fclose($fp);
    }

    public function test_isNeedWriteFile_true()
    {
        $i18nService = new I18nService();
        $i18n_key = "Hello World";

        // 寫檔案
        $fp = tmpfile();
        fwrite($fp, '"abc123","abc123",,');
        $file = stream_get_meta_data($fp)['uri'];

        // 判斷需不需要新行
        $response = $i18nService->isNeedWriteFile($file, $i18n_key);
        $this->assertTrue($response);

        // 關閉並刪除檔案 tmp 檔
        fclose($fp);
    }

    public function test_isNeedWriteFile_false()
    {
        $i18nService = new I18nService();
        $i18n_key = "Hello World";

        // 寫檔案
        $fp = tmpfile();
        fwrite($fp, '"Hello World","哈囉世界",,');
        $file = stream_get_meta_data($fp)['uri'];

        // 判斷需不需要新行
        $response = $i18nService->isNeedWriteFile($file, $i18n_key);
        $this->assertFalse($response);

        // 關閉並刪除檔案 tmp 檔
        fclose($fp);
    }


    public function test_isHaveNewLine_true()
    {
        $i18nService = new I18nService();

        $phrase = " Turn your NAS into a smart facial recognition solution.
 
This complete solution is suitable for applications such as member identification management, door access control systems, and smart retail.";

        $response = $i18nService->isHaveNewLine($phrase);
        $this->assertTrue($response);
    }

    public function test_isHaveNewLine_false()
    {
        $i18nService = new I18nService();
        $phrase = " Turn your NAS into a smart facial recognition solution.";
        $response = $i18nService->isHaveNewLine($phrase);
        $this->assertFalse($response);
    }

    public function test_getMagentoPath()
    {
        $i18nService = new I18nService();
        $response = $i18nService->getMagentoPath('sw');
        $this->assertEquals("/mnt/c/Users/Leo Kuo/Code/software-store", $response);

        $response = $i18nService->getMagentoPath('eu');
        $this->assertEquals("/mnt/c/Users/Leo Kuo/Code/eshop-eu", $response);
    }
}
