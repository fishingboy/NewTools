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
    public function __construct()
    {

    }
    
    public function searchKey(string $file, string $key, $module_name = "")
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = $this->convertCsvDoubleQuotes($key);

        // 讀檔
        $fp = fopen($file, "r");
        // 逐行檢查
        while ($line = fgets($fp)) {
            if ( ! $this->isMatchKey($line, $key)) {
                continue;
            }
            if ( ! $this->isMatchModuleName($line, $module_name)) {
                continue;
            }
            //  找到 key 那行就做更新
            return str_getcsv($line);
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

    public function update(string $file, array $data, $module_name = ""): bool
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 如果 key 有重覆，就整個刪掉重寫一筆新的
        if ($this->isDuplicateKey($file, $data[0], $module_name)) {
            // 刪掉
            $this->deleteKey($file, $data[0]);
            // 寫入
            $this->write($file, $data);

            echo "[{$data[0]}] 重覆出現，刪掉重建！！\n";
            return true;
        }

        // 組合出 csv 裡的搜尋字串
        $key = $data[0];

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        while ($line = fgets($fp)) {
            if ($this->isMatchKey($line, $key) && $this->isMatchModuleName($line, $module_name)) {
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

    public function deleteKey(string $file, string $key, $module_name = ""): bool
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = $this->convertCsvDoubleQuotes($key);

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        while ($line = fgets($fp)) {
            // 符合 key 的那行不寫入
            if ($this->isMatchKey($line, $key) && $this->isMatchModuleName($line, $module_name)) {
                continue;
            }
            $new_content .= $line;
        }
        file_put_contents($file, $new_content);
        return true;
    }

    public function isDuplicateKey(string $file, string $key, $module_name = ""): bool
    {
        if ( ! file_exists($file)) {
            return false;
        }

        // 組合出 csv 裡的搜尋字串
        $key = $this->convertCsvDoubleQuotes($key);

        // 讀檔
        $fp = fopen($file, "r");
        $new_content = "";
        // 逐行檢查
        $count = 0;
        while ($line = fgets($fp)) {
            if ( ! $this->isMatchKey($line, $key)) {
                continue;
            }

            if ( ! $this->isMatchModuleName($line, $module_name)) {
                continue;
            }

            $count++;
        }
        return ($count > 1);
    }

    /**
     * 取得要寫入 csv 的內容
     * @param $data
     * @return string
     */
    private function getWriteLine($data): string
    {
        $csv_content = "";
        $count = count($data);
        foreach ($data as $i => $value) {
            if ($i > 0) {
                $csv_content .= ",";
            }
            $data[$i] = $this->convertCsvDoubleQuotes($value);
            if ($data[$i]) {
                if ($i >= $count -2) {
                    // module, module name 不加雙引號(維持原本的習慣)
                    $csv_content .= "{$data[$i]}";
                } else {
                    $csv_content .= "\"{$data[$i]}\"";
                }
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



    private function isMatchModuleName(string $line, $module_name): bool
    {
        $data = array_reverse(str_getcsv($line));
        if ($module_name) {
            if ($data[0] == $module_name) {
                return true;
            }
        } else if (preg_match("/,,$/", $line)) {
            return true;
        }
        return false;
    }

    private function isMatchKey(string $line, string $key): bool
    {
        $key = $this->convertCsvDoubleQuotes($key);
        return (strpos($line, "$key,") === 0 || strpos($line, "\"$key\",") === 0);
    }
}
