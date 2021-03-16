<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class I18nService
{
    public static function getCsv(string $csv_file): string
    {
        return Storage::get($csv_file);
    }

    public static function getData(string $csv_file)
    {
        $fp = fopen("storage/app/" . $csv_file, "r");
        $raw_data = [];
        $i18n = [];
        $line_no = 0;
        while ($line = fgetcsv($fp)) {
            $line_no++;
            if ($line_no == 2) {
                $code = $line;
            }

            $line = self::trimArray($line);

            if ($line_no > 2) {
                $i18n_row = [];
                foreach ($line as $i => $item) {
                    $i18n_code = $code[$i];
                    if ($i18n_code) {
                        $i18n_row[$i18n_code] = $item;
                    }
                }
                $i18n[] = $i18n_row;
            }

            $raw_data[] = $line;
        }
        return [
            'raw_data' => $raw_data,
            'i18n' => $i18n,
        ];
    }

    public static function trimArray(array $items): array
    {
        foreach ($items as $i=> $item) {
            $items[$i] = trim($item);
        }
        return $items;
    }

    public static function getFilePath(string $i18n_code, $env = "sw"): string
    {
        $sw_path = "/mnt/c/Users/Leo Kuo/Code/software-store";

        $file = "$sw_path/app/i18n/Mageplaza/{$i18n_code}/github_contributions.csv";
        if (file_exists($file)) {
            return $file;
        }

        $file = "$sw_path/app/i18n/eadesigndev/{$i18n_code}/ro_RO.csv";
        if (file_exists($file)) {
            return $file;
        }

        return "";
    }

    public static function getWriteLine($i18n, $code): string
    {
        return "\"{$i18n['en_us']}\",\"{$i18n[$code]}\",,";
    }

    public static function writeFiles(array $i18n_array, string $env)
    {
        foreach ($i18n_array as $i18n) {
            foreach ($i18n as $code => $lang) {
                if ($code != "en_us") {
                    $file = self::getFilePath($code, $env);
                    $write_line = self::getWriteLine($i18n, $code);

                    $new_line = self::isNeedNewLine($file);
                    if ($file) {
                        $fp = fopen($file, "a+");
                        if ($new_line) {
                            fwrite($fp, "\n");
                        }
                        fwrite($fp, $write_line . "\n");
                        fclose($fp);
                    }
                }
            }
        }
        return true;
    }

    public static function getWriteRow($i18n, string $code): array
    {
        $row[] = $i18n['en_us'];
        $row[] = $i18n[$code];
        $row[] = "";
        $row[] = "";
        return $row;
    }

    public static function isNeedNewLine(string $file): bool
    {
        $content = file_get_contents($file);
        $len = strlen($content);
        return ! ($content[$len - 1] == "\n");
    }


}
