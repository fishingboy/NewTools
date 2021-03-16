<?php

namespace WcStudio\Tool\Tests\Feature;

use NewLog;
use ApiResponse;
use ServiceResponse;
use Illuminate\Support\Facades\File;
use WcStudio\Tool\Tests\TestCase;

class CommonServiceTest extends TestCase
{

    public function testNewLog()
    {

        $filePathName = config('logging')['channels']['single']['path'];
        dump($filePathName);
        if (File::exists($filePathName)) {
            unlink($filePathName);
        }

        $this->assertFalse(File::exists($filePathName));
        NewLog::log4php(200, "Success", []);

        $this->assertTrue(File::exists($filePathName));

    }

    public function testServiceResponse()
    {
        $this->assertContains('Success', ServiceResponse::parseStatus(200, 'Success'));
        $this->assertArrayHasKey ('status', ServiceResponse::parseStatus(200, 'Success'));
        $this->assertArrayHasKey ('msg', ServiceResponse::parseStatus(200, 'Success'));
    }

    public function testApiResponse()
    {
        $testObj = ApiResponse::parseStatus(200, 'Success');
        $this->assertObjectHasAttribute('headers', $testObj);
        $this->assertObjectHasAttribute('headers', $testObj);
        $this->assertObjectHasAttribute('content', $testObj);
        $this->assertObjectHasAttribute('statusCode', $testObj);
        $this->assertIsArray($testObj->original);
        $this->assertArrayHasKey('code', $testObj->original);
        $this->assertArrayHasKey('comment', $testObj->original);
        $this->assertArrayHasKey('date', $testObj->original);
    }
}
