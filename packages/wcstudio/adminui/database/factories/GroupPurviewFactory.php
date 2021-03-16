<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use WcStudio\AdminUi\Repositories\Purview\AdminUiGroup;
use WcStudio\AdminUi\Repositories\Purview\AdminUiGroupPurview;

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

$factory->define(\WcStudio\AdminUi\Repositories\Purview\AdminUiGroup::class, function (Faker $faker) {
    return [
        'group_name' => 'unit_test',
        'status' => 'Y',
        'created_user' => 'unit_test',
        'updated_user' => 'unit_test'
    ];
});

$factory->define(\WcStudio\AdminUi\Repositories\Purview\AdminUiGroupPurview::class, function (Faker $faker) {
    return [
        'group_id' => 2,
        'menu_id' => 9999,
        'created_user' => 'UnitTest',
        'updated_user' => 'UnitTest',
        'status' => 'Y',
    ];
});
