<?php

namespace Tests\Feature;

use App\Services\CsvService;
use Tests\TestCase;

class CsvTest extends TestCase
{
    public function test_write()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
        $data = [
            "Hello","哈囉","",""
        ];
        $csv->write($file, $data);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $this->assertEquals('"Hello","哈囉",,' . PHP_EOL, $content);
    }
    
    public function test_write_exists_file()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
        file_put_contents($file, '"1","2",,');
        $data = [
            "Hello","哈囉","",""
        ];
        $csv->write($file, $data);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"1","2",,' . PHP_EOL;
        $expected .= '"Hello","哈囉",,' . PHP_EOL;
        $this->assertEquals($expected, $content);
    }

    public function test_update_exists_file()
    {
        exec("rm -rf /tmp/*.csv");

        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt") . ".csv";
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fclose($fp);

        $data = [
            "Free","自由","",""
        ];
        $csv->update($file, $data);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"Free","自由",,' . PHP_EOL;
        $expected .= '"World","世界",,' . PHP_EOL;
        $this->assertEquals($expected, $content);
    }

    public function test_update_exists_file_with_module_name()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","洗介",,' . PHP_EOL);
        fputs($fp, '"World","世界",module,Qnap_module1' . PHP_EOL);
        fclose($fp);

        $data = ["Free","自由","",""];
        $csv->update($file, $data);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";

        $data = ["World","界世","module","Qnap_module1"];
        $csv->update($file, $data, "Qnap_module1");


        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"Free","自由",,' . PHP_EOL;
        $expected .= '"World","洗介",,' . PHP_EOL;
        $expected .= '"World","界世",module,Qnap_module1' . PHP_EOL;
        $this->assertEquals($expected, $content);
    }

    public function test_update_not_exists_file()
    {
        $csv = new CsvService();
        $file = "/tmp/11111";
        $data = [
            "Free","自由","",""
        ];
        $response = $csv->update($file, $data);
        $this->assertFalse($response);
    }

    public function test_delete_key_on_exists_file()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fclose($fp);

        $key = "Free";
        $csv->deleteKey($file, $key);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>key = " . print_r($key, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"World","世界",,' . PHP_EOL;
        $this->assertEquals($expected, $content);
    }

    public function test_delete_key_on_exists_file_with_module_name()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"World","世界",module,QNAP_Delete' . PHP_EOL);
        fclose($fp);

        $key = "Free";
        $csv->deleteKey($file, $key);
        $key = "World";
        $csv->deleteKey($file, $key, "QNAP_Delete");

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>key = " . print_r($key, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"World","世界",,' . PHP_EOL;
        $this->assertEquals($expected, $content);
    }

    public function test_delete_same_dir_key()
    {
        exec("rm -rf /tmp/*.csv");

        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt"). ".csv";
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"World","世界",module,QNAP_Delete' . PHP_EOL);
        fclose($fp);

        $file2 = tempnam("/tmp", "csvtt2") . ".csv";
        $fp = fopen($file2, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"World","世界",module,QNAP_Delete' . PHP_EOL);
        fclose($fp);

        $key = "Free";
        $csv->deleteSameDirCsvKey($file, $key);
        $key = "World";
        $csv->deleteSameDirCsvKey($file, $key, "QNAP_Delete");

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>key = " . print_r($key, true) . "</pre>\n";

        $content = file_get_contents($file);
        echo "<pre>content = " . print_r($content, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"World","世界",,' . PHP_EOL;
        $this->assertEquals($expected, $content);

        $content2 = file_get_contents($file);
        echo "<pre>content2 = " . print_r($content2, true) . "</pre>\n";
        $expected  = '"Hello","哈囉",,' . PHP_EOL;
        $expected .= '"World","世界",,' . PHP_EOL;
        $this->assertEquals($expected, $content2);
    }

    public function test_search()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csv-search");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"World","世界2",module,Qnap_Search' . PHP_EOL);
        fclose($fp);

        $key = "Free";
        $response = $csv->searchKey($file, $key);

        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>key = " . print_r($key, true) . "</pre>\n";

        $this->assertEquals(["Free","免費","",""], $response);
    }

    public function test_searchSameDirKey()
    {
        exec("rm -rf /tmp/*.csv");

        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt"). ".csv";
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"Work","工作",module,QNAP_Duplicate' . PHP_EOL);
        fclose($fp);

        $file2 = tempnam("/tmp", "csvtt2") . ".csv";
        $fp = fopen($file2, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"Work","工作",module,QNAP_CC' . PHP_EOL);
        fclose($fp);

        $response = $csv->searchSameDirKey($file, "Hello");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);

        $response = $csv->searchSameDirKey($file, "World");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);

        $response = $csv->searchSameDirKey($file, "Work", "QNAP_CC");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertIsArray($response);

        $response = $csv->searchSameDirKey($file, "Work", "");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertFalse($response);
    }

    public function test_searchWithModule()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csv-search");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"World","世界2",module,Qnap_Search' . PHP_EOL);
        fclose($fp);

        $key = "Free";
        $response = $csv->searchKey($file, $key);
        echo "<pre>file = " . print_r($file, true) . "</pre>\n";
        echo "<pre>key = " . print_r($key, true) . "</pre>\n";

        $this->assertEquals(["Free","免費","",""], $response);

        $key = "World";
        $response = $csv->searchKey($file, $key);
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertEquals(["World","世界","",""], $response);

        $key = "World";
        $response = $csv->searchKey($file, $key, "Qnap_Search");
        echo "<pre>response = " . print_r($response, true) . "</pre>\n";
        $this->assertEquals(["World","世界2","module","Qnap_Search"], $response);
    }

    public function test_isDuplicateKey()
    {
        $csv = new CsvService();
        $file = tempnam("/tmp", "csv-search");
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"Cool","酷",module,Qnap_Leo' . PHP_EOL);
        fputs($fp, '"Cool","酷",module,Qnap_Fishingboy' . PHP_EOL);
        fputs($fp, '"Cool","酷酷",module,"Qnap_Leo"' . PHP_EOL);
        fclose($fp);

        $key = "Hello";
        $response = $csv->isDuplicateKey($file, $key);
        $this->assertTrue($response);

        $key = "Free";
        $response = $csv->isDuplicateKey($file, $key);
        $this->assertFalse($response);

        $key = "Cool";
        $response = $csv->isDuplicateKey($file, $key, "Qnap_Leo");
        $this->assertTrue($response);
        $response = $csv->isDuplicateKey($file, $key, "Qnap_Fishingboy");
        $this->assertFalse($response);
    }

    public function test_isDuplicateSameDirKey()
    {
        exec("rm -rf /tmp/*.csv");

        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt"). ".csv";
        $fp = fopen($file, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"World","世界",,' . PHP_EOL);
        fputs($fp, '"Work","工作",module,QNAP_Duplicate' . PHP_EOL);
        fclose($fp);

        $file2 = tempnam("/tmp", "csvtt2") . ".csv";
        $fp = fopen($file2, "a+");
        fputs($fp, '"Hello","哈囉",,' . PHP_EOL);
        fputs($fp, '"Free","免費",,' . PHP_EOL);
        fputs($fp, '"Work","工作",module,QNAP_CC' . PHP_EOL);
        fclose($fp);

        $response = $csv->isDuplicateSameDirKey($file, "Hello");
        $this->assertTrue($response);

        $response = $csv->isDuplicateSameDirKey($file, "World");
        $this->assertFalse($response);

        $response = $csv->isDuplicateSameDirKey($file, "Work");
        $this->assertFalse($response);

        $response = $csv->isDuplicateSameDirKey($file, "Work", "QNAP_Duplicate");
        $this->assertFalse($response);
    }
}
