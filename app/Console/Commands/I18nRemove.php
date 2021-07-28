<?php

namespace App\Console\Commands;

use App\Services\I18nService;
use Exception;
use Illuminate\Console\Command;

class I18nRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:remove {i18n_key} {--site=sw} {--module=all} {--preview}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刪除 i18n key';

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
        $i18n_key = $this->argument('i18n_key');
        $site = $this->option('site');
        $module = $this->option('module');

        // 顯示執行提示
        $this->show_command_info($i18n_key, $site, $module);

        // 取得 i18n 資料
        $i18nService = new I18nService();
        try {
            $response = $i18nService->deleteKey($i18n_key, $site, $module);
        } catch (Exception $e) {
            exit($e->getMessage()."\n");
        }
    }

    /**
     * 顯示執行命令的提示
     */
    public function show_command_info($i18n_key, $site, $module)
    {
        echo "==========  i18n 佈屬工具 - 移除 key ==========\n";
        echo "\n";
        echo "[i18n_key] : {$i18n_key}\n";
        echo "[site]     : {$site}\n";
        echo "[module]   : {$module}\n";
    }
}
