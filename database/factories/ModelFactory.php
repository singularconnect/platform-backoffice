<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Domains\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10))
    ];
});

$factory->define(App\Domains\Models\Role::class, function (Faker\Generator $faker) {
    $jobtitle = $faker->jobTitle();
    return [
        'id' => snake_case($jobtitle),
        'display_name' => $jobtitle,
        'description' => $faker->sentence()
    ];
});

$factory->define(App\Domains\Models\Permission::class, function (Faker\Generator $faker) {
    $perm = $faker->words(3, true);
    return [
        'id' => snake_case($perm),
        'display_name' => $perm,
        'description' => $faker->sentence()
    ];
});

//$factory->define(App\Domains\Models\Translation::class, function (Faker\Generator $faker) {
//    return [
//        'context' => rand(0,1) ? 'app' : 'plt',
//        'language' => rand(0,1) ? 'app' : 'plt',
//        'key' => $faker->sentence(),
//        'target' => $faker->sentence()
//    ];
//});
