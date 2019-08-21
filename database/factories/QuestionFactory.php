<?php

use Faker\Generator as Faker;
use MattDaneshvar\Survey\Models\Question;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'content' => $faker->name,
    ];
});
