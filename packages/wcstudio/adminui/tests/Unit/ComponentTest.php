<?php


namespace WcStudio\AdminUi\Tests\Unit;


use WcStudio\AdminUi\Repositories\Report\AdminUiMain;
use WcStudio\AdminUi\Repositories\ReportSample;
use WcStudio\AdminUi\Http\Controllers\Component\DatePick;
use WcStudio\AdminUi\Http\Controllers\Component\AdminMenu;
use WcStudio\AdminUi\Http\Controllers\Component\TableListComponent;
use WcStudio\AdminUi\Tests\TestCase;
use AsusMw\WebsiteConfig\Entities\PaymentMethod;
use AsusMw\WebsiteConfig\Entities\WebsiteConfig;
use AsusMw\WebsiteConfig\Entities\WebsiteConfigShow;
use AsusMw\WebsiteConfig\Entities\WebsitePaymentRel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;

class ComponentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function tableList()
    {

        //load header = false 時 會去吃資料
        $config = [
            'columns' => '',
            'name' => 'WcStudio\AdminUi\Entities\ReportSample',
            'loadHeader' => false,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
            'filter_columns' => [
                'id' => '11111',
                'created_date' => date("Y/m/d"),
             ],
        ];
        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();


        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('tablename', $tbl->filter_condition);
        $this->assertArrayHasKey('show_columns', $tbl->filter_condition);
        $this->assertArrayHasKey('dateColumn', $tbl->filter_condition);
        $this->assertArrayHasKey('start', $tbl->filter_condition);
        $this->assertArrayHasKey('end', $tbl->filter_condition);
        $this->assertArrayHasKey('id', $tbl->filter_condition);
        $this->assertArrayHasKey('created_date', $tbl->filter_condition);

        //沒有帶 filter_columns 時 會去吃資料
        $config = [
            'columns' => '',
            'name' => 'WcStudio\AdminUi\Entities\ReportSample',
            'loadHeader' => false,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
        ];
        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();


        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('tablename', $tbl->filter_condition);
        $this->assertArrayHasKey('show_columns', $tbl->filter_condition);
        $this->assertArrayHasKey('dateColumn', $tbl->filter_condition);
        $this->assertArrayHasKey('start', $tbl->filter_condition);
        $this->assertArrayHasKey('end', $tbl->filter_condition);
        $this->assertArrayNotHasKey('id', $tbl->filter_condition);
        $this->assertArrayNotHasKey('created_date', $tbl->filter_condition);


        //load header = true 時
        $config = [
            'columns' => '',
            'name' => 'WcStudio\AdminUi\Entities\ReportSample',
            'loadHeader' => true,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
            'filter_columns' => [
                'id' => '11111',
                'created_date' => date("Y/m/d"),
            ],
        ];
        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();


        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('tablename', $tbl->filter_condition);
        $this->assertArrayNotHasKey('order_id', $tbl->filter_condition);
        $this->assertArrayNotHasKey('created_date',$tbl->filter_condition);

        //tablename  為空時
        $config = [
            'columns' => '',
            'name' => '',
            'loadHeader' => true,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
        ];
        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();


        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('tablename', $tbl->filter_condition);
        $this->assertArrayNotHasKey('order_id', $tbl->filter_condition);
        $this->assertArrayNotHasKey('created_date',$tbl->filter_condition);



    }

    /**
     * @test
     */
    public function menuComponent()
    {
        $config = ['user' => "john@example.com"];
        $tbl = new AdminMenu();

        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();
        $this->assertIsArray($result);

    }

    /**
     * @test
     */
    public function tableListCountry()
    {
        $website = WebsiteConfig::create([
            'wc_type' => 'COMMON',
            'wc_language' => 'zh_TW',
            'wc_store_code' => 'tw',
            'wc_store_view_official' => 'tw',
            'wc_store_view_rog' => 'tw',
            'wc_country_code' => 'tw',
            'wc_website_id' => '1',
            'wc_region_code' => 'asia',
            'wc_edi_path' => '',
        ]);

        WebsiteConfigShow::create([
            'wc_id' => $website['wc_id'],
            'wcs_language' => 'zh_TW',
            'wcs_website_id' => '1',
            'wcs_timezone' => 'Asia/Taipei',
            'wcs_weight_unit' => 'kg',
            'wcs_size_unit' => 'cm',
            'wcs_date_format' => 'Y/m/d',
            'wcs_time_format' => 'H:i:s',
            'wcs_tran_currency' => 'TWD',
            'wcs_tran_currency_symbol' => 'NT$',
            'wcs_tran_comma_symbol' => ',',
            'wcs_tran_decimal_symbol' => '.',
            'wcs_tran_decimal_place' => 2,
            'wcs_tran_calculate_digit' => 2,
            'wcs_bill_currency' => 'TWD',
            'wcs_bill_currency_symbol' => 'NT$',
            'wcs_bill_comma_symbol' => ',',
            'wcs_bill_decimal_symbol' => '.',
            'wcs_bill_decimal_place' => 0,
            'wcs_bill_calculate_digit' => 2,

        ]);

        factory(ReportSample::class)->create();

        $config = [
            'columns' => '',
            'name' => 'WcStudio\AdminUi\Entities\ReportSample',
            'loadHeader' => false,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
            'filter_columns' => [
                'id' => '11111',
                'created_date' => date("Y/m/d"),
            ],
            'country_code' => 'tw',
            'transform_currency' => ['report_id'],
        ];

        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);

        $result = $tbl->componentHandle();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
        $this->assertStringContainsString('.00', $result['data'][0]['report_id']);

        //不進行 format, col 不傳
        $config = [
            'columns' => '',
            'name' => 'WcStudio\AdminUi\Entities\ReportSample',
            'loadHeader' => false,
            'customFunction' => "GetTableData",
            'footerArr' => '["Total"]',
            'footerFunc' => "statementPickpackCal",
            'isExportExcel' => 1,
            'invisible_columns' => '{"delivery_attempt_date":27,"delivery_attempt_time":28,"outbound_date":37,"length":38,"width":39,"height":40}',
            'excelName' => "Pick Pack fee",
            'orderSet' => '[[3,"asc"],[2,"desc"],[4,"asc"]]',
            'start' => 0,
            'end' => 10,
            'filter_columns' => [
                'id' => '11111',
                'created_date' => date("Y/m/d"),
            ],
            'country_code' => 'tw',
            'transform_currency' => [],
        ];

        $tbl = new TableListComponent();
        $tbl->setComponentConfig($config);

        $result = $tbl->componentHandle();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
        $this->assertStringNotContainsString('.00', $result['data'][0]['report_id']);
    }

    /**
     * @test
     */
    public function datePicker()
    {
        $config = [
            'start' => 0,
            'end' => 10,
            'daterange' => '',
            'pickfield' => '',
        ];
        $tbl = new DatePick();
        $tbl->setComponentConfig($config);
        $result = $tbl->componentHandle();
        $this->assertIsArray($result);
        $this->assertEquals(10, $result['end']);

    }
}
