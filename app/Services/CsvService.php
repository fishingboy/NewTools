<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\fileExists;

/**
 * @todo: 給一個 file 做讀取/寫入/更新/查詢的動作
 * Class I18nCsvService
 * @package App\Services
 */
class CsvService
{
    public function searchKey(string $file, string $key)
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = '"' . $this->convertCsvDoubleQuotes($key) . '"';

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        while ($line = fgets($fp)) {
            if (strpos($line, $key) === 0) {
                //  找到 key 那行就做更新
                return str_getcsv($line);
            }
        }
        return false;
    }

    public function write($file, $data): bool
    {
        $write_line = $this->getWriteLine($data);
        $new_line = $this->isNeedNewLine($file);
        $fp = fopen($file, "a+");
        if ($new_line) {
            fwrite($fp, "\n");
        }
        fwrite($fp, $write_line . "\n");
        fclose($fp);

        return true;
    }

    public function update(string $file, array $data): bool
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = '"' . $this->convertCsvDoubleQuotes($data[0]) . '"';

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        while ($line = fgets($fp)) {
            if (strpos($line, $key) === 0) {
                //  找到 key 那行就做更新
                $write_line = $this->getWriteLine($data);
                $new_content .= $write_line . "\n";
            } else {
                // 其他行不動異動直接寫入
                $new_content .= $line;
            }
        }
        file_put_contents($file, $new_content);
        return true;
    }

    public function deleteKey(string $file, string $key): bool
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = '"' . $this->convertCsvDoubleQuotes($key) . '"';

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        while ($line = fgets($fp)) {
            // 符合 key 的那行不寫入
            if (strpos($line, $key) !== 0) {
                $new_content .= $line;
            }
        }
        file_put_contents($file, $new_content);
        return true;
    }

    /**
     * 取得要寫入 csv 的內容
     * @param $data
     * @return string
     */
    private function getWriteLine($data): string
    {
        $csv_content = "";
        foreach ($data as $i => $value) {
            if ($i > 0) {
                $csv_content .= ",";
            }
            $data[$i] = $this->convertCsvDoubleQuotes($value);
            if ($data[$i]) {
                $csv_content .= "\"{$data[$i]}\"";
            }
        }
        return $csv_content;
    }

    private function convertCsvDoubleQuotes($str)
    {
        return str_replace('"', '""', $str);
    }

    /**
     * 寫入 i18n 時，需不需要先換行
     * @param string $file
     * @return bool
     */
    private function isNeedNewLine(string $file): bool
    {
        $content = file_get_contents($file);
        $len = strlen($content);
        if ($len == 0) {
            return false;
        }
        return ! ($content[$len - 1] == "\n");
    }
}
