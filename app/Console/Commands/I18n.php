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
        $csv_file = "20210316-QuWan-i18n.csv";
        $data = I18nService::getData($csv_file);
        echo "<pre>data = " . print_r($data, true) . "</pre>\n";
    }
}
