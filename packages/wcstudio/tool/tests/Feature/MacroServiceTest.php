<?php

namespace WcStudio\Tool\Tests\Feature;

use ApiResponse;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use NewLog;
use ServiceResponse;
use Illuminate\Support\Facades\Validator;
use WcStudio\Tool\Tests\TestCase;

class MacroServiceTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function recursive()
    {
        $arr = [
            'order_id' => '123',
            'order_line' => [
                'line_id' => '444',
                'org' => 'asus',
                'return_item' => [
                    'return_id' => 'R-111',
                    'return_line_id' => '33',
                    'desc' => 'return RRR'
                ]
            ]
        ];
        $col_recur = collect($arr)->recursive();
        $col = collect($arr);
        $this->assertInstanceOf(Collection::class, $col_recur);
        $this->assertInstanceOf(Collection::class, $col);
        $this->assertInstanceOf(Collection::class, $col_recur['order_line']);
        $this->assertIsArray($col['order_line']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function mapByKey()
    {
        $json_rule = '{"return_date":"required","check_line.*.check_date":"required","check_line.*.condition":"required|in:unopened box,opened box,sn mismatch,unopened box damaged"}  ';
        $json = '{"1":{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","currency":"DKK","store":"da_DK","billing_currency":"USD","exchange_rate":0.145001,"return_date":"2020-08-03 15:43:24","return_line":[{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","order_line_id":"6304","product_type":"PHYSICAL","product_name":"ASUS ZenPower","sku":"90AC00P0-BBT079","reason":"Others","check_sn":"-2-2-12343212227","condition":"unopened box","check_date":"2020-08-03 21:43:24","qty":"1","original_price":"19.99","discount_amount":"0.00","final_price":"19.99","tax_amount":"4.00","billing_qty":1,"billing_original_price":2.32,"billing_discount_amount":0,"billing_final_price":2.17,"billing_tax_amount":0.58,"order_sku_seq":"1"},{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","order_line_id":"6310","product_type":"PHYSICAL","product_name":"ASUS Professional Dock","sku":"90AC0360-BDS002","reason":"Others","check_sn":"-2-2-12343212228,-2-2-12343212229","condition":"unopened box","check_date":"2020-08-03 21:43:24","qty":"2","original_price":"1141.20","discount_amount":"0.00","final_price":"1141.20","tax_amount":"228.24","billing_qty":2,"billing_original_price":132.38,"billing_discount_amount":0,"billing_final_price":123.94,"billing_tax_amount":33.09,"order_sku_seq":"2"}],"check_line":[{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","return_line_number":"1","order_line_id":"6304","reason":"Others","check_sn":"-2-2-12343212227","condition":"unopened box","check_date":"2020-08-03 21:43:24","order_sku_seq":"1"},{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","return_line_number":"2","order_line_id":"6310","reason":"Others","check_sn":"-2-2-12343212228","condition":null,"check_date":"2020-08-03 21:43:24","order_sku_seq":"2","msg":"Error Message : Condition data is empty"},{"return_id":"RMA-CS-28000000043-1","order_id":"28000000043","return_line_number":"3","order_line_id":"6310","reason":"Others","check_sn":"-2-2-12343212229","condition":"unopened box","check_date":"2020-08-03 21:43:24","order_sku_seq":"2"}]}}';
        $rule = json_decode($json_rule, true);
        $item = collect(json_decode($json, true));
//        dump('item:'.print_r($item,1));
        $err_msg_list = collect($rule)->keys()->mapByKey($item, 'msg')->filter();
//        dump('err_msg_list.filter:'.print_r($err_msg_list,1));
        $this->assertCount(2, $err_msg_list);
    }

    /**
     * @test
     * @throws Exception
     */
    public function explore_validate_message()
    {
        $message = [
            'return_line.0.check_sn' => 'hello'
        ];
        $explore = Arr::explore_validate_message($message);
        $this->assertIsArray($explore);
        $this->assertArrayHasKey('return_line', $explore);
        $this->assertEquals('hello', $explore['return_line'][0]['msg']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function date_multi_format()
    {
        $success_validator = Validator::make(['stockin_date'=>'2020-11-26 05:25:25'], [
            'stockin_date' => 'date_multi_format:"Y/m/d H:i:s","Y/m/d"'
        ]);
        $this->assertTrue($success_validator->fails());

        $fail_validator = Validator::make(['stockin_date'=>'2020/11/26 05:25:25'], [
            'stockin_date' => 'date_multi_format:"Y/m/d H:i:s","Y/m/d"'
        ]);
        $this->assertFalse($fail_validator->fails());

    }
}
