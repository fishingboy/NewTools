<?php

namespace App\Console\Commands;

use App\Services\I18nService;
use Exception;
use Illuminate\Console\Command;

class I18n extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:deploy {csv_file?} {--site=sw} {--preview} {--split-line}';

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
        // @todo: 已存在的 key 只做更新
        // @todo: 處理雙引號問題
        // @todo: 畫面加一些提示

        $csv_file = $this->argument('csv_file') ?? "20210316-QuWan-i18n.csv";
        $site = $this->option('site');
        $preview = $this->option('preview');
        $split_line = $this->option('split-line');

        // 顯示執行提示
        $this->show_command_info($csv_file, $preview);

        // 取得 i18n 資料
        $i18nService = new I18nService(["split_line" => $split_line]);
        try {
            $response = $i18nService->getData($csv_file);
        } catch (Exception $e) {
            exit($e->getMessage()."\n");
        }

        $i18n = $response['i18n'];

        if ($preview) {
            // 預覽
            echo "<pre>i18n = " . print_r($i18n, true) . "</pre>\n";
        } else {
            // 寫入
            $response = $i18nService->writeFiles($i18n, $site);
            echo "\n匯入 i18n 完畢。\n";
        }
    }

    /**
     * 顯示執行命令的提示
     * @param $csv_file
     * @param $preview
     */
    public function show_command_info($csv_file, $preview)
    {
        $preview_label = $preview ? "On" : "Off";
        echo "==========  i18n 佈屬工具  ==========\n";
        echo "\n";
        echo "[csv_file]: {$csv_file}\n";
        echo "[preview]: {$preview_label}\n";
        echo "\n";
    }
}
