<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use WcStudio\AdminUi\Repositories\AdminUiPage;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/



$factory->define(AdminUiPage::class, function (Faker $faker) {
    return
    [
        'region_code' => "Eu",
        'country_code' => "ca_fr",
        'name' => 'test',
        'type' => 'logistic',
        'description' => 'testing',
        'table_class' => 'WcStudio\AdminUi\Entities\ReportSample',
    ];
});

$factory->define(\WcStudio\AdminUi\Repositories\Report\AdminUiPageConfig::class, function (Faker $faker) {
    $column_name = ['purchase_date', 'purchase_dn', 'sku', 'purchase_qty', 'inbound_fee'];
    $select_colum = $faker->randomElement($column_name);
    $pick_start = "moment().subtract(1, 'month').startOf('month')";
    $pick_end = "moment().subtract(1, 'month').endOf('month')";
    $months = $faker->date('M');
    $day = $faker->date('d');
    return
    [
        'page_name' => 'eu-logistic-sample',
        'select_columns' => json_encode($column_name),
        'date_pick_start' => $pick_start,
        'date_pick_end' => $pick_end,
        'date_pick_range' => json_encode(['months'=>$months, 'days' => $day]),
        'date_pick_field' => json_encode([$select_colum=>0]),
        'custom_table_func' => 'GetTableData',
        'footer_arr' => json_encode(array('Total Lines', 'Total Qty', 'Total Fee')),
        'footer_func' => 'statementInboundCal',
        'is_export_excel' => false,
        'invisible_columns' => json_encode([]),
    ];
});

$factory->define(\WcStudio\AdminUi\Repositories\ReportSample::class, function(Faker $faker){

    return
        [
            'region_code' => "Eu",
            'country_code' => "ca_fr",
            'name' => 'test',
            'type' => 'logistic',
            'description' => 'testing',
            'table_class' => 'WcStudio\AdminUi\Entities\ReportSample',
        ];
});

