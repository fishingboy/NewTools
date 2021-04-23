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
        $csv = new CsvService();
        $file = tempnam("/tmp", "csvtt");
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
}
