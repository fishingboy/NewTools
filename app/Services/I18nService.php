<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class I18nService
{
    const SITE_SW = "sw";
    const SITE_EU = "eu";
    const SITE_TW = "tw";
    const SITE_US = "us";

    private $split_line;

    private $csvService;

    public function __construct($params = [])
    {
        $this->split_line = $params['split_line'] ?? false;
        $this->csvService = new CsvService();
    }

    public function set_csv_service(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }

    /**
     * 取得 csv 檔案 raw data
     * @param string $csv_file
     * @return string
     */
    public function getCsv(string $csv_file): string
    {
//        return Storage::get($csv_file);
        return Storage::get("i18n/{$csv_file}");
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
            $fp = fopen(storage_path("app/i18n/{$csv_file}"), "r");
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
                        if ($i18n_code && $item) {
                            $i18n_row[$i18n_code] = $item;
                        }
                    }
                    $i18n[] = $i18n_row;
                } else {
                    $i18n_tmp = [];
                    foreach ($line as $i => $item) {
                        $i18n_code = $code[$i];
                        if ($i18n_code && $item) {
                            $tmp = explode("\n", $item);
                            foreach ($tmp as $j => $split_item) {
                                $i18n_tmp[$j][$i18n_code] = trim($split_item);
                            }
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
//            if (strpos($item, '"') !== false) {
//                throw new Exception("字串包含雙引號，要額外處理");
//            }
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
     * @param string $site
     * @return string
     */
    public function getFilePath(string $i18n_code, $site = "sw"): string
    {
        $path = $this->getMagentoPath($site);

        $csv_file_name = $this->getCsvFileName($i18n_code);
        $file = "$path/app/i18n/Mageplaza/{$i18n_code}/{$csv_file_name}";
        if ($this->isFileExists($file)) {
            return $file;
        }

        $file = "$path/app/i18n/eadesigndev/{$i18n_code}/ro_RO.csv";
        if ($this->isFileExists($file)) {
            return $file;
        }

        return "";
    }

    /**
     * 取得 i18n 檔案位置
     * @param string $i18n_code
     * @param string $site
     * @return string
     */
    public function getOriginFilePath(string $i18n_code, $site = "sw"): string
    {
        $path = $this->getMagentoPath($site);
        $file = "{$path}/app/i18n/Mageplaza/{$i18n_code}/github_contributions.csv";
        if ($this->isFileExists($file)) {
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
        $i18n_key = $this->convertCsvDoubleQuotes($i18n['i18n_key']);
        $i18n = $this->convertCsvDoubleQuotes($i18n[$code]);
        return "\"{$i18n_key}\",\"{$i18n}\",,";
    }

    /**
     * 取得要寫入 csv 的內容
     * @param $i18n
     * @param $code
     * @return array
     */
    public function getWriteData($i18n, $code): array
    {
        $module = $i18n['Module'] ?? "";
        $module_name = $i18n['Module Name'] ?? "";
        return [
            $i18n['i18n_key'],
            $i18n[$code],
            $module,
            $module_name,
        ];
    }

    private function convertCsvDoubleQuotes($str)
    {
        return str_replace('"', '""', $str);
    }

    /**
     * 寫入 i18n 檔案
     * @param array $i18n_array
     * @param string $site
     * @return bool
     * @todo: 看測試要怎麼寫才不會真的去寫檔案
     */
    public function writeFiles(array $i18n_array, string $site): bool
    {
        foreach ($i18n_array as $i18n) {
            $i18n_key = $i18n['i18n_key'];
            $module_name = $i18n['Module Name'] ?? "";
            echo "\ni18n key : `$i18n_key`, module_name=`$module_name`\n";
            foreach ($i18n as $code => $lang) {
                if (in_array($code, ["i18n_key", 'Module', "Module Name"])) {
                    continue;
                }

                // 檢查檔案存不存在
                $file = $this->getFilePath($code, $site);
                if ( ! $file) {
                    echo "[site=$site]::[code=$code] 找不到檔案，不需寫入!!\n";
                    continue;
                }

                if ( ! $this->isNeedWriteFile($file, $i18n_key, $module_name)) {
                    if ( ! $this->isNeedUpdateFile($file, $i18n_key, $lang, $module_name)) {
                        echo "i18n_key 已存在 [{$code}]::[$file]，且內容相同，不需寫入及更新!!\n";
                        continue;
                    } else {
                        // 寫入 csv
                        $status = $this->csvService->update($file, $this->getWriteData($i18n, $code), $module_name);
                        if ($status) {
                            echo "i18n_key [{$code}]::[$file] 更新成功!!\n";
                        } else {
                            exit("i18n_key [{$code}]::[$file] 更新失敗!!\n");
                        }
                        continue;
                    }
                }


                $origin_file = $this->getOriginFilePath($code, $site);
                if ($origin_file && ! $this->isNeedWriteFile($origin_file, $i18n_key)) {
                    echo "i18n_key 已存在 [{$code}]::[$origin_file]，不需寫入!!\n";
                    continue;
                }

                // 寫入 csv
                $status = $this->csvService->write($file, $this->getWriteData($i18n, $code));
                if ( ! $status) {
                    exit("i18n_key 寫入 [{$code}] 失敗!!\n");
                }
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
        $row[] = $i18n['i18n_key'];
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
     * @param string $module_name
     * @return bool
     */
    public function isNeedWriteFile($file, string $i18n_key, string $module_name = ""): bool
    {
        $csvService = new CsvService();
        return ! $csvService->searchKey($file, $i18n_key, $module_name);
    }

    /**
     * 是不是需要更新檔案
     * @param $file
     * @param string $i18n_key
     * @param string $i18n_value
     * @return bool
     */
    public function isNeedUpdateFile($file, string $i18n_key, string $i18n_value, $module_name = ""): bool
    {
        $csvService = new CsvService();
        $i18n = $csvService->searchKey($file, $i18n_key, $module_name);
        return ($i18n[1] != $i18n_value);
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

    protected function isFileExists($file)
    {
        return file_exists($file);
    }

    public function getMagentoPath(string $site): string
    {
        switch ($site) {
            case self::SITE_EU:
                return "/mnt/c/Users/Leo Kuo/Code/eshop-eu";
            case self::SITE_TW:
                return "/mnt/c/Users/Leo Kuo/Code/eshop-tw";
            case self::SITE_US:
                return "/mnt/c/Users/Leo Kuo/Code/eshop-us";
            case self::SITE_SW:
            default:
                return "/mnt/c/Users/Leo Kuo/Code/software-store";
        }
    }

    /**
     * 刪除 i18n key
     * @param string $i18n_key
     * @param string $site
     * @param string $module
     * @return bool
     */
    public function deleteKey(string $i18n_key, string $site, string $module = "all"): bool
    {
        $codes = $this->getI18nCodes($site);
        foreach ($codes as $code) {
            $file = $this->getFilePath($code, $site);
            $origin_file = $this->getOriginFilePath($code, $site);
            $this->csvService->deleteKey($file, $i18n_key, $module);
            $this->csvService->deleteKey($origin_file, $i18n_key, $module);
        }
        return true;
    }

    /**
     * 取得電商站的 i18n code
     * @param string $site
     * @return array
     */
    public function getI18nCodes(string $site): array
    {
        switch ($site) {
            case self::SITE_SW:
                return [
                    "cs_cz",
                    "da_dk",
                    "de_de",
                    "el_gr",
                    "en_us",
                    "es_es",
                    "fi_fi",
                    "fr_fr",
                    "hu_hu",
                    "it_it",
                    "ja_jp",
                    "ko_kr",
                    "nb_no",
                    "nl_nl",
                    "pl_pl",
                    "pt_br",
                    "ru_ru",
                    "sv_se",
                    "th_th",
                    "tr_tr",
                    "zh_hans_cn",
                    "zh_hant_tw",
                    "ro_ro",
                ];

            case self::SITE_EU:
                return [
                    "cs_cz",
                    "da_dk",
                    "de_de",
                    "es_es",
                    "fi_fi",
                    "fr_fr",
                    "hu_hu",
                    "it_it",
                    "nl_nl",
                    "pl_pl",
                    "pt_br",
                    "sv_se",
                    "ro_ro",
                ];
        }
    }
}
