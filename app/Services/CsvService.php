<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

/**
 * @todo: 給一個 file 做讀取/寫入/更新/查詢的動作
 * Class I18nCsvService
 * @package App\Services
 */
class CsvService
{
    public function write($file, $data)
    {
        $write_line = $this->getWriteLine($data);
        $new_line = $this->isNeedNewLine($file);
        $fp = fopen($file, "a+");
        if ($new_line) {
            fwrite($fp, "\n");
        }
        fwrite($fp, $write_line . "\n");
        fclose($fp);
    }

    /**
     * 取得要寫入 csv 的內容
     * @param $data
     * @return string
     */
    public function getWriteLine($data): string
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
    public function isNeedNewLine(string $file): bool
    {
        $content = file_get_contents($file);
        $len = strlen($content);
        if ($len == 0) {
            return false;
        }
        return ! ($content[$len - 1] == "\n");
    }
}
