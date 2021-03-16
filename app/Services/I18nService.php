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


}
