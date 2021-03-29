<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class I18nService
{
    private $split_line;

    public function __construct($params = [])
    {
        $this->split_line = $params['split_line'] ?? false;
    }
    
    /**
     * 取得 csv 檔案 raw data
     * @param string $csv_file
     * @return string
     */
    public function getCsv(string $csv_file): string
    {
        return Storage::get($csv_file);
    }

    /**
     * 取得 csv 資料
     * @param string $csv_file
     * @return array[]
     */
    public function getData(string $csv_file): array
    {
        if (strpos($csv_file, "/") !== false) {
            // 絕對路徑
            $fp = fopen($csv_file, "r");
        } else {
            // 相對路徑
            $fp = fopen("storage/app/" . $csv_file, "r");
        }
        $raw_data = [];
        $i18n = [];
        $line_no = 0;
        while ($line = fgetcsv($fp)) {
            $line_no++;
            if ($line_no == 2) {
                $code = $line;
            }

            $line = $this->trimArray($line);

            if ($line_no > 2) {
                if ( ! $this->split_line) {
                    $i18n_row = [];
                    foreach ($line as $i => $item) {
                        $i18n_code = $code[$i];
                        if ($i18n_code) {
                            $i18n_row[$i18n_code] = $item;
                        }
                    }
                    $i18n[] = $i18n_row;
                } else {
                    $i18n_tmp = [];
                    foreach ($line as $i => $item) {
                        $i18n_code = $code[$i];
                        $tmp = explode("\n", $item);
                        foreach ($tmp as $j => $split_item) {
                            $i18n_tmp[$j][$i18n_code] = trim($split_item);
                        }
                    }
                    $i18n = array_merge($i18n, $i18n_tmp);
                }
            }

            $raw_data[] = $line;
        }
        return [
            'raw_data' => $raw_data,
            'i18n' => $i18n,
        ];
    }

    /**
     * trim 陣列
     * @param array $items
     * @return array
     * @throws Exception
     */
    public function trimArray(array $items): array
    {
        foreach ($items as $i=> $item) {
            $item = trim($item);
            if (strpos($item, '"') !== false) {
                throw new Exception("字串包含雙引號，要額外處理");
            }
            if ( ! $this->split_line && $this->isHaveNewLine($item)) {
                throw new Exception("[$item] 字詞包含換行字元，要額外處理。");
            }
            $items[$i] = $item;
            $items[$i] = str_replace("<br>", "", $items[$i]);
        }
        return $items;
    }

    /**
     * 取得 i18n 檔案位置
     * @param string $i18n_code
     * @param string $env
     * @return string
     */
    public function getFilePath(string $i18n_code, $env = "sw"): string
    {
        $sw_path = "/mnt/c/Users/Leo Kuo/Code/software-store";

        $csv_file_name = $this->getCsvFileName($i18n_code);
        $file = "$sw_path/app/i18n/Mageplaza/{$i18n_code}/{$csv_file_name}";
        if (file_exists($file)) {
            return $file;
        }

        $file = "$sw_path/app/i18n/eadesigndev/{$i18n_code}/ro_RO.csv";
        if (file_exists($file)) {
            return $file;
        }

        return "";
    }

    /**
     * 取得 i18n 檔案位置
     * @param string $i18n_code
     * @param string $env
     * @return string
     */
    public function getOriginFilePath(string $i18n_code, $env = "sw"): string
    {
        $sw_path = "/mnt/c/Users/Leo Kuo/Code/software-store";
        $file = "$sw_path/app/i18n/Mageplaza/{$i18n_code}/github_contributions.csv";
        if (file_exists($file)) {
            return $file;
        }
        return "";
    }

    /**
     * 取得要寫入 csv 的內容
     * @param $i18n
     * @param $code
     * @return string
     */
    public function getWriteLine($i18n, $code): string
    {
        return "\"{$i18n['en_us']}\",\"{$i18n[$code]}\",,";
    }

    /**
     * 寫入 i18n 檔案
     * @todo: 看測試要怎麼寫才不會真的去寫檔案
     * @param array $i18n_array
     * @param string $env
     * @return bool
     */
    public function writeFiles(array $i18n_array, string $env): bool
    {
        foreach ($i18n_array as $i18n) {
            $i18n_key = $i18n['en_us'];
            echo "i18n key : `$i18n_key`\n\n";
            foreach ($i18n as $code => $lang) {
                if ($code == "en_us") {
                    continue;
                }

                $file = $this->getFilePath($code, $env);
                if ( ! $file) {
                    echo "找不到檔案 [{$file}]，不需寫入!!\n";
                    continue;
                }

                if ( ! $this->isNeedWriteFile($file, $i18n_key)) {
                    echo "i18n_key 已存在 [{$code}]::[$file]，不需寫入!!\n";
                    continue;
                }

                $origin_file = $this->getOriginFilePath($code, $env);
                if ($origin_file && ! $this->isNeedWriteFile($origin_file, $i18n_key)) {
                    echo "i18n_key 已存在 [{$code}]::[$origin_file]，不需寫入!!\n";
                    continue;
                }

                $write_line = $this->getWriteLine($i18n, $code);
                $new_line = $this->isNeedNewLine($file);

                $fp = fopen($file, "a+");
                if ($new_line) {
                    fwrite($fp, "\n");
                }
                fwrite($fp, $write_line . "\n");
                fclose($fp);

                echo "i18n_key 寫入 [{$code}] 完成!!\n";
            }
        }
        return true;
    }

    /**
     * 取得要寫入的 csv data
     * @param $i18n
     * @param string $code
     * @return array
     */
    public function getWriteRow($i18n, string $code): array
    {
        $row[] = $i18n['en_us'];
        $row[] = $i18n[$code];
        $row[] = "";
        $row[] = "";
        return $row;
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
        return ! ($content[$len - 1] == "\n");
    }

    /**
     * 是不是需要寫入檔案
     * @param $file
     * @param string $i18n_key
     * @return bool
     */
    public function isNeedWriteFile($file, string $i18n_key): bool
    {
        $content = file_get_contents($file);
        return (strpos($content, "\"$i18n_key\",") === false);
    }

    /**
     * 看看有沒有換行
     * @param string $phrase
     * @return bool
     */
    public function isHaveNewLine(string $phrase): bool
    {
        return strpos($phrase, "\n") !== false;
    }

    /**
     * 從 i18n code 取得 csv 檔名
     * @param string $i18n_code
     * @return string
     */
    public function getCsvFileName(string $i18n_code): string
    {
        if (strlen($i18n_code) == 5) {
            return substr($i18n_code, 0 , 3) . strtoupper(substr($i18n_code, 3, 2)) . ".csv";
        } else if ($i18n_code == "zh_hans_cn") {
            return "zh_Hans_CN.csv";
        } else if ($i18n_code == "zh_hant_tw") {
            return "zh_Hant_TW.csv";
        }
        return "";
    }
}
