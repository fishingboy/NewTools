<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use WcStudio\AdminUi\Repositories\Component\AdminUiMenu;


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

$factory->define(AdminUiMenu::class, function (Faker $faker) {
    return [
        'menu_name' => $faker->text($maxNbChars = 20),
        'menu_layer' => random_int(0, 2),
        'parent_id' => $faker->randomDigit,
        'order_by' => $faker->numerify('#'),
        'status' => 'Y',
        'url' => $faker->text($maxNbChars = 200),
        'created_user' => $faker->name,
        'updated_user' => $faker->name
    ];
});
