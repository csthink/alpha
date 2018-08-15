<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Status::class, function (Faker $faker) {
    $dataTime = $faker->date . ' ' . $faker->time;

    return [
        'content' => $faker->text(),
        'created_at' => $dataTime,
        'updated_at' => $dataTime,
    ];
});
