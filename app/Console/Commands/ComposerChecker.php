<?php

namespace App\Console\Commands;

use App\Services\I18nService;
use Exception;
use Illuminate\Console\Command;

class ComposerChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composer:check';

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
        $path = "/mnt/c/Users/Leo Kuo/Code/eshop-tw";
        exec("find $path", $output, $result_code);

        echo $output;
//        echo "<pre>output = " . print_r($output, true) . "</pre>\n";

    }
}
