<?php

namespace App\Console\Commands;

use App\Services\I18nService;
use Illuminate\Console\Command;

class I18n extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // @todo: csv 檔名改為傳入
        // @todo: 已存在的 key 只做更新
        // @todo: 處理雙引號問題
        // @todo: 畫面加一些提示

        $csv_file = "20210316-QuWan-i18n.csv";
        $response = I18nService::getData($csv_file);
        $i18n = $response['i18n'];

        $env = "sw";
        $response = I18nService::writeFiles($i18n, $env);

        echo "匯入 i18n 完畢。\n";
    }
}
