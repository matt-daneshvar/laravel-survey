<?php

use Faker\Generator as Faker;
use MattDaneshvar\Survey\Models\Survey;

$factory->define(Survey::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
