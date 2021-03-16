<?php

namespace WcStudio\Tool;

use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 擴充 validator，使其可同時支援檢查多種 date format
         */
        \Illuminate\Support\Facades\Validator::extend('date_multi_format', function($attribute, $value, $formats)
        {
            // iterate through all formats
            foreach ($formats as $format) {

                // parse date with current format
                $parsed = date_parse_from_format($format, $value);

                // if value matches given format return true=validation succeeded
                if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0) {
                    return true;
                }
            }

            // value did not match any of the provided formats, so return false=validation failed
            return false;
        });

        /**
         * 將 array轉成 collection object
         */
        \Illuminate\Support\Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });

        /**
         * 將collection 依 key 找出 value，回傳array
         */
        \Illuminate\Support\Collection::macro('mapByKey', function ($item,$key) {
            if(is_array($item)){
                $item = collect($item)->recursive();
            }
            return $this->map(function (&$rule) use($item,$key){
                $new_rule = preg_replace('/(.*)\.([^.]*)$/','$1.'.$key, $rule);
                if($new_rule === $rule){
                    $new_rule = $key;
                }
                $rule = $item->pluck($new_rule)->flatten()->filter()->first();
                return $rule ;
            });
        });


        /**
         * 將 string 階層，轉換成 array 階層
         * eg return_line.0.check_sn => [return_line=>[0=>[check_sn]]]
         * @param $message
         * @return mixed
         */
        \Illuminate\Support\Arr::macro('explore_validate_message', function ($message) {
            $result = [];
            foreach ($message ?? [] as $path => $value) {
                $temp = &$result;
                $count = 0;
                $arr = explode('.', $path);
                foreach ($arr as $key) {
                    $count++;
                    if ($count === count($arr)) {
                        $key = 'msg';
                    }
                    $temp =&$temp[$key];
                }
                if (is_array($value) && !empty($value)) {
                    $value = $value[0];
                }
                $temp = $value;
            }
            return $result;
        });
    }
}
